<div class="clearfix p20">
    <div class="panel clearfix">
        <div class="row">
            <div class="col-md-6">
                <div class="panel <?php echo "panel-primary" ?>">
                    <div class="panel-body ">
                        <div class="widget-icon">
                            <i class="fa <?php echo "fa-group" ?>"></i>
                        </div>
                        <div class="widget-details">
                            <h1><?php echo $leads_count; ?></h1>
                            <?php echo lang("leads"); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel <?php echo "panel-success"; ?>">
                    <div class="panel-body ">
                        <div class="widget-icon">
                            <i class="fa <?php echo "fa-group"; ?>"></i>
                        </div>
                        <div class="widget-details">
                            <h1><?php echo $clients_count; ?></h1>
                            <?php echo lang("clients"); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="card-body">
                <?php invoice_statistics_widget(); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="panel <?php echo "panel-primary" ?>">
                    <div class="panel-body ">
                        <div class="widget-icon">
                            <i class="fa <?php echo "fa-file-text" ?>"></i>
                        </div>
                        <div class="widget-details">
                            <h3><?php echo to_currency($invoices_info->invoices_total); ?></h3>
                            <?php echo lang("invoice_value"); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel <?php echo "panel-success"; ?>">
                    <div class="panel-body ">
                        <div class="widget-icon">
                            <i class="fa <?php echo "fa-check-square"; ?>"></i>
                        </div>
                        <div class="widget-details">
                            <h3><?php echo to_currency($invoices_info->payments_total); ?></h3>
                            <?php echo lang("payments"); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel <?php echo "panel-coral"; ?>">
                    <div class="panel-body ">
                        <div class="widget-icon">
                            <i class="fa <?php echo "fa-money"; ?>"></i>
                        </div>
                        <div class="widget-details">
                            <h3><?php echo to_currency(ignor_minor_value($invoices_info->due)); ?></h3>
                            <?php echo lang("due"); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <i class="fa fa-bar-chart pt10"></i>&nbsp; <?php echo lang("estimates")." - ".lang("invoices"); ?>
            </div>
            <div class="card-body">
                <?php estimates_statistics_widget(); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="panel <?php echo "panel-primary" ?>">
                    <div class="panel-body ">
                        <div class="widget-icon">
                            <i class="fa <?php echo "fa-file-text" ?>"></i>
                        </div>
                        <div class="widget-details">
                            <h3><?php echo to_currency(ignor_minor_value($invoices_info->total_quotation)); ?></h3>
                            <?php echo lang("estimate_value"); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel <?php echo "panel-success"; ?>">
                    <div class="panel-body ">
                        <div class="widget-icon">
                            <i class="fa <?php echo "fa-group"; ?>"></i>
                        </div>
                        <div class="widget-details">
                            <h3><?php echo round(($invoices_info->invoices_total / (!empty($invoices_info->total_quotation)?$invoices_info->total_quotation:1) * 100 ), 2) . "%"; ?></h3>
                            <?php echo lang("invoice_value")." / ".lang("estimate_value"); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix p20">
            <div class="panel clearfix">
                    <div class="panel panel-default">
                        <div class="page-title clearfix">
                            <h1><?php echo lang('team_members')." ".lang('sales'); ?></h1>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table 
                                    id="quotations_user-table" class="display" cellspacing="0" width="100%">            
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table 
                                    id="invoices_user-table" class="display" cellspacing="0" width="100%">           
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="panel <?php echo "panel-primary" ?>">
                    <div class="panel-body ">
                        <div class="widget-icon">
                            <i class="fa <?php echo "fa-file" ?>"></i>
                        </div>
                        <div class="widget-details">
                            <h3><?php echo $total_estimates; ?>
                            <?php echo lang("estimates")." ".lang("for"); ?>
                            <?php echo $total_estimates_clients; ?>
                            <?php echo lang("leads")/*." ".lang("and")." ".lang("clients")*/; ?></h3>
                        </div>
                    </div>
                </div>
                <div class="panel <?php echo "panel-success"; ?>">
                    <div class="panel-body ">
                        <div class="widget-icon">
                            <i class="fa <?php echo "fa-file"; ?>"></i>
                        </div>
                        <div class="widget-details">
                            <h3><?php echo $total_invoices; ?>
                            <?php echo lang("invoices")." ".lang("for"); ?>
                            <?php echo $total_invoices_clients; ?>
                            <?php echo lang("clients"); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        <i class="fa fa-bar-chart pt10"></i>&nbsp; <?php echo lang("payment_methods"); ?>
                    </div>
                    <div class="card-body">
                        <?php payment_methods_chart(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#quotations_user-table").appTable({
            source: '<?php echo_uri("sales_report/list_data_estimate_user") ?>',
            hideTools: true,
            columns: [
                {title: '<?php echo lang("employee") ?>'},
                {title: '<?php echo lang("estimate")." ".lang("value") ?>'}
            ],
            onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
        });

        $("#invoices_user-table").appTable({
            source: '<?php echo_uri("sales_report/list_data_invoice_user") ?>',
            hideTools: true,
            columns: [
                {title: '<?php echo lang("employee") ?>'},
                {title: '<?php echo lang("invoice")." ".lang("value") ?>'}
            ],
            onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
        });
    });
</script>

