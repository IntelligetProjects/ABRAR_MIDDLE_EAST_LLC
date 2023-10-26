<div class="panel panel-default  p15 no-border m0">
    <span><?php echo lang("status") . ": " . $status_label; ?></span>
    <span class="ml15">
        
        <?php
        if ($sale_return_info->client_id) {
            echo lang("clients") . ": ";
            echo (anchor(get_uri("clients/view/" . $sale_return_info->client_id), $sale_return_info->company_name));
        }
        ?>
        <?php if ($sale_return_info->invoice_id) { ?>
        <span class="ml15"><?php echo lang("invoice") . ": ". anchor(get_uri("invoices/view/" . $sale_return_info->invoice_id), get_invoice_id($sale_return_info->invoice_id)); ?></span>
        <?php } ?>
    </span>
</div>