<div class="tab-content">
    <?php echo form_open(get_uri("payroll/eos_slip/" . $employee_id), array("id" => "general-info-form", "class" => "general-form dashed-row white", "role" => "form")); ?>
    <div class="panel">
        <div class="panel-default panel-heading">
            <h4> <?php echo lang('final_settlement'); ?></h4>
        </div>
            <br><br>
            <div class="form-group">
                <label for="last_working_day" class=" col-md-2"><?php echo lang('last_working_day'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "last_working_day",
                        "name" => "last_working_day",
                        "value" => "",
                        "class" => "form-control",
                        "placeholder" => lang('last_working_day'),
                        "autocomplete" => "off",
                        "data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),
                    ));
                    ?>
                </div>
            </div>

             <div class="form-group">
                <label for="reason" class=" col-md-2"><?php echo lang('reason'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "reason",
                        "name" => "reason",
                        "value" => "",
                        "class" => "form-control",
                        "placeholder" => lang('reason'),
                        "autocomplete" => "off",
                        "data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),
                    ));
                    ?>
                </div>
            </div>

        </div>
        <div class="panel-footer">
            <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('prepare_final_settlement'); ?></button>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">
    $(document).ready(function () {



        setDatePicker("#last_working_day");

    });
</script>    