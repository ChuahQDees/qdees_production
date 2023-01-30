<?php
include_once("../mysql.php");

$product_code=$_POST["product_code"];

function deleteProductCourse($product_code) {
   global $connection;

   $sql="DELETE from product_course where product_code='$product_code'";
   $result=mysqli_query($connection, $sql);

   if ($result) {
      return true;
   } else {
      return false;
   }
}

deleteProductCourse($product_code);

$course_id=$_POST["course_id"];

$count=0;
foreach ($course_id as $key=>$value) {
   $sql="INSERT into product_course (product_code, course_id) values ('$product_code', ";
   $sql.="'".$course_id[$key]."')";
   $result=mysqli_query($connection, $sql);
}

//echo $sql;
//print_r($course_id);
echo "Record saved";
?>