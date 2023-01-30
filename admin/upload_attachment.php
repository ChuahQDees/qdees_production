<?php
include_once("functions.php");
include_once("../mysql.php");

$master_code=$_POST["master_code"];

$tmp_document=$_FILES["attachment"]["tmp_name"];
$attachment_file=$master_code."-".generateRandomString(8).".pdf";
if (is_uploaded_file($tmp_document)) {
   copy($tmp_document, 'uploads/'.$attachment_file);
   $sql="INSERT into master_agreement_file (master_code, attachment) values ('$master_code', '$attachment_file')";
   $result=mysqli_query($connection, $sql);

   if ($result) {
      echoAttachment($master_code);
   } else {
      echo "0|Upload failed";
   }
} else {
   $attachment_file="";
}

?>