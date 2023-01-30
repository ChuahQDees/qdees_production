<?php
include_once("../mysql.php");

$subject=$_POST['subject'];
//echo $subject;
$programme_package=$_POST['programme_package'];

global $connection;


$sql="SELECT * from fee_structure where 1=1 and subject= '$subject' and programme_package='$programme_package' limit 1";
//echo $sql;
$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);

$row1=mysqli_fetch_assoc($result);
print json_encode($row1);

?>