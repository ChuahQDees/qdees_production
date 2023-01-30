<?php
include_once("../mysql.php");

$group_id=$_POST["group_id"];
$group_id=mysqli_real_escape_string($connection, $group_id);

$sql="UPDATE `allocation` set deleted=1 where group_id=$group_id";
$result=mysqli_query($connection, $sql);

if($result){
  $sql2="DELETE from `group` where id=$group_id";
  $result2=mysqli_query($connection, $sql2);
  if ($result2) {
     echo "1|Delete successful";
  } else {
     echo "0|Deleted failed";
  }
}else{
  echo "0|Deleted failed";
}
?>
