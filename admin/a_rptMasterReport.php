
<style>

   @page {
    size: auto;
}

</style>


<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //$country
}

if ($method=="print") {
   include_once("../uikit1.php");
}

function getMasterNameInfo($id, $field_name) {
   global $connection;

   $sql="SELECT * from master_franchisee_name where id='$id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return $row[$field_name];
}

function getMasterCompanyInfo($id, $field_name) {
   global $connection;

   $sql="SELECT * from master_franchisee_company where id='$id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return $row[$field_name];
}

if ($country!="") {
?>


<div class="uk-width-1-1 myheader text-center mt-5">
   <h2 class="uk-text-center myheader-text-color myheader-text-style">Master Report</h2>
</div>

<div class="uk-form master_form" style="background-color: white;">
<table class="uk-table">
   <thead>
      <tr>
        
         <th>Code No</th>
         <th>Country</th>
         <th>Territory</th>
         <th>Year of Incorporation</th>
         <th>Year of Renewal</th>
         <th>Next Renewal Date</th>
         <th>Sign With</th>
         <th>Types of Agreement</th>
         <th>Franchisee Name</th>
         <th>Passport No.</th>
         <th>Franchisee Company Name</th>
         <th>Company No.</th>
      </tr>
   </thead>
   <tbody>
<?php
if ($country == 'ALL') {
  $sql="SELECT * from master";
} else {
  $sql="SELECT * from master where country='$country'";
}

$result=mysqli_query($connection, $sql);
while ($row=mysqli_fetch_assoc($result)) {
?>
      <tr>
        
         <td><?php echo $row["master_code"]?></td>
         <td><?php echo $row["country"]?></td>
         <td><?php echo $row["state"]?></td>
         <td><?php echo $row["year_of_commencement"]?></td>
         <td><?php echo $row["year_of_renewal"]?></td>
         <td><?php echo $row["expiry_date"]?></td>
         <td><?php echo $row["franchisor_company_name"]?></td>
         <td><?php echo $row[""]?></td>
         <td><?php echo getMasterNameInfo($row["master_franchisee_name_id"], "franchisee_name")?></td>
         <td><?php echo getMasterNameInfo($row["master_franchisee_name_id"], "franchisee_passport")?></td>
         <td><?php echo getMasterCompanyInfo($row["master_franchisee_company_id"], "franchisee_company_name")?></td>
         <td><?php echo getMasterCompanyInfo($row["master_franchisee_company_id"], "franchisee_company_no")?></td>
      </tr>
<?php
}
?>
   </tbody>
</table>
</div>
<?php
} else {
   echo "Please enter a country";
}
?>

<script>
$(document).ready(function () {
   var method='<?php echo $method?>';
   if (method=="print") {
      window.print();
   }
});
</script>