<!--<?php // if($_GET["mode"] !="" || $_SERVER['REQUEST_METHOD'] == 'POST'){
      ?>
<a href="/index.php?p=course">                 
             <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>
<?php // } else{ 
?>
<a href="/">                 
             <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>
<?php // }  
?>-->
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
   <span class="page_title"><img src="/images/title_Icons/Visitor Reg.png">Class (BOM)</span>
</span>
<?php
session_start();
if ($_SESSION["isLogin"] == 1) {
   if (($_SESSION["UserType"] == "S" || $_SESSION["UserType"] == "H" || $_SESSION["UserType"] == "C" || $_SESSION["UserType"] == "R" || $_SESSION["UserType"] == "CM" || $_SESSION["UserType"] == "T") & (hasRightGroupXOR($_SESSION["UserName"], "ClassEdit|ClassView"))) {
      include_once("mysql.php");
      include_once("admin/functions.php");
      include_once("lib/pagination/pagination.php");
      include_once("search_new.php");
      $p = $_GET["p"];
      $m = $_GET["m"];
      $get_sha1_id = $_GET["id"];
      $pg = $_GET["pg"];
      $mode = $_GET["mode"];

      $str_module = "Class";
      $p_module = "course";

      foreach ($_GET as $key => $value) {
         $$key = $value;
      }

      if ($mode == "") {
         $mode = "ADD";
      }

      include_once($p_module . "_func.php");
?>
      <script>
         function doDeleteRecord(id) {
            UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function() {
               $("#sha_id").val(id);
               $("#frmDeleteRecord").submit();
            });
         }

         $(document).ready(function() {
            onProgrammeChange();
         });
      </script>
      <script>
         $(document).ready(function() {
            $("#country").change(function() {
               var country = $("#country").val();
               $.ajax({
                  url: "admin/get_state_not_select.php",
                  type: "POST",
                  data: "country=" + country,
                  dataType: "text",
                  beforeSend: function(http) {},
                  success: function(response, status, http) {
                     if (response != "") {
                        $("#state").html(response);
                     } else {
                        $("#state").html("<select name='state' id='state' class='uk-width-1-1'><option value=''>Please select a country</option></select>");
                        UIkit.notify("No state found in " + country);
                     }
                  },
                  error: function(http, status, error) {
                     UIkit.notify("Error:" + error);
                  }
               });
            });
            $("#frmCourse").submit(function(e) {

               var country = $("#country").val();
               var state = $("#state").val();
               var subject = $("#subject").val();
               // var duration = $("#duration").val();
               // var selectPaymentType = $("#selectPaymentType").val();
               // var fees = $("#fees").val();
               var remark = $("#remark").val();

               if (!country || state == "" || !subject ) {

                  if (!country) {
                     $('#validationCountry').show();
                  } else {
                     $('#validationCountry').hide();
                  }

                  if (state == "") {
                     $('#validationState').show();
                  } else {
                     $('#validationState').hide();
                  }

                  if (!subject) {
                     $('#validationSubject').show();
                  } else {
                     $('#validationSubject').hide();
                  }

                  // if (!duration) {
                     // $('#validationDuration').show();
                  // } else {
                     // $('#validationDuration').hide();
                  // }

                  // if (!selectPaymentType) {
                     // $('#validationPaymentType').show();
                  // } else {
                     // $('#validationPaymentType').hide();
                  // }

                  // if (fees == "") {
                     // $('#validationFees').show();
                  // } else {
                     // $('#validationFees').hide();
                  // }

                  return false;
               }
               // if (selectPaymentType == "Termly") {
                  // if (remark == "") {
                     // $('#validationRemark').show();
                     // return false;
                  // } else {
                     // $('#validationRemark').hide();
                  // }
               // }
            });
         });
      </script>
      <div class="uk-margin-right">
         <div class="uk-width-1-1 myheader">
            <h2 class="uk-text-center myheader-text-color"><?php echo $str_module ?></h2>
         </div>
         <div class="uk-form uk-form-small">
            <?php
            if (($_SESSION["UserType"] == "S" || $_SESSION["UserType"]=="H" || $_SESSION["UserType"]=="C" || $_SESSION["UserType"]=="R" || $_SESSION["UserType"]=="CM" || $_SESSION["UserType"]=="T") & (hasRightGroupXOR($_SESSION["UserName"], "ClassEdit"))) {
            ?>
               <form name="frmCourse" id="frmCourse" method="post" action="index.php?p=<?php echo $p_module ?>&pg=<?php echo $pg ?>&mode=SAVE">
               <?php
            }
               ?>
               <div class="uk-grid">
                  <div class="uk-width-medium-5-10">
                     <table class="uk-table uk-table-small">
                        <tr class="uk-text-small">
                           <td class="uk-width-3-10 uk-text-bold">Country<span class="text-danger">*</span>:</td>
                           <td class="uk-width-7-10">
                              <select name="country" id="country" class="uk-width-1-1">
                                 <option value="">Select</option>
                                 <?php
                                 $sql = "SELECT * from codes where module='COUNTRY' order by code";
                                 $result = mysqli_query($connection, $sql);
                                 while ($row = mysqli_fetch_assoc($result)) {
                                 ?>
                                    <option value="<?php echo $row["code"] ?>" <?php if ($row["code"] == $edit_row["country"]) {
                                                                                    echo "selected";
                                                                                 } ?>><?php echo $row["code"] ?></option>
                                 <?php
                                 }
                                 ?>
                              </select>

                              <span id="validationCountry" style="color: red; display: none;">Please select Country</span>
                           </td>
                        </tr>
                        <tr class="uk-text-small">
                           <td class="uk-width-3-10 uk-text-bold">State<span class="text-danger">*</span>:</td>
                           <td class="uk-width-7-10">
                              <select name="state[]" id="state" class="uk-width-1-1" multiple>
                                 <?php
                                 $stateArray = explode(', ', $edit_row['state']);
                                 if ($edit_row["country"] != "") {
                                 ?>
                                    <?php
                                    $sql = "SELECT * from codes where country='" . $edit_row["country"] . "' and module='STATE' order by code";
                                    $result = mysqli_query($connection, $sql);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                       <option value="<?php echo $row['code'] ?>" <?php if (in_array($row["code"], $stateArray)) {
                                                                                       echo "selected";
                                                                                    } ?>><?php echo $row["code"] ?></option>
                                    <?php
                                    }
                                 } else {
                                    ?>
                                    <option value="">Please Select Country First</option>
                                 <?php
                                 }
                                 ?>

                              </select>

                              <span id="validationState" style="color: red; display: none;">Please select State</span>
                           </td>
                        </tr>
                        <tr class="uk-text-small">
                           <td class="uk-width-3-10 uk-text-bold">Student Entry Level : </td>
                           <td class="uk-width-7-10">
                              <select name="subject" id="subject" class="uk-width-1-1">
                                 <option value="">Select</option>
                                 <option <?php echo (($edit_row['subject'] == 'EDP') ? "selected" : "") ?> value="EDP">EDP</option>
                                 <option <?php echo (($edit_row['subject'] == 'QF1') ? "selected" : "") ?> value="QF1">QF1</option>
                                 <option <?php echo (($edit_row['subject'] == 'QF2') ? "selected" : "") ?> value="QF2">QF2</option>
                                 <option <?php echo (($edit_row['subject'] == 'QF3') ? "selected" : "") ?> value="QF3">QF3</option>
                              </select>
							  <input type="hidden" name="id" id="id" value="<?php echo $edit_row['id']?>">
                              <span id="validationSubject" style="color: red; display: none;">Please select Subject</span>
                           </td>
                        </tr>
						<tr class="uk-text-small">
                           <td class="uk-width-3-10 uk-text-bold">Term : </td>
                           <td class="uk-width-7-10">
								<select name="term" id="term" class="uk-width-1-1">
									 <option value="">Select</option>
									 <option <?php echo (($edit_row['term'] == '1') ? "selected" : "") ?> value="1">1</option>
									 <option <?php echo (($edit_row['term'] == '2') ? "selected" : "") ?> value="2">2</option>
									 <option <?php echo (($edit_row['term'] == '3') ? "selected" : "") ?> value="3">3</option>
									 <option <?php echo (($edit_row['term'] == '4') ? "selected" : "") ?> value="4">4</option>
									 <option <?php echo (($edit_row['term'] == '5') ? "selected" : "") ?> value="5">5</option>
								</select>
                              <span id="validationDuration" style="color: red; display: none;">Please select Term</span>
                           </td>
                        </tr>
						<!---<tr class="uk-text-small">
                           <td class="uk-width-3-10 uk-text-bold">Duration : </td>
                           <td class="uk-width-7-10">
                              <select name="duration" id="duration" class="uk-width-1-1">
                                 <option value="">Select</option>
                                 <option <?php // echo (($edit_row['duration'] == 'Full Day') ? "selected" : "") ?> value="Full Day">Full Day</option>
                                 <option <?php// echo (($edit_row['duration'] == 'Half Day') ? "selected" : "") ?> value="Half Day">Half Day</option>
                                 <option <?php// echo (($edit_row['duration'] == '3/4 Day') ? "selected" : "") ?> value="3/4 Day">3/4 Day</option>
                                 
                              </select>
                              <span id="validationDuration" style="color: red; display: none;">Please select Duration</span>
                           </td>
                        </tr>-->
                        
                        <!--                <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Registration Fees : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" type="number" step="0.01" name="registration" id="registration" value="<?php echo $edit_row['registration'] ?>">
                  </td>
               </tr>
 -->
                     </table>
                  </div>
                  <div class="uk-width-medium-5-10">
                     <table class="uk-table uk-table-small">


                        <!--<tr class="uk-text-small">
                           <td class="uk-width-3-10 uk-text-bold">Payment Type : </td>
                           <td class="uk-width-7-10">
                              <select class="uk-width-1-1" id="selectPaymentType" name="selectPaymentType">
                                 <option value="">Select</option>
                                 <?php// if($edit_row['payment_type']):?>

                                 <!--<option value="One Time" <?php // echo (($edit_row['payment_type']== 'One Time')?"selected":"") 
                                                               ?> >One Time</option>
 <option value="Termly" <?php // echo (($edit_row['payment_type']== 'Termly')?"selected":"") 
                        ?> >Termly</option>
 <option value="Monthly" <?php // echo (($edit_row['payment_type']== 'Monthly')?"selected":"") 
                           ?> >Monthly</option>
                        <?php // else:
                        ?>
                        <option value="">Select</option>
                        <option value="One Time">One Time</option>
                        <option value="Termly">Termly</option>
                        <option value="Monthly">Monthly</option>-->
                                 <?php // endif;
                                 ?>
                                 <?php
                                // $sql = "SELECT * from codes where module='PAYMENT_TYPE' order by code";
                                // $result = mysqli_query($connection, $sql);
                                // while ($row = mysqli_fetch_assoc($result)) {
                                 ?>
                                   <!-- <option value="<?php // echo $row['code'] ?>" <?php // if ($row['code'] == $edit_row['payment_type']) {
                                                                                 //echo 'selected';
                                                                             // } ?>><?php // echo $row["code"] ?></option>
                                 <?php
                                // }
                                 ?>
                              </select>
                              <span id="validationPaymentType" style="color: red; display: none;">Please input Payment Type</span>
                           </td>
                        </tr>-->
                        <tr class="uk-text-small">
                           <td class="uk-width-3-10 uk-text-bold">Module : </td>
                           <td class="uk-width-7-10">
                              <input class="uk-width-1-1" type="text" name="moduley" id="moduley" value="<?php echo $edit_row['module'] ?>">
                              <span id="validationFees" style="color: red; display: none;">Please input Module</span>
                           </td>
                        </tr>
						<tr class="uk-text-small">
                           <td class="uk-width-3-10 uk-text-bold">Materials : </td>
                           <td class="uk-width-7-10">
                              <input class="uk-width-1-1" type="text" name="materials" id="materials" value="<?php echo $edit_row['materials'] ?>">
                              <span id="validationFees" style="color: red; display: none;">Please input Materials</span>
                           </td>
                        </tr>
                        <tr class="uk-text-small">
                           <td class="uk-width-3-10 uk-text-bold">Remark : </td>
                           <td class="uk-width-7-10">
                              <textarea class="uk-width-1-1" type="text" name="remark" id="remark"><?php echo $edit_row['remark'] ?></textarea>
                              <span id="validationRemark" style="color: red; display: none;">Please input Remark</span>
                           </td>
                        </tr>
                     </table>
                  </div>
               </div>
               <br>
               <div class="uk-text-center">
                  <?php
                  if (($_SESSION["UserType"] == "S" || $_SESSION["UserType"]=="H" || $_SESSION["UserType"]=="C" || $_SESSION["UserType"]=="R" || $_SESSION["UserType"]=="CM" || $_SESSION["UserType"]=="T") & (hasRightGroupXOR($_SESSION["UserName"], "ClassEdit"))) {
                  ?>
                     <button class="uk-button uk-button-primary">Save</button>
                  <?php
                  }
                  ?>
               </div>
               <?php
               if (($_SESSION["UserType"] == "S" || $_SESSION["UserType"]=="H" || $_SESSION["UserType"]=="C" || $_SESSION["UserType"]=="R" || $_SESSION["UserType"]=="CM" || $_SESSION["UserType"]=="T") & (hasRightGroupXOR($_SESSION["UserName"], "ClassEdit"))) {
               ?>
               </form>
            <?php
               }
            ?>
         </div>
         <br>

         <div class="uk-width-1-1 myheader">
            <h2 class="uk-text-center myheader-text-color">Searching</h2>
         </div>
         <form class="uk-form" name="frmCourseSearch" id="frmCourseSearch" method="post">
            <div class="uk-grid uk-grid-small">
               <div class="uk-width-medium-2-10">
			   <input type="text" name="s_country" id="s_country" class="uk-width-1-1" value="<?php echo $s_country ?>" placeholder="Country" />
               </div>

               <div class="uk-width-medium-2-10">
                  <script>
                     function onProgrammeChange() {
                        var programme = $("#programme").val();
                        var level = '<?php echo $level ?>';

                        if (programme != "") {
                           s = "";
                           s = "<select name='module' id='module' class='uk-width-1-1'>";
                           s = s + "<option value=''>Select Programme and Level First</option>";
                           s = s + "</select>";

                           $("#module").html(s);

                           $.ajax({
                              url: "admin/onProgrammeChange.php",
                              type: "POST",
                              data: "programme=" + programme + "&level=" + level,
                              dataType: "text",
                              beforeSend: function(http) {},
                              success: function(response, status, http) {
                                 $("#level").html(response);
                                 onLevelChange();
                              },
                              error: function(http, status, error) {
                                 UIkit.notify("Error:" + error);
                              }
                           });
                        }
                     }
                  </script>

                 <input type="text" name="s_subject" id="s_subject" class="uk-width-1-1" value="<?php echo $s_subject ?>" placeholder="Student Entry Level" />
                  
               </div>
               <script>
                  // function onLevelChange() {
                  // var programme=$("#programme").val();
                  // var level=$("#level").val();
                  // var the_module='<?php echo $module ?>';

                  // if ((programme!="") & (level!="")) {
                  // $.ajax({
                  // url : "admin/onLevelChange.php",
                  // type : "POST",
                  // data : "programme="+programme+"&level="+level+"&module="+the_module,
                  // dataType : "text",
                  // beforeSend : function(http) {
                  // },
                  // success : function(response, status, http) {
                  // $("#module").html(response);
                  // },
                  // error : function(http, status, error) {
                  // UIkit.notify("Error:"+error);
                  // }
                  // });
                  // } else {
                  // UIkit.notify("Please select a programme and a level");
                  // }
                  // }
               </script>
               <div class="uk-width-medium-2-10">
                  <input type="text" name="s_duration" id="s_duration" class="uk-width-1-1" value="<?php echo $s_duration ?>" placeholder="Duration" />
               </div>
               <!--<div class="uk-width-medium-2-10">
            <select name="level" id="level" onchange="onLevelChange()" class="uk-width-1-1">
               <option value="">Select Programme First</option>
            </select>
         </div>
         <div class="uk-width-medium-2-10">
            <select name="module" id="module" class="uk-width-1-1">
               <option value="">Select Programme and Level First</option>
            </select>
         </div>-->
               <div class="uk-width-2-10">
                  <button class="uk-button uk-width-1-1">Search</button>
               </div>
            </div>
         </form><br>

         <!--    <form class="uk-form" name="frm<?php echo $str_module ?>Search" id="frm<?php echo $str_module ?>Search" method="get">
      <input type="hidden" name="mode" id="mode" value="BROWSE">
      <input type="hidden" name="p" id="p" value="<?php echo $p ?>">
      <input type="hidden" name="m" id="m" value="<?php echo $m ?>">
      <input type="hidden" name="pg" value="">

      <div class="uk-grid">
         <div class="uk-width-7-10 uk-text-small">
            <input class="uk-width-1-1" placeholder="<?php echo $str_module ?>" name="s" id="s" value="<?php echo $_GET['s'] ?>">
         </div>
         <div class="uk-width-3-10">
            <button class="uk-button uk-width-1-1">Search</button>
         </div>
      </div>
   </form><br>
 -->
         <div class="uk-width-1-1 myheader">
            <h2 class="uk-text-center myheader-text-color">Listing</h2>
         </div>

         <?php
         $numperpage = 20;
         $query = "p=$p&m=$m&&programme=$programme&level=$level&module=$module";
         $pagination = getPagination($pg, $numperpage, $query, $browse_sql, $start_record, $num_row);
         $browse_sql .= " limit $start_record, $numperpage";
         
         $browse_result = mysqli_query($connection, $browse_sql);
         $browse_num_row = mysqli_num_rows($browse_result);
        
         ?>
         <div class="uk-overflow-container">
            <table class="uk-table" id="mydatatable">
               <thead>
                  <tr class="uk-text-bold uk-text-small">
                     <td>Country</td>
                     <td style="width:500px;">State</td>
                     <td>Student Entry Level</td>
                     <td>Term</td>
                     <td>Module</td>
                     <td>Materials</td>
                     <td>Remarks</td>
                     <td>Action</td>
                  </tr>
               </thead>
               <tbody>
                  <?php
                  if ($browse_num_row > 0) {
                     while ($browse_row = mysqli_fetch_assoc($browse_result)) {
                        $sha1_id = sha1($browse_row["id"]);
                  ?>
                        <tr class="uk-text-small">
                           <td><?php echo $browse_row["country"] ?></td>
                           <td style="width:500px"><?php echo $browse_row["state"] ?></td>
                           <td><?php echo $browse_row["subject"] ?></td>
                           <td><?php echo $browse_row["term"] ?></td>
                           <td><?php echo $browse_row["module"] ?></td>
                           <td><?php echo $browse_row["materials"] ?></td>
                           <td><?php echo $browse_row["remark"] ?></td>
                           <td>
                              <a href="index.php?p=<?php echo $p ?>&m=<?php echo $m ?>&id=<?php echo $sha1_id ?>&mode=EDIT"><img src="images/edit.png"></a>
                              <?php
                              if (($_SESSION["UserType"] == "S" || $_SESSION["UserType"]=="H" || $_SESSION["UserType"]=="C" || $_SESSION["UserType"]=="R" || $_SESSION["UserType"]=="CM" || $_SESSION["UserType"]=="T") & (hasRightGroupXOR($_SESSION["UserName"], "ClassEdit"))) {
                              ?>
                                 <a onclick="doDeleteRecord('<?php echo $sha1_id ?>')" href="#" id="btnDelete"><img src="images/delete.png"></a>
                              <?php
                              }
                              ?>
                           </td>
                        </tr>
                  <?php
                     }
                  } else {
                     echo "<tr><td colspan='6'>No Record Found</td></tr>";
                  }
                  ?>
               </tbody>
            </table>
         </div>
         <?php
         echo $pagination;
         ?>
      </div>
      <script type="text/javascript">
         $(document).ready(function() {
                  // $('#mydatatable').DataTable({
                  // 'columnDefs': [ {
                  // 'targets': [7], // column index (start from 0)
                  // 'orderable': false, // set orderable false for selected columns

                  // }],
                  // "order":[3, "asc"],
                  // "paging":   false,
                  // "info":   false
                  // });
                  // }); 
      </script>
      <style type="text/css">
         #mydatatable_length {
            display: none;
         }

         #mydatatable_filter {
            display: none;
         }

         /*#mydatatable_paginate{float:initial;text-align:center}*/
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

         table#mydatatable tr td {
            text-align: center;
         }
      </style>
      <form name="frmDeleteRecord" id="frmDeleteRecord" method="get">
         <input type="hidden" name="p" value="<?php echo $p ?>">
         <input type="hidden" name="m" value="<?php echo $m ?>">
         <input type="hidden" name="sha_id" id="sha_id" value="">
         <input type="hidden" name="mode" value="DEL">
      </form>
<?php
      if ($msg != "") {
         echo "<script>UIkit.notify('$msg')</script>";
      }
   } else {
      echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
   }
} else {
   header("Location: index.php");
}
?>