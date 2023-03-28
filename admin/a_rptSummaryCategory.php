<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");
include_once("../search_new.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //$centre_code, $selected_month
}

if ($method=="print") {
   include_once("../uikit1.php");
}
$str_length = strlen($selected_month);
$month = ($selected_month == '13') ? '13' : str_split($selected_month,($str_length - 2))[1];
$year = ($selected_month == '13') ? $_SESSION['Year'] : str_split($selected_month,($str_length - 2))[0];
$grand_total = $sub_total = 0;
$addOn = $products = $tuition = $placement = $registration = [];
if ($student != '') {
   $student = strtolower($student);
}

if($month != '13')
{
   $month_start_date = date('Y-m-01',strtotime($year.'-'.$month)); 
   $month_end_date  = date('Y-m-t',strtotime($year.'-'.$month));
}

function filtering($row, $typeSchoolFees, $num) {
   for ($i=0; $i < $num; $i++) { 
      if ($row['collection_type'] == $typeSchoolFees[$i]) {
         $filtered[$typeSchoolFees[$i]] = $row;
         return $filtered;
      }
   }
}

function getCentreName($centre_code) {
   global $connection;

   $sql="SELECT company_name from centre where centre_code='$centre_code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      return $row["company_name"];
   } else {
      if ($centre_code=="ALL")  {
         return "All Centres";
      } else {
         return "";
      }
   }
}

function getStudentName($studentCode) {
   global $connection;
   $sql="SELECT name from student where student_code='$studentCode'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return $row['name'];
}

function getCourseName($allocationID) {
   global $connection;
   $sql="SELECT c.course_name from `allocation` a LEFT JOIN `course` c 
            on a.course_id = c.id
            where a.id = '$allocationID'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return $row['course_name'];
}


$base_sql ="";
// School Fees

$base_sql .=" select  f.subject,'School Fees' as fee_name, sum(f.school_adjust) as fees, f.school_collection as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and f.school_adjust != 0 and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."')";
if ($month != '13') {
   $base_sql .= " and case when f.school_collection='Monthly' then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'  
   when f.school_collection='Termly' and $month in (1, 4, 7, 10) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'
   when f.school_collection='Half Year' and $month in (1, 7) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'
   when f.school_collection='Annually' and $month in (1) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'
   end ";
  }  
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 
// $base_sql .=" group by f.subject, f.school_collection";

// Multimedia Fees
$base_sql .="  UNION ALL
select  f.subject,'Multimedia Fees' as fee_name, sum(f.multimedia_adjust) as fees, f.multimedia_collection as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and f.multimedia_adjust != 0 and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') ";
if ($month != '13') {
   $base_sql .= " and case when f.multimedia_collection='Monthly' then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'  
   when f.multimedia_collection='Termly' and $month in (1, 4, 7, 10) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'
   when f.multimedia_collection='Half Year' and $month in (1, 7) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'
   when f.multimedia_collection='Annually' and $month in (1) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'
   end ";
  } 
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 
// $base_sql .=" group by f.subject, f.multimedia_collection ";

// Facility Fees
$base_sql .="  UNION ALL
select  f.subject,'Facility Fees' as fee_name, sum(f.facility_adjust) as fees, f.facility_collection as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and f.facility_adjust != 0 and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') ";
if ($month != '13') {
   $base_sql .= " and case when f.facility_collection='Monthly' then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'  
   when f.facility_collection='Termly' and $month in (1, 4, 7, 10) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'
   when f.facility_collection='Half Year' and $month in (1, 7) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'
   when f.facility_collection='Annually' and $month in (1) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'
   end ";
  } 
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 
// $base_sql .=" group by f.subject, f.facility_collection ";

// Integrated Module
$base_sql .="  UNION ALL
select  f.subject,'Integrated Module' as fee_name, sum(f.integrated_adjust) as fees, f.integrated_collection as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and f.integrated_adjust != 0 and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') ";
if ($month != '13') {
   $base_sql .= " and case when f.integrated_collection='Monthly' then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'  
   when f.integrated_collection='Termly' and $month in (1, 4, 7, 10) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'
   when f.integrated_collection='Half Year' and $month in (1, 7) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'
   when f.integrated_collection='Annually' and $month in (1) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'
   end ";
  } 
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 
// $base_sql .=" group by f.subject, f.integrated_collection ";

