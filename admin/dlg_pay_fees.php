<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");
$ssid=$_POST["ssid"];
$year=$_SESSION['Year'];
$centre_code=$_SESSION["CentreCode"];

function registrationPaid($student_id) {
   global $connection, $centre_code, $year;

   $sql="SELECT c.* from collection c where c.year='$year' and c.student_code='$student_id'
   and c.collection_type='registration' and c.void=0 and c.centre_code='$centre_code'";
   writeLog($sql);
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      return true;
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

function getRegistrationFee() {
   global $connection;

   $sql="SELECT registration_fee from centre where centre_code='".$_SESSION["CentreCode"]."'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["registration_fee"];
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

function getCourseFee($course_id) {
   global $connection;

   $sql="SELECT fees from course where id='$course_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return $row["fees"];
}

function getStudentCodeName($ssid, &$student_code, &$student_name) {
   global $connection;

   $sql="SELECT * from student where sha1(id)='$ssid'";
//   echo $sql;
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   $student_code=$row["student_code"];
   $student_name=$row["name"];
}

function getCourseExtraFee($course_id, &$deposit, &$placement) {
   global $connection;

   $sql="SELECT deposit, placement from course where id='$course_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   $deposit=$row["deposit"];
   $placement=$row["placement"];
}

function getStartMonthEndMonth($group_id, &$start_month, &$end_month) {
   global $connection;

   $sql="SELECT * from `group` where id='$group_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   $start_month=date("m", strtotime($row["start_date"]));
   $end_month=date("m", strtotime($row["end_date"]));
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

if ($ssid!="") {
?>
<script>
$(document).ready(function () {
   $("#btnSearch").click(function (event) {
      event.preventDefault();
      doSearch();
   });
});

function reduceQty(product_code, student_code) {
   UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function () {
      $.ajax({
         url : "admin/reduceQty.php",
         type : "POST",
         data : "product_code="+product_code+"&student_code="+student_code,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            var s=response.split("|");

            if (s[0]=="1") {
               getBusket();
            }

            if (s[0]=="0") {
               UIkit.notify(s[1]);
            }
         },
         error : function(http, status, error) {
            UIkit.notify("Error:"+error);
         }
      });
   })
}

function removeFromBusket(product_code, student_code) {
   UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function () {
      var allocation_id=$("#allocation_id").val();

      $.ajax({
         url : "admin/removeFromBusket.php",
         type : "POST",
         data : "product_code="+product_code+"&student_code="+student_code,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            var s=response.split("|");

            if (s[0]=="1") {
               getBusket();
            }

            if (s[0]=="0") {
               UIkit.notify(s[1]);
            }
         },
         error : function(http, status, error) {
            UIkit.notify("Error:"+error);
         }
      });
   })
}

function getBusket() {
   var ssid='<?php echo $ssid?>';
//alert("ssid is "+ssid);
   $.ajax({
      url : "admin/getBusketContent.php",
      type : "POST",
      data : "ssid="+ssid,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         $("#lstBusket").html(response);
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}

function doSearch() {
   var category=$("#category").val();
   var s=$("#s").val();
   var allocation_id=$("#allocation_id").val();
   var student_code=$("#student_code").val();
   if (category!="") {
      $.ajax({
         url : "admin/getProductList.php",
         type : "POST",
         data : "category="+category+"&s="+s+"&allocation_id="+allocation_id+"&student_code="+student_code,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            alert(response);
            $("#lstProduct").html(response);
         },
         error : function(http, status, error) {
            UIkit.notify("Error:"+error);
         }
      });
   } else {
      UIkit.notify("Please fill in both fields");
   }
}

function doTermChange(allocation_id) {
   if ($("#term"+allocation_id).val()!="") {
      var fee=$("#fee"+allocation_id).val();
      $("#the_fee"+allocation_id).val(fee);
   } else {
      $("#the_fee"+allocation_id).val("");
   }
}

function doMonthChange(allocation_id) {
   if ($("#month"+allocation_id).val()!="") {
      var fee=$("#fee"+allocation_id).val();
      $("#the_fee"+allocation_id).val(fee);
   } else {
      $("#the_fee"+allocation_id).val("");
   }
}

function payFeeCancel() {
   $("#dlgPayFees").dialog("close");
}

function doPayFees() {
   UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function () {
      $("#frmPayFees").submit();
   });
}

