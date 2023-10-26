<?php echo form_open(get_uri("delivery_notes/save"), array("id" => "delivery-note-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <input type="hidden" name="client_id" value="<?php echo $model_info->client_id; ?>" />
    <input type="hidden" name="project_id" value="<?php echo $model_info->project_id; ?>" />
    <input type="hidden" name="invoice_id" value="<?php echo $invoice_id ? $invoice_id : $model_info->invoice_id; ?>" />
    <div class="form-group">
        <label for="delivery_note_date" class=" col-md-3"><?php echo lang('date'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "delivery_note_date",
                "name" => "delivery_note_date",
                "value" => $model_info->delivery_note_date,
                "class" => "form-control",
                "placeholder" => lang('delivery_note_date'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="delivery_note_note" class=" col-md-3"><?php echo lang('note'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "delivery_note_note",
                "name" => "delivery_note_note",
                "value" => $model_info->note ? $model_info->note : "",
                "class" => "form-control",
                "placeholder" => lang('note')
            ));
            ?>
        </div>
    </div>
    
    
    <?php if ($invoice_id) { ?>
        <input type="hidden" name="copy_items_from_invoice" value="<?php echo $invoice_id; ?>" />   
    <?php } ?>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        if ("<?php echo $invoice_id; ?>") {
            RELOAD_VIEW_AFTER_UPDATE = false; //go to page
        }

        $("#delivery-note-form").appForm({
            onSuccess: function (result) {
                if (typeof RELOAD_VIEW_AFTER_UPDATE !== "undefined" && RELOAD_VIEW_AFTER_UPDATE) {
                    location.reload();
                } else {
                    window.location = "<?php echo site_url('delivery_notes/view'); ?>/" + result.id;
                }
            }
        });
       

        setDatePicker("#delivery_note_date");


    });
</script>