<?php
session_start();
$msg="";

if( !empty($_FILES["attachment_1_master"]) && ! empty($_FILES["attachment_1_master"]["tmp_name"]) ){
validateAttachment("attachment_1_master");
$tmp_document=$_FILES["attachment_1_master"]["tmp_name"];
//echo $tmp_document; die;
$file_extn = explode(".", strtolower($_FILES["attachment_1_master"]["name"]))[1];
//$check = getimagesize($_FILES["attachment_1_master"]["tmp_name"]);
//if($check !== false) {
	//echo "hello"; die;
	$attachment_1_master_filename=generateRandomString(8).".$file_extn";
	copy($tmp_document, 'admin/uploads/'.$attachment_1_master_filename);
//}
//echo $fattachment_1_centre_filename; die;
}

if( !empty($_FILES["attachment_2_master"]) && ! empty($_FILES["attachment_2_master"]["tmp_name"]) ){
validateAttachment("attachment_2_master");
$tmp_document=$_FILES["attachment_2_master"]["tmp_name"];
$file_extn = explode(".", strtolower($_FILES["attachment_2_master"]["name"]))[1];
//$check = getimagesize($_FILES["attachment_2_master"]["tmp_name"]);
//if($check !== false) {
	$attachment_2_master_filename=generateRandomString(8).".$file_extn";
	copy($tmp_document, 'admin/uploads/'.$attachment_2_master_filename);
//}
}
	


foreach ($_POST as $key=>$value) { //print_r($active_student);die;
	$$key=$value;
 }

$submited_date= date("Y-m-d H:i:s");

$year=$_SESSION['Year'];
$month= date("m");
$mode=$_GET["mode"];
$sha1_id=$_GET["id"];
$centre_code = $_SESSION["CentreCode"];
$submited_by = $_SESSION["UserName"];

