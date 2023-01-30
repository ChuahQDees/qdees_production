<?php

session_start();

include_once("../mysql.php");

include_once("functions.php");

foreach ($_POST as $key => $value) {

  $$key = mysqli_real_escape_string($connection, $value); //$centre_code, $year, $month

}

if ($method == "print") {

  include_once("../uikit1.php");
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
function getCentreName($centre_code)
{
  if ($centre_code == "")
    return "All Centres";

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


function convertCompanyName($name)
{

  global $connection;

  $sql = "SELECT * FROM `centre` where `company_name` LIKE '%$name%' LIMIT 1";

  $result = mysqli_query($connection, $sql);

  $rowcount = mysqli_num_rows($result);

  $row = mysqli_fetch_assoc($result);



  if ($rowcount == 0) {

    return false;
  } else {

    return $row['centre_code'];
  }
}



$current_year = date("Y");

$low_year = $current_year - 1;

$high_year = $current_year + 2;



for ($i = $low_year; $i <= $high_year; $i++) {

  $loop_year .= "$i,";
}

$loop_year = substr($loop_year, 0, -1);

if ($_SESSION["isLogin"] == 1) {

  if (($_SESSION["UserType"] == "S") || ($_SESSION["UserType"]=="H") || ($_SESSION["UserType"]=="C") || ($_SESSION["UserType"]=="R") || ($_SESSION["UserType"]=="CM") || ($_SESSION["UserType"]=="T")) {

    $sql="SELECT count(student_id) as no_of_student, student_status, centre_status_name, centre_code, company_name, OC, id from(
    SELECT s.id student_id, s.student_status, cs.name as centre_status_name, s.centre_code, c.company_name, c.pic as OC, cs.id
     from student s, centre c, centre_status cs where c.centre_status_id = cs.id and s.centre_code = c.centre_code and s.deleted='0' and s.student_status = 'A' and s.extend_year >= '".$_SESSION['Year']."' and s.start_date_at_centre <= '".$year_end_date."'";

    if (isset($centreCode) && $centreCode != 'ALL') {

      $isCentreCode = convertCompanyName($centreCode);

      if ($centreCode == NULL) {

        // $sql .=" and s.centre_code = '$centreCode'";

      } else {

        // $sql .=" and s.centre_code = '$isCentreCode'";

        $sql .= " and s.centre_code = '$centreCode'";
      }
    }

    if (isset($oc) && $oc != 'ALL') {

      $sql .= " and centre.pic = '$oc'";
    }

    if (isset($centreStatus) && $centreStatus != 'ALL') {

      $sql .= " and cs.name = '$centreStatus'";
    }

    if (isset($yearMonth) && $yearMonth != '') {

      $month = substr($yearMonth, 4, 2);

      $year = substr($yearMonth, 0, -2);

      if (str_split($yearMonth, 4)[1] != 13) {
        //$sql .= " and ((MONTH(fl.programme_date) >= '$month' and MONTH(fl.programme_date) <= '$month') or (MONTH(fl.programme_date_end) >= '$month' and MONTH(fl.programme_date_end) >= '$month')) and year(fl.programme_date) = '$year'";
      } else {

        //$sql .= " and year(fl.programme_date) = '$year'";

        $month = 'All';
      }
    } else {

      $year = $_SESSION["Year"];

      $month = 'All';

      //$sql .= " and year(fl.programme_date) = '" . $_SESSION["Year"] . "'";
    }

    if (isset($courseName) && $courseName != 'ALL') {

      //$sql .= " and f.subject like '$courseName%'";
    }

    if (isset($courseSubject) && $courseSubject != 'ALL') {

      //$sql .= " and f.subject like '$courseSubject%'";
    }
    
    $sql .= " group by s.centre_code, s.id";
    $sql .= " )ad group by centre_code ";
    
    //$sql.=" order by id";

    $result = mysqli_query($connection, $sql);

    $output_array = [];

    $OC_array = [];

    while ($row = mysqli_fetch_assoc($result)) {

        $OC_array[$row['company_name']] = $row['OC'];

        $output_array[$row['centre_status_name']][$row['company_name']]['student'][displayStudentStatus($row['student_status'])] += $row['no_of_student'];

    }
?>

    <div class="uk-margin-right">

      <div class="uk-width-1-1 myheader text-center mt-5" style="color:white; text-align:center;">

        <h2 class="uk-text-center myheader-text-color myheader-text-style">Summary Student Population Report</h2>

        For <?php echo ($month != 'All' ? date('F', mktime(0, 0, 0, $month, 10)) : 'All months in') . ' ' . $year; ?><br>

      </div>
      <div class="uk-overflow-container">
        <div class="uk-grid">
          <div class="uk-width-medium-5-10">
            <table class="uk-table">
              <tr>
                <td class="uk-text-bold">Centre Name</td>
                <td>
                  <?php echo getCentreName($centreCode); ?>
                </td>
              </tr>
              <tr>
                <td class="uk-text-bold">Prepare By</td>
                <td><?php echo $_SESSION["UserName"] ?></td>
              </tr>
              <tr id="note">
                <td colspan="2" class="uk-text-bold">NOTE: To be submitted termly with the order form</td>
              </tr>
            </table>
          </div>
          <div class="uk-width-medium-5-10">
            <table class="uk-table">
              <tr>
                <td class="uk-text-bold">Academic Year</td>
                <?php if ($selected_year) : ?>
                  <td><?php echo $selected_year ?></td>
                <?php else : ?>
                  <td><?php echo $_SESSION["Year"]; ?></td>
                <?php endif; ?>
              </tr>
              <tr>
                <td class="uk-text-bold">School Term</td>
                <td>
                  <?php
                     $month = date("m");
                     $year = $_SESSION["Year"];
                     if (isset($selected_month) && $selected_month != '') {
                        $month = substr($selected_month, 4, 2);
                        $year = substr($selected_month, 0, -2);
                     }
                        //$sql = "SELECT * from codes where year=" . $year;
						        $sql = "SELECT * from codes where module='SCHOOL_TERM'";
                    if($month!="13"){
                      $sql .= " and from_month<=$month and to_month>=$month";
                    }
                    $sql .= " order by category";
                        $centre_result = mysqli_query($connection, $sql);
                        $str = "";
                      while ($centre_row = mysqli_fetch_assoc($centre_result)) {
                        $str .= $centre_row['category'] . ', ';
                      }
                      echo rtrim($str, ", ");
                     //}
                     ?>
                </td>
              </tr>
              <tr>
                <td class="uk-text-bold">Date of submission</td>
                <td><?php echo date("Y-m-d H:i:s") ?></td>
              </tr>
              <tr id="note1" style="display: none;">
                <td colspan="2" class="uk-text-bold">NOTE: To be submitted termly with the order form</td>
              </tr>
            </table>
          </div>
        </div>

        <table class="uk-table">

          <tr class="uk-text-bold uk-text-small">

            <td>No</td>
            <td>Centre Category</td>
            <td>Centre Name</td>
            <td>Active</td>
            <td>Deferred</td>
            <td>Transferred</td>
            <td>Dropout</td>
            <td>Amount</td>

          </tr>

          <?php
            $grand_total = 0;
            $centre_count = 0;
            foreach ($output_array as $key1 => $value1) {
          ?>
            <tr>
              <td></td>
              <td colspan="8"><?php echo $key1 ?></td>
            </tr>
          <?php
            $count = $centreStatus_total = 0;
            $subtotal['Active'] = $subtotal['Deferred'] = $subtotal['Transferred'] = $subtotal['Dropout'] = $subtotal['id'] = 0;

            foreach ($value1 as $key2 => $value2) {
              $centre_count++;
              $count++;

          ?>

              <tr>

                <td colspan="2"><?php echo $count ?></td>

                <td colspan="1"><?php echo $key2 ?></td>

                <td colspan="5"></td>

              </tr>

              <?php

              $centre_total = 0;
              $subtotal['Active'] = $subtotal['Deferred'] = $subtotal['Transferred'] = $subtotal['Dropout'] = $subtotal['id'] = 0;

              foreach ($value2 as $key3 => $value3) {

                $subtotal['Active'] += $value3['Active'];
                $subtotal['Deferred'] += $value3['Deferred'];
                $subtotal['Transferred'] += $value3['Transferred'];
                $subtotal['Dropout'] += $value3['Dropout'];
                $subtotal['id'] == $value3['id'];

                $centre_total = $centre_total + $value3['Active'] + $value3['Deferred'] + $value3['Transferred'] + $value3['Dropout'];

              ?>

                <tr>

                  <td></td>

                  <td></td>

                  <td colspan=""><?php echo $key3 ?></td>

                  <td><?php echo $value3['Active'] ? $value3['Active'] : 0 ?></td>

                  <td><?php echo $value3['Deferred'] ? $value3['Deferred'] : 0 ?></td>

                  <td><?php echo $value3['Transferred'] ? $value3['Transferred'] : 0 ?></td>

                  <td><?php echo $value3['Dropout'] ? $value3['Dropout'] : 0 ?></td>

                  <td></td>

                </tr>

              <?php
              }
              $centreStatus_total += $centre_total;
              ?>

              <tr>

                <td colspan="3" style="text-align: left;font-weight: bold;">Total For Each Status:</td>

                <td><?php echo $subtotal['Active'] ?></td>

                <td><?php echo $subtotal['Deferred'] ?></td>

                <td><?php echo $subtotal['Transferred'] ?></td>

                <td><?php echo $subtotal['Dropout'] ?></td>

                <td></td>

              </tr>

              <tr>

                <td colspan="7" style="text-align: left;font-weight: bold;">Total For Centre: <?php echo $key2 ?></td>

                <td><?php echo $centre_total ?></td>

              </tr>

            <?php

            }

            $grand_total += $centreStatus_total;

            ?>

            <tr>

              <td colspan="7" style="text-align: left;font-weight: bold;">Subtotal For: <?php echo $key1 ?></td>

              <td><?php echo $centreStatus_total ?></td>

            </tr>

          <?php

          }

          ?>

          <tr>

            <td colspan="7" class="uk-text-bold uk-text-large">Grand Total of All Students</td>

            <td class="uk-text-bold uk-text-large text-info"><?php echo $grand_total; ?></td>

          </tr>

          <tr>

            <td colspan="7" class="uk-text-bold uk-text-large">Total Number of Centre</td>

            <td class="uk-text-bold uk-text-large text-info"><?php echo $centre_count ?></td>

          </tr>

        </table>

      </div>

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
  <script>
    $(document).ready(function() {
      var method = '<?php echo $method ?>';
      if (method == "print") {
        window.print();
      }
    });
  </script>
