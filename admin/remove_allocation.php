<?php
session_start();
include_once("../mysql.php");

$id=$_POST["id"];

$sql="UPDATE allocation set deleted=1 where id='$id'";
$result=mysqli_query($connection, $sql);
if ($result) {
   echo "1|Student removed";
} else {
   echo "0|Failed";
}
?>