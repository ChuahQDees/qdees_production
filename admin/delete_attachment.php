<?php
include_once("functions.php");
include_once("../mysql.php");

$id=$_POST["id"];

function getThisMasterCode($id) {
   global $connection;

   $sql="SELECT master_code from master_agreement_file where id='$id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["master_code"];
}

function deleteAttachmentFile($id) {
   global $connection;

   $sql="SELECT * from master_agreement_file where id='$id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   $attachment_file=$row["attachment"];

   unlink("admin/uploads/$attachment_file");
}

if ($id!="") {
   $master_code=getThisMasterCode($id);
   deleteAttachmentFile($id);

   $sql="DELETE from master_agreement_file where id='$id'";
   $result=mysqli_query($connection, $sql);

   if ($result) {
      echoAttachment($master_code);
   } else {
      echo "0|Deletion failed";
   }
} else {
   echo "0|Failed";
}
?>