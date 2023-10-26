<div class="clearfix p20">
    <div class="panel clearfix">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <i class="fa fa-bar-chart pt10"></i>&nbsp; <?php echo lang("expense_categories"); ?>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card-body" style="width: 100%">
                        <?php expenses_cats_chart(); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="table-responsive">
                            <table 
                            id="expense_categories-table" class="display" cellspacing="0" width="100%">      
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <i class="fa fa-bar-chart pt10"></i>&nbsp; <?php echo lang("expenses_by_team_member"); ?>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card-body" style="width: 100%">
                        <?php expenses_emp_chart(); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="table-responsive">
                            <table 
                            id="expense_members-table" class="display" cellspacing="0" width="100%">      
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#expense_categories-table").appTable({
            source: '<?php echo_uri("expenses_report/cats_list_data") ?>',
            hideTools: false,
            displayLength: 10,
            dateRangeType: 'monthly',
            columns: [
                {title: '<?php echo lang("category") ?>'},
                {title: '<?php echo lang("amount") ?>', "class": "text-center"}
            ],
            onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
        });
        $("#expense_members-table").appTable({
            source: '<?php echo_uri("expenses_report/emps_list_data") ?>',
            hideTools: false,
            displayLength: 10,
            dateRangeType: 'monthly',
            columns: [
                {title: '<?php echo lang("team_member") ?>'},
                {title: '<?php echo lang("amount") ?>', "class": "text-center"}
            ],
            onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
        });
    });
</script>


