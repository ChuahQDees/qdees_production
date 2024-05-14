<a href="/">                 
				 <!-- <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span> -->
</a>
<span>
    <span class="page_title"><img src="/images/title_Icons/Centre Stock Balance.png">Sales Chart</span>
</span>

<?php
include_once("../mysql.php");
include_once("functions.php");

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

?>

<?php
if (hasRightGroupXOR($_SESSION["UserName"], "SalesView|SalesEdit")) {
?>
    <div class="col-md-12">
        <div class="header-top-part">
            <?php
           if (hasRightGroupXOR($_SESSION["UserName"], "SalesView|SalesEdit")) {
               ?>
            <h3 class="sales-title"><a style="color: white !important" href="index.php?p=sales">Sales</a></h3>
            <?php
           } else {
               ?>
            <h3 style="color: white !important" class="sales-title">Sales</h3>
            <?php
           }
           ?>
        </div>
        <div class="chartmain header-bottom-part">
            <hr>

            <canvas id="myChartattach" style="position: relative; height:80vh; width:80vw"></canvas>
        </div>
    </div>
    <div class="clear"></div>
    <?php
}
?>

<script src="lib/uikit/js/jquery.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
jQuery(document).ready(function() {

    if ($("#myChartattach").length) {
        var activityChartData = <?php echo getChartDataMonthlySales($_SESSION['Year']); ?> ;
         console.log(activityChartData);
        var activityChartCanvas = $("#myChartattach").get(0).getContext("2d");

        var activityChart = new Chart(activityChartCanvas, {
            type: 'bar',
            data: activityChartData,
            options: {
                tooltips: {
                    mode: 'index',
                    intersect: false
                },
                responsive: true,
                scales: {
                    xAxes: [{
                        stacked: true,
                    }],
                    yAxes: [{
                        stacked: true
                    }]
                }
            }
        });
    }
});
</script>

<?php
function getStatus($order_no) {
   global $connection;

   $sql="SELECT * from `order` where order_no='$order_no' or id='$order_no'";

   $result=mysqli_query($connection, $sql);
   if (strtoupper($_SESSION["UserName"])=="SUPER") {
	   if ($result) {
      $row=mysqli_fetch_assoc($result);

      if ($row["cancelled_by"]!="") {
         return "Cancelled";
      } else {
         if ($row["delivered_to_logistic_by"]!="") {
            if ($row["finance_payment_paid_by"]!="") {
               return "Delivered (Paid)";
            } else {
               return "Delivered (Paid)";
            }

         } else {
            if ($row["packed_by"]==1) {
               if ($row["finance_payment_paid_by"]!="") {
                  return "Packing";
               } else {
                  return "Packing";
               }
            } else {
               if ($row["finance_approved_by"]!="") {
                  if ($row["finance_payment_paid_by"]!="") {
                     return "Ready to collect";
                  } else {
                     return "Ready to collect";
                  }
               } else {
                  if ($row["logistic_approved_by"]!="") {
                     return "Packing";
                  } else {
                     if ($row["acknowledged_by"]!="") {
                        return "Acknowledged";
                     } else {
                        return "Pending";
                     }
                  }
               }
            }
         }
      }
   }
	}else{   
   if ($result) {
      $row=mysqli_fetch_assoc($result);

      if ($row["cancelled_by"]!="") {
         return "Cancelled";
      } else {
         if ($row["delivered_to_logistic_by"]!="") {
            if ($row["finance_payment_paid_by"]!="") {
               return "Delivered";
            } else {
               return "Delivered";
            }

         } else {
            if ($row["packed_by"]==1) {
               if ($row["finance_payment_paid_by"]!="") {
                  return "Packing";
               } else {
                  return "Packing";
               }
            } else {
               if ($row["finance_approved_by"]!="") {
                  if ($row["finance_payment_paid_by"]!="") {
                     return "Ready to collect";
                  } else {
                     return "Ready to collect";
                  }
               } else {
                  if ($row["logistic_approved_by"]!="") {
                     return "Packing";
                  } else {
                     if ($row["acknowledged_by"]!="") {
                        return "Acknowledged";
                     } else {
                        return "Pending";
                     }
                  }
               }
            }
         }
      }
   }
}
}
function orderPaid($order_no) {
   global $connection;

   $sql="SELECT * from `order` where order_no='$order_no' or id='$order_no'";

   $result=mysqli_query($connection, $sql);
   if ($result) {
      $row=mysqli_fetch_assoc($result);

      if ($row["finance_payment_paid_by"]!="") {
         return true;
      } else {
         return false;
      }
   }
}

