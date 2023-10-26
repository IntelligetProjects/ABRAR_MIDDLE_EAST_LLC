<?php echo form_open(get_uri("shipments/save"), array("id" => "delivery-note-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <input type="hidden" name="supplier_id" value="<?php echo $model_info->supplier_id; ?>" />
    <input type="hidden" name="purchase_order_id" value="<?php echo $purchase_order_id ? $purchase_order_id : $model_info->purchase_order_id; ?>" />
    <div class="form-group">
        <label for="shipment_date" class=" col-md-3"><?php echo lang('shipment_date'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "shipment_date",
                "name" => "shipment_date",
                "value" => $model_info->date,
                "class" => "form-control",
                "placeholder" => lang('shipment_date'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="invoice_number" class=" col-md-3"><?php echo lang('invoice_number'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "invoice_number",
                "name" => "invoice_number",
                "value" => $model_info->invoice_number,
                "class" => "form-control",
                "placeholder" => lang('invoice_number'),
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="invoice_date" class=" col-md-3"><?php echo lang('invoice_date'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "invoice_date",
                "name" => "invoice_date",
                "value" => $model_info->invoice_date,
                "class" => "form-control",
                "placeholder" => lang('invoice_date'),
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="delivery_note_number" class=" col-md-3"><?php echo lang('delivery_note_number'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "delivery_note_number",
                "name" => "delivery_note_number",
                "value" => $model_info->delivery_note_number,
                "class" => "form-control",
                "placeholder" => lang('delivery_note_number'),
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="delivery_note_date" class=" col-md-3"><?php echo lang('delivery_note_date'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "delivery_note_date",
                "name" => "delivery_note_date",
                "value" => $model_info->delivery_note_date,
                "class" => "form-control",
                "placeholder" => lang('delivery_note_date'),
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="shipment_note" class=" col-md-3"><?php echo lang('note'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "shipment_note",
                "name" => "shipment_note",
                "value" => $model_info->note ? $model_info->note : "",
                "class" => "form-control",
                "placeholder" => lang('note')
            ));
            ?>
        </div>
    </div>
    
    
    <?php if ($purchase_order_id) { ?>
        <input type="hidden" name="copy_items_from_invoice" value="<?php echo $purchase_order_id; ?>" />   
    <?php } ?>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        if ("<?php echo $purchase_order_id; ?>") {
            RELOAD_VIEW_AFTER_UPDATE = false; //go to page
        }

        $("#delivery-note-form").appForm({
            onSuccess: function (result) {
                if (typeof RELOAD_VIEW_AFTER_UPDATE !== "undefined" && RELOAD_VIEW_AFTER_UPDATE) {
                    location.reload();
                } else {
                    window.location = "<?php echo site_url('shipments/view'); ?>/" + result.id;
                }
            }
        });
       

        setDatePicker("#shipment_date");
        setDatePicker("#invoice_date");
        setDatePicker("#delivery_note_date");


    });
</script>