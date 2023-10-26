<div id="page-content" class="clearfix p20">
    <div class="panel clearfix">
        <ul style="display: inline-block;" id="invoices-tabs" data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang("invoices"); ?></h4></li>
            <li><a id="output-vat-button"  role="presentation" class="active" href="javascript:;" data-target="#output-vat"><?php echo lang("output_VAT"); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("vat_report/purchase_import/"); ?>" data-target="#purchase-import"><?php echo lang('purchase_import'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("vat_report/purchase_domestic/"); ?>" data-target="#purchase-domestic"><?php echo lang('purchase_domestic'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("vat_report/expensess_vat_report/"); ?>" data-target="#expensess-vat_report"><?php echo lang('expensess'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("vat_report/vat_report/"); ?>" data-target="#vat-report"><?php echo lang('VAT_summery'); ?></a></li>                  
        </ul>
        <a style="display: inline-block;margin-top: -55px; height: 40px;" class="btn btn-default" href="<?php echo_uri("vat_report/history/"); ?>"><?php echo lang('history'); ?></a>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="output-vat">
                <div class="table-responsive">
                    <table id="output-vat-table" class="display" cellspacing="0" width="100%">   
                    </table>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="purchase-import"></div>
            <div role="tabpanel" class="tab-pane fade" id="purchase-domestic"></div>
            <div role="tabpanel" class="tab-pane fade" id="expensess-vat_report"></div>
            <div role="tabpanel" class="tab-pane fade" id="vat-report"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    loadInvoicesTable = function (selector, dateRange) {
    var customDatePicker = "";
    if (dateRange === "custom") {
    customDatePicker = [{startDate: {name: "start_date", value: moment().format("YYYY-MM-DD")}, endDate: {name: "end_date", value: moment().format("YYYY-MM-DD")}, showClearButton: true}];
    dateRange = "";
    }

    $(selector).appTable({
    source: '<?php echo_uri("vat_report/list_data_output_vat") ?>',
            dateRangeType: dateRange,
            order: [[0, "desc"]],
            // filterDropdown: [
            //     {name: "quarter", class: "w150", options: <?php $this->load->view("vat-report/year_quarter_dropdown"); ?>},
            //             ],
            rangeDatepicker: customDatePicker,
            columns: [
            {title: "<?php echo lang("id") ?>", "class": ""},
            {title: "<?php echo lang("invoice_id") ?>", "class": "w10p"},
            {title: "<?php echo lang("client_name") ?>", "class": ""},
            {title: "<?php echo lang("client_vat_number") ?>", "class": "w15p"},
            {visible: false, searchable: false},
            {title: "<?php echo lang("invoice_date") ?>", "class": "w10p", "iDataSort": 4},
            {visible: false, searchable: false},
            {title: "<?php echo lang("payment_date") ?>", "class": "w10p", "iDataSort": 6},
            {title: "<?php echo lang("invoice_amount") ?>", "class": "w10p text-right"},
            {title: "<?php echo lang("vat_rate") ?>", "class": "w10p text-right"},
            {title: "<?php echo lang("vat_value") ?>", "class": "w10p text-right"},
            ],
            printColumns: combineCustomFieldsColumns([0, 1, 2, 3, 5, 7, 8, 9], ''),
            xlsColumns: combineCustomFieldsColumns([0, 1, 2, 3, 5, 7, 8, 9], ''),
            summation: [{column: 8, dataType: 'number'},/* {column: 9, dataType: 'number'},*/ {column: 10, dataType: 'number'}],
            onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
    });
    };
    $(document).ready(function () {
    loadInvoicesTable("#output-vat-table", "yearly");
    });
</script>