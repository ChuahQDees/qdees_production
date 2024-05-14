<?php
session_start();
include_once("../mysql.php");
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

$current_year=date("Y");
$low_year=$current_year-1;
$high_year=$current_year+2;

for ($i=$low_year; $i<=$high_year; $i++) {
   $loop_year.="$i,";
}
$loop_year=substr($loop_year, 0, -1);
?>
<link rel="stylesheet" type="text/css" href="lib/monthpicker/jquery.monthpicker.css">
<script src="lib/monthpicker_all/jquery.monthpicker.js?v=1"></script>
<script>
function doCentreCodeChange() {
   var centre_code=$("#centre_code").val();

   if (centre_code!="") {		id="back_stock"
      $.ajax({
         url : "admin/doCentreCodeChange.php",
         type : "POST",
         data : "centre_code="+centre_code,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {	
			$("#back_stock").attr("href", window.location.href);											
         },
         error : function(http, status, error) {
            UIkit.notify("Error:"+error);
         }
      });
   }
}

$(document).ready(function() {
   var today = new Date();
   $("#selected_month").val(today.getFullYear()+''+(("0" + (today.getMonth()+1)).slice(-2)));
   
   if($("#selected_month").val() == '' || $("#selected_month").val() == null) { $("#selected_month").val('13'); }
   
   generateReport('screen');
   $("#btnGenerate").click(function(){
	   $("#back_stock").attr("href", window.location.href);
   })
});

function generateReport(method) {
   
   var centre_code=$("[name='centre_code']").val();
   if(centre_code==""){
      centre_code="ALL"
   }
   // var df=$("#df").val();
   // var dt=$("#dt").val();
   var selected_month=$("#selected_month").val();

   var sid=$("#student_id").val();
   var student=$("#student").val();
   var summary=$("#summary").val();

   if ((centre_code!="") & (selected_month!="")) {
      if (method=="screen") {
         $.ajax({
            url : "admin/a_rptSummaryCategory.php",
            type : "POST",
            // data : "centre_code="+centre_code+"&df="+df+"&dt="+dt+"&student_id="+sid,
            data : "centre_code="+centre_code+"&selected_month="+selected_month+"&student="+student+"&summary="+summary,
            dataType : "text",
            beforeSend : function(http) {
            },
            success : function(response, status, http) {
               $("#sctResult").html(response);
			   //$("#back_stock").attr("href", window.location.href);
            },
            error : function(http, status, error) {
               UIkit.notify("Error:"+error);
            }
         });
      } else {
         $("#frmPrintCentreCode").val(centre_code);
         $("#frmPrintstudent").val(student);
         $("#frmPrintsummary").val(summary);
         $("#frmPrintselected_month").val(selected_month);
         $("#frmPrint").submit();
      }
   } else {
     // UIkit.notify("Please select a Centre, From Date and To Date");
   }
}

$(function() {
	var year = '<?php echo $year; ?>';
   $('#month').monthpicker({
      //years: ['2020', '2019'],
	  years: [year],
      topOffset: 6,
      onMonthSelect: function(m, y) {
         var month=m+1;

         if (month<10) {
            month="0"+month;
         }

         $("#selected_month").val(y+month);
      }
   });
});
</script>
<?php if($_GET['mode']!=""){ ?>
<a id="back_stock" href="/index.php?p=rpt_summary_category">                 
             <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>
<?php }else { ?>
<a id="back_stock" href="/">                 
             <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>
<?php } ?>

