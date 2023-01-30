<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");
include_once("../search_new.php");

foreach ($_POST as $key => $value) {
   $$key = mysqli_real_escape_string($connection, $value); //$centre_code; $program
}

if ($method == "print") {
   include_once("../uikit1.php");
}

function calcBF($centre_code, $term, $level, $module)
{
   if ($term > 1) {
      return calcCF($centre_code, $term - 1, $level, $module);
   } else {
      return 0;
   }
}

function calcCF($centre_code, $term, $level, $module)
{
   if ($term == 1) {
      $opening = calcTotalPurchase($centre_code, 1, $term, $level, $module);
      $total_purchase = calcTotalPurchase($centre_code, 2, $term, $level, $module);
   } else {
      $total_purchase = calcTotalPurchase($centre_code, 1, $term, $level, $module);
   }

   $total_consume = calcTotalConsume($centre_code, $term, $level, $module);
   $CF = $opening + $total_purchase - $total_consume;

   return $CF;
}

function calcTotalConsume($centre_code, $term, $level, $module)
{
   global $connection, $program, $selected_month, $lvl;

   if (($level == "") & ($module == "")) {
      $final_program = $program;
   } else {
      $final_program = $program . "L" . $level . "M" . $module;
   }

   $base_sql = "SELECT sum(c.qty) as qty from `collection` c, `course` co, `product_course` pc where co.deleted=0
   and c.product_code=pc.product_code and pc.course_id=co.id and co.course_name like '$final_program%'
   and c.year='" . $_SESSION['Year'] . "' and c.collection_type='product' and c.collection_month='$term'";

   if ($centre_code != 'ALL') {
      $base_sql .= " and c.centre_code = '$centre_code'";
   }

   if ($selected_month != '') {
      $str_length = strlen($selected_month);
      $month = substr($selected_month, ($str_length - 2), 2);
      $year = substr($selected_month, 0, -2);
      $base_sql .= " and c.year = '$year' and c.collection_month = '$month'";
   }

   if ($lvl != 'ALL') {
      if (preg_match('/^IE/', $lvl, $output) == 1) {
         $base_sql .= " and SUBSTR(co.course_name, 1, 4) = '$lvl'";
      } else {
         $base_sql .= " and SUBSTR(co.course_name, 1, 6) = '$lvl'";
      }
   }

   // $cc_token=ConstructToken("c.centre_code", $centre_code, "=");
   // $order_token=ConstructOrderToken("c.collection_date_time", "asc");
   // $final_sql=ConcatWhere($base_sql, $cc_token);
   // if ($term=="t5") {
   //    writeLog($final_sql);
   // }
   $result = mysqli_query($connection, $base_sql);
   $row = mysqli_fetch_assoc($result);

   return number_format($row["qty"], 0);
}

function getPurchasingRecords($centre_code, $term)
{
   global $connection, $program, $selected_month, $lvl;
   $base_sql = "SELECT o.*, co.course_name as course_name, o.order_no, o.ordered_on from `order` o, `product_course` pc, `course` co where o.product_code=pc.product_code
   and pc.course_id=co.id and (CAST(`ordered_on` AS DATE) BETWEEN '".$year_start_date."' AND '".$year_end_date."') and month(ordered_on)='" . $term . "' and co.course_name like '$program%'
   and o.delivered_to_logistic_by<>'' and co.deleted=0";

   if ($centre_code != 'ALL') {
      $base_sql .= " and o.centre_code = '$centre_code'";
   }

   if ($selected_month != '') {
      $str_length = strlen($selected_month);
      $month = substr($selected_month, ($str_length - 2), 2);
      $year = substr($selected_month, 0, -2);
      $base_sql .= " and year(o.ordered_on) = '$year' and month(o.ordered_on) = '$month'";
   }

   if ($lvl != 'ALL') {
      if (preg_match('/^IE/', $lvl, $output) == 1) {
         $base_sql .= " and SUBSTR(co.course_name, 1, 4) = '$lvl'";
      } else {
         $base_sql .= " and SUBSTR(co.course_name, 1, 6) = '$lvl'";
      }
   }

   // $cc_token=ConstructToken("o.centre_code", $centre_code, "=");
   // $final_sql=ConcatWhere($base_sql, $cc_token);
   $result = mysqli_query($connection, $base_sql);

   return $result;
}

function getPurchaseQTY($centre_code, $request, $term, $level, $module, $purchase_no)
{
   global $connection, $program, $selected_month, $lvl;

   if (($level == "") & ($module == "")) {
      return number_format($request['qty'], 0);
   } else {
      $final_program = "/^" . $program . "L" . $level . "M" . $module . "/";
      if (preg_match($final_program, $request['course_name']) == true) {
         return number_format($request['qty'], 0);
      } else {
         return 0;
      }
   }
}

