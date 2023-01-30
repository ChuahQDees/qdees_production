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

if ($method == "print") {
  include_once("../uikit1.php");
}

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

function getChartDataHalfDay(){
    global $connection, $graph_type;
  
    $sub_categories = array(
      "Foundation",
      "Zhi Hui Mandarin",
      "Pendidikan Jawi"
    );
 
    $months= array();
    $months['EDP'] = 'EDP';
    $months['QF1'] = 'QF1';
    $months['QF2'] = 'QF2';
    $months['QF3'] = 'QF3';
    $months['Malay'] = 'Malay';
    $months['Chinese'] = 'Chinese';
    $months['Indian'] = 'Indian';
    $months['Others'] = 'Others';
  
    $chart_data = array(
      'labels' => array_keys($months),
      'datasets' => array()
    );
  
    $colors = array(
      "#d7ccc8",
      "#ffccbc",
      "#ffecb3",
      "#81c784",
      "#64b5f6",
      "#e57373"
    );
  
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

    $zhi_hui_mandarin_QF1 = HalfDayQFLevelCount('QF1','Zhi Hui Mandarin');
    $zhi_hui_mandarin_QF2 = HalfDayQFLevelCount('QF2','Zhi Hui Mandarin');
    $zhi_hui_mandarin_QF3 = HalfDayQFLevelCount('QF3','Zhi Hui Mandarin');

    $zhi_hui_mandarin_program_total = $zhi_hui_mandarin_QF1 + $zhi_hui_mandarin_QF2 + $zhi_hui_mandarin_QF3;
    
    $zhi_hui_mandarin_Malay = HalfDayEthnicityCount('Malay','Zhi Hui Mandarin');
    $zhi_hui_mandarin_Chinese = HalfDayEthnicityCount('Chinese','Zhi Hui Mandarin');
    $zhi_hui_mandarin_Indian = HalfDayEthnicityCount('Indian','Zhi Hui Mandarin');
    $zhi_hui_mandarin_Others = HalfDayEthnicityCount('Others','Zhi Hui Mandarin');

    $zhi_hui_mandarin_ethnicity_total = $zhi_hui_mandarin_Malay + $zhi_hui_mandarin_Chinese + $zhi_hui_mandarin_Indian + $zhi_hui_mandarin_Others;

    $pendidikan_QF1 = HalfDayQFLevelCount('QF1','Pendidikan Jawi');
    $pendidikan_QF2 = HalfDayQFLevelCount('QF2','Pendidikan Jawi');
    $pendidikan_QF3 = HalfDayQFLevelCount('QF3','Pendidikan Jawi');

    $pendidikan_program_total = $pendidikan_QF1 + $pendidikan_QF2 + $pendidikan_QF3;
    
    $pendidikan_Malay = HalfDayEthnicityCount('Malay','Pendidikan Jawi');
    $pendidikan_Chinese = HalfDayEthnicityCount('Chinese','Pendidikan Jawi');
    $pendidikan_Indian = HalfDayEthnicityCount('Indian','Pendidikan Jawi');
    $pendidikan_Others = HalfDayEthnicityCount('Others','Pendidikan Jawi');

    $pendidikan_ethnicity_total = $pendidikan_Malay + $pendidikan_Chinese + $pendidikan_Indian + $pendidikan_Others;

    if($graph_type == 'per')
    {
        foreach($sub_categories as $sub_sub_category){
            $data = array();
      
            if($sub_sub_category == 'Foundation') {
    
                foreach($months as $month){
    
                    if($month == 'EDP') {
                        $data[] = ($foundation_program_total == 0) ? 0 : round(($foundation_EDP * 100) / $foundation_program_total, 2);
                    } else if($month == 'QF1') {
                        $data[] = ($foundation_program_total == 0) ? 0 : round(($foundation_QF1 * 100) / $foundation_program_total, 2);
                    } else if($month == 'QF2') {
                        $data[] = ($foundation_program_total == 0) ? 0 : round(($foundation_QF2 * 100) / $foundation_program_total, 2);
                    } else if($month == 'QF3') {
                        $data[] = ($foundation_program_total == 0) ? 0 : round(($foundation_QF3 * 100) / $foundation_program_total, 2);
                    } else if($month == 'Malay') {
                        $data[] = ($foundation_ethnicity_total == 0) ? 0 : round(($foundation_Malay * 100) / $foundation_ethnicity_total, 2);
                    } else if($month == 'Chinese') {
                        $data[] = ($foundation_ethnicity_total == 0) ? 0 : round(($foundation_Chinese * 100) / $foundation_ethnicity_total, 2);
                    } else if($month == 'Indian') {
                        $data[] = ($foundation_ethnicity_total == 0) ? 0 : round(($foundation_Indian * 100) / $foundation_ethnicity_total, 2);
                    } else if($month == 'Others') {
                        $data[] = ($foundation_ethnicity_total == 0) ? 0 : round(($foundation_Others * 100) / $foundation_ethnicity_total, 2);
                    } else {
                        $data[] = "00.00";
                    }
                }
            } else if($sub_sub_category == 'Zhi Hui Mandarin') {
    
                foreach($months as $month){
    
                    if($month == 'QF1') {
                        $data[] = ($zhi_hui_mandarin_program_total == 0) ? 0 : round(($zhi_hui_mandarin_QF1 * 100) / $zhi_hui_mandarin_program_total, 2);
                    } else if($month == 'QF2') {
                        $data[] = ($zhi_hui_mandarin_program_total == 0) ? 0 : round(($zhi_hui_mandarin_QF2 * 100) / $zhi_hui_mandarin_program_total, 2);
                    } else if($month == 'QF3') {
                        $data[] = ($zhi_hui_mandarin_program_total == 0) ? 0 : round(($zhi_hui_mandarin_QF3 * 100) / $zhi_hui_mandarin_program_total, 2);
                    } else if($month == 'Malay') {
                        $data[] = ($zhi_hui_mandarin_ethnicity_total == 0) ? 0 : round(($zhi_hui_mandarin_Malay * 100) / $zhi_hui_mandarin_ethnicity_total, 2);
                    } else if($month == 'Chinese') {
                        $data[] = ($zhi_hui_mandarin_ethnicity_total == 0) ? 0 : round(($zhi_hui_mandarin_Chinese * 100) / $zhi_hui_mandarin_ethnicity_total, 2);
                    } else if($month == 'Indian') {
                        $data[] = ($zhi_hui_mandarin_ethnicity_total == 0) ? 0 : round(($zhi_hui_mandarin_Indian * 100) / $zhi_hui_mandarin_ethnicity_total, 2);
                    } else if($month == 'Others') {
                        $data[] = ($zhi_hui_mandarin_ethnicity_total == 0) ? 0 : round(($zhi_hui_mandarin_Others * 100) / $zhi_hui_mandarin_ethnicity_total, 2);
                    } else {
                        $data[] = "00.00";
                    }
                }
            } else if($sub_sub_category == 'Pendidikan Jawi') {
    
                foreach($months as $month){
    
                    if($month == 'QF1') {
                        $data[] = ($pendidikan_program_total == 0) ? 0 : round(($pendidikan_QF1 * 100) / $pendidikan_program_total, 2);
                    } else if($month == 'QF2') {
                        $data[] = ($pendidikan_program_total == 0) ? 0 : round(($pendidikan_QF2 * 100) / $pendidikan_program_total, 2);
                    } else if($month == 'QF3') {
                        $data[] = ($pendidikan_program_total == 0) ? 0 : round(($pendidikan_QF3 * 100) / $pendidikan_program_total, 2);
                    } else if($month == 'Malay') {
                        $data[] = ($pendidikan_ethnicity_total == 0) ? 0 : round(($pendidikan_Malay * 100) / $pendidikan_ethnicity_total, 2);
                    } else if($month == 'Chinese') {
                        $data[] = ($pendidikan_ethnicity_total == 0) ? 0 : round(($pendidikan_Chinese * 100) / $pendidikan_ethnicity_total, 2);
                    } else if($month == 'Indian') {
                        $data[] = ($pendidikan_ethnicity_total == 0) ? 0 : round(($pendidikan_Indian * 100) / $pendidikan_ethnicity_total, 2);
                    } else if($month == 'Others') {
                        $data[] = ($pendidikan_ethnicity_total == 0) ? 0 : round(($pendidikan_Others * 100) / $pendidikan_ethnicity_total, 2);
                    } else {
                        $data[] = "00.00";
                    }
                }
            } else {
                $data[] = "00.00";
            }
    
        $chart_data['datasets'][] = array(
            'label' => $sub_sub_category,
            'backgroundColor' => array_pop($colors),
            'data' => $data
        );
        }
    }
    else 
    {
        foreach($sub_categories as $sub_sub_category){
            $data = array();
      
            if($sub_sub_category == 'Foundation') {
    
                foreach($months as $month){
    
                    if($month == 'EDP') {
                        $data[] = $foundation_EDP;
                    } else if($month == 'QF1') {
                        $data[] = $foundation_QF1;
                    } else if($month == 'QF2') {
                        $data[] = $foundation_QF2;
                    } else if($month == 'QF3') {
                        $data[] = $foundation_QF3;
                    } else if($month == 'Malay') {
                        $data[] = $foundation_Malay;
                    } else if($month == 'Chinese') {
                        $data[] = $foundation_Chinese;
                    } else if($month == 'Indian') {
                        $data[] = $foundation_Indian;
                    } else if($month == 'Others') {
                        $data[] = $foundation_Others;
                    } else {
                        $data[] = "00.00";
                    }
                }
            } else if($sub_sub_category == 'Zhi Hui Mandarin') {
    
                foreach($months as $month){
    
                    if($month == 'QF1') {
                        $data[] = $zhi_hui_mandarin_QF1;
                    } else if($month == 'QF2') {
                        $data[] = $zhi_hui_mandarin_QF2;
                    } else if($month == 'QF3') {
                        $data[] = $zhi_hui_mandarin_QF3;
                    } else if($month == 'Malay') {
                        $data[] = $zhi_hui_mandarin_Malay;
                    } else if($month == 'Chinese') {
                        $data[] = $zhi_hui_mandarin_Chinese;
                    } else if($month == 'Indian') {
                        $data[] = $zhi_hui_mandarin_Indian;
                    } else if($month == 'Others') {
                        $data[] = $zhi_hui_mandarin_Others;
                    } else {
                        $data[] = "00.00";
                    }
                }
            } else if($sub_sub_category == 'Pendidikan Jawi') {
    
                foreach($months as $month){
    
                    if($month == 'QF1') {
                        $data[] = $pendidikan_QF1;
                    } else if($month == 'QF2') {
                        $data[] = $pendidikan_QF2;
                    } else if($month == 'QF3') {
                        $data[] = $pendidikan_QF3;
                    } else if($month == 'Malay') {
                        $data[] = $pendidikan_Malay;
                    } else if($month == 'Chinese') {
                        $data[] = $pendidikan_Chinese;
                    } else if($month == 'Indian') {
                        $data[] = $pendidikan_Indian;
                    } else if($month == 'Others') {
                        $data[] = $pendidikan_Others;
                    } else {
                        $data[] = "00.00";
                    }
                }
            
            } else {
                $data[] = "00.00";
            }
    
            $chart_data['datasets'][] = array(
                'label' => $sub_sub_category,
                'backgroundColor' => array_pop($colors),
                'data' => $data
            );
        }
    }
    
    return json_encode($chart_data);
}

