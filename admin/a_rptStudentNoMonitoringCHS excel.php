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
    
    $centrecode_array = array();
    $centrename_array = array();

    
    $i = 0;

    //$foundation_EDP_grand_total = $foundation_QF1_grand_total = $foundation_QF2_grand_total = $foundation_QF3_grand_total = $zhi_hui_mandarin_QF1_grand_total = $zhi_hui_mandarin_QF2_grand_total = $zhi_hui_mandarin_QF3_grand_total = $pendidikan_QF1_grand_total = $pendidikan_QF2_grand_total = $pendidikan_QF3_grand_total = $english_QF1_grand_total = $english_QF2_grand_total = $english_QF3_grand_total = $art_QF1_grand_total = $art_QF2_grand_total = $art_QF3_grand_total = $iq_QF1_grand_total = $iq_QF2_grand_total = $iq_QF3_grand_total = $mandarin_QF1_grand_total = $mandarin_QF2_grand_total = $mandarin_QF3_grand_total = $basic_grand_total = $basic_robotic_junior_grand_total = $basic_robotic_senior_grand_total = $robotic_junior_grand_total = $robotic_senior_grand_total = 0;
    
    //$enh_foundation_grand_total = 0;

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
    //////////////////////////

    while($centre_row = mysqli_fetch_array($centre)) //Push all stuff to the list
    {
        array_push($centrecode_array, $centre_row['centre_code']);
        array_push($centrename_array, $centre_row['company_name']);
    }

    $i = 0;

    while($i < count($centrecode_array)){
        $excel_row = array();
        $counter = $i + 1;
        $validCenter = "true";

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

        if ($validCenter == "true"){ //To generate the CSV faster, we're skipping over the others because we know that if Foundation = 0, then everything else is 0
            
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

            /////
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

        } //End Valid Center check

        //Push everything to excel_row
        $excel_row[] = $counter; //Number
        $excel_row[] = $centrename_array[$i]; //Center Name
        //Foundation
        $excel_row[] = $var0;
        $excel_row[] = $var1;
        $excel_row[] = $var2;
        $excel_row[] = $var3;
        $excel_row[] = $var0+$var1+$var2+$var3;

        // Zhi Hui
        $excel_row[] = $zhihuiQF1;
        $excel_row[] = $zhihuiQF2;
        $excel_row[] = $zhihuiQF3;
        $excel_row[] = $zhihuiQF1+$zhihuiQF2+$zhihuiQF3;

        // Jawi
        $excel_row[] = $jawiQF1;
        $excel_row[] = $jawiQF2;
        $excel_row[] = $jawiQF3;
        $excel_row[] = $jawiQF1+$jawiQF2+$jawiQF3;

        // English
        $excel_row[] = $enhFoundQF1;
        $excel_row[] = $enhFoundQF2;
        $excel_row[] = $enhFoundQF3;
        $excel_row[] = $enhFoundQF1+$enhFoundQF2+$enhFoundQF3;

        // Mandarin
        $excel_row[] = $enhFoundMandQF1;
        $excel_row[] = $enhFoundMandQF2;
        $excel_row[] = $enhFoundMandQF3;
        $excel_row[] = $enhFoundMandQF1+$enhFoundMandQF2+$enhFoundMandQF3;

        //Int art
        $excel_row[] = $intArtQF1;
        $excel_row[] = $intArtQF2;
        $excel_row[] = $intArtQF3;
        $excel_row[] = $intArtQF1+$intArtQF2+$intArtQF3;

        //IQMath
        $excel_row[] = $IQMathQF1;
        $excel_row[] = $IQMathQF2;
        $excel_row[] = $IQMathQF3;
        $excel_row[] = $IQMathQF1+$iIQMathQF2+$IQMathQF3;

        //Gtotal
        $excel_row[] = ($enhFoundQF1 + $enhFoundQF2 + $enhFoundQF3)
        + ($enhFoundMandQF1 + $enhFoundMandQF2 + $enhFoundMandQF3)
        + ($intArtQF1 + $intArtQF2 + $intArtQF3)
        + ($IQMathQF1 + $IQMathQF2 + $IQMathQF3);

        //Basic
        $excel_row[] = $AfProgBasic;

        //Basic + Robotics
        $excel_row[] = $robotJr;
        $excel_row[] = $robotSr;
        $excel_row[] = ($robotJr + $robotSr);
        $excel_row[] = $EFPlusJR;
        $excel_row[] = $EFPlusSR;
        $excel_row[] =  ($EFPlusJR + $EFPlusSR);

        //Push everything to the excel file
        $content[] = $excel_row;
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

    if($centre_code == 'ALL') {
        $excel_row = array();

        $excel_row[] = "";
        $excel_row[] = "GRAND TOTAL";
        $excel_row[] = $var0Total;
        $excel_row[] = $var1Total;
        $excel_row[] = $var2Total;
        $excel_row[] = $var3Total;
        $excel_row[] = $var0Total + $var1Total + $var2Total + $var3Total;
        
        $excel_row[] = $zhihuiQF1Total;
        $excel_row[] = $zhihuiQF2Total;
        $excel_row[] = $zhihuiQF3Total;
        $excel_row[] = $zhihuiQF1Total + $zhihuiQF2Total + $zhihuiQF3Total;

        $excel_row[] = $jawiQF1Total;
        $excel_row[] = $jawiQF2Total;
        $excel_row[] = $jawiQF3Total;
        $excel_row[] = $jawiQF1Total + $jawiQF2Total + $jawiQF3Total;

        $excel_row[] = $enhFoundQF1Total;
        $excel_row[] = $enhFoundQF2Total;
        $excel_row[] = $enhFoundQF3Total;
        $excel_row[] = $enhFoundQF1Total + $enhFoundQF2Total + $enhFoundQF3Total;
        
        $excel_row[] = $enhFoundMandQF1Total;
        $excel_row[] = $enhFoundMandQF2Total;
        $excel_row[] = $enhFoundMandQF3Total;
        $excel_row[] = $enhFoundMandQF1Total + $enhFoundMandQF2Total + $enhFoundMandQF3Total; 

        $excel_row[] = $intArtQF1Total;
        $excel_row[] = $intArtQF2Total;
        $excel_row[] = $intArtQF3Total;
        $excel_row[] = $intArtQF1Total + $intArtQF2Total + $intArtQF3Total;

        $excel_row[] = $IQMathQF1Total;
        $excel_row[] = $IQMathQF2Total;
        $excel_row[] = $IQMathQF3Total;
        $excel_row[] = $IQMathQF1Total + $IQMathQF2Total + $IQMathQF3Total;

        $excel_row[] = ($enhFoundQF1Total + $enhFoundQF2Total + $enhFoundQF3Total)
            + ($enhFoundMandQF1Total + $enhFoundMandQF2Total + $enhFoundMandQF3Total)
            + ($intArtQF1Total + $intArtQF2Total + $intArtQF3Total)
            + ($IQMathQF1Total + $IQMathQF2Total + $IQMathQF3Total);

        $excel_row[] = $AfProgBasicTotal;
        $excel_row[] = $robotJrTotal;
        $excel_row[] = $robotSrTotal;
        $excel_row[] = $robotJrTotal + $robotSrTotal;

        $excel_row[] = $EFPlusJRTotal;
        $excel_row[] = $EFPlusSRTotal; 
        $excel_row[] = $EFPlusJRTotal + $EFPlusSRTotal;

        $content[] = $excel_row;
    }

    $output = fopen('php://output', 'w');
    fputcsv($output, $title);
    foreach ($content as $con) {
        fputcsv($output, $con);
    }
    fclose($output);
}
?>
