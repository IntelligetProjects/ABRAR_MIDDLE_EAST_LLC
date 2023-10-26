<div id="emps_chart"></div>
<script>
$(document).ready(function(){

var options4 = {
          series: [
                <?php $i = 0; while (count($emps) > $i) {
                    echo isset($emps[$i]->totals)?$emps[$i]->totals:0;
                    echo ",";
                    $i++;
                } ?>
            ],
          chart: {
          type: 'pie',
        },
        labels: [
            <?php $i = 0; while (count($emps) > $i) {
                    echo "'" . $emps[$i]->titles . "'";
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

        var chart4 = new ApexCharts(document.querySelector("#emps_chart"), options4);
        chart4.render();

});    
</script>