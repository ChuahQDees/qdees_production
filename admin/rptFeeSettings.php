<?php if ($_GET['selected_year'] != "") { ?>
  <a href="/index.php?p=rpt_student_population">
    <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
  </a>
<?php } else { ?>
  <a href="/">
    <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
  </a>
<?php } ?>
<span>
  <span class="page_title"><img src="/images/title_Icons/Student Population-1.png">Fee Settings Report 
  </span>

  <?php
  include_once("admin/functions.php");
function getStudentStatusCount4($status)
  {
    global $connection;
    $year = $_SESSION['Year'];
    $sql = "SELECT COUNT(id) as total from fee_structure where adjusted = 'Default'";
    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_assoc($result);

    return $row['total'];
  }
  function getStudentStatusCount5($status)
  {
    global $connection;
    $year = $_SESSION['Year'];
    $sql = "SELECT COUNT(id) as total from fee_structure where adjusted = 'Adjusted'";

    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_assoc($result);

    return $row['total'];
  }
  function getStudentStatusCount1($status)
  {
    global $connection;
    $year = $_SESSION['Year'];
    $sql = "SELECT COUNT(id) as total from fee_structure where status = 'Pending'";
    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_assoc($result);

    return $row['total'];
  }
  
 function getStudentStatusCount2($status)
  {
    global $connection;
    $year = $_SESSION['Year'];
    $sql = "SELECT COUNT(id) as total from fee_structure where status = 'Approved'";
    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_assoc($result);

    return $row['total'];
  }
  
  function getStudentStatusCount3($status)
  {
    global $connection;
    $year = $_SESSION['Year'];
    $sql = "SELECT COUNT(id) as total from fee_structure where status = 'Rejected'";
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
      //echo $_SESSION["UserType"];
      include_once("lib/pagination/pagination.php");
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

        <form class="uk-form" name="frmfeeSearch" id="frmfeeSearch" method="get">
          <input type="hidden" name="p" id="p" value="rptFeeSettings">

          <div class="uk-grid">
            <div <?php if (($_SESSION["UserType"] == "S") || ($_SESSION["UserType"] == "H") || ($_SESSION["UserType"] == "C") || ($_SESSION["UserType"] == "R") || ($_SESSION["UserType"] == "CM") || ($_SESSION["UserType"] == "T")) { ?> style="padding-right: 0px;" <?php } ?> class="uk-form uk-grid uk-grid-small ">
              <?php
              if (($_SESSION["UserType"] == "S") || ($_SESSION["UserType"] == "H") || ($_SESSION["UserType"] == "C") || ($_SESSION["UserType"] == "R") || ($_SESSION["UserType"] == "CM") || ($_SESSION["UserType"] == "T")) {
                $sql = "SELECT * from centre order by centre_code";
                $result = mysqli_query($connection, $sql);
              ?>
                <div style="width:16%;padding-right: 0px;" class="uk-width-2-10">
                  Centre Name<br>

                  <input list="centre_name" id="screens.screenid" name="centre_name" value="<?php echo $_GET["centre_name"] ?>">

                  <datalist class="form-control" id="centre_name" style="display: none;">

                    <option value="ALL" <?php echo $centreCode == 'ALL' ? 'selected' : '' ?>>All Centre</option>

                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                      <option value="<?php echo $row['company_name'] ?>" <?php echo $row['centre_code'] == $centreCode ? 'selected' : '' ?>><?php echo $row["centre_code"] ?></option>

                    <?php

                    }

                    ?>

                  </datalist>
                </div>

              <?php
              } 
			  // else {

                // $centrecode = $_SESSION['CentreCode'];

                // $sqlCentre = "SELECT centre_code, company_name from centre where centre_code = '$centrecode'  limit 1 ";
                // $resultCentre = mysqli_query($connection, $sqlCentre);
                // $rowCentre = mysqli_fetch_assoc($resultCentre);
                // var_dump($rowCentre["kindergarten_name"]);die; 
              ?>
                <!--<span class=" centre_n">
                  Centre Name<br>
                  <input style="width: 100%;border: 0;font-weight: bold;" name="centre_name" id="centre_name" value="<?php// echo getCentreNameIndex($_SESSION["CentreCode"]) ?>" readonly>
                </span>-->
              <?php
              // }
              ?>

              <div style="width:10%;padding-right: 0px;padding-left: 5px;" class="uk-width-medium-1-3 centre_n">
                Fees <br>
                <select name='fees' id='fees' class='uk-width-1-1'>
				 <option selected value=''>Select Status</option>
					<option <?php if ($_GET['fees'] == "Default") {
                          echo "selected";
                        } ?> value='Default'>Default</option>
					<option <?php if ($_GET['fees'] == "Adjusted") {
                          echo "selected";
                        } ?> value='Adjusted'>Adjusted</option>
                </select>
              </div>
            

            <div class="uk-width-2-10" style="padding-right: 0px;padding-left: 5px;width: 10%;">
              Status <br>
              <select name='status' id='status'>
                <option selected value=''>Select Status</option>
                <option <?php if ($_GET['status'] == "Pending") {
                          echo "selected";
                        } ?> value='Pending'>Pending</option>
                <option <?php if ($_GET['status'] == "Approved") {
                          echo "selected";
                        } ?> value='Approved'>Approved</option>
                <option <?php if ($_GET['status'] == "Rejected") {
                          echo "selected";
                        } ?> value='Rejected'>Rejected</option>
              </select>
              <?php
              //$fields=array("A"=>"Active", "D"=>"Deferred", "G"=>"Graduated", "S"=>"Suspended", "T"=>"Transferred");
              // generateSelectArray($fields, "student_status", "class='uk-width-1-1 '", $data_array["student_status"]);
              ?>
              <span id="validationStudentStatus" style="color: red; display: none;">Please select Student Status</span>
            </div>
			<div class="uk-width-2-10" style="padding-right: 0px;padding-left: 4px;width: 10%;">
              Level <br>
              <select name='level' id='level'>
                <option selected value=''>Select Level</option>
                <option <?php if ($_GET['level'] == "EDP") {
                          echo "selected";
                        } ?> value='EDP'>EDP</option>
                <option <?php if ($_GET['level'] == "QF1") {
                          echo "selected";
                        } ?> value='QF1'>QF1</option>
                <option <?php if ($_GET['level'] == "QF2") {
                          echo "selected";
                        } ?> value='QF2'>QF2</option>
				<option <?php if ($_GET['level'] == "QF3") {
                          echo "selected";
                        } ?> value='QF3'>QF3</option>
              </select>
              
            </div>
			<div class="uk-width-2-10" style="padding-right: 0px;padding-left: 0px;width: 9.5%; position: relative;bottom: 22px;">
              Programme<br>Package <br>
              <select name='pp' id='pp'>
                <option selected value=''>Select Level</option>
                <option <?php if ($_GET['pp'] == "Full Day") {
                          echo "selected";
                        } ?> value='Full Day'>Full Day</option>
                <option <?php if ($_GET['pp'] == "Half Day") {
                          echo "selected";
                        } ?> value='Half Day'>Half Day</option>
                <option <?php if ($_GET['pp'] == "3/4 Day") {
                          echo "selected";
                        } ?> value='3/4 Day'>3/4 Day</option>
              </select>
            </div>
            <div class="uk-width-2-10" style="width: 12%;padding-right: 0px;padding-left: 2px;">
			Country<br>
				<input name="country" id="country" value="<?php echo $_GET['country'] ?>">			
			</div>
			<!--<div class="uk-width-2-10" style="padding-right: 0px;padding-left: 15px;">
				Level<br>
				<input name="level " id="level" value="<?php// echo $_GET['level'] ?>">			
			</div>-->
			<!--<div class="uk-width-2-10" style="padding-right: 0px; padding-left: 5px;">
				Programme Package<br>
				<input name="pp" id="pp" value="<?php// echo $_GET['pp'] ?>">			
			</div>-->
			<div class="uk-width-2-10" style="padding-right: 0px;margin-top: 12px; width: 12%;position: relative;bottom: 33px;padding-left: 1px;">
				Name of fee<br>structure <br>
				<input name="name" id="name" value="<?php echo $_GET['name'] ?>">			
			</div>
			<!--<div class="uk-width-2-10" style="padding-right: 0px;margin-top: 12px;">
				Month/Year selection<br>
				<input name="month_year" id="month_year" value="">			
			</div>-->
            <div class="uk-width-2-10" style="white-space: nowrap;">
              <br>
              <button class="uk-button">Search</button>
              <!--<a href="admin/rptFeeSettings.php?centre_code=<?php // echo $centreCode ?> target="_blank" class="uk-button">Print</a>-->
			   <button onclick="printDiv('print_1')" target="_blank" class="uk-button">Print</button>
            </div>
            </div>
          </div>
        </form>
