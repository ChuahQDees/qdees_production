<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");
$student_code=$_POST["student_code"];
foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);
}
$ssid=(getssid($student_code));

function getFeesSettings($subject, $field) {
   global $connection;

   $sql="SELECT * from fee where subject='BIEP'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row[$field];
}

function getssid($student_code) {
   global $connection;

   $sql="SELECT id from student where sha1(student_code)='$student_code'";

   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["id"];
}
function getStrTerm($term) {
	switch ($term) {
      case 1 : return "Term 1"; break;
      case 2 : return "Term 2"; break;
      case 3 : return "Term 3"; break;
      case 4 : return "Term 4"; break;
      case 5 : return "Term 5"; break;
   	}
}

function getCurrentHalfYearly($month) {
	if($month<=6){
		return 1;
	}
	else if($month<=12){
		return 2;
	}
}
function getStrHalfYearly($HalfYearly) {
	 switch ($HalfYearly) {
      case 1 : return "First Half"; break;
      case 2 : return "Second Half"; break;  
   }
}
?>

<script>
function removeFromTempBusket(bid, product_code, student_code) {
   //UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function () {
      $.ajax({
         url : "admin/removeFromTempBusket.php",
         type : "POST",
         data : "product_code="+product_code+"&student_code="+student_code+"&bid="+bid,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            var s=response.split("|");

            if (s[0]=="1") {
               getInitialBusket();
            }

            if (s[0]=="0") {
               UIkit.notify(s[1]);
            }
         },
         error : function(http, status, error) {
            UIkit.notify("Error:"+error);
         }
      });
   //}) 
}

function getInitialBusket() {
   var ssid='<?php echo $ssid?>';

   $.ajax({
      url : "admin/getInitialBusketContent.php",
      type : "POST",
      data : "ssid="+ssid,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         $("#lstBusketInit").html(response);
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}

function doPutInBusket() {
   UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function () {
      $.ajax({
         url : "admin/doPutInBusket.php",
         type : "POST",
         data : "",
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            var s=response.split("|");
            if (s[0]=="1") {
               window.location.reload();
            }

            if (s[0]=="0") {
               UIkit.notify(s[1]);
            }
         },
         error : function(http, status, error) {
            UIkit.notify("Error:"+error);
         }
      });
   });
}

function add2TempBusket(course_name, student_code, course_inquiry, initFees, initQty, allocation_id, collection_month, collection_year, collection_pattern, single_year = '') {
   if (course_name!="") {
	   if(course_inquiry=="Deposit"){
		   initFees=$("#"+course_name+course_inquiry).val();
		   if(initFees<=0 || initFees==""){
			   UIkit.notify("Please add amount");
			   return false;
		   }
	   }

      $.ajax({
         url : "admin/add2TempBusket.php",
         type : "POST",
         data : "student_code="+student_code+"&course_name="+course_name+"&course_inquiry="+course_inquiry+"&course_val="+initFees+"&inquiry_qty="+initQty+"&collection_month="+collection_month+"&collection_year="+collection_year+"&collection_pattern="+collection_pattern+"&allocation_id="+allocation_id+"&single_year="+single_year,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            var s=response.split("|");
            if (s[0]=="1") {
               UIkit.notify(s[1]);
            }

            if (s[0]=="0") {
               UIkit.notify(s[1]);
            }
            getInitialBusket();
         },
         error : function(http, status, error) {
            UIkit.notify("Error:"+error);
         }
      });
   } else {
      UIkit.notify("Please provide a product code and student code");
   }
}

$(document).ready(function () {
   getInitialBusket();   
});

function changeType(subject) {
   var course_inquiry=$("#init-"+subject+"-type").val();
   $("#"+course_inquiry+"-"+subject+"-fees").show();
   $("#"+course_inquiry+"-"+subject+"-fees").siblings().hide();
}
</script>

