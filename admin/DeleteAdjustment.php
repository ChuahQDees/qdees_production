<?php
session_start();
include_once("../mysql.php");

foreach ($_POST as $key=>$value) {
	$$key=mysqli_real_escape_string($connection, $value); //id
}

if ($id!="") {
   $sql="DELETE from stock_adjustment where sha1(id)='$id'";
   $result=mysqli_query($connection, $sql);

   if ($result) {
   	echo "1|Record deleted";
   } else {
   	echo "0|Delete failed";
   }
} else {
	echo "0|Delete failed";
}
?>