function validateAttachment($attachment){
	if( !empty($_FILES["$attachment"]) && ! empty($_FILES["$attachment"]["tmp_name"]) ){
	  //validate
  
	  //10MB file size limit
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

// 		//$_SESSION["isLogin"]
// if (isset($mode) and $mode=="SAVE") {
	
// 	//$declaration=mysqli_real_escape_string($connection, $_POST['declaration']);
// 	 if(isset($fee_structure_mame,$subject,$programme_package,$programme_package)) {


// 		validateAttachment("attachment_1_centre");
// 		$tmp_document=$_FILES["attachment_1_centre"]["tmp_name"];
// 		$file_extn = explode(".", strtolower($_FILES["attachment_1_centre"]["name"]))[1];
// 		$check = getimagesize($_FILES["attachment_1_centre"]["tmp_name"]);
// 		if($check !== false) {
// 			$attachment_1_centre_filename=generateRandomString(8).".$file_extn";
// 			copy($tmp_document, 'admin/uploads/'.$attachment_1_centre_filename);
// 		}
// 		//echo $fattachment_1_centre_filename; die;
		
// 		validateAttachment("attachment_2_centre");
// 		$tmp_document=$_FILES["attachment_2_centre"]["tmp_name"];
// 		$file_extn = explode(".", strtolower($_FILES["attachment_2_centre"]["name"]))[1];
// 		$check = getimagesize($_FILES["attachment_2_centre"]["tmp_name"]);
// 		if($check !== false) {
// 			$attachment_2_centre_filename=generateRandomString(8).".$file_extn";
// 			copy($tmp_document, 'admin/uploads/'.$attachment_2_centre_filename);
// 		}

// 		$save_sql="INSERT INTO  declaration ( 
// 			year, 
// 			month, 
// 			centre_code,
// 			submited_date,
// 			submited_by,
// 			remarks_centre_1,
// 			remarks_centre_2,
// 			remarks_master_1,
// 			remarks_master_2,
// 			form_1_status,
// 			form_2_status,
// 			signature_1,
// 			signature_2,
// 			attachment_1_centre,
// 			attachment_2_centre
// 		) VALUES (
			
// 			'$year', 
// 			'$month', 
// 			'$centre_code',
// 			'$submited_date',
// 			'$submited_by',
// 			'$remarks_centre_1',
// 			'$remarks_centre_2',
// 			'$remarks_master_1',
// 			'$remarks_master_2',
// 			'$form_1_status',
// 			'$form_2_status',
// 			'$signature_1',
// 			'$signature_2',
// 			'$attachment_1_centre_filename',
// 			'$attachment_2_centre_filename'
// 		)";
// 		// echo $form_1_status;	//die;  

// 		$result = mysqli_query($connection, $save_sql); 
// 		$master_id = $connection->insert_id;

// 		$length = count($active_student);
// 		for ($i = 0; $i < $length; $i++) {
		
// 			$save_sql="INSERT INTO  declaration_child ( 
// 				master_id,
// 				form,
// 				fee_structure_mame,
// 				subject,
// 				programme_package,
// 				active_student,
// 				fee_rate,
// 				amount
// 			) VALUES (
// 				$master_id,
// 				'$form[$i]',
// 				'$fee_structure_mame[$i]',
// 				'$subject[$i]',
// 				'$programme_package[$i]', 
// 				'$active_student[$i]',
// 				'$fee_rate[$i]',
// 				'$amount[$i]'
// 			)";
// 			 //echo $save_sql;	//die;  

// 			$result=mysqli_query($connection, $save_sql);
// 		}
// 		// echo $save_sql;	//
// 		//die; 
//   if($result){
// 	  //echo "<script type='text/javascript'>window.top.location='index.php?p=declaration_setting&msg=Record saved';</script>";
// 	  //$msg="Record saved";
// 	  $msg="Your respond has been submitted successfully";
//   }else{
// 	  $msg="Failed to save data";
//   }
// 	 }
// }

if (isset($mode) and $mode=="EDIT") {
	
	//$declaration=mysqli_real_escape_string($connection, $_POST['declaration']);
	 if(isset($fee_structure_mame,$subject,$programme_package,$programme_package)) {


		//echo $remarks_master_1; die;

		$save_sql = "UPDATE  declaration set
			remarks_master_1 = '$remarks_master_1',
			remarks_master_2 = '$remarks_master_2', ";
		if($attachment_1_master_filename!=""){
			$save_sql .= "attachment_1_master = '$attachment_1_master_filename', ";
		}
		if($attachment_2_master_filename!=""){
			$save_sql .= "attachment_2_master = '$attachment_2_master_filename', ";
		}
		$save_sql .= "form_1_status = '$form_1_status',
		form_2_status = '$form_2_status',
		update_date = '$submited_date',
					update_by = '$submited_by'
				where sha1(id) = '$sha1_id'
			";
		
		 //echo $save_sql;	die;  

		$result = mysqli_query($connection, $save_sql); 
		//$master_id = $connection->insert_id;

		// echo $save_sql;	//
		//die; 
  if($result){
	  //echo "<script type='text/javascript'>window.top.location='index.php?p=declaration_setting&msg=Record saved';</script>";
	  //$msg="Record saved";
	  $msg="Your respond has been updated successfully";
  }else{
	  $msg="Failed to update data";
  }
	 }
}
	// if (isset($mode) and $mode=="EDIT" && $get_dec_id!="") {
	// 	if(isset($_POST['declaration'],$_POST['subject'],$_POST['programme_package'],$_POST['from_date'],$_POST['to_date'],$_POST['school_adjust'])) {
	// 	$declaration=mysqli_real_escape_string($connection, $_POST['declaration']);
	// $save_sql="update  declaration set 
	// 	centre_code='$centre_code',
	// 	declaration='$declaration',
	// 	subject='".$_POST['subject']."',			
	// 	programme_package='".$_POST['programme_package']."',
	// 	from_date='".$_POST['from_date']."',			
	// 	to_date='".$_POST['to_date']."', 
	// 	school_default='".$_POST['school_default']."', 
	// 	school_adjust='".$_POST['school_adjust']."', 
	// 	school_collection='".$_POST['school_collection']."',
		
	// 	mandarin_default_perent='".$_POST['mandarin_default_perent']."',
	// 	international_perent='".$_POST['international_perent']."',
	// 	link_default_perent='".$_POST['link_default_perent']."',
	// 	mandarin_m_default_perent='".$_POST['mandarin_m_default_perent']."',
	// 	basic_default_perent='".$_POST['basic_default_perent']."',
	// 	mobile_perent='".$_POST['mobile_perent']."',
	// 	extend_year='$extend_year',
	// 	submission_date='$time_date',
	// 	status='Pending'";
	// //echo $save_sql;
	
	// 	$save_sql .= "where sha1(id)='$get_dec_id'";
	// //echo $save_sql; die;
	// $result=mysqli_query($connection, $save_sql);
	// if($result){
	// 	//echo "<script type='text/javascript'>window.top.location='index.php?p=fee&msg=Record Updated';</script>";
	// 	$msg="Record Updated";
	// }else{
	// 	$msg="Failed to save data";
	// }
	// }else{
		
	// }
	
	// }
	 
	  
	  $get_sha1_id=$_GET['id'];
	  if (isset($mode) and $mode=="DEL") {
		$sql="DELETE from `declaration_child` where sha1(master_id)='$get_sha1_id'";
		$result=mysqli_query($connection, $sql);
		//echo $sql; die;
		  $sql="DELETE from `declaration` where sha1(id)='$get_sha1_id'";
			$result=mysqli_query($connection, $sql);
			//echo $sql; die;
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