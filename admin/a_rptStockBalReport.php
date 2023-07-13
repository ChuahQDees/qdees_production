<?php
session_start();
$session_id=session_id();
$centre_code=$_SESSION["CentreCode"];
$year=$_SESSION["Year"];
//echo $year;
include_once("../mysql.php");
include_once("functions.php");
include_once("../search_new.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //$from_date
}
if ($method == "print") {
  include_once("../uikit1.php");
}
//echo $type; die;
$from_date = convertDate2ISO($from_date);
$to_date = convertDate2ISO($to_date);
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
?>



<?php
if ($_SESSION["isLogin"]==1) {
?>
<div class="uk-margin-right">

<div class="uk-width-1-1 myheader text-center mt-5" style="color:white; text-align:center;">

  <h2 class="uk-text-center myheader-text-color myheader-text-style">Stock Balance Report</h2>
  From <?php 
            if($term=="term1"){
              echo "Term 1";
            }else if($term=="term2"){
              echo "Term 2";
            }else if($term=="term3"){
              echo "Term 3";
            }else if($term=="term4"){
              echo "Term 4";
            }else if($term=="term5"){
              echo "Term 5";
            }else{
              echo "All Term";
            } 
             ?><br>
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
            if($term=="term1"){
              echo "Term 1";
            }else if($term=="term2"){
              echo "Term 2";
            }else if($term=="term3"){
              echo "Term 3";
            }else if($term=="term4"){
              echo "Term 4";
            }else if($term=="term5"){
              echo "Term 5";
            }else{
              echo "All Term";
            }
           
      //          $month = date("m");
      //          $year = $_SESSION['Year'];
      //          if (isset($selected_month) && $selected_month != '') {
      //             $month = substr($selected_month, 4, 2);
      //             $year = substr($selected_month, 0, -2);
      //          }
      //             //$sql = "SELECT * from codes where year=" . $year;
      // $sql = "SELECT * from codes where module='SCHOOL_TERM'";
      //         if($from_date!="" && $to_date!=""){
      //           $sql .= " and from_month<=month('$from_date') and to_month>=month('$to_date')";
      //         }
      //         $sql .= " order by category";
      //           //Print_r($sql);
      //             $centre_result = mysqli_query($connection, $sql);
      //             $str = "";
      //           while ($centre_row = mysqli_fetch_assoc($centre_result)) {
      //             // echo $centre_row['category'] . "/" . $centre_row['year'] . "<br>";
      //             $str .= $centre_row['category'] . ', ';
      //           }
      //           echo rtrim($str, ", ");
      //          //}
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

  //$ori_from_date=$from_date;

  // list($d, $m, $y)=explode("/", $from_date);
  // $from_date="$y-$m-$d"; 

  //if ($from_date!="") {
    // if ($user_name == 'super') {
    //   $sql="SELECT DISTINCT o.product_code, p.product_name, p.sub_category, o.centre_code from `order` o, product p Where delivered_to_logistic_on<='$from_date 23:59:59' and delivered_to_logistic_by<>'' and 
//(cancelled_by='' OR cancelled_by IS NULL) and o.product_code=p.product_code";  
    // } else {
     // $sql="SELECT DISTINCT o.product_code, p.product_name, p.sub_category, o.centre_code from `order` o, product p Where delivered_to_logistic_by<>'' and 
//(cancelled_by='' OR cancelled_by IS NULL) and o.centre_code='$centre_code' AND o.product_code=p.product_code";
    //}

     //$user_name_token=ConstructToken('o.user_name', $user_name, '=');
     //$sql=ConcatWhere($sql, $user_name_token);

     //filter sub_category
     if( isset($sub_category) && ! empty($sub_category) ){
       $sub_category_token=ConstructToken('p.sub_category', $sub_category, '=');
       $sql=ConcatWhere($sql, $sub_category_token);
     }

     $sql=ConcatOrder($sql, "p.product_name asc");

      // var_dump($sql);

     $result=mysqli_query($connection, $sql);
     $num_row=mysqli_num_rows($result);

    ?>
        <div class='uk-margin-top'>
        <!-- <div class='row'>
        <div class='col-md-4 d-flex justify-content-center align-items-center'>
      
        </div>
         <div class='col-md-4 d-flex justify-content-center align-items-center'>
       
         </div>
         <div class='col-md-4 d-flex justify-content-center align-items-center'>
        
        </div>
        </div> -->
        <style>
          .rpt-table td {
            border: 1px solid black;
            }
        </style>
        
        <script>
          $(document).ready(function(){
            var term = '<?php echo $term; ?>';
            if(term=="term1"){
              $(".term").hide();
              $(".term1").show();
            }else if(term=="term2"){
              $(".term").hide();
              $(".term2").show();
            }
            else if(term=="term3"){
              $(".term").hide();
              $(".term3").show();
            }
            else if(term=="term4"){
              $(".term").hide();
              $(".term4").show();
            }else{
              $(".term").show();
            }
          })
        </script>
        <?php $jan_opening_total = 0 ?>
        <table class='uk-table rpt-table'>
        <tr class='uk-text-small uk-text-bold'>
          <td rowspan="3">Month</td>
          <td rowspan="3"><?php echo getCentreName($centre_code); ?></td>
          <?php if($type=="" || $type=="Termly Module") { ?>
          <td rowspan="2" colspan="<?php if($term=="") echo "17"; else echo "5"; ?>">Termly Module</td>
          <?php } if($type=="" || $type=="LTR") { ?>
          <td rowspan="2" colspan="<?php if($term=="") echo "17"; else echo "5"; ?>">LTR</td>
          <?php } if($type=="" || $type=="Fliptec@Q Mandarin") { ?>
          <td rowspan="2" colspan="<?php if($term=="") echo "9"; else echo "3"; ?>">Fliptec@Q Mandarin</td>
          <?php } if($type=="" || $type=="Beamind") { ?>
          <td colspan="54">Beamind</td>
          <?php } ?>
        </tr>
        
        <tr class='uk-text-small uk-text-bold'>
        <?php if($type=="" || $type=="Beamind"){ ?>
          <td colspan="<?php if($term=="") echo "13"; else echo "4"; ?>">English</td>
          <td colspan="<?php if($term=="") echo "13"; else echo "4"; ?>">Mandarin</td>
          <td colspan="<?php if($term=="") echo "13"; else echo "4"; ?>">Art</td>
          <td colspan="<?php if($term==""){ echo "12";} else if($term=="term1"){ echo "3"; } else {echo "4";} ?>">Math</td>
          <td rowspan="2">Grand Total</td>
          <?php } ?>
        </tr>
        
        <?php if($type=="" || $type=="Termly Module") { ?>
        <tr class='uk-text-small uk-text-bold'>
          <td class="term term1">ED1</td>
          <td class="term term2">ED2</td>
          <td class="term term3">ED3</td>
          <td class="term term4">ED4</td>
          <td class="term term1">M1</td>
          <td class="term term2">M2</td>
          <td class="term term3">M3</td>
          <td class="term term4">M4</td>
          <td class="term term1">M5</td>
          <td class="term term2">M6</td>
          <td class="term term3">M7</td>
          <td class="term term4">M8</td>
          <td class="term term1">M9</td>
          <td class="term term2">M10</td>
          <td class="term term3">M11</td>
          <td class="term term4">M12</td>
          <td>Total</td>
                        <?php } if($type=="" || $type=="LTR"){ ?>
          <td class="term term1">ED1</td>
          <td class="term term2">ED2</td>
          <td class="term term3">ED3</td>
          <td class="term term4">ED4</td>
          <td class="term term1">M1</td>
          <td class="term term2">M2</td>
          <td class="term term3">M3</td>
          <td class="term term4">M4</td>
          <td class="term term1">M5</td>
          <td class="term term2">M6</td>
          <td class="term term3">M7</td>
          <td class="term term4">M8</td>
          <td class="term term1">M9</td>
          <td class="term term2">M10</td>
          <td class="term term3">M11</td>
          <td class="term term4">M12</td>
          <td>Total</td>
          <?php } if($type=="" || $type=="Fliptec@Q Mandarin"){ ?>
          <td class="term term1">M5</td>
          <td class="term term2">M6</td>
          <td class="term term3">M7</td>
          <td class="term term4">M8</td>
          <td class="term term1">M9</td>
          <td class="term term2">M10</td>
          <td class="term term3">M11</td>
          <td class="term term4">M12</td>
          <td>Total</td>
          <?php } if($type=="" || $type=="Beamind"){ ?>
          <!--English start-->
            <?php } if($type=="" || $type=="Beamind"){ ?>
          <td class="term term1">PREP A</td>
          <td class="term term2">PREP B</td>
          <td class="term term3">M1</td>
          <td class="term term4">M2</td>
          <td class="term term1">M3</td>
          <td class="term term2">M4</td>
          <td class="term term3">M5</td>
          <td class="term term4">M6</td>
          <td class="term term1">M7</td>
          <td class="term term2">M8</td>
          <td class="term term3">M9</td>
          <td class="term term4">M10</td>
          <td>Total</td>
          <!--English end--> 
          <!--Mandarin start-->
          <td class="term term1">M1</td>
          <td class="term term2">M2</td>
          <td class="term term3">M3</td>
          <td class="term term4">M4</td>
          <td class="term term1">M5</td>
          <td class="term term2">M6</td>
          <td class="term term3">M7</td>
          <td class="term term4">M8</td>
          <td class="term term1">M9</td>
          <td class="term term2">M10</td>
          <td class="term term3">M11</td>
          <td class="term term4">M12</td>
          <td>Total</td>
          <!--Mandarin end-->
          <!--Art start-->
          <td class="term term1">M1</td>
          <td class="term term2">M2</td>
          <td class="term term3">M3</td>
          <td class="term term4">M4</td>
          <td class="term term1">M5</td>
          <td class="term term2">M6</td>
          <td class="term term3">M7</td>
          <td class="term term4">M8</td>
          <td class="term term1">M9</td>
          <td class="term term2">M10</td>
          <td class="term term3">M11</td>
          <td class="term term4">M12</td>
          <td>Total</td>
          <!--Art end-->
          <!--Math start-->
          <td class="term term1">ACE02</td>
          <td class="term term2">M1</td>
          <td class="term term3">M2</td>
          <td class="term term4">M3</td>
          <td class="term term2">M4</td>
          <td class="term term3">M5</td>
          <td class="term term4">M6</td>
          <td class="term term1">M7</td>
          <td class="term term2">M8</td>
          <td class="term term3">M9</td>
          <td class="term term4">M10</td>
          <td>Total</td>
          <!--Math end-->
          <?php } ?>
        </tr>
        <!--january start-->
          <tr class="term term1">
            <td rowspan="4">January</td>
            <td>Opening</td>
            <?php if($type=="" || $type=="Termly Module"){ ?>
            <td class="term term1"><!--ED1-->
              <?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-EDP1.EARLY-DIS%') and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-EDP1.EARLY-DIS%') and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $jan_opening_total = round($row["qty"], 0) ;
                }
              ?>
            </td>
            <td class="term term2"><!--ED2--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-EDP2.EARLY-DIS%') and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-EDP2.EARLY-DIS%') and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--ED3--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-EDP3.EARLY-DIS%') and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-EDP3.EARLY-DIS%') and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--ED4--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-EDP4.EARLY-DIS%') and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-EDP4.EARLY-DIS%') and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total += $row["qty"]; echo round($row["qty"], 0);
                }?></td>
            <td class="term term1"><?php //M1
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE01----QF1%') and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE01----QF1%') and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M2--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE02----QF1%') and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE02----QF1%') and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M3--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE03----QF1%') and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE03----QF1%') and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M4--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE04----QF1%') and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE04----QF1%') and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M5
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE05----QF2%') and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE05----QF2%') and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M6--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE06----QF2%') and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE06----QF2%') and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M7--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE07----QF2%') and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE07----QF2%') and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M8--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE08----QF2%') and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE08----QF2%') and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M9
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE09----QF3%') and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE09----QF3%') and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M10--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE10----QF3%') and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE10----QF3%') and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M11--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE11----QF3%') and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE11----QF3%') and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M12--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE12----QF3%') and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE12----QF3%') and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td><?php //Total
                echo round($jan_opening_total, 0);
              ?></td>

              <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
              <?php } if($type=="" || $type=="LTR"){ ?>
              <td class="term term1"><!--ED1-->
              <?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR.EDP-MOD 01%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR.EDP-MOD 01%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $jan_opening_total_LTR = round($row["qty"], 0) ;
                }
              ?>
            </td>
            <td class="term term2"><!--ED2--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR.EDP-MOD 02%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR.EDP-MOD 02%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--ED3--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR.EDP-MOD 03%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR.EDP-MOD 03%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--ED4--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR.EDP-MOD 04%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR.EDP-MOD 04%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M1
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 01%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 01%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M2--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 02%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 02%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M3--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 03%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 03%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M4--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 04%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 04%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M5
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 05%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 05%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M6--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 06%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 06%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M7--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 07%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 07%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M8--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 08%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 08%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M9
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 09%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 09%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M10--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 10%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 10%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M11--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 11%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 11%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M12--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 12%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 12%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td><?php //Total
                echo round($jan_opening_total_LTR, 0);
              ?></td>
            
            <!--Fliptec@Q Mandarin-->
            <?php } if($type=="" || $type=="Fliptec@Q Mandarin"){ ?>
            <td class="term term1"><?php //M5
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM05.MAND--QF2%') and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM05.MAND--QF2%') and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M6--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM06.MAND--QF2%') and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM06.MAND--QF2%') and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M7--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM07.MAND--QF2%') and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM07.MAND--QF2%') and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M8--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM08.MAND--QF2%') and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM08.MAND--QF2%') and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M9
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM09.MAND--QF3%') and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM09.MAND--QF3%') and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M10--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM10.MAND--QF3%') and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM10.MAND--QF3%') and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M11--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM11.MAND--QF3%') and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM11.MAND--QF3%') and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M12--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM12.MAND--QF3%') and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM12.MAND--QF3%') and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td><?php //Total
                echo round($jan_opening_total_Fliptec, 0);
              ?></td>

            <!--English start-->
            <?php } if($type=="" || $type=="Beamind"){ ?>
            <td class="term term1"><?php //PREP A
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-PREP A%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-PREP A%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_English = $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--PREP B--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-PREP B%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-PREP B%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M1--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 01%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 01%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M2--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 02%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 02%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M3
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 03%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 03%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M4--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 04%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 04%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M5--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 05%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 05%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M6--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 06%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 06%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M7
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 07%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 07%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M8--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 08%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 08%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M9--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 09%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 09%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M10--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 10%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 10%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td><?php //Total
                echo round($jan_opening_total_English, 0);
              ?></td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><?php //M1
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 01%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 01%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Mandarin = $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M2--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 02%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 02%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M3--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 03%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 03%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M4--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 04%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 04%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M5
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 05%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 05%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M6--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 06%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 06%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M7--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 07%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 07%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M8--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 08%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 08%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M9
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 09%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 09%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M10--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 10%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 10%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M11--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 11%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 11%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M12--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 12%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 12%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td><?php //Total
                echo round($jan_opening_total_Mandarin, 0);
              ?></td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><?php //M1
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 01%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 01%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Art = $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M2--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 02%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 02%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M3--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 03%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 03%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M4--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 04%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 04%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M5
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 05%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 05%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M6--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 06%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 06%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M7--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 07%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 07%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M8--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 08%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 08%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M9
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 09%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 09%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M10--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 10%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 10%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M11--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 11%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 11%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M12--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 12%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 12%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td><?php //Total
                echo round($jan_opening_total_Art, 0);
              ?></td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><?php //ACE02
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-ACE 02%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-ACE 02%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Math = $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M1--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 01%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 01%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M2--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 02%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 02%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M3--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 03%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 03%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M4--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 04%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 04%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M5--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 05%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 05%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M6--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 06%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 06%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M7
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 07%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 07%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M8--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 08%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 08%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M9--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 09%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 09%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M10--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 10%' and delivered_to_logistic_on<'$year/01/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 10%' and CAST(collection_date_time AS DATE)<'$year/01/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_opening_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td><?php //Total
                echo round($jan_opening_total_Math, 0);
              ?></td>
            <!--Math end-->
            <td><?php //Grand Total
                echo round($jan_opening_total_English + $jan_opening_total_Mandarin + $jan_opening_total_Art + $jan_opening_total_Math, 0);
              ?></td>
              <?php } ?>
          </tr>
          <tr class="term term1">
            <td>Purchase</td><!--january-->
            <?php if($type=="" || $type=="Termly Module"){ ?>
            <td class="term term1"><!--ED1-->
              <?php 
                $sql="SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-EDP1.EARLY-DIS%') and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $jan_purchase_total = round($row["qty"], 0) ;
                }
              ?>
            </td>
            <td class="term term2"><!--ED2--><?php 
                $sql="SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-EDP2.EARLY-DIS%') and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--ED3--><?php 
                $sql="SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-EDP3.EARLY-DIS%') and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--ED4--><?php 
                $sql="SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-EDP4.EARLY-DIS%') and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M1
                $sql="SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE01----QF1%') and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M2--><?php 
                $sql="SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE02----QF1%') and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M3--><?php 
                $sql="SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE03----QF1%') and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M4--><?php 
                $sql="SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE04----QF1%') and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M5
                $sql="SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE05----QF2%') and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
              //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M6--><?php 
                $sql="SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE06----QF2%') and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
               
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M7--><?php 
                $sql="SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE07----QF2%') and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
               
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M8--><?php 
                $sql="SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE08----QF2%') and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
               
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M9
                $sql="SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE09----QF3%') and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M10--><?php 
                $sql="SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE10----QF3%') and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M11--><?php 
                $sql="SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE11----QF3%') and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M12--><?php 
                $sql="SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE12----QF3%') and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td><?php //Total
                echo round($jan_purchase_total, 0);
              ?></td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
              <?php } if($type=="" || $type=="LTR"){ ?>
           <td class="term term1"><!--ED1-->
              <?php 
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR.EDP-MOD 01%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $jan_purchase_total_LTR = round($row["qty"], 0) ;
                }
              ?>
            </td>
            <td class="term term2"><!--ED2--><?php 
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR.EDP-MOD 02%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--ED3--><?php 
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR.EDP-MOD 03%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--ED4--><?php 
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR.EDP-MOD 04%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M1
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 01%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M2--><?php 
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 02%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M3--><?php 
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 03%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M4--><?php 
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 04%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M5
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 05%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
              //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M6--><?php 
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 06%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M7--><?php 
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 07%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M8--><?php 
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 08%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M9
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 09%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M10--><?php 
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 10%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M11--><?php 
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 11%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M12--><?php 
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 12%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td><?php //Total
                echo round($jan_purchase_total_LTR, 0);
              ?></td>

            <!--Fliptec@Q Mandarin-->
            <?php } if($type=="" || $type=="Fliptec@Q Mandarin"){ ?>
            <td class="term term1"><?php //M5
                $sql="SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM05.MAND--QF2%') and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M6--><?php
                $sql="SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM06.MAND--QF2%') and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M7--><?php
                $sql="SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM07.MAND--QF2%') and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M8--><?php
                $sql="SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM08.MAND--QF2%') and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M9
                $sql="SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM09.MAND--QF3%') and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M10--><?php
                $sql="SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM10.MAND--QF3%') and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M11--><?php
                $sql="SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM11.MAND--QF3%') and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M12--><?php
                $sql="SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM12.MAND--QF3%') and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td><?php //Total
                echo round($jan_purchase_total_Fliptec, 0);
              ?></td>

            <!--English start-->
            <?php } if($type=="" || $type=="Beamind"){ ?>
            <td class="term term1"><?php //PREP A
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-PREP A%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_English = $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--PREP B--><?php
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-PREP B%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M1--><?php
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 01%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M2--><?php
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 02%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M3
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 03%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M4--><?php
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 04%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M5--><?php
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 05%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M6--><?php
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 06%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M7
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 07%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M8--><?php
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 08%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M9--><?php
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 09%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M10--><?php
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 10%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td><?php //Total
                echo round($jan_purchase_total_English, 0);
              ?></td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><?php //M1
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 01%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Mandarin = $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M2--><?php
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 02%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M3--><?php
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 03%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M4--><?php
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 04%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M5
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 05%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M6--><?php
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 06%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M7--><?php
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 07%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M8--><?php
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 08%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M9
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 09%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M10--><?php
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 10%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M11--><?php
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 11%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M12--><?php
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 12%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td><?php //Total
                echo round($jan_purchase_total_Mandarin, 0);
              ?></td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><?php //M1
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 01%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Art = $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M2--><?php
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 02%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M3--><?php
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 03%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M4--><?php
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 04%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M5
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 05%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M6--><?php
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 06%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M7--><?php
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 07%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M8--><?php
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 08%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M9
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 09%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M10--><?php
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 10%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M11--><?php
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 11%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M12--><?php
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 12%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td><?php //Total
                echo round($jan_purchase_total_Art, 0);
              ?></td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><?php //ACE02
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-ACE 02%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Math = $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M1--><?php 
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 01%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M2--><?php 
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 02%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M3--><?php 
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 03%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M4--><?php 
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 04%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M5--><?php 
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 05%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M6--><?php 
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 06%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><!--M7--><?php 
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 07%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M8--><?php 
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 08%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M9--><?php 
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 09%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M10--><?php 
                $sql="SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 10%' and year(delivered_to_logistic_on) = '$year' and month(delivered_to_logistic_on) = 1 and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_purchase_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td><?php //Total
                echo round($jan_purchase_total_Math, 0);
              ?></td>
            <!--Math end-->
            <td><?php //Grand Total
                echo round($jan_purchase_total_English + $jan_purchase_total_Mandarin + $jan_purchase_total_Art + $jan_purchase_total_Math, 0);
              ?></td>
              <?php } ?>
          </tr>
          <tr class="term term1">
            <td>Total consumed</td><!--january-->
            <?php if($type=="" || $type=="Termly Module"){ ?>
            <td class="term term1"><!--ED1-->
              <?php 
                $sql="SELECT sum(qty)  as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-EDP1.EARLY-DIS%') and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $jan_consump_total = round($row["qty"], 0) ;
                }
              ?>
            </td>
            <td class="term term2"><!--ED2--><?php 
                $sql="SELECT sum(qty)  as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-EDP2.EARLY-DIS%') and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--ED3--><?php 
                $sql="SELECT sum(qty)  as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-EDP3.EARLY-DIS%') and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--ED4--><?php 
                $sql="SELECT sum(qty)  as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-EDP4.EARLY-DIS%') and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M1
                $sql="SELECT sum(qty)  as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE01----QF1%') and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M2--><?php 
                $sql="SELECT sum(qty)  as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE02----QF1%') and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M3--><?php 
                $sql="SELECT sum(qty)  as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE03----QF1%') and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M4--><?php 
                $sql="SELECT sum(qty)  as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE04----QF1%') and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M5
                $sql="SELECT sum(qty)  as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE05----QF2%') and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M6--><?php 
                $sql="SELECT sum(qty)  as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE06----QF2%') and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
               
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M7--><?php 
                $sql="SELECT sum(qty)  as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE07----QF2%') and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
               
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M8--><?php 
                $sql="SELECT sum(qty)  as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE08----QF2%') and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
               
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M9
                $sql="SELECT sum(qty)  as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE09----QF3%') and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M10--><?php 
                $sql="SELECT sum(qty)  as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE10----QF3%') and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M11--><?php 
                $sql="SELECT sum(qty)  as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE11----QF3%') and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M12--><?php 
                $sql="SELECT sum(qty)  as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE12----QF3%') and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td><?php //Total
                echo round($jan_consump_total, 0);
              ?></td>

                <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
              <?php } if($type=="" || $type=="LTR"){ ?>
                <td class="term term1"><!--ED1-->
              <?php 
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-LTR.EDP-MOD 01%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $jan_consump_total_LTR = round($row["qty"], 0) ;
                }
              ?>
            </td>
            <td class="term term2"><!--ED2--><?php 
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-LTR.EDP-MOD 02%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--ED3--><?php 
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-LTR.EDP-MOD 03%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--ED4--><?php 
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-LTR.EDP-MOD 04%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M1
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-LTR-MOD 01%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M2--><?php 
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-LTR-MOD 02%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M3--><?php 
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-LTR-MOD 03%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M4--><?php 
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-LTR-MOD 04%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M5
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-LTR-MOD 05%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M6--><?php 
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-LTR-MOD 06%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M7--><?php 
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-LTR-MOD 07%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M8--><?php 
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-LTR-MOD 08%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M9
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-LTR-MOD 09%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M10--><?php 
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-LTR-MOD 10%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M11--><?php 
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-LTR-MOD 11%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M12--><?php 
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-LTR-MOD 12%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td><?php //Total
                echo round($jan_consump_total_LTR, 0);
              ?></td>

            <!--Fliptec@Q Mandarin-->
            <?php } if($type=="" || $type=="Fliptec@Q Mandarin"){ ?>
            <td class="term term1"><?php //M5
                $sql="SELECT sum(qty)  as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM05.MAND--QF2%') and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M6--><?php
                $sql="SELECT sum(qty)  as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM06.MAND--QF2%') and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M7--><?php
                $sql="SELECT sum(qty)  as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM07.MAND--QF2%') and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M8--><?php
                $sql="SELECT sum(qty)  as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM08.MAND--QF2%') and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M9
                $sql="SELECT sum(qty)  as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM09.MAND--QF3%') and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M10--><?php
                $sql="SELECT sum(qty)  as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM10.MAND--QF3%') and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M11--><?php
                $sql="SELECT sum(qty)  as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM11.MAND--QF3%') and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M12--><?php
                $sql="SELECT sum(qty)  as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM12.MAND--QF3%') and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td><?php //Total
                echo round($jan_consump_total_Fliptec, 0);
              ?></td>

            <!--English start-->
            <?php } if($type=="" || $type=="Beamind"){ ?>
            <td class="term term1"><?php //PREP A
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'INT-ENG GL 1-PREP A%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_English = $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--PREP B--><?php 
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'INT-ENG GL 1-PREP B%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M1--><?php
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 01%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M2--><?php
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 02%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M3
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 03%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M4--><?php
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 04%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M5--><?php
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 05%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M6--><?php
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 06%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M7
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 07%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M8--><?php
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 08%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M9--><?php
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 09%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M10--><?php
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 10%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td><?php //Total
                echo round($jan_consump_total_English, 0);
              ?></td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><?php //M1
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 01%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Mandarin = $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M2--><?php
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 02%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M3--><?php
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 03%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M4--><?php
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 04%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M5
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 05%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M6--><?php
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 06%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M7--><?php
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 07%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M8--><?php
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 08%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M9
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 09%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M10--><?php
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 10%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M11--><?php
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 11%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M12--><?php
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 12%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td><?php //Total
                echo round($jan_consump_total_Mandarin, 0);
              ?></td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><?php //M1
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 01%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Art = $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M2--><?php
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 02%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M3--><?php
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 03%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M4--><?php
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 04%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M5
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 05%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M6--><?php
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 06%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M7--><?php
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 07%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M8--><?php
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 08%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M9
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 09%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M10--><?php
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 10%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M11--><?php
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 11%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M12--><?php
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 12%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td><?php //Total
                echo round($jan_consump_total_Art, 0);
              ?></td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><?php //ACE02
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'STARTERS-MATH L1-ACE 02%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Math = $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M1--><?php 
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 01%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M2--><?php 
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 02%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M3--><?php 
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 03%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M4--><?php 
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 04%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M5--><?php 
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 05%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M6--><?php 
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 06%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M7
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 07%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M8--><?php 
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 08%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M9--><?php 
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 09%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M10--><?php 
                $sql="SELECT sum(qty)  as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 10%' and year(collection_date_time) = '$year' and month(collection_date_time) = 1 and centre_code='$centre_code' and void=0";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_consump_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td><?php //Total
                echo round($jan_consump_total_Math, 0);
              ?></td>
            <!--Math end-->
            <td><?php //Grand Total
                echo round($jan_consump_total_English + $jan_consump_total_Mandarin + $jan_consump_total_Art + $jan_consump_total_Math, 0);
              ?></td>
              <?php } ?>
          </tr>
          <tr class="term term1">
            <td>Balance c/f</td><!--january-->
            <?php if($type=="" || $type=="Termly Module"){ ?>
            <td class="term term1"><!--ED1-->
              <?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-EDP1.EARLY-DIS%') and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-EDP1.EARLY-DIS%') and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $jan_closing_total = round($row["qty"], 0) ;
                }
              ?>
            </td>
            <td class="term term2"><!--ED2--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-EDP2.EARLY-DIS%') and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-EDP2.EARLY-DIS%') and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--ED3--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-EDP3.EARLY-DIS%') and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-EDP3.EARLY-DIS%') and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--ED4--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-EDP4.EARLY-DIS%') and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-EDP4.EARLY-DIS%') and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M1
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE01----QF1%') and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE01----QF1%') and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M2--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE02----QF1%') and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE02----QF1%') and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M3--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE03----QF1%') and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE03----QF1%') and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M4--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE04----QF1%') and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE04----QF1%') and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M5
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE05----QF2%') and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE05----QF2%') and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M6--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE06----QF2%') and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE06----QF2%') and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M7--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE07----QF2%') and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE07----QF2%') and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M8--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE08----QF2%') and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE08----QF2%') and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M9
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE09----QF3%') and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE09----QF3%') and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M10--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE10----QF3%') and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE10----QF3%') and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M11--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE11----QF3%') and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE11----QF3%') and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M12--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE12----QF3%') and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=74 and product_code like 'MY-MODULE12----QF3%') and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td><?php //Total
                echo round($jan_closing_total, 0);
              ?></td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
              <?php } if($type=="" || $type=="LTR"){ ?>    
            <td class="term term1"><!--ED1-->
              <?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR.EDP-MOD 01%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR.EDP-MOD 01%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $jan_closing_total_LTR = round($row["qty"], 0) ;
                }
              ?>
            </td>
            <td class="term term2"><!--ED2--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR.EDP-MOD 02%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR.EDP-MOD 02%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--ED3--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR.EDP-MOD 03%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR.EDP-MOD 03%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--ED4--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR.EDP-MOD 04%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR.EDP-MOD 04%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M1
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 01%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 01%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M2--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 02%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 02%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M3--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 03%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 03%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M4--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 04%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 04%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M5
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 05%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 05%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M6--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 06%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 06%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M7--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 07%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 07%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M8--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 07%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 07%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M9
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 09%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 09%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M10--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 10%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 10%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M11--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 11%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 11%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M12--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 12%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 12%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td><?php //Total
                echo round($jan_closing_total_LTR, 0);
              ?></td>

            <!--Fliptec@Q Mandarin-->
            <?php } if($type=="" || $type=="Fliptec@Q Mandarin"){ ?>
            <td class="term term1"><?php //M5
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM05.MAND--QF2%') and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM05.MAND--QF2%') and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M6--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM06.MAND--QF2%') and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM06.MAND--QF2%') and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M7--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM07.MAND--QF2%') and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM07.MAND--QF2%') and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M8--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM08.MAND--QF2%') and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM08.MAND--QF2%') and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M9
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM09.MAND--QF3%') and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM09.MAND--QF3%') and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M10--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM10.MAND--QF3%') and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM10.MAND--QF3%') and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M11--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM11.MAND--QF3%') and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM11.MAND--QF3%') and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M12--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM12.MAND--QF3%') and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product`
 where category_id=76 and product_code like 'MY-ZHM12.MAND--QF3%') and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td><?php //Total
                echo round($jan_closing_total_Fliptec, 0);
              ?></td>

            <!--English start-->
            <?php } if($type=="" || $type=="Beamind"){ ?>
            <td class="term term1"><?php //PREP A
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-PREP A%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-PREP A%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_English = $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--PREP B--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-PREP B%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-PREP B%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M1--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 01%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 01%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M2--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 02%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 02%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M3
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 03%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 03%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M4--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 04%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 04%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M5--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 05%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 05%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M6--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 06%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 06%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M7
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 07%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 07%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M8--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 08%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 08%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M9--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 09%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 09%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M10--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 10%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 10%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_English += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td><?php //Total
                echo round($jan_closing_total_English, 0);
              ?></td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><?php //M1
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 01%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 01%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Mandarin = $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M2--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 02%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 02%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M3--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 03%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 03%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M4--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 04%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 04%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M5
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 05%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 05%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M6--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 06%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 06%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M7--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 07%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 07%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M8--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 08%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 08%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M9
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 09%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 09%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M10--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 10%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 10%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M11--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 11%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 11%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M12--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 12%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 12%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td><?php //Total
                echo round($jan_closing_total_Mandarin, 0);
              ?></td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><?php //M1
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 01%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 01%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Art = $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M2--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 02%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 02%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M3--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 03%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 03%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M4--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 04%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 04%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M5
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 05%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 05%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M6--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 06%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 06%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M7--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 07%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 07%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M8--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 08%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 08%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M9
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 09%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 09%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M10--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 10%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 10%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M11--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 11%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 11%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M12--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 12%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 12%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Art += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td><?php //Total
                echo round($jan_closing_total_Art, 0);
              ?></td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><?php //ACE02
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-ACE 02%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-ACE 02%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Math = $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M1--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 01%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 01%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M2--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 02%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 02%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M3--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 03%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 03%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M4--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 04%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 04%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M5--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 05%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 05%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M6--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 06%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 06%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><?php //M7
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 07%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 07%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M8--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 08%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 08%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M9--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 09%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 09%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M10--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 10%' and delivered_to_logistic_on<'$year/02/01' and delivered_to_logistic_by<>'' and 
(cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 10%' and CAST(collection_date_time AS DATE)<'$year/02/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $jan_closing_total_Math += $row["qty"];  echo round($row["qty"], 0);
                }
              ?></td>
            <td><?php //Total
                echo round($jan_closing_total_Math, 0);
              ?></td>
            <!--Math end-->
            <td><?php //Grand Total
                echo round($jan_closing_total_English + $jan_closing_total_Mandarin + $jan_closing_total_Art + $jan_closing_total_Math, 0);
              ?></td>
              <?php } ?>
          </tr>
        <!--january end-->
              </td>
        <!--December END-->
       
        <?php
    //     if ($num_row>0) {
    //     $bal=0;
    //     while ($row=mysqli_fetch_assoc($result)) {

    //        $bal=calcBal($centre_code, $row["product_code"], $from_date);

    //        if ($bal == 0) {
    //          $tr = '<tr class="uk-text-small uk-text-danger uk-text-bold" style="background-color:#ffebee">';
    //          $balance_html = '<span class="uk-text-danger uk-text-bold">'.$bal.'</span>';
    //        }else{
    //          $tr = '<tr class="uk-text-small">';
    //          $balance_html = '<span>'.$bal.'</span>';
    //        }
    //           echo $tr;
    //           echo "     <td>".$row["centre_code"]."</td>";
    //           echo "     <td>".$row["sub_category"]."</td>";
    //           echo "     <td>".$row["product_name"]."</td>";
    //           echo "     <td>".$balance_html."</td>";
    //           echo "  </tr>";
    //     }
    //     echo "</table>";
    //  } else {
    //     echo "No record found";
    //  }
  // } else {
  //    echo "$ori_from_date";
  // }
  ?>
  </div>
  </div>
  <script>
   $(document).ready(function() {
      var method = '<?php echo $method ?>';
      if (method == "print") {
         window.print();
      }
   });
</script>
<!-- <style>
  @media print{
   body{
       font-size:10%;
   }
   body {
      zoom:40%; /*or whatever percentage you need, play around with this number*/
    }
}
</style> -->
<style type="text/css" media="print">
    body {
      zoom:75%; /*or whatever percentage you need, play around with this number*/
    }
</style>
  <?php }?>