<?php
include_once("mysql.php");

foreach ($_POST as $key=>$value) {
   $$key=$value;
}

if ($centre_code!="") {
   if (($name!="") & ($dob!="") & ($country!="") & ($registered_on!="") & ($race!="") & ($ecp!="") & ($emn!="") & ($eon!="")) {
      $sql="INSERT into student_qr (name, dob, country, registered_on, race, ecp, emn, eon, centre_code) values 
      ('$name', '$dob', '$country', '$registered_on', '$race', '$ecp', '$emn', '$eon', '$centre_code')";
      $result=mysqli_query($connection, $sql);
      if ($result) {
         $msg="Your details saved, thank you for your participation";
      } else {
         $msg="Failed, your details has not been saved";
      }
   } else {
      $msg="Please fill in mandatory fields mark *";
   }
} else {
   $msg="Centre Code not found, please come back again";
}

header("location: student_qr.php?msg=$msg&centre_code=$centre_code");
?>