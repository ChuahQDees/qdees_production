<?php
include_once("../mysql.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //$sha_id, $sOrderNo
}

?>

<script>
function doDeliverOrderToLogistic(id, sOrderNo) {
   var thename=$("#name").val();
   var ic_no=$("#ic_no").val();
   var tracking_no=$("#tracking_no").val();
   var courier=$("#courier").val();
   var jsonSignature=$("#jsonSignature").val();
   var remarks=$("#remarks").val();

   //if ((thename!="") & (ic_no!="") & (tracking_no!="") & (courier!="") & (sOrderNo!="")) {
      if ((thename=="") || (ic_no=="") || (courier=="") || (sOrderNo=="")) {
         UIkit.notify("Please provide all field.");
      }else{
      if (courier =='Courier by HQ'){
         if (tracking_no!=""){
            }else {
            UIkit.notify("Please provide a tracking number");
            return false;
         }
      } 
      UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function () {
         var thename=$("#name").val();
         var ic_no=$("#ic_no").val();

         $.ajax({
            url : "admin/defective_do_delivered_to_logistic.php",
            type : "POST",
            data : "sha_id="+id+"&name="+thename+"&ic_no="+ic_no+"&jsonSignature="+jsonSignature+"&tracking_no="+tracking_no+"&courier="+courier+"&sOrderNo="+sOrderNo+"&remarks="+remarks,
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
               UIkit.notification("Error:"+error);
            }
         });
      });
   }
   /*} else {
//      UIkit.notify(thename+"|"+ic_no+"|"+tracking_no+"|"+courier+"|"+jsonSignature+"|"+sOrderNo)
      UIkit.notify("Please provide a name and IC No., tracking number and courier");
   }*/
}
</script>
<style>
.kbw-signature { width: 370px; height: 300px; border:1px;}
</style>
<script src="lib/sign/js/jquery.signature.js"></script>
<script type="text/javascript" src="lib/sign/js/jquery.ui.touch-punch.min.js"></script>
<script>
$(function() {
   var sig = $('#sig').signature({background:'#EFEFEF', syncField: '#jsonSignature'});
});

function clear_signature() {
   $("#sig").signature('clear');
}
</script>

<form class="uk-form" name="frmCancel" id="frmCancel" method="post" action="admin/do_delivered_to_logistic.php">
   <div class="uk-grid uk-grid-small">
      <div class="uk-width-2-10">
         <label class="uk-label" for="name">Name as per IC</label>
         <input name="name" class="uk-width-1-" id="name" type="text" placeholder="Name" value="">
      </div>
      <div class="uk-width-2-10">
         <label class="uk-label" for="ic_no">ID/IC/Passport Number</label>
         <input name="ic_no" class="uk-width-1-1" id="ic_no" type="text" placeholder="IC Number" value="">
      </div>
      <div class="uk-width-2-10">
         <label class='uk-label' for="courier">Collection type</label>
         <select name="courier" id="courier" class="uk-width-1-1">
            <option value="">Select</option>
            <?php
            $sql="SELECT code from codes where module='COURIER' order by code";
            $result=mysqli_query($connection, $sql);
            while ($row=mysqli_fetch_assoc($result)) {
            ?>
                        <option value="<?php echo $row['code']?>"><?php echo $row["code"]?></option>
            <?php
            }
            ?>
         </select>
      </div>
      <div class="uk-width-2-10">
         <label class='uk-label' for="tracking_no">Tracking number</label><br>
         <input type="text" name="tracking_no" class="uk-width-1-1" id="tracking_no" value="">
      </div>
      
      
      <div class="uk-width-2-10">
         <label class='uk-label' for="courier">Remarks</label><br>
         <input name="remarks" class="uk-width-1-1" id="remarks" type="text" placeholder="Remarks" value="">
      </div>
   </div>
   <div class="uk-grid">
      <div class="uk-width-3-10">
         <label class="uk-label" for="sig">Signature</label>
         <div id="sig"></div>
         <a onclick="clear_signature();" class="uk-button uk-button-small">Clear Signature</a><br><br>
         <a onclick="doDeliverOrderToLogistic('<?php echo $sha_id?>', '<?php echo $sOrderNo?>')" class="uk-button uk-button-primary">Submit</a>
         <a onclick="$('#dlg').dialog('close');" class="uk-button">Close</a>
      </div>
      <div class="uk-width-7-10">
         <table class="uk-table">
            <tr class="uk-text-small uk-text-bold">
               <td>Product Code</td>
               <td>Description</td>
               <td>Qty</td>
            </tr>
<?php
$sql="SELECT o.*, p.product_name from `defective` o, product p where sha1(o.order_no)='$sOrderNo'
and p.product_code=o.product_code order by o.product_code";
$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);
if ($num_row>0) {
   while ($row=mysqli_fetch_assoc($result)) {
?>
            <tr class="uk-text-small">
               <td><?php echo explode("((--",$row["product_code"])[0] ?></td>
               <td><?php echo $row["product_name"]?></td>
               <td><?php echo $row["qty"]?></td>
            </tr>
<?php
   }
} else {
   echo "<tr class='uk-text-small uk-text-bold'><td colspan='5'>No record found</td></tr>";
}
?>
         </table>
      </div>
   </div>
   <input type="hidden" name="jsonSignature" id="jsonSignature" value="">
</form>