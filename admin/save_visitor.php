<?php
session_start();
include_once("../mysql.php");

$centre_code=$_SESSION["CentreCode"];
$name = $tel = $email = $find_out = "";
$number_of_children = $child_birth_year_1 = $child_birth_year_2 = $child_birth_year_3 = $child_birth_year_4 = $child_birth_year_5 = $child_birth_year_6 = "";
$accept_terms = 0;

foreach ($_POST as $key=>$value) {
   $$key=$value;
   $$key=mysqli_real_escape_string($connection, $$key);
}

$symbol = '+';
$country_code = $symbol. $countryCode;
$find_out= $_POST['find_out'];
$find_out = implode ("|||", $find_out);
$date = date('Y-m-d H:i:s');
if ($centre_code!="") {
      $tel = preg_replace('/[^0-9]/', '', $tel);
   if (($name!="") & ($tel!="") & ($nric!="") & ($email!="") & ($number_of_children!="") &($child_name_1!="") & ($child_birth_year_1!="") & ($find_out!="")) {
		if($mode=="new")
			$sql="INSERT into visitor (name, nric, country_code, tel, email, find_out, centre_code, date_created, number_of_children, child_name_1, child_birth_year_1, child_name_2,  child_birth_year_2, child_name_3, child_birth_year_3, child_name_4, child_birth_year_4, child_name_5, child_birth_year_5, child_name_6, child_birth_year_6, accept_terms)
			value ('$name', '$nric', '$tel', '$email', '$find_out', '$centre_code', '$date', '$number_of_children', '$child_name_1', '$child_birth_year_1', '$child_name_2', '$child_birth_year_2', '$child_name_3', '$child_birth_year_3', '$child_name_4', '$child_birth_year_4', '$child_name_5', '$child_birth_year_5','$child_name_6', '$child_birth_year_6', '$accept_terms')";
		else
			$sql = "update visitor set name='$name', nric='$nric', country_code='$country_code', tel='$tel', email='$email', find_out='$find_out', centre_code='$centre_code', date_created='$date', number_of_children='$number_of_children', child_name_1='$child_name_1', 
			child_birth_year_1='$child_birth_year_1', child_name_2='$child_name_2', child_birth_year_2='$child_birth_year_2', child_name_3='$child_name_3', child_birth_year_3='$child_birth_year_3', child_name_4='$child_name_4', child_birth_year_4='$child_birth_year_4', child_name_5='$child_name_5', child_birth_year_5='$child_birth_year_5', child_name_6='$child_name_6', child_birth_year_6='$child_birth_year_6', accept_terms='$accept_terms' where sha1(id)='$id'";
      
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

header("location: ../index.php?p=visitor_update&id=$id&msg=$msg");
?>
