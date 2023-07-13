<?php
include_once("mysql.php");
 $save = $_POST["form_mode"];
 $student_code=$_POST["student_code"];
 //error_reporting(E_ALL);
function getssid($student_code) {
   global $connection;
   $sql="SELECT id from student where (student_code)='$student_code'";
   
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["id"];
}

  global $connection;
   foreach ($_POST as $key=>$value) {
    $$key=$value;
  }

	$student_id=(getssid($student_code));
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

	$enherror = "0";
	if ($student_entry_level != "EDP"){ //If it's not EDP, then it's QF1/QF2/QF3
		if ($foundation_int_english[0]=="0" && $foundation_iq_math[0]=="0" && $foundation_int_mandarin[0]=="0" && $foundation_int_art[0]=="0" && $pendidikan_islam[0]=="0"){
			echo '<script>window.location.href = "/index.php?p=student_info&ssid='.sha1($student_id).'&status=enerror";</script>';
			$enherror = "1";
		}
	}

	if ($enherror == "0"){
		if($save=="Add"){

		$insert_sql1="INSERT into `programme_selection` (student_id,student_code,name,student_entry_level, fee_id ) values ('$student_id','$student_code','$name','$student_entry_level','$fee_id[0]')";

		$result=mysqli_query($connection, $insert_sql1);
		$psid = mysqli_insert_id($connection);

		if($psid != '' && $psid > 0)
		{
			for($i=0; $i<count($fee_id); $i++){

				$fee_structure_fees = mysqli_fetch_array(mysqli_query($connection,"SELECT * FROM `fee_structure` WHERE `id` = '".$fee_id[$i]."'"));
		
				$insert_sql="INSERT into `student_fee_list` (student_id,programme_selection_id,programme_duration,fee_id,programme_date,programme_date_end, foundation_int_english, foundation_iq_math, foundation_int_mandarin, foundation_int_art, pendidikan_islam, robotic_plus, foundation_mandarin, afternoon_programme, `registration_default`, `registration_adjust`, `insurance_default`, `insurance_adjust`, `uniform_default`, `uniform_adjust`, `gymwear_default`, `gymwear_adjust`, `q_dees_default`, `q_dees_adjust`, `q_bag_default`, `q_bag_adjust`, `islam_collection`) values ('$student_id','$psid','$programme_duration[$i]','$fee_id[$i]','$programme_date[$i]','$programme_date_end[$i]', '$foundation_int_english[$i]', '$foundation_iq_math[$i]', '$foundation_int_mandarin[$i]', '$foundation_int_art[$i]', '$pendidikan_islam[$i]', '$robotic_plus[$i]', '$foundation_mandarin[$i]', '$afternoon_programme[$i]', '".$fee_structure_fees['registration_default']."', '".$fee_structure_fees['registration_adjust']."', '".$fee_structure_fees['insurance_default']."', '".$fee_structure_fees['insurance_adjust']."', '".$fee_structure_fees['uniform_default']."', '".$fee_structure_fees['uniform_adjust']."', '".$fee_structure_fees['gymwear_default']."', '".$fee_structure_fees['gymwear_adjust']."', '".$fee_structure_fees['q_dees_default']."', '".$fee_structure_fees['q_dees_adjust']."', '".$fee_structure_fees['q_bag_default']."', '".$fee_structure_fees['q_bag_adjust']."', '".$fee_structure_fees['islam_collection']."')";
				
				mysqli_query($connection, $insert_sql);
				
				$extend_year = explode('-', $programme_date_end[$i])[0];
			}
		}

		if($result){
				echo '<script>window.location.href = "/index.php?p=student_info&ssid='.sha1($student_id).'";</script>';
				$msg="Record Inserted";
			}else{
				//$msg="Failed to save data";
			}
	
	}else if($save=="EDIT"){
		$psid=$_POST["programme_selection_id"];
		
		$insert_sql1="UPDATE `programme_selection` set `student_entry_level`='$student_entry_level' where id='$psid'";;
		
		$result=mysqli_query($connection, $insert_sql1);

		$del_sql="DELETE from `student_fee_list` where programme_selection_id='$psid'";
		$result=mysqli_query($connection, $del_sql);

		if($psid != '' && $psid > 0)
		{
			for($i=0; $i<count($fee_id); $i++){

				$fee_structure_fees = mysqli_fetch_array(mysqli_query($connection,"SELECT * FROM `fee_structure` WHERE `id` = '".$fee_id[$i]."'"));
		
				if(isset($old_fee_id[$i]) && $old_fee_id[$i] == $fee_id[$i])
				{
					$uniform_default = isset($uniform_default[$i]) ? $uniform_default[$i] : $fee_structure_fees['uniform_default'];
					$uniform_adjust = isset($uniform_adjust[$i]) ? $uniform_adjust[$i] : $fee_structure_fees['uniform_adjust'];
					$gymwear_default = isset($gymwear_default[$i]) ? $gymwear_default[$i] : $fee_structure_fees['gymwear_default'];
					$gymwear_adjust = isset($gymwear_adjust[$i]) ? $gymwear_adjust[$i] : $fee_structure_fees['gymwear_adjust'];
				}
				else
				{
					$uniform_default = $fee_structure_fees['uniform_default'];
					$uniform_adjust = $fee_structure_fees['uniform_adjust'];
					$gymwear_default = $fee_structure_fees['gymwear_default'];
					$gymwear_adjust = $fee_structure_fees['gymwear_adjust'];
				}
		
				$insert_sql="INSERT into `student_fee_list` (student_id,programme_selection_id,programme_duration,fee_id,programme_date,programme_date_end, foundation_int_english, foundation_iq_math, foundation_int_mandarin, foundation_int_art, pendidikan_islam, robotic_plus, foundation_mandarin, afternoon_programme, `registration_default`, `registration_adjust`, `insurance_default`, `insurance_adjust`, `uniform_default`, `uniform_adjust`, `gymwear_default`, `gymwear_adjust`, `q_dees_default`, `q_dees_adjust`, `q_bag_default`, `q_bag_adjust`, `islam_collection`) values ('$student_id','$psid','$programme_duration[$i]','$fee_id[$i]','$programme_date[$i]','$programme_date_end[$i]', '$foundation_int_english[$i]', '$foundation_iq_math[$i]', '$foundation_int_mandarin[$i]', '$foundation_int_art[$i]', '$pendidikan_islam[$i]', '$robotic_plus[$i]', '$foundation_mandarin[$i]', '$afternoon_programme[$i]', '".$fee_structure_fees['registration_default']."', '".$fee_structure_fees['registration_adjust']."', '".$fee_structure_fees['insurance_default']."', '".$fee_structure_fees['insurance_adjust']."', '".$uniform_default."', '".$uniform_adjust."', '".$gymwear_default."', '".$gymwear_adjust."', '".$fee_structure_fees['q_dees_default']."', '".$fee_structure_fees['q_dees_adjust']."', '".$fee_structure_fees['q_bag_default']."', '".$fee_structure_fees['q_bag_adjust']."', '".$fee_structure_fees['islam_collection']."')";
		
				mysqli_query($connection, $insert_sql);
				$extend_year = explode('-', $programme_date_end[$i])[0];
			}
		}
		
		if($result){
			echo '<script>window.location.href = "/index.php?p=student_info&ssid='.sha1($student_id).'";</script>';
			$msg="Record Updated";
		}else{
			//$msg="Failed to save data";
		}
	}
	}
?>
