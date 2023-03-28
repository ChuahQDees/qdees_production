
<style type="text/css">
   #mydatatable_length {
      display: none;
   }

   #mydatatable_filter {
      display: none;
   }

   /*#mydatatable_paginate{float:initial;text-align:center}*/
   #mydatatable_paginate .paginate_button {
      display: inline-block;
      min-width: 16px;
      padding: 3px 5px;
      line-height: 20px;
      text-decoration: none;
      -moz-box-sizing: content-box;
      box-sizing: content-box;
      text-align: center;
      background: #f7f7f7;
      color: #444444;
      border: 1px solid rgba(0, 0, 0, 0.2);
      border-bottom-color: rgba(0, 0, 0, 0.3);
      background-origin: border-box;
      background-image: -webkit-linear-gradient(top, #ffffff, #eeeeee);
      background-image: linear-gradient(to bottom, #ffffff, #eeeeee);
      text-shadow: 0 1px 0 #ffffff;
      margin-left: 3px;
      margin-right: 3px
   }

   #mydatatable_paginate .paginate_button.current {
      background: #009dd8;
      color: #ffffff !important;
      border: 1px solid rgba(0, 0, 0, 0.2);
      border-bottom-color: rgba(0, 0, 0, 0.4);
      background-origin: border-box;
      background-image: -webkit-linear-gradient(top, #00b4f5, #008dc5);
      background-image: linear-gradient(to bottom, #00b4f5, #008dc5);
      text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.2);
   }

   #mydatatable_filter {
      width: 100%
   }

   #mydatatable_filter label {
      width: 100%;
      display: inline-flex
   }

   #mydatatable_filter label input {
      height: 30px;
      width: 100%;
      padding: 4px 6px;
      border: 1px solid #dddddd;
      background: #ffffff;
      color: #444444;
      -webkit-transition: all linear 0.2s;
      transition: all linear 0.2s;
      border-radius: 4px;
   }
</style>

<?php

