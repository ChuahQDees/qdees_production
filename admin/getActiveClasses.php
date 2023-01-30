<?php
session_start();
$year=$_SESSION['Year'];
include_once("../mysql.php");
$centre_code=$_SESSION["CentreCode"];
//$sql="SELECT count(s.*) as no_of_student, c.course_name, a.class_id, c.id, a.deleted from allocation a, course c, student s
//where a.course_id=c.id and a.student_id=s.id and s.student_status='A' and a.year='$year' and s.deleted=0 and c.deleted=0
//and a.deleted=0 group by course_id, class_id";

$sql="SELECT count(*) as no_of_student, c.course_name, a.class_id, c.id, a.deleted from allocation a, course c, student s
where a.course_id=c.id and a.student_id=s.id and s.student_status='A' and a.`year`='$year' group by course_id, class_id";

$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);
?>

<table class="uk-table">
	<tr class="uk-text-bold">
		<td>Programme</td>
		<td>Class</td>
		<td>No of Students</td>
	</tr>
<?php
if ($num_row>0) {
	while ($row=mysqli_fetch_assoc($result)) {
	    $course_id = $row['id'];
?>
	<tr onclick="showCourseData('<?php echo $row["course_name"] ?>', '<?php echo $row['deleted']; ?>');">
		<td><?php echo $row["course_name"]?></td>
		<td><?php echo $row["class_id"]?></td>
		<td><?php echo $row["no_of_student"]?></td>
	</tr>
<?php
   }
} else {
?>
   <tr>
   	  <td colspan="3">No record found</td>
   </tr>
<?php
}
?>
</table>