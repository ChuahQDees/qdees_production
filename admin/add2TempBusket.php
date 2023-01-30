<?php

session_start();
$session_id=session_id();
include_once("../mysql.php");
include_once("functions.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //$product_code, $student_code
}

$single_year = isset($single_year) ? $single_year : '';

function isProductCodeExist($product_code) {
   global $connection;

   $sql="SELECT * from product where product_code='$product_code'";
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);
   if ($num_row>0) {
      return true;
   } else {
      return false;
   }
}

function isProductAdded($product_code, $student_code) {
   global $connection, $session_id;

   $sql="SELECT * from tmp_busket where session_id='$session_id' and product_code='$product_code' and student_code='$student_code'";
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      return true;
   } else {
      return false;
   }
}

function isCourseAdded($course_name, $student_code, $collection_type) {
   global $connection, $session_id;

   $sql="SELECT * from tmp_busket where session_id='$session_id' and product_code='$course_name' and student_code='$student_code' and collection_type = '$collection_type'";
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      return true;
   } else {
      return false;
   }
}

function isOutstandingAdded($outstanding_name, $student_code, $collection_type) {
   global $connection, $session_id;

   $sql="SELECT * from tmp_busket where session_id='$session_id' and product_code='$outstanding_name' and student_code='$student_code' and collection_type = '$collection_type'";
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      return true;
   } else {
      return false;
   }
}

function getProductInfo($product_code, &$unit_price) {
   global $connection;
   $centre_code=$_SESSION["CentreCode"];
   $sql="SELECT unit_price from product where product_code='$product_code'";
   //and centre_code='$centre_code'
   //echo $sql; die;
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   $unit_price=$row["unit_price"];
}

function increaseQty($product_code, $student_code, $collection_type, $inquiry_qty=1) {
   global $connection, $session_id;

   $sql="UPDATE tmp_busket set qty=qty+$inquiry_qty, amount=unit_price*(qty) where product_code='$product_code' and student_code='$student_code' and session_id='$session_id' and collection_type = '$collection_type'";
   $result=mysqli_query($connection, $sql);
}

function enoughBal($product_code, $centre_code, $student_code) {
   $today=date("Y-m-d");
   $session_id=session_id();

   $bal=calcBal($centre_code, $product_code, $today);
   $required_qty=getQtyInBasket($session_id, $student_code, $product_code)+1;

   if ($bal>=$required_qty) {
      return true;
   } else {
      return false;
   }
}

function getStudentIDByCode($sha1_student_code) {
   global $connection;

   $sql="SELECT * from student where sha1(student_code)='$sha1_student_code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return $row["id"];
}

if (isset($product_code)) {	
   if (($product_code!="") & ($student_code!="")) {
	   
      getProductInfo($product_code, $unit_price);
      if (isProductCodeExist($product_code)) {
         if (!isProductAdded($product_code, $student_code)) {
            //if (enoughBal($product_code, $_SESSION["CentreCode"], $student_code)) {
				
               $centre_code=$_SESSION["CentreCode"];
               $pic=$_SESSION["UserName"];
               $now=date("Y-m-d H:i:s");
               $year=date("Y");
               $student_id=getStudentIDByCode($student_code);
               $sql="INSERT into tmp_busket (session_id, centre_code, student_code, product_code, qty, unit_price, collection_type, pic, collection_date_time, `year`, student_id, collection_pattern) 
               values ('$session_id', '$centre_code', '$student_code', '$product_code', '1', '$unit_price', 'product', '$pic', '$now', '$year', '$student_id', '$collection_pattern')";
               //echo $sql;
               $result=mysqli_query($connection, $sql);
               if ($result) {
                  echo "1|Product added";
               } else {
                  echo "0|Add failed";
               }
            // } else {
               // echo "0|Insufficient balance";
            // }
         } else {
            if (enoughBal($product_code, $_SESSION["CentreCode"], $student_code)) {
               increaseQty($product_code, $student_code, 'product');
               echo "1|Qty increased";
            } else {
               echo "0|Insufficient balance";
            }
         }
      } else {
         echo "0|Product does not exists";
      }
   } else {
      echo "0|Product Code not found";
   }
}

if (isset($course_name)) {
   //if (!isCourseAdded($course_name, $student_code, $course_inquiry)) {
      $centre_code=$_SESSION["CentreCode"];
      $pic=$_SESSION["UserName"];
      $now=date("Y-m-d H:i:s");
     // $year=date("Y");
      $student_id=getStudentIDByCode($student_code);
      $sql="INSERT into tmp_busket (session_id, centre_code, student_code, product_code, qty, unit_price, collection_type, pic, collection_date_time, `year`,`collection_month`, student_id, allocation_id, collection_pattern, single_year) 
      values ('$session_id', '$centre_code', '$student_code', '$course_name', '$inquiry_qty', '$course_val', '$course_inquiry', '$pic', '$now', '$collection_year', '$collection_month', '$student_id', '$allocation_id', '$collection_pattern', '$single_year')";

      $result=mysqli_query($connection, $sql);
      if ($result) {
         echo "1|Course added";
      } else {
         echo "0|Add failed";
      }
   // } else {
      // increaseQty($course_name, $student_code, $course_inquiry, $inquiry_qty);
      // echo "1|Qty increased";
   // }
}

if (isset($outstanding_name)) {
   //if (!isOutstandingAdded($outstanding_name, $student_code, 'tuition')) {
      $centre_code=$_SESSION["CentreCode"];
      $pic=$_SESSION["UserName"];
      $now=date("Y-m-d H:i:s");
      $year=date("Y");
      $student_id=getStudentIDByCode($student_code);
      
      $str = $the_fee;
      $matches = preg_replace('/[^0-9]/', '', $str);

      if ($matches) {
        $amount = $matches;
      }else{
          $amount = $amount;
      }

      $matches= 0;
      
      $sql="INSERT into tmp_busket (session_id,allocation_id, centre_code, student_code, product_code, qty, unit_price, collection_type, pic, collection_date_time, `year`, collection_month, student_id, collection_pattern) 
      values ('$session_id', '$allocation_id', '$centre_code', '$student_code', '$outstanding_name', '1', '$amount', 'tuition', '$pic', '$now', '$collection_year', '$collection_month', '$student_id', '$collection_pattern')";
      $result=mysqli_query($connection, $sql);
      if ($result) {
         echo "1|Outstanding added";
      } else {
         echo "0|Add failed";
      }
   // } else {
      //increaseQty($outstanding_name, $student_code, 'tuition', '1');
      // echo "1|Outstanding added already";
   // }
}


?>