if ($_SESSION["isLogin"] == 1) {
   if (($_SESSION["UserType"] == "S" || $_SESSION["UserType"] == "H" || $_SESSION["UserType"] == "C" || $_SESSION["UserType"] == "R" || $_SESSION["UserType"] == "CM" || $_SESSION["UserType"] == "T") & (hasRightGroupXOR($_SESSION["UserName"], "ProductEdit|ProductView"))) {
      include_once("mysql.php");
      include_once("lib/pagination/pagination.php");
      include_once("admin/functions.php");
      $p = $_GET["p"];
      $m = $_GET["m"];
      $get_sha1_id = $_GET["id"];
      $pg = $_GET["pg"];
      $mode = $_GET["mode"];

      if ($mode == "") {
         $mode = "ADD";
      }

      include_once("product_func.php");
	  
	  function isDilitable($product_code) {
            
	   global $connection;
	   $sql = "SELECT * from `order` where product_code = '$product_code'";
	   //echo "$sql"; die;
	   $result = mysqli_query($connection, $sql);
	   $num_row = mysqli_num_rows($result);

	   if ($num_row > 0) {
		  return false;
	   } else {
		  return true;
	   }
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
      <!--<a href="index.php?p=order_status_pg1">                 
             <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>-->
      <style>
         .page_title {
            position: absolute;
            right: 34px;
         }

         .uk-margin-right {
            margin-top: 40px;
         }

         table#mydatatable tr td {
            text-align: center;
         }
		.form_1{
			padding: 9px 15px;
			border-radius: 10px;
			box-shadow: 0px 2px 3px 0px #00000021 !important;
			font-size: 1.2rem;
			font-weight: bold;
			background: #fff;
			cursor: pointer;
		}
		.form_2{
			padding: 9px 15px;
			border-radius: 10px;
			box-shadow: 0px 2px 3px 0px #00000021 !important;
			font-size: 1.2rem;
			font-weight: bold;
			background: #fff;
		}
</style>      
	<a href="./index.php?p=product_category"><span  id="form_1" class="form_1">Form</span></a>            
	<a style="margin-right: 82%;" href="./index.php?p=product"><span id="form_2" class="form_2">Product</span></a>
	
      <span>
         <span class="page_title"><img src="/images/title_Icons/Visitor Reg.png">Product (SKU)</span>
      </span>
      <div class="uk-margin-right">
         <div class="uk-width-1-1 myheader">
            <h2 class="uk-text-center myheader-text-color">Product Information</h2>
         </div>
         <div class="uk-form uk-form-small">
            <?php
            if (($_SESSION["UserType"] == "S" || $_SESSION["UserType"] == "H" || $_SESSION["UserType"] == "C" || $_SESSION["UserType"] == "R" || $_SESSION["UserType"] == "CM" || $_SESSION["UserType"] == "T") & (hasRightGroupXOR($_SESSION["UserName"], "ProductEdit"))) {
            ?>
               <form name="frmProduct" id="frmProduct" method="post" class="uk-form uk-form-small" action="index.php?p=product&pg=<?php echo $pg ?>&mode=SAVE" enctype="multipart/form-data">
               <?php
            }
               ?>
               <div class="uk-grid">
                  <div class="uk-width-medium-5-10">
                     <table class="uk-table uk-table-small">
                        <tr class="uk-text-small">
                           <td class="uk-width-3-10 uk-text-bold">Country<span class="text-danger">*</span>:</td>
                           <td class="uk-width-7-10">
                              <select name="country" id="country" class="uk-width-1-1">
                                 <option value="">Select</option>
                                 <?php
                                 $sql = "SELECT * from codes where module='COUNTRY' order by code";
                                 $result = mysqli_query($connection, $sql);
                                 while ($row = mysqli_fetch_assoc($result)) {
                                 ?>
                                    <option value="<?php echo $row["code"] ?>" <?php if ($row["code"] == $edit_row["country"]) {
                                                                                    echo "selected";
                                                                                 } ?>><?php echo $row["code"] ?></option>
                                 <?php
                                 }
                                 ?>
                              </select>

                              <span id="validationCountry" style="color: red; display: none;">Please select Country</span>
                           </td>
                        </tr>
                        <tr class="uk-text-small">
                           <td class="uk-width-3-10 uk-text-bold">State<span class="text-danger">*</span>:</td>
                           <td class="uk-width-7-10">
                              <select name="state[]" id="state" class="uk-width-1-1" multiple>
                                 <?php
                                 $stateArray = explode(', ', $edit_row['state']);
                                 if ($edit_row["country"] != "") {
                                 ?>
                                    <?php
                                    $sql = "SELECT * from codes where country='" . $edit_row["country"] . "' and module='STATE' order by code";
                                    $result = mysqli_query($connection, $sql);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                       <option value="<?php echo $row['code'] ?>" <?php if (in_array($row["code"], $stateArray)) {
                                                                                       echo "selected";
                                                                                    } ?>><?php echo $row["code"] ?></option>
                                    <?php
                                    }
                                 } else {
                                    ?>
                                    <option value="">Please Select Country First</option>
                                 <?php
                                 }
                                 ?>

                              </select>

                              <span id="validationState" style="color: red; display: none;">Please select State</span>
                           </td>
                        </tr>
                         <tr class="uk-text-small">
							  <td class="uk-width-3-10 uk-text-bold">Company Name<span class="text-danger">*</span> : </td>
							  <td class="uk-width-7-10">
								 <select name="company_code" id="company_code" class="uk-form-small uk-width-1-1">
									 <option value="">Select</option>
											 <?php
											$sql = "SELECT * from codes where module='CATEGORY' and parent='' and code !=''";
											//echo $sql; die;
											 $result = mysqli_query($connection, $sql);
											 while ($row = mysqli_fetch_assoc($result)) {
											 ?>
												<option value="<?php echo $row['use_code'] ?>" <?php if ($row['code'] == $edit_row['category']) {
																								echo "selected";
																							 } ?>><?php echo $row['code'] ?></option>
											 <?php
											 }
											 ?>
											
								 </select>
								  <span id="validationCompanyName"  style="color: red; display: none;">Please select Company Name</span>
							  </td>
						   </tr>
						<script>
                           $(document).ready(function() {
							   company();
                              $("#company_code").change(function() {
                                company();
                              });
                           });
						   function company(){
							    var company_code = $("#company_code option:selected").text();
								 var category_id = '<?php echo $edit_row['category_id']; ?>';
                                 $.ajax({
                                    url: "admin/get_product_category_name.php",
                                    type: "POST",
                                    data: "company_code=" + company_code,
                                    dataType: "text",
                                    beforeSend: function(http) {},
                                    success: function(response, status, http) {
                                       if (response != "") {
                                          $("#category_id").html(response);
										  $("#category_id").val(category_id);
                                       } else {
                                          $("#category_id").html("<option value=''>Please select a Product Category</option>");
                                          //UIkit.notify("No Product Category found");
                                       }
                                    },
                                    error: function(http, status, error) {
                                       UIkit.notify("Error:" + error);
                                    }
                                 });
						   }
                        </script>
						<tr class="uk-text-small">
                           <td class="uk-width-3-10 uk-text-bold">Form Name<span class="text-danger">*</span> : </td>
                           <td class="uk-width-7-10">							  
							  <select name="category_id" id="category_id" class="uk-form-small uk-width-1-1">
                                 <option value="">Select</option>
								  <?php
									 $sql = "SELECT * from product_category";
									 $result = mysqli_query($connection, $sql);
									 while ($row = mysqli_fetch_assoc($result)) {
									 ?>
										<option value="<?php echo $row['id'] ?>" <?php if ($row['id'] == $edit_row['category_id']) {
																						   echo "selected";
																						} ?>><?php echo $row['category_name'] ?></option>
									 <?php
									 }
									 ?>
                              </select>
							  <span id="validationCategoryId" style="color: red; display: none;">Please select a form</span>
                           </td>
                        </tr>
                       
                        <script>
                           $(document).ready(function() {
                              $("#country").change(function() {
                                 var country = $("#country").val();
                                 $.ajax({
                                    url: "admin/get_state_not_select.php",
                                    type: "POST",
                                    data: "country=" + country,
                                    dataType: "text",
                                    beforeSend: function(http) {},
                                    success: function(response, status, http) {
                                       if (response != "") {
                                          $("#state").html(response);
                                       } else {
                                          $("#state").html("<select name='state' id='state' class='uk-width-1-1'><option value=''>Please select a country</option></select>");
                                          UIkit.notify("No state found in " + country);
                                       }
                                    },
                                    error: function(http, status, error) {
                                       UIkit.notify("Error:" + error);
                                    }
                                 });
                              });
                           });
                        </script>
                        <tr class="uk-text-small">
                           <td class="uk-width-3-10 uk-text-bold">Levels : </td>
                           <td class="uk-width-7-10">
                              <select name="sub_sub_category" id="sub_sub_category" class="uk-form-small uk-width-1-1">
                                 <option value="">Select a Level</option>
                                 <option <?php if($edit_row['sub_sub_category']=='EDP') {echo 'selected';}?> value="EDP">EDP</option>
                                 <option <?php if($edit_row['sub_sub_category']=='QF1'){ echo "selected";}?> value="QF1">QF1</option>
                                 <option <?php if($edit_row['sub_sub_category']=='QF2'){ echo "selected";}?> value="QF2">QF2</option>
                                 <option <?php if($edit_row['sub_sub_category']=='QF3'){ echo "selected";}?> value="QF3">QF3</option>
                                 <option <?php if($edit_row['sub_sub_category']=='Other products'){ echo "selected";}?> value="Other products">Other products</option>
                                 <option <?php if($edit_row['sub_sub_category']=='Marketing set'){ echo "selected";}?> value="Marketing set">Marketing set</option>
                                 <option <?php if($edit_row['sub_sub_category']=='e-readers'){ echo "selected";}?> value="e-readers">e-readers</option>
                              </select>
							  <span id="validationLevels" style="color: red; display: none;">Please select Levels</span>
                           </td>
                        </tr>
						      <tr class="uk-text-small">
                           <td class="uk-width-3-10 uk-text-bold">Term : </td>
                           <td class="uk-width-7-10">
                              <select name="term" id="term" class="uk-form-small uk-width-1-1">
                                 <option value="">Select a Term</option>
                                 <option <?php if($edit_row['term']=='1'){ echo "selected";}?> value="1">1</option>
                                 <option <?php if($edit_row['term']=='2'){ echo "selected";}?> value="2">2</option>
                                 <option <?php if($edit_row['term']=='3'){ echo "selected";}?> value="3">3</option>
                                 <option <?php if($edit_row['term']=='4'){ echo "selected";}?> value="4">4</option>
                                 <option <?php if($edit_row['term']=='5'){ echo "selected";}?> value="5">5</option>
                              </select>
							  <span id="validationLevels" style="color: red; display: none;">Please select Levels</span>
                           </td>
                        </tr>
                        <tr class="uk-text-small">
                           <td class="uk-width-3-10 uk-text-bold">Foundation : </td>
                           <td class="uk-width-7-10">
                              <select name="foundation" id="foundation" class="uk-form-small uk-width-1-1">
                                 <option value="">Select a Foundation</option>
                                 <option <?php if($edit_row['foundation']=='Foundation'){ echo "selected";}?> value="Foundation">Foundation</option>
                                 <option <?php if($edit_row['foundation']=='Enhanced Foundation'){ echo "selected";}?> value="Enhanced Foundation">Enhanced Foundation</option>
                                 <option <?php if($edit_row['foundation']=='Others'){ echo "selected";}?> value="Others">Others</option>
                              </select>
                           </td>
                        </tr>
						 
						      <tr class="uk-text-small">
                           <td class="uk-width-3-10 uk-text-bold">Product Code<span class="text-danger">*</span> : </td>
                           <td class="uk-width-7-10">
						   <?php $product_code = $edit_row['product_code'];
							$product_code = explode("((--",$product_code)[0];
						   ?>
                              <input class="uk-width-1-1" type="text" name="product_code" id="product_code" value="<?php echo $product_code ?>">
                              <input type="hidden" name="hidden_product_code" id="hidden_product_code" value="<?php echo $edit_row['product_code'] ?>">
                              <input type="hidden" name="hidden_id" id="hidden_id" value="<?php echo $edit_row['id'] ?>">
							  <span id="validationproduct_code"  style="color: red; display: none;">Please input Product Code</span>
                           </td>
                        </tr>
                     </table>
                  </div>
                  <div class="uk-width-medium-5-10">
                     <table class="uk-table uk-table-small">
                        <tr class="uk-text-small">
                           <td class="uk-width-3-10 uk-text-bold">Product Name<span class="text-danger">*</span> : </td>
                           <td class="uk-width-7-10">
                              <input class="uk-width-1-1" type="text" name="product_name" id="product_name" value="<?php echo $edit_row['product_name'] ?>">
							  <span id="validationproduct_name"  style="color: red; display: none;">Please input Product Name</span>
                           </td>
						   
                        </tr>
                        <tr class="uk-text-small">
                           <td class="uk-width-3-10 uk-text-bold">Product Photo : </td>
                           <td class="uk-width-7-10">
                              <input class="uk-width-1-1" type="file" name="product_photo" id="product_photo" value="<?php echo $edit_row['product_photo'] ?>">
                              <?php
                              if ($edit_row["product_photo"] != "") {
                                 echo "<br>";
                                 echo "<br>";
                                 echo "<img src='admin/uploads/" . $edit_row["product_photo"] . "' height='200' width='200'>";
                              }
                              ?>
							  
                           </td>
                        </tr>
						<tr class="uk-text-small">
							  <td class="uk-width-3-10 uk-text-bold">Retail Price<span class="text-danger">*</span> : </td>
							  <td class="uk-width-7-10"><input class="uk-width-1-1" type="number" name="retail_price" id="retail_price" step="0.01" value="<?php echo $edit_row['retail_price']?>">
                       <span id="validationRetail"  style="color: red; display: none;">Please input Retail Price</span>
                       </td>
							  
						   </tr>
                        <tr class="uk-text-small">
                           <td class="uk-width-3-10 uk-text-bold">Unit Price<span class="text-danger">*</span> : </td>
                           <td class="uk-width-7-10"><input class="uk-width-1-1" type="number" name="unit_price" id="unit_price" step="0.01" value="<?php echo $edit_row['unit_price'] ?>">
						   <span id="validationUnit"  style="color: red; display: none;">Please input Unit Price</span>
						   </td>
                        </tr> 
                        <script>
                           function dlgProductCourse() {
                              if ($("#product_code").val() != "") {
                                 $.ajax({
                                    url: "admin/dlgProductCourse.php",
                                    type: "POST",
                                    data: "product_code=" + $("#product_code").val(),
                                    dataType: "text",
                                    success: function(response, status, http) {
                                       $("#dlgProductCourse").html(response);
                                       $("#dlgProductCourse").dialog({
                                          dialogClass: "no-close",
                                          title: "Product - Class Allocation",
                                          modal: true,
                                          height: 'auto',
                                          width: 'auto',
                                       });
                                    },
                                    error: function(http, status, error) {
                                       UIkit.notify("Error:" + error);
                                    }
                                 });
                              } else {
                                 UIkit.notify("Please select a product");
                              }
                           }
                        </script>
                        <!--<tr class="uk-text-small">
                           <td colspan="2"><a onclick="dlgProductCourse();" class="uk-button uk-button-small uk-width-1-1">Product - Class Allocation</a></td>
                        </tr>-->
                     </table>
                  </div>
               </div>
               <br>
               <div class="uk-text-center">
                  <?php
                  if (($_SESSION["UserType"] == "S" || $_SESSION["UserType"] == "H" || $_SESSION["UserType"] == "C" || $_SESSION["UserType"] == "R" || $_SESSION["UserType"] == "CM" || $_SESSION["UserType"] == "T") & (hasRightGroupXOR($_SESSION["UserName"], "ProductEdit"))) {
                  ?>
                     <button class="uk-button uk-button-primary">Save</button>
                  <?php
                  }
                  ?>
               </div>
               <?php
               if (($_SESSION["UserType"] == "S" || $_SESSION["UserType"] == "H" || $_SESSION["UserType"] == "C" || $_SESSION["UserType"] == "R" || $_SESSION["UserType"] == "CM" || $_SESSION["UserType"] == "T") & (hasRightGroupXOR($_SESSION["UserName"], "ProductEdit"))) {
               ?>
               </form>
            <?php
               }
            ?>
         </div>
         <br>

         <div class="uk-width-1-1 myheader">
            <h2 class="uk-text-center myheader-text-color">Searching</h2>
         </div>
         <?php
         $numperpage = 10;
         $query = "p=$p&m=$m&s=$s";
         $pagination = getPagination($pg, $numperpage, $query, $browse_sql, $start_record, $num_row);
         $browse_sql .= " order by id desc limit $start_record, $numperpage";
		 //echo $browse_sql;
         $browse_result = mysqli_query($connection, $browse_sql);
         $browse_num_row = mysqli_num_rows($browse_result);

         ?>
         <form class="uk-form" name="frmProductSearch" id="frmProductSearch" method="get">
            <input type="hidden" name="mode" id="mode" value="BROWSE">
            <input type="hidden" name="p" id="p" value="<?php echo $p ?>">
            <input type="hidden" name="m" id="m" value="<?php echo $m ?>">
            <input type="hidden" name="pg" value="">

            <div class="uk-grid">
               <div class="uk-width-7-10 uk-text-small">
                  <script>
                     $(document).ready(function() {
                        $("#scategory").change(function() {
                           var scategory = $("#scategory").val();

                           if (scategory != "") {
                              $.ajax({
                                 url: "admin/getSubCategory.php",
                                 type: "POST",
                                 data: "category=" + scategory + "&s=1",
                                 dataType: "text",
                                 beforeSend: function(http) {},
                                 success: function(response, status, http) {
                                    $("#ssub_category").html(response);
                                 },
                                 error: function(http, status, error) {
                                    UIkit.notification("Error:" + error);
                                 }
                              });
                           }
                        });
                     });
                  </script>
                  <input class="uk-form-small uk-width-3-10" placeholder="Form Name" name="s" id="s" value="<?php echo $_GET['s'] ?>">
                  <input class="uk-form-small uk-width-3-10" placeholder="Product Name" name="pn" id="pn" value="<?php echo $_GET['pn'] ?>">
                  <input class="uk-form-small uk-width-3-10" placeholder="Product Code" name="pc" id="pc" value="<?php echo $_GET['pc'] ?>">
                  <!-- <input class="uk-width-1-1" placeholder="Product Code/Name/Category" name="s" id="s" value="<?php echo $_GET['s'] ?>"> -->
               </div>
               <div class="uk-width-3-10">
                  <button class="uk-button uk-width-1-1">Search</button>
               </div>
            </div>
         </form><br>

         <div class="uk-width-1-1 myheader">
            <h2 class="uk-text-center myheader-text-color">Listing</h2>
         </div>
         <div class="uk-overflow-container">
            <table class="uk-table" id="mydatatable">
               <thead>
                  <tr class="uk-text-bold uk-text-small">
                     <td>Country</td>
                     <td style="width:500px;">State</td>
					<td>Form Name</td>					 
                     <td>Product Code</td>
                     <td>Product Name</td>
                     <td>Retail Price</td>
                     <td>Unit Price</td>
                     <td>Photo</td>
                     <td>Action</td>
                  </tr>
               </thead>
               <tbody>
                  <?php
                  if ($browse_num_row > 0) {
                     while ($browse_row = mysqli_fetch_assoc($browse_result)) {
                        $sha1_id = sha1($browse_row["id"]);
                  ?>
                        <tr class="uk-text-small">
                           <td><?php echo $browse_row["country"] ?></td>
                           <td style="width:200px;"><?php echo $browse_row["state"] ?></td> 
							<td style="width:200px;"><?php echo $browse_row["category_name"] ?></td> 
                           <td style="width:200px;"><?php
								$product_code = $browse_row["product_code"];
							$product_code = explode("((--",$product_code)[0];
						   echo $product_code;
						   
						   ?></td>
                           <td><?php echo $browse_row["product_name"] ?></td>
						   <td><?php echo $browse_row["retail_price"] ?></td>
						   <td><?php echo $browse_row["unit_price"] ?></td> 
						   <td style="width:150px;">
                              <a href="admin/uploads/<?php echo $browse_row['product_photo'] ?>" data-uk-lightbox title="Click for bigger image"><img src="admin/uploads/<?php echo $browse_row['product_photo'] ?>" width='100'></a>
                           </td>
                           <td>
                              <a href="index.php?p=<?php echo $p ?>&m=<?php echo $m ?>&id=<?php echo $sha1_id ?>&mode=EDIT"><img src="images/edit.png"></a>
							   <?php if(isDilitable($browse_row["product_code"]) == true ){ ?>
                              <a onclick="doDeleteRecord('<?php echo $sha1_id ?>')" href="#" id="btnDelete"><img src="images/delete.png"></a>
							   <?php } ?>
                           </td>
                        </tr>
                  <?php
                     }
                  } else {
                     echo "<tr><td colspan='6'>No Record Found</td></tr>";
                  }
                  ?>
               </tbody>
            </table>
         </div>
         <?php
         // echo $pagination;
         ?>

      </div>

      <form name="frmDeleteRecord" id="frmDeleteRecord" method="get">
         <input type="hidden" name="p" value="<?php echo $p ?>">
         <input type="hidden" name="m" value="<?php echo $m ?>">
         <input type="hidden" name="id" id="id" value="">
         <input type="hidden" name="mode" value="DEL">
      </form>
      <div id="dlgProductCourse"></div>
<?php
      if ($msg != "") {
         echo "<script>UIkit.notify('$msg')</script>";
         echo "<script>
         // setTimeout(
         //    function() 
         //    {
         //        window.location.replace('/index.php?p=centre_statement_account');
         //    }, 2000);
            </script>";
      }
   } else {
      echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
   }
} else {
   header("Location: index.php");
}
?>

