<?php
session_start();
include_once("../mysql.php");
// include_once("functions.php");
$year = $_SESSION['Year'];
$centre_code = $_SESSION["CentreCode"];
foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);//product_code, centre_code, date_from, date_to
}

//$final_sql="SELECT * from slot_collection where centre_code='$centre_code' and select_date='$preferred_collection_date' and select_time='$preferredtime' and year = '$year' and deleted=0 order by slot";
//$final_sql="SELECT c.* from slot_collection s inner join slot_collection_child c on c.slot_master_id=s.id where s.select_date='$preferred_collection_date' and s.select_time='$preferredtime' and s.year = '$year' and s.deleted=0 and s.is_booked=0 and c.is_booked=0 order by c.slot_child";
$final_sql="SELECT c.* from slot_collection s inner join slot_collection_child c on c.slot_master_id=s.id where s.select_date='$preferred_collection_date' and s.select_time='$preferredtime' and s.deleted=0 and s.is_booked=0 and c.is_booked=0 order by c.slot_child";

$result=mysqli_query($connection, $final_sql);
$rows = array();
while($r = mysqli_fetch_assoc($result)) {
    $rows[] = $r;
}
$myJSON = json_encode($rows);
echo $myJSON;
?>