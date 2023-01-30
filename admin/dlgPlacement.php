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
   UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function () {
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
               getTempBusket();
            }
            if (s[0]=="0") {
               UIkit.notify(s[1]);
            }
         },
         error : function(http, status, error) {
            UIkit.notify("Error:"+error);
         }
      });
   })
}

function getTempBusket() {

   var ssid='<?php echo $ssid?>';

   $.ajax({

      url : "admin/getCourseTempBusketContent.php",
      type : "POST",
      data : "ssid="+ssid,
      dataType : "text",
      beforeSend : function(http) {

      },

      success : function(response, status, http) {
         $("#lstBusketPlacement").html(response);
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

function add2TempBusket(course_name, student_code, course_inquiry, placement_val, placement_qty, allocation_id, collection_month, collection_year, collection_pattern) {

   	if (course_name!="") {

      	$.ajax({
	        url : "admin/add2TempBusket.php",
    	    type : "POST",
        	data : "student_code="+student_code+"&course_name="+course_name+"&course_inquiry="+course_inquiry+"&course_val="+placement_val+"&inquiry_qty="+placement_qty+"&collection_month="+collection_month+"&collection_year="+collection_year+"&collection_pattern="+collection_pattern+"&allocation_id="+allocation_id,
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
            	getTempBusket();
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

   getTempBusket();   

});

</script>

<div class="uk-grid">

   <div class="uk-width-1-2 uk-form">
   <table class="uk-table uk-table-small uk-form uk-form-small">
   <tr style="background-color:lightgrey">
         <td colspan="2">Year Selection</td>
      </tr>
	<tr>
		<td style="padding-top: 6px;">
		<select name="year" id="year2">
			<option value="">Select Year</option>
			<?php echo getYearOptionList(); ?>
		</select>
		<td>
	</tr>
	 <tr style="background-color:lightgrey">
         <td colspan="2">Outstanding From Previous Years and Current Year</td>
      </tr>
	<tr>
        <td style="padding-top: 6px;">Outstanding Amount: <input type="number" placeholder="Outstanding Amount" step="0.01" id="total_amount_s2" value="" readonly></td>
	  </tr>
		<tr>
			<td style="padding-top: 6px;padding-bottom: 11px;">
			<span style="padding-right:14px;">Outstanding Items:</span> <input type="text" placeholder="Outstanding Item" name="item_names" id="item_names2" value="" readonly>
			</td>
		</tr> 
<script>
	$(document).ready(function(){
		
		var d = new Date();
		var currentYear = '<?php echo $_SESSION['Year']; ?>';
		
		setTimeout(function(){ 
			$("#year2").val(currentYear);
			$("#year2").change();
		}, 100);

			$("#year2").change(function(){
		
				if($(this).val()!="") {
					$(".fees").hide();
					$("."+$(this).val()).show();
					var selected_year = $(this).val();
					var t_amount_o_4 = 0;

					$("#year2 option").each(function()
					{
						var i = $(this).val();
						if(i != '') {
							var fee_amounts4 = $("."+i).find(".fee_amounts4");
							var t_amount4 = 0;
							fee_amounts4.each(function(){
								var fee_amount4 = $(this).val();
								if(fee_amount4!=""){
									t_amount4 += parseFloat(fee_amount4);
								}
							})
							t_amount_o_4 += t_amount4;
							if(i == selected_year) {
								return false;
							}
						}
					});

					$("#total_amount_s2").val(t_amount_o_4);
				} else {
					$(".fees").hide();
					$("#total_amount_s2").val(0);
				}
			})
		
		$("#all_outstanding_fee2").change(function(){
			if($(this).val()!=""){
			$(".feeItems").hide();
			$("."+$(this).val()).show();
			}else{
				$(".feeItems").show();
				//$("#total_amount_s").val($("#total_amount").val());
			}
		})
		var total_amount=$("#total_amount2").val();
		var item_name=$("#item_name2").val();
		$('#total_amount_s2').val(total_amount);	
		$('#item_names2').val(item_name);
	})
</script>
	<tr style="background-color:lightgrey">
         <td colspan="2">Current Outstanding</td>
      </tr>
	<tr>
		<td style="padding-top: 6px;padding-bottom: 11px;">
		<select name="all_outstanding_fee" id="all_outstanding_fee2">
			<option value="">Select Fee</option>
			<option value="f1">IQ Math</option>
			<option value="f2">Enhanced Foundation Mandarin</option>
			<option value="f3">International Art</option>
			<option value="f4">International English</option>
		</select>
		</td>
	</tr>
 </table>
    <?php
		$month = date('m');
		$centre_code=$_SESSION["CentreCode"];
		$year=$_SESSION['Year'];

		//start Monthly	 
		$sql="SELECT ps.id, ps.student_id, s.student_code, f.iq_math_adjust, ps.student_entry_level, ps.enhanced_foundation, f.iq_math_collection, f.mandarin_adjust, f.mandarin_collection, f.international_adjust, f.enhanced_adjust, f.robotic_plus_adjust, f.enhanced_collection, f.international_collection, f.robotic_plus_collection, f.extend_year, f.basic_adjust, f.islam_adjust, fl.fee_id, fl.programme_date, fl.programme_date_end, fl.foundation_int_english, fl.foundation_iq_math, fl.foundation_int_mandarin, fl.foundation_int_art, fl.pendidikan_islam, fl.islam_collection, fl.robotic_plus from student s, programme_selection ps, student_fee_list fl, fee_structure f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.id='$ssid'";
		
		$result=mysqli_query($connection, $sql);
		$num_row=mysqli_num_rows($result);
		$amount = 0;
		while ($row=mysqli_fetch_assoc($result)) {
			$programme_date = $row["programme_date"];
			$programme_date = date("m",strtotime($programme_date));

			$programme_date_end = $row["programme_date_end"];
			$month = date("m",strtotime($programme_date_end));
			$enhanced_foundation = unserialize($row["enhanced_foundation"]);
	?>
		<div class="<?php echo $row["extend_year"] ?> fees">
			<?php if(($row["iq_math_collection"]=="Monthly" && $row["foundation_iq_math"]) || ($row["mandarin_collection"]=="Monthly" && $row["foundation_int_mandarin"]) || ($row["international_collection"]=="Monthly" && $row["foundation_int_art"]) || ($row["enhanced_collection"]=="Monthly" && $row["foundation_int_english"] && $row["enhanced_adjust"]!="" ) || ($row["robotic_plus_collection"]=="Monthly" && $row["robotic_plus"]) || ($row["islam_collection"]=="Monthly" && $row["pendidikan_islam"])){ ?>
			<h2><?php echo $row["student_entry_level"]?></h2>
			<?php } ?>
	<table>
	<tr>
		<td>
		<?php 
			$month_array = getMonthList($row["programme_date"], $row["programme_date_end"]);
			foreach ($month_array as $dt) {

				$i = $dt->format("m");

				if(($row["iq_math_collection"]=="Monthly" && $row["foundation_iq_math"]) || ($row["mandarin_collection"]=="Monthly" && $row["foundation_int_mandarin"])  || ($row["islam_collection"]=="Monthly" && $row["pendidikan_islam"]) || ($row["robotic_plus_collection"]=="Monthly" && $row["robotic_plus"]) || ($row["international_collection"]=="Monthly" && $row["foundation_int_art"]) || ($row["enhanced_collection"]=="Monthly" && $row["foundation_int_english"] && $row["enhanced_adjust"]!="" )){
			?>
				<h3 style="margin-bottom: 0px;"><?php echo $dt->format("M Y"); ?></h3>
		<div>
			<?php } ?>
			<?php 
			if($row["iq_math_adjust"]!="" && $row["iq_math_collection"]!=""){ 
				if ($row["foundation_iq_math"]){
				if($row["iq_math_collection"]=="Monthly"){
				?>
				<div class="f1 feeItems">
				<?php
					$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = $i and c.product_code = 'IQ Math' group by c.id ) ab group by collection_month, fee_structure_id";
					$result1=mysqli_query($connection, $sql);
					$IsRow=mysqli_num_rows($result1);
					$row_collection=mysqli_fetch_assoc($result1);
				?>
					IQ Math
					<br> 
					<input class="<?php if($IsRow<1){ echo "fee_amounts4";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["iq_math_adjust"]?>" id="" readonly>
					<input type="text" placeholder="Fee"  name="iq_math_collection" value="<?php echo $row["iq_math_collection"]?>" readonly>
				</label>
				<?php
					if($IsRow<1){
						$amount += $row["iq_math_adjust"];
						$item = $row["student_entry_level"];
						?>
						<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'IQ Math'?>', '<?php echo $student_code ?>', 'math', '<?php echo $row["iq_math_adjust"]?>', '1','<?php echo $row["id"]?>', '<?php echo $i; ?>', '<?php echo $row["extend_year"];?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
						<?php
					}else{
						if($row["iq_math_adjust"] > $row_collection["collection"]){
							$balance = $row["iq_math_adjust"] - $row_collection["collection"];
							?>
							<input class="fee_amounts4" type="hidden" value="<?php echo $balance?>">
							<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'IQ Math'?>', '<?php echo $student_code ?>', 'math', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '<?php echo $i; ?>', '<?php echo $row["extend_year"];?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
						}else {
							echo "Paid";
						}
					}
					echo "</div>";
			}
		}
			}
			?>
		
		
			<?php if($row["mandarin_adjust"]!="" && $row["mandarin_collection"]!=""){
				if ($row["foundation_int_mandarin"]){
				if($row["mandarin_collection"]=="Monthly"){
				?>
				<div class="f2 feeItems">
			<br>
			<?php 
				echo "Enhanced Foundation Mandarin";
				echo "<br>";
				$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = $i and c.product_code = 'Enhanced Foundation Mandarin' group by c.id ) ab group by collection_month, fee_structure_id";
			
				$result1=mysqli_query($connection, $sql);
				$IsRow=mysqli_num_rows($result1);
				$row_collection=mysqli_fetch_assoc($result1)
			?>
			<input class="<?php if($IsRow<1){ echo "fee_amounts4";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["mandarin_adjust"]?>" readonly>
				<input type="text" placeholder="Fee"  name="mandarin_collection" value="<?php echo $row["mandarin_collection"]?>" readonly>
					</label>
				<?php 
					
				if($IsRow<1){
					$amount += $row["mandarin_adjust"];
					$item = $row["student_entry_level"];
				?>	
					<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Enhanced Foundation Mandarin'?>', '<?php echo $student_code ?>', 'Enhanced Foundation Mandarin', '<?php echo $row["mandarin_adjust"]?>', '1','<?php echo $row["id"]?>', '<?php echo $i; ?>', '<?php echo $row["extend_year"];?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
					<?php
				}else{
					if($row["mandarin_adjust"] > $row_collection["collection"]){
						$balance = $row["mandarin_adjust"] - $row_collection["collection"];
						?>
						<input class="fee_amounts4" type="hidden" value="<?php echo $balance?>">
						<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Enhanced Foundation Mandarin'?>', '<?php echo $student_code ?>', 'Enhanced Foundation Mandarin', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '<?php echo $i; ?>', '<?php echo $row["extend_year"];?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
					}else {
						echo "Paid";
					}
				}
				echo "</div>";
			}
		}
		}
			?>
		
			<?php if($row["international_adjust"]!="" && $row["international_collection"]!=""){
				if ($row["foundation_int_art"]){
				if($row["international_collection"]=="Monthly"){
				?>
				<div class="f3 feeItems">
			<br>
			<?php 
				echo "International Art";
				echo "<br>";
				$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = $i and c.product_code = 'International Art' group by c.id ) ab group by collection_month, fee_structure_id";
		
				$result1=mysqli_query($connection, $sql);
				$IsRow=mysqli_num_rows($result1);
				$row_collection=mysqli_fetch_assoc($result1)
					?>
				<input class="<?php if($IsRow<1){ echo "fee_amounts4";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["international_adjust"]?>" readonly>
				<input type="text" placeholder="Fee"  name="international_collection" value="<?php echo $row["international_collection"]?>" readonly>
					</label>
					<?php
						
				if($IsRow<1){
				$amount += $row["international_adjust"];
				$item = $row["student_entry_level"];
					?>
					<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'International Art'?>', '<?php echo $student_code ?>', 'international', '<?php echo $row["international_adjust"]?>', '1','<?php echo $row["id"]?>', '<?php echo $i; ?>', '<?php echo $row["extend_year"];?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
					<?php
				}else{
					if($row["international_adjust"] > $row_collection["collection"]){
						$balance = $row["international_adjust"] - $row_collection["collection"];
						?>
						<input class="fee_amounts4" type="hidden" value="<?php echo $balance?>">
						<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'International Art'?>', '<?php echo $student_code ?>', 'international', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '<?php echo $i; ?>', '<?php echo $row["extend_year"];?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
					}else {
						echo "Paid";
					}
				}
				echo "</div>";
			}
		}
		}
			?>
		
			<?php if($row["enhanced_adjust"]!="" && $row["enhanced_collection"]!=""){
				if ($row["foundation_int_english"]){
				if($row["enhanced_collection"]=="Monthly"){
				?>
				<div class="f4 feeItems">
			<!-- <h3 style="margin-bottom: 0px;">Afternoon Programme</h3>  -->
			<br>
			<?php 
				echo "International English";
				echo "<br>";
				$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = $i and c.product_code = 'International English' group by c.id ) ab group by collection_month, fee_structure_id";
				
				$result1=mysqli_query($connection, $sql);
				$IsRow=mysqli_num_rows($result1);
				$row_collection=mysqli_fetch_assoc($result1)
					?>
				<input class="<?php if($IsRow<1){ echo "fee_amounts4";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["enhanced_adjust"]?>" readonly>
				<input type="text" placeholder="Fee"  name="enhanced_collection" value="<?php echo $row["enhanced_collection"]?>" readonly>
					</label>
				<?php
				
				if($IsRow<1){
				$amount += $row["enhanced_adjust"];
				$item = $row["student_entry_level"];
				?>	
				<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'International English'?>', '<?php echo $student_code ?>', 'foundation', '<?php echo $row["enhanced_adjust"]?>', '1','<?php echo $row["id"]?>', '<?php echo $i; ?>', '<?php echo $row["extend_year"];?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
					<?php
				}else{
					if($row["enhanced_adjust"] > $row_collection["collection"]){
						$balance = $row["enhanced_adjust"] - $row_collection["collection"];
						?>
						<input class="fee_amounts4" type="hidden" value="<?php echo $balance?>">
						<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'International English'?>', '<?php echo $student_code ?>', 'foundation', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '<?php echo $i; ?>', '<?php echo $row["extend_year"];?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
					}else {
						echo "Paid";
					}
				}
				echo "</div>";
			}
		}
		}
			?>
			<?php 
			if($row["robotic_plus_adjust"]!="" && $row["robotic_plus_collection"]!=""){ 
				if ($row["robotic_plus"]){
				if($row["robotic_plus_collection"]=="Monthly"){
				?>
				<div class="f5 feeItems">
				<?php
					$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = $i and c.product_code = 'Robotic Plus' group by c.id ) ab group by collection_month, fee_structure_id";
					$result1=mysqli_query($connection, $sql);
					$IsRow=mysqli_num_rows($result1);
					$row_collection=mysqli_fetch_assoc($result1);
				?>
					<br>
					Robotic Plus
					<br> 
					<input class="<?php if($IsRow<1){ echo "fee_amounts4";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["robotic_plus_adjust"]?>" id="" readonly>
					<input type="text" placeholder="Fee"  name="robotic_plus_collection" value="<?php echo $row["robotic_plus_collection"]?>" readonly>
				</label>
				<?php
					if($IsRow<1){
						$amount += $row["robotic_plus_adjust"];
						$item = $row["student_entry_level"];
						?>
						<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Robotic Plus'?>', '<?php echo $student_code ?>', 'robotic-plus', '<?php echo $row["robotic_plus_adjust"]; ?>', '1','<?php echo $row["id"]?>', '<?php echo $i; ?>', '<?php echo $row["extend_year"];?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
						<?php
					}else{
						if($row["robotic_plus_adjust"] > $row_collection["collection"]){
							$balance = $row["robotic_plus_adjust"] - $row_collection["collection"];
							?>
							<input class="fee_amounts4" type="hidden" value="<?php echo $balance?>">
							<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Robotic Plus'?>', '<?php echo $student_code ?>', 'robotic-plus', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '<?php echo $i; ?>', '<?php echo $row["extend_year"];?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
						}else {
							echo "Paid";
						}
					}
					echo "</div>";
			}
		}
			}

			if($row["islam_adjust"]!="" && $row["islam_collection"]!=""){ 
				if ($row["pendidikan_islam"]){
				if($row["islam_collection"]=="Monthly"){
				?>
				<div class="f6 feeItems">
				<?php
					$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = $i and c.product_code = 'Pendidikan Islam' group by c.id ) ab group by collection_month, fee_structure_id";
					$result1=mysqli_query($connection, $sql);
					$IsRow=mysqli_num_rows($result1);
					$row_collection=mysqli_fetch_assoc($result1);
				?>
					Pendidikan Islam/Jawi
					<br> 
					<input class="<?php if($IsRow<1){ echo "fee_amounts4";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["islam_adjust"]?>" id="" readonly>
					<input type="text" placeholder="Fee"  name="islam_collection" value="<?php echo $row["islam_collection"]?>" readonly>
				</label>
				<?php
					if($IsRow<1){
						$amount += $row["islam_adjust"];
						$item = $row["student_entry_level"];
						?>
						<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Pendidikan Islam'?>', '<?php echo $student_code ?>', 'pendidikan', '<?php echo $row["islam_adjust"]?>', '1','<?php echo $row["id"]?>', '<?php echo $i; ?>', '<?php echo $row["extend_year"];?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
						<?php
					}else{
						if($row["islam_adjust"] > $row_collection["collection"]){
							$balance = $row["islam_adjust"] - $row_collection["collection"];
							?>
							<input class="fee_amounts4" type="hidden" value="<?php echo $balance?>">
							<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Pendidikan Islam'?>', '<?php echo $student_code ?>', 'pendidikan', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '<?php echo $i; ?>', '<?php echo $row["extend_year"];?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
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
	$sql="SELECT ps.id, ps.student_id, s.student_code, f.iq_math_adjust, ps.student_entry_level, ps.enhanced_foundation, f.iq_math_collection, f.mandarin_adjust, f.mandarin_collection, f.international_adjust, f.enhanced_adjust, f.robotic_plus_adjust, f.enhanced_collection, f.international_collection, f.robotic_plus_collection, f.extend_year, f.basic_adjust, f.islam_adjust, fl.fee_id, fl.programme_date, fl.programme_date_end, fl.foundation_int_english, fl.foundation_iq_math, fl.foundation_int_mandarin, fl.foundation_int_art, fl.robotic_plus, fl.pendidikan_islam, fl.islam_collection from student s, programme_selection ps, student_fee_list fl, fee_structure f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.id='$ssid'";
	
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
	<?php if(($row["iq_math_collection"]=="Termly" && $row["foundation_iq_math"]) || ($row["robotic_plus_collection"]=="Termly" && $row["robotic_plus"]) || ($row["mandarin_collection"]=="Termly" && $row["foundation_int_mandarin"]) || ($row["international_collection"]=="Termly" && $row["foundation_int_art"]) || ($row["enhanced_collection"]=="Termly" && $row["foundation_int_english"] && $row["enhanced_adjust"]!="" ) || ($row["islam_collection"]=="Termly" && $row["pendidikan_islam"])){ ?>
			<h2><?php echo $row["student_entry_level"]?></h2>
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
	for ($i; $i < $term1; $i++) {
		
		if(($row["iq_math_collection"]=="Termly" && $row["foundation_iq_math"]) || ($row["robotic_plus_collection"]=="Termly" && $row["robotic_plus"]) || ($row["mandarin_collection"]=="Termly" && $row["foundation_int_mandarin"]) || ($row["international_collection"]=="Termly" && $row["foundation_int_art"]) || ($row["islam_collection"]=="Termly" && $row["pendidikan_islam"]) || ($row["enhanced_collection"]=="Termly" && $row["foundation_int_english"] && $row["enhanced_adjust"]!="" )){
			
			?>
			
		<h3 style="margin-bottom: 0px;"><?php echo getStrTerm($i+1); ?></h3>
			<?php 
			}
	if($row["iq_math_adjust"]!="" && $row["iq_math_collection"]!=""){ 
		if ($row["foundation_iq_math"]){
		if($row["iq_math_collection"]=="Termly"){
			echo '<div class="f1 feeItems">';
			
			$month = $i+1;
			echo "IQ Math";
			$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '$month' and c.product_code = 'IQ Math' group by c.id ) ab group by collection_month, fee_structure_id";
			
			$result1=mysqli_query($connection, $sql);
			$IsRow=mysqli_num_rows($result1);
			$row_collection=mysqli_fetch_assoc($result1)
			?>
			<br> 
			<input class="<?php if($IsRow<1){ echo "fee_amounts4";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["iq_math_adjust"]?>" id="" readonly>
			<input type="text" placeholder="Fee"  name="iq_math_collection" value="<?php echo $row["iq_math_collection"]?>" readonly>
			
			<?php 
			if($IsRow<1){
				$amount += $row["iq_math_adjust"];
				$item = $row["student_entry_level"];
			?>	
				<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'IQ Math'?>', '<?php echo $student_code ?>', 'math', '<?php echo $row["iq_math_adjust"]?>', '1', '<?php echo $row["id"]; ?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Termly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
			<?php
		}else{
			if($row["iq_math_adjust"] > $row_collection["collection"]){
				$balance = $row["iq_math_adjust"] - $row_collection["collection"];
				?>
				<input class="fee_amounts4" type="hidden" value="<?php echo $balance?>">
				<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'IQ Math'?>', '<?php echo $student_code ?>', 'math', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Termly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
			}else {
				echo "Paid";
			}
		}
		echo "</div>";
		}
	}
	}
	if ($row["foundation_int_mandarin"]){
		if($row["mandarin_collection"]=="Termly"){
			echo '<div class="f2 feeItems">';

			$term = getTermFromDate(date('Y-m-d',strtotime($row['programme_date'])));
		
					$month = $i+1;
				
				echo "<br>";
				echo 'Enhanced Foundation Mandarin';
				echo "<br>";
				$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '$month' and c.product_code = 'Enhanced Foundation Mandarin' group by c.id ) ab group by collection_month, fee_structure_id";
					$result1=mysqli_query($connection, $sql);
					$IsRow=mysqli_num_rows($result1);
					$row_collection=mysqli_fetch_assoc($result1)
					?>
					<input class="<?php if($IsRow<1){ echo "fee_amounts4";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["mandarin_adjust"]?>" readonly>
					<input type="text" placeholder="Fee"  name="mandarin_collection" value="<?php echo $row["mandarin_collection"]?>" readonly>
						
					<?php
					
					if($IsRow<1){
						$amount += $row["mandarin_adjust"];
						$item = $row["student_entry_level"];
					?>	
					<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Enhanced Foundation Mandarin'?>', '<?php echo $student_code ?>', 'Enhanced Foundation Mandarin', '<?php echo $row["mandarin_adjust"]?>', '1', '<?php echo $row["id"]; ?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Termly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
					<?php
				
				}else{
					if($row["mandarin_adjust"] > $row_collection["collection"]){
						$balance = $row["mandarin_adjust"] - $row_collection["collection"];
						?>
						<input class="fee_amounts4" type="hidden" value="<?php echo $balance?>">
						<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Enhanced Foundation Mandarin'?>', '<?php echo $student_code ?>', 'Enhanced Foundation Mandarin', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Termly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
					}else {
						echo "Paid";
					}
				}
				echo "</div>";
				}
			}
			if ($row["foundation_int_art"]){
		if($row["international_collection"]=="Termly"){
			echo '<div class="f3 feeItems">';
			$term = getTermFromDate(date('Y-m-d',strtotime($row['programme_date'])));
				$month = $i+1;
			
			echo "<br>";
			echo 'International Art';
			echo "<br>";
			$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '$month' and c.product_code = 'International Art' group by c.id ) ab group by collection_month, fee_structure_id";
			
				$result1=mysqli_query($connection, $sql);
				$IsRow=mysqli_num_rows($result1);
				$row_collection=mysqli_fetch_assoc($result1)
				?> 
				<input class="<?php if($IsRow<1){ echo "fee_amounts4";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["international_adjust"]?>" readonly>
				<input type="text" placeholder="Fee"  name="international_collection" value="<?php echo $row["international_collection"]?>" readonly>
					
				<?php
					
				if($IsRow<1){
				$amount += $row["international_adjust"];
				$item = $row["student_entry_level"];
				?>	
				<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'International Art'?>', '<?php echo $student_code ?>', 'international', '<?php echo $row["international_adjust"]?>', '1', '<?php echo $row["id"]; ?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Termly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
				<?php
			
			}else{
				if($row["international_adjust"] > $row_collection["collection"]){
					$balance = $row["international_adjust"] - $row_collection["collection"];
					?>
					<input class="fee_amounts4" type="hidden" value="<?php echo $balance?>">
					<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'International Art'?>', '<?php echo $student_code ?>', 'international', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Termly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
				}else {
					echo "Paid";
				}
			}
			echo "</div>";
			}
		}
		if ($row["foundation_int_english"]){
		if($row["enhanced_collection"]=="Termly"){
			echo '<div class="f4 feeItems">';
			$term = getTermFromDate(date('Y-m-d',strtotime($row['programme_date'])));
			
			$month = $i+1;
			
			echo "<br>";
			echo 'International English';
			echo "<br>";
			$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '$month' and c.product_code = 'International English' group by c.id ) ab group by collection_month, fee_structure_id";
		
				$result1=mysqli_query($connection, $sql);
				$IsRow=mysqli_num_rows($result1);
				$row_collection=mysqli_fetch_assoc($result1)
				?>
				<input class="<?php if($IsRow<1){ echo "fee_amounts4";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["enhanced_adjust"]?>" readonly>
				<input type="text" placeholder="Fee"  name="enhanced_collection" value="<?php echo $row["enhanced_collection"]?>" readonly>
					
				<?php
				if($IsRow<1){
				$amount += $row["enhanced_adjust"];
				$item = $row["student_entry_level"];
				?>	
					<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'International English'?>', '<?php echo $student_code ?>', 'foundation', '<?php echo $row["enhanced_adjust"]?>', '1', '<?php echo $row["id"]; ?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Termly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
				<?php
				
			}else{
				if($row["enhanced_adjust"] > $row_collection["collection"]){
					$balance = $row["enhanced_adjust"] - $row_collection["collection"];
					?>
					<input class="fee_amounts4" type="hidden" value="<?php echo $balance?>">
					<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'International English'?>', '<?php echo $student_code ?>', 'foundation', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Termly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
				}else {
					echo "Paid";
				}
			}
			echo "</div>";
			}
		}
		if ($row["robotic_plus"]){
			if($row["robotic_plus_collection"]=="Termly"){
				echo '<div class="f5 feeItems">';
				$term = getTermFromDate(date('Y-m-d',strtotime($row['programme_date'])));
					$month = $i+1;
				
				echo "<br>";
				echo 'Robotic Plus';
				echo "<br>";
				$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '$month' and c.product_code = 'Robotic Plus' group by c.id ) ab group by collection_month, fee_structure_id";
				
					$result1=mysqli_query($connection, $sql);
					$IsRow=mysqli_num_rows($result1);
					$row_collection=mysqli_fetch_assoc($result1)
					?> 
					<input class="<?php if($IsRow<1){ echo "fee_amounts4";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["robotic_plus_adjust"]?>" readonly>
					<input type="text" placeholder="Fee"  name="robotic_plus_collection" value="<?php echo $row["robotic_plus_collection"]?>" readonly>
						
					<?php
						
					if($IsRow<1){
					$amount += $row["robotic_plus_adjust"];
					$item = $row["student_entry_level"];
					?>	
					<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Robotic Plus'?>', '<?php echo $student_code ?>', 'robotic-plus', '<?php echo $row["robotic_plus_adjust"]?>', '1', '<?php echo $row["id"]; ?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Termly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
					<?php
				
				}else{
					if($row["robotic_plus_adjust"] > $row_collection["collection"]){
						$balance = $row["robotic_plus_adjust"] - $row_collection["collection"];
						?>
						<input class="fee_amounts4" type="hidden" value="<?php echo $balance?>">
						<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Robotic Plus'?>', '<?php echo $student_code ?>', 'robotic-plus', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Termly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
					}else {
						echo "Paid";
					}
				}
				echo "</div>";
				}
			}
			if($row["islam_adjust"]!="" && $row["islam_collection"]!=""){ 
				if ($row["pendidikan_islam"]){
					if($row["islam_collection"]=="Termly"){
					echo '<div class="f6 feeItems"><br>';
					
					$month = $i+1;
					echo "Pendidikan Islam/Jawi";
					$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '$month' and c.product_code = 'Pendidikan Islam' group by c.id ) ab group by collection_month, fee_structure_id";
					
					$result1=mysqli_query($connection, $sql);
					$IsRow=mysqli_num_rows($result1);
					$row_collection=mysqli_fetch_assoc($result1)
					?>
					<br> 
					<input class="<?php if($IsRow<1){ echo "fee_amounts4";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["islam_adjust"]?>" id="" readonly>
					<input type="text" placeholder="Fee"  name="islam_collection" value="<?php echo $row["islam_collection"]?>" readonly>
					
					<?php 
					if($IsRow<1){
						$amount += $row["islam_adjust"];
						$item = $row["student_entry_level"];
					?>	
						<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Pendidikan Islam'?>', '<?php echo $student_code ?>', 'pendidikan', '<?php echo $row["islam_adjust"]?>', '1', '<?php echo $row["id"]; ?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Termly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
					<?php
				}else{
					if($row["islam_adjust"] > $row_collection["collection"]){
						$balance = $row["islam_adjust"] - $row_collection["collection"];
						?>
						<input class="fee_amounts4" type="hidden" value="<?php echo $balance?>">
						<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Pendidikan Islam'?>', '<?php echo $student_code ?>', 'pendidikan', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Termly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
					}else {
						echo "Paid";
					}
				}
				echo "</div>";
				}
			}
			}
	}
		?>	
	
	<!---------end termly-------------------------->
	
	</td>
	</tr>
	</table>
	</div>
	<?php

	}
//end Termly

//start Half Yearly	 
	$sql="SELECT ps.id, ps.student_id, s.student_code, f.iq_math_adjust, ps.student_entry_level, ps.enhanced_foundation, f.iq_math_collection, f.mandarin_adjust, f.mandarin_collection, f.international_adjust, f.enhanced_adjust, f.robotic_plus_adjust, f.enhanced_collection, f.international_collection, f.robotic_plus_collection, f.extend_year, f.basic_adjust, f.islam_adjust, fl.fee_id, fl.programme_date, fl.programme_date_end, fl.foundation_int_english, fl.foundation_iq_math, fl.robotic_plus, fl.foundation_int_mandarin, fl.foundation_int_art, fl.pendidikan_islam, fl.islam_collection from student s, programme_selection ps, student_fee_list fl, fee_structure f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.id='$ssid'";
	
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
		<?php if(($row["iq_math_collection"]=="Half Year" && $row["foundation_iq_math"]) || ($row["robotic_plus_collection"]=="Half Year" && $row["robotic_plus"]) || ($row["mandarin_collection"]=="Half Year" && $row["foundation_int_mandarin"]) || ($row["international_collection"]=="Half Year" && $row["foundation_int_art"]) || ($row["islam_collection"]=="Half Year" && $row["pendidikan_islam"]) || ($row["enhanced_collection"]=="Half Year" && $row["foundation_int_english"] && $row["enhanced_adjust"]!="" )){ ?>
			<h2><?php echo $row["student_entry_level"]?></h2>
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
		if(($row["iq_math_collection"]=="Half Year" && $row["foundation_iq_math"]) || ($row["robotic_plus_collection"]=="Half Year" && $row["robotic_plus"]) || ($row["mandarin_collection"]=="Half Year" && $row["foundation_int_mandarin"]) || ($row["international_collection"]=="Half Year" && $row["foundation_int_art"]) || ($row["islam_collection"]=="Half Year" && $row["pendidikan_islam"]) || ($row["enhanced_collection"]=="Half Year" && $row["foundation_int_english"] && $row["enhanced_adjust"]!="" )){
			?>
			
	<label>
		<h3 style="margin-bottom: 0px;"><?php echo getStrHalfYearly($i+1); ?></h3>
			<?php 
			}
	if($row["iq_math_adjust"]!="" && $row["iq_math_collection"]!=""){
		if ($row["foundation_int_art"]){ 
		if($row["iq_math_collection"]=="Half Year"){
			echo '<div class="f1 feeItems">';
		 
				$month = $i+1;
			echo "<br>";
			echo 'IQ Math';
			echo "<br>";
			$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '$month' and c.product_code = 'IQ Math' group by c.id ) ab group by collection_month, fee_structure_id";
			$result1=mysqli_query($connection, $sql);
			$IsRow=mysqli_num_rows($result1);
			$row_collection=mysqli_fetch_assoc($result1)
		?>
			<input class="<?php if($IsRow<1){ echo "fee_amounts4";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["iq_math_adjust"]?>" id="" readonly>
			<input type="text" placeholder="Fee"  name="iq_math_collection" value="<?php echo $row["iq_math_collection"]?>" readonly>
				</label>
			<?php
				
			if($IsRow<1){
				$amount += $row["iq_math_adjust"];
				$item = $row["student_entry_level"];
			?>	
				<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'IQ Math'?>', '<?php echo $student_code ?>', 'math', '<?php echo $row["iq_math_adjust"]?>', '1', '<?php echo $row["id"]; ?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Half Year')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
							
			<?php
			
		}else{
			if($row["iq_math_adjust"] > $row_collection["collection"]){
				$balance = $row["iq_math_adjust"] - $row_collection["collection"];
				?>
				<input class="fee_amounts4" type="hidden" value="<?php echo $balance?>">
				<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'IQ Math'?>', '<?php echo $student_code ?>', 'math', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Half Year')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
			}else {
				echo "Paid";
			}
		}
		echo "</div>";
		}
	}
	}
	if ($row["foundation_int_mandarin"]){ 
	if($row["mandarin_collection"]=="Half Year"){
		echo '<div class="f2 feeItems">';
		$month = $i+1;
			echo "<br>";
			echo 'Enhanced Foundation Mandarin';
			echo "<br>";
			$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '$month' and c.product_code = 'Enhanced Foundation Mandarin' group by c.id ) ab group by collection_month, fee_structure_id";
			
			$result1=mysqli_query($connection, $sql);
			$IsRow=mysqli_num_rows($result1);
			$row_collection=mysqli_fetch_assoc($result1)
		?>
			<input class="<?php if($IsRow<1){ echo "fee_amounts4";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["mandarin_adjust"]?>" readonly>
			<input type="text" placeholder="Fee"  name="mandarin_collection" value="<?php echo $row["mandarin_collection"]?>" readonly>
				</label>
			<?php 
			if($IsRow<1){
				$amount += $row["mandarin_adjust"];
				$item = $row["student_entry_level"];
				?>	
			<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Enhanced Foundation Mandarin'?>', '<?php echo $student_code ?>', 'Enhanced Foundation Mandarin', '<?php echo $row["mandarin_adjust"]?>', '1', '<?php echo $row["id"]; ?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Half Year')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>			   
			<?php
			//}
		}else{
			if($row["mandarin_adjust"] > $row_collection["collection"]){
				$balance = $row["mandarin_adjust"] - $row_collection["collection"];
				?>
				<input class="fee_amounts4" type="hidden" value="<?php echo $balance?>">
				<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Enhanced Foundation Mandarin'?>', '<?php echo $student_code ?>', 'Enhanced Foundation Mandarin', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Half Year')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
			}else {
				echo "Paid";
			}
		}
		echo "</div>";
		}
	}
	if ($row["foundation_int_art"]){ 
	if($row["international_collection"]=="Half Year"){
		echo '<div class="f3 feeItems">';
		$month = $i+1;
			echo "<br>";
			echo 'International Art';
			echo "<br>";
			$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '$month' and c.product_code = 'International Art' group by c.id ) ab group by collection_month, fee_structure_id";
			
			$result1=mysqli_query($connection, $sql);
			$IsRow=mysqli_num_rows($result1);
			$row_collection=mysqli_fetch_assoc($result1)
		?>
			<input class="<?php if($IsRow<1){ echo "fee_amounts4";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["international_adjust"]?>" readonly>
			<input type="text" placeholder="Fee"  name="international_collection" value="<?php echo $row["international_collection"]?>" readonly>
				</label>
				<?php
				
			if($IsRow<1){
			$amount += $row["international_adjust"];
			$item = $row["student_entry_level"];
				?>
			<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'International Art'?>', '<?php echo $student_code ?>', 'international', '<?php echo $row["international_adjust"]?>', '1', '<?php echo $row["id"]; ?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Half Year')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>			   
			<?php
			
		}else{
			if($row["international_adjust"] > $row_collection["collection"]){
				$balance = $row["international_adjust"] - $row_collection["collection"];
				?>
				<input class="fee_amounts4" type="hidden" value="<?php echo $balance?>">
				<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'International Art'?>', '<?php echo $student_code ?>', 'international', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Half Year')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
			}else {
				echo "Paid";
			}
		}
		echo "</div>";
		}
	}
	if ($row["foundation_int_english"]){ 
		if($row["enhanced_collection"]=="Half Year"){
			echo '<div class="f4 feeItems">';
			$month = $i+1;
			echo "<br>";
			echo 'International English';
			echo "<br>";
			$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '$month' and c.product_code = 'International English' group by c.id ) ab group by collection_month, fee_structure_id";
			
			$result1=mysqli_query($connection, $sql);
			$IsRow=mysqli_num_rows($result1);
			$row_collection=mysqli_fetch_assoc($result1)
		?>
			<input class="<?php if($IsRow<1){ echo "fee_amounts4";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["enhanced_adjust"]?>" readonly>
			<input type="text" placeholder="Fee"  name="enhanced_collection" value="<?php echo $row["enhanced_collection"]?>" readonly>
				</label>
			<?php
				
			if($IsRow<1){
			$amount += $row["enhanced_adjust"];
			$item = $row["student_entry_level"];
			?>	
				<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'International English'?>', '<?php echo $student_code ?>', 'foundation', '<?php echo $row["enhanced_adjust"]?>', '1', '<?php echo $row["id"]; ?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Half Year')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
							
			<?php
			//}
		}else{
			if($row["enhanced_adjust"] > $row_collection["collection"]){
				$balance = $row["enhanced_adjust"] - $row_collection["collection"];
				?>
				<input class="fee_amounts4" type="hidden" value="<?php echo $balance?>">
				<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'International English'?>', '<?php echo $student_code ?>', 'foundation', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Half Year')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
			}else {
				echo "Paid";
			}
		}
		echo "</div>";
		}
		}
		if ($row["robotic_plus"]){ 
			if($row["robotic_plus_collection"]=="Half Year"){
				echo '<div class="f5 feeItems">';
				$month = $i+1;
					echo "<br>";
					echo 'Robotic Plus';
					echo "<br>";
					$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '$month' and c.product_code = 'Robotic Plus' group by c.id ) ab group by collection_month, fee_structure_id";
					
					$result1=mysqli_query($connection, $sql);
					$IsRow=mysqli_num_rows($result1);
					$row_collection=mysqli_fetch_assoc($result1)
				?>
					<input class="<?php if($IsRow<1){ echo "fee_amounts4";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["robotic_plus_adjust"]?>" readonly>
					<input type="text" placeholder="Fee"  name="robotic_plus_collection" value="<?php echo $row["robotic_plus_collection"]?>" readonly>
						</label>
					<?php 
					if($IsRow<1){
						$amount += $row["robotic_plus_adjust"];
						$item = $row["student_entry_level"];
						?>	
					<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Robotic Plus'?>', '<?php echo $student_code ?>', 'robotic-plus', '<?php echo $row["robotic_plus_adjust"]?>', '1', '<?php echo $row["id"]; ?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Half Year')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>			   
					<?php
					//}
				}else{
					if($row["robotic_plus_adjust"] > $row_collection["collection"]){
						$balance = $row["robotic_plus_adjust"] - $row_collection["collection"];
						?>
						<input class="fee_amounts4" type="hidden" value="<?php echo $balance?>">
						<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Robotic Plus'?>', '<?php echo $student_code ?>', 'robotic-plus', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Half Year')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
					}else {
						echo "Paid";
					}
				}
				echo "</div>";
				}
			}
			if($row["islam_adjust"]!="" && $row["islam_collection"]!=""){
				if ($row["pendidikan_islam"]){ 
				if($row["islam_collection"]=="Half Year"){
					echo '<div class="f6 feeItems">';
				 
						$month = $i+1;
					echo "<br>";
					echo 'Pendidikan Islam';
					echo "<br>";
					$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '$month' and c.product_code = 'Pendidikan Islam' group by c.id ) ab group by collection_month, fee_structure_id";
					$result1=mysqli_query($connection, $sql);
					$IsRow=mysqli_num_rows($result1);
					$row_collection=mysqli_fetch_assoc($result1)
				?>
					<input class="<?php if($IsRow<1){ echo "fee_amounts4";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["islam_adjust"]?>" id="" readonly>
					<input type="text" placeholder="Fee"  name="islam_collection" value="<?php echo $row["islam_collection"]?>" readonly>
						</label>
					<?php
						
					if($IsRow<1){
						$amount += $row["islam_adjust"];
						$item = $row["student_entry_level"];
					?>	
						<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Pendidikan Islam'?>', '<?php echo $student_code ?>', 'pendidikan', '<?php echo $row["islam_adjust"]?>', '1', '<?php echo $row["id"]; ?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Half Year')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
									
					<?php
					
				}else{
					if($row["islam_adjust"] > $row_collection["collection"]){
						$balance = $row["islam_adjust"] - $row_collection["collection"];
						?>
						<input class="fee_amounts4" type="hidden" value="<?php echo $balance?>">
						<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Pendidikan Islam'?>', '<?php echo $student_code ?>', 'pendidikan', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '<?php echo $i+1; ?>', '<?php echo $row["extend_year"];?>', 'Half Year')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
					}else {
						echo "Paid";
					}
				}
				echo "</div>";
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
//end Half yearly

