<?php

function getCartAmountByCategories($user_name){
  global $connection;
  $list = array();

  $sql="SELECT p.category, p.product_code, (p.retail_price * c.qty) as sub_total from cart c, product p
  where c.user_name='$user_name' and c.product_code=p.product_code order by p.category";

  $result=mysqli_query($connection, $sql);

  if ($result) {
    while ($row=mysqli_fetch_assoc($result)) {
      if( isset($list[$row['category']]) ){
        $list[$row['category']] = $list[$row['category']] + $row['sub_total'];
      }else{
        $list[$row['category']] = $row['sub_total'];
      }
    }
  }

  return $list;
}
?>

<a href="/index.php?p=purchasing" onclick="window.history.go(-1); return false;">
   <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>
<span>
    <span class="page_title" ><img src="/images/title_Icons/View Cart.png">Check Out</span>
</span>

<?php
if ($_SESSION["isLogin"]==1) {
   $msg=$_GET["msg"];
   include_once("../mysql.php");
   $user_name=$_SESSION["UserName"];

   $sql="SELECT c.*, p.product_code, p.product_name, p.retail_price from cart c, product p
   where c.user_name='$user_name' and c.product_code=p.product_code order by c.date_created";

   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      echo "<div class='uk-margin-right' style='position: relative;'>";
      echo "<div class='uk-width-1-1 myheader'>";
      echo "   <h2 class='uk-text-center myheader-text-color myheader-text-style'>Cart Content</h2>";
      echo "</div>";

      echo "<form id='frmSaveCart' class='uk-form' name='frmSaveCart' method='post'>";
      echo "<table class='uk-table uk-form'>";
      echo "   <tr class='uk-text-bold uk-text-medium'>";
      echo "      <td class='uk-text-left'>Product Code</td>";
      echo "      <td class='uk-text-left'>Product Name</td>";
      echo "      <td class='uk-text-right'>Qty</td>";
      echo "      <td class='uk-text-right'>Price</td>";
      echo "      <td class='uk-text-right'>Total</td>";
      echo "   </tr>";

      while ($row=mysqli_fetch_assoc($result)) {
		  $product_code = $row["product_code"];
			$product_code = explode("((--",$product_code)[0];
         echo "   <tr class='uk-text-medium product-row-color'>";
         echo "      <td class='uk-text-left'>".$product_code."</td>";
         echo "      <td class='uk-text-left'>".$row["product_name"]."</td>";
         echo "      <td class='uk-text-right'>";
         echo "         ".$row["qty"];
         echo "         <input type='hidden' name='id' value='".$row["id"]."'>";
         echo "      </td>";
         echo "      <td class='uk-text-right'>".$row["retail_price"]."</td>";
         echo "      <td class='uk-text-right'>".number_format($row["retail_price"]*$row["qty"], 2)."</td>";
         echo "   </tr>";
         $grand_total=$grand_total+($row["retail_price"]*$row["qty"]);
      }
      echo "   <tr>";
      echo "      <td colspan='4' class='uk-text-right uk-text-medium uk-text-bold'>Grand Total</td>";
      echo "      <td class='uk-text-right uk-text-medium'>".number_format($grand_total, 2)."</td>";
      echo "   </tr>";

      echo "</table>";
?>


  <table class="payment-details-table">
    <tr>
      <th colspan="2" >Payment Details </th>
    </tr>
    <?php
      $cart_amount_by_categories = getCartAmountByCategories($user_name);

      foreach($cart_amount_by_categories as $key => $tmp_amount){
    ?>
      <tr>
        <td><li><?php echo $key; ?></li></td>
        <td class="text-right"><?php echo number_format($tmp_amount, 2); ?></td>
      </tr>
    <?php } ?>
  </table>


<div style="display:flex; padding:20px; justify-content:space-around; width:70%">

    <?php 
      $selectDate = mysqli_query($connection,"SELECT `select_date` FROM `slot_collection` WHERE `deleted` = 0 AND `select_date` > CURRENT_DATE()  GROUP BY `select_date`");

        $date_array = array();

        while($row=mysqli_fetch_array($selectDate))
        {
          $checkSlot = mysqli_fetch_array(mysqli_query($connection,"SELECT COUNT(`slot_collection_child`.`id`) AS count_slot FROM `slot_collection_child` LEFT JOIN `slot_collection` ON `slot_collection`.`id` = `slot_collection_child`.`slot_master_id` WHERE `slot_collection`.`select_date` = '".$row['select_date']."' AND `slot_collection_child`.`is_booked` = 0"));

          if($checkSlot['count_slot'] < 1)
          {
              $date_array[] = date('j-n-Y',strtotime($row['select_date']));
          }
        }
          $data_date =  json_encode($date_array);
      ?>

  <div>
    <h4>Select Shipping:</h4>
    <select name="courier" id="courier" class="uk-form uk-form-medium box-style">
    <option value="">Select a shipping method</option>
    <?php
    $sql="SELECT * from codes where module='COURIER' order by code";
    $result1=mysqli_query($connection, $sql);
    while ($row1=mysqli_fetch_assoc($result1)) {
    ?>
       <option value="<?php echo $row1['code']?>"><?php echo $row1["code"]?></option>
    <?php
    }
    ?>
    </select>
  </div>
  <div>
    <h4>Preferred Collection Date:</h4>
    <!-- <input type="text" data-uk-datepicker="{i18n: {weekdays:['Sun','Sat']}, weekstart: 1, minDate: 0, maxDate: 5, format: 'DD/MM/YYYY'}" class="box-style"> -->
    <input type="text" id="preferred_collection_date" name="preferred_collection_date" class="box-style" readonly=true onchange="getPrefferedTime()"><br>
    <span id="error_preferred_collection_date" style="color:red; display:none;">Select Preferred Collection Date</span>
  </div>
</div>
<div id="place_slot" style="display:flex; padding:20px; justify-content:space-around; width:70%;">
  <div>
    <h4>Preferred Time:</h4>
    <select name="preferredtime" id="preferredtime" class="uk-form uk-form-medium box-style" onchange="getPrefferedSlot()">
    <option value="">Select Preferred Time</option>
    <!-- <option class="self_arranged_transporter" value="11am">11am</option>
    <option class="self_arranged_transporter" value="2pm">2pm</option>
    <option class="self_arranged_transporter" value="3pm">3pm</option>
    <option class="self_collection" value="10:30am">10:30am</option>
    <option class="self_collection" value="12pm">12pm</option>
    <option class="self_collection" value="2:30pm">2:30pm</option>
    <option class="self_collection" value="4pm">4pm</option> -->
    <?php
    $centre_code = $_SESSION["CentreCode"];
    $sql="SELECT * from slot_collection where centre_code='$centre_code' and deleted=0 and select_date='CURDATE()' order by select_time";
    echo $sql;
    $result=mysqli_query($connection, $sql);
    while ($row=mysqli_fetch_assoc($result)) {
    ?>
                    <option value="<?php echo $row['select_time']?>"><?php echo $row["select_time"]?></option>
                    <?php
    }
    ?>
    </select><br>
    <span id="error_preferredtime" style="color:red; display:none;">Select Preferred Time</span>
  </div>
  <div>
    <h4>Slot:</h4>
    <select name="slot" id="slot" class="uk-form uk-form-medium box-style">
    <option value="">Select Slot</option>
    <!-- <option value="Slot 1">Slot 1</option>
    <option value="Slot 2">Slot 2</option> -->
    <?php
    $centre_code = $_SESSION["CentreCode"];
    $sql="SELECT * from slot_collection where centre_code='$centre_code' and deleted=0 and select_date='CURDATE()' order by slot";
    $result=mysqli_query($connection, $sql);
    while ($row=mysqli_fetch_assoc($result)) {
    ?>
                    <option value="<?php echo $row['slot']?>"><?php echo $row["slot"]?></option>
                    <?php
    }
    ?>
    </select><br>
    <span id="error_slot" style="color:red; display:none;">Select Slot</span>
  </div>
</div>
<div style="display:flex; padding:20px; justify-content:space-around; width:70%">
  
  <div>
    <h4>Name:</h4>
    <input id="name" type="text" class="box-style"><br>
	<span id="error_name" style="color:red; display:none;">Please Input Name</span>
  </div>
  <div>
    <h4>Remarks/ Transport Details:</h4>
    <textarea name="remarks" id="remarks" placeholder="Transporter Name & Details" style="max-width: 300px; height:100px;" class="box-style"></textarea>
  </div>
</div>
<div style="display:flex; padding:20px; justify-content:space-around; width:70%">
<div style="color:red">
<strong>NOTE:</strong><br>
<span>1. Please note that Saturday, Sunday and public holidays are not available for self-collection.
</span><br>
<span>2. Gymwear and uniform will require at least <b>MINIMUM</b> 1 month for collection.</span>
</div>
</div>
<?php
    echo "<br>";
    echo "<div class='uk-text-center'>";
    echo "   <a id='btnOrder' onclick='placeOrder();' class='uk-button uk-button-primary form_btn'>Place Order</a>";
     echo "</div>";
    echo "</form>";


      echo "</div>";
   } else {
      if ($msg=="") {
         echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Nothing in Cart</div></div>";
      }
   }
} else {
   echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorized access denied</div></div>";
}
?>

