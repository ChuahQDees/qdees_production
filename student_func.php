<?php
if( ! defined('STUDENT_PHOTO_URL') ){
  define('STUDENT_PHOTO_URL', '/admin/student_photo/');
}

if( ! defined('STUDENT_PHOTO_PATH') ){
  define('STUDENT_PHOTO_PATH', __DIR__ . '/student_photo/');
}

if( ! defined('STUDENT_ATTACHMENT_URL') ){
  define('STUDENT_ATTACHMENT_URL', '/admin/attachment/');
}


function getStudentID($sid) {
   global $connection;
	
   $sql="SELECT `id` from student where sha1(id)='$sid'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["id"];
}
function getVisitor($sha1_id){
  global $connection;

  $sql="SELECT v.*, vs.*, v.id as vid from visitor v left join visitor_student vs on v.id=vs.visitor_id where sha1(v.id)='$sha1_id' LIMIT 1";
  $result=mysqli_query($connection, $sql);

  if ($result) {
    $row = mysqli_fetch_assoc($result);
  }else{
    $row = array();
  }

  return $row;
}

function generateRandomString1($length) {
   $characters='0123456789abcdefghijklmnopqrstuwxyz';
   $charactersLength=strlen($characters);
   $randomString='';
   for ($i=0; $i<$length; $i++) {
      $randomString.=$characters[rand(0, $charactersLength-1)];
   }
   return $randomString;
}

function isRecordFound($table, $key_field, $key_value) {
   global $connection;

   $sql="SELECT $key_field from `$table` where $key_field='$key_value'";
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      return true;
   } else {
      return false;
   }
}

function parseDOB($day, $month, $year){
  $result = checkDate($month, $day, $year);

  if( $result ){
    try{
      $dateTime = new DateTime($year . '-' . $month . '-' . $day, new DateTimeZone('Asia/Kuala_Lumpur'));
      return $dateTime->format('Y-m-d');
    }catch(Exception $e){
      throw new Exception('Invalid date of birth');
    }
  }else{
    throw new Exception('Invalid date of birth');
  }
}

function resizeStudentPhoto($file_name){
  $source_file_path = STUDENT_PHOTO_PATH . $file_name;

  $source_properties = getimagesize($source_file_path);
  $image_width = $source_properties[0];
  $image_height = $source_properties[1];
  $image_type = $source_properties[2];

  if( $image_type == IMAGETYPE_JPEG ) {
    //medium size
    resizeStudentJpg($source_file_path, $image_width, $image_height, 300, 400);

    //small size
    resizeStudentJpg($source_file_path, $image_width, $image_height, 60, 80);
  }
}

function resizeStudentJpg($source_file_path, $src_width, $src_height, $des_width, $des_height) {
  $file_parts = pathinfo($source_file_path);
  $file_name_without_extention = $file_parts['filename'];
  $destination_file_path = STUDENT_PHOTO_PATH . sprintf('%s_%sx%s.%s', $file_name_without_extention, $des_width, $des_height, 'jpg');

  $src_resource = imagecreatefromjpeg($source_file_path);
  $des_resource = imagecreatetruecolor($des_width, $des_height);

  imagecopyresampled($des_resource, $src_resource, 0, 0, 0, 0, $des_width, $des_height,  $src_width, $src_height);
  imagejpeg($des_resource, $destination_file_path);
}

