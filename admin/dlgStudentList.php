<?php
session_start();
include_once("../mysql.php");

$centre_code = $_SESSION['CentreCode'];
$year = $_SESSION['Year'];
$month = date('m');
$fee_id = mysqli_real_escape_string($connection, $_REQUEST['fee_id']);

$sql="SELECT s.* from student s, programme_selection ps, student_fee_list fl, fee_structure f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and $month BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) and sha1(f.id)='$fee_id' and  (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.centre_code='$centre_code' and s.student_status = 'A' and s.deleted='0'";

$result_student=mysqli_query($connection, $sql);

function displayStudentStatus($status){
    switch($status){
       case "A":
       return "Active";
     case "D":
       return "Deferred";
     case "G":
       return "Graduated";
     case "I":
       return "Dropout";
     case "S":
       return "Suspended";
     case "T":
       return "Transferred";
       default:
          return $status;
    }
 }

?>
<table class="uk-table">
   <tr class="uk-text-bold">
      <td>Student Code</td>
      <td>Name</td>
      <td>Gender</td>
      <td>Age</td>
      <td>Status</td>
   </tr>
<?php
    while($row=mysqli_fetch_array($result_student))
    {
?>
        <tr>
            <td><?php echo $row["student_code"]; ?></td>
            <td><?php echo $row["name"]; ?></td>
            <td><?php echo $row["gender"]; ?></td>
            <td>
                <?php
                    $age = date('Y',strtotime($year_start_date)) - date('Y',strtotime($row['dob'])); 
                    echo $age; 
                ?>
            </td>
            <td><?php echo displayStudentStatus($row["student_status"]); ?></td>
        </tr>
<?php
    }
?>
</table>
<div class="uk-text-right" >
    <a class="uk-button uk-button-small uk-align-right" onclick="$('#student-dialog').dialog('close');">Cancel</a>
</div>