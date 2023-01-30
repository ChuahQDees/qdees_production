<?php
$table="vendor";
$key_field="id";
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

if ($mode=="SAVE") {
   foreach ($_POST as $key=>$value) {
      $$key=$value;
   }

   if (isRecordFound($table, $key_field, $hidden_id)) {
      if (($vendor_name!="") & ($address1!="") & ($address2!="") & ($address3!="") & ($tel1!="") & ($pic!="")) {
         $update_sql="UPDATE `$table` set vendor_name='$vendor_name', address1='$address1', address2='$address2', address3='$address3', 
         address4='$address4', address5='$address5', tel1='$tel1', tel2='$tel2', fax='$fax', email='$email', pic='$pic' 
         where id='$hidden_id'";

         $result=mysqli_query($connection, $update_sql);
         $msg="Record updated";
      } else {
         $msg="All fields are required";
      }
   } else {
      if (($vendor_name!="") & ($address1!="") & ($address2!="") & ($address3!="") & ($tel1!="") & ($pic!="")) {
         $insert_sql="INSERT into `$table` (vendor_name, address1, address2, address3, address4, address5, tel1, tel2, fax, email, pic) 
         values ('$vendor_name', '$address1', '$address2', '$address3', '$address4', '$address5', '$tel1', '$tel2', '$fax', '$email', '$pic')";

         $result=mysqli_query($connection, $insert_sql);
         $msg="Record inserted";
      } else {
         $msg="All fields are required";
      }
   }
}

$str=$_GET["s"];
if ($_GET["s"]!="") {
   $browse_sql="SELECT * from `$table` where vendor_name like '%$str%' or tel1 like '%$str%' or tel2 like '%$str%' 
   or fax like '%$str%' order by vendor_name";
} else {
   $browse_sql="SELECT * from `$table` order by vendor_name";
}
?>