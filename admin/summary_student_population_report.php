<?php
session_start();
include_once("../mysql.php");
include_once("admin/functions.php");

function displayStudentStatus($status){
   switch($status){
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

if ($_SESSION["isLogin"]==1) {
  if ($_SESSION["UserType"]=="S") {
    $sql="SELECT count(*) as no_of_student, c.course_name, s.student_status, centre_status.name as centre_status_name, s.centre_code, centre.pic as OC from allocation a, course c, student s LEFT JOIN centre ON s.centre_code = centre.centre_code LEFT JOIN centre_status ON centre.centre_status_id = centre_status.id where a.course_id=c.id and a.student_id=s.id and s.student_status='A' and c.deleted=0 and s.deleted=0 and a.deleted=0";

    if (isset($_GET["centre_code"]) && $_GET["centre_code"] != 'Centre code') {
      $centreCode = $_GET["centre_code"];
      $sql .=" and s.centre_code = '$centreCode'";
    } else {
      $centreCode = 'Centre code';
    }

    if (isset($_GET["oc"]) && $_GET["oc"] != 'ALL') {
      $oc = $_GET["oc"];
      $sql .=" and centre.pic = '$oc'";
    } else {
      $oc = 'ALL';
    }

    if (isset($_GET["centre_status"]) && $_GET["centre_status"] != 'ALL') {
      $centreStatus = str_replace('%20', ' ', $_GET["centre_status"]);
      $sql .=" and centre_status.name = '$centreStatus'";
    } else {
      $centreStatus = 'ALL';
    }

    if (isset($_GET["yearMonth"]) && $_GET["yearMonth"] != '') {
      $month=substr($_GET["yearMonth"], 4, 2);
      $year=substr($_GET["yearMonth"], 0, -2);
      $sql .=" and month(a.allocated_date_time) = '$month' and year(a.allocated_date_time) = '$year'";
      $yearMonth = $_GET["yearMonth"];
    } else {
      $yearMonth = '';
    }

    if (isset($_GET["course_subject"]) && $_GET["course_subject"] != 'ALL') {
      $courseSubject = str_replace('%20', ' ', $_GET["course_subject"]);
      $sql .=" and c.course_name = '$courseSubject'";
    } else {
      $courseSubject = 'ALL';
    }

    if (isset($_GET["selected_lvl"]) && $_GET["selected_lvl"] != 'ALL') {
      $selected_lvl = $_GET["selected_lvl"];
      if (preg_match('/^IE/', $_GET["selected_lvl"], $output) == 1) {
        $sql .=" and SUBSTR(c.course_name, 1, 4) = '$selected_lvl'";
      } else {
        $sql .=" and SUBSTR(c.course_name, 1, 6) = '$selected_lvl'";
      }
    } else {
      $selected_lvl = 'ALL';
    }

    $sql .=" group by course_id, class_id";

    $result=mysqli_query($connection, $sql);
    $num_row=mysqli_num_rows($result);
    $output_array = [];
    $OC_array = [];
    while ($row=mysqli_fetch_assoc($result)) {
      if (preg_match('/^IE/', $row['course_name'], $output) == 1) {
        $OC_array[$row['centre_code']] = $row['OC'];
        $output_array[$row['centre_status_name']][$row['centre_code']]['IE'][displayStudentStatus($row['student_status'])] += $row['no_of_student'];
      } else if (preg_match('/^BIEP/', $row['course_name'], $output) == 1) {
        $OC_array[$row['centre_code']] = $row['OC'];
        $output_array[$row['centre_status_name']][$row['centre_code']]['BIEP'][displayStudentStatus($row['student_status'])] = $row['no_of_student'];

      } else if (preg_match('/^BIMP/', $row['course_name'], $output) == 1) {
        $OC_array[$row['centre_code']] = $row['OC'];
        $output_array[$row['centre_status_name']][$row['centre_code']]['BIMP'][displayStudentStatus($row['student_status'])] = $row['no_of_student'];
      }
    }
?>
    <div class="uk-margin-right">
      <div class="uk-width-1-1 myheader mt-5">
          <h2 class="uk-text-center myheader-text-color myheader-text-style">Summary Student Population Report</h2>
      </div>
      <div class="uk-overflow-container">
        <table class="uk-table">
          <tr class="uk-text-bold uk-text-small">
              <td>No</td>
              <td>Centre Code</td>
              <td>Operation Consultant</td>
              <td>Active</td>
              <td>Deferred</td>
              <td>Transferred</td>
              <td>Dropout</td>
          </tr>
          <?php
            foreach ($output_array as $key1 => $value1) {
          ?>
              <tr>
                <td></td>
                <td colspan="6"><?php echo $key1 ?></td>
              </tr>
              <?php
              $count = 0;
            $subtotal['Active'] = $subtotal['Deferred'] = $subtotal['Transferred'] = $subtotal['Dropout'] = 0;
              foreach ($value1 as $key2 => $value2) {
                $count ++;
              ?>
                <tr>
                  <td><?php echo $count ?></td>
                  <td><?php echo $key2 ?></td>
                  <td colspan="5"><?php echo $OC_array[$key2] ?></td>
                </tr>
                <?php
                foreach ($value2 as $key3 => $value3) {
                  $subtotal['Active'] += $value3['Active'];
                  $subtotal['Deferred'] += $value3['Deferred'];
                  $subtotal['Transferred'] += $value3['Transferred'];
                  $subtotal['Dropout'] += $value3['Dropout'];
                ?>
                  <tr>
                    <td></td>
                    <td><?php echo $key3 ?></td>
                    <td></td>
                    <td><?php echo $value3['Active'] ? $value3['Active'] : 0 ?></td>
                    <td><?php echo $value3['Deferred'] ? $value3['Deferred'] : 0 ?></td>
                    <td><?php echo $value3['Transferred'] ? $value3['Transferred'] : 0 ?></td>
                    <td><?php echo $value3['Dropout'] ? $value3['Dropout'] : 0 ?></td>
                  </tr>

              <?php
                }
              }
              ?>
              <tr>
                <td colspan="3">Subtotal For <?php echo $key1 ?></td>
                <td><?php echo $subtotal['Active'] ?></td>
                <td><?php echo $subtotal['Deferred'] ?></td>
                <td><?php echo $subtotal['Transferred'] ?></td>
                <td><?php echo $subtotal['Dropout'] ?></td>
              </tr>
            <?php
            }
            ?>

              <tr>
                
                <td colspan="3" class="uk-text-bold uk-text-large">Grand Total of All Students</td>
                <td class="uk-text-bold uk-text-large text-info"><?php 
                   $total_query="SELECT count(id) as total from student ";
                    $tot=mysqli_query($connection, $total_query);
                 while  ( $row=mysqli_fetch_assoc($tot))
                 {
                echo $row['total']; 
                 }?></td>
              
              </tr>

        </table>
    </div>
    <?php
    if ($msg!="") {
        echo "<script>UIkit.notify('$msg')</script>";
    }
  } else {
      echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
  }
} else {
    header("Location: index.php");
}
?>


