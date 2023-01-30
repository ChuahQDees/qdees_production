<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");
// include_once("../search_new.php");

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

// function getStudentName($student, $centre_code)
// {
//    global $connection;
//    $student_sql = "SELECT name from student  where centre_code='$centre_code' and name like '%$student%' or student_code like '%$student%'";
//    //echo $student_sql;
//    $result = mysqli_query($connection, $student_sql);
//    $row = mysqli_fetch_assoc($result);

//    return $row['name'];
// }
//if (($centre_code != "") & ($selected_month != "")) {
if ($centre_code != "") {
   //echo getStudentCodeFromAllocationID($row["allocation_id"], $student_name) . "";
?>

   <style type="text/css">
      @media print {
         #note {
            display: none;
         }
      }
   </style>

   <div class="uk-width-1-1 myheader text-center mt-5" style="color:white; text-align:center;">
      <h2 class="uk-text-center myheader-text-color myheader-text-style">SCHEDULE REPORT</h2>
      <?php if($date_from!="" && $date_to!=""){ ?>
      For <?php echo $date_from . ' to ' . $date_to; ?><br>
      <?php } ?>
      
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
               <!-- <tr id="note1">
                  <td colspan="2" class="uk-text-bold">NOTE: To be submitted termly with the order form</td>
               </tr> -->
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
                  <!--<td><?php// echo $years; ?></td>-->
               </tr>
               <tr>
                  <!-- <td class="uk-text-bold">School Term</td> -->
                  <!-- <td> -->
                  <?php
//                      $dt =  $_POST["dt"];
//                      $df =  $_POST["df"];
// 					$origDate = $dt;
 
// $date = str_replace('/', '-', $origDate );
// $newDate = date("Y-m-d", strtotime($date));
// $date=date_create($newDate);
// //echo date_format($date,"Y");

// $origDate1 = $df;
 
// $date1 = str_replace('/', '-', $origDate1 );
// $newDate1 = date("Y-m-d", strtotime($date1));
// $date1=date_create($newDate1);
// //echo date_format($date,"Y");
//                      if (isset($df) && $df != '' && isset($dt) && $dt != '') {
//                         //$f_month = substr($df, 5, 2);
//                         $f_month = date_format($date1,"m");
//                         //$f_year = substr($df, 0, 4);
//                         $f_year = date_format($date1,"Y");
//                         //$t_month = substr($dt, 5, 2);
//                         $t_month = date_format($date,"m");
//                         //$t_year = substr($dt, 0, 4);
//                         $t_year = date_format($date,"Y");
						
//                         //echo $df;
//                         $sql = "SELECT * from codes where 
//                         module='SCHOOL_TERM' and DATE_ADD(MAKEDATE(`year`, 1), INTERVAL (`from_month`)-1 MONTH) between '" . $f_year . "-" . $f_month . "-01' and '" . $t_year . "-" . $t_month . "-01' 
//                          or module='SCHOOL_TERM' and DATE_ADD(MAKEDATE(`year`, 1), INTERVAL (`to_month`)-1 MONTH) between '" . $f_year . "-" . $f_month . "-01' and '" . $t_year . "-" . $t_month . "-01' 
//                          or module='SCHOOL_TERM' and DATE_ADD(MAKEDATE(`year`, 1), INTERVAL (`from_month`)-1 MONTH) <= '" . $f_year . "-" . $f_month . "-01' and DATE_ADD(MAKEDATE(`year`, 1), INTERVAL (`to_month`)-1 MONTH) >= '" . $t_year . "-" . $t_month . "-01' order by category";


//                         $centre_result = mysqli_query($connection, $sql);
//                         $str = "";
//                         while ($centre_row = mysqli_fetch_assoc($centre_result)) {
                          
//                            $str .= $centre_row['category'] . ', ';
//                         }
//                         echo rtrim($str, ", ");
//                      }
                     ?>
                  <!-- </td> -->
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
            <td>Centre Name</td>
            <td>Date</td>
            <td>Time</td>
            <td>Slot</td>
         </tr>
         <?php
        
            $date_from = convertDate2ISO($date_from);
            $date_to = convertDate2ISO($date_to);

            $sql = "SELECT o.*, s.select_date, s.select_time, sc.slot_child FROM `order` o left join slot_collection_child sc on o.order_no=sc.order_no left join slot_collection s on s.id=sc.slot_master_id  where 1 and  o.slot!='' ";
            if ($centre_code !== "ALL") {
               $sql .= " and o.centre_code='$centre_code'";
            }
           
         if($date_from!="" & $date_to!=""){
            $sql .= " and  ((o.preferred_collection_date between '$date_from' and '$date_to') or (s.select_date between '$date_from' and '$date_to'))";
         }
         if($select_time!=""){
            $sql .= " and o.preferredtime = '$select_time' ";
         }
         if($slot!=""){
            $sql .= " and o.slot = '$slot' ";
         }
         $sql .= " group by o.order_no ";
         $sql .= " order by o.centre_code, o.id asc ";
     
         $result = mysqli_query($connection, $sql);
         $num_row = mysqli_num_rows($result);

         if ($num_row > 0) {
            $count = 0;
            $grand_total = 0;
            while ($row = mysqli_fetch_assoc($result)) {
               $count++;
               $centre_code = $row["centre_code"];
               ?>
                        <tr>
                           <td ><?php echo $count ?></td>
                           <td><?php echo getCentreName($centre_code) ?></td>
                           <td><?php if($row["select_date"]!=""){ echo date("d/m/Y", strtotime($row["select_date"])); } else { echo date("d/m/Y", strtotime($row["preferred_collection_date"])); } ?></td>
                           <td><?php if($row["select_time"]!=""){ echo $row["select_time"]; } else { echo $row["preferredtime"]; } ?></td>
                           <td><?php if($row["slot_child"]!=""){  echo $row["slot_child"]; } else { echo $row["slot"]; } ?></td>
                        </tr>
                  <?php   
            }
            ?>
            <!-- <tr>
               <td colspan="6" class="uk-text-right uk-text-bold">Grand Total : </td>
               <td class="uk-text-right"><?php echo number_format($grand_total, 2) ?></td>
            </tr> -->
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