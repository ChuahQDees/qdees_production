<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);//user_name
}

function getRights($rights) {
   global $connection, $user_name;

   $sql="SELECT * from user_right where user_name='$user_name' and `right`='$rights'";
   echo $sql;
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      return "checked";
   } else {
      return "";
   }
}

function getUserType($user_name) {
   global $connection;

   $sql="SELECT user_type from user where user_name='$user_name'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["user_type"];
}
function getUserRole($user_name) {
   global $connection;

   $sql="SELECT user_role from user where user_name='$user_name'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["user_role"];
}
//  echo $_SESSION["UserType"]; 
if (getUserType($user_name)=="S" || getUserType($user_name)=="H" || getUserType($user_name)=="C" || 
getUserType($user_name)=="R" || getUserType($user_name)=="CM" || getUserType($user_name)=="T") {
   $the_rights=array("Dashboard"=>"Dashboard","Master"=>"Master", "Centre"=>"Centre", "Student"=>"Student", "MasterRecords"=>"Master Records",
   "Class"=>"Class", "Fee"=>"Fee", "Product"=>"Product", "User"=>"User", "UserRights"=>"User Rights",
   "UserPassword"=>"User Password", "Sales"=>"Sales", "OrderStatus"=>"Order Status", "StockBalances"=>"Stock Balances",
   "DefectiveStatus"=>"Defective Status", "StockAdjustment"=>"Stock Adjustment", "Declaration"=>"Declaration",

   "AcknowledgeDefective"=>"Defective - Acknowledge", "LogisticApproveDefective"=>"Defective - Logistic Approve",
   "PackedDefective"=>"Defective - Packed", "DeliveryDefective"=>"Defective - Delivery",
   "ConfirmPaymentDefective"=>"Defective - Confirm Payment", "FinanceApproveDefective"=>"Defective - Finance Approve",


   "AcknowledgeOrder"=>"Order - Acknowledge", "LogisticApprove"=>"Order - Logistic Approve", "AssignedTo"=>"Order - Assigned To", "Packed"=>"Order - Packed",
   "Delivery"=>"Order - Delivery", "ConfirmPayment"=>"Order - Confirm Payment", "FinanceApprove"=>"Order - Finance Approve",

   "KIV"=>"KIV",
   "SlotCollectionManagement"=>"Slot Collection Management",
   "BufferStock"=>"Buffer Stock",
   "CentreStatementAccount"=>"Centre Statement Account",

   "Reporting"=>"Reporting", "AddOnProduct"=>"Addon Product" );

}else if (getUserType($user_name)=="A") {
   if ($_SESSION["UserType"]=="S"){

   
   $the_rights=array("Dashboard"=>"Dashboard", "Student"=>"Student Population", "Allocation"=>"Class Allocation", "User"=>"User", "UserRights"=>"User Rights",
   "UserPassword"=>"User Password", "Visitor"=>"Visitor", "Sales"=>"Sales", "PointOfSales"=>"Point Of Sales",
   "StockBalances"=>"Stock Balances", "Order"=>"Order", "OrderStatus"=>"Order Status", "ExportContact"=>"Export Contact",
   "Declaration"=>"Declaration", "Reporting"=>"Reporting", "DefectiveProduct"=>"Defective Product",
   "DefectiveStatus"=>"Defective Status",

   "KIV"=>"KIV", 
   "CentreStatementAccount"=>"Centre Statement Account",
   "Discount"=>"Discount", "CreditNote"=>"Credit Note",

   "AddOnProduct"=>"Addon Product");
}


else{
   $the_rights=array("Dashboard"=>"Dashboard", "Student"=>"Student Population", "Allocation"=>"Class Allocation", "User"=>"User", "UserRights"=>"User Rights",
   "UserPassword"=>"User Password", "Visitor"=>"Visitor", "Sales"=>"Sales", "PointOfSales"=>"Point Of Sales",
   "StockBalances"=>"Stock Balances", "Order"=>"Order", "OrderStatus"=>"Order Status", "ExportContact"=>"Export Contact",
   "Declaration"=>"Declaration", "Reporting"=>"Reporting", "DefectiveProduct"=>"Defective Product",
   "DefectiveStatus"=>"Defective Status",

   "KIV"=>"KIV", 
   "CentreStatementAccount"=>"Centre Statement Account",
  

   "AddOnProduct"=>"Addon Product");
}
if (getUserRole($user_name)=="Operator" && $_SESSION["UserType"]=="A") {
   $the_rights=array("Dashboard"=>"Dashboard", "Student"=>"Student Population", "Allocation"=>"Class Allocation", "User"=>"User", "UserRights"=>"User Rights",
   "UserPassword"=>"User Password", "Visitor"=>"Visitor", "Sales"=>"Sales", "PointOfSales"=>"Point Of Sales",
   "StockBalances"=>"Stock Balances", "Order"=>"Order", "OrderStatus"=>"Order Status", "ExportContact"=>"Export Contact",
   "Declaration"=>"Declaration", "Reporting"=>"Reporting", "DefectiveProduct"=>"Defective Product",
   "DefectiveStatus"=>"Defective Status",

   "KIV"=>"KIV", 
   "CentreStatementAccount"=>"Centre Statement Account",
   "Discount"=>"Discount", "CreditNote"=>"Credit Note",

   "AddOnProduct"=>"Addon Product");
}
else{
   $the_rights=array("Dashboard"=>"Dashboard", "Student"=>"Student Population", "Allocation"=>"Class Allocation",  "Visitor"=>"Visitor", "Sales"=>"Sales", "PointOfSales"=>"Point Of Sales",
   "StockBalances"=>"Stock Balances", "Order"=>"Order", "OrderStatus"=>"Order Status", "ExportContact"=>"Export Contact",
   "Declaration"=>"Declaration", "Reporting"=>"Reporting", "DefectiveProduct"=>"Defective Product",
   "DefectiveStatus"=>"Defective Status",

   "KIV"=>"KIV", 
   "CentreStatementAccount"=>"Centre Statement Account",
   


   "AddOnProduct"=>"Addon Product");
}
} else if ((getUserType($user_name)=="O") ||  (getUserRole($user_name)=="Operator")){
   $the_rights=array("Dashboard"=>"Dashboard", "Student"=>"Student Population", "Allocation"=>"Class Allocation", "UserPassword"=>"User Password",
   "Visitor"=>"Visitor", "PointOfSales"=>"Point Of Sales", "StockBalances"=>"Stock Balances",
   "StockAdjustment"=>"Stock Adjustment", "Order"=>"Order", "OrderStatus"=>"Order Status", "ExportContact"=>"Export Contact",
   "Declaration"=>"Declaration", "Reporting"=>"Reporting", "DefectiveProduct"=>"Defective Product",
   "Discount"=>"Discount", "CreditNote"=>"Credit Note", "Sales"=>"Sales",
   "DefectiveStatus"=>"Defective Status");
}else{
   $the_rights=array("Dashboard"=>"Dashboard", "Dashboard"=>"Dashboard", "Student"=>"Student Population", "Allocation"=>"Class Allocation", "UserPassword"=>"User Password",
   "Visitor"=>"Visitor", "PointOfSales"=>"Point Of Sales", "StockBalances"=>"Stock Balances",
   "StockAdjustment"=>"Stock Adjustment", "Order"=>"Order", "OrderStatus"=>"Order Status", "ExportContact"=>"Export Contact",
   "Declaration"=>"Declaration", "Reporting"=>"Reporting", "DefectiveProduct"=>"Defective Product", "Sales"=>"Sales",
   "DefectiveStatus"=>"Defective Status");
}





