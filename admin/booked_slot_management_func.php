<?php
session_start();
$table="slot_collection";
$key_field="id";
$msg="";
$mode=$_GET["mode"];
$get_sha1_id =$_GET["id"];
$p =$_GET["p"];
$pg =$_GET["pg"];

function isRecordFound($select_date, $select_time, $slot_child, $year) {
   global $connection;

   //$sql="SELECT $key_field from `$table` where sha1($key_field)='$key_value'";
   $sql="SELECT s.select_date, s.select_time, sc.* from `slot_collection` s inner join slot_collection_child sc on s.id=sc.slot_master_id  where s.select_date='$select_date' and  s.select_time='$select_time' and  sc.slot_child='$slot_child' and sc.`is_booked`='0' and s.deleted=0 and s.year='$year'";

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
      //$edit_sql="SELECT * from `$table` where sha1(id)='$get_sha1_id'";
      $edit_sql="SELECT s.select_date, s.select_time, c.company_name, sc.*, o.remarks from `$table` s inner join slot_collection_child sc on s.id=sc.slot_master_id left join centre c on c.centre_code=sc.centre_code inner join `order` o on o.order_no = sc.order_no  where sha1(sc.id)='$get_sha1_id'";
      
      $result=mysqli_query($connection, $edit_sql);
      $edit_row=mysqli_fetch_assoc($result);
   }
}

if ($mode=="DEL") {
   if ($get_sha1_id!="") {
      $del_sql="UPDATE `slot_collection_child` set `is_booked`='0', `booked_by`='', `centre_code`='', `order_no`='', `remarks_master`='' where sha1(id)='$get_sha1_id'";
      //$del_sql="UPDATE `$table` set deleted=1 where sha1(id)='$get_sha1_id'";
      $result=mysqli_query($connection, $del_sql);

      // $del_sql="DELETE FROM `slot_collection_child` where sha1(slot_master_id)='$get_sha1_id'";
      // $result=mysqli_query($connection, $del_sql);

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
   //echo $company_name; die;
   $update_sql="UPDATE `slot_collection_child` set `is_booked`='0', `booked_by`='', `centre_code`='', `order_no`='', `remarks_master`='' where sha1(id)='$hidden_id'";
   $result=mysqli_query($connection, $update_sql);

   if (isRecordFound($select_date, $select_time, $slot_child, $year)) {
     // if ($slot!="" & $select_time!="" & $select_date!="") {
         $update_sql="UPDATE slot_collection m, slot_collection_child c SET c.is_booked=1, c.booked_by='$booked_by', c.centre_code = '$centre_code', c.order_no='$order_no', remarks_master='$remarks_master' WHERE m.id = c.slot_master_id and m.select_date='$select_date' and m.select_time='$select_time' and c.slot_child='$slot_child' and m.year=$year and c.is_booked=0";
       
         $result=mysqli_query($connection, $update_sql);

         // $del_sql="DELETE FROM `slot_collection_child` where sha1(slot_master_id)='$hidden_id'";
         // $result=mysqli_query($connection, $del_sql);
         // for ($s = 1; $s <= $slot; $s++) {
         //   $slot_master_id = getSlotId($hidden_id);
         //   //echo $slot_master_id; die;
         //   $insert_sql="INSERT into `slot_collection_child` ( `slot_master_id`, `slot_child`, `is_booked`) values ('$slot_master_id', '$s','0')";
         //    //echo $insert_sql; die;
         //    $result=mysqli_query($connection, $insert_sql);
         //    //echo "The number is: $s <br>";
         //  }

         $msg="Record updated";
      // } else {
      //   // $msg="All fields are required";
      // }
    } else {
       
      //if ($centre_code!="") {
         if ($slot_child!="" & $select_time!="" & $select_date!="") {
         $insert_sql="INSERT into `$table` ( `slot`, `select_time`, `select_date`, `created_by`, `create_date`, `year`) values (1, '$select_time','$select_date', '$created_by', '$create_date', '$year')";
       
         $result=mysqli_query($connection, $insert_sql);
         if (mysqli_affected_rows($connection)>0) {
            $slot_master_id =  mysqli_insert_id($connection);
            
               $insert_sql="INSERT into `slot_collection_child` ( `slot_master_id`, `slot_child`, `is_booked`, booked_by, centre_code, order_no,  remarks_master) values ('$slot_master_id', '$slot_child','1', '$booked_by', '$centre_code', '$order_no', '$remarks_master')";
        
                $result=mysqli_query($connection, $insert_sql);
          
             
            $msg="Record inserted";
         } else {
            $msg="Record insert failed";
         }

      } else {
         $msg="All fields are required";
      }
    }
}

   $company_name=$_GET["centre_name"];
   $slot_child=$_GET["slot_child"];
   $date_from=$_GET["date_from"];
   $date_to=$_GET["date_to"];
   $select_time=$_GET["select_time"];
   $year=$_SESSION['Year'];

   //$browse_sql="SELECT * from `$table` where deleted=0 and year='$year' and centre_code='".$_SESSION["CentreCode"]."' order by class";
   $browse_sql="SELECT s.select_date, s.select_time, c.company_name as centre_name, sc.*, o.remarks from `$table` s inner join slot_collection_child sc on s.id=sc.slot_master_id left join centre c on c.centre_code=sc.centre_code inner join `order` o on o.order_no = sc.order_no  where sc.centre_code!='' and s.deleted=0 and s.year='$year'";
   //$browse_sql="SELECT s.* from `$table` s where s.deleted=0 and s.year='$year'";
   if($company_name!=""){
      $browse_sql.=" and c.company_name='$company_name'";
   }
   if($slot_child!=""){
      $browse_sql.=" and sc.slot_child='$slot_child'";
   }
   if($date_from!="" && $date_to!=""){
      $browse_sql.=" and s.select_date between '$date_from' and '$date_to'";
   }
   if($select_time!=""){
      $browse_sql.=" and s.select_time='$select_time' ";
   }
   
   $browse_sql.=" group by order_no ";
   $browse_sql.="order by s.id desc";
   
?>