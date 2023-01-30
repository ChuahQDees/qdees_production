<?php

session_start();

include_once("../mysql.php");

foreach($_POST as $key=>$value) {

   $$key=mysqli_real_escape_string($connection, $value); //$id, $sOrderNo

}

?>



<script>

function doProvePayment(sha_id, sOrderNo) {

  

   var theform = $("#frmProvePayment")[0];

   var formdata = new FormData(theform);
   var filesize = 0;
   var validate = true;
   $('#doc').each(function (a, b) {
      var fileInput = $('#doc')[a];
      if (fileInput.files.length > 0) {
         var file = fileInput.files[0];
         filesize = file.size;
         if (filesize > 104857600) {
            UIkit.notify('File size must be less than 100MB!');
            validate = false;
         }
         //console.log(filesize);
      }
   })
   if(validate){
      $.ajax({

         url : "admin/do_provePayment.php",

         type : "POST",

         data : formdata,

         enctype: 'multipart/form-data',

         processData: false,

         contentType: false,

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
   }

}



 

function filePreview(input) {

    if (input.files && input.files[0]) {

        var reader = new FileReader();

        reader.onload = function (e) {

            $('#frmProvePayment + img').remove();
            var extension = e.target.result.split(";");
            //console.log(extension[0])
            if(extension[0]=='data:application/pdf'){
               $('#frmProvePayment').after('<img  src="/admin/uploads/pdf.webp"  width="200" height="" style="margin: 0 auto;display: block;"/>');
            }else{
               $('#frmProvePayment').after('<img src="'+e.target.result+'" width="200" height="" style="margin: 0 auto;display: block;"/>');
            }
            

        };

        reader.readAsDataURL(input.files[0]);

    }

}





$("#doc").change(function () {

    filePreview(this);

});



</script>



<form class="uk-form" name="frmProvePayment" id="frmProvePayment">

  <table class="uk-table uk-table-small uk-table-striped">

    <input type="hidden" name="sOrderNo" value="<?php echo $sOrderNo?>">

    <tr class="uk-text-small">

      <td class="uk-text-bold">Photo</td>

      <td><input type="file" id="doc" name="doc[]" multiple></td>

    </tr>

    <tr class="uk-text-small">

      <td class="uk-text-bold">Remarks</td>

      <td><textarea class="uk-width-1-1" id="remarks" name="remarks"></textarea></td>

    </tr>

  </table>

  <div class="uk-text-center">

    <a onclick="doProvePayment()" class="uk-button uk-button-primary"  style="padding: 0 12px;">Submit</a>

    <a href=""class="uk-button" >Cancel</a>

    <!-- for now redirecting back to orders on click cancel-->

    <!--  <a onclick="$('#dlgProveOfPayment').dialog('close');" class="uk-button">Cancel</a> -->

  </div>

</form>



<!--<input type="file" name="file" id="profile-img">

<img src="" id="profile-img-tag" width="200px" />  -->