<div class="uk-grid">
   <div class="uk-width-2-3 uk-form">
   <table class="uk-table uk-table-small uk-form uk-form-small">
   
   <tr style="background-color:lightgrey">
         <td colspan="2">Year Selection</td>
      </tr>
	<tr>
		<td style="padding-top: 6px;">
		<select name="year" id="year1">
			<option value="">Select Year</option>
			<?php echo getYearOptionList(); ?>
		</select>
		</td>
	</tr>
	 <tr style="background-color:lightgrey">
         <td colspan="2">Outstanding From Previous Years and Current Year</td>
      </tr>
	<tr>
        <td style="padding-top: 6px;">Outstanding Amount: <input type="number" placeholder="Outstanding Amount" step="0.01" id="total_amount_s1" value="" readonly></td>
	  </tr>
		
	 	<script>
	$(document).ready(function(){
		 var d = new Date();
		 var currentYear = '<?php echo $_SESSION['Year']; ?>';
		
		$("#year1").change(function(){

			if($(this).val()!=""){
				$(".fees").hide();
				$("."+$(this).val()).show();
				var selected_year = $(this).val();
				var t_amount3 = 0;

				$("#year1 option").each(function()
				{
					var i = $(this).val();
					if(i != '') {
						var fee_amounts2 = $("."+i).find(".fee_amounts2");			
						var t_amount1 = 0;
						fee_amounts2.each(function(){
							var fee_amount1 = $(this).val();
							if(fee_amount1!=""){
								t_amount1 += parseFloat(fee_amount1);
							}
						})
						t_amount3 += t_amount1;
						if(i == selected_year) {
							return false;
						}
					}
				});

				$("#total_amount_s1").val(t_amount3);
			}else{
				$(".fees").hide();
				$("#total_amount_s1").val(0);
			}
		})
		setTimeout(function(){ 
			$("#year1").val(currentYear);
			$("#year1").change();
			}, 100);
		
		$("#all_outstanding_fee1").change(function(){
			if($(this).val()!=""){
			$(".feeItems").hide();
			$("."+$(this).val()).show();
			}else{
				$(".feeItems").show();
			}
		})
		var total_amount=$("#total_amount1").val();
		var item_name=$("#item_name1").val();
		$('#total_amount_s1').val(total_amount);	
		$('#item_names1').val(item_name);
	})
	</script>
	<tr style="background-color:lightgrey">
         <td colspan="2">Current Outstanding</td>
      </tr>
	<tr>
		<td style="padding-top: 6px;padding-bottom: 11px;">
		<select name="all_outstanding_fee" id="all_outstanding_fee1">
			<option value="">Select Fee</option>
			<option value="f1">Mobile app</option>
			<option value="f2">Registration</option>
			<option value="f3">Deposit</option>
			<option value="f4">Insurance</option>
			<option value="f5">Q-dees Level Kit</option>
		</select>
		</td>
	</tr>
 </table>
<?php
	$month = date('m');
	 
	$centre_code=$_SESSION["CentreCode"];
	$year=$_SESSION['Year'];
	$todayDate = date("Y-m-d"); 

// start Monthly
	$sql="SELECT ps.id, ps.student_id, s.student_code, f.mobile_adjust, f.subject, f.mobile_collection, f.registration_adjust, f.insurance_adjust, f.q_dees_adjust, f.extend_year, f.basic_adjust, fl.fee_id, fl.programme_date, fl.programme_date_end from student s, programme_selection ps, student_fee_list fl, fee_structure f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.id='$ssid'";
	
		$result=mysqli_query($connection, $sql);
		$num_row=mysqli_num_rows($result);
		$amount = 0;
		while($row=mysqli_fetch_assoc($result)) { // start main while
			$programme_date = $row["programme_date"];
			$programme_date = date("m",strtotime($programme_date));

			$programme_date_end = $row["programme_date_end"];
			$month = date("m",strtotime($programme_date_end));
		?>
		<div class="<?php echo $row["extend_year"] ?> fees">
		<?php if($row["mobile_collection"]=="Monthly"){ ?>
			<h2><?php echo $row["subject"]?></h2>
			<?php } ?>
			
			<table>
				<tr>
					<td>
						<!---------------start monthly---------------------------------------------------------------------------------->
						<?php 
							$month_array = getMonthList($row["programme_date"], $row["programme_date_end"]);
							foreach ($month_array as $dt) {

								$i = $dt->format("m");

								if($row["mobile_collection"]=="Monthly"){
								?>
									<h3 style="margin-bottom: 0px;margin-top:0px;"><?php echo $dt->format("M Y"); ?></h3>
									<div class="f1 feeItems">
										<?php } ?>
										<?php if($row["mobile_adjust"]!="" && $row["mobile_collection"]!=""){ 
											if($row["mobile_collection"]=="Monthly"){
											?>

											<?php 
											$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '$i' and c.product_code = 'Mobile app' group by c.id ) ab group by collection_month, fee_structure_id";

													$result1=mysqli_query($connection, $sql);
													$IsRow=mysqli_num_rows($result1);
													$row_collection=mysqli_fetch_assoc($result1)
												
												?>
												Mobile app
												<br> 
												<input class="<?php if($IsRow<1){ echo "fee_amounts2";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["mobile_adjust"]?>" id="" readonly>
												<input type="text" placeholder="Fee"  name="mobile_collection" value="<?php echo $row["mobile_collection"]?>" readonly>
													</label>
													<?php
														
												if($IsRow<1){
													$amount += $row["mobile_adjust"];
													?>
													<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Mobile app'?>', '<?php echo $student_code ?>', 'mobile', '<?php echo $row["mobile_adjust"]?>', '1', '<?php echo $row["id"]?>', '<?php echo $i; ?>', '<?php echo $row["extend_year"];?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
													<?php
												}else{
													if($row["mobile_adjust"] > $row_collection["collection"]){
														$balance = $row["mobile_adjust"] - $row_collection["collection"];
														?>
														<input class="fee_amounts2" type="hidden" value="<?php echo $balance?>">
														<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Mobile app'?>', '<?php echo $student_code ?>', 'mobile', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '<?php echo $i; ?>', '<?php echo $row["extend_year"];?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
													}else {
														echo "Paid";
													}
												}	
											}
										}
										?>
											
									</div>
							<?php 
							}
								?>

						<!---------------end monthly---------------------------------------------------------------------------------->
						
					</td>
				</tr>
			
			
			</table>
		</div>
		<?php
	} 
