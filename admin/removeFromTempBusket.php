<?php
session_start();
$session_id=session_id();
include_once("../mysql.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //$bid, $product_code, $student_code
}

if (($product_code!="") & ($student_code!="")) {
   $sql="DELETE from tmp_busket where id='$bid'";
   $result=mysqli_query($connection, $sql);
   if ($result) {
      echo "1|Product removed";
   } else {
      echo "0|Remove failed";
   }
} else {
   echo "0|$product_code,$student_code";
}
?>