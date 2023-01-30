<?php
$table="codes";
$key_field="code|module";
$msg="";

function isRecordFound($table, $key_field, $key_value) {
   global $connection;

   $key_field_array=explode("|", $key_field);
   $key_value_array=explode("|", $key_value);

   $condition="";
   for ($i=0; $i<count($array_key_field); $i++) {
      $condition.=$key_field_array[$i]."="."'".$value."',";
   }
   
   $condition=substr($condition, 0, -1);

   $sql="SELECT $key_field from `$table` where $condition";
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
// echo $_PQOST; die;

 //echo $category; die;
   if ($mode=="SAVE") {
      foreach ($_POST as $key=>$value) {
         $$key=mysqli_real_escape_string($connection, trim($value));
      }
$country=$_POST['country'];  
$code=$_POST['category'];  
// $category=$_POST['1category1']; 
$code_id=$_POST['code_id']; 
      //if (isRecordFound($table, $key_field, $p_module."|".$module."|".$parent)) {
         //if ($p_module!="") {
         if ($code_id!="") {
           // $value=$p_module;
            $hidden_variable="hidden_".$p_module;
            $hidden_value=$$hidden_variable;
            $update_sql="UPDATE `$table` set code='$code', description='$description', category='$category', country='$country' 
            where id='$code_id'";
			//echo $update_sql; die;
            $result=mysqli_query($connection, $update_sql);
            $msg="Record updated";
         //} else {
          //  $msg="All fields are required";
         //}
      } else {
		 
         $value=$p_module;
         $insert_sql="INSERT into `$table` (`code`,`country`, `description`, `module`, category) values ('$code','$country', '$description', '$module', '$category')";
         // echo $insert_sql;
//         $msg=$insert_sql;
         if ($p_module!="") {
            $value=$p_module;
            $insert_sql="INSERT into `$table` (`code`,`country`, `description`, `module`, category) values ('$code','$country', '$description', '$module', '$category')";
			//  echo $insert_sql; die;
            $result=mysqli_query($connection, $insert_sql);
            $msg="Record inserted";
         } else {
//            $msg="All fields are required";
         }
      }
   }
   // else if ($mode=="EDIT") {
	   // $value=$p_module;
            // $hidden_variable="hidden_".$p_module;
            // $hidden_value=$$hidden_variable;
            // $update_sql="UPDATE `$table` set code='$value', parent='$parent', description='$description', category='$category' 
            // where code='$hidden_value' and module='$module'";

            // $result=mysqli_query($connection, $update_sql);
            // $msg="Record updated";
   // }

   $str=$_GET["category"];
  // echo $str; die;
   $country=$_GET["country"];
   if ($_GET["category"]!="" && $_GET["country"]!="") {
      $browse_sql="SELECT * from `$table` where category like '$str' and country like '%$country%' and module='$module' order by code";
//      echo $browse_sql;
   }if ($_GET["category"]!="") {
      $browse_sql="SELECT * from `$table` where category like '$str' and module='$module' order by code";
     //echo $browse_sql; die;
   }else {
      $browse_sql="SELECT * from `$table` where module='$module' order by code";
//      echo $browse_sql;
   }
//} else {
//   $msg="Unauthorised access denied";
//}
?>