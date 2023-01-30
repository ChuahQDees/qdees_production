<?php
session_start();
$session_id=session_id();
include_once("../mysql.php");
include_once("functions.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //$product_code, $student_code
}
echo $product_code; 


function isProductCodeExist($product_code) {
   global $connection;
   $centre_code=$_SESSION["CentreCode"];
   $sql="SELECT * from addon_product where product_code='$product_code' and centre_code = '$centre_code'";
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);
   if ($num_row>0) {
      return true;
   } else {
      return false;
   }
}



function isProductAdded($product_code, $student_code, $collection_month) {
   global $connection, $session_id;

   $sql="SELECT * from tmp_busket where session_id='$session_id' and product_code='$product_code' and student_code='$student_code' and collection_month=$collection_month";
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      return true;
   } else {
      return false;
   }
}

function getProductInfo($product_code, &$unit_price, &$qty) {
   global $connection;
   $centre_code=$_SESSION["CentreCode"];
   $sql="SELECT unit_price, uom from addon_product where product_code='$product_code' and centre_code = '$centre_code'";
   
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

  // $unit_price=$row["unit_price"];
   $qty=$row["uom"];
   
}

function increaseQty($product_code, $student_code, $collection_month) {
   global $connection, $session_id;

   $sql="UPDATE tmp_busket set qty=qty+1, amount=unit_price*(qty) where product_code='$product_code' and student_code='$student_code' and session_id='$session_id' and collection_month = $collection_month";
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
      return true;
   }
}

function getStudentIDByCode($sha1_student_code) {
   global $connection;

   $sql="SELECT * from student where sha1(student_code)='$sha1_student_code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return $row["id"];
}

   // var_dump($fees);die;
if (($product_code!="") & ($student_code!="")) {
   getProductInfo($product_code, $unit_price, $qty);
   if (isProductCodeExist($product_code) ) {
      if (!isProductAdded($product_code, $student_code, $collection_month)) {
         if (enoughBal($product_code, $_SESSION["CentreCode"], $student_code)) {
            $centre_code=$_SESSION["CentreCode"];
            $pic=$_SESSION["UserName"];
            $now=date("Y-m-d H:i:s");
           // $year=date("Y");
            $student_id=getStudentIDByCode($student_code);
            //echo $uom; die;
            //$qty = '10';
            $amount = $unit_price * $qty;

            $sql="INSERT into tmp_busket (`session_id`, centre_code, student_code, product_code, qty, unit_price, amount,
            collection_type, pic, collection_date_time, `year`, student_id, collection_month, collection_pattern)  values ('$session_id', '$centre_code', '$student_code', '$product_code', $qty, 
            '$unit_price', '$amount', 'addon-product', '$pic', '$now', '$collection_year', '$student_id', '$collection_month', '$collection_pattern')";
            $result=mysqli_query($connection, $sql) or die(mysqli_error($connection));
            if ($result) {
               echo "1|Product added";
            } else {
               echo "0|Add failed";
            }
         } else {
            echo "0|Insufficient balance";
         }
      } else {
         if (enoughBal($product_code, $_SESSION["CentreCode"], $student_code)) {
            increaseQty($product_code, $student_code, $collection_month);
            echo "1|Qty increased";
         } else {
            echo "0|Insufficient balance";
         }
      }
   } elseif ($product_code == 'Material' || $product_code == 'Mandarin' || $product_code == 'Registration' || $product_code == 'Mobile' ) {
      if (!isProductAdded($product_code, $student_code, $collection_month)) {
         if (enoughBal($product_code, $_SESSION["CentreCode"], $student_code)) {
            $centre_code=$_SESSION["CentreCode"];
            $pic=$_SESSION["UserName"];
            $now=date("Y-m-d H:i:s");
            $year=date("Y");
            $student_id=getStudentIDByCode($student_code);
            $sql="INSERT into tmp_busket (`session_id`, centre_code, student_code, product_code, qty, unit_price, amount,
            collection_type, pic, collection_date_time, `year`, student_id, collection_month, collection_pattern)  values ('$session_id', '$centre_code', '$student_code', '$product_code', 1, 
            '$fees', '$fees', '$product_code', '$pic', '$now', '$year', '$student_id', '$collection_month', '$collection_pattern')";
            $result=mysqli_query($connection, $sql) or die(mysqli_error($connection));
            if ($result) {
               echo "1|Product added";
            } else {
               echo "0|Add failed";
            }
         } else {
            echo "0|Insufficient balance";
         }
      } else {
         if (enoughBal($product_code, $_SESSION["CentreCode"], $student_code)) {
            increaseQty($product_code, $student_code, $collection_month);
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
?>