<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1><?php echo lang('salary_summary'); ?></h1>
            <!-- <div class="title-button-group">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-default btn-sm active mr-1"  title="<?php echo lang('list_view'); ?>"><i class="fa fa-bars"></i></button>
                    <?php echo anchor(get_uri("team_members/view"), "<i class='fa fa-th-large'></i>", array("class" => "btn btn-default btn-sm")); ?>
                </div>
            </div> -->
        </div>
        <div class="table-responsive">
            <table id="salary-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        
        $("#sidebar").addClass("collapsed");

        $("#salary-table").appTable({
            source: '<?php echo_uri("team_members/list_data_salary_chart/") ?>',
            saveState: false,
            order: [[0, "asc"]],
            columns: [
                {title: "<?php echo lang("emp_id") ?>", "class": "w50 text-center"},
                {title: "", "class": "w50 text-center"},
                {title: "<?php echo lang("name") ?>"},
                {title: "<?php echo lang("job_title") ?>"},
                {title: "<?php echo lang("basic_salary") ?>"},
                {title: "<?php echo lang("housing_allowance") ?>"},
                {title: "<?php echo lang("transportation_allowance") ?>"},
                {title: "<?php echo lang("phone_allowance") ?>"},
                {title: "<?php echo lang("utility") ?>"},
                {title: "<?php echo lang("gross_salary") ?>"},
                {title: "<?php echo lang("joining_date") ?>"},
                {title: "<?php echo lang("years_of_employement") ?>"},
                {title: "<?php echo lang("gratuity") ?>"},
                {title: "<?php echo lang("pasi_11.5%") ?>"},
                {title: "<?php echo lang("employee_pasi_7%") ?>"},
                {title: "<?php echo lang("job_security_1%") ?>"},
                {title: "<?php echo lang("employee_job_security_1%") ?>"},
            ],
            onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
            printColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4]),
            xlsColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4])

        });
    });
</script>    
