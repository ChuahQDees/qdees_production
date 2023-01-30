<?php
session_start();
include_once("../mysql.php");

$centre_code=$_POST["centre_code"];

function deleteName($centre_code) {
   global $connection;

   $sql="DELETE from centre_franchisee_name where centre_code='$centre_code'";
   $result=mysqli_query($connection, $sql);

   if ($result) {
      return true;
   } else {
      return false;
   }
}

deleteName($centre_code);

$franchisee_name=$_POST["franchisee_name"];
$franchisee_passport=$_POST["franchisee_passport"];

$count=0;
foreach ($franchisee_name as $key=>$value) {
   $sql="INSERT into centre_franchisee_name (centre_code, franchisee_name, franchisee_passport) values ('$centre_code', ";
   $sql.="'".$franchisee_name[$key]."',";
   $sql.="'".$franchisee_passport[$key]."')";
   mysqli_query($connection, $sql);
}

echo "Record saved";
?>