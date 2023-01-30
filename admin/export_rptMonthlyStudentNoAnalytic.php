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
$selected_month = (isset($selected_month) && $selected_month != '') ? $selected_month : 'ALL';

function HalfDayQFLevelCount($student_entry_level,$program)
{
    global $connection, $year_start_date, $year_end_date, $current_date, $selected_month;

    if($program == 'Pendidikan Jawi') {

      $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.deleted='0' and ps.student_entry_level ='$student_entry_level' and fl.pendidikan_islam=1 ";

      if($selected_month != 'ALL' && $selected_month != '')
      {
        $sql .= " and '$selected_month' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
      }

      $sql .= "   group by ps.student_entry_level, s.id) ab";

    } else if($program == 'Zhi Hui Mandarin') {

      $sql="SELECT count(`collection`.`id`) as level_count from `collection` LEFT JOIN `product` ON `product`.`product_code` = `collection`.`product_code` where `collection`.`product_code` like 'MY-ZHM%' and (collection_date_time BETWEEN '$year_start_date' AND '$year_end_date') and `collection`.`void`=0 and `product`.`sub_sub_category` = '$student_entry_level'";

      if($selected_month != 'ALL' && $selected_month != '')
      {
        $sql .= " and '$selected_month' BETWEEN DATE_FORMAT(collection.collection_date_time, '%Y-%m') AND DATE_FORMAT(collection.collection_date_time, '%Y-%m') ";
      }

    } else if($program == 'Foundation') {

      if($student_entry_level == 'EDP') {

        $sql="SELECT count(`collection`.`id`) as level_count from `collection` LEFT JOIN `product` ON `product`.`product_code` = `collection`.`product_code` where (collection_date_time BETWEEN '$year_start_date' AND '$year_end_date') and `collection`.`void`=0 and (`collection`.`product_code` like 'MY-EDP1.EARLY-DIS%' OR `collection`.`product_code` like 'MY-EDP2.EARLY-DIS%' OR `collection`.`product_code` like 'MY-EDP3.EARLY-DIS%' OR `collection`.`product_code` like 'MY-EDP4.EARLY-DIS%') ";

      } else {

        $sql="SELECT count(`collection`.`id`) as level_count from `collection` LEFT JOIN `product` ON `product`.`product_code` = `collection`.`product_code` where `collection`.`product_code` like 'MY-MODULE%' and (collection_date_time BETWEEN '$year_start_date' AND '$year_end_date') and `collection`.`void`=0 and `collection`.`product_code` like '%$student_entry_level%' ";
      
      }

      if($selected_month != 'ALL' && $selected_month != '')
      {
        $sql .= " and '$selected_month' BETWEEN DATE_FORMAT(collection.collection_date_time, '%Y-%m') AND DATE_FORMAT(collection.collection_date_time, '%Y-%m') ";
      }
    } 

    $resultt=mysqli_query($connection, $sql);
    $roww=mysqli_fetch_assoc($resultt);
    return $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
}

function HalfDayEthnicityCount($Ethnicity,$program)
{
    global $connection, $year_start_date, $year_end_date, $current_date, $selected_month;

    if($program == 'Pendidikan Jawi') {

      $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.deleted='0' and s.race ='$Ethnicity' and fl.pendidikan_islam=1 ";

      if($selected_month != 'ALL' && $selected_month != '')
      {
          $sql .= " and '$selected_month' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
      }

      $sql .= "   group by ps.student_entry_level, s.id) ab";

    } else if($program == 'Zhi Hui Mandarin') {

      $sql="SELECT count(`collection`.`id`) as level_count from `collection` LEFT JOIN `product` ON `product`.`product_code` = `collection`.`product_code` LEFT JOIN `student` ON `student`.`id` = `collection`.`student_id` where `collection`.`product_code` like 'MY-ZHM%' and (collection_date_time BETWEEN '$year_start_date' AND '$year_end_date') and `collection`.`void`=0 and `student`.`race` = '$Ethnicity' ";

      if($selected_month != 'ALL' && $selected_month != '')
      {
        $sql .= " and '$selected_month' BETWEEN DATE_FORMAT(collection.collection_date_time, '%Y-%m') AND DATE_FORMAT(collection.collection_date_time, '%Y-%m') ";
      }

    } else if($program == 'Foundation') {

      $sql="SELECT count(`collection`.`id`) as level_count from `collection` LEFT JOIN `product` ON `product`.`product_code` = `collection`.`product_code` LEFT JOIN `student` ON `student`.`id` = `collection`.`student_id` where `collection`.`product_code` like 'MY-MODULE%' and (collection_date_time BETWEEN '$year_start_date' AND '$year_end_date') and `collection`.`void`=0 and `student`.`race` = '$Ethnicity' ";

      if($selected_month != 'ALL' && $selected_month != '')
      {
        $sql .= " and '$selected_month' BETWEEN DATE_FORMAT(collection.collection_date_time, '%Y-%m') AND DATE_FORMAT(collection.collection_date_time, '%Y-%m') ";
      }
    } 

    $resultt=mysqli_query($connection, $sql);
    $roww=mysqli_fetch_assoc($resultt);
    return $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
}

function EnhFoundationQFLevelCount($student_entry_level,$program)
{
    global $connection, $year_start_date, $year_end_date, $current_date, $selected_month;

    $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.deleted='0' and ps.student_entry_level ='$student_entry_level' ";

    if($program == 'English') {
        $sql .= " and fl.foundation_int_english=1 ";
    } else if($program == 'Mandarin') {
        $sql .= " and fl.foundation_int_mandarin=1 ";
    } else if($program == 'Int Art') {
        $sql .= " and fl.foundation_int_art=1 ";
    } else if($program == 'IQ Maths') {
        $sql .= " and fl.foundation_iq_math=1 ";
    }

    if($selected_month != 'ALL' && $selected_month != '')
    {
        $sql .= " and '$selected_month' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
    }

    $sql .= "   group by ps.student_entry_level, s.id) ab";

    $resultt=mysqli_query($connection, $sql);
    $roww=mysqli_fetch_assoc($resultt);
    return $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
}

function EnhFoundationEthnicityCount($Ethnicity,$program)
{
    global $connection, $year_start_date, $year_end_date, $current_date, $selected_month;

    $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.deleted='0' and s.race ='$Ethnicity' ";

    if($program == 'English') {
        $sql .= " and fl.foundation_int_english=1 ";
    } else if($program == 'Mandarin') {
        $sql .= " and fl.foundation_int_mandarin=1 ";
    } else if($program == 'Int Art') {
        $sql .= " and fl.foundation_int_art=1 ";
    } else if($program == 'IQ Maths') {
        $sql .= " and fl.foundation_iq_math=1 ";
    }

    if($selected_month != 'ALL' && $selected_month != '')
    {
        $sql .= " and '$selected_month' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
    }

    $sql .= "   group by ps.student_entry_level, s.id) ab";

    $resultt=mysqli_query($connection, $sql);
    $roww=mysqli_fetch_assoc($resultt);
    return $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
}

function AfternoonProgQFLevelCount($student_entry_level,$program)
{
    global $connection, $year_start_date, $year_end_date, $current_date, $selected_month;

    $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.deleted='0' and fl.afternoon_programme = 1 ";

    if($program == 'Basic') {
      $sql .= " and f.basic_adjust > 0 ";
    } else if($program == 'Basic + Robotics') {
      $sql .= " and f.basic_adjust < 1 and f.afternoon_robotic_adjust > 0 ";
    }

    if($student_entry_level == 'junior') {
      $sql .= " and (ps.student_entry_level ='EDP' OR ps.student_entry_level ='QF1') ";
    } else if($student_entry_level == 'senior') {
      $sql .= " and (ps.student_entry_level ='QF2' OR ps.student_entry_level ='QF3') ";
    }

    if($selected_month != 'ALL' && $selected_month != '')
    {
        $sql .= " and '$selected_month' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
    }

    $sql .= "   group by ps.student_entry_level, s.id) ab";

    $resultt=mysqli_query($connection, $sql);
    $roww=mysqli_fetch_assoc($resultt);
    return $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
}