function getChartDataEnhFoundation(){
    global $connection, $graph_type;
  
    $sub_categories = array(
      "English",
      "Mandarin",
      "Int Art",
      "IQ Maths"
    );
 
    $months= array();

    $months['QF1'] = 'QF1';
    $months['QF2'] = 'QF2';
    $months['QF3'] = 'QF3';
    $months['Malay'] = 'Malay';
    $months['Chinese'] = 'Chinese';
    $months['Indian'] = 'Indian';
    $months['Others'] = 'Others';
  
    $chart_data = array(
      'labels' => array_keys($months),
      'datasets' => array()
    );
  
    $colors = array(
      "#d7ccc8",
      "#ffccbc",
      "#ffecb3",
      "#81c784",
      "#64b5f6",
      "#e57373"
    );
  
    $english_QF1 = EnhFoundationQFLevelCount('QF1','English');
    $english_QF2 = EnhFoundationQFLevelCount('QF2','English');
    $english_QF3 = EnhFoundationQFLevelCount('QF3','English');

    $english_program_total = $english_QF1 + $english_QF2 + $english_QF3;
    
    $english_Malay = EnhFoundationEthnicityCount('Malay','English');
    $english_Chinese = EnhFoundationEthnicityCount('Chinese','English');
    $english_Indian = EnhFoundationEthnicityCount('Indian','English');
    $english_Others = EnhFoundationEthnicityCount('Others','English');

    $english_ethnicity_total = $english_Malay + $english_Chinese + $english_Indian + $english_Others;

    $mandarin_QF1 = EnhFoundationQFLevelCount('QF1','Mandarin');
    $mandarin_QF2 = EnhFoundationQFLevelCount('QF2','Mandarin');
    $mandarin_QF3 = EnhFoundationQFLevelCount('QF3','Mandarin');

    $mandarin_program_total = $mandarin_QF1 + $mandarin_QF2 + $mandarin_QF3;
    
    $mandarin_Malay = EnhFoundationEthnicityCount('Malay','Mandarin');
    $mandarin_Chinese = EnhFoundationEthnicityCount('Chinese','Mandarin');
    $mandarin_Indian = EnhFoundationEthnicityCount('Indian','Mandarin');
    $mandarin_Others = EnhFoundationEthnicityCount('Others','Mandarin');

    $mandarin_ethnicity_total = $mandarin_Malay + $mandarin_Chinese + $mandarin_Indian + $mandarin_Others;

    $int_art_QF1 = EnhFoundationQFLevelCount('QF1','Int Art');
    $int_art_QF2 = EnhFoundationQFLevelCount('QF2','Int Art');
    $int_art_QF3 = EnhFoundationQFLevelCount('QF3','Int Art');

    $int_art_program_total = $int_art_QF1 + $int_art_QF2 + $int_art_QF3;
    
    $int_art_Malay = EnhFoundationEthnicityCount('Malay','Int Art');
    $int_art_Chinese = EnhFoundationEthnicityCount('Chinese','Int Art');
    $int_art_Indian = EnhFoundationEthnicityCount('Indian','Int Art');
    $int_art_Others = EnhFoundationEthnicityCount('Others','Int Art');

    $int_art_ethnicity_total = $int_art_Malay + $int_art_Chinese + $int_art_Indian + $int_art_Others;

    $iq_math_QF1 = EnhFoundationQFLevelCount('QF1','IQ Maths');
    $iq_math_QF2 = EnhFoundationQFLevelCount('QF2','IQ Maths');
    $iq_math_QF3 = EnhFoundationQFLevelCount('QF3','IQ Maths');

    $iq_math_program_total = $iq_math_QF1 + $iq_math_QF2 + $iq_math_QF3;
    
    $iq_math_Malay = EnhFoundationEthnicityCount('Malay','IQ Maths');
    $iq_math_Chinese = EnhFoundationEthnicityCount('Chinese','IQ Maths');
    $iq_math_Indian = EnhFoundationEthnicityCount('Indian','IQ Maths');
    $iq_math_Others = EnhFoundationEthnicityCount('Others','IQ Maths');

    $iq_math_ethnicity_total = $iq_math_Malay + $iq_math_Chinese + $iq_math_Indian + $iq_math_Others;

    if($graph_type == 'per')
    {
        foreach($sub_categories as $sub_sub_category){
            $data = array();
      
            if($sub_sub_category == 'English') {
    
                foreach($months as $month){
    
                    if($month == 'QF1') {
                        $data[] = ($english_program_total == 0) ? 0 : round(($english_QF1 * 100) / $english_program_total, 2);
                    } else if($month == 'QF2') {
                        $data[] = ($english_program_total == 0) ? 0 : round(($english_QF2 * 100) / $english_program_total, 2);
                    } else if($month == 'QF3') {
                        $data[] = ($english_program_total == 0) ? 0 : round(($english_QF3 * 100) / $english_program_total, 2);
                    } else if($month == 'Malay') {
                        $data[] = ($english_ethnicity_total == 0) ? 0 : round(($english_Malay * 100) / $english_ethnicity_total, 2);
                    } else if($month == 'Chinese') {
                        $data[] = ($english_ethnicity_total == 0) ? 0 : round(($english_Chinese * 100) / $english_ethnicity_total, 2);
                    } else if($month == 'Indian') {
                        $data[] = ($english_ethnicity_total == 0) ? 0 : round(($english_Indian * 100) / $english_ethnicity_total, 2);
                    } else if($month == 'Others') {
                        $data[] = ($english_ethnicity_total == 0) ? 0 : round(($english_Others * 100) / $english_ethnicity_total, 2);
                    } else {
                        $data[] = "00.00";
                    }
                }
            } else if($sub_sub_category == 'Mandarin') {
    
                foreach($months as $month){
    
                    if($month == 'QF1') {
                        $data[] = ($mandarin_program_total == 0) ? 0 : round(($mandarin_QF1 * 100) / $mandarin_program_total, 2);
                    } else if($month == 'QF2') {
                        $data[] = ($mandarin_program_total == 0) ? 0 : round(($mandarin_QF2 * 100) / $mandarin_program_total, 2);
                    } else if($month == 'QF3') {
                        $data[] = ($mandarin_program_total == 0) ? 0 : round(($mandarin_QF3 * 100) / $mandarin_program_total, 2);
                    } else if($month == 'Malay') {
                        $data[] = ($mandarin_ethnicity_total == 0) ? 0 : round(($mandarin_Malay * 100) / $mandarin_ethnicity_total, 2);
                    } else if($month == 'Chinese') {
                        $data[] = ($mandarin_ethnicity_total == 0) ? 0 : round(($mandarin_Chinese * 100) / $mandarin_ethnicity_total, 2);
                    } else if($month == 'Indian') {
                        $data[] = ($mandarin_ethnicity_total == 0) ? 0 : round(($mandarin_Indian * 100) / $mandarin_ethnicity_total, 2);
                    } else if($month == 'Others') {
                        $data[] = ($mandarin_ethnicity_total == 0) ? 0 : round(($mandarin_Others * 100) / $mandarin_ethnicity_total, 2);
                    } else {
                        $data[] = "00.00";
                    }
                }
            } else if($sub_sub_category == 'Int Art') {
    
                foreach($months as $month){
    
                    if($month == 'QF1') {
                        $data[] = ($int_art_program_total == 0) ? 0 : round(($int_art_QF1 * 100) / $int_art_program_total, 2);
                    } else if($month == 'QF2') {
                        $data[] = ($int_art_program_total == 0) ? 0 : round(($int_art_QF2 * 100) / $int_art_program_total, 2);
                    } else if($month == 'QF3') {
                        $data[] = ($int_art_program_total == 0) ? 0 : round(($int_art_QF3 * 100) / $int_art_program_total, 2);
                    } else if($month == 'Malay') {
                        $data[] = ($int_art_ethnicity_total == 0) ? 0 : round(($int_art_Malay * 100) / $int_art_ethnicity_total, 2);
                    } else if($month == 'Chinese') {
                        $data[] = ($int_art_ethnicity_total == 0) ? 0 : round(($int_art_Chinese * 100) / $int_art_ethnicity_total, 2);
                    } else if($month == 'Indian') {
                        $data[] = ($int_art_ethnicity_total == 0) ? 0 : round(($int_art_Indian * 100) / $int_art_ethnicity_total, 2);
                    } else if($month == 'Others') {
                        $data[] = ($int_art_ethnicity_total == 0) ? 0 : round(($int_art_Others * 100) / $int_art_ethnicity_total, 2);
                    } else {
                        $data[] = "00.00";
                    }
                }
            } else if($sub_sub_category == 'IQ Maths') {
    
                foreach($months as $month){
    
                    if($month == 'QF1') {
                        $data[] = ($iq_math_program_total == 0) ? 0 : round(($iq_math_QF1 * 100) / $iq_math_program_total, 2);
                    } else if($month == 'QF2') {
                        $data[] = ($iq_math_program_total == 0) ? 0 : round(($iq_math_QF2 * 100) / $iq_math_program_total, 2);
                    } else if($month == 'QF3') {
                        $data[] = ($iq_math_program_total == 0) ? 0 : round(($iq_math_QF3 * 100) / $iq_math_program_total, 2);
                    } else if($month == 'Malay') {
                        $data[] = ($iq_math_ethnicity_total == 0) ? 0 : round(($iq_math_Malay * 100) / $iq_math_ethnicity_total, 2);
                    } else if($month == 'Chinese') {
                        $data[] = ($iq_math_ethnicity_total == 0) ? 0 : round(($iq_math_Chinese * 100) / $iq_math_ethnicity_total, 2);
                    } else if($month == 'Indian') {
                        $data[] = ($iq_math_ethnicity_total == 0) ? 0 : round(($iq_math_Indian * 100) / $iq_math_ethnicity_total, 2);
                    } else if($month == 'Others') {
                        $data[] = ($iq_math_ethnicity_total == 0) ? 0 : round(($iq_math_Others * 100) / $iq_math_ethnicity_total, 2);
                    } else {
                        $data[] = "00.00";
                    }
                }
            } else {
                $data[] = "00.00";
            }
    
        $chart_data['datasets'][] = array(
            'label' => $sub_sub_category,
            'backgroundColor' => array_pop($colors),
            'data' => $data
        );
        }
    }
    else 
    {
        foreach($sub_categories as $sub_sub_category){
            $data = array();
      
            if($sub_sub_category == 'English') {
    
                foreach($months as $month){
    
                    if($month == 'QF1') {
                        $data[] = $english_QF1;
                    } else if($month == 'QF2') {
                        $data[] = $english_QF2;
                    } else if($month == 'QF3') {
                        $data[] = $english_QF3;
                    } else if($month == 'Malay') {
                        $data[] = $english_Malay;
                    } else if($month == 'Chinese') {
                        $data[] = $english_Chinese;
                    } else if($month == 'Indian') {
                        $data[] = $english_Indian;
                    } else if($month == 'Others') {
                        $data[] = $english_Others;
                    } else {
                        $data[] = "00.00";
                    }
                }
            } else if($sub_sub_category == 'Mandarin') {
    
                foreach($months as $month){
    
                    if($month == 'QF1') {
                        $data[] = $mandarin_QF1;
                    } else if($month == 'QF2') {
                        $data[] = $mandarin_QF2;
                    } else if($month == 'QF3') {
                        $data[] = $mandarin_QF3;
                    } else if($month == 'Malay') {
                        $data[] = $mandarin_Malay;
                    } else if($month == 'Chinese') {
                        $data[] = $mandarin_Chinese;
                    } else if($month == 'Indian') {
                        $data[] = $mandarin_Indian;
                    } else if($month == 'Others') {
                        $data[] = $mandarin_Others;
                    } else {
                        $data[] = "00.00";
                    }
                }
            } else if($sub_sub_category == 'Int Art') {
    
                foreach($months as $month){
    
                    if($month == 'QF1') {
                        $data[] = $int_art_QF1;
                    } else if($month == 'QF2') {
                        $data[] = $int_art_QF2;
                    } else if($month == 'QF3') {
                        $data[] = $int_art_QF3;
                    } else if($month == 'Malay') {
                        $data[] = $int_art_Malay;
                    } else if($month == 'Chinese') {
                        $data[] = $int_art_Chinese;
                    } else if($month == 'Indian') {
                        $data[] = $int_art_Indian;
                    } else if($month == 'Others') {
                        $data[] = $int_art_Others;
                    } else {
                        $data[] = "00.00";
                    }
                }
            } else if($sub_sub_category == 'IQ Maths') {
    
                foreach($months as $month){
    
                    if($month == 'QF1') {
                        $data[] = $iq_math_QF1;
                    } else if($month == 'QF2') {
                        $data[] = $iq_math_QF2;
                    } else if($month == 'QF3') {
                        $data[] = $iq_math_QF3;
                    } else if($month == 'Malay') {
                        $data[] = $iq_math_Malay;
                    } else if($month == 'Chinese') {
                        $data[] = $iq_math_Chinese;
                    } else if($month == 'Indian') {
                        $data[] = $iq_math_Indian;
                    } else if($month == 'Others') {
                        $data[] = $iq_math_Others;
                    } else {
                        $data[] = "00.00";
                    }
                }
            } else {
                $data[] = "00.00";
            }
    
            $chart_data['datasets'][] = array(
                'label' => $sub_sub_category,
                'backgroundColor' => array_pop($colors),
                'data' => $data
            );
        }
    }
    
    return json_encode($chart_data);
}

