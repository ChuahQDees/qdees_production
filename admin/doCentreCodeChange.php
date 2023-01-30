<?php
include_once('../mysql.php');
foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);//$centre_code
}
?>

<select name="student_id" id="student_id">
   <option value="">Select</option>
<?php
$sql="SELECT * from student where centre_code='$centre_code' and student_status='A' and deleted=0 order by name";
$result=mysqli_query($connection, $sql);
while ($row=mysqli_fetch_assoc($result)) {
?>
   <option value="<?php echo $row['id']?>"><?php echo $row["name"]?></option>
<?php
}
?>
</select>