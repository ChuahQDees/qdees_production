<?php
if (!defined('IS_ADMIN_STUDENT_FORM')) {
  define('IS_ADMIN_STUDENT_FORM', false);
}

if (!defined('IS_PUBLIC_STUDENT_FORM')) {
  define('IS_PUBLIC_STUDENT_FORM', false);
}
?>

<?php if (IS_PUBLIC_STUDENT_FORM) { ?>
<link rel="stylesheet" type="text/css" href="css/my.css">

<style type="text/css">
.required {
    border-color: red;
}

.ui-dialog-titlebar-close {
    background-image: url(images/delete.png) !important;
    background-position: -2px !important;
    background-repeat: no-repeat !important;
}
</style>

<div class="uk-margin-top uk-margin-left uk-margin-right" style="padding-bottom: 80px">
    <div class="uk-text-center bg-form"><img src="images/logo.png"></div>
    <?php } ?>

    <div class="uk-width-1-1 myheader">
        <div class="uk-grid">
            <div class="uk-width-1-10"></div>
            <div class="uk-width-8-10">
                <?php if (IS_ADMIN_STUDENT_FORM) { ?>
                <h2 class="uk-text-center myheader-text-color myheader-text-style">Student Details</h2>
                <?php } else { ?>
                <h2 class="uk-text-center myheader-text-color">Student Registration Form</h2>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php 
 global $connection;

   $sql="SELECT company_name from centre where centre_code  ='$centre_code'";
    $result=mysqli_query($connection, $sql);
  
     if ($result) {
     $row = mysqli_fetch_assoc($result);
	 $centre_name = $row['company_name'];
  }
  $sql1="SELECT full_name from student_emergency_contacts where student_code  ='$temp_student_code' order by id LIMIT 1";
    $result1=mysqli_query($connection, $sql1);
  //echo $sql1;
     if ($result1) {
     $row1 = mysqli_fetch_assoc($result1);
	 $full_name = $row1['full_name'];
  }							

?>
    <div class="uk-text-center" style="background-color: #fff; padding: 10px;">
        <h4 class="uk-margin-remove"><b>Centre Name:</b> <?php echo htmlspecialchars($centre_name); ?></h4>
    </div>

    <?php  if (IS_ADMIN_STUDENT_FORM) { ?>
    <form name="frmStudent" id="frmStudent" method="post" class="uk-form uk-form-small"
        action="<?php echo $form_post_url; ?>" enctype="multipart/form-data" autocomplete="off">
        <input type="hidden" name="centre_code" id="centre_code" value="<?php echo $centre_code; ?>">
        <input type="hidden" name="student_code" id="student_code" value="<?php echo $temp_student_code; ?>">
        <input type="hidden" name="hidden_student_code" id="hidden_student_code"
            value="<?php echo $temp_student_code; ?>">
        <input type="hidden" name="form_mode" id="form_mode" value="">
        <?php } else { ?>
        <div style="max-width: 90%; margin: 0 auto;">
            <form name="frmStudent" id="frmStudent" method="post" class="uk-form uk-form-small"
                action="<?php echo $form_post_url; ?>" enctype="multipart/form-data" autocomplete="off">
                <input type="hidden" name="centre_code" id="centre_code" value="<?php echo $centre_code; ?>">
                <input type="hidden" name="student_code" id="student_code" value="<?php echo $temp_student_code; ?>">
                <input type="hidden" name="hidden_student_code" id="hidden_student_code"
                    value="<?php echo $temp_student_code; ?>">
                <input type="hidden" name="form_mode" id="form_mode" value="qr">
                <?php } ?>

                <input type="hidden" name="contact_check" id="contact_check" value="">

                <div id="the_grid" class="uk-grid" style="margin-left: 0">

                    <div class="uk-width-large-5-10">

                        <div class="uk-grid">
                            <div class="uk-width-large-4-10">
                                <?php
                if( !empty($data_array['photo_file_name']) ){
                  $photo_src = getStudentPhotoSrc($data_array['photo_file_name'], 'medium');
                  echo '<div><a id="add-student-photo" href="' . $photo_src . '" target="_blank"><img src="' . $photo_src . '" width="150px" height="200px" style="border:1px solid #ddd"></a></div>';
                }else{
                  echo '<div><a href="#" id="add-student-photo"><img src="../images/add_photo.png" width="150px" height="200px" style="border:1px solid #ddd"></a></div>';
                }
              ?>
                                <input id="input-student-photo" type="file" accept=".jpg, .jpeg" name="image"
                                    style="display: none; margin-top: 10px">
                                <button id="btn-upload-photo" class="uk-button" style="margin-top: 1rem;">Choose
                                    File</button>
                                <div id="label-upload-photo"
                                    style="text-overflow: ellipsis; white-space: nowrap; overflow: hidden;"></div>
                                <div style="color: red; margin: 1rem 0;">Upload .jpg file only (Less Than 10MB)</div>
                            </div>

                            <div class="uk-width-large-6-10">
                                <?php if (IS_ADMIN_STUDENT_FORM) { ?>
                                <div class="uk-flex uk-flex-column" style="margin-top: 10px">
                                    <div class="uk-width-1-1 uk-text-bold">Student Status* : </div>
                                    <div class="uk-width-1-1">
                                        <?php

                                            if(isset($_GET['mode']) && $_GET['mode'] == 'EDIT') {
                                                $fields=array("A"=>"Active", "D"=>"Deferred", "G"=>"Graduated", "S"=>"Suspended", "T"=>"Transferred");
                                            } else {
                                                $fields=array("A"=>"Active");
                                            }

                                            $data_array["student_status"] = isset($data_array["student_status"]) ? $data_array["student_status"] : 'A';

                                            generateSelectArray($fields, "student_status", "class='uk-width-1-1 '", $data_array["student_status"]);
                                        ?>
                                        <span id="validationStudentStatus" style="color: red; display: none;">Please
                                            select Student Status</span>
                                    </div>
                                </div>

                                <div class="uk-flex uk-flex-column" style="margin-top: 10px">
                                    <div class="uk-width-1-1 uk-text-bold">Student's Full Name* : </div>
                                    <div class="uk-width-1-1">
                                        <input class="uk-width-1-1 " type="text" name="name" id="name"
                                            value="<?php if(isset($data_array['name'])) { echo $data_array['name']; } ?>">
                                        <span id="validationStudentName" style="color: red; display: none;">Please
                                            insert Student's Full Name</span>
                                    </div>
                                </div>

                                <div class="uk-flex uk-flex-column" style="margin-top: 10px">
                                    <div class="uk-width-1-1 uk-text-bold">Start Date at Centre* : </div>
                                    <div class="uk-width-1-1">

                                        <?php
                                            $year_date_student = mysqli_fetch_array(mysqli_query($connection,"SELECT MIN(`term_start`) AS `start_date`, MAX(`term_end`) AS `end_date` FROM `schedule_term` WHERE `centre_code` = '".$centre_code."' GROUP BY `centre_code`"));
                                        ?>

                                        <input readonly class="uk-width-1-1 " type="text"
                                            data-uk-datepicker="{format:'DD/MM/YYYY', maxDate: '<?php echo date('d/m/Y',strtotime($year_date_student['end_date'])); ?>'}" name="start_date_at_centre"
                                            id="start_date_at_centre"
                                            value="<?php if(isset($data_array['start_date_at_centre'])) { echo convertDate2British($data_array['start_date_at_centre']); } ?>">
                                        <span id="validationStartDate" style="color: red; display: none;">Please select
                                            Start Date</span>
                                    </div>
                                </div>

                                <div class="uk-flex uk-flex-column" style="margin-top: 10px">
                                    <div class="uk-width-1-1 uk-text-bold">Form Serial No.:</div>
                                    <div class="uk-width-1-1">
                                        <input class="uk-width-1-1" type="text" name="form_serial_no"
                                            id="form_serial_no" value="<?php echo $data_array['form_serial_no']?>">
                                    </div>
                                </div>
                                <?php } ?>

                            </div>
                        </div>
                        <!--/.uk-grid-->

                        <h3><b>Parent's / Guardian Contact</b></h3>
                        <table class="uk-table uk-table-small">
                            <tr class="uk-text-small">
                                <td class="uk-width-4-10 uk-text-bold" id="contact_check" required>Parent's/Guardian's
                                    Contacts* : </td>

                               
                                <?php if (IS_ADMIN_STUDENT_FORM) { ?>
                                <td id="contact_check"><a id="primary-contact1"
                                        onclick="getEmergencyContacts('<?php echo $temp_student_code?>', '', '<?php echo $sha1_visitor_id ?>')"
                                        class="uk-button uk-width-1-1"
                                        required>
										<?php if($full_name !=""){?>
										<?php echo $full_name ?>
										<?php }else{?>
										<?php echo !empty($row_contact['full_name']) ? $row_contact['full_name'] : 'Primary contact eg: Parents or Guardian'; ?>
										<?php }?>
										</a>
										<input type="hidden" id="primary-contact3"
                                        value="<?php echo $full_name ?>">
                                    <input type="hidden" id="primary-contact3"
                                        value="<?php echo !empty($row_contact['full_name']) ? $row_contact['full_name'] : 'Primary contact eg: Parents or Guardian'; ?>">
										
                                    <span id="validationParentContacts" style="color: red; display: none;">Please insert
                                        Parent's/Guardian's Contacts</span>
                                </td>
                                <?php } else { ?>
                                <td id="contact_check"><a id="primary-contact2"
                                        onclick="getEmergencyContacts('<?php echo $temp_student_code?>', 'qr', '')"
                                        class="uk-button uk-width-1-1"
                                        required><?php echo !empty($row_contact['full_name']) ? $row_contact['full_name'] : 'Primary contact eg: Parents or Guardian'; ?></a>
                                    <span id="validationParentContacts2" style="color: red; display: none;">Please
                                        insert Parent's/Guardian's Contacts</span>
                                </td>
                                <?php } ?>

                            </tr>
                        </table>

                        <h3><b>Student's Detail</b></h3>

                        <table class="uk-table uk-table-small">

                            <?php if (IS_PUBLIC_STUDENT_FORM) { ?>
                            <tr class="uk-text-small">
                                <td class="uk-width-4-10 uk-text-bold">Student's Full Name* : </td>
                                <td class="uk-width-6-10">
                                    <input class="uk-width-1-1 " type="text" name="name" id="name"
                                        value="<?php echo $data_array['name']?>">
                                    <span id="validationStudentName" style="color: red; display: none;">Please insert
                                        Student's Full Name</span>
                                </td>
                            </tr>

                            <tr class="uk-text-small">
                                <td class="uk-width-4-10 uk-text-bold">Start Date at Centre* : </td>
                                <td class="uk-width-6-10">

                                    <?php
                                        $year_date_student = mysqli_fetch_array(mysqli_query($connection,"SELECT MIN(`term_start`) AS `start_date`, MAX(`term_end`) AS `end_date` FROM `schedule_term` WHERE `centre_code` = '".$centre_code."' GROUP BY `centre_code`"));
                                    ?>

                                    <input readonly class="uk-width-1-1 " type="text" data-uk-datepicker="{format:'DD/MM/YYYY', maxDate: '<?php echo date('d/m/Y',strtotime($year_date_student['end_date'])); ?>'}"
                                        name="start_date_at_centre" id="start_date_at_centre"
                                        value="<?php echo convertDate2British($data_array['start_date_at_centre']); ?>">
                                    <span id="validationStartDate" style="color: red; display: none;">Please select
                                        Start Date</span>
                                </td>
                            </tr>
                            <?php } ?>

                            <tr class="uk-text-small">
                                <td class="uk-width-4-10 uk-text-bold">MyKid / Passport No.: </td>
                                <td class="uk-width-6-10"><input class="uk-width-1-1 " type="text" name="nric_no"
                                        id="nric_no" value="<?php echo $data_array['nric_no']?>">
                                    <span id="validationNRIC" style="color: red; display: none;">Please insert MyKid /
                                        Passport No</span>
                                </td>
                            </tr>

                            <tr class="uk-text-small">
                                <td class="uk-width-4-10 uk-text-bold">Date of Birth* : </td>
                                <td class="uk-width-6-10">
                                    <div class="uk-grid-small uk-grid">
                                        <div class="uk-width-1-3" style="padding-right:0px">
                                            <select id="dob_day" name="dob_day" class="uk-width-1-1 ">
                                                <option value="">-- Day --</option>
                                                <?php
                      if (!empty($data_array['dob'])) {
                        $tmp_dob = new DateTime($data_array['dob']);
                        $tmp_day = $tmp_dob->format('j');
                      } elseif (!empty($data_array['dob_day'])) {
                        $tmp_day = $data_array['dob_day'];
                      }

                      for ($i=1; $i<32; $i++) {

                         if($i == $tmp_day){
                           $tmp_selected = true;
                         }else{
                           $tmp_selected = false;
                         }

                         $tmp_day = ltrim($tmp_day, '0');

                         if ($i<10) {
                           echo "<option". ($tmp_selected ? ' selected' : '') . ">0".$i."</option>";
                         } else {
                           echo "<option". ($tmp_selected ? ' selected' : '') . ">$i</option>";
                         }
                      }
                      ?>
                                            </select>
                                            <span id="validationDobDay" style="color: red; display: none;">Please select
                                                Day</span>
                                        </div>

                                        <div class="uk-width-1-3" style="padding-right:0px">
                                            <select id="dob_month" name="dob_month" class="uk-width-1-1 ">
                                                <option value="">-- Month --</option>
                                                <?php
                      if( !empty($data_array['dob']) ){
                        $tmp_month = $tmp_dob->format('n');
                      }else if( !empty($data_array['dob_month']) ){
                        $tmp_month = $data_array['dob_month'];
                      }

                      $tmp_month = ltrim($tmp_month, '0');

                      for ($i=1; $i<13; $i++) {
                        if($i == $tmp_month){
                          $tmp_selected = true;
                        }else{
                          $tmp_selected = false;
                        }

                        if ($i<10) {
                         echo "<option". ($tmp_selected ? ' selected' : '') . ">0".$i."</option>";
                        } else {
                         echo "<option". ($tmp_selected ? ' selected' : '') . ">$i</option>";
                        }
                      }
                      ?>
                                            </select>
                                            <span id="validationDobMonth" style="color: red; display: none;">Please
                                                select Month</span>
                                        </div>

                                        <div class="uk-width-1-3" style="padding-right:0px">
                                            <select id="dob_year" name="dob_year" class="uk-width-1-1 ">
                                                <option value="">-- Year --</option>
                                                <?php
                      if( !empty($data_array['dob']) ){
                        $tmp_year = $tmp_dob->format('Y');
                      } elseif (!empty($data_array['dob_year'])) {
                        $tmp_year = $data_array['dob_year'];
                      } elseif (!empty($visitor['child_birth_year_'.$child_no])) {
                        $tmp_year = $visitor['child_birth_year_'.$child_no];
                      }

                      $current_year=date("Y");
                      $back_year=date("Y", strtotime("-50 year"));
                      $forward_year=date("Y", strtotime("+1 year"));
                      for ($i=$forward_year; $i>=$back_year; $i--) {
                        if($i == $tmp_year){
                          $tmp_selected = true;
                        }else{
                          $tmp_selected = false;
                        }

                        echo "<option". ($tmp_selected ? ' selected' : '') . ">$i</option>";
                      ?>


                                                <!-- <option><?php echo ($tmp_selected ? ' selected' : ''); ?><?php echo $i?></option> -->
                                                <?php }//for ?>
                                            </select>
                                            <span id="validationDobYear" style="color: red; display: none;">Please
                                                select Year</span>
                                        </div>
                                        <!--/.uk-width-1-3-->
                                    </div>
                                </td>
                            </tr>

                            <tr class="uk-text-small">
                                <td class="uk-width-4-10 uk-text-bold">Age : </td>
                                <td class="uk-width-6-10"><input class="uk-width-1-1" type="text" name="age" id="age"
                                        value="<?php echo $data_array['age']?>" readonly></td>
                            </tr>

                            <tr class="uk-text-small">
                                <td class="uk-width-4-10 uk-text-bold">Birth Cert. No.: </td>
                                <td class="uk-width-6-10">
								<input class="uk-width-1-1 " type="text" name="birth_cert_no"
                                        id="birth_cert_no" value="<?php echo $data_array['birth_cert_no']?>">
                                    <span id="validationBirthCert" style="color: red; display: none;">Please insert
                                        Birth Cert.No</span>
                                </td>
                            </tr>

                            <tr class="uk-text-small">
                                <td class="uk-width-4-10 uk-text-bold">Gender* : </td>
                                <td class="uk-width-6-10">
                                    <?php
                    $fields=array("F"=>"Female", "M"=>"Male");
                    generateSelectArray($fields, "gender", "class='uk-width-1-1 '", $data_array["gender"]);
                    ?>
                                    <span id="validationGender" style="color: red; display: none;">Please select
                                        Gender</span>
                                </td>
                            </tr>
                        </table>

                        <h3><b>Student's Address</b></h3>
                        <table class="uk-table uk-table-small">
                            <tr class="uk-text-small">
                                <td class="uk-width-4-10 uk-text-bold">Address* : </td>
                                <td class="uk-width-6-10">
                                    <div style="margin-top: 5px"><input class="uk-width-1-1 " type="text" name="add1"
                                            id="add1" value="<?php echo $data_array['add1']?>"
                                            placeholder="Address Line 1">
                                        <span id="validationAdd1" style="color: red; display: none;">Please insert
                                            address</span>
                                    </div>
                                    <div style="margin-top: 5px"><input class="uk-width-1-1" type="text" name="add2"
                                            id="add1" value="<?php echo $data_array['add2']?>"
                                            placeholder="Address Line 2"></div>
                                    <div style="margin-top: 5px"><input class="uk-width-1-1" type="text" name="add3"
                                            id="add1" value="<?php echo $data_array['add3']?>"
                                            placeholder="Address Line 3"></div>
                                    <div style="margin-top: 5px"><input class="uk-width-1-1" type="text" name="add4"
                                            id="add1" value="<?php echo $data_array['add4']?>"
                                            placeholder="Address Line 4"></div>
                                </td>
                            </tr>

                            <tr class="uk-text-small">
                                <td class="uk-width-3-10 uk-text-bold">Country* : </td>
                                <td class="uk-width-7-10">
                                    <select name="country" id="country" class="uk-width-1-1 ">
                                        <option value="">Select</option>
                                        <?php
                       $sql="SELECT * from codes where module='COUNTRY' order by code";
                       $result=mysqli_query($connection, $sql);
                       while ($row=mysqli_fetch_assoc($result)) {
                       ?>
                                        <option value="<?php echo $row["code"]?>"
                                            <?php if ($row["code"]==$data_array["country"]) {echo "selected";}?>>
                                            <?php echo $row["code"]?></option>
                                        <?php } ?>
                                    </select>

                                    <span id="validationCountry" style="color: red; display: none;">Please select
                                        Country</span>
                                </td>
                            </tr>

                            <tr class="uk-text-small">
                                <td class="uk-width-3-10 uk-text-bold">State* : </td>
                                <td class="uk-width-7-10">
                                    <select name="state" id="state" class="uk-width-1-1 ">
                                        <?php if ($data_array["country"]!="") { ?>
                                        <option value="">Select</option>
                                        <?php
                            $sql="SELECT * from codes where country='".$data_array["country"]."' and module='STATE' order by code";
                            $result=mysqli_query($connection, $sql);
                            while ($row=mysqli_fetch_assoc($result)) {
                         ?>
                                        <option value="<?php echo $row['code']?>"
                                            <?php if ($row["code"]==$data_array['state']) {echo "selected";}?>>
                                            <?php echo $row["code"]?></option>
                                        <?php
                            }
                         } else {
                         ?>
                                        <option value="">Please Select Country First</option>
                                        <?php } ?>
                                    </select>
                                    <span id="validationState" style="color: red; display: none;">Please select
                                        State</span>
                                </td>
                            </tr>

                        </table>
                    </div>
                    <!--/.uk-width-large-5-10-->

                    <div class="uk-width-large-5-10">
                        <h3><b>Other Information</b></h3>
                        <table class="uk-table uk-table-small">

                            <tr class="uk-text-small">
                                <td class="uk-width-4-10 uk-text-bold">Ethnicity* : </td>
                                <td class="uk-width-6-10">
                                    <select name="race" id="race" class="uk-width-1-1 ">
                                        <?php if ($data_array["country"]!="") { ?>
                                        <option value="">select</option>
                                        <?php
                           $sql="SELECT * from codes where country='".$data_array["country"]."' and module='RACE' order by code";
                           $result=mysqli_query($connection, $sql);
                           while ($row=mysqli_fetch_assoc($result)) {
                        ?>
                                        <option value="<?php echo $row['code']?>"
                                            <?php if ($row["code"]==$data_array['race']) {echo "selected";}?>>
                                            <?php echo $row["code"]?></option>
                                        <?php } ?>
                                        <option value="Others"
                                            <?php if ($data_array['race']=="Others") {echo "selected";}?>>Others
                                        </option>
                                        <?php } else { ?>
                                        <option value="">Please Select Country First</option>
                                        <?php } ?>
                                    </select>

                                    <span id="validationRace" style="color: red; display: none;">Please select
                                        Ethnicity</span>
                                </td>
                            </tr>

                            <tr class="uk-text-small">
                                <td class="uk-width-4-10 uk-text-bold">Nationality* : </td>
                                <td class="uk-width-6-10">
                                    <select name="nationality" id="nationality" class="uk-width-1-1 ">
                                        <?php if ($data_array["country"]!="") { ?>
                                        <option value="">select</option>
                                        <?php
                           $sql="SELECT * from codes where country='".$data_array["country"]."' and module='NATIONALITY' order by code";
						   //echo $sql; die;
                           $result=mysqli_query($connection, $sql);
                           while ($row=mysqli_fetch_assoc($result)) {
                        ?>
                                        <option value="<?php echo $row['code']?>"
                                            <?php if ($row["code"]==$data_array['nationality']) {echo "selected";}?>>
                                            <?php echo $row["code"]?></option>
                                        <?php } ?>
                                        <?php } else { ?>
                                        <option value="">Please Select Country First</option>
                                        <?php } ?>
                                    </select>
                                    <span id="validationNationality" style="color: red; display: none;">Please select
                                        Nationality</span>
                                </td>
                            </tr>
                            <tr class="uk-text-small">
                                <td class="uk-width-4-10 uk-text-bold">Religion* : </td>
                                <td class="uk-width-6-10">
                                    <?php
                  $sql="SELECT * from codes where module='religion' order by code";
                  generateSelect($sql, "code", "code", "religion", "class='uk-width-1-1 '", $data_array["religion"]);
                  ?>
                                    <span id="validationReligion" style="color: red; display: none;">Please select
                                        Religion</span>
                                </td>
                            </tr>

                            <tr class="uk-text-small">
                                <td class="uk-width-4-10 uk-text-bold">Medical Condition* : </td>
                                <td class="uk-width-6-10"><input class="uk-width-1-1 " type="text" name="health_problem"
                                        id="health_problem" value="<?php echo $data_array['health_problem']?>">
                                    <span id="validationHealth" style="color: red; display: none;">Please insert Medical
                                        Condition</span>
                                </td>
                            </tr>

                            <tr class="uk-text-small">
                                <td class="uk-width-4-10 uk-text-bold">Allergies* : </td>
                                <td class="uk-width-6-10"><input class="uk-width-1-1 " type="text" name="allergies"
                                        id="allergies" value="<?php echo $data_array['allergies']?>">
                                    <span id="validationAllergies" style="color: red; display: none;">Please insert
                                        Allergies</span>
                                </td>
                            </tr>

                            <tr class="uk-text-small">
                                <td class="uk-width-4-10 uk-text-bold">Remarks : </td>
                                <td class="uk-width-6-10"><textarea class="uk-width-1-1" rows="5" name="remarks"
                                        id="remarks"><?php echo $data_array["remarks"]?></textarea></td>
                            </tr>

                            <tr>
                                <?php if( isset($data_array['attachment']) && $data_array['attachment'] ){ ?>
                                <td class="uk-width-4-10 uk-text-bold"><label class="uk-text-bold">Replace
                                        Attachment</label></td>
                                <?php } else { ?>
                                <td class="uk-width-4-10 uk-text-bold"><label class="uk-text-bold">Upload
                                        Attachment</label></td>
                                <?php } ?>
                                <td class="uk-width-6-10">
                                    <?php if( isset($data_array['attachment']) && $data_array['attachment'] ){ ?>
                                    <?php echo '<div class="uk-width-1-1" style="margin-bottom: 1rem;"><a style="word-break: break-all;" class="uk-width-1-1" href="' . STUDENT_ATTACHMENT_URL . $data_array['attachment'] . '" target="_blank">' . shortenAttachmentName($data_array['attachment']) . '</a></div>'; ?>
                                    <?php } ?>
                                    <input type="file" name="attachment" id="attachment" class="uk-width-1-1"
                                        accept=".doc, .docx, .pdf, .png, .jpg, .jpeg">
                                    <div class="uk-width-1-1" style="color: red; margin: 1rem 0 0;">Upload .jpg /.png /
                                        .docx / .doc /.pdf file only (Less Than 10MB)</div>
                                </td>
                            </tr>

                            <tr class="uk-text-bold">
                                <td class="uk-width-1-1" colspan="2">
                                    <input type="checkbox" name="accept_photo" id="accept_photo" value="1"
                                        <?php echo ($data_array['accept_photo'] == 1 ? ' checked ' : ''); ?>> Accept <a
                                        href="/doc/qdees_consent_letter_for_photography_video_social_networking_r1.pdf"
                                        target="_blank">Consent for Photography, Video &amp; Social Networking</a>
                                </td>
                            </tr>
                            <tr class="uk-text-bold">
                                <td class="uk-width-1-1" colspan="2"><input type="checkbox" name="accept_terms"
                                        id="accept_terms" value="1"
                                        <?php echo ($data_array['accept_terms'] == 1 ? ' checked ' : ''); ?>> Accept <a
                                        href="/doc/qdees_terms_and_conditions.pdf" target="_blank">Terms and
                                        Conditions</a> *
                                    <br>
                                    <span id="validationCheckbox2" style="color: red; display: none;">Please tick the
                                        checkbox</span>
                                </td>
                            </tr>

                            <tr>
                                <td class="uk-width-1-1" colspan="2">
                                    <div class="uk-width-1-1" class="uk-text-bold">Please sign below: *</div>
                                    <input class="uk-width-1-1" type="hidden" id="signature" name="signature"
                                        value='<?php echo $data_array['signature']?>'>
                                    <div class="uk-width-1-1" id="signature-container"></div>
                                    <a class="uk-button mt-2" onclick="clearSignature()">Clear Signature</a>
                                    <span id="validationSignature" style="color: red; display: none;">Please insert
                                        signature</span>
                                </td>
                            </tr>
                            <?php if (IS_ADMIN_STUDENT_FORM) { ?>
                            <tr class="uk-text-small">
                                <td class="uk-width-4-10 uk-text-bold" colspan="2" style=" border-bottom: 0;">
                                    <h2 style="margin: 13px 0 0;font-size: 18px;font-weight: bold;">
                                    <?php echo "Student Card" ?>
                                    </h2>
                                    <p style="margin: 13px 0 0;">
                                        <?php 
                    $sql="SELECT * from student_card where unique_id='$data_array[unique_id]' LIMIT 1";
                   // echo $sql;
                    $result=mysqli_query($connection, $sql);
                    if(mysqli_num_rows($result) > 0){
                    //echo "Assign card";
                    }
                ?>

                                    </p>
                                </td>
                            </tr>
                            <!-- <?php //if (IS_ADMIN_STUDENT_FORM) { ?> -->
                            <tr class="uk-text-small">
                                <td class="uk-width-4-10 uk-text-bold" colspan="2" style=" border-bottom: 0;">
                                Please key in and select the available QR Code ID
                                </td>
                            </tr>
                            <td class="uk-width-4-10 uk-text-bold">Student card ID : </td>
                            <td class="uk-width-6-10">
                                <div class="uk-grid-small uk-grid">
                                    <div class="uk-width-5-10" style="padding-right:0px">
                                        <?php if($data_array["unique_id"]=="" || $data_array["unique_id"]=="Select"){ ?>
                                        <select name="unique_id" class="chosen-select">
                                            <option value="">Select</option>
                                            <?php 
  $CentreCode =  $_SESSION['CentreCode'];
  $postRequest = array(
    'centre_code' => "$CentreCode",
    'session' => 'cb76fe897986563639f6983bfd33b57cd'
 );
//print_r($postRequest);
//$cURLConnection = curl_init('http://13.58.211.59/q-dees/api/qrcodes');
//$cURLConnection = curl_init('http://13.67.72.102/api/qrcodes');
/* $cURLConnection = curl_init('http://starters.q-dees.com/api/qrcodes');
curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $postRequest);
curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

$apiResponse = curl_exec($cURLConnection);
curl_close($cURLConnection);

// $apiResponse - available data from the API request
//echo $apiResponse;
$array = json_decode($apiResponse, true);
//$jsonArrayResponse = json_decode($apiResponse);
$status = $array['status'];
$message = $array['message'];
$TotalCard = $array['Total Card'];
$datas = $array['data'];
//print_r($array['status']);
//print_r($array['data']);
 */
if($status==1){
   foreach($datas as $data){
    $sql="SELECT unique_id from `student` where unique_id='$data[qr_code]' UNION ALL select unique_id from `student_card` where unique_id='$data[qr_code]'";
    $result=mysqli_query($connection, $sql);
    if(mysqli_num_rows($result) == 0){
  ?>

                                            <option value="<?php echo $data[qr_code] ?>"><?php echo $data[qr_code] ?>
                                            </option>

                                            <?php
    }
}
}else{

  ?>
                                            <option><?php echo $message ?></option>
                                            <?php
}
?>
                                        </select>
                                        <?php 
                  } else {
                    ?>
                                        <input type="text" name="unique_id" readonly
                                            value="<?php echo $data_array["unique_id"] ?>">
                                        <?php 
                  }
                  ?>
                                    </div>
                                    <?php 
                  if($_GET["id"]!="") {
                    ?>
                                    <div class="uk-width-5-10" style="padding-right:0px">
                                        <a class="uk-button" style="margin-top: 0;"
                                            href="/index.php?p=student_card&id=<?php echo $_GET["id"]?>">Change Card</a>
                                    </div>
                                    <?php 
                  }
                    ?>
                                </div>




                            </td>

                            </tr>
                            <?php } ?>

                            <tr>
                          
                            <!-- <tr>
                 <td class="uk-width-1-1" colspan="2">
                   <button type="submit" class="uk-button uk-button-primary form_btn mt-2">Submit</button>
                 </td>
               </tr>-->
                        </table>
                    </div>
                    <!--/.uk-width-large-5-10-->
                </div>
                <!--/.uk-grid-->
                <?php if (IS_ADMIN_STUDENT_FORM) { ?>
                <!-- <br>
                <div class="uk-width-1-1 myheader">
                    <h2 class="uk-text-center myheader-text-color myheader-text-style">PROGRAMME SELECTION</h2>
                </div> -->
                <div class="uk-grid uk-form-small nice-form" style="margin-left: 0;">
                    <!-- <br> -->
                    <!-- <div class="uk-width-medium-5-10">
                        <table class="uk-table uk-table-small">

                            <tr class="uk-text-small">
                                <td class="uk-width-4-10 uk-text-bold">Student Entry Level <span
                                        class="text-danger">*</span>:</td>
                                <td class="uk-width-6-10">
                                    <?php
							$fields=array("EDP"=>"EDP", "QF1"=>"QF1", "QF2"=>"QF2", "QF3"=>"QF3");
							generateSelectArray($fields, "student_entry_level", "class='uk-width-1-1 subject_3' id='student_entry_level'", $data_array["student_entry_level"]);
							?>
                                    <span id="validationStudentEntryLevel" style="color: red; display: none;">Please
                                        select Student Entry Level</span>
                                </td>
                            </tr>
                            <tr class="uk-text-small">
                                <td class="uk-width-4-10 uk-text-bold">Foundation Mandarin <span
                                        class="text-danger">*</span>: </td>
                                <td class="uk-width-6-10">
                                    <?php
							$fields=array("yes"=>"Yes", "no"=>"No");
							generateSelectArray($fields, "foundation_mandarin", "class='uk-width-1-1' id='foundation_mandarin' ", $data_array["foundation_mandarin"]);
							?>
                                    <span id="validationFoundationMandarin" style="color: red; display: none;">Please
                                        select Foundation Mandarin</span>
                                </td>
                            </tr>
                            <tr class="uk-text-small">
                                <td class="uk-width-4-10 uk-text-bold">Enhanced Foundation:</td>
                                <td class="uk-width-6-10 checkbox-inline" name="foundation">
                                    Int. Eng:&nbsp;<input type="checkbox" name="foundation_int_english"
                                        id="foundation_int_english" class="uk-width-1-1" value="1"
                                        <?php echo ($data_array['foundation_int_english'] == 1 ? ' checked ' : ''); ?>>&nbsp;&nbsp;
                                    EF IQ Maths:&nbsp;<input type="checkbox" name="foundation_iq_math"
                                        id="foundation_iq_math" class="uk-width-1-1" value="1"
                                        <?php echo ($data_array['foundation_iq_math'] == 1 ? ' checked ' : ''); ?>>
                                    &nbsp;&nbsp;
                                    EF Mandarin:&nbsp;<input type="checkbox" name="foundation_int_mandarin"
                                        id="foundation_int_mandarin" class="uk-width-1-1" value="1"
                                        <?php echo ($data_array['foundation_int_mandarin'] == 1 ? ' checked ' : ''); ?>>
                                    &nbsp;&nbsp;
                                    Int. Art:&nbsp;&nbsp;<input type="checkbox" name="foundation_int_art"
                                        id="foundation_int_art" class="uk-width-1-1" value="1"
                                        <?php echo ($data_array['foundation_int_art'] == 1 ? ' checked ' : ''); ?>>
                                    &nbsp;&nbsp;
                                    Pendidikan Islam:&nbsp;&nbsp;<input type="checkbox" name="pendidikan_islam"
                                        id="pendidikan_islam" class="uk-width-1-1" value="1"
                                        <?php echo ($data_array['pendidikan_islam'] == 1 ? ' checked ' : ''); ?>>
                                    &nbsp;&nbsp; <br>
                                    <span id="validationCheckbox3" style="color: red; display: none;">Please tick any
                                        checkbox</span>
                                </td>
                            </tr>
                            <tr class="uk-text-small">
                                <td class="uk-width-4-10 uk-text-bold">Programme package <span
                                        class="text-danger">*</span>: </td>
                                <td class="uk-width-6-10">
                                    <?php
							$fields=array("Full Day"=>"Full Day", "Half Day"=>"Half Day", "3/4 Day"=>"3/4 Day");
							generateSelectArray($fields, "programme_duration", "class='uk-width-1-1 subject_3' id='programme_duration' ", $data_array["programme_duration"]);
						 ?>
                                    <span id="validationDuration" style="color: red; display: none;">Please select
                                        Programme package</span>
                                </td>
                            </tr>
                            <tr class="uk-text-small">
                                <td class="uk-width-4-10 uk-text-bold">Afternoon Programme <span
                                        class="text-danger">*</span>:</td>
                                <td class="uk-width-6-10">
                                    <?php
              $fields=array("yes"=>"Yes", "no"=>"No");
              generateSelectArray($fields, "afternoon_programme", "class='uk-width-1-1' id='afternoon_programme' ", $data_array["afternoon_programme"]);
              ?>
                                    <span id="validationAfternoon" style="color: red; display: none;">Please select
                                        Afternoon Programme</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="uk-width-medium-5-10">
                        <table class="uk-table uk-table-small">
                            <tr class="uk-text-small">
                                <td class="uk-width-4-10 uk-text-bold">Commencement Date <span
                                        class="text-danger">*</span>:</td>
                                <td class="uk-width-6-10">
                                    <input class="uk-width-1-1" type="text" data-uk-datepicker="{format: 'DD/MM/YYYY'}"
                                        name="programme_date" id="programme_date"
                                        value="<?php echo convertDate2British($data_array['programme_date']); ?>"><br>
                                    <span id="validationCommencement" style="color: red; display: none;">Please select
                                        Commencement Date</span>
                                </td>
                            </tr>
                            <tr class="uk-text-small">
                                <td class="uk-width-4-10 uk-text-bold">Fees Setting <span class="text-danger">*</span>:
                                </td>
                                <td class="uk-width-6-10">

                                    <select name="fee_id" id="fee_id" class="uk-width-1-1 ">
                                        
                                        <option value="">Select</option>
                                        <?php
					
											$sql="SELECT * from fee_structure where id='".$data_array["fee_name"]."'";
											$result=mysqli_query($connection, $sql);
											while ($row=mysqli_fetch_assoc($result)) {
										 ?>
											<option value="<?php echo $row['id']?>"
												<?php if ($row["id"]==$data_array['fee_name']) {echo "selected";}?>>
												<?php echo $row["fees_structure"]?></option>
										<?php } ?>        
                                    </select>
									<input type="hidden" id="fee_name" value="<?php echo $data_array['fee_name']?>">
                                    <span id="validationfee_name" style="color: red; display: none;">Please select Fees
                                        Setting</span>
                                </td>
                            </tr>
                        </table>
                    </div> -->
                    <div style="display: none;" class="uk-width-medium-10-10">
                        <table class="uk-table uk-table-small">
                            <tr>
                                <td colspan="5" style="font-size:18px; font-weight:bold;"></td>
                            </tr>
                            <tr>
                                <td colspan="5" style="font-size:18px; font-weight:bold; border:none;">Part B (School
                                    Fees)</td>
                            </tr>
                            <tr class="">
                                <td style="border:none;" style=" margin-top:50px;" class="uk-width-1-10 uk-text-bold">
                                </td>
                                <!--<td style="border:none;text-align:center;" class="uk-width-1-10 ">
						<span>Default Fee</span>
					  </td>
					  <td style="border:none; " class="uk-width-1-10 de_d">
						<span class="default_1">Default Fee</span><br class="bru">
						<span class="default_1">Collection Pattern</span>
					  </td>-->
                                <td style="border:none;text-align: center;" class="uk-width-4-10">
                                    <span>Adjust Fee</span>
                                </td>
                                <td style="border:none;text-align: center;" class="uk-width-4-10">
                                    <span>Adjust Fee <br class="bru">Collection Pattern</span>
                                </td>
                            </tr>

                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-1-10 uk-text-bold">School
                                    Fees<span class="text-danger">*</span>:</td>
                                <!-- <td style="border:none; text-align:center;" class="uk-width-1-10">
							
						 <input class="school_number" type="number" step="0.01" name="school_default" id="school_default" value="<?php echo $data_array['school_default']?>" readonly><br>
						 <span id="validationsd"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
							
						 <input class="" type="text" name="school_default_perent" id="school_default_perent" value="<?php echo $data_array['school_default_perent'] ?>" readonly><br>
						 <span id="validationschool_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  </td>-->
                                <td style="border:none; text-align:center;" class="uk-width-1-10">

                                    <input class="school_adjust" type="number" step="0.01" name="school_adjust"
                                        id="school_adjust" value="<?php echo $data_array['school_adjust'] ?>"
                                        readonly><br>
                                    <span id="validationschool_adjust"
                                        style="color: red; display: none;font-size:11px;margin-left: -65px;">Please
                                        input Adjust Fee</span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <!-- <select name="school_collection" id="school_collection" class="" style="width: 100%;">
							<option value=""></option>
							<option value="Monthly" <?php // if($data_array['school_collection']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php// if($data_array['school_collection']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php// f($data_array['school_collection']=='Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php// if($data_array['school_collection']=='Annually') {echo 'selected';}?>>Annually</option>
						 </select><br>-->
                                    <input class="school_adjust" type="text" name="school_collection"
                                        id="school_collection" value="<?php echo $data_array['school_collection'] ?>"
                                        readonly>
                                    <span id="validationsc" style="color: red; display: none;font-size:11px;">Please
                                        select Collection Pattern</span>
                                </td>
                            </tr>
                            <tr class="">
                                <td style="margin-top:50px; border:none;" class="uk-width-1-10 uk-text-bold">Multimedia
                                    Fees<span class="text-danger">*</span>:</td>
                                <!--<td style="border:none; text-align:center;" class="uk-width-1-10">
						 <input class="school_number" type="number" step="0.01" name="multimedia_default" id="multimedia_default" value="<?php echo $data_array['multimedia_default']?>" readonly><br>
						 <span id="validationMd"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
							
						 <input class="" type="text" name="multimedia_default_perent" id="multimedia_default_perent" value="<?php // echo $data_array['multimedia_default_perent'] ?>" readonly><br>
						 <span id="validationmultimedia_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  </td>-->
                                <td style="border:none; text-align:center;" class="uk-width-1-10">

                                    <input class="school_adjust" type="number" step="0.01" name="multimedia_adjust"
                                        id="multimedia_adjust"
                                        value="<?php echo $data_array['multimedia_adjust'] ?>"><br>
                                    <span id="validationMa"
                                        style="color: red; display: none;font-size:11px;margin-left: -65px;">Please
                                        input Adjust Fee</span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">

                                    <input class="school_adjust" type="text" name="multimedia_collection"
                                        id="multimedia_collection"
                                        value="<?php echo $data_array['multimedia_collection'] ?>" readonly>
                                    <span id="validationMc" style="color: red; display: none;font-size:11px;">Please
                                        select Collection Pattern</span>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px; border:none;" class="uk-width-1-10 uk-text-bold">Facility
                                    Fees<span class="text-danger">*</span>:</td>
                                <!-- <td style="border:none;text-align:center;" class="uk-width-1-10">
							
						 <input class="school_number" type="number" step="0.01" name="facility_default" id="facility_default" value="<?php //echo $data_array['facility_default']?>" readonly><br>
						 <span id="validationfd"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
							
						 <input class="" type="text" name="facility_default_perent" id="facility_default_perent" value="<?php echo $data_array['facility_default_perent'] ?>" readonly><br>
						 <span id="validationfacility_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  </td>-->
                                <td style="border:none;text-align:center;" class="uk-width-1-10">

                                    <input class="school_adjust" type="number" step="0.01" name="facility_adjust"
                                        id="facility_adjust" value="<?php echo $data_array['facility_adjust'] ?>"><br>
                                    <span id="validationfa"
                                        style="color: red; display: none;font-size:11px;margin-left: -65px;">Please
                                        input Adjust Fee</span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <!--<select name="facility_collection" id="facility_collection" class="" style="width: 100%;">
							<option value=""></option>
							<option value="Monthly" <?php// if($data_array['facility_collection']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php// if($data_array['facility_collection']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php// if($data_array['facility_collection']=='Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php//if($data_array['facility_collection']=='Annually') {echo 'selected';}?>>Annually</option>
						 </select>-->
                                    <input class="school_adjust" type="text" name="facility_collection"
                                        id="facility_collection"
                                        value="<?php echo $data_array['facility_collection'] ?>" readonly><br>
                                    <span id="validationfc" style="color: red; display: none;font-size:11px;">Please
                                        select Collection Pattern</span>
                                </td>
                            </tr>
                            <tr class="">
                                <td style="margin-top:50px;border:none;" class="uk-width-1-10 uk-text-bold">Enhanced
                                    International Foundation English<span class="text-danger">*</span>:</td>
                                <!--<td style="border:none; text-align:center;" class="uk-width-1-10">							
						 <input class="school_number" type="number" step="0.01" name="enhanced_default" id="enhanced_default" value="<?php// echo $data_array['enhanced_default']?>" readonly><br>
						 <span id="validationed"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none; " class="uk-width-1-10">
							
						 <input class="" type="text" name="enhanced_default_perent" id="enhanced_default_perent" value="<?php// echo $data_array['enhanced_default_perent'] ?>" readonly><br>
						 <span id="validationenhanced_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  </td>-->
                                <td style="border:none; text-align:center;" class="uk-width-1-10">

                                    <input class="school_adjust" type="number" step="0.01" name="enhanced_adjust"
                                        id="enhanced_adjust" value="<?php echo $data_array['enhanced_adjust'] ?>"><br>
                                    <span id="validationea"
                                        style="color: red; display: none;font-size:11px;margin-left: -65px;">Please
                                        input Adjust Fee</span>

                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="text" name="enhanced_collection"
                                        id="enhanced_collection"
                                        value="<?php echo $data_array['enhanced_collection'] ?>" readonly><br>
                                    <!--<select name="enhanced_collection" id="enhanced_collection" class="" style="width: 100%;">
							<option value=""></option>
							<option value="Monthly" <?php // if($data_array['enhanced_collection']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php// if($data_array['enhanced_collection']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php // if($data_array['enhanced_collection']=='Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php// if($data_array['enhanced_collection']=='Annually') {echo 'selected';}?>>Annually</option>
						 </select><br>-->
                                    <span id="validationec" style="color: red; display: none;font-size:11px;">Please
                                        select Collection Pattern</span>
                                </td>
                            </tr>
                            <tr class="">
                                <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold">Total:</td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="" type="number" step="0.01" name="school_total_d" id="school_total_d"
                                        value="<?php echo $data_array['school_total_d']?>" readonly>
                                </td>
                                <td class="uk-width-1-10"></td>
                                <!-- <td style="text-align:center;" class="uk-width-1-10">
							
						 <input class="" type="number" step="0.01" name="school_total_f" id="school_total_f" value="<?php  //echo $data_array['school_total_f'] ?>">
					  </td>-->
                                <td class="uk-width-1-10">
                                    <!--<select name="monthly" id="monthly" class="" style="width: 100%;">
							<option value=""></option>
							<option value="Monthly" <?php // if($data_array['programme_package']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php // if($data_array['programme_package']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php // if($data_array['programme_package']==' Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php // if($data_array['programme_package']==' Annually') {echo 'selected';}?>>Annually</option>
						 </select>-->
                                </td>
                            </tr>
                            <!--</table>
				<table class="uk-table uk-table-small">-->
                            <tr>
                                <td colspan="5" style="font-size:18px; font-weight:bold;border:none;">Part C (Enhanced
                                    Foundation Programme)</td>
                            </tr>
                            <tr class="">
                                <td style="margin-top:50px; border:none;" class="uk-width-1-10 uk-text-bold">IQ
                                    Math<span class="text-danger">*</span>:</td>
                                <!--<td style="border:none;text-align:center;" class="uk-width-1-10" readonly>							
						 <input class="math_default" type="number" step="0.01" name="iq_math_default" id="iq_math_default" value="<?php// echo $data_array['iq_math_default'] ?>" readonly><br>
						 <span id="validationiq"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
							
						 <input class="" type="text" name="iq_math_default_perent" id="iq_math_default_perent" value="<?php echo $data_array['iq_math_default_perent'] ?>" readonly><br>
						 <span id="validationiq_math_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  </td>-->
                                <td style="border:none;text-align:center;" class="uk-width-1-10">

                                    <input class="math_adjust" type="number" step="0.01" name="iq_math_adjust"
                                        id="iq_math_adjust" value="<?php echo $data_array['iq_math_adjust'] ?>"><br>
                                    <span id="validationma"
                                        style="color: red; display: none;font-size:11px;margin-left: -65px;">Please
                                        input Adjust Fee</span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="text" name="iq_math_collection"
                                        id="iq_math_collection" value="<?php echo $data_array['iq_math_collection'] ?>"
                                        readonly><br>
                                    <!--<select name="iq_math_collection" id="iq_math_collection" class="" style="width: 100%;">
							<option value=""></option>
							<option value="Monthly" <?php // if($data_array['iq_math_collection']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php// if($data_array['iq_math_collection']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php// if($data_array['iq_math_collection']=='Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php// if($data_array['iq_math_collection']=='Annually') {echo 'selected';}?>>Annually</option>
						 </select><br>-->
                                    <span id="validationmc" style="color: red; display: none;font-size:11px;">Please
                                        select Collection Pattern</span>
                                </td>
                            </tr>
                            <tr class="">
                                <td style="margin-top:50px;border:none;" class="uk-width-1-10 uk-text-bold">
                                    Mandarin<span class="text-danger">*</span>:</td>
                                <!-- <td style="border:none;text-align:center;" class="uk-width-1-10">							
						 <input class="math_default" type="number" step="0.01" name="mandarin_default" id="mandarin_default" value="<?php //echo $data_array['mandarin_default'] ?>" readonly><br>
						 <span id="validationmdf"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
							
						 <input class="" type="text" name="mandarin_default_perent" id="mandarin_default_perent" value="<?php// echo $data_array['mandarin_default_perent'] ?>" readonly><br>
						 <span id="validationmandarin_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  </td>-->
                                <td style="border:none;text-align:center;" class="uk-width-1-10">

                                    <input class="math_adjust" type="number" step="0.01" name="mandarin_adjust"
                                        id="mandarin_adjust" value="<?php echo $data_array['mandarin_adjust'] ?>"><br>
                                    <span id="validationmda"
                                        style="color: red; display: none;font-size:11px;margin-left: -65px;">Please
                                        input Adjust Fee</span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="text" name="mandarin_collection"
                                        id="mandarin_collection"
                                        value="<?php echo $data_array['mandarin_collection'] ?>" readonly><br>
                                    <!--<select name="mandarin_collection" id="mandarin_collection" class="" style="width: 100%;">
							<option value=""></option>
							<option value="Monthly" <?php// if($data_array['mandarin_collection']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php// if($data_array['mandarin_collection']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php// if($data_array['mandarin_collection']=='Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php// if($data_array['mandarin_collection']=='Annually') {echo 'selected';}?>>Annually</option>
						 </select><br>-->
                                    <span id="validationmdc" style="color: red; display: none;font-size:11px;">Please
                                        select Collection Pattern</span>
                                </td>
                            </tr>
                            <tr class="">
                                <td style="margin-top:50px;border:none;" class="uk-width-1-10 uk-text-bold">
                                    International Art<span class="text-danger">*</span>:</td>
                                <!--<td style="border:none;text-align:center;" class="uk-width-1-10">							
						 <input class="math_default" type="number" step="0.01" name="international_default" id="international_default" value="<?php echo $data_array['international_default'] ?>" readonly><br>
						 <span id="validationind"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
							
						 <input class="" type="text" name="international_perent" id="international_perent" value="<?php echo $data_array['mandarin_default_perent'] ?>" readonly><br>
						 <span id="validationinternational_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  </td>-->
                                <td style="border:none;text-align:center;" class="uk-width-1-10">

                                    <input class="math_adjust" type="number" step="0.01" name="international_adjust"
                                        id="international_adjust"
                                        value="<?php echo $data_array['international_adjust'] ?>"><br>
                                    <span id="validationina"
                                        style="color: red; display: none;font-size:11px;margin-left: -65px;">Please
                                        input Adjust Fee</span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="text" name="international_collection"
                                        id="international_collection"
                                        value="<?php echo $data_array['international_collection'] ?>" readonly><br>
                                    <!--<select name="international_collection" id="international_collection" class="" style="width: 100%;">
							<option value=""></option>
							<option value="Monthly" <?php// if($data_array['international_collection']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php// if($data_array['international_collection']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php// if($data_array['international_collection']=='Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php// if($data_array['international_collection']=='Annually') {echo 'selected';}?>>Annually</option>
						 </select><br>-->
                                    <span id="validationinc" style="color: red; display: none;font-size:11px;">Please
                                        select Collection Pattern</span>
                                </td>
                            </tr>
                            <tr class="">
                                <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold">Total:</td>
                                <!--<td style="text-align:center;" class="uk-width-1-10">							
						 <input class="" type="number" step="0.01" name="total_default_c" id="total_default_c" value="<?php  // echo $data_array['total_default_c'] ?>" readonly>
					  </td>
					  <td class="uk-width-1-10"></td>-->
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="" type="number" step="0.01" name="total_adjust_c" id="total_adjust_c"
                                        value="<?php echo $data_array['total_adjust_c'] ?>">
                                </td>
                                <td class="uk-width-1-10">
                                    <!--<select name="monthly" id="monthly" class="" style="width: 100%;">
							<option value=""></option>
							<option value="Monthly" <?php // if($data_array['programme_package']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php // if($data_array['programme_package']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php // if($data_array['programme_package']==' Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php // if($data_array['programme_package']==' Annually') {echo 'selected';}?>>Annually</option>
						 </select>-->
                                </td>
                            </tr>
                            <!--</table>
				<table class="uk-table uk-table-small">-->
                            <tr>
                                <td colspan="5" style="font-size:18px; font-weight:bold;border:none;">Part D (Material
                                    Fees)</td>
                            </tr>
                            <tr class="">
                                <td style="width: 7.7%; margin-top:50px;border:none;"
                                    class="uk-width-1-10 uk-text-bold">Integrated Modules<span
                                        class="text-danger">*</span>:</td>
                                <!--<td style="border:none;text-align:center;" class="uk-width-1-10">							
						 <input class="int_default" type="number" step="0.01" name="integrated_default" id="integrated_default" value="<?php //echo $data_array['integrated_default'] ?>" readonly><br>
						 <span id="validationitd"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
							
						 <input class="" type="text" step="0.01" name="integrated_default_perent" id="integrated_default_perent" value="<?php// echo $data_array['integrated_default_perent'] ?>" readonly><br>
						 <span id="validationintegrated_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  </td>-->
                                <td style="border:none;text-align:center;" class="uk-width-1-10">

                                    <input class="integrated_adjust" type="number" step="0.01" name="integrated_adjust"
                                        id="integrated_adjust"
                                        value="<?php echo $data_array['integrated_adjust'] ?>"><br>
                                    <span id="validationita"
                                        style="color: red; display: none;font-size:11px;margin-left: -65px;">Please
                                        input Adjust Fee</span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="text" name="integrated_collection"
                                        id="integrated_collection"
                                        value="<?php echo $data_array['integrated_collection'] ?>" readonly><br>
                                    <!--<select name="integrated_collection" id="integrated_collection" class="" style="width: 100%;">
							<option value=""></option>
							<option value="Monthly" <?php// if($data_array['integrated_collection']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php// if($data_array['integrated_collection']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php// if($data_array['integrated_collection']=='Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php// if($data_array['integrated_collection']=='Annually') {echo 'selected';}?>>Annually</option>
						 </select><br>-->
                                    <span id="validationitc" style="color: red; display: none;font-size:11px;">Please
                                        select Collection Pattern</span>
                                </td>
                            </tr>
                            <tr class="">
                                <td style="margin-top:50px;border:none;" class="uk-width-1-10 uk-text-bold">Link & Think
                                    Reading Series<span class="text-danger">*</span>:</td>
                                <!--<td style="border:none;text-align:center;" class="uk-width-1-10">							
						 <input class="int_default" type="number" step="0.01" name="link_default" id="link_default" value="<?php // echo $data_array['link_default'] ?>" readonly ><br>
						 <span id="validationlnk"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
							
						 <input class="" type="text" name="link_default_perent" id="link_default_perent" value="<?php//  echo $data_array['link_default_perent'] ?>" readonly><br>
						 <span id="validationlink_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  </td>-->
                                <td style="border:none;text-align:center;" class="uk-width-1-10">

                                    <input class="integrated_adjust" type="number" step="0.01" name="link_adjust"
                                        id="link_adjust" value="<?php echo $data_array['link_adjust'] ?>"><br>
                                    <span id="validationlna"
                                        style="color: red; display: none;font-size:11px;margin-left: -65px;">Please
                                        input Adjust Fee</span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="text" name="link_collection" id="link_collection"
                                        value="<?php echo $data_array['link_collection'] ?>" readonly><br>
                                    <!--<select name="link_collection" id="link_collection" class="" style="width: 100%;">
							<option value=""></option>
							<option value="Monthly" <?php// if($data_array['link_collection']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php// if($data_array['link_collection']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php// if($data_array['link_collection']=='Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php// if($data_array['link_collection']=='Annually') {echo 'selected';}?>>Annually</option>
						 </select><br>-->
                                    <span id="validationlnc" style="color: red; display: none;font-size:11px;">Please
                                        select Collection Pattern</span>
                                </td>
                            </tr>
                            <tr class="">
                                <td style="margin-top:50px; border:none;" class="uk-width-1-10 uk-text-bold">Mandarin
                                    Modules<span class="text-danger">*</span>:</td>
                                <!--<td style="border:none;text-align:center;" class="uk-width-1-10">							
						 <input class="int_default" type="number" step="0.01" name="mandarin_m_defauft" id="mandarin_m_defauft" value="<?php // echo $data_array['mandarin_m_defauft'] ?>" readonly><br>
						 <span id="validationmm"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
							
						 <input class="" type="text" name="mandarin_m_default_perent" id="mandarin_m_default_perent" value="<?php // echo $data_array['mandarin_m_default_perent'] ?>" readonly><br>
						 <span id="validationmandarin_m_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  </td>-->
                                <td style="border:none;text-align:center;" class="uk-width-1-10">

                                    <input class="integrated_adjust" type="number" step="0.01" name="mandarin_m_adjust"
                                        id="mandarin_m_adjust"
                                        value="<?php echo $data_array['mandarin_m_adjust'] ?>"><br>
                                    <span id="validationmma"
                                        style="color: red; display: none;font-size:11px;margin-left: -65px;">Please
                                        input Adjust Fee</span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="text" name="mandarin_m_collection"
                                        id="mandarin_m_collection"
                                        value="<?php echo $data_array['mandarin_m_collection'] ?>" readonly><br>
                                    <!--<select name="mandarin_m_collection" id="mandarin_m_collection" class="" style="width: 100%;">
							<option value=""></option>
							<option value="Monthly" <?php// if($data_array['mandarin_m_collection']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php// if($data_array['mandarin_m_collection']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php// if($data_array['mandarin_m_collection']=='Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php// if($data_array['mandarin_m_collection']=='Annually') {echo 'selected';}?>>Annually</option>
						 </select><br>-->
                                    <span id="validationmmc" style="color: red; display: none;font-size:11px;">Please
                                        select Collection Pattern</span>
                                </td>
                            </tr>
                            <tr class="">
                                <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold">Total:</td>
                                <!--<td style="text-align:center;" class="uk-width-1-10">							
						 <input class="" type="number" step="0.01" name="total_default_d" id="total_default_d" value="<?php echo $data_array['total_default_d'] ?>" readonly>
					  </td>
					  <td class="uk-width-1-10"></td>-->
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="" type="number" step="0.01" name="total_adjust_d" id="total_adjust_d"
                                        value="<?php echo $data_array['total_adjust_d'] ?>">
                                </td>
                                <td class="uk-width-1-10">
                                    <!--<select name="monthly" id="monthly" class="" style="width: 100%;">
							<option value=""></option>
							<option value="Monthly" <?php // if($data_array['programme_package']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php // if($data_array['programme_package']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php // if($data_array['programme_package']==' Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php // if($data_array['programme_package']==' Annually') {echo 'selected';}?>>Annually</option>
						 </select>-->
                                </td>
                            </tr>
                            <!--</table>
				<table class="uk-table uk-table-small">-->
                            <tr>
                                <td colspan="5" style="font-size:18px; font-weight:bold;border:none;">Part E (Afternoon
                                    Programme)</td>
                            </tr>
                            <tr class="">
                                <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold">Basic Afternoon
                                    Programme<span class="text-danger">*</span>:</td>
                                <!--<td style="text-align:center;" class="uk-width-1-10">							
						 <input class="" type="number" step="0.01" name="basic_default" id="basic_default" value="<?php // echo $data_array['basic_default'] ?>" readonly><br>
						 <span id="validationbd"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td class="uk-width-1-10">
							
						 <input class="" type="text" name="basic_default_perent" id="basic_default_perent" value="<?php // echo $data_array['basic_default_perent'] ?>" readonly><br>
						 <span id="validationbasic_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  </td>-->
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="" type="number" step="0.01" name="basic_adjust" id="basic_adjust"
                                        value="<?php echo $data_array['basic_adjust'] ?>"><br><span id="validationba"
                                        style="color: red; display: none;font-size:11px;margin-left: -65px;">Please
                                        input Adjust Fee</span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="text" name="basic_collection"
                                        id="basic_collection" value="<?php echo $data_array['basic_collection'] ?>"
                                        readonly><br>
                                    <!--<select name="basic_collection" id="basic_collection" class="" style="width: 100%;">
							<option value=""></option>
							<option value="Monthly" <?php// if($data_array['basic_collection']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php// if($data_array['basic_collection']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php// if($data_array['basic_collection']=='Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php// if($data_array['basic_collection']=='Annually') {echo 'selected';}?>>Annually</option>
							
						 </select><br>-->
                                    <span id="validationbc" style="color: red; display: none;font-size:11px;">Please
                                        select Collection Pattern</span>
                                </td>
                            </tr>

                            <!--</table>
				<table class="uk-table uk-table-small">-->
                            <tr>
                                <td colspan="5" style="font-size:18px; font-weight:bold;border:none;">Part F (Upon
                                    Registration)</td>
                            </tr>
                            <tr class="">
                                <td style="margin-top:50px; border:none;" class="uk-width-1-10 uk-text-bold">Mobile
                                    Application<span class="text-danger">*</span>:</td>
                                <!--<td style="border:none;text-align:center;" class="uk-width-1-10">							
						 <input class="regist_default" step="0.01" type="number" name="mobile_default" id="mobile_default" value="<?php// echo $data_array['mobile_default'] ?>" readonly><br>
						 <span id="validationmdi"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
							
						 <input class="" type="text" name="mobile_perent" id="mobile_perent" value="<?php// echo $data_array['mobile_perent'] ?>" readonly><br>
						 <span id="validationmobile_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  </td>-->
                                <td style="border:none;text-align:center;" class="uk-width-1-10">

                                    <input class="registration_adjust" type="number" step="0.01" name="mobile_adjust"
                                        id="mobile_adjust" value="<?php echo $data_array['mobile_adjust'] ?>"><br>
                                    <span id="validationmba"
                                        style="color: red; display: none;font-size:11px;margin-left: -65px;">Please
                                        input Adjust Fee</span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="text" name="mobile_collection"
                                        id="mobile_collection" value="<?php echo $data_array['mobile_collection'] ?>"
                                        readonly><br>
                                    <!--<select name="mobile_collection" id="mobile_collection" class="" style="width: 100%;">
							<option value=""></option>
							<option value="Monthly" <?php // if($data_array['mobile_collection']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php// if($data_array['mobile_collection']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php// if($data_array['mobile_collection']=='Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php// if($data_array['mobile_collection']=='Annually') {echo 'selected';}?>>Annually</option>
							
						 </select><br>-->
                                    <span id="validationmbc" style="color: red; display: none;font-size:11px;">Please
                                        select Collection Pattern</span>
                                </td>
                            </tr>
                            <tr class="">
                                <td style="margin-top:50px; border:none;" class="uk-width-1-10 uk-text-bold">
                                    Registration<span class="text-danger">*</span>:</td>
                                <!--<td style="border:none;text-align:center;" class="uk-width-1-10" >							
						 <input class="regist_default" step="0.01" type="number" name="registration_default" id="registration_default" value="<?php// echo $data_array['registration_default'] ?>" readonly><br>
						 <span id="validationrd"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10"></td>-->
                                <td style="border:none;text-align:center;" class="uk-width-1-10">

                                    <input class="registration_adjust" type="number" step="0.01"
                                        name="registration_adjust" id="registration_adjust"
                                        value="<?php echo $data_array['registration_adjust'] ?>" readonly><br>
                                    <span id="validationra"
                                        style="color: red; display: none;font-size:11px;margin-left: -65px;">Please
                                        input Adjust Fee</span>
                                </td>
                                <td style="border:none;" class="uk-width-1-10">
                                    <!--<select name="monthly" id="monthly" class="">
							<option value=""></option>
							<option value="Monthly" <?php // if($data_array['programme_package']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php // if($data_array['programme_package']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php // if($data_array['programme_package']==' Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php // if($data_array['programme_package']==' Annually') {echo 'selected';}?>>Annually</option>
						 </select>-->
                                </td>
                            </tr>

                            <tr class="">
                                <td style="margin-top:50px;border:none;" class="uk-width-1-10 uk-text-bold">
                                    Insurance<span class="text-danger">*</span>:</td>
                                <!--<td style="border:none;text-align:center;" class="uk-width-1-10">							
						 <input class="regist_default" step="0.01" type="number" name="insurance_default" id="insurance_default" value="<?php //echo $data_array['insurance_default'] ?>" readonly><br>
						 <span id="validationinsd"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10"></td>-->
                                <td style="border:none;text-align:center;" class="uk-width-1-10">

                                    <input class="registration_adjust" type="number" step="0.01" name="insurance_adjust"
                                        id="insurance_adjust" value="<?php echo $data_array['insurance_adjust'] ?>"
                                        readonly><br>
                                    <span id="validationinsa"
                                        style="color: red; display: none;font-size:11px;margin-left: -65px;">Please
                                        input Adjust Fee</span>
                                </td>
                                <td style="border:none;" class="uk-width-1-10">
                                    <!--<select name="monthly" id="monthly" class="">
							<option value=""></option>
							<option value="Monthly" <?php // if($data_array['programme_package']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php // if($data_array['programme_package']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php // if($data_array['programme_package']==' Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php // if($data_array['programme_package']==' Annually') {echo 'selected';}?>>Annually</option>
						 </select>-->
                                </td>
                            </tr>
                            <tr class="">
                                <td style="margin-top:50px; border:none;" class="uk-width-1-10 uk-text-bold">Uniform (2
                                    sets)<span class="text-danger">*</span>:</td>
                                <!--<td style="border:none;text-align:center;" class="uk-width-1-10">							
						 <input class="regist_default" step="0.01" type="number" name="uniform_default" id="uniform_default" value="<?php echo $data_array['uniform_default'] ?>" readonly><br>
						 <span id="validationuid"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10"></td>-->
                                <td style="border:none;text-align:center;" class="uk-width-1-10">

                                    <input class="registration_adjust" type="number" step="0.01" name="uniform_adjust"
                                        id="uniform_adjust" value="<?php echo $data_array['uniform_adjust'] ?>"
                                        readonly><br>
                                    <span id="validationuia"
                                        style="color: red; display: none;font-size:11px;margin-left: -65px;">Please
                                        input Adjust Fee</span>
                                </td>
                                <td style="border:none;" class="uk-width-1-10">
                                    <!--<select name="monthly" id="monthly" class="">
							<option value=""></option>
							<option value="Monthly" <?php // if($data_array['programme_package']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php // if($data_array['programme_package']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php // if($data_array['programme_package']==' Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php // if($data_array['programme_package']==' Annually') {echo 'selected';}?>>Annually</option>
						 </select>-->
                                </td>
                            </tr>
                            <tr class="">
                                <td style="margin-top:50px; border:none;" class="uk-width-1-10 uk-text-bold">Gymwear (1
                                    set)<span class="text-danger">*</span>:</td>
                                <!--<td style="border:none;text-align:center;" class="uk-width-1-10">							
						 <input class="regist_default" step="0.01" type="number" name="gymwear_default" id="gymwear_default" value="<?php// echo $data_array['gymwear_default'] ?>" readonly><br>
						 <span id="validationgyd"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10"></td>-->
                                <td style="border:none;text-align:center;" class="uk-width-1-10">

                                    <input class="registration_adjust" type="number" step="0.01" name="gymwear_adjust"
                                        id="gymwear_adjust" value="<?php echo $data_array['gymwear_adjust'] ?>"
                                        readonly><br>
                                    <span id="validationgya"
                                        style="color: red; display: none;font-size:11px;margin-left: -65px;">Please
                                        input Adjust Fee</span>
                                </td>
                                <td style="border:none;" class="uk-width-1-10">
                                    <!--<select name="monthly" id="monthly" class="">
							<option value=""></option>
							<option value="Monthly" <?php // if($data_array['programme_package']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php // if($data_array['programme_package']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php // if($data_array['programme_package']==' Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php // if($data_array['programme_package']==' Annually') {echo 'selected';}?>>Annually</option>
						 </select>-->
                                </td>
                            </tr>
                            <tr class="">
                                <td style="margin-top:50px;border:none;" class="uk-width-1-10 uk-text-bold">Q-dees Level
                                    Kit<span class="text-danger">*</span>:</td>
                                <!--<td style="border:none;text-align:center;" class="uk-width-1-10">							
						 <input class="regist_default" step="0.01" type="number" name="q_dees_default" id="q_dees_default" value="<?php // echo $data_array['q_dees_default'] ?>" readonly><br>
						 <span id="validationqd"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10"></td>-->
                                <td style="border:none;text-align:center;" class="uk-width-1-10">

                                    <input class="registration_adjust" type="number" step="0.01" name="q_dees_adjust"
                                        id="q_dees_adjust" value="<?php echo $data_array['q_dees_adjust'] ?>"
                                        readonly><br>
                                    <span id="validationqa"
                                        style="color: red; display: none;font-size:11px;margin-left: -65px;">Please
                                        input Adjust Fee</span>
                                </td>
                                <td style="border:none;" class="uk-width-1-10">
                                    <!--<select name="monthly" id="monthly" class="">
							<option value=""></option>
							<option value="Monthly" <?php // if($data_array['programme_package']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php // if($data_array['programme_package']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php // if($data_array['programme_package']==' Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php // if($data_array['programme_package']==' Annually') {echo 'selected';}?>>Annually</option>
						 </select>-->
                                </td>
                            </tr>
                            <tr class="">
                                <td style="margin-top:50px;border:none;" class="uk-width-1-10 uk-text-bold">Q-dees
                                    Bag<span class="text-danger">*</span>:</td>
                                <!--<td style="border:none;text-align:center;" class="uk-width-1-10">							
						 <input class="regist_default" step="0.01" type="number" name="q_bag_default" id="q_bag_default" value="<?php // echo $data_array['q_bag_default'] ?>" readonly><br>
						 <span id="validationqbdd"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10"></td>-->
                                <td style="border:none;text-align:center;" class="uk-width-1-10">

                                    <input class="registration_adjust" type="number" step="0.01" name="q_bag_adjust"
                                        id="q_bag_adjust" value="<?php echo $data_array['q_bag_adjust'] ?>"
                                        readonly><br>
                                    <span id="validationqbad"
                                        style="color: red; display: none;font-size:11px;margin-left: -65px;">Please
                                        input Adjust Fee</span>
                                </td>
                                <td style="border:none;" class="uk-width-1-10">
                                    <!--<select name="monthly" id="monthly" class="">
							<option value=""></option>
							<option value="Monthly" <?php // if($data_array['programme_package']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php // if($data_array['programme_package']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php // if($data_array['programme_package']==' Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php // if($data_array['programme_package']==' Annually') {echo 'selected';}?>>Annually</option>
						 </select>-->
                                </td>
                            </tr>
                            <tr class="">
                                <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold">Total:</td>
                                <!--<td style="text-align:center;" class="uk-width-1-10">							
						 <input class="" step="0.01" type="number" name="total_default_f" id="total_default_f" value="<?php echo $data_array['total_default_f'] ?>" readonly>
					  </td>
					  <td class="uk-width-1-10"></td>-->
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="" type="number" step="0.01" name="total_adjust_f" id="total_adjust_f"
                                        value="<?php echo $data_array['total_adjust_f'] ?>" readonly>
                                </td>
                                <td class="uk-width-1-10">
                                    <!--<select name="monthly" id="monthly" class="" style="width: 100%;">
							<option value=""></option>
							<option value="Monthly" <?php // if($data_array['programme_package']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php // if($data_array['programme_package']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php // if($data_array['programme_package']==' Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php // if($data_array['programme_package']==' Annually') {echo 'selected';}?>>Annually</option>
						 </select>-->
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5" style="font-size:18px; font-weight:bold;border:none;">Part G (Pendidikan
                                    Islam)</td>
                            </tr>
                            <tr class="">
                                <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold">Pendidikan Islam (Muslim
                                    students only)<span class="text-danger">*</span>:</td>
                                <!--<td style="text-align:center;" class="uk-width-1-10">							
						 <input class="" step="0.01" type="number" name="islam_default" id="islam_default" value="<?php // echo $data_array['islam_default'] ?>" readonly><br><span id="validationisd"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td class="uk-width-2-10"></td>-->
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="" type="number" step="0.01" name="islam_adjust" id="islam_adjust"
                                        value="<?php echo $data_array['islam_adjust'] ?>" readonly><br><span
                                        id="validationisa"
                                        style="color: red; display: none;font-size:11px;margin-left: -65px;">Please
                                        input Adjust Fee</span>
                                </td>
                                <td class="uk-width-1-10">
                                    <!--<select name="monthly" id="monthly" class="">
							<option value=""></option>
							<option value="Monthly" <?php // if($data_array['programme_package']=='Monthly') {echo 'selected';}?>>Monthly</option>
							<option value="Termly" <?php // if($data_array['programme_package']=='Termly') {echo 'selected';}?>>Termly</option>
							<option value="Half Year" <?php // if($data_array['programme_package']==' Half Year') {echo 'selected';}?>> Half Year</option>
							<option value="Annually" <?php // if($data_array['programme_package']==' Annually') {echo 'selected';}?>>Annually</option>
						 </select>-->
                                </td>
                            </tr>
                        </table>
                    </div>
                    <?php }else{ ?>

                    <?php } ?>
                    <div class="uk-width-medium-10-10 uk-text-center">
                        <br>
                        <button type="submit" id="submit" name="submit"
                            class="uk-button uk-button-primary form_btn uk-text-center">Save</button>

                            <?php if($_GET['mode'] == 'EDIT') { ?>

                                <a type="button" id="print_to_pdf" target="_blank" href="admin/student_detail_print.php?id=<?php echo $_GET['id']; ?>" name="print_to_pdf" class="uk-button uk-button-primary form_btn uk-text-center">Print to PDF</a>

                            <?php } ?>
                    </div>

                </div>
                <?php if (IS_ADMIN_STUDENT_FORM == false) { ?>
        </div>
        <!--/.container-->
        <?php } ?>
    </form>


    <div id="dlgEmergencyContacts"></div>

    <style>
    #the_grid {
        max-width: 100%;
    }

    .kbw-signature {
        width: 100%;
        height: 150px;
        border: 1px solid #999
    }

    .uk-button.uk-button-primary {
        background: #fdba0c !important;
        color: white !important;
        padding: .3em 2em;
    }
    </style>
    <script src="lib/sign/js/jquery.signature.js"></script>
    <script type="text/javascript" src="lib/sign/js/jquery.ui.touch-punch.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.min.css">
    <script>
	function getFee(){
		var subject = $("#student_entry_level").val();
        var programme_package = $("#programme_duration").val();
        $.ajax({
            url: "admin/get_feev_name.php",
            type: "POST",
            data: "subject=" + subject + "&programme_package=" + programme_package,
            dataType: "text",
            beforeSend: function(http) {},
            success: function(response, status, http) {
                //console.log(response)
                if (response != "") { 
                    $("#fee_id").html(response);
					$("#fee_id").val($("#fee_name").val());
                } else {
                    $("#fee_id").html("<option value=''>Select</option>");
                }
            },
            error: function(http, status, error) {

            }
	})
	}
	
    $('.chosen-select').chosen({
        search_contains: true
    }).change(function(obj, result) {
        // console.debug("changed: %o", arguments);
        // console.log("selected: " + result.selected);
    });

    function checkContacts(student_code, form_mode) {
        $.ajax({
            url: "admin/check_contacts.php",
            type: "POST",
            data: "student_code=" + student_code + "&form_mode=" + form_mode,
            dataType: "text",
            beforeSend: function(http) {},
            async: false,
            success: function(response, status, http) {

                if (response == "OK") {
                    $("#contact_check").val("1");
                } else {
                    $("#contact_check").val("0");
                }
            },
            error: function(http, status, error) {
                UIkit.notify("Error:" + error);
            }
        });
    }

    
    function isDataOK(e) {
        var student_code = $("#student_code").val();
        var centre_code = $("#centre_code").val();
        var student_status = $("#student_status").val();
        var name = $("#name").val();
        var nric_no = $("#nric_no").val();
        var dob_year = $("#dob_year").val();
        var dob_month = $("#dob_month").val();
        var dob_day = $("#dob_day").val();
        var birth_cert_no = $("#birth_cert_no").val();
        var start_date_at_centre = $("#start_date_at_centre").val();
        var gender = $("#gender").val();
        var add1 = $("#add1").val();
        var country = $("#country").val();
        var state = $("#state").val();
        var race = $("#race").val();
        var nationality = $("#nationality").val();
        var religion = $("#religion").val();
        var accept_photo = $("#accept_photo").prop('checked');
        var accept_terms = $("#accept_terms").prop('checked');
        var attachment = $("#attachment").val();
        var signature = $("#signature").val();
        var form_mode = $("#form_mode").val();
        var allergies = $("#allergies").val();
        var health_problem = $("#health_problem").val();
        var primary_contact1 = $("#primary-contact3").text();
        var primary_contact2 = $("#primary-contact2").text();
        // var student_entry_level = $("#student_entry_level").val();
       
        // var foundation_mandarin = $("#foundation_mandarin").val();
        // var programme_duration = $("#programme_duration").val();
        // var afternoon_programme = $("#afternoon_programme").val();
        // var programme_date = $("#programme_date").val();
        // var fee_name = $("#fee_id").val();


        checkContacts(student_code, form_mode);
        var contact_check = $("#contact_check").val();

        if (contact_check != "1") {
            e.preventDefault();
            UIkit.notify("Please fill in at least 1 primary contact");
        }
        //if (!name || student_status === "" || !nric_no || dob_year === "" || dob_month === "" || dob_day === "" || ! birth_cert_no || !start_date_at_centre || !gender || !add1 || !country || !state || !race || !nationality || !religion || !accept_terms || !signature || !allergies || !health_problem || student_entry_level === "" || foundation_mandarin === "" || programme_duration === "" || programme_date === "" || primary_contact =="Primary contact eg: Parents or Guardian" || fee_name === "") {

        if (!name || student_status === "" || dob_year === "" || dob_month === "" || dob_day === "" || !start_date_at_centre || !gender || !add1 || !country || !state || !race || !nationality ||
            !religion || !accept_terms || !signature || !allergies || !health_problem || primary_contact ==
            "Primary contact eg: Parents or Guardian" ) {
            e.preventDefault();
            UIkit.notify("Please fill up mandatory fields marked *");

            
            if (primary_contact1 === "Primary contact eg: Parents or Guardian") {
                $('#validationParentContacts').show();
            } else {
                $('#validationParentContacts').hide();
            }
            console.log(primary_contact2);
            if (primary_contact2 === "Primary contact eg: Parents or Guardian") {
                $('#validationParentContacts2').show();
            } else {
                $('#validationParentContacts2').hide();
            }

            if (student_status === "") {
                $('#validationStudentStatus').show();
            } else {
                $('#validationStudentStatus').hide();
            }
            if (!name) {
                $('#validationStudentName').show();
            } else {
                $('#validationStudentName').hide();
            }

/*             if (!nric_no) {
                $('#validationNRIC').show();
            } else {
                $('#validationNRIC').hide();
            }
 */
            if (dob_year === "") {
                $('#validationDobYear').show();
            } else {
                $('#validationDobYear').hide();
            }

            if (dob_month === "") {
                $('#validationDobMonth').show();
            } else {
                $('#validationDobMonth').hide();
            }

            if (dob_day === "") {
                $('#validationDobDay').show();
            } else {
                $('#validationDobDay').hide();
            }

/*             if (!birth_cert_no) {
                $('#validationBirthCert').show();
            } else {
                $('#validationBirthCert').hide();
            } */

            if (!start_date_at_centre) {
                $('#validationStartDate').show();
            } else {
                $('#validationStartDate').hide();
            }

            if (!gender) {
                $('#validationGender').show();
            } else {
                $('#validationGender').hide();
            }

            if (!add1) {
                $('#validationAdd1').show();
            } else {
                $('#validationAdd1').hide();
            }

            if (!country) {
                $('#validationCountry').show();
            } else {
                $('#validationCountry').hide();
            }

            if (!state) {
                $('#validationState').show();
            } else {
                $('#validationState').hide();
            }

            if (!race) {
                $('#validationRace').show();
            } else {
                $('#validationRace').hide();
            }

            if (!nationality) {
                $('#validationNationality').show();
            } else {
                $('#validationNationality').hide();
            }

            if (!religion) {
                $('#validationReligion').show();
            } else {
                $('#validationReligion').hide();
            }

            if (!health_problem) {
                $('#validationHealth').show();
            } else {
                $('#validationHealth').hide();
            }

            if (!allergies) {
                $('#validationAllergies').show();
            } else {
                $('#validationAllergies').hide();
            }
            if (!accept_terms) {
                $('#validationCheckbox2').show();
            } else {
                $('#validationCheckbox2').hide();
            }
            if (signature === "" || signature === '{\"lines\":[]}') {
                $('#validationSignature').show();
				return false;
            } else {
                $('#validationSignature').hide();
            }
        }

        if (form_mode == '') {
            if (student_status == '') {
                e.preventDefault();
                // UIkit.notify("Please fill in all fields (012)");
            }
            if (student_entry_level == '') {
                e.preventDefault();
                // UIkit.notify("Please fill in all fields (012)");
            }
        } else if ((student_code != "") &&
            (centre_code != "") &&
            (name != "") &&
            (dob_year != "") &&
            (dob_month != "") &&
            (dob_day != "") && 
            (start_date_at_centre != "") &&
            (gender != "") &&
            (add1 != "") &&
            (country != "") &&
            (state != "") &&
            (race != "") &&
            (nationality != "") &&
            (religion != "") &&
            (allergies != "") &&
            (health_problem != "") &&
            (accept_terms == true) &&
            (signature != "" && signature != '{\"lines\":[]}') &&
            (fee_name != "")) {


        } else {
            e.preventDefault();
            // UIkit.notify("Please fill in all fields (01)");
        }
    }

    function getEmergencyContacts(student_code, form_mode, visitor) {
        if (student_code == "") {
            student_code = $("#student_code").val();
        }

        if (student_code != "") {
            $.ajax({
                url: "admin/get_emergency_contacts.php",
                type: "POST",
                data: "student_code=" + student_code + "&form_mode=" + form_mode + "&visitor=" + visitor +
                    "&mode=" + "<?php echo $_GET['mode']?>",
                dataType: "text",
                beforeSend: function(http) {},
                success: function(response, status, http) {
                    $("#dlgEmergencyContacts").html(response);
                    $("#dlgEmergencyContacts").dialog({
                        dialogClass: "no-close",
                        title: "Primary Contacts (Parents / Guardians)",
                        modal: true,
                        height: 'auto',
                        width: '98%',
                    });
                },
                error: function(http, status, error) {
                    UIkit.notify("Error:" + error);
                }
            });
        } else {
            UIkit.notify("Please select a student");
        }
    }

    function clearSignature() {
        $('#signature-container').signature('clear');
    }

    function readURL(input, elem) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $(elem).attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $(document).ready(function() {
        // $(".").css("border", "red solid 1px"); 
        $("#signature-container").css("border", "red solid 1px");

        // $('.').focus(function() {
        //    $(this).css("border", "");
        // });

        $("#signature-container").on('click', function(e) {
            $("#signature-container").css("border", "");
        });

        $('#add-student-photo').on('click', function(e) {
            e.preventDefault();
            $('#input-student-photo').trigger('click');
        });



        $("#dob_year, #dob_month, #dob_day").change(function() {
            var year = $("#dob_year").val();
            var month = $("#dob_month").val();
            var day = $("#dob_day").val();
            if (year == "" || month == "" || day == "") {
                $("#age").val('0');
                return;
            }

            var dob = year + '-' + '01' + '-' + '01';
            var dob = new Date(dob);
            var today = new Date();
            var age = Math.floor((today - dob) / (365.25 * 24 * 60 * 60 * 1000));
            $("#age").val(age);
        });

        $("#signature-container").signature({
            background: '#FFFFFF',
            syncField: '#signature'
        });
        if ($("#signature").val()) {
            $("#signature-container").signature('draw', $("#signature").val());
        }

        $('#attachment').bind('change', function() {
            var file_path = $(this).val();
            if (file_path != "") {
                if (this.files[0].size > 10485760) {
                    UIkit.notify("Attachment file size too big.");
                }

                var file = file_path.split(".");
                var count = file.length;
                var index = count > 0 ? (count - 1) : 0;
                var file_ext = file[index].toLowerCase();

                if ((file_ext != "docx") && (file_ext != "doc") && (file_ext != "pdf") && (file_ext !=
                        "jpg") && (file_ext != "png") && (file_ext != "jpeg")) {
                    $('#attachment').val('');
                    UIkit.notify("Please select DOC, DOCX, PDF, JPG, JPEG or PNG file only");
                }
            }
        });

        $('#btn-upload-photo').on('click', function(e) {
            e.preventDefault();
            $('#input-student-photo').trigger('click');
        })

        $('#input-student-photo').bind('change', function(e) {
            var file_path = $(this).val();
            if (file_path != "") {

                if (this.files[0].size > 10485760) {
                    UIkit.notify("Student photo file size too big.");
                }

                var file = file_path.split(".");
                var count = file.length;
                var index = count > 0 ? (count - 1) : 0;
                var file_ext = file[index].toLowerCase();

                if ((file_ext != "jpg") && (file_ext != "jpeg")) {
                    $('#input-student-photo').val('');
                    UIkit.notify("Please select JPG or JPEG file only");
                }

                readURL(this, '#add-student-photo img');
                $('#label-upload-photo').text(this.files[0].name);
            } else {
                $('#label-upload-photo').text();
            }
        });

    });
    </script>

    <script>
    function onCountryChange(country) {
        if (country != "") {
            $.ajax({
                url: "admin/get_state.php",
                type: "POST",
                data: "country=" + country,
                dataType: "text",
                beforeSend: function(http) {

                },
                success: function(response, status, http) {
                    if (response != "") {
                        $("#state").html(response);
                    } else {
                        $("#state").html(
                            "<select name='state' id='state' class='uk-width-1-1'><option value=''>Please select a country</option></select>"
                        );
                        UIkit.notify("No state found in " + country);
                    }
                },
                error: function(http, status, error) {
                    UIkit.notification("Error:" + error);
                }
            });

            $.ajax({
                url: "admin/get_race.php",
                type: "POST",
                data: "country=" + country,
                dataType: "text",
                beforeSend: function(http) {

                },
                success: function(response, status, http) {
                    if (response != "") {
                        $("#race").html(response);
                    } else {
                        $("#race").html(
                            "<select name='race' id='race' class='uk-width-1-1'><option value=''>Please select a country</option></select>"
                        );
                        UIkit.notify("No ethnicity found in " + country);
                    }
                },
                error: function(http, status, error) {
                    UIkit.notification("Error:" + error);
                }
            });

            $.ajax({
                url: "admin/get_nationality.php",
                type: "POST",
                data: "country=" + country,
                dataType: "text",
                beforeSend: function(http) {

                },
                success: function(response, status, http) {
                    if (response != "") {
                        $("#nationality").html(response);
                    } else {
                        $("#nationality").html(
                            "<select name='nationality' id='nationality' class='uk-width-1-1'><option value=''>Please select a country</option></select>"
                        );
                        UIkit.notify("No nationality found in " + country);
                    }
                },
                error: function(http, status, error) {
                    UIkit.notification("Error:" + error);
                }
            });
        } //country
    }

    $(document).ready(function() {
        $("#country").change(function() {
            var country = $("#country").val();
            onCountryChange(country);
        });

        //onCountryChange($("#country").val());
    });
    </script>

    <script type="text/javascript">
    $("#frmStudent").submit(function(e) {
        var student_code = $("#student_code").val();
        var centre_code = $("#centre_code").val();
        var student_status = $("#student_status").val();
        var name = $("#name").val();
        var nric_no = $("#nric_no").val();
        var dob_year = $("#dob_year").val();
        var dob_month = $("#dob_month").val();
        var dob_day = $("#dob_day").val();
        var birth_cert_no = $("#birth_cert_no").val();
        var start_date_at_centre = $("#start_date_at_centre").val();
        var gender = $("#gender").val();
        var add1 = $("#add1").val();
        var country = $("#country").val();
        var state = $("#state").val();
        var race = $("#race").val();
        var nationality = $("#nationality").val();
        var religion = $("#religion").val();
        var accept_photo = $("#accept_photo").prop('checked');
        var accept_terms = $("#accept_terms").prop('checked');
        var attachment = $("#attachment").val();
        var signature = $("#signature").val();
        var form_mode = $("#form_mode").val();
        var allergies = $("#allergies").val();
        var health_problem = $("#health_problem").val();
        var primary_contact1 = $("#primary-contact1").text();
        var primary_contact2 = $("#primary-contact2").text();
        var student_entry_level = $("#student_entry_level").val();
        //alert(student_entry_level);
        var foundation_mandarin = $("#foundation_mandarin").val();
        var programme_duration = $("#programme_duration").val();
        var afternoon_programme = $("#afternoon_programme").val();
        var programme_date = $("#programme_date").val();
        var student_status = $("#student_status").val();
        var fee_name = $("#fee_id").val();
		
        //console.log(primary_contact1);
        
        //console.log(primary_contact2);
        var error = false;
        if (primary_contact2 === "Primary contact eg: Parents or Guardian") {
            $('#validationParentContacts2').show();
            error = true;
        } else {
            $('#validationParentContacts2').hide();
        }

        if (student_status === "") {
            $('#validationStudentStatus').show();
            error = true;
        } else {
            $('#validationStudentStatus').hide();
        }
        if (!name) {
            $('#validationStudentName').show();
            error = true;
        } else {
            $('#validationStudentName').hide();
        }

/*         if (!nric_no) {
            $('#validationNRIC').show();
            error = true;
        } else {
            $('#validationNRIC').hide();
        } */

        if (dob_year === "") {
            $('#validationDobYear').show();
            error = true;
        } else {
            $('#validationDobYear').hide();
        }

        if (dob_month === "") {
            $('#validationDobMonth').show();
            error = true;
        } else {
            $('#validationDobMonth').hide();
        }

        if (dob_day === "") {
            $('#validationDobDay').show();
            error = true;
        } else {
            $('#validationDobDay').hide();
        }

/*         if (!birth_cert_no) {
            $('#validationBirthCert').show();
            error = true;
        } else {
            $('#validationBirthCert').hide();
        } */

        if (!start_date_at_centre) {
            $('#validationStartDate').show();
            error = true;
        } else {
            $('#validationStartDate').hide();
        }

        if (!gender) {
            $('#validationGender').show();
            error = true;
        } else {
            $('#validationGender').hide();
        }

        if (!add1) {
            $('#validationAdd1').show();
            error = true;
        } else {
            $('#validationAdd1').hide();
        }

        if (!country) {
            $('#validationCountry').show();
            error = true;
        } else {
            $('#validationCountry').hide();
        }

        if (!state) {
            $('#validationState').show();
            error = true;
        } else {
            $('#validationState').hide();
        }

        if (!race) {
            $('#validationRace').show();
            error = true;
        } else {
            $('#validationRace').hide();
        }

        if (!nationality) {
            $('#validationNationality').show();
            error = true;
        } else {
            $('#validationNationality').hide();
        }

        if (!religion) {
            $('#validationReligion').show();
            error = true;
        } else {
            $('#validationReligion').hide();
        }

        if (!health_problem) {
            $('#validationHealth').show();
            error = true;
        } else {
            $('#validationHealth').hide();
        }

        if (!allergies) {
            $('#validationAllergies').show();
            error = true;
        } else {
            $('#validationAllergies').hide();
        }



        if (!accept_terms) {
            $('#validationCheckbox2').show();
            error = true;
        } else {
            $('#validationCheckbox2').hide();
        }



        if (signature === "" || signature === '{\"lines\":[]}') {
            $('#validationSignature').show();
            if (signature === '{\"lines\":[]}') {
                error = true;
            }
        } else {
            $('#validationSignature').hide();
        }
		if (primary_contact1 === "Primary contact eg: Parents or Guardian") {
            $('#validationParentContacts').show();
            error = true;
        } else {
            $('#validationParentContacts').hide();
        }

        if(error){
            return false;
        }
        

        <?php

        if (isset($_GET["mode"])) {
            ?>
            var mode = 'EDIT';
	<?php } else { ?>
            var mode = ''; 
			<?php } ?>

        // if (student_status != 'A') {
        //   return window.confirm("Are you sure to change the student status due cant undo selection?");
        // }
        if (mode == 'EDIT') {
            return window.confirm("Are you sure to change the student details?");
        }

        // $(".").each(function() {
        //   var $this = $(this);
        //   var value = $this.val();
        //   $this.removeClass("requiredfield");
        //   if (value.length == 0) {
        //     $this.addClass("requiredfield");
        //     $this.css("border", "red solid 1px");
        //   }
        // });

        // $("#signature-container").removeClass("requiredfield");
        // if (signature.length < 13) {
        //   $("#signature-container").addClass("requiredfield");
        //   $("#signature-container").css("border", "red solid 1px");  
        // }

        // if ($(".requiredfield").length > 0){
        //      e.preventDefault();
        //     UIkit.notify("Please fill in all fields (012)");
        // }

        if (form_mode != '') {
            var student_code = $("#student_code").val();
            checkContacts(student_code, form_mode);
            var contact_check = $("#contact_check").val();

            if (contact_check != "1") {
                e.preventDefault();
                // UIkit.notify("Please fill in at least 1 primary contact");
            }
        }

        // $("#signature-container").onclick(function() {
        //   $(this).removeClass("requiredfield");
        // })

        // $(".").focus(function() {
        //   $(this).removeClass("requiredfield");
        // })
        isDataOK(e);
    });
	
	getFee();
	
    $(".subject_3").change(function() {
        getFee();
        });
		
		var subject = $("#student_entry_level").val();
        var programme_package = $("#programme_duration").val();
        $.ajax({
            url: "admin/get_feev_settingalue.php",
            type: "POST",
            data: "subject=" + subject + "&programme_package=" + programme_package,
            dataType: "json",
            beforeSend: function(http) {},
            success: function(response, status, http) {
                // console.log(response.programme_package);
                if (response != "" && response != null) {
                    // $("#school_adjust").val(response.school_adjust);
                    // $("#school_collection").val(response.school_collection);
                    // $("#multimedia_adjust").val(response.multimedia_adjust);
                    // $("#multimedia_collection").val(response.multimedia_collection);
                    // $("#facility_adjust").val(response.facility_adjust);
                    // $("#facility_collection").val(response.facility_collection);
                    // $("#enhanced_adjust").val(response.enhanced_adjust);
                    // $("#enhanced_collection").val(response.enhanced_collection);
                    // $("#school_total_d").val(response.school_total_d);
                    // $("#iq_math_adjust").val(response.iq_math_adjust);
                    // $("#iq_math_collection").val(response.iq_math_collection);
                    // $("#mandarin_adjust").val(response.mandarin_adjust);
                    // $("#mandarin_collection").val(response.mandarin_collection);
                    // $("#international_adjust").val(response.international_adjust);
                    // $("#international_collection").val(response.international_perent);
                    // $("#total_adjust_c").val(response.total_adjust_c);
                    // $("#integrated_adjust").val(response.integrated_adjust);
                    // $("#integrated_collection").val(response.integrated_collection);
                    // $("#link_adjust").val(response.link_adjust);
                    // $("#link_collection").val(response.link_collection);
                    // $("#mandarin_m_adjust").val(response.mandarin_m_adjust);
                    // $("#mandarin_m_collection").val(response.mandarin_m_collection);
                    // $("#total_adjust_d").val(response.total_adjust_d);
                    // $("#basic_adjust").val(response.basic_adjust);
                    // $("#basic_collection").val(response.basic_collection);
                    // $("#mobile_adjust").val(response.mobile_adjust);
                    // $("#mobile_collection").val(response.mobile_collection);
                    // $("#registration_adjust").val(response.registration_adjust);
                    // $("#insurance_adjust").val(response.insurance_adjust);
                    // $("#uniform_adjust").val(response.uniform_adjust);
                    // $("#gymwear_adjust").val(response.gymwear_adjust);
                    // $("#q_dees_adjust").val(response.q_dees_adjust);
                    // $("#q_bag_adjust").val(response.q_bag_adjust);
                    // $("#total_adjust_f").val(response.total_adjust_f);
                    // $("#islam_adjust").val(response.islam_adjust);
                } else {
                    // $("#school_adjust").val(response.school_adjust);
                    // $("#school_collection").val(response.school_collection);
                    // $("#multimedia_adjust").val(response.multimedia_adjust);
                    // $("#multimedia_collection").val(response.multimedia_collection);
                    // $("#facility_adjust").val(response.facility_adjust);
                    // $("#facility_collection").val(response.facility_collection);
                    // $("#enhanced_adjust").val(response.enhanced_adjust);
                    // $("#enhanced_collection").val(response.enhanced_collection);
                    // $("#school_total_d").val(response.school_total_d);
                    // $("#iq_math_adjust").val(response.iq_math_adjust);
                    // $("#iq_math_collection").val(response.iq_math_collection);
                    // $("#mandarin_adjust").val(response.mandarin_adjust);
                    // $("#mandarin_collection").val(response.mandarin_collection);
                    // $("#international_adjust").val(response.international_adjust);
                    // $("#international_collection").val(response.international_perent);
                    // $("#total_adjust_c").val(response.total_adjust_c);
                    // $("#integrated_adjust").val(response.integrated_adjust);
                    // $("#integrated_collection").val(response.integrated_collection);
                    // $("#link_adjust").val(response.link_adjust);
                    // $("#link_collection").val(response.link_collection);
                    // $("#mandarin_m_adjust").val(response.mandarin_m_adjust);
                    // $("#mandarin_m_collection").val(response.mandarin_m_collection);
                    // $("#total_adjust_d").val(response.total_adjust_d);
                    // $("#basic_adjust").val(response.basic_adjust);
                    // $("#basic_collection").val(response.basic_collection);
                    // $("#mobile_adjust").val(response.mobile_adjust);
                    // $("#mobile_collection").val(response.mobile_collection);
                    // $("#registration_adjust").val(response.registration_adjust);
                    // $("#insurance_adjust").val(response.insurance_adjust);
                    // $("#uniform_adjust").val(response.uniform_adjust);
                    // $("#gymwear_adjust").val(response.gymwear_adjust);
                    // $("#q_dees_adjust").val(response.q_dees_adjust);
                    // $("#q_bag_adjust").val(response.q_bag_adjust);
                    // $("#total_adjust_f").val(response.total_adjust_f);
                    // $("#islam_adjust").val(response.islam_adjust);
                }
            },
            error: function(http, status, error) {
                //UIkit.notify("Error:"+error);
                $("#school_adjust").val(response.school_adjust);
                $("#school_collection").val(response.school_collection);
                $("#multimedia_adjust").val(response.multimedia_adjust);
                $("#multimedia_collection").val(response.multimedia_collection);
                $("#facility_adjust").val(response.facility_adjust);
                $("#facility_collection").val(response.facility_collection);
                $("#enhanced_adjust").val(response.enhanced_adjust);
                $("#enhanced_collection").val(response.enhanced_collection);
                $("#school_total_d").val(response.school_total_d);
                $("#iq_math_adjust").val(response.iq_math_adjust);
                $("#iq_math_collection").val(response.iq_math_collection);
                $("#mandarin_adjust").val(response.mandarin_adjust);
                $("#mandarin_collection").val(response.mandarin_collection);
                $("#international_adjust").val(response.international_adjust);
                $("#international_collection").val(response.international_perent);
                $("#total_adjust_c").val(response.total_adjust_c);
                $("#integrated_adjust").val(response.integrated_adjust);
                $("#integrated_collection").val(response.integrated_collection);
                $("#link_adjust").val(response.link_adjust);
                $("#link_collection").val(response.link_collection);
                $("#mandarin_m_adjust").val(response.mandarin_m_adjust);
                $("#mandarin_m_collection").val(response.mandarin_m_collection); 
                $("#total_adjust_d").val(response.total_adjust_d);
                $("#basic_adjust").val(response.basic_adjust);
                $("#basic_collection").val(response.basic_collection);
                $("#mobile_adjust").val(response.mobile_adjust);
                $("#mobile_collection").val(response.mobile_collection);
                $("#registration_adjust").val(response.registration_adjust);
                $("#insurance_adjust").val(response.insurance_adjust);
                $("#uniform_adjust").val(response.uniform_adjust);
                $("#gymwear_adjust").val(response.gymwear_adjust);
                $("#q_dees_adjust").val(response.q_dees_adjust);
                $("#q_bag_adjust").val(response.q_bag_adjust);
                $("#total_adjust_f").val(response.total_adjust_f);
                $("#islam_adjust").val(response.islam_adjust);
            }
        });

    </script>