<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");
include_once("../search_new.php");
$centre_code=$_POST["centre_code"];

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //$centre_code, $selected_month
}
$student=$_POST["student"];
$status_c=$_POST["Status"];

if ($method == "print") {
   include_once("../uikit1.php");
}
function getCentreName($centre_code)
{
   global $connection;

   $sql = "SELECT * from centre where centre_code='$centre_code'";
   $result = mysqli_query($connection, $sql);
   $row = mysqli_fetch_assoc($result);
   $num_row = mysqli_num_rows($result);

   if ($num_row > 0) {
      return $row["company_name"];
   } else {
      if ($centre_code == "All" || $centre_code =="All Centre" || $centre_code =="") {
         return "All Centres";
      } else {
         return "";
      }
   }
}
//echo $centre_code;
?>
<div class="uk-width-1-1 myheader" style="">
    <h2 class="uk-text-center myheader-text-color">Student Card Report</h2>
</div>
<div class="uk-grid padd_ni">
    <div class="uk-width-medium-5-10">
        <table class="uk-table">
            <tr>
                <td class="uk-text-bold">Centre Name</td>
                <td><?php echo getCentreName($centre_code)?></td>
            </tr>
            <tr>
                <td class="uk-text-bold">Prepare By</td>
                <td><?php echo $_SESSION["UserName"]?></td>
            </tr>
            <tr id="note1">
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
                <td><?php echo date("Y-m-d H:i:s")?></td>
            </tr>
            <tr id="" style="display: none;">
                <td colspan="2" class="uk-text-bold">NOTE: To be submitted termly with the order form</td>
            </tr>
        </table>
    </div>
</div>
<span class="up_down">
<table class="uk-table" style="margin-left: 2%; margin-right: 2%; margin-top: 20px;     width: 96%;margin: 0 auto;">
    <tr class="uk-text-bold">
        <td>Student Name</td>
        <!--<td>Level</td>-->
        <td>QR Code Number </td>

        <td>Status</td>
        <td>Remarks</td>
    </tr>
    <?php
       if ($centre_code =="All Centre" || $centre_code =="") {
         $centre_code = "All";
      }
      $postRequest = array(
         'centre_code' => $centre_code,
         'session' => 'cb76fe897986563639f6983bfd33b57cd',
         'centre_type' => "Starter"
      );

      //$cURLConnection = curl_init('http://13.58.211.59/q-dees/api/qrcodes');
      $cURLConnection = curl_init('http://13.67.72.102/api/qrcodes');
      curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $postRequest);
      curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

      $apiResponse = curl_exec($cURLConnection);
      curl_close($cURLConnection);

      // $apiResponse - available data from the API request
      //echo $apiResponse;
      $array = json_decode($apiResponse, true);
      //$jsonArrayResponse = json_decode($apiResponse);
      $status = $array['status'];
      $message = $array['message'];
      $TotalCard = $array['Total Card'];
      $datas = $array['data'];
      
      //print_r($array);

      if ($status == 1) {
         $active = 0;
         $lost = 0;
         $others = 0;
		$total = 0;
         foreach ($datas as $data) {
			  global $connection;
            //print_r($data);
            //echo $json['message']; // you can access your key value like this if result is array
            // echo $json->status; // you can access your key value like this if result is object
            //$sql="SELECT * from `student` where unique_id='$data[qr_code]'";
            $sql = "SELECT s.name, e.full_name, sc.status, sc.remarks from `student_card` sc inner join student s on sc.student_id = s.id inner join `student_emergency_contacts` e on e.student_code=s.student_code where sc.unique_id='$data[qr_code]'";
			
             if (isset($student) && $student != '') {
               $sql .= " and s.name like '%$student%'";
            }
			 if (isset($status_c) && $status_c != '') {
                 $sql .= " and sc.status like '%$status_c%'";
               }
            $result = mysqli_query($connection, $sql);
            $row = mysqli_fetch_assoc($result);
			if($row['name'] !=''){
      ?>
    <tr>
        <td><?php echo $row['name'] ?></td>
        <!--<td><?php// echo $row['full_name'] ?></td>-->
        <td><?php echo $data[qr_code] ?></td>
        <!-- <td><?php //echo $data[DeviceID] 
                        ?></td> -->
        <td><?php
					$total++;
                     if ($row["status"] == "0") {
                        echo "Active";
                        $active++;
                     } else if ($row["status"] == "1") {
                        echo "Lost";
                        $lost++;
                     } else if ($row["status"] == "2") {
                        echo "Others";
                        $others++;
                     }
                     ?></td>
        <td><?php echo $row['remarks'] ?></td>
    </tr>


    <?php
			}
         }
      } else {
         //echo $message;
      }

      // foreach($array as $json){
      //    print_r($json);
      //    //echo $json['message']; // you can access your key value like this if result is array
      //    echo $json->status; // you can access your key value like this if result is object
      // }
      // if(curl_error($ch)){
      // 	echo 'Request Error:' . curl_error($ch);
      // }
      // else
      // {
      // 	echo $response;
      // }

      // curl_close($ch);



      ?>
    <!-- <tr>
         <td colspan="1" style="font-weight:bold"> Active: <?php // echo $active; ?></td>
         <td colspan="1" style="font-weight:bold">Lost: <?php // echo $lost; ?></td>
         <td colspan="1" style="font-weight:bold">Others: <?php // echo $others; ?></td>
         <td colspan="2" style="font-weight:bold">Total:<?php // echo $total; ?> </td>

      </tr>-->
