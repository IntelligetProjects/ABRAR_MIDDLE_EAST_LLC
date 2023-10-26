<div id="cats_chart"></div>
<script>
$(document).ready(function(){

var options = {
          series: [
                <?php $i = 0; while (count($cats) > $i) {
                    echo isset($cats[$i]->totals)?$cats[$i]->totals:0;
                    echo ",";
                    $i++;
                } ?>
            ],
          chart: {
          type: 'pie',
        },
        labels: [
            <?php $i = 0; while (count($cats) > $i) {
                    echo "'" . $cats[$i]->titles . "'";
                    echo ",";
                    $i++;
            } ?>
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

        var chart = new ApexCharts(document.querySelector("#cats_chart"), options);
        chart.render();

});    
</script>