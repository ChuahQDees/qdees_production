<?php
session_start();
include_once("../mysql.php");

foreach($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //$sha_id, $sOrderNo, $reason
}

if(isset($_POST['approve']) && $_POST['approve'] == '1')
{
   $datetime=date("Y-m-d H:i:s");

   $sql="UPDATE `order` set cancelled_by='".$_SESSION["UserName"]."', cancelled_on='".$datetime."', cancel_reason = cancel_request_reason, approve_cancellation = 1 where sha1(order_no)='$sOrderNo' AND sha1(id) = '".$id."'";
   
   $result=mysqli_query($connection, $sql);

   if ($result) {
		// Notification start
	 	$get_sql="SELECT `order_no`,`centre_code` FROM `order` where sha1(id)='$id'";
		$f_result=mysqli_query($connection, $get_sql);
		$row=mysqli_fetch_assoc($f_result);
		$n_data['action_id'] =$row['order_no'];
		$n_data['send_to'] = $row['centre_code'];
		$n_data['send_from'] = "hq";
		$n_data['is_center_read'] = 0;
		$n_data['is_hq_read'] = 1;
		$n_data['type'] = "order_cancel_approve";
		$n_data['subject'] = "Your sales order no. " .$row['order_no']. " item cancel request has been approved."; 
	//	print_r($n_data);
		notification($n_data);
		// Notification end
      echo "1|Approve successfully";
   } else {
      echo "0|Updated failed";
   }
}
else if(isset($_POST['reject']) && $_POST['reject'] == 1)
{
   $datetime=date("Y-m-d H:i:s");

   $sql="UPDATE `order` set approve_cancellation = 2, request_reject_reason='$reason' where sha1(order_no)='$sOrderNo' AND sha1(id) = '".$id."'";
   
   $result=mysqli_query($connection, $sql);

   if ($result) {
	// Notification start
	$get_sql="SELECT `order_no`,`centre_code` FROM `order` where sha1(id)='$id'";
	$f_result=mysqli_query($connection, $get_sql);
	$row=mysqli_fetch_assoc($f_result);
	$n_data['action_id'] =$row['order_no'];
	$n_data['send_to'] = $row['centre_code'];
	$n_data['send_from'] = "hq";
	$n_data['is_center_read'] = 0;
	$n_data['is_hq_read'] = 1;
	$n_data['type'] = "order_cancel_reject";
	$n_data['subject'] = "Your sales order no. " .$row['order_no']. " item cancel request has been rejected."; 
	notification($n_data);
	// Notification end
      echo "1|Reject successfully";
   } else {
      echo "0|Updated failed";
   }
}
else
{
   if($id == '')
   {
      $datetime=date("Y-m-d H:i:s");
      $sql="UPDATE `order` set cancelled_by='".$_SESSION["UserName"]."', cancelled_on='".$datetime."', cancel_reason='$reason' 
      where sha1(order_no)='$sOrderNo' AND (`cancelled_by` IS NULL OR `cancelled_by` = '' OR `cancelled_by` = ' ')";

      $result=mysqli_query($connection, $sql);

      if ($result) {
		// Notification start
		$get_sql="SELECT `order_no`,`centre_code` FROM `order` where sha1(order_no)='$sOrderNo'";
		$f_result=mysqli_query($connection, $get_sql);
		$row=mysqli_fetch_assoc($f_result);
		$n_data['action_id'] =$row['order_no'];
		$n_data['send_to'] = $row['centre_code'];
		$n_data['send_from'] = "hq";
		$n_data['is_center_read'] = 0;
		$n_data['is_hq_read'] = 1;
		$n_data['type'] = "order_cancel_approve";
		$n_data['subject'] = "Your sales order no. " .$row['order_no']. " cancel request has been approved."; 
		notification($n_data);
		// Notification end
         echo "1|Update successfully";
      } else {
         echo "0|Updated failed";
      }
   }
   else if ($id!="") {
      $datetime=date("Y-m-d H:i:s");

      if($_SESSION["UserType"]!="S")
      {
         $sql="UPDATE `order` set request_by='".$_SESSION["UserName"]."',  cancel_request_reason='$reason', approve_cancellation = 0 where sha1(order_no)='$sOrderNo' AND sha1(id) = '".$id."'";
		// Notification start
		$get_sql="SELECT `order_no`,`centre_code` FROM `order` where sha1(id)='$id'";
		$f_result=mysqli_query($connection, $get_sql);
		$row=mysqli_fetch_assoc($f_result);
		$n_data['action_id'] =$row['order_no'];
		$n_data['send_to'] = 'hq';
		$n_data['send_from'] = $_SESSION['CentreCode'];
		$n_data['is_center_read'] = 1;
		$n_data['is_hq_read'] = 0;
		$n_data['type'] = "order_item_cancel_request";
		$n_data['subject'] = "Item cancel request for sales order no. " .$row['order_no']; 
		notification($n_data);
		// Notification end
      }
      else
      {
         $sql="UPDATE `order` set cancelled_by='".$_SESSION["UserName"]."', cancelled_on='".$datetime."', cancel_reason='$reason' where sha1(order_no)='$sOrderNo' AND sha1(id) = '".$id."'";
	    // Notification start
		$get_sql="SELECT `order_no`,`centre_code` FROM `order` where sha1(id)='$id'";
		$f_result=mysqli_query($connection, $get_sql);
		$row=mysqli_fetch_assoc($f_result);
		$n_data['action_id'] =$row['order_no'];
		$n_data['send_to'] = $row['centre_code'];
		$n_data['send_from'] = 'hq';
		$n_data['is_center_read'] = 0;
		$n_data['is_hq_read'] = 1;
		$n_data['type'] = "order_item_cancel";
		$n_data['subject'] = "Item cancel from sales order no. " .$row['order_no']; 
		notification($n_data);
		// Notification end
      }
      
      $result=mysqli_query($connection, $sql);

      if ($result) {
	
         echo "1|Update successfully";
      } else {
         echo "0|Updated failed";
      }
   } else {
      echo "0|Something is wrong, cannot proceed";
   }
}

?>
