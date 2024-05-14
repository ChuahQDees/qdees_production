<?php
//  $host="localhost";
//  $user="root";
//  $password="";
//  $db="xkyqfvfekd";

// $connection=mysqli_connect($host, $user, $password, $db);
// if (mysqli_connect_errno()) {
//    echo "Failed to connect to MySQL: ".mysqli_connect_error();
// }
if(isset($_GET['debug'])){
   ini_set('display_errors', 1);
   ini_set('display_startup_errors', 1);
   error_reporting(E_ALL);
}
$host="localhost";
$user="appadm";
$password='("`cMPT#5A85L5(s';
$db="xkyqfvfekd";

$connection=mysqli_connect($host, $user, $password, $db);
if (mysqli_connect_errno()) {
   echo "Failed to connect to MySQL: ".mysqli_connect_error();
}

if(isset($_SESSION['Year']))
{
   $year_data = mysqli_fetch_array(mysqli_query($connection,"SELECT MIN(`term_start`) AS `start_date`, MAX(`term_end`) AS `end_date` FROM `schedule_term` WHERE `year` = '".$_SESSION['Year']."' AND `centre_code` = '".$_SESSION['CentreCode']."' GROUP BY `year`"));

   $year_start_date = $year_data['start_date'];
   $year_end_date = $year_data['end_date'];
}

date_default_timezone_set("Asia/Kuala_Lumpur");
include_once("admin/common_function.php");
?>
