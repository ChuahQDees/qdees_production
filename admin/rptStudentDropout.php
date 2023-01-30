<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");

function isMaster($master_code)
{
   global $connection;

   $sql = "SELECT * from master where master_code='$master_code'";
   $result = mysqli_query($connection, $sql);
   $num_row = mysqli_num_rows($result);
   if ($num_row > 0) {
      return true;
   } else {
      return false;
   }
}
?>

<script>
   $(document).ready(function() {
      generateReport('screen');
   });

   function generateReport(method) {
       var centre_code=$('[name="centre_code"]').val();	console.log(centre_code);
   if (centre_code=="") {centre_code="ALL";
     //var centre_code=document.getElementById('screens.screenid').value;	       
  } //else{
   //var centre_code=$('[name="centre_code"]').val();
  //}
      var df = $("#df").val();
      var dt = $("#dt").val();
      var student_name = $("#student_name").val();
      var dropout_reason = $("#dropout_reason").val();

      if (centre_code != "") {
         if (method == "screen") {
            $.ajax({
               url: "admin/a_rptStudentDropout.php",
               type: "POST",
               data: "centre_code=" + centre_code + "&df=" + df + "&dt=" + dt + "&student_name=" + student_name +  "&dropout_reason=" + dropout_reason,
               dataType: "text",
               beforeSend: function(http) {},
               success: function(response, status, http) {
                  $("#sctResult").html(response);
               },
               error: function(http, status, error) {
                  UIkit.notify("Error:" + error);
               }
            });
         } else {
            $("#frmPrintCentreCode").val(centre_code);
            $("#frmPrintdf").val(df);
            $("#frmPrintdt").val(dt);
            $("#frmPrintstudent_name").val(student_name);
            $("#frmPrintdropout_reason").val(dropout_reason);
            $("#frmPrint").submit()
         }
      } else {
         UIkit.notify("Please select a centre");
      }
   }
</script>

<script>
   function doCentreCodeChange() {
      var centre_code = $("#centre_code").val();

      if (centre_code != "") {
         id = "back_stock"
         $.ajax({
            url: "admin/doCentreCodeChange.php",
            type: "POST",
            data: "centre_code=" + centre_code,
            dataType: "text",
            beforeSend: function(http) {},
            success: function(response, status, http) {
               $("#back_stock").attr("href", window.location.href);
            },
            error: function(http, status, error) {
               UIkit.notify("Error:" + error);
            }
         });
      }
   }

   $(document).ready(function() {
      $("#btnGenerate").click(function() {
         $("#back_stock").attr("href", window.location.href);
      })
   });
</script>
<!--<?php // if ($_GET['mode'] != "") { ?>
   <a id="back_stock" href="/index.php?p=rpt_student_dropout">
      <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
   </a>
<?php // } else { ?>
   <a id="back_stock" href="/">
      <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
   </a>
<?php // } ?>-->
<span class="page_title1">
   <span class="page_title"><img src="/images/title_Icons/Stud Reg.png">Student Dropout Report
   </span>
