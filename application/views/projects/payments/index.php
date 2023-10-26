<div class="panel">
    <div class="tab-title clearfix">
        <h4><?php echo lang('payments'); ?></h4>
    </div>

    <div class="table-responsive">
        <table id="invoice-payment-table" class="display" width="100%">
        </table>
    </div>
</div>

<?php 
$status = array(array("id"=>"draft", "text"=>lang("draft")));
if($can_approve) {
    $status[] = array("id"=>"approved", "text"=>lang("approved"));
}
?>

<script type="text/javascript">
    $(document).ready(function() {
        var currencySymbol = "<?php echo $project_info->currency_symbol; ?>";
        $("#invoice-payment-table").appTable({
            source: '<?php echo_uri("invoice_payments/payment_list_data_of_project/" . $project_id) ?>',
            order: [[0, "asc"]],
            columns: [
                {title: '<?php echo lang("invoice_id") ?> ', "class": "w10p"},
                {visible: false, searchable: false},
                {visible: false, searchable: false},
                {title: '<?php echo lang("payment_date") ?> ', "class": "w15p",  "iDataSort": 1},
                {title: '<?php echo lang("payment_method") ?>', "class": "w15p"},
                {title: '<?php echo lang("note") ?>'},
                {title: '<?php echo lang("status") ?>'},
                {title: '<?php echo lang("amount") ?>', "class": "text-right w15p"},
                {visible: false, searchable: false},
                {visible: false, searchable: false},
            ],
            onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
            printColumns: [0, 1, 2, 3, 4],
            xlsColumns: [0, 1, 2, 3, 4],
            summation: [{column: 7, dataType: 'currency', currencySymbol: currencySymbol}]
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
                url: '<?php echo_uri("invoice_payments/update_status") ?>/0' + '/' + $(this).attr('data-id'),
                showbuttons: false,
                source: <?php echo json_encode($status) ?>,
                success: function (response, newValue) {
                    if (response.success) {
                        $("#invoice-payment-table").DataTable().ajax.reload();
                    }
                }
            });
            $(this).editable("show");
        });

    });
</script>