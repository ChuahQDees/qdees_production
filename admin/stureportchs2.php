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

//Zhi Hui
$zhihuiQF1 = 0;
$zhihuiQF2 = 0;
$zhihuiQF3 = 0;

//Jawi
$jawiQF1 = 0;
$jawiQF2 = 0;
$jawiQF3 = 0;

//Enhanced Foundation EN
$enhFoundQF1 = 0;
$enhFoundQF2 = 0;
$enhFoundQF3 = 0;

//Enhanced Foundation Mandarin
$enhFoundMandQF1 = 0;
$enhFoundMandQF2 = 0;
$enhFoundMandQF3 = 0;

//International Art
$intArtQF1 = 0;
$intArtQF2 = 0;
$intArtQF3 = 0;

//IQ Maths
$IQMathQF1 = 0;
$IQMathQF2 = 0;
$IQMathQF3 = 0;

//Afternoon Programme
$AfProgBasic = 0;
$robotJr = 0; //EDP + QF1
$robotSr = 0; //QF2 + QF3

//Enhanced Foundation Plus
$EFPlusJR = 0; //EDP + QF1
$EFPlusSR = 0; //QF2 + QF3

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
            <!-- Basic -->
			<th>Centre Code</th>
			<th>Centre Name</th>
			<th>EDP</th>
			<th>QF1</th>
			<th>QF2</th>
			<th>QF3</th>
			<th>Total</th>
            <!-- End Basic -->

            <!-- Zhi Hui -->
			<th>Zhi Hui Mandarin QF1</th>
			<th>Zhi Hui Mandarin QF2</th>
			<th>Zhi Hui Mandarin QF3</th>
            <th>Zhi Hui Mandarin Total</th>
            <!-- End Zhi Hui -->

            <!-- Jawi -->
            <th>Jawi QF1</th>
			<th>Jawi QF2</th>
			<th>Jawi QF3</th>
            <th>Jawi Total</th>
            <!-- End Jawi -->
            
            <!-- Enhanced Foundation EN -->
            <th>Enh Found EN QF1</th>
			<th>Enh Found EN QF2</th>
			<th>Enh Found EN QF3</th>
            <th>Enh Found EN Total</th>
            <!-- End Enhanced Foundation EN -->

            <!-- Enhanced Foundation Mandarin -->
            <th>Enh Found Mandarin QF1</th>
			<th>Enh Found Mandarin QF2</th>
			<th>Enh Found Mandarin QF3</th>
            <th>Enh Found Mandarin Total</th>

            <!-- Int Art -->
            <th>Int Art QF1</th>
			<th>Int Art QF2</th>
			<th>Int Art QF3</th>
            <th>Int Art Total</th>

            <!-- IQ Maths -->
            <th>IQ Maths QF1</th>
			<th>IQ Maths QF2</th>
			<th>IQ Maths QF3</th>
            <th>IQ Maths Total</th>
            <th>Grand Total</th>

            <!-- Enhanced Foundation -->
            <th>Af.Prog Basic</th>
            <th>Robot Jr</th>
            <th>Robot Sr</th>
            <th>Af.Prog Total</th>

            <!-- Enhanced Foundation Plus -->
            <th>EF+ Jr</th>
            <th>EF+ Sr</th>
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
			
            //Basic
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

            //Zhi Hui
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
                AND fl.foundation_mandarin =1
			) ab GROUP BY student_entry_level");
			
			while ($row = mysqli_fetch_assoc($entry_get)) {
					if ($row['student_entry_level'] == "QF1") $zhihuiQF1 = $row['total'];
					if ($row['student_entry_level'] == "QF2") $zhihuiQF2 = $row['total'];
					if ($row['student_entry_level'] == "QF3") $zhihuiQF3 = $row['total'];
			}

            //Jawi
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
                and fl.pendidikan_islam=1
			) ab GROUP BY student_entry_level");
			
			while ($row = mysqli_fetch_assoc($entry_get)) {
					if ($row['student_entry_level'] == "QF1") $jawiQF1 = $row['total'];
					if ($row['student_entry_level'] == "QF2") $jawiQF2 = $row['total'];
					if ($row['student_entry_level'] == "QF3") $jawiQF3 = $row['total'];
			}

            //Enhanced Foundation EN
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
                and fl.foundation_int_english=1
			) ab GROUP BY student_entry_level");
			
			while ($row = mysqli_fetch_assoc($entry_get)) {
					if ($row['student_entry_level'] == "QF1") $enhFoundQF1 = $row['total'];
					if ($row['student_entry_level'] == "QF2") $enhFoundQF2 = $row['total'];
					if ($row['student_entry_level'] == "QF3") $enhFoundQF3 = $row['total'];
			}

            //Enhanced Foundation Mandarin
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
                and fl.foundation_int_mandarin=1
			) ab GROUP BY student_entry_level");
			
			while ($row = mysqli_fetch_assoc($entry_get)) {
					if ($row['student_entry_level'] == "QF1") $enhFoundMandQF1 = $row['total'];
					if ($row['student_entry_level'] == "QF2") $enhFoundMandQF2 = $row['total'];
					if ($row['student_entry_level'] == "QF3") $enhFoundMandQF3 = $row['total'];
			}

            //Int Art
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
                and fl.foundation_int_art=1
			) ab GROUP BY student_entry_level");
			
			while ($row = mysqli_fetch_assoc($entry_get)) {
					if ($row['student_entry_level'] == "QF1") $intArtQF1 = $row['total'];
					if ($row['student_entry_level'] == "QF2") $intArtQF2 = $row['total'];
					if ($row['student_entry_level'] == "QF3") $intArtQF3 = $row['total'];
			}

            //IQ Maths
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
                and fl.foundation_iq_math=1
			) ab GROUP BY student_entry_level");
			
			while ($row = mysqli_fetch_assoc($entry_get)) {
					if ($row['student_entry_level'] == "QF1") $IQMathQF1 = $row['total'];
					if ($row['student_entry_level'] == "QF2") $IQMathQF2 = $row['total'];
					if ($row['student_entry_level'] == "QF3") $IQMathQF3 = $row['total'];
			}

            //Afternoon Prog
			//Basic
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
                and fl.afternoon_programme =1 and f.basic_adjust > 0
			) ab GROUP BY student_entry_level");
			
			while ($row = mysqli_fetch_assoc($entry_get)) {
				$AfProgBasic = $row['total'];
    		}

            //Afternoon Prog JR + SR
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
                and fl.afternoon_programme =1 and f.basic_adjust < 1 and f.afternoon_robotic_adjust > 0
            ) ab GROUP BY student_entry_level");
            
            while ($row = mysqli_fetch_assoc($entry_get)) {
					if ($row['student_entry_level'] == "EDP") $robotJr += $row['total'];
                    if ($row['student_entry_level'] == "QF1") $robotJr += $row['total'];
					if ($row['student_entry_level'] == "QF2") $robotSr += $row['total'];
                    if ($row['student_entry_level'] == "QF3") $robotSr += $row['total'];
            }

            //Enhanced Foundation Plus
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
                and fl.robotic_plus=1
            ) ab GROUP BY student_entry_level");
            
            while ($row = mysqli_fetch_assoc($entry_get)) {
					if ($row['student_entry_level'] == "EDP") $EFPlusJR += $row['total'];
                    if ($row['student_entry_level'] == "QF1") $EFPlusJR += $row['total'];
					if ($row['student_entry_level'] == "QF2") $EFPlusSR += $row['total'];
                    if ($row['student_entry_level'] == "QF3") $EFPlusSR += $row['total'];
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
            <!-- Basic -->
			<td><?php echo $centrecode_array[$i] ?></td>
			<td><?php echo $centrename_array[$i] ?></td>
			<td><?php echo $var0 ?></td>
			<td><?php echo $var1 ?></td>
			<td><?php echo $var2 ?></td>
			<td><?php echo $var3 ?></td>
			<td><b><?php echo ($var1 + $var2 + $var3 + $var0) ?></b></td>

             <!-- Zhi Hui -->
		 	<td><?php echo $zhihuiQF1 ?></td>
			<td><?php echo $zhihuiQF2 ?></td>
			<td><?php echo $zhihuiQF3 ?></td>
			<td><b><?php echo ($zhihuiQF1 + $zhihuiQF2 + $zhihuiQF3) ?></b></td>
            <!-- End Zhi Hui -->

            <!-- Jawi -->
            <td><?php echo $jawiQF1 ?></td>
			<td><?php echo $jawiQF2 ?></td>
			<td><?php echo $jawiQF3 ?></td>
			<td><b><?php echo ($jawiQF1 + $jawiQF2 + $jawiQF3) ?></b></td>
            <!-- End Jawi -->
            
            <!-- Enhanced Foundation EN -->
            <td><?php echo $enhFoundQF1 ?></td>
			<td><?php echo $enhFoundQF2 ?></td>
			<td><?php echo $enhFoundQF3 ?></td>
			<td><b><?php echo ($enhFoundQF1 + $enhFoundQF2 + $enhFoundQF3) ?></b></td>
            <!-- End Enhanced Foundation EN -->

            <!-- Enhanced Foundation Mandarin -->
            <td><?php echo $enhFoundMandQF1 ?></td>
			<td><?php echo $enhFoundMandQF2 ?></td>
			<td><?php echo $enhFoundMandQF3 ?></td>
			<td><b><?php echo ($enhFoundMandQF1 + $enhFoundMandQF2 + $enhFoundMandQF3) ?></b></td>

            <!-- Int Art -->
			<td><?php echo $intArtQF1 ?></td>
			<td><?php echo $intArtQF2 ?></td>
			<td><?php echo $intArtQF3 ?></td>
			<td><b><?php echo ($intArtQF1 + $intArtQF2 + $intArtQF3) ?></b></td>

            <!-- IQ Maths -->
            <td><?php echo $IQMathQF1 ?></td>
			<td><?php echo $IQMathQF2 ?></td>
			<td><?php echo $IQMathQF3 ?></td>
			<td><b><?php echo ($IQMathQF1 + $IQMathQF2 + $IQMathQF3) ?></b></td>
			<td><b><?php echo (($enhFoundQF1 + $enhFoundQF2 + $enhFoundQF3) + ($enhFoundMandQF1 + $enhFoundMandQF2 + $enhFoundMandQF3) + ($intArtQF1 + $intArtQF2 + $intArtQF3) + ($IQMathQF1 + $IQMathQF2 + $IQMathQF3)) ?></b></td>

            <!-- Enhanced Foundation -->
            <td><?php echo $AfProgBasic ?></td>
            <td><?php echo $robotJr ?></td>
            <td><?php echo $robotSr ?></td>
            <td><?php echo ($AfProgBasic + $robotJr + $robotSr) ?></td>

            <!-- Enhanced Foundation Plus -->
            <td><?php echo $EFPlusJR ?></td>
            <td><?php echo $EFPlusSR ?></td>
            <td><?php echo ($EFPlusJR + $EFPlusSR) ?></td>

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