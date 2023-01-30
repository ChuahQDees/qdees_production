<?php

session_start();
include_once("../mysql.php");

function displayStudentStatus($status){
    switch($status){
       case "A":
       return "Active";
     case "D":
       return "Deferred";
     case "G":
       return "Graduated";
     case "I":
       return "Dropout";
     case "S":
       return "Suspended";
     case "T":
       return "Transferred";
       default:
          return $status;
    }
 }

$str=$_GET["s"];
$student_status=$_GET["student_status"];
$year=$_SESSION['Year'];

$where = '';
if($_GET['country'] != '') { $where .= " and s.country = '".$_GET['country']."'"; }
if($_GET['state'] != '') { $where .= " and s.state = '".$_GET['state']."'"; }
if($_GET['race'] != '') { $where .= " and s.race = '".$_GET['race']."'"; }
if($_GET['age'] != '') { $where .= " and (".date('Y',strtotime($year_start_date))." - YEAR(s.dob)) = '".$_GET['age']."'"; }

if ($_GET["s"]!="") {
    if ($student_status=="") {
      $browse_sql="SELECT DISTINCT s.student_code,s.centre_code,s.name,s.gender,(".date('Y',strtotime($year_start_date))." - YEAR(s.dob)) age,s.dob,s.nric_no, (SELECT
     GROUP_CONCAT(CONCAT( 'Name: ',`sec`.`full_name` , '\nEmail: ' , `sec`.`email` , '\nMobile: +' , `sec`.`mobile_country_code`, `sec`.`mobile`, '\nOccupation: ',`sec`.`occupation`, '\n\n') SEPARATOR '')
    FROM
        `student_emergency_contacts` sec
    WHERE
        `sec`.`student_code` = `s`.`student_code` AND  `sec`.`contact_type` = 'Mother'
    GROUP BY
        `sec`.`student_code`
    ) as mother,
	(SELECT
     GROUP_CONCAT(CONCAT( 'Name: ',`sec`.`full_name` , '\nEmail : ' , `sec`.`email` , '\nMobile : +' , `sec`.`mobile_country_code`, `sec`.`mobile`, '\nOccupation : ',`sec`.`occupation`, '\n\n') SEPARATOR '')
    FROM
        `student_emergency_contacts` sec
    WHERE
        `sec`.`student_code` = `s`.`student_code` AND  `sec`.`contact_type` = 'Father'
    GROUP BY
        `sec`.`student_code`
    ) as father,

		(SELECT
     GROUP_CONCAT(CONCAT( 'Name: ',`sec`.`full_name` , '\nEmail : ' , `sec`.`email` , '\nMobile : +' , `sec`.`mobile_country_code`, `sec`.`mobile`, '\nOccupation : ',`sec`.`occupation`, '\n\n') SEPARATOR '')
    FROM
        `student_emergency_contacts` sec
    WHERE
        `sec`.`student_code` = `s`.`student_code` AND  `sec`.`contact_type` = 'Guardian'
    GROUP BY
        `sec`.`student_code`
    ) as guardian,
	 s.country, s.state, s.add1, s.race, s.student_status, s.start_date_at_centre, s.form_serial_no, s.birth_cert_no, s.religion, s.nationality, s.health_problem, s.allergies, s.remarks FROM `student` s left join (select * from student_emergency_contacts where id in( SELECT min(id) id FROM `student_emergency_contacts` group BY `student_code` order by id asc) ) se on s.`student_code`=se.student_code where deleted=0 and (s.student_code like '%$str%' or `name` like '%$str%' or `mobile` like '%$str%' or `email` like '%$str%') and centre_code='".$_SESSION["CentreCode"]."'".$where;
     
    } else {
     
      $browse_sql="SELECT DISTINCT s.student_code,s.centre_code,s.name,s.gender,(".date('Y',strtotime($year_start_date))." - YEAR(s.dob)) age,s.dob,s.nric_no, (SELECT
     GROUP_CONCAT(CONCAT( 'Name : ',`sec`.`full_name` , '\nEmail : ' , `sec`.`email` , '\nMobile : +' , `sec`.`mobile_country_code`, `sec`.`mobile`, '\nOccupation : ',`sec`.`occupation`, '\n\n') SEPARATOR '')
    FROM
        `student_emergency_contacts` sec
    WHERE
        `sec`.`student_code` = `s`.`student_code` AND  `sec`.`contact_type` = 'Mother'
    GROUP BY
        `sec`.`student_code`
    ) as mother,
	(SELECT
     GROUP_CONCAT(CONCAT( 'Name: ',`sec`.`full_name` , '\nEmail : ' , `sec`.`email` , '\nMobile : +' , `sec`.`mobile_country_code`, `sec`.`mobile`, '\nOccupation : ',`sec`.`occupation`, '\n\n') SEPARATOR '')
    FROM
        `student_emergency_contacts` sec
    WHERE
        `sec`.`student_code` = `s`.`student_code` AND  `sec`.`contact_type` = 'Father'
    GROUP BY
        `sec`.`student_code`
    ) as father,

		(SELECT
     GROUP_CONCAT(CONCAT( 'Name: ',`sec`.`full_name` , '\nEmail : ' , `sec`.`email` , '\nMobile : +' , `sec`.`mobile_country_code`, `sec`.`mobile`, '\nOccupation : ',`sec`.`occupation`, '\n\n') SEPARATOR '')
    FROM
        `student_emergency_contacts` sec
    WHERE
        `sec`.`student_code` = `s`.`student_code` AND  `sec`.`contact_type` = 'Guardian'
    GROUP BY
        `sec`.`student_code`
    ) as guardian,
	 s.country, s.state, s.add1, s.race, s.student_status, s.start_date_at_centre, s.form_serial_no, s.birth_cert_no, s.religion, s.nationality, s.health_problem, s.allergies, s.remarks FROM `student` s left join (select * from student_emergency_contacts where id in( SELECT min(id) id FROM `student_emergency_contacts` group BY `student_code` order by id asc) ) se on s.`student_code`=se.student_code where deleted=0 and (s.student_code like '%$str%' or `name` like '%$str%' or `mobile` like '%$str%' or `email` like '%$str%') and centre_code='".$_SESSION["CentreCode"]."' and student_status='$student_status'  ".$where;
    }
 } else {
   if ($student_status=="") {
     $browse_sql="SELECT DISTINCT s.student_code,s.centre_code,s.name,s.gender,(".date('Y',strtotime($year_start_date))." - YEAR(s.dob)) age,s.dob,s.nric_no, (SELECT
     GROUP_CONCAT(CONCAT( 'Name: ',`sec`.`full_name` , '\nEmail : ' , `sec`.`email` , '\nMobile : +' , `sec`.`mobile_country_code`, `sec`.`mobile`, '\nOccupation : ',`sec`.`occupation`, '\n\n') SEPARATOR '')
    FROM
        `student_emergency_contacts` sec
    WHERE
        `sec`.`student_code` = `s`.`student_code` AND  `sec`.`contact_type` = 'Mother'
    GROUP BY
        `sec`.`student_code`
    ) as mother,
	(SELECT
     GROUP_CONCAT(CONCAT( 'Name: ',`sec`.`full_name` , '\nEmail : ' , `sec`.`email` , '\nMobile : +' , `sec`.`mobile_country_code`, `sec`.`mobile`, '\nOccupation : ',`sec`.`occupation`, '\n\n') SEPARATOR '')
    FROM
        `student_emergency_contacts` sec
    WHERE
        `sec`.`student_code` = `s`.`student_code` AND  `sec`.`contact_type` = 'Father'
    GROUP BY
        `sec`.`student_code`
    ) as father,

		(SELECT
     GROUP_CONCAT(CONCAT( 'Name: ',`sec`.`full_name` , '\nEmail : ' , `sec`.`email` , '\nMobile : +' , `sec`.`mobile_country_code`, `sec`.`mobile`, '\nOccupation : ',`sec`.`occupation`, '\n\n') SEPARATOR '')
    FROM
        `student_emergency_contacts` sec
    WHERE
        `sec`.`student_code` = `s`.`student_code` AND  `sec`.`contact_type` = 'Guardian'
    GROUP BY
        `sec`.`student_code`
    ) as guardian,
	s.country, s.state, s.add1, s.race, s.student_status, s.start_date_at_centre, s.form_serial_no, s.birth_cert_no, s.religion, s.nationality, s.health_problem, s.allergies, s.remarks FROM `student` s left join (select * from student_emergency_contacts where id in( SELECT min(id) id FROM `student_emergency_contacts` group BY `student_code` order by id asc) ) se on s.`student_code`=se.student_code where deleted=0 and centre_code='".$_SESSION["CentreCode"]."'".$where;
      
   } else {
     $browse_sql="SELECT DISTINCT s.student_code,s.centre_code,s.name,s.gender,(".date('Y',strtotime($year_start_date))." - YEAR(s.dob)) age,s.dob,s.nric_no, 
	(SELECT
     GROUP_CONCAT(CONCAT( 'Name: ',`sec`.`full_name` , '\nEmail : ' , `sec`.`email` , '\nMobile : +' , `sec`.`mobile_country_code`, `sec`.`mobile`, '\nOccupation : ',`sec`.`occupation`, '\n\n') SEPARATOR '')
    FROM
        `student_emergency_contacts` sec
    WHERE
        `sec`.`student_code` = `s`.`student_code` AND  `sec`.`contact_type` = 'Mother'
    GROUP BY
        `sec`.`student_code`
    ) as mother,
	(SELECT
     GROUP_CONCAT(CONCAT( 'Name: ',`sec`.`full_name` , '\nEmail : ' , `sec`.`email` , '\nMobile : +' , `sec`.`mobile_country_code`, `sec`.`mobile`, '\nOccupation : ',`sec`.`occupation`, '\n\n') SEPARATOR '')
    FROM
        `student_emergency_contacts` sec
    WHERE
        `sec`.`student_code` = `s`.`student_code` AND  `sec`.`contact_type` = 'Father'
    GROUP BY
        `sec`.`student_code`
    ) as father,

		(SELECT
     GROUP_CONCAT(CONCAT('Name: ',`sec`.`full_name` , '\nEmail : ' , `sec`.`email` , '\nMobile : +' , `sec`.`mobile_country_code`, `sec`.`mobile`, '\nOccupation : ',`sec`.`occupation`, '\n\n') SEPARATOR '')
    FROM
        `student_emergency_contacts` sec
    WHERE
        `sec`.`student_code` = `s`.`student_code` AND  `sec`.`contact_type` = 'Guardian'
    GROUP BY
        `sec`.`student_code`
    ) as guardian,
	s.country, s.state, s.add1, s.race, s.student_status, s.start_date_at_centre, s.form_serial_no, s.birth_cert_no, s.religion, s.nationality, s.health_problem, s.allergies, s.remarks FROM `student` s left join (select * from student_emergency_contacts where id in( SELECT min(id) id FROM `student_emergency_contacts` group BY `student_code` order by id asc) ) se on s.`student_code`=se.student_code where deleted=0 and centre_code='".$_SESSION["CentreCode"]."' ".$where;
   }
 }

 $browse_sql.= " and extend_year >= '$year'";

 if ($student_status!="") {
    $browse_sql .= " and student_status='$student_status'";
 }

 if (isset($_GET["order_by"]) && isset($_GET["order"])) {
    $order_by=mysqli_real_escape_string($connection, $_GET["order_by"]);
    $order=mysqli_real_escape_string($connection,$_GET["order"]);
    $browse_sql.=" ORDER BY " . $order_by . " " . $order . " ";

    $query.="&order_by=" .  $_GET["order_by"] . "&order=" . $_GET['order'];
  }else{
    $browse_sql.=" ORDER BY student_status, student_code asc ";
  }
  //echo $browse_sql; exit;
$result = mysqli_query($connection,$browse_sql);
$users = array();
  
if (mysqli_num_rows($result) > 0) { 
  while($row = $result->fetch_assoc()) {	 
      $users[] = $row;
  }
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=Student_Population_'.time().'.csv');
$output = fopen('php://output', 'w');
fputcsv($output, array("Student Code", "Centre Code", "Name","Gender","Age","Date Of Birth","Mykid / Passport No.","Mother","Father","Guardian","Parent's Info","Country","State","Address","Race","Status","Start Date at Center","Form Serial No.","Birth Cert No.","Religion","Nationality","Medical Condition","Allergies","Remarks"));

if (count($users) > 0) {
    foreach ($users as $row) {
        fputcsv($output, $row);
    }
}
exit;
?>
