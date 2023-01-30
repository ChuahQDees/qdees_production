<?php
session_start();
include_once("../mysql.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //product_code, unit_price, qty
}

if (($product_code!="") & ($unit_price!="") & ($qty!="")) {
   echo number_format($unit_price*$qty, 2);
} else {
   echo "0.00";
}
?>