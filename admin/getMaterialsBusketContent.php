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
   
   $sql="SELECT * from student where (id)='$ssid'";
   $result=mysqli_query($connection, $sql);

   $row=mysqli_fetch_assoc($result);

   return $row["student_code"];

}



function calcTotal($student_code) {

   global $connection, $session_id;



   $sql="SELECT sum(qty*unit_price) as total from tmp_busket where session_id='$session_id' and student_code=sha1('$student_code') and (collection_type = 'link' or collection_type = 'mandarinmodules'or collection_type = 'integrated' or collection_type= 'stem-programme' or collection_type = 'stem-student-kit')";

   $result=mysqli_query($connection, $sql);

   $row=mysqli_fetch_assoc($result);

   return $row["total"];

}



$student_code=getStudentCodeFromSSID11($ssid);

//$sql="SELECT b.product_code as course_name, b.id as bid, b.qty, b.unit_price, b.collection_type from tmp_busket b where b.session_id='$session_id' and b.student_code='$student_code' and b.year = '$year' and collection_type = 'link' or collection_type = 'mandarinmodules'or collection_type = 'integrated'";
//$sql="SELECT b.product_code as course_name, b.id as bid, b.qty, b.unit_price, b.collection_type from tmp_busket b where b.session_id='$session_id' and b.year = '$year' and collection_type = 'link' or collection_type = 'mandarinmodules'or collection_type = 'integrated'";
$sql="SELECT b.product_code as course_name, b.id as bid, b.qty, b.unit_price, b.collection_type, b.collection_month, b.collection_pattern from tmp_busket b where b.session_id='$session_id' and (collection_type = 'link' or collection_type = 'mandarinmodules' or collection_type = 'integrated' or collection_type = 'stem-programme' or collection_type = 'stem-student-kit')";

//var_dump($sql);

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

               getMaterialsBusket();

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

               getMaterialsBusket();

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

      <td><?php
		if($row["course_name"]=="Link"){
			 echo "Link & Think";
		 }else{
			 echo $row["course_name"];
		 } echo $monthName;
	  ?></td>

      <td><?php 
		 if($row["collection_type"]=="link"){
			 echo "Link & Think";
		 }else if($row["collection_type"]=="mandarinmodules"){
			 echo"Mandarin Modules";
		 }else if($row["collection_type"]=="integrated"){
			 echo "Integrated Module";
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