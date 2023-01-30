<?php
session_start();
include_once("../mysql.php");

foreach($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //$sha_id, $sOrderNo
}

if ($sOrderNo!="") {
   $datetime=date("Y-m-d H:i:s");
   $sql="UPDATE `defective` set acknowledged_by='".$_SESSION["UserName"]."', acknowledged_on='".$datetime."' where sha1(order_no)='$sOrderNo'";
   $result=mysqli_query($connection, $sql);

   if ($result) {
      echo "1|Update successfully";
   } else {
      echo "0|Updated failed";
   }
} else {
   echo "0|Something is wrong, cannot proceed";
//   echo "0|$sha_id|$sOrderNo";
}
?>