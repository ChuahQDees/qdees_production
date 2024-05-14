<a href="/" id="aBack"><span class="btn-qdees d_none"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span></a>
<span>
    <span class="page_title"><img src="/images/title_Icons/Payment.png">Payment</span>
</span>

<?php
if (($_SESSION["isLogin"]==1) & (($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) &
(hasRightGroupXOR($_SESSION["UserName"], "PointOfSalesEdit|PointOfSalesView"))) {
include_once("mysql.php");
include_once("admin/functions.php");
include_once("lib/pagination/pagination.php");

$year=$_SESSION['Year'];
$centre_code=$_SESSION["CentreCode"];

$p=$_GET["p"];
$level_type=$_GET["level_type"];

?>

<div id="thetop" class="uk-margin-right uk-margin-top">
   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color myheader-text-style">Order Bundles</h2>
   </div>
   <style>
      .d_none{
         display:none!important;   
      }
   </style>

   <div id="sctListing">
      <div class="uk-overflow-container">
         <table id="mydatatable" class="uk-table" style="font-size:12px !important;">
            <thead>
               <tr class="uk-text-small uk-text-bold">
                  <td></td><!-- Bundle Image -->
                  <td>Bundle Name</td>
                  <td>Bundle Description</td>
                  <td></td>
               </tr>
            </thead>
            <tbody>
               <?php 
               $sql = "SELECT * from `order_bundle_name`";
               $result = mysqli_query($connection, $sql);
               $num_row = mysqli_num_rows($result);

               while($row=mysqli_fetch_assoc($result)) {
               ?>
               <tr>
                  <td width="5%"><img src="<?php echo $row["thumbnail_link"] ?>" style="max-width:150px;"></td>
                  <td width="25%"><?php echo $row["bundle_name"] ?></td>
                  <td width="50%"><?php echo $row["bundle_description"] ?></td>
                  <td width="20%"><center><a onclick="bundleMessage(<?php echo $row['bundle_id'] ?>)" class="uk-button uk-button-primary form_btn uk-text-center">More Details</a></center></td>
               </tr>
               <?php
               }
               ?>
               <!--
               <tr>
                  <td width="5%"><img src="admin/uploads/4213958.png" style="max-width:100px;"></td>
                  <td width="25%">Center Starters Pack</td>
                  <td width="50%">A bundle for new centers. Strongly reccomended to take this bundle as a base to get your center started!</td>
                  <td width="20%"><center><a onclick="bundleMessage()" class="uk-button uk-button-primary form_btn uk-text-center">More Details</a></center></td>
               </tr>
               <tr>
                  <td width="5%"><img src="admin/uploads/qbqSMOMA.jpg" style="max-width:100px;"></td>
                  <td width="25%">Term 3 Module Bundle</td>
                  <td width="50%">Minimum stuff for the new term. Please remember to adjust the quantity!</td>
                  <td width="20%"><center><a onclick="bundleMessage()" class="uk-button uk-button-primary form_btn uk-text-center">More Details</a></center></td>
               </tr>
               -->
         </tbody>
         </table>
      </div>

   </div>
</div>
<div id="student-dialog"></div>
<?php
} else {
   echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
}
?>

<script>
   function bundleMessage(bundleID){
      $.ajax({ url: "admin/bundleMessage2.php",
         type : "POST", 
	      data : "bundleID="+bundleID,
         dataType : "text",
         beforeSend : function(http) {
      },
      async: false,
      success : function(response, status, http) {
         $("#student-dialog").html(response);
		 $("#student-dialog").dialog({
               dialogClass:"no-close",
               title:"WELCOME TO Q-DEES!",
               modal:true,
               height:'auto',
               width:'60%',
            });
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
   }
   </script>

<style>
			
   table tr:not(:first-child):nth-of-type(even) {
      background: #f5f6ff;
   }

   table td {
      color: grey;
      font-size: 1.2em;
   }

   table td {border: none!important;}

   #mydatatable_length{
      display: none;
   }

   #mydatatable_filter{
      display: none;
   }

   /*#mydatatable_paginate{float:initial;text-align:center}*/
   #mydatatable_paginate .paginate_button{display: inline-block; min-width: 16px; padding: 3px 5px; line-height: 20px; text-decoration: none; -moz-box-sizing: content-box; box-sizing: content-box; text-align: center; background: #f7f7f7; color: #444444; border: 1px solid rgba(0, 0, 0, 0.2); border-bottom-color: rgba(0, 0, 0, 0.3); background-origin: border-box; background-image: -webkit-linear-gradient(top, #ffffff, #eeeeee); background-image: linear-gradient(to bottom, #ffffff, #eeeeee); text-shadow: 0 1px 0 #ffffff;margin-left: 3px;
   margin-right: 3px}
   #mydatatable_paginate .paginate_button.current{background: #009dd8; color: #ffffff!important; border: 1px solid rgba(0, 0, 0, 0.2); border-bottom-color: rgba(0, 0, 0, 0.4); background-origin: border-box; background-image: -webkit-linear-gradient(top, #00b4f5, #008dc5); background-image: linear-gradient(to bottom, #00b4f5, #008dc5); text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.2);}
   #mydatatable_filter{width:100%}
   #mydatatable_filter label{width:100%;display:inline-flex}
   #mydatatable_filter label input{height: 30px;width: 100%; padding: 4px 6px; border: 1px solid #dddddd; background: #ffffff; color: #444444; -webkit-transition: all linear 0.2s; transition: all linear 0.2s; border-radius: 4px;}
</style>