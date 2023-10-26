<div class="page-title clearfix no-border bg-off-white">
    <h1>
        <?php echo lang('cheques') ?>
    </h1>
</div>

<div id="page-content" class="clearfix">

    <ul id="tabs" data-toggle="ajax-tab" class="nav nav-tabs" role="tablist">

        <li><a  role="presentation" href="<?php echo_uri("cheques/expenses"); ?>" data-target="#one-files"><?php echo lang('expenses'); ?></a></li>

        <li><a  role="presentation" href="<?php echo_uri("cheques/received_payments"); ?>" data-target="#two-files"><?php echo lang('invoice_payments'); ?></a></li>

        <li><a  role="presentation" href="<?php echo_uri("cheques/purchase_payments"); ?>" data-target="#three-files"><?php echo lang('purchase_order_payments'); ?></a></li>

    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade" id="one-files"></div>
        <div role="tabpanel" class="tab-pane fade" id="two-files"></div>
        <div role="tabpanel" class="tab-pane fade" id="three-files"></div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

    });
</script>