function AfternoonProgEthnicityCount($Ethnicity,$program)
{
    global $connection, $year_start_date, $year_end_date, $current_date, $selected_month;

    $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.deleted='0' and s.race ='$Ethnicity' and fl.afternoon_programme=1 ";

    if($program == 'Basic') {
      $sql .= " and f.basic_adjust > 0 ";
    } else if($program == 'Basic + Robotics') {
      $sql .= " and f.basic_adjust < 1 and f.afternoon_robotic_adjust > 0 ";
    }

    if($selected_month != 'ALL' && $selected_month != '')
    {
        $sql .= " and '$selected_month' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
    }

    $sql .= "   group by ps.student_entry_level, s.id) ab";

    $resultt=mysqli_query($connection, $sql);
    $roww=mysqli_fetch_assoc($resultt);
    return $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
}

function EFPlusQFLevelCount($student_entry_level)
{
    global $connection, $year_start_date, $year_end_date, $current_date, $selected_month;

    $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.deleted='0' and fl.robotic_plus=1 ";

    if($student_entry_level == 'junior') {
      $sql .= " and (ps.student_entry_level ='EDP' OR ps.student_entry_level ='QF1') ";
    } else if($student_entry_level == 'senior') {
      $sql .= " and (ps.student_entry_level ='QF2' OR ps.student_entry_level ='QF3') ";
    }

    if($selected_month != 'ALL' && $selected_month != '')
    {
        $sql .= " and '$selected_month' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
    }

    $sql .= "   group by ps.student_entry_level, s.id) ab";

    $resultt=mysqli_query($connection, $sql);
    $roww=mysqli_fetch_assoc($resultt);
    return $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
}

function EFPlusEthnicityCount($Ethnicity)
{
    global $connection, $year_start_date, $year_end_date, $current_date, $selected_month;

    $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.deleted='0' and s.race ='$Ethnicity' and fl.robotic_plus=1 ";

    if($selected_month != 'ALL' && $selected_month != '')
    {
        $sql .= " and '$selected_month' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
    }

    $sql .= "   group by ps.student_entry_level, s.id) ab";

    $resultt=mysqli_query($connection, $sql);
    $roww=mysqli_fetch_assoc($resultt);
    return $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
}

function StuNoGeoDisEthnicityCount($Ethnicity,$state)
{
    global $connection, $year_start_date, $year_end_date, $current_date, $selected_month;

    $sql="SELECT count(`collection`.`id`) as level_count from `collection` LEFT JOIN `product` ON `product`.`product_code` = `collection`.`product_code` LEFT JOIN `student` ON `student`.`id` = `collection`.`student_id` where `student`.`state` = '$state' and (collection_date_time BETWEEN '$year_start_date' AND '$year_end_date') and `collection`.`void`=0 and `student`.`race` = '$Ethnicity' ";

    if($selected_month != 'ALL' && $selected_month != '')
    {
      $sql .= " and '$selected_month' BETWEEN DATE_FORMAT(collection.collection_date_time, '%Y-%m') AND DATE_FORMAT(collection.collection_date_time, '%Y-%m') ";
    }

    $resultt=mysqli_query($connection, $sql);
    $roww=mysqli_fetch_assoc($resultt);
    return $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
}

?>

