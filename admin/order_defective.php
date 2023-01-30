 <a href="index.php?p=order_status_pg1">                 
             <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a> 
<!--<a href="#">                 
             <span class=""></span>
</a>-->
<span>
    <span class="page_title"><img src="/images/title_Icons/Defective_Status.png"><?php
        if ($_GET["mode"] == 'defective') {
        ?>
          Defective Status
        <?php
        } else {
        ?>
          Order Status
        <?php
        }
        ?></span>
</span>

<?php
if ($_SESSION["isLogin"]==1) {
   if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="S") || ($_SESSION["UserType"]=="O")) &
   (hasRightGroupXOR($_SESSION["UserName"], "OrderStatusEdit|OrderStatusView"))) {

include_once("search_new.php");
include_once("lib/pagination/pagination.php");
include_once("admin/functions.php");

function isDelivered($id) {
   global $connection;

   $sql="SELECT * from `order` where id='$id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   if ($row["delivered_to_logistic_by"]!="") {
      return true;
   } else {
      return false;
   }
}

function getPaidStatus($id) {
   global $connection;

   $sql="SELECT * from `order` where id='$id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   if ($row["finance_payment_paid_by"]!="") {
      return " (Paid)";
   } else {
      return " (Pending Payment)";
   }
}

function getCentreNameOS($centre_code) {
   global $connection;
//echo $centre_code; die;
   $sql="SELECT * from centre where centre_code='$centre_code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["company_name"];
}

function Person($str) {
   if ($str=="") {
      return "NA";
   } else {
      return $str;
   }
}

function packedDateTime($id, $person, $datetime) {
   global $connection;

   $sql="SELECT * from `order` where id='$id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   if ($person=="") {
      return "NA";
   } else {
      return "$datetime - ".$row["tracking_no"];
   }
}

function deliveredToDateTime($id, $person, $datetime) {
   global $connection;

   $sql="SELECT * from `order` where id='$id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   if ($person=="") {
      return "NA";
   } else {
      return "$datetime - ".$row["name"];
   }
}

function cancelDateTime($id, $person, $datetime) {
   global $connection;

   $sql="SELECT * from `order` where id='$id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   if ($person=="") {
      return "NA";
   } else {
      return "$datetime - ".$row["cancel_reason"];
   }
}

function DateTime($person, $datetime) {
   if ($person!="") {
      return $datetime;
   } else {
      return "NA";
   }
}

function getStatus($order_no) {
   global $connection;

   $sql="SELECT * from `order` where order_no='$order_no' or id='$order_no'";

   $result=mysqli_query($connection, $sql);
   if ($result) {
      $row=mysqli_fetch_assoc($result);

      if ($row["cancelled_by"]!="") {
         return "Cancelled";
      } else {
         if ($row["delivered_to_logistic_by"]!="") {
            if ($row["finance_payment_paid_by"]!="") {
               return ($_SESSION["UserType"]=="S") ? "Delivered (Paid)" : "Delivered";
            } else {
               return ($_SESSION["UserType"]=="S") ? "Delivered (Pending Payment)" : "Delivered";
            }

         } else {
         if ($row["packed_by"]!="") {
            if ($row["finance_payment_paid_by"]!="") {
              return ($_SESSION["UserType"]=="S") ? "Packed (Paid)" : "Ready for Collection";
            } else {
              return ($_SESSION["UserType"]=="S") ? "Packed (Pending Payment)" : "Ready for Collection";
            }
         } else {
            /*if ($row["assigned_to_by"]!="") {
               return "Assigned";
            }
            else{*/
               if ($row["finance_approved_by"]!="") {
                 if ($row["finance_payment_paid_by"]!="") {
                   return ($_SESSION["UserType"]=="S") ? "Finance Approved (Paid)" : "Finance Approved";
                 } else {
                   return ($_SESSION["UserType"]=="S") ? "Finance Approved (Pending Payment)" : "Finance Approved";
                 }
               } else {
                 if ($row["logistic_approved_by"]!="") {
                   return "Packing";
                 } else {
                   if ($row["acknowledged_by"]!="") {
                     return "Acknowledged";
                   } else {
                     return "Pending";
                   }
                 }
               }
            //}
         }
         }
      }
   }
}

