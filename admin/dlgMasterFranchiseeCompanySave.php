<?php
session_start();
include_once("../mysql.php");

$master_code=$_POST["master_code"];

function deleteName($master_code) {
   global $connection;

   $sql="DELETE from master_franchisee_company where master_code='$master_code'";
   $result=mysqli_query($connection, $sql);

   if ($result) {
      return true;
   } else {
      return false;
   }
}

deleteName($master_code);

$franchisee_company_name=$_POST["franchisee_company_name"];
$franchisee_company_no=$_POST["franchisee_company_no"];
$franchisee_registered_address1=$_POST["franchisee_registered_address1"];
$franchisee_registered_address2=$_POST["franchisee_registered_address2"];
$franchisee_registered_address3=$_POST["franchisee_registered_address3"];
$franchisee_registered_address4=$_POST["franchisee_registered_address4"];
$franchisee_company_contact_no=$_POST["franchisee_company_contact_no"];
$franchisee_company_email=$_POST["franchisee_company_email"];

//print_r($franchisee_company_name);

$count=0;
foreach ($franchisee_company_name as $key=>$value) {
   $sql="INSERT into master_franchisee_company (master_code, franchisee_company_name, franchisee_company_no, 
   franchisee_registered_address1, franchisee_registered_address2, franchisee_registered_address3, franchisee_registered_address4, 
   franchisee_company_contact_no, franchisee_company_email) values ('$master_code', ";
   $sql.="'".$franchisee_company_name[$key]."',";
   $sql.="'".$franchisee_company_no[$key]."',";
   $sql.="'".$franchisee_registered_address1[$key]."',";
   $sql.="'".$franchisee_registered_address2[$key]."',";
   $sql.="'".$franchisee_registered_address3[$key]."',";
   $sql.="'".$franchisee_registered_address4[$key]."',";
   $sql.="'".$franchisee_company_contact_no[$key]."',";
   $sql.="'".$franchisee_company_email[$key]."')";
//echo $sql;
   mysqli_query($connection, $sql);
}

echo "Record saved";
?>