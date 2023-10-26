<div id="stats_chart"></div>

<script>
$(document).ready(function(){

var options6 = {
          series: [
                <?php echo isset($status[0]->total)?$status[0]->total:0; ?>,
                <?php echo isset($status[1]->total)?$status[1]->total:0; ?>,
                <?php echo isset($status[2]->total)?$status[2]->total:0; ?>,
                <?php echo isset($status[3]->total)?$status[3]->total:0; ?>
            ],
          chart: {
          type: 'pie',
        },
        labels: [
            "<?php echo lang('open')?>",
            "<?php echo lang('completed')?>",
            "<?php echo lang('hold')?>",
            "<?php echo lang('canceled')?>"
        ],
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 200
            },
            legend: {
              position: 'bottom'
            }
          }
        }]
        };

        var chart6 = new ApexCharts(document.querySelector("#stats_chart"), options6);
        chart6.render();

});    
</script>