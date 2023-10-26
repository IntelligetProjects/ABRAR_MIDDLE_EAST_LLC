<div id="page-content" class="clearfix p20">
    <div class="panel clearfix">
        <ul id="material_request-tabs" data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang("material_request"); ?></h4></li>
            <li><a id="monthly-expenses-button"  role="presentation" class="active" href="javascript:;" data-target="#monthly-material_request"><?php echo lang("monthly"); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("material_request/yearly/"); ?>" data-target="#yearly-material_request"><?php echo lang('yearly'); ?></a></li>
            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    <?php if ($can_add_payment) { ?>
                    <!-- <?php echo modal_anchor(get_uri("purchase_order_payments/payment_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_payment'), array("class" => "btn btn-default mb0", "title" => lang('add_payment'))); ?> -->
                    <?php } ?>
                    <?php if ($can_create_module) { ?>
                    <?php echo modal_anchor(get_uri("material_request/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_material_request'), array("class" => "btn btn-default mb0", "title" => lang('add_material_request'))); ?>
                    <?php } ?>
                </div>
            </div>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="monthly-material_request">
                <div class="table-responsive">
                    <table id="monthly-purchase_order-table" class="display" cellspacing="0" width="100%">   
                    </table>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="yearly-material_request"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    loadInvoicesTable = function (selector, dateRange) {

    $(selector).appTable({
    source: '<?php echo_uri("material_request/list_data") ?>',
            dateRangeType: dateRange,
            order: [[0, "desc"]],
            filterDropdown: [
            {name: "status", class: "w150", options: <?php $this->load->view("material_request/purchase_order_statuses_dropdown"); ?>},
            <?php if ($currencies_dropdown) { ?>
                            {name: "currency", class: "w150", options: <?php echo $currencies_dropdown; ?>}
            <?php } ?>
            ],
            columns: [
            {title: "<?php echo lang("id") ?>", "class": "w10p"},
            // {title: "<?php echo lang("supplier") ?>", "class": ""},
            {visible: false, searchable: false},
            {title: "<?php echo lang("project") ?>", "class": "w15p"},
            {visible: false, searchable: false},
            {title: "<?php echo lang("date") ?>", "class": "w10p", "iDataSort": 3},
            {visible: false, searchable: false},
            {visible: false, searchable: false},
            {visible: false, searchable: false},
            // {title: "<?php echo lang("purchase_order_value") ?>", "class": "w10p text-right"},
            // {title: "<?php echo lang("payment_received") ?>", "class": "w10p text-right"},
            // {title: "<?php echo lang("tax_value") ?>", "class": "w10p text-right"},
            {title: "<?php echo lang("status") ?>", "class": "w10p text-center"},
            {title: '<i class="fa fa-bars"></i>', "class": "text-center dropdown-option w100"}
            ],
            printColumns: [0, 1, 2, 3, 5],
            xlsColumns: [0, 1, 2, 3, 5],
            summation: [{column: 5, dataType: 'number'}, {column: 6, dataType: 'number'}, {column: 7, dataType: 'number'}]
    });
    };
    $(document).ready(function () {
    loadInvoicesTable("#monthly-purchase_order-table", "monthly");
    });
</script>