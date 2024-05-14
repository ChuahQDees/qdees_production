<?php
session_start();
include_once("../mysql.php");
foreach ($_POST as $key => $value) {
   $$key = mysqli_real_escape_string($connection, $value);
}

function getSSIDByStudentCode($sha1_student_code)
{
   global $connection;

   $sql = "SELECT * from student where sha1(student_code)='$sha1_student_code'";
   $result = mysqli_query($connection, $sql);
   $row = mysqli_fetch_assoc($result);
   return sha1($row["student_code"]);
}

$ssid = getSSIDByStudentCode($student_code);

?>

<script>
   function removeFromTempBusket(bid, product_code, student_code) {
      //UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function() {
         var allocation_id = $("#allocation_id").val();

         $.ajax({
            url: "admin/removeFromTempBusket.php",
            type: "POST",
            data: "product_code=" + product_code + "&student_code=" + student_code + "&bid=" + bid,
            dataType: "text",
            beforeSend: function(http) {},
            success: function(response, status, http) {
               var s = response.split("|");

               if (s[0] == "1") {
                  getTempBusket();
               }

               if (s[0] == "0") {
                  UIkit.notify(s[1]);
               }
            },
            error: function(http, status, error) {
               UIkit.notify("Error:" + error);
            }
         });
      //})
   }

   function getTempBusket() {
      var ssid = '<?php echo $ssid ?>';

      $.ajax({
         url: "admin/getTempBusketContent.php",
         type: "POST",
         data: "ssid=" + ssid,
         dataType: "text",
         beforeSend: function(http) {},
         success: function(response, status, http) {
            $("#lstBusket").html(response);
         },
         error: function(http, status, error) {
            UIkit.notify("Error:" + error);
         }
      });
   }

   function doSearch() {
      var allocation_id = $("#allocation_id").val();
      var s = $("#s").val();
      var student_code = '<?php echo $ssid ?>';
      var course_name = $("#course_name").val();
      var module = $("#module").val();
      var term = $("#term").val();
      var subject = $("#subject").val();
      //if (category!="") {
      $.ajax({
         url: "admin/getProductList.php",
         type: "POST",
         data: "allocation_id=" + allocation_id + "&student_code=" + student_code + "&course_name=" + course_name+"&module=" + module+ "&term=" + term+ "&subject=" + subject+ "&s=" + s,
         dataType: "text",
         beforeSend: function(http) {},
         success: function(response, status, http) {
            $("#lstProduct").html(response);
            $('#productDatatable').DataTable({
               searching: false,
               info:false,
               "ordering": false,
               'columnDefs': [ { 
                  'targets': [0,4], // column index (start from 0)
               }],
               "bProcessing": true,
               "bServerSide": true,
               "sAjaxSource": "admin/serverresponse/dlg_product.php?course_name=" + course_name + "&module=" + module + "&term=" + term+ "&subject=" + subject+ "&s=" + s+ "&student_code=" + student_code
            });
         },
         error: function(http, status, error) {
            UIkit.notify("Error:" + error);
         }
      });
      /*} else {
         UIkit.notify("Please fill in both fields");
      }*/
   }

   function doPutInBusket() {
      UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function() {
         $.ajax({
            url: "admin/doPutInBusket.php",
            type: "POST",
            data: "",
            dataType: "text",
            beforeSend: function(http) {},
            success: function(response, status, http) {
               var s = response.split("|");
               if (s[0] == "1") {
                  window.location.reload();
               }

               if (s[0] == "0") {
                  UIkit.notify(s[1]);
               }
            },
            error: function(http, status, error) {
               UIkit.notify("Error:" + error);
            }
         });
      });
   }

   $(document).ready(function() {
      $("#btnSearch").click(function(event) {
         event.preventDefault();
         doSearch();
      });
      $("#course_name").change(function(event) {
         //event.preventDefault();
         if($(this).val()=="IE"){
            $(".l5").hide();
            $(".pl1").show();
         }else{
            $(".l5").show(); 
            $(".pl1").hide();
         }
      }); 
      $("#subject").change(function(event) {
         //event.preventDefault();
         if($(this).val()=="Thematic English"){
            $(".lm1").show();
            $(".lm2").hide();
            $(".lm3").hide();
            $(".lm4").hide();
			   $(".lm5").hide();
         }else if($(this).val()=="Phonics"){
			 $(".lm1").show();
            $(".lm2").hide();
            $(".lm3").hide();
            $(".lm4").hide();
			   $(".lm5").hide();
         }else if($(this).val()=="Link&Think Reading Series"){
            $(".lm2").show();
            $(".lm1").hide();
            $(".lm3").hide();
            $(".lm4").hide();
			$(".lm5").hide();
         }else if($(this).val()=="Mandarin"){
            $(".lm1").show();
            $(".lm2").hide();
            $(".lm3").hide();
            $(".lm4").hide();
			$(".lm5").hide();
         }else if($(this).val()=="Bahasa Malaysia"){
			$(".lm1").show();
            $(".lm2").hide();
            $(".lm3").hide();
            $(".lm4").hide();
			   $(".lm5").hide();
         }else if($(this).val()=="IQ Math"){
            $(".lm1").show();
            $(".lm2").hide();
            $(".lm3").hide();
            $(".lm4").hide();
			   $(".lm5").hide();
         }else if($(this).val()=="Science"){
            $(".lm1").show();
            $(".lm2").hide();
            $(".lm3").hide();
            $(".lm4").hide();
			   $(".lm5").hide();
         }else if($(this).val()=="Character Building"){
            $(".lm1").show();
            $(".lm2").hide();
            $(".lm3").hide();
            $(".lm4").hide();
			   $(".lm5").hide();
         }else if($(this).val()=="Music"){
            $(".lm1").show();
            $(".lm2").hide();
            $(".lm3").hide();
            $(".lm4").hide();
			   $(".lm5").hide();
         }else if($(this).val()=="Art & Craft"){
            $(".lm1").show();
            $(".lm2").hide();
            $(".lm3").hide();
            $(".lm4").hide();
			   $(".lm5").hide();
         }else if($(this).val()=="Multimedia Songs"){
            $(".lm3").show();
            $(".lm1").hide();
            $(".lm2").hide();
            $(".lm4").hide();
			   $(".lm5").hide();
         }else if($(this).val()=="International English"){
            $(".lm4").show();
            $(".lm1").hide();
            $(".lm2").hide();
            $(".lm3").hide();
			   $(".lm5").hide();
         }else if($(this).val()=="Q-dees International Art"){
            $(".lm1").show();
            $(".lm2").hide();
            $(".lm3").hide();
            $(".lm4").hide();
			   $(".lm5").hide();
         }else if($(this).val()=="English"){
            $(".lm1").show();
            $(".lm2").hide();
            $(".lm3").hide();
            $(".lm4").hide();
            $(".lm5").hide();
         }else if($(this).val()=="Beamind Mandarin"){
            $(".lm5").show();
            $(".lm1").hide();
            $(".lm2").hide();
            $(".lm3").hide();
            $(".lm4").hide();
         }else if($(this).val()=="Beamind International Art"){
            $(".lm5").show();
            $(".lm1").hide();
            $(".lm2").hide();
            $(".lm3").hide();
            $(".lm4").hide();
         }
      });
      // getTempBusket();   
   });

   $(document).ready(function() {
      getTempBusket();
   });
