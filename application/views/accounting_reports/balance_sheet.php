<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('balance_sheet'); ?></h1>
        </div>
        <div class="table-responsive">
            <table id="balance_sheet-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#balance_sheet-table").appTable({
            source: '<?php echo_uri("accounting_reports/balance_sheet_list_data") ?>',
            displayLength: 100,
            stateSave: false,
            order: [[0, 'desc']],
            
            rangeDatepicker: [{startDate: {name: "start_date", value: "<?= $start_date ?>"}, endDate: {name: "end_date", value: "<?= $end_date ?>"}, showClearButton: true}],
            checkBoxes: [
                // {text: '<?php echo lang("details") ?>', name: "status", value: "details", isChecked: true},
                // {text: '<?php echo lang("zero_accounts") ?>', name: "status", value: "zero", isChecked: true},
                // {text: '<?php echo lang("inactive") ?>', name: "status", value: "inactive", isChecked: true},
            ],
            columns: [
                {title: "", visible: false},
                {title: "<?php echo lang('number') ?> ", "class" : "text-center"},
                {title: "<?php echo lang('account') ?> "},
                {title: "<?php echo lang('balance') ?>", "class" : "text-center"},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w50"}
            ],
            printColumns: [0, 1, 2, 3],
            xlsColumns: [0, 1, 2, 3],
            onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },

            onRelaodCallback: function () {

                $('.edit').each(function() {
                    // var level = $(this).attr('id');
                    // if(level == 1)
                    // {
                    //     $(this).parent().parent().addClass("level3");

                    // }
                    // else if(level == 2)
                    // {
                    //     $(this).parent().parent().addClass("level2");
                    // }

                });
            }
        });



    });
</script>