function get6MonthsSalesLabel() {
   $month1=date("M Y", strtotime("-5 months"));
   $month2=date("M Y", strtotime("-4 months"));
   $month3=date("M Y", strtotime("-3 months"));
   $month4=date("M Y", strtotime("-2 months"));
   $month5=date("M Y", strtotime("-1 months"));
   $month6=date("M Y", strtotime("-0 months"));

   return "['$month1', '$month2', '$month3', '$month4', '$month5', '$month6']";
}

function userIsMaster($centre_code) {
   global $connection;

   $sql="SELECT * from master where master_code='$centre_code'";
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);
   if ($num_row>0) {
      return true;
   } else {
      return false;
   }
}

function getTotalSalesByMonth($centre_code, $month) {
   global $connection;

   if (userIsMaster($centre_code)) {
      $sql="SELECT sum(c.amount) as total from collection c, centre cen where c.centre_code=cen.centre_code
      and cen.upline='$centre_code' and month(c.collection_date_time)='$month' and void='0'";
   } else {
      if (strtoupper($_SESSION["UserName"])=="SUPER") {
         $sql="SELECT sum(amount) as total from collection where month(collection_date_time)='$month' and void='0'";
      } else {
         $sql="SELECT sum(amount) as total from collection where month(collection_date_time)='$month' and centre_code='$centre_code'
         and void='0'";
      } 
   }
  //  echo $sql; die;
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   if ($row["total"]!="") {
      return $row["total"];
   } else {
      return 0;
   }
}


function get6MonthsSalesQty($centre_code, &$minValue, &$maxValue) {
   $month1=date("m", strtotime("-5 months"));
   $month2=date("m", strtotime("-4 months"));
   $month3=date("m", strtotime("-3 months"));
   $month4=date("m", strtotime("-2 months"));
   $month5=date("m", strtotime("-1 months"));
   $month6=date("m", strtotime("-0 months"));

   $sales1=getTotalSalesByMonth($centre_code, $month1);
   $sales2=getTotalSalesByMonth($centre_code, $month2);
   $sales3=getTotalSalesByMonth($centre_code, $month3);
   $sales4=getTotalSalesByMonth($centre_code, $month4);
   $sales5=getTotalSalesByMonth($centre_code, $month5);
   $sales6=getTotalSalesByMonth($centre_code, $month6);

   $maxValue=0;
   if ($sales1>$maxValue) {
      $maxValue=$sales1;
   }

   if ($sales2>$maxValue) {
      $maxValue=$sales2;
   }

   if ($sales3>$maxValue) {
      $maxValue=$sales3;
   }

   if ($sales4>$maxValue) {
      $maxValue=$sales4;
   }

   if ($sales5>$maxValue) {
      $maxValue=$sales5;
   }

   if ($sales6>$maxValue) {
      $maxValue=$sales6;
   }

   $minValue=$maxValue;
   if ($sales1>0) {
      if ($minValue>$sales1) {
         $minValue=$sales1;
      }
   }

   if ($sales2>0) {
      if ($minValue>$sales2) {
         $minValue=$sales2;
      }
   }

   if ($sales3>0) {
      if ($minValue>$sales3) {
         $minValue=$sales3;
      }
   }

   if ($sales4>0) {
      if ($minValue>$sales4) {
         $minValue=$sales4;
      }
   }

   if ($sales5>0) {
      if ($minValue>$sales5) {
         $minValue=$sales5;
      }
   }

   if ($sales6>0) {
      if ($minValue>$sales6) {
         $minValue=$sales6;
      }
   }

   return "[$sales1, $sales2, $sales3, $sales4, $sales5, $sales6]";
}

