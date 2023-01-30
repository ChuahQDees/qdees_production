<?php
session_start();
include_once("mysql.php");
  
  
  $form_mode=$_POST["form_mode"];
  $student_code = $_POST['student_code'];
  $student_entry_level = $_POST['student_entry_level'];

	$query ="UPDATE student SET student_entry_level = '$student_entry_level' WHERE student_code = '$hidden_student_code'";
	$result= mysqli_query($connection,$query);
	if(!$result){
		die('Query failed' .mysqli_error());
	}else{
		echo "success";
	}


?>	