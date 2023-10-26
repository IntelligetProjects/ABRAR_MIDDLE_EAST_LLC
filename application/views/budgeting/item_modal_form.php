<style>
    .tab-content{
        margin-top: 25px;
    }
    .readonly{
        background: #e0e1e2 !important;
        cursor: not-allowed;
    }
    #invoice-total-section, #estimate-total-section {
    display: none;
}
</style>

<?php echo form_open(get_uri("budgeting/save_item"), array("id" => "estimate-item-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <input type="hidden" name="estimate_id" value="<?php echo $estimate_id; ?>" />
    <input type="hidden" id='estimate_item_id' name="estimate_item_id" value="<?php echo $model_info->item_id; ?>" />
    <input type="hidden" name="add_new_item_to_library" value="" id="add_new_item_to_library" />
    <input type="hidden"  value="" id="rate" />
   
    <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#home"><?php echo lang('project'); ?></a></li>
    <li><a data-toggle="tab" href="#split"><?php echo lang('split_ups'); ?></a></li>
    <li id="ctc"><a data-toggle="tab" href="#cost"><?php echo lang('actual_cost_to_complete'); ?></a></li>
    <li id="ctc2"><a data-toggle="tab" href="#quotation"><?php echo lang('estimation'); ?></a></li>
    
   
  </ul>
  <div class="tab-content">
    <div id="home" class="tab-pane fade in active">
    <div class="form-group">
        <label for="estimate_item_title" class=" col-md-3"><?php echo lang('project_name'); ?></label>
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
    <!-- <div class="form-group">
        <label for="item_price" class=" col-md-3"><?php echo lang('item_price'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "item_price",
                "name" => "item_price",
                "value" => $model_info->item_price ? to_decimal_format($model_info->item_price) : "",
                "class" => "form-control",
                "placeholder" => lang('item_price') 
            ));
            ?>
        </div>
    </div> -->
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

   
    <!-- <div class="form-group">
        <label for="tax_id" class=" col-md-3"><?php echo lang('second_tax'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("tax_id2", $taxes_dropdown, array($model_info->tax_id2), "class='select2 tax-select2' id='tax_id2'");
            ?>
        </div>
    </div> -->
    </div>
    <div id="quotation" class="tab-pane fade">
    <!-- <div id="quotation_uom" class="form-group">
            <label for="quotation_uom" class=" col-md-3"><?php echo lang('estimation_uom'); ?></label>
            <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "quotation_uom_s",
                "name" => "quotation_uom",
                "value" => $model_info->quotation_uom ? $model_info->quotation_selling_rate: "",
                "class" => "form-control",
                "placeholder" => lang('estimation_uom') 
            ));
            ?>
            </div>
    </div>
    <div class="form-group">
        <label for="quotation_quantity" class=" col-md-3"><?php echo lang('quantity'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "quotation_quantity",
                "name" => "quotation_quantity",
                "value" => $model_info->quotation_quantity ? to_decimal_format($model_info->quotation_quantity) : "",
                "class" => "form-control",
                "placeholder" => lang('quantity'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
                "min" => 1,
            ));
            ?>
        </div>
    </div> -->
    <div class="form-group">
        <label for="quotation_selling_rate" class=" col-md-3"><?php echo lang('estimation_price (OMR)'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "quotation_selling_rate",
                "name" => "quotation_selling_rate",
                "value" => $model_info->quotation_selling_rate ? to_decimal_format($model_info->quotation_selling_rate) : "",
                "class" => "form-control",
                "placeholder" => lang('estimation_price') 
            ));
            $selling_rate=to_decimal_format($model_info->quotation_selling_rate);
            ?>
        </div>
    </div>
    <!-- <div class="form-group">
        <label for="quotation_total" class=" col-md-3"><?php echo lang('total_amount'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "quotation_total",
                "name" => "quotation_total",
                "readonly" => "readonly",
                "value" => $model_info->quotation_total ? to_decimal_format($model_info->quotation_total) : "",
                "class" => "form-control readonly",
                "placeholder" => lang('total_amount'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div> -->
    </div>
<!-- Cost to Complete Tab  -->
<div id="cost" class="tab-pane fade">
    <div id="cost_uom" class="form-group">
            <label for="cost_uom" class=" col-md-3"><?php echo lang('actual_uom'); ?></label>
            <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "cost_uom_s",
                "name" => "cost_uom",
                "value" => $model_info->cost_uom ? $model_info->cost_uom: "",
                "class" => "form-control",
                "placeholder" => lang('actual_uom') 
            ));
            ?>
            </div>
    </div>
    <div class="form-group">
        <label for="cost_quantity" class=" col-md-3"><?php echo lang('quantity'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "cost_quantity",
                "name" => "cost_quantity",
                "value" => $model_info->cost_quantity ? to_decimal_format($model_info->cost_quantity) : "",
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
        <label for="cost_rate" class=" col-md-3"><?php echo lang('actual_cost_to_complete'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "cost_rate",
                "name" => "cost_rate",
                "value" => $model_info->cost_rate ? to_decimal_format($model_info->cost_rate) : "",
                "class" => "form-control",
                "placeholder" => lang('rate') 
            ));
            ?>
        </div>
    </div>
    <!-- <div class="form-group">
        <label for="cost_total" class=" col-md-3"><?php echo lang('total_amount'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "cost_total",
                "name" => "cost_total",
                "readonly" => "readonly",
                "value" => $model_info->cost_total ? to_decimal_format($model_info->cost_total) : "",
                "class" => "form-control readonly",
                "placeholder" => lang('total_amount'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div> -->

    </div>

<!-- Split Tab  -->
<div id="split" class="tab-pane fade">

    <div class="form-group">
        <label for="material_cost" class=" col-md-3"><?php echo lang('material_cost'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "material_cost",
                "name" => "material_cost",
                "type"=>"number",
                "value" => $model_info->material_cost ? to_decimal_format($model_info->material_cost) : "0",
                "class" => "form-control",
                "placeholder" => lang('material_cost'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
                "min" => 1,
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="labour_cost" class=" col-md-3"><?php echo lang('labour_cost'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "labour_cost",
                "name" => "labour_cost",
                "type"=>"number",
                "value" => $model_info->labour_cost ? to_decimal_format($model_info->labour_cost) : "0",
                "class" => "form-control",
                "placeholder" => lang('rate') 
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="machinery" class=" col-md-3"><?php echo lang('machinery'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "machinery",
                "name" => "machinery",
                "type"=>"number",
                "value" => $model_info->machinery ? to_decimal_format($model_info->machinery) : "0",
                "class" => "form-control",
                "placeholder" => lang('machinery'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="others" class=" col-md-3"><?php echo lang('others'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "others",
                "name" => "others",
                "type"=>"number",
                "value" => $model_info->others ? to_decimal_format($model_info->others) : "0",
                "class" => "form-control",
                "placeholder" => lang('others'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
   
    </div>
    <!-- end tab  -->
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $('#ctc').click(function(){ 
            var sum= parseFloat($('#material_cost').val())+ parseFloat($('#labour_cost').val())+ parseFloat($('#machinery').val())+ parseFloat($('#others').val());
            $('#cost_rate').val(sum);
         })
         $('#ctc2').click(function(){ 
            var selling_rate= parseFloat($("#cost_rate").val()?$("#cost_rate").val():0);
            var profit=parseFloat(localStorage.getItem('profit'));
            console.log(profit);
            var tot= selling_rate * profit /100 + selling_rate;
            $('#quotation_selling_rate').val(round(tot));
        })
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
    // $("#estimate-item-form #quotation_uom_s").select2();
    $("#estimate-item-form #category_id").select2();
    $('#item_type_field').hide();
    $('#category_id_field').hide();
    $("#estimate-item-form #tax_id").select2();
    $("#estimate-item-form #tax_id2").select2();

    function applySelect2OnItemTitle() {
        $("#estimate_item_title").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("budgeting/get_estimate_item_suggestion"); ?>",
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
                    url: "<?php echo get_uri("budgeting/get_estimate_item_info_suggestion"); ?>",
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

                            if (!$("#quotation_uom_s").val()) {
                                $("#quotation_uom_s").val(response.item_info.unit_type);
                                console.log(response.item_info.unit_type);
                                // $("#quotation_uom_s").val(response.item_info.unit_type);
                                // $('#quotation_uom_s option[value='+response.item_info.unit_type+']').attr('selected','selected');
                            }
                            if (!$("#cost_uom_s").val()) {
                                $("#cost_uom_s").val(response.item_info.unit_type);
                                // $("#cost_uom_s").val(response.item_info.unit_type);
                            }

                            if (!$("#quotation_selling_rate").val()) {
                                // $("#quotation_selling_rate").val(response.item_info.rate);
                            }
                            if (!$("#cost_rate").val()) {
                                // $("#cost_rate").val(response.item_info.rate);
                               
                                // rate=response.item_info.rate;
                            }
                            $("#item_price").val(response.item_info.rate);
                                console.log(response.item_info.rate);
                        }
                    }
                });
            }

        });
    }




</script>