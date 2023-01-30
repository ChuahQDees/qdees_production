<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");

foreach ($_POST as $key => $value) {
   $$key = mysqli_real_escape_string($connection, $value); //$centre_code, $year, $month
}

if ($method == "print") {
   include_once("../uikit1.php");
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

function convertDateFormat($date)
{
   $request_Month = substr($date, 4, 2);
   $request_Year = substr($date, 0, -2);
   return $request_Year . '-' . $request_Month . '-' . cal_days_in_month(CAL_GREGORIAN, $request_Month, $request_Year);
}

if ($centre_code != "") {
?>

   <style type="text/css">
      @media print {
         #note {
            display: none;
         }
      }
   </style>
   <div class="uk-width-1-1 myheader text-center mt-5">
      <h2 class="uk-text-center myheader-text-color myheader-text-style">MASTER SUBJECT ENROLLMENT REPORT</h2>
   </div>
   <div class="nice-form" style="overflow: scroll;">
      <div class="uk-grid">
         <div class="uk-width-medium-5-10" style="width:50%">
            <table class="uk-table">
               <tr>
                  <td class="uk-text-bold">Centre Name</td>
                  <td>
                     <?php echo getCentreName($centre_code) ?>
                  </td>
               </tr>
               <tr>
                  <td class="uk-text-bold">Prepare By</td>
                  <td><?php echo $_SESSION["UserName"] ?></td>
               </tr>
               <tr id="note1">
                  <td colspan="2" class="uk-text-bold">NOTE: To be submitted termly with the order form</td>
               </tr>
            </table>
         </div>
         <div class="uk-width-medium-5-10" style="width:50%">
            <table class="uk-table">
               <tr>
                  <td class="uk-text-bold">Academic Year</td>
				   <td><?php echo $_SESSION['Year']; ?></td>
               </tr>
               <tr>
                  <td class="uk-text-bold">School Term</td>
                  <td>
                     <?php
                      $sql = "SELECT * from codes where module='SCHOOL_TERM' ORDER BY category ";
                
                      $centre_result = mysqli_query($connection, $sql);
                      $str = "";
                      while ($centre_row = mysqli_fetch_assoc($centre_result)) {
                        // echo $centre_row['category'] . "/" . $centre_row['year'] . "<br>";
                        $str .= $centre_row['category'] . ', ';
                      }
                      echo rtrim($str, ", ");
                     //}
                     ?>

                  </td>
               </tr>
               <tr>
                  <td class="uk-text-bold">Date of submission</td>
                  <td><?php echo date("Y-m-d H:i:s") ?></td>
               </tr>
               <!-- <tr id="note1" style="display: none;">
                  <td colspan="2" class="uk-text-bold">NOTE: To be submitted termly with the order form</td>
               </tr> -->
            </table>
         </div>
      </div>
      <table class="uk-table rtable">
         <tr class="uk-text-bold">
         <td style="width: 3%;" rowspan="2"><center>No</center></td>
            <td style="width: 15%;" rowspan="2"><center>Centre Name</center></td>
            <td  rowspan="2"><center>OC</center></td>
            <?php //if($course_subject == "EDP" || $course_subject == ""){ ?>
            <td colspan="5"><center>By Level</center></td>
            <td rowspan="2"><center>Day Care (afternoon prog.)</center></td>
            <td colspan="3"><center>FlipteC @ Q Mandarin (foundation mandarin)</center></td>
            <td colspan="3"><center>BIP ENG (international english)</center></td>
            <td rowspan="2"><center>Total (A)</center></td>
            <td rowspan="2"><center>BIP ENG % (B)</center></td>
            <td colspan="3"><center>BIP MAND (enhancced foun. Mandarin)</center></td>
            <td rowspan="2"><center>Total (C)</center></td>
            <td rowspan="2"><center>BIP ENG % (D)</center></td>
            <td colspan="3"><center>BIP ART (international art)</center></td>
            <td rowspan="2"><center>Total (E)</center></td>
            <td rowspan="2"><center>BIP ART % (F)</center></td>
            <td colspan="3"><center>MATH (IQ Math)</center></td>
            <td rowspan="2"><center>Total (G)</center></td>
            <td rowspan="2"><center>MATH % (H)</center></td>
            <td rowspan="2"><center>Grand Total (total from BIP only)</center></td>
            <td rowspan="2"><center>Total Beamind % (I)</center></td>
            <td rowspan="2"><center>Total Stud. No. QF1-QF3 (J)</center></td>
            <td rowspan="2"><center>Location (State)</center></td>
             <?php //} ?>
         </tr>
         <tr class="uk-text-bold">
           
            <?php //if($course_subject == "EDP" || $course_subject == ""){ ?>
            <td><center>EDP</center></td>
            <?php //} if($course_subject == "QF1" || $course_subject == ""){ ?>
			<td><center>QF1</center></td>
         <?php //} if($course_subject == "QF2" || $course_subject == ""){ ?>
			<td><center>QF2</center></td>
         <?php //} if($course_subject == "QF3" || $course_subject == ""){ ?>
			<td><center>QF3</center></td>
         <?php //} if($course_subject == ""){ ?>
			<td><center>TOTAL</center></td>
         <td><center>QF2</center></td><!--FlipteC @ Q Mandarin (foundation mandarin) -->
			<td><center>QF3</center></td><!--FlipteC @ Q Mandarin (foundation mandarin) -->
			<td><center>TOTAL</center></td><!--FlipteC @ Q Mandarin (foundation mandarin) -->
         <td><center>QF1</center></td><!--BIP ENG (international english) -->
			<td><center>QF2</center></td><!--BIP ENG (international english) -->
			<td><center>QF3</center></td><!--BIP ENG (international english) -->
         <td><center>QF1</center></td><!--BIP MAND (enhancced foun. Mandarin) -->
			<td><center>QF2</center></td><!--BIP MAND (enhancced foun. Mandarin) -->
			<td><center>QF3</center></td><!--BIP MAND (enhancced foun. Mandarin) --> 
         <td><center>QF1</center></td><!--BIP ART (international art) -->
			<td><center>QF2</center></td><!--BIP ART (international art) -->
			<td><center>QF3</center></td><!--BIP ART (international art) -->
         <td><center>QF1</center></td><!--MATH (IQ Math) -->
			<td><center>QF2</center></td><!--MATH (IQ Math) -->
			<td><center>QF3</center></td><!--MATH (IQ Math) -->
         <?php //} ?>
         </tr>
         <?php
        
          $year=$_SESSION['Year'];

          $sql="SELECT cs.* from centre_status cs inner join centre c on cs.id=c.centre_status_id where 1=1 ";
            
            if($centre_status!="ALL"){
               $sql.=" and name='$centre_status' ";
            }
            $sql.=" group by cs.id order by id";
           
            $cresult1=mysqli_query($connection, $sql);
            while ($csrow=mysqli_fetch_assoc($cresult1)) {
               $id = $csrow["id"];
               
           $category_name = '';
           $sql="SELECT * from centre c inner join centre_status cs on cs.id=c.centre_status_id where cs.id=$id";
            if($centre_code!="ALL"){
               $sql.=" and c.centre_code='$centre_code' ";
            }
            if($centre_status!="ALL"){
               $sql.=" and cs.name='$centre_status' ";
            }
            $sql.=" order by cs.id";
            $cresult=mysqli_query($connection, $sql);
            //echo $sql;
        //$num_row=mysqli_num_rows($cresult);
         $EDP = 0;
         $QF1 = 0;
         $QF2 = 0;
         $QF3 = 0;
        $Subtotal_EDP = 0;
        $Subtotal_QF1 = 0;
        $Subtotal_QF2 = 0;
        $Subtotal_QF3 = 0;
        $Subtotal_Total = 0;
        $afternoon_programme = 0;
        $Subtotal_Total_afternoon_programme = 0;
        $Subtotal_QF2_foundation_mandarin = 0;
        $Subtotal_QF3_foundation_mandarin = 0;
        $foundation_mandarin = 0;
        $Subtotal_Total_foundation_mandarin = 0;
        $Subtotal_QF1_foundation_int_english = 0;
        $Subtotal_QF2_foundation_int_english = 0;
        $Subtotal_QF3_foundation_int_english = 0;
        $foundation_int_english = 0;
        $Subtotal_Total_foundation_int_english = 0;
        $Subtotal_QF1_foundation_int_mandarin = 0;
        $Subtotal_QF2_foundation_int_mandarin = 0;
        $Subtotal_QF3_foundation_int_mandarin = 0;
        $foundation_int_mandarin = 0;
        $Subtotal_Total_foundation_int_mandarin = 0;
        $Subtotal_QF1_foundation_int_art = 0;
        $Subtotal_QF2_foundation_int_art = 0;
        $Subtotal_QF3_foundation_int_art = 0;
        $foundation_int_art = 0;
        $Subtotal_Total_foundation_int_art = 0;
        $Subtotal_QF1_foundation_iq_math = 0;
         $Subtotal_QF2_foundation_iq_math = 0;
         $Subtotal_QF3_foundation_iq_math = 0;
         $foundation_iq_math = 0;
         $Subtotal_Total_foundation_iq_math = 0;
         $grand_total_from_BIP_only = 0;
         $Total_Stud_No_QF1_QF3 = 0;
        $i=1;
            while ($crow=mysqli_fetch_assoc($cresult)) {
         ?>
			<tr class="founda">
        <td><?php echo $i++; ?></td>
        <td><?php echo $crow["company_name"] ?></td>
        <td><?php echo $crow["pic"] ?></td>
            <?php if($course_subject == "EDP" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.extend_year='$year'  and ps.student_entry_level != '' and s.student_status = 'A' ";
            //if($centre_code!="ALL"){
              $centreCode = $crow["centre_code"];
               $sql.=" and s.centre_code='$centreCode' ";
            //}
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end >= '$to_date')) and s.deleted='0' and ps.student_entry_level ='EDP'  group by ps.student_entry_level, s.id) ab";
            //echo $sql;
            $result=mysqli_query($connection, $sql);
            //echo $sql;
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span><?php 
                        $EDP = $row["level_count"]; 
                        $Subtotal_EDP += $row["level_count"];  
                        echo (empty($row["level_count"]) ? 0 : $row["level_count"]); 
                        if($crow["name"]=="COMPANY OWNED"){
                           $COMPANY_OWNED_EDP += $row["level_count"];
                        }else if($crow["name"]=="JOINT VENTURE"){
                           $JOINT_VENTURE_EDP += $row["level_count"];
                        }else{
                           $Franchisees_EDP += $row["level_count"];
                        }
                        ?></span>
                           
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == "QF1" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.extend_year='$year'  and ps.student_entry_level != '' and s.student_status = 'A' ";
              //if($centre_code!="ALL"){
                $centreCode = $crow["centre_code"];
                $sql.=" and s.centre_code='$centreCode' ";
             //}
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end >= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF1'  group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span><?php 
                        $QF1 = $row["level_count"]; 
                        $Subtotal_QF1 += $row["level_count"]; 
                        echo (empty($row["level_count"]) ? "0" : $row["level_count"]); 
                        if($crow["name"]=="COMPANY OWNED"){
                           $COMPANY_OWNED_QF1 += $row["level_count"];
                        }else if($crow["name"]=="JOINT VENTURE"){
                           $JOINT_VENTURE_QF1 += $row["level_count"];
                        }else{
                           $Franchisees_QF1 += $row["level_count"];
                        }
                        ?></span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == "QF2" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.extend_year='$year'  and ps.student_entry_level != '' and s.student_status = 'A' ";
              //if($centre_code!="ALL"){
                $centreCode = $crow["centre_code"];
                $sql.=" and s.centre_code='$centreCode' ";
             //}
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end >= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF2'  group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span><?php 
                        $QF2 = $row["level_count"]; 
                        $Subtotal_QF2 += $row["level_count"]; 
                        echo (empty($row["level_count"]) ? "0" : $row["level_count"]); 
                        if($crow["name"]=="COMPANY OWNED"){
                           $COMPANY_OWNED_QF2 += $row["level_count"];
                        }else if($crow["name"]=="JOINT VENTURE"){
                           $JOINT_VENTURE_QF2 += $row["level_count"];
                        }else{
                           $Franchisees_QF2 += $row["level_count"];
                        }
                        ?></span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == "QF3" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.extend_year='$year'  and ps.student_entry_level != '' and s.student_status = 'A' ";
              //if($centre_code!="ALL"){
                $centreCode = $crow["centre_code"];
                $sql.=" and s.centre_code='$centreCode' ";
             //}
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end >= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF3'  group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span><?php 
                        $QF3 = $row["level_count"]; 
                        $Subtotal_QF3 += $row["level_count"]; 
                        echo (empty($row["level_count"]) ? "0" : $row["level_count"]); 
                        if($crow["name"]=="COMPANY OWNED"){
                           $COMPANY_OWNED_QF3 += $row["level_count"];
                        }else if($crow["name"]=="JOINT VENTURE"){
                           $JOINT_VENTURE_QF3 += $row["level_count"];
                        }else{
                           $Franchisees_QF3 += $row["level_count"];
                        }
                        ?></span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.extend_year='$year'  and ps.student_entry_level != '' and s.student_status = 'A' ";
              //if($centre_code!="ALL"){
                $centreCode = $crow["centre_code"];
                $sql.=" and s.centre_code='$centreCode' ";
             //}
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end >= '$to_date')) and s.deleted='0'  group by ps.student_entry_level, s.id) ab";
            //echo $sql;
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span><?php 
                        $Subtotal_Total += $row["level_count"]; 
                        echo (empty($row["level_count"]) ? "0" : $row["level_count"]); 
                        $Total_Stud_No_QF1_QF3 = $QF1+$QF2+$QF3;
                        //echo $crow["name"];
                        if($crow["name"]=="COMPANY OWNED"){
                           $COMPANY_OWNED_Total += $row["level_count"];
                        }else if($crow["name"]=="JOINT VENTURE"){
                           $JOINT_VENTURE_Total += $row["level_count"];
                        }else{
                           $Franchisees_Total += $row["level_count"];
                        }
                        ?></span>
                        <?php
                     }
            ?>
            </td>
            <?php } ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.extend_year='$year'  and ps.student_entry_level != '' and s.student_status = 'A' ";
              //if($centre_code!="ALL"){
                $centreCode = $crow["centre_code"];
                $sql.=" and s.centre_code='$centreCode' ";
             //}
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end >= '$to_date')) and fl.afternoon_programme = 1 and s.deleted='0'  group by ps.student_entry_level, s.id) ab";
            //echo $sql; 
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span><?php 
                        $afternoon_programme = $row["level_count"]; 
                        $Subtotal_Total_afternoon_programme += $row["level_count"]; 
                        echo (empty($row["level_count"]) ? "0" : $row["level_count"]); 
                        if($crow["name"]=="COMPANY OWNED"){
                           $COMPANY_OWNED_Total_afternoon_programme += $row["level_count"];
                        }else if($crow["name"]=="JOINT VENTURE"){
                           $JOINT_VENTURE_Total_afternoon_programme += $row["level_count"];
                        }else{
                           $Franchisees_Total_afternoon_programme += $row["level_count"];
                        }
                        ?></span>
                        
                        <?php
                     }
            ?>
            </td>
            <!--start FlipteC @ Q Mandarin (foundation mandarin)-->
            <?php  if($course_subject == "QF2" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.extend_year='$year'  and ps.student_entry_level != '' and s.student_status = 'A' ";
              //if($centre_code!="ALL"){
                $centreCode = $crow["centre_code"];
                $sql.=" and s.centre_code='$centreCode' ";
             //}
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end >= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF2' and fl.foundation_mandarin=1  group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span><?php 
                        $Subtotal_QF2_foundation_mandarin += $row["level_count"]; 
                        echo (empty($row["level_count"]) ? "0" : $row["level_count"]); 
                        if($crow["name"]=="COMPANY OWNED"){
                           $COMPANY_OWNED_QF2_foundation_mandarin += $row["level_count"];
                        }else if($crow["name"]=="JOINT VENTURE"){
                           $JOINT_VENTURE_QF2_foundation_mandarin += $row["level_count"];
                        }else{
                           $Franchisees_QF2_foundation_mandarin += $row["level_count"];
                        }
                        ?></span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == "QF3" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.extend_year='$year'  and ps.student_entry_level != '' and s.student_status = 'A' ";
              //if($centre_code!="ALL"){
                $centreCode = $crow["centre_code"];
                $sql.=" and s.centre_code='$centreCode' ";
             //}
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end >= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF3' and fl.foundation_mandarin=1  group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span><?php 
                        $Subtotal_QF3_foundation_mandarin += $row["level_count"]; 
                        echo (empty($row["level_count"]) ? "0" : $row["level_count"]); 
                        if($crow["name"]=="COMPANY OWNED"){
                           $COMPANY_OWNED_QF3_foundation_mandarin += $row["level_count"];
                        }else if($crow["name"]=="JOINT VENTURE"){
                           $JOINT_VENTURE_QF3_foundation_mandarin += $row["level_count"];
                        }else{
                           $Franchisees_QF3_foundation_mandarin += $row["level_count"];
                        }
                        ?></span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.extend_year='$year'  and ps.student_entry_level != '' and s.student_status = 'A' ";
              //if($centre_code!="ALL"){
                $centreCode = $crow["centre_code"];
                $sql.=" and s.centre_code='$centreCode' ";
             //}
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end >= '$to_date')) and s.deleted='0' and fl.foundation_mandarin=1 and (ps.student_entry_level = 'QF2' or ps.student_entry_level = 'QF3') group by ps.student_entry_level, s.id) ab";
            //echo $sql;
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span><?php 
                        $Subtotal_Total_foundation_mandarin += $row["level_count"]; 
                        echo (empty($row["level_count"]) ? "0" : $row["level_count"]); 
                        if($crow["name"]=="COMPANY OWNED"){
                           $COMPANY_OWNED_Total_foundation_mandarin += $row["level_count"];
                        }else if($crow["name"]=="JOINT VENTURE"){
                           $JOINT_VENTURE_Total_foundation_mandarin += $row["level_count"];
                        }else{
                           $Franchisees_Total_foundation_mandarin += $row["level_count"];
                        }
                        ?></span>
                        <?php
                     }
            ?>
            </td>
            <?php } ?>
             <!--end FlipteC @ Q Mandarin (foundation mandarin)-->
             <!--start BIP ENG (international english)-->
            <?php  if($course_subject == "QF1" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.extend_year='$year'  and ps.student_entry_level != '' and s.student_status = 'A' ";
              //if($centre_code!="ALL"){
                $centreCode = $crow["centre_code"];
                $sql.=" and s.centre_code='$centreCode' ";
             //}
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end >= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF1' and fl.foundation_int_english=1  group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span><?php 
                        $Subtotal_QF1_foundation_int_english += $row["level_count"]; 
                        echo (empty($row["level_count"]) ? "0" : $row["level_count"]); 
                        if($crow["name"]=="COMPANY OWNED"){
                           $COMPANY_OWNED_QF1_foundation_int_english += $row["level_count"];
                        }else if($crow["name"]=="JOINT VENTURE"){
                           $JOINT_VENTURE_QF1_foundation_int_english += $row["level_count"];
                        }else{
                           $Franchisees_QF1_foundation_int_english += $row["level_count"];
                        }
                        ?></span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == "QF2" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.extend_year='$year'  and ps.student_entry_level != '' and s.student_status = 'A' ";
              //if($centre_code!="ALL"){
                $centreCode = $crow["centre_code"];
                $sql.=" and s.centre_code='$centreCode' ";
             //}
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end >= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF2' and fl.foundation_int_english=1  group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span><?php 
                        $Subtotal_QF2_foundation_int_english += $row["level_count"]; 
                        echo (empty($row["level_count"]) ? "0" : $row["level_count"]); 
                        if($crow["name"]=="COMPANY OWNED"){
                           $COMPANY_OWNED_QF2_foundation_int_english += $row["level_count"];
                        }else if($crow["name"]=="JOINT VENTURE"){
                           $JOINT_VENTURE_QF2_foundation_int_english += $row["level_count"];
                        }else{
                           $Franchisees_QF2_foundation_int_english += $row["level_count"];
                        }
                        ?></span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == "QF3" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.extend_year='$year'  and ps.student_entry_level != '' and s.student_status = 'A' ";
              //if($centre_code!="ALL"){
                $centreCode = $crow["centre_code"];
                $sql.=" and s.centre_code='$centreCode' ";
             //}
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end >= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF3' and fl.foundation_int_english=1  group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span><?php 
                        $Subtotal_QF3_foundation_int_english += $row["level_count"]; 
                        echo (empty($row["level_count"]) ? "0" : $row["level_count"]); 
                        if($crow["name"]=="COMPANY OWNED"){
                           $COMPANY_OWNED_QF3_foundation_int_english += $row["level_count"];
                        }else if($crow["name"]=="JOINT VENTURE"){
                           $JOINT_VENTURE_QF3_foundation_int_english += $row["level_count"];
                        }else{
                           $Franchisees_QF3_foundation_int_english += $row["level_count"];
                        }
                        ?></span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.extend_year='$year'  and ps.student_entry_level != '' and s.student_status = 'A' ";
              //if($centre_code!="ALL"){
                $centreCode = $crow["centre_code"];
                $sql.=" and s.centre_code='$centreCode' ";
             //}
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end >= '$to_date')) and s.deleted='0' and fl.foundation_int_english=1 and (ps.student_entry_level = 'QF1' or ps.student_entry_level = 'QF2' or ps.student_entry_level = 'QF3') group by ps.student_entry_level, s.id) ab";
            //echo $sql;
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span><?php 
                        $foundation_int_english = $row["level_count"];
                        $Subtotal_Total_foundation_int_english += $row["level_count"]; 
                        echo (empty($row["level_count"]) ? "0" : $row["level_count"]); 
                        if($crow["name"]=="COMPANY OWNED"){
                           $COMPANY_OWNED_Total_foundation_int_english += $row["level_count"];
                        }else if($crow["name"]=="JOINT VENTURE"){
                           $JOINT_VENTURE_Total_foundation_int_english += $row["level_count"];
                        }else{
                           $Franchisees_Total_foundation_int_english += $row["level_count"];
                        }
                        ?></span>
                        <?php
                     }
            ?>
            </td>
            <?php } ?>
             <!--start BIP ENG % (B)-->
             <?php if($course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
               echo ($Total_Stud_No_QF1_QF3>0 ? round(($foundation_int_english/$Total_Stud_No_QF1_QF3)*100, 2).'%' : 00);
            ?>
            </td>
            <?php } ?>
             <!--end BIP ENG % (B)-->
            <!--end BIP ENG (international english)-->
               <!--start BIP MAND (enhancced foun. Mandarin)-->
            <?php  if($course_subject == "QF1" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.extend_year='$year'  and ps.student_entry_level != '' and s.student_status = 'A' ";
              //if($centre_code!="ALL"){
                $centreCode = $crow["centre_code"];
                $sql.=" and s.centre_code='$centreCode' ";
             //}
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end >= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF1' and fl.foundation_int_mandarin=1  group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span><?php 
                        $Subtotal_QF1_foundation_int_mandarin += $row["level_count"]; 
                        echo (empty($row["level_count"]) ? "0" : $row["level_count"]); 
                        if($crow["name"]=="COMPANY OWNED"){
                           $COMPANY_OWNED_QF1_foundation_int_mandarin += $row["level_count"];
                        }else if($crow["name"]=="JOINT VENTURE"){
                           $JOINT_VENTURE_QF1_foundation_int_mandarin += $row["level_count"];
                        }else{
                           $Franchisees_QF1_foundation_int_mandarin += $row["level_count"];
                        }
                        ?></span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == "QF2" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.extend_year='$year'  and ps.student_entry_level != '' and s.student_status = 'A' ";
              //if($centre_code!="ALL"){
                $centreCode = $crow["centre_code"];
                $sql.=" and s.centre_code='$centreCode' ";
             //}
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end >= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF2' and fl.foundation_int_mandarin=1  group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span><?php 
                        $Subtotal_QF2_foundation_int_mandarin += $row["level_count"]; 
                        echo (empty($row["level_count"]) ? "0" : $row["level_count"]); 
                        if($crow["name"]=="COMPANY OWNED"){
                           $COMPANY_OWNED_QF2_foundation_int_mandarin += $row["level_count"];
                        }else if($crow["name"]=="JOINT VENTURE"){
                           $JOINT_VENTURE_QF2_foundation_int_mandarin += $row["level_count"];
                        }else{
                           $Franchisees_QF2_foundation_int_mandarin += $row["level_count"];
                        }
                        ?></span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == "QF3" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.extend_year='$year'  and ps.student_entry_level != '' and s.student_status = 'A' ";
              //if($centre_code!="ALL"){
                $centreCode = $crow["centre_code"];
                $sql.=" and s.centre_code='$centreCode' ";
             //}
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end >= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF3' and fl.foundation_int_mandarin=1  group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span><?php 
                        $Subtotal_QF3_foundation_int_mandarin += $row["level_count"]; 
                        echo (empty($row["level_count"]) ? "0" : $row["level_count"]); 
                        if($crow["name"]=="COMPANY OWNED"){
                           $COMPANY_OWNED_QF3_foundation_int_mandarin += $row["level_count"];
                        }else if($crow["name"]=="JOINT VENTURE"){
                           $JOINT_VENTURE_QF3_foundation_int_mandarin += $row["level_count"];
                        }else{
                           $Franchisees_QF3_foundation_int_mandarin += $row["level_count"];
                        }
                        ?></span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.extend_year='$year'  and ps.student_entry_level != '' and s.student_status = 'A' ";
              //if($centre_code!="ALL"){
                $centreCode = $crow["centre_code"];
                $sql.=" and s.centre_code='$centreCode' ";
             //}
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end >= '$to_date')) and s.deleted='0' and fl.foundation_int_mandarin=1 and (ps.student_entry_level = 'QF1' or ps.student_entry_level = 'QF2' or ps.student_entry_level = 'QF3') group by ps.student_entry_level, s.id) ab";
            //echo $sql;
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span><?php 
                        $foundation_int_mandarin = $row["level_count"]; 
                        $Subtotal_Total_foundation_int_mandarin += $row["level_count"]; 
                        echo (empty($row["level_count"]) ? "0" : $row["level_count"]);
                        if($crow["name"]=="COMPANY OWNED"){
                           $COMPANY_OWNED_Total_foundation_int_mandarin += $row["level_count"];
                        }else if($crow["name"]=="JOINT VENTURE"){
                           $JOINT_VENTURE_Total_foundation_int_mandarin += $row["level_count"];
                        }else{
                           $Franchisees_Total_foundation_int_mandarin += $row["level_count"];
                        }
                         ?></span>
                        <?php
                     }
            ?>
            </td>
            <?php } ?>
             <!--start BIP MAND % (D)-->
             <?php if($course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
              echo ($Total_Stud_No_QF1_QF3>0 ? round(($foundation_int_mandarin/$Total_Stud_No_QF1_QF3)*100, 2).'%' : 00);
            ?>
            </td>
            <?php } ?>
             <!--end BIP MAND % (D)-->
             <!--end BIP MAND (enhancced foun. Mandarin)-->
            <!--start BIP ART (international art)-->
            <?php  if($course_subject == "QF1" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.extend_year='$year'  and ps.student_entry_level != '' and s.student_status = 'A' ";
              //if($centre_code!="ALL"){
                $centreCode = $crow["centre_code"];
                $sql.=" and s.centre_code='$centreCode' ";
             //}
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end >= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF1' and fl.foundation_int_art=1  group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span><?php 
                        $Subtotal_QF1_foundation_int_art += $row["level_count"]; 
                        echo (empty($row["level_count"]) ? "0" : $row["level_count"]); 
                        if($crow["name"]=="COMPANY OWNED"){
                           $COMPANY_OWNED_QF1_foundation_int_art += $row["level_count"];
                        }else if($crow["name"]=="JOINT VENTURE"){
                           $JOINT_VENTURE_QF1_foundation_int_art += $row["level_count"];
                        }else{
                           $Franchisees_QF1_foundation_int_art += $row["level_count"];
                        }
                        ?></span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == "QF2" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.extend_year='$year'  and ps.student_entry_level != '' and s.student_status = 'A' ";
              //if($centre_code!="ALL"){
                $centreCode = $crow["centre_code"];
                $sql.=" and s.centre_code='$centreCode' ";
             //}
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end >= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF2' and fl.foundation_int_art=1  group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span><?php 
                        $Subtotal_QF2_foundation_int_art += $row["level_count"]; 
                        echo (empty($row["level_count"]) ? "0" : $row["level_count"]); 
                        if($crow["name"]=="COMPANY OWNED"){
                           $COMPANY_OWNED_QF2_foundation_int_art += $row["level_count"];
                        }else if($crow["name"]=="JOINT VENTURE"){
                           $JOINT_VENTURE_QF2_foundation_int_art += $row["level_count"];
                        }else{
                           $Franchisees_QF2_foundation_int_art += $row["level_count"];
                        }
                        ?></span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == "QF3" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.extend_year='$year'  and ps.student_entry_level != '' and s.student_status = 'A' ";
              //if($centre_code!="ALL"){
                $centreCode = $crow["centre_code"];
                $sql.=" and s.centre_code='$centreCode' ";
             //}
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end >= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF3' and fl.foundation_int_art=1  group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span><?php 
                        $Subtotal_QF3_foundation_int_art += $row["level_count"]; 
                        echo (empty($row["level_count"]) ? "0" : $row["level_count"]);
                        if($crow["name"]=="COMPANY OWNED"){
                           $COMPANY_OWNED_QF3_foundation_int_art += $row["level_count"];
                        }else if($crow["name"]=="JOINT VENTURE"){
                           $JOINT_VENTURE_QF3_foundation_int_art += $row["level_count"];
                        }else{
                           $Franchisees_QF3_foundation_int_art += $row["level_count"];
                        }
                         ?></span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.extend_year='$year'  and ps.student_entry_level != '' and s.student_status = 'A' ";
              //if($centre_code!="ALL"){
                $centreCode = $crow["centre_code"];
                $sql.=" and s.centre_code='$centreCode' ";
             //}
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end >= '$to_date')) and s.deleted='0' and fl.foundation_int_art=1 and (ps.student_entry_level = 'QF1' or ps.student_entry_level = 'QF2' or ps.student_entry_level = 'QF3') group by ps.student_entry_level, s.id) ab";
            //echo $sql;
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span><?php 
                        $foundation_int_art = $row["level_count"]; 
                        $Subtotal_Total_foundation_int_art += $row["level_count"]; 
                        echo (empty($row["level_count"]) ? "0" : $row["level_count"]);
                        if($crow["name"]=="COMPANY OWNED"){
                           $COMPANY_OWNED_Total_foundation_int_art += $row["level_count"];
                        }else if($crow["name"]=="JOINT VENTURE"){
                           $JOINT_VENTURE_Total_foundation_int_art += $row["level_count"];
                        }else{
                           $Franchisees_Total_foundation_int_art += $row["level_count"];
                        }
                         ?></span>
                        <?php
                     }
            ?>
            </td>
            <?php } ?>
             <!--start BIP ART % (F)-->
             <?php if($course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
              echo ($Total_Stud_No_QF1_QF3>0 ? round(($foundation_int_art/$Total_Stud_No_QF1_QF3)*100, 2).'%' : 00);
            ?>
            </td>
            <?php } ?>
             <!--end BIP ART % (F)-->
             <!--end BIP ART (international art)-->
             <!--start MATH (IQ Math)-->
            <?php  if($course_subject == "QF1" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.extend_year='$year'  and ps.student_entry_level != '' and s.student_status = 'A' ";
              //if($centre_code!="ALL"){
                $centreCode = $crow["centre_code"];
                $sql.=" and s.centre_code='$centreCode' ";
             //}
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end >= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF1' and fl.foundation_iq_math=1  group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span><?php 
                        $Subtotal_QF1_foundation_iq_math += $row["level_count"]; 
                        echo (empty($row["level_count"]) ? "0" : $row["level_count"]); 
                        if($crow["name"]=="COMPANY OWNED"){
                           $COMPANY_OWNED_QF1_foundation_iq_math += $row["level_count"];
                        }else if($crow["name"]=="JOINT VENTURE"){
                           $JOINT_VENTURE_QF1_foundation_iq_math += $row["level_count"];
                        }else{
                           $Franchisees_QF1_foundation_iq_math += $row["level_count"];
                        }
                        ?></span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == "QF2" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.extend_year='$year'  and ps.student_entry_level != '' and s.student_status = 'A' ";
              //if($centre_code!="ALL"){
                $centreCode = $crow["centre_code"];
                $sql.=" and s.centre_code='$centreCode' ";
             //}
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end >= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF2' and fl.foundation_iq_math=1  group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span><?php 
                        $Subtotal_QF2_foundation_iq_math += $row["level_count"]; 
                        echo (empty($row["level_count"]) ? "0" : $row["level_count"]);
                        if($crow["name"]=="COMPANY OWNED"){
                           $COMPANY_OWNED_QF2_foundation_iq_math += $row["level_count"];
                        }else if($crow["name"]=="JOINT VENTURE"){
                           $JOINT_VENTURE_QF2_foundation_iq_math += $row["level_count"];
                        }else{
                           $Franchisees_QF2_foundation_iq_math += $row["level_count"];
                        }
                         ?></span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == "QF3" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.extend_year='$year'  and ps.student_entry_level != '' and s.student_status = 'A' ";
              //if($centre_code!="ALL"){
                $centreCode = $crow["centre_code"];
                $sql.=" and s.centre_code='$centreCode' ";
             //}
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end >= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF3' and fl.foundation_iq_math=1  group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span><?php 
                        $Subtotal_QF3_foundation_iq_math += $row["level_count"]; 
                        echo (empty($row["level_count"]) ? "0" : $row["level_count"]); 
                        if($crow["name"]=="COMPANY OWNED"){
                           $COMPANY_OWNED_QF3_foundation_iq_math += $row["level_count"];
                        }else if($crow["name"]=="JOINT VENTURE"){
                           $JOINT_VENTURE_QF3_foundation_iq_math += $row["level_count"];
                        }else{
                           $Franchisees_QF3_foundation_iq_math += $row["level_count"];
                        }
                        ?></span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.extend_year='$year'  and ps.student_entry_level != '' and s.student_status = 'A' ";
              //if($centre_code!="ALL"){
                $centreCode = $crow["centre_code"];
                $sql.=" and s.centre_code='$centreCode' ";
             //}
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end >= '$to_date')) and s.deleted='0' and fl.foundation_iq_math=1 and (ps.student_entry_level = 'QF1' or ps.student_entry_level = 'QF2' or ps.student_entry_level = 'QF3') group by ps.student_entry_level, s.id) ab";
            //echo $sql;
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span><?php 
                        $foundation_iq_math = $row["level_count"];
                        $Subtotal_Total_foundation_iq_math += $row["level_count"]; 
                        echo (empty($row["level_count"]) ? "0" : $row["level_count"]); 
                        if($crow["name"]=="COMPANY OWNED"){
                           $COMPANY_OWNED_Total_foundation_iq_math += $row["level_count"];
                        }else if($crow["name"]=="JOINT VENTURE"){
                           $JOINT_VENTURE_Total_foundation_iq_math += $row["level_count"];
                        }else{
                           $Franchisees_Total_foundation_iq_math += $row["level_count"];
                        }
                        ?></span>
                        <?php
                     }
            ?>
            </td>
            <?php } ?>
             <!--start BIP ART % (F)-->
             <?php if($course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
              echo ($Total_Stud_No_QF1_QF3>0 ? round(($foundation_iq_math/$Total_Stud_No_QF1_QF3)*100, 2).'%' : 00);
            ?>
            </td>
            <?php } ?>
             <!--end BIP ART % (F)-->
             <!--end MATH (IQ Math)-->
             <!--start Grand Total (total from BIP only)-->
             <?php if($course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php echo $grand_total_from_BIP_only = $foundation_int_english + $foundation_int_mandarin + $foundation_int_art + $foundation_iq_math; ?>
            </td>
            <?php } ?>
             <!--end Grand Total (total from BIP only)-->
             <!--start Total Beamind % (I)-->
             <?php if($course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
              echo ($Total_Stud_No_QF1_QF3>0 ? round(($grand_total_from_BIP_only/$Total_Stud_No_QF1_QF3)*100, 2).'%' : 00);
            ?>
            </td>
            <?php } ?>
             <!--end Total Beamind % (I)-->  
             <!--start Total Stud. No. QF1-QF3 (J)-->
             <?php if($course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
              echo $Total_Stud_No_QF1_QF3;
            ?>
            </td>
            <?php } ?>
             <!--end Total Stud. No. QF1-QF3 (J)-->
             <!--start Total Stud. No. QF1-QF3 (J)-->
             <?php if($course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php echo $crow["state"] ?>
            </td>
            <?php } ?>
             <!--end Total Stud. No. QF1-QF3 (J)-->
		   </tr>
       <?php }
       
       //if($category_name != $crow["name"]) { 
         $category_name = $csrow["name"]
         ?>
        <tr class="founda uk-text-bold">
          <td colspan="3">SUBTOTAL of <?php echo $csrow["name"] ?></td> 
          <td><?php 
            echo $Subtotal_EDP; 
            $Grand_EDP += $Subtotal_EDP;  ?>
          </td>
          <td><?php 
            echo $Subtotal_QF1; 
            $Grand_QF1 += $Subtotal_QF1;
          ?></td>
          <td><?php 
            echo $Subtotal_QF2; 
            $Grand_QF2 += $Subtotal_QF2;
            ?>
          </td>
          <td><?php 
            echo $Subtotal_QF3; 
            $Grand_QF3 += $Subtotal_QF3;
            ?>
          </td>
          <td><?php 
            echo $Subtotal_Total; 
            $Grand_Total += $Subtotal_Total;
            $Total_Stud_No_QF1_QF3 = $Subtotal_QF1+$Subtotal_QF2+$Subtotal_QF3; 
          ?></td>
          <td><?php 
            echo $Subtotal_Total_afternoon_programme; 
            $Grand_Total_afternoon_programme += $Subtotal_Total_afternoon_programme;
          ?></td>
          <td><?php 
            echo $Subtotal_QF2_foundation_mandarin; 
            $Grand_QF2_foundation_mandarin += $Subtotal_QF2_foundation_mandarin;
          ?></td>
          <td><?php 
            echo $Subtotal_QF3_foundation_mandarin; 
            $Grand_QF3_foundation_mandarin += $Subtotal_QF3_foundation_mandarin;
          ?></td>
          <td><?php 
            echo $Subtotal_Total_foundation_mandarin; 
            $Grand_Total_foundation_mandarin += $Subtotal_Total_foundation_mandarin;
          ?></td>
          <td><?php 
            echo $Subtotal_QF1_foundation_int_english;
            $Grand_QF1_foundation_int_english += $Subtotal_QF1_foundation_int_english; 
         ?></td>
          <td><?php 
            echo $Subtotal_QF2_foundation_int_english; 
            $Grand_QF2_foundation_int_english += $Subtotal_QF2_foundation_int_english;
          ?></td>
          <td><?php 
            echo $Subtotal_QF3_foundation_int_english; 
            $Grand_QF3_foundation_int_english += $Subtotal_QF3_foundation_int_english;
          ?></td>
          <td><?php 
            echo $Subtotal_Total_foundation_int_english; 
            $Grand_Total_foundation_int_english += $Subtotal_Total_foundation_int_english;
         ?></td>
          <td><?php 
            echo ($Total_Stud_No_QF1_QF3>0 ? round(($Subtotal_Total_foundation_int_english/$Total_Stud_No_QF1_QF3)*100, 2).'%' : 00); 
           // $Grand_EDP += $Subtotal_EDP;
          ?></td>
          <td><?php 
            echo $Subtotal_QF1_foundation_int_mandarin; 
            $Grand_QF1_foundation_int_mandarin += $Subtotal_QF1_foundation_int_mandarin;
          ?></td>
          <td><?php 
            echo $Subtotal_QF2_foundation_int_mandarin; 
            $Grand_QF2_foundation_int_mandarin += $Subtotal_QF2_foundation_int_mandarin;
          ?></td>
          <td><?php 
            echo $Subtotal_QF3_foundation_int_mandarin; 
            $Grand_QF3_foundation_int_mandarin += $Subtotal_QF3_foundation_int_mandarin;
          ?></td>
          <td><?php 
            echo $Subtotal_Total_foundation_int_mandarin; 
            $Grand_Total_foundation_int_mandarin += $Subtotal_Total_foundation_int_mandarin;
          ?></td>
          <td><?php 
            echo ($Total_Stud_No_QF1_QF3>0 ? round(($Subtotal_Total_foundation_int_mandarin/$Total_Stud_No_QF1_QF3)*100, 2).'%' : 00); 
            //$Grand_EDP += $Subtotal_EDP;
          ?></td>
          <td><?php 
            echo $Subtotal_QF1_foundation_int_art; 
            $Grand_QF1_foundation_int_art += $Subtotal_QF1_foundation_int_art;
          ?></td>
          <td><?php 
            echo $Subtotal_QF2_foundation_int_art; 
            $Grand_QF2_foundation_int_art += $Subtotal_QF2_foundation_int_art;
          ?></td>
          <td><?php 
            echo $Subtotal_QF3_foundation_int_art; 
            $Grand_QF3_foundation_int_art += $Subtotal_QF3_foundation_int_art;
          ?></td>
          <td><?php 
            echo $Subtotal_Total_foundation_int_art; 
            $Grand_Total_foundation_int_art += $Subtotal_Total_foundation_int_art;
          ?></td>
          <td><?php 
            echo ($Total_Stud_No_QF1_QF3>0 ? round(($Subtotal_Total_foundation_int_art/$Total_Stud_No_QF1_QF3)*100, 2).'%' : 00); 
           // $Grand_EDP += $Subtotal_EDP;
          ?></td>
          <td><?php 
            echo $Subtotal_QF1_foundation_iq_math; 
            $Grand_QF1_foundation_iq_math += $Subtotal_QF1_foundation_iq_math;
          ?></td>
          <td><?php 
            echo $Subtotal_QF2_foundation_iq_math; 
            $Grand_QF2_foundation_iq_math += $Subtotal_QF2_foundation_iq_math;
          ?></td>
          <td><?php 
            echo $Subtotal_QF3_foundation_iq_math; 
            $Grand_QF3_foundation_iq_math += $Subtotal_QF3_foundation_iq_math;
          ?></td>
          <td><?php 
            echo $Subtotal_Total_foundation_iq_math; 
            $Grand_Total_foundation_iq_math += $Subtotal_Total_foundation_iq_math;
          ?></td>
          <td><?php 
            echo ($Total_Stud_No_QF1_QF3>0 ? round(($Subtotal_Total_foundation_iq_math/$Total_Stud_No_QF1_QF3)*100, 2).'%' : 00); 
            //$Grand_EDP += $Subtotal_EDP;
          ?></td>
          <td><?php 
            echo $grand_total_from_BIP_only = $Subtotal_Total_foundation_int_english + $Subtotal_Total_foundation_int_mandarin + $Subtotal_Total_foundation_int_art + $Subtotal_Total_foundation_iq_math; 
            //$Grand_EDP += $Subtotal_EDP;
          ?></td>
          <td><?php 
            echo ($Total_Stud_No_QF1_QF3>0 ? round(($grand_total_from_BIP_only/$Total_Stud_No_QF1_QF3)*100, 2).'%' : 00); 
           // $Grand_EDP += $Subtotal_EDP;
          ?></td> <!--Total Beamind % (I)-->
          <td><?php 
            echo $Total_Stud_No_QF1_QF3; 
            //$Grand_EDP += $Subtotal_EDP;
          ?></td> <!--Total Stud. No. QF1-QF3 (J)-->
          <td></td> <!--Location (State)-->
        </tr>
      <?php 
      $EDP = 0;
      $QF1 = 0;
      $QF2 = 0;
      $QF3 = 0;
      $Subtotal_EDP = 0;
      $Subtotal_QF1 = 0;
      $Subtotal_QF2 = 0;
      $Subtotal_QF3 = 0;
      $Subtotal_Total = 0;
      $afternoon_programme = 0;
      $Subtotal_Total_afternoon_programme = 0;
      $Subtotal_QF2_foundation_mandarin = 0;
      $Subtotal_QF3_foundation_mandarin = 0;
      $foundation_mandarin = 0;
      $Subtotal_Total_foundation_mandarin = 0;
      $Subtotal_QF1_foundation_int_english = 0;
      $Subtotal_QF2_foundation_int_english = 0;
      $Subtotal_QF3_foundation_int_english = 0;
      $foundation_int_english = 0;
      $Subtotal_Total_foundation_int_english = 0;
      $Subtotal_QF1_foundation_int_mandarin = 0;
      $Subtotal_QF2_foundation_int_mandarin = 0;
      $Subtotal_QF3_foundation_int_mandarin = 0;
      $foundation_int_mandarin = 0;
      $Subtotal_Total_foundation_int_mandarin = 0;
      $Subtotal_QF1_foundation_int_art = 0;
      $Subtotal_QF2_foundation_int_art = 0;
      $Subtotal_QF3_foundation_int_art = 0;
      $foundation_int_art = 0;
      $Subtotal_Total_foundation_int_art = 0;
      $Subtotal_QF1_foundation_iq_math = 0;
       $Subtotal_QF2_foundation_iq_math = 0;
       $Subtotal_QF3_foundation_iq_math = 0;
       $foundation_iq_math = 0;
       $Subtotal_Total_foundation_iq_math = 0;
       $grand_total_from_BIP_only = 0;
       $Total_Stud_No_QF1_QF3 = 0;
      //die;
      //} ?>
		  
         <?php
             }
         // }
         ?>
         <!--start All CO (Company Owned) Total-->
          <tr class="founda uk-text-bold">
          <td colspan="3">All CO (Company Owned) Total: </td> 
          <td><?php echo $COMPANY_OWNED_EDP;  ?></td>
          <td><?php echo $COMPANY_OWNED_QF1; ?></td>
          <td><?php echo $COMPANY_OWNED_QF2; ?></td>
          <td><?php echo $COMPANY_OWNED_QF3; ?></td>
          <td><?php echo $COMPANY_OWNED_Total; 
          $COMPANY_OWNED_Total_Stud_No = $COMPANY_OWNED_QF1+$COMPANY_OWNED_QF2+$COMPANY_OWNED_QF3;
          ?></td>
          <td><?php echo $COMPANY_OWNED_Total_afternoon_programme; ?></td>
          <td><?php echo $COMPANY_OWNED_QF2_foundation_mandarin; ?></td>
          <td><?php echo $COMPANY_OWNED_QF3_foundation_mandarin; ?></td>
          <td><?php echo $COMPANY_OWNED_Total_foundation_mandarin; ?></td>
          <td><?php echo $COMPANY_OWNED_QF1_foundation_int_english; ?></td>
          <td><?php echo $COMPANY_OWNED_QF2_foundation_int_english; ?></td>
          <td><?php echo $COMPANY_OWNED_QF3_foundation_int_english; ?></td>
          <td><?php echo $COMPANY_OWNED_Total_foundation_int_english; ?></td>
          <td><?php echo ($COMPANY_OWNED_Total_Stud_No>0 ? round(($COMPANY_OWNED_Total_foundation_int_english/$COMPANY_OWNED_Total_Stud_No)*100, 2).'%' : 00); ?></td>
          <td><?php echo $COMPANY_OWNED_QF1_foundation_int_mandarin; ?></td>
          <td><?php echo $COMPANY_OWNED_QF2_foundation_int_mandarin; ?></td>
          <td><?php echo $COMPANY_OWNED_QF3_foundation_int_mandarin; ?></td>
          <td><?php echo $COMPANY_OWNED_Total_foundation_int_mandarin; ?></td>
          <td><?php echo ($COMPANY_OWNED_Total_Stud_No>0 ? round(($COMPANY_OWNED_Total_foundation_int_mandarin/$COMPANY_OWNED_Total_Stud_No)*100, 2).'%' : 00); ?></td>
          <td><?php echo $COMPANY_OWNED_QF1_foundation_int_art; ?></td>
          <td><?php echo $COMPANY_OWNED_QF2_foundation_int_art; ?></td>
          <td><?php echo $COMPANY_OWNED_QF3_foundation_int_art; ?></td>
          <td><?php echo $COMPANY_OWNED_Total_foundation_int_art; ?></td>
          <td><?php echo ($COMPANY_OWNED_Total_Stud_No>0 ? round(($COMPANY_OWNED_Total_foundation_int_art/$COMPANY_OWNED_Total_Stud_No)*100, 2).'%' : 00); ?></td>
          <td><?php echo $COMPANY_OWNED_QF1_foundation_iq_math; ?></td>
          <td><?php echo $COMPANY_OWNED_QF2_foundation_iq_math; ?></td>
          <td><?php echo $COMPANY_OWNED_QF3_foundation_iq_math; ?></td>
          <td><?php echo $COMPANY_OWNED_Total_foundation_iq_math; ?></td>
          <td><?php echo ($COMPANY_OWNED_Total_Stud_No>0 ? round(($COMPANY_OWNED_Total_foundation_iq_math/$COMPANY_OWNED_Total_Stud_No)*100, 2).'%' : 00); ?></td>
          <td><?php echo $COMPANY_OWNED_total_from_BIP_only = $COMPANY_OWNED_Total_foundation_int_english + $COMPANY_OWNED_Total_foundation_int_mandarin + $COMPANY_OWNED_Total_foundation_int_art + $COMPANY_OWNED_Total_foundation_iq_math; ?></td>
          <td><?php echo $COMPANY_OWNED_Total_Beamind = ($COMPANY_OWNED_Total_Stud_No>0 ? round(($COMPANY_OWNED_total_from_BIP_only/$COMPANY_OWNED_Total_Stud_No)*100, 2).'%' : 00); ?></td> <!--Total Beamind % (I)-->
          <td><?php echo $COMPANY_OWNED_Total_Stud_No; ?></td> <!--Total Stud. No. QF1-QF3 (J)-->
          <td></td> <!--Location (State)-->
         <!--end All CO (Company Owned) Total-->

          <!--start CO Percentage-->
          <tr class="founda uk-text-bold">
          <td colspan="3">CO Percentage: </td> 
          <td><?php echo ($COMPANY_OWNED_Total>0 ?  round(($COMPANY_OWNED_EDP/$COMPANY_OWNED_Total)*100, 2) : 0).'%';  ?></td>
          <td><?php echo ($COMPANY_OWNED_Total>0 ?  round(($COMPANY_OWNED_QF1/$COMPANY_OWNED_Total)*100, 2) : 0).'%'; ?></td>
          <td><?php echo ($COMPANY_OWNED_Total>0 ?  round(($COMPANY_OWNED_QF2/$COMPANY_OWNED_Total)*100, 2) : 0).'%'; ?></td>
          <td><?php echo ($COMPANY_OWNED_Total>0 ?  round(($COMPANY_OWNED_QF3/$COMPANY_OWNED_Total)*100, 2) : 0).'%'; ?></td>
          <td><?php echo ($COMPANY_OWNED_Total>0 ?  round(($COMPANY_OWNED_Total/$COMPANY_OWNED_Total)*100, 2) : 0).'%'; ?></td>
          <td> / </td>
          <td><?php echo ($COMPANY_OWNED_Total_foundation_mandarin>0 ?  round(($COMPANY_OWNED_QF2_foundation_mandarin/$COMPANY_OWNED_Total_foundation_mandarin)*100, 2) : 0); ?>%</td>
          <td><?php echo ($COMPANY_OWNED_Total_foundation_mandarin>0 ?  round(($COMPANY_OWNED_QF3_foundation_mandarin/$COMPANY_OWNED_Total_foundation_mandarin)*100, 2) : 0); ?>%</td>
          <td><?php echo ($COMPANY_OWNED_Total_foundation_mandarin>0 ?  round(($COMPANY_OWNED_Total_foundation_mandarin/$COMPANY_OWNED_Total_foundation_mandarin)*100, 2) : 0); ?>%</td>
          <td><?php echo ($COMPANY_OWNED_Total_foundation_int_english>0 ?  round(($COMPANY_OWNED_QF1_foundation_int_english/$COMPANY_OWNED_Total_foundation_int_english)*100, 2) : 0); ?>%</td>
          <td><?php echo ($COMPANY_OWNED_Total_foundation_int_english>0 ?  round(($COMPANY_OWNED_QF2_foundation_int_english/$COMPANY_OWNED_Total_foundation_int_english)*100, 2) : 0); ?>%</td>
          <td><?php echo ($COMPANY_OWNED_Total_foundation_int_english>0 ?  round(($COMPANY_OWNED_QF3_foundation_int_english/$COMPANY_OWNED_Total_foundation_int_english)*100, 2) : 0); ?>%</td>
          <td><?php echo $COMPANY_OWNED_Total_A = ($COMPANY_OWNED_Total_foundation_int_english>0 ?  round(($COMPANY_OWNED_Total_foundation_int_english/$COMPANY_OWNED_Total_foundation_int_english)*100, 2) : 0); ?>%</td>
          <td><?php $Total_student_percen_COMPANY_OWNED = ($COMPANY_OWNED_total_from_BIP_only>0 ?  round(($COMPANY_OWNED_Total_Stud_No/$COMPANY_OWNED_total_from_BIP_only)*100, 2) : 0);
          echo ($Total_student_percen_COMPANY_OWNED>0 ? round(($COMPANY_OWNED_BIP_int_english/$Total_student_percen_COMPANY_OWNED), 2) : 00); ?>%</td>
          <td><?php echo ($COMPANY_OWNED_Total_foundation_int_mandarin>0 ?  round(($COMPANY_OWNED_QF1_foundation_int_mandarin/$COMPANY_OWNED_Total_foundation_int_mandarin)*100, 2) : 0); ?>%</td>
          <td><?php echo ($COMPANY_OWNED_Total_foundation_int_mandarin>0 ?  round(($COMPANY_OWNED_QF2_foundation_int_mandarin/$COMPANY_OWNED_Total_foundation_int_mandarin)*100, 2) : 0); ?>%</td>
          <td><?php echo ($COMPANY_OWNED_Total_foundation_int_mandarin>0 ?  round(($COMPANY_OWNED_QF3_foundation_int_mandarin/$COMPANY_OWNED_Total_foundation_int_mandarin)*100, 2) : 0); ?>%</td>
          <td><?php echo $COMPANY_OWNED_Total_C = ($COMPANY_OWNED_Total_foundation_int_mandarin>0 ?  round(($COMPANY_OWNED_Total_foundation_int_mandarin/$COMPANY_OWNED_Total_foundation_int_mandarin)*100, 2) : 0); ?>%</td>
          <td><?php echo ($Total_student_percen_COMPANY_OWNED>0 ? round(($COMPANY_OWNED_BIP_int_mandarin/$Total_student_percen_COMPANY_OWNED), 2) : 00); ?>%</td>
          <td><?php echo ($COMPANY_OWNED_Total_foundation_int_art>0 ?  round(($COMPANY_OWNED_QF1_foundation_int_art/$COMPANY_OWNED_Total_foundation_int_art)*100, 2) : 0); ?>%</td>
          <td><?php echo ($COMPANY_OWNED_Total_foundation_int_art>0 ?  round(($COMPANY_OWNED_QF2_foundation_int_art/$COMPANY_OWNED_Total_foundation_int_art)*100, 2) : 0); ?>%</td>
          <td><?php echo ($COMPANY_OWNED_Total_foundation_int_art>0 ?  round(($COMPANY_OWNED_QF3_foundation_int_art/$COMPANY_OWNED_Total_foundation_int_art)*100, 2) : 0); ?>%</td>
          <td><?php echo $COMPANY_OWNED_Total_E = ($COMPANY_OWNED_Total_foundation_int_art>0 ?  round(($COMPANY_OWNED_Total_foundation_int_art/$COMPANY_OWNED_Total_foundation_int_art)*100, 2) : 0); ?>%</td>
          <td><?php echo ($Total_student_percen_COMPANY_OWNED>0 ? round(($COMPANY_OWNED_BIP_int_art/$Total_student_percen_COMPANY_OWNED), 2) : 00); ?>%</td>
          <td><?php echo ($COMPANY_OWNED_Total_foundation_iq_math>0 ?  round(($COMPANY_OWNED_QF1_foundation_iq_math/$COMPANY_OWNED_Total_foundation_iq_math)*100, 2) : 0); ?>%</td>
          <td><?php echo ($COMPANY_OWNED_Total_foundation_iq_math>0 ?  round(($COMPANY_OWNED_QF2_foundation_iq_math/$COMPANY_OWNED_Total_foundation_iq_math)*100, 2) : 0); ?>%</td>
          <td><?php echo ($COMPANY_OWNED_Total_foundation_iq_math>0 ?  round(($COMPANY_OWNED_QF3_foundation_iq_math/$COMPANY_OWNED_Total_foundation_iq_math)*100, 2) : 0); ?>%</td>
          <td><?php echo $COMPANY_OWNED_Total_G = ($COMPANY_OWNED_Total_foundation_iq_math>0 ?  round(($COMPANY_OWNED_Total_foundation_iq_math/$COMPANY_OWNED_Total_foundation_iq_math)*100, 2) : 0); ?>%</td>
          <td><?php echo ($Total_student_percen_COMPANY_OWNED>0 ? round(($COMPANY_OWNED_BIP_iq_math/$Total_student_percen_COMPANY_OWNED), 2) : 00); ?>%</td>
          <td><?php echo $Grand_total_COMPANY_OWNED = $COMPANY_OWNED_Total_A + $COMPANY_OWNED_Total_C + $COMPANY_OWNED_Total_E + $COMPANY_OWNED_Total_G; ?>%</td>
          <td><?php echo ($Total_student_percen_COMPANY_OWNED>0 ? round(($Grand_total_COMPANY_OWNED/$Total_student_percen_COMPANY_OWNED)*100, 2) : 00); ?>%</td> <!--Total Beamind % (I)-->
          <td><?php echo $Total_student_percen_COMPANY_OWNED; ?>%</td> <!--Total Stud. No. QF1-QF3 (J)-->
          <td></td> <!--Location (State)-->
        </tr>
         <!--end CO Percentage-->
         
         

         <!--start All JV (Joint Venture) Total-->
          <tr class="founda uk-text-bold">
          <td colspan="3">All JV (Joint Venture) Total: </td> 
          <td><?php echo $JOINT_VENTURE_EDP;  ?></td>
          <td><?php echo $JOINT_VENTURE_QF1; ?></td>
          <td><?php echo $JOINT_VENTURE_QF2; ?></td>
          <td><?php echo $JOINT_VENTURE_QF3; ?></td>
          <td><?php echo $JOINT_VENTURE_Total; 
          $JOINT_VENTURE_Total_Stud_No = $JOINT_VENTURE_QF1+$JOINT_VENTURE_QF2+$JOINT_VENTURE_QF3;
          ?></td>
          <td><?php echo $JOINT_VENTURE_Total_afternoon_programme; ?></td>
          <td><?php echo $JOINT_VENTURE_QF2_foundation_mandarin; ?></td>
          <td><?php echo $JOINT_VENTURE_QF3_foundation_mandarin; ?></td>
          <td><?php echo $JOINT_VENTURE_Total_foundation_mandarin; ?></td>
          <td><?php echo $JOINT_VENTURE_QF1_foundation_int_english; ?></td>
          <td><?php echo $JOINT_VENTURE_QF2_foundation_int_english; ?></td>
          <td><?php echo $JOINT_VENTURE_QF3_foundation_int_english; ?></td>
          <td><?php echo $JOINT_VENTURE_Total_foundation_int_english; ?></td>
          <td><?php echo ($JOINT_VENTURE_Total_Stud_No>0 ? round(($JOINT_VENTURE_Total_foundation_int_english/$JOINT_VENTURE_Total_Stud_No)*100, 2).'%' : 00); ?></td>
          <td><?php echo $JOINT_VENTURE_QF1_foundation_int_mandarin; ?></td>
          <td><?php echo $JOINT_VENTURE_QF2_foundation_int_mandarin; ?></td>
          <td><?php echo $JOINT_VENTURE_QF3_foundation_int_mandarin; ?></td>
          <td><?php echo $JOINT_VENTURE_Total_foundation_int_mandarin; ?></td>
          <td><?php echo ($JOINT_VENTURE_Total_Stud_No>0 ? round(($JOINT_VENTURE_Total_foundation_int_mandarin/$JOINT_VENTURE_Total_Stud_No)*100, 2).'%' : 00); ?></td>
          <td><?php echo $JOINT_VENTURE_QF1_foundation_int_art; ?></td>
          <td><?php echo $JOINT_VENTURE_QF2_foundation_int_art; ?></td>
          <td><?php echo $JOINT_VENTURE_QF3_foundation_int_art; ?></td>
          <td><?php echo $JOINT_VENTURE_Total_foundation_int_art; ?></td>
          <td><?php echo ($JOINT_VENTURE_Total_Stud_No>0 ? round(($JOINT_VENTURE_Total_foundation_int_art/$JOINT_VENTURE_Total_Stud_No)*100, 2).'%' : 00); ?></td>
          <td><?php echo $JOINT_VENTURE_QF1_foundation_iq_math; ?></td>
          <td><?php echo $JOINT_VENTURE_QF2_foundation_iq_math; ?></td>
          <td><?php echo $JOINT_VENTURE_QF3_foundation_iq_math; ?></td>
          <td><?php echo $JOINT_VENTURE_Total_foundation_iq_math; ?></td>
          <td><?php echo ($JOINT_VENTURE_Total_Stud_No>0 ? round(($JOINT_VENTURE_Total_foundation_iq_math/$JOINT_VENTURE_Total_Stud_No)*100, 2).'%' : 00); ?></td>
          <td><?php echo $JOINT_VENTURE_total_from_BIP_only = $JOINT_VENTURE_Total_foundation_int_english + $JOINT_VENTURE_Total_foundation_int_mandarin + $JOINT_VENTURE_Total_foundation_int_art + $JOINT_VENTURE_Total_foundation_iq_math; ?></td>
          <td><?php echo $JOINT_VENTURE_Total_Beamind = ($JOINT_VENTURE_Total_Stud_No>0 ? round(($JOINT_VENTURE_total_from_BIP_only/$JOINT_VENTURE_Total_Stud_No)*100, 2).'%' : 00);
           ?></td> <!--Total Beamind % (I)-->
          <td><?php echo $JOINT_VENTURE_Total_Stud_No; ?></td> <!--Total Stud. No. QF1-QF3 (J)-->
          <td></td> <!--Location (State)-->
        </tr>
         <!--end All JV (Joint Venture) Total-->

          <!--start JV Percentage-->
          <tr class="founda uk-text-bold">
          <td colspan="3">JV Percentage: </td> 
          <td><?php echo ($JOINT_VENTURE_Total>0 ?  round(($JOINT_VENTURE_EDP/$JOINT_VENTURE_Total)*100, 2) : 0).'%';  ?></td>
          <td><?php echo ($JOINT_VENTURE_Total>0 ?  round(($JOINT_VENTURE_QF1/$JOINT_VENTURE_Total)*100, 2) : 0).'%'; ?></td>
          <td><?php echo ($JOINT_VENTURE_Total>0 ?  round(($JOINT_VENTURE_QF2/$JOINT_VENTURE_Total)*100, 2) : 0).'%'; ?></td>
          <td><?php echo ($JOINT_VENTURE_Total>0 ?  round(($JOINT_VENTURE_QF3/$JOINT_VENTURE_Total)*100, 2) : 0).'%'; ?></td>
          <td><?php echo ($JOINT_VENTURE_Total>0 ?  round(($JOINT_VENTURE_Total/$JOINT_VENTURE_Total)*100, 2) : 0).'%'; ?></td>
          <td> / </td>
          <td><?php echo ($JOINT_VENTURE_Total_foundation_mandarin>0 ?  round(($JOINT_VENTURE_QF2_foundation_mandarin/$JOINT_VENTURE_Total_foundation_mandarin)*100, 2) : 0); ?>%</td>
          <td><?php echo ($JOINT_VENTURE_Total_foundation_mandarin>0 ?  round(($JOINT_VENTURE_QF3_foundation_mandarin/$JOINT_VENTURE_Total_foundation_mandarin)*100, 2) : 0); ?>%</td>
          <td><?php echo ($JOINT_VENTURE_Total_foundation_mandarin>0 ?  round(($JOINT_VENTURE_Total_foundation_mandarin/$JOINT_VENTURE_Total_foundation_mandarin)*100, 2) : 0); ?>%</td>
          <td><?php echo ($JOINT_VENTURE_Total_foundation_int_english>0 ?  round(($JOINT_VENTURE_QF1_foundation_int_english/$JOINT_VENTURE_Total_foundation_int_english)*100, 2) : 0); ?>%</td>
          <td><?php echo ($JOINT_VENTURE_Total_foundation_int_english>0 ?  round(($JOINT_VENTURE_QF2_foundation_int_english/$JOINT_VENTURE_Total_foundation_int_english)*100, 2) : 0); ?>%</td>
          <td><?php echo ($JOINT_VENTURE_Total_foundation_int_english>0 ?  round(($JOINT_VENTURE_QF3_foundation_int_english/$JOINT_VENTURE_Total_foundation_int_english)*100, 2) : 0); ?>%</td>
          <td><?php echo $JOINT_VENTURE_Total_A = ($JOINT_VENTURE_Total_foundation_int_english>0 ?  round(($JOINT_VENTURE_Total_foundation_int_english/$JOINT_VENTURE_Total_foundation_int_english)*100, 2) : 0); ?>%</td>
          <td><?php $Total_student_percen_JOINT_VENTURE = ($JOINT_VENTURE_total_from_BIP_only>0 ?  round(($JOINT_VENTURE_Total_Stud_No/$JOINT_VENTURE_total_from_BIP_only)*100, 2) : 0);
          echo ($Total_student_percen_JOINT_VENTURE>0 ? round(($JOINT_VENTURE_BIP_int_english/$Total_student_percen_JOINT_VENTURE), 2) : 00); ?>%</td>
          <td><?php echo ($JOINT_VENTURE_Total_foundation_int_mandarin>0 ?  round(($JOINT_VENTURE_QF1_foundation_int_mandarin/$JOINT_VENTURE_Total_foundation_int_mandarin)*100, 2) : 0); ?>%</td>
          <td><?php echo ($JOINT_VENTURE_Total_foundation_int_mandarin>0 ?  round(($JOINT_VENTURE_QF2_foundation_int_mandarin/$JOINT_VENTURE_Total_foundation_int_mandarin)*100, 2) : 0); ?>%</td>
          <td><?php echo ($JOINT_VENTURE_Total_foundation_int_mandarin>0 ?  round(($JOINT_VENTURE_QF3_foundation_int_mandarin/$JOINT_VENTURE_Total_foundation_int_mandarin)*100, 2) : 0); ?>%</td>
          <td><?php echo $JOINT_VENTURE_Total_C = ($JOINT_VENTURE_Total_foundation_int_mandarin>0 ?  round(($JOINT_VENTURE_Total_foundation_int_mandarin/$JOINT_VENTURE_Total_foundation_int_mandarin)*100, 2) : 0); ?>%</td>
          <td><?php echo ($Total_student_percen_JOINT_VENTURE>0 ? round(($JOINT_VENTURE_BIP_int_mandarin/$Total_student_percen_JOINT_VENTURE), 2) : 00); ?>%</td>
          <td><?php echo ($JOINT_VENTURE_Total_foundation_int_art>0 ?  round(($JOINT_VENTURE_QF1_foundation_int_art/$JOINT_VENTURE_Total_foundation_int_art)*100, 2) : 0); ?>%</td>
          <td><?php echo ($JOINT_VENTURE_Total_foundation_int_art>0 ?  round(($JOINT_VENTURE_QF2_foundation_int_art/$JOINT_VENTURE_Total_foundation_int_art)*100, 2) : 0); ?>%</td>
          <td><?php echo ($JOINT_VENTURE_Total_foundation_int_art>0 ?  round(($JOINT_VENTURE_QF3_foundation_int_art/$JOINT_VENTURE_Total_foundation_int_art)*100, 2) : 0); ?>%</td>
          <td><?php echo $JOINT_VENTURE_Total_E = ($JOINT_VENTURE_Total_foundation_int_art>0 ?  round(($JOINT_VENTURE_Total_foundation_int_art/$JOINT_VENTURE_Total_foundation_int_art)*100, 2) : 0); ?>%</td>
          <td><?php echo ($Total_student_percen_JOINT_VENTURE>0 ? round(($JOINT_VENTURE_BIP_int_art/$Total_student_percen_JOINT_VENTURE), 2) : 00); ?>%</td>
          <td><?php echo ($JOINT_VENTURE_Total_foundation_iq_math>0 ?  round(($JOINT_VENTURE_QF1_foundation_iq_math/$JOINT_VENTURE_Total_foundation_iq_math)*100, 2) : 0); ?>%</td>
          <td><?php echo ($JOINT_VENTURE_Total_foundation_iq_math>0 ?  round(($JOINT_VENTURE_QF2_foundation_iq_math/$JOINT_VENTURE_Total_foundation_iq_math)*100, 2) : 0); ?>%</td>
          <td><?php echo ($JOINT_VENTURE_Total_foundation_iq_math>0 ?  round(($JOINT_VENTURE_QF3_foundation_iq_math/$JOINT_VENTURE_Total_foundation_iq_math)*100, 2) : 0); ?>%</td>
          <td><?php echo $JOINT_VENTURE_Total_G = ($JOINT_VENTURE_Total_foundation_iq_math>0 ?  round(($JOINT_VENTURE_Total_foundation_iq_math/$JOINT_VENTURE_Total_foundation_iq_math)*100, 2) : 0); ?>%</td>
          <td><?php echo ($Total_student_percen_JOINT_VENTURE>0 ? round(($JOINT_VENTURE_BIP_iq_math/$Total_student_percen_JOINT_VENTURE), 2) : 00); ?>%</td>
          <td><?php echo $Grand_total_JOINT_VENTURE = $JOINT_VENTURE_Total_A + $JOINT_VENTURE_Total_C + $JOINT_VENTURE_Total_E + $JOINT_VENTURE_Total_G; ?>%</td>
          <td><?php echo ($Total_student_percen_JOINT_VENTURE>0 ? round(($Grand_total_JOINT_VENTURE/$Total_student_percen_JOINT_VENTURE)*100, 2) : 00); ?>%</td> <!--Total Beamind % (I)-->
          <td><?php echo $Total_student_percen_JOINT_VENTURE; ?>%</td> <!--Total Stud. No. QF1-QF3 (J)-->
          <td></td> <!--Location (State)-->
        </tr>
         <!--end JOINT_VENTURE Percentage-->

          <!--start All 2004-2021 Franchisees Total-->
          <tr class="founda uk-text-bold">
          <td colspan="3">All Pioneer and 2004-2021 Franchisees Total: </td> 
          <td><?php echo $Franchisees_EDP;  ?></td>
          <td><?php echo $Franchisees_QF1; ?></td>
          <td><?php echo $Franchisees_QF2; ?></td>
          <td><?php echo $Franchisees_QF3; ?></td>
          <td><?php 
          echo $Franchisees_Total; 
          $Franchisees_Total_Stud_No = $Franchisees_QF1+$Franchisees_QF2+$Franchisees_QF3;
          ?></td>
          <td><?php echo $Franchisees_Total_afternoon_programme; ?></td>
          <td><?php echo $Franchisees_QF2_foundation_mandarin; ?></td>
          <td><?php echo $Franchisees_QF3_foundation_mandarin; ?></td>
          <td><?php echo $Franchisees_Total_foundation_mandarin; ?></td>
          <td><?php echo $Franchisees_QF1_foundation_int_english; ?></td>
          <td><?php echo $Franchisees_QF2_foundation_int_english; ?></td>
          <td><?php echo $Franchisees_QF3_foundation_int_english; ?></td>
          <td><?php echo $Franchisees_Total_foundation_int_english; ?></td>
          <td><?php echo $Franchisees_BIP_int_english = ($Franchisees_Total_Stud_No>0 ? round(($Franchisees_Total_foundation_int_english/$Franchisees_Total_Stud_No)*100, 2).'%' : 00); ?></td>
          <td><?php echo $Franchisees_QF1_foundation_int_mandarin; ?></td>
          <td><?php echo $Franchisees_QF2_foundation_int_mandarin; ?></td>
          <td><?php echo $Franchisees_QF3_foundation_int_mandarin; ?></td>
          <td><?php echo $Franchisees_Total_foundation_int_mandarin; ?></td>
          <td><?php echo $Franchisees_BIP_int_mandarin = ($Franchisees_Total_Stud_No>0 ? round(($Franchisees_Total_foundation_int_mandarin/$Franchisees_Total_Stud_No)*100, 2).'%' : 00); ?></td>
          <td><?php echo $Franchisees_QF1_foundation_int_art; ?></td>
          <td><?php echo $Franchisees_QF2_foundation_int_art; ?></td>
          <td><?php echo $Franchisees_QF3_foundation_int_art; ?></td>
          <td><?php echo $Franchisees_Total_foundation_int_art; ?></td>
          <td><?php echo $Franchisees_BIP_int_art =  ($Franchisees_Total_Stud_No>0 ? round(($Franchisees_Total_foundation_int_art/$Franchisees_Total_Stud_No)*100, 2).'%' : 00); ?></td>
          <td><?php echo $Franchisees_QF1_foundation_iq_math; ?></td>
          <td><?php echo $Franchisees_QF2_foundation_iq_math; ?></td>
          <td><?php echo $Franchisees_QF3_foundation_iq_math; ?></td>
          <td><?php echo $Franchisees_Total_foundation_iq_math; ?></td>
          <td><?php echo $Franchisees_BIP_iq_math = ($Franchisees_Total_Stud_No>0 ? round(($Franchisees_Total_foundation_iq_math/$Franchisees_Total_Stud_No)*100, 2) : 00); ?>%</td>
          <td><?php echo $Franchisees_total_from_BIP_only = $Franchisees_Total_foundation_int_english + $Franchisees_Total_foundation_int_mandarin + $Franchisees_Total_foundation_int_art + $Franchisees_Total_foundation_iq_math; ?></td>
          <td><?php echo $Franchisees_Total_Beamind = ($Franchisees_Total_Stud_No>0 ? round(($Franchisees_total_from_BIP_only/$Franchisees_Total_Stud_No)*100, 2).'%' : 00); ?></td> <!--Total Beamind % (I)-->
          <td><?php echo  $Franchisees_Total_Stud_No; ?></td> <!--Total Stud. No. QF1-QF3 (J)-->
          <td></td> <!--Location (State)-->
        </tr>
         <!--end All 2004-2021 Franchisees Total-->

          <!--start Franchisees Percentage-->
          <tr class="founda uk-text-bold">
          <td colspan="3">Franchisees Percentage: </td> 
          <td><?php echo ($Franchisees_Total>0 ?  round(($Franchisees_EDP/$Franchisees_Total)*100, 2) : 0).'%';  ?></td>
          <td><?php echo ($Franchisees_Total>0 ?  round(($Franchisees_QF1/$Franchisees_Total)*100, 2) : 0).'%'; ?></td>
          <td><?php echo ($Franchisees_Total>0 ?  round(($Franchisees_QF2/$Franchisees_Total)*100, 2) : 0).'%'; ?></td>
          <td><?php echo ($Franchisees_Total>0 ?  round(($Franchisees_QF3/$Franchisees_Total)*100, 2) : 0).'%'; ?></td>
          <td><?php echo ($Franchisees_Total>0 ?  round(($Franchisees_Total/$Franchisees_Total)*100, 2) : 0).'%'; ?></td>
          <td> / </td>
          <td><?php echo ($Franchisees_Total_foundation_mandarin>0 ?  round(($Franchisees_QF2_foundation_mandarin/$Franchisees_Total_foundation_mandarin)*100, 2) : 0); ?>%</td>
          <td><?php echo ($Franchisees_Total_foundation_mandarin>0 ?  round(($Franchisees_QF3_foundation_mandarin/$Franchisees_Total_foundation_mandarin)*100, 2) : 0); ?>%</td>
          <td><?php echo ($Franchisees_Total_foundation_mandarin>0 ?  round(($Franchisees_Total_foundation_mandarin/$Franchisees_Total_foundation_mandarin)*100, 2) : 0); ?>%</td>
          <td><?php echo ($Franchisees_Total_foundation_int_english>0 ?  round(($Franchisees_QF1_foundation_int_english/$Franchisees_Total_foundation_int_english)*100, 2) : 0); ?>%</td>
          <td><?php echo ($Franchisees_Total_foundation_int_english>0 ?  round(($Franchisees_QF2_foundation_int_english/$Franchisees_Total_foundation_int_english)*100, 2) : 0); ?>%</td>
          <td><?php echo ($Franchisees_Total_foundation_int_english>0 ?  round(($Franchisees_QF3_foundation_int_english/$Franchisees_Total_foundation_int_english)*100, 2) : 0); ?>%</td>
          <td><?php echo $Franchisees_Total_A = ($Franchisees_Total_foundation_int_english>0 ?  round(($Franchisees_Total_foundation_int_english/$Franchisees_Total_foundation_int_english)*100, 2) : 0); ?>%</td>
          <td><?php $Total_student_percen_Franchisees = ($Franchisees_total_from_BIP_only>0 ?  round(($Franchisees_Total_Stud_No/$Franchisees_total_from_BIP_only)*100, 2) : 0);
           echo ($Total_student_percen_Franchisees>0 ? round(($Franchisees_BIP_int_english/$Total_student_percen_Franchisees), 2) : 00); ?>%</td>
          <td><?php echo ($Franchisees_Total_foundation_int_mandarin>0 ?  round(($Franchisees_QF1_foundation_int_mandarin/$Franchisees_Total_foundation_int_mandarin)*100, 2) : 0); ?>%</td>
          <td><?php echo ($Franchisees_Total_foundation_int_mandarin>0 ?  round(($Franchisees_QF2_foundation_int_mandarin/$Franchisees_Total_foundation_int_mandarin)*100, 2) : 0); ?>%</td>
          <td><?php echo ($Franchisees_Total_foundation_int_mandarin>0 ?  round(($Franchisees_QF3_foundation_int_mandarin/$Franchisees_Total_foundation_int_mandarin)*100, 2) : 0); ?>%</td>
          <td><?php echo $Franchisees_Total_C = ($Franchisees_Total_foundation_int_mandarin>0 ?  round(($Franchisees_Total_foundation_int_mandarin/$Franchisees_Total_foundation_int_mandarin)*100, 2) : 0); ?>%</td>
          <td><?php echo ($Total_student_percen_Franchisees>0 ? round(($Franchisees_BIP_int_mandarin/$Total_student_percen_Franchisees), 2) : 00); ?>%</td>
          <td><?php echo ($Franchisees_Total_foundation_int_art>0 ?  round(($Franchisees_QF1_foundation_int_art/$Franchisees_Total_foundation_int_art)*100, 2) : 0); ?>%</td>
          <td><?php echo ($Franchisees_Total_foundation_int_art>0 ?  round(($Franchisees_QF2_foundation_int_art/$Franchisees_Total_foundation_int_art)*100, 2) : 0); ?>%</td>
          <td><?php echo ($Franchisees_Total_foundation_int_art>0 ?  round(($Franchisees_QF3_foundation_int_art/$Franchisees_Total_foundation_int_art)*100, 2) : 0); ?>%</td>
          <td><?php echo $Franchisees_Total_E = ($Franchisees_Total_foundation_int_art>0 ?  round(($Franchisees_Total_foundation_int_art/$Franchisees_Total_foundation_int_art)*100, 2) : 0); ?>%</td>
          <td><?php echo ($Total_student_percen_Franchisees>0 ? round(($Franchisees_BIP_int_art/$Total_student_percen_Franchisees), 2) : 00); ?>%</td>
          <td><?php echo ($Franchisees_Total_foundation_iq_math>0 ?  round(($Franchisees_QF1_foundation_iq_math/$Franchisees_Total_foundation_iq_math)*100, 2) : 0); ?>%</td>
          <td><?php echo ($Franchisees_Total_foundation_iq_math>0 ?  round(($Franchisees_QF2_foundation_iq_math/$Franchisees_Total_foundation_iq_math)*100, 2) : 0); ?>%</td>
          <td><?php echo ($Franchisees_Total_foundation_iq_math>0 ?  round(($Franchisees_QF3_foundation_iq_math/$Franchisees_Total_foundation_iq_math)*100, 2) : 0); ?>%</td>
          <td><?php echo $Franchisees_Total_G = ($Franchisees_Total_foundation_iq_math>0 ?  round(($Franchisees_Total_foundation_iq_math/$Franchisees_Total_foundation_iq_math)*100, 2) : 0); ?>%</td>
          <td><?php echo ($Total_student_percen_Franchisees>0 ? round(($Franchisees_BIP_iq_math/$Total_student_percen_Franchisees), 2) : 00); ?>%</td>
          <td><?php echo $Grand_total_Franchisees = $Franchisees_Total_A + $Franchisees_Total_C + $Franchisees_Total_E + $Franchisees_Total_G; ?>%</td>
          <td><?php echo ($Total_student_percen_Franchisees>0 ? round(($Grand_total_Franchisees/$Total_student_percen_Franchisees)*100, 2) : 00); ?>%</td> <!--Total Beamind % (I)-->
          <td><?php echo $Total_student_percen_Franchisees; ?>%</td> <!--Total Stud. No. QF1-QF3 (J)-->
          <td></td> <!--Location (State)-->
        </tr>
         <!--end Franchisees Percentage-->

        <tr class="founda uk-text-bold">
          <td colspan="3">Grand Total: </td> 
          <td><?php echo $Grand_EDP;  ?></td>
          <td><?php echo $Grand_QF1; ?></td>
          <td><?php echo $Grand_QF2; ?></td>
          <td><?php echo $Grand_QF3; ?></td>
          <td><?php 
          echo $Grand_Total; 
          $Total_Stud_No_QF1_QF3 = $Grand_QF1+$Grand_QF2+$Grand_QF3;
          ?></td>
          <td><?php echo $Grand_Total_afternoon_programme; ?></td>
          <td><?php echo $Grand_QF2_foundation_mandarin; ?></td>
          <td><?php echo $Grand_QF3_foundation_mandarin; ?></td>
          <td><?php echo $Grand_Total_foundation_mandarin; ?></td>
          <td><?php echo $Grand_QF1_foundation_int_english; ?></td>
          <td><?php echo $Grand_QF2_foundation_int_english; ?></td>
          <td><?php echo $Grand_QF3_foundation_int_english; ?></td>
          <td><?php echo $Grand_Total_foundation_int_english; ?></td>
          <td><?php echo ($Total_Stud_No_QF1_QF3>0 ? round(($Grand_Total_foundation_int_english/$Total_Stud_No_QF1_QF3)*100, 2).'%' : 00); ?></td>
          <td><?php echo $Grand_QF1_foundation_int_mandarin; ?></td>
          <td><?php echo $Grand_QF2_foundation_int_mandarin; ?></td>
          <td><?php echo $Grand_QF3_foundation_int_mandarin; ?></td>
          <td><?php echo $Grand_Total_foundation_int_mandarin; ?></td>
          <td><?php echo ($Total_Stud_No_QF1_QF3>0 ? round(($Grand_Total_foundation_int_mandarin/$Total_Stud_No_QF1_QF3)*100, 2).'%' : 00); ?></td>
          <td><?php echo $Grand_QF1_foundation_int_art; ?></td>
          <td><?php echo $Grand_QF2_foundation_int_art; ?></td>
          <td><?php echo $Grand_QF3_foundation_int_art; ?></td>
          <td><?php echo $Grand_Total_foundation_int_art; ?></td>
          <td><?php echo ($Total_Stud_No_QF1_QF3>0 ? round(($Grand_Total_foundation_int_art/$Total_Stud_No_QF1_QF3)*100, 2).'%' : 00); ?></td>
          <td><?php echo $Grand_QF1_foundation_iq_math; ?></td>
          <td><?php echo $Grand_QF2_foundation_iq_math; ?></td>
          <td><?php echo $Grand_QF3_foundation_iq_math; ?></td>
          <td><?php echo $Grand_Total_foundation_iq_math; ?></td>
          <td><?php echo ($Total_Stud_No_QF1_QF3>0 ? round(($Grand_Total_foundation_iq_math/$Total_Stud_No_QF1_QF3)*100, 2).'%' : 00); ?></td>
          <td><?php echo $grand_total_from_BIP_only = $Grand_Total_foundation_int_english + $Grand_Total_foundation_int_mandarin + $Grand_Total_foundation_int_art + $Grand_Total_foundation_iq_math; ?></td>
          <td><?php echo $COMPANY_OWNED_Total_Beamind + $JOINT_VENTURE_Total_Beamind + $Franchisees_Total_Beamind; ?>%</td> <!--Total Beamind % (I)-->
          <td><?php echo $Total_Stud_No_QF1_QF3; ?></td> <!--Total Stud. No. QF1-QF3 (J)-->
          <td></td> <!--Location (State)-->
        </tr>
      </table>

   <?php
} else {
   echo "Please enter a centre";
}
   ?>
   </div>
 <style>
 .founda{
	 text-align: center;
 }
 .rtable td{
    border:1px solid #0000001a;
 }
 .rtable{
   font-size: 10px;
 }
 @media print {
   .rtable{
    font-size: 5px;//customize your table so they can fit
  }
}
 </style>
   <script>
      $(window).load(function() {
         var method = '<?php echo $method ?>';
         if (method == "print") {
            window.print();
            $("#note1").show();
         }
      });
   </script>