</table>
<table class="table table-bordered uk-table act" style="text-align:center;width: 96%;margin: 0 auto;margin-bottom: 20px;">
    <thead class="myheader">
        <tr>
            <th style="color: #fff; font-size: 20px">Active</th>
            <th style="color: #fff;  font-size: 20px">Lost</th>
            <th style="color: #fff;  font-size: 20px">Others</th>
            <th style="color: #fff;  font-size: 20px">Unassigned </th>
            <th style="color: #fff;  font-size: 20px">Total</th>
        </tr>
    </thead>
    <tbody>
        <tr style="background-color: white">
            <td colspan="1"
                style="font-weight:bold;font-size: 24px; border-right: 1px solid #d3dbe2!important; color: grey;">
                <?php echo $active; ?></td>
            <td colspan="1"
                style="font-weight:bold;font-size: 24px; border-right: 1px solid #d3dbe2!important; color: grey;">
                <?php echo $lost; ?></td>
            <td colspan="1"
                style="font-weight:bold;font-size: 24px; border-right: 1px solid #d3dbe2!important; color: grey;">
                <?php echo $others; ?></td>
            <td colspan="1"
                style="font-weight:bold;font-size: 24px; border-right: 1px solid #d3dbe2!important; color: grey;">
                <?php echo $TotalCard-$total; ?> </td>
            <td colspan="1"
                style="font-weight:bold;font-size: 24px; border-right: 1px solid #d3dbe2!important; color: grey;">
                <?php echo $TotalCard; ?> </td>

        </tr>
    </tbody>
</table>
</span>

<script>
$(document).ready(function() {
    var method = '<?php echo $method ?>';
    console.log(method);
    if (method == "print") {
        window.print();
    }
});
</script>
<style>
.myheader {
   background-color: #69a6f7;
    /* background-color: #30D2D6; */
}

.uk-overflow-container {
    width: 100%;
    padding: 0;
    border-radius: 15px;
    overflow: unset;
}

.padd_ni {
    padding-left: 2%;
    padding-right: 2%;
    padding-top: 2%;
}
.up_down{    
    display: flex;
    flex-direction: column-reverse;
    }
.act tr td,
.act tr th{
    border: 1px solid #d3dbe2!important;
    text-align: center;
}    
.act{
    text-align: center;
}
@media print{@page {size: landscape}}    
</style>