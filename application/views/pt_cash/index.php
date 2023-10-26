<div class="page-title clearfix no-border bg-off-white">
    <h1>
        <?php echo lang("petty_cash") ?>
    </h1>   
</div>

<div id="page-content" class="clearfix">
    <div class="mt15">
    </div>
    <ul data-toggle="ajax-tab" class="nav nav-tabs" role="tablist">
        <li><a  role="presentation" href="<?php echo_uri("PT_cash/cash_on_hand/"); ?>" data-target="#cash_on_hand"> <?php echo lang("petty_cash_summary"); ?></a></li>
        <?php if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "petty_cash") == "all") { ?>
        <li><a  role="presentation" href="<?php echo_uri("PT_cash/internal_transactions/"); ?>" data-target="#internal_transactions"> <?php echo lang("petty_cash_transactions"); ?></a></li>
        <?php } ?>
        <!-- <li><a  role="presentation" href="<?php echo_uri("PT_cash/expenses/"); ?>" data-target="#expenses"> <?php echo lang("expenses"); ?></a></li>         -->
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade" id="cash_on_hand"></div>
        <div role="tabpanel" class="tab-pane fade" id="internal_transactions"></div>
        <div role="tabpanel" class="tab-pane fade" id="expenses"></div>
    </div>

</div>