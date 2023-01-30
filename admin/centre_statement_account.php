<!--<a href="/">                 
         <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>-->

<span style="position: absolute;right: 35px;" class="btnPrintOff">
    <span class="page_title"><img src="/images/title_Icons/Defective_Status.png">Centre Statement Account</span>
</span>
<div style="float:left" class="btnPrintOff">
    <button style="margin-left: 30px;margin-bottom: 13px; position: relative; bottom: 40px;  " onclick="printDiv('print_1')"
        class="uk-button">Print</button>
</div>

<?php
include_once("../mysql.php");
include_once("lib/pagination/pagination.php");

include_once("centre_statement_account_func.php");

if ($_SESSION["isLogin"]==1) {
  
?>

<script>
$(document).ready(function() {
    searchAdjustment();
});
function printDiv(print_1) {
   
   $("#sidebar").hide();
   //$(".navbar-info").attr('style', 'display: none !important');
   $(".navbar-info").remove();
   $(".btnPrintOff").hide();
   $(".footer").hide();

    window.print();
    $("#sidebar").show();
    $(".navbar-info").attr('style', 'display: block');
    $(".btnPrintOff").show(); 
    $(".footer").show();
    location.reload();
   // document.body.innerHTML = originalContents;
}

function doStockAdjustment() {
    var product_code = $("#product_code").val();
    var effective_date = $("#effective_date").val();
    var adjust_qty = $("#adjust_qty").val();
    var centre_code = $("#centre_code").val();
    var file = $("#file").val();

    //alert(file);

    if ((product_code != "") & (effective_date != "") & (adjust_qty != "") & (centre_code != "")) {
        $.ajax({
            url: "admin/doStockAdjustment.php",
            type: "POST",
            data: "product_code=" + product_code + "&effective_date=" + effective_date + "&adjust_qty=" +
                adjust_qty + "&centre_code=" + centre_code + "&file=" + file,
            dataType: "text",
            beforeSend: function(http) {},
            success: function(response, status, http) {
                var s = response.split("|");
                //location.reload();
                UIkit.notify(s[1]);
            },
            error: function(http, status, error) {
                UIkit.notify("Error:" + error);
            }
        });
    } else {
        UIkit.notify("Please fill in all fields");
    }
}

function searchAdjustment() {
    var centre_code = $("#centre_code").val();
    var product_code = $("#product_code").val();
    var date_from = $("#date_from").val();
    var date_to = $("#date_to").val();

    $.ajax({
        url: "admin/SearchAdjustment.php",
        type: "POST",
        data: "product_code=" + product_code + "&centre_code=" + centre_code,
        dataType: "text",
        beforeSend: function(http) {},
        success: function(response, status, http) {
            $("#sctAdjustment").html(response);
        },
        error: function(http, status, error) {
            UIkit.notify("Error:" + error);
        }
    });
}
</script>
<div class="uk-margin-top uk-margin-right uk-form" style="margin-top:40px!important;">
    <div class="uk-width-1-1 myheader">
        <h2 class="uk-text-center myheader-text-color">Centre Statement Account</h2>
    </div>
    <div style="overflow: unset;" class="uk-overflow-container">

    <?php  if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "CentreStatementAccountEdit"))) { ?>
        <form name="frmCentreStatementAccount" id="frmCentreStatementAccount" method="post" action="index.php?p=centre_statement_account&pg=<?php echo $pg?>&mode=SAVE" enctype="multipart/form-data">
    <?php  } ?>     
            <div class="uk-grid">

                <div class="uk-width-medium-5-10">
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.min.css">
                    <table class="uk-table uk-table-small">
                        <input type="hidden" name="hidden_id" id="hidden_id" value="<?php echo sha1($edit_row['id'])?>">
                        <tbody>
                            <tr class="uk-text-small">
                                <td class="uk-width-3-10 uk-text-bold">Centre Name</td>
                                <td class="uk-width-7-10">
                                    <select class="uk-width-1-1 chosen" name="centre_code" id="centre_code" <?php if (($_SESSION["UserType"]=="A")){ echo "disabled"; } ?>>
                                        <option value="">Select</option>
                                        <?php
                        $sql="SELECT * from centre order by kindergarten_name";
                        $result=mysqli_query($connection, $sql);
                        while ($row=mysqli_fetch_assoc($result)) {
                        ?>
                                        <option value="<?php echo $row['centre_code']?>"
                                            <?php if ($row["centre_code"]==$edit_row['centre_code']) {echo "selected";}?>>
                                            <?php echo $row["company_name"]?></option>
                                        <?php
                        }
                        ?>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="uk-width-medium-5-10">
                    <table class="uk-table uk-table-small">

                        <tbody>
                            <tr class="uk-text-small">
                                <td class="uk-width-3-10 uk-text-bold">As at</td>
                                <td class="uk-width-7-10"> 

                                <?php if(($_SESSION["UserType"]=="A")){?>
                                    <input type="hidden" class="uk-width-1-1"
                                    data-uk-datepicker="{format:'YYYY-MM-DD'}" name="effective_date" id="effective_date"
                                    placeholder="" value="<?php echo $edit_row['effective_date']?>" <?php if (($_SESSION["UserType"]=="A")){ echo "readonly"; } ?>> 
                                    <?php echo $edit_row['effective_date'];
                                    ?>

                                <?php } else{ ?>
                                  
                                    <?php if( $edit_row['effective_date'] =="") {?>
                                    <input class="uk-width-1-1"
                                        data-uk-datepicker="{format:'YYYY-MM-DD'}" name="effective_date" id="effective_date"
                                        placeholder="" value="<?php echo $edit_row['effective_date']?>" <?php if (($_SESSION["UserType"]=="A")){ echo "readonly"; } ?>> 
                                <?php } else{ ?>
                                    <input type="hidden" class="uk-width-1-1"
                                    data-uk-datepicker="{format:'YYYY-MM-DD'}" name="effective_date" id="effective_date"
                                    placeholder="" value="<?php echo $edit_row['effective_date']?>" <?php if (($_SESSION["UserType"]=="A")){ echo "readonly"; } ?>> 
                                    <?php echo $edit_row['effective_date'];
                                     } ?>
                                   
                                    <?php  } ?>
                                
                                    </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>


            <!-- sec 2 -->
            <div class="uk-grid">
                <div class="uk-width-1-1 ">
                    <h2 class="uk-text-center ">Outstanding Payment (Stock)</h2>
                </div>
                <div class="uk-width-medium-5-10">
                    <table class="uk-table uk-table-small">
                        <!-- <input type="hidden" name="id" id="id" value=""> -->
                        <tbody>
                            <tr class="uk-text-small">
                                <td class="uk-width-3-10 uk-text-bold">Company Name</td>
                                <td class="uk-width-7-10">
                                    <select class="uk-width-1-1 chosen-select2" name="company_name" id="company_name" <?php if (($_SESSION["UserType"]=="A")){ echo "disabled"; } ?>>
                                        <option value="">Select</option>
                                        <option <?php if($edit_row['company_name']=="Mindspectrum Sdn. Bhd."){  echo "selected"; }?> value="Mindspectrum Sdn. Bhd.">Mindspectrum Sdn. Bhd.</option>
                                        <option <?php if($edit_row['company_name']=="Tenations Global Sdn. Bhd."){  echo "selected"; }?> value="Tenations Global Sdn. Bhd.">Tenations Global Sdn. Bhd.</option>
                                        <option <?php if($edit_row['company_name']=="Q-dees Global Pte. Ltd."){  echo "selected"; }?> value="Q-dees Global Pte. Ltd.">Q-dees Global Pte. Ltd.</option>
                                        <option <?php if($edit_row['company_name']=="Q-dees Holdings Sdn. Bhd."){  echo "selected"; }?> value="Q-dees Holdings Sdn. Bhd.">Q-dees Holdings Sdn. Bhd.</option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="uk-width-medium-5-10">
                    <table class="uk-table uk-table-small">

                        <tbody>
                            <tr class="uk-text-small">
                                <td class="uk-width-3-10 uk-text-bold">Total Outstanding </td>
                                <td class="uk-width-7-10"> <input class="uk-width-1-1" type="number" step=".01" name="total_outstanding_stock"
                                        id="total_outstanding_stock" placeholder="" min="0" oninput="this.value = Math.abs(this.value)" value="<?php echo $edit_row['total_outstanding_stock']?>" <?php if (($_SESSION["UserType"]=="A")){ echo "readonly"; } ?>> </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="uk-text-center uk-width-medium-10-10">
                    <!-- <button class="uk-button uk-button-primary">Save</button> -->
                    <!-- <a class="uk-button uk-button-primary aa" onclick="doStockAdjustment()">Submit</a> -->
                </div>

            </div>


            <!-- sec 3 -->
            <div class="uk-grid">
                <div class="uk-width-1-1 ">
                    <h2 class="uk-text-center ">Outstanding Payment (Others)</h2>
                </div>
                <div class="uk-width-medium-5-10">
                    <table class="uk-table uk-table-small">

                        <tbody>
                            <tr class="uk-text-small">
                                <td class="uk-width-3-10 uk-text-bold">Details </td>
                                <td class="uk-width-7-10"> <input class="uk-width-1-1" type="text" name="details"
                                        id="details" placeholder="" value="<?php echo $edit_row['details']?>" <?php if (($_SESSION["UserType"]=="A")){ echo "readonly"; } ?>> </td>
                            </tr>
                            <tr class="uk-text-small">
                                <td class="uk-width-3-10 uk-text-bold">Company Name</td>
                                <td class="uk-width-7-10">
                                    <select class="uk-width-1-1 chosen-select2" name="company_name_others" id="company_name_others" <?php if (($_SESSION["UserType"]=="A")){ echo "disabled"; } ?>>
                                        <option value="">Select</option>
                                        <option <?php if($edit_row['company_name']=="Mindspectrum Sdn. Bhd."){  echo "selected"; }?> value="Mindspectrum Sdn. Bhd.">Mindspectrum Sdn. Bhd.</option>
                                        <option <?php if($edit_row['company_name']=="Tenations Global Sdn. Bhd."){  echo "selected"; }?> value="Tenations Global Sdn. Bhd.">Tenations Global Sdn. Bhd.</option>
                                        <option <?php if($edit_row['company_name']=="Q-dees Global Pte. Ltd."){  echo "selected"; }?> value="Q-dees Global Pte. Ltd.">Q-dees Global Pte. Ltd.</option>
                                        <option <?php if($edit_row['company_name']=="Q-dees Holdings Sdn. Bhd."){  echo "selected"; }?> value="Q-dees Holdings Sdn. Bhd.">Q-dees Holdings Sdn. Bhd.</option>
                                        <?php
                        // $sql="SELECT * from centre order by kindergarten_name";
                        // $result=mysqli_query($connection, $sql);
                        // while ($row=mysqli_fetch_assoc($result)) {
                        // ?>
                                        <!-- <option value="<?php //echo $row['centre_code']?>" <?php //if ($row["centre_code"]==$edit_row['centre_code']) {echo "selected";}?>><?php //echo $row["company_name"]?></option> -->
                                        <?php
                        // }
                        ?>
                                    </select>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <div class="uk-width-medium-5-10">
                    <table class="uk-table uk-table-small">
                        <!-- <input type="hidden" name="id" id="id" value=""> -->
                        <tbody>
                            <tr class="uk-text-small">
                                <td class="uk-width-3-10 uk-text-bold">Total Outstanding </td>
                                <td class="uk-width-7-10"> <input class="uk-width-1-1" type="number" step=".01" name="total_outstanding_others"
                                        id="total_outstanding_others" placeholder="" value="<?php echo $edit_row['total_outstanding_others']?>" min="0" oninput="this.value = Math.abs(this.value)" 
                                        <?php if(( $_GET['mode'] =="EDIT") || ($_SESSION["UserType"]=="A")){
                                            echo "readonly >";
                                         } ?> </td>
                                    </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

                <div class="uk-text-center uk-width-medium-10-10">
                    <!-- <button class="uk-button uk-button-primary">Save</button> -->
                    <!-- <a class="uk-button uk-button-primary aa" onclick="doStockAdjustment()">Submit</a> -->
                </div>

            </div>


            <!-- sec 3 -->
            <div class="uk-grid">
                <div class="uk-width-1-1 ">
                    <h2 class="uk-text-center ">Royalty Status</h2>
                </div>
                <div class="uk-width-medium-5-10">
                    <table class="uk-table uk-table-small">

                        <tbody>

                            <tr class="uk-text-small">
                                <td class="uk-width-3-10 uk-text-bold">January</td>
                                <td class="uk-width-7-10">
                                    <select class="uk-width-1-1 chosen-select2" name="royalty_january" id="royalty_january" <?php if (($_SESSION["UserType"]=="A")){ echo "disabled"; } ?>>
                                        <option value="">Select</option>
                                        <option <?php if($edit_row['royalty_january']=="Paid"){ echo "selected"; }?> value="Paid">Paid</option>
                                        <option <?php if($edit_row['royalty_january']=="Pending"){ echo "selected"; }?> value="Pending">Pending</option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="uk-text-small">
                                <td class="uk-width-3-10 uk-text-bold">February</td>
                                <td class="uk-width-7-10">
                                    <select class="uk-width-1-1 chosen-select2" name="royalty_february" id="royalty_february" <?php if (($_SESSION["UserType"]=="A")){ echo "disabled"; } ?>>
                                        <option value="">Select</option>
                                       <option <?php if($edit_row['royalty_february']=="Paid"){ echo "selected"; }?> value="Paid">Paid</option>
                                        <option <?php if($edit_row['royalty_february']=="Pending"){ echo "selected"; }?> value="Pending">Pending</option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="uk-text-small">
                                <td class="uk-width-3-10 uk-text-bold">March</td>
                                <td class="uk-width-7-10">
                                    <select class="uk-width-1-1 chosen-select2" name="royalty_march" id="royalty_march" <?php if (($_SESSION["UserType"]=="A")){ echo "disabled"; } ?>>
                                        <option value="">Select</option>
                                       <option <?php if($edit_row['royalty_march']=="Paid"){ echo "selected"; }?> value="Paid">Paid</option>
                                        <option <?php if($edit_row['royalty_march']=="Pending"){ echo "selected"; }?> value="Pending">Pending</option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="uk-text-small">
                                <td class="uk-width-3-10 uk-text-bold">April</td>
                                <td class="uk-width-7-10">
                                    <select class="uk-width-1-1 chosen-select2" name="royalty_april" id="royalty_april" <?php if (($_SESSION["UserType"]=="A")){ echo "disabled"; } ?>>
                                        <option value="">Select</option>
                                       <option <?php if($edit_row['royalty_april']=="Paid"){ echo "selected"; }?> value="Paid">Paid</option>
                                        <option <?php if($edit_row['royalty_april']=="Pending"){ echo "selected"; }?> value="Pending">Pending</option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="uk-text-small">
                                <td class="uk-width-3-10 uk-text-bold">May</td>
                                <td class="uk-width-7-10">
                                    <select class="uk-width-1-1 chosen-select2" name="royalty_may" id="royalty_may" <?php if (($_SESSION["UserType"]=="A")){ echo "disabled"; } ?>>
                                        <option value="">Select</option>
                                       <option <?php if($edit_row['royalty_may']=="Paid"){ echo "selected"; }?> value="Paid">Paid</option>
                                        <option <?php if($edit_row['royalty_may']=="Pending"){ echo "selected"; }?> value="Pending">Pending</option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="uk-text-small">
                                <td class="uk-width-3-10 uk-text-bold">June</td>
                                <td class="uk-width-7-10">
                                    <select class="uk-width-1-1 chosen-select2" name="royalty_june" id="royalty_june" <?php if (($_SESSION["UserType"]=="A")){ echo "disabled"; } ?>>
                                        <option value="">Select</option>
                                       <option <?php if($edit_row['royalty_june']=="Paid"){ echo "selected"; }?> value="Paid">Paid</option>
                                        <option <?php if($edit_row['royalty_june']=="Pending"){ echo "selected"; }?> value="Pending">Pending</option>
                                    </select>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <div class="uk-width-medium-5-10">
                    <table class="uk-table uk-table-small">
                        <!-- <input type="hidden" name="id" id="id" value=""> -->
                        <tbody>
                            <tr class="uk-text-small">
                                <td class="uk-width-3-10 uk-text-bold">July</td>
                                <td class="uk-width-7-10">
                                    <select class="uk-width-1-1 chosen-select2" name="royalty_july" id="royalty_july" <?php if (($_SESSION["UserType"]=="A")){ echo "disabled"; } ?>>
                                        <option value="">Select</option>
                                       <option <?php if($edit_row['royalty_july']=="Paid"){ echo "selected"; }?> value="Paid">Paid</option>
                                        <option <?php if($edit_row['royalty_july']=="Pending"){ echo "selected"; }?> value="Pending">Pending</option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="uk-text-small">
                                <td class="uk-width-3-10 uk-text-bold">August</td>
                                <td class="uk-width-7-10">
                                    <select class="uk-width-1-1 chosen-select2" name="royalty_august" id="royalty_august" <?php if (($_SESSION["UserType"]=="A")){ echo "disabled"; } ?>>
                                        <option value="">Select</option>
                                       <option <?php if($edit_row['royalty_august']=="Paid"){ echo "selected"; }?> value="Paid">Paid</option>
                                        <option <?php if($edit_row['royalty_august']=="Pending"){ echo "selected"; }?> value="Pending">Pending</option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="uk-text-small">
                                <td class="uk-width-3-10 uk-text-bold">September</td>
                                <td class="uk-width-7-10">
                                    <select class="uk-width-1-1 chosen-select2" name="royalty_september" id="royalty_september" <?php if (($_SESSION["UserType"]=="A")){ echo "disabled"; } ?>>
                                        <option value="">Select</option>
                                       <option <?php if($edit_row['royalty_september']=="Paid"){ echo "selected"; }?> value="Paid">Paid</option>
                                        <option <?php if($edit_row['royalty_september']=="Pending"){ echo "selected"; }?> value="Pending">Pending</option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="uk-text-small">
                                <td class="uk-width-3-10 uk-text-bold">October</td>
                                <td class="uk-width-7-10">
                                    <select class="uk-width-1-1 chosen-select2" name="royalty_october" id="royalty_october" <?php if (($_SESSION["UserType"]=="A")){ echo "disabled"; } ?>>
                                        <option value="">Select</option>
                                       <option <?php if($edit_row['royalty_october']=="Paid"){ echo "selected"; }?> value="Paid">Paid</option>
                                        <option <?php if($edit_row['royalty_october']=="Pending"){ echo "selected"; }?> value="Pending">Pending</option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="uk-text-small">
                                <td class="uk-width-3-10 uk-text-bold">November</td>
                                <td class="uk-width-7-10">
                                    <select class="uk-width-1-1 chosen-select2" name="royalty_november" id="royalty_november" <?php if (($_SESSION["UserType"]=="A")){ echo "disabled"; } ?>>
                                        <option value="">Select</option>
                                       <option <?php if($edit_row['royalty_november']=="Paid"){ echo "selected"; }?> value="Paid">Paid</option>
                                        <option <?php if($edit_row['royalty_november']=="Pending"){ echo "selected"; }?> value="Pending">Pending</option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="uk-text-small">
                                <td class="uk-width-3-10 uk-text-bold">December</td>
                                <td class="uk-width-7-10">
                                    <select class="uk-width-1-1 chosen-select2" name="royalty_december" id="royalty_december" <?php if (($_SESSION["UserType"]=="A")){ echo "disabled"; } ?>>
                                        <option value="">Select</option>
                                       <option <?php if($edit_row['royalty_december']=="Paid"){ echo "selected"; }?> value="Paid">Paid</option>
                                        <option <?php if($edit_row['royalty_december']=="Pending"){ echo "selected"; }?> value="Pending">Pending</option>
                                    </select>
                                </td>
                            </tr>
                            

                        </tbody>
                    </table>
                    
                </div>
                <?php  if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "CentreStatementAccountEdit"))) { ?>
                <div class="uk-text-center uk-width-medium-10-10">
                    <!-- <button class="uk-button uk-button-primary">Save</button> -->
                    <input type="submit" class="uk-button uk-button-primary aa" value="Submit"/>
                </div>
                <?php  } ?>
               
                <div class="uk-width-medium-10-10"><br>
                <p>The Franchisee shall pay to Franchisor Royalty Fee, Advertising & Promotion Fee and Software License Fee, according to the terms and conditions stated in this declaration, in Ringgit Malaysia on a monthly basis, on the 1st day but not later than the 5th day of each month.</p>
                <p>Late payments shall be levied with an additional 1.5% charge per month. All outstanding payments MUST be settled before stocks can be released for collection. Online Payment: Cash/Cheque/TT Transfer Goods will be withheld should the franchisee be unable to produce the relevant bank-in slips for collection even though the franchisee may insist that payment has been made. Thank you for your kind understanding and co-operation.</p><br>
                </div>
            </div>
            <?php  if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "CentreStatementAccountEdit"))) { ?>
        </form>
        <?php  } ?>
    </div>
