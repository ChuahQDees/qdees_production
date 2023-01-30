<?php
session_start();
include_once("../mysql.php");

function getAge($nric_no) {
   $dob = getISODOB($nric_no);
   $dob = explode("-", $dob);

   $age = (date("md", date("U", mktime(0, 0, 0, $dob[2], $dob[1], $dob[0]))) > date("md") 
      ? ((date("Y") - $dob[0]) - 1) 
      : (date("Y") - $dob[0]));

   return $age;
}

function getDOB($nric_no) {
   $year=substr($nric_no, 0, 2);
   $month=substr($nric_no, 2, 2);
   $day=substr($nric_no, 4, 2);

   if ($year>="30") {
      $dob="$day/$month/19"."$year";
   } else {
      $dob="$day/$month/20"."$year";
   }
   
   return $dob;
}

function getISODOB($nric_no) {
   $year=substr($nric_no, 0, 2);
   $month=substr($nric_no, 2, 2);
   $day=substr($nric_no, 4, 2);

   if ($year>="30") {
      $dob="19".$year."-".$month."-".$day;
   } else {
      $dob="20".$year."-".$month."-".$day;
   }
   
   return $dob;
}

//if (($_SESSION["isLogin"]==1) & ($_SESSION["UserType"]=="A")) {
   $nric_no=$_POST["nric_no"];

   if ($nric_no!="") {
      $dob=getDOB($nric_no);
      $age=getAge($nric_no);

      echo "$dob|$age";
   }
//} else {
//   echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
//}
?>