<span>
    <span class="page_title"><img src="/images/title_Icons/Payment.png">CENTRE MONTHLY FEES REPORT
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
               // if (strtoupper($_SESSION["UserName"])=="SUPER") {
                  $sql="SELECT * from centre order by centre_code";
               // } else {
               //    $sql="";
               // }
            }
         } else {
            if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) & (hasRightGroupXOR($_SESSION["UserName"], "ReportingView"))) {
               $sql="";
            }
         }

         if ($sql=="") {
         ?>
            <span >Centre Name</span><br> 
			<span style="font-weight: bold;"><?php echo getCentreCompanyName($_SESSION['CentreCode'])?></span>
            <input type="hidden" name="centre_code" id="centre_code" value="<?php echo $_SESSION['CentreCode']?>">
            <input type="hidden" class="uk-width-medium-1-1" type="text" value="<?php echo getCentreCompanyName($_SESSION['CentreCode'])?>" readonly>
         <?php
         } else {
            $result=mysqli_query($connection, $sql);
            if (($_SESSION["UserType"]=="S") || ($_SESSION["UserType"]=="H") || ($_SESSION["UserType"]=="C") || ($_SESSION["UserType"]=="R") || ($_SESSION["UserType"]=="CM") || ($_SESSION["UserType"]=="T")) {
            ?>
               Centre Name<br>
               <input list="centre_code" id="screens.screenid" name="centre_code">

<datalist class="form-control" id="centre_code" style="display: none;">

  <option value="ALL" <?php echo $centreCode == 'ALL' ? 'selected' : '' ?>>All Centre</option>

  <?php

  // foreach ($get_centreCodes as $key => $get_centreCode) {

  //for ($i = 0; $i < count($get_centreCodes); $i++) {
   while ($row=mysqli_fetch_assoc($result)) {
  ?>

    <option value="<?php echo $row['centre_code'] ?>" <?php echo $row['centre_code'] == $centreCode ? 'selected' : '' ?>><?php echo $row["company_name"] ?></option>

  <?php

  }

  ?>
               <!-- <select class="uk-width-medium-1-1" name="centre_code" id="centre_code" onchange="doCentreCodeChange()">
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
         }
         ?>
      </div>
   <!-- <input data-uk-datepicker="{format:'DD/MM/YYYY'}" placeholder="From Date" name="df" id="df" type="text" value="" autocomplete="off">
   <input data-uk-datepicker="{format:'DD/MM/YYYY'}" placeholder="To Date" name="dt" id="dt" type="text" value="" autocomplete="off"> -->
      <div class="uk-width-medium-3-10">
         Collection Type<br>
         <input type="hidden" name="student" id="student" class="uk-width-medium-1-1" placeholder="Collection Type">
		 <select class="uk-width-medium-1-1" name="summary" id="summary" >		   <option value="">Select Collection Type</option>		   <option value="School Fees Summary">School Fees Summary</option>		   <option value="Stock Item Summary">Stock Item Summary</option>		   </select>
      </div>
        <div class="uk-width-medium-2-10" style="width: auto;">
         Months selection<br>
         <select name="selected_month" id="selected_month" >
            <option value="13" >All Months</option>
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
         <!-- <a class="uk-button" id="month">Pick a Month</a>
         <input type="hidden" name="selected_month" id="selected_month" value=""> -->
      </div>		
	  <div style="margin-top:20px; white-space: nowrap;" class="uk-width-medium-2-10">
			  <button onclick="generateReport('screen');" id="btnGenerate" class="uk-button">Show on screen</button>
			  <button onclick="generateReport('print');" id="btnGenerate" class="uk-button">Print</button>
	   </div>
</div>
</div>
<div id="sctResult" style="border-top-right-radius: 15px;border-top-left-radius: 15px;"></div>
<form id="frmPrint" action="admin/a_rptSummaryCategory.php" method="post" target="_blank" style="background: none!important; box-shadow: none!important; padding: 0;">
   <input type="hidden" name="method" value="print">
   <input type="hidden" id="frmPrintCentreCode" name="centre_code" value="">
   <input type="hidden" id="frmPrintselected_month" name="selected_month" value="">
   <input type="hidden" id="frmPrintstudent" name="student" value="">
   <input type="hidden" id="frmPrintsummary" name="summary" value="">
   <!-- <input type="hidden" id="frmPrintdf" name="df" value="">
   <input type="hidden" id="frmPrintdt" name="dt" value=""> -->
</form>
<style>
   .btn-qdees{
      display: none;
   }
   .uk-table_stage th {
    text-align: center!important;
}
}
</style>