<div class="panel panel-default  p15 no-border m0">
    <span><?php echo lang("status") . ": " . $status_label; ?></span>
    <span class="ml15">
    	
        <?php
        if ($delivery_note_info->client_id) {
            echo lang("client") . ": ";
            echo (anchor(get_uri("clients/view/" . $delivery_note_info->client_id), $delivery_note_info->company_name));
        }
        ?>
        <?php if ($delivery_note_info->invoice_id) { ?>
        <span class="ml15"><?php echo lang("invoice") . ": ". anchor(get_uri("invoices/view/" . $delivery_note_info->invoice_id), get_invoice_id($delivery_note_info->invoice_id)); ?></span>
    	<?php } ?>
        <?php if ($delivery_note_info->project_id) { ?>
        <span class="ml15"><?php echo lang("project") . ": " . anchor(get_uri("projects/view/" . $delivery_note_info->project_id), $delivery_note_info->project_title); ?></span>
    	<?php } ?>
    </span>
</div>