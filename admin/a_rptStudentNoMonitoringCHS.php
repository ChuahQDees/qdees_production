<?php
session_start();
$session_id=session_id();
$year=$_SESSION["Year"];

include_once("../mysql.php");
include_once("functions.php");
include_once("../search_new.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //$from_date
}
$current_date = date('Y-m-d');
$centre_code = (isset($centre_code) && $centre_code != '') ? $centre_code : 'ALL';

if($centre_code != 'ALL') {
    $total_no_of_pages = 1;
} else {

    if (!isset($page_no) || $page_no=="") {
        $page_no = 1;
    }
    
    $total_records_per_page = 10;

    if ($chked == "1"){ //Checkbox is checked
        $total_records_per_page = 999;
    }
    
    $offset = ($page_no-1) * $total_records_per_page;
    $previous_page = $page_no - 1;
    $next_page = $page_no + 1;
    $adjacents = "2";

    $result_count = mysqli_query($connection,"SELECT COUNT(*) As total_records FROM `centre` WHERE `status` = 'A'");
    $total_records = mysqli_fetch_array($result_count);
    $total_records = $total_records['total_records'];
    $total_no_of_pages = ceil($total_records / $total_records_per_page);
    $second_last = $total_no_of_pages - 1; // total pages minus 1

    if($total_no_of_pages == $page_no) {
      
    }

}

if ($method == "print") {
  include_once("../uikit1.php");
}

$from_date = convertDate2ISO($from_date);
$to_date = convertDate2ISO($to_date);

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

$centre_list = mysqli_query($connection,"SELECT * from `centre` WHERE `status` = 'A' LIMIT $offset, $total_records_per_page");

if($centre_code != 'ALL') {
    $centre_list = mysqli_query($connection,"SELECT * FROM `centre` WHERE `status` = 'A' AND `centre_code` = '$centre_code'");
}
  
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

//Total Amounts
$var0Total = 0; $var1Total= 0; $var2Total= 0; $var3Total= 0;

$zhihuiQF1Total= 0; $zhihuiQF2Total= 0; $zhihuiQF3Total= 0;

$jawiQF1Total= 0; $jawiQF2Total= 0; $jawiQF3Total= 0;

$enhFoundQF1Total= 0; $enhFoundQF2Total= 0; $enhFoundQF3Total= 0;

$enhFoundMandQF1Total= 0; $enhFoundMandQF2Total= 0; $enhFoundMandQF3Total= 0;

$intArtQF1Total= 0; $intArtQF2Total= 0; $intArtQF3Total= 0;

$IQMathQF1Total= 0; $IQMathQF2Total= 0; $IQMathQF3Total= 0;

$AfProgBasicTotal= 0; $robotJrTotal= 0; $robotSrTotal= 0;

$EFPlusJRTotal= 0; $EFPlusSRTotal= 0;
//////////////////////////
?>

