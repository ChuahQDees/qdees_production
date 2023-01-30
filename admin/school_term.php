<?php
if ($_SESSION["isLogin"] == 1) {
   if ($_SESSION["UserType"] == "S") {
      include_once("mysql.php");
      include_once("lib/pagination/pagination.php");
      $p = $_GET["p"];
      $m = $_GET["m"];
      $get_sha1_id = $_GET["id"];
      $pg = $_GET["pg"];
      $mode = $_GET["mode"];

      $module = "SCHOOL_TERM";
      //echo $module;
      $str_module = "School Term";
      $p_module = "school_term";

      if ($mode == "") {
         $mode = "ADD";
      }
      include_once("$p_module" . "_func.php");
?>

      <script>
         function doDeleteRecord(id) {
            UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function() {
               $("#id").val(id);
               $("#frmDeleteRecord").submit();
            });
         }
      </script>
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
      <script>
         $(document).ready(function() {
            $("#country2").change(function() {
               var country = $("#country2").val();
               $.ajax({
                  url: "admin/get_state_not_select.php",
                  type: "POST",
                  data: "country=" + country,
                  dataType: "text",
                  beforeSend: function(http) {},
                  success: function(response, status, http) {
                     if (response != "") {
                        $("#state2").html(response);
                     } else {
                        $("#state2").html("<select name='state' id='state' class='uk-width-1-1'><option value=''>Please select a country</option></select>");
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
      <div class="uk-margin-right">
         <div class="uk-width-1-1 myheader">
            <h2 class="uk-text-center myheader-text-color"><?php echo $str_module ?></h2>
         </div>
         <form name="frmlegel" id="frmlegel" method="post" class="uk-form uk-form-small" action="index.php?p=<?php echo $p_module ?>&pg=<?php echo $pg ?>&mode=SAVE">
            <div class="uk-grid">
               <div class="uk-width-5-10">
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
                                 <option value="">Select</option>
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
                        <td class="uk-width-3-10 uk-text-bold"> Term : </td>
                        <td class="uk-width-7-10">
                           <input class="uk-width-1-1" type="text" name="term" id="term" value="<?php echo $edit_row['category'] ?>">
                           <input type="hidden" name="hidden_<?php echo $p_module ?>" id="hidden_<?php echo $p_module ?>" value="<?php echo $edit_row['code'] ?>">
                           <input type="hidden" name="module" id="module" value="<?php echo $module ?>">
                           <input type="hidden" name="code_id" id="code_id" value="<?php echo $edit_row['id'] ?>">
                        </td>
                     </tr>
                  </table>
               </div>
               <div class="uk-width-5-10">
                  <table class="uk-table uk-table-small">
                     <!--<tr class="uk-text-small">
                        <td class="uk-width-3-10 uk-text-bold">Year : </td>
                        <td class="uk-width-7-10"><input class="uk-width-1-1" type="text" name="year" id="year" value="<?php // echo $edit_row['year'] ?>"></td>
                     </tr>-->
                   
                     <tr class="uk-text-small">
                        <td class="uk-width-3-10 uk-text-bold">From Month : </td>
                        <td class="uk-width-7-10">
                           <select name="from_month" id="from_month" class="uk-width-1-1">
                              <option value="">Select</option>
                              <option <?php if ($edit_row['from_month'] == "1") {echo "selected"; } ?> value='1'>January</option>
                              <option <?php if ($edit_row['from_month'] == "2") {echo "selected"; } ?> value='2'>February</option>
                              <option <?php if ($edit_row['from_month'] == "3") {echo "selected"; } ?> value='3'>March</option>
                              <option <?php if ($edit_row['from_month'] == "4") {echo "selected"; } ?> value='4'>April</option>
                              <option <?php if ($edit_row['from_month'] == "5") {echo "selected"; } ?> value='5'>May</option>
                              <option <?php if ($edit_row['from_month'] == "6") {echo "selected"; } ?> value='6'>June</option>
                              <option <?php if ($edit_row['from_month'] == "7") {echo "selected"; } ?> value='7'>July</option>
                              <option <?php if ($edit_row['from_month'] == "8") {echo "selected"; } ?> value='8'>August</option>
                              <option <?php if ($edit_row['from_month'] == "9") {echo "selected"; } ?> value='9'>September</option>
                              <option <?php if ($edit_row['from_month'] == "10") {echo "selected"; } ?> value='10'>October</option>
                              <option <?php if ($edit_row['from_month'] == "11") {echo "selected"; } ?> value='11'>November</option>
                              <option <?php if ($edit_row['from_month'] == "12") {echo "selected"; } ?> value='12'>December</option>
                           </select>

                        </td>
                     </tr>
                     <tr class="uk-text-small">
                        <td class="uk-width-3-10 uk-text-bold">To Month : </td>
                        <td class="uk-width-7-10">
                        <select name="to_month" id="to_month" class="uk-width-1-1">
                              <option value="">Select</option>
                              <option <?php if ($edit_row['to_month'] == "1") {echo "selected"; } ?> value='1'>January</option>
                              <option <?php if ($edit_row['to_month'] == "2") {echo "selected"; } ?> value='2'>February</option>
                              <option <?php if ($edit_row['to_month'] == "3") {echo "selected"; } ?> value='3'>March</option>
                              <option <?php if ($edit_row['to_month'] == "4") {echo "selected"; } ?> value='4'>April</option>
                              <option <?php if ($edit_row['to_month'] == "5") {echo "selected"; } ?> value='5'>May</option>
                              <option <?php if ($edit_row['to_month'] == "6") {echo "selected"; } ?> value='6'>June</option>
                              <option <?php if ($edit_row['to_month'] == "7") {echo "selected"; } ?> value='7'>July</option>
                              <option <?php if ($edit_row['to_month'] == "8") {echo "selected"; } ?> value='8'>August</option>
                              <option <?php if ($edit_row['to_month'] == "9") {echo "selected"; } ?> value='9'>September</option>
                              <option <?php if ($edit_row['to_month'] == "10") {echo "selected"; } ?> value='10'>October</option>
                              <option <?php if ($edit_row['to_month'] == "11") {echo "selected"; } ?> value='11'>November</option>
                              <option <?php if ($edit_row['to_month'] == "12") {echo "selected"; } ?> value='12'>December</option>
                           </select>

                        </td>
                     </tr>
                     <tr class="uk-text-small">
                        <td class="uk-width-3-10 uk-text-bold">Description : </td>
                        <td class="uk-width-7-10"><input class="uk-width-1-1" type="text" name="description" id="description" value="<?php echo $edit_row['description'] ?>"></td>
                     </tr>

                  </table>
               </div>
            </div>
            <br>
            <div class="uk-text-center">
               <button class="uk-button uk-button-primary">Save</button>
            </div>
         </form><br>

         <div class="uk-width-1-1 myheader">
            <h2 class="uk-text-center myheader-text-color">Searching</h2>
         </div>
         <form class="uk-form" name="frm<?php echo $str_module ?>Search" id="frm<?php echo $str_module ?>Search" method="get">
            <input type="hidden" name="mode" id="mode" value="BROWSE">
            <input type="hidden" name="p" id="p" value="<?php echo $p ?>">
            <input type="hidden" name="m" id="m" value="<?php echo $m ?>">
            <input type="hidden" name="pg" value="">

            <div class="uk-grid">
               <div class="uk-width-2-10 uk-text-small">
                  <input class="uk-width-1-1" placeholder="<?php echo $str_module ?>" name="category" id="s" value="<?php echo $_GET['category'] ?>">
               </div>
               <!--<div class="uk-width-2-10 uk-text-small">
                  <input class="uk-width-1-1" placeholder="Year" name="year" id="year" value="<?php // echo $_GET['year'] ?>">
               </div>-->
               <div class="uk-width-2-10 uk-text-small">
                  <select name="country" id="country2" class="uk-width-1-1">
                     <option value="">Select</option>
                     <option <?php if ($_GET['country'] == "Bangladesh") {
                                 echo "selected";
                              } ?> value="Bangladesh">Bangladesh</option>
                     <option <?php if ($_GET['country'] == "Brunei") {
                                 echo "selected";
                              } ?> value="Brunei">Brunei</option>
                     <option <?php if ($_GET['country'] == "Cambodia") {
                                 echo "selected";
                              } ?> value="Cambodia">Cambodia</option>
                     <option <?php if ($_GET['country'] == "Indonesia") {
                                 echo "selected";
                              } ?> value="Indonesia">Indonesia</option>
                     <option <?php if ($_GET['country'] == "Laos") {
                                 echo "selected";
                              } ?> value="Laos">Laos</option>
                     <option <?php if ($_GET['country'] == "Malaysia") {
                                 echo "selected";
                              } ?> value="Malaysia">Malaysia</option>
                     <option <?php if ($_GET['country'] == "Myanmar") {
                                 echo "selected";
                              } ?> value="Myanmar">Myanmar</option>
                     <option <?php if ($_GET['country'] == "Philippines") {
                                 echo "selected";
                              } ?> value="Philippines">Philippines</option>
                     <option <?php if ($_GET['country'] == "Singapore") {
                                 echo "selected";
                              } ?> value="Singapore">Singapore</option>
                     <option <?php if ($_GET['country'] == "Thailand") {
                                 echo "selected";
                              } ?> value="Thailand">Thailand</option>
                     <option <?php if ($_GET['country'] == "Timor-Leste") {
                                 echo "selected";
                              } ?> value="Timor-Leste">Timor-Leste</option>
                     <option <?php if ($_GET['country'] == "Vietnam") {
                                 echo "selected";
                              } ?> value="Vietnam">Vietnam</option>

                  </select>
               </div>
               <div class="uk-width-2-10 uk-text-small">
                  <?php $state = $_GET['state'];
                  //echo $state;
                  ?>
                  <select name="state" id="state2" class="uk-width-1-1">
                     <?php


                     if ($edit_row["country"] != "") {
                     ?>
                        <option value="">Select</option>
                        <?php
                        $sql = "SELECT * from codes where country='" . $edit_row["country"] . "' and module='STATE' order by code";
                        $result = mysqli_query($connection, $sql);
                        while ($row = mysqli_fetch_assoc($result)) {
                           //echo $_GET['state'];
                        ?>
                           <option value="<?php echo $row['code'] ?>" <?php if ($row["code"] == $state) {
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
               </div>
               <div class="uk-width-2-10">
                  <button class="uk-button uk-width-1-1">Search</button>
               </div>
            </div>
         </form><br>

         <div class="uk-width-1-1 myheader">
            <h2 class="uk-text-center myheader-text-color">Listing</h2>
         </div>

         <?php
         $numperpage = 20;
         $query = "p=$p&m=$m&s=$s";
         $pagination = getPagination($pg, $numperpage, $query, $browse_sql, $start_record, $num_row);
         $browse_sql .= " limit $start_record, $numperpage";
         //echo $browse_sql;

         $browse_result = mysqli_query($connection, $browse_sql);
         $browse_num_row = mysqli_num_rows($browse_result);
         ?>

         <table class="uk-table uk-table-striped">
            <tr class="uk-text-bold uk-text-small">
               <td>Country</td>
               <td>State</td>
               <td>Term</td>
               <!--<td>Year</td>-->
               <td>Months</td>
               <td>Description</td>
               <td>Action</td>
            </tr>
            <?php
            if ($browse_num_row > 0) {
               while ($browse_row = mysqli_fetch_assoc($browse_result)) {
                  $sha1_id = sha1($browse_row["id"]);
            ?>
                  <tr class="uk-text-small">
                     <!--<td><?php // echo $browse_row["code"]
                              ?></td>-->
                     <td><?php echo $browse_row["country"] ?></td>
                     <td><?php echo $browse_row["state"] ?></td>
                     <td><?php echo $browse_row["category"] ?></td>
                     <!--<td><?php // echo $browse_row["year"] ?></td>-->
                     <td><?php echo $browse_row["from_month"] ?> To <?php echo $browse_row["to_month"] ?></td> 
                     <td><?php echo $browse_row["description"] ?></td>
                     <td>
                        <a href="index.php?p=<?php echo $p ?>&m=<?php echo $m ?>&id=<?php echo $sha1_id ?>&mode=EDIT&master=1"><img src="images/edit.png"></a>
                        <a onclick="doDeleteRecord('<?php echo $sha1_id ?>')" href="#" id="btnDelete"><img src="images/delete.png"></a>
                     </td>
                  </tr>
            <?php
               }
            } else {
               echo "<tr><td colspan='6'>No Record Found</td></tr>";
            }
            ?>
         </table>
         <?php
         echo $pagination;
         ?>
      </div>

      <form name="frmDeleteRecord" id="frmDeleteRecord" method="get">
         <input type="hidden" name="p" value="<?php echo $p ?>">
         <input type="hidden" name="m" value="<?php echo $m ?>">
         <input type="hidden" name="id" id="id" value="">
         <input type="hidden" name="mode" value="DEL">
         <input type="hidden" name="master" value="1">
      </form>
<?php
      if ($msg != "") {
         echo "<script>UIkit.notify('$msg')</script>";
      }
   } else {
      echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
   }
} else {
   header("Location: index.php");
}
?>