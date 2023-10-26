<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('logs'); ?></h1>
            
        </div>
        <div class="table-responsive">
            <table id="<?php echo $module ?>-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    LoadLogs = function (tab) {
        $("#"+tab).appTable({
            source: '<?php echo_uri("log/list_data/". $module) ?>',
            order: [[0, 'desc']],
            rangeDatepicker: [{startDate: {name: "start_date", value: moment().format("YYYY-MM-DD")}, endDate: {name: "end_date", value: moment().format("YYYY-MM-DD")}, showClearButton: true}],
            columns: [
                {title: "<?php echo lang('actions'); ?>"},
                {title: "<?php echo lang('field'); ?>"},
                {title: "<?php echo lang('details'); ?>"},
                {title: "<?php echo lang('created_by'); ?>"},
                {title: "<?php echo lang('created_at'); ?>"}
            ],
            onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
            printColumns: [0],
            xlsColumns: [0]
        });
    }


    $(document).ready(function () {
        LoadLogs('<?php echo $module ?>'+'-table');
    });
</script>