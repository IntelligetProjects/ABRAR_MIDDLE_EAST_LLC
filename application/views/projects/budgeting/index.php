<div id="page-content" class="p20 clearfix">
    <div class="panel clearfix">
        <ul id="estimate-tabs" data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang('Budgeting'); ?></h4></li>
            <li><a id="monthly-estimate-button" class="active" role="presentation" href="javascript:;" data-target="#monthly-estimates"><?php echo lang("monthly"); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("budgeting/yearly/"); ?>" data-target="#yearly-estimates"><?php echo lang('yearly'); ?></a></li>
            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    <?php echo modal_anchor(get_uri("budgeting/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_budget'), array("class" => "btn btn-default", "title" => lang('add_budget'))); ?>
                </div>
            </div>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="monthly-estimates">
                <div class="table-responsive">
                    <table id="monthly-estimate-table" class="display" cellspacing="0" width="100%">   
                    </table>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="yearly-estimates"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    loadEstimatesTable = function (selector, dateRange) {
        $(selector).appTable({
            source: '<?php echo_uri("budgeting/list_data_projects/".$project_id) ?>',
            order: [[0, "desc"]],
            dateRangeType: dateRange,
            // filterDropdown: [{name: "status", class: "w150", options: <?php $this->load->view("budgeting/estimate_statuses_dropdown"); ?>}],
            columns: [
                {title: "<?php echo lang("id") ?>"},
                {title: "<?php echo lang("budgeting") ?> "},
                {title: "<?php echo lang("project") ?> "},
                {visible: false, searchable: false},
                {title: "<?php echo lang("profit") ?> "},
                {title: "<?php echo lang("budgeting_date") ?>", "iDataSort": 3, }
<?php //echo $custom_field_headers; ?>,
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
            printColumns: combineCustomFieldsColumns([0, 1, 2,3], '<?php //echo $custom_field_headers; ?>'),
            xlsColumns: combineCustomFieldsColumns([0, 1,2, 3], '<?php //echo $custom_field_headers; ?>'),
            // summation: [{column: 5, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol}, {column: 6, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol}]
        });
    };

    $(document).ready(function () {
        loadEstimatesTable("#monthly-estimate-table", "monthly");
    });

</script>