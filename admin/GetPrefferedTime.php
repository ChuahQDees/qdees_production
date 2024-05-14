<?php
session_start();
include_once("../mysql.php");
$year = $_SESSION['Year'];
$centre_code = $_SESSION["CentreCode"];
foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);//product_code, centre_code, date_from, date_to
}

//$final_sql="SELECT *, CONVERT(SUBSTRING_INDEX(select_time, ' ', 1),UNSIGNED INTEGER) as s_time, TRIM( SUBSTR(select_time, LOCATE(' ', select_time)) ) AS ampm from slot_collection where centre_code='$centre_code' and select_date='$preferred_collection_date' and year = '$year' and deleted=0 group by select_time order by ampm, s_time";

//$final_sql="SELECT s.*, CONVERT(SUBSTRING_INDEX(s.select_time, ' ', 1),UNSIGNED INTEGER) as s_time, TRIM( SUBSTR(s.select_time, LOCATE(' ', select_time)) ) AS ampm from slot_collection s inner join slot_collection_child sc on s.id=sc.slot_master_id where s.select_date='$preferred_collection_date' and s.year = '$year' and s.deleted=0 and sc.is_booked=0 group by s.select_time order by ampm, s_time";

$final_sql="SELECT s.*, CONVERT(SUBSTRING_INDEX(s.select_time, ' ', 1),UNSIGNED INTEGER) as s_time, TRIM( SUBSTR(s.select_time, LOCATE(' ', select_time)) ) AS ampm from slot_collection s inner join slot_collection_child sc on s.id=sc.slot_master_id where s.select_date='$preferred_collection_date' and s.deleted=0 and sc.is_booked=0 group by s.select_time order by ampm, s_time";

$result=mysqli_query($connection, $final_sql);
$rows = array();
while($r = mysqli_fetch_assoc($result)) {
    $rows[] = $r;
}
$myJSON = json_encode($rows);
echo $myJSON;
?>