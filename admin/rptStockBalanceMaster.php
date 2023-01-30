
<?php
include_once("../mysql.php");
include_once("functions.php");

if ($_SESSION["isLogin"]==1) {
    if (($_SESSION["UserType"]=="S" )) {
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
        <?php 
        $sql="SELECT * from centre where 1 order by centre_code";
        $result=mysqli_query($connection, $sql);
            ?>
        Centre Name<br>
      <input type="hidden"  id="hfCenterCode" name="centre_code"">
            <input list="centre_code" id="screens.screenid" name="company_name" style="width: 100%;">
      <datalist class="form-control" id="centre_code" style="display: none;">
         <option value="ALL" <?php echo $centreCode == 'ALL' ? 'selected' : '' ?>>All Centre</option>
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
       
        </div>
        
        <!-- <input type="text" name="from_date" id="from_date" placeholder="From Date" data-uk-datepicker="{format: 'DD/MM/YYYY'}" value="<?php echo date("d/m/Y");?>">
        <input type="text" name="to_date" id="to_date" placeholder="To Date" data-uk-datepicker="{format: 'DD/MM/YYYY'}" value="<?php echo date("d/m/Y");?>"> -->
        <div class="uk-width-medium-2-10">
        Term<br>
        <select name="term" id="term" class="uk-width-medium-1-1">
            <option value="">Select Term</option>
            <option value="term1">Term 1</option>
            <option value="term2">Term 2</option>
            <option value="term3">Term 3</option>
            <option value="term4">Term 4</option>
            <option value="term5">Term 5</option>
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
   <form id="frmPrint" action="admin/a_rptStockBalReportMaster.php" method="post" target="_blank" style="background: none!important; box-shadow: none!important; padding: 0;">
   <input type="hidden" name="method" value="print">
   <input type="hidden" id="frmPrintCentreCode" name="CentreCode" value="">
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
            var CenterCode=$("#hfCenterCode").val();
            //window.open('admin/a_rptStockBalReportMaster.php?term='+term+'&sub_category='+sub_category);
            $("#frmPrintSubCategory").val(sub_category);
            $("#frmPrintCentreCode").val(CenterCode);
            $("#frmPrintTerm").val(term);
            $("#frmPrintType").val(type);
            $("#frmPrint").submit();
        }

        function generateBalReport(sub_category) {
            $("#sctResult").html("Loading....");
            // var from_date=$("#from_date").val();
            // var to_date=$("#to_date").val();
            var centre_code=$("#hfCenterCode").val();
            var term=$("#term").val();
            var type=$("#type").val();
            //if (from_date!="" || to_date!="") {
               // data : "from_date="+from_date+"&to_date="+to_date+"&sub_category="+sub_category,
                $.ajax({
                    url : "admin/a_rptStockBalReportMaster.php",
                    type : "POST",
                    data : "term="+term+"&sub_category="+sub_category+"&type="+type+"&CentreCode="+centre_code,
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
        }

        $(document).ready(function () {
           // generateBalReport('');
        });
        
        
        
    </script>
   <div id="sctResult" style="border-top-right-radius: 15px;border-top-left-radius: 15px;">
   <div class="uk-margin-right">

<div class="uk-width-1-1 myheader text-center mt-5" style="color:white; text-align:center;">

  <h2 class="uk-text-center myheader-text-color myheader-text-style">Stock Balance Report</h2>
  From All Term<br>
</div>

<div class="uk-overflow-container">
<div class="uk-grid">
  <div class="uk-width-medium-5-10">
    <table class="uk-table">
      <tbody><tr>
        <td class="uk-text-bold">Centre Name</td>
        <td></td>
        </tr>
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
        <!-- <div class='row'>
        <div class='col-md-4 d-flex justify-content-center align-items-center'>
      
        </div>
         <div class='col-md-4 d-flex justify-content-center align-items-center'>
       
         </div>
         <div class='col-md-4 d-flex justify-content-center align-items-center'>
        
        </div>
        </div> -->
        <style>
          .rpt-table td {
            border: 1px solid black;
            }
        </style>
        
       
        <table class="uk-table rpt-table">
        <tbody><tr class="uk-text-small uk-text-bold">
          <td rowspan="3">No.</td>
          <td rowspan="3">Centre</td>
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
                
  </tbody></table></div></div></div>
   </div>

   
<?php
   }
}
?>
