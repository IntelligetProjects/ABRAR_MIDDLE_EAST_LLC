<div id="page-content" class="clearfix p20">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1><?php echo lang('shipments'); ?></h1>            
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
            source: '<?php echo_uri("shipments/list_data") ?>',
            order: [[0, "desc"]],
            //dateRangeType: "monthly",
            columns: [
                {title: "<?php echo lang("id") ?> "},
                {title: "<?php echo lang("shipment") ?> ", "class": "w15p"},
                {title: "<?php echo lang("purchase_order") ?> "},
                {title: "<?php echo lang("supplier") ?> "},
                {title: "<?php echo lang("shipment_date") ?>", "iDataSort": 2, "class": "w100"},
                {title: "<?php echo lang("invoice_number") ?> "},
                {title: "<?php echo lang("invoice_date") ?> "},
                {title: "<?php echo lang("delivery_note_number") ?> "},
                {title: "<?php echo lang("delivery_note_date") ?> "},
                {title: "<?php echo lang("status") ?> "},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
            printColumns: [0, 1,2],
            xlsColumns: [0, 1,2]
            
        });
    });
</script>