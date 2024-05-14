<?php
session_start();
function getLevelCss($sub_sub_category)
{
   $cat = preg_replace('/[^a-zA-Z0-9]+/', '', strtolower($sub_sub_category));

   $css = 'default';
   if (strpos($cat, 'prelevel1') !== false) {
      $css = 'pre-level-1';
   } else if (strpos($cat, 'level1') !== false) {
      $css = 'level-1';
   } else if (strpos($cat, 'level2') !== false) {
      $css = 'level-2';
   } else if (strpos($cat, 'level3') !== false) {
      $css = 'level-3';
   } else if (strpos($cat, 'level4') !== false) {
      $css = 'level-4';
   } else if (strpos($cat, 'level5') !== false) {
      $css = 'level-5';
   } else if (strpos($cat, 'level6') !== false) {
      $css = 'level-6';
   }

   return 'cat-' . $css;
}

if (strpos($_SERVER['HTTP_REFERER'], '?p=subject_category') !== false) {
   $_SESSION['back_url'] = $_SERVER['HTTP_REFERER'];
}

?>

<a id="back_stock" href="<?php if(isset($_SESSION['back_url'])) { echo $_SESSION['back_url']; } else { echo 'index.php?p=subject_category'; } ?>">                 
         <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>
<span>
   <span class="page_title"><img src="images/title_Icons/Order.png">Order</span>
</span>

<style>
.uk-button:hover, .uk-button:focus {
    background-color: #fdba0e73;
}
.cat-pre-level-1 {
    background-color: #ebeff8 !important;
}

   .cat-level-1 {
      background-color: #f03237 !important
   }

   .cat-level-2 {
      background-color: #f98e2d !important
   }

   .cat-level-3 {
      background-color: #fbf836 !important
   }

   .cat-level-4 {
      background-color: #30be44 !important
   }

   .cat-level-5 {
      background-color: #303bf4 !important
   }

   .cat-level-6 {
      background-color: #a1887f !important
   }

   p {
      font-size: 12px;
   }
</style>

<script>
   $(document).ready(function() {
      $('button[id^="product-qty-add-"]').on('click', function() {
         var rowId = $(this).data('row');
         var $input = $('#product-qty-' + rowId);

         $input.val(parseInt($input.val()) + 1);
      });

      $('button[id^="product-qty-minus-"]').on('click', function() {
         var rowId = $(this).data('row');
         var $input = $('#product-qty-' + rowId);

         var value = parseInt($input.val()) - 1;

         if (value <= 1) {
            value = 1;
         }

         $input.val(value);
      });
   });

   function add2Cart(product_id, row_id) {
      if (product_id != "") {
         var product_qty = $('#product-qty-' + row_id).val();

         $.ajax({
            url: "admin/add_2_cart.php",
            type: "POST",
            data: "product_id=" + product_id + "&product_qty=" + product_qty,
            dataType: "text",
            beforeSend: function(http) {},
            success: function(response, status, http) {
               UIkit.notify(response);
               getNoInCart();
            },
            error: function(http, status, error) {
               UIkit.notification("Error:" + error);
            }
         });
      }
   }

   function getNoInCart() {
      $.ajax({
         url: "admin/get_no_in_cart.php",
         type: "POST",
         data: "1=1",
         dataType: "text",
         beforeSend: function(http) {},
         success: function(response, status, http) {
            $("#no_in_cart").replaceWith("<span id=\"no_in_cart\" class=\"badge badge-pill badge-danger\">" + response + "</span>");
            $("#no_in_cart_mnu").replaceWith("<span id=\"no_in_cart_mnu\" class=\"badge badge-pill badge-danger\">" + response + "</span>");
         },
         error: function(http, status, error) {
            UIkit.notification("Error:" + error);
         }
      });
   }
</script>

<?php
include_once("search_new.php");
include_once("mysql.php");
include_once("lib/pagination/pagination.php");

