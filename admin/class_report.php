<?php
session_start();
include_once("../mysql.php");
//include_once("../uikit1.php");
include_once("functions.php");

$group_id=$_GET["group_id"];
$get_class_id=$_GET["class"];
$get_course_id= $_GET["course_id"];

function getDOWString($dow) {
   switch ($dow) {
      case 0 : return "Sunday"; break;
      case 1 : return "Monday"; break;
      case 2 : return "Tuesday"; break;
      case 3 : return "Wednesday"; break;
      case 4 : return "Thursday"; break;
      case 5 : return "Friday"; break;
      case 6 : return "Saturday"; break;
   }
}

function getDOWIndex($dow) {
   switch ($dow) {
      case "Sunday": return 0; break;
      case "Monday": return 1; break;
      case "Tuesday": return 2; break;
      case "Wednesday": return 3; break;
      case "Thursday": return 4; break;
      case "Friday": return 5; break;
      case "Saturday": return 6; break;
   }
}

function getGroupInfo($group_id, &$programme, &$level, &$module, &$class, &$course_id) {
   global $connection;

   $sql="SELECT * from `group` where id='$group_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   $class=$row["class_id"];
	 $course_id=$row['course_id'];

	 return $row;
}

function getCourseName($course_id){
	global $connection;

	$sql="SELECT * from `course` where id='$course_id'";
	$result=mysqli_query($connection, $sql);
	$row=mysqli_fetch_assoc($result);

	return $row['course_name'];
}

function getCoursesList(){
	global $connection;

	$sql="SELECT `id`, `course_name` from `course` ORDER BY course_name ASC";
	$result=mysqli_query($connection, $sql);

	$courses = array();
	if($result){
		while($row=mysqli_fetch_assoc($result)){
			$courses[$row['id']] = $row['course_name'];
		};
	}

	return $courses;
}

