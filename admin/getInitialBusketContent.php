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

   //$sql="SELECT * from student where sha1(id)='$ssid'";
   $sql="SELECT * from student where (id)='$ssid'";
  
   $result=mysqli_query($connection, $sql);

   $row=mysqli_fetch_assoc($result);

   return $row["student_code"];

}

function calcTotal($student_code) {

   global $connection, $session_id;

   $sql="SELECT sum(qty*unit_price) as total from tmp_busket where session_id='$session_id' and student_code=sha1('$student_code') and (collection_type = 'Deposit' or collection_type = 'registration' or collection_type = 'mobile' or collection_type = 'insurance' or collection_type = 'q-dees-level-kit' or collection_type = 'Uniform (2 sets)' or collection_type = 'Gymwear (1 set)' or collection_type = 'Q-dees Bag')";
 
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return $row["total"];

}

$student_code=getStudentCodeFromSSID11($ssid);

$sql="SELECT b.product_code as course_name, b.id as bid, b.qty, b.unit_price, b.collection_type, b.single_year from tmp_busket b where b.session_id='$session_id' and b.student_code=sha1('$student_code') and b.year = '$year' and (collection_type = 'Deposit' or collection_type = 'registration' or collection_type = 'mobile' or collection_type = 'insurance' or collection_type = 'q-dees-level-kit' or collection_type = 'Uniform (2 sets)' or collection_type = 'Gymwear (1 set)' or collection_type = 'Q-dees Bag') ";

$result=mysqli_query($connection, $sql);

$num_row=mysqli_num_rows($result);

?>
<script>

function reduceQty(bid, product_code, student_code) {

   UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function () {

      $.ajax({

         url : "admin/reduceQty.php",

         type : "POST",

         data : "product_code="+product_code+"&student_code="+student_code+"&bid="+bid,

         dataType : "text",

         beforeSend : function(http) {

         },

         success : function(response, status, http) {

            var s=response.split("|");



            if (s[0]=="1") {

               getInitialBusket();

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

               getInitialBusket();

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

      <td>Name</td>

      <td>Type</td>

      <td>Qty</td>

      <td data-uk-tooltip title="Retail price">Unit Price</td>

      <td>Amount</td>

      <td>Action</td>

   </tr>

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

?>

   <tr class="uk-text-small">

      <td><?php 
			if($row["course_name"]=="EDP" || $row["course_name"]=="QF1" || $row["course_name"]=="QF2" || $row["course_name"]=="QF3"){
				echo "Deposit";
			}else{
            if($row["course_name"] == 'Insurance') {
               echo $row["course_name"]." (".$row['single_year'].")";
            } else {
				   echo $row["course_name"];
            }
			}
	  ?></td>
			
      <td><?php 
			if($row["collection_type"]=="mobile"){
				echo "Mobile app";
			}else if($row["collection_type"]=="registration"){
				echo"Registration";
   }else if($row["collection_type"]=="insurance"){
				echo"Insurance";
			}else if($row["collection_type"]=="q-dees-level-kit"){
				echo"Q-dees Level Kit";
			}else{
				echo $row["collection_type"];
			}
	  ?></td>

      <td><?php echo $row["qty"]?></td>

      <td><?php if (!canChangeProduct()) {?>

         <?php echo $row["unit_price"]?>

          <?php } else {?>

          <input name="unit_price" id="unit_price" type="number" value="<?php echo $row["unit_price"]?>" onfocusout="changeProductPrice('<?php echo $row["course_name"]?>')">

          <?php }?>

      </td>

      <td><?php echo number_format($row["qty"]*$row["unit_price"], 2)?></td>

      <td>

         <a data-uk-tooltip="{pos:top}" title="Remove from Basket" onclick="removeFromTempBusket('<?php echo $row["bid"]?>', '<?php echo $row["course_name"]?>')"><i class="uk-icon-hover uk-icon-medium uk-icon-close"></i></a>

         <a data-uk-tooltip="{pos:top}" title="Reduce Qty" onclick="reduceQty('<?php echo $row["bid"]?>', '<?php echo $row["course_name"]?>')"><i class="uk-icon-hover uk-icon-medium uk-icon-minus-circle"></i></a>

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

