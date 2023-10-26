<?php echo form_open(get_uri("banking/save_transfer_money"), array("id" => "transfer_money-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">

    <div class="form-group">
        <label for="date" class=" col-md-3"><?php echo lang('date'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "date",
                "name" => "date",
                "value" => get_my_local_time("Y-m-d"),
                "class" => "form-control",
                "placeholder" => lang('date'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
            <label for="from" class=" col-md-3"><?php echo lang('from'); ?></label>
            <div class=" col-md-9">
                <?php
               echo form_dropdown("from", $from_to_dropdown, "", "class='select2 validate-hidden' id='from' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                ?>
            </div>
    </div>
    <div class="form-group">
            <label for="to" class=" col-md-3"><?php echo lang('to'); ?></label>
            <div class=" col-md-9">
                <?php
               echo form_dropdown("to", $from_to_dropdown, "", "class='select2 validate-hidden' id='to' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                ?>
            </div>
    </div>


    <div class="form-group">
        <label for="amount" class=" col-md-3"><?php echo lang('amount'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "amount",
                "name" => "amount",
                "value" => "",
                "class" => "form-control",
                "placeholder" => lang('amount'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="narration" class=" col-md-3"><?php echo lang('narration'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "narration",
                "name" => "narration",
                "value" => "",
                "class" => "form-control",
                "placeholder" => lang('narration'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
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
        $("#transfer_money-form").appForm({
            onSuccess: function (result) {
                location.reload();
            }
        });
        $("#transfer_money-form #from").select2();
        $("#transfer_money-form #to").select2();
        setDatePicker("#date");
    });
</script>