<?php 
 session_start();
 if ($_SESSION["isLogin"]==1) {
 if (($_SESSION["UserType"]=="A") & (hasRightGroupXOR($_SESSION["UserName"], "SalesEdit|SalesView"))) {

$current_date = date('Y-m-d');

include_once("mysql.php");
include_once("admin/functions.php");

$centre_code = $_SESSION['CentreCode'];
$year = $_SESSION['Year'];
if($_GET["month"]){
	$month = $_GET["month"];
}else{
	$month = date('m');
}
$pdf_name = '';
$monthyear = date('Y').'-'.$month;

include_once("admin/declaration_func.php");

 $submited_date = date("d/m/Y");
 if($mode=="EDIT"){
    $sha_id=$_GET["id"];
    $sql="SELECT * from `declaration` where sha1(id)='$sha_id'";
    $result=mysqli_query($connection, $sql);
    //$num_row = mysqli_num_rows($result);
    //if ($num_row>0) {
    $row_edit=mysqli_fetch_assoc($result);
    $master_id=$row_edit["id"];
    //$centre_code = $row_edit["centre_code"];
    $year = $row_edit["year"];
    $month = $row_edit["month"];
    $submited_date = $row_edit["submited_date"];
    $timestamp = strtotime($submited_date);
    $new_date_format = date('Y-m-d', $timestamp);
    $submited_date = convertDate2British($new_date_format);

    $pdf_name = (!empty($row_edit['declaration_pdf'])) ? $row_edit['declaration_pdf'] : '';
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
      $sql = "SELECT * from centre where centre_code='$centre_code' ";
      $result = mysqli_query($connection, $sql); 
        while ($row = mysqli_fetch_assoc($result)) {
            $operator_name = $row["operator_name"];
            $address1 = $row["address1"];
            $created_date = $row["created_date"];
            $today_date = date("Y-m-d h:i");
        }
    if($row_edit["id"] == ''){
		$sha_id = $_SESSION["temp_id"] ;
	}else{
		$sha_id = $row_edit['id'];
	}
   ?>

<style>
.page_title {
    position: absolute;
    right: 34px;
}

/*--.uk-margin-right {
    margin-top: 40px;
}--*/
.form_1 {
    padding: 9px 15px;
    border-radius: 10px;
    box-shadow: 0px 2px 3px 0px #00000021 !important;
    font-size: 1.1rem;
    font-weight: bold;
    background: #fff;
    cursor: pointer;
}

.form_2 {
    padding: 9px 15px;
    border-radius: 10px;
    box-shadow: 0px 2px 3px 0px #00000021 !important;
    font-size: 1.1rem;
    font-weight: bold;
    background: #fff;
    margin-right: 82%;
    cursor: pointer;
}

.mobile_app_fee {
    display:none;
}

.studentLink {
    cursor:pointer;
    color:#3399ff;
}
</style>

<?php 
    if(date('d') <= 5 && $_GET['mode'] != 'EDIT') {
        $prev_month = (date('m') == 1) ? 12 : (date('m') - 1);
        $declaration_data = mysqli_fetch_array(mysqli_query($connection,"SELECT `declaration_pdf` FROM `declaration` WHERE `centre_code` = '".$_SESSION['CentreCode']."' AND `month` = '".$prev_month."' AND `year` = '".$_SESSION['Year']."' ORDER BY `id` DESC"));

        $pdf_name = (!empty($declaration_data['declaration_pdf'])) ? $declaration_data['declaration_pdf'] : '';
    } 

    $half_year_array = array();
    $half_year_array[0] = 3;
    $half_year_array[1] = 9;

    $half_year_array = implode(", ", $half_year_array);

    $term_array = array();

    $term_start_date = mysqli_query($connection,"SELECT MIN(`term_start`) AS `start_date` FROM `schedule_term` WHERE `year` = '".$_SESSION['Year']."' AND `centre_code` = '".$_SESSION['CentreCode']."' GROUP BY `term_num` ORDER BY `term_num` ASC");

    $j = 0;

    while($term_row = mysqli_fetch_array($term_start_date)) {
        $term_array[$j] = date('m',strtotime($term_row['start_date']));
        $j++;
    }
    
    $term_array = implode(", ", $term_array);
?>

<span>
    <span class="page_title"><img src="/images/title_Icons/Visitor Reg.png">DECLARATION OF Q-DEES AND PAYMENTS
        REPORT</span>
</span><br>
<div style="<?php if($pdf_name != '') { echo 'display:none;'; } ?>" >
    <button style="margin-left: 30px;margin-bottom: 13px; position: relative; bottom: 40px;  " id="btnPrint" onclick="printDiv('print_1')"
        class="uk-button">Print</button>
</div>

<?php if($pdf_name != '') { ?>

    <div class="uk-margin-right" style="height:1000px;width:100%;padding-bottom:25px;" >
        <iframe src="admin/declaration_pdf/<?php echo $pdf_name; ?>" height="100%" width="100%"></iframe>
    </div>

<?php } ?>

<div id="print_1" class="uk-margin-right">

    <div class="uk-width-1-1 myheader" style="<?php if($pdf_name != '') { echo 'display:none;'; } ?>">
        <h2 class="uk-text-center myheader-text-color">DECLARATION OF Q-DEES AND PAYMENTS REPORT</h2>
    </div>
    <div class="uk-form uk-form-small">

        <form name="frmdeclaration" id="frmdeclaration_id" method="post"
            action="index.php?p=declaration&amp;id=<?php if($mode=="EDIT"){echo $sha1_id;}?>&mode=<?php if($mode=="EDIT"){echo $mode;}else{ echo "SAVE"; }?>"
            enctype="multipart/form-data">
            <div id="frmdeclaration" >
                <div class="uk-grid" style="<?php if($pdf_name != '') { echo 'display:none;'; } ?>">
                    <div class="uk-width-medium-4-10">
                        
                        <table class="uk-table">
                            <tbody>
                                <tr>
                                    <td class="uk-text-bold">Centre Name:</td>
                                    <td><?php echo getCentreName($centre_code); ?></td>

                                </tr>
                                <tr>
                                    <td class="uk-text-bold">Centre Address:</td>
                                    <td><?php echo $address1; ?></td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="uk-text-bold">Form: 1</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="uk-width-medium-4-10">
                        <table class="uk-table">
                       
                            <tr>
                                <td class="uk-text-bold">Month/Year:</td>
                                <td><?php echo date("F", mktime(0, 0, 0, $month, 10)); //date("F");?>/<?php echo $year; //date("Y");?></td>
                            </tr>
                            <tr>
                                <td class="uk-text-bold">Date of Submission:</td>
                                <td> <?php echo $submited_date; //date("d/m/Y");?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div style="padding-left: 0px; padding-right: 0px;" class="uk-width-medium-2-10">
                        <img src="images/Qdees-logo-n.png" alt="" class="img_logo" style="width: 80%;">
                    </div>
                </div>
                <div class="uk-grid" >
                    <div class="uk-width-medium-10-10" style="<?php if($pdf_name != '') { echo 'display:none;'; } ?>">
                        <table class="uk-table uk-table-small">
                            <tr>
                                <td colspan="5" style="font-size:18px; font-weight:bold;">CALCULATION OF GROSS TURNOVER
                                </td>
                            </tr>
                            <!-- a start -->
                            <tr>
                                <td class="uk-width-6-10" style="font-size:16px; font-weight:bold; border:none;">A:
                                    School Fees <br>Foundation Programme</td>
                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Active student</span>
                                </td>

                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Centre Adjusted Fee<br class="bru">RM</span>
                                </td>
                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Total <br class="bru">RM</span>
                                </td>
                            </tr>
                            
                            <!--i EDP start-->

                            <tr class="">
                                <td style=" margin-top:50px;border:none; font-size:18px;"
                                    class="uk-width-6-10 uk-text-bold">(i) School Fees</td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">EDP:</td>
                            </tr>
                            <?php
                            if($mode=="EDIT"){
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as school_adjust, amount
                            from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='School Fees' and subject='EDP'";
                            }else{
                                $sql="SELECT fees_structure, ROUND(case 
                            when school_collection = 'Termly' then  school_adjust/3 
                            when school_collection = 'Half Year' then  school_adjust/6 
                            when school_collection = 'Annually' then  school_adjust/12
                            else school_adjust end, 2) as school_adjust
                            from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
                            }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_EDP_student = 0;
                            $total_EDP_school_adjust=0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php 
                                if($mode=="EDIT"){
                                    $level_count = $row['active_student'];
                                    $total_EDP_school_adjust += $level_count * $row['school_adjust'];
                                    $total_EDP_student += $level_count;
                                }else{
                                    //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.school_adjust !='' ";
                                    
                                    $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' ";

                                    $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
                                    // $sql .= " and case when f.school_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                    // when f.school_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                    // when f.school_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                    // when f.school_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (1) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                    // end ";

                                    $sql .= "  group by ps.student_entry_level, s.id";
                                    //"and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m')";
                                    $resultt=mysqli_query($connection, $sql);
                                  
                                    $student_id_array = array();

                                    $level_count=mysqli_num_rows($resultt);
                                    //$level_count = 0;
                                    while ($roww=mysqli_fetch_array($resultt)) {

                                        $student_id_array[] = $roww['id'];

                                        //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                    }

                                    $student_id_array = implode(', ', $student_id_array);

                                    $total_EDP_school_adjust += $level_count * $row['school_adjust'];
                                    $total_EDP_student += $level_count;
                                }
                                ?>

                                <td style=" margin-top:50px;border:none;"  class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>
                                </td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="School Fees" />
                                <input type="hidden" id="subject" name="subject[]" value="EDP" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center; white-space:nowrap;" class="uk-width-1-10">

                                    <input class="edp_tsd edp_tsdt" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none; white-space:nowrap" class="uk-width-1-10">
                                    <input class="edp_tsd edp_tsdt" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['school_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['school_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php 
							}
								}
						?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10"><input class="total_s" type="number" step="0.01"
                                        name="school_total_f" id="school_total_f"
                                        value="<?php echo $total_EDP_student;  ?>" readonly></td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_EDP_school_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>
                            <!--i EDP start-->

                            <!--i QF1 start-->
                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF1:</td>
                            </tr>
                            <?php
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as school_adjust, amount from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='School Fees' and subject='QF1'";
                        }else{
							$sql="SELECT fees_structure, ROUND(case 
                            when school_collection = 'Termly' then  school_adjust/3 
                            when school_collection = 'Half Year' then  school_adjust/6 
                            when school_collection = 'Annually' then  school_adjust/12
                            else school_adjust end, 2) as school_adjust
                            from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
                        }
						
							$result1=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result1);
                            $total_QF1_school_adjust = 0;
                            $total_QF1_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result1)) {
						  ?>
                            <tr class="">
                                
                                    <?php 
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF1_school_adjust +=  $row['amount'];
                                $total_QF1_student += $level_count;
                            }else{
                                //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.school_adjust !=''  ";

                                $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' ";

                                $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
                                // $sql .= " and case when f.school_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                // when f.school_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.school_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.school_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (1) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // end ";

                                $sql .= "   group by ps.student_entry_level, s.id";

                                $resultt=mysqli_query($connection, $sql);

                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);
                                
                                $total_QF1_school_adjust += $level_count * $row['school_adjust'];
                                $total_QF1_student += $level_count;
                            }
                                ?>

                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>    
                                </td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="School Fees" />
                                <input type="hidden" id="subject" name="subject[]" value="QF1" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">

                                    <input class="edp_tsd edp_tsdq1" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none; white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd edp_tsdq1" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['school_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['school_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php 
								}
							}
						?>

                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10"><input class="total_s" type="number" step="0.01"
                                        name="school_total_f" id="school_total_f"
                                        value="<?php echo $total_QF1_student;  ?>" readonly></td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF1_school_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>
                            <!--i QF1 end-->
                            <!--i QF2 start-->
                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF2:</td>
                            </tr>
                            <?php
                         if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as school_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='School Fees' and subject='QF2'";
                        }else{
							$sql="SELECT fees_structure, ROUND(case 
                            when school_collection = 'Termly' then  school_adjust/3 
                            when school_collection = 'Half Year' then  school_adjust/6 
                            when school_collection = 'Annually' then  school_adjust/12
                            else school_adjust end, 2) as school_adjust
                            from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
                        }
						
							$result2=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result2);
                            $total_QF2_school_adjust = 0;
                            $total_QF2_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result2)) {
						  ?>
                                    <tr class="">
                                        
                                    <?php 
                                        if($mode=="EDIT"){
                                            $level_count = $row['active_student'];
                                            $total_QF2_school_adjust +=  $row['amount'];
                                            $total_QF2_student += $level_count;
                                        }else{
                                            // $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.school_adjust !=''  ";

                                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."'  ";

                                            $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
											
                                            // $sql .= " and case when f.school_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                            // when f.school_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                            // when f.school_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                            // when f.school_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (1) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                            // end ";

                                            $sql .= "   group by ps.student_entry_level, s.id";
                                            
                                            $resultt=mysqli_query($connection, $sql);
                                            
											echo $sql; 
											
                                            $student_id_array = array();

                                            $level_count=mysqli_num_rows($resultt);
                                            //$level_count = 0;
                                            while ($roww=mysqli_fetch_array($resultt)) {

                                                $student_id_array[] = $roww['id'];

                                                //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                            }

                                            $student_id_array = implode(', ', $student_id_array);
                                            
                                            $total_QF2_school_adjust += $level_count * $row['school_adjust'];
                                            $total_QF2_student += $level_count;
                                        }
                                ?>
                                        <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                            <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>
                                        </td>
                                        <input type="hidden" id="form" name="form[]" value="Form1" />
                                        <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                            value="School Fees" />
                                        <input type="hidden" id="subject" name="subject[]" value="QF2" />
                                        <input type="hidden" id="programme_package" name="programme_package[]"
                                            value="<?php echo $row['fees_structure']?>" />
                                        <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">

                                            <input class="edp_tsd edp_tsdq2" type="number" step="0.01" name="active_student[]" id="active_student" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                                        </td>
                                        <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                            <input class="edp_tsd edp_tsdq2" type="number" name="fee_rate[]" id="fee_rate"
                                                value="<?php echo $row['school_adjust']?>" readonly> <span class="edp_eq2">=
                                            </span>
                                        </td>
                                        <td style="border:none; text-align:center;" class="uk-width-1-10">
                                            <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                                value="<?php echo number_format((float)$level_count * $row['school_adjust'], 2, '.', '');  ?>" readonly><br>
                                        </td>
                                    </tr>
                            <?php } }?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10"><input class="total_s" type="number" step="0.01"
                                        name="school_total_f" id="school_total_f"
                                        value="<?php echo $total_QF2_student;  ?>" readonly></td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF2_school_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>
                            <!--i QF2 end-->



                            <!--i QF3 start-->
                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF3:</td>
                            </tr>
                            <?php
                         if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as school_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='School Fees' and subject='QF3'";
                        }else{
							$sql="SELECT fees_structure, ROUND(case 
                            when school_collection = 'Termly' then  school_adjust/3 
                            when school_collection = 'Half Year' then  school_adjust/6 
                            when school_collection = 'Annually' then  school_adjust/12
                            else school_adjust end, 2) as school_adjust 
                            from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
                        }
				
							$result3=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result3);
                            $total_QF3_school_adjust = 0;
                            $total_QF3_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result3)) {
						  ?>
                            <tr class="">
                                
                                    <?php 
                             if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF3_school_adjust +=  $row['amount'];
                                $total_QF3_student += $level_count;
                            }else{
                                //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.school_adjust !=''  ";

                                $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."'  ";


                                $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
                                
                                // $sql .= " and case when f.school_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                // when f.school_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.school_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.school_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (1) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                        
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_QF3_school_adjust += $level_count * $row['school_adjust'];
                                $total_QF3_student += $level_count;
                            }
                                ?>
                                    <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                        <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>    
                                    </td>
                                    <input type="hidden" id="form" name="form[]" value="Form1" />
                                    <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                        value="School Fees" />
                                    <input type="hidden" id="subject" name="subject[]" value="QF3" />
                                    <input type="hidden" id="programme_package" name="programme_package[]"
                                        value="<?php echo $row['fees_structure']?>" />
                                    <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">

                                    <input class="edp_tsd edp_tsdq3" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd edp_tsdq3" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['school_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['school_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php }}?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF3_student;  ?>" readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF3_school_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>
                            <!--i QF3 end-->

                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student (i)</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php echo $total_student_school += $total_EDP_student+$total_QF1_student+$total_QF2_student+$total_QF3_student; $total_student_a += $total_student_school; ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total (i)" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php  $total_school_adjust = $total_EDP_school_adjust + $total_QF1_school_adjust  + $total_QF2_school_adjust + $total_QF3_school_adjust; 
                                        echo number_format((float)$total_school_adjust, 2, '.', ''); ?>"
                                        readonly>
                                </td>
                            </tr>
                            <?php $total_a += $total_school_adjust; ?>

                            <!-- multimedia start -->
                            <!-- multimedia edp start -->
                            <tr class="">
                                <td style=" margin-top:50px;border:none; font-size:18px;"
                                    class="uk-width-6-10 uk-text-bold">(ii) Multimedia Fees</td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">EDP:</td>
                            </tr>
                            <?php
                         if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as multimedia_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Multimedia' and subject='EDP'";
                        }else{
							$sql="SELECT fees_structure, ROUND(case 
                            when multimedia_collection = 'Termly' then  multimedia_adjust/3 
                            when multimedia_collection = 'Half Year' then  multimedia_adjust/6 
                            when multimedia_collection = 'Annually' then  multimedia_adjust/12
                            else multimedia_adjust end, 2) as multimedia_adjust 
                            from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
                        }
					
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
                            $total_EDP_multimedia_adjust = 0;
                            $total_EDP_multimedia_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                             if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_EDP_multimedia_adjust +=  $row['amount'];
                                $total_EDP_multimedia_student += $level_count;
                            }else{
                           // $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.multimedia_adjust !=''  ";

                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."'  ";

                            $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";

                                // $sql .= " and case when f.multimedia_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                // when f.multimedia_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.multimedia_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.multimedia_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (1) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                              
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;

                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_EDP_multimedia_adjust += $level_count * $row['multimedia_adjust'];
                                $total_EDP_multimedia_student += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>    
                                </td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Multimedia" />
                                <input type="hidden" id="subject" name="subject[]" value="EDP" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['multimedia_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['multimedia_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php
							}
							}
						?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_EDP_multimedia_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_EDP_multimedia_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>
                            <!-- multimedia edp end -->
                            <!-- multimedia QF1 start -->

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF1:</td>
                            </tr>
                            <?php
                         if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as multimedia_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Multimedia' and subject='QF1'";
                        }else{
							$sql="SELECT fees_structure, ROUND(case 
                            when multimedia_collection = 'Termly' then  multimedia_adjust/3 
                            when multimedia_collection = 'Half Year' then  multimedia_adjust/6 
                            when multimedia_collection = 'Annually' then  multimedia_adjust/12
                            else multimedia_adjust end, 2) as multimedia_adjust 
                             from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
                            $total_QF1_multimedia_adjust = 0;
                            $total_QF1_multimedia_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                             if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF1_multimedia_adjust +=  $row['amount'];
                                $total_QF1_multimedia_student += $level_count;
                            }else{
                            // $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.multimedia_adjust !=''  ";
                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' ";

                            $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
                                // $sql .= " and case when f.multimedia_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                // when f.multimedia_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.multimedia_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.multimedia_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (1) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_QF1_multimedia_adjust += $level_count * $row['multimedia_adjust'];
                                $total_QF1_multimedia_student += $level_count;
                            }
                                ?>
                                    <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                        <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>     
                                    </td>
                                    <input type="hidden" id="form" name="form[]" value="Form1" />
                                    <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                        value="Multimedia" />
                                    <input type="hidden" id="subject" name="subject[]" value="QF1" />
                                    <input type="hidden" id="programme_package" name="programme_package[]"
                                        value="<?php echo $row['fees_structure']?>" />
                                    <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">

                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['multimedia_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['multimedia_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php
							}
							}
						?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF1_multimedia_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF1_multimedia_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>
                            <!-- multimedia qf1 end -->
                            <!-- multimedia QF2 start -->

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF2:</td>
                            </tr>
                            <?php
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as multimedia_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Multimedia' and subject='QF2'";
                        }else{
							$sql="SELECT fees_structure, ROUND(case 
                            when multimedia_collection = 'Termly' then  multimedia_adjust/3 
                            when multimedia_collection = 'Half Year' then  multimedia_adjust/6 
                            when multimedia_collection = 'Annually' then  multimedia_adjust/12
                            else multimedia_adjust end, 2) as multimedia_adjust 
                             from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
                            $total_QF2_multimedia_adjust = 0; 
                            $total_QF2_multimedia_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF2_multimedia_adjust +=  $row['amount'];
                                $total_QF2_multimedia_student += $level_count;
                            }else{
                            //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.multimedia_adjust !=''  ";
                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' ";


                             $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
                            //     $sql .= " and case when f.multimedia_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                            //     when f.multimedia_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                            //     when f.multimedia_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                            //     when f.multimedia_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (1) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                            //     end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_QF2_multimedia_adjust += $level_count * $row['multimedia_adjust'];
                                $total_QF2_multimedia_student += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>    
                                </td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Multimedia" />
                                <input type="hidden" id="subject" name="subject[]" value="QF2" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['multimedia_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['multimedia_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php
							}
							}
						?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF2_multimedia_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF2_multimedia_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>
                            <!-- multimedia QF2 end -->
                            <!-- multimedia QF3 start -->
                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF3:</td>
                            </tr>
                            <?php
                         if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as multimedia_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Multimedia' and subject='QF3'";
                        }else{
							$sql="SELECT fees_structure, ROUND(case 
                            when multimedia_collection = 'Termly' then  multimedia_adjust/3 
                            when multimedia_collection = 'Half Year' then  multimedia_adjust/6 
                            when multimedia_collection = 'Annually' then  multimedia_adjust/12
                            else multimedia_adjust end, 2) as multimedia_adjust 
                             from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							$total_QF3_multimedia_adjust = 0;
                            $total_QF3_multimedia_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF3_multimedia_adjust +=  $row['amount'];
                                $total_QF3_multimedia_student += $level_count;
                            }else{
                            //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.multimedia_adjust !=''  ";

                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' ";


                            $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
                                // $sql .= " and case when f.multimedia_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                // when f.multimedia_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.multimedia_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.multimedia_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (1) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_QF3_multimedia_adjust += $level_count * $row['multimedia_adjust'];
                                $total_QF3_multimedia_student += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>    
                                </td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Multimedia" />
                                <input type="hidden" id="subject" name="subject[]" value="QF3" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['multimedia_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['multimedia_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php
							}
							}
						?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF3_multimedia_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF3_multimedia_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>
                            <!-- multimedia qf3 end -->

                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student (ii)</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_student_multimedia += $total_EDP_multimedia_student + $total_QF1_multimedia_student + $total_QF2_multimedia_student + $total_QF3_multimedia_student;  
                                $total_student_a += $total_student_multimedia;?>" readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total (ii)" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php  $total_multimedia_adjust = $total_EDP_multimedia_adjust + $total_QF1_multimedia_adjust  + $total_QF2_multimedia_adjust + $total_QF3_multimedia_adjust; 
                                        echo number_format((float)$total_multimedia_adjust, 2, '.', '') ?>"
                                        readonly>
                                </td>
                                <?php $total_a += $total_multimedia_adjust; ?>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;"></td>
                            </tr>
                            <!-- multimedia end -->

                            <!-- afternoon start -->
                            <tr class="">
                                <td style=" margin-top:50px;border:none; font-size:18px;"
                                    class="uk-width-6-10 uk-text-bold">(iii) Afternoon Programme Fees</td>
                            </tr>
                            <!-- Afternoon edp start -->

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">EDP:</td>
                            </tr>
                            <?php
                         if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as basic_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Afternoon Programme Fees' and subject='EDP'";
                        }else{
							$sql="SELECT fees_structure, ROUND(case 
                            when basic_collection = 'Termly' then  basic_adjust/3 
                            when basic_collection = 'Half Year' then  basic_adjust/6 
                            when basic_collection = 'Annually' then  basic_adjust/12
                            else basic_adjust end, 2) as basic_adjust, ROUND(case 
                            when afternoon_robotic_collection = 'Termly' then  afternoon_robotic_adjust/3 
                            when afternoon_robotic_collection = 'Half Year' then  afternoon_robotic_adjust/6 
                            when afternoon_robotic_collection = 'Annually' then  afternoon_robotic_adjust/12
                            else afternoon_robotic_adjust end, 2) as afternoon_robotic_adjust 
                             from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
                            $total_EDP_basic_adjust = 0;
                            $total_EDP_basic_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                    <?php
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_EDP_basic_adjust +=  $row['amount'];
                                $total_EDP_basic_student += $level_count;
                            }else{
                            //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and fl.afternoon_programme =1 and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.basic_adjust !=''  ";

                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and fl.afternoon_programme =1 and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."'  ";

                            $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
                                // $sql .= " and case when f.basic_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                // when f.basic_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.basic_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.basic_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (1) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                if($row['basic_adjust'] < 1 && $row['afternoon_robotic_adjust'] > 0) {
                                    $row['basic_adjust'] = $row['afternoon_robotic_adjust'];
                                }

                                $total_EDP_basic_adjust += $level_count * $row['basic_adjust'];
                                $total_EDP_basic_student += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span> 
                                </td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Afternoon Programme Fees" />
                                <input type="hidden" id="subject" name="subject[]" value="EDP" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['basic_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['basic_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php
							}
							}
						?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_EDP_basic_student;  ?>" readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_EDP_basic_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>

                            <!-- Afternoon edp end -->
                            <!-- Afternoon QF1 start -->

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF1:</td>
                            </tr>
                            <?php
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as basic_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Afternoon Programme Fees' and subject='QF1'";
                        }else{
							$sql="SELECT fees_structure, ROUND(case 
                            when basic_collection = 'Termly' then  basic_adjust/3 
                            when basic_collection = 'Half Year' then  basic_adjust/6 
                            when basic_collection = 'Annually' then  basic_adjust/12
                            else basic_adjust end, 2) as basic_adjust, ROUND(case 
                            when afternoon_robotic_collection = 'Termly' then  afternoon_robotic_adjust/3 
                            when afternoon_robotic_collection = 'Half Year' then  afternoon_robotic_adjust/6 
                            when afternoon_robotic_collection = 'Annually' then  afternoon_robotic_adjust/12
                            else afternoon_robotic_adjust end, 2) as afternoon_robotic_adjust 
                             from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							$total_QF1_basic_adjust = 0;
                            $total_QF1_basic_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                             if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF1_basic_adjust +=  $row['amount'];
                                $total_QF1_basic_student += $level_count;
                            }else{
                            //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and fl.afternoon_programme =1 and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.basic_adjust !=''  ";

                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and fl.afternoon_programme =1 and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' ";

                            $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
                                // $sql .= " and case when f.basic_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                // when f.basic_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.basic_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.basic_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (1) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);

                                if($row['basic_adjust'] < 1 && $row['afternoon_robotic_adjust'] > 0) {
                                    $row['basic_adjust'] = $row['afternoon_robotic_adjust'];
                                }

                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_QF1_basic_adjust += $level_count * $row['basic_adjust'];
                                $total_QF1_basic_student += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>    
                                </td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Afternoon Programme Fees" />
                                <input type="hidden" id="subject" name="subject[]" value="QF1" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['basic_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['basic_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php
							}
							}
						?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF1_basic_student;  ?>" readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF1_basic_adjust, 2, '.', '') ;  ?>" readonly>
                                </td>
                            </tr>
                            <!-- Afternoon QF1 end -->
                            <!-- Afternoon QF2 start -->

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF2:</td>
                            </tr>
                            <?php
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as basic_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Afternoon Programme Fees' and subject='QF2'";
                        }else{
							$sql="SELECT fees_structure, ROUND(case 
                            when basic_collection = 'Termly' then  basic_adjust/3 
                            when basic_collection = 'Half Year' then  basic_adjust/6 
                            when basic_collection = 'Annually' then  basic_adjust/12
                            else basic_adjust end, 2) as basic_adjust, ROUND(case 
                            when afternoon_robotic_collection = 'Termly' then  afternoon_robotic_adjust/3 
                            when afternoon_robotic_collection = 'Half Year' then  afternoon_robotic_adjust/6 
                            when afternoon_robotic_collection = 'Annually' then  afternoon_robotic_adjust/12
                            else afternoon_robotic_adjust end, 2) as afternoon_robotic_adjust 
                             from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							$total_QF2_basic_adjust = 0;
                            $total_QF2_basic_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF2_basic_adjust +=  $row['amount'];
                                $total_QF2_basic_student += $level_count;
                            }else{
                            //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and fl.afternoon_programme =1 and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.basic_adjust !=''  ";

                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and fl.afternoon_programme =1 and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' ";


                            $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
                                // $sql .= " and case when f.basic_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                // when f.basic_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.basic_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.basic_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (1) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                if($row['basic_adjust'] < 1 && $row['afternoon_robotic_adjust'] > 0) {
                                    $row['basic_adjust'] = $row['afternoon_robotic_adjust'];
                                }

                                $total_QF2_basic_adjust += $level_count * $row['basic_adjust'];
                                $total_QF2_basic_student += $level_count;
                            }
                                ?>

                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span> 
                                </td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Afternoon Programme Fees" />
                                <input type="hidden" id="subject" name="subject[]" value="QF2" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">

                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['basic_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['basic_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php
							}
							}
						?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF2_basic_student;  ?>" readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF2_basic_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>
                            <!-- Afternoon QF2 end -->
                            <!-- Afternoon QF3 start -->

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF3:</td>
                            </tr>
                            <?php
                         if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as basic_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Afternoon Programme Fees' and subject='QF3'";
                        }else{
							$sql="SELECT fees_structure, ROUND(case 
                            when basic_collection = 'Termly' then  basic_adjust/3 
                            when basic_collection = 'Half Year' then  basic_adjust/6 
                            when basic_collection = 'Annually' then  basic_adjust/12
                            else basic_adjust end, 2) as basic_adjust, ROUND(case 
                            when afternoon_robotic_collection = 'Termly' then  afternoon_robotic_adjust/3 
                            when afternoon_robotic_collection = 'Half Year' then  afternoon_robotic_adjust/6 
                            when afternoon_robotic_collection = 'Annually' then  afternoon_robotic_adjust/12
                            else afternoon_robotic_adjust end, 2) as afternoon_robotic_adjust 
                             from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							$total_QF3_basic_adjust = 0;
                            $total_QF3_basic_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF3_basic_adjust +=  $row['amount'];
                                $total_QF3_basic_student += $level_count;
                            }else{
                            //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and fl.afternoon_programme =1 and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.basic_adjust !=''  ";

                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and fl.afternoon_programme =1 and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' ";


                            $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
                                // $sql .= " and case when f.basic_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                // when f.basic_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.basic_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.basic_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (1) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);

                                if($row['basic_adjust'] < 1 && $row['afternoon_robotic_adjust'] > 0) {
                                    $row['basic_adjust'] = $row['afternoon_robotic_adjust'];
                                }

                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_QF3_basic_adjust += $level_count * $row['basic_adjust'];
                                $total_QF3_basic_student += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>    
                                </td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Afternoon Programme Fees" />
                                <input type="hidden" id="subject" name="subject[]" value="QF3" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['basic_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['basic_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php
							}
							}
						?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF3_basic_student;  ?>" readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF3_basic_adjust, 2, '.', '') ;  ?>" readonly>
                                </td>
                            </tr>
                            <!-- Afternoon QF3 end -->

                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student (iii)</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_student_basic += $total_EDP_basic_student + $total_QF1_basic_student+ $total_QF2_basic_student+ $total_QF3_basic_student;  
                                $total_student_a += $total_student_basic;?>" readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total (iii)" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php  $total_basic_adjust = $total_EDP_basic_adjust + $total_QF1_basic_adjust  + $total_QF2_basic_adjust + $total_QF3_basic_adjust;
                                        echo number_format((float)$total_basic_adjust, 2, '.', '')  ?>"
                                        readonly>
                                    <?php $total_a += $total_basic_adjust; ?>
                                </td>
                            </tr>
                            <!-- afternoon end -->
                            <!-- Mobile App start -->
                            <tr class="mobile_app_fee">
                                <td style=" margin-top:50px;border:none; font-size:18px;"
                                    class="uk-width-6-10 uk-text-bold">(iv) Mobile App Fees</td>
                            </tr>
                            <!-- Mobile App edp start -->

                            <tr class="mobile_app_fee" >
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">EDP:</td>
                            </tr>
                            <?php
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as mobile_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Mobile App Fees' and subject='EDP'";
                        }else{
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
                            $total_EDP_mobile_adjust = 0;
                            $total_EDP_mobile_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="mobile_app_fee">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Mobile App Fees" />
                                <input type="hidden" id="subject" name="subject[]" value="EDP" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                             if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_EDP_mobile_adjust +=  $row['amount'];
                                $total_EDP_mobile_student += $level_count;
                            }else{
                            //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.mobile_adjust !=''  ";

                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."'  ";

                                $sql .= " and case when f.mobile_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                when f.mobile_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.mobile_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.mobile_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);
                                $total_EDP_mobile_adjust += $level_count * $row['mobile_adjust'];
                                $total_EDP_mobile_student += $level_count;
                            }
                                ?>
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['mobile_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['mobile_adjust'], 2, '.', '') ;  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="mobile_app_fee">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_EDP_mobile_student;  ?>" readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_EDP_mobile_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>
                            <!-- Mobile App edp end -->
                            <!-- Mobile App QF1 start -->

                            <tr class="mobile_app_fee">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF1:</td>
                            </tr>
                            <?php
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as mobile_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Mobile App Fees' and subject='QF1'";
                        }else{
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							$total_QF1_mobile_adjust = 0;
                            $total_QF1_mobile_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="mobile_app_fee">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Mobile App Fees" />
                                <input type="hidden" id="subject" name="subject[]" value="QF1" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                             if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF1_mobile_adjust +=  $row['amount'];
                                $total_QF1_mobile_student += $level_count;
                            }else{
                            //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.mobile_adjust !=''  ";

                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' ";


                                $sql .= " and case when f.mobile_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                when f.mobile_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.mobile_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.mobile_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_QF1_mobile_adjust += $level_count * $row['mobile_adjust'];
                                $total_QF1_mobile_student += $level_count;
                            }
                                ?>
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['mobile_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['mobile_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="mobile_app_fee">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF1_mobile_student;  ?>" readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF1_mobile_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>
                            <!-- Mobile App QF1 end -->
                            <!-- Mobile App QF2 start -->

                            <tr class="mobile_app_fee">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF2:</td>
                            </tr>
                            <?php
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as mobile_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Mobile App Fees' and subject='QF2'";
                        }else{
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							$total_QF2_mobile_adjust = 0;
                            $total_QF2_mobile_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="mobile_app_fee">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Mobile App Fees" />
                                <input type="hidden" id="subject" name="subject[]" value="QF2" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                             if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF2_mobile_adjust +=  $row['amount'];
                                $total_QF2_mobile_student += $level_count;
                            }else{
                            //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.mobile_adjust !=''  ";

                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' ";

                                $sql .= " and case when f.mobile_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                when f.mobile_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.mobile_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.mobile_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);
                                $total_QF2_mobile_adjust += $level_count * $row['mobile_adjust'];
                                $total_QF2_mobile_student += $level_count;
                            }
                                ?>
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['mobile_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['mobile_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="mobile_app_fee">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF2_mobile_student;  ?>" readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF2_mobile_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>
                            <!-- Mobile App QF2 end -->
                            <!-- Mobile App QF3 start -->

                            <tr class="mobile_app_fee">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF3:</td>
                            </tr>
                            <?php
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as mobile_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Mobile App Fees' and subject='QF3'";
                        }else{
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							$total_QF3_mobile_adjust = 0;
                            $total_QF3_mobile_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="mobile_app_fee">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Mobile App Fees" />
                                <input type="hidden" id="subject" name="subject[]" value="QF3" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                             if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF3_mobile_adjust +=  $row['amount'];
                                $total_QF3_mobile_student += $level_count;
                            }else{
                            //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.mobile_adjust !=''  ";

                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' ";


                                $sql .= " and case when f.mobile_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                when f.mobile_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.mobile_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.mobile_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_QF3_mobile_adjust += $level_count * $row['mobile_adjust'];
                                $total_QF3_mobile_student += $level_count;
                            }
                                ?>
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['mobile_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['mobile_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="mobile_app_fee">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF3_mobile_student;  ?>" readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF3_mobile_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>
                            <!-- Mobile App QF3 end -->

                            <tr class="mobile_app_fee">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student (iv)</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php echo $total_student_mobile += $total_EDP_mobile_student + $total_QF1_mobile_student + $total_QF2_mobile_student + $total_QF3_mobile_student; //$total_student_a += $total_student_mobile; ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total (iv)" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php  $total_mobile_adjust = $total_EDP_mobile_adjust + $total_QF1_mobile_adjust  + $total_QF2_mobile_adjust + $total_QF3_mobile_adjust; 
                                        echo number_format((float)$total_mobile_adjust, 2, '.', ''); ?>"
                                        readonly>
                                    <?php $total_mobile_adjust = 0; //$total_a += $total_mobile_adjust; ?>
                                </td>
                            </tr>
                            <!-- Mobile App end -->


                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total A</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_student_a;  ?>" readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total A" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_a, 2, '.', ''); ?>" readonly>
                                </td>
                            </tr>
                            <!--full day end-->
                            <!-- end school fees A  -->
                            <!-- a end -->


                            <!-- B: Material Fees start -->
                            <tr class="">
                                <td class="uk-width-6-10" style="font-size:16px; font-weight:bold; border:none;">B:
                                    Materials
                                    Fees</td>
                            </tr>
                            <!--b 1  start -->
                            <tr class="">
                                <td style=" margin-top:50px;border:none; font-size:18px;"
                                    class="uk-width-6-10 uk-text-bold">(i) Q-dess Foundation Materials</td>
                            </tr>
                            <!--  b 1 edp start -->

                            <tr class="">
                                <td style=" margin-top:200px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">EDP</td>
                            </tr>
                            <?php
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as integrated_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Q-dess Foundation Materials' and subject='EDP'";
                        }else{
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
                            $total_EDP_integrated_adjust = 0;
                            $total_EDP_integrated_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                             if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_EDP_integrated_adjust +=  $row['amount'];
                                $total_EDP_integrated_student += $level_count;
                            }else{
                            //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.integrated_adjust !=''  ";

                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' ";

                                $sql .= " and case when f.integrated_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                when f.integrated_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.integrated_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.integrated_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $row['integrated_adjust'] = 195;

                                $total_EDP_integrated_adjust += $level_count * $row['integrated_adjust'];
                                $total_EDP_integrated_student += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>    
                                </td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Q-dess Foundation Materials" />
                                <input type="hidden" id="subject" name="subject[]" value="EDP" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format((float)$row['integrated_adjust'], 2, '.', ''); ?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['integrated_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_EDP_integrated_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_EDP_integrated_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>

                            <!--  b edp end -->
                            <!--  b QF1 start -->

                            <tr class="">
                                <td style=" margin-top:200px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF1</td>
                            </tr>
                            <?php
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as integrated_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Q-dess Foundation Materials' and subject='QF1'";
                        }else{
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF1_integrated_student = 0;
							$total_QF1_integrated_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF1_integrated_adjust +=  $row['amount'];
                                $total_QF1_integrated_student += $level_count;
                            }else{
                            //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.integrated_adjust !=''  ";

                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' ";

                                $sql .= " and case when f.integrated_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                when f.integrated_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.integrated_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.integrated_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $row['integrated_adjust'] = 195;

                                $total_QF1_integrated_adjust += $level_count * $row['integrated_adjust'];
                                $total_QF1_integrated_student += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>     
                                </td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Q-dess Foundation Materials" />
                                <input type="hidden" id="subject" name="subject[]" value="QF1" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format((float)$row['integrated_adjust'], 2, '.', ''); ?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['integrated_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF1_integrated_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF1_integrated_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>
                            <!--  b QF1 end -->
                            <!--  b QF2 start -->

                            <tr class="">
                                <td style=" margin-top:200px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF2</td>
                            </tr>
                            <?php
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as integrated_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Q-dess Foundation Materials' and subject='QF2'";
                        }else{
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF2_integrated_student = 0;
							$total_QF2_integrated_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF2_integrated_adjust +=  $row['amount'];
                                $total_QF2_integrated_student += $level_count;
                            }else{
                            //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.integrated_adjust !=''  ";

                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' ";

                                $sql .= " and case when f.integrated_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                when f.integrated_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.integrated_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.integrated_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $row['integrated_adjust'] = 195;

                                $total_QF2_integrated_adjust += $level_count * $row['integrated_adjust'];
                                $total_QF2_integrated_student += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>    
                                </td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Q-dess Foundation Materials" />
                                <input type="hidden" id="subject" name="subject[]" value="QF2" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format((float)$row['integrated_adjust'], 2, '.', ''); ?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo  number_format((float)$level_count * $row['integrated_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF2_integrated_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF2_integrated_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>
                            <!--  b QF2 end -->
                            <!--  b QF3 start -->

                            <tr class="">
                                <td style=" margin-top:200px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF3</td>
                            </tr>
                            <?php
                         if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as integrated_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Q-dess Foundation Materials' and subject='QF3'";
                        }else{
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF3_integrated_student = 0;
							$total_QF3_integrated_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF3_integrated_adjust +=  $row['amount'];
                                $total_QF3_integrated_student += $level_count;
                            }else{
                            //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.integrated_adjust !=''  ";

                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' ";

                                $sql .= " and case when f.integrated_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                when f.integrated_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.integrated_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.integrated_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $row['integrated_adjust'] = 195;

                                $total_QF3_integrated_adjust += $level_count * $row['integrated_adjust'];
                                $total_QF3_integrated_student += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>      
                                </td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Q-dess Foundation Materials" />
                                <input type="hidden" id="subject" name="subject[]" value="QF3" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format((float)$row['integrated_adjust'], 2, '.', ''); ?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['integrated_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF3_integrated_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF3_integrated_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>
                            </tr>
                            <!--  b QF3 end -->

                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student (i)</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php echo $total_integrated_student = $total_EDP_integrated_student + $total_QF1_integrated_student + $total_QF2_integrated_student + $total_QF3_integrated_student; $total_student_B = $total_integrated_student; ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total (i)" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php  $total_integrated_adjust = $total_EDP_integrated_adjust + $total_QF1_integrated_adjust  + $total_QF2_integrated_adjust + $total_QF3_integrated_adjust; 
                                        echo  number_format((float)$total_integrated_adjust, 2, '.', '')?>"
                                        readonly>
                                    <?php $total_b = $total_integrated_adjust; ?>
                                </td>
                            </tr>
                            <!--  b 1 end -->


                            <!--b 2  start -->
                            <tr class="">
                                <td style=" margin-top:50px;border:none; font-size:18px;"
                                    class="uk-width-6-10 uk-text-bold">(ii) Q-dees Foundation Mandarin Modules Materials
                                </td>
                            </tr>
                            <!--  b 2 edp start -->

                            <tr class="">
                                <td style=" margin-top:200px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">EDP</td>
                            </tr>
                            <?php
                         if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as mandarin_m_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Q-dees Foundation Mandarin Modules Materials' and subject='EDP'";
                        }else{
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_EDP_mandarin_student = 0;
                            $total_EDP_mandarin_m_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_EDP_mandarin_m_adjust +=  $row['amount'];
                                $total_EDP_mandarin_student += $level_count;
                            }else{
                            //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and fl.foundation_mandarin =1 and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.mandarin_m_adjust !=''  ";

                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and fl.foundation_mandarin =1 and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' ";

                                $sql .= " and case when f.mandarin_m_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                when f.mandarin_m_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.mandarin_m_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.mandarin_m_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $row['mandarin_m_adjust'] = 0;

                                $total_EDP_mandarin_m_adjust += $level_count * $row['mandarin_m_adjust'];
                                $total_EDP_mandarin_student += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>      
                                </td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Q-dees Foundation Mandarin Modules Materials" />
                                <input type="hidden" id="subject" name="subject[]" value="EDP" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format($row['mandarin_m_adjust'], 2, '.', ''); ?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['mandarin_m_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_EDP_mandarin_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_EDP_mandarin_m_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>
                            <!--  b 2 edp end -->
                            <!--  b 2 QF1 start -->

                            <tr class="">
                                <td style=" margin-top:200px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF1</td>
                            </tr>
                            <?php
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as mandarin_m_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Q-dees Foundation Mandarin Modules Materials' and subject='QF1'";
                        }else{
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF1_mandarin_student = 0;
							$total_QF1_mandarin_m_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF1_mandarin_m_adjust +=  $row['amount'];
                                $total_QF1_mandarin_student += $level_count;
                            }else{
                            //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and fl.foundation_mandarin =1 and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.mandarin_m_adjust !=''  ";

                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and fl.foundation_mandarin =1 and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' ";

                                $sql .= " and case when f.mandarin_m_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                when f.mandarin_m_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.mandarin_m_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.mandarin_m_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $row['mandarin_m_adjust'] = 0;
                                $total_QF1_mandarin_m_adjust += $level_count * $row['mandarin_m_adjust'];
                                $total_QF1_mandarin_student += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>    
                                </td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Q-dees Foundation Mandarin Modules Materials" />
                                <input type="hidden" id="subject" name="subject[]" value="QF1" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format($row['mandarin_m_adjust'], 2, '.', ''); ?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['mandarin_m_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF1_mandarin_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF1_mandarin_m_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>
                            <!--  b 2 QF1 end -->
                            <!--  b 2 QF2 start -->

                            <tr class="">
                                <td style=" margin-top:200px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF2</td>
                            </tr>
                            <?php
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as mandarin_m_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Q-dees Foundation Mandarin Modules Materials' and subject='QF2'";
                        }else{
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF2_mandarin_student = 0;
							$total_QF2_mandarin_m_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF2_mandarin_m_adjust +=  $row['amount'];
                                $total_QF2_mandarin_student += $level_count;
                            }else{
                            //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and fl.foundation_mandarin =1 and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.mandarin_m_adjust !=''  ";

                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and fl.foundation_mandarin =1 and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' ";

                                $sql .= " and case when f.mandarin_m_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                when f.mandarin_m_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.mandarin_m_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.mandarin_m_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $row['mandarin_m_adjust'] = 55;

                                $total_QF2_mandarin_m_adjust += $level_count * $row['mandarin_m_adjust'];
                                $total_QF2_mandarin_student += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>    
                                </td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Q-dees Foundation Mandarin Modules Materials" />
                                <input type="hidden" id="subject" name="subject[]" value="QF2" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format($row['mandarin_m_adjust'], 2, '.', ''); ?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['mandarin_m_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF2_mandarin_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo  number_format((float)$total_QF2_mandarin_m_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>
                            <!--  b 2 QF2 end -->
                            <!--  b 2 QF3 start -->

                            <tr class="">
                                <td style=" margin-top:200px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF3</td>
                            </tr>
                            <?php
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as mandarin_m_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Q-dees Foundation Mandarin Modules Materials' and subject='QF3'";
                        }else{
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF3_mandarin_student = 0;
							$total_QF3_mandarin_m_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF3_mandarin_m_adjust +=  $row['amount'];
                                $total_QF3_mandarin_student += $level_count;
                            }else{
                            //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and fl.foundation_mandarin =1 and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.mandarin_m_adjust !=''  ";

                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and fl.foundation_mandarin =1 and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' ";

                                $sql .= " and case when f.mandarin_m_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                when f.mandarin_m_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.mandarin_m_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.mandarin_m_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $row['mandarin_m_adjust'] = 55;

                                $total_QF3_mandarin_m_adjust += $level_count * $row['mandarin_m_adjust'];
                                $total_QF3_mandarin_student += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>      
                                </td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Q-dees Foundation Mandarin Modules Materials" />
                                <input type="hidden" id="subject" name="subject[]" value="QF3" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format($row['mandarin_m_adjust'], 2, '.', ''); ?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['mandarin_m_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF3_mandarin_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF3_mandarin_m_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>
                            <!--  b 2 QF3 end -->
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student (ii)</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php echo $total_mandarin_student = $total_EDP_mandarin_student + $total_QF1_mandarin_student + $total_QF2_mandarin_student + $total_QF3_mandarin_student; $total_student_B =+ $total_mandarin_student; ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total (ii)" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php  $total_mandarin_m_adjust = $total_EDP_mandarin_m_adjust + $total_QF1_imandarin_m_adjust  + $total_QF2_mandarin_m_adjust + $total_QF3_mandarin_m_adjust;  
                                        echo number_format((float)$total_mandarin_m_adjust, 2, '.', '')?>"
                                        readonly>
                                    <?php $total_b += $total_mandarin_m_adjust; ?>
                                </td>
                            </tr>
                            <!--  b 2 end -->





                            <?php if($monthyear >= '2024-03') { ?>

                            <!--b 1  start -->
                            <tr class="">
                                <td style=" margin-top:50px;border:none; font-size:18px;"
                                    class="uk-width-6-10 uk-text-bold">(iii) STEM Programme</td>
                            </tr>
                            <!--  b 1 edp start -->

                            <tr class="">
                                <td style=" margin-top:200px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">EDP</td>
                            </tr>
                            <?php
                            if($mode=="EDIT"){
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as stem_programme_adjust, amount
                            from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='STEM Programme' and subject='EDP'";
                            }else{
                                $sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
                            }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);

                            $total_EDP_stem_programme_adjust = 0;
                            $total_EDP_stem_programme_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                             if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_EDP_stem_programme_adjust +=  $row['amount'];
                                $total_EDP_stem_programme_student += $level_count;
                            }else{

                                $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' ";

                                $sql .= " and case when f.stem_programme_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                when f.stem_programme_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.stem_programme_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.stem_programme_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                              
                                while ($roww=mysqli_fetch_array($resultt)) {
                                    $student_id_array[] = $roww['id'];
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_EDP_stem_programme_adjust += $level_count * $row['stem_programme_adjust'];
                                $total_EDP_stem_programme_student += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>    
                                </td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="STEM Programme" />
                                <input type="hidden" id="subject" name="subject[]" value="EDP" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format((float)$row['stem_programme_adjust'], 2, '.', ''); ?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['stem_programme_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_EDP_stem_programme_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_EDP_stem_programme_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>

                            <!--  b edp end -->
                            <!--  b QF1 start -->

                            <tr class="">
                                <td style=" margin-top:200px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF1</td>
                            </tr>
                            <?php
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as stem_programme_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='STEM Programme' and subject='QF1'";
                        }else{
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF1_stem_programme_student = 0;
							$total_QF1_stem_programme_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF1_stem_programme_adjust +=  $row['amount'];
                                $total_QF1_stem_programme_student += $level_count;
                            }else{

                                $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' ";

                                $sql .= " and case when f.stem_programme_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                when f.stem_programme_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.stem_programme_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.stem_programme_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                
                                while ($roww=mysqli_fetch_array($resultt)) {
                                    $student_id_array[] = $roww['id'];
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_QF1_stem_programme_adjust += $level_count * $row['stem_programme_adjust'];
                                $total_QF1_stem_programme_student += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>     
                                </td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="STEM Programme" />
                                <input type="hidden" id="subject" name="subject[]" value="QF1" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format((float)$row['stem_programme_adjust'], 2, '.', ''); ?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['stem_programme_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF1_stem_programme_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF1_stem_programme_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>
                            <!--  b QF1 end -->
                            <!--  b QF2 start -->

                            <tr class="">
                                <td style=" margin-top:200px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF2</td>
                            </tr>
                            <?php
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as stem_programme_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='STEM Programme' and subject='QF2'";
                        }else{
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF2_stem_programme_student = 0;
							$total_QF2_stem_programme_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF2_stem_programme_adjust +=  $row['amount'];
                                $total_QF2_stem_programme_student += $level_count;
                            }else{

                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' ";

                                $sql .= " and case when f.stem_programme_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                when f.stem_programme_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.stem_programme_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.stem_programme_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                            
                                while ($roww=mysqli_fetch_array($resultt)) {
                                    $student_id_array[] = $roww['id']; 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_QF2_stem_programme_adjust += $level_count * $row['stem_programme_adjust'];
                                $total_QF2_stem_programme_student += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>    
                                </td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="STEM Programme" />
                                <input type="hidden" id="subject" name="subject[]" value="QF2" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format((float)$row['stem_programme_adjust'], 2, '.', ''); ?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo  number_format((float)$level_count * $row['stem_programme_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF2_stem_programme_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF2_stem_programme_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>
                            <!--  b QF2 end -->
                            <!--  b QF3 start -->

                            <tr class="">
                                <td style=" margin-top:200px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF3</td>
                            </tr>
                            <?php
                         if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as stem_programme_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='STEM Programme' and subject='QF3'";
                        }else{
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF3_stem_programme_student = 0;
							$total_QF3_stem_programme_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF3_stem_programme_adjust +=  $row['amount'];
                                $total_QF3_stem_programme_student += $level_count;
                            }else{

                                $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' ";

                                $sql .= " and case when f.stem_programme_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                when f.stem_programme_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.stem_programme_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.stem_programme_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);

                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_QF3_stem_programme_adjust += $level_count * $row['stem_programme_adjust'];
                                $total_QF3_stem_programme_student += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>      
                                </td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="STEM Programme" />
                                <input type="hidden" id="subject" name="subject[]" value="QF3" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format((float)$row['stem_programme_adjust'], 2, '.', ''); ?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['stem_programme_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF3_stem_programme_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF3_stem_programme_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>
                            </tr>
                            <!--  b QF3 end -->

                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student (iii)</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php echo $total_stem_programme_student = $total_EDP_stem_programme_student + $total_QF1_stem_programme_student + $total_QF2_stem_programme_student + $total_QF3_stem_programme_student; $total_student_B += $total_stem_programme_student; ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total (iii)" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php  $total_stem_programme_adjust = $total_EDP_stem_programme_adjust + $total_QF1_stem_programme_adjust  + $total_QF2_stem_programme_adjust + $total_QF3_stem_programme_adjust; 
                                        echo  number_format((float)$total_stem_programme_adjust, 2, '.', '')?>"
                                        readonly>
                                    <?php $total_b += $total_stem_programme_adjust; ?>
                                </td>
                            </tr>
                            <!--  b 1 end -->



                            <!--b 1  start -->
                            <tr class="">
                                <td style=" margin-top:50px;border:none; font-size:18px;"
                                    class="uk-width-6-10 uk-text-bold">(iv) STEM Student Kit</td>
                            </tr>
                            <!--  b 1 edp start -->

                            <tr class="">
                                <td style=" margin-top:200px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">EDP</td>
                            </tr>
                            <?php
                            if($mode=="EDIT"){
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as stem_studentKit_adjust, amount
                            from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='STEM Student Kit' and subject='EDP'";
                            }else{
                                $sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
                            }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);

                            $total_EDP_stem_studentKit_adjust = 0;
                            $total_EDP_stem_studentKit_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                             if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_EDP_stem_studentKit_adjust +=  $row['amount'];
                                $total_EDP_stem_studentKit_student += $level_count;
                            }else{

                                $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' ";

                                $sql .= " and case when f.stem_studentKit_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                when f.stem_studentKit_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.stem_studentKit_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.stem_studentKit_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                           
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id']; 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_EDP_stem_studentKit_adjust += $level_count * $row['stem_studentKit_adjust'];
                                $total_EDP_stem_studentKit_student += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>    
                                </td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="STEM Student Kit" />
                                <input type="hidden" id="subject" name="subject[]" value="EDP" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format((float)$row['stem_studentKit_adjust'], 2, '.', ''); ?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['stem_studentKit_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_EDP_stem_studentKit_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_EDP_stem_studentKit_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>

                            <!--  b edp end -->
                            <!--  b QF1 start -->

                            <tr class="">
                                <td style=" margin-top:200px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF1</td>
                            </tr>
                            <?php
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as stem_studentKit_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='STEM Student Kit' and subject='QF1'";
                        }else{
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF1_stem_studentKit_student = 0;
							$total_QF1_stem_studentKit_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF1_stem_studentKit_adjust +=  $row['amount'];
                                $total_QF1_stem_studentKit_student += $level_count;
                            }else{

                                $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' ";

                                $sql .= " and case when f.stem_studentKit_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                when f.stem_studentKit_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.stem_studentKit_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.stem_studentKit_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                end ";

                                $sql .= "   group by ps.student_entry_level, s.id";

                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                
                                while ($roww=mysqli_fetch_array($resultt)) {
                                    $student_id_array[] = $roww['id'];
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_QF1_stem_studentKit_adjust += $level_count * $row['stem_studentKit_adjust'];
                                $total_QF1_stem_studentKit_student += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>     
                                </td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="STEM Student Kit" />
                                <input type="hidden" id="subject" name="subject[]" value="QF1" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format((float)$row['stem_studentKit_adjust'], 2, '.', ''); ?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['stem_studentKit_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF1_stem_studentKit_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF1_stem_studentKit_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>
                            <!--  b QF1 end -->
                            <!--  b QF2 start -->

                            <tr class="">
                                <td style=" margin-top:200px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF2</td>
                            </tr>
                            <?php
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as stem_studentKit_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='STEM Student Kit' and subject='QF2'";
                        }else{
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF2_stem_studentKit_student = 0;
							$total_QF2_stem_studentKit_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF2_stem_studentKit_adjust +=  $row['amount'];
                                $total_QF2_stem_studentKit_student += $level_count;
                            }else{

                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' ";

                                $sql .= " and case when f.stem_studentKit_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                when f.stem_studentKit_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.stem_studentKit_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.stem_studentKit_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                            
                                while ($roww=mysqli_fetch_array($resultt)) {
                                    $student_id_array[] = $roww['id'];
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_QF2_stem_studentKit_adjust += $level_count * $row['stem_studentKit_adjust'];
                                $total_QF2_stem_studentKit_student += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>    
                                </td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="STEM Student Kit" />
                                <input type="hidden" id="subject" name="subject[]" value="QF2" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format((float)$row['stem_studentKit_adjust'], 2, '.', ''); ?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo  number_format((float)$level_count * $row['stem_studentKit_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF2_stem_studentKit_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF2_stem_studentKit_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>
                            <!--  b QF2 end -->
                            <!--  b QF3 start -->

                            <tr class="">
                                <td style=" margin-top:200px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF3</td>
                            </tr>
                            <?php
                         if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as stem_studentKit_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='STEM Student Kit' and subject='QF3'";
                        }else{
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF3_stem_studentKit_student = 0;
							$total_QF3_stem_studentKit_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF3_stem_studentKit_adjust +=  $row['amount'];
                                $total_QF3_stem_studentKit_student += $level_count;
                            }else{

                                $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' ";

                                $sql .= " and case when f.stem_studentKit_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                when f.stem_studentKit_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.stem_studentKit_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.stem_studentKit_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);

                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_QF3_stem_studentKit_adjust += $level_count * $row['stem_studentKit_adjust'];
                                $total_QF3_stem_studentKit_student += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>      
                                </td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="STEM Student Kit" />
                                <input type="hidden" id="subject" name="subject[]" value="QF3" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format((float)$row['stem_studentKit_adjust'], 2, '.', ''); ?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['stem_studentKit_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF3_stem_studentKit_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF3_stem_studentKit_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>
                            </tr>
                            <!--  b QF3 end -->

                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student (iv)</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php echo $total_stem_studentKit_student = $total_EDP_stem_studentKit_student + $total_QF1_stem_studentKit_student + $total_QF2_stem_studentKit_student + $total_QF3_stem_studentKit_student; $total_student_B += $total_stem_studentKit_student; ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total (iv)" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php  $total_stem_studentKit_adjust = $total_EDP_stem_studentKit_adjust + $total_QF1_stem_studentKit_adjust  + $total_QF2_stem_studentKit_adjust + $total_QF3_stem_studentKit_adjust; 
                                        echo  number_format((float)$total_stem_studentKit_adjust, 2, '.', '')?>"
                                        readonly>
                                    <?php $total_b += $total_stem_studentKit_adjust; ?>
                                </td>
                            </tr>
                            <!--  b 1 end -->

                        <?php 
                        }
                        ?>


                            <!--b 3  start -->
                            <tr class="">
                                <td style=" margin-top:50px;border:none; font-size:18px;"
                                    class="uk-width-6-10 uk-text-bold">(<?php echo ($monthyear >= '2024-03') ? 'v' : 'iii'; ?>) Registration Fee</td>
                            </tr>
                            <!--  b 3 edp start -->

                            <tr class="">
                                <td style=" margin-top:200px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">EDP</td>
                            </tr>
                            <?php
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as registration_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Registration Pack' and subject='EDP'";
                        }else{
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_EDP_registration_student = 0;
                            $total_EDP_registration_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_EDP_registration_adjust +=  $row['amount'];
                                $total_EDP_registration_student += $level_count;
                            }else{
                           // $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where c.void='0' and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.registration_adjust !='' and c.product_code='Registration' and month(c.collection_date_time) = $month group by ps.student_entry_level, s.id) ab";

                           $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and DATE_FORMAT(s.start_date_at_centre, '%Y-%m') = '".$monthyear."' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.registration_adjust !=''  and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_EDP_registration_adjust += $level_count * $row['registration_adjust'];
                                $total_EDP_registration_student += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>     
                                </td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Registration Pack" />
                                <input type="hidden" id="subject" name="subject[]" value="EDP" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['registration_adjust']?>" readonly> <span
                                        class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['registration_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_EDP_registration_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_EDP_registration_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>
                            <!--  b 3 edp end -->
                            <!--  b 3 QF1 start -->

                            <tr class="">
                                <td style=" margin-top:200px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF1</td>
                            </tr>
                            <?php
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as registration_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Registration Pack' and subject='QF1'";
                        }else{
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF1_registration_student = 0;
							$total_QF1_registration_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF1_registration_adjust +=  $row['amount'];
                                $total_QF1_registration_student += $level_count;
                            }else{
                            //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.registration_adjust !='' and c.product_code='Registration' and month(c.collection_date_time) = $month group by ps.student_entry_level, s.id) ab";

                            // $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where c.void='0' and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and c.product_code='Registration' and month(c.collection_date_time) = $month group by ps.student_entry_level, s.id) ab";

                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and DATE_FORMAT(s.start_date_at_centre, '%Y-%m') = '".$monthyear."' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m')  group by ps.student_entry_level, s.id";

                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);
                                $total_QF1_registration_adjust += $level_count * $row['registration_adjust'];
                                $total_QF1_registration_student += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>    
                                </td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Registration Pack" />
                                <input type="hidden" id="subject" name="subject[]" value="QF1" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['registration_adjust']?>" readonly> <span
                                        class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['registration_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF1_registration_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF1_registration_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>
                            <!--  b 3 QF1 end -->
                            <!--  b 3 QF2 start -->

                            <tr class="">
                                <td style=" margin-top:200px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF2</td>
                            </tr>
                            <?php
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as registration_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Registration Pack' and subject='QF2'";
                        }else{
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF2_registration_student = 0;
							$total_QF2_registration_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF2_registration_adjust +=  $row['amount'];
                                $total_QF2_registration_student += $level_count;
                            }else{
                            //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.registration_adjust !='' and c.product_code='Registration' and month(c.collection_date_time) = $month group by ps.student_entry_level, s.id) ab";

                            //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where c.void='0' and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and c.product_code='Registration' and month(c.collection_date_time) = $month group by ps.student_entry_level, s.id) ab";

                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and DATE_FORMAT(s.start_date_at_centre, '%Y-%m') = '".$monthyear."' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id";

                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_QF2_registration_adjust += $level_count * $row['registration_adjust'];
                                $total_QF2_registration_student += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>     
                                </td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Registration Pack" />
                                <input type="hidden" id="subject" name="subject[]" value="QF2" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['registration_adjust']?>" readonly> <span
                                        class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['registration_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF2_registration_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF2_registration_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>
                            <!--  b 3 QF2 end -->
                            <!--  b 3 QF3 start -->

                            <tr class="">
                                <td style=" margin-top:200px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF3</td>
                            </tr>
                            <?php
                         if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as registration_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Registration Pack' and subject='QF3'";
                        }else{
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF3_registration_student = 0;
							$total_QF3_registration_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>     
                                </td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Registration Pack" />
                                <input type="hidden" id="subject" name="subject[]" value="QF3" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF3_registration_adjust +=  $row['amount'];
                                $total_QF3_registration_student += $level_count;
                            }else{
                            //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.registration_adjust !='' and c.product_code='Registration' and month(c.collection_date_time) = $month group by ps.student_entry_level, s.id) ab";

                            // $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where c.void='0' and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and c.product_code='Registration' and month(c.collection_date_time) = $month group by ps.student_entry_level, s.id) ab";

                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and DATE_FORMAT(s.start_date_at_centre, '%Y-%m') = '".$monthyear."' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id";

                            

                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_QF3_registration_adjust += $level_count * $row['registration_adjust'];
                                $total_QF3_registration_student += $level_count;
                            }
                                ?>
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['registration_adjust']?>" readonly> <span
                                        class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['registration_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF3_registration_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF3_registration_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>
                            <!--  b 3 QF3 end -->
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student (<?php echo ($monthyear >= '2024-03') ? 'v' : 'iii'; ?>)</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php echo $total_registration_student = $total_EDP_registration_student + $total_QF1_registration_student + $total_QF2_registration_student + $total_QF3_registration_student; $total_student_B += $total_registration_student; ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total (<?php echo ($monthyear >= '2024-03') ? 'v' : 'iii'; ?>)" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php  $total_registration_adjust = $total_EDP_registration_adjust + $total_QF1_registration_adjust  + $total_QF2_registration_adjust + $total_QF3_registration_adjust;  
                                        echo number_format((float)$total_registration_adjust, 2, '.', '');?>"
                                        readonly>
                                    <?php $total_b += $total_registration_adjust; ?>
                                </td>
                            </tr>
                            <!--  b 3 end -->



                            <!-- <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">(iii)
                                Registration Pack<span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd registration" type="number" step="0.01" name="registration_s"
                                    id="registration_s" value="1"> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd registration" type="number" name="registration_st"
                                    id="registration_st" value="10" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust1" type="number" step="0.01" name="registration_t"
                                    id="registration_t" value="12"><br>
                            </td>
                        </tr> -->
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10"><!-- Total B --></td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" style="display:none;" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_student_B; ?>" readonly>
                                </td>
                                <td style="text-align:center; border:none;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_b" id="total_b"
                                        placeholder="Total B"> <span style="font-size:14px;" class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center; border:none;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="totalb_g" id="totalb_g"
                                        value="<?php echo number_format((float)$total_b, 2, '.', ''); ?>" readonly>
                                </td>
                            </tr>
                            <tr class="">
                                <td colspan="5">Note: as per Student Fee List for each Academic Year as prescribed by
                                    the
                                    Franchisor<br>*as stated in the Operations e-Manual</td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">C: Products</td>
                            </tr>

                            <tr class="">
                                
                                    <?php
                             if($mode=="EDIT"){
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate, amount
                                from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Pre-school Kits'";
                                $result=mysqli_query($connection, $sql);
                                $num_row = mysqli_num_rows($result);
                                if ($num_row>0) {
                                    while ($row=mysqli_fetch_assoc($result)) {
                                        $level_count = $row['active_student'];
                                        $fees = $row["fee_rate"]; 
                                        $total_pre_school_kits =  $row['amount'];
                                        $total_student_C += $level_count;
                                    }
                                }
                                
                            }else{
                            //$sql="SELECT count(id) level_count, fees from (SELECT s.id, p.unit_price as fees from student s inner join `collection` c on c.student_id = s.id inner join product p on p.product_code=c.product_code where c.year = '$year' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and p.product_name like 'Pre-school Kit%' and c.product_code like '%QHSB_55' and month(c.collection_date_time) = $month group by s.id) ab";
                            //$sql="SELECT count(id) level_count, fees from (SELECT s.id, p.unit_price as fees from student s inner join `collection` c on c.student_id = s.id inner join product p on p.product_code=c.product_code where c.year = '$year' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and p.product_name like 'Pre-school Kit%'  and month(c.collection_date_time) = $month group by s.id) ab";
                            $sql = "SELECT round(sum(level_count), 0) level_count, round(avg(fees), 2) fees from ( ";
                            $sql .="SELECT round(sum(qty), 0) level_count, fees from (SELECT c.qty, c.unit_price as fees from student s inner join `collection` c on c.student_id = s.id inner join product p on p.product_code=c.product_code where c.void='0' and c.year = '$year' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and p.product_name like 'Pre-school Kit%'  and month(c.collection_date_time) = $month group by c.id) ab ";
                            $sql .=" UNION ALL ";
                            $sql .=" SELECT count(id) level_count, fees from (SELECT ps.student_entry_level, s.id, c.unit_price as fees from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where c.void='0' and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and c.product_code='Q-dees Level Kit' and month(c.collection_date_time) = $month group by ps.student_entry_level, s.id) ab ";
                            $sql .= " )abc ";
						
							$sql1="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and DATE_FORMAT(s.start_date_at_centre, '%Y-%m') = '".$monthyear."' and s.centre_code='$centre_code' and s.deleted='0'";

							$sql1 .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id";
								$sql_price="SELECT *, BIN(`deleted` + 0) AS `deleted`, unit_price as fees
								FROM `product`
								WHERE `product_name` LIKE '%Pre-school Kit%'
								LIMIT 1";
								$result_price=mysqli_query($connection, $sql_price);
								$row_price=mysqli_fetch_assoc($result_price);
                                $resultt=mysqli_query($connection, $sql1);
                                 
                                
                                $fees = 0;

                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];
                                    $fees = (empty($row_price["fees"]) ? "0" : $row_price["fees"]); 
                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);


                                $total_pre_school_kits += $level_count * $fees;
                                $total_student_C += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" >(i)Pre-school Kits<span class="text-danger"></span>:</span>   
                                </td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Pre-school Kits" />
                                <input type="hidden" id="subject" name="subject[]" value="" />
                                <input type="hidden" id="programme_package" name="programme_package[]" value="" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd pre_school" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd pre_school" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format((float)$fees, 2, '.', '') ?>" readonly> <span class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust2" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$total_pre_school_kits, 2, '.', ''); ?>" readonly><br>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    (ii)Memories to Cherish<span class="text-danger"></span>:</td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Memories to Cherish" />
                                <input type="hidden" id="subject" name="subject[]" value="" />
                                <input type="hidden" id="programme_package" name="programme_package[]" value="" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                            if($mode=="EDIT"){
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate, amount
                                from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Memories to Cherish'";
                                $result=mysqli_query($connection, $sql);
                                $num_row = mysqli_num_rows($result);
                                if ($num_row>0) {
                                    while ($row=mysqli_fetch_assoc($result)) {
                                        $level_count = $row['active_student'];
                                        $fees = $row["fee_rate"]; 
                                        $total_memories_to_cherish =  $row['amount'];
                                        $total_student_C += $level_count;
                                    }
                                }
                                
                            }else{
                                $sql = "SELECT round(sum(level_count), 0) level_count, round(avg(fees), 2) fees from ( ";
                                $sql .="SELECT round(sum(qty), 0) level_count, fees from (SELECT c.qty, c.unit_price as fees from student s inner join `collection` c on c.student_id = s.id inner join product p on p.product_code=c.product_code where c.void='0' and c.year = '$year' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and p.product_name like 'Memories To Cherish Album%' and month(c.collection_date_time) = $month group by c.id) ab";
                                $sql .=" UNION ALL ";
                                $sql .=" SELECT count(id) level_count, fees from (SELECT ps.student_entry_level, s.id, c.unit_price as fees from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where c.void='0' and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and c.product_code='MEMORIES TO CHERISH' and month(c.collection_date_time) = $month group by ps.student_entry_level, s.id) ab ";
                                $sql .= " )abc ";
								
                            
                                $resultt=mysqli_query($connection, $sql);
                                
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                $fees = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                    $fees = (empty($roww["fees"]) ? "0" : $roww["fees"]); 
                                }
                                $total_memories_to_cherish += $level_count * $fees;
                                $total_student_C += $level_count;
                            }
                                ?>
                                    <input class="edp_tsd pre_school" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd pre_school" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format((float)$fees, 2, '.', '') ?>" readonly> <span class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust2" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$total_memories_to_cherish, 2, '.', ''); ?>" readonly><br>
                                </td>
                            </tr>

                            <tr class="">
                                
                                <?php
                                if($mode=="EDIT"){
                                    $sql="SELECT programme_package as fees_structure, active_student, fee_rate, amount
                                    from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Q-dees Bag'";
                                    $result=mysqli_query($connection, $sql);
                                    $num_row = mysqli_num_rows($result);
                                    if ($num_row>0) {
                                        while ($row=mysqli_fetch_assoc($result)) {
                                            $level_count = $row['active_student'];
                                            $fees = $row["fee_rate"]; 
                                            $total_q_bag_adjust =  $row['amount'];
                                            $total_student_C += $level_count;
                                        }
                                    }
                                    
                                }else{
                                    //$sql="SELECT count(id) level_count, fees from (SELECT ps.student_entry_level, s.id, f.q_bag_adjust as fees from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and f.q_bag_adjust !='' and c.product_code='Q-dees Bag' and month(c.collection_date_time) = $month group by ps.student_entry_level, s.id) ab";
                                    $sql = "SELECT round(sum(level_count), 0) level_count, round(avg(fees), 2) fees from ( ";
                                    $sql .="SELECT round(sum(qty), 0) level_count, fees from (SELECT c.qty, c.unit_price as fees from student s inner join `collection` c on c.student_id = s.id inner join product p on p.product_code=c.product_code where c.void='0' and c.year = '$year' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and p.product_name = 'Q-dees Back Pack Bag' and month(c.collection_date_time) = $month group by c.id) ab";
                                    $sql .=" UNION ALL ";
                                    $sql .=" SELECT count(id) level_count, fees from (SELECT ps.student_entry_level, s.id, f.q_bag_adjust as fees from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where c.void='0' and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and c.product_code='Q-dees Bag' and month(c.collection_date_time) = $month group by ps.student_entry_level, s.id) ab";
                                    $sql .= " )abc ";

                                    $sql1="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where c.void='0' and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') ".$new_price_condition." and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and c.product_code='Q-dees Bag' and DATE_FORMAT(c.collection_date_time, '%Y-%m') = '$monthyear' group by ps.student_entry_level, s.id";

									//$sql1 .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";
									
                                    $sql_price="SELECT *, BIN(`deleted` + 0) AS `deleted`, unit_price as fees
									FROM `product`
									WHERE `product_name` LIKE '%Q-dees Back Pack Bag%'
									LIMIT 1";
									$result_price=mysqli_query($connection, $sql_price);
									$row_price=mysqli_fetch_assoc($result_price);

                                    $resultt=mysqli_query($connection, $sql1);
                                    
                                    $fees = 0;
                                    

                                    $student_id_array = array();

                                    $level_count=mysqli_num_rows($resultt);
                                    //$level_count = 0;
                                    while ($roww=mysqli_fetch_array($resultt)) {

                                        $student_id_array[] = $roww['id'];

                                        //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                        $fees = (empty($row_price["fees"]) ? "0" : $row_price["fees"]);
                                    }

                                    $student_id_array = implode(', ', $student_id_array);

                                    $total_q_bag_adjust += $level_count * $fees;
                                    $total_student_C += $level_count;
                                }
                                ?>

                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">

                                <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" >(iii)Q-dees
                                Bag<span class="text-danger"></span>:</span>

                                </td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                value="Q-dees Bag" />
                                <input type="hidden" id="subject" name="subject[]" value="" />
                                <input type="hidden" id="programme_package" name="programme_package[]" value="" />

                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd q_dees" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq" readonly>✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd q_dees" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format((float)$fees, 2, '.', '') ?>" readonly>
                                    <span class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust2" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$total_q_bag_adjust, 2, '.', ''); ?>" readonly><br>
                                </td>
                            </tr>

                            <tr class="">

                            <?php $new_price_condition = ''; if(($monthyear == '2022-10' && $_GET['mode'] != 'EDIT') || ($_GET['mode'] == 'EDIT' && $month == '10' && ($year == '2022' || $year == '2022-2023'))) { 

                                $new_price_condition = "and fl.`uniform_adjust` = '98'";

                                $student_id_list=mysqli_query($connection,"SELECT s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and fl.`uniform_adjust` != '98' and ps.student_entry_level != '' and s.student_status = 'A' and DATE_FORMAT(s.start_date_at_centre, '%Y-%m') = '".$monthyear."' and s.centre_code='$centre_code' and s.deleted='0' and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id");

                                if(mysqli_num_rows($student_id_list) > 0) 
                                {
                                    $student_id_array = array();

                                    while($student_id_row = mysqli_fetch_array($student_id_list))
                                    {
                                        $student_id_array[] = $student_id_row['id'];
                                    }
                                    
                                    $new_price_condition .= " and s.id NOT IN (" . implode(", ", $student_id_array) . ")";
                                }

                                ?> (New Price) <?php } ?>

                                <?php
                                 if($mode=="EDIT"){
                                     $sql="SELECT programme_package as fees_structure, active_student, fee_rate, amount
                                     from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Uniform'";
                                     $result=mysqli_query($connection, $sql);
                                     $num_row = mysqli_num_rows($result);
                                     if ($num_row>0) {
                                         while ($row=mysqli_fetch_assoc($result)) {
                                             $level_count = $row['active_student'];
                                             $fees = $row["fee_rate"]; 
                                             $total_uniform_adjust =  $row['amount'];
                                             $total_student_C += $level_count;
                                         }
                                     }
                                 }else{
                                    // $sql="SELECT count(id) level_count, fees from (SELECT ps.student_entry_level, s.id, f.uniform_adjust as fees from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and f.uniform_adjust !='' and c.product_code='Uniform (2 sets)' and month(c.collection_date_time) = $month group by ps.student_entry_level, s.id) ab";
                                    
                                    $sql =" SELECT count(id) level_count, fees from (SELECT ps.student_entry_level, s.id, fl.uniform_adjust as fees from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where c.void='0' and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and c.product_code='Uniform (2 sets)' and month(c.collection_date_time) = $month group by ps.student_entry_level, s.id) ab";

									$sql1="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where c.void='0' and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') ".$new_price_condition." and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and c.product_code='Uniform (2 sets)' and DATE_FORMAT(c.collection_date_time, '%Y-%m') = '$monthyear' group by ps.student_entry_level, s.id";

										//$sql1 .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";
                                        
										$sql_price="SELECT *, BIN(`deleted` + 0) AS `deleted`, unit_price as fees
										FROM `product`
										WHERE `product_name` LIKE '%Uniform%'
										LIMIT 1";
										$result_price=mysqli_query($connection, $sql_price);
										$row_price=mysqli_fetch_assoc($result_price);

                                        $resultt=mysqli_query($connection, $sql1);
                                        
                                        $fees = 0;
                                       
                                        $student_id_array1 = array();

                                        $level_count=mysqli_num_rows($resultt);
                                        //$level_count = 0;
                                        while ($roww=mysqli_fetch_array($resultt)) {

                                            $student_id_array1[] = $roww['id'];

                                            //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                            $fees = (empty($row_price["fees"]) ? "0" : $row_price["fees"]);
                                        }

                                        $student_id_array1 = implode(', ', $student_id_array1);

                                        $fees = 98;

                                        $total_uniform_adjust += $level_count * $fees;
                                        $total_student_C += $level_count;
                            }
                                ?>

                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array1; ?>')" class="studentLink" >(iv)Uniform<span class="text-danger"></span>:</span>
                                </td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Uniform" />
                                <input type="hidden" id="subject" name="subject[]" value="" />
                                <input type="hidden" id="programme_package" name="programme_package[]" value="" />

                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd q_dees" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq" readonly>✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd q_dees" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format((float)$fees, 2, '.', '') ?>" readonly>
                                    <span class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust2" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$total_uniform_adjust, 2, '.', '') ?>" readonly><br>
                                </td>

                            </tr>
                            <?php if(($monthyear == '2022-10' && $_GET['mode'] != 'EDIT') || ($_GET['mode'] == 'EDIT' && $month == '10' && ($year == '2022' || $year == '2022-2023'))) { ?>
                                <tr class="">
                                    <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                        (iv)Uniform (Old Price) <span class="text-danger"></span>:</td>
                                    <input type="hidden" id="form" name="form[]" value="Form1" />
                                    <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                        value="Uniform (Old Price)" />
                                    <input type="hidden" id="subject" name="subject[]" value="" />
                                    <input type="hidden" id="programme_package" name="programme_package[]" value="" />
                                <?php
                                    if($mode=="EDIT"){
                                        $sql="SELECT programme_package as fees_structure, active_student, fee_rate, amount
                                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Uniform (Old Price)'";
                                        $result=mysqli_query($connection, $sql);
                                        $num_row = mysqli_num_rows($result);
                                        if ($num_row>0) {
                                            while ($row=mysqli_fetch_assoc($result)) {
                                                $level_count = $row['active_student'];
                                                $fees = $row["fee_rate"]; 
                                                $total_uniform_adjust_old =  $row['amount'];
                                                $total_uniform_adjust +=  $row['amount'];
                                                $total_student_C += $level_count;
                                            }
                                        }
                                    } else {
                                        // $sql="SELECT count(id) level_count, fees from (SELECT ps.student_entry_level, s.id, f.uniform_adjust as fees from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and f.uniform_adjust !='' and c.product_code='Uniform (2 sets)' and month(c.collection_date_time) = $month group by ps.student_entry_level, s.id) ab";
                                        
                                        $sql =" SELECT count(id) level_count, fees from (SELECT ps.student_entry_level, s.id, fl.uniform_adjust as fees from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where c.void='0' and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and c.product_code='Uniform (2 sets)' and month(c.collection_date_time) = $month group by ps.student_entry_level, s.id) ab";

                                        $sql1="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and fl.`uniform_adjust` != '98' and ps.student_entry_level != '' and s.student_status = 'A' and DATE_FORMAT(s.start_date_at_centre, '%Y-%m') = '".$monthyear."' and s.centre_code='$centre_code' and s.deleted='0'";

                                        $sql1 .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";

                                        $sql_price="SELECT *, BIN(`deleted` + 0) AS `deleted`, unit_price as fees
                                        FROM `product`
                                        WHERE `product_name` LIKE '%Uniform%'
                                        LIMIT 1";
                                        
                                        $result_price=mysqli_query($connection, $sql_price);
                                        $row_price=mysqli_fetch_assoc($result_price);

                                        $resultt=mysqli_query($connection, $sql1);
                                        
                                        $num_row=mysqli_num_rows($resultt);
                                        $level_count = 0;
                                        $fees = 0;
                                        while ($roww=mysqli_fetch_assoc($resultt)) {
                                            $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                            $fees = (empty($row_price["fees"]) ? "0" : $row_price["fees"]); 
                                        }
                                        
                                        $fees = 86;
                                    
                                        $total_uniform_adjust_old += $level_count * $fees;
                                        $total_uniform_adjust += $level_count * $fees;
                                        $total_student_C += $level_count;
                                    }
                                    ?>
                                    <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                        <input class="edp_tsd q_dees" type="number" step="0.01" name="active_student[]"
                                            id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                            class="edp_eq" readonly>✕ </span>
                                    </td>
                                    <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                        <input class="edp_tsd q_dees" type="number" name="fee_rate[]" id="fee_rate"
                                            value="<?php echo number_format((float)$fees, 2, '.', '') ?>" readonly>
                                        <span class="edp_eq2">= </span>
                                    </td>
                                    <td style="border:none; text-align:center;" class="uk-width-1-10">
                                        <input class="school_adjust2" type="number" step="0.01" name="amount[]" id="amount"
                                            value="<?php echo number_format((float)$total_uniform_adjust_old, 2, '.', '') ?>" readonly><br>
                                    </td>

                                </tr>
                            <?php } ?>
                            <?php
                            if($mode=="EDIT"){
                                $sql="SELECT programme_package as fees_structure, fee_structure_mame as product_name, active_student as level_count, fee_rate as fees, amount from `declaration_child` where master_id=$master_id and form='Form1' and (fee_structure_mame like 'Q-dees Male Uniform%' or fee_structure_mame like 'Q-dees Long Pants%' or fee_structure_mame like 'Q-dees Female Uniform%' or fee_structure_mame like 'Q-dees Kelantan Male Uniform%' or fee_structure_mame like 'Q-dees Kelantan Female Uniform%') ORDER BY FIELD(product_name,'Q-dees Male Uniform - XS(short)','Q-dees Male Uniform - S(short)','Q-dees Male Uniform - M(short)','Q-dees Male Uniform - L(short)','Q-dees Male Uniform - XL(short)','Q-dees Male Uniform - XXL(short)','Q-dees Male Uniform - XXXL(short)','Q-dees Male Uniform - Special Make(short)','Q-dees Male Uniform - XS(long)','Q-dees Male Uniform - S(long)','Q-dees Male Uniform - M(long)','Q-dees Male Uniform - L(long)','Q-dees Male Uniform - XL(long)', 'Q-dees Long Pants - XS', 'Q-dees Long Pants - S', 'Q-dees Long Pants - M', 'Q-dees Long Pants - L', 'Q-dees Long Pants - XL', 'Q-dees Female Uniform - XS', 'Q-dees Female Uniform - S', 'Q-dees Female Uniform - M', 'Q-dees Female Uniform - L', 'Q-dees Female Uniform - XL', 'Q-dees Female Uniform - XXL', 'Q-dees Female Uniform - XXXL', 'Q-dees Female Uniform - Special Make', 'Q-dees Kelantan Male Uniform - XS', 'Q-dees Kelantan Male Uniform - S', 'Q-dees Kelantan Male Uniform - M', 'Q-dees Kelantan Male Uniform - L', 'Q-dees Kelantan Male Uniform - XL', 'Q-dees Kelantan Male Uniform - XXL', 'Q-dees Kelantan Male Uniform - XXXL', 'Q-dees Kelantan Female Uniform - XS', 'Q-dees Kelantan Female Uniform - S', 'Q-dees Kelantan Female Uniform - M', 'Q-dees Kelantan Female Uniform - L', 'Q-dees Kelantan Female Uniform - XL', 'Q-dees Kelantan Female Uniform - XXL', 'Q-dees Kelantan Female Uniform - XXXL') ";
                            }else{
                            $sql ="SELECT product_name, round(sum(qty), 0) level_count, fees from (SELECT p.product_name, c.qty, c.unit_price as fees from student s inner join `collection` c on c.student_id = s.id inner join product p on p.product_code=c.product_code where c.void='0' and c.year = '$year' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and 
                            (p.product_name like 'Q-dees Male Uniform%' or p.product_name like 'Q-dees Long Pants%' or p.product_name like 'Q-dees Female Uniform%' or p.product_name like 'Q-dees Kelantan Male Uniform%' or p.product_name like 'Q-dees Kelantan Female Uniform%') 
                            and month(c.collection_date_time) = $month group by c.id, c.product_code) ab group by product_name ORDER BY FIELD(product_name,'Q-dees Male Uniform - XS(short)','Q-dees Male Uniform - S(short)','Q-dees Male Uniform - M(short)','Q-dees Male Uniform - L(short)','Q-dees Male Uniform - XL(short)','Q-dees Male Uniform - XXL(short)','Q-dees Male Uniform - XXXL(short)','Q-dees Male Uniform - Special Make(short)','Q-dees Male Uniform - XS(long)','Q-dees Male Uniform - S(long)','Q-dees Male Uniform - M(long)','Q-dees Male Uniform - L(long)','Q-dees Male Uniform - XL(long)', 'Q-dees Long Pants - XS', 'Q-dees Long Pants - S', 'Q-dees Long Pants - M', 'Q-dees Long Pants - L', 'Q-dees Long Pants - XL', 'Q-dees Female Uniform - XS', 'Q-dees Female Uniform - S', 'Q-dees Female Uniform - M', 'Q-dees Female Uniform - L', 'Q-dees Female Uniform - XL', 'Q-dees Female Uniform - XXL', 'Q-dees Female Uniform - XXXL', 'Q-dees Female Uniform - Special Make', 'Q-dees Kelantan Male Uniform - XS', 'Q-dees Kelantan Male Uniform - S', 'Q-dees Kelantan Male Uniform - M', 'Q-dees Kelantan Male Uniform - L', 'Q-dees Kelantan Male Uniform - XL', 'Q-dees Kelantan Male Uniform - XXL', 'Q-dees Kelantan Male Uniform - XXXL', 'Q-dees Kelantan Female Uniform - XS', 'Q-dees Kelantan Female Uniform - S', 'Q-dees Kelantan Female Uniform - M', 'Q-dees Kelantan Female Uniform - L', 'Q-dees Kelantan Female Uniform - XL', 'Q-dees Kelantan Female Uniform - XXL', 'Q-dees Kelantan Female Uniform - XXXL')";
                            }
                            $resul=mysqli_query($connection, $sql);
                            
                            //$num_row=mysqli_num_rows($resul);
                            $level_count = 0;
                            $fees = 0;
                            while ($ro=mysqli_fetch_assoc($resul)) {
                                $product_name = $ro['product_name'];
                                $level_count = (empty($ro["level_count"]) ? "0" : $ro["level_count"]); 
                                $fees = (empty($ro["fees"]) ? "0" : $ro["fees"]); 
                           
                                $total_q_bag_male_uniform = $level_count * $fees;
                                $total_q_bag_male_uniforms += $total_q_bag_male_uniform;
                                $total_student_C += $level_count;
                            ?>

                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold"><?php echo $product_name ?><span class="text-danger"></span>:</td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="<?php echo $product_name ?>" />
                                <input type="hidden" id="subject" name="subject[]" value="" />
                                <input type="hidden" id="programme_package" name="programme_package[]" value="" />
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd q_dees" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq" readonly>✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd q_dees" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format((float)$fees, 2, '.', '') ?>" readonly>
                                    <span class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust2" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$total_q_bag_male_uniform, 2, '.', ''); ?>" readonly><br>
                                </td>
                            </tr>                
                            <?php
                                }
                            ?>

                            <tr class="">

                                <?php $new_price_condition = ''; if(($monthyear == '2022-10' && $_GET['mode'] != 'EDIT') || ($_GET['mode'] == 'EDIT' && $month == '10' && ($year == '2022' || $year == '2022-2023'))) { 
                                        
                                    $new_price_condition = "and fl.`gymwear_adjust` = '37' "; 

                                    $student_id_list=mysqli_query($connection,"SELECT s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and fl.`gymwear_adjust` != '37' and ps.student_entry_level != '' and s.student_status = 'A' and DATE_FORMAT(s.start_date_at_centre, '%Y-%m') = '".$monthyear."' and s.centre_code='$centre_code' and s.deleted='0' and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id");
                                    
                                    if(mysqli_num_rows($student_id_list) > 0) 
                                    {
                                        $student_id_array = array();

                                        while($student_id_row = mysqli_fetch_array($student_id_list))
                                        {
                                            $student_id_array[] = $student_id_row['id'];
                                        }
                                        
                                        $new_price_condition .= " and s.id NOT IN (" . implode(", ", $student_id_array) . ")";
                                    }

                                ?> (New Price) <?php } ?>

                                
                                <?php
                                    if($mode=="EDIT"){
                                        $sql="SELECT programme_package as fees_structure, active_student, fee_rate, amount
                                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Gymwear'";
                                        $result=mysqli_query($connection, $sql);
                                        $num_row = mysqli_num_rows($result);
                                        if ($num_row>0) {
                                            while ($row=mysqli_fetch_assoc($result)) {
                                                $level_count = $row['active_student'];
                                                $fees = $row["fee_rate"]; 
                                                $total_gymwear_adjust =  $row['amount'];
                                                $total_student_C += $level_count;
                                            }
                                        }
                                        
                                    }else{
                                        // $sql="SELECT count(id) level_count, fees from (SELECT ps.student_entry_level, s.id, f.gymwear_adjust as fees from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and f.gymwear_adjust !='' and c.product_code='Gymwear (1 set)' and month(c.collection_date_time) = $month group by ps.student_entry_level, s.id) ab";
                                        $sql ="SELECT count(id) level_count, fees from (SELECT ps.student_entry_level, s.id, fl.gymwear_adjust as fees from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where c.void='0' and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and c.product_code='Gymwear (1 set)' and month(c.collection_date_time) = $month group by ps.student_entry_level, s.id) ab";

                                        $sql1="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where c.void='0' and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') ".$new_price_condition." and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and c.product_code='Gymwear (1 set)' and DATE_FORMAT(c.collection_date_time, '%Y-%m') = '$monthyear' group by ps.student_entry_level, s.id";

										// $sql1 .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";
										
											$sql_price="SELECT *, BIN(`deleted` + 0) AS `deleted`, unit_price as fees
											FROM `product`
											WHERE `product_name` LIKE '%Gymwear%'
											LIMIT 1";
											$result_price=mysqli_query($connection, $sql_price);
											$row_price=mysqli_fetch_assoc($result_price);

                                            $resultt=mysqli_query($connection, $sql1);
                                            
                                            $fees = 0;
                                           
                                            $student_id_array1 = array();

                                            $level_count=mysqli_num_rows($resultt);
                                            //$level_count = 0;
                                            while ($roww=mysqli_fetch_array($resultt)) {

                                                $student_id_array1[] = $roww['id'];

                                                //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 

                                                //$fees = (empty($row_price["fees"]) ? "0" : $row_price["fees"]); 

                                                $fees = 37.00;
                                            }

                                            $student_id_array1 = implode(', ', $student_id_array1);

                                            $total_gymwear_adjust += $level_count * $fees;
                                            $total_student_C += $level_count;
                                        }
                                ?>

                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array1; ?>')" class="studentLink" >(v)Gymwear<span class="text-danger"></span>:</span>
                                </td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Gymwear" />
                                <input type="hidden" id="subject" name="subject[]" value="" />
                                <input type="hidden" id="programme_package" name="programme_package[]" value="" />

                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd q_dees" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd q_dees" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $fees ?>" readonly>
                                    <span class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust2" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$total_gymwear_adjust, 2, '.', ''); ?>" readonly><br>
                                </td>
                            </tr>
                            <?php if(($monthyear == '2022-10' && $_GET['mode'] != 'EDIT') || ($_GET['mode'] == 'EDIT' && $month == '10' && ($year == '2022' || $year == '2022-2023'))) { ?>

                                <tr class="">
                                    <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                        (v)Gymwear (Old Price) <span class="text-danger"></span>:</td>
                                    <input type="hidden" id="form" name="form[]" value="Form1" />
                                    <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                        value="Gymwear (Old Price)" />
                                    <input type="hidden" id="subject" name="subject[]" value="" />
                                    <input type="hidden" id="programme_package" name="programme_package[]" value="" />
                                    <?php
                                        if($mode=="EDIT"){
                                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate, amount
                                            from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Gymwear (Old Price)'";
                                            $result=mysqli_query($connection, $sql);
                                            $num_row = mysqli_num_rows($result);
                                            if ($num_row>0) {
                                                while ($row=mysqli_fetch_assoc($result)) {
                                                    $level_count = $row['active_student'];
                                                    $fees = $row["fee_rate"]; 
                                                    $total_gymwear_adjust_old =  $row['amount'];
                                                    $total_gymwear_adjust +=  $row['amount'];
                                                    $total_student_C += $level_count;
                                                }
                                            }
                                            
                                        }else{
                                            // $sql="SELECT count(id) level_count, fees from (SELECT ps.student_entry_level, s.id, f.gymwear_adjust as fees from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and f.gymwear_adjust !='' and c.product_code='Gymwear (1 set)' and month(c.collection_date_time) = $month group by ps.student_entry_level, s.id) ab";
                                            $sql ="SELECT count(id) level_count, fees from (SELECT ps.student_entry_level, s.id, fl.gymwear_adjust as fees from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where c.void='0' and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and fl.`gymwear_adjust` != '37' and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and c.product_code='Gymwear (1 set)' and month(c.collection_date_time) = $month group by ps.student_entry_level, s.id) ab";

                                            $sql1="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and fl.`gymwear_adjust` != '37' and ps.student_entry_level != '' and s.student_status = 'A' and DATE_FORMAT(s.start_date_at_centre, '%Y-%m') = '".$monthyear."' and s.centre_code='$centre_code' and s.deleted='0'";

                                            $sql1 .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";
                                            
                                                $sql_price="SELECT *, BIN(`deleted` + 0) AS `deleted`, unit_price as fees
                                                FROM `product`
                                                WHERE `product_name` LIKE '%Gymwear%'
                                                LIMIT 1";
                                                $result_price=mysqli_query($connection, $sql_price);
                                                $row_price=mysqli_fetch_assoc($result_price);

                                                $resultt=mysqli_query($connection, $sql1);
                                                
                                                $num_row=mysqli_num_rows($resultt);
                                                $level_count = 0;
                                                $fees = 0;
                                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                                    //$fees = (empty($row_price["fees"]) ? "0" : $row_price["fees"]); 

                                                    $fees = 33.00;
                                                }
                                                $total_gymwear_adjust_old += $level_count * $fees;
                                                $total_gymwear_adjust += $level_count * $fees;
                                                $total_student_C += $level_count;
                                            }
                                    ?>
                                    <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                        <input class="edp_tsd q_dees" type="number" step="0.01" name="active_student[]"
                                            id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                            class="edp_eq">✕ </span>
                                    </td>
                                    <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                        <input class="edp_tsd q_dees" type="number" name="fee_rate[]" id="fee_rate"
                                            value="<?php echo $fees ?>" readonly>
                                        <span class="edp_eq2">= </span>
                                    </td>
                                    <td style="border:none; text-align:center;" class="uk-width-1-10">
                                        <input class="school_adjust2" type="number" step="0.01" name="amount[]" id="amount"
                                            value="<?php echo number_format((float)$total_gymwear_adjust_old, 2, '.', ''); ?>" readonly><br>
                                    </td>
                                </tr>
                                <?php } ?>
                            <?php
                            if($mode=="EDIT"){
                                $sql="SELECT programme_package as fees_structure, fee_structure_mame as product_name, active_student as level_count, fee_rate as fees, amount from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame like 'Q-dees Gymwear%' ORDER BY FIELD(product_name,'Q-dees Gymwear - XS','Q-dees Gymwear - S','Q-dees Gymwear - M','Q-dees Gymwear - L','Q-dees Gymwear - XL','Q-dees Gymwear - XXL','Q-dees Gymwear - XXXL','Q-dees Gymwear - Special Make','Q-dees Gymwear T-Shirt - XS','Q-dees Gymwear T-Shirt - S','Q-dees Gymwear T-Shirt - M','Q-dees Gymwear T-Shirt - L','Q-dees Gymwear T-Shirt - XL')";
                            }else{
                            $sql ="SELECT product_name, round(sum(qty), 0) level_count, fees from (SELECT p.product_name, c.qty, c.unit_price as fees from student s inner join `collection` c on c.student_id = s.id inner join product p on p.product_code=c.product_code where c.void='0' and c.year = '$year' and  and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and p.product_name like 'Q-dees Gymwear%' and month(c.collection_date_time) = $month group by c.id, c.product_code) ab group by product_name ORDER BY FIELD(product_name,'Q-dees Gymwear - XS','Q-dees Gymwear - S','Q-dees Gymwear - M','Q-dees Gymwear - L','Q-dees Gymwear - XL','Q-dees Gymwear - XXL','Q-dees Gymwear - XXXL','Q-dees Gymwear - Special Make','Q-dees Gymwear T-Shirt - XS','Q-dees Gymwear T-Shirt - S','Q-dees Gymwear T-Shirt - M','Q-dees Gymwear T-Shirt - L','Q-dees Gymwear T-Shirt - XL')";
                            }
                            $resul=mysqli_query($connection, $sql);
                            
                            //$num_row=mysqli_num_rows($resul);
                            $level_count = 0;
                            $fees = 0;
                            while ($ro=mysqli_fetch_assoc($resul)) {
                                $product_name = $ro['product_name'];
                                $level_count = (empty($ro["level_count"]) ? "0" : $ro["level_count"]); 
                                $fees = (empty($ro["fees"]) ? "0" : $ro["fees"]); 
                           
                                $total_q_bag_gymwear = $level_count * $fees;
                                $total_q_bag_gymwears += $total_q_bag_gymwear;
                                $total_student_C += $level_count;
                            ?>

                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold"><?php echo $product_name ?><span class="text-danger"></span>:</td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="<?php echo $product_name ?>" />
                                <input type="hidden" id="subject" name="subject[]" value="" />
                                <input type="hidden" id="programme_package" name="programme_package[]" value="" />
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd q_dees" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq" readonly>✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd q_dees" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format((float)$fees, 2, '.', '') ?>" readonly>
                                    <span class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust2" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$total_q_bag_gymwear, 2, '.', ''); ?>" readonly><br>
                                </td>
                            </tr>                
                            <?php
                                }
                            ?>


                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10"><!-- Total C --></td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" style="display:none;" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_student_C;  ?>" readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="school_default" id="total_c2"
                                        placeholder="Total C"> <span style="font-size:14px;" class="edp_eq"
                                        readonly>RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="" type="number" step="0.01" name="total_c" id="total_c"
                                        value="<?php  $total_c = $total_pre_school_kits + $total_memories_to_cherish + $total_q_bag_adjust  + $total_uniform_adjust + $total_gymwear_adjust + $total_q_bag_gymwears + 
										$total_q_bag_male_uniforms; 
                                        echo number_format((float)$total_c, 2, '.', ''); ?>"
                                        readonly>
                                    <?php //$total_c += $total_uniform_adjust; ?>
                                </td>
                            </tr>
                        </table>
                        <table class="uk-table uk-table-small">
                            <tr>
                                <td class="uk-width-6-10" style="font-size:18px; font-weight:bold; border:none;">(1)
                                    ROYALTY
                                    FEE PAYABLE</td>
                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Percentage</span>
                                </td>

                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Gross Turnover</span>
                                </td>
                                <!--<td style="border:none;width:20px;"></td>-->
                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Total(1)</span>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">Royalty
                                    Fee(5% on Gross Tumover of A. + B.+ C.)<span class="text-danger"></span>:</td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Royalty Fee(5% on Gross Tumover of A. + B.+ C.)" />
                                <input type="hidden" id="subject" name="subject[]" value="" />
                                <input type="hidden" id="programme_package" name="programme_package[]" value="" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input style="position:relative;" class="edp_tsd royalty" type="number" step="0.01"
                                        name="active_student[]" id="active_student" value="5" readonly> <span
                                        style="margin-left: -30px;font-size: 20px;position: absolute;">%</span> <span
                                        style="" class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd royalty" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php  $gross_tumover = $total_a + $total_b + $total_c; 
                                       echo number_format((float)$gross_tumover, 2, '.', ''); ?>" readonly>
                                    <span class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php  $royalty_fee = ($gross_tumover/100)*5; $total_1_2_3 += $royalty_fee;
                                        echo number_format((float)$royalty_fee, 2, '.', ''); ?>"
                                        readonly><br>
                                </td>
                            </tr>
                        </table>
                        <table class="uk-table uk-table-small">
                            <tr>
                                <td colspan="5" style="font-size:18px; font-weight:bold;"></td>
                            </tr>
                            <tr>
                                <td class="uk-width-6-10" style="font-size:18px; font-weight:bold; border:none;">(2) A&P
                                    FEE
                                    PAYABLE</td>
                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Percentage</span>
                                </td>

                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Total A</span>
                                </td>
                                <!--<td style="border:none;width:20px;"></td>-->
                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Total(2)</span>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px;" class="uk-width-6-10 uk-text-bold">Advertising & Promotion
                                    Fee(3% on A. only)<span class="text-danger"></span>:</td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Advertising & Promotion Fee(3% on A. only)" />
                                <input type="hidden" id="subject" name="subject[]" value="" />
                                <input type="hidden" id="programme_package" name="programme_package[]" value="" />
                                <td style="text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input style="position:relative;" class="edp_tsd advertising" type="number"
                                        name="active_student[]" id="active_student" value="3" readonly> <span
                                        style="margin-left: -30px;font-size: 20px;position: absolute;">%</span> <span
                                        style="" class="edp_eq">✕ </span>
                                </td>
                                <td style="white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd advertising" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo  number_format((float)$total_a - $total_mobile_adjust, 2, '.', ''); ?>" readonly> <span
                                        class="edp_eq2">= </span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php  $advertising_promotion_fee = (($total_a - $total_mobile_adjust)/100)*3; $total_1_2_3 += $advertising_promotion_fee;
                                        echo number_format((float)$advertising_promotion_fee, 2, '.', ''); ?>"
                                        readonly><br>
                                </td>
                            </tr>
                        </table>
                        <table class="uk-table uk-table-small">
                            <tr>
                                <td class="uk-width-6-10" style="font-size:18px; font-weight:bold; border:none;">(3)
                                    (Q-DEES
                                    PROGRAMME)<br> SOFTWARE LICENCE FEE PAYABLE</td>
                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Prescriber<br>student</span>
                                </td>

                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Prescriber<br>Rate per</span>
                                </td>
                                <!--<td style="border:none;width:20px;"></td>-->
                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Total(3)</span>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">Software
                                    License Fee-EDP<span class="text-danger"></span>:</td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Software License Fee-EDP" />
                                <input type="hidden" id="subject" name="subject[]" value="" />
                                <input type="hidden" id="programme_package" name="programme_package[]" value="" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php 
                            if($mode=="EDIT"){
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate, amount
                                from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Software License Fee-EDP'";
                                $result=mysqli_query($connection, $sql);
                                $num_row = mysqli_num_rows($result);
                                if ($num_row>0) {
                                    while ($row=mysqli_fetch_assoc($result)) {
                                        $total_EDP_student = $row['active_student'];
                                        // $fees = $row["fee_rate"]; 
                                        // $total_pre_school_kits =  $row['amount'];
                                        // $total_student_C += $level_count;
                                    }
                                }
                                
                            }else{
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m')  group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $num_row=mysqli_num_rows($resultt);
                                $total_EDP_student = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $total_EDP_student = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                            }
                                ?>
                                    <input class="edp_tsd software" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $total_EDP_student;?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd software" type="number" name="fee_rate[]" id="fee_rate"
                                        value="33.00" readonly> <span class="edp_eq2" readonly>= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust5" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php  $total_EDP_student*33; $total_1_2_3 += $total_EDP_student*33;
                                        echo number_format((float)$total_EDP_student*33, 2, '.', ''); ?>"
                                        readonly><br>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">Software License Fee-QF1, QF2, QF3<span class="text-danger"></span>:</td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Software License Fee-QF1, QF2, QF3" />
                                <input type="hidden" id="subject" name="subject[]" value="" />
                                <input type="hidden" id="programme_package" name="programme_package[]" value="" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php 
                            if($mode=="EDIT"){
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate, amount
                                from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Software License Fee-QF1, QF2, QF3'";
                                $result=mysqli_query($connection, $sql);
                                $num_row = mysqli_num_rows($result);
                                if ($num_row>0) {
                                    while ($row=mysqli_fetch_assoc($result)) {
                                        $total_QF1_QF2_QF2_student = $row['active_student'];
                                        // $fees = $row["fee_rate"]; 
                                        // $total_pre_school_kits =  $row['amount'];
                                        // $total_student_C += $level_count;
                                    }
                                }
                                
                            }else{
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level  in ('QF1','QF2','QF3') and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $num_row=mysqli_num_rows($resultt);
                                $total_QF1_QF2_QF2_student = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $total_QF1_QF2_QF2_student = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                            }
                                ?>
                                    <input class="edp_tsd softwarefee" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $total_QF1_QF2_QF2_student;?>" readonly>
                                    <span class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd softwarefee" type="number" name="fee_rate[]" id="fee_rate"
                                        value="33.00" readonly> <span class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust5" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php  $total_QF1_QF2_QF2_student*33; $total_1_2_3 += $total_QF1_QF2_QF2_student*33;
                                        echo number_format((float)$total_QF1_QF2_QF2_student*33, 2, '.', '');?>"
                                        readonly><br>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">Q-dees Mobile Apps–EDP, QF1, QF2, QF3<span class="text-danger"></span>:</td>
                                <input type="hidden" id="form" name="form[]" value="Form1" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Q-dees Mobile Apps–EDP, QF1, QF2, QF3" />
                                <input type="hidden" id="subject" name="subject[]" value="" />
                                <input type="hidden" id="programme_package" name="programme_package[]" value="" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php 
                            if($mode=="EDIT"){
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate, amount
                                from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Q-dees Mobile Apps–EDP, QF1, QF2, QF3'";
                                $result=mysqli_query($connection, $sql);
                                $num_row = mysqli_num_rows($result);
                                if ($num_row>0) {
                                    while ($row=mysqli_fetch_assoc($result)) {
                                        $total_EDP_QF1_QF2_QF2_student = $row['active_student'];
                                        // $fees = $row["fee_rate"]; 
                                        // $total_pre_school_kits =  $row['amount'];
                                        // $total_student_C += $level_count;
                                    }
                                }
                                
                            }else{
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level  in ('EDP','QF1','QF2','QF3') and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $num_row=mysqli_num_rows($resultt);
                                $total_EDP_QF1_QF2_QF2_student = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $total_EDP_QF1_QF2_QF2_student = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                            }
                            $total_EDP_QF1_QF2_QF2_student = 0;
                                ?>
                                    <input class="edp_tsd softwarefee" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $total_EDP_QF1_QF2_QF2_student;?>" readonly>
                                    <span class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd softwarefee" type="number" name="fee_rate[]" id="fee_rate"
                                        value="11.25" readonly> <span class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust5" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php  $total_EDP_QF1_QF2_QF2_student*11.25; $total_1_2_3 += $total_EDP_QF1_QF2_QF2_student*11.25;
                                        echo number_format((float)$total_EDP_QF1_QF2_QF2_student*11.25, 2, '.', '');?>"
                                        readonly><br>
                                </td>
                            </tr>
                            <tr class="">
                                <td colspan="5">*Prescribed Rate multiplied by the Prescribed Student Number and payable
                                    for
                                    11 full months in a calendar year, irrespective of public holidays and
                                    weekends.<br>*The
                                    highest number of Students at the Approved Centre in a month, irrespective of public
                                    holidays and weekends.</td>

                            </tr>

                        </table>
                        <table class="uk-table uk-table-small">
                            <tr class="">
                                <td style=" margin-top:50px; border:none;" class="uk-width-6-10 uk-text-bold">Subtotal
                                    (Form 1)<span class="text-danger"></span>:</td>
                                <td style="border:none;" class="uk-width-1-10"></td>
                                <td style="border:none;" class="uk-width-1-10"></td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust56" type="number" step="0.01" name="grand_total"
                                        id="grand_total" value="<?php echo sprintf('%0.2f', $total_1_2_3); ?>"
                                        readonly><br>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px;" class="uk-width-6-10 uk-text-bold">PAYABLE TO Q-DEES
                                    WORLDWIDE
                                    EDUSYSTEMS (M) SDN BHD (MBB 514196314454)<span class="text-danger"></span>:</td>
                                <td style="" class="uk-width-1-10"></td>
                                <td style="" class="uk-width-1-10"></td>
                                <td style=" text-align:center;" class="uk-width-1-10"></td>
                            </tr>
                        </table>
                        
                    </div>
                </div>

                <?php if(($pdf_name != '' && $_GET['mode'] == 'EDIT') || $pdf_name == '') { ?> 

                <table class="uk-table uk-table-small">
                    <tr>
                        <td class="uk-width-6-10" style="font-size:18px; font-weight:bold; border:none;">TERMS
                            AND
                            CONDITIONS</td>
                    </tr>
                    <tr class="">
                        <td style="border:none;" colspan="5"><i>The Franchisee shall pay to Franchisor Royalty
                                Fee,
                                Advertising & Promotion Fee and Software License Fee, according to the terms and
                                conditions stated in this declaration, in Ringgit Malaysia on a monthly basis,
                                on
                                the 1st day but not later than the 5th day of each month. Late payments shall be
                                levied with an additional 1.5% charge per month. <b>(This Declaration Form shall
                                    constitute as a legally binding contract between the parties and shall be
                                    read
                                    and construed as if the Declaration Form were inserted in the Franchise
                                    Agreement as an integral part of the Franchise Agreement.)</b><i></td>
                    </tr>
                    <tr class="">
                        <td style="border:none; " colspan="5"><i>I <?php echo $operator_name;?>, hereby
                                acknowledge
                                and agree to the above terms and I hereby declare the information above to be
                                accurate, complete and in compliance with the law of Malaysia. I also
                                acknowledge
                                that I have taken all reasonable steps to ensure the same.<i></td>
                    </tr>
                    <tr style="display:none;">
                        <td class="uk-width-1-1" colspan="2">
                            <div class="uk-width-1-1" class="uk-text-bold">Please sign below: *</div>
                            <input class="uk-width-1-1" type="hidden" id="signature_1" name="signature_1"
                                value='<?php echo $row_edit['signature_1']?>'>
                            <div class="uk-width-1-1" id="signature-container"></div>
                            <a class="uk-button mt-2" onclick="clearSignature()">Clear Signature</a>
                            <span id="validationSignature1" style="color: red; display: none;">Please insert
                                signature</span>
                        </td>
                        <!-- <td class="uk-width-5-10"
                        style="font-size:18px; font-weight:bold; border:none; padding-top: 120px;" colspan="4">
                        <span
                            style="border-top: 2px solid; padding-left: 100px; padding-right: 100px;margin-left: 75px;">
                            Signature of Key Operator
                        </span>
                    </td> -->
                        <!-- <td class="uk-width-5-10" style="font-size:18px; font-weight:bold; border:none;text-align: center;">Official Stamp of Franchisee</td> -->
                    </tr>
                    <tr class="" style="display:none;">
                        <td style=" margin-top:50px;border:none;" class="uk-width-5-10 uk-text-bold">Name:
                            <?php echo $operator_name;?> </td>
                    </tr>
                    <tr class="" style="display:none;">
                        <td style=" margin-top:50px;border:none;" class="uk-width-2-10 uk-text-bold">Date:
                            <?php if($mode=="EDIT"){
                            echo $row_edit['submited_date'];
                        }else{
                            echo date("d/m/Y");
                        } ?></td>
                    </tr>
                    <tr style="display:none1;">
                        <td style="margin-top:50px;border:none;" class="uk-width-5-10 uk-text-bold">Centre's
                            Remarks:</td>
                        <td style="border:none;" class="uk-width-5-10"><textarea style="font-weight: normal;" id="remarks_centre_1" name="remarks_centre_1" rows="5" cols="65"><?php echo $row_edit['remarks_centre_1'] ?></textarea></td>
                    </tr>
                    <tr class="uk-text-small" style="display:none1;">
                        <td class="uk-width-5-10 uk-text-bold" style="border:none;font-size: 15px;"></td>
                        <td class="uk-width-5-10" id="dvSSM_file">
                        <a onclick="dlg_declaration_doc('<?php echo $sha_id ?>','1')" class='uk-button uk-button-small form_btn' style="color:white;"><i class="fa fa-check"></i> Add attachment</a>
                                <a style="color:white;" type="button" data-toggle="modal"  data-target="#exampleModal" class="uk-button uk-button-small form_btn" onclick="dlg_declaration_list('<?php echo $sha_id ?>','1')" >View attachment</a>
                            <input style="display:none" class="uk-width-1-1" type="file" name="attachment_1_centre"
                                id="attachment_1_centre" accept=".doc, .docx, .pdf, .png, .jpg, .jpeg, .csv, .xls, .xlsx">

                            <?php
                        if ($row_edit["attachment_1_centre"]!="") {
                        ?>
                            <a href="admin/uploads/<?php echo $row_edit['attachment_1_centre']?>"
                                target="_blank">Click to
                                view document</a>
                            <?php
                        }
                        ?>
                        </td>
                    </tr>
                    <tr style="display:none1;">
                        <td colspan="5" style="font-size:18px; font-weight:bold;"></td>
                    </tr>
                    <tr style="display:none1;">
                        <td style="margin-top:50px;border:none;" class="uk-width-5-10 uk-text-bold">Master's
                            Remarks:</td>
                        <td style="border:none;" class="uk-width-5-10"><textarea style="font-weight: normal;"
                                id="remarks_master1" name="remarks_master1" rows="5" cols="65"
                                readonly><?php echo $row_edit['remarks_master_1'] ?> </textarea></td>
                    </tr >
                    <tr class="uk-text-small" style="display:none1;">
                        <td class="uk-width-5-10 uk-text-bold" style="border:none;font-size: 15px;"></td>
                        <td class="uk-width-5-10" id="dvSSM_file">
                            <!-- <input class="uk-width-1-1" type="file" name="attachment_1_master" id="attachment_1_master"
                            accept=".doc, .docx, .pdf, .png, .jpg, .jpeg"> -->

                            <?php
                        if ($row_edit["attachment_1_master"]!="") {
                        ?>
                            <a href="admin/uploads/<?php echo $row_edit['attachment_1_master']?>"
                                target="_blank">Click to
                                view document</a>
                            <?php
                        }
                        ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" style="font-size:18px; font-weight:bold;"></td>
                    </tr>
                    <tr>
                        <td style="margin-top:50px;border:none;" class="uk-width-5-10 uk-text-bold">Payment
                            Status</td>
                        <td style="border:none;" class="uk-width-5-10 uk-text-bold">
                            <!-- <select name="form_1_status" id="form_1_status" class="uk-width-1-1">
                                <option value="">Payment Status Form 1</option>
                                <option value="Paid" <?php  //if($row_edit['form_1_status']=='Paid') {echo 'selected';}?>>Paid</option>
                                <option value="Pending" <?php  //if($row_edit['form_1_status']=='Pending') {echo 'selected';}?>>Pending</option>
                        </select> -->
                            <?php  echo $row_edit['form_1_status']; ?>
                        </td>
                    </tr>

                </table>

                <?php }else{ ?>
				<table class="uk-table uk-table-small">
					<tr>
						<td align='right'>To pay declaration, please click on the <img src="images/edit.png"> icon at the listing according to month, located at the bottom.</td>
					</tr>
				</table>
				<?php } ?>

                <div class="uk-width-10-10"
                    style="font-size:18px; font-weight:bold; border:none;text-align: right; margin-top: 20px; padding-right: 25px;">
                    <span style="margin-right: 0;<?php if($pdf_name != '') { echo 'display:none;'; } ?>" onclick="myFunction()" href="javascript:void(0);" id="form_2"
                        class="form_2 uk-button uk-button-primary form_btn">NEXT</span>

                    <?php if($pdf_name != '' && isset($_GET['mode']) && $_GET['mode'] == 'EDIT') { ?> 
                        <button type="submit" id="submit" name="submit"
                        class="uk-button uk-button-primary form_btn uk-text-center">SAVE</button>
                    <?php } ?>
                </div>

                <!-- </form> -->
            </div>


            <div style="display:none;" id="frmdeclaration2">
                

                <div class="uk-grid">
                    <div class="uk-width-medium-4-10">
                        <table class="uk-table">
                            <tbody>
                                <tr>
                                    <td class="uk-text-bold">Centre Name:</td>
                                    <td><?php echo getCentreName($centre_code); ?></td>

                                </tr>
                                <tr>
                                    <td class="uk-text-bold">Centre Address:</td>
                                    <td><?php echo $address1; ?></td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="uk-text-bold">Form: 2</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="uk-width-medium-4-10">
                        <table class="uk-table">
                            <tbody>
                                <!-- <tr>
			  <td class="uk-text-bold">Month:</td>
			  <td></td>
			  <td class="uk-text-bold">Year:</td>
			  <td></td>
			</tr> -->
            <tr>
                                <td class="uk-text-bold">Month/Year:</td>
                                <td><?php echo date("F", mktime(0, 0, 0, $month, 10)); //$month; //date("F"); ?>/<?php echo $year; //date("Y"); ?></td>
                            </tr>
                            <tr>
                                <td class="uk-text-bold">Date of Submission:</td>
                                <td> <?php echo $submited_date; //date("d/m/Y"); ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div style="padding-left: 0px; padding-right: 0px;" class="uk-width-medium-2-10">
                        <img src="/images/Qdees-logo-n.png" alt="" class="img_logo">
                    </div>
                </div>
                <div class="uk-grid">
                    <div class="uk-width-medium-10-10">
                        <table class="uk-table uk-table-small">
                            <tr>
                                <td colspan="5" style="font-size:18px; font-weight:bold;">CALCULATION OF GROSS TURNOVER
                                </td>
                            </tr>



                            <!-- from 2 full start  -->

                            <!-- a start -->
                            <tr>
                                <td class="uk-width-6-10" style="font-size:16px; font-weight:bold; border:none;">A:
                                    School Fees <br>Foundation Programme</td>
                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Active student</span>
                                </td>

                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Centre Adjusted Fee<br class="bru">RM</span>
                                </td>
                                <!--<td style="border:none;width:20px;"></td>-->
                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Total <br class="bru">RM</span>
                                </td>
                            </tr>
                            <!--<tr class="">
						<td style="border:none;" style=" margin-top:50px;" class="uk-width-1-10 uk-text-bold"></td>
					  <td style="border:none;text-align:center;" class="uk-width-1-10 ">
						<span>Default Fee</span>
					  </td>
					  <td style="border:none; " class="uk-width-1-10 de_d">
						<span class="default_1">Default Fee</span><br class="bru">
						<span class="default_1">Collection Pattern</span>
					  </td>
					  <td style="border:none;text-align: center;" class="uk-width-1-10">
						<span>Adjust Fee</span>
					  </td>
					  <td style="border:none;text-align: center;" class="uk-width-1-10">
						<span>Adjust Fee <br class="bru">Collection Pattern</span>
					  </td>
					</tr>-->




                            <!--i EDP start-->

                            <tr class="">
                                <td style=" margin-top:50px;border:none; font-size:18px;"
                                    class="uk-width-6-10 uk-text-bold">(i) International English</td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">EDP:</td>
                            </tr>
                            <?php
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as enhanced_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='International English' and subject='EDP'";
                        }else{
							$sql="SELECT fees_structure, ROUND(case 
                            when enhanced_collection = 'Termly' then  enhanced_adjust/3 
                            when enhanced_collection = 'Half Year' then  enhanced_adjust/6 
                            when enhanced_collection = 'Annually' then  enhanced_adjust/12
                            else enhanced_adjust end, 2) as enhanced_adjust 
                             from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_EDP_enhanced_student2 = 0;
                            $total_EDP_school_adjust2=0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php 
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_EDP_enhanced_adjust += $row['amount'];
                                $total_EDP_enhanced_student2 += $level_count;
                            }else{
								
                                $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."'  and fl.foundation_int_english=1  ";
								

                                $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
                                // $sql .= " and case when f.enhanced_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                // when f.enhanced_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.enhanced_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.enhanced_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (1) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_EDP_enhanced_adjust += $level_count * $row['enhanced_adjust'];
                                $total_EDP_enhanced_student2 += $level_count; 
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>       
                                </td>
                                <input type="hidden" id="form2" name="form[]" value="Form2" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="International English" />
                                <input type="hidden" id="subject" name="subject[]" value="EDP" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center; white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd edp_tsdt" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none; white-space:nowrap" class="uk-width-1-10">
                                    <input class="edp_tsd edp_tsdt" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['enhanced_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['enhanced_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_EDP_enhanced_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_EDP_enhanced_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>
                            <!--i EDP start-->

                            <!--i QF1 start-->
                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF1:</td>
                            </tr>
                            <?php
                         if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as enhanced_adjust, amount
                            from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='International English' and subject='QF1'";
                        }else{
							$sql="SELECT fees_structure, ROUND(case 
                            when enhanced_collection = 'Termly' then  enhanced_adjust/3 
                            when enhanced_collection = 'Half Year' then  enhanced_adjust/6 
                            when enhanced_collection = 'Annually' then  enhanced_adjust/12
                            else enhanced_adjust end, 2) as enhanced_adjust 
                             from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF1_enhanced_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php 
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF1_enhanced_adjust += $row['amount'];
                                $total_QF1_enhanced_student2 += $level_count;
                            }else{
                               // $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.enhanced_adjust !='' and fl.foundation_int_english=1  ";

                                $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."'  ";

                                $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
                                // $sql .= " and case when f.enhanced_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                // when f.enhanced_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.enhanced_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.enhanced_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (1) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_QF1_enhanced_adjust += $level_count * $row['enhanced_adjust'];
                                $total_QF1_enhanced_student2 += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>    
                                </td>
                                <input type="hidden" id="form2" name="form[]" value="Form2" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="International English" />
                                <input type="hidden" id="subject" name="subject[]" value="QF1" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd edp_tsdt" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none; white-space:nowrap" class="uk-width-1-10">
                                    <input class="edp_tsd edp_tsdt" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['enhanced_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['enhanced_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF1_enhanced_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF1_enhanced_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>
                            <!--i QF1 end-->
                            <!--i QF2 start-->
                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF2:</td>
                            </tr>
                            <?php
                        $total_QF2_enhanced_student2 = 0;
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as enhanced_adjust, amount
                            from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='International English' and subject='QF2'";
                        }else{
							$sql="SELECT fees_structure, ROUND(case 
                            when enhanced_collection = 'Termly' then  enhanced_adjust/3 
                            when enhanced_collection = 'Half Year' then  enhanced_adjust/6 
                            when enhanced_collection = 'Annually' then  enhanced_adjust/12
                            else enhanced_adjust end, 2) as enhanced_adjust 
                             from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF2_enhanced_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php 
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF2_enhanced_adjust += $row['amount'];
                                $total_QF2_enhanced_student2 += $level_count;
                            }else{
                                // $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.enhanced_adjust !='' and fl.foundation_int_english=1  ";

                                $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."'  ";

                                $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
                                // $sql .= " and case when f.enhanced_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                // when f.enhanced_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.enhanced_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.enhanced_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (1) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_QF2_enhanced_adjust += $level_count * $row['enhanced_adjust'];
                                $total_QF2_enhanced_student2 += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>     
                                </td>
                                <input type="hidden" id="form2" name="form[]" value="Form2" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="International English" />
                                <input type="hidden" id="subject" name="subject[]" value="QF2" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd edp_tsdt" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>"> <span class="edp_eq">✕
                                    </span>
                                </td>
                                <td style="border:none; white-space:nowrap" class="uk-width-1-10">
                                    <input class="edp_tsd edp_tsdt" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['enhanced_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['enhanced_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF2_enhanced_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF2_enhanced_adjust, 2, '.', '') ;  ?>" readonly>
                                </td>
                            </tr>
                            <!--i QF2 end-->



                            <!--i QF3 start-->
                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF3:</td>
                            </tr>
                            <?php
                        $total_QF3_enhanced_adjust = 0;
                         if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as enhanced_adjust, amount
                            from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='International English' and subject='QF3'";
                        }else{
							$sql="SELECT fees_structure, ROUND(case 
                            when enhanced_collection = 'Termly' then  enhanced_adjust/3 
                            when enhanced_collection = 'Half Year' then  enhanced_adjust/6 
                            when enhanced_collection = 'Annually' then  enhanced_adjust/12
                            else enhanced_adjust end, 2) as enhanced_adjust 
                             from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF3_enhanced_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php 
                            
                          if($mode=="EDIT"){
                            $level_count = $row['active_student'];
                            $total_QF3_enhanced_adjust += $row['amount'];
                            $total_QF3_enhanced_student2 += $level_count;
                        }else{
                                //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.enhanced_adjust !='' and fl.foundation_int_english=1  ";

                                $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."'  ";

                                $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
                                // $sql .= " and case when f.enhanced_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                // when f.enhanced_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.enhanced_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.enhanced_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (1) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_QF3_enhanced_adjust += $level_count * $row['enhanced_adjust'];
                                $total_QF3_enhanced_student2 += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>    
                                </td>
                                <input type="hidden" id="form2" name="form[]" value="Form2" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="International English" />
                                <input type="hidden" id="subject" name="subject[]" value="QF3" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd edp_tsdt" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none; white-space:nowrap" class="uk-width-1-10">
                                    <input class="edp_tsd edp_tsdt" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['enhanced_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['enhanced_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF3_enhanced_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF3_enhanced_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>
                            <!--i QF3 end-->




                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student (i)</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php echo $total_enhanced_student2 = $total_EDP_enhanced_student2 + $total_QF1_enhanced_student2 + $total_QF2_enhanced_student2 + $total_QF3_enhanced_student2; $total_student_A2 += $total_enhanced_student2; ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total (i)" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php  $total_enhanced_adjust = $total_EDP_enhanced_adjust + $total_QF1_enhanced_adjust  + $total_QF2_enhanced_adjust + $total_QF3_enhanced_adjust;  
                                        echo number_format((float)$total_enhanced_adjust, 2, '.', ''); ?>"
                                        readonly>
                                </td>
                            </tr>


                            <!-- multimedia start -->
                            <!-- multimedia edp start -->
                            <tr class="">
                                <td style=" margin-top:50px;border:none; font-size:18px;"
                                    class="uk-width-6-10 uk-text-bold">(ii) International Art</td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">EDP:</td>
                            </tr>
                            <?php
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as international_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='International Art' and subject='EDP'";
                        }else{
							$sql="SELECT fees_structure, ROUND(case 
                            when international_collection = 'Termly' then  international_adjust/3 
                            when international_collection = 'Half Year' then  international_adjust/6 
                            when international_collection = 'Annually' then  international_adjust/12
                            else international_adjust end, 2) as international_adjust 
                             from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_EDP_international_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_EDP_international_adjust += $row['amount'];
                                $total_EDP_international_student2 += $level_count;
                            }else{
                            //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.international_adjust !='' and fl.foundation_int_art=1  ";

                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and fl.foundation_int_art=1  ";

                            $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
                                // $sql .= " and case when f.international_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                // when f.international_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.international_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.international_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (1) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_EDP_international_adjust += $level_count * $row['international_adjust'];
                                $total_EDP_international_student2 += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>     
                                </td>
                                <input type="hidden" id="form2" name="form[]" value="Form2" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="International Art" />
                                <input type="hidden" id="subject" name="subject[]" value="EDP" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['international_adjust']?>" readonly> <span
                                        class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['international_adjust'], 2, '.', '');  ?>"
                                        readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_EDP_international_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_EDP_international_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>
                            <!-- multimedia edp end -->
                            <!-- multimedia QF1 start -->

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF1:</td>
                            </tr>
                            <?php
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as international_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='International Art' and subject='QF1'";
                        }else{
							$sql="SELECT fees_structure, ROUND(case 
                            when international_collection = 'Termly' then  international_adjust/3 
                            when international_collection = 'Half Year' then  international_adjust/6 
                            when international_collection = 'Annually' then  international_adjust/12
                            else international_adjust end, 2) as international_adjust 
                             from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF1_international_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                             if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF1_international_adjust += $row['amount'];
                                $total_QF1_international_student2 += $level_count;
                            }else{
                            //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.international_adjust !='' and fl.foundation_int_art=1  ";

                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and fl.foundation_int_art=1  ";

                            $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
                                // $sql .= " and case when f.international_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                // when f.international_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.international_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.international_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (1) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_QF1_international_adjust += $level_count * $row['international_adjust'];
                                $total_QF1_international_student2 += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>     
                                </td>
                                <input type="hidden" id="form2" name="form[]" value="Form2" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="International Art" />
                                <input type="hidden" id="subject" name="subject[]" value="QF1" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['international_adjust']?>" readonly> <span
                                        class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['international_adjust'], 2, '.', '');  ?>"
                                        readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF1_international_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF1_international_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>
                            <!-- multimedia qf1 end -->
                            <!-- multimedia QF2 start -->

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF2:</td>
                            </tr>
                            <?php
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as international_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='International Art' and subject='QF2'";
                        }else{
							$sql="SELECT fees_structure, ROUND(case 
                            when international_collection = 'Termly' then  international_adjust/3 
                            when international_collection = 'Half Year' then  international_adjust/6 
                            when international_collection = 'Annually' then  international_adjust/12
                            else international_adjust end, 2) as international_adjust 
                             from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF2_international_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF2_international_adjust += $row['amount'];
                                $total_QF2_international_student2 += $level_count;
                            }else{
                            // $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.international_adjust !='' and fl.foundation_int_art=1  ";
                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and fl.foundation_int_art=1  ";

                            $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
                                // $sql .= " and case when f.international_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                // when f.international_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.international_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.international_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (1) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_QF2_international_adjust += $level_count * $row['international_adjust'];
                                $total_QF2_international_student2 += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>     
                                </td>
                                <input type="hidden" id="form2" name="form[]" value="Form2" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="International Art" />
                                <input type="hidden" id="subject" name="subject[]" value="QF2" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['international_adjust']?>" readonly> <span
                                        class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['international_adjust'], 2, '.', '');  ?>"
                                        readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF2_international_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF2_international_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>
                            <!-- multimedia QF2 end -->
                            <!-- multimedia QF3 start -->
                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF3:</td>
                            </tr>
                            <?php
                        $total_QF3_international_adjust = 0;
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as international_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='International Art' and subject='QF3'";
                        }else{
							$sql="SELECT fees_structure, ROUND(case 
                            when international_collection = 'Termly' then  international_adjust/3 
                            when international_collection = 'Half Year' then  international_adjust/6 
                            when international_collection = 'Annually' then  international_adjust/12
                            else international_adjust end, 2) as international_adjust 
                             from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF3_international_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                            
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF3_international_adjust += $row['amount'];
                                $total_QF3_international_student2 += $level_count;
                            }else{
                            //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.international_adjust !='' and fl.foundation_int_art=1  ";

                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and fl.foundation_int_art=1  ";

                            $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
                                // $sql .= " and case when f.international_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                // when f.international_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.international_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.international_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (1) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_QF3_international_adjust += $level_count * $row['international_adjust'];
                                $total_QF3_international_student2 += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>        
                                </td>
                                <input type="hidden" id="form2" name="form[]" value="Form2" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="International Art" />
                                <input type="hidden" id="subject" name="subject[]" value="QF3" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['international_adjust']?>" readonly> <span
                                        class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['international_adjust'], 2, '.', '');  ?>"
                                        readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF3_international_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF3_international_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>
                            <!-- multimedia qf3 end -->

                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student (ii)</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php echo $total_international_student2 = $total_EDP_international_student2 + $total_QF1_international_student2 + $total_QF2_international_student2 + $total_QF3_international_student2; $total_student_A2 += $total_international_student2; ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total (ii)" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php  $total_international_adjust = $total_EDP_international_adjust + $total_QF1_international_adjust  + $total_QF2_international_adjust + $total_QF3_international_adjust; 
                                        echo number_format((float)$total_international_adjust, 2, '.', '') ?>"
                                        readonly>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;"></td>
                            </tr>
                            <!-- multimedia end -->

                            <!-- afternoon start -->
                            <tr class="">
                                <td style=" margin-top:50px;border:none; font-size:18px;"
                                    class="uk-width-6-10 uk-text-bold">(iii) Mandarin</td>
                            </tr>
                            <!-- Afternoon edp start -->

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">EDP:</td>
                            </tr>
                            <?php
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as mandarin_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='Mandarin' and subject='EDP'";
                        }else{
							$sql="SELECT fees_structure, ROUND(case 
                            when mandarin_collection = 'Termly' then  mandarin_adjust/3 
                            when mandarin_collection = 'Half Year' then  mandarin_adjust/6 
                            when mandarin_collection = 'Annually' then  mandarin_adjust/12
                            else mandarin_adjust end, 2) as mandarin_adjust 
                             from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_EDP_mandarin_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_EDP_mandarin_adjust += $row['amount'];
                                $total_EDP_mandarin_student2 += $level_count;
                            }else{
                            //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.mandarin_adjust !='' and fl.foundation_int_mandarin=1  ";

                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and fl.foundation_int_mandarin=1  ";

                            $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
                                // $sql .= " and case when f.mandarin_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                // when f.mandarin_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.mandarin_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.mandarin_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (1) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_EDP_mandarin_adjust += $level_count * $row['mandarin_adjust'];
                                $total_EDP_mandarin_student2 += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold"> 
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>  
                                </td>
                                <input type="hidden" id="form2" name="form[]" value="Form2" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Mandarin" />
                                <input type="hidden" id="subject" name="subject[]" value="EDP" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd afternoon" type="number" step="0.01" name="active_student[]"
                                        id="afternoon_s" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd afternoon" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['mandarin_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['mandarin_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_EDP_mandarin_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_EDP_mandarin_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>
                            <!-- Afternoon edp end -->
                            <!-- Afternoon QF1 start -->

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF1:</td>
                            </tr>
                            <?php
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as mandarin_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='Mandarin' and subject='QF1'";
                        }else{
							$sql="SELECT fees_structure, ROUND(case 
                            when mandarin_collection = 'Termly' then  mandarin_adjust/3 
                            when mandarin_collection = 'Half Year' then  mandarin_adjust/6 
                            when mandarin_collection = 'Annually' then  mandarin_adjust/12
                            else mandarin_adjust end, 2) as mandarin_adjust 
                             from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF1_mandarin_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF1_mandarin_adjust += $row['amount'];
                                $total_QF1_mandarin_student2 += $level_count;
                            }else{
                            //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.mandarin_adjust !='' and fl.foundation_int_mandarin=1  ";

                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and fl.foundation_int_mandarin=1  ";

                            $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
                                // $sql .= " and case when f.mandarin_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                // when f.mandarin_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.mandarin_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.mandarin_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (1) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_QF1_mandarin_adjust += $level_count * $row['mandarin_adjust'];
                                $total_QF1_mandarin_student2 += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold"> 
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>
                                </td>
                                <input type="hidden" id="form2" name="form[]" value="Form2" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Mandarin" />
                                <input type="hidden" id="subject" name="subject[]" value="QF1" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd afternoon" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd afternoon" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['mandarin_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['mandarin_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF1_mandarin_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF1_mandarin_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>
                            <!-- Afternoon QF1 end -->
                            <!-- Afternoon QF2 start -->

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF2:</td>
                            </tr>

                            <?php
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as mandarin_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='Mandarin' and subject='QF2'";
                        }else{
							$sql="SELECT fees_structure, ROUND(case 
                            when mandarin_collection = 'Termly' then  mandarin_adjust/3 
                            when mandarin_collection = 'Half Year' then  mandarin_adjust/6 
                            when mandarin_collection = 'Annually' then  mandarin_adjust/12
                            else mandarin_adjust end, 2) as mandarin_adjust 
                             from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF2_mandarin_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF2_mandarin_adjust += $row['amount'];
                                $total_QF2_mandarin_student2 += $level_count;
                            }else{
                            //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.mandarin_adjust !='' and fl.foundation_int_mandarin=1  ";

                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and fl.foundation_int_mandarin=1  ";

                            $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
                                // $sql .= " and case when f.mandarin_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                // when f.mandarin_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.mandarin_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.mandarin_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (1) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_QF2_mandarin_adjust += $level_count * $row['mandarin_adjust'];
                                $total_QF2_mandarin_student2 += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold"> 
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>
                                </td>
                                <input type="hidden" id="form2" name="form[]" value="Form2" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Mandarin" />
                                <input type="hidden" id="subject" name="subject[]" value="QF2" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd afternoon" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd afternoon" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['mandarin_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['mandarin_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF2_mandarin_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF2_mandarin_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>
                            <!-- Afternoon QF2 end -->
                            <!-- Afternoon QF3 start -->

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF3:</td>
                            </tr>

                            <?php
                        $total_QF3_mandarin_adjust = 0;
                         if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as mandarin_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='Mandarin' and subject='QF3'";
                        }else{
							$sql="SELECT fees_structure, ROUND(case 
                            when mandarin_collection = 'Termly' then  mandarin_adjust/3 
                            when mandarin_collection = 'Half Year' then  mandarin_adjust/6 
                            when mandarin_collection = 'Annually' then  mandarin_adjust/12
                            else mandarin_adjust end, 2) as mandarin_adjust 
                             from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF3_mandarin_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                            
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF3_mandarin_adjust += $row['amount'];
                                $total_QF3_mandarin_student2 += $level_count;
                            }else{
                            //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.mandarin_adjust !='' and fl.foundation_int_mandarin=1  ";

                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and fl.foundation_int_mandarin=1  ";

                            $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
                                // $sql .= " and case when f.mandarin_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                // when f.mandarin_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.mandarin_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.mandarin_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (1) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_QF3_mandarin_adjust += $level_count * $row['mandarin_adjust'];
                                $total_QF3_mandarin_student2 += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold"> 
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>
                                </td>
                                <input type="hidden" id="form2" name="form[]" value="Form2" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Mandarin" />
                                <input type="hidden" id="subject" name="subject[]" value="QF3" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd afternoon" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd afternoon" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['mandarin_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['mandarin_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF3_mandarin_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF3_mandarin_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>
                            <!-- Afternoon QF3 end -->

                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php echo $total_mandarin_student2 = $total_EDP_mandarin_student2 + $total_QF1_mandarin_student2 + $total_QF2_mandarin_student2 + $total_QF3_mandarin_student2; $total_student_A2 += $total_mandarin_student2; ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total (iii)" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php  $total_mandarin_adjust = $total_EDP_mandarin_adjust + $total_QF1_mandarin_adjust  + $total_QF2_mandarin_adjust + $total_QF3_mandarin_adjust; 
                                        echo number_format((float)$total_mandarin_adjust, 2, '.', '') ?>"
                                        readonly>
                                </td>
                            </tr>
                            <!-- afternoon end -->
                            <!-- Mobile App start -->
                            <tr class="">
                                <td style=" margin-top:50px;border:none; font-size:18px;"
                                    class="uk-width-6-10 uk-text-bold">(iv) IQ Math</td>
                            </tr>
                            <!-- Mobile App edp start -->

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">EDP:</td>
                            </tr>
                            <?php
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as iq_math_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='IQ Math' and subject='EDP'";
                        }else{
							$sql="SELECT fees_structure, ROUND(case 
                            when iq_math_collection = 'Termly' then  iq_math_adjust/3 
                            when iq_math_collection = 'Half Year' then  iq_math_adjust/6 
                            when iq_math_collection = 'Annually' then  iq_math_adjust/12
                            else iq_math_adjust end, 2) as iq_math_adjust 
                             from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_EDP_iq_math_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                             if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_EDP_iq_math_adjust += $row['amount'];
                                $total_EDP_iq_math_student2 += $level_count;
                            }else{
                            //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.iq_math_adjust !='' and fl.foundation_iq_math=1  ";

                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and fl.foundation_iq_math=1  ";

                            $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
                                // $sql .= " and case when f.iq_math_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                // when f.iq_math_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.iq_math_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.iq_math_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (1) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_EDP_iq_math_adjust += $level_count * $row['iq_math_adjust'];
                                $total_EDP_iq_math_student2 += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold"> 
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>
                                </td>
                                <input type="hidden" id="form2" name="form[]" value="Form2" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="IQ Math" />
                                <input type="hidden" id="subject" name="subject[]" value="EDP" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd afternoon" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd afternoon" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['iq_math_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['iq_math_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_EDP_iq_math_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_EDP_iq_math_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>

                            <!-- Mobile App edp end -->
                            <!-- Mobile App QF1 start -->

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF1:</td>
                            </tr>
                            <?php
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as iq_math_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='IQ Math' and subject='QF1'";
                        }else{
							$sql="SELECT fees_structure, ROUND(case 
                            when iq_math_collection = 'Termly' then  iq_math_adjust/3 
                            when iq_math_collection = 'Half Year' then  iq_math_adjust/6 
                            when iq_math_collection = 'Annually' then  iq_math_adjust/12
                            else iq_math_adjust end, 2) as iq_math_adjust 
                             from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF1_iq_math_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                             if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF1_iq_math_adjust += $row['amount'];
                                $total_QF1_iq_math_student2 += $level_count;
                            }else{
                            //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.iq_math_adjust !='' and fl.foundation_iq_math=1  ";

                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and fl.foundation_iq_math=1  ";

                            $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
                                // $sql .= " and case when f.iq_math_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                // when f.iq_math_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.iq_math_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.iq_math_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (1) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_QF1_iq_math_adjust += $level_count * $row['iq_math_adjust'];
                                $total_QF1_iq_math_student2 += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold"> 
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>    
                                </td>
                                <input type="hidden" id="form2" name="form[]" value="Form2" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="IQ Math" />
                                <input type="hidden" id="subject" name="subject[]" value="QF1" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd afternoon" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd afternoon" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['iq_math_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['iq_math_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF1_iq_math_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF1_iq_math_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>
                            <!-- Mobile App QF1 end -->
                            <!-- Mobile App QF2 start -->

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF2:</td>
                            </tr>

                            <?php
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as iq_math_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='IQ Math' and subject='QF2'";
                        }else{
							$sql="SELECT fees_structure, ROUND(case 
                            when iq_math_collection = 'Termly' then  iq_math_adjust/3 
                            when iq_math_collection = 'Half Year' then  iq_math_adjust/6 
                            when iq_math_collection = 'Annually' then  iq_math_adjust/12
                            else iq_math_adjust end, 2) as iq_math_adjust 
                             from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF2_iq_math_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF2_iq_math_adjust += $row['amount'];
                                $total_QF2_iq_math_student2 += $level_count;
                            }else{
                                //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.iq_math_adjust !='' and fl.foundation_iq_math=1  ";

                                $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and fl.foundation_iq_math=1  ";

                                $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
                                    // $sql .= " and case when f.iq_math_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                    // when f.iq_math_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                    // when f.iq_math_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                    // when f.iq_math_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (1) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                    // end ";

                                    $sql .= "   group by ps.student_entry_level, s.id";
                                    $resultt=mysqli_query($connection, $sql);
                                    
                                    $student_id_array = array();

                                    $level_count=mysqli_num_rows($resultt);
                                    //$level_count = 0;
                                    while ($roww=mysqli_fetch_array($resultt)) {

                                        $student_id_array[] = $roww['id'];

                                        //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                    }

                                    $student_id_array = implode(', ', $student_id_array);

                                    $total_QF2_iq_math_adjust += $level_count * $row['iq_math_adjust'];
                                    $total_QF2_iq_math_student2 += $level_count;
                                }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold"> 
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span> 
                                </td>
                                <input type="hidden" id="form2" name="form[]" value="Form2" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="IQ Math" />
                                <input type="hidden" id="subject" name="subject[]" value="QF2" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd afternoon" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd afternoon" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['iq_math_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['iq_math_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF2_iq_math_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF2_iq_math_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>
                            <!-- Mobile App QF2 end -->
                            <!-- Mobile App QF3 start -->

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF3:</td>
                            </tr>

                            <?php
                        $total_QF3_iq_math_adjust = 0;
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as iq_math_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='IQ Math' and subject='QF3'";
                        }else{
							$sql="SELECT fees_structure, ROUND(case 
                            when iq_math_collection = 'Termly' then  iq_math_adjust/3 
                            when iq_math_collection = 'Half Year' then  iq_math_adjust/6 
                            when iq_math_collection = 'Annually' then  iq_math_adjust/12
                            else iq_math_adjust end, 2) as iq_math_adjust 
                             from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF3_iq_math_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                            
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF3_iq_math_adjust += $row['amount'];
                                $total_QF3_iq_math_student2 += $level_count;
                            }else{
                            //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.iq_math_adjust !='' and fl.foundation_iq_math=1  ";

                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and fl.foundation_iq_math=1  ";

                            $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
                                // $sql .= " and case when f.iq_math_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                // when f.iq_math_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.iq_math_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.iq_math_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (1) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_QF3_iq_math_adjust += $level_count * $row['iq_math_adjust'];
                                $total_QF3_iq_math_student2 += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold"> 
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>
                                </td>
                                <input type="hidden" id="form2" name="form[]" value="Form2" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="IQ Math" />
                                <input type="hidden" id="subject" name="subject[]" value="QF3" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd afternoon" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd afternoon" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['iq_math_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['iq_math_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF3_iq_math_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF3_iq_math_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>
                            <!-- Mobile App QF3 end -->

                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student (iv)</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php echo $total_iq_math_student2 = $total_EDP_iq_math_student2 + $total_QF1_iq_math_student2 + $total_QF2_iq_math_student2 + $total_QF3_iq_math_student2; $total_student_A2 += $total_iq_math_student2; ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total (iv)" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php  $total_iq_math_adjust = $total_EDP_iq_math_adjust + $total_QF1_iq_math_adjust  + $total_QF2_iq_math_adjust + $total_QF3_iq_math_adjust; 
                                        echo number_format((float)$total_iq_math_adjust, 2, '.', '') ?>"
                                        readonly>
                                </td>
                            </tr>
                            <!-- Mobile App end -->

<!-- Robotic Plus start -->
<tr class="">
                                <td style=" margin-top:50px;border:none; font-size:18px;"
                                    class="uk-width-6-10 uk-text-bold">(v) Robotics Plus</td>
                            </tr>
                            <!-- Robotic Plus edp start -->

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">EDP:</td>
                            </tr>
                            <?php
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as robotic_plus_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='Robotic Plus' and subject='EDP'";
                        }else{
							$sql="SELECT fees_structure, ROUND(case 
                            when robotic_plus_collection = 'Termly' then  robotic_plus_adjust/3 
                            when robotic_plus_collection = 'Half Year' then  robotic_plus_adjust/6 
                            when robotic_plus_collection = 'Annually' then  robotic_plus_adjust/12
                            else robotic_plus_adjust end, 2) as robotic_plus_adjust 
                             from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_EDP_robotic_plus_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                             if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_EDP_robotic_plus_adjust += $row['amount'];
                                $total_EDP_robotic_plus_student2 += $level_count;
                            }else{
                            //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.iq_math_adjust !='' and fl.foundation_iq_math=1  ";

                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and fl.robotic_plus=1  ";

                            $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";
                                // $sql .= " and case when f.iq_math_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                // when f.iq_math_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.iq_math_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // when f.iq_math_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (1) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                // end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_EDP_robotic_plus_adjust += $level_count * $row['robotic_plus_adjust'];
                                $total_EDP_robotic_plus_student2 += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold"> 
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>
                                </td>
                                <input type="hidden" id="form2" name="form[]" value="Form2" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Robotic Plus" />
                                <input type="hidden" id="subject" name="subject[]" value="EDP" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd afternoon" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd afternoon" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['robotic_plus_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['robotic_plus_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_EDP_robotic_plus_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_EDP_robotic_plus_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>

                            <!-- Mobile App edp end -->
                            <!-- Mobile App QF1 start -->

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF1:</td>
                            </tr>
                            <?php
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as robotic_plus_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='Robotic Plus' and subject='QF1'";
                        }else{
							$sql="SELECT fees_structure, ROUND(case 
                            when robotic_plus_collection = 'Termly' then  robotic_plus_adjust/3 
                            when robotic_plus_collection = 'Half Year' then  robotic_plus_adjust/6 
                            when robotic_plus_collection = 'Annually' then  robotic_plus_adjust/12
                            else robotic_plus_adjust end, 2) as robotic_plus_adjust 
                             from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF1_robotic_plus_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                             if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF1_robotic_plus_adjust += $row['amount'];
                                $total_QF1_robotic_plus_student2 += $level_count;
                            }else{

                            $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and fl.robotic_plus=1  ";

                            $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_QF1_robotic_plus_adjust += $level_count * $row['robotic_plus_adjust'];
                                $total_QF1_robotic_plus_student2 += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold"> 
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>
                                </td>
                                <input type="hidden" id="form2" name="form[]" value="Form2" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Robotic Plus" />
                                <input type="hidden" id="subject" name="subject[]" value="QF1" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd afternoon" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd afternoon" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['robotic_plus_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['robotic_plus_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF1_robotic_plus_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF1_robotic_plus_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>
                            <!-- Robotic Plus QF1 end -->
                            <!-- Robotic Plus QF2 start -->

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF2:</td>
                            </tr>

                            <?php
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as robotic_plus_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='Robotic Plus' and subject='QF2'";
                        }else{
							$sql="SELECT fees_structure, ROUND(case 
                            when robotic_plus_collection = 'Termly' then  robotic_plus_adjust/3 
                            when robotic_plus_collection = 'Half Year' then  robotic_plus_adjust/6 
                            when robotic_plus_collection = 'Annually' then  robotic_plus_adjust/12
                            else robotic_plus_adjust end, 2) as robotic_plus_adjust 
                             from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF2_robotic_plus_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                               
                                    <?php
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF2_robotic_plus_adjust += $row['amount'];
                                $total_QF2_robotic_plus_student2 += $level_count;
                            }else{

                                $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and fl.robotic_plus=1  ";

                                $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";

                                    $sql .= "   group by ps.student_entry_level, s.id";
                                    $resultt=mysqli_query($connection, $sql);
                                    
                                    $student_id_array = array();

                                    $level_count=mysqli_num_rows($resultt);
                                    //$level_count = 0;
                                    while ($roww=mysqli_fetch_array($resultt)) {

                                        $student_id_array[] = $roww['id'];

                                        //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                    }

                                    $student_id_array = implode(', ', $student_id_array);

                                    $total_QF2_robotic_plus_adjust += $level_count * $row['robotic_plus_adjust'];
                                    $total_QF2_robotic_plus_student2 += $level_count;
                                }
                                ?>
                                 <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold"> 
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>
                                </td>
                                <input type="hidden" id="form2" name="form[]" value="Form2" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Robotic Plus" />
                                <input type="hidden" id="subject" name="subject[]" value="QF2" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd afternoon" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd afternoon" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['robotic_plus_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['robotic_plus_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF2_robotic_plus_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF2_robotic_plus_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>
                            <!-- Robotic Plus QF2 end -->
                            <!-- Robotic Plus QF3 start -->

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF3:</td>
                            </tr>

                            <?php
                            $total_QF3_robotic_plus_adjust = 0;
                            if($mode=="EDIT"){
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as robotic_plus_adjust, amount
                            from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='Robotic Plus' and subject='QF3'";
                            }else{
                                $sql="SELECT fees_structure, ROUND(case 
                                when robotic_plus_collection = 'Termly' then  robotic_plus_adjust/3 
                                when robotic_plus_collection = 'Half Year' then  robotic_plus_adjust/6 
                                when robotic_plus_collection = 'Annually' then  robotic_plus_adjust/12
                                else robotic_plus_adjust end, 2) as robotic_plus_adjust 
                                from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
                            }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF3_robotic_plus_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php
                            
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF3_robotic_plus_adjust += $row['amount'];
                                $total_QF3_robotic_plus_student2 += $level_count;
                            }else{

                                $sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and fl.robotic_plus=1  ";

                                $sql .= " and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') ";

                                $sql .= "   group by ps.student_entry_level, s.id";
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);
                                
                                $total_QF3_robotic_plus_adjust += $level_count * $row['robotic_plus_adjust'];
                                $total_QF3_robotic_plus_student2 += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold"> 
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>
                                </td>
                                <input type="hidden" id="form2" name="form[]" value="Form2" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Robotic Plus" />
                                <input type="hidden" id="subject" name="subject[]" value="QF3" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd afternoon" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd afternoon" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['robotic_plus_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['robotic_plus_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF3_robotic_plus_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF3_robotic_plus_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>
                            <!-- Robotic Plus QF3 end -->

                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student (v)</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php echo $total_robotic_plus_student2 = $total_EDP_robotic_plus_student2 + $total_QF1_robotic_plus_student2 + $total_QF2_robotic_plus_student2 + $total_QF3_robotic_plus_student2; $total_student_A2 += $total_robotic_plus_student2; ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total (v)" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php  $total_robotic_plus_adjust = $total_EDP_robotic_plus_adjust + $total_QF1_robotic_plus_adjust  + $total_QF2_robotic_plus_adjust + $total_QF3_robotic_plus_adjust; 
                                        echo number_format((float)$total_robotic_plus_adjust, 2, '.', '') ?>"
                                        readonly>
                                </td>
                            </tr>
                            <!-- Robotic Plus end -->


                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total A</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_student_A2; ?>" readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total A" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php  $total_a2 = $total_iq_math_adjust+$total_mandarin_adjust+$total_international_adjust+$total_enhanced_adjust+$total_robotic_plus_adjust; 
                                        echo number_format((float)$total_a2, 2, '.', '')  ?>"
                                        readonly>
                                </td>
                            </tr>
                            <!--full day end-->
                            <!-- end school fees A  -->
                            <!-- a end form 2-->

                        </table>
                        <table class="uk-table uk-table-small">
                            <tr>
                                <td class="uk-width-6-10" style="font-size:18px; font-weight:bold; border:none;">B:
                                    Readers
                                </td>
                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Prescriber<br>student</span>
                                </td>

                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Prescriber<br>Rate per</span>
                                </td>
                                <!--<td style="border:none;width:20px;"></td>-->
                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Total(3)</span>
                                </td>
                            </tr>

                            <!--i EDP start-->

                            <tr class="">
                                <td style=" margin-top:50px;border:none; font-size:18px;"
                                    class="uk-width-6-10 uk-text-bold">(i) Link & Think Series:</td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">EDP:</td>
                            </tr>
                            <?php
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as link_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='Link & Think Series' and subject='EDP'";
                        }else{
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
                        }
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_EDP_link_student2 = 0;
                            $total_EDP_link_adjust2=0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php 
                            if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_EDP_link_adjust += $row['amount'];
                                $total_EDP_link_student2 += $level_count;
                            }else{
                                //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.link_adjust !=''  ";

                                $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' ";

                                $sql .= " and case when f.link_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                when f.link_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.link_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.link_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                end ";

                                $sql .= "   group by ps.student_entry_level, s.id) ab";
								
								$sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' ";

                                $sql .= " and case when f.integrated_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                when f.integrated_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.integrated_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.integrated_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
								
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $row['link_adjust'] = 60;

                                $total_EDP_link_adjust += $level_count * $row['link_adjust'];
                                $total_EDP_link_student2 += $level_count; 
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>    
                                </td>
                                <input type="hidden" id="form2" name="form[]" value="Form2" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Link & Think Series" />
                                <input type="hidden" id="subject" name="subject[]" value="EDP" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center; white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd edp_tsdt" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none; white-space:nowrap" class="uk-width-1-10">
                                    <input class="edp_tsd edp_tsdt" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format($row['link_adjust'], 2, '.', ''); ?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['link_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_EDP_link_student2;  ?>" readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_EDP_link_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>
                            <!--i EDP start-->

                            <!--i QF1 start-->
                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF1:</td>
                            </tr>
                            <?php
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as link_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='Link & Think Series' and subject='QF1'";
                        }else{
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF1_link_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php 
                             if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF1_link_adjust += $row['amount'];
                                $total_QF1_link_student2 += $level_count;
                            }else{
                                //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.link_adjust !='' and fl.foundation_int_english=1  ";

                                $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' ";

                                $sql .= " and case when f.link_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                when f.link_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.link_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.link_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                end ";

                                $sql .= "   group by ps.student_entry_level, s.id) ab";

 								$sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' ";

                                $sql .= " and case when f.integrated_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                when f.integrated_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.integrated_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.integrated_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                end ";

                                $sql .= "   group by ps.student_entry_level, s.id";
								
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $row['link_adjust'] = 60;

                                $total_QF1_link_adjust += $level_count * $row['link_adjust'];
                                $total_QF1_link_student2 += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>    
                                </td>
                                <input type="hidden" id="form2" name="form[]" value="Form2" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Link & Think Series" />
                                <input type="hidden" id="subject" name="subject[]" value="QF1" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd edp_tsdt" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none; white-space:nowrap" class="uk-width-1-10">
                                    <input class="edp_tsd edp_tsdt" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format($row['link_adjust'], 2, '.', ''); ?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['link_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF1_link_student2;  ?>" readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF1_link_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>
                            <!--i QF1 end-->
                            <!--i QF2 start-->
                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF2:</td>
                            </tr>
                            <?php
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as link_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='Link & Think Series' and subject='QF2'";
                        }else{
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF2_link_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php 
                             if($mode=="EDIT"){
                                $level_count = $row['active_student'];
                                $total_QF2_link_adjust += $row['amount'];
                                $total_QF2_link_student2 += $level_count;
                            }else{
                                //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.link_adjust !='' and fl.foundation_int_english=1  ";

                                $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."'  ";

                                $sql .= " and case when f.link_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                when f.link_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.link_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.link_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                end ";

                                $sql .= "   group by ps.student_entry_level, s.id) ab";

								$sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' ";

                                $sql .= " and case when f.integrated_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                when f.integrated_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.integrated_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.integrated_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                end ";

                                $sql .= " group by ps.student_entry_level, s.id";								
								
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $row['link_adjust'] = 60;

                                $total_QF2_link_adjust += $level_count * $row['link_adjust'];
                                $total_QF2_link_student2 += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>     
                                </td>
                                <input type="hidden" id="form2" name="form[]" value="Form2" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Link & Think Series" />
                                <input type="hidden" id="subject" name="subject[]" value="QF2" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd edp_tsdt" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none; white-space:nowrap" class="uk-width-1-10">
                                    <input class="edp_tsd edp_tsdt" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format($row['link_adjust'], 2, '.', ''); ?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['link_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF2_link_student2;  ?>" readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF2_link_adjust, 2, '.', '') ;  ?>" readonly>
                                </td>
                            </tr>
                            <!--i QF2 end-->



                            <!--i QF3 start-->
                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF3:</td>
                            </tr>
                            <?php
                        $total_QF3_link_adjust = 0;
                        if($mode=="EDIT"){
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as link_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='Link & Think Series' and subject='QF3'";
                        }else{
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
                        }
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF3_link_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                
                                    <?php 
                            
                                if($mode=="EDIT"){
                                    $level_count = $row['active_student'];
                                    $total_QF3_link_adjust += $row['amount'];
                                    $total_QF3_link_student2 += $level_count;
                                }else{
                                //$sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' and f.link_adjust !='' and fl.foundation_int_english=1  ";

                                $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."'  ";

                                $sql .= " and case when f.link_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                when f.link_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.link_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.link_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                end ";

                                $sql .= "   group by ps.student_entry_level, s.id) ab";
								$sql="SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".mysqli_real_escape_string($connection,$row['fees_structure'])."' ";

                                $sql .= " and case when f.integrated_collection='Monthly' then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'  
                                when f.integrated_collection='Termly' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$term_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.integrated_collection='Half Year' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (".$half_year_array.") or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                when f.integrated_collection='Annually' and ((DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and $month in (3) or DATE_FORMAT(fl.programme_date, '%Y-%m')='$monthyear')) then  DATE_FORMAT(fl.programme_date, '%Y-%m')<='$monthyear' and DATE_FORMAT(fl.programme_date_end, '%Y-%m')>='$monthyear'
                                end ";

                                $sql .= "   group by ps.student_entry_level, s.id";								
                                $resultt=mysqli_query($connection, $sql);
                                
                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $row['link_adjust'] = 60;

                                $total_QF3_link_adjust += $level_count * $row['link_adjust'];
                                $total_QF3_link_student2 += $level_count;
                            }
                                ?>
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" ><?php echo $row['fees_structure']?><span class="text-danger"></span>:</span>     
                                </td>
                                <input type="hidden" id="form2" name="form[]" value="Form2" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Link & Think Series" />
                                <input type="hidden" id="subject" name="subject[]" value="QF3" />
                                <input type="hidden" id="programme_package" name="programme_package[]"
                                    value="<?php echo $row['fees_structure']?>" />
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd edp_tsdt" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none; white-space:nowrap" class="uk-width-1-10">
                                    <input class="edp_tsd edp_tsdt" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format($row['link_adjust'], 2, '.', ''); ?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['link_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <!-- <input class="uk-width-1-1" type="hidden" id="signature_3" name="signature_3"
                                        value='333333333'> -->
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF3_link_student2;  ?>" readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF3_link_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>
                            <!--i QF3 end-->

                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student (i)</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php echo $total_link_student2 = $total_EDP_link_student2 + $total_QF1_link_student2 + $total_QF2_link_student2 + $total_QF3_link_student2; $total_student_A2 += $total_link_student2; ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total (i)" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php  $total_link_adjust = $total_EDP_link_adjust + $total_QF1_link_adjust  + $total_QF2_link_adjust + $total_QF3_link_adjust; 
                                        echo number_format((float)$total_link_adjust, 2, '.', '') ?>"
                                        readonly>
                                </td>
                            </tr>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10"><!-- Total B --></td>
                                <td style="margin-top:50px;border:none;" class="uk-width-1-10"> <input class="total_s"
                                        type="number" style="display:none;" step="0.01" name="school_total_f" id="school_total_f"
                                        value="<?php echo $total_link_student2; ?>" readonly></td>
                                <td style="text-align:center;border:none;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_usage" id="total_usage"
                                        placeholder="Total B" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;border:none;" class="uk-width-1-10">

                                    <input class="total_artt" type="number" step="0.01" name="total_usaget"
                                        id="total_usaget" value="<?php  $total_b2 = $total_link_adjust; 
                                        echo number_format((float)$total_b2, 2, '.', '') ?>"
                                        readonly>
                                </td>
                            </tr>
                            <tr class="">
                                <td colspan="5">Note: as per Student Fee List for each Academic Year as prescribed by
                                    the
                                    Franchisor<br>*as stated in the Operations e-Manual</td>

                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px;border:none; font-size:18px;"
                                    class="uk-width-6-10 uk-text-bold">C: Products</td>
                            </tr>
                            <tr class="">
                                
                                    <?php
                            if($mode=="EDIT"){
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate, amount
                                from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='Foundation e-Reader'";
                                $result=mysqli_query($connection, $sql);
                                $num_row = mysqli_num_rows($result);
                                if ($num_row>0) {
                                    while ($row=mysqli_fetch_assoc($result)) {
                                        $level_count = $row['active_student'];
                                        $fees = $row["fee_rate"]; 
                                        $total_foundation_e_reader =  $row['amount'];
                                        //$total_student_C += $level_count;
                                    }
                                }
                                
                            }else{
                                $sql="SELECT s.id, p.unit_price as fees from student s inner join `collection` c on c.student_id = s.id inner join product p on p.product_code=c.product_code where c.void='0' and c.year = '$year' and s.student_status = 'A' and s.start_date_at_centre <= '$current_date' and s.centre_code='$centre_code' and s.deleted='0' and p.product_name like '%e-Reader Nation%' and month(c.collection_date_time) = $month group by s.id";
                                $resultt=mysqli_query($connection, $sql);
                             
                                $fees = 0;

                                $student_id_array = array();

                                $level_count=mysqli_num_rows($resultt);
                                //$level_count = 0;
                                while ($roww=mysqli_fetch_array($resultt)) {

                                    $student_id_array[] = $roww['id'];

                                    //$level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                    $fees = (empty($roww["fees"]) ? "0" : $roww["fees"]);
                                }

                                $student_id_array = implode(', ', $student_id_array);

                                $total_foundation_e_reader += $level_count * $fees;
                            }
                                ?>

                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <span onClick="dlgDeclarationStudentList('<?php echo $student_id_array; ?>')" class="studentLink" >(i) Foundation e-Reader (*Optional for Home Usage)<span class="text-danger"></span>:</span>
                                </td>
                                <input type="hidden" id="form2" name="form[]" value="Form2" />
                                <input type="hidden" id="fee_structure_mame" name="fee_structure_mame[]"
                                    value="Foundation e-Reader" />
                                <input type="hidden" id="subject" name="subject[]" value="" />
                                <input type="hidden" id="programme_package" name="programme_package[]" value="" />
                                <td style=" text-align:center;border:none;white-space:nowrap;" class="uk-width-1-10">

                                    <input class="edp_tsd pre_school" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd pre_school" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format((float)$fees, 2, '.', '') ?>" readonly> <span class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust2" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$total_foundation_e_reader, 2, '.', ''); ?>" readonly><br>
                                </td>
                            </tr>
                            <tr class="">
                                <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold"></td>
                                <td style="margin-top:50px;" class="uk-width-1-10"></td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_usage" id="total_usage"
                                        placeholder="Total C" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_foun_school" type="number" step="0.01" name="total_foun_school"
                                        id="total_foun_school"
                                        value="<?php  $total_c2 = $total_foundation_e_reader; 
                                        echo number_format((float)$total_c2, 2, '.', ''); ?>" readonly>
                                </td>
                            </tr>
                        </table>
                        <table class="uk-table uk-table-small">
                            <tr>
                                <td class="uk-width-6-10" style="font-size:18px; font-weight:bold; border:none;">(1)
                                    ROYALTY
                                    FEE PAYABLE</td>
                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Percentage</span>
                                </td>

                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Gross Turnover</span>
                                </td>
                                <!--<td style="border:none;width:20px;"></td>-->
                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Total(1)</span>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">Royalty Fee
                                    (5% on Gross Tumover of A(i), A(ii), B(i))<span class="text-danger"></span>:</td>
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input style="position:relative;" class="edp_tsd royaltyi" type="number" step="0.01"
                                        name="royalty_si" id="royalty_si" value="5" readonly><span
                                        style="margin-left: -30px;font-size: 20px;position: absolute;">%</span> <span
                                        style="" class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd royaltyi" type="number" name="royalty_sti" id="royalty_sti"
                                        value="<?php echo number_format((float)$total_enhanced_adjust + $total_international_adjust + $total_link_adjust, 2, '.', ''); ?>"
                                        readonly> <span class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="royalty_tii_r" type="number" step="0.01" name="royalty_ti"
                                        id="royalty_ti"
                                        value="<?php  $royalty_fee1 = (($total_enhanced_adjust + $total_international_adjust + $total_link_adjust)/100)*5; $total_royalty_fee2 +=$royalty_fee1; 
                                        echo number_format((float)$royalty_fee1, 2, '.', ''); ?>"
                                        readonly><br>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">Royalty Fee
                                    (5%
                                    on Gross Tumover of A(iii), A(iv), C(i))<span class="text-danger"></span>:</td>
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input style="position:relative;" class="edp_tsd royaltyii" type="number"
                                        step="0.01" name="royalty_sii" id="royalty_sii" value="5" readonly> <span
                                        style="margin-left: -30px;font-size: 20px;position: absolute;">%</span> <span
                                        style="" class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd royaltyii" type="number" name="royalty_stii" id="royalty_stii"
                                        value="<?php echo number_format((float)$total_mandarin_adjust + $total_iq_math_adjust + $total_c2, 2, '.', ''); ?>"
                                        readonly> <span class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="royalty_tii_r" type="number" step="0.01" name="royalty_tii"
                                        id="royalty_tii"
                                        value="<?php  $royalty_fee2 = (($total_mandarin_adjust + $total_iq_math_adjust + $total_c2)/100)*5; $total_royalty_fee2 +=$royalty_fee2; 
                                        echo number_format((float)$royalty_fee2, 2, '.', '') ?>"
                                        readonly><br>
                                </td>
                            </tr>
                        </table>
                        <table class="uk-table uk-table-small">
                            <tr>
                                <td colspan="5" style="font-size:18px; font-weight:bold;"></td>
                            </tr>
                            <tr>
                                <td class="uk-width-6-10" style="font-size:18px; font-weight:bold; border:none;">(2) A&P
                                    FEE
                                    PAYABLE</td>
                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Percentage</span>
                                </td>

                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Total A</span>
                                </td>
                                <!--<td style="border:none;width:20px;"></td>-->
                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Total(2)</span>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px;" class="uk-width-6-10 uk-text-bold">Advertising & Promotion
                                    Fee(3% on A. only)<span class="text-danger"></span>:</td>
                                <td style=" text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input style="position:relative;" class="edp_tsd advertisingi" type="number"
                                        name="advertising_si" id="advertising_si" value="3" readonly> <span
                                        style="margin-left: -30px;font-size: 20px;position: absolute;">%</span> <span
                                        style="" class="edp_eq">✕ </span>
                                </td>
                                <td style="white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd advertisingi" type="number" name="advertising_sti"
                                        id="advertising_sti" value="<?php echo number_format((float)$total_a2, 2, '.', ''); ?>" readonly> <span
                                        class="edp_eq2">= </span>
                                </td>
                                <td style=" text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="advertising_ti"
                                        id="advertising_ti"
                                        value="<?php  $advertising_promotion_fee2 = ($total_a2/100)*3; 
                                        echo number_format((float)$advertising_promotion_fee2, 2, '.', ''); ?>"
                                        readonly><br>
                                </td>
                            </tr>
                        </table>

                        <table class="uk-table uk-table-small">
                            <tr class="">
                                <td style=" margin-top:50px; border:none;" class="uk-width-6-10 uk-text-bold">Subtotal
                                    (Form 1)<span class="text-danger"></span>:</td>
                                <td style="border:none;" class="uk-width-1-10"></td>
                                <td style="border:none;" class="uk-width-1-10"></td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust56" type="number" step="0.01" name="grand_totali"
                                        id="grand_totali"
                                        value="<?php echo $sub_total_1 = sprintf('%0.2f', $total_1_2_3); ?>"
                                        readonly><br>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px; border:none;" class="uk-width-6-10 uk-text-bold">Subtotal
                                    (Form 2)<span class="text-danger"></span>:</td>
                                <td style="border:none;" class="uk-width-1-10"></td>
                                <td style="border:none;" class="uk-width-1-10"></td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust56" type="number" step="0.01" name="grand_totali"
                                        id="grand_totali"
                                        value="<?php $sub_total_2 = $total_royalty_fee2 + $advertising_promotion_fee2; echo sprintf('%0.2f', $sub_total_2); ?>"
                                        readonly><br>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px; border:none;" class="uk-width-6-10 uk-text-bold">Grand
                                    Total (Form 1 + Form 2)<span class="text-danger"></span>:</td>
                                <td style="border:none;" class="uk-width-1-10"></td>
                                <td style="border:none;" class="uk-width-1-10"></td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust56" type="number" step="0.01" name="grand_totali"
                                        id="grand_totali"
                                        value="<?php $grand_total2 = $sub_total_1 + $sub_total_2; echo sprintf('%0.2f', $grand_total2); ?>"
                                        readonly><br>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px;" class="uk-width-6-10 uk-text-bold">PAYABLE TO Q-DEES
                                    WORLDWIDE
                                    EDUSYSTEMS (M) SDN BHD (MBB 514196314454)<span class="text-danger"></span>:</td>
                                <td style="" class="uk-width-1-10"></td>
                                <td style="" class="uk-width-1-10"></td>
                                <td style=" text-align:center;" class="uk-width-1-10"></td>
                            </tr>
                        </table>
                        <table class="uk-table uk-table-small">
                            <tr>
                                <td class="uk-width-6-10" style="font-size:18px; font-weight:bold; border:none;">TERMS
                                    AND
                                    CONDITIONS</td>
                            </tr>
                            <tr class="">
                                <td style="border:none;" colspan="5"><i>The Franchisee shall pay to Franchisor Royalty
                                        Fee,
                                        Advertising & Promotion Fee and Software License Fee, according to the terms and
                                        conditions stated in this declaration, in Ringgit Malaysia on a monthly basis,
                                        on
                                        the 1st day but not later than the 5th day of each month. Late payments shall be
                                        levied with an additional 1.5% charge per month. <b>(This Declaration Form shall
                                            constitute as a legally binding contract between the parties and shall be
                                            read
                                            and construed as if the Declaration Form were inserted in the Franchise
                                            Agreement as an integral part of the Franchise Agreement.)</b><i></td>
                            </tr>
                            <tr class="">
                                <td style="border:none; " colspan="5"><i>I <?php echo $operator_name;?>, hereby
                                        acknowledge
                                        and agree to the above terms and I hereby declare the information above to be
                                        accurate, complete and in compliance with the law of Malaysia. I also
                                        acknowledge
                                        that I have taken all reasonable steps to ensure the same.<i></td>
                            </tr>
                            <tr style="display:none;">
                                <td class="uk-width-1-1" colspan="2">
                                    <div class="uk-width-1-1" class="uk-text-bold">Please sign below: *</div>
                                    <input class="uk-width-1-1" type="hidden" id="signature_2" name="signature_2"
                                        value='<?php echo $row_edit['signature_2']?>'>
                                    <div class="uk-width-1-1" id="signature-container2"></div>
                                    <a class="uk-button mt-2" onclick="clearSignature2()">Clear Signature</a>
                                    <span id="validationSignature2" style="color: red; display: none;">Please insert
                                        signature</span>
                                </td>
                               
                            </tr>
                            <tr class="" style="display:none;">
                                <td style=" margin-top:50px;border:none;" class="uk-width-5-10 uk-text-bold">Name:
                                    <?php echo $operator_name;?> </td>
                            </tr>
                            <tr class="" style="display:none;">
                                <td style=" margin-top:50px;border:none;" class="uk-width-2-10 uk-text-bold">Date:
                                    <?php echo date("d/m/Y");?></td>
                            </tr>
                            <tr style="display:none1;">
                                <td style="margin-top:50px;border:none;" class="uk-width-5-10 uk-text-bold">Centre's
                                    Remarks:</td>
                                <td style="border:none;" class="uk-width-5-10 uk-text-bold"><textarea
                                        style="font-weight: normal;" id="remarks_centre_2" name="remarks_centre_2"
                                        rows="5" cols="65"><?php echo $row_edit['remarks_centre_2'] ?> </textarea></td>
                            </tr>
                            <tr class="uk-text-small" style="display:none1;">
                                <td class="uk-width-5-10 uk-text-bold" style="border:none;font-size: 15px;"></td>
                                <td class="uk-width-5-10" id="dvSSM_file">
                                    <a onclick="dlg_declaration_doc('<?php echo $sha_id ?>','2')" class='uk-button uk-button-small form_btn' style="color:white;"><i class="fa fa-check"></i> Add attachment</a>
                                            <a style="color:white;" type="button" data-toggle="modal"  data-target="#exampleModal" class="uk-button uk-button-small form_btn" onclick="dlg_declaration_list('<?php echo $sha_id ?>','2')" >View attachment</a>
                                            <input style="display:none"  class="uk-width-1-1" type="file" name="attachment_2_centre"
                                                id="attachment_2_centre" accept=".doc, .docx, .pdf, .png, .jpg, .jpeg, .csv, .xls, , .xlsx">

                                        <?php
                                            if ($row_edit["attachment_2_centre"]!="") {
                                        ?>
                                            <a href="admin/uploads/<?php echo $row_edit['attachment_2_centre']?>"
                                                target="_blank">Click to
                                                view document</a>
                                    <?php
                                        }
                                    ?>
                                </td>
                            </tr>
                            <tr style="display:none1;">
                                <td style="margin-top:50px;border:none;" class="uk-width-5-10 uk-text-bold">Master's
                                    Remarks:</td>
                                <td style="border:none;" class="uk-width-5-10"><textarea style="font-weight: normal;"
                                        id="remarks_master_2" name="remarks_master_2" rows="5" cols="65"
                                        readonly><?php echo $row_edit['remarks_master_2'] ?> </textarea></td>
                            </tr>
                            <tr class="uk-text-small" style="display:none1;">
                                <td class="uk-width-5-10 uk-text-bold" style="border:none;font-size: 15px;"></td>
                                <td class="uk-width-5-10" id="dvSSM_file">
                                    <!-- <input class="uk-width-1-1" type="file" name="attachment_2_master" id="attachment_2_master"
                                    accept=".doc, .docx, .pdf, .png, .jpg, .jpeg"> -->

                                    <?php
                    if ($row_edit["attachment_2_master"]!="") {
                    ?>
                                    <a href="admin/uploads/<?php echo $row_edit['attachment_2_master']?>"
                                        target="_blank">Click to
                                        view document</a>
                                    <?php
                    }
                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="margin-top:50px;border:none;" class="uk-width-5-10 uk-text-bold">Payment
                                    Status</td>
                                <td style="border:none;" class="uk-width-5-10 uk-text-bold">
                                    <!-- <select name="form_2_status" id="form_2_status" class="uk-width-1-1">
                                        <option value="">Payment Status Form 2</option>
                                        <option value="Paid" <?php  //if($row_edit['form_2_status']=='Paid') {echo 'selected';}?>>Paid</option>
                                        <option value="Pending" <?php  //if($row_edit['form_2_status']=='Pending') {echo 'selected';}?>>Pending</option>
                                </select> -->
                                    <?php  echo $row_edit['form_2_status']; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="uk-width-medium-10-10 uk-text-center">
                    <br>
                    <span style="margin-right: 0;" onclick="myFunction()" href="javascript:void(0);" id="form_1"
                        class="form_1 uk-button uk-button-primary form_btn">BACK</span>

                    <?php if($pdf_name == '') { ?> 
                        <button type="submit" id="submit" name="submit"
                        class="uk-button uk-button-primary form_btn uk-text-center">SAVE</button>
                    <?php } ?>

                </div>
            </div>
        </form>
    </div>
</div>
<br>
<div class="uk-width-1-1 myheader">
    <h2 class="uk-text-center myheader-text-color">Searching</h2>
</div>

<form class="uk-form" name="frmCentreSearch" id="frmCentreSearch" method="get">
    <input type="hidden" name="mode" id="mode" value="BROWSE">
    <input type="hidden" name="p" id="p" value="declaration">

    <div class="uk-grid">
      
        <div class="uk-width-2-10 uk-text-small">
            <select name="month" id="month" class="uk-width-1-1">
                <option value="">Select Month</option>
                <option value="1" <?php  if( $_GET["month"]=='1') {echo 'selected';}?>>January</option>
                <option value="2" <?php  if( $_GET["month"]=='2') {echo 'selected';}?>>February</option>
                <option value="3" <?php  if( $_GET["month"]=='3') {echo 'selected';}?>>March</option>
                <option value="4" <?php  if( $_GET["month"]=='4') {echo 'selected';}?>>April</option>
                <option value="5" <?php  if( $_GET["month"]=='5') {echo 'selected';}?>>May</option>
                <option value="6" <?php  if( $_GET["month"]=='6') {echo 'selected';}?>>June</option>
                <option value="7" <?php  if( $_GET["month"]=='7') {echo 'selected';}?>>July</option>
                <option value="8" <?php  if( $_GET["month"]=='8') {echo 'selected';}?>>August</option>
                <option value="9" <?php  if( $_GET["month"]=='9') {echo 'selected';}?>>September</option>
                <option value="10" <?php  if( $_GET["month"]=='10') {echo 'selected';}?>>October</option>
                <option value="11" <?php  if( $_GET["month"]=='11') {echo 'selected';}?>>November</option>
                <option value="12" <?php  if( $_GET["month"]=='12') {echo 'selected';}?>>December</option>
            </select>
        </div>
        <div class="uk-width-2-10 uk-text-small">
            
            <select name="sb" id="sb" class="uk-width-1-1">
                <option value="">Payment Status Form 1</option>
                <option value="Paid" <?php  if( $_GET["sb"]=='Paid') {echo 'selected';}?>>Paid</option>
                <option value="Pending" <?php  if( $_GET["sb"]=='Pending') {echo 'selected';}?>>Pending
                </option>
            </select>
        </div>
        <div class="uk-width-2-10 uk-text-small">
            
            <select name="st" id="st" class="uk-width-1-1">
                <option value="">Payment Status Form 2</option>
                <option value="Paid" <?php  if( $_GET["st"]=='Paid') {echo 'selected';}?>>Paid</option>
                <option value="Pending" <?php  if( $_GET["st"]=='Pending') {echo 'selected';}?>>Pending
                </option>
            </select>

        </div>
       
        <div class="uk-width-3-10">
            <button class="uk-button uk-width-1-1">Search</button>
        </div>
    </div>
</form>
<br>
<div class="uk-margin-right" style="margin-top: 4%;">
    <div class="uk-width-1-1 myheader">
        <h2 class="uk-text-center myheader-text-color">LISTING</h2>
    </div>
    <div class="uk-overflow-container">
        <table class="uk-table" id='mydatatable'>
            <thead>
                <tr class="uk-text-bold uk-text-small">
                    <td>Submitted Date & Time </td>
                    <td>Submitted by </td>
                    <td>Centre Name </td>
                    <td>Month</td>
                    <td>Centre Remarks </td>
                    <td>Form 1 Status</td>
                    <td>Form 2 Status</td>
                    <td>Master Remarks</td>
                    <td>Master Attachments</td>
                    <td>Action</td>
                </tr>
            </thead>

            <tbody>
                <?php
				$st=$_GET['st'];
				$sb=$_GET['sb'];
                $month=$_GET['month'];
            $sql = "SELECT d.*, c.company_name from declaration d INNER JOIN centre c on d.`centre_code`=c.`centre_code` where d.centre_code='$centre_code' ";
		
                if($month!=""){
				  $sql=$sql."and month like '%$month%' ";
			  }
			  if($st!=""){
				  $sql=$sql."and form_2_status like '%$st%' ";
			  }
			  if($sb!=""){
				  $sql=$sql."and form_1_status like '%$sb%' ";
              }
              $sql=$sql."order by d.id desc ";
	    
            $result = mysqli_query($connection, $sql);
						
            $num_row = mysqli_num_rows($result);
			
               
                ?>


                <?php

                    if ($num_row>0) {
                        while ($browse_row=mysqli_fetch_assoc($result)) {
                            $sha1_id=sha1($browse_row["id"]);
                 ?>
                <tr class="uk-text-small">
                    <td><?php echo $browse_row["submited_date"]?></td>
                    
                    <td><?php echo $browse_row["submited_by"]?></td>
                    <td><?php echo $browse_row["company_name"]?></td>
                    <td><?php
                            $monthNum = $browse_row["month"];
                            $monthName = date("F", mktime(0, 0, 0, $monthNum, 10));
                            echo $monthName; // Output: May
                            ?>
                    </td>
                    <td><?php echo $browse_row["remarks_centre_1"]?></td>
                    <td><?php echo $browse_row["form_1_status"]?></td>
                    <td><?php echo $browse_row["form_2_status"]?></td>
                    <td><?php echo $browse_row["remarks_master_1"]?></td>
                    <td><?php echo $browse_row["attachment_1_master"]?></td>
                    <td style="width:120px;padding-left: 25px;">
                        <a href="index.php?p=declaration&id=<?php echo $sha1_id?>&mode=EDIT" data-uk-tooltip
                            title="View"><img src="images/edit.png"></a>
                        &nbsp; &nbsp;

                        <?php if($browse_row['declaration_pdf'] != '' && $browse_row['declaration_pdf'] != null) { ?>

                            <a href="admin/declaration_pdf/<?php echo $browse_row['declaration_pdf']; ?>" data-uk-tooltip download
                            title="Download Pdf"><i class="fas fa-download"></i></a>&nbsp; &nbsp;

                        <?php } ?>
                        

                        <a href="index.php?p=declaration_rpt_view&id=<?php echo $sha1_id?>" data-uk-tooltip
                            title="View"><i class="fa fa-eye"></i></a>
                        
                    </td>
                </tr>
                <?php
                        }
                    } else {
                        echo "<tr><td colspan='8'>No Record Found</td></tr>";
                    }
                    ?>
            </tbody>
        </table>
    </div>
</div>
<form name="frmDeleteRecord" id="frmDeleteRecord" method="get">
    <input type="hidden" name="p" value="declaration">
    <input type="hidden" name="id" id="id" value="">
    <input type="hidden" name="mode" value="DEL">
</form>
<?php
if ($msg!="") {
   echo "<script>UIkit.notify('$msg')</script>";
}
?>
<div id="dlg"></div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
 <div class="modal-dialog" role="document">
   <div class="modal-content">
     <div class="modal-header myheader myheader-text-color">  
       <h5 class="modal-title myheader-text-color" id="exampleModalLabel">Declaration attachment list</h5>
       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
         <span aria-hidden="true">&times;</span>
       </button>
     </div>
     <div class="modal-body">

     </div>
     <div class="modal-footer">
       <button type="button" class="uk-button form_btn" data-dismiss="modal">Close</button>
      <!-- <button type="button" class="btn btn-primary">Save changes</button>-->
     </div>
   </div>
 </div>
</div>

<div class="modal fade" id="studentListModal" tabindex="-1" role="dialog" aria-labelledby="studentListModalLabel" aria-hidden="true">
 <div class="modal-dialog" role="document">
   <div class="modal-content">
     <div class="modal-header myheader myheader-text-color">  
       <span class="ui-dialog-title" id="ui-id-1">Student list</span>
       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
         <span aria-hidden="true">&times;</span>
       </button>
     </div>
     <div class="modal-body">

     </div>
     <div class="modal-footer">
       <button type="button" class="uk-button form_btn" data-dismiss="modal">Close</button>
     </div>
   </div>
 </div>
</div>

<script src="lib/sign/js/jquery.signature.js"></script>
<script type="text/javascript" src="lib/sign/js/jquery.ui.touch-punch.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.min.css">
<script type="text/javascript">
function dlg_declaration_doc(sha_id,form) {
   $.ajax({
      url : "admin/dlgDeclarationDoc.php",
      type : "POST", 
	  data : "sha_id="+sha_id+"&form="+form,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         $("#dlg").html(response);
         $("#dlg").dialog({
            dialogClass:"no-close",
            height:'auto', 
            width:'600',
            title:"Declaration attachment",
         });
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}
function dlg_declaration_list(sha_id,form) {
   $.ajax({
      url : "admin/do_declaration_doc_ajax.php",
      type : "POST", 
	    data : "sha_id="+sha_id+"&form="+form,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         $("#exampleModal .modal-body").html(response);
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}
function printDiv(print_1) {
    $(".navbar").remove();    
    $(".print_c").hide(); 
    $("#btnPrint").hide(); 
    $(".sidebar").hide();  
    $(".page_title").remove();  
    $(".myheader").remove(); 
    $(".footer").remove();  
    $("#form_1").remove(); 
    $("#form_2").remove(); 
    $("#submit").remove(); 
    $("#frmCentreSearch").remove();
    $("#mydatatable_wrapper").remove();
    window.print();
}
window.onafterprint = function() {
    window.location.reload(true);
};

function doDeleteRecord(id) {
    UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function() {
        $("#id").val(id);
        $("#frmDeleteRecord").submit();
    });
}
$("#form_1").click(function() {
    $("#frmdeclaration").show();
    $("#frmdeclaration2").hide();
});
$(".form_2").click(function() {
    $("#frmdeclaration").hide();
    $("#frmdeclaration2").show();
    /* if ($("#signature_1").val() != "") {
        $("#frmdeclaration").hide();
        $("#frmdeclaration2").show();
    } else {
        $("#validationSignature1").show();
        UIkit.notify($("#validationSignature1").text());
    } */

});
$("#submit").click(function() {
    /* if ($("#signature_2").val() == "") {
        $("#validationSignature2").show();
        UIkit.notify($("#validationSignature2").text());
        return false;
    } */

});

function dlgDeclarationStudentList(student_id) {
    $.ajax({
        url : "admin/dlgDeclarationStudentList.php",
        type : "POST", 
	    data : "student_id="+student_id,
        beforeSend : function(http) {
        },
        success : function(response, status, http) {
            $('#studentListModal').modal('show');
            $("#studentListModal .modal-body").html(response);
        },
        error : function(http, status, error) {
            UIkit.notify("Error:"+error);
        }
   });
}

function myFunction() {
    var elmnt = document.getElementById("frmdeclaration2");
    elmnt.scrollIntoView();
    window.scrollTo(0, 0);
    return false;
}

function clearSignature() {
    $('#signature-container').signature('clear');
}

function clearSignature2() {
    $('#signature-container2').signature('clear');
}
$(document).ready(function() {
    // $(".").css("border", "red solid 1px"); 
    $("#signature-container").css("border", "red solid 1px");
    $("#signature-container2").css("border", "red solid 1px");

    // $('.').focus(function() {
    //    $(this).css("border", "");
    // });

    $("#signature-container").on('click', function(e) {
        $("#signature-container").css("border", "");
    });
    $("#signature-container2").on('click', function(e) {
        $("#signature-container2").css("border", "");
    });

    $("#signature-container").signature({
        background: '#FFFFFF',
        syncField: '#signature_1'
    });
    if ($("#signature_1").val()) {
        $("#signature-container").signature('draw', $("#signature_1").val());
    }

    $("#signature-container2").signature({
        background: '#FFFFFF',
        syncField: '#signature_2'
    });
    if ($("#signature_2").val()) {
        $("#signature-container2").signature('draw', $("#signature_2").val());
    }
})
$(document).ready(function() {
    $('#mydatatable').DataTable({
        "order": [[ 0, "desc" ]],
        'columnDefs': [{
             'targets': [8], // column index (start from 0)
             'orderable': false, // set orderable false for selected columns
        }]
    });
});
</script>
<style type="text/css">
#mydatatable_length {
    display: none;
}

#mydatatable_filter {
    display: none;
}

/*#mydatatable_paginate{float:initial;text-align:center}*/
#mydatatable_paginate .paginate_button {
    display: inline-block;
    min-width: 16px;
    padding: 3px 5px;
    line-height: 20px;
    text-decoration: none;
    -moz-box-sizing: content-box;
    box-sizing: content-box;
    text-align: center;
    background: #f7f7f7;
    color: #444444;
    border: 1px solid rgba(0, 0, 0, 0.2);
    border-bottom-color: rgba(0, 0, 0, 0.3);
    background-origin: border-box;
    background-image: -webkit-linear-gradient(top, #ffffff, #eeeeee);
    background-image: linear-gradient(to bottom, #ffffff, #eeeeee);
    text-shadow: 0 1px 0 #ffffff;
    margin-left: 3px;
    margin-right: 3px
}

#mydatatable_paginate .paginate_button.current {
    background: #009dd8;
    color: #ffffff !important;
    border: 1px solid rgba(0, 0, 0, 0.2);
    border-bottom-color: rgba(0, 0, 0, 0.4);
    background-origin: border-box;
    background-image: -webkit-linear-gradient(top, #00b4f5, #008dc5);
    background-image: linear-gradient(to bottom, #00b4f5, #008dc5);
    text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.2);
}

#mydatatable_filter {
    width: 100%
}

#mydatatable_filter label {
    width: 100%;
    display: inline-flex
}

#mydatatable_filter label input {
    height: 30px;
    width: 100%;
    padding: 4px 6px;
    border: 1px solid #dddddd;
    background: #ffffff;
    color: #444444;
    -webkit-transition: all linear 0.2s;
    transition: all linear 0.2s;
    border-radius: 4px;
}

/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Firefox */
input[type=number] {
    -moz-appearance: textfield;
}

.default_1 {
    margin-left: -21%;
}

.de_d {
    border: none;
    text-align: center;
    position: absolute;
    width: 240px;
}

.edp_tsd {
    max-width: 90% !important;
}

.edp_tsd {
    max-width: 90% !important;
    text-align: left;
    float: left;
}

.courier {
    max-width: 90% !important;
    text-align: left;
    float: left;
}

span.edp_eq {
    font-size: 18px;
    position: absolute;
    padding-top: 4px;
}

span.edp_eq2 {
    margin-left: 10px;
    font-size: 23px;
    position: absolute;
}

#total_b::-webkit-input-placeholder {
    /* Chrome/Opera/Safari */
    color: #000;
}

#total_b::-moz-placeholder {
    /* Firefox 19+ */
    color: #000;
}

#total_b:-ms-input-placeholder {
    /* IE 10+ */
    color: #000;
}

#total_b:-moz-placeholder {
    /* Firefox 18- */
    color: #000;
}

#total_b {
    text-align: center;
    border: none;
}

#total_a::-webkit-input-placeholder {
    /* Chrome/Opera/Safari */
    color: #000;
}

#total_a::-moz-placeholder {
    /* Firefox 19+ */
    color: #000;
}

#total_a:-ms-input-placeholder {
    /* IE 10+ */
    color: #000;
}

#total_a:-moz-placeholder {
    /* Firefox 18- */
    color: #000;
}

#total_a {
    text-align: center;
    border: none;
}

#total_c2::-webkit-input-placeholder {
    /* Chrome/Opera/Safari */
    color: #000;
}

#total_c2::-moz-placeholder {
    /* Firefox 19+ */
    color: #000;
}

#total_c2:-ms-input-placeholder {
    /* IE 10+ */
    color: #000;
}

