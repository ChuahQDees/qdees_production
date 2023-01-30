<style>
.page_title {
    position: absolute;
    right: 34px;
}
.uk-margin-right {
    margin-top: 40px;
}
</style>
<span>
    <span class="page_title"><img src="/images/title_Icons/Class Creation.png">Class Listing Report</span>
</span>

   <?php
   include_once("mysql.php");
   include_once("admin/functions.php");

   $group_id = isset($_GET["group_id"]) ? $_GET["group_id"] : '';
   $get_class_id = isset($_GET["class"]) ? $_GET["class"] : '';
   $get_course_id = isset($_GET["course_id"]) ? $_GET["course_id"] : '';
   $selected_month = isset($_GET['selected_month']) ? $_GET['selected_month'] : '';
   $centre_code = isset($_GET['centre_code']) ? $_GET['centre_code'] : '';

   function getDOWString($dow)
   {
      switch ($dow) {
         case 0:
            return "Sunday";
            break;
         case 1:
            return "Monday";
            break;
         case 2:
            return "Tuesday";
            break;
         case 3:
            return "Wednesday";
            break;
         case 4:
            return "Thursday";
            break;
         case 5:
            return "Friday";
            break;
         case 6:
            return "Saturday";
            break;
         case 7:
            return "Daily";
            break;
      }
   }

   function getCentreName($centre_code)
   {
      global $connection;

      $sql = "SELECT company_name from centre where centre_code='$centre_code'";
      $result = mysqli_query($connection, $sql);
      $row = mysqli_fetch_assoc($result);
      $num_row = mysqli_num_rows($result);

      if ($num_row > 0) {
         return $row["company_name"];
      } else {
         if ($centre_code == "ALL" || $centre_code=="") {
            return "All Centres";
         } else {
            return "";
         }
      }
   }

   if ($_SESSION["isLogin"] == 1) {
      if (($_SESSION["UserType"] == "O") || ($_SESSION["UserType"] == "A") || ($_SESSION["UserType"] == "S") || ($_SESSION["UserType"]=="H") || ($_SESSION["UserType"]=="C") || ($_SESSION["UserType"]=="R") || ($_SESSION["UserType"]=="CM") || ($_SESSION["UserType"]=="T")) {

         $course = isset($_POST["course"]) ? $_POST["course"] : '';
         $class = isset($_POST["class"]) ? $_POST["class"] : '';
         $student_name = isset($_POST["student_name"]) ? $_POST["student_name"] : '';

         if (($_SESSION["UserType"] == "S") || ($_SESSION["UserType"]=="H") || ($_SESSION["UserType"]=="C") || ($_SESSION["UserType"]=="R") || ($_SESSION["UserType"]=="CM") || ($_SESSION["UserType"]=="T")) {
            if (isset($_GET["centre_code"])) {
               $centre_code = $_GET["centre_code"];
            } else {
               $centre_code = 'ALL';
            }
         } else {
            $centre_code = $_SESSION["CentreCode"];
         }

         $current_year = date("Y");
         $low_year = $current_year - 1;
         $high_year = $current_year + 2;
         $loop_year = '';
         for ($i = $low_year; $i <= $high_year; $i++) {
            $loop_year .= "$i,";
         }
         $loop_year = substr($loop_year, 0, -1);
   ?>

         <link rel="stylesheet" type="text/css" href="lib/monthpicker/jquery.monthpicker.css">
         <script src="lib/monthpicker_all/jquery.monthpicker.js?v=1"></script>
         <script>
            function dlgTransAllocation(group_id, class_id, course_id) {
               $.ajax({
                  url: "admin/dlgTransAllocation.php",
                  type: "POST",
                  data: "group_id=" + group_id + "&class_id=" + class_id + "&course_id=" + course_id,
                  dataType: "text",
                  beforeSend: function(http) {},
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

            function dlgDeleteGroup(group_id) {
               UIkit.modal.confirm("<h2>Confirm delete?</h2>", function() {
                  $.ajax({
                     url: "admin/delete_group.php",
                     type: "POST",
                     data: "group_id=" + group_id,
                     dataType: "text",
                     beforeSend: function(http) {},
                     success: function(response, status, http) {
                        $('#row-id-' + group_id).remove();
                     },
                     error: function(http, status, error) {
                        UIkit.notify("Error:" + error);
                     }
                  });
               });
            }

            function getAllStudent(pg, student_name, allocation_status, group_id, class_id, course_id) {
               $.ajax({
                  url: "admin/get_all_student.php",
                  type: "POST",
                  data: "student_name=" + student_name + "&pg=" + pg + "&allocation_status=" + allocation_status + "&group_id=" + group_id + "&class_id=" + class_id + "&course_id=" + course_id,
                  dataType: "text",
                  beforeSend: function(http) {},
                  success: function(response, status, http) {
                     $("#AllStudent").html(response);
                  },
                  error: function(http, status, error) {
                     UIkit.notify("Error:" + error);
                  }
               });
            }

            function getSelectedStudent(group_id, class_id, course_id) {
               $.ajax({
                  url: "admin/get_selected_student.php",
                  type: "POST",
                  data: "group_id=" + group_id + "&class_id=" + class_id + "&course_id=" + course_id,
                  dataType: "text",
                  beforeSend: function(http) {},
                  success: function(response, status, http) {
                     $("#SelectedStudent").html(response);
                  },
                  error: function(http, status, error) {
                     UIkit.notify("Error:" + error);
                  }
               });
            }

            // function getSelectedStudentName(precourse) {
            //    $.ajax({
            //       url : "admin/get_selected_student.php",
            //       type : "POST",
            //       data : "pre_course=" + precourse + "&year="+<?php echo $year ?>,
            //       dataType : "text",
            //       beforeSend : function(http) {
            //       },
            //       success : function(response, status, http) {
            //          $("#SelectedStudent").html(response);
            //       },
            //       error : function(http, status, error) {
            //          UIkit.notify("Error:"+error);
            //       }
            //    });
            // }

            function findClassData(the_class, programme, level, the_module, allocation_status) {
               if ((the_class != "") & (programme != "") & (level != "") & (the_module != "")) {
                  getAllStudent('<?php echo $pg ?>', "", allocation_status, $_GET["group_id"], $_GET["class"], $_GET["course_id"]);
                  getSelectedStudent($_GET["group_id"], $_GET["class"], $_GET["course_id"]);
               } else {
                  UIkit.notify("Please fill in programme, level, module and group");
               }
            }

            function getGroupID(programme, level, the_module, class_id) {
               if ((programme != "") & (level != "") & (the_module != "") & (class_id != "")) {
                  $.ajax({
                     url: "admin/getGroupID.php",
                     type: "POST",
                     data: "programme=" + programme + "&level=" + level + "&module=" + the_module + "&class_id=" + class_id,
                     dataType: "text",
                     async: false,
                     beforeSend: function(http) {},
                     success: function(response, status, http) {
                        var s = response.split("|");
                        if (s[0] == "0") {
                           $("#get_group_result").val(s[0]);
                           $("#group_id").val(s[1]);
                        } else {
                           $("#get_group_result").val(s[0]);
                           $("#group_id").val(s[1]);
                        }
                     },
                     error: function(http, status, error) {
                        UIkit.notify("Error:" + error);
                     }
                  });
               } else {
                  return "0";
               }
            }

            function generateReport(method) {
                var centre_code=$('centre_code').val();
				//var centre_code=document.getElementById('screens.screenid').value;
            var centre_code=document.getElementById('hfCenterCode').value;	       
  
               var path = window.location.href;
               path = path.split("?");
               window.location.href = path[0] + "?p=rpt_class_listing&centre_code=" + centre_code;
            }

            $(document).ready(function() {
               var today = new Date();
               //$("#selected_month").val(today.getFullYear() + '' + (("0" + (today.getMonth() + 1)).slice(-2)));

               

               $("#btnSearch").click(function() {
                  var programme = $("#programme").val();
                  var level = $("#level").val();
                  var the_module = $("#module").val();
                  var the_class = $("#class").val();
                  var allocation_status = $("#allocation_status").val();

                  if ((programme != "") & (level != "") & (the_module != "") & (the_class != "")) {
                     getGroupID(programme, level, the_module, the_class);

                     if ($("#get_group_result").val() == "1") {
                        findClassData(the_class, programme, level, the_module, allocation_status);
                     } else {
                        UIkit.notify($("#group_id").val());
                     }
                  } else {
                     UIkit.notify("Please fill up all fields");
                  }
               });
            });

            $(function() {
				var year = '<?php echo $_SESSION["Year"]; ?>';
               $('#month').monthpicker({
				  years: [year],
                  topOffset: 6,
                  //SelectedYear: 2020,
                  StartMonth: 01,
                  onMonthSelect: function(m, y) {
                     var month = m + 1;

                     if (month < 10) {
                        month = "0" + month;
                     }

                     $("#selected_month").val(y + month);
                  }
               });
               <?php $selected_month = $_GET['selected_month'];
               if($selected_month!=""){
                  $str_length = strlen($selected_month);
                  $month = substr($selected_month, ($str_length - 2), 2);
               $year = substr($selected_month, 0, -2);
               ?>
               $('#month').text(GetMonthName(<?php echo $month?>)+ " " +"<?php echo $year?>");
               <?php }
               ?>
            });
            function GetMonthName(monthNumber) {
               var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December','Jan', 'All'];
               return months[monthNumber - 1];
         }
         </script>

         <div style="margin-top:40px;" class="uk-width-1-1 myheader">
            <h2 class="uk-text-center myheader-text-color myheader-text-style">Searching</h2>
         </div>

         <form class="uk-form" name="frmStudentSearch" id="frmStudentSearch" method="get">
            <input type="hidden" name="p" id="p" value="rpt_class_listing">

            <div class="uk-grid">
					
               <div class="uk-width-medium-2-10">
                  <?php

                  $sql = "SELECT * from centre order by centre_code";
                  $result = mysqli_query($connection, $sql);
                  if (($_SESSION["UserType"] == "S") || ($_SESSION["UserType"]=="H") || ($_SESSION["UserType"]=="C") || ($_SESSION["UserType"]=="R") || ($_SESSION["UserType"]=="CM") || ($_SESSION["UserType"]=="T")) {
                  ?>

                     Centre Name<br>
                     <input type="hidden"  id="hfCenterCode" name="centre_code" value="<?php echo $_GET["centre_code"] ?>">
                  <input list="centre_code" id="company_name" name="company_name" value="<?php echo $_GET["company_name"] ?>">
                     <!-- <input list="centre_code" id="screens.screenid" name="centre_code" value="<?php echo $_GET['centre_code']?>"> -->

            <datalist class="form-control" id="centre_code" style="display: none;">

              <option value="ALL" <?php echo $centreCode == 'ALL' ? 'selected' : '' ?>>All Centre</option>

              <?php

              // foreach ($get_centreCodes as $key => $get_centreCode) {

               while ($row = mysqli_fetch_assoc($result)) {
                  ?>
                     <option value="<?php echo $row['company_name'] ?>" <?php echo $row['company_name'] == $centre_code ? 'selected' : '' ?>><?php echo $row["centre_code"] ?></option>
                  <?php
                  }

              ?>

            </datalist>
            <script>
                      $(document).on('change', '#company_name', function(){
                          var options = $('datalist')[0].options;
                          for (var i=0;i<options.length;i++){
                         // console.log(options[i].text)
                            if (options[i].value == $(this).val()) 
                              {
                                $("#hfCenterCode").val(options[i].text);
                                break;
                                }
                          }
                      });
                    </script>

                     <!-- <select name="centre_code" id="centre_code" class='uk-width-1-1'>
                        <option value="ALL" <?php echo $centre_code == 'ALL' ? 'selected' : '' ?>>ALL</option>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                           <option value="<?php echo $row['centre_code'] ?>" <?php echo $row['centre_code'] == $centre_code ? 'selected' : '' ?>><?php echo $row["company_name"] ?></option>
                        <?php
                        }
                        ?>
                     </select> -->

                  <?php
                  } else { ?> Centre Name<br> <input type="hidden" name="centre_code" id="centre_code" value="<?php echo $_SESSION['CentreCode'] ?>">
                     <!--<input class="uk-width-medium-1-1" type="text" value="<?php // echo getCentreCompanyName($_SESSION['CentreCode'])
                                                                                 ?>" readonly>--> <span type="text" class="uk-width-medium-1-1 uk-text-bold"><?php echo getCentreCompanyName($_SESSION['CentreCode']) ?></span> <?php     }
                                                                                                                                                                                                                                                                                          ?> </div>
               <div class="uk-width-2-10" style="width: auto;">
                  Month & year Selection<br>
                     <select name="selected_month" id="selected_month" >
                     <option value="" >All Months</option>
                        <?php
                           $period = getMonthList($year_start_date, $year_end_date);
                           $months = array();
                       
                           foreach ($period as $dt) {
                        ?>
                              <option value="<?php echo $dt->format("Ym"); ?>" <?php if(isset($_GET['selected_month']) && $_GET['selected_month'] == $dt->format("Ym")) { echo "selected"; } ?>><?php echo $dt->format("M Y"); ?></option>
                        <?php
                               $months[$dt->format("M Y")] = $dt->format("Y-m");
                           }
                        ?>
                     </select>
                <!--   <a class="uk-button" id="month">Pick a Month</a> -->
                  <!-- <input type="hidden" name="selected_month" id="selected_month" value="<?php echo $_GET["selected_month"]?>"> -->
               </div>
			   <!--<div style="width: 16%;" class="uk-width-medium-2-10">
				 From Date<br>
				 <input data-uk-datepicker="{format:'DD/MM/YYYY', minDate: '<?php echo date('d/m/Y',strtotime($year_start_date)); ?>', maxDate: '<?php echo date('d/m/Y',strtotime($year_end_date)); ?>'}" placeholder="From Date" name="df" id="df" type="text" value="" autocomplete="off" readonly>
			  </div>
			  <div style="width: 16%;" class="uk-width-medium-2-10">
				 To Date<br>
				 <input data-uk-datepicker="{format:'DD/MM/YYYY', minDate: '<?php echo date('d/m/Y',strtotime($year_start_date)); ?>', maxDate: '<?php echo date('d/m/Y',strtotime($year_end_date)); ?>'}" placeholder="To Date" name="dt" id="dt" type="text" value="" autocomplete="off" readonly>
			  </div>-->
               <div class="uk-width-2-10">
					<span style="white-space: nowrap;">Status</span><Br>
					<!--<select style="width: -webkit-fill-available;"  name="fields_status" id="fields_status"  >
					   <option value="ALL">ALL</option>
					   <option <?php // if($_GET['fields_status']=="Active") echo "Selected"?> value="Active">Active</option>
					   <option <?php // if($_GET['fields_status']=="Expired") echo "Selected"?> value="Expired">Expired</option>
					</select>-->
					<?php
					   $fields_status=array("Active"=>"Active", "Expired"=>"Expired");
                  $fields_status = isset($_GET['fields_status']) ? $_GET['fields_status'] : '';
						generateSelectArray($fields_status, "fields_status", "class='uk-width-1-1'", $fields_status);
					 ?>
               </div>
              
               <div class="uk-width-2-10" style="white-space: nowrap;">
                  <br>
                  <button class="uk-button">Show on screen</button>
             
                  <?php
                  if (isset($_GET['selected_month']) && $_GET['selected_month'] != '13') {
                  ?>
                     <a href="admin/class_listing_report.php?centre_code=<?php echo $centre_code ?>&selected_month=<?php echo $_GET['selected_month'] ?>&fields_status=<?php echo $_GET['fields_status'] ?>" target="_blank" class="uk-button ">Print</a>
                  <?php
                  } else {
                  ?>
                     <a href="admin/class_listing_report.php?centre_code=<?php echo $centre_code ?>&fields_status=<?php echo $_GET['fields_status'] ?>" target="_blank" class="uk-button ">Print</a>
                  <?php
                  }
                  ?>
               </div>
            </div>
         </form>
         <br>

         <div class="uk-margin-right uk-margin-top">
            <div class="uk-width-1-1 myheader">
               <h2 class="uk-text-center myheader-text-color myheader-text-style">Class Listing Report</h2>
            </div>

            <div class="uk-grid" style="background-color: white;">
               <div class="uk-width-medium-5-10">
                  <table class="uk-table">
                     <tr>
                        <td class="uk-text-bold">Centre Name</td>
                        <td><?php echo getCentreName($centre_code) ?></td>
                     </tr>
                     <tr>
                        <td class="uk-text-bold">Prepare By</td>
                        <td><?php echo $_SESSION["UserName"] ?></td>
                     </tr>
                     <tr>
                        <td colspan="2" class="uk-text-bold">NOTE: To be submitted termly with the order form</td>
                     </tr>
                  </table>
               </div>
               <div class="uk-width-medium-5-10">
                  <table class="uk-table">
                  <tr>
                     <td class="uk-text-bold">Academic Year</td>
                     <?php 
                        if(isset($year) && $year != $_SESSION['Year'])
                        {
                           $selected_year = getYearFromMonth($year, $month);
                        }
                     if(isset($request_Year)):?>
                     <td><?php echo $selected_year; ?></td>
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
                              if (isset($selected_month) && $selected_month != '') {
                                 $year_length = strlen($selected_month);
                                 $month = substr($selected_month, ($year_length - 2), 2);
                                 $year = substr($selected_month, 0, -2);
                              }

                              echo getTermFromDate(date('Y-m-d',strtotime($year.'-'.$month)));
                           ?>
                     </td>
                     </tr>
                     <tr>
                        <td class="uk-text-bold">Date of submission</td>
                        <td><?php echo date("Y-m-d H:i:s") ?></td>
                     </tr>
                  </table>
               </div>
            </div>
   
<div class="nice-form">
<?php
   $date_from=isset($_GET['date_from']) ? $_GET['date_from'] : '';
   $date_to=isset($_GET['date_to']) ? $_GET['date_to'] : '';
   $todayDate = date("Y-m-d");
   if(isset($selected_year)) {
      $sql = "SELECT g.*, c.subject from `group` g, course c where g.course_id=c.id and g.`year`='$selected_year'";
   } else {
      $sql = "SELECT g.*, c.subject from `group` g, course c where g.course_id=c.id and g.`year`='".$_SESSION['Year']."'";
   }

   if (isset($_GET['filter_course_id']) && $_GET['filter_course_id'] != '') {
      $filter_course_id = mysqli_real_escape_string($connection, $_GET['filter_course_id']);
      $sql .= " and level='$filter_course_id' ";
   }

   if (isset($_GET['course_name']) && $_GET['course_name'] != '') {
      $course_name = mysqli_real_escape_string($connection, $_GET['course_name']);
      $sql .= " and subject like '%$course_name%' ";
   }
  if($_GET['selected_month']!=""){
   
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

   if (isset($_GET['fields_status']) && $_GET['fields_status'] != '') {
      $fields_status = mysqli_real_escape_string($connection, $_GET['fields_status']);
      if ($fields_status == 'Expired') {
         $sql .= " and end_date < '$todayDate'";
      } else {
         $sql .= " and end_date >= '$todayDate'";
      }
   } else {
      //$sql = "SELECT g.*, c.subject from `group` g, course c where g.course_id=c.id";
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


 //echo $sql; die;
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


// $(document).ready(function(){
  // $('.datepicker-group').datepicker({
     // dateFormat: 'yy-mm-dd'
  // });
    // $('#mydatatable').DataTable({
        // language: { search: "" },
         // "bInfo" : false,
       // });
// });
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

 <style>
   form[name="frmAllocation"] tr:first-child {
      font-size: 1.1em;
      color: #585858;
   }

   form[name="frmAllocation"] tr:first-child td {
      font-weight: 400;
      text-align: center;
   }


   form[name="frmAllocation"] tr:not(:first-child) {
      color: grey;
      text-align: center;
   }

   form[name="frmAllocation"] tr:not(:first-child):nth-of-type(even) {
      background: #f5f6ff;
   }

   form[name="frmAllocation"] tr:not(:first-child) td {
      border: none;
   }

   form[name="frmAllocation"] a,
   form[name="frmAllocation"] a:hover {
      color: blue;
      list-style-type: none
   }

   #mydatatable_length {
      display: none;
   }

   #mydatatable_paginate {
      float: initial;
      text-align: center
   }

   #mydatatable_paginate .paginate_button {
      display: inline-block;
      min-width: 16px;
      padding: 3px 5px;
      line-height: 20px;
      text-decoration: none;
      -moz-box-sizing: content-box;
      box-sizing: content-box;
      text-align: center;
      background: #f7f7f7;
      color: #444444;
      border: 1px solid rgba(0, 0, 0, 0.2);
      border-bottom-color: rgba(0, 0, 0, 0.3);
      background-origin: border-box;
      background-image: -webkit-linear-gradient(top, #ffffff, #eeeeee);
      background-image: linear-gradient(to bottom, #ffffff, #eeeeee);
      text-shadow: 0 1px 0 #ffffff;
      margin-left: 3px;
      margin-right: 3px
   }

   #mydatatable_paginate .paginate_button.current {
      background: #009dd8;
      color: #ffffff !important;
      border: 1px solid rgba(0, 0, 0, 0.2);
      border-bottom-color: rgba(0, 0, 0, 0.4);
      background-origin: border-box;
      background-image: -webkit-linear-gradient(top, #00b4f5, #008dc5);
      background-image: linear-gradient(to bottom, #00b4f5, #008dc5);
      text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.2);
   }

   #mydatatable_filter {
      width: 100%
   }

   #mydatatable_filter label {
      width: 100%;
      display: inline-flex
   }

   #mydatatable_filter label input {
      height: 30px;
      width: 100%;
      padding: 4px 6px;
      border: 1px solid #dddddd;
      background: #ffffff;
      color: #444444;
      -webkit-transition: all linear 0.2s;
      transition: all linear 0.2s;
      border-radius: 4px;
   }

   .row-action a i {
      font-size: 1.3rem;
      margin-right: 5px;
   }

   #mydatatable_filter {
      display: none;
   }
</style>