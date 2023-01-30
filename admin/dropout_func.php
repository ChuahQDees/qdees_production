<?php
$table="dropout";
$key_field="student_code";
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

function getStudentInfo($shaid, &$student_code, &$name) {
   global $connection;

   $sql="SELECT id, student_code, name from student where sha1(id)='$shaid'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   $student_code=$row["student_code"];
   $name=$row["name"];
}

function getStudentInfoByDropout($get_sha1_id, &$student_code, &$name) {
   global $connection;

   $sql="SELECT d.*, s.name from `dropout` d LEFT JOIN student s on s.student_code=d.student_code where sha1(d.id)='$get_sha1_id'";
   $result=mysqli_query($connection, $sql);

   if( $result ){
     $row=mysqli_fetch_assoc($result);
     $student_code = $row["student_code"];
     $name=$row["name"];
   }
}

if ($mode=="") {
   $mode="ADD";
   getStudentInfo($student_sha_id, $student_code, $name);
} else {
   getStudentInfoByDropout($get_sha1_id, $student_code, $name);
}

if ($mode=="DEL") {
   if ($get_sha1_id!="") {
      $del_sql="DELETE from `$table` where sha1(id)='$get_sha1_id'";
      $result=mysqli_query($connection, $del_sql);
      $msg="Record deleted";
   }
}

if ($mode=="EDIT" || $mode=="SAVE" && $get_sha1_id!="") {
   $edit_sql="SELECT * from `$table` where sha1(id)='$get_sha1_id'";
   $result=mysqli_query($connection, $edit_sql);
   $edit_row=mysqli_fetch_assoc($result);
}

if ($mode=="SAVE") {
   foreach ($_POST as $key=>$value) {
      $$key=$value;
      $$key=mysqli_real_escape_string($connection, $$key);
    }

   $dropout_date=convertDate2ISO($dropout_date);
   if (isRecordFound($table, $key_field, $student_code)) {
      if (($student_code!="") & ($dropout_date!="") & ($reason!="")){
         $update_sql="UPDATE `$table` set dropout_date='$dropout_date', reason='$reason', other_reason='".$_POST["otherReason"]."', remarks='$remark' where student_code='$student_code'";
         $result=mysqli_query($connection, $update_sql);
		//print_r($result); die;
         $msg="Record updated";
      } else {
         $msg="All fields are required";
		getStudentInfoByDropout($get_sha1_id, $student_code, $name);
		$mode="EDIT";
      } 
   } else {
      if (($student_code!="") & ($dropout_date!="") & ($reason!="")) {
      
         $insert_sql="INSERT into `$table` (student_code, centre_code, dropout_date, reason, other_reason, remarks)
         values ('$student_code', '".$_SESSION["CentreCode"]."', '$dropout_date', '$reason' ,
         '".$_POST["otherReason"]."', '$remark' )";

         $result=mysqli_query($connection, $insert_sql);

         if ($result) {
            $sql="UPDATE student set student_status='I', deleted=1 where student_code='$student_code'";
            $result=mysqli_query($connection, $sql);
            if ($result) {
               $msg="Record inserted";
            } else {
               $msg="Insert failed";
             
				getStudentInfo($student_sha_id, $student_code, $name);
				$mode="ADD";
            }
         } else {
            $msg="Insert failed";
			getStudentInfo($student_sha_id, $student_code, $name);
			$mode="ADD";
         }
      } else {
         $msg="All fields are required";
		getStudentInfo($student_sha_id, $student_code, $name);
		$mode="ADD";
      }
   }
}

include_once("search_new.php");

foreach ($_GET as $key=>$value) {
   $$key=$value;
}
$year = $_SESSION['Year'];
$base_sql="SELECT d.*, s.name from dropout d, student s where d.student_code=s.student_code and (d.dropout_date BETWEEN '".$year_start_date."' AND '".$year_end_date."')";
$centre_token=ConstructToken("d.centre_code", $_SESSION["CentreCode"], "=");
$token=ConstructTokenGroup("d.student_code", "%".$s."%", "like", "s.name", "%".$s."%", "like", "or");
$final_token=ConcatToken($centre_token, $token, "and");
$browse_sql=ConcatWhere($base_sql, $final_token);
?>
