<?php
session_start();
include_once("../mysql.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);//$id (sha1), product_code
}

function getProductName($product_code) {
   global $connection;
   
   $sql="SELECT * from product where product_code='$product_code'";
   //echo $sql; die;
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["product_name"];
}


//$sql="SELECT distinct d.order_no, d.ordered_by, d.ordered_on, d.centre_code, d.qty, d.unit_price, d.total, d.product_code, d.defective_reason, d.doc, d.remarks, p.product_name from `defective` d, `product` p where p.product_code=d.product_code and d.id='$id'";

$sql="SELECT * from `order` where sha1(id)='$id'";
$result=mysqli_query($connection, $sql);
$row=mysqli_fetch_assoc($result);
?>

<script>
function reportDefective() {
   var id=$("#id").val();
   var qty=$("#qty").val();
   var ori_qty=$("#ori_qty").val();
   var defective_reason=$("#defective_reason").val();
   var doc=$("#doc").val();

   if (id!="") {
      //if (qty<=ori_qty) {
         if (defective_reason!="") {
            if (doc!="") {
               var theform = $("#frmDefective")[0];
               var formdata = new FormData(theform);

               $.ajax({
                  url : "admin/reportDefective.php",
                  type : "POST",
                  data : formdata,
                  dataType : "text",
                  enctype: 'multipart/form-data',
                  processData: false,
                  contentType: false,
                  success : function(response, status, http) {
                     var s=response.split("|");

                     if (s[0]=="0") {
                        UIkit.notify(s[1]);
                     } else {
                        UIkit.notify(s[1]);
                        $("#dlg").dialog("close");
                     }
                  },
                  error : function(http, status, error) {
                    UIkit.notify("Error:"+error);
                  }
               });
            } else {
               UIkit.notify("Please snap and attach a photo");
            }
         } else {
            UIkit.notify("Please select a defective reason");
         }
      // } else {
         // UIkit.notify('Please enter a valid quantity');
      // }
   } else {
      UIkit.notify("Something is wrong, please contact administrator");
   }
}
</script>

<div class="uk-form">
<form id="frmDefective" name="frmDefective" class="uk-form uk-form-small">
<input type="hidden" id="id" name="id" value="<?php echo $id?>">
<input type="hidden" id="product_code" name="product_code" value="<?php echo $product_code?>">
<table class="uk-table uk-table-small uk-table-striped">
   <tr class="uk-text-small">
      <td class="uk-text-bold">Centre Code</td>
      <td><?php echo $_SESSION["CentreCode"]?></td>
   </tr>
   <tr class="uk-text-small">
      <td class="uk-text-bold">Order No.</td>
      <td>
         <?php echo $row["order_no"]?>
         <input type="hidden" name="sales_order_no" id="sales_order_no" value="<?php echo $row["order_no"]?>">
      </td>
   </tr>
   <tr class="uk-text-small">
      <td class="uk-text-bold">Product</td>
      <td>
	  <?php $product_code = explode("((--",$row["product_code"])[0];
         echo $product_code?> (<?php echo  getProductName($product_name)?>)
      </td>
   </tr>
   <tr class="uk-text-small">
      <td class="uk-text-bold">Qty</td>
      <td>
        <input name="qty" class="uk-form-small uk-width-1-1" id="qty" value="">
         <input type="hidden" id="ori_qty" value="<?php echo $row['qty']?>">
		 <script>
			var ori_qty = $("#ori_qty").val();
			$("#qty").val(ori_qty);
		 </script>
      </td>
   </tr>
   <tr class="uk-text-small">
      <td class="uk-text-bold">Unit Price</td>
      <td><?php echo $row["unit_price"]?></td>
   </tr>
   <tr class="uk-text-small">
      <td class="uk-text-bold">Total</td>
      <td><?php echo $row["total"]?></td>
   </tr>
   <tr class="uk-text-small">
      <td class="uk-text-bold">Ordered By</td>
      <td><?php echo $row["ordered_by"]?></td>
   </tr>
   <tr class="uk-text-small">
      <td class="uk-text-bold">Ordered On</td>
      <td><?php echo $row["ordered_on"]?></td>
   </tr>
   <tr class="uk-text-small">
      <td class="uk-text-bold">Reason</td>
      <td>
         <select name="defective_reason" id="defective_reason" class="uk-form-small uk-width-1-1">
            <option value="">Select</option>
<?php
$sql="SELECT * from codes where module='DEFECTIVEREASON'";
$result=mysqli_query($connection, $sql);
while ($row=mysqli_fetch_assoc($result)) {
?>
            <option value="<?php echo $row['code']?>"><?php echo $row["code"]?></option>
<?php
}
?>
         </select>
      </td>
   </tr>
   <tr class="uk-text-small">
      <td class="uk-text-bold">Photo</td>
      <td><input type="file" id="doc" name="doc"></td>
   </tr>
   <tr class="uk-text-small">
      <td class="uk-text-bold">Remarks</td>
      <td><textarea class="uk-width-1-1" id="remarks" name="remarks"></textarea></td>
   </tr>
</table>
</form><br>
<div class="uk-text-center">
   <button class="uk-button uk-button-primary" onclick="reportDefective()">Report</button>
   <button class='uk-button' onclick="$('#dlg').dialog('close')">Cancel</button>
</div>

</div>