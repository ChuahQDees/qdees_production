<?php
include_once('../mysql.php');

$user_name=mysqli_real_escape_string($connection, $_POST["user_name"]);

function deleteRights($user_name) {
   global $connection;

   $sql="DELETE from user_right where user_name='$user_name'";
   $result=mysqli_query($connection, $sql);
   if ($result) {
      return true;
   } else {
      return false;
   }
}

deleteRights($user_name);

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);//all rights parameters+user_name

   if ($key!="user_name") {
      if ($value=="1") {
         $sql="INSERT into user_right (user_name, `right`) values ('$user_name', '$key')";
         mysqli_query($connection, $sql);
      }
   }
}

header("location: ../index.php?p=userright&user_name=$user_name");
  