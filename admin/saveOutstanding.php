<?php
session_start();
$session_id=session_id();
$year=$_SESSION['Year'];
$pic=$_SESSION["UserName"];
include_once("../mysql.php");

foreach ($_POST as $key=>$value) {
	$$key=$value;
}

$centre_code=$_SESSION["CentreCode"];
$product_code="";
$qty=0;
$unit_price=0;

function getStudentID($student_code) {
   global $connection;

   $sql="SELECT * from student where student_code='$student_code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["id"];
}

function getCourseID($allocation_id) {
   global $connection;

   $sql="SELECT c.* from course c, allocation a where a.course_id=c.id and a.id='$allocation_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["id"];
}

$student_id=getStudentID($student_code);

if (isset($_POST["allocation_id"])) {
   foreach ($_POST["allocation_id"] as $key=>$value) {
      if ($_POST["the_fee".$value]!="") {
         $current_the_fee=$_POST["the_fee".$value];
         $now=date("Y-m-d H:i:s");
		$course_id=getCourseID($value);
         $sql="INSERT into busket (session_id, allocation_id, centre_code, batch_no, collection_date_time, product_code, qty, unit_price, amount, collection_type, 
         `year`, collection_month, pic, student_id, student_code, course_id) values ('$session_id', '$value', '$centre_code', '$batch_no', '$now', '$product_code', 
         '$qty', '$unit_price', '$current_the_fee', 'tuition', '$year', '".$_POST["month".$value]."', '$pic', '$student_id', '$student_code', '$course_id')";

         $result=mysqli_query($connection, $sql);
      }
   }
}

var_dump(isset($schoolInput));die;
header("location: ../index.php?p=collection&ssid=".sha1($ssid));
?>