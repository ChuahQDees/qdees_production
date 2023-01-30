<?php

session_start();

include_once("../mysql.php");
include_once("functions.php");

if (isset($_GET["inquiry"]) && (mysqli_real_escape_string($connection,$_GET["inquiry"]) == 'tranfer_student')) {

  $trans_date = getNextYear();

  $sql_TransStudent="UPDATE student set extend_year='$trans_date' where sha1(id)='".$_GET["student_sid"]."'";

  $result=mysqli_query($connection, $sql_TransStudent);  

  if ($result) {

      echo "1|Transfer successful";

   } else {

      echo "0|Transfer failed";

   }

}
else if (isset($_GET["inquiry"]) && (mysqli_real_escape_string($connection,$_GET["inquiry"]) == 'tranfer_multiple_student')) {

   $trans_date = getNextYear();

   $student_sid = explode(",",$_GET["student_sid"]);

   foreach($student_sid as $key => $value)
   {
     $sql_TransStudent="UPDATE student set extend_year='$trans_date' where sha1(id)='".$value."'";

      $result=mysqli_query($connection, $sql_TransStudent);  
   }
   
   echo "1|Transfer successful";

}
