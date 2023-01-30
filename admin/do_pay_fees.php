<?php
session_start();
$session_id=session_id();
include_once("../mysql.php");
include_once("functions.php");
include_once("../uikit1.php");

function getReceiptNumber($centre_code) {
   global $connection;

   $sql="SELECT max(batch_no) as batch_no from collection where batch_no like '$centre_code-%'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   if ($row["batch_no"]!="") {
      $serial=substr($row["batch_no"], -6)+1;

      return $centre_code."-".str_pad($serial, 6, '0', STR_PAD_LEFT);;
   } else {
      return $centre_code."-000001";
   }
}

$batch_no=getReceiptNumber($_SESSION["CentreCode"]);
$year=$_SESSION['Year'];
$centre_code=$_SESSION["CentreCode"];

foreach ($_POST as $key=>$value) {
   $$key=$value;
}

function isRecordFound($allocation_id, $collection_type, $year, $month) {
   global $connection;

   $sql="SELECT * from collection where allocation_id='$allocation_id' and collection_type='$collection_type'
   and `year`='$year' and `month`='$month'";
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      return true;
   } else {
      return false;
   }
}

function InsertFees($student_id, $student_code, $year, $month, $amount, $batch_no, $allocation_id, $collection_type) {
   global $connection;

   $centre_code=$_SESSION["CentreCode"];

   if (!isRecordFound($allocation_id, $collection_type, $year, $month)) {
      $collection_date_time=date("Y-m-d H:i:s");
      $pic=$_SESSION["UserName"];

      $sql="INSERT into collection (`allocation_id`, `centre_code`, `batch_no`, `collection_date_time`, `amount`,
      `collection_type`, `year`, `collection_month`, `pic`, student_id, student_code) values ('$allocation_id', '$centre_code', '$batch_no',
      '$collection_date_time', '$amount', '$collection_type', '$year', '$month', '$pic', '$student_id', '$student_code')";

      $result=mysqli_query($connection, $sql);
      if ($result) {
         return "$month fees inserted<br>";
      } else {
         return "$month fees inserted failed<br>";
      }
   } else {
      return "Collection already exists<br>";
   }
}

function getStudentCode1($allocation_id) {
   global $connection;

   $sql="SELECT s.student_code from student s, allocation a where a.student_id=s.id and a.id='$allocation_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["student_code"];
}

function getStudentIDByCode($student_code) {
   global $connection;

   $sql="SELECT id from student where student_code='$student_code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["id"];
}

function getStudentIDByHash($ssid) {
   global $connection;

   $sql="SELECT id from student where sha1(id)='$ssid'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["id"];
}

function insertProductSale($student_code, $allocation_id) {
   global $connection, $batch_no, $session_id, $centre_code;

   $now=date("Y-m-d H:i:s");

   $k="";

   $sql="SELECT * from busket where student_code='$student_code' and session_id='$session_id'";
   $result=mysqli_query($connection, $sql);
   while ($row=mysqli_fetch_assoc($result)) {
      $product_code=$row["product_code"];
      $qty=$row["qty"];
      $unit_price=$row["unit_price"];
      $amount=$qty*$unit_price;
      $collection_type="product";
      $year=$_SESSION['Year'];
      $collection_month=date("n");
      $pic=$_SESSION["UserName"];

      $sql="INSERT into collection (allocation_id, centre_code, batch_no, collection_date_time, product_code, qty, unit_price,
      amount, collection_type, `year`, collection_month, pic) values ('$allocation_id', '$centre_code', '$batch_no', '$now',
      '$product_code', '$qty', '$unit_price', '$amount', '$collection_type', '$year', '$collection_month', '$pic')";

      $insert_result=mysqli_query($connection, $sql);

      if ($insert_result) {
         $k.="$product_code sale recorded<br>";
      }
   }

   $sql="DELETE from busket where student_code='$student_code' and session_id='$session_id'";
   $result=mysqli_query($connection, $sql);

   return $k;
}

$msg="";

//writeLog(json_encode($_POST, true));
$student_id=getStudentIDByCode($student_code);

$registration_fee=$_POST["reg_fee"];
if ($registration_fee!="") {
   if (($registration_fee!="") & ($registration_fee>0)) {
      $msg.=InsertFees($student_id, $student_code, $year, "", $registration_fee, $batch_no, $aid, "registration");
   }
}

foreach ($allocation_id as $aid) {
   if ($_POST["course_type".$aid]=="IE") {
      $term=$_POST["term".$aid];
      $the_fee=$_POST["the_fee".$aid];
      $deposit_fee=$_POST["deposit_fee".$aid];
      $placement_fee=$_POST["placement_fee".$aid];

      if ($term!="") {
         $msg.=InsertFees($student_id, $student_code, $year, $term, $the_fee, $batch_no, $aid, "tuition");
      }

      if (($deposit_fee!="") & ($deposit_fee>0)) {
         $msg.=InsertFees($student_id, $student_code, $year, "", $deposit_fee, $batch_no, $aid, "deposit");
      }

      if (($placement_fee!="") & ($placement_fee>0)) {
         $msg.=InsertFees($student_id, $student_code, $year, "", $placement_fee, $batch_no, $aid, "placement");
      }
   }

   if ($_POST["course_type".$aid]=="BIEP") {
      $student_code=$_POST["student_code"];
      $student_id=getStudentIDByCode($student_code);

      $month=$_POST["month".$aid];
      $the_fee=$_POST["the_fee".$aid];
      $deposit_fee=$_POST["deposit_fee".$aid];
      $placement_fee=$_POST["placement_fee".$aid];

      if ($month!="") {
         $msg.=InsertFees($student_id, $student_code, $year, $month, $the_fee, $batch_no, $aid, "tuition");
      }

      if (($deposit_fee!="") & ($deposit_fee>0)) {
         $msg.=InsertFees($student_id, $student_code, $year, "", $deposit_fee, $batch_no, $aid, "deposit");
      }

      if (($placement_fee!="") & ($placement_fee>0)) {
         $msg.=InsertFees($student_id, $student_code, $year, "", $placement_fee, $batch_no, $aid, "placement");
      }
   }
}

$msg.=insertProductSale(getStudentCode1($aid), $aid);
?>

<form id="redirect" name="redirect" method="GET" action="print_receipt.php">
   <input type="hidden" name="batch_no" value="<?php echo sha1($batch_no)?>">
</form>

<script>
   $("#redirect").submit();
//   document.forms["redirect"].submit();
//   window.history.back();
   window.opener.location.reload(false);
</script>

