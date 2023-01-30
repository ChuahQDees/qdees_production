<?php
session_start();
$session_id=session_id();
include_once("../mysql.php");
echo "in";
$centre_code=$_SESSION["CentreCode"];
$now=date("Y-m-d H:i:s");
$product_code="";
$qty=0;
$unit_price=0;
$amount=0;
$year=$_SESSION["Year"];
$collection_month="";
$pic=$_SESSION["UserName"];
$sha1_student_id=$_POST["ssid"];
$sha1_student_code=$_POST["student_code"];
$user_name=$_SESSION["UserName"];

function getStudentInfo($sha1_student_id, &$student_id, &$student_code) {
   global $connection;

   $sql="SELECT * from student where sha1(id)='$sha1_student_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   $student_id=$row["id"];
   $student_code=$row["student_code"];
}

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
   $$key=$value;
//   echo "$key=".$value."<br>";
}

$batch_no=generateRandomString(10);
getStudentInfo($ssid, $student_id, $student_code);

if (isset($_POST["allocation_id"])) {
   foreach ($_POST["allocation_id"] as $key=>$value) {
      if ($_POST["placement".$value]!="") {
         $current_placement=$_POST["placement".$value];
         $sql="INSERT into busket (session_id, allocation_id, centre_code, batch_no, collection_date_time, product_code, qty, unit_price, amount, collection_type, 
         `year`, collection_month, pic, student_id, student_code) values ('$session_id', '$value', '$centre_code', '$batch_no', '$now', '$product_code', 
         '$qty', '$unit_price', '$current_placement', 'placement', '$year', '0', '$user_name', '$student_id', '$student_code')";

         $result=mysqli_query($connection, $sql);
      }
   }
}

header("location: ../index.php?p=collection&ssid=$ssid");
?>