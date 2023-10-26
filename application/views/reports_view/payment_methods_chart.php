
<div id="pays_chart"></div>

<script>
$(document).ready(function(){

    var options2 = {
      series: [
            <?php echo isset($payment_methods[0]->total)?$payment_methods[0]->total:0; ?>,
            <?php echo isset($payment_methods[1]->total)?$payment_methods[1]->total:0; ?>,
            <?php echo isset($payment_methods[2]->total)?$payment_methods[2]->total:0; ?>
        ],
      chart: {
      width: 380,
      type: 'donut',
    },
    dataLabels: {
      enabled: false
    },
    fill: {
      type: 'gradient',
    },
    labels: [
       "<?php echo lang('cheque') ?>", 
       "<?php echo lang('cash')?>", 
       "<?php echo lang('bank_transfer')?>"
    ],
    responsive: [{
      breakpoint: 480,
      options: {
        chart: {
          width: 200
        }
      }
    }]
    };

    var chart2 = new ApexCharts(document.querySelector("#pays_chart"), options2);
    chart2.render();

});    
</script>