if ($_SESSION["isLogin"] == 1) {
   if ((($_SESSION["UserType"] == "O") || ($_SESSION["UserType"] == "A")) & ($_SESSION["isLogin"] == 1) & (hasRightGroupXOR($_SESSION["UserName"], "OrderEdit"))) {
      $v = $_GET["v"];
      $pn = $_GET["pn"];
      $pg = $_GET["pg"];
      $category = $_GET["category"];
      $sub_category = $_GET["sub_category"];
      $sub_sub_category = $_GET["sub_sub_category"];
      $category_id= $_GET["category-id"];
      if ($pg == "") {
         $pg = "1";
      }

      //       $url="index.php?p=purchasing&v=$v&pn=$pn&category=$category&subcategory=$subcategory";
?>
      <script>
         $(document).ready(function() {
            // $("#category").change(function () {
            //    var category=$("#category").val();
            //    var subcategory=$("#sub_category").val();

            //    $.ajax({
            //       url : "admin/getSubCategory.php",
            //       type : "POST",
            //       data : "category="+category+"&subcategory="+subcategory,
            //       dataType : "text",
            //       beforeSend : function(http) {
            //       },
            //       success : function(response, status, http) {
            //          $("#sub_category").html(response);
            //       },
            //       error : function(http, status, error) {
            //          UIkit.notify("Error:"+error);
            //       }
            //    });
            // });
         });

         $(document).ready(function() {
            $("#sub_category").change(function() {
               var subcategory = $("#sub_category").val();
               var subsubcategory = $("#sub_sub_category").val();

               $.ajax({
                  url: "admin/getSubSubCategory.php",
                  type: "POST",
                  data: "subcategory=" + subcategory + "&subsubcategory=" + subsubcategory,
                  dataType: "text",
                  beforeSend: function(http) {},
                  success: function(response, status, http) {
                     //            UIkit.notify(response);
                     $("#sub_sub_category").html(response);
                  },
                  error: function(http, status, error) {
                     UIkit.notify("Error:" + error);
                  }
               });
            });
         });

         $(document).ready(function() {
            $("#btnClear").click(function() {
               $("#term").val("");
               $("#sub_sub_category").val("");
               $("#sub_company").val("");
               $("#sub_category").val("");
               $("#pn").val("");
               $("#category-id").val("");
               $("#frmPurchasing").submit();
            });
         });
		 
		function changeSubCompany(subCompany)
		{
			var module = '';
			
			if(subCompany == 'Foundation Materials')
			{
				module += '<option value="">Module</option><option value="ED1">ED1</option><option value="ED2">ED2</option><option value="ED3">ED3</option><option value="ED4">ED4</option><option value="M01">M01</option><option value="M02">M02</option><option value="M03">M03</option><option value="M04">M04</option><option value="M05">M05</option><option value="M06">M06</option><option value="M07">M07</option><option value="M08">M08</option><option value="M09">M09</option><option value="M10">M10</option><option value="M11">M11</option><option value="M12">M12</option>';
			}
			else if(subCompany == 'Zhi Hui')
			{
				module += '<option value="">Module</option><option value="M05">M05</option><option value="M06">M06</option><option value="M07">M07</option><option value="M08">M08</option><option value="M09">M09</option><option value="M10">M10</option><option value="M11">M11</option><option value="M12">M12</option>';
			}else{
				
				
			}
			if(subCompany == 'Foundation Materials' || subCompany == 'Zhi Hui')
			{
				$('#sub_sub_category').hide();
				$('#term').hide();
			}else{
				
				$('#sub_sub_category').show();
				$('#term').show();
			}
			$('#module').html(module);
			
			if(module != '') {
				$('#module').show();
			} else {
				$('#module').hide();
			}
		}

      </script>

      <div class="uk-margin-top uk-margin-right">
         <form class="uk-form" method="get" name="frmPurchasing" id="frmPurchasing">
            <input type="hidden" name="p" id="p" value="<?php echo $_GET['p'] ?>">
            <div class="uk-panel">
               <div class="uk-panel-box d-flex justify-content-center">
                  <div>
                     <!--
             <select name="category" id="category">
               <option value="">Company</option>
<?php
      $sql = "SELECT * from codes where module='CATEGORY' and parent='' order by code";
      $result = mysqli_query($connection, $sql);
      while ($row = mysqli_fetch_assoc($result)) {
?>
               <option value="<?php echo $row['code'] ?>" <?php if ($category == $row["code"]) {
                                                               echo "selected";
                                                            } ?>><?php echo $row['code'] ?></option>
<?php
      }
?>
            </select>
 -->
                     <!--
            <select name="sub_category" id="sub_category">
<?php
      if ($category != "") {
         $sql = "SELECT * from codes where module='CATEGORY' and parent='$category' order by code";
         $result = mysqli_query($connection, $sql);
?>
               <option value="">Subject</option>
<?php
         while ($row = mysqli_fetch_assoc($result)) {
?>
               <option value="<?php echo $row['code'] ?>" <?php if ($sub_category == $row['code']) {
                                                               echo "selected";
                                                            } ?>><?php echo $row['code'] ?></option>
<?php
         }
?>
<?php
      } else {
?>
               <option value="">Subject</option>
<?php
      }
?>
            </select>
-->
<input type="text" name="pn" id="pn" placeholder="Product Name/Code" value="<?php echo $_GET['pn']?>" />

                  <?php if(isset($_GET['category-id']) && $_GET['category-id'] == 81) { ?>

                     <select name="sub_company" id="sub_company" onchange="changeSubCompany(this.value)" >
                        <option value="">Products</option>
                        <option value="Colour Paper" <?php if ($_GET["sub_company"] == 'Colour Paper') { echo "selected"; } ?>>Colour Paper</option>
                        <option value="Crepe Paper" <?php if ($_GET["sub_company"] == 'Crepe Paper') { echo "selected"; } ?>>Crepe Paper</option>
						<option value="Others" <?php if ($_GET["sub_company"] == 'Others') { echo "selected"; } ?>>Others (Glue, Drawing Block Paper)</option>
                        <option value="Books" <?php if ($_GET["sub_company"] == 'Books') { echo "selected"; } ?>>Fruitful Guide Book, Attendance Book, Teacher Record Book</option>
                        <option value="Report Book" <?php if ($_GET["sub_company"] == 'Report Book') { echo "selected"; } ?>>Report Book</option>
                        <option value="Flyers" <?php if ($_GET["sub_company"] == 'Flyers') { echo "selected"; } ?>>Flyers</option>
                        <option value="Stackable Bed" <?php if ($_GET["sub_company"] == 'Stackable Bed') { echo "selected"; } ?>>Stackable Bed</option>
                        <option value="Wooven Bag" <?php if ($_GET["sub_company"] == 'Wooven Bag') { echo "selected"; } ?>>Wooven Bag</option>
                        <option value="Foundation Materials" <?php if ($_GET["sub_company"] == 'Foundation Materials') { echo "selected"; } ?>>Foundation Materials</option>
                        <option value="Zhi Hui" <?php if ($_GET["sub_company"] == 'Zhi Hui') { echo "selected"; } ?>>Zhi Hui</option>
                     </select>
					 
                  <?php } else { ?> 
                     
                     <select name="sub_company" id="sub_company">
                        <option value="">Products</option>
						      <option value="Marketing Set" <?php if ($_GET["sub_company"] == 'Marketing Set') { echo "selected"; } ?>>Marketing Set</option>
                        <option value="Student Materials" <?php if ($_GET["sub_company"] == 'Student Materials') { echo "selected"; } ?>>Student Materials</option>
                        <option value="Blank Form for Non-Listed Items" <?php if ($_GET["sub_company"] == 'Blank Form for Non-Listed Items') { echo "selected"; } ?>>Blank Form for Non-Listed Items</option>
                        <option value="Furniture & Equipments" <?php if ($_GET["sub_company"] == 'Furniture & Equipments') { echo "selected"; } ?>>Furniture & Equipments</option>
                        <option value="Corporate Identity" <?php if ($_GET["sub_company"] == 'Corporate Identity') { echo "selected"; } ?>>Corporate Identity</option>
                        <option value="Indoor Corporate Identity" <?php if ($_GET["sub_company"] == 'Indoor Corporate Identity') { echo "selected"; } ?>>Indoor Corporate Identity</option>
                        <option value="Student Attire" <?php if ($_GET["sub_company"] == 'Student Attire') { echo "selected"; } ?>>Student Attire</option>
                        <option value="Administrative Materials" <?php if ($_GET["sub_company"] == 'Administrative Materials') { echo "selected"; } ?>>Administrative Materials</option>
                        <option value="Promotional Items" <?php if ($_GET["sub_company"] == 'Promotional Items') { echo "selected"; } ?>>Promotional Items</option>
                        <option value="Security Deposits" <?php if ($_GET["sub_company"] == 'Security Deposits') { echo "selected"; } ?>>Security Deposits and Requisition Forms</option>
                        <option value="Crepe Paper" <?php if ($_GET["sub_company"] == 'Crepe Paper') { echo "selected"; } ?>>Crepe Paper</option>
                        <option value="Coloured Paper" <?php if ($_GET["sub_company"] == 'Coloured Paper') { echo "selected"; } ?>>Coloured Paper</option>
                        <option value="Materials" <?php if ($_GET["sub_company"] == 'Materials') { echo "selected"; } ?>>Materials</option>
                     </select>

                  <?php } ?>

					<?php 
						$module = isset($_GET['module']) ? $_GET['module'] : ''; 
						$sub_company = isset($_GET['sub_company']) ? $_GET['sub_company'] : '';
					?>
				  
					<select name="module" id="module" style="<?php if(isset($_GET['category-id']) && $_GET['category-id'] == 81 && ($sub_company == 'Foundation Materials' || $sub_company == 'Zhi Hui')) { echo "display:inline-block;"; } else { echo "display:none;"; } ?>" >
                        <option value="">Module</option>
						<?php if($sub_company == 'Foundation Materials') { ?>
						
							<option <?php if ($module == 'ED1') { echo "selected"; } ?> value="ED1">ED1</option>
							<option <?php if ($module == 'ED2') { echo "selected"; } ?> value="ED2">ED2</option>
							<option <?php if ($module == 'ED3') { echo "selected"; } ?> value="ED3">ED3</option>
							<option <?php if ($module == 'ED4') { echo "selected"; } ?> value="ED4">ED4</option>
							<option <?php if ($module == 'M01') { echo "selected"; } ?> value="M01">M01</option>
							<option <?php if ($module == 'M02') { echo "selected"; } ?> value="M02">M02</option>
							<option <?php if ($module == 'M03') { echo "selected"; } ?> value="M03">M03</option>
							<option <?php if ($module == 'M04') { echo "selected"; } ?> value="M04">M04</option>
							<option <?php if ($module == 'M05') { echo "selected"; } ?> value="M05">M05</option>
							<option <?php if ($module == 'M06') { echo "selected"; } ?> value="M06">M06</option>
							<option <?php if ($module == 'M07') { echo "selected"; } ?> value="M07">M07</option>
							<option <?php if ($module == 'M08') { echo "selected"; } ?> value="M08">M08</option>
							<option <?php if ($module == 'M09') { echo "selected"; } ?> value="M09">M09</option>
							<option <?php if ($module == 'M10') { echo "selected"; } ?> value="M10">M10</option>
							<option <?php if ($module == 'M11') { echo "selected"; } ?> value="M11">M11</option>
							<option <?php if ($module == 'M12') { echo "selected"; } ?> value="M12">M12</option>
						
						<?php } else if($sub_company == 'Zhi Hui') { ?>
						
							<option <?php if ($module == 'M05') { echo "selected"; } ?> value="M05">M05</option>
							<option <?php if ($module == 'M06') { echo "selected"; } ?> value="M06">M06</option>
							<option <?php if ($module == 'M07') { echo "selected"; } ?> value="M07">M07</option>
							<option <?php if ($module == 'M08') { echo "selected"; } ?> value="M08">M08</option>
							<option <?php if ($module == 'M09') { echo "selected"; } ?> value="M09">M09</option>
							<option <?php if ($module == 'M10') { echo "selected"; } ?> value="M10">M10</option>
							<option <?php if ($module == 'M11') { echo "selected"; } ?> value="M11">M11</option>
							<option <?php if ($module == 'M12') { echo "selected"; } ?> value="M12">M12</option>
						
						<?php } ?>
                     </select> 
                     <select name="term" id="term" >
                        <option value="">Term</option>
                        <?php  
                           $term_list = mysqli_query($connection,"SELECT `term_num` FROM `schedule_term` WHERE `year` = '".$_SESSION['Year']."' AND `centre_code` = '".$_SESSION['CentreCode']."' AND `deleted` = '0'");

                           while($term_row = mysqli_fetch_array($term_list))
                           {
                        ?>
                              <option value="<?php echo $term_row['term_num']; ?>" <?php if ($_GET["term"] == $term_row['term_num']) { echo "selected"; } ?> ><?php echo $term_row['term_num']; ?></option>
                        <?php
                           }
                        ?>
                     </select>

                     <select name="sub_sub_category" id="sub_sub_category">
						      <option value="">Level</option>
                        <option value="EDP" <?php if ($_GET["sub_sub_category"] == 'EDP') { echo "selected"; } ?>>EDP</option>
                        <option value="QF1" <?php if ($_GET["sub_sub_category"] == 'QF1') { echo "selected"; } ?>>QF1</option>
                        <option value="QF2" <?php if ($_GET["sub_sub_category"] == 'QF2') { echo "selected"; } ?>>QF2</option>
                        <option value="QF3" <?php if ($_GET["sub_sub_category"] == 'QF3') { echo "selected"; } ?>>QF3</option> 
                     </select> 
                     <!-- <?php
                           if ($sub_category != "") {
                              $sql = "SELECT * from codes where module='CATEGORY' and parent='$sub_category' order by code";
                              $result = mysqli_query($connection, $sql);
                           ?>
                           <option value="">Level</option>
                           <?php
                              while ($row = mysqli_fetch_assoc($result)) {
                           ?>
                              <option value="<?php echo $row['code'] ?>" <?php if ($sub_sub_category == $row['code']) {
                                                                              echo "selected";
                                                                           } ?>><?php echo $row['code'] ?></option>
                           <?php
                              }
                           ?>
                        <?php
                           } else {
                        ?>
                           <option value="">Level</option>
                        <?php
                           }
                        ?> -->
                     </select>
					 <input type="hidden" name="category-id" id="category-id" value="<?php echo $_GET['category-id']?>" >
                     <!-- <input name="pn" id="pn" placeholder="Product Name/Code" value="<?php echo $pn ?>" size="25"> -->
                     <button id="btnSearch" class="uk-button blue_button">Search</button>
                     <a id="btnClear" class="uk-button blue_button">Clear</a>
                  </div>
               </div>
            </div>
         </form>
         <div class="uk-container" style="margin: 0 auto;">
            <!-- <div class="uk-container-center">

               <small class="pl-2">Legend:</small>
               <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center mb-3">
                  <div class="pt-2 pb-2 pl-2">
                     <div class="d-flex flex-row align-items-center">
                        <div class="cat-pre-level-1" style="width: 30px; height: 30px; border: 1px solid #ccc"></div>
                        <div class="pl-3 font-weight-bold">Pre-Level 1</div>
                     </div>
                  </div>

                  <div class="pt-2 pb-2 pl-3">
                     <div class="d-flex flex-row align-items-center">
                        <div class="cat-level-1" style="width: 30px; height: 30px; border: 1px solid #ccc"></div>
                        <div class="pt-2 pb-2 pl-3 font-weight-bold">Level 1</div>
                     </div>
                  </div>

                  <div class="pt-2 pb-2 pl-3">
                     <div class="d-flex flex-row align-items-center">
                        <div class="cat-level-2" style="width: 30px; height: 30px; border: 1px solid #ccc"></div>
                        <div class="pt-2 pb-2 pl-3 font-weight-bold">Level 2</div>
                     </div>
                  </div>

                  <div class="pt-2 pb-2 pl-3">
                     <div class="d-flex flex-row align-items-center">
                        <div class="cat-level-3" style="width: 30px; height: 30px; border: 1px solid #ccc"></div>
                        <div class="pt-2 pb-2 pl-3 font-weight-bold">Level 3</div>
                     </div>
                  </div>

                  <div class="pt-2 pb-2 pl-3">
                     <div class="d-flex flex-row align-items-center">
                        <div class="cat-level-4" style="width: 30px; height: 30px; border: 1px solid #ccc"></div>
                        <div class="pt-2 pb-2 pl-3 font-weight-bold">Level 4</div>
                     </div>
                  </div>

                  <div class="pt-2 pb-2 pl-3">
                     <div class="d-flex flex-row align-items-center">
                        <div class="cat-level-5" style="width: 30px; height: 30px; border: 1px solid #ccc"></div>
                        <div class="pl-3 font-weight-bold">Level 5</div>
                     </div>
                  </div>


               </div>
            </div> -->

               <?php
			   $term = $_GET["term"];
            $sub_sub_category = $_GET["sub_sub_category"];
            $pn = $_GET["pn"];
			
			   //echo $term;
               $base_sql = "SELECT *, SUBSTRING_INDEX(trim(lower(sub_sub_category)), 'level', -1) as trimmed_level from product";
               $v_token = ConstructToken("vendor", $v, "=");
               $pn_token = ConstructTokenGroup("product_name", "%$pn%", "like", "product_code", "%$pn%", "like", "or");
               $cat_token = ConstructToken("category", $category, "=");

               if ($_GET["category-id"] != "112"){
                  $cat_term = ConstructToken("term", $term, "=");
               }else{
                  $cat_term = ConstructToken("term", $term, "=");
               
               }

               $subcat_token = ConstructToken("sub_category", $sub_category, "=");

               if ($_GET["category-id"] != "112"){
                  $subsubcat_token = ConstructToken("sub_sub_category", $sub_sub_category, "=");
               }else{
                  //Temporary Hardcode for STEM
                  if ($sub_sub_category == "EDP" || $sub_sub_category == "QF1" ){
                     $subsubcat_token = "(sub_sub_category = 'EDP' OR sub_sub_category = 'QF1')";
                  }else if ($sub_sub_category == "QF2" || $sub_sub_category == "QF3" ){
                     $subsubcat_token = "(sub_sub_category = 'QF2' OR sub_sub_category = 'QF3')";
                  }
               }

               $category_id = ConstructToken("category_id", $category_id, "=");
               $final_token = ConcatToken($v_token, $pn_token, "and");
               $final_token = ConcatToken($final_token, $cat_token, "and");
               $final_token = ConcatToken($final_token, $subcat_token, "and");
               $final_token = ConcatToken($final_token, $subsubcat_token, "and");
               $final_token = ConcatToken($final_token, $category_id, "and");
               $final_token = ConcatToken($final_token, $cat_term, "and");

               $final_sql = ConcatWhere($base_sql, $final_token);
			   
               if($_GET["category-id"] == 81)
               {
                  $final_sql .= " AND `product_name` NOT LIKE '%Courier%' ";
				  
				  if($_GET["sub_company"] != '')
				  {
					  if($sub_company == 'Others')
					  {
						$final_sql .= " AND (`product_name` LIKE '%Glue%' OR `product_name` LIKE '%Drawing Block Paper%')";
					  }
					  else if($sub_company == 'Books')
					  {
						$final_sql .= " AND (`product_name` LIKE '%Fruitful Guide Book%' OR `product_name` LIKE '%Attendance Book%' OR `product_name` LIKE '%Teachers Record Book%')";
					  }
					  else
					  {
						if($sub_company != 'Foundation Materials'){
							$final_sql .= " AND `product_name` LIKE '%".$sub_company."%' ";
						}
					  }
					  
					  if($module != '' && $sub_company == 'Foundation Materials')
					  {
						$final_sql .= " AND `product_name` LIKE '%".$module."%' ";
					  }else{
						  $final_sql .= " AND `product_code` LIKE '%".$module."%' ";
					  }
					  
				  }
               }
			   
               if ($_GET["category-id"] == "112"){
               //echo $final_sql;
               }
               $result = mysqli_query($connection, $final_sql);
               $num_row = mysqli_num_rows($result);

               $numperpage = 20;
               $query = "p=" . $_GET["p"] . "&v=$v&pn=$pn&category-id=" . $_GET["category-id"] . "&sub_category=$sub_category&sub_sub_category=$sub_sub_category";

               $pagination = getPagination($pg, $numperpage, $query, $final_sql, $start_record, $num_row);
               //echo $final_sql."<br><br>";
               //$final_sql .= " ORDER BY locate('pre-level', lower(sub_sub_category)) desc, trim(SUBSTRING_INDEX(lower(sub_sub_category), 'level', -1)), product_name";

               

               $final_sql .= " ORDER BY term, locate('pre-level', lower(sub_sub_category)) desc, trim(SUBSTRING_INDEX(lower(sub_sub_category), 'level', -1)), product_name";
               $final_sql .= " limit $start_record, $numperpage";
               //echo $final_sql."<br><br>";
               $final_result = mysqli_query($connection, $final_sql);
               $final_num_row = mysqli_num_rows($final_result);
               $level = '';
               //echo $pagination;
               if ($final_num_row > 0) {
               ?>
                  <div class="container display_n">
                     <div class="row">
                        <?php
                        if ($_GET["category-id"] == "112"){
                           echo '<h3 style="width:100%"><center><b>Notice:</b> STEM Bundle includes: Student Kit + Materials. For marketing set book purchases, please navigate to <b><a href="index.php?p=purchasing&category-id=111&term=&sub_sub_category=">Form 1C</a></b> instead.</center></h3>';
                        }
                        while ($row = mysqli_fetch_assoc($final_result)) {
                           if ($level == '') {
                              $level = getLevelCss($row["sub_sub_category"]);
                              //echo '<h3 style="width:100%">' . ucwords(str_replace('level-', 'level ', substr($level, 4))) . '</h3>';
                           }
                           if ($level != getLevelCss($row["sub_sub_category"])) {
                              echo '<hr style="width:100%; color: #222;">';

                              $level = getLevelCss($row["sub_sub_category"]);
                              echo '<h3 style="width:100%">' . ucwords(str_replace('level-', 'level ', substr($level, 4))) . '</h3>';
                           }

                        ?>

                           <div class="col-xl-3 col-lg-4 col-md-6 qdees-4-element">
                              <div class="qdees-product <?php echo getLevelCss($row["sub_sub_category"]); ?>">
                                 <a target="_blank" href="admin/uploads/<?php echo $row["product_photo"] ?>"><img class="p_img img-fluid" src="admin/uploads/<?php echo $row["product_photo"] ?>" alt="<?php echo $row["product_name"]; ?>"></a>
                                 <div class="product-desc <?php echo getLevelCss($row["sub_sub_category"]); ?>">
                                    <h4 class="font-weight-bold text-black"><?php echo $row["product_name"]; ?></h4>
                                    <!-- <p style="opacity: .8"><?php //echo 'k' . $row["trimmed_level"] . $row["product_code"]; ?></p> -->
                                        <p style="opacity: .8"><?php $product_code = $row["product_code"];$product_code = explode("((--",$product_code)[0];echo $product_code; ?><br />
                                       <?php echo $row["remarks"]; ?></p>
                                    <div class="uk-flex uk-flex-space-between">
                                       <div style="font-size: 15px"><b><?php echo number_format($row["retail_price"], 2); ?></b></div>
                                       <?php
                                       if (hasRightGroupXOR($_SESSION["UserName"], "OrderEdit")) {
                                       ?>

                                          <div id="product-qty-wrapper-<?php echo $row['id']; ?>" style="text-align: right;">
                                             <button class="uk-button" id="product-qty-minus-<?php echo $row['id']; ?>" data-row="<?php echo $row['id']; ?>" style="margin-top: -3px">-</button>
                                             <input class="uk-input" id="product-qty-<?php echo $row['id']; ?>" style="width: 20%; margin: 3px 10px 0" value="1">
                                             <button class="uk-button" id="product-qty-add-<?php echo $row['id']; ?>" data-row="<?php echo $row['id']; ?>" style="margin-top: -3px">+</button>
                                          </div>
                                    </div>
                                    <div class="uk-margin-top">
                                       <a class="uk-button uk-width-1-1 qdees-button" style="margin: 0 auto" data-uk-tooltip="{pos:top}" title="Add to Cart" onclick="add2Cart('<?php echo $row['product_code'] ?>', '<?php echo $row['id']; ?>')"><span>Add to Cart</span></a>
                                    </div>
                                 <?php
                                       }
                                 ?>
                                 </div>
                              </div>
                           </div>
                        <?php
                        }
                        ?>
                     </div>
                  </div>
               <?php
               } else {
               ?>
                  <div class='uk-margin-top uk-margin-right'>
                     <div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>No record found</div>
                  </div>
               <?php
               }
               ?>
            </div>
         </div>
         <?php
         echo $pagination;
         ?>
      </div>
      <script>
         $(document).ready(function() {
            $("#btnClear").click(function() {

            });
         });
		if("<?php echo $_GET['sub_company'] ?>" == 'Foundation Materials' || "<?php echo $_GET['sub_company'] ?>"  == 'Zhi Hui')
		{
			$('#sub_sub_category').hide();
			$('#term').hide();
		}else{
			
			$('#sub_sub_category').show();
			$('#term').show();
		}
      </script>
<?php
   } else {
      echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
   }
} else {
   header("Location: index.php");
}
?>
<style>
   /* .btn-qdees,
   .display_n {
      display: none;
   } */
   /* .btn-qdees1 {
      display: none;
   } */

   .pl_2_Q {
      text-align: center;
      font-size: 18px;
   }

   .qdessholdings {
      width: 30%;
   }

   .qdessholdings a.qdessholdings_box {
      background-color: #77d7f3;
      padding: 37px;
      margin: 29px;
      text-align: center;
      border-radius: 20px;
      display: flex;
      height: 75%;
      justify-content: center;
   }

   .one_box1 {
      font-weight: bold;
   }

   span.one_box1 {
      text-transform: uppercase;
      font-size: 13px;
      font-weight: bold;
   }

   span.low {
      text-transform: lowercase;
   }
   .product-desc h4 {
    height: 80px;
    text-align: center;
    font-size: 19px;
}
.product-desc p {
    height: 50px;
    text-align: center;
}
.qdees-product .product-desc {
    border-radius: 0 0 10px 10px;
}
.qdees-product {
    /* background-color: #feebee; */
    border-radius: 10px;
}
.p_img{
   border-radius: 10px 10px 0 0 ;
}
.cat-pre-level-1 {
    background-color: #ebeff8 !important;
}
</style>