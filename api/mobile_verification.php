<?php

error_reporting(E_ALL ^ E_WARNING);

require_once("../admin/connect.php");

$jsondata=file_get_contents('php://input');
$js = json_decode($jsondata, true);


$member_phone= $js['member_phone'];

		if( !empty($member_phone) ){


					        $stmt51 = "SELECT user_id FROM c9_user where `mobile` = '".$member_phone."'";
			                $result1 = mysqli_query($conn, $stmt51);
					          if(mysqli_num_rows($result1) > 0){

					    $json = array("error" => false, "message" =>"This is App User!");

							  }else{

								$json = array("error" => true, "message" =>"Mobile No is not Valid");
							}


		}else{

			$json = array("error" => true, "message" =>"Details should not be Empty!");
		}

@mysqli_close($conn);

/* Output header */

 header('Content-type: application/json, charset=UTF-8');
 echo json_encode($json);


	?>
