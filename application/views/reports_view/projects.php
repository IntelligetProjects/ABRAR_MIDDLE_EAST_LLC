<div class="clearfix p20">
    <div class="panel clearfix">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <i class="fa fa-bar-chart pt10"></i>&nbsp; <?php echo lang("summary"); ?>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card-body" style="width: 100%">
                        <?php projects_status_chart(); ?>
                    </div>
                </div>
                <div class="col-md-6">
                <div class="panel <?php echo "panel-primary" ?>">
                    <div class="panel-body ">
                        <div class="widget-icon">
                            <i class="fa <?php echo "fa-bar-chart" ?>"></i>
                        </div>
                        <div class="widget-details">
                            <h3><?php echo $total_projects; ?>
                            <?php echo lang("projects")." ".lang("for"); ?>
                            <?php echo $total_projects_clients; ?>
                            <?php echo lang("clients"); ?></h3>
                        </div>
                    </div>
                </div>
                <div class="panel <?php echo "panel-success"; ?>">
                    <div class="panel-body ">
                        <div class="widget-icon">
                            <i class="fa <?php echo "fa-bar-chart"; ?>"></i>
                        </div>
                        <div class="widget-details">
                            <h3><?php echo $total_invoices; ?>
                            <?php echo lang("invoices")." ".lang("for"); ?>
                            <?php echo $total_invoices_clients; ?>
                            <?php echo lang("clients"); ?></h3>
                        </div>
                    </div>
                </div>
                <div class="panel <?php echo "panel-primary"; ?>">
                    <div class="panel-body ">
                        <div class="widget-icon">
                            <i class="fa <?php echo "fa-bar-chart"; ?>"></i>
                        </div>
                        <div class="widget-details">
                            <h3><?php echo $total_p_invoices; ?>
                            <?php echo lang("invoices_related_to_projects")." ".lang("for"); ?>
                            <?php echo $total_p_invoices_clients; ?>
                            <?php echo lang("clients"); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
         </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <i class="fa fa-list pt10"></i>&nbsp; <?php echo lang("projects"); ?>
            </div>
            <div class="panel panel-default">
                <div class="table-responsive">
                    <table 
                    id="project_report-table" class="display" cellspacing="0" width="100%">      
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#project_report-table").appTable({
            source: '<?php echo_uri("projects_report/list_data_projects") ?>',
            hideTools: false,
            columnShowHideOption: false,
            displayLength: 25,
            columns: [
                {title: '<?php echo lang("project") ?>'},
                {title: '<?php echo lang("client")?>'},
                {title: '<?php echo lang("labels") ?>'},
                {title: '<?php echo lang("total_cost_of_products")?>'},
                {title: '<?php echo lang("total")." ".lang("expenses")?>'},
                {title: '<?php echo lang("total")." ".lang("payments")?>'},
                {title: '<?php echo lang("project_members")?>', "class": "text-center"},
            ],
           
            onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
        });
    });
</script>
