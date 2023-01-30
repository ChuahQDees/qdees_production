<?php
   session_start();
   include_once("../mysql.php");
   include_once("functions.php");
   foreach($_POST as $key=>$value) {
      $$key=mysqli_real_escape_string($connection, $value); //$sha_id, $sOrderNo
   }
   $sha_id = $_POST['sha_id'];
   $sOrderNo = $_POST['sOrderNo'];
   $reject = isset($_POST['reject']) ? 1 : 0;
?>

<script>

function doCancel(id, sOrderNo, reject = 0) {
   var reason=$("#reason").val();

   if (reason!="") {
      UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function () {
         var reason=$("#reason").val();
         $.ajax({
            url : "admin/do_cancelled.php",
            type : "POST",
            data : "id="+id+"&reason="+reason+"&sOrderNo="+sOrderNo+"&reject="+reject,
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
      UIkit.notify("Please provide a reason");
   }
}
</script>
<form class="uk-form" name="frmCancel" id="frmCancel" method="post" action="admin/do_cancelled.php">
   <input name="reason" class="uk-width-1-" id="reason" type="text" placeholder="Cancel Reason" value=""><br><br>
   <a onclick="doCancel('<?php echo $sha_id?>', '<?php echo $sOrderNo?>',<?php echo $reject; ?>)" class="uk-button">Submit</a>
   <a onclick="$('#dlg').dialog('close');" class="uk-button">Cancel</a>
</form>