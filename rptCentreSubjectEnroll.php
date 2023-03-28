<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");

$current_year=date("Y");
$low_year=$current_year-1;
$high_year=$current_year+2;

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

<link rel="stylesheet" type="text/css" href="lib/monthpicker/jquery.monthpicker.css">
<script src="lib/monthpicker_all/jquery.monthpicker.js"></script>
<script>
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
       var y = '<?php echo $y; ?>';
       var m = date.getMonth();
       var day = date.getDate();
       firstDay = ("0" + (new Date(y, m, 1)).getDate()).slice(-2);
       lastDay = (new Date(y, m + 1, 0)).getDate();
   $("#df").val(y+'/'+ (("0" + (m+1)).slice(-2)) +'/'+'01');
   $("#dt").val(y+'/'+(("0" + (m+1)).slice(-2))+'/'+lastDay);
   generateReport('screen');
});
   
function generateReport(method) {
   //var centre_code=$("#centre_code").val();
   var centre_code=$("[name='centre_code']").val();
   if(centre_code=="" || centre_code=="All Centre"){
      centre_code="ALL";
   }
   var from_date=$("#df").val();
   var to_date=$("#dt").val();
    var selected_year=$("#selected_year").val();
   var course_subject=$("#course_subject").val();

   if (centre_code!="") {
      if (method=="screen") {
         $.ajax({
            url : "admin/a_rptCentreSubjectEnroll.php",
            type : "POST",
            data : "centre_code="+centre_code+"&from_date="+from_date+"&to_date="+to_date+"&course_subject="+course_subject+"&selected_year="+selected_year,
            dataType : "text",
            beforeSend : function(http) {
            },
            success : function(response, status, http) {
				//console.log(response.from_date);
			$("#aBack").attr("href", "/index.php?p=rpt_centre_subject_enrl");
               $("#sctResult").html(response);
            },
            error : function(http, status, error) {
               UIkit.notify("Error:"+error);
            }
         });
      } else {
         $("#frmPrintCentreCode").val(centre_code);
         $("#from_date").val(from_date);
         $("#to_date").val(to_date);
         $("#frmPrintselected_year").val(selected_year);
         $("#frmPrintSubject").val(course_subject);
         $("#frmPrint").submit()
      }
   } else {
      UIkit.notify("Please select a centre");
   }
}

$(function() {
   $('#yearMonth').monthpicker({
      years: [2021,2020,2019],
      topOffset: 6,
      onMonthSelect: function(m, y) {
         var month=m+1;

         if (month<10) {
            month="0"+month;
         }

         $("#selected_month").val(y+month);
         $("#selected_year").val(y);

      }
   });
});
</script>
<!--<a href="/" id="aBack">     <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span></a>-->
<div class="uk-width-1-1 myheader">
                <h2 class="uk-text-center myheader-text-color myheader-text-style">Searching</h2>
            </div>
<div class="uk-form nice-form uk-grid" style="border-top-right-radius: 15px;border-top-left-radius: 15px;">
<?php
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "ReportingView"))) {
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

<?php    
   $centrecode = $_SESSION['CentreCode'];

   $sqlCentre = "SELECT centre_code, company_name from centre where centre_code = '$centrecode'  limit 1 ";
   $resultCentre=mysqli_query($connection, $sqlCentre);
   $rowCentre=mysqli_fetch_assoc($resultCentre);
   // var_dump($rowCentre["kindergarten_name"]);die; 
?>
   <div class="uk-width-medium-2-10">
      Centre Name<br>	  <input type="hidden" name="centre_code" id="centre_code" value="<?php echo $_SESSION['CentreCode']?>">	<span type="text" class="uk-width-medium-1-1 uk-text-bold" ><?php echo $rowCentre['company_name']?></span>	
   <!--<input style="width: 100%;" name="centre_name" id="centre_name" value="<?php // echo $rowCentre['company_name']?>" readonly>	
       <select  class="uk-width-1-1" id="selectCentre">
         <option>Select Centre Name</option>

         <?php while ($rowCentre=mysqli_fetch_assoc($resultCentre)):?>
         <option value="<?=$rowCentre["centre_code"] ?>"><?=$rowCentre["kindergarten_name"] ?></option>
      <?php endwhile; ?>

      </select>  -->
   </div>
<?php
} else {
   $result=mysqli_query($connection, $sql);
?>

   <div class="uk-width-medium-3-10">
      Centre Name<br>
      <input type="hidden"  id="hfCenterCode" name="centre_code"">
            <input list="centre_code" id="screens.screenid" name="company_name" style="width: 100%;">
      <datalist class="form-control" id="centre_code" style="display: none;">
         <option value="ALL" <?php echo $centreCode == 'ALL' ? 'selected' : '' ?>>All Centre</option>
         <?php
         while ($row=mysqli_fetch_assoc($result)) {
         ?>
            <option value="<?php echo $row['company_name'] ?>" <?php echo $row['company_name'] == $centreCode ? 'selected' : '' ?>><?php echo $row["centre_code"] ?></option>
         <?php
         }
         ?>
      </datalist>
      <script>
                      var objCompanyName = document.getElementById('screens.screenid');
                      $(document).on('change', objCompanyName, function(){
                       // console.log("options[i].text")
                          var options = $('datalist')[0].options;
                          for (var i=0;i<options.length;i++){
                         // console.log($(objCompanyName).val())
                            if (options[i].value == $(objCompanyName).val()) 
                              {
                                $("#hfCenterCode").val(options[i].text);
                                break;
                              }
                          }
                      });
                    </script>
   </div>
<?php
}