function validateStudentPhoto(){
  if( !empty($_FILES["image"]) && ! empty($_FILES['image']['tmp_name']) ){
    //validate

    //10MB file size limit
    if ($_FILES['image']['size'] > 10485760) {
      throw new Exception('Photo exceeded size limit of 10MB.');
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if (false === $ext = array_search(
        $finfo->file($_FILES['image']['tmp_name']),
        array('jpg' => 'image/jpeg'),
        true
    )) {
        throw new Exception('Please upload JPG photos only');
    }
  }
}

function processStudentPhoto($table, $student_code){
  global $connection;

  if( !isset($_SESSION["StudentReg"]["photo_file_name"]) && !empty($_FILES["image"]) && ! empty($_FILES['image']['tmp_name']) ){

    $file_name = basename(preg_replace('/[^\da-z]/i', '', strtolower($student_code))) . '_' . uniqid() . '.jpg';
    $upload_file_path = STUDENT_PHOTO_PATH . $file_name;

    while(file_exists($upload_file_path)){
      $file_name = basename(preg_replace('/[^\da-z]/i', '', strtolower($student_code))) . '_' . uniqid() . '.jpg';
      $upload_file_path = STUDENT_PHOTO_PATH . $file_name;
    }

    if( move_uploaded_file($_FILES['image']['tmp_name'], $upload_file_path) ){
      resizeStudentPhoto($file_name);

      $file_parts = pathinfo($file_name);
      $file_name_without_extention = $file_parts['filename'];

      //save to database
      $esc_file_name = mysqli_real_escape_string($connection, $file_name_without_extention);
      $update_sql = "UPDATE `$table` SET photo_file_name = '$esc_file_name' WHERE student_code = '$student_code'";
      $result=mysqli_query($connection, $update_sql);

    };
  }
}

function getStudentPhotoSrc($file_name, $size = 'small'){
  $file_url = '';

  if( $file_name ){
    switch($size){
      case 'medium':
        $size = '300x400';
        break;
      default:
        $size = '60x80';
        break;
    }
    $file_path = STUDENT_PHOTO_PATH . $file_name . '_' . $size . '.jpg';

    if( file_exists($file_path) ):
      $file_url = STUDENT_PHOTO_URL . $file_name . '_' . $size . '.jpg';
    endif;
  }

  return $file_url;
}

function validateAttachment(){
  if( !empty($_FILES["attachment"]) && ! empty($_FILES['attachment']['tmp_name']) ){
    //validate

    //10MB file size limit
    if ($_FILES["attachment"]["size"] > 10485760) {
      throw new Exception("Attachment file size exceeded 10MB");
    }

    //mime
    $finfo = new finfo();
    $type = $finfo->file($_FILES["attachment"]['tmp_name'], FILEINFO_MIME_TYPE);

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

function processAttachment($table, $student_code){
  global $connection;

  if( !isset($_SESSION["StudentReg"]["attachment"]) &&  !empty($_FILES["attachment"]) && ! empty($_FILES['attachment']['tmp_name']) ){

    //move file
    $pathinfo = pathinfo($_FILES["attachment"]["name"]);
    $file_name = basename(preg_replace('/[^\da-z]/i', '', strtolower($pathinfo['filename']))) . '_' . uniqid() . "." . $pathinfo['extension'];
    $upload_file_path = __DIR__ . "/attachment/" . $file_name;

    while(file_exists($upload_file_path)) {
      $file_name = basename(preg_replace('/[^\da-z]/i', '', strtolower($pathinfo['filename']))) . '_' . uniqid() . "." . $pathinfo['extension'];
      $upload_file_path = __DIR__ . "/attachment/" . $file_name;
    }

    if (move_uploaded_file($_FILES["attachment"]["tmp_name"], $upload_file_path)) {
      //save to database
      $esc_file_name = mysqli_real_escape_string($connection, $file_name);
      $update_sql = "UPDATE `$table` SET attachment = '$esc_file_name' WHERE student_code = '$student_code'";
      $result=mysqli_query($connection, $update_sql);

      $_SESSION["StudentReg"]["attachment"] = $file_name;
    }

  }else{
    unset($_SESSION["StudentReg"]["attachment"]);
  }
}

function shortenAttachmentName($file_name){
  $split = explode('_', $file_name);
  $split[0] = substr($split[0], 0, 12);

  return $split[0] . '..._' . $split[1];
}

function getPrimaryContact($table, $temp_student_code){
  global $connection;

  $sql="SELECT * from `$table` where student_code='$temp_student_code' order by id LIMIT 1";
  
  $result=mysqli_query($connection, $sql);

  if ($result) {
    $row = mysqli_fetch_assoc($result);
  }else{
    $row = array();
  }

  return $row;
}

function getStudent($table, $get_sha1_id){
  global $connection;

  $sql="SELECT * from `$table` where sha1(id)='$get_sha1_id' LIMIT 1";
  $result=mysqli_query($connection, $sql);

  if ($result) {
    $row=mysqli_fetch_assoc($result);
  }else{
    $row = array();
  }

  return $row;
}

function clean($string) {
  $string2 = str_replace("'","",$string);
  return $string2;
}

function saveStudent($table, $form_mode = ''){
  global $connection;

  foreach ($_POST as $key=>$value) {
     $$key=mysqli_real_escape_string($connection, $value);
  }

  $start_date_at_centre = convertDate2ISO($start_date_at_centre);
  $programme_date = convertDate2ISO($programme_date);
  if( ! empty($dob_year) && ! empty($dob_month) && ! empty($dob_day) ){
     $dob = parseDOB($dob_day, $dob_month, $dob_year);
  }else{
     $dob = '';
  }

  if (!isRecordFound($table, 'student_code', $student_code)) {
    throw new Exception('Student record not found');
  }

  validateStudentPhoto();
  validateAttachment();

  if ($form_mode == 'qr') {
    $student_status = 'A';
    $form_serial_no = '';
  }else{
    if ($student_status == '') {
     // throw new Exception("Please fill in fields with *");
    }
  }

   if (($student_code!="")
       && ($name!="")
       && ($dob!="")
       && ($start_date_at_centre!="")
       && ($gender!="")
       && ($add1!="")
       && ($country!="")
       && ($state!="")
       && ($race!="")
       && ($nationality!="")
       && ($religion!="")
       && ($accept_terms=="1")
       && ($signature!='{\"lines\":[]}' && $signature!="")
     ) {   

		//$extend_year = getYearFromMonth(date('Y',strtotime($start_date_at_centre)), date('m',strtotime($start_date_at_centre)));
    
  //Set extend_year to the current year because everyone's confused where the new student went lmao
	$extend_default = "";

  $startDate = strtotime(date('Y-m-d', strtotime($start_date_at_centre) ) );
  $currentDate = strtotime('2024-03-01'); 
  
//  if($startDate < $currentDate) {
//    $extend_default = "2024-2025";
//  }else{
//    $extend_default = "2023-2024";
//  }

//$extend_default = $extend_year;

    if ($form_mode == 'qr') {
      $sql="SELECT * from `student` where student_code !='$hidden_student_code' and student_status!='I' and nric_no='$nric_no' ";
   }else{
     $sql="SELECT * from `$table` where student_code !='$hidden_student_code' and student_status!='I' and nric_no='$nric_no'";
   }


$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);

/* if ($num_row > 0) {
 resetStudentRegistrationSession();
 echo "<script> alert('Student MyKid/Passport No must be unique, please try again'); </script>";
  // throw new Exception("Same IC / Passport Number In Database");
}else{
 */

	$student_data = mysqli_fetch_array(mysqli_query($connection,"SELECT `student_status` FROM `student` WHERE `student_code`='".$hidden_student_code."'"));
	$defer_date = '';
  if($student_data['student_status'] == 'D') {
    $defer_date = $student_data['defer_date']; 
  } else if($student_status == 'D') {
    $defer_date = date('Y-m-d');
	$defer_date = ", defer_date='$defer_date'";
  } else { 
  }

 $name = clean($name);
/*
       $sql="UPDATE `$table` set
        student_code='$student_code',
        student_status='$student_status',
        name='$name',
        nric_no='$nric_no',
        form_serial_no='$form_serial_no',
        dob='$dob',
        birth_cert_no='$birth_cert_no',
        nationality='$nationality',
        start_date_at_centre='$start_date_at_centre',
        gender='$gender',
        age='$age',
        add1='$add1',
        add2='$add2',
        add3='$add3',
        add4='$add4',
        country='$country',
        extend_year='$extend_year',
        state='$state',
        race='$race',
        nationality='$nationality',
        religion='$religion',
        remarks='$remarks',
        health_problem='$health_problem',
        allergies='$allergies',
        accept_photo='$accept_photo',
        accept_terms='$accept_terms',
        signature='$signature',
		unique_id='$unique_id'
        $defer_date
		where student_code='$hidden_student_code'";
*/

$extend_default = $extend_year;

//Removing Extend year update due to students suddenly going back to previous terms
$sql="UPDATE `$table` set
        student_code='$student_code',
        student_status='$student_status',
        name='$name',
        nric_no='$nric_no',
        form_serial_no='$form_serial_no',
        dob='$dob',
        birth_cert_no='$birth_cert_no',
        nationality='$nationality',
        start_date_at_centre='$start_date_at_centre',
        gender='$gender',
        age='$age',
        add1='$add1',
        add2='$add2',
        add3='$add3',
        add4='$add4',
        country='$country',
        state='$state',
        race='$race',
        nationality='$nationality',
        religion='$religion',
        remarks='$remarks',
        health_problem='$health_problem',
        allergies='$allergies',
        accept_photo='$accept_photo',
        accept_terms='$accept_terms',
        signature='$signature',
        chinese_name='$chinese_name',
		unique_id='$unique_id'
        $defer_date
		where student_code='$hidden_student_code'";
		//extend_year='$extend_year',

     $result = mysqli_query($connection, $sql);
	 $student_id= getStudentID($_GET['id']);
	 
		$sql3 = "select student_entry_level from programme_selection where student_id='$student_id'";
		$result3=mysqli_query($connection, $sql3);
		//$row3=mysqli_fetch_assoc($result3);
		while ($row3 = mysqli_fetch_assoc($result3)) {
		$student_entry_level4 = $row3['student_entry_level'];
		}
	 if($student_entry_level4!=$student_entry_level){
		//$insert_sql2="INSERT into `programme_selection` (student_id,student_code,name,student_entry_level,programme_duration,fee_id) values ('$student_id','$student_code','$name','$student_entry_level','$programme_duration','$fee_id' )";
		//mysqli_query($connection, $insert_sql2);
	 }else{
		//$insert_sql3="UPDATE `programme_selection` set fee_id='$fee_id' where student_id='$student_id' and student_entry_level='$student_entry_level'";
		//echo $insert_sql2; die;
		//mysqli_query($connection, $insert_sql3);
	 }		 
	// 	$sql4 = "select fee_id from student_fee_history where student_id='$student_id'";
	// 	$result3=mysqli_query($connection, $sql4);
	// 	//$row3=mysqli_fetch_assoc($result3);
	// 	while ($row3 = mysqli_fetch_assoc($result3)) {
	// 	$fee_id3 = $row3['fee_id'];
	// 	}
	// if($fee_id3!=$fee_id){	
	// 	$insert_sql4="INSERT into `student_fee_history` (centre_code,student_id,student_code,name,student_entry_level,programme_duration,fee_id) values ('$centre_code','$student_id','$student_code','$name','$student_entry_level','$programme_duration','$fee_id')";
	// 	mysqli_query($connection, $insert_sql4);
	// }

     if ($result) {
        
        //card information
        $sql = "select id from student where student_code='$hidden_student_code'";
        $result1=mysqli_query($connection, $sql);
        $row=mysqli_fetch_assoc($result1);
        $student_id = $row['id'];
        
        $sql2 = "select id from student_card where student_id='$student_id'";
        $result2=mysqli_query($connection, $sql2);
        $num_row=mysqli_fetch_assoc($result2);
      
        if ($num_row == 0) {
          $insert_sql="INSERT into student_card (
            student_id,
            status,
            unique_id
          ) values (
            '$student_id',
            '0',
            '$unique_id'
          )";
        
          mysqli_query($connection, $insert_sql);
        }else{
          $insert_sql="UPDATE student_card set unique_id ='$unique_id' where student_id='$student_id' and unique_id ='' and status=0 and deleted = 0 limit 1";
        
          mysqli_query($connection, $insert_sql);
        }

        $student_detail=mysqli_fetch_assoc(mysqli_query($connection,"SELECT * FROM `student` WHERE `id` = '".$student_id."'"));

        $s="SELECT * from `student_emergency_contacts` where student_code = '" . $hidden_student_code . "'  ORDER BY `student_emergency_contacts`.`id` ASC";
        $r=mysqli_query($connection, $s);

        if (mysqli_num_rows($r)) {
          $student_detail['contacts'] = json_encode(mysqli_fetch_all($r, MYSQLI_ASSOC)[0]);
        }else{
          $student_detail['contacts'] = '';
        }

        AppStudent($student_detail);

        //api code
        $postRequest = array(
          'student_type' => 'Starter',
          'student_id' => '$hidden_student_code',
          'qr_code' => '$unique_id',
          'session' => 'cb76fe897986563639f6983bfd33b57cd'
        );

      
        $cURLConnection = curl_init('https://starters.q-dees.com/application/api/StudentQRassignment');
        curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $postRequest);
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

        $apiResponse = curl_exec($cURLConnection);
        curl_close($cURLConnection);

        //  $status = $array['status'];
        //  $message = $array['message'];
        //  //print_r($array);
        //  if ($status == 1) {

        //  }
        //end API Code


         processStudentPhoto($table, $student_code);
         processAttachment($table, $student_code);

         resetStudentRegistrationSession();
         return "Student successfully saved.";
     } else {
        throw new Exception("Failed to update student");
     }
   /*  } */
  } else {
    // throw new Exception("Please fill in fields with *");
  }
  
}

