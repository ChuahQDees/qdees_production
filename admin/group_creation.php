<a href="/index.php?p=allocation_hq">
   <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>
<span>
   <span class="page_title"><img src="/images/title_Icons/Class Creation.png">Class Creation</span>
</span>


<?php
session_start();
$year=$_SESSION['Year'];
$centre_code=$_SESSION["CentreCode"];
$current_year = $_SESSION['Year'];
include_once("../mysql.php");
include_once("functions.php");

function getDOWString($dow)
{
   switch ($dow) {
      case 0:
         return "Sunday";
         break;
      case 2:
         return "Monday";
         break;
      case 3:
         return "Tuesday";
         break;
      case 4:
         return "Wednesday";
         break;
      case 5:
         return "Thursday";
         break;
      case 6:
         return "Friday";
         break;
      case 7:
         return "Saturday";
         break;
      case 1:
         return "Daily";
         break;
   }
}

function getDOWIndex($dow)
{
   switch ($dow) {
      case "Sunday":
         return 0;
         break;
      case "Monday":
         return 2;
         break;
      case "Tuesday":
         return 3;
         break;
      case "Wednesday":
         return 4;
         break;
      case "Thursday":
         return 5;
         break;
      case "Friday":
         return 6;
         break;
      case "Saturday":
         return 7;
         break;
      case "Daily":
         return 1;
         break;
   }
}

function getCourseName($course_id)
{
   global $connection;
   $sql = "SELECT * from course where id='$course_id'";
   $result = mysqli_query($connection, $sql);
   $row = mysqli_fetch_assoc($result);
   $course_name = $row["course_name"];
   return trim($course_name);
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

function getCourseID($subject) {
   global $connection;

   $sql="SELECT id from course where subject like '%$subject%'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["id"];
}
function isDilitable($group_id) {
            
   global $connection;
   $sql = "SELECT * from `allocation`  where group_id ='$group_id'";
   //echo "$sql";
   $result = mysqli_query($connection, $sql);
   $num_row = mysqli_num_rows($result);

   if ($num_row > 0) {
      return false;
   } else {
      return true;
   }
}

function isDuplicate($year, $course_id, $class_id, $duration, $start_date, $end_date, $centre_code ) {
   global $connection;

   $sql="SELECT * from `group` where `year`='$year' and `start_date`='$start_date' and `end_date`='$end_date' and course_id='$course_id' and class_id='$class_id' and duration='$duration' and centre_code='$centre_code'";
   // echo  $sql; 
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      return true;
   } else {
      return false;
   }
}

if (isset($_POST['group_create'])) {
   foreach ($_POST as $key=>$value) {
      $$key=mysqli_real_escape_string($connection, $value);
   }

   //$course_name=$programme.$level.$module;
   $subject=$level;

   $course_id=getCourseID($subject);
//echo $course_id; die;
   if ($course_id!="") {
      if (!isDuplicate($year, $course_id, $group, $duration, $start_date, $end_date, $centre_code)) {
			
         $sql="INSERT into `group` (`year`, `course_id`, `class_id`, `start_date`, `end_date`, `start_time`, `end_time`, `centre_code`, `dow`, `dow1`, `start_time1`, `end_time1`, `dow2`, `start_time2`, `end_time2`, `dow3`, `start_time3`, `end_time3`, `duration`, `session`, `level`) values ('$year', '$course_id', '$group', '$start_date',
         '$end_date', '$start_time', '$end_time', '$centre_code', '$dow', '$dow1', '$start_time1', '$end_time1', '$dow2', '$start_time2', '$end_time2', '$dow3', '$start_time3', '$end_time3', '$duration', '$session', '$level')";
		//echo $sql;
         $result=mysqli_query($connection, $sql);

         if ($result) {
            echo "<div class='uk-alert uk-alert-success'>Record saved</div>";
         } else {
            echo "<div class='uk-alert uk-alert-danger'>Failed</div>";
         }
      } else {
         echo "<div class='uk-alert uk-alert-warning'>Duplicate record</div>";
      }
   }
}

?><div class="uk-width-1-1 myheader">
    <h2 class="text-center text-white myheader-text-style">Class Creation Form</h2>
</div>
<style>
form.nice-form p {
   opacity: .8;
   margin-bottom: .3em;
}

form.nice-form {
    font-size:
}

form.nice-form input, form.nice-form select {
   color: grey;
   border: 1px solid #d6d6d6;
   padding: 5px;
   border-radius: 5px;
}

