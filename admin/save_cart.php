<?php
session_start();
include_once("../mysql.php");

$id=$_POST["id"];
$qty=$_POST["qty"];

$success=true;
$message="";
$centre_code = $_SESSION["CentreCode"];
$year = $_SESSION['Year']; //MYQWESTC1C10001
$date = date('Y-m-d');
$isBlackListedCenter = "";
$allowOrder = "Y";

foreach ($id as $key=>$value) {

   $sql="SELECT product_code from cart where id='$value' ";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   if ($row["product_code"]=="") {
      $product_code="";
   } else {
      $product_code = $row["product_code"];
   }
  
	if ($isBlackListedCenter == ""){ //Run this only one time
		//Check the Module first
		
		$sqlChkBlacklistModule="SELECT centre_code from `order_blocklist` where centre_code='$centre_code'"; //Check the STEM module
		
		$resultChkBlacklistModule=mysqli_query($connection, $sqlChkBlacklistModule);
		$rowChkBlkModule=mysqli_fetch_assoc($resultChkBlacklistModule);

		if ($rowChkBlkModule["centre_code"]==$centre_code) { //Check if Center is in blacklist table	
			$isBlackListedCenter = "Y";
		}else{
			//No need to check anymore to prevent SQL overload
			$isBlackListedCenter = "N";
		}
		
	}
	
	if ($isBlackListedCenter == "Y"){ 
		//Check if they are trying to order the banned item
		$sqlChkBlacklistModule="SELECT id from `order_blocklist` where centre_code='$centre_code' and product_code='$product_code' and locked = 'Y'"; 
				
		$resultChkBlacklistModule=mysqli_query($connection, $sqlChkBlacklistModule);
        
		while ($browse_row=mysqli_fetch_assoc($resultChkBlacklistModule)) {
			//Look there's a record! Block it
			$allowOrder = "N";
		}
	}
	


      // $sql="SELECT product_name, sub_sub_category FROM `product` where sub_sub_category!='' and product_code ='$product_code' and category_id in (74, 75, 76, 47, 48, 49, 52)";
      // $result=mysqli_query($connection, $sql);
      // $num_row=mysqli_num_rows($result);
      // if ($num_row>0) {
      //   $row=mysqli_fetch_assoc($result);
      //   $sub_sub_category = $row["sub_sub_category"];// EDP or QF1 or QF2 or QF3
      //   $product_name = $row["product_name"];

      //   $sql="SELECT l.student_entry_level, s.level_count from (SELECT DISTINCT student_entry_level from student) l left join ( SELECT student_entry_level, count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id where s.centre_code='$centre_code' and year(fl.programme_date) = '$year' and ((MONTH(fl.programme_date) >= MONTH(CURRENT_DATE()) and MONTH(fl.programme_date) <= MONTH(CURRENT_DATE())) or (MONTH(fl.programme_date_end) >= MONTH(CURRENT_DATE()) and MONTH(fl.programme_date_end) >= MONTH(CURRENT_DATE()))) and ps.student_entry_level != '' and s.student_status = 'A' and s.deleted='0'  group by ps.student_entry_level, s.id) ab group by student_entry_level ) s on s.student_entry_level = l.student_entry_level where l.student_entry_level !='' and l.student_entry_level ='$sub_sub_category' ";

      //   $result=mysqli_query($connection, $sql);
      //   $row=mysqli_fetch_assoc($result);
      //   if ($row["level_count"]=="") {
      //      $student_no=0;
      //   } else {
      //      $student_no = round($row["level_count"], 0);
      //   }

      //   //echo $sql;
        
      //   $sql="SELECT b.buffer_amount FROM `centre` c inner join buffer_stock b on b.state=c.state and b.country=c.country where c.centre_code='$centre_code' and b.year=$year";
      //   $result=mysqli_query($connection, $sql);
      //   $row=mysqli_fetch_assoc($result);
      //   if ($row["buffer_amount"]=="") {
      //      $buffer_stock=0;
      //   } else {
      //      $buffer_stock = round($row["buffer_amount"], 0);
      //   }

      //   $sql="SELECT sum(qty) as closing_qty from ( SELECT sum(qty) as qty from `order` where product_code = '$product_code' and CAST(delivered_to_logistic_on AS DATE)<='$date' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' UNION ALL SELECT sum(qty) * -1 as qty from `collection` where product_code = '$product_code' and and CAST(collection_date_time AS DATE)<='$date' and centre_code='$centre_code' and void=0 )ab ";
      //   $result=mysqli_query($connection, $sql);
      //   $row=mysqli_fetch_assoc($result);
      //   if ($row["closing_qty"]=="") {
      //      $closing_qty=0;
      //   } else {
      //      $closing_qty = round($row["closing_qty"], 0);
      //   }

      //   $limit = $student_no + $buffer_stock - $closing_qty;

      //   if($qty[$key]<=$limit){
      //       $sql="UPDATE cart set qty='$qty[$key]' where id='$value'";
      //       $result=mysqli_query($connection, $sql);
      //       if (!$result) {
      //          $success=false;
      //         // $message ="Cart updated successfully";
      //       }
      //   }else{
      //    $message = "You have reached your maximum limit for $product_name. <br>
      //    Std no.($student_no) + Buffer stock($buffer_stock) - Current balance stock($closing_qty) = Limit($limit)";
      //   }
         

      // } else {
		 if ($allowOrder == "Y"){
			 $sql="UPDATE cart set qty='$qty[$key]' where id='$value'";
			 $result=mysqli_query($connection, $sql);
			 if (!$result) {
				$success=false;
			   // $message ="Cart updated successfully";
			 }
		 }else{
			 $success=false;
		 }
		 
      // }
}
if ($message!="") {
   echo $message;
} else {
   if ($success) {
      echo "Cart updated successfully";
   } else {
      echo "Something is wrong";
   }
}
// if ($success) {
//    echo "Cart updated successfully";
// } else {
//    echo "Something is wrong";
// }
?>