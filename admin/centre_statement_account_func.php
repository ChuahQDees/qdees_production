<?php
   session_start();
   $table="centre_statement_account";
   $key_field="id";
   $msg="";
   $mode=$_GET["mode"];
   $get_sha1_id =$_GET["id"];

   function isRecordFound($table, $key_field, $key_value) {
      global $connection;

      $sql="SELECT $key_field from `$table` where sha1($key_field)='$key_value'";
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
         $del_sql="UPDATE `$table` set deleted=1 where sha1(id)='$get_sha1_id'";
         $result=mysqli_query($connection, $del_sql);
         $msg="Record deleted";
      }
   }

   if ($mode=="SAVE") {
      foreach ($_POST as $key=>$value) {
         $$key=$value;
      }
      $create_date = date("Y/m/d");
      $year=$_SESSION['Year'];
      $created_by=$_SESSION["UserName"];

      if (isRecordFound($table, $key_field, $hidden_id)) {
         if ($centre_code!="") {
            $update_sql="UPDATE `$table` set `centre_code`='$centre_code',`effective_date`='$effective_date',`company_name`='$company_name',`total_outstanding_stock`='$total_outstanding_stock',`details`='$details',`company_name_others`='$company_name_others',`total_outstanding_others`='$total_outstanding_others',`royalty_january`='$royalty_january',`royalty_february`='$royalty_february',`royalty_march`='$royalty_march',`royalty_april`='$royalty_april',`royalty_may`='$royalty_may',`royalty_june`='$royalty_june',`royalty_july`='$royalty_july',`royalty_august`='$royalty_august',`royalty_september`='$royalty_september',`royalty_october`='$royalty_october',`royalty_november`='$royalty_november',`royalty_december`='$royalty_december',`updated_by`='$created_by',`update_date`='$create_date' where sha1(id)='$hidden_id'";
         
            $result=mysqli_query($connection, $update_sql);
            $msg="Record updated";
         } else {
            $msg="All fields are required";
         }
      } else {
         if ($centre_code!="") {
            // $del_sql="UPDATE `$table` set deleted=1 where centre_code='$centre_code'";
            //  $result=mysqli_query($connection, $del_sql);

            $insert_sql="INSERT into `$table` (`centre_code`, `effective_date`, `company_name`, `total_outstanding_stock`, `details`, `company_name_others`, `total_outstanding_others`, `royalty_january`, `royalty_february`, `royalty_march`, `royalty_april`, `royalty_may`, `royalty_june`, `royalty_july`, `royalty_august`, `royalty_september`, `royalty_october`, `royalty_november`, `royalty_december`, `created_by`, `create_date`, `year`) values ('$centre_code', '$effective_date', '$company_name', '$total_outstanding_stock', '$details', '$company_name_others', '$total_outstanding_others', '$royalty_january', '$royalty_february', '$royalty_march', '$royalty_april', '$royalty_may', '$royalty_june', '$royalty_july', '$royalty_august', '$royalty_september', '$royalty_october', '$royalty_november', '$royalty_december', '$created_by', '$create_date', '$year')";
            
            $result=mysqli_query($connection, $insert_sql);
            if (mysqli_affected_rows($connection)>0) {
               $msg="Record inserted";
            } else {
               $msg="Record insert failed";
            }

         } else {
            $msg="All fields are required";
         }
      }
   }

   $company_name=$_GET["company_name"];
   $date_from=$_GET["date_from"];
   $date_to=$_GET["date_to"];
   $year=$_SESSION['Year'];

   //$browse_sql="SELECT * from `$table` where deleted=0 and year='$year' and centre_code='".$_SESSION["CentreCode"]."' order by class";
   $browse_sql="SELECT s.*, c.company_name as centre_name from `$table` s inner join centre c on c.centre_code=s.centre_code where s.deleted=0 and s.year='$year'";
   if($company_name!=""){
      $browse_sql.=" and c.company_name='$company_name'";
   } 
   if($date_from!="" && $date_to!=""){
      $browse_sql.=" and s.effective_date between '$date_from' and '$date_to'";
   } 
   if($_SESSION["UserType"]=="A"){
      $centre_code=$_SESSION["CentreCode"];
      if($centre_code!=""){
         $browse_sql.=" and c.centre_code='$centre_code'";
      }
   }
   
   $browse_sql.="order by s.id desc";
?>