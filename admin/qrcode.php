<?php
session_start();
header('Content-Disposition: attachment; filename="student_qrcode.jpg"');
$centre_code=$_SESSION["CentreCode"];

$server=$_SERVER['HTTP_HOST'];

include_once("../lib/phpqrcode/qrlib.php");
QRcode::png("http://$server/student_qr.php?centre_code=$centre_code");
?>