<div class="panel panel-default  p15 no-border m0">
    <span><?php echo lang("status") . ": " . $status_label; ?></span>
    <span class="ml15">
    	
        <?php
        if ($shipment_info->supplier_id) {
            echo lang("supplier") . ": ";
            echo (anchor(get_uri("suppliers/view/" . $shipment_info->supplier_id), $shipment_info->company_name));
        }
        ?>
        <?php if ($shipment_info->purchase_order_id) { ?>
        <span class="ml15"><?php echo lang("purchase_order") . ": ". anchor(get_uri("purchase_orders/view/" . $shipment_info->purchase_order_id), get_purchase_order_id($shipment_info->purchase_order_id)); ?></span>
    	<?php } ?>
    </span>
</div>