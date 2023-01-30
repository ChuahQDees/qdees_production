<?php
session_start();
include_once("../mysql.php");

$year=$_SESSION['year'];

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //programme, level, module, class_id
}

$course_name=$programme.$level.$module;

$sql="SELECT * from course where course_name like '$course_name%'";
$result=mysqli_query($connection, $sql);
$row=mysqli_fetch_assoc($result);
$course_id=$row["id"];

if ($course_id!="") {
   $sql="SELECT * from `group` where `year`='$year' and course_id='$course_id' and class_id='$class_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   $num_row=mysqli_num_rows($result);
   if ($num_row>0) {
      echo "1|".$row["id"];
   } else {
      echo "0|Class not registered";
   }
} else {
   echo "0|Invalid data";
}
?>