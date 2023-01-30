<?php

if ($_SESSION["isLogin"]==1) {
   if (($_SESSION["UserType"]=="A") & (hasRightGroupXOR($_SESSION["UserName"], "AddOnProductEdit|AddOnProductView"))) {
      include_once("mysql.php");
      include_once("lib/pagination/pagination.php");
      include_once("admin/functions.php");
      $p=$_GET["p"];
      $m=$_GET["m"];
      $get_sha1_id=$_GET["id"];
      $pg=$_GET["pg"];
      $mode=$_GET["mode"];

      if ($mode=="") {
         $mode="ADD";
      }

      include_once("addon_product_func.php");
?>

<script>
function doDeleteRecord(id) {
   UIkit.modal.confirm("<h2>The deleted product will not affect the current payment that has been made</h2>", function () {
      $("#id").val(id);
      $("#frmDeleteRecord").submit();
   });
}

function doSave() {
   $("#")
}
</script>
<!--<a href="/index.php?p=visitor_hq">
         <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>-->
<?php if($_GET['mode']!=""){ ?>
<a href="/index.php?p=addon">                 
             <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>
<?php }else { ?>
<a href="/">                 
             <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>
<?php } ?>
<div class="uk-margin-right">
   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color">Add-on Product Information</h2>
   </div>
   <div class="uk-form uk-form-small">
<?php
if (($_SESSION["UserType"]=="A") & (hasRightGroupXOR($_SESSION["UserName"], "AddOnProductEdit"))) {
?>
   <form name="frmAddOnProduct" id="frmAddOnProduct" method="post" class="uk-form uk-form-small" action="index.php?p=addon&pg=<?php echo $pg?>&mode=SAVE" enctype="multipart/form-data">
<?php
}
?>
      <div class="uk-grid">
         <div class="uk-width-medium-5-10">
            <table class="uk-table uk-table-small">
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Product Code : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" type="text" name="product_code" id="product_code" value="<?php echo $edit_row['product_code']?>">
                     <input type="hidden" name="hidden_product_code" id="hidden_product_code" value="<?php echo $edit_row['product_code']?>">
                     <input type="hidden" name="hidden_product_id" id="hidden_product_id" value="<?php echo $edit_row['id']?>">
                     <input type="hidden" name="hidden_id" id="hidden_id" value="<?php echo sha1($edit_row['id'])?>">
					 <span id="validationProductCode"  style="color: red; display: none;">Please insert File No</span>
					 
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Product Name : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" type="text" name="product_name" id="product_name" value="<?php echo $edit_row['product_name']?>">
					 <span id="validationProductName"  style="color: red; display: none;">Please insert File No</span>
                  </td>
               </tr>
			   <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">UOM : </td>
                  <td class="uk-width-7-10"><input class="uk-width-1-1" type="number" name="uom" id="uom" value="<?php echo $edit_row['uom']?>">
					<span id="validationUom"  style="color: red; display: none;">Please insert File No</span>
				  </td>
               </tr>
			   <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Unit Price : </td>
                  <td class="uk-width-7-10"><input class="uk-width-1-1" type="number" name="unit_price" id="unit_price" step="0.01" value="<?php echo $edit_row['unit_price']?>">
				  <span id="validationUnitprice"  style="color: red; display: none;">Please insert File No</span>
				  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Centre Remarks : </td>
                  <td class="uk-width-7-10"><textarea id="remarks" name="remarks" rows="5" cols="50"><?php echo $edit_row['remarks'] ?> </textarea>
				      <span id="validationRemarks"  style="color: red; display: none;">Please Remarks</span></td>
               </tr>
            </table>
         </div>
         <div class="uk-width-medium-5-10">
            <table class="uk-table uk-table-small">
			     <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Collection Pattern : </td>
                  <td class="uk-width-7-10">
						<select name="monthly" id="monthly" class="uk-width-1-1">
								<option value=""></option>
								<option value="Monthly" <?php  if($edit_row['monthly']=='Monthly') {echo 'selected';}?>>Monthly</option>
								<option value="Termly" <?php  if($edit_row['monthly']=='Termly') {echo 'selected';}?>>Termly</option>
								<option value="Half Year" <?php  if($edit_row['monthly']=='Half Year') {echo 'selected';}?>> Half Year</option>
								<option value="Annually" <?php  if($edit_row['monthly']=='Annually') {echo 'selected';}?>>Annually</option>
						</select>
				  <span id="validationUnitprice"  style="color: red; display: none;">Please select Collection Pattern</span>
				  </td>
               </tr>
			   <tr class="uk-text-small">
					<td class="uk-width-1-10 uk-text-bold" style="border:none;font-size: 11px;">Status :</td>
					<td  style="border:none;font-size:14px;"><?php echo $edit_row['status'] ?></td>
				</tr>
				<tr>
					<td style="margin-top:50px;border:none;font-size: 11px;" class="uk-width-1-10 uk-text-bold">Master's Remarks:</td>
					<td style="border:none;"><textarea id="remarks_master" name="remarks_master" rows="5" cols="50" readonly><?php echo $edit_row['remarks_master'] ?> </textarea></td>
				</tr>
			   <tr class="uk-text-small">
                           <td class="uk-width-1-10 uk-text-bold" style="border:none;font-size: 15px;"></td>
                           
				 <td class="uk-width-7-10" id="dvSSM_file">
                     <input class="uk-width-1-1" type="file" name="file_s" id="file_s"  accept=".doc, .docx, .pdf, .png, .jpg, .jpeg" disabled>
                     
<?php
if ($edit_row["doc_remarks"]!="") {
?>
<a href="admin/uploads/<?php echo $edit_row['doc_remarks']?>" target="_blank">Click to view document</a>
<?php
}
?>
                  </td> 

						</tr>
				
            </table>
         </div>
      </div>
      <br>
      <div class="uk-text-center">
<?php
if (($_SESSION["UserType"]=="A") & (hasRightGroupXOR($_SESSION["UserName"], "AddOnProductEdit"))) {
?>
         <button class="uk-button uk-button-primary">Save</button>
<?php
}
?>
      </div>
<?php
if (($_SESSION["UserType"]=="A") & (hasRightGroupXOR($_SESSION["UserName"], "AddOnProductEdit"))) {
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
   <form class="uk-form" name="frmProductSearch" id="frmProductSearch" method="get">
      <input type="hidden" name="mode" id="mode" value="BROWSE">
      <input type="hidden" name="p" id="p" value="<?php echo $p?>">
      <input type="hidden" name="m" id="m" value="<?php echo $m?>">
      <input type="hidden" name="pg" value="">

      <div class="uk-grid">
         <div class="uk-width-2-10">
            <input name="product_name_code" id="product_name_code" class="uk-width-1-1" placeholder="Product Code/Name" value="<?php echo $_GET["product_name_code"]?>">
         </div>
         <input style="" type="text" class="start_date" name="start_date" id="start_date" placeholder="Start Date" data-uk-datepicker="{format: 'YYYY-MM-DD'}" value="<?php echo $_GET["start_date"]?>" autocomplete="off">
         <div class="uk-width-2-10">
         <input style="" type="text" class="end_date" name="end_date" id="end_date" placeholder="End Date" data-uk-datepicker="{format: 'YYYY-MM-DD'}" value="<?php echo $_GET["end_date"]?>" autocomplete="off">
         </div>
         <div class="uk-width-2-10">
         <select name="status" id="status" class="uk-width-1-1">
               <option value="">Status</option>
               <option value="Pending" <?php  if( $_GET["status"]=='Pending') {echo 'selected';}?>>Pending</option>
               <option value="Approved" <?php  if( $_GET["status"]=='Approved') {echo 'selected';}?>>Approved</option>
               <option value="Rejected" <?php  if( $_GET["status"]=='Rejected') {echo 'selected';}?>>Rejected</option>
         </select>
         </div>
         <div class="uk-width-2-10">
            <button class="uk-button uk-width-1-1 blue_button">Search</button>
         </div>
      </div>
   </form><br>

   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color">Listing</h2>
   </div>
   <div class="uk-overflow-container">
   <table class="uk-table" id="mydatatable" style="width: 100%;font-size:12px;">
      <thead>
         <tr class="uk-text-bold uk-text-small">
            <td>Product Code</td>
            <td>Product Name</td>
            <td>UOM</td>
            <td>Unit Price</td>
            <td>Collection Pattern</td>
            <td>Status</td>
            <td>Remarks</td>
            <td>Master's Remarks</td>
            <td>Attachment</td>
            <td style="min-width:50px;">Action</td>
         </tr>
      </thead>
      <tbody>

      </tbody>
   </table>
   </div>

</div>

<form name="frmDeleteRecord" id="frmDeleteRecord" method="get">
   <input type="hidden" name="p" value="<?php echo $p?>">
   <input type="hidden" name="m" value="<?php echo $m?>">
   <input type="hidden" name="id" id="id" value="">
   <input type="hidden" name="mode" value="DEL">
</form>
<div id="dlgProductCourse"></div>
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
<script>
$(document).ready(function(){
  $("#frmAddOnProduct").submit(function(e){
    
    var product_code=$("#product_code").val();
    var product_name=$("#product_name").val();
    var uom=$("#uom").val();
    var unit_price=$("#unit_price").val();
	
    if (!product_code || !product_name || !uom || unit_price=="") {

   if (!product_code) {
            $('#validationProductCode').show();
        }else{
            $('#validationProductCode').hide();
        }

         if (!product_name) {
            $('#validationProductName').show();
        }else{
            $('#validationProductName').hide();
        }

         if (!uom) {
            $('#validationUom').show();
        }else{
            $('#validationUom').hide();
        }
  
		if (unit_price=="") {
            $('#validationUnitprice').show();
        }else{
            $('#validationUnitprice').hide();
        }
        return false;
  }
 
  });
});

</script>

<script type="text/javascript">

   $(document).ready(function(){
      $('#mydatatable').DataTable({
         'columnDefs': [ { 
         'targets': [0,9], // column index (start from 0)
         'orderable': false, // set orderable false for selected columns
      }],
      "order": [[ 8, "asc" ]],
      "bProcessing": true,
      "bServerSide": true,
      "sAjaxSource": "admin/serverresponse/addon_product.php?product_name_code=<?php echo $_GET['product_name_code']; ?>&start_date=<?php echo $_GET['start_date']; ?>&end_date=<?php echo $_GET['end_date']; ?>&status=<?php echo $_GET['status']; ?>&p=<?php echo $_GET['p']; ?>"
      });
   }); 

</script>

<style>
   .btn-qdees{
      display: none;
   }
</style>