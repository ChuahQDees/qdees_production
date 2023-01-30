<?php
include_once("functions.php");



if ($_SESSION["isLogin"]==1) {
   if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) &
   (hasRightGroupXOR($_SESSION["UserName"], "StudentEdit|StudentView"))) {
      include_once("mysql.php");
      include_once("lib/pagination/pagination.php");
      include_once("admin/functions.php");
      $p=$_GET["p"];
      $m=$_GET["m"];
      $student_sha_id=$_GET["student_sid"];
      $get_sha1_id=$_GET["sid"];
      $pg=$_GET["pg"];
      $mode=$_GET["mode"];

      include_once("dropout_func.php");
?>

<script>
function doDeleteRecord(id) {
   UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function () {
      $("#id").val(id);
      $("#frmDeleteRecord").submit();
   });
}
</script>
<a href="/index.php?p=student_population">
             <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>
<div class="uk-margin-right">
   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color myheader-text-style">Dropout Record</h2>
   </div>
   <div class="uk-form uk-form-small">
<?php
if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) &
   (hasRightGroupXOR($_SESSION["UserName"], "StudentEdit|StudentView"))) {
   if ($mode=="ADD") {
?>
   <form name="frmDropout" id="frmDropout" method="post" class="uk-form uk-form-small" action="index.php?p=dropout&pg=<?php echo $pg?>&student_sid=<?php echo $student_sha_id; ?>&mode=SAVE" enctype="multipart/form-data">
<?php
   }
   else {
?>
   <form name="frmDropout" id="frmDropout" method="post" class="uk-form uk-form-small" action="index.php?p=dropout&pg=<?php echo $pg?>&sid=<?php echo $get_sha1_id; ?>&mode=SAVE" enctype="multipart/form-data">
<?php 
   }
}
?>
      <div class="uk-grid">
         <div class="uk-width-medium-5-10">
            <table class="uk-table uk-table-small">
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Student :</td>
                  <td class="uk-width-7-10">
<?php
if ($student_code!="") {
?>
                     <?php echo $name?>
                     <input type="hidden" name="student_code" id="student_code" value="<?php echo $student_code?>">
<?php
} else {
?>
                     <select name="student_code" id="student_code" class="uk-form-small uk-width-1-1" >
                        <option value="">Select</option>
<?php
$sql="SELECT student_code, name from student where student_status='A' and deleted='0'
and centre_code='".$_SESSION["CentreCode"]."' order by name";
$result=mysqli_query($connection, $sql);
while ($row=mysqli_fetch_assoc($result)) {
?>
                        <option value="<?php echo $row['student_code']?>" <?php if ($row['student_code']==$edit_row['student_code']) {echo "selected";}?>><?php echo $row['name']?></option>
<?php
}
?>
                     </select>
<?php
}
?>
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Dropout Date : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" type="text" name="dropout_date" id="dropout_date" data-uk-datepicker="{format: 'DD/MM/YYYY'}" value="<?php echo convertDate2British($edit_row['dropout_date'])?>" >
					 <span id="validationdropout_date"  style="color: red; display: none;">Please input Dropout Date</span>
                  </td>
               </tr>
            </table>
         </div>
         <div class="uk-width-medium-5-10">
            <table class="uk-table uk-table-small">
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Reason : </td>
                  <td class="uk-width-7-10">
                     <select name="reason" id="reason" class="uk-form-small uk-width-1-1" >
                        <option value="">Reason</option>
<?php
$sql="SELECT * from codes where module='DROPOUTREASON' and code <> 'Other Dropout Reason' order by code";
$result=mysqli_query($connection, $sql);
while ($row=mysqli_fetch_assoc($result)) {
?>
                        <option value="<?php echo $row['code']?>" <?php if ($row['code']==$edit_row['reason']) {echo "selected";}?>><?php echo $row["code"]?></option>
<?php
}
?>
<option value="Other Dropout Reason" <?php if ($edit_row['reason']=="Other Dropout Reason") {echo "selected";}?>>Other Dropout Reason</option>
                     </select>
					 <span id="validationreason"  style="color: red; display: none;">Please select Reason</span>
                  </td>
               </tr>
<tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Remark : </td>
                  <td class="uk-width-7-10">
                     <input name="remark" id="remark" class="uk-form-small uk-width-1-1" 
                     value="<?php echo $edit_row['remarks']?>">
					 <span id="validationremark"  style="color: red; display: none;">Please input Remark</span>
            </table>
         </div>
      </div>
      <br>
      <div class="uk-text-center">
<?php
if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) &
   (hasRightGroupXOR($_SESSION["UserName"], "StudentEdit|StudentView"))) {

?>
         <button class="uk-button uk-button-primary">Save</button>
         <div class="uk-text-center">By clicking Save button, the student will be set to inactive and all further transaction is impossible. Action IRREVERSIBLE.</div>
<?php
}
?>
      </div>
<?php
if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) &
   (hasRightGroupXOR($_SESSION["UserName"], "StudentEdit|StudentView"))) {
?>
   </form>
<?php
}
?>
   </div>
   <br>

   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color myheader-text-style">Searching</h2>
   </div>
