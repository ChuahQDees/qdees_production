<?php
include_once("../mysql.php");

$query = mysqli_query($connection, "SELECT * FROM `student` WHERE `student_code` = '" . $_POST['student_code'] . "';");
$row = $query->fetch_assoc();
echo $row['dob'];