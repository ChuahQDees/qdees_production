<?php
session_start();
include_once("../mysql.php");
//echo "1|";

$user_name=$_SESSION["UserName"];

$sql="SELECT c.*, p.product_code, p.product_name, p.unit_price, p.retail_price from cart c, product p
where c.user_name='$user_name' and c.product_code=p.product_code order by c.date_created";

$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);
if ($num_row>0) {
//   echo "1|";
?>
<script>
function getNoInCart() {
   $.ajax({
      url : "admin/get_no_in_cart.php",
      type : "POST",
      data : "1=1",
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         $("#no_in_cart").replaceWith("<span id=\"no_in_cart\" class=\"uk-badge uk-badge-danger uk-badge-notification\">"+response+"</span>");
      },
      error : function(http, status, error) {
         UIkit.notification("Error:"+error);
      }
   });
}

function saveCart() {
   var theform = $("#frmSaveCart")[0];
   var formdata = new FormData(theform);

   $.ajax({
      url : "admin/save_cart.php",
      type : "POST",
      data : formdata,
      dataType : "text",
      enctype: 'multipart/form-data',
      processData: false,
      contentType: false,
      success : function(response, status, http) {
         UIkit.notify(response);
         getNoInCart();
         viewCart();
      },
      error : function(http, status, error) {
        UIkit.notify("Error:"+error);
      }
   });
}

function doDelete(id) {
   UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function () {
      $.ajax({
         url : "admin/delete_cart.php",
         type : "POST",
         data : "id="+id,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            var s=response.split("|");
            if (s[0]==1) {
               window.location.reload();
            } else {
               UIkit.notify(s[1]);
            }
         },
         error : function(http, status, error) {
            UIkit.notification("Error:"+error);
         }
      });
   })
}

$(document).ready(function () {
  $('button[id^="cart-qty-add-"]').on('click', function(e){
    e.preventDefault();
    var rowId = $(this).data('row');
    var $input = $('#cart-qty-' + rowId);

    $input.val(parseInt($input.val()) + 1);
  });

  $('button[id^="cart-qty-minus-"]').on('click', function(e){
    e.preventDefault();
    var rowId = $(this).data('row');
    var $input = $('#cart-qty-' + rowId);

    var value = parseInt($input.val()) - 1;

    if( value <= 1 ){
      value = 1;
    }

    $input.val(value);
  });

  $('#btn-cart-update').on('click', function(e){
    e.preventDefault();
    saveCart();
  });
});
</script>
<?php
    echo "<style>
    .ui-dialog-titlebar {
        background: #30d2d6;
        text-align: center;
        text-transform: uppercase;
        color: white;
        font-size: 1.5em;
     }


    .ui-dialog-title {float: inherit!important;}

     .ui-dialog {padding: 0!important;}


</style>";
   echo "<form id='frmSaveCart' name='frmSaveCart' method='post' class='nostyle' style='padding: 0px;background: transparent!important; box-shadow: none!important;'>";
   echo "<table class='uk-table uk-form q-table q-table-light' style='width: 100%;box-shadow: none'>";
   echo "   <tr class='uk-text-bold'>";
   echo "      <td class='uk-text-left'>Product Code</td>";
   echo "      <td class='uk-text-left'>Product Name</td>";
   echo "      <td class='uk-text-center'>Qty</td>";
   echo "      <td class='uk-text-center'>Price</td>";
   echo "      <td class='uk-text-center'>Total</td>";
   echo "      <td class='uk-text-left'>Action</td>";
   echo "   </tr>";
   while ($row=mysqli_fetch_assoc($result)) {
	   $product_code = $row["product_code"];
			$product_code = explode("((--",$product_code)[0];
      echo "   <tr>";
      echo "      <td class='uk-text-left'>".$product_code."</td>";
      echo "      <td class='uk-text-left'>".$row["product_name"]."</td>";
      echo "      <td class='uk-text-right'><button class='uk-button' id='cart-qty-minus-".$row["id"]."' data-row='".$row["id"]."'>-</button>";
      echo "         <input class='' min='1' type='number' name='qty[]' id='cart-qty-".$row["id"]."' value='".$row["qty"]."' style='width:60px'>";
      echo "         <button class='uk-button' id='cart-qty-add-".$row["id"]."' data-row='".$row["id"]."'>+</button>";
      echo "      </td>";
      echo "      <td class='uk-text-right'>".$row["retail_price"]."</td>";
      echo "      <td class='uk-text-right'>".number_format($row["qty"]*$row["retail_price"], 2)."</td>";
      echo "      <td class='uk-text-left'>";
      echo "         <input type='hidden' name='id[]' value='".$row["id"]."'>";
      echo "         <a onclick='doDelete(\"".$row["id"]."\")'><img src='images/Del.png' style='width:40px;'></a>";
      echo "      </td>";
      echo "   </tr>";
   }
   echo "</table>";
    echo "<button id='btn-cart-update' class='uk-button uk-button-success form_btn' style='display: block; margin-left: auto; margin-right: 10px;'>Update Cart</button>";
    echo " <hr> <br>";
    if ($_SESSION["isLogin"]==1) {
        $msg = $_GET["msg"];
        include_once("../mysql.php");
        $user_name = $_SESSION["UserName"];

        $sql = "SELECT c.*, p.product_code, p.product_name, p.unit_price, p.retail_price from cart c, product p
               where c.user_name='$user_name' and c.product_code=p.product_code order by c.date_created";

        $result = mysqli_query($connection, $sql);
        $num_row = mysqli_num_rows($result);
        $grand_total=0;
        while ($row=mysqli_fetch_assoc($result)) {
          $grand_total+=$row["retail_price"]*$row["qty"];
        }

        echo "<div style='text-align: right; width: calc(100% - 20px); margin: 0 auto;'> Total price: <b>".number_format($grand_total, 2)."</b></div>";
    }
   echo "</form>";

   echo "<div class='row'>";
    echo "  <div class='col-sm-12 col-md-2'></div>";
   echo "   <div class='col-sm-12 col-md-4'>";
   echo "      <button class='uk-width-1-1 uk-button' style='background: transparent; border: 1px solid darkgrey; color: darkgrey;padding: .3em 2em;' onclick=\"$('#dlgViewCart').dialog('close')\">Close</button>";
   echo "   </div>";
   echo "   <div class='col-sm-12 col-md-4'>";
   echo "      <a class='uk-width-1-1 uk-button uk-button-primary' href='index.php?p=checkout'>Check Out</a>";
   echo "   </div>";
    echo "  <div class='col-sm-12 col-md-2'></div>";
   echo "</div><br>";

} else {
//   echo "0|$sql";
   echo "0|Nothing found";
}
?>
