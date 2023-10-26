<!-- <div class="main-card mb-3 card">
    <div class="card-body"> -->
        <!-- <h5 class="card-title"><?php echo lang("task_status"); ?></h5> -->
        <!-- <div style="width: 50%;"> -->
        <canvas id="mons_chart"></canvas>
        <!-- </div> -->
<!--     </div>
</div>  --> 

<script>
$(document).ready(function(){
    
    configPie = {
    type: 'bar',
    data: {
        datasets: [{
            data: [
                <?php $i = 0; while (count($mons) > $i) {
                    echo isset($mons[$i]->total)?$mons[$i]->total:0;
                    echo ",";
                    $i++;
                } ?>
            ],
            backgroundColor: [
                window.chartColors.green,
                window.chartColors.yellow,
                window.chartColors.blue,
                window.chartColors.red,
                window.chartColors.orange,
                window.chartColors.purple,
                window.chartColors.grey,
                window.chartColors.green,
                window.chartColors.yellow,
                window.chartColors.blue,
                window.chartColors.red,
                window.chartColors.orange,
                window.chartColors.purple,
                window.chartColors.grey,
                window.chartColors.green,
                window.chartColors.yellow,
                window.chartColors.blue,
                window.chartColors.red,
                window.chartColors.orange,
                window.chartColors.purple,
                window.chartColors.grey,
                window.chartColors.green,
                window.chartColors.yellow,
                window.chartColors.blue,
                window.chartColors.red,
                window.chartColors.orange,
                window.chartColors.purple,
                window.chartColors.grey,
                window.chartColors.green,
                window.chartColors.yellow,
                window.chartColors.blue,
                window.chartColors.red,
                window.chartColors.orange,
                window.chartColors.purple,
                window.chartColors.grey,


            ],
            label: 'Total Amount'
        }],
        labels: [
            'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
        ]
    },
    options: {
        responsive: true,
        legend: {
            position: '',
        }
    }
};


    if (document.getElementById('mons_chart')) {
        var ctx2 = document.getElementById('mons_chart').getContext('2d');
        window.myPie = new Chart(ctx2, configPie);
    }

});    
</script>