// end Monthly

// start termly
	$sql="SELECT ps.id, ps.student_id, s.student_code, f.mobile_adjust, f.subject, f.mobile_collection, f.registration_adjust, f.insurance_adjust, f.q_dees_adjust, f.extend_year, f.basic_adjust, fl.fee_id, fl.programme_date, fl.programme_date_end from student s, programme_selection ps, student_fee_list fl, fee_structure f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.id='$ssid' ";
			
		$result=mysqli_query($connection, $sql);
	$num_row=mysqli_num_rows($result);
	$amount = 0;
	$i=0;
	$extend_year = 0;
	while ($row=mysqli_fetch_assoc($result)) { // start main while
			$programme_date = $row["programme_date"];
		$programme_date = date("m",strtotime($programme_date));

		$programme_date_end = $row["programme_date_end"];
		$month = date("m",strtotime($programme_date_end));
	?>
	<div class="<?php echo $row["extend_year"] ?> fees">
	<?php if($row["mobile_collection"]=="Termly"){ 
				if($i==0){
		?>
			<h2><?php echo $row["subject"]?></h2>
			<?php }
				} ?>
		
		<table>
			<tr>
				<td>
					<!---------------start Termly---------------------------------------------------------------------------------->

				<?php 
					$term = getTermFromDate(date('Y-m-d',strtotime($programme_date_end)));
					$term1 = (int)$term;

					if($extend_year != $row["extend_year"]){
						$extend_year = $row["extend_year"];
						$i=0;
					}
					for ($i; $i < $term1; $i++) { 
							if($row["mobile_collection"]=="Termly"){
							?>
							<div class="f1 feeItems">
					<label>
						<h3 style="margin-bottom: 0px;"><?php echo getStrTerm($i+1); ?></h3>
							<?php 
							}
					if($row["mobile_adjust"]!="" && $row["mobile_collection"]!=""){ 
						if($row["mobile_collection"]=="Termly"){
						
							$month = $i+1;
						
							echo "Mobile app";
							$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '$month' and c.product_code = 'Mobile app' group by c.id ) ab group by collection_month, fee_structure_id";
						
								$result1=mysqli_query($connection, $sql);
								$IsRow=mysqli_num_rows($result1);
								$row_collection=mysqli_fetch_assoc($result1)
							?>
							<br> 
							<input class="<?php if($IsRow<1){ echo "fee_amounts2";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["mobile_adjust"]?>" id="" readonly>
								<input type="text" placeholder="Fee"  name="mobile_collection" value="<?php echo $row["mobile_collection"]?>" readonly>
									</label>
									<?php
									
								if($IsRow<1){
									$amount += $row["mobile_adjust"];
									$item = $row["subject"];
									?>
								<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Mobile app'?>', '<?php echo $student_code ?>', 'mobile', '<?php echo $row["mobile_adjust"]?>', '1', '<?php echo $row["id"]; ?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Termly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
								<?php
								//}
							}else{
								if($row["mobile_adjust"] > $row_collection["collection"]){
									$balance = $row["mobile_adjust"] - $row_collection["collection"];
									?>
									<input class="fee_amounts2" type="hidden" value="<?php echo $balance?>">
									<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Mobile app'?>', '<?php echo $student_code ?>', 'mobile', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Termly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
								}else {
									echo "Paid";
								}
							}
						}
						}
					}
						?>	
					</div>
					

					<!---------end termly-------------------------->
					
				</td>
			</tr>
		
		
		</table>
	</div>
	<?php
	} 