form.nice-form input[type="submit"] {
   padding: 6px 30px;
   background:#fdba0c !important;
   border-radius: 8px;
   color:white;
   border: none;
   display: block;
    margin: 0 auto;
   margin-top: 1.3em;
   font-weight: bold;
    text-shadow: 0px 2px 2px #00000059;
    cursor: pointer;
  font-size: 1.1rem;
}
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
form.nice-form select {
    background: transparent;
    border: 1px solid rgba(0,0,0, .2);
    padding: 5px 10px;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    width: 100%;
}

.nice-form.form-2 {
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
    border-bottom-right-radius: 0px;
    border-bottom-left-radius: 0px;
}

.nice-form.form-2 tr:first-child {
    font-size: 1.1em;
    color: #585858;
}
.nice-form.form-2 tr:first-child td {font-weight: 400;}


.nice-form.form-2 tr:not(:first-child) {
    color: grey;
}

.nice-form.form-2 tr:not(:first-child):nth-of-type(even) {
    background: #f5f6ff;
}

.nice-form.form-2 tr:not(:first-child) td {border: none;}

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
<script>	 
function onProgrammeChange() {
   var programme=$("#programme").val();

   if (programme!="") {
      var s="";
      s=s+"<select name='module' id='module' class='uk-width-1-1'>";
      s=s+"<option value=''>Select Programme and Level First</option>";
      s=s+"</select>";

      $("#module").html(s);

      $.ajax({
         url : "admin/onProgrammeChange.php",
         type : "POST",
         data : "programme="+programme,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            $("#level").html(response);
         },
         error : function(http, status, error) {
            UIkit.notify("Error:"+error);
         }
      });
   }
}

function onLevelChange() {
   var level=$("#level").val();

   if ((level!="")) {
      $.ajax({
         url : "admin/onLevelChange.php",
         type : "POST",
         data : "level="+level,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            $("#group").html(response);
         },
         error : function(http, status, error) {
            UIkit.notify("Error:"+error);
         }
      });
   } else {
      UIkit.notify("Please select a level");
   }
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

