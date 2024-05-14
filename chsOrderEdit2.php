<?php
//Simple admin tool to edit order because CENTERS AAAAAAAAAAA

/*
1) Get Parameter from search bar (Should be Order No)
2) Load Order Details from the table
3) There's options to either: 
    A) Click on the table and edit the amount
    B) Undo Cancellation, or set item back to Pending
*/

include_once("mysql.php");

function getStatus($order_no)
{
  global $connection;
  $cancelCounter = "0";

  $sql = "SELECT * from `order` where order_no='$order_no' or id='$order_no'";

  $result = mysqli_query($connection, $sql);
  //if ($result) {
  while ($rowxx = mysqli_fetch_assoc($result)) {
    $row = mysqli_fetch_assoc($result);

    if ($row["cancelled_by"] != "") {
      //return "Cancelled";
    } else {
      $cancelCounter = "1"; //don't mark as cancelled
      if ($row["delivered_to_logistic_by"] != "") {
        if ($row["finance_payment_paid_by"] != "") {
          return "Delivered";
        } else {
          return "Delivered";
        }
      } else {
        if ($row["packed_by"] != "") {
          if ($row["finance_payment_paid_by"] != "") {
            return "Ready for Collection";
          } else {
            return "Ready for Collection";
          }
        } else {
          /*if ($row["assigned_to_by"]!="") {
                return "Assigned";
             }
             else{*/
          if ($row["finance_approved_by"] != "") {
            if ($row["finance_payment_paid_by"] != "") {
              return "Finance Approved (Paid)";
            } else {
              return "Finance Approved";
            }
          } else {
            if ($row["logistic_approved_by"] != "" && $row["acknowledged_by"] != "") {
              return "Packing";
            } else {
              if ($row["acknowledged_by"] != "" && $row["acknowledged_on"] != "") {
                return "Acknowledged";
              } else if (!empty($rowxx["cancelled_by"])) {
                return "Cancelled";
              } else {
                return "Pending";
              }
            }
          }
          //}
        }
      }
    }

    if ($cancelCounter == "0") {
      return "Cancelled";
    }
  }
}
?>

<head>
  <title>Stupid Order Edit Thing</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<style>
  .form-container {
    margin: 15% auto;
    display: block;
    width: 900px;
    /*text-align: center;*/
  }

  body {
    margin: 0 auto;
    font-family: Arial;
    background-color: #041222;
    color: white;
  }

  .form-container input {
    margin-bottom: 10px;
  }

  .form-container2 {
    margin: 15% auto;
    display: block;
    width: 900px;
    /*text-align: center;*/
  }
</style>

<script>
    function autoCalc(){
        var curUnitPrice = document.getElementById("currentUnitPrice").value;
        var curQty = document.getElementById("currentQty").value;
//        var curTotal = document.getElementById("currentTotal").value;

        var finalTotal = Number(curUnitPrice).toFixed(2) * Number(curQty).toFixed(2);
        
        document.getElementById("currentUnitPrice").value = Number(curUnitPrice).toFixed(2);
        document.getElementById("currentQty").value = Number(curQty).toFixed(2);
        document.getElementById("currentTotal").value = Number(finalTotal).toFixed(2);
    }

  // this is the id of the form
  $(document).ready(function() {
    $("#btnSubmit").click(function() {

      var orderID = $("#orderID").val();
      var orderNo = $("#orderNo").val();
      //var curProductCode = $("#curProductCode").val();
      var curProductCode = $('#curProductCode :selected').val();


      var currentUnitPrice = $("#currentUnitPrice").val();
      var currentQty = $("#currentQty").val();
      var currentTotal = $("#currentTotal").val();

      $.ajax({
        url: "a_chsOrderEdit.php",
        type: "POST",
        data: "orderID=" + orderID + "&orderNo="+orderNo+"&curProductCode="+curProductCode+"&currentUnitPrice="+currentUnitPrice+"&currentQty="+currentQty+"&currentTotal="+currentTotal+"&modeType=updateOrder",
        dataType: "text",
        beforeSend: function(http) {},
        success: function(response, status, http) {
          alert(response);
          //alert("Order updated!");
          window.location.href = "/chsOrderEdit.php?orderID=" + orderNo;
        },
        error: function(http, status, error) {
          alert("Error");
        }
      });

    });

  });
</script>