// end Termly

// start Half yearly
	$sql="SELECT ps.id, ps.student_id, s.student_code, f.mobile_adjust, f.subject, f.mobile_collection, f.registration_adjust, f.insurance_adjust, f.q_dees_adjust, f.extend_year, f.basic_adjust, fl.fee_id, fl.programme_date, fl.programme_date_end from student s, programme_selection ps, student_fee_list fl, fee_structure f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.id='$ssid'";
				
		$result=mysqli_query($connection, $sql);
	$num_row=mysqli_num_rows($result);
	$amount = 0;
	$i=0;
	$extend_year = 0;
	while ($row=mysqli_fetch_assoc($result)) { // start main while
			$programme_date = $row["programme_date"];
		$programme_date = date("m",strtotime($programme_date));

		$programme_date_end = $row["programme_date_end"];
		$month = date("m",strtotime($programme_date_end));
	?>
	<div class="<?php echo $row["extend_year"] ?> fees">
	<?php if($row["mobile_collection"]=="Half Year"){ ?>
			<h2><?php echo $row["subject"]?></h2>
			<?php	} ?>
		
		<table>
			<tr>
				<td>
					
					<!---------------start Half Yearly-------------------------------------->
					
					<?php 
					$HearfYearly = getCurrentHalfYearly($month);
					if($extend_year != $row["extend_year"]){
						$extend_year = $row["extend_year"];
						$i=0;
					}
					for ($i; $i < $HearfYearly; $i++) { 
						if($row["mobile_collection"]=="Half Year"){
							?>
							<div class="f1 feeItems">
					<label>
						<h3 style="margin-bottom: 0px;"><?php echo getStrHalfYearly($i+1); ?></h3>
							<?php 
							}
							if($row["mobile_adjust"]!="" && $row["mobile_collection"]!=""){ 
							if($row["mobile_collection"]=="Half Year"){
								
							$date = $HearfYearly;
							echo "<br>";
							echo 'Mobile app';
							$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '$i' and c.product_code = 'Mobile app' group by c.id ) ab group by collection_month, fee_structure_id";
							
								$result1=mysqli_query($connection, $sql);
								$IsRow=mysqli_num_rows($result1);
								$row_collection=mysqli_fetch_assoc($result1)
							?>
							<br> 
								<input class="<?php if($IsRow<1){ echo "fee_amounts2";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["mobile_adjust"]?>" id="" readonly>
								<input type="text" placeholder="Fee"  name="mobile_collection" value="<?php echo $row["mobile_collection"]?>" readonly>
									</label>
									<?php
									
								if($IsRow<1){
									$amount += $row["mobile_adjust"];
									$item = $row["subject"];
									?>
									<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Mobile app'?>', '<?php echo $student_code ?>', 'mobile', '<?php echo $row["mobile_adjust"]?>', '1', '<?php echo $row["id"]; ?>', '<?php echo $i; ?>', '<?php echo $row["extend_year"];?>', 'Half Year')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
												
								<?php
								
							}else{
								if($row["mobile_adjust"] > $row_collection["collection"]){
									$balance = $row["mobile_adjust"] - $row_collection["collection"];
									?>
									<input class="fee_amounts2" type="hidden" value="<?php echo $balance?>">
									<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Mobile app'?>', '<?php echo $student_code ?>', 'mobile', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '<?php echo $i; ?>', '<?php echo $row["extend_year"];?>', 'Half Year')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
								}else {
									echo "Paid";
								}
								}
							}
						}
					}
						?>	
					</div>
					<!---------end Half yearly-------------------------->
					
				</td>
			</tr>
		
		
		</table>
	</div>
	<?php
	} 
// end Half yearly

