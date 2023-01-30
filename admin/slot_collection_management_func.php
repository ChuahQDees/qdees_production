<?php
session_start();
$table="slot_collection";
$key_field="id";
$msg="";
$mode=$_GET["mode"];
$get_sha1_id =$_GET["id"];
$p =$_GET["p"];
$pg =$_GET["pg"];

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

function getSlotId($sha1_id) {
   global $connection;

   $sql="SELECT id from `slot_collection` where sha1(id)='$sha1_id'";
   $result=mysqli_query($connection, $sql);
   //print_r($sql); die;
   if ($result) {
      $row=mysqli_fetch_assoc($result);
      $id = $row['id'];
      
   }else{
      $id = "";
   }
   return $id;
}

if ($mode=="EDIT") {
   if ($get_sha1_id!="") {
      $edit_sql="SELECT * from `$table` where sha1(id)='$get_sha1_id'";
      //echo $edit_sql;
      $result=mysqli_query($connection, $edit_sql);
      $edit_row=mysqli_fetch_assoc($result);
   }
}

if ($mode=="DEL") {
   if ($get_sha1_id!="") {
      $del_sql="UPDATE `$table` set deleted=1 where sha1(id)='$get_sha1_id'";
      $result=mysqli_query($connection, $del_sql);

      $del_sql="DELETE FROM `slot_collection_child` where sha1(slot_master_id)='$get_sha1_id'";
      $result=mysqli_query($connection, $del_sql);

      $msg="Record deleted";
   }
}
//echo $mode; die;
if ($mode=="SAVE") {
   foreach ($_POST as $key=>$value) {
      $$key=$value;
   }
   $create_date = date("Y/m/d");
   $year=$_SESSION['Year'];
   $created_by=$_SESSION["UserName"];
 
   if (isRecordFound($table, $key_field, $hidden_id)) {
      if ($slot!="" & $select_time!="" & $select_date!="") {
         $update_sql="UPDATE `$table` set `slot`='$slot',`select_time`='$select_time',`select_date`='$select_date',`remarks`='$remarks',`updated_by`='$created_by',`update_date`='$create_date' where sha1(id)='$hidden_id'";
       
         $result=mysqli_query($connection, $update_sql);

         $del_sql="DELETE FROM `slot_collection_child` where sha1(slot_master_id)='$hidden_id'";
         $result=mysqli_query($connection, $del_sql);
         for ($s = 1; $s <= $slot; $s++) {
           $slot_master_id = getSlotId($hidden_id);
     
           $insert_sql="INSERT into `slot_collection_child` ( `slot_master_id`, `slot_child`, `is_booked`) values ('$slot_master_id', '$s','0')";
       
            $result=mysqli_query($connection, $insert_sql);
         
          }


         $msg="Record updated";
      } else {
         $msg="All fields are required";
      }
   } else {
      //if ($centre_code!="") {
         if ($slot!="" & $select_time!="" & $select_date!="") {
         $insert_sql="INSERT into `$table` ( `slot`, `select_time`, `select_date`,`remarks`, `created_by`, `create_date`, `year`) values ('$slot', '$select_time','$select_date', '$remarks', '$created_by', '$create_date', '$year')";
      
         $result=mysqli_query($connection, $insert_sql);
         if (mysqli_affected_rows($connection)>0) {
            $slot_master_id =  mysqli_insert_id($connection);
            for ($s = 1; $s <= $slot; $s++) {
        
               $insert_sql="INSERT into `slot_collection_child` ( `slot_master_id`, `slot_child`, `is_booked`) values ('$slot_master_id', '$s','0')";
               $result=mysqli_query($connection, $insert_sql);
            }
            $msg="Record inserted";
         } else {
            $msg="Record insert failed";
         }

      } else {
         $msg="All fields are required";
      }
   }
}

$company_name=$_GET["company_name"];
$slot=$_GET["slot"];
$date_from=$_GET["date_from"];
$date_to=$_GET["date_to"];
$year=$_SESSION['Year'];

   //$browse_sql="SELECT * from `$table` where deleted=0 and year='$year' and centre_code='".$_SESSION["CentreCode"]."' order by class";
   $browse_sql="SELECT s.*, c.company_name as centre_name from `$table` s left join centre c on c.centre_code=s.centre_code where s.deleted=0 and s.year='$year'";
   //$browse_sql="SELECT s.* from `$table` s where s.deleted=0 and s.year='$year'";
   // if($company_name!=""){
   //    $browse_sql.=" and c.company_name='$company_name'";
   // }
   if($slot!=""){
      $browse_sql.=" and s.slot='$slot'";
   }
   if($date_from!="" && $date_to!=""){
      $browse_sql.=" and s.select_date between '$date_from' and '$date_to' ";
   }
   
 $browse_sql.=" order by s.id desc";
   //echo $browse_sql;
?>