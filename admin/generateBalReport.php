<?php
session_start();
$session_id=session_id();
$centre_code=$_SESSION["CentreCode"];
include_once("../mysql.php");
include_once("functions.php");
include_once("../search_new.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //$cut_off_date
}
if ($method=="print") {
  include_once("../uikit1.php");
}
function getCentreName($centre_code)
{
  if ($centre_code == "")
    return "All Centres";

  global $connection;

  $sql = "SELECT company_name from centre where centre_code='$centre_code'";
  $result = mysqli_query($connection, $sql);
  $row = mysqli_fetch_assoc($result);
  $num_row = mysqli_num_rows($result);

  if ($num_row > 0) {
    return $row["company_name"];
  } else {
    if ($centre_code == "ALL") {
      return "All Centres";
    } else {
      return "";
    }
  }
}
function getStockAdjustment1($centre_code, $product_code) {
  global $connection;

  if ($centre_code == 'ALL') {
     //$sql="SELECT sum(adjust_qty) as total_qty from stock_adjustment where product_code='$product_code' and effective_date<='$cut_off_date'";
     $sql="SELECT sum(adjust_qty) as total_qty from stock_adjustment where product_code='$product_code' ";
  } else {
     //$sql="SELECT sum(adjust_qty) as total_qty from stock_adjustment where product_code='$product_code' and centre_code='$centre_code' and effective_date<='$cut_off_date'";
     $sql="SELECT sum(adjust_qty) as total_qty from stock_adjustment where product_code='$product_code' and centre_code='$centre_code'";
  }

  $result=mysqli_query($connection, $sql);
  $row=mysqli_fetch_assoc($result);
  return $row["total_qty"];
}

function calcBal1($centre_code, $product_code) {
   global $connection;

   return getIncomingQty1($centre_code, $product_code)-
   getOutgoingQty1($centre_code, $product_code)+
   getStockAdjustment1($centre_code, $product_code);
}

function getIncomingQty1($centre_code, $product_code) {
   global $connection;
  
   if ($centre_code == 'ALL') {
      //$sql="SELECT sum(qty) as qty from `order` where product_code='$product_code' and delivered_to_logistic_on<='$cut_off_date 23:59:59'       and delivered_to_logistic_by<>'' and cancelled_by=''";
       $sql="SELECT sum(qty) as qty from `order` where product_code='$product_code' and delivered_to_logistic_by<>'' and cancelled_by=''"; 
   } else {
     // $sql="SELECT sum(qty) as qty from `order` where product_code='$product_code' and delivered_to_logistic_on<='$cut_off_date 23:59:59'and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code'";
      $sql="SELECT sum(qty) as qty from `order` where product_code='$product_code' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code'";
   }
  //  if($product_code=="MY-KE.EARLYDIS-KIT((--QHSB_77"){
  //   echo $sql;
  //  }
   //echo $sql;
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   if ($row["qty"]=="") {
      return 0;
   } else {
      return $row["qty"];
   }
   
}

function getOutgoingQty1($centre_code, $product_code) {
   global $connection;

   if ($centre_code == 'ALL') {
      //$sql="SELECT sum(qty) as qty from `collection` where product_code='$product_code' and collection_date_time<='$cut_off_date 23:59:59' and void=0";
      $sql="SELECT sum(qty) as qty from `collection` where product_code='$product_code' and void=0";
   } else {
     // $sql="SELECT sum(qty) as qty from `collection` where product_code='$product_code' and collection_date_time<='$cut_off_date 23:59:59' and centre_code='$centre_code' and void=0"; 
      $sql="SELECT sum(qty) as qty from `collection` where product_code='$product_code' and centre_code='$centre_code' and void=0";      
   }
  //  if($product_code=="MY-KE.EARLYDIS-KIT((--QHSB_77"){
  //   echo $sql;
  //  }
   
//   echo $sql;
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   if ($row["qty"]=="") {
      return 0;
   } else {
      return $row["qty"];
   }
}


?>



