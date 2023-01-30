<?php
session_start();
include_once("../mysql.php");
foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);//year_of_commencement, year_of_renewal
}

list($day, $month, $year)=explode("/", $year_of_commencement);
$year_of_commencement="$year-$month-$day";

$yoc = date("Y-m-d", strtotime(date("Y-m-d", strtotime($year_of_commencement)) . " +".$year_of_renewal." year"));

list ($year, $month, $day)=explode("-", $yoc);
$yoc="$day/$month/$year";

echo $yoc;
?>