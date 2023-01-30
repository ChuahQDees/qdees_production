	<?php
session_start();
include_once("mysql.php");
  
  $student_code = $_POST['student_code'];
  $entry = $_POST['level'];
  $foundation = $_POST['foundation'];
  $checkbox = $_POST['checkbox'];
  $duration = $_POST['duration'];
  $date = $_POST['datee'];
	$query ="UPDATE student SET student_entry_level = '$entry', foundation_mandarin = '$foundation', enhance_foundation = '$checkbox', duration = '$duration', commence_date = '$date' WHERE student_code = '$student_code'";
	$result= mysqli_query($connection,$query);
	if(!$result){
		die('Query failed' .mysqli_error());
	}else{
		echo "success";
	}


?>	