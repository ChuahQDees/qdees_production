<?php
//session_start();
include_once("../mysql.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); // $id (sha1)
}
?>
<!--
<table class="uk-table">
   <tr class="uk-text-bold">
      <td>Student Code</td>
      <td>Name</td>
      <td>Gender</td>
      <td>Age</td>
      <td>Status</td>
   </tr>
   
</table>
-->
<script>
function add2CartBundle() {
   $.ajax({
      url: "admin/add_2_cart_bundle.php",
      type: "POST",
      data: "bundle_id=1",
      dataType: "text",
      beforeSend: function(http) {},
      success: function(response, status, http) {
         UIkit.notify(response);
         getNoInCart();
      },
      error: function(http, status, error) {
         UIkit.notification("Error:" + error);
      }
   });
}

function getNoInCart() { //Update the Shopping cart number
   $.ajax({
      url: "admin/get_no_in_cart.php",
      type: "POST",
      data: "1=1",
      dataType: "text",
      beforeSend: function(http) {},
      success: function(response, status, http) {
         $("#no_in_cart").replaceWith("<span id=\"no_in_cart\" class=\"badge badge-pill badge-danger\">" + response + "</span>");
         $("#no_in_cart_mnu").replaceWith("<span id=\"no_in_cart_mnu\" class=\"badge badge-pill badge-danger\">" + response + "</span>");
         
         const s = document.getElementById("message1");
	      s.style.display = "none" ;

         const s2 = document.getElementById("message2");
	      s2.style.display = "block" ;
      },
      error: function(http, status, error) {
         UIkit.notification("Error:" + error);
      }
   });
}
</script>

<div id="message1" style="padding-left: 5px;padding-top:5px;">
   <p style="font-size:15px;">
      Hello!
      <br/>
      We're really glad to have you on-board! This contains a starter pack for new centers: <?php echo $bundleID ?>
   </p>
   <ul style="font-size:15px;">
   <li>Uniform</li>
   <li>Gymwear</li>
   <li>Barbeque Sauce</li>
   </ul>
   <p style="font-size:15px;">
      After clicking on the button below, your shopping cart will be added with the items above. Items which are already in the shopping cart will not be touched.
   </p>

   <div class="uk-text-right" >
      <div class="row">  
         <div class="col-sm-1 col-md-2"></div>   
         <div class="col-sm-10 col-md-4">      
            <button class="uk-width-1-1 uk-button" style="background: transparent; border: 1px solid darkgrey; color: darkgrey;padding: .3em 2em;" onclick="$('#student-dialog').dialog('close');">Close</button>
         </div>   
         <div class="col-sm-10 col-md-4">      
            <a class="uk-width-1-1 uk-button uk-button-primary" style="margin: 0 auto" data-uk-tooltip="{pos:top}" title="Add to Cart" onclick="add2CartBundle()"><span>Add Bundle to Cart</span></a>
         </div>  
         <div class="col-sm-11 col-md-2"></div>
      </div>
   </div>
</div>

<div id="message2" style="padding-left: 5px;padding-top:5px;display: none;">
   <p style="font-size:15px;">
         Your bundle has been added!
         <br />
         Please close this window and click on the shopping cart at the top right to look at your order!
   </p>

   <div class="uk-text-right" >
      <div class="row">  
         <div class="col-sm-1 col-md-4"></div>   
         <div class="col-sm-10 col-md-4">      
            <button class="uk-width-1-1 uk-button" style="background: transparent; border: 1px solid darkgrey; color: darkgrey;padding: .3em 2em;" onclick="$('#student-dialog').dialog('close');">Close</button>
         </div>   
         <div class="col-sm-11 col-md-4"></div>
      </div>
   </div>
</div>
<!-- 
   Need to create a function to check if there's any existing record inside the cart.

   If there's existing record, do NOT update it
-->
</p>