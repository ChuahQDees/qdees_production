<?php

session_start();
include_once("../mysql.php");
//include_once("../uikit1.php");
include_once("functions.php");

function getDOWString($dow) {
   switch ($dow) {
      case 0 : return "Sunday"; break;
      case 1 : return "Monday"; break;
      case 2 : return "Tuesday"; break;
      case 3 : return "Wednesday"; break;
      case 4 : return "Thursday"; break;
      case 5 : return "Friday"; break;
      case 6 : return "Saturday"; break;
      case 7 : return "Daily"; break;
   }
}

function getDOWIndex($dow) {
   switch ($dow) {
      case "Sunday": return 0; break;
      case "Monday": return 1; break;
      case "Tuesday": return 2; break;
      case "Wednesday": return 3; break;
      case "Thursday": return 4; break;
      case "Friday": return 5; break;
      case "Saturday": return 6; break;
      case "Daily": return 7; break;
   }
}

function getGroupInfo($group_id, &$programme, &$level, &$module, &$class, &$course_id) {
   global $connection;

   $sql="SELECT * from `group` where id='$group_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   $class=$row["class_id"];
   $course_id=$row['course_id'];

   return $row;
}

function getCentreName($centre_code) {
   global $connection;

   $sql="SELECT kindergarten_name from centre where centre_code='$centre_code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      return $row["kindergarten_name"];
   } else {
      if ($centre_code=="ALL" || $centre_code=="")  {
         return "All Centres";
      } else { 
         return "";
      }
   }
}

function getCourseName($course_id){
  global $connection;

  $sql="SELECT * from `course` where id='$course_id'";
  $result=mysqli_query($connection, $sql);
  $row=mysqli_fetch_assoc($result);

  return $row['course_name'];
}

function getCoursesList(){
  global $connection;

  $sql="SELECT `id`, `course_name` from `course` ORDER BY course_name ASC";
  $result=mysqli_query($connection, $sql);

  $courses = array();
  if($result){
    while($row=mysqli_fetch_assoc($result)){
      $courses[$row['id']] = $row['course_name'];
    };
  }

  return $courses;
}

