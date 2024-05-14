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
    text-align: center;
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
    text-align: center;
  }
</style>

<script>
  // this is the id of the form
  $(document).ready(function() {
    $("#btnSubmit").click(function() {

      var orderID = $("#orderID").val();

      $.ajax({
        url: "a_chsOrderEdit.php",
        type: "POST",
        data: "orderID=" + orderID + "&modeType=searchID",
        dataType: "text",
        beforeSend: function(http) {},
        success: function(response, status, http) {

          var s = response.split("|");
          if (s[0] == "1") {
            //document.getElementById("demo").innerHTML = "Found a record! Refresh the page with the parameter.";
            window.location.href = "/chsOrderEdit.php?orderID=" + s[1];
          } else {
            document.getElementById("demo").innerHTML = s;
          }
        },
        error: function(http, status, error) {
          document.getElementById("demo").innerHTML = "Error";
        }
      });

    });

    //Revert Transaction to 'Pending'
    $("#btnPending").click(function() {

      if (confirm("Reverting to 'Pending' status. Confirm?") == true) {

        var orderIDF = document.getElementById("orderIDField");
        var orderID = orderIDF.value;

        $.ajax({
          url: "a_chsOrderEdit.php",
          type: "POST",
          data: "orderID=" + orderID + "&modeType=revertPending",
          dataType: "text",
          beforeSend: function(http) {},
          success: function(response, status, http) {
            alert("Order updated!");
            window.location.reload();

          },
          error: function(http, status, error) {
            alert("Error");
          }
        });

      }

    });

    $("#normalizeSTEM").click(function() {

      //var checkArray = document.getElementById("studentIDArray").value;
      //if (checkArray != ""){
        if (confirm("Adding Student Kit/Modules in. Confirm?") == true) {
        
          var orderIDF = document.getElementById("orderIDField");
          var orderID = orderIDF.value;

          $.ajax({
            url: "a_chsOrderEdit.php",
            type: "POST",
            data: "orderID=" + orderID + "&modeType=normalizeSTEM",
            dataType: "text",
            beforeSend: function(http) {},
            success: function(response, status, http) {
              console.log(response);
              alert("Order updated!");
              window.location.reload();

            },
            error: function(http, status, error) {
              alert("Error");
            }
          });
        }
     // }

    });
  });
</script>

<?php
$orderID = "";
if (isset($_GET['orderID'])) {
  $orderID = $_GET['orderID'];
}
?>

