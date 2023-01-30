<?php
include_once("mysql.php");

$name = $tel = $email = $find_out = $centre_code = "";
// $number_of_children = $child_name_1 = $child_name_2 = $child_name_3 = $child_name_4 = $child_name_5 = $child_name_6 = "";
$number_of_children = $child_birth_year_1 = $child_birth_year_2 = $child_birth_year_3 = $child_birth_year_4 = $child_birth_year_5 = $child_birth_year_6 = "";
$accept_terms = 0;

foreach ($_POST as $key=>$value) {
   $$key=$value;
   $$key=mysqli_real_escape_string($connection, $$key);
}
$symbol = '+';
$country_code = $symbol. $countryCode;
// var_dump($country_code);die;
$find_out= $_POST['find_out'];
$find_out = implode ("|||", $find_out);
$date = date('Y-m-d H:i:s');
//echo $date; die;
if ($centre_code!="") {
      $tel = preg_replace('/[^0-9]/', '', $tel);
//   if (($name!="") & ($tel!="") & ($age!="") & ($find_out!="")) {
      $sql="INSERT into visitor (name, nric, country_code, tel, email, find_out, centre_code, date_created, number_of_children, child_name_1, child_birth_year_1, child_name_2, child_birth_year_2, child_name_3, child_birth_year_3, child_name_4, child_birth_year_4, child_name_5, child_birth_year_5, child_name_6, child_birth_year_6, accept_terms)
      value ('$name', '$nric','$country_code', '$tel', '$email', '$find_out', '$centre_code','$date', '$number_of_children',  '$child_name_1', '$child_birth_year_1','$child_name_2', '$child_birth_year_2', '$child_name_3', '$child_birth_year_3', '$child_name_4','$child_birth_year_4','$child_name_5', '$child_birth_year_5','$child_name_6', '$child_birth_year_6', '$accept_terms')";
	//echo $sql; die;
      $result=mysqli_query($connection, $sql);
      if ($result) {
         $msg="Your details saved, thank you for your participation";
      } else {
         $msg="Failed, your details has not been saved";
      }
	  
	  // echo $sql; die;
//   } else {
//      $msg="Please fill in mandatory fields mark *";
//   }
} else {
   $msg="Centre Code not found, please come back again";
}

header("location: visitor_qr.php?msg=$msg&centre_code=$centre_code");
?>
