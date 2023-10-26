<table id="history" class="display" cellspacing="0" width="100%">   
            </table>
<script type="text/javascript">
     loadExpensessVatReportTable = function (selector) {
    var customDatePicker = "";

    $(selector).appTable({
    source: '<?php echo_uri("vat_report/list_history") ?>',
            dateRangeType: 'yearly',
            order: [[0, "desc"]],
            // filterDropdown: [
            //     {name: "quarter", class: "w150", options: <?php $this->load->view("vat-report/year_quarter_dropdown"); ?>},
            // ],
            rangeDatepicker: customDatePicker,
            columns: [
            {title: "<?php echo lang("year") ?>", "class": "w10p", "iDataSort": 4},
            {title: "<?php echo lang("quarter") ?>", "class": ""},
            {title: "<?php echo lang("vat_amount") ?>", "class": ""},
            {title: "<?php echo lang("status") ?>", "class": ""},
            {title: "<?php echo lang("action") ?>", "class": "w15p"},
            
            ],
            printColumns: combineCustomFieldsColumns([0, 1, 2, 3, 5], ''),
            xlsColumns: combineCustomFieldsColumns([0, 1, 2, 3, 5], ''),
            // summation: [{column: 2, dataType: 'number'}],
            onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
    });
    };
    $(document).ready(function () {
        loadExpensessVatReportTable("#history");
    });
</script>