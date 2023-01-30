<?php
session_start();
if ($_SESSION["isLogin"]==1) {
   session_destroy();
   header("location: index.php");
} else {
   header("location: index.php");
}
?>