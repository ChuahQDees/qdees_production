<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Qdees Student Registration Form</title>
<?php
include_once("uikit.php");
include_once("bootstrap.php");
?>

  <link rel="shortcut icon" href="images/favicon.png" />
    <style>
    .no-close .ui-dialog-titlebar-close {
      background-image: url('images/delete.png') !important;
      background-position: -2px !important;
      background-repeat: no-repeat !important;

    }

    .btn-qdees {
      padding: 9px 15px;
      border-radius: 10px;
      box-shadow: 0px 2px 3px 0px #00000021 !important;
      font-size: 1.2rem;
      font-weight: bold;
      position: absolute;
      left: 50px;
      top: 19px;
      color: grey;
    }
  </style>
</head>

<body id="theBody">
	<span class="kfhg">
		<a href="/index.php?p=student_qr_list">
		  <span class=" btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
		</a>
	</span>
<?php

include_once("mysql.php");
include_once("admin/functions.php");

define('IS_PUBLIC_STUDENT_FORM', true);
include_once("admin/student_func.php");
include_once("admin/student_form.php");

if ($msg!="") {
   echo "<script>UIkit.notify('$msg')</script>";
}
?>
</body>
</html>
