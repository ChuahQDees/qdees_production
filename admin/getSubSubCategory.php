<?php
include_once("../mysql.php");

$subcategory=$_POST["subcategory"];
$subsubcategory=$_POST["subsubcategory"];
$s=$_POST["s"];

$sql="SELECT * from codes where `parent`='$subcategory' and module='CATEGORY' order by code";
$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);

if ($s=="1") {
   $nameid="ssub_sub_category";
} else {
   $nameid="sub_sub_category";
}

if ($num_row>0) {
?>
   <select name="<?php echo $nameid?>" id="<?php echo $nameid?>">
      <option value="">Level</option>
<?php
   while ($row=mysqli_fetch_assoc($result)) {
?>
      <option value="<?php echo $row['code']?>" <?php if ($row['code']==$subsubcategory) {echo "selected";}?>><?php echo $row["code"]?></option>
<?php
   }
?>
   </select>
<?php
} else {
?>
   <select name="<?php echo $nameid?>" id="<?php echo $nameid?>">
      <option value="">Select a Subject First</option>
   </select>
<?php
}
?>