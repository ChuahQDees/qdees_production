<?php
session_start();

function getCentreName($centre_code) {
   global $connection;

   $sql="SELECT company_name from centre where centre_code='$centre_code'";
//   echo $sql;
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["company_name"];
}
function getProductName($product_code) {
   global $connection;

   $sql="SELECT product_name from product where product_code='$product_code'";
//   echo $sql;
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["product_name"];
}
if ($_SESSION["isLogin"]==1) {
   if (($_SESSION["UserType"]=="S") &
      (hasRightGroupXOR($_SESSION["UserName"], "KIVEdit|KIVView"))) {
      include_once("mysql.php");
      include_once("admin/functions.php");
      include_once("lib/pagination/pagination.php");
      $p=$_GET["p"];
      $m=$_GET["m"];
      $get_sha1_id=$_GET["id"];
      $pg=$_GET["pg"];
      $mode=$_GET["mode"];

      $str_module="KIV";
      $p_module="kiv";
      $s=$_GET["s"];

      if ($mode=="") {
         $mode="ADD";
      }

      include_once($p_module."_func.php");
?>

<script>
$(document).ready(function () {
   $("#product_code").change(function () {
      var product_code=$("#product_code").val();
	  //alert(product_code);
      $.ajax({
         url : "admin/getUnitPrice.php",
         type : "POST",
         data : "product_code="+product_code,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            $("#unit_price").val(response);
         },
         error : function(http, status, error) {
            UIkit.notify("Error:"+error);
         }
      });
   });

   $("#qty").focusout(function () {
      var product_code=$("#product_code").val();
      var qty=$("#qty").val();
      var unit_price=$("#unit_price").val();

      if ((product_code!="") & (unit_price!="") & (qty!="")) {
         $.ajax({
            url : "admin/calcTotal.php",
            type : "POST",
            data : "product_code="+product_code+"&unit_price="+unit_price+"&qty="+qty,
            dataType : "text",
            beforeSend : function(http) {
            },
            success : function(response, status, http) {
               $("#total").val(response);
            },
            error : function(http, status, error) {
               UIkit.notify("Error:"+error);
            }
         });
      } else {
         UIkit.notify("Please fill in Product, Unit Price and Qty");
      }
   });
});

function doDeleteRecord(id) {
   UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function () {
      $("#sha_id").val(id);
      $("#frmDeleteRecord").submit();
   });
}
</script>

<div class="uk-margin-right">
   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color"><?php echo $str_module?></h2>
   </div>
   <div class="uk-form uk-form-small">
<?php
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "KIVEdit"))) {
?>
   <form name="frmCourse" id="frmCourse" method="post" action="index.php?p=<?php echo $p_module?>&pg=<?php echo $pg?>&mode=SAVE">
<?php
}
?>
      <div class="uk-grid">
         <div class="uk-width-medium-5-10">
            <table class="uk-table uk-table-small">
               <input type="hidden" name="id" id="id" value="<?php echo $edit_row['id']?>">
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Centre Name</td>
                  <td class="uk-width-7-10">
                     <select class="uk-width-1-1 chosen-select" name="centre_code" id="centre_code">
                        <option value="">Select</option>
<?php
$sql="SELECT * from centre order by kindergarten_name";
$result=mysqli_query($connection, $sql);
while ($row=mysqli_fetch_assoc($result)) {
?>
                        <option value="<?php echo $row['centre_code']?>" <?php if ($row["centre_code"]==$edit_row['centre_code']) {echo "selected";}?>><?php echo $row["company_name"]?></option>
<?php
}
?>
                     </select>
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Product Name : </td>
                  <td class="uk-width-7-10">
                     <select class="uk-width-1-1 chosen-select" name="product_code" id="product_code">
                        <option value="">Select</option>