// Link & Think
$base_sql .="  UNION ALL
select  f.subject,'Link & Think' as fee_name, sum(f.link_adjust) as fees, f.link_collection as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and f.link_adjust != 0 and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') ";
if ($month != '13') {
   $base_sql .= " and case when f.link_collection='Monthly' then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'  
   when f.link_collection='Termly' and $month in (1, 4, 7, 10) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'
   when f.link_collection='Half Year' and $month in (1, 7) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'
   when f.link_collection='Annually' and $month in (1) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'
   end ";
  } 
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 
// $base_sql .=" group by f.subject, f.link_collection ";

// Mobile app
$base_sql .="  UNION ALL
select  f.subject,'Mobile app' as fee_name, sum(f.mobile_adjust) as fees, f.mobile_collection as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and f.mobile_adjust != 0 and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') ";
if ($month != '13') {
   $base_sql .= " and case when f.mobile_collection='Monthly' then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'  
   when f.mobile_collection='Termly' and $month in (1, 4, 7, 10) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'
   when f.mobile_collection='Half Year' and $month in (1, 7) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'
   when f.mobile_collection='Annually' and $month in (1) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'
   end ";
  } 
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 
// $base_sql .=" group by f.subject, f.mobile_collection ";

// Registration
$base_sql .="  UNION ALL
select  f.subject,'Registration' as fee_name, sum(f.registration_adjust) as fees, 'Annually' as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and f.registration_adjust != 0 and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') ";
if ($month != '13') {
   $base_sql .= " and case when $month in (1) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date' end ";
  } 
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 
// $base_sql .=" group by f.subject ";

// Insurance
$base_sql .="  UNION ALL
select  f.subject,'Insurance' as fee_name, sum(f.insurance_adjust) as fees, 'Annually' as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and f.insurance_adjust != 0 and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') ";
if ($month != '13') {
   $base_sql .= " and case when $month in (1) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date' end ";
  } 
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 
// $base_sql .=" group by f.subject ";

// Q-dees Level Kit
$base_sql .="  UNION ALL
select  f.subject,'Q-dees Level Kit' as fee_name, sum(f.q_dees_adjust) as fees, 'Annually' as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and f.q_dees_adjust != 0 and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') ";
if ($month != '13') {
   $base_sql .= " and case when $month in (1) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date' end ";
  } 
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 
// $base_sql .=" group by f.subject ";

// Uniform (2 sets)
$base_sql .="  UNION ALL
select  f.subject,'Uniform (2 sets)' as fee_name, sum(fl.uniform_adjust) as fees, 'Annually' as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and fl.uniform_adjust != 0 and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') ";
if ($month != '13') {
   $base_sql .= " and case when $month in (1) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date' end ";
  } 
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 
// $base_sql .=" group by f.subject ";

// Gymwear (1 set)
$base_sql .="  UNION ALL
select  f.subject,'Gymwear (1 set)' as fee_name, sum(fl.gymwear_adjust) as fees, 'Annually' as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and fl.gymwear_adjust != 0 and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') ";
if ($month != '13') {
   $base_sql .= " and case when $month in (1) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date' end ";
  } 
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 
// $base_sql .=" group by f.subject ";

// Q-dees Bag
$base_sql .="  UNION ALL
select  f.subject,'Q-dees Bag' as fee_name, sum(f.q_bag_adjust) as fees, 'Annually' as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and f.q_bag_adjust != 0 and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') ";
if ($month != '13') {
   $base_sql .= " and case when $month in (1) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date' end ";
  } 
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 
// $base_sql .=" group by f.subject ";

// Mandarin Modules
$base_sql .=" UNION ALL
select  f.subject,'Mandarin Modules' as fee_name, sum(f.mandarin_m_adjust) as fees, f.mandarin_m_collection as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and fl.foundation_mandarin =1 and f.mandarin_m_adjust != 0  and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') ";
if ($month != '13') {
   $base_sql .= " and case when f.mandarin_m_collection='Monthly' then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'  
   when f.mandarin_m_collection='Termly' and $month in (1, 4, 7, 10) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'
   when f.mandarin_m_collection='Half Year' and $month in (1, 7) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'
   when f.mandarin_m_collection='Annually' and $month in (1) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'
   end ";
  } 
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 
//$base_sql .=" group by f.subject, f.mandarin_m_collection";

