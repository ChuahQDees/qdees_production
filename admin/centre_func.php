<?php
$table="centre";
$key_field="centre_code";
$msg="";

function isStatusOK($status, $other_status) {
   if ($status=="Others") {
      if ($other_status!="") {
         return true;
      } else {
         return false;
      }
   } else {
      if ($status!="") {
         return true;
      } else {
         return false;
      }
   }
}

function isRecordFound($table, $key_field, $key_value) {
   global $connection;

   $sql="SELECT $key_field from `$table` where $key_field='$key_value'";
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);
   //echo $sql; die;

   if ($num_row>0) {
      return true;
   } else {
      return false;
   }
}

if ($mode1=="SAVE_CENTRE") {
   foreach ($_POST as $key=>$value) {
      $$key=mysqli_real_escape_string($connection, $value);
   }

   $year_of_commencement=convertDate2ISO($year_of_commencement);
   $expiry_date=convertDate2ISO($expiry_date);

   if (isRecordFound($table, $key_field, $hidden_centre_code)) {
     
      if(($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")){

         $update_sql="UPDATE `$table` set bank_detail='$bank_detail' where centre_code='$hidden_centre_code'";
         $result=mysqli_query($connection, $update_sql);

         $msg="Record updated";

      }  
   }  
}

if ($mode=="EDIT") {
   if ($get_sha1_id!="") {
      $edit_sql="SELECT * from `$table` where sha1(id)='$get_sha1_id'";
      $result=mysqli_query($connection, $edit_sql);
      $edit_row=mysqli_fetch_assoc($result);
	  
   }
}

if ($mode=="DEL") {
   if ($get_sha1_id!="") {
      $del_sql="DELETE from `$table` where sha1(id)='$get_sha1_id'";
      $result=mysqli_query($connection, $del_sql);
      $msg="Record deleted";
   }
}

if ($mode=="SAVE") {
   foreach ($_POST as $key=>$value) {
      $$key=mysqli_real_escape_string($connection, $value);
   }

   $year_of_commencement=convertDate2ISO($year_of_commencement);
   $expiry_date=convertDate2ISO($expiry_date);

   if (isRecordFound($table, $key_field, $hidden_centre_code)) {
      // echo "$centre_code|$kindergarten_name|$upline|$year_of_commencement|$year_of_renewal|$expiry_date|$SSM_file|
      // $MOE_license_file|$operator_name|$operator_nric|$operator_contact_no|$principle_name|$principle_contact_no|$status|
      // $other_status|$can_adjust_fee|$can_adjust_product|$address1|$address2|$country|$state";
      // if (($centre_code!="") & ($kindergarten_name!="") & ($upline!="") & ($year_of_commencement!="") & ($year_of_renewal!="") &
      //    ($expiry_date!="") & ($SSM_file!="") & ($MOE_license_file!="") & ($operator_name!="") & ($operator_nric!="") &
      //    ($operator_contact_no!="") & ($principle_name!="") & ($principle_contact_no!="") & (isStatusOK($status, $other_status)) &
      //    ($can_adjust_fee!="") & ($can_adjust_product!="") & ($address1!="") & ($address2!="") & ($country!="") & ($state!="")) {

      // if (($centre_code!="") & ($kindergarten_name!="") & ($upline!="") & ($year_of_commencement!="") & ($year_of_renewal!="") &
      //    ($expiry_date!="") & ($operator_name!="") & ($operator_nric!="") &
      //    ($operator_contact_no!="") & ($principle_name!="") & ($principle_contact_no!="") & (isStatusOK($status, $other_status)) &
      //    ($can_adjust_fee!="") & ($can_adjust_product!="") & ($address1!="") & ($address2!="") & ($country!="") & ($state!="")) {
//$subject= $_POST['subject']; 
//$subject = implode(', ', $subject); 
//echo $subject; die;

      if ($centre_code!="") {
         $update_sql="UPDATE `$table` set
         kindergarten_name='$kindergarten_name', company_name='$company_name', upline='$upline', year_of_commencement='$year_of_commencement', year_of_renewal='$year_of_renewal', `expiry_date`='$expiry_date', operator_name='$operator_name', operator_nric='$operator_nric', operator_contact_no='$operator_contact_no', principle_name='$principle_name', principle_contact_no='$principle_contact_no', assistant_name='$assistant_name', ANP_tel='$ANP_tel', personal_tel='$personal_tel', `status`='$status', other_status='$other_status',
         ANP_email='$ANP_email', company_email='$company_email', can_adjust_fee='$can_adjust_fee', can_adjust_product='$can_adjust_product', address1='$address1', address2='$address2', address3='$address3', address4='$address4', address5='$address5', country='$country', `state`='$state', franchisor_company_name='$franchisor_company_name', centre_franchisee_name_id='$centre_franchisee_name_id', centre_franchisee_company_id='$centre_franchisee_company_id', registration_fee='$registration_fee', centre_status_id='$status_center', pic='$pic', centre_code='$centre_code', bank_detail='$bank_detail' where centre_code='$hidden_centre_code'";
		
         $result=mysqli_query($connection, $update_sql);
		
         if(mysqli_num_rows(mysqli_query($connection,"SELECT * FROM `schedule_term` WHERE `centre_code` = '$hidden_centre_code'")) < 1) 
         {
            $schedule_term_data = mysqli_query($connection,"SELECT * FROM `schedule_term` WHERE `centre_code` = 'MYQWESTC1C10001' AND `deleted` = '0' ORDER BY `id` ASC");

            while($schedule_term_row = mysqli_fetch_array($schedule_term_data))
            {
               mysqli_query($connection,"INSERT into `schedule_term` (`centre_code`,`term`, `term_start`, `term_end`, `year`, `term_num`) values ('".$hidden_centre_code."','".$schedule_term_row['term']."', '".$schedule_term_row['term_start']."', '".$schedule_term_row['term_end']."', '".$schedule_term_row['year']."','".$schedule_term_row['term_num']."')");
            }
         }

         $tmp_document=$_FILES["SSM_file"]["tmp_name"];
         $SSM_file_name=generateRandomString(8).".pdf";
         if (is_uploaded_file($tmp_document)) {
            copy($tmp_document, 'admin/uploads/'.$SSM_file_name);
            $sql="UPDATE `$table` set SSM_file='$SSM_file_name' where centre_code='$hidden_centre_code'";
            mysqli_query($connection, $sql);
         }

         $tmp_document=$_FILES["MOE_license_file"]["tmp_name"];
         $MOE_license_file_name=generateRandomString(8).".pdf";
         if (is_uploaded_file($tmp_document)) {
            copy($tmp_document, 'admin/uploads/'.$MOE_license_file_name);
            $sql="UPDATE `$table` set MOE_license_file='$MOE_license_file_name' where centre_code='$hidden_centre_code'";
            mysqli_query($connection, $sql);
         }

         $msg="Record updated";
      } else {
         $msg="All fields are required";
      }
   } else {
      // echo "$centre_code|$kindergarten_name|$upline|$year_of_commencement|$year_of_renewal|$expiry_date|$SSM_file|
      // $MOE_license_file|$operator_name|$operator_nric|$operator_contact_no|$principle_name|$principle_contact_no|$status|
      // $other_status|$can_adjust_fee|$can_adjust_product|$address1|$address2|$country|$state";
      // if (($centre_code!="") & ($kindergarten_name!="") & ($upline!="") & ($year_of_commencement!="") & ($year_of_renewal!="") &
      //    ($expiry_date!="") & ($SSM_file!="") & ($MOE_license_file!="") & ($operator_name!="") & ($operator_nric!="") &
      //    ($operator_contact_no!="") & ($principle_name!="") & ($principle_contact_no!="") & (isStatusOK($status, $other_status)) &
      //    ($can_adjust_fee!="") & ($can_adjust_product!="") & ($address1!="") & ($address2!="") & ($country!="") & ($state!="")) {

      // if (($centre_code!="") & ($kindergarten_name!="") & ($upline!="") & ($year_of_commencement!="") & ($year_of_renewal!="") &
      //    ($expiry_date!="") & ($operator_name!="") & ($operator_nric!="") &
      //    ($operator_contact_no!="") & ($principle_name!="") & ($principle_contact_no!="") & (isStatusOK($status, $other_status)) &
      //    ($can_adjust_fee!="") & ($can_adjust_product!="") & ($address1!="") & ($address2!="") & ($country!="") & ($state!="")) {
$subject= $_POST['subject']; 
$subject = implode(', ', $subject);
 
      if ($centre_code!="") {
		  if($registration_fee == '') $registration_fee = 0;
		  if($year_of_renewal == '') $year_of_renewal = 0;
		  if($year_of_commencement == '') $year_of_commencement = '0000-00-00';
		  if($year_of_commencement == '') $year_of_commencement = '0000-00-00';
		  if($expiry_date == '') $expiry_date = '0000-00-00';
          $insert_sql="INSERT into `$table` (
         centre_code, kindergarten_name, company_name, upline, year_of_commencement, year_of_renewal, expiry_date,
         SSM_file, MOE_license_file, operator_name, operator_nric, operator_contact_no, principle_name,
         principle_contact_no, assistant_name, ANP_tel, personal_tel, status, other_status, ANP_email, company_email,
         can_adjust_fee, can_adjust_product, address1, address2, address3, address4, address5, country, state,
         franchisor_company_name, centre_franchisee_company_id, centre_franchisee_name_id, registration_fee, `centre_status_id`, `pic`,`bank_detail`)
         values (
         '$centre_code', '$kindergarten_name', '$company_name', '$upline', '$year_of_commencement', '$year_of_renewal', '$expiry_date',
         '$SSM_file', '$MOE_license_file', '$operator_name', '$operator_nric', '$operator_contact_no', '$principle_name',
         '$principle_contact_no', '$assistant_name', '$ANP_tel', '$personal_tel', '$status', '$other_status', '$ANP_email', '$company_email',
         '$can_adjust_fee', '$can_adjust_product', '$address1', '$address2', '$address3', '$address4', '$address5', '$country', '$state',
         '$franchisor_company_name', '$centre_franchisee_company_id', '$centre_franchisee_name_id', '$registration_fee', '$status_center', '$pic','$bank_detail')";

         $result=mysqli_query($connection, $insert_sql);
		 
         $schedule_term_data = mysqli_query($connection,"SELECT * FROM `schedule_term` WHERE `centre_code` = 'MYQWESTC1C10001' AND `deleted` = '0' ORDER BY `id` ASC");

         while($schedule_term_row = mysqli_fetch_array($schedule_term_data))
         {
            mysqli_query($connection,"INSERT into `schedule_term` (`centre_code`,`term`, `term_start`, `term_end`, `year`, `term_num`) values ('".$centre_code."','".$schedule_term_row['term']."', '".$schedule_term_row['term_start']."', '".$schedule_term_row['term_end']."', '".$schedule_term_row['year']."','".$schedule_term_row['term_num']."')");
         }

		  $tmp_document=$_FILES["SSM_file"]["tmp_name"];
         $SSM_file_name=generateRandomString(8).".pdf";
         if (is_uploaded_file($tmp_document)) {
            copy($tmp_document, 'admin/uploads/'.$SSM_file_name);
            $sql="UPDATE `$table` set SSM_file='$SSM_file_name' where centre_code='$centre_code'";
            mysqli_query($connection, $sql);
         }

         $tmp_document=$_FILES["MOE_license_file"]["tmp_name"];
         $MOE_license_file_name=generateRandomString(8).".pdf";
         if (is_uploaded_file($tmp_document)) {
            copy($tmp_document, 'admin/uploads/'.$MOE_license_file_name);
            $sql="UPDATE `$table` set MOE_license_file='$MOE_license_file_name' where centre_code='$centre_code'";
            mysqli_query($connection, $sql);
         }
		 
         $msg="Record inserted";
 
      } else {
         $msg="All fields are required";
      }
   }
}

