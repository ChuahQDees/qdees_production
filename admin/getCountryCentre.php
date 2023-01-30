<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);//country
}

$sql="SELECT * from centre where country='$country' order by centre_code";
$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);

if ($num_row>0) {
?>
<select name="centre_code" id="centre_code">
   <option value="">Select</option>
   <option value="ALL">ALL</option>
<?php
while ($row=mysqli_fetch_assoc($result)) {
?>
   <option value="<?php echo $row['centre_code']?>"><?php echo $row["centre_code"]." - ".$row["kindergarten_name"]?></option>
<?php
}
?>
</select>
<?php
} else {
?>
<select name="centre_code" id="centre_code">
   <option value="">Select</option>
</select>
<?php
}
?>