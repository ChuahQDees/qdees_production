<a href="index.php?p=<?php echo $_GET["back"] ? 'student_info&ssid='.$_GET["ssid"].'&back='.$_GET["back"] : 'student_info&ssid='.$_GET["ssid"].'&back='.$_GET["back"]?>">
   <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>
<span>
   <span class="page_title"><img src="/images/title_Icons/Payment.png">Payment</span>
</span>

<?php
session_start();
if (($_SESSION["isLogin"]==1) & (($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) &
(hasRightGroupXOR($_SESSION["UserName"], "PointOfSalesEdit|PointOfSalesView"))) {
include_once("mysql.php");
include_once("lib/mylib/search_new.php");
include_once("admin/functions.php");
include_once("lib/pagination/pagination.php");

$ssid=$_GET["ssid"];
$year=$_SESSION['Year'];
$centre_code=$_SESSION["CentreCode"];

$p=$_GET["p"]; 
$level_type=$_GET["level_type"];

function getStrMonth($month) {
   switch ($month) {
      case 1 : return "Jan"; break;
      case 2 : return "Feb"; break;
      case 3 : return "Mar"; break;
      case 4 : return "Apr"; break;
      case 5 : return "May"; break;
      case 6 : return "Jun"; break;
      case 7 : return "Jul"; break;
      case 8 : return "Aug"; break;
      case 9 : return "Sep"; break;
      case 10 : return "Oct"; break;
      case 11 : return "Nov"; break;
      case 12 : return "Dec"; break;
   }
}

function getStudentCodeName($ssid, &$student_code, &$student_name) {
   global $connection;

   //$sql="SELECT * from student where sha1(student_code)='$ssid'";
   $sql="SELECT * from student where sha1(id)='$ssid'";
   //echo $sql;
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   $student_code=$row["student_code"];
   $student_name=$row["name"];
}

function getStartMonthEndMonth($group_id, &$start_month, &$end_month) {
   global $connection;

   $sql="SELECT * from `group` where id='$group_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   $start_month=date("m", strtotime($row["start_date"]));
   $end_month=date("m", strtotime($row["end_date"]));
}

function getBatchNoByAllocationID($allocation_id, $collection_type) {
   global $connection;

   $sql="SELECT batch_no from collection where allocation_id='$allocation_id' and collection_type='$collection_type'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return sha1($row["batch_no"]);
}

function getBatchNoByAllocationIDA($allocation_id, $collection_type, $month) {
   global $connection;

   $sql="SELECT batch_no from collection where allocation_id='$allocation_id' and collection_type='$collection_type' and collection_month='$month'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return sha1($row["batch_no"]);
}

function isAllDeleted($sql) {
   global $connection;

   $result=mysqli_query($connection, $sql);
   $got_at_least_one_is_active=false;
   while ($row=mysqli_fetch_assoc($result)) {
      if (!isDeleted($row["id"])) {
         $got_at_least_one_is_active=true;
      }
   }

   if ($got_at_least_one_is_active==true) {
      return false;
   } else {
      return true;
   }
}

function getStudentName($sid) {
   global $connection;

   $sql="SELECT `name` from student where sha1(id)='$sid'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["name"];
}

function getStudentID($sid) {
   global $connection;
	
   $sql="SELECT `id` from student where sha1(id)='$sid'";
   //echo $sql; die;
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["id"];
}


function isStudentDeleted($student_id) {
   global $connection;

   $sql="SELECT deleted from student where id='$student_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   if ($row["deleted"]==1) {
      return true;
   } else {
      return false;
   }
}

function isCourseDeleted($course_id) {
   global $connection;

   $sql="SELECT deleted from course where id='$course_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   if ($row["deleted"]==1) {
      return true;
   } else {
      return false;
   }
}

function isClassDeleted($class_id) {
   global $connection;

   $sql="SELECT deleted from class where id='$class_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   if ($row["deleted"]==1) {
      return false;
   } else {
      return false;
   }
}

function isDeleted($allocation_id) {
   global $connection;

   $sql="SELECT * from allocation where id='$allocation_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   if ($row["deleted"]==0) {
      $student_id=$row["student_id"];
      $course_id=$row["course_id"];
      $class_id=$row["class_id"];

      if (isStudentDeleted($student_id) || isCourseDeleted($course_id) || (isClassDeleted($class_id))) {
         return true;
      } else {
         return false;
      }
   } else {
      return true;
   }
}

