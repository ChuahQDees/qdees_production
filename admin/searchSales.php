<?php
session_start();
include_once("../mysql.php");
include_once("../search_new.php");
include_once("functions.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);//$df, $dt, $collection_type, $student, $product_code
}

$centre_code=$_SESSION["CentreCode"];
list($day, $month, $year)=explode("/", $df);
$df="$year-$month-$day";

list($day, $month, $year)=explode("/", $dt);
$dt="$year-$month-$day";

$product_code=trim($product_code);
$year=$_SESSION['Year'];
// if ($product_code!="") {
//    //$base_sql="SELECT c.*, s.name, s.id as ssid, sum(amount) as total_amount from collection c, student s
//    $base_sql="SELECT c.*, s.name, s.id as ssid, amount as total_amount from collection c, student s
//    where (c.student_code=s.student_code)";
//    $date_token=ConstructTokenGroup("c.collection_date_time", $df." 00:00:00", ">=", "c.collection_date_time", $dt." 23:59:59", "<=", "and");
//    $type_token=ConstructToken("c.collection_type", "product", "=");
//    $student_token=ConstructTokenGroup("s.student_code", "%".$student."%", "like", "s.name", "%".$student."%", "like", "or");
//    $product_token=ConstructToken("c.batch_no", "%".$product_code."%", "like");
// } else {
   $base_sql="SELECT c.*, s.name, s.id as ssid, sum(amount) as total_amount from collection c, student s
   where (c.student_code=s.student_code)";
	
   $date_token=ConstructTokenGroup("c.collection_date_time", $df." 00:00:00", ">=", "c.collection_date_time", $dt." 23:59:59", "<=", "and");
   $type_token= isset($collection_type) ? ConstructToken("c.collection_type", $collection_type, "=") : '';
   $student_token=ConstructTokenGroup("s.student_code", "%".$student."%", "like", "s.name", "%".$student."%", "like", "or");
   //$product_token=ConstructToken("c.collection_type", "product", "<>");
//}
//$base_sql.= "and year(start_date_at_centre) <= '$year' and extend_year >= '$year'"; //commented by shehab 
//echo $base_sql;
//$final_token=ConcatToken($date_token, $type_token, "and");
//$final_token=$date_token;
if($df!="--"&&$dt!="--"){
	$final_token=ConcatToken($final_token, $date_token, "and");
}

$final_token=ConcatToken($final_token, $student_token, "and");
$final_token=ConcatToken($final_token, $product_token, "and");

$final_sql=ConcatWhere($base_sql, $final_token). " and c.centre_code='$centre_code' group by c.batch_no order by c.collection_date_time desc";