//print_r($the_rights); die;
?>
<p>
<script>
function changeEditCheckBox(source, target) {
   var source=$("#"+source);
   var target=$("#"+target);

   if (source.is(":checked")) {
      target.prop("checked", false);
   }
}

function changeViewCheckBox(source, target) {
   var source=$("#"+source);
   var target=$("#"+target);

   if (target.is(":checked")) {
      source.prop("checked", false);
   }
}
// $(function(){
//   $('#ReportingEdit input[type="checkbox"]').prop("checked", true).trigger("ReportingView");
// });
</script>

<h2>User Rights for <?php echo $user_name?>(<?php echo getUserRole($user_name); ?>)(<?php echo getUserType($user_name); ?>)</h2>
<?php
if (hasRightGroupXOR($_SESSION["UserName"], "UserRightsEdit")) {
?>
<form name="frmSaveRights" id="frmSaveRights" method="post" action="admin/UserRightsSave.php">
<?php
}
?>
   <input type="hidden" name="user_name" id="user_name" value="<?php echo $user_name?>">
   <div class="uk-width-6-10">
   <table class="uk-table uk-table-small uk-table-striped">
      <tr class="uk-text-bold">
         <td>Right/Module</td>
         <td>Can Edit</td>
         <td>View Only</td>
      </tr>
