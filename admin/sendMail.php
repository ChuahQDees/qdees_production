<?php
	include_once("../mysql.php");
	session_start();
	$student_code=$_POST['student_code'];
	//echo $subject;

	global $connection;

    //Get Parents Email
    //Get Parents Name

	$sql="SELECT contact_type, full_name, email from `student_emergency_contacts` where student_code ='$student_code'"; // new
   //$sql="SELECT * from collection where sha1(batch_no)='$batch_no' order by id asc"; // old
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);

   $msg = "";

   //Return the following:
   //Parent Email (If Any)
   //Parent Name
   //Parent Gender
   
	if ($num_row>0) {
		if ($row=mysqli_fetch_assoc($result)) {
			//$msg = json_encode($row["email"] );
			$suffix = $row["contact_type"];
			$full_name = $row["full_name"];
			$email = $row["email"];

			$arrayMSG = array($suffix, $full_name, $email);

			$msg = json_encode($arrayMSG);

			echo $msg;
		}
	} else {
		echo "";
	}
?>
