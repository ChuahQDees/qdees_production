<?php
session_start();

if ($_POST["year"]!="") {
   $_SESSION['Year']=$_POST["year"];
   echo "1";
} else {
   echo "0";
}
?>