</div>
<div class="uk-form uk-margin-top uk-margin-right btnPrintOff">
    <div class="uk-width-1-1 myheader">
        <h2 class="uk-text-center myheader-text-color">Searching</h2>
    </div>
    <form class="uk-form" name="frmCentreStatementAccount" id="frmCentreStatementAccount" method="get">
            <input type="hidden" name="p" id="p" value="centre_statement_account">
            <input type="hidden" name="mode" id="mode" value="BROWSE">
            <input type="hidden" name="pg" value="">

    <div style="overflow: unset;" class="uk-overflow-container">
        <div class="uk-grid uk-grid-small">
            <div class="uk-width-medium-3-10">
            <input type="hidden" id="hfCenterCode" name="centre_code">
                <input list="centre_code1" id="company_name" name="company_name" value=""
                    placeholder="Select Centre Name" style="width:100%;">
                <datalist class="form-control" id="centre_code1" style="display:none;">
                    <!--<option value="ALL" <?php // echo $centre_code == 'ALL' ? 'selected' : '' 
					?>>ALL</option>-->

                    <?php
							$centre_code= $_GET["centre_code"];
							$sql = "SELECT * from centre order by centre_code";
							$result_centre = mysqli_query($connection, $sql);                        
					
						   while ($row=mysqli_fetch_assoc($result_centre)) {
						  ?>
                    <option value="<?php echo $row['company_name']?>" <?php if($_GET["company_name"]==$row["company_name"]){ echo 'selected';
	}?>> <?php echo $row["centre_code"]
																				?></option>
                    <?php
						   }
						  ?>
                </datalist>
                <script src="lib/uikit/js/jquery.js"></script>
                <script>
                var objCompanyName = document.getElementById('company_name');
                $(document).on('change', objCompanyName, function() {
                     //console.log("options[i].text")
                    var options = $('datalist')[0].options;
                    for (var i = 0; i < options.length; i++) {
                        // console.log($(objCompanyName).val())
                        if (options[i].value == $(objCompanyName).val()) {
                            $("#hfCenterCode").val(options[i].text);
                            break;
                        }
                    }
                });
                </script>
            </div>
           
            <div class="uk-width-medium-2-10">
                <input class="uk-width-1-1" data-uk-datepicker="{format:'YYYY-MM-DD'}" name="date_from" id="date_from"
                    placeholder="Date From" value="<?php echo $_GET["date_from"]; ?>">
            </div>
            <div class="uk-width-medium-2-10">
                <input class="uk-width-1-1" data-uk-datepicker="{format:'YYYY-MM-DD'}" name="date_to" id="date_to"
                    placeholder="Date To" value="<?php echo $_GET["date_to"]; ?>">
            </div>
           
            <div class="uk-width-medium-2-10">
            <button class="uk-button uk-width-1-1">Search</button>
            </div>
            </form><br>
        </div>

    </div>
