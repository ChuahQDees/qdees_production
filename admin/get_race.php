<?php
include_once("../mysql.php");

$country=$_POST["country"];

$sql="SELECT * from codes where module='RACE' and country='$country' order by code";
$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);

if ($num_row>0) {
?>
   <select name="race" id="race" class="uk-width-1-1">
      <option value="">Select</option>
<?php
while ($row=mysqli_fetch_assoc($result)) {
?>
      <option value="<?php echo $row['code']?>"><?php echo $row['code']?></option>
<?php
}
?>
      <option value="Others">Others</option>
   </select>
<?php
} else {
   echo "";
}
?>