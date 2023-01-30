<?php
session_start();
include_once("../mysql.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($value); //product_code
}
$product_code=$_POST["product_code"];
$sql="SELECT * from product where product_code='$product_code'";

$result=mysqli_query($connection, $sql);
//print_r($result); die;
$num_row=mysqli_num_rows($result);
if ($num_row>0) {
	while ($row=mysqli_fetch_assoc($result)) {
	echo number_format($row["unit_price"], 2);
	}
} else {
   echo "0.00";
}
?>