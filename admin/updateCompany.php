<?php
session_start();
include_once("../mysql.php");

$centre_code=$_POST["centre_code"];

?>

<select class="uk-width-1-1" name="centre_franchisee_company_id" id="centre_franchisee_company_id">
   <option value="">Select</option>
<?php
$sql="SELECT * from centre_franchisee_company where centre_code='".$centre_code."'";
$result=mysqli_query($connection, $sql);
while ($row=mysqli_fetch_assoc($result)) {
?>
   <option value="<?php echo $row['id']?>"><?php echo $row["franchisee_company_name"]?></option>
<?php
}
?>
</select>
