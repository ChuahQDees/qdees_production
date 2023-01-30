<a href="/">                 
				 <!-- <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span> -->
</a>
<span>
    <span class="page_title"><img src="/images/title_Icons/Centre Stock Balance.png">Centre Stock Balance Report</span>
</span>

<?php
include_once("../mysql.php");
include_once("functions.php");

function isMaster($master_code) {
    global $connection;
 
    $sql="SELECT * from master where master_code='$master_code'";
    $result=mysqli_query($connection, $sql);
    $num_row=mysqli_num_rows($result);
    if ($num_row>0) {
       return true;
    } else {
       return false;
    }
 }

if ($_SESSION["isLogin"]==1) {
   //if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) & (hasRightGroupXOR($_SESSION["UserName"], "StockBalancesView"))) {
?>

<div class="uk-margin-right uk-margin-top uk-form">
   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color myheader-text-style">Centre Stock Balance Report</h2>
   </div>
    <!-- <div style="background-color: white; padding: 8px" class="d-flex justify-content-center">
        <div>
        <input type="text" name="cut_off_date" id="cut_off_date" placeholder="Cut Off Date" data-uk-datepicker="{format: 'DD/MM/YYYY'}" value="<?php echo date("d/m/Y");?>">
        <a class="uk-button" onclick="generateBalReport('')">Generate</a>
        </div>
    </div> -->
    <div class="uk-overflow-container uk-form nice-form">
   <div class="d-flex uk-grid uk-grid-small">
        <div class="uk-width-medium-2-10">
        <?php
        if ((($_SESSION["UserType"]=="S") || ($_SESSION["UserType"]=="H") || ($_SESSION["UserType"]=="C") || ($_SESSION["UserType"]=="R") || ($_SESSION["UserType"]=="CM") || ($_SESSION["UserType"]=="T")) & (hasRightGroupXOR($_SESSION["UserName"], "ReportingView"))) {
           if (isMaster($_SESSION["CentreCode"])) {
              $sql="SELECT * from centre where upline='".$_SESSION["CentreCode"]."' order by centre_code";
           } else {
              $sql="SELECT * from centre order by centre_code";
           }
        } else {
           if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) & (hasRightGroupXOR($_SESSION["UserName"], "ReportingView"))) {
              $sql="";
           }
        }
        if ($sql=="") {
            ?>
              Centre Name<br>
             <input type="hidden" name="centre_code" id="centre_code"  value="<?php echo $_SESSION['CentreCode']?>">            
             <span type="text" class="uk-width-medium-1-1 uk-text-bold" ><?php echo getCentreCompanyName($_SESSION['CentreCode'])?></span>
            <?php
            } else {
                $result=mysqli_query($connection, $sql);
             ?>
                 Centre Name<br>
      <input type="hidden"  id="hfCenterCode" name="centre_code"">
            <input list="centre_code" id="screens.screenid" name="company_name" style="width: 100%;">
      <datalist class="form-control" id="centre_code" style="display: none;">
         <!-- <option value="Select Centre" <?php //echo $centreCode == 'ALL' ? 'selected' : '' ?>>Select Centre</option> -->
         <?php
         while ($row=mysqli_fetch_assoc($result)) {
         ?>
            <option value="<?php echo $row['company_name'] ?>" <?php echo $row['company_name'] == $centreCode ? 'selected' : '' ?>><?php echo $row["centre_code"] ?></option>
         <?php
         }
         ?>
      </datalist>
        <script>
            var objCompanyName = document.getElementById('screens.screenid');
            $(document).on('change', objCompanyName, function(){
            // console.log("options[i].text")
                var options = $('datalist')[0].options;
                for (var i=0;i<options.length;i++){
                // console.log($(objCompanyName).val())
                if (options[i].value == $(objCompanyName).val()) 
                    {
                    $("#hfCenterCode").val(options[i].text);
                    break;
                    }
                }
            });
        </script>
             <?php
             }
        ?>
        </div>
       
        
        <!-- <input type="text" name="from_date" id="from_date" placeholder="From Date" data-uk-datepicker="{format: 'DD/MM/YYYY'}" value="<?php echo date("d/m/Y");?>">
        <input type="text" name="to_date" id="to_date" placeholder="To Date" data-uk-datepicker="{format: 'DD/MM/YYYY'}" value="<?php echo date("d/m/Y");?>"> -->
        <div class="uk-width-medium-2-10">
        Term<br>
        <?php 
             $month = date('m');
        ?>
        <select name="term" id="term" class="uk-width-medium-1-1">
            <option value="">Select Term</option>
            <option <?php if($month <= 3){ echo "selected"; }?> value="1">Term 1</option>
            <option <?php if($month >= 4 && $month <= 6){ echo "selected"; }?> value="2">Term 2</option>
            <option <?php if($month >= 7 && $month <= 9){ echo "selected"; }?> value="3">Term 3</option>
            <option <?php if($month >= 10 && $month <= 12){ echo "selected"; }?> value="4">Term 4</option>
            <option <?php if($month >= 1 && $month <= 2){ echo "selected"; }?> value="5">Term 5</option>
        </select>
        </div>
        <div class="uk-width-medium-2-10">
        Products<br>
        <select name="product" id="product" class="uk-width-medium-1-1">
            <option value="">Select	Products</option>
            <option value="Other products">Other products</option>
            <option value="Marketing set">Marketing set</option>
            <!-- <option value="E-readers">E-readers</option> -->
        </select>
        </div>
        <div class="uk-width-medium-2-10"><br>
            <button class="uk-button" onclick="generateBalReport('screen')">Show on screen</button>
            <button onclick="generateBalReport('print')" class="uk-button">Print</button> 
        </div>
        
    </div>
   </div>
    <script>
        function generateBalReport(method) {
            var centre_code=$("[name='centre_code']").val();
            if(centre_code=="" || centre_code=="All Centre"){
                centre_code="ALL";
            }
            //var cut_off_date=$("#cut_off_date").val();
            var product=$("#product").val();
            //var cut_off_date="30/07/2021";
            var term=$("#term").val();
            if (term!="") {
                if (method=="screen") {
                    $.ajax({
                        url : "admin/generateBalReport.php",
                        type : "POST",
                        data : "centre_code="+centre_code+"&product="+product+"&term="+term,
                        dataType : "text",
                        success : function(response, status, http) {
                            $("#sctBal").html(response);
                        },
                        error : function(http, status, error) {
                            UIkit.notify("Error:"+error);
                        }
                    });
                } else {
                    $("#frmPrintCentreCode").val(centre_code);
                    $("#frmPrintproduct").val(product);
                    $("#frmPrintterm").val(term);
                    $("#frmPrint").submit();
                }
            } else {
                UIkit.notify("Please enter a cut off date");
            }
        }

        $(document).ready(function () {
            generateBalReport('screen');
        });
    </script>
   <div id="sctBal"></div>
</div>
<form id="frmPrint" action="admin/generateBalReport.php" method="post" target="_blank" style="background: none!important; box-shadow: none!important; padding: 0;">
   <input type="hidden" name="method" value="print">
   <input type="hidden" id="frmPrintCentreCode" name="centre_code" value="">
   <input type="hidden" id="frmPrintproduct" name="product" value="">
   <input type="hidden" id="frmPrintterm" name="term" value="">
</form>
<?php
 //  }
}

?>
<style>
    .rpt-table tr td{
        border:1px solid #80808082;
    }
    .rpt-table{
        border-collapse: unset;
        border-spacing: 0px;
    }
</style>