function getChartDataAfternoonProg(){
    global $connection, $graph_type;
  
    $sub_categories = array(
      "Basic",
      "Basic + Robotics"
    );
 
    $months= array();

    $months['Junior'] = 'Junior';
    $months['Senior'] = 'Senior';
    $months['Malay'] = 'Malay';
    $months['Chinese'] = 'Chinese';
    $months['Indian'] = 'Indian';
    $months['Others'] = 'Others';
  
    $chart_data = array(
      'labels' => array_keys($months),
      'datasets' => array()
    );
  
    $colors = array(
      "#d7ccc8",
      "#ffccbc",
      "#ffecb3",
      "#81c784",
      "#64b5f6",
      "#e57373"
    );
  
    $basic_junior = AfternoonProgQFLevelCount('junior','Basic');
    $basic_senior = AfternoonProgQFLevelCount('senior','Basic');

    $basic_program_total = $basic_junior + $basic_senior;

    $basic_Malay = AfternoonProgEthnicityCount('Malay','Basic');
    $basic_Chinese = AfternoonProgEthnicityCount('Chinese','Basic');
    $basic_Indian = AfternoonProgEthnicityCount('Indian','Basic');
    $basic_Others = AfternoonProgEthnicityCount('Others','Basic');

    $basic_ethnicity_total = $basic_Malay + $basic_Chinese + $basic_Indian + $basic_Others;

    $basic_robotic_junior = AfternoonProgQFLevelCount('junior','Basic + Robotics');
    $basic_robotic_senior = AfternoonProgQFLevelCount('senior','Basic + Robotics');

    $basic_robotic_program_total = $basic_robotic_junior + $basic_robotic_senior;

    $basic_robotic_Malay = AfternoonProgEthnicityCount('Malay','Basic + Robotics');
    $basic_robotic_Chinese = AfternoonProgEthnicityCount('Chinese','Basic + Robotics');
    $basic_robotic_Indian = AfternoonProgEthnicityCount('Indian','Basic + Robotics');
    $basic_robotic_Others = AfternoonProgEthnicityCount('Others','Basic + Robotics');

    $basic_robotic_ethnicity_total = $basic_robotic_Malay + $basic_robotic_Chinese + $basic_robotic_Indian + $basic_robotic_Others;

    if($graph_type == 'per')
    {
        foreach($sub_categories as $sub_sub_category){
            $data = array();
      
            if($sub_sub_category == 'Basic') {
    
                foreach($months as $month){
    
                    if($month == 'Junior') {
                        $data[] = ($basic_program_total == 0) ? 0 : round(($basic_junior * 100) / $basic_program_total, 2);
                    } else if($month == 'Senior') {
                        $data[] = ($basic_program_total == 0) ? 0 : round(($basic_senior * 100) / $basic_program_total, 2);
                    } else if($month == 'Malay') {
                        $data[] = ($basic_ethnicity_total == 0) ? 0 : round(($basic_Malay * 100) / $basic_ethnicity_total, 2);
                    } else if($month == 'Chinese') {
                        $data[] = ($basic_ethnicity_total == 0) ? 0 : round(($basic_Chinese * 100) / $basic_ethnicity_total, 2);
                    } else if($month == 'Indian') {
                        $data[] = ($basic_ethnicity_total == 0) ? 0 : round(($basic_Indian * 100) / $basic_ethnicity_total, 2);
                    } else if($month == 'Others') {
                        $data[] = ($basic_ethnicity_total == 0) ? 0 : round(($basic_Others * 100) / $basic_ethnicity_total, 2);
                    } else {
                        $data[] = "00.00";
                    }
                }
            } else if($sub_sub_category == 'Basic + Robotics') {
    
                foreach($months as $month){
    
                    if($month == 'Junior') {
                        $data[] = ($basic_robotic_program_total == 0) ? 0 : round(($basic_robotic_junior * 100) / $basic_robotic_program_total, 2);
                    } else if($month == 'Senior') {
                        $data[] = ($basic_robotic_program_total == 0) ? 0 : round(($basic_robotic_senior * 100) / $basic_robotic_program_total, 2);
                    } else if($month == 'Malay') {
                        $data[] = ($basic_robotic_ethnicity_total == 0) ? 0 : round(($basic_robotic_Malay * 100) / $basic_robotic_ethnicity_total, 2);
                    } else if($month == 'Chinese') {
                        $data[] = ($basic_robotic_ethnicity_total == 0) ? 0 : round(($basic_robotic_Chinese * 100) / $basic_robotic_ethnicity_total, 2);
                    } else if($month == 'Indian') {
                        $data[] = ($basic_robotic_ethnicity_total == 0) ? 0 : round(($basic_robotic_Indian * 100) / $basic_robotic_ethnicity_total, 2);
                    } else if($month == 'Others') {
                        $data[] = ($basic_robotic_ethnicity_total == 0) ? 0 : round(($basic_robotic_Others * 100) / $basic_robotic_ethnicity_total, 2);
                    } else {
                        $data[] = "00.00";
                    }
                }
            } else {
                $data[] = "00.00";
            }
    
        $chart_data['datasets'][] = array(
            'label' => $sub_sub_category,
            'backgroundColor' => array_pop($colors),
            'data' => $data
        );
        }
    }
    else 
    {
        foreach($sub_categories as $sub_sub_category){
            $data = array();
      
            if($sub_sub_category == 'Basic') {
    
                foreach($months as $month){
    
                    if($month == 'Junior') {
                        $data[] = $basic_junior;
                    } else if($month == 'Senior') {
                        $data[] = $basic_senior;
                    } else if($month == 'Malay') {
                        $data[] = $basic_Malay;
                    } else if($month == 'Chinese') {
                        $data[] = $basic_Chinese;
                    } else if($month == 'Indian') {
                        $data[] = $basic_Indian;
                    } else if($month == 'Others') {
                        $data[] = $basic_Others;
                    } else {
                        $data[] = "00.00";
                    }
                }
            } else if($sub_sub_category == 'Basic + Robotics') {
    
                foreach($months as $month){
    
                    if($month == 'Junior') {
                        $data[] = $basic_robotic_junior;
                    } else if($month == 'Senior') {
                        $data[] = $basic_robotic_senior;
                    } else if($month == 'Malay') {
                        $data[] = $basic_robotic_Malay;
                    } else if($month == 'Chinese') {
                        $data[] = $basic_robotic_Chinese;
                    } else if($month == 'Indian') {
                        $data[] = $basic_robotic_Indian;
                    } else if($month == 'Others') {
                        $data[] = $basic_robotic_Others;
                    } else {
                        $data[] = "00.00";
                    }
                }
            } else {
                $data[] = "00.00";
            }
    
            $chart_data['datasets'][] = array(
                'label' => $sub_sub_category,
                'backgroundColor' => array_pop($colors),
                'data' => $data
            );
        }
    }
    
    return json_encode($chart_data);
}