$(function () {
   getBusket();
});

function onFeeLostFocus(allocation_id) {
   var the_fee=$("#the_fee"+allocation_id).val();
   var fee=$("#fee"+allocation_id).val();
   var can_adjust_fee='<?php echo $_SESSION["CanAdjustFee"]?>';

   if (can_adjust_fee=="N") {
      if (the_fee>fee) {
         UIkit.notify("Not allowed");
         $("#the_fee"+allocation_id).val("");
         $("#the_fee"+allocation_id).focus();
      }
   }
}
</script>
<?php
getStudentCodeName($ssid, $student_code, $student_name);
?>
      <div class="uk-grid">
         <div class="uk-width-1-3">
            <table class="uk-table uk-table-small">
               <tr>
                  <td class="uk-text-bold">Student Code</td>
                  <td>
                     <?php echo $student_code?>
                     <input type="hidden" name="student_code" id="student_code" value="<?php echo $student_code?>">
                  </td>
               </tr>
            </table>
         </div>
         <div class="uk-width-1-3">
            <table class="uk-table uk-table-small">
               <tr>
                  <td class="uk-text-bold">Student Name</td>
                  <td><?php echo $student_name?></td>
               </tr>
            </table>
         </div>
         <div class="uk-width-1-3">
            <table class="uk-table uk-table-small">
               <tr>
                  <td class="uk-text-bold">Year</td>
                  <td><?php echo $_SESSION['Year']?></td>
               </tr>
            </table>
         </div>
         <div class="uk-width-1-3">
            <table class="uk-table uk-table-small">
               <tr>
                  <td colspan="2">
                     <label class="uk-text-small">Registration</label><br>
                     <div class="uk-form class=width-1-1">
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
               </tr>
            </table>
         </div>
      </div>
      <form class="uk-form uk-form-small" name="frmPayFees" id="frmPayFees" method="post" action="admin/do_pay_fees.php" target="_blank">
         <input type="hidden" name="reg_fee" id="reg_fee" value="">
<?php
$sql="SELECT a.student_id, s.student_code, a.id, a.course_id, a.class_id, s.name, co.course_name, a.class_id, a.group_id
      from student s, course co, allocation a where a.student_id=s.id and a.course_id=co.id and a.year='$year'
      and s.student_status='A' and s.centre_code='$centre_code' and sha1(a.student_id)='$ssid'
      and co.course_name LIKE '%IE%' AND (not co.course_name LIKE '%BIE%') order by co.course_name asc";
//echo $sql;
$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);

if ($num_row>0) {
$registration=getRegistrationFee();
while ($row=mysqli_fetch_assoc($result)) {
   $fees=getCourseFee($row["course_id"]);
   getCourseExtraFee($row["course_id"], $deposit, $placement);
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
         <table class="uk-table uk-table-small uk-form uk-form-small">
            <tr>
               <td>
                  <input name="registration" id="registration" type="hidden" value="<?php echo $registration?>">
                  <input name="deposit<?php echo $row["id"]?>" id="deposit<?php echo $row["id"]?>" type="hidden" value="<?php echo $deposit?>">
                  <input name="placement<?php echo $row["id"]?>" id="placement<?php echo $row["id"]?>" type="hidden" value="<?php echo $placement?>">

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
               <td>
                  <label class="uk-text-small">Placement Fee</label><br>
<?php
if (!extraFeePaid($row["student_id"], $row["id"], $row["group_id"], 'placement')) {
?>
                  <input class="uk-form-small" name="placement_fee<?php echo $row["id"]?>" id="placement_fee<?php echo $row["id"]?>" type="text" placeholder="Placement Fee" value="">
                  <a onclick="copyPlacement('<?php echo $row['id']?>')" class="uk-button uk-button-small">Default</a>
<?php
} else {
?>
                  <div class="uk-text-bold uk-text-success">Placement Paid</div>
<?php
}
?>
               </td>
            </tr>
         </table>
<?php
   }
}
}
$sql="SELECT a.student_id, s.student_code, a.id, a.course_id, a.group_id, a.class_id, s.name, co.course_name, a.class_id, a.group_id
      from student s, course co, allocation a where a.student_id=s.id and a.course_id=co.id and a.year='$year'
      and s.student_status='A' and s.centre_code='$centre_code' and sha1(a.student_id)='$ssid'
      and (co.course_name LIKE '%BIEP%' or co.course_name LIKE '%BIMP%') order by co.course_name asc";

