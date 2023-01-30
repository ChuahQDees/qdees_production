<?php

if ($_SESSION["isLogin"]==1) {
   if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "AddOnProductEdit|AddOnProductView"))) {
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

      include_once("addon_product_approve_func.php");
	  
	  // $edit_sql="SELECT * from `addon_product` where sha1(id)='$get_sha1_id'";
	 
      // $result=mysqli_query($connection, $edit_sql);
      // $edit_row=mysqli_fetch_assoc($result);
?>

<script>
function doDeleteRecord(id) {
    UIkit.modal.confirm("<h2>The deleted product will not affect the current payment that has been made</h2>",
    function() {
        $("#id").val(id);
        $("#frmDeleteRecord").submit();
    });
}

function doSave() {
    $("#")
}
</script>
<span class="sad_l">
    <a href="./index.php?p=fee"><span id="form_1" class="form_1">Default Fee</span></a>
    <a style="" href="./index.php?p=fee_approve"><span id="form_2" class="form_2">Request Fee</span></a>
    <a style="" href="./index.php?p=addon_approve"><span id="form_2" class="form_2">Add On Product Request</span></a>
</span>
<span>
    <span class="page_title"><img src="/images/title_Icons/Visitor Reg.png">Add-on Product</span>
</span>
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
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "AddOnProductEdit"))) {
?>
        <form name="frmAddOnProduct" id="frmAddOnProduct" method="post" class="uk-form uk-form-small"
            action="index.php?p=addon_approve&pg=<?php echo $pg?>&mode=EDIT" enctype="multipart/form-data">
            <?php
}
?>
            <div class="uk-grid">
                <div class="uk-width-medium-5-10">
                    <table class="uk-table uk-table-small">
                        <tr class="uk-text-small">
                            <td class="uk-width-3-10 uk-text-bold">Product Code : </td>
                            <td class="uk-width-7-10">
                                <input class="uk-width-1-1" type="text" name="product_code" id="product_code"
                                    value="<?php echo $edit_row['product_code']?>" readonly>
                                <input type="hidden" name="hidden_product_code" id="hidden_product_code"
                                    value="<?php echo $edit_row['product_code']?>">
                                <input type="hidden" name="hidden_product_id" id="hidden_product_id"
                                    value="<?php echo $edit_row['id']?>">
                                <input type="hidden" name="hidden_id" id="hidden_id"
                                    value="<?php echo sha1($edit_row['id'])?>">
                                <span id="validationProductCode" style="color: red; display: none;">Please insert File
                                    No</span>

                            </td>
                        </tr>
                        <tr class="uk-text-small">
                            <td class="uk-width-3-10 uk-text-bold">Product Name : </td>
                            <td class="uk-width-7-10">
                                <input class="uk-width-1-1" type="text" name="product_name" id="product_name"
                                    value="<?php echo $edit_row['product_name']?>" readonly>
                                <span id="validationProductName" style="color: red; display: none;">Please insert File
                                    No</span>
                            </td>
                        </tr>
                        <!--<tr>
					<td style="margin-top:50px;border:none;" class="uk-width-1-10 uk-text-bold">Remarks:</td>
					<td style="border:none;"><textarea id="remarks" name="remarks" rows="5" cols="50" readonly><?php// echo $edit_row['remarks'] ?> </textarea></td>
				</tr>-->
                        <tr class="uk-text-small">
                            <td class="uk-width-1-10 uk-text-bold" style="border:none;font-size: 11px;">Status :</td>
                            <td style="border:none;font-size:14px;"><?php echo $edit_row['status'] ?></td>
                        </tr>
                        <tr>
                            <td style="margin-top:50px;border:none;font-size: 11px;" class="uk-width-1-10 uk-text-bold">Master's
                                Remarks:</td>
                            <td style="border:none;"><textarea id="remarks_master" name="remarks_master" rows="5"
                                    cols="50"><?php echo $edit_row['remarks_master'] ?> </textarea></td>
                        </tr>
                        <tr class="uk-text-small">
                            <td class="uk-width-1-10 uk-text-bold" style="border:none;font-size: 11px;"></td>

                            <td class="uk-width-7-10" id="dvSSM_file">
                                <input class="uk-width-1-1" type="file" name="file_s" id="file_s"
                                    accept=".doc, .docx, .pdf, .png, .jpg, .jpeg">

                                <?php
