<?php
$table="course";
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
   $sha_id=$_GET["sha_id"];
   if ($sha_id!="") {
      $del_sql="UPDATE $table set deleted=1 where sha1(id)='$sha_id'";
      $result=mysqli_query($connection, $del_sql);
      $msg="Record deleted";
   }
}

foreach ($_POST as $key=>$value) {
   $$key=$value;
}

 if ($mode=="SAVE") {
$state= $_POST['state'];
//$module= $_POST['moduley'];

//$course_name= $_POST['duration'];
$state = implode (", ", $state); 
   if (isRecordFound($table, $key_field, $id)) {
      if (($country!="") & ($state!="")& ($subject!="")) {

         $update_sql="UPDATE `$table` set country='$country',state='$state',subject='$subject', term='$term', module='$moduley', materials='$materials', remark='$remark' where id='$id'";
       

         $result=mysqli_query($connection, $update_sql);
         $msg="Record updated";
      } else {
         //$msg="All fields are required";
      }
   }else{ 
	
      if (($country!="") & ($state!="")& ($subject!="")) {
         $insert_sql="INSERT into `$table` (country,state,subject, remark, term, module, materials) values ('$country','$state','$subject', '$remark', '$term', '$moduley', '$materials')";
        

         $result=mysqli_query($connection, $insert_sql);
         $msg="Record inserted";
      } else {
         $msg="All fields are required";
      }
   
 }}

//$str=$_GET["s"];
$year=$_SESSION['Year'];

$base_sql="SELECT * from `$table`";
$programme_token=ConstructToken("country", $s_country."%", "like");
$level_token=ConstructToken("subject", "%".$s_subject."%", "like");
$module_token=ConstructToken("duration", "%".$s_duration."%", "like");
$deleted_token=ConstructToken("deleted", "0", "=");
//$order_token=ConstructOrderToken("duration", "asc");

$final_token=ConcatToken($programme_token, $level_token, "and");
$final_token=ConcatToken($final_token, $module_token, "and");
$final_token=ConcatToken($final_token, $deleted_token, "and");
$final_token=ConcatOrder($final_token, $order_token);
$browse_sql=ConcatWhere($base_sql, $final_token);

//$programme_token=ConstructToken("")
//if ($_GET["s"]!="") {
//   $browse_sql="SELECT * from `$table` where duration like '%$str%' and deleted=0 order by duration";
//} else {
//   $browse_sql="SELECT * from `$table` where deleted=0 order by duration";
//}
?>