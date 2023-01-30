<?php
include_once("../mysql.php");

$country=$_POST["country"];

$sql="SELECT * from codes where module='STATE' and country='$country' order by code";
$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);

if ($num_row>0) {
?>
   <select name="state" id="state" class="uk-width-1-1">
      <option value="">Select State</option>
<?php
while ($row=mysqli_fetch_assoc($result)) {
?>
      <option value="<?php echo $row['code']?>"><?php echo $row['code']?></option>
<?php
}
?>
   </select>
<?php   
} else {
   echo "";
}
?>