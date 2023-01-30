
<?php
session_start();
if ($_SESSION["isLogin"]==1) {
   if (($_SESSION["UserType"]=="A")) {
      include_once("mysql.php");
      include_once("admin/functions.php");
      include_once("lib/pagination/pagination.php");
      include_once("search_new.php");
      $p=$_GET["p"];
      $m=$_GET["m"];
      $get_sha1_id=$_GET["id"];
      $pg=$_GET["pg"];
      $mode=$_GET["mode"];

      $str_module="Class";
      $p_module="course";

      foreach ($_GET as $key=>$value) {
         $$key=$value;
      }

      if ($mode=="") {
         $mode="ADD";
      }

      include_once($p_module."_func.php");
?>
<script>
function doDeleteRecord(id) {
   UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function () {
      $("#sha_id").val(id);
      $("#frmDeleteRecord").submit();
   });
}

// $(document).ready(function () {
//    onProgrammeChange();
// });
</script>

<style type="text/css">
    #mydatatable_length{
display: none;
}

#mydatatable_paginate{float:initial;text-align:center}
#mydatatable_paginate .paginate_button{display: inline-block; min-width: 16px; padding: 3px 5px; line-height: 20px; text-decoration: none; -moz-box-sizing: content-box; box-sizing: content-box; text-align: center; background: #f7f7f7; color: #444444; border: 1px solid rgba(0, 0, 0, 0.2); border-bottom-color: rgba(0, 0, 0, 0.3); background-origin: border-box; background-image: -webkit-linear-gradient(top, #ffffff, #eeeeee); background-image: linear-gradient(to bottom, #ffffff, #eeeeee); text-shadow: 0 1px 0 #ffffff;margin-left: 3px;
    margin-right: 3px}
#mydatatable_paginate .paginate_button.current{background: #009dd8; color: #ffffff!important; border: 1px solid rgba(0, 0, 0, 0.2); border-bottom-color: rgba(0, 0, 0, 0.4); background-origin: border-box; background-image: -webkit-linear-gradient(top, #00b4f5, #008dc5); background-image: linear-gradient(to bottom, #00b4f5, #008dc5); text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.2);}
#mydatatable_filter{width:100%}
#mydatatable_filter label{width:100%;display:inline-flex}
#mydatatable_filter label input{height: 30px;width: 100%; padding: 4px 6px; border: 1px solid #dddddd; background: #ffffff; color: #444444; -webkit-transition: all linear 0.2s; transition: all linear 0.2s; border-radius: 4px;}
</style>

<div class="uk-margin-right">
   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color">Fee Structure Settings</h2>
   </div>
   <div class="uk-form uk-form-small">
<?php
if (($_SESSION["UserType"]=="A")) {
?>
    <form name="frmCourse" id="frmCourse" method="post" action="index.php?p=c_fee&pg=<?php echo $pg?>&mode=SAVEFEE">
<?php
}
?>

