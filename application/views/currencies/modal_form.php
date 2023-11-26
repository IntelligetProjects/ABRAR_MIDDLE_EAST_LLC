<?php echo form_open(get_uri("currencies/save"), array("id" => "currency-form", "class" => "general-form", "role" => "form")); ?>
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
                "readonly" => true,
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="symbol" class=" col-md-3"><?php echo lang('symbol'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "symbol",
                "name" => "symbol",
                "value" => $model_info->symbol,
                "class" => "form-control",
                "placeholder" => lang('symbol'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
                "readonly" => true,
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="rate" class=" col-md-3"><?php echo lang('currency_rate').$currency_note; ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "rate",
                "name" => "rate",
                "value" => $model_info->rate ? number_format($model_info->rate,6) : "",
                "class" => "form-control",
                "placeholder" => lang('currency_rate'),
                "type" => "number",
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
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
        $("#currency-form").appForm({
            onSuccess: function(result) {
                $("#currencies-table").appTable({newData: result.data, dataId: result.id});
            }
        });

        // $("#title").hide();
    });
</script>    