<script>

  var unavailableDates = <?php echo $data_date ?>;

  function unavailable(date) {
      dmy = date.getDate() + "-" + (date.getMonth() + 1) + "-" + date.getFullYear();
      if ($.inArray(dmy, unavailableDates) == -1 && date.getDay() !=6 && date.getDay()!=0) {
          return [true, ""];
      } else {
          return [false, "", "Unavailable"];
      }
  }

  $( function() {

      var minDate = '+'+getNext5WorkingDays()+'D'
      $( "#preferred_collection_date" ).datepicker({ 
        dateFormat: 'dd/mm/yy',
        minDate: '0',
        
/*         beforeShowDay: function(date) {
          var show = true;
          if(date.getDay()==6||date.getDay()==0) show=false
          return [show];
        },  */
        minDate: minDate,
        beforeShowDay: unavailable
      });
      $("#place_slot").hide()
      $("#courier").change(function(){
      if($(this).val()=="Self-arranged Transporter"){
        $("#place_slot").show()
        // $(".self_arranged_transporter").show()
        // $(".self_collection").hide()
      }else if($(this).val()=="Self-collection at HQ "){
        $("#place_slot").show()
        // $(".self_arranged_transporter").hide()
        // $(".self_collection").show()
        $("#slot").show()
      }else(
        $("#place_slot").hide()
      )
      })
  } );
  function getNext5WorkingDays(){
    var d = new Date();
    var day = d.getDay();
    //if(day>=0 && day<=3) return 7;
	//CHS: Changed to 9 days instead of 7 upon request
	if(day>=0 && day<=3) return 9;
    else if(day!=6) return 8;
    else return 9;
}

