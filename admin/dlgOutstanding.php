<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");
$student_code=$_POST["student_code"];

function canChangeFee() {
   global $connection;
   $centre_code=$_SESSION["CentreCode"];

   $sql="SELECT can_adjust_fee from centre where centre_code='$centre_code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   if ($row["can_adjust_fee"]=='Y') {
      return true;
   } else {
      return false;
   }
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

function getssid($student_code) {
   global $connection;

   $sql="SELECT id from student where sha1(student_code)='$student_code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["id"];
}
?>
<script>
	$(".checkbox").change(function() {
		if(this.checked) {
			$(this).siblings().val($(this).val());
		} else {
			$(this).siblings().val('');
		}
	});
</script>
<?php
	$ssid=(getssid($student_code));
	$centre_code=$_SESSION["CentreCode"];
	$year=$_SESSION['Year'];
?>
<div class="uk-grid">
   	<div class="uk-width-2-3 uk-form">
		<form class="uk-form uk-form-small" name="frmOutstanding" id="frmOutstanding" method="post" action="admin/saveOutstanding.php">
   			<input type="hidden" name="reg_fee" id="reg_fee" value="">

   			<table class="uk-table uk-table-small uk-form uk-form-small">
   				<tr style="background-color:lightgrey">
         			<td colspan="2">Year Selection</td>
      			</tr>
				<tr>
					<td>
					<select name="year" id="year">
						<option value="">Select Year</option>
						<?php echo getYearOptionList(); ?>
					</select>
					</td>
				</tr>
				<tr style="background-color:lightgrey">
					<td colspan="2">Outstanding From Previous Years and Current Year</td>
				</tr>
      
				<tr>
					<td>Outstanding Amount: <input type="number" placeholder="Outstanding Amount" step="0.01" id="total_amount_s" value="" readonly></td>
				</tr>
	 			<script>
			
					$(document).ready(function(){
						var d = new Date();
						var currentYear = '<?php echo $_SESSION['Year']; ?>';
						$("#year").change(function(){
							if($(this).val()!="") {
								$(".fees").hide();
								$("."+$(this).val()).show();
								var selected_year = $(this).val();
								var t_amount_o_3 = 0;

								$("#year option").each(function()
								{
									var i = $(this).val();
									if(i != '') {
										var fee_amounts = $("."+i).find(".fee_amounts");			
										var t_amount = 0;
										fee_amounts.each(function(){
											var fee_amount = $(this).val();
											if(fee_amount!=""){
												t_amount += parseFloat(fee_amount);
											}
										})
										t_amount_o_3 += t_amount;
										if(i == selected_year) {
											return false;
										}
									}
								});
								
								$("#total_amount_s").val(t_amount_o_3);
							}else{
								$(".fees").hide();
								$("#total_amount_s").val(0);
							}
						})
						
						setTimeout(function(){ 
							$("#year").val(currentYear);
							$("#year").change();
							}, 100);
						
						$("#all_outstanding_fee").change(function(){
							if($(this).val()!=""){
							$(".feeItems").hide();
							$("."+$(this).val()).show();
							}else{
								$(".feeItems").show();
							}
						})
					})
				</script>
				<tr style="background-color:lightgrey">
					<td colspan="2">Current Outstanding</td>
				</tr>
				<tr>
					<td>
					<select name="all_outstanding_fee" id="all_outstanding_fee">
						<option value="">Select Outstanding Fee</option>
						<option value="f1">School Fees</option>
						<option value="f2">Multimedia Fees</option>
						<option value="f3">Facility Fees</option>
						<option value="f4">Afternoon Programme</option>
					</select>
					</td>
				</tr>
   			</table>
   			<?php
			$todayDate = date("Y-m-d"); 
			// start monthly 
			$sql="SELECT ps.id, ps.student_id, fl.afternoon_programme, s.student_code, f.school_adjust, f.subject, f.school_collection, f.multimedia_collection, f.facility_collection, f.basic_collection, f.basic_default_perent, f.multimedia_adjust, f.facility_adjust, f.extend_year, f.basic_adjust, f.afternoon_robotic_adjust, fl.fee_id, fl.programme_date, fl.programme_date_end from student s, programme_selection ps, student_fee_list fl, fee_structure f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.id='$ssid' ";
			
			$result=mysqli_query($connection, $sql);
			$num_row=mysqli_num_rows($result);
			$amount = 0;

			$jan2022 = 0; $feb2022 = 0; $adjustjan2022 = 0; $adjustfeb2022 = 0; $mediaadjustjan2022 = 0; $mediaadjustfeb2022 = 0; $facilityadjustjan2022 = 0; $facilityadjustfeb2022 = 0; $basicadjustjan2022 = 0; $basicadjustfeb2022 = 0;

			while ($row=mysqli_fetch_assoc($result)) {

				if(empty($row['basic_collection'])) { 
					$row['basic_collection'] = $row['basic_default_perent'];
				}

				$programme_date = $row["programme_date"];
				$programme_date = date("m",strtotime($programme_date));
				
				$programme_date_end = $row["programme_date_end"];
				$month = date("m",strtotime($programme_date_end));
		?>
				<div class="<?php echo $row["extend_year"] ?> fees">
				<?php  
					if($row["school_collection"]=="Monthly" || $row["multimedia_collection"]=="Monthly" || $row["facility_collection"]=="Monthly" || $row["basic_collection"]=="Monthly"){ ?>
						<h2><?php echo $row["subject"]; ?></h2>
				<?php } ?>
			
					<table>
						<tr>
							<td>
				<?php 
					$month_array = getMonthList($row["programme_date"], $row["programme_date_end"]);

					if($row["basic_adjust"] == 0 && $row["afternoon_robotic_adjust"] > 0) {
						$afternoon_robotics = 1;
					} else {
						$afternoon_robotics = 0;
					}

					foreach ($month_array as $dt) {

						if($dt->format("M Y") == 'Jan 2022') 
						{ 
							$jan2022 = 1; 
							$adjustjan2022 = $row["school_adjust"]; 
							$mediaadjustjan2022 = $row["multimedia_adjust"];
							$facilityadjustjan2022 = $row["facility_adjust"];
							$basicadjustjan2022 = ($afternoon_robotics == 1) ? $row["afternoon_robotic_adjust"] : $row["basic_adjust"]; 
						}
						if($dt->format("M Y") == 'Feb 2022') 
						{ 
							$feb2022 = 1; 
							$adjustfeb2022 = $row["school_adjust"]; 
							$mediaadjustfeb2022 = $row["multimedia_adjust"]; 
							$facilityadjustfeb2022 = $row["facility_adjust"];
							$basicadjustfeb2022 = ($afternoon_robotics == 1) ? $row["afternoon_robotic_adjust"] : $row["basic_adjust"]; 
						}

						if($row["school_collection"]=="Monthly" || $row["multimedia_collection"]=="Monthly" || $row["facility_collection"]=="Monthly" || $row["basic_collection"]=="Monthly"){
							?>
						<h3 style="margin-bottom: 0px;"><?php echo $dt->format("M Y"); ?></h3>
						<div>
							<?php } ?>
							<?php if($row["school_adjust"]!="" && $row["school_collection"]!=""){ 
								if($row["school_collection"]=="Monthly"){
								?>
							<div class="f1 feeItems">
								<?php 
								$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = ".$dt->format("m")." and c.product_code = 'School Fees' group by c.id ) ab group by CAST(collection_month AS int), fee_structure_id";
							
								$result1=mysqli_query($connection, $sql);
								$IsRow=mysqli_num_rows($result1);
								$row_collection=mysqli_fetch_assoc($result1);
								
									?>
									School Fees
									<br> 
									<input class="<?php if($IsRow<1){ echo "fee_amounts";}?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["school_adjust"]?>" readonly>
									<input type="text" placeholder="Fee"  name="school_collection" value="<?php echo $row["school_collection"]?>" readonly>
								
								<?php 
								if($IsRow<1){ 
								$amount += $row["school_adjust"];
								$item = $row["subject"];
								?>
								
								<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'School Fees'?>', '<?php echo $student_code ?>', 'School Fees', '<?php echo $row["school_adjust"] ?>', '<?php echo $row["id"]?>', '<?php echo $dt->format("m"); ?>', '<?php echo $row["extend_year"];?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
									<?php
								}else{
									if($dt->format("M Y") == 'Jan 2023' || $dt->format("M Y") == 'Feb 2023') {
										
										if(($dt->format("M Y") == 'Jan 2023' && ($row["school_adjust"] + $adjustjan2022) == $row_collection["collection"]) || ($dt->format("M Y") == 'Feb 2023' && ($row["school_adjust"] + $adjustfeb2022) == $row_collection["collection"])){
											echo "Paid";
										}
										else if($dt->format("M Y") == 'Jan 2023' && $jan2022 == 0 && $row["school_adjust"] <= $row_collection["collection"]){
											echo "Paid";
										}
										else if($dt->format("M Y") == 'Feb 2023' && $feb2022 == 0 && $row["school_adjust"] <= $row_collection["collection"]){
											echo "Paid";
										}
										else if(($dt->format("M Y") == 'Jan 2023' && $jan2022 == 1 && ($adjustjan2022) >= $row_collection["collection"]) || ($dt->format("M Y") == 'Feb 2023' && $feb2022 == 1 && ($adjustfeb2022) >= $row_collection["collection"]) || ($dt->format("M Y") == 'Jan 2023' && $jan2022 == 0 && ($row_collection["collection"] == 0)) || ($dt->format("M Y") == 'Feb 2023' && $feb2022 == 0 && ($row_collection["collection"] == 0)))
										{
											$amount += $row["school_adjust"];
											$item = $row["subject"];
								?>
											
											<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'School Fees'?>', '<?php echo $student_code ?>', 'School Fees', '<?php echo $row["school_adjust"] ?>', '<?php echo $row["id"]?>', '<?php echo $dt->format("m"); ?>', '<?php echo $row["extend_year"];?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
								<?php 
										}
										else if(($dt->format("M Y") == 'Jan 2023' && $jan2022 == 1 && ($row["school_adjust"] + $adjustjan2022) > $row_collection["collection"]) || ($dt->format("M Y") == 'Feb 2023' && $feb2022 == 1 && ($row["school_adjust"] + $adjustfeb2022) > $row_collection["collection"]) || ($dt->format("M Y") == 'Jan 2023' && $jan2022 == 0 && $row["school_adjust"] > $row_collection["collection"]) || ($dt->format("M Y") == 'Feb 2023' && $feb2022 == 0 && $row["school_adjust"] > $row_collection["collection"])) {
											
											$balance = ($dt->format("M Y") == 'Jan 2023') ? $row["school_adjust"] - ($row_collection["collection"] - $adjustjan2022) : $row["school_adjust"] - ($row_collection["collection"] - $adjustfeb2022);
								?>
											<input class="fee_amounts" type="hidden" value="<?php echo $balance?>">
											<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'School Fees'?>', '<?php echo $student_code ?>', 'School Fees', '<?php echo $balance; ?>', '<?php echo $row["id"]?>', '<?php echo $dt->format("m"); ?>', '<?php echo $row["extend_year"];?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . ($dt->format("M Y") == 'Jan 2023') ? ($row_collection["collection"] - $adjustjan2022) : ($row_collection["collection"] - $adjustfeb2022);

										}
									} else {
										if($row["school_adjust"] > $row_collection["collection"]){
											$balance = $row["school_adjust"] - $row_collection["collection"];
											?>
											<input class="fee_amounts" type="hidden" value="<?php echo $balance?>">
											<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'School Fees'?>', '<?php echo $student_code ?>', 'School Fees', '<?php echo $balance; ?>', '<?php echo $row["id"]?>', '<?php echo $dt->format("m"); ?>', '<?php echo $row["extend_year"];?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
										}else {
											echo "Paid";
										}
									}
								}
								echo "</div>";
							}
							}
							?>
							<?php if($row["multimedia_adjust"]!="" && $row["multimedia_collection"]!=""){
								if($row["multimedia_collection"]=="Monthly"){
								?>
							<div class="f2 feeItems">
							<br>
							<?php 
								echo "Multimedia Fees";
								echo "<br>";
								$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and  c.collection_month = ".$dt->format("m")." and c.product_code = 'Multimedia Fees' group by c.id ) ab group by CAST(collection_month AS int), fee_structure_id";
								
								$result1=mysqli_query($connection, $sql);
								$IsRow=mysqli_num_rows($result1);
								$row_collection=mysqli_fetch_assoc($result1)
							?>
							<input class="<?php if($IsRow<1){ echo "fee_amounts";}?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["multimedia_adjust"]?>" readonly>
							<input type="text" placeholder="Fee"  name="multimedia_collection" value="<?php echo $row["multimedia_collection"]?>" 	readonly>
							<?php 
								if($IsRow<1){
								$amount += $row["multimedia_adjust"];
								$item = $row["subject"];
									?>
							<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Multimedia Fees'?>', '<?php echo $student_code ?>', 'Multimedia Fees', '<?php echo $row["multimedia_adjust"]?>', '<?php echo $row["id"]?>', '<?php echo $dt->format("m"); ?>', '<?php echo $row["extend_year"];?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
							<?php
							}else{

								if($dt->format("M Y") == 'Jan 2023' || $dt->format("M Y") == 'Feb 2023') {

									if(($dt->format("M Y") == 'Jan 2023' && ($row["multimedia_adjust"] + $mediaadjustjan2022) == $row_collection["collection"]) || ($dt->format("M Y") == 'Feb 2023' && ($row["multimedia_adjust"] + $mediaadjustfeb2022) == $row_collection["collection"]))
									{
										echo "Paid";
									}
									else if($dt->format("M Y") == 'Jan 2023' && $jan2022 == 0 && $row["multimedia_adjust"] <= $row_collection["collection"]){
										echo "Paid";
									}
									else if($dt->format("M Y") == 'Feb 2023' && $feb2022 == 0 && $row["multimedia_adjust"] <= $row_collection["collection"]){
										echo "Paid";
									}
									else if(($dt->format("M Y") == 'Jan 2023' && $jan2022 == 1 && ($mediaadjustjan2022) >= $row_collection["collection"]) || ($dt->format("M Y") == 'Feb 2023' && $feb2022 == 1 && ($mediaadjustfeb2022) >= $row_collection["collection"]) || ($dt->format("M Y") == 'Jan 2023' && $jan2022 == 0 && ($row_collection["collection"] == 0)) || ($dt->format("M Y") == 'Feb 2023' && $feb2022 == 0 && ($row_collection["collection"] == 0)))
									{
										$amount += $row["multimedia_adjust"];
										$item = $row["subject"];
							?>
										
										<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Multimedia Fees'?>', '<?php echo $student_code ?>', 'Multimedia Fees', '<?php echo $row["multimedia_adjust"]?>', '<?php echo $row["id"]?>', '<?php echo $dt->format("m"); ?>', '<?php echo $row["extend_year"];?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
							<?php 
									}
									else if(($dt->format("M Y") == 'Jan 2023' && $jan2022 == 1 && ($row["multimedia_adjust"] + $mediaadjustjan2022) > $row_collection["collection"]) || ($dt->format("M Y") == 'Feb 2023' && $feb2022 == 1 && ($row["multimedia_adjust"] + $mediaadjustfeb2022) > $row_collection["collection"]) || ($dt->format("M Y") == 'Jan 2023' && $jan2022 == 0 && $row["multimedia_adjust"] > $row_collection["collection"]) || ($dt->format("M Y") == 'Feb 2023' && $feb2022 == 0 && $row["multimedia_adjust"] > $row_collection["collection"])) 
									{
										$balance = ($dt->format("M Y") == 'Jan 2023') ? $row["multimedia_adjust"] - ($row_collection["collection"] - $mediaadjustjan2022) : $row["multimedia_adjust"] - ($row_collection["collection"] - $mediaadjustfeb2022);
									?>
										<input class="fee_amounts" type="hidden" value="<?php echo $balance?>">
										<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Multimedia Fees'?>', '<?php echo $student_code ?>', 'Multimedia Fees', '<?php echo $balance; ?>', '<?php echo $row["id"]?>', '<?php echo $dt->format("m"); ?>', '<?php echo $row["extend_year"];?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . ($dt->format("M Y") == 'Jan 2023') ? ($row_collection["collection"] - $mediaadjustjan2022) : ($row_collection["collection"] - $mediaadjustfeb2022);
									}
								} else {
									if($row["multimedia_adjust"] > $row_collection["collection"]){
										$balance = $row["multimedia_adjust"] - $row_collection["collection"];
										?>
										<input class="fee_amounts" type="hidden" value="<?php echo $balance?>">
										<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Multimedia Fees'?>', '<?php echo $student_code ?>', 'Multimedia Fees', '<?php echo $balance; ?>', '<?php echo $row["id"]?>', '<?php echo $dt->format("m"); ?>', '<?php echo $row["extend_year"];?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
									}else {
										echo "Paid";
									}
								}
							}
							echo "</div>";
							}
						}
							?>
		
							<?php if($row["facility_adjust"]!="" && $row["facility_collection"]!=""){
								if($row["facility_collection"]=="Monthly"){
								?>
							<!-- <h3 style="margin-bottom: 0px;">Facility Fees</h3> -->
							<div class="f3 feeItems">
							<br>
							<?php 
								echo "Facility Fees";
								echo "<br>";
								$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = ".$dt->format("m")." and c.product_code = 'Facility Fees' group by c.id ) ab group by CAST(collection_month AS int), fee_structure_id";
								$result1=mysqli_query($connection, $sql);
								$IsRow=mysqli_num_rows($result1);
								$row_collection=mysqli_fetch_assoc($result1)
							?>
								<input class="<?php if($IsRow<1){ echo "fee_amounts";}?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["facility_adjust"]?>" readonly>
									<input type="text" placeholder="Fee"  name="facility_collection" value="<?php echo $row["facility_collection"]?>" readonly>
								
									<?php 
										
									if($IsRow<1){
										$amount += $row["facility_adjust"];
										$item = $row["subject"];
									?>
									<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Facility Fees'?>', '<?php echo $student_code ?>', 'Facility Fees', '<?php echo $row["facility_adjust"]?>', '<?php echo $row["id"]?>', '<?php echo $dt->format("m"); ?>', '<?php echo $row["extend_year"];?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
							<?php
								}else{

									if($dt->format("M Y") == 'Jan 2023' || $dt->format("M Y") == 'Feb 2023') {

										if(($dt->format("M Y") == 'Jan 2023' && ($row["facility_adjust"] + $facilityadjustjan2022) == $row_collection["collection"]) || ($dt->format("M Y") == 'Feb 2023' && ($row["facility_adjust"] + $facilityadjustfeb2022) == $row_collection["collection"]))
										{
											echo "Paid";
										}
										else if($dt->format("M Y") == 'Jan 2023' && $jan2022 == 0 && $row["facility_adjust"] <= $row_collection["collection"]){
											echo "Paid";
										}
										else if($dt->format("M Y") == 'Feb 2023' && $feb2022 == 0 && $row["facility_adjust"] <= $row_collection["collection"]){
											echo "Paid";
										}
										else if(($dt->format("M Y") == 'Jan 2023' && $jan2022 == 1 && ($facilityadjustjan2022) >= $row_collection["collection"]) || ($dt->format("M Y") == 'Feb 2023' && $feb2022 == 1 && ($facilityadjustfeb2022) >= $row_collection["collection"]) || ($dt->format("M Y") == 'Jan 2023' && $jan2022 == 0 && ($row_collection["collection"] == 0)) || ($dt->format("M Y") == 'Feb 2023' && $feb2022 == 0 && ($row_collection["collection"] == 0)))
										{
											$amount += $row["facility_adjust"];
											$item = $row["subject"];
											?>
											
											<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Facility Fees'?>', '<?php echo $student_code ?>', 'Facility Fees', '<?php echo $row["facility_adjust"]?>', '<?php echo $row["id"]?>', '<?php echo $dt->format("m"); ?>', '<?php echo $row["extend_year"];?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
								<?php 
										}
										else if(($dt->format("M Y") == 'Jan 2023' && $jan2022 == 1 && ($row["facility_adjust"] + $facilityadjustjan2022) > $row_collection["collection"]) || ($dt->format("M Y") == 'Feb 2023' && $feb2022 == 1 && ($row["facility_adjust"] + $facilityadjustfeb2022) > $row_collection["collection"]) || ($dt->format("M Y") == 'Jan 2023' && $jan2022 == 0 && $row["facility_adjust"] > $row_collection["collection"]) || ($dt->format("M Y") == 'Feb 2023' && $feb2022 == 0 && $row["facility_adjust"] > $row_collection["collection"])) 
										{
											$balance = ($dt->format("M Y") == 'Jan 2023') ? $row["facility_adjust"] - ($row_collection["collection"] - $facilityadjustjan2022) : $row["facility_adjust"] - ($row_collection["collection"] - $facilityadjustfeb2022);
								?>
											<input class="fee_amounts" type="hidden" value="<?php echo $balance?>">
											<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Facility Fees'?>', '<?php echo $student_code ?>', 'Facility Fees', '<?php echo $balance; ?>', '<?php echo $row["id"] ?>', '<?php echo $dt->format("m"); ?>', '<?php echo $row["extend_year"];?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . ($dt->format("M Y") == 'Jan 2023') ? ($row_collection["collection"] - $facilityadjustjan2022) : ($row_collection["collection"] - $facilityadjustfeb2022); 
										}
									} else {

										if($row["facility_adjust"] > $row_collection["collection"]){
											$balance = $row["facility_adjust"] - $row_collection["collection"];
											?>
											<input class="fee_amounts" type="hidden" value="<?php echo $balance?>">
											<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Facility Fees'?>', '<?php echo $student_code ?>', 'Facility Fees', '<?php echo $balance; ?>', '<?php echo $row["id"] ?>', '<?php echo $dt->format("m"); ?>', '<?php echo $row["extend_year"];?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
										}else {
											echo "Paid";
										}
									}
								}
								echo "</div>";
							}
						}
						if($row["afternoon_programme"]){
							if($row["basic_adjust"]!="" && $row["basic_collection"]!=""){
								if($row["basic_collection"]=="Monthly"){
					?>
									<div class="f4 feeItems">
									<br>
									<?php 
										if(isset($afternoon_robotics) && $afternoon_robotics == 1)
										{
											$row["basic_adjust"] = $row["afternoon_robotic_adjust"];
											echo "Afternoon Programme + Robotics";
										}
										else
										{
											echo "Afternoon Programme";
										}
										
										echo "<br>";
										$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = ".$dt->format("m")." and c.product_code = 'Afternoon Programme' group by c.id ) ab group by CAST(collection_month AS int), fee_structure_id";
										//	$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join fee_structure f on f.id= p.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and c.collection_month = $i and c.product_code = 'Afternoon Programme' group by c.id ) ab group by CAST(collection_month AS int), fee_structure_id";
										//echo $sql;
												$result1=mysqli_query($connection, $sql);
												$IsRow=mysqli_num_rows($result1);
												$row_collection=mysqli_fetch_assoc($result1)
											?>
												<input class="<?php if($IsRow<1){ echo "fee_amounts";}?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["basic_adjust"]?>" readonly>
												<input type="text" placeholder="Fee"  name="basic_collection" value="<?php echo $row["basic_collection"]?>" readonly>
												<?php 
													
											if($IsRow<1){
													$amount += $row["basic_adjust"];
													$item = $row["subject"];
												?>
												<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Afternoon Programme'?>', '<?php echo $student_code ?>', 'Afternoon Programme', '<?php echo $row["basic_adjust"]?>', '<?php echo $row["id"]?>',  '<?php echo $dt->format("m"); ?>', '<?php echo $row["extend_year"];?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
										<?php
											}else{

												if($dt->format("M Y") == 'Jan 2023' || $dt->format("M Y") == 'Feb 2023') {

													if(($dt->format("M Y") == 'Jan 2023' && ($row["basic_adjust"] + $basicadjustjan2022) == $row_collection["collection"]) || ($dt->format("M Y") == 'Feb 2023' && ($row["basic_adjust"] + $basicadjustfeb2022) == $row_collection["collection"]))
													{
														echo "Paid";
													}
													else if($dt->format("M Y") == 'Jan 2023' && $jan2022 == 0 && $row["basic_adjust"] <= $row_collection["collection"]){
														echo "Paid";
													}
													else if($dt->format("M Y") == 'Feb 2023' && $feb2022 == 0 && $row["basic_adjust"] <= $row_collection["collection"]){
														echo "Paid";
													}
													else if(($dt->format("M Y") == 'Jan 2023' && $jan2022 == 1 && ($basicadjustjan2022) >= $row_collection["collection"]) || ($dt->format("M Y") == 'Feb 2023' && $feb2022 == 1 && ($basicadjustfeb2022) >= $row_collection["collection"]) || ($dt->format("M Y") == 'Jan 2023' && $jan2022 == 0 && ($row_collection["collection"] == 0)) || ($dt->format("M Y") == 'Feb 2023' && $feb2022 == 0 && ($row_collection["collection"] == 0)))
													{
														$amount += $row["basic_adjust"];
														$item = $row["subject"];
												?>
														<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Afternoon Programme'?>', '<?php echo $student_code ?>', 'Afternoon Programme', '<?php echo $row["basic_adjust"]?>', '<?php echo $row["id"]?>',  '<?php echo $dt->format("m"); ?>', '<?php echo $row["extend_year"];?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
											<?php 
													}
													else if(($dt->format("M Y") == 'Jan 2023' && $jan2022 == 1 && ($row["basic_adjust"] + $basicadjustjan2022) > $row_collection["collection"]) || ($dt->format("M Y") == 'Feb 2023' && $feb2022 == 1 && ($row["basic_adjust"] + $basicadjustfeb2022) > $row_collection["collection"]) || ($dt->format("M Y") == 'Jan 2023' && $jan2022 == 0 && $row["basic_adjust"] > $row_collection["collection"]) || ($dt->format("M Y") == 'Feb 2023' && $feb2022 == 0 && $row["basic_adjust"] > $row_collection["collection"])) 
													{
														$balance = ($dt->format("M Y") == 'Jan 2023') ? $row["basic_adjust"] - ($row_collection["collection"] - $basicadjustjan2022) : $row["basic_adjust"] - ($row_collection["collection"] - $basicadjustfeb2022);
														?>
														<input class="fee_amounts" type="hidden" value="<?php echo $balance?>">
														<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Afternoon Programme'?>', '<?php echo $student_code ?>', 'Afternoon Programme', '<?php echo $balance; ?>', '<?php echo $row["id"]?>', '<?php echo $dt->format("m"); ?>', '<?php echo $row["extend_year"];?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . ($dt->format("M Y") == 'Jan 2023') ? ($row_collection["collection"] - $basicadjustjan2022) : ($row_collection["collection"] - $basicadjustfeb2022);
													}
												} else {

													if($row["basic_adjust"] > $row_collection["collection"]){
														$balance = $row["basic_adjust"] - $row_collection["collection"];
														?>
														<input class="fee_amounts" type="hidden" value="<?php echo $balance?>">
														<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Afternoon Programme'?>', '<?php echo $student_code ?>', 'Afternoon Programme', '<?php echo $balance; ?>', '<?php echo $row["id"]?>', '<?php echo $dt->format("m"); ?>', '<?php echo $row["extend_year"];?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
													}else {
														echo "Paid";
													}

												}
											}
											echo "</div>";
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
// end monthly 

// start termly 
	$sql="SELECT ps.id, ps.student_id, fl.afternoon_programme, s.student_code, f.school_adjust, f.subject, f.school_collection, f.multimedia_collection, f.facility_collection, f.basic_collection, f.multimedia_adjust, f.facility_adjust, f.extend_year, f.basic_adjust, f.afternoon_robotic_adjust, fl.fee_id, fl.programme_date, fl.programme_date_end from student s, programme_selection ps, student_fee_list fl, fee_structure f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.id='$ssid'";
	$result=mysqli_query($connection, $sql);
	$num_row=mysqli_num_rows($result);
	$amount = 0;
	$i=0;
	$extend_year = 0;
  while ($row=mysqli_fetch_assoc($result)) {
		  $programme_date = $row["programme_date"];
		  $programme_date = date("m",strtotime($programme_date));
		  $programme_date_end = $row["programme_date_end"];
		  $month = date("m",strtotime($programme_date_end));
	  ?>
	  <div class="<?php echo $row["extend_year"] ?> fees">
	  <?php  if($row["school_collection"]=="Termly" || $row["multimedia_collection"]=="Termly" || $row["facility_collection"]=="Termly" || ($row["basic_collection"]=="Termly" && $row["afternoon_programme"] == 1)){ ?>
			<h2><?php echo $row["subject"]?></h2>
			<?php	} ?>
		  
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
			if($row["basic_adjust"] == 0 && $row["afternoon_robotic_adjust"] > 0) {
				$afternoon_robotics = 1;
			} else {
				$afternoon_robotics = 0;
			}
		  	for ($i; $i < $term1; $i++) { 
				  if($row["school_collection"]=="Termly" || $row["multimedia_collection"]=="Termly" || $row["facility_collection"]=="Termly" || ($row["basic_collection"]=="Termly" && $row["afternoon_programme"] == 1)){
				  ?>
				  <div>
		  <label>
			  <h3 style="margin-bottom: 0px;"><?php echo getStrTerm($i+1);; ?></h3>
				  <?php 
				  }
		  if($row["school_adjust"]!="" && $row["school_collection"]!=""){ 
			  if($row["school_collection"]=="Termly"){
				  echo '<div class="f1 feeItems">';
				  $month = $i+1;
				  echo "School Fees";
				  $sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '$month' and c.product_code = 'School Fees' group by c.id ) ab group by CAST(collection_month AS int), fee_structure_id";
				  $result1=mysqli_query($connection, $sql);
				  $IsRow=mysqli_num_rows($result1);
				  $row_collection=mysqli_fetch_assoc($result1)
				  ?>
				  <br> 
				  <input class="<?php if($IsRow<1){ echo"fee_amounts";}?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["school_adjust"]?>" readonly>
				  <input type="text" placeholder="Fee"  name="school_collection" value="<?php echo $row["school_collection"]?>" readonly>
				  </label>
				  <?php
				  
				  if($IsRow<1){
					  $amount += $row["school_adjust"];
					  $item = $row["subject"];
				  ?>
				  <a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'School Fees'?>', '<?php echo $student_code ?>', 'School Fees', '<?php echo $row["school_adjust"]?>', '<?php echo $row["id"]?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Termly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
				  <?php
				  //}
			  }else{
				  if($row["school_adjust"] > $row_collection["collection"]){
					  $balance = $row["school_adjust"] - $row_collection["collection"];
					  ?>
					  <input class="fee_amounts" type="hidden" value="<?php echo $balance?>">
					  <a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'School Fees'?>', '<?php echo $student_code ?>', 'School Fees', '<?php echo $balance; ?>', '<?php echo $row["id"]?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Termly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
				  }else {
					  echo "Paid";
				  }
			  }
			  }
			  echo "</div>";
		  }
			  if($row["multimedia_collection"]=="Termly"){
				  echo '<div class="f2 feeItems">';
				  $term = getTermFromDate(date('Y-m-d',strtotime($row['programme_date'])));
					  $month = $i+1;
					  echo "<br>";
					  echo 'Multimedia Fees';
					  echo "<br>";
					  $sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '$month' and c.product_code = 'Multimedia Fees' group by c.id ) ab group by CAST(collection_month AS int), fee_structure_id";
					  $result1=mysqli_query($connection, $sql);
					  $IsRow=mysqli_num_rows($result1);
					  $row_collection=mysqli_fetch_assoc($result1)
				  ?>
					  <input class="<?php if($IsRow<1){ echo "fee_amounts";}?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["multimedia_adjust"]?>" readonly>
						  <input type="text" placeholder="Fee"  name="multimedia_collection" value="<?php echo $row["multimedia_collection"]?>" readonly>
						  </label>
						  <?php 
					  if($IsRow<1){
						  $amount += $row["multimedia_adjust"];
						  $item = $row["subject"];
						  ?>
					  <a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Multimedia Fees'?>', '<?php echo $student_code ?>', 'Multimedia Fees', '<?php echo $row["multimedia_adjust"]?>', '<?php echo $row["id"]?>', '<?php echo $i+1;?>', '<?php echo $row["extend_year"];?>', 'Termly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
				  <?php
					  //}
				  }else{
					  if($row["multimedia_adjust"] > $row_collection["collection"]){
						  $balance = $row["multimedia_adjust"] - $row_collection["collection"];
						  ?>
						  <input class="fee_amounts" type="hidden" value="<?php echo $balance?>">
						  <a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Multimedia Fees'?>', '<?php echo $student_code ?>', 'Multimedia Fees', '<?php echo $balance; ?>', '<?php echo $row["id"]?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Termly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
					  }else {
						  echo "Paid";
					  }
				  }
				  echo "<br>";
				  echo "</div>";
				  }
			  	if($row["facility_collection"]=="Termly"){
					$term = getTermFromDate(date('Y-m-d',strtotime($row['programme_date'])));
					  echo '<div class="f3 feeItems">';
					  $month = $i+1;
				  echo "<br>";
				  echo 'Facility Fees';
				  echo "<br>";
				  $sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '$month' and c.product_code = 'Facility Fees' group by c.id ) ab group by CAST(collection_month AS int), fee_structure_id";
				  $result1=mysqli_query($connection, $sql);
				  $IsRow=mysqli_num_rows($result1);
				  $row_collection=mysqli_fetch_assoc($result1)
				  ?>
					  <input class="<?php if($IsRow<1){ echo "fee_amounts";}?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["facility_adjust"]?>" readonly>
				  <input type="text" placeholder="Fee"  name="facility_collection" value="<?php echo $row["facility_collection"]?>" readonly>
				  </label>
				  <?php 
						  
				  if($IsRow<1){
					  $amount += $row["facility_adjust"];
					  $item = $row["subject"];
				  ?>
				  <a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Facility Fees'?>', '<?php echo $student_code ?>', 'Facility Fees', '<?php echo $row["facility_adjust"]?>', '<?php echo $row["id"]?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Termly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
				  <?php
				  //}
				  }else{
					  if($row["facility_adjust"] > $row_collection["collection"]){
						  $balance = $row["facility_adjust"] - $row_collection["collection"];
						  ?>
						  <input class="fee_amounts" type="hidden" value="<?php echo $balance?>">
						  <a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Facility Fees'?>', '<?php echo $student_code ?>', 'Facility Fees', '<?php echo $balance; ?>', '<?php echo $row["id"]?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Termly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
					  }else {
						  echo "Paid";
					  }
				  }
				  echo "</div>";
			  	}
			  	if($row["afternoon_programme"]){
			  		if($row["basic_collection"]=="Termly"){
						$term = getTermFromDate(date('Y-m-d',strtotime($row['programme_date'])));
					  	echo '<div class="f4 feeItems">';
					  	$month = $i+1;
				  		echo "<br>";
						if(isset($afternoon_robotics) && $afternoon_robotics == 1)
						{
							$row["basic_adjust"] = $row["afternoon_robotic_adjust"];
							echo "Afternoon Programme + Robotics";
						}
						else
						{
							echo "Afternoon Programme";
						}
				  		echo "<br>";
				  		$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '$month' and c.product_code = 'Afternoon Programme' group by c.id ) ab group by CAST(collection_month AS int), fee_structure_id";
				  		$result1=mysqli_query($connection, $sql);
				  		$IsRow=mysqli_num_rows($result1);
				  		$row_collection=mysqli_fetch_assoc($result1)
				  ?>
				  		<input class="<?php if($IsRow<1){ echo "fee_amounts";}?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["basic_adjust"]?>" readonly>
				  		<input type="text" placeholder="Fee"  name="basic_collection" value="<?php echo $row["basic_collection"]?>" readonly>
				  	</label>
				  	<?php
					  
				  	if($IsRow<1) {
						$amount += $row["basic_adjust"];
						$item = $row["subject"];
				  	?>
				  		<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Afternoon Programme'?>', '<?php echo $student_code ?>', 'Afternoon Programme', '<?php echo $row["basic_adjust"]?>', '<?php echo $row["id"]?>',  '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Termly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
				  	<?php
				  	
					} else {
					  if($row["basic_adjust"] > $row_collection["collection"]){
						  $balance = $row["basic_adjust"] - $row_collection["collection"];
						  ?>
						  <input class="fee_amounts" type="hidden" value="<?php echo $balance?>">
						  <a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Afternoon Programme'?>', '<?php echo $student_code ?>', 'Afternoon Programme', '<?php echo $balance; ?>', '<?php echo $row["id"]?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Termly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
					  }else {
						  echo "Paid";
					  }
				  }
				  echo "</div>";
				  echo "<br>";
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
// end termly

// start half yearly 
	$sql="SELECT ps.id, ps.student_id, fl.afternoon_programme, s.student_code, f.school_adjust, f.subject, f.school_collection, f.multimedia_collection, f.facility_collection, f.basic_collection, f.multimedia_adjust, f.facility_adjust, f.extend_year, f.basic_adjust, f.afternoon_robotic_adjust, fl.fee_id, fl.programme_date, fl.programme_date_end from student s, programme_selection ps, student_fee_list fl, fee_structure f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.id='$ssid'";
	$result=mysqli_query($connection, $sql);
	$num_row=mysqli_num_rows($result);
	$amount = 0;
	$i=0;
	$extend_year = 0;
  	while ($row=mysqli_fetch_assoc($result)) {
		  $programme_date = $row["programme_date"];
		  $programme_date = date("m",strtotime($programme_date));
		  $programme_date_end = $row["programme_date_end"];
		  $month = date("m",strtotime($programme_date_end));
	  ?>
	  <div class="<?php echo $row["extend_year"] ?> fees">
	  <?php  if($row["school_collection"]=="Half Year" || $row["multimedia_collection"]=="Half Year" || $row["facility_collection"]=="Half Year" || $row["basic_collection"]=="Half Year"){ ?>
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

			if($row["basic_adjust"] == 0 && $row["afternoon_robotic_adjust"] > 0) {
				$afternoon_robotics = 1;
			} else {
				$afternoon_robotics = 0;
			}

		  for ($i; $i < $HearfYearly; $i++) { 
				  if($row["school_collection"]=="Half Year" || $row["multimedia_collection"]=="Half Year" || $row["facility_collection"]=="Half Year" || $row["basic_collection"]=="Half Year"){
				  ?>
				  <div>
		  <label>
			  <h3 style="margin-bottom: 0px;"><?php echo getStrHalfYearly($i+1); ?></h3>
				  <?php 
				  }
		  if($row["school_adjust"]!="" && $row["school_collection"]!=""){ 
			  if($row["school_collection"]=="Half Year"){
				  echo '<div class="f1 feeItems">';
				  $month = $i+1;
				  echo "<br>";
				  echo 'School Fees';
					  $sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '$month' and c.product_code = 'School Fees' group by c.id ) ab group by CAST(collection_month AS int), fee_structure_id";
					  $result1=mysqli_query($connection, $sql);
					  $IsRow=mysqli_num_rows($result1);
					  $row_collection=mysqli_fetch_assoc($result1)
				  ?>
				  <br> 
					  <input class="<?php if($IsRow<1){ echo "fee_amounts";}?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["school_adjust"]?>" readonly>
					  <input type="text" placeholder="Fee"  name="school_collection" value="<?php echo $row["school_collection"]?>" readonly>
				  </label>
				  <?php
					  
					  if($IsRow<1){
						  $amount += $row["school_adjust"];
						  $item = $row["subject"];
				  ?>
				  <a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'School Fees'?>', '<?php echo $student_code ?>', 'School Fees', '<?php echo $row["school_adjust"]?>', '<?php echo $row["id"]?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Half Year')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
					  <?php
					  //}
				  }else{
					  if($row["school_adjust"] > $row_collection["collection"]){
						  $balance = $row["school_adjust"] - $row_collection["collection"];
						  ?>
						  <input class="fee_amounts" type="hidden" value="<?php echo $balance?>">
						  <a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'School Fees'?>', '<?php echo $student_code ?>', 'School Fees', '<?php echo $balance; ?>', '<?php echo $row["id"]?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Half Year')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
					  }else {
						  echo "Paid";
					  }

				  }
			  }
			  echo "</div>";
		  }
		  if($row["multimedia_collection"]=="Half Year"){
				  $month = $i+1;
				  echo '<div class="f2 feeItems">';
				  echo "<br>";
				  echo 'Multimedia Fees';
				  echo "<br>";
				  $sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '$month' and c.product_code = 'Multimedia Fees' group by c.id ) ab group by CAST(collection_month AS int), fee_structure_id";
				  $result1=mysqli_query($connection, $sql);
				  $IsRow=mysqli_num_rows($result1);
				  $row_collection=mysqli_fetch_assoc($result1)
				  ?>
					  <input class="<?php if($IsRow<1){ echo "fee_amounts";}?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["multimedia_adjust"]?>" readonly>
						  <input type="text" placeholder="Fee"  name="multimedia_collection" value="<?php echo $row["multimedia_collection"]?>" readonly>
					  </label>
					  <?php 
				  if($IsRow<1){
					  $amount += $row["multimedia_adjust"];
					  $item = $row["subject"];
					  ?>
					  <a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Multimedia Fees'?>', '<?php echo $student_code ?>', 'Multimedia Fees', '<?php echo $row["multimedia_adjust"]?>', '<?php echo $row["id"]?>', '<?php echo $i+1;?>', '<?php echo $row["extend_year"];?>', 'Half Year')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
			  <?php
			  //}
			  }else{
				  if($row["multimedia_adjust"] > $row_collection["collection"]){
					  $balance = $row["multimedia_adjust"] - $row_collection["collection"];
					  ?>
					  <input class="fee_amounts" type="hidden" value="<?php echo $balance?>">
					  <a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Multimedia Fees'?>', '<?php echo $student_code ?>', 'Multimedia Fees', '<?php echo $balance; ?>', '<?php echo $row["id"]?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Half Year')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
				  }else {
					  echo "Paid";
				  }
			  }
			  echo "</div>";
		  }
		  if($row["facility_collection"]=="Half Year"){
			  echo '<div class="f3 feeItems">';
			  $month = $i+1;
				  echo "<br>";
				  echo 'Facility Fees';
				  echo "<br>";
				  $sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '$month' and c.product_code = 'Facility Fees' group by c.id ) ab group by CAST(collection_month AS int), fee_structure_id";
						  $result1=mysqli_query($connection, $sql);
						  $IsRow=mysqli_num_rows($result1);
						  $row_collection=mysqli_fetch_assoc($result1)
						  ?>
						  <input class="<?php if($IsRow<1){ echo "fee_amounts";}?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["facility_adjust"]?>" readonly>
						  <input type="text" placeholder="Fee"  name="facility_collection" value="<?php echo $row["facility_collection"]?>" readonly>
						  </label>
						  <?php 
							  
						  if($IsRow<1){
							  $amount += $row["facility_adjust"];
							  $item = $row["subject"];
						  ?>
						  <a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Facility Fees'?>', '<?php echo $student_code ?>', 'Facility Fees', '<?php echo $row["facility_adjust"]?>', '<?php echo $row["id"]?>', '<?php echo  $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Half Year')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
					  <?php
					  }else{
						  if($row["facility_adjust"] > $row_collection["collection"]){
							  $balance = $row["facility_adjust"] - $row_collection["collection"];
							  ?>
							  <input class="fee_amounts" type="hidden" value="<?php echo $balance?>">
							  <a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Facility Fees'?>', '<?php echo $student_code ?>', 'Facility Fees', '<?php echo $balance; ?>', '<?php echo $row["id"]?>', '<?php echo  $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Half Year')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
						  }else {
							  echo "Paid";
						  } 
					  }
					  echo "</div>";
		  }
		  if($row["afternoon_programme"]){
		  if($row["basic_collection"]=="Half Year"){
			  echo '<div class="f4 feeItems">';
			  $month = $i+1;
				  echo "<br>";
				  	if(isset($afternoon_robotics) && $afternoon_robotics == 1)
					{
						$row["basic_adjust"] = $row["afternoon_robotic_adjust"];
						echo "Afternoon Programme + Robotics";
					}
					else
					{
						echo "Afternoon Programme";
					}
				  echo "<br>";
				  $sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '$month' and c.product_code = 'Afternoon Programme' group by c.id ) ab group by CAST(collection_month AS int), fee_structure_id";
						  $result1=mysqli_query($connection, $sql);
						  $IsRow=mysqli_num_rows($result1);
						  $row_collection=mysqli_fetch_assoc($result1)
						  ?>
						  <input class="<?php if($IsRow<1){ echo "fee_amounts";}?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["basic_adjust"]?>" readonly>
						  <input type="text" placeholder="Fee"  name="basic_collection" value="<?php echo $row["basic_collection"]?>" readonly>
						  </label>
						  <?php
							  
						  if($IsRow<1){
							  $amount += $row["basic_adjust"];
							  $item = $row["subject"];
						  ?>
						  <a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Afternoon Programme'?>', '<?php echo $student_code ?>', 'Afternoon Programme', '<?php echo $row["basic_adjust"]?>', '<?php echo $row["id"]?>',  '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Half Year')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
						  <?php
						  //}
					  }else{
						  if($row["basic_adjust"] > $row_collection["collection"]){
							  $balance = $row["basic_adjust"] - $row_collection["collection"];
							  ?>
							  <input class="fee_amounts" type="hidden" value="<?php echo $balance?>">
							  <a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Afternoon Programme'?>', '<?php echo $student_code ?>', 'Afternoon Programme', '<?php echo $balance; ?>', '<?php echo $row["id"]?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Half Year')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
						  }else {
							  echo "Paid";
						  } 
					  }
					  echo "</div>";
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
// end half yearly

