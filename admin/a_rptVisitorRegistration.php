<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");

foreach ($_POST as $key => $value) {
   $$key = mysqli_real_escape_string($connection, $value); //$centre_code
}

if ($method == "print") {
   include_once("../uikit1.php");
}

$year = $_SESSION["Year"];

function getVisitorByMonth($centre_code, $month)
{
   global $connection, $year;

   if ($centre_code == "ALL") {    
      $sql="SELECT count(number_of_children) as count from visitor where month(date_created)='$month' and year(date_created)='$year'";
      //$sql="SELECT COALESCE(SUM(number_of_children), 0) as count from visitor where month(date_created)='$month' and year(date_created)='$year'"; 
   } else {
      $sql = "SELECT count(number_of_children) as count from visitor where centre_code='$centre_code' and month(date_created)='$month'
      and year(date_created)='$year'";
   }

   $result = mysqli_query($connection, $sql);
   $row = mysqli_fetch_assoc($result);
   return $row["count"];
}

function getVisitor($centre_code, $reason, $month)
{
   global $connection, $year;

   if ($centre_code == "ALL") {
      $sql = "SELECT count(number_of_children) as count from visitor where find_out like '$reason%' and month(date_created)='$month' and year(date_created)='$year'";
   } else {
      $sql = "SELECT count(number_of_children) as count from visitor where centre_code='$centre_code' and find_out like '$reason%'
      and month(date_created)='$month' and year(date_created)='$year'";
   }
   // echo  $sql . "<br>"; 
   $result = mysqli_query($connection, $sql);
   $row = mysqli_fetch_assoc($result);
   return $row["count"];
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

function getConversionQTYByMonth($centre_code, $month)
{
   global $connection, $year;

   $total_converted = 0;
   if ($centre_code == "ALL") {
      $sql = "SELECT id from visitor where month(date_created)='$month' and year(date_created)='$year'";
   } else {
      $sql = "SELECT id from visitor where centre_code='$centre_code' and month(date_created)='$month' and year(date_created)='$year'";
   }
   // if($month==3){
    // echo $sql;
   // }
   $result = mysqli_query($connection, $sql);
   while ($row = mysqli_fetch_assoc($result)) {
      $sql2 = "SELECT * from visitor_student where visitor_id = '" . $row["id"] . "'";
      $result2 = mysqli_query($connection, $sql2);
      $row = mysqli_fetch_assoc($result2);
      $SumChildRegistered = 0;
      for ($i = 1; $i < 7; $i++) {
         if ($row['child_' . $i . '_student_id'] != null) {
            $SumChildRegistered += 1;
         }
      }
      $total_converted = $SumChildRegistered + $total_converted;
   }

   return $total_converted;
}

function getParentConversionQTYByMonth($centre_code, $month)
{
   global $connection, $year;

   $total_converted_ = 0;
   if ($centre_code == "ALL") {
      $sql = "SELECT id from visitor where month(date_created)='$month' and year(date_created)='$year'";
   } else {
      $sql = "SELECT id from visitor where centre_code='$centre_code' and month(date_created)='$month' and year(date_created)='$year'";
   }
   // echo $sql; die;
   $result = mysqli_query($connection, $sql);
   while ($row = mysqli_fetch_assoc($result)) {
      $sql2 = "SELECT * from visitor_student where visitor_id = '" . $row["id"] . "'";
      $result2 = mysqli_query($connection, $sql2);
      $row = mysqli_fetch_assoc($result2);
      if ($row > 0) {
         $total_converted_ += 1;
      }
   }
   return $total_converted_;
}

function getConversionQTY($centre_code, $reason, $month)
{
   global $connection, $year;

   $total_converted = 0;
   if ($centre_code == "ALL") {
      $sql = "SELECT id from visitor where find_out='$reason' and month(date_created)='$month' and year(date_created)='$year'";
   } else {
      $sql = "SELECT id from visitor where centre_code='$centre_code' and find_out='$reason' and month(date_created)='$month' and year(date_created)='$year'";
   }
   $result = mysqli_query($connection, $sql);
   while ($row = mysqli_fetch_assoc($result)) {
      $sql2 = "SELECT * from visitor_student where visitor_id = '" . $row["id"] . "'";
      $result2 = mysqli_query($connection, $sql2);
      $row = mysqli_fetch_assoc($result2);
      $SumChildRegistered = 0;
      for ($i = 1; $i < 7; $i++) {
         if ($row['child_' . $i . '_student_id'] != null) {
            $SumChildRegistered += 1;
         }
      }
      $total_converted = $SumChildRegistered + $total_converted;
   }

   return $total_converted;
}
function getParentConversion($centre_code, $reason, $month)
{
   global $connection, $year;

   $total_converted_ = 0;
   if ($centre_code == "ALL") {
      $sql = "SELECT id from visitor where find_out='$reason' and month(date_created)='$month' and year(date_created)='$year'";
   } else {
      $sql = "SELECT id from visitor where centre_code='$centre_code' and find_out='$reason' and month(date_created)='$month' and year(date_created)='$year'";
   }
   $result = mysqli_query($connection, $sql);
   while ($row = mysqli_fetch_assoc($result)) {
      $sql2 = "SELECT * from visitor_student where visitor_id = '" . $row["id"] . "'";
      $result2 = mysqli_query($connection, $sql2);
      $row = mysqli_fetch_assoc($result2);
      if ($row > 0) {
         $total_converted_ += 1;
      }
   }

   return $total_converted_;
}

if ($centre_code != "") {
?>
   <style type="text/css">
      @page {
         size: auto;
      }
   </style>

   <div class="uk-width-1-1 myheader text-center mt-5">
      <h2 class="uk-text-center myheader-text-color myheader-text-style">VISITORS REGISTRATION REPORT</h2>
   </div>
   <div class="nice-form">
      <div class="uk-grid">
         <div class="uk-width-medium-5-10">
            <table class="uk-table" style="width: 100%;">
               <tr>

                  <td class="uk-text-bold">Centre Name</td>
                  <td><?php echo getCentreName($centre_code); ?></td>
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
                     $month = date("m");
                     $year = $_SESSION["Year"];
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
      <div>
      <?php
         $str_length = !empty($selected_month) ? strlen($selected_month) : 0;
         if (!empty($selected_month) && (str_split($selected_month, ($str_length - 2))[1] != 13)) {
            $monthNum = str_split($selected_month, ($str_length - 2))[1];
            $year = str_split($selected_month, ($str_length - 2))[0];
      ?>
            <table class="uk-table">
               <tr class="uk-text-bold">
                  <td>Media</td>
                  <td><?php echo date('M', mktime(0, 0, 0, $monthNum, 10)); ?></td>
                  <td class="d_none">Parent Conversion<br>by Media</td>
                  <td class="d_none">Registered Student<br>by Media</td>
                  <td>Total Visitors by Media</td>
               </tr>
               <?php
               $sql = "SELECT * from codes where module='VISITREASON' order by code";
               $result = mysqli_query($connection, $sql);
               $sum_ConvertedAllMonths = 0;
               while ($row = mysqli_fetch_assoc($result)) {
                  $month = getVisitor($centre_code, $row["code"], $monthNum);
               ?>
                  <tr>
                     <td class="uk-text-bold"><?php echo $row["code"] ?></td>
                     <td><?php echo $month ?></td>
                     
                     <td class="d_none"><?php echo getParentConversion($centre_code, $row["code"], $monthNum) ?></td>
                     <td class="d_none"><?php echo getConversionQTY($centre_code, $row["code"], $monthNum) ?></td>
                     <td><?php echo $month ?></td>
                  </tr>
               <?php
               }
               $month_total = getVisitorByMonth($centre_code, $monthNum);
               ?>
               <tr>
                  <td class="uk-text-bold">Total Visitors by Month</td>
                  <td><?php echo $month_total ?></td>
                  <td class="d_none"><?php echo getParentConversionQTYByMonth($centre_code, $monthNum) ?></td>
                  <td class="d_none"><?php echo getConversionQTYByMonth($centre_code, $monthNum) ?></td>
                  <td><?php echo $month_total ?></td>
               </tr>
               <!--<tr>
                  <td class="uk-text-bold">Total Parent Conversion</td>
                  <td><?php echo getConversionQTYByMonth($centre_code, $monthNum) ?></td>
                  <td> - </td>
                  <td><?php echo getConversionQTYByMonth($centre_code, $monthNum) ?></td>
               </tr>
               <tr>
                  <td colspan='4' class="uk-text-bold" style="text-align: right;">Total Registered Student</td>
                  <td>-</td>

               </tr>-->
            </table>

         <?php
         } else {

            $str_length = !empty($selected_month) ? strlen($selected_month) : 0;
            if (!empty($selected_month) && (str_split($selected_month, ($str_length - 2))[1] == 13)) {
               $year = str_split($selected_month, ($str_length - 2))[0];
            }
         ?>
            <table class="uk-table" style="width: 100%;">
               <tr class="uk-text-bold">
                  <td style="vertical-align: middle;">Media</td>
                  <td style="vertical-align: middle;">Jan</td>
                  <td style="vertical-align: middle;">Feb</td>
                  <td style="vertical-align: middle;">Mar</td>
                  <td style="vertical-align: middle;">Apr</td>
                  <td style="vertical-align: middle;">May</td>
                  <td style="vertical-align: middle;">June</td>
                  <td style="vertical-align: middle;">July</td>
                  <td style="vertical-align: middle;">Aug</td>
                  <td style="vertical-align: middle;">Sept</td>
                  <td style="vertical-align: middle;">Oct</td>
                  <td style="vertical-align: middle;">Nov</td>
                  <td style="vertical-align: middle;">Dec</td>
                  <td style="vertical-align: middle;" class="d_none">Total <br>Parent Conversion<br>by Media</td>
                  <td style="vertical-align: middle;" class="d_none">Total <br>Registered Student<br>by Media</td>
                  <td style="vertical-align: middle;">Total Visitors <br> by Media</td>
               </tr>
               <?php
               $sql = "SELECT * from codes where module='VISITREASON' order by code";
               $result = mysqli_query($connection, $sql);
               $sum_ParentConvertedAllMonths = 0;
               $sum_ConvertedAllMonths = 0;
               while ($row = mysqli_fetch_assoc($result)) {
                  $jan = getVisitor($centre_code, $row["code"], "1");
                  $feb = getVisitor($centre_code, $row["code"], "2");
                  $mar = getVisitor($centre_code, $row["code"], "3");
                  $apr = getVisitor($centre_code, $row["code"], "4");
                  $may = getVisitor($centre_code, $row["code"], "5");
                  $jun = getVisitor($centre_code, $row["code"], "6");
                  $jul = getVisitor($centre_code, $row["code"], "7");
                  $aug = getVisitor($centre_code, $row["code"], "8");
                  $sep = getVisitor($centre_code, $row["code"], "9");
                  $oct = getVisitor($centre_code, $row["code"], "10");
                  $nov = getVisitor($centre_code, $row["code"], "11");
                  $dec = getVisitor($centre_code, $row["code"], "12");
                  $total = $jan + $feb + $mar + $apr + $may + $jun + $jul + $aug + $sep + $oct + $nov + $dec;
                  $sum_ParentConvertedByReason = 0;
                  for ($i = 1; $i < 13; $i++) {
                     $sum_ParentConvertedByReason = $sum_ParentConvertedByReason + getParentConversion($centre_code, $row["code"], $i);
                  }
                  if ($total == 0) {
                     $ParentConversionRate = 0;
                  } else {
                     $sum_ParentConvertedAllMonths = $sum_ParentConvertedAllMonths + $sum_ParentConvertedByReason;
                     $ParentConversionRate = round((($sum_ParentConvertedByReason / $total) * 100), 2);
                  }

                  $sum_ConvertedByReason = 0;
                  for ($i = 1; $i < 13; $i++) {
                     $sum_ConvertedByReason = $sum_ConvertedByReason + getConversionQTY($centre_code, $row["code"], $i);
                  }
                  if ($total == 0) {
                     $conversionRate = 0;
                  } else {
                     $sum_ConvertedAllMonths = $sum_ConvertedAllMonths + $sum_ConvertedByReason;
                     $conversionRate = round((($sum_ConvertedByReason / $total) * 100), 2);
                  }
               ?>
                  <tr>
                     <td class="uk-text-bold"><?php echo $row["code"] ?></td>
                     <td><?php echo $jan ?></td>
                     <td><?php echo $feb ?></td>
                     <td><?php echo $mar ?></td>
                     <td><?php echo $apr ?></td>
                     <td><?php echo $may ?></td>
                     <td><?php echo $jun ?></td>
                     <td><?php echo $jul ?></td>
                     <td><?php echo $aug ?></td>
                     <td><?php echo $sep ?></td>
                     <td><?php echo $oct ?></td>
                     <td><?php echo $nov ?></td>
                     <td><?php echo $dec ?></td>
                     
                     <td class="d_none"><?php echo $sum_ParentConvertedByReason ?></td>
                     <td class="d_none"><?php echo $sum_ConvertedByReason ?></td>
                     <td><?php echo $total ?></td>
                  </tr>
               <?php
               }
               $jan_total = getVisitorByMonth($centre_code, "1");
               $feb_total = getVisitorByMonth($centre_code, "2");
               $mar_total = getVisitorByMonth($centre_code, "3");
               $apr_total = getVisitorByMonth($centre_code, "4");
               $may_total = getVisitorByMonth($centre_code, "5");
               $jun_total = getVisitorByMonth($centre_code, "6");
               $jul_total = getVisitorByMonth($centre_code, "7");
               $aug_total = getVisitorByMonth($centre_code, "8");
               $sep_total = getVisitorByMonth($centre_code, "9");
               $oct_total = getVisitorByMonth($centre_code, "10");
               $nov_total = getVisitorByMonth($centre_code, "11");
               $dec_total = getVisitorByMonth($centre_code, "12");

               $grand_total = $jan_total + $feb_total + $mar_total + $apr_total + $may_total + $jun_total + $jul_total + $aug_total + $sep_total + $oct_total + $nov_total + $dec_total;

               if ($grand_total == 0) {
                  $conversionRateByMonth = 0;
               } else {
                  $conversionRateByMonth = round((($sum_ConvertedAllMonths / $grand_total) * 100), 2);
               }
               ?>
                <tr>
                  <td class="uk-text-bold">Total Parent Conversion by Month</td>
                  <td><?php echo getParentConversionQTYByMonth($centre_code, "1") ?></td>
                  <td><?php echo getParentConversionQTYByMonth($centre_code, "2") ?></td>
                  <td><?php echo getParentConversionQTYByMonth($centre_code, "3") ?></td>
                  <td><?php echo getParentConversionQTYByMonth($centre_code, "4") ?></td>
                  <td><?php echo getParentConversionQTYByMonth($centre_code, "5") ?></td>
                  <td><?php echo getParentConversionQTYByMonth($centre_code, "6") ?></td>
                  <td><?php echo getParentConversionQTYByMonth($centre_code, "7") ?></td>
                  <td><?php echo getParentConversionQTYByMonth($centre_code, "8") ?></td>
                  <td><?php echo getParentConversionQTYByMonth($centre_code, "9") ?></td>
                  <td><?php echo getParentConversionQTYByMonth($centre_code, "10") ?></td>
                  <td><?php echo getParentConversionQTYByMonth($centre_code, "11") ?></td>
                  <td><?php echo getParentConversionQTYByMonth($centre_code, "12") ?></td>
                  <td class="d_none"><?php echo $sum_ParentConvertedAllMonths ?></td>
                  <td class="d_none"> - </td>
                  <td> - </td>
                  <!--<td><?php //echo $sum_ConvertedAllMonths ?></td>-->
                  <!--<td><?php //echo $grand_total ?></td>-->
                  
               </tr>

               <tr>
                  <td class="uk-text-bold">Total Registered Student by Month</td>
                  <td><?php echo getConversionQTYByMonth($centre_code, "1") ?></td>
                  <td><?php echo getConversionQTYByMonth($centre_code, "2") ?></td>
                  <td><?php echo getConversionQTYByMonth($centre_code, "3") ?></td>
                  <td><?php echo getConversionQTYByMonth($centre_code, "4") ?></td>
                  <td><?php echo getConversionQTYByMonth($centre_code, "5") ?></td>
                  <td><?php echo getConversionQTYByMonth($centre_code, "6") ?></td>
                  <td><?php echo getConversionQTYByMonth($centre_code, "7") ?></td>
                  <td><?php echo getConversionQTYByMonth($centre_code, "8") ?></td>
                  <td><?php echo getConversionQTYByMonth($centre_code, "9") ?></td>
                  <td><?php echo getConversionQTYByMonth($centre_code, "10") ?></td>
                  <td><?php echo getConversionQTYByMonth($centre_code, "11") ?></td>
                  <td><?php echo getConversionQTYByMonth($centre_code, "12") ?></td>
                  <!--<td><?php //echo $sum_ParentConvertedAllMonths ?></td>-->
                  <td class="d_none"> - </td>
                  <td class="d_none"><?php echo $sum_ConvertedAllMonths ?></td>
                  <!--<td><?php //echo $grand_total ?></td>-->
                  <td> - </td>
                 
               </tr>
               <tr>
                  <td class="uk-text-bold">Total Visitors by Month</td>
                  <td><?php echo $jan_total ?></td>
                  <td><?php echo $feb_total ?></td>
                  <td><?php echo $mar_total ?></td>
                  <td><?php echo $apr_total ?></td>
                  <td><?php echo $may_total ?></td>
                  <td><?php echo $jun_total ?></td>
                  <td><?php echo $jul_total ?></td>
                  <td><?php echo $aug_total ?></td>
                  <td><?php echo $sep_total ?></td>
                  <td><?php echo $oct_total ?></td>
                  <td><?php echo $nov_total ?></td>
                  <td><?php echo $dec_total ?></td>
                  <td class="d_none"> - </td>
                  <td class="d_none"> - </td>
                  <!--<td><?php //echo $sum_ParentConvertedAllMonths ?></td>
                  <td><?php //echo $sum_ConvertedAllMonths ?></td>-->
                  <td><?php echo $grand_total ?></td>
               </tr>

              

              <!-- <tr>
                  <td colspan='15' class="uk-text-bold" style="text-align: right;">Total Registered Student</td>
                  <td>-</td>

               </tr> -->

            </table>
      <?php
         }
      } else {
         echo "Please enter a centre";
      }
      ?>
      </div>
   </div>

   <script>
      $(document).ready(function() {
         var method = '<?php echo $method ?>';
         if (method == "print") {
            window.print();
         }
      });
   </script>
   <style>
      .d_none{
         display: none;
      }
   </style>