foreach ($_GET as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);
}
?>
<script>
function dlgReportOrdereview(id, sOrderNo) {
   $.ajax({
      url : "admin/dlgReportOrderview.php",
      type : "POST",
      data : "id="+id+"&sOrderNo="+sOrderNo,
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
function dlgReportDefective(id, product_code) {
   $.ajax({
      url : "admin/dlgReportDefective.php",
      type : "POST",
      data : "id="+id+"&product_code="+product_code,
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

function doAcknowledged(sha_id, sOrderNo) {
   UIkit.modal.confirm("<h2>ARE YOU SURE TO PROCEED?</h2>", function () {
      $.ajax({
         url : "admin/do_acknowledged.php",
         type : "POST",
         data : "sha_id="+sha_id+"&sOrderNo="+sOrderNo,
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
}

function doLogisticApprove(sha_id, sOrderNo) {
   UIkit.modal.confirm("<h2>ARE YOU SURE TO PROCEED?</h2>", function () {
      $.ajax({
         url : "admin/do_logistic_approve.php",
         type : "POST",
         data : "sha_id="+sha_id+"&sOrderNo="+sOrderNo,
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

function dlgConfirmPayment(sha_id, sOrderNo) {
   $.ajax({
      url : "admin/dlgConfirmPayment.php",
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
            title:"Please provide proof of payment",
         });
      },
      error : function(http, status, error) {
         UIkit.notification("Error:"+error);
      }
   });
}

function doFinanceApproved(sha_id, sOrderNo) {
   UIkit.modal.confirm("<h2>ARE YOU SURE TO PROCEED?</h2>", function () {
      $.ajax({
         url : "admin/do_finance_approved.php",
         type : "POST",
         data : "sha_id="+sha_id+"&sOrderNo="+sOrderNo,
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

function dlgAssignedTo(sha_id, sOrderNo) {
   $.ajax({
      url : "admin/dlg_assigned_to.php",
      type : "POST",
      data : "sha_id="+sha_id+"&sOrderNo="+sOrderNo,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         $("#dlg").html(response);
         $("#dlg").dialog({
            dialogClass:"no-close",
            height:'auto',
            width:'600',
            title:"Please select a person",
         });
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}

function doPacked(sha_id, sOrderNo) {
   UIkit.modal.confirm("<h2>ARE YOU SURE TO PROCEED?</h2>", function () {
      $.ajax({
         url : "admin/do_packed.php",
         type : "POST",
         data : "sha_id="+sha_id+"&sOrderNo="+sOrderNo,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            var s=response.split("|");
            if (s[0]=="1") {
               UIkit.notify(s[1]);
               location.reload();
            }

            if (s[0]=="0") {
               UIkit.notify(s[1]);
            }
         },
         error : function(http, status, error) {
            UIkit.notify("Error:"+error);
         }
      });
   });
}

function doDeliveredToLogistic(sha_id, sOrderNo) {
   $.ajax({
      url : "admin/dlg_delivered_to_logistic.php",
      type : "POST",
      data : "sha_id="+sha_id+"&sOrderNo="+sOrderNo,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         $("#dlg").html(response);
         $("#dlg").dialog({
            dialogClass:"no-close",
            height:'auto',
            width:'1300',
            title:"Please fill in information",
         });
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}

function doCancelOrder(sha_id, sOrderNo) {
   //alert(sOrderNo);
   $.ajax({
      url : "admin/dlg_cancel.php",
      type : "POST",
      data : "sha_id="+sha_id+"&sOrderNo="+sOrderNo,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         $("#dlg").html(response);
         $("#dlg").dialog({
            dialogClass:"no-close",
            height:'auto',
            width:'auto',
            title:"Please provide a reason to cancel ",
         });
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}



function dlg_Prove_Of_Payment(sha_id, sOrderNo) {
   $.ajax({
      url : "admin/dlgProveOfPayment.php",
      type : "POST",
      data : "sha_id="+sha_id+"&sOrderNo="+sOrderNo,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         $("#dlg").html(response);
         $("#dlg").dialog({
            dialogClass:"no-close",
            height:'auto',
            width:'600',
            title:"Proof Of Payment",
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
      $("#sProductCode").val("");
      $("#sCentreCode").val("");
      $("#sOrderStatus").val("");
      $("#sOrderDateFrom").val("");
      $("#sOrderDateTo").val("");

      $("#frmStatus").submit();
   });
});
</script>

<div class="uk-margin-top uk-margin-right">
   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color myheader-text-style">
        <?php
        if (isset($mode) && $mode == 'defective') {
        ?>
          Defective Status
        <?php
        } else {
        ?>
          Order Status
        <?php
        }
        ?>
      </h2>
   </div>

    <div style="background: white">

<?php
include_once("../mysql.php");
if ($_SESSION["UserType"]=="S") {
	$sOrderNo = $_GET['sOrderNo'];
  $sql21="select * from prove_payment where order_no='$sOrderNo'";
}
 

$year=$_SESSION['Year'];
$current_year=date("Y");
if ($_SESSION["UserType"]=="S") {
   $sql="SELECT * from `order` where (CAST(o.ordered_on AS DATE) BETWEEN '".$year_start_date."' AND '".$year_end_date."')";
} else {
   $sql="SELECT o.*, d.sales_order_no, d.order_no as defective_no, d.courier from `order` o, `defective` d where o.order_no=d.sales_order_no and o.centre_code='".$_SESSION["CentreCode"]."' and (CAST(o.ordered_on AS DATE) BETWEEN '".$year_start_date."' AND '".$year_end_date."')";
   
   //$sql="SELECT distinct order_no, sales_order_no, ordered_by, ordered_on, delivered_to_logistic_by,delivered_to_logistic_on, courier, defective_reason, doc, remarks from `defective` where centre_code='".$_SESSION["CentreCode"]."' and year(ordered_on) = '$year'";
}
$token_order_no=ConstructToken("order_no", "%".$_GET["sOrderNo"]."%", "like");
$token_product_code=ConstructToken("product_code", "%".$_GET["sProductCode"]."%", "like");
$token_centre_code=ConstructToken("centre_code", "%".$_GET["sCentreCode"]."%", "like");
if ($_GET["sOrderDateFrom"]!="") {
   list($day, $month, $year)=explode("/", $_GET["sOrderDateFrom"]);
   $sOrderDateFrom="$year-$month-$day";
} else {
   $sOrderDateFrom="";
}

if ($_GET["sOrderDateTo"]!="") {
   list($day, $month, $year)=explode("/", $_GET["sOrderDateTo"]);
   $sOrderDateTo="$year-$month-$day";
} else {
   $sOrderDateTo="";
}

$token_date=ConstructTokenGroup("ordered_on", $sOrderDateFrom, ">=", "ordered_on", $sOrderDateTo, "<=", "and");
if ($_GET["sOrderStatus"]!="") {
   switch ($_GET["sOrderStatus"]) {
      case "Ordered" : $token_order_status=ConstructToken("ordered_by", "{}", "<>"); break;
      case "Acknowledged" : $token_order_status=ConstructToken("acknowledged_by", "{}", "<>"); break;
      case "Logistic" : $token_order_status=ConstructToken("logistic_approved_by", "{}", "<>"); break;
      case "Approved" : $token_order_status=ConstructToken("finance_approved_by", "{}", "<>"); break;
      case "Assigned" : $token_order_status=ConstructToken("assigned_to_by", "{}", "<>"); break;
      case "Packed" : $token_order_status=ConstructToken("packed_by", "{}", "<>"); break;
      case "Paid" : $token_order_status=ConstructToken("finance_payment_paid_by", "{}", "<>"); break;
      case "Delivered2Logistic" : $token_order_status=ConstructToken("delivered_to_logistic_by", "{}", "<>"); break;
      case "Cancelled" : $token_order_status=ConstructToken("cancelled_by", "{}", "<>"); break;
   }
} else {
   $token_order_status="";
}

$order_token=ConstructOrderToken("ordered_on", "desc");
$final_token=ConcatToken($token_order_no, $token_product_code, "and");
$final_token=ConcatToken($final_token, $token_centre_code, "and");
$final_token=ConcatToken($final_token, $token_order_status, "and");
$final_token=ConcatToken($final_token, $token_date, "and");
$final_sql=ConcatWhere($sql, $final_token);
$final_sql=ConcatOrder($final_sql, $order_token);
// echo $final_sql;
$numperpage=1000000;
$query="p=$p&sOrderNo=$sOrderNo&sProductCode=$sProduct_code&sUserName=$sUserName&sOrderStatus=$sOrderStatus";
$pagination=getPagination($pg, $numperpage, $query, $final_sql, $start_record, $num_row);
$final_sql.=" limit $start_record, $numperpage";
$result=mysqli_query($connection, $final_sql);
$num_row=mysqli_num_rows($result);
$sOrderNo=sha1($_GET["sOrderNo"]);
$the_result=mysqli_query($connection, $final_sql);
$row=mysqli_fetch_assoc($the_result);
$the_result33=mysqli_query($connection, $sql21);
 $row33=mysqli_fetch_assoc($the_result33);
$sha_id=sha1($row["id"]);

 //print_r($row33); die; 
?>
    <style>
        .form_btn {
            float: right;
            margin: 0 5px;
        }

        #frmStatus {
            background: transparent!important;
            box-shadow: none!important;
        }

        #frmStatus input, #frmStatus select {width: 100%;}

        .step-container {
            width: 80%;
            margin: 1.5em auto;
        }

        .step-container .step {
            text-align: center;
            width: calc(calc(100% / 5) - 5%);
        }

        .step-arrow {
            display: flex;
            align-items: center;
            color: darkgrey;
            width: 5%;
            justify-content: center;
        }

        .step-container .step i {font-size: 3.5em}
		
    </style>
<form name="frmStatus" id="frmStatus" method="get" class="uk-form" >
    <div class="row">
        <div class="col-sm-12 col-md">
            <input type="text" name="sOrderNo" size="12" id="sOrderNo" placeholder="Order No" value="<?php echo $_GET['sOrderNo']?>" readonly>
        </div>
        <div class="col-sm-12 col-md">
            <input type="text" name="sProductCode" id="sProductCode" placeholder="Product Code" value="<?php echo $_GET['sProductCode']?>">
        </div>
        <div class="col-sm-12 col-md">
            <select name="sOrderStatus" id="sOrderStatus">
                <option value="">Select</option>
                <option value="Ordered" <?php if ($sOrderStatus=="Ordered") {echo "selected";}?>>Ordered</option>
                <option value="Acknowledged" <?php if ($sOrderStatus=="Acknowledged") {echo "selected";}?>>Acknowledged</option>
                 <option value="Logistic" <?php if ($sOrderStatus=="Logistic") {echo "selected";}?>>Logistic</option>
                <option value="Approved" <?php if ($sOrderStatus=="Approved") {echo "selected";}?>>Approved</option>
                <option value="Assigned" <?php if ($sOrderStatus=="Assigned") {echo "selected";}?>>Assigned</option>
                <option value="Packed" <?php if ($sOrderStatus=="Packed") {echo "selected";}?>>Packed</option>
                <option value="Paid" <?php if ($sOrderStatus=="Paid") {echo "selected";}?>>Paid</option>
                <option value="Delivered2Logistic" <?php if ($sOrderStatus=="Delivered2Logistic") {echo "selected";}?>>Delivered To Logistic</option>
                <option value="Cancelled" <?php if ($sOrderStatus=="Cancelled") {echo "selected";}?>>Cancelled</option>
            </select>
        </div>
        <div class="col-sm-12 col-md-2">
            <input type="hidden" name="p" id="p" value="<?php echo $_GET['p']?>">
            <button class="uk-button full-width-blue">Search</button><br><br>
        </div>
    </div>

    <?php if ($mode != 'defective'):?>
    <a onclick="dlg_Prove_Of_Payment('<?php echo $sha_id?>', '<?php echo $sOrderNo?>')" class='uk-button uk-button-small form_btn' style="color:white;"><i class="fa fa-check"></i> Proof of Payment</a>
         <a style="color:white;" type="button" data-toggle="modal"  data-target="#exampleModal" class="uk-button uk-button-small form_btn" >View Proof of Payment</a>

         <br><br><br>
       <?php endif;?>


    <?php if ($mode != 'defective'):?>

    <div class="step-container d-md-flex d-sm-block">
        <div class="step" <?php echo( getStatus($_GET["sOrderNo"]) == "Pending") ? 'style="color:#ffa500"' : ''?>>
            <i class="fas fa-pager"></i>
            <p>Pending</p>
        </div>
        <div class="step-arrow">
            <i class="fas fa-chevron-right"></i>
        </div>
      
        <div class="step" <?php echo( getStatus($_GET["sOrderNo"]) == "Acknowledged") ? 'style="color:#ffa500"' : ''?>>
            <i class="fas fa-check-circle"></i>
            <p>Acknowledged</p>
        </div>
         <div class="step-arrow">
            <i class="fas fa-chevron-right"></i>
        </div>
        <?php if ($_SESSION["UserType"]=="S"):?>
         <div class="step" <?php echo( getStatus($_GET["sOrderNo"]) == "Logistic") ? 'style="color:#ffa500"' : ''?>>
            <i class="fas fa-truck"></i>
            <p>Logistic</p>
        </div>
        <div class="step-arrow">
            <i class="fas fa-chevron-right"></i>
        </div>
      <?php endif;?>
        <div class="step" <?php echo( in_array(getStatus($_GET["sOrderNo"]), ["Packing", "Logistic", "Finance Approved", "Finance Approved (Paid)", "Finance Approved (Pending Payment)", "Assigned","Approved","finance_approved_by"])) ? 'style="color:#ffa500"' : ''?>>
            <i class="fas fa-box-open"></i>
            <p>Packing</p>
        </div>
        <div class="step-arrow">
            <i class="fas fa-chevron-right"></i>
        </div>
         <?php if ($_SESSION["UserType"]=="S"):?>
        <div class="step" <?php echo( getStatus($_GET["sOrderNo"]) == ["Approved","finance_approved_by"]) ? 'style="color:#ffa500"' : ''?>>
            <i class="fas fa-money"></i>
            <p>Finance approved by</p>
        </div>
        <div class="step-arrow">
            <i class="fas fa-chevron-right"></i>
        </div>
         <?php endif;?>
        <div class="step" <?php echo( in_array(getStatus($_GET["sOrderNo"]), ["Ready for Collection", "Packed (Pending Payment)", "Packed (Paid)"])) ? 'style="color:#ffa500"' : ''?>>
            <i class="fas fa-phone-volume"></i>
            <p>Ready for Collection <?php if($_SESSION["UserType"]=="S"): ?>(<?php echo ($row33["id"]=="") ? "Pending Payment" : "Paid" ?>)<?php endif ?></p>
        </div>
        <div class="step-arrow">
            <i class="fas fa-chevron-right"></i>
        </div>
        <div class="step" <?php echo( in_array(getStatus($_GET["sOrderNo"]), ["Delivered", "Delivered (Pending Payment)", "Delivered (Paid)"])) ? 'style="color:#ffa500"' : ''?>>
            <i class="fas fa-truck-loading"></i>
            <p>Delivered <?php if($_SESSION["UserType"]=="S"): ?>(<?php echo ($row33["id"]=="") ? "Pending Payment" : "Paid" ?>)<?php endif ?></p>
        </div>
    </div>
  <?php endif; ?>

<!--    <a class="uk-button" id="btnClear">Clear</a> -->
</form><br>
<?php
if (!isset($mode) && $mode != 'defective') {
?>
  <div class="uk-grid">
    <div class="uk-width-medium-1-1">
      <?php
      if (hasRightGroupXOR($_SESSION["UserName"], "OrderStatusEdit")) {
      if (($row["finance_payment_paid_by"]=="") & ($_SESSION["UserType"]=="S") & ($_SESSION["isLogin"]==1)
         & (hasRight($_SESSION["UserName"] ,"ConfirmPaymentEdit"))) {
      ?>
                  <a style="display:none;" id="btnConfirmPayment" onclick="dlgConfirmPayment('<?php echo $sha_id?>', '<?php echo $sOrderNo?>')" class="uk-button uk-button-small form_btn text-white"><i class="fa fa-money"></i> Confirm Payment</a>
      <?php
      }
      ?>
     
      <?php
      if (($row["acknowledged_by"]=="") & ($row["cancelled_by"]=="") & ($_SESSION["isLogin"]==1) & ($_SESSION["UserType"]=="S")
         & (hasRight($_SESSION["UserName"], "AcknowledgeOrderEdit"))) {
      ?>
                  <a style="color: white;" onclick="doAcknowledged('<?php echo $sha_id?>', '<?php echo $sOrderNo?>')" class="uk-button uk-button-small form_btn"><i class="fa fa-check"></i> Acknowledge</a>
      <?php
      }
      ?>
      <?php
      if (($row["acknowledged_by"]!="") & ($row["logistic_approved_by"]=="") & ($row["cancelled_by"]=="") & ($_SESSION["isLogin"]==1)
         & ($_SESSION["UserType"]=="S") & (hasRight($_SESSION["UserName"], "LogisticApproveEdit"))) {
      ?>
                  <a style="color: white;" onclick="doLogisticApprove('<?php echo $sha_id?>', '<?php echo $sOrderNo?>')" class="uk-button uk-button-small form_btn"><i class="fa fa-check-circle-o"></i> Logistic Approve</a>
      <?php
      }
      ?>
      <?php
      if (($row["acknowledged_by"]!="") & ($row["logistic_approved_by"]!="") & ($row["finance_approved_by"]=="") & ($row["packed_by"]!="") &
         ($row["cancelled_by"]=="") & ($_SESSION["isLogin"]==1) & ($_SESSION["UserType"]=="S")
         & (hasRight($_SESSION["UserName"], "FinanceApproveEdit"))) {
      ?>
                  <a style="color: white;" onclick="doFinanceApproved('<?php echo $sha_id?>', '<?php echo $sOrderNo?>')" class='uk-button uk-button-small form_btn'><i class="fa fa-check-square-o"></i> Finance Approved</a>
      <?php
      }
      ?>
      <?php
      if (($row["acknowledged_by"]!="") & ($row["assigned_to_by"]=="") & ($row["logistic_approved_by"]!="") & ($row["cancelled_by"]=="")
         & ($_SESSION["isLogin"]==1) & ($_SESSION["UserType"]=="S") & (hasRight($_SESSION["UserName"], "PackedEdit"))) {
      ?>
                  <a style="color: white;" onclick="dlgAssignedTo('<?php echo $sha_id?>', '<?php echo $sOrderNo?>')" class='uk-button uk-button-small form_btn'><i class="fa fa-user"></i> Assigned To</a>
      <?php
      }
      ?>
      <?php
      if (($row["acknowledged_by"]!="") & ($row["finance_approved_by"]=="") & ($row["packed_by"]=="") & ($row["logistic_approved_by"]!="") & ($row["cancelled_by"]=="") & ($row["assigned_to_by"]!="")
         & ($_SESSION["isLogin"]==1) & ($_SESSION["UserType"]=="S") & (hasRight($_SESSION["UserName"], "PackedEdit"))) {
      ?>
                  <a style="color: white;" onclick="doPacked('<?php echo $sha_id?>', '<?php echo $sOrderNo?>')" class='uk-button uk-button-small form_btn'><i class="fa fa-barcode"></i> Packed</a>
      <?php
      }
      ?>
      <?php
      if (($row["acknowledged_by"]!="") & ($row["finance_approved_by"]!="") & ($row["packed_by"]!="") & ($row["delivered_to_logistic_by"]=="")
         & ($row["cancelled_by"]=="") & ($_SESSION["isLogin"]==1) & ($_SESSION["UserType"]=="S") & ($row["logistic_approved_by"]!="")
         & (hasRight($_SESSION["UserName"], "DeliveryEdit"))) {
      ?>
                  <a onclick="doDeliveredToLogistic('<?php echo $sha_id?>', '<?php echo $sOrderNo?>')" class="uk-button uk-button-small form_btn" style="color:white;"><i class="fa fa-truck"></i> Delivery</a>
      <?php
      }
      }  //if hasRightGroupXOR($_SESSION["UserName], "OrderStatusEdit")
      ?>
      
      <?php
      if (($row["ordered_by"]!="") & ($_SESSION["isLogin"]==1) & ($row["delivered_to_logistic_by"]!="")) {
         $order=sha1($sOrderNo);
      ?>
                  <a href="admin/generate_do.php?order_no=<?php echo $sOrderNo?>" class="uk-button-small form_btn" style="color:white;" target="_blank"><i class="fa fa-bookmark"></i> Generate DO</a>
      <?php
      }
      ?>
         

         <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header myheader myheader-text-color">
        <h5 class="modal-title myheader-text-color" id="exampleModalLabel">Proof of Payment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <table class="uk-table">
          <tr>
            <td>Image</td>
            <td>Order No</td>
            <td>Remarks</td>
            <td>Payment From</td>
            <td>Date</td>
          </tr>
        <?php

$od= $row["order_no"];
$r=$row['finance_payment_paid_by'];
$d =$row['finance_approved_on'];
//echo $d;
// $g=$row['remarks'];
$sql22 ="SELECT prove_payment_doc.*, prove_payment.remark FROM prove_payment_doc left JOIN prove_payment ON prove_payment.id=prove_payment_doc.prove_payment_id WHERE prove_payment_doc.order_no = '$od' order by prove_payment.id desc";
$result22=mysqli_query($connection,$sql22);
while($row22 = mysqli_fetch_assoc($result22)) {

$doc = $row22['doc'];
$remarks = $row22['remark'];
$update_date = $row22['update_date'];
// $ext = pathinfo($doc, "http://starters.q-dees.com.my/admin/uploads/");
// echo $ext; die;
$path = "/admin/uploads/".$doc;
$path_info = pathinfo($path);

//print_r($path_info);
//$path_info['extension'];
echo "<tr>";
echo "<td style='text-align: center; vertical-align: middle;'><a target='_blank' href='/admin/uploads/$doc'>";

if ($path_info['extension']=='pdf') {
   echo '<img  width="35" src="/admin/uploads/pdf.webp" alt="PDF">';
} else {
   echo '<img  width="100" height="100" src="/admin/uploads/'.$doc.'" alt="image">';
}
echo "</a></td>";
echo "<td>$od</td>";
echo "<td>$remarks</td>";
echo "<td>$r</td>";
echo "<td>$update_date</td>";
echo "</tr>";
}?>

</table>
      </div>
      <div class="modal-footer">
        <button type="button" class="uk-button form_btn" data-dismiss="modal">Close</button>
       <!-- <button type="button" class="btn btn-primary">Save changes</button>-->
      </div>
    </div>
  </div>
</div>


    </div>
  </div>
<?php
}
?>
 &nbsp;
</div>


<div class="flex">
<?php
// var_dump(hasRight($_SESSION["UserName"] ,"ConfirmPaymentEdit"));
if (($row["finance_payment_paid_by"]!="") ) {
?>
        <!--  <a data-uk-tooltip title="Open" href="admin/generate_p.php?order_no=<?php echo sha1($row["order_no"])?>" target="_blank"> -->


          <!--  <img src="../images/p.png" width="150px" height="200px" style="border:1px solid #ddd">-->
          </a>
       
<?php
}
?>

<?php
if (!isset($mode) && $mode != 'defective') {
?>
  <div class="nice-form">
    <div class="uk-grid">
     <div class="uk-width-medium-5-10">
        <table class="uk-table">
           <tr>
              <td class="uk-text-bold">Collection Name</td>
              <td>
                 <?php echo $row["name"] ?>
              </td>
           </tr>
           <tr>
              <td class="uk-text-bold">Ordered By</td>
              <td><?php echo Person($row["ordered_by"])?></td>
           </tr>
        </table>
     </div>
     <div class="uk-width-medium-5-10">
        <table class="uk-table">
           <tr>
              <td class="uk-text-bold">Order No.</td>
              <td><?php echo $row["order_no"]?></td>
           </tr>
           <tr>
              <td class="uk-text-bold">Ordered Date</td>
              <td><?php echo $row["ordered_on"]?></td>
           </tr>
        </table>
        <?php
      if (($row["ordered_by"]!="") & ($_SESSION["isLogin"]==1) & (($_SESSION["UserType"]=="S") || ($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O"))) {
         $order=sha1($sOrderNo);
      ?>
                  <a href="admin/generate_so.php?order_no=<?php echo $sOrderNo?>" class="uk-button-small form_btn" style="color:white;" target="_blank"><i class="fa fa-bullhorn"></i> Generate SO</a>
      <?php
      }
      ?>
       <?php
      if (($row["cancelled_by"]=="") & ($_SESSION["isLogin"]==1)
         & (($_SESSION["UserType"]=="S") || ($_SESSION["UserType"]=="A"))) {
      ?>
                  <a onclick="doCancelOrder('<?php echo $sha_id?>', '<?php echo $sOrderNo?>')" class="uk-button uk-button-small form_btn text-white"><i class="fa fa-times-circle"></i> Cancel Order</a>
      <?php
      }
      ?>
      <br><br><br>
     </div>
  </div>

<?php
}

if ($num_row>0) {
  
?>
    <style>
		.uk-tooltip{
			display:none!important;
		}
        .q-table.q-table-light tr:first-child {
            background: white;
            color: darkgrey;
        }

        .q-table.q-table-light tr:not(:first-child) td {padding: 13px 0;}

        .q-table.q-table-light tr:not(:first-child):nth-of-type(odd) {
            background: white;
        }

         #mydatatable_length{
display: none;
}

 #mydatatable_filter{
display: none;
}

#mydatatable_paginate{float:initial;text-align:center}
#mydatatable_paginate .paginate_button{display: inline-block; min-width: 16px; padding: 3px 5px; line-height: 20px; text-decoration: none; -moz-box-sizing: content-box; box-sizing: content-box; text-align: center; background: #f7f7f7; color: #444444; border: 1px solid rgba(0, 0, 0, 0.2); border-bottom-color: rgba(0, 0, 0, 0.3); background-origin: border-box; background-image: -webkit-linear-gradient(top, #ffffff, #eeeeee); background-image: linear-gradient(to bottom, #ffffff, #eeeeee); text-shadow: 0 1px 0 #ffffff;margin-left: 3px;
    margin-right: 3px}
#mydatatable_paginate .paginate_button.current{background: #009dd8; color: #ffffff!important; border: 1px solid rgba(0, 0, 0, 0.2); border-bottom-color: rgba(0, 0, 0, 0.4); background-origin: border-box; background-image: -webkit-linear-gradient(top, #00b4f5, #008dc5); background-image: linear-gradient(to bottom, #00b4f5, #008dc5); text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.2);}
#mydatatable_filter{width:100%}
#mydatatable_filter label{width:100%;display:inline-flex}
#mydatatable_filter label input{height: 30px;width: 100%; padding: 4px 6px; border: 1px solid #dddddd; background: #ffffff; color: #444444; -webkit-transition: all linear 0.2s; transition: all linear 0.2s; border-radius: 4px;}
    </style>
<div class="uk-width-1-1 myheader">
            <h2 class="uk-text-center myheader-text-color myheader-text-style">LISTING</h2>
         </div>
   <div class="uk-overflow-container" style="background:transparent!important; box-shadow: none!important; padding: 0px;">
   
   <table class="uk-table" id="mydatatable">
    <thead>
      <tr class="uk-text-bold ">  
	<?php
		if ($_SESSION["UserType"]!="A") {
		?>
		<th>Product Code</th>
         <th data-uk-tooltip title="Centre Name">Centre Name</th>
		 <th class="uk-text-center">Qty</th>
         <th class="uk-text-center">Unit Price</th>
         <th class="uk-text-center">Total</th>
	<?php
	}else{
	?>
		<th>Order No.</th>
		<th>Defective No.</th>
		<td>Recorded By</td>
         <td>Recorded On</td>
		  <td>Shipping Method</td>
	<?php } ?>
         
<?php
if ($_SESSION["UserType"]=='A') {
?>
         <th class="uk-text-center">Status</th>

<?php
} else {
?>
<?php
if (hasRightGroupXOR($_SESSION["UserName"], "AcknowledgeOrderEdit|AcknowledgeOrderView")) {
?>
         <th data-uk-tooltip="{pos:top}" title="Acknowledged By">Acknowledged By CGO</th>
<?php
}
?>
<?php
if (hasRightGroupXOR($_SESSION["UserName"], "LogisticApproveEdit|LogisticApproveView")) {
?>
         <th data-uk-tooltip="{pos:top}" title="Logistic Approved By">Logistic Approved By</th>
<?php
}
?>

<?php
if (hasRightGroupXOR($_SESSION["UserName"], "AssignedToEdit|AssignedToView")) {
?>
         <th data-uk-tooltip="{pos:top}" title="Assigned To">Assigned To</th>
<?php
}
?>

<?php
if (hasRightGroupXOR($_SESSION["UserName"], "PackedEdit|PackedView")) {
?>
         <th data-uk-tooltip="{pos:top}" title="Packed By">Packed By Store</th>
<?php
}
?>
<?php
if (hasRightGroupXOR($_SESSION["UserName"], "FinanceApproveEdit|FinanceApproveView")) {
?>
         <th data-uk-tooltip="{pos:top}" title="Finance Approved By">Finance Approved By</th>
<?php
}
?>
<?php
if (hasRightGroupXOR($_SESSION["UserName"], "DeliveryEdit|DeliveryView")) {
?>
         <th data-uk-tooltip="{pos:top}" title="Delivered To Logistic">Delivery</th>
<?php
}
?>
         <td data-uk-tooltip="{pos:top}" title="Cancelled By">Cancelled By</td>


<?php
}
?>
<?php
	if ($_SESSION["UserType"]!="A") {
?>
<th class="uk-text-center">Remarks</th>
	<?php } ?>
<th class="uk-text-center">Action</th>
         
      </tr>
    </thead>
    <tbody>
<?php
//   $result=mysqli_query($final_sql);
   while ($row=mysqli_fetch_assoc($result)) {
      $sha_id=sha1($row["id"]);
      $sha_order_no=sha1($row["order_no"]);
?>
 <tr class="">
		<?php
			if ($_SESSION["UserType"]!="A") {
		?>
         <td><?php echo $row["order_no"]?></td>
		 <td><?php 
			$product_code = $row["product_code"];
			$product_code = explode("((--",$product_code)[0];
			echo $product_code;
		echo $row["product_code"]
		
		 ?></td>
		  <td class="uk-text-right"><?php echo $row["qty"]?></td>
         <td class="uk-text-right"><?php echo $row["unit_price"]?></td>
         <td class="uk-text-right"><?php echo $row["total"]?></td>
		 
	   <?php }else{ ?>
			<td><?php echo $row["sales_order_no"]?></td>
			<td><a href="index.php?p=defective_status&sOrderNo=<?php echo $row['defective_no']?>"><?php echo $row["defective_no"]?></a></td>
			<td><?php echo $row["ordered_by"]?></td>
			<td><?php echo $row["ordered_on"]?></td>
			<td><?php echo $row["courier"]?></td>
	  <?php } ?>
<?php
if ($_SESSION["UserType"]!="A") {
?>
         <!--<td data-uk-tooltip title="<?php// echo getCentreNameOS($row['company_name'])?>"><?php// echo $row["company_name"]?></td>-->
		 <td data-uk-tooltip title="<?php echo $_GET['company_name'];?>"><?php echo $_GET['company_name'];?></td>
<?php
}
?>
        
<?php
if ($_SESSION["UserType"]=="A") {
?>
         <td><div data-uk-tooltip title="<?php echo $row['tracking_no']?>"><?php echo getStatus($row['id'])?></div></td>
<?php
} else {
?>
<?php
if (hasRightGroupXOR($_SESSION["UserName"], "AcknowledgeOrderEdit|AcknowledgeOrderView")) {
?>
         <td><div data-uk-tooltip="{pos:top}" title="<?php echo DateTime($row['acknowledged_by'], $row['acknowledged_on'])?>"><?php echo Person($row["acknowledged_by"])?></div></td>
<?php
}
?>
<?php
if (hasRightGroupXOR($_SESSION["UserName"], "LogisticApproveEdit|LogisticApproveView")) {
?>
         <td><div data-uk-tooltip="{pos:top}" title="<?php echo DateTime($row['logistic_approved_by'], $row['logistic_approved_on'])?>"><?php echo Person($row["logistic_approved_by"])?></div></td>
<?php
}
?>
<?php
if (hasRightGroupXOR($_SESSION["UserName"], "AssignedToEdit|AssignedToView")) {
?>
         <td><div data-uk-tooltip="{pos:top}" title="<?php echo DateTime($row['assigned_to_by'], $row['assigned_to_on'])?>"><?php echo Person($row["assigned_to"]).' (By '.Person($row["assigned_to_by"]).')'?></div></td>
<?php
}
?>
<?php
if (hasRightGroupXOR($_SESSION["UserName"], "PackedEdit|PackedView")) {
?>
         <td><div data-uk-tooltip="{pos:top}" title="<?php echo packedDateTime($row['id'], $row['packed_by'], $row['packed_on'])?>"><?php echo Person($row["packed_by"]).getPaidStatus($row["id"])?></div></td>
<?php
}
?>
<?php
if (hasRightGroupXOR($_SESSION["UserName"], "FinanceApproveEdit|FinanceApproveView")) {
?>
         <td><div data-uk-tooltip="{pos:top}" title="<?php echo DateTime($row['finance_approved_by'], $row['finance_approved_on'])?>"><?php echo Person($row["finance_approved_by"])?></div></td>
<?php
}
?>
<?php
if (hasRightGroupXOR($_SESSION["UserName"], "DeliveryEdit|DeliveryView")) {
?>
         <td><div data-uk-tooltip="{pos:top}" title="<?php echo deliveredToDateTime($row['id'], $row['delivered_to_logistic_by'], $row['delivered_to_logistic_on'])?>"><?php echo Person($row["delivered_to_logistic_by"]).getPaidStatus($row["id"])?></div></td>
<?php
}
?>
         <td><div data-uk-tooltip="{pos:top}" title="<?php echo cancelDateTime($row['id'], $row['cancelled_by'], $row['cancelled_on'])?>"><?php echo Person($row["cancelled_by"])?></div></td>
<?php
}
?>
<?php
if ($_SESSION["UserType"]!="A") {
?>
<td class="uk-text-center"><?php echo $row["cancel_reason"]?></td>

<?php } ?>
 <td>
 <?php
if ($_SESSION["UserType"]=="S") {?>
	<a onclick="dlgReportOrdereview('<?php echo $row['id']?>', '<?php echo $sOrderNo?>')";" data-uk-tooltip title="View report"><i style="color: #30d2d6; font-size:15px;" class="fas fa-flag"></i></a>

<?php
}
// this one is for order status icon
if ((isDelivered($row['id'])) & (hasRightGroupXOR($_SESSION["UserName"], "DefectiveProductEdit"))) {
?>
            <a onclick="dlgReportDefective('<?php echo sha1($row['id']) ?>', '<?php echo $row['product_code'] ?>');" data-uk-tooltip title="Report Defective"><i style="color: #30d2d6" class="fas fa-flag"></i></a> 
  <?php
  if (isset($mode) && $mode == 'defective') {
  ?>
            <a data-uk-tooltip title="Generate Delivery Order" href="admin/generate_do.php?order_no=<?php echo sha1($row["order_no"])?>" target="_blank"><img src="images/DO.png" style="width:30px;"></a>
<?php
  }
}
?>
         </td>
      </tr>
<?php
   }
?>
</tbody>
   </table>
   </div>
<?php  
} else {
   echo "<tr class='uk-text-small'><td colspan='9'>No Record found</td></tr>";
}
?>
</div>
<?php
   } else {
      echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div></div>";
   }
} else {
   header("Location: index.php");
}
?>
<div id="dlg"></div>






<div id="#dropdownMenuButton">

  <div class="dropdown-menu" >
    <a class="dropdown-item" href="#">Hang tight cooming soon</a>
  
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
  
   $('#mydatatable').DataTable();
}); 
// $("button").click(function(){
  // $("p:first").addClass("intro");
// });
</script>