if ($_SESSION["isLogin"]==1) {
    if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O") || ($_SESSION["UserType"]=="S") || ($_SESSION["UserType"]=="H") || ($_SESSION["UserType"]=="C") || ($_SESSION["UserType"]=="R") || ($_SESSION["UserType"]=="CM") || ($_SESSION["UserType"]=="T")) &
        (hasRightGroupXOR($_SESSION["UserName"], "StudentEdit|StudentView"))) {

        /* include_once("../lib/pagination/pagination.php"); */
        $p=$_GET["p"];
        $m=$_GET["m"];
        $get_sha1_id=$_GET["id"];
        $pg=$_GET["pg"];
        $mode=$_GET["mode"];
        $fields_status =$_GET['fields_status'];
        $centre_code =$_GET['centre_code'];
        $todayDate = date("Y-m-d");
        //echo $fields_status; die;
        if (isset($_GET['selected_year'])) {
          $year = $_GET['selected_year'];
        } else {
          $year=$_SESSION['Year'];
        }

        if (($_SESSION["UserType"] == "S") || ($_SESSION["UserType"] == "S") || ($_SESSION["UserType"]=="H") || ($_SESSION["UserType"]=="C") || ($_SESSION["UserType"]=="R") || ($_SESSION["UserType"]=="CM") || ($_SESSION["UserType"]=="T")) {
         if (isset($_GET["centre_code"])) {
            $centre_code = $_GET["centre_code"];
         } else {
            $centre_code = 'ALL';
         }
      } else {
         $centre_code = $_SESSION["CentreCode"];
      }

        include_once("student_func.php");

        /* $numperpage=20; */
        $query="p=$p&m=$m&s=$s";

        $str=$_GET["s"];
        $student_status=$_GET["student_status"];

        $browse_sql="SELECT g.*, c.course_name from `group` g, course c where g.course_id=c.id and c.deleted = '0'";
        if (isset($_GET['filter_course_id']) && $_GET['filter_course_id'] != '') {
         $filter_course_id = mysqli_real_escape_string($connection, $_GET['filter_course_id']);
         $browse_sql .= " and course_id='$filter_course_id' ";
      }

      if (isset($_GET['filter_day']) && $_GET['filter_day'] != '') {
         $filter_day = mysqli_real_escape_string($connection, $_GET['filter_day']);
         $filter_day = getDOWIndex($filter_day);
         $browse_sql .= " and (dow='$filter_day' or dow1='$filter_day' or dow2='$filter_day' or dow3='$filter_day') ";
      }
      
      $str_length = isset($_GET['selected_month']) ? strlen($_GET['selected_month']) : 0;

      if (isset($_GET['selected_month']) && substr($_GET['selected_month'], ($str_length - 2), 2) != '13') {
         $selected_month = mysqli_real_escape_string($connection, $_GET['selected_month']);
         $str_length = strlen($selected_month);
         $request_Month = substr($selected_month, ($str_length - 2), 2);
         $request_Year = substr($selected_month, 0, -2);

         $browse_sql .= " and ('$request_Month' between month(start_date) and month(end_date)) and (year(start_date) = '$request_Year' or year(end_date) = '$request_Year')";
      } else {
         if (isset($_GET['selected_month'])) {
            $browse_sql .= " and g.`year`='" . substr($_GET['selected_month'], 0, -2) . "'";
         } else {
            $browse_sql .= " and g.`year`='" . $_SESSION['Year'] . "'";
         }
      }

      if (isset($fields_status) && $fields_status != '') {
        
         //$fields_status = mysqli_real_escape_string($connection, $_GET['fields_status']);
         if ($fields_status == 'Expired') {
            
            $browse_sql .= " and end_date < '$todayDate'";
         } else {
            $browse_sql .= " and end_date >= '$todayDate'";
         }
      } else {
         $browse_sql .= " and end_date >= '$todayDate'";
      }

      if ($centre_code == 'ALL' || $centre_code == '') {
        
         $browse_sql .= " order by CASE WHEN g.end_date >= NOW() THEN g.id END desc,";
        
     $browse_sql .= " g.id desc";
      } else {
        
     $browse_sql .= " and centre_code='$centre_code' order by CASE WHEN g.end_date >= NOW() THEN g.id END desc,";
        
   $browse_sql .= " g.id desc";
      }
     

      

        $order_query = $query;
        $order_arrow = "&uarr;";
        if ($_GET['order'] == 'asc') {
          $order_query.="&order_by=name&order=desc";
          $order_arrow = "&uarr;";
        }else if ($_GET['order'] == 'desc') {
          $order_query.="&order_by=name&order=asc";
          $order_arrow = "&darr;";
        }else{
          $order_query.="&order_by=name&order=desc";
          $order_arrow = "&uarr;";
        }

        if (isset($_GET["order_by"]) && isset($_GET["order"])) {
          $order_by=mysqli_real_escape_string($connection, $_GET["order_by"]);
          $order=mysqli_real_escape_string($connection,$_GET["order"]);
          $browse_sql.=" ORDER BY " . $order_by . " " . $order . " ";

          $query.="&order_by=" .  $_GET["order_by"] . "&order=" . $_GET['order'];
        }else{
          $browse_sql.=" ORDER BY student_status asc ";
        }

    /* $pagination=getPagination($pg, $numperpage, $query, $browse_sql, $start_record, $num_row);*/
        /* $browse_sql.=" limit $start_record, $numperpage"; */
        $browse_result=mysqli_query($connection, $browse_sql);
        if ($browse_result) {
            $browse_num_row=mysqli_num_rows($browse_result);
        } else {
          $browse_num_row=0;
        }
?>

<script>
function dlgTransAllocation(group_id, class_id, course_id) {
   $.ajax({
      url : "admin/dlgTransAllocation.php",
      type : "POST",
      data : "group_id="+group_id+"&class_id="+class_id+"&course_id="+course_id,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         $("#dlgTransAllocation").html(response);
         $("#dlgTransAllocation").dialog({
            dialogClass:"no-close",
            title:"Transfer Allocation",
            width:"auto",
            height:"auto"
         });
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}

function dlgDeleteGroup(group_id){
  UIkit.modal.confirm("<h2>Confirm delete?</h2>", function () {
    $.ajax({
       url : "admin/delete_group.php",
       type : "POST",
       data : "group_id="+group_id,
       dataType : "text",
       beforeSend : function(http) {
       },
       success : function(response, status, http) {
          $('#row-id-' + group_id).remove();
       },
       error : function(http, status, error) {
          UIkit.notify("Error:"+error);
       }
    });
  });
}

function getAllStudent(pg, student_name, allocation_status, group_id, class_id, course_id) {
   $.ajax({
      url : "admin/get_all_student.php",
      type : "POST",
      data : "student_name="+student_name+"&pg="+pg+"&allocation_status="+allocation_status+"&group_id="+group_id+"&class_id="+class_id+"&course_id="+course_id,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         $("#AllStudent").html(response);
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}

function getSelectedStudent(group_id, class_id, course_id) {
   $.ajax({
      url : "admin/get_selected_student.php",
      type : "POST",
      data : "group_id="+group_id+"&class_id="+class_id+"&course_id="+course_id,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         $("#SelectedStudent").html(response);
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}

function getSelectedStudentName(precourse) {
   $.ajax({
      url : "admin/get_selected_student.php",
      type : "POST",
      data : "pre_course=" + precourse + "&year="+<?php echo $year ?>,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         $("#SelectedStudent").html(response);
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}

function findClassData(the_class, programme, level, the_module, allocation_status) {
   if ((the_class!="") & (programme!="") & (level!="") & (the_module!="")) {
      getAllStudent('<?php echo $pg?>', "", allocation_status, $_GET["group_id"], $_GET["class"], $_GET["course_id"]);
      getSelectedStudent($_GET["group_id"], $_GET["class"], $_GET["course_id"]);
   } else {
      UIkit.notify("Please fill in programme, level, module and group");
   }
}

function getGroupID(programme, level, the_module, class_id) {
   if ((programme!="") & (level!="") & (the_module!="") & (class_id!="")) {
      $.ajax({
         url : "admin/getGroupID.php",
         type : "POST",
         data : "programme="+programme+"&level="+level+"&module="+the_module+"&class_id="+class_id,
         dataType : "text",
         async: false,
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            var s=response.split("|");
            if (s[0]=="0") {
               $("#get_group_result").val(s[0]);
               $("#group_id").val(s[1]);
            } else {
               $("#get_group_result").val(s[0]);
               $("#group_id").val(s[1]);
            }
         },
         error : function(http, status, error) {
            UIkit.notify("Error:"+error);
         }
      });
   } else {
      return "0";
   }
}

function generateReport(method) {
  var centre_code=$("#centre_code").val();
  var path = window.location.href;
  path = path.split("?");
  window.location.href = path[0] + "?p=rpt_class_listing&centre_code="+centre_code;
}



$(document).ready(function () {
   $("#btnSearch").click(function () {
      var programme=$("#programme").val();
      var level=$("#level").val();
      var the_module=$("#module").val();
      var the_class=$("#class").val();
      var allocation_status=$("#allocation_status").val();

      if ((programme!="") & (level!="") & (the_module!="") & (the_class!="")) {
         getGroupID(programme, level, the_module, the_class);

         if ($("#get_group_result").val()=="1") {
            findClassData(the_class, programme, level, the_module, allocation_status);
         } else {
            UIkit.notify($("#group_id").val());
         }
      } else {
         UIkit.notify("Please fill up all fields");
      }
   });
});

</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css">
<link rel="stylesheet" type="text/css" href="../lib/uikit/css/uikit.gradient.min6.my.css">
<link rel="stylesheet" href="../css/my1.css">
<link rel="stylesheet" href="../css/style.css">
<style>
@page {
    size: auto;
}

@media print {
  *{ color-adjust: exact; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
  #btnPrint{
    display:none;
  }
}
</style>

<div class="uk-margin-right uk-margin-left uk-margin-top">

  <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color myheader-text-style">Class Listing Report</h2>
   </div>

   <div class="nice-form">
     <div class="uk-grid" style="background-color: white;">
           <div class="uk-width-medium-5-10" width:50%>
              <table class="uk-table">
                 <tr>
                    <td class="uk-text-bold">Centre Name</td>
                    <td><?php echo getCentreName($centreCode)?></td>
                 </tr>
                 <tr>
                    <td class="uk-text-bold">Prepare By</td>
                    <td><?php echo $_SESSION["UserName"]?></td>
                 </tr>
                 <tr>
                    <td colspan="2" class="uk-text-bold">NOTE: To be submitted termly with the order form</td>
                 </tr>
              </table>
           </div>
           <div class="uk-width-medium-5-10" style="width:50%">
              <table class="uk-table">
              <tr>
               <td class="uk-text-bold">Academic Year</td>
               <?php if($selected_month):?>
               <td><?php echo $selected_month?></td>
               <?php else:?>
               <td><?php echo $_SESSION['Year'];?></td>
               <?php endif;?>
            </tr>
                 <tr>
                    <td class="uk-text-bold">School Term</td>
                    <td>
            <?php
            $month = date("m");
            $year = $_SESSION['Year'];
            $selected_month = $_GET['selected_month'];
                     if (isset($selected_month) && $selected_month != '') {
                        $str_length = strlen($selected_month);
                        $month = substr($selected_month, ($str_length - 2), 2);
                        $year = substr($selected_month, 0, -2);
                     }
                     $sql = "SELECT * from codes where year=".$year." and from_month<=$month and to_month>=$month";
                    
                     $centre_result = mysqli_query($connection, $sql);
                     while ($centre_row = mysqli_fetch_assoc($centre_result)) {
                        echo $centre_row['category']."/".$centre_row['year']."<br>";
                     }
                  //}
                     ?>
            </td>
                 </tr>
                 <tr>
                    <td class="uk-text-bold">Date of submission</td>
                    <td><?php echo date("Y-m-d H:i:s")?></td>
                 </tr>
              </table>
           </div>
        </div>
<?php
 $date_from=$_GET['date_from'];
$date_to=$_GET['date_to'];
$todayDate = date("Y-m-d");
   $sql = "SELECT g.*, c.subject from `group` g, course c where g.course_id=c.id and g.`year`='$year'";
if (isset($_GET['filter_course_id']) && $_GET['filter_course_id'] != '') {
      $filter_course_id = mysqli_real_escape_string($connection, $_GET['filter_course_id']);
      $sql .= " and level='$filter_course_id' ";
   }

   if (isset($_GET['course_name']) && $_GET['course_name'] != '') {
      $course_name = mysqli_real_escape_string($connection, $_GET['course_name']);
      $sql .= " and subject like '%$course_name%' ";
   }
  if($_GET['fields_status']==""){

   $str_length = isset($_GET['selected_month']) ? strlen($_GET['selected_month']) : 0;

	if (isset($_GET['selected_month']) && substr($_GET['selected_month'], ($str_length - 2), 2) != '13') {
	  $selected_month = mysqli_real_escape_string($connection, $_GET['selected_month']);
     $str_length = strlen($selected_month);
	  $request_Month = substr($selected_month, ($str_length - 2), 2);
	  $request_Year = substr($selected_month, 0, -2);

	  $sql .= " and ('$request_Month' between month(start_date) and month(end_date)) and (year(start_date) = '$request_Year' or year(end_date) = '$request_Year')";
	} else {
	  if (isset($_GET['selected_month'])) {
		 $sql .= " and g.`year`='" . substr($_GET['selected_month'], 0, -2) . "'";
	  } else {
		 $sql .= " and g.`year`='" . $_SESSION['Year'] . "'";
	  }
	}
  }
   // echo $sql; die;
   if (isset($_GET['fields_status']) && $_GET['fields_status'] != '') {
      $fields_status = mysqli_real_escape_string($connection, $_GET['fields_status']);
      if ($fields_status == 'Expired') {
         $sql .= " and end_date < '$todayDate'";
      } else {
         $sql .= " and end_date >= '$todayDate'";
      }
   } else {
      $sql = "SELECT g.*, c.subject from `group` g, course c where g.course_id=c.id";
   }
   if (isset($_GET['filter_date']) && $_GET['filter_date'] != '') {
      $filter_date = mysqli_real_escape_string($connection, $_GET['filter_date']);
      $sql .= " and '$filter_date' between start_date and end_date ";
   }
   if ($date_from != "") {
	   $sql .=" and g.start_date >= '$date_from' ";
	}
	if ($date_to != "") {
	   $sql .=" and g.end_date <= '$date_to' ";
	} 

$sql .= " and centre_code='$centre_code' order by c.subject";



$result=mysqli_query($connection, $sql);
  echo "<table class='uk-table' id='mydatatable'>";
   echo "<thead>";
   echo "  <tr class='uk-text-bold'>";
   echo "    <td>No.</td>";
   echo "    <td>Period</td>";
   echo "    <td>Day of Week</td>";
   //echo "    <td>Time</td>";
   echo "    <td>Programme (Class)</td>";
   echo "    <td>Duration</td>";
   echo "    <td>Group</td>";
   echo "    <td>No of Active Students</td>";
   echo "    <td>Status</td>";
   echo "  </tr>";
   echo "</thead>";

echo "<tbody>";
$row_index = 0;
while ($row=mysqli_fetch_assoc($result)) {
   $row_group_id=$row["id"];
   $row_class_id=$row["class_id"];
   $row_course_id=$row["course_id"];
   $row_duration=$row["duration"];
   $sql="SELECT count(*) as no_of_student, c.course_name, c.subject, a.class_id, c.id, a.deleted from allocation a, course c, student s
   where a.course_id=c.id and a.student_id=s.id and s.student_status='A' and s.deleted=0 and a.deleted=0 and a.`year`='$year' and a.group_id='$row_group_id' and a.class_id='$row_class_id' and a.course_id='$row_course_id'";

   if ($centre_code == 'ALL') {
     $sql .= " group by course_id, class_id";
   } else {
     $sql .= " and s.centre_code='".$centre_code."' group by course_id, class_id";
   }
//echo $sql; die;
   $c_result=mysqli_query($connection, $sql);
   $c_num_row=mysqli_num_rows($c_result);
   $c_row=mysqli_fetch_assoc($c_result);

   if ($c_num_row>0) {
      $no_of_student=$c_row["no_of_student"];
   } else {
      $no_of_student=0;
   }

   $period=convertDate2British($row["start_date"])." - ".convertDate2British($row["end_date"]);
   $time=$row["start_time"]." - ".$row["end_time"];
   $param="\"$row_group_id\", \"$row_class_id\", \"$row_course_id\"";
   $row_index++;
   echo "<tr id='row-id-" . $row["id"] . "'>";
   echo "  <td>$row_index</td>";
   echo "  <td>$period</td>";
   echo '  <td><span style="display:none">'.$row["dow"].'</span><div class="pl-1"><i class="fas fa-calendar-alt mr-2"></i>'.getDOWString($row["dow"]) . '</div>';
   echo '<div><span class="badge badge-primary mt-2" style="font-size: 14px"><i class="fas fa-clock mr-2"></i>' . "$time" . '</span></div>';
   if ($row["dow1"]!=-1) {
      echo '<span style="display:none">'.$row["dow1"].'</span><div class="mt-3 pl-1"><i class="fas fa-calendar-alt mr-2"></i>' . getDOWString($row["dow1"]) . '</div>';
      $time=$row["start_time1"]." - ".$row["end_time1"];
      echo '<div><span class="badge badge-primary mt-2" style="font-size: 14px"><i class="fas fa-clock mr-2"></i>' . $time . '</span></div>';
   }
   if ($row["dow2"]!=-1) {
      echo '<span style="display:none">'.$row["dow2"].'</span><div class="mt-3 pl-1"><i class="fas fa-calendar-alt mr-2"></i>' . getDOWString($row["dow2"]) . '</div>';
      $time=$row["start_time2"]." - ".$row["end_time2"];
      echo '<div><span class="badge badge-primary mt-2" style="font-size: 14px"><i class="fas fa-clock mr-2"></i>' . $time . '</span></div>';
   }
   if ($row["dow3"]!=-1) {
      echo '<span style="display:none">'.$row["dow3"].'</span><div class="mt-3 pl-1"><i class="fas fa-calendar-alt mr-2"></i>' . getDOWString($row["dow3"]) . '</div>';
      $time=$row["start_time3"]." - ".$row["end_time3"];
      echo '<div><span class="badge badge-primary mt-2" style="font-size: 14px"><i class="fas fa-clock mr-2"></i>' . $time . '</span></div>';
   }
   echo "  </td>";
$subject=$row["subject"];
   echo "  <td><a href='index.php?p=rpt_class_listing_report&group_id=$row_group_id&class=$row_class_id&course_id=$row_course_id&subject=$subject&duration=$row_duration'>".$row["subject"]."</a></td>";
  
   echo " <td>$row_duration</td>";
		if($row_class_id=="En-Picasso1"){
			echo "<td>En-Picasso 1</td>";
		 }else if($row_class_id=="En-Nightingale1"){
			 echo "<td>En-Nightingale 1</td>";
		 }else if($row_class_id=="En-Picasso2"){
			 echo "<td>En-Picasso 2</td>";
		 }else if($row_class_id=="En-Nightingale2"){
			 echo "<td>En-Nightingale 2</td>";
		 }else if($row_class_id=="En-DaVinci"){
			 echo "<td>En-Da Vinci</td>";
		 }else if($row_class_id=="En-GrahamBell"){
			 echo "<td>En-Graham Bell</td>";
		 }else{
		 echo "  <td>$row_class_id</td>";
		 }
   echo "  <td> <center> ".$no_of_student." <center> </td>";
	  if ($row["end_date"] >= $todayDate) {
		 echo "<td>" . 'Active' . "</td>";
	  } else {
		 echo "<td>" . 'Expired' . "</td>";
	  }
   echo "</tr>";
}
echo "</tbody>";

echo "</table>";

echo "<input name='programme' id='programme' value='' type='hidden'>";
echo "<input name='level' id='level' value='' type='hidden'>";
echo "<input name='module' id='module' value='' type='hidden'>";
echo "<input name='class' id='class' value='' type='hidden'>";
?>
         </div>
    <br>
</div>
<script>
function printDialog() {
   window.print();
}

printDialog();
opener.location.reload();

</script>
<?php
   } else {
      echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
   }
} else {
   header("Location: ../index.php");
}
?>


