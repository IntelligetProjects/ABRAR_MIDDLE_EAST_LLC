<?php echo form_open(get_uri("items/save_adjustment"), array("id" => "ad-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="item_id" value="<?php echo $item_id; ?>" />

    <div class="form-group">
        <label for="item" class=" col-md-3"><?php echo lang('item'); ?></label>
        <label  class=" col-md-9"><?php echo $model_info->title; ?></label>
    </div>

    <div class="form-group">
        <label for="current_quantity" class=" col-md-3"><?php echo lang('current_quantity'); ?></label>
        <label  class=" col-md-9"><?php echo $current_quantity; ?></label>
    </div>

    <div class="form-group">
        <label for="quantity" class=" col-md-3"><?php echo lang('Adjust_quantity'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "quantity",
                "name" => "quantity",
                "value" =>  $adj_quantity,
                "class" => "form-control",
                "placeholder" => lang('new_quantity'),
                "data-rule-required" => true,
                // "min" => 0,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="note" class="col-md-3"><?php echo lang('note'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "note",
                "name" => "note",
                "value" =>  $adj_note,
                "class" => "form-control",
                "placeholder" => lang('note'),
                "data-rich-text-editor" => true
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
    $(document).ready(function () {
        $("#ad-form").appForm({
            onSuccess: function (result) {
                $("#item-table").appTable({newData: result.data, dataId: result.id});
            }
        });
    });
</script>