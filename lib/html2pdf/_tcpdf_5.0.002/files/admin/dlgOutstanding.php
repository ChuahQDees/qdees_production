<?php
session_start();
include_once("../mysql.php");
$student_code=$_POST["student_code"];

//echo $student_code."<br>";
//echo sha1("MYQWEC1C10001-0031")."<br>";
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

function canChangeFee() {
   global $connection;
   $centre_code=$_SESSION["CentreCode"];

   $sql="SELECT can_adjust_fee from centre where centre_code='$centre_code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   if ($row["can_adjust_fee"]==1) {
      return true;
   } else {
      return false;
   }
}

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

function getCourseIDByAllocationID($allocation_id) {
   global $connection;

   $sql="SELECT course_id from allocation where id='$allocation_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["course_id"];
}

function feePaid($student_id, $allocation_id, $group_id, $collection_month, &$owe_amount) {
   global $connection, $centre_code, $year;

   $sql="SELECT sum(c.amount) as amount from collection c, allocation a where c.allocation_id=a.id and a.year='$year'
   and a.student_id='$student_id' and a.id='$allocation_id' and c.collection_month='$collection_month' and a.group_id='$group_id'
   and c.void=0 and c.centre_code='$centre_code' and c.collection_type='tuition'";

//   writeLog($sql);

   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   $paid_amount=$row["amount"];
   $course_fee=getCourseFee(getCourseIDByAllocationID($allocation_id));

   $owe_amount=$course_fee-$paid_amount;

   if ($owe_amount<=0) {
      return true;
   } else {
      return false;
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

function getCourseFee($course_id) {
   global $connection;

   $sql="SELECT fees from course where id='$course_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return $row["fees"];
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


function getssid($student_code) {
   global $connection;

   $sql="SELECT id from student where (student_code)='$student_code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["id"];
}

?>
<script>
function doMonthChange(allocation_id) {
   if ($("#month"+allocation_id).val()!="") {
      var fee=$("#fee"+allocation_id).val();
      $("#the_fee"+allocation_id).val(fee);
   } else {
      $("#the_fee"+allocation_id).val("");
   }
}
</script>
<?php
$ssid=(getssid($student_code));
$centre_code=$_SESSION["CentreCode"];
$year=$_SESSION["Year"];
?>
      <form class="uk-form uk-form-small" name="frmOutstanding" id="frmOutstanding" method="post" action="admin/saveOutstanding.php">
         <input type="hidden" name="reg_fee" id="reg_fee" value="">
<?php
$sql="SELECT a.student_id, s.student_code, a.id, a.course_id, a.class_id, s.name, co.course_name, a.class_id, a.group_id
      from student s, course co, allocation a where a.student_id=s.id and a.course_id=co.id and a.year='$year'
      and s.student_status='A' and s.centre_code='$centre_code' and (a.student_id)='$ssid'
      and co.course_name LIKE '%IE%' AND (not co.course_name LIKE '%BIE%') order by co.course_name asc";
//echo $sql."<br>";
$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);

if ($num_row>0) {
//$registration=getRegistrationFee();
while ($row=mysqli_fetch_assoc($result)) {
   $fees=getCourseFee($row["course_id"]);
//   getCourseExtraFee($row["course_id"], $deposit, $placement);
   if (!isDeleted($row["id"])) {
?>
         <table class="uk-table uk-table-small uk-form uk-form-small">
            <tr style="background-color:lightgrey">
               <td colspan="5"><?php echo $row["course_name"]?>
                  <input type="hidden" name="ssid" id="ssid" value="<?php echo $ssid?>">
                  <input type="hidden" name="student_code" id="student_code" value="<?php echo $student_code?>">
                  <input type="hidden" name="allocation_id[]" value="<?php echo $row["id"]?>">
                  <input type="hidden" name="fee<?php echo $row["id"]?>" id="fee<?php echo $row["id"]?>" value="<?php echo $fees?>">
                  <input type="hidden" name="course_type<?php echo $row['id']?>" id ="course_type<?php echo $row['id']?>" value="IE">
               </td>
            </tr>
            <tr class="uk-text-small">
               <td>
                  <select name="term<?php echo $row['id']?>" id="term<?php echo $row['id']?>" onchange="doTermChange('<?php echo $row["id"]?>')">
                     <option value="">Select</option>
                     <option value="t1">Term 1</option>
                     <option value="t2">Term 2</option>
                     <option value="t3">Term 3</option>
                     <option value="t4">Term 4</option>
                     <option value="t5">Term 5</option>
                  </select>
                  <input type="number" placeholder="Fee" step="0.01" name="the_fee<?php echo $row['id']?>" id="the_fee<?php echo $row['id']?>" value="" onblur="onFeeLostFocus('<?php echo $row['id']?>')">
               </td>
            </tr>
         </table>
<?php
   }
}
}
$sql="SELECT a.student_id, s.student_code, a.id, a.course_id, a.group_id, a.class_id, s.name, co.course_name, a.class_id
      from student s, course co, allocation a where a.student_id=s.id and a.course_id=co.id and a.year='$year'
      and s.student_status='A' and s.centre_code='$centre_code' and (a.student_id)='$ssid'
      and (co.course_name LIKE '%BIEP%' or co.course_name LIKE '%BIMP%') order by co.course_name asc";
//echo $sql."<br>";

$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);
if ($num_row>0) {
//$registration=getRegistrationFee();
while ($row=mysqli_fetch_assoc($result)) {
   getStartMonthEndMonth($row["group_id"], $start_month, $end_month);
   $fees=getCourseFee($row["course_id"]);
//   getCourseExtraFee($row["course_id"], $deposit, $placement);
   if (!isDeleted($row["id"])) {
?>
         <table class="uk-table uk-table-small uk-form uk-form-small">
            <tr style="background-color:lightgrey">
               <td colspan="12"><?php echo $row["course_name"]?>
                  <input type="hidden" name="allocation_id[]" value="<?php echo $row["id"]?>">
                  <input type="hidden" name="ssid" id="ssid" value="<?php echo $ssid?>">
                  <input type="hidden" name="student_code" id="student_code" value="<?php echo $student_code?>">
                  <input type="hidden" name="fee<?php echo $row["id"]?>" id="fee<?php echo $row["id"]?>" value="<?php echo $fees?>">
                  <input type="hidden" name="course_type<?php echo $row['id']?>" id ="course_type<?php echo $row['id']?>" value="BIEP">
               </td>
            </tr>
            <tr class="uk-text-small">
               <td>
                  <select name="month<?php echo $row['id']?>" id="month<?php echo $row['id']?>" onchange="doMonthChange('<?php echo $row["id"]?>')">
                     <option value="">Select</option>
<?php
for ($i=1; $i<=12; $i++) {
   if (($i>=$start_month) & ($i<=$end_month)) {
      if (!feePaid($row["student_id"], $row["id"], $row["group_id"], $i, $owe_amount)) {
?>
                     <option value="<?php echo $i?>"><?php echo getStrMonth($i)?> -- <?php echo $owe_amount?></option>
<?php
      } else {
?>
                     <option value="" disabled><?php echo getStrMonth($i)?> -- Paid</option>
<?php
      }
   }
}
?>

                  </select>
<?php
if (canChangeFee()) {
?>
                  <input type="number" placeholder="Fee" step="0.01" name="the_fee<?php echo $row['id']?>" id="the_fee<?php echo $row['id']?>" value="">
<?php
} else {
?>
                  <input type="number" placeholder="Fee" step="0.01" name="the_fee<?php echo $row['id']?>" id="the_fee<?php echo $row['id']?>" value="" readonly>
<?php
}
?>
               </td>
            </tr>
         </table>
      </form>
<?php
   }
}
}
?>
<script>
function doSave() {
   $("#frmOutstanding").submit();
}
</script>

<a onclick="doSave()" class="uk-button uk-button-small">Put in Basket</a>
<a onclick="$('#outstanding-dialog').dialog('close');" class="uk-button uk-button-small">Cancel</a>