</script>
<form action="../index.php?p=group_creation" class="nice-form" method="post" id="fGroupCreation">
    <div class="row">
        <div class="col-sm-12 col-md-2">
            <p>Start Date</p>
            <input type="text" name="start_date" id="start_date" class="datepicker-group" placeholder="Select" autocomplete="off" style="width: 100%;">
			<span id="validationstart_date" style="color: red; display: none;">Please insert Start Date</span>
        </div>
        <div class="col-sm-12 col-md-2">
            <p>End Date</p>
            <input type="text" name="end_date" id="end_date" class="datepicker-group" placeholder="Select" autocomplete="off" style="width: 100%;">
			<span id="validationend_date" style="color: red; display: none;">Please insert End Date</span>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-sm-12 col-md-2">
            <p>Duration</p>
            <select name="duration" id="duration" >
                <option value="">Select</option>
                <option value="Full Day">Full Day</option>
                <option value="Half Day">Half Day</option>
                <option value="3/4 Day">3/4 Day</option>
            </select>
			<span id="validationduration" style="color: red; display: none;">Please select Duration</span>
        </div>
        <div class="col-sm-12 col-md-2">
            <p>Student Entry Level</p>
           <select name="level" id="level" onchange="onLevelChange()">
                <option value="">Select</option>
                <option value="EDP">EDP</option>
                <option value="QF1">QF1</option>
                <option value="QF2">QF2</option>
                <option value="QF3">QF3</option>
            </select>
			<span id="validationlevel"  style="color: red; display: none;">Please select Level</span>
        </div>
        <div class="col-sm-12 col-md-2">
            <p>Class Name</p>
            <select name="group" id="group">
                <option value="">Select</option> 
            </select>
			<span id="validationgroup" style="color: red; display: none;">Please select Group</span>
			
        </div>
        <div class="col-sm-12 col-md-2">
            <p>AM/PM</p>
            <select name="session" id="session">
                <option value="">Select</option>
                <option value="am">AM & PM</option>
                <option value="am">AM</option>
                <option value="pm">PM</option>
            </select>
			<span id="validationsession" style="color: red; display: none;">Please select Session</span>
        </div>
       </div>
      <hr>
   
    <div class="d-flex flex-column flex-md-row font-weight-bold align-items-center">
      <div class="text-center text-md-left" style="width: 100px">Sessions</div>
      <div class="text-center text-md-left" style="width: 120px">Day</div>
      <div class="text-center text-md-left" style="width: 200px" class="pl-md-2">Start Time</div>
      <div class="text-center text-md-left" style="width: 200px" class="pl-md-2">End Time</div>
    </div>

    <div class="d-flex flex-column flex-md-row mt-2 align-items-center">
      <div style="width: 100px">Sessions 1</div>
      <div style="width: 110px">
        <select name="dow" id="dow">
           <option value="-1">Select</option>
          <option value="1">Daily</option>
          <option value="2">Monday</option>
          <option value="3">Tuesday</option>
          <option value="4">Wednesday</option>
          <option value="5">Thursday</option>
          <option value="6">Friday</option>
          <option value="7">Saturday</option>
          <option value="0">Sunday</option>
        </select>
		<span id="validationdow" style="color: red; display: none;">Please select Sessions 1</span>
      </div>
       <div style="width: 200px"class="pl-md-2"><input type="text" name="start_time" class="timepicker" placeholder="Start time" autocomplete="off" style="display:none"></div>
       <div style="width: 200px"class="pl-md-2"><input type="text" name="end_time" class="timepicker" placeholder="End time" autocomplete="off" style="display:none"></div>
    </div>

    <div class="d-flex flex-column flex-md-row mt-2 align-items-center">
      <div style="width: 100px">Sessions 2</div>
      <div style="width: 110px">
        <select name="dow1" id="dow1">
          <option value="-1">Select</option>
          <option value="1">Daily</option>
          <option value="2">Monday</option>
          <option value="3">Tuesday</option>
          <option value="4">Wednesday</option>
          <option value="5">Thursday</option>
          <option value="6">Friday</option>
          <option value="7">Saturday</option>
          <option value="0">Sunday</option>
        </select>
      </div>
      <div style="width: 200px" class="pl-md-2"><input type="text" name="start_time1" class="timepicker" placeholder="Start time" autocomplete="off" style="display:none"></div>
      <div style="width: 200px" class="pl-md-2"><input type="text" name="end_time1" class="timepicker" placeholder="End time" autocomplete="off" style="display:none"></div>
    </div>

    <div class="d-flex flex-column flex-md-row mt-2 align-items-center">
      <div style="width: 100px">Sessions 3</div>
      <div style="width: 110px">
        <select name="dow2" id="dow2">
           <option value="-1">Select</option>
          <option value="1">Daily</option>
          <option value="2">Monday</option>
          <option value="3">Tuesday</option>
          <option value="4">Wednesday</option>
          <option value="5">Thursday</option>
          <option value="6">Friday</option>
          <option value="7">Saturday</option>
          <option value="0">Sunday</option>
        </select>
      </div>
      <div style="width: 200px" class="pl-md-2"><input type="text" name="start_time2" class="timepicker" placeholder="Start time" autocomplete="off" style="display:none"></div>
      <div style="width: 200px" class="pl-md-2"><input type="text" name="end_time2" class="timepicker" placeholder="End time" autocomplete="off" style="display:none"></div>
    </div>

    <div class="d-flex flex-column flex-md-row mt-2 align-items-center">
      <div style="width: 100px">Sessions 4</div>
      <div style="width: 110px">
        <select name="dow3" id="dow3">
         <option value="-1">Select</option>
          <option value="1">Daily</option>
          <option value="2">Monday</option>
          <option value="3">Tuesday</option>
          <option value="4">Wednesday</option>
          <option value="5">Thursday</option>
          <option value="6">Friday</option>
          <option value="7">Saturday</option>
          <option value="0">Sunday</option>
        </select>
      </div>
      <div style="width: 200px" class="pl-md-2"><input type="text" name="start_time3" class="timepicker" placeholder="Start time" autocomplete="off" style="display:none"></div>
      <div style="width: 200px" class="pl-md-2"><input type="text" name="end_time3" class="timepicker" placeholder="End time" autocomplete="off" style="display:none"></div>
    </div>
<hr>
   <input type="submit" style="" value="Submit" name="group_create" id="group_create">
</form>

