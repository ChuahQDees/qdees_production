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
  
	$order_by_list = array('id', 'centre_code', 'kindergarten_name', 'company_name', 'year_of_commencement', 'expiry_date', 'status', 'country', 'state', 'created_date');
  
	if (!in_array($order_by, $order_by_list)) {
		throw new Exception("Invalid order_by column name");
	}
	$year = date("Y");
  // $sql="SELECT count(id) as total from `centre`";
	$sql="SELECT * from `centre` where year(created_date) <= '$year' and `status`='A'  ORDER BY `centre`.`id` DESC $limit";
  $result=mysqli_query($connection, $sql);

  if (mysqli_num_rows($result)) {
    $tmp =mysqli_fetch_assoc($result);
    // $total = (int)$tmp['total'];
    $total = mysqli_num_rows($result);
  }else{
    $total  = 0;
  }

  $sql="SELECT `id`, `centre_code`, `kindergarten_name`, `company_name`, `upline`, `year_of_commencement`, `year_of_renewal`, `expiry_date`, `SSM_file`, `MOE_license_file`, `operator_name`, `operator_nric`, `operator_contact_no`, `principle_name`, `principle_contact_no`, `assistant_name`, `ANP_tel`, `personal_tel`, `status`, `centre_status_id`, `pic`, `ANP_email`, `company_email`, `can_adjust_fee`, `can_adjust_product`, `address1`, `address2`, `address3`, `address4`, `address5`, `country`, `state`, `franchisor_company_name`, `centre_franchisee_company_id`, `centre_franchisee_name_id`, `created_date`, `registration_fee` FROM `centre` where year(created_date) <= '$year' and `status`='A' ORDER BY `$order_by` $order_dir  $limit";

  
  
  $result=mysqli_query($connection, $sql);

  if (mysqli_num_rows($result)) {
    while($centre=mysqli_fetch_assoc($result)){
		if ($centre['SSM_file']) 
			$centre['SSM_file'] = CENTRE_UPLOAD_URL . $centre['SSM_file'];
		
		if ($centre['MOE_license_file']) 
			$centre['MOE_license_file'] = CENTRE_UPLOAD_URL . $centre['MOE_license_file'];
		
		//centre_agreement_file
		$sql2="SELECT * from `centre_agreement_file` where centre_code = '" . mysqli_real_escape_string($connection, $centre['centre_code']) . "'";
		$result2=mysqli_query($connection, $sql2);
		$agreement_files = [];
		if (mysqli_num_rows($result2)) {
			while($agreement_file=mysqli_fetch_assoc($result2)){
				if ($agreement_file['attachment']) 
					$agreement_file['attachment'] = CENTRE_UPLOAD_URL . $agreement_file['attachment'];
				$agreement_files[] = $agreement_file;
			}
		}

		$centre['agreement_files'] = $agreement_files;
		
		//centre_franchisee_name
		$sql3="SELECT * from `centre_franchisee_name` where centre_code = '" . mysqli_real_escape_string($connection, $centre['centre_code']) . "'";
		$result3=mysqli_query($connection, $sql3);
		$franchisee_names = [];
		if (mysqli_num_rows($result3)) {
			while($franchisee_name=mysqli_fetch_assoc($result3)){
				$franchisee_names[] = $franchisee_name;
			}
		}

		$centre['franchisee_names'] = $franchisee_names;
		
		//centre_franchisee_company
		$sql4="SELECT * from `centre_franchisee_company` where centre_code = '" . mysqli_real_escape_string($connection, $centre['centre_code']) . "'";
		$result4=mysqli_query($connection, $sql4);
		$franchisee_companies = [];
		if (mysqli_num_rows($result4)) {
			while($franchisee_company=mysqli_fetch_assoc($result4)){
				$franchisee_companies[] = $franchisee_company;
			}
		}

		$centre['franchisee_companies'] = $franchisee_companies;
		$data[] = $centre;
    }

    $o = array(
      'status' => 'OK',
      'message' => 'Centre found',
      'total' => $total,
      'data' => $data
    );
  }else{
    throw new Exception("Centre list empty");
  }
} catch (\Exception $e) {
  $o = array(
    'status' => 'ERROR',
    'message' => $e->getMessage(),
  );
}

echo json_encode($o, JSON_PRETTY_PRINT);
