<a href="/">
   <span class="btn-qdees" style="display:none;"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>
<span>
   <span class="page_title"><img src="/images/title_Icons/Defective_Status.png">Student Card Report</span>
</span>
</p>

<?php
include_once("../mysql.php");
session_start();
?>
<script>
   $(document).ready(function() {
      generateReport('screen');
   });

   function generateReport(method) {
     // console.log(method)
      var centre_code=$("[name='centre_code']").val();
       var student=$("#student").val();
       var Status=$("#Status").val();
	  if(centre_code==""){
		  centre_code="All"
	   }
      if (centre_code != "") {
		 
         if (method == "screen") {
			  
            $.ajax({
               url: "admin/a_rptStudentTag.php",
               type: "POST",
               data: "centre_code=" + centre_code+"&student="+student+"&Status="+Status ,
               dataType: "text",
               beforeSend: function(http) {},
               success: function(response, status, http) {
                  // $("#aBack").attr("href", "/index.php?p=rpt_outstanding");
                  $("#sctResult").html(response);
               },
               error: function(http, status, error) {
                  UIkit.notify("Error:" + error);
               }
            });
         } else {
            $("#frmPrintCentreCode").val(centre_code);
            $("#frmPrint").submit();
         }
      } else {
         //UIkit.notify("Please select a centre");
      }

   }
</script>
<!-- <form class="uk-form" name="qr_code_list" id="qr_code_list" method="get">
   <input type="hidden" name="p" id="p" value="qr_code_list_section"> -->
   
   <div class="uk-grid">
   
      <div style="width:100%;" class="uk-form uk-grid uk-grid-small uk-width-10-10">
		<div class="uk-width-1-1 myheader" style="">
                <h2 class="uk-text-center myheader-text-color myheader-text-style">Searching</h2>
            </div>
            <div class="dista">
            <?php
         if (($_SESSION["UserType"] == "S") || ($_SESSION["UserType"] == "H") || ($_SESSION["UserType"] == "C") || ($_SESSION["UserType"] == "R") || ($_SESSION["UserType"] == "CM") || ($_SESSION["UserType"] == "T")) {
            $sql = "SELECT * from centre order by centre_code";
            $result = mysqli_query($connection, $sql);

            $centre_code = $_GET["centre_code"];
            if($centre_code == "All" || $centre_code =="All Centre" || $centre_code ==""){
               $centre_code = "All";
            }
         ?>
         
            <div style="" class="uk-width-2-10 mr_1">
               Centre Name<br>
               <input type="hidden" id="hfPage" name="p" value="<?php echo $_GET["p"] ?>">
               <input type="hidden" id="hfCenterCode" name="centre_code" value="<?php echo $centre_code ?>">
               <input style="width: 100%;" list="centre_code" id="company_name" name="company_name" value="<?php echo $_GET["company_name"] ?>">


               <datalist class="form-control" id="centre_code" style="display: none;">

                  <option value="All" <?php echo $centreCode == 'ALL' ? 'selected' : '' ?>>All Centre</option>

                  <?php
                  while ($row = mysqli_fetch_assoc($result)) {
                  ?>
                     <option value="<?php echo $row['company_name'] ?>" <?php echo $row['company_name'] == $centreCode ? 'selected' : '' ?>><?php echo $row["centre_code"] ?></option>

                  <?php

                  }

                  ?>

               </datalist>
            </div>
            <script>
               $(document).on('change', '#company_name', function() {
                  var options = $('datalist')[0].options;
                  for (var i = 0; i < options.length; i++) {
                     // console.log(options[i].text)
                     if (options[i].value == $(this).val()) {
                        $("#hfCenterCode").val(options[i].text);
                        break;
                     }
                  }
               });
            </script>
         <?php
         } else {

            $centrecode = $_SESSION['CentreCode'];
            $sqlCentre = "SELECT centre_code, company_name from centre where centre_code = '$centrecode'  limit 1 ";
            //echo $sqlCentre;
            $resultCentre = mysqli_query($connection, $sqlCentre);
            $rowCentre = mysqli_fetch_assoc($resultCentre);
            // var_dump($rowCentre["kindergarten_name"]);die; 
         ?>
         
            <span class=" centre_n mr_1">
               Centre Name<br>
               <input style="width: 100%;border: 0;font-weight: bold;" name="centre_name" id="centre_name" value="<?php echo getCentreNameIndex($_SESSION["CentreCode"]) ?>" readonly>
               <input type="hidden" id="hfCenterCode" name="centre_code" value="<?php echo $_SESSION["CentreCode"] ?>">
            </span>
         <?php
         }
         ?>
         <div class="uk-width-medium-2-10 mr_1">
            Student Name<br>
            <input type="text" name="student" id="student" class="uk-width-medium-1-1" placeholder="Student Name">
         </div>
         <div class="uk-width-medium-1-10 mr_1">
            Status<br>
           <select class="uk-width-medium-1-1" name="Status" id="Status">
				<option value="">All Status</option>
				<option value="0">Active</option>
				<option value="1">Lost</option>
				<option value="2">Others</option>
			</select>
         </div>
         <div class="uk-grid uk-width-medium-2-10 mr_1">
            <div class="uk-width-medium-5-5" style="white-space: nowrap;margin-top: 20px;">
               <!-- Months selection<br> -->
               <!-- <a class="uk-button" id="month">Pick a Month</a>
               <input type="hidden" name="selected_month" id="selected_month" value="">
               <input type="hidden" name="selected_year" id="selected_year" value=""> -->
               <button onclick="generateReport('screen');" id="btnGenerate" class="uk-button">Show on screen</button>
				<button onclick="generateReport('print');" id="btnGenerate" class="uk-button">Print </button>
            </div>

         </div>
         </div>
        


         <!-- <div class="uk-width-2-10" style="white-space: nowrap;">
            <br>
            <button class="uk-button">Search</button>
         </div> 
</form>-->
<br>

<div style="width:100%;" class="uk-overflow-container">
  <div id="sctResult" style="border-top-right-radius: 15px;border-top-left-radius: 15px;background:white;"></div> 
  </div>
</div>

<form id="frmPrint" action="admin/a_rptStudentTag.php" method="post" target="_blank" style="background: none!important; box-shadow: none!important; padding: 0;" >
   <input type="hidden" name="method" value="print">
   <input type="hidden" id="frmPrintCentreCode" name="centre_code" value="">
</form>
<style>
   .nice-form table td.uk-text-right:last-child {
      text-align: right !important;
   }
   .dista{
      width: 100%;
    display: flex;
    flex-direction: row;
    background: white!important;
    box-shadow: 0px 13px 21px -10px rgba(0, 0, 0, 0.15)!important;
    padding: 1.5em;
    border-bottom-right-radius: 15px;
    border-bottom-left-radius: 15px;
    margin-bottom: 3%;
   }
   .mr_1{
      margin-right: 2%;
   }

   <style>.modal-body {
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
   }
</style>
 