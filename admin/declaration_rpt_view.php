<?php
    session_start();
    include_once("../mysql.php");
    include_once("functions.php");

    $sha_id=isset($_GET["id"]) ? $_GET["id"] : '';
    $sql="SELECT * from `declaration` where sha1(id)='$sha_id'";
    $result=mysqli_query($connection, $sql);

    $row_edit=mysqli_fetch_assoc($result);
    $master_id=$row_edit["id"];
    $centre_code = $row_edit["centre_code"];
    $year = $row_edit["year"];
    $month = (strlen($row_edit["month"]) == 1) ? '0'.$row_edit["month"] : $row_edit["month"];
    $submited_date = $row_edit["submited_date"];
    $timestamp = strtotime($submited_date);
    $new_date_format = date('Y-m-d', $timestamp);
    $submited_date = convertDate2British($new_date_format);
    $sha_id = $row_edit['id'];

    $year_data = mysqli_fetch_array(mysqli_query($connection,"SELECT MIN(`term_start`) AS `start_date`, MAX(`term_end`) AS `end_date` FROM `schedule_term` WHERE `year` = '".$year."' AND `centre_code` = '".$centre_code."' GROUP BY `year`"));

    $year_start_date = $year_data['start_date'];
    $year_end_date = $year_data['end_date'];

    if(strlen($year) > 4) {

        $single_year = '';

        $year1 = substr($year, 0, 4);
        $year2 = substr($year, 5, 4);

        $str1 = $year1.'-'.$month.'-01';
        $str2 = $year2.'-'.$month.'-01';

        $single_year1 = mysqli_num_rows(mysqli_query($connection,"SELECT `term_start`, `term_end` FROM `schedule_term` WHERE `year` = '".$year."' AND `centre_code` = '".$centre_code."' AND ('$str1' between term_start and term_end)"));

        $single_year2 = mysqli_num_rows(mysqli_query($connection,"SELECT `term_start`, `term_end` FROM `schedule_term` WHERE `year` = '".$year."' AND `centre_code` = '".$centre_code."' AND ('$str2' between term_start and term_end)"));

        if($single_year1 == 1 && $single_year2 == 0) {
            $single_year = $year1;
        } else if($single_year1 == 0 && $single_year2 == 1) {
            $single_year = $year2;
        } else if($single_year1 == 1 && $single_year2 == 1) {

            if(date('Y',strtotime($row_edit["submited_date"])) == $year1) {
                $single_year = $year1;
            } else if(date('Y',strtotime($row_edit["submited_date"])) == $year2) {
                $single_year = $year2;
            }
        }

        $date = $single_year.'-'.$month.'-01';
        $monthyear = $single_year.'-'.$month;
        $current_date = $single_year.'-'.$month.'-'.date('t',strtotime($date));
        
    } else {

        $date = $year.'-'.$month.'-01';
        $monthyear = $year.'-'.$month;
        $current_date = $year.'-'.$month.'-'.date('t',strtotime($date));
        
    }

    function getCentreName($centre_code)
    {
        global $connection;
    
        $sql = "SELECT company_name from centre where centre_code='$centre_code'";
        $result = mysqli_query($connection, $sql);
        $row = mysqli_fetch_assoc($result);
        $num_row = mysqli_num_rows($result);
    
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

    $half_year_array = array();
    $half_year_array[0] = 3;
    $half_year_array[1] = 9;

    $half_year_array = implode(", ", $half_year_array);

    $term_array = array();

    $term_start_date = mysqli_query($connection,"SELECT MIN(`term_start`) AS `start_date` FROM `schedule_term` WHERE `year` = '".$year."' AND `centre_code` = '".$centre_code."' GROUP BY `term_num` ORDER BY `term_num` ASC");

    $j = 0;

    while($term_row = mysqli_fetch_array($term_start_date)) {
        $term_array[$j] = date('m',strtotime($term_row['start_date']));
        $j++;
    }
    
    $term_array = implode(", ", $term_array);
?>

<a id="back_button" onclick="history.back();">                
    <span class="d_n btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>
<span>
    <span class="page_title"><img src="/images/title_Icons/Visitor Reg.png">Declaration Report View</span>
</span>
<div class="uk-width-1-1 myheader text-center">
    <h2 class="uk-text-center myheader-text-color myheader-text-style">DECLARATION REPORT</h2>
</div>

<div class="nice-form">
    <div class="uk-grid">
        <div class="uk-width-medium-5-10">
            <table class="uk-table" style="width: 100%;">
               <tr>

                  <td class="uk-text-bold">Centre Name</td>
                  <td><?php echo getCentreName($centre_code); ?></td>
               </tr>
               <tr>
                  <td class="uk-text-bold">Prepared By</td>
                  <td><?php echo $_SESSION['UserName']; ?></td>
               </tr>
               <tr>
                  <td class="uk-text-bold">Date of submission</td>
                  <td><?php echo $submited_date; ?></td>
               </tr>
            </table>
         </div>
         <div class="uk-width-medium-5-10">
            <table class="uk-table">
               <tr>
                  <td class="uk-text-bold">Academic Year</td>
                  <td><?php echo $year; ?></td>
               </tr>
               <tr>
                  <td class="uk-text-bold">Month/Year</td>
                  <td>
                    <?php 
                        $dateObj   = DateTime::createFromFormat('!m', $month);
                        $monthName = $dateObj->format('F');
                        echo date('M',strtotime($monthName)).'/'.$year; 
                    ?>
                    </td>
               </tr>
            </table>
        </div>
    </div>
    <!-- 
    //////////////////////////////
    A) SCHOOL FEES
    //////////////////////////////
    -->
    <div name="school_fees">
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold " style="background-color: #F5F5F5;color:#2F2F2F;border:none;">
                    <td style="vertical-align: middle;font-size:18px;">A) School Fees</td>
                    <td />
                </tr>
            </tbody>
        </table>
        <!-- SCHOOL FEES START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <?php
                    for($i = 1; $i <= 4; $i++) 
                    {
                        if($i == 1) { 
                            $sub = 'EDP'; 
                        } else if($i == 2) { 
                            $sub = 'QF1'; 
                        } else if($i == 3) { 
                            $sub = 'QF2'; 
                        } else if($i == 4) { 
                            $sub = 'QF3'; 
                        } else {
                            $sub = '';
                        }

                        $sql="SELECT programme_package as fees_structure, active_student, fee_rate as school_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='School Fees' and subject='".$sub."'";

                        $result=mysqli_query($connection, $sql);
                        $num_row = mysqli_num_rows($result);

                        if ($num_row>0) {
    
                            $str = '';
                            $total_student = 0;

                            while ($row=mysqli_fetch_assoc($result)) {

                                if($row['active_student'] > 0) {
                    
                                    $str .= '<tr class="uk-text-bold">';
                                    $str .= '<td style="vertical-align: middle;font-size:16px;border:none;text-decoration: underline;">'.$row['fees_structure'].' ('.$row['active_student'].')</td>';
                                    $str .= '</tr>';
                    
                                    $sql="SELECT ps.student_entry_level, s.id, s.name from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and (s.student_status = 'A' or (s.student_status = 'D' and s.defer_date >= '$current_date')) and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='".$sub."' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' ";

                                    $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";

                                    $sql .= "  group by ps.student_entry_level, s.id LIMIT ".$row['active_student'];
                                    $resultt=mysqli_query($connection, $sql);
                                
                                    $j = 1;

                                    $num = mysqli_num_rows($resultt);

                                    $total_student += $row['active_student'];

                                    while ($roww=mysqli_fetch_array($resultt)) {
                                        
                                        if($j%2 == 0) {
                    
                                            $str .= '<td class="uk-text-bold" style="text-indent: 50px;border:none;">'.$j.'. '.$roww['name'].'</td>';
                                            $str .= '</tr>';
                    
                                        } else if($j%2 == 1) {
                    
                                            $str .= '<tr>';
                                            $str .= '<td class="uk-text-bold" style="text-indent: 50px;border:none;">'.$j.'. '.$roww['name'].'</td>';
                    
                                        }
                                        $j++;
                                    }
                                }
                            }
                            if($total_student > 0) {

                                echo '<tr class="uk-text-bold">';
                                echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">'.$sub.' ('.$total_student.')</td>';
                                echo '<td />.';
                                echo '</tr>';
                                echo $str;

                            } else {

                                echo '<tr class="uk-text-bold">';
                                echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">'.$sub.' (0)</td>';
                                echo '<td />';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<td style="text-indent: 50px;border:none;">No Record Found</td>';
                                echo '</tr>';    
                            }
                        }
                        else 
                        {
                                echo '<tr class="uk-text-bold">';
                                echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">'.$sub.' (0)</td>';
                                echo '<td />';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<td style="text-indent: 50px;border:none;">No Record Found</td>';
                                echo '</tr>';    
                        }
                    }

                ?>
            </tbody>
        </table>
        <!-- SCHOOL FEES END -->
    </div>

    <!-- 
    //////////////////////////////
    B) AFTERNOON PROGRAMME FEES
    //////////////////////////////
    -->
    <div name="afternoon_programme_fees">
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold " style="background-color: #F5F5F5;color:#2F2F2F;border:none;">
                    <td style="vertical-align: middle;font-size:18px;">B) Afternoon Programme Fees</td>
                    <td />
                </tr>
            </tbody>
        </table>
        <!-- AFTERNOON PROGRAMME FEES START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <?php
                    for($i = 1; $i <= 4; $i++) 
                    {
                        if($i == 1) { 
                            $sub = 'EDP'; 
                        } else if($i == 2) { 
                            $sub = 'QF1'; 
                        } else if($i == 3) { 
                            $sub = 'QF2'; 
                        } else if($i == 4) { 
                            $sub = 'QF3'; 
                        } else {
                            $sub = '';
                        }

                        $sql="SELECT programme_package as fees_structure, active_student, fee_rate as basic_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Afternoon Programme Fees' and subject='".$sub."'";

                        $result=mysqli_query($connection, $sql);
                        $num_row = mysqli_num_rows($result);

                        if ($num_row>0) {
    
                            $str = '';
                            $total_student = 0;

                            while ($row=mysqli_fetch_assoc($result)) {

                                if($row['active_student'] > 0) {
                    
                                    $str .= '<tr class="uk-text-bold">';
                                    $str .= '<td style="vertical-align: middle;font-size:16px;border:none;text-decoration: underline;">'.$row['fees_structure'].' ('.$row['active_student'].')</td>';
                                    $str .= '</tr>';

                                    $sql="SELECT ps.student_entry_level, s.id, s.name from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and (s.student_status = 'A' or (s.student_status = 'D' and s.defer_date >= '$current_date')) and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='".$sub."' and fl.afternoon_programme =1 and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."'  ";

                                    $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";

                                    $sql .= "   group by ps.student_entry_level, s.id LIMIT ".$row['active_student'];

                                    $resultt=mysqli_query($connection, $sql);
                                
                                    $j = 1;

                                    $num = mysqli_num_rows($resultt);

                                    $total_student += $row['active_student'];

                                    while ($roww=mysqli_fetch_array($resultt)) {
                                        
                                        if($j%2 == 0) {
                    
                                            $str .= '<td class="uk-text-bold" style="text-indent: 50px;border:none;">'.$j.'. '.$roww['name'].'</td>';
                                            $str .= '</tr>';
                    
                                        } else if($j%2 == 1) {
                    
                                            $str .= '<tr>';
                                            $str .= '<td class="uk-text-bold" style="text-indent: 50px;border:none;">'.$j.'. '.$roww['name'].'</td>';
                    
                                        }
                                        $j++;
                                    }
                                }
                            }
                            if($total_student > 0) {

                                echo '<tr class="uk-text-bold">';
                                echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">'.$sub.' ('.$total_student.')</td>';
                                echo '<td />.';
                                echo '</tr>';
                                echo $str;

                            } else {

                                echo '<tr class="uk-text-bold">';
                                echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">'.$sub.' (0)</td>';
                                echo '<td />';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<td style="text-indent: 50px;border:none;">No Record Found</td>';
                                echo '</tr>';    
                            }
                        }
                        else 
                        {
                                echo '<tr class="uk-text-bold">';
                                echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">'.$sub.' (0)</td>';
                                echo '<td />';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<td style="text-indent: 50px;border:none;">No Record Found</td>';
                                echo '</tr>';    
                        }
                    }

                ?>
            </tbody>
        </table>
        <!-- AFTERNOON PROGRAMME FEES END -->
    </div>

    <!-- 
    //////////////////////////////
    C) MATERIAL FEES
    //////////////////////////////
    -->
    <div name="material_fees">
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold" style="background-color: #F5F5F5;color:#2F2F2F;border:none;">
                    <td style="vertical-align: middle;font-size:18px;">C) Material Fees</td>
                    <td />
                </tr>
            </tbody>
        </table>

        <!-- MATERIAL FEE START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <?php
                    for($i = 1; $i <= 4; $i++) 
                    {
                        if($i == 1) { 
                            $sub = 'EDP'; 
                        } else if($i == 2) { 
                            $sub = 'QF1'; 
                        } else if($i == 3) { 
                            $sub = 'QF2'; 
                        } else if($i == 4) { 
                            $sub = 'QF3'; 
                        } else {
                            $sub = '';
                        }

                        $sql="SELECT programme_package as fees_structure, active_student, fee_rate as integrated_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Q-dess Foundation Materials' and subject='".$sub."'";

                        $result=mysqli_query($connection, $sql);
                        $num_row = mysqli_num_rows($result);

                        if ($num_row>0) {
    
                            $str = '';
                            $total_student = 0;

                            while ($row=mysqli_fetch_assoc($result)) {

                                if($row['active_student'] > 0) {
                    
                                    $str .= '<tr class="uk-text-bold">';
                                    $str .= '<td style="vertical-align: middle;font-size:16px;border:none;text-decoration: underline;">'.$row['fees_structure'].' ('.$row['active_student'].')</td>';
                                    $str .= '</tr>';

                                    $sql="SELECT ps.student_entry_level, s.id, s.name from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and (s.student_status = 'A' or (s.student_status = 'D' and s.defer_date >= '$current_date')) and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='".$sub."' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' ";

                                    $sql .= " and case when f.integrated_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                    when f.integrated_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                    when f.integrated_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                    when f.integrated_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                    end ";

                                    $sql .= " group by ps.student_entry_level, s.id LIMIT ".$row['active_student'];

                                    

                                    $resultt=mysqli_query($connection, $sql);
                                
                                    $j = 1;

                                    $num = mysqli_num_rows($resultt);

                                    $total_student += $row['active_student'];

                                    while ($roww=mysqli_fetch_array($resultt)) {
                                        
                                        if($j%2 == 0) {
                    
                                            $str .= '<td class="uk-text-bold" style="text-indent: 50px;border:none;">'.$j.'. '.$roww['name'].'</td>';
                                            $str .= '</tr>';
                    
                                        } else if($j%2 == 1) {
                    
                                            $str .= '<tr>';
                                            $str .= '<td class="uk-text-bold" style="text-indent: 50px;border:none;">'.$j.'. '.$roww['name'].'</td>';
                    
                                        }
                                        $j++;
                                    }
                                }
                            }
                            if($total_student > 0) {

                                echo '<tr class="uk-text-bold">';
                                echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">'.$sub.' ('.$total_student.')</td>';
                                echo '<td />.';
                                echo '</tr>';
                                echo $str;

                            } else {

                                echo '<tr class="uk-text-bold">';
                                echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">'.$sub.' (0)</td>';
                                echo '<td />';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<td style="text-indent: 50px;border:none;">No Record Found</td>';
                                echo '</tr>';    
                            }
                        }
                        else 
                        {
                                echo '<tr class="uk-text-bold">';
                                echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">'.$sub.' (0)</td>';
                                echo '<td />';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<td style="text-indent: 50px;border:none;">No Record Found</td>';
                                echo '</tr>';    
                        }
                    }

                ?>
            </tbody>
        </table>
        <!-- MATERIAL FEE END -->
    </div>

    <!-- 
    //////////////////////////////
    D) Q-DEES FOUDNATION MANDARIN MODULES MATERIALS
    //////////////////////////////
    -->
    <div name="foundation_mandarin">
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold" style="background-color: #F5F5F5;color:#2F2F2F;border:none;">
                    <td style="vertical-align: middle;font-size:18px;">D) Q-Dees Foundation Mandarin Modules Materials</td>
                    <td />
                </tr>
            </tbody>
        </table>

        <!-- MANDARIN START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <?php
                    for($i = 1; $i <= 4; $i++) 
                    {
                        if($i == 1) { 
                            $sub = 'EDP'; 
                        } else if($i == 2) { 
                            $sub = 'QF1'; 
                        } else if($i == 3) { 
                            $sub = 'QF2'; 
                        } else if($i == 4) { 
                            $sub = 'QF3'; 
                        } else {
                            $sub = '';
                        }

                        $sql="SELECT programme_package as fees_structure, active_student, fee_rate as mandarin_m_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Q-dees Foundation Mandarin Modules Materials' and subject='".$sub."'";

                        $result=mysqli_query($connection, $sql);
                        $num_row = mysqli_num_rows($result);

                        if ($num_row>0) {
    
                            $str = '';
                            $total_student = 0;

                            while ($row=mysqli_fetch_assoc($result)) {

                                if($row['active_student'] > 0) {
                    
                                    $str .= '<tr class="uk-text-bold">';
                                    $str .= '<td style="vertical-align: middle;font-size:16px;border:none;text-decoration: underline;">'.$row['fees_structure'].' ('.$row['active_student'].')</td>';
                                    $str .= '</tr>';

                                    $sql="SELECT ps.student_entry_level, s.id, s.name from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and (s.student_status = 'A' or (s.student_status = 'D' and s.defer_date >= '$current_date')) and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='".$sub."' and fl.foundation_mandarin =1 and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' ";

                                    $sql .= " and case when f.mandarin_m_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                    when f.mandarin_m_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                    when f.mandarin_m_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                    when f.mandarin_m_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                    end ";

                                    $sql .= " group by ps.student_entry_level, s.id LIMIT ".$row['active_student'];

                                    $resultt=mysqli_query($connection, $sql);
                                
                                    $j = 1;

                                    $num = mysqli_num_rows($resultt);

                                    $total_student += $row['active_student'];

                                    while ($roww=mysqli_fetch_array($resultt)) {
                                        
                                        if($j%2 == 0) {
                    
                                            $str .= '<td class="uk-text-bold" style="text-indent: 50px;border:none;">'.$j.'. '.$roww['name'].'</td>';
                                            $str .= '</tr>';
                    
                                        } else if($j%2 == 1) {
                    
                                            $str .= '<tr>';
                                            $str .= '<td class="uk-text-bold" style="text-indent: 50px;border:none;">'.$j.'. '.$roww['name'].'</td>';
                    
                                        }
                                        $j++;
                                    }
                                }
                            }
                            if($total_student > 0) {

                                echo '<tr class="uk-text-bold">';
                                echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">'.$sub.' ('.$total_student.')</td>';
                                echo '<td />.';
                                echo '</tr>';
                                echo $str;

                            } else {

                                echo '<tr class="uk-text-bold">';
                                echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">'.$sub.' (0)</td>';
                                echo '<td />';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<td style="text-indent: 50px;border:none;">No Record Found</td>';
                                echo '</tr>';    
                            }
                        }
                        else 
                        {
                                echo '<tr class="uk-text-bold">';
                                echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">'.$sub.' (0)</td>';
                                echo '<td />';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<td style="text-indent: 50px;border:none;">No Record Found</td>';
                                echo '</tr>';    
                        }
                    }

                ?>
            </tbody>
        </table>
        <!-- MANDARIN END -->
    </div>

    <!-- 
    //////////////////////////////
    E) REGISTRATION PACK
    //////////////////////////////
    -->
    <div name="registration_pack">
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold" style="background-color: #F5F5F5;color:#2F2F2F;border:none;">
                    <td style="vertical-align: middle;font-size:18px;">E) REGISTRATION PACK</td>
                    <td />
                </tr>
            </tbody>
        </table>

        <!-- REGISTRATION START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <?php
                    for($i = 1; $i <= 4; $i++) 
                    {
                        if($i == 1) { 
                            $sub = 'EDP'; 
                        } else if($i == 2) { 
                            $sub = 'QF1'; 
                        } else if($i == 3) { 
                            $sub = 'QF2'; 
                        } else if($i == 4) { 
                            $sub = 'QF3'; 
                        } else {
                            $sub = '';
                        }

                        $sql="SELECT programme_package as fees_structure, active_student, fee_rate as registration_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Registration Pack' and subject='".$sub."'";

                        $result=mysqli_query($connection, $sql);
                        $num_row = mysqli_num_rows($result);

                        if ($num_row>0) {
    
                            $str = '';
                            $total_student = 0;

                            while ($row=mysqli_fetch_assoc($result)) {

                                if($row['active_student'] > 0) {
                    
                                    $str .= '<tr class="uk-text-bold">';
                                    $str .= '<td style="vertical-align: middle;font-size:16px;border:none;text-decoration: underline;">'.$row['fees_structure'].' ('.$row['active_student'].')</td>';
                                    $str .= '</tr>';

                                    $sql="SELECT ps.student_entry_level, s.id, s.name from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and (s.student_status = 'A' or (s.student_status = 'D' and s.defer_date >= '$current_date')) and DATE_FORMAT(s.start_date_at_centre, '%Y-%m') = '".$monthyear."' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='".$sub."' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.registration_adjust !=''  and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id LIMIT ".$row['active_student'];

                                    $resultt=mysqli_query($connection, $sql);
                                
                                    $j = 1;

                                    $num = mysqli_num_rows($resultt);

                                    $total_student += $row['active_student'];

                                    while ($roww=mysqli_fetch_array($resultt)) {
                                        
                                        if($j%2 == 0) {
                    
                                            $str .= '<td class="uk-text-bold" style="text-indent: 50px;border:none;">'.$j.'. '.$roww['name'].'</td>';
                                            $str .= '</tr>';
                    
                                        } else if($j%2 == 1) {
                    
                                            $str .= '<tr>';
                                            $str .= '<td class="uk-text-bold" style="text-indent: 50px;border:none;">'.$j.'. '.$roww['name'].'</td>';
                    
                                        }
                                        $j++;
                                    }
                                }
                            }
                            if($total_student > 0) {

                                echo '<tr class="uk-text-bold">';
                                echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">'.$sub.' ('.$total_student.')</td>';
                                echo '<td />.';
                                echo '</tr>';
                                echo $str;

                            } else {

                                echo '<tr class="uk-text-bold">';
                                echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">'.$sub.' (0)</td>';
                                echo '<td />';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<td style="text-indent: 50px;border:none;">No Record Found</td>';
                                echo '</tr>';    
                            }
                        }
                        else 
                        {
                                echo '<tr class="uk-text-bold">';
                                echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">'.$sub.' (0)</td>';
                                echo '<td />';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<td style="text-indent: 50px;border:none;">No Record Found</td>';
                                echo '</tr>';    
                        }
                    }

                ?>
            </tbody>
        </table>
        <!-- REGISTRATION END -->
    </div>

    <!-- 
    //////////////////////////////
    F) PRODUCTS
    //////////////////////////////
    -->
    <div name="products">
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold" style="background-color: #F5F5F5;color:#2F2F2F;border:none;">
                    <td style="vertical-align: middle;font-size:18px;">F) PRODUCTS</td>
                    <td />
                </tr>
            </tbody>
        </table>

        <!-- 
        //////////////////////////////
        i) Pre-School Kits
        //////////////////////////////
        -->

        <!-- PRE-SCHOOL KITS START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <?php
                    $sql="SELECT programme_package as fees_structure, active_student, fee_rate, amount from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Pre-school Kits'";

                    $result=mysqli_query($connection, $sql);
                    $num_row = mysqli_num_rows($result);

                    if ($num_row>0) {

                        $str = '';
                                                                                                                                         
                        $total_student = 0;

                        while ($row=mysqli_fetch_assoc($result)) {

                            if($row['active_student'] > 0) {

                                $sql="SELECT ps.student_entry_level, s.id, s.name from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and (s.student_status = 'A' or (s.student_status = 'D' and s.defer_date >= '$current_date')) and DATE_FORMAT(s.start_date_at_centre, '%Y-%m') = '".$monthyear."' and s.centre_code='$centre_code' and s.deleted='0'";

                                $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id LIMIT ".$row['active_student'];

                                $resultt=mysqli_query($connection, $sql);
                            
                                $j = 1;

                                $num = mysqli_num_rows($resultt);

                                $total_student += $row['active_student'];

                                while ($roww=mysqli_fetch_array($resultt)) {
                                    
                                    if($j%2 == 0) {
                
                                        $str .= '<td class="uk-text-bold" style="text-indent: 50px;border:none;">'.$j.'. '.$roww['name'].'</td>';
                                        $str .= '</tr>';
                
                                    } else if($j%2 == 1) {
                
                                        $str .= '<tr>';
                                        $str .= '<td class="uk-text-bold" style="text-indent: 50px;border:none;">'.$j.'. '.$roww['name'].'</td>';
                
                                    }
                                    $j++;
                                }
                            }
                        }
                        if($total_student > 0) {

                            echo '<tr class="uk-text-bold">';
                            echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">i) Pre-School Kits ('.$total_student.')</td>';
                            echo '<td />.';
                            echo '</tr>';
                            echo $str;

                        } else {

                            echo '<tr class="uk-text-bold">';
                            echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">i) Pre-School Kits (0)</td>';
                            echo '<td />';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td style="text-indent: 50px;border:none;">No Record Found</td>';
                            echo '</tr>';    
                        }
                    }
                    else 
                    {
                            echo '<tr class="uk-text-bold">';
                            echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">i) Pre-School Kits (0)</td>';
                            echo '<td />';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td style="text-indent: 50px;border:none;">No Record Found</td>';
                            echo '</tr>';    
                    }
                ?>
            </tbody>
        </table>
        <!-- PRE-SCHOOL KITS END -->

        <!-- 
        //////////////////////////////
        ii) Memories to Cherish
        //////////////////////////////
        -->

        <!-- MEMORIES TO CHERISH START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <?php
                    $sql="SELECT programme_package as fees_structure, active_student, fee_rate, amount from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Memories to Cherish'";

                    $result=mysqli_query($connection, $sql);
                    $num_row = mysqli_num_rows($result);

                    if ($num_row>0) {

                        $str = '';
                                                                                                                                         
                        $total_student = 0;

                        while ($row=mysqli_fetch_assoc($result)) {

                            $row['active_student'] = 0;

                            if($row['active_student'] > 0) {

                                $sql = "SELECT round(sum(level_count), 0) level_count, round(avg(fees), 2) fees from ( ";
                                $sql .="SELECT round(sum(qty), 0) level_count, fees from (SELECT c.qty, c.unit_price as fees from student s inner join `collection` c on c.student_id = s.id inner join product p on p.product_code=c.product_code where c.void='0' and c.year = '$year' and (s.student_status = 'A' or (s.student_status = 'D' and s.defer_date >= '$current_date')) and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and p.product_name like 'Memories To Cherish Album%' and month(c.collection_date_time) = $month group by c.id) ab";
                                $sql .=" UNION ALL ";
                                $sql .=" SELECT count(id) level_count, fees from (SELECT ps.student_entry_level, s.id, c.unit_price as fees from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where c.void='0' and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and (s.student_status = 'A' or (s.student_status = 'D' and s.defer_date >= '$current_date')) and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and c.product_code='MEMORIES TO CHERISH' and month(c.collection_date_time) = $month group by ps.student_entry_level, s.id) ab ";
                                $sql .= " )abc ";

                                $resultt=mysqli_query($connection, $sql);
                            
                                $j = 1;

                                $num = mysqli_num_rows($resultt);

                                $total_student += $row['active_student'];

                                while ($roww=mysqli_fetch_array($resultt)) {
                                    
                                    if($j%2 == 0) {
                
                                        $str .= '<td class="uk-text-bold" style="text-indent: 50px;border:none;">'.$j.'. '.$roww['name'].'</td>';
                                        $str .= '</tr>';
                
                                    } else if($j%2 == 1) {
                
                                        $str .= '<tr>';
                                        $str .= '<td class="uk-text-bold" style="text-indent: 50px;border:none;">'.$j.'. '.$roww['name'].'</td>';
                
                                    }
                                    $j++;
                                }
                            }
                        }
                        if($total_student > 0) {

                            echo '<tr class="uk-text-bold">';
                            echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">ii) Memories to Cherish ('.$total_student.')</td>';
                            echo '<td />.';
                            echo '</tr>';
                            echo $str;

                        } else {

                            echo '<tr class="uk-text-bold">';
                            echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">ii) Memories to Cherish (0)</td>';
                            echo '<td />';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td style="text-indent: 50px;border:none;">No Record Found</td>';
                            echo '</tr>';    
                        }
                    }
                    else 
                    {
                            echo '<tr class="uk-text-bold">';
                            echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">ii) Memories to Cherish</td>';
                            echo '<td />';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td style="text-indent: 50px;border:none;">No Record Found</td>';
                            echo '</tr>';    
                    }
                ?>
            </tbody>
        </table>
        <!-- MEMORIES TO CHERISH END -->

        <!-- 
        //////////////////////////////
        iii) Q-DEES BAG
        //////////////////////////////
        -->

        <!-- Q-DEES BAG START -->
        
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <?php
                    $sql="SELECT programme_package as fees_structure, active_student, fee_rate, amount from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Q-dees Bag'";

                    $result=mysqli_query($connection, $sql);
                    $num_row = mysqli_num_rows($result);

                    if ($num_row>0) {

                        $str = '';
                                                                                                                                         
                        $total_student = 0;

                        while ($row=mysqli_fetch_assoc($result)) {

                            if($row['active_student'] > 0) {

                                //$sql="SELECT ps.student_entry_level, s.id, s.name from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where c.void='0' and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and c.product_code='Q-dees Bag' and DATE_FORMAT(c.collection_date_time, '%Y-%m') = '$monthyear' group by ps.student_entry_level, s.id LIMIT ".$row['active_student'];

                                $sql="SELECT ps.student_entry_level, s.id, s.name from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and (s.student_status = 'A' or (s.student_status = 'D' and s.defer_date >= '$current_date')) and DATE_FORMAT(s.start_date_at_centre, '%Y-%m') = '".$monthyear."' and s.centre_code='$centre_code' and s.deleted='0' and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id LIMIT ".$row['active_student'];

                                $resultt=mysqli_query($connection, $sql);
                            
                                $j = 1;

                                $num = mysqli_num_rows($resultt);

                                $total_student += $row['active_student'];

                                while ($roww=mysqli_fetch_array($resultt)) {
                                    
                                    if($j%2 == 0) {
                
                                        $str .= '<td class="uk-text-bold" style="text-indent: 50px;border:none;">'.$j.'. '.$roww['name'].'</td>';
                                        $str .= '</tr>';
                
                                    } else if($j%2 == 1) {
                
                                        $str .= '<tr>';
                                        $str .= '<td class="uk-text-bold" style="text-indent: 50px;border:none;">'.$j.'. '.$roww['name'].'</td>';
                
                                    }
                                    $j++;
                                }
                            }
                        }
                        if($total_student > 0) {

                            echo '<tr class="uk-text-bold">';
                            echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">iii) Q-Dees Bag ('.$total_student.')</td>';
                            echo '<td />.';
                            echo '</tr>';
                            echo $str;

                        } else {

                            echo '<tr class="uk-text-bold">';
                            echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">iii) Q-Dees Bag (0)</td>';
                            echo '<td />';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td style="text-indent: 50px;border:none;">No Record Found</td>';
                            echo '</tr>';    
                        }
                    }
                    else 
                    {
                            echo '<tr class="uk-text-bold">';
                            echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">iii) Q-Dees Bag (0)</td>';
                            echo '<td />';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td style="text-indent: 50px;border:none;">No Record Found</td>';
                            echo '</tr>';    
                    }
                ?>
            </tbody>
        </table>

        <!-- Q-DEES BAG END -->

    </div>

    <!-- 
    //////////////////////////////
    iv) UNIFORM
    //////////////////////////////
    -->

    <!-- UNIFORM START -->
    <table class="uk-table" style="width: 100%;">
        <tbody>
            <?php
                $sql="SELECT programme_package as fees_structure, active_student, fee_rate, amount from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Uniform'";

                $result=mysqli_query($connection, $sql);
                $num_row = mysqli_num_rows($result);

                if ($num_row>0) {

                    $str = '';
                                                                                                                                        
                    $total_student = 0;

                    while ($row=mysqli_fetch_assoc($result)) {

                        if($row['active_student'] > 0) {

                            $sql="SELECT ps.student_entry_level, s.id, s.name from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where c.void='0' and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and (s.student_status = 'A' or (s.student_status = 'D' and s.defer_date >= '$current_date')) and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and c.product_code='Uniform (2 sets)' and DATE_FORMAT(c.collection_date_time, '%Y-%m') = '$monthyear' group by ps.student_entry_level, s.id LIMIT ".$row['active_student'];

                            $resultt=mysqli_query($connection, $sql);
                        
                            $j = 1;

                            $num = mysqli_num_rows($resultt);

                            $total_student += $row['active_student'];

                            while ($roww=mysqli_fetch_array($resultt)) {
                                
                                if($j%2 == 0) {
            
                                    $str .= '<td class="uk-text-bold" style="text-indent: 50px;border:none;">'.$j.'. '.$roww['name'].'</td>';
                                    $str .= '</tr>';
            
                                } else if($j%2 == 1) {
            
                                    $str .= '<tr>';
                                    $str .= '<td class="uk-text-bold" style="text-indent: 50px;border:none;">'.$j.'. '.$roww['name'].'</td>';
            
                                }
                                $j++;
                            }
                        }
                    }
                    if($total_student > 0) {

                        echo '<tr class="uk-text-bold">';
                        echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">iv) Uniform ('.$total_student.')</td>';
                        echo '<td />.';
                        echo '</tr>';
                        echo $str;

                    } else {

                        echo '<tr class="uk-text-bold">';
                        echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">iv) Uniform (0)</td>';
                        echo '<td />';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td style="text-indent: 50px;border:none;">No Record Found</td>';
                        echo '</tr>';    
                    }
                }
                else 
                {
                        echo '<tr class="uk-text-bold">';
                        echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">iv) Uniform (0)</td>';
                        echo '<td />';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td style="text-indent: 50px;border:none;">No Record Found</td>';
                        echo '</tr>';    
                }
            ?>
        </tbody>
    </table>
    <!-- UNIFORM END -->

     <!-- 
    //////////////////////////////
    v) GYMWEAR
    //////////////////////////////
    -->

    <!-- GYMWEAR START -->
    <table class="uk-table" style="width: 100%;">
        <tbody>
            <?php
                $sql="SELECT programme_package as fees_structure, active_student, fee_rate, amount from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Gymwear'";

                $result=mysqli_query($connection, $sql);
                $num_row = mysqli_num_rows($result);

                if ($num_row>0) {

                    $str = '';
                                                                                                                                        
                    $total_student = 0;

                    while ($row=mysqli_fetch_assoc($result)) {

                        if($row['active_student'] > 0) {

                            $sql="SELECT ps.student_entry_level, s.id, s.name from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where c.void='0' and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and (s.student_status = 'A' or (s.student_status = 'D' and s.defer_date >= '$current_date')) and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and c.product_code='Gymwear (1 set)' and DATE_FORMAT(c.collection_date_time, '%Y-%m') = '$monthyear' group by ps.student_entry_level, s.id LIMIT ".$row['active_student'];

                            $resultt=mysqli_query($connection, $sql);
                        
                            $j = 1;

                            $num = mysqli_num_rows($resultt);

                            $total_student += $row['active_student'];

                            while ($roww=mysqli_fetch_array($resultt)) {
                                
                                if($j%2 == 0) {
            
                                    $str .= '<td class="uk-text-bold" style="text-indent: 50px;border:none;">'.$j.'. '.$roww['name'].'</td>';
                                    $str .= '</tr>';
            
                                } else if($j%2 == 1) {
            
                                    $str .= '<tr>';
                                    $str .= '<td class="uk-text-bold" style="text-indent: 50px;border:none;">'.$j.'. '.$roww['name'].'</td>';
            
                                }
                                $j++;
                            }
                        }
                    }
                    if($total_student > 0) {

                        echo '<tr class="uk-text-bold">';
                        echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">v) Gymwear ('.$total_student.')</td>';
                        echo '<td />.';
                        echo '</tr>';
                        echo $str;

                    } else {

                        echo '<tr class="uk-text-bold">';
                        echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">v) Gymwear (0)</td>';
                        echo '<td />';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td style="text-indent: 50px;border:none;">No Record Found</td>';
                        echo '</tr>';    
                    }
                }
                else 
                {
                        echo '<tr class="uk-text-bold">';
                        echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">v) Gymwear (0)</td>';
                        echo '<td />';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td style="text-indent: 50px;border:none;">No Record Found</td>';
                        echo '</tr>';    
                }
            ?>
        </tbody>
    </table>
    <!-- GYMWEAR END -->

    <div name="registration_pack">

        <table class="uk-table" style="width: 100%;text-align:center;">
            <tbody>
                <tr class="uk-text-bold" style="background-color: #F5F5F5;color:#2F2F2F;border:none;">
                    <td style="vertical-align: middle;font-size:18px;">Form: 2</td>
                    <td />
                </tr>
            </tbody>
        </table>

        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold" style="background-color: #F5F5F5;color:#2F2F2F;border:none;">
                    <td style="vertical-align: middle;font-size:18px;">A: School Fees Foundation Programme</td>
                    <td />
                </tr>
            </tbody>
        </table>

        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold" style="background-color: #F5F5F5;color:#2F2F2F;border:none;">
                    <td style="vertical-align: middle;font-size:18px;">i) INTERNATIONAL ENGLISH</td>
                    <td />
                </tr>
            </tbody>
        </table>
    <!-- INTERNATIONAL ENGLISH -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <?php
                    for($i = 1; $i <= 4; $i++) 
                    {
                        if($i == 1) { 
                            $sub = 'EDP'; 
                        } else if($i == 2) { 
                            $sub = 'QF1'; 
                        } else if($i == 3) { 
                            $sub = 'QF2'; 
                        } else if($i == 4) { 
                            $sub = 'QF3'; 
                        } else {
                            $sub = '';
                        }

                        $sql="SELECT programme_package as fees_structure, active_student, fee_rate as enhanced_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='International English' and subject='".$sub."'";

                        $result=mysqli_query($connection, $sql);
                        $num_row = mysqli_num_rows($result);

                        if ($num_row>0) {
    
                            $str = '';
                            $total_student = 0;

                            while ($row=mysqli_fetch_assoc($result)) {

                                if($row['active_student'] > 0) {
                    
                                    $str .= '<tr class="uk-text-bold">';
                                    $str .= '<td style="vertical-align: middle;font-size:16px;border:none;text-decoration: underline;">'.$row['fees_structure'].' ('.$row['active_student'].')</td>';
                                    $str .= '</tr>';

                                    $sql="SELECT ps.student_entry_level, s.id, s.name from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and (s.student_status = 'A' or (s.student_status = 'D' and s.defer_date >= '$current_date')) and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='".$sub."' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."'  and fl.foundation_int_english=1  ";

                                    $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";

                                    $sql .= "   group by ps.student_entry_level, s.id LIMIT ".$row['active_student'];

                                    $resultt=mysqli_query($connection, $sql);
                                
                                    $j = 1;

                                    $num = mysqli_num_rows($resultt);

                                    $total_student += $row['active_student'];

                                    while ($roww=mysqli_fetch_array($resultt)) {
                                        
                                        if($j%2 == 0) {
                    
                                            $str .= '<td class="uk-text-bold" style="text-indent: 50px;border:none;">'.$j.'. '.$roww['name'].'</td>';
                                            $str .= '</tr>';
                    
                                        } else if($j%2 == 1) {
                    
                                            $str .= '<tr>';
                                            $str .= '<td class="uk-text-bold" style="text-indent: 50px;border:none;">'.$j.'. '.$roww['name'].'</td>';
                    
                                        }
                                        $j++;
                                    }
                                }
                            }
                            if($total_student > 0) {

                                echo '<tr class="uk-text-bold">';
                                echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">'.$sub.' ('.$total_student.')</td>';
                                echo '<td />.';
                                echo '</tr>';
                                echo $str;

                            } else {

                                echo '<tr class="uk-text-bold">';
                                echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">'.$sub.' (0)</td>';
                                echo '<td />';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<td style="text-indent: 50px;border:none;">No Record Found</td>';
                                echo '</tr>';    
                            }
                        }
                        else 
                        {
                                echo '<tr class="uk-text-bold">';
                                echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">'.$sub.' (0)</td>';
                                echo '<td />';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<td style="text-indent: 50px;border:none;">No Record Found</td>';
                                echo '</tr>';    
                        }
                    }

                ?>
            </tbody>
        </table>
        <!-- INTERNATIONAL ENGLISH -->
    </div>

    <div name="registration_pack">
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold" style="background-color: #F5F5F5;color:#2F2F2F;border:none;">
                    <td style="vertical-align: middle;font-size:18px;">ii) INTERNATIONAL ART</td>
                    <td />
                </tr>
            </tbody>
        </table>
    <!-- INTERNATIONAL ART -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <?php
                    for($i = 1; $i <= 4; $i++) 
                    {
                        if($i == 1) { 
                            $sub = 'EDP'; 
                        } else if($i == 2) { 
                            $sub = 'QF1'; 
                        } else if($i == 3) { 
                            $sub = 'QF2'; 
                        } else if($i == 4) { 
                            $sub = 'QF3'; 
                        } else {
                            $sub = '';
                        }

                        $sql="SELECT programme_package as fees_structure, active_student, fee_rate as international_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='International Art' and subject='".$sub."'";

                        $result=mysqli_query($connection, $sql);
                        $num_row = mysqli_num_rows($result);

                        if ($num_row>0) {
    
                            $str = '';
                            $total_student = 0;

                            while ($row=mysqli_fetch_assoc($result)) {

                                if($row['active_student'] > 0) {
                    
                                    $str .= '<tr class="uk-text-bold">';
                                    $str .= '<td style="vertical-align: middle;font-size:16px;border:none;text-decoration: underline;">'.$row['fees_structure'].' ('.$row['active_student'].')</td>';
                                    $str .= '</tr>';

                                    $sql="SELECT ps.student_entry_level, s.id, s.name from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and (s.student_status = 'A' or (s.student_status = 'D' and s.defer_date >= '$current_date')) and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='".$sub."' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and fl.foundation_int_art=1  ";

                                    $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
                                    

                                    $sql .= "   group by ps.student_entry_level, s.id LIMIT ".$row['active_student'];

                                    $resultt=mysqli_query($connection, $sql);
                                
                                    $j = 1;

                                    $num = mysqli_num_rows($resultt);

                                    $total_student += $row['active_student'];

                                    while ($roww=mysqli_fetch_array($resultt)) {
                                        
                                        if($j%2 == 0) {
                    
                                            $str .= '<td class="uk-text-bold" style="text-indent: 50px;border:none;">'.$j.'. '.$roww['name'].'</td>';
                                            $str .= '</tr>';
                    
                                        } else if($j%2 == 1) {
                    
                                            $str .= '<tr>';
                                            $str .= '<td class="uk-text-bold" style="text-indent: 50px;border:none;">'.$j.'. '.$roww['name'].'</td>';
                    
                                        }
                                        $j++;
                                    }
                                }
                            }
                            if($total_student > 0) {

                                echo '<tr class="uk-text-bold">';
                                echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">'.$sub.' ('.$total_student.')</td>';
                                echo '<td />.';
                                echo '</tr>';
                                echo $str;

                            } else {

                                echo '<tr class="uk-text-bold">';
                                echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">'.$sub.' (0)</td>';
                                echo '<td />';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<td style="text-indent: 50px;border:none;">No Record Found</td>';
                                echo '</tr>';    
                            }
                        }
                        else 
                        {
                                echo '<tr class="uk-text-bold">';
                                echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">'.$sub.' (0)</td>';
                                echo '<td />';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<td style="text-indent: 50px;border:none;">No Record Found</td>';
                                echo '</tr>';    
                        }
                    }

                ?>
            </tbody>
        </table>
        <!-- INTERNATIONAL ART -->
    </div>

    <div name="registration_pack">
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold" style="background-color: #F5F5F5;color:#2F2F2F;border:none;">
                    <td style="vertical-align: middle;font-size:18px;">iii) MANDARIN</td>
                    <td />
                </tr>
            </tbody>
        </table>
    <!-- MANDARIN -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <?php
                    for($i = 1; $i <= 4; $i++) 
                    {
                        if($i == 1) { 
                            $sub = 'EDP'; 
                        } else if($i == 2) { 
                            $sub = 'QF1'; 
                        } else if($i == 3) { 
                            $sub = 'QF2'; 
                        } else if($i == 4) { 
                            $sub = 'QF3'; 
                        } else {
                            $sub = '';
                        }

                        $sql="SELECT programme_package as fees_structure, active_student, fee_rate as mandarin_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='Mandarin' and subject='".$sub."'";

                        $result=mysqli_query($connection, $sql);
                        $num_row = mysqli_num_rows($result);

                        if ($num_row>0) {
    
                            $str = '';
                            $total_student = 0;

                            while ($row=mysqli_fetch_assoc($result)) {

                                if($row['active_student'] > 0) {
                    
                                    $str .= '<tr class="uk-text-bold">';
                                    $str .= '<td style="vertical-align: middle;font-size:16px;border:none;text-decoration: underline;">'.$row['fees_structure'].' ('.$row['active_student'].')</td>';
                                    $str .= '</tr>';

                                    $sql="SELECT ps.student_entry_level, s.id, s.name from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and (s.student_status = 'A' or (s.student_status = 'D' and s.defer_date >= '$current_date')) and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='".$sub."' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and fl.foundation_int_mandarin=1  ";

                                    $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
                                
                                    $sql .= " group by ps.student_entry_level, s.id LIMIT ".$row['active_student'];

                                    $resultt=mysqli_query($connection, $sql);
                                
                                    $j = 1;

                                    $num = mysqli_num_rows($resultt);

                                    $total_student += $row['active_student'];

                                    while ($roww=mysqli_fetch_array($resultt)) {
                                        
                                        if($j%2 == 0) {
                    
                                            $str .= '<td class="uk-text-bold" style="text-indent: 50px;border:none;">'.$j.'. '.$roww['name'].'</td>';
                                            $str .= '</tr>';
                    
                                        } else if($j%2 == 1) {
                    
                                            $str .= '<tr>';
                                            $str .= '<td class="uk-text-bold" style="text-indent: 50px;border:none;">'.$j.'. '.$roww['name'].'</td>';
                    
                                        }
                                        $j++;
                                    }
                                }
                            }
                            if($total_student > 0) {

                                echo '<tr class="uk-text-bold">';
                                echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">'.$sub.' ('.$total_student.')</td>';
                                echo '<td />.';
                                echo '</tr>';
                                echo $str;

                            } else {

                                echo '<tr class="uk-text-bold">';
                                echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">'.$sub.' (0)</td>';
                                echo '<td />';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<td style="text-indent: 50px;border:none;">No Record Found</td>';
                                echo '</tr>';    
                            }
                        }
                        else 
                        {
                                echo '<tr class="uk-text-bold">';
                                echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">'.$sub.' (0)</td>';
                                echo '<td />';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<td style="text-indent: 50px;border:none;">No Record Found</td>';
                                echo '</tr>';    
                        }
                    }

                ?>
            </tbody>
        </table>
        <!-- MANDARIN -->
    </div>

    <div name="registration_pack">
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold" style="background-color: #F5F5F5;color:#2F2F2F;border:none;">
                    <td style="vertical-align: middle;font-size:18px;">iv) IQ MATH</td>
                    <td />
                </tr>
            </tbody>
        </table>
        <!-- IQ MATH -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <?php
                    for($i = 1; $i <= 4; $i++) 
                    {
                        if($i == 1) { 
                            $sub = 'EDP'; 
                        } else if($i == 2) { 
                            $sub = 'QF1'; 
                        } else if($i == 3) { 
                            $sub = 'QF2'; 
                        } else if($i == 4) { 
                            $sub = 'QF3'; 
                        } else {
                            $sub = '';
                        }

                        $sql="SELECT programme_package as fees_structure, active_student, fee_rate as iq_math_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='IQ Math' and subject='".$sub."'";

                        $result=mysqli_query($connection, $sql);
                        $num_row = mysqli_num_rows($result);

                        if ($num_row>0) {
    
                            $str = '';
                            $total_student = 0;

                            while ($row=mysqli_fetch_assoc($result)) {

                                if($row['active_student'] > 0) {
                    
                                    $str .= '<tr class="uk-text-bold">';
                                    $str .= '<td style="vertical-align: middle;font-size:16px;border:none;text-decoration: underline;">'.$row['fees_structure'].' ('.$row['active_student'].')</td>';
                                    $str .= '</tr>';

                                    $sql="SELECT ps.student_entry_level, s.id, s.name from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and (s.student_status = 'A' or (s.student_status = 'D' and s.defer_date >= '$current_date')) and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='".$sub."' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and fl.foundation_iq_math=1  ";

                                    $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";

                                    $sql .= "   group by ps.student_entry_level, s.id LIMIT ".$row['active_student'];

                                    $resultt=mysqli_query($connection, $sql);
                                
                                    $j = 1;

                                    $num = mysqli_num_rows($resultt);

                                    $total_student += $row['active_student'];

                                    while ($roww=mysqli_fetch_array($resultt)) {
                                        
                                        if($j%2 == 0) {
                    
                                            $str .= '<td class="uk-text-bold" style="text-indent: 50px;border:none;">'.$j.'. '.$roww['name'].'</td>';
                                            $str .= '</tr>';
                    
                                        } else if($j%2 == 1) {
                    
                                            $str .= '<tr>';
                                            $str .= '<td class="uk-text-bold" style="text-indent: 50px;border:none;">'.$j.'. '.$roww['name'].'</td>';
                    
                                        }
                                        $j++;
                                    }
                                }
                            }
                            if($total_student > 0) {

                                echo '<tr class="uk-text-bold">';
                                echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">'.$sub.' ('.$total_student.')</td>';
                                echo '<td />.';
                                echo '</tr>';
                                echo $str;

                            } else {

                                echo '<tr class="uk-text-bold">';
                                echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">'.$sub.' (0)</td>';
                                echo '<td />';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<td style="text-indent: 50px;border:none;">No Record Found</td>';
                                echo '</tr>';    
                            }
                        }
                        else 
                        {
                                echo '<tr class="uk-text-bold">';
                                echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">'.$sub.' (0)</td>';
                                echo '<td />';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<td style="text-indent: 50px;border:none;">No Record Found</td>';
                                echo '</tr>';    
                        }
                    }

                ?>
            </tbody>
        </table>
        <!-- IQ MATH -->
    </div>

    <div name="registration_pack">
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold" style="background-color: #F5F5F5;color:#2F2F2F;border:none;">
                    <td style="vertical-align: middle;font-size:18px;">v) Robotics Plus</td>
                    <td />
                </tr>
            </tbody>
        </table>
        <!-- Robotics Plus -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <?php
                    for($i = 1; $i <= 4; $i++) 
                    {
                        if($i == 1) { 
                            $sub = 'EDP'; 
                        } else if($i == 2) { 
                            $sub = 'QF1'; 
                        } else if($i == 3) { 
                            $sub = 'QF2'; 
                        } else if($i == 4) { 
                            $sub = 'QF3'; 
                        } else {
                            $sub = '';
                        }

                        $sql="SELECT programme_package as fees_structure, active_student, fee_rate as robotic_plus_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='Robotic Plus' and subject='".$sub."'";

                        $result=mysqli_query($connection, $sql);
                        $num_row = mysqli_num_rows($result);

                        if ($num_row>0) {
    
                            $str = '';
                            $total_student = 0;

                            while ($row=mysqli_fetch_assoc($result)) {

                                if($row['active_student'] > 0) {
                    
                                    $str .= '<tr class="uk-text-bold">';
                                    $str .= '<td style="vertical-align: middle;font-size:16px;border:none;text-decoration: underline;">'.$row['fees_structure'].' ('.$row['active_student'].')</td>';
                                    $str .= '</tr>';

                                    $sql="SELECT ps.student_entry_level, s.id, s.name from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and (s.student_status = 'A' or (s.student_status = 'D' and s.defer_date >= '$current_date')) and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='".$sub."' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and fl.robotic_plus=1  ";

                                    $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
                                
                                    $sql .= " group by ps.student_entry_level, s.id LIMIT ".$row['active_student'];

                                    $resultt=mysqli_query($connection, $sql);
                                
                                    $j = 1;

                                    $num = mysqli_num_rows($resultt);

                                    $total_student += $row['active_student'];

                                    while ($roww=mysqli_fetch_array($resultt)) {
                                        
                                        if($j%2 == 0) {
                    
                                            $str .= '<td class="uk-text-bold" style="text-indent: 50px;border:none;">'.$j.'. '.$roww['name'].'</td>';
                                            $str .= '</tr>';
                    
                                        } else if($j%2 == 1) {
                    
                                            $str .= '<tr>';
                                            $str .= '<td class="uk-text-bold" style="text-indent: 50px;border:none;">'.$j.'. '.$roww['name'].'</td>';
                    
                                        }
                                        $j++;
                                    }
                                }
                            }
                            if($total_student > 0) {

                                echo '<tr class="uk-text-bold">';
                                echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">'.$sub.' ('.$total_student.')</td>';
                                echo '<td />.';
                                echo '</tr>';
                                echo $str;

                            } else {

                                echo '<tr class="uk-text-bold">';
                                echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">'.$sub.' (0)</td>';
                                echo '<td />';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<td style="text-indent: 50px;border:none;">No Record Found</td>';
                                echo '</tr>';    
                            }
                        }
                        else 
                        {
                                echo '<tr class="uk-text-bold">';
                                echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">'.$sub.' (0)</td>';
                                echo '<td />';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<td style="text-indent: 50px;border:none;">No Record Found</td>';
                                echo '</tr>';    
                        }
                    }

                ?>
            </tbody>
        </table>
        <!-- Robotics Plus -->
    </div>

    <div name="registration_pack">
        
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold" style="background-color: #F5F5F5;color:#2F2F2F;border:none;">
                    <td style="vertical-align: middle;font-size:18px;">B: Readers</td>
                    <td />
                </tr>
            </tbody>
        </table>
                    
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold" style="background-color: #F5F5F5;color:#2F2F2F;border:none;">
                    <td style="vertical-align: middle;font-size:18px;">i) Link & Think Series</td>
                    <td />
                </tr>
            </tbody>
        </table>
        <!-- Link & Think Series -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <?php
                    for($i = 1; $i <= 4; $i++) 
                    {
                        if($i == 1) { 
                            $sub = 'EDP'; 
                        } else if($i == 2) { 
                            $sub = 'QF1'; 
                        } else if($i == 3) { 
                            $sub = 'QF2'; 
                        } else if($i == 4) { 
                            $sub = 'QF3'; 
                        } else {
                            $sub = '';
                        }

                        $sql="SELECT programme_package as fees_structure, active_student, fee_rate as link_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='Link & Think Series' and subject='".$sub."'";

                        $result=mysqli_query($connection, $sql);
                        $num_row = mysqli_num_rows($result);

                        if ($num_row>0) {
    
                            $str = '';
                            $total_student = 0;

                            while ($row=mysqli_fetch_assoc($result)) {

                                if($row['active_student'] > 0) {
                    
                                    $str .= '<tr class="uk-text-bold">';
                                    $str .= '<td style="vertical-align: middle;font-size:16px;border:none;text-decoration: underline;">'.$row['fees_structure'].' ('.$row['active_student'].')</td>';
                                    $str .= '</tr>';

                                    $sql="SELECT ps.student_entry_level, s.id, s.name from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and (s.student_status = 'A' or (s.student_status = 'D' and s.defer_date >= '$current_date')) and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='".$sub."' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' ";

                                    $sql .= " and case when f.integrated_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                    when f.integrated_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                    when f.integrated_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                    when f.integrated_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                    end ";

                                    $sql .= " group by ps.student_entry_level, s.id LIMIT ".$row['active_student'];

                                    $resultt=mysqli_query($connection, $sql);
                                
                                    $j = 1;

                                    $num = mysqli_num_rows($resultt);

                                    $total_student += $row['active_student'];

                                    while ($roww=mysqli_fetch_array($resultt)) {
                                        
                                        if($j%2 == 0) {
                    
                                            $str .= '<td class="uk-text-bold" style="text-indent: 50px;border:none;">'.$j.'. '.$roww['name'].'</td>';
                                            $str .= '</tr>';
                    
                                        } else if($j%2 == 1) {
                    
                                            $str .= '<tr>';
                                            $str .= '<td class="uk-text-bold" style="text-indent: 50px;border:none;">'.$j.'. '.$roww['name'].'</td>';
                    
                                        }
                                        $j++;
                                    }
                                }
                            }
                            if($total_student > 0) {

                                echo '<tr class="uk-text-bold">';
                                echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">'.$sub.' ('.$total_student.')</td>';
                                echo '<td />.';
                                echo '</tr>';
                                echo $str;

                            } else {

                                echo '<tr class="uk-text-bold">';
                                echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">'.$sub.' (0)</td>';
                                echo '<td />';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<td style="text-indent: 50px;border:none;">No Record Found</td>';
                                echo '</tr>';    
                            }
                        }
                        else 
                        {
                                echo '<tr class="uk-text-bold">';
                                echo '<td style="vertical-align: middle;font-size:16px;color:#086788;">'.$sub.' (0)</td>';
                                echo '<td />';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<td style="text-indent: 50px;border:none;">No Record Found</td>';
                                echo '</tr>';    
                        }
                    }

                ?>
            </tbody>
        </table>
        <!-- Link & Think Series -->
    </div>

    <!-- Print to PDF -->
    <!-- Chuah Note: Put this as the last part to prevent double work, once everything above done, just copy the stuff above and paste, while removing the-->
    <center>
        <a type="button" id="print_to_pdf" target="_blank" onclick="printDiv('print_1')" name="print_to_pdf" class="uk-button uk-button-primary form_btn uk-text-center">Print to PDF</a>
    </center>
</div>

<script>
    function printDiv(print_1) {
        $(".navbar").remove();    
        $(".print_c").hide(); 
        $("#print_to_pdf").hide(); 
        $("#back_button").hide(); 
        $(".sidebar").hide();  
        $(".page_title").remove();  
        $(".myheader").remove(); 
        $(".footer").remove();  
        
        $("#submit").remove(); 
        $("#mydatatable_wrapper").remove();
        window.print();
    }
    window.onafterprint = function() {
        window.location.reload(true);
    };
</script>
