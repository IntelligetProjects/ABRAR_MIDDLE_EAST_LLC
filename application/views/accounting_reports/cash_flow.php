<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('cash_flow'); ?></h1>
        </div>
        <div class="table-responsive">
            <table id="cash_flow-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#cash_flow-table").appTable({
            source: '<?php echo_uri("accounting_reports/cash_flow_list_data") ?>',
            displayLength: 100,
            stateSave: false,
            order: [[0, 'desc']],
            
            rangeDatepicker: [{startDate: {name: "start_date", value: "<?= $start_date ?>"}, endDate: {name: "end_date", value: "<?= $end_date ?>"}, showClearButton: true}],
            columns: [
                {title: "", visible: false},
                {title: "<?php echo lang('account') ?> "},
                {title: "<?php echo lang('balance') ?>", "class" : "text-center"},
                //{title: "<i class='fa fa-bars'></i>", "class": "text-center option w50"}
            ],
            printColumns: [0, 1, 2, 3],
            xlsColumns: [0, 1, 2, 3],
            onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
        });



    });
</script>