$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);
if ($num_row>0) {
$registration=getRegistrationFee();
while ($row=mysqli_fetch_assoc($result)) {
   getStartMonthEndMonth($row["group_id"], $start_month, $end_month);
   $fees=getCourseFee($row["course_id"]);
   getCourseExtraFee($row["course_id"], $deposit, $placement);
   if (!isDeleted($row["id"])) {
?>
         <table class="uk-table uk-table-small uk-form uk-form-small">
            <tr style="background-color:lightgrey">
               <td colspan="12"><?php echo $row["course_name"]?>
                  <input type="hidden" name="allocation_id[]" value="<?php echo $row["id"]?>">
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
         <table class="uk-table uk-table-small uk-form uk-form-small">
            <tr>
               <td>
                  <input name="registration" id="registration" type="hidden" value="<?php echo $registration?>">
                  <input name="deposit<?php echo $row["id"]?>" id="deposit<?php echo $row["id"]?>" type="hidden" value="<?php echo $deposit?>">
                  <input name="placement<?php echo $row["id"]?>" id="placement<?php echo $row["id"]?>" type="hidden" value="<?php echo $placement?>">

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
               <td>
                  <label class="uk-text-small">Placement Fee</label><br>
<?php
if (!extraFeePaid($row["student_id"], $row["id"], $row["group_id"], 'placement')) {
?>
                  <input class="uk-form-small" name="placement_fee<?php echo $row["id"]?>" id="placement_fee<?php echo $row["id"]?>" type="text" placeholder="Placement Fee" value="">
                  <a onclick="copyPlacement('<?php echo $row['id']?>')" class="uk-button uk-button-small">Default</a>
<?php
} else {
?>
                  <div class="uk-text-bold uk-text-success">Placement Paid</div>
<?php
}
?>
               </td>
            </tr>
         </table>
<?php
   }
}
}
getCourseExtraFee($row["course_id"], $registration, $deposit, $placement);
?>
<script>
function copyRegistration() {
   var registration=$("#registration").val();
   $("#registration_fee").val(registration);
   $("#reg_fee").val(registration);
}

function copyDeposit(allocation_id) {
   var deposit=$("#deposit"+allocation_id).val();
   $("#deposit_fee"+allocation_id).val(deposit);
}

function copyPlacement(allocation_id) {
   var placement=$("#placement"+allocation_id).val();
   $("#placement_fee"+allocation_id).val(placement);
}
</script>
      <div class="uk-grid">
         <div class="uk-width-1-2 uk-form">
            <div class="uk-text-center myheader">
               <h2 class="myheader-text-color">Search Product</h2>
            </div>
            <select name="category" id="category" class="uk-form-small">
               <option value="">Select</option>
<?php
$sql="SELECT * from codes where module='CATEGORY' and parent='' order by code";
$result=mysqli_query($connection, $sql);
while ($row=mysqli_fetch_assoc($result)) {
?>
               <option value="<?php echo $row['code']?>"><?php echo $row["code"]?></option>
<?php
}
?>
            </select>
            <input name="s" id="s" value="" placeholder="Product Code/Name" class="uk-form-small">
            <a class="uk-button uk-button-small" id="btnSearch">Search</a>
            <div id="lstProduct"></div>
         </div>
         <div class="uk-width-1-2 uk-form">
            <div class="uk-text-center myheader">
               <h2 class="myheader-text-color">Basket Content</h2>
            </div>
            <div id="lstBusket"><a class="uk-button" onclick="getBusket();">getBusket</a></div>
            <a onclick="doPayFees()" class="uk-button uk-button-small uk-button-primary uk-align-right">Pay</a>
            <a class="uk-button uk-button-small uk-align-right" onclick="payFeeCancel();">Cancel</a>
         </div>
      </div>
      </form>
<?php
   } else {
      echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Record not found</div></div>";
   }
?>