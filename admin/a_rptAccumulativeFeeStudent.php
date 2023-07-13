<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //$centre_code, $selected_month (MMYYYY)
}

if ($method=="print") {
   include_once("../uikit1.php");
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

function getStudentName($student) {
   global $connection;
   $student_sql="SELECT name from student where name like '$student%' or student_code like '%$student%'";
   $result=mysqli_query($connection, $student_sql);
   $row=mysqli_fetch_assoc($result);

   return $row['name'];
}

function getCourseFee($course_id) {
   global $connection;

   $sql="SELECT fees from course where id='$course_id'";

   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["fees"];
}

function getMonthTerm($selected_month, &$month, &$term) {
   $str_length = strlen($selected_month);
   $themonth=substr($selected_month+$num, ($str_length - 2), 2);
   $theyear=substr($selected_month, 0, -2);

   switch ($themonth) {
      case "1" : $term="t1"; break;
      case "2" : $term="t1"; break;
      case "3" : $term="t2"; break;
      case "4" : $term="t2"; break;
      case "5" : $term="t3"; break;
      case "6" : $term="t3"; break;
      case "7" : $term="t4"; break;
      case "8" : $term="t4"; break;
      case "9" : $term="t5"; break;
      case "10" : $term="t5"; break;
      case "11" : $term="t5"; break;
      case "12" : $term="t5"; break;
   }

   if ($themonth<10) {
      $themonth=substr($themonth, 1, 1);
   }

   $month=$theyear.$themonth;
   $term=$theyear.$term;
}

function convertDateFormat($date) {
   $request_Month=substr($date, 4, 2);
   $request_Year=substr($date, 0, -2);
   return $request_Year.'-'.$request_Month.'-'.cal_days_in_month(CAL_GREGORIAN, $request_Month, $request_Year);
} 

function calcCurrentReceived($centre_code, $month,  $student) {
   //echo $month;
   global $connection;
   //$request_Month=intval(substr($month, 4, 2));
   //$request_Year=substr($month, 0, -2);
   $year = $_SESSION['Year'];
   
      $sql=" SELECT sum(amount) as amount from(
      SELECT sum(amount) as amount from (SELECT c.amount from collection c, student s, programme_selection ps, student_fee_list fl where s.id=ps.student_id and ps.id = fl.programme_selection_id and c.student_id=s.id and c.year=$year and void=0 and c.centre_code='$centre_code' and c.student_id='$student' and s.deleted=0 and month(collection_date_time) = $month  group by c.id)ab ";
      //and (collection_pattern='Monthly' or collection_pattern ='')
      // if ($month<=3) {
      //    $sql.=" UNION ALL
      //    SELECT sum(amount) as amount from (SELECT c.amount from collection c, student s, programme_selection ps, student_fee_list fl where s.id=ps.student_id and ps.id = fl.programme_selection_id and c.student_id=s.id and c.year=$year and void=0 and c.centre_code='$centre_code' and c.student_id='$student' and s.deleted=0 and CONVERT(c.`collection_month`, UNSIGNED) = 1 and collection_pattern='Termly' and month(collection_date_time) = $month group by c.id)ab ";
      // }else if ($month<=6) {
      //    $sql.=" UNION ALL
      //    SELECT sum(amount) as amount from (SELECT c.amount from collection c, student s, programme_selection ps, student_fee_list fl where s.id=ps.student_id and ps.id = fl.programme_selection_id and c.student_id=s.id and c.year=$year and void=0 and c.centre_code='$centre_code' and c.student_id='$student' and s.deleted=0 and CONVERT(c.`collection_month`, UNSIGNED) = 2 and collection_pattern='Termly' and month(collection_date_time) = $month group by c.id)ab ";
      // }else if ($month<=9) {
      //    $sql.=" UNION ALL
      //    SELECT sum(amount) as amount from (SELECT c.amount from collection c, student s, programme_selection ps, student_fee_list fl where s.id=ps.student_id and ps.id = fl.programme_selection_id and c.student_id=s.id and c.year=$year and void=0 and c.centre_code='$centre_code' and c.student_id='$student' and s.deleted=0 and CONVERT(c.`collection_month`, UNSIGNED) = 3 and collection_pattern='Termly' and month(collection_date_time) = $month group by c.id)ab ";
      // }else if ($month<=12) {
      //    $sql.=" UNION ALL
      //    SELECT sum(amount) as amount from (SELECT c.amount from collection c, student s, programme_selection ps, student_fee_list fl where s.id=ps.student_id and ps.id = fl.programme_selection_id and c.student_id=s.id and c.year=$year and void=0 and c.centre_code='$centre_code' and c.student_id='$student' and s.deleted=0 and CONVERT(c.`collection_month`, UNSIGNED) = 4 and collection_pattern='Termly' and month(collection_date_time) = $month group by c.id)ab ";
      // }

      // if ($month<=6) {
      //    $sql.=" UNION ALL
      //    SELECT sum(amount) as amount from (SELECT c.amount from collection c, student s, programme_selection ps, student_fee_list fl where s.id=ps.student_id and ps.id = fl.programme_selection_id and c.student_id=s.id and c.year=$year and void=0 and c.centre_code='$centre_code' and c.student_id='$student' and s.deleted=0 and CONVERT(c.`collection_month`, UNSIGNED) = 1 and collection_pattern='Half Year' and month(collection_date_time) = $month group by c.id)ab ";
      // }else if ($month<=12) {
      //    $sql.=" UNION ALL
      //    SELECT sum(amount) as amount from (SELECT c.amount from collection c, student s, programme_selection ps, student_fee_list fl where s.id=ps.student_id and ps.id = fl.programme_selection_id and c.student_id=s.id and c.year=$year and void=0 and c.centre_code='$centre_code' and c.student_id='$student' and s.deleted=0 and CONVERT(c.`collection_month`, UNSIGNED) = 2 and collection_pattern='Half Year' and month(collection_date_time) = $month group by c.id)ab ";
      // }
     
      //    $sql.=" UNION ALL
      //    SELECT sum(amount) as amount from (SELECT c.amount from collection c, student s, programme_selection ps, student_fee_list fl where s.id=ps.student_id and ps.id = fl.programme_selection_id and c.student_id=s.id and c.year=$year and void=0 and c.centre_code='$centre_code' and c.student_id='$student' and s.deleted=0 and CONVERT(c.`collection_month`, UNSIGNED) = 1 and collection_pattern='Annually' and month(collection_date_time) = $month group by c.id)ab ";
         $sql.=" )cd ";
      //echo $sql;
      //CONVERT(c.`collection_month`, UNSIGNED) = $month
      //month(collection_date_time) = $month and
      //between month(g.start_date) and month(g.end_date)
      //and CONVERT(c.`collection_month`, UNSIGNED) = $request_Month
      // if($student=="1227"){
       //echo $sql; //die; 
      // }
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return $row["amount"];
}

function calcAdvanceReceivedInThisMonth($centre_code, $month,  $student) {
   global $connection;
   //$request_Month=intval(substr($month, 4, 2));
   //$request_Year=substr($month, 0, -2);
   $year = $_SESSION['Year'];
   
      $sql="SELECT sum(amount) as amount from (SELECT c.amount from collection c, student s, programme_selection ps, student_fee_list fl where s.id=ps.student_id and ps.id = fl.programme_selection_id and c.student_id=s.id and c.year=$year and void=0 and c.centre_code='$centre_code' and c.student_id='$student' and s.deleted=0 and month(c.collection_date_time) = $month and CONVERT(c.`collection_month`, UNSIGNED) > $month group by c.id)ab";
      //CONVERT(c.`collection_month`, UNSIGNED) = $month
      //month(collection_date_time) = $month and
      //between month(g.start_date) and month(g.end_date)
      //and CONVERT(c.`collection_month`, UNSIGNED) = $request_Month
      // if($student=="1227"){
       //echo $sql; //die; 
      // }
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return $row["amount"];
}
function calcAdvanceReceived($centre_code, $month,  $student) {
   global $connection;
   //$request_Month=intval(substr($month, 4, 2));
   //$request_Year=substr($month, 0, -2);
   $year = $_SESSION['Year'];
   
      $sql="SELECT sum(amount) as amount from (SELECT c.amount from collection c, student s, programme_selection ps, student_fee_list fl where s.id=ps.student_id and ps.id = fl.programme_selection_id and c.student_id=s.id and c.year=$year and void=0 and c.centre_code='$centre_code' and c.student_id='$student' and s.deleted=0 and CONVERT(c.`collection_month`, UNSIGNED) > $month and month(fl.programme_date)<=$month group by c.id)ab ";
      //month(collection_date_time) = $month and
      //between month(g.start_date) and month(g.end_date)
      //and CONVERT(c.`collection_month`, UNSIGNED) = $request_Month
      // if($student=="1227"){
      // echo $sql; //die; 
      // }
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return $row["amount"];
}
function calcLastMonthReceived($centre_code, $month,  $student) {
   global $connection;
   //$request_Month=intval(substr($month, 4, 2));
   //$request_Year=substr($month, 0, -2);
   $year = $_SESSION['Year'];
   $lastMonth = $month-1;
      $sql="SELECT sum(amount) as amount from (SELECT c.amount from collection c, student s, programme_selection ps, student_fee_list fl where s.id=ps.student_id and ps.id = fl.programme_selection_id and c.student_id=s.id and c.year=$year and void=0 and c.centre_code='$centre_code' and c.student_id='$student' and s.deleted=0 and CONVERT(c.`collection_month`, UNSIGNED) = $month and month(c.collection_date_time) = $lastMonth group by c.id)ab";
      //month(collection_date_time) = $month and
      //between month(g.start_date) and month(g.end_date)
      //and CONVERT(c.`collection_month`, UNSIGNED) = $request_Month
      // if($student=="1227"){
      // echo $sql; //die; 
      // }
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return $row["amount"];
}
function calcDiscountOffered($centre_code, $month,  $student) {
   global $connection;
   $year = $_SESSION['Year'];
   //$sql="SELECT sum(c.discount) as discount from collection c, allocation a where concat(c.year, month(c.collection_date_time))=$month and void=0 and collection_type='tuition' and centre_code='$centre_code' and c.allocation_id=a.id and a.course_id='$course' and a.student_id='$student' and a.deleted=0";

   //$sql="SELECT sum(c.discount) as discount from collection c, allocation a wherec.year='$year' and month(c.collection_date_time)=$month and void=0 and collection_type='tuition' and centre_code='$centre_code' and c.allocation_id=a.id and a.student_id='$student' and a.deleted=0";

   $sql=" SELECT sum(discount) as discount from (SELECT c.discount from collection c, student s, programme_selection ps, student_fee_list fl where s.id=ps.student_id and ps.id = fl.programme_selection_id and c.student_id=s.id and c.year=$year and void=0 and c.centre_code='$centre_code' and c.student_id='$student' and s.deleted=0 and CONVERT(c.`collection_month`, UNSIGNED) = $month group by c.id)ab ";

   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return $row["discount"];
}

function calcCreditNote($centre_code, $month,  $student) {
   global $connection;
   $year = $_SESSION['Year'];
   //$sql="SELECT sum(c.amount) as amount from collection c, allocation a where concat(c.year, month(c.collection_month))=$month and void=1 and cn_no<>'' and collection_type='tuition' and centre_code='$centre_code' and c.allocation_id=a.id and a.course_id='$course' and a.student_id='$student' and a.deleted=0"; 
   //$sql="SELECT sum(c.amount) as amount from collection c, allocation a where c.year='$year' and month(c.collection_date_time)=$month and void=1 and cn_no<>'' and collection_type='tuition' and centre_code='$centre_code' and c.allocation_id=a.id and a.student_id='$student' and a.deleted=0";
   $sql="SELECT sum(amount) as amount from (SELECT c.amount from collection c, student s, programme_selection ps, student_fee_list fl where s.id=ps.student_id and ps.id = fl.programme_selection_id and c.student_id=s.id and c.year=$year and void=1 and cn_no<>'' and c.centre_code='$centre_code' and c.student_id='$student' and s.deleted=0 and CONVERT(c.`collection_month`, UNSIGNED) = $month group by c.id)ab ";
   //echo $sql;
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return $row["amount"];
} 

function calcAdvancePayment($centre_code, $month, $student) {
   //$already_paid=calcAlreadyPayFeeB4($centre_code, $month,  $student);
   //$should_pay=calcShouldPayFeeB4($centre_code, $month,  $student);
   $already_paid=0;
   $should_pay=0;
   for ($i=1; $i < $month; $i++) { 
      $already_paid+=calcCurrentReceived($centre_code, $i,  $student);
      $should_pay +=calcCurrentMonthCourseFees($centre_code, $i,  $student);
   }

   return $already_paid - $should_pay;
}

// function calcAdvancePayment($centre_code, $month,  $student) {
//    global $connection;
//    $request_Month=intval(substr($month, 4, 2));
//    $request_Year=substr($month, 0, -2);

//    $sql="SELECT sum(c.amount) - sum(c.fees) as amount from collection c, allocation a LEFT JOIN `group` g on g.id=a.group_id where c.collection_month < '$request_Month' and c.year='$request_Year' and void=0 and collection_type='tuition' and c.centre_code='$centre_code' and c.allocation_id=a.id and a.course_id='$course' and a.student_id='$student' and a.deleted=0";

//    $result=mysqli_query($connection, $sql);
//    $row=mysqli_fetch_assoc($result);

//    return number_format($row["amount"], 2);
// }

function getAllocatedDateTime($centre_code, $month,  $student) {
   global $connection;

   getMonthTerm($month, $period, $term_period);

   $sql="SELECT a.id, a.allocated_date_time from allocation a, student s where a.student_id=s.id and s.centre_code='$centre_code' and student_id='$student' and a.deleted=0";

   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   if ($row["allocated_date_time"]=="0000-00-00 00:00:00") {
      return $year_start_date;
   } else {
      return $row["allocated_date_time"];
   }

}

function getCourseType($course_id) {
   global $connection;

   $sql="SELECT * from course where id='$course_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   $array_course_name=trim(explode($row["course_name"], "-"));
   $course_name=$array_course_name[0];

   $char_2=substr($course_name, 0, 2);
   $char_4=substr($course_name, 0, 4);

   if ($char_2=="IE") {
      return $char_2;
   } else {
      return $char_4;
   }
}

function month_involved($group_id, $payment_type){
   global $connection;
   $strMonth = [];
   $sql="SELECT * from `group` where id='$group_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   $start_month=explode('-', ($row["start_date"]));
   if ($payment_type == 'Monthly') {
      $end_month=explode('-', ($row["end_date"]));
      $total_month = (intval($end_month[1]) - intval($start_month[1])) + 1;
      for ($i=0; $i < $total_month; $i++) { 
         $strMonth[] = intval($start_month[1]) + $i;
      }
   } else {
      $total_month = 1;
      $strMonth[] = intval($start_month[1]);
   }

   return [$total_month, $strMonth];
}
function calcAlreadyPayFeeB4($centre_code, $month,  $student) {
   global $connection;
   //$request_Month=intval(substr($month, 4, 2));
   //$request_Year=substr($month, 0, -2);
   $year = $_SESSION['Year'];
   $month = $month-1;
   $sql=" SELECT sum(amount) as amount from(
      SELECT sum(amount)+sum(discount) as amount from (SELECT c.amount, c.discount from collection c, student s, programme_selection ps, student_fee_list fl where s.id=ps.student_id and ps.id = fl.programme_selection_id and c.student_id=s.id and c.year=$year and c.centre_code='$centre_code' and c.student_id='$student' and s.deleted=0 and CONVERT(c.`collection_month`, UNSIGNED) = $month and collection_pattern='Monthly' group by c.id)ab";
      
      if ($month<=3) {
         $sql.=" UNION ALL
         SELECT sum(amount) as amount from (SELECT c.amount from collection c, student s, programme_selection ps, student_fee_list fl where s.id=ps.student_id and ps.id = fl.programme_selection_id and c.student_id=s.id and c.year=$year and void=0 and c.centre_code='$centre_code' and c.student_id='$student' and s.deleted=0 and CONVERT(c.`collection_month`, UNSIGNED) = 1 and collection_pattern='Termly' and month(collection_date_time) = $month group by c.id)ab ";
      }else if ($month<=6) {
         $sql.=" UNION ALL
         SELECT sum(amount) as amount from (SELECT c.amount from collection c, student s, programme_selection ps, student_fee_list fl where s.id=ps.student_id and ps.id = fl.programme_selection_id and c.student_id=s.id and c.year=$year and void=0 and c.centre_code='$centre_code' and c.student_id='$student' and s.deleted=0 and CONVERT(c.`collection_month`, UNSIGNED) = 2 and collection_pattern='Termly' and month(collection_date_time) = $month group by c.id)ab ";
      }else if ($month<=9) {
         $sql.=" UNION ALL
         SELECT sum(amount) as amount from (SELECT c.amount from collection c, student s, programme_selection ps, student_fee_list fl where s.id=ps.student_id and ps.id = fl.programme_selection_id and c.student_id=s.id and c.year=$year and void=0 and c.centre_code='$centre_code' and c.student_id='$student' and s.deleted=0 and CONVERT(c.`collection_month`, UNSIGNED) = 3 and collection_pattern='Termly' and month(collection_date_time) = $month group by c.id)ab ";
      }else if ($month<=12) {
         $sql.=" UNION ALL
         SELECT sum(amount) as amount from (SELECT c.amount from collection c, student s, programme_selection ps, student_fee_list fl where s.id=ps.student_id and ps.id = fl.programme_selection_id and c.student_id=s.id and c.year=$year and void=0 and c.centre_code='$centre_code' and c.student_id='$student' and s.deleted=0 and CONVERT(c.`collection_month`, UNSIGNED) = 4 and collection_pattern='Termly' and month(collection_date_time) = $month group by c.id)ab ";
      }

      if ($month<=6) {
         $sql.=" UNION ALL
         SELECT sum(amount) as amount from (SELECT c.amount from collection c, student s, programme_selection ps, student_fee_list fl where s.id=ps.student_id and ps.id = fl.programme_selection_id and c.student_id=s.id and c.year=$year and void=0 and c.centre_code='$centre_code' and c.student_id='$student' and s.deleted=0 and CONVERT(c.`collection_month`, UNSIGNED) = 1 and collection_pattern='Half Year' and month(collection_date_time) = $month group by c.id)ab ";
      }else if ($month<=12) {
         $sql.=" UNION ALL
         SELECT sum(amount) as amount from (SELECT c.amount from collection c, student s, programme_selection ps, student_fee_list fl where s.id=ps.student_id and ps.id = fl.programme_selection_id and c.student_id=s.id and c.year=$year and void=0 and c.centre_code='$centre_code' and c.student_id='$student' and s.deleted=0 and CONVERT(c.`collection_month`, UNSIGNED) = 2 and collection_pattern='Half Year' and month(collection_date_time) = $month group by c.id)ab ";
      }
  
         $sql.=" UNION ALL
         SELECT sum(amount) as amount from (SELECT c.amount from collection c, student s, programme_selection ps, student_fee_list fl where s.id=ps.student_id and ps.id = fl.programme_selection_id and c.student_id=s.id and c.year=$year and void=0 and c.centre_code='$centre_code' and c.student_id='$student' and s.deleted=0 and CONVERT(c.`collection_month`, UNSIGNED) = 1 and collection_pattern='Annually' and month(collection_date_time) = $month group by c.id)ab ";
         $sql.=" )cd";
      //echo $sql;
      //between month(g.start_date) and month(g.end_date)
      //and CONVERT(c.`collection_month`, UNSIGNED) = $request_Month
      // if($student=="1227"){
      // echo $sql; //die; 
      // }
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return $row["amount"];
}
// function calcAlreadyPayFeeB4($centre_code, $month,  $student) {
//    global $connection;

//    //$sql="SELECT sum(c.amount) as amount, c.* from collection c, allocation a LEFT JOIN `group` g on g.id=a.group_id where concat(c.year, RIGHT(100+c.collection_month,2))<$month and void=0 and c.collection_type='tuition' and c.centre_code='$centre_code' and c.allocation_id=a.id and a.student_id='$student' and a.deleted=0";
   
//    $sql="SELECT sum(c.amount) as amount, c.* from collection c where concat(c.year, RIGHT(100+c.collection_month,2))<$month and void=0 and c.centre_code='$centre_code'  c.student_id='$student'";
//    $result=mysqli_query($connection, $sql);
//    $row=mysqli_fetch_assoc($result);

//    return $row["amount"];
// }

function calcShouldPayFeeB4($centre_code, $month,  $student, $payment_type) {
   global $connection;
   $totalFees=0;

   $sql="SELECT a.*, g.start_date, g.end_date from allocation a LEFT JOIN `group` g on g.id=a.group_id where centre_code='$centre_code' and a.student_id='$student' and a.deleted=0";

   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   $monthInv = month_involved($row['group_id'], $payment_type);

   for ($i=0; $i < $monthInv[0]; $i++) { 
      $outputDate = $row['year'].''.str_pad($monthInv[1][$i], 2, "0", STR_PAD_LEFT);
      if ($outputDate < $month) {
         $totalFees = $totalFees + $row['fees'];
      }
   }

   return $totalFees;
}

function calcCurrentMonthCourseFees($centre_code, $month,  $student) {
   global $connection;
   //$month=substr($Yearmonth, 4, 2);
   //$year=substr($Yearmonth, 0, -2);
   $year = $_SESSION['Year'];
   $base_sql ="select sum(fees) fees from ( ";
// School Fees
$base_sql .=" select f.subject,'School Fees' as fee_name, sum(f.school_adjust) as fees, f.school_collection as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and f.school_adjust != 0 and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.id = '$student'";
if ($month != '13') {
   $base_sql .= " and case when f.school_collection='Monthly' then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month  
   when f.school_collection='Termly' and ((month(fl.programme_date)<=$month and $month in (1, 4, 7, 10) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
   when f.school_collection='Half Year' and ((month(fl.programme_date)<=$month and $month in (1, 7) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
   when f.school_collection='Annually' and ((month(fl.programme_date)<=$month and $month in (1) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
   end ";
  }  
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 
//echo $base_sql; die;
// $base_sql .=" group by f.subject, f.school_collection";

// Multimedia Fees
$base_sql .="  UNION ALL
select f.subject,'Multimedia Fees' as fee_name, sum(f.multimedia_adjust) as fees, f.multimedia_collection as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and f.multimedia_adjust != 0 and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.id = '$student' ";
if ($month != '13') {
   $base_sql .= " and case when f.multimedia_collection='Monthly' then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month  
   when f.multimedia_collection='Termly' and ((month(fl.programme_date)<=$month and $month in (1, 4, 7, 10) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
   when f.multimedia_collection='Half Year' and ((month(fl.programme_date)<=$month and $month in (1, 7) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
   when f.multimedia_collection='Annually' and ((month(fl.programme_date)<=$month and $month in (1) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
   end ";
  } 
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 
// $base_sql .=" group by f.subject, f.multimedia_collection ";

// Facility Fees
$base_sql .="  UNION ALL
select f.subject,'Facility Fees' as fee_name, sum(f.facility_adjust) as fees, f.facility_collection as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and f.facility_adjust != 0 and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.id = '$student' ";
if ($month != '13') {
   $base_sql .= " and case when f.facility_collection='Monthly' then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month  
   when f.facility_collection='Termly' and ((month(fl.programme_date)<=$month and $month in (1, 4, 7, 10) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
   when f.facility_collection='Half Year' and ((month(fl.programme_date)<=$month and $month in (1, 7) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
   when f.facility_collection='Annually' and ((month(fl.programme_date)<=$month and $month in (1) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
   end ";
  } 
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 
// $base_sql .=" group by f.subject, f.facility_collection ";

// Integrated Module
$base_sql .="  UNION ALL
select f.subject,'Integrated Module' as fee_name, sum(f.integrated_adjust) as fees, f.integrated_collection as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and f.integrated_adjust != 0 and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.id = '$student' ";
if ($month != '13') {
   $base_sql .= " and case when f.integrated_collection='Monthly' then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month  
   when f.integrated_collection='Termly' and ((month(fl.programme_date)<=$month and $month in (1, 4, 7, 10) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
   when f.integrated_collection='Half Year' and ((month(fl.programme_date)<=$month and $month in (1, 7) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
   when f.integrated_collection='Annually' and ((month(fl.programme_date)<=$month and $month in (1) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
   end ";
  } 
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 
// $base_sql .=" group by f.subject, f.integrated_collection ";

// Link & Think
$base_sql .="  UNION ALL
select f.subject,'Link & Think' as fee_name, sum(f.link_adjust) as fees, f.link_collection as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and f.link_adjust != 0 and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.id = '$student' ";
if ($month != '13') {
   $base_sql .= " and case when f.link_collection='Monthly' then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month  
   when f.link_collection='Termly' and ((month(fl.programme_date)<=$month and $month in (1, 4, 7, 10) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
   when f.link_collection='Half Year' and ((month(fl.programme_date)<=$month and $month in (1, 7) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
   when f.link_collection='Annually' and ((month(fl.programme_date)<=$month and $month in (1) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
   end ";
  } 
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 
// $base_sql .=" group by f.subject, f.link_collection ";

// Mobile app
$base_sql .="  UNION ALL
select f.subject,'Mobile app' as fee_name, sum(f.mobile_adjust) as fees, f.mobile_collection as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and f.mobile_adjust != 0 and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.id = '$student' ";
if ($month != '13') {
   $base_sql .= " and case when f.mobile_collection='Monthly' then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month  
   when f.mobile_collection='Termly' and ((month(fl.programme_date)<=$month and $month in (1, 4, 7, 10) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
   when f.mobile_collection='Half Year' and ((month(fl.programme_date)<=$month and $month in (1, 7) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
   when f.mobile_collection='Annually' and ((month(fl.programme_date)<=$month and $month in (1) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
   end ";
  } 
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 
// $base_sql .=" group by f.subject, f.mobile_collection ";
//echo $base_sql; die;
// Registration
$base_sql .="  UNION ALL
select f.subject,'Registration' as fee_name, sum(f.registration_adjust) as fees, 'Annually' as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and f.registration_adjust != 0 and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.id = '$student' ";
if ($month != '13') {
   $base_sql .= " and case when ((month(fl.programme_date)<=$month and $month in (1) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month end ";
  } 
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 
// $base_sql .=" group by f.subject ";

// Insurance
$base_sql .="  UNION ALL
select f.subject,'Insurance' as fee_name, sum(f.insurance_adjust) as fees, 'Annually' as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and f.insurance_adjust != 0 and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.id = '$student' ";
if ($month != '13') {
   $base_sql .= " and case when ((month(fl.programme_date)<=$month and $month in (1) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month end ";
  } 
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 
// $base_sql .=" group by f.subject ";

// Q-dees Level Kit
$base_sql .="  UNION ALL
select f.subject,'Q-dees Level Kit' as fee_name, sum(f.q_dees_adjust) as fees, 'Annually' as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and f.q_dees_adjust != 0 and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.id = '$student' ";
if ($month != '13') {
   $base_sql .= " and case when ((month(fl.programme_date)<=$month and $month in (1) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month end ";
  } 
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 
// $base_sql .=" group by f.subject ";

// Uniform (2 sets)
$base_sql .="  UNION ALL
select f.subject,'Uniform (2 sets)' as fee_name, sum(fl.uniform_adjust) as fees, 'Annually' as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and fl.uniform_adjust != 0 and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.id = '$student' ";
if ($month != '13') {
   $base_sql .= " and case when ((month(fl.programme_date)<=$month and $month in (1) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month end ";
  } 
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 
// $base_sql .=" group by f.subject ";

// Gymwear (1 set)
$base_sql .="  UNION ALL
select f.subject,'Gymwear (1 set)' as fee_name, sum(fl.gymwear_adjust) as fees, 'Annually' as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and fl.gymwear_adjust != 0 and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.id = '$student' ";
if ($month != '13') {
   $base_sql .= " and case when ((month(fl.programme_date)<=$month and $month in (1) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month end ";
  } 
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 
// $base_sql .=" group by f.subject ";

// Q-dees Bag
$base_sql .="  UNION ALL
select f.subject,'Q-dees Bag' as fee_name, sum(f.q_bag_adjust) as fees, 'Annually' as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and f.q_bag_adjust != 0 and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.id = '$student' ";
if ($month != '13') {
   $base_sql .= " and case when ((month(fl.programme_date)<=$month and $month in (1) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month end ";
  } 
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 
// $base_sql .=" group by f.subject ";

// Mandarin Modules
$base_sql .=" UNION ALL
select f.subject,'Mandarin Modules' as fee_name, sum(f.mandarin_m_adjust) as fees, f.mandarin_m_collection as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and fl.foundation_mandarin =1 and f.mandarin_m_adjust != 0  and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.id = '$student' ";
if ($month != '13') {
   $base_sql .= " and case when f.mandarin_m_collection='Monthly' then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month  
   when f.mandarin_m_collection='Termly' and ((month(fl.programme_date)<=$month and $month in (1, 4, 7, 10) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
   when f.mandarin_m_collection='Half Year' and ((month(fl.programme_date)<=$month and $month in (1, 7) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
   when f.mandarin_m_collection='Annually' and ((month(fl.programme_date)<=$month and $month in (1) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
   end ";
  } 
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 
//$base_sql .=" group by f.subject, f.mandarin_m_collection";

// IQ Math
$base_sql .=" UNION ALL
select f.subject,'IQ Math' as fee_name, sum(f.iq_math_adjust) as fees, f.iq_math_collection as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and fl.foundation_iq_math =1 and f.iq_math_adjust != 0   and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.id = '$student' ";
if ($month != '13') {
   //$base_sql .= " and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.id = '$student'  and fl.programme_date<='$selectedDateTime'";
   $base_sql .= " and case when f.iq_math_collection='Monthly' then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month  
   when f.iq_math_collection='Termly' and ((month(fl.programme_date)<=$month and $month in (1, 4, 7, 10) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
   when f.iq_math_collection='Half Year' and ((month(fl.programme_date)<=$month and $month in (1, 7) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
   when f.iq_math_collection='Annually' and ((month(fl.programme_date)<=$month and $month in (1) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
   end ";
  } 
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 
//$base_sql .=" group by f.subject, f.iq_math_collection";

// Mandarin
$base_sql .=" UNION ALL
select f.subject,'Mandarin' as fee_name, sum(f.mandarin_adjust) as fees, f.mandarin_collection as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and fl.foundation_int_mandarin =1 and f.mandarin_adjust != 0   and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.id = '$student' ";
if ($month != '13') {
   //$base_sql .= " and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.id = '$student'  and fl.programme_date<='$selectedDateTime'";
   $base_sql .= " and case when f.mandarin_collection='Monthly' then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month  
   when f.mandarin_collection='Termly' and ((month(fl.programme_date)<=$month and $month in (1, 4, 7, 10) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
   when f.mandarin_collection='Half Year' and ((month(fl.programme_date)<=$month and $month in (1, 7) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
   when f.mandarin_collection='Annually' and ((month(fl.programme_date)<=$month and $month in (1) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
   end ";
  } 
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 
//$base_sql .=" group by f.subject, f.mandarin_collection";

// International Art
$base_sql .=" UNION ALL
select f.subject,'International Art' as fee_name, sum(f.mandarin_adjust) as fees, f.international_collection as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and fl.foundation_int_art =1 and f.mandarin_adjust != 0   and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.id = '$student' ";
if ($month != '13') {
   //$base_sql .= " and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.id = '$student'  and fl.programme_date<='$selectedDateTime'";
   $base_sql .= " and case when f.international_collection='Monthly' then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month  
   when f.international_collection='Termly' and ((month(fl.programme_date)<=$month and $month in (1, 4, 7, 10) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
   when f.international_collection='Half Year' and ((month(fl.programme_date)<=$month and $month in (1, 7) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
   when f.international_collection='Annually' and ((month(fl.programme_date)<=$month and $month in (1) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
   end ";
  } 
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 
//$base_sql .=" group by f.subject, f.international_collection";

// International English
$base_sql .=" UNION ALL
select f.subject,'International English' as fee_name, sum(f.enhanced_adjust) as fees, f.enhanced_collection as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and fl.foundation_int_english =1 and f.enhanced_adjust != 0   and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.id = '$student' ";
if ($month != '13') {
   //$base_sql .= " and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.id = '$student'  and fl.programme_date<='$selectedDateTime'";
   $base_sql .= " and case when f.enhanced_collection='Monthly' then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month  
   when f.enhanced_collection='Termly' and ((month(fl.programme_date)<=$month and $month in (1, 4, 7, 10) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
   when f.enhanced_collection='Half Year' and ((month(fl.programme_date)<=$month and $month in (1, 7) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
   when f.enhanced_collection='Annually' and ((month(fl.programme_date)<=$month and $month in (1) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
   end ";
  } 
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 
//$base_sql .=" group by f.subject, f.enhanced_collection";

// Pendidikan Islam
$base_sql .=" UNION ALL
select f.subject,'Pendidikan Islam' as fee_name, sum(f.islam_adjust) as fees, 'Annually' as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and fl.pendidikan_islam =1 and f.islam_adjust != 0   and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.id = '$student' ";
if ($month != '13') {
   $base_sql .= " and case when ((month(fl.programme_date)<=$month and $month in (1) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month end ";
  } 
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 
//$base_sql .=" group by f.subject";

// Afternoon Programme
$base_sql .=" UNION ALL
select f.subject,'Afternoon Programme' as fee_name, sum(f.basic_adjust) as fees, f.basic_collection as payment_type, s.extend_year 
from student s, programme_selection ps, student_fee_list fl, fee_structure f 
where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.`student_status`='A' and fl.afternoon_programme =1 and f.basic_adjust != 0   and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.id = '$student' ";
if ($month != '13') {
   //$base_sql .= " and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.id = '$student'  and fl.programme_date<='$selectedDateTime'";
   $base_sql .= " and case when f.basic_collection='Monthly' then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month  
   when f.basic_collection='Termly' and ((month(fl.programme_date)<=$month and $month in (1, 4, 7, 10) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
   when f.basic_collection='Half Year' and ((month(fl.programme_date)<=$month and $month in (1, 7) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
   when f.basic_collection='Annually' and ((month(fl.programme_date)<=$month and $month in (1) or month(fl.programme_date)=$month)) then  month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month
   end ";
  } 
if($centre_code != "ALL"){
   $base_sql .=" and s.centre_code='$centre_code'";
} 


// $sql11="select  fl.programme_date, fl.programme_date_end from student s, programme_selection ps, student_fee_list fl 
// where s.id=ps.student_id and ps.id = fl.programme_selection_id and s.`student_status`='A' and year(fl.programme_date)='$year' and s.id = '$student' and month(fl.programme_date)<=$month and month(fl.programme_date_end) >=$month ";
// //echo $sql;
// $result11=mysqli_query($connection, $sql11);
// //$row=mysqli_fetch_assoc($result);
// $num_row=mysqli_num_rows($result11);

// if ($num_row>0) {
//    $end_date_addon= "";
//    $z=0;
//    while ($row111 = mysqli_fetch_assoc($result11)) {
//       if($z==0){
//          $programme_date = $row111['programme_date'];
//       }
//       $programme_date_end = $row111['programme_date_end'];
//       $z++;
//    }
// }

// if ($num_row>0) {
//    $programme_start_month = explode('-', ($programme_date))[1];
//    //echo $programme_start_month;
//   // $programme_date = $row['programme_date'];
//   //echo $num_row;
//    //if($programme_date <= $month){
//       $base_sql .= " UNION ALL
//       select 'Add-On Products' as subject, `product_code` as fee_name, unit_price as fees, `monthly` as payment_type, '$year' as extend_year from addon_product where status='Approved' and `monthly`='Monthly'";
//       if($centre_code != "ALL"){
//          $base_sql .=" and centre_code='$centre_code'";
//       } 
      
//       if($month == 1 || $month == 4 || $month == 7 || $month == 10 || $programme_start_month == $month){
//       $base_sql .= " UNION ALL
//       select 'Add-On Products' as subject, `product_code` as fee_name, unit_price as fees, `monthly` as payment_type, '$year' as extend_year from addon_product where status='Approved' and `monthly`='Termly'";
//       if($centre_code != "ALL"){
//          $base_sql .=" and centre_code='$centre_code'";
//       }
//       }

//       if($month == 1 || $month == 7 || $programme_start_month == $month){
//       $base_sql .= " UNION ALL
//       select 'Add-On Products' as subject, `product_code` as fee_name, unit_price as fees, `monthly` as payment_type, '$year' as extend_year from addon_product where status='Approved' and `monthly`='Half Year'";
//       if($centre_code != "ALL"){
//          $base_sql .=" and centre_code='$centre_code'";
//       }
//       }

//       if($month == 1 || $programme_start_month == $month){
//       $base_sql .= " UNION ALL
//       select 'Add-On Products' as subject, `product_code` as fee_name, unit_price as fees, `monthly` as payment_type, '$year' as extend_year from addon_product where status='Approved' and `monthly`='Annually'";
//       if($centre_code != "ALL"){
//          $base_sql .=" and centre_code='$centre_code'";
//       }
//       }
      
//    //}
// }

$base_sql .=" ) rpt ";
//echo $base_sql; 
//echo $base_sql; 
//  if($month==4){
    //echo $base_sql; die; 
//  }

   $result=mysqli_query($connection, $base_sql);
   $row=mysqli_fetch_assoc($result);

   echo $base_sql;
   // if($student=="1140"){
   //   echo number_format($row["fees"], 2); //die; 
   //  }

   return $row["fees"];
}

function calcOpeningDebt($centre_code, $month,  $student) {
   // $already_paid=calcAlreadyPayFeeB4($centre_code, $month,  $student);
   $already_paid=0;
   $should_pay=0;
   for ($i=1; $i < $month; $i++) { 
     $already_paid+=calcCurrentReceived($centre_code, $i,  $student);
      $should_pay +=calcCurrentMonthCourseFees($centre_code, $i,  $student);
   }
   //$should_pay=calcShouldPayFeeB4($centre_code, $month,  $student, $payment_type);

   return $should_pay-$already_paid;
}
?>
<style type="text/css">
  @media print {
  #note{
    display:none;
  }
}
</style>

<br>
<div class="uk-width-1-1 myheader text-center mt-5" style="color:white; text-align:center;">
   <h2 class="uk-text-center myheader-text-color myheader-text-style">ACCUMULATIVE FEE REPORT</h2>
<?php
if ($selected_month == '201913' || $selected_month == '202013') {
  echo "Selected Month : All Month<br>";
}else{
   $str_length = strlen($selected_month);
   $monthNum = substr($selected_month, ($str_length - 2), 2);
   $year = substr($selected_month, 0, -2);
  
$dateObj   = DateTime::createFromFormat('!m', $monthNum);
$monthName = $dateObj->format('F');
   echo "Selected Month : $monthName $year <br>";
}
$center_name = getCentreName($centre_code);
if ($centre_code=="") {
   echo "Centre ALL";
} else {
   echo "Centre $center_name";
}
?>
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
                  <td><?php 
                     if(!empty($selected_month)) {
                        $str_length = strlen($selected_month);
                        echo str_split($selected_month, ($str_length - 2))[0];
                     } else { 
                        echo $_SESSION['Year'];
                     }
                  ?></td>
         </tr>
         <tr>
            <td class="uk-text-bold">School Term</td>
            <td>
            <?php
                     if (isset($selected_month) && $selected_month != '') {
                        $str_length = strlen($selected_month);
                        $month = substr($selected_month, ($str_length - 2), 2);
                        $year = substr($selected_month, 0, -2);
                        $sql = "SELECT * from codes where year=" . $year;
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
                  }
                     ?>
            </td>
         </tr>
         <tr>
            <td class="uk-text-bold">Date of submission</td>
            <td><?php echo date("Y-m-d H:i:s")?></td>
         </tr>
         <tr id="note" style="display: none;">
            <td colspan="2" class="uk-text-bold">NOTE: To be submitted termly with the order form</td>
         </tr>
      </table>
   </div>
</div>

<table class="uk-table uk-text-small" style="width: 100%;">
   <tr class="uk-text-bold">
      <th>No.</th>
      <th>Student No.</th>
      <th>Name of student</th>
      <th class="uk-text-right">Opening Advance</th>
      <th class="uk-text-right">Opening DEBT</th>
      <th class="uk-text-right">Current MTH Fees</th>
      <th class="uk-text-right">Current Received</th>
      <th class="uk-text-right">Current Outstanding</th>
      <th class="uk-text-right">Closing Advance</th>
      <th class="uk-text-right">Closing DEBT</th>
      <th class="uk-text-right">Advance Adjustment</th>
      <th class="uk-text-right">DEBT Adjustment</th>
      <th class="uk-text-right">Discount</th>
      <th class="uk-text-right">Credit Note</th>
  </tr>

<?php
if (($centre_code!="") & ($selected_month!="")) {
   // if ($centre_code=="ALL") {
   //    $sql="SELECT distinct s.centre_code from allocation a, student s, course c where a.course_id=c.id and a.student_id=s.id and a.deleted=0 order by s.centre_code";
   // } else {
   //    $sql="SELECT distinct s.centre_code from allocation a, student s, course c where a.course_id=c.id and a.student_id=s.id
   //    and s.centre_code='$centre_code' and a.deleted=0";
   // }
   // $result=mysqli_query($connection, $sql);
   $count1=0;
   $count=0;

   //while ($row=mysqli_fetch_assoc($result)) {
      //if ($student) {
      //$sql="SELECT distinct c.course_name, c.id, s.centre_code from allocation a, student s, course c where a.course_id=c.id and a.student_id=s.id and a.deleted=0 and (s.student_code like '%".$student."%' or s.name like '".$student."%') ";
     // }else{
 //$sql="SELECT distinct c.course_name, c.id, s.centre_code from allocation a, student s, course c where a.course_id=c.id and a.student_id=s.id  and a.deleted=0 ";
 //$sql="SELECT * from centre c where 1=1 ";
 
      //}
      //if ($centre_code!="ALL") {
        // $sql .= "and c.centre_code='$centre_code'";
     // }
      //$sql .= " order by c.company_name ";
      
      //$result_course=mysqli_query($connection, $sql);

      //while ($row_course=mysqli_fetch_assoc($result_course)) {
        // $centre_code = $row_course['centre_code'];
        // echo $student;
        if ($student) {
         $sql="SELECT distinct s.id, s.student_code, s.name, f.subject, fl.programme_date, fl.programme_date_end from student s, programme_selection ps, student_fee_list fl,  fee_structure f where ps.student_id=s.id and fl.programme_selection_id = ps.id and f.id=fl.fee_id and s.deleted=0 and (s.student_code like '%".$student."%' or s.name like '".$student."%') and s.extend_year >= $year ";
      }else{
          $sql="SELECT distinct s.id, s.student_code, s.name, f.subject, fl.programme_date, fl.programme_date_end from student s, programme_selection ps, student_fee_list fl,  fee_structure f where ps.student_id=s.id and fl.programme_selection_id = ps.id and f.id=fl.fee_id and s.deleted=0 and s.extend_year >= $year ";
      }
         if ($centre_code!="ALL" && $centre_code!="") {
            $sql .= " and s.centre_code='$centre_code'";
         }
         $sql .= " group by s.student_code ORDER BY `s`.`student_code` ASC ";

         $count1++;
         $str_length = strlen($selected_month);
         $_month=substr($selected_month, ($str_length - 2), 2);
         $result_student=mysqli_query($connection, $sql);
         while ($row_student=mysqli_fetch_assoc($result_student)) {
            $student_code = $row_student["student_code"];
         $name = $row_student["name"];
         $programme_date = $row_student["programme_date"];
         $programme_start_month =explode('-', ($programme_date))[1];

            $advance_received=calcAdvanceReceived($centre_code, $_month,  $row_student["id"]);
            $discount_offered_for_opening=calcDiscountOffered($centre_code, $_month-1,  $row_student["id"]);
            $credit_note_for_opening=calcCreditNote($centre_code, $_month-1,  $row_student["id"]);
            $last_month_received_for_opening=calcLastMonthReceived($centre_code, $_month-1,  $row_student["id"]);
            
            $last_month_received=calcLastMonthReceived($centre_code, $_month,  $row_student["id"]);
            if($_month>1 && $programme_start_month<$_month){
               $_advance_opening=calcAdvancePayment($centre_code, $_month,  $row_student["id"]);
               //$advance_opening= $last_month_received+$advance_opening;
               $advance_opening=($_advance_opening > 0) ? $_advance_opening : 00;
               $advance_opening= $last_month_received+$advance_opening+$advance_received;

               $_opening_debt=calcOpeningDebt($centre_code, $_month,  $row_student["id"]);
            
            $AdvanceReceivedInThisMonth_for_opening = calcAdvanceReceivedInThisMonth($centre_code, $_month-1,  $row_student["id"]);
            $_opening_debt = $_opening_debt+$discount_offered_for_opening+$credit_note_for_opening+$last_month_received_for_opening+AdvanceReceivedInThisMonth_for_opening;
            $opening_debt=($_opening_debt > 0) ? $_opening_debt : 00;

            }else{
               $advance_opening=0;
               $opening_debt=0;
            }
            
            $current_mth_fees=calcCurrentMonthCourseFees($centre_code, $_month,  $row_student["id"]);
            $current_received=calcCurrentReceived($centre_code, $_month,  $row_student["id"]);
            $AdvanceReceivedInThisMonth = calcAdvanceReceivedInThisMonth($centre_code, $_month,  $row_student["id"]);
            
            //$request_Month=substr($selected_month, 4, 2);
            //$request_Year=substr($selected_month, 0, -2);
            $discount_offered=calcDiscountOffered($centre_code, $_month,  $row_student["id"]);
            $credit_note=calcCreditNote($centre_code, $_month,  $row_student["id"]);

            //$current_received = $current_received + $AdvanceReceivedInThisMonth-$discount_offered;
            //$current_received = $current_received + $AdvanceReceivedInThisMonth;
  
            //$_current_outstanding=$current_mth_fees-($advance_opening + $current_received+$discount_offered+$credit_note) - $AdvanceReceivedInThisMonth;   
            $_current_outstanding=$current_mth_fees-( $current_received+$discount_offered+$credit_note);
            //+ $AdvanceReceivedInThisMonth;   
            //$_current_outstanding=$current_mth_fees-( $current_received+$discount_offered+$credit_note) + $AdvanceReceivedInThisMonth;       
            $current_outstanding=($_current_outstanding > 0) ? $_current_outstanding : 00;

            $_closing_advance= $advance_opening +($current_received+$discount_offered+$credit_note) - ($opening_debt+$current_mth_fees);
            $closing_advance=($_closing_advance > 0) ? $_closing_advance : 00;
            $closing_advance= $advance_received + $closing_advance;

            $_closing_debt=($opening_debt+$current_mth_fees) - ($advance_opening+($current_received+$discount_offered+$credit_note)) + $last_month_received + $AdvanceReceivedInThisMonth;
            $closing_debt=($_closing_debt > 0) ? $_closing_debt : 00;

            $_advance_adjustment=$advance_opening - $closing_advance;
            $advance_adjustment=($_advance_adjustment > 0) ? $_advance_adjustment : 00;

            $_debt_adjustment=$opening_debt - $closing_debt;
            $debt_adjustment=($_debt_adjustment > 0) ? $_debt_adjustment : 00;

            //if ($advance_opening > 0 || $opening_debt > 0 || $current_mth_fees > 0 || $current_received > 0 || $discount_offered > 0 || $credit_note > 0) {
               $count++;
               $ct_advance_opening+=$advance_opening;
               $ct_opening_debt+=$opening_debt;
               $ct_current_mth_fees+=$current_mth_fees;
               $ct_current_received+=$current_received;
               $ct_current_outstanding+=$current_outstanding;
               $ct_closing_advance+=$closing_advance;
               $ct_closing_debt+=$closing_debt;
               $ct_advance_adjustment+=$advance_adjustment;
               $ct_debt_adjustment+=$debt_adjustment;
               $ct_discount_offered+=$discount_offered;
               $ct_credit_note+=$credit_note;

               $cet_advance_opening+=$advance_opening;
               $cet_opening_debt+=$opening_debt;
               $cet_current_mth_fees+=$current_mth_fees;
               $cet_current_received+=$current_received;
               $cet_current_outstanding+=$current_outstanding;
               $cet_closing_advance+=$closing_advance;
               $cet_closing_debt+=$closing_debt;
               $cet_advance_adjustment+=$advance_adjustment;
               $cet_debt_adjustment+=$debt_adjustment;
               $cet_discount_offered+=$discount_offered;
               $cet_credit_note+=$credit_note;

               $gt_advance_opening+=$advance_opening;
               $gt_opening_debt+=$opening_debt;
               $gt_current_mth_fees+=$current_mth_fees;
               $gt_current_received+=$current_received;
               $gt_current_outstanding+=$current_outstanding;
               $gt_closing_advance+=$closing_advance;
               $gt_closing_debt+=$closing_debt;
               $gt_advance_adjustment+=$advance_adjustment;
               $gt_debt_adjustment+=$debt_adjustment;
               $gt_discount_offered+=$discount_offered;
               $gt_credit_note+=$credit_note;

              
               echo "<tr>";
               echo "  <td>$count</td>";
               echo "  <td>".$row_student["student_code"]."</td>";
               echo "  <td>".$row_student["name"]."</td>";
               echo "  <td class='uk-text-right'>".number_format(abs($advance_opening), 2)."</td>";
               echo "  <td class='uk-text-right'>".number_format(abs($opening_debt), 2)."</td>";
               echo "  <td class='uk-text-right'>".number_format(abs($current_mth_fees), 2)."</td>";
               echo "  <td class='uk-text-right'>".number_format(abs($current_received), 2)."</td>";
               echo "  <td class='uk-text-right'>".number_format(abs($current_outstanding), 2)."</td>";
               echo "  <td class='uk-text-right'>".number_format(abs($closing_advance), 2)."</td>";
               echo "  <td class='uk-text-right'>".number_format(abs($closing_debt), 2)."</td>";
               echo "  <td class='uk-text-right'>".number_format(abs($advance_adjustment), 2)."</td>";
               echo "  <td class='uk-text-right'>".number_format(abs($debt_adjustment), 2)."</td>";
               echo "  <td class='uk-text-right'>".number_format(abs($discount_offered), 2)."</td>";
               echo "  <td class='uk-text-right'>".number_format(abs($credit_note), 2)."</td>";
               echo "</tr>";
            //}
         }
         
         $ct_advance_opening=0;
         $ct_opening_debt=0;
         $ct_current_mth_fees=0;
         $ct_current_received=0;
         $ct_current_outstanding=0;
         $ct_closing_advance=0;
         $ct_closing_debt=0;
         $ct_advance_adjustment=0;
         $ct_debt_adjustment=0;
         $ct_discount_offered=0;
         $ct_credit_note=0;
      
   }
   echo "<tr class='uk-text-bold' style='font-size: 12px;'>";
   echo "  <td colspan='3'>Grand Total: </td>";
   echo "  <td class='uk-text-right'>".number_format($gt_advance_opening, 2)."</td>";
   echo "  <td class='uk-text-right'>".number_format(abs($gt_opening_debt), 2)."</td>";
   echo "  <td class='uk-text-right'>".number_format($gt_current_mth_fees, 2)."</td>";
   echo "  <td class='uk-text-right'>".number_format($gt_current_received, 2)."</td>";
   echo "  <td class='uk-text-right'>".number_format((abs($gt_current_outstanding)), 2)."</td>";
   echo "  <td class='uk-text-right'>".number_format((abs($gt_closing_advance)), 2)."</td>";
   echo "  <td class='uk-text-right'>".number_format((abs($gt_closing_debt)), 2)."</td>";
   echo "  <td class='uk-text-right'>".number_format((abs($gt_advance_adjustment)), 2)."</td>";
   echo "  <td class='uk-text-right'>".number_format((abs($gt_debt_adjustment)), 2)."</td>";
   echo "  <td class='uk-text-right'>".number_format($gt_discount_offered, 2)."</td>";
   echo "  <td class='uk-text-right'>".number_format($gt_credit_note, 2)."</td>";
   echo "</tr>";
?>
<?php
// } else {
//    echo "Please enter a centre and a month";
// }
?>
</table>

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