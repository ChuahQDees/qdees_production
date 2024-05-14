<?php

session_start();

include_once("../mysql.php");

if (isset($_REQUEST["request"]) && $_REQUEST["request"] == 'send_request') {

    if(isset($_FILES['delete_document']['name']) && !empty($_FILES['delete_document']['name']))
    {
        $doc = $_FILES['delete_document']['name'];
        $tmp = $_FILES['delete_document']['tmp_name'];

        $ext = strtolower(pathinfo($doc, PATHINFO_EXTENSION));
    
        $path = 'uploads/';
        $doc_name = time().'.'.$ext;
        $final_doc = $path.$doc_name;

        move_uploaded_file($tmp,$final_doc);
    }
    else
    {
        $doc_name = '';
    }

    $delete_remarks = $_REQUEST['delete_remarks'];

    $sql_TransStudent="UPDATE student set `delete_document` = '".$doc_name."', `delete_remarks` = '".$delete_remarks."', `delete_request` = 3, `request_by` = '".$_SESSION["UserName"]."' where sha1(id)='".$_REQUEST["student_sid"]."'";

    $result=mysqli_query($connection, $sql_TransStudent);  

    $student_data = mysqli_fetch_array(mysqli_query($connection,"SELECT `id`, `name` FROM `student` WHERE sha1(id) = '".$_REQUEST['student_sid']."'"));

    $centre_data = mysqli_fetch_array(mysqli_query($connection,"SELECT `company_name` FROM `centre` WHERE `centre_code` = '".$_SESSION['CentreCode']."'"));

    $n_data['action_id'] = $student_data['id'];
    $n_data['send_to'] = 'hq';
    $n_data['send_from'] = $_SESSION['CentreCode'];
    $n_data['is_center_read'] = 1;
    $n_data['is_hq_read'] = 0;
    $n_data['type'] = "student_delete_request";
    $n_data['subject'] = "Request for delete student ".$student_data['name']." from " . $centre_data['company_name']; 
    notification($n_data);

    if ($result) {
        echo "1|Request send successful";
    } else {
        echo "0|Failed";
    }
} else if (isset($_GET["request"]) && $_GET["request"] == 'approve_request') {

    $sql_TransStudent="UPDATE student set `delete_request` = 1, `deleted` = 1 where sha1(id)='".$_GET["student_sid"]."'";
  
    $result=mysqli_query($connection, $sql_TransStudent);  
  
    $student_data = mysqli_fetch_array(mysqli_query($connection,"SELECT `id`, `name`,`centre_code` FROM `student` WHERE sha1(id) = '".$_GET['student_sid']."'"));
    
      $n_data['action_id'] = $student_data['id'];
      $n_data['send_to'] = $student_data['centre_code'];
      $n_data['send_from'] = 'hq';
      $n_data['is_center_read'] = 0;
      $n_data['is_hq_read'] = 1;
      $n_data['type'] = "approve_student_delete_request";
      $n_data['subject'] = "Request for delete student ".$student_data['name']." is approved"; 
      notification($n_data);
  
      if ($result) {
		  //CHS: Hide Notification from List
		  $sqlUpdateNotification = "UPDATE notifications set hide_from_view = '1' WHERE id = '".$_GET['notification_id']."'";
		  
		  $result2=mysqli_query($connection, $sqlUpdateNotification);  
		   
		if ($result2) {
			echo "1|Approved successful";
		}
      } else {
          echo "0|Failed";
      }
} else if (isset($_GET["request"]) && $_GET["request"] == 'reject_request') {

    $sql_TransStudent="UPDATE student set `delete_request` = 2 where sha1(id)='".$_GET["student_sid"]."'";
  
    $result=mysqli_query($connection, $sql_TransStudent);  
  
    $student_data = mysqli_fetch_array(mysqli_query($connection,"SELECT `id`, `name`,`centre_code` FROM `student` WHERE sha1(id) = '".$_GET['student_sid']."'"));
    
      $n_data['action_id'] = $student_data['id'];
      $n_data['send_to'] = $student_data['centre_code'];
      $n_data['send_from'] = 'hq';
      $n_data['is_center_read'] = 0;
      $n_data['is_hq_read'] = 1;
      $n_data['type'] = "approve_student_delete_request";
      $n_data['subject'] = "Request for delete student ".$student_data['name']." is rejected"; 
      notification($n_data);
  
      if ($result) {
         //CHS: Hide Notification from List
		  $sqlUpdateNotification = "UPDATE notifications set hide_from_view = '1' WHERE id = '".$_GET['notification_id']."'";
		  
		  $result2=mysqli_query($connection, $sqlUpdateNotification);  
		   
		if ($result2) {
			echo "1|Approved successful";
		}
      } else {
          echo "0|Failed";
      }
}