<?php
if ($_SESSION["isLogin"]==1) {
?>
<div class="uk-margin-right">

<div class="uk-width-1-1 myheader text-center mt-5" style="color:white; text-align:center;">

  <h2 class="uk-text-center myheader-text-color myheader-text-style">Centre Stock Balance Report</h2>

  For Term <?php echo $term; ?><br>

</div>

<div class="uk-overflow-container">
<div class="uk-grid">
  <div class="uk-width-medium-5-10">
    <table class="uk-table">
      <tr>
        <td class="uk-text-bold">Centre Name</td>
        <td>
          <?php echo getCentreName($centre_code); ?>
          </td>
        </tr>
        <tr>
          <td class="uk-text-bold">Prepare By</td>
          <td><?php echo $_SESSION["UserName"] ?></td>
        </tr>
        <tr id="note">
          <td colspan="2" class="uk-text-bold">NOTE: To be submitted termly with the order form</td>
        </tr>
      </table>
    </div>
    <div class="uk-width-medium-5-10">
      <table class="uk-table">
        <tr>
          <td class="uk-text-bold">Academic Year</td>
          <?php if ($selected_year) : ?>
            <td><?php echo $selected_year ?></td>
          <?php else : ?>
            <td><?php echo $_SESSION['Year']; ?></td>
          <?php endif; ?>
        </tr>
        <tr>
          <td class="uk-text-bold">School Term</td>
          <td>
            <?php
               $month = date("m");
               $year = $_SESSION["Year"];
               if (isset($selected_month) && $selected_month != '') {
                  $str_length = strlen($selected_month);
                  $month = substr($selected_month, ($str_length - 2), 2);
                  $year = substr($selected_month, 0, -2);
               }
                  //$sql = "SELECT * from codes where year=" . $year;
      $sql = "SELECT * from codes where module='SCHOOL_TERM'";
              if($month!="13"){
                $sql .= " and from_month<=$month and to_month>=$month";
              }
              $sql .= " order by category";
                //Print_r($sql);
                  $centre_result = mysqli_query($connection, $sql);
                  $str = "";
                while ($centre_row = mysqli_fetch_assoc($centre_result)) {
                  // echo $centre_row['category'] . "/" . $centre_row['year'] . "<br>";
                  $str .= $centre_row['category'] . ', ';
                }
                echo rtrim($str, ", ");
               //}
               ?>
          </td>
        </tr>
        <tr>
          <td class="uk-text-bold">Date of submission</td>
          <td><?php echo date("Y-m-d H:i:s") ?></td>
        </tr>
        <tr id="note1" style="display: none;">
          <td colspan="2" class="uk-text-bold">NOTE: To be submitted termly with the order form</td>
        </tr>
      </table>
    </div>
  </div>


<?php
  $user_name=$_SESSION["UserName"];

  // $ori_cut_off_date=$cut_off_date;

  // list($d, $m, $y)=explode("/", $cut_off_date);
  // $cut_off_date="$y-$m-$d";

  if ($term!="") {
    if ($centre_code == 'ALL') {
     // $sql="SELECT DISTINCT o.product_code, p.product_name, p.sub_category, o.centre_code from `order` o, product p Where delivered_to_logistic_on<='$cut_off_date 23:59:59' and delivered_to_logistic_by<>'' and cancelled_by='' and o.product_code=p.product_code";
     //$sql="SELECT DISTINCT p.foundation, p.sub_sub_category, o.product_code, p.product_name, p.sub_category, o.centre_code from `order` o, product p Where delivered_to_logistic_on!='0000-00-00 00:00:00' and delivered_to_logistic_by<>'' and cancelled_by='' and o.product_code=p.product_code and foundation <> '' ";
    } else {
      //$sql="SELECT DISTINCT p.sub_sub_category, o.product_code, p.product_name, p.sub_category, o.centre_code from `order` o, product p Where delivered_to_logistic_on<='$cut_off_date 23:59:59' and delivered_to_logistic_by<>'' and cancelled_by='' and o.centre_code='$centre_code' AND o.product_code=p.product_code AND term=$term";
      $sql="SELECT DISTINCT p.foundation, p.sub_sub_category, o.product_code, p.product_name, p.sub_category, o.centre_code from `order` o, product p Where delivered_to_logistic_on!='0000-00-00 00:00:00' and delivered_to_logistic_by<>'' and cancelled_by='' and o.centre_code='$centre_code' AND o.product_code=p.product_code and foundation <> '' ";
      
    }
    
    if($product==""){
      $sql.=" AND (p.term=$term OR p.sub_sub_category  in ('Marketing set', 'Other products')) ";
      //$sql.=" OR p.sub_sub_category='$product'";
    }else{
      $sql.=" AND (p.term=$term OR p.sub_sub_category = '$product') ";
    }
    //where term <> '' or sub_sub_category <> '' or
    //echo $sql;
     //$user_name_token=ConstructToken('o.user_name', $user_name, '=');
     //$sql=ConcatWhere($sql, $user_name_token);

     //filter sub_category
     if( isset($sub_category) && ! empty($sub_category) ){
       $sub_category_token=ConstructToken('p.sub_category', $sub_category, '=');
       $sql=ConcatWhere($sql, $sub_category_token);
     }

     $sql=ConcatOrder($sql, "FIELD(p.foundation, 'foundation') DESC, p.foundation, p.sub_sub_category, p.product_name asc");

       //var_dump($sql);

     $result=mysqli_query($connection, $sql);
     $num_row=mysqli_num_rows($result);

     if ($num_row>0) {
        echo "<div class='uk-margin-top'>";
        echo "<div class='row'>";
        echo "<div class='col-md-4 d-flex justify-content-center align-items-center'>";
        //echo "<button class='uk-width-1-1 qdees-main-button' onclick='generateBalReport(\"BIMP\")'>BIMP</button>";
        echo "</div>";
         echo "<div class='col-md-4 d-flex justify-content-center align-items-center'>";
        // echo "<button class='uk-width-1-1 qdees-main-button' onclick='generateBalReport(\"BIEP\")'>BIEP</button>";
         echo "</div>";
         echo "<div class='col-md-4 d-flex justify-content-center align-items-center'>";
        // echo "<button class='uk-width-1-1 qdees-main-button' onclick='generateBalReport(\"IE\")'>IE</button>";
         echo "</div>";
        echo "</div>";
        echo "<table class='uk-table rpt-table' style='border: 1px solid #80808085;'>";
        echo "  <tr class='uk-text-small uk-text-bold'>";
        //echo "     <td>Centre Name</td>";
        echo "     <td style='border-bottom:0px'>Foundation</td>";
        echo "     <td>Level</td>";
        echo "     <td>Module</td>";
        echo "     <td>Quantity</td>";
        echo "  </tr>";
        $bal=0;
        $foundation_="";
        while ($row=mysqli_fetch_assoc($result)) {

           $bal=calcBal1($centre_code, $row["product_code"]);

          //  if ($bal == 0) {
          //    $tr = '<tr class="uk-text-small uk-text-danger uk-text-bold" style="background-color:#ffebee">';
          //    $balance_html = '<span class="uk-text-danger uk-text-bold">'.$bal.'</span>';
          //  }else{
             $tr = '<tr class="uk-text-small">';
             $balance_html = '<span>'.max($bal, 0).'</span>';
           //}
              echo $tr;
              //echo "     <td>".$row["centre_code"]."</td>";
              
              if($foundation_!==$row["foundation"]){
               $foundation_ = $row["foundation"];
                echo "     <td style='border-bottom-color: transparent; border-top: 2px solid #80808082;'>".$row["foundation"]."</td>";
              }else{
                echo "     <td style='border-bottom-color: transparent; border-top-color: transparent;'></td>";
              }
             

              echo "     <td>".$row["sub_sub_category"]."</td>";
              echo "     <td>".$row["product_name"]."</td>";
              echo "     <td>".$balance_html."</td>";
              echo "  </tr>";
        }
        echo "</table>";
     } else {
        echo "No record found";
     }
  } else {
     //echo "$term";
  }
  ?>
  </div>
  </div>
<script>
  $(document).ready(function () {
    var method='<?php echo $method?>';
    if (method=="print") {
        window.print();
    }
  });
</script>
<?php }
