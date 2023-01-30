<?php
function notification($data) {
	global $connection;	
	$created_at=date("Y-m-d H:i:s");
	$action_id = $data['action_id'];
	$send_to = $data['send_to'];
	$is_hq_read = $data['is_hq_read'];
	$is_center_read = $data['is_center_read'];
	$send_from = $data['send_from'];
	$type = $data['type'];
	$subject = $data['subject']; 
	$centre_code = isset($_SESSION['CentreCode']) ? $_SESSION['CentreCode'] : '';
	$sql="INSERT into `notifications` (centre_code,action_id,send_to,send_from,type,subject,created_at,is_hq_read,is_center_read) 
	values ('$centre_code','$action_id','$send_to','$send_from','$type','$subject','$created_at','$is_hq_read','$is_center_read' )";
	$result=mysqli_query($connection, $sql);
}

function curlCall($url_param)
{
	$ch = curl_init();
	$urla = "http://helpdesk.q-dees.com/".$url_param;
	curl_setopt($ch, CURLOPT_URL,$urla);
	curl_setopt($ch, CURLOPT_POST, 0);
	//curl_setopt($ch, CURLOPT_POSTFIELDS,'');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$buffer = curl_exec($ch);
	curl_close ($ch);
 
	return $buffer;
}

function getKey()
{
	return strtolower( sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
        mt_rand( 0, 0xffff ),
        mt_rand( 0, 0x0C2f ) | 0x4000,
        mt_rand( 0, 0x3fff ) | 0x8000,
        mt_rand( 0, 0x2Aff ), mt_rand( 0, 0xffD3 ), mt_rand( 0, 0xff4B ))
     );
}

?>