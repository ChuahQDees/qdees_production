<a href="/" id="aBack"><span class="btn-qdees d_none"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span></a>
<span>
    <span class="page_title"><img src="/images/title_Icons/Payment.png">Payment</span>
</span>

<?php
if (($_SESSION["isLogin"]==1) & (($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) &
(hasRightGroupXOR($_SESSION["UserName"], "PointOfSalesEdit|PointOfSalesView"))) {
include_once("mysql.php");
include_once("admin/functions.php");
include_once("lib/pagination/pagination.php");

$year=$_SESSION['Year'];
$centre_code=$_SESSION["CentreCode"];

$p=$_GET["p"];
$level_type=$_GET["level_type"];

function isGotProductSale($allocation_id, $collection_month) {
   global $connection, $year;

   $sql="SELECT * from collection where allocation_id='$allocation_id' and collection_type='product'
   and `year`='$year' and collection_month='$collection_month'";
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      return true;
   } else {
      return false;
   }
}

function getBatchNo($allocation_id, $collection_month) {
   global $connection;

   $sql="SELECT * from collection where allocation_id='$allocation_id' and collection_month='$collection_month'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["batch_no"];
}

function getFees($allocation_id, $collection_month, $collection_type) {
   global $connection, $year;

   $sql="SELECT sum(amount) as amount from collection where allocation_id='$allocation_id' and collection_type='$collection_type'
   and `year`='$year' and collection_month='$collection_month' group by allocation_id";

   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);
   $row=mysqli_fetch_assoc($result);

   if ($num_row>0) {
      return $row["amount"];
   } else {
      return "0";
   }
}

function getExtraFees($allocation_id, $collection_type) {
   global $connection, $year;

   $sql="SELECT sum(amount) as amount from collection where allocation_id='$allocation_id' and collection_type='$collection_type'
   and `year`='$year' group by allocation_id";

   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);
   $row=mysqli_fetch_assoc($result);

   if ($num_row>0) {
      return $row["amount"];
   } else {
      return "0";
   }
}

?>

<div id="thetop" class="uk-margin-right uk-margin-top">
   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color myheader-text-style">Point of Sales</h2>
   </div>
   <form class="uk-form" name="frmCollection" id="frmCollection" method="post" style="border-bottom-left-radius: 0!important;border-bottom-right-radius: 0!important;">
      <div class="uk-grid uk-grid-small">
         <div class="uk-width-medium-1-10 d_none" style="padding-right: 0px;">
<script>
function onProgrammeChange() {
   var programme=$("#programme").val();

   if (programme!="") {
      $.ajax({
         url : "admin/onProgrammeChange.php",
         type : "POST",
         data : "programme="+programme,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            $("#level").html(response);
         },
         error : function(http, status, error) {
            UIkit.notify("Error:"+error);
         }
      });
   } else {
      s="";
      s="<select name='level' id='level' class='uk-width-1-1'>";
      s=s+"<option value=''>Select Programme First</option>";
      s=s+"</select>";

      $("#level").html(s);
   }
}
</script>
            <select name="programme" id="programme" onchange="onProgrammeChange()" class="uk-width-1-1 uk-form-small">
               <option value="">Programme</option>
               <option value="BIEP">BIEP</option>
               <option value="BIMP">BIMP</option>
               <option value="IE">IE</option>
            </select>
         </div>
<script>
   function doSearch() 
   {
      var programme=$("#programme").val();
      var level=$("#level").val();
      var modules=$("#module").val();
      var group=$("#class").val();
      var sname=$("#sname").val();
      var status=$("#sstatus").val();
      var age=$("#age").val();
      var subject = $("#subject").val();

      $('#mydatatable').DataTable({
         "bProcessing": true,
         "bServerSide": true,
         "sAjaxSource": "admin/serverresponse/payment.php?programme="+programme+"&level="+level+"&module="+modules+"&group="+group+"&status="+status+"&sname="+sname+"&age="+age+"&subject="+subject,
         "bDestroy": true
      });
   }

function onLevelChange() {
   var programme=$("#programme").val();
   var level=$("#level").val();

   if ((programme!="") & (level!="")) {
      $.ajax({
         url : "admin/onLevelChange.php",
         type : "POST",
         data : "programme="+programme+"&level="+level,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            $("#module").html(response);
         },
         error : function(http, status, error) {
            UIkit.notify("Error:"+error);
         }
      });
   } else {
      s="";
      s="<select name='module' id='module' class='uk-width-1-1'>";
      s=s+"<option value=''>Select Programme and Level First</option>";
      s=s+"</select>";

      $("#module").html(s);
   }
}
</script>
         <div class="uk-width-medium-2-10 d_none" style="padding-right: 0px;">
            <select name="level" id="level" onchange="onLevelChange()" class="uk-width-1-1 uk-form-small">
               <option value="">Select Programme First</option>
            </select>
         </div>
         <div class="uk-width-medium-2-10 d_none" style="padding-right: 0px;">
            <select name="module" id="module" class="uk-width-1-1 uk-form-small">
               <option value="">Select Programme and Level First</option>
            </select>
         </div>
         <div class="uk-width-medium-1-10 d_none" style="padding-right: 0px;">
            <select name="class" id="class" class="uk-width-1-1 uk-form-small">
               <option value="">Group</option>