function getChartDataEFPlus(){
    global $connection, $graph_type;
  
    $sub_categories = array(
      "Robotics"
    );
 
    $months= array();

    $months['Junior'] = 'Junior';
    $months['Senior'] = 'Senior';
    $months['Malay'] = 'Malay';
    $months['Chinese'] = 'Chinese';
    $months['Indian'] = 'Indian';
    $months['Others'] = 'Others';
  
    $chart_data = array(
      'labels' => array_keys($months),
      'datasets' => array()
    );
  
    $colors = array(
      "#d7ccc8",
      "#ffccbc",
      "#ffecb3",
      "#81c784",
      "#64b5f6",
      "#e57373"
    );
  
    $robotic_plus_junior = EFPlusQFLevelCount('junior');
    $robotic_plus_senior = EFPlusQFLevelCount('senior');

    $robotic_plus_program_total = $robotic_plus_junior + $robotic_plus_senior;

    $robotic_plus_Malay = EFPlusEthnicityCount('Malay');
    $robotic_plus_Chinese = EFPlusEthnicityCount('Chinese');
    $robotic_plus_Indian = EFPlusEthnicityCount('Indian');
    $robotic_plus_Others = EFPlusEthnicityCount('Others');

    $robotic_plus_ethnicity_total = $robotic_plus_Malay + $robotic_plus_Chinese + $robotic_plus_Indian + $robotic_plus_Others;

    if($graph_type == 'per')
    {
        foreach($sub_categories as $sub_sub_category){
            $data = array();
      
            if($sub_sub_category == 'Robotics') {
    
                foreach($months as $month){
    
                    if($month == 'Junior') {
                        $data[] = ($robotic_plus_program_total == 0) ? 0 : round(($robotic_plus_junior * 100) / $robotic_plus_program_total, 2);
                    } else if($month == 'Senior') {
                        $data[] = ($robotic_plus_program_total == 0) ? 0 : round(($robotic_plus_senior * 100) / $robotic_plus_program_total, 2);
                    } else if($month == 'Malay') {
                        $data[] = ($robotic_plus_ethnicity_total == 0) ? 0 : round(($robotic_plus_Malay * 100) / $robotic_plus_ethnicity_total, 2);
                    } else if($month == 'Chinese') {
                        $data[] = ($robotic_plus_ethnicity_total == 0) ? 0 : round(($robotic_plus_Chinese * 100) / $robotic_plus_ethnicity_total, 2);
                    } else if($month == 'Indian') {
                        $data[] = ($robotic_plus_ethnicity_total == 0) ? 0 : round(($robotic_plus_Indian * 100) / $robotic_plus_ethnicity_total, 2);
                    } else if($month == 'Others') {
                        $data[] = ($robotic_plus_ethnicity_total == 0) ? 0 : round(($robotic_plus_Others * 100) / $robotic_plus_ethnicity_total, 2);
                    } else {
                        $data[] = "00.00";
                    }
                }
            } else {
                $data[] = "00.00";
            }
    
        $chart_data['datasets'][] = array(
            'label' => $sub_sub_category,
            'backgroundColor' => array_pop($colors),
            'data' => $data
        );
        }
    }
    else 
    {
        foreach($sub_categories as $sub_sub_category){
            $data = array();
      
            if($sub_sub_category == 'Robotics') {
    
                foreach($months as $month){
    
                    if($month == 'Junior') {
                        $data[] = $robotic_plus_junior;
                    } else if($month == 'Senior') {
                        $data[] = $robotic_plus_senior;
                    } else if($month == 'Malay') {
                        $data[] = $robotic_plus_Malay;
                    } else if($month == 'Chinese') {
                        $data[] = $robotic_plus_Chinese;
                    } else if($month == 'Indian') {
                        $data[] = $robotic_plus_Indian;
                    } else if($month == 'Others') {
                        $data[] = $robotic_plus_Others;
                    } else {
                        $data[] = "00.00";
                    }
                }

            } else {
                $data[] = "00.00";
            }
    
            $chart_data['datasets'][] = array(
                'label' => $sub_sub_category,
                'backgroundColor' => array_pop($colors),
                'data' => $data
            );
        }
    }
    
    return json_encode($chart_data);
}

