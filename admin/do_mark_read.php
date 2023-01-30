<?php
session_start();
include_once("../mysql.php");

foreach($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //$sha_id, $sOrderNo, $reason
} 
if($_SESSION["UserType"] == "A"){
	  $sql="UPDATE `notifications` set is_center_read=1 WHERE id = '".$id."'";
}else{
	$sql="UPDATE `notifications` set is_hq_read=1 WHERE id = '".$id."'";
}
$result=mysqli_query($connection, $sql);
if($_SESSION["UserType"] == "A"){
	$c_sql="SELECT * from notifications where send_to='".$_SESSION['CentreCode']."' AND is_center_read=0 order by created_at DESC";
	 
}else{
	$c_sql="SELECT * from notifications where send_to='hq' AND is_hq_read =0 order by created_at DESC"; 
}
$c_result=mysqli_query($connection, $c_sql);  
echo $num_row=mysqli_num_rows($c_result);
?>
 