function isGotProductSale($allocation_id, $collection_month) {
   global $connection, $year;

   $sql="SELECT * from collection where allocation_id='$allocation_id' and collection_type='product'
   and `year`='$year' and month(collection_date_time)='$collection_month'";

   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      return true;
   } else {
      return false;
   }
}

function getBatchNo($allocation_id, $collection_month) {
   global $connection;

   $sql="SELECT * from collection where allocation_id='$allocation_id' and month(collection_date_time)='$collection_month'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return sha1($row["batch_no"]);
}

function getCourseFee($course_id, &$course_fee, &$registration, &$deposit, &$placement) {
   global $connection;

   $sql="SELECT * from course where id='$course_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   $course_fee=$row["fees"];
   $deposit=$row["deposit"];
   $placement=$row["placement"];

   $sql="SELECT registration_fee from centre where centre_code='".$_SESSION["CentreCode"]."'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   $registration=$row["registration_fee"];
}

function getCourseName($course_id) {
   global $connection;

   $sql="SELECT * from course where id='$course_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["course_name"];
}
function getAoProductName($product_code) {
   global $connection;

   $sql="SELECT * from addon_product where product_code='$product_code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["product_name"];
}

function getFees($allocation_id, $collection_month, $collection_type) {
   global $connection, $year;

   $sql="SELECT sum(amount) as amount from collection where allocation_id='$allocation_id' and collection_type='$collection_type'
   and `year`='$year' and collection_month='$collection_month' group by allocation_id";

   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);
   $row=mysqli_fetch_assoc($result);

   if ($num_row>0) {
      return $row["amount"];
   } else {
      return "0";
   }
}

function getExtraFees($allocation_id, $collection_type) {
   global $connection, $year;

   if ($collection_type!="registration") {
      //$sql="SELECT sum(amount) as amount from collection where allocation_id='$allocation_id' and collection_type='$collection_type'
      //and `year`='$year' group by allocation_id";
	  $sql="SELECT sum(amount) as amount from collection where collection_type='$collection_type'";
   } else {
      //$sql="SELECT s.student_code from student s, allocation a where a.student_id=s.id and a.id='$allocation_id'";
	  $sql="SELECT s.student_code from student s, programme_selection ps where ps.student_id=s.id";
      $result=mysqli_query($connection, $sql);
      $row=mysqli_fetch_assoc($result);
      $student_code=$row["student_code"];

      $sql="SELECT sum(amount) as amount from collection where collection_type='$collection_type'
      and `year`='$year' and student_code='$student_code'";
   }

   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);
   $row=mysqli_fetch_assoc($result);

   if ($num_row>0) {
      return $row["amount"];
   } else {
      return "0";
   }
}
?>