<?php
if ($_SESSION["isLogin"]==1) {

    if($program == 'Half Day Prog and Ethnicity') { 
        $filename = 'half_day_student_no_analytic_'.date('Y-m-d').'.csv';
    } else if($program == 'Enh Foundation and Ethnicity') { 
        $filename = 'enh_foundation_student_no_analytic_'.date('Y-m-d').'.csv';
    } else if($program == 'Afternoon Prog and Ethnicity') { 
        $filename = 'afternoon_prog_student_no_analytic_'.date('Y-m-d').'.csv';
    } else if($program == 'EF Plus and Ethnicity') { 
        $filename = 'EF_plus_student_no_analytic_'.date('Y-m-d').'.csv';
    } else if($program == 'Stu No Geographical Distribution and Ethnicity') { 
        $filename = 'geographical_student_no_analytic_'.date('Y-m-d').'.csv';
    } else {
        $filename = 'student_no_analytic_'.date('Y-m-d').'.csv';
    }

    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");

    $content = array();

    $month = ($selected_month == 'ALL') ? 'All Months' : date('M Y',strtotime($selected_month));

    $content[] = array("Month : ",$month,"","","","","","","","","","","","","","","","","","");

    $content[] = array("Prepare By : ",$_SESSION["UserName"],"","","","","","","","","","","","","","","","","","");

    $content[] = array("Academic Year : ",$_SESSION['Year'],"","","","","","","","","","","","","","","","","","");

    $content[] = array("Date of submission : ",date("Y-m-d H:i:s"),"","","","","","","","","","","","","","","","","","");

    $content[] = array("","","","","","","","","","","","","","","","","","","","","");

    if($program == 'Half Day Prog and Ethnicity') { 

        $row = array();

        $row[] = "Half Day Prog & Ethnicity";
        $row[] = "EDP";
        $row[] = "";
        $row[] = "QF1";
        $row[] = "";
        $row[] = "QF2";
        $row[] = "";
        $row[] = "QF3";
        $row[] = "";
        $row[] = "Total";
        $row[] = "";
        $row[] = "Malay";
        $row[] = "";
        $row[] = "Chinese";
        $row[] = "";
        $row[] = "Indian";
        $row[] = "";
        $row[] = "Others";
        $row[] = "";
        $row[] = "Total";
        $row[] = "";

        $content[] = $row;

        $content[] = array("","No","%","No","%","No","%","No","%","No","%","No","%","No","%","No","%","No","%","No","%");
 
        // Foundation

        $foundation_EDP = HalfDayQFLevelCount('EDP','Foundation');
        $foundation_QF1 = HalfDayQFLevelCount('QF1','Foundation');
        $foundation_QF2 = HalfDayQFLevelCount('QF2','Foundation');
        $foundation_QF3 = HalfDayQFLevelCount('QF3','Foundation');

        $foundation_program_total = $foundation_EDP + $foundation_QF1 + $foundation_QF2 + $foundation_QF3;
        
        $foundation_Malay = HalfDayEthnicityCount('Malay','Foundation');
        $foundation_Chinese = HalfDayEthnicityCount('Chinese','Foundation');
        $foundation_Indian = HalfDayEthnicityCount('Indian','Foundation');
        $foundation_Others = HalfDayEthnicityCount('Others','Foundation');

        $foundation_ethnicity_total = $foundation_Malay + $foundation_Chinese + $foundation_Indian + $foundation_Others;
                
        $row = array();

        $row[] = "Foundation";
        $row[] = $foundation_EDP;
        $row[] = ($foundation_program_total == 0) ? 0 : round(($foundation_EDP * 100) / $foundation_program_total, 2)."%";
        $row[] = $foundation_QF1;
        $row[] = ($foundation_program_total == 0) ? 0 : round(($foundation_QF1 * 100) / $foundation_program_total, 2)."%";
        $row[] = $foundation_QF2;
        $row[] = ($foundation_program_total == 0) ? 0 : round(($foundation_QF2 * 100) / $foundation_program_total, 2)."%";
        $row[] = $foundation_QF3;
        $row[] = ($foundation_program_total == 0) ? 0 : round(($foundation_QF3 * 100) / $foundation_program_total, 2)."%";
        $row[] = $foundation_program_total;
        $row[] = "100%";
        $row[] = $foundation_Malay;
        $row[] = ($foundation_ethnicity_total == 0) ? 0 : round(($foundation_Malay * 100) / $foundation_ethnicity_total, 2)."%";
        $row[] = $foundation_Chinese;
        $row[] = ($foundation_ethnicity_total == 0) ? 0 : round(($foundation_Chinese * 100) / $foundation_ethnicity_total, 2)."%";
        $row[] = $foundation_Indian;
        $row[] = ($foundation_ethnicity_total == 0) ? 0 : round(($foundation_Indian * 100) / $foundation_ethnicity_total, 2)."%";
        $row[] = $foundation_Others;
        $row[] = ($foundation_ethnicity_total == 0) ? 0 : round(($foundation_Others * 100) / $foundation_ethnicity_total, 2)."%";
        $row[] = $foundation_ethnicity_total;
        $row[] = "100%";

        $content[] = $row;

        // Zhi Hui Mandarin

        $zhi_hui_mandarin_QF1 = HalfDayQFLevelCount('QF1','Zhi Hui Mandarin');
        $zhi_hui_mandarin_QF2 = HalfDayQFLevelCount('QF2','Zhi Hui Mandarin');
        $zhi_hui_mandarin_QF3 = HalfDayQFLevelCount('QF3','Zhi Hui Mandarin');

        $zhi_hui_mandarin_program_total = $zhi_hui_mandarin_QF1 + $zhi_hui_mandarin_QF2 + $zhi_hui_mandarin_QF3;
        
        $zhi_hui_mandarin_Malay = HalfDayEthnicityCount('Malay','Zhi Hui Mandarin');
        $zhi_hui_mandarin_Chinese = HalfDayEthnicityCount('Chinese','Zhi Hui Mandarin');
        $zhi_hui_mandarin_Indian = HalfDayEthnicityCount('Indian','Zhi Hui Mandarin');
        $zhi_hui_mandarin_Others = HalfDayEthnicityCount('Others','Zhi Hui Mandarin');

        $zhi_hui_mandarin_ethnicity_total = $zhi_hui_mandarin_Malay + $zhi_hui_mandarin_Chinese + $zhi_hui_mandarin_Indian + $zhi_hui_mandarin_Others;

        $row = array();

        $row[] = "Zhi Hui Mandarin";
        $row[] = "";
        $row[] = "";
        $row[] = $zhi_hui_mandarin_QF1;
        $row[] = ($zhi_hui_mandarin_program_total == 0) ? 0 : round(($zhi_hui_mandarin_QF1 * 100) / $zhi_hui_mandarin_program_total, 2)."%";
        $row[] = $zhi_hui_mandarin_QF2;
        $row[] = ($zhi_hui_mandarin_program_total == 0) ? 0 : round(($zhi_hui_mandarin_QF2 * 100) / $zhi_hui_mandarin_program_total, 2)."%";
        $row[] = $zhi_hui_mandarin_QF3;
        $row[] = ($zhi_hui_mandarin_program_total == 0) ? 0 : round(($zhi_hui_mandarin_QF3 * 100) / $zhi_hui_mandarin_program_total, 2)."%";
        $row[] = $zhi_hui_mandarin_program_total;
        $row[] = "100%";
        $row[] = $zhi_hui_mandarin_Malay;
        $row[] = ($zhi_hui_mandarin_ethnicity_total == 0) ? 0 : round(($zhi_hui_mandarin_Malay * 100) / $zhi_hui_mandarin_ethnicity_total, 2)."%";
        $row[] = $zhi_hui_mandarin_Chinese;
        $row[] = ($zhi_hui_mandarin_ethnicity_total == 0) ? 0 : round(($zhi_hui_mandarin_Chinese * 100) / $zhi_hui_mandarin_ethnicity_total, 2)."%";
        $row[] = $zhi_hui_mandarin_Indian;
        $row[] = ($zhi_hui_mandarin_ethnicity_total == 0) ? 0 : round(($zhi_hui_mandarin_Indian * 100) / $zhi_hui_mandarin_ethnicity_total, 2)."%";
        $row[] = $zhi_hui_mandarin_Others;
        $row[] = ($zhi_hui_mandarin_ethnicity_total == 0) ? 0 : round(($zhi_hui_mandarin_Others * 100) / $zhi_hui_mandarin_ethnicity_total, 2)."%";
        $row[] = $zhi_hui_mandarin_ethnicity_total;
        $row[] = "100%";

        $content[] = $row;
              

        // Pendidikan Jawi

        $pendidikan_QF1 = HalfDayQFLevelCount('QF1','Pendidikan Jawi');
        $pendidikan_QF2 = HalfDayQFLevelCount('QF2','Pendidikan Jawi');
        $pendidikan_QF3 = HalfDayQFLevelCount('QF3','Pendidikan Jawi');

        $pendidikan_program_total = $pendidikan_QF1 + $pendidikan_QF2 + $pendidikan_QF3;
        
        $pendidikan_Malay = HalfDayEthnicityCount('Malay','Pendidikan Jawi');
        $pendidikan_Chinese = HalfDayEthnicityCount('Chinese','Pendidikan Jawi');
        $pendidikan_Indian = HalfDayEthnicityCount('Indian','Pendidikan Jawi');
        $pendidikan_Others = HalfDayEthnicityCount('Others','Pendidikan Jawi');

        $pendidikan_ethnicity_total = $pendidikan_Malay + $pendidikan_Chinese + $pendidikan_Indian + $pendidikan_Others;

        $row = array();

        $row[] = "Pendidikan Jawi";
        $row[] = "";
        $row[] = "";
        $row[] = $pendidikan_QF1;
        $row[] = ($pendidikan_program_total == 0) ? 0 : round(($pendidikan_QF1 * 100) / $pendidikan_program_total, 2)."%";
        $row[] = $pendidikan_QF2;
        $row[] = ($pendidikan_program_total == 0) ? 0 : round(($pendidikan_QF2 * 100) / $pendidikan_program_total, 2)."%";
        $row[] = $pendidikan_QF3;
        $row[] = ($pendidikan_program_total == 0) ? 0 : round(($pendidikan_QF3 * 100) / $pendidikan_program_total, 2)."%";
        $row[] = $pendidikan_program_total;
        $row[] = "100%";
        $row[] = $pendidikan_Malay;
        $row[] = ($pendidikan_ethnicity_total == 0) ? 0 : round(($pendidikan_Malay * 100) / $pendidikan_ethnicity_total, 2)."%";
        $row[] = $pendidikan_Chinese;
        $row[] = ($pendidikan_ethnicity_total == 0) ? 0 : round(($pendidikan_Chinese * 100) / $pendidikan_ethnicity_total, 2)."%";
        $row[] = $pendidikan_Indian;
        $row[] = ($pendidikan_ethnicity_total == 0) ? 0 : round(($pendidikan_Indian * 100) / $pendidikan_ethnicity_total, 2)."%";
        $row[] = $pendidikan_Others;
        $row[] = ($pendidikan_ethnicity_total == 0) ? 0 : round(($pendidikan_Others * 100) / $pendidikan_ethnicity_total, 2)."%";
        $row[] = $pendidikan_ethnicity_total;
        $row[] = "100%";

        $content[] = $row;

    } else if($program == 'Enh Foundation and Ethnicity') { 

        $row = array();

        $row[] = "Enh Foundation & Ethnicity";
        $row[] = "QF1";
        $row[] = "";
        $row[] = "QF2";
        $row[] = "";
        $row[] = "QF3";
        $row[] = "";
        $row[] = "Total";
        $row[] = "";
        $row[] = "Malay";
        $row[] = "";
        $row[] = "Chinese";
        $row[] = "";
        $row[] = "Indian";
        $row[] = "";
        $row[] = "Others";
        $row[] = "";
        $row[] = "Total";
        $row[] = "";

        $content[] = $row;

        $content[] = array("","No","%","No","%","No","%","No","%","No","%","No","%","No","%","No","%","No","%");

        // English

        $english_QF1 = EnhFoundationQFLevelCount('QF1','English');
        $english_QF2 = EnhFoundationQFLevelCount('QF2','English');
        $english_QF3 = EnhFoundationQFLevelCount('QF3','English');

        $english_program_total = $english_QF1 + $english_QF2 + $english_QF3;
        
        $english_Malay = EnhFoundationEthnicityCount('Malay','English');
        $english_Chinese = EnhFoundationEthnicityCount('Chinese','English');
        $english_Indian = EnhFoundationEthnicityCount('Indian','English');
        $english_Others = EnhFoundationEthnicityCount('Others','English');

        $english_ethnicity_total = $english_Malay + $english_Chinese + $english_Indian + $english_Others;

        $row = array();

        $row[] = "English";
        $row[] = $english_QF1;
        $row[] = ($english_program_total == 0) ? 0 : round(($english_QF1 * 100) / $english_program_total, 2)."%";
        $row[] = $english_QF2;
        $row[] = ($english_program_total == 0) ? 0 : round(($english_QF2 * 100) / $english_program_total, 2)."%";
        $row[] = $english_QF3;
        $row[] = ($english_program_total == 0) ? 0 : round(($english_QF3 * 100) / $english_program_total, 2)."%";
        $row[] = $english_program_total;
        $row[] = "100%";
        $row[] = $english_Malay;
        $row[] = ($english_ethnicity_total == 0) ? 0 : round(($english_Malay * 100) / $english_ethnicity_total, 2)."%";
        $row[] = $english_Chinese;
        $row[] = ($english_ethnicity_total == 0) ? 0 : round(($english_Chinese * 100) / $english_ethnicity_total, 2)."%";
        $row[] = $english_Indian;
        $row[] = ($english_ethnicity_total == 0) ? 0 : round(($english_Indian * 100) / $english_ethnicity_total, 2)."%";
        $row[] = $english_Others;
        $row[] = ($english_ethnicity_total == 0) ? 0 : round(($english_Others * 100) / $english_ethnicity_total, 2)."%";
        $row[] = $english_ethnicity_total;
        $row[] = "100%";

        $content[] = $row;
        
        // Mandarin

        $mandarin_QF1 = EnhFoundationQFLevelCount('QF1','Mandarin');
        $mandarin_QF2 = EnhFoundationQFLevelCount('QF2','Mandarin');
        $mandarin_QF3 = EnhFoundationQFLevelCount('QF3','Mandarin');

        $mandarin_program_total = $mandarin_QF1 + $mandarin_QF2 + $mandarin_QF3;
        
        $mandarin_Malay = EnhFoundationEthnicityCount('Malay','Mandarin');
        $mandarin_Chinese = EnhFoundationEthnicityCount('Chinese','Mandarin');
        $mandarin_Indian = EnhFoundationEthnicityCount('Indian','Mandarin');
        $mandarin_Others = EnhFoundationEthnicityCount('Others','Mandarin');

        $mandarin_ethnicity_total = $mandarin_Malay + $mandarin_Chinese + $mandarin_Indian + $mandarin_Others;

        $row = array();

        $row[] = "Mandarin";
        $row[] = $mandarin_QF1;
        $row[] = ($mandarin_program_total == 0) ? 0 : round(($mandarin_QF1 * 100) / $mandarin_program_total, 2)."%";
        $row[] = $mandarin_QF2;
        $row[] = ($mandarin_program_total == 0) ? 0 : round(($mandarin_QF2 * 100) / $mandarin_program_total, 2)."%";
        $row[] = $mandarin_QF3;
        $row[] = ($mandarin_program_total == 0) ? 0 : round(($mandarin_QF3 * 100) / $mandarin_program_total, 2)."%";
        $row[] = $mandarin_program_total;
        $row[] = "100%";
        $row[] = $mandarin_Malay;
        $row[] = ($mandarin_ethnicity_total == 0) ? 0 : round(($mandarin_Malay * 100) / $mandarin_ethnicity_total, 2)."%";
        $row[] = $mandarin_Chinese;
        $row[] = ($mandarin_ethnicity_total == 0) ? 0 : round(($mandarin_Chinese * 100) / $mandarin_ethnicity_total, 2)."%";
        $row[] = $mandarin_Indian;
        $row[] = ($mandarin_ethnicity_total == 0) ? 0 : round(($mandarin_Indian * 100) / $mandarin_ethnicity_total, 2)."%";
        $row[] = $mandarin_Others;
        $row[] = ($mandarin_ethnicity_total == 0) ? 0 : round(($mandarin_Others * 100) / $mandarin_ethnicity_total, 2)."%";
        $row[] = $mandarin_ethnicity_total;
        $row[] = "100%";

        $content[] = $row;

        // Int Art

        $int_art_QF1 = EnhFoundationQFLevelCount('QF1','Int Art');
        $int_art_QF2 = EnhFoundationQFLevelCount('QF2','Int Art');
        $int_art_QF3 = EnhFoundationQFLevelCount('QF3','Int Art');

        $int_art_program_total = $int_art_QF1 + $int_art_QF2 + $int_art_QF3;
        
        $int_art_Malay = EnhFoundationEthnicityCount('Malay','Int Art');
        $int_art_Chinese = EnhFoundationEthnicityCount('Chinese','Int Art');
        $int_art_Indian = EnhFoundationEthnicityCount('Indian','Int Art');
        $int_art_Others = EnhFoundationEthnicityCount('Others','Int Art');

        $int_art_ethnicity_total = $int_art_Malay + $int_art_Chinese + $int_art_Indian + $int_art_Others;

        $row = array();

        $row[] = "Int Art";
        $row[] = $int_art_QF1;
        $row[] = ($int_art_program_total == 0) ? 0 : round(($int_art_QF1 * 100) / $int_art_program_total, 2)."%";
        $row[] = $int_art_QF2;
        $row[] = ($int_art_program_total == 0) ? 0 : round(($int_art_QF2 * 100) / $int_art_program_total, 2)."%";
        $row[] = $int_art_QF3;
        $row[] = ($int_art_program_total == 0) ? 0 : round(($int_art_QF3 * 100) / $int_art_program_total, 2)."%";
        $row[] = $int_art_program_total;
        $row[] = "100%";
        $row[] = $int_art_Malay;
        $row[] = ($int_art_ethnicity_total == 0) ? 0 : round(($int_art_Malay * 100) / $int_art_ethnicity_total, 2)."%";
        $row[] = $int_art_Chinese;
        $row[] = ($int_art_ethnicity_total == 0) ? 0 : round(($int_art_Chinese * 100) / $int_art_ethnicity_total, 2)."%";
        $row[] = $int_art_Indian;
        $row[] = ($int_art_ethnicity_total == 0) ? 0 : round(($int_art_Indian * 100) / $int_art_ethnicity_total, 2)."%";
        $row[] = $int_art_Others;
        $row[] = ($int_art_ethnicity_total == 0) ? 0 : round(($int_art_Others * 100) / $int_art_ethnicity_total, 2)."%";
        $row[] = $int_art_ethnicity_total;
        $row[] = "100%";

        $content[] = $row; 
           
        // IQ Maths

        $iq_math_QF1 = EnhFoundationQFLevelCount('QF1','IQ Maths');
        $iq_math_QF2 = EnhFoundationQFLevelCount('QF2','IQ Maths');
        $iq_math_QF3 = EnhFoundationQFLevelCount('QF3','IQ Maths');

        $iq_math_program_total = $iq_math_QF1 + $iq_math_QF2 + $iq_math_QF3;
        
        $iq_math_Malay = EnhFoundationEthnicityCount('Malay','IQ Maths');
        $iq_math_Chinese = EnhFoundationEthnicityCount('Chinese','IQ Maths');
        $iq_math_Indian = EnhFoundationEthnicityCount('Indian','IQ Maths');
        $iq_math_Others = EnhFoundationEthnicityCount('Others','IQ Maths');

        $iq_math_ethnicity_total = $iq_math_Malay + $iq_math_Chinese + $iq_math_Indian + $iq_math_Others;

        $row = array();

        $row[] = "IQ Maths";
        $row[] = $iq_math_QF1;
        $row[] = ($iq_math_program_total == 0) ? 0 : round(($iq_math_QF1 * 100) / $iq_math_program_total, 2)."%";
        $row[] = $iq_math_QF2;
        $row[] = ($iq_math_program_total == 0) ? 0 : round(($iq_math_QF2 * 100) / $iq_math_program_total, 2)."%";
        $row[] = $iq_math_QF3;
        $row[] = ($iq_math_program_total == 0) ? 0 : round(($iq_math_QF3 * 100) / $iq_math_program_total, 2)."%";
        $row[] = $iq_math_program_total;
        $row[] = "100%";
        $row[] = $iq_math_Malay;
        $row[] = ($iq_math_ethnicity_total == 0) ? 0 : round(($iq_math_Malay * 100) / $iq_math_ethnicity_total, 2)."%";
        $row[] = $iq_math_Chinese;
        $row[] = ($iq_math_ethnicity_total == 0) ? 0 : round(($iq_math_Chinese * 100) / $iq_math_ethnicity_total, 2)."%";
        $row[] = $iq_math_Indian;
        $row[] = ($iq_math_ethnicity_total == 0) ? 0 : round(($iq_math_Indian * 100) / $iq_math_ethnicity_total, 2)."%";
        $row[] = $iq_math_Others;
        $row[] = ($iq_math_ethnicity_total == 0) ? 0 : round(($iq_math_Others * 100) / $iq_math_ethnicity_total, 2)."%";
        $row[] = $iq_math_ethnicity_total;
        $row[] = "100%";

        $content[] = $row; 


    } else if($program == 'Afternoon Prog and Ethnicity') {

        $row = array();

        $row[] = "Afternoon Prog & Ethnicity";
        $row[] = "Junior";
        $row[] = "";
        $row[] = "Senior";
        $row[] = "";
        $row[] = "Total";
        $row[] = "";
        $row[] = "Malay";
        $row[] = "";
        $row[] = "Chinese";
        $row[] = "";
        $row[] = "Indian";
        $row[] = "";
        $row[] = "Others";
        $row[] = "";
        $row[] = "Total";
        $row[] = "";

        $content[] = $row;

        $content[] = array("","No","%","No","%","No","%","No","%","No","%","No","%","No","%","No","%");

        // Basic

        $basic_junior = AfternoonProgQFLevelCount('junior','Basic');
        $basic_senior = AfternoonProgQFLevelCount('senior','Basic');

        $basic_program_total = $basic_junior + $basic_senior;

        $basic_Malay = AfternoonProgEthnicityCount('Malay','Basic');
        $basic_Chinese = AfternoonProgEthnicityCount('Chinese','Basic');
        $basic_Indian = AfternoonProgEthnicityCount('Indian','Basic');
        $basic_Others = AfternoonProgEthnicityCount('Others','Basic');

        $basic_ethnicity_total = $basic_Malay + $basic_Chinese + $basic_Indian + $basic_Others;

        $row = array();

        $row[] = "Basic";
        $row[] = $basic_junior;
        $row[] = ($basic_program_total == 0) ? 0 : round(($basic_junior * 100) / $basic_program_total, 2)."%";
        $row[] = $basic_senior;
        $row[] = ($basic_program_total == 0) ? 0 : round(($basic_senior * 100) / $basic_program_total, 2)."%";
        $row[] = $basic_program_total;
        $row[] = "100%";
        $row[] = $basic_Malay;
        $row[] = ($basic_ethnicity_total == 0) ? 0 : round(($basic_Malay * 100) / $basic_ethnicity_total, 2)."%";
        $row[] = $basic_Chinese;
        $row[] = ($basic_ethnicity_total == 0) ? 0 : round(($basic_Chinese * 100) / $basic_ethnicity_total, 2)."%";
        $row[] = $basic_Indian;
        $row[] = ($basic_ethnicity_total == 0) ? 0 : round(($basic_Indian * 100) / $basic_ethnicity_total, 2)."%";
        $row[] = $basic_Others;
        $row[] = ($basic_ethnicity_total == 0) ? 0 : round(($basic_Others * 100) / $basic_ethnicity_total, 2)."%";
        $row[] = $basic_ethnicity_total;
        $row[] = "100%";

        $content[] = $row;

        // Basic + Robotics

        $basic_robotic_junior = AfternoonProgQFLevelCount('junior','Basic + Robotics');
        $basic_robotic_senior = AfternoonProgQFLevelCount('senior','Basic + Robotics');

        $basic_robotic_program_total = $basic_robotic_junior + $basic_robotic_senior;

        $basic_robotic_Malay = AfternoonProgEthnicityCount('Malay','Basic + Robotics');
        $basic_robotic_Chinese = AfternoonProgEthnicityCount('Chinese','Basic + Robotics');
        $basic_robotic_Indian = AfternoonProgEthnicityCount('Indian','Basic + Robotics');
        $basic_robotic_Others = AfternoonProgEthnicityCount('Others','Basic + Robotics');

        $basic_robotic_ethnicity_total = $basic_robotic_Malay + $basic_robotic_Chinese + $basic_robotic_Indian + $basic_robotic_Others;

        $row = array();

        $row[] = "Basic + Robotics";
        $row[] = $basic_robotic_junior;
        $row[] = ($basic_robotic_program_total == 0) ? 0 : round(($basic_robotic_junior * 100) / $basic_robotic_program_total, 2)."%";
        $row[] = $basic_robotic_senior;
        $row[] = ($basic_robotic_program_total == 0) ? 0 : round(($basic_robotic_senior * 100) / $basic_robotic_program_total, 2)."%";
        $row[] = $basic_robotic_program_total;
        $row[] = "100%";
        $row[] = $basic_robotic_Malay;
        $row[] = ($basic_robotic_ethnicity_total == 0) ? 0 : round(($basic_robotic_Malay * 100) / $basic_robotic_ethnicity_total, 2)."%";
        $row[] = $basic_robotic_Chinese;
        $row[] = ($basic_robotic_ethnicity_total == 0) ? 0 : round(($basic_robotic_Chinese * 100) / $basic_robotic_ethnicity_total, 2)."%";
        $row[] = $basic_robotic_Indian;
        $row[] = ($basic_robotic_ethnicity_total == 0) ? 0 : round(($basic_robotic_Indian * 100) / $basic_robotic_ethnicity_total, 2)."%";
        $row[] = $basic_robotic_Others;
        $row[] = ($basic_robotic_ethnicity_total == 0) ? 0 : round(($basic_robotic_Others * 100) / $basic_robotic_ethnicity_total, 2)."%";
        $row[] = $basic_robotic_ethnicity_total;
        $row[] = "100%";

        $content[] = $row;
                
    } else if($program == 'EF Plus and Ethnicity') { 
          
        $row = array();

        $row[] = "EF Plus & Ethnicity";
        $row[] = "Junior";
        $row[] = "";
        $row[] = "Senior";
        $row[] = "";
        $row[] = "Total";
        $row[] = "";
        $row[] = "Malay";
        $row[] = "";
        $row[] = "Chinese";
        $row[] = "";
        $row[] = "Indian";
        $row[] = "";
        $row[] = "Others";
        $row[] = "";
        $row[] = "Total";
        $row[] = "";

        $content[] = $row;

        $content[] = array("","No","%","No","%","No","%","No","%","No","%","No","%","No","%","No","%");

        // Robotics

        $robotic_plus_junior = EFPlusQFLevelCount('junior');
        $robotic_plus_senior = EFPlusQFLevelCount('senior');

        $robotic_plus_program_total = $robotic_plus_junior + $robotic_plus_senior;

        $robotic_plus_Malay = EFPlusEthnicityCount('Malay');
        $robotic_plus_Chinese = EFPlusEthnicityCount('Chinese');
        $robotic_plus_Indian = EFPlusEthnicityCount('Indian');
        $robotic_plus_Others = EFPlusEthnicityCount('Others');

        $robotic_plus_ethnicity_total = $robotic_plus_Malay + $robotic_plus_Chinese + $robotic_plus_Indian + $robotic_plus_Others;

        $row = array();

        $row[] = "Robotics";
        $row[] = $robotic_plus_junior;
        $row[] = ($robotic_plus_program_total == 0) ? 0 : round(($robotic_plus_junior * 100) / $robotic_plus_program_total, 2)."%";
        $row[] = $robotic_plus_senior;
        $row[] = ($robotic_plus_program_total == 0) ? 0 : round(($robotic_plus_senior * 100) / $robotic_plus_program_total, 2)."%";
        $row[] = $robotic_plus_program_total;
        $row[] = "100%";
        $row[] = $robotic_plus_Malay;
        $row[] = ($robotic_plus_ethnicity_total == 0) ? 0 : round(($robotic_plus_Malay * 100) / $robotic_plus_ethnicity_total, 2)."%";
        $row[] = $robotic_plus_Chinese;
        $row[] = ($robotic_plus_ethnicity_total == 0) ? 0 : round(($robotic_plus_Chinese * 100) / $robotic_plus_ethnicity_total, 2)."%";
        $row[] = $robotic_plus_Indian;
        $row[] = ($robotic_plus_ethnicity_total == 0) ? 0 : round(($robotic_plus_Indian * 100) / $robotic_plus_ethnicity_total, 2)."%";
        $row[] = $robotic_plus_Others;
        $row[] = ($robotic_plus_ethnicity_total == 0) ? 0 : round(($robotic_plus_Others * 100) / $robotic_plus_ethnicity_total, 2)."%";
        $row[] = $robotic_plus_ethnicity_total;
        $row[] = "100%";

        $content[] = $row;

    } else if($program == 'Stu No Geographical Distribution and Ethnicity') { 
          
        $row = array();

        $row[] = "Stu No Geographical Distribution & Ethnicity";
        $row[] = "Johor";
        $row[] = "";
        $row[] = "Melaka";
        $row[] = "";
        $row[] = "N. Sembilan";
        $row[] = "";
        $row[] = "Selangor";
        $row[] = "";
        $row[] = "FT - KL";
        $row[] = "";
        $row[] = "FT - Labuan";
        $row[] = "";
        $row[] = "FT - Putrajaya";
        $row[] = "";
        $row[] = "Perak";
        $row[] = "";
        $row[] = "Kedah";
        $row[] = "";
        $row[] = "Penang";
        $row[] = "";
        $row[] = "Perlis";
        $row[] = "";
        $row[] = "Kelantan";
        $row[] = "";
        $row[] = "Terengganu";
        $row[] = "";
        $row[] = "Pahang";
        $row[] = "";
        $row[] = "Sabah";
        $row[] = "";
        $row[] = "Sarawak";
        $row[] = "";
        $row[] = "Total";
        $row[] = "";

        $content[] = $row;

        $content[] = array("","No","%","No","%","No","%","No","%","No","%","No","%","No","%","No","%","No","%","No","%","No","%","No","%","No","%","No","%","No","%","No","%","No","%");

        // Malay

        $malay_johor = StuNoGeoDisEthnicityCount('Malay','Johor');
        $malay_melaka = StuNoGeoDisEthnicityCount('Malay','Melaka');
        $malay_N_sembilan = StuNoGeoDisEthnicityCount('Malay','N. Sembilan');
        $malay_selangor = StuNoGeoDisEthnicityCount('Malay','Selangor');
        $malay_FT_KL = StuNoGeoDisEthnicityCount('Malay','FT - KL');
        $malay_FT_labuan = StuNoGeoDisEthnicityCount('Malay','FT - Labuan');
        $malay_FT_putrajaya = StuNoGeoDisEthnicityCount('Malay','FT - Putrajaya');
        $malay_perak = StuNoGeoDisEthnicityCount('Malay','Perak');
        $malay_kedah = StuNoGeoDisEthnicityCount('Malay','Kedah');
        $malay_penang = StuNoGeoDisEthnicityCount('Malay','Penang');
        $malay_perlis = StuNoGeoDisEthnicityCount('Malay','Perlis');
        $malay_kelantan = StuNoGeoDisEthnicityCount('Malay','Kelantan');
        $malay_terengganu = StuNoGeoDisEthnicityCount('Malay','Terengganu');
        $malay_pahang = StuNoGeoDisEthnicityCount('Malay','Pahang');
        $malay_sabah = StuNoGeoDisEthnicityCount('Malay','Sabah');
        $malay_sarawak = StuNoGeoDisEthnicityCount('Malay','Sarawak');

        $malay_total = $malay_johor + $malay_melaka + $malay_N_sembilan + $malay_selangor + $malay_FT_KL + $malay_FT_labuan + $malay_FT_putrajaya + $malay_perak + $malay_kedah + $malay_penang + $malay_perlis + $malay_kelantan + $malay_terengganu + $malay_pahang + $malay_sabah + $malay_sarawak;

        $row = array();

        $row[] = "Malay";
        $row[] = $malay_johor;
        $row[] = ($malay_total == 0) ? 0 : round(($malay_johor * 100) / $malay_total, 2)."%";
        $row[] = $malay_melaka;
        $row[] = ($malay_total == 0) ? 0 : round(($malay_melaka * 100) / $malay_total, 2)."%";
        $row[] = $malay_N_sembilan;
        $row[] = ($malay_total == 0) ? 0 : round(($malay_N_sembilan * 100) / $malay_total, 2)."%";
        $row[] = $malay_selangor;
        $row[] = ($malay_total == 0) ? 0 : round(($malay_selangor * 100) / $malay_total, 2)."%";
        $row[] = $malay_FT_KL;
        $row[] = ($malay_total == 0) ? 0 : round(($malay_FT_KL * 100) / $malay_total, 2)."%";
        $row[] = $malay_FT_labuan;
        $row[] = ($malay_total == 0) ? 0 : round(($malay_FT_labuan * 100) / $malay_total, 2)."%";
        $row[] = $malay_FT_putrajaya;
        $row[] = ($malay_total == 0) ? 0 : round(($malay_FT_putrajaya * 100) / $malay_total, 2)."%";
        $row[] = $malay_perak;
        $row[] = ($malay_total == 0) ? 0 : round(($malay_perak * 100) / $malay_total, 2)."%";
        $row[] = $malay_kedah;
        $row[] = ($malay_total == 0) ? 0 : round(($malay_kedah * 100) / $malay_total, 2)."%";
        $row[] = $malay_penang;
        $row[] = ($malay_total == 0) ? 0 : round(($malay_penang * 100) / $malay_total, 2)."%";
        $row[] = $malay_perlis;
        $row[] = ($malay_total == 0) ? 0 : round(($malay_perlis * 100) / $malay_total, 2)."%";
        $row[] = $malay_kelantan;
        $row[] = ($malay_total == 0) ? 0 : round(($malay_kelantan * 100) / $malay_total, 2)."%";
        $row[] = $malay_terengganu;
        $row[] = ($malay_total == 0) ? 0 : round(($malay_terengganu * 100) / $malay_total, 2)."%";
        $row[] = $malay_pahang;
        $row[] = ($malay_total == 0) ? 0 : round(($malay_pahang * 100) / $malay_total, 2)."%";
        $row[] = $malay_sabah;
        $row[] = ($malay_total == 0) ? 0 : round(($malay_sabah * 100) / $malay_total, 2)."%";
        $row[] = $malay_sarawak;
        $row[] = ($malay_total == 0) ? 0 : round(($malay_sarawak * 100) / $malay_total, 2)."%";
        $row[] = $malay_total;
        $row[] = "100%";

        $content[] = $row;

        // Chinese

        $chinese_johor = StuNoGeoDisEthnicityCount('Chinese','Johor');
        $chinese_melaka = StuNoGeoDisEthnicityCount('Chinese','Melaka');
        $chinese_N_sembilan = StuNoGeoDisEthnicityCount('Chinese','N. Sembilan');
        $chinese_selangor = StuNoGeoDisEthnicityCount('Chinese','Selangor');
        $chinese_FT_KL = StuNoGeoDisEthnicityCount('Chinese','FT - KL');
        $chinese_FT_labuan = StuNoGeoDisEthnicityCount('Chinese','FT - Labuan');
        $chinese_FT_putrajaya = StuNoGeoDisEthnicityCount('Chinese','FT - Putrajaya');
        $chinese_perak = StuNoGeoDisEthnicityCount('Chinese','Perak');
        $chinese_kedah = StuNoGeoDisEthnicityCount('Chinese','Kedah');
        $chinese_penang = StuNoGeoDisEthnicityCount('Chinese','Penang');
        $chinese_perlis = StuNoGeoDisEthnicityCount('Chinese','Perlis');
        $chinese_kelantan = StuNoGeoDisEthnicityCount('Chinese','Kelantan');
        $chinese_terengganu = StuNoGeoDisEthnicityCount('Chinese','Terengganu');
        $chinese_pahang = StuNoGeoDisEthnicityCount('Chinese','Pahang');
        $chinese_sabah = StuNoGeoDisEthnicityCount('Chinese','Sabah');
        $chinese_sarawak = StuNoGeoDisEthnicityCount('Chinese','Sarawak');

        $chinese_total = $chinese_johor + $chinese_melaka + $chinese_N_sembilan + $chinese_selangor + $chinese_FT_KL + $chinese_FT_labuan + $chinese_FT_putrajaya + $chinese_perak + $chinese_kedah + $chinese_penang + $chinese_perlis + $chinese_kelantan + $chinese_terengganu + $chinese_pahang + $chinese_sabah + $chinese_sarawak;

        $row = array();

        $row[] = "Chinese";
        $row[] = $chinese_johor;
        $row[] = ($chinese_total == 0) ? 0 : round(($chinese_johor * 100) / $chinese_total, 2)."%";
        $row[] = $chinese_melaka;
        $row[] = ($chinese_total == 0) ? 0 : round(($chinese_melaka * 100) / $chinese_total, 2)."%";
        $row[] = $chinese_N_sembilan;
        $row[] = ($chinese_total == 0) ? 0 : round(($chinese_N_sembilan * 100) / $chinese_total, 2)."%";
        $row[] = $chinese_selangor;
        $row[] = ($chinese_total == 0) ? 0 : round(($chinese_selangor * 100) / $chinese_total, 2)."%";
        $row[] = $chinese_FT_KL;
        $row[] = ($chinese_total == 0) ? 0 : round(($chinese_FT_KL * 100) / $chinese_total, 2)."%";
        $row[] = $chinese_FT_labuan;
        $row[] = ($chinese_total == 0) ? 0 : round(($chinese_FT_labuan * 100) / $chinese_total, 2)."%";
        $row[] = $chinese_FT_putrajaya;
        $row[] = ($chinese_total == 0) ? 0 : round(($chinese_FT_putrajaya * 100) / $chinese_total, 2)."%";
        $row[] = $chinese_perak;
        $row[] = ($chinese_total == 0) ? 0 : round(($chinese_perak * 100) / $chinese_total, 2)."%";
        $row[] = $chinese_kedah;
        $row[] = ($chinese_total == 0) ? 0 : round(($chinese_kedah * 100) / $chinese_total, 2)."%";
        $row[] = $chinese_penang;
        $row[] = ($chinese_total == 0) ? 0 : round(($chinese_penang * 100) / $chinese_total, 2)."%";
        $row[] = $chinese_perlis;
        $row[] = ($chinese_total == 0) ? 0 : round(($chinese_perlis * 100) / $chinese_total, 2)."%";
        $row[] = $chinese_kelantan;
        $row[] = ($chinese_total == 0) ? 0 : round(($chinese_kelantan * 100) / $chinese_total, 2)."%";
        $row[] = $chinese_terengganu;
        $row[] = ($chinese_total == 0) ? 0 : round(($chinese_terengganu * 100) / $chinese_total, 2)."%";
        $row[] = $chinese_pahang;
        $row[] = ($chinese_total == 0) ? 0 : round(($chinese_pahang * 100) / $chinese_total, 2)."%";
        $row[] = $chinese_sabah;
        $row[] = ($chinese_total == 0) ? 0 : round(($chinese_sabah * 100) / $chinese_total, 2)."%";
        $row[] = $chinese_sarawak;
        $row[] = ($chinese_total == 0) ? 0 : round(($chinese_sarawak * 100) / $chinese_total, 2)."%";
        $row[] = $chinese_total;
        $row[] = "100%";

        $content[] = $row;

        // Indian

        $indian_johor = StuNoGeoDisEthnicityCount('Indian','Johor');
        $indian_melaka = StuNoGeoDisEthnicityCount('Indian','Melaka');
        $indian_N_sembilan = StuNoGeoDisEthnicityCount('Indian','N. Sembilan');
        $indian_selangor = StuNoGeoDisEthnicityCount('Indian','Selangor');
        $indian_FT_KL = StuNoGeoDisEthnicityCount('Indian','FT - KL');
        $indian_FT_labuan = StuNoGeoDisEthnicityCount('Indian','FT - Labuan');
        $indian_FT_putrajaya = StuNoGeoDisEthnicityCount('Indian','FT - Putrajaya');
        $indian_perak = StuNoGeoDisEthnicityCount('Indian','Perak');
        $indian_kedah = StuNoGeoDisEthnicityCount('Indian','Kedah');
        $indian_penang = StuNoGeoDisEthnicityCount('Indian','Penang');
        $indian_perlis = StuNoGeoDisEthnicityCount('Indian','Perlis');
        $indian_kelantan = StuNoGeoDisEthnicityCount('Indian','Kelantan');
        $indian_terengganu = StuNoGeoDisEthnicityCount('Indian','Terengganu');
        $indian_pahang = StuNoGeoDisEthnicityCount('Indian','Pahang');
        $indian_sabah = StuNoGeoDisEthnicityCount('Indian','Sabah');
        $indian_sarawak = StuNoGeoDisEthnicityCount('Indian','Sarawak');

        $indian_total = $indian_johor + $indian_melaka + $indian_N_sembilan + $indian_selangor + $indian_FT_KL + $indian_FT_labuan + $indian_FT_putrajaya + $indian_perak + $indian_kedah + $indian_penang + $indian_perlis + $indian_kelantan + $indian_terengganu + $indian_pahang + $indian_sabah + $indian_sarawak;

        $row = array();

        $row[] = "Indian";
        $row[] = $indian_johor;
        $row[] = ($indian_total == 0) ? 0 : round(($indian_johor * 100) / $indian_total, 2)."%";
        $row[] = $indian_melaka;
        $row[] = ($indian_total == 0) ? 0 : round(($indian_melaka * 100) / $indian_total, 2)."%";
        $row[] = $indian_N_sembilan;
        $row[] = ($indian_total == 0) ? 0 : round(($indian_N_sembilan * 100) / $indian_total, 2)."%";
        $row[] = $indian_selangor;
        $row[] = ($indian_total == 0) ? 0 : round(($indian_selangor * 100) / $indian_total, 2)."%";
        $row[] = $indian_FT_KL;
        $row[] = ($indian_total == 0) ? 0 : round(($indian_FT_KL * 100) / $indian_total, 2)."%";
        $row[] = $indian_FT_labuan;
        $row[] = ($indian_total == 0) ? 0 : round(($indian_FT_labuan * 100) / $indian_total, 2)."%";
        $row[] = $indian_FT_putrajaya;
        $row[] = ($indian_total == 0) ? 0 : round(($indian_FT_putrajaya * 100) / $indian_total, 2)."%";
        $row[] = $indian_perak;
        $row[] = ($indian_total == 0) ? 0 : round(($indian_perak * 100) / $indian_total, 2)."%";
        $row[] = $indian_kedah;
        $row[] = ($indian_total == 0) ? 0 : round(($indian_kedah * 100) / $indian_total, 2)."%";
        $row[] = $indian_penang;
        $row[] = ($indian_total == 0) ? 0 : round(($indian_penang * 100) / $indian_total, 2)."%";
        $row[] = $indian_perlis;
        $row[] = ($indian_total == 0) ? 0 : round(($indian_perlis * 100) / $indian_total, 2)."%";
        $row[] = $indian_kelantan;
        $row[] = ($indian_total == 0) ? 0 : round(($indian_kelantan * 100) / $indian_total, 2)."%";
        $row[] = $indian_terengganu;
        $row[] = ($indian_total == 0) ? 0 : round(($indian_terengganu * 100) / $indian_total, 2)."%";
        $row[] = $indian_pahang;
        $row[] = ($indian_total == 0) ? 0 : round(($indian_pahang * 100) / $indian_total, 2)."%";
        $row[] = $indian_sabah;
        $row[] = ($indian_total == 0) ? 0 : round(($indian_sabah * 100) / $indian_total, 2)."%";
        $row[] = $indian_sarawak;
        $row[] = ($indian_total == 0) ? 0 : round(($indian_sarawak * 100) / $indian_total, 2)."%";
        $row[] = $indian_total;
        $row[] = "100%";

        $content[] = $row;

        // Others

        $others_johor = StuNoGeoDisEthnicityCount('Others','Johor');
        $others_melaka = StuNoGeoDisEthnicityCount('Others','Melaka');
        $others_N_sembilan = StuNoGeoDisEthnicityCount('Others','N. Sembilan');
        $others_selangor = StuNoGeoDisEthnicityCount('Others','Selangor');
        $others_FT_KL = StuNoGeoDisEthnicityCount('Others','FT - KL');
        $others_FT_labuan = StuNoGeoDisEthnicityCount('Others','FT - Labuan');
        $others_FT_putrajaya = StuNoGeoDisEthnicityCount('Others','FT - Putrajaya');
        $others_perak = StuNoGeoDisEthnicityCount('Others','Perak');
        $others_kedah = StuNoGeoDisEthnicityCount('Others','Kedah');
        $others_penang = StuNoGeoDisEthnicityCount('Others','Penang');
        $others_perlis = StuNoGeoDisEthnicityCount('Others','Perlis');
        $others_kelantan = StuNoGeoDisEthnicityCount('Others','Kelantan');
        $others_terengganu = StuNoGeoDisEthnicityCount('Others','Terengganu');
        $others_pahang = StuNoGeoDisEthnicityCount('Others','Pahang');
        $others_sabah = StuNoGeoDisEthnicityCount('Others','Sabah');
        $others_sarawak = StuNoGeoDisEthnicityCount('Others','Sarawak');

        $others_total = $others_johor + $others_melaka + $others_N_sembilan + $others_selangor + $others_FT_KL + $others_FT_labuan + $others_FT_putrajaya + $others_perak + $others_kedah + $others_penang + $others_perlis + $others_kelantan + $others_terengganu + $others_pahang + $others_sabah + $others_sarawak;

        $row = array();

        $row[] = "Others";
        $row[] = $others_johor;
        $row[] = ($others_total == 0) ? 0 : round(($others_johor * 100) / $others_total, 2)."%";
        $row[] = $others_melaka;
        $row[] = ($others_total == 0) ? 0 : round(($others_melaka * 100) / $others_total, 2)."%";
        $row[] = $others_N_sembilan;
        $row[] = ($others_total == 0) ? 0 : round(($others_N_sembilan * 100) / $others_total, 2)."%";
        $row[] = $others_selangor;
        $row[] = ($others_total == 0) ? 0 : round(($others_selangor * 100) / $others_total, 2)."%";
        $row[] = $others_FT_KL;
        $row[] = ($others_total == 0) ? 0 : round(($others_FT_KL * 100) / $others_total, 2)."%";
        $row[] = $others_FT_labuan;
        $row[] = ($others_total == 0) ? 0 : round(($others_FT_labuan * 100) / $others_total, 2)."%";
        $row[] = $others_FT_putrajaya;
        $row[] = ($others_total == 0) ? 0 : round(($others_FT_putrajaya * 100) / $others_total, 2)."%";
        $row[] = $others_perak;
        $row[] = ($others_total == 0) ? 0 : round(($others_perak * 100) / $others_total, 2)."%";
        $row[] = $others_kedah;
        $row[] = ($others_total == 0) ? 0 : round(($others_kedah * 100) / $others_total, 2)."%";
        $row[] = $others_penang;
        $row[] = ($others_total == 0) ? 0 : round(($others_penang * 100) / $others_total, 2)."%";
        $row[] = $others_perlis;
        $row[] = ($others_total == 0) ? 0 : round(($others_perlis * 100) / $others_total, 2)."%";
        $row[] = $others_kelantan;
        $row[] = ($others_total == 0) ? 0 : round(($others_kelantan * 100) / $others_total, 2)."%";
        $row[] = $others_terengganu;
        $row[] = ($others_total == 0) ? 0 : round(($others_terengganu * 100) / $others_total, 2)."%";
        $row[] = $others_pahang;
        $row[] = ($others_total == 0) ? 0 : round(($others_pahang * 100) / $others_total, 2)."%";
        $row[] = $others_sabah;
        $row[] = ($others_total == 0) ? 0 : round(($others_sabah * 100) / $others_total, 2)."%";
        $row[] = $others_sarawak;
        $row[] = ($others_total == 0) ? 0 : round(($others_sarawak * 100) / $others_total, 2)."%";
        $row[] = $others_total;
        $row[] = "100%";

        $content[] = $row;

        // Total

        $johor_total = $malay_johor + $chinese_johor + $indian_johor + $others_johor;
        $melaka_total = $malay_melaka + $chinese_melaka + $indian_melaka + $others_melaka;
        $N_sembilan_total = $malay_N_sembilan + $chinese_N_sembilan + $indian_N_sembilan + $others_N_sembilan;
        $selangor_total = $malay_selangor + $chinese_selangor + $indian_selangor + $others_selangor;
        $FT_KL_total = $malay_FT_KL + $chinese_FT_KL + $indian_FT_KL + $others_FT_KL;
        $FT_labuan_total = $malay_FT_labuan + $chinese_FT_labuan + $indian_FT_labuan + $others_FT_labuan;
        $FT_putrajaya_total = $malay_FT_putrajaya + $chinese_FT_putrajaya + $indian_FT_putrajaya + $others_FT_putrajaya;
        $perak_total = $malay_perak + $chinese_perak + $indian_perak + $others_perak;
        $kedah_total = $malay_kedah + $chinese_kedah + $indian_kedah + $others_kedah;
        $penang_total = $malay_penang + $chinese_penang + $indian_penang + $others_penang;
        $perlis_total = $malay_perlis + $chinese_perlis + $indian_perlis + $others_perlis;
        $kelantan_total = $malay_kelantan + $chinese_kelantan + $indian_kelantan + $others_kelantan;
        $terengganu_total = $malay_terengganu + $chinese_terengganu + $indian_terengganu + $others_terengganu;
        $pahang_total = $malay_pahang + $chinese_pahang + $indian_pahang + $others_pahang;
        $sabah_total = $malay_sabah + $chinese_sabah + $indian_sabah + $others_sabah;
        $sarawak_total = $malay_sarawak + $chinese_sarawak + $indian_sarawak + $others_sarawak;

        $grand_total = $johor_total + $melaka_total + $N_sembilan_total + $selangor_total + $FT_KL_total + $FT_labuan_total + $FT_putrajaya_total + $perak_total + $kedah_total + $penang_total + $perlis_total + $kelantan_total + $terengganu_total + $pahang_total + $sabah_total + $sarawak_total;

        $row = array();

        $row[] = "Others";
        $row[] = $johor_total;
        $row[] = ($grand_total == 0) ? 0 : round(($johor_total * 100) / $grand_total, 2)."%";
        $row[] = $melaka_total;
        $row[] = ($grand_total == 0) ? 0 : round(($melaka_total * 100) / $grand_total, 2)."%";
        $row[] = $N_sembilan_total;
        $row[] = ($grand_total == 0) ? 0 : round(($N_sembilan_total * 100) / $grand_total, 2)."%";
        $row[] = $selangor_total;
        $row[] = ($grand_total == 0) ? 0 : round(($selangor_total * 100) / $grand_total, 2)."%";
        $row[] = $FT_KL_total;
        $row[] = ($grand_total == 0) ? 0 : round(($FT_KL_total * 100) / $grand_total, 2)."%";
        $row[] = $FT_labuan_total;
        $row[] = ($grand_total == 0) ? 0 : round(($FT_labuan_total * 100) / $grand_total, 2)."%";
        $row[] = $FT_putrajaya_total;
        $row[] = ($grand_total == 0) ? 0 : round(($FT_putrajaya_total * 100) / $grand_total, 2)."%";
        $row[] = $perak_total;
        $row[] = ($grand_total == 0) ? 0 : round(($perak_total * 100) / $grand_total, 2)."%";
        $row[] = $kedah_total;
        $row[] = ($grand_total == 0) ? 0 : round(($kedah_total * 100) / $grand_total, 2)."%";
        $row[] = $penang_total;
        $row[] = ($grand_total == 0) ? 0 : round(($penang_total * 100) / $grand_total, 2)."%";
        $row[] = $perlis_total;
        $row[] = ($grand_total == 0) ? 0 : round(($perlis_total * 100) / $grand_total, 2)."%";
        $row[] = $kelantan_total;
        $row[] = ($grand_total == 0) ? 0 : round(($kelantan_total * 100) / $grand_total, 2)."%";
        $row[] = $terengganu_total;
        $row[] = ($grand_total == 0) ? 0 : round(($terengganu_total * 100) / $grand_total, 2)."%";
        $row[] = $pahang_total;
        $row[] = ($grand_total == 0) ? 0 : round(($pahang_total * 100) / $grand_total, 2)."%";
        $row[] = $sabah_total;
        $row[] = ($grand_total == 0) ? 0 : round(($sabah_total * 100) / $grand_total, 2)."%";
        $row[] = $sarawak_total;
        $row[] = ($grand_total == 0) ? 0 : round(($sabah_total * 100) / $grand_total, 2)."%";
        $row[] = $grand_total;
        $row[] = "100%";

        $content[] = $row;

    }

    $output = fopen('php://output', 'w');
    fputcsv($output, $title);
    foreach ($content as $con) {
        fputcsv($output, $con);
    }
    fclose($output);
} 
?>