function getChartDataStuGeographical(){
    global $connection, $graph_type;
  
    $sub_categories = array(
      "Malay",
      "Chinese",
      "Indian",
      "Others"
    );
 
    $months= array();

    $months['Johor'] = 'Johor';
    $months['Melaka'] = 'Melaka';
    $months['N. Sembilan'] = 'N. Sembilan';
    $months['Selangor'] = 'Selangor';
    $months['FT - KL'] = 'FT - KL';
    $months['FT - Labuan'] = 'FT - Labuan';
    $months['FT - Putrajaya'] = 'FT - Putrajaya';
    $months['Perak'] = 'Perak';
    $months['Penang'] = 'Penang';
    $months['Perlis'] = 'Perlis';
    $months['Kelantan'] = 'Kelantan';
    $months['Terengganu'] = 'Terengganu';
    $months['Pahang'] = 'Pahang';
    $months['Sabah'] = 'Sabah';
    $months['Sarawak'] = 'Sarawak';
  
    $chart_data = array(
      'labels' => array_keys($months),
      'datasets' => array()
    );
  
    $colors = array(
      "#d7ccc8",
      "#ffccbc",
      "#ffecb3",
      "#81c784",
      "#64b5f6",
      "#e57373"
    );
  
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

    if($graph_type == 'per')
    {
        foreach($sub_categories as $sub_sub_category){
            $data = array();
      
            if($sub_sub_category == 'Malay') {
    
                foreach($months as $month){

                    if($month == 'Johor') {
                        $data[] = ($malay_total == 0) ? 0 : round(($malay_johor * 100) / $malay_total, 2);
                    } else if($month == 'Melaka') {
                        $data[] = ($malay_total == 0) ? 0 : round(($malay_melaka * 100) / $malay_total, 2);
                    } else if($month == 'N. Sembilan') {
                        $data[] = ($malay_total == 0) ? 0 : round(($malay_N_sembilan * 100) / $malay_total, 2);
                    } else if($month == 'Selangor') {
                        $data[] = ($malay_total == 0) ? 0 : round(($malay_selangor * 100) / $malay_total, 2);
                    } else if($month == 'FT - KL') {
                        $data[] = ($malay_total == 0) ? 0 : round(($malay_FT_KL * 100) / $malay_total, 2);
                    } else if($month == 'FT - Labuan') {
                        $data[] = ($malay_total == 0) ? 0 : round(($malay_FT_labuan * 100) / $malay_total, 2);
                    } else if($month == 'FT - Putrajaya') {
                        $data[] = ($malay_total == 0) ? 0 : round(($malay_FT_putrajaya * 100) / $malay_total, 2);
                    } else if($month == 'Perak') {
                        $data[] = ($malay_total == 0) ? 0 : round(($malay_perak * 100) / $malay_total, 2);
                    } else if($month == 'Penang') {
                        $data[] = ($malay_total == 0) ? 0 : round(($malay_penang * 100) / $malay_total, 2);
                    } else if($month == 'Perlis') {
                        $data[] = ($malay_total == 0) ? 0 : round(($malay_perlis * 100) / $malay_total, 2);
                    } else if($month == 'Kelantan') {
                        $data[] = ($malay_total == 0) ? 0 : round(($malay_kelantan * 100) / $malay_total, 2);
                    } else if($month == 'Terengganu') {
                        $data[] = ($malay_total == 0) ? 0 : round(($malay_terengganu * 100) / $malay_total, 2);
                    } else if($month == 'Pahang') {
                        $data[] = ($malay_total == 0) ? 0 : round(($malay_pahang * 100) / $malay_total, 2);
                    } else if($month == 'Sabah') {
                        $data[] = ($malay_total == 0) ? 0 : round(($malay_sabah * 100) / $malay_total, 2);
                    } else if($month == 'Sarawak') {
                        $data[] = ($malay_total == 0) ? 0 : round(($malay_sarawak * 100) / $malay_total, 2);
                    } else {
                        $data[] = "00.00";
                    }
                }
            } else if($sub_sub_category == 'Chinese') {
    
                foreach($months as $month){

                    if($month == 'Johor') {
                        $data[] = ($chinese_total == 0) ? 0 : round(($chinese_johor * 100) / $chinese_total, 2);
                    } else if($month == 'Melaka') {
                        $data[] = ($chinese_total == 0) ? 0 : round(($chinese_melaka * 100) / $chinese_total, 2);
                    } else if($month == 'N. Sembilan') {
                        $data[] = ($chinese_total == 0) ? 0 : round(($chinese_N_sembilan * 100) / $chinese_total, 2);
                    } else if($month == 'Selangor') {
                        $data[] = ($chinese_total == 0) ? 0 : round(($chinese_selangor * 100) / $chinese_total, 2);
                    } else if($month == 'FT - KL') {
                        $data[] = ($chinese_total == 0) ? 0 : round(($chinese_FT_KL * 100) / $chinese_total, 2);
                    } else if($month == 'FT - Labuan') {
                        $data[] = ($chinese_total == 0) ? 0 : round(($chinese_FT_labuan * 100) / $chinese_total, 2);
                    } else if($month == 'FT - Putrajaya') {
                        $data[] = ($chinese_total == 0) ? 0 : round(($chinese_FT_putrajaya * 100) / $chinese_total, 2);
                    } else if($month == 'Perak') {
                        $data[] = ($chinese_total == 0) ? 0 : round(($chinese_perak * 100) / $chinese_total, 2);
                    } else if($month == 'Penang') {
                        $data[] = ($chinese_total == 0) ? 0 : round(($chinese_penang * 100) / $chinese_total, 2);
                    } else if($month == 'Perlis') {
                        $data[] = ($chinese_total == 0) ? 0 : round(($chinese_perlis * 100) / $chinese_total, 2);
                    } else if($month == 'Kelantan') {
                        $data[] = ($chinese_total == 0) ? 0 : round(($chinese_kelantan * 100) / $chinese_total, 2);
                    } else if($month == 'Terengganu') {
                        $data[] = ($chinese_total == 0) ? 0 : round(($chinese_terengganu * 100) / $chinese_total, 2);
                    } else if($month == 'Pahang') {
                        $data[] = ($chinese_total == 0) ? 0 : round(($chinese_pahang * 100) / $chinese_total, 2);
                    } else if($month == 'Sabah') {
                        $data[] = ($chinese_total == 0) ? 0 : round(($chinese_sabah * 100) / $chinese_total, 2);
                    } else if($month == 'Sarawak') {
                        $data[] = ($chinese_total == 0) ? 0 : round(($chinese_sarawak * 100) / $chinese_total, 2);
                    } else {
                        $data[] = "00.00";
                    }
                }
            } else if($sub_sub_category == 'Indian') {
    
                foreach($months as $month){

                    if($month == 'Johor') {
                        $data[] = ($indian_total == 0) ? 0 : round(($indian_johor * 100) / $indian_total, 2);
                    } else if($month == 'Melaka') {
                        $data[] = ($indian_total == 0) ? 0 : round(($indian_melaka * 100) / $indian_total, 2);
                    } else if($month == 'N. Sembilan') {
                        $data[] = ($indian_total == 0) ? 0 : round(($indian_N_sembilan * 100) / $indian_total, 2);
                    } else if($month == 'Selangor') {
                        $data[] = ($indian_total == 0) ? 0 : round(($indian_selangor * 100) / $indian_total, 2);
                    } else if($month == 'FT - KL') {
                        $data[] = ($indian_total == 0) ? 0 : round(($indian_FT_KL * 100) / $indian_total, 2);
                    } else if($month == 'FT - Labuan') {
                        $data[] = ($indian_total == 0) ? 0 : round(($indian_FT_labuan * 100) / $indian_total, 2);
                    } else if($month == 'FT - Putrajaya') {
                        $data[] = ($indian_total == 0) ? 0 : round(($indian_FT_putrajaya * 100) / $indian_total, 2);
                    } else if($month == 'Perak') {
                        $data[] = ($indian_total == 0) ? 0 : round(($indian_perak * 100) / $indian_total, 2);
                    } else if($month == 'Penang') {
                        $data[] = ($indian_total == 0) ? 0 : round(($indian_penang * 100) / $indian_total, 2);
                    } else if($month == 'Perlis') {
                        $data[] = ($indian_total == 0) ? 0 : round(($indian_perlis * 100) / $indian_total, 2);
                    } else if($month == 'Kelantan') {
                        $data[] = ($indian_total == 0) ? 0 : round(($indian_kelantan * 100) / $indian_total, 2);
                    } else if($month == 'Terengganu') {
                        $data[] = ($indian_total == 0) ? 0 : round(($indian_terengganu * 100) / $indian_total, 2);
                    } else if($month == 'Pahang') {
                        $data[] = ($indian_total == 0) ? 0 : round(($indian_pahang * 100) / $indian_total, 2);
                    } else if($month == 'Sabah') {
                        $data[] = ($indian_total == 0) ? 0 : round(($indian_sabah * 100) / $indian_total, 2);
                    } else if($month == 'Sarawak') {
                        $data[] = ($indian_total == 0) ? 0 : round(($indian_sarawak * 100) / $indian_total, 2);
                    } else {
                        $data[] = "00.00";
                    }
                }
            } else if($sub_sub_category == 'Others') {
    
                foreach($months as $month){

                    if($month == 'Johor') {
                        $data[] = ($others_total == 0) ? 0 : round(($others_johor * 100) / $others_total, 2);
                    } else if($month == 'Melaka') {
                        $data[] = ($others_total == 0) ? 0 : round(($others_melaka * 100) / $others_total, 2);
                    } else if($month == 'N. Sembilan') {
                        $data[] = ($others_total == 0) ? 0 : round(($others_N_sembilan * 100) / $others_total, 2);
                    } else if($month == 'Selangor') {
                        $data[] = ($others_total == 0) ? 0 : round(($others_selangor * 100) / $others_total, 2);
                    } else if($month == 'FT - KL') {
                        $data[] = ($others_total == 0) ? 0 : round(($others_FT_KL * 100) / $others_total, 2);
                    } else if($month == 'FT - Labuan') {
                        $data[] = ($others_total == 0) ? 0 : round(($others_FT_labuan * 100) / $others_total, 2);
                    } else if($month == 'FT - Putrajaya') {
                        $data[] = ($others_total == 0) ? 0 : round(($others_FT_putrajaya * 100) / $others_total, 2);
                    } else if($month == 'Perak') {
                        $data[] = ($others_total == 0) ? 0 : round(($others_perak * 100) / $others_total, 2);
                    } else if($month == 'Penang') {
                        $data[] = ($others_total == 0) ? 0 : round(($others_penang * 100) / $others_total, 2);
                    } else if($month == 'Perlis') {
                        $data[] = ($others_total == 0) ? 0 : round(($others_perlis * 100) / $others_total, 2);
                    } else if($month == 'Kelantan') {
                        $data[] = ($others_total == 0) ? 0 : round(($others_kelantan * 100) / $others_total, 2);
                    } else if($month == 'Terengganu') {
                        $data[] = ($others_total == 0) ? 0 : round(($others_terengganu * 100) / $others_total, 2);
                    } else if($month == 'Pahang') {
                        $data[] = ($others_total == 0) ? 0 : round(($others_pahang * 100) / $others_total, 2);
                    } else if($month == 'Sabah') {
                        $data[] = ($others_total == 0) ? 0 : round(($others_sabah * 100) / $others_total, 2);
                    } else if($month == 'Sarawak') {
                        $data[] = ($others_total == 0) ? 0 : round(($others_sarawak * 100) / $others_total, 2);
                    } else {
                        $data[] = "00.00";
                    }
                }
            } else {
                $data[] = "00.00";
            }
    
        $chart_data['datasets'][] = array(
            'label' => $sub_sub_category,
            'backgroundColor' => array_pop($colors),
            'data' => $data
        );
        }
    }
    else 
    {
        foreach($sub_categories as $sub_sub_category){
            $data = array();
      
            if($sub_sub_category == 'Malay') {
    
                foreach($months as $month){

                    if($month == 'Johor') {
                        $data[] = $malay_johor;
                    } else if($month == 'Melaka') {
                        $data[] = $malay_melaka;
                    } else if($month == 'N. Sembilan') {
                        $data[] = $malay_N_sembilan;
                    } else if($month == 'Selangor') {
                        $data[] = $malay_selangor;
                    } else if($month == 'FT - KL') {
                        $data[] = $malay_FT_KL;
                    } else if($month == 'FT - Labuan') {
                        $data[] = $malay_FT_labuan;
                    } else if($month == 'FT - Putrajaya') {
                        $data[] = $malay_FT_putrajaya;
                    } else if($month == 'Perak') {
                        $data[] = $malay_perak;
                    } else if($month == 'Penang') {
                        $data[] = $malay_penang;
                    } else if($month == 'Perlis') {
                        $data[] = $malay_perlis;
                    } else if($month == 'Kelantan') {
                        $data[] = $malay_kelantan;
                    } else if($month == 'Terengganu') {
                        $data[] = $malay_terengganu;
                    } else if($month == 'Pahang') {
                        $data[] = $malay_pahang;
                    } else if($month == 'Sabah') {
                        $data[] = $malay_sabah;
                    } else if($month == 'Sarawak') {
                        $data[] = $malay_sarawak;
                    } else {
                        $data[] = "00.00";
                    }
                }
            } else if($sub_sub_category == 'Chinese') {
    
                foreach($months as $month){

                    if($month == 'Johor') {
                        $data[] = $chinese_johor;
                    } else if($month == 'Melaka') {
                        $data[] = $chinese_melaka;
                    } else if($month == 'N. Sembilan') {
                        $data[] = $chinese_N_sembilan;
                    } else if($month == 'Selangor') {
                        $data[] = $chinese_selangor;
                    } else if($month == 'FT - KL') {
                        $data[] = $chinese_FT_KL;
                    } else if($month == 'FT - Labuan') {
                        $data[] = $chinese_FT_labuan;
                    } else if($month == 'FT - Putrajaya') {
                        $data[] = $chinese_FT_putrajaya;
                    } else if($month == 'Perak') {
                        $data[] = $chinese_perak;
                    } else if($month == 'Penang') {
                        $data[] = $chinese_penang;
                    } else if($month == 'Perlis') {
                        $data[] = $chinese_perlis;
                    } else if($month == 'Kelantan') {
                        $data[] = $chinese_kelantan;
                    } else if($month == 'Terengganu') {
                        $data[] = $chinese_terengganu;
                    } else if($month == 'Pahang') {
                        $data[] = $chinese_pahang;
                    } else if($month == 'Sabah') {
                        $data[] = $chinese_sabah;
                    } else if($month == 'Sarawak') {
                        $data[] = $chinese_sarawak;
                    } else {
                        $data[] = "00.00";
                    }
                }
            } else if($sub_sub_category == 'Indian') {
    
                foreach($months as $month){

                    if($month == 'Johor') {
                        $data[] = $indian_johor;
                    } else if($month == 'Melaka') {
                        $data[] = $indian_melaka;
                    } else if($month == 'N. Sembilan') {
                        $data[] = $indian_N_sembilan;
                    } else if($month == 'Selangor') {
                        $data[] = $indian_selangor;
                    } else if($month == 'FT - KL') {
                        $data[] = $indian_FT_KL;
                    } else if($month == 'FT - Labuan') {
                        $data[] = $indian_FT_labuan;
                    } else if($month == 'FT - Putrajaya') {
                        $data[] = $indian_FT_putrajaya;
                    } else if($month == 'Perak') {
                        $data[] = $indian_perak;
                    } else if($month == 'Penang') {
                        $data[] = $indian_penang;
                    } else if($month == 'Perlis') {
                        $data[] = $indian_perlis;
                    } else if($month == 'Kelantan') {
                        $data[] = $indian_kelantan;
                    } else if($month == 'Terengganu') {
                        $data[] = $indian_terengganu;
                    } else if($month == 'Pahang') {
                        $data[] = $indian_pahang;
                    } else if($month == 'Sabah') {
                        $data[] = $indian_sabah;
                    } else if($month == 'Sarawak') {
                        $data[] = $indian_sarawak;
                    } else {
                        $data[] = "00.00";
                    }
                }
            } else if($sub_sub_category == 'Others') {
    
                foreach($months as $month){

                    if($month == 'Johor') {
                        $data[] = $others_johor;
                    } else if($month == 'Melaka') {
                        $data[] = $others_melaka;
                    } else if($month == 'N. Sembilan') {
                        $data[] = $others_N_sembilan;
                    } else if($month == 'Selangor') {
                        $data[] = $others_selangor;
                    } else if($month == 'FT - KL') {
                        $data[] = $others_FT_KL;
                    } else if($month == 'FT - Labuan') {
                        $data[] = $others_FT_labuan;
                    } else if($month == 'FT - Putrajaya') {
                        $data[] = $others_FT_putrajaya;
                    } else if($month == 'Perak') {
                        $data[] = $others_perak;
                    } else if($month == 'Penang') {
                        $data[] = $others_penang;
                    } else if($month == 'Perlis') {
                        $data[] = $others_perlis;
                    } else if($month == 'Kelantan') {
                        $data[] = $others_kelantan;
                    } else if($month == 'Terengganu') {
                        $data[] = $others_terengganu;
                    } else if($month == 'Pahang') {
                        $data[] = $others_pahang;
                    } else if($month == 'Sabah') {
                        $data[] = $others_sabah;
                    } else if($month == 'Sarawak') {
                        $data[] = $others_sarawak;
                    } else {
                        $data[] = "00.00";
                    }
                }
            } else {
                $data[] = "00.00";
            }
    
            $chart_data['datasets'][] = array(
                'label' => $sub_sub_category,
                'backgroundColor' => array_pop($colors),
                'data' => $data
            );
        }
    }
    
    return json_encode($chart_data);
}
?>

