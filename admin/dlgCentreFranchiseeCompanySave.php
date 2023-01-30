<?php
session_start();
include_once("../mysql.php");

$centre_code=$_POST["centre_code"];

function deleteName($centre_code) {
   global $connection;

   $sql="DELETE from centre_franchisee_company where centre_code='$centre_code'";
   $result=mysqli_query($connection, $sql);

   if ($result) {
      return true;
   } else {
      return false;
   }
}

deleteName($centre_code);

$franchisee_company_name=$_POST["franchisee_company_name"];
$franchisee_company_no=$_POST["franchisee_company_no"];

$count=0;
foreach ($franchisee_company_name as $key=>$value) {
   $sql="INSERT into centre_franchisee_company (centre_code, franchisee_company_name, franchisee_company_no) values ('$centre_code', ";
   $sql.="'".$franchisee_company_name[$key]."',";
   $sql.="'".$franchisee_company_no[$key]."')";
   $result=mysqli_query($connection, $sql);
}

echo "Record saved";
?>