</script>
<style>
   .dataTables_length{
      display:none;
   }
</style>
<div class="uk-grid">
   <div class="uk-width-1-2 uk-form">
      <div class="uk-text-center myheader">
         <h2 class="myheader-text-color">Search Product</h2>
      </div>
      <select name="course_name" id="course_name" class="uk-width-1-3">
         <option value="">Student Entry Level </option>
         <option value="EDP">EDP</option>
         <option value="QF1">QF1</option>
         <option value="QF2">QF2</option>
         <option value="QF3">QF3</option>
      </select>
      <select style="width:14%;" name="term" id="term" class="uk-width-1-3">
         <option value="">Term</option>
         <?php  
            $term_list = mysqli_query($connection,"SELECT `term_num` FROM `schedule_term` WHERE `year` = '".$_SESSION['Year']."' AND `centre_code` = '".$_SESSION['CentreCode']."' AND `deleted` = '0'");

            while($term_row = mysqli_fetch_array($term_list))
            {
         ?>
               <option value="<?php echo $term_row['term_num']; ?>"><?php echo $term_row['term_num']; ?></option>
         <?php
            }
         ?>
      </select>
	  <select name="subject" id="subject" class="uk-width-1-3">
         <option value="">Subject</option>
         <option value="Thematic English">Thematic English</option>
         <option value="Phonics">Phonics</option>
         <option value="LTR">Link&Think Reading Series</option>
         <option value="Mandarin">Mandarin</option>
         <option value="Bahasa Malaysia">Bahasa Malaysia</option>
         <option value="Maths">I/Q Math</option>
         <option value="Science">Science</option>
         <option value="Character Building">Character Building</option>
         <option value="Music">Music</option>
         <option value="Art & Craft">Art & Craft</option>
         <option value="Multimedia Songs">Multimedia Songs</option>
         <option value="EDP1">EDP 1</option>
         <option value="EDP2">EDP 2</option>
         <option value="EDP3">EDP 3</option>
         <option value="EDP4">EDP 4</option>
		 <option value="International English">International English</option>
         <option value="International Art">Q-dees International Art</option>
         <option value="Beamind Mandarin">Beamind Mandarin</option>
      </select>

      <select name="module" id="module" class="uk-width-1-3">
         <option value="">Module</option>

         <option class="lm1" style="display:none" value="MOD 01">Module 1</option>
         <option class="lm1" style="display:none" value="MOD 02">Module 2</option>
         <option class="lm1" style="display:none" value="MOD 03">Module 3</option>
         <option class="lm1" style="display:none" value="MOD 04">Module 4</option>
         <option class="lm1" style="display:none" value="MOD 05">Module 5</option>
         <option class="lm1" style="display:none" value="MOD 06">Module 6</option>
         <option class="lm1" style="display:none" value="MOD 07">Module 7</option>
         <option class="lm1" style="display:none" value="MOD 08">Module 8</option>
         <option class="lm1" style="display:none" value="MOD 09">Module 9</option>
         <option class="lm1" style="display:none" value="MOD 10">Module 10</option>
         <option class="lm1" style="display:none" value="MOD 11">Module 11</option>
         <option class="lm1" style="display:none" value="MOD 12">Module 12</option>
		 
         <option class="lm2" style="display:none" value="EDP-MOD 01">ED 1</option>
         <option class="lm2" style="display:none" value="EDP-MOD 02">ED 2</option>
         <option class="lm2" style="display:none" value="EDP-MOD 03">ED 3</option>
         <option class="lm2" style="display:none" value="EDP-MOD 04">ED 4</option>
		   <option class="lm2" style="display:none" value="MOD 01">Module 1</option>
         <option class="lm2" style="display:none" value="MOD 02">Module 2</option>
         <option class="lm2" style="display:none" value="MOD 03">Module 3</option>
         <option class="lm2" style="display:none" value="MOD 04">Module 4</option>
         <option class="lm2" style="display:none" value="MOD 05">Module 5</option>
         <option class="lm2" style="display:none" value="MOD 06">Module 6</option>
         <option class="lm2" style="display:none" value="MOD 07">Module 7</option>
         <option class="lm2" style="display:none" value="MOD 08">Module 8</option>
         <option class="lm2" style="display:none" value="MOD 09">Module 9</option>
         <option class="lm2" style="display:none" value="MOD 10">Module 10</option>
         <option class="lm2" style="display:none" value="MOD 11">Module 11</option>
         <option class="lm2" style="display:none" value="MOD 12">Module 12</option>
		 
         <option class="lm3" style="display:none" value="MOD 1">Module 1</option>
		 
		 <option class="lm4" style="display:none" value="Prep Module A">Prep Module A</option>
		 <option class="lm4" style="display:none" value="Prep Module B">Prep Module B</option>
		 <option class="lm4" style="display:none" value="MOD 01">Module 1</option>
         <option class="lm4" style="display:none" value="MOD 02">Module 2</option>
         <option class="lm4" style="display:none" value="MOD 03">Module 3</option>
         <option class="lm4" style="display:none" value="MOD 04">Module 4</option>
         <option class="lm4" style="display:none" value="MOD 05">Module 5</option>
         <option class="lm4" style="display:none" value="MOD 06">Module 6</option>
         <option class="lm4" style="display:none" value="MOD 07">Module 7</option>
         <option class="lm4" style="display:none" value="MOD 08">Module 8</option>
         <option class="lm4" style="display:none" value="MOD 09">Module 9</option>
         <option class="lm4" style="display:none" value="MOD 10">Module 10</option>
		 
		 <option class="lm5" style="display:none" value="MOD 01">Module 1</option>
         <option class="lm5" style="display:none" value="MOD 02">Module 2</option>
         <option class="lm5" style="display:none" value="MOD 03">Module 3</option>
         <option class="lm5" style="display:none" value="MOD 04">Module 4</option>
         <option class="lm5" style="display:none" value="MOD 05">Module 5</option>
         <option class="lm5" style="display:none" value="MOD 06">Module 6</option>
         <option class="lm5" style="display:none" value="MOD 07">Module 7</option>
         <option class="lm5" style="display:none" value="MOD 08">Module 8</option>
         <option class="lm5" style="display:none" value="MOD 09">Module 9</option>
         <option class="lm5" style="display:none" value="MOD 10">Module 10</option>
         <option class="lm5" style="display:none" value="MOD 11">Module 11</option>
         <option class="lm5" style="display:none" value="MOD 12">Module 12</option>
      </select>
      <input name="s" id="s" value="" placeholder="Product Code/Name" class="uk-form-small">
      <a class="uk-button uk-button-small" id="btnSearch">Search</a>
      <div id="lstProduct"></div>
   </div>
   <div class="uk-width-1-2 uk-form">
      <div class="uk-text-center myheader">
         <h2 class="myheader-text-color">Product Basket Content</h2>
      </div>
      <div id="lstBusket"></div>
      <a onclick="doPutInBusket()" class="uk-button uk-button-small uk-button-primary uk-align-right">Put in Basket</a>
      <a class="uk-button uk-button-small uk-align-right" onclick="$('#products-dialog').dialog('close');">Cancel</a>
   </div>
</div>
<style>
   .uk-width-1-3 {
    width: 30%;
    margin-right: 10px!important;
    margin-bottom: 10px!important;
}
</style>