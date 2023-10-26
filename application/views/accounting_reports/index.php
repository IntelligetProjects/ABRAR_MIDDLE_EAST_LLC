<div class="page-title clearfix no-border bg-off-white">
    <h1>
        <?php echo lang('accounting_reports') ?>
    </h1>
</div>

<div id="page-content" class="clearfix">

    <ul id="tabs" data-toggle="ajax-tab" class="nav nav-tabs" role="tablist">
<?php if($this->db->dbprefix=="dev"){?>
        <li><a  role="presentation" href="<?php echo_uri("accounting_reports/balance_sheet"); ?>" data-target="#one"><?php echo lang('balance_sheet'); ?></a></li>   
        <li><a  role="presentation" href="<?php echo_uri("accounting_reports/expenses"); ?>" data-target="#seven"><?php echo lang('expenses'); ?></a></li>
        <li><a  role="presentation" href="<?php echo_uri("accounting_reports/cash_flow"); ?>" data-target="#three"><?php echo lang('cash_flow'); ?></a></li>
<?php } ?>
        <li><a  role="presentation" href="<?php echo_uri("accounting_reports/profit_and_loss"); ?>" data-target="#two"><?php echo lang('profit_and_loss'); ?></a></li>
        <li><a  role="presentation" href="<?php echo_uri("accounting_reports/trial_balance"); ?>" data-target="#four"><?php echo lang('trial_balance'); ?></a></li>

    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade" id="one"></div>
        <div role="tabpanel" class="tab-pane fade" id="two"></div>
        <div role="tabpanel" class="tab-pane fade" id="three"></div>
        <div role="tabpanel" class="tab-pane fade" id="four"></div>
        <div role="tabpanel" class="tab-pane fade" id="five"></div>
        <div role="tabpanel" class="tab-pane fade" id="six"></div>
        <div role="tabpanel" class="tab-pane fade" id="seven"></div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

    });
</script>
