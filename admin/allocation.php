<?php if($_GET['p'] == "allocation" && isset($_GET['backa']) ){ ?>
<a href="/index.php?p=allocation">                 
             <span class="d_n btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>

 <?php } else if($_GET['p'] == "allocation" && isset($_GET['groupback'])  ) { ?>
   <a href="/index.php?p=group_creation">                 
             <span class="d_n btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a> 
<?php }else if($_GET['p'] == "allocation"){ ?>
   <a href="/index.php?p=allocation_hq">                 
             <span class="d_n btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>
<?php } ?>

<!-- <a href="/index.php?p=allocation_hq">
         <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a> -->
<span>
    <span class="page_title"><img src="/images/title_Icons/Classrom Allocation.png">Classroom Allocation</span>
</span>

<style>
    form[name="frmAllocation"] tr:first-child {
        font-size: 1.1em;
        color: #585858;
    }
    form[name="frmAllocation"] tr:first-child td {font-weight: 400;text-align: center;}


    form[name="frmAllocation"] tr:not(:first-child) {
        color: grey;
        text-align: center;
    }

    form[name="frmAllocation"] tr:not(:first-child):nth-of-type(even) {
        background: #f5f6ff;
    }

    form[name="frmAllocation"] tr:not(:first-child) td {border: none;}

    form[name="frmAllocation"] a, form[name="frmAllocation"] a:hover {color: blue; list-style-type: none}

    #mydatatable_length{
display: none;
}

#mydatatable_paginate{float:initial;text-align:center}
#mydatatable_paginate .paginate_button{display: inline-block; min-width: 16px; padding: 3px 5px; line-height: 20px; text-decoration: none; -moz-box-sizing: content-box; box-sizing: content-box; text-align: center; background: #f7f7f7; color: #444444; border: 1px solid rgba(0, 0, 0, 0.2); border-bottom-color: rgba(0, 0, 0, 0.3); background-origin: border-box; background-image: -webkit-linear-gradient(top, #ffffff, #eeeeee); background-image: linear-gradient(to bottom, #ffffff, #eeeeee); text-shadow: 0 1px 0 #ffffff;margin-left: 3px;
    margin-right: 3px}
#mydatatable_paginate .paginate_button.current{background: #009dd8; color: #ffffff!important; border: 1px solid rgba(0, 0, 0, 0.2); border-bottom-color: rgba(0, 0, 0, 0.4); background-origin: border-box; background-image: -webkit-linear-gradient(top, #00b4f5, #008dc5); background-image: linear-gradient(to bottom, #00b4f5, #008dc5); text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.2);}
#mydatatable_filter{width:100%}
#mydatatable_filter label{width:100%;display:inline-flex}
#mydatatable_filter label input{height: 30px;width: 100%; padding: 4px 6px; border: 1px solid #dddddd; background: #ffffff; color: #444444; -webkit-transition: all linear 0.2s; transition: all linear 0.2s; border-radius: 4px;}

.row-action a i{
  font-size: 1.3rem;
  margin-right: 5px;
}
#mydatatable_filter{
	display:none;
}
.icon_active{
	white-space: nowrap;
}
table#mydatatable {
    text-align: center;
}
</style>

<?php
include_once("mysql.php");
include_once("admin/functions.php");

$group_id=$_GET["group_id"];
$get_class_id=$_GET["class"];
$get_course_id= $_GET["course_id"];

function getDOWString($dow) {
   switch ($dow) {
      case 0 : return "Sunday"; break;
      case 2 : return "Monday"; break;
      case 3 : return "Tuesday"; break;
      case 4 : return "Wednesday"; break;
      case 5 : return "Thursday"; break;
      case 6 : return "Friday"; break;
      case 7 : return "Saturday"; break;
      case 1 : return "Daily"; break;
   }
}

