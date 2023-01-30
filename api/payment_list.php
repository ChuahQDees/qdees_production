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

if( ! defined('CENTRE_UPLOAD_URL') ){
  define('CENTRE_UPLOAD_URL', siteURL().'/admin/uploads/');
}
if( ! defined('STUDENT_PHOTO_URL') ){
  define('STUDENT_PHOTO_URL', siteURL() . '/admin/student_photo/');
}

if( ! defined('STUDENT_PHOTO_PATH') ){
  define('STUDENT_PHOTO_PATH', __DIR__ . '/../admin/student_photo/');
}

if( ! defined('STUDENT_ATTACHMENT_URL') ){
  define('STUDENT_ATTACHMENT_URL', siteURL() . '/admin/attachment/');
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
  $batch_no = $_GET['batch_no'];
  $student_code = $_GET['student_code'];
  $order_by = !isset($_GET['order_by']) ? 'id' : $_GET['order_by'];
  $order_dir = !isset($_GET['order_dir']) ? 'desc' : $_GET['order_dir'];
	$limit = '';
	if(isset($_GET['page'])){
		$page = (int)$_GET['page'];
		if ($page > 1) {
			$offset = (($page-1)*25) + 1;
		}else{
			$page = 0;
			$offset = 0;
		}
		$limit = "LIMIT $offset, 25";
	}
  
	$order_by_list = array('id', 'payment_method', 'transaction_date');
  
	if (!in_array($order_by, $order_by_list)) {
		throw new Exception("Invalid order_by column name");
	}
	$conditions = [];
	if(isset($centre_code)) $conditions[] = "centre_code='$centre_code'";
	if(isset($batch_no)) $conditions[] = "batch_no='$batch_no'";
	if(isset($student_code)) $conditions[] = "student_code='$student_code'";
	$where = !empty($conditions) ? "where ".implode(' and ', $conditions) : '';
	
  $sql="SELECT * from `payment_detail` p left join collection c on c.payment_detail_id=p.id $where group by p.id ORDER BY `c`.`collection_date_time` $limit";
  $result=mysqli_query($connection, $sql);

  if (mysqli_num_rows($result)) {
    $tmp =mysqli_fetch_assoc($result);
    $total = mysqli_num_rows($result);
  }else{
    $total  = 0;
  }

  $sql="SELECT p.*, c.centre_code, c.batch_no, c.student_code from `payment_detail` p left join collection c on c.payment_detail_id=p.id $where group by p.id ORDER BY p.`$order_by` $order_dir $limit";
  
  $result=mysqli_query($connection, $sql);

  if (mysqli_num_rows($result)) {
    while($payment=mysqli_fetch_assoc($result)){
		//student
		$sql2="SELECT * from `student` where student_code = '" . mysqli_real_escape_string($connection, $payment['student_code']) . "' $limit";
		
		$result2=mysqli_query($connection, $sql2);
		if (mysqli_num_rows($result2)) {
			$student = mysqli_fetch_assoc($result2);
			if ($student['photo_file_name']) {
				$student['photo_file_name'] = getStudentPhotoSrc($student['photo_file_name']);
			}

			if ($student['attachment']) {
				$student['attachment'] = STUDENT_ATTACHMENT_URL . $student['attachment'];
			}
			$payment['student_info'] = $student;
		}
		
		//centre
		$sql3="SELECT * from `centre` where centre_code = '" . mysqli_real_escape_string($connection, $payment['centre_code']) . "' $limit ";
		$result3=mysqli_query($connection, $sql3);
		if (mysqli_num_rows($result3)) {
			$centre = mysqli_fetch_assoc($result3);
			if ($centre['SSM_file']) 
				$centre['SSM_file'] = CENTRE_UPLOAD_URL . $centre['SSM_file'];
			
			if ($centre['MOE_license_file']) 
				$centre['MOE_license_file'] = CENTRE_UPLOAD_URL . $centre['MOE_license_file'];
			$payment['centre_info'] = $centre;
		}
		
		$item_total = 0;
		//collection
		$sql4="SELECT * from collection where payment_detail_id='".$payment["id"]."'$limit ";
		$result4=mysqli_query($connection, $sql4);
		$items = [];
		if (mysqli_num_rows($result4)) {
			while($item=mysqli_fetch_assoc($result4)){
				$items[] = $item;
				$item_total++;
			}
		}

		$payment['no_of_items'] = $item_total;
		$payment['items'] = $items;
		$data[] = $payment;
    }

    $o = array(
      'status' => 'OK',
      'message' => 'Payment found',
      'total' => $total,
      'data' => $data
    );
  }else{
    throw new Exception("Payment list empty");
  }
} catch (\Exception $e) {
  $o = array(
    'status' => 'ERROR',
    'message' => $e->getMessage(),
  );
}

echo json_encode($o, JSON_PRETTY_PRINT);