// start annylly 
	$sql="SELECT ps.id, ps.student_id, fl.afternoon_programme, s.student_code, f.school_adjust, f.subject, f.school_collection, f.multimedia_collection, f.facility_collection, f.basic_collection, f.multimedia_adjust, f.facility_adjust, f.extend_year, f.basic_adjust, f.afternoon_robotic_adjust, fl.fee_id, fl.programme_date, fl.programme_date_end from student s, programme_selection ps, student_fee_list fl, fee_structure f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.id='$ssid'  group by ps.id";
	$result=mysqli_query($connection, $sql);
	$num_row=mysqli_num_rows($result);
	$amount = 0;
	while ($row=mysqli_fetch_assoc($result)) {
		  $programme_date = $row["programme_date"];
		  $programme_date = date("m",strtotime($programme_date));
		  $programme_date_end = $row["programme_date_end"];
		  $month = date("m",strtotime($programme_date_end));

		if($row["basic_adjust"] == 0 && $row["afternoon_robotic_adjust"] > 0) {
			$afternoon_robotics = 1;
		} else {
			$afternoon_robotics = 0;
		}
	  ?>
	  <div class="<?php echo $row["extend_year"] ?> fees">
	  <?php  if($row["school_collection"]=="Annually" || $row["multimedia_collection"]=="Annually" || $row["facility_collection"]=="Annually" || $row["basic_collection"]=="Annually"){ ?>
			<h2><?php echo $row["subject"]?></h2>
			<?php	} ?>
		  
	  <table>
		  <tr>
		  <td>
		  <!---------------start Annually-------------------------------------->
		  <?php 
				  if($row["school_collection"]=="Annually" || $row["multimedia_collection"]=="Annually" || $row["facility_collection"]=="Annually" || $row["basic_collection"]=="Annually"){
				  ?>
				  <div>
		  <label>
			  <h3 style="margin-bottom: 0px;">Annually</h3>
				  <?php 
				  }
		  if($row["school_adjust"]!="" && $row["school_collection"]!=""){ 
			  if($row["school_collection"]=="Annually"){
				  echo '<div class="f1 feeItems">';
				  echo "<br>";
				  echo 'School Fees';
				  echo "<br>";
				  $sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '1' and c.product_code = 'School Fees' group by c.id ) ab group by CAST(collection_month AS int), fee_structure_id";
						  $result1=mysqli_query($connection, $sql);
						  $IsRow=mysqli_num_rows($result1);
						  $row_collection=mysqli_fetch_assoc($result1)
					  ?>
					  <br> 
						  <input class="<?php if($IsRow<1){ echo "fee_amounts";}?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["school_adjust"]?>" readonly>
						  <input type="text" placeholder="Fee"  name="school_collection" value="<?php echo $row["school_collection"]?>" readonly>
					  </label>
					  <?php
						  
						  if($IsRow<1){
							  $amount += $row["school_adjust"];
							  $item = $row["subject"];
					  ?>
					  <a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'School Fees'?>', '<?php echo $student_code ?>', 'School Fees', '<?php echo $row["school_adjust"]?>', '<?php echo $row["id"]?>', '1', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
						  <?php
						  }else{
							  if($row["school_adjust"] > $row_collection["collection"]){
								  $balance = $row["school_adjust"] - $row_collection["collection"];
								  ?>
								  <input class="fee_amounts" type="hidden" value="<?php echo $balance?>">
								  <a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'School Fees'?>', '<?php echo $student_code ?>', 'School Fees', '<?php echo $balance; ?>', '<?php echo $row["id"]?>', '1', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
							  }else {
								  echo "Paid";
							  } 
					  }
			  }
			  echo "</div>";
		  }
		  if($row["multimedia_collection"]=="Annually"){
			  echo '<div class="f2 feeItems">';
				  echo "<br>";
				  echo 'Multimedia Fees';
				  echo "<br>";
				  $sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '1' and c.product_code = 'Multimedia Fees' group by c.id ) ab group by CAST(collection_month AS int), fee_structure_id";
						  $result1=mysqli_query($connection, $sql);
						  $IsRow=mysqli_num_rows($result1);
						  $row_collection=mysqli_fetch_assoc($result1)
					  ?>
						  <input class="<?php if($IsRow<1){ echo "fee_amounts";}?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["multimedia_adjust"]?>" readonly>
						  <input type="text" placeholder="Fee"  name="multimedia_collection" value="<?php echo $row["multimedia_collection"]?>" readonly>
						  </label>
						  <?php
							  
						  if($IsRow<1){
							  $amount += $row["multimedia_adjust"];
							  $item = $row["subject"];
						  ?>
						  <a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Multimedia Fees'?>', '<?php echo $student_code ?>', 'Multimedia Fees', '<?php echo $row["multimedia_adjust"]?>', '<?php echo $row["id"]?>', '1', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
				  <?php  
					  }else{
						  if($row["multimedia_adjust"] > $row_collection["collection"]){
							  $balance = $row["multimedia_adjust"] - $row_collection["collection"];
							  ?>
							  <input class="fee_amounts" type="hidden" value="<?php echo $balance?>">
							  <a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Multimedia Fees'?>', '<?php echo $student_code ?>', 'Multimedia Fees', '<?php echo $balance; ?>', '<?php echo $row["id"]?>', '1', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
						  }else {
							  echo "Paid";
						  } 
					  }
					  echo "</div>";
		  }
		  if($row["facility_collection"]=="Annually"){
			  echo '<div class="f3 feeItems">';
				  echo "<br>";
				  echo 'Facility Fees';
				  echo "<br>";
				  $sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '1' and c.product_code = 'Facility Fees' group by c.id ) ab group by CAST(collection_month AS int), fee_structure_id";
				  $result1=mysqli_query($connection, $sql);
				  $IsRow=mysqli_num_rows($result1);
				  $row_collection=mysqli_fetch_assoc($result1)
				  ?>
				  <input class="<?php if($IsRow<1){ echo "fee_amounts";}?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["facility_adjust"]?>" readonly>
				  <input type="text" placeholder="Fee"  name="facility_collection" value="<?php echo $row["facility_collection"]?>" readonly>
				  </label>
				  <?php
					  
					  if($IsRow<1){
						  $amount += $row["facility_adjust"];
						  $item = $row["subject"];
				  ?>
				  <a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Facility Fees'?>', '<?php echo $student_code ?>', 'Facility Fees', '<?php echo $row["facility_adjust"]?>', '<?php echo $row["id"]?>', '1', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
				  <?php
				  }else{
					  if($row["facility_adjust"] > $row_collection["collection"]){
						  $balance = $row["facility_adjust"] - $row_collection["collection"];
						  ?>
						  <input class="fee_amounts" type="hidden" value="<?php echo $balance?>">
						  <a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Facility Fees'?>', '<?php echo $student_code ?>', 'Facility Fees', '<?php echo $balance; ?>', '<?php echo $row["id"]?>', '1', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
					  }else {
						  echo "Paid";
					  }
				  }
				  echo "</div>";
		  }
		  if($row["afternoon_programme"]){
		  if($row["basic_collection"]=="Annually"){
			  echo '<div class="f4 feeItems">';
				  echo "<br>";
				  	if(isset($afternoon_robotics) && $afternoon_robotics == 1)
					{
						$row["basic_adjust"] = $row["afternoon_robotic_adjust"];
						echo "Afternoon Programme + Robotics";
					}
					else
					{
						echo "Afternoon Programme";
					}
				  echo "<br>";
				  $sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '1' and c.product_code = 'Afternoon Programme' group by c.id ) ab group by CAST(collection_month AS int), fee_structure_id";
				
				  $result1=mysqli_query($connection, $sql);
				  $IsRow=mysqli_num_rows($result1);
				  $row_collection=mysqli_fetch_assoc($result1)
					  ?>
				  <input class="<?php if($IsRow<1){ echo "fee_amounts";}?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["basic_adjust"]?>" readonly>
				  <input type="text" placeholder="Fee"  name="basic_collection" value="<?php echo $row["basic_collection"]?>" readonly>
				  </label>
				  <?php 

				  if($IsRow<1){
					  $amount += $row["basic_adjust"];
					  $item = $row["subject"];
				  ?>
				  <a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Afternoon Programme'?>', '<?php echo $student_code ?>', 'Afternoon Programme', '<?php echo $row["basic_adjust"]?>', '<?php echo $row["id"]?>', '1', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>

				  <?php
				  }else{
					  if($row["basic_adjust"] > $row_collection["collection"]){
						  $balance = $row["basic_adjust"] - $row_collection["collection"];
						  ?>
						  <input class="fee_amounts" type="hidden" value="<?php echo $balance?>">
						  <a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Afternoon Programme'?>', '<?php echo $student_code ?>', 'Afternoon Programme', '<?php echo $balance; ?>', '<?php echo $row["id"]?>', '1', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
					  }else {
						  echo "Paid";
					  }
				  }
				  echo "</div>";
		  }
	  }
			  
			  ?>
		  
		  
			  
				  
		  </div>
		  

		  <!---------end Annually-------------------------->


		  </td>
	  </tr>
	  
	  </table>
	  </div>
	  <?php
 
	}
