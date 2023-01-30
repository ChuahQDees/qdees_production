<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");
foreach ($_POST as $key => $value) {
   $$key = mysqli_real_escape_string($connection, $value); //$centre_code; $month
}
if($month != '13') {
   $month_end_date = date("Y-m-t", strtotime($year.'-'.$month));
}
$monthList = getMonthList($year_start_date, $year_end_date);

if (isset($method) && $method == "print") {
   include_once("../uikit1.php");
}

$center_name = getCentreName($centre_code);

function echoReportHeader($month, $year)
{
   global $centre_code, $center_name;
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
   global $center_name;

   echo "<div class='nice-form'>";
   echo "<div class='uk-grid'>";
   echo "<div class='uk-width-medium-5-10'>";
   echo "<table class='uk-table'>";
   echo "<tr>";
   echo "<td class='uk-text-bold'>Centre Name</td>";
   echo "<td>" . $center_name . "</td>";
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
}
function echoTableHeader()
{
   echo "<table class='uk-table'>";
   echo "<tr class='uk-text-bold'>";
   echo "  <td style='width: 5%;'>No.</td>";
   echo "  <td style='width: 25%;' >Student </td>";
   echo "  <td style='width: 10%;'>Class Name</td>";
   echo "  <td style='width: 25%;'>Fee Description</td>";
   echo "  <td style='width: 8%;' class='uk-text-right'>Amount</td>";
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

function echoRowWithClassInfo($no, $subject, $student_name, $fee_description, $amount)
{
   echo "<tr>";
   echo "  <td style='width: 5%;'>$no</td>";
   echo "  <td style='width: 25%;'>$student_name</td>";
   echo "  <td style='width: 10%;'>$subject</td>";
   echo "  <td style='width: 25%;'>$fee_description</td>";
   echo "  <td class='uk-text-right' style='width: 8%;'>" . number_format($amount, 2) . "</td>";
   echo "</tr>";
}

function echoStudentTotal($total)
{
   echo "<tr>";
   echo "  <td colspan='4' class='uk-text-left uk-text-bold'>Total Outstanding</td>";
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

function getStrTerm($term) {
	 switch ($term) {
      case 1 : return "Term 1"; break;
      case 2 : return "Term 2"; break;
      case 3 : return "Term 3"; break;
      case 4 : return "Term 4"; break;
      case 5 : return "Term 5"; break;
   }
}

$halfDate = getHalfYearDate($_SESSION['Year']);

function getCurrentHalfYearly($date) {

   global $halfDate;

	if($date < $halfDate){
		return 1;
	}
	else if($date >= $halfDate){
		return 2;
	}
}

function getStrHalfYearly($HalfYearly) {
	 switch ($HalfYearly) {
      case 1 : return "First Half"; break;
      case 2 : return "Second Half"; break;
      
   }
}

foreach ($monthList as $dt) {
   $m = $dt->format("MY");
   $$m = 0;
} 

$Total = 0;

$sql="SELECT ps.id, ps.student_id, fl.afternoon_programme, s.name as student_name, s.student_code, f.school_adjust, f.subject, f.school_collection, f.multimedia_collection, f.facility_collection, f.basic_collection, f.multimedia_adjust, f.facility_adjust, f.extend_year, f.basic_adjust, fl.fee_id, month(fl.programme_date) programme_start_month, fl.programme_date, fl.programme_date_end, fl.foundation_iq_math, fl.foundation_int_mandarin, fl.foundation_int_art, fl.foundation_mandarin, fl.foundation_int_english, fl.pendidikan_islam from student s, programme_selection ps, student_fee_list fl, fee_structure f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.centre_code='$centre_code' and s.`student_status`='A' ";
if ($month != '13') {
   $sql .= " and fl.programme_date >= '".$year_start_date."' and fl.programme_date <='".$month_end_date."'";
} else {
   $sql .= " and fl.programme_date >= '".$year_start_date."' and fl.programme_date <='".$year_end_date."'";
}

if ($student) {
   $sql .= " and (s.name like '%$student%' or s.student_code like '%$student%')";
}
if ($student) {
   $sql .= " and (s.name like '%$student%' or s.student_code like '%$student%')";
}
$sql .= " group by ps.student_id";

$c_result = mysqli_query($connection, $sql);
$branch_total = 0;
echoReportHeader($month, $year);

$term = '';
if ($month == "13") {
   
   $term_data = mysqli_query($connection,"SELECT `term_num` FROM `schedule_term` WHERE `year` = '".$_SESSION['Year']."' AND `deleted` = '0' AND `centre_code` = '".$centre_code."' ORDER BY `term_num` ASC");

   while($term_row = mysqli_fetch_array($term_data))
   {
      $term .= $term_row['term_num'].',';
   }

} else {
   $term = getTermFromDate(date('Y-m-d',strtotime($year.'-'.$month)));
}

echoReportHeader1($centre_code, $_SESSION['UserName'], $_SESSION['Year'], $term);

echoTableHeader();

$count = 0;

$where = '';

$where .= ($centre_code != '') ? " AND `centre_code` = '".$centre_code."'" : "";

$where .= " AND `status` != 'Pending'";

while ($c_row = mysqli_fetch_assoc($c_result)) {

   $sql="SELECT ps.id as allocation_id, ps.student_id,  f.subject, f.fee_name, f.fees, f.payment_type, f.extend_year, fl.fee_id, fl.programme_date as start_date, fl.programme_date_end as end_date
   from student s, programme_selection ps, student_fee_list fl, 
   (
      select id, subject,'School Fees' as fee_name, school_adjust as fees, school_collection as payment_type, extend_year from fee_structure WHERE 1=1 ".$where."
      UNION ALL
      select id, subject,'Multimedia Fees' as fee_name, multimedia_adjust as fees, multimedia_collection as payment_type, extend_year from fee_structure WHERE 1=1 ".$where."
      UNION ALL
      select id, subject,'Facility Fees' as fee_name, facility_adjust as fees, facility_collection as payment_type, extend_year from fee_structure WHERE 1=1 ".$where."
      UNION ALL
      select id, subject,'Integrated Module' as fee_name, integrated_adjust as fees, integrated_collection as payment_type, extend_year from fee_structure WHERE 1=1 ".$where."
      UNION ALL
      select id, subject,'Link & Think' as fee_name, link_adjust as fees, link_collection as payment_type, extend_year from fee_structure WHERE 1=1 ".$where."
      UNION ALL
      select id, subject,'Mobile app' as fee_name, mobile_adjust as fees, mobile_collection as payment_type, extend_year from fee_structure WHERE 1=1 ".$where."
      UNION ALL
      select id, subject,'Registration' as fee_name, registration_adjust as fees, 'Annually' as payment_type, extend_year from fee_structure WHERE 1=1 ".$where."
      UNION ALL
      select id, subject,'Insurance' as fee_name, insurance_adjust as fees, 'Annually' as payment_type, extend_year from fee_structure WHERE 1=1 ".$where."
      UNION ALL
      select id, subject,'Q-dees Level Kit' as fee_name, q_dees_adjust as fees, 'Annually' as payment_type, extend_year from fee_structure WHERE 1=1 ".$where."
      UNION ALL
      select id, subject,'Uniform (2 sets)' as fee_name, uniform_adjust as fees, 'Annually' as payment_type, extend_year from fee_structure WHERE 1=1 ".$where."
      UNION ALL
      select id, subject,'Gymwear (1 set)' as fee_name, gymwear_adjust as fees, 'Annually' as payment_type, extend_year from fee_structure WHERE 1=1 ".$where."
      UNION ALL
      select id, subject,'Q-dees Bag' as fee_name, q_bag_adjust as fees, 'Annually' as payment_type, extend_year from fee_structure  WHERE 1=1 ".$where."";
      if ($c_row["foundation_mandarin"]){
         $sql .= " 
         UNION ALL
      select id, subject,'Mandarin Modules' as fee_name, mandarin_m_adjust as fees, mandarin_m_collection as payment_type, extend_year from fee_structure  WHERE 1=1 ".$where."";
      }
      if ($c_row["foundation_iq_math"]){
         $sql .= " 
         UNION ALL
         select id, subject,'IQ Math' as fee_name, iq_math_adjust as fees, iq_math_collection as payment_type, extend_year from fee_structure  WHERE 1=1 ".$where."";
      }
      if ($c_row["foundation_int_mandarin"]){
         $sql .= " 
         UNION ALL
         select id, subject,'Enhanced Foundation Mandarin' as fee_name, mandarin_adjust as fees, mandarin_collection as payment_type, extend_year from fee_structure  WHERE 1=1 ".$where."";
      }
      if ($c_row["foundation_int_art"]){
         $sql .= " 
         UNION ALL
         select id, subject,'International Art' as fee_name, international_adjust as fees, international_collection as payment_type, extend_year from fee_structure  WHERE 1=1 ".$where."";
       }
      if ($c_row["foundation_int_english"]){
         $sql .= " UNION ALL
         select id, subject,'International English' as fee_name, enhanced_adjust as fees, enhanced_collection as payment_type, extend_year from fee_structure  WHERE 1=1 ".$where."";
      }
      if ($c_row["pendidikan_islam"]){
         $sql .= " UNION ALL
         select id, subject, 'Pendidikan Islam' as fee_name, islam_adjust as fees, 'Annually' as payment_type, extend_year from fee_structure  WHERE 1=1 ".$where."";
      }
     if ($c_row["afternoon_programme"]){
         $sql .= " UNION ALL
         select id, subject,'Afternoon Programme' as fee_name, basic_adjust as fees, basic_collection as payment_type, extend_year from fee_structure  WHERE 1=1 ".$where."";
      }
      
      $sql .= " ) f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.centre_code='$centre_code' and ps.student_id='" . $c_row["student_id"] . "' ";
      
      if ($month != '13') {
         $sql .= " and fl.programme_date >= '".$year_start_date."' and fl.programme_date <='".$month_end_date."'";
      } else {
         $sql .= " and fl.programme_date >= '".$year_start_date."' and fl.programme_date <='".$year_end_date."'";
      }

      $sql11="select  fl.programme_date, fl.programme_date_end from student s, programme_selection ps, student_fee_list fl where s.id=ps.student_id and ps.id = fl.programme_selection_id and s.`student_status`='A' and fl.programme_date >= '".$year_start_date."' and s.id = '" . $c_row["student_id"] . "' and fl.programme_date <= '".$month_end_date."'";

      $result11=mysqli_query($connection, $sql11);
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
            
      $sql .= " UNION ALL 
      select '0' as allocation_id, '" . $c_row["student_id"] . "' as student_id, 'Add-On Products' as subject, `product_name` as fee_name, unit_price as fees, `monthly` as payment_type, '".$_SESSION['Year']."' as extend_year, '' as fee_id, '$programme_date' as start_date, '$programme_date_end' as end_date from addon_product where centre_code='$centre_code' and status='Approved'";
         
      $sql .= " order by fee_name ";

   $result = mysqli_query($connection, $sql);
   $num_row = mysqli_num_rows($result);
   $count++;
   if ($num_row > 0) {
      
      echoTable();
      $class_total = 0;
      $programme_date = $c_row["programme_start_month"];
      $check_item_array=array();
      
      while ($row = mysqli_fetch_assoc($result)) {
          if($row["fees"]!=0){
            
            if ($row["payment_type"] == 'Monthly') {
               $start_month = explode('-', ($row["start_date"]))[1];
               $end_month = explode('-', ($row["end_date"]))[1];
             
               if($row["start_date"] < $year_start_date) {
                  $start = $year_start_date;
               } else {
                  $start = $row["start_date"];
               }
               if($row["end_date"] > $year_end_date) {
                  $end = $year_end_date;
               } else {
                  $end = $row["end_date"];
               }

               $months = getMonthList($start,$end);

               foreach ($months as $dt) {
                  $var_name = $dt->format("MY");

                  $full_item_name = $row["fee_name"].' for ' . $dt->format("M Y");
                  if (!in_array($full_item_name, $check_item_array)) {
                     array_push($check_item_array, $full_item_name);

                     if (($month < 13 && $dt->format("Ym") <= date('Ym',strtotime($year.'-'.$month))) || ($month == 13 && $i+1 <= date('Ym',strtotime($row["end_date"])))) {
                        $allocation_id= $row["allocation_id"];
                        $fee_name= $row["fee_name"];
                        $product_code= $row["fee_name"];
                        if($row["subject"] =="Add-On Products"){
                           $product_code = getProductNameByCode($fee_name, $centre_code);
                        }
                        
                        $student_id= $row["student_id"];
                        $collection_month= $dt->format("m");

                        $sql22 = "SELECT sum(amount)+sum(discount) collection FROM `collection` c  where c.student_id = '$student_id' and  c.year = '".$_SESSION['Year']."' and c.void = 0 and c.`collection_month` = $collection_month and c.product_code = '$product_code' and collection_pattern ='Monthly'";
                       
                        $result22=mysqli_query($connection, $sql22);
                      
                        $row_collection=mysqli_fetch_assoc($result22);
                        
                        $collection =(($row_collection["collection"] == '') ? 0 : $row_collection["collection"]);
                        $balance = $row["fees"] - $collection;
                        if(($balance>0 && $row["subject"] !="Add-On Products") || ($row["subject"] =="Add-On Products" && $collection>0 && $balance>0)){ 
                      
                           echoRowWithClassInfo( ($class_total == 0) ? $count : "" , $row["subject"], ($class_total == 0) ? $c_row["student_name"] . '<br>' . $c_row["student_code"] : "", $row["fee_name"].' for ' . $dt->format("M Y"), $balance);
                              $class_total += $balance;
                              $Total += $balance;

                              $$var_name += $balance;
                        }
                     }
                  }
               }
            }
            else if ($row["payment_type"] == 'Termly') {
           
               $start_month = date("MY",strtotime($row["start_date"]));
                
               $term_start = getTermFromDate($row["start_date"]);
               $term_end = getTermFromDate($row["end_date"]);
               $i = (int)$term_start-1;
               $term_end_int = (int)$term_end;
               
               for ($i; $i < $term_end_int; $i++) { 
                  $full_item_name = $row["fee_name"].' for ' . getStrTerm($i+1);
                  if (!in_array($full_item_name, $check_item_array)) {
                     array_push($check_item_array, $full_item_name);

                     if (($month < 13 && $i+1 <= getTermFromDate(date('Y-m-d',strtotime($year.'-'.$month)))) || ($month == 13 && $i+1 <= $term_end)) {
                        $allocation_id= $row["allocation_id"];
                        $fee_name= $row["fee_name"];
                        $product_code= $row["fee_name"];
                        if($row["subject"] =="Add-On Products"){
                           $product_code = getProductNameByCode($fee_name, $centre_code);
                        }
                        $student_id= $row["student_id"];
                        $collection_month= $i+1;
              
                        $sql22 = "SELECT sum(amount)+sum(discount) collection FROM `collection` c  where c.student_id = '$student_id' and  c.year = '".$_SESSION['Year']."' and c.void = 0 and c.`collection_month` = $collection_month and c.product_code = '$product_code' and collection_pattern ='Termly' ";
                        
                        $result22=mysqli_query($connection, $sql22);
              
                        $row_collection=mysqli_fetch_assoc($result22);
              
                        $collection =(($row_collection["collection"] == '') ? 0 : $row_collection["collection"]);
                        
                        $balance = $row["fees"] - $collection;
                     
                        if(($balance>0 && $row["subject"] !="Add-On Products") || ($row["subject"] =="Add-On Products" && $collection>0 && $balance>0)){    
                           
                           echoRowWithClassInfo( ($class_total == 0) ? $count : "" , $row["subject"], ($class_total == 0) ? $c_row["student_name"] . '<br>' . $c_row["student_code"] : "", $row["fee_name"].' for ' . getStrTerm($i+1), $balance);
                           $class_total += $balance;
                           $Total += $balance;

                           $$start_month += $balance;
                        }
                     }
                  }
               }
            } else if ($row["payment_type"] == 'Half Year') {
               
               $start_month = date("MY",strtotime($row["start_date"]));

               $i = getCurrentHalfYearly($row["start_date"]);
               $HearfYearly = getCurrentHalfYearly($row["end_date"]);

               for ($i; $i < $HearfYearly; $i++) {

                  if (($month < 13 && $i+1 <= getCurrentHalfYearly(date('Y-m-d',strtotime($year.'-'.$month)))) || ($month == 13 && $i+1 <= $HearfYearly)) {
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

                        $sql22 = "SELECT sum(amount)+sum(discount) collection FROM `collection` c  where c.student_id = '$student_id' and  c.year = '".$_SESSION['Year']."' and c.void = 0 and c.`collection_month` = $collection_month and c.product_code = '$product_code' and collection_pattern ='Half Year'";

                        $result22=mysqli_query($connection, $sql22);
                        $IsRow22=mysqli_num_rows($result22);
                        $row_collection=mysqli_fetch_assoc($result22);
                        $collection =(($row_collection["collection"] == '') ? 0 : $row_collection["collection"]);
                        $balance = $row["fees"] - $collection;
                        if(($balance>0 && $row["subject"] !="Add-On Products") || ($row["subject"] =="Add-On Products" && $collection>0 && $balance>0)){ 

                        echoRowWithClassInfo( ($class_total == 0) ? $count : "" , $row["subject"], ($class_total == 0) ? $c_row["student_name"] . '<br>' . $c_row["student_code"] : "", $row["fee_name"].' for ' . getStrHalfYearly($i+1), $balance);
                        $class_total += $balance;
                        $Total += $balance;

                        $$start_month += $balance;
                      }
                     }
                  }
               }
         } else if ($row["payment_type"] == 'Annually') {

            $start_month = date("MY",strtotime($row["start_date"]));
            $strYear = $_SESSION['Year'];

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

               $sql22 = "SELECT sum(amount)+sum(discount) collection FROM `collection` c  where c.student_id = '$student_id' and  c.year = '".$_SESSION['Year']."' and c.void = 0 and c.`collection_month` = $collection_month and c.product_code = '$product_code' and collection_pattern ='Annually'";
               
               $result22=mysqli_query($connection, $sql22);
               $row_collection=mysqli_fetch_assoc($result22);
               $collection =(($row_collection["collection"] == '') ? 0 : $row_collection["collection"]);
               $balance = $row["fees"] - $collection;
               if(($balance>0 && $row["subject"] !="Add-On Products") || ($row["subject"] =="Add-On Products" && $collection>0 && $balance>0)){ 
               
                  echoRowWithClassInfo(($class_total == 0) ? $count : "", $row["subject"], ($class_total == 0) ? $c_row["student_name"] . '<br>' . $c_row["student_code"] : "", $row["fee_name"].' for ' . $strYear, $balance);
                  $class_total += $balance;
                  $Total += $balance;
               
                     $$start_month += $balance;
                  }
               }
            }
         }
      }
      $branch_total += $class_total;
      echoStudentTotal($class_total);
   } else {
      //echoNotFound();
   }
}

$current_month_year = date('MY',strtotime($year.'-'.$month));
echoGrandTotal($$current_month_year);

?>
<table class="uk-table sum-table">
   <thead>
      <tr>
         <th>Month/Year</th>
         <?php 
            foreach ($monthList as $dt) {
               echo '<th>'.$dt->format("M Y").'</th>';
            }        
         ?>
         <th>Total</th>
      </tr>
   </thead>
   <tbody>
      <tr>  
         <td style="width: 5%;"><?php echo $_SESSION['Year']; ?></td>  
         <?php 
            foreach ($monthList as $dt) {
               $m = $dt->format("MY");
               echo '<td class="uk-text-right">'.$$m.'</td>';
            }        
         ?>
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
