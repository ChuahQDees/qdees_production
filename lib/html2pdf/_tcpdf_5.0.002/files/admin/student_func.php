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

function saveStudent($table, $form_mode = ''){
  global $connection;

  foreach ($_POST as $key=>$value) {
     $$key=mysqli_real_escape_string($connection, $value);
  }

  $start_date_at_centre = convertDate2ISO($start_date_at_centre);
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
      throw new Exception("Please fill in fields with *");
    }
  }

   if (($student_code!="")
       && ($name!="")
       && ($nric_no!="")
       && ($dob!="")
       && ($birth_cert_no!="")
       && ($start_date_at_centre!="")
       && ($gender!="")
       && ($add1!="")
       && ($country!="")
       && ($state!="")
       && ($race!="")
       && ($nationality!="")
       && ($religion!="")
       && ($accept_terms=="1")
       && ($signature!='{\"lines\":[]}' && $signature!="")) {

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
        signature='$signature'
     where student_code='$hidden_student_code'";

     $result = mysqli_query($connection, $sql);

     if ($result) {
         processStudentPhoto($table, $student_code);
         processAttachment($table, $student_code);

         resetStudentRegistrationSession();
     } else {
        throw new Exception("Failed to update student");
     }
  } else {
     throw new Exception("Please fill in fields with *");
  }
}

function createStudent($table){
  global $connection;

  foreach ($_POST as $key=>$value) {
    $$key=mysqli_real_escape_string($connection, $value);
  }

  if (isRecordFound($table, 'student_code', $student_code)) {
    resetStudentRegistrationSession();
    unset($_POST);
    throw new Exception("You have already submitted the form.");
  }

  $start_date_at_centre=convertDate2ISO($start_date_at_centre);

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

  if (($student_code!="")
   && ($name!="")
   && ($nric_no!="")
   && ($dob!="")
   && ($birth_cert_no!="")
   && ($start_date_at_centre!="")
   && ($gender!="")
   && ($add1!="")
   && ($country!="")
   && ($state!="")
   && ($race!="")
   && ($nationality!="")
   && ($religion!="")
   && ($accept_terms=="1")
   && ($signature!='{\"lines\":[]}' && $signature!="")) {

     $photo_file_name = '';
     $attachment = '';

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
       signature
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
       '$signature'
     )";

     $result=mysqli_query($connection, $insert_sql);

     if ($result) {
        processStudentPhoto($table, $student_code);
        processAttachment($table, $student_code);

        resetStudentRegistrationSession();
     } else {
        forceDeleteStudentByStudentCode($table, $student_code);
        throw new Exception("Failed to create student");
     }
  } else {
    forceDeleteStudentByStudentCode($table, $student_code);
    throw new Exception("Please fill in fields with *");
  }
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

if (defined('IS_ADMIN_STUDENT_FORM') && IS_ADMIN_STUDENT_FORM ) {
  $table="student";
  $table_contact="student_emergency_contacts";
  $centre_code=$_SESSION["CentreCode"];
  $get_sha1_id=$_GET["id"];

  $form_post_url = "index.php?p=student_reg&id=$get_sha1_id&m=$m";

  if ($_SESSION["isLogin"]) {
    try {
      if (!empty($get_sha1_id)) {

        //process form
        if (!empty($_POST)) {
          saveStudent($table);
          $msg = "Student successfully saved.";
        }

        $data_array = getStudent($table, $get_sha1_id);
        $temp_student_code = $data_array['student_code'];
        $row_contact = getPrimaryContact($table_contact, $temp_student_code);
      }else{
        //new student
        $data_array = array();
        $temp_student_code = getStudentCode($_SESSION["CentreCode"]);

        //process form
        if (!empty($_POST)) {
          createStudent($table);

          $msg = "Student successfully created.";

          //reset
          $data_array = array();
          $temp_student_code = getStudentCode($_SESSION["CentreCode"]);
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
