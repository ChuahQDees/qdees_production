<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");
include_once("../search_new.php");

foreach ($_POST as $key => $value) {
   $$key = mysqli_real_escape_string($connection, $value); //$centre_code, $df, $dt, $collection_type
}

if ($method == "print") {
   include_once("../uikit1.php");
}
$year = $_SESSION['Year'];

$o_df = $df;
$o_dt = $dt;
$df = convertDate2ISO($df);
$dt = convertDate2ISO($dt);

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

if (($centre_code != "") & ($df != "") & ($dt != "")) {
?>
   <style type="text/css">
      @media print {
         #note {
            display: none;
         }
      }
   </style>

   <div class="uk-width-1-1 myheader text-center mt-5" style="text-align:center;color:white;">
      <h2 class="uk-text-center myheader-text-color myheader-text-style">DISCOUNT LISTING REPORT</h2>
      <?php
      echo "From $o_df To $o_dt<br>";
      if ($centre_code == "") {
         echo "Centre ALL";
      } else {
         echo "Centre $centre_code";
      }
      ?>
   </div>
   <div class="nice-form">
      <div class="uk-grid">
         <div class="uk-width-medium-5-10" style="width:50%">
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
         <div class="uk-width-medium-5-10" style="width:50%">
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
					         $origDate = $dt;
 
                        $date = str_replace('/', '-', $origDate );
                        $newDate = date("Y-m-d", strtotime($date));
                        $date=date_create($newDate);

                        $origDate1 = $df;
                        
                        $date1 = str_replace('/', '-', $origDate1 );
                        $newDate1 = date("Y-m-d", strtotime($date1));
                        $date1=date_create($newDate1);

                     if (isset($df) && $df != '' && isset($dt) && $dt != '') {
                        //$f_month = substr($df, 5, 2);
                        $f_month = date_format($date1,"m");
                        //$f_year = substr($df, 0, 4);
                        $f_year = date_format($date1,"Y");
                        //$t_month = substr($dt, 5, 2);
                        $t_month = date_format($date,"m");
                        //$t_year = substr($dt, 0, 4);
                        $t_year = date_format($date,"Y");
						
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
            <td>Collection Type</td>
            <td>User</td>
            <td>Collection Date Time</td>
            <td class="uk-text-right">Qty</td>
            <td class="uk-text-right">Unit Price</td>
            <td class="uk-text-right">Discount Amount</td>
         </tr>
         <?php
		// $dt =  $_POST["dt"];
        // $df =  $_POST["df"];
         
         // $base_sql = "SELECT * from collection c left join student s on s.id=c.student_id where  collection_date_time>='$df 00:00:00'
   // and collection_date_time<='$dt 23:59:59' and discount>0";
		$base_sql = "SELECT * from collection c left join student s on s.id=c.student_id where discount>0";	
         if ($centre_code != "ALL") {
            $base_sql .= " and c.centre_code='$centre_code'";
         }if ($df != "" && $dt != "") {
            $base_sql .= " and collection_date_time between  '$df' and '$dt' ";
         }
		 $base_sql .= "and start_date_at_centre <= '".$year_end_date."' and extend_year >= '$year'";
		
         $student_token = ConstructTokenGroup("s.name", "%$student%", "like", "s.student_code", "%$student%", "like", "or");
         $ct_token = ConstructToken("collection_type", $collection_type, "=");
         $order_token = ConstructOrderToken("collection_date_time", "asc");
         $final_sql = ConcatWhere($base_sql, $ct_token);
         $final_sql = ConcatWhere($final_sql, $student_token);
         $final_sql = ConcatOrder($final_sql, $order_token);
         $result = mysqli_query($connection, $final_sql);
       
         $num_row = mysqli_num_rows($result);
         if ($num_row > 0) {
            $count = 0;
            $grand_total = 0;
            while ($row = mysqli_fetch_assoc($result)) {
               $count++;
               $grand_total = $grand_total + $row["discount"];
         ?>
               <tr>
                  <td class="uk-text-right"><?php echo $count ?></td>
                  <td><?php echo $row["batch_no"] ?></td>
                  <td>
                     <?php
                     echo $row["name"] . "<br>" . $row["student_code"];
                     ?>
                  </td>
                  <td><?php //echo getCollectionType($row["collection_type"]) 
                        ?>
                     <?php
                     // switch ($row["collection_type"]) {
                        // case "tuition":
                           // echo "Tuition Fee";
                           // break;
                        // case "registration":
                           // echo $row["subject"] ? "Registration - " . $row["subject"] : "Registration";
                           // break;
                        // case "deposit":
                           // echo $row["subject"] ? "Deposit - " . $row["subject"] : "Deposit";
                           // break;
                        // case "placement":
                           // echo $row["subject"] ? "Placement - " . $row["subject"] : "Placement";
                           // break;
                        // case "product":
                           // echo "Product Sale - " . $row["product_name"];
                           // break;
                        // case "addon-product":
                           // echo "Addon Product - " . $row["product_name"];
                           // break;
                        // case "mobile":
                           // echo "Mobile Apps";
                           // break;
                        // default:
                           // echo ucwords($row["collection_type"]);
                     // }
                     ?>
<?php echo ucwords(explode("((--",$row["product_code"])[0]) ?>
                  </td>
                  <td><?php echo $row["pic"] ?></td>
                  <td><?php echo $row["collection_date_time"] ?></td>
                  <?php
                  if ($row["collection_type"] == "product") {
                  ?>
                     <td class="uk-text-right"><?php echo $row["qty"] ?></td>
                  <?php
                  } else {
                  ?>
                     <td class="uk-text-right">-</td>
                  <?php
                  }

                  if ($row["collection_type"] == "product") {
                  ?>
                     <td class="uk-text-right"><?php echo $row["unit_price"] ?></td>
                  <?php
                  } else {
                  ?>
                     <td class="uk-text-right">-</td>
                  <?php
                  }
                  ?>
                  <td class="uk-text-right"><?php echo $row["discount"] ?></td>
               </tr>
            <?php
            }
            ?>
            <tr>
               <td colspan="8" class="uk-text-right uk-text-bold">Grand Total : </td>
               <td class="uk-text-right"><?php echo number_format($grand_total, 2) ?></td>
               <td>&nbsp;</td>
            </tr>
         <?php
         } else {
            echo "<tr><td colspan='10'>No record found</td></tr>";
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