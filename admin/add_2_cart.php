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

      //Check if this is a STEM Bundle
      $checkSTEMBundle = substr($_POST["product_id"], 0, 18);
      $isSTEMBundle = '0';

      if ($checkSTEMBundle == 'MY-STEM-MOD-BUNDLE'){
         $isSTEMBundle = '1';
      }


      if ($isSTEMBundle == '0'){
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
      }else{
		  $qtyLocked = "N";
		 //This is a STEM Bundle, split it to MOD and Student Kit
		 
         //Add Kit or Module to shopping cart if it's STEM (supposed to be bundle but finance is lazy lmao)
         //Search String

         // Get the number
         $result = substr($_POST["product_id"], 19, 2);

         //Create the new IDs to put inside the shopping cart
         $stringMod = "MY-STEM-MOD ".$result."((--MSSB_112";
         $stringKit = "MY-STEM-S.KIT-MOD ".$result."((--MSSB_112";
      
         $productCode = "";

		//Check if Center is in Blacklist table (And if it is, check the number of assigned students)
		//If Success, run the code below as usual
		
		//Check the Module first
		$sqlChkBlacklistModule="SELECT * from `order_blocklist` where centre_code='$centre_code' and (product_code='$stringMod' OR product_code='$stringKit') and locked = 'Y'"; //Check the STEM module
		
        $resultChkBlacklistModule=mysqli_query($connection, $sqlChkBlacklistModule);
        $rowChkBlkModule=mysqli_fetch_assoc($resultChkBlacklistModule);

		$isBlackListedCenter = "N";

        if ($rowChkBlkModule["centre_code"]==$centre_code) { //Check if Center is in blacklist table		
			$isBlackListedCenter = "Y";
			$sqlGetTotal = "SELECT * FROM `order` WHERE (product_code = '$stringMod' OR product_code = '$stringKit') 
						  AND centre_code = '$centre_code'
						  AND cancelled_on IS NULL";
						  
			$resultGetTotal = mysqli_query($connection, $sqlGetTotal);
					
			//$num_rowTotal = mysqli_num_rows($resultGetTotal);
			
			//Get number of Modules and Student Kits they have already ordered
			$totalModule = 0;
			$totalStudentKit = 0;
			$finalVar = 0; //Use this amount for the shopping cart instead

			$assignedTotalStudents = 0; //Get the Total students which are involved with the STEM module they are trying to buy
			$finalVarString = $stringKit; //Which product code is the function below taking?
								  
			//Now add what they want to order inside their shopping cart
			//$finalVar += $product_qty;
			
			//if ($num_rowTotal>0) {
			while ($browse_row=mysqli_fetch_assoc($resultGetTotal)) {
				//Start counting the module and the student kit purchased by the user
				if ($browse_row["product_code"] == $stringMod){
					$totalModule++;
				}else if ($browse_row["product_code"] == $stringKit){
					$totalStudentKit++;
				}
				
				//Get the lesser amount to prevent exploit
				if ($totalModule > $totalStudentKit){
					$finalVar = $totalStudentKit;
					$finalVarString = $stringKit;
				}else{
					$finalVar = $totalModule;
					$finalVarString = $stringMod;
				}			
				
				//echo $totalModule; //Check why is this not reading the order and updating the finalVar
				//return 0;
			
			}
			
			
			//Don't forget the buffer!
				$assignedTotalStudents += $rowChkBlkModule["qty_buffer"];
			//}
			
			$month = date('m');
			$current_date = date('Y-m-d');
			$monthyear = date('Y').'-'.$month;
			
			//Copy pasted and modified from Declaration Loool
			//Get the total amount of students which was assigned in the system
			$sql="SELECT ps.student_entry_level, COUNT(s.id) AS id from student s 
			inner join programme_selection ps on ps.student_id=s.id 
			inner join student_fee_list fl on fl.programme_selection_id = ps.id 
			inner join fee_structure f on f.id=fl.fee_id 

			where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') 
			and ps.student_entry_level != '' 
			and s.student_status = 'A' 
			and s.start_date_at_centre <= '$current_date' 
			and s.centre_code='$centre_code' 
			and s.deleted='0'
			and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') 
			AND DATE_FORMAT(fl.programme_date_end, '%Y-%m')

			group by ps.student_entry_level";
						
			$resultt=mysqli_query($connection, $sql);
			
			//echo $sql; 

			//$level_count = 0;
			$EDPCount = 0;
			$QF1Count = 0;
			$QF2Count = 0;
			$QF3Count = 0;
			
			while ($roww=mysqli_fetch_array($resultt)) {
				if ($roww['student_entry_level'] == "EDP" && $rowChkBlkModule["EDP"] == 'Y'){
					$EDPCount = $roww['id'];
					$assignedTotalStudents += $EDPCount;
				}
				
				if ($roww['student_entry_level'] == "QF1" && $rowChkBlkModule["QF1"] == 'Y'){
					$QF1Count = $roww['id'];
					$assignedTotalStudents += $QF1Count;
				}
				
				if ($roww['student_entry_level'] == "QF2" && $rowChkBlkModule["QF2"] == 'Y'){
					$QF2Count = $roww['id'];
					$assignedTotalStudents += $QF2Count;
				}
				
				if ($roww['student_entry_level'] == "QF3" && $rowChkBlkModule["QF3"] == 'Y'){
					$QF3Count = $roww['id'];
					$assignedTotalStudents += $QF3Count;
				}
			}
			
			//FinalVar = The total of all the ordered items in History
			
			//if ($assignedTotalStudents < $finalVar){ //Oh look, they're trying to order more than the students assigned in fee structure. Change product qty please


			if (!isFoundInCart($stringMod) && !isFoundInCart($stringKit)) {//Item is already found in the cart
				//It's not in the cart
				if ($assignedTotalStudents < $product_qty){ //Oh look, they're trying to order more than the students assigned in fee structure. Change product qty please
					$product_qty = $assignedTotalStudents - $finalVar; //17 - 0
				}else if ($assignedTotalStudents == $finalVar){
						$product_qty = $assignedTotalStudents;
				}
			}else if ($isBlackListedCenter == "Y"){
				//For blacklisted accounts only
				
				//Get cart quantity
			    $sql="SELECT qty FROM cart where user_name='".$_SESSION["UserName"]."' and product_code='$finalVarString'";

				$resultt=mysqli_query($connection, $sql);
				
				//echo $sql; 

				//$level_count = 0;
				$cartqty = 0;
				if ($rowChk=mysqli_fetch_array($resultt)) {
					$cartqty = $rowChk['qty'];
				}
				
				//echo $cartqty; //get 0 check whyyyy (Most likely you never bought anything lol)
				//return 0;
				
				//Check if the current product quantity is more than the assigned total students
				//if ($assignedTotalStudents < ($finalVar + $product_qty)){
				if ($assignedTotalStudents < ($finalVar + $product_qty)){
					/*
					$product_qty = ($finalVar + $product_qty) - $assignedTotalStudents;
					
					if ($assignedTotalStudents < 0){
						$product_qty = 0;
					}
					*/
					//$product_qty = ($assignedTotalStudents - $finalVar) - $product_qty; //(17-0) - 1
					$product_qty = 0;
					//echo $finalVar; //get 0 check whyyyy (Most likely you never bought anything lol)
					//return 0;
					
					$qtyLocked = "Y";
				}else if(($finalVar + $product_qty) < $assignedTotalStudents){//To prevent user from being able to continuously click 'add to cart 1'
					if (($finalVar + $cartqty) >= $assignedTotalStudents){
						$product_qty = 0;
						$qtyLocked = "Y";
					}
				}
				
				//else{
				//	$product_qty = ($assignedTotalStudents - $finalVar) - $product_qty; //(17-0) - 1
				//}
			} 
			
			
						  
        }
		////////////////////////////////////////////////
		
         //Proceed to add TWO items into the cart
         if (!isFoundInCart($stringMod) && !isFoundInCart($stringKit)) {
            $sql="INSERT into cart (user_name, product_code, qty) values ('".$_SESSION["UserName"]."', '$stringMod', '".$product_qty."')";
            $result=mysqli_query($connection, $sql);
            $sql2="INSERT into cart (user_name, product_code, qty) values ('".$_SESSION["UserName"]."', '$stringKit', '".$product_qty."')";
            $result2=mysqli_query($connection, $sql2);

            if ($result2) {
				if ($qtyLocked == "Y"){
					echo "Quantity locked";
			   }else{
					echo "Add successful";
			   }
            } else {
               echo "Adding failed";
            }
         }else{
            if (addQty($stringMod, $product_qty) && addQty($stringKit, $product_qty)) {
				if ($qtyLocked == "Y"){
					echo "Quantity locked";
			   }else{
					echo "Qty Increased";
			   }
            } else {
               echo "Failed";
            }
         }
      }
      //END STEM BUNDLE ADDING
   //}
   } else {
      echo "Unauthorized access denied";
   }
} else {
   echo "Please login to the system";
}
?>
