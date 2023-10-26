<?php echo form_open(get_uri("material_request/save"), array("id" => "purchase_order-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="form-group">
        <label for="material_request_date" class=" col-md-3"><?php echo lang('date'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "material_request_date",
                "name" => "material_request_date",
                "value" => $model_info->material_request_date ? $model_info->material_request_date : get_my_local_time("Y-m-d"),
                "class" => "form-control",
                "placeholder" => lang('material_request_date'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="purchase_order_supplier_id" class=" col-md-3"><?php echo lang('supplier'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_dropdown("purchase_order_supplier_id", $suppliers_dropdown, array($model_info->supplier_id), "class='select2 validate-hidden' id='purchase_order_supplier_id' data-rule-required='false', data-msg-required='" . lang('field_required') . "'");
            ?>
        </div>
    </div>

    <?php if ($project_id) { ?>
        <input type="hidden" name="purchase_order_project_id" value="<?php echo $project_id; ?>" />
    <?php } else { ?>
        <div class="form-group">
            <label for="purchase_order_project_id" class=" col-md-3"><?php echo lang('project'); ?></label>
            <div class=" col-md-9">
                <?php
                echo form_dropdown("purchase_order_project_id", $projects_dropdown, array($model_info->project_id), "class='select2 validate-hidden' id='purchase_order_project_id'");
                ?>
            </div>
        </div>
    <?php } ?>
 
    <div class="form-group">
        <label for="purchase_order_note" class=" col-md-3"><?php echo lang('note'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "purchase_order_note",
                "name" => "purchase_order_note",
                "value" => $model_info->note ? $model_info->note : "",
                "class" => "form-control",
                "placeholder" => lang('note'),
                "data-rich-text-editor" => true
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="purchase_order_labels" class=" col-md-3"><?php echo lang('labels'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "purchase_order_labels",
                "name" => "labels",
                "value" => $model_info->labels,
                "class" => "form-control",
                "placeholder" => lang('labels')
            ));
            ?>
        </div>
    </div>
    <!-- FOR VAT REPORT  -->
    <?php  
    //TODO: for test accout only remove IF statement if changes in database ready for all clients
    if ($this->login_user->is_admin && ($this->db->dbprefix === 'Test_teamway' || $this->db->dbprefix === 'Tarteeb' ))
        { ?>
    <div class="form-group">
        <label for="invoice_ref_number" class=" col-md-3"><?php echo lang('invoice_reference_number'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "invoice_ref_number",
                "name" => "invoice_ref_number",
                "value" => $model_info->invoice_ref_number,
                "class" => "form-control",
                "placeholder" => lang('invoice_number')
            ));
            ?>
        </div>
    </div>
    
    <div class="form-group">
        <label for="taxable_value_in_FC" class=" col-md-3"><?php echo lang('taxable_value_in_FC'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "taxable_value_in_FC",
                "name" => "taxable_value_in_FC",
                "value" => $model_info->taxable_value_in_FC,
                "class" => "form-control",
                "placeholder" => lang('taxable_value_in_FC')
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="exchange_rate_at_PO_time" class=" col-md-3"><?php echo lang('exchange_rate_at_PO_time'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "exchange_rate_at_PO_time",
                "name" => "exchange_rate_at_PO_time",
                "value" => $model_info->exchange_rate_at_PO_time,
                "class" => "form-control",
                "placeholder" => lang('exchange_rate_at_PO_time').' %'
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="purchase_type" class=" col-md-3"><?php echo lang('purchase_type'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_dropdown("purchase_type", array(""=>"-","domestic","import"), array($model_info->type =="domestic"?0:1), "class='select2 validate-hidden' id='purchase_type' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="asset_type" class=" col-md-3"><?php echo lang('asset_type'); ?></label>
        <div class=" col-md-9">
            <?php
            if($model_info->asset_type === "direct")
            $selected = 0;
            else if($model_info->asset_type === "indirect")
            $selected = 1;
            else 
            $selected ='';
            echo form_dropdown("asset_type", array(""=>"-","direct","indirect"), array($selected), "class='select2 validate-hidden' id='asset_type' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
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
        $("#purchase_order-form").appForm({
            onSuccess: function (result) {
                if (typeof RELOAD_VIEW_AFTER_UPDATE !== "undefined" && RELOAD_VIEW_AFTER_UPDATE) {
                    location.reload();
                } else {
                    window.location = "<?php echo site_url('material_request/view'); ?>/" + result.id;
                }
            },
        });

        $("#purchase_order_labels").select2({
            tags: <?php echo json_encode($label_suggestions); ?>
        });

        $("#purchase_order_supplier_id").select2();
        $("#purchase_order_project_id").select2();

        setDatePicker("#material_request_date");

    });
</script>