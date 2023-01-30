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
$current_date = date('Y-m-d');

function FoundationCount($student_entry_level,$selected_month)
{
    global $connection, $year_start_date, $year_end_date, $current_date;

    if($student_entry_level == 'EDP') {

        $sql="SELECT count(`collection`.`id`) as level_count from `collection` LEFT JOIN `product` ON `product`.`product_code` = `collection`.`product_code` where (collection_date_time BETWEEN '$year_start_date' AND '$year_end_date') and `collection`.`void`=0 and (`collection`.`product_code` like 'MY-EDP1.EARLY-DIS%' OR `collection`.`product_code` like 'MY-EDP2.EARLY-DIS%' OR `collection`.`product_code` like 'MY-EDP3.EARLY-DIS%' OR `collection`.`product_code` like 'MY-EDP4.EARLY-DIS%') ";

    } else {

        $sql="SELECT count(`collection`.`id`) as level_count from `collection` LEFT JOIN `product` ON `product`.`product_code` = `collection`.`product_code` where `collection`.`product_code` like 'MY-MODULE%' and (collection_date_time BETWEEN '$year_start_date' AND '$year_end_date') and `collection`.`void`=0 and `collection`.`product_code` like '%$student_entry_level%' ";

    }

    if($selected_month != 'ALL' && $selected_month != '')
    {
        $sql .= " and '$selected_month' BETWEEN DATE_FORMAT(collection.collection_date_time, '%Y-%m') AND DATE_FORMAT(collection.collection_date_time, '%Y-%m') ";
    }

    $resultt=mysqli_query($connection, $sql);
    $roww=mysqli_fetch_assoc($resultt);
    return $level_count = (empty($roww["level_count"]) ? "0" : $roww["level_count"]); 
}

function getChartData(){
    global $connection, $graph_type;
  
    $sub_categories = array(
      "EDP",
      "QF1",
      "QF2",
      "QF3"
    );
 
    global $year_start_date, $year_end_date;
    $period = getMonthList($year_start_date, $year_end_date);
    $months = array();
    $student_no = array();

    foreach ($period as $dt) {
        $months[$dt->format("M Y")] = $dt->format("Y-m");

        $student_no['EDP'][$dt->format("Y-m")] = FoundationCount('EDP',$dt->format("Y-m"));
        $student_no['QF1'][$dt->format("Y-m")] = FoundationCount('QF1',$dt->format("Y-m"));
        $student_no['QF2'][$dt->format("Y-m")] = FoundationCount('QF2',$dt->format("Y-m"));
        $student_no['QF3'][$dt->format("Y-m")] = FoundationCount('QF3',$dt->format("Y-m"));
    }
  
    $chart_data = array(
      'labels' => array_keys($months),
      'datasets' => array()
    );
  
    $colors = array(
      "#d7ccc8",
      "#ffccbc",
      "#ffecb3",
      "#81c784",
      "#64b5f6",
      "#e57373"
    );

    if($graph_type == 'per')
    {
        foreach($sub_categories as $sub_sub_category){
            $data = array();

            $total = array_sum($student_no[$sub_sub_category]);

            foreach($months as $month){
                if (isset($student_no[$sub_sub_category][$month])) {
                    $data[] = ($total == 0) ? 0 : round(($student_no[$sub_sub_category][$month] * 100) / $total, 2);
                }else{
                    $data[] = "0.00";
                }
            }
            $chart_data['datasets'][] = array(
                'label' => $sub_sub_category,
                'backgroundColor' => array_pop($colors),
                'data' => $data
            );
        }
    }
    else 
    {
        foreach($sub_categories as $sub_sub_category){
            $data = array();
            foreach($months as $month){
                if (isset($student_no[$sub_sub_category][$month])) {
                    $data[] = $student_no[$sub_sub_category][$month];
                }else{
                    $data[] = "0.00";
                }
            }
            $chart_data['datasets'][] = array(
                'label' => $sub_sub_category,
                'backgroundColor' => array_pop($colors),
                'data' => $data
            );
        }
    }
    
    return json_encode($chart_data);
}

?>

<?php
if ($_SESSION["isLogin"]==1) {
?>
<div class="uk-margin-right">

    <div class="uk-width-1-1 myheader text-center mt-5" style="color:white; text-align:center;">
        <h2 class="uk-text-center myheader-text-color myheader-text-style">Month to Month Student No Tracking </h2><h3 class="uk-text-center myheader-text-color myheader-text-style"></h3>
    </div>

    <div class="uk-overflow-container">
        <div class="uk-grid">
            <div class="uk-width-medium-5-10">
                <table class="uk-table">
                    <tr>
                        <td class="uk-text-bold">Month</td>
                        <td>
                            <?php echo (empty($selected_month) || $selected_month == 'ALL') ? 'All Months' : date('M Y',strtotime($selected_month)); ?>
                        </td>
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
                </table>
            </div>
        </div>

        <div class='uk-margin-top'>
        
            <style>
            .rpt-table td {
                border: 1px solid black;
                }
            </style>

            <div class="chartmain header-bottom-part">
                <canvas id="myChartattach" style="position: relative; height:80vh; width:80vw"></canvas>
            </div>

        </div>
    </div>
</div>
    <script>

        jQuery(document).ready(function() {

            if ($("#myChartattach").length) {

                var activityChartData = <?php echo getChartData(); ?> ;
                var activityChartCanvas = $("#myChartattach").get(0).getContext("2d");

                var activityChart = new Chart(activityChartCanvas, {
                    type: 'bar',
                    data: activityChartData,
                    options: {
                        tooltips: {
                            mode: 'index',
                            intersect: false
                        },
                        responsive: true
                    }
                });
            }
        });
    </script>

    <style type="text/css" media="print">
        body {
        zoom:75%; 
        }
    </style>
<?php } ?>
