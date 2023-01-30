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

    $term_data = mysqli_fetch_array(mysqli_query($connection,"SELECT MIN(`term_start`) AS `start_date`, MAX(`term_end`) AS `end_date` FROM `schedule_term` WHERE `year` = '".$_SESSION['Year']."' AND `centre_code` = '".$_SESSION['CentreCode']."' AND `term_num` = $term GROUP BY `year`, `term_num`"));

    $term_start_date = $term_data['start_date'];
    $term_end_date = $term_data['end_date'];
?>
<style>
    .rpt-table td {
        border: 1px solid black;
    }
</style>
<div class="uk-margin-right">
    <div class="uk-width-1-1 myheader text-center mt-5" style="color:white; text-align:center;">
        <h2 class="uk-text-center myheader-text-color myheader-text-style">Foundation Termly Centre Stock Order Report</h2>
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
                                echo "Term 1";

                                $product_code1 = 'MY-EDP1.EARLY-DIS';
                                $product_code2 = 'MY-MODULE01';
                                $product_code3 = 'MY-MODULE05';
                                $product_code4 = 'MY-MODULE09';

                            }else if($term=="2"){
                                echo "Term 2";

                                $product_code1 = 'MY-EDP2.EARLY-DIS';
                                $product_code2 = 'MY-MODULE02';
                                $product_code3 = 'MY-MODULE06';
                                $product_code4 = 'MY-MODULE10';

                            }else if($term=="3"){
                                echo "Term 3";

                                $product_code1 = 'MY-EDP3.EARLY-DIS';
                                $product_code2 = 'MY-MODULE03';
                                $product_code3 = 'MY-MODULE07';
                                $product_code4 = 'MY-MODULE11';

                            }else if($term=="4"){
                                echo "Term 4";
                                
                                $product_code1 = 'MY-EDP4.EARLY-DIS';
                                $product_code2 = 'MY-MODULE04';
                                $product_code3 = 'MY-MODULE08';
                                $product_code4 = 'MY-MODULE12';

                            }else{
                                echo "All Term";

                                $product_code1 = '';
                                $product_code2 = '';
                                $product_code3 = '';
                                $product_code4 = '';
                            
                            }
                        ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="uk-text-bold">Date of submission</td>
                        <td><?php echo date("Y-m-d H:i:s") ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class='uk-margin-top'>
            
            <?php
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
            ?>

            <table class='uk-table text-center' >
                <tr>
                    <td>
                        <table class='uk-table rpt-table'>
                            <tr class='uk-text-small uk-text-bold' style="font-size:14px;">
                                <td colspan="3">Foundation TERM <?php echo $term; ?> - Total Ctr Order Report</td>
                            </tr>
                            <tr class='uk-text-small uk-text-bold'>
                                <td colspan="3">ORDER</td>
                            </tr>
                            <tr class='uk-text-small uk-text-bold'>
                                <td>Total</td>
                                <td>Submitted</td>
                                <td>Not submitted</td>
                            </tr>
                            <tr class='uk-text-small uk-text-bold'>
                                <td><?php echo $total; ?></td>
                                <td><?php echo $submitted; ?></td>
                                <td><?php echo $notSubmitted; ?></td>
                            </tr>
                            <tr class='uk-text-small uk-text-bold'>
                                <td>100%</td>
                                <td><?php echo ($total == 0) ? 0 : round(($submitted * 100) / $total, 2); ?>%</td>
                                <td><?php echo ($total == 0) ? 0 : round(($notSubmitted * 100) / $total, 2); ?>%</td>
                            </tr>
                            <tr class='uk-text-small uk-text-bold'>
                                <td colspan="3"></td>
                            </tr>
                            <tr class='uk-text-small uk-text-bold'>
                                <td colspan="3">PAYMENT</td>
                            </tr>
                            <tr class='uk-text-small uk-text-bold'>
                                <td>Total</td>
                                <td>Paid</td>
                                <td>Not paid</td>
                            </tr>
                            <tr class='uk-text-small uk-text-bold'>
                                <td><?php echo $total; ?></td>
                                <td><?php echo $paid; ?></td>
                                <td><?php echo $notPaid; ?></td>
                            </tr>
                            <tr class='uk-text-small uk-text-bold'>
                                <td>100%</td>
                                <td><?php echo ($total == 0) ? 0 : round(($paid * 100) / $total, 2); ?>%</td>
                                <td><?php echo ($total == 0) ? 0 : round(($notPaid * 100) / $total, 2); ?>%</td>
                            </tr>
                            <tr class='uk-text-small uk-text-bold'>
                                <td colspan="3"></td>
                            </tr>
                            <tr class='uk-text-small uk-text-bold'>
                                <td colspan="3">COLLECTION</td>
                            </tr>
                            <tr class='uk-text-small uk-text-bold'>
                                <td>Total</td>
                                <td>Collected</td>
                                <td>Not collected</td>
                            </tr>
                            <tr class='uk-text-small uk-text-bold'>
                                <td><?php echo $total; ?></td>
                                <td><?php echo $collected; ?></td>
                                <td><?php echo $notCollected; ?></td>
                            </tr>
                            <tr class='uk-text-small uk-text-bold'>
                                <td>100%</td>
                                <td><?php echo ($total == 0) ? 0 : round(($collected * 100) / $total, 2); ?>%</td>
                                <td><?php echo ($total == 0) ? 0 : round(($notCollected * 100) / $total, 2); ?>%</td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table class='uk-table rpt-table'>
                            <tr class='uk-text-small uk-text-bold' style="font-size:14px;">
                                <td colspan="3">Foundation TERM <?php echo $term; ?> - Submitted Ctr Order Report</td>
                            </tr>
                            <tr class='uk-text-small uk-text-bold'>
                                <td colspan="3">ORDER</td>
                            </tr>
                            <tr class='uk-text-small uk-text-bold'>
                                <td>Total</td>
                                <td>Submitted</td>
                                <td>Not submitted</td>
                            </tr>
                            <tr class='uk-text-small uk-text-bold'>
                                <td><?php echo $total; ?></td>
                                <td><?php echo $submitted; ?></td>
                                <td><?php echo $notSubmitted; ?></td>
                            </tr>
                            <tr class='uk-text-small uk-text-bold'>
                                <td>100%</td>
                                <td><?php echo ($total == 0) ? 0 : round(($submitted * 100) / $total, 2); ?>%</td>
                                <td><?php echo ($total == 0) ? 0 : round(($notSubmitted * 100) / $total, 2); ?>%</td>
                            </tr>
                            <tr class='uk-text-small uk-text-bold'>
                                <td colspan="3"></td>
                            </tr>
                            <tr class='uk-text-small uk-text-bold'>
                                <td colspan="3">PAYMENT</td>
                            </tr>
                            <tr class='uk-text-small uk-text-bold'>
                                <td>Total</td>
                                <td>Paid</td>
                                <td>Not paid</td>
                            </tr>
                            <tr class='uk-text-small uk-text-bold'>
                                <td><?php echo $submitted; ?></td>
                                <td><?php echo $paid; ?></td>
                                <td><?php echo $submitted - $paid; ?></td>
                            </tr>
                            <tr class='uk-text-small uk-text-bold'>
                                <td>100%</td>
                                <td><?php echo ($submitted == 0) ? 0 : round(($paid * 100) / $submitted, 2); ?>%</td>
                                <td><?php echo ($submitted == 0) ? 0 : round((($submitted - $paid) * 100) / $submitted, 2); ?>%</td>
                            </tr>
                            <tr class='uk-text-small uk-text-bold'>
                                <td colspan="3"></td>
                            </tr>
                            <tr class='uk-text-small uk-text-bold'>
                                <td colspan="3">COLLECTION</td>
                            </tr>
                            <tr class='uk-text-small uk-text-bold'>
                                <td>Total</td>
                                <td>Collected</td>
                                <td>Not collected</td>
                            </tr>
                            <tr class='uk-text-small uk-text-bold'>
                                <td><?php echo $paid; ?></td>
                                <td><?php echo $collected; ?></td>
                                <td><?php echo $paid - $collected; ?></td>
                            </tr>
                            <tr class='uk-text-small uk-text-bold'>
                                <td>100%</td>
                                <td><?php echo ($paid == 0) ? 0 : round(($collected * 100) / $paid, 2); ?>%</td>
                                <td><?php echo ($paid == 0) ? 0 : round((($paid - $collected) * 100) / $paid, 2); ?>%</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
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
<style type="text/css" media="print">
    body {
      zoom:75%; /*or whatever percentage you need, play around with this number*/
    }
</style>
  <?php }?>