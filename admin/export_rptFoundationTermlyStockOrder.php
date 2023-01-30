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

if ($_SESSION["isLogin"]==1) {

    $filename = 'foundation_term'.$term.'_centre_stock_order_report_'.date('Y-m-d').'.csv';

    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");

    $content = array();

    $term_data = mysqli_fetch_array(mysqli_query($connection,"SELECT MIN(`term_start`) AS `start_date`, MAX(`term_end`) AS `end_date` FROM `schedule_term` WHERE `year` = '".$_SESSION['Year']."' AND `centre_code` = '".$_SESSION['CentreCode']."' AND `term_num` = $term GROUP BY `year`, `term_num`"));

    $term_start_date = $term_data['start_date'];
    $term_end_date = $term_data['end_date'];

    $centre = ($centre_code == 'ALL') ? 'All Centre' : getCentreName($centre_code);

    $content[] = array("Centre Name : ",$centre);

    $content[] = array("Prepare By : ",$_SESSION["UserName"]);

    $content[] = array("Academic Year : ",$_SESSION['Year']);

    $content[] = array("Date of submission : ",date("Y-m-d H:i:s"));

    $content[] = array("School Term : ","Term ".$term);

    $content[] = array("","","","");

    if($term=="1"){

        $product_code1 = 'MY-EDP1.EARLY-DIS';
        $product_code2 = 'MY-MODULE01';
        $product_code3 = 'MY-MODULE05';
        $product_code4 = 'MY-MODULE09';

    }else if($term=="2"){

        $product_code1 = 'MY-EDP2.EARLY-DIS';
        $product_code2 = 'MY-MODULE02';
        $product_code3 = 'MY-MODULE06';
        $product_code4 = 'MY-MODULE10';

    }else if($term=="3"){

        $product_code1 = 'MY-EDP3.EARLY-DIS';
        $product_code2 = 'MY-MODULE03';
        $product_code3 = 'MY-MODULE07';
        $product_code4 = 'MY-MODULE11';

    }else if($term=="4"){

        $product_code1 = 'MY-EDP4.EARLY-DIS';
        $product_code2 = 'MY-MODULE04';
        $product_code3 = 'MY-MODULE08';
        $product_code4 = 'MY-MODULE12';

    }else{

        $product_code1 = '';
        $product_code2 = '';
        $product_code3 = '';
        $product_code4 = '';
    }

    $sql="SELECT count(id) as count_order from `order` where (product_code like '$product_code1%' OR product_code like '$product_code2%' OR product_code like '$product_code3%' OR product_code like '$product_code4%') and (ordered_on BETWEEN '$term_start_date' AND '$term_end_date') and (cancelled_by='' OR cancelled_by IS NULL)";

    if($centre_code != '' && $centre_code != 'ALL') {
        $sql .= " and `centre_code` = '$centre_code'";
    }

    $result=mysqli_query($connection, $sql);
    $row=mysqli_fetch_assoc($result);
    if ($row["count_order"]=="") {
        $total = 0;
    } else {
        $total = round($row["count_order"], 0);
    }

    $sql="SELECT count(id) as count_order from `order` where (product_code like '$product_code1%' OR product_code like '$product_code2%' OR product_code like '$product_code3%' OR product_code like '$product_code4%') and (ordered_on BETWEEN '$term_start_date' AND '$term_end_date') and (acknowledged_by!='' AND acknowledged_by IS NOT NULL) and (cancelled_by='' OR cancelled_by IS NULL)";

    if($centre_code != '' && $centre_code != 'ALL') {
        $sql .= " and `centre_code` = '$centre_code'";
    }

    $result=mysqli_query($connection, $sql);
    $row=mysqli_fetch_assoc($result);
    if ($row["count_order"]=="") {
        $submitted = 0;
    } else {
        $submitted = round($row["count_order"], 0);
    }

    $notSubmitted = $total - $submitted;

    $sql="SELECT count(id) as count_order from `order` where (product_code like '$product_code1%' OR product_code like '$product_code2%' OR product_code like '$product_code3%' OR product_code like '$product_code4%') and (ordered_on BETWEEN '$term_start_date' AND '$term_end_date') and (finance_payment_paid_by!='' AND finance_payment_paid_by IS NOT NULL) and (cancelled_by='' OR cancelled_by IS NULL)";

    if($centre_code != '' && $centre_code != 'ALL') {
        $sql .= " and `centre_code` = '$centre_code'";
    }

    $result=mysqli_query($connection, $sql);
    $row=mysqli_fetch_assoc($result);
    if ($row["count_order"]=="") {
        $paid = 0;
    } else {
        $paid = round($row["count_order"], 0);
    }

    $notPaid = $total - $paid;

    $sql="SELECT count(id) as count_order from `order` where (product_code like '$product_code1%' OR product_code like '$product_code2%' OR product_code like '$product_code3%' OR product_code like '$product_code4%') and (ordered_on BETWEEN '$term_start_date' AND '$term_end_date') and (finance_payment_paid_by!='' AND finance_payment_paid_by IS NOT NULL) and (delivered_to_logistic_by!='' AND delivered_to_logistic_by IS NOT NULL) and (cancelled_by='' OR cancelled_by IS NULL)";

    if($centre_code != '' && $centre_code != 'ALL') {
        $sql .= " and `centre_code` = '$centre_code'";
    }

    $result=mysqli_query($connection, $sql);
    $row=mysqli_fetch_assoc($result);
    if ($row["count_order"]=="") {
        $collected = 0;
    } else {
        $collected = round($row["count_order"], 0);
    }

    $notCollected = $total - $collected;

    $content[] = array("Foundation TERM ".$term." - Total Ctr Order Report","","");

    // ORDER

    $content[] = array("","ORDER","");
    $content[] = array("Total","Submitted","Not submitted");
    $content[] = array($total,$submitted,$notSubmitted);

    $row = array();

    $row[] = "100%";
    $row[] = ($total == 0) ? 0 : round(($submitted * 100) / $total, 2)."%";
    $row[] = ($total == 0) ? 0 : round(($notSubmitted * 100) / $total, 2)."%";

    $content[] = $row;

    // PAYMENT

    $content[] = array("","","");

    $content[] = array("","PAYMENT","");
    $content[] = array("Total","Paid","Not paid");
    $content[] = array($total,$paid,$notPaid);

    $row = array();

    $row[] = "100%";
    $row[] = ($total == 0) ? 0 : round(($paid * 100) / $total, 2)."%";
    $row[] = ($total == 0) ? 0 : round(($notPaid * 100) / $total, 2)."%";

    $content[] = $row;

    // COLLECTION

    $content[] = array("","","");

    $content[] = array("","COLLECTION","");
    $content[] = array("Total","Collected","Not collected");
    $content[] = array($total,$collected,$notCollected);

    $row = array();

    $row[] = "100%";
    $row[] = ($total == 0) ? 0 : round(($collected * 100) / $total, 2)."%";
    $row[] = ($total == 0) ? 0 : round(($notCollected * 100) / $total, 2)."%";

    $content[] = $row;

    $content[] = array("","","");
    $content[] = array("","","");

    $content[] = array("Foundation TERM ".$term." - Submitted Ctr Order Report","","");

    // ORDER

    $content[] = array("","ORDER","");
    $content[] = array("Total","Submitted","Not submitted");
    $content[] = array($total,$submitted,$notSubmitted);

    $row = array();

    $row[] = "100%";
    $row[] = ($total == 0) ? 0 : round(($submitted * 100) / $total, 2)."%";
    $row[] = ($total == 0) ? 0 : round(($notSubmitted * 100) / $total, 2)."%";

    $content[] = $row;

    // PAYMENT

    $content[] = array("","","");

    $content[] = array("","PAYMENT","");
    $content[] = array("Total","Paid","Not paid");
    $content[] = array($submitted,$paid,($submitted - $paid));

    $row = array();

    $row[] = "100%";
    $row[] = ($submitted == 0) ? 0 : round(($paid * 100) / $submitted, 2)."%";
    $row[] = ($submitted == 0) ? 0 : round((($submitted - $paid) * 100) / $submitted, 2)."%";

    $content[] = $row;

    // COLLECTION

    $content[] = array("","","");

    $content[] = array("","COLLECTION","");
    $content[] = array("Total","Collected","Not collected");
    $content[] = array($paid,$collected,($paid - $collected));

    $row = array();

    $row[] = "100%";
    $row[] = ($paid == 0) ? 0 : round(($collected * 100) / $paid, 2)."%";
    $row[] = ($paid == 0) ? 0 : round((($paid - $collected) * 100) / $paid, 2)."%";

    $content[] = $row;

    $output = fopen('php://output', 'w');
    fputcsv($output, $title);
    foreach ($content as $con) {
        fputcsv($output, $con);
    }
    fclose($output);
}
?>