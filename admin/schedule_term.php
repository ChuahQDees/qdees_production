<?php

if ($_SESSION["isLogin"] == 1) {
  // if ($_SESSION["UserType"] != "S") {
      include_once("mysql.php");
      include_once("lib/pagination/pagination.php");
      $p = $_GET["p"];
      $m = isset($_GET["m"]) ? $_GET["m"] : '';
      $get_sha1_id = isset($_GET["id"]) ? $_GET["id"] : '';
      $pg = isset($_GET["pg"]) ? $_GET["pg"] : '';
      $mode = isset($_GET["mode"]) ? $_GET["mode"] : '';

      $module = "SCHEDULE_TERM";
      //echo $module;
      $str_module = "Schedule Term";
      $p_module = "schedule_term";

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
     
      <div class="uk-margin-right">
         <div class="uk-width-1-1 myheader">
            <h2 class="uk-text-center myheader-text-color"><?php echo $str_module ?></h2>
         </div>
         <form name="frmlegel" id="frmlegel" method="post" class="uk-form uk-form-small" action="index.php?p=<?php echo $p_module ?>&pg=<?php echo $pg ?>&mode=SAVE">
            <div class="uk-grid">
               <div class="uk-width-10-10">
                  <table class="uk-table uk-table-small">
                     <tr class="uk-text-small">
                        <td class="uk-width-3-10 ">Term</td>
                        <td class="uk-width-3-10 ">From Month<span class="text-danger">*</span>:</td>
                        <td class="uk-width-3-10">To Month<span class="text-danger">*</span>:</td>
                     </tr>
                     <?php
                        for($i = 1; $i <= 5; $i++)
                        {
                           if($mode == 'EDIT') {
                              $editData = mysqli_fetch_array(mysqli_query($connection,"SELECT * FROM $table WHERE `year` = '".$_GET['year']."' AND `centre_code` = '".$_SESSION["CentreCode"]."' AND `term` = 'Term ".$i."'"));
                           ?>
                              <input type="hidden" name="id<?php echo $i; ?>" value="<?php if(isset($editData['id'])) { echo $editData['id']; } ?>">
                     <?php 
                           }
                           if($i == 5)
                           {
                     ?>
                              <tr class="uk-text-small">
                                 <td class="uk-width-3-10 ">
                                    <input type="checkbox" id="check" <?php if(isset($editData['term_start'])) { echo 'checked'; } ?> onchange="term5_change(this)"> Add Term 5
                                 </td>
                                 <td class="uk-width-3-10"></td>
                                 <td class="uk-width-3-10"></td>
                              </tr>
                     <?php
                           }
                     ?>
                           <tr class="uk-text-small" <?php if($i == 5) { ?> id="term5" <?php if(!isset($editData['term_start'])) { ?> style="display:none;" <?php } } ?> >
                              <td class="uk-width-3-10 ">Term <?php echo $i; ?></td>
                              <td class="uk-width-3-10">
                                 <input type="text" readonly class="datepicker" placeholder="From" name="term<?php echo $i; ?>_from_date" id="term<?php echo $i; ?>_from_date" value="<?php if(isset($editData['term_start'])) { echo date('F Y',strtotime($editData['term_start'])); } ?>" onchange="dateChange()" autocomplete="off">

                                 <div id="validationTerm<?php echo $i; ?>FromDate" style="color: red; display: none;">Please select</div>
                              </td>
                              <td class="uk-width-3-10">
                                 <input type="text" readonly class="datepicker" placeholder="To" name="term<?php echo $i; ?>_to_date" id="term<?php echo $i; ?>_to_date" value="<?php if(isset($editData['term_end'])) { echo date('F Y',strtotime($editData['term_end'])); } ?>" onchange="dateChange()" autocomplete="off">
                                 <div id="validationTerm<?php echo $i; ?>ToDate" style="color: red; display: none;">Please select</div>
                              </td>
                           </tr>
                     <?php
                        }
                     ?>
                  </table>
               </div>
            </div>
            <br>
            <div class="uk-text-center">
               <button class="uk-button uk-button-primary">Save</button>
            </div>
         </form><br>

         <div class="uk-width-1-1 myheader">
            <h2 class="uk-text-center myheader-text-color">Listing</h2>
         </div>

         <?php
         $numperpage = 20;
         $query = "p=$p&m=$m";
         $pagination = getPagination($pg, $numperpage, $query, $browse_sql, $start_record, $num_row);
         $browse_sql .= " limit $start_record, $numperpage";
         //echo $browse_sql;

         $browse_result = mysqli_query($connection, $browse_sql);
         $browse_num_row = mysqli_num_rows($browse_result);
         ?>

         <table class="uk-table uk-table-striped">
            <thead>
               <tr class="uk-text-bold uk-text-small">
                  <td>Year</td>
                  <td>Term</td>
                  <td>From</td>
                  <td>To</td>
                  <td>Action</td>
               </tr>
            </thead>
            <tbody>
            <?php
               if ($browse_num_row > 0) {
                  $year = '';
                  while ($browse_row = mysqli_fetch_assoc($browse_result)) {
                     $sha1_id = sha1($browse_row["id"]);
            ?>
                     <tr class="uk-text-small">
                        <td>
                           <?php 
                              if($browse_row["year"] != $year)
                              {
                                 $year = $browse_row["year"];
                                 echo $year;
                                 if($year >= date('Y'))
                                 {
                                    $action = 1;
                                 }
                              }
                              else
                              {
                                 $action = 0;
                              }
                           ?>
                        </td>
                        <td><?php echo $browse_row["term"] ?></td>
                        <td><?php echo date('F Y',strtotime($browse_row["term_start"])); ?></td>
                        <td><?php echo date('F Y',strtotime($browse_row["term_end"])); ?></td>
                        <td>
                           <?php if($action == 1) { ?>
                              <a href="index.php?p=<?php echo $p ?>&m=<?php echo $m ?>&year=<?php echo $year ?>&mode=EDIT&master=1"><img src="images/edit.png"></a>
                             <!--  <a onclick="doDeleteRecord('<?php echo $year ?>')" href="#" id="btnDelete"><img src="images/delete.png"></a> -->
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
   /* } else {
      echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
   } */
} else {
   header("Location: index.php");
}
?>

<script>
   $(document).ready(function(){
      $("#frmlegel").submit(function(e){
         var term1_from_date = $("#term1_from_date").val();
         var term1_to_date = $("#term1_to_date").val();

         var term2_from_date = $("#term2_from_date").val();
         var term2_to_date = $("#term2_to_date").val();

         var term3_from_date = $("#term3_from_date").val();
         var term3_to_date = $("#term3_to_date").val();

         var term4_from_date = $("#term4_from_date").val();
         var term4_to_date = $("#term4_to_date").val();

         var term5_from_date = $("#term5_from_date").val();
         var term5_to_date = $("#term5_to_date").val();
         
         var error = 0;
        
         if (!term1_from_date) {
            error = 1;
            $('#validationTerm1FromDate').show();
         }else{
            $('#validationTerm1FromDate').hide();
         }

         if (!term1_to_date) {
            error = 1;
            $('#validationTerm1ToDate').show();
         }else{
            $('#validationTerm1ToDate').hide();
         }

         if (!term2_from_date) {
            error = 1;
            $('#validationTerm2FromDate').show();
         }else{
            $('#validationTerm2FromDate').hide();
         }

         if (!term2_to_date) {
            error = 1;
            $('#validationTerm2ToDate').show();
         }else{
            $('#validationTerm2ToDate').hide();
         }

         if (!term3_from_date) {
            error = 1;
            $('#validationTerm3FromDate').show();
         }else{
            $('#validationTerm3FromDate').hide();
         }

         if (!term3_to_date) {
            error = 1;
            $('#validationTerm3ToDate').show();
         }else{
            $('#validationTerm3ToDate').hide();
         }

         if (!term4_from_date) {
            error = 1;
            $('#validationTerm4FromDate').show();
         }else{
            $('#validationTerm4FromDate').hide();
         }

         if (!term4_to_date) {
            error = 1;
            $('#validationTerm4ToDate').show();
         }else{
            $('#validationTerm4ToDate').hide();
         }

         if($("#check").prop('checked') == true){

            if (!term5_from_date) {
               error = 1;
               $('#validationTerm5FromDate').show();
            }else{
               $('#validationTerm5FromDate').hide();
            }

            if (!term5_to_date) {
               error = 1;
               $('#validationTerm5ToDate').show();
            }else{
               $('#validationTerm5ToDate').hide();
            }
         }

         if(error == 1)
         {
            e.preventDefault();
            return false;
         }

      });
   });

</script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>

<script>
   $(document).ready(function(){
      $(".datepicker").datepicker({
         format: "MM yyyy",
         viewMode: "months", 
         minViewMode: "months",
         autoclose:true
      });   
   })

   function dateChange()
   {
      var term1_from_date = $("#term1_from_date").val();
      var term1_to_date = $("#term1_to_date").val();

      var term2_from_date = $("#term2_from_date").val();
      var term2_to_date = $("#term2_to_date").val();

      var term3_from_date = $("#term3_from_date").val();
      var term3_to_date = $("#term3_to_date").val();

      var term4_from_date = $("#term4_from_date").val();
      var term4_to_date = $("#term4_to_date").val();

      var term5_from_date = $("#term5_from_date").val();
      var term5_to_date = $("#term5_to_date").val();

      if(term1_from_date != '')
      {
         /* var date = new Date(term1_from_date);
         $("#term1_to_date").datepicker({
            minDate: date
         }); */
      }
   }

   function term5_change(thiss)
   {
      $('#term5').toggle();
      
      if($(thiss).prop('checked') == false){
         $('#term5_from_date').val('');
         $('#term5_to_date').val('');
      }
   }

</script>

<style>

   .datepicker.datepicker-dropdown, .datepicker.datepicker-inline {
      max-width:200px;
   }

   .uk-text-small {
      font-size: 14px;
   }

   .d_n {
      display: none;
   }

   #frmStatus input {
      width: 100%;
   }

   .q-table.q-table-light tr:first-child {
      background: transparent;
      color: darkgrey;
   }

   .q-table.q-table-light {
      box-shadow: none !important;
      background: white !important;
   }

   .uk-overflow-container {
      padding: 1.5em 0 1.5em 0;
   }

   #mydatatable_length {
      display: none;
   }

   #mydatatable_filter {
      display: none;
   }

   #mydatatable_paginate {
      float: initial;
      text-align: center
   }

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
