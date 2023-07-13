<!--<?php// if($_GET["mode"]==""){?>
		<a href="/">
		 <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
		</a>
        <?php// }else{ ?>
	<a href="/index.php?p=student_population">
	   <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
	</a>
<?php// } ?>-->
<span style="position: absolute;right: 35px;">
    <span class="page_title"><img src="images/title_Icons/Student Population-1.png">Student Population</span>
</span>
<style>
  .uk-grid > * {
      padding-right: 0px;
  }

  @media (min-width: 1220px) {
    .uk-grid > * {
      padding-left: 25px;
    }
  }
  
</style>
<?php
include_once("admin/functions.php");

function getTotalStudents($centre_code){
  global $connection;

  $sql = "SELECT COUNT(id) as total FROM student  WHERE centre_code ='". mysqli_real_escape_string($connection,$centre_code) ."' AND deleted=0";
  $result=mysqli_query($connection, $sql);
  $row=mysqli_fetch_assoc($result);

  return $row['total'];
}

function displayStudentStatus($status){
   switch($status){
      case "A":
      return "Active";
    case "D":
      return "Deferred";
    case "G":
      return "Graduated";
    case "I":
      return "Dropout";
    case "S":
      return "Suspended";
    case "T":
      return "Transferred";
      default:
         return $status;
   }
}

function getStudentStatusCount($centre_code, $status, $with_deleted = false){
  global $connection;
  $year=$_SESSION['Year'];

  //$sql = "SELECT COUNT(id) as total FROM student WHERE centre_code ='". mysqli_real_escape_string($connection,$centre_code) ."' AND student_status='" . mysqli_real_escape_string($connection,$status) . "' and year(start_date_at_centre) <= '$year' and extend_year >= '$year'";
  
  // $sql = "SELECT COUNT(id) as total FROM student WHERE ((deleted=0 and student_status<>'I') or (deleted=1 and exists(select * from dropout d where d.student_code=student.student_code and centre_code='".mysqli_real_escape_string($connection,$centre_code)."'))) and centre_code ='". mysqli_real_escape_string($connection,$centre_code) ."' AND student_status='" . mysqli_real_escape_string($connection,$status) . "' and extend_year = '$year'";
	
	// $sql = "SELECT COUNT(id) as total FROM student WHERE student_status='$status' and extend_year = '$year' ";
	
  $sql="SELECT COUNT(id) as total from student where student_status = '$status' and extend_year >= '$year' and centre_code='".$_SESSION["CentreCode"]."' ";


  if ($with_deleted|| $status=="I") {
    $sql .= " AND deleted=1";
  }else{
     $sql .= " AND deleted=0";
  }
  // $sql .= " GROUP BY student_code";
  // echo $sql; //die;
  $result=mysqli_query($connection, $sql);
  $row=mysqli_fetch_assoc($result);

  return $row['total'];
}

function getDropoutCount($centre_code){
  global $connection;
  global $year_end_date;
  $year=$_SESSION['Year'];

  $sql = "SELECT COUNT(d.id) as total FROM dropout d, student s where d.student_code=s.student_code and s.student_status= 'I' and d.centre_code ='". mysqli_real_escape_string($connection,$centre_code) . "' and s.start_date_at_centre <= '".$year_end_date."' and extend_year >= '$year'";

  $result=mysqli_query($connection, $sql);
  $row=mysqli_fetch_assoc($result);

  return $row['total'];
}

