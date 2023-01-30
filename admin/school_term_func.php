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
$state=$_POST['state']; 
$state = implode (", ", $state); 
$category=$_POST['term'];  
//$code=$_POST['year'] . " ".$_POST['term'];
$code=$_POST['term'];
$code_id=$_POST['code_id']; 
$year=$_POST['year']; 
$from_month=$_POST['from_month'];
$to_month=$_POST['to_month'];

// print_r($state); die;
      //if (isRecordFound($table, $key_field, $p_module."|".$module."|".$parent)) {
         //if ($p_module!="") {
         if ($code_id!="") {
           // $value=$p_module;
            $hidden_variable="hidden_".$p_module;
            $hidden_value=$$hidden_variable;
            $update_sql="UPDATE `$table` set code='$code', description='$description', category='$category', state='$state', country='$country',  from_month='$from_month', to_month='$to_month'
            where id='$code_id'";
			//echo $update_sql; die;
            $result=mysqli_query($connection, $update_sql);
            $msg="Record updated";
      } else {
         if ($p_module!="") {
            $value=$p_module;
            $insert_sql="INSERT into `$table` (`code`, `country`, `state`, `description`, `module`, category, from_month, to_month, use_code, parent, `year`,company) values ('$code', '$country', '$state', '$description', '$module', '$category', '$from_month', '$to_month','','',0,'')";
            $result=mysqli_query($connection, $insert_sql);
			  print_r($insert_sql); die;
			
            $msg="Record inserted";
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
   
   $country=$_GET["country"];
   $state=$_GET["state"];
   // echo $state;
   if ($_GET["category"]!="" && $_GET["country"]!="") {
      $browse_sql="SELECT * from `$table` where category like '%$str%' and country like '%$country%' and module='$module' order by code";
   }if ($_GET["category"]!="") {
      $browse_sql="SELECT * from `$table` where category like '%$str%' and module='$module' order by code";
   }else if ($_GET["country"]!="") {
      $browse_sql="SELECT * from `$table` where country like '%$country%' and module='$module' order by code";
   }else if ($_GET["state"]!="") {
      $browse_sql="SELECT * from `$table` where state like '%$state%' and module='$module' order by code";
   } else {
      $browse_sql="SELECT * from `$table` where module='$module' order by code";
   }
   
   
//} else {
//   $msg="Unauthorised access denied";
//}
?>