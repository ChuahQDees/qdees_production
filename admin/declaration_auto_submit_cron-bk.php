<?php
 
include_once("../mysql.php");
global $connection;

function isRecordFound($centre_code, $year, $month) {
	global $connection;
 
	$sql_check="SELECT * from `declaration` where centre_code='$centre_code' and year=$year and month=$month";
	$result_check=mysqli_query($connection, $sql_check);
	$num_row_check=mysqli_num_rows($result_check);
 
	if ($num_row_check>0) {
	   return true;
	} else {
	   return false;
	}
 }

$submited_date= date("Y-m-d H:i:s");
$year= date("Y");
$month= date("m");
if(date("m")==1){
 $year = $year-1;
 $month= 12;
}else{
	$month= $month-1;
}

//echo $year .'-'. $month;die;

//$sql="SELECT centre_code from `centre` where `status`='A' and centre_code='MYQWESTC1C10001'";
$sql="SELECT centre_code from `centre` where `status`='A'";
$result=mysqli_query($connection, $sql);
$num_row = mysqli_num_rows($result);
if ($num_row>0) {
	while ($row=mysqli_fetch_assoc($result)) {
		$centre_code = $row['centre_code'];
		if(!isRecordFound($centre_code, $year, $month)){
		//if(true){
			
			$save_sql="INSERT INTO  declaration ( 
				year, 
				month, 
				centre_code,
				submited_date,
				remarks_centre_1,
				remarks_centre_2,
				form_1_status,
				form_2_status
			) VALUES (
				
				'$year', 
				'$month', 
				'$centre_code',
				'$submited_date',
				'Auto Submit',
				'Auto Submit',
				'Pending',
				'Pending'
			)";
			//echo $save_sql;	die;  

			$result_master = mysqli_query($connection, $save_sql); 
			$master_id = $connection->insert_id;
			//var_dump($result_master);
			if($result_master){
				echo $master_id;

				// start School Fees EDP
				$sql_adjust="SELECT fees_structure, ROUND(case 
					when school_collection = 'Termly' then  school_adjust/3 
					when school_collection = 'Half Year' then  school_adjust/6 
					when school_collection = 'Annually' then  school_adjust/12
					else school_adjust end, 2) as school_adjust
					from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".$row_adjust['fees_structure']."' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						$form="Form1";
						$fee_structure_mame="School Fees";
						$subject="EDP";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['school_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						//echo $save_sql;	//die;  
						$result_child=mysqli_query($connection, $save_sql);
						//echo $result_child;
					}
				}
				// end School Fees EDP

				// start School Fees QF1
				$sql_adjust="SELECT fees_structure, ROUND(case 
					when school_collection = 'Termly' then  school_adjust/3 
					when school_collection = 'Half Year' then  school_adjust/6 
					when school_collection = 'Annually' then  school_adjust/12
					else school_adjust end, 2) as school_adjust
					from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".$row_adjust['fees_structure']."' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						$form="Form1";
						$fee_structure_mame="School Fees";
						$subject="QF1";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['school_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						//echo $save_sql;	//die;  
						$result_child=mysqli_query($connection, $save_sql);
						//echo $result_child;
					}
				}
				// end School Fees QF1

				// start School Fees QF2
				$sql_adjust="SELECT fees_structure, ROUND(case 
					when school_collection = 'Termly' then  school_adjust/3 
					when school_collection = 'Half Year' then  school_adjust/6 
					when school_collection = 'Annually' then  school_adjust/12
					else school_adjust end, 2) as school_adjust
					from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".$row_adjust['fees_structure']."' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						$form="Form1";
						$fee_structure_mame="School Fees";
						$subject="QF2";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['school_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						//echo $save_sql;	//die;  
						$result_child=mysqli_query($connection, $save_sql);
						//echo $result_child;
					}
				}
				// end School Fees QF2

				// start School Fees QF3
				$sql_adjust="SELECT fees_structure, ROUND(case 
					when school_collection = 'Termly' then  school_adjust/3 
					when school_collection = 'Half Year' then  school_adjust/6 
					when school_collection = 'Annually' then  school_adjust/12
					else school_adjust end, 2) as school_adjust
					from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".$row_adjust['fees_structure']."' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						$form="Form1";
						$fee_structure_mame="School Fees";
						$subject="QF3";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['school_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						//echo $save_sql;	//die;  
						$result_child=mysqli_query($connection, $save_sql);
						//echo $result_child;
					}
				}
				// end School Fees QF3

				// start Multimedia EDP
				$sql_adjust="SELECT fees_structure, ROUND(case 
					when school_collection = 'Termly' then  multimedia_adjust/3 
					when school_collection = 'Half Year' then  multimedia_adjust/6 
					when school_collection = 'Annually' then  multimedia_adjust/12
					else multimedia_adjust end, 2) as multimedia_adjust
					from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".$row_adjust['fees_structure']."' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						$form="Form1";
						$fee_structure_mame="Multimedia";
						$subject="EDP";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['multimedia_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						//echo $save_sql;	//die;  
						$result_child=mysqli_query($connection, $save_sql);
						//echo $result_child;
					}
				}
				// end Multimedia EDP

				// start Multimedia QF1
				$sql_adjust="SELECT fees_structure, ROUND(case 
					when school_collection = 'Termly' then  multimedia_adjust/3 
					when school_collection = 'Half Year' then  multimedia_adjust/6 
					when school_collection = 'Annually' then  multimedia_adjust/12
					else multimedia_adjust end, 2) as multimedia_adjust
					from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".$row_adjust['fees_structure']."' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						$form="Form1";
						$fee_structure_mame="Multimedia";
						$subject="QF1";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['multimedia_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						//echo $save_sql;	//die;  
						$result_child=mysqli_query($connection, $save_sql);
						//echo $result_child;
					}
				}
				// end Multimedia QF1

				// start Multimedia QF2
				$sql_adjust="SELECT fees_structure, ROUND(case 
					when school_collection = 'Termly' then  multimedia_adjust/3 
					when school_collection = 'Half Year' then  multimedia_adjust/6 
					when school_collection = 'Annually' then  multimedia_adjust/12
					else multimedia_adjust end, 2) as multimedia_adjust
					from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".$row_adjust['fees_structure']."' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						$form="Form1";
						$fee_structure_mame="Multimedia";
						$subject="QF2";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['multimedia_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						//echo $save_sql;	//die;  
						$result_child=mysqli_query($connection, $save_sql);
						//echo $result_child;
					}
				}
				// end Multimedia QF2

				// start Multimedia QF3
				$sql_adjust="SELECT fees_structure, ROUND(case 
					when school_collection = 'Termly' then  multimedia_adjust/3 
					when school_collection = 'Half Year' then  multimedia_adjust/6 
					when school_collection = 'Annually' then  multimedia_adjust/12
					else multimedia_adjust end, 2) as multimedia_adjust
					from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".$row_adjust['fees_structure']."' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						$form="Form1";
						$fee_structure_mame="Multimedia";
						$subject="QF3";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['multimedia_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						//echo $save_sql;	//die;  
						$result_child=mysqli_query($connection, $save_sql);
						//echo $result_child;
					}
				}
				// end Multimedia QF3

				// start Afternoon Programme Fees EDP
				$sql_adjust="SELECT fees_structure, ROUND(case 
					when school_collection = 'Termly' then  basic_adjust/3 
					when school_collection = 'Half Year' then  basic_adjust/6 
					when school_collection = 'Annually' then  basic_adjust/12
					else basic_adjust end, 2) as basic_adjust
					from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and fl.afternoon_programme =1 and f.fees_structure='".$row_adjust['fees_structure']."' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						$form="Form1";
						$fee_structure_mame="Afternoon Programme Fees";
						$subject="EDP";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['basic_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						//echo $save_sql;	//die;  
						$result_child=mysqli_query($connection, $save_sql);
						//echo $result_child;
					}
				}
				// end Afternoon Programme Fees EDP

				// start Afternoon Programme Fees QF1
				$sql_adjust="SELECT fees_structure, ROUND(case 
					when school_collection = 'Termly' then  basic_adjust/3 
					when school_collection = 'Half Year' then  basic_adjust/6 
					when school_collection = 'Annually' then  basic_adjust/12
					else basic_adjust end, 2) as basic_adjust
					from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and fl.afternoon_programme =1 and f.fees_structure='".$row_adjust['fees_structure']."' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						$form="Form1";
						$fee_structure_mame="Afternoon Programme Fees";
						$subject="QF1";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['basic_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						//echo $save_sql;	//die;  
						$result_child=mysqli_query($connection, $save_sql);
						//echo $result_child;
					}
				}
				// end Afternoon Programme Fees QF1

				// start Afternoon Programme Fees QF2
				$sql_adjust="SELECT fees_structure, ROUND(case 
					when school_collection = 'Termly' then  basic_adjust/3 
					when school_collection = 'Half Year' then  basic_adjust/6 
					when school_collection = 'Annually' then  basic_adjust/12
					else basic_adjust end, 2) as basic_adjust
					from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and fl.afternoon_programme =1 and f.fees_structure='".$row_adjust['fees_structure']."' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						$form="Form1";
						$fee_structure_mame="Afternoon Programme Fees";
						$subject="QF2";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['basic_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						//echo $save_sql;	//die;  
						$result_child=mysqli_query($connection, $save_sql);
						//echo $result_child;
					}
				}
				// end Afternoon Programme Fees QF2

				// start Afternoon Programme Fees QF3
				$sql_adjust="SELECT fees_structure, ROUND(case 
					when school_collection = 'Termly' then  basic_adjust/3 
					when school_collection = 'Half Year' then  basic_adjust/6 
					when school_collection = 'Annually' then  basic_adjust/12
					else basic_adjust end, 2) as basic_adjust
					from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and fl.afternoon_programme =1 and f.fees_structure='".$row_adjust['fees_structure']."' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						$form="Form1";
						$fee_structure_mame="Afternoon Programme Fees";
						$subject="QF3";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['basic_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						//echo $save_sql;	//die;  
						$result_child=mysqli_query($connection, $save_sql);
						//echo $result_child;
					}
				}
				// end Afternoon Programme Fees QF3


				// start Mobile App Fees EDP
				$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".$row_adjust['fees_structure']."'  ";
						$sql_student .= " and case when f.mobile_collection='Monthly' then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month  
						when f.mobile_collection='Termly' and ((month(fl.programme_date)<=$month and $month in (1, 4, 7, 10) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
						when f.mobile_collection='Half Year' and ((month(fl.programme_date)<=$month and $month in (1, 7) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
						when f.mobile_collection='Annually' and ((month(fl.programme_date)<=$month and $month in (1) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
						end ";
						$sql_student .= " group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						$form="Form1";
						$fee_structure_mame="Mobile App Fees";
						$subject="EDP";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['mobile_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						//echo $save_sql;	//die;  
						$result_child=mysqli_query($connection, $save_sql);
						//echo $result_child;
					}
				}
				// end Mobile App Fees EDP

				// start Mobile App Fees QF1
				$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".$row_adjust['fees_structure']."'  ";
						$sql_student .= " and case when f.mobile_collection='Monthly' then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month  
						when f.mobile_collection='Termly' and ((month(fl.programme_date)<=$month and $month in (1, 4, 7, 10) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
						when f.mobile_collection='Half Year' and ((month(fl.programme_date)<=$month and $month in (1, 7) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
						when f.mobile_collection='Annually' and ((month(fl.programme_date)<=$month and $month in (1) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
						end ";
						$sql_student .= " group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						$form="Form1";
						$fee_structure_mame="Mobile App Fees";
						$subject="QF1";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['mobile_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						//echo $save_sql;	//die;  
						$result_child=mysqli_query($connection, $save_sql);
						//echo $result_child;
					}
				}
				// end Mobile App Fees QF1

				// start Mobile App Fees QF2
				$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".$row_adjust['fees_structure']."'  ";
						$sql_student .= " and case when f.mobile_collection='Monthly' then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month  
						when f.mobile_collection='Termly' and ((month(fl.programme_date)<=$month and $month in (1, 4, 7, 10) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
						when f.mobile_collection='Half Year' and ((month(fl.programme_date)<=$month and $month in (1, 7) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
						when f.mobile_collection='Annually' and ((month(fl.programme_date)<=$month and $month in (1) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
						end ";
						$sql_student .= " group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						$form="Form1";
						$fee_structure_mame="Mobile App Fees";
						$subject="QF2";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['mobile_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						//echo $save_sql;	//die;  
						$result_child=mysqli_query($connection, $save_sql);
						//echo $result_child;
					}
				}
				// end Mobile App Fees QF2

				// start Mobile App Fees QF3
				$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".$row_adjust['fees_structure']."'  ";
						$sql_student .= " and case when f.mobile_collection='Monthly' then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month  
						when f.mobile_collection='Termly' and ((month(fl.programme_date)<=$month and $month in (1, 4, 7, 10) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
						when f.mobile_collection='Half Year' and ((month(fl.programme_date)<=$month and $month in (1, 7) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
						when f.mobile_collection='Annually' and ((month(fl.programme_date)<=$month and $month in (1) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
						end ";
						$sql_student .= " group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						$form="Form1";
						$fee_structure_mame="Mobile App Fees";
						$subject="QF3";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['mobile_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						//echo $save_sql;	//die;  
						$result_child=mysqli_query($connection, $save_sql);
						//echo $result_child;
					}
				}
				// end Mobile App Fees QF3

				// start Q-dess Foundation Materials EDP
				$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".$row_adjust['fees_structure']."'  ";
						$sql_student .= " and case when f.mobile_collection='Monthly' then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month  
						when f.mobile_collection='Termly' and ((month(fl.programme_date)<=$month and $month in (1, 4, 7, 10) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
						when f.mobile_collection='Half Year' and ((month(fl.programme_date)<=$month and $month in (1, 7) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
						when f.mobile_collection='Annually' and ((month(fl.programme_date)<=$month and $month in (1) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
						end ";
						$sql_student .= " group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						$form="Form1";
						$fee_structure_mame="Q-dess Foundation Materials";
						$subject="EDP";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['integrated_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						//echo $save_sql;	//die;  
						$result_child=mysqli_query($connection, $save_sql);
						//echo $result_child;
					}
				}
				// end Q-dess Foundation Materials EDP

				// start Q-dess Foundation Materials QF1
				$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".$row_adjust['fees_structure']."'  ";
						$sql_student .= " and case when f.mobile_collection='Monthly' then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month  
						when f.mobile_collection='Termly' and ((month(fl.programme_date)<=$month and $month in (1, 4, 7, 10) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
						when f.mobile_collection='Half Year' and ((month(fl.programme_date)<=$month and $month in (1, 7) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
						when f.mobile_collection='Annually' and ((month(fl.programme_date)<=$month and $month in (1) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
						end ";
						$sql_student .= " group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						$form="Form1";
						$fee_structure_mame="Q-dess Foundation Materials";
						$subject="QF1";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['integrated_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						//echo $save_sql;	//die;  
						$result_child=mysqli_query($connection, $save_sql);
						//echo $result_child;
					}
				}
				// end Q-dess Foundation Materials QF1

				// start Q-dess Foundation Materials QF2
				$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".$row_adjust['fees_structure']."'  ";
						$sql_student .= " and case when f.mobile_collection='Monthly' then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month  
						when f.mobile_collection='Termly' and ((month(fl.programme_date)<=$month and $month in (1, 4, 7, 10) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
						when f.mobile_collection='Half Year' and ((month(fl.programme_date)<=$month and $month in (1, 7) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
						when f.mobile_collection='Annually' and ((month(fl.programme_date)<=$month and $month in (1) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
						end ";
						$sql_student .= " group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						$form="Form1";
						$fee_structure_mame="Q-dess Foundation Materials";
						$subject="QF2";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['integrated_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						//echo $save_sql;	//die;  
						$result_child=mysqli_query($connection, $save_sql);
						//echo $result_child;
					}
				}
				// end Q-dess Foundation Materials QF2

				// start Q-dess Foundation Materials QF3
				$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".$row_adjust['fees_structure']."'  ";
						$sql_student .= " and case when f.mobile_collection='Monthly' then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month  
						when f.mobile_collection='Termly' and ((month(fl.programme_date)<=$month and $month in (1, 4, 7, 10) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
						when f.mobile_collection='Half Year' and ((month(fl.programme_date)<=$month and $month in (1, 7) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
						when f.mobile_collection='Annually' and ((month(fl.programme_date)<=$month and $month in (1) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
						end ";
						$sql_student .= " group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						$form="Form1";
						$fee_structure_mame="Q-dess Foundation Materials";
						$subject="QF3";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['integrated_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						//echo $save_sql;	//die;  
						$result_child=mysqli_query($connection, $save_sql);
						//echo $result_child;
					}
				}
				// end Q-dess Foundation Materials QF3

				// start Q-dees Foundation Mandarin Modules Materials EDP
				$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and fl.foundation_mandarin =1 and f.fees_structure='".$row_adjust['fees_structure']."'  ";
						$sql_student .= " and case when f.mobile_collection='Monthly' then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month  
						when f.mobile_collection='Termly' and ((month(fl.programme_date)<=$month and $month in (1, 4, 7, 10) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
						when f.mobile_collection='Half Year' and ((month(fl.programme_date)<=$month and $month in (1, 7) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
						when f.mobile_collection='Annually' and ((month(fl.programme_date)<=$month and $month in (1) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
						end ";
						$sql_student .= " group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						$form="Form1";
						$fee_structure_mame="Q-dees Foundation Mandarin Modules Materials";
						$subject="EDP";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['mandarin_m_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						//echo $save_sql;	//die;  
						$result_child=mysqli_query($connection, $save_sql);
						//echo $result_child;
					}
				}
				// end Q-dees Foundation Mandarin Modules Materials EDP

				// start Q-dees Foundation Mandarin Modules Materials QF1
				$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and fl.foundation_mandarin =1 and f.fees_structure='".$row_adjust['fees_structure']."'  ";
						$sql_student .= " and case when f.mobile_collection='Monthly' then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month  
						when f.mobile_collection='Termly' and ((month(fl.programme_date)<=$month and $month in (1, 4, 7, 10) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
						when f.mobile_collection='Half Year' and ((month(fl.programme_date)<=$month and $month in (1, 7) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
						when f.mobile_collection='Annually' and ((month(fl.programme_date)<=$month and $month in (1) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
						end ";
						$sql_student .= " group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						$form="Form1";
						$fee_structure_mame="Q-dees Foundation Mandarin Modules Materials";
						$subject="QF1";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['mandarin_m_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						//echo $save_sql;	//die;  
						$result_child=mysqli_query($connection, $save_sql);
						//echo $result_child;
					}
				}
				// end Q-dees Foundation Mandarin Modules Materials QF1

				// start Q-dees Foundation Mandarin Modules Materials QF2
				$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and fl.foundation_mandarin =1 and f.fees_structure='".$row_adjust['fees_structure']."'  ";
						$sql_student .= " and case when f.mobile_collection='Monthly' then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month  
						when f.mobile_collection='Termly' and ((month(fl.programme_date)<=$month and $month in (1, 4, 7, 10) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
						when f.mobile_collection='Half Year' and ((month(fl.programme_date)<=$month and $month in (1, 7) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
						when f.mobile_collection='Annually' and ((month(fl.programme_date)<=$month and $month in (1) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
						end ";
						$sql_student .= " group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						$form="Form1";
						$fee_structure_mame="Q-dees Foundation Mandarin Modules Materials";
						$subject="QF2";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['mandarin_m_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						//echo $save_sql;	//die;  
						$result_child=mysqli_query($connection, $save_sql);
						//echo $result_child;
					}
				}
				// end Q-dees Foundation Mandarin Modules Materials QF2

				// start Q-dees Foundation Mandarin Modules Materials QF3
				$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and fl.foundation_mandarin =1 and f.fees_structure='".$row_adjust['fees_structure']."'  ";
						$sql_student .= " and case when f.mobile_collection='Monthly' then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month  
						when f.mobile_collection='Termly' and ((month(fl.programme_date)<=$month and $month in (1, 4, 7, 10) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
						when f.mobile_collection='Half Year' and ((month(fl.programme_date)<=$month and $month in (1, 7) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
						when f.mobile_collection='Annually' and ((month(fl.programme_date)<=$month and $month in (1) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
						end ";
						$sql_student .= " group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						$form="Form1";
						$fee_structure_mame="Q-dees Foundation Mandarin Modules Materials";
						$subject="QF3";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['mandarin_m_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						//echo $save_sql;	//die;  
						$result_child=mysqli_query($connection, $save_sql);
						//echo $result_child;
					}
				}
				// end Q-dees Foundation Mandarin Modules Materials QF3

				// start Registration Pack EDP
				$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where c.void='0' and year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".$row_adjust['fees_structure']."' and f.registration_adjust !='' and c.product_code='Registration' and month(c.collection_date_time) = $month group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						$form="Form1";
						$fee_structure_mame="Registration Pack";
						$subject="EDP";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['registration_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						//echo $save_sql;	//die;  
						$result_child=mysqli_query($connection, $save_sql);
						//echo $result_child;
					}
				}
				// end Registration Pack EDP

				// start Registration Pack QF1
				$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where c.void='0' and year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".$row_adjust['fees_structure']."' and f.registration_adjust !='' and c.product_code='Registration' and month(c.collection_date_time) = $month group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						$form="Form1";
						$fee_structure_mame="Registration Pack";
						$subject="QF1";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['registration_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						//echo $save_sql;	//die;  
						$result_child=mysqli_query($connection, $save_sql);
						//echo $result_child;
					}
				}
				// end Registration Pack QF1

				// start Registration Pack QF2
				$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where c.void='0' and year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".$row_adjust['fees_structure']."' and f.registration_adjust !='' and c.product_code='Registration' and month(c.collection_date_time) = $month group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						$form="Form1";
						$fee_structure_mame="Registration Pack";
						$subject="QF2";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['registration_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						//echo $save_sql;	//die;  
						$result_child=mysqli_query($connection, $save_sql);
						//echo $result_child;
					}
				}
				// end Registration Pack QF2

				// start Registration Pack QF3
				$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where c.void='0' and year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".$row_adjust['fees_structure']."' and f.registration_adjust !='' and c.product_code='Registration' and month(c.collection_date_time) = $month group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						$form="Form1";
						$fee_structure_mame="Registration Pack";
						$subject="QF3";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['registration_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						//echo $save_sql;	//die;  
						$result_child=mysqli_query($connection, $save_sql);
						//echo $result_child;
					}
				}
				// end Registration Pack QF3


				// start Pre-school Kits
				
					$sql_student = "SELECT round(sum(level_count), 0) level_count, round(avg(fees), 2) fees from ( ";
					$sql_student .="SELECT round(sum(qty), 0) level_count, fees from (SELECT c.qty, c.unit_price as fees from student s inner join `collection` c on c.student_id = s.id inner join product p on p.product_code=c.product_code where c.void='0' and c.year = '$year' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and p.product_name like 'Pre-school Kit%'  and month(c.collection_date_time) = $month group by c.id) ab ";
					$sql_student .=" UNION ALL ";
					$sql_student .=" SELECT count(id) level_count, fees from (SELECT ps.student_entry_level, s.id, c.unit_price as fees from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where c.void='0' and year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and c.product_code='Q-dees Level Kit' and month(c.collection_date_time) = $month group by ps.student_entry_level, s.id) ab ";
					$sql_student .= " )abc ";

					$result_student=mysqli_query($connection, $sql_student);
					$active_student = 0;
					$fee_rate = 0;
					while ($row_student=mysqli_fetch_assoc($result_student)) {
						$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						$fee_rate = (empty($row_student["fees"]) ? "0" : $row_student["fees"]); 
					}

					$form="Form1";
					$fee_structure_mame="Pre-school Kits";
					$amount=$active_student * $fee_rate;
					$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', $active_student, $fee_rate, $amount )";
					//echo $save_sql;	//die;  
					$result_child=mysqli_query($connection, $save_sql);
					//echo $result_child;
					
				// end Pre-school Kits

				// start Memories to Cherish
					$sql_student = "SELECT round(sum(level_count), 0) level_count, round(avg(fees), 2) fees from ( ";
					$sql_student .="SELECT round(sum(qty), 0) level_count, fees from (SELECT c.qty, c.unit_price as fees from student s inner join `collection` c on c.student_id = s.id inner join product p on p.product_code=c.product_code where c.void='0' and c.year = '$year' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and p.product_name like 'Memories To Cherish Album%' and month(c.collection_date_time) = $month group by c.id) ab";
					$sql_student .=" UNION ALL ";
					$sql_student .=" SELECT count(id) level_count, fees from (SELECT ps.student_entry_level, s.id, c.unit_price as fees from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where c.void='0' and year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and c.product_code='MEMORIES TO CHERISH' and month(c.collection_date_time) = $month group by ps.student_entry_level, s.id) ab ";
					$sql_student .= " )abc ";

					$result_student=mysqli_query($connection, $sql_student);
					$active_student = 0;
					$fee_rate = 0;
					while ($row_student=mysqli_fetch_assoc($result_student)) {
						$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						$fee_rate = (empty($row_student["fees"]) ? "0" : $row_student["fees"]); 
					}

					$form="Form1";
					$fee_structure_mame="Memories to Cherish";
					$amount=$active_student * $fee_rate;
					$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', $active_student, $fee_rate, $amount )";
					//echo $save_sql;	//die;  
					$result_child=mysqli_query($connection, $save_sql);
					//echo $result_child;
					
				// end Memories to Cherish

				// start Q-dees Bag
					$sql_student = "SELECT round(sum(level_count), 0) level_count, round(avg(fees), 2) fees from ( ";
					$sql_student .="SELECT round(sum(qty), 0) level_count, fees from (SELECT c.qty, c.unit_price as fees from student s inner join `collection` c on c.student_id = s.id inner join product p on p.product_code=c.product_code where c.void='0' and c.year = '$year' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and p.product_name = 'Q-dees Back Pack Bag' and month(c.collection_date_time) = $month group by c.id) ab";
					$sql_student .=" UNION ALL ";
					$sql_student .=" SELECT count(id) level_count, fees from (SELECT ps.student_entry_level, s.id, f.q_bag_adjust as fees from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where c.void='0' and year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and c.product_code='Q-dees Bag' and month(c.collection_date_time) = $month group by ps.student_entry_level, s.id) ab";
					$sql_student .= " )abc ";

					$result_student=mysqli_query($connection, $sql_student);
					$active_student = 0;
					$fee_rate = 0;
					while ($row_student=mysqli_fetch_assoc($result_student)) {
						$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						$fee_rate = (empty($row_student["fees"]) ? "0" : $row_student["fees"]); 
					}

					$form="Form1";
					$fee_structure_mame="Q-dees Bag";
					$amount=$active_student * $fee_rate;
					$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', $active_student, $fee_rate, $amount )";
					//echo $save_sql;	//die;  
					$result_child=mysqli_query($connection, $save_sql);
					//echo $result_child;
					
				// end Q-dees Bag

				// start Uniform
					
					$sql_student =" SELECT count(id) level_count, fees from (SELECT ps.student_entry_level, s.id, f.uniform_adjust as fees from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where c.void='0' and year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and c.product_code='Uniform (2 sets)' and month(c.collection_date_time) = $month group by ps.student_entry_level, s.id) ab";

					$result_student=mysqli_query($connection, $sql_student);
					$active_student = 0;
					$fee_rate = 0;
					while ($row_student=mysqli_fetch_assoc($result_student)) {
						$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						$fee_rate = (empty($row_student["fees"]) ? "0" : $row_student["fees"]); 
					}

					$form="Form1";
					$fee_structure_mame="Uniform";
					$amount=$active_student * $fee_rate;
					$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', $active_student, $fee_rate, $amount )";
					//echo $save_sql;	//die;  
					$result_child=mysqli_query($connection, $save_sql);
					//echo $result_child;
					
				// end Uniform

				// start Uniform products
					
					$sql_student ="SELECT product_name, round(sum(qty), 0) level_count, fees from (SELECT p.product_name, c.qty, c.unit_price as fees from student s inner join `collection` c on c.student_id = s.id inner join product p on p.product_code=c.product_code where c.void='0' and c.year = '$year' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and 
					(p.product_name like 'Q-dees Male Uniform%' or p.product_name like 'Q-dees Long Pants%' or p.product_name like 'Q-dees Female Uniform%' or p.product_name like 'Q-dees Kelantan Male Uniform%' or p.product_name like 'Q-dees Kelantan Female Uniform%') 
					and month(c.collection_date_time) = $month group by c.id, c.product_code) ab group by product_name ORDER BY FIELD(product_name,'Q-dees Male Uniform - XS(short)','Q-dees Male Uniform - S(short)','Q-dees Male Uniform - M(short)','Q-dees Male Uniform - L(short)','Q-dees Male Uniform - XL(short)','Q-dees Male Uniform - XXL(short)','Q-dees Male Uniform - XXXL(short)','Q-dees Male Uniform - Special Make(short)','Q-dees Male Uniform - XS(long)','Q-dees Male Uniform - S(long)','Q-dees Male Uniform - M(long)','Q-dees Male Uniform - L(long)','Q-dees Male Uniform - XL(long)', 'Q-dees Long Pants - XS', 'Q-dees Long Pants - S', 'Q-dees Long Pants - M', 'Q-dees Long Pants - L', 'Q-dees Long Pants - XL', 'Q-dees Female Uniform - XS', 'Q-dees Female Uniform - S', 'Q-dees Female Uniform - M', 'Q-dees Female Uniform - L', 'Q-dees Female Uniform - XL', 'Q-dees Female Uniform - XXL', 'Q-dees Female Uniform - XXXL', 'Q-dees Female Uniform - Special Make', 'Q-dees Kelantan Male Uniform - XS', 'Q-dees Kelantan Male Uniform - S', 'Q-dees Kelantan Male Uniform - M', 'Q-dees Kelantan Male Uniform - L', 'Q-dees Kelantan Male Uniform - XL', 'Q-dees Kelantan Male Uniform - XXL', 'Q-dees Kelantan Male Uniform - XXXL', 'Q-dees Kelantan Female Uniform - XS', 'Q-dees Kelantan Female Uniform - S', 'Q-dees Kelantan Female Uniform - M', 'Q-dees Kelantan Female Uniform - L', 'Q-dees Kelantan Female Uniform - XL', 'Q-dees Kelantan Female Uniform - XXL', 'Q-dees Kelantan Female Uniform - XXXL')";

					$result_student=mysqli_query($connection, $sql_student);
					$active_student = 0;
					$fee_rate = 0;
					$product_name ="";
					while ($row_student=mysqli_fetch_assoc($result_student)) {
						$product_name = $row_student['product_name'];
						$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						$fee_rate = (empty($row_student["fees"]) ? "0" : $row_student["fees"]); 
					
						$form="Form1";
						$fee_structure_mame=$product_name;
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', $active_student, $fee_rate, $amount )";
						//echo $save_sql;	//die;  
						$result_child=mysqli_query($connection, $save_sql);
						//echo $result_child;
					}
				// end Uniform products

				// start Gymwear
					
					$sql_student ="SELECT count(id) level_count, fees from (SELECT ps.student_entry_level, s.id, f.gymwear_adjust as fees from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where c.void='0' and year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and c.product_code='Gymwear (1 set)' and month(c.collection_date_time) = $month group by ps.student_entry_level, s.id) ab";

					$result_student=mysqli_query($connection, $sql_student);
					$active_student = 0;
					$fee_rate = 0;
					while ($row_student=mysqli_fetch_assoc($result_student)) {
						$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						$fee_rate = (empty($row_student["fees"]) ? "0" : $row_student["fees"]); 
					}

					$form="Form1";
					$fee_structure_mame="Gymwear";
					$amount=$active_student * $fee_rate;
					$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', $active_student, $fee_rate, $amount )";
					//echo $save_sql;	//die;  
					$result_child=mysqli_query($connection, $save_sql);
					//echo $result_child;
					
				// end Gymwear

				// start Gymwear products
					
					$sql_student ="SELECT product_name, round(sum(qty), 0) level_count, fees from (SELECT p.product_name, c.qty, c.unit_price as fees from student s inner join `collection` c on c.student_id = s.id inner join product p on p.product_code=c.product_code where c.void='0' and c.year = '$year' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and p.product_name like 'Q-dees Gymwear%' and month(c.collection_date_time) = $month group by c.id, c.product_code) ab group by product_name ORDER BY FIELD(product_name,'Q-dees Gymwear - XS','Q-dees Gymwear - S','Q-dees Gymwear - M','Q-dees Gymwear - L','Q-dees Gymwear - XL','Q-dees Gymwear - XXL','Q-dees Gymwear - XXXL','Q-dees Gymwear - Special Make','Q-dees Gymwear T-Shirt - XS','Q-dees Gymwear T-Shirt - S','Q-dees Gymwear T-Shirt - M','Q-dees Gymwear T-Shirt - L','Q-dees Gymwear T-Shirt - XL')";

					$result_student=mysqli_query($connection, $sql_student);
					$active_student = 0;
					$fee_rate = 0;
					$product_name ="";
					while ($row_student=mysqli_fetch_assoc($result_student)) {
						$product_name = $row_student['product_name'];
						$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						$fee_rate = (empty($row_student["fees"]) ? "0" : $row_student["fees"]); 
					
						$form="Form1";
						$fee_structure_mame=$product_name;
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', $active_student, $fee_rate, $amount )";
						//echo $save_sql;	//die;  
						$result_child=mysqli_query($connection, $save_sql);
						//echo $result_child;
					}
					
				// end Gymwear products


				// start Software License Fee-EDP
					
					$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end)  group by ps.student_entry_level, s.id) ab";

					$result_student=mysqli_query($connection, $sql_student);
					$active_student = 0;
					$fee_rate = 0;
					while ($row_student=mysqli_fetch_assoc($result_student)) {
						$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						$fee_rate = '33.00'; 
					}

					$form="Form1";
					$fee_structure_mame="Software License Fee-EDP";
					$amount=number_format((float)$active_student*33, 2, '.', '');
					$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', $active_student, $fee_rate, $amount )";
					//echo $save_sql;	//die;  
					$result_child=mysqli_query($connection, $save_sql);
					//echo $result_child;
					
				// end Software License Fee-EDP

				// start Software License Fee-QF1, QF2, QF3
					
					$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level  in ('QF1','QF2','QF3') and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";

					$result_student=mysqli_query($connection, $sql_student);
					$active_student = 0;
					$fee_rate = 0;
					while ($row_student=mysqli_fetch_assoc($result_student)) {
						$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						$fee_rate = '33.00'; 
					}

					$form="Form1";
					$fee_structure_mame="Software License Fee-QF1, QF2, QF3";
					$amount=number_format((float)$active_student*33, 2, '.', '');
					$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', $active_student, $fee_rate, $amount )";
					//echo $save_sql;	//die;  
					$result_child=mysqli_query($connection, $save_sql);
					//echo $result_child;
					
				// end Software License Fee-QF1, QF2, QF3

				// start Q-dees Mobile Apps–EDP, QF1, QF2, QF3
					
					$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level  in ('EDP','QF1','QF2','QF3') and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";

					$result_student=mysqli_query($connection, $sql_student);
					$active_student = 0;
					$fee_rate = 0;
					while ($row_student=mysqli_fetch_assoc($result_student)) {
						$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						$fee_rate = '11.25'; 
					}

					$form="Form1";
					$fee_structure_mame="Q-dees Mobile Apps–EDP, QF1, QF2, QF3";
					$amount=number_format((float)$active_student*11.25, 2, '.', '');
					$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', $active_student, $fee_rate, $amount )";
					//echo $save_sql;	//die;  
					$result_child=mysqli_query($connection, $save_sql);
					//echo $result_child;
					
				// end Q-dees Mobile Apps–EDP, QF1, QF2, QF3


				// start International English EDP
					$sql_adjust="SELECT fees_structure, ROUND(case 
						when school_collection = 'Termly' then  enhanced_adjust/3 
						when school_collection = 'Half Year' then  enhanced_adjust/6 
						when school_collection = 'Annually' then  enhanced_adjust/12
						else enhanced_adjust end, 2) as enhanced_adjust
						from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
					$result_adjust=mysqli_query($connection, $sql_adjust);
					$num_row_adjust = mysqli_num_rows($result_adjust);
					if ($num_row_adjust>0) {
						while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
							$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and fl.foundation_int_english=1 and f.fees_structure='".$row_adjust['fees_structure']."' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";

							$result_student=mysqli_query($connection, $sql_student);
							$active_student = 0;
							while ($row_student=mysqli_fetch_assoc($result_student)) {
								$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
							}

							$form="Form2";
							$fee_structure_mame="International English";
							$subject="EDP";
							$programme_package=$row_adjust['fees_structure'];
							$active_student=$active_student;
							$fee_rate=$row_adjust['enhanced_adjust'];
							$amount=$active_student * $fee_rate;
							$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
							//echo $save_sql;	//die;  
							$result_child=mysqli_query($connection, $save_sql);
							//echo $result_child;
						}
					}
				// end International English EDP

				// start International English QF1
					$sql_adjust="SELECT fees_structure, ROUND(case 
						when school_collection = 'Termly' then  enhanced_adjust/3 
						when school_collection = 'Half Year' then  enhanced_adjust/6 
						when school_collection = 'Annually' then  enhanced_adjust/12
						else enhanced_adjust end, 2) as enhanced_adjust
						from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
					$result_adjust=mysqli_query($connection, $sql_adjust);
					$num_row_adjust = mysqli_num_rows($result_adjust);
					if ($num_row_adjust>0) {
						while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
							$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and fl.foundation_int_english=1 and f.fees_structure='".$row_adjust['fees_structure']."' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";

							$result_student=mysqli_query($connection, $sql_student);
							$active_student = 0;
							while ($row_student=mysqli_fetch_assoc($result_student)) {
								$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
							}

							$form="Form2";
							$fee_structure_mame="International English";
							$subject="QF1";
							$programme_package=$row_adjust['fees_structure'];
							$active_student=$active_student;
							$fee_rate=$row_adjust['enhanced_adjust'];
							$amount=$active_student * $fee_rate;
							$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
							//echo $save_sql;	//die;  
							$result_child=mysqli_query($connection, $save_sql);
							//echo $result_child;
						}
					}
				// end International English QF1

				// start International English QF2
					$sql_adjust="SELECT fees_structure, ROUND(case 
						when school_collection = 'Termly' then  enhanced_adjust/3 
						when school_collection = 'Half Year' then  enhanced_adjust/6 
						when school_collection = 'Annually' then  enhanced_adjust/12
						else enhanced_adjust end, 2) as enhanced_adjust
						from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
					$result_adjust=mysqli_query($connection, $sql_adjust);
					$num_row_adjust = mysqli_num_rows($result_adjust);
					if ($num_row_adjust>0) {
						while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
							$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and fl.foundation_int_english=1 and f.fees_structure='".$row_adjust['fees_structure']."' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";

							$result_student=mysqli_query($connection, $sql_student);
							$active_student = 0;
							while ($row_student=mysqli_fetch_assoc($result_student)) {
								$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
							}

							$form="Form2";
							$fee_structure_mame="International English";
							$subject="QF2";
							$programme_package=$row_adjust['fees_structure'];
							$active_student=$active_student;
							$fee_rate=$row_adjust['enhanced_adjust'];
							$amount=$active_student * $fee_rate;
							$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
							//echo $save_sql;	//die;  
							$result_child=mysqli_query($connection, $save_sql);
							//echo $result_child;
						}
					}
				// end International English QF2

				// start International English QF3
					$sql_adjust="SELECT fees_structure, ROUND(case 
						when school_collection = 'Termly' then  enhanced_adjust/3 
						when school_collection = 'Half Year' then  enhanced_adjust/6 
						when school_collection = 'Annually' then  enhanced_adjust/12
						else enhanced_adjust end, 2) as enhanced_adjust
						from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
					$result_adjust=mysqli_query($connection, $sql_adjust);
					$num_row_adjust = mysqli_num_rows($result_adjust);
					if ($num_row_adjust>0) {
						while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
							$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and fl.foundation_int_english=1 and f.fees_structure='".$row_adjust['fees_structure']."' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";

							$result_student=mysqli_query($connection, $sql_student);
							$active_student = 0;
							while ($row_student=mysqli_fetch_assoc($result_student)) {
								$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
							}

							$form="Form2";
							$fee_structure_mame="International English";
							$subject="QF3";
							$programme_package=$row_adjust['fees_structure'];
							$active_student=$active_student;
							$fee_rate=$row_adjust['enhanced_adjust'];
							$amount=$active_student * $fee_rate;
							$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
							//echo $save_sql;	//die;  
							$result_child=mysqli_query($connection, $save_sql);
							//echo $result_child;
						}
					}
				// end International English QF3

				// start International Art EDP
					$sql_adjust="SELECT fees_structure, ROUND(case 
					when school_collection = 'Termly' then  international_adjust/3 
					when school_collection = 'Half Year' then  international_adjust/6 
					when school_collection = 'Annually' then  international_adjust/12
					else international_adjust end, 2) as international_adjust
					from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
					$result_adjust=mysqli_query($connection, $sql_adjust);
					$num_row_adjust = mysqli_num_rows($result_adjust);
					if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and fl.foundation_int_art=1 and f.fees_structure='".$row_adjust['fees_structure']."' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						$form="Form2";
						$fee_structure_mame="International Art";
						$subject="EDP";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['international_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						//echo $save_sql;	//die;  
						$result_child=mysqli_query($connection, $save_sql);
						//echo $result_child;
					}
				}
			// end International Art EDP

			// start International Art QF1
				$sql_adjust="SELECT fees_structure, ROUND(case 
					when school_collection = 'Termly' then  international_adjust/3 
					when school_collection = 'Half Year' then  international_adjust/6 
					when school_collection = 'Annually' then  international_adjust/12
					else international_adjust end, 2) as international_adjust
					from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
					$result_adjust=mysqli_query($connection, $sql_adjust);
					$num_row_adjust = mysqli_num_rows($result_adjust);
					if ($num_row_adjust>0) {
						while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and fl.foundation_int_art=1 and f.fees_structure='".$row_adjust['fees_structure']."' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						$form="Form2";
						$fee_structure_mame="International Art";
						$subject="QF1";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['international_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						//echo $save_sql;	//die;  
						$result_child=mysqli_query($connection, $save_sql);
						//echo $result_child;
						}
					}
			// end International Art QF1

			// start International Art QF2
				$sql_adjust="SELECT fees_structure, ROUND(case 
					when school_collection = 'Termly' then  international_adjust/3 
					when school_collection = 'Half Year' then  international_adjust/6 
					when school_collection = 'Annually' then  international_adjust/12
					else international_adjust end, 2) as international_adjust
					from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
					$result_adjust=mysqli_query($connection, $sql_adjust);
					$num_row_adjust = mysqli_num_rows($result_adjust);
					if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and fl.foundation_int_art=1 and f.fees_structure='".$row_adjust['fees_structure']."' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						$form="Form2";
						$fee_structure_mame="International Art";
						$subject="QF2";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['international_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						//echo $save_sql;	//die;  
						$result_child=mysqli_query($connection, $save_sql);
						//echo $result_child;
					}
				}
			// end International Art QF2

			// start International Art QF3
				$sql_adjust="SELECT fees_structure, ROUND(case 
					when school_collection = 'Termly' then  international_adjust/3 
					when school_collection = 'Half Year' then  international_adjust/6 
					when school_collection = 'Annually' then  international_adjust/12
					else international_adjust end, 2) as international_adjust
					from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and fl.foundation_int_art=1 and f.fees_structure='".$row_adjust['fees_structure']."' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						$form="Form2";
						$fee_structure_mame="International Art";
						$subject="QF3";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['international_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						//echo $save_sql;	//die;  
						$result_child=mysqli_query($connection, $save_sql);
						//echo $result_child;
					}
				}
			// end International Art QF3

			// start Mandarin EDP
				$sql_adjust="SELECT fees_structure, ROUND(case 
				when school_collection = 'Termly' then  mandarin_adjust/3 
				when school_collection = 'Half Year' then  mandarin_adjust/6 
				when school_collection = 'Annually' then  mandarin_adjust/12
				else mandarin_adjust end, 2) as mandarin_adjust
				from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
				while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
					$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and fl.foundation_int_mandarin=1 and f.fees_structure='".$row_adjust['fees_structure']."' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";

					$result_student=mysqli_query($connection, $sql_student);
					$active_student = 0;
					while ($row_student=mysqli_fetch_assoc($result_student)) {
						$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
					}

					$form="Form2";
					$fee_structure_mame="Mandarin";
					$subject="EDP";
					$programme_package=$row_adjust['fees_structure'];
					$active_student=$active_student;
					$fee_rate=$row_adjust['mandarin_adjust'];
					$amount=$active_student * $fee_rate;
					$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
					//echo $save_sql;	//die;  
					$result_child=mysqli_query($connection, $save_sql);
					//echo $result_child;
					}
				}
			// end Mandarin EDP

			// start Mandarin QF1
				$sql_adjust="SELECT fees_structure, ROUND(case 
					when school_collection = 'Termly' then  mandarin_adjust/3 
					when school_collection = 'Half Year' then  mandarin_adjust/6 
					when school_collection = 'Annually' then  mandarin_adjust/12
					else mandarin_adjust end, 2) as mandarin_adjust
					from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
					$result_adjust=mysqli_query($connection, $sql_adjust);
					$num_row_adjust = mysqli_num_rows($result_adjust);
					if ($num_row_adjust>0) {
						while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and fl.foundation_int_mandarin=1 and f.fees_structure='".$row_adjust['fees_structure']."' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						$form="Form2";
						$fee_structure_mame="Mandarin";
						$subject="QF1";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['mandarin_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						//echo $save_sql;	//die;  
						$result_child=mysqli_query($connection, $save_sql);
						//echo $result_child;
						}
					}
			// end Mandarin QF1

			// start Mandarin QF2
				$sql_adjust="SELECT fees_structure, ROUND(case 
					when school_collection = 'Termly' then  mandarin_adjust/3 
					when school_collection = 'Half Year' then  mandarin_adjust/6 
					when school_collection = 'Annually' then  mandarin_adjust/12
					else mandarin_adjust end, 2) as mandarin_adjust
					from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
					$result_adjust=mysqli_query($connection, $sql_adjust);
					$num_row_adjust = mysqli_num_rows($result_adjust);
					if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and fl.foundation_int_mandarin=1 and f.fees_structure='".$row_adjust['fees_structure']."' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						$form="Form2";
						$fee_structure_mame="Mandarin";
						$subject="QF2";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['mandarin_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						//echo $save_sql;	//die;  
						$result_child=mysqli_query($connection, $save_sql);
						//echo $result_child;
					}
				}
			// end Mandarin QF2

			// start Mandarin QF3
				$sql_adjust="SELECT fees_structure, ROUND(case 
					when school_collection = 'Termly' then  mandarin_adjust/3 
					when school_collection = 'Half Year' then  mandarin_adjust/6 
					when school_collection = 'Annually' then  mandarin_adjust/12
					else mandarin_adjust end, 2) as mandarin_adjust
					from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and fl.foundation_int_mandarin=1 and f.fees_structure='".$row_adjust['fees_structure']."' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						$form="Form2";
						$fee_structure_mame="Mandarin";
						$subject="QF3";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['mandarin_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						//echo $save_sql;	//die;  
						$result_child=mysqli_query($connection, $save_sql);
						//echo $result_child;
					}
				}
			// end Mandarin QF3

			// start IQ Math EDP
				$sql_adjust="SELECT fees_structure, ROUND(case 
				when school_collection = 'Termly' then  iq_math_adjust/3 
				when school_collection = 'Half Year' then  iq_math_adjust/6 
				when school_collection = 'Annually' then  iq_math_adjust/12
				else iq_math_adjust end, 2) as iq_math_adjust
				from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
				while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
					$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and fl.foundation_iq_math=1 and f.fees_structure='".$row_adjust['fees_structure']."' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";

					$result_student=mysqli_query($connection, $sql_student);
					$active_student = 0;
					while ($row_student=mysqli_fetch_assoc($result_student)) {
						$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
					}

					$form="Form2";
					$fee_structure_mame="IQ Math";
					$subject="EDP";
					$programme_package=$row_adjust['fees_structure'];
					$active_student=$active_student;
					$fee_rate=$row_adjust['iq_math_adjust'];
					$amount=$active_student * $fee_rate;
					$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
					//echo $save_sql;	//die;  
					$result_child=mysqli_query($connection, $save_sql);
					//echo $result_child;
						}
					}
				// end IQ Math EDP

				// start IQ Math QF1
					$sql_adjust="SELECT fees_structure, ROUND(case 
						when school_collection = 'Termly' then  iq_math_adjust/3 
						when school_collection = 'Half Year' then  iq_math_adjust/6 
						when school_collection = 'Annually' then  iq_math_adjust/12
						else iq_math_adjust end, 2) as iq_math_adjust
						from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
						$result_adjust=mysqli_query($connection, $sql_adjust);
						$num_row_adjust = mysqli_num_rows($result_adjust);
						if ($num_row_adjust>0) {
							while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
							$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and fl.foundation_iq_math=1 and f.fees_structure='".$row_adjust['fees_structure']."' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";

							$result_student=mysqli_query($connection, $sql_student);
							$active_student = 0;
							while ($row_student=mysqli_fetch_assoc($result_student)) {
								$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
							}

							$form="Form2";
							$fee_structure_mame="IQ Math";
							$subject="QF1";
							$programme_package=$row_adjust['fees_structure'];
							$active_student=$active_student;
							$fee_rate=$row_adjust['iq_math_adjust'];
							$amount=$active_student * $fee_rate;
							$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
							//echo $save_sql;	//die;  
							$result_child=mysqli_query($connection, $save_sql);
							//echo $result_child;
							}
						}
				// end IQ Math QF1

				// start IQ Math QF2
					$sql_adjust="SELECT fees_structure, ROUND(case 
						when school_collection = 'Termly' then  iq_math_adjust/3 
						when school_collection = 'Half Year' then  iq_math_adjust/6 
						when school_collection = 'Annually' then  iq_math_adjust/12
						else iq_math_adjust end, 2) as iq_math_adjust
						from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
						$result_adjust=mysqli_query($connection, $sql_adjust);
						$num_row_adjust = mysqli_num_rows($result_adjust);
						if ($num_row_adjust>0) {
						while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
							$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and fl.foundation_iq_math=1 and f.fees_structure='".$row_adjust['fees_structure']."' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";

							$result_student=mysqli_query($connection, $sql_student);
							$active_student = 0;
							while ($row_student=mysqli_fetch_assoc($result_student)) {
								$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
							}

							$form="Form2";
							$fee_structure_mame="IQ Math";
							$subject="QF2";
							$programme_package=$row_adjust['fees_structure'];
							$active_student=$active_student;
							$fee_rate=$row_adjust['iq_math_adjust'];
							$amount=$active_student * $fee_rate;
							$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
							//echo $save_sql;	//die;  
							$result_child=mysqli_query($connection, $save_sql);
							//echo $result_child;
						}
					}
				// end IQ Math QF2

				// start IQ Math QF3
					$sql_adjust="SELECT fees_structure, ROUND(case 
						when school_collection = 'Termly' then  iq_math_adjust/3 
						when school_collection = 'Half Year' then  iq_math_adjust/6 
						when school_collection = 'Annually' then  iq_math_adjust/12
						else iq_math_adjust end, 2) as iq_math_adjust
						from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
					$result_adjust=mysqli_query($connection, $sql_adjust);
					$num_row_adjust = mysqli_num_rows($result_adjust);
					if ($num_row_adjust>0) {
						while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
							$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and fl.foundation_iq_math=1 and f.fees_structure='".$row_adjust['fees_structure']."' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";

							$result_student=mysqli_query($connection, $sql_student);
							$active_student = 0;
							while ($row_student=mysqli_fetch_assoc($result_student)) {
								$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
							}

							$form="Form2";
							$fee_structure_mame="IQ Math";
							$subject="QF3";
							$programme_package=$row_adjust['fees_structure'];
							$active_student=$active_student;
							$fee_rate=$row_adjust['iq_math_adjust'];
							$amount=$active_student * $fee_rate;
							$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
							//echo $save_sql;	//die;  
							$result_child=mysqli_query($connection, $save_sql);
							//echo $result_child;
						}
					}
				// end IQ Math QF3

			// start Link & Think Series EDP
				$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
				while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
					
					$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".$row_adjust['fees_structure']."' ";
					$sql_student .= " and case when f.link_collection='Monthly' then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month  
					when f.link_collection='Termly' and ((month(fl.programme_date)<=$month and $month in (1, 4, 7, 10) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
					when f.link_collection='Half Year' and ((month(fl.programme_date)<=$month and $month in (1, 7) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
					when f.link_collection='Annually' and ((month(fl.programme_date)<=$month and $month in (1) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
					end ";
					$sql_student .= "   group by ps.student_entry_level, s.id) ab";

					$result_student=mysqli_query($connection, $sql_student);
					$active_student = 0;
					while ($row_student=mysqli_fetch_assoc($result_student)) {
						$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
					}

					$form="Form2";
					$fee_structure_mame="Link & Think Series";
					$subject="EDP";
					$programme_package=$row_adjust['fees_structure'];
					$active_student=$active_student;
					$fee_rate=$row_adjust['iq_math_adjust'];
					$amount=$active_student * $fee_rate;
					$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
					//echo $save_sql;	//die;  
					$result_child=mysqli_query($connection, $save_sql);
					//echo $result_child;
						}
					}
				// end Link & Think Series EDP

				// start Link & Think Series QF1
					$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
						$result_adjust=mysqli_query($connection, $sql_adjust);
						$num_row_adjust = mysqli_num_rows($result_adjust);
						if ($num_row_adjust>0) {
							while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
								$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".$row_adjust['fees_structure']."' ";
								$sql_student .= " and case when f.link_collection='Monthly' then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month  
								when f.link_collection='Termly' and ((month(fl.programme_date)<=$month and $month in (1, 4, 7, 10) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
								when f.link_collection='Half Year' and ((month(fl.programme_date)<=$month and $month in (1, 7) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
								when f.link_collection='Annually' and ((month(fl.programme_date)<=$month and $month in (1) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
								end ";
								$sql_student .= "   group by ps.student_entry_level, s.id) ab";

							$result_student=mysqli_query($connection, $sql_student);
							$active_student = 0;
							while ($row_student=mysqli_fetch_assoc($result_student)) {
								$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
							}

							$form="Form2";
							$fee_structure_mame="Link & Think Series";
							$subject="QF1";
							$programme_package=$row_adjust['fees_structure'];
							$active_student=$active_student;
							$fee_rate=$row_adjust['iq_math_adjust'];
							$amount=$active_student * $fee_rate;
							$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
							//echo $save_sql;	//die;  
							$result_child=mysqli_query($connection, $save_sql);
							//echo $result_child;
							}
						}
				// end Link & Think Series QF1

				// start Link & Think Series QF2
					$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
						$result_adjust=mysqli_query($connection, $sql_adjust);
						$num_row_adjust = mysqli_num_rows($result_adjust);
						if ($num_row_adjust>0) {
						while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
							$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".$row_adjust['fees_structure']."' ";
							$sql_student .= " and case when f.link_collection='Monthly' then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month  
							when f.link_collection='Termly' and ((month(fl.programme_date)<=$month and $month in (1, 4, 7, 10) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
							when f.link_collection='Half Year' and ((month(fl.programme_date)<=$month and $month in (1, 7) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
							when f.link_collection='Annually' and ((month(fl.programme_date)<=$month and $month in (1) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
							end ";
							$sql_student .= "   group by ps.student_entry_level, s.id) ab";

							$result_student=mysqli_query($connection, $sql_student);
							$active_student = 0;
							while ($row_student=mysqli_fetch_assoc($result_student)) {
								$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
							}

							$form="Form2";
							$fee_structure_mame="Link & Think Series";
							$subject="QF2";
							$programme_package=$row_adjust['fees_structure'];
							$active_student=$active_student;
							$fee_rate=$row_adjust['iq_math_adjust'];
							$amount=$active_student * $fee_rate;
							$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
							//echo $save_sql;	//die;  
							$result_child=mysqli_query($connection, $save_sql);
							//echo $result_child;
						}
					}
				// end Link & Think Series QF2

				// start Link & Think Series QF3
					$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
					$result_adjust=mysqli_query($connection, $sql_adjust);
					$num_row_adjust = mysqli_num_rows($result_adjust);
					if ($num_row_adjust>0) {
						while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
							$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".$row_adjust['fees_structure']."' ";
							$sql_student .= " and case when f.link_collection='Monthly' then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month  
							when f.link_collection='Termly' and ((month(fl.programme_date)<=$month and $month in (1, 4, 7, 10) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
							when f.link_collection='Half Year' and ((month(fl.programme_date)<=$month and $month in (1, 7) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
							when f.link_collection='Annually' and ((month(fl.programme_date)<=$month and $month in (1) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
							end ";
							$sql_student .= "   group by ps.student_entry_level, s.id) ab";

							$result_student=mysqli_query($connection, $sql_student);
							$active_student = 0;
							while ($row_student=mysqli_fetch_assoc($result_student)) {
								$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
							}

							$form="Form2";
							$fee_structure_mame="Link & Think Series";
							$subject="QF3";
							$programme_package=$row_adjust['fees_structure'];
							$active_student=$active_student;
							$fee_rate=$row_adjust['iq_math_adjust'];
							$amount=$active_student * $fee_rate;
							$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
							//echo $save_sql;	//die;  
							$result_child=mysqli_query($connection, $save_sql);
							//echo $result_child;
						}
					}
				// end Link & Think Series QF3

				// start Foundation e-Reader
						
					$sql_student =" SELECT count(id) level_count, fees from (SELECT s.id, p.unit_price as fees from student s inner join `collection` c on c.student_id = s.id inner join product p on p.product_code=c.product_code where c.void='0' and c.year = '$year' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and p.product_name like '%e-Reader Nation%' and month(c.collection_date_time) = $month group by s.id) ab";

					$result_student=mysqli_query($connection, $sql_student);
					$active_student = 0;
					$fee_rate = 0;
					while ($row_student=mysqli_fetch_assoc($result_student)) {
						$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						$fee_rate = (empty($row_student["fees"]) ? "0" : $row_student["fees"]); 
					}

					$form="Form2";
					$fee_structure_mame="Foundation e-Reader";
					$amount=$active_student * $fee_rate;
					$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', $active_student, $fee_rate, $amount )";
					//echo $save_sql;	//die;  
					$result_child=mysqli_query($connection, $save_sql);
					//echo $result_child;
					
				// end Foundation e-Reader





			
			}
		}else{
			echo "Record Exists";
		}
	} 
}

?>