//start Annually	 
	$sql="SELECT ps.id, ps.student_id, s.student_code, f.iq_math_adjust, ps.student_entry_level, ps.enhanced_foundation, f.iq_math_collection, f.mandarin_adjust, f.mandarin_collection, f.international_adjust, f.enhanced_adjust, f.enhanced_collection, f.international_collection, f.extend_year, f.basic_adjust, f.islam_adjust, fl.fee_id, fl.programme_date, fl.programme_date_end, fl.foundation_int_english, fl.foundation_iq_math, fl.foundation_int_mandarin, fl.foundation_int_art, fl.pendidikan_islam, fl.islam_collection, fl.robotic_plus from student s, programme_selection ps, student_fee_list fl, fee_structure f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.id='$ssid'";
	
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
		<?php 
			if(($row["iq_math_collection"]=="Annually" && $row["foundation_iq_math"]) || ($row["mandarin_collection"]=="Annually" && $row["foundation_int_mandarin"]) || ($row["international_collection"]=="Annually" && $row["foundation_int_art"]) || ($row["enhanced_collection"]=="Annually" && $row["foundation_int_english"] && $row["enhanced_adjust"]!="" ) || ($row["pendidikan_islam"] && ($row["islam_collection"]=="" || $row["islam_collection"]=="Annually")))
			{ 
		?>
				<h2><?php echo $row["student_entry_level"]?></h2>
		<?php	
			} 
		?>
			<table>
				<tr>
					<td>
	
	<!---------------start Annually-------------------------------------->

					<?php 
						if(($row["iq_math_collection"]=="Annually" && $row["foundation_iq_math"]) || ($row["mandarin_collection"]=="Annually" && $row["foundation_int_mandarin"]) || ($row["international_collection"]=="Annually" && $row["foundation_int_art"]) || ($row["enhanced_collection"]=="Annually" && $row["foundation_int_english"] && $row["enhanced_adjust"]!="" )){
					?>
							<div>
								<label>
									<h3 style="margin-bottom: 0px;">Annually</h3>
					<?php 
						}
						if($row["iq_math_adjust"]!="" && $row["iq_math_collection"]!=""){ 
							if ($row["foundation_iq_math"]){
								if($row["iq_math_collection"]=="Annually"){
									echo '<div class="f1 feeItems">';
									echo "<br>";
									echo 'IQ Math';
									echo "<br>";
									$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '1' and c.product_code = 'IQ Math' group by c.id ) ab group by collection_month, fee_structure_id";
									$result1=mysqli_query($connection, $sql);
									$IsRow=mysqli_num_rows($result1);
									$row_collection=mysqli_fetch_assoc($result1)
					?>
									<br> 
									<input class="<?php if($IsRow<1){ echo "fee_amounts4";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["iq_math_adjust"]?>" id="" readonly>
									<input type="text" placeholder="Fee"  name="iq_math_collection" value="<?php echo $row["iq_math_collection"]?>" readonly>
								</label>
				<?php 
								if($IsRow<1){
									$amount += $row["iq_math_adjust"];
									$item = $row["student_entry_level"];
				?>
									<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'IQ Math'?>', '<?php echo $student_code ?>', 'math', '<?php echo $row["iq_math_adjust"]?>', '1','<?php echo $row["id"]?>', '1', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
			<?php
								}
								else
								{
									if($row["iq_math_adjust"] > $row_collection["collection"])
									{
										$balance = $row["iq_math_adjust"] - $row_collection["collection"];
			?>
										<input class="fee_amounts4" type="hidden" value="<?php echo $balance?>">
										<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'IQ Math'?>', '<?php echo $student_code ?>', 'math', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '1', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
									} 
									else 
									{
										echo "Paid";
									}
								}
								echo "</div>";
							}
						}
					}
	if ($row["foundation_int_mandarin"]){
		if($row["mandarin_collection"]=="Annually"){
			echo '<div class="f2 feeItems">';
			echo "<br>";
			echo 'Enhanced Foundation Mandarin';
			echo "<br>";
			$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '1' and c.product_code = 'Enhanced Foundation Mandarin' group by c.id ) ab group by collection_month, fee_structure_id";
			$result1=mysqli_query($connection, $sql);
			$IsRow=mysqli_num_rows($result1);
			$row_collection=mysqli_fetch_assoc($result1)
		?>
		<br> 
			<input class="<?php if($IsRow<1){ echo "fee_amounts4";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["mandarin_adjust"]?>" readonly>
			<input type="text" placeholder="Fee"  name="mandarin_collection" value="<?php echo $row["mandarin_collection"]?>" readonly>
				</label>
			<?php
				
			if($IsRow<1){
				$amount += $row["mandarin_adjust"];
				$item = $row["student_entry_level"];
			?>	
				<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Enhanced Foundation Mandarin'?>', '<?php echo $student_code ?>', 'Enhanced Foundation Mandarin', '<?php echo $row["mandarin_adjust"]?>', '1','<?php echo $row["id"]?>', '1', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
				
			<?php
			}else{
				if($row["mandarin_adjust"] > $row_collection["collection"]){
					$balance = $row["mandarin_adjust"] - $row_collection["collection"];
					?>
					<input class="fee_amounts4" type="hidden" value="<?php echo $balance?>">
					<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Enhanced Foundation Mandarin'?>', '<?php echo $student_code ?>', 'Enhanced Foundation Mandarin', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '1', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
				}else {
					echo "Paid";
				}
			}
			echo "</div>";
		}
	}
	if ($row["foundation_int_art"]){
	if($row["international_collection"]=="Annually"){
		echo '<div class="f3 feeItems">';
			echo "<br>";
			echo 'International Art';
			echo "<br>";
			$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '1' and c.product_code = 'International Art' group by c.id ) ab group by collection_month, fee_structure_id";
			$result1=mysqli_query($connection, $sql);
			$IsRow=mysqli_num_rows($result1);
			$row_collection=mysqli_fetch_assoc($result1)
		?>
		<br> 
			<input class="<?php if($IsRow<1){ echo "fee_amounts4";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["international_adjust"]?>" readonly>
			<input type="text" placeholder="Fee"  name="international_collection" value="<?php echo $row["international_collection"]?>" readonly>
				</label>
			<?php
			
			if($IsRow<1){
			$amount += $row["international_adjust"];
			$item = $row["student_entry_level"];
			?>	
				<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'International Art'?>', '<?php echo $student_code ?>', 'international', '<?php echo $row["international_adjust"]?>', '1','<?php echo $row["id"]?>', '1', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
				
			<?php
			}else{
				if($row["international_adjust"] > $row_collection["collection"]){
					$balance = $row["international_adjust"] - $row_collection["collection"];
					?>
					<input class="fee_amounts4" type="hidden" value="<?php echo $balance?>">
					<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'International Art'?>', '<?php echo $student_code ?>', 'international', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '1', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
				}else {
					echo "Paid";
				}
			}
			echo "</div>";
		}
		}
		if ($row["foundation_int_english"]){
		if($row["enhanced_collection"]=="Annually"){
			echo '<div class="f4 feeItems">';
			echo "<br>";
			echo 'International English';
			echo "<br>";
			$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '1' and c.product_code = 'International English' group by c.id ) ab group by collection_month, fee_structure_id";
		
			$result1=mysqli_query($connection, $sql);
			$IsRow=mysqli_num_rows($result1);
			$row_collection=mysqli_fetch_assoc($result1)
		?>
		<br> 
			<input type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["enhanced_adjust"]?>" readonly>
			<input type="text" placeholder="Fee"  name="enhanced_collection" value="<?php echo $row["enhanced_collection"]?>" readonly>
				</label>
			<?php
			
			if($IsRow<1){
			$amount += $row["enhanced_adjust"];
			$item = $row["student_entry_level"];
			?>	
				<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'International English'?>', '<?php echo $student_code ?>', 'foundation', '<?php echo $row["enhanced_adjust"]?>', '1','<?php echo $row["id"]?>', '1', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
				
			<?php
			}else{
				if($row["enhanced_adjust"] > $row_collection["collection"]){
					$balance = $row["enhanced_adjust"] - $row_collection["collection"];
					?>
					<input class="fee_amounts4" type="hidden" value="<?php echo $balance?>">
					<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'International English'?>', '<?php echo $student_code ?>', 'foundation', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '1', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
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
	<?php
		if ($row["pendidikan_islam"] && ($row["islam_collection"] == '' || $row["islam_collection"] == 'Annually')){
	?>	 
	<tr>
		<td>
		<div class="f2 feeItems">
			<h3 style="margin-bottom: 0px;">Pendidikan Islam/Jawi</h3>
				<?php 
					$sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '$ssid' and f.id = '$row[fee_id]' and  c.year = f.extend_year and c.void = 0 and c.collection_month = '1' and c.product_code = 'Pendidikan Islam' group by c.id ) ab group by collection_month, fee_structure_id";
				
					$result1=mysqli_query($connection, $sql);
					$IsRow=mysqli_num_rows($result1);
					$row_collection=mysqli_fetch_assoc($result1)
				?>
				<input class="<?php if($IsRow<1){ echo "fee_amounts4";} ?>" type="number" placeholder="Fee" step="0.01" name="" value="<?php echo $row["islam_adjust"]?>" readonly>
				
			<?php 				
				if($IsRow<1){
					$amount += $row["islam_adjust"];
					$item = $row["student_entry_level"];
			?>
					<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Pendidikan Islam'?>', '<?php echo $student_code ?>', 'pendidikan', '<?php echo $row["islam_adjust"]?>', '1', '<?php echo $row["id"]?>', '1', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
				
			<?php
				}else{
					if($row["islam_adjust"] > $row_collection["collection"]){
						$balance = $row["islam_adjust"] - $row_collection["collection"];
						?>
						<input class="fee_amounts4" type="hidden" value="<?php echo $balance?>">
						<a class="uk-form-small uk-width-1-1" data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket('<?php echo 'Pendidikan Islam'?>', '<?php echo $student_code ?>', 'pendidikan', '<?php echo $balance; ?>', '1', '<?php echo $row["id"]?>', '1', '<?php echo $row["extend_year"];?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>	<?php echo "Collected: " . $row_collection["collection"]; 
					}else {
						echo "Paid";
					}
				}
			?>	 
	</div>
	</td>
	</tr>
	<?php
	}
	?>	 
	</table>
	</div>
	<?php
	}
//end Yearly
	  ?>
	<table>
	  <tr>
		<td>
		<input type="hidden" placeholder="Outstanding Amount" step="0.01" name="total_amount2" id="total_amount2" value="<?php echo $amount; ?>" readonly>
		</td>
	</tr>
	<tr>
		<td>
		<input type="hidden" placeholder="Outstanding Item" name="item_name2" id="item_name2" value="<?php echo $item; ?>" readonly>
		</td>
	</tr> 
	</table>
   </div>

   <div class="uk-width-1-2 uk-form">

      <div class="uk-text-center myheader">

         <h2 class="myheader-text-color">Enhanced Foundation Basket Content</h2>

      </div>

      <div id="lstBusketPlacement"></div>

      <a onclick="doPutInBusket()" class="uk-button uk-button-small uk-button-primary uk-align-right">Put in Basket</a>

      <a class="uk-button uk-button-small uk-align-right" onclick="$('#placement-dialog').dialog('close');">Cancel</a>

   </div>

