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
?>
<link rel="stylesheet" type="text/css" href="lib/monthpicker/jquery.monthpicker.css">
<script>
$(document).ready(function() {
   var today = new Date();
   //$("#selected_month").val(today.getFullYear()+''+(("0" + (today.getMonth()+1)).slice(-2)));
    $("#selected_month").val('<?php echo $year ?>'+''+(("0" + (today.getMonth()+1)).slice(-2)));
   generateReport('screen');
});

function doCentreCodeChange() {
   var centre_code=$("[name='centre_code']").val();
   // if(centre_code==""){
   //    centre_code="ALL"
   // }

   if (centre_code!="") {
      $.ajax({
         url : "admin/doCentreCodeChange.php",
         type : "POST",
         data : "centre_code="+centre_code,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            $("#student").replaceWith(response);
         },
         error : function(http, status, error) {
            UIkit.notify("Error:"+error);
         }
      });
   }
}

function generateReport(method, click) {
   var centre_code=$("[name='centre_code']").val();
   if(centre_code==""){
      centre_code="ALL"
   }
   var selected_month=$("#selected_month").val();
   var student=$("#student").val();
   var class_student = $("#class_student").val();
   var url = "admin/a_rptAccumulativeFeeStudent.php";
   if(class_student=='class'){
    url = "admin/a_rptAccumulativeFee.php";
   }

   if ((centre_code!="") & (selected_month!="")) {
      if (method=="screen") {
         $.ajax({
            url : url,
            type : "POST",
            data : "centre_code="+centre_code+"&selected_month="+selected_month+"&student="+student,
            dataType : "text",
            beforeSend : function(http) {
            },
            success : function(response, status, http) {
				if(click){
					$("#aBack").attr("href", "/index.php?p=rpt_accumulative_fee");
				}
				
               $("#sctResult").html(response);
            },
            error : function(http, status, error) {
               UIkit.notify("Error:"+error);
            }
         });
      } else {
         $("#frmPrintCentreCode").val(centre_code);
         $("#frmPrintStudent").val(student);
         $("#frmPrintselected_month").val(selected_month);
         $("#frmPrint").submit();
      }
   } else {
     // UIkit.notify("Please select a Centre and a month");
   }

}
</script>
<a href="/" id="aBack">                 
         <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>
<span>
    <span class="page_title"><img src="/images/title_Icons/Payment.png">Accumulative Fee Report
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
            Centre Name<br>
            <input type="hidden" name="centre_code" id="centre_code" value="<?php echo $_SESSION['CentreCode']?>">
			<span class="uk-width-medium-1-1 uk-text-bold" ><?php echo getCentreCompanyName($_SESSION['CentreCode'])?></span>
            
         <?php
         } else {
            $result=mysqli_query($connection, $sql);
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
            <!-- <select class="uk-width-medium-1-1" name="centre_code" id="centre_code">
               <option value="ALL">ALL</option>
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
      <div class="uk-width-medium-1-10">
         Student/Class<br>
          <select class="uk-width-medium-1-1" name="class_student" id="class_student">
               <option value="student">Student</option>
               <option value="class">Class</option>
            </select>
      </div>
      <div class="uk-width-medium-3-10">
         Student Code/Name<br>
         <input type="text" name="student" id="student" class="uk-width-medium-1-1" placeholder="Student Code/Name">
      </div>
      
      <div class="uk-grid uk-width-medium-4-10">
         <div class="uk-width-medium-5-5">
            Months selection<br>
            <a class="uk-button" id="month">Pick a Month</a>
            <input type="hidden" name="selected_month" id="selected_month" value="">
			<button onclick="generateReport('screen', 'true');" id="btnGenerate" class="uk-button">Show on screen</button>
			<button onclick="generateReport('print', 'true');" id="btnGenerate" class="uk-button">Print</button>
         </div>
         
      </div>
   </div>
</div>
<div id="sctResult"  class="" style="border-top-right-radius: 15px;border-top-left-radius: 15px;"></div>

<script src="lib/monthpicker_all/jquery.monthpicker.js?v=1"></script>

<?php
$current_year=date("Y");
$low_year=$current_year-1;
$high_year=$current_year+2;

for ($i=$low_year; $i<=$high_year; $i++) {
   $loop_year.="$i,";
}
$loop_year=substr($loop_year, 0, -1);
?>
<script>
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
<form id="frmPrint" action="admin/a_rptAccumulativeFeeStudent.php" method="post" target="_blank" style="background: none!important; box-shadow: none!important; padding: 0;">
   <input type="hidden" name="method" value="print">
   <input type="hidden" id="frmPrintCentreCode" name="centre_code" value="">
   <input type="hidden" id="frmPrintselected_month" name="selected_month" value="">
   <input type="hidden" id="frmPrintStudent" name="student" value="">
</form>
<style>
   .btn-qdees{
      display: none;
   }
   #month{
      margin-right: 30px;
   }
}
</style>