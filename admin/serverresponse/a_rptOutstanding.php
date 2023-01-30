<?php
session_start();
include_once("../mysql.php");
foreach ($_POST as $key => $value) {
   $$key = mysqli_real_escape_string($connection, $value); //$centre_code; $month
}
//$centre_code='MYQWESTC1C10004';
if (isset($method) && $method == "print") {
   include_once("../uikit1.php");
}

function echoReportHeader($month, $year)
{
   global $centre_code;
   $center_name = getCentreName($centre_code);
   echo "<div class='uk-width-1-1 myheader text-center mt-5' style='text-align:center;color:white;'>";
   echo "<h2 class='uk-text-center myheader-text-color myheader-text-style'>CENTRE MONTHLY STUDENT FEES OUTSTANDING REPORT (DETAIL)</h2>";
   if ($month == '13') {
      echo "Selected Month :All Month<br>";
   } else {
      $dateObj   = DateTime::createFromFormat('!m', $month);
      $monthName = $dateObj->format('F');
      echo "Selected Month : $monthName $year<br>";
   }

   if ($centre_code == "") {
      echo "Centre ALL";
   } else {
      echo "Centre $center_name";
   }
   echo "</div>";
}
function getProductNameByCode($product_name, $centre_code)
{
   global $connection;

   $sql = "SELECT product_code from addon_product where product_name='$product_name' and centre_code = '$centre_code'";
   //echo $sql;
   $result = mysqli_query($connection, $sql);
   $row = mysqli_fetch_assoc($result);
   $num_row = mysqli_num_rows($result);

   return $row["product_code"];
   
}

function getCentreName($centre_code)
{
   global $connection;

   $sql = "SELECT company_name from centre where centre_code='$centre_code'";
   $result = mysqli_query($connection, $sql);
   $row = mysqli_fetch_assoc($result);
   $num_row = mysqli_num_rows($result);

   if ($num_row > 0) {
      return $row["company_name"];
   } else {
      if ($centre_code == "ALL") {
         return "All Centres";
      } else {
         return "";
      }
   }
}
function echoReportHeader1($centre_code, $user_name, $date, $term)
{
   echo "<div class='nice-form'>";
   echo "<div class='uk-grid'>";
   echo "<div class='uk-width-medium-5-10'>";
   echo "<table class='uk-table'>";
   echo "<tr>";
   echo "<td class='uk-text-bold'>Centre Name</td>";
   echo "<td>" . getCentreName($centre_code) . "</td>";
   echo "</tr>";
   echo "<tr>";
   echo "<td class='uk-text-bold'>Prepare By</td>";
   echo "<td>" . $user_name . "</td>";


   echo "<tr>";
   echo "<td colspan='2' class='uk-text-bold'>NOTE: To be submitted termly with the order form</td>";
   echo "</tr>";


   echo "</table>";
   echo "</div>";
   echo "<div class='uk-width-medium-5-10'>";
   echo "<table class='uk-table'>";


   echo "<tr>";
   echo "<td class='uk-text-bold'>Academic Year</td>";
   echo "<td>" . $date . "</td>";
   echo "</tr>";
   echo "<tr>";
   echo "<td class='uk-text-bold'>School Term</td>";
   echo "<td>" .  rtrim($term, ", ") . "</td>";
   echo "</tr>";
   echo "<tr>";
   echo "<td class='uk-text-bold'>Date of submission</td>";
   echo " <td>" . date('Y-m-d H:i:s') . "</td>";
   echo "</tr>";
   echo "</tr>";


   echo " </table>";
   echo "</div>";
   echo "</div>";
}

function echoTable()
{

   echo "<table class='uk-table'>";
   // echo "<tr class='uk-text-bold'>";
   // echo "  <td>No.</td>";
   // echo "  <td>Student</td>";
   // echo "  <td>Class Name</td>";
   // //echo "  <td>Collection Month</td>";
   // echo "  <td>Fee Description</td>";
   // echo "  <td class='uk-text-right'>Amount</td>";
   // echo "  <td class='uk-text-right'>Total Amount</td>";
   // echo "</tr>";
}
function echoTableHeader()
{
   echo "<table class='uk-table'>";
   echo "<tr class='uk-text-bold'>";
   echo "  <td style='width: 5%;'>No.</td>";
   echo "  <td style='width: 25%;' >Student </td>";
   echo "  <td style='width: 10%;'>Class Name</td>";
   //echo "  <td>Collection Month</td>";
   echo "  <td style='width: 25%;'>Fee Description</td>";
   echo "  <td style='width: 8%;' class='uk-text-right'>Amount</td>";
   //echo "  <td style='width: 13%;' class='uk-text-right'>Total Amount</td>";
   echo "</tr>";
   echo "</table>";
}
function echoClassHeader($class)
{
   echo "<tr>";
   echo "  <td colspan='6'><h3 class='myheader'>$class</h3></td>";
   echo "</tr>";
}

function echoStudentHeader($student_name, $student_code)
{
   echo "<tr>";
   echo "  <td colspan='6'><h3 class='myheader'>$student_code $student_name</h3></td>";
   echo "</tr>";
}

function echoRowWithStudentInfo($no, $student_code, $student_name, $collection_month, $fee_description, $amount)
{
   echo "<tr>";
   echo "  <td style='width: 5%;'>$no</td>";
   echo "  <td style='width: 15%;'>$student_code</td>";
   echo "  <td style='width: 15%;'>$student_name</td>";
   //echo "  <td>$collection_month</td>";
   echo "  <td style='width: 15%;'>$fee_description</td>";
   echo "  <td class='uk-text-right' style='width: 8%;'>" . number_format($amount, 2) . "</td>";
   echo "  <td>&nbsp;</td>";
   echo "</tr>";
}
// echoRowWithClassInfo($count, $row["course_name"], $month, 'Tuition', $row["fees"]);
//function echoRowWithClassInfo($no, $subject, $student_name, $collection_month, $fee_description, $amount)
function echoRowWithClassInfo($no, $subject, $student_name, $fee_description, $amount)
{
   echo "<tr>";
   echo "  <td style='width: 5%;'>$no</td>";
   echo "  <td style='width: 25%;'>$student_name</td>";
   echo "  <td style='width: 10%;'>$subject</td>";
   //echo "  <td>$collection_month</td>";
   echo "  <td style='width: 25%;'>$fee_description</td>";
   echo "  <td class='uk-text-right' style='width: 8%;'>" . number_format($amount, 2) . "</td>";
   //echo "  <td>&nbsp;</td>";
   echo "</tr>";
}