// end annylly
	  ?>
	  <table>
	  <tr>
		<td>
		<input type="hidden" placeholder="Outstanding Amount" step="0.01" name="total_amount" id="total_amount" value="<?php echo $amount; ?>" readonly>
		</td>
	</tr>
	<tr>
		<td>
		<input type="hidden" placeholder="Outstanding Item" name="item_name" id="item_name" value="<?php echo $item; ?>" readonly>
		</td>
	</tr> 
	</table>
	 
</div>

 <div class="uk-width-1-3 uk-form">
      <div class="uk-text-center myheader">
         <h2 class="myheader-text-color">Outstanding Basket Content</h2>
      </div>
      <div id="lstBusketOut"></div>
      <a onclick="doPutInBusket()" class="uk-button uk-button-small uk-button-primary uk-align-right">Put in Basket</a>
      <a class="uk-button uk-button-small uk-align-right" onclick="$('#outstanding-dialog').dialog('close');">Cancel</a>
   </div>
</div>

<script>
function doSave() {
   $("#frmOutstanding").submit();
}
</script>

<!-- <?php
if ($year == date('Y')) {
?>
   <a onclick="doSave()" class="uk-button uk-button-small">Put in Basket</a>
<?php
}
?>
<a onclick="$('#outstanding-dialog').dialog('close');" class="uk-button uk-button-small">Cancel</a>
 -->