<style type="text/css">
   tr.spaceUnder>td {
  padding-bottom: 1.5em;
}
</style>
      <div class="uk-grid">
         <div class="uk-width-medium-10-10">
            <table class=" uk-table-small" style="width: 100%;"> 
               <input type="hidden" name="ID" value="<?php echo $edit_row['ID']?>">
               <tr class=" spaceUnder">
                  <td class="uk-width-3-10 uk-text-bold">Name of fee structure : </td>
                  <td class="uk-width-7-10"><input class="uk-width-1-1" type="text" name="name" id="name" value="<?php echo $edit_row['name']?>">
               <span id="validationName"  style="color: red; display: none;">Please Insert Name</span> 
                  </td>
               </tr>

               <tr class=" spaceUnder">
                  <td class="uk-width-3-10 uk-text-bold">Select Student Entry Level : </td>
                  <td class="uk-width-7-10">
                     <?php
                     $fields=array("EDP"=>"EDP", "QF1"=>"QF1", "QF2"=>"QF2", "QF3"=>"QF3");
                     generateSelectArray($fields, "student_entry_level", "class='uk-width-1-1' id='student_entry_level'", $edit_row["student_entry_level"]);
                     ?>     
              <span id="validationStudentEntryLevel"  style="color: red; display: none;">Please Select Student Entry Level</span>  
                  </td>
               </tr>
              
               <tr class=" spaceUnder">
                  <td class="uk-width-3-10 uk-text-bold">Programme package : </td>
                  <td class="uk-width-7-10"><?php
                     $fields=array("Full Day"=>"Full Day", "Half Day"=>"Half Day", "3/4 Day"=>"3/4 Day");
                     generateSelectArray($fields, "programme_duration", "class='uk-width-1-1' id='programme_duration' ", $edit_row["duration"]);
                   ?>   
             <span id="validationDuration"  style="color: red; display: none;">Please Select Duration</span>       
          </td>
               </tr>

               <tr class=" spaceUnder">
                  <td class="uk-width-3-10 uk-text-bold">Commencement Date : </td>
                  <td class="uk-width-7-10">

                    <input style="width: 49%;" placeholder="Start Date"  type="text" data-uk-datepicker="{format: 'DD/MM/YYYY'}" name="start_date" id="start_date" value="<?php echo convertDate2British($edit_row['commencement_date']); ?>">

                    <input style="width: 50%;" placeholder="End Date"  type="text" data-uk-datepicker="{format: 'DD/MM/YYYY'}" name="programme_date" id="programme_date" value="<?php echo convertDate2British($edit_row['commencement_date']); ?>"  >

                    <br>
                      <span id="validationStartDate"  style="color: red; display: none;">Please Select Start Date</span>

                      
                      <span id="validationCommencement"  style="color: red; display: none; margin-left: 29%;">Please Select End Date</span>

                   </td>
                   
               </tr>

               <tr class=" spaceUnder">
                  <td class="uk-width-3-10 uk-text-bold">School Fees : </td>
                  <td class="uk-width-7-10"><input class="uk-width-1-1" type="number" name="school" id="school" value="<?php echo $edit_row['school_fees']?>">
                  <span id="validationSchool"  style="color: red; display: none;">Please Insert School Fees</span>
                  </td>
               </tr>

               <tr class=" spaceUnder">
                  <td class="uk-width-3-10 uk-text-bold">Multimedia Fees : </td>
                  <td class="uk-width-7-10"><input class="uk-width-1-1" type="number" name="multimedia" id="multimedia" value="<?php echo $edit_row['multimedia_fees']?>">
                  <span id="validationMultimedia"  style="color: red; display: none;">Please Insert Multimedia Fees</span>
                  </td>
               </tr>

               <tr class=" spaceUnder">
                  <td class="uk-width-3-10 uk-text-bold">Facilities Fees : </td>
                  <td class="uk-width-7-10"><input class="uk-width-1-1" type="number" name="facilities" id="facilities" value="<?php echo $edit_row['facilities_fees']?>">
                  <span id="validationFacilities"  style="color: red; display: none;">Please Insert Facilities Fees</span>
                  </td>
               </tr>

               <tr class=" spaceUnder">
                  <td class="uk-width-3-10 uk-text-bold">Enchanced Foundation : </td>
                  <td class="uk-width-7-10">
                     <?php
                     $fields=array("Eng"=>"Int. Eng", "Math"=>"EF IQ Math", "Mandarin"=>"EF Mandarin", "Art"=>"Int Art");
                     generateSelectArray($fields, "foundation", "class='uk-width-1-1' id='foundation'", $edit_row["enchanced_foundation"]);
                     ?>     
              <span id="validationFoundation"  style="color: red; display: none;">Please Select Enchanced Foundation</span>  
              <br>
              <input class="uk-width-1-1" type="number" name="foundation_fees" id="foundation_fees" value="<?php echo $edit_row['foundation_fees']?>" placeholder="Please insert Foundation Fees" style="display: none;">
                  <span id="validationFoundationFees"  style="color: red; display: none;">Please Insert Foundation Fees</span>
           </td>
               </tr>

            </table>
         </div>


         <div class="uk-width-medium-10-10">
          <br><br>
            <table class=" uk-table-small" style="width: 100%;"> 
               <tr class=" spaceUnder">
                  <td class="uk-width-3-10 uk-text-bold">Material Fees : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" type="number" name="material" id="material" value="<?php echo $edit_row['material_fees']?>">
                  <span id="validationMaterial"  style="color: red; display: none;">Please Insert Material Fees</span>
                  </td>
               </tr>
              
               <tr class=" spaceUnder">
                  <td class="uk-width-3-10 uk-text-bold">MSS : </td>
                  <td class="uk-width-7-10"><input class="uk-width-1-1" type="number" name="mss" id="mss" value="<?php echo $edit_row['mss']?>">
                  <span id="validationMSS"  style="color: red; display: none;">Please Insert MSS</span>
                  </td>
               </tr>

               <tr class=" spaceUnder">
                  <td class="uk-width-3-10 uk-text-bold">Mandarin : </td>
                  <td class="uk-width-7-10"><input class="uk-width-1-1" type="number" name="mandarin" id="mandarin" value="<?php echo $edit_row['mandarin']?>">
                  <span id="validationMandarin"  style="color: red; display: none;">Please Insert Mandarin</span>
                  </td>
               </tr>

             
            </table>

          
         </div>

         <div class="uk-width-medium-10-10">
          <br><br>
            <table class=" uk-table-small" style="width: 100%;"> 
               <tr class=" spaceUnder">
                  <td class="uk-width-3-10 uk-text-bold">Registration : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" type="number" name="registration" id="registration" value="<?php echo $edit_row['registration']?>">
                     <span id="validationRegistration"  style="color: red; display: none;">Please Insert Registration</span>
                  </td>
               </tr>
              
               <tr class=" spaceUnder">
                  <td class="uk-width-3-10 uk-text-bold">Mobile : </td>
                  <td class="uk-width-7-10"><input class="uk-width-1-1" type="number" name="mobile" id="mobile" value="<?php echo $edit_row['mobile']?>">
                  <span id="validationMobile"  style="color: red; display: none;">Please Insert Mobile</span>
                  </td>
               </tr>

               <tr class=" spaceUnder">
                  <td class="uk-width-3-10 uk-text-bold">Placement Fee : </td>
                  <td class="uk-width-7-10"><input class="uk-width-1-1" type="number" name="placement" id="placement" value="<?php echo $edit_row['placement_fee']?>">
                  <span id="validationPlacement"  style="color: red; display: none;">Please Insert Placement</span>
                  </td>
               </tr>

            </table>

          
         </div>
         
      </div>
      <br>
      <div class="uk-text-center">
