<?php
session_start();
$centre_code=$_SESSION["CentreCode"];
$year=$_SESSION["Year"];
include_once("../mysql.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //sha1(student_code)
}

$ssid=sha1(getStudentID($student_code));

function getRegistrationFee($centre_code) {
   global $connection;

   $sql="SELECT registration_fee from centre where centre_code='$centre_code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["registration_fee"];
}

function getDepositFee($course_id) {
   global $connection;

   $sql="SELECT * from course where id='$course_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["deposit"];
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

function extraFeePaid($student_id, $allocation_id, $group_id, $extra_fee_type) {
   global $connection, $centre_code, $year;

   $sql="SELECT c.* from collection c, allocation a where c.allocation_id=a.id and a.year='$year' and a.student_id='$student_id'
   and c.collection_type='$extra_fee_type' and c.void=0 and c.centre_code='$centre_code' and a.id='$allocation_id'
   and a.group_id='$group_id'";

   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      return true;
   } else {
      return false;
   }
}

function getCourseFee($course_id) {
   global $connection;

   $sql="SELECT fees from course where id='$course_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return $row["fees"];
}

function getCourseExtraFee($course_id, &$deposit, &$placement) {
   global $connection;

   $sql="SELECT deposit, placement from course where id='$course_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   $deposit=$row["deposit"];
   $placement=$row["placement"];
}

function getStudentID($sha1_student_code) {
   global $connection;

   $sql="SELECT * from student where sha1(student_code)='$sha1_student_code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["id"];
}

function registrationPaid($student_id) {
   global $connection, $centre_code, $year;

   $sql="SELECT c.* from collection c where c.year='$year' and sha1(c.student_code)='$student_id'
   and c.collection_type='registration' and c.void=0 and c.centre_code='$centre_code'";
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      return true;
   } else {
      return false;
   }
}
?>
<script>
function copyRegistration() {
   var registration=$("#the_registration").val();
   $("#registration_fee").val(registration);
}

function copyDeposit(allocation_id) {
   var deposit=$("#the_deposit"+allocation_id).val();
   $("#deposit_fee"+allocation_id).val(deposit);
}

function doSave() {
   $("#frmInitial").submit();
}
</script>
<div>
   <form name="frmInitial" id="frmInitial" method="post" action="admin/saveInitial.php">
   <input type="hidden" name="the_registration" id="the_registration" value="<?php echo getRegistrationFee($_SESSION['CentreCode'])?>">
   <table class="uk-table uk-table-small">
      <tr>
         <td>
            <label class="uk-text-small">Registration</label><br>
            <div class="uk-form">
<?php
if (!registrationPaid($student_code)) {
?>
               <input class="uk-form-small" name="registration_fee" id="registration_fee" type="text" placeholder="Registration Fee" value="">
               <a onclick="copyRegistration()" class="uk-button uk-button-small">Default</a>
<?php
} else {
?>
               <div class="uk-text-bold uk-text-success">Registration Paid</div>
<?php
}
?>
            </div>
         </td>
         <td>
            <div class="uk-form">
               <label class="uk-text-small">Mobile App Fee</label><br>
               <input class="uk-form-small" name="mobile" id="mobile" type="text" placeholder="Mobile App Fee" value="">
            </div>
         </td>
      </tr>
   </table>
<?php
$sql="SELECT a.student_id, s.student_code, a.id, a.course_id, a.class_id, s.name, co.course_name, a.class_id, a.group_id
      from student s, course co, allocation a where a.student_id=s.id and a.course_id=co.id and a.year='$year'
      and s.student_status='A' and s.centre_code='$centre_code' and sha1(a.student_id)='$ssid'
      and co.course_name LIKE '%IE%' AND (not co.course_name LIKE '%BIE%') order by co.course_name asc";
$result=mysqli_query($connection, $sql);
?>
   <table class="uk-table uk-table-small uk-form uk-form-small">
      <tr>
