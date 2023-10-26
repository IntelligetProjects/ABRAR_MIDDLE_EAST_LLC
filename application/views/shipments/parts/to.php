<div><b><?php echo lang("shipment_from"); ?></b></div>
<div style="line-height: 2px; border-bottom: 1px solid #f2f2f2;"> </div>
<div style="line-height: 3px;"> </div>
<strong><?php echo $supplier_info->company_name; ?> </strong>
<div style="line-height: 3px;"> </div>
<span class="invoice-meta" style="font-size: 90%; color: #666;">
    <?php if ($supplier_info->phone ||$supplier_info->address || $supplier_info->vat_number || (isset($supplier_info->custom_fields) && $supplier_info->custom_fields)) { ?>
        <div><?php echo nl2br($supplier_info->address); ?>
            <?php if ($supplier_info->phone) { ?>
                <br /><?php echo $supplier_info->phone; ?>
            <?php } ?>
            <?php if ($supplier_info->city) { ?>
                <br /><?php echo $supplier_info->city; ?>
            <?php } ?>
            <?php if ($supplier_info->state) { ?>
                <br /><?php echo $supplier_info->state; ?>
            <?php } ?>
            <?php if ($supplier_info->zip) { ?>
                <br /><?php echo $supplier_info->zip; ?>
            <?php } ?>
            <?php if ($supplier_info->country) { ?>
                <br /><?php echo $supplier_info->country; ?>
            <?php } ?>
            <?php if ($supplier_info->vat_number) { ?>
                <br /><?php echo lang("vat_number") . ": " . $supplier_info->vat_number; ?>
            <?php } ?>
            <?php if ($shipment_info->invoice_number) { ?>
                <br /><?php echo lang("invoice_number") . ": " . $shipment_info->invoice_number   ; ?>
            <?php } ?>
            <?php if ($shipment_info->invoice_date) { ?>
                <br /><?php echo lang("invoice_date") . ": " . format_to_date($shipment_info->invoice_date); ?>
            <?php } ?>
            <?php if ($shipment_info->delivery_note_number) { ?>
                <br /><?php echo lang("delivery_note_number") . ": " . ($shipment_info->delivery_note_number); ?>
            <?php } ?>
            <?php if ($shipment_info->delivery_note_date) { ?>
                <br /><?php echo lang("delivery_note_date") . ": " . format_to_date($shipment_info->delivery_note_date); ?>
            <?php } ?>

        </div>
<?php } ?>
</span>