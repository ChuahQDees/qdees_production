<?php
include_once("../mysql.php");

$id=$_POST["id"];
$sql="DELETE from cart where id='$id'";
$result=mysqli_query($connection, $sql);
if ($result) {
   echo "1|Delete successful";
} else {
   echo "0|Deleted failed";
}
?>