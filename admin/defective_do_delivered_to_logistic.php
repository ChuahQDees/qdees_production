<?php
session_start();
include_once("../mysql.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);
}

//echo $sha_id=$_POST["sha_id"]; die;
// $name=$_POST["name"];
// $ic_no=$_POST["ic_no"];
// $tracking_no=$_POST["tracking_no"];
// $courier=$_POST["courier"];
// $jsonSignature=$_POST["jsonSignature"];
// $sOrderNo=$_POST["sOrderNo"];

if (($sOrderNo!="") & ($name!="") & ($ic_no!="") & ($tracking_no!="") & ($jsonSignature!="") & ($courier!="")) {
   $datetime=date("Y-m-d H:i:s");
   $sql="UPDATE `defective` set delivered_to_logistic_by='".$_SESSION["UserName"]."',
   delivered_to_logistic_on='$datetime', name='$name', ic_no='$ic_no', signature='$jsonSignature',
   tracking_no='$tracking_no', courier='$courier' where sha1(order_no)='$sOrderNo'";
   $result=mysqli_query($connection, $sql);

   if ($result) {
      echo "1|Update successfully";
   } else {
      echo "0|Updated failed";
   }
} else {
//   echo "0|$sha_id|$name|$ic_no";
   echo "0|Something is wrong, cannot proceed";
}
?>