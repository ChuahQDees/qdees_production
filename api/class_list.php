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

  

	$order_by_list = array('id', 'centre_code', 'year', 'start_date', 'end_date');

  

	if (!in_array($order_by, $order_by_list)) {

		throw new Exception("Invalid order_by column name");

	}

	
	$year = date("Y");
	$where = "inner join centre c on c.centre_code=g.centre_code";

	$where .= isset($centre_code) ? " and g.centre_code='$centre_code'" : '';

	

  // $sql="SELECT count(g.id) as total from `group` g $where";

	//$sql="SELECT * from `group` g $where ORDER BY `g`.`id` DESC $limit";
	$sql="SELECT * from `group` g $where where g.`year`='$year' and CURDATE() <= DATE_ADD(`end_date`, INTERVAL 14 DAY) ORDER BY `g`.`id` DESC $limit";

  // var_dump($sql);

  $result=mysqli_query($connection, $sql);



  if (mysqli_num_rows($result)) {

    $tmp =mysqli_fetch_assoc($result);

    // $total = (int)$tmp['total'];

    $total = mysqli_num_rows($result);

  }else{

    $total  = 0;

  }



  //$sql="SELECT g.* from `group` g $where ORDER BY `$order_by` $order_dir $limit ";
  $sql="SELECT g.* from `group` g $where where g.`year`='$year' and CURDATE() <= DATE_ADD(`end_date`, INTERVAL 14 DAY) ORDER BY `$order_by` $order_dir $limit ";

  $result=mysqli_query($connection, $sql);



  if (mysqli_num_rows($result)) {

    while($class=mysqli_fetch_assoc($result)){

		if ($class['class_id']){

			$class['group'] = $class['class_id'];

			unset($class['class_id']);

		}

		

		

		//course

		$sql2="SELECT `id`,  CONCAT(`subject` , ' - ', '".$class['group']."', ' - ',  '".$class['duration']."') as course_name, `fees`, `registration`, `deposit`, `placement`, `deleted`, `payment_type`, `country`, `state`, `subject`, `remark`, `duration`, `term`, `module`, `materials` from `course` where id = '" . mysqli_real_escape_string($connection, $class['course_id']) . "'";

		$result2=mysqli_query($connection, $sql2);

		if (mysqli_num_rows($result2)) {

			$class['course_info'] = mysqli_fetch_assoc($result2);

		}

		

		//centre

		$sql3="SELECT *, company_name as centre_name from `centre` where centre_code = '" . mysqli_real_escape_string($connection, $class['centre_code']) . "'";
		//echo $sql3;

		$result3=mysqli_query($connection, $sql3);

		if (mysqli_num_rows($result3)) {

			$centre = mysqli_fetch_assoc($result3);

			if ($centre['SSM_file']) 

				$centre['SSM_file'] = CENTRE_UPLOAD_URL . $centre['SSM_file'];

			

			if ($centre['MOE_license_file']) 

				$centre['MOE_license_file'] = CENTRE_UPLOAD_URL . $centre['MOE_license_file'];

			$class['centre_info'] = $centre;

		}

		

		$student_total = 0;

		//student

		$sql4="SELECT s.* from allocation a, student s where a.group_id='".$class['id']."' and course_id='".$class['course_id']."'

      and a.class_id='".$class['group']."' and a.year='".$class['year']."' and s.id=a.student_id and a.deleted=0 and s.deleted=0

      and s.student_status='A' and s.centre_code='".$class["centre_code"]."'";

		$result4=mysqli_query($connection, $sql4);

		$students = [];

		if (mysqli_num_rows($result4)) {

			while($student=mysqli_fetch_assoc($result4)){

				if ($student['photo_file_name']) {

					$student['photo_file_name'] = getStudentPhotoSrc($student['photo_file_name']);

				}



				if ($student['attachment']) {

					$student['attachment'] = STUDENT_ATTACHMENT_URL . $student['attachment'];

				}

				$students[] = $student;

				$student_total++;

			}

		}



		$class['no_of_students'] = $student_total;

		$class['students'] = $students;

		$data[] = $class;

    }



    $o = array(

      'status' => 'OK',

      'message' => 'Class found',

      'total' => $total,

      'data' => $data

    );

  }else{

    throw new Exception("Class list empty");

  }

} catch (\Exception $e) {

  $o = array(

    'status' => 'ERROR',

    'message' => $e->getMessage(),

  );

}



echo json_encode($o, JSON_PRETTY_PRINT);

