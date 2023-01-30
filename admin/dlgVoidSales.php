<?php
include_once("../mysql.php");
foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);//id (sha1)
}
?>

<div class="uk-form uk-margin-top uk-margin-left uk-margin-right">
   <div class="uk-width-1-1">
      Reason<br>
      <input name="void_reason" id="void_reason" type="text" size="30" placeholder="Please provide a reason" class="uk-width-1-1 uk-form-small" value=""><br><br>
      <a onclick="doVoid('<?php echo $id?>')" class="uk-button uk-button-small">Void</a>
   </div>
</div>
