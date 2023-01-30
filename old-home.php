
<?php
//session_start();
$year=$_SESSION["Year"];

// include_once("../mysql.php");
include_once("mysql.php");

include_once("./admin/functions.php");

$currentMonth=date("m");
$strCurrentMonth=date("M");




if ($_SESSION["UserType"]=="S") {
?>
  <!--  <div class="row">


      <?php
      if (hasRightGroupXOR($_SESSION["UserName"], "SalesView")) {
         ?>
         <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
               <div class="card-body">
                  <?php
                  if (hasRightGroupXOR($_SESSION["UserName"], "SalesView|SalesEdit")) {
                     ?>
                     <h5 class="card-title"><a href="index.php?p=sales">Total Sales</a></h5>
                     <?php
                  } else {
                     ?>
                     <h5 class="card-title">Total Sales</h5>
                     <?php
                  }
                  ?>
                  <div class="d-flex justify-content-between">
                     <p>Total Sales</p>
                  </div>
                  <canvas id="chart"></canvas>
               </div>
            </div>
         </div>
         <?php
      }
      ?>
      <div class="col-lg-12 grid-margin d-flex flex-column justify-content-stretch">
         <div class="row grid-margin flex-grow">
            <?php
            $sql="SELECT * from `master` where upline='".$_SESSION["CentreCode"]."'";
            $result=mysqli_query($connection, $sql);
            $num_row=mysqli_num_rows($result);
            ?>
            <div class="col-md-2">
               <div class="card card-statistics bg-success text-white">
                  <div class="card-body">
                     <div class="icon-wrapper mb-4">
                        <i class="fa fa-user-circle"></i>
                        <span class="badge"><i class="mdi mdi-alert-circle"></i></span>
                     </div>
                     <?php
                     if (hasRightGroupXOR($_SESSION["UserName"], "MasterView|MasterEdit")) {
                        ?>
                        <h6><a href="index.php?p=master">Number of Master</a></h6>
                        <?php
                     } else {
                        ?>
                        <h6>Number of Master</h6>
                        <?php
                     }
                     ?>
                     <p><div class="uk-text-bold uk-text-large"><?php echo $num_row?></div></p>
                  </div>
               </div>
            </div>
            <?php
            $sql="SELECT * from centre where upline='".$_SESSION["CentreCode"]."'";
            $result=mysqli_query($connection, $sql);
            $num_row=mysqli_num_rows($result);
            ?>
            <div class="col-md-2">
               <div class="card card-statistics bg-primary text-white">
                  <div class="card-body">
                     <div class="icon-wrapper mb-4">
                        <i class="fa fa-building"></i>
                        <span class="badge"><i class="mdi mdi-alert-circle"></i></span>
                     </div>
                     <?php
                     if (hasRightGroupXOR($_SESSION["UserName"], "CentreEdit|CentreView")) {
                        ?>
                        <h6><a href="index.php?p=centre">Number of Centre</a></h6>
                        <?php
                     } else {
                        ?>
                        <h6>Number of Centre</h6>
                        <?php
                     }
                     ?>
                     <p><div class="uk-text-bold uk-text-large"><?php echo $num_row?></div></p>
                  </div>
               </div>
            </div>
            <?php
            $sql="SELECT s.* from student s, centre c where c.upline='".$_SESSION["CentreCode"]."' and s.centre_code=c.centre_code
            and s.student_status='A'";
            $result=mysqli_query($connection, $sql);
            $num_row=mysqli_num_rows($result);
            ?>
            <div class="col-md-2">
               <div class="card card-statistics bg-info text-white">
                  <div class="card-body">
                     <div class="icon-wrapper mb-4">
                        <i class="fa fa-address-card-o"></i>
                        <span class="badge"><i class="mdi mdi-alert-circle"></i></span>
                     </div>
                     <?php
                     if (hasRightGroupXOR($_SESSION["UserName"], "StudentEdit|StudentView")) {
                        ?>
                        <h6>Active <a href="index.php?p=student">Student</a></h6>
                        <?php
                     } else {
                        ?>
                        <h6>Active Student</h6>
                        <?php
                     }
                     ?>
                     <p><div class="uk-text-bold uk-text-large"><?php echo $num_row?></div></p>
                  </div>
               </div>
            </div>
            <?php
            $sql="SELECT o.* from `order` o, centre c where month(ordered_on)='".$currentMonth."' and o.centre_code=c.centre_code";
            $result=mysqli_query($connection, $sql);
            $num_row=mysqli_num_rows($result);
            ?>
            <div class="col-md-2">
               <div class="card card-statistics bg-info text-white">
                  <div class="card-body">
                     <div class="icon-wrapper mb-4">
                        <i class="fa fa-shopping-cart"></i>
                        <span class="badge"><i class="mdi mdi-alert-circle"></i></span>
                     </div>
                     <?php
                     if (hasRightGroupXOR($_SESSION["UserName"], "OrderStatusEdit|OrderStatusView")) {
                        ?>
                        <h6>Number of New <a href="index.php?p=order_status_pg1">Order</a> (<?php echo $strCurrentMonth?>)</h6>
                        <?php
                     } else {
                        ?>
                        <h6>Number of New Order (<?php echo $strCurrentMonth?>)</h6>
                        <?php
                     }
                     ?>
                     <p><div class="uk-text-bold uk-text-large"><?php echo $num_row?></div></p>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div> -->
   <?php
}

