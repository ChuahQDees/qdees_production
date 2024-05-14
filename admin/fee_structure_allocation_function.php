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
	$foundation_int_english=$_POST["foundation_int_english"];
	$foundation_iq_math=$_POST["foundation_iq_math"];
	$foundation_int_mandarin=$_POST["foundation_int_mandarin"];
	$foundation_int_art=$_POST["foundation_int_art"];
	$pendidikan_islam=$_POST["pendidikan_islam"];
	$programme_duration=$_POST["programme_duration"];
	$programme_date=$_POST["programme_date"];
	$programme_date_end=$_POST["programme_date_end"];
	
	$foundation_mandarin=$_POST["foundation_mandarin"];
	$afternoon_programme=$_POST["afternoon_programme"];
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
    //print_r($myArray);
    $i = 0;

	$enherror = "0";
if ($student_entry_level != "EDP"){ //If it's not EDP, then it's QF1/QF2/QF3
	if ($foundation_int_english[0]=="0" && $foundation_iq_math[0]=="0" && $foundation_int_mandarin[0]=="0" && $foundation_int_art[0]=="0" && $pendidikan_islam[0]=="0"){
		//Temporary disable
		echo '<script>window.location.href = "/index.php?p=fee_str_allocate&status=enerror";</script>';
		$enherror = "1";
		//echo '<script>window.location.href = "/index.php?p=fee_str_allocate&status=completed";</script>';
	}
}

if ($enherror == "0"){
	while($i < count($studentArray))
	{
		$name = "";
		$student_code = "";
		$entry_get = mysqli_query($connection, "SELECT name, student_code FROM `student` WHERE id = '$studentArray[$i]'");

		while ($row = mysqli_fetch_assoc($entry_get)) {
			$name = $row['name'];
			$student_code = $row['student_code'];
		}

		if ($student_code != "" && $name != ""){
			$insersql = "INSERT into `programme_selection` (student_id,student_code,name,student_entry_level, fee_id ) 
			values ('$studentArray[$i]','$student_code','$name','$student_entry_level','$fee_id[0]')";

			$result=mysqli_query($connection, $insersql);
			$psid = mysqli_insert_id($connection);

			if($psid != '' && $psid > 0)
			{
				for($x=0; $x<count($fee_id); $x++){

					$fee_structure_fees = mysqli_fetch_array(mysqli_query($connection,"SELECT * FROM `fee_structure` WHERE `id` = '".$fee_id[$x]."'"));
			
					$insert_sql="INSERT into `student_fee_list` (student_id,programme_selection_id,
					programme_duration,fee_id,programme_date,programme_date_end, foundation_int_english, 
					foundation_iq_math, foundation_int_mandarin, foundation_int_art, pendidikan_islam, robotic_plus, 
					foundation_mandarin, afternoon_programme, `registration_default`, `registration_adjust`, `insurance_default`, 
					`insurance_adjust`, `uniform_default`, `uniform_adjust`, `gymwear_default`, `gymwear_adjust`, `q_dees_default`, 
					`q_dees_adjust`, `q_bag_default`, `q_bag_adjust`, `islam_collection`) 
					values ('$studentArray[$i]','$psid','$programme_duration[$x]','$fee_id[$x]',
					'$programme_date[$x]','$programme_date_end[$x]', '$foundation_int_english[$x]', '$foundation_iq_math[$x]', 
					'$foundation_int_mandarin[$x]', '$foundation_int_art[$x]', '$pendidikan_islam[$x]', '$robotic_plus[$x]', 
					'$foundation_mandarin[$x]', '$afternoon_programme[$x]', '".$fee_structure_fees['registration_default']."', 
					'".$fee_structure_fees['registration_adjust']."', '".$fee_structure_fees['insurance_default']."', '".$fee_structure_fees['insurance_adjust']."', 
					'".$fee_structure_fees['uniform_default']."', '".$fee_structure_fees['uniform_adjust']."', '".$fee_structure_fees['gymwear_default']."', 
					'".$fee_structure_fees['gymwear_adjust']."', '".$fee_structure_fees['q_dees_default']."', '".$fee_structure_fees['q_dees_adjust']."', 
					'".$fee_structure_fees['q_bag_default']."', '".$fee_structure_fees['q_bag_adjust']."', '".$fee_structure_fees['islam_collection']."')";
					
					mysqli_query($connection, $insert_sql);
					
					$extend_year = explode('-', $programme_date_end[$x])[0];
				}
			}
		}
		$i++;
	}
	if($result){
		echo '<script>window.location.href = "/index.php?p=fee_str_allocate&status=completed";</script>';
	}else{
		$msg="Failed to save data";
	}
}
?>
