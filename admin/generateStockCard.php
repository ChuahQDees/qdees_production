<?php
session_start();
$session_id=session_id();
$centre_code=$_SESSION["CentreCode"];
include_once("../mysql.php");
include_once("functions.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //$product_code, $date_from, $date_to
}

list($d, $m, $y)=explode("/", $date_from);
$date_from="$y-$m-$d";

list($d, $m, $y)=explode("/", $date_to);
$date_to="$y-$m-$d";

function getProductNameA($product_code) {
   global $connection;

   $sql="SELECT product_name from product where product_code='$product_code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["product_name"];
}

function insertOpening($product_code, $date_from) {
   global $connection, $session_id;

   $opening=calcBal($product_code, $date_from);
   $product_name=getProductNameA($product_code);
   $trans_date=$date_from;
//   $trans_date=date("Y-m-d", strtotime($date_from));

   $isql="INSERT into tmp_stock (`session_id`, product_code, product_name, trans_date, `description`, `in`, `out`, bal)
   values ('$session_id', '$product_code', '$product_name', '$trans_date', 'Opening', '$opening', '0', '0')";
   $result=mysqli_query($connection, $isql);
}

function insertIncoming($product_code, $date_from, $date_to) {
   global $connection, $session_id, $centre_code;

   $sql="SELECT p.product_code, p.product_name, o.ordered_on, o.qty from product p, `order` o
   where o.product_code=p.product_code and o.cancelled_by='' and o.delivered_to_logistic_by<>''
   and o.product_code='$product_code' and o.centre_code='$centre_code'
   and ordered_on>='$date_from 00:00:00' and ordered_on<='$date_to 23:59:59'";

   $result=mysqli_query($connection, $sql);

   while ($row=mysqli_fetch_assoc($result)) {
      $product_name=$row["product_name"];
      $trans_date=row["ordered_on"];
      $trans_date=date("Y-m-d", strtotime($trans_date));
      $in=$row["qty"];
      $isql="INSERT into tmp_stock (session_id, product_code, product_name, trans_date, description, `in`, `out`, bal)
      values ('$session_id', '$product_code', '$product_name', '$trans_date', 'Purchases', '$in', '0', '0')";
      mysqli_query($connection, $isql);
   }
}

function insertOutgoing($product_code, $date_from, $date_to) {
   global $connection, $session_id, $centre_code;

   $sql="SELECT p.product_code, p.product_name, c.collection_date_time, c.qty from product p, `collection` c
   where c.product_code=p.product_code and c.product_code='$product_code' and c.centre_code='$centre_code'
   and collection_date_time>='$date_from 00:00:00' and collection_date_time<='$date_to 23:59:59'";

   $result=mysqli_query($connection, $sql);

   while ($row=mysqli_fetch_assoc($result)) {
      $product_name=$row["product_name"];
      $trans_date=$row["collection_date_time"];
      $trans_date=date("Y-m-d", strtotime($trans_date));
      $out=$row["qty"];
      $isql="INSERT into tmp_stock (session_id, product_code, product_name, trans_date, description, `in`, `out`, bal)
      values ('$session_id', '$product_code', '$product_name', '$trans_date', 'Sales', '0', '$out', '0')";
      mysqli_query($connection, $sql);
   }
}

if ($product_code!="") {
   if (deleteAllTransaction($product_code)) {
      InsertOpening($product_code, $date_from);
      insertIncoming($product_code, $date_from, $date_to);
      insertOutgoing($product_code, $date_from, $date_to);

      $sql="SELECT * from tmp_stock where session_id='$session_id' and product_code='$product_code' order by trans_date";
      $result=mysqli_query($connection, $sql);
      $num_row=mysqli_num_rows($result);

      if ($num_row>0) {
         echo "<div class='uk-margin-top'>";
         echo "<h2 class='uk-text-center'>Stock card for $product_code</h2>";
         echo "<table class='uk-table'>";
         echo "  <tr class='uk-text-small uk-text-bold'>";
         echo "     <td>Transaction Date</td>";
         echo "     <td>Description</td>";
         echo "     <td>In</td>";
         echo "     <td>Out</td>";
         echo "     <td>Balance</td>";
         echo "  </tr>";
         $bal=0;
         while ($row=mysqli_fetch_assoc($result)) {
            echo "  <tr class='uk-text-small'>";
            echo "     <td>".$row["trans_date"]."</td>";
            echo "     <td>".$row["description"]."</td>";
            echo "     <td>".$row["in"]."</td>";
            echo "     <td>".$row["out"]."</td>";
            $bal=$bal+$row["in"]-$row["out"];
            echo "     <td>".number_format($bal, 2)."</td>";
            echo "  </tr>";
         }
         echo "</table>";
//         deleteAllTransaction($product_code);
      }
   } else {
      echo "0|Something went wrong, cannot proceed";
   }
}
?>
</div>