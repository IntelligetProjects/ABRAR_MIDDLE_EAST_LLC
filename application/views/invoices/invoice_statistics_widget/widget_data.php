<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <i class="fa fa fa-file-text-o"></i>&nbsp; <?php echo lang("invoice_statistics"); ?>

        <?php if ($currencies && $this->login_user->user_type == "staff") { ?>
            <div class="pull-right">
                <span class="pull-right dropdown">
                    <div class="dropdown-toggle clickable font-14" type="button" data-toggle="dropdown" aria-expanded="true" >
                        <i class="fa fa-ellipsis-h ml10 mr10"></i>
                    </div>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <?php
                            $default_currency = get_setting("default_currency");
                            echo js_anchor($default_currency, array("class" => "load-currency-wise-data", "data-value" => $default_currency)); //default currency

                            foreach ($currencies as $currency) {
                                echo js_anchor($currency->currency, array("class" => "load-currency-wise-data", "data-value" => $currency->currency));
                            }
                            ?>
                        </li>
                    </ul>
                </span>
            </div>
        <?php } ?>
    </div>
    <?php 
        
        $invoices = json_decode($invoices);
        $payments = json_decode($payments);

        $invArray = array();
        $payArray = array();
        foreach ($invoices as $key => $data) {
             $invArray[] = $data[1];
        }
        $invArray = json_encode($invArray);

        foreach ($payments as $key => $data2) {
             $payArray[] = $data2[1];
        }
        $payArray = json_encode($payArray);
     ?>
    <div class="panel-body ">
        <div id="invoice-payment-statistics-flotchart" style="width: 100%; height: 300px;"></div>
    </div>

</div>

<script type="text/javascript">
    $(document).ready(function () {
        
        var invoices = <?php echo $invArray; ?>;
        var payments = <?php echo $payArray; ?>;
         var options = {
          series: [{
          name: "<?php echo lang("invoices"); ?>",
          data: invoices
        }, {
          name: "<?php echo lang("invoice_payments"); ?>",
          data: payments
        }],
          chart: {
          type: 'area',
          stacked: false,
          height: 323,
          zoom: {
            enabled: false
          },
        },
        dataLabels: {
          enabled: false
        },
        markers: {
          size: 0,
        },
        fill: {
          type: 'gradient',
          gradient: {
              shadeIntensity: 1,
              inverseColors: false,
              opacityFrom: 0.45,
              opacityTo: 0.05,
              stops: [20, 100, 100, 100]
            },
        },
        xaxis: {
          categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        },
        tooltip: {
          shared: true
        },
        legend: {
          position: 'top',
          horizontalAlign: 'left',
          offsetX: -10
        }
        };

        var chart = new ApexCharts(document.querySelector("#invoice-payment-statistics-flotchart"), options);
        chart.render();
    });
</script>    

