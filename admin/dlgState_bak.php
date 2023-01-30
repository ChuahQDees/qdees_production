<?php
session_start();
include_once("../mysql.php");

$master_code=$_POST["master_code"];
$country=$_POST["country"];

//echo "User Type is ".$_SESSION["UserType"];

function generateRandomString($length) {
   $characters='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
   $charactersLength=strlen($characters);
   $randomString='';
   for ($i=0; $i<$length; $i++) {
      $randomString.=$characters[rand(0, $charactersLength-1)];
   }
   return $randomString;
}

if (($_SESSION["isLogin"]==1) & ($_SESSION["UserType"]=="S")) {
?>
<script>
function doSave() {
   var theform=$("#frmSaveDate")[0];
   var formdata=new FormData(theform);

   $.ajax({
      url : "admin/save_master_state.php",
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
         $("#dlgMasterState").dialog("close");
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}

function deleteRow(id) {
   $("table#tblMasterState tr#"+id).remove();
}

function addRow() {
   var html="";
   var row_id="row"+Math.floor(Math.random() * 1000000);

   html=html+"<tr class=\"uk-text-small\" id='"+row_id+"'>";
   html=html+"   <td>";
   html=html+"      <select name=\"state[]\" id=\"state\" class=\"uk-form\">"
   html=html+"         <option value=\"\">State</option>";
<?php
$sql="SELECT * from codes where module='STATE' and country='$country' order by code";
$state_result=mysqli_query($connection, $sql);
while ($state_row=mysqli_fetch_assoc($state_result)) {
   $state=$state_row["code"];
   echo "html=html+\"         <option value='$state'>$state</option>\";";
}
?>
   html=html+"      </select>";
   html=html+"   </td>";
   html=html+"   <td><a><img data-uk-tooltip=\"{pos:top}\" title=\"Remove Date\" onclick=\"deleteRow('"+row_id+"');\" src=\"images/delete.png\"></a>";
   html=html+"   </td>";
   html=html+"</tr>";

   $("#tblMasterState").append(html);
}
</script>

<form class="uk-form" name="frmSaveDate" id="frmSaveDate" method="post" enctype="multipart" action="admin/save_master_franchise_date.php">
   <input type="hidden" name="master_code" id="master_code" value="<?php echo $master_code?>">
   <input type="hidden" name="country" id="country" value="<?php echo $country?>">
   <table class="uk-table uk-form-small uk-table-condensed" id="tblMasterState">
      <tr class="uk-text-small uk-text-bold">
         <td>State</td>
         <td><a><img data-uk-tooltip="{pos:top}" title="Add State" onclick="addRow();" src="images/add.png"></a></td>
      </tr>
<?php
$sql="SELECT * from master_state where master_code='$master_code' order by id";
//echo $sql;
$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);
while ($row=mysqli_fetch_assoc($result)) {
   $row_id=generateRandomString(8);
?>
      <tr class="uk-text-small" id="<?php echo $row_id?>">
         <td>
            <select name="state[]" id="state" class="uk-form">
               <option value="">State</option>
<?php
$sql="SELECT * from codes where module='STATE' and country='$country' order by code";
$state_result=mysqli_query($connection, $sql);
while ($state_row=mysqli_fetch_assoc($state_result)) {
?>
               <option value="<?php echo $state_row["code"]?>" <?php if ($row['state']==$state_row['code']) {echo "selected";}?>><?php echo $state_row["code"]?></option>
<?php
}
?>
            </select>
         </td>
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
   <a onclick="doSave();" class="uk-button uk-button-small uk-button-primary">Save</a>
</form>
<?php
} else {
   echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
}
?>