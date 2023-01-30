<?php
include_once("../mysql.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //$centre_code
}

if ($centre_code!="") {
   $sql="SELECT * from centre where centre_code='$centre_code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   $principle_name=$row["principle_name"];
   $company_email=$row["company_email"];
   $principle_contact_no=$row["principle_contact_no"];
   echo "1|$principle_name|$company_email|$principle_contact_no";
} else {
   echo "0|Centre Code not found or invalid";
}
?>