<?php
for ($i=1; $i<21; $i++) {
?>
               <option value="<?php echo $i?>" <?php if ($class==$i) {echo "selected";}?>><?php echo $i?></option>
<?php
}
?>
            </select>
         </div>
          <!-- <div style="width: 100%; height: .5em"></div> -->
          <div class="uk-width-medium-4-10" style="padding-right: 0px;white-space: nowrap;">
              <div class="uk-width-medium-6-10" style="display: inline-block">
                  <input type="text" name="sname" id="sname" class="uk-width-1-1 uk-form-small" placeholder="Student Code/Name" value="">
              </div>
              <div class="uk-width-medium-3-10" style="display: inline-block">
                  <input type="text" name="age" id="age" class="uk-width-1-1 uk-form-small" placeholder="Age" value="">
              </div>
              <div class="uk-width-medium-3-10" style="display: inline-block">
                  <select name="subject" id="subject" class="uk-width-1-1 uk-form-small">
                      <option value="">Select</option>
                      <option value="EDP">EDP</option>
                      <option value="QF1">QF1</option>
                      <option value="QF2">QF2</option>
                      <option value="QF3">QF3</option>
                  </select>
              </div>
              <div class="uk-width-medium-2-10dn d_none" style="display: inline-block">
                  <select name="sstatus" id="sstatus" class="uk-width-1-1 uk-form-small">
                      <option value="">All</option>
                      <option value="A">Only Active</option>
                      <option value="I">Only Inactive</option>
                  </select>
              </div>
              <div class="uk-width-medium-4-10" style="display: inline-block">
                  <a id="btnSearch" class="uk-button uk-button-small blue_button" style="line-height:24px;" onclick="doSearch();">Search</a>
              </div>
          </div>
          

      </div>
   </form>
   <style>
      .d_none{
         display:none!important;   
      }
   </style>

<div id="sctListing">
   <div class="uk-overflow-container">
      <table id="mydatatable" class="uk-table" style="font-size:12px !important;">
         <thead>
            <tr class="uk-text-small uk-text-bold">
               <td>Student Name</td>
               <td>Student Code</td>
               <td>Age</td>
               <td>Class</td>
               <td>Start Date at Centre</td>
               <td>NRIC</td>
            </tr>
         </thead>
         <tbody>
   
      </tbody>
      </table>
   </div>

</div>
</div>
<script>
$(document).ready(function () {

   var programme=$("#programme").val();
   var level=$("#level").val();
   var modules=$("#module").val();
   var group=$("#class").val();
   var sname=$("#sname").val();
   var status=$("#sstatus").val();
   var age=$("#age").val();
   var subject = $("#subject").val();

   $('#mydatatable').DataTable({
		'columnDefs': [ { 
        'targets': [0,5], // column index (start from 0)
        'orderable': false, // set orderable false for selected columns
      	}],
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": "admin/serverresponse/payment.php?programme="+programme+"&level="+level+"&module="+modules+"&group="+group+"&status="+status+"&sname="+sname+"&age="+age+"&subject="+subject
	});

   //doSearch();
  /*  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      doSearch();
      return false;
    }
  }); */
});
</script>
<?php
} else {
   echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
}
?>

<style>
			
   table tr:not(:first-child):nth-of-type(even) {
      background: #f5f6ff;
   }

   table td {
      color: grey;
      font-size: 1.2em;
   }

   table td {border: none!important;}

   #mydatatable_length{
      display: none;
   }

   #mydatatable_filter{
      display: none;
   }

   /*#mydatatable_paginate{float:initial;text-align:center}*/
   #mydatatable_paginate .paginate_button{display: inline-block; min-width: 16px; padding: 3px 5px; line-height: 20px; text-decoration: none; -moz-box-sizing: content-box; box-sizing: content-box; text-align: center; background: #f7f7f7; color: #444444; border: 1px solid rgba(0, 0, 0, 0.2); border-bottom-color: rgba(0, 0, 0, 0.3); background-origin: border-box; background-image: -webkit-linear-gradient(top, #ffffff, #eeeeee); background-image: linear-gradient(to bottom, #ffffff, #eeeeee); text-shadow: 0 1px 0 #ffffff;margin-left: 3px;
   margin-right: 3px}
   #mydatatable_paginate .paginate_button.current{background: #009dd8; color: #ffffff!important; border: 1px solid rgba(0, 0, 0, 0.2); border-bottom-color: rgba(0, 0, 0, 0.4); background-origin: border-box; background-image: -webkit-linear-gradient(top, #00b4f5, #008dc5); background-image: linear-gradient(to bottom, #00b4f5, #008dc5); text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.2);}
   #mydatatable_filter{width:100%}
   #mydatatable_filter label{width:100%;display:inline-flex}
   #mydatatable_filter label input{height: 30px;width: 100%; padding: 4px 6px; border: 1px solid #dddddd; background: #ffffff; color: #444444; -webkit-transition: all linear 0.2s; transition: all linear 0.2s; border-radius: 4px;}
</style>