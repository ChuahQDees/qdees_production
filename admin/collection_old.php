<?php
session_start();
if (($_SESSION["isLogin"]==1) & (($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) &
(hasRightGroupXOR($_SESSION["UserName"], "PointOfSalesEdit|PointOfSalesView"))) {
include_once("mysql.php");
include_once("lib/mylib/search_new.php");
include_once("admin/functions.php");
include_once("lib/pagination/pagination.php");

$ssid=$_GET["ssid"];
$year=$_SESSION['Year'];
$centre_code=$_SESSION["CentreCode"];

$p=$_GET["p"];
$level_type=$_GET["level_type"];

function getStrMonth($month) {
   switch ($month) {
      case 1 : return "Jan"; break;
      case 2 : return "Feb"; break;
      case 3 : return "Mar"; break;
      case 4 : return "Apr"; break;
      case 5 : return "May"; break;
      case 6 : return "Jun"; break;
      case 7 : return "Jul"; break;
      case 8 : return "Aug"; break;
      case 9 : return "Sep"; break;
      case 10 : return "Oct"; break;
      case 11 : return "Nov"; break;
      case 12 : return "Dec"; break;
   }
}

function getStartMonthEndMonth($group_id, &$start_month, &$end_month) {
   global $connection;

   $sql="SELECT * from `group` where id='$group_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   $start_month=date("m", strtotime($row["start_date"]));
   $end_month=date("m", strtotime($row["end_date"]));
}

function getBatchNoByAllocationID($allocation_id, $collection_type) {
   global $connection;

   $sql="SELECT batch_no from collection where allocation_id='$allocation_id' and collection_type='$collection_type'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return sha1($row["batch_no"]);
}

function getBatchNoByAllocationIDA($allocation_id, $collection_type, $month) {
   global $connection;

   $sql="SELECT batch_no from collection where allocation_id='$allocation_id' and collection_type='$collection_type' and collection_month='$month'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return sha1($row["batch_no"]);
}

function isAllDeleted($sql) {
   global $connection;

   $result=mysqli_query($connection, $sql);
   $got_at_least_one_is_active=false;
   while ($row=mysqli_fetch_assoc($result)) {
      if (!isDeleted($row["id"])) {
         $got_at_least_one_is_active=true;
      }
   }

   if ($got_at_least_one_is_active==true) {
      return false;
   } else {
      return true;
   }
}

function getStudentName($sid) {
   global $connection;

   $sql="SELECT `name` from student where sha1(id)='$sid'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["name"];
}

function isStudentDeleted($student_id) {
   global $connection;

   $sql="SELECT deleted from student where id='$student_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   if ($row["deleted"]==1) {
      return true;
   } else {
      return false;
   }
}

function isCourseDeleted($course_id) {
   global $connection;

   $sql="SELECT deleted from course where id='$course_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   if ($row["deleted"]==1) {
      return true;
   } else {
      return false;
   }
}

function isClassDeleted($class_id) {
   global $connection;

   $sql="SELECT deleted from class where id='$class_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   if ($row["deleted"]==1) {
      return false;
   } else {
      return false;
   }
}

function isDeleted($allocation_id) {
   global $connection;

   $sql="SELECT * from allocation where id='$allocation_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   if ($row["deleted"]==0) {
      $student_id=$row["student_id"];
      $course_id=$row["course_id"];
      $class_id=$row["class_id"];

      if (isStudentDeleted($student_id) || isCourseDeleted($course_id) || (isClassDeleted($class_id))) {
         return true;
      } else {
         return false;
      }
   } else {
      return true;
   }
}

function isGotProductSale($allocation_id, $collection_month) {
   global $connection, $year;

   $sql="SELECT * from collection where allocation_id='$allocation_id' and collection_type='product'
   and `year`='$year' and month(collection_date_time)='$collection_month'";
//   echo $sql."<br>";
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      return true;
   } else {
      return false;
   }
}

function getBatchNo($allocation_id, $collection_month) {
   global $connection;

   $sql="SELECT * from collection where allocation_id='$allocation_id' and month(collection_date_time)='$collection_month'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return sha1($row["batch_no"]);
}

function getCourseFee($course_id, &$course_fee, &$registration, &$deposit, &$placement) {
   global $connection;

   $sql="SELECT * from course where id='$course_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   $course_fee=$row["fees"];
   $deposit=$row["deposit"];
   $placement=$row["placement"];

   $sql="SELECT registration_fee from centre where centre_code='".$_SESSION["CentreCode"]."'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   $registration=$row["registration_fee"];
}

