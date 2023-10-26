<table id="purchase-import-table" class="display" cellspacing="0" width="100%">   
            </table>
<script type="text/javascript">
     loadPurchaseImportTable = function (selector) {
    var customDatePicker = "";

    $(selector).appTable({
    source: '<?php echo_uri("vat_report/list_data_purchase_import") ?>',
            dateRangeType: 'yearly',
            order: [[0, "desc"]],
            filterDropdown: [
                {name: "quarter", class: "w150", options: <?php $this->load->view("vat-report/year_quarter_dropdown"); ?>},
            ],
            rangeDatepicker: customDatePicker,
            columns: [
            {title: "<?php echo lang("id") ?>", "class": ""},
            {title: "<?php echo lang("date_of_invoice") ?>","class": "w10p", "iDataSort": 4},
            {title: "<?php echo lang("supplier_vat_number") ?>", "class": "w10p"},
            {title: "<?php echo lang("invoice_number") ?>", "class": ""},
            {title: "<?php echo lang("name_of_supplier") ?>", "class": "w15p"},
            // {visible: false, searchable: false},
            {title: "<?php echo lang("invoice_currency") ?>", "class": "w15p"},
            // {visible: false, searchable: false},
            {title: "<?php echo lang("payment_date") ?>", "class": ""},
            // {title: "<?php echo lang("texable_value_in_fc") ?>", "class": "w10p text-right"},
            // {title: "<?php echo lang("exchange_rate") ?>", "class": "w10p text-right"},
            {title: "<?php echo lang("texable_value_in_OMR") ?>", "class": "w10p text-right"},
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
        loadPurchaseImportTable("#purchase-import-table");
    });
</script>