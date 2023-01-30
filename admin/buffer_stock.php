<!--<a href="/">                 
         <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>-->
<span style="position: absolute;right: 35px;">
    <span class="page_title"><img src="/images/title_Icons/Defective_Status.png">Buffer Stock</span>
</span>

<?php
include_once("../mysql.php");
include_once("lib/pagination/pagination.php");

include_once("buffer_stock _func.php");

if ($_SESSION["isLogin"]==1) {
   if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "StockAdjustmentEdit"))) {
?>

<div class="uk-margin-top uk-margin-right uk-form" style="margin-top:40px!important;">
    <div class="uk-width-1-1 myheader">
        <h2 class="uk-text-center myheader-text-color">Buffer Stock</h2>
    </div>
    <form name="frmSlotCollectionManagement" id="frmSlotCollectionManagement" method="post" action="index.php?p=buffer_stock&mode=SAVE" enctype="multipart/form-data">
        <div style="overflow: unset;" class="uk-overflow-container">
            <div class="uk-grid">
                <div class="uk-width-medium-5-10">
                    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.min.css"> -->
                    <table class="uk-table uk-table-small">
                        <input type="hidden" name="hidden_id" id="hidden_id" value="<?php echo sha1($edit_row['id'])?>">
                        <tbody>
                            <tr class="uk-text-small">
                                <td class="uk-width-3-10 uk-text-bold">Country</td>
                                <td class="uk-width-7-10">
                                <select name="country" id="country" class="uk-width-1-1 ">
                                        <option value="">Select</option>
                                        <?php
                       $sql="SELECT * from codes where module='COUNTRY' order by code";
                       $result=mysqli_query($connection, $sql);
                       while ($row=mysqli_fetch_assoc($result)) {
                       ?>
                                        <option value="<?php echo $row["code"]?>"
                                            <?php if ($row["code"]==$edit_row["country"]) {echo "selected";}?>>
                                            <?php echo $row["code"]?></option>
                                        <?php } ?>
                                    </select>

                                    <span id="validationCountry" style="color: red; display: none;">Please select
                                        Country</span>
                                </td>
                            </tr>
                            <tr class="uk-text-small">
                                <td class="uk-width-3-10 uk-text-bold">State </td>
                                <td class="uk-width-7-10"> 
                                <select name="state" id="state" class="uk-width-1-1 ">
                                        <?php if ($edit_row["country"]!="") { ?>
                                        <option value="">Select</option>
                                        <?php
                            $sql="SELECT * from codes where country='".$edit_row["country"]."' and module='STATE' order by code";
                            $result=mysqli_query($connection, $sql);
                            while ($row=mysqli_fetch_assoc($result)) {
                         ?>
                                        <option value="<?php echo $row['code']?>"
                                            <?php if ($row["code"]==$edit_row['state']) {echo "selected";}?>>
                                            <?php echo $row["code"]?></option>
                                        <?php
                            }
                         } else {
                         ?>
                                        <option value="">Please Select Country First</option>
                                        <?php } ?>
                                    </select>
                                    <span id="validationState" style="color: red; display: none;">Please select
                                        State</span>
                                 </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="uk-width-medium-5-10">
                    <table class="uk-table uk-table-small">

                        <tbody>
                            <tr class="uk-text-small">
                                <td class="uk-width-3-10 uk-text-bold">Buffer Amount </td>
                                <td>
                                    <input class="uk-width-1-1" placeholder="" type="text" name="buffer_amount"
                                        id="buffer_amount" value="<?php echo $edit_row['buffer_amount']?>">
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="uk-text-center">
                <!-- <button class="uk-button uk-button-primary">Save</button> -->
                <input type="submit" class="uk-button uk-button-primary aa" value="Save"/>
            </div>

        </div>
    </form>
</div>
<div class="uk-form uk-margin-top uk-margin-right">
    <div class="uk-width-1-1 myheader">
        <h2 class="uk-text-center myheader-text-color">Searching</h2>
    </div>
    <form class="uk-form" name="frmSlotCollectionManagement" id="frmSlotCollectionManagement" method="get">
            <input type="hidden" name="p" id="p" value="buffer_stock">
            <input type="hidden" name="mode" id="mode" value="BROWSE">
            <input type="hidden" name="pg" value="">
    <div style="overflow: unset;" class="uk-overflow-container">
        <div class="uk-grid uk-grid-small">
            <div class="uk-width-medium-2-10">
            <select name="country" id="country_search" class="uk-width-1-1 ">
                                        <option value="">Select</option>
                                        <?php
                       $sql="SELECT * from codes where module='COUNTRY' order by code";
                       $result=mysqli_query($connection, $sql);
                       while ($row=mysqli_fetch_assoc($result)) {
                       ?>
                                        <option value="<?php echo $row["code"]?>"
                                            <?php if ($row["code"]==$_GET["country"]) {echo "selected";}?>>
                                            <?php echo $row["code"]?></option>
                                        <?php } ?>
                                    </select>
            </div>
            <div class="uk-width-medium-2-10">
                 <select name="state" id="state_search" class="uk-width-1-1 ">
                                        <?php if ($_GET["country"]!="") { ?>
                                        <option value="">Select</option>
                                        <?php
                            $sql="SELECT * from codes where country='".$_GET["country"]."' and module='STATE' order by code";
                            $result=mysqli_query($connection, $sql);
                            while ($row=mysqli_fetch_assoc($result)) {
                         ?>
                                        <option value="<?php echo $row['code']?>"
                                            <?php if ($row["code"]==$_GET['state']) {echo "selected";}?>>
                                            <?php echo $row["code"]?></option>
                                        <?php
                            }
                         } else {
                         ?>
                                        <option value="">Please Select Country First</option>
                                        <?php } ?>
                    </select>
            </div>
            <div class="uk-width-medium-2-10">
            <input class="uk-width-1-1" placeholder="" type="text" name="buffer_amount"
                                        id="buffer_amount" value="<?php echo $_GET['buffer_amount']?>">
            </div>
           
            <div class="uk-width-medium-2-10">
            <button class="uk-button uk-width-1-1">Search</button>
            </div><br><br>
            <!-- <div class="uk-text-muted">* List latest 50 records, newest first</div> -->
        </div>
    </div>
    </form>
</div>
<br>
<div class="uk-width-1-1 myheader">
    <h2 class="uk-text-center myheader-text-color">Listing</h2>
</div>
<div class="uk-overflow-container">
<?php    
$numperpage = 20;
         $query = "p=$p&m=$m&s=$s";
         $pagination = getPagination($pg, $numperpage, $query, $browse_sql, $start_record, $num_row);
         $browse_sql .= " limit $start_record, $numperpage";
         //echo $browse_sql; 
         //die;
         $browse_result = mysqli_query($connection, $browse_sql);
         $browse_num_row = mysqli_num_rows($browse_result);
         ?>

         <table class="uk-table uk-table-striped">
            <tr class="uk-text-bold uk-text-small">
               <!-- <td>Country</td> -->
               <td>Country</td>
               <td>State </td>
               <td>Buffer Amount</td>
               <td>Action</td>
            </tr>
            <?php
            if ($browse_num_row > 0) {
               while ($browse_row = mysqli_fetch_assoc($browse_result)) {
                  $sha1_id = sha1($browse_row["id"]);
            ?>
                  <tr class="uk-text-small">
                     <td><?php echo $browse_row["country"] ?></td>
                     <td><?php echo $browse_row["state"] ?></td>
                     <td><?php echo $browse_row["buffer_amount"] ?></td>
                     <td>
                        <a href="index.php?p=buffer_stock&id=<?php echo $sha1_id ?>&mode=EDIT"><img src="images/edit.png"></a>
                        <a onclick="doDeleteRecord('<?php echo $sha1_id ?>')" href="#" id="btnDelete"><img src="images/delete.png"></a>
                     </td>
                  </tr>
            <?php
               }
            } else {
               echo "<tr><td colspan='6'>No Record Found</td></tr>";
            }
            ?>
         </table>
         <?php
         echo $pagination;
         ?>
</div>
<form name="frmDeleteRecord" id="frmDeleteRecord" method="get">
         <input type="hidden" name="p" value="buffer_stock">
         <input type="hidden" name="id" id="id" value="">
         <input type="hidden" name="mode" value="DEL">
      </form>
<script>
    function doDeleteRecord(id) {
    UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function() {
        $("#id").val(id);
        $("#frmDeleteRecord").submit();
    });
    }
