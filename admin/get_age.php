<?php
include_once('../mysql.php');

function dateDifference($date_1 , $date_2 , $differenceFormat = '%y' ) {
    $datetime1 = date_create($date_1);
    $datetime2 = date_create($date_2);

    $interval = date_diff($datetime1, $datetime2);

    return $interval->format($differenceFormat);
}

$dob=$_POST["dob"];
list($day, $month, $year)=explode("/", $dob);
$the_month=date("m");
$the_day=date("d");
$dob="$year-$the_month-$the_day";
$today=date("Y-m-d");

echo "1|".dateDifference($today, $dob);
?>