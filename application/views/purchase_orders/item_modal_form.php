<?php echo form_open(get_uri("purchase_orders/save_item"), array("id" => "purchase_order-item-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <input type="hidden" name="purchase_order_id" value="<?php echo $purchase_order_id; ?>" />
    <input type="hidden" id='purchase_order_item_id' name='purchase_order_item_id' value="<?php echo $model_info->item_id ;?>" />
    <input type="hidden" name="add_new_item_to_library" value="" id="add_new_item_to_library" />
    <div class="form-group">
        <label for="purchase_order_item_title" class=" col-md-3"><?php echo lang('item'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "purchase_order_item_title",
                "name" => "purchase_order_item_title",
                "value" => $model_info->title,
                "class" => "form-control validate-hidden",
                "placeholder" => lang('select_or_create_new_item'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
            <a id="purchase_order_item_title_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span>×</span></a>
        </div>
    </div>
    <div class="form-group">
        <label for="purchase_order_item_description" class="col-md-3"><?php echo lang('description'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "purchase_order_item_description",
                "name" => "purchase_order_item_description",
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
        <label for="purchase_order_item_quantity" class=" col-md-3"><?php echo lang('quantity'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "purchase_order_item_quantity",
                "name" => "purchase_order_item_quantity",
                "value" => $model_info->quantity ? to_decimal_format($model_info->quantity) : "",
                "class" => "form-control",
                "placeholder" => lang('quantity'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="purchase_order_unit_type" class=" col-md-3"><?php echo lang('unit_type'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "purchase_order_unit_type",
                "name" => "purchase_order_unit_type",
                "value" => $model_info->unit_type,
                "class" => "form-control",
                "placeholder" => lang('unit_type') . ' (Ex: hours, pc, etc.)'
            ));
            ?>
        </div>
    </div>
    <div id= "purchase_order_item_rate_field" class="form-group">
        <label for="purchase_order_item_rate" class=" col-md-3"><?php echo lang('rate'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "purchase_order_item_rate",
                "name" => "purchase_order_item_rate",
                "value" => "",
                "class" => "form-control",
                "placeholder" => lang('rate'),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="purchase_order_item_cost" class=" col-md-3"><?php echo lang('cost_price'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "purchase_order_item_cost",
                "name" => "purchase_order_item_cost",
                "value" => $model_info->rate ? to_decimal_format($model_info->rate) : "",
                "class" => "form-control",
                "placeholder" => lang('cost_price'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
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
        $("#purchase_order-item-form").appForm({
            onSuccess: function (result) {
                $("#purchase_order-item-table").appTable({newData: result.data, dataId: result.id});
                $("#purchase_order-total-section").html(result.purchase_order_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.purchase_order_id);
                }
            }
        });

        //show item suggestion dropdown when adding new item
        var isUpdate = "<?php echo $model_info->id; ?>";
        if (!isUpdate) {
            applySelect2OnItemTitle();
        }

        //re-initialize item suggestion dropdown on request
        $("#purchase_order_item_title_dropdwon_icon").click(function () {
            applySelect2OnItemTitle();
        })

    });

    $("#purchase_order-item-form #item_type").select2();
    $("#purchase_order-item-form #category_id").select2();
    $("#purchase_order-item-form #tax_id").select2();
    $("#purchase_order-item-form #tax_id2").select2();
    $('#item_type_field').hide();
    $('#category_id_field').hide();

    $('#purchase_order_item_rate_field').hide();

    function applySelect2OnItemTitle() {
        $("#purchase_order_item_title").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("purchase_orders/get_purchase_order_item_suggestion"); ?>",
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
                $("#purchase_order_item_title").select2("destroy").val("").focus();
                $("#add_new_item_to_library").val(1); //set the flag to add new item in library
                $('#item_type_field').show();
                $('#item_type').prop('required', true);
                $('#category_id_field').show();
                $('#category_id').prop('required', true);
                $('#purchase_order_item_rate_field').show();
                $('#purchase_order_item_rate').prop('required', true);
            } else if (e.val) {
                //get existing item info
                $("#add_new_item_to_library").val(""); //reset the flag to add new item in library
                $('#item_type_field').hide();
                $('#item_type').prop('required', false);
                $('#category_id_field').hide();
                $('#category_id').prop('required', false);
                $('#purchase_order_item_rate_field').hide();
                $('#purchase_order_item_rate').prop('required', false);
                $.ajax({
                    url: "<?php echo get_uri("purchase_orders/get_purchase_order_item_info_suggestion"); ?>",
                    data: {item_name: e.val},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {

                            $("#purchase_order_item_id").val(response.item_info.id);

                            if (!$("#purchase_order_item_description").val()) {
                                $("#purchase_order_item_description").val(response.item_info.description);
                            }

                            if (!$("#purchase_order_unit_type").val()) {
                                $("#purchase_order_unit_type").val(response.item_info.unit_type);
                            }

                            if (!$("#purchase_order_item_cost").val()) {
                                $("#purchase_order_item_cost").val(response.item_info.cost);
                            }
                        }
                    }
                });
            }

        });
    }




</script>