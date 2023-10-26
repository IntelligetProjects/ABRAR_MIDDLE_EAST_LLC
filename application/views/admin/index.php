<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1><?php echo lang('expires'); ?></h1>
            <div class="title-button-group">
            <?php                
                echo modal_anchor(get_uri("expires/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_item'), array("class" => "btn btn-default", "title" => lang('add_item')));
            	
        	?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="expires-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
       
        $("#expires-table").appTable({
            source: '<?php echo_uri("expires/list_data") ?>',

            singleDatepicker: [
                {name: "expiry", defaultText: "<?php echo "expiry" ?>",
                 options: [
                    {value: "expired", text: "expired"},
                    {value: moment().add(30, 'days').format("YYYY-MM-DD"), text: "<?php echo sprintf("Within Month", 30); ?>"}
                ]}
            ],

            filterDropdown: [
                {name: "type", class: "w150", options: <?php echo json_encode($type_dropdown); ?>}],
            
            columns: [
                {title: "<?php echo lang("id") ?>", "class": "text-center w50"},
                {title: "<?php echo lang("item") ?>"},
                {title: "<?php echo lang("type") ?>"},
                {title: "<?php echo lang("department") ?>"},
                {title: "<?php echo lang("expiry") ?>", "class": "w150"},
                {title: "<?php echo lang("recu_charges") ?>"},
                {title: "<?php echo lang("responsible") ?>"},
                 {title: "<?php echo lang("client") ?>"},
                  {title: "<?php echo "Dashboard" ?>"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns:[0, 1, 2, 3, 4, 5, 6, 7],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6, 7]
        });


        $('body').on('click', '[data-act=update-status]', function () {
            $(this).editable({
                type: "select2",
                pk: 1,
                name: 'status',
                ajaxOptions: {
                    type: 'post',
                    dataType: 'json'
                },
                value: $(this).attr('data-value'),
                url: '<?php echo_uri("expires/update_status") ?>/0' + '/' + $(this).attr('data-id'),
                showbuttons: false,
                source: <?php echo json_encode($status) ?>,
                success: function (response, newValue) {
                    if (response.success) {
                        $("#expires-table").DataTable().ajax.reload();
                    }
                }
            });
            $(this).editable("show");
        });
    
    });
</script>