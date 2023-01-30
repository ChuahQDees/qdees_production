<?php
$table="kiv";
$key_field="id";
$msg="";
$year = $_SESSION['Year'];
function generateRandomString1($length) {
   $characters='0123456789';
   $charactersLength=strlen($characters);
   $randomString='';
   for ($i=0; $i<$length; $i++) {
      $randomString.=$characters[rand(0, $charactersLength-1)];
   }
   return $randomString;
}

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
   $sha_id=$_GET["sha_id"];
   if ($sha_id!="") {
      $del_sql="DELETE from $table where sha1(id)='$sha_id'";
      $result=mysqli_query($connection, $del_sql);
      $msg="Record deleted";
   }
}

if ($mode=="SAVE") {
   foreach ($_POST as $key=>$value) {
      $$key=$value;
   }

   $delivery_date=convertDate2ISO($delivery_date);
   
   if (isRecordFound($table, $key_field, $id)) {
      //if (($product_code!="") & ($centre_code!="") & ($delivery_date!="") & ($qty!="") & ($unit_price!="") & ($total!="") & ($remarks!="") & ($status!="")) {
      if (($product_code!="") & ($centre_code!="") & ($qty!="") & ($unit_price!="") & ($total!="") & ($status!="")) {
         $update_sql="UPDATE `$table` set product_code='$product_code', qty='$qty', unit_price='$unit_price',
         total='$total', `status`='$status', remarks='$remarks', centre_code='$centre_code', delivery_date='$delivery_date'
         where id='$id'";
         //echo $update_sql; die;
         $result=mysqli_query($connection, $update_sql);
         $msg="Record updated";
      } else {
         $msg="All fields are required";
      }
   } else {
      if ($status=="") {
         $status="Pending";
      }

      //if (($product_code!="") & ($centre_code!="") & ($delivery_date!="") & ($qty!="") & ($unit_price!="") & ($total!="") & ($remarks!="") & ($status!="")) {
      if (($product_code!="") & ($centre_code!="") & ($qty!="") & ($unit_price!="") & ($total!="") & ($status!="")) {
         $insert_sql="INSERT into `$table` (product_code, qty, unit_price, total, `status`, remarks, centre_code, delivery_date)
         values ('$product_code', '$qty', '$unit_price', '$total', '$status', '$remarks', '$centre_code', '$delivery_date')";

         $result=mysqli_query($connection, $insert_sql);
         //echo $insert_sql; die;
         $msg="Record inserted";
      } else {
         $msg="All fields are required";
      }
   }
}

$str=$_GET["s"];
// $centre_name=$_GET["centre_name"];
// $status=$_GET["status"];
// $remarks=$_GET["remarks"];
if ($_GET["s"]!="") {
   //$browse_sql="SELECT k.* from kiv k, product p, centre c where k.product_code=p.product_code and k.centre_code=c.centre_code and year(delivery_date) = '$year' and p.product_name like '%$str%' and c.company_name like '%$str%' and k.status like '%$str%' and k.remarks like '%$str%' order by date_created desc";
   //$browse_sql="SELECT k.* from kiv k, product p, centre c where k.product_code=p.product_code and k.centre_code=c.centre_code and year(delivery_date) = '$year' and (p.product_name like '%$str%' or c.company_name like '%$str%' or k.status like '%$str%' or k.remarks like '%$str%') order by date_created desc";
   $browse_sql="SELECT k.* from kiv k, product p, centre c where k.product_code=p.product_code and k.centre_code=c.centre_code and (p.product_name like '%$str%' or c.company_name like '%$str%' or k.status like '%$str%' or k.remarks like '%$str%') order by date_created desc";
   //echo $browse_sql;
}else {
   //$browse_sql="SELECT k.* from kiv k, product p where k.product_code=p.product_code and year(delivery_date) = '$year' order by date_created desc";
   $browse_sql="SELECT k.* from kiv k, product p where k.product_code=p.product_code order by date_created desc";
}
//echo $browse_sql;
?>