<?php
if ($_SESSION["isLogin"]==1) {
?>
<div class="uk-margin-right">

    <div class="uk-width-1-1 myheader text-center mt-5" style="color:white; text-align:center;">
        <h2 class="uk-text-center myheader-text-color myheader-text-style">Student No Monitoring Report </h2><h3 class="uk-text-center myheader-text-color myheader-text-style">(Foundation, Enh Foundation, Afternoon Prog and EF Plus)</h3>
    </div>

<div class="uk-overflow-container">
<div class="uk-grid">
  <div class="uk-width-medium-5-10">
    <table class="uk-table">
      <tr>
        <td class="uk-text-bold">Centre Name</td>
        <td>
          <?php echo ($centre_code == 'ALL') ? 'All Centres' : getCentreName($centre_code); ?>
          </td>
        </tr>
        <tr>
          <td class="uk-text-bold">Prepare By</td>
          <td><?php echo $_SESSION["UserName"] ?></td>
        </tr>
        <tr id="note">
          <td colspan="1" class="uk-text-bold">
            <input type="checkbox" name="halfDay" id="halfDay" class="uk-checkbox" checked/>
            <label for="halfDay">Half Day Programme</label>
          </td>
          <td colspan="1" class="uk-text-bold">
            <input type="checkbox" name="enhFoundation" id="enhFoundation" class="uk-checkbox" checked/>
            <label for="enhFoundation">Enhanced Foundation</label>
          </td>
        </tr>
      </table>
    </div>
    <div class="uk-width-medium-5-10">
      <table class="uk-table">
        <tr>
          <td class="uk-text-bold">Academic Year</td>
          <?php if ($selected_year) : ?>
            <td><?php echo $selected_year ?></td>
          <?php else : ?>
            <td><?php echo $_SESSION['Year']; ?></td>
          <?php endif; ?>
        </tr>
        <tr>
          <td class="uk-text-bold">Date of submission</td>
          <td><?php echo date("Y-m-d H:i:s") ?></td>
        </tr>
        <td colspan="1" class="uk-text-bold">
            <input type="checkbox" name="afterProg" id="afterProg" class="uk-checkbox" checked/>
            <label for="afterProg">Afternoon Programme</label>
          </td>
          <td colspan="1" class="uk-text-bold">
            <input type="checkbox" name="efPlus" id="efPlus" class="uk-checkbox" checked/>
            <label for="efPlus">EF Plus</label>
          </td>
      </table>
    </div>
  </div>

<?php
  $user_name=$_SESSION["UserName"];
?>
        <div class='uk-margin-top'>
       
        <style>
          .rpt-table td {
            border: 1px solid black;
            }

            /* Strikout table */
            table {
                border-collapse: collapse;
            }

            td {
                position: relative;
                padding: 5px 10px;
            }

            tr.strikeout td:before {
                content: " ";
                position: absolute;
                top: 50%;
                left: 0;
                /*border-bottom: 1px solid #9E9E9E;*/
                width: 100%;
            }
        </style>

        <table class='uk-table rpt-table'>
            <tr class='uk-text-small uk-text-bold text-center'>
              <td rowspan="3" style="background-color: #0D0D0D; color: #FFFFFF;" >No.</td>
              <td rowspan="3" style="background-color: #0D0D0D; color: #FFFFFF;" >Centre</td>
              <td colspan="13" class="halfdayColumn" style="background-color: #0D0D0D; color: #FFFFFF;" >Half Day Prog</td>
              <td colspan="17" class="enhColumn" style="background-color: #0D0D0D; color: #FFFFFF;" >Enh Foundation</td>
              <td colspan="4" class="afColumn" style="background-color: #0D0D0D; color: #FFFFFF;" >Afternoon Prog</td>
              <td colspan="3" class="plusColumn" style="background-color: #0D0D0D; color: #FFFFFF;" >EF Plus</td>
            </tr>
            <tr class='uk-text-small uk-text-bold text-center'>
              <td colspan="5" class="halfdayColumn" style="background-color: #A9E2BE;" >Foundation</td>
              <td colspan="4" class="halfdayColumn" style="background-color: #A9E2BE;" >Zhi Hui Mandarin</td>
              <td colspan="4" class="halfdayColumn" style="background-color: #A9E2BE;" >Pendidikan Jawi</td>

              <td colspan="4" class="enhColumn" style="background-color: #F19697;" >English</td>
              <td colspan="4" class="enhColumn" style="background-color: #F19697;" >Mandarin</td>
              <td colspan="4" class="enhColumn" style="background-color: #F19697;" >Int Art</td>
              <td colspan="4" class="enhColumn" style="background-color: #F19697;" >IQ Maths</td>
              <td rowspan="2" class="enhColumn" style="background-color: #F19697;" >Grand Total</td>

              <td rowspan="2" class="afColumn" style="background-color: #E2EE9F;" >Basic</td>
              <td colspan="3" class="afColumn" style="background-color: #E2EE9F;" >Basic + Robotics</td>
              
              <td colspan="3" class="plusColumn" style="background-color: #B9ADE3;" >Robotics</td>
            </tr>
            <tr class='uk-text-small uk-text-bold text-center'>
                <td class="halfdayColumn" style="background-color: #CFEFDB;">EDP</td>
                <td class="halfdayColumn" style="background-color: #CFEFDB;">QF1</td>
                <td class="halfdayColumn" style="background-color: #CFEFDB;">QF2</td>
                <td class="halfdayColumn" style="background-color: #CFEFDB;">QF3</td>
                <td class="halfdayColumn" style="background-color: #A9E2BE;">Total</td>
                <td class="halfdayColumn" style="background-color: #CFEFDB;">QF1</td>
                <td class="halfdayColumn" style="background-color: #CFEFDB;">QF2</td>
                <td class="halfdayColumn" style="background-color: #CFEFDB;">QF3</td>
                <td class="halfdayColumn" style="background-color: #A9E2BE;">Total</td>
                <td class="halfdayColumn" style="background-color: #CFEFDB;">QF1</td>
                <td class="halfdayColumn" style="background-color: #CFEFDB;">QF2</td>
                <td class="halfdayColumn" style="background-color: #CFEFDB;">QF3</td>
                <td class="halfdayColumn" style="background-color: #A9E2BE;">Total</td>
                <td class="enhColumn" style="background-color: #F8C9CA;">QF1</td>
                <td class="enhColumn" style="background-color: #F8C9CA;">QF2</td>
                <td class="enhColumn" style="background-color: #F8C9CA;">QF3</td>
                <td class="enhColumn" style="background-color: #F19697;">Total</td>
                <td class="enhColumn" style="background-color: #F8C9CA;">QF1</td>
                <td class="enhColumn" style="background-color: #F8C9CA;">QF2</td>
                <td class="enhColumn" style="background-color: #F8C9CA;">QF3</td>
                <td class="enhColumn" style="background-color: #F19697;">Total</td>
                <td class="enhColumn" style="background-color: #F8C9CA;">QF1</td>
                <td class="enhColumn" style="background-color: #F8C9CA;">QF2</td>
                <td class="enhColumn" style="background-color: #F8C9CA;">QF3</td>
                <td class="enhColumn" style="background-color: #F19697;">Total</td>
                <td class="enhColumn"style="background-color: #F8C9CA;">QF1</td>
                <td class="enhColumn" style="background-color: #F8C9CA;">QF2</td>
                <td class="enhColumn" style="background-color: #F8C9CA;">QF3</td>
                <td class="enhColumn" style="background-color: #F19697;">Total</td>
                <td class="afColumn" style="background-color: #EFF6CC;">Junior</td>
                <td class="afColumn" style="background-color: #EFF6CC;">Senior</td>
                <td class="afColumn" style="background-color: #E2EE9F;">Total</td>
                <td class="plusColumn" style="background-color: #D9D3F0;">Junior</td>
                <td class="plusColumn" style="background-color: #D9D3F0;">Senior</td>
                <td class="plusColumn" style="background-color: #B9ADE3;">Total</td>
            </tr>

          <?php 
            if($centre_code == 'ALL') {
              $centre = mysqli_query($connection,"SELECT * FROM `centre` WHERE `status` = 'A' LIMIT $offset, $total_records_per_page");
            } else {
              $centre = mysqli_query($connection,"SELECT * FROM `centre` WHERE `status` = 'A' AND `centre_code` = '$centre_code'");
            }
            
            $i = 0;
            if($page_no > 1) 
            {
                $i = (($page_no-1)*10);
            }

            $foundation_EDP_grand_total = $foundation_QF1_grand_total = $foundation_QF2_grand_total = $foundation_QF3_grand_total = $zhi_hui_mandarin_QF1_grand_total = $zhi_hui_mandarin_QF2_grand_total = $zhi_hui_mandarin_QF3_grand_total = $pendidikan_QF1_grand_total = $pendidikan_QF2_grand_total = $pendidikan_QF3_grand_total = $english_QF1_grand_total = $english_QF2_grand_total = $english_QF3_grand_total = $art_QF1_grand_total = $art_QF2_grand_total = $art_QF3_grand_total = $iq_QF1_grand_total = $iq_QF2_grand_total = $iq_QF3_grand_total = $mandarin_QF1_grand_total = $mandarin_QF2_grand_total = $mandarin_QF3_grand_total = $basic_grand_total = $basic_robotic_junior_grand_total = $basic_robotic_senior_grand_total = $robotic_junior_grand_total = $robotic_senior_grand_total = 0;

            $foundation_EDP_final_total = $foundation_QF1_final_total = $foundation_QF2_final_total = $foundation_QF3_final_total = $zhi_hui_mandarin_QF1_final_total = $zhi_hui_mandarin_QF2_final_total = $zhi_hui_mandarin_QF3_final_total = $pendidikan_QF1_final_total = $pendidikan_QF2_final_total = $pendidikan_QF3_final_total = $english_QF1_final_total = $english_QF2_final_total = $english_QF3_final_total = $art_QF1_final_total = $art_QF2_final_total = $art_QF3_final_total = $iq_QF1_final_total = $iq_QF2_final_total = $iq_QF3_final_total = $mandarin_QF1_final_total = $mandarin_QF2_final_total = $mandarin_QF3_final_total = $basic_final_total = $basic_robotic_junior_final_total = $basic_robotic_senior_final_total = $robotic_junior_final_total = $robotic_senior_final_total = 0;
            
            $enh_foundation_grand_total = 0;

            $year_data = mysqli_fetch_array(mysqli_query($connection,"SELECT MIN(`term_start`) AS `start_date`, MAX(`term_end`) AS `end_date` FROM `schedule_term` WHERE `year` = '".$_SESSION['Year']."' GROUP BY `year`"));

            $year_start_date = $year_data['start_date'];
            $year_end_date = $year_data['end_date'];

            if($year_start_date == '')
            {
                if($_SESSION['Year'] == '2022-2023')
                {
                $year_data = mysqli_fetch_array(mysqli_query($connection,"SELECT MIN(`term_start`) AS `start_date`, MAX(`term_end`) AS `end_date` FROM `schedule_term` WHERE `year` = '2022' GROUP BY `year`"));

                $year_start_date = $year_data['start_date'];
                $year_end_date = $year_data['end_date'];
                }
                else if($_SESSION['Year'] == '2022')
                {
                $year_data = mysqli_fetch_array(mysqli_query($connection,"SELECT MIN(`term_start`) AS `start_date`, MAX(`term_end`) AS `end_date` FROM `schedule_term` WHERE `year` = '2022-2023' GROUP BY `year`"));

                $year_start_date = $year_data['start_date'];
                $year_end_date = $year_data['end_date'];
                }
            }

            $academic_year_start = $year_start_date;
            $academic_year_end = $year_end_date;
            $date_start = $year_start_date;
            $date_end = $year_end_date;

/*
            while($centre_row = mysqli_fetch_array($centre))
            {
                $centre_code = $centre_row['centre_code'];

                $foundation_total = $zhi_hui_mandarin_total = $english_total = $art_total = $iq_total = $mandarin_total = $basic_robotic_total = $robotic_total = $pendidikan_total = $enh_foundation_total = 0;

                $year_data = mysqli_fetch_array(mysqli_query($connection,"SELECT MIN(`term_start`) AS `start_date`, MAX(`term_end`) AS `end_date` FROM `schedule_term` WHERE `year` = '".$_SESSION['Year']."' AND `centre_code` = '".$centre_code."' GROUP BY `year`"));

                $year_start_date = $year_data['start_date'];
                $year_end_date = $year_data['end_date'];

                if($year_start_date == '')
                {
                  if($_SESSION['Year'] == '2022-2023')
                  {
                    $year_data = mysqli_fetch_array(mysqli_query($connection,"SELECT MIN(`term_start`) AS `start_date`, MAX(`term_end`) AS `end_date` FROM `schedule_term` WHERE `year` = '2022' AND `centre_code` = '".$centre_code."' GROUP BY `year`"));

                    $year_start_date = $year_data['start_date'];
                    $year_end_date = $year_data['end_date'];
                  }
                  else if($_SESSION['Year'] == '2022')
                  {
                    $year_data = mysqli_fetch_array(mysqli_query($connection,"SELECT MIN(`term_start`) AS `start_date`, MAX(`term_end`) AS `end_date` FROM `schedule_term` WHERE `year` = '2022-2023' AND `centre_code` = '".$centre_code."' GROUP BY `year`"));

                    $year_start_date = $year_data['start_date'];
                    $year_end_date = $year_data['end_date'];
                  }
                }

                $i++;
                */
        ?>

<?php 
//CHS: Custom Function
    while($row = mysqli_fetch_array($centre_list)) {
        array_push($centrecode_array, $row['centre_code']);
        array_push($centrename_array, $row['company_name']);
        //$centrecode_array[] = $row['centre_code'];
        //$centrename_array[] = $row['company_name'];
        } 
        
        $i = 0;

        while($i < count($centrecode_array)){
        $validCenter = "true";
        
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
            if ($row['student_entry_level'] == "EDP"){ 
                $var0 = $row['total']; 
                $var0Total += $row['total'];
            }
            if ($row['student_entry_level'] == "QF1"){ 
                $var1 = $row['total']; 
                $var1Total += $row['total'];
            }
            if ($row['student_entry_level'] == "QF2"){
                $var2 = $row['total']; 
                $var2Total += $row['total'];
            }
            if ($row['student_entry_level'] == "QF3"){ 
                $var3 = $row['total']; 
                $var3Total += $row['total'];
            }
        }

        if (($var0 + $var1 + $var2 + $var3) == 0){
            $validCenter = "false";
        }

        if ($validCenter == "true"){

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
                    if ($row['student_entry_level'] == "QF1"){ 
                        $zhihuiQF1 = $row['total'];
                        $zhihuiQF1Total += $row['total'];
                    }
                    if ($row['student_entry_level'] == "QF2"){ 
                        $zhihuiQF2 = $row['total'];
                        $zhihuiQF2Total += $row['total'];
                    }
                    if ($row['student_entry_level'] == "QF3"){ 
                        $zhihuiQF3 = $row['total'];
                        $zhihuiQF3Total += $row['total'];
                    }
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
                    if ($row['student_entry_level'] == "QF1"){ 
                        $jawiQF1 = $row['total'];
                        $jawiQF1Total += $row['total'];
                    }
                    if ($row['student_entry_level'] == "QF2"){ 
                        $jawiQF2 = $row['total'];
                        $jawiQF2Total += $row['total'];
                    }
                    if ($row['student_entry_level'] == "QF3"){ 
                        $jawiQF3 = $row['total'];
                        $jawiQF3Total += $row['total'];
                    }
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
                    if ($row['student_entry_level'] == "QF1"){ 
                        $enhFoundQF1 = $row['total'];
                        $enhFoundQF1Total += $row['total'];
                    }
                    
                    if ($row['student_entry_level'] == "QF2"){ 
                        $enhFoundQF2 = $row['total'];
                        $enhFoundQF2Total += $row['total'];
                    }
                    
                    if ($row['student_entry_level'] == "QF3"){ 
                        $enhFoundQF3 = $row['total'];
                        $enhFoundQF3Total += $row['total'];
                    }
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
                    if ($row['student_entry_level'] == "QF1"){
                        $enhFoundMandQF1 = $row['total'];
                        $enhFoundMandQF1Total += $row['total'];
                    } 
                    if ($row['student_entry_level'] == "QF2"){
                        $enhFoundMandQF2 = $row['total'];
                        $enhFoundMandQF2Total += $row['total'];
                    } 
                    if ($row['student_entry_level'] == "QF3"){
                        $enhFoundMandQF3 = $row['total'];
                        $enhFoundMandQF3Total += $row['total'];
                    }
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
                    if ($row['student_entry_level'] == "QF1"){ 
                        $intArtQF1 = $row['total'];
                        $intArtQF1Total += $row['total'];
                    }
                    if ($row['student_entry_level'] == "QF2"){ 
                        $intArtQF2 = $row['total'];
                        $intArtQF2Total += $row['total'];
                    }
                    if ($row['student_entry_level'] == "QF3"){ 
                        $intArtQF3 = $row['total'];
                        $intArtQF3Total += $row['total'];
                    }
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
                    if ($row['student_entry_level'] == "QF1"){
                        $IQMathQF1 = $row['total'];
                        $IQMathQF1Total += $row['total'];
                    } 

                    if ($row['student_entry_level'] == "QF2"){
                        $IQMathQF2 = $row['total'];
                        $IQMathQF2Total += $row['total'];
                    } 

                    if ($row['student_entry_level'] == "QF3"){
                        $IQMathQF3 = $row['total'];
                        $IQMathQF3Total += $row['total'];
                    } 
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
            ) ab");
            
            while ($row = mysqli_fetch_assoc($entry_get)) {
                $AfProgBasic = $row['total'];
                $AfProgBasicTotal += $row['total'];
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
                    if ($row['student_entry_level'] == "EDP"){
                            $robotJr += $row['total'];
                            $robotJrTotal += $row['total'];
                    }
                    if ($row['student_entry_level'] == "QF1"){ 
                        $robotJr += $row['total'];
                        $robotJrTotal += $row['total'];
                    }
                    if ($row['student_entry_level'] == "QF2"){
                        $robotSr += $row['total'];
                        $robotSrTotal += $row['total'];
                    }

                    if ($row['student_entry_level'] == "QF3"){ 
                        $robotSr += $row['total'];
                        $robotSrTotal += $row['total'];
                    }
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
                    if ($row['student_entry_level'] == "EDP"){ 
                        $EFPlusJR += $row['total'];
                        $EFPlusJRTotal += $row['total'];
                    }
                    if ($row['student_entry_level'] == "QF1"){ 
                        $EFPlusJR += $row['total'];
                        $EFPlusJRTotal += $row['total'];
                    }
                    if ($row['student_entry_level'] == "QF2"){
                        $EFPlusSR += $row['total'];
                        $EFPlusSRTotal += $row['total'];
                    }
                    if ($row['student_entry_level'] == "QF3"){ 
                        $EFPlusSR += $row['total'];
                        $EFPlusSRTotal += $row['total'];
                    }
            }

        }
        
            if ($counterInit == false){
                $counter = $offset + 1;
                $counterInit = true;
            }else{
                $counter++;
            }
			//$visibility = banFunction($centrename_array[$i]);
			?>
                <tr <?php if ($validCenter == "false"){ ?>class="strikeout" style="background-color: #F0F0F0; color: #707070;" <?php } ?> >
                    <td style="background-color: #0D0D0D; color: #FFFFFF;"><b><?php echo $counter; ?></b></td>
                    <td <?php if ($validCenter == "true"){ ?> style="background-color: #A9E2BE;" <?php }else {?> style="background-color: #dadbda;" <?php } ?> ><b><?php echo $centrename_array[$i] ?></b></td>
                    <!-- Foundation Start -->
                    <td class="halfdayColumn" <?php if ($validCenter == "true"){ ?> style="background-color: #CFEFDB;" <?php }?> ><?php echo $var0; ?></td>
                    <td class="halfdayColumn" <?php if ($validCenter == "true"){ ?> style="background-color: #CFEFDB;" <?php }?> ><?php echo $var1; ?></td>
                    <td class="halfdayColumn" <?php if ($validCenter == "true"){ ?> style="background-color: #CFEFDB;" <?php }?> ><?php echo $var2; ?></td>
                    <td class="halfdayColumn" <?php if ($validCenter == "true"){ ?> style="background-color: #CFEFDB;" <?php }?> ><?php echo $var3; ?></td>
                    <td class="halfdayColumn" <?php if ($validCenter == "true"){ ?> style="background-color: #A9E2BE;" <?php }else {?> style="background-color: #dadbda;" <?php } ?>><b><?php echo ($var1 + $var2 + $var3 + $var0) ?></b></td>
                    <!-- Foundation End -->
                    <!-- Zhi Hui Mandrin Start -->
                    <td class="halfdayColumn" <?php if ($validCenter == "true"){ ?> style="background-color: #CFEFDB;" <?php }?> ><?php echo $zhihuiQF1; ?></td>
                    <td class="halfdayColumn" <?php if ($validCenter == "true"){ ?> style="background-color: #CFEFDB;" <?php }?> ><?php echo $zhihuiQF2; ?></td>
                    <td class="halfdayColumn" <?php if ($validCenter == "true"){ ?> style="background-color: #CFEFDB;" <?php }?> ><?php echo $zhihuiQF3; ?></td>
                    <td class="halfdayColumn" <?php if ($validCenter == "true"){ ?> style="background-color: #A9E2BE;" <?php }else {?> style="background-color: #dadbda;" <?php } ?> ><b><?php echo ($zhihuiQF1 + $zhihuiQF2 + $zhihuiQF3); ?></b></td>
                    <!-- Zhi Hui Mandrin End -->
                    <!-- Pendidikan Jawi Start -->
                    <td class="halfdayColumn" <?php if ($validCenter == "true"){ ?> style="background-color: #CFEFDB;" <?php }?> ><?php echo $jawiQF1; ?></td>
                    <td class="halfdayColumn" <?php if ($validCenter == "true"){ ?> style="background-color: #CFEFDB;" <?php }?> ><?php echo $jawiQF2; ?></td>
                    <td class="halfdayColumn" <?php if ($validCenter == "true"){ ?> style="background-color: #CFEFDB;" <?php }?> ><?php echo $jawiQF3; ?></td>
                    <td class="halfdayColumn" <?php if ($validCenter == "true"){ ?> style="background-color: #A9E2BE;" <?php }else {?> style="background-color: #dadbda;" <?php } ?> ><b><?php echo ($jawiQF1 + $jawiQF2 + $jawiQF3); ?></b></td>
                    <!-- Pendidikan Jawi End -->
                    <!-- English Start -->
                    <td class="enhColumn" <?php if ($validCenter == "true"){ ?> style="background-color: #F8C9CA;" <?php }?> ><?php echo $enhFoundQF1; ?></td>
                    <td class="enhColumn" <?php if ($validCenter == "true"){ ?> style="background-color: #F8C9CA;" <?php }?> ><?php echo $enhFoundQF2; ?></td>
                    <td class="enhColumn" <?php if ($validCenter == "true"){ ?> style="background-color: #F8C9CA;" <?php }?> ><?php echo $enhFoundQF3; ?></td>
                    <td class="enhColumn" <?php if ($validCenter == "true"){ ?> style="background-color: #F19697;" <?php }else {?> style="background-color: #dadbda;" <?php } ?> ><b><?php echo ($enhFoundQF1 + $enhFoundQF2 + $enhFoundQF3); ?></b></td>
                    <!-- English End -->
                    <!-- Mandarin Start -->
                    <td class="enhColumn" <?php if ($validCenter == "true"){ ?> style="background-color: #F8C9CA;" <?php }?> ><?php echo $enhFoundMandQF1; ?></td>
                    <td class="enhColumn" <?php if ($validCenter == "true"){ ?> style="background-color: #F8C9CA;" <?php }?> ><?php echo $enhFoundMandQF2; ?></td>
                    <td class="enhColumn" <?php if ($validCenter == "true"){ ?> style="background-color: #F8C9CA;" <?php }?> ><?php echo $enhFoundMandQF3; ?></td>
                    <td class="enhColumn" <?php if ($validCenter == "true"){ ?> style="background-color: #F19697;" <?php }else {?> style="background-color: #dadbda;" <?php } ?> ><b><?php echo ($enhFoundMandQF1 + $enhFoundMandQF2 + $enhFoundMandQF3); ?></b></td>
                    <!-- Mandarin End -->
                    <!-- Int Art Start -->
                    <td class="enhColumn" <?php if ($validCenter == "true"){ ?> style="background-color: #F8C9CA;" <?php }?> ><?php echo $intArtQF1; ?></td>
                    <td class="enhColumn" <?php if ($validCenter == "true"){ ?> style="background-color: #F8C9CA;" <?php }?> ><?php echo $intArtQF2; ?></td>
                    <td class="enhColumn" <?php if ($validCenter == "true"){ ?> style="background-color: #F8C9CA;" <?php }?> ><?php echo $intArtQF3; ?></td>
                    <td class="enhColumn" <?php if ($validCenter == "true"){ ?> style="background-color: #F19697;" <?php }else {?> style="background-color: #dadbda;" <?php } ?> ><b><?php echo ($intArtQF1 + $intArtQF2 + $intArtQF3); ?></b></td>
                    <!-- Int Art End -->
                    <!-- IQ Maths Start -->
                    <td class="enhColumn" <?php if ($validCenter == "true"){ ?> style="background-color: #F8C9CA;" <?php }?> ><?php echo $IQMathQF1; ?></td>
                    <td class="enhColumn" <?php if ($validCenter == "true"){ ?> style="background-color: #F8C9CA;" <?php }?> ><?php echo $IQMathQF2; ?></td>
                    <td class="enhColumn" <?php if ($validCenter == "true"){ ?> style="background-color: #F8C9CA;" <?php }?> ><?php echo $IQMathQF3; ?></td>
                    <td class="enhColumn" <?php if ($validCenter == "true"){ ?> style="background-color: #F19697;" <?php }else {?> style="background-color: #dadbda;" <?php } ?> ><b><?php echo ($IQMathQF1 + $IQMathQF2 + $IQMathQF3); ?></b></td>
                    <!-- IQ Maths End -->
                    <td class="enhColumn" <?php if ($validCenter == "true"){ ?> style="background-color: #F19697;" <?php }else {?> style="background-color: #dadbda;" <?php } ?> ><b><?php echo ($enhFoundQF1 + $enhFoundQF2 + $enhFoundQF3)
                    + ($enhFoundMandQF1 + $enhFoundMandQF2 + $enhFoundMandQF3)
                    + ($intArtQF1 + $intArtQF2 + $intArtQF3)
                    + ($IQMathQF1 + $IQMathQF2 + $IQMathQF3);?></b></td>
                    <!-- Basic Start -->
                    <td class="afColumn" <?php if ($validCenter == "true"){ ?> style="background-color: #EFF6CC;" <?php }?> ><?php echo $AfProgBasic; ?></td>
                    <!-- Basic End -->
                    <!-- Basic + Robotics Start -->
                    <td class="afColumn" <?php if ($validCenter == "true"){ ?> style="background-color: #EFF6CC;" <?php }?> ><?php echo $robotJr; ?></td>
                    <td class="afColumn" <?php if ($validCenter == "true"){ ?> style="background-color: #EFF6CC;" <?php }?> ><?php echo $robotSr; ?></td>
                    <td class="afColumn" <?php if ($validCenter == "true"){ ?> style="background-color: #E2EE9F;" <?php }else {?> style="background-color: #dadbda;" <?php } ?> ><b><?php echo ($robotJr + $robotSr); ?></b></td>
                    <td class="plusColumn" <?php if ($validCenter == "true"){ ?> style="background-color: #D9D3F0;" <?php }?> ><?php echo $EFPlusJR; ?></td>
                    <td class="plusColumn" <?php if ($validCenter == "true"){ ?> style="background-color: #D9D3F0;" <?php }?> ><?php echo $EFPlusSR; ?></td>
                    <td class="plusColumn" <?php if ($validCenter == "true"){ ?> style="background-color: #B9ADE3;" <?php }else {?> style="background-color: #dadbda;" <?php } ?> ><b><?php echo ($EFPlusJR + $EFPlusSR); ?></b></td>
                    <!-- Robotics End -->
                </tr>
        <?php
            $i++;

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
            }
            
          ?>
          <tr style="font-weight:bold;" >
            <td style="background-color: #0D0D0D; color: #FFFFFF;"></td>
            <td style="background-color: #0D0D0D; color: #FFFFFF;">TOTAL</td>
            <!-- Foundation -->
            <td class="halfdayColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $var0Total; ?></td>
            <td class="halfdayColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $var1Total; ?></td>
            <td class="halfdayColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $var2Total; ?></td>
            <td class="halfdayColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $var3Total; ?></td>
            <td class="halfdayColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $var0Total + $var1Total + $var2Total + $var3Total; ?></td>
            <!-- Zhi Hui -->
            <td class="halfdayColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $zhihuiQF1Total; ?></td>
            <td class="halfdayColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $zhihuiQF2Total; ?></td>
            <td class="halfdayColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $zhihuiQF3Total; ?></td>
            <td class="halfdayColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $zhihuiQF1Total + $zhihuiQF2Total + $zhihuiQF3Total; ?></td>
            <!-- Jawi -->
            <td class="halfdayColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $jawiQF1Total; ?></td>
            <td class="halfdayColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $jawiQF2Total; ?></td>
            <td class="halfdayColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $jawiQF3Total; ?></td>
            <td class="halfdayColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $jawiQF1Total + $jawiQF2Total + $jawiQF3Total; ?></td>
            <!-- Enhanced Foundation EN -->
            <td class="enhColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $enhFoundQF1Total; ?></td>
            <td class="enhColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $enhFoundQF2Total; ?></td>
            <td class="enhColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $enhFoundQF3Total; ?></td>
            <td class="enhColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $enhFoundQF1Total + $enhFoundQF2Total + $enhFoundQF3Total; ?></td>
            <!-- Enhanced Foundation Mandarin -->
            <td class="enhColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $enhFoundMandQF1Total; ?></td>
            <td class="enhColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $enhFoundMandQF2Total; ?></td>
            <td class="enhColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $enhFoundMandQF3Total; ?></td>
            <td class="enhColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $enhFoundMandQF1Total + $enhFoundMandQF2Total + $enhFoundMandQF3Total; ?></td>
            <!-- International Art -->
            <td class="enhColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $intArtQF1Total; ?></td>
            <td class="enhColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $intArtQF2Total; ?></td>
            <td class="enhColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $intArtQF3Total; ?></td>
            <td class="enhColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $intArtQF1Total + $intArtQF2Total + $intArtQF3Total; ?></td>
            <!-- IQ Maths -->
            <td class="enhColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $IQMathQF1Total; ?></td>
            <td class="enhColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $IQMathQF2Total; ?></td>
            <td class="enhColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $IQMathQF3Total; ?></td>
            <td class="enhColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $IQMathQF1Total + $IQMathQF2Total + $IQMathQF3Total; ?></td>
            <!--Afternoon Programme Basic -->
            <td class="enhColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo ($enhFoundQF1Total + $enhFoundQF2Total + $enhFoundQF3Total)
            + ($enhFoundMandQF1Total + $enhFoundMandQF2Total + $enhFoundMandQF3Total)
            + ($intArtQF1Total + $intArtQF2Total + $intArtQF3Total)
            + ($IQMathQF1Total + $IQMathQF2Total + $IQMathQF3Total);?></td>
            <!-- Afternoon Programme Jr + Sr -->
            <td class="afColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $AfProgBasicTotal; ?></td>
            <td class="afColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $robotJrTotal; ?></td>
            <td class="afColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $robotSrTotal; ?></td>
            <td class="afColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $robotJrTotal + $robotSrTotal; ?></td>
            <!-- Robotics -->
            <td class="plusColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $EFPlusJRTotal; ?></td>
            <td class="plusColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $EFPlusSRTotal; ?></td>
            <td class="plusColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $EFPlusJRTotal + $EFPlusSRTotal; ?></td>
          </tr>

          <?php if($page_no == $total_no_of_pages && $_POST['centre_code'] == 'ALL') { 
            
            //Begin calculating Grand Total
            $var0TotalG = 0;
            $var1TotalG = 0;
            $var2TotalG = 0;
            $var3TotalG = 0;

            $zhihuiQF1TotalG = 0;
            $zhihuiQF2Total = 0;
            $zhihuiQF3Total = 0;

            $jawiQF1TotalG = 0;
            $jawiQF2TotalG = 0;
            $jawiQF3Total = 0;

            $enhFoundQF1TotalG = 0;
            $enhFoundQF2TotalG = 0;
            $enhFoundQF3TotalG = 0;

            $enhFoundMandQF1G = 0;
            $enhFoundMandQF2G = 0;
            $enhFoundMandQF3G = 0;

            $intArtQF1TotalG = 0;
            $intArtQF2TotalG = 0;
            $intArtQF3TotalG = 0;

            $IQMathQF1TotalG = 0;
            $IQMathQF2TotalG = 0;
            $IQMathQF3TotalG = 0;

            $AfProgBasicTotalG = 0;

            $robotJrTotalG = 0;
            $robotSrTotalG = 0;

            $EFPlusJRTotalG = 0;
            $EFPlusSRTotalG = 0;

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
                AND ((fl.programme_date >= '".$date_start."' AND fl.programme_date <= '".$date_end."') OR (fl.programme_date_end >= '".$date_start."' 
                AND fl.programme_date_end >= '".$date_end."')) AND s.deleted = '0' 
            ) ab GROUP BY student_entry_level");
            
            while ($row = mysqli_fetch_assoc($entry_get)) {
                    if ($row['student_entry_level'] == "EDP"){ 
                        $var0TotalG += $row['total'];
                    }
                    if ($row['student_entry_level'] == "QF1"){ 
                        $var1TotalG += $row['total'];
                    }
                    if ($row['student_entry_level'] == "QF2"){
                        $var2TotalG += $row['total'];
                    }
                    if ($row['student_entry_level'] == "QF3"){ 
                        $var3TotalG += $row['total'];
                    }
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
                
                AND ((fl.programme_date >= '".$date_start."' AND fl.programme_date <= '".$date_end."') OR (fl.programme_date_end >= '".$date_start."' 
                AND fl.programme_date_end >= '".$date_end."')) AND s.deleted = '0' 
                AND fl.foundation_mandarin =1
            ) ab GROUP BY student_entry_level");
            
            while ($row = mysqli_fetch_assoc($entry_get)) {
                    if ($row['student_entry_level'] == "QF1"){ 
                        $zhihuiQF1TotalG += $row['total'];
                    }
                    if ($row['student_entry_level'] == "QF2"){ 
                        $zhihuiQF2TotalG += $row['total'];
                    }
                    if ($row['student_entry_level'] == "QF3"){ 
                        $zhihuiQF3TotalG += $row['total'];
                    }
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
                
                AND ((fl.programme_date >= '".$date_start."' AND fl.programme_date <= '".$date_end."') OR (fl.programme_date_end >= '".$date_start."' 
                AND fl.programme_date_end >= '".$date_end."')) AND s.deleted = '0' 
                and fl.pendidikan_islam=1
            ) ab GROUP BY student_entry_level");
            
            while ($row = mysqli_fetch_assoc($entry_get)) {
                    if ($row['student_entry_level'] == "QF1"){ 
                        $jawiQF1TotalG += $row['total'];
                    }
                    if ($row['student_entry_level'] == "QF2"){ 
                        $jawiQF2TotalG += $row['total'];
                    }
                    if ($row['student_entry_level'] == "QF3"){ 
                        $jawiQF3TotalG += $row['total'];
                    }
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
                
                AND ((fl.programme_date >= '".$date_start."' AND fl.programme_date <= '".$date_end."') OR (fl.programme_date_end >= '".$date_start."' 
                AND fl.programme_date_end >= '".$date_end."')) AND s.deleted = '0' 
                and fl.foundation_int_english=1
            ) ab GROUP BY student_entry_level");
            
            while ($row = mysqli_fetch_assoc($entry_get)) {
                    if ($row['student_entry_level'] == "QF1"){ 
                        $enhFoundQF1TotalG += $row['total'];
                    }
                    
                    if ($row['student_entry_level'] == "QF2"){ 
                        $enhFoundQF2TotalG += $row['total'];
                    }
                    
                    if ($row['student_entry_level'] == "QF3"){ 
                        $enhFoundQF3TotalG += $row['total'];
                    }
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
                
                AND ((fl.programme_date >= '".$date_start."' AND fl.programme_date <= '".$date_end."') OR (fl.programme_date_end >= '".$date_start."' 
                AND fl.programme_date_end >= '".$date_end."')) AND s.deleted = '0' 
                and fl.foundation_int_mandarin=1
            ) ab GROUP BY student_entry_level");
            
            while ($row = mysqli_fetch_assoc($entry_get)) {
                    if ($row['student_entry_level'] == "QF1"){
                        $enhFoundMandQF1TotalG += $row['total'];
                    } 
                    if ($row['student_entry_level'] == "QF2"){
                        $enhFoundMandQF2TotalG += $row['total'];
                    } 
                    if ($row['student_entry_level'] == "QF3"){
                        $enhFoundMandQF3TotalG += $row['total'];
                    }
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
                
                AND ((fl.programme_date >= '".$date_start."' AND fl.programme_date <= '".$date_end."') OR (fl.programme_date_end >= '".$date_start."' 
                AND fl.programme_date_end >= '".$date_end."')) AND s.deleted = '0' 
                and fl.foundation_int_art=1
            ) ab GROUP BY student_entry_level");
            
            while ($row = mysqli_fetch_assoc($entry_get)) {
                    if ($row['student_entry_level'] == "QF1"){ 
                        $intArtQF1TotalG += $row['total'];
                    }
                    if ($row['student_entry_level'] == "QF2"){ 
                        $intArtQF2TotalG += $row['total'];
                    }
                    if ($row['student_entry_level'] == "QF3"){ 
                        $intArtQF3TotalG += $row['total'];
                    }
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
                
                AND ((fl.programme_date >= '".$date_start."' AND fl.programme_date <= '".$date_end."') OR (fl.programme_date_end >= '".$date_start."' 
                AND fl.programme_date_end >= '".$date_end."')) AND s.deleted = '0' 
                and fl.foundation_iq_math=1
            ) ab GROUP BY student_entry_level");
            
            while ($row = mysqli_fetch_assoc($entry_get)) {
                    if ($row['student_entry_level'] == "QF1"){
                        $IQMathQF1TotalG += $row['total'];
                    } 

                    if ($row['student_entry_level'] == "QF2"){
                        $IQMathQF2TotalG += $row['total'];
                    } 

                    if ($row['student_entry_level'] == "QF3"){
                        $IQMathQF3TotalG += $row['total'];
                    } 
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
                
                AND ((fl.programme_date >= '".$date_start."' AND fl.programme_date <= '".$date_end."') OR (fl.programme_date_end >= '".$date_start."' 
                AND fl.programme_date_end >= '".$date_end."')) AND s.deleted = '0' 
                and fl.afternoon_programme =1 and f.basic_adjust > 0
            ) ab");
            
            while ($row = mysqli_fetch_assoc($entry_get)) {
                $AfProgBasicTotalG += $row['total'];
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
                
                AND ((fl.programme_date >= '".$date_start."' AND fl.programme_date <= '".$date_end."') OR (fl.programme_date_end >= '".$date_start."' 
                AND fl.programme_date_end >= '".$date_end."')) AND s.deleted = '0' 
                and fl.afternoon_programme =1 and f.basic_adjust < 1 and f.afternoon_robotic_adjust > 0
            ) ab GROUP BY student_entry_level");
            
            while ($row = mysqli_fetch_assoc($entry_get)) {
                    if ($row['student_entry_level'] == "EDP"){
                        $robotJrTotalG += $row['total'];
                    }
                    if ($row['student_entry_level'] == "QF1"){ 
                        $robotJrTotalG += $row['total'];
                    }
                    if ($row['student_entry_level'] == "QF2"){
                        $robotSrTotalG += $row['total'];
                    }

                    if ($row['student_entry_level'] == "QF3"){ 
                        $robotSrTotalG += $row['total'];
                    }
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
                
                AND ((fl.programme_date >= '".$date_start."' AND fl.programme_date <= '".$date_end."') OR (fl.programme_date_end >= '".$date_start."' 
                AND fl.programme_date_end >= '".$date_end."')) AND s.deleted = '0' 
                and fl.robotic_plus=1
            ) ab GROUP BY student_entry_level");
            
            while ($row = mysqli_fetch_assoc($entry_get)) {
                    if ($row['student_entry_level'] == "EDP"){ 
                        $EFPlusJRTotalG += $row['total'];
                    }
                    if ($row['student_entry_level'] == "QF1"){ 
                        $EFPlusJRTotalG += $row['total'];
                    }
                    if ($row['student_entry_level'] == "QF2"){
                        $EFPlusSRTotalG += $row['total'];
                    }
                    if ($row['student_entry_level'] == "QF3"){ 
                        $EFPlusSRTotalG += $row['total'];
                    }
            }
            ?>

            <?php if ($chked != "1"){ ?>
            <tr  style="font-weight:bold;" >
              <td style="background-color: #0D0D0D; color: #FFFFFF;"></td>
              <td style="background-color: #0D0D0D; color: #FFFFFF;">GRAND TOTAL</td>
              <td class="halfdayColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $var0TotalG ?></td>
              <td class="halfdayColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $var1TotalG ?></td>
              <td class="halfdayColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $var2TotalG ?></td>
              <td class="halfdayColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $var3TotalG ?></td>
              <td class="halfdayColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $var0TotalG + $var1TotalG + $var2TotalG + $var3TotalG ?></td>
              <td class="halfdayColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $zhihuiQF1TotalG ?></td>
              <td class="halfdayColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $zhihuiQF2TotalG ?></td>
              <td class="halfdayColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $zhihuiQF3TotalG ?></td>
              <td class="halfdayColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $zhihuiQF1TotalG + $zhihuiQF2TotalG + $zhihuiQF3TotalG ?></td>
              <td class="halfdayColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $jawiQF1TotalG ?></td>
              <td class="halfdayColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $jawiQF2TotalG ?></td>
              <td class="halfdayColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $jawiQF3TotalG ?></td>
              <td class="halfdayColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $jawiQF1TotalG + $jawiQF2TotalG + $jawiQF3TotalG ?></td>
              <td class="enhColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $enhFoundQF1TotalG ?></td>
              <td class="enhColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $enhFoundQF2TotalG ?></td>
              <td class="enhColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $enhFoundQF3TotalG ?></td>
              <td class="enhColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $enhFoundQF1TotalG + $enhFoundQF2TotalG + $enhFoundQF3TotalG ?></td>
              <td class="enhColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $enhFoundMandQF1TotalG ?></td>
              <td class="enhColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $enhFoundMandQF2TotalG ?></td>
              <td class="enhColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $enhFoundMandQF3TotalG ?></td>
              <td class="enhColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $enhFoundMandQF1TotalG + $enhFoundMandQF2TotalG + $enhFoundMandQF3TotalG ?></td>
              <td class="enhColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $intArtQF1TotalG ?></td>
              <td class="enhColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $intArtQF2TotalG ?></td>
              <td class="enhColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $intArtQF3TotalG ?></td>
              <td class="enhColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $intArtQF1TotalG + $intArtQF2TotalG + $intArtQF3TotalG ?></td>
              <td class="enhColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $IQMathQF1TotalG ?></td>
              <td class="enhColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $IQMathQF2TotalG ?></td>
              <td class="enhColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $IQMathQF3TotalG ?></td>
              <td class="enhColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $IQMathQF1TotalG + $IQMathQF2TotalG + $IQMathQF3TotalG ?></td>
              <td class="enhColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo ($enhFoundQF1TotalG + $enhFoundQF2TotalG + $enhFoundQF3TotalG)
            + ($enhFoundMandQF1TotalG + $enhFoundMandQF2TotalG + $enhFoundMandQF3TotalG)
            + ($intArtQF1TotalG + $intArtQF2TotalG + $intArtQF3TotalG)
            + ($IQMathQF1TotalG + $IQMathQF2TotalG + $IQMathQF3TotalG);?></td>
              <td class="afColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $AfProgBasicTotalG ?></td>
              <td class="afColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $robotJrTotalG ?></td>
              <td class="afColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $robotSrTotalG ?></td>
              <td class="afColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $robotJrTotalG + $robotSrTotalG ?></td>
              <td class="plusColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $EFPlusJRTotalG ?></td>
              <td class="plusColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $EFPlusSRTotalG ?></td>
              <td class="plusColumn" style="background-color: #0D0D0D; color: #FFFFFF;"><?php echo $EFPlusJRTotalG + $EFPlusSRTotalG ?></td>
            </tr>

          <?php 
            }
        }
        ?>
        </table>
        
        <div class="container">
            <?php if ($total_no_of_pages > 1) { ?>
                <div style='padding: 0px 20px 0px; '>
                    <strong class="pull-left" style="margin:20px 0;">Page <?php echo $page_no." of ".$total_no_of_pages; ?></strong>
                    <ul class="pagination pagination-lg pull-right">
                    <?php 
                        if ($total_no_of_pages <= 10 && $total_no_of_pages > 1){  	 
                            for ($counter = 1; $counter <= $total_no_of_pages; $counter++){
                                if ($counter == $page_no) {
                                    echo "<li class='active' onclick=\"generateBalReport('',$counter)\" ><a>$counter</a></li>";	
                                }else{
                                    echo "<li onclick=\"generateBalReport('',$counter)\" ><a >$counter</a></li>";
                                }
                            }
                        } elseif ($total_no_of_pages > 10){
                            if($page_no <= 4) {			
                                for ($counter = 1; $counter < 8; $counter++){		 
                                    if ($counter == $page_no) {
                                        echo "<li onclick=\"generateBalReport('',$counter)\" class='active'><a>$counter</a></li>";	
                                    }else{
                                        echo "<li onclick=\"generateBalReport('',$counter)\" ><a >$counter</a></li>";
                                    }
                                }
                                echo "<li><a>...</a></li>";
                                echo "<li onclick=\"generateBalReport('',$second_last)\" ><a >$second_last</a></li>";
                                echo "<li onclick=\"generateBalReport('',$total_no_of_pages)\" ><a >$total_no_of_pages</a></li>";

                            } elseif($page_no > 4 && $page_no < $total_no_of_pages - 4) {		 
                                
                                echo "<li onclick=\"generateBalReport('',1)\" ><a >1</a></li>";
                                echo "<li onclick=\"generateBalReport('',2)\" ><a >2</a></li>";
                                echo "<li ><a>...</a></li>";
                                for ($counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++) {		
                                    if ($counter == $page_no) {
                                        echo "<li onclick=\"generateBalReport('',$counter)\" class='active'><a>$counter</a></li>";	
                                    }else{
                                        echo "<li onclick=\"generateBalReport('',$counter)\" ><a >$counter</a></li>";
                                    }                  
                                }
                                echo "<li><a>...</a></li>";
                                echo "<li onclick=\"generateBalReport('',$second_last)\" ><a >$second_last</a></li>";
                                echo "<li onclick=\"generateBalReport('',$total_no_of_pages)\" ><a >$total_no_of_pages</a></li>";

                            } else {

                                echo "<li onclick=\"generateBalReport('',1)\" ><a >1</a></li>";
                                echo "<li onclick=\"generateBalReport('',2)\" ><a >2</a></li>";
                                echo "<li><a>...</a></li>";
                                for ($counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++) {
                                    if ($counter == $page_no) {
                                        echo "<li onclick=\"generateBalReport('',$counter)\" class='active'><a>$counter</a></li>";	
                                    }else{
                                        echo "<li onclick=\"generateBalReport('',$counter)\" ><a >$counter</a></li>";
                                    }                   
                                }
                            }
                        }
                    ?>
                </ul>
                </div>
            <?php } ?>
            
        </div>
    </div>
</div>
    <script>
    $(document).ready(function() {
        var method = '<?php echo $method ?>';
        if (method == "print") {
            window.print();
        }

        $('#halfDay').on('change', function(){
            if ($(this).prop('checked')) {
                $('.halfdayColumn').show();
            }
            else {
                $('.halfdayColumn').hide();
            }
        });

        $('#enhFoundation').on('change', function(){
            if ($(this).prop('checked')) {
                $('.enhColumn').show();
            }
            else {
                $('.enhColumn').hide();
            }
        });

        $('#afterProg').on('change', function(){
            if ($(this).prop('checked')) {
                $('.afColumn').show();
            }
            else {
                $('.afColumn').hide();
            }
        });

        $('#efPlus').on('change', function(){
            if ($(this).prop('checked')) {
                $('.plusColumn').show();
            }
            else {
                $('.plusColumn').hide();
            }
        });
    });
    </script>
<!-- <style>
  @media print{
   body{
       font-size:10%;
   }
   body {
      zoom:40%; /*or whatever percentage you need, play around with this number*/
    }
}
</style> -->
<style type="text/css" media="print">
    body {
      zoom:75%; /*or whatever percentage you need, play around with this number*/
    }
</style>
  <?php }?>