<?php
$sql="SELECT * from product order by product_code";
$result=mysqli_query($connection, $sql);
while ($row=mysqli_fetch_assoc($result)) {
?>
                        <option <?php if ($row['product_code']==$edit_row['product_code']) {echo "selected";}?> value="<?php echo $row['product_code']?>"><?php echo $row["product_name"]?></option>
<?php
}
?>
                     </select>
                  </td>
               </tr>
			   <script src="lib/sign/js/jquery.signature.js"></script>
    <script type="text/javascript" src="lib/sign/js/jquery.ui.touch-punch.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.min.css">
    <script>
    $('.chosen-select').chosen({
        search_contains: true
    }).change(function(obj, result) {
        // console.debug("changed: %o", arguments);
        // console.log("selected: " + result.selected);
    });
	</script>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Quantity : </td>
                  <td>
                     <input type="number" class="uk-width-1-1" name="qty" id="qty" step="1" placeholder="Quantity" value="<?php echo $edit_row['qty']?>">
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Status : </td>
                  <td>
                     <select name="status" id="status" class="uk-width-1-1">
                        <option value="">Select</option>
                        <option value="Pending" <?php if ($edit_row['status']=="Pending") {echo "selected";}?>>Pending</option>
                        <option value="Packing" <?php if ($edit_row['status']=="Packing") {echo "selected";}?>>Packing</option>
						<option value="Ready for Collection" <?php if ($edit_row['status']=="Ready for Collection") {echo "selected";}?>>Ready for Collection</option>
						<option value="Delivered" <?php if ($edit_row['status']=="Delivered") {echo "selected";}?>>Delivered</option>
						<!--<option value="Approved" <?php// if ($edit_row['status']=="Approved") {echo "selected";}?>>Approved</option>-->
                     </select>
                  </td>
               </tr>
            </table>
         </div>
         <div class="uk-width-medium-5-10">
            <table class="uk-table uk-table-small">
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Unit Price : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" type="number" step="0.01" name="unit_price" id="unit_price" value="<?php echo $edit_row['unit_price']?>" readonly>
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Total : </td>
                  <td class="uk-width-7-10"><input class="uk-width-1-1" type="number" step="0.01" name="total" id="total" value="<?php echo $edit_row['total']?>" readonly></td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Delivery Date : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" data-uk-datepicker="{format:'DD/MM/YYYY'}" type="text" class="uk-width-1-1" name="delivery_date" id="delivery_date" value="<?php echo $edit_row['delivery_date']?>">
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Remarks : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" type="text" class="uk-width-1-1" name="remarks" id="remarks" value="<?php echo $edit_row['remarks']?>">
                  </td>
               </tr>
            </table>
         </div>
      </div>
      <br>
      <div class="uk-text-center">
<?php
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "KIVEdit"))) {
?>
         <button class="uk-button uk-button-primary">Save</button>
<?php
}
?>
      </div>
<?php
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "KIVEdit"))) {
?>
   </form>
<?php
}
?>
   </div>
   <br>

   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color">Searching</h2>
   </div>
   <form class="uk-form" name="frm<?php echo $str_module?>Search" id="frm<?php echo $str_module?>Search" method="get">
      <input type="hidden" name="mode" id="mode" value="BROWSE">
      <input type="hidden" name="p" id="p" value="<?php echo $p?>">
      <input type="hidden" name="m" id="m" value="<?php echo $m?>">
      <input type="hidden" name="pg" value="">

      <div class="uk-grid">
         <div class="uk-width-7-10 uk-text-small">
            <input class="uk-width-1-1" placeholder="Centre Name/Product Name/Status/Remarks" name="s" id="s" value="<?php echo $_GET['s']?>">
         </div>
		<!-- <div class="uk-width-2-10 uk-text-small">
			<!--<input class="uk-width-1-1" placeholder="Product Name" name="product_name" id="product_name" value="<?php echo $_GET['product_name']?>">
         </div>
		 <div class="uk-width-2-10 uk-text-small">
			<input class="uk-width-1-1" placeholder="Status" name="status" id="status" value="<?php echo $_GET['status']?>">
         </div>
		 <div class="uk-width-2-10 uk-text-small">
			<input class="uk-width-1-1" placeholder="Remarks" name="remarks" id="remarks" value="<?php echo $_GET['remarks']?>">
         </div>-->
         <div class="uk-width-2-10">
            <button class="uk-button uk-width-1-1">Search</button>
         </div>
      </div>
   </form><br>

   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color">Listing</h2>
   </div>

