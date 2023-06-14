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
      <h2 class="uk-text-center myheader-text-color myheader-text-style">CENTRE SUBJECT ENROLLMENT REPORT</h2>
   </div>
   <div class="nice-form">
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
                      // Print_r($sql);
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
      <table class="uk-table">
         <tr class="uk-text-bold">
            <td style="width: 25%;"><center>PRODUCT</center></td>
            <?php if($course_subject == "EDP" || $course_subject == ""){ ?>
            <td><center>EDP</center></td>
            <?php } if($course_subject == "QF1" || $course_subject == ""){ ?>
			<td><center>QF1</center></td>
         <?php } if($course_subject == "QF2" || $course_subject == ""){ ?>
			<td><center>QF2</center></td>
         <?php } if($course_subject == "QF3" || $course_subject == ""){ ?>
			<td><center>QF3</center></td>
         <?php } if($course_subject == ""){ ?>
			<td><center>TOTAL</center></td>
         <?php } ?>
         </tr>
         <?php

         // $month = date("M Y");
         // $sql = "SELECT * from centre";
         // $sql .= ($centre_code != "ALL") ? " where centre_code='$centre_code'" : "";
         // $sql .= " order by centre_code";
         // $centre_result = mysqli_query($connection, $sql);
         // while ($centre_row = mysqli_fetch_assoc($centre_result)) {
            // $sql = "SELECT count(*) as no_of_students, s.centre_code, cen.kindergarten_name, c.course_name, c.subject from course c, student s, centre cen, allocation a LEFT JOIN `group` g on a.group_id=g.id where s.student_status= 'A' and a.student_id=s.id and a.course_id=c.id and a.deleted=0 and s.centre_code='" . $centre_row["centre_code"] . "'and s.centre_code=cen.centre_code";
            // if (isset($selected_month) && $selected_month != '') {
               // $month = substr($selected_month, 4, 2);
               // $year = substr($selected_month, 0, -2);
               // if ($month == 13) {
                  // $sql .= " and year(g.start_date) = '$year'";
               // } else {
                  // $convertedDate = convertDateFormat($selected_month);
                  // $sql .= " and '$convertedDate' between start_date and end_date";
               // }
            // } else {
               // $sql .= " and year(g.start_date) = '$year'";
            // }
            // if (isset($course_subject) && $course_subject != 'ALL') {
               
               // $sql .= " and c.subject like '$course_subject%'";
            // }

            // $sql .= " group by a.course_id";
            
            // $result = mysqli_query($connection, $sql);

            // while ($row = mysqli_fetch_assoc($result)) {
               // $count++;
               $year=$_SESSION['Year'];
               //$centre_code=$_SESSION["CentreCode"];
           // $sql="select l.student_entry_level, s.level_count from (SELECT DISTINCT student_entry_level from student) l left join ( SELECT student_entry_level, count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and student_status = 'A' and deleted='0' group by ps.student_entry_level, s.id) ab group by student_entry_level ) s on s.student_entry_level = l.student_entry_level where l.student_entry_level !=''"; 
           
         ?>
			<tr class="founda">
				<td>Foundation</td>
            <?php if($course_subject == "EDP" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
            if($centre_code!="ALL"){
               $sql.=" and s.centre_code='$centre_code' ";
            }
         
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end <= '$to_date')) and s.deleted='0' and ps.student_entry_level ='EDP' group by ps.student_entry_level, s.id) ab";
            $result=mysqli_query($connection, $sql);
            //echo $sql;
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span style="cursor:pointer;color:blue;" onclick="dlgTotalStudentList('<?php echo $from_date; ?>','<?php echo $to_date; ?>','<?php echo $centre_code; ?>','Foundation','EDP')" ><?php echo (empty($row["level_count"]) ? "0" : $row["level_count"]); ?></span>
                            </span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == "QF1" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
              if($centre_code!="ALL"){
                $sql.=" and s.centre_code='$centre_code' ";
             }
            
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end <= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF1' group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span style="cursor:pointer;color:blue;" onclick="dlgTotalStudentList('<?php echo $from_date; ?>','<?php echo $to_date; ?>','<?php echo $centre_code; ?>','Foundation','QF1')" ><?php echo (empty($row["level_count"]) ? "0" : $row["level_count"]); ?></span>
                            </span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == "QF2" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
              if($centre_code!="ALL"){
                $sql.=" and s.centre_code='$centre_code' ";
             }
         
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end <= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF2' group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span style="cursor:pointer;color:blue;" onclick="dlgTotalStudentList('<?php echo $from_date; ?>','<?php echo $to_date; ?>','<?php echo $centre_code; ?>','Foundation','QF2')" ><?php echo (empty($row["level_count"]) ? "0" : $row["level_count"]); ?></span>
                            </span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == "QF3" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
              if($centre_code!="ALL"){
                $sql.=" and s.centre_code='$centre_code' ";
             }
            
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end <= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF3' group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span style="cursor:pointer;color:blue;" onclick="dlgTotalStudentList('<?php echo $from_date; ?>','<?php echo $to_date; ?>','<?php echo $centre_code; ?>','Foundation','QF3')" ><?php echo (empty($row["level_count"]) ? "0" : $row["level_count"]); ?></span>
                            </span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
             
              if($centre_code!="ALL"){
                $sql.=" and s.centre_code='$centre_code' ";
             }
   
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end <= '$to_date')) and s.deleted='0' group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span style="cursor:pointer;color:blue;" onclick="dlgTotalStudentList('<?php echo $from_date; ?>','<?php echo $to_date; ?>','<?php echo $centre_code; ?>','Foundation')" ><?php echo (empty($row["level_count"]) ? "0" : $row["level_count"]); ?></span>
                            </span>
                        <?php
                     }
            ?>
            </td>
            <?php } ?>
		   </tr>
		   <tr class="founda">
				<td>Foundation Mandarin</td>
            <?php if($course_subject == "EDP" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
              if($centre_code!="ALL"){
                $sql.=" and s.centre_code='$centre_code' ";
             }
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end <= '$to_date')) and s.deleted='0' and ps.student_entry_level ='EDP' and fl.foundation_mandarin =1 group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span style="cursor:pointer;color:blue;" onclick="dlgTotalStudentList('<?php echo $from_date; ?>','<?php echo $to_date; ?>','<?php echo $centre_code; ?>','Foundation Mandarin','EDP')" ><?php echo (empty($row["level_count"]) ? "0" : $row["level_count"]); ?></span>
                            </span>
                            <p><?php echo $sql ?></p>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == "QF1" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
              if($centre_code!="ALL"){
                $sql.=" and s.centre_code='$centre_code' ";
             }
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end <= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF1' and fl.foundation_mandarin =1 group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span style="cursor:pointer;color:blue;" onclick="dlgTotalStudentList('<?php echo $from_date; ?>','<?php echo $to_date; ?>','<?php echo $centre_code; ?>','Foundation Mandarin','QF1')" ><?php echo (empty($row["level_count"]) ? "0" : $row["level_count"]); ?></span>
                            </span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == "QF2" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
              if($centre_code!="ALL"){
                $sql.=" and s.centre_code='$centre_code' ";
             }
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end <= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF2' and fl.foundation_mandarin =1 group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span style="cursor:pointer;color:blue;" onclick="dlgTotalStudentList('<?php echo $from_date; ?>','<?php echo $to_date; ?>','<?php echo $centre_code; ?>','Foundation Mandarin','QF2')" ><?php echo (empty($row["level_count"]) ? "0" : $row["level_count"]); ?></span>
                            </span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == "QF3" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
              if($centre_code!="ALL"){
                $sql.=" and s.centre_code='$centre_code' ";
             }
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end <= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF3' and fl.foundation_mandarin =1 group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span style="cursor:pointer;color:blue;" onclick="dlgTotalStudentList('<?php echo $from_date; ?>','<?php echo $to_date; ?>','<?php echo $centre_code; ?>','Foundation Mandarin','QF3')" ><?php echo (empty($row["level_count"]) ? "0" : $row["level_count"]); ?></span>
                            </span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
              if($centre_code!="ALL"){
                $sql.=" and s.centre_code='$centre_code' ";
             }
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end <= '$to_date')) and s.deleted='0' and fl.foundation_mandarin =1 group by ps.student_entry_level, s.id) ab";
            //echo $sql;
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span style="cursor:pointer;color:blue;" onclick="dlgTotalStudentList('<?php echo $from_date; ?>','<?php echo $to_date; ?>','<?php echo $centre_code; ?>','Foundation Mandarin')" ><?php echo (empty($row["level_count"]) ? "0" : $row["level_count"]); ?></span>
                            </span>
                        <?php
                     }
            ?>
            </td>
            <?php }  ?>
		   </tr>
		   <tr class="founda">
				<td>Enhanced Foundation International English</td>
            <?php if($course_subject == "EDP" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
              if($centre_code!="ALL"){
                $sql.=" and s.centre_code='$centre_code' ";
             }
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end <= '$to_date')) and s.deleted='0' and ps.student_entry_level ='EDP' and fl.foundation_int_english =1 group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span style="cursor:pointer;color:blue;" onclick="dlgTotalStudentList('<?php echo $from_date; ?>','<?php echo $to_date; ?>','<?php echo $centre_code; ?>','Enhanced Foundation International English','EDP')"><?php echo (empty($row["level_count"]) ? "0" : $row["level_count"]); ?></span>
                            </span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == "QF1" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
              if($centre_code!="ALL"){
                $sql.=" and s.centre_code='$centre_code' ";
             }
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end <= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF1' and fl.foundation_int_english =1 group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span style="cursor:pointer;color:blue;" onclick="dlgTotalStudentList('<?php echo $from_date; ?>','<?php echo $to_date; ?>','<?php echo $centre_code; ?>','Enhanced Foundation International English','QF1')"><?php echo (empty($row["level_count"]) ? "0" : $row["level_count"]); ?></span>
                            </span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == "QF2" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
              if($centre_code!="ALL"){
                $sql.=" and s.centre_code='$centre_code' ";
             }
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end <= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF2' and fl.foundation_int_english =1 group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span style="cursor:pointer;color:blue;" onclick="dlgTotalStudentList('<?php echo $from_date; ?>','<?php echo $to_date; ?>','<?php echo $centre_code; ?>','Enhanced Foundation International English','QF2')"><?php echo (empty($row["level_count"]) ? "0" : $row["level_count"]); ?></span>
                            </span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == "QF3" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
              if($centre_code!="ALL"){
                $sql.=" and s.centre_code='$centre_code' ";
             }
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end <= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF3' and fl.foundation_int_english =1 group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span style="cursor:pointer;color:blue;" onclick="dlgTotalStudentList('<?php echo $from_date; ?>','<?php echo $to_date; ?>','<?php echo $centre_code; ?>','Enhanced Foundation International English','QF3')"><?php echo (empty($row["level_count"]) ? "0" : $row["level_count"]); ?></span>
                            </span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
              if($centre_code!="ALL"){
                $sql.=" and s.centre_code='$centre_code' ";
             }
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end <= '$to_date')) and s.deleted='0' and fl.foundation_int_english =1 group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span style="cursor:pointer;color:blue;" onclick="dlgTotalStudentList('<?php echo $from_date; ?>','<?php echo $to_date; ?>','<?php echo $centre_code; ?>','Enhanced Foundation International English')"><?php echo (empty($row["level_count"]) ? "0" : $row["level_count"]); ?></span>
                            </span>
                        <?php
                     }
            ?>
            </td>
            <?php } ?>
		   </tr>
		   <tr class="founda">
				<td>Enhanced Foundation IQ Math</td>
            <?php if($course_subject == "EDP" || $course_subject == ""){ ?>
				<td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
              if($centre_code!="ALL"){
                $sql.=" and s.centre_code='$centre_code' ";
             }
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end <= '$to_date')) and s.deleted='0' and ps.student_entry_level ='EDP' and fl.foundation_iq_math =1 group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span style="cursor:pointer;color:blue;" onclick="dlgTotalStudentList('<?php echo $from_date; ?>','<?php echo $to_date; ?>','<?php echo $centre_code; ?>','Enhanced Foundation IQ Math','EDP')" ><?php echo (empty($row["level_count"]) ? "0" : $row["level_count"]); ?></span>
                            </span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == "QF1" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
              if($centre_code!="ALL"){
                $sql.=" and s.centre_code='$centre_code' ";
             }
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end <= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF1' and fl.foundation_iq_math =1 group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span style="cursor:pointer;color:blue;" onclick="dlgTotalStudentList('<?php echo $from_date; ?>','<?php echo $to_date; ?>','<?php echo $centre_code; ?>','Enhanced Foundation IQ Math','QF1')" ><?php echo (empty($row["level_count"]) ? "0" : $row["level_count"]); ?></span>
                            </span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == "QF2" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
              if($centre_code!="ALL"){
                $sql.=" and s.centre_code='$centre_code' ";
             }
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end <= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF2' and fl.foundation_iq_math =1 group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span style="cursor:pointer;color:blue;" onclick="dlgTotalStudentList('<?php echo $from_date; ?>','<?php echo $to_date; ?>','<?php echo $centre_code; ?>','Enhanced Foundation IQ Math','QF2')" ><?php echo (empty($row["level_count"]) ? "0" : $row["level_count"]); ?></span>
                            </span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == "QF3" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
              if($centre_code!="ALL"){
                $sql.=" and s.centre_code='$centre_code' ";
             }
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end <= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF3' and fl.foundation_iq_math =1 group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span style="cursor:pointer;color:blue;" onclick="dlgTotalStudentList('<?php echo $from_date; ?>','<?php echo $to_date; ?>','<?php echo $centre_code; ?>','Enhanced Foundation IQ Math','QF3')" ><?php echo (empty($row["level_count"]) ? "0" : $row["level_count"]); ?></span>
                            </span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
              if($centre_code!="ALL"){
                $sql.=" and s.centre_code='$centre_code' ";
             }
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end <= '$to_date')) and s.deleted='0' and fl.foundation_iq_math =1 group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span style="cursor:pointer;color:blue;" onclick="dlgTotalStudentList('<?php echo $from_date; ?>','<?php echo $to_date; ?>','<?php echo $centre_code; ?>','Enhanced Foundation IQ Math')" ><?php echo (empty($row["level_count"]) ? "0" : $row["level_count"]); ?></span>
                            </span>
                        <?php
                     }
            ?>
            </td>
            <?php } ?>
		   </tr>
		   <tr class="founda">
				<td>Enhanced Foundation International Art</td>
            <?php if($course_subject == "EDP" || $course_subject == ""){ ?>
				<td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
              if($centre_code!="ALL"){
                $sql.=" and s.centre_code='$centre_code' ";
             }
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end <= '$to_date')) and s.deleted='0' and ps.student_entry_level ='EDP' and fl.foundation_int_art =1 group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span style="cursor:pointer;color:blue;" onclick="dlgTotalStudentList('<?php echo $from_date; ?>','<?php echo $to_date; ?>','<?php echo $centre_code; ?>','Enhanced Foundation International Art','EDP')" ><?php echo (empty($row["level_count"]) ? "0" : $row["level_count"]); ?></span>
                            </span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == "QF1" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
              if($centre_code!="ALL"){
                $sql.=" and s.centre_code='$centre_code' ";
             }
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end <= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF1' and fl.foundation_int_art =1 group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span style="cursor:pointer;color:blue;" onclick="dlgTotalStudentList('<?php echo $from_date; ?>','<?php echo $to_date; ?>','<?php echo $centre_code; ?>','Enhanced Foundation International Art','QF1')" ><?php echo (empty($row["level_count"]) ? "0" : $row["level_count"]); ?></span>
                            </span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == "QF2" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
              if($centre_code!="ALL"){
                $sql.=" and s.centre_code='$centre_code' ";
             }
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end <= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF2' and fl.foundation_int_art =1 group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span style="cursor:pointer;color:blue;" onclick="dlgTotalStudentList('<?php echo $from_date; ?>','<?php echo $to_date; ?>','<?php echo $centre_code; ?>','Enhanced Foundation International Art','QF2')" ><?php echo (empty($row["level_count"]) ? "0" : $row["level_count"]); ?></span>
                            </span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == "QF3" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
              if($centre_code!="ALL"){
                $sql.=" and s.centre_code='$centre_code' ";
             }
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end <= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF3' and fl.foundation_int_art =1 group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span style="cursor:pointer;color:blue;" onclick="dlgTotalStudentList('<?php echo $from_date; ?>','<?php echo $to_date; ?>','<?php echo $centre_code; ?>','Enhanced Foundation International Art','QF3')" ><?php echo (empty($row["level_count"]) ? "0" : $row["level_count"]); ?></span>
                            </span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
              if($centre_code!="ALL"){
                $sql.=" and s.centre_code='$centre_code' ";
             }
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end <= '$to_date')) and s.deleted='0' and fl.foundation_int_art =1 group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span style="cursor:pointer;color:blue;" onclick="dlgTotalStudentList('<?php echo $from_date; ?>','<?php echo $to_date; ?>','<?php echo $centre_code; ?>','Enhanced Foundation International Art')" ><?php echo (empty($row["level_count"]) ? "0" : $row["level_count"]); ?></span>
                            </span>
                        <?php
                     }
            ?>
            </td>
            <?php } ?>
		   </tr>
		   <tr class="founda">
				<td>Enhanced Foundation Mandarin</td>
            <?php if($course_subject == "EDP" || $course_subject == ""){ ?>
				<td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
              if($centre_code!="ALL"){
                $sql.=" and s.centre_code='$centre_code' ";
             }
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end <= '$to_date')) and s.deleted='0' and ps.student_entry_level ='EDP' and fl.foundation_int_mandarin =1 group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span style="cursor:pointer;color:blue;" onclick="dlgTotalStudentList('<?php echo $from_date; ?>','<?php echo $to_date; ?>','<?php echo $centre_code; ?>','Enhanced Foundation Mandarin','EDP')" ><?php echo (empty($row["level_count"]) ? "0" : $row["level_count"]); ?></span>
                            </span>
                            <p><?php echo $sql ?></p>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == "QF1" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
              if($centre_code!="ALL"){
                $sql.=" and s.centre_code='$centre_code' ";
             }
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end <= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF1' and fl.foundation_int_mandarin =1 group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span style="cursor:pointer;color:blue;" onclick="dlgTotalStudentList('<?php echo $from_date; ?>','<?php echo $to_date; ?>','<?php echo $centre_code; ?>','Enhanced Foundation Mandarin','QF1')" ><?php echo (empty($row["level_count"]) ? "0" : $row["level_count"]); ?></span>
                            </span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == "QF2" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
              if($centre_code!="ALL"){
                $sql.=" and s.centre_code='$centre_code' ";
             }
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end <= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF2' and fl.foundation_int_mandarin =1 group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span style="cursor:pointer;color:blue;" onclick="dlgTotalStudentList('<?php echo $from_date; ?>','<?php echo $to_date; ?>','<?php echo $centre_code; ?>','Enhanced Foundation Mandarin','QF2')" ><?php echo (empty($row["level_count"]) ? "0" : $row["level_count"]); ?></span>
                            </span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == "QF3" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
              if($centre_code!="ALL"){
                $sql.=" and s.centre_code='$centre_code' ";
             }
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end <= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF3' and fl.foundation_int_mandarin =1 group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span style="cursor:pointer;color:blue;" onclick="dlgTotalStudentList('<?php echo $from_date; ?>','<?php echo $to_date; ?>','<?php echo $centre_code; ?>','Enhanced Foundation Mandarin','QF3')" ><?php echo (empty($row["level_count"]) ? "0" : $row["level_count"]); ?></span>
                            </span>
                        <?php
                     }
            ?>
            </td>
            <?php } if( $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
              if($centre_code!="ALL"){
                $sql.=" and s.centre_code='$centre_code' ";
             }
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end <= '$to_date')) and s.deleted='0' and fl.foundation_int_mandarin =1 group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span style="cursor:pointer;color:blue;" onclick="dlgTotalStudentList('<?php echo $from_date; ?>','<?php echo $to_date; ?>','<?php echo $centre_code; ?>','Enhanced Foundation Mandarin')" ><?php echo (empty($row["level_count"]) ? "0" : $row["level_count"]); ?></span>
                            </span>
                        <?php
                     }
            ?>
            </td>
            <?php } ?>
		   </tr>
		   <tr class="founda">
				<td>Afternoon Programme</td>
            <?php if($course_subject == "EDP" || $course_subject == ""){ ?>
				<td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
              if($centre_code!="ALL"){
                $sql.=" and s.centre_code='$centre_code' ";
             }
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end <= '$to_date')) and s.deleted='0' and ps.student_entry_level ='EDP' and fl.afternoon_programme =1 group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span style="cursor:pointer;color:blue;" onclick="dlgTotalStudentList('<?php echo $from_date; ?>','<?php echo $to_date; ?>','<?php echo $centre_code; ?>','Afternoon Programme','EDP')" ><?php echo (empty($row["level_count"]) ? "0" : $row["level_count"]); ?></span>
                            </span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == "QF1" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
              if($centre_code!="ALL"){
                $sql.=" and s.centre_code='$centre_code' ";
             }
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end <= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF1' and fl.afternoon_programme =1 group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span style="cursor:pointer;color:blue;" onclick="dlgTotalStudentList('<?php echo $from_date; ?>','<?php echo $to_date; ?>','<?php echo $centre_code; ?>','Afternoon Programme','QF1')" ><?php echo (empty($row["level_count"]) ? "0" : $row["level_count"]); ?></span>
                            </span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == "QF2" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
              if($centre_code!="ALL"){
                $sql.=" and s.centre_code='$centre_code' ";
             }
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end <= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF2' and fl.afternoon_programme =1 group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span style="cursor:pointer;color:blue;" onclick="dlgTotalStudentList('<?php echo $from_date; ?>','<?php echo $to_date; ?>','<?php echo $centre_code; ?>','Afternoon Programme','QF2')" ><?php echo (empty($row["level_count"]) ? "0" : $row["level_count"]); ?></span>
                            </span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == "QF3" || $course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
              if($centre_code!="ALL"){
                $sql.=" and s.centre_code='$centre_code' ";
             }
            $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end <= '$to_date')) and s.deleted='0' and ps.student_entry_level ='QF3' and fl.afternoon_programme =1 group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
				<span style="cursor:pointer;color:blue;" onclick="dlgTotalStudentList('<?php echo $from_date; ?>','<?php echo $to_date; ?>','<?php echo $centre_code; ?>','Afternoon Programme','QF3')" ><?php echo (empty($row["level_count"]) ? "0" : $row["level_count"]); ?></span>
                            </span>
                        <?php
                     }
            ?>
            </td>
            <?php } if($course_subject == ""){ ?>
            <td> <span class="gd_sb11">
            <?php 
             $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '".$year_start_date."' AND fl.programme_date <= '".$year_end_date."') and ps.student_entry_level != '' and s.student_status = 'A' ";
              if($centre_code!="ALL"){
                $sql.=" and s.centre_code='$centre_code' ";
             }
             $sql.=" and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end <= '$to_date')) and s.deleted='0' and fl.afternoon_programme =1 group by ps.student_entry_level, s.id) ab";
				$result=mysqli_query($connection, $sql);
				$num_row=mysqli_num_rows($result);
            while ($row=mysqli_fetch_assoc($result)) {
                ?>    
                        <span style="cursor:pointer;color:blue;" onclick="dlgTotalStudentList('<?php echo $from_date; ?>','<?php echo $to_date; ?>','<?php echo $centre_code; ?>','Afternoon Programme')" ><?php echo (empty($row["level_count"]) ? "0" : $row["level_count"]); ?></span>
                            </span>
                        <?php
                     }
            ?>
            </td>
            <?php } ?>
		   </tr>
         <?php
            // }
         // }
         ?>
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
 
 </style>
   <script>

      function dlgTotalStudentList(from_date,to_date,centre_code,project,student_entry_level = '') {
         $.ajax({
            url : "admin/dlgTotalStudentList.php",
            type : "POST",
            data : "from_date="+from_date+"&to_date="+to_date+"&centre_code="+centre_code+"&project="+project+"&student_entry_level="+student_entry_level,
            dataType : "text",
            beforeSend : function(http) {
            },
            async: false,
            success : function(response, status, http) {
               $("#student-dialog").html("");
               $("#student-dialog").html(response);
            $("#student-dialog").dialog({
                     dialogClass:"no-close",
                     title:"Students Detail",
                     modal:true,
                     height:'auto',
                     width:'60%',
                  });
            },
            error : function(http, status, error) {
               UIkit.notify("Error:"+error);
            }
         });
      }

      $(window).load(function() {
         var method = '<?php echo $method ?>';
         if (method == "print") {
            window.print();
            $("#note1").show();
         }
      });
   </script>

