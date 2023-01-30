<?php
include_once("../mysql.php");
include_once("basic_auth.php");

global $connection;

function siteURL()
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
  $subfolder = rtrim(dirname($_SERVER[PHP_SELF]), '/api');
  $subfolder = ($subfolder=='/' or $subfolder == '\\') ? '' : $subfolder;
    $domainName = $_SERVER['HTTP_HOST'] . $subfolder;
    return $protocol.$domainName;
}
if( ! defined('STUDENT_PHOTO_URL') ){
  define('STUDENT_PHOTO_URL', siteURL(). '/admin/student_photo/');
}

if( ! defined('STUDENT_PHOTO_PATH') ){
  define('STUDENT_PHOTO_PATH', __DIR__ . '/../admin/student_photo/');
}

if( ! defined('STUDENT_ATTACHMENT_URL') ){
  define('STUDENT_ATTACHMENT_URL', siteURL(). '/admin/attachment/');
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

try {
  $centre_code = $_GET['centre_code'];
  $page = (int)$_GET['page'];
  $order_by = $_GET['order_by'];
  $order_dir = !isset($_GET['order_dir']) ? 'asc' : $_GET['order_dir'];
  $year = date("Y");

  $limit = '';
  if(isset($_GET['page'])){
    $page = (int)$_GET['page'];
    if ($page > 1) {
      $offset = (($page-1)*20) + 1;
    }else{
      $page = 0;
      $offset = 0;
    }
	if($offset==0){
    $limit = "LIMIT $offset, 21";
  }else	{
	  $limit = "LIMIT $offset, 20";
  }
  }

  if ($_GET['page'] == '0') {
     throw new Exception("Student list empty");
  }

  // if (!in_array($order_by, array('date_created', 'id'))) {
  //   throw new Exception("Invalid order_by column name");
  // }
  
  if ($centre_code) {
    $sql="SELECT *, unique_id as qr_id from `student` where  student_status='A' and deleted='0' and centre_code = '$centre_code'     ORDER BY `student`.`id` DESC $limit";
  }else{
    $sql="SELECT *, unique_id as qr_id from `student` where student_status='A' and deleted='0'   ORDER BY `student`.`id` DESC $limit ";
  }

  if ($limit && !$centre_code) {
    $sql="SELECT *, unique_id as qr_id from `student` where student_status='A' and deleted='0'   ORDER BY `student`.`id` DESC";
    $result=mysqli_query($connection, $sql);
    $row = mysqli_num_rows($result);
    $totalpageNumber = $row / 20;
	
  }elseif ($limit && $centre_code) {
   $sql="SELECT *, unique_id as qr_id from `student` where student_status='A'    and deleted='0'and centre_code = '$centre_code'  ORDER BY `student`.`id` DESC";
    $result=mysqli_query($connection, $sql);
    $row = mysqli_num_rows($result);
    $totalpageNumber = $row / 20;
    if ($totalpageNumber < 1) {
      $totalpageNumber = $_GET['page'];
    }
  } 
   
  $result=mysqli_query($connection, $sql);
  // echo $sql; die;

  if (mysqli_num_rows($result)) {
    $tmp =mysqli_fetch_assoc($result);
    $total_student = mysqli_num_rows($result);
  }else{
    $total_student = 0;
  }

  

 if ($centre_code) {
	$sql="SELECT *, unique_id as qr_id from `student` where centre_code = '$centre_code' AND student_status='A' and deleted='0'   ORDER BY  `student`.`id` DESC $limit ";
  }else{
    $sql="SELECT *, unique_id as qr_id from `student` where student_status='A' and deleted='0'   ORDER BY `student`.`id` DESC $limit ";
  }
   //echo $sql; die;
   // echo $sql; die;
  $result=mysqli_query($connection, $sql);
  



  if (mysqli_num_rows($result)) {
    while($student=mysqli_fetch_assoc($result)){

      if ($student['photo_file_name']) {
        $student['photo_file_name'] = getStudentPhotoSrc($student['photo_file_name']);
      }

      if ($student['attachment']) {
        $student['attachment'] = STUDENT_ATTACHMENT_URL . $student['attachment'];
      }

      //student contacts
      //$sql2="SELECT * from `student_emergency_contacts` where student_code = '" . mysqli_real_escape_string($connection, $student['student_code']) . "'  ORDER BY `student_emergency_contacts`.`id` ASC $limit ";
      $sql2="SELECT * from `student_emergency_contacts` where student_code = '" . mysqli_real_escape_string($connection, $student['student_code']) . "'  ORDER BY `student_emergency_contacts`.`id` ASC";
      $result2=mysqli_query($connection, $sql2);

       //var_dump($sql2);

      if (mysqli_num_rows($result2)) {
        $student['contacts'] = mysqli_fetch_all($result2, MYSQLI_ASSOC);
        //print_r($student['contacts']);
      }else{
        $student['contacts'] = array();
      }

      $data[] = $student;
    }


    if ($limit) {
    $o = array(
      'status' => 'OK',
      'message' => 'Student found',
      'total' => $total_student,
      'current page' => $_GET['page'], 
      'total page' => ceil($totalpageNumber),
      'data' => $data
    );
  }else{
    $o = array(
      'status' => 'OK',
      'message' => 'Student found',
      'total' => $total_student,
      'data' => $data
    );
  }
  }else{
    throw new Exception("Student list empty");
  }
} catch (\Exception $e) {
  $o = array(
    'status' => 'ERROR',
    'message' => $e->getMessage(),
  );
}

echo json_encode($o, JSON_PRETTY_PRINT);
