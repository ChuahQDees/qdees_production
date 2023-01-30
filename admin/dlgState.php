<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");

$master_code=$_POST["master_code"];
$country=$_POST["country"];

function isGotState($master_code, $country) {
   global $connection;
   $sql="SELECT * from master_state where master_code='$master_code' and country='$country'";
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);
   if ($num_row>0) {
      return true;
   } else {
      return false;
   }
}

function isStateFound ($master_code, $country, $state) {
   global $connection;

   $sql="SELECT * from master_state where master_code='$master_code' and country='$country' and `state`='$state'";
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      return true;
   } else {
      return false;
   }
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
         $("#dlg").dialog("close");
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
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
   <input type="hidden" name="country" id="country" value="<?php echo $country?>">
<?php
$is_got_state=isGotState($master_code, $country);

$sql="SELECT * from codes where module='STATE' and country='$country' order by code";
$result=mysqli_query($connection, $sql);
while ($row=mysqli_fetch_assoc($result)) {
   $the_state=str_replace(" ", "", $row["code"]);
   if ($is_got_state==true) {
      if (isStateFound($master_code, $country, $row["code"])) {
         $checked="checked";
      } else {
         $checked="";
      }
?>
   <input <?php echo $checked?> class="uk-form" type="checkbox" name="<?php echo $the_state?>" id="<?php echo $the_state?>" value="1">&nbsp;<?php echo $row["code"]?><br>
<?php
   } else {
?>
   <input checked class="uk-form" type="checkbox" name="<?php echo $the_state?>" id="<?php echo $the_state?>" value="1">&nbsp;<?php echo $row["code"]?><br>
<?php
   }
}
?>
   <br>
<?php
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "MasterEdit"))) {
?>
   <a onclick="doSave();" class="uk-button uk-button-small uk-button-primary">Save</a>
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