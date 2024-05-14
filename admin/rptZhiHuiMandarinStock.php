<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");

if ($_SESSION["isLogin"]==1) {
    if ($_SESSION["UserName"]=="management") {

?>
<style>
     .overlay{
        display: none;
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        z-index: 999;
        background: rgba(255,255,255,0.8) url("https://www.tutorialrepublic.com/examples/images/loader.gif") center no-repeat;
    }
    
    /* Turn off scrollbar when body element has the loading class */
    body.loading{
        overflow: hidden;   
    }
    /* Make spinner image visible when body element has the loading class */
    body.loading .overlay{
        display: block;
    }
    
</style>
<div class="overlay"></div>
<a id="back_stock" href="/">                 
         <!-- <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span> -->
</a>
  <span class="page_title"><img src="/images/title_Icons/Student Population-1.png">Termly Centre Stock Report</span>

<!-- <div class="uk-margin-right uk-margin-top uk-form"> -->
   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color myheader-text-style">SEARCHING</h2>
   </div>
   <div class="uk-overflow-container uk-form nice-form">
   <div class="d-flex uk-grid uk-grid-small">
        <div class="uk-width-medium-2-10">
        Centre<br>
        <?php
            $sql = "SELECT * from centre order by centre_code";
            $result = mysqli_query($connection, $sql);
        ?>
            <select name="centre_name" class="select2" >
                <option value="ALL" <?php echo $centreCode == 'ALL' ? 'selected' : '' ?> >All Centres</option>
                <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                        <option value="<?php echo $row['centre_code'] ?>" <?php echo $row['centre_code'] == $centreCode ? 'selected' : '' ?>><?php echo $row["company_name"] ?></option>
                <?php
                    }
                ?>

            </select>
        </div>
        
        <!-- <input type="text" name="from_date" id="from_date" placeholder="From Date" data-uk-datepicker="{format: 'DD/MM/YYYY'}" value="<?php echo date("d/m/Y");?>">
        <input type="text" name="to_date" id="to_date" placeholder="To Date" data-uk-datepicker="{format: 'DD/MM/YYYY'}" value="<?php echo date("d/m/Y");?>"> -->
        <div class="uk-width-medium-2-10">
        Term<br>
        <select name="term" id="term" class="uk-width-medium-1-1">
            <option value="">Select Term</option>
            <?php  
                $term_list = mysqli_query($connection,"SELECT `term_num` FROM `schedule_term` WHERE `year` = '".$_SESSION['Year']."' AND `centre_code` = '".$_SESSION['CentreCode']."' AND `deleted` = '0' ORDER BY `term_num` ASC LIMIT 4");

                while($term_row = mysqli_fetch_array($term_list))
                {
            ?>
                  <option value="<?php echo $term_row['term_num']; ?>" >Term <?php echo $term_row['term_num']; ?></option>
            <?php
                }
            ?>
        </select>
        </div>
        <div class="uk-width-medium-3-10"><br>
            <button class="uk-button" onclick="generateBalReport('',1)">Show on screen</button>
            <button onclick="printint('')" style="display:none;" target="_blank" class="uk-button">Print</button> 
            <button onclick="exportexcel()" id="btnGenerate" class="uk-button" >Export CSV</button>
        </div>
        
    </div>
    </div>
    <form id="frmPrint" action="admin/a_rptZhiHuiMandarinStock.php" method="post" target="_blank" style="background: none!important; box-shadow: none!important; padding: 0;">
        <input type="hidden" name="method" value="print">
        <input type="hidden" id="frmPrintSubCategory" name="sub_category" value="">
        <input type="hidden" id="frmPrintTerm" name="term" value="">
        <input type="hidden" id="frmPrintCentreCode" name="centre_code" value="">
    </form>

    <form id="frmExcel" action="admin/export_rptZhiHuiMandarinStock.php" method="post" style="background: none!important; box-shadow: none!important; padding: 0;">
        <input type="hidden" name="method" value="excel">
        <input type="hidden" id="frmExcelTerm" name="term" value="">
        <input type="hidden" id="frmExcelCentreCode" name="centre_code" value="">
    </form>

<!-- </div> -->
    <script>
        function printint(sub_category) {
            var term=$("#term").val();
            var centre_code=$("[name='centre_name']").val();
            //window.open('admin/a_rptStockBalReport.php?term='+term+'&sub_category='+sub_category);
            $("#frmPrintSubCategory").val(sub_category);
            $("#frmPrintTerm").val(term);
            $("#frmPrintCentreCode").val(centre_code);
            $("#frmPrint").submit();
        }

        function exportexcel() {
            var term=$("#term").val();
            var centre_code=$("[name='centre_name']").val();
            $("#frmExcelTerm").val(term);
            $("#frmExcelCentreCode").val(centre_code);
            $("#frmExcel").submit();
        }

        function generateBalReport(sub_category,page_no) {
            $("body").addClass("loading");
            var term=$("#term").val();
            var centre_code=$("[name='centre_name']").val();
           
            $.ajax({
                url : "admin/a_rptZhiHuiMandarinStock.php",
                type : "POST",
                data : "term="+term+"&sub_category="+sub_category+"&centre_code="+centre_code+"&page_no="+page_no,
                dataType : "text",
                success : function(response, status, http) {						
                    if(sub_category!=""){							
                        $("#back_stock").attr("href", window.location.href);						
                    }
                    $("#sctResult").html(response);
                    $("body").removeClass("loading");
                },
                error : function(http, status, error) {
                    $("body").removeClass("loading");
                    UIkit.notify("Error:"+error);
                }
                
            });
        }

        $(document).ready(function () {
            $("#term").val(1);
            generateBalReport('',1);
        });

    </script>
    <div id="sctResult" style="border-top-right-radius: 15px;border-top-left-radius: 15px;">

    </div>
<?php
  }
}
?>