// IQ Math
$base_sql .=" UNION ALL
select  f.subject,'IQ Math' as fee_name, sum(f.iq_math_adjust) as fees, f.iq_math_collection as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and fl.foundation_iq_math =1 and f.iq_math_adjust != 0   and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') ";
if ($month != '13') {
   //$base_sql .= " and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."')  and fl.programme_date<='$selectedDateTime'";
   $base_sql .= " and case when f.iq_math_collection='Monthly' then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'  
   when f.iq_math_collection='Termly' and $month in (1, 4, 7, 10) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'
   when f.iq_math_collection='Half Year' and $month in (1, 7) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'
   when f.iq_math_collection='Annually' and $month in (1) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'
   end ";
  } 
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 
//$base_sql .=" group by f.subject, f.iq_math_collection";

// Mandarin
$base_sql .=" UNION ALL
select  f.subject,'Enhanced Foundation Mandarin' as fee_name, sum(f.mandarin_adjust) as fees, f.mandarin_collection as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and fl.foundation_int_mandarin =1 and f.mandarin_adjust != 0   and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') ";
if ($month != '13') {
   //$base_sql .= " and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."')  and fl.programme_date<='$selectedDateTime'";
   $base_sql .= " and case when f.mandarin_collection='Monthly' then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'  
   when f.mandarin_collection='Termly' and $month in (1, 4, 7, 10) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'
   when f.mandarin_collection='Half Year' and $month in (1, 7) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'
   when f.mandarin_collection='Annually' and $month in (1) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'
   end ";
  } 
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 
//$base_sql .=" group by f.subject, f.mandarin_collection";

// International Art
$base_sql .=" UNION ALL
select  f.subject,'International Art' as fee_name, sum(f.mandarin_adjust) as fees, f.international_collection as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and fl.foundation_int_art =1 and f.mandarin_adjust != 0   and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') ";
if ($month != '13') {
   //$base_sql .= " and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."')  and fl.programme_date<='$selectedDateTime'";
   $base_sql .= " and case when f.international_collection='Monthly' then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'  
   when f.international_collection='Termly' and $month in (1, 4, 7, 10) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'
   when f.international_collection='Half Year' and $month in (1, 7) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'
   when f.international_collection='Annually' and $month in (1) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'
   end ";
  } 
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 
//$base_sql .=" group by f.subject, f.international_collection";

// International English
$base_sql .=" UNION ALL
select  f.subject,'International English' as fee_name, sum(f.enhanced_adjust) as fees, f.enhanced_collection as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and fl.foundation_int_english =1 and f.enhanced_adjust != 0   and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') ";
if ($month != '13') {
   //$base_sql .= " and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."')  and fl.programme_date<='$selectedDateTime'";
   $base_sql .= " and case when f.enhanced_collection='Monthly' then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'  
   when f.enhanced_collection='Termly' and $month in (1, 4, 7, 10) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'
   when f.enhanced_collection='Half Year' and $month in (1, 7) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'
   when f.enhanced_collection='Annually' and $month in (1) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'
   end ";
  } 
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 
//$base_sql .=" group by f.subject, f.enhanced_collection";

// Pendidikan Islam
$base_sql .=" UNION ALL
select   f.subject,'Pendidikan Islam' as fee_name, sum(f.islam_adjust) as fees, 'Annually' as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and fl.pendidikan_islam =1 and f.islam_adjust != 0   and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') ";
if ($month != '13') {
   $base_sql .= " and case when $month in (1) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date' end ";
  } 
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 
//$base_sql .=" group by f.subject";

// Afternoon Programme
$base_sql .=" UNION ALL
select  f.subject,'Afternoon Programme' as fee_name, sum(f.basic_adjust) as fees, f.basic_collection as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and fl.afternoon_programme =1 and f.basic_adjust != 0   and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') ";
if ($month != '13') {
   //$base_sql .= " and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."')  and fl.programme_date<='$selectedDateTime'";
   $base_sql .= " and case when f.basic_collection='Monthly' then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'  
   when f.basic_collection='Termly' and $month in (1, 4, 7, 10) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'
   when f.basic_collection='Half Year' and $month in (1, 7) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'
   when f.basic_collection='Annually' and $month in (1) then  fl.programme_date <= '$month_end_date' and fl.programme_date_end >='$month_start_date'
   end ";
  } 
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 
//$base_sql .=" group by f.subject, f.basic_collection";
   
//echo $base_sql;
 