function getMonthlySalesBySubCategory($year){
  global $connection, $year_start_date, $year_end_date;

  $addWhere = '';
  if ($_SESSION["UserType"]!="S" || $_SESSION["UserType"]=="H
  " || $_SESSION["UserType"]=="C" || $_SESSION["UserType"]=="R" || $_SESSION["UserType"]=="CM" || $_SESSION["UserType"]=="T") {
  $centre_code = mysqli_real_escape_string($connection, $_SESSION["CentreCode"]);
  $addWhere = "and centre_code = '$centre_code'";
  }

  //$sql = "SELECT hh.sub_category, SUM(hh.total) as grand_total, hh.order_date FROM (SELECT p.sub_category, o.total, DATE_FORMAT(CAST(ordered_on AS DATE), '%Y-%m') AS order_date FROM `order` o LEFT JOIN product p ON o.product_code=p.product_code";
  //$sql .= " WHERE o.centre_code = '". $centre_code . "' AND DATE_FORMAT(CAST(ordered_on AS DATE), '%Y-%m') between '".$year."-01' AND '".$year."-10') as hh GROUP BY hh.sub_category, hh.order_date";
  //$sql = "select cd.code, sum(cl.amount) as total, DATE_FORMAT(collection_date_time, '%Y-%m') as collection_date from collection cl left join course co on co.id=cl.course_id left join (select code from codes where parent in (select code from codes where parent='' and module='CATEGORY') group by code) cd on co.course_name like CONCAT('% ',cd.code,' %') where  collection_type not in ('addon-product') AND cl.void=0 AND DATE_FORMAT(collection_date_time, '%Y-%m') between '$year-01' AND '$year-12' $addWhere group by cd.code, DATE_FORMAT(collection_date_time, '%Y-%m')";

  
    $sql = "SELECT 'School Fees' as product_code, sum(amount) as total, DATE_FORMAT(collection_date_time, '%Y-%m') as collection_date from collection where product_code in ('Multimedia Fees', 'School Fees', 'Facility Fees') AND DATE_FORMAT(collection_date_time, '%Y-%m') between '".date('Y-m',strtotime($year_start_date))."' AND '".date('Y-m',strtotime($year_end_date))."' $addWhere group by DATE_FORMAT(collection_date_time, '%Y-%m')";
    //  echo $sql;
    $sql .= " Union all ";
    $sql .= "SELECT 'Material Fees' as product_code, sum(amount) as total, DATE_FORMAT(collection_date_time, '%Y-%m') as collection_date from collection where product_code in ('Integrated Module', 'Link', 'Mandarin Modules') AND DATE_FORMAT(collection_date_time, '%Y-%m') between '".date('Y-m',strtotime($year_start_date))."' AND '".date('Y-m',strtotime($year_end_date))."' $addWhere group by DATE_FORMAT(collection_date_time, '%Y-%m')";
    $sql .= " Union all ";
    $sql .= "SELECT 'Enhanced Foundation Fees' as product_code, sum(amount) as total, DATE_FORMAT(collection_date_time, '%Y-%m') as collection_date from collection where product_code in ('International English', 'IQ Math', 'Mandarin', 'International Art', 'Pendidikan Islam') AND DATE_FORMAT(collection_date_time, '%Y-%m') between '".date('Y-m',strtotime($year_start_date))."' AND '".date('Y-m',strtotime($year_end_date))."' $addWhere group by DATE_FORMAT(collection_date_time, '%Y-%m')";
  $result = mysqli_query($connection, $sql);

  $o = array();
  if ($result){
    while($row=mysqli_fetch_assoc($result)){
      $o[$row['product_code']][$row['collection_date']] = $row['total'];
    }
  }

  return $o;
}

function get1YearLabel($year){

    global $year_start_date, $year_end_date;
    $period = getMonthList($year_start_date, $year_end_date);
    $months = array();

    foreach ($period as $dt) {
        $months[$dt->format("M Y")] = $dt->format("Y-m");
    }

  return $months;
}

function getChartSubCategories(){
  global $connection;

  $sql = "SELECT distinct sub_sub_category FROM product p";
  $result=mysqli_query($connection, $sql);
  $sub_categories = array();
  if ($result) {
    while($row=mysqli_fetch_assoc($result)){
      $sub_categories[] = $row['sub_sub_category'];
    }
  }

  return $sub_categories;
}

function getChartDataMonthlySales($year){
  global $connection;

  //$sql = "SELECT distinct sub_sub_category FROM product p";
  //$result=mysqli_query($connection, $sql);
  $sub_categories = array(
    "School Fees",
    "Material Fees",
    "Enhanced Foundation Fees"
  );
//   if ($result) {
//     while($row=mysqli_fetch_assoc($result)){
//       $sub_categories[] = $row['sub_sub_category'];
//     }
//   }

  $months = get1YearLabel($year);
  $sales = getMonthlySalesBySubCategory($year);

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

  foreach($sub_categories as $sub_sub_category){
    $data = array();
    foreach($months as $month){
      if (isset($sales[$sub_sub_category][$month])) {
        $data[] = $sales[$sub_sub_category][$month];
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

  return json_encode($chart_data);
}
?>
<style>
    .rpt-table tr td{
        border:1px solid #80808082;
    }
    .rpt-table{
        border-collapse: unset;
        border-spacing: 0px;
    }
</style>