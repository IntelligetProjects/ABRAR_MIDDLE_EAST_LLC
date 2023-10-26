<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1><?php echo lang('suppliers'); ?></h1>
            <?php if ($can_create_module) { ?>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("suppliers/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_supplier'), array("class" => "btn btn-default", "title" => lang('add_supplier'))); ?>
            </div>
            <?php } ?>
        </div>
        <div class="table-responsive">
            <table id="supplier-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var showInfo = true;
        if (!"<?php echo $show_info; ?>") {
            showInfo = false;
        }
        $("#supplier-table").appTable({
            source: '<?php echo_uri("suppliers/list_data") ?>',
            stateSave: false,
            columns: [
                {title: "<?php echo lang("id") ?>", "class": "text-center w50"},
                {title: "<?php echo lang("company_name") ?>"},
                {title: "<?php echo lang("contact_name") ?>"},
                {title: "<?php echo lang("email") ?>"},
                {title: "<?php echo lang("phone") ?>"},
                {visible: showInfo, searchable: showInfo, title: "<?php echo lang("purchase_order_value") ?>"},
                {visible: showInfo, searchable: showInfo, title: "<?php echo lang("payment_released") ?>"},
                {visible: showInfo, searchable: showInfo, title: "<?php echo lang("due") ?>"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
            printColumns: [0, 1, 2, 3, 4, 5, 6],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6]
        });
    });
</script>