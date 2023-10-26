<div id="item_cats_chart"></div>

<script>
$(document).ready(function(){

var options12 = {
  series: [
                <?php $i = 0; while (count($cats) > $i) {
                    echo isset($cats[$i]->totals)?$cats[$i]->totals:0;
                    echo ",";
                    $i++;
                } ?>
            ],
  labels: [
            <?php $i = 0; while (count($cats) > $i) {
                    echo "'" . $cats[$i]->titles . "'";
                    echo ",";
                    $i++;
            } ?>
        ],
  chart: {
  type: 'donut',
},
responsive: [{
  breakpoint: 480,
  options: {
    chart: {
      width: 200
    },
    legend: {
      position: 'left'
    }
  }
}]
};

var chart12 = new ApexCharts(document.querySelector("#item_cats_chart"), options12);
chart12.render();

});    
</script>