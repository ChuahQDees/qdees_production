<?php
$table="codes";
$key_field="code";
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

//if (($_SESSION["isAdmin"]==1) & ($_SESSION["isLogin"]==1)) {
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

      if (isRecordFound($table, $key_field, $$p_module)) {
         if ($$p_module!="") {
            $value=$$p_module;
            $hidden_variable="hidden_".$p_module;
            $hidden_value=$$hidden_variable;
            $update_sql="UPDATE `$table` set code='$value', description='$description', use_code='$use_code' where code='$hidden_value' 
            and module='$module'";

            $result=mysqli_query($connection, $update_sql);
            $msg="Record updated";
         } else {
            $msg="All fields are required";
         }
      } else {
         if ($$p_module!="") {
            $value=$$p_module;
            $insert_sql="INSERT into `$table` (code, description, module, use_code) values 
            ('$value', '$description', '$module', '$use_code')";

            $result=mysqli_query($connection, $insert_sql);
            $msg="Record inserted";
         } else {
            $msg="All fields are required";
         }
      }
   }

   $str=$_GET["s"];
   if ($_GET["s"]!="") {
      $browse_sql="SELECT * from `$table` where code like '%$str%' and module='$module' order by code";
   } else {
      $browse_sql="SELECT * from `$table` where module='$module' order by code";
   }
//} else {
//   $msg="Unauthorised access denied";
//}
?>