<?php 
// session_start();

// if ($_SESSION["isLogin"]==1) {
// 	//print_r($_SESSION); die;
//  if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "SalesEdit|SalesView"))) {
//       include_once("mysql.php");
//       foreach ($_GET as $key=>$value) {
//          $key=$value;
//       }
	  
// $mode=$_GET["mode"];
// $centre_code = $_SESSION["CentreCode"];
// $get_sha1_fee_id=$_POST['fee_id'];
// $from_date= $_POST['from_date'];
// $from_date=convertDate2ISO($from_date);



 include_once("mysql.php");
   include_once("admin/functions.php");
 $centre_code = $_SESSION['CentreCode'];
 $year = $_SESSION['Year'];
 $month = date('m');;
 //echo $centre_code;
function getCentreName($centre_code)
   {
      global $connection;

      $sql = "SELECT franchisee_name from centre_franchisee_name where centre_code='$centre_code'";
	  //echo $sql;
      $result = mysqli_query($connection, $sql);
      $row = mysqli_fetch_assoc($result);
      $num_row = mysqli_num_rows($result);

      if ($num_row > 0) {
         return $row["franchisee_name"];
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
    font-size: 1.2rem;
    font-weight: bold;
    background: #fff;
    cursor: pointer;
}

.form_2 {
    padding: 9px 15px;
    border-radius: 10px;
    box-shadow: 0px 2px 3px 0px #00000021 !important;
    font-size: 1.2rem;
    font-weight: bold;
    background: #fff;
    margin-right: 82%;
    cursor: pointer;
}
</style>

<span id="form_1" class="form_1">Form 1</span>
<span id="form_2" class="form_2">Form 2</span>

<span>
    <span class="page_title"><img src="/images/title_Icons/Visitor Reg.png">DECLARATION OF Q-DEES AND PAYMENTS
        REPORT</span>
</span><br>
<div>
    <button style="margin-left: 30px;margin-bottom: 13px;" onclick="printDiv('print_1')"
        class="uk-button">Print</button>
</div>
<div id="print_1" class="uk-margin-right">

    <div class="uk-width-1-1 myheader">
        <h2 class="uk-text-center myheader-text-color">DECLARATION OF Q-DEES AND PAYMENTS REPORT</h2>
    </div>
    <div class="uk-form uk-form-small">

        <form name="frmdeclaration" id="frmdeclaration" method="post"
            action="index.php?p=fee_structure_setting&amp;mode=SAVE">
            <div class="uk-grid">
                <div class="uk-width-medium-5-10">
                    <table class="uk-table">
                        <tbody>
                            <tr>
                                <td class="uk-text-bold">Franchisee's Name:</td>
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
                <div class="uk-width-medium-3-10">
                    <table class="uk-table">
                        <!-- <tbody><tr>
			  <td class="uk-text-bold">Month:</td> 
			  <td></td>
			  <td class="uk-text-bold">Year:</td>
			  <td></td>
			</tr> -->
                        <tr>
                            <td class="uk-text-bold">Date of submission</td>
                            <td><?php echo $today_date;?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div style="padding-left: 0px; padding-right: 0px;" class="uk-width-medium-2-10">
                    <img src="images/Artboard – 4.png" alt="" class="img_logo" style="width: 80%;">
                </div>
            </div>
            <div class="uk-grid">
                <div class="uk-width-medium-10-10">
                    <table class="uk-table uk-table-small">
                        <tr>
                            <td colspan="5" style="font-size:18px; font-weight:bold;">CALCULATION OF GROSS TURNOVER</td>
                        </tr>
                        <!-- a start -->
                        <tr>
                            <td class="uk-width-6-10" style="font-size:16px; font-weight:bold; border:none;">A: School Fees <br>Foundation Programme</td>
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
                                class="uk-width-6-10 uk-text-bold">(i) School Fees</td>
                        </tr>
                        <tr class="">
                            <td style=" margin-top:20px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">EDP:</td>
                        </tr>
                        <?php
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_EDP_student = 0;
                            $total_EDP_school_adjust=0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center; white-space:nowrap;" class="uk-width-1-10">
                            <?php 
                                $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".$row['fees_structure']."' and f.school_adjust !='' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_EDP_school_adjust += $level_count * $row['school_adjust'];
                                $total_EDP_student += $level_count;
                                ?>
                                <input class="edp_tsd edp_tsdt" type="number" step="0.01" name="edp_EDP" id="edp_EDP"
                                    value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none; white-space:nowrap" class="uk-width-1-10">
                                <input class="edp_tsd edp_tsdt" type="number" name="edp_EDP_st" id="edp_EDP_st"
                                    value="<?php echo $row['school_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="edp_EDP_t[]" id="edp_EDP_t"
                                    value="<?php echo $level_count * $row['school_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php 
							}
								}
						?>
                        <tr class="">
                            <td style="margin-top:50px; padding-top: 11px; text-align: right;" class="uk-width-1-10">Total Student</td>
                            <td class="uk-width-1-10"><input class="total_s" type="number" step="0.01" name="school_total_f" id="school_total_f" value="<?php echo $total_EDP_student;  ?>" readonly></td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_EDP_school_adjust;  ?>" readonly>
                            </td>
                        </tr>
                        <!--i EDP start-->

                        <!--i QF1 start-->
                        <tr class="">
                            <td style=" margin-top:20px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">QF1:</td>
                        </tr>
                        <?php
							$sql1="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result1=mysqli_query($connection, $sql1);
							$num_row = mysqli_num_rows($result1);
                            $total_QF1_school_adjust = 0;
                            $total_QF1_student = 0;
							if ($num_row>0) {
								while ($row1=mysqli_fetch_assoc($result1)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                <?php echo $row1['fees_structure']?><span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php 
                                $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".$row1['fees_structure']."' and f.school_adjust !='' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_QF1_school_adjust += $level_count * $row1['school_adjust'];
                                $total_QF1_student += $level_count;
                                ?>
                                <input class="edp_tsd edp_tsdq1" type="number" step="0.01" name="QF1_s" id="QF1_s"
                                    value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none; white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd edp_tsdq1" type="number" name="QF1_st" id="QF1_st"
                                    value="<?php echo $row1['school_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="QF1_t" id="QF1_t" value="<?php echo $level_count * $row1['school_adjust'];  ?>"
                                    readonly><br>
                            </td>
                        </tr>
                        <?php 
								}
							}
						?>

                        <tr class="">
                        <td style="margin-top:50px; padding-top: 11px; text-align: right;" class="uk-width-1-10">Total Student</td>
                            <td class="uk-width-1-10"><input class="total_s" type="number" step="0.01" name="school_total_f" id="school_total_f" value="<?php echo $total_QF1_student;  ?>" readonly></td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_QF1_school_adjust;  ?>">
                            </td>
                        </tr>
                        <!--i QF1 end-->
                        <!--i QF2 start-->
                        <tr class="">
                            <td style=" margin-top:20px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">QF2:</td>
                        </tr>
                        <?php
							$sql2="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result2=mysqli_query($connection, $sql2);
							$num_row = mysqli_num_rows($result2);
                            $total_QF2_school_adjust = 0;
                            $total_QF2_student = 0;
							if ($num_row>0) {
								while ($row2=mysqli_fetch_assoc($result2)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                <?php echo $row2['fees_structure']?><span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php 
                                $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".$row2['fees_structure']."' and f.school_adjust !='' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_QF2_school_adjust += $level_count * $row2['school_adjust'];
                                $total_QF2_student += $level_count;
                                ?>
                                <input class="edp_tsd edp_tsdq2" type="number" step="0.01" name="QF2_s" id="QF2_s"
                                    value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd edp_tsdq2" type="number" name="QF2_st" id="QF2_st"
                                    value="<?php echo $row2['school_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="QF2_t" id="QF2_t" value="<?php echo $level_count * $row2['school_adjust'];  ?>"
                                    readonly><br>
                            </td>
                        </tr>
                        <?php } }?>
                        <tr class="">
                        <td style="margin-top:50px; padding-top: 11px; text-align: right;" class="uk-width-1-10">Total Student</td>
                            <td class="uk-width-1-10"><input class="total_s" type="number" step="0.01" name="school_total_f" id="school_total_f" value="<?php echo $total_QF2_student;  ?>" readonly></td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_QF2_school_adjust;  ?>">
                            </td>
                        </tr>
                        <!--i QF2 end-->



                        <!--i QF3 start-->
                        <tr class="">
                            <td style=" margin-top:20px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">QF3:</td>
                        </tr>
                        <?php
							$sql3="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result3=mysqli_query($connection, $sql3);
							$num_row = mysqli_num_rows($result3);
                            $total_QF3_school_adjust = 0;
                            $total_QF3_student = 0;
							if ($num_row>0) {
								while ($row3=mysqli_fetch_assoc($result3)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                <?php echo $row3['fees_structure']?><span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php 
                                $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".$row3['fees_structure']."' and f.school_adjust !='' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_QF3_school_adjust += $level_count * $row3['school_adjust'];
                                $total_QF3_student += $level_count;
                                ?>
                                <input class="edp_tsd edp_tsdq3" type="number" step="0.01" name="QF3_s" id="QF3_s"
                                    value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd edp_tsdq3" type="number" name="QF3_st" id="QF3_st"
                                    value="<?php echo $row3['school_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="QF3_t" id="QF3_t" value="<?php echo $level_count * $row2['school_adjust'];  ?>"
                                    readonly><br>
                            </td>
                        </tr>
                        <?php }}?>
                        <tr class="">
                            <td style="margin-top:50px; padding-top: 11px; text-align: right;" class="uk-width-1-10">Total Student</td>
                            <td class="uk-width-1-10">
                                <input class="total_s" type="number" step="0.01" name="school_total_f" id="school_total_f" value="<?php echo $total_QF3_student;  ?>" readonly>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_QF3_school_adjust;  ?>">
                            </td>
                        </tr>
                        <!--i QF3 end-->




                        <tr class="">
                        <td style="margin-top:50px; padding-top: 11px; text-align: right;" class="uk-width-1-10">Total Student (i)</td>
                            <td class="uk-width-1-10">
                                <input class="total_s" type="number" step="0.01" name="school_total_f" id="school_total_f" value="<?php echo $total_EDP_student+$total_QF1_student+$total_QF2_student+$total_QF3_student;  ?>" readonly>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total (i)" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_school_adjust = $total_EDP_school_adjust + $total_QF1_school_adjust  + $total_QF2_school_adjust + $total_QF3_school_adjust;  ?>">
                            </td>
                        </tr>
                        <?php $total_a += $total_school_adjust; ?>
                        <!-- <tr class="">
					   <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">(ii) Waived School Fees</td>
					</tr>    -->
                        <!-- <tr class="">
					   <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">Key Operator/ Principal<span class="text-danger"></span>:</td>
					  <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">	
						 <input class="edp_tsd waived_school" type="number" step="0.01" name="key_operator" id="key_operator" value=""> <span class="edp_eq">✕ </span>
					  </td>
					  <td style="border:none;white-space:nowrap;" class="uk-width-1-10">	
						 <input class="edp_tsd waived_school" type="number" name="key_operator_st" id="key_operator_st" value=""> <span class="edp_eq2">= </span>
					  </td>
					  <td style="border:none; text-align:center;" class="uk-width-1-10">	
						 <input class="school_adjust" type="number" step="0.01" name="key_operator_t" id="key_operator_t" value="" readonly><br>
					  </td>
					</tr> -->
                        <!-- <tr class="">
					   <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">Teachers<span class="text-danger"></span>:</td>
					  <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">	
						 <input class="edp_tsd teachers" type="number" step="0.01" name="teachers_s" id="teachers_s" value=""> <span class="edp_eq">✕ </span>
					  </td>
					  <td style="border:none;white-space:nowrap;" class="uk-width-1-10">	
						 <input class="edp_tsd teachers" type="number" name="teachers_st" id="teachers_st" value=""> <span class="edp_eq2">= </span>
					  </td>
					  <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">	
						 <input class="school_adjust" type="number" step="0.01" name="teachers_t" id="teachers_t" value="" readonly><br>
					  </td>
					</tr>
					<tr class="">
					   <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">Sliblings(QF1-QF3)<span class="text-danger"></span>:</td>
					  <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">	
						 <input class="edp_tsd sliblings" type="number" step="0.01" name="sliblings_s" id="sliblings_s" value=""> <span class="edp_eq">✕ </span>
					  </td>
					  <td style="border:none;white-space:nowrap;" class="uk-width-1-10">	
						 <input class="edp_tsd sliblings" type="number" name="sliblings_st" id="sliblings_st" value=""> <span class="edp_eq2">= </span>
					  </td>
					  <td style="border:none; text-align:center;" class="uk-width-1-10">	
						 <input class="school_adjust" type="number" step="0.01" name="sliblings_t" id="sliblings_t" value="" readonly><br>
					  </td>
					</tr>
					<tr class="">
					   <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">Siblings(EDP)<span class="text-danger"></span>:</td>
					  <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">	
						 <input class="edp_tsd siblings" type="number" step="0.01" name="siblings_s" id="siblings_s" value=""> <span class="edp_eq">✕ </span>
					  </td>
					  <td style="border:none;white-space:nowrap;" class="uk-width-1-10">	
						 <input class="edp_tsd siblings" type="number" name="siblings_st" id="siblings_st" value=""> <span class="edp_eq2">= </span>
					  </td>
					  <td style="border:none; text-align:center;" class="uk-width-1-10">	
						 <input class="school_adjust" type="number" step="0.01" name="siblings_t" id="siblings_t" value="" readonly><br>
					  </td>
					</tr>
					<tr class="">
						<td style=" margin-top:50px;border:none;"></td>
					</tr> -->


                        <!-- multimedia start -->
                        <!-- multimedia edp start -->
                        <tr class="">
                            <td style=" margin-top:50px;border:none; font-size:18px;"
                                class="uk-width-6-10 uk-text-bold">(ii) Multimedia</td>
                        </tr>
                        <tr class="">
                            <td style=" margin-top:20px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">EDP:</td>
                        </tr>
                        <?php
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
                            $total_EDP_multimedia_adjust = 0;
                            $total_EDP_multimedia_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".$row['fees_structure']."' and f.multimedia_adjust !='' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_EDP_multimedia_adjust += $level_count * $row['multimedia_adjust'];
                                $total_EDP_multimedia_student += $level_count;
                                ?>
                                <input class="edp_tsd multimedia" type="number" step="0.01" name="multimedia_s"
                                    id="multimedia_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd multimedia" type="number" name="multimedia_st" id="multimedia_st"
                                    value="<?php echo $row['multimedia_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="multimedia_t"
                                    id="multimedia_t" value="<?php echo $level_count * $row['multimedia_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php
							}
							}
						?>
                        <tr class="">
                            <td style="margin-top:50px; padding-top: 11px; text-align: right;" class="uk-width-1-10">Total Student</td>
                            <td class="uk-width-1-10">
                                <input class="total_s" type="number" step="0.01" name="school_total_f" id="school_total_f" value="<?php echo $total_EDP_multimedia_student;  ?>" readonly>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_EDP_multimedia_adjust;  ?>" readonly>
                            </td>
                        </tr>
                        <!-- multimedia edp end -->
                        <!-- multimedia QF1 start -->

                        <tr class="">
                            <td style=" margin-top:20px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">QF1:</td>
                        </tr>
                        <?php
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
                            $total_QF1_multimedia_adjust = 0;
                            $total_QF1_multimedia_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".$row['fees_structure']."' and f.multimedia_adjust !='' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_QF1_multimedia_adjust += $level_count * $row['multimedia_adjust'];
                                $total_QF1_multimedia_student += $level_count;
                                ?>
                                <input class="edp_tsd multimedia" type="number" step="0.01" name="multimedia_s"
                                    id="multimedia_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd multimedia" type="number" name="multimedia_st" id="multimedia_st"
                                    value="<?php echo $row['multimedia_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="multimedia_t"
                                    id="multimedia_t" value="<?php echo $level_count * $row['multimedia_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php
							}
							}
						?>
                        <tr class="">
                            <td style="margin-top:50px; padding-top: 11px; text-align: right;" class="uk-width-1-10">Total Student</td>
                            <td class="uk-width-1-10">
                                <input class="total_s" type="number" step="0.01" name="school_total_f" id="school_total_f" value="<?php echo $total_QF1_multimedia_student;  ?>" readonly>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_QF1_multimedia_adjust;  ?>" readonly>
                            </td>
                        </tr>
                        <!-- multimedia qf1 end -->
                        <!-- multimedia QF2 start -->

                        <tr class="">
                            <td style=" margin-top:20px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">QF2:</td>
                        </tr>
                        <?php
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
                            $total_QF2_multimedia_adjust = 0; 
                            $total_QF2_multimedia_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".$row['fees_structure']."' and f.multimedia_adjust !='' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_QF2_multimedia_adjust += $level_count * $row['multimedia_adjust'];
                                $total_QF2_multimedia_student += $level_count;
                                ?>
                                <input class="edp_tsd multimedia" type="number" step="0.01" name="multimedia_s"
                                    id="multimedia_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd multimedia" type="number" name="multimedia_st" id="multimedia_st"
                                    value="<?php echo $row['multimedia_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="multimedia_t"
                                    id="multimedia_t" value="<?php echo $level_count * $row['multimedia_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php
							}
							}
						?>
                        <tr class="">
                            <td style="margin-top:50px; padding-top: 11px; text-align: right;" class="uk-width-1-10">Total Student</td>
                            <td class="uk-width-1-10">
                                <input class="total_s" type="number" step="0.01" name="school_total_f" id="school_total_f" value="<?php echo $total_QF2_multimedia_student;  ?>" readonly>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_QF2_multimedia_adjust;  ?>" readonly>
                            </td>
                        </tr>
                        <!-- multimedia QF2 end -->
                        <!-- multimedia QF3 start -->
                        <tr class="">
                            <td style=" margin-top:20px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">QF3:</td>
                        </tr>
                        <?php
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							$total_QF3_multimedia_adjust = 0;
                            $total_QF3_multimedia_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".$row['fees_structure']."' and f.multimedia_adjust !='' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_QF3_multimedia_adjust += $level_count * $row['multimedia_adjust'];
                                $total_QF3_multimedia_student += $level_count;
                                ?>
                                <input class="edp_tsd multimedia" type="number" step="0.01" name="multimedia_s"
                                    id="multimedia_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd multimedia" type="number" name="multimedia_st" id="multimedia_st"
                                    value="<?php echo $row['multimedia_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="multimedia_t"
                                    id="multimedia_t" value="<?php echo $level_count * $row['multimedia_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php
							}
							}
						?>
                        <tr class="">
                        <td style="margin-top:50px; padding-top: 11px; text-align: right;" class="uk-width-1-10">Total Student</td>
                            <td class="uk-width-1-10">
                                <input class="total_s" type="number" step="0.01" name="school_total_f" id="school_total_f" value="<?php echo $total_QF3_multimedia_student;  ?>" readonly>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_QF3_multimedia_adjust;  ?>" readonly>
                            </td>
                        </tr>
                        <!-- multimedia qf3 end -->

                        <tr class="">
                        <td style="margin-top:50px; padding-top: 11px; text-align: right;" class="uk-width-1-10">Total Student (ii)</td>
                            <td class="uk-width-1-10">
                                <input class="total_s" type="number" step="0.01" name="school_total_f" id="school_total_f" value="<?php echo $total_EDP_multimedia_student + $total_QF1_multimedia_student + $total_QF2_multimedia_student + $total_QF3_multimedia_student;  ?>" readonly>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total (ii)" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_multimedia_adjust = $total_EDP_multimedia_adjust + $total_QF1_multimedia_adjust  + $total_QF2_multimedia_adjust + $total_QF3_multimedia_adjust;  ?>">
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
                                class="uk-width-6-10 uk-text-bold">(iii) Afternoon
                                Programme Fees</td>
                        </tr>
                        <!-- Afternoon edp start -->

                        <tr class="">
                            <td style=" margin-top:20px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">EDP:</td>
                        </tr>
                        <?php
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
                            $total_EDP_basic_adjust = 0;
                            $total_EDP_basic_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and fl.afternoon_programme =1 and f.fees_structure='".$row['fees_structure']."' and f.basic_adjust !='' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_EDP_basic_adjust += $level_count * $row['basic_adjust'];
                                $total_EDP_basic_student += $level_count;
                                ?>
                                <input class="edp_tsd multimedia" type="number" step="0.01" name="multimedia_s"
                                    id="multimedia_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd multimedia" type="number" name="multimedia_st" id="multimedia_st"
                                    value="<?php echo $row['basic_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="multimedia_t"
                                    id="multimedia_t" value="<?php echo $level_count * $row['basic_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php
							}
							}
						?>
                        <tr class="">
                        <td style="margin-top:50px; padding-top: 11px; text-align: right;" class="uk-width-1-10">Total Student</td>
                            <td class="uk-width-1-10">
                                <input class="total_s" type="number" step="0.01" name="school_total_f" id="school_total_f" value="<?php echo $total_EDP_basic_student;  ?>" readonly>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_EDP_basic_adjust;  ?>" readonly>
                            </td>
                        </tr>
							
                        <!-- Afternoon edp end -->
                        <!-- Afternoon QF1 start -->

                        <tr class="">
                            <td style=" margin-top:20px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">QF1:</td>
                        </tr>
                        <?php
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							$total_QF1_basic_adjust = 0;
                            $total_QF1_basic_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and fl.afternoon_programme =1 and f.fees_structure='".$row['fees_structure']."' and f.basic_adjust !='' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_QF1_basic_adjust += $level_count * $row['basic_adjust'];
                                $total_QF1_basic_student += $level_count;
                                ?>
                                <input class="edp_tsd multimedia" type="number" step="0.01" name="multimedia_s"
                                    id="multimedia_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd multimedia" type="number" name="multimedia_st" id="multimedia_st"
                                    value="<?php echo $row['basic_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="multimedia_t"
                                    id="multimedia_t" value="<?php echo $level_count * $row['basic_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php
							}
							}
						?>
                        <tr class="">
                        <td style="margin-top:50px; padding-top: 11px; text-align: right;" class="uk-width-1-10">Total Student</td>
                            <td class="uk-width-1-10">
                                <input class="total_s" type="number" step="0.01" name="school_total_f" id="school_total_f" value="<?php echo $total_QF1_basic_student;  ?>" readonly>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_QF1_basic_adjust;  ?>" readonly>
                            </td>
                        </tr>
                        <!-- Afternoon QF1 end -->
                        <!-- Afternoon QF2 start -->

                        <tr class="">
                            <td style=" margin-top:20px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">QF2:</td>
                        </tr>
                        <?php
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							$total_QF2_basic_adjust = 0;
                            $total_QF2_basic_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and fl.afternoon_programme =1 and f.fees_structure='".$row['fees_structure']."' and f.basic_adjust !='' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_QF2_basic_adjust += $level_count * $row['basic_adjust'];
                                $total_QF2_basic_student += $level_count;
                                ?>
                                <input class="edp_tsd multimedia" type="number" step="0.01" name="multimedia_s"
                                    id="multimedia_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd multimedia" type="number" name="multimedia_st" id="multimedia_st"
                                    value="<?php echo $row['basic_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="multimedia_t"
                                    id="multimedia_t" value="<?php echo $level_count * $row['basic_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php
							}
							}
						?>
                        <tr class="">
                        <td style="margin-top:50px; padding-top: 11px; text-align: right;" class="uk-width-1-10">Total Student</td>
                            <td class="uk-width-1-10">
                                <input class="total_s" type="number" step="0.01" name="school_total_f" id="school_total_f" value="<?php echo $total_QF2_basic_student;  ?>" readonly>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_QF2_basic_adjust;  ?>" readonly>
                            </td>
                        </tr>
                        <!-- Afternoon QF2 end -->
                        <!-- Afternoon QF3 start -->

                        <tr class="">
                            <td style=" margin-top:20px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">QF3:</td>
                        </tr>
                        <?php
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							$total_QF3_basic_adjust = 0;
                            $total_QF3_basic_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and fl.afternoon_programme =1 and f.fees_structure='".$row['fees_structure']."' and f.basic_adjust !='' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_QF3_basic_adjust += $level_count * $row['basic_adjust'];
                                $total_QF3_basic_student += $level_count;
                                ?>
                                <input class="edp_tsd multimedia" type="number" step="0.01" name="multimedia_s"
                                    id="multimedia_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd multimedia" type="number" name="multimedia_st" id="multimedia_st"
                                    value="<?php echo $row['basic_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="multimedia_t"
                                    id="multimedia_t" value="<?php echo $level_count * $row['basic_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php
							}
							}
						?>
                        <tr class="">
                        <td style="margin-top:50px; padding-top: 11px; text-align: right;" class="uk-width-1-10">Total Student</td>
                            <td class="uk-width-1-10">
                                <input class="total_s" type="number" step="0.01" name="school_total_f" id="school_total_f" value="<?php echo $total_QF3_basic_student;  ?>" readonly>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_QF3_basic_adjust;  ?>" readonly>
                            </td>
                        </tr>
                        <!-- Afternoon QF3 end -->

                        <tr class="">
                        <td style="margin-top:50px; padding-top: 11px; text-align: right;" class="uk-width-1-10">Total Student (iii)</td>
                            <td class="uk-width-1-10">
                                <input class="total_s" type="number" step="0.01" name="school_total_f" id="school_total_f" value="<?php echo $total_EDP_basic_student + $total_QF1_basic_student+ $total_QF2_basic_student+ $total_QF3_basic_student;  ?>" readonly>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total (iii)" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_basic_adjust = $total_EDP_basic_adjust + $total_QF1_basic_adjust  + $total_QF2_basic_adjust + $total_QF3_basic_adjust;  ?>">
                                    <?php $total_a += $total_basic_adjust; ?>
                            </td>
                        </tr>
                        <!-- afternoon end -->
                        <!-- Mobile App start -->
                        <tr class="">
                            <td style=" margin-top:50px;border:none; font-size:18px;"
                                class="uk-width-6-10 uk-text-bold">(iv) Mobile App Fees</td>
                        </tr>
                        <!-- Mobile App edp start -->

                        <tr class="">
                            <td style=" margin-top:20px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">EDP:</td>
                        </tr>
                        <?php
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
                            $total_EDP_mobile_adjust = 0;
                            $total_EDP_mobile_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".$row['fees_structure']."' and f.mobile_adjust !='' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_EDP_mobile_adjust += $level_count * $row['mobile_adjust'];
                                $total_EDP_mobile_student += $level_count;
                                ?>
                                <input class="edp_tsd multimedia" type="number" step="0.01" name="multimedia_s"
                                    id="multimedia_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd multimedia" type="number" name="multimedia_st" id="multimedia_st"
                                    value="<?php echo $row['mobile_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="multimedia_t"
                                    id="multimedia_t" value="<?php echo $level_count * $row['mobile_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php } }?>
                        <tr class="">
                        <td style="margin-top:50px; padding-top: 11px; text-align: right;" class="uk-width-1-10">Total Student</td>
                            <td class="uk-width-1-10">
                                <input class="total_s" type="number" step="0.01" name="school_total_f" id="school_total_f" value="<?php echo $total_EDP_mobile_student;  ?>" readonly>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_EDP_mobile_adjust;  ?>" readonly>
                            </td>
                        </tr>
                        <!-- Mobile App edp end -->
                        <!-- Mobile App QF1 start -->

                        <tr class="">
                            <td style=" margin-top:20px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">QF1:</td>
                        </tr>
                        <?php
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							$total_QF1_mobile_adjust = 0;
                            $total_QF1_mobile_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".$row['fees_structure']."' and f.mobile_adjust !='' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_QF1_mobile_adjust += $level_count * $row['mobile_adjust'];
                                $total_QF1_mobile_student += $level_count;
                                ?>
                                <input class="edp_tsd multimedia" type="number" step="0.01" name="multimedia_s"
                                    id="multimedia_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd multimedia" type="number" name="multimedia_st" id="multimedia_st"
                                    value="<?php echo $row['mobile_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="multimedia_t"
                                    id="multimedia_t" value="<?php echo $level_count * $row['mobile_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php } }?>
                        <tr class="">
                            <td style="margin-top:50px; padding-top: 11px; text-align: right;" class="uk-width-1-10">Total Student</td>
                            <td class="uk-width-1-10">
                                <input class="total_s" type="number" step="0.01" name="school_total_f" id="school_total_f" value="<?php echo $total_QF1_mobile_student;  ?>" readonly>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_QF1_mobile_adjust;  ?>" readonly>
                            </td>
                        </tr>
                        <!-- Mobile App QF1 end -->
                        <!-- Mobile App QF2 start -->

                        <tr class="">
                            <td style=" margin-top:20px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">QF2:</td>
                        </tr>
                        <?php
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							$total_QF2_mobile_adjust = 0;
                            $total_QF2_mobile_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".$row['fees_structure']."' and f.mobile_adjust !='' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_QF2_mobile_adjust += $level_count * $row['mobile_adjust'];
                                $total_QF2_mobile_student += $level_count;
                                ?>
                                <input class="edp_tsd multimedia" type="number" step="0.01" name="multimedia_s"
                                    id="multimedia_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd multimedia" type="number" name="multimedia_st" id="multimedia_st"
                                    value="<?php echo $row['mobile_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="multimedia_t"
                                    id="multimedia_t" value="<?php echo $level_count * $row['mobile_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php } }?>
                        <tr class="">
                            <td style="margin-top:50px; padding-top: 11px; text-align: right;" class="uk-width-1-10">Total Student</td>
                            <td class="uk-width-1-10">
                                <input class="total_s" type="number" step="0.01" name="school_total_f" id="school_total_f" value="<?php echo $total_QF2_mobile_student;  ?>" readonly>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_QF2_mobile_adjust;  ?>" readonly>
                            </td>
                        </tr>
                        <!-- Mobile App QF2 end -->
                        <!-- Mobile App QF3 start -->

                        <tr class="">
                            <td style=" margin-top:20px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">QF3:</td>
                        </tr>
                        <?php
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							$total_QF3_mobile_adjust = 0;
                            $total_QF3_mobile_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".$row['fees_structure']."' and f.mobile_adjust !='' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_QF3_mobile_adjust += $level_count * $row['mobile_adjust'];
                                $total_QF3_mobile_student += $level_count;
                                ?>
                                <input class="edp_tsd multimedia" type="number" step="0.01" name="multimedia_s"
                                    id="multimedia_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd multimedia" type="number" name="multimedia_st" id="multimedia_st"
                                    value="<?php echo $row['mobile_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="multimedia_t"
                                    id="multimedia_t" value="<?php echo $level_count * $row['mobile_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php } }?>
                        <tr class="">
                            <td style="margin-top:50px; padding-top: 11px; text-align: right;" class="uk-width-1-10">Total Student</td>
                            <td class="uk-width-1-10">
                                <input class="total_s" type="number" step="0.01" name="school_total_f" id="school_total_f" value="<?php echo $total_QF3_mobile_student;  ?>" readonly>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_QF3_mobile_adjust;  ?>" readonly>
                            </td>
                        </tr>
                        <!-- Mobile App QF3 end -->

                        <tr class="">
                        <td style="margin-top:50px; padding-top: 11px; text-align: right;" class="uk-width-1-10">Total Student (iv)</td>
                            <td class="uk-width-1-10">
                                <input class="total_s" type="number" step="0.01" name="school_total_f" id="school_total_f" value="<?php echo $total_EDP_mobile_student + $total_QF1_mobile_student + $total_QF2_mobile_student + $total_QF3_mobile_student;  ?>" readonly>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total (iv)" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_mobile_adjust = $total_EDP_mobile_adjust + $total_QF1_mobile_adjust  + $total_QF2_mobile_adjust + $total_QF3_mobile_adjust;  ?>">
                                    <?php $total_a += $total_mobile_adjust; ?>
                            </td>
                        </tr>
                        <!-- Mobile App end -->



                        <tr class="">
                            <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold"></td>
                            <td class="uk-width-1-10"></td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total A" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_a; ?>">
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
                                class="uk-width-6-10 uk-text-bold">(i) Q-dess Foundation Materials(Termly)</td>
                        </tr>
                        <!--  b 1 edp start -->

                        <tr class="">
                            <td style=" margin-top:200px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">EDP</td>
                        </tr>
                        <?php
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
                            $total_EDP_integrated_adjust = 0;
                            $total_EDP_integrated_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".$row['fees_structure']."' and f.integrated_adjust !='' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_EDP_integrated_adjust += $level_count * $row['integrated_adjust'];
                                $total_EDP_integrated_student += $level_count;
                                ?>
                                <input class="edp_tsd multimedia" type="number" step="0.01" name="multimedia_s"
                                    id="multimedia_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd multimedia" type="number" name="multimedia_st" id="multimedia_st"
                                    value="<?php echo $row['integrated_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="multimedia_t"
                                    id="multimedia_t" value="<?php echo $level_count * $row['integrated_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php } }?>
                        <tr class="">
                        <td style="margin-top:50px; padding-top: 11px; text-align: right;" class="uk-width-1-10">Total Student</td>
                            <td class="uk-width-1-10">
                                <input class="total_s" type="number" step="0.01" name="school_total_f" id="school_total_f" value="<?php echo $total_EDP_integrated_student;  ?>" readonly>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_EDP_integrated_adjust;  ?>" readonly>
                            </td>
                        </tr>
							
                        <!--  b edp end -->
                        <!--  b QF1 start -->

                        <tr class="">
                            <td style=" margin-top:200px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">QF1</td>
                        </tr>
                        <?php
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF1_integrated_student = 0;
							$total_QF1_integrated_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".$row['fees_structure']."' and f.integrated_adjust !='' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_QF1_integrated_adjust += $level_count * $row['integrated_adjust'];
                                $total_QF1_integrated_student += $level_count;
                                ?>
                                <input class="edp_tsd multimedia" type="number" step="0.01" name="multimedia_s"
                                    id="multimedia_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd multimedia" type="number" name="multimedia_st" id="multimedia_st"
                                    value="<?php echo $row['integrated_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="multimedia_t"
                                    id="multimedia_t" value="<?php echo $level_count * $row['integrated_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php } }?>
                        <tr class="">
                        <td style="margin-top:50px; padding-top: 11px; text-align: right;" class="uk-width-1-10">Total Student</td>
                            <td class="uk-width-1-10">
                                <input class="total_s" type="number" step="0.01" name="school_total_f" id="school_total_f" value="<?php echo $total_QF1_integrated_student;  ?>" readonly>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_QF1_integrated_adjust;  ?>" readonly>
                            </td>
                        </tr>
                        <!--  b QF1 end -->
                        <!--  b QF2 start -->

                        <tr class="">
                            <td style=" margin-top:200px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">QF2</td>
                        </tr>
                        <?php
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF2_integrated_student = 0;
							$total_QF2_integrated_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".$row['fees_structure']."' and f.integrated_adjust !='' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_QF2_integrated_adjust += $level_count * $row['integrated_adjust'];
                                $total_QF2_integrated_student += $level_count;
                                ?>
                                <input class="edp_tsd multimedia" type="number" step="0.01" name="multimedia_s"
                                    id="multimedia_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd multimedia" type="number" name="multimedia_st" id="multimedia_st"
                                    value="<?php echo $row['integrated_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="multimedia_t"
                                    id="multimedia_t" value="<?php echo $level_count * $row['integrated_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php } }?>
                        <tr class="">
                            <td style="margin-top:50px; padding-top: 11px; text-align: right;" class="uk-width-1-10">Total Student</td>
                            <td class="uk-width-1-10">
                                <input class="total_s" type="number" step="0.01" name="school_total_f" id="school_total_f" value="<?php echo $total_QF2_integrated_student;  ?>" readonly>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_QF2_integrated_adjust;  ?>" readonly>
                            </td>
                        </tr>
                        <!--  b QF2 end -->
                        <!--  b QF3 start -->

                        <tr class="">
                            <td style=" margin-top:200px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">QF3</td>
                        </tr>
                        <?php
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF3_integrated_student = 0;
							$total_QF3_integrated_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".$row['fees_structure']."' and f.integrated_adjust !='' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_QF3_integrated_adjust += $level_count * $row['integrated_adjust'];
                                $total_QF3_integrated_student += $level_count;
                                ?>
                                <input class="edp_tsd multimedia" type="number" step="0.01" name="multimedia_s"
                                    id="multimedia_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd multimedia" type="number" name="multimedia_st" id="multimedia_st"
                                    value="<?php echo $row['integrated_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="multimedia_t"
                                    id="multimedia_t" value="<?php echo $level_count * $row['integrated_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php } }?>
                        <tr class="">
                        <td style="margin-top:50px; padding-top: 11px; text-align: right;" class="uk-width-1-10">Total Student</td>
                            <td class="uk-width-1-10">
                                <input class="total_s" type="number" step="0.01" name="school_total_f" id="school_total_f" value="<?php echo $total_QF3_integrated_student;  ?>" readonly>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_QF3_integrated_adjust;  ?>" readonly>
                            </td>
                        </tr>
                        </tr>
                        <!--  b QF3 end -->

                        <tr class="">
                            <td style="margin-top:50px; padding-top: 11px; text-align: right;" class="uk-width-1-10">Total Student (i)</td>
                            <td class="uk-width-1-10">
                                <input class="total_s" type="number" step="0.01" name="school_total_f" id="school_total_f" value="<?php echo $total_EDP_integrated_student + $total_QF1_integrated_student + $total_QF2_integrated_student + $total_QF3_integrated_student;  ?>" readonly>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total (i)" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_integrated_adjust = $total_EDP_integrated_adjust + $total_QF1_integrated_adjust  + $total_QF2_integrated_adjust + $total_QF3_integrated_adjust;  ?>">
                                    <?php $total_b = $total_integrated_adjust; ?>
                            </td>
                        </tr>
                        <!--  b 1 end -->


                        <!--b 2  start -->
                        <tr class="">
                            <td style=" margin-top:50px;border:none; font-size:18px;"
                                class="uk-width-6-10 uk-text-bold">(ii) Q-dees Foundation Mandarin Modules Materials(Termly)</td>
                        </tr>
                        <!--  b 2 edp start -->

                        <tr class="">
                            <td style=" margin-top:200px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">EDP</td>
                        </tr>
                        <?php
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
                            $total_EDP_mandarin_m_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and fl.foundation_mandarin =1 and f.fees_structure='".$row['fees_structure']."' and f.mandarin_m_adjust !='' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_EDP_mandarin_m_adjust += $level_count * $row['mandarin_m_adjust'];
                                //$total_EDP_student += $level_count;
                                ?>
                                <input class="edp_tsd multimedia" type="number" step="0.01" name="multimedia_s"
                                    id="multimedia_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd multimedia" type="number" name="multimedia_st" id="multimedia_st"
                                    value="<?php echo $row['mandarin_m_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="multimedia_t"
                                    id="multimedia_t" value="<?php echo $level_count * $row['mandarin_m_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php } }?>
                        <tr class="">
                            <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold"></td>
                            <td class="uk-width-1-10"></td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_EDP_mandarin_m_adjust;  ?>" readonly>
                            </td>
                        </tr>
                        <!--  b 2 edp end -->
                        <!--  b 2 QF1 start -->

                        <tr class="">
                            <td style=" margin-top:200px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">QF1</td>
                        </tr>
                        <?php
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							$total_QF1_mandarin_m_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and fl.foundation_mandarin =1 and f.fees_structure='".$row['fees_structure']."' and f.mandarin_m_adjust !='' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_QF1_mandarin_m_adjust += $level_count * $row['mandarin_m_adjust'];
                                //$total_QF1_QF2_QF2_student += $level_count;
                                ?>
                                <input class="edp_tsd multimedia" type="number" step="0.01" name="multimedia_s"
                                    id="multimedia_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd multimedia" type="number" name="multimedia_st" id="multimedia_st"
                                    value="<?php echo $row['mandarin_m_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="multimedia_t"
                                    id="multimedia_t" value="<?php echo $level_count * $row['mandarin_m_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php } }?>
                        <tr class="">
                            <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold"></td>
                            <td class="uk-width-1-10"></td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_QF1_mandarin_m_adjust;  ?>" readonly>
                            </td>
                        </tr>
                        <!--  b 2 QF1 end -->
                        <!--  b 2 QF2 start -->

                        <tr class="">
                            <td style=" margin-top:200px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">QF2</td>
                        </tr>
                        <?php
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							$total_QF2_mandarin_m_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and fl.foundation_mandarin =1 and f.fees_structure='".$row['fees_structure']."' and f.mandarin_m_adjust !='' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_QF2_mandarin_m_adjust += $level_count * $row['mandarin_m_adjust'];
                                //$total_QF1_QF2_QF2_student += $level_count;
                                ?>
                                <input class="edp_tsd multimedia" type="number" step="0.01" name="multimedia_s"
                                    id="multimedia_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd multimedia" type="number" name="multimedia_st" id="multimedia_st"
                                    value="<?php echo $row['mandarin_m_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="multimedia_t"
                                    id="multimedia_t" value="<?php echo $level_count * $row['mandarin_m_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php } }?>
                        <tr class="">
                            <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold"></td>
                            <td class="uk-width-1-10"></td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_QF2_mandarin_m_adjust;  ?>" readonly>
                            </td>
                        </tr>
                        <!--  b 2 QF2 end -->
                        <!--  b 2 QF3 start -->

                        <tr class="">
                            <td style=" margin-top:200px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">QF3</td>
                        </tr>
                        <?php
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							$total_QF3_mandarin_m_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and fl.foundation_mandarin =1 and f.fees_structure='".$row['fees_structure']."' and f.mandarin_m_adjust !='' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_QF3_mandarin_m_adjust += $level_count * $row['mandarin_m_adjust'];
                                //$total_QF1_QF2_QF2_student += $level_count;
                                ?>
                                <input class="edp_tsd multimedia" type="number" step="0.01" name="multimedia_s"
                                    id="multimedia_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd multimedia" type="number" name="multimedia_st" id="multimedia_st"
                                    value="<?php echo $row['mandarin_m_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="multimedia_t"
                                    id="multimedia_t" value="<?php echo $level_count * $row['mandarin_m_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php } }?>
                        <tr class="">
                            <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold"></td>
                            <td class="uk-width-1-10"></td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_QF3_mandarin_m_adjust;  ?>" readonly>
                            </td>
                        </tr>
                        <!--  b 2 QF3 end -->
                        <tr class="">
                            <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold"></td>
                            <td class="uk-width-1-10"></td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total (ii)" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_mandarin_m_adjust = $total_EDP_mandarin_m_adjust + $total_QF1_imandarin_m_adjust  + $total_QF2_mandarin_m_adjust + $total_QF3_mandarin_m_adjust;  ?>">
                                    <?php $total_b += $total_mandarin_m_adjust; ?>
                            </td>
                        </tr>
                        <!--  b 2 end -->
                        

                        
                        <!--b 3  start -->
                        <tr class="">
                            <td style=" margin-top:50px;border:none; font-size:18px;"
                                class="uk-width-6-10 uk-text-bold">(iii) Registration Pack</td>
                        </tr>
                        <!--  b 3 edp start -->

                        <tr class="">
                            <td style=" margin-top:200px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">EDP</td>
                        </tr>
                        <?php
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
                            $total_EDP_registration_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".$row['fees_structure']."' and f.registration_adjust !='' and c.product_code='Registration' and month(c.collection_date_time) = $month group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_EDP_registration_adjust += $level_count * $row['registration_adjust'];
                                //$total_EDP_student += $level_count;
                                ?>
                                <input class="edp_tsd multimedia" type="number" step="0.01" name="multimedia_s"
                                    id="multimedia_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd multimedia" type="number" name="multimedia_st" id="multimedia_st"
                                    value="<?php echo $row['registration_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="multimedia_t"
                                    id="multimedia_t" value="<?php echo $level_count * $row['registration_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php } }?>
                        <tr class="">
                            <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold"></td>
                            <td class="uk-width-1-10"></td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_EDP_registration_adjust;  ?>" readonly>
                            </td>
                        </tr>
                        <!--  b 3 edp end -->
                        <!--  b 3 QF1 start -->

                        <tr class="">
                            <td style=" margin-top:200px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">QF1</td>
                        </tr>
                        <?php
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							$total_QF1_registration_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".$row['fees_structure']."' and f.registration_adjust !='' and c.product_code='Registration' and month(c.collection_date_time) = $month group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_QF1_registration_adjust += $level_count * $row['registration_adjust'];
                                //$total_QF1_QF2_QF2_student += $level_count;
                                ?>
                                <input class="edp_tsd multimedia" type="number" step="0.01" name="multimedia_s"
                                    id="multimedia_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd multimedia" type="number" name="multimedia_st" id="multimedia_st"
                                    value="<?php echo $row['registration_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="multimedia_t"
                                    id="multimedia_t" value="<?php echo $level_count * $row['registration_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php } }?>
                        <tr class="">
                            <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold"></td>
                            <td class="uk-width-1-10"></td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_QF1_registration_adjust;  ?>" readonly>
                            </td>
                        </tr>
                        <!--  b 3 QF1 end -->
                        <!--  b 3 QF2 start -->

                        <tr class="">
                            <td style=" margin-top:200px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">QF2</td>
                        </tr>
                        <?php
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							$total_QF2_registration_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".$row['fees_structure']."' and f.registration_adjust !='' and c.product_code='Registration' and month(c.collection_date_time) = $month group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_QF2_registration_adjust += $level_count * $row['registration_adjust'];
                                //$total_QF1_QF2_QF2_student += $level_count;
                                ?>
                                <input class="edp_tsd multimedia" type="number" step="0.01" name="multimedia_s"
                                    id="multimedia_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd multimedia" type="number" name="multimedia_st" id="multimedia_st"
                                    value="<?php echo $row['registration_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="multimedia_t"
                                    id="multimedia_t" value="<?php echo $level_count * $row['registration_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php } }?>
                        <tr class="">
                            <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold"></td>
                            <td class="uk-width-1-10"></td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_QF2_registration_adjust;  ?>" readonly>
                            </td>
                        </tr>
                        <!--  b 3 QF2 end -->
                        <!--  b 3 QF3 start -->

                        <tr class="">
                            <td style=" margin-top:200px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">QF3</td>
                        </tr>
                        <?php
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							$total_QF3_registration_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".$row['fees_structure']."' and f.registration_adjust !='' and c.product_code='Registration' and month(c.collection_date_time) = $month group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_QF3_registration_adjust += $level_count * $row['registration_adjust'];
                                //$total_QF1_QF2_QF2_student += $level_count;
                                ?>
                                <input class="edp_tsd multimedia" type="number" step="0.01" name="multimedia_s"
                                    id="multimedia_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd multimedia" type="number" name="multimedia_st" id="multimedia_st"
                                    value="<?php echo $row['registration_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="multimedia_t"
                                    id="multimedia_t" value="<?php echo $level_count * $row['registration_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php } }?>
                        <tr class="">
                            <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold"></td>
                            <td class="uk-width-1-10"></td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_QF3_registration_adjust;  ?>" readonly>
                            </td>
                        </tr>
                        <!--  b 3 QF3 end -->
                        <tr class="">
                            <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold"></td>
                            <td class="uk-width-1-10"></td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total (ii)" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_registration_adjust = $total_EDP_registration_adjust + $total_QF1_iregistration_adjust  + $total_QF2_registration_adjust + $total_QF3_registration_adjust;  ?>">
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
                            <td style="margin-top:50px; border:none;" class="uk-width-1-10 uk-text-bold"></td>
                            <td style="border:none;" class="uk-width-1-10"></td>
                            <td style="text-align:center; border:none;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_b" id="total_b"
                                    placeholder="Total B."> <span style="font-size:14px;" class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center; border:none;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="totalb_g" id="totalb_g" value="<?php echo $total_b; ?>" readonly>
                            </td>
                        </tr>
                        <tr class="">
                            <td colspan="5">Note: as per Student Fee List for each Academic Year as prescribed by the
                                Franchisor<br>*as stated in the Operations e-Manual</td>
                        </tr>
                        <tr class="">
                            <td style=" margin-top:50px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">C: Products</td>
                        </tr>
                        
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">(i)Pre-school
                                Kits<span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count, fees from (SELECT s.id, p.unit_price as fees from student s inner join `collection` c on c.student_id = s.id inner join product p on p.product_code=c.product_code where c.year = '$year' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and p.product_name like 'Pre-school Kit%' and c.product_code like '%QHSB_55' and month(c.collection_date_time) = $month group by s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                $fees = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                    $fees = (empty($roww["fees"]) ? "0" : $roww["fees"]); 
                                }
                                $total_pre_school_kits += $level_count * $fees;
                                ?>
                                <input class="edp_tsd pre_school" type="number" step="0.01" name="pre_school_s"
                                    id="pre_school_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd pre_school" type="number" name="pre_school_st" id="pre_school_st"
                                    value="<?php echo $fees ?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust2" type="number" step="0.01" name="pre_school_t"
                                    id="pre_school_t" value="<?php echo $total_pre_school_kits ?>" readonly><br>
                            </td>
                        </tr>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">(ii)Memories to
                                Cherish<span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count, fees from (SELECT s.id, p.retail_price as fees from student s inner join `collection` c on c.student_id = s.id inner join product p on p.product_code=c.product_code where c.year = '$year' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and p.product_name like 'Memories To Cherish' and month(c.collection_date_time) = $month group by s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                $fees = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                    $fees = (empty($roww["fees"]) ? "0" : $roww["fees"]); 
                                }
                                $total_memories_to_cherish += $level_count * $fees;
                                ?>
                                <input class="edp_tsd pre_school" type="number" step="0.01" name="pre_school_s"
                                    id="pre_school_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd pre_school" type="number" name="pre_school_st" id="pre_school_st"
                                    value="<?php echo $fees ?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust2" type="number" step="0.01" name="pre_school_t"
                                    id="pre_school_t" value="<?php echo $total_memories_to_cherish ?>" readonly><br>
                            </td>
                        </tr>
                        
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">(iii)Q-dees
                                Bag<span class="text-danger"></span>:</td>
                                <?php
                            $sql="SELECT count(id) level_count, fees from (SELECT ps.student_entry_level, s.id, f.q_bag_adjust as fees from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and f.q_bag_adjust !='' and c.product_code='Q-dees Bag' and month(c.collection_date_time) = $month group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                $fees = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                    $fees = (empty($roww["fees"]) ? "0" : $roww["fees"]); 
                                }
                                $total_q_bag_adjust += $level_count * $fees;
                                ?>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd q_dees" type="number" step="0.01" name="q_dees_s" id="q_dees_s"
                                    value="<?php echo $level_count ?>"> <span class="edp_eq" readonly>✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd q_dees" type="number" name="q_dees_st" id="q_dees_st" value="<?php echo $fees ?>" readonly>
                                <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust2" type="number" step="0.01" name="q_dees_t" id="q_dees_t"
                                    value="<?php echo $total_q_bag_adjust ?>" readonly><br>
                            </td>
                        </tr>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                (iv)Uniform<span class="text-danger"></span>:</td>
                                <?php
                            $sql="SELECT count(id) level_count, fees from (SELECT ps.student_entry_level, s.id, f.uniform_adjust as fees from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and f.uniform_adjust !='' and c.product_code='Uniform (2 sets)' and month(c.collection_date_time) = $month group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                $fees = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                    $fees = (empty($roww["fees"]) ? "0" : $roww["fees"]); 
                                }
                                $total_uniform_adjust += $level_count * $fees;
                                ?>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd q_dees" type="number" step="0.01" name="q_dees_s" id="q_dees_s"
                                    value="<?php echo $level_count ?>"> <span class="edp_eq" readonly>✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd q_dees" type="number" name="q_dees_st" id="q_dees_st" value="<?php echo $fees ?>" readonly>
                                <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust2" type="number" step="0.01" name="q_dees_t" id="q_dees_t"
                                    value="<?php echo $total_uniform_adjust ?>" readonly><br>
                            </td>
                          
                        </tr>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">(v)Gymwear<span
                                    class="text-danger"></span>:</td>
                                    <?php
                            $sql="SELECT count(id) level_count, fees from (SELECT ps.student_entry_level, s.id, f.gymwear_adjust as fees from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id inner join `collection` c on c.allocation_id = ps.id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and f.gymwear_adjust !='' and c.product_code='Gymwear (1 set)' and month(c.collection_date_time) = $month group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                $fees = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                    $fees = (empty($roww["fees"]) ? "0" : $roww["fees"]); 
                                }
                                $total_gymwear_adjust += $level_count * $fees;
                                ?>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd q_dees" type="number" step="0.01" name="q_dees_s" id="q_dees_s"
                                    value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd q_dees" type="number" name="q_dees_st" id="q_dees_st" value="<?php echo $fees ?>" readonly>
                                <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust2" type="number" step="0.01" name="q_dees_t" id="q_dees_t"
                                    value="<?php echo $total_gymwear_adjust ?>" readonly><br>
                            </td>
                        </tr>
                        <tr class="">
                            <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold"></td>
                            <td style="" class="uk-width-1-10"></td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="school_default" id="total_c2"
                                    placeholder="Total C."> <span style="font-size:14px;" class="edp_eq" readonly>RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="" type="number" step="0.01" name="total_c" id="total_c" value="<?php echo $total_c = $total_pre_school_kits + $total_memories_to_cherish + $total_q_bag_adjust  + $total_uniform_adjust + $total_gymwear_adjust;  ?>" readonly>
                                    <?php //$total_c += $total_uniform_adjust; ?>
                            </td>
                        </tr>
                    </table>
                    <table class="uk-table uk-table-small">
                        <tr>
                            <td class="uk-width-6-10" style="font-size:18px; font-weight:bold; border:none;">(1) ROYALTY
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
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">Royalty Fee(5%
                                on Gross Tumover of A. + B.+ C.)<span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                <input style="position:relative;" class="edp_tsd royalty" type="number" step="0.01"
                                    name="royalty_s" id="royalty_s" value="5" readonly> <span
                                    style="margin-left: -30px;font-size: 20px;position: absolute;">%</span> <span
                                    style="" class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd royalty" type="number" name="royalty_st" id="royalty_st" value="<?php echo $gross_tumover = $total_a + $total_b + $total_c; ?>" readonly>
                                <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="royalty_t" id="royalty_t"
                                    value="<?php echo $royalty_fee = ($gross_tumover/100)*5; $total_1_2_3 += $royalty_fee; ?>" readonly><br>
                            </td>
                        </tr>
                    </table>
                    <table class="uk-table uk-table-small">
                        <tr>
                            <td colspan="5" style="font-size:18px; font-weight:bold;"></td>
                        </tr>
                        <tr>
                            <td class="uk-width-6-10" style="font-size:18px; font-weight:bold; border:none;">(2) A&P FEE
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
                            <td style="text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                <input style="position:relative;" class="edp_tsd advertising" type="number"
                                    name="advertising_s" id="advertising_s" value="3" readonly> <span
                                    style="margin-left: -30px;font-size: 20px;position: absolute;">%</span> <span
                                    style="" class="edp_eq">✕ </span>
                            </td>
                            <td style="white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd advertising" type="number" name="advertising_st"
                                    id="advertising_st" value="<?php echo $total_a; ?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="advertising_t"
                                    id="advertising_t" value="<?php echo $advertising_promotion_fee = ($total_a/100)*3; $total_1_2_3 += $advertising_promotion_fee; ?>" readonly><br>
                            </td>
                        </tr>
                    </table>
                    <table class="uk-table uk-table-small">
                        <tr>
                            <td class="uk-width-6-10" style="font-size:18px; font-weight:bold; border:none;">(3) (Q-DEES
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
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php 
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $total_EDP_student = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $total_EDP_student = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                               
                                ?>
                                <input class="edp_tsd software" type="number" step="0.01" name="software_s"
                                    id="software_s" value="<?php echo $total_EDP_student;?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd software" type="number" name="software_st" id="software_st"
                                    value="33"> <span class="edp_eq2" readonly>= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust5" type="number" step="0.01" name="software_t"
                                    id="software_t" value="<?php echo $total_EDP_student*33; $total_1_2_3 += $total_EDP_student*33;?>" readonly><br>
                            </td>
                        </tr>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">Software
                                License Fee-QF1, QF2, QF3<span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php 
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level  in ('QF1','QF2','QF3') and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $total_QF1_QF2_QF2_student = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $total_QF1_QF2_QF2_student = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                               
                                ?>
                                <input class="edp_tsd softwarefee" type="number" step="0.01" name="softwarefee_s"
                                    id="softwarefee_s" value="<?php echo $total_QF1_QF2_QF2_student;?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd softwarefee" type="number" name="softwarefee_st"
                                    id="softwarefee_st" value="33" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust5" type="number" step="0.01" name="softwarefee_t"
                                    id="softwarefee_t" value="<?php echo $total_QF1_QF2_QF2_student*33; $total_1_2_3 += $total_QF1_QF2_QF2_student*33;?>" readonly><br>
                            </td>
                        </tr>
                        <tr class="">
                            <td colspan="5">*Prescribed Rate multiplied by the Prescribed Student Number and payable for
                                11 full months in a calendar year, irrespective of public holidays and weekends.<br>*The
                                highest number of Students at the Approved Centre in a month, irrespective of public
                                holidays and weekends.</td>

                        </tr>

                    </table>
                    <table class="uk-table uk-table-small">
                        <tr class="">
                            <td style=" margin-top:50px; border:none;" class="uk-width-6-10 uk-text-bold">Sub Total
                                (1)<span class="text-danger"></span>:</td>
                            <td style="border:none;" class="uk-width-1-10"></td>
                            <td style="border:none;" class="uk-width-1-10"></td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust56" type="number" step="0.01" name="grand_total"
                                    id="grand_total" value="<?php echo sprintf('%0.2f', $total_1_2_3); ?>" readonly><br>
                            </td>
                        </tr>
                        <tr class="">
                            <td style=" margin-top:50px;" class="uk-width-6-10 uk-text-bold">PAYABLE TO Q-DEES WORLDWIDE
                                EDUSYSTEMS (M) SDN BHD (MBB 514196314454)<span class="text-danger"></span>:</td>
                            <td style="" class="uk-width-1-10"></td>
                            <td style="" class="uk-width-1-10"></td>
                            <td style=" text-align:center;" class="uk-width-1-10"></td>
                        </tr>
                    </table>
                    <table class="uk-table uk-table-small">
                        <tr>
                            <td class="uk-width-6-10" style="font-size:18px; font-weight:bold; border:none;">TERMS AND
                                CONDITIONS</td>
                        </tr>
                        <tr class="">
                            <td style="border:none;" colspan="5"><i>The Franchisee shall pay to Franchisor Royalty Fee,
                                    Advertising & Promotion Fee and Software License Fee, according to the terms and
                                    conditions stated in this declaration, in Ringgit Malaysia on a monthly basis, on
                                    the 1st day but not later than the 5th day of each month. Late payments shall be
                                    levied with an additional 1.5% charge per month. <b>(This Declaration Form shall
                                        constitute as a legally binding contract between the parties and shall be read
                                        and construed as if the Declaration Form were inserted in the Franchise
                                        Agreement as an integral part of the Franchise Agreement.)</b><i></td>
                        </tr>
                        <tr class="">
                            <td style="border:none; " colspan="5"><i>I <?php echo $operator_name;?>, hereby acknowledge
                                    and agree to the above terms and I hereby declare the information above to be
                                    accurate, complete and in compliance with the law of Malaysia. I also acknowledge
                                    that I have taken all reasonable steps to ensure the same.<i></td>
                        </tr>
                        <tr>
                            <td class="uk-width-5-10"
                                style="font-size:18px; font-weight:bold; border:none; padding-top: 120px;" colspan="4">
                                <span
                                    style="border-top: 2px solid; padding-left: 100px; padding-right: 100px;margin-left: 75px;">
                                    Signature of Key Operator
                                </span>
                            </td>
                            <!-- <td class="uk-width-5-10" style="font-size:18px; font-weight:bold; border:none;text-align: center;">Official Stamp of Franchisee</td> -->
                        </tr>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-5-10 uk-text-bold">Name:
                                <?php echo $operator_name;?> </td>
                        </tr>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-2-10 uk-text-bold">Date:
                                <?php// echo $created_date;?></td>
                        </tr>
                        <tr>
                            <td style="margin-top:50px;border:none;" class="uk-width-5-10 uk-text-bold">Centre's
                                Remarks:</td>
                            <td style="border:none;" class="uk-width-5-10 uk-text-bold"><textarea id="remarks_centre"
                                    name="remarks_centre" rows="5"
                                    cols="65"><?php echo $edit_row['remarks_centre'] ?> </textarea></td>
                        </tr>
                        <tr class="uk-text-small">
                            <td class="uk-width-5-10 uk-text-bold" style="border:none;font-size: 15px;"></td>
                            <td class="uk-width-5-10" id="dvSSM_file">
                                <input class="uk-width-1-1" type="file" name="file_s" id="file_s"
                                    accept=".doc, .docx, .pdf, .png, .jpg, .jpeg">

                                <?php
                    if ($edit_row["doc_remarks"]!="") {
                    ?>
                                <a href="admin/uploads/<?php echo $edit_row['doc_remarks']?>" target="_blank">Click to
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
                            <td style="margin-top:50px;border:none;" class="uk-width-5-10 uk-text-bold">Master's
                                Remarks:</td>
                            <td style="border:none;" class="uk-width-5-10 uk-text-bold"><textarea id="remarks_master"
                                    name="remarks_master" rows="5"
                                    cols="65"><?php echo $edit_row['remarks_master'] ?> </textarea></td>
                        </tr>
                        <tr class="uk-text-small">
                            <td class="uk-width-5-10 uk-text-bold" style="border:none;font-size: 15px;"></td>
                            <td class="uk-width-5-10" id="dvSSM_file">
                                <input class="uk-width-1-1" type="file" name="file_s" id="file_s"
                                    accept=".doc, .docx, .pdf, .png, .jpg, .jpeg">

                                <?php
                    if ($edit_row["doc_remarks"]!="") {
                    ?>
                                <a href="admin/uploads/<?php echo $edit_row['doc_remarks']?>" target="_blank">Click to
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
                            <td style="margin-top:50px;border:none;" class="uk-width-5-10 uk-text-bold">Payment Status</td>
                            <td style="border:none;" class="uk-width-5-10 uk-text-bold">
                                <select name="sb" id="sb" class="uk-width-1-1">
                                        <option value="">Payment Status Form 1</option>
                                        <option value="Paid" <?php // if($edit_row['subject']=='Paid') {echo 'selected';}?>>Paid</option>
                                        <option value="Pending" <?php // if($edit_row['subject']=='Pending') {echo 'selected';}?>>Pending</option>
                                </select>
                            </td>
                        </tr>



                    </table>
                </div>
            </div>
            <div class="uk-width-10-10"
                style="font-size:18px; font-weight:bold; border:none;text-align: right; margin-top: 20px; padding-right: 25px;">
                <span style="margin-right: 0;" onclick="myFunction()" href="javascript:void(0);" id="form_2" class="form_2">NEXT</span></div>

        </form>



        <form style="display:none;" name="frmdeclaration2" id="frmdeclaration2" method="post"
            action="index.php?p=declaration&amp;mode=SAVE">

            <div class="uk-grid">
                <div class="uk-width-medium-5-10">
                    <table class="uk-table">
                        <tbody>
                            <tr>
                                <td class="uk-text-bold">Franchisee's Name:</td>
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
                <div class="uk-width-medium-3-10">
                    <table class="uk-table">
                        <tbody>
                            <!-- <tr>
			  <td class="uk-text-bold">Month:</td>
			  <td></td>
			  <td class="uk-text-bold">Year:</td>
			  <td></td>
			</tr> -->
                            <tr>
                                <td colspan="5" class="uk-text-bold">Date of submission</td>
                                <td><?php echo $today_date;?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div style="padding-left: 0px; padding-right: 0px;" class="uk-width-medium-2-10">
                    <img src="/images/Artboard – 4.png" alt="" class="img_logo">
                </div>
            </div>
            <div class="uk-grid">
                <div class="uk-width-medium-10-10">
                    <table class="uk-table uk-table-small">
                        <tr>
                            <td colspan="5" style="font-size:18px; font-weight:bold;">CALCULATION OF GROSS TURNOVER</td>
                        </tr>



                        <!-- from 2 full start  -->

                        <!-- a start -->
                        <tr>
                            <td class="uk-width-6-10" style="font-size:16px; font-weight:bold; border:none;">A: School Fees <br>Foundation Programme</td>
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
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
                            $total_EDP_school_adjust2=0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center; white-space:nowrap;" class="uk-width-1-10">
                            <?php 
                                $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".$row['fees_structure']."' and f.enhanced_adjust !='' and fl.foundation_int_english=1 and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_EDP_enhanced_adjust += $level_count * $row['enhanced_adjust'];
                                //$total_EDP_student += $level_count; 
                                ?>
                                <input class="edp_tsd edp_tsdt" type="number" step="0.01" name="edp_EDP" id="edp_EDP"
                                    value="<?php echo $level_count ?>"> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none; white-space:nowrap" class="uk-width-1-10">
                                <input class="edp_tsd edp_tsdt" type="number" name="edp_EDP_st" id="edp_EDP_st"
                                    value="<?php echo $row['enhanced_adjust']?>"> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="edp_EDP_t[]" id="edp_EDP_t"
                                    value="<?php echo $level_count * $row['enhanced_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php } } ?>
                        <tr class="">
                            <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold"></td>
                            <td class="uk-width-1-10"></td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_EDP_enhanced_adjust;  ?>">
                            </td>
                        </tr>
                        <!--i EDP start-->

                        <!--i QF1 start-->
                        <tr class="">
                            <td style=" margin-top:20px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">QF1:</td>
                        </tr>
                        <?php
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php 
                                $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".$row['fees_structure']."' and f.enhanced_adjust !='' and fl.foundation_int_english=1 and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_QF1_enhanced_adjust += $level_count * $row['enhanced_adjust'];
                                //$total_EDP_student += $level_count;
                                ?>
                                <input class="edp_tsd edp_tsdt" type="number" step="0.01" name="edp_EDP" id="edp_EDP"
                                    value="<?php echo $level_count ?>"> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none; white-space:nowrap" class="uk-width-1-10">
                                <input class="edp_tsd edp_tsdt" type="number" name="edp_EDP_st" id="edp_EDP_st"
                                    value="<?php echo $row['enhanced_adjust']?>"> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="edp_EDP_t[]" id="edp_EDP_t"
                                    value="<?php echo $level_count * $row['enhanced_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php } } ?>
                        <tr class="">
                            <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold"></td>
                            <td class="uk-width-1-10"></td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_QF1_enhanced_adjust;  ?>">
                            </td>
                        </tr>
                        <!--i QF1 end-->
                        <!--i QF2 start-->
                        <tr class="">
                            <td style=" margin-top:20px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">QF2:</td>
                        </tr>
                        <?php
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php 
                                $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".$row['fees_structure']."' and f.enhanced_adjust !='' and fl.foundation_int_english=1 and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_QF2_enhanced_adjust += $level_count * $row['enhanced_adjust'];
                                //$total_EDP_student += $level_count;
                                ?>
                                <input class="edp_tsd edp_tsdt" type="number" step="0.01" name="edp_EDP" id="edp_EDP"
                                    value="<?php echo $level_count ?>"> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none; white-space:nowrap" class="uk-width-1-10">
                                <input class="edp_tsd edp_tsdt" type="number" name="edp_EDP_st" id="edp_EDP_st"
                                    value="<?php echo $row['enhanced_adjust']?>"> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="edp_EDP_t[]" id="edp_EDP_t"
                                    value="<?php echo $level_count * $row['enhanced_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php } } ?>
                        <tr class="">
                            <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold"></td>
                            <td class="uk-width-1-10"></td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_QF2_enhanced_adjust;  ?>">
                            </td>
                        </tr>
                        <!--i QF2 end-->



                        <!--i QF3 start-->
                        <tr class="">
                            <td style=" margin-top:20px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">QF3:</td>
                        </tr>
                        <?php
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php 
                                $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".$row['fees_structure']."' and f.enhanced_adjust !='' and fl.foundation_int_english=1 and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_QF3_enhanced_adjust += $level_count * $row['enhanced_adjust'];
                                //$total_EDP_student += $level_count;
                                ?>
                                <input class="edp_tsd edp_tsdt" type="number" step="0.01" name="edp_EDP" id="edp_EDP"
                                    value="<?php echo $level_count ?>"> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none; white-space:nowrap" class="uk-width-1-10">
                                <input class="edp_tsd edp_tsdt" type="number" name="edp_EDP_st" id="edp_EDP_st"
                                    value="<?php echo $row['enhanced_adjust']?>"> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="edp_EDP_t[]" id="edp_EDP_t"
                                    value="<?php echo $level_count * $row['enhanced_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php } } ?>
                        <tr class="">
                            <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold"></td>
                            <td class="uk-width-1-10"></td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_QF3_enhanced_adjust;  ?>">
                            </td>
                        </tr>
                        <!--i QF3 end-->




                        <tr class="">
                            <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold"></td>
                            <td class="uk-width-1-10"></td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total (i)" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_enhanced_adjust = $total_EDP_enhanced_adjust + $total_QF1_enhanced_adjust  + $total_QF2_enhanced_adjust + $total_QF3_enhanced_adjust;  ?>">
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
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".$row['fees_structure']."' and f.international_adjust !='' and fl.foundation_int_art=1 and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_EDP_international_adjust += $level_count * $row['international_adjust'];
                                //$total_EDP_student += $level_count;
                                ?>
                                <input class="edp_tsd multimedia" type="number" step="0.01" name="multimedia_s"
                                    id="multimedia_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd multimedia" type="number" name="multimedia_st" id="multimedia_st"
                                    value="<?php echo $row['international_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="multimedia_t"
                                    id="multimedia_t" value="<?php echo $level_count * $row['international_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php } } ?>
                        <tr class="">
                            <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold"></td>
                            <td class="uk-width-1-10"></td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_EDP_international_adjust;  ?>" readonly>
                            </td>
                        </tr>
                        <!-- multimedia edp end -->
                        <!-- multimedia QF1 start -->

                        <tr class="">
                            <td style=" margin-top:20px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">QF1:</td>
                        </tr>
                        <?php
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".$row['fees_structure']."' and f.international_adjust !='' and fl.foundation_int_art=1 and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_QF1_international_adjust += $level_count * $row['international_adjust'];
                                //$total_EDP_student += $level_count;
                                ?>
                                <input class="edp_tsd multimedia" type="number" step="0.01" name="multimedia_s"
                                    id="multimedia_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd multimedia" type="number" name="multimedia_st" id="multimedia_st"
                                    value="<?php echo $row['international_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="multimedia_t"
                                    id="multimedia_t" value="<?php echo $level_count * $row['international_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php } } ?>
                        <tr class="">
                            <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold"></td>
                            <td class="uk-width-1-10"></td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_QF1_international_adjust;  ?>" readonly>
                            </td>
                        </tr>
                        <!-- multimedia qf1 end -->
                        <!-- multimedia QF2 start -->

                        <tr class="">
                            <td style=" margin-top:20px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">QF2:</td>
                        </tr>
                        <?php
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".$row['fees_structure']."' and f.international_adjust !='' and fl.foundation_int_art=1 and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_QF2_international_adjust += $level_count * $row['international_adjust'];
                                //$total_EDP_student += $level_count;
                                ?>
                                <input class="edp_tsd multimedia" type="number" step="0.01" name="multimedia_s"
                                    id="multimedia_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd multimedia" type="number" name="multimedia_st" id="multimedia_st"
                                    value="<?php echo $row['international_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="multimedia_t"
                                    id="multimedia_t" value="<?php echo $level_count * $row['international_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php } } ?>
                        <tr class="">
                            <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold"></td>
                            <td class="uk-width-1-10"></td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_QF2_international_adjust;  ?>" readonly>
                            </td>
                        </tr>
                        <!-- multimedia QF2 end -->
                        <!-- multimedia QF3 start -->
                        <tr class="">
                            <td style=" margin-top:20px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">QF3:</td>
                        </tr>
                        <?php
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".$row['fees_structure']."' and f.international_adjust !='' and fl.foundation_int_art=1 and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_QF2_international_adjust += $level_count * $row['international_adjust'];
                                //$total_EDP_student += $level_count;
                                ?>
                                <input class="edp_tsd multimedia" type="number" step="0.01" name="multimedia_s"
                                    id="multimedia_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd multimedia" type="number" name="multimedia_st" id="multimedia_st"
                                    value="<?php echo $row['international_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="multimedia_t"
                                    id="multimedia_t" value="<?php echo $level_count * $row['international_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php } } ?>
                        <tr class="">
                            <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold"></td>
                            <td class="uk-width-1-10"></td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_QF3_international_adjust;  ?>" readonly>
                            </td>
                        </tr>
                        <!-- multimedia qf3 end -->

                        <tr class="">
                            <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold"></td>
                            <td class="uk-width-1-10"></td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total (ii)" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_international_adjust = $total_EDP_international_adjust + $total_QF1_international_adjust  + $total_QF2_international_adjust + $total_QF3_international_adjust;  ?>" readonly>
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
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold"> <span
                                    class="text-danger"></span><?php echo $row['fees_structure']?></td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".$row['fees_structure']."' and f.mandarin_adjust !='' and fl.foundation_int_mandarin=1 and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_EDP_mandarin_adjust += $level_count * $row['mandarin_adjust'];
                                //$total_EDP_student += $level_count;
                                ?>
                                <input class="edp_tsd afternoon" type="number" step="0.01" name="afternoon_s"
                                    id="afternoon_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd afternoon" type="number" name="afternoon_st" id="afternoon_st"
                                    value="<?php echo $row['mandarin_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="afternoon_t"
                                    id="afternoon_t" value="<?php echo $level_count * $row['mandarin_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php } } ?>
                        <tr class="">
                            <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold"></td>
                            <td class="uk-width-1-10"></td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_EDP_mandarin_adjust;  ?>" readonly>
                            </td>
                        </tr>
                        <!-- Afternoon edp end -->
                        <!-- Afternoon QF1 start -->

                        <tr class="">
                            <td style=" margin-top:20px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">QF1:</td>
                        </tr>
                        <?php
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold"> <span
                                    class="text-danger"></span><?php echo $row['fees_structure']?></td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".$row['fees_structure']."' and f.mandarin_adjust !='' and fl.foundation_int_mandarin=1 and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_QF1_mandarin_adjust += $level_count * $row['mandarin_adjust'];
                                //$total_EDP_student += $level_count;
                                ?>
                                <input class="edp_tsd afternoon" type="number" step="0.01" name="afternoon_s"
                                    id="afternoon_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd afternoon" type="number" name="afternoon_st" id="afternoon_st"
                                    value="<?php echo $row['mandarin_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="afternoon_t"
                                    id="afternoon_t" value="<?php echo $level_count * $row['mandarin_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php } } ?>
                        <tr class="">
                            <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold"></td>
                            <td class="uk-width-1-10"></td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_QF1_mandarin_adjust;  ?>" readonly>
                            </td>
                        </tr>
                        <!-- Afternoon QF1 end -->
                        <!-- Afternoon QF2 start -->

                        <tr class="">
                            <td style=" margin-top:20px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">QF2:</td>
                        </tr>

                        <?php
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold"> <span
                                    class="text-danger"></span><?php echo $row['fees_structure']?></td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".$row['fees_structure']."' and f.mandarin_adjust !='' and fl.foundation_int_mandarin=1 and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_QF2_mandarin_adjust += $level_count * $row['mandarin_adjust'];
                                //$total_EDP_student += $level_count;
                                ?>
                                <input class="edp_tsd afternoon" type="number" step="0.01" name="afternoon_s"
                                    id="afternoon_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd afternoon" type="number" name="afternoon_st" id="afternoon_st"
                                    value="<?php echo $row['mandarin_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="afternoon_t"
                                    id="afternoon_t" value="<?php echo $level_count * $row['mandarin_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php } } ?>
                        <tr class="">
                            <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold"></td>
                            <td class="uk-width-1-10"></td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_QF2_mandarin_adjust;  ?>" readonly>
                            </td>
                        </tr>
                        <!-- Afternoon QF2 end -->
                        <!-- Afternoon QF3 start -->

                        <tr class="">
                            <td style=" margin-top:20px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">QF3:</td>
                        </tr>

                        <?php
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold"> <span
                                    class="text-danger"></span><?php echo $row['fees_structure']?></td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".$row['fees_structure']."' and f.mandarin_adjust !='' and fl.foundation_int_mandarin=1 and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_QF3_mandarin_adjust += $level_count * $row['mandarin_adjust'];
                                //$total_EDP_student += $level_count;
                                ?>
                                <input class="edp_tsd afternoon" type="number" step="0.01" name="afternoon_s"
                                    id="afternoon_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd afternoon" type="number" name="afternoon_st" id="afternoon_st"
                                    value="<?php echo $row['mandarin_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="afternoon_t"
                                    id="afternoon_t" value="<?php echo $level_count * $row['mandarin_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php } } ?>
                        <tr class="">
                            <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold"></td>
                            <td class="uk-width-1-10"></td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_QF3_mandarin_adjust;  ?>" readonly>
                            </td>
                        </tr>
                        <!-- Afternoon QF3 end -->

                        <tr class="">
                            <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold"></td>
                            <td class="uk-width-1-10"></td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total (iii)" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_mandarin_adjust = $total_EDP_mandarin_adjust + $total_QF1_mandarin_adjust  + $total_QF2_mandarin_adjust + $total_QF3_mandarin_adjust;  ?>" readonly>
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
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='EDP' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold"> <span
                                    class="text-danger"></span><?php echo $row['fees_structure']?></td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='EDP' and f.fees_structure='".$row['fees_structure']."' and f.iq_math_adjust !='' and fl.foundation_iq_math=1 and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_EDP_iq_math_adjust += $level_count * $row['iq_math_adjust'];
                                //$total_EDP_student += $level_count;
                                ?>
                                <input class="edp_tsd afternoon" type="number" step="0.01" name="afternoon_s"
                                    id="afternoon_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd afternoon" type="number" name="afternoon_st" id="afternoon_st"
                                    value="<?php echo $row['iq_math_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="afternoon_t"
                                    id="afternoon_t" value="<?php echo $level_count * $row['iq_math_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php } } ?>
                        <tr class="">
                            <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold"></td>
                            <td class="uk-width-1-10"></td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_EDP_iq_math_adjust;  ?>" readonly>
                            </td>
                        </tr>
                                
                        <!-- Mobile App edp end -->
                        <!-- Mobile App QF1 start -->

                        <tr class="">
                            <td style=" margin-top:20px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">QF1:</td>
                        </tr>
                        <?php
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF1' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold"> <span
                                    class="text-danger"></span><?php echo $row['fees_structure']?></td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF1' and f.fees_structure='".$row['fees_structure']."' and f.iq_math_adjust !='' and fl.foundation_iq_math=1 and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_QF1_iq_math_adjust += $level_count * $row['iq_math_adjust'];
                                //$total_EDP_student += $level_count;
                                ?>
                                <input class="edp_tsd afternoon" type="number" step="0.01" name="afternoon_s"
                                    id="afternoon_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd afternoon" type="number" name="afternoon_st" id="afternoon_st"
                                    value="<?php echo $row['iq_math_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="afternoon_t"
                                    id="afternoon_t" value="<?php echo $level_count * $row['iq_math_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php } } ?>
                        <tr class="">
                            <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold"></td>
                            <td class="uk-width-1-10"></td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_QF1_iq_math_adjust;  ?>" readonly>
                            </td>
                        </tr>
                        <!-- Mobile App QF1 end -->
                        <!-- Mobile App QF2 start -->

                        <tr class="">
                            <td style=" margin-top:20px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">QF2:</td>
                        </tr>

                        <?php
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF2' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold"> <span
                                    class="text-danger"></span><?php echo $row['fees_structure']?></td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF2' and f.fees_structure='".$row['fees_structure']."' and f.iq_math_adjust !='' and fl.foundation_iq_math=1 and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_QF2_iq_math_adjust += $level_count * $row['iq_math_adjust'];
                                //$total_EDP_student += $level_count;
                                ?>
                                <input class="edp_tsd afternoon" type="number" step="0.01" name="afternoon_s"
                                    id="afternoon_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd afternoon" type="number" name="afternoon_st" id="afternoon_st"
                                    value="<?php echo $row['iq_math_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="afternoon_t"
                                    id="afternoon_t" value="<?php echo $level_count * $row['iq_math_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php } } ?>
                        <tr class="">
                            <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold"></td>
                            <td class="uk-width-1-10"></td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_QF2_iq_math_adjust;  ?>" readonly>
                            </td>
                        </tr>
                        <!-- Mobile App QF2 end -->
                        <!-- Mobile App QF3 start -->

                        <tr class="">
                            <td style=" margin-top:20px;border:none; font-size:16px;"
                                class="uk-width-6-10 uk-text-bold">QF3:</td>
                        </tr>

                        <?php
							$sql="SELECT * from `fee_structure` where centre_code='$centre_code' and subject='QF3' and status='Approved' and extend_year='$year'";
							//echo $sql;
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold"> <span
                                    class="text-danger"></span><?php echo $row['fees_structure']?></td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where year(fl.programme_date) = '$year' and ps.student_entry_level != '' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and ps.student_entry_level ='QF3' and f.fees_structure='".$row['fees_structure']."' and f.iq_math_adjust !='' and fl.foundation_iq_math=1 and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) group by ps.student_entry_level, s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                }
                                $total_QF3_iq_math_adjust += $level_count * $row['iq_math_adjust'];
                                //$total_EDP_student += $level_count;
                                ?>
                                <input class="edp_tsd afternoon" type="number" step="0.01" name="afternoon_s"
                                    id="afternoon_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd afternoon" type="number" name="afternoon_st" id="afternoon_st"
                                    value="<?php echo $row['iq_math_adjust']?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="afternoon_t"
                                    id="afternoon_t" value="<?php echo $level_count * $row['iq_math_adjust'];  ?>" readonly><br>
                            </td>
                        </tr>
                        <?php } } ?>
                        <tr class="">
                            <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold"></td>
                            <td class="uk-width-1-10"></td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_QF3_iq_math_adjust;  ?>" readonly>
                            </td>
                        </tr>
                        <!-- Mobile App QF3 end -->

                        <tr class="">
                            <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold"></td>
                            <td class="uk-width-1-10"></td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total (iv)" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_iq_math_adjust = $total_EDP_iq_math_adjust + $total_QF1_iq_math_adjust  + $total_QF2_iq_math_adjust + $total_QF3_iq_math_adjust;  ?>" readonly>
                            </td>
                        </tr>
                        <!-- Mobile App end -->



                        <tr class="">
                            <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold"></td>
                            <td class="uk-width-1-10"></td>
                            <td style="text-align:center;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                    placeholder="Total A (Full Day)" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;" class="uk-width-1-10">

                                <input class="total_a" type="number" step="0.01" name="school_total_f"
                                    id="school_total_f" value="<?php echo $total_a2 = $total_iq_math_adjust+$total_mandarin_adjust+$total_international_adjust+$total_enhanced_adjust; ?>">
                            </td>
                        </tr>
                        <!--full day end-->
                        <!-- end school fees A  -->
                        <!-- a end form 2-->

                    </table>
                    <table class="uk-table uk-table-small">
                        <tr>
                            <td class="uk-width-6-10" style="font-size:18px; font-weight:bold; border:none;">B: Readers
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
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">(i) Link &
                                Think Series(Termly)<span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count, fees from (SELECT s.id, p.unit_price as fees from student s inner join `collection` c on c.student_id = s.id inner join product p on p.product_code=c.product_code where c.year = '$year' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and p.product_name like 'LTR%' and month(c.collection_date_time) = $month group by s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                $fees = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                    $fees = (empty($roww["fees"]) ? "0" : $roww["fees"]); 
                                }
                                $total_link_think_series += $level_count * $fees;
                                ?>
                                <input class="edp_tsd pre_school" type="number" step="0.01" name="pre_school_s"
                                    id="pre_school_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd pre_school" type="number" name="pre_school_st" id="pre_school_st"
                                    value="<?php echo $fees ?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust2" type="number" step="0.01" name="pre_school_t"
                                    id="pre_school_t" value="<?php echo $total_link_think_series ?>" readonly><br>
                            </td>
                        <!--<tr class="">
					   <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">Foundation e-Reader(*Optional for Home Usage)<span class="text-danger"></span>:</td>
					  <td style="border:none; text-align:center;" class="uk-width-1-10">	
						 <input class="edp_tsd usage" type="number" step="0.01" name="usage_s" id="usage_s" value=""> <span class="edp_eq">✕ </span>
					  </td>
					  <td style="border:none;" class="uk-width-1-10">	
						 <input class="edp_tsd usage" type="number" name="usage_st" id="usage_st" value=""> <span class="edp_eq2">= </span>
					  </td>
					  <td style="border:none; text-align:center;" class="uk-width-1-10">	
						 <input class="linkschool_adjust5" type="number" step="0.01" name="usage_t" id="usage_t" value=""><br>
					  </td>
					</tr>-->
                        <tr class="">
                            <td style="margin-top:50px;border:none;" class="uk-width-1-10 uk-text-bold"></td>
                            <td style="margin-top:50px;border:none;" class="uk-width-1-10"></td>
                            <td style="text-align:center;border:none;" class="uk-width-1-10">
                                <input class="edp_tsd" type="number" step="0.01" name="total_usage" id="total_usage"
                                    placeholder="Total B" readonly> <span style="font-size:14px;"
                                    class="edp_eq">RM</span>
                            </td>
                            <td style="text-align:center;border:none;" class="uk-width-1-10">

                                <input class="total_artt" type="number" step="0.01" name="total_usaget"
                                    id="total_usaget" value="<?php echo $total_b2 = $total_link_think_series;  ?>" readonly>
                            </td>
                        </tr>
                        <tr class="">
                            <td colspan="5">Note: as per Student Fee List for each Academic Year as prescribed by the
                                Franchisor<br>*as stated in the Operations e-Manual</td>

                        </tr>
                        <tr class="">
                            <td style=" margin-top:50px;border:none; font-size:18px;"
                                class="uk-width-6-10 uk-text-bold">C: Products</td>
                        </tr>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">(i) Foundation
                                e-Reader (*Optional for Home Usage)<span class="text-danger"></span>:</td>
                            <td style=" text-align:center;border:none;white-space:nowrap;" class="uk-width-1-10">
                            <?php
                            $sql="SELECT count(id) level_count, fees from (SELECT s.id, p.unit_price as fees from student s inner join `collection` c on c.student_id = s.id inner join product p on p.product_code=c.product_code where c.year = '$year' and s.student_status = 'A' and s.centre_code='$centre_code' and s.deleted='0' and p.product_name like 'e-reader%' and month(c.collection_date_time) = $month group by s.id) ab";
                                $resultt=mysqli_query($connection, $sql);
                                //echo $sql;
                                $num_row=mysqli_num_rows($resultt);
                                $level_count = 0;
                                $fees = 0;
                                while ($roww=mysqli_fetch_assoc($resultt)) {
                                    $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
                                    $fees = (empty($roww["fees"]) ? "0" : $roww["fees"]); 
                                }
                                $total_foundation_e_reader += $level_count * $fees;
                                ?>
                                <input class="edp_tsd pre_school" type="number" step="0.01" name="pre_school_s"
                                    id="pre_school_s" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd pre_school" type="number" name="pre_school_st" id="pre_school_st"
                                    value="<?php echo $fees ?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust2" type="number" step="0.01" name="pre_school_t"
                                    id="pre_school_t" value="<?php echo $total_foundation_e_reader ?>" readonly><br>
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
                                    id="total_foun_school" value="<?php echo $total_c2 = $total_foundation_e_reader;  ?>" readonly>
                            </td>
                        </tr>
                    </table>
                    <table class="uk-table uk-table-small">
                        <tr>
                            <td class="uk-width-6-10" style="font-size:18px; font-weight:bold; border:none;">(1) ROYALTY
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
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">Royalty Fee (5%
                                on Gross Tumover of A(i), A(ii), B(i))<span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                <input style="position:relative;" class="edp_tsd royaltyi" type="number" step="0.01"
                                    name="royalty_si" id="royalty_si" value="5" readonly><span
                                    style="margin-left: -30px;font-size: 20px;position: absolute;">%</span> <span
                                    style="" class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd royaltyi" type="number" name="royalty_sti" id="royalty_sti"
                                    value="<?php echo $total_enhanced_adjust + $total_international_adjust + $total_link_think_series; ?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="royalty_tii_r" type="number" step="0.01" name="royalty_ti" id="royalty_ti"
                                    value="<?php echo $royalty_fee1 = (($total_enhanced_adjust + $total_international_adjust + $total_link_think_series)/100)*5; $total_royalty_fee2 =$royalty_fee1; ?>" readonly><br>
                            </td>
                        </tr>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">Royalty Fee (5%
                                on Gross Tumover of A(iii), A(iv), C(i))<span class="text-danger"></span>:</td>
                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                <input style="position:relative;" class="edp_tsd royaltyii" type="number" step="0.01"
                                    name="royalty_sii" id="royalty_sii" value="5" readonly> <span
                                    style="margin-left: -30px;font-size: 20px;position: absolute;">%</span> <span
                                    style="" class="edp_eq">✕ </span>
                            </td>
                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                <input class="edp_tsd royaltyii" type="number" name="royalty_stii" id="royalty_stii"
                                    value="<?php echo $total_mandarin_adjust + $total_iq_math_adjust + $total_foundation_e_reader; ?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="royalty_tii_r" type="number" step="0.01" name="royalty_tii"
                                    id="royalty_tii" value="<?php echo $royalty_fee2 = (($total_mandarin_adjust + $total_iq_math_adjust + $total_foundation_e_reader)/100)*5; $total_royalty_fee2 +=$royalty_fee2; ?>" readonly><br>
                            </td>
                        </tr>
                    </table>
                    <table class="uk-table uk-table-small">
                        <tr>
                            <td colspan="5" style="font-size:18px; font-weight:bold;"></td>
                        </tr>
                        <tr>
                            <td class="uk-width-6-10" style="font-size:18px; font-weight:bold; border:none;">(2) A&P FEE
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
                                    id="advertising_sti" value="<?php echo $total_a2; ?>" readonly> <span class="edp_eq2">= </span>
                            </td>
                            <td style=" text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust" type="number" step="0.01" name="advertising_ti"
                                    id="advertising_ti" value="<?php echo $advertising_promotion_fee2 = ($total_a2/100)*3; ?>" readonly><br>
                            </td>
                        </tr>
                    </table>

                    <table class="uk-table uk-table-small">
                        <tr class="">
                            <td style=" margin-top:50px; border:none;" class="uk-width-6-10 uk-text-bold">Sub Total
                                (1)<span class="text-danger"></span>:</td>
                            <td style="border:none;" class="uk-width-1-10"></td>
                            <td style="border:none;" class="uk-width-1-10"></td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust56" type="number" step="0.01" name="grand_totali"
                                    id="grand_totali" value="<?php echo $sub_total_1 = sprintf('%0.2f', $total_1_2_3); ?>" readonly><br>
                            </td>
                        </tr>
                        <tr class="">
                            <td style=" margin-top:50px; border:none;" class="uk-width-6-10 uk-text-bold">Sub Total
                                (2)<span class="text-danger"></span>:</td>
                            <td style="border:none;" class="uk-width-1-10"></td>
                            <td style="border:none;" class="uk-width-1-10"></td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust56" type="number" step="0.01" name="grand_totali"
                                    id="grand_totali" value="<?php $sub_total_2 = $total_royalty_fee2 + $advertising_promotion_fee2; echo sprintf('%0.2f', $sub_total_2); ?>" readonly><br>
                            </td>
                        </tr>
                        <tr class="">
                            <td style=" margin-top:50px; border:none;" class="uk-width-6-10 uk-text-bold">Grand Total
                                (2)<span class="text-danger"></span>:</td>
                            <td style="border:none;" class="uk-width-1-10"></td>
                            <td style="border:none;" class="uk-width-1-10"></td>
                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                <input class="school_adjust56" type="number" step="0.01" name="grand_totali"
                                    id="grand_totali" value="<?php $grand_total2 = $sub_total_1 + $sub_total_2; echo sprintf('%0.2f', $grand_total2); ?>" readonly><br>
                            </td>
                        </tr>
                        <tr class="">
                            <td style=" margin-top:50px;" class="uk-width-6-10 uk-text-bold">PAYABLE TO Q-DEES WORLDWIDE
                                EDUSYSTEMS (M) SDN BHD (MBB 514196314454)<span class="text-danger"></span>:</td>
                            <td style="" class="uk-width-1-10"></td>
                            <td style="" class="uk-width-1-10"></td>
                            <td style=" text-align:center;" class="uk-width-1-10"></td>
                        </tr>
                    </table>
                    <table class="uk-table uk-table-small">
                        <tr>
                            <td class="uk-width-6-10" style="font-size:18px; font-weight:bold; border:none;">TERMS AND
                                CONDITIONS</td>
                        </tr>
                        <tr class="">
                            <td style="border:none;" colspan="5"><i>The Franchisee shall pay to Franchisor Royalty Fee,
                                    Advertising & Promotion Fee and Software License Fee, according to the terms and
                                    conditions stated in this declaration, in Ringgit Malaysia on a monthly basis, on
                                    the 1st day but not later than the 5th day of each month. Late payments shall be
                                    levied with an additional 1.5% charge per month. <b>(This Declaration Form shall
                                        constitute as a legally binding contract between the parties and shall be read
                                        and construed as if the Declaration Form were inserted in the Franchise
                                        Agreement as an integral part of the Franchise Agreement.)</b><i></td>
                        </tr>
                        <tr class="">
                            <td style="border:none; " colspan="5"><i>I <?php echo $operator_name;?>, hereby acknowledge
                                    and agree to the above terms and I hereby declare the information above to be
                                    accurate, complete and in compliance with the law of Malaysia. I also acknowledge
                                    that I have taken all reasonable steps to ensure the same.<i></td>
                        </tr>
                        <tr>
                            <td class="uk-width-5-10"style="font-size:18px; font-weight:bold; border:none; padding-top: 120px;" colspan="4">
                            <span style="border-top: 2px solid; padding-left: 100px; padding-right: 100px;margin-left: 75px;">Signature
                                    of Key Operator</span></td>
                            <!-- <td class="uk-width-5-10" style="font-size:18px; font-weight:bold; border:none;text-align: center;">Official Stamp of Franchisee</td> -->
                        </tr>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-5-10 uk-text-bold">Name:
                                <?php echo $operator_name;?> </td>
                        </tr>
                        <tr class="">
                            <td style=" margin-top:50px;border:none;" class="uk-width-2-10 uk-text-bold">Date:
                                <?php// echo $created_date;?></td>
                        </tr>
                        <tr>
                            <td style="margin-top:50px;border:none;" class="uk-width-5-10 uk-text-bold">Centre's
                                Remarks:</td>
                            <td style="border:none;" class="uk-width-5-10 uk-text-bold"><textarea id="remarks_centre2"
                                    name="remarks_centre" rows="5"
                                    cols="65"><?php echo $edit_row['remarks_centre'] ?> </textarea></td>
                        </tr>
                        <tr class="uk-text-small">
                            <td class="uk-width-5-10 uk-text-bold" style="border:none;font-size: 15px;"></td>
                            <td class="uk-width-5-10" id="dvSSM_file">
                                <input class="uk-width-1-1" type="file" name="file_s" id="file_s"
                                    accept=".doc, .docx, .pdf, .png, .jpg, .jpeg">

                                <?php
                    if ($edit_row["doc_remarks"]!="") {
                    ?>
                                <a href="admin/uploads/<?php echo $edit_row['doc_remarks']?>" target="_blank">Click to
                                    view document</a>
                                <?php
                    }
                    ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="margin-top:50px;border:none;" class="uk-width-5-10 uk-text-bold">Master's
                                Remarks:</td>
                            <td style="border:none;" class="uk-width-5-10 uk-text-bold"><textarea id="remarks_master"
                                    name="remarks_master" rows="5"
                                    cols="65"><?php echo $edit_row['remarks_master'] ?> </textarea></td>
                        </tr>
                        <tr class="uk-text-small">
                            <td class="uk-width-5-10 uk-text-bold" style="border:none;font-size: 15px;"></td>
                            <td class="uk-width-5-10" id="dvSSM_file">
                                <input class="uk-width-1-1" type="file" name="file_s" id="file_s"
                                    accept=".doc, .docx, .pdf, .png, .jpg, .jpeg">

                                <?php
                    if ($edit_row["doc_remarks"]!="") {
                    ?>
                                <a href="admin/uploads/<?php echo $edit_row['doc_remarks']?>" target="_blank">Click to
                                    view document</a>
                                <?php
                    }
                    ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="margin-top:50px;border:none;" class="uk-width-5-10 uk-text-bold">Payment Status</td>
                            <td style="border:none;" class="uk-width-5-10 uk-text-bold">
                                <select name="sb" id="sb" class="uk-width-1-1">
                                        <option value="">Payment Status Form 2</option>
                                        <option value="Paid" <?php // if($edit_row['subject']=='Paid') {echo 'selected';}?>>Paid</option>
                                        <option value="Pending" <?php // if($edit_row['subject']=='Pending') {echo 'selected';}?>>Pending</option>
                                </select>
                            </td>
                        </tr>



                    </table>
                </div>
            </div>
            <div class="uk-width-medium-10-10 uk-text-center">
                <br>
                <button type="submit" id="submit" name="submit"
                    class="uk-button uk-button-primary form_btn uk-text-center">Save</button>
            </div>
        </form>
    </div>
