<?php echo form_open(get_uri("salary_advance/save"), array("id" => "salary-advance-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="form-group">
        <label for="user_id" class=" col-md-3"><?php echo lang('employee'); ?></label>
        <div class=" col-md-9">
            <?php
            
            echo form_dropdown("user_id", $users_dropdown, $model_info->user_id, "class='select2 validate-hidden' id='user_id' data-rule-required='true' data-msg-required='" . lang('field_required') . "' ");
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="amount" class=" col-md-3"><?php echo lang('amount'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "amount",
                "name" => "amount",
                "value" => $model_info->amount,
                "class" => "form-control",
                "placeholder" => lang('amount'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="date" class=" col-md-3"><?php echo lang('date'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "date",
                "name" => "date",
                "value" => $model_info->date,
                "class" => "form-control",
                "placeholder" => lang('date'),
                "autofocus" => true,
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
        $("#salary-advance-form").appForm({
            onSuccess: function (result) {
                $("#salary-advance-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        $("#amount").focus();
        setDatePicker('#date');
        $("#user_id").select2();

    });
</script>    