<div id="estimates_status_chart_columns"></div>

<script type="text/javascript">
    $(document).ready(function () {

        var invoices = <?php echo $invArray; ?>;
        var estimates = <?php echo $estArray; ?>;
        var options = {
          series: [{
                name: "<?php echo lang("invoices"); ?>",
                data: invoices
                } , {
                name: "<?php echo lang("estimates"); ?>",
                data: estimates
            }],
          chart: {
          type: 'bar',
          height: 350
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded'
          },
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        xaxis: {
          categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        },
        yaxis: {
          title: {
            text: "<?php echo lang("number"); ?>"
          }
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val
            }
          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#estimates_status_chart_columns"), options);
        chart.render();
 
    });
</script>    

