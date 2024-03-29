<?php
session_start();
include_once("../mysql.php");

    $centre_code = isset($_REQUEST['centre_code']) ? $_REQUEST['centre_code'] : '';
    $from_date = isset($_REQUEST['from_date']) ? date('Y-m-d',strtotime($_REQUEST['from_date'])) : '';
    $to_date = isset($_REQUEST['to_date']) ? date('Y-m-d',strtotime($_REQUEST['to_date'])) : '';
    $project = isset($_REQUEST['project']) ? $_REQUEST['project'] : '';
    $year = $_SESSION['Year'];
	
	$student_entry_level = isset($_REQUEST['student_entry_level']) ? $_REQUEST['student_entry_level'] : '';
	
    $sql="SELECT s.* from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.student_status = 'A' ";
      
	if($student_entry_level != '' && ($student_entry_level != 'Junior' && $student_entry_level != 'Senior'))
	{
		$sql .= " and ps.student_entry_level = '".$student_entry_level."'";
	}
	else if($student_entry_level == 'Junior' || $student_entry_level == 'Senior')
    {
        if ($student_entry_level == 'Junior'){
            $sql .= " and (ps.student_entry_level = 'EDP' OR ps.student_entry_level = 'QF1')";
        }else{
            $sql .= " and (ps.student_entry_level = 'QF2' OR ps.student_entry_level = 'QF3')";
        }
    }
    else
    {
		$sql .= " and ps.student_entry_level != ''";
	}
	  
    if($centre_code!="ALL"){
        $sql.=" and s.centre_code='$centre_code' ";
    }

    $sql .= " and ((fl.programme_date >= '$from_date' and fl.programme_date <= '$to_date') or (fl.programme_date_end >= '$from_date' and fl.programme_date_end <= '$to_date')) and s.deleted='0'";

    if($project == 'Foundation')
    {
        $sql.=" group by ps.student_entry_level, s.id";
    }
    else if($project == 'Foundation Mandarin')
    {
        $sql.=" and fl.foundation_mandarin =1 group by ps.student_entry_level, s.id";
    } 
    else if($project == 'Enhanced Foundation International English')
    {
        $sql.=" and fl.foundation_int_english =1 group by ps.student_entry_level, s.id";
    } 
    else if($project == 'Enhanced Foundation IQ Math')
    {
        $sql.=" and fl.foundation_iq_math =1 group by ps.student_entry_level, s.id";
    } 
    else if($project == 'Enhanced Foundation International Art')
    {
        $sql.=" and fl.foundation_int_art =1 group by ps.student_entry_level, s.id";
    }    
    else if($project == 'Enhanced Foundation Mandarin')
    {
        $sql.=" and fl.foundation_int_mandarin =1 group by ps.student_entry_level, s.id";
    } 
    else if($project == 'Afternoon Programme')
    {
        $sql.=" and fl.afternoon_programme =1 group by ps.student_entry_level, s.id";
    }
    else if($project == 'AFBasicRobotics')
    {
        $sql.=" and fl.afternoon_programme =1 
        and f.basic_adjust < 1 
        and f.afternoon_robotic_adjust > 0 
        group by ps.student_entry_level, s.id";
    }
    else if($project == 'EFPlusRobotics')
    {
        $sql.=" and fl.robotic_plus=1 
        group by ps.student_entry_level, s.id";
    }

    
    $result_student=mysqli_query($connection, $sql);
    $num_row=mysqli_num_rows($result_student);


?>
<table class="uk-table" >
   <tr class="uk-text-bold">
      <td>#</td>
      <td>Student Code</td>
      <td>Name</td>
      <!-- <td>Gender</td> -->
     <!--  <td>Age</td> -->
   </tr>
<?php
    $i=1;
    while($row=mysqli_fetch_array($result_student))
    {
?>
        <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $row["student_code"]; ?></td>
            <td><?php echo $row["name"]; ?></td>
         <!--    <td><?php echo $row["gender"]; ?></td> -->
        <!--     <td>
                <?php
                    $age = date('Y',strtotime($year_start_date)) - date('Y',strtotime($row['dob'])); 
                    echo $age; 
                ?>
            </td> -->
        </tr>
<?php
        $i++;
    }
?>
</table>
<div class="uk-text-center" >
    <a class="uk-button uk-button-small uk-align-center" style="width:100px;" onclick="$('#student-dialog').dialog('close');">Cancel</a>
</div>