function getDOWIndex($dow) {
   switch ($dow) {
      case "Sunday": return 0; break;
      case "Monday": return 2; break;
      case "Tuesday": return 3; break;
      case "Wednesday": return 4; break;
      case "Thursday": return 5; break;
      case "Friday": return 6; break;
      case "Saturday": return 7; break;
      case "Daily": return 1; break;
   }
}

function getGroupInfo($group_id, &$duration, &$level, &$session, &$class, &$course_id) {
   global $connection;

   $sql="SELECT * from `group` where id='$group_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   $class=$row["class_id"];
   $course_id=$row['course_id'];

   return $row;
}

function getCourseName($course_id){
  global $connection;

  $sql="SELECT * from  `course` where id='$course_id'";
  $result=mysqli_query($connection, $sql);
  $row=mysqli_fetch_assoc($result);

  return $row['subject'];
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
function isDilitable($group_id) {
            
   global $connection;
   $sql = "SELECT * from `allocation`  where group_id ='$group_id' AND `deleted` = 0";

   $result = mysqli_query($connection, $sql);
   $num_row = mysqli_num_rows($result);

   if ($num_row > 0) {
      return false;
   } else {
      return true;
   }
}
if ($_SESSION["isLogin"]==1) {
   if (($_SESSION["UserType"]=="O") || ($_SESSION["UserType"]=="A")) {

   $course=$_POST["course"];
   $class=$_POST["class"];
   $student_name=$_POST["student_name"];

   $year=$_SESSION['Year'];
   $centre_code=$_SESSION["CentreCode"];
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
            title:"Transfer Class",
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

function getAllStudent(pg, student_name, allocation_status, group_id, class_id, course_id, student_entry_level) {
   $.ajax({
      url : "admin/get_all_student.php",
      type : "POST",
      data : "student_name="+student_name+"&pg="+pg+"&allocation_status="+allocation_status+"&group_id="+group_id+"&class_id="+class_id+"&course_id="+course_id+"&student_entry_level="+student_entry_level,
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

function findClassData(the_class, duration, level, session, allocation_status) {
   if ((the_class == $_GET['class']) & (duration!="") & (level!="") & (session!="")) {
      getAllStudent('<?php echo $pg?>', "", allocation_status, $_GET["group_id"], $_GET["class"], $_GET["course_id"]);
      getSelectedStudent($_GET["group_id"], $_GET["class"], $_GET["course_id"]);
   } else {
      UIkit.notify("Please fill in duration, level, session and group");
   }
}

function getGroupID(duration, level, session, class_id) {
   if ((duration!="") & (level!="") & (session!="") & (class_id!="")) {
      $.ajax({
         url : "admin/getGroupID.php",
         type : "POST",
         data : "duration="+duration+"&level="+level+"&session="+session+"&class_id="+class_id,
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

$(document).ready(function () {
   $("#btnSearch").click(function () {
      var duration=$("#duration").val();
      var level=$("#level").val();
      var session=$("#session").val();
      var the_class=$("#class").val();
      var allocation_status=$("#allocation_status").val();

      if ((duration!="") & (level!="") & (session!="") & (the_class!="")) {
         getGroupID(duration, level, session, the_class);

         if ($("#get_group_result").val()=="1") {
            findClassData(the_class, duration, level, session, allocation_status);
         } else {
            UIkit.notify($("#group_id").val());
         }
      } else {
         UIkit.notify("Please fill up all fields");
      }
   });
});

</script>

<div class="uk-margin-right uk-margin-top">

  <?php if( !empty($group_id) ){ ?>
    <?php
      $group_info = getGroupInfo($group_id, $duration, $level, $session, $get_class_id, $course_id);
      $group_info = getGroupInfo($group_id, $duration, $level, $session, $get_class_id, $course_id);
      $group_period=convertDate2British($group_info["start_date"])." - ".convertDate2British($group_info["end_date"]);
    ?>
    <div class="uk-width-1-1 myheader text-center">
	<?php 
		$sql="SELECT * from  `group` where course_id='$course_id' and class_id='$get_class_id'";
		//echo $sql;
		$result = mysqli_query($connection, $sql);
         $num_row = mysqli_num_rows($result);

         if ($num_row > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
				$level2 = $row["level"];
				$class_id2 = $row["class_id"];
				$duration = $row["duration"];
			}}
	
	?>
	
	<h2 class="uk-text-center myheader-text-color myheader-text-style"><?php  echo $level2; ?> -
	<?php if($_GET['class']=="En-Picasso1"){
			echo "En-Picasso 1";
		 }else if($_GET['class']=="En-Nightingale1"){
			 echo "En-Nightingale 1";
		 }else if($_GET['class']=="En-Picasso2"){
			 echo "En-Picasso 2";
		 }else if($_GET['class']=="En-Nightingale2"){
			 echo "En-Nightingale 2";
		 }else if($_GET['class']=="En-DaVinci"){
			 echo "En-Da Vinci";
		 }else if($_GET['class']=="En-GrahamBell"){
			 echo "En-Graham Bell";
		 }else{
		 echo $_GET['class'];
		 } ?> 
		 
		 - <?php  echo $_GET['duration']; ?></h2>
    </div>

    <div class="nice-form text-center">
      <a href="admin/class_report.php?group_id=<?php echo $group_id ?>&class=<?php echo $get_class_id ?>&course_id=<?php echo $course_id ?>" target="_blank" class="uk-button full-width-blue" style="width:120px; float:right">Print</a>
      <h4><b>Period:</b> <?php echo $group_period; ?></h4>
      <div class=" table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <td style="font-weight:bold; width: 25%">Session 1</td>
              <td style="font-weight:bold; width: 25%">Session 2</td>
              <td style="font-weight:bold; width: 25%">Session 3</td>
              <td style="font-weight:bold; width: 25%">Session 4</td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <?php
                  if ($group_info["dow"]!=-1) {
                     echo '<div class="mt-3 mb-2 pl-1"><i class="fas fa-calendar-alt mr-2"></i>' . getDOWString($group_info["dow"]) . '</div>';
                    $time=$group_info["start_time"]." - ".$group_info["end_time"];
                     echo '<div><span class="badge badge-primary mt-2" style="font-size: 14px"><i class="fas fa-clock mr-2"></i>' . $time . '</span></div>';
                  }
                ?>
              </td>
              <td>
                <?php
                  if ($group_info["dow1"]!=-1) {
                     echo '<div class="mt-3 mb-2 pl-1"><i class="fas fa-calendar-alt mr-2"></i>' . getDOWString($group_info["dow1"]) . '</div>';
                    $time=$group_info["start_time1"]." - ".$group_info["end_time1"];
                     echo '<div><span class="badge badge-primary mt-2" style="font-size: 14px"><i class="fas fa-clock mr-2"></i>' . $time . '</span></div>';
                  }
                ?>
              </td>
              <td>
                <?php
                if ($group_info["dow2"]!=-1) {
                   echo '<div class="mt-3 mb-2 pl-1"><i class="fas fa-calendar-alt mr-2"></i>' . getDOWString($group_info["dow2"]) . '</div>';
                  $time=$group_info["start_time2"]." - ".$group_info["end_time2"];
                   echo '<div><span class="badge badge-primary mt-2" style="font-size: 14px"><i class="fas fa-clock mr-2"></i>' . $time . '</span></div>';
                }
                ?>
              </td>
              <td>
                <?php
                if ($group_info["dow3"]!=-1) {
                   echo '<div class="mt-3 mb-2 pl-1"><i class="fas fa-calendar-alt mr-2"></i>' . getDOWString($group_info["dow3"]) . '</div>';
                  $time=$group_info["start_time3"]." - ".$group_info["end_time3"];
                   echo '<div><span class="badge badge-primary mt-2" style="font-size: 14px"><i class="fas fa-clock mr-2"></i>' . $time . '</span></div>';
                }
                ?>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <p class="font-weight-bold">Please allocate students to this class using the action below.</p>
    </div>

    <div id="student-info" class="mb-5">
      <div class="uk-grid">
         <div class="uk-width-medium-1-2">
            <div id="AllStudent"></div>
         </div>
         <div class="uk-width-medium-1-2">
            <div id="SelectedStudent"></div>
         </div>
      </div>
    </div>

    <?php
         echo "<script>";
         echo "   getAllStudent('$pg', '', 'onlyunallocated', '$group_id', '$get_class_id', '$get_course_id', '$level2');";
         echo "   getSelectedStudent('$group_id', '$get_class_id', '$get_course_id');";
         echo "</script>";
    ?>
  <?php } ?>

  <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color myheader-text-style">Searching</h2>
  </div>

  <form class="uk-form" name="frmSearch" id="frmSearch" method="get" action>
      <input type="hidden" name="p" value="allocation">
      <div class="uk-grid">
          <div class="uk-width-2-10 uk-text-small">
				<select name="filter_course_id" id="filter_course_id" class="uk-width-1-1">
			   <option value="">Select</option>
				<option value="EDP">EDP</option>
			   <option value="QF1">QF1</option>
			   <option value="QF2">QF2</option>
			    <option value="QF3">QF3</option>
			</select>
            <?php
              //$fields_classes = getCoursesList();
             // generateSelectArray($fields_classes, "filter_course_id", "class='uk-width-1-1'", $_GET['filter_course_id']);
            ?>
          </div>
          <div class="uk-width-2-10 uk-text-small" style="padding-left:0px; padding-right:20px; width: 15%;">
           <?php
             $fields=array("Monday"=>"Monday", "Tuesday"=>"Tuesday", "Wednesday"=>"Wednesday", "Thursday"=>"Thursday", "Friday"=>"Friday", "Saturday"=>"Saturday", "Sunday"=>"Sunday");
             generateSelectArray($fields, "filter_day", "class='uk-width-1-1'", $_GET['filter_day']);
           ?>
          </div>
		  <div class="uk-width-2-10 uk-text-small" style="padding-left:0px; padding-right:20px; width: 15%;">
         <?php
           $fields_status=array("Active"=>"Active", "Expired"=>"Expired");
            generateSelectArray($fields_status, "fields_status", "class='uk-width-1-1'", $_GET['fields_status']);
         ?>
        </div>
		<div class="uk-width-medium-1-10" style="padding-left:0px; padding-right:20px; width: 15%;">
			<input type="text" class="uk-width-1-1" name="date_from" id="date_from" placeholder="Date From" data-uk-datepicker="{format: 'YYYY-MM-DD'}" value="">
		  </div>
		  <div class="uk-width-medium-1-10" style="padding-left:20px; padding-right:20px; width: 15%;">
				<input type="text" class="uk-width-1-1" name="date_to" id="date_to" placeholder="Date To" data-uk-datepicker="{format: 'YYYY-MM-DD'}" value="">
		  </div>
          <!--<div class="uk-width-2-10 uk-text-small">
            <input type="text" name="filter_date" class="datepicker-group" placeholder="Select Date" autocomplete="off" style="width: 100%;" value="<?php // echo isset($_GET['filter_date']) ? $_GET['filter_date'] : ''; ?>">
          </div>-->
          <div class="uk-width-2-10">
              <button class="uk-button uk-width-1-1 full-width-blue">Search</button>
          </div>
      </div>
  </form>

   <div class="uk-width-1-1 myheader mt-5">
      <h2 class="uk-text-center myheader-text-color myheader-text-style">Student - Course - Class Allocation</h2>
   </div>
<div class="nice-form">
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

   if (isset($_GET['filter_day']) && $_GET['filter_day'] != '') {
      $filter_day = mysqli_real_escape_string($connection, $_GET['filter_day']);
      $filter_day = getDOWIndex($filter_day);
      //$sql .= " and (dow='$filter_day' or dow1='$filter_day' or dow2='$filter_day' or dow3='$filter_day') ";
	  if(($filter_day!='0') && ($filter_day!='7')){
		$sql .= " and (dow='$filter_day' or dow='1' or dow1='$filter_day' or dow2='$filter_day' or dow3='$filter_day') ";
	  }else{
		   $sql .= " and (dow='$filter_day' or dow1='$filter_day' or dow2='$filter_day' or dow3='$filter_day') ";
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
    //  $sql .= " and end_date >= '$todayDate'";
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
//echo $sql;
   $result = mysqli_query($connection, $sql);
echo "<table class='uk-table' id='mydatatable'>";
echo "<thead>";
echo "  <tr class='uk-text-bold'>";
echo "    <td class='no_st_no'>No.</td>";
echo "    <td>Period</td>";
echo "    <td>Day</td>";
echo "    <td>Level</td>";
echo "    <td>Duration</td>";
echo "    <td>Class Name</td>";
echo "    <td>No of Students</td>";
echo "    <td class='no_st'>Status</td>";
echo "    <td>Action</td>";
echo "  </tr>";
echo "</thead>";

echo "<tbody>";
$row_index = 0;
while ($row=mysqli_fetch_assoc($result)) {
	$end_date = $row["end_date"];
	$before_14_ex = date('Y-m-d', strtotime('-14 day', strtotime($end_date)));
     $after_14_ex = date('Y-m-d', strtotime('+14 day', strtotime($end_date)));
	 if (date('Y-m-d') >= $before_14_ex) {
         $color_red = 'style="color:red;"';
      } else {
         $color_red = '';
      }
	if (date('Y-m-d') <= $after_14_ex) {
         $row_group_id = $row["id"];
         $row_class_id = $row["class_id"];
         $level = $row["level"];
         $row_course_id = $row["course_id"];

         $sql = "SELECT count(*) as no_of_student, c.course_name, c.subject, a.class_id, c.id, a.deleted from allocation a, course c, student s where a.course_id=c.id and a.student_id=s.id and s.student_status='A' and a.deleted=0 and a.`year`='$year' and a.group_id='$row_group_id' and a.class_id='$row_class_id' and a.course_id='$row_course_id' and s.centre_code='" . $_SESSION["CentreCode"] . "' group by course_id, class_id";
        
         $c_result = mysqli_query($connection, $sql);
         $c_num_row = mysqli_num_rows($c_result);
         $c_row = mysqli_fetch_assoc($c_result);
         if ($c_num_row > 0) {
            $no_of_student = $c_row["no_of_student"];
         } else {
            $no_of_student = 0;
         }


         $period = convertDate2British($row["start_date"]) . " - " . convertDate2British($row["end_date"]);
         $time = $row["start_time"] . " - " . $row["end_time"];
         $param = "\"$row_group_id\", \"$row_class_id\", \"$row_course_id\"";
         $row_index++;
         echo "<tr id='row-id-" . $row["id"] . "'>";
         echo "  <td>$row_index</td>";
         echo "  <td>$period</td>";
         echo '  <td><span style="display:none">' . $row["dow"] . '</span><div class="pl-1"><i class="fas fa-calendar-alt mr-2"></i>' . getDOWString($row["dow"]) . '</div>';
         echo '<div><span class="badge badge-primary mt-2" style="font-size: 14px"><i class="fas fa-clock mr-2"></i>' . "$time" . '</span></div>';
         if ($row["dow1"] != -1) {
            echo '<span style="display:none">' . $row["dow1"] . '</span><div class="mt-3 pl-1"><i class="fas fa-calendar-alt mr-2"></i>' . getDOWString($row["dow1"]) . '</div>';
            $time = $row["start_time1"] . " - " . $row["end_time1"];
            echo '<div><span class="badge badge-primary mt-2" style="font-size: 14px"><i class="fas fa-clock mr-2"></i>' . $time . '</span></div>';
         }
         if ($row["dow2"] != -1) {
            echo '<span style="display:none">' . $row["dow2"] . '</span><div class="mt-3 pl-1"><i class="fas fa-calendar-alt mr-2"></i>' . getDOWString($row["dow2"]) . '</div>';
            $time = $row["start_time2"] . " - " . $row["end_time2"];
            echo '<div><span class="badge badge-primary mt-2" style="font-size: 14px"><i class="fas fa-clock mr-2"></i>' . $time . '</span></div>';
         }
         if ($row["dow3"] != -1) {
            echo '<span style="display:none">' . $row["dow3"] . '</span><div class="mt-3 pl-1"><i class="fas fa-calendar-alt mr-2"></i>' . getDOWString($row["dow3"]) . '</div>';
            $time = $row["start_time3"] . " - " . $row["end_time3"];
            echo '<div><span class="badge badge-primary mt-2" style="font-size: 14px"><i class="fas fa-clock mr-2"></i>' . $time . '</span></div>';
         }
         echo "  </td>";
		 $duration = $row["duration"];
         if ($row["end_date"] >= $todayDate) {
            echo "  <td><a " . $color_red . " href='index.php?p=allocation&group_id=$row_group_id&class=$row_class_id&course_id=$row_course_id&backa&duration=$duration'>" . $row["level"] . "</a></td>";
         } else {
            echo "<td><a " . $color_red . " href='#'>" . $row["level"] . "</a></td>";
         }
		  echo "<td>" . $row["duration"] . "</td>";
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
         echo "  <td>" . $no_of_student . "</td>";

         if ($row["end_date"] >= $todayDate) {
            echo "<td>" . 'Active' . "</td>";
         } else {
            echo "<td>" . 'Expired' . "</td>";
         }

         echo "  <td class='row-action icon_active'>";
      
         //if ($current_year == $year) {
            if ($row["end_date"] >= $todayDate) {
			
               echo "  <a href='index.php?p=allocation&group_id=$row_group_id&class=$row_class_id&course_id=$row_course_id&level=$level&backa&duration=$duration'><i data-uk-tooltip title='Allocate Student' class='fas fa-user text-primary'></i></a>";
               //echo "  <a onclick='dlgTransAllocation(" . $param . ");'><i data-uk-tooltip title='Transfer Class' class='fas fa-exchange-alt text-success'></i></a>";
			   //if(isDilitable( $row["id"] ) == true){
            if($no_of_student == 0) {
               echo "  <a onclick='dlgDeleteGroup(" . $row["id"] . ");'><i data-uk-tooltip title='Delete Class' class='fas fa-trash text-danger'></i></a>";
            }
			}
        // }
         echo "  </td>";
         echo "</tr>";
      }
}
echo "</tbody>";

echo "</table>";

echo "<input name='programme' id='programme' value='' type='hidden'>";
echo "<input name='level' id='level' value='' type='hidden'>";
echo "<input name='module' id='module' value='' type='hidden'>";
echo "<input name='class' id='class' value='' type='hidden'>";
?>
         </div>

<input type="hidden" name="get_group_result" id="get_group_result" value="">
<input type="hidden" name="group_id" id="group_id" value="">

<script>
function getActiveClasses() {
   $.ajax({
      url : "admin/getActiveClasses.php",
      type : "POST",
      data : "",
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         $("#sctActiveClasses").html(response)
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}


$(document).ready(function(){
  $('.datepicker-group').datepicker({
     dateFormat: 'yy-mm-dd'
  });
  $('#mydatatable').DataTable({
        language: { search: "" },
        "bInfo" : false,
		 'columnDefs': [ {
			'targets': [ 0, 7, 8 ], // column index (start from 0)
			'orderable': false, // set orderable false for selected columns
		 }]
       });
$(".no_st_no").removeClass("sorting_asc");
});
</script>
<br>
</div>
<?php
   } else {
      echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
   }
} else {
   header("Location: index.php");
}
?>
</div>
<div id="dlgTransAllocation"></div>