#total_c2:-moz-placeholder {
    /* Firefox 18- */
    color: #000;
}

#total_c2 {
    text-align: center;
    border: none;
}

#total_inter::-webkit-input-placeholder {
    /* Chrome/Opera/Safari */
    color: #000;
}

#total_inter::-moz-placeholder {
    /* Firefox 19+ */
    color: #000;
}

#total_inter:-ms-input-placeholder {
    /* IE 10+ */
    color: #000;
}

#total_inter:-moz-placeholder {
    /* Firefox 18- */
    color: #000;
}

#total_inter {
    text-align: center;
    border: none;
}

#total_art::-webkit-input-placeholder {
    /* Chrome/Opera/Safari */
    color: #000;
}

#total_art::-moz-placeholder {
    /* Firefox 19+ */
    color: #000;
}

#total_art:-ms-input-placeholder {
    /* IE 10+ */
    color: #000;
}

#total_art:-moz-placeholder {
    /* Firefox 18- */
    color: #000;
}

#total_art {
    text-align: center;
    border: none;
}

#total_usage::-webkit-input-placeholder {
    /* Chrome/Opera/Safari */
    color: #000;
}

#total_usage::-moz-placeholder {
    /* Firefox 19+ */
    color: #000;
}

#total_usage:-ms-input-placeholder {
    /* IE 10+ */
    color: #000;
}

