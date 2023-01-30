<?php
session_start();
$table="buffer_stock";
$key_field="id";
$msg="";
$mode=$_GET["mode"];
$get_sha1_id =$_GET["id"];

function isRecordFound($table, $key_field, $key_value) {
   global $connection;

   $sql="SELECT $key_field from `$table` where sha1($key_field)='$key_value'";
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
      $del_sql="UPDATE `$table` set deleted=1 where sha1(id)='$get_sha1_id'";
      $result=mysqli_query($connection, $del_sql);
      $msg="Record deleted";
   }
}

if ($mode=="SAVE") {
   foreach ($_POST as $key=>$value) {
      $$key=$value;
   }
   $create_date = date("Y/m/d");
   $year=$_SESSION['Year'];
   $created_by=$_SESSION["UserName"];

   if (isRecordFound($table, $key_field, $hidden_id)) {
      if ($country!="") {
         $update_sql="UPDATE `$table` set `country`='$country',`state`='$state',`buffer_amount`='$buffer_amount',`updated_by`='$created_by',`update_date`='$create_date' where sha1(id)='$hidden_id'";

         $result=mysqli_query($connection, $update_sql);
         $msg="Record updated";
      } else {
         $msg="All fields are required";
      }
   } else {
      if ($country!="") {
         $insert_sql="INSERT into `$table` ( `country`, `state`, `buffer_amount`, `created_by`, `create_date`, `year`) values ('$country', '$state', '$buffer_amount', '$created_by', '$create_date', '$year')";
     
         $result=mysqli_query($connection, $insert_sql);
         if (mysqli_affected_rows($connection)>0) {
            $msg="Record inserted";
         } else {
            $msg="Record insert failed";
         }

      } else {
         $msg="All fields are required";
      }
   }
}

$country=$_GET["country"];
$state=$_GET["state"];
$buffer_amount=$_GET["buffer_amount"];
$year=$_SESSION['Year'];

   //$browse_sql="SELECT * from `$table` where deleted=0 and year='$year' and centre_code='".$_SESSION["CentreCode"]."' order by class";
   $browse_sql="SELECT * from `$table` where deleted=0 and year='$year' ";
   if($country!=""){
      $browse_sql.=" and country='$country'";
   }
   if($state!=""){
      $browse_sql.=" and state='$state'";
   }
   if($buffer_amount!=""){
      $browse_sql.=" and buffer_amount = '$buffer_amount'";
   }
   
 $browse_sql.="order by id desc";
//echo $browse_sql;
?>