function getFees($allocation_id, $collection_month, $collection_type) {
   global $connection, $year;

   $sql="SELECT sum(amount) as amount from collection where allocation_id='$allocation_id' and collection_type='$collection_type'
   and `year`='$year' and collection_month='$collection_month' group by allocation_id";

   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);
   $row=mysqli_fetch_assoc($result);

   if ($num_row>0) {
      return $row["amount"];
   } else {
      return "0";
   }
}

function getExtraFees($allocation_id, $collection_type) {
   global $connection, $year;

   if ($collection_type!="registration") {
      $sql="SELECT sum(amount) as amount from collection where allocation_id='$allocation_id' and collection_type='$collection_type'
      and `year`='$year' group by allocation_id";
   } else {
      $sql="SELECT s.student_code from student s, allocation a where a.student_id=s.id and a.id='$allocation_id'";
      $result=mysqli_query($connection, $sql);
      $row=mysqli_fetch_assoc($result);
      $student_code=$row["student_code"];

      $sql="SELECT sum(amount) as amount from collection where collection_type='$collection_type'
      and `year`='$year' and student_code='$student_code'";
   }

   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);
   $row=mysqli_fetch_assoc($result);

   if ($num_row>0) {
      return $row["amount"];
   } else {
      return "0";
   }
}
?>