function echoRowWithoutStudentInfo($no, $student_code, $student_name, $collection_month, $fee_description, $amount)
{
   echo "<tr>";
   echo "  <td style='width: 5%;'>$no</td>";
   echo "  <td style='width: 15%;'>&nbsp;</td>";
    echo "  <td>&nbsp;</td>";
   //echo "  <td>$collection_month</td>";
   echo "  <td>$fee_description</td>";
   echo "  <td class='uk-text-right'>" . number_format($amount, 2) . "</td>";
   echo "  <td>&nbsp;</td>";
   echo "</tr>";
}

function echoStudentTotal($total)
{
   echo "<tr>";
   echo "  <td colspan='4' class='uk-text-left uk-text-bold'>Total Outstanding</td>";
   echo "  <td class='uk-text-right'>" . number_format($total, 2) . "</td>";
   echo "</tr>";
}

function echoClassTotal($total)
{
   echo "<tr>";
   echo "  <td colspan='3' class='uk-text-right uk-text-bold'>CLASS TOTAL:</td>";
   echo "  <td class='uk-text-right'>" . number_format($total, 2) . "</td>";
   echo "</tr>";
}

function echoBranchTotal($total)
{
   echo "<tr>";
   echo "  <td colspan='3' class='uk-text-right uk-text-bold'>BRANCH TOTAL:</td>";
   echo "  <td class='uk-text-right'>" . number_format($total, 2) . "</td>";
   echo "</tr>";
}

function echoGrandTotal($total)
{
   echo "<tr>";
   echo "  <td colspan='4' class='uk-text-right uk-text-bold'>TOTAL:</td>";
   echo "  <td class='uk-text-right'>" . number_format($total, 2) . "</td>";
   echo "</tr>";
}

function echoNotFound()
{
   echo "<table class='uk-table'>";
   echo "  <tr class='uk-text-bold'>";
   echo "    <td colspan='4'>No record found</td>";
   echo "  </tr>";
   echo "</table>";
}

function echoTableFooter()
{
   echo "</table>";
   echo "</div>";
}

function convertMonth2Term($month)
{
   switch ($month) {
      case "1":
         return "t1";
         break;
      case "2":
         return "t1";
         break;
      case "3":
         return "t2";
         break;
      case "4":
         return "t2";
         break;
      case "5":
         return "t3";
         break;
      case "6":
         return "t3";
         break;
      case "7":
         return "t4";
         break;
      case "8":
         return "t4";
         break;
      case "9":
         return "t5";
         break;
      case "10":
         return "t5";
         break;
   }
}

function getCourseID($allocation_id)
{
   global $connection;

   $sql = "SELECT course_id from allocation where id='$allocation_id'";
   $result = mysqli_query($connection, $sql);
   $row = mysqli_fetch_assoc($result);
   return $row["course_id"];
}

function gotFullPayment($centre_code, $allocation_id, $collection_month, $collection_type, &$outstanding_amount)
{
   global $connection;



   $term = convertMonth2Term($collection_month);

   if ($collection_type == "tuition") {
      $sql = "SELECT sum(amount) from collection where centre_code='$centre_code' and allocation_id='$allocation_id'
      and (collection_month='$collection_month' or collection_month='$term') and `year`='$year'
      and collection_type='$collection_type' and void=0";
   } else {
      $sql = "SELECT sum(amount) from collection where centre_code='$centre_code' and allocation_id='$allocation_id'
      and `year`='$year' and collection_type='$collection_type' and void=0";
   }

   $result = mysqli_query($connection, $sql);

   $row = mysqli_fetch_assoc($result);
   $payment_amount = $row["amount"];

   $full_fee = getCourseFee(getCourseID($allocation_id), $collection_type);

   $outstanding_amount = $full_fee - $payment_amount;
   if ($outstanding_amount == 0) {
      return true;
   } else {
      return false;
   }
}

function getCourseFee($course_id, $collection_type)
{
   global $connection;

   $sql = "SELECT * from course where id='$course_id'";
   $result = mysqli_query($connection, $sql);
   $num_row = mysqli_num_rows($result);
   $row = mysqli_fetch_assoc($result);

   if ($num_row > 0) {
      switch ($collection_type) {
         case "tuition":
            return $row["fees"];
            break;
         case "placement":
            return $row["placement"];
            break;
         case "deposit":
            return $row["deposit"];
            break;
         case "registration":
            return $row["registration"];
            break;
      }
   } else {
      return 0;
   }
}
function getStrMonth($month) {
   switch ($month) {
      case 1 : return "Jan"; break;
      case 2 : return "Feb"; break;
      case 3 : return "Mar"; break;
      case 4 : return "Apr"; break;
      case 5 : return "May"; break;
      case 6 : return "Jun"; break;
      case 7 : return "Jul"; break;
      case 8 : return "Aug"; break;
      case 9 : return "Sep"; break;
      case 10 : return "Oct"; break;
      case 11 : return "Nov"; break;
      case 12 : return "Dec"; break;
   }
}
function getCurrentTerm($month) {
	if($month<=3){
		return 1;
	}
	else if($month<=6){
		return 2;
	}
	else if($month<=9){
		return 3;
	}
	else if($month<=13){
		return 4;
	}
}
function getStrTerm($term) {
	 switch ($term) {
      case 1 : return "Term 1"; break;
      case 2 : return "Term 2"; break;
      case 3 : return "Term 3"; break;
      case 4 : return "Term 4"; break;
      case 5 : return "Term 5"; break;
   }
  
}

