<?php
session_start();
include_once("mysql.php");
include_once("admin/functions.php");

function getCanAdjustFee($centre_code) {
   global $connection;

   $sql="SELECT can_adjust_fee from centre where centre_code='$centre_code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return $row["can_adjust_fee"];
}

$user_name=$_POST["user_name"];
$user_name=str_replace("'", "", $user_name);
$user_name=str_replace('"', '', $user_name);

$password=$_POST["password"];
$password=str_replace("'", "", $password);
$password=str_replace('"', '', $password);

$sql="SELECT * from user where user_name='$user_name' and `password`=password('$password') and is_active=1";

$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);
$row=mysqli_fetch_assoc($result);

if ($num_row>0) {
   $_SESSION["isLogin"]=1;
   $_SESSION["UserType"]=$row["user_type"];
   $_SESSION["UserName"]=$row["user_name"];
   $_SESSION["Name"]=$row["name"];
   $_SESSION["CentreCode"]=$row["centre_code"];
   $_SESSION["Country"]=getCountry($row["centre_code"]);
   $_SESSION["Email"]=$row["email"];

   $year_data = mysqli_query($connection,"SELECT `year` FROM `schedule_term` WHERE `centre_code` = '".$row['centre_code']."' AND ('".date('Y-m-d')."' BETWEEN `term_start` AND `term_end`)");

   if(mysqli_num_rows($year_data) > 0) {
      $year_row = mysqli_fetch_array($year_data);
      $_SESSION['Year'] = $year_row['year'];
   } else {
      $_SESSION['Year']=date("Y");
   }
   
   $_SESSION["CanAdjustFee"]=getCanAdjustFee($_SESSION["CentreCode"]);

   if(($row['api_key'] == null || $row['api_key'] == '') && ($_SESSION["UserType"]!="S"))
   {
      $api_key = getKey();

      mysqli_query($connection,"UPDATE `user` SET `api_key` = '$api_key', `helpdesk_role` = 'Franchisor' WHERE `id` = '".$row['id']."'");

     curlCall("admin/add-user?centre_name=".urlencode($user_name)."&email=".$row['email']."&password=".$password."&country=".$row['country']."&api_key=".$api_key."&role=Franchisor");
   }

	//CHS: Check if 2023/2024 students is more than 10 or not. If it's less than 10, remove order access
	$totalAmt = 0;
	$sql = "SELECT count(id) total
				FROM (
	SELECT DISTINCT ps.student_entry_level, s.id
					FROM student s
					INNER JOIN programme_selection ps ON ps.student_id = s.id
					INNER JOIN student_fee_list fl ON fl.programme_selection_id = ps.id
					INNER JOIN fee_structure f ON f.id = fl.fee_id
					WHERE (fl.programme_date >= '2023-03-01' AND fl.programme_date <= '2024-02-29') 
					AND ps.student_entry_level != '' AND s.student_status = 'A' 
					AND s.centre_code='".$_SESSION["CentreCode"]."'
					AND ((fl.programme_date >= '2023-04-01' AND fl.programme_date <= '2023-04-30') OR (fl.programme_date_end >= '2023-04-01' 
					AND fl.programme_date_end >= '2023-04-30')) AND s.deleted = '0' 
				) ab ";
	  $result=mysqli_query($connection, $sql);
	  //$sub_categories = array();
	  if ($result) {
		while($row=mysqli_fetch_assoc($result)){
			$totalAmt = $row['total'];
		}
	  }
	  
	if ($totalAmt < 10){ //Lack of students found. Remove access.
		$sql = "DELETE FROM `user_right` WHERE user_name = '".$_SESSION["UserName"]."' 
		AND (`right` = 'OrderEdit' OR `right` = 'OrderView')";
	}else{
		//Check if the account has 'View Only' Permission for Order
		$sql2 = "SELECT * `user_right` WHERE user_name = '".$_SESSION["UserName"]."' 
		AND (`right` = 'OrderView')"
		
		$result2=mysqli_query($connection, $sql2);
		if ($result2) {
			while($row=mysqli_fetch_assoc($result2)){
				$totalAmt = $row['total']; //Check to see if there's any result returned
			}
		}
		
		if ($totalAmt = 0){ //No records found. Proceed to automatically give permissions.
			
		}
	  
		//Check if they are an admin or Operator account
	}
	
	$result=mysqli_query($connection, $sql);

	

   echo "1|Login successful";
} else {
   echo "0|Login failed, please try again";
}
?>