<script>
 $('document').ready(function(){
	  $("#fGroupCreation").submit(function(e) {
         var start_date = $("#start_date").val();
         var end_date = $("#end_date").val();
         var duration= $("#duration").val();
         var level=$("#level").val();
         var group = $("#group").val();
		 var session = $("#session").val();
         var dow = $("#dow").val();
         // var dow1=$("#dow1").val();
         // var dow2=$("#dow2").val();
         // var dow3=$("#dow3").val();

         if (!start_date || !end_date || !duration || !level || !group || !session || dow == '-1') {
            
            e.preventDefault();
            

            if (!start_date) {
               $('#validationstart_date').show();
            } else {
               $('#validationstart_date').hide();
            }

            if (!end_date) {
               $('#validationend_date').show();
            } else {
               $('#validationend_date').hide();
            }

            if (!duration) {
               $('#validationduration').show();
            } else {
               $('#validationduration').hide();
            }
			
			if (!level) {
            $('#validationlevel').show();
			}else{
				$('#validationlevel').hide();
			}
		
            if (!group) {
               $('#validationgroup').show();
            } else {
               $('#validationgroup').hide();
            }
			if (!session) {
               $('#validationsession').show();
            } else {
               $('#validationsession').hide();
            }
            if (dow == '-1') {
               $('#validationdow').show();
            } else {
               $('#validationdow').hide();
            }
            return false;
         }
      });
   $('#dow').on('change', function(){
     if( $(this).val() != '-1'){
       $('input[name="start_time"]').show();
       $('input[name="end_time"]').show();
     }else{
       $('input[name="start_time"]').val('').hide();
       $('input[name="end_time"]').val('').hide();
     }
   });

   $('#dow1').on('change', function(){
     if( $(this).val() != '-1'){
       $('input[name="start_time1"]').show();
       $('input[name="end_time1"]').show();
     }else{
       $('input[name="start_time1"]').val('').hide();
       $('input[name="end_time1"]').val('').hide();
     }
   });

   $('#dow2').on('change', function(){
     if( $(this).val() != '-1'){
       $('input[name="start_time2"]').show();
       $('input[name="end_time2"]').show();
     }else{
       $('input[name="start_time2"]').val('').hide();
       $('input[name="end_time2"]').val('').hide();
     }
   });

   $('#dow3').on('change', function(){
     if( $(this).val() != '-1'){
       $('input[name="start_time3"]').show();
       $('input[name="end_time3"]').show();
     }else{
       $('input[name="start_time3"]').val('').hide();
       $('input[name="end_time3"]').val('').hide();
     }
   });
 });
</script>

<div class="uk-width-1-1 myheader mt-5">
    <h2 class="uk-text-center myheader-text-color myheader-text-style">Searching</h2>
</div>

<form class="uk-form" name="frmSearch" id="frmSearch" method="get" action>
    <input type="hidden" name="p" value="group_creation">
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
            //generateSelectArray($fields_classes, "filter_course_id", //"class='uk-width-1-1'", $_GET['filter_course_id']);
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
        <!--<div class="uk-width-2-10 uk-text-small">
          <input type="text" name="filter_date" class="datepicker-group" placeholder="Select Date" autocomplete="off" style="width: 100%;" value="<?php // echo isset($_GET['filter_date']) ? $_GET['filter_date'] : ''; ?>">
        </div>-->
		<div class="uk-width-medium-1-10" style="padding-left:0px; padding-right:20px; width: 15%;">
		<input type="text" class="uk-width-1-1" name="date_from" id="date_from" placeholder="Date From" data-uk-datepicker="{format: 'YYYY-MM-DD'}" value="">
      </div>
      <div class="uk-width-medium-1-10" style="padding-left:20px; padding-right:20px; width: 15%;">
			<input type="text" class="uk-width-1-1" name="date_to" id="date_to" placeholder="Date To" data-uk-datepicker="{format: 'YYYY-MM-DD'}" value="">
      </div>
        <div class="uk-width-2-10">
            <button class="uk-button uk-width-1-1 full-width-blue">Search</button>
        </div>
    </div>
</form>

<div class="uk-width-1-1 myheader mt-5">
      <h2 class="uk-text-center myheader-text-color myheader-text-style">Classes</h2>
   </div>
<div class="nice-form">

<!-- New Table -->
<?php
$date_from=$_GET['date_from'];
$date_to=$_GET['date_to'];
//$sql="SELECT g.*, c.course_name from `group` g, course c where g.course_id=c.id";
$todayDate = date("Y-m-d");
   $sql = "SELECT g.*, c.course_name from `group` g, course c where g.course_id=c.id and g.`year`='$year'";
