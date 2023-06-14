<?php
	include_once("../mysql.php");
	session_start();
	$fee_id=$_POST['fee_id'];
	global $connection;

    $sql="SELECT from_date, to_date from fee_structure where id =  '$fee_id'";

    $result=mysqli_query($connection, $sql);
	$num_row=mysqli_num_rows($result);

	if ($num_row>0) {

        if ($row=mysqli_fetch_assoc($result)) {
            $dbResponse = array( "programme_date" => $row['from_date'], "programme_date_end" => $row['to_date']);
            echo json_encode($dbResponse);
        }

    }else {
		echo "";
	}
?>
