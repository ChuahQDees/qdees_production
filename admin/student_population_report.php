<?php

session_start();
include_once("../mysql.php");
//include_once("../uikit1.php");
include_once("functions.php");

function getTotalStudents($centre_code)
{
  global $connection;

  $sql = "SELECT COUNT(id) as total FROM student WHERE centre_code ='" . mysqli_real_escape_string($connection, $centre_code) . "' AND deleted=0";
  $result = mysqli_query($connection, $sql);
  $row = mysqli_fetch_assoc($result);

  return $row['total'];
}

function displayStudentStatus($status)
{
  switch ($status) {
    case "A":
      return "Active";
    case "D":
      return "Deferred";
    case "G":
      return "Graduated";
    case "I":
      return "Dropout";
    case "S":
      return "Suspended";
    case "T":
      return "Transferred";
    default:
      return $status;
  }
}

function getStudentStatusCount($centre_code, $status, $with_deleted = false)
{
  global $connection, $year;

  $sql = "SELECT COUNT(id) as total FROM student WHERE student_status='" . mysqli_real_escape_string($connection, $status) . "' and start_date_at_centre <= '".$year_end_date." 23:59:59' and extend_year >= '$year'";

  if ($centre_code != 'ALL') {
    $sql .= " and centre_code ='" . mysqli_real_escape_string($connection, $centre_code) . "'";
  }

  if ($with_deleted) {
    $sql .= " AND deleted=1";
  } else {
    $sql .= " AND deleted=0";
  }

  $result = mysqli_query($connection, $sql);
  $row = mysqli_fetch_assoc($result);

  return $row['total'];
}

function getDropoutCount($centre_code)
{
  global $connection, $year;

  $sql = "SELECT COUNT(d.id) as total FROM dropout d, student s where d.student_code=s.student_code and s.start_date_at_centre <= '".$year_end_date." 23:59:59' and extend_year >= '$year'";

  if ($centre_code != 'ALL') {
    $sql .= " and d.centre_code ='" . mysqli_real_escape_string($connection, $centre_code) . "'";
  }

  $result = mysqli_query($connection, $sql);
  $row = mysqli_fetch_assoc($result);

  return $row['total'];
}

function getCentreName($centre_code)
{
  global $connection;

  $sql = "SELECT company_name from centre where centre_code='$centre_code'";
  $result = mysqli_query($connection, $sql);
  $row = mysqli_fetch_assoc($result);
  $num_row = mysqli_num_rows($result);

  if ($num_row > 0) {
    return $row["company_name"];
  } else {
    if ($centre_code == "ALL") {
      return "All Centres";
    } else {
      return "";
    }
  }
}

