<a href="/">
   <span class="btn-qdees" style="display:none;"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>
<span>
   <span class="page_title"><img src="/images/title_Icons/Defective_Status.png">Student Card List</span>
</span>
</p>
<?php
include_once("../mysql.php");
session_start();

if ($_SESSION["isLogin"] == 1) {
   if ((($_SESSION["UserType"] == "S") || ($_SESSION["UserType"] == "H") || ($_SESSION["UserType"] == "C") || ($_SESSION["UserType"] == "R") || ($_SESSION["UserType"] == "CM") || ($_SESSION["UserType"] == "T"))  & (hasRightGroupXOR($_SESSION["UserName"], "StockAdjustmentEdit"))) {
?>
      <br>
      <div class="uk-width-1-1 myheader">
         <h2 class="uk-text-center myheader-text-color">Student Card Listing</h2>
      </div>
      <div class="uk-overflow-container">
         <div id="sctAdjustment"></div>
         <table class="uk-table">
            <form class="uk-form" name="qr_code_list" id="qr_code_list" method="get">
               <!-- <input type="hidden" name="p" id="p" value="qr_code_list_section"> -->

               <div class="uk-grid">
                  <div <?php if (($_SESSION["UserType"] == "S") || ($_SESSION["UserType"] == "H") || ($_SESSION["UserType"] == "C") || ($_SESSION["UserType"] == "R") || ($_SESSION["UserType"] == "CM") || ($_SESSION["UserType"] == "T")) { ?> style="width:100%;" <?php } ?> class="uk-form uk-grid uk-grid-small uk-width-4-10">
                     <?php
                     if (($_SESSION["UserType"] == "S") || ($_SESSION["UserType"] == "H") || ($_SESSION["UserType"] == "C") || ($_SESSION["UserType"] == "R") || ($_SESSION["UserType"] == "CM") || ($_SESSION["UserType"] == "T")) {
                        $sql = "SELECT * from centre order by centre_code";
                        $result = mysqli_query($connection, $sql);

                        $centre_code = $_GET["centre_code"];
                        if($centre_code == "All" || $centre_code =="All Centre" || $centre_code ==""){
                           $centre_code = "All";
                        }
                     ?>
                        <div style="width:35%;" class="uk-width-4-10">
                           Centre Name<br>
                           <input type="hidden" id="hfPage" name="p" value="<?php echo $_GET["p"] ?>">
                           <input type="hidden" id="hfCenterCode" name="centre_code" value="<?php echo $centre_code ?>">
                           <input style="width: 100%;" list="centre_code" id="company_name" name="company_name" value="<?php echo $_GET["company_name"] ?>">
                           

                           <datalist class="form-control" id="centre_code" style="display: none;">

                              <option value="All" <?php echo $centreCode == 'ALL' ? 'selected' : '' ?>>All Centre</option>

                              <?php
                              while ($row = mysqli_fetch_assoc($result)) {
                              ?>
                                 <option value="<?php echo $row['company_name'] ?>" <?php echo $row['company_name'] == $centreCode ? 'selected' : '' ?>><?php echo $row["centre_code"] ?></option>

                              <?php

                              }

                              ?>

                           </datalist>
                        </div>
                        <script>
                           $(document).on('change', '#company_name', function() {
                              var options = $('datalist')[0].options;
                              for (var i = 0; i < options.length; i++) {
                                 // console.log(options[i].text)
                                 if (options[i].value == $(this).val()) {
                                    $("#hfCenterCode").val(options[i].text);
                                    break;
                                 }
                              }
                           });
                        </script>
                     <?php
                     } else {

                        $centrecode = $_SESSION['CentreCode'];

                        $sqlCentre = "SELECT centre_code, company_name from centre where centre_code = '$centrecode'  limit 1 ";
                        $resultCentre = mysqli_query($connection, $sqlCentre);
                        $rowCentre = mysqli_fetch_assoc($resultCentre);
                        // var_dump($rowCentre["kindergarten_name"]);die; 
                     ?>
                        <span class=" centre_n">
                           Centre Name<br>
                           <input style="width: 100%;border: 0;font-weight: bold;" name="centre_name" id="centre_name" value="<?php echo getCentreNameIndex($_SESSION["CentreCode"]) ?>" readonly>
                        </span>
                     <?php
                     }
                     ?>
                         <div class="uk-width-2-10 ">
                     Student Name<br>
                        <input class="uk-width-1-1" placeholder="Student Name" name="s" id="s" value="<?php echo $_GET['s'];?>">
                    </div>
                    <div class="uk-width-2-10 ">
                    Status<br>
                     <select name="status" id="status" class="uk-width-1-1">
                           <option value="">Select</option>
                     
                           <option <?php if($_GET['status'] == '0') { echo "selected"; } ?> value="0">Active</option>
                    
                           <option <?php if($_GET['status'] == '-1') { echo "selected"; } ?> value="-1">Unassigned</option>
                    
                           <option <?php if($_GET['status'] == '1') { echo "selected"; } ?> value="1">Lost</option>
                 
                           <option <?php if($_GET['status'] == '2') { echo "selected"; } ?> value="2">Others</option>
                        </select>
                    </div>
                     <div class="uk-width-2-10" style="white-space: nowrap;">
                        <br>
                        <button class="uk-button">Search</button>
                     </div>
                  </form>

                     <tr class="uk-text-bold">
                        <td>Centre Name</td>
                        <td>Student Name</td>
                        <td>Parent Name</td>
                        <td style="width: 130px;">Student card ID</td>
                        <td>QR code</td>
                        <!-- <td>Device ID </td> -->
                        <td>Status</td>
                        <td>Remarks</td>
                     </tr>
                     <?php
					  
					 
                     $postRequest = array(
                        'centre_code' => $centre_code,
                        'session' => 'cb76fe897986563639f6983bfd33b57cd',
                        'centre_type' => "Starter"
                     );

                    // $cURLConnection = curl_init('http://13.58.211.59/q-dees/api/qrcodes');
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
                     //print_r($array['status']);
                     //print_r($array);
						
                     if ($status == 1) {
                        $active = 0;
                        $lost = 0;
                        $others = 0;
                        $total_assigned = 0;				
						
                        foreach ($datas as $data) {
                           global $connection;
						   
                           //print_r($data);
                           //echo $json['message']; // you can access your key value like this if result is array
                           // echo $json->status; // you can access your key value like this if result is object
                           //$sql="SELECT * from `student` where unique_id='$data[qr_code]'";
                           
                           //$sql="SELECT s.name, e.full_name from `student` s inner join `student_emergency_contacts` e on e.student_code=s.student_code where s.unique_id='$data[qr_code]'";
                      $sql = "SELECT s.name, e.full_name, sc.status, sc.remarks from `student_card` sc inner join student s on sc.student_id = s.id inner join `student_emergency_contacts` e on e.student_code=s.student_code where sc.unique_id='$data[qr_code]' order by e.id asc limit 1";
                      //echo $sql; die;
							 // if($_GET['s'] !=''){
								// $student_name = $_GET['s'];
								// $sql.=" and s.name like '%$student_name%'"; 
							 // }
							 // if($_GET['status'] !=''){
								// $status = $_GET['status'];
								// $sql.=" and sc.status like '%$status%'";
							// }
						 //echo $sql; die;
                           $result = mysqli_query($connection, $sql);
                           $row = mysqli_fetch_assoc($result);

                           if ($row["status"] == "0") {
                              $active++;
                              $total_assigned++;
                           } else if ($row["status"] == "1") {
                              $lost++;
                              $total_assigned++;
                           } else if ($row["status"] == "2") {
                              $others++;
                              $total_assigned++;
                           }

                          $status = $_GET['status'];
                          $student_name = $_GET['s'];
                          $studentOk = true;
                          $statusOk = true;
                          if($student_name != ""){
                             if(strpos(strtolower($row['name']), strtolower($student_name)) !== false){
                              $studentOk = true;
                             }else{
                              $studentOk = false;
                             }
                           
                           }
                           if(isset($_GET['status'])){
                              if($status== ''){
                                 $statusOk = true;
                              }else{
                                 if($status== '-1'){
                                    $status = '';
                                 }

                                 if($row["status"] == $status){
                                    $statusOk = true;
                                 }else{
                                       $statusOk = false;
                                 }
                              }
                            }
                           if($studentOk && $statusOk){
                           
                     ?>
                           <tr>
                              <td><?php echo $data[centre_name] ?></td>
                              <td><?php echo $row['name'] ?></td>
                              <td><?php echo $row['full_name'] ?></td>
                              <td><?php echo $data[qr_code] ?></td>
                              <td>
                                 <div class="genQR" data-toggle="modal" data-target=".modal">
                                    <img src="<?php echo $data[qr_image] ?>" class="imgQR" style="width: 50px; height: 50px;">
                                    <!-- <p>Student Registration QR Code</p> -->
                                 </div>
                              </td>
                              <!-- <td><?php //echo $data[DeviceID] 
                                       ?></td> -->
                              <td><?php
                              
                                    if ($row["status"] == "0") {
                                       echo "Active";
                                       //$active++;
                                       //$total_assigned++;
                                    } else if ($row["status"] == "1") {
                                       echo "Lost";
                                      // $lost++;
                                       //$total_assigned++;
                                    } else if ($row["status"] == "2") {
                                       echo "Others";
                                       //$others++;
                                       //$total_assigned++;
                                    }
                                   // echo $total_assigned;
                                
                                    ?></td>
                              <td><?php echo $row['remarks'] ?></td>
                           </tr>

                                    
                     <?php
                           }
                        }
                     } else {
                       // echo $message;
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
                     <!--<tr>
                        <td colspan="1" style="font-weight:bold"> Active: <?php// echo $active; ?></td>
                        <td colspan="2" style="font-weight:bold">Lost: <?php// echo $lost; ?></td>
                        <td colspan="2" style="font-weight:bold">Others: <?php// echo $others; ?></td>
                        <td colspan="2" style="font-weight:bold">Total:<?php// echo $TotalCard; ?> </td>

                     </tr>-->
         </table>
		  <table class="table table-bordered mb-4" style="text-align:center;">
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
					<td colspan="1" style="font-weight:bold;font-size: 24px; border-right: 1px solid #d3dbe2!important; color: grey;"><?php echo $active; ?></td>
					<td colspan="1" style="font-weight:bold;font-size: 24px; border-right: 1px solid #d3dbe2!important; color: grey;"><?php echo $lost; ?></td>
               <td colspan="1" style="font-weight:bold;font-size: 24px; border-right: 1px solid #d3dbe2!important; color: grey;"><?php echo $others; ?></td>
               <td colspan="1" style="font-weight:bold;font-size: 24px; border-right: 1px solid #d3dbe2!important; color: grey;"><?php echo  $TotalCard - $total_assigned; ?> </td>
					<td colspan="1" style="font-weight:bold;font-size: 24px; border-right: 1px solid #d3dbe2!important; color: grey;"><?php echo $TotalCard; ?> </td>
			  </tr>
			</tbody>
		</table>
         <div class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
               <div class="modal-content">
                  <div class="modal-header myheader">
                     <h5 class="uk-text-center myheader-text-color myheader-text-style">QR Code</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:bold;">
                        <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
                  <div class="modal-body">

                     <a href="#">
                        <img class="img" id="imgModalQR" src="admin/qrcode.php" alt=""><br /><br>
                        <button class="uk-button uk-button-primary form_btn"> Download </button>
                     </a>
                  </div>
               </div>
            </div>
         </div>
      </div>
      </div>
      <script>
         $(document).ready(function() {
            $(".imgQR").click(function() {
               $("#imgModalQR").attr("src", $(this).attr("src"))
            })
         });
      </script>
<?php
   }
}
?>
<style>
   .modal-body {
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
   }
</style>