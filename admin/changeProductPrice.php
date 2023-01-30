<?php
session_start();
include_once("../mysql.php");
foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);//product_code, unit_price
}

$session_id=session_id();

$sql="UPDATE busket set unit_price='$unit_price' where session_id='$session_id' and product_code='$product_code'";
$result=mysqli_query($connection, $sql);
if ($result) {
   echo "1|Updated successfully";
} else {
   echo "0|Update failed";
}
?>