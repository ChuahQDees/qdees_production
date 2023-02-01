<?php
session_start();
include_once("../mysql.php");
if( ! defined('STUDENT_PHOTO_URL') ){
    define('STUDENT_PHOTO_URL', '/admin/student_photo/');
  }
  
  if( ! defined('STUDENT_PHOTO_PATH') ){
    define('STUDENT_PHOTO_PATH', __DIR__ . '/student_photo/');
  }
include_once("../uikit1.php");

foreach ($_GET as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);
}

if ($id!="") {
?>
<style>
table {
   width :100%;
   border-collapse: collapse;
}

.td-border {
   border: solid 1px #000000;
}

.td-no-border {
   border: 0px;
}

.hr-border {
   border-width: 2px;
   border-color:#000;
}

.font-30 {
   font-size: 30px;
}

.td-no-right-border {
   border-right:none;
}

.td-no-bottom-border {
   border-bottom:none;
}

.td-no-top-border {
   border-top:none;
}
@media print { 
   #btnPrint{
      display:none;
   }
}
</style>

<?php

function getStudentPhotoSrc($file_name, $size = 'small'){
    $file_url = '';
  
    if( $file_name ){
      switch($size){
        case 'medium':
          $size = '300x400';
          break;
        default:
          $size = '60x80';
          break;
      }
      $file_path = STUDENT_PHOTO_PATH . $file_name . '_' . $size . '.jpg';
  
      if( file_exists($file_path) ):
        $file_url = STUDENT_PHOTO_URL . $file_name . '_' . $size . '.jpg';
      endif;
    }
  
    return $file_url;
}

function displayStudentStatus($status){
    switch($status){
       case "A":
       return "Active";
     case "D":
       return "Deferred";
     case "G":
       return "Graduated";
     case "I":
       return "Dropout";
     case "S":
       return "Suspended";
     case "T":
       return "Transferred";
       default:
          return $status;
    }
}

    $student_detail = mysqli_fetch_array(mysqli_query($connection,"SELECT student.*,centre.company_name FROM `student` LEFT JOIN `centre` ON `centre`.`centre_code` = `student`.`centre_code` WHERE sha1(`student`.`id`) = '".$_GET['id']."'"));

?>

