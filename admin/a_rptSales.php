<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");
include_once("../search_new.php");

foreach ($_POST as $key => $value) {
   $$key = mysqli_real_escape_string($connection, $value); //$centre_code, $df, $dt, $student_id
}
// $df = convertDate2ISO($df);
// $dt = convertDate2ISO($dt);

if ($method == "print") {
   include_once("../uikit1.php");
}
$year = $_SESSION['Year'];
// $month = str_split($selected_month, 4)[1];
// $year = str_split($selected_month, 4)[0];

if ($collection_type == "ALL") {
   $collection_type = "";
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

function getStudentName($student, $centre_code)
{
   global $connection;
   $student_sql = "SELECT name from student  where centre_code='$centre_code' and name like '%$student%' or student_code like '%$student%'";
   //echo $student_sql;
   $result = mysqli_query($connection, $student_sql);
   $row = mysqli_fetch_assoc($result);

   return $row['name'];
}
//if (($centre_code != "") & ($selected_month != "")) {
if ($centre_code != "") {
   echo getStudentCodeFromAllocationID($row["allocation_id"], $student_name) . "";
?>

   <style type="text/css">
      @media print {
         #note {
            display: none;
         }
      }
   </style>

   <div class="uk-width-1-1 myheader text-center mt-5" style="color:white; text-align:center;">
      <h2 class="uk-text-center myheader-text-color myheader-text-style">CENTRE COLLECTION SUMMARY REPORT</h2>
      For <?php echo $df. ' To '. $dt; //echo date('F', mktime(0, 0, 0, $month, 10)) . ' ';
           ?><br>
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
   <div class="nice-form">
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
                     } ?></td>
                  <!--<td><?php// echo $years; ?></td>-->
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
               <tr id="" style="display: none;">
                  <td colspan="2" class="uk-text-bold">NOTE: To be submitted termly with the order form</td>
               </tr>
            </table>
         </div>
      </div>
      <table class="uk-table">
         <tr class="uk-text-bold">
            <td>No</td>
            <td>Receipt No.</td>
            <td>Student</td>
            <!-- <td>User</td> -->
            <!-- <td>Class</td> -->
            <td>Received Date</td>
            <!-- <td>Received Amount</td>
      <td>Sub Category</td> -->
            <td>Payment Method</td>
            <td>Cheque No</td>
            <td class="uk-text-right">Amount</td>
         </tr>
         <?php
         /*$base_sql="SELECT c.*, sum(c.amount) as total_amount from collection c, allocation a, student s where c.centre_code='$centre_code'
and c.collection_date_time>='$df 00:00:00' and collection_date_time<='$dt 23:59:59' and c.void=0 and c.allocation_id=a.id
and a.student_id=s.id and s.deleted=0 and a.deleted=0 and s.student_status='A'";*/
$df = convertDate2ISO($df);
$dt = convertDate2ISO($dt);
         if ($month == '13') {
            $base_sql = "SELECT c.student_id, sum(amount) as total_amount from collection c left join student s on c.student_code=s.student_code ";
            //if($collection!=""){
               $base_sql .= " left join payment_detail p on p.id=c.payment_detail_id ";
            //}
            $base_sql .= "where c.collection_date_time>='$df 00:00:00' and collection_date_time<'$dt 23:59:59' or (CAST(`collection_date_time` AS DATE) BETWEEN '".$year_start_date."' AND '".$year_end_date."') and c.void=0 ";
            if ($centre_code !== "ALL") {
               $base_sql .= " and c.centre_code='$centre_code'";
            }
         } else {
            $base_sql = "SELECT c.student_id, sum(amount) as total_amount from collection c left join student s on c.student_code=s.student_code ";
             $base_sql .= " left join payment_detail p on p.id=c.payment_detail_id ";
             $base_sql .= " where c.collection_date_time>='$df 00:00:00' and collection_date_time<'$dt 23:59:59' and (CAST(`collection_date_time` AS DATE) BETWEEN '".$year_start_date."' AND '".$year_end_date."') and c.void=0 ";
            if ($centre_code !== "ALL") {
               $base_sql .= " and c.centre_code='$centre_code'";
            }
           
			
         }
         if($collection!=""){
            $base_sql .= " and p.payment_method like '%$collection%' ";
         }
           //echo $base_sql; //die;
         $student_token = ConstructTokenGroup("s.name", "%$student%", "like", "s.student_code", "%$student%", "like", "or");


         $order_token = ConstructOrderToken("collection_date_time", "asc");
         $final_sql = ConcatWhere($base_sql, $student_token);
         $final_sql = ConcatGroupBy($final_sql, "c.student_id");
         $final_sql = ConcatOrder($final_sql, $order_token);

         // var_dump($final_sql);

         $result = mysqli_query($connection, $final_sql);
         $num_row = mysqli_num_rows($result);

         if ($num_row > 0) {
            $count = 0;
            $grand_total = 0;
            while ($row = mysqli_fetch_assoc($result)) {
               $noCollected_row = '';
               $grand_total = $grand_total + $row["total_amount"];

         //       if ($month == '13') {

         //          $base_sql2 = "SELECT c.*, s.name, sum(amount) as total_amount from collection c left join student s on c.student_code=s.student_code
         // where c.student_id='$row[student_id]' and month(c.collection_date_time)='$month' or year(collection_date_time)='$year' and c.void=0 ";
         //          if ($centre_code !== "ALL") {
         //             $base_sql .= " and c.centre_code='$centre_code'";
         //          }
         //       } else {

                  $base_sql2 = "SELECT c.*, s.name, sum(amount) as total_amount from collection c left join student s on c.student_code=s.student_code
         where c.student_id='$row[student_id]' and c.collection_date_time>='$df 00:00:00' and collection_date_time<'$dt 23:59:59' and (CAST(`collection_date_time` AS DATE) BETWEEN '".$year_start_date."' AND '".$year_end_date."') and c.void=0 ";
                  if ($centre_code !== "ALL") {
                     $base_sql .= " and c.centre_code='$centre_code'";
                  }
               //}

               $student_token = ConstructTokenGroup("s.name", "%$student%", "like", "s.student_code", "%$student%", "like", "or");
               $order_token2 = ConstructOrderToken("collection_date_time", "desc");
               $final_sql2 = ConcatWhere($base_sql2, $student_token);
               // $final_sql2=ConcatGroupBy($final_sql2, "p.sub_category, c.student_id");
               $final_sql2 = ConcatGroupBy($final_sql2, "c.batch_no");      //$order_token3=ConstructOrderToken("`c`.`batch_no`", "asc");
               $final_sql2 = ConcatOrder($final_sql2, $order_token2);
               $final_sql2 = ConcatOrder($final_sql2, $order_token3);
               //  echo $final_sql2; 
               $result2 = mysqli_query($connection, $final_sql2);
               $num_row2 = mysqli_num_rows($result2);

               if ($num_row2 > 0) {
                  while ($row2 = mysqli_fetch_assoc($result2)) {
                     $base_sql3 = "SELECT * from payment_detail where id='$row2[payment_detail_id]' and payment_method <> 'CN'";

                     $collection_token = ConstructToken("payment_method", "%$collection%", "like", "and");

                     $base_sql3 = ConcatWhere($base_sql3, $collection_token);
                     // var_dump($base_sql3);
                     $result3 = mysqli_query($connection, $base_sql3);
                     $row3 = mysqli_fetch_assoc($result3);
                     if (count($row3) > 0) {
                        $count++;
         ?>
                        <tr>
                           <td class="uk-text-right"><?php echo $count ?></td>
                           <td><?php echo $row2["batch_no"] ?></td>
                           <td><?php echo $row2["name"] . "<br>" . $row2["student_code"] ?></td>
                           <!-- <td><?php // echo $row2["sub_sub_category"]
                                    ?></td> -->
                           <td><?php echo $row2["collection_date_time"] ?></td>
                           <!--<td class="uk-text-center"><?php // echo $row2["total_amount"]
                                                            ?></td>-->
                           <td class="uk-text-center">
                              <?php if ($row3["payment_method"] == 'BT') : ?>
                                 BANK TRANSFER
                              <?php elseif ($row3["payment_method"] == 'CC') : ?>
                                 CREDIT CARD
                              <?php else : ?>
                                 <?php echo $row3["payment_method"] ?>
                              <?php endif; ?>
                           </td>
                           <td class="uk-text-center"><?php echo ($row3["payment_method"] == 'CHEQUE' ? $row3["cheque_no"] : '-'); ?></td>
                           <td class="uk-text-right"><?php echo $row2["total_amount"] ?></td>
                        </tr>
                  <?php
                     } else {
                        $noCollected_row = 'on';
                     }
                  }
               }
               if ($noCollected_row != 'on') {
                  ?>

            <?php
               } else {
                 // $grand_total -= $row["total_amount"];
               }
            }
            ?>
            <tr>
               <td colspan="6" class="uk-text-right uk-text-bold">Grand Total : </td>
               <td class="uk-text-right"><?php echo number_format($grand_total, 2) ?></td>
            </tr>
         <?php
         } else {
            echo "<tr><td colspan='8'>No record found</td></tr>";
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
            $("#note1").show();
         }
      });
   </script>