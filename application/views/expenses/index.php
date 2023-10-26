<div id="page-content" class="clearfix p20">
    <div class="panel clearfix">
        <ul id="expenses-tabs" data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang("expenses"); ?></h4></li>
            <li><a id="monthly-expenses-button"  role="presentation" class="active" href="javascript:;" data-target="#monthly-expenses"><?php echo lang("monthly"); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("expenses/yearly/"); ?>" data-target="#yearly-expenses"><?php echo lang('yearly'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("expenses/custom/"); ?>" data-target="#custom-expenses"><?php echo lang('custom'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("expenses/yearly_chart/"); ?>" data-target="#yearly-chart"><?php echo lang('chart'); ?></a></li>
            <div class="tab-title clearfix no-border">
            <?php if ($can_create_module) { ?>
                <div class="title-button-group">
                    <?php echo modal_anchor(get_uri("expenses/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_expense'), array("class" => "btn btn-default mb0", "title" => lang('add_expense'))); ?>
                </div>
            <?php } ?>
            </div>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="monthly-expenses">
                <div class="table-responsive">
                    <table id="monthly-expense-table" class="display" cellspacing="0" width="100%">
                    </table>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="yearly-expenses"></div>
            <div role="tabpanel" class="tab-pane fade" id="custom-expenses"></div>
            <div role="tabpanel" class="tab-pane fade" id="yearly-chart"></div>
        </div>
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
    loadExpensesTable = function (selector, dateRange) {
        var customDatePicker = "";
        if (dateRange === "custom") {
            customDatePicker = [{startDate: {name: "start_date", value: moment().format("YYYY-MM-DD")}, endDate: {name: "end_date", value: moment().format("YYYY-MM-DD")}, showClearButton: true}];
            dateRange = "";
        }

        $(selector).appTable({
            source: '<?php echo_uri("expenses/list_data") ?>',
            dateRangeType: dateRange,
            filterDropdown: [
                {name: "category_id", class: "w200", options: <?php echo $categories_dropdown; ?>},
                {name: "user_id", class: "w200", options: <?php echo $members_dropdown; ?>},
                {name: "client_id", class: "w200", options: <?php echo $clients_dropdown; ?>},
                {name: "project_id", class: "w200", options: <?php echo $projects_dropdown; ?>},
                {name: "payment_mode", class: "w200", options: <?php echo $modes_dropdown; ?>}
            ],
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
                {title: '<?php echo lang("VAT_amount") ?>', "class": "text-right"},
                {title: '<?php echo lang("payment_mode") ?>'},
                {title: '<?php echo lang("status") ?>',"class": "text-center"},
                {visible: false, searchable: false},
                {visible: false, searchable: false},
                {visible: false, searchable: false}
<?php echo $custom_field_headers; ?>,
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
            printColumns: [1, 2, 3, 4, 6, 7],
            xlsColumns: [1, 2, 3, 4, 6, 7],
            summation: [{column: 6, dataType: 'currency'},{column: 7, dataType: 'currency'}]
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
                                // $(selector).DataTable().ajax.reload();
                            }
                        }
                    });
                    $(this).editable("show");
                });
    };

    $(document).ready(function () {
        loadExpensesTable("#monthly-expense-table", "monthly");
    });
</script>
