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

$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);
$row=mysqli_fetch_assoc($result);

if ($num_row>0) {
   $_SESSION["isLogin"]=1;
   $_SESSION["UserType"]=$row["user_type"];
   $_SESSION["UserName"]=$row["user_name"];
   $_SESSION["Name"]=$row["name"];
   $_SESSION["CentreCode"]=$row["centre_code"];
   
   if (!$row["centre_code"] && $row["user_type"] = "S"){ //Center code blank and it's a HQ Account
	   $_SESSION["CentreCode"]="MYQWESTC1C10001";
   }
   $_SESSION["Country"]=getCountry($row["centre_code"]);
   $_SESSION["Email"]=$row["email"];

   $year_data = mysqli_query($connection,"SELECT `year` FROM `schedule_term` WHERE `centre_code` = '".$_SESSION["CentreCode"]."' AND ('".date('Y-m-d')."' BETWEEN `term_start` AND `term_end`)");

   if(mysqli_num_rows($year_data) > 0) {
      $year_row = mysqli_fetch_array($year_data);
      $_SESSION['Year'] = $year_row['year'];
   } else {
      $_SESSION['Year']=date("Y");
   }
   
   $_SESSION["CanAdjustFee"]=getCanAdjustFee($_SESSION["CentreCode"]);

   if(($row['api_key'] == null || $row['api_key'] == '') && ($_SESSION["UserType"]!="S"))
   {
      $api_key = getKey();

      mysqli_query($connection,"UPDATE `user` SET `api_key` = '$api_key', `helpdesk_role` = 'Franchisor' WHERE `id` = '".$row['id']."'");

     curlCall("admin/add-user?centre_name=".urlencode($user_name)."&email=".$row['email']."&password=".$password."&country=".$row['country']."&api_key=".$api_key."&role=Franchisor");
   }

   echo "1|Login successful";
} else {
   echo "0|Login failed, please try again";
}
?>