$year=$_SESSION['Year'];
$str=$_GET["s"];
// if ($_GET["s"]!="") {
   // $browse_sql="SELECT cn.*, cs.name, cn.centre_code, cnm.franchisee_name, cnm.franchisee_passport  from `$table` cn left join centre_status cs on cs.id=cn.centre_status_id left join (select * from centre_franchisee_name) cnm on cnm.id=cn.centre_franchisee_name_id where  year(created_date) <= '$year' and status='A' and kindergarten_name like '%$str%' or state like '%$str%' or ANP_tel like '%$str%'
   // or operator_name like '%$str%' or operator_contact_no like '%$str%' or principle_name like '%$str%' or principle_contact_no like '%$str%' or personal_tel like '%$str%' or ANP_email like '%$str%' or name like '%$str%' or company_email like '%$str%' or country like '%$str%'  or company_name like '%$str%' order by kindergarten_name";
    
// } else {
   // $browse_sql="SELECT cn.*, cs.name, cn.centre_code, cnm.franchisee_name, cnm.franchisee_passport from `$table` cn left join centre_status cs on cs.id=cn.centre_status_id left join (select * from centre_franchisee_name) cnm on cnm.id=cn.centre_franchisee_name_id where   year(created_date) <= '$year' and status='A' order by kindergarten_name";
// }
if ($_GET["s"]!="") {
   $browse_sql="SELECT cn.*, cs.name, cn.centre_code, cnm.franchisee_company_name from `$table` cn left join centre_status cs on cs.id=cn.centre_status_id left join (select * from centre_franchisee_company) cnm on cnm.centre_code=cn.centre_code where  created_date <= '".$year_end_date." 23:59:59' and status='A' and kindergarten_name like '%$str%' or state like '%$str%' or ANP_tel like '%$str%'
   or operator_name like '%$str%' or operator_contact_no like '%$str%' or principle_name like '%$str%' or principle_contact_no like '%$str%' or personal_tel like '%$str%' or ANP_email like '%$str%' or name like '%$str%' or company_email like '%$str%' or country like '%$str%'  or company_name like '%$str%' or cn.centre_code like '%$str%' order by kindergarten_name";
    
} else {
   $browse_sql="SELECT cn.*, cs.name, cn.centre_code, cnm.franchisee_company_name from `$table` cn left join centre_status cs on cs.id=cn.centre_status_id left join (select * from centre_franchisee_company) cnm on cnm.centre_code=cn.centre_code where created_date <= '".$year_end_date." 23:59:59' and status='A' order by kindergarten_name";
}

?>