if ($_SESSION["isLogin"] == 1) {
  if ((($_SESSION["UserType"] == "A") || ($_SESSION["UserType"] == "O") || ($_SESSION["UserType"] == "S") || ($_SESSION["UserType"]=="H") || ($_SESSION["UserType"]=="C") || ($_SESSION["UserType"]=="R") || ($_SESSION["UserType"]=="CM") || ($_SESSION["UserType"]=="T")) ||
    (hasRightGroupXOR($_SESSION["UserName"], "StudentEdit|StudentView"))
  ) {

    /* include_once("../lib/pagination/pagination.php"); */
    $p = $_GET["p"];
    $m = $_GET["m"];
    $get_sha1_id = $_GET["id"];
    $pg = $_GET["pg"];
    $mode = $_GET["mode"];
    if (isset($_GET['selected_year'])) {
      $year = $_GET['selected_year'];
    } else {
      $year = $_SESSION['Year'];
    }

    if (($_SESSION["UserType"] == "S") || ($_SESSION["UserType"]=="H") || ($_SESSION["UserType"]=="C") || ($_SESSION["UserType"]=="R") || ($_SESSION["UserType"]=="CM") || ($_SESSION["UserType"]=="T")) {
      if (isset($_GET["centre_code"]) && $_GET["centre_code"]!="") {
        $centreCode = $_GET["centre_code"];
      } else {
        $centreCode = 'ALL';
      }
    } else {
      $centreCode = $_SESSION["CentreCode"];
    }

    include_once("student_func.php");

    /* $numperpage=20; */
    $query = "p=$p&m=$m&s=$s";

    $str = $_GET["s"];
    $student_status = $_GET["student_status"];

    //$browse_sql="SELECT * from `student` s where ((deleted=0 and student_status<>'I') or (deleted=1 and exists(select * from dropout d where d.student_code=s.student_code";
    $browse_sql = "SELECT DISTINCT s.*, se.email parent_email, se.mobile_country_code, se.mobile parent_mobile, se.full_name parent_name FROM `student` s left join (SELECT * FROM `student_emergency_contacts` group BY `student_code` order by id asc) se on s.`student_code`=se.student_code where ((deleted=0 and student_status<>'I') or (deleted=1 and exists(select * from dropout d where d.student_code=s.student_code";

    if ($centreCode != 'ALL') {
      $browse_sql .= " and centre_code='" . $centreCode . "'))) and centre_code='" . $centreCode . "'";
    } else {
      $browse_sql .= ")))";
    }

    if ($_GET["s"] != "") {
      $browse_sql .= " and (student_code like '%$str%' or `name` like '%$str%')";
    }

    if ($student_status != "") {
      $browse_sql .= " and student_status='$student_status'";
    }
    $browse_sql .= " and s.start_date_at_centre <= '".$year_end_date." 23:59:59' and extend_year >= '$year'";
    $order_query = $query;
    $order_arrow = "&uarr;";
    if ($_GET['order'] == 'asc') {
      $order_query .= "&order_by=name&order=desc";
      $order_arrow = "&uarr;";
    } else if ($_GET['order'] == 'desc') {
      $order_query .= "&order_by=name&order=asc";
      $order_arrow = "&darr;";
    } else {
      $order_query .= "&order_by=name&order=desc";
      $order_arrow = "&uarr;";
    }

    if (isset($_GET["order_by"]) && isset($_GET["order"])) {
      $order_by = mysqli_real_escape_string($connection, $_GET["order_by"]);
      $order = mysqli_real_escape_string($connection, $_GET["order"]);
      $browse_sql .= " ORDER BY " . $order_by . " " . $order . " ";

      $query .= "&order_by=" .  $_GET["order_by"] . "&order=" . $_GET['order'];
    } else {
      $browse_sql .= " ORDER BY student_status asc ";
    }

    /* $pagination=getPagination($pg, $numperpage, $query, $browse_sql, $start_record, $num_row);*/
    /* $browse_sql.=" limit $start_record, $numperpage"; */
    $browse_result = mysqli_query($connection, $browse_sql);
    if ($browse_result) {
      $browse_num_row = mysqli_num_rows($browse_result);
    } else {
      $browse_num_row = 0;
    }
?>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../lib/uikit/css/uikit.gradient.min6.my.css">
    <link rel="stylesheet" href="../css/my1.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
      @page {
        size: auto;
      }

      @media print {
        * {
          color-adjust: exact;
          -webkit-print-color-adjust: exact;
          print-color-adjust: exact;
        }

        #btnPrint {
          display: none;
        }
      }
    </style>

    <div class="uk-margin-right uk-margin-left uk-margin-top">


      <div class="uk-width-1-1 myheader text-center">
        <h2 class="uk-text-center myheader-text-color myheader-text-style">Student Population Report</h2>
      </div>
      <br>
      <div class="nice-form text-center">
        <div class=" table-responsive">
          <div class="uk-grid">
            <div class="uk-width-medium-5-10">
              <table class="uk-table">
                <tr>
                  <td class="uk-text-bold">Centre Name</td>
                  <td><?php echo getCentreName($centreCode) ?></td>
                </tr>
                <tr>
                  <td class="uk-text-bold">Prepare By</td>
                  <td><?php echo $_SESSION["UserName"] ?></td>
                </tr>
                <tr>
                  <td colspan="2" class="uk-text-bold">NOTE: To be submitted termly with the order form</td>
                </tr>
              </table>
            </div>



            <div class="uk-width-medium-5-10">
              <table class="uk-table">
                <tr>
                  <td class="uk-text-bold">Academic Year</td>
                  <td><?php echo $year ?></td>
                </tr>
                <tr>
                  <td class="uk-text-bold">School Term</td>
                  <td>
                  <?php
				  //echo "sdfdsf";
                          if (isset($year) && $year != '') {
                      
                      $sql = "SELECT * from codes where module='SCHOOL_TERM' and `year` = '$year'";
                       //Print_r($sql);
                      $centre_result = mysqli_query($connection, $sql);
                      while ($centre_row = mysqli_fetch_assoc($centre_result)) {
                        //echo $centre_row['category'] . "/" . $centre_row['year'] . "<br>";
						$str .= $centre_row['category'] . ', ';
                      }
					  echo rtrim($str, ", ");
                    }
                    ?>

                  </td>
                </tr>
                <tr>
                  <td class="uk-text-bold">Date of submission</td>
                  <td><?php echo date("Y-m-d H:i:s") ?></td>
                </tr>
              </table>
            </div>
          </div>
          <br>
          <div class="table-responsive">
            <table class="table table-bordered" style="text-align:center;border:1px; width: 100%;">
              <thead>
                <tr class="uk-text-bold">
                  <!--  class="uk-block-primary uk-text-contrast" -->
                  <td style="font-weight:bold;font-size: 16px;">Active</td>
                  <td style="font-weight:bold;font-size: 16px;">Deferred</td>
                  <td style="font-weight:bold;font-size: 16px;">Graduated</td>
                  <td style="font-weight:bold;font-size: 16px;">Dropout</td>
                  <td style="font-weight:bold;font-size: 16px;">Suspended</td>
                  <td style="font-weight:bold;font-size: 16px;">Transfered</td>
                  <td style="font-weight:bold;font-size: 16px;">Total</td>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><?php echo getStudentStatusCount($centreCode, 'A'); ?></td>
                  <td><?php echo getStudentStatusCount($centreCode, 'D'); ?></td>
                  <td><?php echo getStudentStatusCount($centreCode, 'G'); ?></td>
                  <td><?php echo getDropoutCount($centreCode); ?></td>
                  <td><?php echo getStudentStatusCount($centreCode, 'S'); ?></td>
                  <td><?php echo getStudentStatusCount($centreCode, 'T'); ?></td>
                  <?php
                  $active = getStudentStatusCount($centreCode, 'A');
                  $deferred = getStudentStatusCount($centreCode, 'D');
                  $graduated = getStudentStatusCount($centreCode, 'G');
                  //$dropout = getStudentStatusCount($centreCode, 'I');
                  $dropout = getDropoutCount($centreCode);
                  $suspended = getStudentStatusCount($centreCode, 'S');
                  $transfered = getStudentStatusCount($centreCode, 'T');
                  $total = $active + $deferred + $graduated + $dropout + $suspended + $transfered;
                  ?>
                  <td style="font-size: 24px;  border-right: 1px solid #d3dbe2!important"><?php echo $total; ?></td>
                </tr>

              </tbody>
            </table>
          </div>
          <table class="table table-bordered" style="width: 100%;">
            <thead>
              <tr class="uk-text-bold">
                <td data-uk-tooltip="{pos:top}" title="centre code">Centre Name</td>
                <td>Photo</td>
                <td>Student Name</td>
                <td>Parent's Name</td>
                <td>Parent's Contact</td>
                <td>Gender</td>
                <td>Age</td>
                <td>Mykid / Passport No.</td>
                <td>Status</td>
              </tr>
            </thead>
            <tbody>

              <?php

              if ($browse_num_row > 0) {
                while ($browse_row = mysqli_fetch_assoc($browse_result)) {
                  $sha1_id = sha1($browse_row["id"]);
              ?>
                  <tr class="uk-text-small">
                    <td><?php echo getCentreName($centreCode) ?></td>
                    <td>
                      <?php
                      $student_photo_src = getStudentPhotoSrc($browse_row['photo_file_name']);
                      if ($student_photo_src) {
                        echo '<img src="' . $student_photo_src . '" width="60px" height="80px">';
                      }
                      ?>
                    </td>
                    <!-- <td><?php // echo $browse_row["centre_code"]
                              ?></td>
								  
                                <td><?php // echo $browse_row["student_code"]
                                    ?></td>-->
                    <td><?php echo $browse_row["name"] ?></td>
                    <td><?php echo $browse_row["parent_name"] ?></td>
                    <td><?php echo "+" . $browse_row["mobile_country_code"] . $browse_row["parent_mobile"] ?><br><?php echo $browse_row["parent_email"] ?></td>
                    <td><?php echo $browse_row["gender"] ?></td>
                    <td>
                      <?php
                        $age = date('Y',strtotime($year_start_date)) - date('Y',strtotime($browse_row['dob'])); 

                        echo $age; 
                      ?>
                    </td>
                    <td><?php echo $browse_row["nric_no"] ?></td>
                    <td><?php echo displayStudentStatus($browse_row["student_status"]) ?></td>
                  </tr>
              <?php
                }
              } else {
                echo "<tr><td colspan='8'>No Record Found</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="uk-margin-top" style="text-align: right;">
        <button id="btnPrint" class="uk-button" onclick="printDialog();">Print it</button>
      </div>
      <br>
    </div>
    <script>
      function printDialog() {
        window.print();
      }

      printDialog();
      opener.location.reload();
    </script>
<?php
  } else {
    echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
  }
} else {
  header("Location: ../index.php");
}
?>