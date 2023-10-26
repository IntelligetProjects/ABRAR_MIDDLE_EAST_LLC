<div id="page-content" class="p20 clearfix">
    <div class="panel clearfix">
        <div class="page-title clearfix">
            <h1> <?php echo lang("general_journal_entries"); ?></h1>
            <?php   if($this->db->dbprefix=='Integrated_Banners_'){ if($this->login_user->role_id==1 || $this->login_user->is_admin){ ?>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("transactions/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang("add_transaction"), array("class" => "btn btn-default", "title" => lang("add_transaction"))); ?>
            </div>
            <?php } }else{ ?>
                <div class="title-button-group">
                <?php echo modal_anchor(get_uri("transactions/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang("add_transaction"), array("class" => "btn btn-default", "title" => lang("add_transaction"))); ?>
            </div>
            <?php } ?>
        </div>

        <div class="table-responsive">
            <table id="transactions-table" class="display table table-hoover" cellspacing="0" width="100%">
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function () {
    $("#transactions-table").appTable({
        source: '<?php echo_uri("transactions/list_data") ?>',
        order: [[0, 'desc']],
        displayLength: 100,
        tableRefreshButton: true,
        filterParams: {pending: "<?php echo $pending ?>"},
        filterDropdown: [
                {name: "manual_auto", class: "w200", options: <?php echo json_encode($source); ?>},
            ],
        /*rangeDatepicker: [{startDate: {name: "start_date", value: "<?= $start_date ?>"}, endDate: {name: "end_date", value: "<?= $end_date ?>"}, showClearButton: true}],*/
        rangeDatepicker: [{startDate: {name: "start_date", value: moment().format("YYYY-MM-DD")}, endDate: {name: "end_date", value: moment().format("YYYY-MM-DD")}, showClearButton: true}],
        columns: [
            {title: "<?php echo lang("id") ?>"},
            {visible: false, searchable: false},
            {title: '<?php echo lang("date"); ?>', "class": "w100", "iDataSort": 1},
            {title: '<?php echo lang("type"); ?>'},            
            {title: "<?php echo lang("status") ?>", "class": "text-center"},
            {title: "<?php echo lang("DR_amount") ?>", "class": "text-center"},
            <?php  if (strcasecmp($this->db->dbprefix, 'Integrated_Banners_') == 0) {
                 echo '{title: "'. lang("project") .'"},';} ?>       
            <?php  if (strcasecmp($this->db->dbprefix, 'Integrated_Banners_') == 0) {
                 echo '{title: "'. lang("approval_status") .'"},';} ?>       
            {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"},
        ],
        onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
            printColumns: [0, 1, 2, 3, 4, 5, 6],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6]
    });

    $("#transactions-table").on('click', '[data-act=update-status]', function () {
        var instanceSettings = window.InstanceCollection["#transactions-table"];
        filter = instanceSettings.filterParams;
                    // $(this).editable({
                    //     type: "select2",
                    //     pk: 1,
                    //     name: 'status',
                    //     ajaxOptions: {
                    //         type: 'post',
                    //         dataType: 'json'
                    //     },
                    //     value: $(this).attr('data-value'),
                    //     url: '<?php echo_uri("transactions/update_status") ?>/0' + '/' + $(this).attr('data-id'),
                    //     showbuttons: false,
                    //     source: <?php echo json_encode($status) ?>,
                    //     success: function (response, newValue) {
                    //         if (response.success) {
                    //             // $("#transactions-table").appTable({reload: true, filterParams: filter});
                    //             window.location = '<?php echo site_url("transactions/view")."/"?>'+ response.id;
                    //         }
                    //     }
                    // });
                    // $(this).editable("show");
            if ($(this).attr('data-value') != 1) return;
            var $button = $("#actionConfirmButton");
            element = this;
            //bind click event
            $button.unbind("click");
            $button.on("click", function() {
                // send request
                $.post('<?php echo_uri("pending_transactions/approve_transaction") ?>/' + $(element).attr('data-id') , {},
                    function(data, status) {
                        response = JSON.parse(data);
                        if (status == "success" && response.success) {
                            window.location.reload();
                        }
                    });
            });

            $("#actionConfirmationModal").modal('show');
        });
});
</script>