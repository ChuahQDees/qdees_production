<?php
session_start();

include_once("functions.php");
$year=$_SESSION["Year"];

function isMaster($master_code) {
   global $connection;

   $sql="SELECT * from master where master_code='$master_code'";
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);
   if ($num_row>0) {
      return true;
   } else {
      return false;
   }
}
?>
<link rel="stylesheet" type="text/css" href="lib/monthpicker/jquery.monthpicker.css">
<script>
$(document).ready(function() {
   var today = new Date();
   $("#selected_month").val("13");
   outstanding_pdf_listing();
   //generateReport('screen');
});

function generateReport(method) {
   var centre_code=$("[name='centre_code']").val();
   var selected_month=$("#selected_month").val();
   if(selected_month == '13') {
      var month = '13';
      var year = '<?php echo $_SESSION['Year']; ?>';
   } else {
      var month = selected_month.substring(4, 6);
      var year = selected_month.substring(0, 4);
   }

   var student=$("#student").val();

   if (centre_code!="") {
      if (method=="screen") {
         $.ajax({
            url : "admin/run_generate_outstanding_pdf.php",
            type : "POST",
            data : "centre_code="+centre_code+"&month="+month+"&year="+year+"&student="+student+"&UserName=<?php echo $_SESSION['UserName']; ?>&session_year=<?php echo $_SESSION['Year']; ?>",
            dataType : "text",
            beforeSend : function(http) {
            },
            success : function(response, status, http) {	
               UIkit.notify("Background process is running for generating report pdf. You will notify when pdf generated.");	
               outstanding_pdf_listing();		
               $("#aBack").attr("href", "/index.php?p=rpt_outstanding");
               //$("#sctResult").html(response);
            },
            error : function(http, status, error) {
               UIkit.notify("Error:"+error);
            }
         });
      } else {
         $("#frmPrintCentreCode").val(centre_code);
         $("#frmPrintselected_month").val(month);
         $("#frmPrintselected_year").val(year);
         $("#frmPrintStudent").val(student);
         $("#frmPrint").submit();
      }
   } else {
      //UIkit.notify("Please select a centre");
   }

}

function outstanding_pdf_listing() {
   $.ajax({
      url : "admin/outstanding_pdf_listing.php",
      type : "GET",
      success : function(response, status, http) {	
         $("#mydatatable").html(response);
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}
</script>

<a href="/" id="aBack">                 
         <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>

<span>
    <span class="page_title"><img src="/images/title_Icons/Payment.png">Outstanding Fee Report
</span>
<div class="uk-width-1-1 myheader">
                <h2 class="uk-text-center myheader-text-color myheader-text-style">Searching</h2>
            </div>
<div class="uk-form nice-form" style="border-top-right-radius: 15px;border-top-left-radius: 15px;">
   <div class="uk-grid uk-grid-small">
      <div class="uk-width-medium-2-10">
         <?php
         if ((($_SESSION["UserType"]=="S") || ($_SESSION["UserType"]=="H") || ($_SESSION["UserType"]=="C") || ($_SESSION["UserType"]=="R") || ($_SESSION["UserType"]=="CM") || ($_SESSION["UserType"]=="T")) & (hasRightGroupXOR($_SESSION["UserName"], "ReportingView"))) {
            if (isMaster($_SESSION["CentreCode"])) {
               $sql="SELECT * from centre where upline='".$_SESSION["CentreCode"]."' order by centre_code";
            } else {
               $sql="SELECT * from centre order by centre_code";
            }
         } else {
            if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) & (hasRightGroupXOR($_SESSION["UserName"], "ReportingView"))) {
               $sql="";
            }
         }

         if ($sql=="") {
         ?>
            Centre Name<br>
            <input type="hidden" name="centre_code" id="centre_code" value="<?php echo $_SESSION['CentreCode']?>">
            <span type="text" class="uk-width-medium-1-1 uk-text-bold" ><?php echo getCentreCompanyName($_SESSION['CentreCode'])?></span>
         <?php
         } else {
            $result=mysqli_query($connection, $sql);
         ?>
            Centre Name<br>
            <input list="centre_code" id="screens.screenid" name="centre_code">

               <datalist class="form-control" id="centre_code" style="display: none;">

               <!-- <option value="ALL" <?php echo $centreCode == 'ALL' ? 'selected' : '' ?>>All Centre</option> -->

               <?php

               // foreach ($get_centreCodes as $key => $get_centreCode) {

               //for ($i = 0; $i < count($get_centreCodes); $i++) {
                  while ($row=mysqli_fetch_assoc($result)) {
               ?>

                  <option value="<?php echo $row['centre_code'] ?>" <?php echo $row['centre_code'] == $centreCode ? 'selected' : '' ?>><?php echo $row["company_name"] ?></option>

               <?php

               }

               ?>
            <!-- <select class="uk-width-medium-1-1" name="centre_code" id="centre_code">
               <?php
               while ($row=mysqli_fetch_assoc($result)) {
               ?>
                  <option value="<?php echo $row['centre_code']?>"><?php echo $row["company_name"]?></option>
               <?php
               }
               ?>
            </select> -->
         <?php
         }
         ?>
      </div>
      <div class="uk-width-medium-4-10">
         Student Code/Name<br>
         <input type="text" name="student" id="student" class="uk-width-medium-1-1" placeholder="Student Code/Name">
      </div>
      <div class="uk-grid uk-width-medium-4-10">
         <div class="uk-width-medium-5-5">
            Months selection<br>
            <select name="selected_month" id="selected_month" >
               <option value="13" >All Months</option>
                  <?php
                     $period = getMonthList($year_start_date, $year_end_date);
                     $months = array();
                  
                     foreach ($period as $dt) {
                  ?>
                        <option value="<?php echo $dt->format("Ym"); ?>" ><?php echo $dt->format("M Y"); ?></option>
                  <?php
                           $months[$dt->format("M Y")] = $dt->format("Y-m");
                     }
                  ?>
               </select>
            <input type="hidden" name="selected_year" id="selected_year" value="">			
            <button onclick="generateReport('screen');" id="btnGenerate" class="uk-button">Generate PDF</button>			
           <!--  <button onclick="generateReport('print');" id="btnGenerate" class="uk-button">Print
         </button> -->
         </div>
         
      </div>
</div>
<div id="sctResult" style="border-top-right-radius: 15px;border-top-left-radius: 15px;background:white;"></div>
                  </div>
<script>
$(function() {
	var year = '<?php echo $year; ?>';
   /* $('#month').monthpicker({
      years: [year],
      topOffset: 6,
      onMonthSelect: function(m, y) {
         var month=m+1;
       
         $("#selected_month").val(month);
         $("#selected_year").val(y);
      }
   }); */
});
</script>
<form id="frmPrint" action="admin/a_rptOutstanding.php" method="post" target="_blank" style="background: none!important; box-shadow: none!important; padding: 0;">
   <input type="hidden" name="method" value="print">
   <input type="hidden" id="frmPrintCentreCode" name="centre_code" value="">
   <input type="hidden" id="frmPrintselected_month" name="month" value="">
   <input type="hidden" id="frmPrintselected_year" name="year" value="">
   <input type="hidden" id="frmPrintStudent" name="student" value="">
</form>
<style>
   .btn-qdees{
      display: none;
   }
</style>

<div class="uk-margin-right" style="margin-top: 4%;">
    <div class="uk-width-1-1 myheader">
        <h2 class="uk-text-center myheader-text-color">LISTING</h2>
    </div>

    <div class="uk-overflow-container">
        <table class="uk-table" id='mydatatable'>
            
        </table>
    </div>
</div>
