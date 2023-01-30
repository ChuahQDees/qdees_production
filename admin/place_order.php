<?php
session_start();
include_once("../mysql.php");

function generateRandomString($length) {
   $characters='0123456789';
   $charactersLength=strlen($characters);
   $randomString='';
   for ($i=0; $i<$length; $i++) {
      $randomString.=$characters[rand(0, $charactersLength-1)];
   }
   return $randomString;
}

function insertOrder($order_no, $centre_code, $user_name, $product_code, $qty, $unit_price, $total, $ordered_by, $ordered_on, $courier, $remarks, $name, $preferred_collection_date, $preferredtime, $slot) {
   global $connection;

   $sql="INSERT into `order` (order_no, centre_code,company_name, user_name, product_code, qty, unit_price, total, ordered_by, ordered_on, name, courier, remarks, preferred_collection_date, preferredtime, slot) values (";
   $sql.="'".$order_no."',";
   $sql.="'".$centre_code."',";
    $sql.="'".$company_name."',";
   $sql.="'".$user_name."',";
   $sql.="'".$product_code."',";
   $sql.="'".$qty."',";
   $sql.="'".$unit_price."',";
   $sql.="'".$total."',";
   $sql.="'".$ordered_by."',";
   $sql.="'".$ordered_on."',";
   $sql.="'".$name."',";
   $sql.="'".$courier."',";
   $sql.="'".$remarks."',";
   $sql.="'".$preferred_collection_date."',";
   $sql.="'".$preferredtime."',";
   $sql.="'".$slot."'";
   $sql.=")";

   $result=mysqli_query($connection, $sql) or die(mysqli_error($connection));
}

function deleteCartItem($id) {
   global $connection;

   $sql="DELETE from cart where id='$id'";
   $result=mysqli_query($connection, $sql);
}

function getUnitPrice($product_code) {
   global $connection;

   $sql="SELECT * from product where product_code='$product_code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["retail_price"];
}

function getNextOrderNo() {
   global $connection;
   $sql="SELECT COALESCE(max(order_no), 0) as last_no from `order` where centre_code = '".$_SESSION["CentreCode"]."'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   //$num_row=mysqli_num_rows($result);
   
   if ($row["last_no"]) {
      return str_pad($row["last_no"]+1, 10, "0", STR_PAD_LEFT);
   } else {
      $prefix = substr($_SESSION["CentreCode"], strrpos($_SESSION["CentreCode"], "C1")+2);
      return str_pad($prefix, 9, "0", STR_PAD_RIGHT)."1";
   }
}

if (($_SESSION["isLogin"]==1) & ($_SESSION["UserType"]=="A")) {
   $courier=$_POST["courier_service"];
   $remarks= mysqli_real_escape_string($connection, $_POST["remarks"]); 
   $name=$_POST["name"];
   $preferred_collection_date=$_POST["preferred_collection_date"];
   $preferredtime=$_POST["preferredtime"];
   $slot=$_POST["slot"];
   $sql="SELECT * from cart where user_name='".$_SESSION["UserName"]."'";
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      $order_no=getNextOrderNo();//generateRandomString(10);
      while ($row=mysqli_fetch_assoc($result)) {
         $unit_price=getUnitPrice($row["product_code"]);
         $order_date=date("Y-m-d H:i:s");
         insertOrder($order_no, $_SESSION["CentreCode"], $_SESSION["UserName"], $row["product_code"],
           $row["qty"], $unit_price, $row["qty"]*$unit_price, $_SESSION["UserName"], $order_date, $courier, $remarks, $name, $preferred_collection_date, $preferredtime, $slot);

			// Notification start
			$get_sql="SELECT `order_no`,`centre_code` FROM `order` where sha1(order_no)='$order_no'";
			$f_result=mysqli_query($connection, $get_sql);
			$orderRow=mysqli_fetch_assoc($f_result);
			$n_data['action_id'] =$orderRow['order_no'];
			$n_data['send_to'] = "hq";
			$n_data['send_from'] = $_SESSION['CentreCode'];
			$n_data['type'] = "order_new";
			$n_data['is_center_read'] = 1;
			$n_data['is_hq_read'] = 0;
			$n_data['subject'] = "You have a new sales order no. #" .$order_no; 
			notification($n_data);
			// Notification end
         deleteCartItem($row["id"]);
      }
      $year=$_SESSION['Year'];
      $booked_by=$_SESSION["UserName"];
      $centre_code=$_SESSION["CentreCode"];

      $update_sql="UPDATE slot_collection m, slot_collection_child c SET c.is_booked=1, c.booked_by='$booked_by', c.centre_code = '$centre_code', c.order_no='$order_no' WHERE m.id = c.slot_master_id and m.select_date='$preferred_collection_date' and m.select_time='$preferredtime' and c.slot_child='$slot' and m.year=$year and c.is_booked=0";

      //$update_sql="UPDATE `slot_collection` set is_booked=1, booked_by='$booked_by', centre_code='$centre_code', order_no='$order_no' where select_date='$preferred_collection_date' and select_time='$preferredtime' and slot='$slot' and year=$year and is_booked=0";
      
      $result=mysqli_query($connection, $update_sql);

      header("location: ../index.php?p=order_placed&order_no=" . $order_no);
   }
}
?>