function getCurrentHalfYearly($month) {
	if($month<=6){
		return 1;
	}
	else if($month<=13){
		return 2;
	}
}
function getStrHalfYearly($HalfYearly) {
	 switch ($HalfYearly) {
      case 1 : return "First Half"; break;
      case 2 : return "Second Half"; break;
      
   }
  
}

function getCourseIDByAllocationID($allocation_id) {
   global $connection;

   $sql="SELECT course_id from allocation where id='$allocation_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["course_id"];
}

$term = convertMonth2Term($month);

$selectedDateTime = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-01'; // 00:00:00';
$selectedDateTime = date("Y-m-t", strtotime($selectedDateTime));

 $January = $February = $February = $March = $April = $May = $June = $July = $August = $September = $October = $November = $December = $Total = 0;

//find out how many courses available
/*$sql="SELECT distinct c.course_name, a.course_id from allocation a, student s, course c where
a.student_id=s.id and c.id=a.course_id and c.deleted=0 and s.deleted=0 and a.deleted=0 and
s.centre_code='$centre_code' order by c.course_name";
$sql="SELECT c.id as course_id, c.course_name from `group` g, course c 
LEFT JOIN collection cl ON (cl.course_id=c.id AND cl.year='$year' and collection_month='$month')
where g.course_id=c.id AND g.centre_code='$centre_code' and cl.id IS null order by c.course_name";*/


// if ($month == '13') {
//    $sql = "SELECT a.student_id, s.name as student_name, s.student_code, c.id as course_id, c.course_name, c.subject, year(g.start_date) from allocation a
//       inner join student s ON a.student_id=s.id
//       INNER JOIN course c ON a.course_id=c.id
//       left JOIN `group` g ON a.group_id=g.id
//       LEFT JOIN collection cl ON (cl.course_id=c.id and cl.centre_code=s.centre_code AND cl.allocation_id=a.id AND cl.student_id=s.id and cl.year='$year' and cl.collection_type='tuition')
//       where s.student_status='A' and a.deleted=0 and a.`year`='$year' and s.centre_code='$centre_code' and year(g.start_date)<='$year' group by a.student_id";
// } else {
//    $sql = "SELECT a.student_id, s.name as student_name, s.student_code, c.id as course_id, c.course_name, c.subject from allocation a
//       inner join student s ON a.student_id=s.id
//       INNER JOIN course c ON a.course_id=c.id
//       left JOIN `group` g ON a.group_id=g.id
//       LEFT JOIN collection cl ON (cl.course_id=c.id and cl.centre_code=s.centre_code AND cl.allocation_id=a.id AND cl.student_id=s.id and cl.year='$year' and collection_month='$month' and cl.collection_type='tuition')
//       where s.student_status='A' and a.deleted=0 and a.`year`='$year' and s.centre_code='$centre_code' and g.start_date<='$selectedDateTime'";
// }
//$centre_code = 'MYQWESTC1C10006';
$sql="SELECT ps.id, ps.student_id, fl.afternoon_programme, s.name as student_name, s.student_code, f.school_adjust, f.subject, f.school_collection, f.multimedia_collection, f.facility_collection, f.basic_collection, f.multimedia_adjust, f.facility_adjust, f.extend_year, f.basic_adjust, fl.fee_id, month(fl.programme_date) programme_start_month, fl.programme_date, fl.programme_date_end, fl.foundation_iq_math, fl.foundation_int_mandarin, fl.foundation_int_art, fl.foundation_mandarin, fl.foundation_int_english, fl.pendidikan_islam from student s, programme_selection ps, student_fee_list fl, fee_structure f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.centre_code='$centre_code' and s.`student_status`='A' ";
if ($month != '13') {
 $sql .= " and year(fl.programme_date)='$year'  and month(fl.programme_date)<='$month'";
} 

if ($student) {
   $sql .= " and (s.name like '%$student%' or s.student_code like '%$student%')";
}
if ($student) {
   $sql .= " and (s.name like '%$student%' or s.student_code like '%$student%')";
}
$sql .= " group by ps.student_id";
//echo $sql; 
$c_result = mysqli_query($connection, $sql);
$branch_total = 0;
echoReportHeader($month, $year);
$term = '';
//if (isset($selected_month) && $selected_month != '') {
// $month = substr($selected_month, 4, 2);
// $year = substr($selected_month, 0, -2);
$sql = "SELECT * from codes where year=" . $year;
if ($month != "13") {
   $sql .= " and from_month<=$month and to_month>=$month";
}
$sql .= " order by category";


/* while ($centre_row = mysqli_fetch_assoc($centre_result)) {
  // echo $centre_row['category'] . "/" . $centre_row['year'] . "<br>";
  $str .= $centre_row['category'] . ', ';
} */