<?php
if (($_SESSION["UserType"]=="A")) {
?>
         <button class="uk-button uk-button-primary">Save</button>
<?php
}
?>
      </div>
<?php
if (($_SESSION["UserType"]=="A")) {
?>
   </form>
<?php
}
?>
   </div>
</div>

  <br><br>
   <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
   <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css">
  <div class="uk-margin-right">
   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color">Fees Listing</h2>
   </div>
   

        <div class="nice-form" >
            <table class='uk-table' id='mydatatable' style="width: 100%;">
               <thead>
                  <tr>
                     <td></td>
                     <td>No</td>
                     <td>Name</td>
                     <td>Student Entry Level</td>
                     <td>Programme package</td>
                     <td>Start Date</td>
                     <td>End Date</td>
                     <td>School Fees</td>
                     <td>Multimedia Fees</td>
                     <td>Facilities Fees</td>
                     <td>Enchanced Foundation</td>
                     <td>Foundation Fees</td>
                     <td>Material Fees</td>
                     <td>MSS</td>
                     <td>Mandarin</td>
                     <td>Registration</td>
                     <td>Mobile</td>
                     <td>Placement Fee</td>
                     <td>Action</td>
                  </tr>
               </thead>

               <tbody>
                   <?php  $sql="SELECT * from centre_fees order by ID";
            $result=mysqli_query($connection, $sql);?>
            <?php while ($row=mysqli_fetch_assoc($result)):?>
               <?php $sha1_id=sha1($row["ID"]); ?>
                  <tr>
                     <td></td>
                     <td><?=$row['ID']?></td>
                     <td><?=$row['name']?></td>
                     <td><?=$row['student_entry_level']?></td>
                     <td><?=$row['duration']?></td>
                     <td><?=$row['start_date']?></td>
                     <td><?=$row['end_date']?></td>
                     <td><?=$row['school_fees']?></td>
                     <td><?=$row['multimedia_fees']?></td>
                     <td><?=$row['facilities_fees']?></td>
                     <td><?=$row['enchanced_foundation']?></td>
                     <td><?=$row['foundation_fees']?></td>
                     <td><?=$row['material_fees']?></td>
                     <td><?=$row['mss']?></td>
                     <td><?=$row['mandarin']?></td>
                     <td><?=$row['registration']?></td>
                     <td><?=$row['mobile']?></td>
                     <td><?=$row['placement_fee']?></td>
                     <td>
                        <a href="index.php?p=c_fee&id=<?php echo $sha1_id?>&mode=EDITFEE" data-uk-tooltip title="Edit <?php echo $row['name']?>"><i class="fas fa-user-edit" style="font-size: 1.3em;"></i></a>

                        <a href="index.php?p=c_fee&id=<?php echo $sha1_id?>&mode=DELFEE" data-uk-tooltip title="Delete <?php echo $row['name']?>" id="btnDelete"><i style="font-size: 1.3em; color: #FF6e6e;" class="fas fa-box-open"></i></a></td>
                 </tr>
                   <?php endwhile;?>
               </tbody>
               

            </table>
        </div>
     
  
