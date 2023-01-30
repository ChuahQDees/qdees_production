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
    //   $sql="SELECT DISTINCT o.product_code, p.product_name, p.sub_category, o.centre_code from `order` o, product p Where delivered_to_logistic_on<='$from_date 23:59:59' and delivered_to_logistic_by<>'' and cancelled_by='' and o.product_code=p.product_code";
    // } else {
      $sql="SELECT DISTINCT o.product_code, p.product_name, p.sub_category, o.centre_code from `order` o, product p Where delivered_to_logistic_on<='$from_date 23:59:59' and delivered_to_logistic_by<>'' and cancelled_by='' and o.centre_code='$centre_code' AND o.product_code=p.product_code";
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
          <td rowspan="3">No.</td>
          <td rowspan="3">Centre</td>
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
        <?php
        if($term=="" || $term=="term1"){
        if($CentreCode==""||$CentreCode=="All Centre"){
          $sql = "SELECT cs.* FROM `centre_status` cs inner join `centre` c on cs.id=c.centre_status_id group by cs.id";
        }else{
          $sql = "SELECT cs.* FROM `centre_status` cs inner join `centre` c on cs.id=c.centre_status_id where c.centre_code='$CentreCode' group by cs.id";
        }
        
        
        $resultf = mysqli_query($connection, $sql);
        
        while ($rowf=mysqli_fetch_assoc($resultf)) {
          $centre_status_id = $rowf["id"];
          if($CentreCode==""||$CentreCode=="All Centre"){
            $sql = "SELECT * FROM `centre` where centre_status_id = '$centre_status_id'";
          }else{
            $sql = "SELECT * FROM `centre` where centre_status_id = '$centre_status_id' and centre_code='$CentreCode' ";
          }
          //echo $sql;
          $resultc = mysqli_query($connection, $sql);
          $i=0;
          $term_1_ED1=0;
          $term_1_M1=0;
          $term_1_M5=0;
          $term_1_M9=0;
          $term_1_termly_module_total=0;

          $term_1_LTR_ED1=0;
          $term_1_LTR_M1=0;
          $term_1_LTR_M5=0;
          $term_1_LTR_M9=0;
          $term_1_LTR_total=0;

          $term_1_fliptec_M5=0;
          $term_1_fliptec_M9=0;
          $term_1_fliptec_total=0;
          
          $term_1_English_PREP_A=0;
          $term_1_English_M3=0;
          $term_1_English_M7=0;
          $term_1_English_total=0;

          $term_1_Mandarin_M1=0;
          $term_1_Mandarin_M5=0;
          $term_1_Mandarin_M9=0;
          $term_1_Mandarin_total=0;
          
          $term_1_Art_M1=0;
          $term_1_Art_M5=0;
          $term_1_Art_M9=0;
          $term_1_Art_total=0;

          $term_1_Math_ACE02=0;
          $term_1_Math_M7=0;
          $term_1_Math_total=0;
          
          $term_1_Beamind_grand_total=0;

          $term_2_ED2=0;
          $term_2_M2=0;
          $term_2_M6=0;
          $term_2_M10=0;
          $term_2_termly_module_total=0;

          $term_2_LTR_ED2=0;
          $term_2_LTR_M2=0;
          $term_2_LTR_M6=0;
          $term_2_LTR_M10=0;
          $term_2_LTR_total=0;

          $term_2_fliptec_M6=0;
          $term_2_fliptec_M10=0;
          $term_2_fliptec_total=0;
          
          $term_2_English_PREP_B=0;
          $term_2_English_M4=0;
          $term_2_English_M8=0;
          $term_2_English_total=0;

          $term_2_Mandarin_M2=0;
          $term_2_Mandarin_M6=0;
          $term_2_Mandarin_M10=0;
          $term_2_Mandarin_total=0;
          
          $term_2_Art_M2=0;
          $term_2_Art_M6=0;
          $term_2_Art_M10=0;
          $term_2_Art_total=0;

          $term_2_Math_M1=0;
          $term_2_Math_M4=0;
          $term_2_Math_M8=0;
          $term_2_Math_total=0;
          
          $term_2_Beamind_grand_total=0;

          $term_3_ED3=0;
          $term_3_M3=0;
          $term_3_M7=0;
          $term_3_M11=0;
          $term_3_termly_module_total=0;

          $term_3_LTR_ED3=0;
          $term_3_LTR_M3=0;
          $term_3_LTR_M7=0;
          $term_3_LTR_M11=0;
          $term_3_LTR_total=0;

          $term_3_fliptec_M7=0;
          $term_3_fliptec_M11=0;
          $term_3_fliptec_total=0;
          
          $term_3_English_M1=0;
          $term_3_English_M5=0;
          $term_3_English_M9=0;
          $term_3_English_total=0;

          $term_3_Mandarin_M3=0;
          $term_3_Mandarin_M7=0;
          $term_3_Mandarin_M11=0;
          $term_3_Mandarin_total=0;
          
          $term_3_Art_M3=0;
          $term_3_Art_M7=0;
          $term_3_Art_M11=0;
          $term_3_Art_total=0;

          $term_3_Math_M2=0;
          $term_3_Math_M5=0;
          $term_3_Math_M9=0;
          $term_3_Math_total=0;
          
          $term_3_Beamind_grand_total=0;


          $term_4_ED4=0;
          $term_4_M4=0;
          $term_4_M8=0;
          $term_4_M12=0;
          $term_4_termly_module_total=0;

          $term_4_LTR_ED4=0;
          $term_4_LTR_M4=0;
          $term_4_LTR_M8=0;
          $term_4_LTR_M12=0;
          $term_4_LTR_total=0;

          $term_4_fliptec_M8=0;
          $term_4_fliptec_M12=0;
          $term_4_fliptec_total=0;
          
          $term_4_English_M2=0;
          $term_4_English_M6=0;
          $term_4_English_M10=0;
          $term_4_English_total=0;

          $term_4_Mandarin_M4=0;
          $term_4_Mandarin_M8=0;
          $term_4_Mandarin_M12=0;
          $term_4_Mandarin_total=0;
          
          $term_4_Art_M4=0;
          $term_4_Art_M8=0;
          $term_4_Art_M12=0;
          $term_4_Art_total=0;

          $term_4_Math_M3=0;
          $term_4_Math_M6=0;
          $term_4_Math_M10=0;
          $term_4_Math_total=0;
          
          $term_4_Beamind_grand_total=0;
          while ($rowc=mysqli_fetch_assoc($resultc)) {
            $i++;
            $centre_code = $rowc["centre_code"];
            //echo $rowc["company_name"];

            $mar_closing_total=0;
            $mar_closing_total_LTR=0;
            $mar_closing_total_Fliptec=0;
            $mar_closing_total_English=0;
            $mar_closing_total_Mandarin=0;
            $mar_closing_total_Art=0;
            $mar_closing_total_Math=0;
           
        ?>
        <!--Term 1 start-->
          <tr class="term term1">
            <td><?php echo $i; ?></td></td>
            <td><?php echo $rowc["company_name"]; ?></td><!--March-->
            <?php if($type=="" || $type=="Termly Module"){ ?>
            <td class="term term1"><!--ED1-->
              <?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-EDP1.EARLY-DIS%') and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-EDP1.EARLY-DIS%') and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $mar_closing_total = round($row["qty"], 0) ;
                  $term_1_ED1 += round($row["qty"], 0) ;
                }
              ?>
            </td>
            <td class="term term2"><!--ED2--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-EDP2.EARLY-DIS%') and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-EDP2.EARLY-DIS%') and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $mar_closing_total += round($row["qty"], 0) ;
                  $term_2_ED2 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--ED3--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-EDP3.EARLY-DIS%') and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-EDP3.EARLY-DIS%') and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $mar_closing_total += round($row["qty"], 0) ;
                  $term_3_ED3 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--ED4--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-EDP4.EARLY-DIS%') and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-EDP4.EARLY-DIS%') and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $mar_closing_total += round($row["qty"], 0) ;
                  $term_4_ED4 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term1"><?php //M1
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE01----QF1%') and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE01----QF1%') and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total += $row["qty"];  echo round($row["qty"], 0);
                  $term_1_M1 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M2--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE02----QF1%') and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE02----QF1%') and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total += $row["qty"];  echo round($row["qty"], 0);
                  $term_2_M2 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M3--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE03----QF1%') and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE03----QF1%') and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total += $row["qty"];  echo round($row["qty"], 0);
                  $term_3_M3 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M4--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE04----QF1%') and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE04----QF1%') and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total += $row["qty"];  echo round($row["qty"], 0);
                  $term_4_M4 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term1"><?php //M5
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE05----QF2%') and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE05----QF2%') and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_M5 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M6--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE06----QF1%') and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE06----QF1%') and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total += $row["qty"];  echo round($row["qty"], 0);
                  $term_2_M6 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M7--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE07----QF1%') and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE07----QF1%') and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total += $row["qty"];  echo round($row["qty"], 0);
                  $term_3_M7 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M8--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE08----QF1%') and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE08----QF1%') and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total += $row["qty"];  echo round($row["qty"], 0);
                  $term_4_M8 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term1"><?php //M9
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE09----QF1%') and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE09----QF1%') and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total += $row["qty"];  echo round($row["qty"], 0);
                  $term_1_M9 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M10--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE10----QF1%') and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE10----QF1%') and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total += $row["qty"];  echo round($row["qty"], 0);
                  $term_2_M10 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M11--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE11----QF1%') and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE11----QF1%') and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total += $row["qty"];  echo round($row["qty"], 0);
                  $term_3_M11 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M12--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE12----QF1%') and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE12----QF1%') and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total += $row["qty"];  echo round($row["qty"], 0);
                  $term_4_M12 += round($row["qty"], 0) ;
                }
              ?></td>
            <td><?php //Total
                echo round($mar_closing_total, 0);
                $term_1_termly_module_total += round($mar_closing_total, 0) ;
              ?></td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
              <?php } if($type=="" || $type=="LTR"){ ?>
            <td class="term term1"><!--ED1-->
              <?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR.EDP-MOD 01%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR.EDP-MOD 01%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $mar_closing_total_LTR = round($row["qty"], 0) ;
                  $term_1_LTR_ED1 += round($row["qty"], 0) ;
                }
              ?>
            </td>
            <td class="term term2"><!--ED2--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR.EDP-MOD 02%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR.EDP-MOD 02%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $mar_closing_total_LTR += round($row["qty"], 0) ;
                  $term_2_LTR_ED2 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--ED3--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR.EDP-MOD 03%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR.EDP-MOD 03%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $mar_closing_total_LTR += round($row["qty"], 0) ;
                  $term_3_LTR_ED3 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--ED4--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR.EDP-MOD 04%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR.EDP-MOD 04%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $mar_closing_total_LTR += round($row["qty"], 0) ;
                  $term_4_LTR_ED4 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term1"><?php //M1
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 01%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 01%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                  $term_1_LTR_M1 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M2--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 02%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 02%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                  $term_2_LTR_M2 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M3--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 03%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 03%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                  $term_3_LTR_M3 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M4--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 04%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 04%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                  $term_4_LTR_M4 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term1"><?php //M5
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 05%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 05%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_LTR_M5 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M6--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 06%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 06%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                  $term_2_LTR_M6 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M7--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 07%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 07%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                  $term_3_LTR_M7 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M8--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 08%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 08%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                  $term_4_LTR_M8 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term1"><?php //M9
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 09%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 09%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                  $term_1_LTR_M9 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M10--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 010%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 010%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                  $term_2_LTR_M10 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M11--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 011%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 011%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                  $term_3_LTR_M11 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M12--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 12%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 12%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_LTR += $row["qty"];  echo round($row["qty"], 0);
                  $term_4_LTR_M12 += round($row["qty"], 0) ;
                }
              ?></td>
            <td><?php //Total
                echo round($mar_closing_total_LTR, 0);
                $term_1_LTR_total += $mar_closing_total_LTR ;
              ?></td>
          
            <!--Fliptec@Q Mandarin-->
            <?php } if($type=="" || $type=="Fliptec@Q Mandarin"){ ?>
            <td class="term term1"><?php //M5
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM05.MAND--QF2%') and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM05.MAND--QF2%') and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Fliptec = $row["qty"];  echo round($row["qty"], 0);
                  $term_1_fliptec_M5 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M6--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM06.MAND--QF2%') and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM06.MAND--QF2%') and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                  $term_2_fliptec_M6 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M7--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM07.MAND--QF2%') and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM07.MAND--QF2%') and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                  $term_3_fliptec_M7 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M8--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM08.MAND--QF2%') and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM08.MAND--QF2%') and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                  $term_4_fliptec_M8 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term1"><?php //M9
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM09.MAND--QF3%') and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM09.MAND--QF3%') and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                  $term_1_fliptec_M9 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M10--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM10.MAND--QF3%') and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM10.MAND--QF3%') and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                  $term_2_fliptec_M10 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M11--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM11.MAND--QF3%') and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM11.MAND--QF3%') and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                  $term_3_fliptec_M11 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M12--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM12.MAND--QF3%') and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM12.MAND--QF3%') and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Fliptec += $row["qty"];  echo round($row["qty"], 0);
                  $term_4_fliptec_M12 += round($row["qty"], 0) ;
                }
              ?></td>
            <td><?php //Total
                echo round($mar_closing_total_Fliptec, 0);
                $term_1_fliptec_total += $mar_closing_total_Fliptec ;
              ?></td>
            
            <!--English start-->
            <?php } if($type=="" || $type=="Beamind"){ ?>
            <td class="term term1"><?php //PREP A
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-PREP A%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-PREP A%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_English = $row["qty"];  echo round($row["qty"], 0);
                  $term_1_English_PREP_A += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--PREP B--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-PREP B%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-PREP B%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_English += $row["qty"];  echo round($row["qty"], 0);
                  $term_2_English_PREP_B += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M1--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 01%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 01%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_English += $row["qty"];  echo round($row["qty"], 0);
                  $term_3_English_M1 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M2--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 02%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 02%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_English += $row["qty"];  echo round($row["qty"], 0);
                  $term_4_English_M2 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term1"><?php //M3
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 03%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 03%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_English += $row["qty"];  echo round($row["qty"], 0);
                  $term_1_English_M3 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M4--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 04%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 04%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_English += $row["qty"];  echo round($row["qty"], 0);
                  $term_2_English_M4 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M5--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 05%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 05%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_English += $row["qty"];  echo round($row["qty"], 0);
                  $term_3_English_M5 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M6--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 06%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 06%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_English += $row["qty"];  echo round($row["qty"], 0);
                  $term_4_English_M6 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term1"><?php //M7
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 07%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 07%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_English += $row["qty"];  echo round($row["qty"], 0);
                  $term_1_English_M7 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M8--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 08%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 08%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_English += $row["qty"];  echo round($row["qty"], 0);
                  $term_2_English_M8 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M9--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 09%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 09%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_English += $row["qty"];  echo round($row["qty"], 0);
                  $term_3_English_M9 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M10--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 10%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 10%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_English += $row["qty"];  echo round($row["qty"], 0);
                  $term_4_English_M10 += round($row["qty"], 0) ;
                }
              ?></td>
            <td><?php //Total
                echo round($mar_closing_total_English, 0);
                $term_1_English_total += $mar_closing_total_English;
              ?></td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><?php //M1
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 01%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 01%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Mandarin = $row["qty"];  echo round($row["qty"], 0);
                  $term_1_Mandarin_M1 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M2--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 02%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 02%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                  $term_2_Mandarin_M2 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M3--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 03%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 03%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                  $term_3_Mandarin_M3 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M4--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 04%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 04%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                  $term_4_Mandarin_M4 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term1"><?php //M5
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 05%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 05%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                  $term_1_Mandarin_M5 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M6--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 06%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 06%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                  $term_2_Mandarin_M6 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M7--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 07%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 07%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                  $term_3_Mandarin_M7 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M8--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 08%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 08%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                  $term_4_Mandarin_M8 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term1"><?php //M9
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 09%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 09%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                  $term_1_Mandarin_M9 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M10--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 10%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 10%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                  $term_2_Mandarin_M10 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M11--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 11%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 11%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                  $term_3_Mandarin_M11 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M12--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 12%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 12%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Mandarin += $row["qty"];  echo round($row["qty"], 0);
                  $term_4_Mandarin_M12 += round($row["qty"], 0) ;
                }
              ?></td>
            <td><?php //Total
                echo round($mar_closing_total_Mandarin, 0);
                $term_1_Mandarin_total += $mar_closing_total_Mandarin;
              ?></td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><?php //M1
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 01%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 01%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Art = $row["qty"];  echo round($row["qty"], 0);
                  $term_1_Art_M1 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M2--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 02%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 02%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Art += $row["qty"];  echo round($row["qty"], 0);
                  $term_2_Art_M2 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M3--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 03%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 03%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Art += $row["qty"];  echo round($row["qty"], 0);
                  $term_3_Art_M3 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M4--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 04%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 04%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Art += $row["qty"];  echo round($row["qty"], 0);
                  $term_4_Art_M4 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term1"><?php //M5
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 05%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 05%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Art += $row["qty"];  echo round($row["qty"], 0);
                  $term_1_Art_M5 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M6--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 06%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 06%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Art += $row["qty"];  echo round($row["qty"], 0);
                  $term_2_Art_M6 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M7--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 07%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 07%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Art += $row["qty"];  echo round($row["qty"], 0);
                  $term_3_Art_M7 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M8--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 08%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 08%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Art += $row["qty"];  echo round($row["qty"], 0);
                  $term_4_Art_M8 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term1"><?php //M9
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 09%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 09%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Art += $row["qty"];  echo round($row["qty"], 0);
                  $term_1_Art_M9 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M10--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 10%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 10%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Art += $row["qty"];  echo round($row["qty"], 0);
                  $term_2_Art_M10 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M11--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 11%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 11%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Art += $row["qty"];  echo round($row["qty"], 0);
                  $term_3_Art_M11 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M12--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 12%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 12%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Art += $row["qty"];  echo round($row["qty"], 0);
                  $term_4_Art_M12 += round($row["qty"], 0) ;
                }
              ?></td>
            <td><?php //Total
                echo round($mar_closing_total_Art, 0);
                $term_1_Art_total += $mar_closing_total_Art;
              ?></td>
            <!--Art end-->
            <!--Math start--> 
            <td class="term term1"><?php //ACE02
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-ACE 02%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-ACE 02%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Math = $row["qty"];  echo round($row["qty"], 0);
                  $term_1_Math_ACE02 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M1--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 01%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 01%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Math += $row["qty"];  echo round($row["qty"], 0);
                  $term_2_Math_M1 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M2--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 02%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 02%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Math += $row["qty"];  echo round($row["qty"], 0);
                  $term_3_Math_M2 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M3--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 03%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 03%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Math += $row["qty"];  echo round($row["qty"], 0);
                  $term_4_Math_M3 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M4--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 04%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 04%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Math += $row["qty"];  echo round($row["qty"], 0);
                  $term_2_Math_M4 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M5--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 05%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 05%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Math += $row["qty"];  echo round($row["qty"], 0);
                  $term_3_Math_M5 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M6--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 06%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 06%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Math += $row["qty"];  echo round($row["qty"], 0);
                  $term_4_Math_M6 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term1"><?php //M7
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 07%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 07%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Math += $row["qty"];  echo round($row["qty"], 0);
                  $term_1_Math_M7 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M8--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 08%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 08%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Math += $row["qty"];  echo round($row["qty"], 0);
                  $term_2_Math_M8 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M9--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 09%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 09%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Math += $row["qty"];  echo round($row["qty"], 0);
                  $term_3_Math_M9 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M10--><?php
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 10%' and delivered_to_logistic_on<'$year/04/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 10%' and CAST(collection_date_time AS DATE)<'$year/04/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $mar_closing_total_Math += $row["qty"];  echo round($row["qty"], 0);
                  $term_4_Math_M10 += round($row["qty"], 0) ;
                }
              ?></td>
            <td><?php //Total
                echo round($mar_closing_total_Math, 0);
                $term_1_Math_total += $mar_closing_total_Math;
              ?></td>
            <!--Math end-->
            <td><?php //Grand Total
                echo round($mar_closing_total_English + $mar_closing_total_Mandarin + $mar_closing_total_Art + $mar_closing_total_Math, 0);
                $term_1_Beamind_grand_total += round($mar_closing_total_English + $mar_closing_total_Mandarin + $mar_closing_total_Art + $mar_closing_total_Math, 0);
              ?></td>
              <?php } ?>
          </tr>
        <!--Term 1 end-->
        <?php } ?>
        <!--Term 1 subtotal start-->
          <tr class="term term1" style="font-weight: bold;">
            <td></td></td>
            <td>Subtotal of <?php echo $rowf["name"]; ?></td><!--March-->
            <?php if($type=="" || $type=="Termly Module"){ ?>
            <td class="term term1"><!--ED1-->
              <?php 
                echo $term_1_ED1;
              ?>
            </td>
            <td class="term term2"><!--ED2--><?php 
                echo $term_2_ED2;
              ?></td>
            <td class="term term3"><!--ED3--><?php 
                echo $term_3_ED3;
              ?></td>
            <td class="term term4"><!--ED4--><?php 
                echo $term_4_ED4;
              ?></td>
            <td class="term term1"><?php //M1
                echo $term_1_M1;
              ?></td>
            <td class="term term2"><!--M2--><?php
                echo $term_2_M2;
              ?></td>
            <td class="term term3"><!--M3--><?php
                echo $term_3_M3;
              ?></td>
            <td class="term term4"><!--M4--><?php
                echo $term_4_M4;
              ?></td>
            <td class="term term1"><?php //M5
                echo $term_1_M5;
              ?></td>
            <td class="term term2"><!--M6--><?php
                echo $term_2_M6;
              ?></td>
            <td class="term term3"><!--M7--><?php
                echo $term_3_M7;
              ?></td>
            <td class="term term4"><!--M8--><?php
                echo $term_4_M8;
              ?></td>
            <td class="term term1"><?php //M9
                echo $term_1_M9;
              ?></td>
            <td class="term term2"><!--M10--><?php
                echo $term_2_M10;
              ?></td>
            <td class="term term3"><!--M11--><?php
                echo $term_3_M11;
              ?></td>
            <td class="term term4"><!--M12--><?php
                echo $term_4_M12;
              ?></td>
            <td><?php //Total
                echo $term_1_termly_module_total;
              ?></td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
              <?php } if($type=="" || $type=="LTR"){ ?>
            <td class="term term1"><!--ED1-->
              <?php 
                echo $term_1_LTR_ED1;
              ?>
            </td>
            <td class="term term2"><!--ED2--><?php 
                echo $term_2_LTR_ED2;
              ?></td>
            <td class="term term3"><!--ED3--><?php 
                echo $term_3_LTR_ED3;
              ?></td>
            <td class="term term4"><!--ED4--><?php 
                echo $term_4_LTR_ED4;
              ?></td>
            <td class="term term1"><?php //M1
                echo $term_1_LTR_M1;
              ?></td>
            <td class="term term2"><!--M2--><?php
                echo $term_2_LTR_M2;
              ?></td>
            <td class="term term3"><!--M3--><?php 
                echo $term_3_LTR_M3;
              ?></td>
            <td class="term term4"><!--M4--><?php 
                echo $term_4_LTR_M4;
              ?></td>
            <td class="term term1"><?php //M5
                echo $term_1_LTR_M5;
              ?></td>
            <td class="term term2"><!--M6--><?php 
                echo $term_2_LTR_M6;
              ?></td>
            <td class="term term3"><!--M7--><?php 
                echo $term_3_LTR_M7;
              ?></td>
            <td class="term term4"><!--M8--><?php 
                echo $term_4_LTR_M8;
              ?></td>
            <td class="term term1"><?php //M9
               echo $term_1_LTR_M9;
              ?></td>
            <td class="term term2"><!--M10--><?php 
                echo $term_2_LTR_M10;
              ?></td>
            <td class="term term3"><!--M11--><?php
                echo $term_3_LTR_M11;
              ?></td>
            <td class="term term4"><!--M12--><?php
                echo $term_4_LTR_M12;
              ?></td>
            <td><?php //Total
                echo $term_1_LTR_total;
              ?></td>

            <!--Fliptec@Q Mandarin-->
            <?php } if($type=="" || $type=="Fliptec@Q Mandarin"){ ?>
            <td class="term term1"><?php //M5
                echo $term_1_fliptec_M5;
              ?></td>
            <td class="term term2"><!--M6--><?php
                echo $term_2_fliptec_M6;
              ?></td>
            <td class="term term3"><!--M7--><?php
                echo $term_3_fliptec_M7;
              ?></td>
            <td class="term term4"><!--M8--><?php
                echo $term_4_fliptec_M8;
              ?></td>
            <td class="term term1"><?php //M9
                echo $term_1_fliptec_M9;
              ?></td>
            <td class="term term2"><!--M10--><?php
                echo $term_2_fliptec_M10;
              ?></td>
            <td class="term term3"><!--M11--><?php
                echo $term_3_fliptec_M11;
              ?></td>
            <td class="term term4"><!--M12--><?php
                echo $term_4_fliptec_M12;
              ?></td>
            <td><?php //Total
                echo $term_1_fliptec_total;
              ?></td>

            <!--English start-->
            <?php } if($type=="" || $type=="Beamind"){ ?>
            <td class="term term1"><?php //PREP A
                echo $term_1_English_PREP_A;
              ?></td>
            <td class="term term2"><!--PREP B--><?php
                echo $term_2_English_PREP_B;
              ?></td>
            <td class="term term3"><!--M1--><?php
                echo $term_3_English_M1;
              ?></td>
            <td class="term term4"><!--M2--><?php
                echo $term_4_English_M2;
              ?></td>
            <td class="term term1"><?php //M3
                echo $term_1_English_M3;
              ?></td>
            <td class="term term2"><!--M4--><?php
                echo $term_2_English_M4;
              ?></td>
            <td class="term term3"><!--M5--><?php
                echo $term_3_English_M5;
              ?></td>
            <td class="term term4"><!--M6--><?php
                echo $term_4_English_M6;
              ?></td>
            <td class="term term1"><?php //M7
                echo $term_1_English_M7;
              ?></td>
            <td class="term term2"><!--M8--><?php
                echo $term_2_English_M8;
              ?></td>
            <td class="term term3"><!--M9--><?php
                echo $term_3_English_M9;
              ?></td>
            <td class="term term4"><!--M10--><?php
                echo $term_4_English_M10;
              ?></td>
            <td><?php //Total
                echo $term_1_English_total;
              ?></td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><?php //M1
                echo $term_1_Mandarin_M1;
              ?></td>
            <td class="term term2"><!--M2--><?php
                echo $term_2_Mandarin_M2;
              ?></td>
            <td class="term term3"><!--M3--><?php
                echo $term_3_Mandarin_M3;
              ?></td>
            <td class="term term4"><!--M4--><?php
                echo $term_4_Mandarin_M4;
              ?></td>
            <td class="term term1"><?php //M5
                echo $term_1_Mandarin_M5;
              ?></td>
            <td class="term term2"><!--M6--><?php
                echo $term_2_Mandarin_M6;
              ?></td>
            <td class="term term3"><!--M7--><?php
                echo $term_3_Mandarin_M7;
              ?></td>
            <td class="term term4"><!--M8--><?php
                echo $term_4_Mandarin_M8;
              ?></td>
            <td class="term term1"><?php //M9
                echo $term_1_Mandarin_M9;
              ?></td>
            <td class="term term2"><!--M10--><?php
                echo $term_2_Mandarin_M10;
              ?></td>
            <td class="term term3"><!--M11--><?php
                echo $term_3_Mandarin_M11;
              ?></td>
            <td class="term term4"><!--M12--><?php
                echo $term_4_Mandarin_M12;
              ?></td>
            <td><?php //Total
                echo $term_1_Mandarin_total;
              ?></td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><?php //M1
                echo $term_1_Art_M1;
              ?></td>
            <td class="term term2"><!--M2--><?php
                echo $term_2_Art_M2;
              ?></td>
            <td class="term term3"><!--M3--><?php
                echo $term_3_Art_M3;
              ?></td>
            <td class="term term4"><!--M4--><?php
                echo $term_4_Art_M4;
              ?></td>
            <td class="term term1"><?php //M5
                echo $term_1_Art_M5;
              ?></td>
            <td class="term term2"><!--M6--><?php
                echo $term_2_Art_M6;
              ?></td>
            <td class="term term3"><!--M7--><?php
                echo $term_3_Art_M7;
              ?></td>
            <td class="term term4"><!--M8--><?php
                echo $term_4_Art_M8;
              ?></td>
            <td class="term term1"><?php //M9
                echo $term_1_Art_M9;
              ?></td>
            <td class="term term2"><!--M10--><?php
                echo $term_2_Art_M10;
              ?></td>
            <td class="term term3"><!--M11--><?php
                echo $term_3_Art_M11;
              ?></td>
            <td class="term term4"><!--M12--><?php
                echo $term_4_Art_M12;
              ?></td>
            <td><?php //Total
                echo $term_1_Art_total;
              ?></td>
            <!--Art end-->
            <!--Math start--> 
            <td class="term term1"><?php //ACE02
                echo $term_1_Math_ACE02;
              ?></td>
            <td class="term term2"><!--M1--><?php
                echo $term_2_Math_M1;
              ?></td>
            <td class="term term3"><!--M2--><?php
                echo $term_3_Math_M2;
              ?></td>
            <td class="term term4"><!--M3--><?php
                echo $term_4_Math_M3;
              ?></td>
            <td class="term term2"><!--M4--><?php
                echo $term_2_Math_M4;
              ?></td>
            <td class="term term3"><!--M5--><?php
                echo $term_3_Math_M5;
              ?></td>
            <td class="term term4"><!--M6--><?php
                echo $term_4_Math_M6;
              ?></td>
            <td class="term term1"><?php //M7
                echo $term_1_Math_M7;
              ?></td>
            <td class="term term2"><!--M8--><?php
                echo $term_2_Math_M8;
              ?></td>
            <td class="term term3"><!--M9--><?php
                echo $term_3_Math_M9;
              ?></td>
            <td class="term term4"><!--M10--><?php
                echo $term_4_Math_M10;
              ?></td>
            <td><?php //Total
                echo $term_1_Math_total;
              ?></td>
            <!--Math end-->
            <td><?php //Grand Total
                echo $term_1_Beamind_grand_total;
              ?></td>
              <?php } ?>
          </tr>
        <!--Term 1 subtotal end-->
        <?php } } ?>    
        <?php
        if($term=="" || $term=="term2"){
        if($CentreCode==""||$CentreCode=="All Centre"){
          $sql = "SELECT cs.* FROM `centre_status` cs inner join `centre` c on cs.id=c.centre_status_id group by cs.id";
        }else{
          $sql = "SELECT cs.* FROM `centre_status` cs inner join `centre` c on cs.id=c.centre_status_id where c.centre_code='$CentreCode' group by cs.id";
        }
        
        

        //sleep(15);
        $resultf = mysqli_query($connection, $sql);
        
        while ($rowf=mysqli_fetch_assoc($resultf)) {
          $centre_status_id = $rowf["id"];
          if($CentreCode==""||$CentreCode=="All Centre"){
            $sql = "SELECT * FROM `centre` where centre_status_id = '$centre_status_id'";
          }else{
            $sql = "SELECT * FROM `centre` where centre_status_id = '$centre_status_id' and centre_code='$CentreCode' ";
          }
          
          $resultc = mysqli_query($connection, $sql);
          $i=0;
          $term_1_ED1=0;
          $term_1_M1=0;
          $term_1_M5=0;
          $term_1_M9=0;
          $term_1_termly_module_total=0;

          $term_1_LTR_ED1=0;
          $term_1_LTR_M1=0;
          $term_1_LTR_M5=0;
          $term_1_LTR_M9=0;
          $term_1_LTR_total=0;

          $term_1_fliptec_M5=0;
          $term_1_fliptec_M9=0;
          $term_1_fliptec_total=0;
          
          $term_1_English_PREP_A=0;
          $term_1_English_M3=0;
          $term_1_English_M7=0;
          $term_1_English_total=0;

          $term_1_Mandarin_M1=0;
          $term_1_Mandarin_M5=0;
          $term_1_Mandarin_M9=0;
          $term_1_Mandarin_total=0;
          
          $term_1_Art_M1=0;
          $term_1_Art_M5=0;
          $term_1_Art_M9=0;
          $term_1_Art_total=0;

          $term_1_Math_ACE02=0;
          $term_1_Math_M7=0;
          $term_1_Math_total=0;
          
          $term_1_Beamind_grand_total=0;

          $term_2_ED2=0;
          $term_2_M2=0;
          $term_2_M6=0;
          $term_2_M10=0;
          $term_2_termly_module_total=0;

          $term_2_LTR_ED2=0;
          $term_2_LTR_M2=0;
          $term_2_LTR_M6=0;
          $term_2_LTR_M10=0;
          $term_2_LTR_total=0;

          $term_2_fliptec_M6=0;
          $term_2_fliptec_M10=0;
          $term_2_fliptec_total=0;
          
          $term_2_English_PREP_B=0;
          $term_2_English_M4=0;
          $term_2_English_M8=0;
          $term_2_English_total=0;

          $term_2_Mandarin_M2=0;
          $term_2_Mandarin_M6=0;
          $term_2_Mandarin_M10=0;
          $term_2_Mandarin_total=0;
          
          $term_2_Art_M2=0;
          $term_2_Art_M6=0;
          $term_2_Art_M10=0;
          $term_2_Art_total=0;

          $term_2_Math_M1=0;
          $term_2_Math_M4=0;
          $term_2_Math_M8=0;
          $term_2_Math_total=0;
          
          $term_2_Beamind_grand_total=0;

          $term_3_ED3=0;
          $term_3_M3=0;
          $term_3_M7=0;
          $term_3_M11=0;
          $term_3_termly_module_total=0;

          $term_3_LTR_ED3=0;
          $term_3_LTR_M3=0;
          $term_3_LTR_M7=0;
          $term_3_LTR_M11=0;
          $term_3_LTR_total=0;

          $term_3_fliptec_M7=0;
          $term_3_fliptec_M11=0;
          $term_3_fliptec_total=0;
          
          $term_3_English_M1=0;
          $term_3_English_M5=0;
          $term_3_English_M9=0;
          $term_3_English_total=0;

          $term_3_Mandarin_M3=0;
          $term_3_Mandarin_M7=0;
          $term_3_Mandarin_M11=0;
          $term_3_Mandarin_total=0;
          
          $term_3_Art_M3=0;
          $term_3_Art_M7=0;
          $term_3_Art_M11=0;
          $term_3_Art_total=0;

          $term_3_Math_M2=0;
          $term_3_Math_M5=0;
          $term_3_Math_M9=0;
          $term_3_Math_total=0;
          
          $term_3_Beamind_grand_total=0;


          $term_4_ED4=0;
          $term_4_M4=0;
          $term_4_M8=0;
          $term_4_M12=0;
          $term_4_termly_module_total=0;

          $term_4_LTR_ED4=0;
          $term_4_LTR_M4=0;
          $term_4_LTR_M8=0;
          $term_4_LTR_M12=0;
          $term_4_LTR_total=0;

          $term_4_fliptec_M8=0;
          $term_4_fliptec_M12=0;
          $term_4_fliptec_total=0;
          
          $term_4_English_M2=0;
          $term_4_English_M6=0;
          $term_4_English_M10=0;
          $term_4_English_total=0;

          $term_4_Mandarin_M4=0;
          $term_4_Mandarin_M8=0;
          $term_4_Mandarin_M12=0;
          $term_4_Mandarin_total=0;
          
          $term_4_Art_M4=0;
          $term_4_Art_M8=0;
          $term_4_Art_M12=0;
          $term_4_Art_total=0;

          $term_4_Math_M3=0;
          $term_4_Math_M6=0;
          $term_4_Math_M10=0;
          $term_4_Math_total=0;
          
          $term_4_Beamind_grand_total=0;
          
          
          while ($rowc=mysqli_fetch_assoc($resultc)) {
            $i++;
            $centre_code = $rowc["centre_code"];
            //echo $rowc["company_name"];

            $june_closing_total=0;
            $june_closing_total_LTR=0;
            $june_closing_total_Fliptec=0;
            $june_closing_total_English=0;
            $june_closing_total_Mandarin=0;
            $june_closing_total_Art=0;
            $june_closing_total_Math=0;
           
        ?>   
        <!--Term 2 start-->
          <tr class="term term2">
            <td><?php echo $i; ?></td>
            <td><?php echo getCentreName($centre_code); ?></td>
            <?php if($type=="" || $type=="Termly Module"){ ?>
            <td class="term term1"><!--ED1--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-EDP1.EARLY-DIS%') and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-EDP1.EARLY-DIS%') and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $june_closing_total += round($row["qty"], 0);
                  $term_1_ED1 += round($row["qty"], 0) ;

                }
              ?></td>
            <td class="term term2"><!--ED2--> <?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-EDP2.EARLY-DIS%') and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-EDP2.EARLY-DIS%') and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $june_closing_total += round($row["qty"], 0);
                  $term_2_ED2 += round($row["qty"], 0) ;

                }
              ?></td>
            <td class="term term3"><!--ED3--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-EDP3.EARLY-DIS%') and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-EDP3.EARLY-DIS%') and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $june_closing_total += round($row["qty"], 0);
                  $term_3_ED3 += round($row["qty"], 0) ;

                }
              ?></td>
            <td class="term term4"><!--ED4--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-EDP4.EARLY-DIS%') and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-EDP4.EARLY-DIS%') and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $june_closing_total += round($row["qty"], 0);
                  $term_4_ED4 += round($row["qty"], 0) ;

                }
              ?></td>
            <td class="term term1"><!--M1--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE01----QF1%') and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE01----QF1%') and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_M1 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M2--> <?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE02----QF1%') and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE02----QF1%') and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_M2 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M3--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE03----QF1%') and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE03----QF1%') and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_M3 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M4--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE04----QF1%') and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE04----QF1%') and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_M4 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term1"><!--M5--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE05----QF2%') and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE05----QF2%') and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_M5 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M6--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE06----QF2%') and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE06----QF2%') and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_M6 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M7--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE07----QF2%') and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE07----QF2%') and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_M7 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M8--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE08----QF2%') and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE08----QF2%') and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_M8 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term1"><!--M9--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE09----QF3%') and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE09----QF3%') and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_M9 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M10--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE10----QF3%') and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE10----QF3%') and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_M10 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M11--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE11----QF3%') and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE11----QF3%') and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_M11 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M12--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE12----QF3%') and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE12----QF3%') and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_M12 += round($row["qty"], 0) ;
                }
              ?></td>
            <td><!--Total--><?php echo round($june_closing_total, 0);
            $term_2_termly_module_total += round($june_closing_total, 0);
            ?></td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
              <?php } if($type=="" || $type=="LTR"){ ?>
            <td class="term term1"><!--ED1--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR.EDP-MOD 01%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR.EDP-MOD 01%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $june_closing_total_LTR += round($row["qty"], 0);
                  $term_1_LTR_ED1 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--ED2--> <?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR.EDP-MOD 02%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR.EDP-MOD 02%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $june_closing_total_LTR += round($row["qty"], 0);
                  $term_2_LTR_ED2 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--ED3--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR.EDP-MOD 03%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR.EDP-MOD 03%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $june_closing_total_LTR += round($row["qty"], 0);
                  $term_3_LTR_ED3 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--ED4--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR.EDP-MOD 04%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR.EDP-MOD 04%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $june_closing_total_LTR += round($row["qty"], 0);
                  $term_4_LTR_ED4 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term1"><!--M1--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 01%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 01%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_LTR_M1 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M2--> <?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 02%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 02%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_LTR_M2 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M3--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 03%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 03%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_LTR_M3 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M4--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 04%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 04%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_LTR_M4 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term1"><!--M5--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 05%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 05%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_LTR_M5 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M6--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 06%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 06%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_LTR_M6 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M7--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 07%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 07%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_LTR_M7 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M8--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 08%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 08%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_LTR_M8 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term1"><!--M9--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 09%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 09%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_LTR_M9 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M10--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 10%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 10%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_LTR_M10 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M11--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 11%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 11%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_LTR_M11 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M12--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 12%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 12%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_LTR_M12 += round($row["qty"], 0) ;
                }
              ?></td>
            <td><!--Total--><?php echo round($june_closing_total_LTR, 0);
            $term_2_LTR_total += round($june_closing_total_LTR, 0);
            ?></td>

            <!--Fliptec@Q Mandarin-->
            <?php } if($type=="" || $type=="Fliptec@Q Mandarin"){ ?>
            <td class="term term1"><!--M5--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM05.MAND--QF2%') and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM05.MAND--QF2%') and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Fliptec += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_fliptec_M5 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M6--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM06.MAND--QF2%') and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM06.MAND--QF2%') and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Fliptec += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_fliptec_M6 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M7--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM07.MAND--QF2%') and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM07.MAND--QF2%') and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Fliptec += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_fliptec_M7 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M8--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM08.MAND--QF2%') and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM08.MAND--QF2%') and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Fliptec += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_fliptec_M8 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term1"><!--M9--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM09.MAND--QF2%') and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM09.MAND--QF2%') and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Fliptec += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_fliptec_M9 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M10--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM10.MAND--QF3%') and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM10.MAND--QF3%') and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Fliptec += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_fliptec_M10 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M11--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM11.MAND--QF2%') and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM11.MAND--QF2%') and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Fliptec += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_fliptec_M11 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M12--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM12.MAND--QF2%') and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM12.MAND--QF2%') and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
                //echo $sql;
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Fliptec += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_fliptec_M12 += round($row["qty"], 0) ;
                }
              ?></td>
            <td><!--Total--><?php echo round($june_closing_total_Fliptec, 0);
            $term_2_fliptec_total += $june_closing_total_Fliptec ;
            ?></td>

            <!--English start-->
            <?php } if($type=="" || $type=="Beamind"){ ?>
            <td class="term term1"><!--PREP A--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-PREP A%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-PREP A%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_English += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_English_PREP_A += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--PREP B--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-PREP B%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-PREP B%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_English += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_English_PREP_B += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M1--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 01%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 01%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_English += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_English_M1 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M2--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 02%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 02%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_English += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_English_M2 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term1"><!--M3--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 03%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 03%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_English += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_English_M3 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M4--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 04%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 04%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_English += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_English_M4 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M5--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 05%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 05%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_English += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_English_M5 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M6--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 06%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 06%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_English += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_English_M6 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term1"><!--M7--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 07%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 07%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_English += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_English_M7 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M8--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 08%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 08%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_English += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_English_M8 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M9--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 09%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 09%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_English += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_English_M9 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M10--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 10%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 10%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_English += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_English_M10 += round($row["qty"], 0) ;
                }
              ?></td>
            <td><!--Total--><?php echo round($june_closing_total_English, 0);
            $term_2_English_total += round($june_closing_total_English, 0);
            ?></td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 01%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 01%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Mandarin += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_Mandarin_M1 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M2--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 02%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 02%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Mandarin += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_Mandarin_M2 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M3--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 03%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 03%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Mandarin += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_Mandarin_M3 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M4--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 04%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 04%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Mandarin += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_Mandarin_M4 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term1"><!--M5--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 05%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 05%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Mandarin += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_Mandarin_M5 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M6--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 06%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 06%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Mandarin += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_Mandarin_M6 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M7--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 07%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 07%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Mandarin += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_Mandarin_M7 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M8--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 08%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 08%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Mandarin += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_Mandarin_M8 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term1"><!--M9--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 09%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 09%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Mandarin += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_Mandarin_M9 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M10--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 10%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 10%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Mandarin += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_Mandarin_M10 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M11--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 11%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 11%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Mandarin += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_Mandarin_M11 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M12--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 12%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 12%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Mandarin += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_Mandarin_M12 += round($row["qty"], 0) ;
                }
              ?></td>
            <td><!--Total--><?php echo round($june_closing_total_Mandarin, 0);
            $term_2_Mandarin_total += round($june_closing_total_Mandarin, 0);
            ?></td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 01%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 01%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Art += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_Art_M1 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M2--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 02%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 02%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Art += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_Art_M2 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M3--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 03%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 03%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Art += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_Art_M3 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M4--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 04%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 04%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Art += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_Art_M4 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term1"><!--M5--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 05%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 05%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Art += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_Art_M5 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M6--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 06%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 06%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Art += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_Art_M6 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M7--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 07%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 07%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Art += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_Art_M7 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M8--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 08%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 08%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Art += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_Art_M8 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term1"><!--M9--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 09%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 09%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Art += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_Art_M9 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M10--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 10%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 10%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Art += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_Art_M10 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M11--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 11%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 11%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Art += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_Art_M11 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M12--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 12%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 12%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Art += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_Art_M12 += round($row["qty"], 0) ;
                }
              ?></td>
            <td><!--Total--><?php echo round($june_closing_total_Art, 0);
            $term_2_Art_total += round($june_closing_total_Art, 0);
            ?></td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-ACE 02%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-ACE 02%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Math += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_Math_ACE02 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M1--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 01%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 01%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Math += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_Math_M1 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M2--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 02%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 02%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Math += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_Math_M2 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M3--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 03%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 03%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Math += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_Math_M3 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M4--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 04%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 04%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Math += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_Math_M4 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M5--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 05%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 05%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Math += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_Math_M5 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M6--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 06%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 06%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Math += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_Math_M6 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term1"><!--M7--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 07%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 07%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Math += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_Math_M7 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term2"><!--M8--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 08%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 08%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Math += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_Math_M8 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term3"><!--M9--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 09%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 09%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Math += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_Math_M9 += round($row["qty"], 0) ;
                }
              ?></td>
            <td class="term term4"><!--M10--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 10%' and delivered_to_logistic_on<'$year/07/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 10%' and CAST(collection_date_time AS DATE)<'$year/07/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $june_closing_total_Math += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_Math_M10 += round($row["qty"], 0) ;
                }
              ?></td>
            <td><!--Total--><?php echo round($june_closing_total_Math, 0);
            $term_2_Math_total += round($june_closing_total_Math, 0);
            ?></td>
            <!--Math end-->
            <td><?php //Grand Total
                echo round($june_closing_total_English + $june_closing_total_Mandarin + $june_closing_total_Art + $june_closing_total_Math, 0);
                $term_2_Beamind_grand_total += round($june_closing_total_English + $june_closing_total_Mandarin + $june_closing_total_Art + $june_closing_total_Math, 0);
              ?></td>
              <?php } ?>
          </tr>
        <!--Term 2 END-->
        <?php } ?>
        <!--Term 2 subtotal start-->
          <tr class="term term2" style="font-weight: bold;">
            <td></td>
            <td>Subtotal of <?php echo $rowf["name"]; ?></td>
            <?php if($type=="" || $type=="Termly Module"){ ?>
              <td class="term term1"><!--ED1-->
              <?php 
                echo $term_1_ED1;
              ?>
              </td>
              <td class="term term2"><!--ED2--><?php 
                  echo $term_2_ED2;
                ?></td>
              <td class="term term3"><!--ED3--><?php 
                  echo $term_3_ED3;
                ?></td>
              <td class="term term4"><!--ED4--><?php 
                  echo $term_4_ED4;
                ?></td>
              <td class="term term1"><?php //M1
                  echo $term_1_M1;
                ?></td>
              <td class="term term2"><!--M2--><?php
                  echo $term_2_M2;
                ?></td>
              <td class="term term3"><!--M3--><?php
                  echo $term_3_M3;
                ?></td>
              <td class="term term4"><!--M4--><?php
                  echo $term_4_M4;
                ?></td>
              <td class="term term1"><?php //M5
                  echo $term_1_M5;
                ?></td>
              <td class="term term2"><!--M6--><?php
                  echo $term_2_M6;
                ?></td>
              <td class="term term3"><!--M7--><?php
                  echo $term_3_M7;
                ?></td>
              <td class="term term4"><!--M8--><?php
                  echo $term_4_M8;
                ?></td>
              <td class="term term1"><?php //M9
                  echo $term_1_M9;
                ?></td>
              <td class="term term2"><!--M10--><?php
                  echo $term_2_M10;
                ?></td>
              <td class="term term3"><!--M11--><?php
                  echo $term_3_M11;
                ?></td>
              <td class="term term4"><!--M12--><?php
                  echo $term_4_M12;
                ?></td>
            <td><!--Total--><?php echo $term_2_termly_module_total; ?></td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
              <?php } if($type=="" || $type=="LTR"){ ?>
                <td class="term term1"><!--ED1-->
              <?php 
                echo $term_1_LTR_ED1;
              ?>
            </td>
            <td class="term term2"><!--ED2--><?php 
                echo $term_2_LTR_ED2;
              ?></td>
            <td class="term term3"><!--ED3--><?php 
                echo $term_3_LTR_ED3;
              ?></td>
            <td class="term term4"><!--ED4--><?php 
                echo $term_4_LTR_ED4;
              ?></td>
            <td class="term term1"><?php //M1
                echo $term_1_LTR_M1;
              ?></td>
            <td class="term term2"><!--M2--><?php
                echo $term_2_LTR_M2;
              ?></td>
            <td class="term term3"><!--M3--><?php 
                echo $term_3_LTR_M3;
              ?></td>
            <td class="term term4"><!--M4--><?php 
                echo $term_4_LTR_M4;
              ?></td>
            <td class="term term1"><?php //M5
                echo $term_1_LTR_M5;
              ?></td>
            <td class="term term2"><!--M6--><?php 
                echo $term_2_LTR_M6;
              ?></td>
            <td class="term term3"><!--M7--><?php 
                echo $term_3_LTR_M7;
              ?></td>
            <td class="term term4"><!--M8--><?php 
                echo $term_4_LTR_M8;
              ?></td>
            <td class="term term1"><?php //M9
               echo $term_1_LTR_M9;
              ?></td>
            <td class="term term2"><!--M10--><?php 
                echo $term_2_LTR_M10;
              ?></td>
            <td class="term term3"><!--M11--><?php
                echo $term_3_LTR_M11;
              ?></td>
            <td class="term term4"><!--M12--><?php
                echo $term_4_LTR_M12;
              ?></td>
            <td><!--Total--><?php echo $term_2_LTR_total;?></td>

            <!--Fliptec@Q Mandarin-->
            <?php } if($type=="" || $type=="Fliptec@Q Mandarin"){ ?>
              <td class="term term1"><?php //M5
                echo $term_1_fliptec_M5;
              ?></td>
            <td class="term term2"><!--M6--><?php
                echo $term_2_fliptec_M6;
              ?></td>
            <td class="term term3"><!--M7--><?php
                echo $term_3_fliptec_M7;
              ?></td>
            <td class="term term4"><!--M8--><?php
                echo $term_4_fliptec_M8;
              ?></td>
            <td class="term term1"><?php //M9
                echo $term_1_fliptec_M9;
              ?></td>
            <td class="term term2"><!--M10--><?php
                echo $term_2_fliptec_M10;
              ?></td>
            <td class="term term3"><!--M11--><?php
                echo $term_3_fliptec_M11;
              ?></td>
            <td class="term term4"><!--M12--><?php
                echo $term_4_fliptec_M12;
              ?></td>
            <td><!--Total--><?php echo $term_2_fliptec_total;?></td>

            <!--English start-->
            <?php } if($type=="" || $type=="Beamind"){ ?>
              <td class="term term1"><?php //PREP A
                echo $term_1_English_PREP_A;
              ?></td>
            <td class="term term2"><!--PREP B--><?php
                echo $term_2_English_PREP_B;
              ?></td>
            <td class="term term3"><!--M1--><?php
                echo $term_3_English_M1;
              ?></td>
            <td class="term term4"><!--M2--><?php
                echo $term_4_English_M2;
              ?></td>
            <td class="term term1"><?php //M3
                echo $term_1_English_M3;
              ?></td>
            <td class="term term2"><!--M4--><?php
                echo $term_2_English_M4;
              ?></td>
            <td class="term term3"><!--M5--><?php
                echo $term_3_English_M5;
              ?></td>
            <td class="term term4"><!--M6--><?php
                echo $term_4_English_M6;
              ?></td>
            <td class="term term1"><?php //M7
                echo $term_1_English_M7;
              ?></td>
            <td class="term term2"><!--M8--><?php
                echo $term_2_English_M8;
              ?></td>
            <td class="term term3"><!--M9--><?php
                echo $term_3_English_M9;
              ?></td>
            <td class="term term4"><!--M10--><?php
                echo $term_4_English_M10;
              ?></td>
            <td><!--Total--><?php echo $term_2_English_total;?></td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><?php //M1
                echo $term_1_Mandarin_M1;
              ?></td>
            <td class="term term2"><!--M2--><?php
                echo $term_2_Mandarin_M2;
              ?></td>
            <td class="term term3"><!--M3--><?php
                echo $term_3_Mandarin_M3;
              ?></td>
            <td class="term term4"><!--M4--><?php
                echo $term_4_Mandarin_M4;
              ?></td>
            <td class="term term1"><?php //M5
                echo $term_1_Mandarin_M5;
              ?></td>
            <td class="term term2"><!--M6--><?php
                echo $term_2_Mandarin_M6;
              ?></td>
            <td class="term term3"><!--M7--><?php
                echo $term_3_Mandarin_M7;
              ?></td>
            <td class="term term4"><!--M8--><?php
                echo $term_4_Mandarin_M8;
              ?></td>
            <td class="term term1"><?php //M9
                echo $term_1_Mandarin_M9;
              ?></td>
            <td class="term term2"><!--M10--><?php
                echo $term_2_Mandarin_M10;
              ?></td>
            <td class="term term3"><!--M11--><?php
                echo $term_3_Mandarin_M11;
              ?></td>
            <td class="term term4"><!--M12--><?php
                echo $term_4_Mandarin_M12;
              ?></td>
            <td><!--Total--><?php echo $term_2_Mandarin_total;?></td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><?php //M1
                echo $term_1_Art_M1;
              ?></td>
            <td class="term term2"><!--M2--><?php
                echo $term_2_Art_M2;
              ?></td>
            <td class="term term3"><!--M3--><?php
                echo $term_3_Art_M3;
              ?></td>
            <td class="term term4"><!--M4--><?php
                echo $term_4_Art_M4;
              ?></td>
            <td class="term term1"><?php //M5
                echo $term_1_Art_M5;
              ?></td>
            <td class="term term2"><!--M6--><?php
                echo $term_2_Art_M6;
              ?></td>
            <td class="term term3"><!--M7--><?php
                echo $term_3_Art_M7;
              ?></td>
            <td class="term term4"><!--M8--><?php
                echo $term_4_Art_M8;
              ?></td>
            <td class="term term1"><?php //M9
                echo $term_1_Art_M9;
              ?></td>
            <td class="term term2"><!--M10--><?php
                echo $term_2_Art_M10;
              ?></td>
            <td class="term term3"><!--M11--><?php
                echo $term_3_Art_M11;
              ?></td>
            <td class="term term4"><!--M12--><?php
                echo $term_4_Art_M12;
              ?></td>
            <td><!--Total--><?php echo $term_2_Art_total;?></td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><?php //ACE02
                echo $term_1_Math_ACE02;
              ?></td>
            <td class="term term2"><!--M1--><?php
                echo $term_2_Math_M1;
              ?></td>
            <td class="term term3"><!--M2--><?php
                echo $term_3_Math_M2;
              ?></td>
            <td class="term term4"><!--M3--><?php
                echo $term_4_Math_M3;
              ?></td>
            <td class="term term2"><!--M4--><?php
                echo $term_2_Math_M4;
              ?></td>
            <td class="term term3"><!--M5--><?php
                echo $term_3_Math_M5;
              ?></td>
            <td class="term term4"><!--M6--><?php
                echo $term_4_Math_M6;
              ?></td>
            <td class="term term1"><?php //M7
                echo $term_1_Math_M7;
              ?></td>
            <td class="term term2"><!--M8--><?php
                echo $term_2_Math_M8;
              ?></td>
            <td class="term term3"><!--M9--><?php
                echo $term_3_Math_M9;
              ?></td>
            <td class="term term4"><!--M10--><?php
                echo $term_4_Math_M10;
              ?></td>
            <td><!--Total--><?php echo $term_2_Math_total; ?></td>
            <!--Math end-->
            <td><?php //Grand Total
                echo $term_2_Beamind_grand_total;
              ?></td>
              <?php } ?>
          </tr>
        <!--Term 2 subtotal END-->
        <?php } } ?>
        <?php
        if($term=="" || $term=="term3"){
        if($CentreCode==""||$CentreCode=="All Centre"){
          $sql = "SELECT cs.* FROM `centre_status` cs inner join `centre` c on cs.id=c.centre_status_id group by cs.id";
        }else{
          $sql = "SELECT cs.* FROM `centre_status` cs inner join `centre` c on cs.id=c.centre_status_id where c.centre_code='$CentreCode' group by cs.id";
        }
        
        //sleep(30);
        $resultf = mysqli_query($connection, $sql);
        
        
        while ($rowf=mysqli_fetch_assoc($resultf)) {
          $centre_status_id = $rowf["id"];
          if($CentreCode==""||$CentreCode=="All Centre"){
            $sql = "SELECT * FROM `centre` where centre_status_id = '$centre_status_id'";
          }else{
            $sql = "SELECT * FROM `centre` where centre_status_id = '$centre_status_id' and centre_code='$CentreCode' ";
          }
          
          $resultc = mysqli_query($connection, $sql);
          $i=0;
          $term_1_ED1=0;
          $term_1_M1=0;
          $term_1_M5=0;
          $term_1_M9=0;
          $term_1_termly_module_total=0;

          $term_1_LTR_ED1=0;
          $term_1_LTR_M1=0;
          $term_1_LTR_M5=0;
          $term_1_LTR_M9=0;
          $term_1_LTR_total=0;

          $term_1_fliptec_M5=0;
          $term_1_fliptec_M9=0;
          $term_1_fliptec_total=0;
          
          $term_1_English_PREP_A=0;
          $term_1_English_M3=0;
          $term_1_English_M7=0;
          $term_1_English_total=0;

          $term_1_Mandarin_M1=0;
          $term_1_Mandarin_M5=0;
          $term_1_Mandarin_M9=0;
          $term_1_Mandarin_total=0;
          
          $term_1_Art_M1=0;
          $term_1_Art_M5=0;
          $term_1_Art_M9=0;
          $term_1_Art_total=0;

          $term_1_Math_ACE02=0;
          $term_1_Math_M7=0;
          $term_1_Math_total=0;
          
          $term_1_Beamind_grand_total=0;

          $term_2_ED2=0;
          $term_2_M2=0;
          $term_2_M6=0;
          $term_2_M10=0;
          $term_2_termly_module_total=0;

          $term_2_LTR_ED2=0;
          $term_2_LTR_M2=0;
          $term_2_LTR_M6=0;
          $term_2_LTR_M10=0;
          $term_2_LTR_total=0;

          $term_2_fliptec_M6=0;
          $term_2_fliptec_M10=0;
          $term_2_fliptec_total=0;
          
          $term_2_English_PREP_B=0;
          $term_2_English_M4=0;
          $term_2_English_M8=0;
          $term_2_English_total=0;

          $term_2_Mandarin_M2=0;
          $term_2_Mandarin_M6=0;
          $term_2_Mandarin_M10=0;
          $term_2_Mandarin_total=0;
          
          $term_2_Art_M2=0;
          $term_2_Art_M6=0;
          $term_2_Art_M10=0;
          $term_2_Art_total=0;

          $term_2_Math_M1=0;
          $term_2_Math_M4=0;
          $term_2_Math_M8=0;
          $term_2_Math_total=0;
          
          $term_2_Beamind_grand_total=0;

          $term_3_ED3=0;
          $term_3_M3=0;
          $term_3_M7=0;
          $term_3_M11=0;
          $term_3_termly_module_total=0;

          $term_3_LTR_ED3=0;
          $term_3_LTR_M3=0;
          $term_3_LTR_M7=0;
          $term_3_LTR_M11=0;
          $term_3_LTR_total=0;

          $term_3_fliptec_M7=0;
          $term_3_fliptec_M11=0;
          $term_3_fliptec_total=0;
          
          $term_3_English_M1=0;
          $term_3_English_M5=0;
          $term_3_English_M9=0;
          $term_3_English_total=0;

          $term_3_Mandarin_M3=0;
          $term_3_Mandarin_M7=0;
          $term_3_Mandarin_M11=0;
          $term_3_Mandarin_total=0;
          
          $term_3_Art_M3=0;
          $term_3_Art_M7=0;
          $term_3_Art_M11=0;
          $term_3_Art_total=0;

          $term_3_Math_M2=0;
          $term_3_Math_M5=0;
          $term_3_Math_M9=0;
          $term_3_Math_total=0;
          
          $term_3_Beamind_grand_total=0;


          $term_4_ED4=0;
          $term_4_M4=0;
          $term_4_M8=0;
          $term_4_M12=0;
          $term_4_termly_module_total=0;

          $term_4_LTR_ED4=0;
          $term_4_LTR_M4=0;
          $term_4_LTR_M8=0;
          $term_4_LTR_M12=0;
          $term_4_LTR_total=0;

          $term_4_fliptec_M8=0;
          $term_4_fliptec_M12=0;
          $term_4_fliptec_total=0;
          
          $term_4_English_M2=0;
          $term_4_English_M6=0;
          $term_4_English_M10=0;
          $term_4_English_total=0;

          $term_4_Mandarin_M4=0;
          $term_4_Mandarin_M8=0;
          $term_4_Mandarin_M12=0;
          $term_4_Mandarin_total=0;
          
          $term_4_Art_M4=0;
          $term_4_Art_M8=0;
          $term_4_Art_M12=0;
          $term_4_Art_total=0;

          $term_4_Math_M3=0;
          $term_4_Math_M6=0;
          $term_4_Math_M10=0;
          $term_4_Math_total=0;
          
          $term_4_Beamind_grand_total=0;
          
          
          while ($rowc=mysqli_fetch_assoc($resultc)) {
            $i++;
            $centre_code = $rowc["centre_code"];
            //echo $rowc["company_name"];

            $september_closing_total=0;
            $september_closing_total_LTR=0;
            $september_closing_total_Fliptec=0;
            $september_closing_total_English=0;
            $september_closing_total_Mandarin=0;
            $september_closing_total_Art=0;
            $september_closing_total_Math=0;
           
        ?>
        <!--Term 3 start-->
          <tr class="term term3">
            <td><?php echo $i; ?></td>
            <td><?php echo getCentreName($centre_code); ?></td>
            <?php if($type=="" || $type=="Termly Module"){ ?>
            <td class="term term1"><!--ED1--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-EDP1.EARLY-DIS%') and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-EDP1.EARLY-DIS%') and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $september_closing_total += round($row["qty"], 0);
                  $term_1_ED1 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--ED2--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-EDP2.EARLY-DIS%') and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-EDP2.EARLY-DIS%') and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $september_closing_total += round($row["qty"], 0);
                  $term_2_ED2 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--ED3--> <?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-EDP3.EARLY-DIS%') and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-EDP3.EARLY-DIS%') and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $september_closing_total += round($row["qty"], 0);
                  $term_3_ED3 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--ED4--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-EDP4.EARLY-DIS%') and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-EDP4.EARLY-DIS%') and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $september_closing_total += round($row["qty"], 0);
                  $term_4_ED4 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><!--M1--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE01----QF1%') and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE01----QF1%') and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_M1 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M2--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE02----QF1%') and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE02----QF1%') and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_M2 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M3--> <?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE03----QF1%') and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE03----QF1%') and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_M3 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M4--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE04----QF1%') and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE04----QF1%') and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_M4 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><!--M5--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE05----QF2%') and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE05----QF2%') and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_M5 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M6--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE06----QF2%') and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE06----QF2%') and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_M6 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M7--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE07----QF2%') and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE07----QF2%') and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_M7 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M8--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE08----QF2%') and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE08----QF2%') and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_M8 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><!--M9--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE09----QF3%') and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE09----QF3%') and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_M9 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M10--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE10----QF3%') and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE10----QF3%') and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_M10 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M11--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE11----QF3%') and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE11----QF3%') and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_M11 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M12--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE12----QF3%') and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE12----QF3%') and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_M12 += round($row["qty"], 0);
                }
              ?></td>
            <td><!--Total--><?php echo round($september_closing_total, 0);
            $term_3_termly_module_total  += round($september_closing_total, 0);
            ?></td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
              <?php } if($type=="" || $type=="LTR"){ ?>
            <td class="term term1"><!--ED1--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR.EDP-MOD 01%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR.EDP-MOD 01%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $september_closing_total_LTR += round($row["qty"], 0);
                  $term_1_LTR_ED1 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--ED2--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR.EDP-MOD 02%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR.EDP-MOD 02%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $september_closing_total_LTR += round($row["qty"], 0);
                  $term_2_LTR_ED2 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--ED3--> <?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR.EDP-MOD 03%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR.EDP-MOD 03%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $september_closing_total_LTR += round($row["qty"], 0);
                  $term_3_LTR_ED3 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--ED4--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR.EDP-MOD 04%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR.EDP-MOD 04%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $september_closing_total_LTR += round($row["qty"], 0);
                  $term_4_LTR_ED4 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><!--M1--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 01%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 01%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_LTR_M1 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M2--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 02%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 02%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_LTR_M2 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M3--> <?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 03%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 03%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_LTR_M3 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M4--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 04%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 04%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_LTR_M4 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><!--M5--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 05%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 05%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_LTR_M5 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M6--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 06%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 06%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_LTR_M6 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M7--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 07%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 07%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_LTR_M7 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M8--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 08%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 08%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_LTR_M8 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><!--M9--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 09%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 09%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_LTR_M9 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M10--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 10%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 10%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_LTR_M10 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M11--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 11%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 11%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_LTR_M11 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M12--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 12%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 12%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_LTR_M12 += round($row["qty"], 0);
                }
              ?></td>
            <td><!--Total--><?php echo round($september_closing_total_LTR, 0);
            $term_3_LTR_total += round($september_closing_total_LTR, 0);
            ?></td>

            <!--Fliptec@Q Mandarin-->
            <?php } if($type=="" || $type=="Fliptec@Q Mandarin"){ ?>
            <td class="term term1"><!--M5--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM05.MAND--QF2%') and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM05.MAND--QF2%') and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Fliptec += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_fliptec_M5 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M6--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM06.MAND--QF2%') and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM06.MAND--QF2%') and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Fliptec += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_fliptec_M6 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M7--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM07.MAND--QF2%') and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM07.MAND--QF2%') and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Fliptec += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_fliptec_M7 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M8--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM08.MAND--QF2%') and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM08.MAND--QF2%') and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Fliptec += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_fliptec_M8 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><!--M9--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM09.MAND--QF2%') and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM09.MAND--QF2%') and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Fliptec += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_fliptec_M9 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M10--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM10.MAND--QF2%') and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM10.MAND--QF2%') and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Fliptec += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_fliptec_M10 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M11--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM11.MAND--QF3%') and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM11.MAND--QF3%') and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Fliptec += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_fliptec_M11 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M12--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM12.MAND--QF2%') and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM12.MAND--QF2%') and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Fliptec += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_fliptec_M12 += round($row["qty"], 0);
                }
              ?></td>
            <td><!--Total--><?php echo round($september_closing_total_Fliptec, 0);
            $term_3_fliptec_total += round($september_closing_total_Fliptec, 0);
            ?></td>

            <!--English start-->
            <?php } if($type=="" || $type=="Beamind"){ ?>
            <td class="term term1"><!--PREP A--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-PREP A%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-PREP A%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_English += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_English_PREP_A += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--PREP B--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-PREP B%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-PREP B%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_English += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_English_PREP_B += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M1--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 01%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 01%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_English += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_English_M1 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M2--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 02%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 02%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_English += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_English_M2 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><!--M3--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 03%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 03%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_English += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_English_M3 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M4--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 04%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 04%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_English += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_English_M4 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M5--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 05%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 05%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_English += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_English_M5 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M6--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 06%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 06%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_English += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_English_M6 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><!--M7--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 07%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 07%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_English += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_English_M7 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M8--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 08%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 08%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_English += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_English_M8 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M9--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 09%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 09%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_English += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_English_M9 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M10--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 10%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 10%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_English += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_English_M10 += round($row["qty"], 0);
                }
              ?></td>
            <td><!--Total--><?php echo round($september_closing_total_English, 0);
            $term_3_English_total += round($september_closing_total_English, 0);
            ?></td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 01%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 01%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Mandarin += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_Mandarin_M1 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M2--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 02%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 02%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Mandarin += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_Mandarin_M2 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M3--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 03%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 03%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Mandarin += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_Mandarin_M3 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M4--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 04%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 04%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Mandarin += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_Mandarin_M4 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><!--M5--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 05%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 05%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Mandarin += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_Mandarin_M5 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M6--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 06%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 06%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Mandarin += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_Mandarin_M6 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M7--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 07%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 07%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Mandarin += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_Mandarin_M7 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M8--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 08%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 08%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Mandarin += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_Mandarin_M8 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><!--M9--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 09%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 09%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Mandarin += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_Mandarin_M9 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M10--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 10%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 10%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Mandarin += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_Mandarin_M10 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M11--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 11%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 11%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Mandarin += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_Mandarin_M11 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M12--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 12%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 12%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Mandarin += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_Mandarin_M12 += round($row["qty"], 0);
                }
              ?></td>
            <td><!--Total--><?php echo round($september_closing_total_Mandarin, 0);
            $term_3_Mandarin_total += round($september_closing_total_Mandarin, 0);
            ?></td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 01%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 01%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Art += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_Art_M1 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M2--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 02%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 02%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Art += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_Art_M2 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M3--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 03%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 03%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Art += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_Art_M3 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M4--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 04%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 04%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Art += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_Art_M4 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><!--M5--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 05%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 05%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Art += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_Art_M5 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M6--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 06%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 06%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Art += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_Art_M6 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M7--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 07%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 07%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Art += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_Art_M7 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M8--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 08%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 08%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Art += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_Art_M8 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><!--M9--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 09%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 09%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Art += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_Art_M9 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M10--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 10%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 10%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Art += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_Art_M10 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M11--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 11%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MODs 11%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Art += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_Art_M11 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M12--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 12%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 12%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Art += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_Art_M12 += round($row["qty"], 0);
                }
              ?></td>
            <td><!--Total--><?php echo round($september_closing_total_Art, 0);
            $term_3_Art_total += round($september_closing_total_Art, 0);
            ?></td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-ACE 02%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-ACE 02%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Math += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_Math_ACE02 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M1--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 01%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 01%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Math += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_Math_M1 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M2--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 02%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 02%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Math += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_Math_M2 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M3--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 03%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 03%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Math += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_Math_M3 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M4--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 04%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 04%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Math += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_Math_M4 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M5--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 05%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 05%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Math += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_Math_M5 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M6--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 06%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 06%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Math += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_Math_M6 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><!--M7--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 07%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 07%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Math += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_Math_M7 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M8--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 08%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 08%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Math += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_Math_M8 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M9--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 09%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 09%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Math += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_Math_M9 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M10--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 10%' and delivered_to_logistic_on<'$year/10/01' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 10%' and CAST(collection_date_time AS DATE)<'$year/10/01' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $september_closing_total_Math += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_Math_M10 += round($row["qty"], 0);
                }
              ?></td>
            <td><!--Total--><?php echo round($september_closing_total_Math, 0);
            $term_3_Math_total += round($september_closing_total_Math, 0);
            ?></td>
            <!--Math end-->
            <td><?php //Grand Total
                echo round($september_closing_total_English + $september_closing_total_Mandarin + $september_closing_total_Art + $september_closing_total_Math, 0);
                $term_3_Beamind_grand_total += round($september_closing_total_English + $september_closing_total_Mandarin + $september_closing_total_Art + $september_closing_total_Math, 0);
              ?></td>
              <?php } ?>
          </tr>
        <!--Term 3 END-->
        <?php } ?>
        <!--Term 3 subtotal start-->
          <tr class="term term3" style="font-weight: bold;">
            <td></td>
            <td>Subtotal of <?php echo $rowf["name"]; ?></td>
            <?php if($type=="" || $type=="Termly Module"){ ?>
              <td class="term term1"><!--ED1-->
              <?php 
                echo $term_1_ED1;
              ?>
            </td>
            <td class="term term2"><!--ED2--><?php 
                echo $term_2_ED2;
              ?></td>
            <td class="term term3"><!--ED3--><?php 
                echo $term_3_ED3;
              ?></td>
            <td class="term term4"><!--ED4--><?php 
                echo $term_4_ED4;
              ?></td>
            <td class="term term1"><?php //M1
                echo $term_1_M1;
              ?></td>
            <td class="term term2"><!--M2--><?php
                echo $term_2_M2;
              ?></td>
            <td class="term term3"><!--M3--><?php
                echo $term_3_M3;
              ?></td>
            <td class="term term4"><!--M4--><?php
                echo $term_4_M4;
              ?></td>
            <td class="term term1"><?php //M5
                echo $term_1_M5;
              ?></td>
            <td class="term term2"><!--M6--><?php
                echo $term_2_M6;
              ?></td>
            <td class="term term3"><!--M7--><?php
                echo $term_3_M7;
              ?></td>
            <td class="term term4"><!--M8--><?php
                echo $term_4_M8;
              ?></td>
            <td class="term term1"><?php //M9
                echo $term_1_M9;
              ?></td>
            <td class="term term2"><!--M10--><?php
                echo $term_2_M10;
              ?></td>
            <td class="term term3"><!--M11--><?php
                echo $term_3_M11;
              ?></td>
            <td class="term term4"><!--M12--><?php
                echo $term_4_M12;
              ?></td>
            <td><!--Total--><?php echo $term_3_termly_module_total;?></td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
              <?php } if($type=="" || $type=="LTR"){ ?>
                <td class="term term1"><!--ED1-->
              <?php 
                echo $term_1_LTR_ED1;
              ?>
            </td>
            <td class="term term2"><!--ED2--><?php 
                echo $term_2_LTR_ED2;
              ?></td>
            <td class="term term3"><!--ED3--><?php 
                echo $term_3_LTR_ED3;
              ?></td>
            <td class="term term4"><!--ED4--><?php 
                echo $term_4_LTR_ED4;
              ?></td>
            <td class="term term1"><?php //M1
                echo $term_1_LTR_M1;
              ?></td>
            <td class="term term2"><!--M2--><?php
                echo $term_2_LTR_M2;
              ?></td>
            <td class="term term3"><!--M3--><?php 
                echo $term_3_LTR_M3;
              ?></td>
            <td class="term term4"><!--M4--><?php 
                echo $term_4_LTR_M4;
              ?></td>
            <td class="term term1"><?php //M5
                echo $term_1_LTR_M5;
              ?></td>
            <td class="term term2"><!--M6--><?php 
                echo $term_2_LTR_M6;
              ?></td>
            <td class="term term3"><!--M7--><?php 
                echo $term_3_LTR_M7;
              ?></td>
            <td class="term term4"><!--M8--><?php 
                echo $term_4_LTR_M8;
              ?></td>
            <td class="term term1"><?php //M9
               echo $term_1_LTR_M9;
              ?></td>
            <td class="term term2"><!--M10--><?php 
                echo $term_2_LTR_M10;
              ?></td>
            <td class="term term3"><!--M11--><?php
                echo $term_3_LTR_M11;
              ?></td>
            <td class="term term4"><!--M12--><?php
                echo $term_4_LTR_M12;
              ?></td>
            <td><!--Total--><?php echo $term_3_LTR_total;?></td>

            <!--Fliptec@Q Mandarin-->
            <?php } if($type=="" || $type=="Fliptec@Q Mandarin"){ ?>
              <td class="term term1"><?php //M5
                echo $term_1_fliptec_M5;
              ?></td>
            <td class="term term2"><!--M6--><?php
                echo $term_2_fliptec_M6;
              ?></td>
            <td class="term term3"><!--M7--><?php
                echo $term_3_fliptec_M7;
              ?></td>
            <td class="term term4"><!--M8--><?php
                echo $term_4_fliptec_M8;
              ?></td>
            <td class="term term1"><?php //M9
                echo $term_1_fliptec_M9;
              ?></td>
            <td class="term term2"><!--M10--><?php
                echo $term_2_fliptec_M10;
              ?></td>
            <td class="term term3"><!--M11--><?php
                echo $term_3_fliptec_M11;
              ?></td>
            <td class="term term4"><!--M12--><?php
                echo $term_4_fliptec_M12;
              ?></td>
            <td><!--Total--><?php echo $term_3_fliptec_total;?></td>

            <!--English start-->
            <?php } if($type=="" || $type=="Beamind"){ ?>
              <td class="term term1"><?php //PREP A
                echo $term_1_English_PREP_A;
              ?></td>
            <td class="term term2"><!--PREP B--><?php
                echo $term_2_English_PREP_B;
              ?></td>
            <td class="term term3"><!--M1--><?php
                echo $term_3_English_M1;
              ?></td>
            <td class="term term4"><!--M2--><?php
                echo $term_4_English_M2;
              ?></td>
            <td class="term term1"><?php //M3
                echo $term_1_English_M3;
              ?></td>
            <td class="term term2"><!--M4--><?php
                echo $term_2_English_M4;
              ?></td>
            <td class="term term3"><!--M5--><?php
                echo $term_3_English_M5;
              ?></td>
            <td class="term term4"><!--M6--><?php
                echo $term_4_English_M6;
              ?></td>
            <td class="term term1"><?php //M7
                echo $term_1_English_M7;
              ?></td>
            <td class="term term2"><!--M8--><?php
                echo $term_2_English_M8;
              ?></td>
            <td class="term term3"><!--M9--><?php
                echo $term_3_English_M9;
              ?></td>
            <td class="term term4"><!--M10--><?php
                echo $term_4_English_M10;
              ?></td>
            <td><!--Total--><?php echo $term_3_English_total;?></td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><?php //M1
                echo $term_1_Mandarin_M1;
              ?></td>
            <td class="term term2"><!--M2--><?php
                echo $term_2_Mandarin_M2;
              ?></td>
            <td class="term term3"><!--M3--><?php
                echo $term_3_Mandarin_M3;
              ?></td>
            <td class="term term4"><!--M4--><?php
                echo $term_4_Mandarin_M4;
              ?></td>
            <td class="term term1"><?php //M5
                echo $term_1_Mandarin_M5;
              ?></td>
            <td class="term term2"><!--M6--><?php
                echo $term_2_Mandarin_M6;
              ?></td>
            <td class="term term3"><!--M7--><?php
                echo $term_3_Mandarin_M7;
              ?></td>
            <td class="term term4"><!--M8--><?php
                echo $term_4_Mandarin_M8;
              ?></td>
            <td class="term term1"><?php //M9
                echo $term_1_Mandarin_M9;
              ?></td>
            <td class="term term2"><!--M10--><?php
                echo $term_2_Mandarin_M10;
              ?></td>
            <td class="term term3"><!--M11--><?php
                echo $term_3_Mandarin_M11;
              ?></td>
            <td class="term term4"><!--M12--><?php
                echo $term_4_Mandarin_M12;
              ?></td>
            <td><!--Total--><?php echo $term_3_Mandarin_total;?></td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><?php //M1
                echo $term_1_Art_M1;
              ?></td>
            <td class="term term2"><!--M2--><?php
                echo $term_2_Art_M2;
              ?></td>
            <td class="term term3"><!--M3--><?php
                echo $term_3_Art_M3;
              ?></td>
            <td class="term term4"><!--M4--><?php
                echo $term_4_Art_M4;
              ?></td>
            <td class="term term1"><?php //M5
                echo $term_1_Art_M5;
              ?></td>
            <td class="term term2"><!--M6--><?php
                echo $term_2_Art_M6;
              ?></td>
            <td class="term term3"><!--M7--><?php
                echo $term_3_Art_M7;
              ?></td>
            <td class="term term4"><!--M8--><?php
                echo $term_4_Art_M8;
              ?></td>
            <td class="term term1"><?php //M9
                echo $term_1_Art_M9;
              ?></td>
            <td class="term term2"><!--M10--><?php
                echo $term_2_Art_M10;
              ?></td>
            <td class="term term3"><!--M11--><?php
                echo $term_3_Art_M11;
              ?></td>
            <td class="term term4"><!--M12--><?php
                echo $term_4_Art_M12;
              ?></td>
            <td><!--Total--><?php echo $term_3_Art_total;?></td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><?php //ACE02
                echo $term_1_Math_ACE02;
              ?></td>
            <td class="term term2"><!--M1--><?php
                echo $term_2_Math_M1;
              ?></td>
            <td class="term term3"><!--M2--><?php
                echo $term_3_Math_M2;
              ?></td>
            <td class="term term4"><!--M3--><?php
                echo $term_4_Math_M3;
              ?></td>
            <td class="term term2"><!--M4--><?php
                echo $term_2_Math_M4;
              ?></td>
            <td class="term term3"><!--M5--><?php
                echo $term_3_Math_M5;
              ?></td>
            <td class="term term4"><!--M6--><?php
                echo $term_4_Math_M6;
              ?></td>
            <td class="term term1"><?php //M7
                echo $term_1_Math_M7;
              ?></td>
            <td class="term term2"><!--M8--><?php
                echo $term_2_Math_M8;
              ?></td>
            <td class="term term3"><!--M9--><?php
                echo $term_3_Math_M9;
              ?></td>
            <td class="term term4"><!--M10--><?php
                echo $term_4_Math_M10;
              ?></td>
            <td><!--Total--><?php echo $term_3_Math_total;?></td>
            <!--Math end-->
            <td><?php //Grand Total
                echo $term_3_Beamind_grand_total;
              ?></td>
              <?php } ?>
          </tr>
        <!--Term 3 subtotal END-->
        <?php } } ?>
        <?php
        if($term=="" || $term=="term4"){
        if($CentreCode==""||$CentreCode=="All Centre"){
          $sql = "SELECT cs.* FROM `centre_status` cs inner join `centre` c on cs.id=c.centre_status_id group by cs.id";
        }else{
          $sql = "SELECT cs.* FROM `centre_status` cs inner join `centre` c on cs.id=c.centre_status_id where c.centre_code='$CentreCode' group by cs.id";
        }
        
        
        $resultf = mysqli_query($connection, $sql);
        
        while ($rowf=mysqli_fetch_assoc($resultf)) {
          $centre_status_id = $rowf["id"];
          if($CentreCode==""||$CentreCode=="All Centre"){
            $sql = "SELECT * FROM `centre` where centre_status_id = '$centre_status_id'";
          }else{
            $sql = "SELECT * FROM `centre` where centre_status_id = '$centre_status_id' and centre_code='$CentreCode' ";
          }
          
          $resultc = mysqli_query($connection, $sql);
          $i=0;
          $term_1_ED1=0;
          $term_1_M1=0;
          $term_1_M5=0;
          $term_1_M9=0;
          $term_1_termly_module_total=0;

          $term_1_LTR_ED1=0;
          $term_1_LTR_M1=0;
          $term_1_LTR_M5=0;
          $term_1_LTR_M9=0;
          $term_1_LTR_total=0;

          $term_1_fliptec_M5=0;
          $term_1_fliptec_M9=0;
          $term_1_fliptec_total=0;
          
          $term_1_English_PREP_A=0;
          $term_1_English_M3=0;
          $term_1_English_M7=0;
          $term_1_English_total=0;

          $term_1_Mandarin_M1=0;
          $term_1_Mandarin_M5=0;
          $term_1_Mandarin_M9=0;
          $term_1_Mandarin_total=0;
          
          $term_1_Art_M1=0;
          $term_1_Art_M5=0;
          $term_1_Art_M9=0;
          $term_1_Art_total=0;

          $term_1_Math_ACE02=0;
          $term_1_Math_M7=0;
          $term_1_Math_total=0;
          
          $term_1_Beamind_grand_total=0;

          $term_2_ED2=0;
          $term_2_M2=0;
          $term_2_M6=0;
          $term_2_M10=0;
          $term_2_termly_module_total=0;

          $term_2_LTR_ED2=0;
          $term_2_LTR_M2=0;
          $term_2_LTR_M6=0;
          $term_2_LTR_M10=0;
          $term_2_LTR_total=0;

          $term_2_fliptec_M6=0;
          $term_2_fliptec_M10=0;
          $term_2_fliptec_total=0;
          
          $term_2_English_PREP_B=0;
          $term_2_English_M4=0;
          $term_2_English_M8=0;
          $term_2_English_total=0;

          $term_2_Mandarin_M2=0;
          $term_2_Mandarin_M6=0;
          $term_2_Mandarin_M10=0;
          $term_2_Mandarin_total=0;
          
          $term_2_Art_M2=0;
          $term_2_Art_M6=0;
          $term_2_Art_M10=0;
          $term_2_Art_total=0;

          $term_2_Math_M1=0;
          $term_2_Math_M4=0;
          $term_2_Math_M8=0;
          $term_2_Math_total=0;
          
          $term_2_Beamind_grand_total=0;

          $term_3_ED3=0;
          $term_3_M3=0;
          $term_3_M7=0;
          $term_3_M11=0;
          $term_3_termly_module_total=0;

          $term_3_LTR_ED3=0;
          $term_3_LTR_M3=0;
          $term_3_LTR_M7=0;
          $term_3_LTR_M11=0;
          $term_3_LTR_total=0;

          $term_3_fliptec_M7=0;
          $term_3_fliptec_M11=0;
          $term_3_fliptec_total=0;
          
          $term_3_English_M1=0;
          $term_3_English_M5=0;
          $term_3_English_M9=0;
          $term_3_English_total=0;

          $term_3_Mandarin_M3=0;
          $term_3_Mandarin_M7=0;
          $term_3_Mandarin_M11=0;
          $term_3_Mandarin_total=0;
          
          $term_3_Art_M3=0;
          $term_3_Art_M7=0;
          $term_3_Art_M11=0;
          $term_3_Art_total=0;

          $term_3_Math_M2=0;
          $term_3_Math_M5=0;
          $term_3_Math_M9=0;
          $term_3_Math_total=0;
          
          $term_3_Beamind_grand_total=0;


          $term_4_ED4=0;
          $term_4_M4=0;
          $term_4_M8=0;
          $term_4_M12=0;
          $term_4_termly_module_total=0;

          $term_4_LTR_ED4=0;
          $term_4_LTR_M4=0;
          $term_4_LTR_M8=0;
          $term_4_LTR_M12=0;
          $term_4_LTR_total=0;

          $term_4_fliptec_M8=0;
          $term_4_fliptec_M12=0;
          $term_4_fliptec_total=0;
          
          $term_4_English_M2=0;
          $term_4_English_M6=0;
          $term_4_English_M10=0;
          $term_4_English_total=0;

          $term_4_Mandarin_M4=0;
          $term_4_Mandarin_M8=0;
          $term_4_Mandarin_M12=0;
          $term_4_Mandarin_total=0;
          
          $term_4_Art_M4=0;
          $term_4_Art_M8=0;
          $term_4_Art_M12=0;
          $term_4_Art_total=0;

          $term_4_Math_M3=0;
          $term_4_Math_M6=0;
          $term_4_Math_M10=0;
          $term_4_Math_total=0;
          
          $term_4_Beamind_grand_total=0;
          

          while ($rowc=mysqli_fetch_assoc($resultc)) {
            $i++;
            $centre_code = $rowc["centre_code"];
            //echo $rowc["company_name"];

            $december_closing_total=0;
            $december_closing_total_LTR=0;
            $december_closing_total_Fliptec=0;
            $december_closing_total_English=0;
            $december_closing_total_Mandarin=0;
            $december_closing_total_Art=0;
            $december_closing_total_Math=0;
           
        ?>
        <!--Term 4 start-->
          <tr class="term term4">
            <td><?php echo $i; ?></td>
            <td><?php echo getCentreName($centre_code); ?></td>
            <?php if($type=="" || $type=="Termly Module"){ ?>
            <td class="term term1"><!--ED1--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-EDP1.EARLY-DIS%') and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-EDP1.EARLY-DIS%') and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $december_closing_total += round($row["qty"], 0);
                  $term_1_ED1 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--ED2--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-EDP2.EARLY-DIS%') and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-EDP2.EARLY-DIS%') and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $december_closing_total += round($row["qty"], 0);
                  $term_2_ED2 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--ED3--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-EDP3.EARLY-DIS%') and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-EDP31.EARLY-DIS%') and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $december_closing_total += round($row["qty"], 0);
                  $term_3_ED3 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--ED4--> <?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-EDP4.EARLY-DIS%') and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-EDP4.EARLY-DIS%') and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $december_closing_total += round($row["qty"], 0);
                  $term_4_ED4 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><!--M1--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE01----QF1%') and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE01----QF1%') and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_M1 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M2--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE02----QF1%') and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE02----QF1%') and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_M2 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M3--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE03----QF1%') and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE03----QF1%') and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_M3 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M4--> <?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE04----QF1%') and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE04----QF1%') and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_M4 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><!--M5--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE05----QF2%') and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE05----QF2%') and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_M5 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M6--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE06----QF2%') and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE06----QF2%') and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_M6 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M7--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE07----QF2%') and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE07----QF2%') and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_M7 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M8--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE08----QF2%') and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE08----QF2%') and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_M8 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><!--M9--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE09----QF3%') and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE09----QF3%') and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_M9 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M10--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE10----QF3%') and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE10----QF3%') and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_M10 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M11--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE11----QF3%') and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE11----QF3%') and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_M11 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M12--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE12----QF3%') and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=74 and product_code like 'MY-MODULE12----QF3%') and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_M12 += round($row["qty"], 0);
                }
              ?></td>
            <td><!--Total--><?php echo round($december_closing_total, 0);
            $term_4_termly_module_total += round($december_closing_total, 0);
            ?></td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
              <?php } if($type=="" || $type=="LTR"){ ?>
            <td class="term term1"><!--ED1--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR.EDP-MOD 01%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR.EDP-MOD 01%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $december_closing_total_LTR += round($row["qty"], 0);
                  $term_1_LTR_ED1 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--ED2--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR.EDP-MOD 02%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR.EDP-MOD 02%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $december_closing_total_LTR += round($row["qty"], 0);
                  $term_2_LTR_ED2 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--ED3--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR.EDP-MOD 03%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR.EDP-MOD 03%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $december_closing_total_LTR += round($row["qty"], 0);
                  $term_3_LTR_ED3 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--ED4--> <?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR.EDP-MOD 04%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR.EDP-MOD 04%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  echo $december_closing_total_LTR += round($row["qty"], 0);
                  $term_4_LTR_ED4 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><!--M1--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 01%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 01%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_LTR_M1 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M2--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 02%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 02%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_LTR_M2 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M3--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 03%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 03%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_LTR_M3 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M4--> <?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 04%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 04%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_LTR_M4 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><!--M5--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 05%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 05%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_LTR_M5 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M6--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 06%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 06%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_LTR_M6 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M7--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 07%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 07%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_LTR_M7 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M8--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 08%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 08%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_LTR_M8 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><!--M9--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 09%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 09%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_LTR_M9 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M10--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 10%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 10%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_LTR_M10 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M11--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 11%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 11%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_LTR_M11 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M12--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-LTR-MOD 12%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-LTR-MOD 12%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_LTR += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_LTR_M12 += round($row["qty"], 0);
                }
              ?></td>
            <td><!--Total--><?php echo round($december_closing_total_LTR, 0);
            $term_4_LTR_total += round($december_closing_total_LTR, 0);
            ?></td>

            <!--Fliptec@Q Mandarin-->
            <?php } if($type=="" || $type=="Fliptec@Q Mandarin"){ ?>
            <td class="term term1"><!--M5--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM05.MAND--QF2%') and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM05.MAND--QF2%') and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Fliptec += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_fliptec_M5 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M6--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM06.MAND--QF2%') and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM06.MAND--QF2%') and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Fliptec += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_fliptec_M6 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M7--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM07.MAND--QF2%') and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM07.MAND--QF2%') and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Fliptec += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_fliptec_M7 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M8--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM08.MAND--QF2%') and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM08.MAND--QF2%') and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Fliptec += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_fliptec_M8 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><!--M9--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM09.MAND--QF2%') and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM09.MAND--QF2%') and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Fliptec += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_fliptec_M9 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M10--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM10.MAND--QF2%') and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM10.MAND--QF2%') and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Fliptec += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_fliptec_M10 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M11--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM11.MAND--QF2%') and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM11.MAND--QF2%') and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Fliptec += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_fliptec_M11 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M12--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM12.MAND--QF3%') and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code in(SELECT product_code FROM `product` where category_id=76 and product_code like 'MY-ZHM12.MAND--QF3%') and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Fliptec += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_fliptec_M12 += round($row["qty"], 0);
                }
              ?></td>
            <td><!--Total--><?php echo round($december_closing_total_Fliptec, 0);
            $term_4_fliptec_total += round($december_closing_total_Fliptec, 0);
            ?></td>

            <!--English start-->
            <?php } if($type=="" || $type=="Beamind"){ ?>
            <td class="term term1"><!--PREP A--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-PREP A%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-PREP A%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_English += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_English_PREP_A += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--PREP B--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-PREP B%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-PREP B%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_English += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_English_PREP_B += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M1--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 01%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 01%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_English += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_English_M1 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M2--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 02%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 02%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_English += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_English_M2 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><!--M3--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 03%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 03%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_English += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_English_M3 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M4--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 04%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 04%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_English += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_English_M4 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M5--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 05%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 05%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_English += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_English_M5 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M6--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 06%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 06%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_English += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_English_M6 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><!--M7--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 07%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 07%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_English += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_English_M7 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M8--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 08%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 08%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_English += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_English_M8 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M9--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 09%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 09%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_English += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_English_M9 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M10--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'INT-ENG GL 1-MOD 10%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'INT-ENG GL 1-MOD 10%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_English += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_English_M10 += round($row["qty"], 0);
                }
              ?></td>
            <td><!--Total--><?php echo round($december_closing_total_English, 0);
            $term_4_English_total += round($december_closing_total_English, 0);
            ?></td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><!--M1--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 01%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 01%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Mandarin = $row["qty"]; echo round($row["qty"], 0);
                  $term_1_Mandarin_M1 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M2--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 02%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 02%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Mandarin = $row["qty"]; echo round($row["qty"], 0);
                  $term_2_Mandarin_M2 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M3--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 03%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 03%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Mandarin = $row["qty"]; echo round($row["qty"], 0);
                  $term_3_Mandarin_M3 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M4--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 04%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 04%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Mandarin = $row["qty"]; echo round($row["qty"], 0);
                  $term_4_Mandarin_M4 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><!--M5--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 05%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 05%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Mandarin = $row["qty"]; echo round($row["qty"], 0);
                  $term_1_Mandarin_M5 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M6--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 06%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 06%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Mandarin = $row["qty"]; echo round($row["qty"], 0);
                  $term_2_Mandarin_M6 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M7--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 07%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 07%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Mandarin = $row["qty"]; echo round($row["qty"], 0);
                  $term_3_Mandarin_M7 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M8--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 08%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 08%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Mandarin += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_Mandarin_M8 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><!--M9--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 09%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 09%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Mandarin = $row["qty"]; echo round($row["qty"], 0);
                  $term_1_Mandarin_M9 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M10--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 10%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 10%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Mandarin = $row["qty"]; echo round($row["qty"], 0);
                  $term_2_Mandarin_M10 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M11--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 11%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 11%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Mandarin = $row["qty"]; echo round($row["qty"], 0);
                  $term_3_Mandarin_M11 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M12--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-MAND PROG-MOD 12%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-MAND PROG-MOD 12%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Mandarin += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_Mandarin_M12 += round($row["qty"], 0);
                }
              ?></td>
            <td><!--Total--><?php echo round($december_closing_total_Mandarin, 0);
            $term_4_Mandarin_total += round($december_closing_total_Mandarin, 0);
            ?></td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><!--M1--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 01%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 01%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Art += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_Art_M1 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M2--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 02%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 02%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Art += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_Art_M2 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M3--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 03%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 03%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Art += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_Art_M3 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M4--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 04%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 04%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Art += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_Art_M4 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><!--M5--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 05%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 05%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Art += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_Art_M5 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M6--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 06%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 06%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Art += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_Art_M6 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M7--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 07%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 07%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Art += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_Art_M7 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M8--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 08%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 08%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Art += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_Art_M8 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><!--M9--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 09%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 09%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Art += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_Art_M9 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M10--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 10%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 10%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Art += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_Art_M10 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M11--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 11%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 11%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Art += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_Art_M11 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M12--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'MY-BEAM-ART PROGM-MOD 12%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'MY-BEAM-ART PROGM-MOD 12%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Art += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_Art_M12 += round($row["qty"], 0);
                }
              ?></td>
            <td><!--Total--><?php echo round($december_closing_total_Art, 0);
            $term_4_Art_total += round($december_closing_total_Art, 0);
            ?></td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><!--ACE02--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-ACE 02%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-ACE 02%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Math += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_Math_ACE02 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M1--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 01%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 01%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Math += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_Math_M1 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M2--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 02%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 02%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Math += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_Math_M2 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M3--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 03%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 03%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Math += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_Math_M3 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M4--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 04%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 04%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Math += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_Math_M4 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M5--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 05%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 05%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Math += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_Math_M5 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M6--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 06%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 06%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Math += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_Math_M6 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term1"><!--M7--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 07%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 07%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Math += $row["qty"]; echo round($row["qty"], 0);
                  $term_1_Math_M7 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term2"><!--M8--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 08%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 08%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Math += $row["qty"]; echo round($row["qty"], 0);
                  $term_2_Math_M8 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term3"><!--M9--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 09%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 09%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Math += $row["qty"]; echo round($row["qty"], 0);
                  $term_3_Math_M9 += round($row["qty"], 0);
                }
              ?></td>
            <td class="term term4"><!--M10--><?php 
                $sql="SELECT sum(qty) as qty from (";
                $sql .=" SELECT sum(qty) as qty from `order` where product_code like 'STARTERS-MATH L1-MOD 10%' and delivered_to_logistic_on<='$year/12/31' and delivered_to_logistic_by<>'' and cancelled_by='' and centre_code='$centre_code' ";
                $sql .=" UNION ALL ";
                $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like 'STARTERS-MATH L1-MOD 10%' and CAST(collection_date_time AS DATE)<='$year/12/31' and centre_code='$centre_code' and void=0";
                $sql .=" )ab ";
    
                $result=mysqli_query($connection, $sql);
                $row=mysqli_fetch_assoc($result);
                if ($row["qty"]=="") {
                  echo 0;
                } else {
                  $december_closing_total_Math += $row["qty"]; echo round($row["qty"], 0);
                  $term_4_Math_M10 += round($row["qty"], 0);
                }
              ?></td>
            <td><!--Total--><?php echo round($december_closing_total_Math, 0);
            $term_4_Math_total += round($december_closing_total_Math, 0);
            ?></td>
            <!--Math end-->
            <td><?php //Grand Total
                echo round($december_closing_total_English + $december_closing_total_Mandarin + $december_closing_total_Art + $december_closing_total_Math, 0);
                $term_4_Beamind_grand_total += round($december_closing_total_English + $december_closing_total_Mandarin + $december_closing_total_Art + $december_closing_total_Math, 0);;
              ?></td>
              <?php } ?>
          </tr>
        <!--Term 4 END-->
        <?php } ?>
        <!--Term 4 subtotal start-->
          <tr class="term term4" style="font-weight: bold;">
            <td></td>
            <td>Subtotal of <?php echo $rowf["name"]; ?></td>
            <?php if($type=="" || $type=="Termly Module"){ ?>
              <td class="term term1"><!--ED1-->
              <?php 
                echo $term_1_ED1;
              ?>
            </td>
            <td class="term term2"><!--ED2--><?php 
                echo $term_2_ED2;
              ?></td>
            <td class="term term3"><!--ED3--><?php 
                echo $term_3_ED3;
              ?></td>
            <td class="term term4"><!--ED4--><?php 
                echo $term_4_ED4;
              ?></td>
            <td class="term term1"><?php //M1
                echo $term_1_M1;
              ?></td>
            <td class="term term2"><!--M2--><?php
                echo $term_2_M2;
              ?></td>
            <td class="term term3"><!--M3--><?php
                echo $term_3_M3;
              ?></td>
            <td class="term term4"><!--M4--><?php
                echo $term_4_M4;
              ?></td>
            <td class="term term1"><?php //M5
                echo $term_1_M5;
              ?></td>
            <td class="term term2"><!--M6--><?php
                echo $term_2_M6;
              ?></td>
            <td class="term term3"><!--M7--><?php
                echo $term_3_M7;
              ?></td>
            <td class="term term4"><!--M8--><?php
                echo $term_4_M8;
              ?></td>
            <td class="term term1"><?php //M9
                echo $term_1_M9;
              ?></td>
            <td class="term term2"><!--M10--><?php
                echo $term_2_M10;
              ?></td>
            <td class="term term3"><!--M11--><?php
                echo $term_3_M11;
              ?></td>
            <td class="term term4"><!--M12--><?php
                echo $term_4_M12;
              ?></td>
            <td><!--Total--><?php echo $term_4_termly_module_total;?></td>

            <!-- LTR (LTR has 4 EDP modules, and 12 other modules) -->
              <?php } if($type=="" || $type=="LTR"){ ?>
                <td class="term term1"><!--ED1-->
              <?php 
                echo $term_1_LTR_ED1;
              ?>
            </td>
            <td class="term term2"><!--ED2--><?php 
                echo $term_2_LTR_ED2;
              ?></td>
            <td class="term term3"><!--ED3--><?php 
                echo $term_3_LTR_ED3;
              ?></td>
            <td class="term term4"><!--ED4--><?php 
                echo $term_4_LTR_ED4;
              ?></td>
            <td class="term term1"><?php //M1
                echo $term_1_LTR_M1;
              ?></td>
            <td class="term term2"><!--M2--><?php
                echo $term_2_LTR_M2;
              ?></td>
            <td class="term term3"><!--M3--><?php 
                echo $term_3_LTR_M3;
              ?></td>
            <td class="term term4"><!--M4--><?php 
                echo $term_4_LTR_M4;
              ?></td>
            <td class="term term1"><?php //M5
                echo $term_1_LTR_M5;
              ?></td>
            <td class="term term2"><!--M6--><?php 
                echo $term_2_LTR_M6;
              ?></td>
            <td class="term term3"><!--M7--><?php 
                echo $term_3_LTR_M7;
              ?></td>
            <td class="term term4"><!--M8--><?php 
                echo $term_4_LTR_M8;
              ?></td>
            <td class="term term1"><?php //M9
               echo $term_1_LTR_M9;
              ?></td>
            <td class="term term2"><!--M10--><?php 
                echo $term_2_LTR_M10;
              ?></td>
            <td class="term term3"><!--M11--><?php
                echo $term_3_LTR_M11;
              ?></td>
            <td class="term term4"><!--M12--><?php
                echo $term_4_LTR_M12;
              ?></td> 
            <td><!--Total--><?php echo $term_4_LTR_total;?></td>

            <!--Fliptec@Q Mandarin-->
            <?php } if($type=="" || $type=="Fliptec@Q Mandarin"){ ?>
              <td class="term term1"><?php //M5
                echo $term_1_fliptec_M5;
              ?></td>
            <td class="term term2"><!--M6--><?php
                echo $term_2_fliptec_M6;
              ?></td>
            <td class="term term3"><!--M7--><?php
                echo $term_3_fliptec_M7;
              ?></td>
            <td class="term term4"><!--M8--><?php
                echo $term_4_fliptec_M8;
              ?></td>
            <td class="term term1"><?php //M9
                echo $term_1_fliptec_M9;
              ?></td>
            <td class="term term2"><!--M10--><?php
                echo $term_2_fliptec_M10;
              ?></td>
            <td class="term term3"><!--M11--><?php
                echo $term_3_fliptec_M11;
              ?></td>
            <td class="term term4"><!--M12--><?php
                echo $term_4_fliptec_M12;
              ?></td>
            <td><!--Total--><?php echo $term_4_fliptec_total;?></td>

            <!--English start-->
            <?php } if($type=="" || $type=="Beamind"){ ?>
              <td class="term term1"><?php //PREP A
                echo $term_1_English_PREP_A;
              ?></td>
            <td class="term term2"><!--PREP B--><?php
                echo $term_2_English_PREP_B;
              ?></td>
            <td class="term term3"><!--M1--><?php
                echo $term_3_English_M1;
              ?></td>
            <td class="term term4"><!--M2--><?php
                echo $term_4_English_M2;
              ?></td>
            <td class="term term1"><?php //M3
                echo $term_1_English_M3;
              ?></td>
            <td class="term term2"><!--M4--><?php
                echo $term_2_English_M4;
              ?></td>
            <td class="term term3"><!--M5--><?php
                echo $term_3_English_M5;
              ?></td>
            <td class="term term4"><!--M6--><?php
                echo $term_4_English_M6;
              ?></td>
            <td class="term term1"><?php //M7
                echo $term_1_English_M7;
              ?></td>
            <td class="term term2"><!--M8--><?php
                echo $term_2_English_M8;
              ?></td>
            <td class="term term3"><!--M9--><?php
                echo $term_3_English_M9;
              ?></td>
            <td class="term term4"><!--M10--><?php
                echo $term_4_English_M10;
              ?></td>
            <td><!--Total--><?php echo $term_4_English_total;?></td>
            <!--English end--> 
            <!--Mandarin start-->
            <td class="term term1"><?php //M1
                echo $term_1_Mandarin_M1;
              ?></td>
            <td class="term term2"><!--M2--><?php
                echo $term_2_Mandarin_M2;
              ?></td>
            <td class="term term3"><!--M3--><?php
                echo $term_3_Mandarin_M3;
              ?></td>
            <td class="term term4"><!--M4--><?php
                echo $term_4_Mandarin_M4;
              ?></td>
            <td class="term term1"><?php //M5
                echo $term_1_Mandarin_M5;
              ?></td>
            <td class="term term2"><!--M6--><?php
                echo $term_2_Mandarin_M6;
              ?></td>
            <td class="term term3"><!--M7--><?php
                echo $term_3_Mandarin_M7;
              ?></td>
            <td class="term term4"><!--M8--><?php
                echo $term_4_Mandarin_M8;
              ?></td>
            <td class="term term1"><?php //M9
                echo $term_1_Mandarin_M9;
              ?></td>
            <td class="term term2"><!--M10--><?php
                echo $term_2_Mandarin_M10;
              ?></td>
            <td class="term term3"><!--M11--><?php
                echo $term_3_Mandarin_M11;
              ?></td>
            <td class="term term4"><!--M12--><?php
                echo $term_4_Mandarin_M12;
              ?></td>
            <td><!--Total--><?php echo $term_4_Mandarin_total;?></td>
            <!--Mandarin end-->
            <!--Art start-->
            <td class="term term1"><?php //M1
                echo $term_1_Art_M1;
              ?></td>
            <td class="term term2"><!--M2--><?php
                echo $term_2_Art_M2;
              ?></td>
            <td class="term term3"><!--M3--><?php
                echo $term_3_Art_M3;
              ?></td>
            <td class="term term4"><!--M4--><?php
                echo $term_4_Art_M4;
              ?></td>
            <td class="term term1"><?php //M5
                echo $term_1_Art_M5;
              ?></td>
            <td class="term term2"><!--M6--><?php
                echo $term_2_Art_M6;
              ?></td>
            <td class="term term3"><!--M7--><?php
                echo $term_3_Art_M7;
              ?></td>
            <td class="term term4"><!--M8--><?php
                echo $term_4_Art_M8;
              ?></td>
            <td class="term term1"><?php //M9
                echo $term_1_Art_M9;
              ?></td>
            <td class="term term2"><!--M10--><?php
                echo $term_2_Art_M10;
              ?></td>
            <td class="term term3"><!--M11--><?php
                echo $term_3_Art_M11;
              ?></td>
            <td class="term term4"><!--M12--><?php
                echo $term_4_Art_M12;
              ?></td>
            <td><!--Total--><?php echo $term_4_Art_total;?></td>
            <!--Art end-->
            <!--Math start-->
            <td class="term term1"><?php //ACE02
                echo $term_1_Math_ACE02;
              ?></td>
            <td class="term term2"><!--M1--><?php
                echo $term_2_Math_M1;
              ?></td>
            <td class="term term3"><!--M2--><?php
                echo $term_3_Math_M2;
              ?></td>
            <td class="term term4"><!--M3--><?php
                echo $term_4_Math_M3;
              ?></td>
            <td class="term term2"><!--M4--><?php
                echo $term_2_Math_M4;
              ?></td>
            <td class="term term3"><!--M5--><?php
                echo $term_3_Math_M5;
              ?></td>
            <td class="term term4"><!--M6--><?php
                echo $term_4_Math_M6;
              ?></td>
            <td class="term term1"><?php //M7
                echo $term_1_Math_M7;
              ?></td>
            <td class="term term2"><!--M8--><?php
                echo $term_2_Math_M8;
              ?></td>
            <td class="term term3"><!--M9--><?php
                echo $term_3_Math_M9;
              ?></td>
            <td class="term term4"><!--M10--><?php
                echo $term_4_Math_M10;
              ?></td>
            <td><!--Total--><?php echo $term_4_Math_total;?></td>
            <!--Math end-->
            <td><?php //Grand Total
                echo $term_4_Beamind_grand_total;
              ?></td>
              <?php } ?>
          </tr>
        <!--Term 4 subtotal END-->
        <?php } } ?>
       
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