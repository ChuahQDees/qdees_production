<?php
include_once("../mysql.php");

$id=$_REQUEST["id"];
$prove_payment_id=$_REQUEST["prove_payment_id"];

$sql="UPDATE `prove_payment_doc` set deleted = 1 where id = $id";
$result=mysqli_query($connection, $sql);

if(mysqli_num_rows(mysqli_query($connection,"SELECT `id` FROM `prove_payment_doc` WHERE `prove_payment_id` = $prove_payment_id")) < 1)
{
    $sql=mysqli_query($connection,"UPDATE `prove_payment` set deleted = 1 where id = $prove_payment_id");
}

echo "1|Delete successful";
?>
