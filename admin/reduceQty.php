<?php
session_start();
$session_id=session_id();

include_once("../mysql.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //$bid, $product_code, $student_code
}

function getQty($bid, $product_code, $student_code) {
   global $connection, $session_id;

   $sql="SELECT qty from tmp_busket where id='$bid'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["qty"];
}

if (($product_code!="") & ($student_code!="")) {
   $qty=getQty($bid, $product_code, $student_code);
   if ($qty>1) {
      $new_qty=$qty-1;
      $sql="UPDATE tmp_busket set qty='$new_qty' where id='$bid'";
      $result=mysqli_query($connection, $sql);
      if ($result) {
         echo "1|Qty reduced";
      } else {
         echo "0|Failed";
      }
   }
   //else {
      //echo "0|Cannot reduce anymore";
   //}
} else {
   echo "0|Minimum quantity is 1";
}
?>