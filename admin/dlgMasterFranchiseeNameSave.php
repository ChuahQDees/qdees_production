<?php
session_start();
include_once("../mysql.php");

$master_code=$_POST["master_code"];

function deleteName($master_code) {
   global $connection;

   $sql="DELETE from master_franchisee_name where master_code='$master_code'";
   $result=mysqli_query($connection, $sql);

   if ($result) {
      return true;
   } else {
      return false;
   }
}

deleteName($master_code);

$franchisee_name=$_POST["franchisee_name"];
$franchisee_passport=$_POST["franchisee_passport"];
$franchisee_ic_no=$_POST["franchisee_ic_no"];
$franchisee_id_no=$_POST["franchisee_id_no"];
$franchisee_residential_address1=$_POST["franchisee_residential_address1"];
$franchisee_residential_address2=$_POST["franchisee_residential_address2"];
$franchisee_residential_address3=$_POST["franchisee_residential_address3"];
$franchisee_residential_address4=$_POST["franchisee_residential_address4"];
$franchisee_contact_no=$_POST["franchisee_contact_no"];
$franchisee_email=$_POST["franchisee_email"];

$count=0;
foreach ($franchisee_name as $key=>$value) {
   $sql="INSERT into master_franchisee_name (master_code, franchisee_name, franchisee_passport, franchisee_ic_no, franchisee_id_no,
   franchisee_residential_address1, franchisee_residential_address2, franchisee_residential_address3, franchisee_residential_address4,
   franchisee_contact_no, franchisee_email) values ('$master_code', ";
   $sql.="'".$franchisee_name[$key]."',";
   $sql.="'".$franchisee_passport[$key]."',";
   $sql.="'".$franchisee_ic_no[$key]."',";
   $sql.="'".$franchisee_id_no[$key]."',";
   $sql.="'".$franchisee_residential_address1[$key]."',";
   $sql.="'".$franchisee_residential_address2[$key]."',";
   $sql.="'".$franchisee_residential_address3[$key]."',";
   $sql.="'".$franchisee_residential_address4[$key]."',";
   $sql.="'".$franchisee_contact_no[$key]."',";
   $sql.="'".$franchisee_email[$key]."')";
   mysqli_query($connection, $sql);
}

echo "Record saved";
?>