<form id="idForm" method="post">
  <div class="form-container" style="width:300px;">
    <section>
      <input class="form-control" id="orderID" name="orderID" type="text" placeholder="orderID">
      <a id="btnSubmit" class="btn btn-block btn-success btn-lg font-weight-medium">Search</a>
      <p id="demo" style="color:yellow"></p>
    </section>
  </div>

  <script>
    //Simulate pressing button on click for enter button (OrderID)
    var input = document.getElementById("orderID");
    input.addEventListener("keypress", function(event) {
      if (event.key === "Enter") {
        event.preventDefault();
        document.getElementById("btnSubmit").click();
      }
    });
  </script>

  <?php
  if ($orderID != "") {
  ?>
    <div class="form-container" style="width:900px;">
      <center>
        <h1>Order ID - <?php echo $orderID ?></h1>
        <br />
        <!--<h1>Current Status - <?php echo getStatus($orderID) ?></h1>-->
      </center>

      <input type="text" id="orderIDField" name="orderIDField" value="<?php echo $orderID ?>" hidden>
      <input type="text" id="recordIDArray" name="recordIDArray" hidden> <!-- For future multi-select to change selected field to STEM (Get the list which was clicked by the user on screen, then change to STEM package accordingly) -->

      <?php $statusBold = getStatus($orderID) ?>
      <!-- Image Stuff -->
      <h3>
        <?php if ($statusBold == "Pending"){ echo "<b style='color:yellow'>Pending</b>"; }else{ echo "Pending"; } ?> || 
        <?php if ($statusBold == "Acknowledged"){ echo "<b style='color:yellow'>Acknowledged</b>"; }else{ echo "Acknowledged"; } ?> || 
        <?php if ($statusBold == "Packing"){ echo "<b style='color:yellow'>Packing</b>"; }else{ echo "Packing"; } ?> || 
        <?php if ($statusBold == "Ready for Collection"){ echo "<b style='color:yellow'>Ready for Collection</b>"; }else{ echo "Ready for Collection"; } ?> || 
        <?php if ($statusBold == "Delivered"){ echo "<b style='color:yellow'>Delivered</b>"; }else{ echo "Delivered"; } ?> || 
        <?php if ($statusBold == "Cancelled"){ echo "<b style='color:yellow'>Cancelled</b>"; }else{ echo "Cancelled"; } ?>
  </h3>

    <!-- End Image Stuff -->

      <a id="btnPending" class="btn btn-primary btn-lg">Revert to 'Pending'</a>
      <!--<a id="btnAcknowledged" class="btn btn-danger btn-lg">Revert to 'Acknowleged'</a>-->

      <!--<button type="button" class="btn btn-danger btn-lg">Revert to 'Acknowleged'</button>-->
      <?php
      $sOrderNo = sha1($orderID);
      ?>
      <!--
        <button type="button" href="admin/generate_so.php?order_no=<?php echo $sOrderNo ?>" class="btn btn-warning btn-lg">View Sales Order</button>
        -->
      <a href="admin/generate_so.php?order_no=<?php echo $sOrderNo ?>" class="btn btn-warning btn-lg" target="_blank"><i class="fa fa-bullhorn"></i> Generate SO</a>
      <a id="normalizeSTEM" class="btn btn-danger btn-lg">Normalize STEM</a>
      <p></p>
      <input type="text" id="studentIDArray" name="studentIDArray" hidden>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">ID</th>
            <th scope="col">Product Name</th>
            <th scope="col">Qty</th>
            <th scope="col">U.Price</th>
            <th scope="col">Total</th>
            <th scope="col">Ackn. By</th>
            <th scope="col">Logistic Appr.</th>
            <th scope="col">Pay Doc</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <?php
        //$sql="SELECT * from `order` where order_no='$orderID'"; 
        $sql = "SELECT o.*, p.product_name from `order` o, product p where order_no='$orderID' and p.product_code=o.product_code and (o.cancelled_by = '' OR o.cancelled_by IS NULL) order by id";
        $result = mysqli_query($connection, $sql);
        $num_row = mysqli_num_rows($result);

        ?>
        <tbody>
          <?php
          $counter = 1;
          //echo 'PHP version: ' . phpversion();


          while ($row = mysqli_fetch_assoc($result)) {

            $parts = explode("-", $row["product_code"]);
            $part2 = $parts[3];
          ?>
            <tr class="checkBoxChecked" id="stu_id<?php echo $row['id']?>" <?php if (strpos($part2, 'BUNDLE') !== false) { ?>style="background-color:#00ff102b" <?php } else if (array_search('STEM', $parts) !== false) { ?>style="background-color:#ff00002b" <?php } ?>>
              <th scope="row"><?php echo $counter ?></th>
              <td>   <input type="checkbox" 
                    specId="<?php echo $row['id']?>" 
                    class="Aps_checkbox"
                    id="stu_id<?php echo $row['id']?>" 
                    name="stu_id<?php echo $row['id']?>"
                    value="<?php echo $row['id']?>" 
                    aria-labelledBy="stu_id<?php echo $row['id']?>" 
                    
                   
                    /><?php echo $row["product_code"] ?></td>
              <td><?php echo $row["product_name"] ?></td>
              <td><?php echo number_format($row["qty"], 0) ?></td>
              <td><?php echo number_format($row["unit_price"], 2) ?></td>
              <td><?php echo number_format($row["qty"] * $row["unit_price"], 2) ?></td>
              <td><?php echo $row["acknowledged_by"] ?></td>
              <td><?php echo $row["logistic_approved_by"] ?></td>
              <td><?php if($row["payment_document"] != ""){ echo "Yes"; } ?></td>
              <td><a href="/chsOrderEdit2.php?orderID=<?php echo $row["id"] ?>&orderNo=<?php echo $row["order_no"] ?>"><b>Edit</b></a></td> <!--Open a pop-up or navigate to next screen -->
            </tr>
          <?php
            $counter++;
          } ?>
        </tbody>
      </table>

    </div>
  <?php } ?>
</form>

<script>
      var items = [];

  function addStudentIDtoArray(selectObject, addRemove){
      //alert("Hello! "+selectObject);
      //boxvalue = document.getElementById('selectObject').value;
      if (addRemove == "Add"){
          items.push(selectObject);  
      }else{
          const index = items.indexOf(selectObject);
          if (index > -1) { // only splice array when item is foundf
              items.splice(index, 1); // 2nd parameter means remove one item only
          }
      }
      console.log(items);
      document.getElementById("studentIDArray").value = items;

      /*
      const button = document.getElementById('submit')

      if (document.getElementById("studentIDArray").value != ""){
          button.removeAttribute("disabled");
      }else{
          button.setAttribute("disabled", "disabled");
      }
      */
}

    $('.checkBoxChecked').on('click', function(){
      var checkbox = $(this).find('.Aps_checkbox');
      checkbox.prop("checked", !checkbox.prop("checked"));

      //Highlight if checkbox is checked
      var specTr = $(checkbox).attr("specId");
      
      if($(checkbox).prop("checked")){
          $("#stu_id"+specTr).addClass("highlight");
          addStudentIDtoArray(specTr, "Add");
          //document.getElementById('chgtext'+specTr).innerHTML='<i class="fa fa-check-square-o"></i>';
      }else{
          $("#stu_id"+specTr).removeClass("highlight");
          addStudentIDtoArray(specTr, "Remove");
          //document.getElementById('chgtext'+specTr).innerHTML='<i class="fa fa-square-o"></i>';
      }
  });
</script>