<?php
include_once("../mysql.php");

if ($_SESSION["isLogin"]==1) {
   if (($_SESSION["UserType"]=="A") & (hasRightGroupXOR($_SESSION["UserName"], "StockBalancesView"))) {
?>

<div class="uk-margin-right uk-margin-top uk-form">
   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color">Stock Card</h2>
   </div>
   <select name="product_code" id="product_code">
      <option value="">Product</option>
<?php
$sql="SELECT * from product order by product_code";
$result=mysqli_query($connection, $sql);
while ($row=mysqli_fetch_assoc($result)) {
?>
   <option value="<?php echo $row["product_code"]?>"><?php echo $row["product_name"]?></option>
<?php
}
?>
   </select>
   <input type="text" name="date_from" id="date_from" placeholder="Date From" data-uk-datepicker="{format: 'DD/MM/YYYY'}" value="<?php echo $date_from ?>" autocomplete="off">
   <input type="text" name="date_to" id="date_to" placeholder="Date To" data-uk-datepicker="{format: 'DD/MM/YYYY'}" value="<?php echo $date_to ?>" autocomplete="off">
<script>
function generateStockCard() {
   var product_code=$("#product_code").val();
   var date_from=$("#date_from").val();
   var date_to=$("#date_to").val();

   if (product_code!="") {
      $.ajax({
         url : "admin/generateStockCard.php",
         type : "POST",
         data : "product_code="+product_code+"&date_from="+date_from+"&date_to="+date_to,
         dataType : "text",
         success : function(response, status, http) {
            $("#sctStockCard").html(response);
         },
         error : function(http, status, error) {
            UIkit.notify("Error:"+error);
         }
      });
   } else {
      UIkit.notify("Please enter a product code");
   }
}
</script>
   <a class="uk-button" onclick="generateStockCard()">Generate</a>
   <div id="sctStockCard"></div>
</div>
<?php
   } else {
      echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
   }
} else {
   header("Location: index.php");
}
?>