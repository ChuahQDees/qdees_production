<?php
session_start();
$session_id=session_id();
include_once("../mysql.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);
}

function getStudentCodeFromSSID11($ssid) {
   global $connection;

   $sql="SELECT * from student where sha1(id)='$ssid'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["student_code"];
}

function calcAOTotal($student_code) {
   global $connection, $session_id;

   $sql="SELECT sum(b.qty*b.unit_price) as total from tmp_busket b, addon_product p where session_id='$session_id' and  student_code=sha1('$student_code') and b.product_code=p.product_code";
 
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["total"];
}

   $student_code=getStudentCodeFromSSID11($ssid);

   $centre_code=$_SESSION["CentreCode"];
   $sql="SELECT p.*, b.id as bid, b.qty, b.unit_price, b.collection_month, b.collection_pattern from tmp_busket b, addon_product p where p.centre_code='$centre_code' and b.session_id='$session_id' and b.student_code=sha1('$student_code') and b.product_code=p.product_code";

   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);
?>

<script>
function reduceAOQty(bid, product_code, student_code) {
   UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function () {
      $.ajax({
         url : "admin/reduceAOQty.php",
         type : "POST",
         data : "product_code="+product_code+"&student_code="+student_code+"&bid="+bid,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            var s=response.split("|");

            if (s[0]=="1") {
               getAOTempBusket();
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

// function AddAllRelatedProducts(student_code) {
//    UIkit.modal.confirm("<h2>Are you sure to proceed?</h2>", function () {
//       $.ajax({
//          url : "admin/AddAllRelatedProducts.php",
//          type : "POST",
//          data : "student_code="+student_code,
//          dataType : "text",
//          beforeSend : function(http) {
//          },
//          success : function(response, status, http) {
//             getAOTempBusket();
//             if (response!="") {
//                UIkit.notify(response);
//             }
//          },
//          error : function(http, status, error) {
//             UIkit.notify("Error:"+error);
//          }
//       });
//    });
// }

</script>
<!-- <a onclick="AddAllRelatedProducts('<?php echo $student_code?>')" class="uk-button uk-button-small">Add all programme's related products</a> -->

<script>
function changeAOProductPrice(product_code) {
   var unit_price=$("#unit_price").val();
   UIkit.modal.confirm("<h2>Are you sure to proceed?</h2>", function () {
      $.ajax({
         url : "admin/changeAOProductPrice.php",
         type : "POST",
         data : "product_code="+product_code+"&unit_price="+unit_price,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            var s=response.split("|");
            if (s[0]=="1") {
               getAOTempBusket();
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
      <!-- <td>Photo</td> -->
      <td>Code</td>
      <td>Name</td>
      <td>Qty</td>
      <td data-uk-tooltip title="Retail price">Unit Price</td>
      <td>Amount</td>
      <td>Action</td>
   </tr>
<?php
function canAOChangeProduct() {
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

      <?php 
         $monthName = '';

         if($row['collection_pattern'] == 'Monthly') {
            $monthNum  = $row["collection_month"];
            $dateObj   = DateTime::createFromFormat('!m', $monthNum);
            $monthName = ' ('.$dateObj->format('F').')';
         } else if($row['collection_pattern'] == 'Termly') {
            $monthNum  = $row["collection_month"];
            $monthName = ' (Term '.$monthNum.')';
         }
      ?>

      <!-- <td><a href="admin/uploads/<?php echo $row["product_photo"]?>" data-uk-lightbox><img src="admin/uploads/<?php echo $row["product_photo"]?>" width="50"></a></td> -->
      <td><?php echo $row["product_code"].$monthName; ?></td>
      <td><?php echo $row["product_name"]; ?></td>
      <td><?php echo $row["qty"]; ?></td>
      <td><?php if (!canAOChangeProduct()) {?>
         <?php echo $row["unit_price"]?>
          <?php } else {?>
          <input name="unit_price" id="unit_price" type="number" value="<?php echo $row["unit_price"]?>" onfocusout="changeAOProductPrice('<?php echo $row["product_code"]?>')">
          <?php }?>
      </td>
      <td><?php echo number_format($row["qty"]*$row["unit_price"], 2)?></td>
      <td>
         <a data-uk-tooltip="{pos:top}" title="Remove from Basket" onclick="removeAOFromTempBusket('<?php echo $row["bid"]?>', '<?php echo $product_code?>', '<?php echo $student_code?>')"><i class="uk-icon-hover uk-icon-medium uk-icon-close"></i></a>
         <a data-uk-tooltip="{pos:top}" title="Reduce Qty" onclick="reduceAOQty('<?php echo $row["bid"]?>', '<?php echo $product_code?>', '<?php echo $student_code?>')"><i class="uk-icon-hover uk-icon-medium uk-icon-minus-circle"></i></a>
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
$total=calcAOTotal($student_code);
if ($total>0) {
?>
<div class="uk-grid">
   <div class="uk-width-1-1">
      <div class="uk-text-right uk-text-small uk-text-bold">Total : <?php echo calcAOTotal($student_code);?></div>
   </div>
</div>
<br>
<?php
}
?>