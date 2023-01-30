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

<div class="uk-text-center" style="background-color: #fff; padding: 10px;">
  <h4 class="uk-margin-remove"><b>Centre Code:</b> <?php echo htmlspecialchars($centre_code); ?></h4>
</div>

<?php if (IS_ADMIN_STUDENT_FORM) { ?>
    <form name="frmStudent" id="frmStudent" method="post" class="uk-form uk-form-small" action="<?php echo $form_post_url; ?>" enctype="multipart/form-data">
      <input type="hidden" name="centre_code" id="centre_code" value="<?php echo $centre_code; ?>">
      <input type="hidden" name="hidden_student_code" id="hidden_student_code" value="<?php echo $temp_student_code; ?>">
      <input type="hidden" name="form_mode" id="form_mode" value="">
<?php } else { ?>
    <div style="max-width: 90%; margin: 0 auto;">
      <form name="frmStudent" id="frmStudent" method="post" class="uk-form uk-form-small" action="<?php echo $form_post_url; ?>" enctype="multipart/form-data">
        <input type="hidden" name="centre_code" id="centre_code" value="<?php echo $centre_code; ?>">
        <input type="hidden" name="student_code" id="student_code" value="<?php echo $temp_student_code; ?>">
        <input type="hidden" name="hidden_student_code" id="hidden_student_code" value="<?php echo $temp_student_code; ?>">
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
              <input id="input-student-photo" type="file"  accept=".jpg, .jpeg" name="image" style="display: none; margin-top: 10px">
              <button id="btn-upload-photo" class="uk-button" style="margin-top: 1rem;">Choose File</button>
              <div id="label-upload-photo" style="text-overflow: ellipsis; white-space: nowrap; overflow: hidden;"></div>
              <div style="color: red; margin: 1rem 0;">Upload .jpg file only (Less Than 10MB)</div>
            </div>

            <div class="uk-width-large-6-10">
              <?php if (IS_ADMIN_STUDENT_FORM) { ?>
                <div class="uk-flex uk-flex-column">
                  <div class="uk-width-1-1 uk-text-bold">Student Code <span class="text-danger">*</span>:</div>
                  <div class="uk-width-1-1">
                     <input class="uk-width-1-1" type="text" name="student_code" id="student_code" value="<?php echo $temp_student_code; ?>" readonly>
                  </div>
                </div>

                <div class="uk-flex uk-flex-column" style="margin-top: 10px">
                  <div class="uk-width-1-1 uk-text-bold">Student Status<span class="text-danger">*</span>:</div>
                  <div class="uk-width-1-1">
                    <?php
                    $fields=array("A"=>"Active", "D"=>"Deferred", "G"=>"Graduated", "I"=>"Dropout", "S"=>"Suspended", "T"=>"Transferred");
                    generateSelectArray($fields, "student_status", "class='uk-width-1-1'", $data_array["student_status"]);
                    ?>
                  </div>
                </div>

                <div class="uk-flex uk-flex-column" style="margin-top: 10px">
                  <div class="uk-width-1-1 uk-text-bold">Student's Full Name<span class="text-danger">*</span>: </div>
                  <div class="uk-width-1-1">
                    <input class="uk-width-1-1" type="text" name="name" id="name" value="<?php echo $data_array['name']?>">
                  </div>
                </div>

                <div class="uk-flex uk-flex-column" style="margin-top: 10px">
                   <div class="uk-width-1-1 uk-text-bold">Start Date at Centre<span class="text-danger">*</span>:</div>
                   <div class="uk-width-1-1">
                     <input class="uk-width-1-1" type="text" data-uk-datepicker="{format: 'DD/MM/YYYY'}" name="start_date_at_centre" id="start_date_at_centre" value="<?php echo convertDate2British($data_array['start_date_at_centre']); ?>" >
                   </div>
                </div>

                <div class="uk-flex uk-flex-column" style="margin-top: 10px">
                   <div class="uk-width-1-1 uk-text-bold">Form Serial No.:</div>
                   <div class="uk-width-1-1">
                      <input class="uk-width-1-1" type="text" name="form_serial_no" id="form_serial_no" value="<?php echo $data_array['form_serial_no']?>">
                   </div>
                </div>
              <?php } ?>

            </div>
          </div><!--/.uk-grid-->

          <hr>

          <h3><b>Parent's / Guardian Contact</b></h3>
          <table class="uk-table uk-table-small">
            <tr class="uk-text-small">
             <td class="uk-width-4-10 uk-text-bold">Parent's/Guardian's Contacts<span class="text-danger">*</span>: </td>
              <?php if (IS_ADMIN_STUDENT_FORM) { ?>
                <td><a id="primary-contact" onclick="getEmergencyContacts('<?php echo $temp_student_code?>', '')" class="uk-button uk-width-1-1"><?php echo !empty($row_contact['full_name']) ? $row_contact['full_name'] : 'Primary contact eg: Parents or Guardian'; ?></a></td>
              <?php } else { ?>
                <td><a id="primary-contact" onclick="getEmergencyContacts('<?php echo $temp_student_code?>', 'qr')" class="uk-button uk-width-1-1"><?php echo !empty($row_contact['full_name']) ? $row_contact['full_name'] : 'Primary contact eg: Parents or Guardian'; ?></a></td>
              <?php } ?>
            </tr>
          </table>

          <h3><b>Student's Detail</b></h3>

          <table class="uk-table uk-table-small">

            <?php if (IS_PUBLIC_STUDENT_FORM) { ?>
              <tr class="uk-text-small">
                <td class="uk-width-4-10 uk-text-bold">Student's Full Name<span class="text-danger">*</span>:</td>
                <td class="uk-width-6-10">
                  <input class="uk-width-1-1" type="text" name="name" id="name" value="<?php echo $data_array['name']?>">
                </td>
              </tr>

              <tr class="uk-text-small">
                 <td class="uk-width-4-10 uk-text-bold">Start Date at Centre<span class="text-danger">*</span>:</td>
                 <td class="uk-width-6-10">
                   <input class="uk-width-1-1" type="text" data-uk-datepicker="{format: 'DD/MM/YYYY'}" name="start_date_at_centre" id="start_date_at_centre" value="<?php echo convertDate2British($data_array['start_date_at_centre']); ?>" >
                 </td>
              </tr>
            <?php } ?>

            <tr class="uk-text-small">
              <td class="uk-width-4-10 uk-text-bold">MyKid / Passport No.<span class="text-danger">*</span>:</td>
              <td class="uk-width-6-10"><input class="uk-width-1-1" type="text" name="nric_no" id="nric_no" value="<?php echo $data_array['nric_no']?>"></td>
            </tr>

           <tr class="uk-text-small">
             <td class="uk-width-4-10 uk-text-bold">Date of Birth<span class="text-danger">*</span>:</td>
             <td class="uk-width-6-10">
               <div class="uk-grid-small uk-grid">
                 <div class="uk-width-1-3" style="padding-right:0px">
                   <select id="dob_day" name="dob_day" class="uk-width-1-1" >
                      <option value="no_selected">-- Day --</option>
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
                 </div>

                 <div class="uk-width-1-3" style="padding-right:0px">
                   <select id="dob_month" name="dob_month" class="uk-width-1-1" >
                      <option value="no_selected">-- Month --</option>
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
                 </div>

                 <div class="uk-width-1-3" style="padding-right:0px">
                   <select id="dob_year" name="dob_year" class="uk-width-1-1" >
                      <option value="no_selected">-- Year --</option>
                      <?php
                      if( !empty($data_array['dob']) ){
                        $tmp_year = $tmp_dob->format('Y');
                      } elseif (!empty($data_array['dob_year'])) {
                        $tmp_year = $data_array['dob_year'];
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
                      ?>
                       <option<?php echo ($tmp_selected ? ' selected' : ''); ?>><?php echo $i?></option>
                       <?php }//for ?>
                     </select>
                  </div><!--/.uk-width-1-3-->
                </div>
               </td>
             </tr>

             <tr class="uk-text-small">
                <td class="uk-width-4-10 uk-text-bold">Age : </td>
                <td class="uk-width-6-10"><input class="uk-width-1-1" type="text" name="age" id="age" value="<?php echo $data_array['age']?>" readonly></td>
             </tr>

             <tr class="uk-text-small">
                <td class="uk-width-4-10 uk-text-bold">Birth Cert. No.<span class="text-danger">*</span>:</td>
                <td class="uk-width-6-10"><input class="uk-width-1-1" type="text" name="birth_cert_no" id="birth_cert_no" value="<?php echo $data_array['birth_cert_no']?>" ></td>
             </tr>

               <tr class="uk-text-small">
                  <td class="uk-width-4-10 uk-text-bold">Gender<span class="text-danger">*</span>:</td>
                  <td class="uk-width-6-10">
                    <?php
                    $fields=array("F"=>"Female", "M"=>"Male", "O"=>"Others");
                    generateSelectArray($fields, "gender", "class='uk-width-1-1'", $data_array["gender"]);
                    ?>
                  </td>
               </tr>
             </table>

             <h3><b>Student's Address</b></h3>
             <table class="uk-table uk-table-small">
               <tr class="uk-text-small">
                  <td class="uk-width-4-10 uk-text-bold">Address<span class="text-danger">*</span>:</td>
                  <td class="uk-width-6-10">
                     <div style="margin-top: 5px"><input class="uk-width-1-1" type="text" name="add1" id="add1" value="<?php echo $data_array['add1']?>" placeholder="Address Line 1"></div>
                     <div style="margin-top: 5px"><input class="uk-width-1-1" type="text" name="add2" id="add1" value="<?php echo $data_array['add2']?>" placeholder="Address Line 2"></div>
                     <div style="margin-top: 5px"><input class="uk-width-1-1" type="text" name="add3" id="add1" value="<?php echo $data_array['add3']?>" placeholder="Address Line 3"></div>
                     <div style="margin-top: 5px"><input class="uk-width-1-1" type="text" name="add4" id="add1" value="<?php echo $data_array['add4']?>" placeholder="Address Line 4"></div>
                  </td>
               </tr>

                <tr class="uk-text-small">
                   <td class="uk-width-3-10 uk-text-bold">Country<span class="text-danger">*</span>:</td>
                   <td class="uk-width-7-10">
                      <select name="country" id="country" class="uk-width-1-1" >
                         <option value="">Select</option>
                       <?php
                       $sql="SELECT * from codes where module='COUNTRY' order by code";
                       $result=mysqli_query($connection, $sql);
                       while ($row=mysqli_fetch_assoc($result)) {
                       ?>
                         <option value="<?php echo $row["code"]?>" <?php if ($row["code"]==$data_array["country"]) {echo "selected";}?>><?php echo $row["code"]?></option>
                        <?php } ?>
                      </select>
                   </td>
                </tr>

                <tr class="uk-text-small">
                   <td class="uk-width-3-10 uk-text-bold">State<span class="text-danger">*</span>: </td>
                   <td class="uk-width-7-10">
                      <select name="state" id="state" class="uk-width-1-1" >
                        <?php if ($data_array["country"]!="") { ?>
                         <option value="">Select</option>
                         <?php
                            $sql="SELECT * from codes where country='".$data_array["country"]."' and module='STATE' order by code";
                            $result=mysqli_query($connection, $sql);
                            while ($row=mysqli_fetch_assoc($result)) {
                         ?>
                         <option value="<?php echo $row['code']?>" <?php if ($row["code"]==$data_array['state']) {echo "selected";}?>><?php echo $row["code"]?></option>
                         <?php
                            }
                         } else {
                         ?>
                         <option value="">Please Select Country First</option>
                         <?php } ?>
                      </select>
                   </td>
                </tr>

            </table>
         </div><!--/.uk-width-large-5-10-->

         <div class="uk-width-large-5-10">
            <h3><b>Other Information</b></h3>
            <table class="uk-table uk-table-small">

               <tr class="uk-text-small">
                  <td class="uk-width-4-10 uk-text-bold">Ethnicity<span class="text-danger">*</span>: </td>
                  <td class="uk-width-6-10">
                     <select name="race" id="race" class="uk-width-1-1" >
                        <?php if ($data_array["country"]!="") { ?>
                        <option value="">Select</option>
                        <?php
                           $sql="SELECT * from codes where country='".$data_array["country"]."' and module='RACE' order by code";
                           $result=mysqli_query($connection, $sql);
                           while ($row=mysqli_fetch_assoc($result)) {
                        ?>
                        <option value="<?php echo $row['code']?>" <?php if ($row["code"]==$data_array['race']) {echo "selected";}?>><?php echo $row["code"]?></option>
                        <?php } ?>
                        <option value="Others" <?php if ($data_array['race']=="Others") {echo "selected";}?>>Others</option>
                        <?php } else { ?>
                        <option value="">Please Select Country First</option>
                        <?php } ?>
                     </select>
                  </td>
               </tr>

               <tr class="uk-text-small">
                  <td class="uk-width-4-10 uk-text-bold">Nationality<span class="text-danger">*</span>:</td>
                  <td class="uk-width-6-10">
                     <select name="nationality" id="nationality" class="uk-width-1-1" >
                        <?php if ($data_array["country"]!="") { ?>
                        <option value="">Select</option>
                        <?php
                           $sql="SELECT * from codes where country='".$data_array["country"]."' and module='NATIONALITY' order by code";
                           $result=mysqli_query($connection, $sql);
                           while ($row=mysqli_fetch_assoc($result)) {
                        ?>
                        <option value="<?php echo $row['code']?>" <?php if ($row["code"]==$data_array['nationality']) {echo "selected";}?>><?php echo $row["code"]?></option>
                        <?php } ?>
                        <option value="Others" <?php if ($data_array['nationality']=="Others") {echo "selected";}?>>Others</option>
                        <?php } else { ?>
                        <option value="">Please Select Country First</option>
                        <?php } ?>
                     </select>
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-4-10 uk-text-bold">Religion<span class="text-danger">*</span>:</td>
                  <td class="uk-width-6-10">
                  <?php
                  $sql="SELECT * from codes where module='religion' order by code";
                  generateSelect($sql, "code", "code", "religion", "class='uk-width-1-1'", $data_array["religion"]);
                  ?>
                  </td>
               </tr>

               <tr class="uk-text-small">
                  <td class="uk-width-4-10 uk-text-bold">Medical Condition<span class="text-danger">*</span>:</td>
                  <td class="uk-width-6-10"><input class="uk-width-1-1" type="text" required name="health_problem" id="health_problem" value="<?php echo $data_array['health_problem']?>"></td>
               </tr>

               <tr class="uk-text-small">
                  <td class="uk-width-4-10 uk-text-bold">Allergies<span class="text-danger">*</span>:</td>
                  <td class="uk-width-6-10"><input class="uk-width-1-1" type="text" required name="allergies" id="allergies" value="<?php echo $data_array['allergies']?>"></td>
               </tr>

               <tr class="uk-text-small">
                  <td class="uk-width-4-10 uk-text-bold">Remarks : </td>
                  <td class="uk-width-6-10"><textarea class="uk-width-1-1" rows="5" name="remarks" id="remarks"><?php echo $data_array["remarks"]?></textarea></td>
               </tr>

               <tr>
                 <?php if( $data_array['attachment'] ){ ?>
                  <td><label class="uk-text-bold">Replace Attachment</label></td>
                 <?php } else { ?>
                  <td><label class="uk-text-bold">Upload Attachment</label></td>
                 <?php } ?>
                  <td>
                    <?php if( $data_array['attachment'] ){ ?>
                      <?php echo '<div style="margin-bottom: 1rem;"><a href="' . STUDENT_ATTACHMENT_URL . $data_array['attachment'] . '" target="_blank">' . shortenAttachmentName($data_array['attachment']) . '</a></div>'; ?>
                    <?php } ?>
                    <div><input type="file" name="attachment" id="attachment" accept=".doc, .docx, .pdf, .png, .jpg, .jpeg"></div>
                    <div style="color: red; margin: 1rem 0 0;">Upload .jpg /.png / .docx / .doc /.pdf file only (Less Than 10MB)</div>
                  </td>
               </tr>

               <tr class="uk-text-bold">
                 <td colspan="2"><input type="checkbox" name="accept_photo" id="accept_photo" value="1" <?php echo ($data_array['accept_photo'] == 1 ? ' checked ' : ''); ?>> Accept <a href="/doc/qdees_consent_letter_for_photography_video_social_networking_r1.pdf" target="_blank">Consent for Photography, Video &amp; Social Networking</a></td>
               </tr>

               <tr class="uk-text-bold">
                 <td colspan="2"><input type="checkbox" name="accept_terms" id="accept_terms" value="1"  <?php echo ($data_array['accept_terms'] == 1 ? ' checked ' : ''); ?>> Accept <a href="/doc/qdees_terms_and_conditions.pdf" target="_blank">Terms and Conditions</a> *</td>
               </tr>

               <tr>
                 <td colspan="2">
                   <label class="uk-text-bold">Please sign below<span class="text-danger">*</span>:</label>
                   <input type="hidden" id="signature" name="signature" value='<?php echo $data_array['signature']?>'>
                   <div id="signature-container"></div>
                   <a class="uk-button mt-2" onclick="clearSignature()">Clear Signature</a>
                 </td>
               </tr>

               <tr>
                 <td colspan="2">
                   <button type="submit" class="uk-button uk-button-primary form_btn">Submit</button>
                 </td>
               </tr>
            </table>
         </div><!--/.uk-width-large-5-10-->
      </div><!--/.uk-grid-->

      <?php if (IS_ADMIN_STUDENT_FORM == false) { ?>
      </div><!--/.container-->
      <?php } ?>
   </form>


<div id="dlgEmergencyContacts"></div>

<style>
  #the_grid {
      max-width: 100%;
  }
  .kbw-signature { width: 100%; height: 150px; border:1px solid #999}
</style>
<script src="../lib/sign/js/jquery.signature.js"></script>
<script type="text/javascript" src="../lib/sign/js/jquery.ui.touch-punch.min.js"></script>

<script>
function checkContacts(student_code, form_mode) {
   $.ajax({
      url : "admin/check_contacts.php",
      type : "POST",
      data : "student_code="+student_code+"&form_mode="+form_mode,
      dataType : "text",
      beforeSend : function(http) {
      },
      async: false,
      success : function(response, status, http) {
         if (response=="OK") {
            $("#contact_check").val("1");
         } else {
            $("#contact_check").val("0");
         }
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}

function isDataOK(e) {
   var student_code=$("#student_code").val();
   var centre_code=$("#centre_code").val();
   var student_status=$("#student_status").val();
   var name=$("#name").val();
   var nric_no=$("#nric_no").val();
   var dob_year=$("#dob_year").val();
   var dob_month=$("#dob_month").val();
   var dob_day=$("#dob_day").val();
   var birth_cert_no=$("#birth_cert_no").val();
   var start_date_at_centre=$("#start_date_at_centre").val();
   var gender=$("#gender").val();
   var add1=$("#add1").val();
   var country=$("#country").val();
   var state=$("#state").val();
   var race=$("#race").val();
   var nationality=$("#nationality").val();
   var religion=$("#religion").val();
   var accept_photo=$("#accept_photo").prop('checked');
   var accept_terms=$("#accept_terms").prop('checked');
   var attachment=$("#attachment").val();
   var signature=$("#signature").val();
   var form_mode=$("#form_mode").val();
   

   if (form_mode == '') {
     if (student_status == '') {
       e.preventDefault();
       UIkit.notify("Please fill in all fields (012)");
     }
   } else if ((student_code!="")
      && (centre_code!="")
      && (name!="")
      && (nric_no!="")
      && (dob_year!="")
      && (dob_month!="")
      && (dob_day!="")
      && (birth_cert_no!="")
      && (start_date_at_centre!="")
      && (gender!="")
      && (add1!="")
      && (country!="")
      && (state!="")
      && (race!="")
      && (nationality!="")
      && (religion!="")
      && (accept_terms==true)
      && (signature!="" && signature!='{\"lines\":[]}')) {

      checkContacts(student_code, form_mode);
      var contact_check=$("#contact_check").val();

      if (contact_check != "1") {
         e.preventDefault();
         UIkit.notify("Please fill in at least 1 primary contact");
      }
   } else {
      e.preventDefault();
      UIkit.notify("Please fill in all fields (01)");
   }
}

function getEmergencyContacts(student_code, form_mode) {
   if (student_code=="") {
      student_code=$("#student_code").val();
   }

   if (student_code!="") {
      $.ajax({
         url : "admin/get_emergency_contacts.php",
         type : "POST",
         data : "student_code="+student_code+"&form_mode="+form_mode,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            $("#dlgEmergencyContacts").html(response);
            $("#dlgEmergencyContacts").dialog({
               dialogClass:"no-close",
               title:"Primary Contacts (Parents / Guardians)",
               modal:true,
               height:'auto',
               width:'98%',
            });
         },
         error : function(http, status, error) {
            UIkit.notify("Error:"+error);
         }
      });
   } else {
      UIkit.notify("Please select a student");
   }
}

function clearSignature(){
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

$(document).ready(function () {
   $('#add-student-photo').on('click', function(e){
      e.preventDefault();
      $('#input-student-photo').trigger('click');
   });

   $("#frmStudent").submit(function (e) {
      var allergies=$("#allergies").val();
      var health_problem=$("#health_problem").val();
      if(allergies && health_problem){
        isDataOK(e);  
      }else{
        UIkit.notify("Please fill in all fields with test");
      }
      
   });

   $("#dob_year, #dob_month, #dob_day").change(function () {
        var year  =$("#dob_year").val();
        var month =$("#dob_month").val();
        var day   =$("#dob_day").val();
        if(year == "no_selected" || month == "no_selected" || day == "no_selected"){
           $("#age").val('0');
           return ;
        }
        var dob   = year + '-' + '01' + '-' + '01';
        dob       = new Date(dob);
        var today = new Date();
        var age   = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
        $("#age").val(age);
   });

   $("#signature-container").signature({background:'#FFFFFF', syncField: '#signature'});
   if ($("#signature").val()) {
     $("#signature-container").signature('draw', $("#signature").val());
   }

   $('#attachment').bind('change', function() {
    var file_path = $(this).val();
    if (file_path!="") {
      if (this.files[0].size > 10485760) {
        UIkit.notify("Attachment file size too big.");
      }

      var file = file_path.split(".");
      var count = file.length;
      var index = count > 0 ? (count-1) : 0;
      var file_ext = file[index].toLowerCase();

       if ((file_ext!="docx") && (file_ext!="doc") && (file_ext!="pdf") && (file_ext!="jpg") && (file_ext!="png") && (file_ext!="jpeg")) {
          $('#attachment').val('');
          UIkit.notify("Please select DOC, DOCX, PDF, JPG, JPEG or PNG file only");
       }
    }
  });

  $('#btn-upload-photo').on('click', function(e){
    e.preventDefault();
    $('#input-student-photo').trigger('click');
  })

  $('#input-student-photo').bind('change', function(e) {
    var file_path = $(this).val();
    if (file_path!="") {

      if (this.files[0].size > 10485760) {
       UIkit.notify("Student photo file size too big.");
      }

      var file = file_path.split(".");
      var count = file.length;
      var index = count > 0 ? (count-1) : 0;
      var file_ext = file[index].toLowerCase();

      if ((file_ext!="jpg") && (file_ext!="jpeg")) {
         $('#input-student-photo').val('');
         UIkit.notify("Please select JPG or JPEG file only");
      }
		
		readURL(this, '#add-student-photo img');
      $('#label-upload-photo').text(this.files[0].name);
    }else{
      $('#label-upload-photo').text();
    }
  });

});
</script>

<script>
  function onCountryChange(country) {
    if (country!="") {
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
             $("#state").html("<select name='state' id='state' class='uk-width-1-1'><option value=''>Please select a country</option></select>");
             UIkit.notify("No state found in "+country);
          }
        },
        error : function(http, status, error) {
          UIkit.notification("Error:"+error);
        }
      });

      $.ajax({
        url : "admin/get_race.php",
        type : "POST",
        data : "country="+country,
        dataType : "text",
        beforeSend : function(http) {

        },
        success : function(response, status, http) {
          if (response!="") {
             $("#race").html(response);
          } else {
             $("#race").html("<select name='race' id='race' class='uk-width-1-1'><option value=''>Please select a country</option></select>");
             UIkit.notify("No ethnicity found in "+country);
          }
        },
        error : function(http, status, error) {
          UIkit.notification("Error:"+error);
        }
      });

      $.ajax({
        url : "admin/get_nationality.php",
        type : "POST",
        data : "country="+country,
        dataType : "text",
        beforeSend : function(http) {

        },
        success : function(response, status, http) {
          if (response!="") {
             $("#nationality").html(response);
          } else {
             $("#nationality").html("<select name='nationality' id='nationality' class='uk-width-1-1'><option value=''>Please select a country</option></select>");
             UIkit.notify("No nationality found in "+country);
          }
        },
        error : function(http, status, error) {
          UIkit.notification("Error:"+error);
        }
      });
    }//country
  }

  $(document).ready(function () {
    $("#country").change(function () {
      var country=$("#country").val();
      onCountryChange(country);
    });

    //onCountryChange($("#country").val());
  });
</script>
