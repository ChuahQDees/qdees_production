<?php

session_start();

include_once("../mysql.php");

include_once("functions.php");

?>



<script>

$(document).ready(function() {

   generateReport('screen');

});

function generateReport(method) {

   var country=$("#country").val();



    if (method=="screen") {

   if (country!="") {

      $.ajax({

         url : "admin/a_rptMasterReport.php",

         type : "POST",

         data : "country="+country,

         dataType : "text",

         beforeSend : function(http) {

         },

         success : function(response, status, http) {

            $("#sctResult").html(response);

         },

         error : function(http, status, error) {

            UIkit.notify("Error:"+error);

         }

      });

   } else {

      UIkit.notify("Please select a country");

   }

}else{

         $("#frmPrintCountry").val(country);

         $("#frmPrint").submit();

   }

}

</script>


<!-- 
<a href="/">                 

         <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>

</a> -->



<div class="uk-width-1-1 myheader">

   <h2 class="uk-text-center myheader-text-color myheader-text-style">Searching</h2>

</div>

<?php

// if (strtoupper($_SESSION["UserName"])=="SUPER") {
   if (($_SESSION["UserType"]=="S") || ($_SESSION["UserType"]=="H") || ($_SESSION["UserType"]=="C") || ($_SESSION["UserType"]=="R") || ($_SESSION["UserType"]=="CM") || ($_SESSION["UserType"]=="T")) {

?>

   <div class="uk-form nice-form" style="border-top-right-radius: 15px;border-top-left-radius: 15px;">

      <div class="uk-grid uk-grid-small">

         <div class="uk-width-medium-4-10">

            Country<br>

            <select class="uk-width-medium-1-1" name="country" id="country" class="uk-width-1-1">

               <option value="ALL">All Country</option>

               <?php

               $sql="SELECT code from codes where module='COUNTRY' order by code";

               $result=mysqli_query($connection, $sql);

               while ($row=mysqli_fetch_assoc($result)) {

               ?>

                     <option value="<?php echo $row['code']?>"><?php echo $row['code']?></option>

               <?php

               }

               ?>

            </select>

         </div>

         <button onclick="generateReport('screen');" id="btnGenerate" class="uk-button ">Generate</button>

         <button onclick="generateReport('report');" id="btnGenerate" class="uk-button ">Print</button>

      </div>

   </div>

<div id="sctResult"></div>



<form id="frmPrint" action="admin/a_rptMasterReport.php" method="post" target="_blank" style="background: none!important; box-shadow: none!important; padding: 0;">

   <input type="hidden" name="method" value="print">

   <input type="hidden" id="frmPrintCountry" name="country" value="">

</form>



<?php

} else {

   echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";

}

?>