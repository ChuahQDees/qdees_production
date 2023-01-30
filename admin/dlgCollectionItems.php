<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");

foreach($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //$id
}

$sql="SELECT * from collection where batch_no='$id'";
$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);
?>
<table class="uk-table">
   <tr class="uk-text-bold">
      <td>Collection Type</td>
      <td>Collection Month</td>
      <td>Amount</td>
<?php
if (hasRightGroupXOR($_SESSION["UserName"], "SalesEdit")) {
?>
     <?php if (hasRightGroupXOR($_SESSION["UserName"], "CreditNoteEdit|CreditNoteView")) { ?> <td>Action</td>
      <?php
      }
      ?>
<?php
}
?>
   </tr>
<?php
if ($num_row>0) {
	
   while ($row=mysqli_fetch_assoc($result)) {
?>
   <tr>
		<td>
<?php
switch (strtolower($row["collection_type"])) {
    case "tuition" : echo  $row["product_code"] ; break;
   // case "registration" : echo $row["subject"] ? "Registration - ".$row["subject"] : "Registration"; break;
   // case "deposit" : echo $row["subject"] ? "Deposit - ".$row["subject"] : "Deposit"; break;
   // case "placement" : echo $row["subject"] ? "Placement - ".$row["subject"] : "Placement"; break;
    case "product" : echo "Product Sale - ".$row["product_name"]; break;
    case "addon-product" : echo "Addon Product - ".$row["product_name"]; break;
    case "mobile" : echo "Mobile Apps"; break;
	case "registration" : echo $row["subject"] ? "Registration - ".$row["subject"] : "Registration"; break;
	case "insurance" : echo $row["subject"] ? "Insurance - ".$row["subject"] : "Insurance"; break;
	case "deposit" : echo $row["subject"] ? "Deposit - ".$row["subject"] : "Deposit"; break;
   case "q-dees-level-kit" : echo $row["subject"] ? "Q-dees Level Kit - ".$row["subject"] : "Q-dees Level Kit"; break;
   case "uniform (2 sets)" : echo $row["subject"] ? "Uniform (2 sets) - ".$row["subject"] : "Uniform (2 sets)"; break;
   case "gymwear (1 set)" : echo $row["subject"] ? "Gymwear (1 set) - ".$row["subject"] : "Gymwear (1 set)"; break;
   case "q-dees bag" : echo $row["subject"] ? "Q-dees Bag - ".$row["subject"] : "Q-dees Bag"; break;
	case "placement" : echo $row["subject"] ? "Placement - ".$row["subject"] : "Placement"; break;
	case "math" : echo $row["subject"] ? "IQ Math - ".$row["subject"] : "IQ Math"; break;
	case "mandarin" : echo $row["subject"] ? "Enhanced Foundation Mandarin - ".$row["subject"] : "Enhanced Foundation Mandarin"; break;
   case "enhanced foundation mandarin" : echo $row["subject"] ? "Enhanced Foundation Mandarin - ".$row["subject"] : "Enhanced Foundation Mandarin"; break;
	case "international" : echo $row["subject"] ? "International Art - ".$row["subject"] : "International Art"; break;
   case "foundation" : echo $row["subject"] ? "Foundation English - ".$row["subject"] : "Foundation English"; break;
   case "pendidikan" : echo $row["subject"] ? "Pendidikan Islam - ".$row["subject"] : "Pendidikan Islam"; break;
	case "integrated" : echo $row["subject"] ? "Integrated Module - ".$row["subject"] : "Integrated Module"; break;
	case "link" : echo $row["subject"] ? "Link & Think - ".$row["subject"] : "Link & Think"; break;
	case "mandarinmodules" : echo $row["subject"] ? "Mandarin Modules - ".$row["subject"] : "Mandarin Modules"; break;
	case "uniform" : echo $row["subject"] ? "Uniform - ".$row["subject"] : "Uniform"; break;
	case "gymwear" : echo $row["subject"] ? "Gymwear - ".$row["subject"] : "Gymwear"; break;
	case "qdeesbag" : echo $row["subject"] ? "Q-dees Bag - ".$row["subject"] : "Q-dees Bag"; break;
    if ($row["collection_type"] == 'tuition' && $row["product_code"]=='opening-balance') {
         $description='Opening tuition outstanding'; 
         $qty=1; 
         $unit_price=""; 
         $total=$row["amount"];
      } elseif ($row["collection_type"] == 'tuition' && $row["product_code"]!='opening-balance') {
         if ($row["allocation_id"]>0) {
            $description=getCourseName($row["course_id"]); 
            $description=$row["product_code"]; 
         }else {
            $description=$row["subject"]; 
            $description=$row["product_code"]; 
         }
         $qty=1; 
         //$unit_price=""; 
         $total=$row["amount"];
      }
   default : echo $row["product_name"];
}

?>
		</td>
		<td>
<?php
         if ($row["collection_month"]!="") {
            echo date('F', mktime(0, 0, 0, $row["collection_month"], 10));
         } else {
            echo "NA";
         }
?>
		</td>
      <td ><?php echo $row["amount"]?></td>
<?php
if (hasRightGroupXOR($_SESSION["UserName"], "SalesEdit")) {
?>
      
      
            
      <td>
<?php
if ($row["void"]=="0") {
?>

<?php if (hasRightGroupXOR($_SESSION["UserName"], "CreditNoteEdit")) { ?> 
         <a onclick="dlgVoid('<?php echo sha1($row['id'])?>');" data-uk-tooltip="{pos:top}" title="Void the record"><i class="uk-icon-eraser"></i></a>
      
<?php } elseif (hasRightGroupXOR($_SESSION["UserName"], "CreditNoteView")) { ?> 
         <a  data-uk-tooltip="{pos:top}" title="Permission not granted to void"><i class="uk-icon-eraser"></i></a>
         <?php
      }
      ?>
<?php
} else {
   $void_reason=$row["void_reason"];
   echo "<span data-uk-tooltip title='$void_reason'>Voided</span>";
}
?>
      </td>
     
<?php
}
?>
   </tr>
<?php
   }
} else {
?>
   <tr>
      <td colspan="3">No record found</td>
   </tr>
<?php
}
?>
</table>
<div class="uk-text-right" >
<!--a onclick="$('#dlgCollectionItems').dialog('close');" class="uk-button uk-button-small">Cancel</a-->
<a href="index.php?p=student_info&ssid=<?php echo $ssid?>&back=sales" class="blue_button_s1 blue_button">Payment <i class="fa fa-arrow-right"></i></a>
</div>
<style>
.blue_button_s1{
	  line-height: 28px; 
     min-height: 30px; 
     font-size: 1rem; 
     border-radius: 4px;
	 cursor: pointer;
	 padding:5px;
  }
</style>