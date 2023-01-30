<?php

session_start();

include_once("../mysql.php");

include_once("functions.php");



$current_year = date("Y");

$low_year = $current_year - 1;

$high_year = $current_year + 2;



for ($i = $low_year; $i <= $high_year; $i++) {

   $loop_year .= "$i,";
}

$loop_year = substr($loop_year, 0, -1);



?>

<link rel="stylesheet" type="text/css" href="lib/monthpicker/jquery.monthpicker.css">

<script src="lib/monthpicker_all/jquery.monthpicker.js?v=1"></script>

<script>
   $(document).ready(function() {

      generateReport('screen');

   });



   function generateReport(method) {

      // var centre_code=$("#centre_code").val();

      var centre_code = document.getElementById('screens.screenid').value;

      var selected_month = $("#selected_month").val();

      var centre_status = $("#centre_status").val();

      var course_subject = $("#course_subject").val();



      if (method == "screen") {

         $.ajax({

            url: "admin/a_rptMasterSubjectEnroll.php",

            type: "POST",

            data: "centre_code=" + centre_code + "&selected_month=" + selected_month + "&centre_status=" + centre_status + "&course_subject=" + course_subject,

            dataType: "text",

            beforeSend: function(http) {

            },

            success: function(response, status, http) {

               $("#sctResult").html(response);

            },

            error: function(http, status, error) {

               UIkit.notify("Error:" + error);

            }

         });

      } else {

         $("#frmPrintCentreCode").val(centre_code);

         $("#frmPrintselected_month").val(selected_month);

         $("#frmPrintCentreStatus").val(centre_status);

         $("#frmPrintSubject").val(course_subject);

         $("#frmPrint").submit()

      }

   }



   $(function() {

      $('#yearMonth').monthpicker({

         years: ['2020', '2019'],

         topOffset: 6,

         onMonthSelect: function(m, y) {

            var month = m + 1;



            if (month < 10) {

               month = "0" + month;

            }



            $("#selected_month").val(y + month);

         }

      });

   });
</script>


<script>
   function doCentreCodeChange() {
      var centre_code = $("#centre_code").val();

      if (centre_code != "") {
         id = "back_stock"
         $.ajax({
            url: "admin/doCentreCodeChange.php",
            type: "POST",
            data: "centre_code=" + centre_code,
            dataType: "text",
            beforeSend: function(http) {},
            success: function(response, status, http) {
               $("#back_stock").attr("href", window.location.href);
            },
            error: function(http, status, error) {
               UIkit.notify("Error:" + error);
            }
         });
      }
   }

   $(document).ready(function() {
      $("#btnGenerate").click(function() {
         $("#back_stock").attr("href", window.location.href);
      })
   });
</script>
<!--<?php if ($_GET['mode'] != "") { ?>
   <a id="back_stock" href="/index.php?p=rpt_master_subject_enrl">
      <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
   </a>
<?php } else { ?>
   <a id="back_stock" href="/">
      <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
   </a>
<?php } ?>-->

   
<style>
.page_title {
    position: absolute;
    right: 34px;
}
.uk-margin-right {
    margin-top: 40px;
}
</style>
<span class="page_title"><img src="/images/title_Icons/Student Population-1.png">MASTER SUBJECT ENROLLMENT REPORT
   </span>
   <div class="uk-width-1-1 myheader mt-5">

      <h2 class="uk-text-center myheader-text-color myheader-text-style">Searching</h2>

   </div>

   <div class="uk-overflow-container">



      <div class="uk-form uk-grid uk-grid-small" style="border-top-right-radius: 15px;border-top-left-radius: 15px;">

         <?php

         if ((($_SESSION["UserType"] == "S") || ($_SESSION["UserType"]=="H") || ($_SESSION["UserType"]=="C") || ($_SESSION["UserType"]=="R") || ($_SESSION["UserType"]=="CM") || ($_SESSION["UserType"]=="T")) & (hasRightGroupXOR($_SESSION["UserName"], "ReportingView"))) {

            $sql = "SELECT * from centre order by centre_code";

            $result = mysqli_query($connection, $sql);

         ?>

            <div class="uk-width-medium-2-10">

               Centre Name<br>



               <input list="centre_code" id="screens.screenid" name="centre_code">

                <datalist class="form-control" id="centre_code" style="display: none;">

                  <option value="ALL">ALL Centre</option>

                  <?php

                  while ($row = mysqli_fetch_assoc($result)) {

                  ?>

                     <option value="<?php echo $row['centre_code'] ?>"><?php echo $row["company_name"] ?></option>

                  <?php

                  }

                  ?>

               </datalist> 

            </div>

            <?php

            $sql2 = "SELECT * from centre_status order by name";

            $result2 = mysqli_query($connection, $sql2);

            ?>

            <div class="uk-width-medium-2-10">

               Centre Category<Br>

               <select name="centre_status" id="centre_status" class="uk-width-1-1">

                  <option value="ALL" <?php echo $centreStatus == 'ALL' ? 'selected' : '' ?>>All Centre Category</option>

                  <?php

                  while ($row2 = mysqli_fetch_assoc($result2)) {

                  ?>

                     <option value="<?php echo $row2['name'] ?>" <?php echo $row2['name'] == $centreStatus ? 'selected' : '' ?>><?php echo $row2["name"] ?></option>

                  <?php

                  }

                  ?>

               </select>

            </div>

            <?php

            $sql3 = "SELECT * from course order by course_name";

            $result3 = mysqli_query($connection, $sql3);

            ?>

            <div class="uk-width-medium-2-10">

               Course Name<Br>

               <select name="course_subject" id="course_subject" class="uk-width-1-1">

                  <option value="ALL" <?php echo $courseSubject == 'ALL' ? 'selected' : '' ?>>All Courses</option>

                  <?php

                  while ($row3 = mysqli_fetch_assoc($result3)) {

                  ?>

                     <option value="<?php echo $row3['subject'] ?>" <?php echo $row3['subject'] == $courseSubject ? 'selected' : '' ?>><?php echo $row3["subject"] ?></option>

                  <?php

                  }

                  ?>

               </select>

            </div>

            <div class=" uk-width-medium-1-10" style="width: 12%;white-space: nowrap;">

               <span style="white-space: nowrap;">Months selection<span><Br>

                     <a class="uk-button" id="yearMonth" style="white-space: nowrap;">Pick a Month</a>

                     <input type="hidden" name="selected_month" id="selected_month" value="">

            </div>
            <div class=" uk-width-medium-2-10" style="white-space: nowrap;">
               <br>
               <button onclick="generateReport('screen')" id="btnGenerate" class="uk-button">Show on screen</button>

               <button onclick="generateReport('print')" id="btnGenerate" class="uk-button">Print</button>
            </div>


         <?php

         }

         ?>

      </div>

      </Br>

   </div>



   <br>

   <div id="sctResult" class="" style="border-top-right-radius: 15px;border-top-left-radius: 15px;"></div>

   <form id="frmPrint" action="admin/a_rptMasterSubjectEnroll.php" method="post" target="_blank" style="background: none!important; box-shadow: none!important; padding: 0;">

      <input type="hidden" name="method" value="print">

      <input type="hidden" id="frmPrintCentreCode" name="centre_code" value="">

      <input type="hidden" id="frmPrintselected_month" name="selected_month" value="">

      <input type="hidden" id="frmPrintCentreStatus" name="centre_status" value="">

      <input type="hidden" id="frmPrintSubject" name="course_subject" value="">

   </form>