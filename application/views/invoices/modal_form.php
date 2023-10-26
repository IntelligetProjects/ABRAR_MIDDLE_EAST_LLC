<?php echo form_open(get_uri("invoices/save"), array("id" => "invoice-form", "class" => "general-form", "role" => "form")); ?>
<div id="events-dropzone" class="post-dropzone">
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <?php if ($is_clone) { ?>
        <input type="hidden" name="is_clone" value="1" />
        <input type="hidden" name="discount_amount" value="<?php echo $model_info->discount_amount; ?>" />
        <input type="hidden" name="discount_amount_type" value="<?php echo $model_info->discount_amount_type; ?>" />
        <input type="hidden" name="discount_type" value="<?php echo $model_info->discount_type; ?>" />
    <?php } ?>

    <div class="form-group">
        <label for="invoice_bill_date" class=" col-md-3"><?php echo lang('bill_date'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "invoice_bill_date",
                "name" => "invoice_bill_date",
                "value" => $model_info->bill_date ? $model_info->bill_date : get_my_local_time("Y-m-d"),
                "class" => "form-control recurring_element",
                "placeholder" => lang('bill_date'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="invoice_due_date" class=" col-md-3"><?php echo lang('due_date'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "invoice_due_date",
                "name" => "invoice_due_date",
                "value" => $model_info->due_date,
                "class" => "form-control",
                "placeholder" => lang('due_date'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
                "data-rule-greaterThanOrEqual" => "#invoice_bill_date",
                "data-msg-greaterThanOrEqual" => lang("end_date_must_be_equal_or_greater_than_start_date")
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
            <label for="sale_item" class=" col-md-3"><?php echo lang('sale_item'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("sale_item", $sale_item_dropdown, $model_info->sale_item, "class=' form-control select2 validate-hidden' id='sale_item' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                ?>
            </div>
    </div>
    <?php if ($client_id && !$project_id) { ?>
        <input type="hidden" name="invoice_client_id" value="<?php echo $client_id; ?>" />
    <?php } else { ?>
        <div class="form-group">
            <label for="invoice_client_id" class=" col-md-3"><?php echo lang('client'); ?></label>
            <div class="col-md-7">
                <?php
                echo form_dropdown("invoice_client_id", $clients_dropdown, array($model_info->client_id), "class='select2 validate-hidden' id='invoice_client_id' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                ?>
            </div>
            <?php if (!$model_info->client_id) { ?>
                <div class="col-md-2">
                    <a class="btn btn-primary" id="add_lead" style="display:none"><?= lang("new")?></a>
                </div>
            <?php } ?>
        </div>
    <?php } ?>

    <?php if (!$model_info->client_id) { ?>
        <div id="new_lead" style="border: 0px solid; margin: 0px 0px 20px; padding: 10px">
            
            <div class="form-group">   
            

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

            <a class="btn btn-danger" id="close_lead" style="float: right;    margin-right: 10px;
            margin-bottom: 10px;"><?= lang("close")?></a> </div>
           
        </div>
    <?php } ?>

    <?php if ($project_id) { ?>
        <input type="hidden" name="invoice_project_id" value="<?php echo $project_id; ?>" />
    <?php } else { ?>
        <div class="form-group">
            <label for="invoice_project_id" class=" col-md-3"><?php echo lang('project'); ?></label>
            <div class="col-md-9" id="invoice-porject-dropdown-section">
                <?php
                echo form_input(array(
                    "id" => "invoice_project_id",
                    "name" => "invoice_project_id",
                    "value" => $model_info->project_id,
                    "class" => "form-control",
                    "placeholder" => lang('project')
                ));
                ?>
            </div>
        </div>
    <?php } ?>

   
    <div class="form-group">
        <label for="invoice_note" class=" col-md-3"><?php echo lang('note'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "invoice_note",
                "name" => "invoice_note",
                "value" => $model_info->note ? $model_info->note : "",
                "class" => "form-control",
                "placeholder" => lang('note'),
                "data-rich-text-editor" => true
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="invoice_labels" class=" col-md-3"><?php echo lang('labels'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "invoice_labels",
                "name" => "labels",
                "value" => $model_info->labels,
                "class" => "form-control",
                "placeholder" => lang('labels')
            ));
            ?>
        </div>
    </div>

    

    <?php if ($estimate_id) { ?>
        <div class="form-group">
            <label for="estimate_id_checkbox" class=" col-md-12">
                <input type="hidden" name="copy_items_from_estimate" value="<?php echo $estimate_id; ?>" />
                <?php
                echo form_checkbox("estimate_id_checkbox", $estimate_id, true, " class='pull-left' disabled='disabled'");
                ?>    
                <span class="pull-left ml15"> <?php echo lang('include_all_items_of_this_estimate'); ?> </span>
            </label>
        </div>
    <?php } ?>

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
<!-- </div> -->

<div class="form-group">
            <div class="col-md-12">
                <?php
                $this->load->view("includes/file_list", array("files" => $model_info->files));
                ?>
            </div>
        </div>
        <?php $this->load->view("custom_fields/form/prepare_context_fields", array("custom_fields" => $custom_fields, "label_column" => "col-md-3", "field_column" => " col-md-9")); ?> 

        <?php $this->load->view("includes/dropzone_preview"); ?>    
</div>
<div class="modal-footer">
<button class="btn btn-default upload-file-button pull-left btn-sm round" type="button" style="color:#7988a2"><i class="fa fa-camera"></i> <?php echo lang("upload_file"); ?></button>
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        if ("<?php echo $estimate_id; ?>") {
            RELOAD_VIEW_AFTER_UPDATE = false; //go to invoice page
        }

        // file upload 
        var uploadUrl = "<?php echo get_uri("invoices/upload_file"); ?>";
        var validationUri = "<?php echo get_uri("invoices/validate_events_file"); ?>";
        var dropzone = attachDropzoneWithForm("#events-dropzone", uploadUrl, validationUri);

        $("#invoice-form").appForm({
            onSuccess: function (result) {
                if (typeof RELOAD_VIEW_AFTER_UPDATE !== "undefined" && RELOAD_VIEW_AFTER_UPDATE) {
                    location.reload();
                } else {
                    window.location = "<?php echo site_url('invoices/view'); ?>/" + result.id;
                }
            },
            onAjaxSuccess: function (result) {
                if (!result.success && result.next_recurring_date_error) {
                    $("#next_recurring_date").val(result.next_recurring_date_value);
                    $("#next_recurring_date_container").removeClass("hide");

                    $("#invoice-form").data("validator").showErrors({
                        "next_recurring_date": result.next_recurring_date_error
                    });
                }
            }
        });
        $("#invoice-form .tax-select2").select2();
        $("#repeat_type").select2();
        $("#sale_item").select2();

        // lead functions
            $("#new_lead").hide();
            $("#add_lead").click(function (){
                 $("#new_lead").show();
                 $("#invoice_client_id").attr("data-rule-required", false);
                 $("#invoice_client_id").attr("disabled", true);
                 
            });

            $("#close_lead").click(function (){
                $("#new_lead").hide();
                $("#invoice_client_id").attr("data-rule-required", true);
                $("#invoice_client_id").attr("disabled", false);
            });

            $("#invoice_client_id").change(function () {
                var client = $("#invoice_client_id").val();
                if (client) {
                    $("#add_lead").hide();
                    $("#new_lead .lead_data").attr("data-rule-required", false);
                } else {
                    $("#add_lead").show();
                }

                //alert ( $("#invoice_client_id").val());
            });

        $("#invoice_labels").select2({
            tags: <?php echo json_encode($label_suggestions); ?>
        });

        setDatePicker("#invoice_bill_date, #invoice_due_date");

        //load all projects of selected client
        $("#invoice_client_id").select2().on("change", function () {
            var client_id = $(this).val();
            if ($(this).val()) {
                $('#invoice_project_id').select2("destroy");
                $("#invoice_project_id").hide();
                appLoader.show({container: "#invoice-porject-dropdown-section"});
                $.ajax({
                    url: "<?php echo get_uri("invoices/get_project_suggestion") ?>" + "/" + client_id,
                    dataType: "json",
                    success: function (result) {
                        $("#invoice_project_id").show().val("");
                        $('#invoice_project_id').select2({data: result});
                        appLoader.hide();
                    }
                });
            }
        });

        $('#invoice_project_id').select2({data: <?php echo json_encode($projects_suggestion); ?>});

        if ("<?php echo $project_id; ?>") {
            $("#invoice_client_id").select2("readonly", true);
        }

        //show/hide recurring fields
        $("#invoice_recurring").click(function () {
            if ($(this).is(":checked")) {
                $("#recurring_fields").removeClass("hide");
            } else {
                $("#recurring_fields").addClass("hide");
            }
        });

        setDatePicker("#next_recurring_date", {
            startDate: moment().add(1, 'days').format("YYYY-MM-DD") //set min date = tomorrow
        });


        $('[data-toggle="tooltip"]').tooltip();

        var defaultDue = "<?php echo get_setting('default_due_date_after_billing_date'); ?>";
        var id = "<?php echo $model_info->id; ?>";

        //disable this operation in edit mode
        if (defaultDue && !id) {
            //for auto fill the due date based on bill date
            setDefaultDueDate = function () {
                var dateFormat = getJsDateFormat().toUpperCase();

                var billDate = $('#invoice_bill_date').val();
                var dueDate = moment(billDate, dateFormat).add(defaultDue, 'days').format(dateFormat);
                $("#invoice_due_date").val(dueDate);

            };

            $("#invoice_bill_date").change(function () {
                setDefaultDueDate();
            });

            setDefaultDueDate();
        }

    });
</script>