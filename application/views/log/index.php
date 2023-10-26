<div class="page-title clearfix no-border bg-off-white">
    <h1>
        <?php echo lang("logs") ?>        
    </h1>
</div>
<div id="page-content" class="clearfix">
    <?php 
        $modules = array( 'clients', 'events', 'items','purchase_orders', 'purchase_order_payments', 'suppliers','contacts', 'estimates', 'invoices', 'delivery_notes', 'expenses', 'invoice_payments', 'projects', 'tasks','general');

    ?>


    <ul data-toggle="ajax-tab" class="nav nav-tabs" role="tablist">
    <?php foreach ($modules as $module) { ?>
          <li><a  role="presentation" href="<?php echo_uri("log/show_tab/".$module); ?>" data-target="#<?php echo $module ?>"> <?php echo lang($module) ; ?></a></li>
    <?php } ?>
    </ul>



    <div class="tab-content">
        <?php foreach ($modules as $module) { ?>
            <div role="tabpanel" class="tab-pane fade" id="<?php echo $module ?>"></div>
        <?php } ?>
    </div>
</div>