<script type="text/javascript">
   $(document).ready(function() {
      $('#mydatatable').DataTable({
         language: {
            // searchPlaceholder: "Search Class Status",
            //  search: ""
         },
         "bInfo": false,
      });
   });
   $("#frmProduct").submit(function(e){
    
    var country=$("#country").val();
    var state=$("#state").val(); 
    var company_code=$("#company_code").val();
    var product_code=$("#product_code").val();
    //var sub_sub_category=$("#sub_sub_category").val();
    var product_name=$("#product_name").val();
    var category_id=$("#category_id").val();
    var retail_price=$("#retail_price").val();
    var unit_price=$("#unit_price").val();
    //var fees=$("#fees").val();
    var remark=$("#remark").val();
	///console.log(fees);

    if (!country || state==""|| !course_name || !selectPaymentType) {


   if (!country) {
            $('#validationCountry').show();
        }else{
            $('#validationCountry').hide();
        }
        console.log(state);
         if (state=="" || state==null) {
            $('#validationState').show();
        }else{
            $('#validationState').hide();
        }

        if (company_code=="") {
            $('#validationCompanyName').show();
        }else{
            $('#validationCompanyName').hide();
        }

		if (product_code=="") {
            $('#validationproduct_code').show();
        }else{
            $('#validationproduct_code').hide();
        }
			
		
		// if (sub_sub_category=="") {
            // $('#validationLevels').show();
        // }else{
            // $('#validationLevels').hide();
        // }
		if (product_name=="") {
            $('#validationproduct_name').show();
        }else{
            $('#validationproduct_name').hide();
        }
		if (category_id=="") {
            $('#validationCategoryId').show();
        }else{
            $('#validationCategoryId').hide();
        }

          if (!retail_price) {
            $('#validationRetail').show();
        }else{
            $('#validationRetail').hide();
        }
         if (!unit_price) {
            $('#validationUnit').show();
        }else{
            $('#validationUnit').hide();
        }
		if (!remark) {
            $('#validationRemark').show();
        }else{
            $('#validationRemark').hide();
        }
         
        return false;
  }
})
</script>
