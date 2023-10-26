<div class="panel panel-default">
    <div class="tab-title clearfix">
        <h4> <?php echo "Expenses list" ?></h4>
        <?php if ($can_create_module) { ?>
                <div class="title-button-group">
                    <?php echo modal_anchor(get_uri("expenses/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_expense'), array("class" => "btn btn-default mb0", "title" => lang('add_expense'), "data-post-invoice" => $invoice_info->id)); ?>
                </div>
            <?php } ?>
    </div>
    <div class="table-responsive">
        <table id="invoice-expense-table" class="display" cellspacing="0" width="100%">            
        </table>
    </div>
</div>

<?php 
$status = array(array("id"=>"draft", "text"=>lang("draft")));
if($can_approve_expense) {
    $status[] = array("id"=>"approved", "text"=>lang("approved"));
    $status[] = array("id"=>"rejected", "text"=>lang("rejected"));
}

?>

<script>
	$(document).ready(function () {

		$("#invoice-expense-table").appTable({
            source: '<?php echo_uri("expenses/invoice_expense_list_data/" . $invoice_info->id . "/") ?>',
            order: [[0, "asc"]],
            columns: [
                {visible: false, searchable: false},
                {visible: false, searchable: false},
                {title: '<?php echo lang("date") ?>', "iDataSort": 0},
                {title: '<?php echo lang("category") ?>'},
                {title: '<?php echo lang("description") ?>'},
                {title: '<?php echo lang("files") ?>'},
                {visible: false, searchable: false},
                {title: '<?php echo lang("amount") ?>', "class": "text-right", "iDataSort": 6},
                {title: '<?php echo lang("payment_mode") ?>'},
                {title: '<?php echo lang("status") ?>',"class": "text-center"}
                /*{visible: false, searchable: false},*/
                //{visible: false, searchable: false},
                //{visible: false, searchable: false}
                <?php echo $expense_custom_field_headers; ?>,
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            summation: [{column: 7, dataType: 'currency'}]
        });


         $('#invoice-expense-table').on('click', '[data-act=update-status]', function () {
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
                        $("#invoice-expense-table").DataTable().ajax.reload();
                        //$("#invoice-expense-table").appTable({newData: response.data, dataId: response.id});
                    }
                }
            });
            $(this).editable("show");
        });
    

	});
</script>