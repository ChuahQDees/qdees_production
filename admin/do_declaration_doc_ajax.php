<?php
session_start();
include_once("../mysql.php");

?>

<table class="uk-table">
  <tr>
	<td>File</td> 
	<td>Remarks</td> 
	<td>Created By</td> 
	<td>Date</td> 
  </tr>
<?php
 
foreach($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //$sha_id, $sOrderNo
}
 if($sha_id == ''){ $sha_id = $_SESSION["temp_id"];}
$sql22 ="SELECT * FROM declaration_doc WHERE declaration_id = '".$sha_id."' order by id desc";
$result22=mysqli_query($connection,$sql22);
while($row22 = mysqli_fetch_assoc($result22)) {

$doc = $row22['doc'];
$remarks = $row22['remarks'];
$update_date = $row22['update_date'];
$payment_by = $row22['created_by'];
// $ext = pathinfo($doc, "http://starters.q-dees.com.my/admin/uploads/");
// echo $ext; die;
$path = "/admin/uploads/".$doc;
$path_info = pathinfo($path);

//print_r($path_info);
//$path_info['extension'];
echo "<tr>";
echo "<td style='text-align: center; vertical-align: middle;'><a target='_blank' href='/admin/uploads/$doc'>";

if ($path_info['extension']=='pdf') {
   echo '<img  width="35" src="/admin/uploads/pdf.webp" alt="PDF">';
} else {
   echo '<img  width="100" height="100" src="/admin/uploads/'.$doc.'" alt="image">';
}
echo "</a></td>"; 
echo "<td>$remarks</td>"; 
echo "<td>$payment_by</td>";
echo "<td>$update_date</td>";
echo "</tr>";
}?>

</table>