<?php
session_start();
include_once("../mysql.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);//$id (sha1)
}
 $sql="UPDATE `defective` set reject_status='1' where order_no = $sOrderNo";
 $result=mysqli_query($connection, $sql);