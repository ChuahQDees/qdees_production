<style>
	body{
		font-family: "Courier New"
	}
</style>
<body>
<?php
//Because Chuah can't honestly bother adding everything one by one

include_once("mysql.php");

$date = "2024-01-02";
$dateToStop = "2024-03-29";
$createDate = "2023-12-04 00:00:00"; //YYYY MM DD

$my_date = date('Y-m-d', strtotime($date));
$new_date = $date;

//echo $my_date;
$sqlInsert2 = "INSERT INTO `slot_collection` (select_date, select_time, slot, created_by, create_date, year, remarks) <br>
VALUES <br>
('$my_date','9:30 AM','2','super','2023-04-12 00:00:00','2023-2024', ''), <br>
('$my_date','11:30 AM','2','super','2023-04-12 00:00:00','2023-2024', ''),<br>
('$my_date','2 PM','2','super','2023-04-12 00:00:00','2023-2024', ''), <br>
('$my_date','4 PM','2','super','2023-04-12 00:00:00','2023-2024', ''), 
<br> <br>";

while (strtotime($new_date) < strtotime($dateToStop)){
	$new_date = date('Y-m-d', strtotime("+1 day", strtotime($new_date)));
	//echo $new_date;

	//Do not print if the date is a weekend
	$dayOfWeek = date('w', strtotime($new_date));

	if ($dayOfWeek == 0 || $dayOfWeek == 6) {

	}else{
		$sqlInsert2 .= "('$new_date','9:30 AM','2','super','2023-04-12 00:00:00','2023-2024', ''), <br>
		('$new_date','11:30 AM','2','super','2023-04-12 00:00:00','2023-2024', ''),<br>
		('$new_date','2 PM','2','super','2023-04-12 00:00:00','2023-2024', ''), <br>
		('$new_date','4 PM','2','super','2023-04-12 00:00:00','2023-2024', '')";

		if (strtotime($new_date) < strtotime($dateToStop)){
			$sqlInsert2 .= ", <br> <br>";
		}
	}
}

echo $sqlInsert2;

echo "<hr>";
//Order Child

$initial = 2833; //Start from latest order
$stopCounter = 3080; //End at this counter

$counter = $initial;

$sqlInsert = "INSERT INTO `slot_collection_child` (slot_master_id, slot_child) <br> VALUES ";
while ($counter <= $stopCounter){
	$sqlInsert .= "('".$counter."', '1'), ('".$counter."', '2')";
	$counter++;
	
	if ($counter <= $stopCounter){
		$sqlInsert .= ", <br>";
	}
}

echo $sqlInsert;
?>
</body>