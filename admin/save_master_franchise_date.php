<?php
include_once("../mysql.php");

$master_code=$_POST["master_code"];

function deleteDate($master_code) {
   global $connection;

   $sql="DELETE from master_franchise_date where master_code='$master_code'";
   $result=mysqli_query($connection, $sql);

   if ($result) {
      return true;
   } else {
      return false;
   }
}

deleteDate($master_code);

$date=$_POST["date"];
$description=$_POST["description"];

$count=0;
foreach ($date as $key=>$value) {
   $sql="INSERT into master_franchise_date (master_code, `date`, description) values ('$master_code', ";
   $sql.="'".$date[$key]."',";
   $sql.="'".$description[$key]."')";
   mysqli_query($connection, $sql);
}

echo "Record saved";
?>