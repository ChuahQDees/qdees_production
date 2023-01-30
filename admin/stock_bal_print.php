<?php
if(session_id() == ''){
  session_start();
}
include_once("../mysql.php");
include_once("functions.php");
include_once("../search_new.php");
include_once("../uikit1.php");
?>
<html>
<body style="padding:10px;">
<?php 
if ($_SESSION["isLogin"]==1) {
   if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O") || ($_SESSION["UserType"]=="S")) & (hasRightGroupXOR($_SESSION["UserName"], "StockBalancesView"))) {
	   
		$centre_code=$_SESSION["CentreCode"];
		$user_name=$_SESSION["UserName"];
		$cut_off_date = date("d/m/Y");
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
    <script>
		window.print();
    </script>

<?php
   }
}
?>
<html>
<body>
