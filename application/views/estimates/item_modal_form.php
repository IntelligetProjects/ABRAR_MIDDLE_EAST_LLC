<?php echo form_open(get_uri("estimates/save_item"), array("id" => "estimate-item-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <input type="hidden" name="estimate_id" value="<?php echo $estimate_id; ?>" />
    <input type="hidden" id='estimate_item_id' name="estimate_item_id" value="<?php echo $model_info->item_id; ?>" />
    <input type="hidden" name="add_new_item_to_library" value="" id="add_new_item_to_library" />
    <div class="form-group">
        <label for="estimate_item_title" class=" col-md-3"><?php echo lang('item'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "estimate_item_title",
                "name" => "estimate_item_title",
                "value" => $model_info->title,
                "class" => "form-control validate-hidden",
                "placeholder" => lang('select_or_create_new_item'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
            <a id="estimate_item_title_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span>×</span></a>
        </div>
    </div>
    <div class="form-group">
        <label for="estimate_item_description" class="col-md-3"><?php echo lang('description'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "estimate_item_description",
                "name" => "estimate_item_description",
                "value" => $model_info->description ? $model_info->description : "",
                "class" => "form-control",
                "placeholder" => lang('description'),
                "data-rich-text-editor" => true
            ));
            ?>
        </div>
    </div>

    <div id="item_type_field" class="form-group">
            <label for="item_type" class=" col-md-3"><?php echo lang('item_type'); ?></label>
            <div class=" col-md-9">
                <?php
               echo form_dropdown("item_type", $types_dropdown, "", "class='select2 validate-hidden' id='item_type'");
                ?>
            </div>
    </div>

    <div id="category_id_field" class="form-group">
            <label for="category_id" class=" col-md-3"><?php echo lang('item_category'); ?></label>
            <div class=" col-md-9">
                <?php
                echo form_dropdown("category_id", $categories_dropdown, "", "class='select2 validate-hidden' id='category_id'");
                ?>
            </div>
    </div>

    <div class="form-group">
        <label for="estimate_item_quantity" class=" col-md-3"><?php echo lang('quantity'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "estimate_item_quantity",
                "name" => "estimate_item_quantity",
                "value" => $model_info->quantity ? to_decimal_format($model_info->quantity) : "",
                "class" => "form-control",
                "placeholder" => lang('quantity'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
                "min" => 1,
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="estimate_unit_type" class=" col-md-3"><?php echo lang('unit_type'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "estimate_unit_type",
                "name" => "estimate_unit_type",
                "value" => $model_info->unit_type,
                "class" => "form-control",
                "placeholder" => lang('unit_type') . ' (Ex: hours, pc, etc.)'
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="estimate_item_rate" class=" col-md-3"><?php echo lang('rate'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "estimate_item_rate",
                "name" => "estimate_item_rate",
                "value" => $model_info->rate ? to_decimal_format($model_info->rate) : "",
                "class" => "form-control",
                "placeholder" => lang('rate'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="discount" class="col-md-3"><?php echo lang('discount'); ?></label>
        <div class="col-md-4">
            <?php
            echo form_input(array(
                "id" => "discount",
                "name" => "discount_amount",
                "value" => $model_info->discount_amount ? $model_info->discount_amount : "",
                "class" => "form-control",
                "autofocus" => "true",
                "placeholder" => lang('discount'),
                "data-rule-required" => false,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
        <div class="col-md-5">
            <?php
            $discount_percentage_dropdown = array("percentage" => lang("percentage"), "fixed_amount" => lang("fixed_amount"));
            echo form_dropdown("discount_amount_type", $discount_percentage_dropdown, $model_info->discount_amount_type, "class='form-control select2'");
            ?>
        </div>
    </div>
    
    <div class="form-group">
        <label for="tax_id" class=" col-md-3"><?php echo lang('tax'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("tax_id", $taxes_dropdown, array($model_info->tax_id), "class='select2 tax-select2' id='tax_id'");
            ?>
        </div>
    </div>
    <!-- <div class="form-group">
        <label for="tax_id" class=" col-md-3"><?php echo lang('second_tax'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("tax_id2", $taxes_dropdown, array($model_info->tax_id2), "class='select2 tax-select2' id='tax_id2'");
            ?>
        </div>
    </div> -->
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#estimate-item-form").appForm({
            onSuccess: function (result) {
                $("#estimate-item-table").appTable({newData: result.data, dataId: result.id});
                $("#estimate-total-section").html(result.estimate_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.estimate_id);
                }
            }
        });

        //show item suggestion dropdown when adding new item
        var isUpdate = "<?php echo $model_info->id; ?>";
        if (!isUpdate) {
            applySelect2OnItemTitle();
        }

        //re-initialize item suggestion dropdown on request
        $("#estimate_item_title_dropdwon_icon").click(function () {
            applySelect2OnItemTitle();
        })

    });

    $("#estimate-item-form #item_type").select2();
    $("#estimate-item-form #category_id").select2();
    $('#item_type_field').hide();
    $('#category_id_field').hide();
    $("#estimate-item-form #tax_id").select2();
    $("#estimate-item-form #tax_id2").select2();

    function applySelect2OnItemTitle() {
        $("#estimate_item_title").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("estimates/get_estimate_item_suggestion"); ?>",
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term // search term
                    };
                },
                results: function (data, page) {
                    return {results: data};
                }
            }
        }).change(function (e) {
            if (e.val === "+") {
                //show simple textbox to input the new item
                $("#estimate_item_title").select2("destroy").val("").focus();
                $("#add_new_item_to_library").val(1); //set the flag to add new item in library
                $('#item_type_field').show();
                $('#item_type').prop('required', true);
                $('#category_id_field').show();
                $('#category_id').prop('required', true);
            } else if (e.val) {
                $('#item_type_field').hide();
                $('#item_type').prop('required', false);
                $('#category_id_field').hide();
                $('#category_id').prop('required', false);
                //get existing item info
                $("#add_new_item_to_library").val(""); //reset the flag to add new item in library
                $.ajax({
                    url: "<?php echo get_uri("estimates/get_estimate_item_info_suggestion"); ?>",
                    data: {item_name: e.val},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {

                            $("#estimate_item_id").val(response.item_info.id);

                            if (!$("#estimate_item_description").val()) {
                                $("#estimate_item_description").val(response.item_info.description);
                            }

                            if (!$("#estimate_unit_type").val()) {
                                $("#estimate_unit_type").val(response.item_info.unit_type);
                            }

                            if (!$("#estimate_item_rate").val()) {
                                $("#estimate_item_rate").val(response.item_info.rate);
                            }
                        }
                    }
                });
            }

        });
    }




</script>