if ($_SESSION["isLogin"]==1) {
    if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) &
        (hasRightGroupXOR($_SESSION["UserName"], "StudentEdit|StudentView"))) {

        include_once("lib/pagination/pagination.php");
        $p=$_GET["p"];
        $m=$_GET["m"];
        $get_sha1_id=$_GET["id"];
        $pg=$_GET["pg"];
        $mode=$_GET["mode"];
        $year=$_SESSION['Year'];
        $current_year=date("Y");

        include_once("student_func.php");

        // $numperpage=25;
        $query="p=$p&m=$m&s=$s";
		//$status= displayStudentStatus('I');
        $str=$_GET["s"];
        $student_status=$_GET["student_status"];
        
        $where = '';

        if($_GET['country'] != '') { $where .= " and s.country = '".$_GET['country']."'"; }
        if($_GET['state'] != '') { $where .= " and s.state = '".$_GET['state']."'"; }
        if($_GET['race'] != '') { $where .= " and s.race = '".$_GET['race']."'"; }
        if($_GET['age'] != '') { $where .= " and (".date('Y',strtotime($year_start_date))." - YEAR(s.dob)) = '".$_GET['age']."'"; }
       
?>

        <script>
          function doDeleteRecord(id) {
              UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function () {
                  $("#id").val(id);
                  $("#frmDeleteRecord").submit();
              });
          }

          function doTransferStudent(id) {
             UIkit.modal.confirm("<h2>Do you confirm to transfer this student to next year?</h2>", function () {
                $.ajax({
                   url : "admin/transfer_student.php",
                   type : "GET",
                   data : "inquiry=tranfer_student&student_sid="+id,
                   dataType : "text",
                   beforeSend : function(http) {
                   },
                   success : function(response, status, http) {
                      var s=response.split("|");
                      UIkit.notify(s[1]);
                      location.reload();
                   },
                   error : function(http, status, error) {
                      UIkit.notification("Error:"+error);
                   }
                });
             })
          }

          function doTransferMultipleStudent() {

            var len = $(".transfer_student:checkbox:checked").length;

            if(len > 0)
            {
              if(len <= 10)
              {
                UIkit.modal.confirm("<h2>Do you confirm to transfer these students to next year?</h2>", function () {

                var id = $(".transfer_student:checkbox:checked").map(function(){
                  return $(this).val();
                }).get(); 

                $.ajax({
                    url : "admin/transfer_student.php",
                    type : "GET",
                    data : "inquiry=tranfer_multiple_student&student_sid="+id,
                    dataType : "text",
                    beforeSend : function(http) {
                    },
                    success : function(response, status, http) {
                        var s=response.split("|");
                        UIkit.notify(s[1]);
                        location.reload();
                    },
                    error : function(http, status, error) {
                        UIkit.notification("Error:"+error);
                    }
                  });
                })
              }
              else 
              {
                alert("You can't select more then 10 student");
              }
            }
            else
            {
              alert('Please select student');
            }
          }

          function dlgDeleteStudent(id) {
            UIkit.modal.confirm("<h2>Are you sure you want to send request for delete this student?</h2>", function () {
                $.ajax({
                   url : "admin/dlgDeleteStudent.php",
                   type : "GET",
                   data : "request=send_request&student_sid="+id,
                   dataType : "text",
                   beforeSend : function(http) {
                   },
                   success : function(response, status, http) {
                    if (response != "") {
                        $("#dlgDeleteStudent").html(response);
                        $("#dlgDeleteStudent").dialog({
                            dialogClass: "q-dialog",
                            title: "Delete Student",
                            modal: true,
                            height: '250',
                            width: "60%",
                        });
                    }
                   },
                   error : function(http, status, error) {
                      UIkit.notification("Error:"+error);
                   }
                });
             })
          }

        </script>
  <div id="dlgDeleteStudent"></div>
        <div style="margin-top: 45px;" class="uk-margin-right">
         <!--   <h3>Total Students: <?php echo getTotalStudents($_SESSION["CentreCode"]); ?></h3>-->
		
            <table class="table table-bordered mb-4" style="text-align:center;">
              <thead class="myheader">
                <tr>
                  <th style="color: #fff; font-size: 20px">Active</th>
                  <th style="color: #fff;  font-size: 20px">Deferred</th>
                  <th style="color: #fff;  font-size: 20px">Graduated</th>
                  <th style="color: #fff;  font-size: 20px">Dropout</th>
                  <th style="color: #fff; font-size: 20px">Suspended</th>
                  <th style="color: #fff;  font-size: 20px">Transfered</th>
                </tr>
              </thead>
              <tbody>
               <tr style="background-color: white">
                  <td style="font-size: 24px;  border-right: 1px solid #d3dbe2!important"><?php echo getStudentStatusCount($_SESSION["CentreCode"], 'A'); ?></td>
                  <td style="font-size: 24px;  border-right: 1px solid #d3dbe2!important"><?php echo getStudentStatusCount($_SESSION["CentreCode"], 'D'); ?></td>
                  <td style="font-size: 24px;  border-right: 1px solid #d3dbe2!important"><?php echo getStudentStatusCount($_SESSION["CentreCode"], 'G'); ?></td>
				<td style="font-size: 24px;  border-right: 1px solid #d3dbe2!important"><?php echo getDropoutCount($_SESSION["CentreCode"]); ?></td>				  
                 <td style="font-size: 24px;  border-right: 1px solid #d3dbe2!important"><?php echo getStudentStatusCount($_SESSION["CentreCode"], 'S'); ?></td>
                  <td style="font-size: 24px;  border-right: 1px solid #d3dbe2!important"><?php echo getStudentStatusCount($_SESSION["CentreCode"], 'T'); ?></td>
                </tr>
              </tbody>
            </table>
      <br>
            <div class="uk-width-1-1 myheader">
                <h2 class="uk-text-center myheader-text-color myheader-text-style">Searching</h2>
            </div>

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
            <form class="uk-form" name="frmStudentSearch" id="frmStudentSearch" method="get">
                <input type="hidden" name="p" id="p" value="student_population">
                <input type="hidden" name="mode" id="mode" value="BROWSE">
                <input type="hidden" name="m" id="m" value="<?php echo $m?>">
                <input type="hidden" name="pg" value="">

                <div class="uk-grid">
                    <div class="uk-text-small" style="width:18%;" >
                        <input class="uk-width-1-1" placeholder="Student Code/Name/Tel/Email" name="s" id="s" value="<?php echo $_GET['s']?>">
                    </div>
                    <div class="uk-text-small" style="width:12%;">
                     <?php
                     $fields=array("A"=>"Active", "D"=>"Deferred", "G"=>"Graduated", "I"=>"Dropout", "S"=>"Suspended", "T"=>"Transferred");
                     generateSelectArray($fields, "student_status", "class='uk-width-1-1'", $_GET["student_status"]);
                     ?>
                    </div>
                    <!--<div class="uk-text-small" style="width:12%;">
                      <select name="country" id="country" class="uk-width-1-1 ">
                        <option value="">Select Country</option>
                        <?php
                          $sql="SELECT * from codes where module='COUNTRY' order by code";
                          $result=mysqli_query($connection, $sql);
                          while ($row=mysqli_fetch_assoc($result)) {
                        ?>
                        <option value="<?php echo $row["code"]?>"
                            <?php if ($row["code"]==$_GET["country"]) {echo "selected";} ?>>
                            <?php echo $row["code"]?></option>
                        <?php } ?>
                      </select>
                    </div>-->
                    <div class="uk-text-small" style="width:16%;">
                      <select name="state" id="state" class="uk-width-1-1 ">
                        <?php// if ($_GET['country'] != '') { ?>
                          <option value="">State</option>
                        <?php
                          $sql="SELECT * from codes where country='Malaysia' and module='STATE' order by code";
                          $result=mysqli_query($connection, $sql);
                          while ($row=mysqli_fetch_assoc($result)) {
                        ?>
                        <option value="<?php echo $row['code']?>"
                            <?php if (isset($_GET['state']) && $row["code"]==$_GET['state']) { echo "selected"; }?>>
                            <?php echo $row["code"]?></option>
                        <?php
                       }
                       // } else {
                        ?> 
                        <?php// } ?>
                      </select>
                    </div>
                    <div class="uk-text-small" style="width:16%;">
                    <select name="race" id="race" class="uk-width-1-1 ">
					 <option value="">Race</option>
					 <option value="Malay">Malay</option>
					 <option value="Indian">Indian</option>
					 <option value="Chinese">Chinese</option>
					 <option value="Others">Others</option>
                      <?php /*if ($_GET["country"]!="") { ?>
                          <option value="">select race</option>
                        <?php
                           $sql="SELECT * from codes where country='Malaysia' and module='RACE' order by code";
                           $result=mysqli_query($connection, $sql);
                           while ($row=mysqli_fetch_assoc($result)) {
                        ?>
                          <option value="<?php echo $row['code']?>"
                              <?php if ($row["code"]==$_GET['race']) {echo "selected";}?>>
                              <?php echo $row["code"]?></option>
                          <?php } ?>
                          <option value="Others"
                              <?php if ($_GET['race']=="Others") {echo "selected";}?>>Others
                          </option>
                          <?php } else { ?>
                          <option value="">Please Select Country First</option>
                          <?php }*/ ?>
                      </select>
                    </div>
                    <div class="uk-text-small" style="width:10%;"> 
						  <select name="age" id="age" class="uk-width-1-1 ">
                      
                          <option value="">Age</option>
                        <?php
                          $sql="SELECT MIN(".date('Y',strtotime($year_start_date))." - YEAR(dob)) as min_age, MAX(".date('Y',strtotime($year_start_date))." - YEAR(dob)) as max_age from student where centre_code='".$_SESSION["CentreCode"]."' and extend_year >= '$year'";
                          $result=mysqli_fetch_array(mysqli_query($connection, $sql));
						  
						  for($i = $result['min_age']; $i <= $result['max_age']; $i++)
						  {
						?>
							<option value="<?php echo $i; ?>"
							<?php if (isset($_GET['age']) && $i==$_GET['age']) { echo "selected"; }?>>
							<?php echo $i; ?></option>
						<?php
						  }
                        ?>  
                      </select>
                    </div>
                    <div class="uk-text-small" style="width:10%;">  
						  <select name="subject" id="subject" class="uk-width-1-1">
							  <option value="">Subject</option>
							  <option value="EDP" <?php if (isset($_GET['subject']) && "EDP"==$_GET['subject']) { echo "selected"; }?>>EDP</option>
							  <option value="QF1" <?php if (isset($_GET['subject']) && "QF1"==$_GET['subject']) { echo "selected"; }?>>QF1</option>
							  <option value="QF2" <?php if (isset($_GET['subject']) && "QF2"==$_GET['subject']) { echo "selected"; }?>>QF2</option>
							  <option value="QF3" <?php if (isset($_GET['subject']) && "QF3"==$_GET['subject']) { echo "selected"; }?>>QF3</option>
						  </select>
             
                      </select>
                    </div>
                    <div style="width:15%;">
                        <button class="uk-button uk-width-1-1 full-width-blue">Search</button>
                    </div>
                </div>
            </form>

            <div class="uk-width-1-1 myheader mt-5">
                <h2 class="uk-text-center myheader-text-color myheader-text-style">Student Population</h2>
            </div>
            <div class="uk-overflow-container student_popu">
         <!-- <a href="admin/student_population_report.php?s=<?php echo $_GET['s']?>&student_status=<?php echo $student_status?>" target="_blank" class="uk-button full-width-blue" style="width:120px; float:right;margin-bottom: 15px;">Print</a> --> <a href="admin/export_student_population.php?s=<?php echo $_GET['s']; ?>&student_status=<?php echo $student_status; ?>&state=<?php echo $_GET['state']; ?>&country=<?php echo $_GET['country']; ?>&race=<?php echo $_GET['race']; ?>&age=<?php echo $_GET['age']; ?>" class="uk-button full-width-blue" style="width:120px; float:right;margin-bottom: 15px;">Export CSV</a>
                <table class="uk-table" id='mydatatable' style="width: 100%;font-size:12px;">
                  <thead>

                    <tr class="uk-text-bold uk-text-small">
                        <td style="width: 10px; padding: 0;">  <button class=" uk-button " data-uk-tooltip title="Transfer Selected Student to <?php echo getNextYear(); ?>" onclick="doTransferMultipleStudent()" style="width: 40px;text-align: center;color: #69A6F7;"><i style="font-size: 1.4em;" class="fas fa-exchange-alt "></i></button></td>
                        <td style="width: 10px;">Photo</td>
                        <td style="width: 10px;" data-uk-tooltip="{pos:asc}" title="Student Code">Student Code</td>
                        <td style="width: 10px;">Name</td>
                        <td style="width: 10px;">Gender</td>
                        <td style="width: 10px;">Age</td>
                        <td style="width: 10px;">Mykid / <br>Passport No.</td>
                        <td style="width: 10px;">Parent's Name</td>
                        <td style="width: 10px;">Parent's Contact</td>
                        <td style="width: 10px;">Subject</td>
                        <td style="width: 10px;">Race</td>
                        <td style="width: 10px;">Country</td>
                        <td style="width: 10px;">State</td>
                        <td style="width: 10px;">Status</td>
                        <td style="width: 20px;">Joined Date</td>
                        <td style="width: 20px;">Start Date</td>
                        <!--<td>Parent's Phone No</td>
                        <td>Parent's Email</td>-->
                        <td style="width: 120px;">Action</td>
                    </tr>
                  </thead>

                  <tbody>
				  
                  </tbody>
                </table>
            </div>
        </div>

        <form name="frmDeleteRecord" id="frmDeleteRecord" method="get">
            <input type="hidden" name="p" value="<?php echo $p?>">
            <input type="hidden" name="m" value="<?php echo $m?>">
            <input type="hidden" name="id" id="id" value="">
            <input type="hidden" name="mode" value="DEL">
        </form>

        <?php
        if ($msg!="") {
            echo "<script>UIkit.notify('$msg')</script>";
        }
    } else {
        echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
    }
} else {
    header("Location: index.php");
}
?>