$result=mysqli_query($connection, $final_sql);
$num_row=mysqli_num_rows($result);

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
var curCIId, curCISsid;
function dlgCollectionItems(id, ssid) {
  curCIId = id;
  curCISsid = ssid;
   $.ajax({
      url : "admin/dlgCollectionItems.php",
      type : "POST",
      data : "id="+id+"&ssid="+ssid,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         if (response!="") {
            $("#dlgCollectionItems").html(response);
            $("#dlgCollectionItems").dialog({
               dialogClass:"q-dialog",//"no-close",
               title:"Items for receipt "+id,
               modal:true,
               height:'auto',
               width:'60%',
        maxHeight: 400
            });
         }
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}
function dlgVoid(id) {
   $.ajax({
      url : "admin/dlgVoidSales.php",
      type : "POST",
      data : "id="+id,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         $("#dlg").html(response);
         $("#dlg").dialog({
           dialogClass:"q-dialog",//"no-close",
            title:"Reason to void a transaction",
            modal:true,
            height:'auto',
            width:500,
         });
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}

function doVoid(id) {
   var void_reason=$("#void_reason").val();
   var void_reason_en=encodeURI(void_reason);

   if ((id!="") & (void_reason!="")) {
      UIkit.modal.confirm("<h2>Are you sure to void the record?</h2>", function () {
         $.ajax({
            url : "admin/doVoidSales.php",
            type : "POST",
            data : "id="+id+"&void_reason="+void_reason_en,
            dataType : "text",
            beforeSend : function(http) {
            },
            success : function(response, status, http) {
               var s=response.split("|");
               if (s[0]=="0") {
                  UIkit.notify(s[1]);
               }

               if (s[0]=="1") {
                  UIkit.notify(s[1]);
                  $("#dlg").dialog("close");
                  $(".dlg").dialog("close");
                  searchSales();
          $("#dlgCollectionItems").dialog("close");
          $(".dlgCollectionItems").dialog("close");
          dlgCollectionItems(curCIId, curCISsid);
               }
            },
            error : function(http, status, error) {
               UIkit.notify("Error:"+error);
            }
         });
      })
   } else {
      UIkit.notify("Please provide a reason");
   }
}
</script>
<?php
//if ($product_code!="") {
if (false) {
?>
<div class="uk-overflow-container">
<table class="uk-table">
   <tr class="uk-text-bold">
      <td>Receipt No.</td>
      <td>Student Name</td>
      <!--td>Collection Type</td-->
      <!-- <td>Product Name</td> -->
      <!-- <td>Collection Month</td> -->
      <td>Collection Date Time</td>
      <!-- <td class="uk-text-right">Qty</td> -->
      <td class="uk-text-right">Amount</td>
   </tr>
<?php
if ($num_row>0) {
   while ($row=mysqli_fetch_assoc($result)) {
	  
?>
   <tr>
      <td><a href="#" onclick="dlgCollectionItems('<?php echo $row["batch_no"]?>', '<?php echo sha1($row['ssid']) ?>');return false;"><?php echo $row["batch_no"]?></a></td>
      <td><?php echo $row["name"]?></td>
      <!-- <td>Product Sale</td> -->
      <!-- <td><?php echo $row["void_reason"]?></td> -->
      <!-- <td> -->
<?php
         // if ($row["collection_month"]!="") {
         //    echo $row["collection_month"];
         // } else {
         //    echo "NA";
         // }
?>
      <!-- </td> -->
      <td><?php echo $row["collection_date_time"]?></td>
      <!-- <td class="uk-text-right"><?php echo $row["qty"]?></td> -->
      <td class="uk-text-right"><?php echo $row["total_amount"]?></td>
   </tr>
<?php
   }
} else {
   echo "<td colspan='8'>No Record Found</td>";
}
?>
</table>
</div>
<?php
}
//if ($product_code=="") {
if (true) {
?>
<div class="uk-overflow-container">
<table class="uk-table">
   <tr class="uk-text-bold">
      <td>Receipt No.</td>
      <td>Student Name</td>
      <!--td>Collection Type</td-->
      <!-- <td>Collection Month</td> -->
      <td>Collection Date Time</td>
      <td class="uk-text-right">Amount</td>
   </tr>
<?php
if ($num_row>0) {
   while ($row=mysqli_fetch_assoc($result)) {
?>
    <tr>
      <td><a href="#" onclick="dlgCollectionItems('<?php echo $row["batch_no"]?>', '<?php echo sha1($row['ssid']) ?>');return false;"><?php echo $row["batch_no"]?></a></td>
      <td><?php echo $row["name"]?></td>
      <!--td>
<?php
switch ($row["collection_type"]) {
   case "tuition" : echo "Tuition Fee"; break;
   case "registration" : echo "Registration"; break;
   case "deposit" : echo "Deposit"; break;
   case "placement" : echo "Placement"; break;
   case "product" : echo "Product Sale"; break;
   case "addon-product" : echo "Addon Product"; break;
   case "mobile" : echo "Mobile Apps"; break;
   default: echo ucwords($row["collection_type"]);
}
?>
      </td-->
      <!-- <td>
<?php
        // if ($row["collection_month"]!="") {
           // echo date('F', mktime(0, 0, 0, $row["collection_month"], 10));
       //  } else {
          //  echo "NA";
       //  }
?>
      </td> -->
      <td><?php echo $row["collection_date_time"]?></td>
      <td class="uk-text-right"><?php echo $row["total_amount"]?></td>
   </tr>
<?php
   }
} else {
   echo "<td colspan='6'>No Record Found</td>";
}
?>
</table>
</div>
<?php
}
?>
<div id="dlg" class="dlg"></div>
<div id="dlgCollectionItems" class="dlgCollectionItems"></div>