<?php
while ($row=mysqli_fetch_assoc($result)) {
   if (!isDeleted($row["id"])) {

?>
         <td>
            <label class="uk-text-small">Deposit</label><br>
<?php
if (!extraFeePaid($row["student_id"], $row["id"], $row["group_id"], 'deposit')) {
?>
            <input type="hidden" name="ssid" id="ssid" value="<?php echo $ssid?>">
            <input type="hidden" name="student_code" id="student_code" value="<?php echo $student_code?>">
            <input type="hidden" name="allocation_id[]" value="<?php echo $row["id"]?>">
            <input type="hidden" name="fee<?php echo $row["id"]?>" id="fee<?php echo $row["id"]?>" value="<?php echo $fees?>">
            <input type="hidden" name="the_deposit<?php echo $row['id']?>" id="the_deposit<?php echo $row['id']?>" value="<?php echo getDepositFee($row['id'])?>">
            <input type="hidden" name="course_type<?php echo $row['id']?>" id ="course_type<?php echo $row['id']?>" value="IE">

            <input class="uk-form-small" name="deposit_fee<?php echo $row["id"]?>" id="deposit_fee<?php echo $row["id"]?>" type="text" placeholder="Deposit" value="">
            <a onclick="copyDeposit('<?php echo $row['id']?>')" class="uk-button uk-button-small">Default</a>
<?php
} else {
?>
            <div class="uk-text-bold uk-text-success">Deposit Paid</div>
<?php
}
?>
         </td>
<?php
   }
}
?>
      </tr>
   </table>
<?php
$sql="SELECT a.student_id, s.student_code, a.id, a.course_id, a.group_id, a.class_id, s.name, co.course_name, a.class_id, a.group_id
      from student s, course co, allocation a where a.student_id=s.id and a.course_id=co.id and a.year='$year'
      and s.student_status='A' and s.centre_code='$centre_code' and sha1(a.student_id)='$ssid'
      and (co.course_name LIKE '%BIEP%' or co.course_name LIKE '%BIMP%') order by co.course_name asc";
$result=mysqli_query($connection, $sql);
?>

<?php
while ($row=mysqli_fetch_assoc($result)) {
   $fees=getCourseFee($row["course_id"]);
   if (!isDeleted($row["id"])) {
?>
   <table class="uk-table uk-table-small uk-form uk-form-small">
      <tr style="background-color:lightgrey">
         <td colspan="5"><?php echo $row["course_name"]?>
            <input type="hidden" name="ssid" id="ssid" value="<?php echo $ssid?>">
            <input type="hidden" name="student_code" id="student_code" value="<?php echo $student_code?>">
            <input type="hidden" name="allocation_id[]" value="<?php echo $row["id"]?>">
            <input type="hidden" name="fee<?php echo $row["id"]?>" id="fee<?php echo $row["id"]?>" value="<?php echo $fees?>">
            <input type="hidden" name="the_deposit<?php echo $row['id']?>" id="the_deposit<?php echo $row['id']?>" value="<?php echo getDepositFee($row['id'])?>">
            <input type="hidden" name="course_type<?php echo $row['id']?>" id ="course_type<?php echo $row['id']?>" value="IE">
         </td>
      </tr>
      <tr>
         <td>
            <label class="uk-text-small">Deposit</label><br>
<?php
if (!extraFeePaid($row["student_id"], $row["id"], $row["group_id"], 'deposit')) {
?>
            <input class="uk-form-small" name="deposit_fee<?php echo $row["id"]?>" id="deposit_fee<?php echo $row["id"]?>" type="text" placeholder="Deposit" value="">
            <a onclick="copyDeposit('<?php echo $row['id']?>')" class="uk-button uk-button-small">Default</a>
<?php
} else {
?>
            <div class="uk-text-bold uk-text-success">Deposit Paid</div>
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
?>
<a onclick="doSave()" class="uk-button uk-button-small">Put in Basket</a>
<a onclick="$('#initial-dialog').dialog('close');" class="uk-button uk-button-small">Cancel</a>
</div>