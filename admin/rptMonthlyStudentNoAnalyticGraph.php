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
    
    .uk-tab > li.uk-active > a {
        border : 1px solid #dddddd;
    }

    .uk-tab > li > a {
        padding: 3px 12px 3px 12px;
    }
</style>
<div class="overlay"></div>
<a id="back_stock" href="/">                 
         <!-- <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span> -->
</a>
  <span class="page_title"><img src="/images/title_Icons/Student Population-1.png">Student No Monitoring Report</span>

<!-- <div class="uk-margin-right uk-margin-top uk-form"> -->
   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color myheader-text-style">SEARCHING</h2>
   </div>
   <div class="uk-overflow-container uk-form nice-form">
   <div class="d-flex uk-grid uk-grid-small">
        <div class="uk-width-medium-2-10" style="display:none;">
        Centre<br>
        <?php
            $sql = "SELECT * from centre order by centre_code";
            $result = mysqli_query($connection, $sql);
        ?>
            <input list="centre_name" id="screens.screenid" name="centre_name" value="">

            <datalist class="form-control" id="centre_name" style="display: none;">

            <option value="ALL" <?php echo $centreCode == 'ALL' ? 'selected' : '' ?> >All Centres</option>

            <?php
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
                    <option value="<?php echo $row['centre_code'] ?>" <?php echo $row['centre_code'] == $centreCode ? 'selected' : '' ?>><?php echo $row["company_name"] ?></option>

            <?php
                }
            ?>

            </datalist>
        </div>
        <div class="uk-width-2-10" style="width: auto;">
            Select<br>
            <select name="program" id="program" >
                <option selected value="Half Day Prog and Ethnicity" >Half Day Prog & Ethnicity</option>
                <option value="Enh Foundation and Ethnicity" >Enh Foundation & Ethnicity</option>
                <option value="Afternoon Prog and Ethnicity" >Afternoon Prog & Ethnicity</option>
                <option value="EF Plus and Ethnicity" >EF Plus & Ethnicity</option>
                <option value="Stu No Geographical Distribution and Ethnicity" >Stu No Geographical Distribution & Ethnicity</option>
            </select>
        </div>
        <div class="uk-width-2-10" style="width: auto;">
            Month Selection<br>
            <select name="selected_month" id="selected_month" >
            <option value="" >All Months</option>
            <?php
                $period = getMonthList($year_start_date, $year_end_date);
                $months = array();
            
                foreach ($period as $dt) {
            ?>
                    <option value="<?php echo $dt->format("Y-m"); ?>" ><?php echo $dt->format("M Y"); ?></option>
            <?php
                    $months[$dt->format("M Y")] = $dt->format("Y-m");
                }
            ?>
            </select>
        </div>

        <div class="uk-width-2-10" style="width: auto;">
          <br>
            <ul class="uk-tab" style="border-bottom:none;" data-uk-tab="{connect:'#my-id'}">
                <li onclick="generateBalReport()" ><a href="" >Student No</a></li>
                <li onclick="generateBalReport('per')" ><a href="" >Percentage</a></li>
            </ul>
        </div>
        
        <div class="uk-width-medium-2-10"><br>
            <button class="uk-button" onclick="generateBalReport()">Show on screen</button>
        </div>
        
    </div>
   </div>
   <form id="frmPrint" action="admin/a_rptMonthlyStudentNoAnalyticGraph.php" method="post" target="_blank" style="background: none!important; box-shadow: none!important; padding: 0;">
   <input type="hidden" name="method" value="print">
   <input type="hidden" id="frmPrintSubCategory" name="sub_category" value="">
   <input type="hidden" id="frmPrintTerm" name="term" value="">
   <input type="hidden" id="frmPrintCentreCode" name="centre_code" value="">
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

        function generateBalReport(graph_type = '') {
            $("body").addClass("loading");
            var program=$("#program").val();
            var selected_month=$("#selected_month").val();
            var centre_code=$("[name='centre_name']").val();
            $.ajax({
                url : "admin/a_rptMonthlyStudentNoAnalyticGraph.php",
                type : "POST",
                data : "selected_month="+selected_month+"&graph_type="+graph_type+"&centre_code="+centre_code+"&program="+program,
                dataType : "text",
                success : function(response, status, http) {						
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
            $("#selected_month").val("");
            generateBalReport();
        });

    </script>
    <div id="sctResult" style="border-top-right-radius: 15px;border-top-left-radius: 15px;">

    </div>
<?php
  }
}
?>