</span>
   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color myheader-text-style">Searching</h2>
   </div>

   <div class="uk-form  nice-form" style="border-top-right-radius: 15px;border-top-left-radius: 15px;">
      <?php
      if ((($_SESSION["UserType"] == "S")  || ($_SESSION["UserType"]=="H") || ($_SESSION["UserType"]=="C") || ($_SESSION["UserType"]=="R") || ($_SESSION["UserType"]=="CM") || ($_SESSION["UserType"]=="T")) & (hasRightGroupXOR($_SESSION["UserName"], "ReportingView"))) {
         //if (isMaster($_SESSION["CentreCode"])) {
         // $sql="SELECT * from centre where upline='".$_SESSION["CentreCode"]."' order by centre_code";
         //} else {
         $sql = "SELECT * from centre order by centre_code";
         // }
      } else {
         if ((($_SESSION["UserType"] == "A") || ($_SESSION["UserType"] == "O")) & (hasRightGroupXOR($_SESSION["UserName"], "ReportingView"))) {
            $sql = "";
         }
      }

      if ($sql == "") {
      ?>

         <?php

         $centrecode = $_SESSION['CentreCode'];

         // $sqlCentre = "SELECT centre_code, company_name from centre where centre_code = '$centrecode'  limit 1 ";
         $sqlCentre = "SELECT centre_code, company_name from centre ";
         $resultCentre = mysqli_query($connection, $sqlCentre);
         $rowCentre = mysqli_fetch_assoc($resultCentre);
         // var_dump($rowCentre["kindergarten_name"]);die;

         ?>

         <div class="uk-grid uk-grid-small">
            <div class="uk-width-medium-2-10" style="width: 210px;">
               Centre Name<br>
			   <span style="width: 100%;border: 0;font-weight: bold;"><?php echo getCentreNameIndex($_SESSION["CentreCode"]) ?></span>
              <!-- <input name="centre_name" id="centre_name" value="" readonly>
                <select  class="uk-width-1-1" id="selectCentre">
         <option>Select Centre Name</option>

         <?php while ($rowCentre = mysqli_fetch_assoc($resultCentre)) : ?>
         <option value="<?= $rowCentre["centre_code"] ?>"><?= $rowCentre["kindergarten_name"] ?></option>
      <?php endwhile; ?>

      </select> -->
               <input type="hidden" name="centre_code" class="uk-width-1-1 gg" id="centre_code" value="<?php echo $_SESSION['CentreCode'] ?>" readonly>
            </div>


            <div class="uk-width-medium-2-10">
               Dropout Date From<br>
               <input type="text" class="uk-width-1-1" name="df" id="df" placeholder="Date From" data-uk-datepicker="{format: 'YYYY-MM-DD'}" value="" autocomplete="off">
               <!-- <select class="uk-width-1-1" name="df" id="df"></select> -->
            </div>
            <div class="uk-width-medium-2-10">
               Dropout Date To<br>
               <input type="text" class="uk-width-1-1" name="dt" id="dt" placeholder="Date To" data-uk-datepicker="{format: 'YYYY-MM-DD'}" value="" autocomplete="off">
               <!-- <select class="uk-width-1-1" name="dt" id="dt"></select> -->
            </div>
            <div class="uk-width-medium-2-10">
               Student Name<br>
               <!-- <input type="text" class="uk-width-1-1" name="df" id="df" placeholder="Date From"  value="" autocomplete="off"> -->
               <input style="width: 100%;" name="student_name" id="student_name" value="">
            </div>
			<div class="uk-width-medium-2-10">
            Dropout Reason<br> 
               <!--<select name="dropout_reason" id="dropout_reason" class="">
                  <option value="">Select Dropout Reason</option>
                  <option value="Transfer to Other Scholars">Transfer to Other Scholars</option>
                  <option value="Family Relocation">Family Relocation</option>
                  <option value="Financial Difficulties">Financial Difficulties</option>
                  <option value="Poor Q-Coach Delivery">Poor Q-Coach Delivery</option>
                  <option value="Program Quality">Program Quality</option>
                  <option value="Other Dropout Reason">Other Dropout Reason</option>
               </select>-->
			   <select name="dropout_reason" id="dropout_reason">
                        <option value="">Select Dropout Reason</option>
<?php
$sql="SELECT * from codes where module='DROPOUTREASON' order by code";
$result=mysqli_query($connection, $sql);
while ($row=mysqli_fetch_assoc($result)) {
?>
                        <option value="<?php echo $row["code"]?>" <?php if ($row["code"]==$edit_row["country"]) {echo "selected";}?>><?php echo $row["code"]?></option>
<?php
}
?>
                     </select>
            </div>
            <div class="uk-width-medium-2-10" style="white-space: nowrap;">
               <br>
               <button onclick="generateReport('screen')" id="btnGenerate" class=" uk-button">Show on Screen</button>
               <!-- </div>
            <div class="uk-width-medium-1-10"> 
               <br>-->
               <button onclick="generateReport('print')" class=' uk-button'>Print</button>
            </div>
         </div>

      <?php
      } else {

         $result = mysqli_query($connection, $sql);
         // print_r($result); die;
      ?>


         <div class="uk-grid uk-grid-small">
            <div class="uk-width-medium-2-10">
               Centre Name<br>
              <input list="centre_code"  id="screens.screenid" name="centre_code" value="">
				<datalist class="form-control" id="centre_code" style="display:none;">    
				  <option value="ALL" <?php echo $centre_code == 'ALL' ? 'selected' : '' 
                        ?>>ALL</option>
                            <?php
                               while ($row=mysqli_fetch_assoc($result)) {
                              ?>
                              <option value="<?php echo $row['centre_code']
                                             ?>" <?php echo $row['centre_code'] == $centre_code ? 'selected' : '' 
                                                   ?>><?php echo $row["company_name"]
                                                                                    ?></option>
                            <?php
                               }
                              ?>
				</datalist>
               
            </div>
            <div class="uk-width-medium-2-10">
               Dropout Date From<br>
               <input type="text" class="uk-width-1-1" name="df" id="df" placeholder="Date From" data-uk-datepicker="{format: 'YYYY-MM-DD'}" value="">
               <!-- <select class="uk-width-1-1" name="df" id="df"></select> -->
            </div>
            <div class="uk-width-medium-2-10">
               Dropout Date To<br>
               <input type="text" class="uk-width-1-1" name="dt" id="dt" placeholder="Date To" data-uk-datepicker="{format: 'YYYY-MM-DD'}" value="">
               <!-- <select class="uk-width-1-1" name="dt" id="dt"></select> -->
            </div>
            <div class="uk-width-medium-2-10">
               Student Name<br>
               <!-- <input type="text" class="uk-width-1-1" name="df" id="df" placeholder="Date From"  value="" autocomplete="off"> -->
               <input style="width: 100%;" name="student_name" id="student_name" value="">
            </div>
            <div class="uk-width-medium-2-10">
            Dropout Reason<br> 
               <!--<select name="dropout_reason" id="dropout_reason" class="">
                  <option value="">Select Dropout Reason</option>
                  <option value="Transfer to Other Q-dees">Transfer to Other Scholars</option>
                  <option value="Family Relocation">Family Relocation</option>
                  <option value="Financial Difficulties">Financial Difficulties</option>
                  <option value="Poor Q-Coach Delivery">Poor Q-Coach Delivery</option>
                  <option value="Program Quality">Program Quality</option>
                  <option value="Other Dropout Reason">Other Dropout Reason </option>
               </select>-->
			   <select name="dropout_reason" id="dropout_reason">
                        <option value="">Select Dropout Reason</option>
<?php
$sql="SELECT * from codes where module='DROPOUTREASON' order by code";
$result=mysqli_query($connection, $sql);
while ($row=mysqli_fetch_assoc($result)) {
?>
                        <option value="<?php echo $row["code"]?>" <?php if ($row["code"]==$edit_row["country"]) {echo "selected";}?>><?php echo $row["code"]?></option>
<?php
}
?>
                     </select>
            </div>
            <div class="uk-width-medium-2-10" style="white-space: nowrap;">
               <br>
               <button onclick="generateReport('screen')" id="btnGenerate" class="uk-width-1-1 uk-button">Show on Screen</button>
            
               <button onclick="generateReport('print')" class='uk-button uk-width-0'>Print</button>
            </div>
         </div>
      <?php
      }
      ?>
   </div>
   <div id="sctResult" class="" style="border-top-right-radius: 15px;border-top-left-radius: 15px;"></div>
   <form id="frmPrint" action="admin/a_rptStudentDropout.php" method="post" target="_blank" style="background: none!important; box-shadow: none!important; padding: 0;">
      <input type="hidden" name="method" value="print">
      <input type="hidden" id="frmPrintCentreCode" name="centre_code" value="">
      <input type="hidden" id="frmPrintdf" name="df" value="">
      <input type="hidden" id="frmPrintdt" name="dt" value="">
      <input type="hidden" id="frmPrintstudent_name" name="student_name" value="">
      <input type="hidden" id="frmPrintdropout_reason" name="dropout_reason" value="">
   </form>

   <script type="text/javascript">
      $('#selectCentre').on('change', function() {
         $("#centre_code").val(this.value);
      });


      var startYear = 2017;
      for (i = new Date().getFullYear(); i > startYear; i--) {
         $('#df').append($('<option />').val(i).html(i));
         $('#dt').append($('<option />').val(i).html(i));
      }
   </script>
   <style>
   .uk_t{
		font-size:14px;
	}
      .page_title1{
         display: block;
    width: 100%;
    text-align: right;
      }
      .uk-width-medium-2-10 {
    width: 14.5%;}
   </style>