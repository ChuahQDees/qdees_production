<?php
$table="user";
$key_field="user_name";
$msg="";

function isRecordFound($table, $key_field, $key_value) {
   global $connection;

   $sql="SELECT $key_field from `$table` where $key_field='$key_value'";
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      return true;
   } else {
      return false;
   }
}

if ($mode=="EDIT") {
   if ($get_sha1_id!="") {
      $edit_sql="SELECT * from `$table` where sha1(id)='$get_sha1_id'";
      $result=mysqli_query($connection, $edit_sql);
      $edit_row=mysqli_fetch_assoc($result);
   }
}

if ($mode=="DEL") {
   if ($get_sha1_id!="") {
      $del_sql="DELETE from `$table` where sha1(id)='$get_sha1_id'";
      $result=mysqli_query($connection, $del_sql);
      $msg="Record deleted";
   }
}

function dataOK() {
   global $mastertype, $country, $state, $user_name, $user_type, $email, $password, $user_role, $centre_name; 
   //echo $_SESSION["UserType"];
   if (strtoupper($_SESSION["UserType"])=="S") {
      if (($mastertype!="") & ($country!="") & ($state!="") & ($user_name!="") & ($user_type!="") & ($password!="") & ($user_role!="")) {
         return true;
      } else {
         return false;
      }
   } else {
      //if (($mastertype!="") & ($country!="") & ($state!="") & ($user_name!="") & ($user_type!="") & ($email!="") & ($password!="") & ($centre_name!="") & ($user_role!="")) {
      if (($mastertype!="") & ($country!="") & ($state!="") & ($user_name!="") & ($user_type!="") & ($password!="") & ($centre_name!="") & ($user_role!="")) {
         return true;
      } else {
         return false;
      }
   }
}

//if ($mode=="SAVE") {
foreach ($_POST as $key=>$value) {
   $$key=$value;
}

 global $connection;

   $sql="SELECT centre_code from centre where company_name  ='$centre_name'";
    $result=mysqli_query($connection, $sql);
     if ($result) {
     $row = mysqli_fetch_assoc($result);
	 $centre_code = $row['centre_code'];
  }

$state= $_POST['state'];
$state = implode (", ", $state);
//echo dataOK(); die;
   if (isRecordFound($table, $key_field, $hidden_user_name)) {
      if (dataOK()) {
         $update_sql="UPDATE `$table` set mastertype='$mastertype', country='$country', state='$state', user_name='$user_name', email='$email', user_type='$user_type', centre_name='$centre_name', user_role='$user_role', centre_code='$centre_code' where user_name='$hidden_user_name'";

         $result=mysqli_query($connection, $update_sql);

         if ($password!=$hidden_password) {
            $update_sql="UPDATE `$table` set `password`=password('$password') where user_name='$hidden_user_name'";
            $result=mysqli_query($connection, $update_sql);
			//echo $update_sql; die;
         }

         $msg="Record updated";
      } else {
         //$msg="All fields are required";
      }
   } else {
      //echo dataOK(); die;
      if (dataOK()) {

        $api_key = getKey();

        $insert_sql="INSERT into `$table` (mastertype, country, state, user_name, user_type, user_role, email, `password`, centre_name, centre_code,`name`,tel,api_key,helpdesk_role)
         values ('$mastertype', '$country', '$state', '$user_name', '$user_type', '$user_role' , '$email',  password('$password'), '$centre_name', '$centre_code','','','$api_key','$helpdesk_role')";

         $userRightArray = array('ReportingView' , 'DeclarationEdit', 'ExportContactEdit', 'OrderStatusView', 'OrderEdit', 'StockBalancesView', 'PointOfSalesEdit', 'SalesEdit', 'VisitorEdit', 'UserPasswordEdit', 'UserRightsEdit', 'UserEdit', 'AllocationEdit', 'StudentEdit', 'DashboardView', 'DefectiveProductEdit', 'DefectiveStatusView', 'KIVEdit', 'AddOnProductEdit', 'StockAdjustmentEdit', 'DefectiveStatusView');

         foreach ($userRightArray as $key => $value) {
            
          $insert_sql1="INSERT into `user_right` (user_name, `right`) values ('$user_name', '$value')";
          //var_dump($insert_sql1);
         $result=mysqli_query($connection, $insert_sql1);
         }
        $result=mysqli_query($connection, $insert_sql);
     
        curlCall("admin/add-user?centre_name=".urlencode($user_name)."&email=".$email."&password=".$password."&country=".$country."&api_key=".$api_key."&role=".$helpdesk_role);
      
      $msg="Record inserted";
		 echo '<script>window.location.href = "/index.php?p=userright&user_name='.$user_name.'";</script>';
      } else {
         //$msg="All fields are required";
      }
   }
//}

$str=$_GET["s"];
$centre_name=$_SESSION["centre_name"];
$centre_code=$_SESSION["CentreCode"];

/* if ($_GET["s"]!="") {
   if (($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) {
      $browse_sql="SELECT * from `$table` where (user_name like '%$str%' or centre_name like '%$str%') and centre_name='$centre_name' order by user_name";
   }

   if (($_SESSION["UserType"]=="S")  || ($_SESSION["UserType"]=="H") || ($_SESSION["UserType"]=="C") || ($_SESSION["UserType"]=="R") || ($_SESSION["UserType"]=="CM") || ($_SESSION["UserType"]=="T")) {
      $browse_sql="SELECT * from `$table` where (user_name like '%$str%' or centre_name like '%$str%') order by user_name";
   }
} else {
   if (($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) {
      $browse_sql="SELECT * from `$table` where centre_code='$centre_code' order by user_name";
	  //echo $browse_sql;
   }

   if (($_SESSION["UserType"]=="S")  || ($_SESSION["UserType"]=="H") || ($_SESSION["UserType"]=="C") || ($_SESSION["UserType"]=="R") || ($_SESSION["UserType"]=="CM") || ($_SESSION["UserType"]=="T")) {
      $browse_sql="SELECT * from `$table` order by user_name";
   }
} */
?>