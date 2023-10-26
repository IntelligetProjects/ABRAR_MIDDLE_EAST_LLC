<div id="page-content" class="clearfix p20">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1><?php echo lang('purchase_returns'); ?></h1> 
            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    <?php echo modal_anchor(get_uri("purchase_returns/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_purchase_return'), array("class" => "btn btn-default mb0", "title" => lang('add_purchase_return'))); ?>
                </div>
            </div>           
        </div>
        
        <div class="table-responsive">
            <table id="delivery-table" class="table table-hoover" cellspacing="0" width="100%">   
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#delivery-table").appTable({
            source: '<?php echo_uri("purchase_returns/list_data") ?>',
            order: [[0, "desc"]],
            //dateRangeType: "monthly",
            columns: [
                {title: "<?php echo lang("id") ?> "},
                {title: "<?php echo lang("purchase_return") ?> ", "class": "w15p"},
                {title: "<?php echo lang("purchase_order") ?> "},
                {title: "<?php echo lang("supplier") ?> "},
                {title: "<?php echo lang("purchase_return_date") ?>", "iDataSort": 2, "class": "w100"},
                {title: "<?php echo lang("total_value") ?> "},
                {title: "<?php echo lang("tax_value") ?> "},
                {title: "<?php echo lang("payment_method") ?> "},
                {title: "<?php echo lang("note") ?> "},
                {title: "<?php echo lang("status") ?> "},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
            printColumns: [0, 1,2],
            xlsColumns: [0, 1,2],
            summation: [{column: 5, dataType: 'number'}, {column: 6, dataType: 'number'}]
            
        });
    });
</script>