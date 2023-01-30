<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");

$product_code=$_POST["product_code"];

if (($_SESSION["isLogin"]==1) & ($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "ProductEdit|ProductView"))) {
?>
<script>
function doSave() {
   var theform=$("#frmSaveProductCourse")[0];
   var formdata=new FormData(theform);

   $.ajax({
      url : "admin/dlgProductCourseSave.php",
      type : "POST",
      data : formdata,
      dataType : "text",
      enctype: 'multipart/form-data',
      processData: false,
      contentType: false,
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         UIkit.notify(response);
         $("#dialog").dialog("close");
		 $("#dlgProductCourse").dialog("close");
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}

function deleteRow(id) {
   $("table#tblProductCourse tr#"+id).remove();
}

function addRow() {
   var html="";
   var row_id="row"+Math.floor(Math.random() * 1000000);

   html=html+"<tr class=\"uk-text-small\" id='"+row_id+"'>";
   html=html+"   <td>";

   html=html+"<select name=\"course_id[]\" id=\"course_id\">";
   html=html+"   <option value=\"\">Select</option>";
<?php
$psql="SELECT subject, id from course where deleted=0 order by subject";
$presult=mysqli_query($connection, $psql);
while ($prow=mysqli_fetch_assoc($presult)) {
?>
   html=html+"   <option value='<?php echo $prow["id"]?>'><?php echo $prow["subject"]?></option>";
<?php
}
?>
   html=html+"</select>";
   html=html+"   </td>";
   html=html+"   <td><a><img data-uk-tooltip=\"{pos:top}\" title=\"Remove Row\" onclick=\"deleteRow('"+row_id+"');\" src=\"images/delete.png\"></a></td>";
   html=html+"</tr>";

   $("#tblProductCourse").append(html);
}
</script>

<div class="uk-form">
<?php
if (($_SESSION["isLogin"]==1) & ($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "ProductEdit"))) {
?>
<form name="frmSaveProductCourse" id="frmSaveProductCourse" method="post" enctype="multipart" action="admin/dlgProductCourseSave.php">
<?php
}
?>
   <input type="hidden" name="product_code" id="product_code" value="<?php echo $product_code?>">
   <table class="uk-table">
      <tr>
         <td><?php echo $product_code?></td>
      </tr>
   </table>
   <table class="uk-table uk-form-small" id="tblProductCourse">
      <tr class="uk-text-small uk-text-bold">
         <td>Class</td>
         <td><a><img data-uk-tooltip="{pos:top}" title="Add Record" onclick="addRow();" src="images/add.png"></a></td>
      </tr>
<?php
$sql="SELECT * from product_course where product_code='$product_code' order by id";
//echo $sql;
$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);
while ($row=mysqli_fetch_assoc($result)) {
   $row_id=generateRandomString(8);
?>
      <tr class="uk-text-small" id="<?php echo $row_id?>">
         <td>
            <select name="course_id[]" id="course_id">
               <option value="">Select</option>
<?php
$psql="SELECT subject, id from course where deleted=0 order by subject";
$presult=mysqli_query($connection, $psql);
while ($prow=mysqli_fetch_assoc($presult)) {
?>
               <option value="<?php echo $prow['id']?>" <?php if ($prow["id"]==$row["course_id"]) {echo "selected";}?>><?php echo $prow["subject"]?></option>
<?php
}
?>
            </select>
         </td>
         <td><a><img data-uk-tooltip="{pos:top}" title="Remove Row" onclick="deleteRow('<?php echo $row_id?>');" src="images/delete.png"></a></td>
      </tr>
<?php
}
?>
<?php
if ($num_row==0) { 
?>
      <script>addRow();</script>
<?php
}
?>
   </table>
<?php
if (($_SESSION["isLogin"]==1) & ($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "ProductEdit"))) {
?>
   <a onclick="doSave('<?php echo $centre_code?>');" class="uk-button uk-button-small uk-button-primary">Save</a>
<?php
}
?>
   <a onclick="$('#dlgProductCourse').dialog('close');" class="uk-button uk-button-small">Close</a>
<?php
if (($_SESSION["isLogin"]==1) & ($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "ProductEdit"))) {
?>
</form>
<?php
}
?>
</div>
<?php
} else {
   echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
}
?>