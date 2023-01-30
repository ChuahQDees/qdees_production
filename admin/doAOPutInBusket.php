<?php
session_start();
$session_id=session_id();
include_once("../mysql.php");

$sql="SELECT * from tmp_busket where session_id='$session_id' order by id";
$result=mysqli_query($connection, $sql);

function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function getStudentCodeByID($student_id) {
   global $connection;

   $sql="SELECT student_code from student where id='$student_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["student_code"];
}

while ($row=mysqli_fetch_assoc($result)) {
	foreach ($row as $key=>$value) {
		$$key=$value;
	}

   $batch_no=generateRandomString(10);
   $student_code=getStudentCodeByID($student_id);
   $allocation_id = $allocation_id ? $allocation_id : 0;
   $amount = $qty * $unit_price;
	$isql="INSERT into busket (session_id, allocation_id, centre_code, batch_no, collection_date_time, product_code, qty, unit_price, amount, 
	collection_type, `year`, collection_month, pic, student_id, student_code, collection, collection_pattern) 
	values ('$session_id', '$allocation_id', '$centre_code', '$batch_no', '$collection_date_time', '$product_code', '$qty', '$unit_price', '$amount', '$collection_type', 
	'$year', '$collection_month', '$pic', '$student_id', '$student_code', '$amount', '$collection_pattern')";

	$iresult=mysqli_query($connection, $isql);

	if ($iresult) {
		$dsql="DELETE from tmp_busket where session_id='$session_id'";
		$dresult=mysqli_query($connection, $dsql);
		
		echo "1|Added to basket successfully";
	} else {
		echo "0|Failed";
	}
}
?>