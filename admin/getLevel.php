<?php
include_once("../mysql.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);
}

if ($level_type=="EDP") {
   $sql="SELECT * from course where course_name like '%EDP' order by course_name";
}

if ($level_type=="QF 1") {
   $sql="SELECT * from course where course_name like '%QF 1' order by course_name";
}

if ($level_type=="QF 2") {
   $sql="SELECT * from course where course_name like '%QF 2' order by course_name";
}

if ($level_type=="QF 3") {
   $sql="SELECT * from course where course_name like '%QF 3' order by course_name";
}

$result=mysqli_query($connection, $sql);
?>

<select name="scourse" id="scourse">
   <option value="">Select</option>
<?php
while ($row=mysqli_fetch_assoc($result)) {
?>
   <option value="<?php echo $row['id']?>"><?php echo $row['course_name']?></option>
<?php
}
?>
</select>