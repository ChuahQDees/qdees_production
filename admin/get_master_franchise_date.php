<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");

$master_code=$_POST["master_code"];

//echo "User Type is ".$_SESSION["UserType"];

if (($_SESSION["isLogin"]==1) & ($_SESSION["UserType"]=="S")) {
?>
<script>
function doSave(master_code) {
   var theform=$("#frmSaveDate")[0];
   var formdata=new FormData(theform);

   $.ajax({
      url : "admin/save_master_franchise_date.php",
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
         $("#dlg").dialog("close");
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}

function deleteRow(id) {
   $("table#tblMasterFranchiseDate tr#"+id).remove();
}

function addRow() {
   var html="";
   var row_id="row"+Math.floor(Math.random() * 1000000);

   html=html+"<tr class=\"uk-text-small\" id='"+row_id+"'>";
   html=html+"<td><input type=\"text\" class='uk-width-1-1' data-uk-datepicker=\"{format: 'YYYY-MM-DD'}\" placeholder=\"Date\" name=\"date[]\" id=\"date[]\" value=\"\" required></td>";
   html=html+"<td><input type=\"text\" class='uk-width-1-1' placeholder=\"Description\" name=\"description[]\" id=\"description[]\" value=\"\" required></td>";
   html=html+"<td><a><img data-uk-tooltip=\"{pos:top}\" title=\"Remove Date\" onclick=\"deleteRow('"+row_id+"');\" src=\"images/delete.png\"></a>";
   html=html+"</td>";
   html=html+"</tr>";

   $("#tblMasterFranchiseDate").append(html);
}
</script>
<div class="uk-form">
<?php
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "MasterEdit"))) {
?>
<form name="frmSaveDate" id="frmSaveDate" method="post" enctype="multipart" action="admin/save_master_franchise_date.php">
<?php
}
?>
   <input type="hidden" name="master_code" id="master_code" value="<?php echo $master_code?>">
   <table class="uk-table uk-form-small" id="tblMasterFranchiseDate">
      <tr class="uk-text-small uk-text-bold">
         <td>Date</td>
         <td>Description</td>
         <td><a><img data-uk-tooltip="{pos:top}" title="Add Date" onclick="addRow();" src="images/add.png"></a></td>
      </tr>
<?php
$sql="SELECT * from master_franchise_date where master_code='$master_code' order by id";
//echo $sql;
$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);
while ($row=mysqli_fetch_assoc($result)) {
   $row_id=generateRandomString(8);
?>
      <tr class="uk-text-small" id="<?php echo $row_id?>">
         <td><input type="text" class="uk-width-1-1" data-uk-datepicker="{format: 'YYYY-MM-DD'}" placeholder="Date" name="date[]" id="date[]" value="<?php echo $row['date']?>" required></td>
         <td><input type="text" class="uk-width-1-1" placeholder="iption" name="description[]" id="description[]" value="<?php echo $row['description']?>" required></td>
         <td>
            <a><img data-uk-tooltip="{pos:top}" title="Remove Row" onclick="deleteRow('<?php echo $row_id?>');" src="images/delete.png"></a>
         </td>
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
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "MasterEdit"))) {
?>
   <a onclick="doSave('<?php echo $master_code?>');" class="uk-button uk-button-small uk-button-primary">Save</a>
   <a onclick="$('#dlg').dialog('close')" class="uk-button uk-button-small">Close</a>
<?php
}
?>
<?php
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "MasterEdit"))) {
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