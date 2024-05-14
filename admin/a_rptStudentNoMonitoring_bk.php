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
          <?php echo ($centre_code == 'ALL') ? 'All Centre' : getCentreName($centre_code); ?>
          </td>
        </tr>
        <tr>
          <td class="uk-text-bold">Prepare By</td>
          <td><?php echo $_SESSION["UserName"] ?></td>
        </tr>
        <tr id="note">
          <td colspan="2" class="uk-text-bold">NOTE: To be submitted termly with the order form</td>
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
        <tr id="note1" style="display: none;">
          <td colspan="2" class="uk-text-bold">NOTE: To be submitted termly with the order form</td>
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

        <table class='uk-table rpt-table'>
            <tr class='uk-text-small uk-text-bold text-center'>
              <td rowspan="3">No.</td>
              <td rowspan="3">Centre</td>
              <td colspan="13">Half Day Prog</td>
              <td colspan="17">Enh Foundation</td>
              <td colspan="4">Afternoon Prog</td>
              <td colspan="3">EF Plus</td>
            </tr>
            <tr class='uk-text-small uk-text-bold text-center'>
              <td colspan="5">Foundation</td>
              <td colspan="4">Zhi Hui Mandarin</td>
              <td colspan="4">Pendidikan Jawi</td>
              <td colspan="4">English</td>
              <td colspan="4">Mandarin</td>
              <td colspan="4">Int Art</td>
              <td colspan="4">IQ Maths</td>
              <td rowspan="2">Grand Total</td>
              <td rowspan="2">Basic</td>
              <td colspan="3">Basic + Robotics</td>
              <td colspan="3">Robotics</td>
            </tr>
            <tr class='uk-text-small uk-text-bold text-center'>
                <td >EDP</td>
                <td >QF1</td>
                <td >QF2</td>
                <td >QF3</td>
                <td >Total</td>
                <td >QF1</td>
                <td >QF2</td>
                <td >QF3</td>
                <td >Total</td>
                <td >QF1</td>
                <td >QF2</td>
                <td >QF3</td>
                <td >Total</td>
                <td >QF1</td>
                <td >QF2</td>
                <td >QF3</td>
                <td >Total</td>
                <td >QF1</td>
                <td >QF2</td>
                <td >QF3</td>
                <td >Total</td>
                <td >QF1</td>
                <td >QF2</td>
                <td >QF3</td>
                <td >Total</td>
                <td >QF1</td>
                <td >QF2</td>
                <td >QF3</td>
                <td >Total</td>
                <td >Junior</td>
                <td >Senior</td>
                <td >Total</td>
                <td >Junior</td>
                <td >Senior</td>
                <td >Total</td>
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
        ?>
                <tr >
                    <td><?php echo $i; ?></td>
                    <td><?php echo $centre_row['company_name']; ?></td>
                    <!-- Foundation Start -->
                    <td >
                      <?php
                      
                      $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
                      if($centre_code!="ALL"){
                        $sql.=" and s.centre_code='$centre_code' ";
                      }
                      
                      $sql.=" and s.deleted='0' and ps.student_entry_level ='EDP' group by ps.student_entry_level, s.id) ab";

                        //$sql="SELECT count(`collection`.`id`) as level_count from `collection` LEFT JOIN `product` ON `product`.`product_code` = `collection`.`product_code` where (`collection`.`product_code` like 'MY-EDP1.EARLY-DIS%' OR `collection`.`product_code` like 'MY-EDP2.EARLY-DIS%' OR `collection`.`product_code` like 'MY-EDP3.EARLY-DIS%' OR `collection`.`product_code` like 'MY-EDP4.EARLY-DIS%') and (collection_date_time BETWEEN '$year_start_date' AND '$year_end_date') and `collection`.`centre_code`='$centre_code' and `collection`.`void`=0 and `product`.`sub_sub_category` = 'EDP'";

                        $resultt=mysqli_query($connection, $sql);
                                
                        $num_row=mysqli_num_rows($resultt);
                        $level_count = 0;
                        while ($roww=mysqli_fetch_assoc($resultt)) {
                            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                        }

                        echo $level_count;

                        $foundation_total += $level_count;
                        $foundation_EDP_grand_total += $level_count;

                      ?>
                    </td>
                    <td >
                      <?php

                        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
                        if($centre_code!="ALL"){
                          $sql.=" and s.centre_code='$centre_code' ";
                        }

                        $sql.=" and s.deleted='0' and ps.student_entry_level ='QF1' group by ps.student_entry_level, s.id) ab";

                        //$sql="SELECT count(`collection`.`id`) as level_count from `collection` LEFT JOIN `product` ON `product`.`product_code` = `collection`.`product_code` where `collection`.`product_code` like 'MY-MODULE%' and (collection_date_time BETWEEN '$year_start_date' AND '$year_end_date') and `collection`.`centre_code`='$centre_code' and `collection`.`void`=0 and `product`.`sub_sub_category` = 'QF1'";

                        $resultt=mysqli_query($connection, $sql);
                                
                        $num_row=mysqli_num_rows($resultt);
                        $level_count = 0;
                        while ($roww=mysqli_fetch_assoc($resultt)) {
                            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                        }

                        echo $level_count;

                        $foundation_total += $level_count;
                        $foundation_QF1_grand_total += $level_count;
                      ?>
                    </td>
                    <td >
                      <?php

                        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
                        if($centre_code!="ALL"){
                          $sql.=" and s.centre_code='$centre_code' ";
                        }

                        $sql.=" and s.deleted='0' and ps.student_entry_level ='QF2' group by ps.student_entry_level, s.id) ab";

                        //$sql="SELECT count(`collection`.`id`) as level_count from `collection` LEFT JOIN `product` ON `product`.`product_code` = `collection`.`product_code` where `collection`.`product_code` like 'MY-MODULE%' and (collection_date_time BETWEEN '$year_start_date' AND '$year_end_date') and `collection`.`centre_code`='$centre_code' and `collection`.`void`=0 and `product`.`sub_sub_category` = 'QF2'";

                        $resultt=mysqli_query($connection, $sql);
                                
                        $num_row=mysqli_num_rows($resultt);
                        $level_count = 0;
                        while ($roww=mysqli_fetch_assoc($resultt)) {
                            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                        }

                        echo $level_count;

                        $foundation_total += $level_count;
                        $foundation_QF2_grand_total += $level_count;
                      ?>
                    </td>
                    <td >
                      <?php

                        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
                        if($centre_code!="ALL"){
                          $sql.=" and s.centre_code='$centre_code' ";
                        }

                        $sql.=" and s.deleted='0' and ps.student_entry_level ='QF3' group by ps.student_entry_level, s.id) ab";

                        //$sql="SELECT count(`collection`.`id`) as level_count from `collection` LEFT JOIN `product` ON `product`.`product_code` = `collection`.`product_code` where `collection`.`product_code` like 'MY-MODULE%' and (collection_date_time BETWEEN '$year_start_date' AND '$year_end_date') and `collection`.`centre_code`='$centre_code' and `collection`.`void`=0 and `product`.`sub_sub_category` = 'QF3'";

                        $resultt=mysqli_query($connection, $sql);
                                
                        $num_row=mysqli_num_rows($resultt);
                        $level_count = 0;
                        while ($roww=mysqli_fetch_assoc($resultt)) {
                            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                        }

                        echo $level_count;

                        $foundation_total += $level_count;
                        $foundation_QF3_grand_total += $level_count;
                      ?>
                    </td>
                    <td >
                      <?php 
                        echo $foundation_total; 
                      ?>
                    </td>
                    <!-- Foundation End -->
                    <!-- Zhi Hui Mandrin Start -->
                    <td >
                      <?php

                        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
                        $sql.=" and s.centre_code='$centre_code' ";
                        $sql.=" and ((fl.programme_date >= '$year_start_date' and fl.programme_date <= '$year_end_date') or (fl.programme_date_end >= '$year_start_date' and fl.programme_date_end <= '$year_end_date')) and s.deleted='0' and ps.student_entry_level ='QF1' and fl.foundation_mandarin =1 group by ps.student_entry_level, s.id) ab";

                        //$sql="SELECT count(`collection`.`id`) as level_count from `collection` LEFT JOIN `product` ON `product`.`product_code` = `collection`.`product_code` where `collection`.`product_code` like 'MY-ZHM%' and (collection_date_time BETWEEN '$year_start_date' AND '$year_end_date') and `collection`.`centre_code`='$centre_code' and `collection`.`void`=0 and `product`.`sub_sub_category` = 'QF1'";

                        $resultt=mysqli_query($connection, $sql);
                                
                        $num_row=mysqli_num_rows($resultt);
                        $level_count = 0;
                        while ($roww=mysqli_fetch_assoc($resultt)) {
                            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                        }

                        echo $level_count;

                        $zhi_hui_mandarin_total += $level_count;
                        $zhi_hui_mandarin_QF1_grand_total += $level_count;
                      ?>
                    </td>
                    <td >
                      <?php
                        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
                        $sql.=" and s.centre_code='$centre_code' ";
                        $sql.=" and ((fl.programme_date >= '$year_start_date' and fl.programme_date <= '$year_end_date') or (fl.programme_date_end >= '$year_start_date' and fl.programme_date_end <= '$year_end_date')) and s.deleted='0' and ps.student_entry_level ='QF2' and fl.foundation_mandarin =1 group by ps.student_entry_level, s.id) ab";

                        //$sql="SELECT count(`collection`.`id`) as level_count from `collection` LEFT JOIN `product` ON `product`.`product_code` = `collection`.`product_code` where `collection`.`product_code` like 'MY-ZHM%' and (collection_date_time BETWEEN '$year_start_date' AND '$year_end_date') and `collection`.`centre_code`='$centre_code' and `collection`.`void`=0 and `product`.`sub_sub_category` = 'QF2'";

                        $resultt=mysqli_query($connection, $sql);
                                
                        $num_row=mysqli_num_rows($resultt);
                        $level_count = 0;
                        while ($roww=mysqli_fetch_assoc($resultt)) {
                            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                        }

                        echo $level_count;

                        $zhi_hui_mandarin_total += $level_count;
                        $zhi_hui_mandarin_QF2_grand_total += $level_count;
                      ?>
                    </td>
                    <td >
                      <?php

                        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
                        $sql.=" and s.centre_code='$centre_code' ";
                        $sql.=" and ((fl.programme_date >= '$year_start_date' and fl.programme_date <= '$year_end_date') or (fl.programme_date_end >= '$year_start_date' and fl.programme_date_end <= '$year_end_date')) and s.deleted='0' and ps.student_entry_level ='QF3' and fl.foundation_mandarin =1 group by ps.student_entry_level, s.id) ab";

                        //$sql="SELECT count(`collection`.`id`) as level_count from `collection` LEFT JOIN `product` ON `product`.`product_code` = `collection`.`product_code` where `collection`.`product_code` like 'MY-ZHM%' and (collection_date_time BETWEEN '$year_start_date' AND '$year_end_date') and `collection`.`centre_code`='$centre_code' and `collection`.`void`=0 and `product`.`sub_sub_category` = 'QF3'";

                        $resultt=mysqli_query($connection, $sql);
                                
                        $num_row=mysqli_num_rows($resultt);
                        $level_count = 0;
                        while ($roww=mysqli_fetch_assoc($resultt)) {
                            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                        }

                        echo $level_count;

                        $zhi_hui_mandarin_total += $level_count;
                        $zhi_hui_mandarin_QF3_grand_total += $level_count;
                      ?>
                    </td>
                    <td >
                      <?php 
                        echo $zhi_hui_mandarin_total; 
                      ?>
                    </td>
                    <!-- Zhi Hui Mandrin End -->
                    <!-- Pendidikan Jawi Start -->
                    <td >
                      <?php
                        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1'  and fl.pendidikan_islam=1 group by ps.student_entry_level, s.id) ab";

                        $resultt=mysqli_query($connection, $sql);
                                
                        $num_row=mysqli_num_rows($resultt);
                        $level_count = 0;
                        while ($roww=mysqli_fetch_assoc($resultt)) {
                            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                        }

                        echo $level_count;

                        $pendidikan_total += $level_count;
                        $pendidikan_QF1_grand_total += $level_count;
                      ?>
                    </td>
                    <td >
                      <?php
                        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2'  and fl.pendidikan_islam=1 group by ps.student_entry_level, s.id) ab";

                        $resultt=mysqli_query($connection, $sql);
                                
                        $num_row=mysqli_num_rows($resultt);
                        $level_count = 0;
                        while ($roww=mysqli_fetch_assoc($resultt)) {
                            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                        }

                        echo $level_count;

                        $pendidikan_total += $level_count;
                        $pendidikan_QF2_grand_total += $level_count;
                      ?>
                    </td>
                    <td >
                      <?php
                        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3'  and fl.pendidikan_islam=1 group by ps.student_entry_level, s.id) ab";

                        $resultt=mysqli_query($connection, $sql);
                                
                        $num_row=mysqli_num_rows($resultt);
                        $level_count = 0;
                        while ($roww=mysqli_fetch_assoc($resultt)) {
                            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                        }

                        echo $level_count;

                        $pendidikan_total += $level_count;
                        $pendidikan_QF3_grand_total += $level_count;
                      ?>
                    </td>
                    <td >
                      <?php 
                        echo $pendidikan_total; 
                      ?>
                    </td>
                    <!-- Pendidikan Jawi End -->
                    <!-- English Start -->
                    <td >
                      <?php
                        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1'  and fl.foundation_int_english=1 group by ps.student_entry_level, s.id) ab";

                        $resultt=mysqli_query($connection, $sql);
                                
                        $num_row=mysqli_num_rows($resultt);
                        $level_count = 0;
                        while ($roww=mysqli_fetch_assoc($resultt)) {
                            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                        }

                        echo $level_count;

                        $english_total += $level_count;
                        $english_QF1_grand_total += $level_count;
                      ?>
                    </td>
                    <td >
                    <?php
                        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2'  and fl.foundation_int_english=1  ";
                        
                        $sql .= " group by ps.student_entry_level, s.id) ab";

                        $resultt=mysqli_query($connection, $sql);
                                
                        $num_row=mysqli_num_rows($resultt);
                        $level_count = 0;
                        while ($roww=mysqli_fetch_assoc($resultt)) {
                            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                        }

                        echo $level_count;

                        $english_total += $level_count;
                        $english_QF2_grand_total += $level_count;
                      ?>
                    </td>
                    <td >
                    <?php
                        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3'  and fl.foundation_int_english=1 group by ps.student_entry_level, s.id) ab";

                        $resultt=mysqli_query($connection, $sql);
                                
                        $num_row=mysqli_num_rows($resultt);
                        $level_count = 0;
                        while ($roww=mysqli_fetch_assoc($resultt)) {
                            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                        }

                        echo $level_count;

                        $english_total += $level_count;
                        $english_QF3_grand_total += $level_count;
                      ?>
                    </td>
                    <td >
                      <?php 
                        $enh_foundation_total += $english_total;
                        echo $english_total; 
                      ?>
                    </td>
                    <!-- English End -->
                    <!-- Mandarin Start -->
                    <td >
                      <?php
                        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and fl.foundation_int_mandarin=1 group by ps.student_entry_level, s.id) ab";

                        $resultt=mysqli_query($connection, $sql);
                                
                        $num_row=mysqli_num_rows($resultt);
                        $level_count = 0;
                        while ($roww=mysqli_fetch_assoc($resultt)) {
                            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                        }

                        echo $level_count;

                        $mandarin_total += $level_count;
                        $mandarin_QF1_grand_total += $level_count;
                      ?>
                    </td>
                    <td >
                    <?php
                        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and fl.foundation_int_mandarin=1 group by ps.student_entry_level, s.id) ab";

                        $resultt=mysqli_query($connection, $sql);
                                
                        $num_row=mysqli_num_rows($resultt);
                        $level_count = 0;
                        while ($roww=mysqli_fetch_assoc($resultt)) {
                            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                        }

                        echo $level_count;

                        $mandarin_total += $level_count;
                        $mandarin_QF2_grand_total += $level_count;
                      ?>
                    </td>
                    <td >
                    <?php
                        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and fl.foundation_int_mandarin=1 group by ps.student_entry_level, s.id) ab";

                        $resultt=mysqli_query($connection, $sql);
                                
                        $num_row=mysqli_num_rows($resultt);
                        $level_count = 0;
                        while ($roww=mysqli_fetch_assoc($resultt)) {
                            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                        }

                        echo $level_count;

                        $mandarin_total += $level_count;
                        $mandarin_QF3_grand_total += $level_count;
                      ?>
                    </td>
                    <td >
                      <?php 
                        $enh_foundation_total += $mandarin_total;
                        echo $mandarin_total; 
                      ?>
                    </td>
                    <!-- Mandarin End -->
                    <!-- Int Art Start -->
                    <td >
                      <?php
                        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and fl.foundation_int_art=1 group by ps.student_entry_level, s.id) ab";

                        $resultt=mysqli_query($connection, $sql);
                                
                        $num_row=mysqli_num_rows($resultt);
                        $level_count = 0;
                        while ($roww=mysqli_fetch_assoc($resultt)) {
                            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                        }

                        echo $level_count;

                        $art_total += $level_count;
                        $art_QF1_grand_total += $level_count;
                      ?>
                    </td>
                    <td >
                    <?php
                        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and fl.foundation_int_art=1 group by ps.student_entry_level, s.id) ab";

                        $resultt=mysqli_query($connection, $sql);
                                
                        $num_row=mysqli_num_rows($resultt);
                        $level_count = 0;
                        while ($roww=mysqli_fetch_assoc($resultt)) {
                            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                        }

                        echo $level_count;

                        $art_total += $level_count;
                        $art_QF2_grand_total += $level_count;
                      ?>
                    </td>
                    <td >
                    <?php
                        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and fl.foundation_int_art=1 group by ps.student_entry_level, s.id) ab";

                        $resultt=mysqli_query($connection, $sql);
                                
                        $num_row=mysqli_num_rows($resultt);
                        $level_count = 0;
                        while ($roww=mysqli_fetch_assoc($resultt)) {
                            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                        }

                        echo $level_count;

                        $art_total += $level_count;
                        $art_QF3_grand_total += $level_count;
                      ?>
                    </td>
                    <td >
                      <?php 
                        $enh_foundation_total += $art_total;
                        echo $art_total; 
                      ?>
                    </td>
                    <!-- Int Art End -->
                    <!-- IQ Maths Start -->
                    <td >
                      <?php
                        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and fl.foundation_iq_math=1 group by ps.student_entry_level, s.id) ab";

                        $resultt=mysqli_query($connection, $sql);
                                
                        $num_row=mysqli_num_rows($resultt);
                        $level_count = 0;
                        while ($roww=mysqli_fetch_assoc($resultt)) {
                            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                        }

                        echo $level_count;

                        $iq_total += $level_count;
                        $iq_QF1_grand_total += $level_count;
                      ?>
                    </td>
                    <td >
                    <?php
                        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and fl.foundation_iq_math=1 group by ps.student_entry_level, s.id) ab";

                        $resultt=mysqli_query($connection, $sql);
                                
                        $num_row=mysqli_num_rows($resultt);
                        $level_count = 0;
                        while ($roww=mysqli_fetch_assoc($resultt)) {
                            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                        }

                        echo $level_count;

                        $iq_total += $level_count;
                        $iq_QF2_grand_total += $level_count;
                      ?>
                    </td>
                    <td >
                    <?php
                        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and fl.foundation_iq_math=1 group by ps.student_entry_level, s.id) ab";

                        $resultt=mysqli_query($connection, $sql);
                                
                        $num_row=mysqli_num_rows($resultt);
                        $level_count = 0;
                        while ($roww=mysqli_fetch_assoc($resultt)) {
                            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                        }

                        echo $level_count;

                        $iq_total += $level_count;
                        $iq_QF3_grand_total += $level_count;
                      ?>
                    </td>
                    <td >
                      <?php 
                        $enh_foundation_total += $iq_total;
                        echo $iq_total; 
                      ?>
                    </td>
                    <!-- IQ Maths End -->
                    <td >
                      <?php
                        echo $enh_foundation_total;
                        $enh_foundation_grand_total += $enh_foundation_total;
                      ?>
                    </td>
                    <!-- Basic Start -->
                    <td >
                      <?php
                        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and fl.afternoon_programme =1 and f.basic_adjust > 0 group by ps.student_entry_level, s.id) ab";

                        $resultt=mysqli_query($connection, $sql);
                        
                        $num_row=mysqli_num_rows($resultt);
                        $level_count = 0;
                        while ($roww=mysqli_fetch_assoc($resultt)) {
                            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                        }

                        echo $level_count;

                        $basic_grand_total += $level_count;
                      ?>
                    </td>
                    <!-- Basic End -->
                    <!-- Basic + Robotics Start -->
                    <td >
                      <?php
                        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and (ps.student_entry_level ='EDP' OR ps.student_entry_level ='QF1') and fl.afternoon_programme =1 and f.basic_adjust < 1 and f.afternoon_robotic_adjust > 0 group by ps.student_entry_level, s.id) ab";

                        $resultt=mysqli_query($connection, $sql);
                        
                        $num_row=mysqli_num_rows($resultt);
                        $level_count = 0;
                        while ($roww=mysqli_fetch_assoc($resultt)) {
                            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                        }

                        echo $level_count;

                        $basic_robotic_total += $level_count;
                        $basic_robotic_junior_grand_total += $level_count;
                      ?>
                    </td>
                    <td >
                      <?php
                        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and (ps.student_entry_level ='QF2' OR ps.student_entry_level ='QF3') and fl.afternoon_programme =1 and f.basic_adjust < 1 and f.afternoon_robotic_adjust > 0 group by ps.student_entry_level, s.id) ab";

                        $resultt=mysqli_query($connection, $sql);
                        
                        $num_row=mysqli_num_rows($resultt);
                        $level_count = 0;
                        while ($roww=mysqli_fetch_assoc($resultt)) {
                            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                        }

                        echo $level_count;

                        $basic_robotic_total += $level_count;
                        $basic_robotic_senior_grand_total += $level_count;
                      ?>
                    </td>
                    <td >
                      <?php
                        echo $basic_robotic_total;
                      ?>
                    </td>
                    <!-- Basic + Robotics End -->
                    <!-- Robotics Start -->
                    <td >
                      <?php 
                        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and (ps.student_entry_level ='EDP' OR ps.student_entry_level ='QF1') and fl.robotic_plus=1 group by ps.student_entry_level, s.id) ab";

                        $resultt=mysqli_query($connection, $sql);
                                
                        $num_row=mysqli_num_rows($resultt);
                        $level_count = 0;
                        while ($roww=mysqli_fetch_assoc($resultt)) {
                            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                        }

                        echo $level_count;

                        $robotic_total += $level_count;
                        $robotic_junior_grand_total += $level_count;
                      ?>
                    </td>
                    <td >
                    <?php 
                        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and (ps.student_entry_level ='QF2' OR ps.student_entry_level ='QF3') and fl.robotic_plus=1 group by ps.student_entry_level, s.id) ab";

                        $resultt=mysqli_query($connection, $sql);
                                
                        $num_row=mysqli_num_rows($resultt);
                        $level_count = 0;
                        while ($roww=mysqli_fetch_assoc($resultt)) {
                            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                        }

                        echo $level_count;

                        $robotic_total += $level_count;
                        $robotic_senior_grand_total += $level_count;
                      ?>
                    </td>
                    <td >
                    <?php 
                        echo $robotic_total; 
                      ?>
                    </td>
                    <!-- Robotics End -->
                </tr>
        <?php
            }
          ?>
          <tr  style="font-weight:bold;" >
            <td></td>
            <td>TOTAL</td>
            <td ><?php echo $foundation_EDP_grand_total; ?></td>
            <td ><?php echo $foundation_QF1_grand_total; ?></td>
            <td ><?php echo $foundation_QF2_grand_total; ?></td>
            <td ><?php echo $foundation_QF3_grand_total; ?></td>
            <td ><?php echo $foundation_EDP_grand_total + $foundation_QF1_grand_total + $foundation_QF2_grand_total + $foundation_QF3_grand_total; ?></td>
            <td ><?php echo $zhi_hui_mandarin_QF1_grand_total; ?></td>
            <td ><?php echo $zhi_hui_mandarin_QF2_grand_total; ?></td>
            <td ><?php echo $zhi_hui_mandarin_QF3_grand_total; ?></td>
            <td ><?php echo $zhi_hui_mandarin_QF1_grand_total + $zhi_hui_mandarin_QF2_grand_total + $zhi_hui_mandarin_QF3_grand_total; ?></td>
            <td ><?php echo $pendidikan_QF1_grand_total; ?></td>
            <td ><?php echo $pendidikan_QF2_grand_total; ?></td>
            <td ><?php echo $pendidikan_QF3_grand_total; ?></td>
            <td ><?php echo $pendidikan_QF1_grand_total + $pendidikan_QF2_grand_total + $pendidikan_QF3_grand_total; ?></td>
            <td ><?php echo $english_QF1_grand_total; ?></td>
            <td ><?php echo $english_QF2_grand_total; ?></td>
            <td ><?php echo $english_QF3_grand_total; ?></td>
            <td ><?php echo $english_QF1_grand_total + $english_QF2_grand_total + $english_QF3_grand_total; ?></td>
            <td ><?php echo $mandarin_QF1_grand_total; ?></td>
            <td ><?php echo $mandarin_QF2_grand_total; ?></td>
            <td ><?php echo $mandarin_QF3_grand_total; ?></td>
            <td ><?php echo $mandarin_QF1_grand_total + $mandarin_QF2_grand_total + $mandarin_QF3_grand_total; ?></td>
            <td ><?php echo $art_QF1_grand_total; ?></td>
            <td ><?php echo $art_QF2_grand_total; ?></td>
            <td ><?php echo $art_QF3_grand_total; ?></td>
            <td ><?php echo $art_QF1_grand_total + $art_QF2_grand_total + $art_QF3_grand_total; ?></td>
            <td ><?php echo $iq_QF1_grand_total; ?></td>
            <td ><?php echo $iq_QF2_grand_total; ?></td>
            <td ><?php echo $iq_QF3_grand_total; ?></td>
            <td ><?php echo $iq_QF1_grand_total + $iq_QF2_grand_total + $iq_QF3_grand_total; ?></td>
            <td ><?php echo $enh_foundation_grand_total; ?></td>
            <td ><?php echo $basic_grand_total; ?></td>
            <td ><?php echo $basic_robotic_junior_grand_total; ?></td>
            <td ><?php echo $basic_robotic_senior_grand_total; ?></td>
            <td ><?php echo $basic_robotic_junior_grand_total + $basic_robotic_senior_grand_total; ?></td>
            <td ><?php echo $robotic_junior_grand_total; ?></td>
            <td ><?php echo $robotic_senior_grand_total; ?></td>
            <td ><?php echo $robotic_junior_grand_total + $robotic_senior_grand_total; ?></td>
          </tr>

          <?php if($page_no == $total_no_of_pages && $_POST['centre_code'] == 'ALL') { ?>

            <tr  style="font-weight:bold;" >
              <td></td>
              <td>GRAND TOTAL</td>
              <td >
                <?php 

                  $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";

                  $sql.=" and s.deleted='0' and ps.student_entry_level ='EDP' group by ps.student_entry_level, s.id) ab";

                  $resultt=mysqli_query($connection, $sql);
                          
                  $num_row=mysqli_num_rows($resultt);
                  $level_count = 0;
                  while ($roww=mysqli_fetch_assoc($resultt)) {
                      $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                  }

                  $foundation_EDP_final_total = $level_count;

                  echo $foundation_EDP_final_total; 
                ?>
              </td>
              <td >
                <?php 

                  $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";

                  $sql.=" and s.deleted='0' and ps.student_entry_level ='QF1' group by ps.student_entry_level, s.id) ab";

                  $resultt=mysqli_query($connection, $sql);
                          
                  $num_row=mysqli_num_rows($resultt);
                  $level_count = 0;
                  while ($roww=mysqli_fetch_assoc($resultt)) {
                      $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                  }

                  $foundation_QF1_final_total += $level_count;

                  echo $foundation_QF1_final_total; 
                ?>
              </td>
              <td >
                <?php 

                  $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";

                  $sql.=" and s.deleted='0' and ps.student_entry_level ='QF2' group by ps.student_entry_level, s.id) ab";

                  $resultt=mysqli_query($connection, $sql);
                          
                  $num_row=mysqli_num_rows($resultt);
                  $level_count = 0;
                  while ($roww=mysqli_fetch_assoc($resultt)) {
                      $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                  }

                  $foundation_QF2_final_total += $level_count;

                  echo $foundation_QF2_final_total; 
                ?>
              </td>
              <td >
                <?php 
                  $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";

                  $sql.=" and s.deleted='0' and ps.student_entry_level ='QF3' group by ps.student_entry_level, s.id) ab";

                  $resultt=mysqli_query($connection, $sql);
                          
                  $num_row=mysqli_num_rows($resultt);
                  $level_count = 0;
                  while ($roww=mysqli_fetch_assoc($resultt)) {
                      $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                  }

                  $foundation_QF3_final_total += $level_count;

                  echo $foundation_QF3_final_total; 
                ?>
              </td>
              <td ><?php echo $foundation_EDP_final_total + $foundation_QF1_final_total + $foundation_QF2_final_total + $foundation_QF3_final_total; ?></td>
              <td >
                <?php 
                  $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
                  
                  $sql.=" and ((fl.programme_date >= '$year_start_date' and fl.programme_date <= '$year_end_date') or (fl.programme_date_end >= '$year_start_date' and fl.programme_date_end <= '$year_end_date')) and s.deleted='0' and ps.student_entry_level ='QF1' and fl.foundation_mandarin =1 group by ps.student_entry_level, s.id) ab";

                  $resultt=mysqli_query($connection, $sql);
                          
                  $num_row=mysqli_num_rows($resultt);
                  $level_count = 0;
                  while ($roww=mysqli_fetch_assoc($resultt)) {
                      $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                  }

                  $zhi_hui_mandarin_QF1_final_total = $level_count;

                  echo $zhi_hui_mandarin_QF1_final_total; 
                ?>
              </td>
              <td >
              <?php 
                  $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
                  
                  $sql.=" and ((fl.programme_date >= '$year_start_date' and fl.programme_date <= '$year_end_date') or (fl.programme_date_end >= '$year_start_date' and fl.programme_date_end <= '$year_end_date')) and s.deleted='0' and ps.student_entry_level ='QF2' and fl.foundation_mandarin =1 group by ps.student_entry_level, s.id) ab";

                  $resultt=mysqli_query($connection, $sql);
                          
                  $num_row=mysqli_num_rows($resultt);
                  $level_count = 0;
                  while ($roww=mysqli_fetch_assoc($resultt)) {
                      $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                  }

                  $zhi_hui_mandarin_QF2_final_total = $level_count;

                  echo $zhi_hui_mandarin_QF2_final_total; 
                ?>
              </td>
              <td >
              <?php 
                  $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
                  
                  $sql.=" and ((fl.programme_date >= '$year_start_date' and fl.programme_date <= '$year_end_date') or (fl.programme_date_end >= '$year_start_date' and fl.programme_date_end <= '$year_end_date')) and s.deleted='0' and ps.student_entry_level ='QF3' and fl.foundation_mandarin =1 group by ps.student_entry_level, s.id) ab";

                  $resultt=mysqli_query($connection, $sql);
                          
                  $num_row=mysqli_num_rows($resultt);
                  $level_count = 0;
                  while ($roww=mysqli_fetch_assoc($resultt)) {
                      $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                  }

                  $zhi_hui_mandarin_QF3_final_total = $level_count;

                  echo $zhi_hui_mandarin_QF3_final_total; 
                ?>
              </td>
              <td ><?php echo $zhi_hui_mandarin_QF1_final_total + $zhi_hui_mandarin_QF2_final_total + $zhi_hui_mandarin_QF3_final_total; ?></td>
              <td >
                <?php
                  $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.deleted='0' and ps.student_entry_level ='QF1'  and fl.pendidikan_islam=1 group by ps.student_entry_level, s.id) ab";

                  $resultt=mysqli_query($connection, $sql);
                          
                  $num_row=mysqli_num_rows($resultt);
                  $level_count = 0;
                  while ($roww=mysqli_fetch_assoc($resultt)) {
                      $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                  }

                  $pendidikan_QF1_final_total += $level_count;
                
                  echo $pendidikan_QF1_final_total; 
                ?>
              </td>
              <td >
              <?php
                  $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.deleted='0' and ps.student_entry_level ='QF2'  and fl.pendidikan_islam=1 group by ps.student_entry_level, s.id) ab";

                  $resultt=mysqli_query($connection, $sql);
                          
                  $num_row=mysqli_num_rows($resultt);
                  $level_count = 0;
                  while ($roww=mysqli_fetch_assoc($resultt)) {
                      $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                  }

                  $pendidikan_QF2_final_total += $level_count;
                
                  echo $pendidikan_QF2_final_total; 
                ?>
              </td>
              <td >
              <?php
                  $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.deleted='0' and ps.student_entry_level ='QF3'  and fl.pendidikan_islam=1 group by ps.student_entry_level, s.id) ab";

                  $resultt=mysqli_query($connection, $sql);
                          
                  $num_row=mysqli_num_rows($resultt);
                  $level_count = 0;
                  while ($roww=mysqli_fetch_assoc($resultt)) {
                      $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                  }

                  $pendidikan_QF3_final_total += $level_count;
                
                  echo $pendidikan_QF3_final_total; 
                ?>
              </td>
              <td ><?php echo $pendidikan_QF1_final_total + $pendidikan_QF2_final_total + $pendidikan_QF3_final_total; ?></td>
              <td >
                <?php 
                  
                  $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.deleted='0' and ps.student_entry_level ='QF1'  and fl.foundation_int_english=1 group by ps.student_entry_level, s.id) ab";

                  $resultt=mysqli_query($connection, $sql);
                          
                  $num_row=mysqli_num_rows($resultt);
                  $level_count = 0;
                  while ($roww=mysqli_fetch_assoc($resultt)) {
                      $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                  }

                  $english_QF1_final_total += $level_count;

                  echo $english_QF1_final_total; 
                ?>
              </td>
              <td >
              <?php 
                  
                  $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.deleted='0' and ps.student_entry_level ='QF2'  and fl.foundation_int_english=1 group by ps.student_entry_level, s.id) ab";

                  $resultt=mysqli_query($connection, $sql);
                          
                  $num_row=mysqli_num_rows($resultt);
                  $level_count = 0;
                  while ($roww=mysqli_fetch_assoc($resultt)) {
                      $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                  }

                  $english_QF2_final_total += $level_count;

                  echo $english_QF2_final_total; 
                ?>
              </td>
              <td >
              <?php 
                  
                  $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.deleted='0' and ps.student_entry_level ='QF3'  and fl.foundation_int_english=1 group by ps.student_entry_level, s.id) ab";

                  $resultt=mysqli_query($connection, $sql);
                          
                  $num_row=mysqli_num_rows($resultt);
                  $level_count = 0;
                  while ($roww=mysqli_fetch_assoc($resultt)) {
                      $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                  }

                  $english_QF3_final_total += $level_count;

                  echo $english_QF3_final_total; 
                ?>
              </td>
              <td ><?php echo $english_QF1_final_total + $english_QF2_final_total + $english_QF3_final_total; ?></td>
              <td >
                <?php 

                  $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.deleted='0' and ps.student_entry_level ='QF1' and fl.foundation_int_mandarin=1 group by ps.student_entry_level, s.id) ab";

                  $resultt=mysqli_query($connection, $sql);
                          
                  $num_row=mysqli_num_rows($resultt);
                  $level_count = 0;
                  while ($roww=mysqli_fetch_assoc($resultt)) {
                      $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                  }

                  $mandarin_QF1_final_total += $level_count;

                  echo $mandarin_QF1_final_total; 
                ?>
              </td>
              <td >
                <?php 

                  $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.deleted='0' and ps.student_entry_level ='QF2' and fl.foundation_int_mandarin=1 group by ps.student_entry_level, s.id) ab";

                  $resultt=mysqli_query($connection, $sql);
                          
                  $num_row=mysqli_num_rows($resultt);
                  $level_count = 0;
                  while ($roww=mysqli_fetch_assoc($resultt)) {
                      $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                  }

                  $mandarin_QF2_final_total += $level_count;

                  echo $mandarin_QF2_final_total; 
                ?>
              </td>
              <td >
                <?php 

                  $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.deleted='0' and ps.student_entry_level ='QF3' and fl.foundation_int_mandarin=1 group by ps.student_entry_level, s.id) ab";

                  $resultt=mysqli_query($connection, $sql);
                          
                  $num_row=mysqli_num_rows($resultt);
                  $level_count = 0;
                  while ($roww=mysqli_fetch_assoc($resultt)) {
                      $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                  }

                  $mandarin_QF3_final_total += $level_count;

                  echo $mandarin_QF3_final_total; 
                ?>
              </td>
              <td ><?php echo $mandarin_QF1_final_total + $mandarin_QF2_final_total + $mandarin_QF3_final_total; ?></td>
              <td >
                <?php 

                  $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.deleted='0' and ps.student_entry_level ='QF1' and fl.foundation_int_art=1 group by ps.student_entry_level, s.id) ab";

                  $resultt=mysqli_query($connection, $sql);
                          
                  $num_row=mysqli_num_rows($resultt);
                  $level_count = 0;
                  while ($roww=mysqli_fetch_assoc($resultt)) {
                      $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                  }

                  $art_QF1_final_total += $level_count;

                  echo $art_QF1_final_total; 
                ?>
              </td>
              <td >
                <?php 

                  $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.deleted='0' and ps.student_entry_level ='QF2' and fl.foundation_int_art=1 group by ps.student_entry_level, s.id) ab";

                  $resultt=mysqli_query($connection, $sql);
                          
                  $num_row=mysqli_num_rows($resultt);
                  $level_count = 0;
                  while ($roww=mysqli_fetch_assoc($resultt)) {
                      $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                  }

                  $art_QF2_final_total += $level_count;

                  echo $art_QF2_final_total; 
                ?>
              </td>
              <td >
                <?php 

                  $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.deleted='0' and ps.student_entry_level ='QF3' and fl.foundation_int_art=1 group by ps.student_entry_level, s.id) ab";

                  $resultt=mysqli_query($connection, $sql);
                          
                  $num_row=mysqli_num_rows($resultt);
                  $level_count = 0;
                  while ($roww=mysqli_fetch_assoc($resultt)) {
                      $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                  }

                  $art_QF3_final_total += $level_count;

                  echo $art_QF3_final_total; 
                ?>
              </td>
              <td ><?php echo $art_QF1_final_total + $art_QF2_final_total + $art_QF3_final_total; ?></td>
              <td >
                <?php 

                  $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.deleted='0' and ps.student_entry_level ='QF1' and fl.foundation_iq_math=1 group by ps.student_entry_level, s.id) ab";

                  $resultt=mysqli_query($connection, $sql);
                          
                  $num_row=mysqli_num_rows($resultt);
                  $level_count = 0;
                  while ($roww=mysqli_fetch_assoc($resultt)) {
                      $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                  }

                  $iq_QF1_final_total += $level_count;

                  echo $iq_QF1_final_total; 
                ?>
              </td>
              <td >
                <?php 

                  $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.deleted='0' and ps.student_entry_level ='QF2' and fl.foundation_iq_math=1 group by ps.student_entry_level, s.id) ab";

                  $resultt=mysqli_query($connection, $sql);
                          
                  $num_row=mysqli_num_rows($resultt);
                  $level_count = 0;
                  while ($roww=mysqli_fetch_assoc($resultt)) {
                      $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                  }

                  $iq_QF2_final_total += $level_count;

                  echo $iq_QF2_final_total; 
                ?>
              </td>
              <td >
                <?php 

                  $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.deleted='0' and ps.student_entry_level ='QF3' and fl.foundation_iq_math=1 group by ps.student_entry_level, s.id) ab";

                  $resultt=mysqli_query($connection, $sql);
                          
                  $num_row=mysqli_num_rows($resultt);
                  $level_count = 0;
                  while ($roww=mysqli_fetch_assoc($resultt)) {
                      $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                  }

                  $iq_QF3_final_total = $level_count;

                  echo $iq_QF3_final_total; 
                ?>
              </td>
              <td ><?php echo $iq_QF1_final_total + $iq_QF2_final_total + $iq_QF3_final_total; ?></td>
              <td ><?php echo $english_QF1_final_total + $english_QF2_final_total + $english_QF3_final_total + $mandarin_QF1_final_total + $mandarin_QF2_final_total + $mandarin_QF3_final_total + $art_QF1_final_total + $art_QF2_final_total + $art_QF3_final_total + $iq_QF1_final_total + $iq_QF2_final_total + $iq_QF3_final_total; ?></td>
              <td >
                <?php

                  $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.deleted='0' and fl.afternoon_programme =1 and f.basic_adjust > 0 group by ps.student_entry_level, s.id) ab";

                  $resultt=mysqli_query($connection, $sql);

                  $num_row=mysqli_num_rows($resultt);
                  $level_count = 0;
                  while ($roww=mysqli_fetch_assoc($resultt)) {
                      $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                  }

                  $basic_final_total = $level_count;
                
                  echo $basic_final_total; 
                ?>
              </td>
              <td >
                <?php 

                  $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.deleted='0' and (ps.student_entry_level ='EDP' OR ps.student_entry_level ='QF1') and fl.afternoon_programme =1 and f.basic_adjust < 1 and f.afternoon_robotic_adjust > 0 group by ps.student_entry_level, s.id) ab";

                  $resultt=mysqli_query($connection, $sql);

                  $num_row=mysqli_num_rows($resultt);
                  $level_count = 0;
                  while ($roww=mysqli_fetch_assoc($resultt)) {
                      $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                  }

                  $basic_robotic_junior_final_total += $level_count;

                  echo $basic_robotic_junior_final_total; 
                ?>
              </td>
              <td >
                <?php 

                  $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.deleted='0' and (ps.student_entry_level ='QF2' OR ps.student_entry_level ='QF3') and fl.afternoon_programme =1 and f.basic_adjust < 1 and f.afternoon_robotic_adjust > 0 group by ps.student_entry_level, s.id) ab";

                  $resultt=mysqli_query($connection, $sql);

                  $num_row=mysqli_num_rows($resultt);
                  $level_count = 0;
                  while ($roww=mysqli_fetch_assoc($resultt)) {
                      $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                  }

                  $basic_robotic_senior_final_total += $level_count;

                  echo $basic_robotic_senior_final_total; 
                ?>
              </td>
              <td ><?php echo $basic_robotic_junior_final_total + $basic_robotic_senior_final_total; ?></td>
              <td >
                <?php

                  $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.deleted='0' and (ps.student_entry_level ='EDP' OR ps.student_entry_level ='QF1') and fl.robotic_plus=1 group by ps.student_entry_level, s.id) ab";

                  $resultt=mysqli_query($connection, $sql);
                          
                  $num_row=mysqli_num_rows($resultt);
                  $level_count = 0;
                  while ($roww=mysqli_fetch_assoc($resultt)) {
                      $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                  }

                  $robotic_junior_final_total += $level_count;
                
                  echo $robotic_junior_final_total; 
                ?>
              </td>
              <td >
                <?php 

                  $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.deleted='0' and (ps.student_entry_level ='QF2' OR ps.student_entry_level ='QF3') and fl.robotic_plus=1 group by ps.student_entry_level, s.id) ab";

                  $resultt=mysqli_query($connection, $sql);
                          
                  $num_row=mysqli_num_rows($resultt);
                  $level_count = 0;
                  while ($roww=mysqli_fetch_assoc($resultt)) {
                      $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                  }

                  $robotic_senior_final_total += $level_count;

                  echo $robotic_senior_final_total; 
                ?>
              </td>
              <td ><?php echo $robotic_junior_final_total + $robotic_senior_final_total; ?></td>
            </tr>

          <?php } ?>
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