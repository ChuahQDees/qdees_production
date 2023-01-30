<?php
session_start();
include("../mysql.php");

function noInCart() {
   global $connection;

   $user_name=$_SESSION["UserName"];

   $sql="SELECT sum(qty) as qty from cart where user_name='$user_name' order by date_created";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   $num_row=mysqli_num_rows($result);
   if ($num_row>0) {
      if ($row["qty"]!="") {
         return number_format($row["qty"], 0);
      } else {
         return "0";
      }
   } else {
      return "0";
   }
}

echo noInCart();
?>