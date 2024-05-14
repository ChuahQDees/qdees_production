<?php
session_start();
if ($_SESSION["isLogin"]==1) {
   if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "FeeEdit|FeeView"))) {
      include_once("mysql.php");
      foreach ($_GET as $key=>$value) {
         $key=$value;
      }
$mode=isset($_GET["mode"]) ? $_GET["mode"] : '';
$get_sha1_fee_id=isset($_POST['fee_id']) ? $_POST['fee_id'] : '';
$state= isset($_POST['state']) ? $_POST['state'] : '';
$extend_year = $_SESSION['Year'];
$state = ($state != '') ? implode (", ", $state) : ''; 
      if (isset($mode) and $mode=="EDIT" && $get_sha1_fee_id!="") {
		  if(isset($_POST['fee_id'],$_POST['country'],$_POST['state'],$_POST['subject'],$_POST['registration'],$_POST['mobile_app'])) {
            $_POST['deposit'] = (isset($_POST['deposit'])) ? $_POST['deposit'] : 0.00;
            $_POST['placement'] = (isset($_POST['placement'])) ? $_POST['placement'] : 0.00;
        $save_sql="update fee set 
			country='".$_POST['country']."', 
			state='$state', 
			fees_structure='".$_POST['fees_structure']."',
			subject='".$_POST['subject']."', 
			deposit='".$_POST['deposit']."', 
			placement='".$_POST['placement']."', 
			registration='".$_POST['registration']."', 
			mobile_app='".$_POST['mobile_app']."',
			programme_package='".$_POST['programme_package']."',
			from_date='".$_POST['from_date']."',
			to_date='".$_POST['to_date']."',
			school_fee='".$_POST['school_fee']."',
			multimedia_fee='".$_POST['multimedia_fee']."',
			facility_fee='".$_POST['facility_fee']."',
			eife='".$_POST['eife']."',
			iq_math='".$_POST['iq_math']."',
			mandarin='".$_POST['mandarin']."',
			international_art='".$_POST['international_art']."',
			integrated_modules='".$_POST['integrated_modules']."',
			link_think='".$_POST['link_think']."',
			mandarin_modules='".$_POST['mandarin_modules']."',
			basic_afternoon_p='".$_POST['basic_afternoon_p']."',
            afternoon_robotic='".$_POST['afternoon_robotic']."',
			insurance='".$_POST['insurance']."',
			uniform='".$_POST['uniform']."',
			gymwear='".$_POST['gymwear']."',
			q_dees_kit='".$_POST['q_dees_kit']."',
			q_dees_bag='".$_POST['q_dees_bag']."',
			pendidikan_islam='".$_POST['pendidikan_islam']."',
            robotic_plus='".$_POST['robotic_plus']."',
			school_collection='".$_POST['school_collection']."',
			multimedia_collection='".$_POST['multimedia_collection']."',
			facility_collection='".$_POST['facility_collection']."',
			enhanced_collection='".$_POST['enhanced_collection']."',
			iq_math_collection='".$_POST['iq_math_collection']."',
			mandarin_collection='".$_POST['mandarin_collection']."',
			international_collection='".$_POST['international_collection']."',
			integrated_collection='".$_POST['integrated_collection']."',
			link_collection='".$_POST['link_collection']."',
			mandarin_m_collection='".$_POST['mandarin_m_collection']."',
			basic_collection='".$_POST['basic_collection']."',
			mobile_collection='".$_POST['mobile_collection']."',
            pendidikan_islam_collection='".$_POST['pendidikan_islam_collection']."',
            robotic_plus_collection='".$_POST['robotic_plus_collection']."',
            afternoon_robotic_collection='".$_POST['afternoon_robotic_collection']."',
			total_fee='".$_POST['total_fee']."',
			total_default_c='".$_POST['total_default_c']."',
			total_default_d='".$_POST['total_default_d']."',
			school_total_f='".$_POST['school_total_f']."',
            stem_programme='".$_POST['stem_programme']."',
            stem_programme_collection='".$_POST['stem_programme_collection']."',
            stem_student_kit='".$_POST['stem_student_kit']."',
            stem_student_kit_collection='".$_POST['stem_student_kit_collection']."'";
			//extend_year='$extend_year'";
			
		$save_sql .= isset($_POST['deposit_locked']) ? ", deposit_locked='".$_POST['deposit_locked']."'" : ", deposit_locked='N'"; 
		$save_sql .= isset($_POST['placement_locked']) ? ", placement_locked='".$_POST['placement_locked']."'" : ", placement_locked='N'"; 
		$save_sql .= isset($_POST['registration_locked']) ? ", registration_locked='".$_POST['registration_locked']."'" : ", registration_locked='N'"; 
		$save_sql .= isset($_POST['mobile_app_locked']) ? ", mobile_app_locked='".$_POST['mobile_app_locked']."'" : ", mobile_app_locked='N'"; 
		$save_sql .= "where sha1(id)='$get_sha1_fee_id'";
		
		$result=mysqli_query($connection, $save_sql);
		if($result){
			//echo "<script type='text/javascript'>window.top.location='index.php?p=fee&msg=Record Updated';</script>";
			$msg="Record Updated";
		}else{
			$msg="Failed to save data";
		}
      
		  }
	  }
	  if (isset($mode) and $mode=="SAVE") {
		  $from_date= $_POST['from_date'];
		  $from_date=convertDate2ISO($from_date);
		  $extend_year = explode('-', $from_date)[0];
		   if(isset($_POST['country'],$_POST['state'],$_POST['registration'],$_POST['mobile_app'])) {
		   $save_sql="INSERT INTO fee ( country,state,fees_structure,subject,registration,mobile_app,programme_package,from_date,to_date,school_fee,multimedia_fee,facility_fee,eife,iq_math,mandarin,international_art,integrated_modules,link_think,mandarin_modules,basic_afternoon_p,afternoon_robotic,insurance,uniform,gymwear,q_dees_kit,q_dees_bag,pendidikan_islam,robotic_plus,school_collection,multimedia_collection,facility_collection,enhanced_collection,iq_math_collection,mandarin_collection,international_collection,integrated_collection,link_collection,mandarin_m_collection,basic_collection,afternoon_robotic_collection,mobile_collection,pendidikan_islam_collection,robotic_plus_collection,total_fee,total_default_c,total_default_d,school_total_f,extend_year, stem_programme, stem_programme_collection, stem_student_kit, stem_student_kit_collection) VALUES ('".$_POST['country']."','$state','".$_POST['fees_structure']."','".$_POST['subject']."','".$_POST['registration']."','".$_POST['mobile_app']."','".$_POST['programme_package']."','".$_POST['from_date']."','".$_POST['to_date']."','".$_POST['school_fee']."','".$_POST['multimedia_fee']."','".$_POST['facility_fee']."','".$_POST['eife']."','".$_POST['iq_math']."','".$_POST['mandarin']."','".$_POST['international_art']."','".$_POST['integrated_modules']."','".$_POST['link_think']."','".$_POST['mandarin_modules']."','".$_POST['basic_afternoon_p']."','".$_POST['afternoon_robotic']."','".$_POST['insurance']."','".$_POST['uniform']."','".$_POST['gymwear']."','".$_POST['q_dees_kit']."','".$_POST['q_dees_bag']."','".$_POST['pendidikan_islam']."','".$_POST['robotic_plus']."','".$_POST['school_collection']."','".$_POST['multimedia_collection']."','".$_POST['facility_collection']."','".$_POST['enhanced_collection']."','".$_POST['iq_math_collection']."','".$_POST['mandarin_collection']."','".$_POST['international_collection']."','".$_POST['integrated_collection']."','".$_POST['link_collection']."','".$_POST['mandarin_m_collection']."','".$_POST['basic_collection']."','".$_POST['afternoon_robotic_collection']."','".$_POST['mobile_collection']."','".$_POST['pendidikan_islam_collection']."','".$_POST['robotic_plus_collection']."','".$_POST['total_fee']."','".$_POST['total_default_c']."','".$_POST['total_default_d']."','".$_POST['school_total_f']."','$extend_year','".$_POST['stem_programme']."','".$_POST['stem_programme_collection']."','".$_POST['stem_student_kit']."','".$_POST['stem_student_kit_collection']."')";
		 
			$result=mysqli_query($connection, $save_sql);
			 
		if($result){
			//echo "<script type='text/javascript'>window.top.location='index.php?p=fee&msg=Record saved';</script>";
			//$msg="Record saved";
			$msg="Your respond has been submitted successfully";
		}else{
			$msg="Failed to save data";
		}
		   }
	  }
	  
	  $get_sha1_id=$_GET['id'];
	  if (isset($mode) and $mode=="DUPLICATE" AND $get_sha1_id != '') {
		  $save_sql="INSERT INTO fee  (`subject`, `deposit`, `deposit_locked`, `placement`, `placement_locked`, `registration`, `registration_locked`, `mobile_app`, `mobile_app_locked`, `country`, `state`, `fees_structure`, `programme_package`, `from_date`, `to_date`, `school_fee`, `multimedia_fee`, `facility_fee`, `eife`, `iq_math`, `mandarin`, `international_art`, `integrated_modules`, `link_think`, `mandarin_modules`, `basic_afternoon_p`, `afternoon_robotic`, `insurance`, `uniform`, `gymwear`, `q_dees_kit`, `q_dees_bag`, `pendidikan_islam`, `robotic_plus`, `school_collection`, `multimedia_collection`, `facility_collection`, `enhanced_collection`, `iq_math_collection`, `mandarin_collection`, `international_collection`, `integrated_collection`, `link_collection`, `mandarin_m_collection`, `basic_collection`, `afternoon_robotic_collection`, `mobile_collection`, `pendidikan_islam_collection`, `robotic_plus_collection`, `total_fee`, `total_default_c`, `total_default_d`, `school_total_f`, `extend_year`, `stem_programme`,`stem_programme_collection`,`stem_student_kit`,`stem_student_kit_collection`) SELECT  `subject`, `deposit`, `deposit_locked`, `placement`, `placement_locked`, `registration`, `registration_locked`, `mobile_app`, `mobile_app_locked`, `country`, `state`, `fees_structure`, `programme_package`, `from_date`, `to_date`, `school_fee`, `multimedia_fee`, `facility_fee`, `eife`, `iq_math`, `mandarin`, `international_art`, `integrated_modules`, `link_think`, `mandarin_modules`, `basic_afternoon_p`, `afternoon_robotic`, `insurance`, `uniform`, `gymwear`, `q_dees_kit`, `q_dees_bag`, `pendidikan_islam`, `robotic_plus`, `school_collection`, `multimedia_collection`, `facility_collection`, `enhanced_collection`, `iq_math_collection`, `mandarin_collection`, `international_collection`, `integrated_collection`, `link_collection`, `mandarin_m_collection`, `basic_collection`, `afternoon_robotic_collection`, `mobile_collection`, `pendidikan_islam_collection`, `robotic_plus_collection`, `total_fee`, `total_default_c`, `total_default_d`, `school_total_f`, `extend_year`,`stem_programme`,`stem_programme_collection`,`stem_student_kit`,`stem_student_kit_collection` FROM fee WHERE sha1(id) ='$get_sha1_id'";  
		$result=mysqli_query($connection, $save_sql);
		if($result){
			echo "<script type='text/javascript'>window.top.location='index.php?p=fee&mode=DUPLICATE&msg=Record duplicated';</script>";
		
		}else{
			$msg="Failed to duplicated data";
		}
		   
	  }
	  if (isset($mode) and $mode=="DEL") {
		  $save_sql="DELETE from `fee` where sha1(id)='$get_sha1_id'";
			$result=mysqli_query($connection, $save_sql);
		if($result){
			//echo "<script type='text/javascript'>window.top.location='index.php?p=fee&msg=Record saved';</script>";
			$msg="Record deleted";
		}else{
			$msg="Failed to save data";
		}
		   
	  }
	  
	  $edit_sql="SELECT * from `fee` where sha1(id)='$get_sha1_id'";
	 
      $result=mysqli_query($connection, $edit_sql);
      $edit_row=mysqli_fetch_assoc($result);

?>

<!--<a href="index.php?p=order_status_pg1">                 
             <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>-->
<style>
.page_title {
    position: absolute;
    right: 34px;
}

.uk-margin-right {
    margin-top: 40px;
}

.form_1 {
    padding: 9px 15px;
    border-radius: 10px;
    box-shadow: 0px 2px 3px 0px #00000021 !important;
    font-size: 1.2rem;
    font-weight: bold;
    background: #fff;
    cursor: pointer;
}

.form_2 {
    padding: 9px 15px;
    border-radius: 10px;
    box-shadow: 0px 2px 3px 0px #00000021 !important;
    font-size: 1.2rem;
    font-weight: bold;
    background: #fff;
}
</style>
<span class="sad_l">
<a href="./index.php?p=fee"><span id="form_1" class="form_1">Default Fee</span></a>
<a style="" href="./index.php?p=fee_approve"><span id="form_2" class="form_2">Request Fee</span></a>
<a style="" href="./index.php?p=addon_approve"><span id="form_2" class="form_2">Add On Product Request</span></a>
</span>
<span>
    <span class="page_title"><img src="/images/title_Icons/Visitor Reg.png">Fees Setting</span>
</span>

<div class="uk-margin-right">
    <div class="uk-width-1-1 myheader">
        <h2 class="uk-text-center myheader-text-color">Fees Setting</h2>
    </div>
    <div class="uk-form uk-form-small">
        <?php
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "FeeEdit"))) {
	if(isset($_GET["mode"]) && $_GET["mode"]=="EDIT" && isset($_GET["id"])){
?>
        <form name="frmCourse" id="frmCourse" method="post" action="index.php?p=fee&mode=EDIT">
            <?php
	}else{
?>
            <form name="frmCourse" id="frmCourse" method="post" action="index.php?p=fee&mode=SAVE">
                <?php
	}
}
?>
                <div class="uk-grid">
                    <input type="hidden" id="fee_id" name="fee_id" value="<?php echo $_GET["id"]; ?>" />
                    <div class="uk-width-medium-5-10">
                        <table class="uk-table uk-table-small">
                            <tr>
                                <td colspan="5" style="font-size:18px; font-weight:bold;border:none;">Part A (Centre
                                    Information)</td>
                            </tr>
                            <tr class="uk-text-small">
                                <td style="border:none;font-size:13px;" class="uk-width-3-10 uk-text-bold">Country<span
                                        class="text-danger">*</span>:</td>
                                <td style="border:none;" class="uk-width-7-10">
                                    <select name="country" id="country" class="uk-width-1-1">
                                        <option value="">Select</option>
                                        <?php
                              $sql = "SELECT * from codes where module='COUNTRY' order by code";
                              $result = mysqli_query($connection, $sql);
                              while ($row = mysqli_fetch_assoc($result)) {
                              ?>
                                        <option value="<?php echo $row["code"] ?>" <?php if ($row["code"] == $edit_row["country"]) {
                                                                                 echo "selected";
                                                                              } ?>><?php echo $row["code"] ?></option>
                                        <?php
                              }
                              ?>
                                    </select>

                                    <span id="validationCountry" style="color: red; display: none;">Please select
                                        Country</span>
                                </td>
                            </tr>
                            <tr class="uk-text-small">
                                <td style="border:none;font-size:13px;" class="uk-width-3-10 uk-text-bold">State<span
                                        class="text-danger">*</span>:</td>
                                <td style="border:none;" class="uk-width-7-10">
                                    <select name="state[]" id="state" class="uk-width-1-1" multiple>
                                        <?php
                              $stateArray = explode(', ', $edit_row['state']);
                              if ($edit_row["country"] != "") {
                              ?>
                                        <?php
                                 $sql = "SELECT * from codes where country='" . $edit_row["country"] . "' and module='STATE' order by code";
                                 $result = mysqli_query($connection, $sql);
                                 while ($row = mysqli_fetch_assoc($result)) {
                                 ?>
                                        <option value="<?php echo $row['code'] ?>" <?php if (in_array($row["code"], $stateArray)) {
                                                                                    echo "selected";
                                                                                 } ?>><?php echo $row["code"] ?>
                                        </option>
                                        <?php
                                 }
                              } else {
                                 ?>
                                        <option value="">Please Select Country First</option>
                                        <?php
                              }
                              ?>

                                    </select>

                                    <span id="validationState" style="color: red; display: none;">Please select
                                        State</span>
                                </td>
                            </tr>
                            <tr class="uk-text-small">
                                <td style="border:none;font-size:13px;" class="uk-width-3-10 uk-text-bold">Name of Fees
                                    Structure:</td>
                                <td style="border:none;" class="uk-width-7-10">
                                    <input class="uk-width-1-1" type="text"
                                        value="<?php echo $edit_row['fees_structure']?>" name="fees_structure"
                                        id="fees_structure">
                                    <span id="validationStructure" style="color: red; display: none;">Please insert
                                        Name</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div style="margin-top:45px;" class="uk-width-medium-5-10">
                        <table class="uk-table uk-table-small">
                            <tr class="uk-text-small">
                                <td style="border:none;font-size:13px;" class="uk-width-3-10 uk-text-bold">Student Entry
                                    Level<span class="text-danger">*</span>:</td>
                                <td style="border:none;" class="uk-width-7-10">
                                    <select name="subject" id="subject" class="uk-width-1-1">
                                        <option value="">Please Select Student Entry Level</option>
                                        <option value="EDP" <?php if($edit_row['subject']=='EDP') {echo 'selected';}?>>
                                            EDP</option>
                                        <option value="QF1" <?php if($edit_row['subject']=='QF1') {echo 'selected';}?>>
                                            QF1</option>
                                        <option value="QF2" <?php if($edit_row['subject']=='QF2') {echo 'selected';}?>>
                                            QF2</option>
                                        <option value="QF3" <?php if($edit_row['subject']=='QF3') {echo 'selected';}?>>
                                            QF3</option>
                                    </select>
                                    <span id="validationSubject" style="color: red; display: none;">Please select
                                        Student Entry Level</span>
                                </td>
                            </tr>
                        </table>
                        <table class="uk-table uk-table-small">
                            <tr class="uk-text-small">
                                <td style="border:none;font-size:13px;" class="uk-width-3-10 uk-text-bold">Programme
                                    Package<span class="text-danger">*</span>:</td>
                                <td style="border:none;" class="uk-width-7-10">
                                    <select name="programme_package" id="programme_package" class="uk-width-1-1">
                                        <option value="">Please Select Programme Package</option>
                                        <option value="Full Day"
                                            <?php if($edit_row['programme_package']=='Full Day') {echo 'selected';}?>>
                                            Full Day</option>
                                        <option value="Half Day"
                                            <?php if($edit_row['programme_package']=='Half Day') {echo 'selected';}?>>
                                            Half Day</option>
                                        <option value="3/4 Day"
                                            <?php if($edit_row['programme_package']=='3/4 Day') {echo 'selected';}?>>3/4
                                            Day</option>
                                    </select>
                                    <span id="validationprogramme_package" style="color: red; display: none;">Please
                                        select Programme Package</span>
                                </td>
                            </tr>
                        </table>
                        <table class="uk-table uk-table-small">
                            <tr class="uk-text-small">
                                <td style="border:none;font-size:13px;" class="uk-width-3-10 uk-text-bold">Commencement
                                    Date<span class="text-danger">*</span>:</td>
                                <td style="border:none;" class="uk-width-3-10">
                                    <input style="width: 100%;" type="text" class="" name="from_date" id="from_date"
                                        placeholder="Start Date" data-uk-datepicker="{format: 'YYYY-MM-DD'}"
                                        value="<?php echo $edit_row['from_date']?>" autocomplete="off">
                                    <span id="validationdf" style="color: red; display: none;">Please input Start
                                        Date</span>
                                </td>
                                <td style="border:none;" class="uuk-width-3-10">
                                    <input style="width: 100%;" type="text" class="" name="to_date" id="to_date"
                                        placeholder="End Date" data-uk-datepicker="{format: 'YYYY-MM-DD'}"
                                        value="<?php echo $edit_row['to_date']?>" autocomplete="off">
                                    <span id="validationdt" style="color: red; display: none;">Please input End
                                        Date</span>
                                </td>
                            </tr>
                        </table>

                    </div>
                    <div class="uk-width-medium-10-10">
                        <table class="uk-table uk-table-small">
                            <tr class="uk-text-small">
                                <td class="uk-width-3-10 uk-text-bold"></td>
                                <td class="uk-width-7-10">
                                </td>
                                <td class="uk-width-7-10">
                                </td>
                                <td class="uk-width-7-10">
                                </td>
                            </tr>
                        </table>
                        <table class="uk-table uk-table-small">
                            <tr>
                                <td colspan="5" style="font-size:18px; font-weight:bold; border:none;">Part B (School
                                    Fees)</td>
                            </tr>
                            <tr class="">
                                <td style="border:none;" style=" margin-top:50px;" class="uk-width-1-10 uk-text-bold">
                                </td>
                                <td style="border:none;" class="uk-width-1-10">
                                    <span style="margin-left: 30px;">Default Fee</span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <span style="margin-right:30%;">Collection Pattern</span>
                                </td>
                            </tr>
                        </table>
                        <table class="uk-table uk-table-small">
                            <tr class="uk-text-small">
                                <td style="font-size:13px;" class="uk-width-1-10 uk-text-bold">School Fees<span
                                        class="text-danger">*</span>:</td>
                                <td class="uk-width-2-10">
                                    <input class="uk-width-1-1 school" type="number" name="school_fee" id="school_fee"
                                        step="0.01" value="<?php echo $edit_row['school_fee']  ?>">
                                    <span id="validationschool" style="color: red; display: none;">Please input School
                                        Fees</span>
                                </td>
                                <td class="uk-width-2-10">
                                    <select name="school_collection" id="school_collection" class=""
                                        style="width: 100%;">
                                        <option value=""></option>
                                        <option value="Monthly"
                                            <?php if($edit_row['school_collection']=='Monthly') {echo 'selected';}?>>
                                            Monthly</option>
                                        <option value="Termly"
                                            <?php if($edit_row['school_collection']=='Termly') {echo 'selected';}?>>
                                            Termly</option>
                                        <option value="Half Year"
                                            <?php if($edit_row['school_collection']=='Half Year') {echo 'selected';}?>>
                                            Half Year</option>
                                        <option value="Annually"
                                            <?php if($edit_row['school_collection']=='Annually') {echo 'selected';}?>>
                                            Annually</option>
                                    </select><br>
                                    <span id="validationsc" style="color: red; display: none;">Please select Collection
                                        Pattern</span>
                                </td>
                            </tr>
                        </table>
                        <table class="uk-table uk-table-small">
                            <tr class="uk-text-small">
                                <td style="font-size:13px;" class="uk-width-1-10 uk-text-bold">Multimedia Fees<span
                                        class="text-danger">*</span>:</td>
                                <td class="uk-width-2-10">
                                    <input class="uk-width-1-1 school" type="number" name="multimedia_fee"
                                        id="multimedia_fee" step="0.01"
                                        value="<?php echo $edit_row['multimedia_fee'] ?>">
                                    <span id="validationMultimedia" style="color: red; display: none;">Please input
                                        Multimedia Fees</span>
                                </td>
                                <td class="uk-width-2-10">
                                    <select name="multimedia_collection" id="multimedia_collection" class=""
                                        style="width: 100%;">
                                        <option value=""></option>
                                        <option value="Monthly"
                                            <?php if($edit_row['multimedia_collection']=='Monthly') {echo 'selected';}?>>
                                            Monthly</option>
                                        <option value="Termly"
                                            <?php if($edit_row['multimedia_collection']=='Termly') {echo 'selected';}?>>
                                            Termly</option>
                                        <option value="Half Year"
                                            <?php if($edit_row['multimedia_collection']=='Half Year') {echo 'selected';}?>>
                                            Half Year</option>
                                        <option value="Annually"
                                            <?php if($edit_row['multimedia_collection']=='Annually') {echo 'selected';}?>>
                                            Annually</option>
                                    </select><br>
                                    <span id="validationMc" style="color: red; display: none;">Please select Collection
                                        Pattern</span>
                                </td>
                            </tr>
                        </table>
                        <table class="uk-table uk-table-small">
                            <tr class="uk-text-small">
                                <td style="font-size:13px;" class="uk-width-1-10 uk-text-bold">Facility Fees<span
                                        class="text-danger">*</span>:</td>
                                <td class="uk-width-2-10">
                                    <input class="uk-width-1-1 school" type="number" name="facility_fee"
                                        id="facility_fee" step="0.01" value="<?php echo $edit_row['facility_fee'] ?>">
                                    <span id="validationFacility" style="color: red; display: none;">Please input
                                        Facility Fees</span>
                                </td>
                                <td class="uk-width-2-10">
                                    <select name="facility_collection" id="facility_collection" class=""
                                        style="width: 100%;">
                                        <option value=""></option>
                                        <option value="Monthly"
                                            <?php if($edit_row['facility_collection']=='Monthly') {echo 'selected';}?>>
                                            Monthly</option>
                                        <option value="Termly"
                                            <?php if($edit_row['facility_collection']=='Termly') {echo 'selected';}?>>
                                            Termly</option>
                                        <option value="Half Year"
                                            <?php if($edit_row['facility_collection']=='Half Year') {echo 'selected';}?>>
                                            Half Year</option>
                                        <option value="Annually"
                                            <?php if($edit_row['facility_collection']=='Annually') {echo 'selected';}?>>
                                            Annually</option>
                                    </select><br>
                                    <span id="validationfc" style="color: red; display: none;">Please select Collection
                                        Pattern</span>
                                </td>
                            </tr>
                        </table>
                        <table class="uk-table uk-table-small">
                            <tr class="uk-text-small">
                                <td style="font-size:13px;" class="uk-width-1-10 uk-text-bold">Enhanced Foundation
                                    International English<span class="text-danger">*</span>:</td>
                                <td class="uk-width-2-10">
                                    <input class="uk-width-1-1 school" type="number" name="eife" id="eife" step="0.01"
                                        value="<?php echo $edit_row['eife'] ?>">
                                    <span id="validationeife" style="color: red; display: none;">Please input Enhanced
                                        International Foundation English</span>
                                </td>
                                <td class="uk-width-2-10">
                                    <select name="enhanced_collection" id="enhanced_collection" class=""
                                        style="width: 100%;">
                                        <option value=""></option>
                                        <option value="Monthly"
                                            <?php if($edit_row['enhanced_collection']=='Monthly') {echo 'selected';}?>>
                                            Monthly</option>
                                        <option value="Termly"
                                            <?php if($edit_row['enhanced_collection']=='Termly') {echo 'selected';}?>>
                                            Termly</option>
                                        <option value="Half Year"
                                            <?php if($edit_row['enhanced_collection']=='Half Year') {echo 'selected';}?>>
                                            Half Year</option>
                                        <option value="Annually"
                                            <?php if($edit_row['enhanced_collection']=='Annually') {echo 'selected';}?>>
                                            Annually</option>
                                    </select><br>
                                    <span id="validationec" style="color: red; display: none;">Please select Collection
                                        Pattern</span>
                                </td>
                            </tr>
                        </table>
                        <table class="uk-table uk-table-small">
                            <tr class="uk-text-small">
                                <td style="font-size:13px;" class="uk-width-1-10 uk-text-bold">Total:</td>
                                <td class="uk-width-2-10">
                                    <input class="uk-width-1-1" type="number" name="total_fee" id="total_fee"
                                        value="<?php echo $edit_row['total_fee'] ?>">
                                </td>
                                <td class="uk-width-2-10">
                                </td>
                            </tr>
                        </table>
                        <table class="uk-table uk-table-small">
                            <tr>
                                <td colspan="5" style="font-size:18px; font-weight:bold;">Part C (Enhanced Foundation
                                    Programme)</td>
                            </tr>
                            <tr class="uk-text-small">
                                <td style="font-size:13px;" class="uk-width-1-10 uk-text-bold">IQ Math<span
                                        class="text-danger">*</span>:</td>
                                <td class="uk-width-2-10">
                                    <input class="uk-width-1-1 math_default" type="number" name="iq_math" id="iq_math"
                                        step="0.01" value="<?php echo $edit_row['iq_math'] ?>">
                                    <span id="validationMath" style="color: red; display: none;">Please input IQ
                                        Math</span>
                                </td>
                                <td class="uk-width-2-10">
                                    <select name="iq_math_collection" id="iq_math_collection" class=""
                                        style="width: 100%;">
                                        <option value=""></option>
                                        <option value="Monthly"
                                            <?php if($edit_row['iq_math_collection']=='Monthly') {echo 'selected';}?>>
                                            Monthly</option>
                                        <option value="Termly"
                                            <?php if($edit_row['iq_math_collection']=='Termly') {echo 'selected';}?>>
                                            Termly</option>
                                        <option value="Half Year"
                                            <?php if($edit_row['iq_math_collection']=='Half Year') {echo 'selected';}?>>
                                            Half Year</option>
                                        <option value="Annually"
                                            <?php if($edit_row['iq_math_collection']=='Annually') {echo 'selected';}?>>
                                            Annually</option>
                                    </select><br>
                                    <span id="validationmc" style="color: red; display: none;">Please select Collection
                                        Pattern</span>
                                </td>
                            </tr>
                        </table>
                        <table class="uk-table uk-table-small">
                            <tr class="uk-text-small">
                                <td style="font-size:13px;" class="uk-width-1-10 uk-text-bold">Mandarin<span
                                        class="text-danger">*</span>:</td>
                                <td class="uk-width-2-10">
                                    <input class="uk-width-1-1 math_default" type="number" name="mandarin" id="mandarin"
                                        step="0.01" value="<?php echo $edit_row['mandarin'] ?>">
                                    <span id="validationMandarin" style="color: red; display: none;">Please input
                                        Mandarin</span>
                                </td>
                                <td class="uk-width-2-10">
                                    <select name="mandarin_collection" id="mandarin_collection" class=""
                                        style="width: 100%;">
                                        <option value=""></option>
                                        <option value="Monthly"
                                            <?php if($edit_row['mandarin_collection']=='Monthly') {echo 'selected';}?>>
                                            Monthly</option>
                                        <option value="Termly"
                                            <?php if($edit_row['mandarin_collection']=='Termly') {echo 'selected';}?>>
                                            Termly</option>
                                        <option value="Half Year"
                                            <?php if($edit_row['mandarin_collection']=='Half Year') {echo 'selected';}?>>
                                            Half Year</option>
                                        <option value="Annually"
                                            <?php if($edit_row['mandarin_collection']=='Annually') {echo 'selected';}?>>
                                            Annually</option>
                                    </select><br>
                                    <span id="validationmdc" style="color: red; display: none;">Please select Collection
                                        Pattern</span>
                                </td>
                            </tr>
                        </table>
                        <table class="uk-table uk-table-small">
                            <tr class="uk-text-small">
                                <td style="font-size:13px;" class="uk-width-1-10 uk-text-bold">International Art<span
                                        class="text-danger">*</span>:</td>
                                <td class="uk-width-2-10">
                                    <input class="uk-width-1-1 math_default" type="number" name="international_art"
                                        id="international_art" step="0.01"
                                        value="<?php echo $edit_row['international_art'] ?>">
                                    <span id="validationinternational_art" style="color: red; display: none;">Please
                                        input International Art</span>
                                </td>
                                <td class="uk-width-2-10">
                                    <select name="international_collection" id="international_collection" class=""
                                        style="width: 100%;">
                                        <option value=""></option>
                                        <option value="Monthly"
                                            <?php if($edit_row['international_collection']=='Monthly') {echo 'selected';}?>>
                                            Monthly</option>
                                        <option value="Termly"
                                            <?php if($edit_row['international_collection']=='Termly') {echo 'selected';}?>>
                                            Termly</option>
                                        <option value="Half Year"
                                            <?php if($edit_row['international_collection']=='Half Year') {echo 'selected';}?>>
                                            Half Year</option>
                                        <option value="Annually"
                                            <?php if($edit_row['international_collection']=='Annually') {echo 'selected';}?>>
                                            Annually</option>
                                    </select><br>
                                    <span id="validationinc" style="color: red; display: none;">Please select Collection
                                        Pattern</span>
                                </td>
                            </tr>
                        </table>
                        <table class="uk-table uk-table-small">
                            <tr class="uk-text-small">
                                <td style="font-size:13px;" class="uk-width-1-10 uk-text-bold">Robotic Plus<span
                                        class="text-danger">*</span>:</td>
                                <td class="uk-width-2-10">
                                    <input class="uk-width-1-1 math_default" type="number" name="robotic_plus"
                                        id="robotic_plus" step="0.01"
                                        value="<?php echo $edit_row['robotic_plus'] ?>">
                                    <span id="validationrobotic_plus" style="color: red; display: none;">Please
                                        input Robotic Plus</span>
                                </td>
                                <td class="uk-width-2-10">
                                    <select name="robotic_plus_collection" id="robotic_plus_collection" class=""
                                        style="width: 100%;">
                                        <option value=""></option>
                                        <option value="Monthly"
                                            <?php if($edit_row['robotic_plus_collection']=='Monthly') {echo 'selected';}?>>
                                            Monthly</option>
                                        <option value="Termly"
                                            <?php if($edit_row['robotic_plus_collection']=='Termly') {echo 'selected';}?>>
                                            Termly</option>
                                        <option value="Half Year"
                                            <?php if($edit_row['robotic_plus_collection']=='Half Year') {echo 'selected';}?>>
                                            Half Year</option>
                                        <option value="Annually"
                                            <?php if($edit_row['robotic_plus_collection']=='Annually') {echo 'selected';}?>>
                                            Annually</option>
                                    </select><br>
                                    <span id="validationrp" style="color: red; display: none;">Please select Collection
                                        Pattern</span>
                                </td>
                            </tr>
                        </table>
                        <table class="uk-table uk-table-small">
                            <tr class="uk-text-small">
                                <td style="font-size:13px;" class="uk-width-1-10 uk-text-bold">Total:</td>
                                <td class="uk-width-2-10">
                                    <input class="uk-width-1-1" type="number" name="total_default_c"
                                        id="total_default_c" value="<?php echo $edit_row['total_default_c'] ?>">
                                </td>
                                <td class="uk-width-2-10">
                                </td>
                            </tr>
                        </table>

                        <table class="uk-table uk-table-small">
                            <tr>
                                <td colspan="5" style="font-size:18px; font-weight:bold;">Part D (Material Fees)</td>
                            </tr>
                            <tr class="uk-text-small">
                                <td style="font-size:13px;" class="uk-width-1-10 uk-text-bold">Integrated Modules<span
                                        class="text-danger">*</span>:</td>
                                <td class="uk-width-2-10">
                                    <input class="uk-width-1-1 int_default" type="number" name="integrated_modules"
                                        id="integrated_modules" step="0.01"
                                        value="<?php echo $edit_row['integrated_modules'] ?>">
                                    <span id="validationIntegrated" style="color: red; display: none;">Please input
                                        Integrated Modules</span>
                                </td>
                                <td class="uk-width-2-10">
                                    <select name="integrated_collection" id="integrated_collection" class=""
                                        style="width: 100%;">
                                        <option value=""></option>
                                        <option value="Monthly"
                                            <?php if($edit_row['integrated_collection']=='Monthly') {echo 'selected';}?>>
                                            Monthly</option>
                                        <option value="Termly"
                                            <?php if($edit_row['integrated_collection']=='Termly') {echo 'selected';}?>>
                                            Termly</option>
                                        <option value="Half Year"
                                            <?php if($edit_row['integrated_collection']=='Half Year') {echo 'selected';}?>>
                                            Half Year</option>
                                        <option value="Annually"
                                            <?php if($edit_row['integrated_collection']=='Annually') {echo 'selected';}?>>
                                            Annually</option>
                                    </select><br>
                                    <span id="validationitc" style="color: red; display: none;">Please select Collection
                                        Pattern</span>
                                </td>
                            </tr>
                        </table>
                        <table class="uk-table uk-table-small">
                            <tr class="uk-text-small">
                                <td style="font-size:13px;" class="uk-width-1-10 uk-text-bold">Link & Think Reading
                                    Series<span class="text-danger">*</span>:</td>
                                <td class="uk-width-2-10">
                                    <input class="uk-width-1-1 int_default" type="number" name="link_think"
                                        id="link_think" step="0.01" value="<?php echo $edit_row['link_think'] ?>">
                                    <span id="validationLink" style="color: red; display: none;">Please input Link &
                                        Think Reading Series</span>
                                </td>
                                <td class="uk-width-2-10">
                                    <select name="link_collection" id="link_collection" class="" style="width: 100%;">
                                        <option value=""></option>
                                        <option value="Monthly"
                                            <?php if($edit_row['link_collection']=='Monthly') {echo 'selected';}?>>
                                            Monthly</option>
                                        <option value="Termly"
                                            <?php if($edit_row['link_collection']=='Termly') {echo 'selected';}?>>Termly
                                        </option>
                                        <option value="Half Year"
                                            <?php if($edit_row['link_collection']=='Half Year') {echo 'selected';}?>>
                                            Half Year</option>
                                        <option value="Annually"
                                            <?php if($edit_row['link_collection']=='Annually') {echo 'selected';}?>>
                                            Annually</option>
                                    </select><br>
                                    <span id="validationlnc" style="color: red; display: none;">Please select Collection
                                        Pattern</span>
                                </td>
                            </tr>
                        </table>
                        <table class="uk-table uk-table-small">
                            <tr class="uk-text-small">
                                <td style="font-size:13px;" class="uk-width-1-10 uk-text-bold">Mandarin Modules<span
                                        class="text-danger">*</span>:</td>
                                <td class="uk-width-2-10">
                                    <input class="uk-width-1-1 int_default" type="number" name="mandarin_modules"
                                        id="mandarin_modules" step="0.01"
                                        value="<?php echo $edit_row['mandarin_modules'] ?>">
                                    <span id="validationMandarinM" style="color: red; display: none;">Please input
                                        Mandarin Modules</span>
                                </td>
                                <td class="uk-width-2-10">
                                    <select name="mandarin_m_collection" id="mandarin_m_collection" class=""
                                        style="width: 100%;">
                                        <option value=""></option>
                                        <option value="Monthly"
                                            <?php if($edit_row['mandarin_m_collection']=='Monthly') {echo 'selected';}?>>
                                            Monthly</option>
                                        <option value="Termly"
                                            <?php if($edit_row['mandarin_m_collection']=='Termly') {echo 'selected';}?>>
                                            Termly</option>
                                        <option value="Half Year"
                                            <?php if($edit_row['mandarin_m_collection']=='Half Year') {echo 'selected';}?>>
                                            Half Year</option>
                                        <option value="Annually"
                                            <?php if($edit_row['mandarin_m_collection']=='Annually') {echo 'selected';}?>>
                                            Annually</option>
                                    </select><br>
                                    <span id="validationmmc" style="color: red; display: none;">Please select Collection
                                        Pattern</span>
                                </td>
                            </tr>
                        </table>
                        <!-- BEGIN STEM ADD -->
                        <table class="uk-table uk-table-small">
                            <tr class="uk-text-small">
                                <td style="font-size:13px;" class="uk-width-1-10 uk-text-bold">STEM Programme<span
                                        class="text-danger">*</span>:</td>
                                <td class="uk-width-2-10">
                                    <input class="uk-width-1-1 int_default" type="number" name="stem_programme"
                                        id="stem_programme" step="0.01"
                                        value="<?php echo $edit_row['stem_programme'] ?>">
                                    <span id="validationSTEM" style="color: red; display: none;">Please input
                                        STEM Programme</span>
                                </td>
                                <td class="uk-width-2-10">
                                    <select name="stem_programme_collection" id="stem_programme_collection" class=""
                                        style="width: 100%;">
                                        <option value=""></option>
                                        <option value="Termly"
                                            <?php if($edit_row['stem_programme_collection']=='Termly') {echo 'selected';}?>>
                                            Termly</option>
                                    </select><br>
                                    <span id="validationCollectionStemPgm" style="color: red; display: none;">Please select Collection
                                        Pattern</span>
                                </td>
                            </tr>
                        </table>
                        <!-- END STEM ADD -->

                        <!-- BEGIN STEM STUDENT KIT ADD -->
                        <table class="uk-table uk-table-small">
                            <tr class="uk-text-small">
                                <td style="font-size:13px;" class="uk-width-1-10 uk-text-bold">STEM Student Kit<span
                                        class="text-danger">*</span>:</td>
                                <td class="uk-width-2-10">
                                    <input class="uk-width-1-1 int_default" type="number" name="stem_student_kit"
                                        id="stem_student_kit" step="0.01"
                                        value="<?php echo $edit_row['stem_student_kit'] ?>">
                                    <span id="validationSTEMStudentKit" style="color: red; display: none;">Please input
                                        STEM Programme</span>
                                </td>
                                <td class="uk-width-2-10">
                                    <select name="stem_student_kit_collection" id="stem_student_kit_collection" class=""
                                        style="width: 100%;">
                                        <option value=""></option>
                                        <option value="Termly"
                                            <?php if($edit_row['stem_student_kit_collection']=='Termly') {echo 'selected';}?>>
                                            Termly</option>
                                    </select><br>
                                    <span id="validationCollectionStemKit" style="color: red; display: none;">Please select Collection
                                        Pattern</span>
                                </td>
                            </tr>
                        </table>
                        <!-- END STEM ADD -->

                        <table class="uk-table uk-table-small">
                            <tr class="uk-text-small">
                                <td style="font-size:13px;" class="uk-width-1-10 uk-text-bold">Total:</td>
                                <td class="uk-width-2-10">
                                    <input class="uk-width-1-1" type="number" name="total_default_d"
                                        id="total_default_d" value="<?php echo $edit_row['total_default_d'] ?>">
                                </td>
                                <td class="uk-width-2-10">
                                </td>
                            </tr>
                        </table>
                        <table class="uk-table uk-table-small">
                            <tr>
                                <td colspan="5" style="font-size:18px; font-weight:bold;">Part E (Afternoon Programme)
                                </td>
                            </tr>
                            <tr class="uk-text-small">
                                <td style="font-size:13px;" class="uk-width-1-10 uk-text-bold">Basic Afternoon
                                    Programme<span class="text-danger">*</span>:</td>
                                <td class="uk-width-2-10">
                                    <input class="uk-width-1-1" type="number" name="basic_afternoon_p"
                                        id="basic_afternoon_p" step="0.01"
                                        value="<?php echo $edit_row['basic_afternoon_p'] ?>">
                                    <span id="validationBasic" style="color: red; display: none;">Please input Basic
                                        Afternoon Programme</span>
                                </td>
                                <td class="uk-width-2-10">
                                    <select name="basic_collection" id="basic_collection" class="" style="width: 100%;">
                                        <option value=""></option>
                                        <option value="Monthly"
                                            <?php if($edit_row['basic_collection']=='Monthly') {echo 'selected';}?>>
                                            Monthly</option>
                                        <option value="Termly"
                                            <?php if($edit_row['basic_collection']=='Termly') {echo 'selected';}?>>
                                            Termly</option>
                                        <option value="Half Year"
                                            <?php if($edit_row['basic_collection']=='Half Year') {echo 'selected';}?>>
                                            Half Year</option>
                                        <option value="Annually"
                                            <?php if($edit_row['basic_collection']=='Annually') {echo 'selected';}?>>
                                            Annually</option>

                                    </select><br>
                                    <span id="validationbc" style="color: red; display: none;">Please select Collection
                                        Pattern</span>
                                </td>
                            </tr>
                            <tr class="uk-text-small">
                                <td style="font-size:13px;" class="uk-width-1-10 uk-text-bold">Afternoon Programme + Robotics<span class="text-danger">*</span>:</td>
                                <td class="uk-width-2-10">
                                    <input class="uk-width-1-1" type="number" name="afternoon_robotic"
                                        id="afternoon_robotic" step="0.01"
                                        value="<?php echo $edit_row['afternoon_robotic'] ?>">
                                    <span id="validationAfternoonRobotics" style="color: red; display: none;">Please input Afternoon Programme + Robotics</span>
                                </td>
                                <td class="uk-width-2-10">
                                    <select name="afternoon_robotic_collection" id="afternoon_robotic_collection" class="" style="width: 100%;">
                                        <option value=""></option>
                                        <option value="Monthly"
                                            <?php if($edit_row['afternoon_robotic_collection']=='Monthly') {echo 'selected';}?>>
                                            Monthly</option>
                                        <option value="Termly"
                                            <?php if($edit_row['afternoon_robotic_collection']=='Termly') {echo 'selected';}?>>
                                            Termly</option>
                                        <option value="Half Year"
                                            <?php if($edit_row['afternoon_robotic_collection']=='Half Year') {echo 'selected';}?>>
                                            Half Year</option>
                                        <option value="Annually"
                                            <?php if($edit_row['afternoon_robotic_collection']=='Annually') {echo 'selected';}?>>
                                            Annually</option>

                                    </select><br>
                                    <span id="validationarc" style="color: red; display: none;">Please select Collection
                                        Pattern</span>
                                </td>
                            </tr>
                        </table>
                        <table class="uk-table uk-table-small">
                            <tr>
                                <td colspan="5" style="font-size:18px; font-weight:bold;">Part F (Upon Registration)
                                </td>
                            </tr>
                            <tr class="uk-text-small">
                                <td style="font-size:13px;" class="uk-width-1-10 uk-text-bold">Mobile App<span
                                        class="text-danger">*</span>:</td>
                                <td class="uk-width-2-10">
                                    <input class="uk-width-1-1 school_adjust" type="number" name="mobile_app"
                                        id="mobile_app" step="0.01" value="<?php echo $edit_row['mobile_app'] ?>">
                                    <span id="validationMobile" style="color: red; display: none;">Please input Mobile
                                        App</span>
                                </td>
                                <td class="uk-width-2-10">
                                    <select name="mobile_collection" id="mobile_collection" class=""
                                        style="width: 100%;">
                                        <option value=""></option>
                                        <option value="Monthly"
                                            <?php if($edit_row['mobile_collection']=='Monthly') {echo 'selected';}?>>
                                            Monthly</option>
                                        <option value="Termly"
                                            <?php if($edit_row['mobile_collection']=='Termly') {echo 'selected';}?>>
                                            Termly</option>
                                        <option value="Half Year"
                                            <?php if($edit_row['mobile_collection']=='Half Year') {echo 'selected';}?>>
                                            Half Year</option>
                                        <option value="Annually"
                                            <?php if($edit_row['mobile_collection']=='Annually') {echo 'selected';}?>>
                                            Annually</option>

                                    </select><br>
                                    <span id="validationmbc" style="color: red; display: none;">Please select Collection
                                        Pattern</span>
                                </td>
                            </tr>
                        </table>
                        <table class="uk-table uk-table-small">

                            <tr class="uk-text-small">
                                <td style="font-size:13px;" class="uk-width-1-10 uk-text-bold">Registration<span
                                        class="text-danger">*</span>:</td>
                                <td class="uk-width-2-10">
                                    <input class="uk-width-1-1 school_adjust" type="number" name="registration"
                                        id="registration" step="0.01" value="<?php echo $edit_row['registration'] ?>">
                                    <span id="validationRegistration" style="color: red; display: none;">Please input
                                        Registration</span>
                                </td>
                                <td class="uk-width-2-10">

                                </td>
                            </tr>
                        </table>

                        <table class="uk-table uk-table-small">
                            <tr class="uk-text-small">
                                <td style="font-size:13px;" class="uk-width-1-10 uk-text-bold">Insurance<span
                                        class="text-danger">*</span>:</td>
                                <td class="uk-width-2-10">
                                    <input class="uk-width-1-1 school_adjust" type="number" name="insurance"
                                        id="insurance" step="0.01" value="<?php echo $edit_row['insurance'] ?>">
                                    <span id="validationInsurance" style="color: red; display: none;">Please input
                                        Insurance</span>
                                </td>
                                <td class="uk-width-2-10"></td>
                            </tr>
                        </table>
                        <table class="uk-table uk-table-small">
                            <tr class="uk-text-small">
                                <td style="font-size:13px;" class="uk-width-1-10 uk-text-bold">Uniform (2 sets)<span
                                        class="text-danger">*</span>:</td>
                                <td class="uk-width-2-10">
                                    <input class="uk-width-1-1 school_adjust" type="number" name="uniform" id="uniform"
                                        step="0.01" value="<?php echo $edit_row['uniform'] ?>">
                                    <span id="validationUniform" style="color: red; display: none;">Please input
                                        Uniform</span>
                                </td>
                                <td class="uk-width-2-10"></td>
                            </tr>
                        </table>
                        <table class="uk-table uk-table-small">
                            <tr class="uk-text-small">
                                <td style="font-size:13px;" class="uk-width-1-10 uk-text-bold">Gymwear (1 set)<span
                                        class="text-danger">*</span>:</td>
                                <td class="uk-width-2-10">
                                    <input class="uk-width-1-1 school_adjust" type="number" name="gymwear" id="gymwear"
                                        step="0.01" value="<?php echo $edit_row['gymwear'] ?>">
                                    <span id="validationGymwear" style="color: red; display: none;">Please input
                                        Gymwear</span>
                                </td>
                                <td class="uk-width-2-10"></td>
                            </tr>
                        </table>
                        <table class="uk-table uk-table-small">
                            <tr class="uk-text-small">
                                <td style="font-size:13px;" class="uk-width-1-10 uk-text-bold">Q-dees Level Kit<span
                                        class="text-danger">*</span>:</td>
                                <td class="uk-width-2-10">
                                    <input class="uk-width-1-1 school_adjust" type="number" name="q_dees_kit"
                                        id="q_dees_kit" step="0.01" value="<?php echo $edit_row['q_dees_kit'] ?>">
                                    <span id="validationQdees_kit" style="color: red; display: none;">Please input
                                        Q-dees Level Kit</span>
                                </td>
                                <td class="uk-width-2-10"></td>
                            </tr>
                        </table>
                        <table class="uk-table uk-table-small">
                            <tr class="uk-text-small">
                                <td style="font-size:13px;" class="uk-width-1-10 uk-text-bold">Q-dees Bag<span
                                        class="text-danger">*</span>:</td>
                                <td class="uk-width-2-10">
                                    <input class="uk-width-1-1 school_adjust" type="number" name="q_dees_bag"
                                        id="q_dees_bag" step="0.01" value="<?php echo $edit_row['q_dees_bag']  ?>">
                                    <span id="validationQdees_bag" style="color: red; display: none;">Please input
                                        Q-dees Bag</span>
                                </td>
                                <td class="uk-width-2-10"></td>
                            </tr>
                        </table>
                        <table class="uk-table uk-table-small">
                            <tr class="uk-text-small">
                                <td style="font-size:13px;" class="uk-width-1-10 uk-text-bold">Total:</td>
                                <td class="uk-width-2-10">
                                    <input class="uk-width-1-1" type="number" name="school_total_f" id="school_total_f"
                                        value="<?php echo $edit_row['school_total_f'] ?>">
                                </td>
                                <td class="uk-width-2-10">
                                </td>
                            </tr>
                        </table>
                        <table class="uk-table uk-table-small">
                            <tr>
                                <td colspan="5" style="font-size:18px; font-weight:bold;">Part G (Pendidikan Islam)</td>
                            </tr>
                            <tr class="uk-text-small">
                                <td style="font-size:13px;" class="uk-width-1-10 uk-text-bold">Pendidikan Islam / Jawi<span class="text-danger">*</span>:</td>
                                <td class="uk-width-2-10">
                                    <input class="uk-width-1-1" type="number" name="pendidikan_islam"
                                        id="pendidikan_islam" step="0.01"
                                        value="<?php echo $edit_row['pendidikan_islam'] ?>">
                                    <span id="validationPendidikan_islam" style="color: red; display: none;">Please
                                        input Pendidikan Islam / Jawi</span>
                                </td>
                                <td class="uk-width-2-10">
                                    <select name="pendidikan_islam_collection" id="pendidikan_islam_collection" class=""
                                        style="width: 100%;">
                                        <option value=""></option>
                                        <option value="Monthly"
                                            <?php if($edit_row['pendidikan_islam_collection']=='Monthly') {echo 'selected';}?>>
                                            Monthly</option>
                                        <option value="Termly"
                                            <?php if($edit_row['pendidikan_islam_collection']=='Termly') {echo 'selected';}?>>
                                            Termly</option>
                                        <option value="Half Year"
                                            <?php if($edit_row['pendidikan_islam_collection']=='Half Year') {echo 'selected';}?>>
                                            Half Year</option>
                                        <option value="Annually"
                                            <?php if($edit_row['mobile_pendidikan_islam_collectioncollection']=='Annually') {echo 'selected';}?>>
                                            Annually</option>

                                    </select><br>
                                    <span id="validationpi" style="color: red; display: none;">Please select Collection
                                        Pattern</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <br>
                <div class="uk-text-center">
                    <?php
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "FeeEdit"))) {
?>
                    <button class="uk-button uk-button-primary">Save</button>
                    <?php
}
?>
                </div>
                <?php
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "FeeEdit"))) {
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
    <input type="hidden" name="p" id="p" value="fee">


    <div class="uk-grid">
        <div class="uk-width-2-10 uk-text-small">
            <input type="text" name="c" id="c" value="<?php echo $_GET['c']?>" placeholder="Country">
        </div>
        <div class="uk-width-2-10 uk-text-small">
            <input type="text" name="st" id="st" value="<?php echo $_GET['st']?>" placeholder="State">
        </div>
        <div class="uk-width-2-10 uk-text-small">
            <input type="text" name="sb" id="sb" value="<?php echo $_GET['sb']?>" placeholder="Student Entry Level">
        </div>
        <div class="uk-width-2-10 uk-text-small">
            <input type="text" name="pp" id="pp" value="<?php echo $_GET['pp']?>" placeholder=" Programme Package">
        </div>
        <div class="uk-width-2-10">
            <button class="uk-button uk-width-1-1">Search</button>
        </div>
    </div>
</form><br>
<div class="uk-margin-right">
    <div class="uk-width-1-1 myheader">
        <h2 class="uk-text-center myheader-text-color">DEFAULT LISTING</h2>
    </div>
    <div class="uk-overflow-container">
        <table class="uk-table" id='mydatatable'>
            <thead>
                <tr class="uk-text-bold uk-text-small">
                    <td>Country</td>
                    <td>State</td>
                    <td>Student Entry Level</td>
                    <td>Programme Package</td>
                    <td>Start Date</td>
                    <td>End Date</td>
                    <td style="display:none;">Mobile App</td>
                    <td style="width: 120px;">Action</td>
                </tr>
            </thead>

            <tbody>
                <?php
				$c=isset($_GET['c']) ? $_GET['c'] : '';
				$st=isset($_GET['st']) ? $_GET['st'] : '';
				$sb=isset($_GET['sb']) ? $_GET['sb'] : '';
				$pp=isset($_GET['pp']) ? $_GET['pp'] : '';
				$year=$_SESSION['Year'];
            $sql = "SELECT * from fee where 1=1 ";
			//$sql.= "and year(from_date) <= '$year' and extend_year >= '$year'";
			 if($c!=""){
		  $sql=$sql."and country like '%$c%' "; 
	  }
	  if($st!=""){
		  $sql=$sql."and state like '%$st%' ";
	  }
	  if($sb!=""){
		  $sql=$sql."and subject like '%$sb%' "; 
	  }
	  if($pp!=""){
		  $sql=$sql."and programme_package like '%$pp%' "; 
	  }
	    $sql=$sql." ORDER BY id DESC"; 
            $result = mysqli_query($connection, $sql);
						
            $num_row = mysqli_num_rows($result);
			//$m_row = mysqli_fetch_assoc($result) 
			//print_r($num_row); die;
               
                ?>


                <?php

                    if ($num_row>0) {
                        while ($browse_row=mysqli_fetch_assoc($result)) {
                            $sha1_id=sha1($browse_row["id"]);
                            ?>
                <tr class="uk-text-small">
                    <td><?php echo $browse_row["country"]?></td>
                    <td><?php echo $browse_row["state"]?></td>
                    <td><?php echo $browse_row["subject"]?></td>
                    <td><?php echo $browse_row["programme_package"]?></td>
                    <td><?php echo $browse_row["from_date"]?></td>
                    <td><?php echo $browse_row["to_date"]?></td>
                    <td style="display:none;"><?php echo $browse_row["mobile_app"]?></td>
                    <td style="width:120px">
                        <?php
                                   
                                          ?>
                        <a href="index.php?p=fee&id=<?php echo $sha1_id?>&mode=EDIT" data-uk-tooltip title="Edit"><img
                                src="images/edit.png"></a>
                        &nbsp; &nbsp;                    
						<a href="index.php?p=fee&id=<?php echo $sha1_id?>&mode=DUPLICATE" data-uk-tooltip title="Duplicate"><img
                                src="images/duplicate.jpg" style="width: 25px;"></a>
                        &nbsp; &nbsp;
                        <a onclick="doDeleteRecord('<?php echo $sha1_id?>')" href="#" id="btnDelete" data-uk-tooltip
                            title="Delete"><img src="images/delete.png"></a>
                        <!--<a onclick="centreApproved('<?php echo $sha1_id?>')" href="index.php?p=fee_approve"
                            id="btnApproved" data-uk-tooltip title="View Request"><img
                                style="width:20px;margin-left: 12px;" src="images/request.png"></a>-->
                    </td>
                </tr>
                <?php
                        }
                    } else {
                        echo "<tr><td colspan='8'>No Record Found</td></tr>";
                    }
                    ?>
            </tbody>
        </table>
    </div>
</div>
<form name="frmDeleteRecord" id="frmDeleteRecord" method="get">
    <input type="hidden" name="p" value="fee">
    <input type="hidden" name="id" id="id" value="">
    <input type="hidden" name="mode" value="DEL">
</form>
<script>
function sumSchoolNumber() {
    var Amount = 0;
    $(".school").each(function() {
        var amt = $(this).val();
        amt = parseFloat(amt);
        if (isNaN(amt)) {
            amt = 0;
        }
        Amount += amt;
    })
    $("#total_fee").val(Amount.toFixed(2));
}

function summathNumber() {
    var Amount = 0;
    $(".math_default").each(function() {
        var amt = $(this).val();
        amt = parseFloat(amt);
        if (isNaN(amt)) {
            amt = 0;
        }
        Amount += amt;
    })
    $("#total_default_c").val(Amount.toFixed(2));
}

function sumintNumber() {
    var Amount = 0;
    $(".int_default").each(function() {
        var amt = $(this).val();
        amt = parseFloat(amt);
        if (isNaN(amt)) {
            amt = 0;
        }
        Amount += amt;
    })
    $("#total_default_d").val(Amount.toFixed(2));
}

function sumadjustNumber() {
    var Amount = 0;
    $(".school_adjust").each(function() {
        var amt = $(this).val();
        amt = parseFloat(amt);
        if (isNaN(amt)) {
            amt = 0;
        }
        Amount += amt;
    })
    $("#school_total_f").val(Amount.toFixed(2));
}
$(".school").on('keyup keypress blur change', function(e) {
    sumSchoolNumber()
});
$(".math_default").on('keyup keypress blur change', function(e) {
    summathNumber()
});
$(".int_default").on('keyup keypress blur change', function(e) {
    sumintNumber()
});
$(".school_adjust").on('keyup keypress blur change', function(e) {
    sumadjustNumber()
});


$("#programme_package").change(function() {
	var to_date_check= $("#to_date").val();
	if(to_date_check != ''){
	// UIkit.modal.alert("<h2>Please change commencement end date</h2>")
	}
	
});
$("#country").change(function() {
    var country = $("#country").val();
    $.ajax({
        url: "admin/get_state.php",
        type: "POST",
        data: "country=" + country,
        dataType: "text",
        beforeSend: function(http) {},
        success: function(response, status, http) {
            if (response != "") {
                $("#state").html(response);
            } else {
                //$("#state").html("<select name='state' id='state' class='uk-width-1-1'><option value=''>Please select a country</option></select>");
                // UIkit.notify("No state found in "+country);
            }
        },
        error: function(http, status, error) {
            UIkit.notify("Error:" + error);
            sumSchoolNumber();
            summathNumber();
            sumintNumber();
            sumadjustNumber();
        }
    });
});

$("#frmCourse").submit(function(e) {
    var fees_structure = $("#fees_structure").val();
	var data = 1;
   	$.ajax({
        url: "admin/check_fees_structure.php",
        type: "GET",
        data: "name=" + fees_structure + "&id=<?php echo $get_sha1_id; ?>",
        dataType: "text",
		async: false, 
        beforeSend: function(http) {},
        success: function(response, status, http) {
			response = JSON.parse(response);
			data = response.status;
			console.log(response.status)
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
    var country = $("#country").val();
    var state = $("#state").val();
    var subject = $("#subject").val();
    var df = $("#from_date").val();
    var dt = $("#to_date").val();
    var programme_package = $("#programme_package").val();
    var school_fee = $("#school_fee").val(); 
    var multimedia_fee = $("#multimedia_fee").val();
    var registration = $("#registration").val();
    var facility_fee = $("#facility_fee").val();
    var eife = $("#eife").val();
    var iq_math = $("#iq_math").val();
    var mandarin = $("#mandarin").val();
    var integrated_modules = $("#integrated_modules").val();
    var link_think = $("#link_think").val();
    var mandarin_modules = $("#mandarin_modules").val();
    var mobile_app = $("#mobile_app").val();
    var insurance = $("#insurance").val();
    var basic_afternoon_p = $("#basic_afternoon_p").val();
    var afternoon_robotic = $("#afternoon_robotic").val();
    var uniform = $("#uniform").val();
    var gymwear = $("#gymwear").val();
    var q_dees_kit = $("#q_dees_kit").val();
    var q_dees_bag = $("#q_dees_bag").val();
    var pendidikan_islam = $("#pendidikan_islam").val();
    var robotic_plus = $("#robotic_plus").val();
    var international_art = $("#international_art").val();
    var school_collection = $("#school_collection").val();
    var multimedia_collection = $("#multimedia_collection").val();
    var facility_collection = $("#facility_collection").val();
    var enhanced_collection = $("#enhanced_collection").val();
    var iq_math_collection = $("#iq_math_collection").val();
    var mandarin_collection = $("#mandarin_collection").val();
    var international_collection = $("#international_collection").val();
    var link_collection = $("#link_collection").val();
    var mandarin_m_collection = $("#mandarin_m_collection").val();
    var integrated_collection = $("#integrated_collection").val();
    var basic_collection = $("#basic_collection").val();
    var afternoon_robotic_collection = $("#afternoon_robotic_collection").val();
    var mobile_collection = $("#mobile_collection").val();
    var pendidikan_islam_collection = $("#pendidikan_islam_collection").val();
    var robotic_plus_collection = $("#robotic_plus_collection").val();
    // STEM ADD
    var stem_programme = $("#stem_programme").val();
    var stem_programme_collection = $("#stem_programme_collection").val();
    var stem_student_kit = $("#stem_student_kit").val();
    var stem_student_kit_collection = $("#stem_student_kit_collection").val();

    if (!country || !state || !subject || !school_fee || !multimedia_fee || !registration || !facility_fee || !
        mobile_collection || !fees_structure || !robotic_plus || !robotic_plus_collection || !pendidikan_islam_collection || !afternoon_robotic || !afternoon_robotic_collection) {
        e.preventDefault();
        if (!country) {
            $('#validationCountry').show();
        } else {
            $('#validationCountry').hide();
        }
        if (!fees_structure) {
            $('#validationStructure').show();
        } else {
            $('#validationStructure').hide();
        }
		
        if (!state) {
            $('#validationState').show();
        } else {
            $('#validationState').hide();
        }
        if (!programme_package) {
            $('#validationprogramme_package').show();
        } else {
            $('#validationprogramme_package').hide();
        }
        if (!subject) {
            $('#validationSubject').show();
        } else {
            $('#validationSubject').hide();
        }

        if (!school_fee) {
            $('#validationschool').show();
        } else {
            $('#validationschool').hide();
        }
        if (!school_collection) {
            $('#validationsc').show();
        } else {
            $('#validationsc').hide();
        }
        if (!dt) {
            $('#validationdt').show();
        } else {
            $('#validationdt').hide();
        }

        if (!df) {
            $('#validationdf').show();
        } else {
            $('#validationdf').hide();
        }

        if (!multimedia_fee) {
            $('#validationMultimedia').show();
        } else {
            $('#validationMultimedia').hide();
        }
        if (!multimedia_collection) {
            $('#validationMc').show();
        } else {
            $('#validationMc').hide();
        }
        if (!registration) {
            $('#validationRegistration').show();
        } else {
            $('#validationRegistration').hide();
        }

        if (!facility_fee) {
            $('#validationFacility').show();
        } else {
            $('#validationFacility').hide();
        }
        if (!facility_collection) {
            $('#validationfc').show();
        } else {
            $('#validationfc').hide();
        }
        if (!eife) {
            $('#validationeife').show();
        } else {
            $('#validationeife').hide();
        }
        if (!iq_math) {
            $('#validationMath').show();
        } else {
            $('#validationMath').hide();
        }
        if (!mandarin) {
            $('#validationMandarin').show();
        } else {
            $('#validationMandarin').hide();
        }
        if (!integrated_modules) {
            $('#validationIntegrated').show();
        } else {
            $('#validationIntegrated').hide();
        }
        if (!link_think) {
            $('#validationLink').show();
        } else {
            $('#validationLink').hide();
        }
        if (!mandarin_modules) {
            $('#validationMandarinM').show();
        } else {
            $('#validationMandarinM').hide();
        }
        if (!mobile_app) {
            $('#validationMobile').show();
        } else {
            $('#validationMobile').hide();
        }
        if (!insurance) {
            $('#validationInsurance').show();
        } else {
            $('#validationInsurance').hide();
        }
        if (!basic_afternoon_p) {
            $('#validationBasic').show();
        } else {
            $('#validationBasic').hide();
        }
        if (!afternoon_robotic) {
            $('#validationAfternoonRobotics').show();
        } else {
            $('#validationAfternoonRobotics').hide();
        }
        if (!uniform) {
            $('#validationUniform').show();
        } else {
            $('#validationUniform').hide();
        }
        if (!gymwear) {
            $('#validationGymwear').show();
        } else {
            $('#validationGymwear').hide();
        }
        if (!q_dees_kit) {
            $('#validationQdees_kit').show();
        } else {
            $('#validationQdees_kit').hide();
        }
        if (!q_dees_bag) {
            $('#validationQdees_bag').show();
        } else {
            $('#validationQdees_bag').hide();
        }
        if (!pendidikan_islam) {
            $('#validationPendidikan_islam').show();
        } else {
            $('#validationPendidikan_islam').hide();
        }
        if (!robotic_plus) {
            $('#validationrobotic_plus').show();
        } else {
            $('#validationrobotic_plus').hide();
        }
        if (!international_art) {
            $('#validationinternational_art').show();
        } else {
            $('#validationinternational_art').hide();
        }

        if (!enhanced_collection) {
            $('#validationec').show();
        } else {
            $('#validationec').hide();
        }

        if (!iq_math_collection) {
            $('#validationmc').show();
        } else {
            $('#validationmc').hide();
        }

        if (!mandarin_collection) {
            $('#validationmdc').show();
        } else {
            $('#validationmdc').hide();
        }

        if (!international_collection) {
            $('#validationinc').show();
        } else {
            $('#validationinc').hide();
        }
        if (!integrated_collection) {
            $('#validationitc').show();
        } else {
            $('#validationitc').hide();
        }
        if (!link_collection) {
            $('#validationlnc').show();
        } else {
            $('#validationlnc').hide();
        }
        if (!mandarin_m_collection) {
            $('#validationmmc').show();
        } else {
            $('#validationmmc').hide();
        }
        if (!basic_collection) {
            $('#validationbc').show();
        } else {
            $('#validationbc').hide();
        }
        if (!mobile_collection) {
            $('#validationmbc').show();
        } else {
            $('#validationmbc').hide();
        }

        if (!pendidikan_islam_collection) {
            $('#validationpi').show();
        } else {
            $('#validationpi').hide();
        }
        if (!robotic_plus_collection) {
            $('#validationrp').show();
        } else {
            $('#validationrp').hide();
        }

        if (!afternoon_robotic_collection) {
            $('#validationarc').show();
        } else {
            $('#validationarc').hide();
        }

        if (!stem_programme) {
            $('#validationSTEM').show();
        } else {
            $('#validationSTEM').hide();
        }
        if (!stem_programme_collection) {
            $('#validationCollectionStemPgm').show();
        } else {
            $('#validationCollectionStemPgm').hide();
        }

        if (!stem_student_kit) {
            $('#validationSTEMStudentKit').show();
        } else {
            $('#validationSTEMStudentKit').hide();
        }
        if (!stem_student_kit_collection) {
            $('#stem_student_kit_collection').show();
        } else {
            $('#stem_student_kit_collection').hide();
        }

        return false;
    }

});
$(document).ready(function() {
    $('#mydatatable').DataTable({
        'columnDefs': [{
            'targets': [7], // column index (start from 0)
            'orderable': false, // set orderable false for selected columns
        }]
    });
    $("#c").change(function() {
        loadState();
    });
});
loadState();

function loadState() {
    var country = $("#c").val();
    $.ajax({
        url: "admin/get_state.php",
        type: "POST",
        data: "country=" + country,
        dataType: "text",
        beforeSend: function(http) {},
        success: function(response, status, http) {
            if (response != "") {
                $("#st").html(response);
            } else {
                // $("#st").html("<select name='st' id='st' class='uk-width-1-1'><option value=''>Please select a country</option></select>");
                // UIkit.notify("No state found in "+country);
            }
            $("#st").val('<?php echo $_GET["st"] ?>');
        },
        error: function(http, status, error) {
            UIkit.notify("Error:" + error);

        }
    });
}

function doDeleteRecord(id) {
    UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function() {
        $("#id").val(id);
        $("#frmDeleteRecord").submit();
    });
}
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
#mydatatable_length {
    display: none;
}

#mydatatable_filter {
    display: none;
}

/*#mydatatable_paginate{float:initial;text-align:center}*/
#mydatatable_paginate .paginate_button {
    display: inline-block;
    min-width: 16px;
    padding: 3px 5px;
    line-height: 20px;
    text-decoration: none;
    -moz-box-sizing: content-box;
    box-sizing: content-box;
    text-align: center;
    background: #f7f7f7;
    color: #444444;
    border: 1px solid rgba(0, 0, 0, 0.2);
    border-bottom-color: rgba(0, 0, 0, 0.3);
    background-origin: border-box;
    background-image: -webkit-linear-gradient(top, #ffffff, #eeeeee);
    background-image: linear-gradient(to bottom, #ffffff, #eeeeee);
    text-shadow: 0 1px 0 #ffffff;
    margin-left: 3px;
    margin-right: 3px
}

#mydatatable_paginate .paginate_button.current {
    background: #009dd8;
    color: #ffffff !important;
    border: 1px solid rgba(0, 0, 0, 0.2);
    border-bottom-color: rgba(0, 0, 0, 0.4);
    background-origin: border-box;
    background-image: -webkit-linear-gradient(top, #00b4f5, #008dc5);
    background-image: linear-gradient(to bottom, #00b4f5, #008dc5);
    text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.2);
}

#mydatatable_filter {
    width: 100%
}

#mydatatable_filter label {
    width: 100%;
    display: inline-flex
}

#mydatatable_filter label input {
    height: 30px;
    width: 100%;
    padding: 4px 6px;
    border: 1px solid #dddddd;
    background: #ffffff;
    color: #444444;
    -webkit-transition: all linear 0.2s;
    transition: all linear 0.2s;
    border-radius: 4px;
}

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
</style>