<script>
function removeFromTempBusket(bid, product_code, student_code) {
   //UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function () { //Remove Confirmation
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
               getOutstandingBusket();
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

function getOutstandingBusket() {
   var ssid='<?php echo $ssid?>';

   $.ajax({
      url : "admin/getOutstandingBusketContent.php",
      type : "POST",
      data : "ssid="+ssid,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         $("#lstBusketOut").html(response);
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

function add2TempBusket(outstanding_name, student_code, month_fees, amount, allocation_id, collection_month, collection_year, collection_pattern
) {

   var the_fee = $("input[name='the_fee[]']").map(function(){
                return $(this).val();
            }).get();
  
      $.ajax({
         url : "admin/add2TempBusket.php",
         type : "POST",
         data : "outstanding_name="+outstanding_name+"&student_code="+student_code+"&month_fees="+month_fees+"&amount="+amount+"&allocation_id="+allocation_id+"&collection_month="+collection_month+"&collection_year="+collection_year+"&collection_pattern="+collection_pattern+"&the_fee="+the_fee,
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
            getOutstandingBusket();
         },
         error : function(http, status, error) {
            UIkit.notify("Error:"+error);
         }
      });
  
}

$(document).ready(function () {
   getOutstandingBusket();   
});

function changeType(subject) {
   var course_inquiry=$("#init-"+subject+"-type").val();
   $("#"+course_inquiry+"-"+subject+"-fees").show();
   $("#"+course_inquiry+"-"+subject+"-fees").siblings().hide();
}

var total_amount=$("#total_amount").val();
var item_name=$("#item_name").val();
$('#total_amount_s').val(total_amount);	
$('#item_names').val(item_name);	

</script>
<style>
.ui-dialog.ui-corner-all.ui-widget.ui-widget-content.ui-front.q-dialog.ui-draggable.ui-resizable {
    top: 60px!important;
}
</style>
