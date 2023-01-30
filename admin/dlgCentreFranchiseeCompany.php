<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");

$centre_code=$_POST["centre_code"];

//echo "User Type is ".$_SESSION["UserType"];

if (($_SESSION["isLogin"]==1) & ($_SESSION["UserType"]=="S")) {
?>
<script>
function doSave(centre_code) {
   var theform=$("#frmSaveDate")[0];
   var formdata=new FormData(theform);

   $.ajax({
      url : "admin/dlgCentreFranchiseeCompanySave.php",
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
         $("#dlgCentreFranchiseeCompany").dialog("close");
         ajaxUpdateCompany();
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}

function deleteRow(id) {
   $("table#tblCentreFranchiseeCompany tr#"+id).remove();
}

function addRow() {
   var html="";
   var row_id="row"+Math.floor(Math.random() * 1000000);

   html=html+"<tr class=\"uk-text-small\" id='"+row_id+"'>";
   html=html+"   <td><input type=\"text\" class=\"uk-width-1-1\" placeholder=\"Company Name\" name=\"franchisee_company_name[]\" id=\"company_name\" value=\"\" required></td>";
   html=html+"   <td><input type=\"text\" class=\"uk-width-1-1\" placeholder=\"Company No.\" name=\"franchisee_company_no[]\" id=\"company_no\" value=\"\" required></td>";
   html=html+"   <td><a><img data-uk-tooltip=\"{pos:top}\" title=\"Remove Row\" onclick=\"deleteRow('"+row_id+"');\" src=\"images/delete.png\"></a></td>";
   html=html+"</tr>";

   $("#tblCentreFranchiseeCompany").append(html);
}
</script>

<div class="uk-form">
<?php
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "CentreEdit"))) {
?>
<form class="uk-form" name="frmSaveDate" id="frmSaveDate" method="post" enctype="multipart" action="admin/dlgCentreFranchiseeCompanySave.php">
<?php
}
?>
   <input type="hidden" name="centre_code" id="centre_code" value="<?php echo $centre_code?>">
   <table class="uk-table uk-form-small" id="tblCentreFranchiseeCompany">
      <tr class="uk-text-small uk-text-bold">
         <td>Company Name</td>
         <td data-uk-tooltip="{pos:top}" title="Company No.">C. No.</td>
         <td><a><img data-uk-tooltip="{pos:top}" title="Add Record" onclick="addRow();" src="images/add.png"></a></td>
      </tr>
<?php
$sql="SELECT * from centre_franchisee_company where centre_code='$centre_code' order by id";
//echo $sql;
$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);
while ($row=mysqli_fetch_assoc($result)) {
   $row_id=generateRandomString(8);
?>
      <tr class="uk-text-small" id="<?php echo $row_id?>">
         <td><input type="text" class="uk-width-1-1" placeholder="Company Name" name="franchisee_company_name[]" id="franchisee_company_name" value="<?php echo $row['franchisee_company_name']?>" required></td>
         <td><input type="text" class="uk-width-1-1" placeholder="Company No." name="franchisee_company_no[]" id="franchisee_company_no" value="<?php echo $row['franchisee_company_no']?>" required></td>
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
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "CentreEdit"))) {
?>
   <a onclick="doSave('<?php echo $centre_code?>');" class="uk-button uk-button-small uk-button-primary">Save</a>
<?php
}
?>
   <a onclick="$('#dlgCentreFranchiseeCompany').dialog('close');" class="uk-button uk-button-small">Close</a>
<?php
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "CentreEdit"))) {
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