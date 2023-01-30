<?php
session_start();
include_once("../mysql.php");
include_once("../lib/mylib/search_new.php");

function getCourseID($course_name) {
   global $connection;

   if ($course_name!="") {
      $sql="SELECT * from course where course_name like '$course_name%'";

      $result=mysqli_query($connection, $sql);
      $row=mysqli_fetch_assoc($result);
      return $row["id"];
   } else {
      return "";
   }
}

function isDeleted($allocation_id) {
   global $connection;

   $sql="SELECT * from allocation where id='$allocation_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   if ($row["deleted"]==0) {
      $student_id=$row["student_id"];
      $course_id=$row["course_id"];
      $class_id=$row["class_id"];

      if (isStudentDeleted($student_id) || isCourseDeleted($course_id) || (isClassDeleted($class_id))) {
         return true;
      } else {
         return false;
      }
   } else {
      return true;
   }
}

function isStudentDeleted($student_id) {
   global $connection;

   $sql="SELECT deleted from student where id='$student_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   if ($row["deleted"]==1) {
      return true;
   } else {
      return false;
   }
}

function isCourseDeleted($course_id) {
   global $connection;

   $sql="SELECT deleted from course where id='$course_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   if ($row["deleted"]==1) {
      return true;
   } else {
      return false;
   }
}

function isClassDeleted($class_id) {
   global $connection;

   $sql="SELECT deleted from class where id='$class_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   if ($row["deleted"]==1) {
      return false;
   } else {
      return false;
   }
}

function getGroupID($course_id, $group) {
   global $connection, $year;

   $sql="SELECT * from `group` where course_id='$course_id' and class_id='$group'";

   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      return $row["id"];
   } else {
      return 0;
   }
}

foreach ($_REQUEST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //programme, level, module, group, status, sname
}

$course_name=$programme.$level.$module;
$course_id=getCourseID($course_name);
$group_id=getGroupID($course_id, $group);
$year=$_SESSION['Year'];
$centre_code=$_SESSION["CentreCode"];


//if ($group_id!="") {
  // $base_sql="SELECT a.student_id, s.student_code, s.start_date_at_centre, s.nric_no, s.race,
  // s.religion, a.id, a.course_id, a.class_id, s.student_code, s.name, co.course_name, a.class_id from student s, course co,
  // allocation a, `group` g where a.student_id=s.id and s.student_status = 'A' and a.course_id=co.id and a.year='$year' and s.centre_code='$centre_code'
 //  and s.deleted=0  and a.deleted=0 and g.id=a.group_id and g.class_id='$group' and g.id='$group_id'";
//} else {
   // $base_sql="SELECT a.student_id, s.student_code, s.start_date_at_centre, s.nric_no, s.race,
   // s.religion, a.id, a.course_id, a.class_id, s.student_code, s.name, co.course_name, a.class_id from student s, course co,
   // allocation a, `group` g where a.student_id=s.id and a.course_id=co.id and a.year='$year' and s.centre_code='$centre_code'
   // and s.deleted=0  and a.deleted=0 and g.id=a.group_id ";
//}

$year=$_SESSION['Year'];

//$base_sql="SELECT s.id, s.student_code, s.start_date_at_centre, s.nric_no, s.race, s.religion,  s.student_code, s.name from student s, course co where s. centre_code='$centre_code' and s.`student_status`='A' and s.deleted=0 and year(s.start_date_at_centre) ='$year' ";
//$base_sql="SELECT s.id, s.student_code, s.start_date_at_centre, s.nric_no, s.race, s.religion,  s.student_code, s.name from student s, course co where s. centre_code='$centre_code' and s.`student_status`='A' and s.deleted=0 and year(start_date_at_centre) <= '$year' and extend_year >= '$year' ";

$where = '';

if(isset($_REQUEST['age']) && $_REQUEST['age'] != '')
{
   $where .= " and (".date('Y',strtotime($year_start_date))." - YEAR(s.dob)) = '".$_REQUEST['age']."'"; 
}

$base_sql="SELECT s.id, s.student_code, s.start_date_at_centre, s.nric_no, s.dob, s.race, s.religion,  s.student_code, s.name, co.subject from student s, course co where s. centre_code='$centre_code' and s.`student_status`='A' and s.deleted=0 and extend_year >= '$year' ".$where;
 
$name_token=ConstructTokenGroup("s.name", "%$sname%", "like", "s.student_code", "%$sname%", "like", "or");
$course_token=ConstructToken("co.course_name", "$course_name%", "LIKE");
$group_token=ConstructToken("g.class_id", "$group", "=");

switch ($status) {
   case "A" : $status_token=ConstructToken("s.student_status", "A", "="); break;
   case "I" : $status_token=ConstructToken("s.student_status", "A", "<>"); break;
   case "" : $status_token="";
}

$order_token=ConstructOrderToken("s.start_date_at_centre", "desc");

