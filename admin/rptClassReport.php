



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

      case 1 : return "Monday"; break;

      case 2 : return "Tuesday"; break;

      case 3 : return "Wednesday"; break;

      case 4 : return "Thursday"; break;

      case 5 : return "Friday"; break;

      case 6 : return "Saturday"; break;

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

   if (($_SESSION["UserType"]=="O") || ($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="S")) {



   $course=$_POST["course"];

   $class=$_POST["class"];

   $student_name=$_POST["student_name"];



   $year=$_SESSION['Year'];

   if (($_SESSION["UserType"]=="S")) {

 $centre_code = $_GET["centre_code"];

   }else{

    $centre_code=$_SESSION["CentreCode"];

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



<div class="uk-margin-right uk-margin-top">



  <?php if( !empty($group_id) ){ ?>

    <?php

      $group_info = getGroupInfo($group_id, $programme, $level, $module, $get_class_id, $course_id);

      $group_period=convertDate2British($group_info["start_date"])." - ".convertDate2British($group_info["end_date"]);

    ?>

    <div class="uk-width-1-1 myheader text-center">

      <h2 class="uk-text-center myheader-text-color myheader-text-style"><?php echo $_GET['subject']."-".$_GET['class']."-".$_GET['duration'] ?></h2>

    </div>

    <div class="uk-overflow-container">

      <div class="uk-grid">

         <div class="uk-width-medium-5-10">

            <table class="uk-table">

               <tr>

                  <td class="uk-text-bold">Centre Name</td>

                  <?php 

                  $sql="SELECT company_name from centre where centre_code='$centre_code'";

                  $result=mysqli_query($connection, $sql);

                  $row=mysqli_fetch_assoc($result);

                  ?>

                  <td><?php echo $centre_code != 'ALL' ? $row['company_name'] : 'All Centre';?></td>

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

         <div class="uk-width-medium-5-10">

            <table class="uk-table">

               <tr>

                  <td class="uk-text-bold">Academic Year</td>

                  <td><?php echo $year?></td>

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
                        //$sql = "SELECT * from codes where year=" . $year;
						$sql = "SELECT * from codes where module='SCHOOL_TERM'";
                    if($month!="13"){
                      $sql .= " and from_month<=$month and to_month>=$month";
                    }
                    $sql .= " order by category";
                      //Print_r($sql);
                        $centre_result = mysqli_query($connection, $sql);
                        $str = "";
                      while ($centre_row = mysqli_fetch_assoc($centre_result)) {
                        // echo $centre_row['category'] . "/" . $centre_row['year'] . "<br>";
                        $str .= $centre_row['category'] . ', ';
                      }
                      echo rtrim($str, ", ");
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

    </div>

    <div class="nice-form text-center">

    <a href="admin/class_report.php?group_id=<?php echo $group_id ?>&class=<?php echo $get_class_id ?>&course_id=<?php echo $course_id ?>&subject=<?php echo $_GET['subject'] ?>&duration=<?php echo $_GET['duration'] ?>" target="_blank" class="uk-button full-width-blue" style="width:120px; float:right">Print</a>

      <h4><b>Period:</b> <?php echo $group_period; ?></h4>

      <div class=" table-responsive">

        <table class="table table-bordered">

          <thead>

            <tr class="uk-text-bold"><!--  class="uk-block-primary uk-text-contrast" -->

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

    </div>

      <br>

      <br>

    <div class="uk-width-1-1 myheader text-center">

      <h2 class="uk-text-center myheader-text-color myheader-text-style">Student Listing</h2>

    </div>  

    <div class="nice-form">

    

<?php



// $sql="SELECT s.*, group_concat(DISTINCT concat('(',sec.mobile_country_code, ') ',sec.mobile) ORDER BY  sec.id ASC SEPARATOR ', ') as contacts 

//   from allocation a, `group` g, student s left join student_emergency_contacts sec on sec.student_code=s.student_code 

//   where a.group_id='$group_id' and a.course_id='$course_id' and s.student_status='A'

//     and a.class_id='$get_class_id' and a.year='$year' and s.id=a.student_id and a.group_id=g.id and a.deleted=0 and s.centre_code='$centre_code' group by s.student_code";



$sql="SELECT s.*, group_concat(DISTINCT concat('(',sec.mobile_country_code, ') ',sec.mobile) ORDER BY  sec.id ASC SEPARATOR ', ') as contacts 

  from allocation a, `group` g, student s left join student_emergency_contacts sec on sec.student_code=s.student_code 

  where a.group_id='$group_id' and a.course_id='$course_id' 

    and a.class_id='$get_class_id' and a.year='$year' and s.id=a.student_id and a.group_id=g.id and a.deleted=0  group by s.student_code";



$result=mysqli_query($connection, $sql);

$num_row=mysqli_num_rows($result);

$ethnics = [];

$genders = [];

$count=0;

?>

      <div class=" table-responsive">

        <table class="uk-table">

          <thead>

            <tr class="uk-text-bold">

              <td>No</td>

              <td>Student Code</td>

              <td>Name</td>

              <td>Gender</td>

              <td>D.O.B</td>

              <td>Age</td>

              <td>Ethnicity</td>

              <td>Primary Contact</td>

              <td>Medical Condition</td>

              <td>Allergies</td>

              <td>Status</td>

              <td>Consent for social media</td>

            </tr>

          </thead>

          <tbody>

<?php

if ($num_row>0) {

  while ($row=mysqli_fetch_assoc($result)) {

  ?>

            <tr class="">

              <td><?php echo $count+1 ?></td>

              <td><?php echo $row['student_code'] ?></td>

              <td><?php echo $row['name'] ?></td>

              <td class="uk-text-center"><?php echo $row['gender'] ?></td>

              <td><?php echo date('d/m/Y', strtotime($row['dob'])) ?></td>

              <td class="uk-text-center"><?php echo $row['age'] ?></td>

              <td><?php echo $row['race'] ?></td>

              <td><?php echo $row['contacts'] ?></td>

              <td><?php echo $row['health_problem'] ?></td>

              <td><?php echo $row['allergies'] ?></td>

              <?php if($row['student_status'] == 'A'):?>

                <td>Active</td>

                <?php elseif($row['student_status'] == 'D'):?>

                  <td>Deferred</td>

                <?php elseif($row['student_status'] == 'G'):?>

                  <td>Graduated</td>

                <?php elseif($row['student_status'] == 'S'):?>

                  <td>Suspended</td>

                <?php elseif($row['student_status'] == 'T'):?>

                  <td>Transferred</td>

                <?php elseif($row['student_status'] == 'I'):?>

                  <td>Dropout</td>

                <?php endif;?>

              

              <td class="uk-text-center"><?php echo $row['accept_photo']==1 ? '&#10004;' : '' ?></td>

            </tr>

  <?php

    $genders[$row['gender']] = isset($genders[$row['gender']]) ? $genders[$row['gender']]+1 : 1;

    $ethnics[$row['race']] = isset($ethnics[$row['race']]) ? $ethnics[$row['race']]+1 : 1;

    $count++;

  }

} else {

?>

   <tr class="uk-text-small"><td colspan="11">No record found</td></tr>

<?php

}

?>

          </tbody>

        </table>

      </div>

    </div>

<?php   if($count>0){ ?>

      <br>

      <br>

    

    

      <div class="table-responsive">

        <table class="table table-bordered uk-text-center">

        <thead class="myheader" style="color:white;">

          <tr>

            <th  rowspan="2" style="font-size:20px;vertical-align:middle;">Total</th>

            <th  colspan="<?php echo count($genders) ?>" style="font-size:20px">Gender</th>

            <th  colspan="<?php echo count($ethnics) ?>" style="font-size:20px">Ethnicity</th>

          </tr>

          <tr>

            <?php foreach($genders as $gender=>$total){ echo '<th style="font-size:20px">'.$gender.'</th>'; } ?>

            <?php foreach($ethnics as $ethnic=>$total){ echo '<th style="font-size:20px">'.$ethnic.'</th>'; } ?>

          </tr>

        </thead>

        <tbody style="background-color: white">

          <tr>

            <td style="font-size:24px"><?php echo $count ?></td>

            <?php foreach($genders as $total){ echo '<td style="font-size:24px">'.$total.'</td>'; } ?>

            <?php foreach($ethnics as $total){ echo '<td style="font-size:24px">'.$total.'</td>'; } ?>

          </tr>

        </tbody>

        </table>

      </div>

    



<?php 

    } 

  }

?>



  

   



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

       });

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

