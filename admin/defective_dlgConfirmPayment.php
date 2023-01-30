<?php
include_once("../mysql.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);
}

?>

<script>

$(document).ready(function () {
   $("#submit").click(function () {
      var upload=$("#doc").val();
      var extension="";
      var uploadvalue=upload.toLowerCase(upload);

      if (uploadvalue.indexOf(".jpg")!=-1) {
         extension=".jpg";
      } else {
         if (uploadvalue.indexOf(".pdf")!=-1) {
            extension=".pdf";
         } else {
            extension="";
         }
      }

      if (extension != "") {
         $("#extension").val(extension);
         var theform = $("#frmConfirm")[0];
         var formdata = new FormData(theform);

         $.ajax({
            url : "admin/defective_doConfirmPayment.php",
            type : "POST",
            data : formdata,
            dataType : "text",
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            success : function(response, status, http) {
               var s=response.split("|");
               if (s[0]=="1") {
                  UIkit.notify(s[1]);
                  $("#btnConfirmPayment").hide();
//                  $("#btnConfirmPayment").prop("disabled", true);
//                  $("#btnConfirmPayment").prop("onclick", null).off("click");
                  $("#dlg").dialog("close");
//                  location.reload();
               }

               if (s[0]=="0") {
                  UIkit.notify(s[1]);
               }
            },
            error : function(http, status, error) {
              UIkit.notify("Error:"+error);
            }
         });
      } else {
         UIkit.notify("Please select JPG or PDF file only");
      }
   });
});
</script>

<form name="frmConfirm" id="frmConfirm" method="post" enctype="multipart/form-data" action="defective_doConfirmPayment.php">
   <input type="file" name="doc" accept=".jpg,.jpeg,.pdf" class="uk-form uk-width-1-1" id="doc" value=""><br><br>
   <input type="hidden" name="id" id="id" value="<?php echo $id?>">
   <input type="hidden" name="extension" id="extension" value="">
   <input type="hidden" name="sOrderNo" id="sOrderNo" value="<?php echo $sOrderNo?>">
   <a id="submit" class="uk-button">Submit</a>
   <a onclick="$('#dlg').dialog('close');" class="uk-button">Close</a>
</form>