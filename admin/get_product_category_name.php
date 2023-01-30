<?php
include_once("../mysql.php");

$company_code=$_POST['company_code'];

global $connection;


$sql="SELECT * from product_category where company_name= '$company_code'";
//echo $sql;
$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);

if ($num_row>0) {
?>
   
      <option value="">Select</option>
		<?php
		while ($row=mysqli_fetch_assoc($result)) {
		?>
			 <option value="<?php echo $row['id'] ?>"><?php echo $row['category_name'] ?></option>
		<?php
		}
		?>
   
<?php
} else {
   echo "";
}

?>