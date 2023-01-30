<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");
$year=$_SESSION["Year"];
if (($_SESSION["UserType"]=="S") || ($_SESSION["UserType"]=="H") || ($_SESSION["UserType"]=="C") || ($_SESSION["UserType"]=="R") || ($_SESSION["UserType"]=="CM") || ($_SESSION["UserType"]=="T")) {
  
}else{
   die;
}
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
       var y = '<?php echo $y; ?>';
       var m = date.getMonth();
       var d = date.getDate();
      
   //$("#date_from").val((d<10 ? '0' : '') + d+'/'+(("0" + (m+1)).slice(-2))+'/'+y);
   generateReport('screen');
});

$(document).ready(function() {
   var today = new Date();
   //$("#selected_month").val(today.getFullYear()+''+(("0" + (today.getMonth()+1)).slice(-2)));
   //$("#selected_month").val('<?php //echo $year ?>'+''+(("0" + (today.getMonth()+1)).slice(-2)));
   generateReport('screen');
});

function generateReport(method) {
   //var centre_code=$("#centre_code1").val();
   var centre_code=$("[name='centre_code']").val();
   if(centre_code==""){
      centre_code="ALL";
   }
   // var df=$("#df").val();
   // var dt=$("#dt").val();
   var date_from=$("#date_from").val();
   var date_to=$("#date_to").val();
   var select_time=$("#select_time").val();
   var slot=$("#slot").val();

   //if ((centre_code!="") & (date_from!="") & (select_time!="") & (slot!="")) {
      if (method=="screen") {
         $.ajax({
            url : "admin/a_rptSchedule.php",
            type : "POST",
            data : "centre_code="+centre_code+"&date_from="+date_from+"&date_to="+date_to+"&select_time="+select_time+"&slot="+slot,
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
         $("#frmPrintDateFrom").val(date_from); 
         $("#frmPrintDateTo").val(date_to);
         $("#frmPrintSelectTime").val(select_time);
         $("#frmPrintsSlot").val(slot);
         $("#frmPrint").submit();
      }
   // } else {
   //    UIkit.notify("Please select a Centre and Month");
   // }

}
// $(function() {
// 	var year = '<?php //echo $year; ?>';
//    $('#month').monthpicker({
//       years: [year],
//       topOffset: 6,
//       onMonthSelect: function(m, y) {
//          var month=m+1;

//          if (month<10) {
//             month="0"+month;
//          }

//          $("#selected_month").val(y+month);
//       }
//    });
// });
</script>
<!-- <a href="/" id="aBack">                          	<span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span></a> -->
<a href="/index.php?p=booked_slot_management">                 
         <span class="btn-qdees">Booked Slot</span>
</a>
<span>
    <span class="page_title"><img src="/images/title_Icons/Payment History.png">SCHEDULE REPORT
</span>

<div class="uk-width-1-1 myheader">
                <h2 class="uk-text-center myheader-text-color myheader-text-style">Searching</h2>
            </div>
<div class="uk-form nice-form" style="border-top-right-radius: 15px;border-top-left-radius: 15px;">
   <div class="uk-grid uk-grid-small">
      <div class="uk-width-medium-2-10" style="width: 16%;">
         <?php
         
                  $sql="SELECT * from centre order by centre_code";
               

         
            $result=mysqli_query($connection, $sql);
            ?>
           
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
      <div class="uk-width-medium-1-10" style="width: auto; width: 11%;">
      Date From<br>
   <input data-uk-datepicker="{format:'DD/MM/YYYY'}" placeholder="Date From" name="date_from" id="date_from" type="text" value="" autocomplete="off" >
   </div>
   <div class="uk-width-medium-1-10" style="width: auto;width: 11%;" >
      Date To<br>
   <input data-uk-datepicker="{format:'DD/MM/YYYY'}" placeholder="Date To" name="date_to" id="date_to" type="text" value="" autocomplete="off" >
   </div>
         <div class="uk-width-medium-2-10" style="width: 16%;">
         Select Time<br>
         <select class="uk-width-1-1 " name="select_time" id="select_time">
               <option value="">Select</option>
               <option value="9 AM">9 AM</option>
               <option value="9:30 AM">9:30 AM</option>
               <option value="10 AM">10 AM</option>
               <option value="10:30 AM">10:30 AM</option>
               <option value="11 AM">11 AM</option>
               <option value="11:30 AM">11:30 AM</option>
               <option value="12 PM">12 PM</option>
               <option value="12:30 PM">12:30 PM</option>
               <option value="1 PM">1 PM</option>
               <option value="1:30 PM">1:30 PM</option>
               <option value="2 PM">2 PM</option>
               <option value="2:30 PM">2:30 PM</option>
               <option value="3 PM">3 PM</option>
               <option value="3:30 PM">3:30 PM</option>
               <option value="4 PM">4 PM</option>
               <option value="4:30 PM">4:30 PM</option>
               <option value="5 PM">5 PM</option>
               <option value="5:30 PM">5:30 PM</option>
               <option value="6 PM">6 PM</option>
         </select>
         </div>
         <div class="uk-width-medium-2-10" style="width: 16%;">
         Slot<br>
         <select class="uk-width-1-1 " name="slot" id="slot">
               <option value="">Select</option>
               <option value="1">1</option>
               <option value="2">2</option>
               <option value="3">3</option>
               <option value="4">4</option>
               <option value="5">5</option>
               <option value="6">6</option>
               <option value="7">7</option>
               <option value="8">8</option>
               <option value="9">9</option>
               <option value="10">10</option>
               <option value="11">11</option>
               <option value="12">12</option>
               <option value="13">13</option>
               <option value="14">14</option>
               <option value="15">15</option>
               <option value="16">16</option>
               <option value="17">17</option>
               <option value="18">18</option>
               <option value="19">19</option>
               <option value="20">20</option>
         </select>
         </div>
     
  
    
	  <div style="margin-top:20px; white-space: nowrap;" class="uk-width-medium-1-10" style="width: 16%;">
			  <button onclick="generateReport('screen');" id="btnGenerate" class="uk-button">Show on screen</button>
			  <button onclick="generateReport('print');" id="btnGenerate" class="uk-button">Print</button>
	   </div>   </div>
</div>
<div id="sctResult" class="" style="border-top-right-radius: 15px;border-top-left-radius: 15px;"></div>
<form id="frmPrint" action="admin/a_rptSchedule.php" method="post" target="_blank">
   <input type="hidden" name="method" value="print">
   <input type="hidden" id="frmPrintCentreCode" name="centre_code" value="">
    <input type="hidden" id="frmPrintDateFrom" name="date_from" value="">
    <input type="hidden" id="frmPrintDateTo" name="date_to" value="">
   <input type="hidden" id="frmPrintSelectTime" name="select_time" value="">
   <input type="hidden" id="frmPrintSlot" name="slot" value="">
</form>
<style>
/* .btn-qdees{
   display: none;
} */
</style>