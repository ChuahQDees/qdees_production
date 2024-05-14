<?php
    session_start();
    include_once("../mysql.php");

    $fee_structure = isset($_REQUEST['fee_structure']) ? $_REQUEST['fee_structure'] : '';
    $student_entry_level = isset($_REQUEST['student_entry_level']) ? $_REQUEST['student_entry_level'] : '';
    $subject = isset($_REQUEST['subject']) ? $_REQUEST['subject'] : '';
    $student_id = isset($_REQUEST['student_id']) ? $_REQUEST['student_id'] : array();

   // $student_id = implode(', ', $student_id);
	
    $sql="SELECT `id`, `name`, `student_code`, `start_date_at_centre` from student WHERE `id` IN (".$student_id.")";
         
    $result_student=mysqli_query($connection, $sql);
    $num_row=mysqli_num_rows($result_student);
?>
<table class="uk-table" >
   <tr class="uk-text-bold">
      <td>#</td>
      <td>Student Code</td>
      <td>Name</td>
      <td>Start Date At Centre</td>
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
            <td><?php echo date('d-m-Y',strtotime($row["start_date_at_centre"])); ?></td> 
        </tr>
<?php
        $i++;
    }
?>
</table>
