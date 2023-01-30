<?php
session_start();
include_once("../mysql.php");
foreach($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //$sha_id, $sOrderNo
}
?>

<script>
function doAssignedTo(id, sOrderNo) {
   var person=$("#person").val();
   var checker=$("#checker").val();

   if (person!="" && checker !="") {
      UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function () {
         $.ajax({
            url : "admin/do_assigned_to.php",
            type : "POST",
            data : "id="+id+"&person="+person+"&checker="+checker+"&sOrderNo="+sOrderNo,
            dataType : "text",
            beforeSend : function(http) {
            },
            success : function(response, status, http) {
               var s=response.split("|");

               if (s[0]=="1") {
                  location.reload();
               } else {
                  UIkit.notify(s[1]);
               }
            },
            error : function(http, status, error) {
               UIkit.notify("Error:"+error);
            }
         });
      });
   } else {
     // if(person==""){
         UIkit.notify("Please select a person");
      // }
      // if(checker==""){
      //    UIkit.notify("Please select a person");
      // }
      
   }
}
</script>
<form class="uk-form" name="frmCancel" id="frmCancel" method="post" action="admin/do_cancelled.php">
A. Packer <br>
   <select name="person" id="person">
		<option value="">Select</option>
<?php
if ($_SESSION["UserType"]=="S") {
   $sql="SELECT * from user where is_active=1 and user_type='S'";
} else {
   if ($_SESSION["UserType"]=="A") {
      $sql="SELECT * from user where is_active=1 and user_type='S' and centre_code='".$_SESSION["CentreCode"]."'";
   }
}
$result=mysqli_query($connection, $sql);
while ($row=mysqli_fetch_assoc($result)) {
?>
      <option value="<?php echo $row['user_name']?>"><?php echo $row["user_name"];?></option>
<?php
}
?>
	</select><br><br>
   B. Checker<br>
   <select name="checker" id="checker">
		<option value="">Select</option>
<?php
if ($_SESSION["UserType"]=="S") {
   $sql="SELECT * from user where is_active=1 and user_type='S'";
} else {
   if ($_SESSION["UserType"]=="A") {
      $sql="SELECT * from user where is_active=1 and user_type='S' and centre_code='".$_SESSION["CentreCode"]."'";
   }
}
$result=mysqli_query($connection, $sql);
while ($row=mysqli_fetch_assoc($result)) {
?>
      <option value="<?php echo $row['user_name']?>"><?php echo $row["user_name"];?></option>
<?php
}
?>
	</select><br><br>

   <a onclick="doAssignedTo('<?php echo $sha_id?>', '<?php echo $sOrderNo?>')" class="uk-button">Submit</a>
   <a onclick="$('#dlg').dialog('close');" class="uk-button">Cancel</a>
</form>