<div id="print_1">
        <div class="uk-width-1-1 myheader mt-5">
          <h2 class="uk-text-center myheader-text-color myheader-text-style">Fee Settings Report</h2>
        </div>
		<div class="nice-form">
      <div class="uk-grid">
         <div class="uk-width-medium-5-10">
            <table class="uk-table" style="width: 100%;">
               <tr>

                  <td class="uk-text-bold">Centre Name</td>
                  <td>All Centres<?php// echo getCentreName($centre_code); ?></td>
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
                  <td><?php 
                    if(!empty($selected_month)) {
                      $str_length = strlen($selected_month);
                      echo str_split($selected_month, ($str_length - 2))[0];
                   } else { 
                      echo $_SESSION['Year'];
                   }
                  ?></td>
               </tr>
               <tr>
                  <td class="uk-text-bold">School Term</td>
                  <td>
                     <?php
                     $month = date("m");
                     $year = $_SESSION['Year'];
                     if (isset($selected_month) && $selected_month != '') {
                        $year_length = strlen($selected_month);
                        $month = substr($selected_month, ($year_length - 2), 2);
                        $year = substr($selected_month, 0, -2);
                     }
                        //$sql = "SELECT * from codes where year=" . $year;
						$sql = "SELECT * from codes where module='SCHOOL_TERM'";
                    if($month!="13"){
                      $sql .= " and from_month<=$month and to_month>=$month";
                    }
                    $sql .= " order by category";
                      //Print_r($sql);
                        $centre_result = mysqli_query($connection, $sql);
                        $str = "";
                      while ($centre_row = mysqli_fetch_assoc($centre_result)) {
                        // echo $centre_row['category'] . "/" . $centre_row['year'] . "<br>";
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
            </table>
         </div>
      </div>
      </div>
        <div class="uk-overflow-container">
          <!--<div class="uk-grid">
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
                    if (isset($year) && $year != '') {

                      // $sql = "SELECT * from codes where module='SCHOOL_TERM' and `year` = '$year' ORDER BY asc";
                      $sql = "SELECT * from codes where module='SCHOOL_TERM' and `year` = '$year' ORDER BY category ";
                      // Print_r($sql);
                      $centre_result = mysqli_query($connection, $sql);
                      $str = "";
                      while ($centre_row = mysqli_fetch_assoc($centre_result)) {
                        // echo $centre_row['category'] . "/" . $centre_row['year'] . "<br>";
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
          </div>-->
          <table class="table table-bordered mb-4" style="text-align:center;">
            <thead class="myheader">
              <tr>
                <th style="color: #fff; font-size: 20px">Default</th>
                <th style="color: #fff;  font-size: 20px">Adjusted</th>
                <th style="color: #fff;  font-size: 20px">Pending</th>
                <th style="color: #fff;  font-size: 20px">Approved</th>
                <th style="color: #fff; font-size: 20px">Rejected</th> 
              </tr>
            </thead>
            <tbody>
              <tr style="background-color: white">
                <td style="font-size: 24px;  border-right: 1px solid #d3dbe2!important"><?php echo getStudentStatusCount4($centreCode, 'A'); ?></td>
                <td style="font-size: 24px;  border-right: 1px solid #d3dbe2!important"><?php echo getStudentStatusCount5($centreCode, 'D'); ?></td>
                <td style="font-size: 24px;  border-right: 1px solid #d3dbe2!important"><?php echo getStudentStatusCount1($centreCode, 'G'); ?></td>
                <td style="font-size: 24px;  border-right: 1px solid #d3dbe2!important"><?php echo  getStudentStatusCount2($centreCode, 'I'); //getDropoutCount($centreCode) 
                                                                                        ?></td>
                <td style="font-size: 24px;  border-right: 1px solid #d3dbe2!important"><?php echo getStudentStatusCount3($centreCode, 'S'); ?></td>
                <!--<td style="font-size: 24px;  border-right: 1px solid #d3dbe2!important"><?php// echo getStudentStatusCount($centreCode, 'T'); ?></td>
                <?php
                // $active = getStudentStatusCount($centreCode, 'A');
                // $deferred = getStudentStatusCount($centreCode, 'D');
                // $graduated = getStudentStatusCount($centreCode, 'G');
                // $dropout = getStudentStatusCount($centreCode, 'I');
                // $suspended = getStudentStatusCount($centreCode, 'S');
                // $transfered = getStudentStatusCount($centreCode, 'T');
                // $total = $active + $deferred + $graduated + $dropout + $suspended + $transfered;
                ?>
                <td style="font-size: 24px;  border-right: 1px solid #d3dbe2!important"><?php// echo $total; ?></td>-->
              </tr>
            </tbody>
          </table>
          <table class="uk-table">
            <tr class="uk-text-bold uk-text-small">
			  <td>Submitted date</td>
              <td data-uk-tooltip="{pos:top}" title="centre code">Country</td>
              <td>State</td>
              <!--<td data-uk-tooltip="{pos:top}" title="Student Code">Student Code</td>-->
              <td>Centre Name</td>
              <td>Name of Fee Structure</td>
              <td>Level</td>
              <td>Programme Package</td>
              <td>Fees</td>
              <td>Start Date</td>
              <td>End Date</td>
              <td>Status</td>
              <!-----<td>Action</td>------------->
            </tr>
            <?php
				$centre_name=$_GET['centre_name'];
				$fees=$_GET['fees'];
				$status=$_GET['status'];
				$country=$_GET['country'];
				$level=$_GET['level'];
				$pp=$_GET['pp'];
				$name=$_GET['name'];
				//echo $cm;
            //$sql = "SELECT * from fee_structure where 1=1 ";
            $sql = "SELECT f.*, c.company_name, c.country, c.state from `fee_structure` f INNER JOIN `centre` c on f.`centre_code`=c.`centre_code` ";
			
			 if($centre_name!=""){
				$sql=$sql."and c.company_name like '%$centre_name%' ";   
			  }
			  if($fees!=""){
				  $sql=$sql."and f.adjusted like '%$fees%' ";
			  }
			  if($status!=""){
				  //echo $status;
				  $sql=$sql."and f.status like '%$status%' ";
				  
			  }
			  if($country!=""){
				  $sql=$sql."and c.country like '%$country%' ";
			  }
			  if($level!=""){
				  $sql=$sql."and f.subject like '%$level%' ";
			  }
			  if($pp!=""){
				  $sql=$sql."and f.programme_package like '%$pp%' ";
			  }
			  if($name!=""){
				  $sql=$sql."and f.fees_structure like '%$name%' ";
			  }
			  $sql=$sql." ORDER BY `submission_date` DESC ";
	   //echo $sql; 
            $result = mysqli_query($connection, $sql);
						
            $num_row = mysqli_num_rows($result);
			//$m_row = mysqli_fetch_assoc($result) 
			//print_r($num_row); die;
               
                ?>
				
				
                    <?php

                    if ($num_row>0) {
                        while ($browse_row=mysqli_fetch_assoc($result)) {
                            $sha1_id=sha1($browse_row["id"]);
                            ?>
                            <tr class="uk-text-small">
								<td><?php echo $browse_row["submission_date"]?></td>
								<td><?php echo $browse_row["country"]?></td>
								<td><?php echo $browse_row["state"]?></td>
								<td><?php echo $browse_row["company_name"]?></td>
                                <td><?php echo $browse_row["fees_structure"]?></td>
								<td><?php echo $browse_row["subject"]?></td>
								<td><?php echo $browse_row["programme_package"]?></td>
								<td><?php echo $browse_row["adjusted"]?></td>
                                <td><?php echo $browse_row["from_date"]?></td>
								<td><?php echo $browse_row["to_date"]?></td>
								<td><?php echo $browse_row["status"]?></td>  
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
        <?php
        echo $pagination;
        ?>

      </div>
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
  <script>
	function printDiv(print_1) {
        // $('input[type=number]' ).each(function () {
			// var cell = $(this);
			// cell.replaceWith('<span>'  + cell.val() +'</span> ');
		// });
		$('input[type=number]' ).each(function () {
			var edp_tsd ="edp_tsd";
			var cell = $(this);
			cell.replaceWith("<input type=\"number\" class=\"" + edp_tsd + "\" value=\"" + + cell.val() + "\" />");
		});
		 var printContents = document.getElementById(print_1).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }
		window.onafterprint = function() {
        window.location.reload(true);
    };
  </script>