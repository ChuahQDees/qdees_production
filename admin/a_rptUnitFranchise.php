<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //$centre_code
}

$year=$_SESSION["Year"];

function getVisitorByMonth($centre_code, $month) {
   global $connection, $year;

   if ($centre_code=="ALL") {
      $sql="SELECT count(*) as count from visitor where month(date_created)='$month' and year(date_created)='$year'";
   } else {
      $sql="SELECT count(*) as count from visitor where centre_code='$centre_code' and month(date_created)='$month'
      and year(date_created)='$year'";
   }

   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["count"];
}

function getVisitor($centre_code, $reason, $month) {
   global $connection, $year;

   if ($centre_code=="ALL") {
      $sql="SELECT count(*) as count from visitor where find_out='$reason' and month(date_created)='$month' and year(date_created)='$year'";
//      echo $sql."<br>";
   } else {
      $sql="SELECT count(*) as count from visitor where centre_code='$centre_code' and find_out='$reason'
      and month(date_created)='$month' and year(date_created)='$year'";
   }

   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["count"];
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

if ($centre_code!="") {
?>
<br>
<div class="uk-text-center">
   <h2>UNIT FRANCHISE REPORT</h2>
</div>
<div class="uk-grid">
   <div class="uk-width-medium-5-10">
      <table class="uk-table">
         <tr>
            <td class="uk-text-bold">Centre Name</td>
            <td><?php echo getCentreName($centre_code)?></td>
         </tr>
         <tr>
            <td class="uk-text-bold">Prepare By</td>
            <td><?php echo $_SESSION["UserName"]?></td>
         </tr>
         <tr>
            <td colspan="2" class="uk-text-bold">NOTE: To be submitted termly with the order form</td>
         </tr>
      </table>
   </div>
   <div class="uk-width-medium-5-10">
      <table class="uk-table">
         <tr>
            <td class="uk-text-bold">Academic Year</td>
            <td><?php echo $_SESSION['Year']?></td>
         </tr>
         <tr>
            <td class="uk-text-bold">School Term</td>
            <td></td>
         </tr>
         <tr>
            <td class="uk-text-bold">Date of submission</td>
            <td><?php echo date("Y-m-d H:i:s")?></td>
         </tr>
      </table>
   </div>
</div>
<table class="uk-table">
   <tr class="uk-text-bold">
      <td>Centre</td>
      <td>January</td>
      <td>February</td>
      <td>March</td>
      <td>April</td>
      <td>May</td>
      <td>June</td>
      <td>July</td>
      <td>August</td>
      <td>September</td>
      <td>October</td>
      <td>November</td>
      <td>December</td>
      <td>Total</td>
   </tr>
<?php
$sql="SELECT * from codes where module='VISITREASON' order by code";
$result=mysqli_query($connection, $sql);

for($x=0;$x<10;$x++)
{
?>
	<tr>
      <td class="uk-text-bold"><?php echo ($x+1);?></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
	  <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
	  <td></td>
      <td></td>
      <td></td>
      <td></td>
   </tr>
<?php
}
?>

</table>

<?php
/*while ($row=mysqli_fetch_assoc($result)) {
   $jan=getVisitor($centre_code, $row["code"], "1");
   $feb=getVisitor($centre_code, $row["code"], "2");
   $mar=getVisitor($centre_code, $row["code"], "3");
   $apr=getVisitor($centre_code, $row["code"], "4");
   $may=getVisitor($centre_code, $row["code"], "5");
   $jun=getVisitor($centre_code, $row["code"], "6");
   $jul=getVisitor($centre_code, $row["code"], "7");
   $aug=getVisitor($centre_code, $row["code"], "8");
   $sep=getVisitor($centre_code, $row["code"], "9");
   $oct=getVisitor($centre_code, $row["code"], "10");
   $nov=getVisitor($centre_code, $row["code"], "11");
   $dec=getVisitor($centre_code, $row["code"], "12");
   $total=$jan+$feb+$mar+$apr+$may+$jun+$jul+$aug+$sep+$oct+$nov+$dec;
?>
   <tr>
      <td class="uk-text-bold"><?php echo $row["code"]?></td>
      <td><?php echo $jan?></td>
      <td><?php echo $feb?></td>
      <td><?php echo $mar?></td>
      <td><?php echo $apr?></td>
      <td><?php echo $may?></td>
      <td><?php echo $jun?></td>
      <td><?php echo $jul?></td>
      <td><?php echo $aug?></td>
      <td><?php echo $sep?></td>
      <td><?php echo $oct?></td>
      <td><?php echo $nov?></td>
      <td><?php echo $dec?></td>
      <td><?php echo $total?></td>
   </tr>
<?php
}
?>
   <tr>
<?php
$jan_total=getVisitorByMonth($centre_code, "1");
$feb_total=getVisitorByMonth($centre_code, "2");
$mar_total=getVisitorByMonth($centre_code, "3");
$apr_total=getVisitorByMonth($centre_code, "4");
$may_total=getVisitorByMonth($centre_code, "5");
$jun_total=getVisitorByMonth($centre_code, "6");
$jul_total=getVisitorByMonth($centre_code, "7");
$aug_total=getVisitorByMonth($centre_code, "8");
$sep_total=getVisitorByMonth($centre_code, "9");
$oct_total=getVisitorByMonth($centre_code, "10");
$nov_total=getVisitorByMonth($centre_code, "11");
$dec_total=getVisitorByMonth($centre_code, "12");

$grand_total=$jan_total+$feb_total+$mar_total+$apr_total+$may_total+$jun_total+$jul_total+$aug_total+$sep_total+$oct_total+$nov_total+$dec_total;
?>
      <td class="uk-text-bold">Total</td>
      <td><?php echo $jan_total?></td>
      <td><?php echo $feb_total?></td>
      <td><?php echo $mar_total?></td>
      <td><?php echo $apr_total?></td>
      <td><?php echo $may_total?></td>
      <td><?php echo $jun_total?></td>
      <td><?php echo $jul_total?></td>
      <td><?php echo $aug_total?></td>
      <td><?php echo $sep_total?></td>
      <td><?php echo $oct_total?></td>
      <td><?php echo $nov_total?></td>
      <td><?php echo $dec_total?></td>
      <td><?php echo $grand_total?></td>
   </tr>
</table>
<?php
*/
} else {
   echo "Please enter a centre";
}
?>