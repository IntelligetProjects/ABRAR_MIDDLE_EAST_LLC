<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<div class="page-title clearfix no-border bg-off-white">
    <h1>
        <?php echo lang('reports') ?>
    </h1>
</div>

<div id="page-content" class="clearfix">

    <ul id="tabs" data-toggle="ajax-tab" class="nav nav-tabs" role="tablist">

        <li><a  role="presentation" href="<?php echo_uri("expenses/income_vs_expenses"); ?>" data-target="#one"><?php echo lang('income_vs_expenses'); ?></a></li>

        <li><a  role="presentation" href="<?php echo_uri("sales_report/index"); ?>" data-target="#two"><?php echo lang('sales'); ?></a></li>

        <li><a  role="presentation" href="<?php echo_uri("expenses_report/index"); ?>" data-target="#three"><?php echo lang('expenses'); ?></a></li>

        <li><a  role="presentation" href="<?php echo_uri("projects_report/index"); ?>" data-target="#four"><?php echo lang('projects'); ?></a></li>

        <li><a  role="presentation" href="<?php echo_uri("items_report/index"); ?>" data-target="#five"><?php echo lang('inventory'); ?></a></li>

        <!-- <li><a  role="presentation" href="<?php echo_uri("hr_report/index"); ?>" data-target="#six"><?php echo lang('hr'); ?></a></li> -->

    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade" id="one"></div>
        <div role="tabpanel" class="tab-pane fade" id="two"></div>
        <div role="tabpanel" class="tab-pane fade" id="three"></div>
        <div role="tabpanel" class="tab-pane fade" id="four"></div>
        <div role="tabpanel" class="tab-pane fade" id="five"></div>
        <div role="tabpanel" class="tab-pane fade" id="six"></div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

    });
</script>