</div>
   
<?php
if ($msg!="") {
   echo "<script>UIkit.notify('$msg')</script>";
}
   } else {
      echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
   }
} else {
   header("Location: index.php");
}
?>

  <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>

<script type="text/javascript">

  $(document).ready(function(){
    $("#start_date").datepicker({
        numberOfMonths: 1,
        onSelect: function(selected) {
          $("#programme_date").datepicker("option","minDate", selected)
        }
    });
    $("#programme_date").datepicker({ 
        numberOfMonths: 1,
        onSelect: function(selected) {
           $("#start_date").datepicker("option","maxDate", selected)
        }
    });  
});



   $(document).ready(function() {
    $('#mydatatable').DataTable( {
        responsive: {
            details: {
                type: 'column',
                target: 'tr'
            }
        },
        columnDefs: [ {
            className: 'control',
            orderable: false,
            targets:   0
        } ],
        order: [ 1, 'asc' ]
    } );
} );

   $('#foundation').on('change', function() {
  $("#foundation_fees").show();
});
   
    $('#frmCourse').submit(function() {

   var name=$("#name").val(); 
   var student_entry_level=$("#student_entry_level").val();
   var programme_duration=$("#programme_duration").val();
   var programme_date=$("#programme_date").val();
   var start_date=$("#start_date").val();
   var school=$("#school").val();
   var multimedia=$("#multimedia").val();
   var facilities=$("#facilities").val();
   var foundation=$("#foundation").val();
   var material=$("#material").val();
   var mss=$("#mss").val();
   var mandarin=$("#mandarin").val();
   var registration=$("#registration").val();
   var mobile=$("#mobile").val();
   var placement=$("#placement").val();
   var foundation_fees=$("#foundation_fees").val();     

   // alert(!student_entry_level);
   
    if ( !name || !student_entry_level || !programme_duration || !programme_date || !start_date || !school || 
      !multimedia || !facilities || !foundation || !material || !mss || !mandarin || !registration 
      ||  !mobile || !placement || !foundation_fees  ) {

        UIkit.notify("Please fill up mandatory fields marked *");
       
       if (!name) {
            $('#validationName').show();
        }else{
            $('#validationName').hide();
        }

        if (!student_entry_level) {
            $('#validationStudentEntryLevel').show();
        }else{
            $('#validationStudentEntryLevel').hide();
        }

        if (!programme_duration) {
            $('#validationDuration').show();
        }else{
            $('#validationDuration').hide();
        }

        if (!programme_date) {
            $('#validationCommencement').show();
        }else{
            $('#validationCommencement').hide();
        }

        if (!start_date) {
            $('#validationStartDate').show();
        }else{
            $('#validationStartDate').hide();
        }

        if (!school) {
            $('#validationSchool').show();
        }else{
            $('#validationSchool').hide();
        }

        if (!multimedia) {
            $('#validationMultimedia').show();
        }else{
            $('#validationMultimedia').hide();
        }

        if (!facilities) {
            $('#validationFacilities').show();
        }else{
            $('#validationFacilities').hide();
        }

        if (!foundation) {
            $('#validationFoundation').show();
        }else{
            $('#validationFoundation').hide();
        }

        if (!material) {
            $('#validationMaterial').show();
        }else{
            $('#validationMaterial').hide();
        }

        if (!mss) {
            $('#validationMSS').show();
        }else{
            $('#validationMSS').hide();
        }

        if (!mandarin) {
            $('#validationMandarin').show();
        }else{
            $('#validationMandarin').hide();
        }

        if (!registration) {
            $('#validationRegistration').show();
        }else{
            $('#validationRegistration').hide();
        }

        if (!mobile) {
            $('#validationMobile').show();
        }else{
            $('#validationMobile').hide();
        }

        if (!placement) {
            $('#validationPlacement').show();
        }else{
            $('#validationPlacement').hide();
        }

        if (!foundation_fees) {
            $('#validationFoundationFees').show();
        }else{
            $('#validationFoundationFees').hide();
        }
       
        return false;
    }
});

</script>
