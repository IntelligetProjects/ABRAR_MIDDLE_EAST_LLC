<?php echo form_open(get_uri("items/save"), array("id" => "item-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <?php if ($model_info->id) { ?>
        <div class="form-group">
            <div class="col-md-12 text-off"> <?php echo lang('item_edit_instruction'); ?></div>
        </div>
    <?php } ?>

    <div class="form-group">
        <label for="title" class=" col-md-3"><?php echo lang('title'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "title",
                "name" => "title",
                "value" => $model_info->title,
                "class" => "form-control validate-hidden",
                "placeholder" => lang('title'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
            <label for="item_type" class=" col-md-3"><?php echo lang('item_type'); ?></label>
            <div class=" col-md-9">
                <?php
               echo form_dropdown("item_type", $types_dropdown, $model_info->item_type, "class='select2 validate-hidden' id='item_type' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                ?>
            </div>
    </div>
    <?php  if( $this->db->dbprefix=='Tadqeeq'){ ?>
     <!-- certificate_no -->   
     <div class="form-group">
                <label for="certificate_no" class="col-md-3"><?php echo lang('certificate_no'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "certificate_no",
                        "name" => "certificate_no",
                        "value" => $model_info->certificate_no,
                        "class" => "form-control lead_data",
                        "placeholder" => lang('certificate_no'),
                        "data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
    <?php } ?>
    <div class="form-group">
            <label for="category_id" class=" col-md-3"><?php echo lang('item_category'); ?></label>
            <div class=" col-md-9">
                <?php
                echo form_dropdown("category_id", $categories_dropdown, $model_info->category_id, "class='select2 validate-hidden' id='category_id' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                ?>
            </div>
    </div>

    <div class="form-group">
        <label for="description" class="col-md-3"><?php echo lang('description'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "description",
                "name" => "description",
                "value" => $model_info->description ? $model_info->description : "",
                "class" => "form-control",
                "placeholder" => lang('description'),
                "data-rich-text-editor" => true
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="unit_type" class=" col-md-3"><?php echo lang('unit_type'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "unit_type",
                "name" => "unit_type",
                "value" => $model_info->unit_type,
                "class" => "form-control",
                "placeholder" => lang('unit_type') . ' (Ex: hours, pc, etc.)'
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="item_cost" class=" col-md-3"><?php echo lang('cost_price'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "item_cost",
                "name" => "item_cost",
                "value" => $model_info->cost ? $model_info->cost : "",
                "class" => "form-control",
                "placeholder" => lang('cost_price'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    
    <div class="form-group">
        <label for="item_rate" class=" col-md-3"><?php echo lang('rate'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "item_rate",
                "name" => "item_rate",
                "value" => $model_info->rate ? $model_info->rate : "",
                "class" => "form-control",
                "placeholder" => lang('rate'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>

    <?php if($this->db->dbprefix == 'tarteeb_v3'){ ?>
    <!-- MAX STOCK -->
    <div class="form-group">
        <label for="item_rate" class=" col-md-3"><?php echo lang('max_stock'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "max_stock",
                "name" => "max_stock",
                "value" => $model_info->max_stock ? $model_info->max_stock : "",
                "class" => "form-control",
                "placeholder" => lang('max_stock'),
            ));
            ?>
        </div>
    </div>
    <!-- NOTIFY WHEN  -->
    <div class="form-group">
        <label for="item_rate" class=" col-md-3"><?php echo lang('notify_max_stock_on'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "notify_max_stock_on",
                "name" => "notify_max_stock_on",
                "value" => $model_info->notify_max_stock_on ? $model_info->notify_max_stock_on : "",
                "class" => "form-control",
                "placeholder" => lang('notify_max_stock_on'),
            ));
            ?>
        </div>
    </div>
    <?php }?>
    

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#item-form").appForm({
            onSuccess: function (result) {
                $("#item-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        $("#item-form #item_type").select2();
        $("#item-form #category_id").select2();
    });
</script>