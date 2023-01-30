<?php

session_start();

include_once("../mysql.php");



foreach($_POST as $key=>$value) {

    $$key=mysqli_real_escape_string($connection, $value); //$sha_id, $sOrderNo, $remarks

}

 
if($sha_id == ''){
	$sha_id = $_SESSION["temp_id"]  = time();
} 
if ($sha_id!="") {

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
		$document=$sha_id."_".time()."_".$count.".".$file_extn;
   

      if (is_uploaded_file($tmp_document)) {







         copy($tmp_document, 'uploads/'.$document);



      } else {

         $document[]="";

      }

      array_push($documents , $document);





   }



   $datetime=date("Y-m-d H:i:s");
   
   foreach ($documents as $key => $document) {



// var_dump($document);die;

       $sql="INSERT into declaration_doc (declaration_id, remarks, doc, update_date, created_by,centre_code,form) values (".$sha_id.", '$remarks', '$document', '$datetime', '".$_SESSION["UserName"]."','".$_SESSION["CentreCode"]."','".$form."')";

      $result=mysqli_query($connection, $sql);
     

   }


 
   if ($result) {

      echo "1|Update successfully";

      

   } else {

      echo "0|Updated failed";

   }

} else {

   echo "0|Something is wrong, cannot proceed";

}

?>