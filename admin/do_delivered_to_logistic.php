<?php
session_start();
include_once("../mysql.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);
}

// $sha_id=$_POST["sha_id"]; die;
// $name=$_POST["name"];
// $ic_no=$_POST["ic_no"];
// $tracking_no=$_POST["tracking_no"];
// $courier=$_POST["courier"];
// $jsonSignature=$_POST["jsonSignature"];
// $sOrderNo=$_POST["sOrderNo"];

//if (($sOrderNo!="") & ($name!="") & ($ic_no!="") & ($tracking_no!="") & ($jsonSignature!="") & ($courier!="")) {
   $datetime=date("Y-m-d H:i:s");
   $sql="UPDATE `order` set delivered_to_logistic_by='".$_SESSION["UserName"]."',
   delivered_to_logistic_on='$datetime', name='$name', ic_no='$ic_no', signature='$jsonSignature',
   tracking_no='$tracking_no', courier='$courier', remarks_delivery='$remarks' where sha1(order_no)='$sOrderNo'";
  
   $result=mysqli_query($connection, $sql);
   
   // Notification start
	$get_sql="SELECT `order_no`,`centre_code` FROM `order` where sha1(order_no)='$sOrderNo'";
	$f_result=mysqli_query($connection, $get_sql);
	$row=mysqli_fetch_assoc($f_result);
	$n_data['action_id'] =$row['order_no'];
	$n_data['send_to'] = $row['centre_code'];
	$n_data['send_from'] = $_SESSION['CentreCode'];
    $n_data['is_center_read'] = 0;
    $n_data['is_hq_read'] = 1;
	$n_data['type'] = "order_delivered";
	$n_data['subject'] = "Your sales order no. " .$row['order_no']." has been delivered"; 
	notification($n_data);
	// Notification end
 //echo $result; die;
   if ($result) {
      echo "1|Update successfully";
   } else {
      echo "0|Updated failed";
   }
//} else {
//   echo "0|$sha_id|$name|$ic_no";
  // echo "0|Something is wrong, cannot proceed";
//}
?>