<script type="text/javascript">
  $(document).ready(function(){
   $('#mydatatable').DataTable({
     'columnDefs': [ { 
        'targets': [0,9], // column index (start from 0)
        'orderable': false, // set orderable false for selected columns
     }],
	"order": [[ 8, "asc" ]],
    "bProcessing": true,
    "bServerSide": true,
    "sAjaxSource": "admin/serverresponse/student_population.php?s=<?php echo $_GET['s']; ?>&student_status=<?php echo $student_status; ?>&state=<?php echo $_GET['state']; ?>&country=<?php echo $_GET['country']; ?>&race=<?php echo $_GET['race']; ?>&age=<?php echo $_GET['age']; ?>&subject=<?php echo $_GET['subject']; ?>"
   });
}); 

function onCountryChange(country) {
    $.ajax({
        url: "admin/get_state.php",
        type: "POST",
        data: "country=" + country,
        dataType: "text",
        beforeSend: function(http) {

        },
        success: function(response, status, http) {
            if (response != "") {
                $("#state").html(response);
            } else {
                $("#state").html(
                    "<select name='state' id='state' class='uk-width-1-1'><option value=''>Please select a country</option></select>"
                );
            }
        },
        error: function(http, status, error) {
            UIkit.notification("Error:" + error);
        }
    });

    $.ajax({
      url: "admin/get_race.php",
      type: "POST",
      data: "country=" + country,
      dataType: "text",
      beforeSend: function(http) {
      },
      success: function(response, status, http) {
          if (response != "") {
              $("#race").html(response);
          } else {
              $("#race").html(
                  "<select name='race' id='race' class='uk-width-1-1'><option value=''>Please select a country</option></select>"
              );
          }
      },
      error: function(http, status, error) {
          UIkit.notification("Error:" + error);
      }
    });

}

$(document).ready(function() {
  $("#country").change(function() {
    var country = $("#country").val();
    onCountryChange(country);
  });
});

</script>
