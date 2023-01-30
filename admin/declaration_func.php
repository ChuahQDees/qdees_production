<?php
//phpinfo();
session_start();
$msg="";

if( !empty($_FILES["attachment_1_centre"]) && ! empty($_FILES["attachment_1_centre"]["tmp_name"]) ){
validateAttachment("attachment_1_centre");
$tmp_document=$_FILES["attachment_1_centre"]["tmp_name"];

$file_extn = explode(".", strtolower($_FILES["attachment_1_centre"]["name"]))[1];
//$check = getimagesize($_FILES["attachment_1_centre"]["tmp_name"]);
//if($check !== false) {

	$attachment_1_centre_filename=generateRandomString(8).".$file_extn";
	copy($tmp_document, 'admin/uploads/'.$attachment_1_centre_filename);
//}
//echo $fattachment_1_centre_filename; die;
}

if( !empty($_FILES["attachment_2_centre"]) && ! empty($_FILES["attachment_2_centre"]["tmp_name"]) ){
validateAttachment("attachment_2_centre");
$tmp_document=$_FILES["attachment_2_centre"]["tmp_name"];
$file_extn = explode(".", strtolower($_FILES["attachment_2_centre"]["name"]))[1];
//$check = getimagesize($_FILES["attachment_2_centre"]["tmp_name"]);
//if($check !== false) {
	$attachment_2_centre_filename=generateRandomString(8).".$file_extn";
	copy($tmp_document, 'admin/uploads/'.$attachment_2_centre_filename);
//}
}

foreach ($_POST as $key=>$value) { //print_r($active_student);die;
	$$key=$value;
 }


$submited_date= date("Y-m-d H:i:s");
//$year= date("Y");
$year=$_SESSION['Year'];
$month= date("m");
$mode=$_GET["mode"];
$sha1_id=$_GET["id"];
$centre_code = $_SESSION["CentreCode"];
$submited_by = $_SESSION["UserName"];

function validateAttachment($attachment){

	if( !empty($_FILES["$attachment"]) && ! empty($_FILES["$attachment"]["tmp_name"]) ){

	  if ($_FILES["$attachment"]["size"] > 10485760) {
		throw new Exception("Attachment file size exceeded 10MB");
	  }
  
	  //mime
	  $finfo = new finfo();
	  $type = $finfo->file($_FILES["$attachment"]["tmp_name"], FILEINFO_MIME_TYPE);

	  switch ($type) {
		case 'image/jpg':
		case 'image/jpeg':
		case 'image/png':
		case 'application/pdf':
		case 'application/msword':
		case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
		  break;
		default:
		  throw new Exception("Upload .jpg, .png, .doc and .pdf file only. ($type)");
		  break;
	  }
	}
  }

if (isset($mode) and $mode=="SAVE") {
	
	//$declaration=mysqli_real_escape_string($connection, $_POST['declaration']);
	 if(isset($fee_structure_mame,$subject,$programme_package,$programme_package)) {

		
		$signature_1 = '';
		$signature_2 = '';
		$remarks_centre_1 = '';
		$remarks_centre_2 = '';
		$remarks_master_1 = '';
		$remarks_master_2 = '';
		$attachment_1_centre_filename = '';
		$attachment_2_centre_filename = '';

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
	
		$master_id = $connection->insert_id;

		$save_sql = "UPDATE  declaration_doc set declaration_id = '".$master_id."' WHERE  declaration_id = '".$_SESSION["temp_id"]."'";
		$result=mysqli_query($connection, $save_sql);
		$_SESSION["temp_id"]='';
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
		

			$result=mysqli_query($connection, $save_sql);
		}
		
		$nm = date('d-M-Y')."-".$centre_code."-".time().".pdf";

		mysqli_query($connection,"UPDATE `declaration` SET `declaration_pdf` = '".$nm."' WHERE `id` = '".$master_id."'");

		$purl = "http://178.128.87.243/admin/declaration_pdf.php?declaration_id=".$master_id."&CentreCode=".$centre_code."&monthyear=".$monthyear;
		
		exec("export PATH='/usr/bin:/bin' && wkhtmltopdf '$purl' /var/www/html/admin/declaration_pdf/$nm", $output);

  if($result){
	  //echo "<script type='text/javascript'>window.top.location='index.php?p=declaration_setting&msg=Record saved';</script>";
	  //$msg="Record saved";
	  $msg="Your respond has been submitted successfully";
  }else{
	  $msg="Failed to save data";
  }
	 }
}

if (isset($mode) and $mode=="EDIT") {
	
	//$declaration=mysqli_real_escape_string($connection, $_POST['declaration']);
	 if(isset($fee_structure_mame,$subject,$programme_package,$programme_package)) {

		$signature_1 = isset($signature_1) ? $signature_1 : '';
		$signature_2 = isset($signature_2) ? $signature_2 : '';
		$remarks_centre_1 = isset($remarks_centre_1) ? $remarks_centre_1 : '';
		$remarks_centre_2 = isset($remarks_centre_2) ? $remarks_centre_2 : '';
		$remarks_master_1 = isset($remarks_master_1) ? $remarks_master_1 : '';
		$remarks_master_2 = isset($remarks_master_2) ? $remarks_master_2 : '';

		$save_sql = "UPDATE  declaration set
			remarks_centre_1 = '$remarks_centre_1',
			remarks_centre_2 = '$remarks_centre_2',
			update_date = '$submited_date',
			update_by = '$submited_by', ";
		if($attachment_1_centre_filename!=""){
			$save_sql .= "attachment_1_centre = '$attachment_1_centre_filename', ";
		}
		if($attachment_2_centre_filename!=""){
			$save_sql .= "attachment_2_centre = '$attachment_2_centre_filename', ";
		}
		if(isset($signature_1)){
			$save_sql .= "signature_1 = '$signature_1', ";
		}
		$save_sql .= "signature_2 = '$signature_2'
			where sha1(id) = '$sha1_id'
			"; 

		$result = mysqli_query($connection, $save_sql); 

  if($result){
	
	  $msg="Your respond has been updated successfully";
  }else{
	  $msg="Failed to update data";
  }
	 }
}

	  
	  $get_sha1_id=$_GET['id'];
	  if (isset($mode) and $mode=="DEL") {
		$sql="DELETE from `declaration_child` where sha1(master_id)='$get_sha1_id'";
		$result=mysqli_query($connection, $sql);
		//echo $sql; die;
		  $sql="DELETE from `declaration` where sha1(id)='$get_sha1_id'";
			$result=mysqli_query($connection, $sql);
	
		if($result){
			//echo "<script type='text/javascript'>window.top.location='index.php?p=declaration_setting&msg=Record saved';</script>";
			$msg="Record deleted";
		}else{
			$msg="Failed to save data";
		}
		   
	   }
	  
	//   $edit_sql="SELECT * from `declaration` where sha1(id)='$get_sha1_id'";
	 
    //   $result=mysqli_query($connection, $edit_sql);
    //   $edit_row=mysqli_fetch_assoc($result);

?>