<?php

session_start();

$session_id=session_id();

include_once("../mysql.php");

foreach ($_POST as $key=>$value) {

	$$key=mysqli_real_escape_string($connection, $value); //$id of busket in sha1

}



if ($session_id!="") {

   $sql="UPDATE busket SET discount = '$discount' where sha1(id)='$id' and session_id='$session_id'";

   $result=mysqli_query($connection, $sql);

  	if ($result) {

  		$sql2="SELECT sum(amount) as amount, sum(discount) as discount from busket where student_id='$student_id' and session_id='$session_id'";
//echo $sql2;
   		$result2=mysqli_query($connection, $sql2);

   		$row=mysqli_fetch_assoc($result2);



  	  	echo "1|".number_format(($row["amount"] - $row["discount"]),2);

  	} else {

  	  	echo "0|Update failed";

  	}

} else {

	echo "0|Something is wrong, cannot proceed";

}

?>