<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");
$year=$_SESSION['Year'];
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
   var centre_code=$("#centre_code1").val();

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

<?php
   $m = date('m');
   if($m >= date('m',strtotime($year_start_date))) {
      $y = date('Y',strtotime($year_start_date));
   } else {
      $y = date('Y',strtotime($year_end_date));
   }
?>

$(document).ready(function() {
   var date = new Date();
       //y = date.getFullYear();
       y = '<?php echo $y; ?>';
       m = date.getMonth();
       firstDay = ("0" + (new Date(y, m, 1)).getDate()).slice(-2);
       lastDay = (new Date(y, m + 1, 0)).getDate();
   $("#df").val(firstDay+'/'+(("0" + (m+1)).slice(-2))+'/'+y);
   $("#dt").val(lastDay+'/'+(("0" + (m+1)).slice(-2))+'/'+y);
   generateReport('screen');
});

$(document).ready(function() {
   var today = new Date();
   //$("#selected_month").val(today.getFullYear()+''+(("0" + (today.getMonth()+1)).slice(-2)));
   //$("#selected_month").val('<?php //echo $year ?>'+''+(("0" + (today.getMonth()+1)).slice(-2)));
   generateReport('screen');
});

function generateReport(method) {
   var centre_code=$("#centre_code1").val();
   if(centre_code==""){
      centre_code="ALL";
   }
   // var df=$("#df").val();
   // var dt=$("#dt").val();
   var selected_month=$("#selected_month").val();
   var student=$("#student").val();
   var collection=$("#collection").val();
   var df=$("#df").val();
   var dt=$("#dt").val();

   if ((centre_code!="") & (df!="") & (dt!="")) {
      if (method=="screen") {
         $.ajax({
            url : "admin/a_rptSales.php",
            type : "POST",
            data : "centre_code="+centre_code+"&df="+df+"&dt="+dt+"&student="+student+"&collection="+collection,
            dataType : "text",
            beforeSend : function(http) {
            },
            success : function(response, status, http) {				$("#aBack").attr("href", "/index.php?p=rpt_sales");
               $("#sctResult").html(response);
            },
            error : function(http, status, error) {
               UIkit.notify("Error:"+error);
            }
         });
      } else {
         $("#frmPrintCentreCode").val(centre_code);
         // $("#frmPrintdf").val(df);
         // $("#frmPrintdt").val(dt);
         $("#frmPrintStudent").val(student);
         $("#frmPrintdf").val(df);
         $("#frmPrintdt").val(dt);
         $("#frmPrintCollection").val(collection);
         $("#frmPrint").submit();
      }
   } else {
      UIkit.notify("Please select a Centre and Month");
   }

}
$(function() {
	var year = '<?php echo $year; ?>';
   $('#month').monthpicker({
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
<a href="/" id="aBack">                          	<span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span></a>
<span>
    <span class="page_title"><img src="/images/title_Icons/Payment History.png">CENTRE COLLECTION SUMMARY REPORT
</span>

<div class="uk-width-1-1 myheader">
                <h2 class="uk-text-center myheader-text-color myheader-text-style">Searching</h2>
            </div>
<div class="uk-form nice-form" style="border-top-right-radius: 15px;border-top-left-radius: 15px;">
   <div class="uk-grid uk-grid-small">
      <div class="uk-width-medium-2-10" style="width: 16%;">
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
            <input type="hidden" name="centre_code" id="centre_code1" value="<?php echo $_SESSION['CentreCode']?>">
            <!--<input type="text" class="uk-width-medium-1-1" value="<?php // echo getCentreCompanyName($_SESSION['CentreCode'])?>" class="uk-width-medium-2-10" readonly>-->			<span type="text" class="uk-width-medium-1-1 uk-text-bold" ><?php echo getCentreCompanyName($_SESSION['CentreCode'])?></span>
         <?php
         } else {
            $result=mysqli_query($connection, $sql);
            ?>
            <?php
            if (($_SESSION["UserType"]=="S") || ($_SESSION["UserType"]=="H") || ($_SESSION["UserType"]=="C") || ($_SESSION["UserType"]=="R") || ($_SESSION["UserType"]=="CM") || ($_SESSION["UserType"]=="T")) {
            ?>
               Centre Name<br>
               <input list="centre_code"  id="centre_code1" name="centre_code" value="">
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

               <!-- <select class="uk-width-medium-1-1" name="centre_code" id="centre_code" class="uk-width-medium-2-10">
               <option value="ALL">ALL</option>
                  <?php
                  //while ($row=mysqli_fetch_assoc($result)) {
                  ?>
                     <option value="<?php //echo $row['centre_code']?>"><?php //echo $row["company_name"]?></option>
                  <?php
                  //}
                  ?>
               </select> -->
            <?php
            }
         }
         ?>
      </div>
      <?php
      if ($_SESSION["UserType"]=="A" || $_SESSION["UserType"]=="S") {
      ?>
         <div class="uk-width-medium-2-10" style="width: 16%;">
            Payment Type<br>
            <select name="collection" id="collection" class="uk-width-medium-1-1">
               <option value="">Select a payment type</option>
               <option value="CC">Credit Card</option>
               <option value="CASH">Cash</option>
               <option value="BT">Bank Transfer</option>
               <option value="CHEQUE">Cheque</option>
               <option value="CN">Credit Note</option>
            </select>
         </div>
         <div class="uk-width-medium-2-10" style="width: 16%;">
            Student Code/Name<br>
            <input type="text" name="student" id="student" class="uk-width-medium-1-1" placeholder="Student Code/Name">
         </div>
      <?php
      } else {
      ?>
         <div class="uk-width-medium-2-10" style="width: 16%;">
            Payment Type<br>
            <select name="centre_code" id="centre_code" class="uk-width-medium-1-1">
               <option value="">Select</option>
            </select>
         </div>
         <div class="uk-width-medium-2-10" style="width: 16%;">
            Student Code/Name<br>
            <input type="text" name="student" id="student" class="uk-width-medium-1-1" placeholder="Student Code/Name" disabled>
         </div>
      <?php
      }
      ?>
      <div class="uk-width-medium-1-10" style="width: auto;" style="width: 16%;">
      From Date<br>
   <input data-uk-datepicker="{format:'DD/MM/YYYY'}" placeholder="From Date" name="df" id="df" type="text" value="" autocomplete="off" >
   </div>
   <div class="uk-width-medium-1-10" style="width: auto;" style="width: 16%;">
      To Date<br>
   <input data-uk-datepicker="{format:'DD/MM/YYYY'}" placeholder="To Date" name="dt" id="dt" type="text" value="" autocomplete="off">
   </div>
      <!-- <div class="uk-width-medium-2-10" style="width: auto;">
         Months selection<br>
         <a class="uk-button" id="month">Pick a Month</a>
         <input type="hidden" name="selected_month" id="selected_month" value="">
      </div>		 -->
	  <div style="margin-top:20px; white-space: nowrap;" class="uk-width-medium-1-10" style="width: 16%;">
			  <button onclick="generateReport('screen');" id="btnGenerate" class="uk-button">Show on screen</button>
			  <button onclick="generateReport('print');" id="btnGenerate" class="uk-button">Print</button>
	   </div>   </div>
</div>
<div id="sctResult" class="" style="border-top-right-radius: 15px;border-top-left-radius: 15px;"></div>
<form id="frmPrint" action="admin/a_rptSales.php" method="post" target="_blank">
   <input type="hidden" name="method" value="print">
   <input type="hidden" id="frmPrintCentreCode" name="centre_code" value="">
   <input type="hidden" id="frmPrintStudent" name="student" value="">
   <input type="hidden" id="frmPrintdf" name="df" value="">
    <input type="hidden" id="frmPrintdt" name="dt" value="">
   <!-- <input type="hidden" id="frmPrintselected_month" name="selected_month" value=""> -->
   <input type="hidden" id="frmPrintCollection" name="collection" value="">
</form>
<style>
.btn-qdees{
   display: none;
}
</style>