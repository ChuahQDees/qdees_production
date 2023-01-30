<?php
include_once("../mysql.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);
}

function isRightFound($user_name, $user_right) {
   global $connection;

   $sql="SELECT * from user_right where user_name='$user_name' and right='$user_right'";
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      return true;
   } else {
      return false;
   }
}

if (($user_name!="") & ($user_right!="")) {
   if (!isRightFound($user_name, $user_right)) {
      $sql="INSERT into user_right (user_name, `right`) values ('$user_name', '$user_right')";
      $result=mysqli_query($connection, $sql);
      if ($result) {
         echo "1|Right added";
      } else {
         echo "0|Adding right failed";
      }
   } else {
      echo "0|Right already exists";
   }
} else {
   echo "0|Please fill in both fields";
}
?>