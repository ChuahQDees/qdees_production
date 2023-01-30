<?php
session_start();
$year=$_SESSION['Year'];
include_once("../mysql.php");
$allocation_id=$_POST["allocation_id"];
$collection_month=$_POST["collection_month"];
$batch_no=$_POST["batch_no"];
$ssid=$_POST["ssid"];
//echo "ssid is ".$ssid;

function getStudentID($ssid) {
   global $connection;

   $sql="SELECT id from student where sha1(id)='$ssid'";
//   echo $sql;
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return $row["id"];
}

function getPlainBatchNo($batch_no) {
   global $connection;

   $sql="SELECT batch_no from collection where sha1(batch_no)='$batch_no'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return $row["batch_no"];
}

$student_id=getStudentID($ssid);

$sql="SELECT c.*, p.product_name, p.product_photo from collection c, product p, allocation a, student s where c.product_code=p.product_code
and c.collection_type='product' and c.year='$year' and c.allocation_id=a.id and a.student_id=s.id and s.id='$student_id'";
//and sha1(c.batch_no)='$batch_no'";
//echo $sql;
$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);
?>

<!-- <h3>Batch No. : <?php echo getPlainBatchNo($batch_no)?></h3> -->
<table class="uk-table">
   <tr class="uk-text-bold uk-text-small">
      <td>Photo</td>
      <td>Code</td>
      <td>Name</td>
      <td>Qty</td>
      <td>U/Price</td>
      <td>Amount</td>
   </tr>
<?php
if ($num_row>0) {
while ($row=mysqli_fetch_assoc($result)) {
?>
   <tr class="uk-text-small">
      <td>
         <a href="admin/uploads/<?php echo $row['product_photo']?>" data-uk-lightbox title="Click for bigger image"><img src="admin/uploads/<?php echo $row['product_photo']?>" width='50'></a>
      </td>
      <td><?php echo $row["product_code"]?></td>
      <td><?php echo $row["product_name"]?></td>
      <td><?php echo $row["qty"]?></td>
      <td><?php echo $row["unit_price"]?></td>
      <td><?php echo $row["amount"]?></td>
   </tr>
<?php
}
} else {
   echo "<tr class='uk-text-small uk-text-bold'><td colspan='6'>No record found</td></tr>";
}
?>
</table>
<button class="uk-button uk-button-small uk-align-right" onclick="$('#dlgProductSale').dialog('close');">Close</button>