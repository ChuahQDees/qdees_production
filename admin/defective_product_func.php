<?php
$table="defective";
$key_field="id";
$msg="";

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

   $order_no=generateRandomString1(10);

   if (isRecordFound($table, $key_field, $id)) {
      if (($centre_code!="") & ($product_code!="") & ($qty!="") & ($unit_price!="") & ($total!="")) {
         // $update_sql="UPDATE `$table` set course_name='$course_name', fees='$fees', registration='$registration',
         // deposit='$deposit', placement='$placement' where id='$id'";

         // $result=mysqli_query($connection, $update_sql);
         // $msg="Record updated";
      } else {
         // $msg="All fields are required";
      }
   } else {
      if (($centre_code!="") & ($product_code!="") & ($qty!="") & ($unit_price!="") & ($total!="")) {
         $insert_sql="INSERT into `$table` (order_no, centre_code, product_code, qty, unit_price, total, ordered_by, ordered_on)
         values ('$order_no', '$centre_code', '$product_code', '$qty', '$unit_price',
         '$total', '".$_SESSION["UserName"]."', '".date("Y-m-d H:i:s")."')";

         $result=mysqli_query($connection, $insert_sql);
         $msg="Record inserted";
      } else {
         $msg="All fields are required";
      }
   }
}

$str=$_GET["s"];
$year=$_SESSION['Year'];
if ($_GET["s"]!="") {
   $browse_sql="SELECT d.*, c.kindergarten_name, p.product_name from `$table` d, centre c, product p
   where (c.kindergarten_name like '%$str%' or c.centre_code like '%$str%'
   or p.product_name like '%$str%' or p.product_code like '%$str%')
   and d.centre_code=c.centre_code and d.product_code=p.product_code order by d.ordered_on desc";
} else {
   $browse_sql="SELECT d.*, c.kindergarten_name, p.product_name from `$table` d, centre c, product p
   where d.centre_code=c.centre_code and d.product_code=p.product_code order by d.ordered_on desc";
}
?>