// start yearly
	$sql="SELECT ps.id, ps.student_id, s.student_code, s.start_date_at_centre, f.mobile_adjust, f.subject, f.mobile_collection, f.registration_adjust, f.insurance_adjust, f.q_dees_adjust, fl.uniform_adjust, fl.gymwear_adjust, f.q_bag_adjust, f.extend_year, f.basic_adjust, fl.fee_id, fl.programme_date, fl.programme_date_end from student s, programme_selection ps, student_fee_list fl, fee_structure f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.id='$ssid' group by ps.id";
				
	$result=mysqli_query($connection, $sql);
	$num_row=mysqli_num_rows($result);
	$amount = 0;
	while ($row=mysqli_fetch_assoc($result)) { // start main while
		$programme_date = $row["programme_date"];
		$programme_date = date("m",strtotime($programme_date));

		$programme_date_end = $row["programme_date_end"];
		$month = date("m",strtotime($programme_date_end));
	?>
		<div class="<?php echo $row["extend_year"] ?> fees">
		<?php if($row["mobile_collection"]=="Annually"){ ?>
			<h2><?php echo $row["subject"]?></h2>
		<?php	} ?>
		
		<table>
			<tr>
				<td>
					
					<!---------------start Annually-------------------------------------->

					<?php 
							if($row["mobile_collection"]=="Annually"){
							?>
							<div class="f1 feeItems">
					<label>
						<h3 style="margin-bottom: 0px;">Annually</h3>
							<?php 
							}
							if($row["mobile_adjust"]!="" && $row["mobile_collection"]!=""){ 
								if($row["mobile_collection"]=="Annually"){
									echo "<br>";
									echo 'Mobile app';
									echo "<br>";
									$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.product_code = 'Mobile app' group by c.id ) ab group by collection_month, fee_structure_id";
								
									$result1=mysqli_query($connection, $sql);
									$IsRow=mysqli_num_rows($result1);
									$row_collection=mysqli_fetch_assoc($result1)
								?>
								<br> 
									<input class="<?php if($IsRow<1){ echo "fee_amounts2";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["mobile_adjust"]?>" id="" readonly>
									<input type="text" placeholder="Fee"  name="mobile_collection" value="<?php echo $row["mobile_collection"]?>" readonly>
										</label>
									<?php 
									if($IsRow<1){
										$amount += $row["mobile_adjust"];
										$item = $row["subject"];
									?>
										<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Mobile app'?>', '<?php echo $student_code ?>', 'mobile', '<?php echo $row["mobile_adjust"]?>', '1', '<?php echo $row["id"]; ?>', '1', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
										
								
									<?php
									}else{
										if($row["mobile_adjust"] > $row_collection["collection"]){
											$balance = $row["mobile_adjust"] - $row_collection["collection"];
											?>
											<input class="fee_amounts2" type="hidden" value="<?php echo $balance?>">
											<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Mobile app'?>', '<?php echo $student_code ?>', 'mobile', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '1', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
										}else {
											echo "Paid";
										}
									}
								}
							}
						?>	
					</div>
					

					<!---------end Annually-------------------------->

					<div class="f2 feeItems">
					<br>
					<label>
						<h3 style="margin-bottom: 0px;">Registration</h3>
								<?php 
								$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.product_code = 'Registration' group by c.id ) ab group by collection_month, fee_structure_id";
								
									$result1=mysqli_query($connection, $sql);
									$IsRow=mysqli_num_rows($result1);
									$row_collection=mysqli_fetch_assoc($result1);

									$checkReg = mysqli_fetch_array(mysqli_query($connection,"SELECT `start_date_at_centre` FROM `student` WHERE `id` = '".$ssid."'"));

									$student_start_year = getYearFromMonth(date('Y',strtotime($checkReg['start_date_at_centre'])),date('m',strtotime($checkReg['start_date_at_centre'])));

									$registration_adjust = ($student_start_year == $row['extend_year']) ? $row["registration_adjust"] : 0;

								?>

								<input class="<?php if($IsRow<1){ echo "fee_amounts2";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $registration_adjust; ?>" readonly>
								</label>
								<?php 
								
									if($IsRow<1){
										$amount += $registration_adjust;
										$item = $row["subject"];

										if($registration_adjust > 0)
										{
							?>
											<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Registration'?>', '<?php echo $student_code ?>', 'registration', '<?php echo $registration_adjust; ?>', '1', '<?php echo $row["id"]?>', '1', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
							<?php
										}
										else
										{
							?>
											<!-- <a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" ><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a> -->
							<?php
										}
									}else{
										if($registration_adjust > $row_collection["collection"]){
											$balance = $registration_adjust - $row_collection["collection"];
							?>
											<input class="fee_amounts2" type="hidden" value="<?php echo $balance?>">

							<?php 
										if($registration_adjust > 0) 
										{
							?>

											<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Registration'?>', '<?php echo $student_code ?>', 'Registration', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '1', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	
											
									<?php
										}
										else
										{

										}
									echo "Collected: " . $row_collection["collection"]; 
								
									}else {
										echo "Paid";
									}
								}
							?>	 
					</div>
					<div class="f3 feeItems">
					<br>
					<label>
					<?php 
					$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_type = 'Deposit' group by c.id ) ab group by fee_structure_id";
				
									$result2=mysqli_query($connection, $sql);
									$IsRow=mysqli_num_rows($result2);
									$row_collection=mysqli_fetch_assoc($result2)
					?>
						<h3 style="margin-bottom: 0px;">Deposit</h3>
								<input  type="number" id="<?php echo $row["subject"]?>Deposit" placeholder="Fee" step="0.01" name="" value="" >
								</label>
								
								<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo $row["subject"] ?>', '<?php echo $student_code ?>', '<?php echo'Deposit'?>', '100', '1', '<?php echo $row["id"]?>', '1', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
							 <?php
							 if($IsRow>0){
								echo "Collected: " . $row_collection["collection"]; 
							 }
								
							 ?>	
					</div>

				<?php

					$year_date = mysqli_fetch_array(mysqli_query($connection,"SELECT MIN(`term_start`) AS `start_date`, MAX(`term_end`) AS `end_date` FROM `schedule_term` WHERE `year` = '".$row['extend_year']."' AND `centre_code` = '".$_SESSION['CentreCode']."' GROUP BY `year`"));

					$month_array = getMonthList($year_date['start_date'], $year_date['end_date']);

					$jan_arr = array();

					foreach ($month_array as $dt) {

						if($dt->format("m") == 01) {
							$jan_arr[] = $dt->format("Y");
						}
					}

					$yr_arr = array();

					if($row['start_date_at_centre'] < $year_date['start_date']) {

						foreach ($jan_arr as $jan) {
							$yr_arr[] = $jan;
						}
					} 
					else if($row['start_date_at_centre'] == $year_date['start_date'])
					{
						if(date('m',strtotime($year_date['start_date'])) != '01')
						{
							$yr_arr[] = date('Y',strtotime($year_date['start_date']));
						}

						foreach ($jan_arr as $jan) {
							$yr_arr[] = $jan;
						}
					}
					else if($row['start_date_at_centre'] > $year_date['start_date'])
					{
						if(count($jan_arr) == 1) {

							if($row['start_date_at_centre'] < $jan_arr[0].'-01-01') 
							{
								$yr_arr[] = date('Y',strtotime($row['start_date_at_centre']));
							}

							$yr_arr[] = $jan_arr[0];

						} else if(count($jan_arr) == 2) {

							if($row['start_date_at_centre'] > $jan_arr[0].'-01-01' && $row['start_date_at_centre'] < $jan_arr[1].'-01-01') 
							{
								$yr_arr[] = $jan_arr[0];
								$yr_arr[] = $jan_arr[1];
							}
							else if($row['start_date_at_centre'] > $jan_arr[1].'-01-01') 
							{
								$yr_arr[] = $jan_arr[1];
							}
						}
					}

					foreach ($yr_arr as $yr) {
				?>
					<div class="f4 feeItems">
						<br>
						<label>
							<h3 style="margin-bottom: 0px;">Insurance (<?php echo $yr; ?>)</h3>
							<?php 
								$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.product_code = 'Insurance' and c.single_year = '".$yr."' group by c.id ) ab group by collection_month, fee_structure_id";
								$result1=mysqli_query($connection, $sql);
								$IsRow=mysqli_num_rows($result1);
								$row_collection=mysqli_fetch_assoc($result1);
							?>
									<input class="<?php if($IsRow<1){ echo "fee_amounts2";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["insurance_adjust"]?>" readonly>
									</label>
									<?php 
										if($IsRow<1){
										$amount += $row["insurance_adjust"];
										$item = $row["subject"];
									?>		
											<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Insurance'?>', '<?php echo $student_code ?>', 'insurance', '<?php echo $row["insurance_adjust"]?>', '1','<?php echo $row["id"]?>', '1', '<?php echo $row["extend_year"];?>', 'Annually','<?php echo $yr; ?>')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
								<?php
										} else {
											if($row["insurance_adjust"] > $row_collection["collection"]){
											$balance = $row["insurance_adjust"] - $row_collection["collection"];
											?>
												<input class="fee_amounts2" type="hidden" value="<?php echo $balance?>">
												<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Insurance'?>', '<?php echo $student_code ?>', 'Insurance', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '1', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
										} else {
											echo "Paid";
										}
									}
								?>	
						</div>
				<?php
					}
				?>

					<div class="f5 feeItems">
					<br>
					<label>
						<h3 style="margin-bottom: 0px;">Q-dees Level Kit</h3>
						<?php 
							$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.product_code = 'Q-dees Level Kit' group by c.id ) ab group by collection_month, fee_structure_id";
					
							$result1=mysqli_query($connection, $sql);
							$IsRow=mysqli_num_rows($result1);
							$row_collection=mysqli_fetch_assoc($result1)
						?>
							<input class="<?php if($IsRow<1){ echo "fee_amounts2";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["q_dees_adjust"]?>" readonly>
						</label>
						<?php 		
							if($IsRow<1){
								$amount += $row["q_dees_adjust"];
								$item = $row["subject"];
						?>
								<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Q-dees Level Kit'?>', '<?php echo $student_code ?>', 'q-dees-level-kit', '<?php echo $row["q_dees_adjust"]?>', '1','<?php echo $row["id"]?>', '1', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
						<?php
							} else {
								if($row["q_dees_adjust"] > $row_collection["collection"]){
									$balance = $row["q_dees_adjust"] - $row_collection["collection"];
						?>
										<input class="fee_amounts2" type="hidden" value="<?php echo $balance?>">
										<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'q-dees-level-kit'?>', '<?php echo $student_code ?>', 'q-dees-level-kit', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '1', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
									}else {
										echo "Paid";
									}
								}
							?>	
							
					</div>
					<?php 
						$student_data = mysqli_fetch_array(mysqli_query($connection,"SELECT `start_date_at_centre` FROM `student` WHERE `id` = '".$ssid."'"));

						//$start_year = date('Y',strtotime($student_data['start_date_at_centre']));
						$start_year = getYearFromMonth(date('Y',strtotime($student_data['start_date_at_centre'])), date('m',strtotime($student_data['start_date_at_centre'])));

						$flag = 1;
						if($start_year != $row['extend_year']) { $flag = 0; }

					?>

					<?php if($flag == 1) { ?>
						<div class="f5 feeItems">
					<?php } else { $row["uniform_adjust"] = 0; ?>
						<div class="f5 feeItems" style="display:none;">
					<?php } ?>	
						
					<br>
					<label>
						<h3 style="margin-bottom: 0px;">Uniform (2 sets)</h3>
						<?php 
							$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.product_code = 'Uniform (2 sets)' group by c.id ) ab group by collection_month, fee_structure_id";
						
							$result1=mysqli_query($connection, $sql);
							$IsRow=mysqli_num_rows($result1);
							$row_collection=mysqli_fetch_assoc($result1);
						?>
							<input class="<?php if($IsRow<1){ echo "fee_amounts2";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["uniform_adjust"]; ?>" readonly>
						</label>
						<?php 
							if($IsRow<1){
								$amount += $row["uniform_adjust"];
								$item = $row["subject"];
						?>
								<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Uniform (2 sets)'?>', '<?php echo $student_code ?>', 'Uniform (2 sets)', '<?php echo $row["uniform_adjust"]?>', '1','<?php echo $row["id"]?>', '1', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
						<?php
							} else {
								if($row["uniform_adjust"] > $row_collection["collection"]){
									$balance = $row["uniform_adjust"] - $row_collection["collection"];
						?>
									<input class="fee_amounts2" type="hidden" value="<?php echo $balance?>">
									<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Uniform (2 sets)'?>', '<?php echo $student_code ?>', 'Uniform (2 sets)', '<?php echo $balance; ?>', '1', '<?php echo $row["id"] ?>', '1', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
								}else {
									echo "Paid";
								}
							}
						?>	
					</div>
							
					<?php if($flag == 1) { ?>
						<div class="f5 feeItems">
					<?php } else { $row["gymwear_adjust"] = 0; ?>
						<div class="f5 feeItems" style="display:none;">
					<?php } ?>	
					
					<br>
					<label>
						<h3 style="margin-bottom: 0px;">Gymwear (1 set)</h3>
					<?php 
						$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.product_code = 'Gymwear (1 set)' group by c.id ) ab group by collection_month, fee_structure_id";
					
						$result1=mysqli_query($connection, $sql);
						$IsRow=mysqli_num_rows($result1);
						$row_collection=mysqli_fetch_assoc($result1)
					?>
								<input class="<?php if($IsRow<1){ echo "fee_amounts2";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["gymwear_adjust"]; ?>" readonly>
								</label>
								<?php 
								
									if($IsRow<1){
									$amount += $row["gymwear_adjust"];
									$item = $row["subject"];
							?>
								<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Gymwear (1 set)'?>', '<?php echo $student_code ?>', 'Gymwear (1 set)', '<?php echo $row["gymwear_adjust"]?>', '1','<?php echo $row["id"]?>', '1', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
							<?php
								}else{
									if($row["gymwear_adjust"] > $row_collection["collection"]){
										$balance = $row["gymwear_adjust"] - $row_collection["collection"];
										?>
										<input class="fee_amounts2" type="hidden" value="<?php echo $balance?>">
										<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Gymwear (1 set)'?>', '<?php echo $student_code ?>', 'Gymwear (1 set)', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '1', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
									}else {
										echo "Paid";
									}
								}
							?>		
					</div>

					<?php if($flag == 1) { ?>
						<div class="f5 feeItems">
					<?php } else { $row["q_bag_adjust"] = 0; ?>
						<div class="f5 feeItems" style="display:none;">
					<?php } ?>	
					<br>
					<label>
						<h3 style="margin-bottom: 0px;">Q-dees Bag</h3>
					<?php 
						$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.product_code = 'Q-dees Bag' group by c.id ) ab group by collection_month, fee_structure_id";
						
						$result1=mysqli_query($connection, $sql);
						$IsRow=mysqli_num_rows($result1);
						$row_collection=mysqli_fetch_assoc($result1)
					?>
						<input class="<?php if($IsRow<1){ echo "fee_amounts2";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["q_bag_adjust"]; ?>" readonly>
					</label>
					<?php 		
						if($IsRow<1){
							$amount += $row["q_bag_adjust"];
							$item = $row["subject"];
					?>
								<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Q-dees Bag'?>', '<?php echo $student_code ?>', 'Q-dees Bag', '<?php echo $row["q_bag_adjust"]?>', '1','<?php echo $row["id"]?>', '1', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
							<?php
								}else{
									if($row["q_bag_adjust"] > $row_collection["collection"]){
										$balance = $row["q_bag_adjust"] - $row_collection["collection"];
										?>
										<input class="fee_amounts2" type="hidden" value="<?php echo $balance?>">
										<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Q-dees Bag'?>', '<?php echo $student_code ?>', 'Q-dees Bag', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '1', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
									}else {
										echo "Paid";
									}
								}
							?>	
					</div>
				</td>
			</tr>
		</table>
	</div>
	<?php
	} 
// end yearly
	  ?>
	  <table>
	  <tr>
		<td>
		<input type="hidden" placeholder="Outstanding Amount" step="0.01" name="total_amount1" id="total_amount1" value="<?php echo $amount; ?>" readonly>
		</td>
	</tr>
	<tr>
		<td>
		<input type="hidden" placeholder="Outstanding Item" name="item_name" id="item_name1" value="<?php echo $item; ?>" readonly>
		</td>
	</tr> 
	</table>
   </div>
   <div class="uk-width-1-3 uk-form">
      <div class="uk-text-center myheader">
         <h2 class="myheader-text-color">Initial Basket Content</h2>
      </div>
      <div id="lstBusketInit"></div>
      <a onclick="doPutInBusket()" class="uk-button uk-button-small uk-button-primary uk-align-right">Put in Basket</a>
      <a class="uk-button uk-button-small uk-align-right" onclick="$('#initial-dialog').dialog('close');">Cancel</a>
   </div>
</div>
<style>
.ui-dialog.ui-corner-all.ui-widget.ui-widget-content.ui-front.q-dialog.ui-draggable.ui-resizable {
    top: 60px!important;
}
</style>