<div class="uk-margin uk-margin-top uk-margin-left uk-margin-right">
    <table style="width:100%;">
        <tr>
            <td class="td-no-border" style="text-align:center;">
                <img src="https://starters.q-dees.com/images/Qdees-logo-n.png" alt="logo" style="width:150px;">
            </td>
        </tr>
    </table>
    <table style="width:100%;" >
        <tr>
            <td style="text-align:center;font-weight:bold;font-size:18px;"><br>
                <?php echo $student_detail['company_name']; ?><br><br>
                <?php echo $student_detail['name'].' ('.$student_detail['nric_no'].')'; ?><br>
            </td>
        </tr>
    </table>
    <hr class="hr-border">
    
    <table style="width:100%;color:#000;margin-bottom:25px;" >
        <tr>
            <td style="width:75%;">
                <table style="color:#000;" >
                    <tr style="background-color:#d4d1d1;border:1px solid #000;">
                        <td style="font-weight:bold;padding:10px;">STUDENT DETAILS</td>
                    </tr>
                    <tr style="border:1px solid #000;">
                        <td style="padding:10px;">
                            <b>Status:</b> <?php echo displayStudentStatus($student_detail['student_status']); ?><br>
                            <b>Gender:</b> <?php echo ($student_detail['gender'] == 'F') ? 'Female' : 'Male'; ?><br>
                            <b>Start Date at Centre:</b> <?php echo date('d/m/Y',strtotime($student_detail['start_date_at_centre'])); ?><br>
                            <b>Date of Birth:</b> <?php echo date('d/m/Y',strtotime($student_detail['dob'])); ?><br>
                            <b>Birth Cert No:</b> <?php echo $student_detail['birth_cert_no']; ?><br>
                            <b>Form Serial No:</b> <?php echo $student_detail['form_serial_no']; ?><br>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="font-weight:bold;padding:10px;">
                <?php if(!empty($student_detail['photo_file_name'])) { $photo_src = getStudentPhotoSrc($student_detail['photo_file_name'], 'medium'); ?>
                    <img src="<?php echo $photo_src; ?>" alt="logo" style="width:150px;height:200px;" >
                <?php } else { ?>
                    <img src="../images/add_photo.png" alt="logo" style="width:150px;height:200px;" >
                <?php } ?>
            </td>
        </tr>
    </table>
    <table style="width:100%;color:#000;" >
        <tr style="background-color:#d4d1d1;border:1px solid #000;">
            <td style="font-weight:bold;padding:10px;" colspan="2" >PARENT’S / GUARDIAN CONTACT</td>
        </tr>

        <?php 
            $parent_data = mysqli_query($connection,"SELECT * from student_emergency_contacts where student_code='".$student_detail['student_code']."' order by id ASC");

            while($row = mysqli_fetch_array($parent_data)) {
        ?>
                <tr style="border:1px solid #000;">
                    <td style="padding:10px;">
                        <b>Name:</b> <?php echo $row['full_name']; ?><br>
                        <b>E-Mail:</b> <?php echo $row['email']; ?><br>
                        <b>Contact:</b> <?php echo $row['mobile']; ?><br>
                    </td>
                    <td style="padding:10px;">
                        <b>IC/Passport:</b> <?php echo $row['nric']; ?><br>
                        <b>Occupation:</b> <?php echo $row['occupation']; ?><br>
                        <b>Education Level:</b> <?php echo $row['education_level']; ?><br>
                    </td>
                </tr>
        <?php
            }
        ?>

    </table>
    <hr class="hr-border">
    <table style="width:100%;color:#000;" >
        <tr>
            <td style="font-weight:bold;" >MISC. DETAILS</td>
        </tr>
        <tr>
            <td><br>
                <b>Address:</b> <?php echo $student_detail['add1']; echo !empty($student_detail['add2']) ? ','.$student_detail['add2'] : ''; echo !empty($student_detail['add3']) ? ','.$student_detail['add3'] : ''; echo !empty($student_detail['add4']) ? ','.$student_detail['add4'] : ''; ?><br>
                <b>Country:</b> <?php echo $student_detail['country']; ?><br>
                <b>State:</b> <?php echo $student_detail['state']; ?><br>
            </td>
        </tr>
    </table>
    <br>
    <table style="width:100%;color:#000;" >
        <tr>
            <td>
                <b>Ethnicity:</b> <?php echo $student_detail['race']; ?><br>
            </td>
            <td>
                <b>Medical Condition:</b> <?php echo $student_detail['health_problem']; ?><br>
            </td>
        </tr>
        <tr>
            <td>
                <b>Nationality:</b> <?php echo $student_detail['nationality']; ?><br>
            </td>
            <td>
                <b>Allergies:</b> <?php echo $student_detail['allergies']; ?><br>
            </td>
        </tr>
        <tr>
            <td>
                <b>Religion:</b> <?php echo $student_detail['religion']; ?><br>
            </td>
            <td>
            </td>
        </tr>
    </table>
    <br>
    <table style="width:100%;color:#000;" >
        <tr>
            <td>
                <b>Remarks:</b> <?php echo $student_detail['remarks']; ?><br>
            </td>
        </tr>
    </table>
</div>

<script>
function printDialog() {
   //$("#btnPrint").hide();
   window.print();
}
<?php if(!isset($display)): ?>
$(document).ready(function () {
   printDialog();
   opener.location.reload();
});
<?php endif ?>
</script>
<div class="uk-margin-top">
   <button id="btnPrint" class="uk-button" onclick="printDialog();">Print</button>
</div>
<?php

} else {
   echo "Something is wrong, cannot proceed";
}
?>