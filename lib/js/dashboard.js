(function($) {
  'use strict';
  $(function() {
    if ($("#chart-activity").length) {
      var activityChartData = {
        labels: ["2011", "2012", "2013", "2014", "2015", "2016", "2017"],
        datasets: [{
          data: [10, 25, 40, 40, 60, 90, 105],
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
            ticks: {
  						autoSkip: false,
  						maxRotation: 0,
              stepSize : 15,
              max: 120
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
      var activityChartCanvas = $("#chart-activity").get(0).getContext("2d");
      var activityChart = new Chart(activityChartCanvas, {
        type: 'line',
        data: activityChartData,
        options: activityChartOptions
      });
    }
    if($("#total-visit-chart").length) {
      $("#total-visit-chart").sparkline('html', {
        enableTagOptions: true,
        width: '100%',
        height: '70',
        fillColor: 'false',
        barWidth: 2,
        barSpacing: 4,
        chartRangeMin: 0
      });
    }
    if($("#unique-visitor-chart").length) {
      $("#unique-visitor-chart").sparkline('html', {
        enableTagOptions: true,
        width: '100%',
        height: '70',
        fillColor: 'false',
        barWidth: 4,
        barSpacing: 4,
        chartRangeMin: 0
      });
    }
    if($("#page-views-chart").length) {
      $("#page-views-chart").sparkline('html', {
        enableTagOptions: true,
        width: '100%',
        height: '70',
        fillColor: 'false',
        barWidth: 4,
        barSpacing: 4,
        chartRangeMin: -20
      });
    }
    if($("#data-sent-chart").length) {
      var d1 = [
        [0, 20],
        [1, 70],
        [2, 35],
        [3, 50],
        [4, 10],
        [5, 25]
      ];
      var dataSentOptions = {
        series: {
            shadowSize: 0,
            curvedLines: { //This is a third party plugin to make curved lines
                apply: true,
                active: true,
                monotonicFit: true
            },
            lines: {
                show: false,
                lineWidth: 0,
            },
        },
        grid: {
            borderWidth: 0,
            labelMargin:10,
            hoverable: true,
            clickable: true,
            mouseActiveRadius:6,
        },
        xaxis: {
            tickDecimals: 0,
            ticks: false
        },

        yaxis: {
            tickDecimals: 0,
            ticks: false
        },

        legend: {
            show: false
        }

      };
      $.plot($("#data-sent-chart"), [
       {
        data: d1,
        label: 'data',
        color: '#b67eff',
        lines: {
            show: true,
            fill: 0.2,
            lineWidth: 2
        },
        curvedLines: {
            apply: true,
            monotonicFit: true
        }
       }
     ], dataSentOptions);
    }
    if($("#invite-sent-chart").length) {
      var d1 = [
        [0, 50],
        [1, 70],
        [2, 35],
        [3, 50],
        [4, 20],
        [5, 35]
      ];
      var inviteSentOptions = {
        series: {
            shadowSize: 0,
            curvedLines: { //This is a third party plugin to make curved lines
                apply: true,
                active: true,
                monotonicFit: true
            },
            lines: {
                show: false,
                lineWidth: 0,
            },
        },
        grid: {
            borderWidth: 0,
            labelMargin:10,
            hoverable: true,
            clickable: true,
            mouseActiveRadius:6,
        },
        xaxis: {
            tickDecimals: 0,
            ticks: false
        },

        yaxis: {
            tickDecimals: 0,
            ticks: false
        },

        legend: {
            show: false
        }

      };
      $.plot($("#invite-sent-chart"), [
       {
        data: d1,
        label: 'data',
        color: '#52d1b7',
        lines: {
            show: true,
            fill: 0.2,
            lineWidth: 2
        },
        curvedLines: {
            apply: true,
            monotonicFit: true
        }
       }
     ], inviteSentOptions);
    }
  });
})(jQuery);