function placeOrder() {
   var courier=$("#courier").val();
   var preferred_collection_date_array=$("#preferred_collection_date").val().split("/");
   var preferred_collection_date=preferred_collection_date_array[2]+"/"+preferred_collection_date_array[1]+"/"+preferred_collection_date_array[0];
   var preferredtime=$("#preferredtime").val();
   var slot=$("#slot").val();
   var remarks=$("#remarks").val();
   var name=$("#name").val();
   var rtn = true;
   
   if (courier=="Courier by HQ") {
    rtn=true;
      $("#courier_service").val(courier);
      $("#preferred_collection_date_hidden").val(preferred_collection_date);
      $("#preferredtime_hidden").val(preferredtime);
      $("#slot_hidden").val(slot);
      $("#remarks_hidden").val(remarks);
      $("#name_hidden").val(name);
      //$("#frmPlaceOrder").submit();
	  if ($("#name").val()=="") {
		   UIkit.notify("Please Input Name");
			return false;
		}else{
		   $("#frmPlaceOrder").submit();
		}
  }
   if (courier=="Self-arranged Transporter" || courier=="Self-collection at HQ ") {
    if ($("#preferred_collection_date").val()==""){
      $("#error_preferred_collection_date").show();
     }else{
      rtn=false;
      $("#error_preferred_collection_date").hide();
     }
     if ($("#preferredtime").val()=="" || $("#preferredtime").val()==null){
      $("#error_preferredtime").show();
     }else{
      rtn=false;
      $("#error_preferredtime").hide();
     }
     //console.log($("#slot").val());
     if ($("#slot").val()=="" || $("#slot").val()==null){
      $("#error_slot").show();
     }else{
      rtn=false;
      $("#error_slot").hide();
     }
	 if ($("#name").val()==""){
      $("#error_name").show();
     }else{
      rtn=false;
      $("#error_name").hide();
     }
   
   }else {
      UIkit.notify("Please provide a shipping method");
   }

    if(rtn || courier != ""){
      $("#courier_service").val(courier);
      $("#preferred_collection_date_hidden").val(preferred_collection_date);
      $("#preferredtime_hidden").val(preferredtime);
      $("#slot_hidden").val(slot);
      $("#remarks_hidden").val(remarks);
      $("#name_hidden").val(name);
		if ($("#slot").val()=="" || $("#slot").val()==null || $("#name").val()=="") {
			return false;
		}else{
		   $("#frmPlaceOrder").submit();
		}
	  
   }
	 
   
}
function getPrefferedTime() {
    //var preferred_collection_date = $("#preferred_collection_date").val();
    var preferred_collection_date_array=$("#preferred_collection_date").val().split("/");
   var preferred_collection_date=preferred_collection_date_array[2]+"/"+preferred_collection_date_array[1]+"/"+preferred_collection_date_array[0];
    $.ajax({
        url: "admin/GetPrefferedTime.php",
        type: "POST",
        data: "preferred_collection_date=" + preferred_collection_date,
        dataType: "json",
        beforeSend: function(http) {},
        success: function(response, status, http) {
          //var data = JSON.parse(response);
          // console.log(response);
          // $.each(response, function (index, value) {
          //   console.log(response[index].select_time);
          // })
          $('#preferredtime').empty();
          $('#preferredtime').append('<option selected="true" disabled>Select Preferred Time</option>');

         // $('#slot').empty();
         // $('#slot').append('<option selected="true" disabled>Select Slot</option>');

          $.each(response, function (key, entry) {
            $('#preferredtime').append($('<option></option>').attr('value', entry.select_time).text(entry.select_time));
            //$('#slot').append($('<option></option>').attr('value', entry.slot).text(entry.slot));
            // for (i = 1; i <= entry.slot; i++) {
            //   $('#slot').append($('<option></option>').attr('value', i).text(i));
            // }
          })
            //$("#sctAdjustment").html(response);
        },
        error: function(http, status, error) {
            UIkit.notify("Error:" + error);
        }
    });
}
function getPrefferedSlot() {
    //var preferred_collection_date = $("#preferred_collection_date").val();
    var preferred_collection_date_array=$("#preferred_collection_date").val().split("/");
   var preferred_collection_date=preferred_collection_date_array[2]+"/"+preferred_collection_date_array[1]+"/"+preferred_collection_date_array[0];
    var preferredtime = $("#preferredtime").val();

    $.ajax({
        url: "admin/GetPrefferedSlot.php",
        type: "POST",
        data: "preferred_collection_date=" + preferred_collection_date +"&preferredtime=" + preferredtime,
        dataType: "json",
        beforeSend: function(http) {},
        success: function(response, status, http) {
          //var data = JSON.parse(response);
          // console.log(response);
          // $.each(response, function (index, value) {
          //   console.log(response[index].select_time);
          // })
         // $('#preferredtime').empty();
         // $('#preferredtime').append('<option selected="true" disabled>Select Preferred Time</option>');

          $('#slot').empty();
          $('#slot').append('<option selected="true" disabled>Select Slot</option>');

          $.each(response, function (key, entry) {
            //$('#preferredtime').append($('<option></option>').attr('value', entry.select_time).text(entry.select_time));
            $('#slot').append($('<option></option>').attr('value', entry.slot_child).text(entry.slot_child));
            // for (i = 1; i <= entry.slot; i++) {
            //   $('#slot').append($('<option></option>').attr('value', i).text(i));
            // }
          })
            //$("#sctAdjustment").html(response);
        },
        error: function(http, status, error) {
            UIkit.notify("Error:" + error);
        }
    });
}
</script>
<form id="frmPlaceOrder" name="frmPlaceOrder" method="post" action="admin/place_order.php">
   <input type="hidden" name="courier_service" id="courier_service" value="">
   <input type="hidden" name="preferred_collection_date" id="preferred_collection_date_hidden" value="">
   <input type="hidden" name="preferredtime" id="preferredtime_hidden" value="">
   <input type="hidden" name="slot" id="slot_hidden" value="">
   <input type="hidden" name="remarks" id="remarks_hidden" value="">
   <input type="hidden" name="name" id="name_hidden" value="">
   
</form>

<?php
if ($msg!="") {
   echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>$msg</div></div>";
}
?>
<style>
.ui-dialog{
  position: fixed!important;
  top: 10%!important;
}
</style>