function createStudent($table, $visitor=[]){
  global $connection, $child_no;

  foreach ($_POST as $key=>$value) {
    $$key=mysqli_real_escape_string($connection, $value);
  }

  if (isRecordFound($table, 'student_code', $student_code)) {
    resetStudentRegistrationSession();
    unset($_POST);
    throw new Exception("You have already submitted the form.");
  }

  $start_date_at_centre=convertDate2ISO($start_date_at_centre);
  $programme_date=convertDate2ISO($programme_date);

  if( ! empty($dob_year) && ! empty($dob_month) && ! empty($dob_day) ){
   $dob = parseDOB($dob_day, $dob_month, $dob_year);
  }else{
   $dob = '';
  }

  if (!isset($student_status)) {
   $student_status = 'A';
  }

  if (!isset($form_serial_no)) {
   $form_serial_no = '';
  }

  validateStudentPhoto();
  validateAttachment();
  if ($table == 'student') {
    if (!isset($_SESSION["StudentCodeTaken"])) {
      $student_code2 = getStudentCode($_SESSION["CentreCode"]);
      $_SESSION["StudentCodeTaken"]=$student_code2;
    }else{
      $student_code2 = $_SESSION["StudentCodeTaken"];
    }
   // $student_code2 = getStudentCode($_SESSION["CentreCode"]);
  } else {
    $student_code2 = $student_code;
  }

  if (($name!="")) {
$extend_year = getYearFromMonth(date('Y',strtotime($start_date_at_centre)), date('m',strtotime($start_date_at_centre)));
     $photo_file_name = '';
     $attachment = '';

     $sql="SELECT * from `$table` where nric_no='$nric_no' and student_status!='I'";
   $result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);

/* if ($num_row > 0) {
resetStudentRegistrationSession();
echo "<script> alert('Student MyKid/Passport No must be unique, please try again'); </script>";
 throw new Exception("Student MyKid/Passport No must be unique, please try again");
}else { */

$defer = ($student_status == 'D') ? ', defer_date' : '';
$defer_dates = ($student_status == 'D') ? ", '".date('Y-m-d h:m:i')."'" : '';
$date_created = date('Y-m-d h:m:i');

//Remove special characters
$name = clean($name);

	//Set extend_year to the current year because everyone's confused where the new student went lmao
	$extend_default = "";

    $startDate = strtotime(date('Y-m-d', strtotime($start_date_at_centre) ) );
    $currentDate = strtotime('2024-03-01'); 
    
    //if($startDate < $currentDate) {
    //  $extend_default = "2023-2024";
    //}else{
	  $extend_default = "2024-2025";
	//}

    $insert_sql="INSERT into `$table` (
       student_code,
       centre_code,
       student_status,
       name,
       photo_file_name,
       nric_no,
       form_serial_no,
       dob,
       birth_cert_no,
       start_date_at_centre,
       gender,
       age,
       add1,
       add2,
       add3,
       add4,
       country,
       state,
       race,
       nationality,
       religion,
       health_problem,
       allergies,
       remarks,
       accept_photo,
       accept_terms,
       attachment,
       signature,
       chinese_name,
	   extend_year,
	   unique_id,
     delete_document,
     delete_remarks,
	 date_created ".$defer."
     ) values (
       '$student_code',
       '$centre_code',
       '$student_status',
       '$name',
       '$photo_file_name',
       '$nric_no',
       '$form_serial_no',
       '$dob',
       '$birth_cert_no',
       '$start_date_at_centre',
       '$gender',
       '$age',
       '$add1',
       '$add2',
       '$add3',
       '$add4',
       '$country',
       '$state',
       '$race',
       '$nationality',
       '$religion',
       '$health_problem',
       '$allergies',
       '$remarks',
       '$accept_photo',
       '$accept_terms',
       '$attachment',
       '$signature',
       '$chinese_name',
       '$extend_default',
		'$unique_id','','','$date_created'".$defer_dates."
     )";
     $result=mysqli_query($connection, $insert_sql);
	 $student_id = mysqli_insert_id($connection);
	 //$insert_sql2="INSERT into `programme_selection` (student_id,student_code,name,student_entry_level,programme_duration,fee_id) values ('$student_id','$student_code','$name','$student_entry_level','$programme_duration','$fee_id' )";
	//	mysqli_query($connection, $insert_sql2);
		
	// $insert_sql3="INSERT into `student_fee_history` (centre_code,student_id,student_code,name,student_entry_level,programme_duration,fee_id) values ('$centre_code','$student_id','$student_code','$name','$student_entry_level','$programme_duration','$fee_id')";
	// mysqli_query($connection, $insert_sql3);
		
     if ($result) {
       
      $del_sql="DELETE FROM `student_code_draft` where student_code='$student_code'";
      $del_result=mysqli_query($connection, $del_sql);

      if ($table == 'student') {

        $student_detail=mysqli_fetch_assoc(mysqli_query($connection,"SELECT * FROM `student` WHERE `id` = '".$student_id."'"));

        $s="SELECT * from `student_emergency_contacts` where student_code = '" . $student_code . "'  ORDER BY `student_emergency_contacts`.`id` ASC";
        $r=mysqli_query($connection, $s);

        if (mysqli_num_rows($r)) {
          $student_detail['contacts'] = json_encode(mysqli_fetch_all($r, MYSQLI_ASSOC)[0]);
        }else{
          $student_detail['contacts'] = '';
        }

        AppStudent($student_detail);

        $sql_getContact="SELECT * from `student_emergency_contacts` where student_code='".$student_code."'";
        $result_getContact=mysqli_query($connection, $sql_getContact);
        
        //   while ($row_getContact=mysqli_fetch_assoc($result_getContact)) {


        //   $sql_transferContact="INSERT into `student_emergency_contacts` (student_code, contact_type, full_name, nric, email, mobile_country_code, mobile, occupation, education_level, can_pick_up, vehicle_no, remarks) values ('$student_code2','".$row_getContact['contact_type']."','".$row_getContact['full_name']."','".$row_getContact['nric']."','".$row_getContact['email']."','".$row_getContact['mobile_country_code']."','".$row_getContact['mobile']."','".$row_getContact['occupation']."','".$row_getContact['education_level']."','".$row_getContact['can_pick_up']."','".$row_getContact['vehicle_no']."','".$row_getContact['remarks']."')";
        //   mysqli_query($connection, $sql_transferContact);

        //   // $sql_delContact="DELETE FROM `tmp_student_emergency_contacts` where student_code='$student_code'";
        //   //mysqli_query($connection, $sql_delContact);
        // }
       
        //card information
      $sql = "select id from student where student_code='$student_code'";
      $result1=mysqli_query($connection, $sql);
      $row=mysqli_fetch_assoc($result1);
      $student_id = $row['id'];

      $sql2 = "select id from student_card where student_id='$student_id'";
      $result2=mysqli_query($connection, $sql2);
      $num_row=mysqli_fetch_assoc($result2);
      if ($num_row == 0) {
      $insert_sql="INSERT into student_card (
        student_id,
        status,
        unique_id
      ) values (
        '$student_id',
        '0',
        '$unique_id'
      )";
       mysqli_query($connection, $insert_sql);
      }

      //api code
      $postRequest = array(
        'student_type' => 'Scholar',
        'student_id' => '$student_code',
        'qr_code' => '$unique_id',
        'session' => 'cb76fe897986563639f6983bfd33b57cd'
     );

     $cURLConnection = curl_init('https://starters.q-dees.com/application/api/StudentQRassignment');
     curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $postRequest);
     curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

     $apiResponse = curl_exec($cURLConnection);
     curl_close($cURLConnection);

    //  $status = $array['status'];
    //  $message = $array['message'];
    //  //print_r($array);
    //  if ($status == 1) {
    
    //  }
    //end API Code
