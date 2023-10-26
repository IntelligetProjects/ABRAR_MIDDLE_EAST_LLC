<div id="page-content" class="clearfix p20">
    <div class="panel clearfix">
        <ul id="expenses-tabs" data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang("expenses"); ?></h4></li>
            <li><a id="monthly-expenses-button"  role="presentation" class="active" href="javascript:;" data-target="#monthly-expenses"><?php echo lang("yearly"); ?></a></li>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="monthly-expenses">
                <div class="table-responsive">
                    <table id="monthly-expense-table" class="display" cellspacing="0" width="100%">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
$status = array(array("id"=>"draft", "text"=>lang("draft")), array("id"=>"approved", "text"=>lang("approved")));
$cheque_status = array(array("id"=>"1", "text"=>lang("cleared")), array("id"=>"0", "text"=>lang("not_cleared")));
?>

<script type="text/javascript">
    loadExpensesTable = function (selector, dateRange) {
        var customDatePicker = "";
        if (dateRange === "custom") {
            customDatePicker = [{startDate: {name: "start_date", value: moment().format("YYYY-MM-DD")}, endDate: {name: "end_date", value: moment().format("YYYY-MM-DD")}, showClearButton: true}];
            dateRange = "";
        }

        $(selector).appTable({
            source: '<?php echo_uri("cheques/list_data") ?>',
            dateRangeType: dateRange,
            order: [[0, "asc"]],
            stateSave: false,
            rangeDatepicker: customDatePicker,
            columns: [
                {visible: false, searchable: false},
                {title: '<?php echo lang("date") ?>', "iDataSort": 0},
                {title: '<?php echo lang("category") ?>'},
                {visible: false, searchable: false},
                {title: '<?php echo lang("description") ?>'},
                {title: '<?php echo lang("files") ?>'},
                {title: '<?php echo lang("amount") ?>', "class": "text-right"},
                {title: '<?php echo lang("payment_mode") ?>'},
                {title: '<?php echo lang("status") ?>',"class": "text-center"},
                
                {visible: false, searchable: false},
                {visible: false, searchable: false},
                {visible: false, searchable: false},
                {title: '<?php echo lang("cheque_status") ?>',"class": "text-center"},
            ],
            printColumns: [1, 2, 3, 4, 6, 7],
            xlsColumns: [1, 2, 3, 4, 6, 7],
            summation: [{column: 6, dataType: 'currency'}]
        });

        $(selector).on('click', '[data-act=update-status]', function () {
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
                                $(selector).DataTable().ajax.reload();
                            }
                        }
                    });
                    $(this).editable("show");
                });

        $(selector).on('click', '[data-act=update-cheque_status]', function () {
                    $(this).editable({
                        type: "select2",
                        pk: 1,
                        name: 'cheque',
                        ajaxOptions: {
                            type: 'post',
                            dataType: 'json'
                        },
                        value: $(this).attr('data-value'),
                        url: '<?php echo_uri("cheques/expense_transaction") ?>' + '/' + $(this).attr('data-id'),
                        showbuttons: false,
                        source: <?php echo json_encode($cheque_status) ?>,
                        success: function (response, newValue) {
                            if (response.success) {
                                $(selector).DataTable().ajax.reload();
                            }
                        }
                    });
                    $(this).editable("show");
                });
    };

    $(document).ready(function () {
        loadExpensesTable("#monthly-expense-table", "yearly");
    });
</script>
