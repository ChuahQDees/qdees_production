<?php if ($_GET['selected_year'] != "") { ?>
  <a href="/index.php?p=rpt_student_population">
    <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
  </a>
<?php } else { ?>
  <a href="/">
    <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
  </a>
<?php } ?>
<span>
  <span class="page_title"><img src="/images/title_Icons/Student Population-1.png">Fee Settings Report 
  </span>

  <?php
  include_once("admin/functions.php");
function getStudentStatusCount4($status)
  {
    global $connection;
    $year = $_SESSION['Year'];
    $sql = "SELECT COUNT(id) as total from fee_structure where adjusted = 'Default'";
    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_assoc($result);

    return $row['total'];
  }
  function getStudentStatusCount5($status)
  {
    global $connection;
    $year = $_SESSION['Year'];
    $sql = "SELECT COUNT(id) as total from fee_structure where adjusted = 'Adjusted'";

    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_assoc($result);

    return $row['total'];
  }
  function getStudentStatusCount1($status)
  {
    global $connection;
    $year = $_SESSION['Year'];
    $sql = "SELECT COUNT(id) as total from fee_structure where status = 'Pending'";
    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_assoc($result);

    return $row['total'];
  }
  
 function getStudentStatusCount2($status)
  {
    global $connection;
    $year = $_SESSION['Year'];
    $sql = "SELECT COUNT(id) as total from fee_structure where status = 'Approved'";
    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_assoc($result);

    return $row['total'];
  }
  
  function getStudentStatusCount3($status)
  {
    global $connection;
    $year = $_SESSION['Year'];
    $sql = "SELECT COUNT(id) as total from fee_structure where status = 'Rejected'";
    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_assoc($result);

    return $row['total'];
  }
  
  function getCentreName($centre_code)
  {
    global $connection;

    $sql = "SELECT company_name from centre where centre_code='$centre_code'";
    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_assoc($result);
    $num_row = mysqli_num_rows($result); //echo $num_row; die;

    if ($num_row > 0) {
      return $row["company_name"];
    } else {
      if ($centre_code == "ALL") {
        return "All Centres";
      } else {
        return "";
      }
    }
  }

  if ($_SESSION["isLogin"] == 1) {

    if ((($_SESSION["UserType"] == "S") || ($_SESSION["UserType"] == "O") || ($_SESSION["UserType"] == "A") || ($_SESSION["UserType"] == "H") || ($_SESSION["UserType"] == "C") || ($_SESSION["UserType"] == "R") || ($_SESSION["UserType"] == "CM") || ($_SESSION["UserType"] == "T")) ||
      (hasRightGroupXOR($_SESSION["UserName"], "StudentEdit|StudentView"))
    ) {
      //echo $_SESSION["UserType"];
      include_once("lib/pagination/pagination.php");
      $p = $_GET["p"];
      $m = $_GET["m"];
      $get_sha1_id = $_GET["id"];
      $pg = $_GET["pg"];
      $mode = $_GET["mode"];
      if (isset($_GET['selected_year'])) {
        $year = $_GET['selected_year'];
      } else {
        $year = $_SESSION['Year'];
      }
  ?>

      <script>
        function doDeleteRecord(id) {
          UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function() {
            $("#id").val(id);
            $("#frmDeleteRecord").submit();
          });
        }
      </script>
      <div class="uk-margin-right">
        <!--   <h3>Total Students: <?php /*echo getTotalStudents($_SESSION["CentreCode"]);*/ ?></h3>-->



        <style>

.uk-margin-right {
		margin-top: 40px;
	}

          .report-table tr:not(:first-child):nth-of-type(even) {
            background: #f5f6ff;
          }

          .report-table td {
            color: grey;
            font-size: 1.2em;
          }

          .report-table td {
            border: none !important;
          }

          .btn-qdees {
            display: none;
          }
        </style>

<?php
if (isset($_GET["id"]) && $_GET["mode"]=="VIEW"){
  $get_sha1_id=$_GET['id'];
  $edit_sql="SELECT `fee_structure`.*, `centre`.`company_name` from `fee_structure` LEFT JOIN `centre` ON `fee_structure`.`centre_code` = `centre`.`centre_code` where sha1(`fee_structure`.`id`)='$get_sha1_id'";
	
      	$edit_result=mysqli_query($connection, $edit_sql);
      	$edit_row=mysqli_fetch_assoc($edit_result);

?>
<div class="uk-margin-right">
   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color">Fees Structure Setting</h2>
   </div>
   <div class="uk-form uk-form-small">
<?php
if ($_SESSION["UserType"]=="S") {
?>
   <form name="frmCourse" id="frmCourse" method="post" action="index.php?p=fee_approve_func&mode=VIEW" enctype="multipart/form-data">
<?php
	}
?>
  <div class="uk-grid">
		<input type="hidden" id="fee_id" name="fee_id" value="<?php echo $_GET["id"]; ?>"/>
		<div class="uk-width-medium-5-10">
			<table class="uk-table uk-table-small">
				<tr>
					<td  colspan="5" style="font-size:18px; font-weight:bold;border:none;">Part A (<?php echo $edit_row['company_name']; ?>)</td>
				</tr>
				<tr class="uk-text-small">
          <td style="border:none; font-size:13px;" class="uk-width-3-10 uk-text-bold">Name of Fees Structure<span class="text-danger">*</span>:</td>
          <td style="border:none;" class="uk-width-7-10">
            <input class="uk-width-1-1" type="text" readonly value="<?php echo $edit_row['fees_structure']?>" name="fees_structure" id="fees_structure" >
            <span id="validationStructure"  style="color: red; display: none;">Please insert Name</span>
          </td>
        </tr> 
			  <tr class="uk-text-small">
          <td style="border:none;font-size:13px;" class="uk-width-3-10 uk-text-bold">Student Entry Level<span class="text-danger">*</span>:</td>
				  <td style="border:none;" class="uk-width-7-10">
            <input class="uk-width-1-1" type="text" readonly value="<?php echo $edit_row['subject']?>" name="subject" id="subject" >       
            <span id="validationSubject"  style="color: red; display: none;">Please select Student Entry Level</span>
          </td>
        </tr>
			</table>
		</div>
		<div style="margin-top:45px;" class="uk-width-medium-5-10">
			<table class="uk-table uk-table-small">
			  <tr class="uk-text-small">
          <td style="border:none;font-size:13px;" class="uk-width-3-10 uk-text-bold">Programme Package<span class="text-danger">*</span>:</td>
				  <td style="border:none;" class="uk-width-7-10">       
            <input class="uk-width-1-1" type="text" readonly value="<?php echo $edit_row['programme_package']?>" name="programme_package" id="programme_package" >      
          </td>
        </tr>
      </table>
			<table class="uk-table uk-table-small">
        <tr class="uk-text-small">
          <td style="border:none;font-size:13px;" class="uk-width-3-10 uk-text-bold">Commencement Date<span class="text-danger">*</span>:</td>
          <td style="border:none;" class="uk-width-3-10">
            <input style="width: 100%;" type="text" class="subject_3" name="from_date" id="from_date" placeholder="Start Date" value="<?php echo $edit_row['from_date']?>" readonly>
          </td>
          <td style="border:none;" class="uuk-width-3-10">
            <input style="width: 100%;" type="text" class="subject_3" name="to_date" id="to_date" placeholder="End Date" value="<?php echo $edit_row['to_date']?>" readonly>
          </td>
        </tr>
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
							
						 <input class="school_number" type="number" step="0.01" name="school_default" id="school_default" value="<?php echo $edit_row['school_default']?>" readonly><br>
						 
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
							
						  <input class="" type="text" name="school_default_perent" id="school_default_perent" value="<?php echo $edit_row['school_default_perent'] ?>" readonly><br>
						
					  </td>
					  <td style="border:none; text-align:center;" class="uk-width-1-10">
							
						  <input class="school_adjust" readonly type="number" step="0.01" name="school_adjust" id="school_adjust" value="<?php echo $edit_row['school_adjust'] ?>" ><br>
						
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
              <input class="school_adjust" readonly type="text" step="0.01" style="width: 100%;" value="<?php echo $edit_row['school_collection'] ?>" ><br>
						</td>
					</tr>
					<tr class="">
					   <td style="margin-top:50px; border:none;" class="uk-width-1-10 uk-text-bold">Multimedia Fees<span class="text-danger">*</span>:</td>
					  <td style="border:none; text-align:center;" class="uk-width-1-10">
						 <input class="school_number" type="number" step="0.01" name="multimedia_default" id="multimedia_default" value="<?php echo $edit_row['multimedia_default']?>" readonly><br>
						
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
							
						 <input class="" type="text" name="multimedia_default_perent" id="multimedia_default_perent" value="<?php echo $edit_row['multimedia_default_perent'] ?>" readonly><br>
						 <span id="validationmultimedia_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none; text-align:center;" class="uk-width-1-10">
							
						 <input class="school_adjust" readonly type="number" step="0.01" name="multimedia_adjust" id="multimedia_adjust" value="<?php echo $edit_row['multimedia_adjust'] ?>"><br>
						 <span id="validationMa"  style="color: red; display: none;font-size:11px;margin-left: -65px;">Please input Adjust Fee</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
              <input class="school_adjust" readonly type="text" step="0.01" style="width: 100%;" value="<?php echo $edit_row['multimedia_collection'] ?>" ><br>
					  </td>
					</tr>
					<tr class="">
					   <td style=" margin-top:50px; border:none;" class="uk-width-1-10 uk-text-bold">Facility Fees<span class="text-danger">*</span>:</td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">
							
						 <input class="school_number" type="number" step="0.01" name="facility_default" id="facility_default" value="<?php echo $edit_row['facility_default']?>" readonly><br>
						 <span id="validationfd"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
							
						 <input class="" type="text" name="facility_default_perent" id="facility_default_perent" value="<?php echo $edit_row['facility_default_perent'] ?>" readonly><br>
						 <span id="validationfacility_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">
							
						 <input class="school_adjust" readonly type="number" step="0.01" name="facility_adjust" id="facility_adjust" value="<?php echo $edit_row['facility_adjust'] ?>" ><br>
						 <span id="validationfa"  style="color: red; display: none;font-size:11px;margin-left: -65px;">Please input Adjust Fee</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">

              <input class="school_adjust" readonly type="text" step="0.01" style="width: 100%;" value="<?php echo $edit_row['facility_collection'] ?>" ><br>
          
					  </td>
					</tr>
					<tr class="">
					   <td style="margin-top:50px;border:none;" class="uk-width-1-10 uk-text-bold">Enhanced Foundation International English<span class="text-danger">*</span>:</td>
					  <td style="border:none; text-align:center;" class="uk-width-1-10">							
						 <input class="school_number" type="number" step="0.01" name="enhanced_default" id="enhanced_default" value="<?php echo $edit_row['enhanced_default']?>" readonly><br>
						 <span id="validationed"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none; " class="uk-width-1-10">
							
						 <input class="" type="text" name="enhanced_default_perent" id="enhanced_default_perent" value="<?php echo $edit_row['enhanced_default_perent'] ?>" readonly><br>
						 <span id="validationenhanced_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none; text-align:center;" class="uk-width-1-10">
							
						  <input class="school_adjust" readonly type="number" step="0.01" name="enhanced_adjust" id="enhanced_adjust" value="<?php echo $edit_row['enhanced_adjust'] ?>" ><br>
						 
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
              <input class="school_adjust" readonly type="text" step="0.01" style="width: 100%;" value="<?php echo $edit_row['enhanced_collection'] ?>" ><br>
					  </td>
					</tr>
					<tr class="">
					   <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold">Total:</td>
					  <td style="text-align:center;" class="uk-width-1-10">							
						 <input class="" type="number" step="0.01" name="school_total_d" id="school_total_d" value="<?php echo $edit_row['school_total_d']?>" readonly>
					  </td>
					  <td class="uk-width-1-10"></td>
					  <td style="text-align:center;" class="uk-width-1-10">
							
						 <input class="" type="number" readonly step="0.01" name="school_total_f" id="school_total_f" value="<?php echo $edit_row['school_total_f'] ?>">
					  </td>
					  <td class="uk-width-1-10">
						
					  </td>
					</tr>
				
					<tr>
						<td colspan="5" style="font-size:18px; font-weight:bold;border:none;">Part C (Enhanced Foundation Programme)</td>
					</tr>
					<tr class="">
					   <td style="margin-top:50px; border:none;" class="uk-width-1-10 uk-text-bold">IQ Math<span class="text-danger">*</span>:</td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10" readonly>							
						 <input class="math_default" type="number" step="0.01" name="iq_math_default" id="iq_math_default" value="<?php echo $edit_row['iq_math_default'] ?>" readonly><br>
						 <span id="validationiq"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
							
						 <input class="" type="text" name="iq_math_default_perent" id="iq_math_default_perent" value="<?php echo $edit_row['iq_math_default_perent'] ?>" readonly><br>
						 <span id="validationiq_math_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">
						  <input class="math_adjust" readonly type="number" step="0.01" name="iq_math_adjust" id="iq_math_adjust" value="<?php echo $edit_row['iq_math_adjust'] ?>" ><br>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
              <input class="iq_math_collection" readonly type="text" step="0.01" style="width: 100%;" value="<?php echo $edit_row['iq_math_collection'] ?>" ><br>
					  </td>
					</tr>
					<tr class="">
					  <td style="margin-top:50px;border:none;" class="uk-width-1-10 uk-text-bold">Mandarin<span class="text-danger">*</span>:</td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">							
						  <input class="math_default" type="number" step="0.01" name="mandarin_default" id="mandarin_default" value="<?php echo $edit_row['mandarin_default'] ?>" readonly><br>
						
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
							
						 <input class="" type="text" name="mandarin_default_perent" id="mandarin_default_perent" value="<?php echo $edit_row['mandarin_default_perent'] ?>" readonly><br>
						
					  </td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">
							
						 <input class="math_adjust" type="number" readonly step="0.01" name="mandarin_adjust" id="mandarin_adjust" value="<?php echo $edit_row['mandarin_adjust'] ?>" ><br>
						
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
              <input class="iq_math_collection" readonly type="text" step="0.01" style="width: 100%;" value="<?php echo $edit_row['mandarin_collection'] ?>" ><br>
					  </td>
					</tr>
					<tr class="">
					    <td style="margin-top:50px;border:none;" class="uk-width-1-10 uk-text-bold">International Art<span class="text-danger">*</span>:</td>
					    <td style="border:none;text-align:center;" class="uk-width-1-10">							
						    <input class="math_default" type="number" step="0.01" name="international_default" id="international_default" value="<?php echo $edit_row['international_default'] ?>" readonly><br>
					    </td>
					    <td style="border:none;" class="uk-width-1-10">
						    <input class="" type="text" name="international_perent" id="international_perent" value="<?php echo $edit_row['mandarin_default_perent'] ?>" readonly><br>
						 <span id="validationinternational_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">
							
						 <input class="math_adjust" readonly type="number" step="0.01" name="international_adjust" id="international_adjust" value="<?php echo $edit_row['international_adjust'] ?>" ><br>
						 <span id="validationina"  style="color: red; display: none;font-size:11px;margin-left: -65px;">Please input Adjust Fee</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
              <input class="iq_math_collection" readonly type="text" step="0.01" style="width: 100%;" value="<?php echo $edit_row['international_collection'] ?>" ><br>
					  </td>
					</tr>

					<tr class="">
					   <td style="margin-top:50px; border:none;" class="uk-width-1-10 uk-text-bold">Robotic Plus<span class="text-danger">*</span>:</td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10" readonly>							
						 <input class="math_default " type="number" step="0.01" name="robotic_plus_default" id="robotic_plus_default" value="<?php echo $edit_row['robotic_plus_default'] ?>" readonly><br>
						
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
							
						 <input class="" type="text" name="robotic_plus_default_perent" id="robotic_plus_default_perent" value="<?php echo $edit_row['robotic_plus_default_perent'] ?>" readonly><br>
						 <span id="validationrobotic_plus_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">
							
						 <input class="math_adjust" readonly type="number" step="0.01" name="robotic_plus_adjust" id="robotic_plus_adjust" value="<?php echo $edit_row['robotic_plus_adjust'] ?>"><br>
						 <span id="validationrp"  style="color: red; display: none;font-size:11px;margin-left: -65px;">Please input Adjust Fee</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
					 
            <input class="iq_math_collection" readonly type="text" step="0.01" style="width: 100%;" value="<?php echo $edit_row['robotic_plus_collection'] ?>" ><br>
						
					  </td>
					</tr>

					<tr class="">
					   <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold">Total:</td>
					  <td style="text-align:center;" class="uk-width-1-10">							
						 <input class="" type="number" step="0.01" name="total_default_c" id="total_default_c" value="<?php echo $edit_row['total_default_c'] ?>" readonly>
					  </td>
					  <td class="uk-width-1-10"></td>
					  <td style="text-align:center;" class="uk-width-1-10">
							
						 <input class="" readonly type="number" step="0.01" name="total_adjust_c" id="total_adjust_c" value="<?php echo $edit_row['total_adjust_c'] ?>" >
					  </td>
					  <td class="uk-width-1-10">
					
					  </td>
					</tr>
			
					<tr>
						<td colspan="5" style="font-size:18px; font-weight:bold;border:none;">Part D (Material Fees)</td>
					</tr>
					<tr class="">
					   <td style="width: 7.7%; margin-top:50px;border:none;" class="uk-width-1-10 uk-text-bold">Integrated Modules<span class="text-danger">*</span>:</td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">							
						 <input class="int_default" type="number" step="0.01" name="integrated_default" id="integrated_default" value="<?php echo $edit_row['integrated_default'] ?>" readonly><br>
						 <span id="validationitd"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
							
						 <input class="" type="text" step="0.01" name="integrated_default_perent" id="integrated_default_perent" value="<?php echo $edit_row['integrated_default_perent'] ?>" readonly><br>
						 <span id="validationintegrated_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">
							
						 <input class="integrated_adjust" readonly type="number" step="0.01" name="integrated_adjust" id="integrated_adjust" value="<?php echo $edit_row['integrated_adjust'] ?>" ><br>
						 <span id="validationita"  style="color: red; display: none;font-size:11px;margin-left: -65px;">Please input Adjust Fee</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
					    <input class="iq_math_collection" readonly type="text" step="0.01" style="width: 100%;" value="<?php echo $edit_row['integrated_collection'] ?>" ><br>
						
					  </td>
					</tr>
					<tr class="">
					   <td style="margin-top:50px;border:none;" class="uk-width-1-10 uk-text-bold">Link & Think Reading Series<span class="text-danger">*</span>:</td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">							
						 <input class="int_default" type="number" step="0.01" name="link_default" id="link_default" value="<?php echo $edit_row['link_default'] ?>" readonly ><br>
						 <span id="validationlnk"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
							
						 <input class="" type="text" name="link_default_perent" id="link_default_perent" value="<?php echo $edit_row['link_default_perent'] ?>" readonly><br>
						 <span id="validationlink_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">
							
						 <input class="integrated_adjust" readonly type="number" step="0.01" name="link_adjust" id="link_adjust" value="<?php echo $edit_row['link_adjust'] ?>" ><br>
						 <span id="validationlna"  style="color: red; display: none;font-size:11px;margin-left: -65px;">Please input Adjust Fee</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
              <input class="iq_math_collection" readonly type="text" step="0.01" style="width: 100%;" value="<?php echo $edit_row['link_collection'] ?>" ><br>
					  </td>
					</tr>
					<tr class="">
					   <td style="margin-top:50px; border:none;" class="uk-width-1-10 uk-text-bold">Mandarin Modules<span class="text-danger">*</span>:</td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">							
						 <input class="int_default" type="number" step="0.01" name="mandarin_m_defauft" id="mandarin_m_defauft" value="<?php echo $edit_row['mandarin_m_defauft'] ?>" readonly><br>
						
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
							
						 <input class="" type="text" name="mandarin_m_default_perent" id="mandarin_m_default_perent" value="<?php echo $edit_row['mandarin_m_default_perent'] ?>" readonly><br>
				
					  </td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">
							
						 <input class="integrated_adjust" readonly type="number" step="0.01" name="mandarin_m_adjust" id="mandarin_m_adjust" value="<?php echo $edit_row['mandarin_m_adjust'] ?>" ><br>
						
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
              <input class="iq_math_collection" readonly type="text" step="0.01" style="width: 100%;" value="<?php echo $edit_row['mandarin_m_collection'] ?>" ><br>
					  </td>
					</tr>
					<tr class="">
					   <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold">Total:</td>
					  <td style="text-align:center;" class="uk-width-1-10">							
						 <input class="" type="number" step="0.01" name="total_default_d" id="total_default_d" value="<?php echo $edit_row['total_default_d'] ?>" readonly>
					  </td>
					  <td class="uk-width-1-10"></td>
					  <td style="text-align:center;" class="uk-width-1-10">
							
						 <input class="" type="number" readonly step="0.01" name="total_adjust_d" id="total_adjust_d" value="<?php echo $edit_row['total_adjust_d'] ?>" >
					  </td>
					  <td class="uk-width-1-10">

					  </td>
					</tr>
		
					<tr>
						<td colspan="5" style="font-size:18px; font-weight:bold;border:none;">Part E (Afternoon Programme)</td>
					</tr>
					<tr class="">
					   <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold">Basic Afternoon Programme<span class="text-danger">*</span>:</td>
					  <td style="text-align:center;" class="uk-width-1-10">							
						 <input class="" type="number" step="0.01" name="basic_default" id="basic_default" value="<?php echo $edit_row['basic_default'] ?>" readonly><br>
						 <span id="validationbd"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td class="uk-width-1-10">
							
						 <input class="" type="text" name="basic_default_perent" id="basic_default_perent" value="<?php echo $edit_row['basic_default_perent'] ?>" readonly><br>
						 <span id="validationbasic_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  </td>
					  <td style="text-align:center;" class="uk-width-1-10">							
						 <input class="" readonly type="number" step="0.01" name="basic_adjust" id="basic_adjust" value="<?php echo $edit_row['basic_adjust'] ?>" ><br><span id="validationba"  style="color: red; display: none;font-size:11px;margin-left: -65px;">Please input Adjust Fee</span>
					  </td>
					  <td class="uk-width-1-10">
              <input class="iq_math_collection" readonly type="text" step="0.01" style="width: 100%;" value="<?php echo $edit_row['basic_collection'] ?>" ><br>
						
					  </td>
					</tr>
					<tr class="">
					   <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold">Afternoon Programme + Robotics<span class="text-danger">*</span>:</td>
					  <td style="text-align:center;" class="uk-width-1-10">							
						 <input class="view-data" type="number" step="0.01" name="afternoon_robotic_default" id="afternoon_robotic_default" value="<?php echo $edit_row['afternoon_robotic_default'] ?>" readonly><br>
						 <span id="validationard"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td class="uk-width-1-10">
							
						 <input class="view-data" type="text" name="afternoon_robotic_default_perent" id="afternoon_robotic_default_perent" value="<?php echo $edit_row['afternoon_robotic_default_perent'] ?>" readonly><br>
						 <span id="validationafternoon_robotic_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  </td>
					  <td style="text-align:center;" class="uk-width-1-10">							
						 <input class="view-data" readonly type="number" step="0.01" name="afternoon_robotic_adjust" id="afternoon_robotic_adjust" value="<?php echo $edit_row['afternoon_robotic_adjust'] ?>"><br><span id="validationara"  style="color: red; display: none;font-size:11px;margin-left: -65px;">Please input Adjust Fee</span>
					  </td>
					  <td class="uk-width-1-10">
              <input class="iq_math_collection" readonly type="text" step="0.01" style="width: 100%;" value="<?php echo $edit_row['afternoon_robotic_collection'] ?>" ><br>
					  </td>
					</tr>
			
					<tr>
						<td colspan="5" style="font-size:18px; font-weight:bold;border:none;">Part F (Upon Registration)</td>
					</tr>
					<tr class="">
					   <td style="margin-top:50px; border:none;" class="uk-width-1-10 uk-text-bold">Mobile Application<span class="text-danger">*</span>:</td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">							
						 <input class="regist_default" step="0.01" type="number" name="mobile_default" id="mobile_default" value="<?php echo $edit_row['mobile_default'] ?>" readonly><br>
						 <span id="validationmdi"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
							
						 <input class="" type="text" name="mobile_perent" id="mobile_perent" value="<?php echo $edit_row['mobile_perent'] ?>" readonly><br>
						 <span id="validationmobile_perent" style="color: red; display: none;font-size:11px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">
							
						 <input class="registration_adjust" readonly step="0.01" type="number" name="mobile_adjust" id="mobile_adjust" value="<?php echo $edit_row['mobile_adjust'] ?>" ><br>
						 <span id="validationmba"  style="color: red; display: none;font-size:11px;margin-left: -65px;">Please input Adjust Fee</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
              <input class="iq_math_collection" readonly type="text" step="0.01" style="width: 100%;" value="<?php echo $edit_row['mobile_collection'] ?>" ><br>
					  </td>
					</tr>
					<tr class="">
					   <td style="margin-top:50px; border:none;" class="uk-width-1-10 uk-text-bold">Registration<span class="text-danger">*</span>:</td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10" >							
						 <input class="regist_default" step="0.01" type="number" name="registration_default" id="registration_default" value="<?php echo $edit_row['registration_default'] ?>" readonly><br>
					
					  </td>
					  <td style="border:none;" class="uk-width-1-10"></td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">
							
						 <input class="registration_adjust" readonly step="0.01" type="number" name="registration_adjust" id="registration_adjust" value="<?php echo $edit_row['registration_adjust'] ?>" ><br>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
					
					  </td>
					</tr>
					
					<tr class="">
					   <td style="margin-top:50px;border:none;" class="uk-width-1-10 uk-text-bold">Insurance<span class="text-danger">*</span>:</td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">							
						 <input class="regist_default" step="0.01" type="number" name="insurance_default" id="insurance_default" value="<?php echo $edit_row['insurance_default'] ?>" readonly><br>
						 <span id="validationinsd"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10"></td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">
							
						 <input class="registration_adjust" step="0.01" type="number" name="insurance_adjust" id="insurance_adjust" value="<?php echo $edit_row['insurance_adjust'] ?>" readonly ><br>
						
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
						
					  </td>
					</tr>
					<tr class="">
					   <td style="margin-top:50px; border:none;" class="uk-width-1-10 uk-text-bold">Uniform (2 sets)<span class="text-danger">*</span>:</td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">							
						 <input class="regist_default" step="0.01" type="number" name="uniform_default" id="uniform_default" value="<?php echo $edit_row['uniform_default'] ?>" readonly><br>
						 <span id="validationuid"  style="color: red; display: none;font-size:11px;margin-left: -17px;">Please select Student Entry Level</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10"></td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">
							
						 <input class="registration_adjust" readonly step="0.01" type="number" name="uniform_adjust" id="uniform_adjust" value="<?php echo $edit_row['uniform_adjust'] ?>" ><br>
						 <span id="validationuia"  style="color: red; display: none;font-size:11px;margin-left: -65px;">Please input Adjust Fee</span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
					
					  </td>
					</tr>
					<tr class="">
					   <td style="margin-top:50px; border:none;" class="uk-width-1-10 uk-text-bold">Gymwear (1 set)<span class="text-danger">*</span>:</td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">							
						 <input class="regist_default" step="0.01" type="number" name="gymwear_default" id="gymwear_default" value="<?php echo $edit_row['gymwear_default'] ?>" readonly><br>
					
					  </td>
					  <td style="border:none;" class="uk-width-1-10"></td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">
							
						 <input class="registration_adjust" readonly step="0.01" type="number" name="gymwear_adjust" id="gymwear_adjust" value="<?php echo $edit_row['gymwear_adjust'] ?>" ><br>
						
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
						
					  </td>
					</tr>
					<tr class="">
					   <td style="margin-top:50px;border:none;" class="uk-width-1-10 uk-text-bold">Q-dees Level Kit<span class="text-danger">*</span>:</td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">							
						 <input class="regist_default" step="0.01" type="number" name="q_dees_default" id="q_dees_default" value="<?php echo $edit_row['q_dees_default'] ?>" readonly><br>
					
					  </td>
					  <td style="border:none;" class="uk-width-1-10"></td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">
							
						 <input class="registration_adjust" readonly step="0.01" type="number" name="q_dees_adjust" id="q_dees_adjust" value="<?php echo $edit_row['q_dees_adjust'] ?>" ><br>
						 
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
						
					  </td>
					</tr>
					<tr class="">
					   <td style="margin-top:50px;border:none;" class="uk-width-1-10 uk-text-bold">Q-dees Bag<span class="text-danger">*</span>:</td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">							
						 <input class="regist_default" step="0.01" type="number" name="q_bag_default" id="q_bag_default" value="<?php echo $edit_row['q_bag_default'] ?>" readonly><br>
						
					  </td>
					  <td style="border:none;" class="uk-width-1-10"></td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10">
							
						 <input readonly class="registration_adjust" step="0.01" type="number" name="q_bag_adjust" id="q_bag_adjust" value="<?php echo $edit_row['q_bag_adjust'] ?>" ><br>
						
					  </td>
					  <td style="border:none;" class="uk-width-1-10">
					
					  </td>
					</tr>
					<tr class="">
					   <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold">Total:</td>
					  <td style="text-align:center;" class="uk-width-1-10">							
						 <input class="" step="0.01" type="number" name="total_default_f" id="total_default_f" value="<?php echo $edit_row['total_default_f'] ?>" readonly>
					  </td>
					  <td class="uk-width-1-10"></td>
					  <td style="text-align:center;" class="uk-width-1-10">
							
						 <input class="" readonly step="0.01" type="number" name="total_adjust_f" id="total_adjust_f" value="<?php echo $edit_row['total_adjust_f'] ?>" >
					  </td>
					  <td class="uk-width-1-10">
				
					  </td>
					</tr>
					<tr>
						<td colspan="5" style="font-size:18px; font-weight:bold;border:none;">Part G (Pendidikan Islam)</td>
					</tr>
					<tr class="">
					   <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold">Pendidikan Islam / Jawi<span class="text-danger">*</span>:</td>
					  <td style="text-align:center;" class="uk-width-1-10">							
						 <input class="" step="0.01" type="number" name="islam_default" id="islam_default" value="<?php echo $edit_row['islam_default'] ?>" readonly><br>
					  </td>
					  <td class="uk-width-1-10">
						 	<input class="" type="text" name="islam_default_perent" id="islam_default_perent" value="<?php echo $edit_row['islam_default_perent'] ?>" readonly><br>
						
					  	</td>
					  <td style="text-align:center;" class="uk-width-1-10">
							
						 <input class="" readonly step="0.01" type="number" name="islam_adjust" id="islam_adjust" value="<?php echo $edit_row['islam_adjust'] ?>" ><br><span id="validationisa"  style="color: red; display: none;font-size:11px;margin-left: -65px;">Please input Adjust Fee</span>
					  </td>
					  <td class="uk-width-1-10">
              <input class="iq_math_collection" readonly type="text" step="0.01" style="width: 100%;" value="<?php echo $edit_row['islam_collection'] ?>" ><br>
						
					  	</td></tr>
				</table>
			</div>
         <div class="uk-width-medium-5-10">	
			<table class="uk-table uk-table-small">
				<tr>
					<td style="margin-top:50px;border:none;" class="uk-width-1-10 uk-text-bold">Remarks:</td>
					<td style="border:none;"><textarea id="remarks" name="remarks" rows="5" cols="65" readonly><?php echo $edit_row['remarks'] ?> </textarea></td>
				</tr>
				
				<tr class="uk-text-small">
					<td class="uk-width-1-10 uk-text-bold" style="border:none;font-size: 15px;">Status :</td>
					<td  style="border:none;font-size:15px;"><?php echo $edit_row['status'] ?></td>
					<!-- <td  style="border:none; color:#00ff00;font-size:25px;">Approved</td>
					<td  style="border:none;  color:#ff0000;font-size:25px;">Rejected</td> -->
				</tr>
				<tr class="uk-text-small">
				<td class="uk-width-1-10 uk-text-bold" style="border:none;font-size: 15px;">Submitted Fee :</td>
				<td class="uk-width-1-10">
						<select style="font-size:15px;" name="adjusted" id="adjusted" class="">
						<option value="Default" <?php if($edit_row['adjusted']=='Default') {echo 'selected';}?>>Default</option>
						<option value="Adjusted" <?php if($edit_row['adjusted']=='Adjusted') {echo 'selected';}?>>Adjusted </option>
						 </select>
					  </td>
				</tr>
				<tr>
					<td style="margin-top:50px;border:none;" class="uk-width-1-10 uk-text-bold">Master's Remarks:</td>
					<td style="border:none;"><textarea id="remarks_master" readonly name="remarks_master" rows="5" cols="65"><?php echo $edit_row['remarks_master'] ?> </textarea></td>
				</tr>
				<tr class="uk-text-small">
                           <td class="uk-width-1-10 uk-text-bold" style="border:none;font-size: 15px;"></td>
                           
				 <td class="uk-width-7-10" id="dvSSM_file">
                     <!-- <input class="uk-width-1-1" type="file" name="file_s" id="file_s"  accept=".doc, .docx, .pdf, .png, .jpg, .jpeg"> -->
                     
<?php
if ($edit_row["doc_remarks"]!="") {
?>
<a href="admin/uploads/<?php echo $edit_row['doc_remarks']?>" target="_blank">Click to view document</a>
<?php
}
?>
                  </td> 

						</tr>
			</table>
         </div>
      </div>
 
             
<?php
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "SalesEdit"))) {
?>
   </form>
<?php
}
?>
   </div>
