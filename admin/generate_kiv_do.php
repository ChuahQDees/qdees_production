<?php
include_once("../mysql.php");
include_once("../uikit1.php");

foreach ($_REQUEST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //$id
}

function getFranchiseeInfo($id, &$actual_id, &$delivery_date, &$company_name, &$address1, &$address2, &$address3, &$address4, &$address5, &$principle_name, &$principle_contact_no) {
   global $connection;

   $sql="SELECT k.*, c.company_name, c.address1, c.address2, c.address3, c.address4, c.address5, c.principle_name,
   c.principle_contact_no from centre c, kiv k where k.centre_code=c.centre_code and sha1(k.id)='$id'";
//echo $sql;
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   $actual_id=$row["id"];
   $company_name=$row["company_name"];
   $delivery_date=date("d/m/Y", strtotime($row["delivery_date"]));
   $address1=$row["address1"];
   $address2=$row["address2"];
   $address3=$row["address3"];
   $address4=$row["address4"];
   $address5=$row["address5"];
   $principle_name=$row["principle_name"];
   $principle_contact_no=$row["principle_contact_no"];
}

$sql="SELECT k.*, p.product_name from `kiv` k, product p where sha1(k.id)='$id' and p.product_code=k.product_code order by id";
//echo $sql;
$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);

if ($num_row>0) {
   getFranchiseeInfo($id, $actual_id, $delivery_date, $company_name, $address1, $address2, $address3, $address4, $address5, $principle_name, $principle_contact_no);
?>
<script src="../lib/sign/js/jquery.signature.js"></script>
<script type="text/javascript" src="../lib/sign/js/jquery.ui.touch-punch.min.js"></script>

<style>
table {
   width :100%;
   border-collapse: collapse;
}

.td-border {
   border: solid 1px #000000;
}

.td-no-border {
   border: 0px;
}

.hr-border {
   border-width: 1px;
   border-style: inset;
}

.font-30 {
   font-size: 30px;
}

.td-no-right-border {
   border-right:none;
}

.td-no-bottom-border {
   border-bottom:none;
}

.td-no-top-border {
   border-top:none;
}

</style>
<div class="uk-margin-left uk-margin-right">
<table>
   <tr>
      <td class="td-no-border">
      Q-dees Holdings SDN BHD<br>
      Company No. : 221544-V
      </td>
	  <td class="td-no-border" style="text-align:right;">
      <img src="/images/Logo Qdees Scholar.png" alt="logo" style="width:150px;">
      </td>
   </tr>
</table>
<hr class="hr-border">
<table>
   <tr>
      <td class="font-30"><center>KIV DELIVERY ORDER</center></td>
   </tr>
</table>
<table>
   <col style="width: 60%">
   <col style="width: 40%">
   <tr>
      <td>
         <?php echo $company_name?><br>
         <?php echo $address1?><br>
         <?php echo $address2?><br>
         <?php echo $address3?><br>
         <?php echo $address4?><br>
         <?php echo $address5?><br>
         ATTN : <?php echo $principle_name?><br>
         TEL. : <?php echo $principle_contact_no?><br>
         FAX  : <?php echo $fax?><br>
      </td>
      <td>
         <?php echo "Delivery Order No. : $actual_id"?><br><br>
         <?php echo "DATE : $delivery_date"?><br><br>
         <?php echo "TERMS : "?><br><br>
         <?php echo "PAGE : "?><br><br>
      </td>
   </tr>
</table>

<table>
   <tr>
      <td colspan="5"><hr></td>
   </tr>
   <tr class="td-no-border">
      <td>ITEM NO.</td>
      <td>DESCRIPTION</td>
      <td style="text-align:right">QTY</td>
   </tr>
   <tr>
      <td colspan="5"><hr></td>
   </tr>
<?php
$count=0; $grand_total=0; $total_qty=0;
while ($row=mysqli_fetch_assoc($result)) {
   $count++;
   $remarks=$row["remarks"];
   $grand_total=$grand_total+($row["qty"]*$row["unit_price"]);
   $total_qty=$total_qty+$row["qty"];
   $name=$row["name"];
   $ic_no=$row["ic_no"];
   $tracking_no=$row["tracking_no"];
   $signature=$row["signature"];
?>
   <tr class="td-no-border">
      <td><?php echo explode("((--",$row["product_code"])[0];?></td>
      <td><?php echo $row["product_name"]?></td>
      <td style="text-align:right"><?php echo number_format($row["qty"], 0)?></td>
   </tr>
<?php
}
?>
   <tr>
      <td colspan="5">
<?php
for ($i=0; $i<15-$count; $i++) {
   echo "<br>";
}
?>
Remarks : <?php echo $remarks?>
         <hr>
      </td>
   </tr>
   <tr>
      <td>Cheque should be crossed and made to </td>
      <td style="text-align:right">Total : </td>
      <td style="text-align:right"><?php echo number_format($total_qty, 0)?></td>
   </tr>
</table>

<table>
   <tr>
      <td style="text-align:left">This is computer generated Delivery Order. No signature required.</td>
   </tr>
</table>

<style>
.kbw-signature { width: 370px; height: 300px; border:1px}
</style>

<table>
   <tr>
      <td>
         <input type="hidden" id="signaturejson" value='<?php echo $signature?>'>
         <div id="signature"></div>
      </td>
   </tr>
   <tr>
      <td>
         <table style="align:left">
            <tr>
               <td>Name : <?php echo $name?></td>
            </tr>
            <tr>
               <td>IC No : <?php echo $ic_no?></td>
            </tr>
            <tr>
               <td>Tracking No. : <?php echo $tracking_no?></td>
            </tr>
         </table>
      </td>
   </tr>
</table>
<br><br>
<script>
function printDialog() {
   $("#btnPrint").hide();
   window.print();
}
</script>
<button onclick="printDialog();" id="btnPrint" class="uk-button">Print</button>
<script>
$(document).ready(function () {
   $("#signature").signature({background:'#FFFFFF'});
   $("#signature").signature('draw', $("#signaturejson").val());
   $("#signature").signature("disable", true);
});
</script>
</div>
<p style="text-align:right"></p>
<?php
}
?>