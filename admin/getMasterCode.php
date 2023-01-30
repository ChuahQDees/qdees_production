<?php
session_start();

include_once("../mysql.php");
include_once("functions.php");

$country=$_POST["country"];
$mastertype=$_POST["mastertype"];

//getCountryTerritoryCode($_SESSION["UserName"], $country_code, $territory_code);

echo getMasterCode($country, $mastertype);
?>