</div><br>
<?php } ?>


        <br>
        <div class="uk-width-1-1 myheader">
          <h2 class="uk-text-center myheader-text-color myheader-text-style">Searching</h2>
        </div>

        <form class="uk-form" name="frmfeeSearch" id="frmfeeSearch" method="get">
          <input type="hidden" name="p" id="p" value="rptFeeSettings">

          <div class="uk-grid">
            <div <?php if (($_SESSION["UserType"] == "S") || ($_SESSION["UserType"] == "H") || ($_SESSION["UserType"] == "C") || ($_SESSION["UserType"] == "R") || ($_SESSION["UserType"] == "CM") || ($_SESSION["UserType"] == "T")) { ?> style="padding-right: 0px;" <?php } ?> class="uk-form uk-grid uk-grid-small ">
              <?php
              if (($_SESSION["UserType"] == "S") || ($_SESSION["UserType"] == "H") || ($_SESSION["UserType"] == "C") || ($_SESSION["UserType"] == "R") || ($_SESSION["UserType"] == "CM") || ($_SESSION["UserType"] == "T")) {
                $sql = "SELECT * from centre order by centre_code";
                $result = mysqli_query($connection, $sql);
              ?>
                <div style="width:16%;padding-right: 0px;" class="uk-width-2-10">
                  Centre Name<br>

                  <input list="centre_name" id="screens.screenid" name="centre_name" value="<?php echo $_GET["centre_name"] ?>">

                  <datalist class="form-control" id="centre_name" style="display: none;">

                    <option value="ALL" <?php echo $centreCode == 'ALL' ? 'selected' : '' ?>>All Centre</option>

                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                      <option value="<?php echo $row['company_name'] ?>" <?php echo $row['centre_code'] == $centreCode ? 'selected' : '' ?>><?php echo $row["centre_code"] ?></option>

                    <?php

                    }

                    ?>

                  </datalist>
                </div>

              <?php
              } 
			  // else {

                // $centrecode = $_SESSION['CentreCode'];

                // $sqlCentre = "SELECT centre_code, company_name from centre where centre_code = '$centrecode'  limit 1 ";
                // $resultCentre = mysqli_query($connection, $sqlCentre);
                // $rowCentre = mysqli_fetch_assoc($resultCentre);
                // var_dump($rowCentre["kindergarten_name"]);die; 
              ?>
                <!--<span class=" centre_n">
                  Centre Name<br>
                  <input style="width: 100%;border: 0;font-weight: bold;" name="centre_name" id="centre_name" value="<?php// echo getCentreNameIndex($_SESSION["CentreCode"]) ?>" readonly>
                </span>-->
              <?php
              // }
              ?>

              <div style="width:10%;padding-right: 0px;padding-left: 5px;" class="uk-width-medium-1-3 centre_n">
                Fees <br>
                <select name='fees' id='fees' class='uk-width-1-1'>
				 <option selected value=''>Select Status</option>
					<option <?php if ($_GET['fees'] == "Default") {
                          echo "selected";
                        } ?> value='Default'>Default</option>
					<option <?php if ($_GET['fees'] == "Adjusted") {
                          echo "selected";
                        } ?> value='Adjusted'>Adjusted</option>
                </select>
              </div>
            

            <div class="uk-width-2-10" style="padding-right: 0px;padding-left: 5px;width: 10%;">
              Status <br>
              <select name='status' id='status'>
                <option selected value=''>Select Status</option>
                <option <?php if ($_GET['status'] == "Pending") {
                          echo "selected";
                        } ?> value='Pending'>Pending</option>
                <option <?php if ($_GET['status'] == "Approved") {
                          echo "selected";
                        } ?> value='Approved'>Approved</option>
                <option <?php if ($_GET['status'] == "Rejected") {
                          echo "selected";
                        } ?> value='Rejected'>Rejected</option>
              </select>
              <?php
              //$fields=array("A"=>"Active", "D"=>"Deferred", "G"=>"Graduated", "S"=>"Suspended", "T"=>"Transferred");
              // generateSelectArray($fields, "student_status", "class='uk-width-1-1 '", $data_array["student_status"]);
              ?>
              <span id="validationStudentStatus" style="color: red; display: none;">Please select Student Status</span>
            </div>
			<div class="uk-width-2-10" style="padding-right: 0px;padding-left: 4px;width: 10%;">
              Level <br>
              <select name='level' id='level'>
                <option selected value=''>Select Level</option>
                <option <?php if ($_GET['level'] == "EDP") {
                          echo "selected";
                        } ?> value='EDP'>EDP</option>
                <option <?php if ($_GET['level'] == "QF1") {
                          echo "selected";
                        } ?> value='QF1'>QF1</option>
                <option <?php if ($_GET['level'] == "QF2") {
                          echo "selected";
                        } ?> value='QF2'>QF2</option>
				<option <?php if ($_GET['level'] == "QF3") {
                          echo "selected";
                        } ?> value='QF3'>QF3</option>
              </select>
              
            </div>
			<div class="uk-width-2-10" style="padding-right: 0px;padding-left: 0px;width: 9.5%; position: relative;bottom: 22px;">
              Programme<br>Package <br>
              <select name='pp' id='pp'>
                <option selected value=''>Select Level</option>
                <option <?php if ($_GET['pp'] == "Full Day") {
                          echo "selected";
                        } ?> value='Full Day'>Full Day</option>
                <option <?php if ($_GET['pp'] == "Half Day") {
                          echo "selected";
                        } ?> value='Half Day'>Half Day</option>
                <option <?php if ($_GET['pp'] == "3/4 Day") {
                          echo "selected";
                        } ?> value='3/4 Day'>3/4 Day</option>
              </select>
            </div>
            <div class="uk-width-2-10" style="width: 12%;padding-right: 0px;padding-left: 2px;">
			Country<br>
				<input name="country" id="country" value="<?php echo $_GET['country'] ?>">			
			</div>
			<!--<div class="uk-width-2-10" style="padding-right: 0px;padding-left: 15px;">
				Level<br>
				<input name="level " id="level" value="<?php// echo $_GET['level'] ?>">			
			</div>-->
			<!--<div class="uk-width-2-10" style="padding-right: 0px; padding-left: 5px;">
				Programme Package<br>
				<input name="pp" id="pp" value="<?php// echo $_GET['pp'] ?>">			
			</div>-->
			<div class="uk-width-2-10" style="padding-right: 0px;margin-top: 12px; width: 12%;position: relative;bottom: 33px;padding-left: 1px;">
				Name of fee<br>structure <br>
				<input name="name" id="name" value="<?php echo $_GET['name'] ?>">			
			</div>
			<!--<div class="uk-width-2-10" style="padding-right: 0px;margin-top: 12px;">
				Month/Year selection<br>
				<input name="month_year" id="month_year" value="">			
			</div>-->
            <div class="uk-width-2-10" style="white-space: nowrap;">
              <br>
              <button class="uk-button">Search</button>
              <!--<a href="admin/rptFeeSettings.php?centre_code=<?php // echo $centreCode ?> target="_blank" class="uk-button">Print</a>-->
			   <button onclick="printDiv('print_1')" target="_blank" class="uk-button">Print</button>
            </div>
            </div>
          </div>
        </form>
