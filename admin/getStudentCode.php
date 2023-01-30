<?php
session_start();

include_once("../mysql.php");
include_once("functions.php");

$centre_code=$_POST["centre_code"];
//getCountryTerritoryCode($_SESSION["UserName"], $country_code, $territory_code);

echo getStudentCode($centre_code);
?>
