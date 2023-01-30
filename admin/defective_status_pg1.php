<!--<a href="/">                 
         <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>-->
<span style="float: right;position: absolute;right: 35px;">
    <span class="page_title"><img src="/images/title_Icons/Defective_Status.png">Defective Status</span>
</span>

<?php
include_once("admin/functions.php");
if ($_SESSION["isLogin"] == 1) {
   if ((($_SESSION["UserType"] == "A") || ($_SESSION["UserType"] == "S") || ($_SESSION["UserType"] == "O") || ($_SESSION["UserType"] == "H") || ($_SESSION["UserType"] == "C") || ($_SESSION["UserType"] == "R") || ($_SESSION["UserType"] == "CM") || ($_SESSION["UserType"] == "T")) &
      (hasRightGroupXOR($_SESSION["UserName"], "OrderStatusView|OrderStatusEdit"))
   ) {

      include_once("search_new.php");
      include_once("lib/pagination/pagination.php");

function getStatus($order_no) {
   global $connection;

   $sql="SELECT * from `defective` where order_no='$order_no'";

   $result=mysqli_query($connection, $sql);
   if ($result) {
      $row=mysqli_fetch_assoc($result);
 
       if ($row["reject_status"] === '1') {
           return "Rejected";
       }elseif ($row["reject_status"] === '2'){
         return "Cancelled";
      }
      if ($row["cancelled_by"] != "") {
               return "Cancelled";
            } else {
               if ($row["delivered_to_logistic_by"] != "") {
                  if ($row["finance_payment_paid_by"] != "") {
                     //return ($_SESSION["UserType"] == "S") ? "Delivered (Paid)" : "Delivered";
                     return ($_SESSION["UserType"] == "S") ? "Delivered" : "Delivered";
                  } else {
                     //return ($_SESSION["UserType"] == "S") ? "Delivered (Pending Payment)" : "Delivered"; 
                     return ($_SESSION["UserType"] == "S") ? "Delivered" : "Delivered"; 
                  }
               } else {
                  if ($row["packed_by"] != "") {
                     if ($row["finance_payment_paid_by"] != "") {
                        //return ($_SESSION["UserType"] == "S") ? "Packed (Paid)" : "Ready for Collection";
                        return ($_SESSION["UserType"] == "S") ? "Ready for Collection" : "Ready for Collection";
                     } else {
                        //return ($_SESSION["UserType"] == "S") ? "Packed (Pending Payment)" : "Ready for Collection";  
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

function DateTime($person, $datetime) {
   if ($person!="") {
      return $datetime;
   } else {
      return "NA";
   }
}

foreach ($_GET as $key=>$value) {
   $$key=$value;
}
?>
<script>
function dlgReportDefective(id) {
   $.ajax({
      url : "admin/dlgReportDefective.php",
      type : "POST",
      data : "id="+id,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         $("#dlg").html(response);
         $("#dlg").dialog({
            dialogClass:"no-close",
            height:'auto',
            width:'auto',
            title:"Report Defective Product",
         });
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}
$(document).ready(function () {
   $("#btnClear").click(function () {
      $("#sOrderNo").val("");
      $("#sCentreCode").val("");
      $("#frmStatus").submit();
   });
});
function doCancelOrder(sha_id, sOrderNo) {
	//alert(sOrderNo);
   $.ajax({
      url : "admin/defective_dlg_cancel.php",
      type : "POST",
      data : "id="+sha_id+"&sOrderNo="+sOrderNo,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         $("#dlg").html(response);
         $("#dlg").dialog({
            dialogClass:"no-close",
            height:'auto',
            width:'auto',
            title:"Please provide a reason to cancel order",
         });
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}
</script>

<div style="margin-top:40px!important;" class="uk-margin-top uk-margin-right">
   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color">Defective Status</h2>
   </div>

<?php
include_once("../mysql.php");
$year = $_SESSION['Year'];
if (($_SESSION["UserType"]=="S") || ($_SESSION["UserType"]=="H") || ($_SESSION["UserType"]=="C") || ($_SESSION["UserType"]=="R") || ($_SESSION["UserType"]=="CM") || ($_SESSION["UserType"]=="T")) {
    $sql="SELECT distinct order_no, ordered_by, sales_order_no, ordered_on, delivered_to_logistic_by,
    delivered_to_logistic_on, courier, defective_reason, c.company_name, doc, remarks from `defective` left join centre c on c.centre_code=`defective`.centre_code and (CAST(`ordered_on` AS DATE) BETWEEN '".$year_start_date."' AND '".$year_end_date."')"; 
} else {
   $sql="SELECT distinct order_no, ordered_by, sales_order_no, ordered_on, delivered_to_logistic_by,
   delivered_to_logistic_on, courier, defective_reason, doc, remarks from `defective` where centre_code='".$_SESSION["CentreCode"]."' and (CAST(`ordered_on` AS DATE) BETWEEN '".$year_start_date."' AND '".$year_end_date."')";
}

foreach ($_GET as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);
}

if ($date_from!="") {
   $y_date_from=$date_from;
   list($day, $month, $year)=explode("/", $date_from);
   $date_from="$year-$month-$day";
}

if ($date_to!="") {
   $y_date_to=$date_to;
   list($day, $month, $year)=explode("/", $date_to);
   $date_to="$year-$month-$day";
}

$token_order_no=ConstructToken("order_no", "%".$_GET["sOrderNo"]."%", "like");
$token_centre_code=ConstructToken("`defective`.centre_code", $_GET["sCentreCode"], "=");
if ($date_from && !$date_to) {
 
   $token_order_date=ConstructTokenGroup("ordered_on", $date_from." 00:00:00", ">=", "ordered_on", date("Y/m/d")." 23:59:59", "<=", "and");

   $token_order_date= str_replace(array( '(', ')' ), '', $token_order_date);
}elseif (!$date_from && $date_to) {
 
   $token_order_date=ConstructTokenGroup("ordered_on", "2019/01/01"." 00:00:00", ">=", "ordered_on", $date_to." 23:59:59", "<=", "and");
   $token_order_date= str_replace(array( '(', ')' ), '', $token_order_date);
}elseif ($date_from && $date_to) {
  
   $token_order_date=ConstructTokenGroup("ordered_on", $date_from." 00:00:00", ">=", "ordered_on", $date_to." 23:59:59", "<=", "and");
   $token_order_date= str_replace(array( '(', ')' ), '', $token_order_date);
} else {
   $token_order_date="";
}
$final_token=ConcatToken($token_order_no, $token_centre_code, "and");
$final_token=ConcatToken($final_token, $token_order_date, "and");
$final_sql=ConcatWhere($sql, $final_token);

$numperpage=10000000;
$query="p=$p&sOrderNo=$sOrderNo";
$pagination=getPagination($pg, $numperpage, $query, $final_sql, $start_record, $num_row);
$final_sql.=" limit $start_record, $numperpage";

$result=mysqli_query($connection, $final_sql);
$num_row=mysqli_num_rows($result);
?>
<form name="frmStatus" id="frmStatus" method="get" class="uk-form">
  
<?php
	if (($_SESSION["UserType"] == "S") || ($_SESSION["UserType"] == "H") || ($_SESSION["UserType"] == "C") || ($_SESSION["UserType"] == "R") || ($_SESSION["UserType"] == "CM") || ($_SESSION["UserType"] == "T")) {
?>
					 <!--Centre Name<br>-->
               <input type="hidden"  id="hfCenterCode" name="sCentreCode"">
               <input list="centre_code" id="company_name" name="company_name" value="" placeholder="Select Centre Name">
				<datalist class="form-control" id="centre_code" style="display:none;">				
				  
                            
							<?php
								$sCentreCode= $_GET["sCentreCode"];
								$sql = "SELECT * from centre order by centre_code";
								$result_centre = mysqli_query($connection, $sql);                        
                        
                               while ($row=mysqli_fetch_assoc($result_centre)) {
                              ?>
<option value="<?php echo $row['company_name']?>" <?php if($_GET["sCentreCode"]==$row["centre_code"]){ echo 'selected';
}?>> <?php echo $row["centre_code"]
                                                                                    ?></option>
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
                    
                  <?php
                  }else{ ?>
					 
            <input type="hidden" name="centre_code" id="centre_code1" value="<?php echo $_SESSION['CentreCode']?>">
            		<span type="text" class="uk-width-medium-1-1 uk-text-bold" ><?php echo getCentreCompanyName($_SESSION['CentreCode'])?></span>
				  <?php }
                  ?>
	
		 <select name="status" id="status">
			<option value="">Select Status</option>
			<option <?php if($_GET["status"]=="Pending") echo "selected" ?> value="Pending">Pending</option>
			<option <?php if($_GET["status"]=="Acknowledged") echo "selected" ?> value="Acknowledged">Acknowledged</option> 
			<option <?php if($_GET["status"]=="Packing") echo "selected" ?> value="Packing">Packing</option>
			<option <?php if($_GET["status"]=="Ready to collect") echo "selected" ?> value="Ready for Collection">Ready for Collection</option>
			<option <?php if($_GET["status"]=="Delivered") echo "selected" ?> value="Delivered">Delivered</option>						
		 </select> 
 <input type="text" name="sOrderNo" size="12" id="sOrderNo" placeholder="Defective No" value="<?php echo $_GET['sOrderNo']?>">         
   <input type="hidden" name="p" id="p" value="<?php echo $_GET['p']?>">
   <input type="text" data-uk-datepicker="{format:'DD/MM/YYYY'}" placeholder="Order Date From" name="date_from" id="date_to" value="<?php echo $y_date_from?>">
   <input type="text" data-uk-datepicker="{format:'DD/MM/YYYY'}" placeholder="Order Date To" name="date_to" id="date_to" value="<?php echo $y_date_to?>">
   <button class="uk-button">Search</button>
   <a class="uk-button" href="index.php?p=defective_status_pg1" id="btnClear">Clear</a>
</form>
<?php
if ($num_row>0) {
  
?>
<div style="margin-top:30px;" class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color">LISTING</h2>
   </div>
   <div class="uk-overflow-container">
		<table class="uk-table" id="mydatatable">
		<thead>
      <tr class="uk-text-bold uk-text-small">

<?php
	if (($_SESSION["UserType"] == "S")) {
?>
<td>Centre Name</td>
<?php
      }
?>    
         <td>Order No.</td>
         <td>Defective No.</td>
         <td>Recorded By</td>
         <td>Recorded On</td>
         <td>Status</td>
         <td>Shipping Method</td>
         <td>Action</td>
      </tr>
	  </thead>
	   <tbody>
<?php
   while ($row=mysqli_fetch_assoc($result)) {
      $sha_id=sha1($row["id"]);
      $sha_order_no=sha1($row["order_no"]);
	  $status = $_GET["status"];
	if(getStatus($row["order_no"])==$status || $status == ""){
?>
      <tr class="uk-text-small">
      <?php
         if (($_SESSION["UserType"] == "S")) {
      ?>
      <td><?php echo $row["company_name"]?></td>
      <?php
            }
      ?>
		<td><?php echo $row["sales_order_no"]?></td>
         <td><a href="index.php?p=defective_status&sOrderNo=<?php echo $row['order_no']?>"><?php echo $row["order_no"]?></a></td>
         
         <td><?php echo $row["ordered_by"]?></td>
         <td><?php echo $row["ordered_on"]?></td>
		 <td><?php echo getStatus($row["order_no"]); ?></td>
         
         <td>
		<?php
		 if (getStatus($row["order_no"]) != "Rejected") {
			 echo $row["courier"];
		 } 
		 ?>
		 </td>
        
         <td>
<?php
if (($_SESSION["isLogin"]==1) & (hasRightGroupXOR($_SESSION["UserName"], "DefectiveStatusEdit|DefectiveStatusView"))) {
?>  <center>
		<?php
		  if (($row["delivered_to_logistic_by"] != "") & ($_SESSION["isLogin"] == 1) & (hasRightGroupXOR($_SESSION["UserName"], "OrderStatusEdit|OrderStatusView"))) {
		  ?>
		  <?php if (getStatus($row["order_no"]) != "Rejected") { ?>
		<a data-uk-tooltip title="Generate Delivery Order" href="admin/generate_defective_do.php?order_no=<?php echo sha1($row["order_no"])?>" target="_blank"><i class="fa fa-truck text-info" style="font-size: 1.3rem;"></i></a>
        <?php
			}
		  }
		?>
          
		
		
	    <a data-uk-tooltip title="Cancel" onclick="doCancelOrder('<?php echo $sha_id?>', '<?php echo sha1($row["order_no"])?>')" href="#"><i class="fa fa-times text-danger" style="font-size: 1.3rem;"></i></a>
        </center>
           
<?php
}
?>

         </td>
      </tr>
<?php
    }else{
		 $num_row--;
	 }
	 }
?>
 </tbody>
   </table>
   </div>
      <?php
               // echo $pagination;
            } else {
               echo "<div class='uk-alert'>No Record found</div>";
            }
      ?>
<?php
   } else {
      echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
   }
} else {
   header("Location: index.php");
}

$sOrderNo=isset($_GET['sOrderNo']) ? $_GET['sOrderNo'] : '';
$sCentreCode=isset($_GET['sCentreCode']) ? $_GET['sCentreCode'] : '';
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
            'targets': [6], // column index (start from 0)
            'orderable': false, // set orderable false for selected columns
         }],
         "bProcessing": true,
         "bServerSide": true,
         "sAjaxSource": "admin/serverresponse/defective_status.php?sOrderNo=<?php echo $sOrderNo; ?>&sCentreCode=<?php echo $sCentreCode; ?>&status=<?php echo $status; ?>&company_name=<?php echo $company_name; ?>&date_from=<?php echo $date_from; ?>&date_to=<?php echo $date_to; ?>"
      });
   });
</script>
<style>

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

<?php
// if ($_GET['reject']) {
   // $order_no = $_GET['reject'];
  // $sql="UPDATE `defective` set reject_status='1' where order_no = $order_no";
 // $result=mysqli_query($connection, $sql);

// }else
if ($_GET['cancel']) {
   $order_no = $_GET['cancel'];
  $sql="UPDATE `defective` set reject_status='2' where order_no = $order_no";
 $result=mysqli_query($connection, $sql);

}
 ?>
<script>
function doRejectRecord() {
   UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function () {
    
  location.reload();
     // alert($sql);
   });
}
</script>

<style>
	.uk-text-small {
		font-size: 14px!important;
		line-height: 16px;
	}
</style>