<?php
include_once("mysql.php");
include_once("admin/functions.php");
global $connection;

$submited_date= date("Y-m-d H:i:s");
//$year= date("Y");
$year=$_SESSION['Year'];
$month= date("m");
$mode=$_GET["mode"];
$sha1_id=$_GET["id"];
$centre_code = $_SESSION["CentreCode"];
$submited_by = $_SESSION["UserName"];
		

		$save_sql="INSERT INTO  declaration ( 
			year, 
			month, 
			centre_code,
			submited_date,
			submited_by,
			remarks_centre_1,
			remarks_centre_2,
			remarks_master_1,
			remarks_master_2,
			form_1_status,
			form_2_status,
			signature_1,
			signature_2,
			attachment_1_centre,
			attachment_2_centre
		) VALUES (
			
			'$year', 
			'$month', 
			'$centre_code',
			'$submited_date',
			'$submited_by',
			'$remarks_centre_1',
			'$remarks_centre_2',
			'$remarks_master_1',
			'$remarks_master_2',
			'$form_1_status',
			'$form_2_status',
			'$signature_1',
			'$signature_2',
			'$attachment_1_centre_filename',
			'$attachment_2_centre_filename'
		)";
		// echo $form_1_status;	die;  

		$result = mysqli_query($connection, $save_sql); 
		// var_dump($result);
		$master_id = $connection->insert_id;


		$length = count($active_student);
		for ($i = 0; $i < $length; $i++) {
		
			$save_sql="INSERT INTO  declaration_child ( 
				master_id,
				form,
				fee_structure_mame,
				subject,
				programme_package,
				active_student,
				fee_rate,
				amount
			) VALUES (
				$master_id,
				'$form[$i]',
				'$fee_structure_mame[$i]',
				'$subject[$i]',
				'$programme_package[$i]', 
				'$active_student[$i]',
				'$fee_rate[$i]',
				'$amount[$i]'
			)";
			// echo $save_sql;	die;  

			$result=mysqli_query($connection, $save_sql);
		}
	





?>