?>

<!-- Franchisee Dashboard  -->
<?php
if (($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) {
   if (hasRightGroupXOR($_SESSION["UserName"], "DashboardView")) {
      ?>
      <div class="row">
         <?php
         if (hasRightGroupXOR($_SESSION["UserName"], "SalesEdit|SalesView")) {
            ?>
            <div class="col-lg-12 grid-margin stretch-card">
               <div class="card">
                  <div class="card-body">
                     <?php
                     if (hasRightGroupXOR($_SESSION["UserName"], "SalesView|SalesEdit")) {
                        ?>
                        <h5 class="card-title"><a href="index.php?p=sales">Total Sales</a></h5>
                        <?php
                     } else {
                        ?>
                        <h5 class="card-title">Total Sales</h5>
                        <?php
                     }
                     ?>
                     <div class="d-flex justify-content-between">
                        <p>Total Sales</p>
                     </div>
                     <canvas id="chart" height="250"></canvas>
                  </div>
               </div>
            </div>
            <?php
         }
         ?>
         <div class="col-lg-12 grid-margin d-flex flex-column justify-content-stretch">
            <div class="row grid-margin flex-grow">
               <?php
               if (hasRightGroupXOR($_SESSION["UserName"], "SalesEdit|SalesView")) {
                  $sql="SELECT sum(amount) as total from collection where centre_code='".$_SESSION["CentreCode"]."' and month(collection_date_time)='$currentMonth'";
                  $result=mysqli_query($connection, $sql);
                  $row=mysqli_fetch_assoc($result);
                  $total_sales=$row["total"];
                  ?>
                  <div class="col-md-2">
                     <div class="card card-statistics bg-success text-white">
                        <div class="card-body">
                           <div class="icon-wrapper mb-4">
                              <i class="mdi mdi-security"></i>
                              <span class="badge"><i class="mdi mdi-alert-circle"></i></span>
                           </div>
                           <?php
                           if (hasRightGroupXOR($_SESSION["UserName"], "SalesView|SalesEdit")) {
                              ?>
                              <h6><a href="index.php?p=sales">Total Sales</a> (<?php echo $strCurrentMonth?>)</h6>
                              <?php
                           } else {
                              ?>
                              <h6>Total Sales (<?php echo $strCurrentMonth?>)</h6>
                              <?php
                           }
                           ?>
                           <p><div class="uk-text-bold uk-text-large"><?php echo $total_sales?></div></p>
                        </div>
                     </div>
                  </div>
                  <?php
               }
               ?>
               <?php
               $sql="SELECT count(*) as count from student where month(date_created)='$currentMonth' and
               centre_code='".$_SESSION["CentreCode"]."' and student_status='A'";
            //$sql="SELECT count(*) as count from student where centre_code='".$_SESSION["CentreCode"]."' and `student_status`='A'";
               $result=mysqli_query($connection, $sql);
               $num_row=mysqli_num_rows($result);
               $row=mysqli_fetch_assoc($result);
               ?>
               <div class="col-md-2">
                  <div class="card card-statistics bg-primary text-white">
                     <div class="card-body">
                        <div class="icon-wrapper mb-4">
                           <i class="fa fa-address-card-o"></i>
                           <span class="badge"><i class="mdi mdi-alert-circle"></i></span>
                        </div>
                        <?php
                        if (hasRightGroupXOR($_SESSION["UserName"], "StudentEdit|StudentView")) {
                           ?>
                           <h6>Active <a href="index.php?p=student">Student</a> (<?php echo $strCurrentMonth?>)</h6>
                           <?php
                        } else {
                           ?>
                           <h6>Active Student (<?php echo $strCurrentMonth?>)</h6>
                           <?php
                        }
                        ?>
                        <p><div class="uk-text-bold uk-text-large"><?php echo $row["count"]?></div></p>
                     </div>
                  </div>
               </div>
               <?php
               $sql="SELECT count(*) from visitor where centre_code='".$_SESSION["CentreCode"]."' and month(date_created)='$currentMonth'";
            //$sql="SELECT count(*) from visitor where centre_code='".$_SESSION["CentreCode"];
               $result=mysqli_query($connection, $sql);
               $num_row=mysqli_num_rows($result);
               ?>
               <div class="col-md-2">
                  <div class="card card-statistics bg-secondary text-white">
                     <div class="card-body">
                        <div class="icon-wrapper mb-4">
                           <i class="mdi mdi-speedometer"></i>
                           <span class="badge"><i class="mdi mdi-alert-circle"></i></span>
                        </div>
                        <?php
                        if (hasRightGroupXOR($_SESSION["UserName"], "VisitorEdit|VisitorView")) {
                           ?>
                           <h6><a href="index.php?p=visitor_qr_list">Visitor</a> (<?php echo $strCurrentMonth?>)</h6>
                           <?php
                        } else {
                           ?>
                           <h6>Visitor (<?php echo $strCurrentMonth?>)</h6>
                           <?php
                        }
                        ?>
                        <p><div class="uk-text-bold uk-text-large"><?php echo $row["count"]?></div></p>
                     </div>
                  </div>
               </div>
               <?php
         //$sql="SELECT count(*) from student where month(date_created)='$currentMonth' and centre_code='".$_SESSION["CentreCode"]."'
         //and student_status='A'";

               $sql="SELECT count(*) as no_of_student, c.course_name, a.class_id from allocation a, course c, student s
               where a.course_id=c.id and a.student_id=s.id and s.student_status='A' and a.year='$year'
               and s.centre_code='".$_SESSION["CentreCode"]."' group by s.centre_code";
               $result=mysqli_query($connection, $sql);
               $num_row=mysqli_num_rows($result);
               $row=mysqli_fetch_assoc($result);
               ?>
               <div class="col-md-2">
                  <div class="card card-statistics bg-info text-white">
                     <div class="card-body">
                        <div class="icon-wrapper mb-4">
                           <i class="fa fa-address-card-o"></i>
                           <span class="badge"><i class="mdi mdi-alert-circle"></i></span>
                        </div>
                        <?php
                        if (hasRightGroupXOR($_SESSION["UserName"], "StudentEdit|StudentView")) {
                           ?>
                           <h6>Active Classes (<?php echo $strCurrentMonth?>)</h6>
                           <?php
                        } else {
                           ?>
                           <h6>Active Classes (<?php echo $strCurrentMonth?>)</h6>
                           <?php
                        }
                        ?>
                        <p><div class="uk-text-bold uk-text-large"><?php echo $row["no_of_student"]?></div></p>
                     </div>
                  </div>
               </div>
               <?php
               $today=date("Y-m-d");
               $strToday=date("d/m/Y");
               $sql="SELECT product_code from product";
               $result=mysqli_query($connection, $sql);
               $count=0;
               while ($row=mysqli_fetch_assoc($result)) {
                  $bal=calcBal($_SESSION["CentreCode"], $row["product_code"], $today);
                  if ($bal<=5) {
                     $count++;
                  }
               }
               ?>
               <div class="col-md-2">
                  <div class="card card-statistics bg-danger text-white">
                     <div class="card-body">
                        <div class="icon-wrapper mb-4">
                           <i class="fa fa-book"></i>
                           <span class="badge"><i class="mdi mdi-alert-circle"></i></span>
                        </div>
                        <?php
                        if (hasRightGroupXOR($_SESSION["UserName"], "ProductEdit|ProductView")) {
                           ?>
                           <h6><a href="index.php?p=product">Product</a> with Low Stock (as at <?php echo $strToday?>)</h6>
                           <?php
                        } else {
                           ?>
                           <h6>Product with Low Stock (as at <?php echo $strToday?>)</h6>
                           <?php
                        }
                        ?>
                        <p><div class="uk-text-bold uk-text-large"><?php echo $count?></div></p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <?php
      $sql="SELECT distinct order_no, ordered_on from `order` where centre_code='".$_SESSION["CentreCode"]."' and month(ordered_on)='$currentMonth' order by ordered_on desc";
      $result=mysqli_query($connection, $sql);
      $num_row=mysqli_num_rows($result);
      ?>

       <div class="row">
         <div class="col-lg-12 grid-margin d-flex flex-column justify-content-stretch">
            <div class="row grid-margin flex-grow">
               <div class="col-md-12">
                  <div class="card card-statistics bg-success text-white">
                     <div class="card-body">
                        <table class="uk-table">
                           <tr>
                              <td colspan="3"><div class="uk-text-center"><h3>Order Status</h3></div></td>
                           </tr>
                           <tr>
                              <td>Order No.</td>
                              <td>Order Date</td>
                              <td>Status</td>
                           </tr>
                           <?php
                           function getStatus($order_no) {
                              global $connection;

                              $sql="SELECT * from `order` where order_no='$order_no' or id='$order_no'";

                              $result=mysqli_query($connection, $sql);
                              if ($result) {
                                 $row=mysqli_fetch_assoc($result);

                                 if ($row["cancelled_by"]!="") {
                                    return "Cancelled";
                                 } else {
                                    if ($row["delivered_to_logistic_by"]!="") {
                                       if ($row["finance_payment_paid_by"]!="") {
                                          return "Delivered (Paid)";
                                       } else {
                                          return "Delivered (Pending Payment)";
                                       }

                                    } else {
                                       if ($row["packed_by"]==1) {
                                          if ($row["finance_payment_paid_by"]!="") {
                                             return "Packed (Paid)";
                                          } else {
                                             return "Packed (Pending Payment)";
                                          }
                                       } else {
                                          if ($row["finance_approved_by"]!="") {
                                             if ($row["finance_payment_paid_by"]!="") {
                                                return "Finance Approved (Paid)";
                                             } else {
                                                return "Finance Approved (Pending Payment)";
                                             }
                                          } else {
                                             if ($row["logistic_approved_by"]!="") {
                                                return "Logistic Approved";
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

                           if ($num_row>0) {
                              while ($row=mysqli_fetch_assoc($result)) {
                                 ?>
                                 <tr>
                                    <td><?php echo $row["order_no"]?></td>
                                    <td><?php echo $row["ordered_on"]?></td>
                                    <td><?php echo getStatus($row["order_no"])?></td>
                                 </tr>
                                 <?php
                              }
                           } else {
                              ?>
                              <tr>
                                 <td colspan="3">No Order Found</td>
                              </tr>
                              <?php
                           }
                           ?>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <?php
   } else {
      ?>
      <div class="uk-margin uk-margin-top uk-margin-left uk-margin-right uk-margin-bottom">
         <div class="uk-text-center">
            <h1>Welcome back, <?php echo $_SESSION["UserName"]?>!</h1>
         </div>
      </div>
      <?php
   }
}
?>

<?php
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
      $sql="SELECT sum(amount) as total from collection where month(collection_date_time)='$month' and centre_code='$centre_code'
      and void='0'";
   }
//echo $sql;
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
?>

<script>
var ctx = document.getElementById('myChartattach');
var myChartattach = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Nov 2018', 'Dec 2018', 'Jan 2019', 'Feb 2019', 'Mar 2019', 'Apr 2019'],
        datasets: [{
            label: '# of Votes',
            data: [470, 240, 150, 351, 120, 435],
            backgroundColor: [
                'rgba(82, 209, 184, 0.2)'
            ],
            borderColor: [
                'rgba(80, 209, 184, 1)'
            ],
            borderWidth: 1,

        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                },
            }]
        },
        layout: {
            padding: 1,
        }
    }
});
</script>

<script>
 $(function() {
  if ($("#chart").length) {
   var activityChartData = {
//        labels: ["2014", "2015", "2016", "2017", "2018", "2019"],
labels : <?php echo get6MonthsSalesLabel();?>,
datasets: [{
//          data: [25, 40, 40, 60, 90, 105],
data: <?php echo get6MonthsSalesQty($_SESSION["CentreCode"], $minValue, $maxValue);?>,
backgroundColor: [
'rgba(82,209,183,.2)'
],
borderColor: [
'rgba(82,209,183,1)'
],
borderWidth: 2,
fill: 'origin',
}]
};
var activityChartOptions = {
   maintainAspectRatio: true,
   plugins: {
      filler: {
         propagate: false
      }
   },
   scales: {
      xAxes: [{
         gridLines: {
           lineWidth: 0,
           color: "rgba(255,255,255,0)"
        }
     }],
     yAxes: [{
            // display: false,
            <?php
            if ($minValue==$maxValue) {
               $minValue=$minValue-100;
            }
            ?>
            ticks: {
             //autoSkip: false,
             maxRotation: 0,
             // stepSize : 100,
             min: <?php echo $minValue?>,
             max: <?php echo $maxValue;?>
          }
       }]
    },
    legend: {
      display: false
   },
   tooltips: {
      enabled: true
   },
   elements: {
     line: {
       tension: 0
    }
 }
}
var activityChartCanvas = $("#chart").get(0).getContext("2d");
var activityChart = new Chart(activityChartCanvas, {
 type: 'line',
 data: activityChartData,
 options: activityChartOptions
});
}
});
 activityChartCanvas.height=500;
</script>