if (isset($_GET['filter_course_id']) && $_GET['filter_course_id'] != '') {
      $filter_course_id = mysqli_real_escape_string($connection, $_GET['filter_course_id']);
      $sql .= " and level='$filter_course_id' ";
   }

   if (isset($_GET['course_name']) && $_GET['course_name'] != '') {
      $course_name = mysqli_real_escape_string($connection, $_GET['course_name']);
      $sql .= " and course_name like '%$course_name%' ";
   }

   if (isset($_GET['filter_day']) && $_GET['filter_day'] != '') {
      $filter_day = mysqli_real_escape_string($connection, $_GET['filter_day']);
      $filter_day = getDOWIndex($filter_day);
	  //echo  $filter_day;
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
echo "    <td>No.</td>";
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
		 //echo $row_class_id;
         $row_course_id = $row["course_id"];

         $sql = "SELECT count(*) as no_of_student, c.course_name, c.subject, a.class_id, c.id, a.deleted from allocation a, course c, student s
   where a.course_id=c.id and a.student_id=s.id and s.student_status='A' and a.deleted=0 and a.`year`='$year' and a.group_id='$row_group_id' and a.class_id='$row_class_id' and a.course_id='$row_course_id' and s.centre_code='" . $_SESSION["CentreCode"] . "' group by course_id, class_id";
       //  echo $sql; 
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
         if ($row["end_date"] >= $todayDate) {
            echo "  <td><a " . $color_red . " href='index.php?p=allocation&group_id=$row_group_id&class=$row_class_id&course_id=$row_course_id&groupback'>" . $row["level"] . "</a></td>";
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
         if ($current_year == $year) {
            if ($row["end_date"] >= $todayDate) {
               echo "  <a href='index.php?p=allocation&group_id=$row_group_id&class=$row_class_id&course_id=$row_course_id&backa'><i data-uk-tooltip title='Allocate Student' class='fas fa-user text-primary'></i></a>";
               echo "  <a onclick='dlgTransAllocation(" . $param . ");'><i data-uk-tooltip title='Transfer Class' class='fas fa-exchange-alt text-success'></i></a>";
			   if(isDilitable( $row["id"] ) == true){
				   echo "  <a onclick='dlgDeleteGroup(" . $row["id"] . ");'><i data-uk-tooltip title='Delete Class' class='fas fa-trash text-danger'></i></a>";
				}
			}
         }
         echo "  </td>";
         echo "</tr>";
      }
}
echo "</tbody>";

echo "</table>";

?>










<!-- Old Table  -->


<!--    <table class="uk-table">
      <tr class="uk-text-small uk-text-bold">
         <td>Programme</td>
         <td>Group</td>
         <td>Start Date</td>
         <td>End Date</td>
         <td>Start Time</td>
         <td>End Time</td>
         <td>Day of Week</td>
      </tr> -->
 <?php
// $sql="SELECT * from `group` order by id";
// $result=mysqli_query($connection, $sql);
// $num_row=mysqli_num_rows($result);
// if ($num_row>0) {
//    while ($row=mysqli_fetch_assoc($result)) {
?>
<!--       <tr class="uk-text-small">
         <td><?php // echo getCourseName($row["course_id"])?></td>
         <td><?php // echo $row["class_id"]?></td>
         <td><?php // echo $row["start_date"]?></td>
         <td><?php // echo $row["end_date"]?></td>
         <td><?php // echo $row["start_time"]?></td>
         <td><?php // echo $row["end_time"]?></td>
         <td><?php // echo getDOWString($row["dow"])?></td>
      </tr> -->
<?php
//    }
// } else {
?>
<!--       <tr class="uk-text-small uk-text-bold">
         <td colspan="7">No record found</td>
      </tr> -->
<?php
// }
?>
   </table>
</div>
<script>
   $(document).ready(function () {
      $('.datepicker-group').datepicker({
         dateFormat: 'yy-mm-dd'
      });
      $('.timepicker').timepicker({
         timeFormat: 'h:mm p',
         interval: 5,
         minTime: '6',
         maxTime: '11:59pm',
         defaultTime: '6',
         startTime: '06:00',
         endTime: '00:00',

         dynamic: true,
         dropdown: true,
         scrollbar: true
      });
       $('#mydatatable').DataTable({
        language: { search: "" },
        "bInfo" : false,
		 'columnDefs': [ {
			'targets': [ 0, 7, 8 ], // column index (start from 0)
			'orderable': false, // set orderable false for selected columns
		 }]
       });
   })
   function dlgTransAllocation(group_id, class_id, course_id) {
      $.ajax({  
         url: "admin/dlgTransAllocation.php",
         type: "POST",
         data: "group_id=" + group_id + "&class_id=" + class_id + "&course_id=" + course_id,
         dataType: "text",
         beforeSend: function(http) {},
		 //console.log(response, status, http); 
         success: function(response, status, http) {
            $("#dlgTransAllocation").html(response);
            $("#dlgTransAllocation").dialog({
               dialogClass: "no-close",
               title: "Transfer Allocation",
               width: "auto",
               height: "auto"
            });
         },
         error: function(http, status, error) {
            UIkit.notify("Error:" + error);
         }
      });
   }
</script>
<div id="dlgTransAllocation"></div>