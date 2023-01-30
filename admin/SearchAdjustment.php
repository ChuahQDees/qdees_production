<?php
session_start();
include_once("../mysql.php");
include_once("../search_new.php");
include_once("functions.php");
$year = $_SESSION['Year'];
foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);//product_code, centre_code, date_from, date_to
}

$base_sql="SELECT s.*, c.company_name, p.product_name from stock_adjustment s, centre c, product p where s.centre_code=c.centre_code and s.product_code=p.product_code and (effective_date BETWEEN '".$year_start_date."' AND '".$year_end_date."')";

$product_code_token=ConstructToken("product_code", $product_code, "=");
$centre_code_token=ConstructToken("centre_code", $centre_code, "=");
$date_token=ConstructTokenGroup("effective_date", $df, ">=", "effective_date", $dt, "<=", "and");
$order_token=ConstructOrderToken("effective_date", "desc");
 $final_token=ConcatToken($product_code_token, $centre_code_token, "and");
 $final_token=ConcatToken($final_token, $date_token, "and");

 $final_sql=ConcatWhere($base_sql, $final_token);
 $final_sql=ConcatOrder($final_sql, $order_token);
 $final_sql.=" limit 50";
//echo $final_sql;
$result=mysqli_query($connection, $final_sql);
$num_row=mysqli_num_rows($result);

?>
<script>
function doDelete(id) {
   // alert(id);
   // if (id!="") {
   //    $.ajax({
   //       url : "admin/DeleteAdjustment.php",
   //       type : "POST",
   //       data : "id="+id,
   //       dataType : "text",
   //       beforeSend : function(http) {
   //       },
   //       success : function(response, status, http) {
   //          var s=response.split("|");

   //          if (s[0]=="1") {
   //             UIkit.notify(s[1]);
   //             window.location.reload();
   //          }

   //          if (s[0]=="0") {
   //             UIkit.notify(s[1]);
   //          }
   //       },
   //       error : function(http, status, error) {
   //          UIkit.notify("Error:"+error);
   //       }
   //    });
   // } else {
   //      UIkit.notify("Error");
   // }
}
</script>
<table class="uk-table">
<?php
if ($num_row>0) {
?>
   <tr class="uk-text-bold">
        <!--<td>Centre Code</td>-->
        <td>Centre Name</td>
        <td>Product Name</td>
		<td>Current Qty</td>
		<td>Update Qty</td>
        <td>Effective Date</td>        
        <td>Adjusted date</td>
        <td>Adjusted On</td>
<?php
if (hasRightGroupXOR($_SESSION["UserName"], "StockAdjustmentEdit")) {
?>
        <td>Action</td>
<?php
}
?>
   </tr>
<?php
while ($row=mysqli_fetch_assoc($result)) {
?>
   <tr>
      <!--<td><?php// echo $row["centre_code"]?></td>-->
      <td><?php echo $row["company_name"]?></td>
      <td><?php
			// $product_code = $row["product_code"];
			// $product_code = explode("((--",$product_code)[0];
			// echo $product_code;
	  echo $row["product_name"]
	  
	  ?></td>
      <td><?php// echo $row["effective_date"]?></td>
	  <td><?php echo number_format($row["adjust_qty"])?></td>
      <td><?php echo $row["effective_date"]?></td>
	  <td><?php echo $row["adjusted_at"]?></td>
      <td><?php echo $row["adjusted_by"]?></td>
      
<?php
if (hasRightGroupXOR($_SESSION["UserName"], "StockAdjustmentEdit")) {
   $id=sha1($row["id"]);
?>
      <td>
         <a onclick="doDelete('<?php echo $id?>')" data-uk-tooltip title="Delete"><img src="../images/delete.png"></a>
      </td>
<?php
}
?>
   </tr>
<?php
}
?>
<?php
} else {
?>
   <tr><td colspan="5">No record found</td></tr>
<?php
}
?>
</table>