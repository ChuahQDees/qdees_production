<?php
session_start();
include_once("../mysql.php");

$master_code=$_POST["master_code"];

?>

<select class="uk-width-1-1" name="master_franchisee_company_id" id="master_franchisee_company_id">
   <option value="">Select</option>
<?php
$sql="SELECT * from master_franchisee_company where master_code='".$master_code."'";
$result=mysqli_query($connection, $sql);
while ($row=mysqli_fetch_assoc($result)) {
?>
   <option value="<?php echo $row['id']?>"><?php echo $row["franchisee_company_name"]?></option>
<?php
}
?>
</select>
