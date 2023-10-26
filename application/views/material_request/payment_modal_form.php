<?php echo form_open(get_uri("purchase_order_payments/save_payment"), array("id" => "purchase_order-payment-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <?php if ($purchase_order_id) { ?>
        <input type="hidden" name="purchase_order_id" value="<?php echo $purchase_order_id; ?>" />
    <?php } else { ?>
        <div class="form-group">
            <label for="purchase_order_id" class=" col-md-3"><?php echo lang('purchase_order'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("purchase_order_id", $purchase_orders_dropdown, "", "class='select2 validate-hidden' id='purchase_order_id' data-rule-required='true' data-msg-required='" . lang('field_required') . "' ");
                ?>
            </div>
        </div>
    <?php } ?>

    <div class="form-group">
        <label for="purchase_order_payment_method_id" class=" col-md-3"><?php echo lang('payment_method'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("purchase_order_payment_method_id", $payment_methods_dropdown, array($model_info->payment_method_id), "class='select2' id='purchase_order_payment_method_id'");
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
    <div class="form-group">
        <label for="purchase_order_payment_date" class=" col-md-3"><?php echo lang('payment_date'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "purchase_order_payment_date",
                "name" => "purchase_order_payment_date",
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
        <label for="purchase_order_payment_amount" class=" col-md-3"><?php echo lang('amount'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "purchase_order_payment_amount",
                "name" => "purchase_order_payment_amount",
                "value" => $model_info->amount ? to_decimal_format($model_info->amount) : "",
                "max" => $purchase_order_id ? $balance_due : 9999,
                "class" => "form-control",
                "placeholder" => lang('amount'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="purchase_order_payment_note" class="col-md-3"><?php echo lang('note'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "purchase_order_payment_note",
                "name" => "purchase_order_payment_note",
                "value" => $model_info->note ? $model_info->note : "",
                "class" => "form-control",
                "placeholder" => lang('description'),
                "data-rich-text-editor" => true
            ));
            ?>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#purchase_order-payment-form").appForm({
            onSuccess: function (result) {
                if (typeof RELOAD_VIEW_AFTER_UPDATE !== "undefined" && RELOAD_VIEW_AFTER_UPDATE) {
                    location.reload();
                } else {
                    if ($("#purchase_order-payment-table").length) {
                        //it's from purchase_order details view
                        $("#purchase_order-payment-table").appTable({newData: result.data, dataId: result.id});
                        $("#purchase_order-total-section").html(result.purchase_order_total_view);
                        if (typeof updateInvoiceStatusBar == 'function') {
                            updateInvoiceStatusBar(result.purchase_order_id);
                        }
                    } else {
                        //it's from purchase_orders list view
                        //update table data
                        $("#" + $(".dataTable:visible").attr("id")).appTable({reload: true});
                    }
                }
            }
        });
        $("#purchase_order-payment-form .select2").select2();

        setDatePicker("#purchase_order_payment_date");

        setDatePicker("#cheque_due_date");

        $("#cheque").hide();

        //load all 
        $("#purchase_order_payment_method_id").on("change", function () {
            if ($(this).val() == "5") {
                $("#cheque").show();
            } else {
                $("#cheque").hide();
            }
        });

    });
</script>