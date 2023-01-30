<?php
$table="addon_product";
$key_field="product_code";
$msg="";

function isRecordFound($table, $key_field, $key_value) {
   global $connection;

   $sql="SELECT $key_field from `$table` where id='$key_value'";
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      return true;
   } else {
      return false;
   }
}
function isCodeExists($table, $product_code, $centre_code) {
   global $connection;

   $sql="SELECT * from `$table` where product_code='$product_code' and centre_code = '$centre_code'";
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      return true;
   } else {
      return false;
   }
}

if ($mode=="EDIT") {
   if ($get_sha1_id!="") {
      $edit_sql="SELECT * from `$table` where sha1(id)='$get_sha1_id'";
      $result=mysqli_query($connection, $edit_sql);
      $edit_row=mysqli_fetch_assoc($result);
   }
}

if ($mode=="DEL") {
   if ($get_sha1_id!="") {
      $del_sql="DELETE from `$table` where sha1(id)='$get_sha1_id'";
      $result=mysqli_query($connection, $del_sql);
      $msg="Record deleted";
   }
}

if ($mode=="SAVE") {
   foreach ($_POST as $key=>$value) {
      $$key=$value;
      $$key=mysqli_real_escape_string($connection, $$key);
   }

//   echo "$product_code|$product_name|$uom|$unit_price|$retail_price|$category|$sub_category";
  
   if (isRecordFound($table, $key_field, $hidden_product_id)) {
      if (($product_code!="") & ($product_name!="") & ($uom!="") & ($unit_price!="")) {
		$time_date= date("Y-m-d H:i:s");
         $update_sql="UPDATE `$table` set product_code='$product_code', product_name='$product_name', uom='$uom', unit_price='$unit_price', monthly='$monthly', submission_date='$time_date', status='Pending',remarks='$remarks' where id='$hidden_product_id'";
         //echo $update_sql; die;
         $result=mysqli_query($connection, $update_sql);

         $msg="Record updated";
      } else {
         $msg="All fields are required";
      }
   } else {
      if (($product_code!="") & ($product_name!="") & ($uom!="") & ($unit_price!="")) {

         $centre_code=$_SESSION["CentreCode"];
         if(isCodeExists($table, $product_code, $centre_code)){
            $msg="Product code already exists!";
         }else{
			$time_date= date("Y-m-d H:i:s");
            $insert_sql="INSERT into `$table` (product_code, product_name, uom, unit_price, centre_code, monthly, submission_date, status,remarks,doc_remarks,remarks_master) 
            values ('$product_code', '$product_name', '$uom', '$unit_price', '$centre_code', '$monthly', '$time_date', 'Pending','$remarks','','')";
            // echo $insert_sql; die;
            $result=mysqli_query($connection, $insert_sql) or die(mysqli_error($connection));

            $msg="Record inserted";
         }
         
      } else {
         $msg="All fields are required";
      }
   }
}

include_once("search_new.php");

foreach ($_GET as $key=>$value) {
   $$key=$value;
//   echo $key."=".$value."<br>";
}

$final_token="";
$base_sql="SELECT * from `$table`" ;
$centre_code=$_SESSION["CentreCode"];
$token=ConstructTokenGroup("product_code", "%$product_name_code%", "like", "product_name", "%$product_name_code%", "like", "or");
$token1=ConstructToken("centre_code", $centre_code, "=");
$token2=ConstructToken("status", $status, "=");
$token_start_date=ConstructToken("submission_date", $start_date, ">=");
$token_end_date=ConstructToken("submission_date", $end_date, "<=");
$final_token=ConcatToken($token, $token1, "and");
$final_token=ConcatToken($final_token, $token2, "and");
$final_token=ConcatToken($final_token, $token_start_date, "and");
$final_token=ConcatToken($final_token, $token_end_date, "and");

$browse_sql=ConcatWhere($base_sql, $final_token);
//echo $browse_sql;
?>