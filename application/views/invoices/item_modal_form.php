<?php echo form_open(get_uri("invoices/save_item"), array("id" => "invoice-item-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <input type="hidden" name="invoice_id" value="<?php echo $invoice_id; ?>" />
    <input type="hidden" id='invoice_item_id' name='invoice_item_id' value="<?php echo $model_info->item_id ;?>" />
    <input type="hidden" id='cost' name='cost' value="<?php echo $model_info->cost ;?>" />
    <input type="hidden" name="add_new_item_to_library" value="" id="add_new_item_to_library" />
    <div class="form-group">
        <div class=" col-md-3"></div>
        <div class="col-md-9" style="margin-bottom: 13px;">
        <?php echo modal_anchor(get_uri("invoices/new_item_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_new_item'), array("class" => "btn btn-default", "title" => lang('add_item'))); ?>  
    </div>  
    <div class="form-group">
       
        <label for="invoice_item_title" class=" col-md-3"><?php echo lang('item'); ?></label>
        
        <div class="col-md-9">
                    
            <?php
            echo form_input(array(
                "id" => "invoice_item_title",
                "name" => "invoice_item_title",
                "value" => $model_info->title,
                "class" => "form-control validate-hidden",
                "placeholder" => lang('select_or_create_new_item'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
            <a id="invoice_item_title_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span>×</span></a>
        </div>
    </div>
    <div class="form-group">
        <label for="invoice_item_description" class="col-md-3"><?php echo lang('description'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "invoice_item_description",
                "name" => "invoice_item_description",
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
        <label for="invoice_item_quantity" class=" col-md-3"><?php echo lang('quantity'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "invoice_item_quantity",
                "name" => "invoice_item_quantity",
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
        <label for="invoice_unit_type" class=" col-md-3"><?php echo lang('unit_type'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "invoice_unit_type",
                "name" => "invoice_unit_type",
                "value" => $model_info->unit_type,
                "class" => "form-control",
                "placeholder" => lang('unit_type') . ' (Ex: hours, pc, etc.)'
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="invoice_item_rate" class=" col-md-3"><?php echo lang('rate'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "invoice_item_rate",
                "name" => "invoice_item_rate",
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
    <?php //var_dump($taxes_dropdown); die(); ?>
    <div class="form-group">
        <label for="tax_id" class=" col-md-3"><?php echo lang('tax'); ?></label>
        <div class="col-md-9">
            <!-- <input type="text" disabled value=" VAT (5%)" class="form-control" > -->
            <?php
            echo form_dropdown("tax_id", $taxes_dropdown, 1, " class='select2 tax-select2' id='tax_id'");
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
        $("#invoice-item-form").appForm({
            onSuccess: function (result) {
                $("#invoice-item-table").appTable({newData: result.data, dataId: result.id});
                $("#invoice-total-section").html(result.invoice_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.invoice_id);
                }
            }
        });

        //show item suggestion dropdown when adding new item
        var isUpdate = "<?php echo $model_info->id; ?>";
        if (!isUpdate) {
            applySelect2OnItemTitle();
        }

        //re-initialize item suggestion dropdown on request
        $("#invoice_item_title_dropdwon_icon").click(function () {
            applySelect2OnItemTitle();
        })

    });

    $("#invoice-item-form #item_type").select2();
    $("#invoice-item-form #category_id").select2();
    $('#item_type_field').hide();
    $('#category_id_field').hide();
    $("#invoice-item-form #tax_id").select2();
    $("#invoice-item-form #tax_id2").select2();

    function applySelect2OnItemTitle() {
        $("#invoice_item_title").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("invoices/get_invoice_item_suggestion"); ?>",
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
                $("#invoice_item_title").select2("destroy").val("").focus();
                $("#add_new_item_to_library").val(1); //set the flag to add new item in library
                $('#item_type_field').show();
                $('#item_type').prop('required', true);
                $('#category_id_field').show();
                $('#category_id').prop('required', true);
            } else if (e.val) {
                get_item_details(e.val);
            }

        });
    }
    $('.invoice_item_title').change(function(e){
        get_item_details(e.val);
    })
    function get_item_details(val){
        //get existing item info
        $("#add_new_item_to_library").val(""); //reset the flag to add new item in library
                $('#item_type_field').hide();
                $('#item_type').prop('required', false);
                $('#category_id_field').hide();
                $('#category_id').prop('required', false);
                console.log(val);
                $.ajax({
                    url: "<?php echo get_uri("invoices/get_invoice_item_info_suggestion"); ?>",
                    data: {item_name: val},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {

                            $("#invoice_item_id").val(response.item_info.id);

                            // if (!$("#invoice_item_description").val()) {
                                $("#invoice_item_description").val(response.item_info.description);
                            // }

                            // if (!$("#invoice_unit_type").val()) {
                                $("#invoice_unit_type").val(response.item_info.unit_type);
                            // }

                            // if (!$("#invoice_item_rate").val()) {
                                $("#invoice_item_rate").val(response.item_info.rate);
                                $("#cost").val(response.item_info.cost);
                            // }
                            // if (!$("#certificate_no").val()) {
                                $("#certificate_no").val(response.item_info.certificate_no);
                            // }
                        }
                    }
                });
    }




</script>