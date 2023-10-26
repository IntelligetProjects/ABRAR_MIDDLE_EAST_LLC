<table id="expensess-vat-report-table" class="display" cellspacing="0" width="100%">   
            </table>
<script type="text/javascript">
     loadExpensessVatReportTable = function (selector) {
    var customDatePicker = "";

    $(selector).appTable({
    source: '<?php echo_uri("vat_report/list_data_expensess_vat_report") ?>',
            dateRangeType: 'yearly',
            order: [[0, "desc"]],
            filterDropdown: [
                {name: "quarter", class: "w150", options: <?php $this->load->view("vat-report/year_quarter_dropdown"); ?>},
            ],
            rangeDatepicker: customDatePicker,
            columns: [
            {title: "<?php echo lang("date_of_expense") ?>", "class": "w10p", "iDataSort": 4},
            {title: "<?php echo lang("service provider_vat_number") ?>", "class": ""},
            {title: "<?php echo lang("invoice_refrence_number") ?>", "class": ""},
            {title: "<?php echo lang("name_of_service_provider") ?>", "class": "w10p"},
            {title: "<?php echo lang("expense_currency") ?>", "class": ""},
            {title: "<?php echo lang("domestic_/_import") ?>", "class": "w15p"},
            // {visible: false, searchable: false},
            {title: "<?php echo lang("direct_/_indirect_/_assets") ?>", "class": ""},
            // {visible: false, searchable: false},
            {title: "<?php echo lang("texable_value_in_OMR") ?>", "class": ""},
            {title: "<?php echo lang("vat_rate") ?>", "class": "w10p text-right"},
            {title: "<?php echo lang("auto_input_vat_OMR") ?>", "class": "w10p text-right"},
            ],
            printColumns: combineCustomFieldsColumns([0, 1, 2, 3, 5, 7, 8, 9], ''),
            xlsColumns: combineCustomFieldsColumns([0, 1, 2, 3, 5, 7, 8, 9], ''),
            summation: [{column: 7, dataType: 'number'}, {column: 9, dataType: 'number'}],
            onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
    });
    };
    $(document).ready(function () {
        loadExpensessVatReportTable("#expensess-vat-report-table");
    });
</script>