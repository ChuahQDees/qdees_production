<?php
session_start();
$session_id=session_id();
include_once('../mysql.php');
$sql="DELETE from busket where session_id='$session_id'";
$result=mysqli_query($connection, $sql);
if ($result) {
	echo "1|Clear basket successfully";
} else {
	echo "0|Failed";
}
?>