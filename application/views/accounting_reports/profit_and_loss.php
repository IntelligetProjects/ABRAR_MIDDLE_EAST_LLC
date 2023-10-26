<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('profit_and_loss'); ?></h1>
        </div>
        <div class="table-responsive">
            <table id="profit_and_loss-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#profit_and_loss-table").appTable({
            // source: '<?php echo_uri("accounting_reports/profit_and_loss_list_data") ?>',
            source: '<?php echo_uri("accounting_reports/profit_and_loss_get_data") ?>',
            displayLength: 100,
            stateSave: false,
            // order: [[0, 'desc']],
            
            rangeDatepicker: [{startDate: {name: "start_date", value: "<?= $start_date ?>"}, endDate: {name: "end_date", value: "<?= $end_date ?>"}, showClearButton: true}],
           
            columns: [
                {title: "<?php echo lang('no') ?> "},
                {title: "<?php echo lang('item') ?> "},
                {title: "<?php echo lang('amount_(OMR)') ?>",  },
            ],
            printColumns: [0, 1, 2],
            xlsColumns: [0, 1, 2],
            onInitComplete: function () {

                // $('.edit').each(function() {
                //     var level = $(this).attr('id');
                //     if(level == 1)
                //     {
                //         $(this).parent().parent().addClass("level3");

                //     }
                //     else if(level == 2)
                //     {
                //         $(this).parent().parent().addClass("level2");
                //     }

                // });
            },

            onRelaodCallback: function () {

                // $('.edit').each(function() {
                //     var level = $(this).attr('id');
                //     if(level == 1)
                //     {
                //         $(this).parent().parent().addClass("level3");

                //     }
                //     else if(level == 2)
                //     {
                //         $(this).parent().parent().addClass("level2");
                //     }

                // });
            }
        });



    });
</script>