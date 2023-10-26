<?php echo form_open(get_uri("sale_returns/save"), array("id" => "delivery-note-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <input type="hidden" name="invoice_id" value="<?php echo $invoice_id ? $invoice_id : $model_info->invoice_id; ?>" />
    <?php if (!$model_info->invoice_id) { ?>
    <div class="form-group">
        <label for="invoice_id" class=" col-md-3"><?php echo lang('invoice'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_dropdown("invoice_id", $invoices_dropdown, $model_info->invoice_id, "class='select2 validate-hidden' id='invoice_id' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
            ?>
        </div>
    </div>
    <?php } ?>

    <div class="form-group">
        <label for="sale_return_date" class=" col-md-3"><?php echo lang('sale_return_date'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "sale_return_date",
                "name" => "sale_return_date",
                "value" => $model_info->date,
                "class" => "form-control",
                "placeholder" => lang('sale_return_date'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>

    <div class="form-group" style="display:none">
        <label for="payment_method_id" class=" col-md-3"><?php echo lang('payment_method'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("payment_method_id", $payment_methods_dropdown, array($model_info->payment_method_id), "class='select2' id='payment_method_id'");
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="sale_return_note" class=" col-md-3"><?php echo lang('note'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "sale_return_note",
                "name" => "sale_return_note",
                "value" => $model_info->note ? $model_info->note : "",
                "class" => "form-control",
                "placeholder" => lang('note')
            ));
            ?>
        </div>
    </div>
    
    
    <?php if (!$model_info->invoice_id) { ?>
        <input type="hidden" name="copy_items_from_invoice" value="<?php echo 1; ?>" />   
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
                    window.location = "<?php echo site_url('sale_returns/view'); ?>/" + result.id;
                }
            }
        });
       
        $("#delivery-note-form .select2").select2();
        setDatePicker("#sale_return_date");


    });
</script>