<?php
/* f("export PATH='/usr/bin:/bin' && wkhtmltopdf http://178.128.87.243/admin/declaration_pdf.php?declaration_id=5225 /var/www/html/admin/declaration_pdf/1.pdf", $output);
print_r($output);
exit; */
include_once("../mysql.php");
include_once("functions.php");  	
global $connection;
error_reporting(0);
function isRecordFound($centre_code, $year, $month) {
	global $connection;
	$sql_check="SELECT * from `declaration` where centre_code='$centre_code' and year='$year' and month=$month";
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

$monthyear = date('Y-m',strtotime($year.'-'.$month));
$date = $year.'-'.$month.'-01';
$current_date = $year.'-'.$month.'-'.date('t',strtotime($date));
$flag = 1;

//$sql="SELECT centre_code from `centre` where `status`='A' and centre_code='MYQWESTC1C10148'";
$sql="SELECT centre_code from `centre` where `status`='A'";
$result=mysqli_query($connection, $sql);
$num_row = mysqli_num_rows($result);
if ($num_row>0) {
	
	$single_year = $year;

	$half_year_array = array();
    $half_year_array[0] = 3;
    $half_year_array[1] = 9;

    $half_year_array = implode(", ", $half_year_array);

	while ($row=mysqli_fetch_assoc($result)) {
		$centre_code = $row['centre_code'];
	
		$year_data = mysqli_fetch_array(mysqli_query($connection,"SELECT `year` FROM `schedule_term` WHERE ('".date('Y-m-d',strtotime($single_year.'-'.$month))."' BETWEEN `term_start` AND `term_end`) AND `centre_code` = '".$centre_code."' AND `deleted` = 0"));

		$year = $year_data['year'];

		$year_data = mysqli_fetch_array(mysqli_query($connection,"SELECT MIN(`term_start`) AS `start_date`, MAX(`term_end`) AS `end_date` FROM `schedule_term` WHERE `year` = '".$year."' AND `centre_code` = '".$centre_code."' GROUP BY `year`"));

		$year_start_date = $year_data['start_date'];
		$year_end_date = $year_data['end_date'];

		$term_array = array();

		$term_start_date = mysqli_query($connection,"SELECT MIN(`term_start`) AS `start_date` FROM `schedule_term` WHERE `year` = '".$year."' AND `centre_code` = '".$centre_code."' GROUP BY `term_num` ORDER BY `term_num` ASC");

		$j = 0;

		while($term_row = mysqli_fetch_array($term_start_date)) {
			$term_array[$j] = date('m',strtotime($term_row['start_date']));
			$j++;
		}
		
		$term_array = implode(", ", $term_array);

		if(!isRecordFound($centre_code, $year, $month)){
		//if(true){
			if($flag == 10)
			{
				//exit;
			}

			$flag++;
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
			

			$result_master = mysqli_query($connection, $save_sql); 
			$master_id = $connection->insert_id;
				
			if($result_master){
				//echo $master_id;

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

						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

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
						
						$result_child=mysqli_query($connection, $save_sql);
					
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

						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

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
						 
						$result_child=mysqli_query($connection, $save_sql);
					
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

						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

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
				
						$result_child=mysqli_query($connection, $save_sql);
					
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

						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

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
						
						$result_child=mysqli_query($connection, $save_sql);
					
					}
				}
				// end School Fees QF3

				// start Multimedia EDP
				$sql_adjust="SELECT fees_structure, ROUND(case 
					when multimedia_collection = 'Termly' then  multimedia_adjust/3 
					when multimedia_collection = 'Half Year' then  multimedia_adjust/6 
					when multimedia_collection = 'Annually' then  multimedia_adjust/12
					else multimedia_adjust end, 2) as multimedia_adjust 
					from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

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
				
						$result_child=mysqli_query($connection, $save_sql);
				
					}
				}
				// end Multimedia EDP

				// start Multimedia QF1
				$sql_adjust="SELECT fees_structure, ROUND(case 
					when multimedia_collection = 'Termly' then  multimedia_adjust/3 
					when multimedia_collection = 'Half Year' then  multimedia_adjust/6 
					when multimedia_collection = 'Annually' then  multimedia_adjust/12
					else multimedia_adjust end, 2) as multimedia_adjust
					from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

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
					when multimedia_collection = 'Termly' then  multimedia_adjust/3 
					when multimedia_collection = 'Half Year' then  multimedia_adjust/6 
					when multimedia_collection = 'Annually' then  multimedia_adjust/12
					else multimedia_adjust end, 2) as multimedia_adjust
					from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

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
				
						$result_child=mysqli_query($connection, $save_sql);
					}
				}
				// end Multimedia QF2

				// start Multimedia QF3
				$sql_adjust="SELECT fees_structure, ROUND(case 
					when multimedia_collection = 'Termly' then  multimedia_adjust/3 
					when multimedia_collection = 'Half Year' then  multimedia_adjust/6 
					when multimedia_collection = 'Annually' then  multimedia_adjust/12
					else multimedia_adjust end, 2) as multimedia_adjust
					from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

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
					
						$result_child=mysqli_query($connection, $save_sql);
					
					}
				}
				// end Multimedia QF3

				// start Afternoon Programme Fees EDP

				$sql_adjust="SELECT fees_structure, ROUND(case 
					when basic_collection = 'Termly' then  basic_adjust/3 
					when basic_collection = 'Half Year' then  basic_adjust/6 
					when basic_collection = 'Annually' then  basic_adjust/12
					else basic_adjust end, 2) as basic_adjust, ROUND(case 
					when afternoon_robotic_collection = 'Termly' then  afternoon_robotic_adjust/3 
					when afternoon_robotic_collection = 'Half Year' then  afternoon_robotic_adjust/6 
					when afternoon_robotic_collection = 'Annually' then  afternoon_robotic_adjust/12
					else afternoon_robotic_adjust end, 2) as afternoon_robotic_adjust
					from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and fl.afternoon_programme =1 and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						if($row_adjust['basic_adjust'] < 1 && $row_adjust['afternoon_robotic_adjust'] > 0) {
							$row_adjust['basic_adjust'] = $row_adjust['afternoon_robotic_adjust'];
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
					when basic_collection = 'Termly' then  basic_adjust/3 
					when basic_collection = 'Half Year' then  basic_adjust/6 
					when basic_collection = 'Annually' then  basic_adjust/12
					else basic_adjust end, 2) as basic_adjust, ROUND(case 
					when afternoon_robotic_collection = 'Termly' then  afternoon_robotic_adjust/3 
					when afternoon_robotic_collection = 'Half Year' then  afternoon_robotic_adjust/6 
					when afternoon_robotic_collection = 'Annually' then  afternoon_robotic_adjust/12
					else afternoon_robotic_adjust end, 2) as afternoon_robotic_adjust
					from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and fl.afternoon_programme =1 and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						if($row_adjust['basic_adjust'] < 1 && $row_adjust['afternoon_robotic_adjust'] > 0) {
							$row_adjust['basic_adjust'] = $row_adjust['afternoon_robotic_adjust'];
						}

						$form="Form1";
						$fee_structure_mame="Afternoon Programme Fees";
						$subject="QF1";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['basic_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
					
						$result_child=mysqli_query($connection, $save_sql);
					
					}
				}
				// end Afternoon Programme Fees QF1

				// start Afternoon Programme Fees QF2
				$sql_adjust="SELECT fees_structure, ROUND(case 
					when basic_collection = 'Termly' then  basic_adjust/3 
					when basic_collection = 'Half Year' then  basic_adjust/6 
					when basic_collection = 'Annually' then  basic_adjust/12
					else basic_adjust end, 2) as basic_adjust, ROUND(case 
					when afternoon_robotic_collection = 'Termly' then  afternoon_robotic_adjust/3 
					when afternoon_robotic_collection = 'Half Year' then  afternoon_robotic_adjust/6 
					when afternoon_robotic_collection = 'Annually' then  afternoon_robotic_adjust/12
					else afternoon_robotic_adjust end, 2) as afternoon_robotic_adjust
					from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and fl.afternoon_programme =1 and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						if($row_adjust['basic_adjust'] < 1 && $row_adjust['afternoon_robotic_adjust'] > 0) {
							$row_adjust['basic_adjust'] = $row_adjust['afternoon_robotic_adjust'];
						}

						$form="Form1";
						$fee_structure_mame="Afternoon Programme Fees";
						$subject="QF2";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['basic_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						 
						$result_child=mysqli_query($connection, $save_sql);
					}
				}
				// end Afternoon Programme Fees QF2

				// start Afternoon Programme Fees QF3
				$sql_adjust="SELECT fees_structure, ROUND(case 
					when basic_collection = 'Termly' then  basic_adjust/3 
					when basic_collection = 'Half Year' then  basic_adjust/6 
					when basic_collection = 'Annually' then  basic_adjust/12
					else basic_adjust end, 2) as basic_adjust, ROUND(case 
					when afternoon_robotic_collection = 'Termly' then  afternoon_robotic_adjust/3 
					when afternoon_robotic_collection = 'Half Year' then  afternoon_robotic_adjust/6 
					when afternoon_robotic_collection = 'Annually' then  afternoon_robotic_adjust/12
					else afternoon_robotic_adjust end, 2) as afternoon_robotic_adjust
					from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and fl.afternoon_programme =1 and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						if($row_adjust['basic_adjust'] < 1 && $row_adjust['afternoon_robotic_adjust'] > 0) {
							$row_adjust['basic_adjust'] = $row_adjust['afternoon_robotic_adjust'];
						}

						$form="Form1";
						$fee_structure_mame="Afternoon Programme Fees";
						$subject="QF3";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['basic_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
					 
						$result_child=mysqli_query($connection, $save_sql);
				
					}
				}
				// end Afternoon Programme Fees QF3


				// start Mobile App Fees EDP
			
				// end Mobile App Fees EDP

				// start Mobile App Fees QF1
				$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and case when f.mobile_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
						when f.mobile_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
						when f.mobile_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
						when f.mobile_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
						end group by ps.student_entry_level, s.id) ab";

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
					
						$result_child=mysqli_query($connection, $save_sql);
						
					}
				}
				// end Mobile App Fees QF1

				// start Mobile App Fees QF2
				$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and case when f.mobile_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
						when f.mobile_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
						when f.mobile_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
						when f.mobile_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
						end group by ps.student_entry_level, s.id) ab";

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
					
						$result_child=mysqli_query($connection, $save_sql);
					
					}
				}
				// end Mobile App Fees QF2

				// start Mobile App Fees QF3
				$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and case when f.mobile_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                        when f.mobile_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear' when f.mobile_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear' when f.mobile_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear' end group by ps.student_entry_level, s.id) ab";

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

						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and case when f.integrated_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
						when f.integrated_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
						when f.integrated_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
						when f.integrated_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
						end group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}
						$row_adjust['integrated_adjust'] = 195;
						$form="Form1";
						$fee_structure_mame="Q-dess Foundation Materials";
						$subject="EDP";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['integrated_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						$result_child=mysqli_query($connection, $save_sql);
					}
				}
				// end Q-dess Foundation Materials EDP

				// start Q-dess Foundation Materials QF1
				$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and case when f.integrated_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
						when f.integrated_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
						when f.integrated_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
						when f.integrated_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
						end group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}
						$row_adjust['integrated_adjust'] = 195;
						$form="Form1";
						$fee_structure_mame="Q-dess Foundation Materials";
						$subject="QF1";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['integrated_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						$result_child=mysqli_query($connection, $save_sql);
					}
				}
				// end Q-dess Foundation Materials QF1

				// start Q-dess Foundation Materials QF2
				$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and case when f.integrated_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
						when f.integrated_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
						when f.integrated_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
						when f.integrated_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
						end group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}
						$row_adjust['integrated_adjust'] = 195;
						$form="Form1";
						$fee_structure_mame="Q-dess Foundation Materials";
						$subject="QF2";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['integrated_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
					
						$result_child=mysqli_query($connection, $save_sql);
				
					}
				}
				// end Q-dess Foundation Materials QF2

				// start Q-dess Foundation Materials QF3
				$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and case when f.integrated_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
						when f.integrated_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
						when f.integrated_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
						when f.integrated_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
						end group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}
						$row_adjust['integrated_adjust'] = 195;
						$form="Form1";
						$fee_structure_mame="Q-dess Foundation Materials";
						$subject="QF3";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['integrated_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
				
						$result_child=mysqli_query($connection, $save_sql);
				
					}
				}
				// end Q-dess Foundation Materials QF3

				// start Q-dees Foundation Mandarin Modules Materials EDP
				$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and fl.foundation_mandarin =1 and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and case when f.mandarin_m_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
						when f.mandarin_m_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
						when f.mandarin_m_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
						when f.mandarin_m_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
						end group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}
						$row_adjust['mandarin_m_adjust'] = 0;
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

						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and fl.foundation_mandarin =1 and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and case when f.mandarin_m_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
						when f.mandarin_m_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
						when f.mandarin_m_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
						when f.mandarin_m_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
						end group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}
						$row_adjust['mandarin_m_adjust'] = 0;
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

						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and fl.foundation_mandarin =1 and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and case when f.mandarin_m_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
						when f.mandarin_m_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
						when f.mandarin_m_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
						when f.mandarin_m_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
						end group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}
						$row_adjust['mandarin_m_adjust'] = 55;
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

						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and fl.foundation_mandarin =1 and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and case when f.mandarin_m_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
						when f.mandarin_m_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
						when f.mandarin_m_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
						when f.mandarin_m_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
						end group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}
						$row_adjust['mandarin_m_adjust'] = 55;
						$form="Form1";
						$fee_structure_mame="Q-dees Foundation Mandarin Modules Materials";
						$subject="QF3";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['mandarin_m_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						$result_child=mysqli_query($connection, $save_sql);
					}
				}
				// end Q-dees Foundation Mandarin Modules Materials QF3


				if($monthyear >= '2024-03') {

					// start STEM Programme EDP
					$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
					$result_adjust=mysqli_query($connection, $sql_adjust);
					$num_row_adjust = mysqli_num_rows($result_adjust);
					if ($num_row_adjust>0) {
						while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

							$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and case when f.stem_programme_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
							when f.stem_programme_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
							when f.stem_programme_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
							when f.stem_programme_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
							end group by ps.student_entry_level, s.id) ab";

							$result_student=mysqli_query($connection, $sql_student);
							$active_student = 0;
							while ($row_student=mysqli_fetch_assoc($result_student)) {
								$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
							}
							
							$form="Form1";
							$fee_structure_mame="STEM Programme";
							$subject="EDP";
							$programme_package=$row_adjust['fees_structure'];
							
							$fee_rate=$row_adjust['stem_programme_adjust'];
							$amount=$active_student * $fee_rate;
							$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
							$result_child=mysqli_query($connection, $save_sql);
						}
					}
					// end STEM Programme EDP

					// start STEM Programme QF1
					$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
					$result_adjust=mysqli_query($connection, $sql_adjust);
					$num_row_adjust = mysqli_num_rows($result_adjust);
					if ($num_row_adjust>0) {
						while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

							$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and case when f.stem_programme_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
							when f.stem_programme_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
							when f.stem_programme_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
							when f.stem_programme_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
							end group by ps.student_entry_level, s.id) ab";

							$result_student=mysqli_query($connection, $sql_student);
							$active_student = 0;
							while ($row_student=mysqli_fetch_assoc($result_student)) {
								$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
							}
							
							$form="Form1";
							$fee_structure_mame="STEM Programme";
							$subject="QF1";
							$programme_package=$row_adjust['fees_structure'];
							
							$fee_rate=$row_adjust['stem_programme_adjust'];
							$amount=$active_student * $fee_rate;
							$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
							$result_child=mysqli_query($connection, $save_sql);
						}
					}
					// end STEM Programme QF1

					// start STEM Programme QF2
					$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
					$result_adjust=mysqli_query($connection, $sql_adjust);
					$num_row_adjust = mysqli_num_rows($result_adjust);
					if ($num_row_adjust>0) {
						while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

							$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and case when f.stem_programme_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
							when f.stem_programme_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
							when f.stem_programme_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
							when f.stem_programme_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
							end group by ps.student_entry_level, s.id) ab";

							$result_student=mysqli_query($connection, $sql_student);
							$active_student = 0;
							while ($row_student=mysqli_fetch_assoc($result_student)) {
								$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
							}
							
							$form="Form1";
							$fee_structure_mame="STEM Programme";
							$subject="QF2";
							$programme_package=$row_adjust['fees_structure'];
							
							$fee_rate=$row_adjust['stem_programme_adjust'];
							$amount=$active_student * $fee_rate;
							$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						
							$result_child=mysqli_query($connection, $save_sql);

						}
					}
					// end STEM Programme QF2

					// start STEM Programme QF3
					$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
					$result_adjust=mysqli_query($connection, $sql_adjust);
					$num_row_adjust = mysqli_num_rows($result_adjust);
					if ($num_row_adjust>0) {
						while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

							$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and case when f.stem_programme_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
							when f.stem_programme_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
							when f.stem_programme_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
							when f.stem_programme_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
							end group by ps.student_entry_level, s.id) ab";

							$result_student=mysqli_query($connection, $sql_student);
							$active_student = 0;
							while ($row_student=mysqli_fetch_assoc($result_student)) {
								$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
							}
							
							$form="Form1";
							$fee_structure_mame="STEM Programme";
							$subject="QF3";
							$programme_package=$row_adjust['fees_structure'];
							
							$fee_rate=$row_adjust['stem_programme_adjust'];
							$amount=$active_student * $fee_rate;
							$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";

							$result_child=mysqli_query($connection, $save_sql);

						}
					}
					// end STEM Programme QF3




					// start STEM Student Kit EDP
					$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
					$result_adjust=mysqli_query($connection, $sql_adjust);
					$num_row_adjust = mysqli_num_rows($result_adjust);
					if ($num_row_adjust>0) {
						while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

							$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and case when f.stem_studentKit_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
							when f.stem_studentKit_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
							when f.stem_studentKit_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
							when f.stem_studentKit_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
							end group by ps.student_entry_level, s.id) ab";

							$result_student=mysqli_query($connection, $sql_student);
							$active_student = 0;
							while ($row_student=mysqli_fetch_assoc($result_student)) {
								$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
							}
							
							$form="Form1";
							$fee_structure_mame="STEM Student Kit";
							$subject="EDP";
							$programme_package=$row_adjust['fees_structure'];
							
							$fee_rate=$row_adjust['stem_studentKit_adjust'];
							$amount=$active_student * $fee_rate;
							$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
							$result_child=mysqli_query($connection, $save_sql);
						}
					}
					// end STEM Student Kit EDP

					// start STEM Student Kit QF1
					$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
					$result_adjust=mysqli_query($connection, $sql_adjust);
					$num_row_adjust = mysqli_num_rows($result_adjust);
					if ($num_row_adjust>0) {
						while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

							$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and case when f.stem_studentKit_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
							when f.stem_studentKit_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
							when f.stem_studentKit_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
							when f.stem_studentKit_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
							end group by ps.student_entry_level, s.id) ab";

							$result_student=mysqli_query($connection, $sql_student);
							$active_student = 0;
							while ($row_student=mysqli_fetch_assoc($result_student)) {
								$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
							}
							
							$form="Form1";
							$fee_structure_mame="STEM Student Kit";
							$subject="QF1";
							$programme_package=$row_adjust['fees_structure'];
							
							$fee_rate=$row_adjust['stem_studentKit_adjust'];
							$amount=$active_student * $fee_rate;
							$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
							$result_child=mysqli_query($connection, $save_sql);
						}
					}
					// end STEM Student Kit QF1

					// start STEM Student Kit QF2
					$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
					$result_adjust=mysqli_query($connection, $sql_adjust);
					$num_row_adjust = mysqli_num_rows($result_adjust);
					if ($num_row_adjust>0) {
						while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

							$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and case when f.stem_studentKit_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
							when f.stem_studentKit_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
							when f.stem_studentKit_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
							when f.stem_studentKit_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
							end group by ps.student_entry_level, s.id) ab";

							$result_student=mysqli_query($connection, $sql_student);
							$active_student = 0;
							while ($row_student=mysqli_fetch_assoc($result_student)) {
								$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
							}
							
							$form="Form1";
							$fee_structure_mame="STEM Student Kit";
							$subject="QF2";
							$programme_package=$row_adjust['fees_structure'];
							
							$fee_rate=$row_adjust['stem_studentKit_adjust'];
							$amount=$active_student * $fee_rate;
							$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
						
							$result_child=mysqli_query($connection, $save_sql);

						}
					}
					// end STEM Student Kit QF2

					// start STEM Student Kit QF3
					$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
					$result_adjust=mysqli_query($connection, $sql_adjust);
					$num_row_adjust = mysqli_num_rows($result_adjust);
					if ($num_row_adjust>0) {
						while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

							$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and case when f.stem_studentKit_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
							when f.stem_studentKit_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
							when f.stem_studentKit_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
							when f.stem_studentKit_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
							end group by ps.student_entry_level, s.id) ab";

							$result_student=mysqli_query($connection, $sql_student);
							$active_student = 0;
							while ($row_student=mysqli_fetch_assoc($result_student)) {
								$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
							}
							
							$form="Form1";
							$fee_structure_mame="STEM Student Kit";
							$subject="QF3";
							$programme_package=$row_adjust['fees_structure'];
							
							$fee_rate=$row_adjust['stem_studentKit_adjust'];
							$amount=$active_student * $fee_rate;
							$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";

							$result_child=mysqli_query($connection, $save_sql);

						}
					}
					// end STEM Student Kit QF3

				}




				// start Registration Pack EDP
				$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and DATE_FORMAT(s.start_date_at_centre, '%Y-%m') = '".$monthyear."' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and f.registration_adjust !=''  and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

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

						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and DATE_FORMAT(s.start_date_at_centre, '%Y-%m') = '".$monthyear."' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m')  group by ps.student_entry_level, s.id) ab";

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

						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and DATE_FORMAT(s.start_date_at_centre, '%Y-%m') = '".$monthyear."' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

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

						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and DATE_FORMAT(s.start_date_at_centre, '%Y-%m') = '".$monthyear."' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

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
				
					$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and DATE_FORMAT(s.start_date_at_centre, '%Y-%m') = '".$monthyear."' and s.centre_code='$centre_code' and s.deleted='0' and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

					$sql_price="SELECT *, BIN(`deleted` + 0) AS `deleted`, unit_price as fees
								FROM `product`
								WHERE `product_name` LIKE '%Pre-school Kit%'
								LIMIT 1";
					$result_price=mysqli_query($connection, $sql_price);
					$row_price=mysqli_fetch_assoc($result_price);

					$result_student=mysqli_query($connection, $sql_student);
					$active_student = 0;
					$fee_rate = 0;
					while ($row_student=mysqli_fetch_assoc($result_student)) {
						$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						$fee_rate = (empty($row_price["fees"]) ? "0" : $row_price["fees"]); 
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
				$sql_student .="SELECT round(sum(qty), 0) level_count, fees from (SELECT c.qty, c.unit_price as fees from student s inner join `collection` c on c.student_id = s.id inner join product p on p.product_code=c.product_code where c.void='0' and c.year = '$year' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and p.product_name like 'Memories To Cherish Album%' and month(c.collection_date_time) = $month group by c.id) ab";
				$sql_student .=" UNION ALL ";
				$sql_student .=" SELECT count(id) level_count, fees from (SELECT ps.student_entry_level, s.id, c.unit_price as fees from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where c.void='0' and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and c.product_code='MEMORIES TO CHERISH' and month(c.collection_date_time) = $month group by ps.student_entry_level, s.id) ab )abc ";

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
			  
					$result_child=mysqli_query($connection, $save_sql);
				
				// end Memories to Cherish

				// start Q-dees Bag

					$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and DATE_FORMAT(s.start_date_at_centre, '%Y-%m') = '".$monthyear."' and s.centre_code='$centre_code' and s.deleted='0' and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

					$sql_price="SELECT *, BIN(`deleted` + 0) AS `deleted`, unit_price as fees
					FROM `product`
					WHERE `product_name` LIKE '%Q-dees Back Pack Bag%'
					LIMIT 1";

					$result_price=mysqli_query($connection, $sql_price);
					$row_price=mysqli_fetch_assoc($result_price);

					$result_student=mysqli_query($connection, $sql_student);
					$active_student = 0;
					$fee_rate = 0;
					while ($row_student=mysqli_fetch_assoc($result_student)) {
						$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						$fee_rate = (empty($row_price["fees"]) ? "0" : $row_price["fees"]); 
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
					
					$new_price_condition = "and fl.`uniform_adjust` = '98'";

					$student_id_list=mysqli_query($connection,"SELECT s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and fl.`uniform_adjust` != '98' and ps.student_entry_level != '' and s.student_status = 'A' and DATE_FORMAT(s.start_date_at_centre, '%Y-%m') = '".$monthyear."' and s.centre_code='$centre_code' and s.deleted='0' and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id");

					if(mysqli_num_rows($student_id_list) > 0) 
					{
						$student_id_array = array();

						while($student_id_row = mysqli_fetch_array($student_id_list))
						{
							$student_id_array[] = $student_id_row['id'];
						}
						
						$new_price_condition .= " and s.id NOT IN (" . implode(", ", $student_id_array) . ")";
					}

					//$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ".$new_price_condition." ps.student_entry_level != '' and s.student_status = 'A' and DATE_FORMAT(s.start_date_at_centre, '%Y-%m') = '".$monthyear."' and s.centre_code='$centre_code' and s.deleted='0' and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

					$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where c.void='0' and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') ".$new_price_condition." and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and c.product_code='Uniform (2 sets)' and DATE_FORMAT(c.collection_date_time, '%Y-%m') = '$monthyear' group by ps.student_entry_level, s.id) ab";

					$sql_price="SELECT *, BIN(`deleted` + 0) AS `deleted`, unit_price as fees
					FROM `product`
					WHERE `product_name` LIKE '%Uniform%'
					LIMIT 1";

					$result_price=mysqli_query($connection, $sql_price);
					$row_price=mysqli_fetch_assoc($result_price);

					$result_student=mysqli_query($connection, $sql_student);
					$active_student = 0;
					$fee_rate = 0;
					while ($row_student=mysqli_fetch_assoc($result_student)) {
						$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						$fee_rate = (empty($row_price["fees"]) ? "0" : $row_price["fees"]); ; 
					}

					$fee_rate = 98;

					$form="Form1";
					$fee_structure_mame="Uniform";
					$amount=$active_student * $fee_rate;
					$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', $active_student, $fee_rate, $amount )";
				
					$result_child=mysqli_query($connection, $save_sql);



					$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and fl.`uniform_adjust` != '98' and  ps.student_entry_level != '' and s.student_status = 'A' and DATE_FORMAT(s.start_date_at_centre, '%Y-%m') = '".$monthyear."' and s.centre_code='$centre_code' and s.deleted='0' and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

					$sql_price="SELECT *, BIN(`deleted` + 0) AS `deleted`, unit_price as fees
					FROM `product`
					WHERE `product_name` LIKE '%Uniform%'
					LIMIT 1";

					$result_price=mysqli_query($connection, $sql_price);
					$row_price=mysqli_fetch_assoc($result_price);

					$result_student=mysqli_query($connection, $sql_student);
					$active_student = 0;
					$fee_rate = 0;
					while ($row_student=mysqli_fetch_assoc($result_student)) {
						$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						$fee_rate = (empty($row_price["fees"]) ? "0" : $row_price["fees"]); ; 
					}

					$fee_rate = 86;

					$form="Form1";
					$fee_structure_mame="Uniform (Old Price)";
					$amount=$active_student * $fee_rate;
					$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', $active_student, $fee_rate, $amount )";
				
					$result_child=mysqli_query($connection, $save_sql);
				
				// end Uniform

				// start Uniform products
					
					$sql_student ="SELECT product_name, round(sum(qty), 0) level_count, fees from (SELECT p.product_name, c.qty, c.unit_price as fees from student s inner join `collection` c on c.student_id = s.id inner join product p on p.product_code=c.product_code where c.void='0' and c.year = '$year' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and 
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
					  
						$result_child=mysqli_query($connection, $save_sql);
					
					}
				// end Uniform products

				// start Gymwear
					
					$new_price_condition = "and fl.`gymwear_adjust` = '37' "; 

					$student_id_list=mysqli_query($connection,"SELECT s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and fl.`gymwear_adjust` != '37' and ps.student_entry_level != '' and s.student_status = 'A' and DATE_FORMAT(s.start_date_at_centre, '%Y-%m') = '".$monthyear."' and s.centre_code='$centre_code' and s.deleted='0' and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id");
					
					if(mysqli_num_rows($student_id_list) > 0) 
					{
						$student_id_array = array();

						while($student_id_row = mysqli_fetch_array($student_id_list))
						{
							$student_id_array[] = $student_id_row['id'];
						}
						
						$new_price_condition .= " and s.id NOT IN (" . implode(", ", $student_id_array) . ")";
					}

					//$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') ".$new_price_condition." and ps.student_entry_level != '' and s.student_status = 'A' and DATE_FORMAT(s.start_date_at_centre, '%Y-%m') = '".$monthyear."' and s.centre_code='$centre_code' and s.deleted='0' and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";
				
					$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where c.void='0' and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') ".$new_price_condition." and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and c.product_code='Gymwear (1 set)' and DATE_FORMAT(c.collection_date_time, '%Y-%m') = '$monthyear' group by ps.student_entry_level, s.id) ab";

					$sql_price="SELECT *, BIN(`deleted` + 0) AS `deleted`, unit_price as fees
					FROM `product`
					WHERE `product_name` LIKE '%Gymwear%'
					LIMIT 1";

					$result_price=mysqli_query($connection, $sql_price);
					$row_price=mysqli_fetch_assoc($result_price);

					$result_student=mysqli_query($connection, $sql_student);
					$active_student = 0;
					$fee_rate = 0;
					while ($row_student=mysqli_fetch_assoc($result_student)) {
						$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						$fee_rate = (empty($row_price["fees"]) ? "0" : $row_price["fees"]); 
					}

					$form="Form1";
					$fee_structure_mame="Gymwear";

					$fee_rate = 37;

					$amount=$active_student * $fee_rate;
					
					$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', $active_student, $fee_rate, $amount )";
				 
					$result_child=mysqli_query($connection, $save_sql);

					$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and fl.`gymwear_adjust` != '37' and ps.student_entry_level != '' and s.student_status = 'A' and DATE_FORMAT(s.start_date_at_centre, '%Y-%m') = '".$monthyear."' and s.centre_code='$centre_code' and s.deleted='0' and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";
				
					$sql_price="SELECT *, BIN(`deleted` + 0) AS `deleted`, unit_price as fees
					FROM `product`
					WHERE `product_name` LIKE '%Gymwear%'
					LIMIT 1";

					$result_price=mysqli_query($connection, $sql_price);
					$row_price=mysqli_fetch_assoc($result_price);

					$result_student=mysqli_query($connection, $sql_student);
					$active_student = 0;
					$fee_rate = 0;
					while ($row_student=mysqli_fetch_assoc($result_student)) {
						$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						$fee_rate = (empty($row_price["fees"]) ? "0" : $row_price["fees"]); 
					}

					$form="Form1";
					$fee_structure_mame="Gymwear (Old Price)";

					$fee_rate = 33;

					$amount=$active_student * $fee_rate;
					
					$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', $active_student, $fee_rate, $amount )";
				 
					$result_child=mysqli_query($connection, $save_sql);
				// end Gymwear

				// start Gymwear products
					
					$sql_student ="SELECT product_name, round(sum(qty), 0) level_count, fees from (SELECT p.product_name, c.qty, c.unit_price as fees from student s inner join `collection` c on c.student_id = s.id inner join product p on p.product_code=c.product_code where c.void='0' and c.year = '$year' and  and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and p.product_name like 'Q-dees Gymwear%' and month(c.collection_date_time) = $month group by c.id, c.product_code) ab group by product_name ORDER BY FIELD(product_name,'Q-dees Gymwear - XS','Q-dees Gymwear - S','Q-dees Gymwear - M','Q-dees Gymwear - L','Q-dees Gymwear - XL','Q-dees Gymwear - XXL','Q-dees Gymwear - XXXL','Q-dees Gymwear - Special Make','Q-dees Gymwear T-Shirt - XS','Q-dees Gymwear T-Shirt - S','Q-dees Gymwear T-Shirt - M','Q-dees Gymwear T-Shirt - L','Q-dees Gymwear T-Shirt - XL')";

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
					 
						$result_child=mysqli_query($connection, $save_sql);
					
					}
					
				// end Gymwear products

				// start Software License Fee-EDP
					
					$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m')  group by ps.student_entry_level, s.id) ab";

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
			
					$result_child=mysqli_query($connection, $save_sql);
				
				// end Software License Fee-EDP

				// start Software License Fee-QF1, QF2, QF3
					
					$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level  in ('QF1','QF2','QF3') and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

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
				
					$result_child=mysqli_query($connection, $save_sql);
				
				// end Software License Fee-QF1, QF2, QF3

				// start Q-dees Mobile Apps–EDP, QF1, QF2, QF3
					
					$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level  in ('EDP','QF1','QF2','QF3') and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

					$result_student=mysqli_query($connection, $sql_student);
					$active_student = 0;
					$fee_rate = 0;
					while ($row_student=mysqli_fetch_assoc($result_student)) {
						$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						$fee_rate = '11.25'; 
					}

					$active_student = 0;

					$form="Form1";
					$fee_structure_mame="Q-dees Mobile Apps–EDP, QF1, QF2, QF3";
					$amount=number_format((float)$active_student*11.25, 2, '.', '');
					$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', $active_student, $fee_rate, $amount )";
				
					$result_child=mysqli_query($connection, $save_sql);
				
				// end Q-dees Mobile Apps–EDP, QF1, QF2, QF3

				// start International English EDP
					$sql_adjust="SELECT fees_structure, ROUND(case 
						when enhanced_collection = 'Termly' then  enhanced_adjust/3 
						when enhanced_collection = 'Half Year' then  enhanced_adjust/6 
						when enhanced_collection = 'Annually' then  enhanced_adjust/12
						else enhanced_adjust end, 2) as enhanced_adjust
						from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
					$result_adjust=mysqli_query($connection, $sql_adjust);
					$num_row_adjust = mysqli_num_rows($result_adjust);
					if ($num_row_adjust>0) {
						while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

							$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."'  and fl.foundation_int_english=1 and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

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
							$result_child=mysqli_query($connection, $save_sql);
						}
					}
				// end International English EDP

				// start International English QF1
					$sql_adjust="SELECT fees_structure, ROUND(case 
						when enhanced_collection = 'Termly' then  enhanced_adjust/3 
						when enhanced_collection = 'Half Year' then  enhanced_adjust/6 
						when enhanced_collection = 'Annually' then  enhanced_adjust/12
						else enhanced_adjust end, 2) as enhanced_adjust
						from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
					$result_adjust=mysqli_query($connection, $sql_adjust);
					$num_row_adjust = mysqli_num_rows($result_adjust);
					if ($num_row_adjust>0) {
						while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

							$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

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
						when enhanced_collection = 'Termly' then  enhanced_adjust/3 
						when enhanced_collection = 'Half Year' then  enhanced_adjust/6 
						when enhanced_collection = 'Annually' then  enhanced_adjust/12
						else enhanced_adjust end, 2) as enhanced_adjust
						from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
					$result_adjust=mysqli_query($connection, $sql_adjust);
					$num_row_adjust = mysqli_num_rows($result_adjust);
					if ($num_row_adjust>0) {
						while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

							$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

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
						when enhanced_collection = 'Termly' then  enhanced_adjust/3 
						when enhanced_collection = 'Half Year' then  enhanced_adjust/6 
						when enhanced_collection = 'Annually' then  enhanced_adjust/12
						else enhanced_adjust end, 2) as enhanced_adjust
						from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
					$result_adjust=mysqli_query($connection, $sql_adjust);
					$num_row_adjust = mysqli_num_rows($result_adjust);
					if ($num_row_adjust>0) {
						while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

							$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

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
							$result_child=mysqli_query($connection, $save_sql);
						}
					}
				// end International English QF3

				// start International Art EDP
					$sql_adjust="SELECT fees_structure, ROUND(case 
					when international_collection = 'Termly' then  international_adjust/3 
					when international_collection = 'Half Year' then  international_adjust/6 
					when international_collection = 'Annually' then  international_adjust/12
					else international_adjust end, 2) as international_adjust
					from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
					$result_adjust=mysqli_query($connection, $sql_adjust);
					$num_row_adjust = mysqli_num_rows($result_adjust);
					if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and fl.foundation_int_art=1 and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

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
					when international_collection = 'Termly' then  international_adjust/3 
					when international_collection = 'Half Year' then  international_adjust/6 
					when international_collection = 'Annually' then  international_adjust/12
					else international_adjust end, 2) as international_adjust
					from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
					$result_adjust=mysqli_query($connection, $sql_adjust);
					$num_row_adjust = mysqli_num_rows($result_adjust);
					if ($num_row_adjust>0) {
						while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

							$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and fl.foundation_int_art=1 and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

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
							$result_child=mysqli_query($connection, $save_sql);
						}
					}
			// end International Art QF1

			// start International Art QF2
				$sql_adjust="SELECT fees_structure, ROUND(case 
					when international_collection = 'Termly' then  international_adjust/3 
					when international_collection = 'Half Year' then  international_adjust/6 
					when international_collections = 'Annually' then  international_adjust/12
					else international_adjust end, 2) as international_adjust
					from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
					$result_adjust=mysqli_query($connection, $sql_adjust);
					$num_row_adjust = mysqli_num_rows($result_adjust);
					if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and fl.foundation_int_art=1 and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

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
						$result_child=mysqli_query($connection, $save_sql);
					}
				}
			// end International Art QF2

			// start International Art QF3
				$sql_adjust="SELECT fees_structure, ROUND(case 
					when international_collection = 'Termly' then  international_adjust/3 
					when international_collection = 'Half Year' then  international_adjust/6 
					when international_collection = 'Annually' then  international_adjust/12
					else international_adjust end, 2) as international_adjust
					from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and fl.foundation_int_art=1 and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

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
						$result_child=mysqli_query($connection, $save_sql);
					}
				}
			// end International Art QF3

			// start Mandarin EDP
				$sql_adjust="SELECT fees_structure, ROUND(case 
				when mandarin_collection = 'Termly' then  mandarin_adjust/3 
				when mandarin_collection = 'Half Year' then  mandarin_adjust/6 
				when mandarin_collection = 'Annually' then  mandarin_adjust/12
				else mandarin_adjust end, 2) as mandarin_adjust
				from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and fl.foundation_int_mandarin=1 and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

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
					
						$result_child=mysqli_query($connection, $save_sql);
					}
				}
			// end Mandarin EDP

			// start Mandarin QF1
				$sql_adjust="SELECT fees_structure, ROUND(case 
					when mandarin_collection = 'Termly' then  mandarin_adjust/3 
					when mandarin_collection = 'Half Year' then  mandarin_adjust/6 
					when mandarin_collection = 'Annually' then  mandarin_adjust/12
					else mandarin_adjust end, 2) as mandarin_adjust
					from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
					$result_adjust=mysqli_query($connection, $sql_adjust);
					$num_row_adjust = mysqli_num_rows($result_adjust);
					if ($num_row_adjust>0) {
						while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

							$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and fl.foundation_int_mandarin=1 and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

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
					when mandarin_collection = 'Termly' then  mandarin_adjust/3 
					when mandarin_collection = 'Half Year' then  mandarin_adjust/6 
					when mandarin_collection = 'Annually' then  mandarin_adjust/12
					else mandarin_adjust end, 2) as mandarin_adjust
					from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
					$result_adjust=mysqli_query($connection, $sql_adjust);
					$num_row_adjust = mysqli_num_rows($result_adjust);
					if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and fl.foundation_int_mandarin=1 and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

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
						$result_child=mysqli_query($connection, $save_sql);
					}
				}
			// end Mandarin QF2

			// start Mandarin QF3
				$sql_adjust="SELECT fees_structure, ROUND(case 
					when mandarin_collection = 'Termly' then  mandarin_adjust/3 
					when mandarin_collection = 'Half Year' then  mandarin_adjust/6 
					when mandarin_collection = 'Annually' then  mandarin_adjust/12
					else mandarin_adjust end, 2) as mandarin_adjust
					from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and fl.foundation_int_mandarin=1 and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

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
						$result_child=mysqli_query($connection, $save_sql);
					}
				}
			// end Mandarin QF3

			// start IQ Math EDP
				$sql_adjust="SELECT fees_structure, ROUND(case 
				when iq_math_collection = 'Termly' then  iq_math_adjust/3 
				when iq_math_collection = 'Half Year' then  iq_math_adjust/6 
				when iq_math_collection = 'Annually' then  iq_math_adjust/12
				else iq_math_adjust end, 2) as iq_math_adjust
				from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and fl.foundation_iq_math=1 and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

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
				
						$result_child=mysqli_query($connection, $save_sql);
					}
				}
				// end IQ Math EDP

				// start IQ Math QF1
					$sql_adjust="SELECT fees_structure, ROUND(case 
						when iq_math_collection = 'Termly' then  iq_math_adjust/3 
						when iq_math_collection = 'Half Year' then  iq_math_adjust/6 
						when iq_math_collection = 'Annually' then  iq_math_adjust/12
						else iq_math_adjust end, 2) as iq_math_adjust
						from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
						$result_adjust=mysqli_query($connection, $sql_adjust);
						$num_row_adjust = mysqli_num_rows($result_adjust);
						if ($num_row_adjust>0) {
							while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

								$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and fl.foundation_iq_math=1 and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

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
								$result_child=mysqli_query($connection, $save_sql);
							}
						}
				// end IQ Math QF1

				// start IQ Math QF2
					$sql_adjust="SELECT fees_structure, ROUND(case 
						when iq_math_collection = 'Termly' then  iq_math_adjust/3 
						when iq_math_collection = 'Half Year' then  iq_math_adjust/6 
						when iq_math_collection = 'Annually' then  iq_math_adjust/12
						else iq_math_adjust end, 2) as iq_math_adjust
						from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
						$result_adjust=mysqli_query($connection, $sql_adjust);
						$num_row_adjust = mysqli_num_rows($result_adjust);
						if ($num_row_adjust>0) {
						while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

							$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and fl.foundation_iq_math=1 and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

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
							$result_child=mysqli_query($connection, $save_sql);
						}
					}
				// end IQ Math QF2

				// start IQ Math QF3
					$sql_adjust="SELECT fees_structure, ROUND(case 
						when iq_math_collection = 'Termly' then  iq_math_adjust/3 
						when iq_math_collection = 'Half Year' then  iq_math_adjust/6 
						when iq_math_collection = 'Annually' then  iq_math_adjust/12
						else iq_math_adjust end, 2) as iq_math_adjust
						from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
					$result_adjust=mysqli_query($connection, $sql_adjust);
					$num_row_adjust = mysqli_num_rows($result_adjust);
					if ($num_row_adjust>0) {
						while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

							$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and fl.foundation_iq_math=1 and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

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
							$result_child=mysqli_query($connection, $save_sql);
						}
					}
				// end IQ Math QF3



				// start Robotic Plus EDP
				$sql_adjust="SELECT fees_structure, ROUND(case 
				when robotic_plus_collection = 'Termly' then  robotic_plus_adjust/3 
				when robotic_plus_collection = 'Half Year' then  robotic_plus_adjust/6 
				when robotic_plus_collection = 'Annually' then  robotic_plus_adjust/12
				else robotic_plus_adjust end, 2) as robotic_plus_adjust
				from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";

				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
					while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

						$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and fl.robotic_plus=1 and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

						$result_student=mysqli_query($connection, $sql_student);
						$active_student = 0;
						while ($row_student=mysqli_fetch_assoc($result_student)) {
							$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
						}

						$form="Form2";
						$fee_structure_mame="Robotic Plus";
						$subject="EDP";
						$programme_package=$row_adjust['fees_structure'];
						$active_student=$active_student;
						$fee_rate=$row_adjust['robotic_plus_adjust'];
						$amount=$active_student * $fee_rate;
						$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
				
						$result_child=mysqli_query($connection, $save_sql);
					}
				}
				// end Robotic Plus EDP

				// start Robotic Plus QF1

					$sql_adjust="SELECT fees_structure, ROUND(case 
					when robotic_plus_collection = 'Termly' then  robotic_plus_adjust/3 
					when robotic_plus_collection = 'Half Year' then  robotic_plus_adjust/6 
					when robotic_plus_collection = 'Annually' then  robotic_plus_adjust/12
					else robotic_plus_adjust end, 2) as robotic_plus_adjust
					from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";

						$result_adjust=mysqli_query($connection, $sql_adjust);
						$num_row_adjust = mysqli_num_rows($result_adjust);
						if ($num_row_adjust>0) {
							while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

								$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and fl.robotic_plus=1 and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

								$result_student=mysqli_query($connection, $sql_student);
								$active_student = 0;
								while ($row_student=mysqli_fetch_assoc($result_student)) {
									$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
								}

								$form="Form2";
								$fee_structure_mame="Robotic Plus";
								$subject="QF1";
								$programme_package=$row_adjust['fees_structure'];
								$active_student=$active_student;
								$fee_rate=$row_adjust['robotic_plus_adjust'];
								$amount=$active_student * $fee_rate;
								$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )"; 
								$result_child=mysqli_query($connection, $save_sql);
							}
						}
				// end Robotic Plus QF1

				// start Robotic Plus QF2
					$sql_adjust="SELECT fees_structure, ROUND(case 
					when robotic_plus_collection = 'Termly' then  robotic_plus_adjust/3 
					when robotic_plus_collection = 'Half Year' then  robotic_plus_adjust/6 
					when robotic_plus_collection = 'Annually' then  robotic_plus_adjust/12
					else robotic_plus_adjust end, 2) as robotic_plus_adjust
					from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";

						$result_adjust=mysqli_query($connection, $sql_adjust);
						$num_row_adjust = mysqli_num_rows($result_adjust);
						if ($num_row_adjust>0) {
						while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

							$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and fl.robotic_plus=1 and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

							$result_student=mysqli_query($connection, $sql_student);
							$active_student = 0;
							while ($row_student=mysqli_fetch_assoc($result_student)) {
								$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
							}

							$form="Form2";
							$fee_structure_mame="Robotic Plus";
							$subject="QF2";
							$programme_package=$row_adjust['fees_structure'];
							$active_student=$active_student;
							$fee_rate=$row_adjust['robotic_plus_adjust'];
							$amount=$active_student * $fee_rate;
							$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
							$result_child=mysqli_query($connection, $save_sql);
						}
					}
				// end Robotic Plus QF2

				// start Robotic Plus QF3
					$sql_adjust="SELECT fees_structure, ROUND(case 
					when robotic_plus_collection = 'Termly' then  robotic_plus_adjust/3 
					when robotic_plus_collection = 'Half Year' then  robotic_plus_adjust/6 
					when robotic_plus_collection = 'Annually' then  robotic_plus_adjust/12
					else robotic_plus_adjust end, 2) as robotic_plus_adjust
					from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";

					$result_adjust=mysqli_query($connection, $sql_adjust);
					$num_row_adjust = mysqli_num_rows($result_adjust);
					if ($num_row_adjust>0) {
						while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

							$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and fl.robotic_plus=1 and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

							$result_student=mysqli_query($connection, $sql_student);
							$active_student = 0;
							while ($row_student=mysqli_fetch_assoc($result_student)) {
								$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
							}

							$form="Form2";
							$fee_structure_mame="Robotic Plus";
							$subject="QF3";
							$programme_package=$row_adjust['fees_structure'];
							$active_student=$active_student;
							$fee_rate=$row_adjust['robotic_plus_adjust'];
							$amount=$active_student * $fee_rate;
							$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
							$result_child=mysqli_query($connection, $save_sql);
						}
					}
				// end Robotic Plus QF3





			// start Link & Think Series EDP
				$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
				$result_adjust=mysqli_query($connection, $sql_adjust);
				$num_row_adjust = mysqli_num_rows($result_adjust);
				if ($num_row_adjust>0) {
				while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {
					
					$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and case when f.link_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
					when f.link_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
					when f.link_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
					when f.link_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
					end group by ps.student_entry_level, s.id) ab";

					$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and case when f.integrated_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
					when f.integrated_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
					when f.integrated_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
					when f.integrated_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
					end group by ps.student_entry_level, s.id) ab";

					$result_student=mysqli_query($connection, $sql_student);
					$active_student = 0;
					while ($row_student=mysqli_fetch_assoc($result_student)) {
						$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
					}
					$row_adjust['link_adjust'] = 60;
					$form="Form2";
					$fee_structure_mame="Link & Think Series";
					$subject="EDP";
					$programme_package=$row_adjust['fees_structure'];
					$active_student=$active_student;
					$fee_rate=$row_adjust['link_adjust'];
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

								$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and case when f.link_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                when f.link_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.link_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.link_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                end group by ps.student_entry_level, s.id) ab";

 								$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and case when f.integrated_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                when f.integrated_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.integrated_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.integrated_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                end group by ps.student_entry_level, s.id) ab";

							$result_student=mysqli_query($connection, $sql_student);
							$active_student = 0;
							while ($row_student=mysqli_fetch_assoc($result_student)) {
								$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
							}
							$row_adjust['link_adjust'] = 60;
							$form="Form2";
							$fee_structure_mame="Link & Think Series";
							$subject="QF1";
							$programme_package=$row_adjust['fees_structure'];
							$active_student=$active_student;
							$fee_rate=$row_adjust['link_adjust'];
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

							$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and case when f.link_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
							when f.link_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
							when f.link_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
							when f.link_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
							end group by ps.student_entry_level, s.id) ab";

							$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and case when f.integrated_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
							when f.integrated_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
							when f.integrated_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
							when f.integrated_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
							end group by ps.student_entry_level, s.id) ab";

							$result_student=mysqli_query($connection, $sql_student);
							$active_student = 0;
							while ($row_student=mysqli_fetch_assoc($result_student)) {
								$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
							}
							$row_adjust['link_adjust'] = 60;
							$form="Form2";
							$fee_structure_mame="Link & Think Series";
							$subject="QF2";
							$programme_package=$row_adjust['fees_structure'];
							$active_student=$active_student;
							$fee_rate=$row_adjust['link_adjust'];
							$amount=$active_student * $fee_rate;
							$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
							$result_child=mysqli_query($connection, $save_sql);
						}
					}
				// end Link & Think Series QF2

				// start Link & Think Series QF3
					$sql_adjust="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
					$result_adjust=mysqli_query($connection, $sql_adjust);
					$num_row_adjust = mysqli_num_rows($result_adjust);
					if ($num_row_adjust>0) {
						while ($row_adjust=mysqli_fetch_assoc($result_adjust)) {

							$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and case when f.link_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
							when f.link_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
							when f.link_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
							when f.link_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
							end group by ps.student_entry_level, s.id) ab";

							$sql_student="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row_adjust['fees_structure'])."' and case when f.integrated_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
							when f.integrated_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
							when f.integrated_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
							when f.integrated_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
							end group by ps.student_entry_level, s.id) ab";	

							$result_student=mysqli_query($connection, $sql_student);
							$active_student = 0;
							while ($row_student=mysqli_fetch_assoc($result_student)) {
								$active_student = (empty($row_student["level_count"]) ? "0" : $row_student["level_count"]); 
							}
							$row_adjust['link_adjust'] = 60;
							$form="Form2";
							$fee_structure_mame="Link & Think Series";
							$subject="QF3";
							$programme_package=$row_adjust['fees_structure'];
							$active_student=$active_student;
							$fee_rate=$row_adjust['link_adjust'];
							$amount=$active_student * $fee_rate;
							$save_sql="INSERT INTO  declaration_child ( master_id, form, fee_structure_mame, subject, programme_package, active_student, fee_rate, amount ) VALUES ( $master_id, '$form', '$fee_structure_mame', '$subject', '$programme_package', $active_student, $fee_rate, $amount )";
							//echo $save_sql;	//die;  
							$result_child=mysqli_query($connection, $save_sql);
							//echo $result_child;
						}
					}
				// end Link & Think Series QF3

				// start Foundation e-Reader
						
					$sql_student="SELECT count(id) level_count, fees from (SELECT s.id, p.unit_price as fees from student s inner join `collection` c on c.student_id = s.id inner join product p on p.product_code=c.product_code where c.void='0' and c.year = '$year' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and p.product_name like '%e-Reader Nation%' and month(c.collection_date_time) = $month group by s.id) ab";

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
					$result_child=mysqli_query($connection, $save_sql);
					
					$nm = date('d-M-Y')."-".$centre_code."-".time().".pdf";

					mysqli_query($connection,"UPDATE `declaration` SET `declaration_pdf` = '".$nm."' WHERE `id` = '".$master_id."'");

					$purl = "https://starters.q-dees.com/admin/declaration_pdf.php?declaration_id=".$master_id."&CentreCode=".$centre_code."&monthyear=".$monthyear;
					exec("export PATH='/usr/bin:/bin' && wkhtmltopdf '$purl' /var/www/html/starters.q-dees.com/admin/declaration_pdf/$nm", $output);

					//print_r($output);
					//exit;

					// end Foundation e-Reader
					echo $submited_date."-".$centre_code."-".$master_id."-Record Added \n";
			}
		}else{
			echo $submited_date."-".$centre_code."-Record Exists \n";
		}
	} 
}

?>