<?php
$numperpage=20;
$query="p=$p&m=$m&s=$s";
$pagination=getPagination($pg, $numperpage, $query, $browse_sql, $start_record, $num_row);
$browse_sql.=" limit $start_record, $numperpage";
$browse_result=mysqli_query($connection, $browse_sql);
$browse_num_row=mysqli_num_rows($browse_result);
//echo $pagination;
?>
   <form class="uk-form" name="frmDropoutSearch" id="frmDropoutSearch" method="get">
      <input type="hidden" name="mode" id="mode" value="BROWSE">
      <input type="hidden" name="p" id="p" value="<?php echo $p?>">
      <input type="hidden" name="m" id="m" value="<?php echo $m?>">
      <input type="hidden" name="pg" value="">

      <div class="uk-grid">
         <div class="uk-width-7-10 uk-text-small">
            <input class="uk-width-1-1" placeholder="Student Code/Name" name="s" id="s" value="<?php echo $_GET['s']?>">
         </div>
         <div class="uk-width-3-10">
            <button class="uk-button uk-width-1-1">Search</button>
         </div>
      </div>
   </form><br>

   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color myheader-text-style">Student Population</h2>
   </div>
   <div class="uk-overflow-container">
   <table class="uk-table">
      <tr class="uk-text-bold uk-text-small">
         <td>Student Code</td>
         <td>Student Name</td>
         <td>Dropout Date</td>
         <td>Reason</td>
         <td>Remark</td>
         <td>Action</td>
      </tr>
<?php
if ($browse_num_row>0) {
   while ($browse_row=mysqli_fetch_assoc($browse_result)) {
      $sha1_id=sha1($browse_row["id"]);
?>
      <tr class="uk-text-small">
         <td><?php echo $browse_row["student_code"]?></td>
         <td><?php echo $browse_row["name"]?></td>
         <td><?php echo $browse_row["dropout_date"]?></td>

         <?php //if($browse_row["reason"] == 'Other Dropout Reason'):?>
         <!--<td><?php //echo $browse_row["reason"]?> - <?php //echo $browse_row["remark"]?></td>-->
         <?php //else:?>
          <td><?php echo $browse_row["reason"]?></td>
         <?php //endif;?>
		 <td><?php echo $browse_row["remarks"]?></td>
         <td>
            <a href="index.php?p=<?php echo $p?>&m=<?php echo $m?>&sid=<?php echo $sha1_id?>&mode=EDIT"><img src="images/edit.png"></a>
          <!--  <a onclick="doDeleteRecord('<?php echo $sha1_id?>')" href="#" id="btnDelete"><img src="images/delete.png"></a>-->
         </td>
      </tr>
<?php
   }
} else {
   echo "<tr><td colspan='6'>No Record Found</td></tr>";
}
?>
   </table>
   </div>
<?php
echo $pagination;
?>
</div>

<form name="frmDeleteRecord" id="frmDeleteRecord" method="get">
   <input type="hidden" name="p" value="<?php echo $p?>">
   <input type="hidden" name="m" value="<?php echo $m?>">
   <input type="hidden" name="id" id="id" value="">
   <input type="hidden" name="mode" value="DEL">
</form>
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


<script type="text/javascript">
$('document').ready(function(){
	 $("#frmDropout").submit(function(e){
    
    var dropout_date=$("#dropout_date").val();
    var reason=$("#reason").val();
    var remark=$("#remark").val();
	var isError=false;
     if (!dropout_date) {
            $('#validationdropout_date').show();
			isError=true;
			e.preventDefault();
        }else{
            $('#validationdropout_date').hide();
        }
		
		if (!reason) {
            $('#validationreason').show();
			isError=true;
			e.preventDefault();
        }else{
            $('#validationreason').hide();
        }
		
		if (reason=="Other Dropout Reason") {
            if (!remark) {
				$('#validationremark').show();
				isError=true;
				e.preventDefault();
			}else{
				$('#validationremark').hide();
			}
        }
		
if(isError){
	 
	return false;
}
    
    if (!start_date || !end_date || !programme || !level || !module || !group) {


 
   if (!start_date) {
            $('#validationstart_date').show();
        }else{
            $('#validationstart_date').hide();
        }

         if (!end_date) {
            $('#validationend_date').show();
        }else{
            $('#validationend_date').hide();
        }

         if (!programme) {
            $('#validationprogramme').show();
        }else{
            $('#validationprogramme').hide();
        }

         if (!level) {
            $('#validationlevel').show();
        }else{
            $('#validationlevel').hide();
        }

         if (!module) {
            $('#validationmodule').show();
        }else{
            $('#validationmodule').hide();
        }

         if (!group) {
            $('#validationgroup').show();
        }else{
            $('#validationgroup').hide();
        }
        
        return false;
  }
  });
  
   // $('#reason').on('change', function() {
	  // if( this.value == 'Other Dropout Reason' ) {
	   // $("#remark").prop('re',true);
	  // }else{
	   // $("#remark").prop('required',false);
	  // }
	// });
 });

 
</script>