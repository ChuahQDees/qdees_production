<?php
session_start();

include_once("../mysql.php");
include_once("functions.php");

$master_code=$_POST["master_code"];

//getCountryTerritoryCode($_SESSION["UserName"], $country_code, $territory_code);

echo getCentreCode($master_code);
?>