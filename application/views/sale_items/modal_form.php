<?php echo form_open(get_uri("sale_items/save"), array("id" => "category-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="form-group">
            <label for="item_id" class=" col-md-3"><?php echo lang('sale_item_account'); ?></label>
            <div class="col-md-9">
                <?php
                // echo form_dropdown("sale_account_id", $items_dropdown, array($model_info->sale_account_id), "class=' select2 validate-hidden' id='item_id' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                echo form_dropdown("sale_account_id", $items_dropdown, array($model_info->sale_account_id), "class='select2 validate-hidden' id='item_id' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
               
                ?>
                
            </div>
    </div>
    <div class="form-group">
            <label for="parent" class=" col-md-3"><?php echo lang('cost_of_sale_item_account'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("sale_cost_account_id", $items_dropdown, $model_info->sale_cost_account_id, "class=' form-control select2 validate-hidden' id='item_id2' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                ?>
            </div>
    </div>
    <div class="form-group">
        <br />
        <label for="title" class=" col-md-3"><?php echo lang('title'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "title",
                "name" => "title",
                "value" => $model_info->title,
                "class" => "form-control",
                "placeholder" => lang('title'),
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
        $("#item_id").select2();
        $("#item_id2").select2();
        $("#category-form").appForm({
            onSuccess: function(result) {
                $("#category-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        $("#title").focus();
    });
</script>