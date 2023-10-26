<?php echo form_open(get_uri("cost_centers/save"), array("id" => "cost-center-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="form-group">
        <label for="name" class=" col-md-3"><?php echo lang('name'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "name",
                "name" => "name",
                "value" => $model_info->name,
                "class" => "form-control",
                "placeholder" => lang('name'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="currency_id" class=" col-md-3"><?php echo lang('currency'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_dropdown("currency_id", $currencies_dropdown, array($model_info->currency_id), "class=' form-control select2 validate-hidden' id='currency_id' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
            ?>
        </div>
    </div>


</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#cost-center-form").appForm({
            onSuccess: function(result) {
                $("#cost_centers-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        $("#name").focus();

        $("#currency_id").select2();
    });
</script>    