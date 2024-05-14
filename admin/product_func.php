<?php
$table="product";
$key_field="product_code";
$msg="";

function isRecordFound($table, $key_field, $key_value) {
   global $connection;

   $sql="SELECT $key_field from `$table` where $key_field='$key_value'";
   
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
 $category_id= $_POST['category_id'];
	$base_sql1="SELECT * from product p inner join product_category pc where pc.id='$category_id' order by category_id";
    $browse_result1 = mysqli_query($connection, $base_sql1);
	 $browse_num_row1 = mysqli_num_rows($browse_result1);
	 if ($browse_num_row1 > 0) {
	 while ($browse_row1 = mysqli_fetch_assoc($browse_result1)) {
		$category =$browse_row1["company_name"];
		$category_name =$browse_row1["category_name"];
	 }
	 }
if ($mode=="SAVE") {
   foreach ($_POST as $key=>$value) {
      $$key=$value;
      $$key=mysqli_real_escape_string($connection, $$key);
   }
  
//echo $_POST['product_photo'];
   //echo isRecordFound($table, $key_field, $product_code); die;
   
	//echo $product_code; die;
   
   //if (isRecordFound($table, $key_field, $product_code) == true) {
	   if($hidden_id!=''){
	   
	   $state= $_POST['state'];
		$state = implode (", ", $state);
		$product_code = $_POST['product_code'].'((--'.$_POST['company_code'].'_'.$_POST['category_id'];

      if (($product_code!="") & ($product_name!="") & ($unit_price!="") & ($country!="") & ($state!="")) {
         $term1 = !empty($term) ? "$term" : "0";
         $update_sql="UPDATE `$table` set product_code='$product_code', product_name='$product_name', category='$category', category_name='$category_name', country='$country', `state`='$state', unit_price='$unit_price', sub_sub_category='$sub_sub_category', term='$term1', category_id='$category_id', retail_price='$retail_price', foundation='$foundation', remarks = '$remarks', remarks_invoice = '$remarks_invoice'
         where id='$hidden_id'";
         $result=mysqli_query($connection, $update_sql);

         $tmp_document=$_FILES["product_photo"]["tmp_name"];
         $product_photo_filename=generateRandomString(8).".jpg";
         if (is_uploaded_file($tmp_document)) {
            copy($tmp_document, 'admin/uploads/'.$product_photo_filename);
            $update_sql="UPDATE `$table` set product_photo='$product_photo_filename' where product_code='$hidden_product_code'";
            $result=mysqli_query($connection, $update_sql);
         }

         $msg="Record updated";
      } else {
         //$msg="All fields are required";
      }
   } else {
     $state= $_POST['state'];
	$state = implode (", ", $state);
	//$product_code= $_POST['company_code'].'-'.$_POST['product_code'];
	$product_code = $_POST['product_code'].'((--'.$_POST['company_code'].'_'.$_POST['category_id'];
	//echo $product_code; die;
      if (($product_code!="") & ($product_name!="") & ($unit_price!="") & ($country!="") & ($state!="")) {
			$tmp_document=$_FILES["product_photo"]["tmp_name"];
         $product_photo_filename=generateRandomString(8).".jpg";
		 copy($tmp_document, 'admin/uploads/'.$product_photo_filename);
		 $term1 = !empty($term) ? "$term" : "0";
         $insert_sql="INSERT into `$table` (product_code, product_name, category, unit_price, retail_price, sub_sub_category, `country`, `state`, `product_photo`, category_id, term, category_name, foundation, remarks, remarks_invoice) values ('$product_code', '$product_name', '$category', '$unit_price', '$retail_price', '$sub_sub_category', '$country', '$state', '$product_photo_filename', '$category_id', '$term1', '$category_name', '$foundation', '$remarks', '$remarks_invoice')";
         //echo $insert_sql; die;
         $result=mysqli_query($connection, $insert_sql);
		 
         $msg="Record inserted";
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
$base_sql="SELECT * from product";

$c_token=ConstructToken("category_name", "%$s%", "like");
$sc_token=ConstructToken("product_name", "%$pn%", "like");
$ssc_token=ConstructToken("product_code", "%$pc%", "like");
// echo "c_token=$c_token<br>";
// echo "sc_token=$sc_token<br>";
// echo "ssc_token=$ssc_token<br>";
$final_token=ConcatToken($c_token, $sc_token, "and");
$final_token=ConcatToken($final_token, $ssc_token, "and");
$browse_sql=ConcatWhere($base_sql, $final_token);
?>