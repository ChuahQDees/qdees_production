<?php
session_start();
include_once("../mysql.php");

foreach($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //$sha_id, $sOrderNo, $reason
}  
$c_sql="SELECT * from notification_important where user_id='".$_SESSION['CentreCode']."' AND notification_id='".$id."'";
$c_result=mysqli_query($connection, $c_sql);  
$num_row=mysqli_num_rows($c_result);
if($num_row>0){
	$sql="DELETE  from notification_important where user_id='".$_SESSION['CentreCode']."' AND notification_id='".$id."'";
	mysqli_query($connection, $sql);
	echo 0;
	exit;
}else{
  	$sql="INSERT INTO `notification_important` (`notification_id`, `user_id`) VALUES ('$id','".$_SESSION['CentreCode']."')";
	mysqli_query($connection, $sql);
	echo 1;
	exit;
}
?>
 