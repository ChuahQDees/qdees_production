<?php
$table="class";
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
      $del_sql="UPDATE `$table` set deleted=1 where sha1(id)='$get_sha1_id'";
      $result=mysqli_query($connection, $del_sql);
      $msg="Record deleted";
   }
}

if ($mode=="SAVE") {
   foreach ($_POST as $key=>$value) {
      $$key=$value;
   }

   if (isRecordFound($table, $key_field, $hidden_id)) {
      if ($class!="") {
         $update_sql="UPDATE `$table` set `class`='$class', `year`='".$_SESSION['Year']."' where id='$hidden_id'";

         $result=mysqli_query($connection, $update_sql);
         $msg="Record updated";
      } else {
         $msg="All fields are required";
      }
   } else {
      if ($class!="") {
         $insert_sql="INSERT into `$table` (class, description, `year`, centre_code) values ('$class', '$description', '".$_SESSION['Year']."', 
         '$centre_code')";

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

$str=$_GET["s"];
$year=$_SESSION['Year'];
if ($_GET["s"]!="") {
   $browse_sql="SELECT * from `$table` where `class` like '%$str%' and deleted=0 and year='$year' and centre_code='".$_SESSION["CentreCode"]."' order by class";
} else {
   $browse_sql="SELECT * from `$table` where deleted=0 and year='$year' and centre_code='".$_SESSION["CentreCode"]."' order by class";
}
?>