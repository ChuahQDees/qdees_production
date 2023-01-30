<?php
session_start();

include_once("../mysql.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);//$id(sha1), $qty, $defective_reason, $doc, $sales_order_no, $remarks
}

function generateRandomString($length) {
   $characters='0123456789';
   $charactersLength=strlen($characters);
   $randomString='';
   for ($i=0; $i<$length; $i++) {
      $randomString.=$characters[rand(0, $charactersLength-1)];
   }
   return $randomString;
}

if(($_FILES['doc']['size'] >= 2097152) || ($_FILES["doc"]["size"] == 0)) {
   echo '0|File too large. File must be less than 2 MB.';
} else {

   $tmp_document=$_FILES["doc"]["tmp_name"];
   $document=$sales_order_no."_".time().".jpg";
   if (is_uploaded_file($tmp_document)) {
      copy($tmp_document, 'uploads/'.$document);
   } else {
      $document="";
   }

   if (($id!="") & ($qty!="") & ($defective_reason!="") & ($document!="")) {
      $sql="SELECT * from `order` where sha1(id)='$id'";
      $result=mysqli_query($connection, $sql);
      $row=mysqli_fetch_assoc($result);

      $order_no=generateRandomString(10);
      $centre_code=$_SESSION["CentreCode"];
      $product_code=$row["product_code"];
      $unit_price=$row["unit_price"];
      $total=$qty*$unit_price;
      $ordered_by=$_SESSION["UserName"];
      $ordered_on=date("Y-m-d H:i:s");
      $user_name=$_SESSION["UserName"];

      $sql="INSERT into defective (order_no, sales_order_no, centre_code, user_name, product_code, qty, unit_price, total, ordered_by,
      ordered_on, defective_reason, doc, remarks) values ('$order_no', '$sales_order_no', '$centre_code', '$user_name', '$product_code',
      '$qty', '$unit_price', '$total', '$ordered_by', '$ordered_on', '$defective_reason', '$document', '$remarks')";

      $result=mysqli_query($connection, $sql);

      if ($result) {
         echo "1|Defective reported";
      } else {
         echo "0|Defective report failed";
      }
   } else {
   //   echo "0|$id,$qty,$defective_reason,$document";
      echo "0|Incomplete data";
   }
}


?>