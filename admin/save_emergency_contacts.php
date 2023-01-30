<?php
session_start();
include_once("../mysql.php");

$form_mode=$_POST["form_mode"];
$mode=$_POST["mode"];

if ($_SESSION["isLogin"] && $form_mode!='qr') {
  $table="student_emergency_contacts";
} else {
   $table="tmp_student_emergency_contacts";
}

$student_code=$_POST["student_code"];
$form_mode=$_POST["form_mode"];

function deleteContact($student_code) {
   global $connection, $table;

   $sql="DELETE from `$table` where student_code='$student_code'";
   //echo $sql;
   $result=mysqli_query($connection, $sql);

   if ($result) {
      return true;
   } else {
      return false;
   }
}

deleteContact($student_code);

foreach ($_POST as $key=>$value) {
   $$key=$value;
}

   // $sql="SELECT * from `$table` where student_code='$student_code'";
   // $result=mysqli_query($connection, $sql);
   // if (!$result) {
      $i=0;
foreach ($contact_type as $key=>$no) {
   // var_dump($nric[$key]);die;
   $sql="SELECT * from `$table` where nric='$nric[$key]' order by id ASC";
   //echo $sql;
   $result=mysqli_query($connection, $sql);
	 $row=mysqli_fetch_assoc($result);
 if (count($row)>0 && $nric[$key] != '') {
    if($i==0){
      $i++;
      $_full_name = $row["full_name"];
    }
	 	//echo $mode;
   if($mode=="EDIT"){
      //return $row["id"];
      $sql="INSERT into `$table` (student_code, contact_type, full_name, nric, email, mobile_country_code, mobile, occupation, education_level,
      can_pick_up, vehicle_no, remarks) values ('$student_code', ";
      $sql.="'". (! empty($contact_type[$key]) ? $contact_type[$key] : '') ."',";
      $sql.="'". (! empty($full_name[$key]) ? $full_name[$key] : '') ."',";
      $sql.="'". (! empty($row["nric"]) ? $row["nric"] : '') ."',";
      $sql.="'". (! empty($email[$key]) ? $email[$key] : '') ."',";
      $sql.="'". (! empty($mobile_country_code[$key]) ? $mobile_country_code[$key] : '') ."',";
      $sql.="'". (! empty($mobile[$key]) ? $mobile[$key] : '') ."',";
      $sql.="'". (! empty($occupation[$key]) ? $occupation[$key] : '') ."',";
      $sql.="'". (! empty($education_level[$key]) ? $education_level[$key] : '') ."',";
      $sql.="'". (! empty($can_pick_up[$key]) ? $can_pick_up[$key] : '') ."',";
      $sql.="'". (! empty($vehicle_no[$key]) ? $vehicle_no[$key] : '') ."',";
      $sql.="'". (! empty($remarks[$key]) ? $remarks[$key] : '') ."')";
      mysqli_query($connection, $sql);
      //echo $sql;
      $msg = $full_name[0];
   }else{
      //return $row["id"];
      $sql="INSERT into `$table` (student_code, contact_type, full_name, nric, email, mobile_country_code, mobile, occupation, education_level,
      can_pick_up, vehicle_no, remarks) values ('$student_code', ";
      $sql.="'". (! empty($row["contact_type"]) ? $row["contact_type"] : '') ."',";
      $sql.="'". (! empty($row["full_name"]) ? $row["full_name"] : '') ."',";
      $sql.="'". (! empty($row["nric"]) ? $row["nric"] : '') ."',";
      $sql.="'". (! empty($row["email"]) ? $row["email"] : '') ."',";
      $sql.="'". (! empty($row["mobile_country_code"]) ? $row["mobile_country_code"] : '') ."',";
      $sql.="'". (! empty($row["mobile"]) ? $row["mobile"] : '') ."',";
      $sql.="'". (! empty($row["occupation"]) ? $row["occupation"] : '') ."',";
      $sql.="'". (! empty($row["education_level"]) ? $row["education_level"] : '') ."',";
      $sql.="'". (! empty($row["can_pick_up"]) ? $row["can_pick_up"] : '0') ."',";
      $sql.="'". (! empty($row["vehicle_no"]) ? $row["vehicle_no"] : '') ."',";
      $sql.="'". (! empty($row["remarks"]) ? $row["remarks"] : '') ."')";

      mysqli_query($connection, $sql);

      $msg = $_full_name;
   }
   
	 
   
}else{
  $sql="INSERT into `$table` (student_code, contact_type, full_name, nric, email, mobile_country_code, mobile, occupation, education_level,
   can_pick_up, vehicle_no, remarks) values ('$student_code', ";
   $sql.="'". (! empty($contact_type[$key]) ? $contact_type[$key] : '') ."',";
   $sql.="'". (! empty($full_name[$key]) ? $full_name[$key] : '') ."',";
   $sql.="'". (! empty($nric[$key]) ? $nric[$key] : '') ."',";
   $sql.="'". (! empty($email[$key]) ? $email[$key] : '') ."',";
   $sql.="'". (! empty($mobile_country_code[$key]) ? $mobile_country_code[$key] : '') ."',";
   $sql.="'". (! empty($mobile[$key]) ? $mobile[$key] : '') ."',";
   $sql.="'". (! empty($occupation[$key]) ? $occupation[$key] : '') ."',";
   $sql.="'". (! empty($education_level[$key]) ? $education_level[$key] : '') ."',";
   $sql.="'". (! empty($can_pick_up[$key]) ? $can_pick_up[$key] : '0') ."',";
   $sql.="'". (! empty($vehicle_no[$key]) ? $vehicle_no[$key] : '') ."',";
   $sql.="'". (! empty($remarks[$key]) ? $remarks[$key] : '') ."')";

   mysqli_query($connection, $sql);

    $msg = 'Contact saved';
    //$msg = $full_name[$key];
}

   
   }
   // }else{
	   
	   
	 // foreach ($contact_type as $key=>$no) {  
	     // $sql="INSERT into `$table` (student_code, contact_type, full_name, nric, email, mobile_country_code, mobile, occupation, education_level,
   // can_pick_up, vehicle_no, remarks) values ('$student_code', ";
   // $sql.="'". (! empty($contact_type[$key]) ? $contact_type[$key] : '') ."',";
   // $sql.="'". (! empty($full_name[$key]) ? $full_name[$key] : '') ."',";
   // $sql.="'". (! empty($nric[$key]) ? $nric[$key] : '') ."',";
   // $sql.="'". (! empty($email[$key]) ? $email[$key] : '') ."',";
   // $sql.="'". (! empty($mobile_country_code[$key]) ? $mobile_country_code[$key] : '') ."',";
   // $sql.="'". (! empty($mobile[$key]) ? $mobile[$key] : '') ."',";
   // $sql.="'". (! empty($occupation[$key]) ? $occupation[$key] : '') ."',";
   // $sql.="'". (! empty($education_level[$key]) ? $education_level[$key] : '') ."',";
   // $sql.="'". (! empty($can_pick_up[$key]) ? $can_pick_up[$key] : '0') ."',";
   // $sql.="'". (! empty($vehicle_no[$key]) ? $vehicle_no[$key] : '') ."',";
   // $sql.="'". (! empty($remarks[$key]) ? $remarks[$key] : '') ."')";

   // mysqli_query($connection, $sql);

    // $msg = 'Contact saved';
	 // }
   // }

echo $msg;
?>
