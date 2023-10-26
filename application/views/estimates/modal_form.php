<?php echo form_open(get_uri("estimates/save"), array("id" => "estimate-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <input type="hidden" name="estimate_request_id" value="<?php echo $estimate_request_id; ?>" />

    <?php if ($is_clone) { ?>
        <input type="hidden" name="is_clone" value="1" />
        <input type="hidden" name="discount_amount" value="<?php echo $model_info->discount_amount; ?>" />
        <input type="hidden" name="discount_amount_type" value="<?php echo $model_info->discount_amount_type; ?>" />
        <input type="hidden" name="discount_type" value="<?php echo $model_info->discount_type; ?>" />
    <?php } ?>

    <div class="form-group">
        <label for="estimate_date" class=" col-md-3"><?php echo lang('estimate_date'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "estimate_date",
                "name" => "estimate_date",
                "value" => $model_info->estimate_date,
                "class" => "form-control",
                "placeholder" => lang('estimate_date'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="valid_until" class=" col-md-3"><?php echo lang('valid_until'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "valid_until",
                "name" => "valid_until",
                "value" => $model_info->valid_until,
                "class" => "form-control",
                "placeholder" => lang('valid_until'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
                "data-rule-greaterThanOrEqual" => "#estimate_date",
                "data-msg-greaterThanOrEqual" => lang("end_date_must_be_equal_or_greater_than_start_date")
            ));
            ?>
        </div>
    </div>
    <!-- <?php if ($client_id) { ?>
        <input type="hidden" name="estimate_client_id" value="<?php echo $client_id; ?>" />
    <?php } else { ?>
        <div class="form-group">
            <label for="estimate_client_id" class=" col-md-3"><?php echo lang('client'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("estimate_client_id", $clients_dropdown, array($model_info->client_id), "class='select2 validate-hidden' id='estimate_client_id' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                ?>
            </div>
        </div>
    <?php } ?> -->

    <?php if ($client_id) { ?>
        <input type="hidden" name="estimate_client_id" value="<?php echo $client_id; ?>" />
    <?php } else { ?>
        <div class="form-group">
            <label for="estimate_client_id" class=" col-md-3"><?php echo lang('client'); ?></label>
            <div class="col-md-6">
                <?php
                echo form_dropdown("estimate_client_id", $clients_dropdown, array($model_info->client_id), "class='select2 validate-hidden' id='estimate_client_id' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                ?>
            </div>
            <?php if (!$model_info->client_id) { ?>
                <div class="col-md-3">
                    <a class="btn btn-primary" id="add_lead"><?= lang("new_lead")?></a>
                </div>
            <?php } ?>
            </div>  
    <?php } ?>


    <?php if (!$model_info->client_id) { ?>
    <div id="new_lead" style="border: 0px solid; margin: 0px 0px 20px; padding: 10px">
        
    <div class="form-group">   
    <a class="btn btn-danger" id="close_lead" style="float: right;    margin-right: 10px;
    margin-bottom: 10px;"><?= lang("close")?></a> </div>

        <!-- name -->
        <div class="form-group">
            <label for="company_name" class="col-md-3"><?php echo lang('company_name'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "company_name",
                    "name" => "company_name",
                    "value" => "",
                    "class" => "form-control lead_data",
                    "placeholder" => lang('company_name'),
                    "autofocus" => true,
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required"),
                ));
                ?>
            </div>
        </div>

        <!-- phone -->
        <div class="form-group">
            <label for="phone" class="col-md-3"><?php echo lang('phone'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "phone",
                    "name" => "phone",
                    "value" => "",
                    "class" => "form-control lead_data",
                    "placeholder" => lang('phone'),
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required"),
                ));
                ?>
            </div>
        </div>

        <!-- address -->
        <div class="form-group">
            <label for="address" class="col-md-3"><?php echo lang('address'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "address",
                    "name" => "address",
                    "value" => "",
                    "class" => "form-control lead_data",
                    "placeholder" => lang('address'),
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required"),
                ));
                ?>
            </div>
        </div>
       
    </div>
    <?php } ?>

    <!-- <div class="form-group">
        <label for="tax_id" class=" col-md-3"><?php echo lang('tax'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("tax_id", $taxes_dropdown, array($model_info->tax_id), "class='select2 tax-select2'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="tax_id" class=" col-md-3"><?php echo lang('second_tax'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("tax_id2", $taxes_dropdown, array($model_info->tax_id2), "class='select2 tax-select2'");
            ?>
        </div>
    </div> -->
    <div class="form-group">
        <label for="estimate_note" class=" col-md-3"><?php echo lang('note'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "estimate_note",
                "name" => "estimate_note",
                "value" => $model_info->note ? $model_info->note : "",
                "class" => "form-control",
                "placeholder" => lang('note'),
                "data-rich-text-editor" => true
            ));
            ?>
        </div>
    </div>

    <?php $this->load->view("custom_fields/form/prepare_context_fields", array("custom_fields" => $custom_fields, "label_column" => "col-md-3", "field_column" => " col-md-9")); ?> 

    <?php if ($is_clone) { ?>
        <div class="form-group">
            <label for="copy_items"class=" col-md-12">
                <?php
                echo form_checkbox("copy_items", "1", true, "id='copy_items' disabled='disabled' class='pull-left mr15'");
                ?>    
                <?php echo lang('copy_items'); ?>
            </label>
        </div>
        <div class="form-group">
            <label for="copy_discount"class=" col-md-12">
                <?php
                echo form_checkbox("copy_discount", "1", true, "id='copy_discount' disabled='disabled' class='pull-left mr15'");
                ?>    
                <?php echo lang('copy_discount'); ?>
            </label>
        </div>
    <?php } ?> 

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#estimate-form").appForm({
            onSuccess: function (result) {
                if (typeof RELOAD_VIEW_AFTER_UPDATE !== "undefined" && RELOAD_VIEW_AFTER_UPDATE) {
                    location.reload();
                } else {
                    window.location = "<?php echo site_url('estimates/view'); ?>/" + result.id;
                }
            }
        });
        $("#estimate-form .tax-select2").select2();
        $("#estimate_client_id").select2();

        setDatePicker("#estimate_date, #valid_until");

        // lead functions
            $("#new_lead").hide();
            $("#add_lead").click(function (){
                 $("#new_lead").show();
                 $("#estimate_client_id").attr("data-rule-required", false);
                 $("#estimate_client_id").attr("disabled", true);
                 
            });

            $("#close_lead").click(function (){
                $("#new_lead").hide();
                $("#estimate_client_id").attr("data-rule-required", true);
                $("#estimate_client_id").attr("disabled", false);
            });

            $("#estimate_client_id").change(function () {
                var client = $("#estimate_client_id").val();
                if (client) {
                    $("#add_lead").hide();
                    $("#new_lead .lead_data").attr("data-rule-required", false);
                } else {
                    $("#add_lead").show();
                }

                //alert ( $("#estimate_client_id").val());
            });


    });
</script>