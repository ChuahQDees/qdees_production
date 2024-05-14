<?php
 if ($_GET['sOrderNo'] != "") { ?>
   <a href="/index.php?p=order_status_pg1">
      <span class="d_n btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
   </a>
<?php } else { ?>
   <a href="/">
      <span class="d_n btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
   </a>
<?php } ?>
<span>
   <span class="page_title"><img src="/images/title_Icons/Current Status Order.png">Current Order Status</span>
</span>

<?php
include_once("admin/functions.php");
if ($_SESSION["isLogin"] == 1) {
   if ((($_SESSION["UserType"] == "A") || ($_SESSION["UserType"] == "S") || ($_SESSION["UserType"] == "O") || ($_SESSION["UserType"] == "H") || ($_SESSION["UserType"] == "C") || ($_SESSION["UserType"] == "R") || ($_SESSION["UserType"] == "CM") || ($_SESSION["UserType"] == "T")) &
      (hasRightGroupXOR($_SESSION["UserName"], "OrderStatusView|OrderStatusEdit"))
   ) {

      include_once("search_new.php");

      function isDelivered($order_no)
      {
         global $connection;

         $sql = "SELECT * from `order` where order_no='$order_no'";;
         $result = mysqli_query($connection, $sql);
         $row = mysqli_fetch_assoc($result);
         if ($row["delivered_to_logistic_by"] != "") {
            return true;
         } else {
            return false;
         }
      }

      function getStatus($order_no)
      {
         global $connection;

         $sql = "SELECT * from `order` where order_no='$order_no'";

         $result = mysqli_query($connection, $sql);
         if ($result) {
            $row = mysqli_fetch_assoc($result);

            if ($row["cancelled_by"] != "") {
               return "Cancelled";
            } else {
               if ($row["delivered_to_logistic_by"] != "") {
                  if ($row["finance_payment_paid_by"] != "") {
                 
                     return ($_SESSION["UserType"] == "S") ? "Delivered" : "Delivered";
                  } else {
                  
                     return ($_SESSION["UserType"] == "S") ? "Delivered" : "Delivered"; 
                  }
               } else {
                  if ($row["packed_by"] != "") {
                     if ($row["finance_payment_paid_by"] != "") {
                       
                        return ($_SESSION["UserType"] == "S") ? "Ready for Collection" : "Ready for Collection";
                     } else {
                       
                        return ($_SESSION["UserType"] == "S") ? "Ready for Collection" : "Ready for Collection";  
                     }
                  } else {
                     if ($row["finance_approved_by"] != "") {
                        if ($row["finance_payment_paid_by"] != "") {
                           return ($_SESSION["UserType"] == "S") ? "Finance Approved (Paid)" : "Finance Approved";
                        } else {
                           return ($_SESSION["UserType"] == "S") ? "Finance Approved (Pending Payment)" : "Finance Approved";
                        }
                     } else {
                        if ($row["logistic_approved_by"] != "") {
                           return "Packing";
                        } else {
                           if ($row["acknowledged_by"] != "") {
                              return "Acknowledged";
                           } else {
                              return "Pending";
                           }
                        }
                     }
                     
                  }
               }
            }
         }
      }

      function DateTime($person, $datetime)
      {
         if ($person != "") {
            return $datetime;
         } else {
            return "NA";
         }
      }

      foreach ($_GET as $key => $value) {
         $$key = $value;
      }
?>
      <script>
         $(document).ready(function() {
			
			 var centre_code= $('#centre_code').find(":selected").val();
		
			 $('#company_name').val(centre_code);
			 
            $("#btnClear").click(function() {
               $("#sOrderNo").val("");
               $("#sCentreCode").val("");
               $("#frmStatus").submit();
            });
         });
      </script>

      <div class="uk-margin-top uk-margin-right">
         <div class="uk-width-1-1 myheader">
            <h2 class="uk-text-center myheader-text-color myheader-text-style">Search</h2>
         </div>
         <div class="uk-overflow-container">
            <form name="frmStatus nostyle" style="background: transparent!important; box-shadow: none!important;" id="frmStatus" method="get" class="uk-form">
               <div class="uk-grid">
				 <div  style="padding-right: 5px; width:14%;" class="uk-width-2-10 uk-text-small">
					<input type="text" name="sOrderNo" size="12" id="sOrderNo" placeholder="Order No" value="<?php echo $_GET['sOrderNo'] ?>">
				 </div>
				      <div style="padding-left: 5px;padding-right: 5px; width:15%;" class="uk-width-2-10 uk-text-small">
                     <select style="width: 100%;" name="status" id="status">
                        <option value="">Select Status</option>
                        <option <?php if($_GET["status"]=="Pending") echo "selected" ?> value="Pending">Pending</option>
                        <option <?php if($_GET["status"]=="Acknowledged") echo "selected" ?> value="Acknowledged">Acknowledged</option> 
						<?php if ($_SESSION["UserType"] != "A"){?>
						<option style="font-weight: bold;" <?php if($_GET["status"]=="Finance Pending (F)") echo "selected" ?> value="Finance Pending (F)">Finance Pending (F)</option>
						<option style="font-weight: bold;" <?php if($_GET["status"]=="Logistics Pending (L)") echo "selected" ?> value="Logistics Pending (L)">Logistics Pending (L)</option>
						<?php } ?>
                        <option <?php if($_GET["status"]=="Packing") echo "selected" ?> value="Packing">Packing</option>
                        <option <?php if($_GET["status"]=="Ready for Collection") echo "selected" ?> value="Ready for Collection">Ready for Collection</option>
						<!--
                        <option <?php if($_GET["status"]=="Ready for Collection (F)") echo "selected" ?> value="Ready for Collection (F)">Ready for Collection (F)</option>
						-->
						<?php if ($_SESSION["UserType"] != "A"){?>
						      <option style="font-weight: bold;"<?php if($_GET["status"]=="Ready for Collection (S)") echo "selected" ?> value="Ready for Collection (S)">Ready for Collection (S)</option>
						<?php } ?>
                        <option <?php if($_GET["status"]=="Delivered") echo "selected" ?> value="Delivered">Delivered</option>	
                        <option <?php if($_GET["status"]=="Cancelled") echo "selected" ?> value="Cancelled">Cancelled</option>
                     </select>          
				      </div>
				  
                  <?php
                     if (($_SESSION["UserType"] == "S") || ($_SESSION["UserType"] == "H") || ($_SESSION["UserType"] == "C") || ($_SESSION["UserType"] == "R") || ($_SESSION["UserType"] == "CM") || ($_SESSION["UserType"] == "T")) {
                  ?>
				  <div style="padding-left: 5px;padding-right: 5px; width:14%;" class="uk-width-2-10 uk-text-small">
					 <!--Centre Name<br>-->
               <input type="hidden"  id="hfCenterCode" name="sCentreCode1">
               <input list="centre_code" id="company_name" name="company_name" value="" placeholder="Select Centre Name">
				<datalist class="form-control" id="centre_code" style="display:none;">				
				
                            
							<?php
								$company_name= $_GET["company_name"];
								$sql = "SELECT * from centre order by centre_code";
								$result_centre = mysqli_query($connection, $sql);                        
                        
                        while ($row=mysqli_fetch_assoc($result_centre)) {
                     ?>
                           <option value="<?php echo $row['company_name']?>" <?php if($_GET["company_name"]==$row["company_name"]){ echo 'selected'; }?> > <?php echo $row["centre_code"]; ?></option>
                     <?php
                        }
                     ?>
				</datalist>
				 <script>
                      var objCompanyName = document.getElementById('company_name');
                      $(document).on('change', objCompanyName, function(){
                  
                          var options = $('datalist')[0].options;
                          for (var i=0;i<options.length;i++){
                     
                            if (options[i].value == $(objCompanyName).val()) 
                              {
                                $("#hfCenterCode").val(options[i].text);
                                break;
                              }
                          }
                      });
				 
                    </script>   
					 </div>
                  <?php
                  }
                  ?>
				  
				   <div style="padding-left: 5px;padding-right: 5px; width:14%;" class="uk-width-2-10 uk-text-small">
                  
                     <input type="hidden" name="p" id="p" value="<?php echo $_GET['p'] ?>">
                     <input type="text" data-uk-datepicker="{format:'DD/MM/YYYY'}" placeholder="Order Date From" name="date_from" id="date_to" value="<?php echo $_GET['date_from'] ?>" autocomplete="off">
                  </div>
                  <div style="padding-left: 5px;padding-right: 5px; width:14%;" class="uk-width-2-10 uk-text-small">
                     <input type="text" data-uk-datepicker="{format:'DD/MM/YYYY'}" placeholder="Order Date To" name="date_to" id="date_to" value="<?php echo $_GET['date_to'] ?>" autocomplete="off">
                  </div>

                  <div style="padding-left: 5px;padding-right: 5px; width:14%;" class="uk-width-4-10 uk-text-small">
                     <button id="dsearch" class="uk-button ">Search</button>
                     <a class="uk-button" href="index.php?p=order_status_pg1" id="btnClear">Clear</a>
                  </div>
               </div>
            </form>

            <?php
               include_once("../mysql.php");
               $year = $_SESSION['Year'];
         
               foreach ($_GET as $key => $value) {
                  $$key = mysqli_real_escape_string($connection, $value);
               }

               $sOrderNo=$_GET["sOrderNo"];
               $status=$_GET["status"];
               $company_name=$_GET["company_name"];
               $date_from=$_GET["date_from"];
               $date_to=$_GET["date_to"];
            ?>
         </div>
   <div style="margin-top:30px;" class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color">LISTING</h2>
   </div>
    <div class="uk-overflow-container">

         <table class="uk-table" id="mydatatable">
            <thead>
               <tr class="uk-text-bold uk-text-small">
                  <?php
                  if (($_SESSION["UserType"] == "S") || ($_SESSION["UserType"] == "H") || ($_SESSION["UserType"] == "C") || ($_SESSION["UserType"] == "R") || ($_SESSION["UserType"] == "CM") || ($_SESSION["UserType"] == "T")) {
                  ?>
                     <td>Centre Name</td>
                  <?php
                  }
                  ?>
                  <td>Order No.</td>
                  <td>Ordered By</td>
                  <td>Ordered On</td>
                  <td>Status</td>
                  <td>Shipping Method</td>
                  <td>Action</td>
               </tr>
            </thead>
            <tbody>
            
            </tbody>
         </table>
      </div>
     
<?php
   } else {
      echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
   }
} else {
   header("Location: index.php");
}

   $sOrderNo=isset($_GET['sOrderNo']) ? $_GET['sOrderNo'] : '';
   $sCentreCode1=isset($_GET['sCentreCode1']) ? $_GET['sCentreCode1'] : '';
   $status=isset($_GET['status']) ? $_GET['status'] : '';
   $company_name=isset($_GET['company_name']) ? $_GET['company_name'] : '';
   $date_from=isset($_GET['date_from']) ? $_GET['date_from'] : '';
   $date_to=isset($_GET['date_to']) ? $_GET['date_to'] : '';