<script>
function dlgProductSale(allocation_id, collection_month, batch_no, ssid) {
   $.ajax({
      url : "admin/dlgProductSale.php",
      type : "POST",
      data : "allocation_id="+allocation_id+"&collection_month="+collection_month+"&batch_no="+batch_no+"&ssid="+ssid,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         if (response!="") {
            $("#dlgProductSale").html(response);
            $("#dlgProductSale").dialog({
               dialogClass:"no-close",
               title:"Product Sale",
               modal:true,
               height:'auto',
               width:'auto',
            });
         }
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}

<?php
if (hasRightGroupXOR($_SESSION["UserName"], "PointOfSalesEdit")) {
?>
function dlgPayFees(student_id) {
   $.ajax({
      url : "admin/dlg_pay_fees.php",
      type : "POST",
      data : "ssid="+student_id,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         $("#dlgPayFees").html(response);
         $("#dlgPayFees").dialog({
            title:"Pay Fees/Buy Product",
            dialogClass:"no-close",
            modal:true,
            height:'auto',
            width:'1000',
         });
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}

<?php
}
?>
</script>

<div id="thetop" class="uk-margin-right uk-margin-top">
   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color myheader-text-style">Point of Sales - <?php echo getStudentName($_GET["ssid"])?></h2>
   </div>

<?php
getStudentCodeName($ssid, $student_code, $student_name);
?>
<div class="nice-form">
   <form class="uk-form" name="frmSearch" id="frmSearch" method="get" style="padding:0;">
      <div class="uk-grid uk-grid-small">
         <div class="uk-width-medium-4-10">
            <div class="uk-grid uk-grid-small">
               <div  style="text-align:left;padding-left: 0;" class=" uk-text-bold">Student Code: </div>
               <div style="text-align:left;padding-left: 0;" class=""> <?php echo $student_code?></div>
            </div>
         </div>
         <div class="uk-width-medium-4-10">
            <div class="uk-grid">
               <div style="text-align:left;padding-left: 0;" class=" uk-text-bold">Student Name: </div>
               <div style="text-align:left;padding-left: 0;" class=""> <?php echo $student_name?></div>
            </div>
         </div>
         <div class="uk-width-medium-2-10">
            <div class="uk-grid">
               <div style="text-align:left;padding-left: 0;" class=" uk-text-bold">Year: </div>
               <div  style="text-align:left;padding-left: 0;" class=""> <?php echo $_SESSION['Year']?></div>
            </div>
         </div>
      </div>
   </form>
   <hr>
<script>
function dlgInitial(student_code) {
   $.ajax({
      url : "admin/dlgInitial.php",
      type : "POST",
      data : "student_code="+student_code,
      dataType : "text",
      beforeSend : function(http) {
      },
      async: false,
      success : function(response, status, http) {
         $("#initial-dialog").html("");
         $("#initial-dialog").html(response);
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}

function dlgPlacement(student_code) {
   $.ajax({
      url : "admin/dlgPlacement.php",
      type : "POST",
      data : "student_code="+student_code,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         $("#placement-dialog").html("");
         $("#placement-dialog").html(response);
      },
      async: false,
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}
function dlgMaterials(student_code) {
   $.ajax({
      url : "admin/dlgMaterials.php",
      type : "POST",
      data : "student_code="+student_code,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         $("#materials-dialog").html("");
         $("#materials-dialog").html(response);
      },
      async: false,
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}
function dlgOutstanding(student_code) {
   $.ajax({
      url : "admin/dlgOutstanding.php",
      type : "POST",
      data : "student_code="+student_code,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         $("#outstanding-dialog").html("");
         $("#outstanding-dialog").html(response);
      },
      async: false,
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}

function dlgProduct(student_code) {
   $.ajax({
      url : "admin/dlgProduct.php",
      type : "POST",
      data : "student_code="+student_code,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         $("#products-dialog").html("");
         $("#products-dialog").html(response);
      },
      async: false,
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}

function dlgAddOnProduct(student_code) {
   $.ajax({
      url : "admin/dlgAddOnProduct.php",
      type : "POST",
      data : "student_code="+student_code,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         $("#addon-product-dialog").html("");
         $("#addon-product-dialog").html(response);
      },
      async: false,
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}

</script>
      <div class="uk-grid">
         <div class="uk-width-medium-1-2" style="border-right: 1px solid #dddddd;">
            <div class="uk-grid uk-grid-small">
               <div class="uk-width-medium-1-3 square-button">
                  <a onclick="dlgOutstanding('<?php echo sha1($student_code)?>')" class="uk-button outstanding uk-button-large uk-width-1-1" data-uk-tooltip title="Outstanding">Outstanding</a>
                   <div id="outstanding-dialog"></div>
               </div>
               <div class="uk-width-medium-1-3 square-button">
                  <a onclick="dlgInitial('<?php echo sha1($student_code)?>')" class="initial uk-button uk-button-large uk-width-1-1" data-uk-tooltip title="Initial">Initial</a>
                  <div id="initial-dialog"></div>
               </div>
               <div class="uk-width-medium-1-3 square-button">
                  <a onclick="dlgPlacement('<?php echo sha1($student_code)?>')" class="placement uk-button uk-button-large uk-width-1-1" data-uk-tooltip title="Enhanced Foundation">Enhanced Foundation</a>
                  <div id="placement-dialog"></div>
               </div>
			   <div class="uk-width-medium-1-3 square-button">
                  <a onclick="dlgMaterials('<?php echo sha1($student_code)?>')" class="materials uk-button uk-button-large uk-width-1-1" data-uk-tooltip title="Materials">Materials</a>
                  <div id="materials-dialog"></div>
               </div>
<!--           <div class="uk-width-medium-1-3 square-button">
                  <a class="uk-button uk-button-large uk-width-1-1" data-uk-tooltip title="Advance IE">Advance IE</a>
               </div>
            <div class="uk-width-medium-1-3 square-button">
                  <a class="uk-button uk-button-large uk-width-1-1" data-uk-tooltip title="Advance BIEP">Advance BIEP</a>
               </div>
               <div class="uk-width-medium-1-3 square-button">
                  <a class="uk-button uk-button-large uk-width-1-1" data-uk-tooltip title="Advance BIMP">Advance BIMP</a>
               </div>
 -->               <div class="uk-width-medium-1-3 square-button">
                  <a class="products uk-button uk-button-large uk-width-1-1" data-uk-tooltip title="Products">Products</a>
                   <div id="products-dialog"></div>
               </div>
               <div class="uk-width-medium-1-3 square-button">
                  <a onclick="dlgAddOnProduct('<?php echo sha1($student_code)?>')" class="addon-product uk-button uk-button-large uk-width-1-1" data-uk-tooltip title="Add On Product">Add-On Products</a>
                   <div id="addon-product-dialog"></div>
               </div>
            </div>
         </div>
         <div class="uk-width-medium-1-2">
            <div class="myheader uk-text-center myheader-text-color" style="padding: 6px;font-size: 22px;">Basket Content</div>
            <table class="uk-table">
               <tr class="uk-text-bold uk-text-small">
                  <td>No.</td>
                  <td>Description</td>
                  <td class="uk-text-right">Qty</td>
                  <td class="uk-text-right">U/Price</td>
                  <td class="uk-text-right">Total</td>
               <?php if (hasRightGroupXOR($_SESSION["UserName"], "DiscountEdit|DiscountView")) { ?> <td>Discount</td>
               <?php } ?>
               <td class="">Collection</td>
                  <td>Action</td>
               </tr>
<script>
function doRemove(id) {
   UIkit.modal.confirm("<h2>Are you sure you wish to continue?</h2>", function () {
      $.ajax({
         url : "admin/remove_from_basket.php",
         type : "POST",
         data : "id="+id,
         dataType : "text",
         beforeSend : function(http) {
         },
         async: false,
         success : function(response, status, http) {
           var s=response.split("|");
           if (s[0]=="1") {
              window.location.reload();
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

function doPayAll() {
   event.preventDefault();
   var payment_method=$("#payment_method").val();
   var cheque_no=$("#cheque_no").val();
   var bank=$("#bank").val();
   var ref_no=$("#ref_no").val();
   var remarks=$("#remarks").val();
   var total=$("#total").val();
   if($('#total').is(':checked')) { 
      var title_description = $("#title_description").val();
      if (title_description=="") {
         UIkit.notify("Please provide a description");
         return false;
      }
   }

   var verify = "1"; //If 0, do not proceed

   if (payment_method!="") {
      if (payment_method=="CHEQUE") {
         if ((cheque_no!="") & (bank!="") & (ref_no!="")) {
           // $("#frmPayment").submit();
           // window.location.href = "index.php?p=<?php echo $_GET["back"] ? 'student_info&ssid='.$_GET["ssid"].'&back='.$_GET["back"] : 'student_info&ssid='.$_GET["ssid"].'&back='.$_GET["back"]; ?>";
         } else {
            UIkit.notify("Please provide a cheque number, bank and ref no");
            verify = "0";
         }
      } else {
         if (payment_method=="CC") {
            if (bank!="") {
              // $("#frmPayment").submit();
              // window.location.href = "index.php?p=<?php echo $_GET["back"] ? 'student_info&ssid='.$_GET["ssid"].'&back='.$_GET["back"] : 'student_info&ssid='.$_GET["ssid"].'&back='.$_GET["back"]; ?>";
            } else {
               UIkit.notify("Please provide a bank");
               verify = "0";
            }
         } else if (payment_method=="CN") {
            if (remarks!="") {
               UIkit.notify("This is not no payment received.");
               //$("#frmPayment").submit();
               //window.location.href = "index.php?p=<?php echo $_GET["back"] ? 'student_info&ssid='.$_GET["ssid"].'&back='.$_GET["back"] : 'student_info&ssid='.$_GET["ssid"].'&back='.$_GET["back"]; ?>";
            } else {
               UIkit.notify("Please fill in remarks");
               verify = "0";
            }
         } else {
            if (payment_method=="BT") {
               if (bank!="" & (ref_no!="")) {
                 // $("#frmPayment").submit();
                  //window.location.href = "index.php?p=<?php echo $_GET["back"] ? 'student_info&ssid='.$_GET["ssid"].'&back='.$_GET["back"] : 'student_info&ssid='.$_GET["ssid"].'&back='.$_GET["back"]; ?>";
               } else {
                  UIkit.notify("Please provide a bank and reference number");
                  verify = "0";
               }
            }
         }
      }

      if (verify == "1"){
         UIkit.modal.confirm("<h2>Paying receipt. Continue?</h2>", function () {
         $("#frmPayment").submit();
         window.location.href = "index.php?p=<?php echo $_GET["back"] ? 'student_info&ssid='.$_GET["ssid"].'&back='.$_GET["back"] : 'student_info&ssid='.$_GET["ssid"].'&back='.$_GET["back"]; ?>";
         });   
      }

   } else {
      UIkit.notify("Please fill in a payment method");
   }
}

function clearBasket() {
   UIkit.modal.confirm("<h2>Are you sure you wish to continue?</h2>", function () {
      $.ajax({
         url : "admin/clearBasket.php",
         type : "POST",
         data : "",
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
           var s=response.split("|");
           if (s[0]=="1") {
              window.location.reload();
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
function updateDiscount(id, student_id) {
   var discount = Math.abs($('#'+id).val());
   $('#'+id).val(discount);
   new_price = ($('#total-price').val() - discount);
   $.ajax({
      url : "admin/update_discount.php",
      type : "POST",
      data : "discount="+discount+"&id="+id +"&student_id="+student_id,
      dataType : "text",
      beforeSend : function(http) {
      },
      async: false,
      success : function(response, status, http) {
        var s=response.split("|");
        if (s[0]=="1") {
           $('#total-price').text(s[1]);
        } else {
           UIkit.notify(s[1]);
        }
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });

  // updateCollection(id, student_id);
}
function updateCollection(id, student_id) {
   var collection = Math.abs($('#collection_'+id).val());
   $('#collection_'+id).val(collection);
  // new_price = ($('#total-price').val() - discount);
   $.ajax({
      url : "admin/update_collection.php",
      type : "POST",
      data : "collection="+collection+"&id="+ id +"&student_id="+student_id,
      dataType : "text",
      beforeSend : function(http) {
      },
      async: false,
      success : function(response, status, http) {
        var s=response.split("|");
        if (s[0]=="1") {
           $('#total-collection').text(s[1]);
        } else {
           UIkit.notify(s[1]);
        }
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}
</script>
<?php
$studentID = getStudentID($_GET["ssid"]);
//echo $studentID;
$sql="SELECT * from busket where session_id='".session_id()."' and student_id = '$studentID'";

$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);
if ($num_row>0) {
   $count=0;
   while ($row=mysqli_fetch_assoc($result)) {
      $count++;
      switch ($row["collection_type"]) {
         case "product" : $description=$row["product_code"]; $qty=$row["qty"]; $unit_price=$row["unit_price"]; $total=$qty*$unit_price; break;
         case "addon-product" : $description=getAoProductName($row["product_code"]); $qty=$row["qty"]; $unit_price=$row["unit_price"]; $total=$qty*$unit_price; break;
		   case "tuition" : $description=$row["qty"]; $qty=$row["qty"]; $unit_price=$row["unit_price"]; $total=$row["amount"]; break;
         case "Deposit" : $description="Deposit "; $qty=$row["qty"]; $unit_price=$row["unit_price"]; $total=$row["amount"]; break;
         case "placement" : $description="Placement Fee "; $qty=$row["qty"]; $unit_price=$row["unit_price"]; $total=$row["amount"]; break;
         case "registration" : $description="Registration Fee"; $qty=$row["qty"]; $unit_price=$row["unit_price"]; $total=$row["amount"]; break;
		 case "insurance" : $description="Insurance"; $qty=$row["qty"]; $unit_price=$row["unit_price"]; $total=$row["amount"]; break;
		 case "q-dees-level-kit" : $description="Q-dees Level Kit"; $qty=$row["qty"]; $unit_price=$row["unit_price"]; $total=$row["amount"]; break;
         // case "tuition" : ($row["product_code"]=='opening-balance'? ($description='Opening tuition outstanding') : ($description=$row["subject"])); $qty=""; $unit_price=""; $total=$row["amount"]; break;
         case "mobile" : $description="Mobile App Fee"; $qty=$row["qty"]; $unit_price=$row["unit_price"]; $total=$row["amount"]; break;
		case "math" : $description=$row["product_code"]; $qty=$row["qty"]; $unit_price=$row["unit_price"]; $total=$row["amount"]; break;
		case "mandarin" : $description=$row["product_code"]; $qty=$row["qty"]; $unit_price=$row["unit_price"]; $total=$row["amount"]; break;
		case "international" : $description=$row["product_code"]; $qty=$row["qty"]; $unit_price=$row["unit_price"]; $total=$row["amount"]; break;
		case "foundation" : $description=$row["product_code"]; $qty=$row["qty"]; $unit_price=$row["unit_price"]; $total=$row["amount"]; break;case "integrated" : $description=$row["product_code"]; $qty=$row["qty"]; $unit_price=$row["unit_price"]; $total=$row["amount"]; break;
		break;
		case "link" : $description=$row["product_code"]; $qty=$row["qty"]; $unit_price=$row["unit_price"]; $total=$row["amount"]; break;
		break;
		case "mandarinmodules" : $description=$row["product_code"]; $qty=$row["qty"]; $unit_price=$row["unit_price"]; $total=$row["amount"]; break;
		break;
		case "pendidikan" : $description=$row["product_code"]; $qty=$row["qty"]; $unit_price=$row["unit_price"]; $total=$row["amount"]; break;
      break;
      default: $description=$row["product_code"]; $qty=$row["qty"]; $unit_price=$row["unit_price"]; $total=$row["amount"];
      }

      // var_dump($row["collection_type"] == 'tuition' && $row["product_code"]!='opening-balance');
      if ($row["collection_type"] == 'tuition' && $row["product_code"]=='opening-balance') {
         $description='Opening tuition outstanding'; 
         $qty=1; 
         $unit_price=""; 
         $total=$row["amount"];
      } elseif ($row["collection_type"] == 'tuition' && $row["product_code"]!='opening-balance') {
         if ($row["allocation_id"]>0) {
            $description=getCourseName($row["course_id"]); 
            $description=$row["product_code"]; 
         }else {
            $description=$row["subject"]; 
            $description=$row["product_code"]; 
         }
         $qty=1; 
         //$unit_price=""; 
         $total=$row["amount"];
      }
?>
               <tr class="uk-text-small">
                  <td><?php echo $count?></td>
                  <td>
                     <?php 
                        if($description=="Link"){
                           echo "Link & Think";
                        } else if($description=="pendidikan"){
                              echo "Pendidikan Islam";
                        } else if($description=="Insurance"){
                              echo "Insurance (".$row['single_year'].')';
                        }else{
                           echo explode("((--",$description)[0];
                        }

                        $monthName = '';

                        if($row['collection_type'] == 'tuition' || $row['collection_type'] == 'link' || $row['collection_type'] == 'mandarinmodules' || $row['collection_type'] == 'integrated' || $row['collection_type'] == 'placement' || $row['collection_type'] == 'math' || $row['collection_type'] == 'mandarin' || $row['collection_type'] == 'international' || $row['collection_type'] == 'foundation' || $row['collection_type'] == 'pendidikan' || $row['collection_type'] == 'product' || $row['collection_type'] == 'addon-product' || $row['collection_type'] == 'robotic-plus') {  
                           if($row['collection_pattern'] == 'Monthly') {
                              $monthNum  = $row["collection_month"];
                              $dateObj   = DateTime::createFromFormat('!m', $monthNum);
                              $monthName = $dateObj->format('F');
                           } else if($row['collection_pattern'] == 'Termly') {
                              $monthNum  = $row["collection_month"];
                              $monthName = 'Term '.$monthNum;
                           }
                           if($monthName != '')
                           {
                              echo ' ('.$monthName.')';
                           }
                        }
				         ?>
                  </td>
                  <td class="uk-text-right"><?php echo number_format($qty)?></td>
                  <td class="uk-text-right"><?php echo $unit_price?></td>
                  <td class="uk-text-right"><?php echo number_format($total, 2)?></td>
                  <?php if (hasRightGroupXOR($_SESSION["UserName"], "DiscountEdit")) { ?> 
                     <td>
                     <input style="width: 100px;" min="0" oninput="" type="number" id="<?php echo sha1($row["id"])?>" onchange="updateDiscount('<?php echo sha1($row["id"])?>', '<?php echo $studentID ?>')" value="<?php echo number_format($row["discount"], 2)?>" >
                  </td>
                 
                  <?php } elseif  (hasRightGroupXOR($_SESSION["UserName"], "DiscountView")) { ?> 
                     <td>
                     <input style="border: 0;width: 100px;" min="0" type="number" oninput="" onchange="updateDiscount('<?php echo sha1($row["id"])?>'), '<?php echo $studentID ?>')" value="<?php echo number_format($row["discount"], 2)?>" disabled readonly>
                  </td>
                  
                  <?php
                  }
                  ?>
                  <td>
                  <input type="number" style="width: 100px;" min="0" oninput="" id="collection_<?php echo sha1($row["id"])?>" onchange="updateCollection('<?php echo sha1($row["id"])?>', '<?php echo $studentID ?>')" value="<?php echo number_format($row["collection"], 2)?>" >
                  </td>
                  <td><a onclick="doRemove('<?php echo sha1($row["id"])?>')"><i class="uk-icon-close uk-icon-medium" style="color:#FF0000" data-uk-tooltip title="Remove from Basket"></i></a></td>
               </tr>
<?php
   }
} else {
   echo "<tr class='uk-text-bold uk-text-small'><td colspan='8'>No Record Found</td></tr>";
}
?>
            </table>
<?php

function calcTotalCollection($session_id, $studentID) {
   global $connection;

   $sql="SELECT sum(collection) as collection from busket where session_id='$session_id' and student_id = '$studentID' ";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   $num_row=mysqli_num_rows($result);
   // var_dump($row);

   if ($num_row>0) {
      return ($row["collection"]);
   } else {
      return 0.00;
   }
}
function calcTotal($session_id, $studentID) {
   global $connection;

   $sql="SELECT sum(amount) as amount, sum(discount) as discount from busket where session_id='$session_id' and student_id = '$studentID' ";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   $num_row=mysqli_num_rows($result);
   // var_dump($row);

   if ($num_row>0) {
      return ($row["amount"] - $row["discount"]);
   } else {
      return 0.00;
   }
}
?>
      <form id="frmPayment" name="frmPayment" method="post" action="admin/payAll.php" target="_blank" style="padding: 0;box-shadow: none !important;">
         <input type="hidden" name="studentID" value="<?php echo $studentID?>">
         <div class="uk-width-medium-7-10" style="padding-top:50px; margin-left:auto; margin-right:0;">
            <div class="uk-grid uk-grid-small">
               <div class="uk-width-4-10" style="padding-right:0px;">Total Price:</div>
               <div class="uk-width-6-10" id="total-price" style="padding-right:0px;"><?php echo number_format(calcTotal(session_id() , $studentID), 2)?></div>
            </div>
            <div class="uk-grid uk-grid-small">
               <div class="uk-width-4-10" style="padding-right:0px;">Total Collection:</div>
               <div class="uk-width-6-10" id="total-collection" style="padding-right:0px;"><?php echo number_format(calcTotalCollection(session_id() , $studentID), 2)?></div>
            </div>
            <div class="uk-grid uk-grid-small">
               <div class="uk-width-4-10" style="padding-right:0px;">Remark:</div>
               <div class="uk-width-6-10" style="padding-right:0px;"><input type="text" name="remarks" id="remarks" class="box-style-pos"></div>
            </div>
            <div class="uk-grid uk-grid-small">
               <div class="uk-width-4-10" style="padding-right:0px;">Payment Method:</div>
               <div class="uk-width-6-10" style="padding-right:0px;">
                  <select name="payment_method" id="payment_method" class="uk-form uk-form-medium box-style-pos">
                     <option value="">Select a payment method</option>
                     <option value="CC">Credit Card</option>
                     <option value="CASH">Cash</option>
                     <option value="BT">Bank Transfer</option>
                     <option value="CHEQUE">Cheque</option>
                     <!-- <option value="DISCOUNT">Discount</option> -->
                  </select>
               </div>
            </div>
            <div class="uk-grid uk-grid-small">
               <div class="uk-width-4-10" style="padding-right:0px;">Cheque No:</div>
               <div class="uk-width-6-10" style="padding-right:0px;"><input name="cheque_no" id="cheque_no" type="text" class="box-style-pos"></div>
            </div>
            <div class="uk-grid uk-grid-small">
               <div class="uk-width-4-10" style="padding-right:0px;">Bank:</div>
               <div class="uk-width-6-10" style="padding-right:0px;"><input type="text" name="bank" id="bank" class="box-style-pos"></div>
            </div>
            <div class="uk-grid uk-grid-small">
               <div class="uk-width-4-10" style="padding-right:0px;">Ref No:</div>
               <div class="uk-width-6-10" style="padding-right:0px;"><input type="text" name="ref_no" id="ref_no" class="box-style-pos"></div>
            </div>
            <!-- new addition -->
            <div class="uk-grid uk-grid-small">
               <div class="uk-width-4-10" style="padding-right:0px;">Print:</div>
               <div class="uk-width-6-10" style="padding-right:0px;">
               <label> Total 
                  <input class="uk-radio" id="total" type="radio" name="print_option" value="Total" style=" margin-left: 10px;position: relative; bottom: -2px;" />
               </label>
               <label style="margin-left: 20px;">Itemized
                  <input class="uk-radio" id="itemized" type="radio" name="print_option" value="Itemized" style=" margin-left: 10px;position: relative; bottom: -2px;" checked />
               </label>
               </div>
            </div>
           
            <div id="blk-Total" class="uk-grid uk-grid-small toHide" style="display:none">
               <div class="uk-width-4-10" style="padding-right:0px;">Description:</div>
               <div class="uk-width-6-10" style="padding-right:0px;">
                  <textarea style="width: 100%;" class="uk-width-1-1" rows="4" name="title_description" id="title_description"></textarea>
               </div>
            </div>
            <div class=" toHide" id="des-2" style="display:none">
            </div>
               <script>
                $(function() {
               $("[name=print_option]").click(function(){
                        $('.toHide').hide();
                        $("#blk-"+$(this).val()).show('slow');
               });
            });
               </script>
            <!-- new addition end-->
         </div>
         <div class="uk-grid uk-width-medium-1-1" style="margin-top: 55px; justify-content:center;margin-left: 0px;">
            <div class="uk-grid uk-grid-small">
               <a onclick="clearBasket();" target="_blank" class="modal_btn" style="background:transparent !important; border:1px solid darkgrey; color:darkgrey;text-shadow: none;padding: .4em 2em;">Clear Basket</a>
            </div>
            <div class="uk-grid uk-grid-small" style="margin-top: 0px;">
               <button onclick="doPayAll()" class="modal_btn"  style="padding: .4em 3em;">Pay</button>
            </div>
         </div>
      </form>
   </div>
</div>
</div>
<?php

?>
</div>
<div id="dlgPayFees"></div>
<div id="dlgProductSale"></div>
<div id="dlgs"></div>
<?php
} else {
   echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
}
?>

<style>
    .ui-widget-overlay {
        opacity: 0.5;
        filter: Alpha(Opacity=50);
        background-color: black;
    }

    .q-dialog .ui-dialog-titlebar {
        color: white;
        text-transform: uppercase;
        text-align: center;
        background: #30D2D6;
        border: none;
        padding: 0px!important;
    }

    .q-dialog {
        border: none;
        padding: 0px!important;
    }

    .q-dialog .ui-dialog-titlebar span {
        width: 100%;
        font-size: 1.5rem;
        padding: .75rem 0;
    }

    .q-dialog .ui-dialog-titlebar button.ui-dialog-titlebar-close {
        background: transparent;
        border: none;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 1.2rem;
    }

    .q-dialog .ui-dialog-titlebar button.ui-dialog-titlebar-close::before {
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        content: "\f00d";
    }

    .ui-dialog-content {
        padding: 1.25rem!important;
    }
</style>

<script>
$(function() {
   function myDialog (name) {
      const mytitle = $(`.${name}`).attr('title');
      $(`#${name}-dialog`).dialog({
         autoOpen: false,
         modal: true,
         dialogClass: "q-dialog",
         width: "85%",
         title: mytitle,
      });

      $(`.${name}`).click(() => {
         $(`#${name}-dialog`).dialog("open");
      });
   }

   //dlgOutstanding('<?php echo sha1($student_code)?>')
   myDialog('outstanding');

   //dlgInitial('<?php echo sha1($student_code)?>');
   myDialog('initial');

   //dlgPlacement('<?php echo sha1($student_code)?>');
   myDialog('placement');
   
   //dlgMaterials('<?php echo sha1($student_code)?>');
   myDialog('materials');
   
   dlgProduct('<?php echo sha1($student_code)?>');
   myDialog('products');

   //dlgAddOnProduct('<?php echo sha1($student_code)?>');
   myDialog('addon-product');
});

</script>
