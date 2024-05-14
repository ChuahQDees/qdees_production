<?php
session_start();
include_once("../mysql.php");

//Based from add_2_cart.php

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
        $add_failed = "false"; //if this is true, break the loop and display error message saying that adding failed

        $bundle_id = $_POST["bundle_id"];

        $sqlx="SELECT * FROM order_bundle WHERE bundle_id = '1'";
        $resultx=mysqli_query($connection, $sqlx);

        while ($rowx = mysqli_fetch_assoc($resultx)) {
            //Original Shopping Cart Code
            //$product_qty = (!empty($_POST["product_qty"]) ? $_POST["product_qty"] : 1);
            //$centre_code = $_SESSION["CentreCode"];
            $year = $_SESSION['Year']; //MYQWESTC1C10001
            //$date = date('Y-m-d');
            $product_code = $rowx["product_code"];

            //Edited:
            $product_qty = (!empty($rowx["qty"]) ? $rowx["qty"] : 1);
            $username = $_SESSION["UserName"];
            //echo "test: ".$product_code;

            if (!isFoundInCart($product_code)) { //If it's not in cart, add the entry, otherwise increase quantity
                //$sql="INSERT into cart (user_name, product_code, qty) values ('".$_SESSION["UserName"]."', '$product_code', '$product_qty')";
                $sql = "INSERT INTO cart(user_name, product_code, qty)
                VALUES ('$username', '$product_code', '$product_qty')";
                $result=mysqli_query($connection, $sql);

                
                if (!$result){
                    $add_failed = "true";
                    //echo "Adding failed";
                }
                
            } else {
                
                if (addQty($product_code, $product_qty)) {
                    //echo "Qty Increased";
                } else {
                    $add_failed = "true";
                    //echo "Failed";
                }
                
            }
        }

        if ($add_failed == "false"){
            echo "Bundle added!";
        }else{
            echo "Adding failed, please contact IT";
        }
   } else {
      echo "Unauthorized access denied";
   }
} else {
   echo "Please login to the system";
}
?>