</div>


<!-- <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color">Searching</h2>
   </div>

   <form class="uk-form" name="frmCentreSearch" id="frmCentreSearch" method="get">
      <input type="hidden" name="mode" id="mode" value="BROWSE">
      <input type="hidden" name="p" id="p" value="fee_structure_setting">


      <div class="uk-grid">
         <div class="uk-width-2-10 uk-text-small">
			<input type="text" name="n" id="n" value="<?php echo $_GET['n']?>" placeholder="Name of Fees Structure">
            
         </div> 
		 <div class="uk-width-2-10 uk-text-small">
			<input type="text" name="sb" id="sb" value="<?php echo $_GET['sb']?>" placeholder="Student Entry Level">
            
         </div> 
		 <div class="uk-width-2-10 uk-text-small">
			<input type="text" name="st" id="st" value="<?php echo $_GET['st']?>" placeholder="Programme Package">
            
         </div> 
         <div class="uk-width-3-10">
            <button class="uk-button uk-width-1-1">Search</button>
         </div>
      </div>
   </form><br> -->
   <div class="uk-margin-right">
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
				$n=$_GET['n'];
				$st=$_GET['st'];
				$sb=$_GET['sb'];
            $sql = "SELECT * from fee_structure where 1=1 and centre_code='$centre_code'";
			 if($n!=""){
				$sql=$sql."and fees_structure like '%$n%' ";   
			  }
			  if($st!=""){
				  $sql=$sql."and programme_package like '%$st%' ";
			  }
			  if($sb!=""){
				  $sql=$sql."and subject like '%$sb%' ";
			  }
	   //echo $sql; 
            $result = mysqli_query($connection, $sql);
						
            $num_row = mysqli_num_rows($result);
			//$m_row = mysqli_fetch_assoc($result) 
			//print_r($num_row); die;
               
                ?>
				
				
                    <?php

                    if ($num_row>0) {
                        while ($browse_row=mysqli_fetch_assoc($result)) {
                            $sha1_id=sha1($browse_row["id"]);
                            ?>
                            <tr class="uk-text-small">
                                <td><?php echo $browse_row["demooooo"]?></td>
                                <td><?php echo $browse_row["demooooo"]?></td>
                                <td><?php echo $browse_row["demooooo"]?></td>
                                <td><?php echo $browse_row["demooooo"]?></td>
                                <td><?php echo $browse_row["demooooo"]?></td>
                                <td><?php echo $browse_row["demooooo"]?></td>
                                <td><?php echo $browse_row["demooooo"]?></td>
                                <td><?php echo $browse_row["demooooo"]?></td>
								<td style="width:120px">
									<a href="index.php?p=fee_structure_setting&id=<?php echo $sha1_id?>&mode=EDIT" data-uk-tooltip title="View"><img src="images/edit.png"></a>
                                          &nbsp; &nbsp; 
									<?php
									$id = $browse_row["id"];
									$sql3="SELECT * from `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id=f.id where f.id = '$id' limit 1";
									//echo $sql3;
									$result3=mysqli_query($connection, $sql3);
									$num_row3=mysqli_num_rows($result3);
									if ($num_row3==0) { ?>
                                          
                                          <a onclick="doDeleteRecord('<?php echo $sha1_id?>')" href="#" id="btnDelete"><img src="images/delete.png"></a>
									<?php
										} 
									?>
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
   <input type="hidden" name="p" value="fee_structure_setting">
   <input type="hidden" name="id" id="id" value="">
   <input type="hidden" name="mode" value="DEL">
</form>
<script type="text/javascript">
function printDiv(print_1) {
    // $('input[type=number]' ).each(function () {
    // var cell = $(this);
    // cell.replaceWith('<span>'  + cell.val() +'</span> ');
    // });
    $('input[type=number]').each(function() {
        var edp_tsd = "edp_tsd";
        var cell = $(this);
        cell.replaceWith("<input type=\"number\" class=\"" + edp_tsd + "\" value=\"" + +cell.val() + "\" />");
    });
    var printContents = document.getElementById(print_1).innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    window.print();

    document.body.innerHTML = originalContents;
}
window.onafterprint = function() {
    window.location.reload(true);
};
$("#form_1").click(function() {
    $("#frmdeclaration").show();
    $("#frmdeclaration2").hide();
});
$(".form_2").click(function() {
    $("#frmdeclaration").hide();
    $("#frmdeclaration2").show();
});
// $(document).ready(function() {
//     function sumregistrationNumber() {
//         var Amount = 0;
//         $(".school_adjust").each(function() {
//             var amt = $(this).val();
//             amt = parseFloat(amt);
//             if (isNaN(amt)) {
//                 amt = 0;
//             }
//             Amount += amt;
//         })
//         $("#school_total_f").val(Amount.toFixed(2));
//     }
//     $(".school_adjust").on('keyup keypress blur change', function(e) {
//         sumregistrationNumber()
//     });

//     function sumregistrationNumberb() {
//         var Amount = 0;
//         $(".school_adjust1").each(function() {
//             var amt = $(this).val();
//             amt = parseFloat(amt);
//             if (isNaN(amt)) {
//                 amt = 0;
//             }
//             Amount += amt;
//         })
//         $("#totalb_g").val(Amount.toFixed(2));
//     }
//     $(".school_adjust1").on('keyup keypress blur change', function(e) {
//         sumregistrationNumberb()
//     });

//     function sumregistrationNumberc() {
//         var Amount = 0;
//         $(".school_adjust2").each(function() {
//             var amt = $(this).val();
//             amt = parseFloat(amt);
//             if (isNaN(amt)) {
//                 amt = 0;
//             }
//             Amount += amt;
//         })
//         $("#total_c").val(Amount.toFixed(2));
//     }
//     $(".school_adjust2").on('keyup keypress blur change', function(e) {
//         sumregistrationNumberc()
//     });
    // function sumregistrationNumberab(){
    // var Amount = 0;
    // $(".total_a").each(function(){
    // var amt = $(this).val();
    // amt = parseFloat(amt);
    // if (isNaN(amt)) {
    // amt = 0;
    // }
    // Amount += amt;
    // })
    // $("#royalty_st").val(Amount.toFixed(2));
    // }
    // $(".total_a").on('keyup keypress blur change', function (e) {
    // sumregistrationNumberab()
    // });			
//     $(".edp_tsdt").keyup(function() {
//         var rate = parseFloat($('#edp_EDP').val()) || 0;
//         var box = parseFloat($('#edp_EDP_st').val()) || 0;
//         var edp_tsdt = (rate * box);
//         var edp_tsdt = edp_tsdt.toFixed(2);
//         $('#edp_EDP_t').val(edp_tsdt);
//         sumregistrationNumber();
//     });


//     $('.edp_tsdq1').keyup(function() {
//         var rate = parseFloat($('#QF1_s').val()) || 0;
//         var box = parseFloat($('#QF1_st').val()) || 0;
//         var edp_tsdq1 = (rate * box);
//         var edp_tsdq1 = edp_tsdq1.toFixed(2);
//         $('#QF1_t').val(edp_tsdq1);
//         sumregistrationNumber();
//     });
//     $('.edp_tsdq2').keyup(function() {
//         var rate = parseFloat($('#QF2_s').val()) || 0;
//         var box = parseFloat($('#QF2_st').val()) || 0;
//         var edp_tsdq2 = (rate * box);
//         var edp_tsdq2 = edp_tsdq2.toFixed(2);
//         $('#QF2_t').val(edp_tsdq2);
//         sumregistrationNumber();
//     });
//     $('.edp_tsdq3').keyup(function() {
//         var rate = parseFloat($('#QF3_s').val()) || 0;
//         var box = parseFloat($('#QF3_st').val()) || 0;
//         var edp_tsdq3 = (rate * box);
//         var edp_tsdq3 = edp_tsdq3.toFixed(2);
//         $('#QF3_t').val(edp_tsdq3);
//         sumregistrationNumber();
//     });
//     $('.waived_school').keyup(function() {
//         var key_operator = parseFloat($('#key_operator').val()) || 0;
//         var key_operator_st = parseFloat($('#key_operator_st').val()) || 0;
//         var waived_school = (key_operator * key_operator_st);
//         var waived_school = waived_school.toFixed(2);
//         $('#key_operator_t').val(waived_school);
//         sumregistrationNumber();
//     });
//     $('.teachers').keyup(function() {
//         var teachers_s = parseFloat($('#teachers_s').val()) || 0;
//         var teachers_st = parseFloat($('#teachers_st').val()) || 0;
//         var teachers = (teachers_s * teachers_st);
//         var teachers = teachers.toFixed(2);
//         $('#teachers_t').val(teachers);
//         sumregistrationNumber();
//     });
//     $('.sliblings').keyup(function() {
//         var sliblings_s = parseFloat($('#sliblings_s').val()) || 0;
//         var sliblings_st = parseFloat($('#sliblings_st').val()) || 0;
//         var sliblings = (sliblings_s * sliblings_st);
//         var sliblings = sliblings.toFixed(2);
//         $('#sliblings_t').val(sliblings);
//         sumregistrationNumber();
//     });
//     $('.siblings').keyup(function() {
//         var siblings_s = parseFloat($('#siblings_s').val()) || 0;
//         var siblings_st = parseFloat($('#siblings_st').val()) || 0;
//         var siblings = (siblings_s * siblings_st);
//         var siblings = siblings.toFixed(2);
//         $('#siblings_t').val(siblings);
//         sumregistrationNumber();
//     });
//     $('.multimedia').keyup(function() {
//         var multimedia_s = parseFloat($('#multimedia_s').val()) || 0;
//         var multimedia_st = parseFloat($('#multimedia_st').val()) || 0;
//         var multimedia = (multimedia_s * multimedia_st);
//         var multimedia = multimedia.toFixed(2);
//         $('#multimedia_t').val(multimedia);
//         sumregistrationNumber();
//     });
//     $('.afternoon').keyup(function() {
//         var afternoon_s = parseFloat($('#afternoon_s').val()) || 0;
//         var afternoon_st = parseFloat($('#afternoon_st').val()) || 0;
//         var afternoon = (afternoon_s * afternoon_st);
//         var afternoon = afternoon.toFixed(2);
//         $('#afternoon_t').val(afternoon);
//         sumregistrationNumber();

//     });

//     $('.dees_founda').keyup(function() {
//         var termly_s = parseFloat($('#termly_s').val()) || 0;
//         var termly_st = parseFloat($('#termly_st').val()) || 0;
//         var dees_founda = (termly_s * termly_st);
//         var dees_founda = dees_founda.toFixed(2);
//         $('#termly_t').val(dees_founda);
//         sumregistrationNumberb();

//     });
//     $('.dees_foundation').keyup(function() {
//         var f_modules_s = parseFloat($('#f_modules_s').val()) || 0;
//         var f_modules_st = parseFloat($('#f_modules_st').val()) || 0;
//         var dees_foundation = (f_modules_s * f_modules_st);
//         var dees_foundation = dees_foundation.toFixed(2);
//         $('#f_modules_t').val(dees_foundation);
//         sumregistrationNumberb();

//     });
//     $('.registration').keyup(function() {
//         var registration_s = parseFloat($('#registration_s').val()) || 0;
//         var registration_st = parseFloat($('#registration_st').val()) || 0;
//         var registration = (registration_s * registration_st);
//         var registration = registration.toFixed(2);
//         $('#registration_t').val(registration);
//         sumregistrationNumberb();

//     });
//     $('.mernories').keyup(function() {
//         var mernories_s = parseFloat($('#mernories_s').val()) || 0;
//         var mernories_st = parseFloat($('#mernories_st').val()) || 0;
//         var mernories = (mernories_s * mernories_st);
//         var mernories = mernories.toFixed(2);
//         $('#mernories_t').val(mernories);
//         sumregistrationNumberb();

//     });
//     $('.dees_bag').keyup(function() {
//         var dees_bag_s = parseFloat($('#dees_bag_s').val()) || 0;
//         var dees_bag_st = parseFloat($('#dees_bag_st').val()) || 0;
//         var dees_bag = (dees_bag_s * dees_bag_st);
//         var dees_bag = dees_bag.toFixed(2);
//         $('#dees_bag_t').val(dees_bag);
//         sumregistrationNumberb();

//     });
//     $('.uniform').keyup(function() {
//         var uniform_s = parseFloat($('#uniform_s').val()) || 0;
//         var uniform_st = parseFloat($('#uniform_st').val()) || 0;
//         var uniform = (uniform_s * uniform_st);
//         var uniform = uniform.toFixed(2);
//         $('#uniform_t').val(uniform);
//         sumregistrationNumberb();

//     });
//     $('.pre_school').keyup(function() {
//         var pre_school_s = parseFloat($('#pre_school_s').val()) || 0;
//         var pre_school_st = parseFloat($('#pre_school_st').val()) || 0;
//         var pre_school = (pre_school_s * pre_school_st);
//         var pre_school = pre_school.toFixed(2);
//         $('#pre_school_t').val(pre_school);
//         sumregistrationNumberc();

//     });
//     $('.memories').keyup(function() {
//         var memories_s = parseFloat($('#memories_s').val()) || 0;
//         var memories_st = parseFloat($('#memories_st').val()) || 0;
//         var memories = (memories_s * memories_st);
//         var memories = memories.toFixed(2);
//         $('#memories_t').val(memories);
//         sumregistrationNumberc();

//     });
//     $('.q_dees').keyup(function() {
//         var q_dees_s = parseFloat($('#q_dees_s').val()) || 0;
//         var q_dees_st = parseFloat($('#q_dees_st').val()) || 0;
//         var q_dees = (q_dees_s * q_dees_st);
//         var q_dees = q_dees.toFixed(2);
//         $('#q_dees_t').val(q_dees);
//         sumregistrationNumberc();

//     });
//     $('.uniform_sc').keyup(function() {
//         var uniform_s1 = parseFloat($('#uniform_s1').val()) || 0;
//         var uniform_st1 = parseFloat($('#uniform_st1').val()) || 0;
//         var uniform_sc = (uniform_s1 * uniform_st1);
//         var uniform_sc = uniform_sc.toFixed(2);
//         $('#uniform_t1').val(uniform_sc);
//         sumregistrationNumberc();

//     });
//     $('.gymwear').keyup(function() {
//         var gymwear_s = parseFloat($('#gymwear_s').val()) || 0;
//         var gymwear_st = parseFloat($('#gymwear_st').val()) || 0;
//         var gymwear = (gymwear_s * gymwear_st);
//         var gymwear = gymwear.toFixed(2);
//         $('#gymwear_t').val(gymwear);
//         sumregistrationNumberc();

//     });

//     $('.software').keyup(function() {
//         var software_s = parseFloat($('#software_s').val()) || 0;
//         var software_st = parseFloat($('#software_st').val()) || 0;
//         var software = (software_s * software_st);
//         var software = software.toFixed(2);
//         $('#software_t').val(software);

//     });
//     $('.softwarefee').keyup(function() {
//         var softwarefee_s = parseFloat($('#softwarefee_s').val()) || 0;
//         var softwarefee_st = parseFloat($('#softwarefee_st').val()) || 0;
//         var softwarefee = (softwarefee_s * softwarefee_st);
//         var softwarefee = softwarefee.toFixed(2);
//         $('#softwarefee_t').val(softwarefee);

//     });
// });
// $(document).on("keyup", function() {
//     var royalty_t = parseFloat($('#royalty_t').val()) || 0;
//     var advertising_t = parseFloat($('#advertising_t').val()) || 0;
//     var software_t = parseFloat($('#software_t').val()) || 0;
//     var softwarefee_t = parseFloat($('#softwarefee_t').val()) || 0;

//     var softwarefee_te = (royalty_t + advertising_t + software_t + softwarefee_t);
//     var softwarefee_te = softwarefee_te.toFixed(2);
//     $('#grand_total').val(softwarefee_te);

// });
// $(document).on("keyup", function() {
//     var school_total_f = parseFloat($('#school_total_f').val()) || 0;
//     var totalb_g = parseFloat($('#totalb_g').val()) || 0;
//     var total_c = parseFloat($('#total_c').val()) || 0;
//     $('#royalty_st').val(school_total_f + totalb_g + total_c);

// });
// $(document).on("change keyup blur", "#royalty_s", function() {
//     var royalty_st = $('#royalty_st').val();
//     var royalty_s = $('#royalty_s').val();
//     var royalty_s = (royalty_s / 100).toFixed(2); //its convert 10 into 0.10
//     var royalty_st = royalty_st * royalty_s; // gives the value for subtract from main value
//     var royalty_st = royalty_st.toFixed(2);
//     $('#royalty_t').val(royalty_st);
// });

// $(document).on("keyup", function() {
//     var school_total_f = parseFloat($('#school_total_f').val()) || 0;
//     $('#advertising_st').val(school_total_f);
// });
// $(document).on("change keyup blur", "#advertising_s", function() {
//     var advertising_st = $('#advertising_st').val();
//     var advertising_s = $('#advertising_s').val();
//     var advertising_s = (advertising_s / 100).toFixed(2); //its convert 10 into 0.10
//     var advertising_st = advertising_st * advertising_s; // gives the value for subtract from main value
//     var advertising_st = advertising_st.toFixed(2);
//     $('#advertising_t').val(advertising_st);
// });

// // form 2
// $(document).on("keyup", function() {
//     var total_intere = parseFloat($('#total_intere').val()) || 0;
//     var total_artt = parseFloat($('#total_artt').val()) || 0;
//     var total_mant = parseFloat($('#total_mant').val()) || 0;
//     var total_iqt = parseFloat($('#total_iqt').val()) || 0;

//     $('#total_iqt2').val(total_intere + total_artt + total_mant + total_iqt);

// });

// function sumregistrationNumberic() {
//     var Amount = 0;
//     $(".form2school_adjust1").each(function() {
//         var amt = $(this).val();
//         amt = parseFloat(amt);
//         if (isNaN(amt)) {
//             amt = 0;
//         }
//         Amount += amt;
//     })
//     $("#total_intere").val(Amount.toFixed(2));
// }
// // $(".form2school_adjust1").on('keyup keypress blur change', function(e) {
// //     sumregistrationNumberic()
// // });

// function sumregistrationNumberinc() {
//     var Amount = 0;
//     $(".form2school_adjust2").each(function() {
//         var amt = $(this).val();
//         amt = parseFloat(amt);
//         if (isNaN(amt)) {
//             amt = 0;
//         }
//         Amount += amt;
//     })
//     $("#total_artt").val(Amount.toFixed(2));
// }
// // $(".form2school_adjust2").on('keyup keypress blur change', function(e) {
// //     sumregistrationNumberinc()
// // });

// function sumregistrationNumbermnc() {
//     var Amount = 0;
//     $(".form2school_adjust3").each(function() {
//         var amt = $(this).val();
//         amt = parseFloat(amt);
//         if (isNaN(amt)) {
//             amt = 0;
//         }
//         Amount += amt;
//     })
//     $("#total_mant").val(Amount.toFixed(2));
// }
// // $(".form2school_adjust3").on('keyup keypress blur change', function(e) {
// //     sumregistrationNumbermnc()
// // });

// function sumregistrationNumbermq() {
//     var Amount = 0;
//     $(".form2school_adjust4").each(function() {
//         var amt = $(this).val();
//         amt = parseFloat(amt);
//         if (isNaN(amt)) {
//             amt = 0;
//         }
//         Amount += amt;
//     })
//     $("#total_iqt").val(Amount.toFixed(2));
// }
// // $(".form2school_adjust4").on('keyup keypress blur change', function(e) {
// //     sumregistrationNumbermq()
// // });

// function sumregistrationNumbermwq() {
//     var Amount = 0;
//     $(".linkschool_adjust5").each(function() {
//         var amt = $(this).val();
//         amt = parseFloat(amt);
//         if (isNaN(amt)) {
//             amt = 0;
//         }
//         Amount += amt;
//     })
//     $("#total_usaget").val(Amount.toFixed(2));
// }
// $(".linkschool_adjust5").on('keyup keypress blur change', function(e) {
//     sumregistrationNumbermwq()
// });

// function sumregistrationNumbermwqr() {
//     var Amount = 0;
//     $(".school_adjust6").each(function() {
//         var amt = $(this).val();
//         amt = parseFloat(amt);
//         if (isNaN(amt)) {
//             amt = 0;
//         }
//         Amount += amt;
//     })
//     $("#total_foun_school").val(Amount.toFixed(2));
// }
// $(".school_adjust6").on('keyup keypress blur change', function(e) {
//     sumregistrationNumbermwqr()
// });
// $('.foun_school').keyup(function() {
//     var foun_school_s = parseFloat($('#foun_school_s').val()) || 0;
//     var foun_school_st = parseFloat($('#foun_school_st').val()) || 0;
//     var foun_school = (foun_school_s * foun_school_st);
//     var foun_school = foun_school.toFixed(2);
//     $('#foun_school_t').val(foun_school);
//     sumregistrationNumbermwqr();

// });
// $('.form2edp_tsdq1').keyup(function() {
//     var rate = parseFloat($('#form2QF1_s').val()) || 0;
//     var box = parseFloat($('#form2QF1_st').val()) || 0;
//     var form2edp_tsdq1 = (rate * box);
//     var form2edp_tsdq1 = form2edp_tsdq1.toFixed(2);
//     $('#form2QF1_t').val(form2edp_tsdq1);
//     sumregistrationNumberic();
// });
// $('.form2edp_tsdq2').keyup(function() {
//     var rate = parseFloat($('#form2QF2_s').val()) || 0;
//     var box = parseFloat($('#form2QF2_st').val()) || 0;
//     var form2edp_tsdq2 = (rate * box);
//     var form2edp_tsdq2 = form2edp_tsdq2.toFixed(2);
//     $('#form2QF2_t').val(form2edp_tsdq2);
//     sumregistrationNumberic();
// });
// $('.form2edp_tsdq3').keyup(function() {
//     var rate = parseFloat($('#form2QF3_s').val()) || 0;
//     var box = parseFloat($('#form2QF3_st').val()) || 0;
//     var form2edp_tsdq3 = (rate * box);
//     var form2edp_tsdq3 = form2edp_tsdq3.toFixed(2);
//     $('#form2QF3_t').val(rate * box);
//     sumregistrationNumberic();
// });

// $('.form2edp_tsdq1a').keyup(function() {
//     var rate = parseFloat($('#form2QF1_sa').val()) || 0;
//     var box = parseFloat($('#form2QF1_sta').val()) || 0;
//     var form2edp_tsdq1a = (rate * box);
//     var form2edp_tsdq1a = form2edp_tsdq1a.toFixed(2);
//     $('#form2QF1_ta').val(form2edp_tsdq1a);
//     sumregistrationNumberinc();
// });
// $('.form2edp_tsdq2a').keyup(function() {
//     var rate = parseFloat($('#form2QF2_sa').val()) || 0;
//     var box = parseFloat($('#form2QF2_sta').val()) || 0;
//     var form2edp_tsdq2a = (rate * box);
//     var form2edp_tsdq2a = form2edp_tsdq2a.toFixed(2);
//     $('#form2QF2_ta').val(form2edp_tsdq2a);
//     sumregistrationNumberinc();
// });
// $('.form2edp_tsdq3a').keyup(function() {
//     var rate = parseFloat($('#form2QF3_sa').val()) || 0;
//     var box = parseFloat($('#form2QF3_sta').val()) || 0;
//     var form2edp_tsdq3a = (rate * box);
//     var form2edp_tsdq3a = form2edp_tsdq3a.toFixed(2);
//     $('#form2QF3_ta').val(form2edp_tsdq3a);
//     sumregistrationNumberinc();
// });
// $('.form2edp_tsdq1m').keyup(function() {
//     var rate = parseFloat($('#form2QF1_sm').val()) || 0;
//     var box = parseFloat($('#form2QF1_stm').val()) || 0;
//     var form2edp_tsdq1m = (rate * box);
//     var form2edp_tsdq1m = form2edp_tsdq1m.toFixed(2);
//     $('#form2QF1_tm').val(form2edp_tsdq1m);
//     sumregistrationNumbermnc();
// });
// $('.form2edp_tsdq2m').keyup(function() {
//     var rate = parseFloat($('#form2QF2_sm').val()) || 0;
//     var box = parseFloat($('#form2QF2_stm').val()) || 0;
//     var form2edp_tsdq2m = (rate * box);
//     var form2edp_tsdq2m = form2edp_tsdq2m.toFixed(2);
//     $('#form2QF2_tm').val(form2edp_tsdq2m);
//     sumregistrationNumbermnc();
// });
// $('.form2edp_tsdq3m').keyup(function() {
//     var rate = parseFloat($('#form2QF3_sm').val()) || 0;
//     var box = parseFloat($('#form2QF3_stm').val()) || 0;
//     var form2edp_tsdq3m = (rate * box);
//     var form2edp_tsdq3m = form2edp_tsdq3m.toFixed(2);
//     $('#form2QF3_tm').val(form2edp_tsdq3m);
//     sumregistrationNumbermnc();
// });

// $('.form2edp_tsdq1q').keyup(function() {
//     var rate = parseFloat($('#form2QF1_sq').val()) || 0;
//     var box = parseFloat($('#form2QF1_stq').val()) || 0;
//     var form2edp_tsdq1q = (rate * box);
//     var form2edp_tsdq1q = form2edp_tsdq1q.toFixed(2);
//     $('#form2QF1_tq').val(form2edp_tsdq1q);
//     sumregistrationNumbermq();
// });
// $('.form2edp_tsdq2q').keyup(function() {
//     var rate = parseFloat($('#form2QF2_sq').val()) || 0;
//     var box = parseFloat($('#form2QF2_stq').val()) || 0;
//     var form2edp_tsdq2q = (rate * box);
//     var form2edp_tsdq2q = form2edp_tsdq2q.toFixed(2);
//     $('#form2QF2_tq').val(form2edp_tsdq2q);
//     sumregistrationNumbermq();
// });
// $('.form2edp_tsdq3q').keyup(function() {
//     var rate = parseFloat($('#form2QF3_sq').val()) || 0;
//     var box = parseFloat($('#form2QF3_stq').val()) || 0;
//     var form2edp_tsdq3q = (rate * box);
//     var form2edp_tsdq3q = form2edp_tsdq3q.toFixed(2);
//     $('#form2QF3_tq').val(form2edp_tsdq3q);
//     sumregistrationNumbermq();
// });

// $('.link').keyup(function() {
//     var rate = parseFloat($('#link_s').val()) || 0;
//     var box = parseFloat($('#link_st').val()) || 0;
//     var link = (rate * box);
//     var link = link.toFixed(2);
//     $('#link_t').val(link);
//     sumregistrationNumbermwq();
// });
// // $('.usage').keyup(function(){
// // var rate = parseFloat($('#usage_s').val()) || 0;
// // var box = parseFloat($('#usage_st').val()) || 0;

// // $('#usage_t').val(rate * box); 
// // sumregistrationNumbermwq();
// // });
// $(document).on("change keyup blur", "#royalty_si", function() {
//     var royalty_sti = $('#royalty_sti').val();
//     var royalty_si = $('#royalty_si').val();
//     var royalty_si = (royalty_si / 100).toFixed(2); //its convert 10 into 0.10
//     var royalty_sti = royalty_sti * royalty_si; // gives the value for subtract from main value
//     var royalty_sti = royalty_sti.toFixed(2);
//     $('#royalty_ti').val(royalty_sti);
// });
// $(document).on("change keyup blur", "#royalty_sii", function() {
//     var royalty_stii = $('#royalty_stii').val();
//     var royalty_sii = $('#royalty_sii').val();
//     var royalty_sii = (royalty_sii / 100).toFixed(2); //its convert 10 into 0.10
//     var royalty_stii = royalty_stii * royalty_sii; // gives the value for subtract from main value
//     var royalty_stii = royalty_stii.toFixed(2);
//     $('#royalty_tii').val(royalty_stii);
// });
// $(document).on("change keyup blur", "#advertising_si", function() {
//     var advertising_sti = $('#advertising_sti').val();
//     var advertising_si = $('#advertising_si').val();
//     var advertising_si = (advertising_si / 100).toFixed(2); //its convert 10 into 0.10
//     var advertising_sti = advertising_sti * advertising_si; // gives the value for subtract from main value
//     var advertising_sti = advertising_sti.toFixed(2);
//     $('#advertising_ti').val(advertising_sti);
// });
// $(document).on("keyup", function() {
//     var royalty_ti = parseFloat($('#royalty_ti').val()) || 0;
//     var royalty_tii = parseFloat($('#royalty_tii').val()) || 0;
//     var advertising_ti = parseFloat($('#advertising_ti').val()) || 0;

//     $('#grand_totali').val(royalty_ti + royalty_tii + advertising_ti);

// });
// $(document).on("keyup", function() {
//     var total_intere = parseFloat($('#total_intere').val()) || 0;
//     var total_artt = parseFloat($('#total_artt').val()) || 0;
//     var total_usaget = parseFloat($('#total_usaget').val()) || 0;

//     $('#royalty_sti').val(total_intere + total_artt + total_usaget);
// });
// $(document).on("keyup", function() {
//     var total_mant = parseFloat($('#total_mant').val()) || 0;
//     var total_iqt = parseFloat($('#total_iqt').val()) || 0;
//     var total_foun_school = parseFloat($('#total_foun_school').val()) || 0;
//     $('#royalty_stii').val(total_mant + total_iqt + total_foun_school);
// });
// $(document).on("keyup", function() {
//     var total_iqt2 = parseFloat($('#total_iqt2').val()) || 0;
//     $('#advertising_sti').val(total_iqt2);
// });
    function myFunction() {
        var elmnt = document.getElementById("frmdeclaration2");
        elmnt.scrollIntoView();
        return false;
    }
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
</style>