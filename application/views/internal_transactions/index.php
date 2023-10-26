<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('internal_transactions'); ?></h1>
            <div class="title-button-group">

                <?php echo modal_anchor(get_uri("internal_transactions/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_transaction'), array("class" => "btn btn-default", "title" => lang('add_transaction'))); ?>
            </div>
        </div>
            <div class="table-responsive">
                <table id="internal_transactions-table" class="table table-hoover" cellspacing="0" width="100%">            
                </table>
            </div>
    </div>
</div>

<?php 
$status = array(array("id"=>"draft", "text"=>lang("draft")));
if ($can_approve) {
   $status[] = array("id"=>"approved", "text"=>lang("approved")); 
}
?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#internal_transactions-table").appTable({
            source: '<?php echo_uri("internal_transactions/list_data") ?>',
            columns: [
                {title: '<?php echo lang("date") ?>'},
                {title: '<?php echo lang("from") ?>'},
                {title: '<?php echo lang("to") ?>'},
                {title: '<?php echo lang("amount") ?>'},
                {title: '<?php echo lang("note") ?>'},
                {title: '<?php echo lang("cash_on_hand") ?>'},
                {title: '<?php echo lang("status") ?>', "class": 'stat'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
            printColumns: [0, 1, 2, 3, 4, 5],
            xlsColumns: [0, 1, 2, 3, 4, 5]
        });

        $("#internal_transactions-table").on('click', '[data-act=update-status]', function () {
                    $(this).editable({
                        type: "select2",
                        pk: 1,
                        name: 'status',
                        ajaxOptions: {
                            type: 'post',
                            dataType: 'json'
                        },
                        value: $(this).attr('data-value'),
                        url: '<?php echo_uri("internal_transactions/update_status") ?>/0' + '/' + $(this).attr('data-id'),
                        showbuttons: false,
                        source: <?php echo json_encode($status) ?>,
                        success: function (response, newValue) {
                            if (response.success) {
                                $("#internal_transactions-table").DataTable().ajax.reload();
                            }
                        }
                    });
                    $(this).editable("show");
                });


    });
</script>