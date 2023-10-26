<div class="panel panel-default  p15 no-border m0">
    <span><?php echo lang("status") . ": " . $status_label; ?></span>
    <span class="ml15">
        
        <?php
        if ($purchase_return_info->supplier_id) {
            echo lang("supplier") . ": ";
            echo (anchor(get_uri("suppliers/view/" . $purchase_return_info->supplier_id), $purchase_return_info->company_name));
        }
        ?>
        <?php if ($purchase_return_info->purchase_order_id) { ?>
        <span class="ml15"><?php echo lang("purchase_order") . ": ". anchor(get_uri("purchase_orders/view/" . $purchase_return_info->purchase_order_id), get_purchase_order_id($purchase_return_info->purchase_order_id)); ?></span>
        <?php } ?>
    </span>
</div>