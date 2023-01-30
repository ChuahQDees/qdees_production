<?php
session_start();
$session_id=session_id();
include_once("../mysql.php");
include_once("functions.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //$student_code
}

function isProductFound($product_code, $student_code) {
   global $session_id, $connection;
   //echo $product_code; die;
   $sql="SELECT * from tmp_busket where session_id='$session_id' and product_code='$product_code' and student_code='$student_code'";
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      return true;
   } else {
      return false;
   }
}

function getStudentIDByCode($student_code) {
   global $connection;

   $sql="SELECT id from student where student_code='$student_code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return $row["id"];
}

function getProductInfo($product_code, &$unit_price) {
   global $connection;

   $sql="SELECT retail_price from product where product_code='$product_code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   $unit_price=$row["retail_price"];
}

if ($student_code!="") {
	$student_id=getStudentIDByCode($student_code);
   $msg="";
   //$sql="SELECT distinct c.id AS course_id, c.course_name, p.product_code, p.product_name, p.retail_price
  // FROM course c, student s, allocation a, product p, product_course pc WHERE s.id=a.student_id AND a.course_id=c.id
  // AND s.student_code='$student_code' AND p.product_code=pc.product_code AND c.id=pc.course_id";
$sql="SELECT distinct p.product_code, p.product_name, p.retail_price FROM student s, programme_selection ps, product p, product_course pc WHERE s.id=ps.student_id AND s.student_code='$student_code'";
  //echo $sql; die; 
   $result=mysqli_query($connection, $sql);
   while ($row=mysqli_fetch_assoc($result)) {
      $bal=calcBal($_SESSION["CentreCode"], $row["product_code"], date("Y-m-d"));
      $qty_in_basket=getQtyInBasket($session_id, $student_code, $row["product_code"]);
      if ($bal>=$qty_in_basket+1) {
         if (!isProductFound($row["product_code"], $student_code)) {
            $centre_code=$_SESSION["CentreCode"];
            $pic=$_SESSION["UserName"];
            $now=date("Y-m-d H:i:s");
            $year=date("Y");
            getProductInfo($row["product_code"], $unit_price);

            $isql="INSERT into tmp_busket (session_id, centre_code, student_code, product_code, qty, unit_price, collection_type, pic, collection_date_time, `year`, student_id) 
            values ('$session_id', '$centre_code', sha1('$student_code'), '".$row["product_code"]."', '1', '".$row["retail_price"]."', 
            'product', '".$_SESSION["UserName"]."', '$now', '$year', '$student_id')";
//            $isql="INSERT into tmp_busket (session_id, student_code, product_code, qty, unit_price)
//            values ('$session_id', '$student_code', '".$row["product_code"]."', '1', '".$row["unit_price"]."')";
            mysqli_query($connection, $isql);
         } else {
            $usql="UPDATE tmp_busket set qty=qty+1 where product_code='$product_code' and student_code='$student_code' and session_id='$session_id'";
            mysqli_query($connection, $sql);
         }
      } else {
         $msg.="<li>Insufficient stock - ".$row["product_code"]."</li>";
      }
   }

   if ($msg!="") {
      echo "<ul>".$msg."</ul>";
   }
}
?>