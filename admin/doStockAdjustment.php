<?php
session_start();
include_once("../mysql.php");
date_default_timezone_set("Asia/Kuala_Lumpur");

function isDuplicateRecord($product_code, $effective_date, $adjust_qty) {
   global $connection;

   $sql="SELECT * from stock_adjustment where product_code='$product_code' and effective_date='$effective_date' and adjust_qty='$adjust_qty'";
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      return true;
   } else {
      return false;
   }
}

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);//product_code, effective_date, adjust_qty, centre_code
}

list($day, $month, $year)=explode("/", $effective_date);
// $effective_date="$year-$month-$day";

//$centre_code=$_SESSION["CentreCode"];

// var_dump($effective_date);die;

if (($product_code!="") & ($effective_date!="") & ($adjust_qty!="")) {
   if (!isDuplicateRecord($product_code, $effective_date, $adjust_qty)) {
      $user_name=$_SESSION["UserName"];
      $adjusted_at=date("Y-m-d H:i:s");
    //print_r($_FILES['file']); die;
    $file = $_FILES['file']['tmp_name'];
	//echo $file; die;
    $filename = $_FILES['file']['name'];
	
    $folder="/uploads";
    move_uploaded_file($file,$folder.$filename);
    $handle = $folder.$filename;
      $sql="INSERT into stock_adjustment (centre_code, product_code, effective_date, adjust_qty, adjusted_by, adjusted_at,file)
      values ('$centre_code', '$product_code', '$effective_date', '$adjust_qty', '$user_name', '$adjusted_at','$handle')";

      $result=mysqli_query($connection, $sql);

      if ($result) {
         echo "1|Adjusted successfully";
      } else {
         echo "0|Something is wrong";
      }
   } else {
      echo "0|Record already inserted";
   }
} else {
   echo "0|Please fill in all fields";
}
?>
