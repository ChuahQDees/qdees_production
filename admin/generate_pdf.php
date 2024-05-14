<?php
include_once("../mysql.php"); 	
global $connection;

$declaration = mysqli_query($connection,"SELECT * FROM `declaration` WHERE `declaration_pdf` IS NULL");
$i = 1;
while($row = mysqli_fetch_array($declaration))
{
    $nm = date('d-M-Y',strtotime($row['submited_date']))."-".$row['centre_code']."-".time().".pdf";

	$purl = "https://starters.q-dees.com/admin/declaration_pdf.php?declaration_id=".$row['id']."&CentreCode=".$row['centre_code']."&monthyear=";
	exec("export PATH='/usr/bin:/bin' && wkhtmltopdf '$purl' /var/www/html/starters.q-dees.com/admin/declaration_pdf/$nm", $output);

    mysqli_query($connection,"UPDATE `declaration` SET `declaration_pdf` = '".$nm."' WHERE `id` = '".$row['id']."'");

    echo $i." = ".$nm." <br>";
    $i++;
}

?>
