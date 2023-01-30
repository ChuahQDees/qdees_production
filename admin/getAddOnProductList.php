<?php
session_start();
include_once("../mysql.php");
include_once("../search_new.php");
include_once("functions.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //$category, $s, $allocation_id, $student_code
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
function getCurrentHalfYearlyTerm($term)
{
   if($term < 3) {
      return 1;
   } else if($term >= 3) {
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
function add2AOTempBusket(product_code, student_code, unit_price, collection_month, collection_year, collection_pattern) {
   if ((product_code!="") & (student_code!="")) {
      $.ajax({
         url : "admin/add2AOTempBusket.php",
         type : "POST",
         data : "product_code="+escape(product_code)+"&student_code="+student_code+"&unit_price="+unit_price+"&collection_month="+collection_month+"&collection_year="+collection_year+"&collection_pattern="+collection_pattern,
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
            getAOTempBusket();
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

		 var currentYear = "<?php echo $_SESSION['Year']; ?>";
       $("#year9").change(function(){
	
			if($(this).val()!=""){
			$(".fees").hide();
			$("."+$(this).val()).show();
			var selected_year = $(this).val();
			var t_amount_o_8 = 0;
			for (i = 2019; i < parseInt(selected_year)+1; i++) {
				var fee_amounts2 = $("."+i).find(".fee_amounts2");
			
				var t_amount1 = 0;
				fee_amounts2.each(function(){
					var fee_amount1 = $(this).val();
					if(fee_amount1!=""){
						t_amount1 += parseFloat(fee_amount1);
					}
				})
				t_amount_o_8 += t_amount1;
			}
			$("#total_amount_s").val(t_amount_o_8);
			}else{
				$(".fees").hide();
				//$("#total_amount_s").val($("#total_amount").val());
				$("#total_amount_s").val(0);
			}
		})
 
   setTimeout(function(){ 
      $("#year9").val(currentYear);
      $("#year9").change();
      }, 100); 
});
</script>

<br>
<!---------Start Monthly----------------------------------->
   <?php
   //$selected_year = $_SESSION['Year'];
   $sql1="SELECT ps.id, ps.student_id, s.student_code, fl.fee_id, fl.programme_date, fl.programme_date_end, f.extend_year from student s, programme_selection ps, student_fee_list fl, fee_structure f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and sha1(s.student_code)='$student_code'";
         
      $result1=mysqli_query($connection, $sql1);
   $num_row=mysqli_num_rows($result1);
   $amount = 0;

   $jan2022 = 0; $feb2022 = 0;

   while ($row1=mysqli_fetch_assoc($result1)) { // start main while
      $programme_date = $row1["programme_date"];
      $programme_date = date("m",strtotime($programme_date));

      $programme_date_end = $row1["programme_date_end"];
      $month = date("m",strtotime($programme_date_end));

      $month_array = getMonthList($row1["programme_date"], $row1["programme_date_end"]);

      foreach ($month_array as $dt) {

         $i = $dt->format("m");

         if($dt->format("M Y") == 'Jan 2022') { $jan2022 = 1; }
			if($dt->format("M Y") == 'Feb 2022') { $feb2022 = 1; }
         
         $base_sql="SELECT * from addon_product where centre_code='".$_SESSION["CentreCode"]."' and status='Approved' and monthly ='Monthly'";
         $s_token=ConstructTokenGroup("product_code", "%".$s."%", "like", "product_name", "%".$s."%", "like", "or");
    
          $final_sql=ConcatWhere($base_sql, $s_token);
          
          $result=mysqli_query($connection, $final_sql);
          $num_row=mysqli_num_rows($result);
         if ($num_row>0) {
            ?>
            <table class="uk-table uk-table-small <?php echo $row1["extend_year"] ?> fees">
            <tr>
               <td colspan="6"> <h3 style="margin-bottom: 0px;margin-top:0px;"><?php echo $dt->format("M Y"); ?></h3></td>
            </tr>
            <tr class="uk-text-bold uk-text-small">
               <td>Code</td>
               <td>Name</td>
               <td>UOM</td>
               <td>U/Price</td>
               <td>Action</td>
            </tr>
         <?php
            while ($row=mysqli_fetch_assoc($result)) {
               $product_code=$row["product_code"];
               $unit_price=$row["unit_price"];
               ?>
               <tr class="uk-text-small">
                  <td><?php echo $row["product_code"]?></td>
                  <td><?php echo $row["product_name"]?></td>
                  <td><?php echo $row["uom"]?></td>
                  <td><?php echo $row["unit_price"]?></td>
                  <td>
                     <?php
                     //if ($_SESSION['Year'] == date('Y')) {
                        $sql = "SELECT sum(c.amount) as collection, c.`collection_month` FROM `collection` c where sha1(c.student_code) = '$student_code' and c.product_code = '$product_code' and c.year='".$row1["extend_year"]."' and c.collection_month = $i and collection_pattern='Monthly' and c.void = 0 group by c.year, c.`collection_month`";
                         
                           $result2=mysqli_query($connection, $sql);
                           $IsRow=mysqli_num_rows($result2);
                           $row_collection=mysqli_fetch_assoc($result2);
                           $balance = $row["unit_price"] - $row_collection["collection"];
                           if($IsRow<1){
                              ?>
                              <a data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2AOTempBusket('<?php echo $product_code?>', '<?php echo $student_code?>', '<?php echo $unit_price?>','<?php echo $i?>' ,'<?php echo $row1["extend_year"] ?>', 'Monthly' )"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
                              <?php
                           }else{
                              /* if($row["unit_price"] > $row_collection["collection"]){
                                 ?>
                                 <a data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2AOTempBusket('<?php echo $product_code?>', '<?php echo $student_code?>', '<?php echo $balance; ?>','<?php echo $i?>' ,'<?php echo $row1["extend_year"] ?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
                                 <?php
                                 echo "Collected: " . $row_collection["collection"]; 
                              }else {
                                 echo "Paid";
                              } */



                              if($dt->format("M Y") == 'Jan 2023' || $dt->format("M Y") == 'Feb 2023') {
                                 if(($row["unit_price"] * 2) == $row_collection["collection"]){
                                    echo "Paid";
                                 }
                                 else if($dt->format("M Y") == 'Jan 2023' && $jan2022 == 0 && $row["unit_price"] <= $row_collection["collection"]){
                                    echo "Paid";
                                 }
                                 else if($dt->format("M Y") == 'Feb 2023' && $feb2022 == 0 && $row["unit_price"] <= $row_collection["collection"]){
                                    echo "Paid";
                                 }
                                 else if(($dt->format("M Y") == 'Jan 2023' && $jan2022 == 1 && ($row["unit_price"]) >= $row_collection["collection"]) || ($dt->format("M Y") == 'Feb 2023' && $feb2022 == 1 && ($row["unit_price"]) >= $row_collection["collection"]) || ($dt->format("M Y") == 'Jan 2023' && $jan2022 == 0 && ($row_collection["collection"] == 0)) || ($dt->format("M Y") == 'Feb 2023' && $feb2022 == 0 && ($row_collection["collection"] == 0)))
                                 {
                           ?>
                                    <a data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2AOTempBusket('<?php echo $product_code?>', '<?php echo $student_code?>', '<?php echo $unit_price?>','<?php echo $i?>' ,'<?php echo $row1["extend_year"] ?>', 'Monthly' )"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
                           <?php 
                                 }
                                 else if(($dt->format("M Y") == 'Jan 2023' && $jan2022 == 1 && ($row["unit_price"] * 2) > $row_collection["collection"]) || ($dt->format("M Y") == 'Feb 2023' && $feb2022 == 1 && ($row["unit_price"] * 2) > $row_collection["collection"]) || ($dt->format("M Y") == 'Jan 2023' && $jan2022 == 0 && $row["unit_price"] > $row_collection["collection"]) || ($dt->format("M Y") == 'Feb 2023' && $feb2022 == 0 && $row["unit_price"] > $row_collection["collection"])) {
   
                                    $balance = $row["unit_price"] - $row_collection["collection"];
                           ?>

                                    <a data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2AOTempBusket('<?php echo $product_code?>', '<?php echo $student_code?>', '<?php echo $balance; ?>','<?php echo $i?>' ,'<?php echo $row1["extend_year"] ?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
                                    <?php
                                    echo "Collected: " . $row_collection["collection"]; 

                                 }
                              } else {
                                 if($row["unit_price"] > $row_collection["collection"]){
                                    $balance = $row["unit_price"] - $row_collection["collection"];
                                    ?>

                                    <a data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2AOTempBusket('<?php echo $product_code?>', '<?php echo $student_code?>', '<?php echo $balance; ?>','<?php echo $i?>' ,'<?php echo $row1["extend_year"] ?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
                                    <?php
                                    echo "Collected: " . $row_collection["collection"]; 

                                 }else {
                                    echo "Paid";
                                 }
                              }
                           }
                     //}
                     ?>
                  </td>
                  <!-- <td><?php //echo calcBal($_SESSION["CentreCode"], $row["product_code"], date("Y-m-d"));?></td> -->
               </tr>
               <?php
            }
            ?>
            </table>
            <?php
         } 
      }
      
   }
   ?>
<!---------end Monthly----------------------------------->

<!---------Start Termly----------------------------------->
<?php
   //$selected_year = $_SESSION['Year'];
   $sql1="SELECT ps.id, ps.student_id, s.student_code, fl.fee_id, fl.programme_date, fl.programme_date_end, f.extend_year from student s, programme_selection ps, student_fee_list fl, fee_structure f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and sha1(s.student_code)='$student_code'";

   $result1=mysqli_query($connection, $sql1);
   $num_row=mysqli_num_rows($result1);
   $amount = 0;
   $i=0;
   $extend_year =0;
   while ($row1=mysqli_fetch_assoc($result1)) { // start main while
      
      $programme_date = $row1["programme_date"];
      $programme_date = date("m",strtotime($programme_date));
      $programme_date_end = $row1["programme_date_end"];

      $month = date("m",strtotime($programme_date_end));
      $term = getTermFromDate(date('Y-m-d',strtotime($programme_date_end)));
      
      $term1 = (int)$term;

      if($extend_year != $row1["extend_year"]){
         $i=0;
      }
      $extend_year = $row1["extend_year"];
 
      for ($i; $i < $term1; $i++) {
          $base_sql="SELECT * from addon_product where centre_code='".$_SESSION["CentreCode"]."' and status='Approved' and monthly ='Termly'";
          $s_token=ConstructTokenGroup("product_code", "%".$s."%", "like", "product_name", "%".$s."%", "like", "or");
    
          $final_sql=ConcatWhere($base_sql, $s_token);
        
          $result=mysqli_query($connection, $final_sql);
          $num_row=mysqli_num_rows($result);
          
         if ($num_row>0) {
            
            ?>
            <table class="uk-table uk-table-small <?php echo $extend_year ?> fees">
            <tr>
               <td colspan="6"> <h3 style="margin-bottom: 0px;margin-top:0px;"><?php echo getStrTerm($i+1); ?></h3></td>
            </tr>
            <tr class="uk-text-bold uk-text-small">
               <td>Code</td>
               <td>Name</td>
               <td>UOM</td>
               <td>U/Price</td>
               <td>Action</td>
            </tr>
            <?php
           
            while ($row=mysqli_fetch_assoc($result)) {
               $product_code=$row["product_code"];
               $unit_price=$row["unit_price"];
               ?>
               <tr class="uk-text-small">
                  <td><?php echo $row["product_code"]?></td>
                  <td><?php echo $row["product_name"]?></td>
                  <td><?php echo $row["uom"]?></td>
                  <td><?php echo $row["unit_price"]?></td>
                  <td>
                     <?php
                     //if ($_SESSION['Year'] == date('Y')) {
                        $month = $i+1;
                        $sql = "SELECT sum(c.amount) as collection, c.`collection_month` FROM `collection` c where sha1(c.student_code) = '$student_code' and c.product_code = '$product_code' and c.year='".$row1["extend_year"]."' and c.collection_month = '$month' and collection_pattern='Termly' and c.void = 0 group by c.year, c.`collection_month`";
                           //echo $sql;
                           $result2=mysqli_query($connection, $sql);
                           $IsRow=mysqli_num_rows($result2);
                           $row_collection=mysqli_fetch_assoc($result2);
                           $balance = $row["unit_price"] - $row_collection["collection"];
                           if($IsRow<1){
                              ?>
                              <a data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2AOTempBusket('<?php echo $product_code?>', '<?php echo $student_code?>', '<?php echo $unit_price?>','<?php echo $i+1 ?>' ,'<?php echo $row1["extend_year"] ?>', 'Termly' )"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
                              <?php
                           }else{
                              if($row["unit_price"] > $row_collection["collection"]){
                                 ?>
                                 <a data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2AOTempBusket('<?php echo $product_code?>', '<?php echo $student_code?>', '<?php echo $balance; ?>','<?php echo $i+1?>' ,'<?php echo $row1["extend_year"] ?>', 'Termly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
                                 <?php
                                 echo "Collected: " . $row_collection["collection"]; 
                              }else {
                                 echo "Paid";
                              }
                           }
                     //}
                     ?>
                  </td>
                  <!-- <td><?php //echo calcBal($_SESSION["CentreCode"], $row["product_code"], date("Y-m-d"));?></td> -->
               </tr>
               <?php
            }
            ?>
            </table>
            <?php
         } 
      }
      
   }
   ?>
<!---------end Termly----------------------------------->

<!---------Start half yearly----------------------------------->
<?php
   //$selected_year = $_SESSION['Year'];
   $sql1="SELECT ps.id, ps.student_id, s.student_code, fl.fee_id, fl.programme_date, fl.programme_date_end, f.extend_year from student s, programme_selection ps, student_fee_list fl, fee_structure f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and sha1(s.student_code)='$student_code'";
         
      $result1=mysqli_query($connection, $sql1);
   $num_row=mysqli_num_rows($result1);
   $amount = 0;
   $extend_year=0;
   $i=0;
   while ($row1=mysqli_fetch_assoc($result1)) { // start main while
      $programme_date = $row1["programme_date"];
      $programme_date = date("m",strtotime($programme_date));

      $programme_date_end = $row1["programme_date_end"];
      $month = date("m",strtotime($programme_date_end));

      $startHearfYearly = getCurrentHalfYearlyTerm(getTermFromDate($row1["programme_date"]));
      $endHearfYearly = getCurrentHalfYearlyTerm(getTermFromDate($row1["programme_date_end"]));

      if($extend_year != $row1["extend_year"]){
         $i=0;
      }
      $extend_year = $row1["extend_year"];
      //$i=0;
      for ($i = $startHearfYearly-1; $i < $endHearfYearly; $i++) { 
        
          $base_sql="SELECT * from addon_product where centre_code='".$_SESSION["CentreCode"]."' and status='Approved' and monthly ='Half Year'";
          $s_token=ConstructTokenGroup("product_code", "%".$s."%", "like", "product_name", "%".$s."%", "like", "or");
    
          $final_sql=ConcatWhere($base_sql, $s_token);
          $result=mysqli_query($connection, $final_sql);
          $num_row=mysqli_num_rows($result);
         if ($num_row>0) {
            ?>
            <table class="uk-table uk-table-small <?php echo $extend_year ?> fees">
            <tr>
               <td colspan="6"> <h3 style="margin-bottom: 0px;margin-top:0px;"><?php echo getStrHalfYearly($i+1); ?></h3></td>
            </tr>
            <tr class="uk-text-bold uk-text-small">
               <td>Code</td>
               <td>Name</td>
               <td>UOM</td>
               <td>U/Price</td>
               <td>Action</td>
            </tr>
           
            <?php
            while ($row=mysqli_fetch_assoc($result)) {
               $product_code=$row["product_code"];
               $unit_price=$row["unit_price"];
               ?>
               <tr class="uk-text-small">
                  <td><?php echo $row["product_code"]?></td>
                  <td><?php echo $row["product_name"]?></td>
                  <td><?php echo $row["uom"]?></td>
                  <td><?php echo $row["unit_price"]?></td>
                  <td>
                     <?php
                        $month = $i+1;
                        $sql = "SELECT sum(c.amount) as collection, c.`collection_month` FROM `collection` c where sha1(c.student_code) = '$student_code' and c.product_code = '$product_code' and c.year='".$row1["extend_year"]."' and c.collection_month = '$month' and collection_pattern='Half Year' and c.void = 0 group by c.year, c.`collection_month`";
                        
                           $result2=mysqli_query($connection, $sql);
                           $IsRow=mysqli_num_rows($result2);
                           $row_collection=mysqli_fetch_assoc($result2);
                           $balance = $row["unit_price"] - $row_collection["collection"];
                           if($IsRow<1){
                              ?>
                              <a data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2AOTempBusket('<?php echo $product_code?>', '<?php echo $student_code?>', '<?php echo $unit_price?>','<?php echo $i+1?>' ,'<?php echo $row1["extend_year"] ?>', 'Half Year' )"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
                              <?php
                           }else{
                              if($row["unit_price"] > $row_collection["collection"]){
                                 ?>
                                 <a data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2AOTempBusket('<?php echo $product_code?>', '<?php echo $student_code?>', '<?php echo $balance; ?>','<?php echo $i+1?>' ,'<?php echo $row1["extend_year"] ?>', 'Monthly')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
                                 <?php
                                 echo "Collected: " . $row_collection["collection"]; 
                              }else {
                                 echo "Paid";
                              }
                           }
                     //}
                     ?>
                  </td>
                  <!-- <td><?php //echo calcBal($_SESSION["CentreCode"], $row["product_code"], date("Y-m-d"));?></td> -->
               </tr>
               <?php
            }
            ?>
         </table>
         <?php
         } 
      }
      
   }
   ?>
<!---------end half yearly----------------------------------->

<!---------Start yearly----------------------------------->
<?php
   //$selected_year = $_SESSION['Year'];
   $sql1="SELECT ps.id, ps.student_id, s.student_code, fl.fee_id, fl.programme_date, fl.programme_date_end, f.extend_year from student s, programme_selection ps, student_fee_list fl, fee_structure f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and sha1(s.student_code)='$student_code'";
         
   $result1=mysqli_query($connection, $sql1);
   $num_row=mysqli_num_rows($result1);
   $amount = 0;
   $extend_year=0;
   $i=0;
   while ($row1=mysqli_fetch_assoc($result1)) { // start main while
      $programme_date = $row1["programme_date"];
      $programme_date = date("m",strtotime($programme_date));

      $programme_date_end = $row1["programme_date_end"];
      $month = date("m",strtotime($programme_date_end));

      if($extend_year != $row1["extend_year"]){
         $i=0;
      }
      $extend_year = $row1["extend_year"];

         if($i==0){
         ?>
         <?php
          $base_sql="SELECT * from addon_product where centre_code='".$_SESSION["CentreCode"]."' and status='Approved' and monthly ='Annually'";
          $s_token=ConstructTokenGroup("product_code", "%".$s."%", "like", "product_name", "%".$s."%", "like", "or");
    
          $final_sql=ConcatWhere($base_sql, $s_token);
         
          $result=mysqli_query($connection, $final_sql);
          $num_row=mysqli_num_rows($result);
         if ($num_row>0) {
            $i++;
            ?>
            <table class="uk-table uk-table-small <?php echo $extend_year ?> fees">
            <tr>
               <td colspan="6"> <h3 style="margin-bottom: 0px;margin-top:0px;">Annually</h3></td>
            </tr>
            <tr class="uk-text-bold uk-text-small">
               <td>Code</td>
               <td>Name</td>
               <td>UOM</td>
               <td>U/Price</td>
               <td>Action</td>
            </tr>
            <?php
            while ($row=mysqli_fetch_assoc($result)) {
               $product_code=$row["product_code"];
               $unit_price=$row["unit_price"];
               ?>
               <tr class="uk-text-small">
                  <td><?php echo $row["product_code"]?></td>
                  <td><?php echo $row["product_name"]?></td>
                  <td><?php echo $row["uom"]?></td>
                  <td><?php echo $row["unit_price"]?></td>
                  <td>
                     <?php
                     //if ($_SESSION['Year'] == date('Y')) {
                        $sql = "SELECT sum(c.amount) as collection, c.`collection_month` FROM `collection` c where sha1(c.student_code) = '$student_code' and c.product_code = '$product_code' and c.year='".$row1["extend_year"]."' and c.collection_month = '1' and collection_pattern='Annually' and c.void = 0 group by c.year, c.`collection_month`";
                         
                           $result2=mysqli_query($connection, $sql);
                           $IsRow=mysqli_num_rows($result2);
                           $row_collection=mysqli_fetch_assoc($result2);
                           $balance = $row["unit_price"] - $row_collection["collection"];
                           if($IsRow<1){
                              ?>
                              <a data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2AOTempBusket('<?php echo $product_code?>', '<?php echo $student_code?>', '<?php echo $unit_price?>','1' ,'<?php echo $row1["extend_year"] ?>', 'Annually' )"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
                              <?php
                           }else{
                              if($row["unit_price"] > $row_collection["collection"]){
                                 ?>
                                 <a data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2AOTempBusket('<?php echo $product_code?>', '<?php echo $student_code?>', '<?php echo $balance; ?>','1' ,'<?php echo $row1["extend_year"] ?>', 'Annually')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>
                                 <?php
                                 echo "Collected: " . $row_collection["collection"]; 
                              }else {
                                 echo "Paid";
                              }
                           }
                     //}
                     ?>
                  </td>
                  <!-- <td><?php //echo calcBal($_SESSION["CentreCode"], $row["product_code"], date("Y-m-d"));?></td> -->
               </tr>
               <?php
            }
            ?>
            </table>
            <?php
         } 
      }
   }
   ?>
<!---------end Yearly----------------------------------->


   <!-- <tr>
      <td colspan="6">No record found</td>
   </tr> -->

