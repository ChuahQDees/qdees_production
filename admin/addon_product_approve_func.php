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

if ($mode=="EDIT") {
   foreach ($_POST as $key=>$value) {
      $$key=$value;
      $$key=mysqli_real_escape_string($connection, $$key);
   }

//   echo "$product_code|$product_name|$uom|$unit_price|$retail_price|$category|$sub_category";
  
   if (isRecordFound($table, $key_field, $hidden_product_id)) {
      if (($product_code!="") & ($product_name!="") & ($uom!="") & ($unit_price!="")) {
		$tmp_document=$_FILES["file_s"]["tmp_name"];
		$file_extn = explode(".", strtolower($_FILES["file_s"]["name"]))[1];
		if($file_extn !=""){
			//echo "done"; die;
			$files_filename=generateRandomString(8).".$file_extn";		
			copy($tmp_document, 'admin/uploads/'.$files_filename);
		}
		 //print_r($_FILES); die;
         $update_sql="UPDATE `$table` set status='$actionButton', doc_remarks='$files_filename', remarks_master='$remarks_master'
         where id='$hidden_product_id'"; 
         $result=mysqli_query($connection, $update_sql);
		//echo $update_sql;
         $msg="Record updated";
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

 $base_sql = "SELECT f.*, c.company_name from `$table` f INNER JOIN `centre` c on f.`centre_code`=c.`centre_code` ";
//$base_sql="SELECT * from `$table`";
//$centre_code=$_SESSION["CentreCode"];
//$token=ConstructTokenGroup("product_code", "%$s%", "like", "product_name", "%$s%", "like", "or");
$token=ConstructTokenGroup("c.company_name", "%$cm%", "like", "c.centre_code", "%$cm%", "like", "or");
//$token1=ConstructToken("c.centre_code", $cm, "=");
$token2=ConstructToken("f.status", $status, "=");
$token_start_date=ConstructToken("f.submission_date", $start_date, ">=");
$token_end_date=ConstructToken("f.submission_date", $end_date, "<=");
$final_token=ConcatToken($token, $token2, "and");
//$final_token=ConcatToken($final_token, $token2, "and");
$final_token=ConcatToken($final_token, $token_start_date, "and");
$final_token=ConcatToken($final_token, $token_end_date, "and");

$browse_sql=ConcatWhere($base_sql, $final_token);
$browse_sql = $browse_sql . " ORDER BY `submission_date` DESC ";
//echo $browse_sql;
?>