//$sql3="SELECT * from course order by subject";
//$result3=mysqli_query($connection, $sql3);
?>

<input type="hidden" id="course_subject" name="course_subject" value="">
<div class="uk-width-medium-1-10">
	Level<br>
	<select name="course_subject" id="select_course_subject">
		<option value="">Select</option>
		<option <?php if($_GET['course_subject']=="EDP"){ echo "selected"; } ?> value="EDP">EDP</option>
		<option <?php if($_GET['course_subject']=="QF1"){ echo "selected"; } ?> value="QF1">QF1</option>
		<option <?php if($_GET['course_subject']=="QF2"){ echo "selected"; } ?> value="QF2">QF2</option>
		<option <?php if($_GET['course_subject']=="QF3"){ echo "selected"; } ?> value="QF3">QF3</option>
	</select>
</div>
<div class="uk-width-medium-2-10">
   Date From<br>
   <input type="text" class="uk-width-1-1" name="df" id="df" placeholder="Date From" data-uk-datepicker="{format: 'YYYY-MM-DD'}" value="" autocomplete="off">
   <!-- <select class="uk-width-1-1" name="df" id="df"></select> -->
</div>
<div class="uk-width-medium-2-10">
   Date To<br>
   <input type="text" class="uk-width-1-1" name="dt" id="dt" placeholder="Date To" data-uk-datepicker="{format: 'YYYY-MM-DD'}" value="" autocomplete="off">
   <!-- <select class="uk-width-1-1" name="dt" id="dt"></select> -->
</div>
   <!--<div style="width: 15%;" class="uk-width-medium-2-10">
         Months selection<br>
         <a class="uk-button" id="yearMonth">Pick a Month</a>
         <input type="hidden" name="selected_month" id="selected_month" value="">
         <input type="hidden" name="selected_year" id="selected_year" value="">
      </div>-->
      
	<div class="uk-width-medium-2-10">
		 <br>
		<button onclick="generateReport('screen')" id="btnGenerate" class="uk-button">Show on screen</button>
		<button onclick="generateReport('print')" id="btnGenerate" class="uk-button">Print</button>
	</div>

</div>
<div id="sctResult" class="" style="border-top-right-radius: 15px;border-top-left-radius: 15px;"></div>
<form id="frmPrint" action="admin/a_rptCentreSubjectEnroll.php" method="post" target="_blank" style="background: none!important; box-shadow: none!important; padding: 0;" >
   <input type="hidden" name="method" value="print">
   <input type="hidden" id="frmPrintCentreCode" name="centre_code" value="">
   <input type="hidden" id="from_date" name="from_date" value="">
   <input type="hidden" id="to_date" name="to_date" value="">
   <input type="hidden" id="frmPrintselected_year" name="selected_year" value="">
   <input type="hidden" id="frmPrintSubject" name="course_subject" value="">
</form>
<style>
.uk-grid > * {
     padding-right: 0px;
}

</style>
<script type="text/javascript">
     $('#selectCentre').on('change', function() {
      $("[name='centre_code']").val(this.value);
});

      $('#select_course_subject').on('change', function() {
   $("#course_subject").val(this.value);
});
</script>