<?php
$orderID = "";
$orderNo = "";
if (isset($_GET['orderID'])) {
  $orderID = $_GET['orderID'];
}

if (isset($_GET['orderNo'])) {
    $orderNo = $_GET['orderNo'];
  }
?>

<form id="idForm" method="post">
  <div class="form-container">
    <?php
      $sql = "SELECT * from `order` where id='$orderID'";
      $result = mysqli_query($connection, $sql);
      $num_row = mysqli_num_rows($result);

      if ($row = mysqli_fetch_assoc($result)) {
    ?>
    <section>
      <h1>ID No: <?php echo $orderID ?></h1> <input id="orderID" name="orderID" type="text" value="<?php echo $orderID ?>" hidden>
      <h1>Order No: <?php echo $orderNo ?></h1> <input id="orderNo" name="orderNo" type="text" value="<?php echo $orderNo ?>" hidden>
      <h1>User Name: <?php echo $row["user_name"] ?>	</h1>
      <h1>Current Product Code: 
      <select id="curProductCode" name="curProductCode" style="color:black">
          <option value="<?php echo $row["product_code"] ?>" selected><?php echo $row["product_code"] ?></option>
          <option value="MY-STEM-MOD-BUNDLE 01((--MSSB_112">STEM - Module 01 Bundle (Junior Year 1)</option>
          <option value="MY-STEM-MOD-BUNDLE 02((--MSSB_112">STEM - Module 02 Bundle (Junior Year 1)</option>
          <option value="MY-STEM-MOD-BUNDLE 03((--MSSB_112">STEM - Module 03 Bundle (Junior Year 1)</option>
          <option value="MY-STEM-MOD-BUNDLE 04((--MSSB_112">STEM - Module 04 Bundle (Junior Year 1)</option>
          <option value="MY-STEM-MOD-BUNDLE 05((--MSSB_112">STEM - Module 05 Bundle (Junior Year 2)</option>
          <option value="MY-STEM-MOD-BUNDLE 06((--MSSB_112">STEM - Module 06 Bundle (Junior Year 2)</option>
          <option value="MY-STEM-MOD-BUNDLE 07((--MSSB_112">STEM - Module 07 Bundle (Junior Year 2)</option>
          <option value="MY-STEM-MOD-BUNDLE 08((--MSSB_112">STEM - Module 08 Bundle (Junior Year 2)</option>
          <option value="MY-STEM-MOD-BUNDLE 09((--MSSB_112">STEM - Module 09 Bundle (Senior Year 1)</option>
          <option value="MY-STEM-MOD-BUNDLE 10((--MSSB_112">STEM - Module 10 Bundle (Senior Year 1)</option>
          <option value="MY-STEM-MOD-BUNDLE 11((--MSSB_112">STEM - Module 11 Bundle (Senior Year 1)</option>
          <option value="MY-STEM-MOD-BUNDLE 12((--MSSB_112">STEM - Module 12 Bundle (Senior Year 1)</option>
          <option value="MY-STEM-MOD-BUNDLE 13((--MSSB_112">STEM - Module 13 Bundle (Senior Year 2)</option>
          <option value="MY-STEM-MOD-BUNDLE 14((--MSSB_112">STEM - Module 14 Bundle (Senior Year 2)</option>
          <option value="MY-STEM-MOD-BUNDLE 15((--MSSB_112">STEM - Module 15 Bundle (Senior Year 2)</option>
          <option value="MY-STEM-MOD-BUNDLE 16((--MSSB_112">STEM - Module 16 Bundle (Senior Year 2)</option>
      </select>
      </h1>
      <h1>Current Unit Price: <input style="color:black" onchange="autoCalc()"class="form-control form-control-lg" type="number" id="currentUnitPrice" name="currentUnitPrice" value="<?php echo $row["unit_price"] ?>"></h1>
      <h1>Current Qty: <input style="color:black" onchange="autoCalc()" class="form-control form-control-lg" type="number" id="currentQty" name="currentQty" value="<?php echo $row["qty"] ?>"></h1>
      <h1>Current Total:  <input style="color:black" class="form-control form-control-lg" type="number" id="currentTotal" name="currentTotal" readonly value="<?php echo $row["total"] ?>"></h1>

        <a id="btnSubmit" class="btn btn-block btn-success btn-lg font-weight-medium">Save</a>
        <p id="demo" style="color:yellow"></p>
    </section>
    <?php } ?>
  </div>
</form>