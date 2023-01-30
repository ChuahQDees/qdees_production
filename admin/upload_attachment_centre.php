<?php
include_once("functions.php");
include_once("../mysql.php");

$centre_code=$_POST["centre_code"];
$doc_type=$_POST["doc_type"];

$tmp_document=$_FILES["attachment"]["tmp_name"];
$attachment_file=$centre_code."-".generateRandomString(8).".pdf";
if (is_uploaded_file($tmp_document)) {
   copy($tmp_document, 'uploads/'.$attachment_file);
   $sql="INSERT into centre_agreement_file (centre_code, doc_type, attachment) values ('$centre_code', '$doc_type', '$attachment_file')";
   $result=mysqli_query($connection, $sql);

   if ($result) {
      echoCentreAttachment($centre_code);
   } else {
      echo "0|Upload failed";
   }
} else {
   echo "0|Failed";
}

?>