//Add-On Products
   $result=mysqli_query($connection, $base_sql);
   $num_row=mysqli_num_rows($result);


   $base_sql1 = " 
  select 'Add-On Products' as subject, `product_code`, `product_name` as fee_name, unit_price as fees, `monthly` as payment_type, '".$_SESSION['Year']."' as extend_year, '' as fee_id, '$year_start_date' as start_date, '$year_end_date' as end_date from addon_product where status='Approved' and `monthly`='Monthly'";
   if($centre_code != "ALL"){
      $base_sql1 .=" and centre_code='$centre_code'";
   } 

   $base_sql1 .= " UNION ALL
   select 'Add-On Products' as subject, `product_code`, `product_name` as fee_name, unit_price as fees, `monthly` as payment_type, '".$_SESSION['Year']."' as extend_year, '' as fee_id, '$year_start_date' as start_date, '$year_end_date' as end_date from addon_product where status='Approved' and `monthly`='Termly'";
    if($centre_code != "ALL"){
       $base_sql1 .=" and centre_code='$centre_code'";
    }
 

   
   $base_sql1 .= " UNION ALL
   select 'Add-On Products' as subject, `product_code`, `product_name` as fee_name, unit_price as fees, `monthly` as payment_type, '".$_SESSION['Year']."' as extend_year, '' as fee_id, '$year_start_date' as start_date, '$year_end_date' as end_date from addon_product where status='Approved' and `monthly`='Half Year'";
    if($centre_code != "ALL"){
       $base_sql1 .=" and centre_code='$centre_code'";
    }
   
   $base_sql1 .= " UNION ALL
   select 'Add-On Products' as subject, `product_code`, `product_name` as fee_name, unit_price as fees, `monthly` as payment_type, '".$_SESSION['Year']."' as extend_year, '' as fee_id, '$year_start_date' as start_date, '$year_end_date' as end_date from addon_product where status='Approved' and `monthly`='Annually'";
    if($centre_code != "ALL"){
       $base_sql1 .=" and centre_code='$centre_code'";
    }

?>

<style type="text/css">
  @media print {
  #note{
    display:none;
  }
}
</style>

<div class="uk-width-1-1 myheader text-center mt-5" style="text-align:center;color:white;">
   <h2 class="uk-text-center myheader-text-color myheader-text-style">CENTRE MONTHLY FEES REPORT</h2>
   For <?php echo ($selected_month == '13') ? 'All Months' : date('M Y',strtotime($year.'-'.$month));?><br>
</div>
<div class="nice-form">
   <div class="uk-grid">
   <div class="uk-width-medium-5-10">
      <table class="uk-table">
         <tr>
            <td class="uk-text-bold">Centre Name</td>
            <td><?php echo getCentreName($centre_code)?></td>
         </tr>
         <tr>
            <td class="uk-text-bold">Prepare By</td>
            <td><?php echo $_SESSION["UserName"]?></td>
         </tr>
         <tr id="note1">
            <td colspan="2" class="uk-text-bold">NOTE: To be submitted termly with the order form</td>
         </tr>
      </table>
   </div>
   <div class="uk-width-medium-5-10">
      <table class="uk-table">
         <tr>
            <td class="uk-text-bold">Academic Year</td>
            <td><?php echo $_SESSION['Year']; ?></td>
         </tr>
         <tr>
            <td class="uk-text-bold">School Term</td>
            <td>
            <?php
                 $month = date("m");
                     $year = $_SESSION['Year'];
                     if (isset($selected_month) && $selected_month != '') {
                        $str_length = strlen($selected_month);
                        $month = substr($selected_month, ($str_length - 2), 2);
                        $year = substr($selected_month, 0, -2);
                     }
                        
						$sql = "SELECT * from codes where module='SCHOOL_TERM'";
                    if($month!="13"){
                      $sql .= " and from_month<=$month and to_month>=$month";
                    }
                    $sql .= " order by category";
                     
                        $centre_result = mysqli_query($connection, $sql);
                        $str = "";
                      while ($centre_row = mysqli_fetch_assoc($centre_result)) {
                        $str .= $centre_row['category'] . ', ';
                      }
                      echo rtrim($str, ", ");
                     ?>
            </td>
         </tr>
         <tr>
            <td class="uk-text-bold">Date of submission</td>
            <td><?php echo date("Y-m-d H:i:s")?></td>
         </tr>
         <tr id="" style="display: none;">
            <td colspan="2" class="uk-text-bold">NOTE: To be submitted termly with the order form</td>
         </tr>
      </table>
   </div>
