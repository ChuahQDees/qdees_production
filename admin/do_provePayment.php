<?php

session_start();

include_once("../mysql.php");



foreach($_POST as $key=>$value) {

   $$key=mysqli_real_escape_string($connection, $value); //$sha_id, $sOrderNo, $remarks

}

   $sha_order_no = $_POST['sOrderNo']; 

   $sql="SELECT * from `order` where sha1(order_no)='$sha_order_no'";;

   $result=mysqli_query($connection, $sql);

   $row=mysqli_fetch_assoc($result);

   $id = $row['id'];

   $order_no = $row['order_no'];
   

if ($id!="") {

   $tmp_documents=$_FILES["doc"]["tmp_name"];



    //var_dump($_FILES["doc"]["name"]);die;

   //print_r($tmp_documents);

   $documents = [];

   $count = 0;

   foreach ($tmp_documents as $key => $tmp_document) {

      $count ++;

      $file_extn = explode(".", strtolower($_FILES["doc"]["name"][$key]))[1];
      //print_r($file_extn);
      //$document=$order_no."_".time()."_".$count.".jpg";
		$document=$order_no."_".time()."_".$count.".".$file_extn;
   

      if (is_uploaded_file($tmp_document)) {







         copy($tmp_document, 'uploads/'.$document);



      } else {

         $document[]="";

      }

      array_push($documents , $document);





   }



   $datetime=date("Y-m-d H:i:s");

   $sql="INSERT into prove_payment (order_id, order_no, remark) values 

      ('$id', '$order_no', '$remarks')";

   $result=mysqli_query($connection, $sql);



   $lastID=mysqli_insert_id($connection);

   foreach ($documents as $key => $document) {



// var_dump($document);die;

      $sql="INSERT into prove_payment_doc (prove_payment_id, order_no, doc, update_date, payment_by) values (".$lastID.", '$order_no', '$document', '$datetime', '".$_SESSION["UserName"]."')";

      $result=mysqli_query($connection, $sql);
      //added by Shehab
      $today=date("Y-m-d H:i:s");
      $sql="UPDATE `order` set payment_document='$document', finance_payment_paid_by='".$_SESSION["UserName"]."', 
      finance_payment_paid_on='".$today."' where id='$id'";
      //echo $sql; 
      $result=mysqli_query($connection, $sql);
       //end by Shehab

   }


 
   if ($result) {
	 // Notification start
	   $user_name = $row['user_name'];
	   $get_sql="SELECT `centre_code` FROM `user` where user_name='$user_name'";
	   $f_result=mysqli_query($connection, $get_sql);
	   $u_row=mysqli_fetch_assoc($f_result);
	   $n_data['action_id'] =$order_no;
	   $n_data['send_to'] = "hq";
	   $n_data['send_from'] = $_SESSION['CentreCode'];
	   $n_data['is_center_read'] = 1;
	   $n_data['is_hq_read'] = 0;
	   $n_data['type'] = "payment_request";
	   $n_data['subject'] = "Payment made by ".$row['user_name']." for order no. #" .$order_no; 
	   notification($n_data);
	   // Notification end
      echo "1|Update successfully";

      

   } else {

      echo "0|Updated failed";

   }

} else {

   echo "0|Something is wrong, cannot proceed";

}

?>