<?php
session_start();
$session_id=session_id();
$year=$_SESSION['Year'];
include_once("../mysql.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);
}

function getStudentCodeFromSSID11($ssid) {
   global $connection;

   $sql="SELECT * from student where sha1(id)='$ssid'";
   echo $sql;
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["student_code"];
}

function calcTotal($student_code) {
   global $connection, $session_id;

   $sql="SELECT sum(b.qty*b.unit_price) as total from tmp_busket b, product p where b.session_id='$session_id' and b.student_code='$student_code' and b.product_code=p.product_code";
   //echo $sql;
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["total"];
}

//$student_code=getStudentCodeFromSSID11($ssid);
$student_code=$ssid;


$sql="SELECT p.*, b.id as bid, b.qty, b.unit_price from tmp_busket b, product p where b.session_id='$session_id' and b.student_code='$ssid' and b.product_code=p.product_code";

//$sql="SELECT b.product_code as course_name, b.id as bid, b.qty, b.unit_price, b.collection_type from tmp_busket b where b.session_id='$session_id' and b.student_code=sha1('$student_code') and collection_type = 'gymwear' or collection_type = 'qdeesbag' or collection_type = 'uniform'";
 //echo $sql;
$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);
?>

<script>
function reduceQty(bid, product_code, ssid) {
   UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function () {
      $.ajax({
         url : "admin/reduceQty.php",
         type : "POST",
         data : "product_code="+product_code+"&ssid="+ssid+"&bid="+bid,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            var s=response.split("|");

            if (s[0]=="1") {
               getTempBusket();
            }

            if (s[0]=="0") {
               UIkit.notify(s[1]);
            }
         },
         error : function(http, status, error) {
            UIkit.notify("Error:"+error);
         }
      });
   })
}

function AddAllRelatedProducts(student_code) {
   UIkit.modal.confirm("<h2>Are you sure to proceed?</h2>", function () {
      $.ajax({
         url : "admin/AddAllRelatedProducts.php",
         type : "POST",
         data : "student_code="+student_code,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            getTempBusket();
            if (response!="") {
               UIkit.notify(response);
            }
         },
         error : function(http, status, error) {
            UIkit.notify("Error:"+error);
         }
      });
   });
}
</script>

<?php
if ($year_start_date <= date('Y-m-d') && $year_end_date >= date('Y-m-d')) {
?>
  <a onclick="AddAllRelatedProducts('<?php echo $ssid?>')" class="uk-button uk-button-small">Add all programme's related products</a>
<?php
}
?>

<script>
function changeProductPrice(product_code) {
   var unit_price=$("#unit_price").val();
   UIkit.modal.confirm("<h2>Are you sure to proceed?</h2>", function () {
      $.ajax({
         url : "admin/changeProductPrice.php",
         type : "POST",
         data : "product_code="+product_code+"&unit_price="+unit_price,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            var s=response.split("|");
            if (s[0]=="1") {
               getTempBusket();
               UIkit.notify(s[1]);
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
</script>

<table class="uk-table uk-table-small">
   <tr class="uk-text-bold uk-text-small">
      <td>Photo</td>
      <td>Code</td>
      <td>Name</td>
      <td>Qty</td>
      <td data-uk-tooltip title="Retail price">Unit Price</td>
      <td>Amount</td>
      <td>Action</td>
   </tr>
   <!-- <tr class="uk-text-bold uk-text-small">

      <td>Name</td>

      <td>Type</td>

      <td>Qty</td>

      <td data-uk-tooltip title="Retail price">Unit Price</td>

      <td>Amount</td>

      <td>Action</td>

   </tr>-->
<?php
function canChangeProduct() {
   global $connection;
   $centre_code=$_SESSION["CentreCode"];
   $sql="SELECT can_adjust_product from centre where centre_code='$centre_code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   if ($row["can_change_product"]=="1") {
      return true;
   } else {
      return false;
   }
}

if ($num_row>0) {
   while ($row=mysqli_fetch_assoc($result)) {
      $product_code=$row["product_code"];
?>
  <tr class="uk-text-small">
      <td><a href="admin/uploads/<?php echo $row["product_photo"]?>" data-uk-lightbox><img src="admin/uploads/<?php echo $row["product_photo"]?>" width="50"></a></td>
      <td><?php echo explode("((--",$row["product_code"])[0];?></td>
      <td><?php echo $row["product_name"]?></td>
      <td><?php echo $row["qty"]?></td>
      <td><?php if (!canChangeProduct()) {?>
         <?php echo $row["unit_price"]?>
          <?php } else {?>
          <input name="unit_price" id="unit_price" type="number" value="<?php echo $row["unit_price"]?>" onfocusout="changeProductPrice('<?php echo $row["product_code"]?>')">
          <?php }?>
      </td>
      <td><?php echo number_format($row["qty"]*$row["unit_price"], 2)?></td>
      <td>
         <a data-uk-tooltip="{pos:top}" title="Remove from Basket" onclick="removeFromTempBusket('<?php echo $row["bid"]?>', '<?php echo $product_code?>', '<?php echo $ssid?>')"><i class="uk-icon-hover uk-icon-medium uk-icon-close"></i></a>
         <a data-uk-tooltip="{pos:top}" title="Reduce Qty" onclick="reduceQty('<?php echo $row["bid"]?>', '<?php echo $product_code?>', '<?php echo $ssid?>')"><i class="uk-icon-hover uk-icon-medium uk-icon-minus-circle"></i></a>
      </td>
   </tr>
<?php
   }
} else {
?>
   <tr>
      <td colspan="8">No record found</td>
   </tr>
<?php
}
?>
</table>

<?php
$total=calcTotal($student_code);
if ($total>0) {
?>
<div class="uk-grid">
   <div class="uk-width-1-1">
      <div class="uk-text-right uk-text-small uk-text-bold">Total : <?php echo calcTotal($student_code);?></div>
   </div>
</div>
<br>
<?php
}
?>
