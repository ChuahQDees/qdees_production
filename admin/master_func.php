<?php
$table="master";
$key_field="master_code";
//$centre_code=$_SESSION["CentreCode"];
$msg="";

function isRecordFound($table, $key_field, $key_value) {
   global $connection;

   $sql="SELECT $key_field from `$table` where $key_field='$key_value'";
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      return true;
   } else {
      return false;
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

   if (isRecordFound($table, $key_field, $master_code)) {
//      echo "$master_code|$mastertype|$country|$state|$upline|$franchise_fee|$year_of_commencement|$expiry_date|$year_of_renewal|$add1|$add2|$tel1|$fax";
//      if (($master_code!="") & ($mastertype!="") & ($country!="") & ($upline!="") & ($franchise_fee!="") &
//         ($year_of_commencement!="") & ($expiry_date!="") & ($add1!="") & ($add2!="") & ($tel1!="") & ($fax!="")) {
      if ($master_code!="") {
         $update_sql="UPDATE `$table` set
         mastertype='$mastertype', company_name='$company_name', company_no='$company_no', country='$country', `state`='$state',
         year_of_commencement='$year_of_commencement', year_of_renewal='$year_of_renewal', franchise_fee='$franchise_fee', 
         `expiry_date`='$expiry_date', sign_with='$sign_with', add1='$add1', add2='$add2', add3='$add3', add4='$add4',
         tel1='$tel1', tel2='$tel2', fax='$fax', number_of_master_franchisee='$number_of_master_franchisee', upline='$upline',
         franchisor_company_name='$franchisor_company_name', franchisor_company_no='$franchisor_company_no',
         franchisor_registered_address1='$franchisor_registered_address1', franchisor_registered_address2='$franchisor_registered_address2',
         franchisor_registered_address3='$franchisor_registered_address3', franchisor_registered_address4='$franchisor_registered_address4',
         master_franchisee_name_id='$master_franchisee_name_id', master_franchisee_company_id='$master_franchisee_company_id', 
         remarks='$remarks', centre_status_id='$status_center', pic='$pic' where master_code='$master_code'";
//echo $update_sql;
         $result=mysqli_query($connection, $update_sql);
         $msg="Record updated";
      } else {
         $msg="Master Code is required";
      }
   } else {
//echo "$master_code|$mastertype|$company_no|$add1|$add2|$tel1|$fax|$year_of_commencement|$year_of_renewal|$expiry_date|$upline|$franchise_fee";
//      if (($master_code!="") & ($mastertype!="") & ($country!="") & ($upline!="") & ($franchise_fee!="") &
//         ($year_of_commencement!="") & ($expiry_date!="") & ($add1!="") & ($add2!="") & ($tel1!="") & ($fax!="")) {
      if ($master_code!="") {
         $insert_sql="INSERT into $table
         (`master_code`, `mastertype`, `company_name`, `company_no`, `country`, `state`, `year_of_commencement`, `year_of_renewal`,
         `franchise_fee`, `expiry_date`, `sign_with`, `add1`, `add2`, `add3`, `add4`, `tel1`,
         `tel2`, `fax`, `number_of_master_franchisee`, `upline`, `franchisor_company_name`, `franchisor_company_no`,
         `franchisor_registered_address1`, `franchisor_registered_address2`, `franchisor_registered_address3`,
         `franchisor_registered_address4`, `master_franchisee_name_id`, `master_franchisee_company_id`, `remarks`, `centre_status_id`, `pic`)
         values
         ('$master_code', '$mastertype', '$company_name', '$company_no', '$country', '$state', '$year_of_commencement', '$year_of_renewal',
         '$franchise_fee', '$expiry_date', '$sign_with', '$add1', '$add2', '$add3', '$add4', '$tel1',
         '$tel2', '$fax', '$number_of_master_franchisee', '$upline', '$franchisor_company_name', '$franchisor_company_no',
         '$franchisor_registered_address1', '$franchisor_registered_address2', '$franchisor_registered_address3',
         '$franchisor_registered_address4', '$master_franchisee_name_id', '$master_franchisee_company_id', '$remarks', '$status_center', '$pic')";

//echo $insert_sql;
         $result=mysqli_query($connection, $insert_sql);
         $msg="Record inserted";
      } else {
         $msg="Master Code is required";
      }
   }
}

$year=$_SESSION['Year'];
$str=$_GET["s"];
if ($_GET["s"]!="") {
   $browse_sql="SELECT * from `$table` m left join master_franchisee_company mfc on mfc.master_code=m.master_code where  (m.year_of_commencement BETWEEN '".$year_start_date."' AND '".$year_end_date."') and (m.expiry_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and
		(m.master_code like '%$str%' or franchisee_company_name like '%$str%' or country like '%$str%' or pic like '%$str%') order by m.`master_code`";
} else {
   $browse_sql="SELECT * from `$table` where  (year_of_commencement BETWEEN '".$year_start_date."' AND '".$year_end_date."') and (expiry_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') order by master_code";
}
//echo $browse_sql;
?>