?>
<div id="dlg"></div>

<script type="text/javascript">
   $(document).ready(function() {
      $('#mydatatable').DataTable({
         'columnDefs': [{
            'targets': [5], // column index (start from 0)
            'orderable': false, // set orderable false for selected columns
         }],
         order: [
            [5, "asc"]
         ],
         "bProcessing": true,
         "bServerSide": true,
         "sAjaxSource": "admin/serverresponse/order_status.php?sOrderNo=<?php echo $sOrderNo; ?>&sCentreCode1=<?php echo $sCentreCode1; ?>&status=<?php echo $status; ?>&company_name=<?php echo $company_name; ?>&date_from=<?php echo $date_from; ?>&date_to=<?php echo $date_to; ?>"
      });
   });
</script>
 <style>
                  .uk-text-small {
                     font-size: 14px;
                  }

                  .d_n {
                     display: none;
                  }

                  #frmStatus input {
                     width: 100%;
                  }

                  .q-table.q-table-light tr:first-child {
                     background: transparent;
                     color: darkgrey;
                  }

                  .q-table.q-table-light {
                     box-shadow: none !important;
                     background: white !important;
                  }

                  .uk-overflow-container {
                     padding: 1.5em 0 1.5em 0;
                  }

                  #mydatatable_length {
                     display: none;
                  }

                  #mydatatable_filter {
                     display: none;
                  }

                  #mydatatable_paginate {
                     float: initial;
                     text-align: center
                  }

                  #mydatatable_paginate .paginate_button {
                     display: inline-block;
                     min-width: 16px;
                     padding: 3px 5px;
                     line-height: 20px;
                     text-decoration: none;
                     -moz-box-sizing: content-box;
                     box-sizing: content-box;
                     text-align: center;
                     background: #f7f7f7;
                     color: #444444;
                     border: 1px solid rgba(0, 0, 0, 0.2);
                     border-bottom-color: rgba(0, 0, 0, 0.3);
                     background-origin: border-box;
                     background-image: -webkit-linear-gradient(top, #ffffff, #eeeeee);
                     background-image: linear-gradient(to bottom, #ffffff, #eeeeee);
                     text-shadow: 0 1px 0 #ffffff;
                     margin-left: 3px;
                     margin-right: 3px
                  }

                  #mydatatable_paginate .paginate_button.current {
                     background: #009dd8;
                     color: #ffffff !important;
                     border: 1px solid rgba(0, 0, 0, 0.2);
                     border-bottom-color: rgba(0, 0, 0, 0.4);
                     background-origin: border-box;
                     background-image: -webkit-linear-gradient(top, #00b4f5, #008dc5);
                     background-image: linear-gradient(to bottom, #00b4f5, #008dc5);
                     text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.2);
                  }

                  #mydatatable_filter {
                     width: 100%
                  }

                  #mydatatable_filter label {
                     width: 100%;
                     display: inline-flex
                  }

                  #mydatatable_filter label input {
                     height: 30px;
                     width: 100%;
                     padding: 4px 6px;
                     border: 1px solid #dddddd;
                     background: #ffffff;
                     color: #444444;
                     -webkit-transition: all linear 0.2s;
                     transition: all linear 0.2s;
                     border-radius: 4px;
                  }
               </style>