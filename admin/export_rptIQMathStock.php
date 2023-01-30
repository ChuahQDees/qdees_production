<?php
session_start();
$session_id=session_id();
$year=$_SESSION["Year"];

include_once("../mysql.php");
include_once("functions.php");
include_once("../search_new.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); 
}

$centre_code = (isset($centre_code) && $centre_code != '') ? $centre_code : 'ALL';

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

if ($_SESSION["isLogin"]==1) {

    $filename = 'iq_math_term'.$term.'_centre_stock_report_'.date('Y-m-d').'.csv';

    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");

    $content = array();

    $centre = ($centre_code == 'ALL') ? 'All Centre' : getCentreName($centre_code);

    $content[] = array("Centre Name : ",$centre);

    $content[] = array("Prepare By : ",$_SESSION["UserName"]);

    $content[] = array("Academic Year : ",$_SESSION['Year']);

    $content[] = array("Date of submission : ",date("Y-m-d H:i:s"));

    $content[] = array("School Term : ","Term ".$term);

    $content[] = array("","","","");

    if($term=="1"){
        
        $product_code1 = '';
        $product_code2 = 'STARTERS-MATH L1-MOD 03';
        $product_code3 = 'STARTERS-MATH L1-MOD 07';

    } else if($term=="2"){
    
        $product_code1 = 'STARTERS-MATH L1-ACE 02';
        $product_code2 = 'STARTERS-MATH L1-MOD 04';
        $product_code3 = 'STARTERS-MATH L1-MOD 08';

    } else if($term=="3"){
    
        $product_code1 = 'STARTERS-MATH L1-MOD 01';
        $product_code2 = 'STARTERS-MATH L1-MOD 05';
        $product_code3 = 'STARTERS-MATH L1-MOD 09';

    } else if($term=="4"){
        
        $product_code1 = 'STARTERS-MATH L1-MOD 02';
        $product_code2 = 'STARTERS-MATH L1-MOD 06';
        $product_code3 = 'STARTERS-MATH L1-MOD 10';

    } else {
    
        $product_code1 = '';
        $product_code2 = '';
        $product_code3 = '';
        
    }

    $content[] = array("No.","Centre","Opening Balance","","","Stock Delivered","","","Stock Consumed","","","Closing Stock","","","Student Numbers","","");

    if($term == 1) {
        $content[] = array("","","","M3","M7","","M3","M7","","M3","M7","","M3","M7","","M3","M7");
    } else if($term == 2) {
        $content[] = array("","","ACE2","M4","M8","ACE2","M4","M8","ACE2","M4","M8","ACE2","M4","M8","ACE2","M4","M8");
    } else if($term == 3) {
        $content[] = array("","","M1","M5","M9","M1","M5","M9","M1","M5","M9","M1","M5","M9","M1","M5","M9");
    } else if($term == 4) {
        $content[] = array("","","M2","M6","M10","M2","M6","M10","M2","M6","M10","M2","M6","M10","M2","M6","M10");
    } 

    if($centre_code == 'ALL') {
        $centre = mysqli_query($connection,"SELECT * FROM `centre` WHERE `status` = 'A'");
    } else {
        $centre = mysqli_query($connection,"SELECT * FROM `centre` WHERE `status` = 'A' AND `centre_code` = '$centre_code'");
    }
    
    $i = 0;

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

        $excel_row = array();

        $excel_row[] = $i;
        $excel_row[] = $centre_row['company_name'];

        if($term != 1)
        {
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
            $excel_row[] = $opening_balance1;
        }
        else 
        {
            $excel_row[] = "";
            $opening_balance1 = 0;
        }

        $sql="SELECT sum(qty) as qty from (";
        $sql .=" SELECT sum(qty) as qty from `order` where product_code like '$product_code2%' and delivered_to_logistic_on<'$term_start_date' and delivered_to_logistic_by<>'' and (cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
        $sql .=" UNION ALL ";
        $sql .="SELECT sum(qty) * -1 as qty from `collection` where product_code like '$product_code2%' and CAST(collection_date_time AS DATE)<'$term_start_date' and centre_code='$centre_code' and void=0";
        $sql .=" )ab ";

        $result=mysqli_query($connection, $sql);
        $row=mysqli_fetch_assoc($result);
        if ($row["qty"]=="") {
            $opening_balance2 = 0;
        } else {
            $opening_balance2 = round($row["qty"], 0);
            $opening_balance_total2 += $row["qty"];  
        }
        $excel_row[] = $opening_balance2;
    
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
        $excel_row[] = $opening_balance3;                  

        if($term != 1)
        {
            $sql="SELECT sum(qty) as qty from `order` where product_code like '$product_code1%' and (delivered_to_logistic_on BETWEEN '$term_start_date' AND '$term_end_date') and delivered_to_logistic_by<>'' and (cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
            
            $result=mysqli_query($connection, $sql);
            $row=mysqli_fetch_assoc($result);
            if ($row["qty"]=="") {
                $stock_delivered1 = 0;
            } else {
                $stock_delivered1 = round($row["qty"], 0);
                $stock_delivered_total1 += $row["qty"];  
            }
            $excel_row[] = $stock_delivered1;
        }
        else 
        {
            $excel_row[] = "";
            $stock_delivered1 = 0;
        }

        $sql="SELECT sum(qty) as qty from `order` where product_code like '$product_code2%' and (delivered_to_logistic_on BETWEEN '$term_start_date' AND '$term_end_date') and delivered_to_logistic_by<>'' and (cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
        
        $result=mysqli_query($connection, $sql);
        $row=mysqli_fetch_assoc($result);
        if ($row["qty"]=="") {
            $stock_delivered2 = 0;
        } else {
            $stock_delivered2 = round($row["qty"], 0);
            $stock_delivered_total2 += $row["qty"];  
        }
        $excel_row[] = $stock_delivered2;
    
        $sql="SELECT sum(qty) as qty from `order` where product_code like '$product_code3%' and (delivered_to_logistic_on BETWEEN '$term_start_date' AND '$term_end_date') and delivered_to_logistic_by<>'' and (cancelled_by='' OR cancelled_by IS NULL) and centre_code='$centre_code' ";
    
        $result=mysqli_query($connection, $sql);
        $row=mysqli_fetch_assoc($result);
        if ($row["qty"]=="") {
            $stock_delivered3 = 0;
        } else {
            $stock_delivered3 = round($row["qty"], 0);
            $stock_delivered_total3 += $row["qty"];  
        }
        $excel_row[] = $stock_delivered3;
    
        if($term != 1)
        {
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

            $excel_row[] = $stock_consumed1;
        }
        else 
        {
            $excel_row[] = "";
            $stock_consumed1 = 0;
        }
    
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
        $excel_row[] = $stock_consumed2;
    
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
        $excel_row[] = $stock_consumed3;
    
        if($term != 1)
        {
            $excel_row[] = $closing_stock1 = $opening_balance1 + $stock_delivered1 - $stock_consumed1;
            $closing_stock_total1 += $closing_stock1;
        }
        else 
        {
            $excel_row[] = "";
            $closing_stock_total1 = 0;
        }

        $excel_row[] = $closing_stock2 = $opening_balance2 + $stock_delivered2 - $stock_consumed2;

        $closing_stock_total2 += $closing_stock2;
    
        $excel_row[] = $closing_stock3 = $opening_balance3 + $stock_delivered3 - $stock_consumed3;

        $closing_stock_total3 += $closing_stock3;
    
        if($term != 1)
        {
            $excel_row[] = $student_number1;
            $student_number_total1 += $student_number1;
        }
        else 
        {
            $excel_row[] = "";
            $student_number_total1 = 0;
        }
        $excel_row[] = $student_number2;
        $student_number_total2 += $student_number2;
    
        $excel_row[] = $student_number3;
        $student_number_total3 += $student_number3;
                   
        $content[] = $excel_row;
    }

    $excel_row = array();

    $excel_row[] = "";
    $excel_row[] = "GRAND TOTAL";
    $excel_row[] = ($term != 1) ? round($opening_balance_total1, 0) : '';
    $excel_row[] = round($opening_balance_total2, 0);     
    $excel_row[] = round($opening_balance_total3, 0);
    $excel_row[] = ($term != 1) ? round($stock_delivered_total1, 0) : '';
    $excel_row[] = round($stock_delivered_total2, 0);
    $excel_row[] = round($stock_delivered_total3, 0);
    $excel_row[] = ($term != 1) ? round($stock_consumed_total1, 0) : '';
    $excel_row[] = round($stock_consumed_total2, 0);
    $excel_row[] = round($stock_consumed_total3, 0);
    $excel_row[] = ($term != 1) ? round($closing_stock_total1, 0) : '';
    $excel_row[] = round($closing_stock_total2, 0);
    $excel_row[] = round($closing_stock_total3, 0);
    $excel_row[] = ($term != 1) ? round($student_number_total1, 0) : '';
    $excel_row[] = round($student_number_total2, 0);
    $excel_row[] = round($student_number_total3, 0);
         
    $content[] = $excel_row;

    $output = fopen('php://output', 'w');
    fputcsv($output, $title);
    foreach ($content as $con) {
        fputcsv($output, $con);
    }
    fclose($output);
}
?>