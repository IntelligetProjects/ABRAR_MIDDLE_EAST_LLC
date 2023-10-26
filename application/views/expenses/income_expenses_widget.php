<div class="panel panel-default <?php echo $custom_class; ?>">
    <div class="panel-heading clearfix">
        <i class="fa fa-bar-chart"></i>&nbsp;<?php echo lang("income_vs_expenses"); ?>

        <span class="help pull-right" data-toggle="tooltip" title="<?php echo lang('income_expenses_widget_help_message') ?>"><i class="fa fa-question-circle"></i></span>
    </div>
    <div class="panel-body ">
        <div id="income-expense" style="width: 100%; height: 340px;"></div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        

         var options = {
          series: [<?php echo $income ?>, <?php echo $expenses ?>],
          chart: {
          width: 380,
          type: 'pie',
        },

        legend: {
          show: false
        },
        labels: ['Income', 'Expense'],
        responsive: [{
          breakpoint: 380,
          options: {
            chart: {
              width: 200
            },
            legend: {
              show: false
            }
          }
        }]
        };

        var chart = new ApexCharts(document.querySelector("#income-expense"), options);
        chart.render();
    });
</script>    