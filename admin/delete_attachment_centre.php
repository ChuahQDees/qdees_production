<?php
include_once("functions.php");
include_once("../mysql.php");

$id=$_POST["id"];

function getThisCentreCode($id) {
   global $connection;

   $sql="SELECT centre_code from centre_agreement_file where id='$id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["centre_code"];
}

function deleteAttachmentFile($id) {
   global $connection;

   $sql="SELECT * from centre_agreement_file where id='$id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   $attachment_file=$row["attachment"];

   unlink("admin/uploads/$attachment_file");
}

if ($id!="") {
   $centre_code=getThisCentreCode($id);
   deleteAttachmentFile($id);

   $sql="DELETE from centre_agreement_file where id='$id'";
   $result=mysqli_query($connection, $sql);

   if ($result) {
      echoCentreAttachment($centre_code);
   } else {
      echo "0|Deletion failed";
   }
} else {
   echo "0|Failed";
}
?>