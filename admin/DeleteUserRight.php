<?php
include_once("../mysql.php");
foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);
}

$sql="DELETE from user_right where sha1(id)='$id'";
$result=mysqli_query($connection, $sql);
if ($result) {
   echo "1|Successful";
} else {
   echo "0|Delete failed";
}
?>