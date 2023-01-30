<?php
include_once("../mysql.php");
$student_code=$_POST["student_code"];
$centre_code=isset($_POST["centre_code"]) ? $_POST["centre_code"] : '';

if ($student_code!="") {
   $sql="SELECT sec.*, s.name from student_emergency_contacts sec, student s where s.student_code=sec.student_code 
   and s.student_code='$student_code' and s.centre_code = '$centre_code' and s.deleted = 0";
} else {
   $sql="SELECT sec.*, s.name from student_emergency_contacts sec, student s where s.student_code=sec.student_code and s.centre_code = '$centre_code' and s.deleted = 0";   
}
//echo $sql;
$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);

if ($num_row>0) {
   echo "\"Student Code\",\"Student Name\",\"Type\",\"Email\",\"Occupation\",\"Education Level\",\"Company No.\",\"Office No.\",\"Allow to Pick Up\",\"Vehicle No.\",\"Remarks\""."\n";
   while ($row=mysqli_fetch_assoc($result)) {
      header("Cache-Control: public");
      header("Content-Description: File Transfer");
      header("Content-Disposition: attachment; filename=emergency_contact.csv");
      header("Content-Type: application/octet-stream; "); 
      header("Content-Transfer-Encoding: binary");

      foreach ($row as $key=>$value) {
         $$key=$value;
      }

      echo "\"$student_code\",\"$name\",\"$contact_type\",\"$email\",\"$occupation\",\"$education_level\",\"$company_no\",\"$office_no\",\"$can_pick_up\",\"$vehicle_no\",\"$remarks\""."\n";
   }
} else {
   echo "<div class='uk-alert'>Nothing found</div>";
}
?>