//end card information

        
      }
        processStudentPhoto($table, $student_code);
        processAttachment($table, $student_code);
		if(!empty($visitor)){
			if(!empty($visitor['visitor_id'])){
				$vs_sql="UPDATE `visitor_student` SET child_".$child_no."_student_id='".$student_id."' where visitor_id='".$visitor['visitor_id']."'";
			}
			else{
				$vs_sql="INSERT into `visitor_student` (visitor_id, child_".$child_no."_student_id) values ('".$visitor['vid']."', '".$student_id."')";
			}

			mysqli_query($connection, $vs_sql);
			throw new Exception("Student registered successfully");
		}
        resetStudentRegistrationSession();
		throw new Exception("Student registered successfully");
		return $student_id;
     } else {
        forceDeleteStudentByStudentCode($table, $student_code);
        throw new Exception("Failed to create student");
     }
   /*  } */
  } else {
    forceDeleteStudentByStudentCode($table, $student_code);
    throw new Exception("Please fill in fields with *");
  }
  return false;
}

 
function forceDeleteStudentByStudentCode($table, $student_code){
  global $connection;
  if (isRecordFound($table, 'student_code', $student_code)) {
    $del_sql="DELETE FROM `$table` where student_code='$student_code'";
    $result=mysqli_query($connection, $del_sql);
  }
}

