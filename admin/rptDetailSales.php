<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");

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

<script>

   function doCentreCodeChange() {
   var centre_code=$("#centre_code").val();

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

function generateReport(method) {
   var centre_code=$("[name='centre_code']").val();
   if(centre_code==""){
      centre_code="ALL"
   }
   var df=$("#df").val();
   var dt=$("#dt").val();
   var student=$("#student").val();
   var collection_type=$("#collection_type").val();
   var filter_by = $("#filter_by").val();

   if ((centre_code!="") & (df!="") & (dt!="")) {
      if (method=="screen") {
         $.ajax({
            url : "admin/a_rptDetailSales.php",
            type : "POST",
            data : "centre_code="+centre_code+"&df="+df+"&dt="+dt+"&student="+student+"&collection_type="+collection_type+"&filter_by="+filter_by,
            dataType : "text",
            beforeSend : function(http) {
            },
            success : function(response, status, http) {				$("#aBack").attr("href", "/index.php?p=rpt_detail_sales");
               $("#sctResult").html(response);
            },
            error : function(http, status, error) {
               UIkit.notify("Error:"+error);
            }
         });
      } else if(method == "excel") {
         $("#frmExcelCentreCode").val(centre_code);
         $("#frmExcelCollection").val(collection_type);
         $("#frmExcelFilterBy").val(filter_by);
         $("#frmExceldf").val(df);
         $("#frmExceldt").val(dt);
         $("#frmExcelstudent").val(student);
         $("#frmExcel").submit();
      } else {
         $("#frmPrintCentreCode").val(centre_code);
         $("#frmPrintCollection").val(collection_type);
         $("#frmPrintFilterBy").val(filter_by);
         $("#frmPrintdf").val(df);
         $("#frmPrintdt").val(dt);
         $("#frmPrintstudent").val(student);
         $("#frmPrint").submit();
      }
   } else {
      //UIkit.notify("Please select a Centre, From Date and To Date");
   }

}
</script>
<style>
   .uk-grid > * {
    padding-right: 5px;
}
</style>
<a href="/" id="aBack">                    <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span></a>
<span>
    <span class="page_title"><img src="/images/title_Icons/Payment History.png">INDIVIDUAL STUDENT COLLECTION DETAILS

</span>

<div class="uk-width-1-1 myheader">
                <h2 class="uk-text-center myheader-text-color myheader-text-style">Searching</h2>
            </div>
<div class="uk-form nice-form" style="border-top-right-radius: 15px;border-top-left-radius: 15px;">
   <div class="uk-grid uk-grid-small">
      <div style="width: 25%;padding-bottom:10px;" class="uk-width-medium-2-10">
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
            <input type="hidden" name="centre_code" id="centre_code"  value="<?php echo $_SESSION['CentreCode']?>">
            <!--<input type="text" class="uk-width-medium-1-1" value="<?php // echo getCentreCompanyName($_SESSION['CentreCode'])?>" readonly>-->			<span type="text" class="uk-width-medium-1-1 uk-text-bold" ><?php echo getCentreCompanyName($_SESSION['CentreCode'])?></span>
         <?php
         } else {
            $result=mysqli_query($connection, $sql);
         ?>
            Centre Name<br>
            <input style="width:100%;" list="centre_code" id="screens.screenid" name="centre_code">

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

            </datalist>

            <!-- <select class="uk-width-medium-1-1" name="centre_code" id="centre_code" >
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
      <div style="width: 25%;padding-bottom:10px;" class="uk-width-medium-2-10">
      Filter By<br>
            <select name="filter_by" id="filter_by" class="uk-width-medium-1-1">
               <option value="">Select </option>
               <option value="mobile">Mobile app</option>
               <option value="registration">Registration</option>
               <option value="insurance">Insurance</option>
               <option value="Uniform (2 sets)">Uniform (2 sets)</option>
               <option value="Gymwear (1 set)">Gymwear (1 set)</option>
               <option value="q-dees-level-kit">Q-dees Level Kit</option>
               <option value="Q-dees Bag">Q-dees Bag</option>
               <option value="tuition">School Fees</option>
               <option value="Multimedia Fees">Multimedia Fees</option>
               <option value="Facility Fees">Facility Fees</option>
               <option value="Enhanced Foundation Mandarin">Enhanced Foundation Mandarin</option>
               <option value="math">IQ Math</option>
               <option value="mandarin">Mandarin</option>
               <option value="international">International Art</option>
               <option value="foundation">International English</option>
               <option value="integrated">Integrated Module</option>
               <option value="link">Link & Think</option>
               <option value="mandarinmodules">Mandarin Modules</option>
               <option value="pendidikan">Pendidikan Islam</option>
               <option value="addon-product">Add-On Products</option>
            </select>
      </div>
      <div style="width: 25%;padding-bottom:10px;" class="uk-width-medium-2-10">
      <!-- Collection Type<br>
         <select class="uk-width-medium-1-1" name="collection_type" id="collection_type">
            <option value="">All Collection Type</option>
            <option value="tuition">Tuition</option>
            <option value="placement">Placement</option>
            <option value="registration">Registration</option>
            <option value="deposit">Deposit</option>
            <option value="product">Product</option>
         </select> -->
         Payment Type<br>
            <select name="collection_type" id="collection_type" class="uk-width-medium-1-1">
               <option value="">Select a payment type</option>
               <option value="CC">Credit Card</option>
               <option value="CASH">Cash</option>
               <option value="BT">Bank Transfer</option>
               <option value="CHEQUE">Cheque</option>
               <option value="CN">Credit Note</option>
            </select>
      </div>
      <div style="width: 25%;" class="uk-width-medium-6-10">
         Student Code/Name<br>
         <input type="text" name="student" id="student" class="uk-width-medium-1-1" placeholder="Student Code/Name">
      </div>
      <div style="width: 25%;" class="uk-width-medium-2-10">
         From Date<br>
         <input class="uk-width-medium-1-1" data-uk-datepicker="{format:'DD/MM/YYYY', minDate: '<?php echo date('d/m/Y',strtotime($year_start_date)); ?>', maxDate: '<?php echo date('d/m/Y',strtotime($year_end_date)); ?>'}" placeholder="From Date" name="df" id="df" type="text" value="" autocomplete="off" readonly>
      </div>
      <div style="width: 25%;" class="uk-width-medium-2-10">
         To Date<br>
         <input class="uk-width-medium-1-1" data-uk-datepicker="{format:'DD/MM/YYYY', minDate: '<?php echo date('d/m/Y',strtotime($year_start_date)); ?>', maxDate: '<?php echo date('d/m/Y',strtotime($year_end_date)); ?>'}" placeholder="To Date" name="dt" id="dt" type="text" value="" autocomplete="off" readonly>
      </div>
      <div style="width: 50%;margin-top:20px; padding-right:0px;" class="uk-width-medium-2-10">
         <button onclick="generateReport('screen');" id="btnGenerate" class="uk-button" >Show on screen</button>
         <button onclick="generateReport('print');" id="btnGenerate" class="uk-button" >Print</button>
         <button onclick="generateReport('excel');" id="btnGenerate" class="uk-button" >Export CSV</button>
      </div>
   </div>
</div>
<div id="sctResult" style="border-top-right-radius: 15px;border-top-left-radius: 15px;"></div>
<form id="frmPrint" action="admin/a_rptDetailSales.php" method="post" target="_blank" style="background: none!important; box-shadow: none!important; padding: 0;" >
   <input type="hidden" name="method" value="print">
   <input type="hidden" id="frmPrintCentreCode" name="centre_code" value="">
   <input type="hidden" id="frmPrintdf" name="df" value="">
   <input type="hidden" id="frmPrintdt" name="dt" value="">
   <input type="hidden" id="frmPrintstudent" name="student" value="">
   <input type="hidden" id="frmPrintCollection" name="collection_type" value="">
   <input type="hidden" id="frmPrintFilterBy" name="filter_by" value="">
</form>

<form id="frmExcel" action="admin/export_rptDetailSales.php" method="post" style="background: none!important; box-shadow: none!important; padding: 0;" >
   <input type="hidden" name="method" value="excel">
   <input type="hidden" id="frmExcelCentreCode" name="centre_code" value="">
   <input type="hidden" id="frmExceldf" name="df" value="">
   <input type="hidden" id="frmExceldt" name="dt" value="">
   <input type="hidden" id="frmExcelstudent" name="student" value="">
   <input type="hidden" id="frmExcelCollection" name="collection_type" value="">
   <input type="hidden" id="frmExcelFilterBy" name="filter_by" value="">
</form>
<style>
.btn-qdees{
   display: none;
}
</style>
