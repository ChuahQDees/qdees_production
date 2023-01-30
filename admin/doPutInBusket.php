<?php
session_start();
$session_id=session_id();
include_once("../mysql.php");

$sql="SELECT * from tmp_busket where session_id='$session_id' order by id";
$result=mysqli_query($connection, $sql);

function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function getStudentCodeByID($student_id) {
   global $connection;

   $sql="SELECT student_code from student where id='$student_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["student_code"];
}

function getCourseID($allocation_id) {
   global $connection;

   $sql="SELECT c.* from course c, allocation a where a.course_id=c.id and a.id='$allocation_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   if (count($row) > 0) {
    return $row["id"];
   } else {
    return null;
   }  
}

while ($row=mysqli_fetch_assoc($result)) {
  foreach ($row as $key=>$value) {
    $$key=$value;
  }

  $batch_no=generateRandomString(10);
  $student_code=getStudentCodeByID($student_id);
  $allocation_id = $allocation_id ? $allocation_id : 0;
  $amount = $row["qty"] * $row["unit_price"];
  if (in_array($product_code, array("BIMP", "BIEP", "IE"))) {
    $subject = $product_code;
    $product_code = '';
  } else {
    $subject = null;
  }
  $course_id=getCourseID($allocation_id);

  if ($course_id != null) {
    $isql="INSERT into busket (session_id, allocation_id, centre_code, batch_no, collection_date_time, product_code, qty, unit_price, amount, 
    collection_type, `year`, collection_month, pic, student_id, student_code, subject, course_id, collection, collection_pattern, single_year) 
    values ('$session_id', '$allocation_id', '$centre_code', '$batch_no', '$collection_date_time', '$product_code', '$qty', '$unit_price', '$amount', '$collection_type', 
    '$year', '$collection_month', '$pic', '$student_id', '$student_code', '$subject', '$course_id', '$unit_price', '$collection_pattern', '$single_year')";
  } else {
    $isql="INSERT into busket (session_id, allocation_id, centre_code, batch_no, collection_date_time, product_code, qty, unit_price, amount, 
    collection_type, `year`, collection_month, pic, student_id, student_code, subject, collection, collection_pattern, single_year) 
    values ('$session_id', '$allocation_id', '$centre_code', '$batch_no', '$collection_date_time', '$product_code', '$qty', '$unit_price', '$amount', '$collection_type', 
    '$year', '$collection_month', '$pic', '$student_id', '$student_code', '$subject', '$unit_price', '$collection_pattern', '$single_year')";    
  }
//echo $isql; die;
  $iresult=mysqli_query($connection, $isql);

  if ($iresult) {
    $dsql="DELETE from tmp_busket where session_id='$session_id'";
    $dresult=mysqli_query($connection, $dsql);
    
    echo "1|Added to basket successfully";
  } else {
    echo "0|Failed";
  }
}
?>