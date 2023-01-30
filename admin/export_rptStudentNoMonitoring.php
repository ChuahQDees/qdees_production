<?php
session_start();
$session_id=session_id();
$year=$_SESSION["Year"];

include_once("../mysql.php");
include_once("functions.php");
include_once("../search_new.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); 
}
$current_date = date('Y-m-d');
$centre_code = (isset($centre_code) && $centre_code != '') ? $centre_code : 'ALL';

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

?>

<?php
if ($_SESSION["isLogin"]==1) {

    $filename = 'student_no_monitoring_'.date('Y-m-d').'.csv';

    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");

    $content = array();

    $centre = ($centre_code == 'ALL') ? 'All Centre' : getCentreName($centre_code);

    $content[] = array("Centre Name : ",$centre);

    $content[] = array("Prepare By : ",$_SESSION["UserName"]);

    $content[] = array("Academic Year : ",$_SESSION['Year']);

    $content[] = array("Date of submission : ",date("Y-m-d H:i:s"));

    $content[] = array("","","","");

    $content[] = array("No.","Centre","Half Day Prog","","","","","","","","","","","","","Enh Foundation","","","","","","","","","","","","","","","","","Afternoon Prog","","","","EF Plus","","");

    $content[] = array("","","Foundation","","","","","Zhi Hui Mandarin","","","","Pendidikan Jawi","","","","English","","","","Mandarin","","","","Int Art","","","","IQ Maths","","","","Grand Total","Basic","Basic + Robotics","","","Robotics","","");

    $content[] = array("","","EDP","QF1","QF2","QF3","Total","QF1","QF2","QF3","Total","QF1","QF2","QF3","Total","QF1","QF2","QF3","Total","QF1","QF2","QF3","Total","QF1","QF2","QF3","Total","QF1","QF2","QF3","Total","","","Junior","Senior","Total","Junior","Senior","Total");

    if($centre_code == 'ALL') {
        $centre = mysqli_query($connection,"SELECT * FROM `centre` WHERE `status` = 'A'");
    } else {
        $centre = mysqli_query($connection,"SELECT * FROM `centre` WHERE `status` = 'A' AND `centre_code` = '$centre_code'");
    }
    
    $i = 0;

    $foundation_EDP_grand_total = $foundation_QF1_grand_total = $foundation_QF2_grand_total = $foundation_QF3_grand_total = $zhi_hui_mandarin_QF1_grand_total = $zhi_hui_mandarin_QF2_grand_total = $zhi_hui_mandarin_QF3_grand_total = $pendidikan_QF1_grand_total = $pendidikan_QF2_grand_total = $pendidikan_QF3_grand_total = $english_QF1_grand_total = $english_QF2_grand_total = $english_QF3_grand_total = $art_QF1_grand_total = $art_QF2_grand_total = $art_QF3_grand_total = $iq_QF1_grand_total = $iq_QF2_grand_total = $iq_QF3_grand_total = $mandarin_QF1_grand_total = $mandarin_QF2_grand_total = $mandarin_QF3_grand_total = $basic_grand_total = $basic_robotic_junior_grand_total = $basic_robotic_senior_grand_total = $robotic_junior_grand_total = $robotic_senior_grand_total = 0;
    
    $enh_foundation_grand_total = 0;

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

        $excel_row = array();

        $excel_row[] = $i;
        $excel_row[] = $centre_row['company_name'];

        // Foundation Start

        $sql="SELECT count(`collection`.`id`) as level_count from `collection` LEFT JOIN `product` ON `product`.`product_code` = `collection`.`product_code` where (`collection`.`product_code` like 'MY-EDP1.EARLY-DIS%' OR `collection`.`product_code` like 'MY-EDP2.EARLY-DIS%' OR `collection`.`product_code` like 'MY-EDP3.EARLY-DIS%' OR `collection`.`product_code` like 'MY-EDP4.EARLY-DIS%') and (collection_date_time BETWEEN '$year_start_date' AND '$year_end_date') and `collection`.`centre_code`='$centre_code' and `collection`.`void`=0 and `product`.`sub_sub_category` = 'EDP'";

        $resultt=mysqli_query($connection, $sql);
                
        $num_row=mysqli_num_rows($resultt);
        $level_count = 0;
        while ($roww=mysqli_fetch_assoc($resultt)) {
            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
        }

        $excel_row[] = $level_count;

        $foundation_total += $level_count;
        $foundation_EDP_grand_total += $level_count;
        
        $sql="SELECT count(`collection`.`id`) as level_count from `collection` LEFT JOIN `product` ON `product`.`product_code` = `collection`.`product_code` where `collection`.`product_code` like 'MY-MODULE%' and (collection_date_time BETWEEN '$year_start_date' AND '$year_end_date') and `collection`.`centre_code`='$centre_code' and `collection`.`void`=0 and `product`.`sub_sub_category` = 'QF1'";

        $resultt=mysqli_query($connection, $sql);
                
        $num_row=mysqli_num_rows($resultt);
        $level_count = 0;
        while ($roww=mysqli_fetch_assoc($resultt)) {
            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
        }

        $excel_row[] = $level_count;

        $foundation_total += $level_count;
        $foundation_QF1_grand_total += $level_count;
    
        $sql="SELECT count(`collection`.`id`) as level_count from `collection` LEFT JOIN `product` ON `product`.`product_code` = `collection`.`product_code` where `collection`.`product_code` like 'MY-MODULE%' and (collection_date_time BETWEEN '$year_start_date' AND '$year_end_date') and `collection`.`centre_code`='$centre_code' and `collection`.`void`=0 and `product`.`sub_sub_category` = 'QF2'";

        $resultt=mysqli_query($connection, $sql);
                
        $num_row=mysqli_num_rows($resultt);
        $level_count = 0;
        while ($roww=mysqli_fetch_assoc($resultt)) {
            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
        }

        $excel_row[] = $level_count;

        $foundation_total += $level_count;
        $foundation_QF2_grand_total += $level_count;
        
        $sql="SELECT count(`collection`.`id`) as level_count from `collection` LEFT JOIN `product` ON `product`.`product_code` = `collection`.`product_code` where `collection`.`product_code` like 'MY-MODULE%' and (collection_date_time BETWEEN '$year_start_date' AND '$year_end_date') and `collection`.`centre_code`='$centre_code' and `collection`.`void`=0 and `product`.`sub_sub_category` = 'QF3'";

        $resultt=mysqli_query($connection, $sql);
                
        $num_row=mysqli_num_rows($resultt);
        $level_count = 0;
        while ($roww=mysqli_fetch_assoc($resultt)) {
            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
        }

        $excel_row[] = $level_count;

        $foundation_total += $level_count;
        $foundation_QF3_grand_total += $level_count;
        
        $excel_row[] = $foundation_total; 
    
        // Zhi Hui Mandrin Start 

        $sql="SELECT count(`collection`.`id`) as level_count from `collection` LEFT JOIN `product` ON `product`.`product_code` = `collection`.`product_code` where `collection`.`product_code` like 'MY-ZHM%' and (collection_date_time BETWEEN '$year_start_date' AND '$year_end_date') and `collection`.`centre_code`='$centre_code' and `collection`.`void`=0 and `product`.`sub_sub_category` = 'QF1'";

        $resultt=mysqli_query($connection, $sql);
                
        $num_row=mysqli_num_rows($resultt);
        $level_count = 0;
        while ($roww=mysqli_fetch_assoc($resultt)) {
            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
        }

        $excel_row[] = $level_count;

        $zhi_hui_mandarin_total += $level_count;
        $zhi_hui_mandarin_QF1_grand_total += $level_count;
    
        $sql="SELECT count(`collection`.`id`) as level_count from `collection` LEFT JOIN `product` ON `product`.`product_code` = `collection`.`product_code` where `collection`.`product_code` like 'MY-ZHM%' and (collection_date_time BETWEEN '$year_start_date' AND '$year_end_date') and `collection`.`centre_code`='$centre_code' and `collection`.`void`=0 and `product`.`sub_sub_category` = 'QF2'";

        $resultt=mysqli_query($connection, $sql);
                
        $num_row=mysqli_num_rows($resultt);
        $level_count = 0;
        while ($roww=mysqli_fetch_assoc($resultt)) {
            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
        }

        $excel_row[] = $level_count;

        $zhi_hui_mandarin_total += $level_count;
        $zhi_hui_mandarin_QF2_grand_total += $level_count;
    
        $sql="SELECT count(`collection`.`id`) as level_count from `collection` LEFT JOIN `product` ON `product`.`product_code` = `collection`.`product_code` where `collection`.`product_code` like 'MY-ZHM%' and (collection_date_time BETWEEN '$year_start_date' AND '$year_end_date') and `collection`.`centre_code`='$centre_code' and `collection`.`void`=0 and `product`.`sub_sub_category` = 'QF3'";

        $resultt=mysqli_query($connection, $sql);
                
        $num_row=mysqli_num_rows($resultt);
        $level_count = 0;
        while ($roww=mysqli_fetch_assoc($resultt)) {
            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
        }

        $excel_row[] = $level_count;

        $zhi_hui_mandarin_total += $level_count;
        $zhi_hui_mandarin_QF3_grand_total += $level_count;
    
        $excel_row[] = $zhi_hui_mandarin_total; 

        // Pendidikan Jawi Start
    
        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1'  and fl.pendidikan_islam=1 group by ps.student_entry_level, s.id) ab";

        $resultt=mysqli_query($connection, $sql);
                
        $num_row=mysqli_num_rows($resultt);
        $level_count = 0;
        while ($roww=mysqli_fetch_assoc($resultt)) {
            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
        }

        $excel_row[] = $level_count;

        $pendidikan_total += $level_count;
        $pendidikan_QF1_grand_total += $level_count;
    
        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2'  and fl.pendidikan_islam=1 group by ps.student_entry_level, s.id) ab";

        $resultt=mysqli_query($connection, $sql);
                
        $num_row=mysqli_num_rows($resultt);
        $level_count = 0;
        while ($roww=mysqli_fetch_assoc($resultt)) {
            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
        }

        $excel_row[] = $level_count;

        $pendidikan_total += $level_count;
        $pendidikan_QF2_grand_total += $level_count;
    
        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3'  and fl.pendidikan_islam=1 group by ps.student_entry_level, s.id) ab";

        $resultt=mysqli_query($connection, $sql);
                
        $num_row=mysqli_num_rows($resultt);
        $level_count = 0;
        while ($roww=mysqli_fetch_assoc($resultt)) {
            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
        }

        $excel_row[] = $level_count;

        $pendidikan_total += $level_count;
        $pendidikan_QF3_grand_total += $level_count;
    
        $excel_row[] = $pendidikan_total; 
    
        // English Start 
    
        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1'  and fl.foundation_int_english=1 group by ps.student_entry_level, s.id) ab";

        $resultt=mysqli_query($connection, $sql);
                
        $num_row=mysqli_num_rows($resultt);
        $level_count = 0;
        while ($roww=mysqli_fetch_assoc($resultt)) {
            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
        }

        $excel_row[] = $level_count;

        $english_total += $level_count;
        $english_QF1_grand_total += $level_count;
    
        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2'  and fl.foundation_int_english=1  ";
        
        $sql .= " group by ps.student_entry_level, s.id) ab";

        $resultt=mysqli_query($connection, $sql);
                
        $num_row=mysqli_num_rows($resultt);
        $level_count = 0;
        while ($roww=mysqli_fetch_assoc($resultt)) {
            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
        }

        $excel_row[] = $level_count;

        $english_total += $level_count;
        $english_QF2_grand_total += $level_count;
    
        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3'  and fl.foundation_int_english=1 group by ps.student_entry_level, s.id) ab";

        $resultt=mysqli_query($connection, $sql);
                
        $num_row=mysqli_num_rows($resultt);
        $level_count = 0;
        while ($roww=mysqli_fetch_assoc($resultt)) {
            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
        }

        $excel_row[] = $level_count;

        $english_total += $level_count;
        $english_QF3_grand_total += $level_count;
    
        $enh_foundation_total += $english_total;
        $excel_row[] = $english_total; 
    
        // Mandarin Start 
    
        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and fl.foundation_int_mandarin=1 group by ps.student_entry_level, s.id) ab";

        $resultt=mysqli_query($connection, $sql);
                
        $num_row=mysqli_num_rows($resultt);
        $level_count = 0;
        while ($roww=mysqli_fetch_assoc($resultt)) {
            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
        }

        $excel_row[] = $level_count;

        $mandarin_total += $level_count;
        $mandarin_QF1_grand_total += $level_count;
    
        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and fl.foundation_int_mandarin=1 group by ps.student_entry_level, s.id) ab";

        $resultt=mysqli_query($connection, $sql);
                
        $num_row=mysqli_num_rows($resultt);
        $level_count = 0;
        while ($roww=mysqli_fetch_assoc($resultt)) {
            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
        }

        $excel_row[] = $level_count;

        $mandarin_total += $level_count;
        $mandarin_QF2_grand_total += $level_count;
    
        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and fl.foundation_int_mandarin=1 group by ps.student_entry_level, s.id) ab";

        $resultt=mysqli_query($connection, $sql);
                
        $num_row=mysqli_num_rows($resultt);
        $level_count = 0;
        while ($roww=mysqli_fetch_assoc($resultt)) {
            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
        }

        $excel_row[] = $level_count;

        $mandarin_total += $level_count;
        $mandarin_QF3_grand_total += $level_count;
    
        $enh_foundation_total += $mandarin_total;
        $excel_row[] = $mandarin_total; 
        
        // Int Art Start 
    
        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and fl.foundation_int_art=1 group by ps.student_entry_level, s.id) ab";

        $resultt=mysqli_query($connection, $sql);
                
        $num_row=mysqli_num_rows($resultt);
        $level_count = 0;
        while ($roww=mysqli_fetch_assoc($resultt)) {
            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
        }

        $excel_row[] = $level_count;

        $art_total += $level_count;
        $art_QF1_grand_total += $level_count;
    
        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and fl.foundation_int_art=1 group by ps.student_entry_level, s.id) ab";

        $resultt=mysqli_query($connection, $sql);
                
        $num_row=mysqli_num_rows($resultt);
        $level_count = 0;
        while ($roww=mysqli_fetch_assoc($resultt)) {
            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
        }

        $excel_row[] = $level_count;

        $art_total += $level_count;
        $art_QF2_grand_total += $level_count;
    
        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and fl.foundation_int_art=1 group by ps.student_entry_level, s.id) ab";

        $resultt=mysqli_query($connection, $sql);
                
        $num_row=mysqli_num_rows($resultt);
        $level_count = 0;
        while ($roww=mysqli_fetch_assoc($resultt)) {
            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
        }

        $excel_row[] = $level_count;

        $art_total += $level_count;
        $art_QF3_grand_total += $level_count;
        
        $enh_foundation_total += $art_total;
        $excel_row[] = $art_total; 
    
        // IQ Maths Start 
    
        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and fl.foundation_iq_math=1 group by ps.student_entry_level, s.id) ab";

        $resultt=mysqli_query($connection, $sql);
                
        $num_row=mysqli_num_rows($resultt);
        $level_count = 0;
        while ($roww=mysqli_fetch_assoc($resultt)) {
            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
        }

        $excel_row[] = $level_count;

        $iq_total += $level_count;
        $iq_QF1_grand_total += $level_count;
    
        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and fl.foundation_iq_math=1 group by ps.student_entry_level, s.id) ab";

        $resultt=mysqli_query($connection, $sql);
                
        $num_row=mysqli_num_rows($resultt);
        $level_count = 0;
        while ($roww=mysqli_fetch_assoc($resultt)) {
            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
        }

        $excel_row[] = $level_count;

        $iq_total += $level_count;
        $iq_QF2_grand_total += $level_count;
    
        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and fl.foundation_iq_math=1 group by ps.student_entry_level, s.id) ab";

        $resultt=mysqli_query($connection, $sql);
                
        $num_row=mysqli_num_rows($resultt);
        $level_count = 0;
        while ($roww=mysqli_fetch_assoc($resultt)) {
            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
        }

        $excel_row[] = $level_count;

        $iq_total += $level_count;
        $iq_QF3_grand_total += $level_count;
    
        $enh_foundation_total += $iq_total;
        $excel_row[] = $iq_total; 
    
        $excel_row[] = $enh_foundation_total;
        $enh_foundation_grand_total += $enh_foundation_total;
    
        // Basic Start 
    
        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and fl.afternoon_programme =1 and f.basic_adjust > 0 group by ps.student_entry_level, s.id) ab";

        $resultt=mysqli_query($connection, $sql);
        
        $num_row=mysqli_num_rows($resultt);
        $level_count = 0;
        while ($roww=mysqli_fetch_assoc($resultt)) {
            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
        }

        $excel_row[] = $level_count;

        $basic_grand_total += $level_count;
    
        // Basic + Robotics Start 
    
        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and (ps.student_entry_level ='EDP' OR ps.student_entry_level ='QF1') and fl.afternoon_programme =1 and f.basic_adjust < 1 and f.afternoon_robotic_adjust > 0 group by ps.student_entry_level, s.id) ab";

        $resultt=mysqli_query($connection, $sql);
        
        $num_row=mysqli_num_rows($resultt);
        $level_count = 0;
        while ($roww=mysqli_fetch_assoc($resultt)) {
            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
        }

        $excel_row[] = $level_count;

        $basic_robotic_total += $level_count;
        $basic_robotic_junior_grand_total += $level_count;
    
        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and (ps.student_entry_level ='QF2' OR ps.student_entry_level ='QF3') and fl.afternoon_programme =1 and f.basic_adjust < 1 and f.afternoon_robotic_adjust > 0 group by ps.student_entry_level, s.id) ab";

        $resultt=mysqli_query($connection, $sql);
        
        $num_row=mysqli_num_rows($resultt);
        $level_count = 0;
        while ($roww=mysqli_fetch_assoc($resultt)) {
            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
        }

        $excel_row[] = $level_count;

        $basic_robotic_total += $level_count;
        $basic_robotic_senior_grand_total += $level_count;
    
        $excel_row[] = $basic_robotic_total;
    
        // Robotics Start 
    
        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and (ps.student_entry_level ='EDP' OR ps.student_entry_level ='QF1') and fl.robotic_plus=1 group by ps.student_entry_level, s.id) ab";

        $resultt=mysqli_query($connection, $sql);
                
        $num_row=mysqli_num_rows($resultt);
        $level_count = 0;
        while ($roww=mysqli_fetch_assoc($resultt)) {
            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
        }

        $excel_row[] = $level_count;

        $robotic_total += $level_count;
        $robotic_junior_grand_total += $level_count;
        
        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and (ps.student_entry_level ='QF2' OR ps.student_entry_level ='QF3') and fl.robotic_plus=1 group by ps.student_entry_level, s.id) ab";

        $resultt=mysqli_query($connection, $sql);
                
        $num_row=mysqli_num_rows($resultt);
        $level_count = 0;
        while ($roww=mysqli_fetch_assoc($resultt)) {
            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
        }

        $excel_row[] = $level_count;

        $robotic_total += $level_count;
        $robotic_senior_grand_total += $level_count;
    
        $excel_row[] = $robotic_total; 
          
        $content[] = $excel_row;

    }

    $excel_row = array();

    $excel_row[] = "GRAND TOTAL";
    $excel_row[] = $foundation_EDP_grand_total; 
    $excel_row[] = $foundation_QF1_grand_total; 
    $excel_row[] = $foundation_QF2_grand_total; 
    $excel_row[] = $foundation_QF3_grand_total; 
    $excel_row[] = $foundation_EDP_grand_total + $foundation_QF1_grand_total + $foundation_QF2_grand_total + $foundation_QF3_grand_total; 
    $excel_row[] = $zhi_hui_mandarin_QF1_grand_total; 
    $excel_row[] = $zhi_hui_mandarin_QF2_grand_total; 
    $excel_row[] = $zhi_hui_mandarin_QF3_grand_total;
    $excel_row[] = $zhi_hui_mandarin_QF1_grand_total + $zhi_hui_mandarin_QF2_grand_total + $zhi_hui_mandarin_QF3_grand_total; 
    $excel_row[] = $pendidikan_QF1_grand_total; 
    $excel_row[] = $pendidikan_QF2_grand_total; 
    $excel_row[] = $pendidikan_QF3_grand_total;
    $excel_row[] = $pendidikan_QF1_grand_total + $pendidikan_QF2_grand_total + $pendidikan_QF3_grand_total; 
    $excel_row[] = $english_QF1_grand_total; 
    $excel_row[] = $english_QF2_grand_total; 
    $excel_row[] = $english_QF3_grand_total; 
    $excel_row[] = $english_QF1_grand_total + $english_QF2_grand_total + $english_QF3_grand_total; 
    $excel_row[] = $mandarin_QF1_grand_total; 
    $excel_row[] = $mandarin_QF2_grand_total; 
    $excel_row[] = $mandarin_QF3_grand_total; 
    $excel_row[] = $mandarin_QF1_grand_total + $mandarin_QF2_grand_total + $mandarin_QF3_grand_total; 
    $excel_row[] = $art_QF1_grand_total; 
    $excel_row[] = $art_QF2_grand_total; 
    $excel_row[] = $art_QF3_grand_total; 
    $excel_row[] = $art_QF1_grand_total + $art_QF2_grand_total + $art_QF3_grand_total; 
    $excel_row[] = $iq_QF1_grand_total; 
    $excel_row[] = $iq_QF2_grand_total; 
    $excel_row[] = $iq_QF3_grand_total; 
    $excel_row[] = $iq_QF1_grand_total + $iq_QF2_grand_total + $iq_QF3_grand_total; 
    $excel_row[] = $enh_foundation_grand_total; 
    $excel_row[] = $basic_grand_total; 
    $excel_row[] = $basic_robotic_junior_grand_total; 
    $excel_row[] = $basic_robotic_senior_grand_total; 
    $excel_row[] = $basic_robotic_junior_grand_total + $basic_robotic_senior_grand_total; 
    $excel_row[] = $robotic_junior_grand_total; 
    $excel_row[] = $robotic_senior_grand_total; 
    $excel_row[] = $robotic_junior_grand_total + $robotic_senior_grand_total; 
    
    $content[] = $excel_row;

    $output = fopen('php://output', 'w');
    fputcsv($output, $title);
    foreach ($content as $con) {
        fputcsv($output, $con);
    }
    fclose($output);
}
?>