if ($_SESSION["isLogin"]==1) {
   if (($_SESSION["UserType"]=="O") || ($_SESSION["UserType"]=="A")) {

   $course=$_POST["course"];
   $class=$_POST["class"];
   $student_name=$_POST["student_name"];

   $year=$_SESSION['Year'];
   $centre_code=$_SESSION["CentreCode"];
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css">
<link rel="stylesheet" type="text/css" href="../lib/uikit/css/uikit.gradient.min6.my.css">
<link rel="stylesheet" href="../css/my1.css">
<link rel="stylesheet" href="../css/style.css">
<style>

@media print {
  *{ color-adjust: exact; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
	#btnPrint{
		display:none;
	}
}
.uk-width-1-1.myheader {
    border-top-right-radius: 15px;
    border-top-left-radius: 15px;
    padding: 15px 0;
}
</style>

<div class="uk-margin-right uk-margin-left uk-margin-top">

	<?php if( !empty($group_id) ){ ?>
		<?php
			$group_info = getGroupInfo($group_id, $programme, $level, $module, $get_class_id, $course_id);
			$group_period=convertDate2British($group_info["start_date"])." - ".convertDate2British($group_info["end_date"]);
		?>
		 <div class="uk-width-1-1 myheader text-center">

      <h2 class="uk-text-center myheader-text-color myheader-text-style"><?php echo $_GET['subject']."-".$_GET['class']."-".$_GET['duration'] ?></h2>

    </div>

    <div class="uk-overflow-container">

      <div class="uk-grid">

         <div class="uk-width-medium-5-10">

            <table class="uk-table">

               <tr>

                  <td class="uk-text-bold">Centre Name</td>

                  <?php 

                  $sql="SELECT company_name from centre where centre_code='$centre_code'";

                  $result=mysqli_query($connection, $sql);

                  $row=mysqli_fetch_assoc($result);

                  ?>

                  <td><?php echo $centre_code != 'ALL' ? $row['company_name'] : 'All Centre';?></td>

               </tr>

               <tr>

                  <td class="uk-text-bold">Prepare By</td>

                  <td><?php echo $_SESSION["UserName"]?></td>

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

                  <td><?php echo $year?></td>

               </tr>

               <tr>

                  <td class="uk-text-bold">School Term</td>

                  <td>
						<?php
                     $month = date("m");
                     $year = $_SESSION['Year'];
                     if (isset($selected_month) && $selected_month != '') {
						$str_length = strlen($selected_month);
                        $month = substr($selected_month, ($str_length - 2), 2);
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

                  <td><?php echo date("Y-m-d H:i:s")?></td>

               </tr>

            </table>

         </div>

      </div>

    </div>

		<div style="margin-top: 40px;" class="nice-form text-center">
			<h4><b>Period:</b> <?php echo $group_period; ?></h4>
			<div class=" table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr class="uk-text-bold"><!--  class="uk-block-primary uk-text-contrast" -->
							<td style="font-weight:bold; width: 25%">Session 1</td>
							<td style="font-weight:bold; width: 25%">Session 2</td>
							<td style="font-weight:bold; width: 25%">Session 3</td>
							<td style="font-weight:bold; width: 25%">Session 4</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<?php
									if ($group_info["dow"]!=-1) {
										 echo '<div class="mt-3 mb-2 pl-1"><i class="fas fa-calendar-alt mr-2"></i>' . getDOWString($group_info["dow"]) . '</div>';
										$time=$group_info["start_time"]." - ".$group_info["end_time"];
										 echo '<div><span class="badge badge-primary mt-2" style="font-size: 14px"><i class="fas fa-clock mr-2"></i>' . $time . '</span></div>';
									}
								?>
							</td>
							<td>
								<?php
									if ($group_info["dow1"]!=-1) {
										 echo '<div class="mt-3 mb-2 pl-1"><i class="fas fa-calendar-alt mr-2"></i>' . getDOWString($group_info["dow1"]) . '</div>';
										$time=$group_info["start_time1"]." - ".$group_info["end_time1"];
										 echo '<div><span class="badge badge-primary mt-2" style="font-size: 14px"><i class="fas fa-clock mr-2"></i>' . $time . '</span></div>';
									}
								?>
							</td>
							<td>
								<?php
								if ($group_info["dow2"]!=-1) {
									 echo '<div class="mt-3 mb-2 pl-1"><i class="fas fa-calendar-alt mr-2"></i>' . getDOWString($group_info["dow2"]) . '</div>';
									$time=$group_info["start_time2"]." - ".$group_info["end_time2"];
									 echo '<div><span class="badge badge-primary mt-2" style="font-size: 14px"><i class="fas fa-clock mr-2"></i>' . $time . '</span></div>';
								}
								?>
							</td>
							<td>
								<?php
								if ($group_info["dow3"]!=-1) {
									 echo '<div class="mt-3 mb-2 pl-1"><i class="fas fa-calendar-alt mr-2"></i>' . getDOWString($group_info["dow3"]) . '</div>';
									$time=$group_info["start_time3"]." - ".$group_info["end_time3"];
									 echo '<div><span class="badge badge-primary mt-2" style="font-size: 14px"><i class="fas fa-clock mr-2"></i>' . $time . '</span></div>';
								}
								?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<br>
			<br>
<?php
$sql="SELECT s.*, group_concat(DISTINCT concat('(',sec.mobile_country_code, ') ',sec.mobile) ORDER BY  sec.id ASC SEPARATOR ', ') as contacts 
	from allocation a, student s left join student_emergency_contacts sec on sec.student_code=s.student_code 
	where a.group_id='$group_id' and course_id='$course_id'
    and a.class_id='$get_class_id' and a.year='$year' and s.id=a.student_id and a.deleted=0 and s.deleted=0
    and s.student_status='A' and s.centre_code='$centre_code' group by s.student_code";
//echo $sql;
$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);
$ethnics = [];
$genders = [];
$count=0;
?>
			<div class=" table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr class="uk-text-bold">
							<td>No</td>
							<td>Student Code</td>
							<td>Name</td>
							<td>Gender</td>
							<td>D.O.B</td>
							<td>Age</td>
							<td>Ethnicity</td>
							<td>Primary Contact</td>
							<td>Medical Condition</td>
							<td>Allergies</td>
							<td>Consent for social media</td>
						</tr>
					</thead>
					<tbody>
<?php
if ($num_row>0) {
	while ($row=mysqli_fetch_assoc($result)) {
	?>
						<tr>
							<td><?php echo $count+1 ?></td>
							<td><?php echo $row['student_code'] ?></td>
							<td><?php echo $row['name'] ?></td>
							<td class="uk-text-center"><?php echo $row['gender'] ?></td>
							<td><?php echo date('d/m/Y', strtotime($row['dob'])) ?></td>
							<td class="uk-text-center"><?php echo $row['age'] ?></td>
							<td><?php echo $row['race'] ?></td>
							<td><?php echo $row['contacts'] ?></td>
							<td><?php echo $row['health_problem'] ?></td>
							<td><?php echo $row['allergies'] ?></td>
							<td class="uk-text-center"><?php echo $row['accept_photo']==1 ? '&#10004;' : '' ?></td>
						</tr>
	<?php
		$genders[$row['gender']] = isset($genders[$row['gender']]) ? $genders[$row['gender']]+1 : 1;
		$ethnics[$row['race']] = isset($ethnics[$row['race']]) ? $ethnics[$row['race']]+1 : 1;
		$count++;
	}
} else {
?>
   <tr class="uk-text-small"><td colspan="11">No record found</td></tr>
<?php
}
?>
					</tbody>
				</table>
			</div>
<?php 	if($count>0){ ?>
			<br>
			<br>
			<div class="table-responsive">
				<table class="table table-bordered uk-text-center">
					<tr>
						<td class="uk-text-bold" rowspan="2">Total</td>
						<td class="uk-text-bold" colspan="<?php echo count($genders) ?>">Gender</td>
						<td class="uk-text-bold" colspan="<?php echo count($ethnics) ?>">Ethnicity</td>
					</tr>
					<tr>
						<?php foreach($genders as $gender=>$total){ echo '<td class="uk-text-bold">'.$gender.'</td>'; } ?>
						<?php foreach($ethnics as $ethnic=>$total){ echo '<td class="uk-text-bold">'.$ethnic.'</td>'; } ?>
					</tr>
					<tr>
						<td><?php echo $count ?></td>
						<?php foreach($genders as $total){ echo '<td>'.$total.'</td>'; } ?>
						<?php foreach($ethnics as $total){ echo '<td>'.$total.'</td>'; } ?>
					</tr>
				</table>
			</div>
		</div>

<?php 
		} 
	}
?>
<div class="uk-margin-top">
   <button id="btnPrint" class="uk-button" onclick="printDialog();">Print</button>
</div>
<br>
</div>

<script>
function printDialog() {
   window.print();
}

printDialog();
opener.location.reload();

</script>
<?php
   } else {
      echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
   }
} else {
   header("Location: ../index.php");
}
?>
</div>
<div id="dlgTransAllocation"></div>
