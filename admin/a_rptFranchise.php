<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //$centre_code; $country
}

function getFranchiseeNameInfo($id, $field_name) {
   global $connection;

   $sql="SELECT * from centre_franchisee_name where id='$id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return $row[$field_name];
}

function getFranchiseeCompanyInfo($id, $field_name) {
   global $connection;

   $sql="SELECT * from centre_franchisee_company where id='$id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return $row[$field_name];
}

function isMaster($master_code) {
   global $connection;

   $sql="SELECT * from master where master_code='$master_code'";
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);
   if ($num_row>0) {
      return true;
   } else {
      return false;
   }
}

function getCentreName($centre_code) {
   global $connection;

   $sql="SELECT kindergarten_name from centre where centre_code='$centre_code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      return $row["kindergarten_name"];
   } else {
      if ($centre_code=="ALL")  {
         return "All Centres";
      } else {
         return "";
      }
   }
}

?>

<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
  counter-reset: row-num;
}

table tbody tr{
   counter-increment: row-num;
}


table tbody tr td:first-child::before {
    content: counter(row-num) ".";
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

.pagination {
  display: inline-block;
  float:right;
}

.pagination a {
  color: black;
  float: left;
  padding: 8px 16px;
  text-decoration: none;
}

.pagination a.active {
  background-color: #888888c2;
  color: white;
}

.pagination a:hover:not(.active) {background-color: #ddd;}
</style>

<p><h2>Franchise Information</h2></p>
<div class="uk-overflow-container">
<table>
   <thead>
      <tr>
         <th>No.</th>
         <th>Code No</th>
         <th>State</th>
         <th>Approved Location</th>
         <th>Year of Incorporation</th>
         <th>Year of Renewal</th>
         <th>Next Renewal Date</th>
         <th>Sign With</th>
         <th>Company Name</th>
         <th>Kindergarten Name</th>
         <th>Franchisee Name</th>
         <th>Centre Name</th>
     </tr>
   </thead>
   <tbody>
<?php
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "ReportingView"))) {
   if (isMaster($_SESSION["CentreCode"])) {
      $sql="SELECT * from centre where upline='".$_SESSION["CentreCode"]."' order by centre_code";
   } else {
      if (strtoupper($_SESSION["UserName"])=="SUPER") {
         $sql="SELECT * from centre order by centre_code";
      } else {
         $sql="";
      }
   }
} else {
   if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) & (hasRightGroupXOR($_SESSION["UserName"], "ReportingView"))) {
      $sql="";
   }
}

if ($sql!="") {
   $result=mysqli_query($connection, $sql);
   while ($row=mysqli_fetch_assoc($result)) {
?>
      <tr>
         <td></td>
         <td><?php echo $row["centre_code"]?></td>
         <td><?php echo $row["state"]?></td>
         <td>
            <?php echo $row["address1"]?><br>
            <?php echo $row["address2"]?><br>
            <?php echo $row["address3"]?><br>
            <?php echo $row["address4"]?><br>
            <?php echo $row["address5"]?><br>
         </td>
         <td><?php echo date("d/m/Y", strtotime($row["year_of_commencement"]))?></td>
         <td><?php echo $row["year_of_renewal"]?></td>
         <td><?php echo date("d/m/Y", strtotime($row["expiry_date"]))?></td>
         <td><?php echo $row["franchisor_company_name"]?></td>
         <td><?php echo getFranchiseeCompanyInfo($row["centre_franchisee_company_id"], "franchisee_company_name")?></td>
         <td><?php echo $row["kindergarten_name"]?></td>
         <td><?php echo getFranchiseeNameInfo($row["centre_franchisee_name_id"], "franchisee_name")?></td>
         <td><?php echo $row["company_name"]?></td>
      </tr>
<?php
   }
} else {
   echo "<tr><td colspan='12'>No record found</td></tr>";
}
?>
   </tbody>
</table>
</div>