function deleteStudent($table, $get_sha1_id){
    $del_sql="UPDATE `$table` set deleted=1 where sha1(id)='$get_sha1_id'";
    $result=mysqli_query($connection, $del_sql);

    if ($result) {
       return true;
    } else {
       throw new Exception("Cannot delete student");
    }
}

function resetStudentRegistrationSession(){
  //reset
  if (isset($_SESSION["StudentReg"])) {
    unset($_SESSION["StudentReg"]);
  }
}

function setStudentRegistrationSession(){
  if (!empty($_POST)) {
    foreach ($_POST as $key=>$value) {
      $_SESSION["StudentReg"]["$key"]=$value;
    }
  }
}

function dd($var){
  echo '<pre>';
  var_dump($var);
  echo '</pre>';
  die();
}

$msg="";
$msg_saved="";

if (defined('IS_ADMIN_STUDENT_FORM') && IS_ADMIN_STUDENT_FORM ) {
  $table="student";
  $table_contact="student_emergency_contacts";
  $centre_code=$_SESSION["CentreCode"];
  $get_sha1_id=isset($_GET["id"]) ? $_GET["id"] : '';

  $form_post_url = "index.php?p=student_reg&id=$get_sha1_id&m=$m";
	$visitor = getVisitor($sha1_visitor_id);
	if(!empty($visitor)) $form_post_url .= "&visitor=$sha1_visitor_id&ch=$child_no"; 

  if ($_SESSION["isLogin"]) {
    try {
      if (!empty($get_sha1_id)) {

        //process form
        if (!empty($_POST)) {
          $msg = saveStudent($table);
         // $msg = "Student successfully saved.";
        }
        //if($msg_saved==""){
          $data_array = getStudent($table, $get_sha1_id);
          $temp_student_code = $data_array['student_code'];
          $row_contact = getPrimaryContact($table_contact, $temp_student_code);
        //}
        
      }else{
        //new student
        $data_array = array();
        if (!isset($_SESSION["StudentCodeTaken"])) {
          $temp_student_code = getStudentCode($_SESSION["CentreCode"]);
          $_SESSION["StudentCodeTaken"]=$temp_student_code;
        }else{
          $temp_student_code = $_SESSION["StudentCodeTaken"];
        }

        //process form
        if (!empty($_POST)) {
          $new_student_id = createStudent($table, $visitor);
			
          $msg = "Student successfully created.";
exit;
          //reset
          //$data_array = array();
          //$temp_student_code = getStudentCode($_SESSION["CentreCode"]);
		  echo "<script>window.location = 'index.php?p=student_reg&mode=EDIT&id=".sha1($new_student_id)."';</script>";
        }
      }
    } catch (\Exception $e) {
      if (!empty($_POST)) {
        setStudentRegistrationSession();
        $data_array=$_SESSION["StudentReg"];
      }

      $msg = $e->getMessage();
    }
  }//isLogin

}elseif (defined('IS_PUBLIC_STUDENT_FORM') && IS_PUBLIC_STUDENT_FORM ) {

  //public form
  $table="tmp_student";
  $table_contact="tmp_student_emergency_contacts";
  $centre_code=$_GET["centre_code"];
  $get_sha1_id=$_GET["id"];

  if (!empty($_SESSION["isLogin"]) && !empty($get_sha1_id)) {
    $form_post_url = "student_qr.php?&centre_code=".$centre_code."&id=".$get_sha1_id;

    //edit student qr
    try {
      $data_array = getStudent($table, $get_sha1_id);
      $row_contact = getPrimaryContact($table_contact, $data_array['student_code']);

      //save student qr
      if (!empty($_POST)) {
        saveStudent($table, 'qr');

        $data_array = getStudent($table, $get_sha1_id);
        $row_contact = getPrimaryContact($table_contact, $data_array['student_code']);
        $msg = "Student QR successfully saved.";
      }

    } catch (\Exception $e) {
      if (!empty($_POST)) {
        setStudentRegistrationSession();
        $data_array=$_SESSION["StudentReg"];
      }

      $msg = $e->getMessage();
    }

  } else {
    $form_post_url = "student_qr.php?&centre_code=".$centre_code;

    //create student qr
    try {
      if (!empty($_POST)) {
        createStudent($table);

        $msg = "Thank you for submitting.";
      }
    } catch (\Exception $e) {
      if (!empty($_POST)) {
        setStudentRegistrationSession();
        $data_array=$_SESSION["StudentReg"];
      }

      $msg = $e->getMessage();
    }

  }//session

  if (!empty($get_sha1_id)) {
    $temp_student_code = $data_array["student_code"];
  }elseif (!empty($_SESSION['StudentReg']['student_code'])) {
    $temp_student_code=$_SESSION['StudentReg']['student_code'];
  }else{
    $temp_student_code=generateRandomString1(32);
  }

}//IS_ADMIN_STUDENT_FORM

if ($_SESSION['isLogin']) {
  $table = 'student';

  if (!empty($_POST["action"]) &&  $_POST["action"] == 'DEL') {
    try {
      deleteStudent($table, $_POST['id']);
    } catch (\Exception $e) {
      $msg = $e->getMessage();
    }
  }//del
}
?>
