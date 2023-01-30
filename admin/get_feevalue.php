<?php
session_start();
include_once("../mysql.php");

$centre_code = $_SESSION["CentreCode"];

$subject=$_POST['subject'];
$programme_package=$_POST['programme_package'];

$user_name=$_POST["UserName"];
$from_date=$_POST["from_date"];

$to_date=$_POST["to_date"];

//$country = $_SESSION["Country"];
//$state = $_SESSION["State"];

global $connection;

$sql="SELECT * from centre where centre_code='$centre_code'";
$result=mysqli_query($connection, $sql);
$row=mysqli_fetch_assoc($result);
$country = $row["country"];
$state = $row["state"];

$sql="SELECT * from fee where 1=1 and subject= '$subject' and country='$country' and state like '%$state%' and programme_package='$programme_package' and '$from_date' between from_date and to_date and '$to_date' between from_date and to_date order by id desc limit 1";

$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);
     
$row1=mysqli_fetch_assoc($result);
print json_encode($row1);

?>