</div>
<table class="uk-table">
  
   <?php
   $total_amount = 0;
   $Grand_total = 0;
   $collection_total_amount = 0;
   $collection_Grand_total = 0;
   $i=0;
if ($month != '13') {
   if($summary=="" || $summary=="School Fees Summary"){
      while ($row=mysqli_fetch_assoc($result)) {
         //$student_id=$row['student_id'];
         $fee_name = $row['fee_name'];
         //$allocation_id = $row['allocation_id'];
            //print_r($row);
           // if($row['fees']>0){
            $total_amount += $row['fees'];
            $Grand_total += $row['fees'];
            if($i==0){
               $i++;
            ?>
            <tr>
                  <td colspan="4" class="uk-text-bold">School Fees Summary</td>
            </tr>
            <tr class="uk-text-bold">
            <th>Type</th>
            <th>Description</th>
            <th class="uk-text-right">Amount</th>
            <th class="uk-text-right">Collected Fees</th>
         </tr>
            <?php } ?>
            <tr>
               <td><?php echo $row['fee_name'] ?></td>
               <td><?php echo $row['fee_name'] ?></td>
               <td class="uk-text-right"><?php echo (($row['fees'] == '') ? 0 : $row['fees']); ?></td>
               <td class="uk-text-right"><?php 
               if($row['fees'] > 0) {

                  $sql22 = "SELECT sum(amount) collection FROM `collection` c  where  c.year = '".$_SESSION['Year']."' and c.void = 0 and CONVERT(c.`collection_month`, UNSIGNED) = $month and c.product_code = '$fee_name' ";

                  if($centre_code != "ALL"){
                     $sql22 .= "and c.centre_code='$centre_code' ";
                  }
               
                  $result22=mysqli_query($connection, $sql22);
            
                  $row_collection=mysqli_fetch_assoc($result22);

                  if($month_start_date == '2022-01-01' || $month_start_date == '2022-02-01')
                  {
                     $sql22 = "SELECT SUM(amount) collection FROM (SELECT amount FROM `collection` c  where  c.year = '".$_SESSION['Year']."' and c.void = 0 and CONVERT(c.`collection_month`, UNSIGNED) = $month and c.product_code = '$fee_name'";

                     if($centre_code != "ALL"){
                        $sql22 .= "and c.centre_code='$centre_code' ";
                     }
                  
                     $sql22 .= " GROUP BY `allocation_id` ORDER BY `id` ASC) ab";
                     
                     $result22=mysqli_query($connection, $sql22);
               
                     $row_collection=mysqli_fetch_assoc($result22);
                  }
                  else if($month_start_date == '2023-01-01' || $month_start_date == '2023-02-01')
                  {
                     $sql23 = "SELECT SUM(amount) collection FROM (SELECT amount FROM `collection` c  where  c.year = '".$_SESSION['Year']."' and c.void = 0 and CONVERT(c.`collection_month`, UNSIGNED) = $month and c.product_code = '$fee_name' ";

                     if($centre_code != "ALL"){
                        $sql23 .= "and c.centre_code='$centre_code' ";
                     }
                  
                     $sql23 .= " GROUP BY `allocation_id`) ab";

                     $result23=mysqli_query($connection, $sql23);
               
                     $row_collection1=mysqli_fetch_assoc($result23);

                     $row_collection['collection'] = $row_collection['collection'] - $row_collection1['collection'];
                  }

                  echo (($row_collection["collection"] == '') ? 0 : $row_collection["collection"]);
                  $collection_total_amount += $row_collection['collection'];
                  $collection_Grand_total += $row_collection['collection'];
               }else {
                  echo 0;
               }
               ?></td>
            </tr>
         <?php
           // }
      }
   }
}
if($total_amount>0){
   ?>
   
   <tr>
      <td colspan="2" class="uk-text-right uk-text-bold">Total: </td>
      <td class="uk-text-right uk-text-bold"><?php echo number_format((float)$total_amount, 2, '.', '');  ?></td>
      <td class="uk-text-right uk-text-bold"><?php echo number_format((float)$collection_total_amount, 2, '.', ''); ?></td>
   </tr>
<?php } ?>

