<?php
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

?>

<a href="/">
   <span class="btn-qdees1"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>
<span>
   <span class="page_title"><img src="/images/title_Icons/Order.png">Order</span>
</span>

<style>
   .cat-pre-level-1 {
      background-color: #ffebee !important
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
               $("#category_name").val("");
               $("#category").val("");
               $("#sub_category").val("");
               $("#pn").val("");
               $("#frmPurchasing").submit();
            });
         });
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
<input type="text" name="category_name" id="category_name" placeholder="Search by key" value="<?php echo $_GET['category_name']?>" />
                     <select name="sub_company" id="sub_company" class="chosen">
                        <option value="">Products</option>
						<option value="Marketing Set" <?php if ($_GET["sub_company"] == 'Marketing Set') {
                                                                     echo "selected";
                                                                  } ?>>Marketing Set</option>
                        <option value="Students Materials" <?php if ($_GET["sub_company"] == 'Student Materials') {
                                                                     echo "selected";
                                                                  } ?>>Students Materials</option>
                        <option value="Blank Form For Non - Listed Items" <?php if ($_GET["sub_company"] == 'Blank Form for Non-Listed Items') {
                                                                        echo "selected";
                                                                     } ?>>Blank Form For Non - Listed Items</option>
                        <option value="Furniture & Equipments" <?php if ($_GET["sub_company"] == 'Furniture & Equipments') {
                                                         echo "selected";
                                                      } ?>>Furniture & Equipments</option>
                        
						<option value="Corporate Identity" <?php if ($_GET["sub_company"] == 'Corporate Identity') { echo "selected"; } ?>>Corporate Identity</option>
						<option value="Indoor Corporate Identity" <?php if ($_GET["sub_company"] == 'Indoor Corporate Identity') { echo "selected"; } ?>>Indoor Corporate Identity</option>
						<option value="Students Attire" <?php if ($_GET["sub_company"] == 'Student Attire') { echo "selected"; } ?>>Students Attire</option>
						<option value="Administrative Material" <?php if ($_GET["sub_company"] == 'Administrative Materials') { echo "selected"; } ?>>Administrative Material</option>
						<option value="Promotional Items" <?php if ($_GET["sub_company"] == 'Promotional Items') { echo "selected"; } ?>>Promotional Items</option>
						<option value="Software Access" <?php if ($_GET["sub_company"] == 'Software Access') { echo "selected"; } ?>>Software Access</option>
                     </select>
					  

                     <select name="term" id="term">
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
                     <!-- <input name="pn" id="pn" placeholder="Product Name/Code" value="<?php echo $pn ?>" size="25"> -->
                     <button id="btnSearch" class="uk-button blue_button">Search</button>
                     <a id="btnClear" class="uk-button blue_button">Clear</a>
                  </div>
               </div>
            </div>
         </form>
         <div class="uk-container" style="margin: 0 auto;">
            <div class="uk-container-center">


               <?php
               global $connection;
               $sub_company = $_GET["sub_company"];
               $category_name = $_GET["category_name"];
               $term = $_GET["term"];
               $sub_sub_category = $_GET["sub_sub_category"];
               $country = $_SESSION["Country"];               
               $sql = "SELECT c.* from `product_category` c left join `product` p on p.category_id=c.id where 1=1 and c.country='$country' ";
               if ($sub_company != "") {
                  //$sql .= "and company_name = '$sub_company'";
                  $sql .= "and c.category_description like '%$sub_company%' ";
               }
               if ($category_name != "") {
                  //$sql .= "and company_name = '$sub_company'";
                  $sql .= "and (c.category_name like '%$category_name%' or c.category_description like '%$category_name%') ";
               }
               if ($term != "") {
                  $sql .= "and p.term = '$term' ";
               }
               if ($sub_sub_category != "") {
                  $sql .= "and p.sub_sub_category = '$sub_sub_category' ";
               }
               $sql .= " group by c.id order by category_name asc";
              
               //echo $sql;

               $result = mysqli_query($connection, $sql);
               $num_row = mysqli_num_rows($result);
               $numperpage = 10;
               $query = "p=" . $_GET["p"] . "&pn=$pn&sub_company=$sub_company&category_name=$category_name&sub_sub_category=$sub_sub_category&term=$term";
               //echo  $query;
               $pagination = getPagination($pg, $numperpage, $query, $sql, $start_record, $num_row);

               $sql .= " limit $start_record, $numperpage";
               $result = mysqli_query($connection, $sql);

               ?>

               <div class="pl_2_Q"><?php 
               if ($sub_company == "") {
                  echo "ALL";
                } else {
                  echo $sub_company;
                }
                ?>:</div>

               <div class="q-dess">

                  <?php
                  if (mysqli_num_rows($result)) {
                     while ($category = mysqli_fetch_assoc($result)) {
                  ?>
                        <div class="main_row d-flex  ">
                           <div class="qdessholdings">
                              <a href="/index.php?p=purchasing&category-id=<?php echo $category['id']; ?>&term=<?php echo $_GET['term']; ?>&sub_sub_category=<?php echo $_GET['sub_sub_category']; ?>" class="qdessholdings_box">
                                 <span style="padding-left: 11px;" class=" one_box">
                                    <span class="one_box1"><?php echo $category['category_name']; ?></span><br><br>
                                    <span class="one_box2"><?php echo $category['category_description']; ?></span>
                                 </span>
                              </a>
                           </div>
                        </div>

                  <?php
                     }
                  }
                  ?>
                  <!-- <div class="qdessholdings">
                        <a class="qdessholdings_box">
                           <span style="padding-left: 11px;" class=" one_box">
                              <span class="one_box1">Scholars Order FORM 3</span><br><br>
                              <span class="one_box2">Scholars Corporate Identity:<br>Interior Signages</span>
                           </span>
                        </a>
                     </div>
                     <div class="qdessholdings">
                        <a class="qdessholdings_box">
                           <span style="padding-left: 11px;" class=" one_box">
                              <span class="one_box1">Scholars ORDER FORM 4</span><br><br>
                              <span class="one_box2">Franchise Scholars Kit</span>
                           </span>
                        </a>
                     </div>
                     <div class="qdessholdings">
                        <a class="qdessholdings_box">
                           <span style="padding-left: 11px;" class=" one_box">
                              <span class="one_box1">Scholars ORDER FORM 5A</span><br><br>
                              <span class="one_box2">Student Materials:<br>International English Modules<br>(Pre-level 1)</span>
                           </span>
                        </a>
                     </div>
                  </div>
                  <div class="main_row d-flex  ">
                     <div class="qdessholdings">
                        <a class="qdessholdings_box">
                           <span class=" one_box">
                              <span class="one_box1">Scholars ORDER FORM 7B <span class="low">(ii)</span></span><br><br>
                              <span class="one_box2">BIMP Supplementary Sheets<br>(Level 2)</span>
                           </span>
                        </a>
                     </div>
                     <div class="qdessholdings">
                        <a class="qdessholdings_box">
                           <span class=" one_box">
                              <span class="one_box1">Scholars ORDER FORM 7C <span class="low">(ii)</span></span><br><br>
                              <span class="one_box2">BIMP Supplementary Sheets<br>(Level 3)</span>
                           </span>
                        </a>
                     </div> -->


               </div>
            </div>

            <?php
            $base_sql = "SELECT *, SUBSTRING_INDEX(trim(lower(sub_sub_category)), 'level', -1) as trimmed_level from product";
            $v_token = ConstructToken("vendor", $v, "=");
            $pn_token = ConstructTokenGroup("product_name", "%$pn%", "like", "product_code", "%$pn%", "like", "or");
            $cat_token = ConstructToken("category", $category, "=");
            $subcat_token = ConstructToken("sub_category", $sub_category, "=");
            $subsubcat_token = ConstructToken("sub_sub_category", $sub_sub_category, "=");
            $final_token = ConcatToken($v_token, $pn_token, "and");
            $final_token = ConcatToken($final_token, $cat_token, "and");
            $final_token = ConcatToken($final_token, $subcat_token, "and");
            $final_token = ConcatToken($final_token, $subsubcat_token, "and");
            $final_sql = ConcatWhere($base_sql, $final_token);
            //echo $final_sql;
            $result = mysqli_query($connection, $final_sql);
            $num_row = mysqli_num_rows($result);

            $numperpage = 20;
            $query = "p=" . $_GET["p"] . "&v=$v&pn=$pn&category=$category&sub_category=$sub_category&sub_sub_category=$sub_sub_category";

            //$pagination = getPagination($pg, $numperpage, $query, $final_sql, $start_record, $num_row);
            //echo $final_sql."<br><br>";
            $final_sql .= " ORDER BY locate('pre-level', lower(sub_sub_category)) desc, trim(SUBSTRING_INDEX(lower(sub_sub_category), 'level', -1)), product_name";
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
                     while ($row = mysqli_fetch_assoc($final_result)) {
                        if ($level == '') {
                           $level = getLevelCss($row["sub_sub_category"]);
                           echo '<h3 style="width:100%">' . ucwords(str_replace('level-', 'level ', substr($level, 4))) . '</h3>';
                        }
                        if ($level != getLevelCss($row["sub_sub_category"])) {
                           echo '<hr style="width:100%; color: #222;">';

                           $level = getLevelCss($row["sub_sub_category"]);
                           echo '<h3 style="width:100%">' . ucwords(str_replace('level-', 'level ', substr($level, 4))) . '</h3>';
                        }
                     ?>

                        <div class="col-xl-3 col-lg-4 col-md-6 qdees-4-element">
                           <div class="qdees-product">
                              <a target="_blank" href="admin/uploads/<?php echo $row["product_photo"] ?>"><img class="img-fluid" src="admin/uploads/<?php echo $row["product_photo"] ?>" alt="<?php echo $row["product_name"]; ?>"></a>
                              <div class="product-desc <?php echo getLevelCss($row["sub_sub_category"]); ?>">
                                 <h4 class="font-weight-bold text-black"><?php echo $row["product_name"]; ?></h4>
                                 <p style="opacity: .8"><?php echo 'k' . $row["trimmed_level"] . $row["product_code"]; ?></p>
                                 <div class="uk-flex uk-flex-space-between">
                                    <div style="font-size: 15px"><b><?php echo number_format($row["unit_price"], 2); ?></b></div>
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
               <!-- <div class='uk-margin-top uk-margin-right'>
                  <div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>No record found</div>
               </div> -->
            <?php
            }
            ?>
         </div>
      </div>

      <!-- <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="qdees-product">
                    <img class="img-fluid" src="https://cdn.shopify.com/s/files/1/0533/2089/files/placeholder-images-image_large.png?v=1530129081" alt="Image">
                    <div class="product-desc">
                        <h3>International English Bridging Program</h3>
                        <p>INT-ENG.BRIDGING-PROGM</p>
                    </div>
                </div>
            </div>
        </div>
    </div> -->


      <?php
      echo $pagination;
      ?>
      </div>
      <script>
         $(document).ready(function() {
            $("#btnClear").click(function() {

            });
         });
      </script>
<?php
   } else {
      echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
   }
} else {
   header("Location: index.php");
}
?>

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.jquery.min.js"></script>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.min.css">
                <script>
                $('.chosen').chosen({
                    search_contains: true
                }).change(function(obj, result) {
                    // console.debug("changed: %o", arguments);
                    // console.log("selected: " + result.selected);
                });
                </script> -->
<style>
   .btn-qdees1,
   .display_n {
      display: none;
   }

   .pl_2_Q {
      text-align: center;
      font-size: 18px;
   }

   .qdessholdings {
    width: 300px;
}

   .qdessholdings a.qdessholdings_box {
      /* background-color: #77d7f3; */
      background-color: white;
      padding: 37px;
      margin: 29px;
      text-align: center;
      border-radius: 20px;
      display: flex;
      height: 75%;
      justify-content: center;
      border: 1px solid black;
    color: black;
   }

   .one_box1 {
      font-weight: bold;
   }

   span.one_box1 {
      /* text-transform: uppercase; */
      font-size: 15px;
      font-weight: bold;
   }

   span.low {
      text-transform: lowercase;
   }
   .q-dess{
      display: flex;
    /* flex-wrap: wrap; */
    flex-flow: row wrap;
    justify-content: center;
   }
</style>