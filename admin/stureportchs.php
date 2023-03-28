<?php
session_start();
include_once("../mysql.php");
include_once("../uikit1.php");

foreach ($_GET as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);
}

//CHS Comment: Because apparently IT is also the Marketing Team now

//Edit the following dates to generate
$academic_year_start = "2023-03-01"; //Academic Year Start Date
$academic_year_end = "2024-02-29"; //Academic Year Start Date
$date_start = "2023-04-01"; //Manual Start Date
$date_end = "2023-04-30"; //Manual End Date

$param = $_GET['pw'];

//if ($id!="") {
if ($param == "kirakira") {
?>
<style>
table {
   width :100%;
   border-collapse: collapse;
}

table { border-collapse: collapse; empty-cells: show; }

td { position: relative; }

tr.strikeout td:before {
  content: " ";
  position: absolute;
  top: 50%;
  left: 0;
  border-bottom: 1px solid #111;
  width: 100%;
  background-color:#FF0000"
}

tr.strikeout td:after {
  content: "\00B7";
  font-size: 1px;
}

</style>

<?php

$centre_list = mysqli_query($connection,"SELECT * from `centre`");

$centrecode_array = array();
$centrename_array = array();

$var0 = 0;
$var1 = 0;
$var2 = 0;
$var3 = 0;

function banFunction($centreName){
	$visibilityVar = true;
	$banList = array("Starters Jln Meru, Klang",
	"Starters Bdr Seri Putra, Bangi",
	"abbas world",
	"Starters Tadika Testing B",
	"Starters Testing Centre Z",
	"Starters Taman Seri Bendahara,Kuala Selangor",
	"Starters Johor",
	"Starters Testing Error Centre",
	"Starters Testing Centre",
	"Testing",
	"Testing Sally",
	"TestingQDees",
	"Training Department",
	"Testing CHS",
	"Starters Testing Centre A",
	"Starters Sri Petaling,Kuala Lumpur",
	"Starters Mutiara Damansara,Selangor",
	"Starters USJ 1, Selangor",
	"Starters Section 22",
	"Starters Air Itam, Pulau Pinang",
	"Starters Sungai Udang, Melaka",
	"Starters Hutan Melintang,Perak",
	"Starters Foo Chow, Kuching",
	"Starters Taman Ibukota, Setapak, Kuala Lumpur",
	"Taman Sri Rampai, Kuala Lumpur",
	"Taman Maju, Jasin, Melaka",
	"Pusat centre Bergembira",
	"Centre Hebat Berjaya",
	"ChuahTesting",
	"Starters Q-dees Alor Akar ,Kuantan (Sentosa)",
	"Starters Suria Villa Kajang");
	foreach ($banList as $value) {
		if ($value == $centreName){
			$visibilityVar = false;
		}
	}

	return $visibilityVar;
}
?>

<div class="uk-margin uk-margin-top uk-margin-left uk-margin-right">	
		<h1>New Academic Year Sign-Up Report Master View</h1>
		<table>
		  <tr>
			<th>Centre Code</th>
			<th>Centre Name</th>
			<th>EDP</th>
			<th>QF1</th>
			<th>QF2</th>
			<th>QF3</th>
			<th>Total</th>
		  </tr>
		  <?php 
		  while($row = mysqli_fetch_array($centre_list)) {
			array_push($centrecode_array, $row['centre_code']);
			array_push($centrename_array, $row['company_name']);
			//$centrecode_array[] = $row['centre_code'];
			//$centrename_array[] = $row['company_name'];
			} 
			
			$i = 0;
			
			while($i < count($centrecode_array)){
			
			$entry_get = mysqli_query($connection, "SELECT student_entry_level, count(id) total
			FROM (
				SELECT DISTINCT ps.student_entry_level, s.id
				FROM student s
				INNER JOIN programme_selection ps ON ps.student_id = s.id
				INNER JOIN student_fee_list fl ON fl.programme_selection_id = ps.id
				INNER JOIN fee_structure f ON f.id = fl.fee_id
				WHERE (fl.programme_date >= '".$academic_year_start."' AND fl.programme_date <= '".$academic_year_end."') 
				AND ps.student_entry_level != '' AND s.student_status = 'A' 
				AND s.centre_code='".$centrecode_array[$i]."'
				AND ((fl.programme_date >= '".$date_start."' AND fl.programme_date <= '".$date_end."') OR (fl.programme_date_end >= '".$date_start."' 
				AND fl.programme_date_end >= '".$date_end."')) AND s.deleted = '0' 
			) ab GROUP BY student_entry_level");
			
			while ($row = mysqli_fetch_assoc($entry_get)) {
					if ($row['student_entry_level'] == "EDP") $var0 = $row['total'];
					if ($row['student_entry_level'] == "QF1") $var1 = $row['total'];
					if ($row['student_entry_level'] == "QF2") $var2 = $row['total'];
					if ($row['student_entry_level'] == "QF3") $var3 = $row['total'];
			}
			
			$visibility = banFunction($centrename_array[$i]);

			?>
		  
		  <?php if($var0 == "0" && $var1 == "0" && $var2 == "0" && $var3 == "0"){ ?>
		  <tr class="strikeout" style="background-color:#808080">
		  <?php }else{ ?>
		  <tr>
		  <?php } 
		  if ($visibility == true){
		  ?>
			<td><?php echo $centrecode_array[$i] ?></td>
			<td><?php echo $centrename_array[$i] ?></td>
			<td><?php echo $var0 ?></td>
			<td><?php echo $var1 ?></td>
			<td><?php echo $var2 ?></td>
			<td><?php echo $var3 ?></td>
			<td><b><?php echo ($var1 + $var2 + $var3 + $var0) ?></b></td>
		  </tr>
			<?php 
		  }
			$i++;
			$var0 = 0;
			$var1 = 0;
			$var2 = 0;
			$var3 = 0;
			}
			?>
		</table>
</div>

<script>
function printDialog() {
   //$("#btnPrint").hide();
   window.print();
}
<?php if(!isset($display)): ?>
$(document).ready(function () {
   //printDialog();
   opener.location.reload();
});
<?php endif ?>
</script>
<div class="uk-margin-top">
   <!--<button id="btnPrint" class="uk-button" onclick="printDialog();">Print</button>-->
</div>
<?php

} else {
   echo "Something is wrong, cannot proceed";
}
?>