<script>
function dlgProductSale(allocation_id, collection_month, batch_no, ssid) {
   $.ajax({
      url : "admin/dlgProductSale.php",
      type : "POST",
      data : "allocation_id="+allocation_id+"&collection_month="+collection_month+"&batch_no="+batch_no+"&ssid="+ssid,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         if (response!="") {
            $("#dlgProductSale").html(response);
            $("#dlgProductSale").dialog({
               dialogClass:"no-close",
               title:"Product Sale",
               modal:true,
               height:'auto',
               width:'auto',
            });
         }
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}

<?php
if (hasRightGroupXOR($_SESSION["UserName"], "PointOfSalesEdit")) {
?>
function dlgPayFees(student_id) {
   $.ajax({
      url : "admin/dlg_pay_fees.php",
      type : "POST",
      data : "ssid="+student_id,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         $("#dlgPayFees").html(response);
         $("#dlgPayFees").dialog({
            title:"Pay Fees/Buy Product",
            dialogClass:"no-close",
            modal:true,
            height:'auto',
            width:'1000',
         });
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}
<?php
}
?>
</script>

<div id="thetop" class="uk-margin-right uk-margin-top">
   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color">Point of Sales - <?php echo getStudentName($_GET["ssid"])?></h2>
   </div>

   <form class="uk-form" name="frmSearch" id="frmSearch" method="get">
      <input type="hidden" name="p" id="p" value="<?php echo $p?>">
   </form>
   <br>
   <a class="uk-button uk-button-small" href="index.php?p=collection_pg1"><< Back</a>
<?php
if (hasRightGroupXOR($_SESSION["UserName"], "PointOfSalesEdit")) {
?>
   <a class="uk-button uk-button-small" onclick="dlgPayFees('<?php echo $ssid?>')">Pay Fees</a>
<?php
}
?>
<?php
if (hasRightGroupXOR($_SESSION["UserName"], "PointOfSalesEdit|PointOfSalesView")) {
?>
   <a class="uk-button uk-button-small" onclick="dlgProductSale('<?php echo $row['id']?>', 'T1', '<?php echo getBatchNo($row['id'], 'T1');?>', '<?php echo $ssid?>');">View Product Bought</a>
<?php
}
?>
<br><br>
<?php
$sql="SELECT a.student_id, s.student_code, a.id, a.course_id, a.class_id, s.name, co.course_name, a.class_id, a.group_id
from student s, course co, allocation a where a.student_id=s.id and a.course_id=co.id and a.year='$year'
and s.student_status='A' and s.centre_code='$centre_code' and sha1(a.student_id)='$ssid'
and co.course_name LIKE '%IE%' AND (not co.course_name LIKE '%BIE%') order by co.course_name asc";
//echo $sql;
$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);
if (($num_row>0) & (!isAllDeleted($sql))) {
?>
   <div class="uk-overflow-container">
   <table id="the_table" class="uk-table uk-table-small uk-table-hover">
      <tr class="uk-text-small uk-text-bold">
         <td data-uk-tooltip="{pos:top}" title="Student Information">Student<br>Programme</td>
         <td class="uk-text-center" data-uk-tooltip title="Registration Fee">Registration</td>
         <td class="uk-text-center" data-uk-tooltip title="Deposit">Deposit</td>
         <td class="uk-text-center" data-uk-tooltip title="Placement Fee">Placement</td>
         <td class="uk-text-center">Term 1</td>
         <td class="uk-text-center">Term 2</td>
         <td class="uk-text-center">Term 3</td>
         <td class="uk-text-center">Term 4</td>
         <td class="uk-text-center">Term 5</td>
         <td class="uk-text-center"></td>
      </tr>
<?php
      while ($row=mysqli_fetch_assoc($result)) {
         $the_batch_no=sha1($row["batch_no"]);
         if (!isDeleted($row["id"])) {
?>
      <tr class="uk-text-small">
         <td>
            <span data-uk-tooltip="{pos:top}" title="<?php echo $row["student_code"]?>"><?php echo $row["name"]?></span><br>
            <?php echo $row["course_name"]." (".$row["class_id"].")"?>
         </td>
         <td class='uk-text-center'>
<?php
$fee=getExtraFees($row["id"], "registration");
if ($fee>0) {
?>
            <a href="admin/print_receipt.php?batch_no=<?php echo getBatchNoByAllocationID($row['id'], 'registration')?>" target="_blank">
<?php
}
?>
               <?php echo number_format($fee, 0)?>
<?php
if ($fee>0) {
?>
            </a>
<?php
}
?>
            <br>
         </td>
         <td class='uk-text-center'>
<?php
$fee=getExtraFees($row["id"], "deposit");
if ($fee>0) {
?>
            <a href="admin/print_receipt.php?batch_no=<?php echo getBatchNoByAllocationID($row['id'], 'deposit')?>" target="_blank">
<?php
}
?>
               <?php echo number_format($fee, 0)?>
<?php
if ($fee>0) {
?>
            </a>
<?php
}
?>
            <br>
         </td>
         <td class='uk-text-center'>
<?php
$fee=getExtraFees($row["id"], "placement");
if ($fee>0) {
?>
            <a href="admin/print_receipt.php?batch_no=<?php echo getBatchNoByAllocationID($row['id'], 'placement')?>" target="_blank">
<?php
}
?>
               <?php echo number_format($fee, 0)?>
<?php
if ($fee>0) {
?>
            </a>
<?php
}
?>
            <br>
         </td>

<?php
for ($i=1; $i<=5; $i++) {
?>
         <td class="uk-text-center">
 <?php
$fee=getFees($row["id"], "T".$i, "tuition");
if ($fee>0) {
?>
            <a href="admin/print_receipt.php?batch_no=<?php echo getBatchNoByAllocationIDA($row['id'], 'tuition', 'T'.$i)?>" target="_blank">
<?php
}
?>
               <?php echo number_format($fee, 0)?>
<?php
if ($fee>0) {
?>
            </a>
<?php
}
?>
         </td>
<?php
}
?>
      </tr>
<?php
      }
      }
?>
   </table>
   </div>
<?php
}

$sql="SELECT a.student_id, s.student_code, a.id, a.course_id, a.class_id, s.name, co.course_name, a.class_id, a.group_id
from student s, course co, allocation a where a.student_id=s.id and a.course_id=co.id and a.year='$year'
and s.student_status='A' and s.centre_code='$centre_code' and sha1(a.student_id)='$ssid'
and (co.course_name LIKE '%BIEP%' or co.course_name LIKE '%BIMP%') order by co.course_name asc";

$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);
if (($num_row>0) & (!isAllDeleted($sql))) {
   while ($row=mysqli_fetch_assoc($result)) {
      if (!isDeleted($row["id"])) {
         getStartMonthEndMonth($row["group_id"], $start_month, $end_month);
         getCourseFee($row["course_id"], $course_fee, $registration, $deposit, $placement);
?>
   <div class="uk-overflow-container">
   <table id="the_table" class="uk-table uk-table-small uk-table-hover">
      <tr class="uk-text-small uk-text-bold">
         <td data-uk-tooltip="{pos:top}" title="Student Information">Student<br>Programme</td>
         <td class="uk-text-center" data-uk-tooltip title="Registration Fee">Registration</td>
         <td class="uk-text-center" data-uk-tooltip title="Deposit">Deposit</td>
         <td class="uk-text-center" data-uk-tooltip title="Placement Fee">Placement</td>
<?php
for ($j=1; $j<=12; $j++) {
?>
<?php
if (($j>=$start_month) & ($j<=$end_month)) {
?>
         <td class="uk-text-center"><?php echo getStrMonth($j);?></td>
<?php
}
?>
<?php
}
?>
         <td class="uk-text-center"></td>
      </tr>
      <tr class="uk-text-small">
         <td>
            <span data-uk-tooltip="{pos:top}" title="<?php echo $row["student_code"]?>"><?php echo $row["name"]?></span><br>
            <?php echo $row["course_name"]." (".$row["class_id"].")"?>
         </td>
         <td class='uk-text-center'>
<?php
$paid_fee=getExtraFees($row["id"], "registration");
$owe_fee=$registration-$paid_fee;
if ($fee>0) {
?>
            <a href="admin/print_receipt.php?batch_no=<?php echo getBatchNoByAllocationID($row['id'], 'registration')?>" target="_blank"><?php echo number_format($owe_fee, 0)?></a>
<?php
} else {
?>
               <?php echo number_format($owe_fee, 0)?>
<?php
}
?>
            <br>
         </td>
         <td class='uk-text-center'>
<?php
$paid_fee=getExtraFees($row["id"], "deposit");
$owe_fee=$deposit-$paid_fee;
if ($fee>0) {
?>
            <a href="admin/print_receipt.php?batch_no=<?php echo getBatchNoByAllocationID($row['id'], 'deposit')?>" target="_blank"><?php echo number_format($owe_fee, 0)?></a>
<?php
} else {
?>
               <?php echo number_format($owe_fee, 0)?>
<?php
}
?>
            <br>
         </td>
         <td class='uk-text-center'>
<?php
$paid_fee=getExtraFees($row["id"], "placement");
$owe_fee=$placement-$paid_fee;
if ($fee>0) {
?>
            <a href="admin/print_receipt.php?batch_no=<?php echo getBatchNoByAllocationID($row['id'], 'placement')?>" target="_blank"><?php echo number_format($owe_fee, 0)?></a>
<?php
} else {
?>
               <?php echo number_format($owe_fee, 0)?>
<?php
}
?>
            <br>
         </td>

<?php
for ($i=1; $i<=12; $i++) {
   if (($i>=$start_month) & ($i<=$end_month)) {
?>
         <td class="uk-text-center">
<?php
$paid_fee=getFees($row["id"], $i, "tuition");
$owe_fee=$course_fee-$paid_fee;
if ($fee>0) {

?>
            <a href="admin/print_receipt.php?batch_no=<?php echo getBatchNoByAllocationIDA($row['id'], 'tuition', $i)?>" target="_blank"><?php echo number_format($owe_fee, 0)?></a>
<?php
} else {
?>
               <?php echo number_format($owe_fee, 0)?>
<?php
}
?>
         </td>
<?php
   }
}
?>
         <td class="uk-text-center">
<?php
if (isDeleted($row["id"])) {
   echo "Deleted";
}
?>
         </td>
      </tr>
<?php
   }
   }
?>
   </table>
   </div>
<div class="uk-margin-top">
<?php
$sql="SELECT distinct c.batch_no from collection c, allocation a where sha1(a.student_id)='$ssid'
and c.centre_code='".$_SESSION["CentreCode"]."' and year(c.collection_date_time)='".$_SESSION['Year']."'
and c.void=0 and c.allocation_id=a.id";
//echo $sql;
$result=mysqli_query($connection, $sql);
$receipts="Receipts : ";
while ($row=mysqli_fetch_assoc($result)) {
   $batch_no=sha1($row["batch_no"]);
   $receipts.="<a href='admin/print_receipt.php?batch_no=$batch_no' target='_blank'>".$row["batch_no"]."</a>, ";
}
$receipts=substr($receipts, 0, -2);
echo $receipts;
?>
</div>
<?php
}
?>
</div>
<div id="dlgPayFees"></div>
<div id="dlgProductSale"></div>
<?php
} else {
   echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
}
?>