</div>
<br>
<div class="uk-width-1-1 myheader btnPrintOff" >
    <h2 class="uk-text-center myheader-text-color">Listing</h2>
</div>
<div class="uk-overflow-container btnPrintOff">
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
               <td>Centre Name</td>
               <td>As at</td> 
               <td>Submitted By</td>
               <td>Action</td>
            </tr>
            <?php
            if ($browse_num_row > 0) {
               while ($browse_row = mysqli_fetch_assoc($browse_result)) {
                  $sha1_id = sha1($browse_row["id"]);
            ?>
                  <tr class="uk-text-small">
                     <td><?php echo $browse_row["centre_name"] ?></td>
                     <td><?php echo $browse_row["effective_date"] ?></td>
                     <td><?php echo $browse_row["created_by"] ?></td>
                     <td>
                        <a href="index.php?p=centre_statement_account&id=<?php echo $sha1_id ?>&mode=EDIT"><img src="images/edit.png"></a>
                        <?php  if ($_SESSION["UserType"]=="S") { ?>
                        <a onclick="doDeleteRecord('<?php echo $sha1_id ?>')" href="#" id="btnDelete"><img src="images/delete.png"></a>
                        <?php  } ?>
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
         <input type="hidden" name="p" value="centre_statement_account">
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
 <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.jquery.min.js"></script>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.min.css">
                <script>
                $('.chosen').chosen({
                    search_contains: true
                }).change(function(obj, result) {
                    // console.debug("changed: %o", arguments);
                    // console.log("selected: " + result.selected);
                });
                </script>
<?php
}

if ($msg != "") {
    echo "<script>UIkit.notify('$msg')</script>";
    echo "<script>setTimeout(
        function() 
        {
            window.location.replace('/index.php?p=centre_statement_account');
        }, 2000);</script>";
}
?>
<style>

.uk-form select:disabled, .uk-form textarea:disabled, .uk-form input:not([type]):disabled, .uk-form input[type="text"]:disabled, .uk-form input[type="password"]:disabled, .uk-form input[type="datetime"]:disabled, .uk-form input[type="datetime-local"]:disabled, .uk-form input[type="date"]:disabled, .uk-form input[type="month"]:disabled, .uk-form input[type="time"]:disabled, .uk-form input[type="week"]:disabled, .uk-form input[type="number"]:disabled, .uk-form input[type="email"]:disabled, .uk-form input[type="url"]:disabled, .uk-form input[type="search"]:disabled, .uk-form input[type="tel"]:disabled, .uk-form input[type="color"]:disabled {
    color: black;
}

.uk-button.uk-button-primary.aa {
    margin-top: 20px
}

*+.uk-table {
    margin-top: 7px;
}
/* .d_n{
   display: none;
} */
</style>