<?php
$result=mysqli_query($connection, $base_sql1);
$num_row=mysqli_num_rows($result);
$total_amount = 0;
$i=0;
$collection_total_amount =0;
if ($month != '13') {
   if($summary=="" || $summary=="Stock Item Summary"){
      while ($row=mysqli_fetch_assoc($result)) {
         //if($row['subject'] == 'addon-product' || $row['collection_type'] == 'product'){
            //print_r($row);
            $fee_name = $row['fee_name'];
           
            if($i==0){
               $i++;
            ?>
            <tr>
                  <td colspan="4" class=" uk-text-bold">Stock Item Summary</td>
            </tr>
            <tr class="uk-text-bold">
               <th>Type</th>
               <th>Description</th>
               <th class="uk-text-right">Amount</th>
               <th class="uk-text-right">Collected Fees</th>
            </tr>
            <?php } ?>

            <tr>
               <td><?php echo $row['subject'] ?></td>
               <td><?php echo $row['fee_name'] ?></td>
               <td class="uk-text-right"><?php 
                $product_code = $row['product_code'];
               if($row['payment_type']=="Termly"){
                  
                  if($month == 1 || $month == 4 || $month == 7 || $month == 10){
                     $total_amount += $row['fees'];
                     $Grand_total += $row['fees'];
                     echo $row['fees'];
                     switch ($month){
                        case 1:
                           $collection_month=1;
                           break;
                        case 4:
                           $collection_month=2;
                           break;

                        case 7:
                           $collection_month=3;
                           break;

                        case 10:
                           $collection_month=4;
                           break;
                     }

                     if($month_start_date == '2023-01-01') {
                        $collection_month=5;
                     }
                  }else{
                     echo 0;
                  }
               }else if($row['payment_type']=="Half Year"){
                  if($month == date('m',strtotime($year_start_date)) || $month == 7){
                     $total_amount += $row['fees'];
                     $Grand_total += $row['fees'];
                     echo $row['fees'];
                     switch ($month){
                        case date('m',strtotime($year_start_date)):
                           $collection_month=1;
                           break;
                        case 7:
                           $collection_month=2;
                           break;
                     }
                  }else{
                     echo 0;
                  }
               }else if($row['payment_type']=="Annually"){
                  if($month == date('m',strtotime($year_start_date))){
                     $total_amount += $row['fees'];
                     $Grand_total += $row['fees'];
                     echo $row['fees'];
                     $collection_month=date('m',strtotime($year_start_date));
                  }else{
                     echo 0;
                  }
               }else{
                  $total_amount += $row['fees'];
                     $Grand_total += $row['fees'];
                  echo $row['fees'];
                  $collection_month=$month;
               }
               ?></td>
               <td class="uk-text-right"><?php 
              $sql22 = "SELECT sum(amount) collection FROM `collection` c  where  c.year = '".$_SESSION['Year']."' and c.void = 0 and CONVERT(c.`collection_month`, UNSIGNED) = $collection_month and c.product_code = '$product_code' ";

              if($centre_code != "ALL"){
               $sql22 .= "and c.centre_code='$centre_code' ";
              }
             
               $result22=mysqli_query($connection, $sql22);
               //$IsRow22=mysqli_num_rows($result22);
               $row_collection=mysqli_fetch_assoc($result22);
               echo (($row_collection["collection"] == '') ? 0 : $row_collection["collection"]);
               $collection_total_amount += $row_collection['collection'];
               $collection_Grand_total += $row_collection['collection']; 
               ?>
               </td>
            </tr>
            <?php
         //}
      }
   }
}
//if($total_amount>0){
   ?>
   
   <tr>
      <td colspan="2" class="uk-text-right uk-text-bold">Total: </td>
      <td class="uk-text-right uk-text-bold"><?php echo number_format((float)$total_amount, 2, '.', ''); ?></td>
      <td class="uk-text-right uk-text-bold"><?php echo number_format((float)$collection_total_amount, 2, '.', ''); ?></td>
</tr>
<?php //} ?>
<tr>
      <td colspan="2" class="uk-text-right uk-text-bold">Grand Total: </td>
      <td class="uk-text-right uk-text-bold"><?php echo number_format((float)$Grand_total += $row['amount'], 2, '.', ''); ?></td>
      <td class="uk-text-right uk-text-bold"><?php echo number_format((float)$collection_Grand_total += $row['amount'], 2, '.', ''); ?></td>
</tr>
</table>
<?php
// } else {
//    echo "Please enter a Centre, From Date and To Date";
// }
?>
</div>
<script>
$(document).ready(function () {
   var method='<?php echo $method?>';
   if (method=="print") {
      window.print();
      $("#note1").show();
   }
});
</script>