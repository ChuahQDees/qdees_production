<?php
include_once("../mysql.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);
}

// $master_code=$_POST["master_code"];
// $country=$_POST["country"];

function deleteState($master_code, $country) {
   global $connection;

   $sql="DELETE from master_state where master_code='$master_code' and country='$country'";
   $result=mysqli_query($connection, $sql);

   if ($result) {
      return true;
   } else {
      return false;
   }
}

if (($master_code!="") & ($country!="")) {
   deleteState($master_code, $country);
   
   $sql="SELECT * from codes where module='STATE' and country='$country' order by code";
   $result=mysqli_query($connection, $sql);
   while ($row=mysqli_fetch_assoc($result)) {
      $the_state=str_replace(" ", "", $row["code"]);
      if ($$the_state=="1") {
         $sql="INSERT into master_state (master_code, country, `state`) values ('$master_code', '$country', '".$row["code"]."')";
         $state_result=mysqli_query($connection, $sql);
      }
   }
}

// $state=$_POST["state"];

// foreach ($state as $key=>$value) {
//    $sql="INSERT into master_state (master_code, state, country) values ('$master_code', ";
//    $sql.="'".$state[$key]."',";
//    $sql.="'".$country."')";
//    mysqli_query($connection, $sql);
// }

echo "Record saved";
?>