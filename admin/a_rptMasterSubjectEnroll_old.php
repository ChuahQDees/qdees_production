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



function convertDateFormat($date)
{

  $request_Month = substr($date, 4, 2);

  $request_Year = substr($date, 0, -2);

  return $request_Year . '-' . $request_Month . '-' . cal_days_in_month(CAL_GREGORIAN, $request_Month, $request_Year);
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



if ($_SESSION["isLogin"] == 1) {

  if (($_SESSION["UserType"] == "S") || ($_SESSION["UserType"]=="H") || ($_SESSION["UserType"]=="C") || ($_SESSION["UserType"]=="R") || ($_SESSION["UserType"]=="CM") || ($_SESSION["UserType"]=="T")) {

    $sql = "SELECT count(*) as no_of_student, c.course_name, c.subject, s.centre_code, centre.company_name, centre.operator_name,  centre.state,  centre_status.name from allocation a, `group` g, course c, student s LEFT JOIN centre ON s.centre_code = centre.centre_code LEFT JOIN centre_status ON centre.centre_status_id = centre_status.id where a.course_id=c.id and a.student_id=s.id and a.group_id=g.id and a.deleted=0";


    if (isset($centre_code) && $centre_code != 'ALL' && $centre_code != '') {

      $sql .= " and s.centre_code = '$centre_code'";
    }



    if (isset($centre_status) && $centre_status != 'ALL') {

      $centreStatus = str_replace('%20', ' ', $centre_status);

      $sql .= " and centre_status.name = '$centreStatus'";
    }



    if (isset($selected_month) && $selected_month != '') {
      $str_length = strlen($selected_month);
      if (str_split($selected_month, ($str_length - 2))[1] != 13) {

        $month = substr($selected_month, ($str_length - 2), 2);

        $year = substr($selected_month, 0, -2);

        $convertedDate = convertDateFormat($selected_month);

        $sql .= " and '$convertedDate' between start_date and end_date";
      } else {

        $year = substr($selected_month, 0, -2);

        $sql .= " and year(g.start_date) = '$year'";
      }
    } else {

      $year = $_SESSION['Year'];

      $sql .= " and year(g.start_date) = '$year'";
    }



    if (isset($course_subject) && $course_subject != 'ALL') {

      $courseSubject = str_replace('%20', ' ', $course_subject);

      $sql .= " and c.subject = '$courseSubject'";
    }



    $sql .= " group by a.course_id, a.class_id";

//echo $sql;
    $result = mysqli_query($connection, $sql);

    $num_row = mysqli_num_rows($result);

    $kindergarten_array = [];
    $name_array = [];
    $state_array = [];
    $operator_array = [];
	
    $output_array = [];

    $module_array = [];

    while ($row = mysqli_fetch_assoc($result)) {
		$operator_array[] =  $row['operator_name'];
		$state_array[] =  $row['state'];
		$name_array[] =  $row['name'];
		$kindergarten_array[] =  $row['company_name'];
		 
      if (preg_match('/^QF1/', $row['subject'], $output) == 1) {
		
        $module_array[] = explode('M', (explode(' - ', $row['subject'])[0]))[1];
		

        $output_array[$row['company_name']]['QF1'][explode('M', (explode(' - ', $row['subject'])[0]))[1]] += $row['no_of_student'];
      } else if (preg_match('/^QF2/', $row['subject'], $output) == 1) {
		
        $module_array[] = explode('M', (explode(' - ', $row['subject'])[0]))[1];

        $output_array[$row['company_name']]['QF2'][explode('M', (explode(' - ', $row['subject'])[0]))[1]] += $row['no_of_student'];
      } else if (preg_match('/^QF3/', $row['subject'], $output) == 1) {
		
        $module_array[] = explode('M', (explode(' - ', $row['subject'])[0]))[2];

        $output_array[$row['company_name']]['QF3'][explode('M', (explode(' - ', $row['subject'])[0]))[2]] += $row['no_of_student'];
      }else if (preg_match('/^EDP/', $row['subject'], $output) == 1) {
		
        $module_array[] = explode('M', (explode(' - ', $row['subject'])[0]))[2];

        $output_array[$row['company_name']]['EDP'][explode('M', (explode(' - ', $row['subject'])[0]))[2]] += $row['no_of_student'];
      }
	  
    }
	//print_r($output_array);

    $module_array = array_unique($module_array);

    sort($module_array);

?>



    <div class="uk-margin-right">

      <div class="uk-width-1-1 myheader mt-5">

        <h2 class="uk-text-center myheader-text-color myheader-text-style">Master Subject Enrollment Report</h2>

      </div>

      <div class="uk-overflow-container">


      
      
      
      <div class="uk-grid">
          <div class="uk-width-medium-5-10">
            <table class="uk-table">
              <tr>
                <td class="uk-text-bold">Centre Name</td>
                <td>
                  <?php echo getCentreName($centre_code); ?>
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
                  <td><?php echo $_SESSION['Year']; ?></td>
                <?php endif; ?>
              </tr>
              <tr>
                <td class="uk-text-bold">School Term</td>
                <td>
                  <?php
                     $month = date("m");
                     $year = $_SESSION['Year'];
                     if (isset($selected_month) && $selected_month != '') {
                        $str_length = strlen($selected_month);
                        $month = substr($selected_month, ($str_length - 2), 2);
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
              <tr id="note1" style="display: none;">
                <td colspan="2" class="uk-text-bold">NOTE: To be submitted termly with the order form</td>
              </tr>
            </table>
          </div>
        </div>




        <table class="uk-table">

          <tr class="uk-text-bold uk-text-small">
            <td>No</td>
            <td style="width: 250px;">Centre</td>
            <td>OC</td>
            <!--<td>Operation<br>Consultant</td>-->
            <td class="location">Location</td>

            <?php

            $totalCol = 0;

            foreach ($module_array as $key => $module) {

              $totalCol++;

            ?>

              <td class="location">M<?php echo $module ?></td>

            <?php

            }

            ?>

          </tr>

          <?php
          $grand_total_module = [];
			$y=0;
          foreach ($output_array as $key1 => $outputs) {
$y++;
          ?>
            <tr>

              <td></td>
              <td colspan="<?php echo $totalCol ?>" style="font-weight:bold;">
				<?php			  
				  for ($x = 0; $x < count($kindergarten_array); $x++) {
					if($kindergarten_array[$x] == $key1){
						 echo  $name_array[$x];
						 break;
					}
				}
				?>

              </td>
              <td>



              </td>
              <td class="location"></td>
              <td class="location"></td>

            </tr>
            <tr>
              <td><?php echo $y;?></td>
              <td  style="font-weight:bold;">

                <?php echo $key1 ?>

              </td>
              <td>  <?php
			  
			  for ($x = 0; $x < count($kindergarten_array); $x++) {
				if($kindergarten_array[$x] == $key1){
					 echo  $operator_array[$x];
					 break;
				}
			}
			  ?> </td>
              <td class="location" colspan="<?php echo $totalCol ?>">
                 <?php
			  
			  for ($x = 0; $x < count($kindergarten_array); $x++) {
				if($kindergarten_array[$x] == $key1){
					 echo  $state_array[$x];
					 break;
				}
			}
?>
 
              </td>
              <td class="location"></td>
            </tr>

            <?php

            $total_module = [];

            foreach ($outputs as $key => $output) {

            ?>

              <tr>
                <td></td>
                <td><?php echo $key ?></td>
                <td></td>
                <td class="location"></td>

                <?php

                for ($i = 0; $i < count($module_array); $i++) {

                  $total_module[$module_array[$i]] += $output[$module_array[$i]];

                  if (isset($output[$module_array[$i]])) {

                ?>

                    <td class="location"><?php echo $output[$module_array[$i]] ?></td>

                  <?php

                  } else {

                  ?>

                    <td class="location">0</td>

                <?php

                  }
                }

                ?>

              </tr>

            <?php

            }

            ?>

            <tr>
              
              <td colspan="3">

                <?php echo 'Total for ' . $key1 ?>

              </td>
              <td class="location"> </td>
              
              <?php

              for ($i = 0; $i < count($total_module); $i++) {

                $grand_total_module[$module_array[$i]] += $total_module[$module_array[$i]];

                if (isset($total_module[$module_array[$i]])) {

              ?>

                  <td class="location"><?php echo $total_module[$module_array[$i]] ?></td>

                <?php

                } else {

                ?>

                  <td class="location">0</td>

              <?php

                }
              }

              ?>

            </tr>

          <?php

          }

          ?>

          <tr>
            
            <td colspan="3">

              <?php echo '<b>Grand Total</b>' ?>

            </td>
            <td> </td>
            <?php

            for ($i = 0; $i < count($grand_total_module); $i++) {

              if (isset($grand_total_module[$module_array[$i]])) {

            ?>

                <td class="location"><b><?php echo $grand_total_module[$module_array[$i]] ?></b></td>

              <?php

              } else {

              ?>

                <td class="location"><b>0</b></td>

            <?php

              }
            }

            ?>

          </tr>

        </table>

      </div>

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
<style>
td.location {
    display: none;
}
</style>
<script>
  $(document).ready(function() {

    var method = '<?php echo $method ?>';

    if (method == "print") {

      window.print();

    }

  });
</script>