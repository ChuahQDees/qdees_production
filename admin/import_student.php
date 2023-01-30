<?php
include_once("../mysql.php");
include_once("functions.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); // $id (sha1)
}

function getTableFieldsWithoutID($table) {
   global $connection;

   $sql="describe $table";
   $result=mysqli_query($connection, $sql);
   while ($row=mysqli_fetch_assoc($result)) {
	  if ($row["Field"]!="id" && $row["Field"]!="delete_request" && $row["Field"]!="request_by" && $row["Field"]!="requested_at") {
         $field.=$row["Field"].",";
      }
   }

   $field=substr($field, 0, -1);

   return $field;
}

$sql="SELECT * from tmp_student where sha1(id)='$id'";
$result=mysqli_query($connection, $sql);
$row=mysqli_fetch_assoc($result);
$student_code=$row["student_code"];
$centre_code=$row["centre_code"];

if (($result) & ($student_code!="")) {
   $new_student_code=getStudentCode($centre_code);

   if($new_student_code!=$student_code){
      $sql="UPDATE tmp_student set student_code='$new_student_code' where student_code='$student_code'";
      $result=mysqli_query($connection, $sql);

      $sql="UPDATE tmp_student_emergency_contacts set student_code='$new_student_code' where student_code='$student_code'";
      $result=mysqli_query($connection, $sql);
   }

   $student_fields=getTableFieldsWithoutID("student");

   $sql="INSERT into student (".$student_fields.") SELECT ".$student_fields." from tmp_student where sha1(id)='$id'";
   writeLog($sql);
   //echo $sql;die;
   
   $result=mysqli_query($connection, $sql);
   if ($result) {
      //$sql="UPDATE student set student_code='$new_student_code' where student_code='$student_code'";
     // echo $sql;
      //$result=mysqli_query($connection, $sql);

      //if ($result) {
         $student_emergency_contact_fields=getTableFieldsWithoutID("student_emergency_contacts");

         $sql="INSERT into student_emergency_contacts (".$student_emergency_contact_fields.")
         SELECT ".$student_emergency_contact_fields." from tmp_student_emergency_contacts where student_code='$new_student_code'";

         $result=mysqli_query($connection, $sql);
         //echo $sql;die;
         // $sql="UPDATE student_emergency_contacts set student_code='$new_student_code' where student_code='$student_code'";
         // $result=mysqli_query($connection, $sql);
         
            $sql="DELETE from tmp_student where student_code in ('$student_code', '$new_student_code')";
            $result=mysqli_query($connection, $sql);

            $sql="DELETE from tmp_student_emergency_contacts where student_code in ('$student_code', '$new_student_code')";
            $result=mysqli_query($connection, $sql);
        

         $msg="1|Student has been imported successfully";
      // } else {
      //    $msg="0|Import failed 1";
      // }
   } else {
      $msg="0|Import Failed 2";
   }
} else {
   $msg="0|Import failed 3";
}

echo $msg;
?>
