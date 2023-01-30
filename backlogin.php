<?php
session_start();
include_once("mysql.php");
include_once("admin/functions.php");

function getCanAdjustFee($centre_code) {
   global $connection;

   $sql="SELECT can_adjust_fee from centre where centre_code='$centre_code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return $row["can_adjust_fee"];
}

$user_name=$_POST["user_name"];
$user_name=str_replace("'", "", $user_name);
$user_name=str_replace('"', '', $user_name);

$password=$_POST["password"];
$password=str_replace("'", "", $password);
$password=str_replace('"', '', $password);

$sql="SELECT * from user where user_name='$user_name' and `password`=password('$password') and is_active=1";
//echo "0|$sql";
$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);
$row=mysqli_fetch_assoc($result);

if ($num_row>0) {
   $_SESSION["isLogin"]=1;
   $_SESSION["UserType"]=$row["user_type"];
   $_SESSION["UserName"]=$row["user_name"];
   $_SESSION["Name"]=$row["name"];
   $_SESSION["CentreCode"]=$row["centre_code"];
   $_SESSION["Country"]=getCountry($row["centre_code"]);
   $_SESSION["Email"]=$row["email"];
   $_SESSION["Year"]=date("Y");
   $_SESSION["CanAdjustFee"]=getCanAdjustFee($_SESSION["CentreCode"]);

   echo "1|Login successful";
} else {
   echo "0|Login failed, please try again";
}
?>