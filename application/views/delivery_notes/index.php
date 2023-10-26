<div id="page-content" class="clearfix p20">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1><?php echo lang('delivery_notes'); ?></h1>            
        </div>
        <div class="table-responsive">
            <table id="delivery-table" class="table table-hoover" cellspacing="0" width="100%">   
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#delivery-table").appTable({
            source: '<?php echo_uri("delivery_notes/list_data") ?>',
            order: [[0, "desc"]],
            //dateRangeType: "monthly",
            columns: [
                {title: "<?php echo lang("id") ?> ", "class": "w15p"},
                {title: "<?php echo lang("invoice") ?> "},
                {title: "<?php echo lang("delivery_date") ?>", "iDataSort": 2, "class": "w100"},
                {title: "<?php echo lang("client") ?> "},
                {title: "<?php echo lang("project") ?> "},
                {title: "<?php echo lang("status") ?> "},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1,2],
            xlsColumns: [0, 1,2]
            
        });
    });
</script>