<a href="index.php?p=defective_status_pg1">                 
				 <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>
<span>
    <span class="page_title"><img src="/images/title_Icons/Defective_Status.png">Defective Status</span>
</span>

<?php
if ($_SESSION["isLogin"]==1) {
   if ((($_SESSION["UserType"]=="S") ||($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) &
   (hasRightGroupXOR($_SESSION["UserName"], "DefectiveStatusEdit|DefectiveStatusView"))) {

include_once("search_new.php");
include_once("lib/pagination/pagination.php");
include_once("admin/functions.php");

function getPaidStatus($order_no) {
   global $connection;

   $sql="SELECT * FROM prove_payment_doc inner join defective on defective.sales_order_no=prove_payment_doc.order_no WHERE defective.order_no = '$order_no'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   if ($row["id"]!="") {
      return " (Paid)";
   } else {
      return " (Pending Payment)";
   }
}
// function getPaidStatus($id) {
//    global $connection;

//    $sql="SELECT * from `defective` where id='$id'";
//    $result=mysqli_query($connection, $sql);
//    $row=mysqli_fetch_assoc($result);

//    if ($row["finance_payment_paid_by"]!="") {
//       return " (Paid)";
//    } else {
//       return " (Pending Payment)";
//    }
// }
function getCentreNameOS($centre_code) {
   global $connection;

   $sql="SELECT kindergarten_name, company_name from centre where centre_code='$centre_code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["kindergarten_name"];
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

   $sql="SELECT * from `defective` where id='$id'";
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

   $sql="SELECT * from `defective` where id='$id'";
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

   $sql="SELECT * from `defective` where id='$id'";
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

   $sql="SELECT * from `defective` where order_no='$order_no' or id='$order_no'";

   $result=mysqli_query($connection, $sql);
   if ($result) {
      $row=mysqli_fetch_assoc($result);
	  
		if ($row["reject_status"] == '1') {
			  return "Rejected";
		  }
	  
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
// function getStatus($order_no) {
   // global $connection;

   // $sql="SELECT * from `defective` where order_no='$order_no' or id='$order_no'";

   // $result=mysqli_query($connection, $sql);
   // if ($result) {
      // $row=mysqli_fetch_assoc($result);

      // if ($row["cancelled_by"]!="") {
         // return "Cancelled";
      // } else {
         // if ($row["delivered_to_logistic_by"]!="") {
            // if ($row["finance_payment_paid_by"]!="") {
               // return "Delivered (Paid)";
            // } else {
               // return "Delivered (Pending Payment)";
            // }

         // } else {
            // if ($row["packed_by"]==1) {
               // if ($row["finance_payment_paid_by"]!="") {
                  // return "Packed (Paid)";
               // } else {
                  // return "Packed (Pending Payment)";
               // }
            // } else {
               // if ($row["finance_approved_by"]!="") {
                  // if ($row["finance_payment_paid_by"]!="") {
                     // return "Finance Approved (Paid)";
                  // } else {
                     // return "Finance Approved (Pending Payment)";
                  // }
               // } else {
                  // if ($row["logistic_approved_by"]!="") {
                     // return "Logistic Approved";
                  // } else {
                     // if ($row["acknowledged_by"]!="") {
                        // return "Acknowledged";
                     // } else {
                        // return "Pending";
                     // }
                  // }
               // }
            // }
         // }
      // }
   // }
// }

foreach ($_GET as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);
}
?>
<script>
function dlgReportDefectiveview(id, sOrderNo) {
   $.ajax({
      url : "admin/dlgReportDefectiveview.php",
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
function doAcknowledged(sha_id, sOrderNo) {
   UIkit.modal.confirm("<h2>ARE YOU SURE TO PROCEED?</h2>", function () {
      $.ajax({
         url : "admin/defective_do_acknowledged.php",
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
         url : "admin/defective_do_logistic_approve.php",
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
      url : "admin/defective_dlgConfirmPayment.php",
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
         url : "admin/defective_do_finance_approved.php",
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

function doPacked(sha_id, sOrderNo) {
   UIkit.modal.confirm("<h2>ARE YOU SURE TO PROCEED?</h2>", function () {
      $.ajax({
         url : "admin/defective_do_packed.php",
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
      url : "admin/defective_dlg_delivered_to_logistic.php",
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
      <h2 class="uk-text-center myheader-text-color  myheader-text-style">Defective Status</h2>
   </div>

<?php
include_once("../mysql.php");
//if ($_SESSION["UserType"]=="S") {
	$sOrderNo = $_GET['sOrderNo'];
  //$sql21="select * from prove_payment where order_no='$sOrderNo'";
  $sql21 ="SELECT prove_payment_doc.*, defective.sales_order_no  FROM prove_payment_doc inner JOIN user ON prove_payment_doc.payment_by=user.user_name inner join defective on defective.sales_order_no=prove_payment_doc.order_no  WHERE defective.order_no = '$sOrderNo' and user.user_type='S' ";
  $the_result33=mysqli_query($connection, $sql21);
   $row33=mysqli_fetch_assoc($the_result33);
  //echo $sql21;
//}


if ($_SESSION["UserType"]=="S") {
   //$sql="SELECT * from `defective`";
   $sql="SELECT d.*, p.product_name from `defective` d left join product p on d.product_code=p.product_code";
} else {
   //$sql="SELECT * from `defective` where centre_code='".$_SESSION["CentreCode"]."'";
   $sql="SELECT d.*, p.product_name from `defective` d left join product p on d.product_code=p.product_code where d.centre_code='".$_SESSION["CentreCode"]."'";
}
$token_order_no=ConstructToken("d.order_no", "%".$_GET["sOrderNo"]."%", "like");
$token_product_code=ConstructToken("d.product_code", "%".$_GET["sProductCode"]."%", "like");
$token_product_code=ConstructToken("p.product_name", "%".$_GET["sProductName"]."%", "like");
$token_centre_code=ConstructToken("d.centre_code", "%".$_GET["sCentreCode"]."%", "like");
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
      case "Approved" : $token_order_status=ConstructToken("finance_approved_by", "{}", "<>"); break;
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
//echo $final_sql;
$numperpage=30;
$query="p=$p&sOrderNo=$sOrderNo&sProductCode=$sProduct_code&sUserName=$sUserName&sOrderStatus=$sOrderStatus";
$pagination=getPagination($pg, $numperpage, $query, $final_sql, $start_record, $num_row);
$final_sql.=" limit $start_record, $numperpage";
$result=mysqli_query($connection, $final_sql);
$num_row=mysqli_num_rows($result);



$sha_id=sha1($row["id"]);
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
	<!-- <div class="uk-grid"> -->
   <div class="">
    <div class="uk-width-medium-1-1">
	<div class="nice-form">
<form name="frmStatus" id="frmStatus" method="get" class="uk-form" >
    <div class="row">
        <div class="col-sm-12 col-md">
            <input type="text" name="sOrderNo" size="12" id="sOrderNo" placeholder="Order No" value="<?php echo $_GET['sOrderNo']?>" readonly>
        </div>
        <div class="col-sm-12 col-md">
            <input type="text" name="sProductName" id="sProductName" placeholder="Product Name" value="<?php echo $_GET['sProductName']?>">
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
	<?php if (getStatus($_GET["sOrderNo"]) != "Cancelled") { ?>
    <!--<a onclick="dlg_Prove_Of_Payment('<?php echo $sha_id?>', '<?php echo $sOrderNo?>')" class='uk-button uk-button-small uk-button-primary form_btn' style="color:white;"><i class="fa fa-check"></i> Proof of Payment</a>-->
	<?php } ?>
         <a style="color:white;" type="button" data-toggle="modal"  data-target="#exampleModal" class="uk-button uk-button-small uk-button-primary form_btn" >View Proof of Payment</a>

         <br><br><br>
       <?php endif;?>


    <?php if ($mode != 'defective'):?>

    <div class="step-container d-md-flex d-sm-block">
	<?php if (getStatus($_GET["sOrderNo"]) == "Rejected") { ?>
        <div class="step" <?php echo( getStatus($_GET["sOrderNo"]) == "Rejected") ? 'style="color:red"' : ''?>>
            <i class="fas fa-times-circle"></i>
            <p>Rejected</p>
        </div>
	<?php
	}else{
	?>
		<div class="step" <?php echo( getStatus($_GET["sOrderNo"]) == "Pending") ? 'style="color:#ffa500"' : ''?>>
            <i class="fas fa-pager"></i>
            <p>Pending</p>
        </div>
	<?php
	}
	?>	
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
            <!-- <p>Ready for Collection <?php //if($_SESSION["UserType"]=="S"): ?>(<?php //echo ($row33["id"]=="") ? "Pending Payment" : "Paid" ?>)<?php //endif ?></p> -->
               <p>Ready for Collection (<?php echo ($row33["id"]=="") ? "Pending Payment" : "Paid" ?>)</p>
        </div>
        <div class="step-arrow">
            <i class="fas fa-chevron-right"></i>
        </div>
        <div class="step" <?php echo( in_array(getStatus($_GET["sOrderNo"]), ["Delivered", "Delivered (Pending Payment)", "Delivered (Paid)"])) ? 'style="color:#ffa500"' : ''?>>
            <i class="fas fa-truck-loading"></i>
            <!-- <p>Delivered <?php //if($_SESSION["UserType"]=="S"): ?>(<?php //echo ($row33["id"]=="") ? "Pending Payment" : "Paid" ?>)<?php //endif ?></p> -->
               <p>Delivered (<?php echo ($row33["id"]=="") ? "Pending Payment" : "Paid" ?>)</p>
        </div>
    </div>
  <?php endif; ?>

<!--    <a class="uk-button" id="btnClear">Clear</a> -->
</form><br>
<!--<a href="index.php?p=defective_status_pg1" class="uk-button uk-button-small"><< Back</a>-->

<?php
$sOrderNo=sha1($_GET["sOrderNo"]);
$the_result=mysqli_query($connection, $final_sql);
$row=mysqli_fetch_assoc($the_result);
//$sales_order_no= sha1($row["sales_order_no"]);
//echo $sales_order_no;
if (getStatus($_GET["sOrderNo"]) != "Rejected"){ 
if (hasRightGroupXOR($_SESSION["UserName"], "OrderStatusEdit")) {
if (($row["finance_payment_paid_by"]=="") & (($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) & ($_SESSION["isLogin"]==1)
   & (hasRight($_SESSION["UserName"] ,"ConfirmPaymentDefectiveEdit"))) {
?>
            <a id="btnConfirmPayment" onclick="dlgConfirmPayment('<?php echo $sha_id?>', '<?php echo $sOrderNo?>')" class="uk-button uk-button-small uk-button-primary form_btn"><i class="fa fa-money"></i> Confirm Payment</a>
<?php
}
?>
<?php
if (($row["acknowledged_by"]=="") & ($row["cancelled_by"]=="") & ($_SESSION["isLogin"]==1) & (($_SESSION["UserType"]=="S"))
   & (hasRight($_SESSION["UserName"], "AcknowledgeDefectiveEdit"))) {
?>
            <a onclick="doAcknowledged('<?php echo $sha_id?>', '<?php echo $sOrderNo?>')" class="uk-button uk-button-small uk-button-primary form_btn" style="color:#fff;"><i class="fa fa-check"></i> Acknowledge</a>
<?php
}
?>
<?php
if (($row["acknowledged_by"]!="") & ($row["logistic_approved_by"]=="") & ($row["cancelled_by"]=="") & ($_SESSION["isLogin"]==1)
   & (($_SESSION["UserType"]=="S")) & (hasRight($_SESSION["UserName"], "LogisticApproveDefectiveEdit"))) {
?>
            <a onclick="doLogisticApprove('<?php echo $sha_id?>', '<?php echo $sOrderNo?>')" class="uk-button uk-button-small uk-button-primary form_btn" style="color:#fff;"><i class="fa fa-check-circle-o"></i> Logistic Approve</a>
<?php
}
?>
<?php
if (($row["acknowledged_by"]!="") & ($row["logistic_approved_by"]!="") & ($row["finance_approved_by"]=="") & ($row["packed_by"]!="") &
   ($row["cancelled_by"]=="") & ($_SESSION["isLogin"]==1) & (($_SESSION["UserType"]=="S"))
   & (hasRight($_SESSION["UserName"], "FinanceApproveDefectiveEdit"))) {
?>
            <a onclick="doFinanceApproved('<?php echo $sha_id?>', '<?php echo $sOrderNo?>')" class="uk-button uk-button-small uk-button-primary form_btn" style="color:#fff;"><i class="fa fa-check-square-o"></i> Finance Approved</a>
<?php
}
?>
<?php
if (($row["acknowledged_by"]!="") & ($row["finance_approved_by"]=="") & ($row["packed_by"]=="") & ($row["logistic_approved_by"]!="") & ($row["cancelled_by"]=="")
   & ($_SESSION["isLogin"]==1) & (($_SESSION["UserType"]=="S")) & (hasRight($_SESSION["UserName"], "PackedDefectiveEdit"))) {
?>
            <a onclick="doPacked('<?php echo $sha_id?>', '<?php echo $sOrderNo?>')" class="uk-button uk-button-small uk-button-primary form_btn" style="color:white;"><i class="fa fa-barcode"></i> Packed</a>
<?php
}
?>
<?php
if (($row["acknowledged_by"]!="") & ($row["finance_approved_by"]!="") & ($row["packed_by"]!="") & ($row["delivered_to_logistic_by"]=="")
   & ($row["cancelled_by"]=="") & ($_SESSION["isLogin"]==1) & (($_SESSION["UserType"]=="S"))
   & ($row["logistic_approved_by"]!="") & (hasRight($_SESSION["UserName"], "DeliveryDefectiveEdit"))) {
?>
            <a onclick="doDeliveredToLogistic('<?php echo $sha_id?>', '<?php echo $sOrderNo?>')" class="uk-button uk-button-small uk-button-primary form_btn" style="color:white;"><i class="fa fa-truck"></i>Delivery</a>
<?php
}
}  //if hasRightGroupXOR($_SESSION["UserName], "OrderStatusEdit")
}
?>
<?php
if (($row["ordered_by"]!="") & ($_SESSION["isLogin"]==1) & (($_SESSION["UserType"]=="S"))) {
   $order=sha1($sOrderNo);
?>
            <!-- <a href="admin/generate_so.php?order_no=<?php echo $sOrderNo?>" class="uk-button uk-button-small" target="_blank"><i class="fa fa-bullhorn"></i> Generate SO</a> -->
<?php
}
?>
<?php
if (($row["ordered_by"]!="") & ($_SESSION["isLogin"]==1) & ($row["delivered_to_logistic_by"]!="")) {
   $order=sha1($sOrderNo);
?>
            <a href="admin/generate_defective_do.php?order_no=<?php echo $sOrderNo?>" class="uk-button uk-button-small uk-button-primary form_btn" target="_blank" style="color:white;"><i class="fa fa-bookmark"></i> Generate DO</a>
<?php
}

?>



    <div style="margin-top: 72px;" class="uk-grid">
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
		<?php if (getStatus($_GET["sOrderNo"]) != "Rejected") { ?>
        <?php
      if (($row["ordered_by"]!="") & ($_SESSION["isLogin"]==1) & (($_SESSION["UserType"]=="S") || ($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O"))) {
         $order=sha1($sOrderNo);
      ?>
                  <a href="admin/generate_defective_so.php?order_no=<?php echo $sOrderNo?>" class="uk-button uk-button-small uk-button-primary form_btn" target="_blank" style="color:white;"><i class="fa fa-bullhorn"></i> Generate SO</a>
                  
      <?php
      }
      ?>
       <?php
      if (($row["cancelled_by"]=="") & ($_SESSION["isLogin"]==1)
         & (($_SESSION["UserType"]=="S") || ($_SESSION["UserType"]=="A"))) {
      ?>
                  <!-- <a onclick="doCancelOrder('<?php //echo $sha_id?>', '<?php //echo $sOrderNo?>')" class="uk-button uk-button-small uk-button-primary form_btn text-white"><i class="fa fa-times-circle"></i> Cancel Order</a> -->
      <?php
      }
		}
      ?>
      <br><br><br>
     </div>
  </div>
  </div>

    </div>
  </div>
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

$od= $row["sales_order_no"];

$r=$row['finance_payment_paid_by'];
$d =$row['finance_payment_paid_on'];
// $g=$row['remarks'];
$sql22 ="SELECT prove_payment_doc.*, prove_payment.remark FROM prove_payment_doc LEFT JOIN prove_payment ON prove_payment.id=prove_payment_doc.prove_payment_id WHERE prove_payment_doc.order_no = '$od'";
//echo $sql22; die;
$result22=mysqli_query($connection,$sql22);
while($row22 = mysqli_fetch_assoc($result22)) {

$doc = $row22['doc'];
$remarks = $row22['remark'];
$update_date = $row22['update_date'];
$path = "/admin/uploads/".$doc;
$path_info = pathinfo($path);
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

<?php
if ($num_row>0) {
   echo $pagination;
?>
<div style="margin-top:30px;" class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color">LISTING</h2>
   </div>
   <div class="uk-overflow-container">
   <table class="uk-table">
      <tr class="uk-text-bold uk-text-small">
         <td>Defective No.</td>
         <!-- <td>Product Code</td>  -->
         <td>Product Name</td>
         <td class="uk-text-right">Qty</td>
         <td class="uk-text-right">Unit Price</td>
         <td class="uk-text-right">Total</td>
<?php
if (($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) {
?>
         <td class="uk-text-left">Status</td>
<?php
}
?>
<?php
if ($_SESSION["UserType"]=='A') {
?>
         <!-- <td>Status</td> -->
<?php
} else {
?>
         <!--<td data-uk-tooltip="{pos:top}" title="Ordered By">Reported By</td>-->
<?php
if (hasRightGroupXOR($_SESSION["UserName"], "AcknowledgeDefectiveEdit|AcknowledgeDefectiveView")) {
?>
         <td data-uk-tooltip="{pos:top}" title="Acknowledged By">Acknowledged By CGO</td>
<?php
}
?>
<?php
if (hasRightGroupXOR($_SESSION["UserName"], "LogisticApproveDefectiveEdit|LogisticApproveDefectiveView")) {
?>
         <td data-uk-tooltip="{pos:top}" title="Logistic Approved By">Logistic Approved By</td>
<?php
}
?>
<?php
if (hasRightGroupXOR($_SESSION["UserName"], "PackedDefectiveEdit|PackedDefectiveView")) {
?>
         <td data-uk-tooltip="{pos:top}" title="Packed By">Packed By Store</td>
<?php
}
?>
<?php
if (hasRightGroupXOR($_SESSION["UserName"], "FinanceApproveDefectiveEdit|FinanceApproveDefectiveView")) {
?>
         <td data-uk-tooltip="{pos:top}" title="Finance Approved By">Finance Approved By</td>
<?php
}
?>
<?php
if (hasRightGroupXOR($_SESSION["UserName"], "DeliveryDefectiveEdit|DeliveryDefectiveView")) {
?>
         <td data-uk-tooltip="{pos:top}" title="Delivered To Logistic">Delivery</td>
<?php
}
?>
         <td data-uk-tooltip="{pos:top}" title="Cancelled By">Cancelled By</td>
<?php
}
?>
<td>Remarks</td>
<td class="uk-text-center">Action</td>
      </tr>
<?php
//   $result=mysqli_query($final_sql);
   while ($row=mysqli_fetch_assoc($result)) {
      $sha_id=sha1($row["id"]);
      $sha_order_no=sha1($row["order_no"]);
?>
      <tr class="uk-text-small">
         <td><?php echo $row["order_no"]?></td>
         <!-- <td><?php echo explode("((--",$row["product_code"])[0];?></td> -->
         <td><?php echo $row["product_name"];?></td>
         <td class="uk-text-right"><?php echo $row["qty"]?></td>
         <td class="uk-text-right"><?php echo $row["unit_price"]?></td>
         <td class="uk-text-right"><?php echo $row["total"]?></td>
<?php
if (($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) {
?>
         <td><div data-uk-tooltip title="<?php echo $row['tracking_no']?>"><?php echo getStatus($row['id'])?></div></td>
<?php
} else {
?>
         <!--<td><div data-uk-tooltip="{pos:top}" title="<?php //echo DateTime($row['ordered_by'], $row['ordered_on'])?>"><?php //echo Person($row["ordered_by"])?></div></td>-->
<?php
if (hasRightGroupXOR($_SESSION["UserName"], "AcknowledgeDefectiveEdit|AcknowledgeDefectiveView")) {
?>
         <td><div data-uk-tooltip="{pos:top}" title="<?php echo DateTime($row['acknowledged_by'], $row['acknowledged_on'])?>"><?php echo Person($row["acknowledged_by"])?></div></td>
<?php
}
?>
<?php
if (hasRightGroupXOR($_SESSION["UserName"], "LogisticApproveDefectiveEdit|LogisticApproveDefectiveView")) {
?>
         <td><div data-uk-tooltip="{pos:top}" title="<?php echo DateTime($row['logistic_approved_by'], $row['logistic_approved_on'])?>"><?php echo Person($row["logistic_approved_by"])?></div></td>
<?php
}
?>
<?php
if (hasRightGroupXOR($_SESSION["UserName"], "PackedDefectiveEdit|PackedDefectiveView")) {
?>
         <!-- <td><div data-uk-tooltip="{pos:top}" title="<?php //echo packedDateTime($row['id'], $row['packed_by'], $row['packed_on'])?>"><?php //echo Person($row["packed_by"]).getPaidStatus($row["id"])?></div></td> -->
         <td><div data-uk-tooltip="{pos:top}" title="<?php echo packedDateTime($row['id'], $row['packed_by'], $row['packed_on'])?>"><?php echo Person($row["packed_by"]).getPaidStatus($row["order_no"])?></div></td>
<?php
}
?>
<?php
if (hasRightGroupXOR($_SESSION["UserName"], "FinanceApproveDefectiveEdit|FinanceApproveDefectiveView")) {
?>
         <td><div data-uk-tooltip="{pos:top}" title="<?php echo DateTime($row['finance_approved_by'], $row['finance_approved_on'])?>"><?php echo Person($row["finance_approved_by"])?></div></td>
<?php
}
?>
<?php
if (hasRightGroupXOR($_SESSION["UserName"], "DeliveryDefectiveEdit|DeliveryDefectiveView")) {
?>
         <!-- <td><div data-uk-tooltip="{pos:top}" title="<?php //echo deliveredToDateTime($row['id'], $row['delivered_to_logistic_by'], $row['delivered_to_logistic_on'])?>"><?php //echo Person($row["delivered_to_logistic_by"]).getPaidStatus($row["id"])?></div></td> -->
         <td><div data-uk-tooltip="{pos:top}" title="<?php echo deliveredToDateTime($row['id'], $row['delivered_to_logistic_by'], $row['delivered_to_logistic_on'])?>"><?php echo Person($row["delivered_to_logistic_by"]).getPaidStatus($row["order_no"])?></div></td>
<?php
}
?>
         <td><div data-uk-tooltip="{pos:top}" title="<?php echo cancelDateTime($row['id'], $row['cancelled_by'], $row['cancelled_on'])?>"><?php echo Person($row["cancelled_by"])?></div></td>
<?php
}
?>
<td class="uk-text-center"><?php echo $row["cancel_reason"]?></td>
<td style="white-space: nowrap;"><?php
if (($_SESSION["isLogin"]==1) & (hasRightGroupXOR($_SESSION["UserName"], "DefectiveStatusEdit|DefectiveStatusView"))) {
?>  <center>
		<a onclick="dlgReportDefectiveview('<?php echo $row['id']?>', '<?php echo $sOrderNo?>')";" data-uk-tooltip title="View report"><i style="color: #30d2d6; font-size:15px;" class="fas fa-flag"></i></a>
		
<!-- <a data-uk-tooltip title="Reject" onclick="doRejectRecord()"  href='index.php?p=defective_status&reject=<?php //echo $row["order_no"]?>&sOrderNo=<?php //echo $row['order_no']?>' id="btnDelete"><i class="fa fa-ban text-info" style="font-size: 1.3rem;"></i></a> -->
	</center>           
<?php
}
?></td>
      </tr>
<?php
   }
?>
   </table>
   </div>
<?php
} else {
   echo "<tr class='uk-text-small'><td colspan='11'>No Record found</td></tr>";
}
?>
</div>
<?php
   } else {
      echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
   }
} else {
   header("Location: index.php");
}
?>
<div id="dlg"></div>
<?php
if ($_GET['reject']) {
	echo "hh";
   $order_no = $_GET['reject'];
  $sql="UPDATE `defective` set reject_status='1' where order_no = $order_no";
 $result=mysqli_query($connection, $sql);
//header("Location: /index.php?p=defective_status&sOrderNo='$order_no'");
//header("Location: https://www.google.com/");
?>
<script>
	window.location = "/index.php?p=defective_status&sOrderNo=<?php echo $order_no ?>";
</script>
<?php
}
 ?>
<script>
function doRejectRecord() {
   return confirm('Are you sure to continue?',
   function () {
		location.reload();
   }); 
}
</script>


<style>
.form_btn {
    float: right;
    margin: 0 5px;
}
.uk-text-small {
    font-size: 14px;
}
</style>