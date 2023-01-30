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
      if ($centre_code=="ALL" || $centre_code=="")  {
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
function getStudentId($student) {
   global $connection;
   $student_sql="SELECT id from student where name like '$student%' or student_code like '%$student%'";
   $result=mysqli_query($connection, $student_sql);
   $row=mysqli_fetch_assoc($result);

   return $row['id'];
}

function getCourseFee($course_id) {
   global $connection;

   $sql="SELECT fees from course where id='$course_id'";

   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return number_format($row["fees"], 2);
}

function getMonthTerm($selected_month, &$month, &$term) {
   $themonth=substr($selected_month+$num, 4, 2);
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

function calcCurrentReceived($centre_code, $month,  $course_id, $student_id, $payment_type) {
   global $connection;
   $request_Month=intval(substr($month, 4, 2));
   $request_Year=substr($month, 0, -2);
   
   if ($payment_type == 'Monthly') {
      $sql="SELECT sum(c.amount) as amount from collection c, allocation a LEFT JOIN `group` g on g.id=a.group_id where c.year='$request_Year' and c.collection_month='$request_Month' and void=0 and collection_type='tuition' and c.allocation_id=a.id and a.course_id='$course_id' and a.deleted=0 and $request_Month between month(g.start_date) and month(g.end_date)";
   } else {
      $sql="SELECT sum(c.amount) as amount from collection c, allocation a LEFT JOIN `group` g on g.id=a.group_id where c.year='$request_Year' and c.collection_month='$request_Month' and void=0 and collection_type='tuition' and c.allocation_id=a.id and a.course_id='$course_id' and a.deleted=0 and $request_Month = month(g.start_date)";
   }

   if ($centre_code!="ALL" && $centre_code!="") {
      $sql .= " and c.centre_code='$centre_code'";
   }
   if($student_id!=''){
      $sql .= " and c.student_id='$student_id'";
   }
   //echo $sql;
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return number_format($row["amount"], 2);
}

function calcDiscountOffered($centre_code, $month, $year,  $course_id, $student_id) {
   global $connection;
   //$sql="SELECT sum(c.discount) as discount from collection c, allocation a where concat(c.year, month(c.collection_date_time))=$month and void=0 and collection_type='tuition' and centre_code='$centre_code' and c.allocation_id=a.id and a.course_id='$course' and a.course_id='$student' and a.deleted=0";

   $sql="SELECT sum(c.discount) as discount from collection c, allocation a where c.year='$year' and month(c.collection_date_time)='$month' and void=0 and collection_type='tuition' and c.allocation_id=a.id and a.course_id='$course_id' and a.deleted=0";

   if ($centre_code!="ALL" && $centre_code!="") {
      $sql .= " and c.centre_code='$centre_code'";
   }

   if($student_id!=''){
      $sql .= " and c.student_id='$student_id'";
   }

   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return number_format($row["discount"], 2);
}

function calcCreditNote($centre_code, $month, $year,  $course_id, $student_id) {
   global $connection;
   //$sql="SELECT sum(c.amount) as amount from collection c, allocation a where concat(c.year, month(c.collection_month))=$month and void=1 and cn_no<>'' and collection_type='tuition' and centre_code='$centre_code' and c.allocation_id=a.id and a.course_id='$course' and a.course_id='$student' and a.deleted=0"; 
   $sql="SELECT sum(c.amount) as amount from collection c, allocation a where c.year='$year' and month(c.collection_date_time)='$month' and void=1 and cn_no<>'' and collection_type='tuition' and c.allocation_id=a.id and a.course_id='$course_id' and a.deleted=0";
   //echo $sql;
   if ($centre_code!="ALL" && $centre_code!="") {
      $sql .= " and c.centre_code='$centre_code'";
   }
   if($student_id!=''){
      $sql .= " and c.student_id='$student_id'";
   }
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return number_format($row["amount"], 2);
} 

function calcAdvancePayment($centre_code, $month, $student, $student_id, $payment_type) {
   $already_paid=calcAlreadyPayFeeB4($centre_code, $month,  $student, $student_id);
   $should_pay=calcShouldPayFeeB4($centre_code, $month,  $student, $student_id, $payment_type);

   return number_format($already_paid - $should_pay, 2);
}

// function calcAdvancePayment($centre_code, $month,  $student) {
//    global $connection;
//    $request_Month=intval(substr($month, 4, 2));
//    $request_Year=substr($month, 0, -2);

//    $sql="SELECT sum(c.amount) - sum(c.fees) as amount from collection c, allocation a LEFT JOIN `group` g on g.id=a.group_id where c.collection_month < '$request_Month' and c.year='$request_Year' and void=0 and collection_type='tuition' and c.centre_code='$centre_code' and c.allocation_id=a.id and a.course_id='$course' and a.course_id='$student' and a.deleted=0";

//    $result=mysqli_query($connection, $sql);
//    $row=mysqli_fetch_assoc($result);

//    return number_format($row["amount"], 2);
// }

function getAllocatedDateTime($centre_code, $month,  $student) {
   global $connection;

   getMonthTerm($month, $period, $term_period);

   $sql="SELECT a.id, a.allocated_date_time from allocation a, student s where a.course_id=s.id and course_id='$student' and a.deleted=0";

   if ($centre_code!="ALL" && $centre_code!="") {
      $sql .= " and c.centre_code='$centre_code'";
   }

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

function calcAlreadyPayFeeB4($centre_code, $month,  $course_id, $student_id) {
   global $connection;

   $sql="SELECT sum(c.amount) as amount, c.* from collection c, allocation a LEFT JOIN `group` g on g.id=a.group_id where concat(c.year, RIGHT(100+c.collection_month,2))<$month and void=0 and c.collection_type='tuition'
         and c.allocation_id=a.id and a.course_id='$course_id' and a.deleted=0";
   if ($centre_code!="ALL" && $centre_code!="") {
      $sql .= " and c.centre_code='$centre_code'";
   }
   if($student_id!=''){
      $sql .= " and c.student_id='$student_id'";
   }
   //echo $sql;
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return number_format($row["amount"], 2);
}

function calcShouldPayFeeB4($centre_code, $month,  $course_id, $student_id, $payment_type) {
   global $connection;
   $totalFees=0;

   $sql="SELECT a.*, g.start_date, g.end_date from allocation a LEFT JOIN `group` g on g.id=a.group_id where a.course_id='$course_id' and a.deleted=0";
   if ($centre_code!="ALL" && $centre_code!="") {
      $sql .= " and centre_code='$centre_code'";
   }
   if($student_id!=''){
      $sql .= " and a.student_id='$student_id'";
   }

   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   $monthInv = month_involved($row['group_id'], $payment_type);

   for ($i=0; $i < $monthInv[0]; $i++) { 
      $outputDate = $row['year'].''.str_pad($monthInv[1][$i], 2, "0", STR_PAD_LEFT);
      if ($outputDate < $month) {
         $totalFees = $totalFees + $row['fees'];
      }
   }

   return number_format($totalFees, 2);
}

function calcCurrentMonthCourseFees($centre_code, $month, $course_id, $student_id, $payment_type) {
   global $connection;
   $request_Month=substr($month, 4, 2);
   $request_Year=substr($month, 0, -2);
   if ($payment_type == 'Monthly') {
      $sql="SELECT sum(a.fees) as fees from allocation a LEFT JOIN `group` g on g.id=a.group_id where a.course_id='$course_id' and a.deleted=0 and g.year='$request_Year' and $request_Month between month(g.start_date) and month(g.end_date)";
   } else {
      $sql="SELECT sum(a.fees) as fees from allocation a LEFT JOIN `group` g on g.id=a.group_id where  a.course_id='$course_id' and a.deleted=0 and g.year='$request_Year' and $request_Month = month(g.start_date)";
   }
   if($student_id!=''){
      $sql .= " and a.student_id='$student_id'";
   }
   if ($centre_code!="ALL" && $centre_code!="") {
      $sql .= " and centre_code='$centre_code'";
   }
   
   //echo $sql; 
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return number_format($row["fees"], 2);
}

function calcOpeningDebt($centre_code, $month,  $student, $student_id, $payment_type) {
   $already_paid=calcAlreadyPayFeeB4($centre_code, $month,  $student, $student_id);
   $should_pay=calcShouldPayFeeB4($centre_code, $month,  $student, $student_id, $payment_type);

   return number_format($should_pay-$already_paid, 2);
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
            <td>
               <?php
                  if(!empty($selected_month)) { 
                     $str_length = strlen($selected_month);
                     echo str_split($selected_month,($str_length - 2))[0];
                  } else { 
                     echo $_SESSION['Year']; 
                  }
               ?>
            </td>
         </tr>
         <tr>
            <td class="uk-text-bold">School Term</td>
            <td>
            <?php
                      $month = date("m");
                     $year = $_SESSION['Year'];
                     if (isset($selected_month) && $selected_month != '') {

                        $year_length = strlen($selected_month);

                        $month = substr($selected_month, ($year_length - 2), 2);
                        $year = substr($selected_month, 0, -2);
                     }
                        //$sql = "SELECT * from codes where year=" . $year;
						$sql = "SELECT * from codes where module='SCHOOL_TERM'";
                    if($month!="13"){
                      $sql .= " and from_month<=$month and to_month>=$month";
                    }
                    $sql .= " order by category";
                      //Print_r($sql);
                        $centre_result = mysqli_query($connection, $sql);
                        $str = "";
                      while ($centre_row = mysqli_fetch_assoc($centre_result)) {
                        // echo $centre_row['category'] . "/" . $centre_row['year'] . "<br>";
                        $str .= $centre_row['category'] . ', ';
                      }
                      echo rtrim($str, ", ");
                     //}
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
      <th colspan="2">Class</th>
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
   //    $sql="SELECT distinct s.centre_code from allocation a, student s, course c where a.course_id=c.id and a.course_id=s.id and a.deleted=0 order by s.centre_code";
   // } else {
   //    $sql="SELECT distinct s.centre_code from allocation a, student s, course c where a.course_id=c.id and a.course_id=s.id
   //    and s.centre_code='$centre_code' and a.deleted=0";
   // }
   // $result=mysqli_query($connection, $sql);
   $count1=0;
   $count=0;

   //while ($row=mysqli_fetch_assoc($result)) {
      //if ($student) {
      //$sql="SELECT distinct c.course_name, c.id, s.centre_code from allocation a, student s, course c where a.course_id=c.id and a.course_id=s.id and a.deleted=0 and (s.student_code like '%".$student."%' or s.name like '".$student."%') ";
     // }else{
 //$sql="SELECT distinct c.course_name, c.id, s.centre_code from allocation a, student s, course c where a.course_id=c.id and a.course_id=s.id  and a.deleted=0 ";
 //$sql="SELECT * from centre c where 1=1 ";
 
      //}
     // if ($centre_code!="ALL") {
       //  $sql .= "and c.centre_code='$centre_code'";
     // }
      //$sql .= " order by c.company_name ";
      
      //$result_course=mysqli_query($connection, $sql);

      // while ($row_course=mysqli_fetch_assoc($result_course)) {
         //$centre_code = $row_course['centre_code'];
         if ($student){
         $student_id = getStudentId($student);
      }else{
         $student_id = "";
      }

         if ($student) {
            $sql="SELECT distinct c.id, c.subject, c.payment_type from allocation a, student s, course c where a.course_id=c.id and a.student_id=s.id and a.deleted=0 and (s.student_code like '%".$student."%' or s.name like '".$student."%') ";
         }else{
             $sql="SELECT distinct c.id, c.subject, c.payment_type from allocation a, student s, course c where a.course_id=c.id and a.student_id=s.id and a.deleted=0 ";
         }
         if ($centre_code!="ALL" && $centre_code!="") {
            $sql .= "and s.centre_code='$centre_code'";
         }
         $sql .= " group by c.subject ORDER BY c.subject ASC ";

         //echo $sql;

         $count1++;
         
         $result_student=mysqli_query($connection, $sql);
         while ($row_course=mysqli_fetch_assoc($result_student)) {
            //$student_code = $row_course["student_code"];
         $name = $row_course["subject"];

            
           

            //$advance_opening=calcAdvancePayment($centre_code, $selected_month,  $row_course["id"]);
            $_advance_opening=calcAdvancePayment($centre_code, $selected_month,  $row_course["id"], $student_id, $row_course["payment_type"]);
            $advance_opening=($_advance_opening > 0) ? $_advance_opening : 00;

            //$opening_debt=calcOpeningDebt($centre_code, $selected_month,  $row_course["id"], $row_course["payment_type"]);
            $_opening_debt=calcOpeningDebt($centre_code, $selected_month,  $row_course["id"], $student_id, $row_course["payment_type"]);
            $opening_debt=($_opening_debt > 0) ? $_opening_debt : 00;

            $current_mth_fees=calcCurrentMonthCourseFees($centre_code, $selected_month,  $row_course["id"], $student_id, $row_course["payment_type"]);
            $current_received=calcCurrentReceived($centre_code, $selected_month,  $row_course["id"], $student_id, $row_course["payment_type"]);
            $str_length = strlen($selected_month);
            $request_Month=substr($selected_month, ($str_length - 2), 2);
            $request_Year=substr($selected_month, 0, -2);
            $discount_offered=calcDiscountOffered($centre_code, $request_Month, $request_Year,  $row_course["id"], $student_id );
            $credit_note=calcCreditNote($centre_code, $request_Month, $request_Year,  $row_course["id"], $student_id);

            //$current_outstanding=number_format($current_mth_fees-($current_received+$discount_offered+$credit_note), 2);     
            $_current_outstanding=number_format($current_mth_fees-($advance_opening + $current_received+$discount_offered+$credit_note), 2);       
            $current_outstanding=($_current_outstanding > 0) ? $_current_outstanding : 00;

            //$closing_advance=number_format($advance_opening-($current_received+$discount_offered+$credit_note), 2);
            $_closing_advance=number_format($advance_opening+($current_received+$discount_offered+$credit_note) - ($opening_debt+$current_mth_fees), 2);
            $closing_advance=($_closing_advance > 0) ? $_closing_advance : 00;

            //$closing_debt=number_format($opening_debt+$current_outstanding-($current_received+$discount_offered+$credit_note), 2);
            $_closing_debt=number_format(($opening_debt+$current_mth_fees) - ($advance_opening+($current_received+$discount_offered+$credit_note)), 2);
            $closing_debt=($_closing_debt > 0) ? $_closing_debt : 00;

            //$advance_adjustment=number_format($closing_advance-($current_received+$discount_offered+$credit_note), 2);
            $_advance_adjustment=number_format($advance_opening - $closing_advance, 2);
            $advance_adjustment=($_advance_adjustment > 0) ? $_advance_adjustment : 00;

            //$debt_adjustment=number_format($closing_debt-$opening_debt, 2);
            $_debt_adjustment=number_format($opening_debt - $closing_debt, 2);
            $debt_adjustment=($_debt_adjustment > 0) ? $_debt_adjustment : 00;

            if ($advance_opening > 0 || $opening_debt > 0 || $current_mth_fees > 0 || $current_received > 0 || $discount_offered > 0 || $credit_note > 0) {
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
               //echo "  <td>".$row_course["student_code"]."</td>";
               echo "  <td colspan='2'>".$row_course["course_name"]."</td>";
               echo "  <td class='uk-text-right'>".$advance_opening."</td>";
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
            }
         }
         // //echo subtotal for course
         // $course_name=explode('-', $row_course["course_name"]);
         // echo "<tr class='uk-text-bold'>";
         // echo "  <td colspan='3'>Total By Subject: ".trim($course_name[0])."</td>";
         // //echo "  <td>$count1</td>";
         // //echo "  <td>".$student_code."</td>";
         // //echo "  <td>".$name."</td>";
         // echo "  <td class='uk-text-right'>".number_format($ct_advance_opening, 2)."</td>";
         // echo "  <td class='uk-text-right'>".number_format(abs($ct_opening_debt), 2)."</td>";
         // echo "  <td class='uk-text-right'>".number_format($ct_current_mth_fees, 2)."</td>";
         // echo "  <td class='uk-text-right'>".number_format($ct_current_received, 2)."</td>";
         // echo "  <td class='uk-text-right'>".number_format((abs($ct_current_outstanding)), 2)."</td>";
         // echo "  <td class='uk-text-right'>".number_format((abs($ct_closing_advance)), 2)."</td>";
         // echo "  <td class='uk-text-right'>".number_format((abs($ct_closing_debt)), 2)."</td>";
         // echo "  <td class='uk-text-right'>".number_format((abs($ct_advance_adjustment)), 2)."</td>";
         // echo "  <td class='uk-text-right'>".number_format((abs($ct_debt_adjustment)), 2)."</td>";
         // echo "  <td class='uk-text-right'>".number_format($ct_discount_offered, 2)."</td>";
         // echo "  <td class='uk-text-right'>".number_format($ct_credit_note, 2)."</td>";
         // echo "</tr>";

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
      // }
      // //echo subtotal for centre
      // echo "<tr class='uk-text-bold'>";
      // echo "  <td colspan='3'>Total By Centre: ".$centre_code."</td>";
      // echo "  <td class='uk-text-right'>".number_format($cet_advance_opening, 2)."</td>";
      // echo "  <td class='uk-text-right'>".number_format(abs($cet_opening_debt), 2)."</td>";
      // echo "  <td class='uk-text-right'>".number_format($cet_current_mth_fees, 2)."</td>";
      // echo "  <td class='uk-text-right'>".number_format($cet_current_received, 2)."</td>";
      // echo "  <td class='uk-text-right'>".number_format((abs($cet_current_outstanding)), 2)."</td>";
      // echo "  <td class='uk-text-right'>".number_format((abs($cet_closing_advance)), 2)."</td>";
      // echo "  <td class='uk-text-right'>".number_format((abs($cet_closing_debt)), 2)."</td>";
      // echo "  <td class='uk-text-right'>".number_format((abs($cet_advance_adjustment)), 2)."</td>";
      // echo "  <td class='uk-text-right'>".number_format((abs($cet_debt_adjustment)), 2)."</td>";
      // echo "  <td class='uk-text-right'>".number_format($cet_discount_offered, 2)."</td>";
      // echo "  <td class='uk-text-right'>".number_format($cet_credit_note, 2)."</td>";
      // echo "</tr>";

      // $cet_advance_opening=0;
      // $cet_opening_debt=0;
      // $cet_current_mth_fees=0;
      // $cet_current_received=0;
      // $cet_current_outstanding=0;
      // $cet_closing_advance=0;
      // $cet_closing_debt=0;
      // $cet_advance_adjustment=0;
      // $cet_debt_adjustment=0;
      // $cet_discount_offered=0;
      // $cet_credit_note=0;
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