</script>
<?php
   }
}
if ($msg != "") {
    echo "<script>UIkit.notify('$msg')</script>";
    echo "<script>setTimeout(
        function() 
        {
            window.location.replace('/index.php?p=buffer_stock');
        }, 2000);</script>";
}
?>
<script>
    function onCountryChange(country) {
        if (country != "") {
            $.ajax({
                url: "admin/get_state.php",
                type: "POST",
                data: "country=" + country,
                dataType: "text",
                beforeSend: function(http) {

                },
                success: function(response, status, http) {
                    if (response != "") {
                        $("#state").html(response);
                    } else {
                        $("#state").html(
                            "<select name='state' id='state' class='uk-width-1-1'><option value=''>Please select a country</option></select>"
                        );
                        UIkit.notify("No state found in " + country);
                    }
                },
                error: function(http, status, error) {
                    UIkit.notification("Error:" + error);
                }
            });

        } //country
    }
    function onCountryChangeSearch(country) {
        if (country != "") {
            $.ajax({
                url: "admin/get_state.php",
                type: "POST",
                data: "country=" + country,
                dataType: "text",
                beforeSend: function(http) {

                },
                success: function(response, status, http) {
                    if (response != "") {
                        $("#state_search").html(response);
                    } else {
                        $("#state_search").html(
                            "<select name='state' id='state' class='uk-width-1-1'><option value=''>Please select a country</option></select>"
                        );
                        UIkit.notify("No state found in " + country);
                    }
                },
                error: function(http, status, error) {
                    UIkit.notification("Error:" + error);
                }
            });

        } //country
    }

    $(document).ready(function() {
        $("#country").change(function() {
            var country = $("#country").val();
            onCountryChange(country);
        });
        $("#country_search").change(function() {
            var country = $("#country_search").val();
            onCountryChangeSearch(country);
        });
        //onCountryChange($("#country").val());
    });
    </script>
<style>
.uk-button.uk-button-primary.aa {
    margin-top: 20px
}
* + .uk-table {
    margin-top: 6px;
}
</style>