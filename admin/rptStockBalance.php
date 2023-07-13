
<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");

if ($_SESSION["isLogin"]==1) {
    if (($_SESSION["UserType"]=="A" ) & (hasRightGroupXOR($_SESSION["UserName"], "ReportingView"))) {
?>
<a id="back_stock" href="/">                 
         <!-- <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span> -->
</a>
  <span class="page_title"><img src="/images/title_Icons/Student Population-1.png">Stock balance report</span>

<!-- <div class="uk-margin-right uk-margin-top uk-form"> -->
   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color myheader-text-style">SEARCHING</h2>
   </div>
   <div class="uk-overflow-container uk-form nice-form">
   <div class="d-flex uk-grid uk-grid-small">
        <div class="uk-width-medium-2-10">
        Centre Name<br>
        <?php 
        $sql="SELECT * from centre where centre_code='".$_SESSION["CentreCode"]."' order by centre_code";
        $result=mysqli_query($connection, $sql);
        while ($row=mysqli_fetch_assoc($result)) {
            ?>
        <span type="text" class="uk-width-medium-1-1 uk-text-bold"><?php echo $row["company_name"]; ?></span>
        <?php 
            }
            ?>
        </div>
        
        <!-- <input type="text" name="from_date" id="from_date" placeholder="From Date" data-uk-datepicker="{format: 'DD/MM/YYYY'}" value="<?php echo date("d/m/Y");?>">
        <input type="text" name="to_date" id="to_date" placeholder="To Date" data-uk-datepicker="{format: 'DD/MM/YYYY'}" value="<?php echo date("d/m/Y");?>"> -->
        <div class="uk-width-medium-2-10">
        Term<br>
        <select name="term" id="term" class="uk-width-medium-1-1">
            <option value="">Select Term</option>
            <?php  
                $term_list = mysqli_query($connection,"SELECT `term_num` FROM `schedule_term` WHERE `year` = '".$_SESSION['Year']."' AND `centre_code` = '".$_SESSION['CentreCode']."' AND `deleted` = '0'");

                while($term_row = mysqli_fetch_array($term_list))
                {
            ?>
                  <option value="term<?php echo $term_row['term_num']; ?>" >Term <?php echo $term_row['term_num']; ?></option>
            <?php
                }
            ?>
        </select>
        </div>
        <div class="uk-width-medium-2-10">
        Type<br>
        <select name="type" id="type" class="uk-width-medium-1-1">
            <option value="">Select Type</option>
            <option value="Termly Module">Termly Module</option>
            <option value="LTR">LTR</option>
            <option value="Fliptec@Q Mandarin">Fliptec@Q Mandarin</option>
            <option value="Beamind">Beamind</option>
        </select>
        </div>
        <div class="uk-width-medium-2-10"><br>
            <button class="uk-button" onclick="generateBalReport('')">Show on screen</button>
            <button onclick="printint('')" target="_blank" class="uk-button">Print</button> 
        </div>
        
    </div>
   </div>
   <form id="frmPrint" action="admin/a_rptStockBalReport.php" method="post" target="_blank" style="background: none!important; box-shadow: none!important; padding: 0;">
   <input type="hidden" name="method" value="print">
   <input type="hidden" id="frmPrintSubCategory" name="sub_category" value="">
   <input type="hidden" id="frmPrintTerm" name="term" value="">
   <input type="hidden" id="frmPrintType" name="type" value="">
</form>
<!-- </div> -->
    <script>
        function printint(sub_category) {
            // var from_date=$("#from_date").val();
            // var to_date=$("#to_date").val();
            var term=$("#term").val();
            var type=$("#type").val();
            //window.open('admin/a_rptStockBalReport.php?term='+term+'&sub_category='+sub_category);
            $("#frmPrintSubCategory").val(sub_category);
            $("#frmPrintTerm").val(term);
            $("#frmPrintType").val(type);
            $("#frmPrint").submit();
        }

        function generateBalReport(sub_category) {
            // var from_date=$("#from_date").val();
            // var to_date=$("#to_date").val();
            var term=$("#term").val();
            var type=$("#type").val();
            //if (from_date!="" || to_date!="") {
               // data : "from_date="+from_date+"&to_date="+to_date+"&sub_category="+sub_category,
               /*
                $.ajax({
                    url : "admin/a_rptStockBalReport.php",
                    type : "POST",
                    data : "term="+term+"&sub_category="+sub_category+"&type="+type,
                    dataType : "text",
                    success : function(response, status, http) {						
					if(sub_category!=""){							
					$("#back_stock").attr("href", window.location.href);						
					}
                        $("#sctResult").html(response);
                    },
                    error : function(http, status, error) {
                        UIkit.notify("Error:"+error);
                    }
                });
            // } else {
            //     UIkit.notify("Please enter a cut off date");
            // }
            */
        }

        $(document).ready(function () {
            generateBalReport('');
        });
        
        
        
    </script>
   <div id="sctResult" style="border-top-right-radius: 15px;border-top-left-radius: 15px;">
   <div class="uk-margin-right">

<div class="uk-width-1-1 myheader text-center mt-5" style="color:white; text-align:center;">

  <h2 class="uk-text-center myheader-text-color myheader-text-style">Stock Balance Report</h2>
  From<br>
</div>

<div class="uk-overflow-container">
<div class="uk-grid">
  <div class="uk-width-medium-5-10">
    <table class="uk-table">
      <tbody><tr>
        <td class="uk-text-bold">Centre Name</td>
        <td></tr>
        <tr>
          <td class="uk-text-bold">Prepare By</td>
          <td></td>
        </tr>
        <tr id="note">
          <td colspan="2" class="uk-text-bold"></td>
        </tr>
      </tbody></table>
    </div>
    <div class="uk-width-medium-5-10">
      <table class="uk-table">
        <tbody><tr>
          <td class="uk-text-bold">Academic Year</td>
                      <td></td>
                  </tr>
        <tr>
          <td class="uk-text-bold">School Term</td>
          <td></td>
        </tr>
        <tr>
          <td class="uk-text-bold">Date of submission</td>
          <td></td>
        </tr>
        <tr id="note1" style="display: none;">
          <td colspan="2" class="uk-text-bold"></td>
        </tr>
      </tbody></table>
    </div>
  </div>


        <div class="uk-margin-top">
        <style>
          .rpt-table td {
            border: 1px solid black;
            }
        </style>
        
       
                <table class="uk-table rpt-table">
        <tbody><tr class="uk-text-small uk-text-bold">
          <td rowspan="3">Month</td>
          <td rowspan="3">Starters Testing Centre A</td>
                    <td rowspan="2" colspan="17">Termly Module</td>
                    <td rowspan="2" colspan="17">LTR</td>
                    <td rowspan="2" colspan="9">Fliptec@Q Mandarin</td>
                    <td colspan="54">Beamind</td>
                  </tr>
        
        <tr class="uk-text-small uk-text-bold">
                  <td colspan="13">English</td>
          <td colspan="13">Mandarin</td>
          <td colspan="13">Art</td>
          <td colspan="12">Math</td>
          <td rowspan="2">Grand Total</td>
                  </tr>
        
                <tr class="uk-text-small uk-text-bold">
          <td class="term term1">ED1</td>
          <td class="term term2">ED2</td>
          <td class="term term3">ED3</td>
          <td class="term term4">ED4</td>
          <td class="term term1">M1</td>
          <td class="term term2">M2</td>
          <td class="term term3">M3</td>
          <td class="term term4">M4</td>
          <td class="term term1">M5</td>
          <td class="term term2">M6</td>
          <td class="term term3">M7</td>
          <td class="term term4">M8</td>
          <td class="term term1">M9</td>
          <td class="term term2">M10</td>
          <td class="term term3">M11</td>
          <td class="term term4">M12</td>
          <td>Total</td>
                                  <td class="term term1">ED1</td>
          <td class="term term2">ED2</td>
          <td class="term term3">ED3</td>
          <td class="term term4">ED4</td>
          <td class="term term1">M1</td>
          <td class="term term2">M2</td>
          <td class="term term3">M3</td>
          <td class="term term4">M4</td>
          <td class="term term1">M5</td>
          <td class="term term2">M6</td>
          <td class="term term3">M7</td>
          <td class="term term4">M8</td>
          <td class="term term1">M9</td>
          <td class="term term2">M10</td>
          <td class="term term3">M11</td>
          <td class="term term4">M12</td>
          <td>Total</td>
                    <td class="term term1">M5</td>
          <td class="term term2">M6</td>
          <td class="term term3">M7</td>
          <td class="term term4">M8</td>
          <td class="term term1">M9</td>
          <td class="term term2">M10</td>
          <td class="term term3">M11</td>
          <td class="term term4">M12</td>
          <td>Total</td>
                    <!--English start-->
                      <td class="term term1">PREP A</td>
          <td class="term term2">PREP B</td>
          <td class="term term3">M1</td>
          <td class="term term4">M2</td>
          <td class="term term1">M3</td>
          <td class="term term2">M4</td>
          <td class="term term3">M5</td>
          <td class="term term4">M6</td>
          <td class="term term1">M7</td>
          <td class="term term2">M8</td>
          <td class="term term3">M9</td>
          <td class="term term4">M10</td>
          <td>Total</td>
          <!--English end--> 
          <!--Mandarin start-->
          <td class="term term1">M1</td>
          <td class="term term2">M2</td>
          <td class="term term3">M3</td>
          <td class="term term4">M4</td>
          <td class="term term1">M5</td>
          <td class="term term2">M6</td>
          <td class="term term3">M7</td>
          <td class="term term4">M8</td>
          <td class="term term1">M9</td>
          <td class="term term2">M10</td>
          <td class="term term3">M11</td>
          <td class="term term4">M12</td>
          <td>Total</td>
          <!--Mandarin end-->
          <!--Art start-->
          <td class="term term1">M1</td>
          <td class="term term2">M2</td>
          <td class="term term3">M3</td>
          <td class="term term4">M4</td>
          <td class="term term1">M5</td>
          <td class="term term2">M6</td>
          <td class="term term3">M7</td>
          <td class="term term4">M8</td>
          <td class="term term1">M9</td>
          <td class="term term2">M10</td>
          <td class="term term3">M11</td>
          <td class="term term4">M12</td>
          <td>Total</td>
          <!--Art end-->
          <!--Math start-->
          <td class="term term1">ACE02</td>
          <td class="term term2">M1</td>
          <td class="term term3">M2</td>
          <td class="term term4">M3</td>
          <td class="term term2">M4</td>
          <td class="term term3">M5</td>
          <td class="term term4">M6</td>
          <td class="term term1">M7</td>
          <td class="term term2">M8</td>
          <td class="term term3">M9</td>
          <td class="term term4">M10</td>
          <td>Total</td>
          <!--Math end-->
                  </tr>
        <!--january start-->
          <tr class="term term1">
            <td rowspan="4">January</td>
            <td>Opening</td>
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>

              <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                            <td class="term term1"><!--ED1-->
              0            </td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>
            
            <!--Fliptec@Q Mandarin-->
                        <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>

            <!--English start-->
                        <td class="term term1">0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td>0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1">0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td>0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
          <tr class="term term1">
            <td>Purchase</td><!--january-->
                        <td class="term term1"><!--ED1-->
              0            </td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                         <td class="term term1"><!--ED1-->
              0            </td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>

            <!--English start-->
                        <td class="term term1">0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td>0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1">0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td>0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
          <tr class="term term1">
            <td>Total consumed</td><!--january-->
                        <td class="term term1"><!--ED1-->
              0            </td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>

                <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                              <td class="term term1"><!--ED1-->
              0            </td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>

            <!--English start-->
                        <td class="term term1">0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td>0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1">0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td>0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
          <tr class="term term1">
            <td>Balance c/f</td><!--january-->
                        <td class="term term1"><!--ED1-->
              4            </td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                  
            <td class="term term1"><!--ED1-->
              0            </td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>

            <!--English start-->
                        <td class="term term1">0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td>0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1">0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td>0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
        <!--january end-->
        <!--february start-->
          <tr class="term term1">
            <td rowspan="4">February</td>
            <td>Balance b/f</td>
                        <td class="term term1"><!--ED1-->
              4            </td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                           <td class="term term1"><!--ED1-->
              0            </td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>

            <!--English start-->
                        <td class="term term1">0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td>0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1">0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td>0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
          <tr class="term term1">
            <td>Purchase</td><!--february-->
                        <td class="term term1"><!--ED1-->
              0            </td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>

               <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                             <td class="term term1"><!--ED1-->
              0            </td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>

            <!--English start-->
                        <td class="term term1">0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td>0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1">0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td>0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
          <tr class="term term1">
            <td>Total consumed</td><!--february-->
                        <td class="term term1"><!--ED1-->
              0            </td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->
              0            </td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>

            <!--English start-->
                        <td class="term term1">0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td>0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1">0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td>0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
          <tr class="term term1">
            <td>Balance c/f</td><!--february-->
                        <td class="term term1"><!--ED1-->
              4            </td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>


              <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                            <td class="term term1"><!--ED1-->
              0            </td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>

            <!--English start-->
                        <td class="term term1">0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td>0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1">0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td>0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
        <!--february end-->
        <!--March start-->
          <tr class="term term1">
            <td rowspan="4">March</td>
            <td>Balance b/f</td>
                        <td class="term term1"><!--ED1-->
              4            </td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                           <td class="term term1"><!--ED1-->
              0            </td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>

            <!--English start-->
                        <td class="term term1">
            8</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td>0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1">0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td>0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
          <tr class="term term1">
            <td>Purchase</td><!--March-->
                        <td class="term term1"><!--ED1-->
              0            </td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->
              0            </td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>

            <!--English start-->
                        <td class="term term1">0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td>0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1">0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td>0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
          <tr class="term term1">
            <td>Total consumed</td><!--March-->
                        <td class="term term1"><!--ED1-->
              0            </td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->
              0            </td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>

            <!--English start-->
                        <td class="term term1">0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td>0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1">0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td>0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
          <tr class="term term1">
            <td>Balance c/f</td><!--March-->
                        <td class="term term1"><!--ED1-->
              4            </td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->
              0            </td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>

            <!--English start-->
                        <td class="term term1">0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td>0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1">0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td>0</td>
            <!--Art end-->
            <!--Math start--> 
            <td class="term term1">0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1">0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td>0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
        <!--March end-->
        <!--April start-->
          <tr class="term term2">
            <td rowspan="4">April</td>
            <td>Opening</td>
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2--> 0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2--> 0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2--> 0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2--> 0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--English start-->
                        <td class="term term1"><!--PREP A-->0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02-->0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
          <tr class="term term2">
            <td>Purchase</td>
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2--> 2</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2--> 2</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--English start-->
                        <td class="term term1"><!--PREP A-->0</td>
            <td class="term term2"><!--PREP B<-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02-->0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--Math end-->
            <td>21</td>
                        </tr>
          <tr class="term term2">
            <td>Total consumed</td>
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2--> 0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--English start-->
                        <td class="term term1"><!--PREP A-->0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02-->0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
          <tr class="term term2">
            <td>Balance c/f</td>
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2--> 0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--English start-->
                        <td class="term term1"><!--PREP A-->0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02-->0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
        <!--April END-->
        <!--May start-->
          <tr class="term term2">
            <td rowspan="4">May</td>
            <td>Balance b/f</td>
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2--> 0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--English start-->
                        <td class="term term1"><!--PREP A-->0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02-->0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
          <tr class="term term2">
            <td>Purchase</td>
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2--> 0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2--> 0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2--> 0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2--> 0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--English start-->
                        <td class="term term1"><!--PREP A-->0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02-->0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
          <tr class="term term2">
            <td>Total consumed</td>
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2--> 0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2--> 0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2--> 0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2--> 0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--English start-->
                        <td class="term term1"><!--PREP A-->0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02-->0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
          <tr class="term term2">
            <td>Balance c/f</td>
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            
            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2--> 0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--English start-->
                        <td class="term term1"><!--PREP A-->0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02-->0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
        <!--May END-->
        <!--June start-->
          <tr class="term term2">
            <td rowspan="4">June</td>
            <td>Balance b/f</td>
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2--> 0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td></td>

            <!--English start-->
                        <td class="term term1"><!--PREP A-->0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02-->0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
          <tr class="term term2">
            <td>Purchase</td>
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2--> 0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2--> 0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2--> 0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2--> 0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--English start-->
                        <td class="term term1"><!--PREP A-->0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02-->0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
          <tr class="term term2">
            <td>Total consumed</td>
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2--> 0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2--> 0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2--> 0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2--> 0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--English start-->
                        <td class="term term1"><!--PREP A-->0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02-->0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
          <tr class="term term2">
            <td>Balance c/f</td>
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2--> 0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--English start-->
                        <td class="term term1"><!--PREP A-->0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02-->0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
        <!--June END-->
        <!--July start-->
          <tr class="term term3">
            <td rowspan="4">July</td>
            <td>Opening</td>
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3--> 0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3--> 0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

          <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3--> 0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3--> 0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--English start-->
                        <td class="term term1"><!--PREP A-->0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02-->0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
          <tr class="term term3">
            <td>Purchase</td>
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3--> 0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3--> 0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            
            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3--> 0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3--> 0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--English start-->
                        <td class="term term1"><!--PREP A-->0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02-->0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!-- end-->
            <td>0</td>
                        </tr>
          <tr class="term term3">
            <td>Total consumed</td>
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3--> 0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3--> 0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            
            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3--> 0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3--> 0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--English start-->
                        <td class="term term1"><!--PREP A-->0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02-->0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
          <tr class="term term3">
            <td>Balance c/f</td>
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3--> 0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3--> 0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3--> 0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3--> 0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--English start-->
                        <td class="term term1"><!--PREP A-->0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02-->0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
        <!--July END-->
        <!--August start-->
          <tr class="term term3">
            <td rowspan="4">August</td>
            <td>Balance b/f</td>
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3--> 0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3--> 0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            
            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3--> 0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3--> 0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--English start-->
                        <td class="term term1"><!--PREP A-->0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02-->0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
          <tr class="term term3">
            <td>Purchase</td>
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3--> 0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3--> 0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3--> 0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3--> 0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--English start-->
                        <td class="term term1"><!--PREP A-->0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02-->0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
          <tr class="term term3">
            <td>Total consumed</td>
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3--> 0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3--> 0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            
            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3--> 0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3--> 0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--English start-->
                        <td class="term term1"><!--PREP A-->0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02-->0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
          <tr class="term term3">
            <td>Balance c/f</td>
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3--> 0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3--> 0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            
            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3--> 0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3--> 0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--English start-->
                        <td class="term term1"><!--PREP A-->0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02-->0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
        <!--August END-->
        <!--September start-->
          <tr class="term term3">
            <td rowspan="4">September</td>
            <td>Balance b/f</td>
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3--> 0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3--> 0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            
            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3--> 0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3--> 0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--English start-->
                        <td class="term term1"><!--PREP A-->0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02-->0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
          <tr class="term term3">
            <td>Purchase</td>
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3--> 0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3--> 0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3--> 0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3--> 0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--English start-->
                        <td class="term term1"><!--PREP A-->0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02-->0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
          <tr class="term term3">
            <td>Total consumed</td>
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3--> 0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3--> 0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3--> 0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3--> 0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--English start-->
                        <td class="term term1"><!--PREP A-->0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02-->0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
          <tr class="term term3">
            <td>Balance c/f</td>
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3--> 0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3--> 0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3--> 0</td>
            <td class="term term4"><!--ED4-->0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3--> 0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--English start-->
                        <td class="term term1"><!--PREP A-->0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02-->0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
        <!--September END-->
        <!--October start-->
          <tr class="term term4">
            <td rowspan="4">October</td>
            <td>Opening</td>
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4--> 0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4--> 0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4--> 0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4--> 0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--English start-->
                        <td class="term term1"><!--PREP A-->0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02-->0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
          <tr class="term term4">
            <td>Purchase</td>
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4--> 0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4--> 0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            
            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4--> 0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4--> 0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--English start-->
                        <td class="term term1"><!--PREP A-->0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02-->0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
          <tr class="term term4">
            <td>Total consumed</td>
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4--> 0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4--> 0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4--> 0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4--> 0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--English start-->
                        <td class="term term1"><!--PREP A-->0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02-->0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
          <tr class="term term4">
            <td>Balance c/f</td>
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4--> 0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4--> 0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            
            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4--> 0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4--> 0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--English start-->
                        <td class="term term1"><!--PREP A-->0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02-->0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
        <!--October END-->
        <!--November start-->
          <tr class="term term4">
            <td rowspan="4">November</td>
            <td>Balance b/f</td>
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4--> 0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4--> 0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            
            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4--> 0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4--> 0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--English start-->
                        <td class="term term1"><!--PREP A-->0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02-->0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!-- end-->
            <td>0</td>
                        </tr>
          <tr class="term term4">
            <td>Purchase</td>
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4--> 0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4--> 0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4--> 0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4--> 0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--English start-->
                        <td class="term term1"><!--PREP A-->0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02-->0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
          <tr class="term term4">
            <td>Total consumed</td>
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4--> 0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4--> 0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4--> 0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4--> 0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--English start-->
                        <td class="term term1"><!--PREP A-->0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02-->0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
          <tr class="term term4">
            <td>Balance c/f</td>
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4--> 0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4--> 0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            
            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4--> 0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4--> 0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--English start-->
                        <td class="term term1"><!--PREP A-->0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02-->0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
        <!--November END-->
        <!--December start-->
          <tr class="term term4">
            <td rowspan="4">December</td>
            <td>Balance b/f</td>
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4--> 0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4--> 0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4--> 0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4--> 0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--English start-->
                        <td class="term term1"><!--PREP A-->0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02-->0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
          <tr class="term term4">
            <td>Purchase</td>
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4--> 0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4--> 0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4--> 0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4--> 0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--English start-->
                        <td class="term term1"><!--PREP A-->0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02-->0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
          <tr class="term term4">
            <td>Total consumed</td>
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4--> 0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4--> 0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4--> 0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4--> 0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--English start-->
                        <td class="term term1"><!--PREP A-->0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02-->0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
          <tr class="term term4">
            <td>Balance c/f</td>
                        <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4--> 0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4--> 0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
                          <td class="term term1"><!--ED1-->0</td>
            <td class="term term2"><!--ED2-->0</td>
            <td class="term term3"><!--ED3-->0</td>
            <td class="term term4"><!--ED4--> 0</td>
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4--> 0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--Fliptec@Q Mandarin-->
                        <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>

            <!--English start-->
                        <td class="term term1"><!--PREP A-->0</td>
            <td class="term term2"><!--PREP B-->0</td>
            <td class="term term3"><!--M1-->0</td>
            <td class="term term4"><!--M2-->0</td>
            <td class="term term1"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1-->0</td>
            <td class="term term2"><!--M2-->0</td>
            <td class="term term3"><!--M3-->0</td>
            <td class="term term4"><!--M4-->0</td>
            <td class="term term1"><!--M5-->0</td>
            <td class="term term2"><!--M6-->0</td>
            <td class="term term3"><!--M7-->0</td>
            <td class="term term4"><!--M8-->0</td>
            <td class="term term1"><!--M9-->0</td>
            <td class="term term2"><!--M10-->0</td>
            <td class="term term3"><!--M11-->0</td>
            <td class="term term4"><!--M12-->0</td>
            <td><!--Total-->0</td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02-->0</td>
            <td class="term term2"><!--M1-->0</td>
            <td class="term term3"><!--M2-->0</td>
            <td class="term term4"><!--M3-->0</td>
            <td class="term term2"><!--M4-->0</td>
            <td class="term term3"><!--M5-->0</td>
            <td class="term term4"><!--M6-->0</td>
            <td class="term term1"><!--M7-->0</td>
            <td class="term term2"><!--M8-->0</td>
            <td class="term term3"><!--M9-->0</td>
            <td class="term term4"><!--M10-->0</td>
            <td><!--Total-->0</td>
            <!--Math end-->
            <td>0</td>
                        </tr>
        <!--December END-->

  </tbody></table></div></div></div>
   </div>

   
<?php
   }
}
?>
