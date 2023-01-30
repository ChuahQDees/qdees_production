
<?php
include_once("mysql.php");
	if($_POST){
		$Password = $_POST['password'];
		$email_ = $_POST['email_'];
		//echo $Password; die;
		$confirm_password = $_POST['confirm_password'];
		if($Password  === $confirm_password){
			//$Password = md5($Password);
			 
			$sql = "UPDATE user SET `password`=password('$Password') WHERE email='$email_'";
			//echo $sql; die;
			$result = mysqli_query($connection, $sql);
			//print_r($result); die;
			$sql2 = "UPDATE reset_password_log SET `is_active`=0 WHERE email='$email_'";
			//echo $sql2; die;
			mysqli_query($connection, $sql2);
			
			if ($result === TRUE) {
				echo '<script type="text/javascript">
					   window.location = "http://starters.q-dees.com.my/"
				  </script>';
			} 
		}
	}
?>