function calcTotalPurchase($centre_code, $purchase_no, $term, $level, $module)
{
   global $connection, $program, $selected_month, $lvl;
   // need to find out when a term starts and end
   $df = "";
   $dt = "";

   if (($level == "") & ($module == "")) {
      $final_program = $program;
   } else {
      $final_program = $program . "L" . $level . "M" . $module;
   }

   $base_sql = "SELECT sum(o.qty) as qty from `order` o, `product_course` pc, `course` co where o.product_code=pc.product_code
   and pc.course_id=co.id and (CAST(`ordered_on` AS DATE) BETWEEN '".$year_start_date."' AND '".$year_end_date."') and month(ordered_on)='" . $term . "' and co.course_name like '$final_program%'
   and o.delivered_to_logistic_by<>'' and co.deleted=0";

   if ($centre_code != 'ALL') {
      $base_sql .= " and o.centre_code = '$centre_code'";
   }

   if ($selected_month != '') {
      $str_length = strlen($selected_month);
      $month = substr($selected_month, ($str_length - 2), 2);
      $year = substr($selected_month, 0, -2);
      $base_sql .= " and year(o.ordered_on) = '$year' and month(o.ordered_on) = '$month'";
   }

   if ($lvl != 'ALL') {
      if (preg_match('/^IE/', $lvl, $output) == 1) {
         $base_sql .= " and SUBSTR(co.course_name, 1, 4) = '$lvl'";
      } else {
         $base_sql .= " and SUBSTR(co.course_name, 1, 6) = '$lvl'";
      }
   }

   // $cc_token=ConstructToken("o.centre_code", $centre_code, "=");
   // $date_token_group=ConstructTokenGroup("o.ordered_on", $df, "<=", "o.ordered_on", $dt, ">=", "and");

   // $final_token=ConcatToken($cc_token, $date_token_group, "and");
   // $final_sql=ConcatWhere($base_sql, $cc_token);
   $result = mysqli_query($connection, $base_sql);
   $row = mysqli_fetch_assoc($result);

   return number_format($row["qty"], 0);
}

function calcTotalStudent($centre_code, $term, $level, $module)
{
   global $connection, $program, $selected_month, $lvl;

   if (($level == "") & ($module == "")) {
      $final_program = $program;
   } else {
      $final_program = $program . "L" . $level . "M" . $module;
   }

   if ($program == "IE") {
      $term = "t" . $term;
   }

   $base_sql = "SELECT * from `allocation` a, `student` s, `course` co where a.student_id=s.id and a.course_id=co.id
   and year='" . $_SESSION['Year'] . "' and s.deleted=0 and co.deleted=0 and a.deleted=0 and s.student_status='A'
   and a.allocated_date_time>='$df' and a.allocated_date_time<='$dt' and co.course_name like '$final_program%'";

   if ($selected_month != '') {
      $str_length = strlen($selected_month);
      $month = substr($selected_month, ($str_length - 2), 2);
      $year = substr($selected_month, 0, -2);
      $base_sql .= " and year(a.allocated_date_time) = '$year' and month(a.allocated_date_time) = '$month'";
   }

   if ($lvl != 'ALL') {
      if (preg_match('/^IE/', $lvl, $output) == 1) {
         $base_sql .= " and SUBSTR(co.course_name, 1, 4) = '$lvl'";
      } else {
         $base_sql .= " and SUBSTR(co.course_name, 1, 6) = '$lvl'";
      }
   }

   $cc_token = ConstructToken("s.centre_code", $centre_code, "=");
   $final_sql = ConcatWhere($base_sql, $cc_token);
   $result = mysqli_query($connection, $final_sql);
   $num_row = mysqli_num_rows($result);

   return $num_row;
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

if (($centre_code != "") & ($program != "")) {
?>
   <style>
      tr td,
      tr th {
         width: 1%;
         white-space: nowrap;
      }

      .t-border tr,
      .t-border td,
      .t-border th {
         border: 1px solid #aaa;
         text-align: center;
      }
   </style>


   <div class="uk-width-1-1 myheader text-center mt-5" style="color:white;text-align:center;">
      <h2 class="uk-text-center myheader-text-color myheader-text-style">CENTRE MONTHLY STOCK REPORT</h2>
      Program : <?php echo $program ?>
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
               <tr>
                  <td colspan="2" class="uk-text-bold">NOTE: To be submitted termly with the order form</td>
               </tr>
            </table>
         </div>
         <div class="uk-width-medium-5-10">
            <table class="uk-table">
               <tr>
                  <td class="uk-text-bold">Academic Year</td>
                  <td><?php echo $_SESSION['Year'] ?></td>
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
                  <td><?php echo date("Y-m-d H:i:s") ?></td>
               </tr>
            </table>
         </div>
      </div>
      <?php
      if ($program == "IE") {
         // include_once("sctTermlyReport.php");
         include_once("sctMonthlyReport.php");
      } else {
         include_once("sctMonthlyReport.php");
      }
      ?>

   <?php
} else {
   echo "Please enter a centre and program";
}
   ?>
   </div>
   <style type="text/css" media="print">
      @media print {
         html {
            zoom: 50%;
         }
      }

      @page {
         size: landscape;
      }
   </style>
   <script>
      $(document).ready(function() {
         var method = '<?php echo $method ?>';
         if (method == "print") {
            window.print();
         }
      });
   </script>