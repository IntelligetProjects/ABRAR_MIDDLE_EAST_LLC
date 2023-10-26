<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1><?php echo lang('phonebook'); ?></h1>
            <?php if ($can_create_module) { ?>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("contacts/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_contact'), array("class" => "btn btn-default", "title" => lang('add_contact'))); ?>
            </div>
            <?php } ?>
        </div>
        <div class="table-responsive">
            <table id="contact-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        

        $("#contact-table").appTable({
            source: '<?php echo_uri("contacts/list_data") ?>',
            columns: [
                {title: "<?php echo lang("name") ?>"},
                {title: "<?php echo lang("job_title") ?>"},
                {title: "<?php echo lang("email") ?>"},
                {title: "<?php echo lang("phone") ?>"},
                {title: "<?php echo lang("alternative_phone") ?>"},
                {title: "<?php echo lang("address") ?>"},
                {title: "<?php echo lang("note") ?>", "class": "w150"},
                {title: "<?php echo lang("created_at") ?>"},
                {title: "<?php echo lang("created_by") ?>"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5, 6],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6],
            onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
        });
    });
</script>