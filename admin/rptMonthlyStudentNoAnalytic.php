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

  <span class="page_title"><img src="/images/title_Icons/Student Population-1.png">Student No Analytics by Program and Ethnicity</span>

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

        <div class="uk-width-medium-3-10"><br>
            <button class="uk-button" onclick="generateBalReport()">Show on screen</button>
            <button onclick="printint()" target="_blank" class="uk-button">Print</button> 
            <button onclick="exportexcel()" id="btnGenerate" class="uk-button" >Export CSV</button>
        </div>
        
    </div>
   </div>

    <form id="frmPrint" action="admin/a_rptMonthlyStudentNoAnalytic.php" method="post" target="_blank" style="background: none!important; box-shadow: none!important; padding: 0;">
        <input type="hidden" name="method" value="print">
        <input type="hidden" id="frmPrintProgram" name="program" value="">
        <input type="hidden" id="frmPrintSelectedMonth" name="selected_month" value="" >
        <input type="hidden" id="frmPrintCentreCode" name="centre_code" value="">
    </form>
    <form id="frmExcel" action="admin/export_rptMonthlyStudentNoAnalytic.php" method="post" style="background: none!important; box-shadow: none!important; padding: 0;" >
        <input type="hidden" name="method" value="excel">
        <input type="hidden" id="frmExcelCentreCode" name="centre_code" value="">
        <input type="hidden" id="frmExcelProgram" name="program" value="">
        <input type="hidden" id="frmExcelSelectedMonth" name="selected_month" value="">
    </form>

    <script>
        function printint() {
            
            var selected_month=$("#selected_month").val();
            var program=$("#program").val();
            var centre_code=$("[name='centre_name']").val();

            $("#frmPrintProgram").val(program);
            $("#frmPrintCentreCode").val(centre_code);
            $("#frmPrintSelectedMonth").val(selected_month);
            $("#frmPrint").submit();
        }

        function exportexcel() {
            
            var selected_month=$("#selected_month").val();
            var program=$("#program").val();
            var centre_code=$("[name='centre_name']").val();

            $("#frmExcelProgram").val(program);
            $("#frmExcelCentreCode").val(centre_code);
            $("#frmExcelSelectedMonth").val(selected_month);
            $("#frmExcel").submit();
        }

        function generateBalReport() {
            $("body").addClass("loading");
            var selected_month=$("#selected_month").val();
            var program=$("#program").val();
            var centre_code=$("[name='centre_name']").val();
           
            $.ajax({
                url : "admin/a_rptMonthlyStudentNoAnalytic.php",
                type : "POST",
                data : "selected_month="+selected_month+"&centre_code="+centre_code+"&program="+program,
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