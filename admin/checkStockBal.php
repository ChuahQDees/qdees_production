<?php
session_start();
$session_id=session_id();
include_once("../mysql.php");
include("functions.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //$product_code; $qty; $student_code
}

if ($product_code!="") {
   $centre_code=$_SESSION["centre_code"];
   $cut_off_date=date("Y-m-d");
   $qty=1;
   $qty_in_basket=getQtyInBasket($session_id, $student_code, $product_code);

   $bal=calcBal($centre_code, $product_code, $cut_off_date);

   if ($bal>=$qty+$qty_in_basket) {
      echo "1";
   } else {
      echo "0";
   }
}
?>