<div class="panel">
    <div class="tab-title clearfix">
        <h4><?php echo lang('expenses'); ?></h4>
        <?php if ($can_create_module) { ?>
        <div class="title-button-group">
            <?php echo modal_anchor(get_uri("expenses/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_expense'), array("class" => "btn btn-default mb0", "title" => lang('add_expense'), "data-post-user_id" => $user_id)); ?>
        </div>
        <?php } ?>
    </div>
    <div class="table-responsive">
        <table id="expense-table" class="display" cellspacing="0" width="100%">
        </table>
    </div>
</div>

<?php 
$status = array(array("id"=>"draft", "text"=>lang("draft")));
if($can_approve) {
    $status[] = array("id"=>"approved", "text"=>lang("approved"));
    $status[] = array("id"=>"rejected", "text"=>lang("rejected"));
}

?>

<script type="text/javascript">
    $(document).ready(function () {
        $EXPENSE_TABLE = $("#expense-table");

        $EXPENSE_TABLE.appTable({
            source: '<?php echo_uri("expenses/list_data/") ?>',
            filterParams: {user_id: "<?php echo $user_id; ?>"},
            order: [[0, "asc"]],
            columns: [
                {visible: false, searchable: false},
                {title: '<?php echo lang("date") ?>', "iDataSort": 0},
                {title: '<?php echo lang("category") ?>'},
                {visible: false, searchable: false},
                {title: '<?php echo lang("description") ?>'},
                {title: '<?php echo lang("file") ?>'},
                {title: '<?php echo lang("amount") ?>', "class": "text-right"},
                {visible: false, searchable: false},
                {title: '<?php echo lang("status") ?>',"class": "text-center"},
                {visible: false, searchable: false},
                {visible: false, searchable: false},
                {visible: false, searchable: false},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
            printColumns: [1, 2, 3, 4, 6, 7, 8, 9],
            xlsColumns: [1, 2, 3, 4, 6, 7, 8, 9],
            summation: [{column: 6, dataType: 'currency'}, {column: 9, dataType: 'currency'}]
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
                        url: '<?php echo_uri("expenses/update_status") ?>/0' + '/' + $(this).attr('data-id'),
                        showbuttons: false,
                        source: <?php echo json_encode($status) ?>,
                        success: function (response, newValue) {
                            if (response.success) {
                                $($EXPENSE_TABLE).DataTable().ajax.reload();
                            }
                        }
                    });
                    $(this).editable("show");
                });
    });
</script>