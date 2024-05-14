<?php
include_once("../mysql.php");

$userName=$_POST["userName"];
$sql="DELETE from cart where user_name='$userName'";
$result=mysqli_query($connection, $sql);
if ($result) {
   echo "1|Delete successful";
} else {
   echo "0|Deleted failed";
}
?>