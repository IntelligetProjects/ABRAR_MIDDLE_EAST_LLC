<div class="clearfix p20">
    <div class="panel clearfix">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <i class="fa fa-bar-chart pt10"></i>&nbsp; <?php echo lang("categories"); ?>
            </div>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <div class="card-body" style="width: 100%">
                        <?php items_cat_chart(); ?>
                    </div>
                </div>
                <div class="col-md-6">
                <div class="panel <?php echo "panel-primary" ?>">
                    <div class="panel-body ">
                        <div class="widget-icon">
                            <i class="fa <?php echo "fa-wrench" ?>"></i>
                        </div>
                        <div class="widget-details">
                            <h1><?php echo $services; ?>
                            <?php echo lang("services"); ?>
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="panel <?php echo "panel-success"; ?>">
                    <div class="panel-body ">
                        <div class="widget-icon">
                            <i class="fa <?php echo "fa-archive"; ?>"></i>
                        </div>
                        <div class="widget-details">
                            <h1><?php echo $products; ?>
                            <?php echo lang("products"); ?>
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="panel <?php echo "panel-primary"; ?>">
                    <div class="panel-body ">
                        <div class="widget-icon">
                            <i class="fa <?php echo "fa-group"; ?>"></i>
                        </div>
                        <div class="widget-details">
                            <h1><?php echo $suppliers; ?>
                            <?php echo lang("suppliers"); ?>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
         </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <i class="fa fa-list pt10"></i>&nbsp; <?php echo lang("items"); ?>
            </div>
            <div class="panel panel-default">
                <div class="table-responsive">
                    <table id="items-summary-table" class="table table-hoover" cellspacing="0" width="100%">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#items-summary-table").appTable({
        source: '<?php echo_uri("items_report/list_data"); ?>',
        hideTools: false,
        columnShowHideOption: true,
        displayLength: 20,
        order: [[1, "desc"]],
        columns: [
            {title: '<?php echo lang("item") ?>', "class": "w30p", "iDataSort": 0},
            {title: '<?php echo lang("total_quantity_sold") ?>', "class": "w20p text-center"},
            {title: '<?php echo lang("cost_value") ?>', "class": "w20p text-center"},
            {title: '<?php echo lang("sale_value") ?>', "class": "w20p text-center"},
            {title: '<?php echo lang("percentage_of_profit_to_sale") ?>', "class": "w20p text-center"},
        ],
        onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
        printColumns: [1,2,3,4],
        xlsColumns: [1,2,3,4], 
        summation: [{column:1 , dataType: 'number'}, {column:2 , dataType: 'currency'}, {column:3 , dataType: 'currency'}]
    });
        
    });
</script>
