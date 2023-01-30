<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");

$current_year = date("Y");
$low_year = $current_year - 1;
$high_year = $current_year + 2;

for ($i = $low_year; $i <= $high_year; $i++) {
   $loop_year .= "$i,";
}
$loop_year = substr($loop_year, 0, -1);

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
<link rel="stylesheet" type="text/css" href="lib/monthpicker/jquery.monthpicker.css">
<script src="lib/monthpicker_all/jquery.monthpicker.js?v=1"></script>
<script>
   $(document).ready(function() {
      generateReport('screen');
   });

   function generateReport(method) {
      var centre_code = $('[name="centre_code"]').val();
      var selected_month = $("#selected_month").val();
      if (centre_code == ""){
         centre_code = "ALL"
      }

      if (centre_code != "") {
         if (method == "screen") {
            $.ajax({
               url: "admin/a_rptVisitorRegistration.php",
               type: "POST",
               data: "centre_code=" + centre_code + "&selected_month=" + selected_month,
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
            $("#frmPrintselected_month").val(selected_month);
            $("#frmPrint").submit()
         }
      } else {
         UIkit.notify("Please select a centre");
      }

   }

   $(function() {
      $('#month').monthpicker({
         years: ['2022','2021','2020', '2019'],
         topOffset: 6,
         onMonthSelect: function(m, y) {
            var month = m + 1;

            if (month < 10) {
               month = "0" + month;
            }

            $("#selected_month").val(y + month);
         }
      });
   });
</script>

<a href="/">
   <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>
<span class="page_title"><img src="/images/title_Icons/Stud Reg.png">Visitor Registration Report
</span>

<div class="uk-width-1-1 myheader">
   <h2 class="uk-text-center myheader-text-color myheader-text-style">Searching</h2>
</div>
<div class="uk-form nice-form uk-grid" style="border-top-right-radius: 15px;border-top-left-radius: 15px;">
   <?php
   if ((($_SESSION["UserType"] == "S") || ($_SESSION["UserType"]=="H") || ($_SESSION["UserType"]=="C") || ($_SESSION["UserType"]=="R") || ($_SESSION["UserType"]=="CM") || ($_SESSION["UserType"]=="T")) & (hasRightGroupXOR($_SESSION["UserName"], "ReportingView"))) {
      if (isMaster($_SESSION["CentreCode"])) {
         $sql = "SELECT * from centre where upline='" . $_SESSION["CentreCode"] . "' order by centre_code";
      } else {
         $sql = "SELECT * from centre order by centre_code";
      }
   } else {
      if ((($_SESSION["UserType"] == "A") || ($_SESSION["UserType"] == "O")) & (hasRightGroupXOR($_SESSION["UserName"], "ReportingView"))) {
         $sql = "";
      }
   }

   if ($sql == "") {
   ?>


      <input type="hidden" name="centre_code" id="centre_code" value="<?php echo $_SESSION['CentreCode'] ?>">
      <div class="uk-width-medium-3-10">
         Centre Name<br>
         <span class="uk-width-medium-1-1 uk-text-bold"><?php echo getCentreCompanyName($_SESSION['CentreCode']) ?></span>
         <!-- <input type="text" class="uk-width-1-1" value="" readonly> -->
      </div>
      <div class="uk-width-medium-7-10">
         Month Selection
         <br>
         <a class="uk-button" id="month">Pick a Month</a>
         <input type="hidden" name="selected_month" id="selected_month" value="">

         <button onclick="generateReport('screen')" id="btnGenerate" class="uk-button">Show on Screen</button>

         <button onclick="generateReport('print')" class='uk-button'>Print</button>
      </div>



   <?php
   } else {
      $result = mysqli_query($connection, $sql);
   ?>
      <?php
      if (($_SESSION["UserType"] == "S") || ($_SESSION["UserType"]=="H") || ($_SESSION["UserType"]=="C") || ($_SESSION["UserType"]=="R") || ($_SESSION["UserType"]=="CM") || ($_SESSION["UserType"]=="T")) {
         $sql = "SELECT * from centre order by centre_code";
         $result = mysqli_query($connection, $sql);
      ?>
         <div class="uk-width-medium-2-10">
            Centre Name<br>
            <input list="centre_code" id="screens.screenid" name="centre_code">

            <datalist class="form-control" id="centre_code" style="display: none;">

               <option value="ALL" <?php echo $centreCode == 'ALL' ? 'selected' : '' ?>>All Centre</option>

               <?php

               // foreach ($get_centreCodes as $key => $get_centreCode) {

                  while ($row = mysqli_fetch_assoc($result)) {
                     ?>
                        <option value="<?php echo $row['centre_code'] ?>" <?php echo $row['centre_code'] == $centreCode ? 'selected' : '' ?>><?php echo $row["company_name"] ?></option>
                     <?php
                     }

               ?>

            </datalist>
            <!-- <select name="centre_code" id="centre_code" class='uk-width-1-1'>
               <option value="ALL" <?php echo $centreCode == 'ALL' ? 'selected' : '' ?>>All Centre</option>
               <?php
               while ($row = mysqli_fetch_assoc($result)) {
               ?>
                  <option value="<?php echo $row['centre_code'] ?>" <?php echo $row['centre_code'] == $centreCode ? 'selected' : '' ?>><?php echo $row["company_name"] ?></option>
               <?php
               }
               ?>
            </select> -->
         </div>
      <?php
      }
      ?>



      <div class="uk-width-medium-6-10">
         Month Selection
         <br>
         <a class="uk-button" id="month">Pick a Month</a>
         <input type="hidden" name="selected_month" id="selected_month" value="">

         <button onclick="generateReport('screen')" id="btnGenerate" class="uk-button">Show on Screen</button>

         <button onclick="generateReport('print')" class='uk-button'>Print</button>
      </div>
   <?php
   }
   ?>


</div>
<div id="sctResult" class="" style="border-top-right-radius: 15px;border-top-left-radius: 15px;"></div>
<form id="frmPrint" action="admin/a_rptVisitorRegistration.php" method="post" target="_blank" style="background: none!important; box-shadow: none!important; padding: 0;">
   <input type="hidden" name="method" value="print">
   <input type="hidden" id="frmPrintCentreCode" name="centre_code" value="">
   <input type="hidden" id="frmPrintselected_month" name="selected_month" value="">
</form>

<style>
   .btn-qdees {
      display: none;
   }
</style>