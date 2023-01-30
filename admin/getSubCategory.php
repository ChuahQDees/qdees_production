<?php
include_once("../mysql.php");

$category=$_POST["category"];
$subcategory=$_POST["subcategory"];
$s=$_POST["s"];

$sql="SELECT * from codes where `parent`='$category' and module='CATEGORY'";
$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);

if ($s="1") {
   $nameid="ssub_category";
} else {
   $nameid="sub_category";
}

if ($num_row>0) {
?>
   <select name="<?php echo $nameid?>" id="<?php echo $nameid?>">
      <option value="">Subject</option>
<?php
   while ($row=mysqli_fetch_assoc($result)) {
?>
      <option value="<?php echo $row['code']?>" <?php if ($row['code']==$subcategory) {echo "selected";}?>><?php echo $row["code"]?></option>
<?php
   }
?>
   </select>
<?php
} else {
?>
   <select name="<?php echo $nameid?>" id="<?php echo $nameid?>">
      <option value="">Select a Company First</option>
   </select>
<?php
}
?>