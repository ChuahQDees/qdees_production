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
    var today = new Date();
    $("#selected_month").val(today.getFullYear() + '' + (("0" + (today.getMonth() + 1)).slice(-2)));
    generateReport('screen');
    $("#btnGenerate").click(function() {
      $("#back_stock").attr("href", window.location.href);
    })
  });
</script>
<?php if ($_GET['mode'] != "") { ?>
  <a id="back_stock" href="/index.php?p=rpt_summary_students_population">
    <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
  </a>
<?php } else { ?>
  <a id="back_stock" href="/">
    <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
  </a>
<?php } ?>
<span>

  <span class="page_title"><img src="/images/title_Icons/Student Population-1.png">Summary Student Population Report

  </span>





  <?php

  include_once("admin/functions.php");



  $current_year = date("Y");

  $low_year = $current_year - 1;

  $high_year = $current_year + 2;



  for ($i = $low_year; $i <= $high_year; $i++) {

    $loop_year .= "$i,";
  }

  $loop_year = substr($loop_year, 0, -1);



  if ($_SESSION["isLogin"] == 1) {

    if (($_SESSION["UserType"] == "S") || ($_SESSION["UserType"]=="H") || ($_SESSION["UserType"]=="C") || ($_SESSION["UserType"]=="R") || ($_SESSION["UserType"]=="CM") || ($_SESSION["UserType"]=="T")) {



  ?>

      <link rel="stylesheet" type="text/css" href="lib/monthpicker/jquery.monthpicker.css">

      <script src="lib/monthpicker_all/jquery.monthpicker.js?v=1"></script>

      <script>
        $(document).ready(function() {

          generateReport('screen');



          $("#centre_code_textbox").on('input', function(e) {

            if ($("#centre_code_textbox").val() != '') {

              $("#centre_code").attr('disabled', true);

            } else {

              $("#centre_code").attr('disabled', false);

            }

          });

        });

        function getActiveClasses() {
          $.ajax({
            url: "admin/getActiveClasses.php",
            type: "POST",
            data: "",
            dataType: "text",
            beforeSend: function(http) {},
            success: function(response, status, http) {
              $("#sctActiveClasses").html(response)
            },
            error: function(http, status, error) {
              UIkit.notify("Error:" + error);
            }
          });
        }

        function generateReport(method) {

          if ($("#centre_code_textbox").val() != '') {

            var centre_code = $("#centre_code_textbox").val();

          } else {

            var centre_code = document.getElementById('screens.screenid').value;

          }



          // alert();

          var selected_month = $("#selected_month").val();

          var oc = $("#oc").val();

          var centre_status = $("#centre_status").val();

          var course_name = $("#course_name").val();

          var selected_lvl = $("#lvl").val();

          var course_subject = $("#subject").val();



          if (method == "screen") {

            $.ajax({

              url: "admin/a_rptSummaryStudentsPopulation.php",

              type: "POST",

              data: "centreCode=" + centre_code + "&yearMonth=" + selected_month + "&oc=" + oc + "&centreStatus=" + centre_status + "&courseName=" + course_name + "&selected_lvl=" + selected_lvl + "&courseSubject=" + course_subject,

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

            $("#frmPrintOC").val(oc);

            $("#frmPrintCentreStatus").val(centre_status);

            $("#frmPrintCourseName").val(course_name);

            $("#frmPrintLevel").val(selected_lvl);

            $("#frmPrintSubject").val(course_subject);

            $("#frmPrint").submit()

          }

        }



        $(function() {

          $('#yearMonth').monthpicker({

            years: ['2021','2020', '2019'],

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

      <div class="uk-width-1-1 myheader mt-5">

        <h2 class="uk-text-center myheader-text-color myheader-text-style">Searching</h2>

      </div>

      <div class="uk-overflow-container">

        <div class="uk-form uk-grid uk-grid-small">

          <?php

          $sql = "SELECT * from centre order by centre_code";

          $result = mysqli_query($connection, $sql);

          $get_centreCodes = $get_pics = [];

          while ($row = mysqli_fetch_assoc($result)) {

            $get_centreCodes[] = $row['centre_code'];

            $get_centreName[] = $row['company_name'];

            $get_pics[] = $row['pic'];
          }

          $get_pics = array_unique($get_pics);

          ?>



          <div class="d_none">

            Company Name / Centre Code<br>

            <input type="text" id="centre_code_textbox" name="centre_code_textbox" class="uk-width-1-1" placeholder="center name / centre code">

          </div>

          <div class="uk-width-medium-2-10" style="width: 15%;">

            Centre Name<br>

            <input list="centre_code" id="screens.screenid" name="centre_code">

            <datalist class="form-control" id="centre_code" style="display: none;">

              <option value="ALL" <?php echo $centreCode == 'ALL' ? 'selected' : '' ?>>All Centre</option>

              <?php

              // foreach ($get_centreCodes as $key => $get_centreCode) {

              for ($i = 0; $i < count($get_centreCodes); $i++) {

              ?>

                <option value="<?php echo $get_centreCodes[$i] ?>" <?php echo $get_centreCodes[$i] == $centreCode ? 'selected' : '' ?>><?php echo $get_centreName[$i] ?></option>

              <?php

              }

              ?>

            </datalist>

            <!-- <select name="centre_code" id="centre_code" class="uk-width-1-1">

          <option value="ALL" <?php echo $centreCode == 'ALL' ? 'selected' : '' ?>>All Centre</option>

          <?php

          // foreach ($get_centreCodes as $key => $get_centreCode) {

          for ($i = 0; $i < count($get_centreCodes); $i++) {

          ?>

            <option value="<?php echo $get_centreCodes[$i] ?>" <?php echo $get_centreCodes[$i] == $centreCode ? 'selected' : '' ?>><?php echo $get_centreName[$i] ?></option>

          <?php

          }

          ?>

        </select> -->

          </div>

          <div class="uk-width-medium-2-10" style="display:none;">

            Operation Consultant<Br>

            <select name="oc" id="oc" class="uk-width-1-1">

              <option value="ALL" <?php echo $oc == 'ALL' ? 'selected' : '' ?>>Operation Consultant</option>

              <?php

              foreach ($get_pics as $key => $get_pic) {

              ?>

                <option value="<?php echo $get_pic ?>"><?php echo $get_pic ?></option>

              <?php

              }

              ?>

            </select>

          </div>

          <?php

          $sql2 = "SELECT * from centre_status order by name";

          $result2 = mysqli_query($connection, $sql2);

          ?>

          <div class="uk-width-medium-1-10" style="width: 17%;">

            <span style="white-space: nowrap;">Centre Category</span><Br>

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

          <div class="uk-width-medium-1-10" style="width: 15%;">

            <span style="white-space: nowrap;">Subject</span><Br>
            <select style="width: -webkit-fill-available;"  name="course_name" id="course_name"  >
               <option value="ALL">ALL</option>
               <option value="EDP">EDP</option>
               <option value="QF1">QF1</option>
               <option value="QF2">QF2</option>
               <option value="QF3">QF3</option>
            </select>

            <!-- <select name="course_name" id="course_name" class="uk-width-1-1">

              <option value="ALL" <?php echo $courseSubject == 'ALL' ? 'selected' : '' ?>>Course Name</option>

              <?php

              while ($row3 = mysqli_fetch_assoc($result3)) {

              ?>

                <option value="<?php echo $row3['course_name'] ?>" <?php echo $row3['course_name'] == $courseSubject ? 'selected' : '' ?>><?php echo $row3["course_name"] ?></option>

              <?php

              }

              ?>

            </select> -->


          </div>
          <?php

          $array_lvl = array('BIMPL1', 'BIMPL2', 'BIMPL3', 'BIMPL4', 'BIMPL5', 'BIEPL1', 'BIEPL2', 'BIEPL3', 'BIEPL4', 'BIEPL5', 'IEL1', 'IEL2', 'IEL3', 'IEL4', 'IEL5')

          ?>

          <div class="d_none">

            Level<Br>

            <select name="lvl" id="lvl" class="uk-width-1-1">

              <option value="ALL" <?php echo $selected_lvl == 'ALL' ? 'selected' : '' ?>>Level</option>

              <?php

              foreach ($array_lvl as $key => $lvl) {

              ?>

                <option value="<?php echo $lvl ?>" <?php echo $lvl == $selected_lvl ? 'selected' : '' ?>><?php echo $lvl ?></option>

              <?php

              }

              ?>

            </select>

          </div>

          <div class="d_none">

            Subject<Br>

            <select name="subject" id="subject" class="uk-width-1-1">

              <option value="ALL">ALL</option>
               <option value="EDP">EDP</option>
               <option value="QF1">QF1</option>
               <option value="QF2">QF2</option>
               <option value="QF3">QF3</option>

            </select>

          </div>

          <div class=" uk-width-medium-1-10" style="width: 12%;white-space: nowrap;">

               <span style="white-space: nowrap;">Months selection<span><Br>

                     <a class="uk-button" id="yearMonth" style="white-space: nowrap;">Pick a Month</a>

                     <input type="hidden" name="selected_month" id="selected_month" value="">

            </div>

          <div class="uk-width-medium-2-10" style="white-space: nowrap; width: 15%;">

            <br>

            <button onclick="generateReport('screen')" id="btnGenerate" class="uk-button">Show on Screen</button>

            <button onclick="generateReport('print')" id="btnGenerate" class="uk-button">Print</button>

          </div>

        </div>



      </div>



      <div id="sctResult" class="" style="border-top-right-radius: 15px;border-top-left-radius: 15px;"></div>

      <!--<form id="frmPrint" action="admin/a_rptSummaryStudentsPopulation.php" method="post" target="_blank" style="background: none!important; box-shadow: none!important; padding: 0;">

        <input type="hidden" name="method" value="print">

        <input type="hidden" id="frmPrintCentreCode" name="centreCode" value="">

        <input type="hidden" id="frmPrintselected_month" name="yearMonth" value="">

        <input type="hidden" id="frmPrintOC" name="oc" value="">

        <input type="hidden" id="frmPrintCentreStatus" name="centreStatus" value="">

        <input type="hidden" id="frmPrintCourseName" name="courseName" value="">

        <input type="hidden" id="frmPrintSubject" name="selected_lvl" value="">

        <input type="hidden" id="frmPrintLevel" name="courseSubject" value="">

      </form>-->
	  <form id="frmPrint" action="admin/a_rptSummaryStudentsPopulation.php" method="post" target="_blank" style="background: none!important; box-shadow: none!important; padding: 0;" >
   <input type="hidden" name="method" value="print">

        <input type="hidden" id="frmPrintCentreCode" name="centreCode" value="">

        <input type="hidden" id="frmPrintselected_month" name="yearMonth" value="">

        <input type="hidden" id="frmPrintOC" name="oc" value="">

        <input type="hidden" id="frmPrintCentreStatus" name="centreStatus" value="">

        <input type="hidden" id="frmPrintCourseName" name="courseName" value="">

        <input type="hidden" id="frmPrintSubject" name="selected_lvl" value="">

        <input type="hidden" id="frmPrintLevel" name="courseSubject" value="">
</form>



  <?php

      if ($msg != "") {

        echo "<script>UIkit.notify('$msg')</script>";
      }
    } else {

      echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
    }
  } else {

    header("Location: index.php");
  }

  ?>
  <style>
    .btn-qdees,
    .d_none {
      display: none;
    }
  </style>