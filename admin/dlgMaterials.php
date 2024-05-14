<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");
$student_code=$_POST["student_code"];

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);
}

$ssid=(getssid($student_code));

function getssid($student_code) {
   global $connection;

   $sql="SELECT id from student where sha1(student_code)='$student_code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["id"];
}

function getStrMonth($month) {
   switch ($month) {
      case 1 : return "Jan"; break;
      case 2 : return "Feb"; break;
      case 3 : return "Mar"; break;
      case 4 : return "Apr"; break;
      case 5 : return "May"; break;
      case 6 : return "Jun"; break;
      case 7 : return "Jul"; break;
      case 8 : return "Aug"; break;
      case 9 : return "Sep"; break;
      case 10 : return "Oct"; break;
      case 11 : return "Nov"; break;
      case 12 : return "Dec"; break;
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
               getMaterialsBusket();
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

function getMaterialsBusket() {
   var ssid='<?php echo $ssid?>';

   $.ajax({
      url : "admin/getMaterialsBusketContent.php",
      type : "POST",
      data : "ssid="+ssid,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         $("#lstBusketMater").html(response);
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

function add2TempBusketMaterial(course_name, student_code, course_inquiry, initFees, initQty, allocation_id, collection_month, collection_year, collection_pattern) {
   if (course_name!="") {
      $.ajax({
         url : "admin/add2TempBusket.php",
         type : "POST",
         data : "student_code="+student_code+"&course_name="+course_name+"&course_inquiry="+course_inquiry+"&course_val="+initFees+"&inquiry_qty="+initQty+"&collection_month="+collection_month+"&collection_year="+collection_year+"&collection_pattern="+collection_pattern+"&allocation_id="+allocation_id,
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
            getMaterialsBusket();
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
   getMaterialsBusket();   
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
		<select name="year" id="year3">
			<option value="">Select Year</option>
			<?php echo getYearOptionList(); ?>
		</select>
		<td>
	</tr>
	 	<tr style="background-color:lightgrey">
         	<td colspan="2">Outstanding From Previous Years and Current Year</td>
      	</tr>
		<tr>
        <td style="padding-top: 6px;">Outstanding Amount: <input type="number" placeholder="Outstanding Amount" step="0.01" id="total_amount_s3" value="" readonly></td>
	  </tr>
		<tr>
			<td style="padding-top: 6px;padding-bottom: 11px;">
			<span style="padding-right:14px;">Outstanding Items:</span> <input type="text" placeholder="Outstanding Item" name="item_names3" id="item_names3" value="" readonly>
			</td>
		</tr> 
	 	<script>
	$(document).ready(function(){
		var d = new Date();
		var currentYear = '<?php echo $_SESSION['Year']; ?>';
		setTimeout(function(){ 
			$("#year3").val(currentYear);
			$("#year3").change();
		}, 100);

			$("#year3").change(function(){
		
			if($(this).val()!=""){
				$(".fees").hide();
				$("."+$(this).val()).show();
				var selected_year = $(this).val();
				var t_amount_o_3 = 0;

				$("#year3 option").each(function()
				{
					var i = $(this).val();
					if(i != '') {
						var fee_amounts3 = $("."+i).find(".fee_amounts3");			
						var t_amount3 = 0;
						fee_amounts3.each(function(){
							var fee_amount3 = $(this).val();
							if(fee_amount3!=""){
								t_amount3 += parseFloat(fee_amount3);
							}
						})
						t_amount_o_3 += t_amount3;
						if(i == selected_year) {
							return false;
						}
					}
				});

				$("#total_amount_s3").val(t_amount_o_3);
			} else {
				$(".fees").hide();
				$("#total_amount_s3").val(00);
			}
		})

		$("#all_outstanding_fee3").change(function(){
			$(".feeItems").hide();
			$("."+$(this).val()).show();
		})
		var total_amount=$("#total_amount3").val();
		var item_name=$("#item_name3").val();
		$('#total_amount_s3').val(total_amount);	
		$('#item_names3').val(item_name);
	})
	</script>
	<tr style="background-color:lightgrey">
         <td colspan="2">Current Outstanding</td>
      </tr>
	<tr>
		<td style="padding-top: 6px;padding-bottom: 11px;">
		<select name="all_outstanding_fee" id="all_outstanding_fee3">
			<option value="">Select Fee</option>
			<option value="f1">Integrated Module</option>
			<option value="f2">Link & Think</option>
			<option value="f3">Mandarin Modules</option>
		</select>
		</td>
	</tr>
 </table>
	 <?php
	 $month = date('m');
	$centre_code=$_SESSION["CentreCode"];
	$year=$_SESSION['Year'];
		$todayDate = date("Y-m-d"); 
	//$sql="SELECT * from fee_structure fs inner join student st on fs.subject = st.student_entry_level and fs.programme_package = st.programme_duration where sha1(st.student_code) ='$student_code'";

//start Monthly
		$sql="SELECT ps.id, ps.student_id, fl.foundation_mandarin, s.student_code, f.integrated_adjust, f.subject, f.integrated_collection, f.link_adjust, f.link_collection, f.mandarin_m_adjust, f.mandarin_m_collection, f.extend_year, f.basic_adjust, fl.fee_id, fl.programme_date, fl.programme_date_end from student s, programme_selection ps, student_fee_list fl, fee_structure f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.id='$ssid'";
	
		$result=mysqli_query($connection, $sql);
	$num_row=mysqli_num_rows($result);
	$amount = 0;
		while ($row=mysqli_fetch_assoc($result)) {
			$programme_date = $row["programme_date"];
		$programme_date = date("m",strtotime($programme_date));

		$programme_date_end = $row["programme_date_end"];
		$month = date("m",strtotime($programme_date_end));	
	?>
		<div class="<?php echo $row["extend_year"] ?> fees">
		<?php if($row["integrated_collection"]=="Monthly" || $row["link_collection"]=="Monthly" || $row["mandarin_m_collection"]=="Monthly"){ ?>
			<h2><?php echo $row["subject"]?></h2>
			<?php	} ?>
	<table>
	<tr>
		<td>
	<?php 
		$month_array = getMonthList($row["programme_date"], $row["programme_date_end"]);
		
		foreach ($month_array as $dt) {

			$i = $dt->format("m");

			if($row["integrated_collection"]=="Monthly" || $row["link_collection"]=="Monthly" || $row["mandarin_m_collection"]=="Monthly"){
			?>
		<h3 style="margin-bottom: 0px;"><?php echo $dt->format("M Y"); ?></h3>
		<div>
			<?php } ?>
			<?php if($row["integrated_adjust"]!="" && $row["integrated_collection"]!=""){ 
				if($row["integrated_collection"]=="Monthly"){
				?>
					<div class="f1 feeItems">
				<?php 
				$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '$i' and c.product_code = 'Integrated Module' group by c.id ) ab group by collection_month, fee_structure_id";
				
						$result1=mysqli_query($connection, $sql);
						$IsRow=mysqli_num_rows($result1);
						$row_collection=mysqli_fetch_assoc($result1)
				
					?>
					Integrated Module
					<br> 
					<input class="<?php if($IsRow<1){ echo "fee_amounts3";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["integrated_adjust"]?>" readonly>
					<input type="text" placeholder="Fee"  name="integrated_collection" value="<?php echo $row["integrated_collection"]?>" readonly>
						</label>
					<?php
						
					if($IsRow<1){
					$amount += $row["integrated_adjust"];
					$item = $row["subject"];
					?>	
						<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusketMaterial('<?php echo 'Integrated Module'?>', '<?php echo $student_code ?>', 'integrated', '<?php echo $row["integrated_adjust"]?>', '1','<?php echo $row["id"]?>', '<?php echo $i; ?>', '<?php echo $row["extend_year"];?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
						<?php
					}else{
						if($row["integrated_adjust"] > $row_collection["collection"]){
							$balance = $row["integrated_adjust"] - $row_collection["collection"];
							?>
							<input class="fee_amounts3" type="hidden" value="<?php echo $balance?>">
							<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusketMaterial('<?php echo 'Integrated Module'?>', '<?php echo $student_code ?>', 'integrated', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '<?php echo $i; ?>', '<?php echo $row["extend_year"];?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
						}else {
							echo "Paid";
						}
					}
					echo "</div>";
			}
			}
			?>
		
		
			<?php if($row["link_adjust"]!="" && $row["link_collection"]!=""){
				if($row["link_collection"]=="Monthly"){
				?>
				<div class="f2 feeItems">
			<!-- <h3 style="margin-bottom: 0px;"><?php //echo getStrMonth($i);?></h3> -->
			<br>
			<?php 
			
			
				//echo "<br>";
				echo "Link & Think";
				echo "<br>";
				$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '$i' and c.product_code = 'Link & Think' group by c.id ) ab group by collection_month, fee_structure_id";
					
					$result1=mysqli_query($connection, $sql);
					$IsRow=mysqli_num_rows($result1);
					$row_collection=mysqli_fetch_assoc($result1)
						?> 
					<input class="<?php if($IsRow<1){ echo "fee_amounts3";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["link_adjust"]?>" readonly>
					<input type="text" placeholder="Fee"  name="link_collection" value="<?php echo $row["link_collection"]?>" readonly>
						</label>
					<?php
						
					if($IsRow<1){
						$amount += $row["link_adjust"];
						$item = $row["subject"];
					?>	
						
						<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusketMaterial('<?php echo 'Link %26 Think'?>', '<?php echo $student_code ?>', 'link', '<?php echo $row["link_adjust"]?>', '1','<?php echo $row["id"]?>', '<?php echo $i; ?>', '<?php echo $row["extend_year"];?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
						<?php
					}else{
						if($row["link_adjust"] > $row_collection["collection"]){
							$balance = $row["link_adjust"] - $row_collection["collection"];
							?>
							<input class="fee_amounts3" type="hidden" value="<?php echo $balance?>">
							<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusketMaterial('<?php echo 'Link %26 Think'?>', '<?php echo $student_code ?>', 'link', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '<?php echo $i; ?>', '<?php echo $row["extend_year"];?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
						}else {
							echo "Paid";
						}
					}
					echo "</div>";
			}
		}
			?>
		
			<?php 
			if($row["foundation_mandarin"]){
			if($row["mandarin_m_adjust"]!="" && $row["mandarin_m_collection"]!=""){
				if($row["mandarin_m_collection"]=="Monthly"){
				?>
				<div class="f3 feeItems">
			<!-- <h3 style="margin-bottom: 0px;">Facility Fees</h3> -->
			<br>
			<?php 
			
						echo "Mandarin Modules";
						echo "<br>";
						$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '$i' and c.product_code = 'Mandarin Modules' group by c.id ) ab group by collection_month, fee_structure_id";
						$result1=mysqli_query($connection, $sql);
						$IsRow=mysqli_num_rows($result1);
						$row_collection=mysqli_fetch_assoc($result1)
							?>
						<input class="<?php if($IsRow<1){ echo "fee_amounts3";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["mandarin_m_adjust"]?>" readonly>
						<input type="text" placeholder="Fee"  name="mandarin_m_collection" value="<?php echo $row["mandarin_m_collection"]?>" readonly>
							</label>
						<?php
						
						if($IsRow<1){
							$amount += $row["mandarin_m_adjust"];
							$item = $row["subject"];
						?>	
							<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusketMaterial('<?php echo 'Mandarin Modules'?>', '<?php echo $student_code ?>', 'mandarinmodules', '<?php echo $row["mandarin_m_adjust"]?>', '1','<?php echo $row["id"]?>', '<?php echo $i; ?>', '<?php echo $row["extend_year"];?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
							<?php
						}else{
							if($row["mandarin_m_adjust"] > $row_collection["collection"]){
								$balance = $row["mandarin_m_adjust"] - $row_collection["collection"];
								?>
								<input class="fee_amounts3" type="hidden" value="<?php echo $balance?>">
								<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusketMaterial('<?php echo 'Mandarin Modules'?>', '<?php echo $student_code ?>', 'mandarinmodules', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '<?php echo $i; ?>', '<?php echo $row["extend_year"];?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
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
//end Monthly

//start Termly
	$sql="SELECT ps.id, ps.student_id, fl.foundation_mandarin, s.student_code, f.integrated_adjust, f.subject, f.integrated_collection, f.link_adjust, f.link_collection, f.mandarin_m_adjust, f.mandarin_m_collection, f.extend_year, f.basic_adjust, fl.fee_id, fl.programme_date, fl.programme_date_end from student s, programme_selection ps, student_fee_list fl, fee_structure f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.id='$ssid'";
	//echo $sql;
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
	<?php if($row["integrated_collection"]=="Termly" || $row["link_collection"]=="Termly" || $row["mandarin_m_collection"]=="Termly"){ ?>
			<h2><?php echo $row["subject"]?></h2>
			<?php	} ?>
	<table>
	<tr>
	<td>
	
	<!---------------start Termly---------------------------------------------------------------------------------->

	<?php 
	
	$start_term = getTermFromDate(date('Y-m-d',strtotime($programme_date)));
	$term = getTermFromDate(date('Y-m-d',strtotime($programme_date_end)));
	//$term = getCurrentTerm($month);
	$term1 = (int)$term;
	$start_term1 = (int)$start_term;
	if($extend_year != $row["extend_year"]){
		$extend_year = $row["extend_year"];
		$i=0;
	}

	for ($i; $i < $term1; $i++) {
		
			if($row["integrated_collection"]=="Termly" || $row["link_collection"]=="Termly" || $row["mandarin_m_collection"]=="Termly"){
			?>
			<div>
	<label>
		<h3 style="margin-bottom: 0px;"><?php echo getStrTerm($i+1); ?></h3>
			<?php 
			}
	if($row["integrated_adjust"]!="" && $row["integrated_collection"]!=""){ 
		if($row["integrated_collection"]=="Termly"){
			echo '<div class="f1 feeItems">';
			
		$month = $i+1;
			echo "Integrated Module";
			$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '$month' and c.product_code = 'Integrated Module' group by c.id ) ab group by collection_month, fee_structure_id";
			
				$result1=mysqli_query($connection, $sql);
				$IsRow=mysqli_num_rows($result1);
				$row_collection=mysqli_fetch_assoc($result1)
				?>
				<br> 
				<input class="<?php if($IsRow<1){ echo "fee_amounts3";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["integrated_adjust"]?>" readonly>
				<input type="text" placeholder="Fee"  name="integrated_collection" value="<?php echo $row["integrated_collection"]?>" readonly>
					</label>
				<?php
					
				//echo $IsRow; 
				if($IsRow<1){
				$amount += $row["integrated_adjust"];
				$item = $row["subject"];
				?>	
					<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusketMaterial('<?php echo 'Integrated Module'?>', '<?php echo $student_code ?>', 'integrated', '<?php echo $row["integrated_adjust"]?>', '1', '<?php echo $row["id"]; ?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Termly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
				<?php
				//}
			}else{
				if($row["integrated_adjust"] > $row_collection["collection"]){
					$balance = $row["integrated_adjust"] - $row_collection["collection"];
					?>
					<input class="fee_amounts3" type="hidden" value="<?php echo $balance?>">
					<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusketMaterial('<?php echo 'Integrated Module'?>', '<?php echo $student_code ?>', 'integrated', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Termly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
				}else {
					echo "Paid";
				}
			}
			echo "</div>";
		}
	}
		if($row["link_collection"]=="Termly"){
			echo '<div class="f2 feeItems">';
			//$month = $month-1;
			$term = getTermFromDate(date('Y-m-d',strtotime($row['programme_date'])));
				
				$month = $i+1;
				
				echo "<br>";
				echo 'Link & Think';
				echo "<br>";
				$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '$month' and c.product_code = 'Link & Think' group by c.id ) ab group by collection_month, fee_structure_id";
				
				$result1=mysqli_query($connection, $sql);
				$IsRow=mysqli_num_rows($result1);
				$row_collection=mysqli_fetch_assoc($result1)
				?>
				<input class="<?php if($IsRow<1){ echo "fee_amounts3";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["link_adjust"]?>" readonly>
				<input type="text" placeholder="Fee"  name="link_collection" value="<?php echo $row["link_collection"]?>" readonly>
					</label>
				<?php
					
				//echo $IsRow; 
				if($IsRow<1){
					$amount += $row["link_adjust"];
					$item = $row["subject"];
				?>	
					<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusketMaterial('<?php echo 'Link %26 Think'?>', '<?php echo $student_code ?>', 'link', '<?php echo $row["link_adjust"]?>', '1', '<?php echo $row["id"]; ?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Termly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
				<?php
				//}
			}else{
				if($row["link_adjust"] > $row_collection["collection"]){
					$balance = $row["link_adjust"] - $row_collection["collection"];
					?>
					<input class="fee_amounts3" type="hidden" value="<?php echo $balance?>">
					<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusketMaterial('<?php echo 'Link %26 Think'?>', '<?php echo $student_code ?>', 'link', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Termly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
				}else {
					echo "Paid";
				}
			}
			echo "</div>";
			}
			if($row["foundation_mandarin"]){
		if($row["mandarin_m_collection"]=="Termly"){
			$term = getTermFromDate(date('Y-m-d',strtotime($row['programme_date'])));
			//$term = getCurrentTerm($programme_date);
			//for ($i=0; $i < $term; $i++) {
				echo '<div class="f3 feeItems">';
				$month = $i+1;
			
			echo "<br>";
			echo 'Mandarin Modules';
			echo "<br>";
			$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '$month' and c.product_code = 'Mandarin Modules' group by c.id ) ab group by collection_month, fee_structure_id";
			//$sql = "SELECT c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join fee_structure f on f.id= p.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and c.product_code = 'Mandarin Modules'";
				$result1=mysqli_query($connection, $sql);
				$IsRow=mysqli_num_rows($result1);
				$row_collection=mysqli_fetch_assoc($result1)
				?>
				<input class="<?php if($IsRow<1){ echo "fee_amounts3";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["mandarin_m_adjust"]?>" readonly>
				<input type="text" placeholder="Fee"  name="mandarin_m_collection" value="<?php echo $row["mandarin_m_collection"]?>" readonly>
					</label>
				<?php
			
				//echo $IsRow; 
				if($IsRow<1){
					$amount += $row["mandarin_m_adjust"];
					$item = $row["subject"];
				?>	
					<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusketMaterial('<?php echo 'Mandarin Modules'?>', '<?php echo $student_code ?>', 'mandarinmodules', '<?php echo $row["mandarin_m_adjust"]?>', '1', '<?php echo $row["id"]; ?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Termly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
				<?php
				//}
			}else{
				if($row["mandarin_m_adjust"] > $row_collection["collection"]){
					$balance = $row["mandarin_m_adjust"] - $row_collection["collection"];
					?>
					<input class="fee_amounts3" type="hidden" value="<?php echo $balance?>">
					<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusketMaterial('<?php echo 'Mandarin Modules'?>', '<?php echo $student_code ?>', 'mandarinmodules', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Termly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
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
	<!---------end termly-------------------------->
		
	</td>
	</tr>

	</table>
	</div>
	<?php

	}
//end Termly

//start Half yearly
	$sql="SELECT ps.id, ps.student_id, fl.foundation_mandarin, s.student_code, f.integrated_adjust, f.subject, f.integrated_collection, f.link_adjust, f.link_collection, f.mandarin_m_adjust, f.mandarin_m_collection, f.extend_year, f.basic_adjust, fl.fee_id, fl.programme_date, fl.programme_date_end from student s, programme_selection ps, student_fee_list fl, fee_structure f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.id='$ssid'";
	//echo $sql;
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
	<?php if($row["integrated_collection"]=="Half Year" || $row["link_collection"]=="Half Year" || $row["mandarin_m_collection"]=="Half Year"){ ?>
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
		if($row["integrated_collection"]=="Half Year" || $row["link_collection"]=="Half Year" || $row["mandarin_m_collection"]=="Half Year"){
			?>
			<div>
	<label>
		<h3 style="margin-bottom: 0px;"><?php echo getStrHalfYearly($i+1); ?></h3>
			<?php 
			}
	if($row["integrated_adjust"]!="" && $row["integrated_collection"]!=""){ 
		if($row["integrated_collection"]=="Half Year"){
			echo '<div class="f1 feeItems">';
			// for ($i=0; $i < $HearfYearly; $i++) { 
				$month = $i+1;
			echo "<br>";
			echo 'Integrated Module';
			$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '$month' and c.product_code = 'Integrated Module' group by c.id ) ab group by collection_month, fee_structure_id";
			//$sql = "SELECT c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join fee_structure f on f.id= p.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and c.product_code = 'Integrated Module'";
				$result1=mysqli_query($connection, $sql);
				$IsRow=mysqli_num_rows($result1);
				$row_collection=mysqli_fetch_assoc($result1)
			?>
			<br> 
				<input class="<?php if($IsRow<1){ echo "fee_amounts3";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["integrated_adjust"]?>" readonly>
				<input type="text" placeholder="Fee"  name="integrated_collection" value="<?php echo $row["integrated_collection"]?>" readonly>
					</label>
				<?php
					
				if($IsRow<1){
				$amount += $row["integrated_adjust"];
				$item = $row["subject"];
				?>	
					<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusketMaterial('<?php echo 'Integrated Module'?>', '<?php echo $student_code ?>', 'integrated', '<?php echo $row["integrated_adjust"]?>', '1', '<?php echo $row["id"]; ?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Half Year')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
								
				<?php
				//}
			}else{
				if($row["integrated_adjust"] > $row_collection["collection"]){
					$balance = $row["integrated_adjust"] - $row_collection["collection"];
					?>
					<input class="fee_amounts3" type="hidden" value="<?php echo $balance?>">
					<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusketMaterial('<?php echo 'Integrated Module'?>', '<?php echo $student_code ?>', 'integrated', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Half Year')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
				}else {
					echo "Paid";
				}
			}
			echo "</div>";
		}
	}
	if($row["link_collection"]=="Half Year"){
		echo '<div class="f2 feeItems">';
		$month = $i+1;
			echo "<br>";
			echo 'Link & Think';
			echo "<br>";
			$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '$month' and c.product_code = 'Link & Think' group by c.id ) ab group by collection_month, fee_structure_id";
			//$sql = "SELECT c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join fee_structure f on f.id= p.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and c.product_code = 'Link & Think'";
				$result1=mysqli_query($connection, $sql);
				$IsRow=mysqli_num_rows($result1);
				$row_collection=mysqli_fetch_assoc($result1)
			?>
				<input class="<?php if($IsRow<1){ echo "fee_amounts3";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["link_adjust"]?>" readonly>
				<input type="text" placeholder="Fee"  name="link_collection" value="<?php echo $row["link_collection"]?>" readonly>
					</label>
				<?php
					
				if($IsRow<1){
					$amount += $row["link_adjust"];
					$item = $row["subject"];
				?>	
					<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusketMaterial('<?php echo 'Link %26 Think'?>', '<?php echo $student_code ?>', 'link', '<?php echo $row["link_adjust"]?>', '1', '<?php echo $row["id"]; ?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Half Year')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
								
				<?php
				//}
			}else{
				if($row["link_adjust"] > $row_collection["collection"]){
					$balance = $row["link_adjust"] - $row_collection["collection"];
					?>
					<input class="fee_amounts3" type="hidden" value="<?php echo $balance?>">
					<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusketMaterial('<?php echo 'Link %26 Think'?>', '<?php echo $student_code ?>', 'link', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Half Year')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
				}else {
					echo "Paid";
				}
			}
			echo "</div>";
	}
	if($row["foundation_mandarin"]){
	if($row["mandarin_m_collection"]=="Half Year"){
		echo '<div class="f3 feeItems">';
		$month = $i+1;
			echo "<br>";
			echo 'Mandarin Modules';
			echo "<br>";
			$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '$month' and c.product_code = 'Mandarin Modules' group by c.id ) ab group by collection_month, fee_structure_id";
			//$sql = "SELECT c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join fee_structure f on f.id= p.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and c.product_code = 'Mandarin Modules'";
			$result1=mysqli_query($connection, $sql);
			$IsRow=mysqli_num_rows($result1);
			$row_collection=mysqli_fetch_assoc($result1)
		?>
			<input class="<?php if($IsRow<1){ echo "fee_amounts3";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["mandarin_m_adjust"]?>" readonly>
			<input type="text" placeholder="Fee"  name="mandarin_m_collection" value="<?php echo $row["mandarin_m_collection"]?>" readonly>
				</label>
			<?php
				
			if($IsRow<1){
				$amount += $row["mandarin_m_adjust"];
				$item = $row["subject"];
			?>	
				<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusketMaterial('<?php echo 'Mandarin Modules'?>', '<?php echo $student_code ?>', 'mandarinmodules', '<?php echo $row["mandarin_m_adjust"]?>', '1', '<?php echo $row["id"]; ?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Half Year')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
							
			<?php
			//}
		}else{
			if($row["mandarin_m_adjust"] > $row_collection["collection"]){
				$balance = $row["mandarin_m_adjust"] - $row_collection["collection"];
				?>
				<input class="fee_amounts3" type="hidden" value="<?php echo $balance?>">
				<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusketMaterial('<?php echo 'Mandarin Modules'?>', '<?php echo $student_code ?>', 'mandarinmodules', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Half Year')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
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
//end Half yearly

//start Annually
	$sql="SELECT ps.id, ps.student_id, fl.foundation_mandarin, s.student_code, f.integrated_adjust, f.subject, f.integrated_collection, f.link_adjust, f.link_collection, f.mandarin_m_adjust, f.mandarin_m_collection, f.extend_year, f.basic_adjust, fl.fee_id, fl.programme_date, fl.programme_date_end from student s, programme_selection ps, student_fee_list fl, fee_structure f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.id='$ssid' group by ps.id";
	//echo $sql;
	$result=mysqli_query($connection, $sql);
	$num_row=mysqli_num_rows($result);
	$amount = 0;
	while ($row=mysqli_fetch_assoc($result)) {
		$programme_date = $row["programme_date"];
	$programme_date = date("m",strtotime($programme_date));

	$programme_date_end = $row["programme_date_end"];
	$month = date("m",strtotime($programme_date_end));	
	?>
	<div class="<?php echo $row["extend_year"] ?> fees">
		<?php if($row["integrated_collection"]=="Annually" || $row["link_collection"]=="Annually" || $row["mandarin_m_collection"]=="Annually"){ ?>
			<h2><?php echo $row["subject"]?></h2>
			<?php	} ?>
	<table>
	<tr>
	<td>
	
	<!---------------start Annually-------------------------------------->


	<?php 
	if($row["integrated_collection"]=="Annually" || $row["link_collection"]=="Annually" || $row["mandarin_m_collection"]=="Annually"){
		?>
		<div>
	<label>
	<h3 style="margin-bottom: 0px;">Annually</h3>
		<?php 
		}
	if($row["integrated_adjust"]!="" && $row["integrated_collection"]!=""){ 
	if($row["integrated_collection"]=="Annually"){
		echo '<div class="f1 feeItems">';
		echo "<br>";
		echo 'Integrated Module';
		echo "<br>";
		$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '$i' and c.product_code = 'Integrated Module' group by c.id ) ab group by collection_month, fee_structure_id";
		//$sql = "SELECT c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join fee_structure f on f.id= p.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and c.product_code = 'Integrated Module'";
		$result1=mysqli_query($connection, $sql);
		$IsRow=mysqli_num_rows($result1);
		$row_collection=mysqli_fetch_assoc($result1)
	?>
		<input class="<?php if($IsRow<1){ echo "fee_amounts3";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["integrated_adjust"]?>" readonly>
		<input type="text" placeholder="Fee"  name="integrated_collection" value="<?php echo $row["integrated_collection"]?>" readonly>
			</label>
		<?php
			
		if($IsRow<1){
		$amount += $row["integrated_adjust"];
		$item = $row["subject"];
		?>	
			<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusketMaterial('<?php echo 'Integrated Module'?>', '<?php echo $student_code ?>', 'integrated', '<?php echo $row["integrated_adjust"]?>', '1', '<?php echo $row["id"]; ?>', '<?php echo $i; ?>', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
			
		<?php
		}else{
			if($row["integrated_adjust"] > $row_collection["collection"]){
				$balance = $row["integrated_adjust"] - $row_collection["collection"];
				?>
				<input class="fee_amounts3" type="hidden" value="<?php echo $balance?>">
				<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusketMaterial('<?php echo 'Integrated Module'?>', '<?php echo $student_code ?>', 'integrated', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '<?php echo $i; ?>', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
			}else {
				echo "Paid";
			}
		}
		echo "</div>";
	}
	}
	if($row["link_collection"]=="Annually"){
	echo '<div class="f2 feeItems">';
		echo "<br>";
		echo 'Link & Think';
		echo "<br>";
		$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '$i' and c.product_code = 'Link & Think' group by c.id ) ab group by collection_month, fee_structure_id";
		//$sql = "SELECT c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join fee_structure f on f.id= p.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]'";
			$result1=mysqli_query($connection, $sql);
			$IsRow=mysqli_num_rows($result1);
			$row_collection=mysqli_fetch_assoc($result1)
		?>
			<input class="<?php if($IsRow<1){ echo "fee_amounts3";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["link_adjust"]?>" readonly>
			<input type="text" placeholder="Fee"  name="link_collection" value="<?php echo $row["link_collection"]?>" readonly>
				</label>
			<?php
			
			if($IsRow<1){
				$amount += $row["link_adjust"];
				$item = $row["subject"];
			?>	
				<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusketMaterial('<?php echo 'Link %26 Think'?>', '<?php echo $student_code ?>', 'link', '<?php echo $row["link_adjust"]?>', '1', '<?php echo $row["id"]; ?>', '<?php echo $i; ?>', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
				
			<?php
			}else{
				if($row["link_adjust"] > $row_collection["collection"]){
					$balance = $row["link_adjust"] - $row_collection["collection"];
					?>
					<input class="fee_amounts3" type="hidden" value="<?php echo $balance?>">
					<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusketMaterial('<?php echo 'Link %26 Think'?>', '<?php echo $student_code ?>', 'link', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '<?php echo $i; ?>', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
				}else {
					echo "Paid";
				}
		}
		echo "</div>";
	}
	if($row["foundation_mandarin"]){
	if($row["mandarin_m_collection"]=="Annually"){
	echo '<div class="f3 feeItems">';
		echo "<br>";
		echo 'Mandarin Modules';
		echo "<br>";
		$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '$i' and c.product_code = 'Mandarin Modules' group by c.id ) ab group by collection_month, fee_structure_id";
		//$sql = "SELECT c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join fee_structure f on f.id= p.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]'";
		$result1=mysqli_query($connection, $sql);
		$IsRow=mysqli_num_rows($result1);
		$row_collection=mysqli_fetch_assoc($result1)
	?>
		<input class="<?php if($IsRow<1){ echo "fee_amounts3";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["mandarin_m_adjust"]?>" readonly>
		<input type="text" placeholder="Fee"  name="mandarin_m_collection" value="<?php echo $row["mandarin_m_collection"]?>" readonly>
			</label>
		<?php
			
		if($IsRow<1){
			$amount += $row["mandarin_m_adjust"];
			$item = $row["subject"];
		?>	
			<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusketMaterial('<?php echo 'Mandarin Modules'?>', '<?php echo $student_code ?>', 'mandarinmodules', '<?php echo $row["mandarin_m_adjust"]?>', '1', '<?php echo $row["id"]; ?>', '<?php echo $i; ?>', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
			
		<?php
		}else{
			if($row["mandarin_m_adjust"] > $row_collection["collection"]){
				$balance = $row["mandarin_m_adjust"] - $row_collection["collection"];
				?>
				<input class="fee_amounts3" type="hidden" value="<?php echo $balance?>">
				<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusketMaterial('<?php echo 'Mandarin Modules'?>', '<?php echo $student_code ?>', 'mandarinmodules', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '<?php echo $i; ?>', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
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
//end Annually

	  ?>
	  <table>
	  <tr>
		<td>
		<input type="hidden" placeholder="Outstanding Amount" step="0.01" name="total_amount3" id="total_amount3" value="<?php echo $amount; ?>" readonly>
		</td>
	</tr>
	<tr>
		<td>
		<input type="hidden" placeholder="Outstanding Item" name="item_name3" id="item_name3" value="<?php echo $item; ?>" readonly>
		</td>
	</tr> 
	</table>
   </div>
   <div class="uk-width-1-3 uk-form">
      <div class="uk-text-center myheader">
         <h2 class="myheader-text-color">Materials Basket Content</h2>
      </div>
      <div id="lstBusketMater"></div>
      <a onclick="doPutInBusket()" class="uk-button uk-button-small uk-button-primary uk-align-right">Put in Basket</a>
      <a class="uk-button uk-button-small uk-align-right" onclick="$('#materials-dialog').dialog('close');">Cancel</a>
   </div>
</div>