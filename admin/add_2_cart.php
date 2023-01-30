<?php
session_start();
include_once("../mysql.php");

function isFoundInCart($product_id) {
   global $connection;

   $sql="SELECT * from cart where product_code='$product_id' and user_name='".$_SESSION["UserName"]."'";
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      return true;
   } else {
      return false;
   }
}

function addQty($product_id, $product_qty = 1) {
   global $connection;

   $sql="UPDATE cart set qty=qty+". $product_qty ." where user_name='".$_SESSION["UserName"]."' and product_code='$product_id'";
   $result=mysqli_query($connection, $sql);
   if ($result) {
      return true;
   } else {
      return false;
   }
}

if ($_SESSION["isLogin"]==1) {
   if ($_SESSION["UserType"]=="A") {
     $product_qty = (!empty($_POST["product_qty"]) ? $_POST["product_qty"] : 1);
     $centre_code = $_SESSION["CentreCode"];
     $year = $_SESSION['Year']; //MYQWESTC1C10001
     $date = date('Y-m-d');
     $product_code = $_POST["product_id"];
      
   //   $sql="SELECT sub_sub_category FROM `product` where sub_sub_category!='' and product_code ='$product_code' and category_id in (74, 75, 76, 47, 48, 49, 52)";
   //   //echo $sql;
   //   $result=mysqli_query($connection, $sql);
   //   $num_row=mysqli_num_rows($result);
   //   if ($num_row>0) {
   //      $row=mysqli_fetch_assoc($result);
   //      $sub_sub_category = $row["sub_sub_category"];// EDP or QF1 or QF2 or QF3

   //      $sql="SELECT l.student_entry_level, s.level_count from (SELECT DISTINCT student_entry_level from student) l left join ( SELECT student_entry_level, count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id where s.centre_code='$centre_code' and year(fl.programme_date) = '$year' and ((MONTH(fl.programme_date) >= MONTH(CURRENT_DATE()) and MONTH(fl.programme_date) <= MONTH(CURRENT_DATE())) or (MONTH(fl.programme_date_end) >= MONTH(CURRENT_DATE()) and MONTH(fl.programme_date_end) >= MONTH(CURRENT_DATE()))) and ps.student_entry_level != '' and s.student_status = 'A' and s.deleted='0'  group by ps.student_entry_level, s.id) ab group by student_entry_level ) s on s.student_entry_level = l.student_entry_level where l.student_entry_level !='' and l.student_entry_level ='$sub_sub_category' ";

   //      $result=mysqli_query($connection, $sql);
   //      $row=mysqli_fetch_assoc($result);
   //      if ($row["level_count"]=="") {
   //         $student_no=0;
   //      } else {
   //         $student_no = round($row["level_count"], 0);
   //      }

   //      //echo $sql;
        
   //      $sql="SELECT b.buffer_amount FROM `centre` c inner join buffer_stock b on b.state=c.state and b.country=c.country where c.centre_code='$centre_code' and b.year=$year";
   //      $result=mysqli_query($connection, $sql);
   //      $row=mysqli_fetch_assoc($result);
   //      if ($row["buffer_amount"]=="") {
   //         $buffer_stock=0;
   //      } else {
   //         $buffer_stock = round($row["buffer_amount"], 0);
   //      }

   //      $sql="SELECT sum(qty) as closing_qty from ( SELECT sum(qty) as qty from `order` where product_code = '$product_code' and CAST(delivered_to_logistic_on AS DATE)<='$date' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' UNION ALL SELECT sum(qty) * -1 as qty from `collection` where product_code = '$product_code' and CAST(collection_date_time AS DATE)<='$date' and centre_code='$centre_code' and void=0 )ab ";
   //      //echo $sql;
   //      $result=mysqli_query($connection, $sql);
   //      $row=mysqli_fetch_assoc($result);
   //      if ($row["closing_qty"]=="") {
   //         $closing_qty=0;
   //      } else {
   //         $closing_qty = round($row["closing_qty"], 0);
   //      }

   //      $limit = $student_no + $buffer_stock - $closing_qty;
   //      if($product_qty<=$limit){
   //       if (!isFoundInCart($_POST["product_id"])) {
   //          $sql="INSERT into cart (user_name, product_code, qty) values ('".$_SESSION["UserName"]."', '".$_POST["product_id"]."', '".$product_qty."')";
   //          $result=mysqli_query($connection, $sql);
   
   //          if ($result) {
   //             echo "Add successful";
   //          } else {
   //             echo "Adding failed";
   //          }
   //       } else {
   //          $sql="SELECT qty from cart where product_code='$product_code' and user_name='".$_SESSION["UserName"]."'";
   //          $result=mysqli_query($connection, $sql);
   //          $row=mysqli_fetch_assoc($result);
   //          if ($row["qty"]=="") {
   //             $cart_qty=0;
   //          } else {
   //             $cart_qty = $row["qty"];
   //          }
   //          if($product_qty+$cart_qty <= $limit){
   //             if (addQty($_POST["product_id"], $product_qty)) {
   //                echo "Qty Increased";
   //             } else {
   //                echo "Failed";
   //             }
   //          }else{
   //             echo "You have reached your maximum limit. <br>
   //             Std no.($student_no) + Buffer stock($buffer_stock) - Current balance stock($closing_qty) = Limit($limit)";
   //          }
   //       }
   //      }else{
   //       echo "You have reached your maximum limit. <br>
   //       Std no.($student_no) + Buffer stock($buffer_stock) - Current balance stock($closing_qty) = Limit($limit)";
   //      }
         

   //   } else {

      if (!isFoundInCart($_POST["product_id"])) {
         $sql="INSERT into cart (user_name, product_code, qty) values ('".$_SESSION["UserName"]."', '".$_POST["product_id"]."', '".$product_qty."')";
         $result=mysqli_query($connection, $sql);

         if ($result) {
            echo "Add successful";
         } else {
            echo "Adding failed";
         }
      } else {
         if (addQty($_POST["product_id"], $product_qty)) {
            echo "Qty Increased";
         } else {
            echo "Failed";
         }
      }
   //}
   } else {
      echo "Unauthorized access denied";
   }
} else {
   echo "Please login to the system";
}
?>
