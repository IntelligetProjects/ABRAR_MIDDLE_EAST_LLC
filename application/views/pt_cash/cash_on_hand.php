<div id="page-content" class="">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang("petty_cash") ?></h1>
            
        </div>
        <div class="table-responsive">
            <table id="cash-table" class="table table-hoover" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#cash-table").appTable({
            source: '<?php echo_uri("PT_cash/list_cash_data") ?>',
            order: [[0, 'desc']],
            columns: [
                {title: "<?php echo lang("employee") ?>", "class": "w20p"},
                {title: "<?php echo lang("ptc") ?>"},
                {title: "<?php echo lang("total_recieved") ?>"},
                {title: "<?php echo lang("total_transfered") ?>"},
                {title: "<?php echo lang("total_expenses") ?>"}
            ],
            onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
            summation: [{column: 1, dataType: 'currency', currencySymbol: " OMR"}, {column: 2, dataType: 'currency', currencySymbol: " OMR"}, {column: 3, dataType: 'currency', currencySymbol: " OMR"}],
            printColumns: [0, 1, 2, 3, 4],
            xlsColumns: [0, 1, 2, 3, 4]
        });
    });
</script>