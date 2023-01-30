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
$year=$_SESSION['Year'];
$collection_month=date("n");
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


if ($placement!="") {
   $sql="INSERT into busket (session_id, allocation_id, centre_code, batch_no, collection_date_time, product_code, qty, unit_price, amount, collection_type, 
   `year`, collection_month, pic, student_id, student_code, subject) values ('$session_id', '0', '$centre_code', '$batch_no', '$now', '$product_code', '$qty', '$unit_price', 
   '$placement', 'placement', '$year', '$collection_month', '$user_name', '$student_id', '$student_code', '$placement_subject')";

   $result=mysqli_query($connection, $sql);
}

header("location: ../index.php?p=collection&ssid=$ssid");
?>