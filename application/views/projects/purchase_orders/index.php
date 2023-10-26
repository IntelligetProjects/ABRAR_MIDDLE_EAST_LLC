<div class="panel">
    <div class="tab-title clearfix">
        <h4><?php echo lang('purchase_orders'); ?></h4>
        <div class="title-button-group">
            <?php echo modal_anchor(get_uri("purchase_orders/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_purchase_order'), array("class" => "btn btn-default mb0", "title" => lang('add_purchase_order'), "data-post-project_id" => $project_id)); ?>
        </div>
    </div>

    <div class="table-responsive">
        <table id="purchase_orders-table" class="display" width="100%">       
        </table>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var currencySymbol = "<?php echo $project_info->currency_symbol; ?>";
        $("#purchase_orders-table").appTable({
            source: '<?php echo_uri("purchase_orders/purchase_orders_list_data_of_project/". $project_id) ?>',
            order: [[0, "desc"]],
            filterDropdown: [
            {name: "status", class: "w150", options: <?php $this->load->view("purchase_orders/purchase_order_statuses_dropdown"); ?>},
            ],
            columns: [
            {title: "<?php echo lang("purchase_order_id") ?>", "class": "w10p"},
            {title: "<?php echo lang("supplier") ?>", "class": ""},
            {title: "<?php echo lang("project") ?>", "class": "w15p"},
            {visible: false, searchable: false},
            {title: "<?php echo lang("date") ?>", "class": "w10p", "iDataSort": 3},
            {title: "<?php echo lang("purchase_order_value") ?>", "class": "w10p text-right"},
            {title: "<?php echo lang("payment_released") ?>", "class": "w10p text-right"},
            {title: "<?php echo lang("tax_value") ?>", "class": "w10p text-right"},
            {title: "<?php echo lang("status") ?>", "class": "w10p text-center"},
            {title: '<i class="fa fa-bars"></i>', "class": "text-center dropdown-option w100"}
            ],
            onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
            printColumns: [0, 1, 2, 3, 5, 7],
            xlsColumns: [0, 1, 2, 3, 5, 7],
            summation: [{column: 5, dataType: 'number'}, {column: 6, dataType: 'number'}]
        });
    });
</script>