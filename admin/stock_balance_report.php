<?php
session_start();
$session_id=session_id();
$centre_code=$_SESSION["CentreCode"];
include_once("../mysql.php");
include_once("functions.php");
include_once("../search_new.php");

foreach ($_GET as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //$cut_off_date
}

?>



<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css">
<link rel="stylesheet" type="text/css" href="../lib/uikit/css/uikit.gradient.min6.my.css">
<link rel="stylesheet" href="../css/my1.css">
<link rel="stylesheet" href="../css/style.css">
<style>

@media print {
  *{ color-adjust: exact; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
	#btnPrint{
		display:none;
	}
}
</style>

<div class="uk-margin-right uk-margin-left uk-margin-top">

	
		<div class="uk-width-1-1 myheader text-center">
			<h2 class="uk-text-center myheader-text-color myheader-text-style" style="font-size: 24px;">STOCK BALANCE REPORT</h2>
		</div>

		<div class="nice-form text-center">	
			
						
			<div class=" table-responsive">
<div>

<?php
if ($_SESSION["isLogin"]==1) {
  $user_name=$_SESSION["UserName"];

  $ori_cut_off_date=$cut_off_date;

  list($d, $m, $y)=explode("/", $cut_off_date);
  $cut_off_date="$y-$m-$d";

  if ($ori_cut_off_date!="") {
    if ($user_name == 'super') {
      $sql="SELECT DISTINCT o.product_code, p.product_name, p.sub_category from `order` o, product p Where delivered_to_logistic_on<='$cut_off_date 23:59:59' and delivered_to_logistic_by<>'' and cancelled_by='' and o.product_code=p.product_code";
    } else {
      $sql="SELECT DISTINCT o.product_code, p.product_name, p.sub_category from `order` o, product p Where delivered_to_logistic_on<='$cut_off_date 23:59:59' and delivered_to_logistic_by<>'' and cancelled_by='' and o.centre_code='$centre_code' AND o.product_code=p.product_code";
    }

     //$user_name_token=ConstructToken('o.user_name', $user_name, '=');
     //$sql=ConcatWhere($sql, $user_name_token);

     //filter sub_category
     if( isset($sub_category) && ! empty($sub_category) ){
       $sub_category_token=ConstructToken('p.sub_category', $sub_category, '=');
       $sql=ConcatWhere($sql, $sub_category_token);
     }

     $sql=ConcatOrder($sql, "p.product_name asc");

     $result=mysqli_query($connection, $sql);
     $num_row=mysqli_num_rows($result);

     if ($num_row>0) {
        echo "<div class='uk-margin-top'>";
        // echo "<div class='row'>";
        // echo "<div class='col-md-4 d-flex justify-content-center align-items-center'>";
        // echo "<button class='uk-width-1-1 qdees-main-button' onclick='generateBalReport(\"BIMP\")'>BIMP</button>";
        // echo "</div>";
        //  echo "<div class='col-md-4 d-flex justify-content-center align-items-center'>";
        //  echo "<button class='uk-width-1-1 qdees-main-button' onclick='generateBalReport(\"BIEP\")'>BIEP</button>";
        //  echo "</div>";
        //  echo "<div class='col-md-4 d-flex justify-content-center align-items-center'>";
        //  echo "<button class='uk-width-1-1 qdees-main-button' onclick='generateBalReport(\"IE\")'>IE</button>";
        //  echo "</div>";
        // echo "</div>";
        echo "<table class='uk-table'>";
        echo "  <tr class='uk-text-small uk-text-bold'>";
        echo "     <td>Sub Category</td>";
        echo "     <td>Product Name</td>";
        echo "     <td>Balance as at $ori_cut_off_date</td>";
        echo "  </tr>";
        $bal=0;
        while ($row=mysqli_fetch_assoc($result)) {
           $bal=calcBal($centre_code, $row["product_code"], $cut_off_date);

           if ($bal == 0) {
             $tr = '<tr class="uk-text-small uk-text-danger uk-text-bold" style="background-color:#ffebee">';
             $balance_html = '<span class="uk-text-danger uk-text-bold">'.$bal.'</span>';
           }else{
             $tr = '<tr class="uk-text-small">';
             $balance_html = '<span>'.$bal.'</span>';
           }
              echo $tr;
              echo "     <td>".$row["sub_category"]."</td>";
              echo "     <td>".$row["product_name"]."</td>";
              echo "     <td>".$balance_html."</td>";
              echo "  </tr>";
        }
        echo "</table>";
     } else {
        echo "No record found";
     }
  } else {
     echo "$ori_cut_off_date";
  }
  ?>
  </div>
<?php } ?>
			</div>
			
		<br>
		</div>
				
		<div class="uk-margin-top" style="text-align: right;">
			   <button id="btnPrint" class="uk-button" onclick="printDialog();">Print</button>
		</div>
			
			
		
</div>
<script>
function printDialog() {
   window.print();
}

printDialog();
opener.location.reload();

</script>