<div id="print_1">
        <div class="uk-width-1-1 myheader mt-5">
          <h2 class="uk-text-center myheader-text-color myheader-text-style">Fee Settings Report</h2>
        </div>
		<div class="nice-form">
      <div class="uk-grid">
         <div class="uk-width-medium-5-10">
            <table class="uk-table report-table" style="width: 100%;">
               <tr>

                  <td class="uk-text-bold">Centre Name</td>
                  <td>All Centres<?php// echo getCentreName($centre_code); ?></td>
               </tr>
               <tr>
                  <td class="uk-text-bold">Prepare By</td>
                  <td><?php echo $_SESSION["UserName"] ?></td>
               </tr>
               <tr>
                  <td colspan="2" class="uk-text-bold">NOTE: To be submitted termly with the order form</td>
               </tr>
            </table>
         </div>
         <div class="uk-width-medium-5-10">
            <table class="uk-table report-table">
               <tr>
                  <td class="uk-text-bold">Academic Year</td>
                  <td><?php 
                    if(!empty($selected_month)) {
                      $str_length = strlen($selected_month);
                      echo str_split($selected_month, ($str_length - 2))[0];
                   } else { 
                      echo $_SESSION['Year'];
                   }
                  ?></td>
               </tr>
               <tr>
                  <td class="uk-text-bold">School Term</td>
                  <td>
                     <?php
                     $month = date("m");
                     $year = $_SESSION['Year'];
                     if (isset($selected_month) && $selected_month != '') {
                        $year_length = strlen($selected_month);
                        $month = substr($selected_month, ($year_length - 2), 2);
                        $year = substr($selected_month, 0, -2);
                     }
                        //$sql = "SELECT * from codes where year=" . $year;
						$sql = "SELECT * from codes where module='SCHOOL_TERM'";
                    if($month!="13"){
                      $sql .= " and from_month<=$month and to_month>=$month";
                    }
                    $sql .= " order by category";
                      //Print_r($sql);
                        $centre_result = mysqli_query($connection, $sql);
                        $str = "";
                      while ($centre_row = mysqli_fetch_assoc($centre_result)) {
                        // echo $centre_row['category'] . "/" . $centre_row['year'] . "<br>";
                        $str .= $centre_row['category'] . ', ';
                      }
                      echo rtrim($str, ", ");
                     //}
                     ?>


                  </td>
               </tr>
               <tr>
                  <td class="uk-text-bold">Date of submission</td>
                  <td><?php echo date("Y-m-d H:i:s") ?></td>
               </tr>
            </table>
         </div>
      </div>
      </div>
        <div class="uk-overflow-container">
          <!--<div class="uk-grid">
            <div class="uk-width-medium-5-10">
              <table class="uk-table">
                <tr>
                  <td class="uk-text-bold">Centre Name</td>
                  <td><?php echo getCentreName($centreCode) ?></td>
                </tr>
                <tr>
                  <td class="uk-text-bold">Prepare By</td>
                  <td><?php echo $_SESSION["UserName"] ?></td>
                </tr>
                <tr>
                  <td colspan="2" class="uk-text-bold">NOTE: To be submitted termly with the order form</td>
                </tr>
              </table>
            </div>
            <div class="uk-width-medium-5-10">
              <table class="uk-table">
                <tr>
                  <td class="uk-text-bold">Academic Year</td>
                  <td><?php echo $year ?></td>
                </tr>
                <tr>
                  <td class="uk-text-bold">School Term</td>
                  <td>
                    <?php
                    if (isset($year) && $year != '') {

                      // $sql = "SELECT * from codes where module='SCHOOL_TERM' and `year` = '$year' ORDER BY asc";
                      $sql = "SELECT * from codes where module='SCHOOL_TERM' and `year` = '$year' ORDER BY category ";
                      // Print_r($sql);
                      $centre_result = mysqli_query($connection, $sql);
                      $str = "";
                      while ($centre_row = mysqli_fetch_assoc($centre_result)) {
                        // echo $centre_row['category'] . "/" . $centre_row['year'] . "<br>";
                        $str .= $centre_row['category'] . ', ';
                      }
                      echo rtrim($str, ", ");
                    }
                    ?>
                  </td>
                </tr>
                <tr>
                  <td class="uk-text-bold">Date of submission</td>
                  <td><?php echo date("Y-m-d H:i:s") ?></td>
                </tr>
              </table>
            </div>
          </div>-->
          <table class="table table-bordered mb-4 report-table" style="text-align:center;">
            <thead class="myheader">
              <tr>
                <th style="color: #fff; font-size: 20px">Default</th>
                <th style="color: #fff;  font-size: 20px">Adjusted</th>
                <th style="color: #fff;  font-size: 20px">Pending</th>
                <th style="color: #fff;  font-size: 20px">Approved</th>
                <th style="color: #fff; font-size: 20px">Rejected</th> 
              </tr>
            </thead>
            <tbody>
              <tr style="background-color: white">
                <td style="font-size: 24px;  border-right: 1px solid #d3dbe2!important"><?php echo getStudentStatusCount4($centreCode, 'A'); ?></td>
                <td style="font-size: 24px;  border-right: 1px solid #d3dbe2!important"><?php echo getStudentStatusCount5($centreCode, 'D'); ?></td>
                <td style="font-size: 24px;  border-right: 1px solid #d3dbe2!important"><?php echo getStudentStatusCount1($centreCode, 'G'); ?></td>
                <td style="font-size: 24px;  border-right: 1px solid #d3dbe2!important"><?php echo  getStudentStatusCount2($centreCode, 'I'); //getDropoutCount($centreCode) 
                                                                                        ?></td>
                <td style="font-size: 24px;  border-right: 1px solid #d3dbe2!important"><?php echo getStudentStatusCount3($centreCode, 'S'); ?></td>
                <!--<td style="font-size: 24px;  border-right: 1px solid #d3dbe2!important"><?php// echo getStudentStatusCount($centreCode, 'T'); ?></td>
                <?php
                // $active = getStudentStatusCount($centreCode, 'A');
                // $deferred = getStudentStatusCount($centreCode, 'D');
                // $graduated = getStudentStatusCount($centreCode, 'G');
                // $dropout = getStudentStatusCount($centreCode, 'I');
                // $suspended = getStudentStatusCount($centreCode, 'S');
                // $transfered = getStudentStatusCount($centreCode, 'T');
                // $total = $active + $deferred + $graduated + $dropout + $suspended + $transfered;
                ?>
                <td style="font-size: 24px;  border-right: 1px solid #d3dbe2!important"><?php// echo $total; ?></td>-->
              </tr>
            </tbody>
          </table>
          <table class="uk-table report-table">
            <tr class="uk-text-bold uk-text-small">
			  <td>Submitted date</td>
              <td data-uk-tooltip="{pos:top}" title="centre code">Country</td>
              <td>State</td>
              <!--<td data-uk-tooltip="{pos:top}" title="Student Code">Student Code</td>-->
              <td>Centre Name</td>
              <td>Name of Fee Structure</td>
              <td>Level</td>
              <td>Programme Package</td>
              <td>Fees</td>
              <td>Start Date</td>
              <td>End Date</td>
              <td>Status</td>
              <td>Action</td>
            </tr>
            <?php
				$centre_name=$_GET['centre_name'];
				$fees=$_GET['fees'];
				$status=$_GET['status'];
				$country=$_GET['country'];
				$level=$_GET['level'];
				$pp=$_GET['pp'];
				$name=$_GET['name'];
				//echo $cm;
            //$sql = "SELECT * from fee_structure where 1=1 ";
            $sql = "SELECT f.*, c.company_name, c.country, c.state from `fee_structure` f INNER JOIN `centre` c on f.`centre_code`=c.`centre_code` ";
			
			 if($centre_name!=""){
				$sql=$sql."and c.company_name like '%$centre_name%' ";   
			  }
			  if($fees!=""){
				  $sql=$sql."and f.adjusted like '%$fees%' ";
			  }
			  if($status!=""){
				  //echo $status;
				  $sql=$sql."and f.status like '%$status%' ";
				  
			  }
			  if($country!=""){
				  $sql=$sql."and c.country like '%$country%' ";
			  }
			  if($level!=""){
				  $sql=$sql."and f.subject like '%$level%' ";
			  }
			  if($pp!=""){
				  $sql=$sql."and f.programme_package like '%$pp%' ";
			  }
			  if($name!=""){
				  $sql=$sql."and f.fees_structure like '%$name%' ";
			  }
			  $sql=$sql." ORDER BY `submission_date` DESC ";
	   //echo $sql; 
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
								<td><?php echo $browse_row["submission_date"]?></td>
								<td><?php echo $browse_row["country"]?></td>
								<td><?php echo $browse_row["state"]?></td>
								<td><?php echo $browse_row["company_name"]?></td>
                                <td><?php echo $browse_row["fees_structure"]?></td>
								<td><?php echo $browse_row["subject"]?></td>
								<td><?php echo $browse_row["programme_package"]?></td>
								<td><?php echo $browse_row["adjusted"]?></td>
                                <td><?php echo $browse_row["from_date"]?></td>
								<td><?php echo $browse_row["to_date"]?></td>
								<td><?php echo $browse_row["status"]?></td>  
                <td style="width:120px">
                                  
                                          <a href="index.php?p=rptFeeSettings&id=<?php echo $sha1_id?>&mode=VIEW" data-uk-tooltip title="View"><img
                                style="width:20px;margin-left: 12px;" src="images/request.png"></a>
                                        
                                      
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
        <?php
        echo $pagination;
        ?>

      </div>
      </div>

      <form name="frmDeleteRecord" id="frmDeleteRecord" method="get">
        <input type="hidden" name="p" value="<?php echo $p ?>">
        <input type="hidden" name="m" value="<?php echo $m ?>">
        <input type="hidden" name="id" id="id" value="">
        <input type="hidden" name="mode" value="DEL">
      </form>

  <?php
      if ($msg != "") {
        echo "<script>UIkit.notify('$msg')</script>";
      }
    } else {
      echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
    }
  } else {
    header("Location: index.php");
  }
  ?>
  <script>
	function printDiv(print_1) {
        // $('input[type=number]' ).each(function () {
			// var cell = $(this);
			// cell.replaceWith('<span>'  + cell.val() +'</span> ');
		// });
		$('input[type=number]' ).each(function () {
			var edp_tsd ="edp_tsd";
			var cell = $(this);
			cell.replaceWith("<input type=\"number\" class=\"" + edp_tsd + "\" value=\"" + + cell.val() + "\" />");
		});
		 var printContents = document.getElementById(print_1).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }
		window.onafterprint = function() {
        window.location.reload(true);
    };
  </script>