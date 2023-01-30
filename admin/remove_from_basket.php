<?php
session_start();
$session_id=session_id();
include_once("../mysql.php");
foreach ($_POST as $key=>$value) {
	$$key=mysqli_real_escape_string($connection, $value); //$id of busket in sha1
}

if ($id!="") {
  $sql="DELETE from busket where sha1(id)='$id' and session_id='$session_id'";
  $result=mysqli_query($connection, $sql);
  if ($result) {
  	  echo "1|Remove successful";
  } else {
  	  echo "0|Remove failed";
  }
} else {
	echo "0|Something is wrong, cannot proceed";
}
?>