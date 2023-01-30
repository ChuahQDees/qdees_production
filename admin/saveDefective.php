<?php
session_start();
include_once("../mysql.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //centre_code, product_code, qty, unit_price, total
}

if (($centre_code!="") & ($product_code!="") & ($qty!="") & ($unit_price!="") & ($total!="")) {
   $sql="INSERT into defective (centre_code, product_code, qty, unit_price, total, ordered_by, ordered_on)
   values ('$centre_code', '$product_code', '$qty', '$unit_price', '$total', '".$_SESSION["UserName"]."', '".date("Y-m-d H:i:s")."')";
   $result=mysqli_query($connection, $sql);
   if ($result) {
      echo "1|Defective created";
   }  else {
      echo "0|Save failed";
   }
} else {
   echo "0|All fields are required";
}
?>