<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");

$current_year=date("Y");
$low_year=$current_year -1;
$high_year=$current_year + 2;

for ($i=$low_year; $i<=$high_year; $i++) {
   $loop_year.="$i,";
}
$loop_year=substr($loop_year, 0, -1);

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
<link rel="stylesheet" type="text/css" href="lib/monthpicker/jquery.monthpicker.css"><script src="lib/monthpicker_all/jquery.monthpicker.js?v=1"></script>
<script>
$(document).ready(function() {
   var today = new Date();
   $("#selected_month").val(today.getFullYear()+''+(("0" + (today.getMonth()+1)).slice(-2)));
   $("#program").val('IE');
   generateReport('screen');
});

function generateReport(method) {
    var centre_code=$("#centre_code").val();
   if (!centre_code) {
     var centre_code=document.getElementById('screens.screenid').value;
  }else{
   var centre_code=$("#centre_code").val();
  }
   var selected_month=$("#selected_month").val();
   var program=$("#program").val();
   var lvl=$("#lvl").val();
   if ((centre_code!="") & (program!="")) {
      if (method=="screen") {
         $.ajax({
            url : "admin/a_rptCentreTermly.php",
            type : "POST",
            data : "centre_code="+centre_code+"&program="+program+"&selected_month="+selected_month+"&lvl="+lvl,
            dataType : "text",
            beforeSend : function(http) {
            },
            success : function(response, status, http) {			$("#aBack").attr("href", "/index.php?p=rpt_centre_termly_stock");
               $("#sctResult").html(response);
            },
            error : function(http, status, error) {
               UIkit.notify("Error:"+error);
            }
         });
      } else {
         $("#frmPrintCentreCode").val(centre_code);
         $("#frmPrintProgram").val(program);
         $("#frmPrintselected_month").val(selected_month);
         $("#frmPrintLvl").val(lvl);
         $("#frmPrint").submit();
      }
   } else {
      UIkit.notify("Please select a centre and a program");
   }
}

$(function() {
   $('#month').monthpicker({
      years: [<?php echo $loop_year?>],
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
         <input type="hidden" name="centre_code" id="centre_code"  value="<?php echo $_SESSION['CentreCode']?>">            <!--<input class="uk-width-medium-1-1" type="text" value="<?php // echo getCentreCompanyName($_SESSION['CentreCode'])?>" readonly>-->			<span type="text" class="uk-width-medium-1-1 uk-text-bold" ><?php echo getCentreCompanyName($_SESSION['CentreCode'])?></span>
        <?php
        } else {
           $result=mysqli_query($connection, $sql);
        ?>
            Centre Name<br>

            <!--<input list="centre_code"  id="screens.screenid" name="centre_code" value="ALL">
				<datalist class="form-control" id="centre_code" style="display: none;">    
				 <option value="ALL">ALL</option>
							  <?php
							 // while ($row=mysqli_fetch_assoc($result)) {
							  ?>
								<option value="<?php // echo $row['centre_code']?>"><?php echo $row["company_name"]?></option>
							  <?php
							  //}
							  ?>
				</datalist>-->
            <select class="uk-width-medium-1-1" name="centre_code" id="centre_code">
              <option value="ALL">ALL</option>
              <?php
              while ($row=mysqli_fetch_assoc($result)) {
              ?>
                <option value="<?php echo $row['centre_code']?>"><?php echo $row["company_name"]?></option>
              <?php
              }
              ?>
           </select> 
        <?php
        }
        ?>
      </div>
      <div class="uk-width-medium-2-10">
        Course Type<br>
        <select class="uk-width-medium-1-1" name="program" id="program">
          <option value="">Programme</option>
          <option value="IE">IE</option>
          <option value="BIEP">BIEP</option>
          <option value="BIMP">BIMP</option>
        </select>
      </div>

      <?php
      $array_lvl = array('BIMPL1','BIMPL2','BIMPL3','BIMPL4','BIMPL5','BIEPL1','BIEPL2','BIEPL3','BIEPL4','BIEPL5','IEL1','IEL2','IEL3','IEL4','IEL5')
      ?>
      <div class="uk-width-medium-2-10">
        Course Level<br>
        <select class="uk-width-medium-1-1" name="lvl" id="lvl">
          <option value="ALL" <?php echo $selected_lvl == 'ALL' ? 'selected' : '' ?>>ALL Level</option>
          <?php
          foreach ($array_lvl as $key => $lvl) {
          ?>
            <option value="<?php echo $lvl?>" <?php echo $lvl == $selected_lvl ? 'selected' : '' ?>><?php echo $lvl?></option>
          <?php
          }
          ?>
        </select>
      </div>
      <div class="uk-grid uk-width-medium-4-10">
        <div class="uk-width-medium-2-5" style="padding-right:0px;">
          Months selection<br>
          <a class="uk-button" id="month">Pick a Month</a>
          <input type="hidden" name="selected_month" id="selected_month" value="">
        </div>
        <div class="uk-width-medium-2-5" style="white-space: nowrap;margin-top: 20px; padding-right:0px;">
         <button onclick="generateReport('screen');" id="btnGenerate" class="uk-button">Show on Screen</button>
        <button onclick="generateReport('print');" id="btnGenerate" class="uk-button">Print</button>
        </div>
      </div>
    </div>
</div>
<div id="sctResult" ></div>
<form id="frmPrint" action="admin/a_rptCentreTermly.php" method="post" target="_blank" style="background: none!important; box-shadow: none!important; padding: 0;">
   <input type="hidden" name="method" value="print">
   <input type="hidden" id="frmPrintCentreCode" name="centre_code" value="">
   <input type="hidden" id="frmPrintProgram" name="program" value="">
   <input type="hidden" id="frmPrintselected_month" name="selected_month" value="">
   <input type="hidden" id="frmPrintLvl" name="lvl" value="">
</form>