<?php
$numperpage=10;
$query="p=$p&m=$m&s=$s";
$pagination=getPagination($pg, $numperpage, $query, $browse_sql, $start_record, $num_row);
$browse_sql.=" limit $start_record, $numperpage";
$browse_result=mysqli_query($connection, $browse_sql);
$browse_num_row=mysqli_num_rows($browse_result);
?>
   <div class="uk-overflow-container">
   <table class="uk-table">
      <tr class="uk-text-bold uk-text-small">
         <td>Centre</td>
         <td>Product</td>
         <td>Quantity</td>
         <td>Unit Price</td>
         <td>Total</td>
         <td>Status</td>
         <td>Delivery Date</td>
         <td>Remarks</td>
         <td>Action</td>
      </tr>
<?php
if ($browse_num_row>0) {
   while ($browse_row=mysqli_fetch_assoc($browse_result)) {
      $sha1_id=sha1($browse_row["id"]);
?>
      <tr class="uk-text-small">
         <td><?php echo getCentreName($browse_row["centre_code"])?></td>
         <td><?php
			//$product_code = $browse_row["product_code"];
			//$product_code = explode("((--",$product_code)[0];
			echo getProductName($browse_row["product_code"]);
		// echo $browse_row["product_code"] 
		 ?></td>
         <td><?php echo $browse_row["qty"]?></td>
         <td><?php echo $browse_row["unit_price"]?></td>
         <td><?php echo $browse_row["total"]?></td>
         <td><?php echo $browse_row["status"]?></td>
         <td><?php echo convertDate2British($browse_row["delivery_date"])?></td>
         <td><?php echo $browse_row["remarks"]?></td>
         <td>
            <!-- <a href="index.php?p=<?php echo $p?>&m=<?php echo $m?>&id=<?php echo $sha1_id?>&mode=EDIT"><img src="images/edit.png"></a> -->
<?php
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "KIVEdit"))) {
?>
            <a href="index.php?p=<?php echo $p?>&m=<?php echo $m?>&id=<?php echo $sha1_id?>&mode=EDIT&master=1"><img style="width: 15px;" src="images/edit.png"></a>
            <a onclick="doDeleteRecord('<?php echo $sha1_id?>')" href="#" id="btnDelete"><img style="width: 15px;" src="images/delete.png"></a>
<?php
}
?>
<?php
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "KIVEdit|KIVView"))) {
	if ($browse_row["status"]=="Delivered"){
?>

            <a href="admin/generate_kiv_do.php?id=<?php echo $sha1_id?>" target="_blank"><img style="width: 30px;" src="images/do.png" data-uk-tooltip title="Generate KIV DO"></a>
<?php
	}
}
?>
         </td>
      </tr>
<?php
   }
} else {
   echo "<tr><td colspan='5'>No Record Found</td></tr>";
}
?>
   </table>
   </div>
<?php
echo $pagination;
?>
</div>

<form name="frmDeleteRecord" id="frmDeleteRecord" method="get">
   <input type="hidden" name="p" value="<?php echo $p?>">
   <input type="hidden" name="m" value="<?php echo $m?>">
   <input type="hidden" name="sha_id" id="sha_id" value="">
   <input type="hidden" name="mode" value="DEL">
</form>
<?php
if ($msg!="") {
   echo "<script>UIkit.notify('$msg')</script>";
}
   } else {
      echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
   }
} else {
   header("Location: index.php");
}
?>