<a href="/">                 
				 <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>
<span>
    <span class="page_title"><img src="/images/title_Icons/Defective Product.png">Defective Product</span>
</span>

<?php
session_start();
if ($_SESSION["isLogin"]==1) {
   if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) &
      (hasRightGroupXOR($_SESSION["UserName"], "DefectiveProductEdit|DefectiveProductView"))) {
      include_once("mysql.php");
      include_once("admin/functions.php");
      include_once("lib/pagination/pagination.php");
      $p=$_GET["p"];
      $m=$_GET["m"];
      $get_sha1_id=$_GET["id"];
      $pg=$_GET["pg"];
      $mode=$_GET["mode"];

      $str_module="Defective";
      $p_module="defective_product";
      $s=$_GET["s"];

      if ($mode=="") {
         $mode="ADD";
      }

      include_once($p_module."_func.php");
?>

<script>
$(document).ready(function () {
   $("#product_code").change(function () {
      var product_code=$("#product_code");
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
      <h2 class="uk-text-center myheader-text-color myheader-text-style"><?php echo $str_module?></h2>
   </div>
   <div class="uk-form uk-form-small nice-form">
<?php
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "DefectiveProductEdit"))) {
?>
   <form name="frmCourse" id="frmCourse" method="post" action="index.php?p=<?php echo $p_module?>&pg=<?php echo $pg?>&mode=SAVE">
<?php
}
?>
      <div class="uk-grid">
         <div class="uk-width-medium-5-10">
            <table class="uk-table uk-table-small">
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Centre : </td>
                  <td class="uk-width-7-10">
<?php
if (($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) {
?>
                     <?php echo $_SESSION["CentreCode"]?>
                     <input type="hidden" name="centre_code" id="centre_code" value="<?php echo $_SESSION['centre_code']?>">
<?php
} else {
?>
                     <select class="uk-width-1-1" name="centre_code" id="centre_code">
                        <option value="">Select</option>
<?php
$sql="SELECT * from centre order by centre_code";
$result=mysqli_query($connection, $sql);
while ($row=mysqli_fetch_assoc($result)) {
?>
                        <option <?php if ($row['centre_code']==$edit_row['centre_code']) {echo "selected";}?> value="<?php echo $row['centre_code']?>"><?php echo $row["kindergarten_name"]." (".$row["centre_code"].")"?></option>
<?php
}
?>
                     </select>
<?php
}
?>
                     <input type="hidden" name="id" id="id" value="<?php echo $edit_row['id']?>">
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Product : </td>
                  <td class="uk-width-7-10">
                     <select class="uk-width-1-1" name="product_code" id="product_code">
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
            </table>
         </div>
         <div class="uk-width-medium-5-10">
            <table class="uk-table uk-table-small">
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Quantity : </td>
                  <td>
                     <input type="number" class="uk-width-1-1" name="qty" id="qty" value="" step="1" placeholder="Quantity" value="<?php echo $edit_row['qty']?>">
                  </td>
               </tr>
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
            </table>
         </div>
      </div>
      <br>
      <div class="uk-text-center">
<?php
if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) & (hasRightGroupXOR($_SESSION["UserName"], "DefectiveProductEdit"))) {
?>
         <button class="uk-button uk-button-primary">Save</button>
<?php
}
?>
      </div>
<?php
if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) & (hasRightGroupXOR($_SESSION["UserName"], "DefectiveProductEdit"))) {
?>
   </form>
<?php
}
?>
   </div>
   <br>

   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color  myheader-text-style">Searching</h2>
   </div>
   <form class="uk-form" name="frm<?php echo $str_module?>Search" id="frm<?php echo $str_module?>Search" method="get">
      <input type="hidden" name="mode" id="mode" value="BROWSE">
      <input type="hidden" name="p" id="p" value="<?php echo $p?>">
      <input type="hidden" name="m" id="m" value="<?php echo $m?>">
      <input type="hidden" name="pg" value="">

      <div class="uk-grid">
         <div class="uk-width-7-10 uk-text-small">
            <input class="uk-width-1-1" placeholder="Centre Code/Name/Product Code/Name" name="s" id="s" value="<?php echo $_GET['s']?>">
         </div>
         <div class="uk-width-3-10">
            <button class="uk-button uk-width-1-1">Search</button>
         </div>
      </div>
   </form><br>

   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color  myheader-text-style">Listing</h2>
   </div>

<?php
$numperpage=20;
$query="p=$p&m=$m&s=$s";
$pagination=getPagination($pg, $numperpage, $query, $browse_sql, $start_record, $num_row);
$browse_sql.=" limit $start_record, $numperpage";
$browse_result=mysqli_query($connection, $browse_sql);
$browse_num_row=mysqli_num_rows($browse_result);
//echo $pagination;
?>
   <div class="uk-overflow-container">
   <table class="uk-table q-table q-table-light">
      <tr class="uk-text-bold uk-text-small">
         <td>Centre</td>
         <td>Product</td>
         <td>Quantity</td>
         <td>Unit Price</td>
         <td>Total</td>
         <td>Action</td>
      </tr>
<?php
if ($browse_num_row>0) {
   while ($browse_row=mysqli_fetch_assoc($browse_result)) {
      $sha1_id=sha1($browse_row["id"]);
?>
      <tr class="uk-text-small">
         <td><?php echo $browse_row["centre_code"]?></td>
         <td><?php echo $browse_row["product_code"]?></td>
         <td><?php echo $browse_row["qty"]?></td>
         <td><?php echo $browse_row["unit_price"]?></td>
         <td><?php echo $browse_row["total"]?></td>
         <td>
            <!-- <a href="index.php?p=<?php echo $p?>&m=<?php echo $m?>&id=<?php echo $sha1_id?>&mode=EDIT"><img src="images/edit.png"></a> -->
 <?php
 if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "DefectiveProductEdit"))) {
 ?>
            <a onclick="doDeleteRecord('<?php echo $sha1_id?>')" href="#" id="btnDelete"><img src="images/delete.png"></a>
<?php
 }
?>
         </td>
      </tr>
<?php
   }
} else {
   echo "<tr><td colspan='4'>No Record Found</td></tr>";
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