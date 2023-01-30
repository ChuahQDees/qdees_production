<?php
include_once("../mysql.php");
foreach ($_POST as $key=>$value) {
	$$key=mysqli_real_escape_string($connection, $value);
}

?>

	<div class="uk-overflow-container uk-grid uk-grid-small">
         <div class="uk-width-medium-10-10 uk-text-center">
            <table class="uk-table">
               <tr class="uk-text-bold">
                  <td>No.</td>
                  <td>Photo</td>
                  <td>Code</td>
                  <td>Name</td>
                  <td>Quantity</td>
               </tr>
<?php
$sql="SELECT p.product_photo, c.product_code, p.product_name, sum(qty) as qty from product p, collection c 
	where p.product_code=c.product_code and sha1(student_id)='$ssid' and collection_type='product' group by c.product_code";
//echo $sql;
$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);
if ($num_row>0) {
   $count=0;
   while ($row=mysqli_fetch_assoc($result)) {
      $count++;
      switch ($row["payment_method"]) {
         case "CC" : $payment_method="Credit Card"; break;
         case "CASH" : $payment_method="Cash"; break;
         case "BT" : $payment_method="Bank Transfer"; break;
         case "CHEQUE" : $payment_method="Cheque"; break;
         default : $payment_method=$row["payment_method"]; break;
      }
?>
               <tr>
                  <td><?php echo $count?></td>
                  <td>
				  <?php if ($row["product_photo"]): ?>
					<a href="admin/uploads/<?php echo $row["product_photo"]?>" data-uk-lightbox>
						<img src="admin/uploads/<?php echo $row["product_photo"]?>" width="50">
					</a>
				  <?php endif ?>
				  </td>
                  <td><?php echo $row["product_code"]?></td>
                  <td><?php echo $row["product_name"]?></td>
                  <td><?php echo $row["qty"]?></td>
                </tr>
<?php
   }
} else {
   echo "<tr class='uk-text-bold uk-text-small'><td colspan='5'>No Record Found</td></tr>";
}
?>
			</table>
         </div>
	</div>