//Print_r($sql);
$centre_result = mysqli_query($connection, $sql);
while ($centre_row = mysqli_fetch_assoc($centre_result)) {
   //$term = $term . $centre_row['category'] . "/" . $centre_row['year'] . "<br>";
   $term .= $centre_row['category'] . ', ';
}
//}
// echo $year;  die;
echoReportHeader1($centre_code, $_SESSION['UserName'], (!empty($selected_month)) ? str_split($selected_month, 4)[0] : $_SESSION['Year'], $term);
echoTableHeader();
$count = 0;
while ($c_row = mysqli_fetch_assoc($c_result)) {
   //get information according to the course
   // $sql="SELECT a.id as allocation_id, c.course_name, s.student_code, s.name, a.class_id, c.fees
   // from allocation a, student s, course c where a.student_id=s.id and c.id=a.course_id and c.deleted=0
   // and s.deleted=0 and a.deleted=0 and s.centre_code='$centre_code' and a.course_id='".$c_row["course_id"]."' order by
   // s.student_code";

   // if ($month == '13') {
   //    $sql = "SELECT a.id as allocation_id, c.course_name, c.subject, a.class_id, a.fees, c.payment_type, g.start_date, g.end_date from allocation a
   //    inner join student s ON a.student_id=s.id
   //    INNER JOIN course c ON a.course_id=c.id
   //    left JOIN `group` g ON a.group_id=g.id
   //    LEFT JOIN collection cl ON (cl.course_id=c.id and cl.centre_code=s.centre_code AND cl.allocation_id=a.id AND cl.student_id=s.id and cl.year='$year' and collection_month='$month' and cl.collection_type='tuition')
   //    where s.student_status='A' and a.deleted=0 and a.`year`='$year' and a.student_id='" . $c_row["student_id"] . "' and s.centre_code='$centre_code' and year(g.start_date)<='$year'";
   // } else {
   //    $sql = "SELECT a.id as allocation_id, c.course_name, c.subject, a.class_id, a.fees, c.payment_type, g.start_date, g.end_date from allocation a
   //    inner join student s ON a.student_id=s.id
   //    INNER JOIN course c ON a.course_id=c.id
   //    left JOIN `group` g ON a.group_id=g.id
   //    LEFT JOIN collection cl ON (cl.course_id=c.id and cl.centre_code=s.centre_code AND cl.allocation_id=a.id AND cl.student_id=s.id and cl.year='$year' and collection_month='$month' and cl.collection_type='tuition')
   //    where s.student_status='A' and a.deleted=0 and a.`year`='$year' and a.student_id='" . $c_row["student_id"] . "' and s.centre_code='$centre_code' and g.start_date<='$selectedDateTime'";
   // }
  
   $sql="SELECT ps.id as allocation_id, ps.student_id,  f.subject, f.fee_name, f.fees, f.payment_type, f.extend_year, fl.fee_id, fl.programme_date as start_date, fl.programme_date_end as end_date
   from student s, programme_selection ps, student_fee_list fl, 
   (
      select id, subject,'School Fees' as fee_name, school_adjust as fees, school_collection as payment_type, extend_year from fee_structure
      UNION ALL
      select id, subject,'Multimedia Fees' as fee_name, multimedia_adjust as fees, multimedia_collection as payment_type, extend_year from fee_structure
      UNION ALL
      select id, subject,'Facility Fees' as fee_name, facility_adjust as fees, facility_collection as payment_type, extend_year from fee_structure
      UNION ALL
      select id, subject,'Integrated Module' as fee_name, integrated_adjust as fees, integrated_collection as payment_type, extend_year from fee_structure
      UNION ALL
      select id, subject,'Link & Think' as fee_name, link_adjust as fees, link_collection as payment_type, extend_year from fee_structure
      UNION ALL
      select id, subject,'Mobile app' as fee_name, mobile_adjust as fees, mobile_collection as payment_type, extend_year from fee_structure
      UNION ALL
      select id, subject,'Registration' as fee_name, registration_adjust as fees, 'Annually' as payment_type, extend_year from fee_structure
      UNION ALL
      select id, subject,'Insurance' as fee_name, insurance_adjust as fees, 'Annually' as payment_type, extend_year from fee_structure
      UNION ALL
      select id, subject,'Q-dees Level Kit' as fee_name, q_dees_adjust as fees, 'Annually' as payment_type, extend_year from fee_structure
      UNION ALL
      select id, subject,'Uniform (2 sets)' as fee_name, uniform_adjust as fees, 'Annually' as payment_type, extend_year from fee_structure
      UNION ALL
      select id, subject,'Gymwear (1 set)' as fee_name, gymwear_adjust as fees, 'Annually' as payment_type, extend_year from fee_structure
      UNION ALL
      select id, subject,'Q-dees Bag' as fee_name, q_bag_adjust as fees, 'Annually' as payment_type, extend_year from fee_structure ";
      if ($c_row["foundation_mandarin"]){
         $sql .= " 
         UNION ALL
      select id, subject,'Mandarin Modules' as fee_name, mandarin_m_adjust as fees, mandarin_m_collection as payment_type, extend_year from fee_structure ";
      }
      if ($c_row["foundation_iq_math"]){
         $sql .= " 
         UNION ALL
         select id, subject,'IQ Math' as fee_name, iq_math_adjust as fees, iq_math_collection as payment_type, extend_year from fee_structure ";
      }
      if ($c_row["foundation_int_mandarin"]){
         $sql .= " 
         UNION ALL
         select id, subject,'Enhanced Foundation Mandarin' as fee_name, mandarin_adjust as fees, mandarin_collection as payment_type, extend_year from fee_structure ";
      }
      if ($c_row["foundation_int_art"]){
         $sql .= " 
         UNION ALL
         select id, subject,'International Art' as fee_name, international_adjust as fees, international_collection as payment_type, extend_year from fee_structure ";
       }
      if ($c_row["foundation_int_english"]){
         $sql .= " UNION ALL
         select id, subject,'International English' as fee_name, enhanced_adjust as fees, enhanced_collection as payment_type, extend_year from fee_structure ";
      }
      if ($c_row["pendidikan_islam"]){
         $sql .= " 
         UNION ALL
         select id, subject, 'Pendidikan Islam' as fee_name, islam_adjust as fees, 'Annually' as payment_type, extend_year from fee_structure ";
      }
     if ($c_row["afternoon_programme"]){
         $sql .= " 
         UNION ALL
      select id, subject,'Afternoon Programme' as fee_name, basic_adjust as fees, basic_collection as payment_type, extend_year from fee_structure ";
      }
      
      $sql .= " ) f   
   where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.centre_code='$centre_code' and ps.student_id='" . $c_row["student_id"] . "' ";

   //echo $month;
   if ($month != '13') {
      $sql .= " and year(fl.programme_date)='$year'  and month(fl.programme_date)<='$month' ";
   } 

   //echo $sql;

   $sql11="select  fl.programme_date, fl.programme_date_end from student s, programme_selection ps, student_fee_list fl 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and s.`student_status`='A' and year(fl.programme_date)='$year' and s.id = '" . $c_row["student_id"] . "' and month(fl.programme_date)<=$month ";
//echo $sql11;
$result11=mysqli_query($connection, $sql11);
//$row111=mysqli_fetch_assoc($result11);
$num_row111=mysqli_num_rows($result11);

if ($num_row111>0) {
   $end_date_addon= "";
   $z=0;
   while ($row111 = mysqli_fetch_assoc($result11)) {
      if($z==0){
         $programme_date = $row111['programme_date'];
      }
      $programme_date_end = $row111['programme_date_end'];
      $z++;
   }
}
$sql .= " 
UNION ALL 
select '0' as allocation_id, '" . $c_row["student_id"] . "' as student_id, 'Add-On Products' as subject, `product_name` as fee_name, unit_price as fees, `monthly` as payment_type, '$year' as extend_year, '' as fee_id, '$programme_date' as start_date, '$programme_date_end' as end_date from addon_product where centre_code='$centre_code' and status='Approved'";
   
$sql .= " order by fee_name ";

   //echo $sql;
   $result = mysqli_query($connection, $sql);
   $num_row = mysqli_num_rows($result);
   $count++;
   if ($num_row > 0) {
      echoTable();
      // echoClassHeader($c_row["course_name"]);
      //echoStudentHeader($c_row["student_name"], $c_row["student_code"]);
      $class_total = 0;
      //echo $class_total;
      $programme_date = $c_row["programme_start_month"];
      //if($programme_date <= $month){

         $check_item_array=array();
      while ($row = mysqli_fetch_assoc($result)) {
         
            // echoRowWithStudentInfo($count, $row["student_code"], $row["name"], $month, 'Tuition', $row["fees"]);
          if($row["fees"]!=0){
            
            if ($row["payment_type"] == 'Monthly') {
               $start_month = explode('-', ($row["start_date"]))[1];
               $end_month = explode('-', ($row["end_date"]))[1];
               //$total_month = (intval($end_month[1]) - intval($start_month[1])) + 1;
               //$total_month = intval($end_month[1]);
               //echo $end_month;
               //echo $start_month[1];
               //echo $end_month[1];
              // echo $total_month;
             
               for ($i = $start_month-1; $i < $end_month; $i++) {
                  $full_item_name = $row["fee_name"].' for ' . getStrMonth($i+1);
                  if (!in_array($full_item_name, $check_item_array)) {
                     array_push($check_item_array, $full_item_name);

                  //echo $start_month[1];
                 // $strMonth = date('M', mktime(0, 0, 0, (intval($start_month[1]) + $i), 10));
                 // echo $strMonth;
                 
                     if ($i <= $month-1) {
                        $allocation_id= $row["allocation_id"];
                        $fee_name= $row["fee_name"];
                        $product_code= $row["fee_name"];
                        if($row["subject"] =="Add-On Products"){
                           $product_code = getProductNameByCode($fee_name, $centre_code);
                        }
                        $student_id= $row["student_id"];
                        $collection_month= $i+1;
                        //$sql22 = "SELECT sum(amount)+sum(discount) collection FROM `collection` c  where c.student_id = '$student_id' and  c.year = '$year' and c.void = 0 and c.`collection_month` = $collection_month and c.product_code = '$product_code' and c.allocation_id = '$allocation_id' and collection_pattern ='Monthly'";
                        $sql22 = "SELECT sum(amount)+sum(discount) collection FROM `collection` c  where c.student_id = '$student_id' and  c.year = '$year' and c.void = 0 and c.`collection_month` = $collection_month and c.product_code = '$product_code' and collection_pattern ='Monthly'";
                        //  if($i ==1){
                        //     echo $sql22; 
                        //  }
                        //echo "asdfsad"; die;
                        $result22=mysqli_query($connection, $sql22);
                        //$IsRow22=mysqli_num_rows($result22);
                        $row_collection=mysqli_fetch_assoc($result22);
                        //$balance = $row["fees"] - (($row_collection["collection"] == '') ? 0 : $row_collection["collection"]);
                        //echo $balance;
                        // if($row["fee_name"]=="School Fees"){
                        //    echo $row["fees"];
                        //    echo $balance;
                        // }
                        
                        $collection =(($row_collection["collection"] == '') ? 0 : $row_collection["collection"]);
                        $balance = $row["fees"] - $collection;
                        if(($balance>0 && $row["subject"] !="Add-On Products") || ($row["subject"] =="Add-On Products" && $collection>0 && $balance>0)){ 
                        //if($balance>0){ 
                           echoRowWithClassInfo( ($class_total == 0) ? $count : "" , $row["subject"], ($class_total == 0) ? $c_row["student_name"] . '<br>' . $c_row["student_code"] : "", $row["fee_name"].' for ' . getStrMonth($i+1), $balance);
                              $class_total += $balance;
                              $Total += $balance;
                              switch ($i) {
                                 case 0:
                                    $January += $balance;
                                 break;
                                 case 1:
                                    $February += $balance;
                                 break;
                                 case 2:
                                    $March += $balance;
                                 break;
                                 case 3:
                                    $April += $balance;
                                 break;
                                 case 4:
                                    $May += $balance;
                                 break;
                                 case 5:
                                    $June += $balance;
                                 break;
                                 case 6:
                                    $July += $balance;
                                 break;
                                 case 7:
                                    $August += $balance;
                                 break;
                                 case 8:
                                    $September += $balance;
                                 break;
                                 case 9:
                                    $October += $balance;
                                 break;
                                 case 10:
                                    $November += $balance;
                                 break;
                                 case 11:
                                    $December += $balance;
                                 break;
                                 // default:
                                 //   echo "Your favorite color is neither red, blue, nor green!";
                              }
                              //$July, $August, $September, $October, $November, $December, $Total = 0;
                        }
                     }
                  }
               }
               
            }
           else 
         if ($row["payment_type"] == 'Termly') {
           
           // if ($row["payment_type"] == 'Monthly') {
               $start_month = date("m",strtotime($row["start_date"]));
               $end_month = date("m",strtotime($row["end_date"]));
               //$total_month = (intval($end_month[1]) - intval($start_month[1])) + 1;
               //$month = date("m",strtotime($end_month));
               //echo $month;  
               $term_start = getCurrentTerm($start_month);
               $term_end = getCurrentTerm($end_month);
               $i = (int)$term_start-1;
               $term_end_int = (int)$term_end;
               //for ($i = 0; $i < $total_month; $i++) {
               //for ($i; $i < $term1_end; $i++) { 
               
               for ($i; $i < $term_end_int; $i++) { 
                  $full_item_name = $row["fee_name"].' for ' . getStrTerm($i+1);
                  if (!in_array($full_item_name, $check_item_array)) {
                     array_push($check_item_array, $full_item_name);
                  //$strMonth = date('M', mktime(0, 0, 0, (intval($start_month[1]) + $i), 10));
                     if ($i+1 <= getCurrentTerm($month)) {
                        $allocation_id= $row["allocation_id"];
                        $fee_name= $row["fee_name"];
                        $product_code= $row["fee_name"];
                        if($row["subject"] =="Add-On Products"){
                           $product_code = getProductNameByCode($fee_name, $centre_code);
                        }
                        $student_id= $row["student_id"];
                        $collection_month= $i+1;
                        //$sql22 = "SELECT sum(amount)+sum(discount) collection FROM `collection` c  where c.student_id = '$student_id' and  c.year = '$year' and c.void = 0 and c.`collection_month` = $collection_month and c.product_code = '$product_code' and c.allocation_id = '$allocation_id' and collection_pattern ='Termly' and month(c.collection_date_time)>='$start_month'";
                        //$sql22 = "SELECT sum(amount)+sum(discount) collection FROM `collection` c  where c.student_id = '$student_id' and  c.year = '$year' and c.void = 0 and c.`collection_month` = $collection_month and c.product_code = '$product_code' and collection_pattern ='Termly' and month(c.collection_date_time)>='$start_month'";
                        $sql22 = "SELECT sum(amount)+sum(discount) collection FROM `collection` c  where c.student_id = '$student_id' and  c.year = '$year' and c.void = 0 and c.`collection_month` = $collection_month and c.product_code = '$product_code' and collection_pattern ='Termly' ";
                        //  if($fee_name=="Integrated Module"){
                        //      echo $sql22; 
                        //  }
                        
                        $result22=mysqli_query($connection, $sql22);
                        //$IsRow22=mysqli_num_rows($result22);
                        $row_collection=mysqli_fetch_assoc($result22);
                        // $balance = $row["fees"] - (($row_collection["collection"] == '') ? 0 : $row_collection["collection"]);
                        $collection =(($row_collection["collection"] == '') ? 0 : $row_collection["collection"]);
                        
                        $balance = $row["fees"] - $collection;
                     //    if($fee_name=="Integrated Module"){
                     //       echo $collection; 
                     //   }
                        // if($row["fee_name"]=="Student tag card"){
                        //    echo $row["fee_name"] . "<br>";
                        //    echo $balance . "<br>";
                        //    echo $row["subject"] . "<br>";
                        // }
                     
                        if(($balance>0 && $row["subject"] !="Add-On Products") || ($row["subject"] =="Add-On Products" && $collection>0 && $balance>0)){    
                        //if($balance>0){ 
                           echoRowWithClassInfo( ($class_total == 0) ? $count : "" , $row["subject"], ($class_total == 0) ? $c_row["student_name"] . '<br>' . $c_row["student_code"] : "", $row["fee_name"].' for ' . getStrTerm($i+1), $balance);
                           $class_total += $balance;
                           $Total += $balance;
                           switch ($i) {
                              case 0:
                                 switch ($start_month-1) {
                                    case 0:
                                    $January += $balance;
                                    break;
                                    case 1:
                                    $February += $balance;
                                    break;
                                    case 2:
                                    $March += $balance;
                                    break;
                              }
                              break;
                              case 1:
                                 switch ($start_month-1) {
                                    case 3:
                                    $April += $balance;
                                    break;
                                    case 4:
                                    $May += $balance;
                                    break;
                                    case 5:
                                    $June += $balance;
                                    break;
                                    default:
                                    $April += $balance;
                              }
                              break;
                              case 2:
                                 switch ($start_month-1) {
                                    case 6:
                                    $July += $balance;
                                    break;
                                    case 7:
                                    $August += $balance;
                                    break;
                                    case 8:
                                    $September += $balance;
                                    break;
                                    default:
                                       $July += $balance;
                              }
                              break;
                              case 3:
                                 switch ($start_month-1) {
                                    case 9:
                                    $October += $balance;
                                    break;
                                    case 10:
                                    $November += $balance;
                                    break;
                                    case 11:
                                    $December += $balance;
                                    break;
                                    default:
                                    $October += $balance;
                              }
                              break;
                              
                              // default:
                              //   echo "Your favorite color is neither red, blue, nor green!";
                           }
                        }
                     }
                  }
               }
            }
            else 
            if ($row["payment_type"] == 'Half Year') {
               
            //if ($row["payment_type"] == 'Monthly') {
               $start_month = date("m",strtotime($row["start_date"]));
               $end_month = date("m",strtotime($row["end_date"]));
               //$month = date("m",strtotime($end_month));
               $i = getCurrentHalfYearly($start_month)-1;
               $HearfYearly = getCurrentHalfYearly($end_month);
               // if($row["fee_name"]=='Link & Think'){
               //    //echo $row["payment_type"];
               //    echo $month;
               // }
               //for ($i; $i < $HearfYearly; $i++) { 
               for ($i; $i < $HearfYearly; $i++) {
                  if ($i+1 <= getCurrentHalfYearly($month)) {
                     $full_item_name = $row["fee_name"].' for ' . getStrHalfYearly($i+1);
                     if (!in_array($full_item_name, $check_item_array)) {
                        array_push($check_item_array, $full_item_name);
                     
                        $allocation_id= $row["allocation_id"];
                        $fee_name= $row["fee_name"];
                        $product_code= $row["fee_name"];
                        if($row["subject"] =="Add-On Products"){
                           $product_code = getProductNameByCode($fee_name, $centre_code);
                        }
                        $student_id= $row["student_id"];
                        $collection_month= $i+1;
                        //$sql22 = "SELECT sum(amount)+sum(discount) collection FROM `collection` c  where c.student_id = '$student_id' and  c.year = '$year' and c.void = 0 and c.`collection_month` = $collection_month and c.product_code = '$product_code' and c.allocation_id = '$allocation_id' and collection_pattern ='Half Year' and month(c.collection_date_time)>='$start_month'";
                        //$sql22 = "SELECT sum(amount)+sum(discount) collection FROM `collection` c  where c.student_id = '$student_id' and  c.year = '$year' and c.void = 0 and c.`collection_month` = $collection_month and c.product_code = '$product_code' and collection_pattern ='Half Year' and month(c.collection_date_time)>='$start_month'";
                        $sql22 = "SELECT sum(amount)+sum(discount) collection FROM `collection` c  where c.student_id = '$student_id' and  c.year = '$year' and c.void = 0 and c.`collection_month` = $collection_month and c.product_code = '$product_code' and collection_pattern ='Half Year'";
                        // if($product_code=='Link & Think'){
                        //    echo $sql22;
                        // }

                        $result22=mysqli_query($connection, $sql22);
                        $IsRow22=mysqli_num_rows($result22);
                        $row_collection=mysqli_fetch_assoc($result22);
                        // $balance = $row["fees"] - (($row_collection["collection"] == '') ? 0 : $row_collection["collection"]);
                        $collection =(($row_collection["collection"] == '') ? 0 : $row_collection["collection"]);
                        $balance = $row["fees"] - $collection;
                        if(($balance>0 && $row["subject"] !="Add-On Products") || ($row["subject"] =="Add-On Products" && $collection>0 && $balance>0)){ 
                        // if($balance>0){ 
                        //$strMonth = date('M', mktime(0, 0, 0, (intval($start_month[1]) + $i), 10));

                        //if($product_code=='Link & Think'){
                           //echo $sql22;
                          // $full_item_name = $row["fee_name"].' for ' . getStrHalfYearly($i+1);
                          // if (!in_array($full_item_name, $check_item_array)) {
                           //array_push($check_item_array, $full_item_name);
                         //  print_r($check_item_array);
                          // }
                        //}

                        echoRowWithClassInfo( ($class_total == 0) ? $count : "" , $row["subject"], ($class_total == 0) ? $c_row["student_name"] . '<br>' . $c_row["student_code"] : "", $row["fee_name"].' for ' . getStrHalfYearly($i+1), $balance);
                        $class_total += $balance;
                        $Total += $balance;
                        switch ($i) {
                           case 0:
                              switch ($start_month-1) {
                                 case 0:
                                 $January += $balance;
                                 break;
                                 case 1:
                                 $February += $balance;
                                 break;
                                 case 2:
                                 $March += $balance;
                                 break;
                                 case 3:
                                 $April += $balance;
                                 break;
                                 case 4:
                                 $May += $balance;
                                 break;
                                 case 5:
                                 $June += $balance;
                                 break;
                                 default:
                                 $January += $balance;
                           }
                           break;
                           case 1:
                              switch ($start_month-1) {
                                 case 6:
                                 $July += $balance;
                                 break;
                                 case 7:
                                 $August += $balance;
                                 break;
                                 case 8:
                                 $September += $balance;
                                 break;
                                 case 9:
                                 $October += $balance;
                                 break;
                                 case 10:
                                 $November += $balance;
                                 break;
                                 case 11:
                                 $December += $balance;
                                 break;
                                 default:
                                 $July += $balance;
                           }
                           break;
                           // default:
                           //   echo "Your favorite color is neither red, blue, nor green!";
                        }
                      }
                     }
                  }
               }
             } 
         else 
         if ($row["payment_type"] == 'Annually') {

            $start_month = date("m",strtotime($row["start_date"]));
            $end_month = date("m",strtotime($row["end_date"]));
            //$strYear = date('Y', mktime(0, 0, 0, (intval($start_month[1])), 10));
            $strYear = date("Y",strtotime($row["start_date"]));

            $full_item_name = $row["fee_name"].' for ' . $strYear;
            if (!in_array($full_item_name, $check_item_array)) {
               array_push($check_item_array, $full_item_name);

               $allocation_id= $row["allocation_id"];
               $fee_name= $row["fee_name"];
               $product_code= $row["fee_name"];
               if($row["subject"] =="Add-On Products"){
                  $product_code = getProductNameByCode($fee_name, $centre_code);
               }
               $student_id= $row["student_id"];
               $collection_month= 1;
               //$sql22 = "SELECT sum(amount)+sum(discount) collection FROM `collection` c  where c.student_id = '$student_id' and  c.year = '$year' and c.void = 0 and c.`collection_month` = $collection_month and c.product_code = '$product_code' and c.allocation_id = '$allocation_id' and collection_pattern ='Annually' and month(c.collection_date_time)>='$start_month'";
               $sql22 = "SELECT sum(amount)+sum(discount) collection FROM `collection` c  where c.student_id = '$student_id' and  c.year = '$year' and c.void = 0 and c.`collection_month` = $collection_month and c.product_code = '$product_code' and collection_pattern ='Annually'";
               // if($product_code=="Mobile app"){
               //    echo $sql22; 
               // }
               
               $result22=mysqli_query($connection, $sql22);
               //$IsRow22=mysqli_num_rows($result22);
               $row_collection=mysqli_fetch_assoc($result22);
               // $balance = $row["fees"] - (($row_collection["collection"] == '') ? 0 : $row_collection["collection"]);
               $collection =(($row_collection["collection"] == '') ? 0 : $row_collection["collection"]);
               $balance = $row["fees"] - $collection;
               if(($balance>0 && $row["subject"] !="Add-On Products") || ($row["subject"] =="Add-On Products" && $collection>0 && $balance>0)){ 
               // if($balance>0){ 
                  echoRowWithClassInfo(($class_total == 0) ? $count : "", $row["subject"], ($class_total == 0) ? $c_row["student_name"] . '<br>' . $c_row["student_code"] : "", $row["fee_name"].' for ' . $strYear, $balance);
                  $class_total += $balance;
                  $Total += $balance;
               
               
                     switch ($start_month-1) {
                        case 0:
                        $January += $balance;
                        break;
                        case 1:
                        $February += $balance;
                        break;
                        case 2:
                        $March += $balance;
                        break;
                        case 3:
                        $April += $balance;
                        break;
                        case 4:
                        $May += $balance;
                        break;
                        case 5:
                        $June += $balance;
                        break;
                        case 6:
                        $July += $balance;
                        break;
                        case 7:
                        $August += $balance;
                        break;
                        case 8:
                        $September += $balance;
                        break;
                        case 9:
                        $October += $balance;
                        break;
                        case 10:
                        $November += $balance;
                        break;
                        case 11:
                        $December += $balance;
                        break;
                        // default:
                        // $January += $balance;
                     }
                  }
               }
            }
         }
     // }
   }
      //echo $class_total; 
      $branch_total += $class_total;
      // echoClassTotal($class_total);
      echoStudentTotal($class_total);
   } else {
      //echoNotFound();
   }
}
// echoBranchTotal($branch_total);
switch ($month-1) {
   case 0:
      echoGrandTotal($January);
   break;
   case 1:
      echoGrandTotal($February);
   break;
   case 2:
      echoGrandTotal($March);
   break;
   case 3:
      echoGrandTotal($April);
   break;
   case 4:
      echoGrandTotal($May);
   break;
   case 5:
      echoGrandTotal($June);
   break;
   case 6:
      echoGrandTotal($January);
    $July += $balance;
   break;
   case 7:
      echoGrandTotal($August);
   break;
   case 8:
      echoGrandTotal($September);
   break;
   case 9:
      echoGrandTotal($October);
   break;
   case 10:
      echoGrandTotal($November);
   break;
   case 11:
      echoGrandTotal($December);
   break;
}


?>
<table class="uk-table sum-table">
   <thead>
      <tr>
         <th>Month/Year</th>
         <th>January</th>
         <th>February</th>
         <th>March</th>
         <th>April</th>
         <th>May</th>
         <th>June</th>
         <th>July</th>
         <th>August</th>
         <th>September</th>
         <th>October</th>
         <th>November</th>
         <th>December</th>
         <th>Total</th>
      </tr>
   </thead>
   <tbody>
      <!-- <tr>  
         <td style="width: 5%;"><?php echo $year; ?></td>  
         <td class="uk-text-right"><?php  echo $January; ?></td>  
         <td class="uk-text-right"><?php if($month==2){ echo $branch_total; }else{ echo $February;} ?></td>  
         <td class="uk-text-right"><?php if($month==3){ echo $branch_total; }else{  echo $March; }?></td>  
         <td class="uk-text-right"><?php if($month==4){ echo $branch_total; }else{  echo $April; }?></td>
         <td class="uk-text-right"><?php if($month==5){ echo $branch_total; }else{  echo $May; }?></td>
         <td class="uk-text-right"><?php if($month==6){ echo $branch_total; }else{  echo $June; }?></td>
         <td class="uk-text-right"><?php if($month==7){ echo $branch_total; }else{  echo $July; }?></td>
         <td class="uk-text-right"><?php if($month==8){ echo $branch_total; }else{  echo $August; }?></td>
         <td class="uk-text-right"><?php if($month==9){ echo $branch_total; }else{  echo $September; }?></td>
         <td class="uk-text-right"><?php if($month==10){ echo $branch_total; }else{  echo $October; }?></td>
         <td class="uk-text-right"><?php if($month==11){ echo $branch_total; }else{  echo $November; }?></td>
         <td class="uk-text-right"><?php if($month==12){ echo $branch_total; }else{  echo $December; }?></td>
         <td class="uk-text-right"><?php //echo $Total; ?></td>
      </tr> -->
      <tr>  
         <td style="width: 5%;"><?php echo $year; ?></td>  
         <td class="uk-text-right"><?php  echo $January; ?></td>  
         <td class="uk-text-right"><?php echo $February; ?></td>  
         <td class="uk-text-right"><?php echo $March; ?></td>  
         <td class="uk-text-right"><?php echo $April; ?></td>
         <td class="uk-text-right"><?php echo $May; ?></td>
         <td class="uk-text-right"><?php echo $June; ?></td>
         <td class="uk-text-right"><?php echo $July; ?></td>
         <td class="uk-text-right"><?php echo $August; ?></td>
         <td class="uk-text-right"><?php echo $September; ?></td>
         <td class="uk-text-right"><?php echo $October; ?></td>
         <td class="uk-text-right"><?php echo $November; ?></td>
         <td class="uk-text-right"><?php echo $December; ?></td>
         <td class="uk-text-right"><?php echo $Total; ?></td>
      </tr>
   </tbody>
</table>
<script>
   $(document).ready(function() {
      var method = '';
      if (method == "print") {
         window.print();
      }
   });
</script>
<style>
.nice-form table td.uk-text-right:last-child {
    text-align: right!important;  
}
.sum-table td, th {
    border: 1px solid #8080804f;
   }
</style></tbody></table>
<script>
   $(document).ready(function() {
      var method = '<?php echo $method ?>';
      if (method == "print") {
         window.print();
      }
   });
</script>
<style>
   .nice-form table td.uk-text-right:last-child {
    text-align: right!important;
}
</style>