<?php
foreach ($the_rights as $key=>$value) {
?>
      <tr>
         <td><?php echo $value?></td>
         <td>
<?php
if (($key!="StockBalances") & ($key!="Dashboard") & ($key!="Reporting")) {
   if (($key=="DefectiveStatus") || ($key=="OrderStatus")) {
      //if ((getUserType($user_name)=="S")) {
?>
            <input type="checkbox" class="editoption" name="<?php echo $key?>Edit" id="<?php echo $key?>Edit" value="1" <?php echo getRights($key."Edit")?>>
            <!-- <input type="checkbox" onclick="changeEditCheckBox('<?php echo $key.'Edit'?>', '<?php echo $key.'View'?>')" class="" name="<?php echo $key?>Edit" id="<?php echo $key?>Edit" value="1" <?php echo getRights($key."Edit")?>> -->
<?php
     // }
   } else {
?>
            <input type="checkbox" class="editoption" name="<?php echo $key?>Edit" id="<?php echo $key?>Edit" value="1" <?php echo getRights($key."Edit")?>>
            <!-- <input type="checkbox" onclick="changeEditCheckBox('<?php echo $key.'Edit'?>', '<?php echo $key.'View'?>')" class="" name="<?php echo $key?>Edit" id="<?php echo $key?>Edit" value="1" <?php echo getRights($key."Edit")?>> -->
<?php
   }
}
?>
         </td>
         <td>
<?php
//if ($key!="StockAdjustment") {
?>
            <!-- <input type="checkbox" onclick="changeViewCheckBox('<?php echo $key.'Edit'?>', '<?php echo $key.'View'?>')" class="" name="<?php echo $key?>View" id="<?php echo $key?>View" value="1" <?php echo getRights($key."View")?>> -->
            <input type="checkbox" class="viewoption" name="<?php echo $key?>View" id="<?php echo $key?>View" value="1" <?php echo getRights($key."View")?>>
<?php
//}
?>
         </td>
      </tr>
<?php
}
?>
   </table>
<?php
if (hasRightGroupXOR($_SESSION["UserName"], "UserRightsEdit")) {
?>
   <button id="btnSave" class="uk-button uk-button-primary">Save</button>
<?php
}
?>
   </div>
<?php
if (hasRightGroupXOR($_SESSION["UserName"], "UserRightsEdit")) {
?>
</form>
<?php
}
?>
</p>
<script>
$(document).ready(function () {
   $(".editoption").change(function() {
    var ischecked= $(this).is(':checked');
    if(ischecked){
      $(this).parent().next().find('.viewoption').prop('checked', true);
    }else{
     // $(this).parent().next().find('.viewoption').prop('checked', false);
    }
    
});

$(".viewoption").change(function() {
    var ischecked= $(this).is(':checked');
    if(!ischecked){
      $(this).parent().prev().find('.editoption').prop('checked', false);
    }
    
});
   
});

</script>