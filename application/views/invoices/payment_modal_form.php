<?php echo form_open(get_uri("invoice_payments/save_payment"), array("id" => "invoice-payment-form", "class" => "general-form", "role" => "form")); ?>
<div id="events-dropzone" class="post-dropzone">

    <div class="modal-body clearfix">
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

        <?php if ($invoice_id) { ?>
            <input type="hidden" name="invoice_id" value="<?php echo $invoice_id; ?>" />
        <?php } else { ?>
        <div class="form-group">
            <label for="invoice_id" class=" col-md-3"><?php echo lang('invoice'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("invoice_id", $invoices_dropdown, "", "class='select2 validate-hidden' id='invoice_id' data-rule-required='true' data-msg-required='" . lang('field_required') . "' ");
                ?>
            </div>
        </div>
        <?php } ?>

        <div class="form-group">
            <label for="invoice_payment_method_id" class=" col-md-3"><?php echo lang('payment_method'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("invoice_payment_method_id", $payment_methods_dropdown, array($model_info->payment_method_id), "class='select2' id='invoice_payment_method_id'");
                ?>
            </div>
        </div>
        <div class="form-group" id="treasury">
            <label for="treasury" class=" col-md-3"><?php echo lang('cash_on_hand'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("treasury", $treasury, array($model_info->treasury), "class='select2 validate-hidden'  data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                ?>
            </div>
        </div>
        <div id="cheque">

            <div class=" form-group">
                <label for="cheque_due_date" class=" col-md-3"><?php echo lang('cheque_due_date'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "cheque_due_date",
                        "name" => "cheque_due_date",
                        "value" => $model_info->cheque_due_date? $model_info->cheque_due_date: get_my_local_time("Y-m-d"),
                        "class" => "form-control",
                        "data-rule-required" => true,
                        "data-msg-required" => lang("field_required")
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                        <label for="cheque_number" class=" col-md-3"><?php echo lang('cheque_number'); ?></label>
                        <div class=" col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "cheque_number",
                                "name" => "cheque_number",
                                "value" => $model_info->cheque_number ? $model_info->cheque_number : "",
                                "class" => "form-control",
                                "placeholder" => lang('cheque_number'),
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required")
                            ));
                            ?>
                        </div>
                    </div>
            <div class="form-group">
                        <label for="cheque_account" class=" col-md-3"><?php echo lang('cheque_account'); ?></label>
                        <div class=" col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "cheque_account",
                                "name" => "cheque_account",
                                "value" => $model_info->cheque_account ? $model_info->cheque_account : "",
                                "class" => "form-control",
                                "placeholder" => lang('cheque_account'),
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required")
                            ));
                            ?>
                        </div>
                    </div>

            <div class="form-group">
                        <label for="title" class=" col-md-3"><?php echo lang('cheque_description'); ?></label>
                        <div class=" col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "cheque_description",
                                "name" => "cheque_description",
                                "value" => $model_info->cheque_description ? $model_info->cheque_description : "",
                                "class" => "form-control",
                                "placeholder" => lang('cheque_description'),
                            ));
                            ?>
                        </div>
                    </div>

        </div>
       
        <div class="form-group"  id="bank">
            <label for="invoice_id" class=" col-md-3"><?php echo lang('Bank'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("bank", $banks_dropdown, array($model_info->bank), "class='select2 validate-hidden' id='bank_name' data-rule-required='true' data-msg-required='" . lang('field_required') . "' ");
                ?>
            </div>
        </div>

        <div class="form-group">
            <label for="invoice_payment_date" class=" col-md-3"><?php echo lang('payment_date'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "invoice_payment_date",
                    "name" => "invoice_payment_date",
                    "value" => $model_info->payment_date,
                    "class" => "form-control",
                    "placeholder" => lang('payment_date'),
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required")
                ));
                ?>
            </div>
        </div>

        <div class="form-group">
            <label for="invoice_payment_amount" class=" col-md-3"><?php echo lang('amount'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "invoice_payment_amount",
                    "name" => "invoice_payment_amount",
                    "value" => $model_info->amount ? to_decimal_format($model_info->amount) : "",
                    "max" => $invoice_id ? $balance_due : 9999,
                    "class" => "form-control",
                    "placeholder" => lang('amount'),
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required"),
                    "min" => 0.01,
                ));
                ?>
            </div>
        </div>

        <div class="form-group">
            <label for="invoice_payment_note" class="col-md-3"><?php echo lang('note'); ?></label>
            <div class=" col-md-9">
                <?php
                echo form_textarea(array(
                    "id" => "invoice_payment_note",
                    "name" => "invoice_payment_note",
                    "value" => $model_info->note ? $model_info->note : "",
                    "class" => "form-control",
                    "placeholder" => lang('description'),
                    "data-rich-text-editor" => true
                ));
                ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-12">
                <?php
                $this->load->view("includes/file_list", array("files" => $model_info->files));
                ?>
            </div>
        </div>
        <?php $this->load->view("includes/dropzone_preview"); ?>
          


        <div class="modal-footer">
            <button class="btn btn-default upload-file-button pull-left btn-sm round" type="button" style="color:#7988a2"><i class="fa fa-camera"></i> <?php echo lang("upload_file"); ?></button>
        
            <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
            <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
        </div>
    </div>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
         // file upload 
         var uploadUrl = "<?php echo get_uri("invoice_payments/upload_file"); ?>";
        var validationUri = "<?php echo get_uri("invoice_payments/validate_events_file"); ?>";
        var dropzone = attachDropzoneWithForm("#events-dropzone", uploadUrl, validationUri);

        $("#invoice-payment-form").appForm({
            onSuccess: function (result) {
                if (typeof RELOAD_VIEW_AFTER_UPDATE !== "undefined" && RELOAD_VIEW_AFTER_UPDATE) {
                    location.reload();
                } else {
                    if ($("#invoice-payment-table").length) {
                        //it's from invoice details view
                        $("#invoice-payment-table").appTable({newData: result.data, dataId: result.id});
                        $("#invoice-total-section").html(result.invoice_total_view);
                        if (typeof updateInvoiceStatusBar == 'function') {
                            updateInvoiceStatusBar(result.invoice_id);
                        }
                    } else {
                        //it's from invoices list view
                        //update table data
                        $("#" + $(".dataTable:visible").attr("id")).appTable({reload: true});
                    }
                }
            }
        });
        
        $("#invoice-payment-form .select2").select2();

        setDatePicker("#invoice_payment_date");

        setDatePicker("#cheque_due_date");

        $("#cheque").hide();
        $("#bank").hide();
        $("#treasury").hide();
        setTimeout(function() {
            $('#invoice_payment_method_id').change();
        }, 1000)
       
        //load all 
        $("#invoice_payment_method_id").on("change", function () {
            console.log($(this).val());
            if ($(this).val() == "5") {
                $("#cheque").show();
                $("#bank").show();
                $("#treasury").hide();
                $('#treasury select').attr('disabled','disabled');
                $('#bank select').removeAttr('disabled');
            } else {
                $("#bank").show();
                $("#cheque").hide();
                $("#treasury").hide();
                $('#treasury select').attr('disabled','disabled');
                $('#bank select').removeAttr('disabled');
            }
            if ($(this).val() == "1") {
                $("#treasury").show();
                 $("#cheque").hide();
                 $("#bank").hide();
                 $('#treasury select').removeAttr('disabled');
                $('#bank select').attr('disabled','disabled');
            }
        });

    });
</script>