<?php
session_start();
$session_id=session_id();
$year=$_SESSION["Year"];

include_once("../mysql.php");
include_once("functions.php");
include_once("../search_new.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //$from_date
}

$centre_code = (isset($centre_code) && $centre_code != '') ? $centre_code : 'ALL';

if($centre_code != 'ALL') {
    $total_no_of_pages = 1;
} else {

    if (!isset($page_no) || $page_no=="") {
        $page_no = 1;
    }
    
    $total_records_per_page = 10;
    
    $offset = ($page_no-1) * $total_records_per_page;
    $previous_page = $page_no - 1;
    $next_page = $page_no + 1;
    $adjacents = "2";

    $result_count = mysqli_query($connection,"SELECT COUNT(*) As total_records FROM `centre` WHERE `status` = 'A'");
    $total_records = mysqli_fetch_array($result_count);
    $total_records = $total_records['total_records'];
    $total_no_of_pages = ceil($total_records / $total_records_per_page);
    $second_last = $total_no_of_pages - 1; // total pages minus 1
}

if ($method == "print") {
  include_once("../uikit1.php");
}

$from_date = convertDate2ISO($from_date);
$to_date = convertDate2ISO($to_date);

function getCentreName($centre_code)
{
  global $connection;

  $sql = "SELECT company_name from centre where centre_code='$centre_code'";
  $result = mysqli_query($connection, $sql);
  $row = mysqli_fetch_assoc($result);
  $num_row = mysqli_num_rows($result); //echo $num_row; die;

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

  <h2 class="uk-text-center myheader-text-color myheader-text-style">Termly Centre Stock Report - Zhi Hui Mandarin</h2>
  From <?php 
            if($term=="1"){
              echo "Term 1";
            }else if($term=="2"){
              echo "Term 2";
            }else if($term=="3"){
              echo "Term 3";
            }else if($term=="4"){
              echo "Term 4";
            }else if($term=="5"){
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
          <?php echo ($centre_code == 'ALL') ? 'All Centre' : getCentreName($centre_code); ?>
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
            if($term=="1"){

              $product_code1 = 'MY-ZHM01';
              $product_code2 = 'MY-ZHM05';
              $product_code3 = 'MY-ZHM09';

              echo "Term 1";
            }else if($term=="2"){
              echo "Term 2";

              $product_code1 = 'MY-ZHM02';
              $product_code2 = 'MY-ZHM06';
              $product_code3 = 'MY-ZHM10';

            }else if($term=="3"){
              echo "Term 3";

              $product_code1 = 'MY-ZHM03';
              $product_code2 = 'MY-ZHM07';
              $product_code3 = 'MY-ZHM11';

            }else if($term=="4"){
              echo "Term 4";
              
              $product_code1 = 'MY-ZHM04';
              $product_code2 = 'MY-ZHM08';
              $product_code3 = 'MY-ZHM12';

            }else if($term=="5"){
              echo "Term 5";

              $product_code1 = '';
              $product_code2 = '';
              $product_code3 = '';

            }else{
              echo "All Term";

              $product_code1 = '';
              $product_code2 = '';
              $product_code3 = '';
              
            }
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

      $sql="SELECT DISTINCT o.product_code, p.product_name, p.sub_category, o.centre_code from `order` o, product p Where delivered_to_logistic_by<>'' and (cancelled_by='' OR cancelled_by IS NULL) and o.centre_code='$centre_code' AND o.product_code=p.product_code";
   
     //filter sub_category
     if( isset($sub_category) && ! empty($sub_category) ){
       $sub_category_token=ConstructToken('p.sub_category', $sub_category, '=');
       $sql=ConcatWhere($sql, $sub_category_token);
     }

     $sql=ConcatOrder($sql, "p.product_name asc");

     $result=mysqli_query($connection, $sql);
     $num_row=mysqli_num_rows($result);

    ?>
        <div class='uk-margin-top'>
       
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
            <td rowspan="2">No.</td>
            <td rowspan="2">Centre</td>
            <td colspan="3">Opening Balance</td>
            <td colspan="3">Stock Delivered</td>
            <td colspan="3">Stock Consumed</td>
            <td colspan="3">Closing Stock</td>
            <td colspan="3">Student Numbers</td>
            </tr>
        
        <tr class='uk-text-small uk-text-bold'>
          <?php if($term == 1) { ?>
            <td class="term term1">M1</td>
            <td class="term term1">M5</td>
            <td class="term term1">M9</td>
            <td class="term term1">M1</td>
            <td class="term term1">M5</td>
            <td class="term term1">M9</td>
            <td class="term term1">M1</td>
            <td class="term term1">M5</td>
            <td class="term term1">M9</td>
            <td class="term term1">M1</td>
            <td class="term term1">M5</td>
            <td class="term term1">M9</td>
            <td class="term term1">M1</td>
            <td class="term term1">M5</td>
            <td class="term term1">M9</td>
          <?php } else if($term == 2) { ?>
            <td class="term term1">M2</td>
            <td class="term term1">M6</td>
            <td class="term term1">M10</td>
            <td class="term term1">M2</td>
            <td class="term term1">M6</td>
            <td class="term term1">M10</td>
            <td class="term term1">M2</td>
            <td class="term term1">M6</td>
            <td class="term term1">M10</td>
            <td class="term term1">M2</td>
            <td class="term term1">M6</td>
            <td class="term term1">M10</td>
            <td class="term term1">M2</td>
            <td class="term term1">M6</td>
            <td class="term term1">M10</td>
          <?php } else if($term == 3) { ?>
            <td class="term term1">M3</td>
            <td class="term term1">M7</td>
            <td class="term term1">M11</td>
            <td class="term term1">M3</td>
            <td class="term term1">M7</td>
            <td class="term term1">M11</td>
            <td class="term term1">M3</td>
            <td class="term term1">M7</td>
            <td class="term term1">M11</td>
            <td class="term term1">M3</td>
            <td class="term term1">M7</td>
            <td class="term term1">M11</td>
            <td class="term term1">M3</td>
            <td class="term term1">M7</td>
            <td class="term term1">M11</td>
          <?php } else if($term == 4) { ?>
            <td class="term term1">M4</td>
            <td class="term term1">M8</td>
            <td class="term term1">M12</td>
            <td class="term term1">M4</td>
            <td class="term term1">M8</td>
            <td class="term term1">M12</td>
            <td class="term term1">M4</td>
            <td class="term term1">M8</td>
            <td class="term term1">M12</td>
            <td class="term term1">M4</td>
            <td class="term term1">M8</td>
            <td class="term term1">M12</td>
            <td class="term term1">M4</td>
            <td class="term term1">M8</td>
            <td class="term term1">M12</td>
          <?php } ?>
          
        </tr>

          <?php 
            if($centre_code == 'ALL') {
              $centre = mysqli_query($connection,"SELECT * FROM `centre` WHERE `status` = 'A' LIMIT $offset, $total_records_per_page");
            } else {
              $centre = mysqli_query($connection,"SELECT * FROM `centre` WHERE `status` = 'A' AND `centre_code` = '$centre_code'");
            }
            
            $i = 0;
            if($page_no > 1) 
            {
                $i = (($page_no-1)*10);
            }
            $opening_balance_total1 = 0; $stock_delivered_total1 = 0; $stock_consumed_total1 = 0; $closing_stock_total1 = 0;
            $opening_balance_total2 = 0; $stock_delivered_total2 = 0; $stock_consumed_total2 = 0; $closing_stock_total2 = 0;
            $opening_balance_total3 = 0; $stock_delivered_total3 = 0; $stock_consumed_total3 = 0; $closing_stock_total3 = 0;
        
            while($centre_row = mysqli_fetch_array($centre))
            {
                $centre_code = $centre_row['centre_code'];

                $term_data = mysqli_fetch_array(mysqli_query($connection,"SELECT MIN(`term_start`) AS `start_date`, MAX(`term_end`) AS `end_date` FROM `schedule_term` WHERE `year` = '".$_SESSION['Year']."' AND `centre_code` = '".$centre_code."' AND `term_num` = $term GROUP BY `year`, `term_num`"));

                $term_start_date = $term_data['start_date'];
                $term_end_date = $term_data['end_date'];

                if($term_start_date == '')
                {
                  if($_SESSION['Year'] == '2022-2023')
                  {
                    $term_data = mysqli_fetch_array(mysqli_query($connection,"SELECT MIN(`term_start`) AS `start_date`, MAX(`term_end`) AS `end_date` FROM `schedule_term` WHERE `year` = '2022' AND `centre_code` = '".$centre_code."' AND `term_num` = $term GROUP BY `year`, `term_num`"));

                    $term_start_date = $term_data['start_date'];
                    $term_end_date = $term_data['end_date'];
                  }
                  else if($_SESSION['Year'] == '2022')
                  {
                    $term_data = mysqli_fetch_array(mysqli_query($connection,"SELECT MIN(`term_start`) AS `start_date`, MAX(`term_end`) AS `end_date` FROM `schedule_term` WHERE `year` = '2022-2023' AND `centre_code` = '".$centre_code."' AND `term_num` = $term GROUP BY `year`, `term_num`"));

                    $term_start_date = $term_data['start_date'];
                    $term_end_date = $term_data['end_date'];
                  }
                }

                $i++;
        ?>
                <tr class="term term1">
                    <td><?php echo $i; ?></td>
                    <td><?php echo $centre_row['company_name']; ?></td>
                    <td class="term term1"><?php
                        $sql="SELECT sum(qty) as qty from (";
                        $sql .=" SELECT sum(qty) as qty from `order` where product_code like '$product_code1%' and delivered_to_logistic_on<'$term_start_date' and delivered_to_logistic_by<>'' and (cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                        $sql .=" UNION ALL ";
                        $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like '$product_code1%' and CAST(collection_date_time AS DATE)<'$term_start_date' and centre_code='$centre_code' and void=0";
                        $sql .=" )ab ";

                        $result=mysqli_query($connection, $sql);
                        $row=mysqli_fetch_assoc($result);
                        if ($row["qty"]=="") {
                          $opening_balance1 = 0;
                        } else {
                          $opening_balance1 = round($row["qty"], 0);
                          $opening_balance_total1 += $row["qty"];  
                        }
                        echo $opening_balance1;
                    ?></td>
                
                    <td class="term term1"><?php //M3
                        $sql="SELECT sum(qty) as qty from (";
                        $sql .=" SELECT sum(qty) as qty from `order` where product_code like '$product_code2%' and delivered_to_logistic_on<'$term_start_date' and delivered_to_logistic_by<>'' and (cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                        $sql .=" UNION ALL ";
                        $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like '$product_code2%' and CAST(collection_date_time AS DATE)<'$term_start_date' and centre_code='$centre_code' and void=0";
                        $sql .=" )ab ";
                        //echo $sql;
                        $result=mysqli_query($connection, $sql);
                        $row=mysqli_fetch_assoc($result);
                        if ($row["qty"]=="") {
                          $opening_balance2 = 0;
                        } else {
                          $opening_balance2 = round($row["qty"], 0);
                          $opening_balance_total2 += $row["qty"];  
                        }
                        echo $opening_balance2;
                    ?></td>
                    
                    <td class="term term1"><?php //M7
                        $sql="SELECT sum(qty) as qty from (";
                        $sql .=" SELECT sum(qty) as qty from `order` where product_code like '$product_code3%' and delivered_to_logistic_on<'$term_start_date' and delivered_to_logistic_by<>'' and (cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                        $sql .=" UNION ALL ";
                        $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like '$product_code3%' and CAST(collection_date_time AS DATE)<'$term_start_date' and centre_code='$centre_code' and void=0";
                        $sql .=" )ab ";
                        
                        $result=mysqli_query($connection, $sql);
                        $row=mysqli_fetch_assoc($result);
                        if ($row["qty"]=="") {
                          $opening_balance3 = 0;
                        } else {
                          $opening_balance3 = round($row["qty"], 0);
                          $opening_balance_total3 += $row["qty"];  
                        }
                        echo $opening_balance3;
                    ?></td>
                    
                    <td class="term term1"><?php //PREP A
                        $sql="SELECT sum(qty) as qty from `order` where product_code like '$product_code1%' and (delivered_to_logistic_on BETWEEN '$term_start_date' AND '$term_end_date') and delivered_to_logistic_by<>'' and (cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                      
                        $result=mysqli_query($connection, $sql);
                        $row=mysqli_fetch_assoc($result);
                        if ($row["qty"]=="") {
                          $stock_delivered1 = 0;
                        } else {
                          $stock_delivered1 = round($row["qty"], 0);
                          $stock_delivered_total1 += $row["qty"];  
                        }
                        echo $stock_delivered1;
                    ?></td>
                    
                    <td class="term term1"><?php //M3
                        $sql="SELECT sum(qty) as qty from `order` where product_code like '$product_code2%' and (delivered_to_logistic_on BETWEEN '$term_start_date' AND '$term_end_date') and delivered_to_logistic_by<>'' and (cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                       
                        $result=mysqli_query($connection, $sql);
                        $row=mysqli_fetch_assoc($result);
                        if ($row["qty"]=="") {
                          $stock_delivered2 = 0;
                        } else {
                          $stock_delivered2 = round($row["qty"], 0);
                          $stock_delivered_total2 += $row["qty"];  
                        }
                        echo $stock_delivered2;
                    ?></td>
                    
                    <td class="term term1"><?php //M7
                        $sql="SELECT sum(qty) as qty from `order` where product_code like '$product_code3%' and (delivered_to_logistic_on BETWEEN '$term_start_date' AND '$term_end_date') and delivered_to_logistic_by<>'' and (cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
                    
                        $result=mysqli_query($connection, $sql);
                        $row=mysqli_fetch_assoc($result);
                        if ($row["qty"]=="") {
                          $stock_delivered3 = 0;
                        } else {
                          $stock_delivered3 = round($row["qty"], 0);
                          $stock_delivered_total3 += $row["qty"];  
                        }
                        echo $stock_delivered3;
                    ?></td>

                    <td class="term term1"><?php //PREP A
                        $sql="SELECT sum(qty)  as qty, count(DISTINCT `student_code`) as student_number from `collection` where product_code like '$product_code1%' and (collection_date_time BETWEEN '$term_start_date' AND '$term_end_date') and centre_code='$centre_code' and void=0";
                       
                        $result=mysqli_query($connection, $sql);
                        $row=mysqli_fetch_assoc($result);
                        if ($row["qty"]=="") {
                          $stock_consumed1 = 0;
                        } else {
                          $stock_consumed1 = round($row["qty"], 0);
                          $stock_consumed_total1 += $row["qty"];  
                        }
                        $student_number1 = $row['student_number'];

                        echo $stock_consumed1;
                    ?></td>
                    
                    <td class="term term1"><?php //M3
                        $sql="SELECT sum(qty)  as qty, count(DISTINCT `student_code`) as student_number from `collection` where product_code like '$product_code2%' and (collection_date_time BETWEEN '$term_start_date' AND '$term_end_date') and centre_code='$centre_code' and void=0";
                      
                        $result=mysqli_query($connection, $sql);
                        $row=mysqli_fetch_assoc($result);
                        if ($row["qty"]=="") {
                          $stock_consumed2 = 0;
                        } else {
                          $stock_consumed2 = round($row["qty"], 0);
                          $stock_consumed_total2 += $row["qty"];  
                        }
                        $student_number2 = $row['student_number'];
                        echo $stock_consumed2;
                    ?></td>
                    
                    <td class="term term1"><?php //M7
                        $sql="SELECT sum(qty)  as qty, count(DISTINCT `student_code`) as student_number from `collection` where product_code like '$product_code3%' and (collection_date_time BETWEEN '$term_start_date' AND '$term_end_date') and centre_code='$centre_code' and void=0";
                      
                        $result=mysqli_query($connection, $sql);
                        $row=mysqli_fetch_assoc($result);
                        if ($row["qty"]=="") {
                            $stock_consumed3 = 0;
                        } else {
                            $stock_consumed3 = round($row["qty"], 0);
                            $stock_consumed_total3 += $row["qty"]; 
                        }
                        $student_number3 = $row['student_number'];
                        echo $stock_consumed3;
                    ?></td>

                    <td class="term term1"><?php //PREP A

                        echo $closing_stock1 = $opening_balance1 + $stock_delivered1 - $stock_consumed1;

                        $closing_stock_total1 += $closing_stock1;
                    ?></td>
                    
                    <td class="term term1"><?php //M3
                        
                        echo $closing_stock2 = $opening_balance2 + $stock_delivered2 - $stock_consumed2;

                        $closing_stock_total2 += $closing_stock2;
                    ?></td>
                    
                    <td class="term term1"><?php //M7
                        echo $closing_stock3 = $opening_balance3 + $stock_delivered3 - $stock_consumed3;

                        $closing_stock_total3 += $closing_stock3;
                    ?></td>
                    
                    <td class="term term1"><?php //PREP A
                       echo $student_number1;
                       $student_number_total1 += $student_number1;
                    ?></td>
                    
                    <td class="term term1"><?php //M3
                        echo $student_number2;
                        $student_number_total2 += $student_number2;
                    ?></td>
                    
                    <td class="term term1"><?php //M7
                        echo $student_number3;
                        $student_number_total3 += $student_number3;
                    ?></td>
                </tr>
        <?php
            }
          ?>
          <tr class="term term1" style="font-weight:bold;" >
            <td></td>
            <td>GRAND TOTAL</td>
            <td><?php //Grand Total
                echo round($opening_balance_total1, 0);
              ?></td>
            <td><?php //Grand Total
                echo round($opening_balance_total2, 0);
              ?></td>
            <td><?php //Grand Total
                echo round($opening_balance_total3, 0);
              ?></td>
            <td><?php //Total
                echo round($stock_delivered_total1, 0);
              ?></td>
            <td><?php //Total
                echo round($stock_delivered_total2, 0);
              ?></td>
            <td><?php //Total
                echo round($stock_delivered_total3, 0);
              ?></td>
            <td><?php //Total
                echo round($stock_consumed_total1, 0);
              ?></td>
            <td><?php //Total
                echo round($stock_consumed_total2, 0);
              ?></td>
            <td><?php //Total
                echo round($stock_consumed_total3, 0);
              ?></td>
            <td><?php //Total
                echo round($closing_stock_total1, 0);
              ?></td>
            <td><?php //Total
                echo round($closing_stock_total2, 0);
              ?></td>
            <td><?php //Total
                echo round($closing_stock_total3, 0);
              ?></td>
            <td><?php //Total
                echo round($student_number_total1, 0);
              ?></td>
            <td><?php //Total
                echo round($student_number_total2, 0);
              ?></td>
            <td><?php //Total
                echo round($student_number_total3, 0);
              ?></td>
          </tr>
        </table>
        
        <div class="container">
            <?php if ($total_no_of_pages > 1) { ?>
                <div style='padding: 0px 20px 0px; '>
                    <strong class="pull-left" style="margin:20px 0;">Page <?php echo $page_no." of ".$total_no_of_pages; ?></strong>
                    <ul class="pagination pagination-lg pull-right">
                    <?php 
                        if ($total_no_of_pages <= 10 && $total_no_of_pages > 1){  	 
                            for ($counter = 1; $counter <= $total_no_of_pages; $counter++){
                                if ($counter == $page_no) {
                                    echo "<li class='active' onclick=\"generateBalReport('',$counter)\" ><a>$counter</a></li>";	
                                }else{
                                    echo "<li onclick=\"generateBalReport('',$counter)\" ><a >$counter</a></li>";
                                }
                            }
                        } elseif ($total_no_of_pages > 10){
                            if($page_no <= 4) {			
                                for ($counter = 1; $counter < 8; $counter++){		 
                                    if ($counter == $page_no) {
                                        echo "<li onclick=\"generateBalReport('',$counter)\" class='active'><a>$counter</a></li>";	
                                    }else{
                                        echo "<li onclick=\"generateBalReport('',$counter)\" ><a >$counter</a></li>";
                                    }
                                }
                                echo "<li><a>...</a></li>";
                                echo "<li onclick=\"generateBalReport('',$second_last)\" ><a >$second_last</a></li>";
                                echo "<li onclick=\"generateBalReport('',$total_no_of_pages)\" ><a >$total_no_of_pages</a></li>";

                            } elseif($page_no > 4 && $page_no < $total_no_of_pages - 4) {		 
                                
                                echo "<li onclick=\"generateBalReport('',1)\" ><a >1</a></li>";
                                echo "<li onclick=\"generateBalReport('',2)\" ><a >2</a></li>";
                                echo "<li ><a>...</a></li>";
                                for ($counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++) {		
                                    if ($counter == $page_no) {
                                        echo "<li onclick=\"generateBalReport('',$counter)\" class='active'><a>$counter</a></li>";	
                                    }else{
                                        echo "<li onclick=\"generateBalReport('',$counter)\" ><a >$counter</a></li>";
                                    }                  
                                }
                                echo "<li><a>...</a></li>";
                                echo "<li onclick=\"generateBalReport('',$second_last)\" ><a >$second_last</a></li>";
                                echo "<li onclick=\"generateBalReport('',$total_no_of_pages)\" ><a >$total_no_of_pages</a></li>";

                            } else {

                                echo "<li onclick=\"generateBalReport('',1)\" ><a >1</a></li>";
                                echo "<li onclick=\"generateBalReport('',2)\" ><a >2</a></li>";
                                echo "<li><a>...</a></li>";
                                for ($counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++) {
                                    if ($counter == $page_no) {
                                        echo "<li onclick=\"generateBalReport('',$counter)\" class='active'><a>$counter</a></li>";	
                                    }else{
                                        echo "<li onclick=\"generateBalReport('',$counter)\" ><a >$counter</a></li>";
                                    }                   
                                }
                            }
                        }
                    ?>
                </ul>
                </div>
            <?php } ?>
            
        </div>
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