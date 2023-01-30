<?php
session_start();
include_once("../mysql.php");
$student_code=$_POST["student_code"];
$form_mode=$_POST["form_mode"];

if ($_SESSION["isLogin"] == 1 && $form_mode!='qr') {
  $table = 'student_emergency_contacts';
}else{
  $table = 'tmp_student_emergency_contacts';
}

$sql="SELECT * from `$table` where student_code='$student_code'";
$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);
if ($num_row>0) {
   echo "OK";
} else {
   echo $sql;
}
?>
