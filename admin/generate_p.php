<?php 
include_once("../mysql.php");
include_once("../uikit1.php");

foreach ($_REQUEST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //$order_no
}

function getFranchiseeInfo($order_no, &$actual_order_no, &$ordered_on, &$centre_name, &$address1, &$address2, &$address3, &$address4, &$address5, &$principle_name, &$principle_contact_no) {
   global $connection;

   $sql="SELECT o.order_no, o.ordered_on, c.company_name, c.address1, c.address2, c.address3, c.address4, c.address5, c.principle_name,
   c.principle_contact_no from centre c, `order` o where o.centre_code=c.centre_code and sha1(o.order_no)='$order_no'";
//echo $sql;
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   $actual_order_no=$row["order_no"];
   $centre_name=$row["company_name"];
   $ordered_on=$row["ordered_on"];
   $ordered_on=date("d/m/Y", strtotime($ordered_on));
   $address1=$row["address1"];
   $address2=$row["address2"];
   $address3=$row["address3"];
   $address4=$row["address4"];
   $address5=$row["address5"];
   $principle_name=$row["principle_name"];
   $principle_contact_no=$row["principle_contact_no"];
}
$company_sql="SELECT c.code, c.description from `order` o, product p, codes c 
where sha1(order_no)='$order_no' and p.product_code=o.product_code and p.category=c.code and c.module='CATEGORY' and c.parent='' group by p.category";
//echo $sql;
$company_result=mysqli_query($connection, $company_sql);
$company_num_row=mysqli_num_rows($company_result);

if ($company_num_row>0) {
   getFranchiseeInfo($order_no, $actual_order_no, $ordered_on, $centre_name, $address1, $address2, $address3, $address4, $address5, $principle_name, $principle_contact_no);
	$active_company = [];
?>

<style>
html{
	height: 100%;
	box-sizing: border-box;
}

body {
  position: relative;
  margin: 0;
  padding-bottom: 6rem;
  min-height: 100%;
}
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
.header-space,
.footer-space {
  height: 60px;
}

@media print { 
	.hidePrint{
		display:none;
	}
	.footer{
		 position: fixed; 
		 bottom: 20px;
	}
    html, body {
        height: 92%;    
    }
}
</style>
<!--<div style="padding: 10px" class="hidePrint">
<?php 
while ($company_row=mysqli_fetch_assoc($company_result)){
	if($active_company==[]) {
		$active_company[]=$company_row['code'];
		$active_company[]=$company_row['description'];
	}
?>
	<button onclick="switchCompany('<?php echo $company_row['code'] ?>', this)" class="uk-button <?php echo $active_company[0]==$company_row['code'] ? 'uk-active' : '' ?>">Sales Order for <?php echo $company_row['code'] ?></button>
<?php } ?>
</div> -->
<!--<br>
<?php
$company_result=mysqli_query($connection, $company_sql);
while ($company_row=mysqli_fetch_assoc($company_result)){ 
$company_name = $company_row['code'];
?>
<div class="uk-margin-left uk-margin-right so-content" id="company-<?php echo $company_row['code'] ?>" <?php echo $active_company[0]==$company_row['code'] ? '' : 'style="display:none"' ?>>
<table>
   <tr>
      <td class="td-no-border">
      <?php echo strtoupper($company_name) ?><br>
      Company No. : <?php echo $company_row['description'] ?><br>1110457-P
      </td>
	  <td class="td-no-border" style="text-align:right;">
      <img src="../images/Logo Qdees Scholar.png" alt="logo" style="width:150px;">
      </td>
   </tr>
</table>
<hr class="hr-border">-->
<!--<table>
   <tr>
      <td class="font-30"><center><u>PAYMENT PROOF OF</center></td>
   </tr>
</table> -->
<!--<table>
   <col style="width: 60%">
   <col style="width: 40%">
   <tr>
      <td>
         <?php echo $centre_name?><br>
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
         <?php echo "NO. : $actual_order_no"?><br><br>
         <?php echo "DATE : $ordered_on"?><br><br>
         <?php echo "TERMS : "?><br><br>
         <?php echo "PAGE : "?><br><br>
      </td>
   </tr>
</table> 

<table id="items">
<thead>
   <tr>
      <td colspan="5"><hr></td>
   </tr>
   <tr class="td-no-border">
      <td>ITEM NO.</td>
      <td>DESCRIPTION</td>
      <td style="text-align:right">QTY</td>
      <td style="text-align:right">U. PRICE</td>
      <td style="text-align:right">AMOUNT</td>
   </tr>
   <tr>
      <td colspan="5"><hr></td>
   </tr>
  </thead>
  <tbody>
<?php

$sql="SELECT o.*, p.product_name from `order` o, product p where sha1(order_no)='$order_no' and p.product_code=o.product_code and p.category='$company_name' order by id";
//echo $sql;
$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);

if ($num_row>0) {
	$count=0; $grand_total=0; $total_qty=0;
	$remarks = '';
	while ($row=mysqli_fetch_assoc($result)) {
	   $count++;
	   $grand_total=$grand_total+($row["qty"]*$row["unit_price"]);
	   $total_qty=$total_qty+$row["qty"];
	   $remarks = $row['remarks'];
?>
   <tr class="td-no-border">
      <td><?php echo $row["product_code"]?></td>
      <td><?php echo $row["product_name"]?></td>
      <td style="text-align:right"><?php echo number_format($row["qty"], 0)?></td>
      <td style="text-align:right"><?php echo number_format($row["unit_price"], 2)?></td>
      <td style="text-align:right"><?php echo number_format($row["qty"]*$row["unit_price"], 2)?></td>
   </tr>
<?php
	}
}
?> -->
  <!-- </tbody>
   <tfoot>
	   <tr>
		  <td colspan="5"><hr></td>
	   </tr>
	   <tr>
		  <td colspan="2"><span style="float: right">Total : </span></td>
		  <td style="text-align:right"><?php echo number_format($total_qty, 0)?></td>
		  <td style="text-align:right" colspan="2"><?php echo number_format($grand_total, 2)?></td>
	   </tr>
		 <tr>
			<td><div class="footer-space">&nbsp;</div></td>
		 </tr>
   </tfoot>
</table> -->



<div class="uk-text-center">
  

<h1 class="uk-text-center"><u>Document</u></h1>


<?php


$sql22 ="SELECT * FROM prove_payment_doc where sha1(order_no)='$order_no' limit 1";
$result22=mysqli_query($connection,$sql22);

while($row = mysqli_fetch_assoc($result22)) {

  $doc = $row['doc'];




echo "<img  class='img-fluid' width='1000' height='1200' src='uploads/$doc' alt='image'>";
}










?>

<img src="">






</div>










<!--<br><br><br>

<div>Remarks:</div>
<?php echo htmlspecialchars($remarks); ?>

<p style="text-align:left;" class="footer">This is computer generated Payment proof no signature required.</p>
</div>
<?php
}
?>
<div style="padding: 10px" class="hidePrint">
<button onclick="printDialog();" id="btnPrint" class="uk-button">Print</button>
</div>
<script>
function printDialog() {
   //$("#btnPrint").hide();
   window.print();
}
function switchCompany(company, curEl) {
	[].forEach.call(document.querySelectorAll('.uk-active'), function (el) {
	  el.classList.remove("uk-active");
	});
	[].forEach.call(document.querySelectorAll('.so-content'), function (el) {
	  el.style.display = 'none';
	});
	curEl.classList.add("uk-active");
	document.getElementById('company-'+company).style.display = "block";
}
</script>
<?php
}
?> -->