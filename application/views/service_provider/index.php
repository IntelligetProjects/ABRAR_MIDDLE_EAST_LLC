<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1><?php echo lang('service_provider'); ?></h1>
            <div class="title-button-group">
                <!-- //TODO : check permissions -->
            <?php if ($can_create_module) { ?>
                <?php echo modal_anchor(get_uri("service_provider/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_service_provider'), array("class" => "btn btn-default", "title" => lang('add_service_provider'))); ?>
            <?php }?>
            </div>
        </div>
        <div  class="table-responsive">
            <table  id="service-provider-table" class="table-scrollable display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#service-provider-table").appTable({
            source: '<?php echo_uri("service_provider/list_data") ?>',
            columns: [
                {title: "<?php echo lang("id") ?>", "class": "text-center w10p"},
                {title: "<?php echo lang("created_at") ?>",  "class": "w10p", "iDataSort": 4},
                {title: "<?php echo lang("name") ?>", "class": "w10p"},
                {title: "<?php echo lang("phone") ?>", "class": "w10p"},
                {title: "<?php echo lang("email") ?>", "class": "w10p"},
                {title: "<?php echo lang("vat_number") ?>", "class": "w10p"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w10p"}
            ],
            onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
            printColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4, 5, 6], ''),
            xlsColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4, 5, 6], '')
        });
    });
</script>