$final_token=ConcatToken($name_token, $course_token, "and");
$final_token=ConcatToken($final_token, $group_token, "and");
$final_token=ConcatToken($final_token, $status_token, "and");
$final_sql=ConcatWhere($base_sql, $final_token);
$final_sql.=" group by s.name, s.student_code";
$final_sql=ConcatOrder($final_sql, $order_token);

$result=mysqli_query($connection, $final_sql);
$num_row=mysqli_num_rows($result);
?>

   <div class="uk-overflow-container">
   <table id="mydatatable" class="uk-table">
      <thead>
         <tr class="uk-text-small uk-text-bold">
            <td>Student Name</td>
            <td>Student Code</td>
            <td>Age</td>
            <td>Class</td>
            <td>Start Date at Centre</td>
            <td>NRIC</td>
         </tr>
      </thead>
      <tbody>
<?php
if ($num_row>0) {
while ($row=mysqli_fetch_assoc($result)) {
   $continue=false;
   if ($sstatus=="onlyactive") {
      if (!isDeleted($row["id"])) {
         $continue=true;
      } else {
         $continue=false;
      }
   } else {
      if ($sstatus=="onlyinactive") {
         if (isDeleted($row["id"])) {
            $continue=true;
         } else {
            $continue=false;
         }
      } else {
         $continue=true;
      }
   }

   if ($continue) {
      $thecourse=explode("-", $row["course_name"]);
      $thecourse=$thecourse[0];
      $thecourse.=" - ".$row["class_id"];

      $course = mysqli_fetch_array(mysqli_query($connection,"SELECT `c`.`subject` FROM `allocation` a LEFT JOIN `course` c ON a.course_id = c.id WHERE a.year = '".$_SESSION['Year']."' AND a.student_id = '".$row['id']."' AND a.deleted = 0"));

      if(isset($_REQUEST['subject']) && $_REQUEST['subject'] != '' && $course['subject'] != $_REQUEST['subject']){
         continue;
      }

?>
      <tr class="uk-text-small">
         <td><a href="index.php?p=student_info&ssid=<?php echo sha1($row["id"])?>"><?php echo $row["name"]?></a></td>
         <td><?php echo $row["student_code"]?></td>
         <td>
            <?php
            $age = date('Y',strtotime($year_start_date)) - date('Y',strtotime($row['dob'])); 
            echo $age; 
            ?>
         </td>
         <td>
            <?php 
               echo $course['subject']; 
            ?>
         </td>
         <td><?php echo $row["start_date_at_centre"]?></td>
         <td><?php echo $row["nric_no"]?></td>
      </tr>
<?php
   }
   }
} else {
   echo "<tr><td colspan='14'>No record found</td></tr>";
}
?>
   </tbody>
   </table>
   </div>
<?php
//} else {
//   echo "<div class='uk-alert'>Please fill in programme, level, module and group</div>";
//}
?>

<script>
   $('#mydatatable').DataTable({
		'columnDefs': [ { 
        'targets': [0,8], // column index (start from 0)
        'orderable': false, // set orderable false for selected columns
      	}],
		"order": [[ 8, "asc" ]],
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": "admin/serverresponse/payment.php?programme="+programme+"&level="+level+"&module="+modules+"&group="+group+"&status="+status+"&sname="+sname+"&age="+age+"&subject="+subject
	});
</script>

<style>
			
   table tr:not(:first-child):nth-of-type(even) {
      background: #f5f6ff;
   }

   table td {
      color: grey;
      font-size: 1.2em;
   }

   table td {border: none!important;}

   #mydatatable_length{
      display: none;
   }

   #mydatatable_filter{
      display: none;
   }

   /*#mydatatable_paginate{float:initial;text-align:center}*/
   #mydatatable_paginate .paginate_button{display: inline-block; min-width: 16px; padding: 3px 5px; line-height: 20px; text-decoration: none; -moz-box-sizing: content-box; box-sizing: content-box; text-align: center; background: #f7f7f7; color: #444444; border: 1px solid rgba(0, 0, 0, 0.2); border-bottom-color: rgba(0, 0, 0, 0.3); background-origin: border-box; background-image: -webkit-linear-gradient(top, #ffffff, #eeeeee); background-image: linear-gradient(to bottom, #ffffff, #eeeeee); text-shadow: 0 1px 0 #ffffff;margin-left: 3px;
   margin-right: 3px}
   #mydatatable_paginate .paginate_button.current{background: #009dd8; color: #ffffff!important; border: 1px solid rgba(0, 0, 0, 0.2); border-bottom-color: rgba(0, 0, 0, 0.4); background-origin: border-box; background-image: -webkit-linear-gradient(top, #00b4f5, #008dc5); background-image: linear-gradient(to bottom, #00b4f5, #008dc5); text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.2);}
   #mydatatable_filter{width:100%}
   #mydatatable_filter label{width:100%;display:inline-flex}
   #mydatatable_filter label input{height: 30px;width: 100%; padding: 4px 6px; border: 1px solid #dddddd; background: #ffffff; color: #444444; -webkit-transition: all linear 0.2s; transition: all linear 0.2s; border-radius: 4px;}
</style>