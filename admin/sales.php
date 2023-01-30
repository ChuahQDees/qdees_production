<!--<a href="/">                 
				 <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>-->
<span>
    <span style="position:absolute;right:23px;" class="page_title"><img src="/images/title_Icons/Payment History.png">Payment History</span>
</span>

<?php
session_start();
include_once("../mysql.php");
if ($_SESSION["isLogin"]==1) {
   if (($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O") & (hasRightGroupXOR($_SESSION["UserName"], "SalesEdit|SalesView"))) {
?>
<?php
$m = date('m');
if($m >= date('m',strtotime($year_start_date))) {
   $y = date('Y',strtotime($year_start_date));
} else {
   $y = date('Y',strtotime($year_end_date));
}
?>
<script>
$(document).ready(function() {
   var date = new Date();
       //y = date.getFullYear();
       y = '<?php echo $y; ?>';
       m = date.getMonth();
       firstDay = ("0" + (new Date(y, m, 1)).getDate()).slice(-2);
       lastDay = (new Date(y, m + 1, 0)).getDate();
   $("#date_from").val(firstDay+'/'+(("0" + (m+1)).slice(-2))+'/'+y);
   $("#date_to").val(lastDay+'/'+(("0" + (m+1)).slice(-2))+'/'+y);
});
function searchSales() {
   var df=$("#date_from").val();
   var dt=$("#date_to").val();
   
   var student=$("#student").val();
   var collection_type=$("#collection_type").val();
   var product_code=$("#product_code").val();

   $.ajax({
      url : "admin/searchSales.php",
      type : "POST",
      data : "df="+df+"&dt="+dt+"&student="+student+"&collection_type="+collection_type+"&product_code="+product_code,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         $("#sctResult").html(response);
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}

$(function () {
   searchSales();
});
</script>
<div style="margin-top: 36px;" class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color myheader-text-style">Sales</h2>
   </div>
<div class="uk-form" style="background:white;">
   <div class="uk-grid uk-grid-small" style="margin:0px;padding:10px;">
      <div class="uk-width-medium-2-10" style="padding-left:0px; padding-right:0px">
         <input class="uk-width-medium-1-1" type="text" data-uk-datepicker="{format:'DD/MM/YYYY', minDate: '<?php echo date('d/m/Y',strtotime($year_start_date)); ?>', maxDate: '<?php echo date('d/m/Y',strtotime($year_end_date)); ?>'}" placeholder="From" name="date_from" id="date_from" value="" autocomplete="off">
      </div>
      <div class="uk-width-medium-2-10" style="padding-right:0px">
         <input class="uk-width-medium-1-1" type="text" data-uk-datepicker="{format:'DD/MM/YYYY', minDate: '<?php echo date('d/m/Y',strtotime($year_start_date)); ?>', maxDate: '<?php echo date('d/m/Y',strtotime($year_end_date)); ?>'}" placeholder="To" name="date_to" id="date_to" value="" autocomplete="off">
      </div>
      <div class="uk-width-medium-4-10" style="padding-right:0px">
         <input class="uk-width-medium-1-1" type="text" name="student" id="student" placeholder="Student No./Name" value="">
      </div>

      <!--div class="uk-width-medium-2-10" style="padding-right:0px">
         <select name="collection_type" id="collection_type" class="uk-width-medium-1-1">
            <option value="">Collection Type</option>
            <option value="tuition">Tuition Fee</option>
            <option value="registration">Registration</option>
            <option value="deposit">Deposit</option>
            <option value="placement">Placement</option>
            <option value="product">Product Sale</option>
         </select>
      </div-->
      <div class="uk-width-medium-1-10" style="padding-right:0px">
         <input class="uk-width-medium-1-1" type="text" name="product_code" id="product_code" placeholder="Receipt No." value="">
      </div> 
      <div class="uk-width-medium-1-10" style="padding-right:0px">
         <a onclick="searchSales();" class="uk-button uk-width-medium-1-1 blue_button">Search</a>
      </div>
   </div>
   <div id="sctResult"></div>
</div>
<?php
   } else {
      echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
   }
} else {
   header("Location: index.php");;
}
?>