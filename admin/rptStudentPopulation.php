<?php if (isset($_GET['selected_year']) && $_GET['selected_year'] != "") { ?>
  <a href="/index.php?p=rpt_student_population">
    <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
  </a>
<?php } else { ?>
  <a href="/">
    <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
  </a>
<?php } ?>
<span>
  <span class="page_title"><img src="/images/title_Icons/Student Population-1.png">Student Population Report
  </span>

  <?php
  include_once("admin/functions.php");

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
    global $connection, $year_end_date;
    if (isset($_GET['selected_year'])) {
      $year = $_GET['selected_year'];
    } else {
      $year = $_SESSION['Year'];
    }
    

    // $sql = "SELECT COUNT(id) as total FROM student WHERE ((deleted=0 and student_status<>'I') or (deleted=1 and exists(select * from dropout d where d.student_code=student.student_code and centre_code='".mysqli_real_escape_string($connection,$centre_code)."'))) and centre_code ='". mysqli_real_escape_string($connection,$centre_code) ."' AND student_status='" . mysqli_real_escape_string($connection,$status) . "' and extend_year = '$year'";
    //$sql = "SELECT COUNT(id) as total FROM student WHERE student_status='" . mysqli_real_escape_string($connection,$status) . "' and year(start_date_at_centre) <= '$year' and extend_year >= '$year'";

    // $sql = "SELECT COUNT(id) as total FROM student WHERE student_status='$status' and extend_year = '$year' ";
    // $sql = "SELECT COUNT(id) as total FROM student WHERE ((deleted=0 and student_status<>'I') or (deleted=1 and exists(select * from dropout d where d.student_code=student.student_code and centre_code='".mysqli_real_escape_string($connection,$centre_code)."'))) and centre_code ='". mysqli_real_escape_string($connection,$centre_code) ."' AND student_status='" . mysqli_real_escape_string($connection,$status) . "' and extend_year = '$year'";

    //$sql="SELECT COUNT(id) as total from student where student_status = '$status' and year(start_date_at_centre) <= '$year' and extend_year >= '$year' and deleted='0' ";
    $sql = "SELECT COUNT(id) as total from student where student_status = '$status' and start_date_at_centre <= '".$year_end_date." 23:59:59' and extend_year >= '$year' ";

    if ($centre_code != 'ALL') {
      $sql .= " and centre_code ='" . mysqli_real_escape_string($connection, $centre_code) . "'";
    }

    if ($with_deleted || $status == "I") {
      $sql .= " AND deleted=1";
    } else {
      $sql .= " AND deleted=0";
    }
    //echo $sql; die;
    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_assoc($result);

    return $row['total'];
  }

  function getDropoutCount($centre_code)
  {
    global $connection, $year_end_date;

    $year = $_SESSION['Year'];

    $sql = "SELECT COUNT(d.id) as total FROM dropout d, student s where d.student_code=s.student_code and student_status= 'I' and d.centre_code ='" . mysqli_real_escape_string($connection, $centre_code) . "' and s.start_date_at_centre <= '".$year_end_date." 23:59:59' and extend_year >= '$year'";

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
    $num_row = mysqli_num_rows($result); //echo $num_row; die;

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

    if ((($_SESSION["UserType"] == "S") || ($_SESSION["UserType"] == "O") || ($_SESSION["UserType"] == "A") || ($_SESSION["UserType"] == "H") || ($_SESSION["UserType"] == "C") || ($_SESSION["UserType"] == "R") || ($_SESSION["UserType"] == "CM") || ($_SESSION["UserType"] == "T")) ||
      (hasRightGroupXOR($_SESSION["UserName"], "StudentEdit|StudentView"))
    ) {
   
      include_once("lib/pagination/pagination.php");
      $p = $_GET["p"];
      $m = isset($_GET["m"]) ? $_GET["m"] : '';
      $get_sha1_id = isset($_GET["id"]) ? $_GET["id"] : '';
      $pg = isset($_GET["pg"]) ? $_GET["pg"] : '';
      $mode = isset($_GET["mode"]) ? $_GET["mode"] : '';
      if (isset($_GET['selected_year'])) {
        $year = $_GET['selected_year'];
      } else {
        $year = $_SESSION['Year'];
      }

      if (($_SESSION["UserType"] == "S") || ($_SESSION["UserType"] == "H") || ($_SESSION["UserType"] == "C") || ($_SESSION["UserType"] == "R") || ($_SESSION["UserType"] == "CM") || ($_SESSION["UserType"] == "T")) {
        if (isset($_GET["centre_code"]) && $_GET["centre_code"] != "") {
          $centreCode = $_GET["centre_code"];
        } else {
          $centreCode = 'ALL';
        }
      } else {
        $centreCode = $_SESSION["CentreCode"];
      }

      include_once("student_func.php");

      //$numperpage=20;
      $query = "p=$p&centre_code=$centreCode&selected_year=$year";

      $str = isset($_GET["s"]) ? $_GET["s"] : '';
      $student_status = isset($_GET["student_status"]) ? $_GET["student_status"] : '';

      //$browse_sql="SELECT * from `student` s where ((deleted=0 and student_status<>'I') or (deleted=1 and exists(select * from dropout d where d.student_code=s.student_code";
      //$browse_sql="SELECT * from student where deleted='0' ";
      $browse_sql = "SELECT DISTINCT s.*, se.email parent_email, se.mobile_country_code, se.mobile parent_mobile, se.full_name parent_name FROM `student` s left join (SELECT * FROM `student_emergency_contacts` group BY `student_code` order by id asc) se on s.`student_code`=se.student_code where 1=1 ";

      if ($centreCode != 'ALL') {
        $browse_sql .= " and centre_code='" . $centreCode . "' and centre_code='" . $centreCode . "'";
      } else {
        $browse_sql .= " ";
      }

      if (isset($_GET["s"]) && $_GET["s"] != "") {
        $browse_sql .= " and (student_code like '%$str%' or `name` like '%$str%')";
      }

      if ($student_status != "") {
        $browse_sql .= " and student_status='$student_status'";
      }
      if ($student_status == "I") {
        $browse_sql .= " and deleted='1'";
      } else {
        $browse_sql .= " and deleted='0'";
      }
      $browse_sql .= " and start_date_at_centre <= '".$year_end_date." 23:59:59' and extend_year >= '$year'";
	
      $order_query = $query;
      $order_arrow = "&uarr;";
      if (isset($_GET['order']) && $_GET['order'] == 'desc') {
        $order_query .= "&order_by=name&order=asc";
        $order_arrow = "&darr;";
      } else if (isset($_GET['order']) && $_GET['order'] == 'asc') {
        $order_query .= "&order_by=name&order=desc";
        $order_arrow = "&uarr;";
      } else {
        $order_query .= "&order_by=name&order=asc";
        $order_arrow = "&darr;";
      }

      //$order_status_query = $query;
      //$order_status_query.="&order_by=student_status&order=asc";
      //$order_status_query_arrow = "&uarr;";

      if (isset($_GET["order_by"]) && isset($_GET["order"])) {
        $order_by = mysqli_real_escape_string($connection, $_GET["order_by"]);
        $order = mysqli_real_escape_string($connection, $_GET["order"]);
        $browse_sql .= " ORDER BY " . $order_by . " " . $order . " ";

        $query .= "&order_by=" .  $_GET["order_by"] . "&order=" . $_GET['order'];
      } else {
        $browse_sql .= " ORDER BY student_status asc, student_code";
      }
      
      //$pagination = getPagination($pg, $numperpage, $query, $browse_sql, $start_record, $num_row);
      //$browse_sql.=" limit $start_record, $numperpage";
      $browse_result = mysqli_query($connection, $browse_sql);
      if ($browse_result) {
        $browse_num_row = mysqli_num_rows($browse_result);
      } else {
        $browse_num_row = 0;
      }
  ?>

      <script>
        function doDeleteRecord(id) {
          UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function() {
            $("#id").val(id);
            $("#frmDeleteRecord").submit();
          });
        }
      </script>
      <div class="uk-margin-right">
        <!--   <h3>Total Students: <?php /*echo getTotalStudents($_SESSION["CentreCode"]);*/ ?></h3>-->



        <style>
          table tr:not(:first-child):nth-of-type(even) {
            background: #f5f6ff;
          }

          table td {
            color: grey;
            font-size: 1.2em;
          }

          table td {
            border: none !important;
          }

          .btn-qdees {
            display: none;
          }
        </style>


        <br>
        <div class="uk-width-1-1 myheader">
          <h2 class="uk-text-center myheader-text-color myheader-text-style">Searching</h2>
        </div>

        <form class="uk-form" name="frmStudentSearch" id="frmStudentSearch" method="get">
          <input type="hidden" name="p" id="p" value="rpt_student_population">

          <div class="uk-grid">
            <div <?php if (($_SESSION["UserType"] == "S") || ($_SESSION["UserType"] == "H") || ($_SESSION["UserType"] == "C") || ($_SESSION["UserType"] == "R") || ($_SESSION["UserType"] == "CM") || ($_SESSION["UserType"] == "T")) { ?> style="width:35%;" <?php } ?> class="uk-form uk-grid uk-grid-small uk-width-4-10">
              <?php
              if (($_SESSION["UserType"] == "S") || ($_SESSION["UserType"] == "H") || ($_SESSION["UserType"] == "C") || ($_SESSION["UserType"] == "R") || ($_SESSION["UserType"] == "CM") || ($_SESSION["UserType"] == "T")) {
                $sql = "SELECT * from centre order by centre_code";
                $result = mysqli_query($connection, $sql);
              ?>
                <div style="width:60%;" class="uk-width-2-10">
                  Centre Name<br>

                  <input list="centre_code" id="screens.screenid" name="centre_code" value="<?php echo $_GET["centre_code"] ?>">

                  <datalist class="form-control" id="centre_code" style="display: none;">

                    <option value="ALL" <?php echo $centreCode == 'ALL' ? 'selected' : '' ?>>All Centre</option>

                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                      <option value="<?php echo $row['centre_code'] ?>" <?php echo $row['centre_code'] == $centreCode ? 'selected' : '' ?>><?php echo $row["company_name"] ?></option>

                    <?php

                    }

                    ?>

                  </datalist>
                </div>

              <?php
              } else {

                $centrecode = $_SESSION['CentreCode'];

                $sqlCentre = "SELECT centre_code, company_name from centre where centre_code = '$centrecode'  limit 1 ";
                $resultCentre = mysqli_query($connection, $sqlCentre);
                $rowCentre = mysqli_fetch_assoc($resultCentre);
           
              ?>
                <span class=" centre_n">
                  Centre Name<br>
                  <input style="width: 100%;border: 0;font-weight: bold;" name="centre_name" id="centre_name" value="<?php echo getCentreNameIndex($_SESSION["CentreCode"]) ?>" readonly>
                </span>
              <?php
              }
              ?>

              <div style="width: 36%;padding-right: 0px;padding-left: 0;" class="uk-width-medium-1-3 centre_n">
                Year Selection<br>
                <select name='selected_year' id='selected_year' class='uk-width-1-1'>
                  <?php
                  $year_list = mysqli_query($connection,"SELECT `year` FROM `schedule_term` WHERE `centre_code` = '".$_SESSION['CentreCode']."' AND `deleted` = '0' GROUP BY `year`");

                  while($year_row = mysqli_fetch_array($year_list))
                  {
                ?>
                    <option value='<?php echo $year_row['year']; ?>' <?php echo $year == $year_row['year'] ? "selected" : "" ?>><?php echo $year_row['year'] ?></option>
                <?php
                  }

                  /* $current_year = $_SESSION['Year'];
                  $low_year = $current_year - 1;
                  $high_year = $current_year + 2;
                  for ($i = $low_year; $i <= $high_year; $i++) {
                  ?>
                    <option value='<?php echo $i ?>' <?php echo $year == $i ? "selected" : "" ?>><?php echo $i ?></option>
                  <?php
                  } */
                  ?>
                </select>
              </div>
            </div>

            <div class="uk-width-2-10">
              Status<br>
              <select name='student_status' id='student_status' style="width:100%;">
                <option selected value=''>Select Status</option>
                <option <?php if ($student_status == "A") {
                          echo "selected";
                        } ?> value='A'>Active</option>
                <option <?php if ($student_status == "D") {
                          echo "selected";
                        } ?> value='D'>Deferred</option>
                <option <?php if ($student_status == "G") {
                          echo "selected";
                        } ?> value='G'>Graduated</option>
                <option <?php if ($student_status == "I") {
                          echo "selected";
                        } ?> value='I'>Dropout</option>
                <option <?php if ($student_status == "S") {
                          echo "selected";
                        } ?> value='S'>Suspended</option>
                <option <?php if ($student_status == "T") {
                          echo "selected";
                        } ?> value='T'>Transferred</option>
              </select>
              <?php
              //$fields=array("A"=>"Active", "D"=>"Deferred", "G"=>"Graduated", "S"=>"Suspended", "T"=>"Transferred");
              // generateSelectArray($fields, "student_status", "class='uk-width-1-1 '", $data_array["student_status"]);
              ?>
              <span id="validationStudentStatus" style="color: red; display: none;">Please select Student Status</span>
            </div>
            <div class="uk-width-2-10" style="white-space: nowrap;">
              <br>
              <button class="uk-button">Show on screen</button>
              <a href="admin/student_population_report.php?centre_code=<?php echo $centreCode ?>&selected_year=<?php echo $year ?>&student_status=<?php echo $student_status ?>" target="_blank" class="uk-button">Print</a>
            </div>

          </div>
        </form>

        <div class="uk-width-1-1 myheader mt-5">
          <h2 class="uk-text-center myheader-text-color myheader-text-style">Student Population Report</h2>
        </div>
        <div class="uk-overflow-container">
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
                      $selected_year = isset($_GET['selected_year']) ? $_GET['selected_year'] : $_SESSION['Year'];

                      $sql = mysqli_query($connection,"SELECT `term_num` FROM `schedule_term` WHERE `year` = '".$selected_year."' AND `centre_code` = '".$_SESSION['CentreCode']."' AND `deleted` = 0 ORDER BY `term_num` ASC");

                      $str = "";
                      while ($term_row = mysqli_fetch_array($sql)) {
                    
                        $str .= $term_row['term_num'] . ', ';
                      }
                      echo rtrim($str, ", ");
                 
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
          <table class="table table-bordered mb-4" style="text-align:center;">
            <thead class="myheader">
              <tr>
                <th style="color: #fff; font-size: 20px">Active</th>
                <th style="color: #fff;  font-size: 20px">Deferred</th>
                <th style="color: #fff;  font-size: 20px">Graduated</th>
                <th style="color: #fff;  font-size: 20px">Dropout</th>
                <th style="color: #fff; font-size: 20px">Suspended</th>
                <th style="color: #fff;  font-size: 20px">Transfered</th>
                <th style="color: #fff;  font-size: 20px">Total</th>
              </tr>
            </thead>
            <tbody>
              <tr style="background-color: white">
                <td style="font-size: 24px;  border-right: 1px solid #d3dbe2!important"><?php echo getStudentStatusCount($centreCode, 'A'); ?></td>
                <td style="font-size: 24px;  border-right: 1px solid #d3dbe2!important"><?php echo getStudentStatusCount($centreCode, 'D'); ?></td>
                <td style="font-size: 24px;  border-right: 1px solid #d3dbe2!important"><?php echo getStudentStatusCount($centreCode, 'G'); ?></td>
                <td style="font-size: 24px;  border-right: 1px solid #d3dbe2!important"><?php echo  getStudentStatusCount($centreCode, 'I'); //getDropoutCount($centreCode) 
                                                                                        ?></td>
                <td style="font-size: 24px;  border-right: 1px solid #d3dbe2!important"><?php echo getStudentStatusCount($centreCode, 'S'); ?></td>
                <td style="font-size: 24px;  border-right: 1px solid #d3dbe2!important"><?php echo getStudentStatusCount($centreCode, 'T'); ?></td>
                <?php
                $active = getStudentStatusCount($centreCode, 'A');
                $deferred = getStudentStatusCount($centreCode, 'D');
                $graduated = getStudentStatusCount($centreCode, 'G');
                $dropout = getStudentStatusCount($centreCode, 'I');
                $suspended = getStudentStatusCount($centreCode, 'S');
                $transfered = getStudentStatusCount($centreCode, 'T');
                $total = $active + $deferred + $graduated + $dropout + $suspended + $transfered;
                ?>
                <td style="font-size: 24px;  border-right: 1px solid #d3dbe2!important"><?php echo $total; ?></td>
              </tr>
            </tbody>
          </table>
          <table class="uk-table">
            <tr class="uk-text-bold uk-text-small">
              <td data-uk-tooltip="{pos:top}" title="centre code">Centre Name</td>
              <td>Photo</td>
              <!--<td data-uk-tooltip="{pos:top}" title="Student Code">Student Code</td>-->
              <td>Student Name</td>
              <td>Parent's Name</td>
              <td>Parent's Contact</td>
              <td>Gender</td>
              <td>Age</td>
              <td>Mykid / Passport No.</td>
              <td>Status</td>
              <!-----<td>Action</td>------------->
            </tr>
            <?php

            if ($browse_num_row > 0) {
              // print_r(mysqli_fetch_assoc($browse_result)); die;
              while ($browse_row = mysqli_fetch_assoc($browse_result)) {
                $sha1_id = sha1($browse_row["id"]);
            ?>
                <tr class="uk-text-small">
                  <td><?php echo getCentreName($browse_row["centre_code"]) ?></td>
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
                  <td><?php echo "+".$browse_row["mobile_country_code"] .$browse_row["parent_mobile"] ?><br><?php echo $browse_row["parent_email"] ?></td>
                  <td><?php echo $browse_row["gender"] ?></td>
                  <td>
                    <?php
                      $age = date('Y',strtotime($year_start_date)) - date('Y',strtotime($browse_row['dob'])); 

                      echo $age; 
                    ?>
                  </td>
                  <td><?php echo $browse_row["nric_no"] ?></td>
                  <td><?php echo displayStudentStatus($browse_row["student_status"]) ?></td>
                  <!---<td>
                                    <?php /*
                                    if (hasRightGroupXOR($_SESSION["UserName"], "StudentEdit")) {
                                        ?>
                                        <a href="index.php?p=student_reg&m=<?php echo $m?>&id=<?php echo $sha1_id?>&mode=EDIT" data-uk-tooltip title="Edit <?php echo $browse_row['name']?>"><i class="fas fa-user-edit" style="font-size: 1.3em;"></i></a>
                                        <a href="index.php?p=dropout&student_sid=<?php echo sha1($browse_row['id'])?>" data-uk-tooltip title="Dropout <?php echo $browse_row['name']?>" id="btnDelete"><i style="font-size: 1.3em; color: #FF6e6e;" class="fas fa-box-open"></i></a>
                                        <?php
                                    } */
                                    ?>
                                </td>----------------->
                </tr>
            <?php
              }
            } else {
              echo "<tr><td colspan='8'>No Record Found</td></tr>";
            }
            ?>
          </table>
        </div>
        <?php
        //echo $pagination;
        ?>

      </div>

      <form name="frmDeleteRecord" id="frmDeleteRecord" method="get">
        <input type="hidden" name="p" value="<?php echo $p ?>">
        <input type="hidden" name="m" value="<?php echo $m ?>">
        <input type="hidden" name="id" id="id" value="">
        <input type="hidden" name="mode" value="DEL">
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