if ($edit_row["doc_remarks"]!="") {
?>
                                <a href="admin/uploads/<?php echo $edit_row['doc_remarks']?>" target="_blank">Click to
                                    view document</a>
                                <?php
}
?>
                            </td>

                        </tr>

                    </table>
                </div>
                <div class="uk-width-medium-5-10">
                    <table class="uk-table uk-table-small">
                        <tr class="uk-text-small">
                            <td class="uk-width-3-10 uk-text-bold">UOM : </td>
                            <td class="uk-width-7-10"><input class="uk-width-1-1" type="text" name="uom" id="uom"
                                    value="<?php echo $edit_row['uom']?>" readonly>
                                <span id="validationUom" style="color: red; display: none;">Please insert File No</span>
                            </td>
                        </tr>
                        <tr class="uk-text-small">
                            <td class="uk-width-3-10 uk-text-bold">Unit Price : </td>
                            <td class="uk-width-7-10"><input class="uk-width-1-1" type="number" name="unit_price"
                                    id="unit_price" step="0.01" value="<?php echo $edit_row['unit_price']?>" readonly>
                                <span id="validationUnitprice" style="color: red; display: none;">Please insert File
                                    No</span>
                            </td>
                        </tr>
                        <tr class="uk-text-small">
                            <td class="uk-width-3-10 uk-text-bold">Collection Pattern : </td>
                            <td class="uk-width-7-10">
                                <input class="uk-width-1-1" type="text" name="monthly" id="monthly" step="0.01"
                                    value="<?php echo $edit_row['monthly']?>" readonly>
                                <!--<select name="monthly" id="monthly" class="uk-width-1-1" readonly>
								<option value=""></option>
								<option value="Monthly" <?php  if($edit_row['monthly']=='Monthly') {echo 'selected';}?>>Monthly</option>
								<option value="Termly" <?php  if($edit_row['monthly']=='Termly') {echo 'selected';}?>>Termly</option>
								<option value="Half Year" <?php  if($edit_row['monthly']=='Half Year') {echo 'selected';}?>> Half Year</option>
								<option value="Annually" <?php  if($edit_row['monthly']=='Annually') {echo 'selected';}?>>Annually</option>
						</select>-->
                                <span id="validationUnitprice" style="color: red; display: none;">Please select
                                    Collection Pattern</span>
                            </td>
                        </tr>


                    </table>
                </div>
            </div>
            <br>
            <div class="uk-text-center">
                <?php
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "AddOnProductEdit"))) {
?>
                <div class="uk-grid">
                    <div class="uk-text-right uk-width-medium-5-10">
                        <button style="background-color: greenyellow;" class="uk-button  green " name="actionButton"
                            value="Approved">Approve</button>
                    </div>
                    <div class="uk-text-left uk-width-medium-5-10">
                        <button style="background-color: red;" class="uk-button  red " name="actionButton"
                            value="Rejected">Reject</button>
                    </div>
                </div>
                <?php
}
?>
            </div>
            <?php
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "AddOnProductEdit"))) {
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
    <?php
 $numperpage=20;
$query="p=$p&m=$m&s=$s&status=$status&start_date=$start_date&end_date=$end_date&cm=$cm";
 $pagination=getPagination($pg, $numperpage, $query, $browse_sql, $start_record, $num_row);
 $browse_sql.=" limit $start_record, $numperpage";
$browse_result=mysqli_query($connection, $browse_sql);
$browse_num_row=mysqli_num_rows($browse_result);
//echo $pagination;
?>
    <form class="uk-form" name="frmProductSearch" id="frmProductSearch" method="get">
        <input type="hidden" name="mode" id="mode" value="BROWSE">
        <input type="hidden" name="p" id="p" value="<?php echo $p?>">
        <input type="hidden" name="m" id="m" value="<?php echo $m?>">
        <input type="hidden" name="pg" value="">

        <div class="uk-grid">
            <!-- <div class="uk-width-7-10">
            <input name="s" id="s" class="uk-width-1-1" placeholder="Product Code/Product Name" value="<?php echo $_GET["s"]?>">
         </div> -->
            <div style="padding-right: 0px!important;" class="uk-width-2-10">
                <input type="text" name="cm" id="cm" value="<?php echo $_GET['cm']?>" placeholder="Centre Name">
            </div>
            <input style="" type="text" class="start_date" name="start_date" id="start_date" placeholder="Start Date"
                data-uk-datepicker="{format: 'YYYY-MM-DD'}" value="<?php echo $_GET["start_date"]?>" autocomplete="off">
            <div class="uk-width-2-10">
                <input style="" type="text" class="end_date" name="end_date" id="end_date" placeholder="End Date"
                    data-uk-datepicker="{format: 'YYYY-MM-DD'}" value="<?php echo $_GET["end_date"]?>"
                    autocomplete="off">
            </div>
            <div class="uk-width-2-10">
                <select name="status" id="status" class="uk-width-1-1">
                    <option value="">Status</option>
                    <option value="Pending" <?php  if( $_GET["status"]=='Pending') {echo 'selected';}?>>Pending</option>
                    <option value="Approved" <?php  if( $_GET["status"]=='Approved') {echo 'selected';}?>>Approved
                    </option>
                    <option value="Rejected" <?php  if( $_GET["status"]=='Rejected') {echo 'selected';}?>>Rejected
                    </option>
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
        <table class="uk-table" id="mydatatable">
            <tr class="uk-text-bold uk-text-small">
                <td>Submission Date</td>
                <td>Centre name</td>
                <td>Product Code</td>
                <td>Product Name</td>
                <td>UOM</td>
                <td>Unit Price</td>
                <td>Collection Pattern</td>
                <td>Status</td>
                <td>Master's Remarks</td>
                <td>Attachment</td>
                <td>Action</td>
            </tr>
            <?php
if ($browse_num_row>0) {
   while ($browse_row=mysqli_fetch_assoc($browse_result)) {
      $sha1_id=sha1($browse_row["id"]);
?>
            <tr class="uk-text-small">
                <td><?php echo $browse_row["submission_date"]?></td>
                <td><?php echo $browse_row["company_name"]?></td>
                <td><?php echo $browse_row["product_code"]?></td>
                <td><?php echo $browse_row["product_name"]?></td>
                <td><?php echo $browse_row["uom"]?></td>
                <td><?php echo $browse_row["unit_price"]?></td>
                <td><?php echo $browse_row["monthly"]?></td>
                <td><?php echo $browse_row["status"]?></td>
                <td><?php echo $browse_row["remarks_master"]?></td>
                <td><a href="admin/uploads/<?php echo $browse_row["doc_remarks"]?>"
                        target="_blank"><?php echo $browse_row["doc_remarks"]?></a></td>

                <td>
                    <a href="index.php?p=<?php echo $p?>&m=<?php echo $m?>&id=<?php echo $sha1_id?>&mode=EDIT"
                        data-uk-tooltip title="Edit"></i> <img style="width:20px;margin-left: 12px;"
                            src="images/request.png"></a>

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
$(document).ready(function() {
    $('#mydatatable').DataTable({
        language: {
            // searchPlaceholder: "Search Class Status",
            //  search: ""
        },
        // "bInfo": false,
    });
});
$('#file_s').bind('change', function() {
            var file_path = $(this).val();
            if (file_path != "") {
                if (this.files[0].size > 10485760) {
                    UIkit.notify("Attachment file size too big.");
                }

                var file = file_path.split(".");
                var count = file.length;
                var index = count > 0 ? (count - 1) : 0;
                var file_ext = file[index].toLowerCase();

                if ((file_ext != "docx") && (file_ext != "doc") && (file_ext != "pdf") && (file_ext !=
                        "jpg") && (file_ext != "png") && (file_ext != "jpeg")) {
                    $('#file_s').val('');
                    UIkit.notify("Please select DOC, DOCX or PDF file only");
                }
            }
        }
        $(document).ready(function() {
            $("#frmAddOnProduct").submit(function(e) {

                var product_code = $("#product_code").val();
                var product_name = $("#product_name").val();
                var uom = $("#uom").val();
                var unit_price = $("#unit_price").val();

                if (!product_code || !product_name || !uom || unit_price == "") {

                    if (!product_code) {
                        $('#validationProductCode').show();
                    } else {
                        $('#validationProductCode').hide();
                    }

                    if (!product_name) {
                        $('#validationProductName').show();
                    } else {
                        $('#validationProductName').hide();
                    }

                    if (!uom) {
                        $('#validationUom').show();
                    } else {
                        $('#validationUom').hide();
                    }

                    if (unit_price == "") {
                        $('#validationUnitprice').show();
                    } else {
                        $('#validationUnitprice').hide();
                    }
                    return false;
                }

            });
        });
</script>
<style type="text/css">
#mydatatable_length {
    display: none;
}

#mydatatable_filter {
    display: none;
}

/*#mydatatable_paginate{float:initial;text-align:center}*/
#mydatatable_paginate .paginate_button {
    display: inline-block;
    min-width: 16px;
    padding: 3px 5px;
    line-height: 20px;
    text-decoration: none;
    -moz-box-sizing: content-box;
    box-sizing: content-box;
    text-align: center;
    background: #f7f7f7;
    color: #444444;
    border: 1px solid rgba(0, 0, 0, 0.2);
    border-bottom-color: rgba(0, 0, 0, 0.3);
    background-origin: border-box;
    background-image: -webkit-linear-gradient(top, #ffffff, #eeeeee);
    background-image: linear-gradient(to bottom, #ffffff, #eeeeee);
    text-shadow: 0 1px 0 #ffffff;
    margin-left: 3px;
    margin-right: 3px
}

#mydatatable_paginate .paginate_button.current {
    background: #009dd8;
    color: #ffffff !important;
    border: 1px solid rgba(0, 0, 0, 0.2);
    border-bottom-color: rgba(0, 0, 0, 0.4);
    background-origin: border-box;
    background-image: -webkit-linear-gradient(top, #00b4f5, #008dc5);
    background-image: linear-gradient(to bottom, #00b4f5, #008dc5);
    text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.2);
}

#mydatatable_filter {
    width: 100%
}

#mydatatable_filter label {
    width: 100%;
    display: inline-flex
}

#mydatatable_filter label input {
    height: 30px;
    width: 100%;
    padding: 4px 6px;
    border: 1px solid #dddddd;
    background: #ffffff;
    color: #444444;
    -webkit-transition: all linear 0.2s;
    transition: all linear 0.2s;
    border-radius: 4px;
}

/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Firefox */
input[type=number] {
    -moz-appearance: textfield;
}

.btn-qdees {
    display: none;
}

.red {
    background: red !important;
    color: white !important;
    padding: .3em 2em;
    background-color: red;
}

.green {
    background: green !important;
    color: white !important;
    padding: .3em 2em;
    background-color: green;
}

.page_title {
    position: absolute;
    right: 34px;
}

.uk-margin-right {
    margin-top: 40px;
}

.page_title {
    position: absolute;
    right: 34px;
}

.uk-margin-right {
    margin-top: 40px;
}

.form_1 {
    padding: 9px 15px;
    border-radius: 10px;
    box-shadow: 0px 2px 3px 0px #00000021 !important;
    font-size: 1.2rem;
    font-weight: bold;
    background: #fff;
    cursor: pointer;
}

.form_2 {
    padding: 9px 15px;
    border-radius: 10px;
    box-shadow: 0px 2px 3px 0px #00000021 !important;
    font-size: 1.2rem;
    font-weight: bold;
    background: #fff;
}
</style>