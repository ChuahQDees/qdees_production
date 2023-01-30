<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");
include_once("../search_new.php");
$year = $_SESSION['Year'];
foreach ($_POST as $key => $value) {
   $$key = mysqli_real_escape_string($connection, $value); //$centre_code, $df, $dt, $student_id
}

//$centre_code = 'MYQWESTC1C10006';
if ($method == "print") {
   include_once("../uikit1.php");
}

$o_df = $df;
$o_dt = $dt;

$df = convertDate2ISO($df);
$dt = convertDate2ISO($dt);

if ($collection_type == "ALL") {
   $collection_type = "";
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
function getStrHalfYearly($HalfYearly) {
   switch ($HalfYearly) {
     case 1 : return "First Half"; break;
     case 2 : return "Second Half"; break;
     
  }
 
}
function getCollectionType($collection_type)
{
   switch ($collection_type) {
      case "tuition":
         return "Tuition Fee";
         break;
      case "placement":
         return "Placement Fee";
         break;
      case "registration":
         return "Registration Fee";
         break;
      case "deposit":
         return "Deposit";
         break;
      case "product":
         return "Product";
         break;
   }
}

function getStudentCodeFromAllocationID($allocation_id, &$student_name)
{
   global $connection;

   $sql = "SELECT * from allocation where id='$allocation_id'";
   $result = mysqli_query($connection, $sql);
   $row = mysqli_fetch_assoc($result);

   $student_id = $row["student_id"];

   $sql = "SELECT * from student where id='$student_id'";
   $result = mysqli_query($connection, $sql);
   $row = mysqli_fetch_assoc($result);

   $student_name = $row["name"];
   return $row["student_code"];
}

function getCentreName($centre_code)
{
   global $connection;

   $sql = "SELECT * from centre where centre_code='$centre_code'";
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

function getStudentName($student, $centre_code)
{
   global $connection;
   $student_sql = "SELECT name from student where centre_code='$centre_code' and name like '%$student%' or student_code like '%$student%'";
   $result = mysqli_query($connection, $student_sql);
   $row = mysqli_fetch_assoc($result);

   return $row['name'];
}

//if ($centre_code != "") {
if (true) {


   $base_sql = "SELECT c.*, s.name, pd.payment_method,  pd.transaction_date, pd.cheque_no, pd.ref_no from student s ,collection c LEFT JOIN payment_detail pd on pd.id=c.payment_detail_id
      where c.student_code=s.student_code and c.collection_date_time>='$df 00:00:00' and collection_date_time<'$dt 23:59:59' 
      and c.void=0 ";
//$base_sql .= "and year(start_date_at_centre) <= '$year' and extend_year >= '$year'";
   if ($centre_code != "ALL") {
      $base_sql .= " and c.centre_code='$centre_code'";
   }
   // and c.centre_code='$centre_code'

   if ($filter_by != "") {
      $base_sql .= " and c.collection_type='$filter_by'";
   }

   $payment_token = ConstructToken("pd.payment_method", "%$collection_type%", "like");
   // $collection_token = ConstructToken("c.collection_type", "%$collection_type%", "like");
   $student_token = ConstructTokenGroup("s.name", "%$student%", "like", "s.student_code", "%$student%", "like", "or");
   $order_token = ConstructOrderToken("`c`.`batch_no`", "DESC");
   $final_sql = ConcatWhere($base_sql, $payment_token);
   $final_sql = ConcatWhere($final_sql, $collection_token);
   $final_sql = ConcatWhere($final_sql, $student_token);
   $final_sql = ConcatOrder($final_sql, $order_token);

   $result = mysqli_query($connection, $final_sql);
   $num_row = mysqli_num_rows($result);
   //echo $final_sql;
   $result_array = [];
   if ($num_row > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
         $result_array[$row['student_code']][$row['batch_no']][] = $row;
      }
   }
?>
   <style type="text/css">
      @media print {
         #note {
            display: none;
         }
      }
   </style>

   <div class="uk-width-1-1 myheader text-center mt-5" style="color:white; text-align:center;">
      <h2 class="uk-text-center myheader-text-color myheader-text-style">Individual Student Collection Details</h2>
      From <?php echo $o_df ?> To <?php echo $o_dt ?><br>
      <?php
      if ($student != "") {
      ?>
         Student <?php echo getStudentName($student, $centre_code) ?>
      <?php
      } else {
      ?>
         Student ALL
      <?php
      }
      ?>
   </div>
   <div class="nice-form" style="overflow-x: scroll;">
      <div class="uk-grid">
         <div class="uk-width-medium-5-10">
            <table class="uk-table">
               <tr>
                  <td class="uk-text-bold">Centre Name</td>
                  <td><?php echo getCentreName($centre_code) ?></td>
               </tr>
               <tr>
                  <td class="uk-text-bold">Prepare By</td>
                  <td><?php echo $_SESSION["UserName"] ?></td>
               </tr>
               <tr id="note1 ">
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
                     $dt =  $_POST["dt"];
                     $df =  $_POST["df"];
					$origDate = $dt;
 
$date = str_replace('/', '-', $origDate );
$newDate = date("Y-m-d", strtotime($date));
$date=date_create($newDate);
//echo date_format($date,"Y");

$origDate1 = $df;
 
$date1 = str_replace('/', '-', $origDate1 );
$newDate1 = date("Y-m-d", strtotime($date1));
$date1=date_create($newDate1);
//echo date_format($date,"Y");
                     if (isset($df) && $df != '' && isset($dt) && $dt != '') {
                        //$f_month = substr($df, 5, 2);
                        $f_month = date_format($date1,"m");
                        //$f_year = substr($df, 0, 4);
                        $f_year = date_format($date1,"Y");
                        //$t_month = substr($dt, 5, 2);
                        $t_month = date_format($date,"m");
                        //$t_year = substr($dt, 0, 4);
                        $t_year = date_format($date,"Y");
						
                        //echo $df;
                        $sql = "SELECT * from codes where 
                        module='SCHOOL_TERM' and DATE_ADD(MAKEDATE(`year`, 1), INTERVAL (`from_month`)-1 MONTH) between '" . $f_year . "-" . $f_month . "-01' and '" . $t_year . "-" . $t_month . "-01' 
                         or module='SCHOOL_TERM' and DATE_ADD(MAKEDATE(`year`, 1), INTERVAL (`to_month`)-1 MONTH) between '" . $f_year . "-" . $f_month . "-01' and '" . $t_year . "-" . $t_month . "-01' 
                         or module='SCHOOL_TERM' and DATE_ADD(MAKEDATE(`year`, 1), INTERVAL (`from_month`)-1 MONTH) <= '" . $f_year . "-" . $f_month . "-01' and DATE_ADD(MAKEDATE(`year`, 1), INTERVAL (`to_month`)-1 MONTH) >= '" . $t_year . "-" . $t_month . "-01' order by category";


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
                  <td><?php echo date("Y-m-d H:i:s") ?></td>
               </tr>

            </table>
         </div>
      </div>
      <table class="uk-table">
         <tr class="uk-text-bold">
            <td>No</td>
            <td>Receipt No.</td>
            <td>Student</td>
            <td>Collection Type</td>
            <td>Collection Month</td>
            <td>Bank In Date</td>
            <td>Received Date</td>
            <td>Payment Type</td>
            <td>Ref No/Cheque No</td>
            <!-- <td>Paid Amount</td>-->
            <td class="uk-text-right">Total Amount</td>
            <td></td>
         </tr>

         <?php
         /*$base_sql="SELECT c.* from collection c, allocation a, student s where c.centre_code='$centre_code'
and c.collection_date_time>='$df 00:00:00' and collection_date_time<='$dt 23:59:59' and c.void=0 and c.allocation_id=a.id
and a.student_id=s.id and s.deleted=0 and a.deleted=0 and s.student_status='A'";*/
         if ($num_row > 0) {
            $count = 0;
            $grand_total = 0;

            foreach ($result_array as $key => $value) {
               $student_total = 0;
               foreach ($value as $key2 => $value2) {
                  $receipt_total = 0;
                  for ($i = 0; $i < count($value2); $i++) {
                     $grand_total += $value2[$i]["amount"];
                     $student_total += $value2[$i]["amount"];
                     $receipt_total += $value2[$i]["amount"];
         ?>
                     <tr>
                        <?php
                        if ($i == 0) {
                           $count++;
                        ?>
                           <td class="uk-text-right"><?php echo $count ?></td>
                           <td><?php echo $value2[$i]["batch_no"] ?></td>
                           <td><?php echo $value2[$i]["name"] . "<br>" . $value2[$i]["student_code"] ?></td>
                        <?php
                        } else {
                        ?>
                           <td colspan="3"></td>
                        <?php
                        }
                        ?>
                        <td>
                           <?php
                           // switch ($value2[$i]["collection_type"]) {
                           //    case "tuition":
                           //       echo "Tuition Fee";
                           //       break;
                           //    case "registration":
                           //       echo $value2[$i]["subject"] ? "Registration - " . $value2[$i]["subject"] : "Registration";
                           //       break;
                           //    case "deposit":
                           //       echo $value2[$i]["subject"] ? "Deposit - " . $value2[$i]["subject"] : "Deposit";
                           //       break;
                           //    case "placement":
                           //       echo $value2[$i]["subject"] ? "Placement - " . $value2[$i]["subject"] : "Placement";
                           //       break;
                           //    case "product":
                           //       echo $value2[$i]["product_name"] ? "Product Sale - " . $value2[$i]["product_name"] : "Product Sale";
                           //       break;
                           //    case "addon-product":
                           //       echo $value2[$i]["product_name"] ? "Addon Product - " . $value2[$i]["product_name"] : "Addon Product"; 
                           //       break;

                           //       // case "product":
                           //       //    echo "Product Sale - " . $row["product_name"];
                           //       //    break;
                           //    // case "addon-product":
                           //    //    echo "Addon Product - " . $row["product_name"];
                           //    //    break;
                           //    case "mobile":
                           //       echo "Mobile Apps";
                           //       break;
                           //       break;
                           //    default:
                           //       echo ucwords($value2[$i]["collection_type"]);
                                 // echo ucwords(explode("((--", $value2[$i]["product_code"])[0]);
                                // echo (explode("((--", $value2[$i]["product_name"])[0]);
                              //echo $value2[$i]["product_code"];
                           //}
                           switch ($value2[$i]["collection_type"]) {
                              case "product":
                                 echo $value2[$i]["product_name"] ? "Product Sale - " . $value2[$i]["product_name"] : "Product Sale";
                                 break;
                              case "addon-product":
                                 echo $value2[$i]["product_name"] ? "Addon Product - " . $value2[$i]["product_name"] : "Addon Product"; 
                                 break;
                              default:
                                 echo $value2[$i]["product_code"];
                           }
                           ?>
                        </td>
                        <td>
                           <?php
                           if ($value2[$i]["collection_month"] != "") {
                              if ($value2[$i]["collection_pattern"] == "Monthly") {
                              echo date('F', mktime(0, 0, 0, $value2[$i]["collection_month"], 10));
                              }else if ($value2[$i]["collection_pattern"] == "Termly") {
                                 echo getStrTerm($value2[$i]["collection_month"]);
                              }else if ($value2[$i]["collection_pattern"] == "Half Year") {
                                 echo getStrHalfYearly($value2[$i]["collection_month"]);
                              }else if ($value2[$i]["collection_pattern"] == "Annually") {
                              echo "Annually";
                              }
                           } else {
                              echo "NA";
                           }
                           ?>
                        </td>
                        <td><?php echo $value2[$i]["collection_date_time"] ?></td>
                        <td><?php echo $value2[$i]["transaction_date"] ?></td>
                        <td>
                           <?php if ($value2[$i]["payment_method"] == 'BT') : ?>
                              BANK TRANSFER
                           <?php elseif ($value2[$i]["payment_method"] == 'CC') : ?>
                              CREDIT CARD
                           <?php else : ?>
                              <?php echo $value2[$i]["payment_method"] ?>
                           <?php endif; ?>
                        </td>
                        <td><?php echo ($value2[$i]["payment_method"] == 'CASH' || $value2[$i]["payment_method"] == 'CC' || $value2[$i]["payment_method"] == 'BT' ? $value2[$i]["ref_no"] : $value2[$i]["cheque_no"]); ?></td>
                        <!--<td><?php // echo ($value2[$i]["payment_method"] != 'CN'? $value2[$i]["amount"] : '-'); 
                                 ?></td>-->
                        <td class="uk-text-right"><?php echo $value2[$i]["amount"] ?></td>
                        <td></td>
                     </tr>
                  <?php
                  }
                  ?>
                  <tr>
                     <td colspan="9" class="uk-text-right uk-text-bold">Receipt Total : </td>
                     <td class="uk-text-right"><?php echo number_format($receipt_total, 2) ?></td>
                  </tr>
               <?php
               }
               ?>
               <!-- <tr>
            <td colspan="9" class="uk-text-right uk-text-bold">Student Total : </td>
            <td class="uk-text-right"><?php echo number_format($student_total, 2) ?></td>
         </tr> -->
            <?php
            }
            ?>
            <!-- <tr>
         <td colspan="10" class="uk-text-right uk-text-bold">Branch Total : </td>
         <td class="uk-text-right uk-text-bold"><?php //echo number_format($grand_total, 2)
                                                ?></td>
      </tr> -->
            <tr>
               <td colspan="9" class="uk-text-right uk-text-bold">Grand Total : </td>
               <td class="uk-text-right uk-text-bold"><?php echo number_format($grand_total, 2) ?></td>
            </tr>
         <?php
         } else {
            echo "<tr><td colspan='7'>No record found</td></tr>";
         }
         ?>

      </table>
   <?php
} else {
   echo "Please enter a Centre, From Date and To Date";
}
   ?>
   </div>
   <script>
      $(document).ready(function() {
         var method = '<?php echo $method ?>';
         if (method == "print") {
            window.print();
            $("#note").show();
         }
      });
   </script>