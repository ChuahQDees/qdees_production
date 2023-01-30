<?php
session_start();
include_once("../mysql.php");

$name= $_GET['name'];
$get_sha1_id= $_GET['id'];
$is_fee_structure= $_GET['is_fee_structure'];

if($is_fee_structure == 1){
	if($get_sha1_id){
		$edit_sql="SELECT * from `fee_structure` where fees_structure='$name' AND sha1(id) != '$get_sha1_id'";
	}else{
		$edit_sql="SELECT * from `fee_structure` where fees_structure='$name'";
		
	}
}else{
	if($get_sha1_id){
		$edit_sql="SELECT * from `fee` where fees_structure='$name' AND sha1(id) != '$get_sha1_id'";
	}else{
		$edit_sql="SELECT * from `fee` where fees_structure='$name'";
		
	}
}
$result=mysqli_query($connection, $edit_sql);
$edit_row=mysqli_fetch_assoc($result); 

if ($edit_row) { 
  $data['status'] = 0;
} else {
	$data['status'] = 1;
} 
echo json_encode($data);
?>