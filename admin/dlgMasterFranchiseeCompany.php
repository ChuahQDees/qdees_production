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
      url : "admin/dlgMasterFranchiseeCompanySave.php",
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
         ajaxUpdateCompany();
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}

function deleteRow(id) {
   $("table#tblMasterFranchiseeCompany tr#"+id).remove();
}

function addRow() {
   var html="";
   var row_id="row"+Math.floor(Math.random() * 1000000);

   html=html+"<tr class='uk-text-small' id='"+row_id+"'>";
   html=html+"   <td><input type='text' class='uk-width-1-1' placeholder='Company Name' name='franchisee_company_name[]' id='franchisee_company_name[]' value='' required></td>";
   html=html+"   <td><input type='text' class='uk-width-1-1' placeholder='Company No.' name='franchisee_company_no[]' id='franchisee_company_no[]' value='' required></td>";
   html=html+"   <td>";
   html=html+"      <input type='text' class='uk-width-1-1' placeholder='Registered Address' name='franchisee_registered_address1[]' id='franchisee_registered_address1[]' value='' required>";
   html=html+"      <input type='text' class='uk-width-1-1' placeholder='Registered Address' name='franchisee_registered_address2[]' id='franchisee_registered_address2[]' value='' required>";
   html=html+"      <input type='text' class='uk-width-1-1' placeholder='Registered Address' name='franchisee_registered_address3[]' id='franchisee_registered_address3[]' value='' required>";
   html=html+"      <input type='text' class='uk-width-1-1' placeholder='Registered Address' name='franchisee_registered_address4[]' id='franchisee_registered_address4[]' value='' required>";
   html=html+"   </td>";
   html=html+"   <td><input type='text' class='uk-width-1-1' placeholder='Contact Number' name='franchisee_company_contact_no[]' id='franchisee_company_contact_no[]' value='' required></td>";
   html=html+"   <td><input type='text' class='uk-width-1-1' placeholder='Email Address' name='franchisee_company_email[]' id='franchisee_company_email[]' value='' required></td>";
   html=html+"   <td>";
   html=html+"      <a><img data-uk-tooltip='{pos:top}' title='Remove Row' onclick='deleteRow('"+row_id+"');' src='images/delete.png'></a>";
   html=html+"   </td>";
   html=html+"</tr>";

   $("#tblMasterFranchiseeCompany").append(html);
}
</script>
<div class="uk-form">
<?php
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "MasterEdit"))) {
?>
<form name="frmSaveDate" id="frmSaveDate" method="post" enctype="multipart" action="admin/dlgMasterFranchiseeCompanySave.php">
<?php
}
?>
   <input type="hidden" name="master_code" id="master_code" value="<?php echo $master_code?>">
   <table class="uk-table uk-form-small" id="tblMasterFranchiseeCompany">
      <tr class="uk-text-small uk-text-bold">
         <td>Company Name</td>
         <td data-uk-tooltip="{pos:top}" title="Company No.">Company No.</td>
         <td data-uk-tooltip="{pos:top}" title="Registered Address">Registered Address</td>
         <td data-uk-tooltip="{pos:top}" title="Contact No.">Contact No.</td>
         <td data-uk-tooltip="{pos:top}" title="Email Address">Email</td>
         <td><a><img data-uk-tooltip="{pos:top}" title="Add Record" onclick="addRow();" src="images/add.png"></a></td>
      </tr>
<?php
$sql="SELECT * from master_franchisee_company where master_code='$master_code' order by id";
//echo $sql;
$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);
while ($row=mysqli_fetch_assoc($result)) {
   $row_id=generateRandomString(8);
?>
      <tr class="uk-text-small" id="<?php echo $row_id?>">
         <td><input type="text" class="uk-width-1-1" placeholder="Company Name" name="franchisee_company_name[]" id="franchisee_company_name[]" value="<?php echo $row['franchisee_company_name']?>" required></td>
         <td><input type="text" class="uk-width-1-1" placeholder="Company No." name="franchisee_company_no[]" id="franchisee_company_no[]" value="<?php echo $row['franchisee_company_no']?>" required></td>
         <td>
            <input type="text" class="uk-width-1-1" placeholder="Registered Address" name="franchisee_registered_address1[]" id="franchisee_registered_address1[]" value="<?php echo $row['franchisee_registered_address1']?>" required>
            <input type="text" class="uk-width-1-1" placeholder="Registered Address" name="franchisee_registered_address2[]" id="franchisee_registered_address2[]" value="<?php echo $row['franchisee_registered_address2']?>" required>
            <input type="text" class="uk-width-1-1" placeholder="Registered Address" name="franchisee_registered_address3[]" id="franchisee_registered_address3[]" value="<?php echo $row['franchisee_registered_address3']?>" required>
            <input type="text" class="uk-width-1-1" placeholder="Registered Address" name="franchisee_registered_address4[]" id="franchisee_registered_address4[]" value="<?php echo $row['franchisee_registered_address4']?>" required>
         </td>
         <td><input type="text" class="uk-width-1-1" placeholder="Contact Number" name="franchisee_company_contact_no[]" id="franchisee_company_contact_no[]" value="<?php echo $row['franchisee_company_contact_no']?>" required></td>
         <td><input type="text" class="uk-width-1-1" placeholder="Email Address" name="franchisee_company_email[]" id="franchisee_company_email[]" value="<?php echo $row['franchisee_company_email']?>" required></td>
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
<?php
}
?>
   <a onclick="$('#dlg').dialog('close');" class="uk-button uk-button-small">Close</a>
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