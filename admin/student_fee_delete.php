<?php
session_start();
include_once("../mysql.php");

$psid=$_POST["programme_selection_id"];

$sql="DELETE from `programme_selection` where id='$psid'";
$result=mysqli_query($connection, $sql);

$del_sql="DELETE from `student_fee_list` where programme_selection_id='$psid'";
mysqli_query($connection, $del_sql);

if ($result) {
   echo "Record removed";
} else {
   echo "Failed";
}
?>