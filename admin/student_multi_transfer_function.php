<?php
include_once("mysql.php");
  global $connection;
   foreach ($_POST as $key=>$value) {
    $$key=$value;
  }

	$fee_id=$_POST["fee_id"];
	//$enhanced_foundation_array=$_POST["enhanced_foundation"];
	//$enhanced_foundation_serialized = serialize($enhanced_foundation_array); 
	//$enhanced_foundation_array = unserialize($enhanced_foundation_serialized); 
    $studentArray=$_POST["studentIDArray"];


	/*
    echo "Submitted Items:";
    echo "<br/>Fee ID = ".$fee_id[0];
    echo "<br/>Foundation Int English = ".$foundation_int_english[0];
    echo "<br/>Foundation IQ Math = ".$foundation_iq_math[0];
    echo "<br/>Foundation Int Mandarin = ".$foundation_int_mandarin[0];
    echo "<br/>Foundation Int Art = ".$foundation_int_art[0];
    echo "<br/>Pendidikan Islam = ".$pendidikan_islam[0];
    echo "<br/>Programme Duration = ".$programme_duration[0];
    echo "<br/>Programme Date = ".$programme_date[0];
    echo "<br/>Programme Date End = ".$programme_date_end[0];
    echo "<br/>Foundation Mandarin = ".$foundation_mandarin[0];
    echo "<br/>Afternoon Programme = ".$afternoon_programme[0];
    echo "<br/>Students = ".$studentArray;
    echo "<br />";
	echo "aaaa".$student_entry_level;
	*/

    $studentArray = explode(',', $studentArray);
    //print_r($studentArray);
    $i = 0;

	while($i < count($studentArray)){
		$update_sql = "UPDATE `student` SET extend_year = '2023-2024' WHERE id = '$studentArray[$i]'";
		//echo $update_sql;

		$result= mysqli_query($connection, $update_sql);

		$i++;
	}

if($result){
	echo '<script>window.location.href = "/index.php?p=student_multitransfer&status=completed";</script>';
}else{
	$msg="Failed to update data";
}

?>
