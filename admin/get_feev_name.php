<?php
	include_once("../mysql.php");
	session_start();
	$subject=$_POST['subject'];
	//echo $subject;
	$programme_package=$_POST['programme_package'];

	global $connection;


	$sql="SELECT * from fee_structure where subject= '$subject' and programme_package='$programme_package' and status='Approved' and centre_code = '".$_SESSION["CentreCode"]."' and extend_year =  '".$_SESSION['Year']."'";
	// echo $sql;
	$result=mysqli_query($connection, $sql);
	$num_row=mysqli_num_rows($result);

	if ($num_row>0) {
?>
      	<option value="">Select <?php //echo $sql;?></option>
	<?php
		while ($row=mysqli_fetch_assoc($result)) {
	?>
			<option value="<?php echo $row['id']?>"><?php echo $row['fees_structure']?></option>
	<?php
		}
	} else {
		echo "";
	}
?>
