<?php
session_start();
include_once("../mysql.php");

function generateRandomString($length) {
   $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
   $charactersLength = strlen($characters);
   $randomString = '';
   for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
   }
   return $randomString;
}

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);
}

$tmp_document=$_FILES["doc"]["tmp_name"];
$document=generateRandomString(8).$extension;
if (is_uploaded_file($tmp_document)) {
   copy($tmp_document, 'uploads/'.$document);
} else {
   $document="";
}

$today=date("Y-m-d H:i:s");
$sql="UPDATE `defective` set payment_document='$document', finance_payment_paid_by='".$_SESSION["UserName"]."',
finance_payment_paid_on='".$today."' where sha1(order_no)='$sOrderNo'";
$result=mysqli_query($connection, $sql);
if ($result) {
   echo "1|Update successful";
//   echo "1|$sql";
} else {
   echo "0|Update failed";
}
?>