<?php echo form_open(get_uri("Items_levels/save"), array("id" => "Items-levels-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="form-group">
        <br />
        <label for="level_name" class=" col-md-3"><?php echo lang('item_level'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "level_name",
                "name" => "level_name",
                "value" => $model_info->level_name,
                "class" => "form-control",
                "placeholder" => lang('item_level'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
        <br />
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#Items-levels-form").appForm({
            onSuccess: function(result) {
                $("#levels-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        $("#level_name").focus();
    });
</script>