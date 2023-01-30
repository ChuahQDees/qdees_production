<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");

function isMaster($master_code)
{
   global $connection;

   $sql = "SELECT * from master where master_code='$master_code'";
   $result = mysqli_query($connection, $sql);
   $num_row = mysqli_num_rows($result);
   if ($num_row > 0) {
      return true;
   } else {
      return false;
   }
}
?>

<script>
   <?php
   if ((($_SESSION["UserType"] == "A") || ($_SESSION["UserType"] == "O") || ($_SESSION["UserType"] == "S")) & (hasRightGroupXOR($_SESSION["UserName"], "ReportingView"))) {
   }
   ?>$(document).ready(function() {
      generateReport();
   })

   function generateReport() {
      var country = $('#country').val();
      var centre_code = $('[name="centre_code"]').val();
      if (centre_code == "") {
         centre_code = "ALL";
         //var centre_code=document.getElementById('screens.screenid').value;	       
      } //else{
      //var centre_code=$('[name="centre_code"]').val();
      //}

      if (centre_code != "") {
         $.ajax({
            url: "admin/a_rptFranchise.php",
            type: "POST",
            data: "centre_code=" + centre_code+"&country=" + country,
            dataType: "text",
            beforeSend: function(http) {},
            success: function(response, status, http) {
               $("#sctResult").html(response);
            },
            error: function(http, status, error) {
               UIkit.notify("Error:" + error);
            }
         });
      } else {
         UIkit.notify("Please select a centre");
      }

   }

   function generatePrint() {
      var country = $('#country').val();
      var centre_code = $('[name="centre_code"]').val();
      if (centre_code == "") {
         centre_code = "ALL";
      }
      console.log(centre_code);
      var url = 'admin/franchise_report.php?centre_code=' + centre_code+'&country=' + country;

      window.open(url, '_blank');

   }

   function doCountryChange() {
      var country = $("#country").val();

      if (country != "") {
         $.ajax({
            url: "admin/getCountryCentre.php",
            type: "POST",
            data: "country=" + country,
            dataType: "text",
            beforeSend: function(http) {},
            success: function(response, status, http) {
               $('[name="centre_code"]').html(response);
            },
            error: function(http, status, error) {
               UIkit.notify("Error:" + error);
            }
         });
      }
   }
</script>

<a href="/">
   <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>
<span>
   <span class="page_title"><img src="/images/title_Icons/Payment.png">Franchise Report
   </span>
   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color myheader-text-style">Searching</h2>
   </div>
   <div class="uk-form nice-form" style="border-top-right-radius: 15px;border-top-left-radius: 15px;">
      <div class="uk-grid uk-grid-small">
         <?php
         if (strtoupper($_SESSION["UserName"]) == "SUPER") {
         ?>
            <div class="uk-width-medium-2-10">
               Country<br>
               <select class="uk-width-medium-1-1" name="country" id="country" onchange="doCountryChange()">
                  <option value="">Select</option>
                  <?php
                  $sql = "SELECT code from codes where module='COUNTRY' order by code";
                  $result = mysqli_query($connection, $sql);
                  while ($row = mysqli_fetch_assoc($result)) {
                  ?>
                     <option value="<?php echo $row['code'] ?>"><?php echo $row["code"] ?></option>
                  <?php
                  }
                  ?>
               </select>
            </div>
         <?php
         } else {
         ?>
            <input type="hidden" name="country" value="">
         <?php
         }

         if (($_SESSION["UserType"] == "S") & (hasRightGroupXOR($_SESSION["UserName"], "ReportingView"))) {
            if (isMaster($_SESSION["CentreCode"])) {
               $sql = "SELECT * from centre where upline='" . $_SESSION["CentreCode"] . "' order by centre_code";
            } else {
               if (strtoupper($_SESSION["UserName"]) == "SUPER") {
                  $sql = "SELECT * from centre order by centre_code";
               } else {
                  $sql = "";
               }
            }
         } else {
            if ((($_SESSION["UserType"] == "A") || ($_SESSION["UserType"] == "O")) & (hasRightGroupXOR($_SESSION["UserName"], "ReportingView"))) {
               $sql = "";
            }
         }
         ?>
         <div class="uk-width-medium-2-10">
            <?php
            if ($sql == "") {
            ?>
               Centre Name<br>
               <input class="uk-width-medium-1-1" name="centre_code" id="centre_code" value="<?php echo $_SESSION['CentreCode'] ?>" readonly>
            <?php
            } else {
               $result = mysqli_query($connection, $sql);
            ?>
               Centre Name<br>

               <input list="centre_code" id="screens.screenid" name="centre_code">
               <datalist class="form-control" id="centre_code" style="display: none;">
                  <option value="">Select</option>
                  <?php
                  if (strtoupper($_SESSION["UserName"]) == "SUPER") {
                  ?>
                     <option selected value="ALL">ALL</option>
                  <?php
                  }
                  ?>
                  <?php
                  while ($row = mysqli_fetch_assoc($result)) {
                     if (strtoupper($_SESSION["UserName"]) == "SUPER") {
                  ?>
                        <option value="<?php echo $row['centre_code'] ?>"><?php echo $row["centre_code"] . " - " . $row["company_name"] ?></option>
                  <?php
                     }
                  }
                  ?>
               </datalist>
               <!--  <select class="uk-width-medium-1-1" name="centre_code" id="centre_code">
               <option value="">Select</option>
               <?php
               if (strtoupper($_SESSION["UserName"]) == "SUPER") {
               ?>
                  <option value="ALL">ALL</option>
               <?php
               }
               ?>
               <?php
               while ($row = mysqli_fetch_assoc($result)) {
                  if (strtoupper($_SESSION["UserName"]) == "SUPER") {
               ?>
                     <option value="<?php echo $row['centre_code'] ?>"><?php echo $row["centre_code"] . " - " . $row["kindergarten_name"] ?></option>
               <?php
                  }
               }
               ?>
            </select> -->
            <?php
            }
            ?>
         </div>
         <div style="padding-right: 0px;" class="uk-width-medium-2-10"><br>
            <button onclick="generateReport();" id="btnGenerate" class="uk-button">Show on Screen</button>
            <button onclick="generatePrint();" class="uk-button">Print</button>
         </div>
      </div>
   </div>
   <br><br>
   <br><br>
   <div id="sctResult"></div>
   <style>
      .btn-qdees {
         display: none;
      }
   </style>
   <div id="sctResult"></div>