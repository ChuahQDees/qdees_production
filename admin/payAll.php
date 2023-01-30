<?php
session_start();
$session_id=session_id();

function generateRandomString($length) {
   $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
   $charactersLength = strlen($characters);
   $randomString = '';
   for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
   }
   return $randomString;
}

function getReceiptNumber($centre_code) {
   global $connection;

   $sql="SELECT max(batch_no) as batch_no from collection where batch_no like '$centre_code-%'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   if ($row["batch_no"]!="") {
      $serial=substr($row["batch_no"], -6)+1;

      return $centre_code."-".str_pad($serial, 6, '0', STR_PAD_LEFT);;
   } else {
      return $centre_code."-000001";
   }
}
function getCourseNameByAllocationID($allocation_id) {
   global $connection;

   $sql="SELECT * from allocation where id='$allocation_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   $course_id=$row["course_id"];

   $sql="SELECT course_name from course where id='$course_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return $row["course_name"];
}
function getProductNameByProductCode($product_code) {
   global $connection;

   $sql="SELECT product_name from product where product_code='$product_code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return $row["product_name"];
}

function getAOProductNameByProductCode($product_code) {
   global $connection;

   $sql="SELECT product_name from addon_product where product_code='$product_code' and centre_code='".$_SESSION["CentreCode"]."'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return $row["product_name"];
}
include_once("../mysql.php");

foreach ($_POST as $key=>$value) {
	$$key=mysqli_real_escape_string($connection, $value);
}
$student_id= $_POST['studentID'];
//echo $student_id;
$sql="SELECT * from busket where session_id='$session_id' and student_id='$student_id' order by id";
//echo $sql; die;
$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);

if ($result) {
   if ($num_row>0) {

		$now=date("Y-m-d H:i:s");
		$sql="INSERT into payment_detail (remark, payment_method, cheque_no, bank, ref_no, done_by, transaction_date, print_option, title_description) values ('$remarks', '$payment_method', 
		'$cheque_no', '$bank', '$ref_no', '".$_SESSION["UserName"]."', '$now', '$print_option', '$title_description')";

		mysqli_query($connection, $sql);
		$payment_detail_id = mysqli_insert_id($connection);	
		$receipt_number=getReceiptNumber($_SESSION["CentreCode"]);

      while ($row=mysqli_fetch_assoc($result)) {
      	foreach ($row as $key=>$value) {
      		$$key=$value;
      	}
		switch ($collection_type) {
            case "tuition" : $product_name = getCourseNameByAllocationID($row["allocation_id"]); break;
            case "registration" : $product_name = $row["subject"] ? "Registration - ".$row["subject"] : "Registration"; break;
            case "Deposit" : $product_name = $row["subject"] ? "Deposit - ".$row["subject"] : "Deposit"; break;
            case "placement" : $product_name = $row["subject"] ? "Placement - ".$row["subject"] : "Placement"; break;
            case "product" : $product_name = getProductNameByProductCode($row["product_code"]); break;
            case "addon-product" : $product_name = getAOProductNameByProductCode($row["product_code"]); break;
            case "mobile" : $product_name = "Mobile App Fee"; break;
          case "outstanding" : $product_name = $row["product_code"]; break;
         }
		 
		$course_id = $course_id ? "'$course_id'" : 'null';
         //$sql="INSERT into collection (allocation_id, centre_code, batch_no, collection_date_time, product_code, qty, unit_price, amount, collection_type, `year`, collection_month, pic, void, cn_no, void_reason, student_id, student_code, payment_detail_id, course_id, subject, product_name) values ('$allocation_id', '$centre_code', '$receipt_number', '$now', '$product_code', '$qty', '$unit_price', '$amount', '$collection_type', '$year', '$collection_month', '$pic', '0', '', '', '$student_id', '$student_code', '$payment_detail_id', $course_id, '$subject', '$product_name')";
         $sql="INSERT into collection (allocation_id, centre_code, batch_no, collection_date_time, product_code, qty, unit_price, amount, discount, collection_type, `year`, collection_month, pic, void, cn_no, void_reason, student_id, student_code, payment_detail_id, course_id, subject, product_name, collection_pattern, single_year) values ('$allocation_id', '$centre_code', '$receipt_number', '$now', '$product_code', '$qty', '$collection', '$collection', '$discount', '$collection_type', '$year', '$collection_month', '$pic', '0', '', '', '$student_id', '$student_code', '$payment_detail_id', $course_id, '$subject', '$product_name', '$collection_pattern','$single_year')";
//echo $sql; die;
      	$iresult=mysqli_query($connection, $sql);
      }

      $sql="DELETE from busket where session_id='$session_id' and student_id='$student_id'";
      $dresult=mysqli_query($connection, $sql);
      if($print_option=="Itemized"){
         $s="";
         $s.="<form name='frmPrintReceipt' id='frmPrintReceipt' method='get' action='print_receipt.php'>";
         $s.="<input type='hidden' name='batch_no' value='".sha1($receipt_number)."'>";
         $s.="</form>";
         $s.="<script>";
         $s.="document.getElementById('frmPrintReceipt').submit();";
         $s.="</script>";
      }else{
         $s="";
         $s.="<form name='frmPrintReceipt' id='frmPrintReceipt' method='get' action='print_receipt_total.php'>";
         $s.="<input type='hidden' name='batch_no' value='".sha1($receipt_number)."'>";
         $s.="</form>";
         $s.="<script>";
         $s.="document.getElementById('frmPrintReceipt').submit();";
         $s.="</script>";
      }

      echo $s;
   } else {
      $s="";
      $s.="<script>";
      $s.="window.close();";
      $s.="</script>";

      echo $s;
   }
}
?> 