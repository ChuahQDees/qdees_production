<?php
session_start();
if ($_SESSION["isLogin"]==1) {
 if (($_SESSION["UserType"]=="A") & (hasRightGroupXOR($_SESSION["UserName"], "SalesEdit|SalesView"))) {
      include_once("mysql.php");
	  include_once("functions.php");
      foreach ($_GET as $key=>$value) {
         $key=$value;
      }
$time_date= date("Y-m-d H:i:s");
$mode=isset($_GET["mode"]) ? $_GET["mode"] : '';
$centre_code = $_SESSION["CentreCode"];
$get_sha1_fee_id=isset($_POST['fee_id']) ? $_POST['fee_id'] : '';
$from_date= isset($_POST['from_date']) ? $_POST['from_date'] : '';

$extend_year = isset($_POST['from_date']) ? getYearFromMonth(date('Y',strtotime($from_date)),date('m',strtotime($from_date))) : '';

$from_date=convertDate2ISO($from_date);

      if (isset($mode) and $mode=="EDIT" && $get_sha1_fee_id!="") {
		 if(isset($_POST['fees_structure'],$_POST['subject'],$_POST['programme_package'],$_POST['from_date'],$_POST['to_date'],$_POST['school_adjust'])) {
			$fees_structure=mysqli_real_escape_string($connection, $_POST['fees_structure']);
        $save_sql="update  fee_structure set 
			centre_code='$centre_code',
			fees_structure='$fees_structure',
			subject='".$_POST['subject']."',			
			programme_package='".$_POST['programme_package']."',
			from_date='".$_POST['from_date']."',			
			to_date='".$_POST['to_date']."', 
			school_default='".$_POST['school_default']."', 
			school_adjust='".$_POST['school_adjust']."', 
			school_collection='".$_POST['school_collection']."',
			multimedia_default='".$_POST['multimedia_default']."',
			multimedia_adjust='".$_POST['multimedia_adjust']."',
			multimedia_collection='".$_POST['multimedia_collection']."',
			facility_default='".$_POST['facility_default']."',
			facility_adjust='".$_POST['facility_adjust']."',
			facility_collection='".$_POST['facility_collection']."',
			enhanced_default='".$_POST['enhanced_default']."',
			enhanced_adjust='".$_POST['enhanced_adjust']."',
			enhanced_collection='".$_POST['enhanced_collection']."',
			school_total_d='".$_POST['school_total_d']."',
			school_total_f='".$_POST['school_total_f']."',
			iq_math_default='".$_POST['iq_math_default']."',
			iq_math_adjust='".$_POST['iq_math_adjust']."',
			iq_math_collection='".$_POST['iq_math_collection']."',
			mandarin_default='".$_POST['mandarin_default']."',
			mandarin_adjust='".$_POST['mandarin_adjust']."',
			mandarin_collection='".$_POST['mandarin_collection']."',
			international_default='".$_POST['international_default']."',
			international_adjust='".$_POST['international_adjust']."',
			international_collection='".$_POST['international_collection']."',
			total_default_c='".$_POST['total_default_c']."',
			total_adjust_c='".$_POST['total_adjust_c']."',
			integrated_default='".$_POST['integrated_default']."',
			integrated_adjust='".$_POST['integrated_adjust']."',
			integrated_collection='".$_POST['integrated_collection']."',
			link_default='".$_POST['link_default']."',
			link_adjust='".$_POST['link_adjust']."',
			link_collection='".$_POST['link_collection']."',
			mandarin_m_defauft='".$_POST['mandarin_m_defauft']."',
			mandarin_m_adjust='".$_POST['mandarin_m_adjust']."',
			mandarin_m_collection='".$_POST['mandarin_m_collection']."',
			total_default_d='".$_POST['total_default_d']."',
			total_adjust_d='".$_POST['total_adjust_d']."',
			basic_default='".$_POST['basic_default']."',
			basic_adjust='".$_POST['basic_adjust']."',
			basic_collection='".$_POST['basic_collection']."',
			afternoon_robotic_default='".$_POST['afternoon_robotic_default']."',
			afternoon_robotic_adjust='".$_POST['afternoon_robotic_adjust']."',
			afternoon_robotic_collection='".$_POST['afternoon_robotic_collection']."',
			registration_default='".$_POST['registration_default']."',
			registration_adjust='".$_POST['registration_adjust']."',
			mobile_default='".$_POST['mobile_default']."',
			mobile_adjust='".$_POST['mobile_adjust']."',
			mobile_collection='".$_POST['mobile_collection']."',
			insurance_default='".$_POST['insurance_default']."',
			insurance_adjust='".$_POST['insurance_adjust']."',
			uniform_default='".$_POST['uniform_default']."',
			uniform_adjust='".$_POST['uniform_adjust']."',
			gymwear_default='".$_POST['gymwear_default']."',
			gymwear_adjust='".$_POST['gymwear_adjust']."',
			q_dees_default='".$_POST['q_dees_default']."',
			q_dees_adjust='".$_POST['q_dees_adjust']."',
			q_bag_default='".$_POST['q_bag_default']."',
			q_bag_adjust='".$_POST['q_bag_adjust']."',
			total_default_f='".$_POST['total_default_f']."',
			total_adjust_f='".$_POST['total_adjust_f']."',
			islam_default='".$_POST['islam_default']."',
			islam_adjust='".$_POST['islam_adjust']."',
			islam_collection='".$_POST['islam_collection']."',
			robotic_plus_default='".$_POST['robotic_plus_default']."',
			robotic_plus_adjust='".$_POST['robotic_plus_adjust']."',
			robotic_plus_collection='".$_POST['robotic_plus_collection']."',
			remarks='".$_POST['remarks']."',
			school_default_perent='".$_POST['school_default_perent']."',
			multimedia_default_perent='".$_POST['multimedia_default_perent']."',
			facility_default_perent='".$_POST['facility_default_perent']."',
			enhanced_default_perent='".$_POST['enhanced_default_perent']."',
			iq_math_default_perent='".$_POST['iq_math_default_perent']."',
			mandarin_default_perent='".$_POST['mandarin_default_perent']."',
			international_perent='".$_POST['international_perent']."',
			link_default_perent='".$_POST['link_default_perent']."',
			mandarin_m_default_perent='".$_POST['mandarin_m_default_perent']."',
			basic_default_perent='".$_POST['basic_default_perent']."',
			mobile_perent='".$_POST['mobile_perent']."',
			afternoon_robotic_default_perent='".$_POST['afternoon_robotic_default_perent']."',
			robotic_plus_default_perent='".$_POST['robotic_plus_default_perent']."',
			islam_default_perent='".$_POST['islam_default_perent']."',
			extend_year='$extend_year',
			submission_date='$time_date',
			status='Pending'";
		
		// $save_sql .= isset($_POST['deposit_locked']) ? ", deposit_locked='".$_POST['deposit_locked']."'" : ", deposit_locked='N'"; 
		// $save_sql .= isset($_POST['placement_locked']) ? ", placement_locked='".$_POST['placement_locked']."'" : ", placement_locked='N'"; 
		// $save_sql .= isset($_POST['registration_locked']) ? ", registration_locked='".$_POST['registration_locked']."'" : ", registration_locked='N'"; 
		// $save_sql .= isset($_POST['mobile_app_locked']) ? ", mobile_app_locked='".$_POST['mobile_app_locked']."'" : ", mobile_app_locked='N'"; 
		 $save_sql .= "where sha1(id)='$get_sha1_fee_id'";
	
		$result=mysqli_query($connection, $save_sql);
		if($result){
			//echo "<script type='text/javascript'>window.top.location='index.php?p=fee&msg=Record Updated';</script>";
			$msg="Record Updated";
		}else{
			$msg="Failed to save data";
		}
      }else{
		  
	  }
	  
	  }
	  if (isset($mode) and $mode=="SAVE") {
		  $from_date= $_POST['from_date'];
		  $time_date= date("Y-m-d H:i:s");
		  $from_date=convertDate2ISO($from_date);
		  $extend_year = getYearFromMonth(date('Y',strtotime($from_date)),date('m',strtotime($from_date)));
		  $feeStatus = "Approved";

		  if ($_POST['alertPop'] != ""){
			$feeStatus = "Pending";
		  }

		  $fees_structure=mysqli_real_escape_string($connection, $_POST['fees_structure']);
		   if(isset($_POST['fees_structure'],$_POST['subject'],$_POST['programme_package'],$_POST['from_date'],$_POST['to_date'],$_POST['school_adjust'])) {
		   $save_sql="INSERT INTO  fee_structure ( centre_code,fees_structure,subject,programme_package,from_date,to_date,school_default,school_adjust,school_collection,multimedia_default,multimedia_adjust,multimedia_collection,facility_default,facility_adjust,facility_collection,enhanced_default,enhanced_adjust,enhanced_collection,school_total_d,school_total_f,iq_math_default,iq_math_adjust,iq_math_collection,mandarin_default,mandarin_adjust,mandarin_collection,international_default,international_adjust,international_collection,total_default_c,total_adjust_c,integrated_default,integrated_adjust,integrated_collection,link_default,link_adjust,link_collection,mandarin_m_defauft,mandarin_m_adjust,mandarin_m_collection,total_default_d,total_adjust_d,basic_default,basic_adjust,basic_collection,afternoon_robotic_default,
		   afternoon_robotic_adjust,afternoon_robotic_collection,registration_default,registration_adjust,mobile_default,mobile_adjust,mobile_collection,insurance_default,insurance_adjust,uniform_default,uniform_adjust,gymwear_default,gymwear_adjust,q_dees_default,q_dees_adjust,q_bag_default,q_bag_adjust,total_default_f,total_adjust_f,islam_default,islam_adjust,islam_collection,robotic_plus_default,robotic_plus_adjust,robotic_plus_collection,remarks,school_default_perent,multimedia_default_perent,facility_default_perent,enhanced_default_perent,iq_math_default_perent,mandarin_default_perent,international_perent,mandarin_m_default_perent,integrated_default_perent,link_default_perent,basic_default_perent,mobile_perent,afternoon_robotic_default_perent,robotic_plus_default_perent,islam_default_perent, extend_year, submission_date, status) VALUES ('$centre_code','$fees_structure','".$_POST['subject']."','".$_POST['programme_package']."','".$_POST['from_date']."','".$_POST['to_date']."','".$_POST['school_default']."','".$_POST['school_adjust']."','".$_POST['school_collection']."','".$_POST['multimedia_default']."','".$_POST['multimedia_adjust']."','".$_POST['multimedia_collection']."','".$_POST['facility_default']."','".$_POST['facility_adjust']."','".$_POST['facility_collection']."','".$_POST['enhanced_default']."','".$_POST['enhanced_adjust']."','".$_POST['enhanced_collection']."','".$_POST['school_total_d']."','".$_POST['school_total_f']."','".$_POST['iq_math_default']."','".$_POST['iq_math_adjust']."','".$_POST['iq_math_collection']."','".$_POST['mandarin_default']."','".$_POST['mandarin_adjust']."','".$_POST['mandarin_collection']."','".$_POST['international_default']."','".$_POST['international_adjust']."','".$_POST['international_collection']."','".$_POST['total_default_c']."','".$_POST['total_adjust_c']."','".$_POST['integrated_default']."','".$_POST['integrated_adjust']."','".$_POST['integrated_collection']."','".$_POST['link_default']."','".$_POST['link_adjust']."','".$_POST['link_collection']."','".$_POST['mandarin_m_defauft']."','".$_POST['mandarin_m_adjust']."','".$_POST['mandarin_m_collection']."','".$_POST['total_default_d']."','".$_POST['total_adjust_d']."','".$_POST['basic_default']."','".$_POST['basic_adjust']."','".$_POST['basic_collection']."','".$_POST['afternoon_robotic_default']."','".$_POST['afternoon_robotic_adjust']."','".$_POST['afternoon_robotic_collection']."','".$_POST['registration_default']."','".$_POST['registration_adjust']."','".$_POST['mobile_default']."','".$_POST['mobile_adjust']."','".$_POST['mobile_collection']."','".$_POST['insurance_default']."','".$_POST['insurance_adjust']."','".$_POST['uniform_default']."','".$_POST['uniform_adjust']."','".$_POST['gymwear_default']."','".$_POST['gymwear_adjust']."','".$_POST['q_dees_default']."','".$_POST['q_dees_adjust']."','".$_POST['q_bag_default']."','".$_POST['q_bag_adjust']."','".$_POST['total_default_f']."','".$_POST['total_adjust_f']."','".$_POST['islam_default']."','".$_POST['islam_adjust']."','".$_POST['islam_collection']."','".$_POST['robotic_plus_default']."','".$_POST['robotic_plus_adjust']."','".$_POST['robotic_plus_collection']."','".$_POST['remarks']."','".$_POST['school_default_perent']."','".$_POST['multimedia_default_perent']."','".$_POST['facility_default_perent']."','".$_POST['enhanced_default_perent']."','".$_POST['iq_math_default_perent']."','".$_POST['mandarin_default_perent']."','".$_POST['international_perent']."','".$_POST['mandarin_m_default_perent']."','".$_POST['integrated_default_perent']."','".$_POST['link_default_perent']."','".$_POST['basic_default_perent']."','".$_POST['mobile_perent']."','".$_POST['afternoon_robotic_default_perent']."','".$_POST['robotic_plus_default_perent']."','".$_POST['islam_default_perent']."','$extend_year', '$time_date', '$feeStatus')";	  

			$result=mysqli_query($connection, $save_sql);

			$action_id = mysqli_insert_id($connection);

			$centre_data = mysqli_fetch_array(mysqli_query($connection,"SELECT `company_name` FROM `centre` WHERE `centre_code` = '".$_SESSION['CentreCode']."'"));

			$n_data['action_id'] = $action_id;
			$n_data['send_to'] = 'hq';
			$n_data['send_from'] = $_SESSION['CentreCode'];
			$n_data['is_center_read'] = 1;
			$n_data['is_hq_read'] = 0;
			$n_data['type'] = "fee_structure_adjusted";
			$n_data['subject'] = $centre_data['company_name'].", ".$fees_structure.' has been adjusted.'; 
			notification($n_data);

		if($result){
			//echo "<script type='text/javascript'>window.top.location='index.php?p=fee_structure_setting&msg=Record saved';</script>";
			//$msg="Record saved";
			$msg="Your respond has been submitted successfully";
		}else{
			$msg="Failed to save data";
		}
		   }
	  }
	   $get_sha1_id=$_GET['id'];
	  if (isset($mode) and $mode=="DUPLICATE" AND $get_sha1_id != '') {
	/* 	   $save_sql="INSERT INTO fee_structure (`centre_code`, `fees_structure`, `subject`, `programme_package`, `from_date`, `to_date`, `school_default`, `school_adjust`, `school_collection`, `multimedia_default`, `multimedia_adjust`, `multimedia_collection`, `facility_default`, `facility_adjust`, `facility_collection`, `enhanced_default`, `enhanced_adjust`, `enhanced_collection`, `school_total_d`, `school_total_f`, `iq_math_default`, `iq_math_adjust`, `iq_math_collection`, `mandarin_default`, `mandarin_adjust`, `mandarin_collection`, `international_default`, `international_adjust`, `international_collection`, `total_default_c`, `total_adjust_c`, `integrated_default`, `integrated_adjust`, `integrated_collection`, `link_default`, `link_adjust`, `link_collection`, `mandarin_m_defauft`, `mandarin_m_adjust`, `mandarin_m_collection`, `total_default_d`, `total_adjust_d`, `basic_default`, `basic_adjust`, `basic_collection`, `registration_default`, `registration_adjust`, `mobile_default`, `mobile_adjust`, `mobile_collection`, `insurance_default`, `insurance_adjust`, `uniform_default`, `uniform_adjust`, `gymwear_default`, `gymwear_adjust`, `q_dees_default`, `q_dees_adjust`, `q_bag_default`, `q_bag_adjust`, `total_default_f`, `total_adjust_f`, `islam_default`, `islam_adjust`, `remarks`, `school_default_perent`, `multimedia_default_perent`, `facility_default_perent`, `enhanced_default_perent`, `iq_math_default_perent`, `mandarin_default_perent`, `international_perent`, `integrated_default_perent`, `link_default_perent`, `mandarin_m_default_perent`, `basic_default_perent`, `mobile_perent`, `extend_year`, `status`, `submission_date`) SELECT  `centre_code`, `fees_structure`, `subject`, `programme_package`, `from_date`, `to_date`, `school_default`, `school_adjust`, `school_collection`, `multimedia_default`, `multimedia_adjust`, `multimedia_collection`, `facility_default`, `facility_adjust`, `facility_collection`, `enhanced_default`, `enhanced_adjust`, `enhanced_collection`, `school_total_d`, `school_total_f`, `iq_math_default`, `iq_math_adjust`, `iq_math_collection`, `mandarin_default`, `mandarin_adjust`, `mandarin_collection`, `international_default`, `international_adjust`, `international_collection`, `total_default_c`, `total_adjust_c`, `integrated_default`, `integrated_adjust`, `integrated_collection`, `link_default`, `link_adjust`, `link_collection`, `mandarin_m_defauft`, `mandarin_m_adjust`, `mandarin_m_collection`, `total_default_d`, `total_adjust_d`, `basic_default`, `basic_adjust`, `basic_collection`, `registration_default`, `registration_adjust`, `mobile_default`, `mobile_adjust`, `mobile_collection`, `insurance_default`, `insurance_adjust`, `uniform_default`, `uniform_adjust`, `gymwear_default`, `gymwear_adjust`, `q_dees_default`, `q_dees_adjust`, `q_bag_default`, `q_bag_adjust`, `total_default_f`, `total_adjust_f`, `islam_default`, `islam_adjust`, `remarks`, `school_default_perent`, `multimedia_default_perent`, `facility_default_perent`, `enhanced_default_perent`, `iq_math_default_perent`, `mandarin_default_perent`, `international_perent`, `integrated_default_perent`, `link_default_perent`, `mandarin_m_default_perent`, `basic_default_perent`, `mobile_perent`, `extend_year`, 'Pending', '".$time_date."' FROM fee_structure WHERE sha1(id) ='$get_sha1_id'";   
			$result=mysqli_query($connection, $save_sql);
			$last_id = mysqli_insert_id($connection);
			
			$clone_sql="SELECT * from `fee_structure` where id='$last_id'";
			$clone_result=mysqli_query($connection, $clone_sql);
			$select_row=mysqli_fetch_assoc($clone_result);
			$new_fees_structure = "Copy of ".$select_row['fees_structure'];
			$clone_save_sql="update fee_structure set fees_structure='$new_fees_structure' WHERE id = $last_id";
			mysqli_query($connection, $clone_save_sql);
			
		if($result){
			echo "<script type='text/javascript'>window.top.location='index.php?p=fee_structure_setting&mode=DUPLICATE&msg=Record duplicated';</script>";
		
		}else{
			$msg="Failed to duplicated data";
		}
		 */   
	  }
	  $get_sha1_id=$_GET['id'];
	  if (isset($mode) and $mode=="DEL") {
		  $save_sql="DELETE from `fee_structure` where sha1(id)='$get_sha1_id'";
			$result=mysqli_query($connection, $save_sql);
		if($result){
			//echo "<script type='text/javascript'>window.top.location='index.php?p=fee_structure_setting&msg=Record saved';</script>";
			$msg="Record deleted";
		}else{
			$msg="Failed to save data";
		}
		   
	  }
	  
	  $edit_sql="SELECT * from `fee_structure` where sha1(id)='$get_sha1_id'";
	 
      $result=mysqli_query($connection, $edit_sql);
      $edit_row=mysqli_fetch_assoc($result);

	  	//Get Fee structure listing

		$yearSession = $_SESSION['Year'];

		$sql = "SELECT term_num, term_start, term_end from schedule_term WHERE year = '$yearSession' AND centre_code = '$centre_code'";
		$result = mysqli_query($connection, $sql);
		$sdate = ""; //Start Date
		$edate = "asd"; //End Date
		$term5 = false;
		
		while ($row = mysqli_fetch_assoc($result)) {
			if ($row['term_num'] == "1"){
				$sdate = $row['term_start'];
			}else if ($row['term_num'] == "4" && $term5 == false){
				$edate = $row['term_end'];
			}

			if ($row['term_num'] == "5"){ //If there's a magical Term 5 for some reason, get that end date instead. Pray there's no 6
				$edate = $row['term_end'];
				$term5 = true;
			}
		}
?>
<style>
.page_title {
    position: absolute;
    right: 34px;
}
.uk-margin-right {
    margin-top: 40px;
}

</style>
<span>
    <span class="page_title"><img src="/images/title_Icons/Visitor Reg.png">Fees Structure Setting</span>
</span>
<div class="uk-margin-right">
   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color">Fees Structure Setting</h2>
   </div>
   <div class="uk-form uk-form-small">
<?php
if (($_SESSION["UserType"]=="A") & (hasRightGroupXOR($_SESSION["UserName"], "SalesEdit"))) {
	if(isset($_GET["mode"]) && $_GET["mode"]=="EDIT" && isset($_GET["id"])){
?>
   <form name="frmCourse" id="frmCourse" method="post" action="index.php?p=fee_structure_setting&mode=EDIT" enctype="multipart/form-data">
<?php
	}else{
?>
<form name="frmCourse" id="frmCourse" method="post" action="index.php?p=fee_structure_setting&mode=SAVE" enctype="multipart/form-data">
<?php
	}
}

$id = $_GET["id"];
$sql3="SELECT * from `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id=f.id where sha1(f.id) = '$id' limit 1";
//echo $sql3;
$result3=mysqli_query($connection, $sql3);
$row_exists=mysqli_num_rows($result3);

?>
      <div class="uk-grid">
		 <input type="hidden" id="fee_id" name="fee_id" value="<?php echo $_GET["id"]; ?>"/>
		  <div class="uk-width-medium-5-10">
			<table class="uk-table uk-table-small">
				<tr>
					<td  colspan="5" style="font-size:18px; font-weight:bold;border:none;">Part A (Centre Information)</td>
				</tr>
				<tr class="uk-text-small">
                  <td style="border:none; font-size:13px;" class="uk-width-3-10 uk-text-bold">Name of Fee Structure<span class="text-danger">*</span>:</td>
                  <td style="border:none;" class="uk-width-7-10">
                     <input class="uk-width-1-1 view-data" type="text" value="<?php echo ($_GET['mode'] == 'DUPLICATE') ? 'Copy of '.$edit_row['fees_structure'] : $edit_row['fees_structure']; ?>" name="fees_structure" id="fees_structure" placeholder="Example: EDP - Half Day - 2023/2024 - <Center Name>">
                     <span id="validationStructure"  style="color: red; display: none;">Please insert Name</span>
                  </td>
               </tr>
			   
			   <tr class="uk-text-small">
                  <td style="border:none;font-size:13px;" class="uk-width-3-10 uk-text-bold">Student Entry Level<span class="text-danger">*</span>:</td>
                  <td style="border:none;" class="uk-width-7-10">
				  <?php if($row_exists>0){ ?>
						  <input class="uk-width-1-1 view-data"  type="text" name="subject" id="subject" readonly value="<?php echo $edit_row['subject'] ?>">
						  <?php } else { ?>
							<select name="subject" id="subject" class="uk-width-1-1 subject_3 view-data">
                        <option value="">Please Select Student Entry Level</option>
                        <option value="EDP" <?php if($edit_row['subject']=='EDP') {echo 'selected';}?>>EDP</option>
                        <option value="QF1" <?php if($edit_row['subject']=='QF1') {echo 'selected';}?>>QF1</option>
                        <option value="QF2" <?php if($edit_row['subject']=='QF2') {echo 'selected';}?>>QF2</option>
                        <option value="QF3" <?php if($edit_row['subject']=='QF3') {echo 'selected';}?>>QF3</option>
                     </select>
                     <span id="validationSubject"  style="color: red; display: none;">Please select Student Entry Level</span>
						 <?php } ?>
                    
                  </td>
               </tr>
			   </table>
			   </div>
			    <div style="margin-top:45px;" class="uk-width-medium-5-10">
			   <table class="uk-table uk-table-small">
				<tr class="uk-text-small">
                  <td style="border:none;font-size:13px;" class="uk-width-3-10 uk-text-bold">Programme Package<span class="text-danger">*</span>:</td>
                  <td style="border:none;" class="uk-width-7-10">
				  <?php if($row_exists>0){ ?>
						  <input class="uk-width-1-1 view-data" type="text" name="programme_package" readonly id="programme_package" value="<?php echo $edit_row['programme_package'] ?>">
						  <?php } else { ?>
							<select name="programme_package" id="programme_package" class="uk-width-1-1 subject_3 view-data">
                        <option value="">Please Select Programme Package</option>
                        <option value="Full Day" <?php if($edit_row['programme_package']=='Full Day') {echo 'selected';}?>>Full Day</option>
                        <option value="Half Day" <?php if($edit_row['programme_package']=='Half Day') {echo 'selected';}?>>Half Day</option>
                        <option value="3/4 Day" <?php if($edit_row['programme_package']=='3/4 Day') {echo 'selected';}?>>3/4 Day</option>
                     </select>
                     <span id="validationprogramme_package"  style="color: red; display: none;">Please select Programme Package</span>
						 <?php } ?>
                     
                  </td>
               </tr>
            </table>
			<table class="uk-table uk-table-small">
				 <tr class="uk-text-small">
                  <td style="border:none;font-size:13px;" class="uk-width-3-10 uk-text-bold">Commencement Date<span class="text-danger">*</span></td>
				  <td style="border:none;" class="uk-width-7-10">
				  <select name="year_select" id="year_select" class="uk-width-1-1 subject_3 view-data" onChange="dateChange()">
				    <!--<option value="2024-2025">2024-2025</option>-->
                	<option value="2023-2024" selected>2023-2024</option>
                	<!--<option value="2022-2023" <?php if($edit_row['programme_package']=='Half Day') {echo 'selected';}?>>2022-2023</option>-->
                  </select> 
				  </td>
				  <!-- 
				  <td style="border:none;" class="uk-width-3-10">
                    <input style="width: 100%;" type="text" class="subject_3 view-data" <?php if($row_exists>0){ echo 'readonly'; } ?> name="from_date" id="from_date" placeholder="Start Date" <?php if($row_exists==0){ ?> data-uk-datepicker="{format: 'YYYY-MM-DD'}" <?php } ?> value="<?php echo $edit_row['from_date']?>" autocomplete="off">
					 <span id="validationdf"  style="color: red; display: none;">Please input Start Date</span>
                  </td>
				  <td style="border:none;" class="uuk-width-3-10">
                     <input style="width: 100%;" type="text" class="subject_3 view-data" <?php if($row_exists>0){ echo 'readonly'; } ?> name="to_date" id="to_date" placeholder="End Date" <?php if($row_exists==0){ ?> data-uk-datepicker="{format: 'YYYY-MM-DD'}" <?php } ?>  value="<?php echo $edit_row['to_date']?>" autocomplete="off">
					 <span id="validationdt"  style="color: red; display: none;">Please input End Date</span>
                  </td>
					-->
				  <td style="border:none;" class="uk-width-3-10" hidden>
                    <input style="width: 100%;" type="text" class="subject_3 view-data" <?php if($row_exists>0){ echo 'readonly'; } ?> name="from_date" id="from_date" placeholder="Start Date" <?php if($row_exists==0){ ?> data-uk-datepicker="{format: 'YYYY-MM-DD'}" <?php } ?> value="2023-03-06" autocomplete="off">
					 <span id="validationdf"  style="color: red; display: none;">Please input Start Date</span>
                  </td>
				  <td style="border:none;" class="uuk-width-3-10" hidden>
                     <input style="width: 100%;" type="text" class="subject_3 view-data" <?php if($row_exists>0){ echo 'readonly'; } ?> name="to_date" id="to_date" placeholder="End Date" <?php if($row_exists==0){ ?> data-uk-datepicker="{format: 'YYYY-MM-DD'}" <?php } ?>  value="2024-02-29" autocomplete="off">
					 <span id="validationdt"  style="color: red; display: none;">Please input End Date</span>
                  </td>
               </tr>
			   
				<script>
					function dateChange(){
						var yearChk = document.getElementById("year_select");
						var value = yearChk.value;
						var text = yearChk.options[yearChk.selectedIndex].text;

						if(text == "2024-2025"){
							document.getElementById("from_date").value = "2024-03-01";
							document.getElementById("to_date").value = "2024-12-30";
						}else{
							document.getElementById("from_date").value = "2023-03-06";
							document.getElementById("to_date").value = "2024-02-29";
						}
					}
				</script>
			</table>
			</div>
			 <div class="uk-width-medium-10-10">
				<table class="uk-table uk-table-small">
				<tr>
						<td colspan="5" style="font-size:18px; font-weight:bold;"></td>
					</tr>
					<tr>
						<td colspan="5" style="font-size:18px; font-weight:bold; border:none;">Part B (School Fees)</td>
					</tr>
					<input type="text" id="alertPop" name="alertPop" hidden>
					<tr class="">
						<td style="border:none;" style=" margin-top:50px;" class="uk-width-1-10 uk-text-bold"></td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10 ">
						<span>Default Fee</span>
					  </td>
					  <td style="border:none; " class="uk-width-1-10 de_d">
						<span class="default_1">Default Fee</span><br class="bru">
						<span class="default_1">Collection Pattern</span>
					  </td>
					  <td style="border:none;text-align: center;" class="uk-width-1-10">
						<span>Adjust Fee</span>
					  </td>
					  <td style="border:none;text-align: center;" class="uk-width-1-10">
						<span>Adjust Fee <br class="bru">Collection Pattern</span>
					  </td>
					</tr>
					
					<tr class="">
					   <td style=" margin-top:50px;border:none;" class="uk-width-1-10 uk-text-bold">School Fees<span class="text-danger">*</span>:</td>
					  <td style="border:none; text-align:center;" class="uk-width-1-10">
							
						 <input class="school_number view-data" style="font-weight:bold;border:none;" type="number" step="0.01" name="school_default" id="school_default" value="<?php echo $edit_row['school_default']?>" readonly><br>
						 <span id="validationsd"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
							
						 <input class="view-data" style="font-weight:bold;border:none;" type="text" name="school_default_perent" id="school_default_perent" value="<?php echo $edit_row['school_default_perent'] ?>" readonly><br>
						 <span id="validationschool_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none; text-align:center;" class="uk-width-1-10">
							
						 <input class="school_adjust view-data" type="number" step="0.01" <?php if($row_exists>0){ echo 'readonly'; } ?> name="school_adjust" id="school_adjust" value="<?php echo $edit_row['school_adjust'] ?>" <?php if ($mode != "EDIT" && $mode !="DUPLICATE"){ ?>disabled <?php } ?> onChange="autoDecimal('school_adjust')"><br>
						 <span id="validationschool_adjust" style="color: red; display: none;font-size:11px;margin-left: -65px;">Please input Adjust Fee</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
					  <?php if($row_exists>0){ ?>
						  <input  type="text" class="view-data" name="school_collection" readonly id="school_collection" value="<?php echo $edit_row['school_collection'] ?>">
						  <?php } else { ?>
						 <select name="school_collection" id="school_collection" class="view-data" style="width: 100%;" <?php if ($mode != "EDIT" && $mode !="DUPLICATE"){ ?>disabled <?php } ?>>
							<option value=""></option>
							<option value="Monthly" <?php if($edit_row['school_collection']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php if($edit_row['school_collection']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php if($edit_row['school_collection']=='Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php if($edit_row['school_collection']=='Annually') {echo 'selected';}?>>Annually</option>
						 </select><br>
						 <span id="validationsc"  style="color: red; display: none;font-size:11px;">Please select Collection Pattern</span>
						 <?php } ?>
					  </td>
					</tr>
					<tr class="">
					   <td style="margin-top:50px; border:none;" class="uk-width-1-10 uk-text-bold">Multimedia Fees<span class="text-danger">*</span>:</td>
					  <td style="border:none; text-align:center;" class="uk-width-1-10">
						 <input class="school_number view-data" style="font-weight:bold;border:none;" type="number" step="0.01" name="multimedia_default" id="multimedia_default" value="<?php echo $edit_row['multimedia_default']?>" readonly><br>
						 <span id="validationMd"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
							
						 <input class="view-data" type="text" style="font-weight:bold;border:none;" name="multimedia_default_perent" id="multimedia_default_perent" value="<?php echo $edit_row['multimedia_default_perent'] ?>" readonly><br>
						 <span id="validationmultimedia_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none; text-align:center;" class="uk-width-1-10">
							
						 <input class="school_adjust view-data" type="number" step="0.01" <?php if($row_exists>0){ echo 'readonly'; } ?> <?php if ($mode != "EDIT" && $mode !="DUPLICATE"){ ?>disabled <?php } ?> onChange="autoDecimal('multimedia_adjust')" name="multimedia_adjust" id="multimedia_adjust" value="<?php echo $edit_row['multimedia_adjust'] ?>"><br>
						 <span id="validationMa"  style="color: red; display: none;font-size:11px;margin-left: -65px;">Please input Adjust Fee</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
					  <?php if($row_exists>0){ ?>
						  <input class="view-data" type="text" name="multimedia_collection" readonly id="multimedia_collection" value="<?php echo $edit_row['multimedia_collection'] ?>">
						  <?php } else { ?>
							<select name="multimedia_collection" id="multimedia_collection" class="view-data" <?php if ($mode != "EDIT" && $mode !="DUPLICATE"){ ?>disabled <?php } ?> style="width: 100%;">
							<option value=""></option>
							<option value="Monthly" <?php if($edit_row['multimedia_collection']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php if($edit_row['multimedia_collection']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php if($edit_row['multimedia_collection']=='Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php if($edit_row['multimedia_collection']=='Annually') {echo 'selected';}?>>Annually</option>
						 </select><br>
						 <span id="validationMc"  style="color: red; display: none;font-size:11px;">Please select Collection Pattern</span>
						 <?php } ?>
						
					  </td>
					</tr>
					<tr class="">
					   <td style=" margin-top:50px; border:none;" class="uk-width-1-10 uk-text-bold">Facility Fees<span class="text-danger">*</span>:</td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">
							
						 <input class="school_number view-data" type="number" style="font-weight:bold;border:none;" name="facility_default" id="facility_default" value="<?php echo $edit_row['facility_default']?>" readonly><br>
						 <span id="validationfd"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
							
						 <input class="view-data" type="text" style="font-weight:bold;border:none;" name="facility_default_perent" id="facility_default_perent" value="<?php echo $edit_row['facility_default_perent'] ?>" readonly><br>
						 <span id="validationfacility_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">
							
						 <input class="school_adjust view-data" type="number" <?php if($row_exists>0){ echo 'readonly'; } ?> step="0.01" name="facility_adjust" id="facility_adjust" <?php if ($mode != "EDIT" && $mode !="DUPLICATE"){ ?>disabled <?php } ?> onChange="autoDecimal('facility_adjust')"  value="<?php echo $edit_row['facility_adjust'] ?>"><br>
						 <span id="validationfa"  style="color: red; display: none;font-size:11px;margin-left: -65px;">Please input Adjust Fee</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
					  <?php if($row_exists>0){ ?>
						  <input class="view-data" type="text" name="facility_collection" readonly id="facility_collection" value="<?php echo $edit_row['facility_collection'] ?>">
						  <?php } else { ?>
							<select name="facility_collection" id="facility_collection" class="view-data" style="width: 100%;" <?php if ($mode != "EDIT" && $mode !="DUPLICATE"){ ?>disabled <?php } ?>>
							<option value=""></option>
							<option value="Monthly" <?php if($edit_row['facility_collection']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php if($edit_row['facility_collection']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php if($edit_row['facility_collection']=='Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php if($edit_row['facility_collection']=='Annually') {echo 'selected';}?>>Annually</option>
						 </select><br>
						 <span id="validationfc"  style="color: red; display: none;font-size:11px;">Please select Collection Pattern</span>
						 <?php } ?>
						
					  </td>
					</tr>
					<tr class="">
					   <td style="margin-top:50px;border:none;" style="font-weight:bold;border:none;" class="uk-width-1-10 uk-text-bold">Enhanced Foundation International English<span class="text-danger">*</span>:</td>
					  <td style="border:none; text-align:center;" class="uk-width-1-10">							
						 <input class="school_number view-data" type="number" style="font-weight:bold;border:none;" step="0.01" name="enhanced_default" id="enhanced_default" value="<?php echo $edit_row['enhanced_default']?>" readonly><br>
						 <span id="validationed"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none; " class="uk-width-1-10">
							
						 <input class="view-data" type="text" style="font-weight:bold;border:none;" name="enhanced_default_perent" id="enhanced_default_perent" value="<?php echo $edit_row['enhanced_default_perent'] ?>" readonly><br>
						 <span id="validationenhanced_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none; text-align:center;" class="uk-width-1-10">
							
						 <input class="school_adjust view-data" type="number" <?php if($row_exists>0){ echo 'readonly'; } ?> step="0.01" name="enhanced_adjust" id="enhanced_adjust" <?php if ($mode != "EDIT" && $mode !="DUPLICATE"){ ?>disabled <?php } ?> onChange="autoDecimal('enhanced_adjust')" value="<?php echo $edit_row['enhanced_adjust'] ?>"><br>
						 <span id="validationea"  style="color: red; display: none;font-size:11px;margin-left: -65px;">Please input Adjust Fee</span>

					  </td>
					  <td style="border:none;" class="uk-width-1-10">
					  <?php if($row_exists>0){ ?>
						  <input class="view-data" type="text" name="enhanced_collection" readonly id="enhanced_collection" value="<?php echo $edit_row['enhanced_collection'] ?>">
						  <?php } else { ?>
							<select name="enhanced_collection" id="enhanced_collection" class="view-data" style="width: 100%;" <?php if ($mode != "EDIT" && $mode !="DUPLICATE"){ ?>disabled <?php } ?>>
							<option value=""></option>
							<option value="Monthly" <?php if($edit_row['enhanced_collection']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php if($edit_row['enhanced_collection']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php if($edit_row['enhanced_collection']=='Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php if($edit_row['enhanced_collection']=='Annually') {echo 'selected';}?>>Annually</option>
						 </select><br>
							<span id="validationec"  style="color: red; display: none;font-size:11px;">Please select Collection Pattern</span>
						 <?php } ?>
						
					  </td>
					</tr>
					<tr class="">
					   <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold">Total:</td>
					  <td style="text-align:center;" class="uk-width-1-10">							
						 <input class="view-data" type="number" style="font-weight:bold;border:none;" step="0.01" name="school_total_d" id="school_total_d" value="<?php echo $edit_row['school_total_d']?>" readonly>
					  </td>
					  <td class="uk-width-1-10"></td>
					  <td style="text-align:center;" class="uk-width-1-10">
							
						 <input class="view-data" type="number" step="0.01" readonly style="font-weight:bold;border:none;" name="school_total_f" id="school_total_f" value="<?php echo $edit_row['school_total_f'] ?>">
					  </td>
					  <td class="uk-width-1-10">
						<!--<select name="monthly" id="monthly" class="" style="width: 100%;">
							<option value=""></option>
							<option value="Monthly" <?php // if($edit_row['programme_package']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php // if($edit_row['programme_package']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php // if($edit_row['programme_package']==' Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php // if($edit_row['programme_package']==' Annually') {echo 'selected';}?>>Annually</option>
						 </select>-->
					  </td>
					</tr>
				<!--</table>
				<table class="uk-table uk-table-small">-->
					<tr>
						<td colspan="5" style="font-size:18px; font-weight:bold;border:none;">Part C (Enhanced Foundation Programme)</td>
					</tr>
					<tr class="">
					   <td style="margin-top:50px; border:none;" class="uk-width-1-10 uk-text-bold">IQ Math<span class="text-danger">*</span>:</td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10" readonly>							
						 <input class="math_default view-data" style="font-weight:bold;border:none;" type="number" step="0.01" name="iq_math_default" id="iq_math_default" value="<?php echo $edit_row['iq_math_default'] ?>" readonly><br>
						 <span id="validationiq"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
							
						 <input class="view-data" type="text" style="font-weight:bold;border:none;" name="iq_math_default_perent" id="iq_math_default_perent" value="<?php echo $edit_row['iq_math_default_perent'] ?>" readonly><br>
						 <span id="validationiq_math_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">
							
						 <input class="math_adjust view-data" type="number" <?php if($row_exists>0){ echo 'readonly'; } ?> step="0.01" name="iq_math_adjust" value="<?php echo $edit_row['iq_math_adjust'] ?>" id="iq_math_adjust" <?php if ($mode != "EDIT" && $mode !="DUPLICATE"){ ?>disabled <?php } ?> onChange="autoDecimal('iq_math_adjust')"><br>
						 <span id="validationma"  style="color: red; display: none;font-size:11px;margin-left: -65px;">Please input Adjust Fee</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
					  <?php if($row_exists>0){ ?>
						  <input class="view-data" type="text" name="iq_math_collection" readonly id="iq_math_collection" value="<?php echo $edit_row['iq_math_collection'] ?>">
						  <?php } else { ?>
							<select name="iq_math_collection" id="iq_math_collection" class="view-data" style="width: 100%;" <?php if ($mode != "EDIT" && $mode !="DUPLICATE"){ ?>disabled <?php } ?>>
							<option value=""></option>
							<option value="Monthly" <?php if($edit_row['iq_math_collection']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php if($edit_row['iq_math_collection']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php if($edit_row['iq_math_collection']=='Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php if($edit_row['iq_math_collection']=='Annually') {echo 'selected';}?>>Annually</option>
						 </select><br>
						 <span id="validationmc"  style="color: red; display: none;font-size:11px;">Please select Collection Pattern</span>
						 <?php } ?>
						
					  </td>
					</tr>
					<tr class="">
					   <td style="margin-top:50px;border:none;" class="uk-width-1-10 uk-text-bold">Mandarin<span class="text-danger">*</span>:</td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">							
						 <input class="math_default view-data" style="font-weight:bold;border:none;" type="number" step="0.01" name="mandarin_default" id="mandarin_default" value="<?php echo $edit_row['mandarin_default'] ?>" readonly><br>
						 <span id="validationmdf"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
							
						 <input class="view-data" type="text" style="font-weight:bold;border:none;" name="mandarin_default_perent" id="mandarin_default_perent" value="<?php echo $edit_row['mandarin_default_perent'] ?>" readonly><br>
						 <span id="validationmandarin_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">
							
						 <input class="math_adjust view-data" type="number" <?php if($row_exists>0){ echo 'readonly'; } ?> step="0.01" name="mandarin_adjust" id="mandarin_adjust" value="<?php echo $edit_row['mandarin_adjust'] ?>" <?php if ($mode != "EDIT" && $mode !="DUPLICATE"){ ?>disabled <?php } ?> onChange="autoDecimal('mandarin_adjust')"><br>
						 <span id="validationmda"  style="color: red; display: none;font-size:11px;margin-left: -65px;">Please input Adjust Fee</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
					  <?php if($row_exists>0){ ?>
						  <input class="view-data" type="text" name="mandarin_collection" readonly id="mandarin_collection" value="<?php echo $edit_row['mandarin_collection'] ?>">
						  <?php } else { ?>
							<select name="mandarin_collection" id="mandarin_collection" class="view-data" style="width: 100%;" <?php if ($mode != "EDIT" && $mode !="DUPLICATE"){ ?>disabled <?php } ?>>
							<option value=""></option>
							<option value="Monthly" <?php if($edit_row['mandarin_collection']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php if($edit_row['mandarin_collection']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php if($edit_row['mandarin_collection']=='Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php if($edit_row['mandarin_collection']=='Annually') {echo 'selected';}?>>Annually</option>
						 </select><br>
						 <span id="validationmdc"  style="color: red; display: none;font-size:11px;">Please select Collection Pattern</span>
						 <?php } ?>
						
					  </td>
					</tr>
					<tr class="">
					   <td style="margin-top:50px;border:none;" class="uk-width-1-10 uk-text-bold">International Art<span class="text-danger">*</span>:</td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">							
						 <input class="math_default view-data" style="font-weight:bold;border:none;" type="number" step="0.01" name="international_default" id="international_default" value="<?php echo $edit_row['international_default'] ?>" readonly><br>
						 <span id="validationind"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
							
						 <input class="view-data" type="text" style="font-weight:bold;border:none;" name="international_perent" id="international_perent" value="<?php echo $edit_row['mandarin_default_perent'] ?>" readonly><br>
						 <span id="validationinternational_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">
							
						 <input class="math_adjust view-data" type="number" <?php if($row_exists>0){ echo 'readonly'; } ?> step="0.01" name="international_adjust" id="international_adjust" value="<?php echo $edit_row['international_adjust'] ?>" <?php if ($mode != "EDIT" && $mode !="DUPLICATE"){ ?>disabled <?php } ?> onChange="autoDecimal('international_adjust')"><br>
						 <span id="validationina"  style="color: red; display: none;font-size:11px;margin-left: -65px;">Please input Adjust Fee</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
					  <?php if($row_exists>0){ ?>
						  <input class="view-data" type="text" name="international_collection" readonly id="international_collection" value="<?php echo $edit_row['international_collection'] ?>">
						  <?php } else { ?>
							<select name="international_collection" id="international_collection" class="view-data" style="width: 100%;" <?php if ($mode != "EDIT" && $mode !="DUPLICATE"){ ?>disabled <?php } ?>>
							<option value=""></option>
							<option value="Monthly" <?php if($edit_row['international_collection']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php if($edit_row['international_collection']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php if($edit_row['international_collection']=='Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php if($edit_row['international_collection']=='Annually') {echo 'selected';}?>>Annually</option>
						 </select><br>
						 <span id="validationinc"  style="color: red; display: none;font-size:11px;">Please select Collection Pattern</span>
						 <?php } ?>
						
					  </td>
					</tr>
					<tr class="">
					   <td style="margin-top:50px; border:none;" class="uk-width-1-10 uk-text-bold">Robotic Plus<span class="text-danger">*</span>:</td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10" readonly>							
						 <input class="math_default view-data" style="font-weight:bold;border:none;" type="number" step="0.01" name="robotic_plus_default" id="robotic_plus_default" value="<?php echo $edit_row['robotic_plus_default'] ?>" readonly><br>
						 <!-- <span id="validationrp"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span> -->
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
							
						 <input class="view-data" type="text" style="font-weight:bold;border:none;" name="robotic_plus_default_perent" id="robotic_plus_default_perent" value="<?php echo $edit_row['robotic_plus_default_perent'] ?>" readonly><br>
						 <span id="validationrobotic_plus_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">
							
						 <input class="math_adjust view-data" type="number" <?php if($row_exists>0){ echo 'readonly'; } ?> step="0.01" name="robotic_plus_adjust" id="robotic_plus_adjust" value="<?php echo $edit_row['robotic_plus_adjust'] ?>" <?php if ($mode != "EDIT" && $mode !="DUPLICATE"){ ?>disabled <?php } ?> onChange="autoDecimal('robotic_plus_adjust')"><br>
						 <span id="validationrp"  style="color: red; display: none;font-size:11px;margin-left: -65px;">Please input Adjust Fee</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
					  <?php if($row_exists>0){ ?>
						  <input class="view-data" type="text" name="robotic_plus_collection" readonly id="robotic_plus_collection" value="<?php echo $edit_row['robotic_plus_collection'] ?>">
						  <?php } else { ?>
							<select name="robotic_plus_collection" id="robotic_plus_collection" class="view-data" style="width: 100%;" <?php if ($mode != "EDIT" && $mode !="DUPLICATE"){ ?>disabled <?php } ?>>
							<option value=""></option>
							<option value="Monthly" <?php if($edit_row['robotic_plus_collection']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php if($edit_row['robotic_plus_collection']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php if($edit_row['robotic_plus_collection']=='Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php if($edit_row['robotic_plus_collection']=='Annually') {echo 'selected';}?>>Annually</option>
						 </select><br>
						 <span id="validationrpc"  style="color: red; display: none;font-size:11px;">Please select Collection Pattern</span>
						 <?php } ?>
						
					  </td>
					</tr>
					<tr class="">
					   <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold">Total:</td>
					  <td style="text-align:center;" class="uk-width-1-10">							
						 <input class="view-data" type="number" style="font-weight:bold;border:none;" step="0.01" name="total_default_c" id="total_default_c" value="<?php echo $edit_row['total_default_c'] ?>" readonly>
					  </td>
					  <td class="uk-width-1-10"></td>
					  <td style="text-align:center;" class="uk-width-1-10">
							
						 <input class="view-data" type="number" step="0.01" style="font-weight:bold;border:none;" readonly <?php if($row_exists>0){ echo 'readonly'; } ?> name="total_adjust_c" id="total_adjust_c" value="<?php echo $edit_row['total_adjust_c'] ?>">
					  </td>
					  <td class="uk-width-1-10">
						<!--<select name="monthly" id="monthly" class="" style="width: 100%;">
							<option value=""></option>
							<option value="Monthly" <?php // if($edit_row['programme_package']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php // if($edit_row['programme_package']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php // if($edit_row['programme_package']==' Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php // if($edit_row['programme_package']==' Annually') {echo 'selected';}?>>Annually</option>
						 </select>-->
					  </td>
					</tr>
				<!--</table>
				<table class="uk-table uk-table-small">-->
					<tr>
						<td colspan="5" style="font-size:18px; font-weight:bold;border:none;">Part D (Material Fees)</td>
					</tr>
					<tr class="">
					   <td style="width: 7.7%; margin-top:50px;border:none;" class="uk-width-1-10 uk-text-bold">Integrated Modules<span class="text-danger">*</span>:</td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">							
						 <input class="int_default view-data" type="number" style="font-weight:bold;border:none;" step="0.01" name="integrated_default" id="integrated_default" value="<?php echo $edit_row['integrated_default'] ?>" readonly><br>
						 <span id="validationitd"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
							
						 <input class="view-data" type="text" style="font-weight:bold;border:none;" step="0.01" name="integrated_default_perent" id="integrated_default_perent" value="<?php echo $edit_row['integrated_default_perent'] ?>" readonly><br>
						 <span id="validationintegrated_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">
							
						 <input class="integrated_adjust view-data" type="number" <?php if($row_exists>0){ echo 'readonly'; } ?> step="0.01" name="integrated_adjust" id="integrated_adjust" value="<?php echo $edit_row['integrated_adjust'] ?>" <?php if ($mode != "EDIT" && $mode !="DUPLICATE"){ ?>disabled <?php } ?> onChange="autoDecimal('integrated_adjust')"><br>
						 <span id="validationita"  style="color: red; display: none;font-size:11px;margin-left: -65px;">Please input Adjust Fee</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
					  <?php if($row_exists>0){ ?>
						  <input class="view-data" type="text" name="integrated_collection" readonly id="integrated_collection" value="<?php echo $edit_row['integrated_collection'] ?>">
						  <?php } else { ?>
							<select name="integrated_collection" id="integrated_collection" class="view-data" style="width: 100%;" <?php if ($mode != "EDIT" && $mode !="DUPLICATE"){ ?>disabled <?php } ?>>
							<option value=""></option>
							<option value="Monthly" <?php if($edit_row['integrated_collection']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php if($edit_row['integrated_collection']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php if($edit_row['integrated_collection']=='Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php if($edit_row['integrated_collection']=='Annually') {echo 'selected';}?>>Annually</option>
						 </select><br>
						 <span id="validationitc"  style="color: red; display: none;font-size:11px;">Please select Collection Pattern</span>
						 <?php } ?>
						
					  </td>
					</tr>
					<tr class="">
					   <td style="margin-top:50px;border:none;" class="uk-width-1-10 uk-text-bold">Link & Think Reading Series<span class="text-danger">*</span>:</td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">							
						 <input class="int_default view-data" type="number" style="font-weight:bold;border:none;" step="0.01" name="link_default" id="link_default" value="<?php echo $edit_row['link_default'] ?>" readonly ><br>
						 <span id="validationlnk"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
							
						 <input class="view-data" type="text" style="font-weight:bold;border:none;" name="link_default_perent" id="link_default_perent" value="<?php echo $edit_row['link_default_perent'] ?>" readonly><br>
						 <span id="validationlink_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">
							
						 <input class="integrated_adjust view-data" type="number" <?php if($row_exists>0){ echo 'readonly'; } ?> step="0.01" name="link_adjust" id="link_adjust" value="<?php echo $edit_row['link_adjust'] ?>" <?php if ($mode != "EDIT" && $mode !="DUPLICATE"){ ?>disabled <?php } ?> onChange="autoDecimal('link_adjust')"><br>
						 <span id="validationlna"  style="color: red; display: none;font-size:11px;margin-left: -65px;">Please input Adjust Fee</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
					  <?php if($row_exists>0){ ?>
						  <input class="view-data" type="text" name="link_collection" readonly id="link_collection" value="<?php echo $edit_row['link_collection'] ?>">
						  <?php } else { ?>
							<select name="link_collection" id="link_collection" class="view-data" style="width: 100%;" <?php if ($mode != "EDIT" && $mode !="DUPLICATE"){ ?>disabled <?php } ?>>
							<option value=""></option>
							<option value="Monthly" <?php if($edit_row['link_collection']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php if($edit_row['link_collection']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php if($edit_row['link_collection']=='Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php if($edit_row['link_collection']=='Annually') {echo 'selected';}?>>Annually</option>
						 </select><br>
							<span id="validationlnc"  style="color: red; display: none;font-size:11px;">Please select Collection Pattern</span>
						 <?php } ?>
						
					  </td>
					</tr>
					<tr class="">
					   <td style="margin-top:50px; border:none;" class="uk-width-1-10 uk-text-bold">Mandarin Modules<span class="text-danger">*</span>:</td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">							
						 <input class="int_default view-data" style="font-weight:bold;border:none;" type="number" step="0.01" name="mandarin_m_defauft" id="mandarin_m_defauft" value="<?php echo $edit_row['mandarin_m_defauft'] ?>" readonly><br>
						 <span id="validationmm"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
							
						 <input class="view-data" type="text" style="font-weight:bold;border:none;" name="mandarin_m_default_perent" id="mandarin_m_default_perent" value="<?php echo $edit_row['mandarin_m_default_perent'] ?>" readonly><br>
						 <span id="validationmandarin_m_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">
							
						 <input class="integrated_adjust view-data" type="number" <?php if($row_exists>0){ echo 'readonly'; } ?> step="0.01" name="mandarin_m_adjust" id="mandarin_m_adjust" value="<?php echo $edit_row['mandarin_m_adjust'] ?>" <?php if ($mode != "EDIT" && $mode !="DUPLICATE"){ ?>disabled <?php } ?> onChange="autoDecimal('mandarin_m_adjust')"><br>
						 <span id="validationmma"  style="color: red; display: none;font-size:11px;margin-left: -65px;">Please input Adjust Fee</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
					  <?php if($row_exists>0){ ?>
						  <input class="view-data" type="text" name="mandarin_m_collection" readonly id="mandarin_m_collection" value="<?php echo $edit_row['mandarin_m_collection'] ?>">
						  <?php } else { ?>
							<select name="mandarin_m_collection" id="mandarin_m_collection" class="view-data" style="width: 100%;" <?php if ($mode != "EDIT" && $mode !="DUPLICATE"){ ?>disabled <?php } ?>>
							<option value=""></option>
							<option value="Monthly" <?php if($edit_row['mandarin_m_collection']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php if($edit_row['mandarin_m_collection']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php if($edit_row['mandarin_m_collection']=='Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php if($edit_row['mandarin_m_collection']=='Annually') {echo 'selected';}?>>Annually</option>
						 </select><br>
						 <span id="validationmmc"  style="color: red; display: none;font-size:11px;">Please select Collection Pattern</span>
						 <?php } ?>
						
					  </td>
					</tr>
					<tr class="">
					   <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold">Total:</td>
					  <td style="text-align:center;" class="uk-width-1-10">							
						 <input class="view-data" type="number" style="font-weight:bold;border:none;" step="0.01" name="total_default_d" id="total_default_d" value="<?php echo $edit_row['total_default_d'] ?>" readonly>
					  </td>
					  <td class="uk-width-1-10"></td>
					  <td style="text-align:center;" class="uk-width-1-10">
							
						 <input class="view-data" type="number" style="font-weight:bold;border:none;" step="0.01" <?php if($row_exists>0){ echo 'readonly'; } ?> name="total_adjust_d" id="total_adjust_d" value="<?php echo $edit_row['total_adjust_d'] ?>">
					  </td>
					  <td class="uk-width-1-10">
						<!--<select name="monthly" id="monthly" class="" style="width: 100%;">
							<option value=""></option>
							<option value="Monthly" <?php // if($edit_row['programme_package']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php // if($edit_row['programme_package']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php // if($edit_row['programme_package']==' Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php // if($edit_row['programme_package']==' Annually') {echo 'selected';}?>>Annually</option>
						 </select>-->
					  </td>
					</tr>
				<!--</table>
				<table class="uk-table uk-table-small">-->
					<tr>
						<td colspan="5" style="font-size:18px; font-weight:bold;border:none;">Part E (Afternoon Programme)</td>
					</tr>
					<tr class="">
					   <td style="margin-top:50px;border:none;" class="uk-width-1-10 uk-text-bold">Basic Afternoon Programme<span class="text-danger">*</span>:</td>
					  <td style="text-align:center;border:none;" class="uk-width-1-10">							
						 <input class="view-data" type="number" step="0.01" style="font-weight:bold;border:none;" name="basic_default" id="basic_default" value="<?php echo $edit_row['basic_default'] ?>" readonly><br>
						 <span id="validationbd"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
							
						 <input class="view-data" type="text" style="font-weight:bold;border:none;" name="basic_default_perent" id="basic_default_perent" value="<?php echo $edit_row['basic_default_perent'] ?>" readonly><br>
						 <span id="validationbasic_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  </td>
					  <td style="text-align:center;border:none;" class="uk-width-1-10">							
						 <input class="view-data" type="number" step="0.01" <?php if($row_exists>0){ echo 'readonly'; } ?> name="basic_adjust" id="basic_adjust" value="<?php echo $edit_row['basic_adjust'] ?>" <?php if ($mode != "EDIT" && $mode !="DUPLICATE"){ ?>disabled <?php } ?> onChange="autoDecimal('basic_adjust')">
						 <br><span id="validationba"  style="color: red; display: none;font-size:11px;margin-left: -65px;">Please input Adjust Fee</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
					  <?php if($row_exists>0){ ?>
						  <input class="view-data" type="text" name="basic_collection" readonly id="basic_collection" value="<?php echo $edit_row['basic_collection'] ?>">
						  <?php } else { ?>
							<select name="basic_collection" id="basic_collection" class="view-data" style="width: 100%;" <?php if ($mode != "EDIT" && $mode !="DUPLICATE"){ ?>disabled <?php } ?>>
							<option value=""></option>
							<option value="Monthly" <?php if($edit_row['basic_collection']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php if($edit_row['basic_collection']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php if($edit_row['basic_collection']=='Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php if($edit_row['basic_collection']=='Annually') {echo 'selected';}?>>Annually</option>
							
						 </select><br>
						 <span id="validationbc"  style="color: red; display: none;font-size:11px;">Please select Collection Pattern</span>
						 <?php } ?>
						
					  </td>
					</tr>
					<tr class="">
					   <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold">Afternoon Programme + Robotics<span class="text-danger">*</span>:</td>
					  <td style="text-align:center;" class="uk-width-1-10">							
						 <input class="view-data" type="number" style="font-weight:bold;border:none;" step="0.01" name="afternoon_robotic_default" id="afternoon_robotic_default" value="<?php echo $edit_row['afternoon_robotic_default'] ?>" readonly><br>
						 <span id="validationard"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td class="uk-width-1-10">
							
						 <input class="view-data" type="text" style="font-weight:bold;border:none;" name="afternoon_robotic_default_perent" id="afternoon_robotic_default_perent" value="<?php echo $edit_row['afternoon_robotic_default_perent'] ?>" readonly><br>
						 <span id="validationafternoon_robotic_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  </td>
					  <td style="text-align:center;" class="uk-width-1-10">							
						 <input class="view-data" type="number" step="0.01" <?php if($row_exists>0){ echo 'readonly'; } ?> name="afternoon_robotic_adjust" id="afternoon_robotic_adjust" value="<?php echo $edit_row['afternoon_robotic_adjust'] ?>" <?php if ($mode != "EDIT" && $mode !="DUPLICATE"){ ?>disabled <?php } ?> onChange="autoDecimal('afternoon_robotic_adjust')"><br><span id="validationara"  style="color: red; display: none;font-size:11px;margin-left: -65px;">Please input Adjust Fee</span>
					  </td>
					  <td class="uk-width-1-10">
					  <?php if($row_exists>0){ ?>
						  <input class="view-data" type="text" name="afternoon_robotic_collection" readonly id="afternoon_robotic_collection" value="<?php echo $edit_row['afternoon_robotic_collection'] ?>">
						  <?php } else { ?>
							<select name="afternoon_robotic_collection" id="afternoon_robotic_collection" class="view-data" style="width: 100%;" <?php if ($mode != "EDIT" && $mode !="DUPLICATE"){ ?>disabled <?php } ?>><br><span id="validationara"  style="color: red; display: none;font-size:11px;margin-left: -65px;">
							<option value=""></option>
							<option value="Monthly" <?php if($edit_row['afternoon_robotic_collection']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php if($edit_row['afternoon_robotic_collection']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php if($edit_row['afternoon_robotic_collection']=='Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php if($edit_row['afternoon_robotic_collection']=='Annually') {echo 'selected';}?>>Annually</option>
							
						 </select><br>
						 <span id="validationbc"  style="color: red; display: none;font-size:11px;">Please select Collection Pattern</span>
						 <?php } ?>
						
					  </td>
					</tr>
					
				<!--</table>
				<table class="uk-table uk-table-small">-->
					<tr>
						<td colspan="5" style="font-size:18px; font-weight:bold;border:none;">Part F (Upon Registration)</td>
					</tr>
					<tr class="">
					   <td style="margin-top:50px; border:none;" class="uk-width-1-10 uk-text-bold">Mobile Application<span class="text-danger">*</span>:</td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">							
						 <input class="regist_default view-data" step="0.01" type="number" style="font-weight:bold;border:none;" name="mobile_default" id="mobile_default" value="<?php echo $edit_row['mobile_default'] ?>" readonly><br>
						 <span id="validationmdi"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
							
						 <input class="view-data" type="text" name="mobile_perent" id="mobile_perent" style="font-weight:bold;border:none;" value="<?php echo $edit_row['mobile_perent'] ?>" readonly><br>
						 <span id="validationmobile_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">
							
						 <input class="registration_adjust view-data" step="0.01" <?php if($row_exists>0){ echo 'readonly'; } ?> type="number" name="mobile_adjust" id="mobile_adjust" <?php if ($mode != "EDIT" && $mode !="DUPLICATE"){ ?>disabled <?php } ?> onChange="autoDecimal('mobile_adjust')" value="<?php echo $edit_row['mobile_adjust'] ?>"><br>
						 <span id="validationmba"  style="color: red; display: none;font-size:11px;margin-left: -65px;">Please input Adjust Fee</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
					  <?php if($row_exists>0){ ?>
						  <input class="view-data" type="text" name="mobile_collection" readonly id="mobile_collection" value="<?php echo $edit_row['mobile_collection'] ?>">
						  <?php } else { ?>
							<select name="mobile_collection" id="mobile_collection" class="view-data" style="width: 100%;" <?php if ($mode != "EDIT" && $mode !="DUPLICATE"){ ?>disabled <?php } ?>>
							<option value=""></option>
							<option value="Monthly" <?php if($edit_row['mobile_collection']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php if($edit_row['mobile_collection']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php if($edit_row['mobile_collection']=='Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php if($edit_row['mobile_collection']=='Annually') {echo 'selected';}?>>Annually</option>
							
						 </select><br>
						 <span id="validationmbc"  style="color: red; display: none;font-size:11px;">Please select Collection Pattern</span>
						 <?php } ?>
						
					  </td>
					</tr>
					<tr class="">
					   <td style="margin-top:50px; border:none;" class="uk-width-1-10 uk-text-bold">Registration<span class="text-danger">*</span>:</td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10" >							
						 <input class="regist_default view-data" step="0.01" style="font-weight:bold;border:none;" type="number" name="registration_default" id="registration_default" value="<?php echo $edit_row['registration_default'] ?>" readonly><br>
						 <span id="validationrd"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10"></td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">
							
						 <input class="registration_adjust view-data" step="0.01" <?php if($row_exists>0){ echo 'readonly'; } ?> type="number" name="registration_adjust" id="registration_adjust" <?php if ($mode != "EDIT" && $mode !="DUPLICATE"){ ?>disabled <?php } ?> onChange="autoDecimal('registration_adjust')" value="<?php echo $edit_row['registration_adjust'] ?>"><br>
						 <span id="validationra"  style="color: red; display: none;font-size:11px;margin-left: -65px;">Please input Adjust Fee</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
						<!--<select name="monthly" id="monthly" class="">
							<option value=""></option>
							<option value="Monthly" <?php // if($edit_row['programme_package']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php // if($edit_row['programme_package']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php // if($edit_row['programme_package']==' Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php // if($edit_row['programme_package']==' Annually') {echo 'selected';}?>>Annually</option>
						 </select>-->
					  </td>
					</tr>
					
					<tr class="">
					   <td style="margin-top:50px;border:none;" class="uk-width-1-10 uk-text-bold">Insurance<span class="text-danger">*</span>:</td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">							
						 <input class="regist_default view-data" style="font-weight:bold;border:none;" step="0.01" type="number" name="insurance_default" id="insurance_default" value="<?php echo $edit_row['insurance_default'] ?>" readonly><br>
						 <span id="validationinsd"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10"></td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">
							
						 <input class="registration_adjust view-data" step="0.01" <?php if($row_exists>0){ echo 'readonly'; } ?> type="number" name="insurance_adjust" id="insurance_adjust" <?php if ($mode != "EDIT" && $mode !="DUPLICATE"){ ?>disabled <?php } ?> onChange="autoDecimal('insurance_adjust')" value="<?php echo $edit_row['insurance_adjust'] ?>"><br>
						 <span id="validationinsa"  style="color: red; display: none;font-size:11px;margin-left: -65px;">Please input Adjust Fee</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
						<!--<select name="monthly" id="monthly" class="">
							<option value=""></option>
							<option value="Monthly" <?php // if($edit_row['programme_package']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php // if($edit_row['programme_package']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php // if($edit_row['programme_package']==' Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php // if($edit_row['programme_package']==' Annually') {echo 'selected';}?>>Annually</option>
						 </select>-->
					  </td>
					</tr>
					<tr class="">
					   <td style="margin-top:50px; border:none;" class="uk-width-1-10 uk-text-bold view-data">Uniform (2 sets)<span class="text-danger">*</span>:</td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">							
						 <input class="regist_default view-data" style="font-weight:bold;border:none;" step="0.01" type="number" name="uniform_default" id="uniform_default" value="<?php echo $edit_row['uniform_default'] ?>" readonly><br>
						 <span id="validationuid"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10"></td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">
							
						 <input class="registration_adjust view-data" step="0.01" <?php if($row_exists>0){ echo 'readonly'; } ?> type="number" name="uniform_adjust" id="uniform_adjust" <?php if ($mode != "EDIT" && $mode !="DUPLICATE"){ ?>disabled <?php } ?> onChange="autoDecimal('uniform_adjust')" value="<?php echo $edit_row['uniform_adjust'] ?>"><br>
						 <span id="validationuia"  style="color: red; display: none;font-size:11px;margin-left: -65px;">Please input Adjust Fee</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
						<!--<select name="monthly" id="monthly" class="">
							<option value=""></option>
							<option value="Monthly" <?php // if($edit_row['programme_package']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php // if($edit_row['programme_package']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php // if($edit_row['programme_package']==' Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php // if($edit_row['programme_package']==' Annually') {echo 'selected';}?>>Annually</option>
						 </select>-->
					  </td>
					</tr>
					<tr class="">
					   <td style="margin-top:50px; border:none;" class="uk-width-1-10 uk-text-bold view-data">Gymwear (1 set)<span class="text-danger">*</span>:</td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">							
						 <input class="regist_default view-data" style="font-weight:bold;border:none;" step="0.01" type="number" name="gymwear_default" id="gymwear_default" value="<?php echo $edit_row['gymwear_default'] ?>" readonly><br>
						 <span id="validationgyd"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10"></td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">
							
						 <input class="registration_adjust view-data" step="0.01" <?php if($row_exists>0){ echo 'readonly'; } ?> type="number" name="gymwear_adjust" id="gymwear_adjust" <?php if ($mode != "EDIT" && $mode !="DUPLICATE"){ ?>disabled <?php } ?> onChange="autoDecimal('gymwear_adjust')" value="<?php echo $edit_row['gymwear_adjust'] ?>"><br>
						 <span id="validationgya"  style="color: red; display: none;font-size:11px;margin-left: -65px;">Please input Adjust Fee</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
						<!--<select name="monthly" id="monthly" class="">
							<option value=""></option>
							<option value="Monthly" <?php // if($edit_row['programme_package']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php // if($edit_row['programme_package']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php // if($edit_row['programme_package']==' Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php // if($edit_row['programme_package']==' Annually') {echo 'selected';}?>>Annually</option>
						 </select>-->
					  </td>
					</tr>
					<tr class="">
					   <td style="margin-top:50px;border:none;" class="uk-width-1-10 uk-text-bold">Q-dees Level Kit<span class="text-danger">*</span>:</td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">							
						 <input class="regist_default view-data" style="font-weight:bold;border:none;" step="0.01" type="number" name="q_dees_default" id="q_dees_default" value="<?php echo $edit_row['q_dees_default'] ?>" readonly><br>
						 <span id="validationqd"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10"></td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">
							
						 <input class="registration_adjust view-data" step="0.01" <?php if($row_exists>0){ echo 'readonly'; } ?> type="number" name="q_dees_adjust" id="q_dees_adjust" <?php if ($mode != "EDIT" && $mode !="DUPLICATE"){ ?>disabled <?php } ?> onChange="autoDecimal('q_dees_adjust')" value="<?php echo $edit_row['q_dees_adjust'] ?>"><br>
						 <span id="validationqa"  style="color: red; display: none;font-size:11px;margin-left: -65px;">Please input Adjust Fee</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
						<!--<select name="monthly" id="monthly" class="">
							<option value=""></option>
							<option value="Monthly" <?php // if($edit_row['programme_package']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php // if($edit_row['programme_package']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php // if($edit_row['programme_package']==' Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php // if($edit_row['programme_package']==' Annually') {echo 'selected';}?>>Annually</option>
						 </select>-->
					  </td>
					</tr>
					<tr class="">
					   <td style="margin-top:50px;border:none;" class="uk-width-1-10 uk-text-bold">Q-dees Bag<span class="text-danger">*</span>:</td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">							
						 <input class="regist_default view-data"style="font-weight:bold;border:none;"  step="0.01" type="number" name="q_bag_default" id="q_bag_default" value="<?php echo $edit_row['q_bag_default'] ?>" readonly><br>
						 <span id="validationqbdd"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10"></td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">
							
						 <input class="registration_adjust view-data" step="0.01" <?php if($row_exists>0){ echo 'readonly'; } ?> type="number" name="q_bag_adjust" id="q_bag_adjust" <?php if ($mode != "EDIT" && $mode !="DUPLICATE"){ ?>disabled <?php } ?> onChange="autoDecimal('q_bag_adjust')"  value="<?php echo $edit_row['q_bag_adjust'] ?>"><br>
						 <span id="validationqbad"  style="color: red; display: none;font-size:11px;margin-left: -65px;">Please input Adjust Fee</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
						<!--<select name="monthly" id="monthly" class="">
							<option value=""></option>
							<option value="Monthly" <?php // if($edit_row['programme_package']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php // if($edit_row['programme_package']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php // if($edit_row['programme_package']==' Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php // if($edit_row['programme_package']==' Annually') {echo 'selected';}?>>Annually</option>
						 </select>-->
					  </td>
					</tr>
					<tr class="">
					   <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold">Total:</td>
					  <td style="text-align:center;" class="uk-width-1-10">							
						 <input class="view-data" step="0.01" style="font-weight:bold;border:none;" type="number" name="total_default_f" id="total_default_f" value="<?php echo $edit_row['total_default_f'] ?>" readonly>
					  </td>
					  <td class="uk-width-1-10"></td>
					  <td style="text-align:center;" class="uk-width-1-10">
							
						 <input class="view-data" step="0.01" type="number" <?php if($row_exists>0){ echo 'readonly'; } ?> name="total_adjust_f" id="total_adjust_f" style="font-weight:bold;border:none;" value="<?php echo $edit_row['total_adjust_f'] ?>">
					  </td>
					  <td class="uk-width-1-10">
						<!--<select name="monthly" id="monthly" class="" style="width: 100%;">
							<option value=""></option>
							<option value="Monthly" <?php // if($edit_row['programme_package']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php // if($edit_row['programme_package']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php // if($edit_row['programme_package']==' Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php // if($edit_row['programme_package']==' Annually') {echo 'selected';}?>>Annually</option>
						 </select>-->
					  </td>
					</tr>
					<tr>
						<td colspan="5" style="font-size:18px; font-weight:bold;border:none;">Part G (Pendidikan Islam)</td>
					</tr>
					<tr class="">
					   	<td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold">Pendidikan Islam / Jawi<span class="text-danger">*</span>:</td>
					  	<td style="text-align:center;" class="uk-width-1-10">							
						 	<input class="view-data" step="0.01" style="font-weight:bold;border:none;" type="number" name="islam_default" id="islam_default" value="<?php echo $edit_row['islam_default'] ?>" readonly><br><span id="validationisd"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  	</td>
					  	<td class="uk-width-1-10">
							
						 	<input class="view-data" type="text" style="font-weight:bold;border:none;" name="islam_default_perent" id="islam_default_perent" value="<?php echo $edit_row['islam_default_perent'] ?>" readonly><br>
						 	<span id="validationislam_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  	</td>
					  	<td style="text-align:center;" class="uk-width-1-10">
						 	<input class="view-data" step="0.01" type="number" <?php if($row_exists>0){ echo 'readonly'; } ?> name="islam_adjust" id="islam_adjust" <?php if ($mode != "EDIT" && $mode !="DUPLICATE"){ ?>disabled <?php } ?> onChange="autoDecimal('islam_adjust')" value="<?php echo $edit_row['islam_adjust'] ?>"><br><span id="validationisa"  style="color: red; display: none;font-size:11px;margin-left: -65px;">Please input Adjust Fee</span>
					  	</td>
					  	<td class="uk-width-1-10">
					  		<?php if($row_exists>0){ ?>
						  		<input class="view-data" type="text" name="islam_collection" readonly id="islam_collection" value="<?php echo $edit_row['islam_collection'] ?>">
						  	<?php } else { ?>
								<select name="islam_collection" id="islam_collection" <?php if ($mode != "EDIT" && $mode !="DUPLICATE"){ ?>disabled <?php } ?> class="view-data" style="width: 100%;">
									<option value=""></option>
									<option value="Monthly" <?php if($edit_row['islam_collection']=='Monthly') {echo 'selected';}?>>Monthly</option>
									<option value="Termly" <?php if($edit_row['islam_collection']=='Termly') {echo 'selected';}?>>Termly</option>
									<option value="Half Year" <?php if($edit_row['islam_collection']=='Half Year') {echo 'selected';}?>> Half Year</option>
									<option value="Annually" <?php if($edit_row['islam_collection']=='Annually') {echo 'selected';}?>>Annually</option>
								</select><br>
								<span id="validationpic"  style="color: red; display: none;font-size:11px;">Please select Collection Pattern</span>
							<?php } ?>
					  	</td>
					</tr>
				</table>
			</div>
         	<div class="uk-width-medium-5-10">	
			<table class="uk-table uk-table-small">
				<tr>
					<td style="margin-top:50px;border:none;" class="uk-width-1-10 uk-text-bold">Remarks:</td>
					<td style="border:none;">
						<textarea class="view-data" id="remarks" name="remarks" rows="5" <?php if($row_exists>0){ echo 'readonly'; } ?> cols="65"><?php echo $edit_row['remarks'] ?></textarea>
						<br />
						<span id="validationRemarks" style="color: red; font-size:11px; display: none;">Please state the reason why the fee structure is changed from the default in the Remarks</span>
					</td>
				</tr>
				
				<tr class="uk-text-small">
					<td class="uk-width-1-10 uk-text-bold" style="border:none;font-size: 15px;">Status :</td>
					<td  style="border:none;font-size:15px;"><?php echo ($_GET['mode'] == 'EDIT') ? $edit_row['status'] : ''; ?></td>
					<!-- <td  style="border:none; color:#00ff00;font-size:25px;">Approved</td>
					<td  style="border:none;  color:#ff0000;font-size:25px;">Rejected</td> -->
				</tr>
				<tr>
					<td style="margin-top:50px;border:none;" class="uk-width-1-10 uk-text-bold">Master's Remarks:</td>
					<td style="border:none;"><textarea class="view-data" id="remarks_master" name="remarks_master" rows="5" cols="65" readonly><?php echo ($_GET['mode'] == 'EDIT') ? $edit_row['remarks_master'] : ''; ?> </textarea></td>
				</tr>
				<tr class="uk-text-small">
                 	<td class="uk-width-1-10 uk-text-bold" style="border:none;font-size: 15px;">Master's Doc Remarks:</td>
				<?php
				
						//$doc = $row22['doc'];
						$remarks = ($_GET['mode'] == 'EDIT') ? $edit_row['doc_remarks'] : '';
						//$update_date = $row22['update_date'];
						//$payment_by = $row22['payment_by'];
						// $ext = pathinfo($doc, "http://starters.q-dees.com.my/admin/uploads/");
						// echo $ext; die;
						//$path = "/admin/uploads/".$doc;
						//$path_info = pathinfo($path);

						//print_r($path_info);
						//$path_info['extension'];
						
						echo "<td style='text-align: left; vertical-align: middle;'><a target='_blank' href='/admin/uploads/$remarks'>";
						echo $remarks;
						// if ($path_info['extension']=='pdf') {
						// echo '<img  width="35" src="/admin/uploads/pdf.webp" alt="PDF">';
						// } else {
						// echo '<img  width="100" height="100" src="/admin/uploads/'.$doc.'" alt="image">';
						// }
						echo "</a>
						
						</td>";
						?>
						   <script>
								// $('#files').change(function (event) {
									// var file = URL.createObjectURL(event.target.files[0]);
									// $('#dvSSM_file').append('<a href="' + file + '" target="_blank">Click to view document</a><br>');
								// });
							</script>
						</tr>
			</table>
         </div>
      </div>
      <br>
      <div class="uk-text-center">
         <button <?php if($_GET['mode'] == 'EDIT' && $edit_row['status'] == 'Approved') { echo 'disabled'; } ?> class="uk-button uk-button-primary">Save</button>
      </div>
<?php
if (($_SESSION["UserType"]=="A") & (hasRightGroupXOR($_SESSION["UserName"], "SalesEdit"))) {
?>
   </form>
<?php
}
?>
   </div>
</div><br>
 	<div class="uk-width-1-1 myheader">
      	<h2 class="uk-text-center myheader-text-color">Searching</h2>
   	</div>

   	<form class="uk-form" name="frmCentreSearch" id="frmCentreSearch" method="get">
      	<input type="hidden" name="mode" id="mode" value="BROWSE">
      	<input type="hidden" name="p" id="p" value="fee_structure_setting">

      	<div class="uk-grid">
         	<div class="uk-width-2-10 uk-text-small">
				<input type="text" name="n" id="n" value="<?php echo $_GET['n']?>" placeholder="Name of Fees Structure">
         	</div> 
		 	<div class="uk-width-2-10 uk-text-small">
				<!-- <input type="text" name="sb" id="sb" value="<?php echo $_GET['sb']?>" placeholder="Student Entry Level"> -->
				<select name="sb" id="sb" value="<?php echo $_GET['sb']?>">
				  <option value="">Student Entry Level</option>
				  <option value="EDP">EDP</option>
				  <option value="QF1">QF1</option>
				  <option value="QF2">QF2</option>
				  <option value="QF3">QF3</option>
				</select>
         	</div> 
		 	<div class="uk-width-2-10 uk-text-small">
				<!-- Optimizing Selection for Search -->
				<!--<input type="text" name="st" id="st" value="<?php echo $_GET['st']?>" placeholder="Programme Package">-->
				<select name="st" id="st" value="<?php echo $_GET['st']?>">
				  <option value="">Programme Package</option>
				  <option value="Full Day">Full Day</option>
				  <option value="Half Day">Half Day</option>
				  <option value="3/4 Day">3/4 Day</option>
				</select>
         	</div> 
		 <!--<div class="uk-width-2-10 uk-text-small">
		      <select name="st" id="st" class="uk-width-1-1">
					<option value="">Select State</option>
				</select>
            
         </div>
		 <div class="uk-width-2-10 uk-text-small">
		      <select name="sb" id="sb" class="uk-width-1-1">
                        <option value="">Please Select Subject</option>
                        <option value="EDP" <?php // if($edit_row['subject']=='EDP') {echo 'selected';}?>>EDP</option>
                        <option value="QF1" <?php // if($edit_row['subject']=='QF1') {echo 'selected';}?>>QF1</option>
                        <option value="QF2" <?php // if($edit_row['subject']=='QF2') {echo 'selected';}?>>QF2</option>
                        <option value="QF3" <?php // if($edit_row['subject']=='QF3') {echo 'selected';}?>>QF3</option>
                     </select>
            
         </div>-->
         <div class="uk-width-3-10">
            <button class="uk-button uk-width-1-1">Search</button>
         </div>
      </div>
   </form><br>
<div class="uk-margin-right">
   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color">LISTING</h2>
   </div>
 <div class="uk-overflow-container">
 				<?php
					$n=isset($_GET['n']) ? $_GET['n'] : '';
					$st=isset($_GET['st']) ? $_GET['st'] : '';
					$sb=isset($_GET['sb']) ? $_GET['sb'] : '';
                ?>
                <table class="uk-table" id='mydatatable' style="width: 100%;font-size:12px;">
                  <thead>
                    <tr class="uk-text-bold uk-text-small">
                        <td>Name of Fees Structure</td>
                        <td>Student Entry Level</td>
						<td>Student Number</td>
                        <td>Programme Package</td>
                        <td>Start Date</td>
                        <td>End Date</td>
						<td>Status</td>
						<td>Master's Remarks</td> 
                        <td style="width: 120px;">Action</td>
                    </tr>
                  </thead>

                  <tbody>
				  
                  </tbody>
                </table>
            </div>	
</div>
<form name="frmDeleteRecord" id="frmDeleteRecord" method="get">
   <input type="hidden" name="p" value="fee_structure_setting">
   <input type="hidden" name="id" id="id" value="">
   <input type="hidden" name="mode" value="DEL">
</form>

<div id="student-dialog"></div>

<script>
function comparePrice(default_price, default_collection, adjusted_price, adjusted_collection){
	var defaultPrice = document.getElementById(default_price).value;
	var adjustedPrice = document.getElementById(adjusted_price).value;
	var defaultCollection = "";
	var adjustedCollection = "";

	if (default_collection != ""){
		defaultCollection = document.getElementById(default_collection).value;
		adjustedCollection = document.getElementById(adjusted_collection).value;
	}

	if (defaultPrice != adjustedPrice || defaultCollection != adjustedCollection){
		//alert("Something is different, set the flag");
		document.getElementById("alertPop").value = "changed";
	}
}

function autoDecimal(adjusted_price){
	var adjustedPrice = document.getElementById(adjusted_price).value;
	
	var newAdjustedPrice = (Math.round(adjustedPrice * 100) / 100).toFixed(2);
	document.getElementById(adjusted_price).value = newAdjustedPrice;
}

function sumSchoolNumber(){
	var Amount = 0;
	$(".school_number").each(function(){
		var amt = $(this).val();
		amt = parseFloat(amt);
		if (isNaN(amt)) {
			amt = 0;
		}
		Amount += amt;
	})
	$("#school_total_d").val(Amount.toFixed(2));
}
function summathNumber(){
	var Amount = 0;
		$(".math_default").each(function(){
			var amt = $(this).val();
			amt = parseFloat(amt);
			if (isNaN(amt)) {
                amt = 0;
            }
			Amount += amt;
		})
		$("#total_default_c").val(Amount.toFixed(2));
}
function sumintNumber(){
	var Amount = 0;
		$(".int_default").each(function(){
			var amt = $(this).val();
			amt = parseFloat(amt);
			if (isNaN(amt)) {
                amt = 0;
            }
			Amount += amt;
		})
		$("#total_default_d").val(Amount.toFixed(2));
}
function sumregistNumber(){
	var Amount = 0;
		$(".regist_default").each(function(){
			var amt = $(this).val();
			amt = parseFloat(amt);
			if (isNaN(amt)) {
                amt = 0;
            }
			Amount += amt;
		})
		$("#total_default_f").val(Amount.toFixed(2));
}
function sumadjustNumber(){
	var Amount = 0;
		$(".school_adjust").each(function(){
			var amt = $(this).val();
			amt = parseFloat(amt);
			if (isNaN(amt)) {
                amt = 0;
            }
			Amount += amt;
		})
		$("#school_total_f").val(Amount.toFixed(2));
}
function sumadNumber(){
	var Amount = 0;
		$(".math_adjust").each(function(){
			var amt = $(this).val();
			amt = parseFloat(amt);
			if (isNaN(amt)) {
                amt = 0;
            }
			Amount += amt;
		})
		$("#total_adjust_c").val(Amount.toFixed(2));
}
function sumintegNumber(){
	var Amount = 0;
		$(".integrated_adjust").each(function(){
			var amt = $(this).val();
			amt = parseFloat(amt);
			if (isNaN(amt)) {
                amt = 0;
            }
			Amount += amt;
		})
		$("#total_adjust_d").val(Amount.toFixed(2));
}
function sumregistrationNumber(){
	var Amount = 0;
		$(".registration_adjust").each(function(){
			var amt = $(this).val();
			amt = parseFloat(amt);
			if (isNaN(amt)) {
                amt = 0;
            }
			Amount += amt;
		})
		$("#total_adjust_f").val(Amount.toFixed(2));
}

function removeDisable(var3, var4){
	//const button = document.getElementById(var1);
	//const side = document.getElementById(var2);
	const button2 = document.getElementById(var3);
	button2.removeAttribute("disabled");

	//button.removeAttribute("disabled");
	//side.removeAttribute("disabled");
	if (var4 != ""){
		const side2 = document.getElementById(var4);
		side2.removeAttribute("disabled");
	}
//	alert(var1);
//	alert(var2);
//	alert(var3);
//	alert(var4);
};


$(document).ready(function(){

	<?php if($_GET['mode'] == 'EDIT' && isset($edit_row['status']) && $edit_row['status'] == 'Approved') { ?>

		$('.view-data').prop('disabled',true);

	<?php } 
	?>

	$('#mydatatable').DataTable({
		'columnDefs': [ { 
        'targets': [0,8], // column index (start from 0)
        'orderable': false, // set orderable false for selected columns
      	}],
		"order": [[ 4, "desc" ]],
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": "admin/serverresponse/fee_structure_setting.php?p=<?php echo $_GET['p']; ?>&n=<?php echo $n; ?>&sb=<?php echo $sb; ?>&st=<?php echo $st; ?>&sdate=<?php echo $sdate; ?>&edate=<?php echo $edate; ?>"
	});

   	$("#frmCourse").submit(function(e){
    
    var fees_structure = $("#fees_structure").val();
	var data = 1;
   	$.ajax({
        url: "admin/check_fees_structure.php",
        type: "GET",
        data: "name=" + fees_structure + "&id=<?php echo ($_GET['mode'] == 'EDIT') ? $get_sha1_id : ''; ?>&is_fee_structure=1",
        dataType: "text",
		async: false, 
        beforeSend: function(http) {},
        success: function(response, status, http) {
			response = JSON.parse(response);
			data = response.status; 
			if(response.status == 0){
			 //  UIkit.modal.alert("<h2>Fees structure name already exist</h2>")
			  // return false;
			} 
        },
        error: function(http, status, error) {
            UIkit.notify("Error:" + error);

        }
    }); 
	
	if(data == 0){
	   UIkit.modal.alert("<h2>Fees structure name already exist</h2>")
	   return false;
	}  
    var subject=$("#subject").val();
    var school_adjust=$("#school_adjust").val();
    var programme_package=$("#programme_package").val();
    var from_date=$("#from_date").val();
    var to_date=$("#to_date").val();
    var school_default=$("#school_default").val();
    var school_collection=$("#school_collection").val();
    var multimedia_default=$("#multimedia_default").val();
    var multimedia_adjust=$("#multimedia_adjust").val();
    var multimedia_collection=$("#multimedia_collection").val();
    var facility_default=$("#facility_default").val();
    var facility_adjust=$("#facility_adjust").val();
    var facility_collection=$("#facility_collection").val();
    var enhanced_default=$("#enhanced_default").val();
    var enhanced_adjust=$("#enhanced_adjust").val();
    var enhanced_collection=$("#enhanced_collection").val();
    var iq_math_default=$("#iq_math_default").val();
    var iq_math_default_perent=$("#iq_math_default_perent").val();
    var iq_math_adjust=$("#iq_math_adjust").val();
    var iq_math_collection=$("#iq_math_collection").val();
    var mandarin_default=$("#mandarin_default").val();
    var mandarin_default_perent=$("#mandarin_default_perent").val();
    var mandarin_adjust=$("#mandarin_adjust").val();
    var mandarin_collection=$("#mandarin_collection").val();
    var international_default=$("#international_default").val();
    var international_adjust=$("#international_adjust").val();
    var international_collection=$("#international_collection").val();
    var integrated_default=$("#integrated_default").val();
    var integrated_adjust=$("#integrated_adjust").val();
    var link_default=$("#link_default").val();
    var link_adjust=$("#link_adjust").val();
    var link_collection=$("#link_collection").val();
    var mandarin_m_defauft=$("#mandarin_m_defauft").val();
    var mandarin_m_adjust=$("#mandarin_m_adjust").val();
    var mandarin_m_collection=$("#mandarin_m_collection").val();
    var integrated_collection=$("#integrated_collection").val();
    var basic_default=$("#basic_default").val();
    var basic_adjust=$("#basic_adjust").val();
    var basic_collection=$("#basic_collection").val();
	var afternoon_robotic_default=$("#afternoon_robotic_default").val();
    var afternoon_robotic_adjust=$("#afternoon_robotic_adjust").val();
    var afternoon_robotic_collection=$("#afternoon_robotic_collection").val();
    var registration_default=$("#registration_default").val();
    var registration_adjust=$("#registration_adjust").val();
    var mobile_default=$("#mobile_default").val();
    var mobile_adjust=$("#mobile_adjust").val();
    var insurance_default=$("#insurance_default").val();
    var insurance_adjust=$("#insurance_adjust").val();
    var uniform_default=$("#uniform_default").val();
    var uniform_adjust=$("#uniform_adjust").val();
    var gymwear_default=$("#gymwear_default").val();
    var gymwear_adjust=$("#gymwear_adjust").val();
    var q_dees_default=$("#q_dees_default").val();
    var q_dees_adjust=$("#q_dees_adjust").val();
    var q_bag_default=$("#q_bag_default").val();
    var q_bag_adjust=$("#q_bag_adjust").val();
    var islam_default=$("#islam_default").val();
    var islam_adjust=$("#islam_adjust").val();
	var islam_collection=$("#islam_collection").val();
	var robotic_plus_default_perent=$("#robotic_plus_default_perent").val();
	var robotic_plus_default=$("#robotic_plus_default").val();
    var robotic_plus_adjust=$("#robotic_plus_adjust").val();
	var robotic_plus_collection=$("#robotic_plus_collection").val();
    var mobile_collection=$("#mobile_collection").val();
    var school_default_perent=$("#school_default_perent").val();
    var multimedia_default_perent=$("#multimedia_default_perent").val();
    var facility_default_perent=$("#facility_default_perent").val();
    var enhanced_default_perent=$("#enhanced_default_perent").val();
    var international_perent=$("#international_perent").val();
    var integrated_default_perent=$("#integrated_default_perent").val();
    var link_default_perent=$("#link_default_perent").val();
    var mandarin_m_default_perent=$("#mandarin_m_default_perent").val();
    var basic_default_perent=$("#basic_default_perent").val();
    var mobile_perent=$("#mobile_perent").val();

	//Checking if there's any differences in numbers
	document.getElementById("alertPop").value = "";

	var basicAPPrice = document.getElementById("basic_adjust").value; //Check if the user is electing to do a Afternoon Programme + Robotics instead, if it's 0, then check robotics
	var proPackage = document.getElementById("programme_package").value; 
	
	comparePrice('school_default', 'school_default_perent', 'school_adjust', 'school_collection');
	comparePrice('multimedia_default', 'multimedia_default_perent', 'multimedia_adjust', 'multimedia_collection');
	comparePrice('facility_default', 'facility_default_perent', 'facility_adjust', 'facility_collection');
	comparePrice('enhanced_default', 'enhanced_default_perent', 'enhanced_adjust', 'enhanced_collection');

	comparePrice('iq_math_default', 'iq_math_default_perent', 'iq_math_adjust', 'iq_math_collection');
	comparePrice('mandarin_default', 'mandarin_default_perent', 'mandarin_adjust', 'mandarin_collection');
	comparePrice('international_default', 'international_perent', 'international_adjust', 'international_collection');
	comparePrice('robotic_plus_default', 'robotic_plus_default_perent', 'robotic_plus_adjust', 'robotic_plus_collection');

	comparePrice('integrated_default', 'integrated_default_perent', 'integrated_adjust', 'integrated_collection');
	comparePrice('link_default', 'link_default_perent', 'link_adjust', 'link_collection');
	comparePrice('mandarin_m_defauft', 'mandarin_m_default_perent', 'mandarin_m_adjust', 'mandarin_m_collection');

	if (proPackage == "Full Day"){
		if (basicAPPrice > 0){
			comparePrice('basic_default', 'basic_default_perent', 'basic_adjust', 'basic_collection');
		}else{
			comparePrice('afternoon_robotic_default', 'afternoon_robotic_default_perent', 'afternoon_robotic_adjust', 'afternoon_robotic_collection');
		}
	}

	comparePrice('mobile_default', 'mobile_perent', 'mobile_adjust', 'mobile_collection');
	comparePrice('registration_default', '', 'registration_adjust', '');
	comparePrice('insurance_default', '', 'insurance_adjust', '');
	comparePrice('uniform_default', '', 'uniform_adjust', '');
	comparePrice('gymwear_default', '', 'gymwear_adjust', '');
	comparePrice('q_dees_default', '', 'q_dees_adjust', '');
	comparePrice('q_bag_default', '', 'q_bag_adjust', '');

	comparePrice('islam_default', '', 'islam_adjust', '');

	var adjustedPrice = document.getElementById("alertPop").value;
	var remarkContent = document.getElementById("remarks").value;

	var errorFlag = "";
    if (!school_adjust || !fees_structure || to_date < from_date) {
		errorFlag = "1";
	}

   if (!school_adjust) {
		$('#validationschool_adjust').show();
		errorFlag = "1";
	}else{
		$('#validationschool_adjust').hide();
	}
	if (!fees_structure) {
		$('#validationStructure').show();
		errorFlag = "1";
	}else{
		$('#validationStructure').hide();
	}
	if (!subject) {
		$('#validationSubject').show();
		errorFlag = "1";
	}else{
		$('#validationSubject').hide();
	}
	if (!programme_package) {
		$('#validationprogramme_package').show();
		errorFlag = "1";
	}else{
		$('#validationprogramme_package').hide();
	}
	if (!from_date) {
		$('#validationdf').show();
		errorFlag = "1";
	}else{
		$('#validationdf').hide();
	}
	if (!to_date) {
		$('#validationdt').text('Please input End Date');
		$('#validationdt').show();
		errorFlag = "1";
	} else if (to_date < from_date) {
		$('#validationdt').text('End date must be greater then start date');
		$('#validationdt').show();
		errorFlag = "1";
	}else{
		$('#validationdt').hide();
	}
	if (!school_default) {
		$('#validationsd').show();
		errorFlag = "1";
	}else{
		$('#validationsd').hide();
	}
	if (!school_default_perent) {
		$('#validationschool_perent').show();
		errorFlag = "1";
	}else{
		$('#validationschool_perent').hide();
	}
	if (!school_collection) {
		$('#validationsc').show();
		errorFlag = "1";
	}else{
		$('#validationsc').hide();
	}
	if (!multimedia_default) {
		$('#validationMd').show();
		errorFlag = "1";
	}else{
		$('#validationMd').hide();
	}
	if (!multimedia_default_perent) {
		$('#validationmultimedia_perent').show();
		errorFlag = "1";
	}else{
		$('#validationmultimedia_perent').hide();
	}
	if (!multimedia_adjust) {
		$('#validationMa').show();
		errorFlag = "1";
	}else{
		$('#validationMa').hide();
	}
	if (!multimedia_collection) {
		$('#validationMc').show();
		errorFlag = "1";
	}else{
		$('#validationMc').hide();
	}
	if (!facility_default) {
		$('#validationfd').show();
		errorFlag = "1";
	}else{
		$('#validationfd').hide();
	}
	if (!facility_default_perent) {
		$('#validationfacility_perent').show();
		errorFlag = "1";
	}else{
		$('#validationfacility_perent').hide();
	}
	if (!facility_adjust) {
		$('#validationfa').show();
		errorFlag = "1";
	}else{
		$('#validationfa').hide();
	}
	if (!facility_collection) {
		$('#validationfc').show();
		errorFlag = "1";
	}else{
		$('#validationfc').hide();
	}
	if (!enhanced_default) {
		$('#validationed').show();
		errorFlag = "1";
	}else{
		$('#validationed').hide();
	}
	if (!enhanced_default_perent) {
		$('#validationenhanced_perent').show();
		errorFlag = "1";
	}else{
		$('#validationenhanced_perent').hide();
	}
	if (!enhanced_adjust) {
		$('#validationea').show();
		errorFlag = "1";
	}else{
		$('#validationea').hide();
	}
	if (!enhanced_collection) {
		$('#validationec').show();
		errorFlag = "1";
	}else{
		$('#validationec').hide();
	}
	if (!iq_math_default) {
		$('#validationiq').show();
		errorFlag = "1";
	}else{
		$('#validationiq').hide();
	}
	if (!iq_math_default_perent) {
		$('#validationiq_math_perent').show();
		errorFlag = "1";
	}else{
		$('#validationiq_math_perent').hide();
	}
	if (!iq_math_adjust) {
		$('#validationma').show();
		errorFlag = "1";
	}else{
		$('#validationma').hide();
	}
	if (!iq_math_collection) {
		$('#validationmc').show();
		errorFlag = "1";
	}else{
		$('#validationmc').hide();
	}
	if (!mandarin_default) {
		$('#validationmdf').show();
		errorFlag = "1";
	}else{
		$('#validationmdf').hide();
	}
	if (!mandarin_default_perent) {
		$('#validationmandarin_perent').show();
		errorFlag = "1";
	}else{
		$('#validationmandarin_perent').hide();
	}
	if (!mandarin_adjust) {
		$('#validationmda').show();
		errorFlag = "1";
	}else{
		$('#validationmda').hide();
	}
	if (!mandarin_collection) {
		$('#validationmdc').show();
		errorFlag = "1";
	}else{
		$('#validationmdc').hide();
	}
	if (!international_default) {
		$('#validationind').show();
		errorFlag = "1";
	}else{
		$('#validationind').hide();
	}
	if (!international_perent) {
		$('#validationinternational_perent').show();
		errorFlag = "1";
	}else{
		$('#validationinternational_perent').hide();
	}
	if (!international_adjust) {
		$('#validationina').show();
		errorFlag = "1";
	}else{
		$('#validationina').hide();
	}
	if (!integrated_default_perent) {
		$('#validationintegrated_perent').show();
		errorFlag = "1";
	}else{
		$('#validationintegrated_perent').hide();
	}
	if (!international_collection) {
		$('#validationinc').show();
		errorFlag = "1";
	}else{
		$('#validationinc').hide();
	}
	if (!integrated_default) {
		$('#validationitd').show();
		errorFlag = "1";
	}else{
		$('#validationitd').hide();
	}
	if (!integrated_adjust) {
		$('#validationita').show();
		errorFlag = "1";
	}else{
		$('#validationita').hide();
	}
	if (!integrated_collection) {
		$('#validationitc').show();
		errorFlag = "1";
	}else{
		$('#validationitc').hide();
	}
	if (!link_default) {
		$('#validationlnk').show();
		errorFlag = "1";
	}else{
		$('#validationlnk').hide();
	}
	if (!link_default_perent) {
		$('#validationlink_perent').show();
		errorFlag = "1";
	}else{
		$('#validationlink_perent').hide();
	}
	if (!mandarin_m_default_perent) {
		$('#validationmandarin_m_perent').show();
		errorFlag = "1";
	}else{
		$('#validationmandarin_m_perent').hide();
	}
	if (!link_adjust) {
		$('#validationlna').show();
		errorFlag = "1";
	}else{
		$('#validationlna').hide();
	}
	if (!link_collection) {
		$('#validationlnc').show();errorFlag = "1";
	}else{
		$('#validationlnc').hide();
	}
	if (!mandarin_m_defauft) {
		$('#validationmm').show();errorFlag = "1";
	}else{
		$('#validationmm').hide();
	}
	if (!mandarin_m_adjust) {
		$('#validationmma').show();errorFlag = "1";
	}else{
		$('#validationmma').hide();
	}
	if (!mandarin_m_collection) {
		$('#validationmmc').show();errorFlag = "1";
	}else{
		$('#validationmmc').hide();
	}
	if (!basic_default) {
		$('#validationbd').show();errorFlag = "1";
	}else{
		$('#validationbd').hide();
	}
	if (!basic_default_perent) {
		$('#validationbasic_perent').show();errorFlag = "1";
	}else{
		$('#validationbasic_perent').hide();
	}
	if (!basic_adjust) {
		$('#validationba').show();errorFlag = "1";
	}else{
		$('#validationba').hide();
	}
	if (!basic_collection) {
		$('#validationbc').show();errorFlag = "1";
	}else{
		$('#validationbc').hide();
	}
	if (!registration_default) {
		$('#validationrd').show();errorFlag = "1";
	}else{
		$('#validationrd').hide();
	}
	if (!registration_adjust) {
		$('#validationra').show();errorFlag = "1";
	}else{
		$('#validationra').hide();
	}
	if (!mobile_default) {
		$('#validationmdi').show();errorFlag = "1";
	}else{
		$('#validationmdi').hide();
	}
	if (!mobile_perent) {
		$('#validationmobile_perent').show();errorFlag = "1";
	}else{
		$('#validationmobile_perent').hide();
	}
	if (!mobile_adjust) {
		$('#validationmba').show();errorFlag = "1";
	}else{
		$('#validationmba').hide();
	}
	if (!mobile_collection) {
		$('#validationmbc').show();errorFlag = "1";
	}else{
		$('#validationmbc').hide();
	}
	if (!insurance_default) {
		$('#validationinsd').show();errorFlag = "1";
	}else{
		$('#validationinsd').hide();
	}
	if (!insurance_adjust) {
		$('#validationinsa').show();errorFlag = "1";
	}else{
		$('#validationinsa').hide();
	}
	if (!uniform_default) {
		$('#validationuid').show();errorFlag = "1";
	}else{
		$('#validationuid').hide();
	}
	if (!uniform_adjust) {
		$('#validationuia').show();errorFlag = "1";
	}else{
		$('#validationuia').hide();
	}
	if (!gymwear_default) {
		$('#validationgyd').show();errorFlag = "1";
	}else{
		$('#validationgyd').hide();
	}
	if (!gymwear_adjust) {
		$('#validationgya').show();errorFlag = "1";
	}else{
		$('#validationgya').hide();
	}
	if (!q_dees_default) {
		$('#validationqd').show();errorFlag = "1";
	}else{
		$('#validationqd').hide();
	}
	if (!q_dees_adjust) {
		$('#validationqa').show();errorFlag = "1";
	}else{
		$('#validationqa').hide();
	}
	if (!q_bag_default) {
		$('#validationqbdd').show();errorFlag = "1";
	}else{
		$('#validationqbdd').hide();
	}
	if (!q_bag_adjust) {
		$('#validationqbad').show();errorFlag = "1";
	}else{
		$('#validationqbad').hide();
	}
	if (!islam_default) {
		$('#validationisd').show();errorFlag = "1";
	}else{
		$('#validationisd').hide();
	}
	if (!islam_adjust) {
		$('#validationisa').show();errorFlag = "1";
	}else{
		$('#validationisa').hide();
	}
	if (!islam_collection) {
		$('#validationpic').show();errorFlag = "1";
	}else{
		$('#validationpic').hide();
	}

	if (!robotic_plus_adjust) {
		$('#validationrp').show();errorFlag = "1";
	}else{
		$('#validationrp').hide();
	}
	if (!robotic_plus_default_perent) {
		$('#validationrobotic_plus_perent').show();errorFlag = "1";
	}else{
		$('#validationrobotic_plus_perent').hide();
	}
	if (!robotic_plus_collection) {
		$('#validationrpc').show();errorFlag = "1";
	}else{
		$('#validationrpc').hide();
	}

	if (adjustedPrice != ""){ //Check to see if there's remarks input if the price changed
		if (remarkContent == "") {
			$('#validationRemarks').show();errorFlag = "1";
		}else{
			$('#validationRemarks').hide();

			if (confirm("Adjusted Fee Structures from the Default Fee take between 3-5 working days to check and approve. Proceed?") == false) {
				e.preventDefault();
				return false;
			}
		}
	}

	if (errorFlag == "1"){
		e.preventDefault();
        return false;
	}
  });
	//console.log("in");
	$(".school_number").on('keyup keypress blur change', function (e) {
		sumSchoolNumber()
        });
	$(".math_default").on('keyup keypress blur change', function (e) {
		summathNumber()
	});
	$(".int_default").on('keyup keypress blur change', function (e) {
		sumintNumber()
	});
	$(".regist_default").on('keyup keypress blur change', function (e) {
		sumregistNumber()
	});
	$(".school_adjust").on('keyup keypress blur change', function (e) {
		sumadjustNumber()
	});
	$(".math_adjust").on('keyup keypress blur change', function (e) {
		sumadNumber()
	});
	$(".integrated_adjust").on('keyup keypress blur change', function (e) {
		sumintegNumber()
	});
	$(".registration_adjust").on('keyup keypress blur change', function (e) {
		sumregistrationNumber()
	});
	
	$(".subject_3").change(function () {
      var subject=$("#subject").val();
      var programme_package=$("#programme_package").val();
      var from_date="2023-03-06";
	  var to_date="2024-02-29";
	  //var from_date=$("#from_date").val();
      //var to_date=$("#to_date").val();
	  var UserName='<?php echo $_SESSION["UserName"]; ?>';
	  
      $.ajax({
         url : "admin/get_feevalue.php",
         type : "POST",
         data : "subject="+subject+"&UserName="+UserName+"&programme_package="+programme_package+"&from_date="+from_date+"&to_date="+to_date,
         dataType : "json",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
			 
            if (response!="" && response!=null) {
				//CHS: To-do: Unlock the disabled for Part B
				//alert("Hello, come here!");
				/*

				const button = document.getElementById('school_default')
				const side = document.getElementById('school_default_perent')
				const button2 = document.getElementById('school_adjust')
				const side2 = document.getElementById('school_collection');

				button.removeAttribute("disabled");
				side.removeAttribute("disabled");
				button2.removeAttribute("disabled");
				side2.removeAttribute("disabled");
				*/

			  	removeDisable('school_adjust', 'school_collection');
				removeDisable('multimedia_adjust', 'multimedia_collection');
				removeDisable('facility_adjust', 'facility_collection');
				removeDisable('enhanced_adjust', 'enhanced_collection');

				removeDisable('iq_math_adjust', 'iq_math_collection');
				removeDisable('mandarin_adjust', 'mandarin_collection');
				removeDisable('international_adjust', 'international_collection');
				removeDisable('robotic_plus_adjust', 'robotic_plus_collection');

				removeDisable('integrated_adjust', 'integrated_collection');
				removeDisable('link_adjust', 'link_collection');
				removeDisable('mandarin_m_adjust', 'mandarin_m_collection');

				removeDisable('basic_adjust', 'basic_collection');
				removeDisable('afternoon_robotic_adjust', 'afternoon_robotic_collection');

				removeDisable('mobile_adjust', 'mobile_collection');
				removeDisable('registration_adjust', '');
				removeDisable('insurance_adjust', '');
				removeDisable('uniform_adjust', '');
				removeDisable('gymwear_adjust', '');
				removeDisable('q_dees_adjust', '');
				removeDisable('q_bag_adjust', '');

				removeDisable('islam_adjust', '');
				removeDisable('islam_collection', '');

               $("#school_default").val(response.school_fee);
               $("#school_adjust").val(response.school_fee);
               $("#multimedia_default").val(response.multimedia_fee);
               $("#multimedia_adjust").val(response.multimedia_fee);
               $("#facility_default").val(response.facility_fee);
               $("#facility_adjust").val(response.facility_fee);
               $("#enhanced_default").val(response.eife);
               $("#enhanced_adjust").val(response.eife);
               $("#iq_math_default").val(response.iq_math);
               $("#iq_math_adjust").val(response.iq_math);
			   $("#robotic_plus_default").val(response.robotic_plus);
               $("#robotic_plus_adjust").val(response.robotic_plus);
               $("#mandarin_default").val(response.mandarin);
               $("#mandarin_adjust").val(response.mandarin);
               $("#international_default").val(response.international_art);
               $("#international_adjust").val(response.international_art);
               $("#total_adjust_c").val(response.total_default_c);
               $("#integrated_default").val(response.integrated_modules);
               $("#integrated_adjust").val(response.integrated_modules);
               $("#link_default").val(response.link_think);
			   $("#link_adjust").val(response.link_think);
               $("#mandarin_m_defauft").val(response.mandarin_modules);
               $("#total_adjust_d").val(response.total_default_d);
               $("#mandarin_m_adjust").val(response.mandarin_modules);
               $("#basic_default").val(response.basic_afternoon_p);
               $("#basic_adjust").val(response.basic_afternoon_p);
			   $("#afternoon_robotic_default").val(response.afternoon_robotic);
               $("#afternoon_robotic_adjust").val(0);
               $("#registration_default").val(response.registration);
               $("#registration_adjust").val(response.registration);
               $("#mobile_default").val(response.mobile_app);
               $("#mobile_adjust").val(response.mobile_app);
               $("#insurance_default").val(response.insurance);
               $("#insurance_adjust").val(response.insurance);
               $("#uniform_default").val(response.uniform);
               $("#uniform_adjust").val(response.uniform);
               $("#gymwear_default").val(response.gymwear);
               $("#gymwear_adjust").val(response.gymwear);
               $("#q_dees_default").val(response.q_dees_kit);
               $("#q_dees_adjust").val(response.q_dees_kit);
               $("#q_bag_default").val(response.q_dees_bag);
               $("#q_bag_adjust").val(response.q_dees_bag);
               $("#total_adjust_f").val(response.school_total_f);
               $("#islam_default").val(response.pendidikan_islam);
               $("#islam_adjust").val(response.pendidikan_islam);
               $("#school_default_perent").val(response.school_collection);
               $("#multimedia_default_perent").val(response.multimedia_collection);
               $("#multimedia_collection").val(response.multimedia_collection);
			   $("#facility_default_perent").val(response.facility_collection);
			   $("#facility_collection").val(response.facility_collection);
			   $("#enhanced_default_perent").val(response.enhanced_collection);
			   $("#enhanced_collection").val(response.enhanced_collection);
			   $("#school_total_f").val(response.total_fee);
			   $("#iq_math_default_perent").val(response.iq_math_collection);
			   $("#iq_math_collection").val(response.iq_math_collection);
			   $("#robotic_plus_default_perent").val(response.robotic_plus_collection);
			   $("#robotic_plus_collection").val(response.robotic_plus_collection);
               $("#mandarin_default_perent").val(response.mandarin_collection);
               $("#mandarin_collection").val(response.mandarin_collection);
               $("#international_perent").val(response.international_collection);
               $("#international_collection").val(response.international_collection);
			   $("#integrated_default_perent").val(response.integrated_collection);
			   $("#integrated_collection").val(response.integrated_collection);
               $("#link_collection").val(response.link_collection);
               $("#link_default_perent").val(response.link_collection);
               $("#mandarin_m_default_perent").val(response.mandarin_m_collection);
               $("#mandarin_m_collection").val(response.mandarin_m_collection);
			   $("#afternoon_robotic_default_perent").val(response.afternoon_robotic_collection);
			   $("#afternoon_robotic_collection").val(response.afternoon_robotic_collection);
			   $("#basic_default_perent").val(response.basic_collection);
			   $("#basic_collection").val(response.basic_collection);
			   $("#islam_default_perent").val(response.pendidikan_islam_collection);
			   $("#islam_collection").val(response.pendidikan_islam_collection);
               $("#mobile_perent").val(response.mobile_collection);
               $("#mobile_collection").val(response.mobile_collection);
               $("#school_collection").val(response.school_collection);
            } else {
			$("#school_default").val(response.school_fee);
               $("#school_adjust").val(response.school_fee);
               $("#multimedia_default").val(response.multimedia_fee);
               $("#multimedia_adjust").val(response.multimedia_fee);
               $("#facility_default").val(response.facility_fee);
               $("#facility_adjust").val(response.facility_fee);
               $("#enhanced_default").val(response.eife);
               $("#enhanced_adjust").val(response.eife);
               $("#iq_math_default").val(response.iq_math);
               $("#mandarin_default").val(response.mandarin);
               $("#international_default").val(response.international_art);
               $("#integrated_default").val(response.integrated_modules);
               $("#link_default").val(response.link_think);
               $("#link_adjust").val(response.link_think);
               $("#mandarin_m_defauft").val(response.mandarin_modules);
               $("#basic_default").val(response.basic_afternoon_p);
               $("#registration_default").val(response.registration);
               $("#mobile_default").val(response.mobile_app);
               $("#insurance_default").val(response.insurance);
               $("#uniform_default").val(response.uniform);
               $("#gymwear_default").val(response.gymwear);
               $("#q_dees_default").val(response.q_dees_kit);
               $("#q_bag_default").val(response.q_dees_bag);
               $("#islam_default").val(response.pendidikan_islam);
               $("#school_default_perent").val(response.school_collection);
               $("#multimedia_default_perent").val(response.multimedia_collection);
               $("#multimedia_collection").val(response.multimedia_collection);
			   $("#facility_default_perent").val(response.facility_collection);
			   $("#facility_collection").val(response.facility_collection);
			   $("#enhanced_default_perent").val(response.enhanced_collection);
			   $("#enhanced_collection").val(response.enhanced_collection);
			   $("#school_total_f").val(response.total_fee);
			   $("#iq_math_default_perent").val(response.iq_math_collection);
               $("#mandarin_default_perent").val(response.mandarin_collection);
               $("#international_perent").val(response.international_collection);
			   $("#integrated_default_perent").val(response.integrated_collection);
			   $("#integrated_collection").val(response.integrated_collection);
               $("#link_default_perent").val(response.link_collection);
               $("#mandarin_m_default_perent").val(response.mandarin_m_collection);
			   $("#basic_default_perent").val(response.basic_collection);
			   $("#basic_collection").val(response.basic_collection);
			   $("#islam_default_perent").val(response.pendidikan_islam_collection);
			   $("#islam_collection").val(response.pendidikan_islam_collection);
               $("#mobile_perent").val(response.mobile_collection);
               $("#mobile_collection").val(response.mobile_collection);
               $("#school_collection").val(response.school_collection);
               //$("#state").html("<select name='state' id='state' class='uk-width-1-1'><option value=''>Please select a country</option></select>");
              // UIkit.notify("No state found in "+country);
            }
			sumSchoolNumber();
			summathNumber();
			sumintNumber();
			sumregistNumber();
         },
         error : function(http, status, error) {
            //UIkit.notify("Error:"+error);
			$("#school_default").val(response.school_fee);
               $("#school_adjust").val(response.school_fee);
               $("#multimedia_default").val(response.multimedia_fee);
               $("#multimedia_adjust").val(response.multimedia_fee);
               $("#facility_default").val(response.facility_fee);
               $("#facility_adjust").val(response.facility_fee);
               $("#enhanced_default").val(response.eife);
               $("#enhanced_adjust").val(response.eife);
               $("#iq_math_default").val(response.iq_math);
               $("#mandarin_default").val(response.mandarin);
               $("#international_default").val(response.international_art);
               $("#integrated_default").val(response.integrated_modules);
               $("#link_default").val(response.link_think);
               $("#mandarin_m_defauft").val(response.mandarin_modules);
               $("#basic_default").val(response.basic_afternoon_p);
               $("#registration_default").val(response.registration);
               $("#mobile_default").val(response.mobile_app);
               $("#insurance_default").val(response.insurance);
               $("#uniform_default").val(response.uniform);
               $("#gymwear_default").val(response.gymwear);
               $("#q_dees_default").val(response.q_dees_kit);
               $("#q_bag_default").val(response.q_dees_bag);
               $("#islam_default").val(response.pendidikan_islam);
               $("#school_default_perent").val(response.school_collection);
               $("#multimedia_default_perent").val(response.multimedia_collection);
               $("#multimedia_collection").val(response.multimedia_collection);
			   $("#facility_default_perent").val(response.facility_collection);
			   $("#facility_collection").val(response.facility_collection);
			 $("#enhanced_default_perent").val(response.enhanced_collection);
			 $("#enhanced_collection").val(response.enhanced_collection);
			 $("#school_total_f").val(response.total_fee);
			  $("#iq_math_default_perent").val(response.iq_math_collection);
               $("#mandarin_default_perent").val(response.mandarin_collection);
               $("#international_perent").val(response.international_collection);
			   $("#integrated_default_perent").val(response.integrated_collection);
               $("#link_default_perent").val(response.link_collection);
               $("#mandarin_m_default_perent").val(response.mandarin_m_collection);
			   $("#basic_default_perent").val(response.basic_collection);
               $("#mobile_perent").val(response.mobile_collection);
               $("#school_collection").val(response.school_collection);
			sumSchoolNumber();
			summathNumber();
			sumintNumber();
			sumregistNumber();
         }
      });
   });
	
  
   $("#country").change(function () {
      var country=$("#country").val();
      $.ajax({
         url : "admin/get_state.php",
         type : "POST",
         data : "country="+country,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            if (response!="") {
               $("#state").html(response);
            } else {
               //$("#state").html("<select name='state' id='state' class='uk-width-1-1'><option value=''>Please select a country</option></select>");
              // UIkit.notify("No state found in "+country);
            }
         },
         error : function(http, status, error) {
            UIkit.notify("Error:"+error);
         }
      });
   });
   
    $("#c").change(function () {
    loadState();
   });
});
loadState();
function loadState(){
	  var country=$("#c").val();
      $.ajax({
         url : "admin/get_state.php",
         type : "POST",
         data : "country="+country,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            if (response!="") {
               $("#st").html(response);
            } else {
              // $("#st").html("<select name='st' id='st' class='uk-width-1-1'><option value=''>Please select a country</option></select>");
              // UIkit.notify("No state found in "+country);
            }
			$("#st").val('<?php echo $_GET["st"] ?>');
         },
         error : function(http, status, error) {
            UIkit.notify("Error:"+error);
         }
      });
}
function doDeleteRecord(id) {
   UIkit.modal.confirm("<h2>Are you sure you wish to continue?</h2>", function () {
      $("#id").val(id);
      $("#frmDeleteRecord").submit();
   });
}

function dlgStudentList(fee_id) {
   $.ajax({
      url : "admin/dlgStudentList.php",
      type : "POST",
      data : "fee_id="+fee_id,
      dataType : "text",
      beforeSend : function(http) {
      },
      async: false,
      success : function(response, status, http) {
         $("#student-dialog").html("");
         $("#student-dialog").html(response);
		 $("#student-dialog").dialog({
               dialogClass:"no-close",
               title:"Students Detail",
               modal:true,
               height:'auto',
               width:'60%',
            });
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}

$(document).ready(function(){
  	$("#basic_adjust").change(function(){
		if($(this).val() > 0) {
			$("#afternoon_robotic_adjust").val(0);
		}
  	});
  	$("#afternoon_robotic_adjust").change(function(){
		if($(this).val() > 0) {
			$("#basic_adjust").val(0);
		}
  	});
});

</script>
<?php
//$msg=$_GET['msg'];
if ($msg!="") {
   echo "<script>UIkit.notify('$msg')</script>";
}
   } else {
      echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
   }
} else {
   header("Location: index.php");
}

$m = $_GET['msg'];
if (isset($mode) and $mode=="DUPLICATE" AND $_GET['msg'] != '') {
	  echo "<script>UIkit.notify('$m')</script>";
}
?>
<style type="text/css">
   #mydatatable_length{
display: none;
}

 #mydatatable_filter{
display: none;
}

/*#mydatatable_paginate{float:initial;text-align:center}*/
#mydatatable_paginate .paginate_button{display: inline-block; min-width: 16px; padding: 3px 5px; line-height: 20px; text-decoration: none; -moz-box-sizing: content-box; box-sizing: content-box; text-align: center; background: #f7f7f7; color: #444444; border: 1px solid rgba(0, 0, 0, 0.2); border-bottom-color: rgba(0, 0, 0, 0.3); background-origin: border-box; background-image: -webkit-linear-gradient(top, #ffffff, #eeeeee); background-image: linear-gradient(to bottom, #ffffff, #eeeeee); text-shadow: 0 1px 0 #ffffff;margin-left: 3px;
    margin-right: 3px}
#mydatatable_paginate .paginate_button.current{background: #009dd8; color: #ffffff!important; border: 1px solid rgba(0, 0, 0, 0.2); border-bottom-color: rgba(0, 0, 0, 0.4); background-origin: border-box; background-image: -webkit-linear-gradient(top, #00b4f5, #008dc5); background-image: linear-gradient(to bottom, #00b4f5, #008dc5); text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.2);}
#mydatatable_filter{width:100%}
#mydatatable_filter label{width:100%;display:inline-flex}
#mydatatable_filter label input{height: 30px;width: 100%; padding: 4px 6px; border: 1px solid #dddddd; background: #ffffff; color: #444444; -webkit-transition: all linear 0.2s; transition: all linear 0.2s; border-radius: 4px;}
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
.default_1{
	margin-left:-21%;
}
.de_d{
	border: none;
    text-align: center;
    position: absolute;
    width: 240px;
}
</style>