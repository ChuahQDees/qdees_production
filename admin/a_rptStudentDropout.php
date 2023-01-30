<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");
include_once("../search_new.php");

foreach ($_POST as $key => $value) {
   $$key = mysqli_real_escape_string($connection, $value); //$centre_code, $df, $dt
}

if ($method == "print") {
   include_once("../uikit1.php");
}

// $df=convertDate2ISO($df);
// $dt=convertDate2ISO($dt);

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

function getCompanyName($centre_code)
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

function getPrimaryContact($student_code)
{
   global $connection;

   $sql = "SELECT full_name, email, mobile, mobile_country_code from student_emergency_contacts where student_code='$student_code' ORDER BY id ASC LIMIT 1";
   $result = mysqli_query($connection, $sql);

   if ($result) {
      $row = mysqli_fetch_assoc($result);
      return $row;
   } else {
      return array('full_name' => '', 'email' => '', 'mobile' => '', 'mobile_country_code' => '');
   }
}

if ($centre_code != "") {
?>
<style type="text/css">
      @page {
         size: auto;
      }
   </style>
<div class="uk-width-1-1 myheader text-center mt-5">
   <h2 class="uk-text-center myheader-text-color myheader-text-style">STUDENT DROPOUT REPORT</h2>
</div>
<div class="nice-form">
<div class="uk-grid">
   <div class="uk-width-medium-5-10">
      <table class="uk-table">
         <tr>
            <td class="uk-text-bold">Centre Name</td>
            <td>
               <?php echo getCentreName($centre_code)?>
            </td>
         </tr>
         <tr>
            <td class="uk-text-bold">Prepare By</td>
            <td><?php echo $_SESSION["UserName"]?></td>
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
            <td><?php echo $_SESSION['Year']?></td>
         </tr>
         <tr>
            <td class="uk-text-bold">School Term</td>
            <td>
				 <?php
                 $dt =  $_POST["dt"];
                 $df =  $_POST["df"];

                 $f_month = date("m");
                 $f_year = $_SESSION["Year"];
                 $t_month = date("m");
                 $t_year = $_SESSION["Year"];
                   
                     if (isset($df) && $df != '' && isset($dt) && $dt != '') {
                        $f_month = substr($df, 5, 2);
                        $f_year = substr($df, 0, 4);
                        $t_month = substr($dt, 5, 2);
                        $t_year = substr($dt, 0, 4);
                     }
                       
                     //$sql = "SELECT * from codes where module='SCHOOL_TERM' and year>=".$f_year." and year<=".$t_year." and from_month<=$f_month and to_month>=$t_month";
                     $sql = "SELECT * from codes where 
                     module='SCHOOL_TERM' and DATE_ADD(MAKEDATE(`year`, 1), INTERVAL (`from_month`)-1 MONTH) between '".$f_year."-".$f_month."-01' and '".$t_year."-".$t_month."-01' 
                     or module='SCHOOL_TERM' and DATE_ADD(MAKEDATE(`year`, 1), INTERVAL (`to_month`)-1 MONTH) between '".$f_year."-".$f_month."-01' and '".$t_year."-".$t_month."-01'
                     or module='SCHOOL_TERM' and DATE_ADD(MAKEDATE(`year`, 1), INTERVAL (`from_month`)-1 MONTH) <= '" . $f_year . "-" . $f_month . "-01' and DATE_ADD(MAKEDATE(`year`, 1), INTERVAL (`to_month`)-1 MONTH) >= '" . $t_year . "-" . $t_month . "-01'  order by category";
                     //Print_r($sql);
					// $sql = "SELECT * from codes where module='SCHOOL_TERM' order by category";
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
      </table>
   </div>
</div>
<div class="uk-grid">
   <div class="uk-width-1-2">
      <h4 class="uk-text-bold">Dropout Date From : <?php echo $_POST["df"]?></h4>
   </div>
   <div class="uk-width-1-2">
      <h4 class="uk-text-bold">Dropout Date To : <?php echo $_POST["dt"]?></h4>
   </div>
</div>
<style>
.uk_t{
	font-size:10px;
}
@media print{@page {size: landscape}}
</style>
 <div style="width:100%;overflow-x:auto">
<table class="uk-table uk_t">
   <tr class="uk-text-bold">
      <td>No.</td>
      <td>Student's Name</td>
      <td>Parent's Name</td>
      <td>Contact No.</td>
      <td>Email</td>
<?php
$reason_sql="SELECT * from codes where module='DROPOUTREASON' order by id";
$reason_result=mysqli_query($connection, $reason_sql);
while ($reason_row=mysqli_fetch_assoc($reason_result)) {
?>
      <td><?php echo $reason_row["code"]?></td>
<?php
}
?>
      <td>Remarks</td>
   </tr>
		<?php
			$year = $_SESSION['Year'];
			$df = $_POST["df"];
			$dt = $_POST["dt"];
			$df = convertDate2ISO($df);
			$dt = convertDate2ISO($dt);
			$student_name = $_POST["student_name"];
			$dropout_reason = $_POST["dropout_reason"];

			if (($centre_code != "ALL") & ($centre_code != "")) {
			   // $base_sql="SELECT d.*, s.name, d.remark from dropout d, student s where d.centre_code='$centre_code' and s.student_code=d.student_code and s.student_status= 'D' and year(s.start_date_at_centre) <= '$year' and extend_year >= '$year'";
			   $base_sql = "SELECT d.*, s.name, d.remarks from dropout d, student s where d.centre_code='$centre_code' and s.student_code=d.student_code and s.student_status= 'I' and s.start_date_at_centre <= '".$year_end_date."' and extend_year >= '$year'";
			} else {
			   if ($centre_code != "") {
				  //$base_sql="SELECT d.*, s.name, d.remark from dropout d, student s where s.student_code=d.student_code and s.student_status= 'D' and year(s.start_date_at_centre) <= '$year' and extend_year >= '$year'";
				  $base_sql = "SELECT d.*, s.name, d.remarks from dropout d, student s where s.student_code=d.student_code and s.student_status= 'I' and s.start_date_at_centre <= '".$year_end_date."' and extend_year >= '$year'";
			   }
			}
			if ($df != "") {
               $base_sql = $base_sql . " and d.dropout_date >= '$df' ";
            }
            if ($dt != "") {
               $base_sql = $base_sql . " and d.dropout_date <= '$dt' ";
            }
            if ($student_name != "") {
               $base_sql = $base_sql . " and ( s.student_code like '%$student_name%' or `name` like '%$student_name%')";
            }
            if ($dropout_reason != "") {
               $base_sql = $base_sql . " and ( d.reason like '%$dropout_reason%')";
            }

           // echo $base_sql; die;
            $date_token = ConstructToken("dropout_date", $dt, "<=", "and");
            $date_token1 = ConstructToken("dropout_date", $df, ">=", "and");

            // $student_nametoken = ConstructToken("s.name", $student_name, "=", "and");
            $final_sql = ConcatWhere($base_sql, $date_token, "and");
            $final_sql1 = ConcatWhere($final_sql, $date_token1, "and");
            $final_sql2 = ConcatWhere($final_sql1, $student_nametoken, "and");

            if ($student_name == 'undefined') {
               $result = mysqli_query($connection, $final_sql1);
            } else {
               $result = mysqli_query($connection, $final_sql2);
            }

            // var_dump($final_sql2);
            $num_row = mysqli_num_rows($result);
if ($num_row>0) {
   $count=0;
   while ($row=mysqli_fetch_assoc($result)) {
      $count++;
?>
   <tr>
      <td><?php echo $count?></td>
      <td>
         <?php echo $row['name']?><br>
         <?php echo $row["student_code"]?>
      </td>
      <?php $primary_contact = getPrimaryContact($row['student_code']); ?>
      <td><?php echo $primary_contact["full_name"]?></td>
      <td><?php echo $primary_contact["mobile_country_code"].$primary_contact["mobile"]?></td>
      <td><?php echo $primary_contact["email"]?></td>
<?php
$reason_sql="SELECT * from codes where module='DROPOUTREASON' order by id";
$reason_result=mysqli_query($connection, $reason_sql);
while ($reason_row=mysqli_fetch_assoc($reason_result)) {
   if ($reason_row["code"]==$row["reason"]) {
      if ($method=="screen") {
?>
      <td><div class="fa fa-check"></div></td>
<?php
      } else {
?>
      <td><img src="../images/tick.png"></td>
<?php
      }
   } else {
?>
      <td></td>
<?php
   }
}
?>
      <td><?php echo $row['remarks'] ?></td>
   </tr>
<?php
   }
}
?>
</table>
<?php
} else {
   echo "Please enter a centre";
}
?>
</div>
</div>
<script>
$(document).ready(function () {
   var method='<?php echo $method?>';
   if (method=="print") {
      window.print();
   }
});
</script>
