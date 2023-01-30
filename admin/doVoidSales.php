<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);//$id, $void_reason
}

function isRecordFound($id) {
   global $connection;

   $sql="SELECT * from collection where sha1(id)='$id'";
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      return true;
   } else {
      return false;
   }
}

function voidRecord($id, $void_reason) {
   global $connection;

   $cn_no=generateRandomNumber(8);

   $sql="UPDATE collection set void=1, void_reason='$void_reason', cn_no='$cn_no' where sha1(id)='$id'";
   $result=mysqli_query($connection, $sql);

   if ($result) {
      return true;
   } else {
      return false;
   }
}

if (($id!="") & ($void_reason!="")) {
   if (isRecordFound($id)) {
      if (voidRecord($id, $void_reason)) {
         echo "1|Transaction voided";
      } else {
         echo "0|Void failed";
      }
   } else {
      echo "0|Record not found";
   }
}
?>