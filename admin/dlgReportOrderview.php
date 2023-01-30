<?php
session_start();
include_once("../mysql.php");

function getProductName($product_code) {
   global $connection;

   $sql="SELECT * from product where product_code='$product_code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["product_name"];
}

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);//$id (sha1)
}
//echo $id;
 $sql="SELECT distinct o.order_no, o.ordered_by, o.ordered_on, o.centre_code, d.qty, o.unit_price, o.total, o.product_code, o.remarks, p.product_name, d.defective_reason, d.doc, d.remarks from `order` o, `product` p, `defective` d where p.product_code=o.product_code and o.order_no=d.sales_order_no and o.id='$id'";
 //$sql="SELECT distinct o.order_no, o.ordered_by, o.ordered_on, o.centre_code, d.qty, o.unit_price, o.total, o.product_code, o.remarks, d.defective_reason, d.doc, d.remarks from `order` o, `product` p, `defective` d where  o.order_no=d.sales_order_no and o.id='$id'";
 
 //echo $sql;
//$sql="SELECT * from `defective` where id='$id'";
$result=mysqli_query($connection, $sql);
$row=mysqli_fetch_assoc($result);
$doc = $row['doc'];
//echo $doc;
?>

<div class="uk-form">
<form id="frmDefective" name="frmDefective" class="uk-form uk-form-small">
<input type="hidden" id="id" name="id" value="<?php echo $id?>">
<table class="uk-table uk-table-small uk-table-striped">
	<tr class="uk-text-small">
      <td class="uk-text-bold">Centre Code</td>
      <td><?php echo $row["centre_code"]?></td>
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
         echo $product_code?> (<?php echo $row["product_name"]?>)
      </td>
   </tr>
   <tr class="uk-text-small">
      <td class="uk-text-bold">Qty</td>
      <td>
         <input name="qty" class="uk-form-small uk-width-1-1 fs14" id="qty" value="<?php echo $row["qty"]?>" readonly>
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
         <input name="qty" class="uk-form-small uk-width-1-1 fs14" id="qty" value="<?php echo $row["defective_reason"]?>" readonly>
      </td>
   </tr>
   <tr class="uk-text-small">
      <td class="uk-text-bold">Upload Photo</td>
      <td>
      <a type="button" class="btn btn-primary aa" data-toggle="modal" data-target="#myModal"><img  width='100' height='100' src='/admin/uploads/<?php echo $doc; ?>' alt='image'></a>
      <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-body">
        <button type="button" class="close asda" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
            <img src='/admin/uploads/<?php echo $doc; ?>' class="img-responsive">
        </div>
    </div>
  </div>
</div>
<script>
  $(function() {
          $('.pop').on('click', function() {
              $('.imagepreview').attr('src', $(this).find('img').attr('src'));
              $('#imagemodal').modal('show');   
          });     
  });
</script>
      </td>
   </tr>
   <tr class="uk-text-small">
      <td class="uk-text-bold">Remarks</td>
      <td>
         <input name="qty" class="uk-form-small uk-width-1-1 fs14" id="qty" value="<?php echo $row["remarks"]?>" readonly>
      </td>
   </tr>
</table>
</form><br>
<div class="uk-text-center">
  <!-- <button class="uk-button uk-button-primary" onclick="reportDefective()">Report</button>-->
   <button class='uk-button' onclick="$('#dlg').dialog('close')">Cancel</button>
</div>

</div>
<style>
.uk-text-small {
    font-size: 14px;
}
.modal-backdrop.show {
    opacity: 0;
}
.close.asda{
   width: 50px;
    font-size: 50px;
    line-height: 0.8;
    position: relative;
    left: 36px;

}
.modal-backdrop {
    position: unset;
    }
    .modal .modal-dialog .modal-content .modal-body {
      padding: 0 35px 35px;
    background-color: #ffffff;
    border: 0.5px solid #b3b3b3;
    border-radius: 5px;
}
.btn.btn-primary.aa{
   box-shadow: unset;
    background-color: unset;
    border-color: unset;
    border: unset;
}
input.fs14{
   font-size: 14px!important;
    border: 0!important;
    background: #00000005!important;
}
</style>