#total_usage:-moz-placeholder {
    /* Firefox 18- */
    color: #000;
}

#total_usage {
    text-align: center;
    border: none;
}

#total_iq::-webkit-input-placeholder {
    /* Chrome/Opera/Safari */
    color: #000;
}

#total_iq::-moz-placeholder {
    /* Firefox 19+ */
    color: #000;
}

#total_iq:-ms-input-placeholder {
    /* IE 10+ */
    color: #000;
}

#total_iq:-moz-placeholder {
    /* Firefox 18- */
    color: #000;
}

#total_iq {
    text-align: center;
    border: none;
}

#total_man::-webkit-input-placeholder {
    /* Chrome/Opera/Safari */
    color: #000;
}

#total_man::-moz-placeholder {
    /* Firefox 19+ */
    color: #000;
}

#total_iq:-ms-input-placeholder {
    /* IE 10+ */
    color: #000;
}

#total_man:-moz-placeholder {
    /* Firefox 18- */
    color: #000;
}

#total_man {
    text-align: center;
    border: none;
}

#total_iq2::-webkit-input-placeholder {
    /* Chrome/Opera/Safari */
    color: #000;
}

#total_iq2::-moz-placeholder {
    /* Firefox 19+ */
    color: #000;
}

#total_iq2:-ms-input-placeholder {
    /* IE 10+ */
    color: #000;
}

#total_iq2:-moz-placeholder {
    /* Firefox 18- */
    color: #000;
}

#total_iq2 {
    text-align: center;
    border: none;
}

.kbw-signature {
    width: 400px;
    height: 150px;
    border: 1px solid #999
}

#frmdeclaration2 {}
</style>

<?php }} ?>
