<?php
include_once("../mysql.php");

$use_code=$_POST["use_code"];

$sql="SELECT code from codes where module='COUNTRY' and use_code='$use_code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   echo $row["code"];
?>