<?php
if ($_SESSION["isLogin"]==1) {
?>
<div class="uk-margin-right">

    <div class="uk-width-1-1 myheader text-center mt-5" style="color:white; text-align:center;">
        <h2 class="uk-text-center myheader-text-color myheader-text-style">Monthly Student No Analytics by Program and Ethnicity </h2><h3 class="uk-text-center myheader-text-color myheader-text-style"></h3>
    </div>

<div class="uk-overflow-container">
<div class="uk-grid">
  <div class="uk-width-medium-5-10">
    <table class="uk-table">
      <tr>
        <td class="uk-text-bold">Month</td>
        <td>
          <?php echo ($selected_month == 'ALL') ? 'All Months' : date('M Y',strtotime($selected_month)); ?>
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
        </style>

        <div class="chartmain header-bottom-part">

            <canvas id="myChartattach" style="position: relative; height:80vh; width:80vw"></canvas>
        </div>

    </div>
</div>


    <script>
        $(document).ready(function() {
        var method = '<?php echo $method ?>';
        if (method == "print") {
            window.print();
        }
        });
    </script>

    <script>
        jQuery(document).ready(function() {

            if ($("#myChartattach").length) {

                <?php if($program == 'Half Day Prog and Ethnicity') {?> 

                    var activityChartData = <?php echo getChartDataHalfDay(); ?> ;
                
                <?php } else if($program == 'Enh Foundation and Ethnicity') { ?>

                    var activityChartData = <?php echo getChartDataEnhFoundation(); ?> ;

                <?php } else if($program == 'Afternoon Prog and Ethnicity') { ?>

                    var activityChartData = <?php echo getChartDataAfternoonProg(); ?> ;

                <?php } else if($program == 'EF Plus and Ethnicity') { ?>

                    var activityChartData = <?php echo getChartDataEFPlus(); ?> ;
                    
                <?php } else if($program == 'Stu No Geographical Distribution and Ethnicity') { ?>

                    var activityChartData = <?php echo getChartDataStuGeographical(); ?> ;

                <?php } ?>
                
                console.log(activityChartData);
                var activityChartCanvas = $("#myChartattach").get(0).getContext("2d");

                var activityChart = new Chart(activityChartCanvas, {
                    type: 'bar',
                    data: activityChartData,
                    options: {
                        tooltips: {
                            mode: 'index',
                            intersect: false
                        },
                        responsive: true
                    }
                });
            }
        });
    </script>

    <style type="text/css" media="print">
        body {
        zoom:75%; /*or whatever percentage you need, play around with this number*/
        }
    </style>
<?php } ?>
