<?php echo form_open(get_uri("payroll/save"), array("id" => "payroll-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">

    <input type="hidden" name="id" value="<?php echo isset($model_info->id) ? $model_info->id : 0; ?>" />

    
    <?php if (isset($model_info->payroll_id) && $model_info->payroll_id != 0) { ?>
       
    <div class="form-group">
        <label for="manual_bounce" class=" col-md-3"><?php echo "Manual Bonus"; ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "manual_bounce",
                "name" => "manual_bounce",
                "value" => $model_info->manual_bounce,
                "class" => "form-control",
                "placeholder" => "Manual bounce"
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="manual_bounce" class=" col-md-3"><?php echo "Bonus Reason"; ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "manual_bonus_reason",
                "name" => "manual_bonus_reason",
                "value" => $model_info->manual_bonus_reason,
                "class" => "form-control",
                "placeholder" => "Bonus Reason"
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="manual_deduction" class=" col-md-3"><?php echo "Manual Deduction"; ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "manual_deduction",
                "name" => "manual_deduction",
                "value" => $model_info->manual_deduction,
                "class" => "form-control",
                "placeholder" => "Manual Deduction"
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="manual_deduction_reason" class=" col-md-3"><?php echo "Deduction Reason"; ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "manual_deduction_reason",
                "name" => "manual_deduction_reason",
                "value" => $model_info->manual_deduction_reason,
                "class" => "form-control",
                "placeholder" => "Deduction Reason"
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="loan" class=" col-md-3"><?php echo "Loan"; ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "loan",
                "name" => "loan",
                "value" => $model_info->loan,
                "class" => "form-control",
                "placeholder" => "Loan"
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="advance" class=" col-md-3"><?php echo "Advance"; ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "advance",
                "name" => "advance",
                "value" => $model_info->advance,
                "class" => "form-control",
                "placeholder" => "Advance"
            ));
            ?>
        </div>
    </div>


    <div class="form-group">
        <label for="pasi_company" class=" col-md-3"><?php echo lang("pasi_company_share"); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "pasi_company",
                "name" => "pasi_company",
                "value" => $model_info->pasi_company,
                "class" => "form-control",
                "placeholder" => lang("pasi_company")
            ));
            ?>
        </div>
    </div>


    <div class="form-group">
        <label for="pasi_employee" class=" col-md-3"><?php echo lang("pasi_employee_share"); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "pasi_employee",
                "name" => "pasi_employee",
                "value" => $model_info->pasi_employee,
                "class" => "form-control",
                "placeholder" => lang("pasi_employee")
            ));
            ?>
        </div>
    </div>



    <div class="form-group">
        <label for="job_s_company" class=" col-md-3"><?php echo lang("job_security_company_share"); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "job_s_company",
                "name" => "job_s_company",
                "value" => $model_info->job_s_company,
                "class" => "form-control",
                "placeholder" => lang("job_security_company_share")
            ));
            ?>
        </div>
    </div>



    <div class="form-group">
        <label for="job_s_employee" class=" col-md-3"><?php echo lang("job_security_employee_share"); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "job_s_employee",
                "name" => "job_s_employee",
                "value" => $model_info->job_s_employee,
                "class" => "form-control",
                "placeholder" => lang("job_security_employee_share")
            ));
            ?>
        </div>
    </div>

    <?php } else { ?>
        <div class="form-group">
            <label for="month" class=" col-md-3"><?php echo "Month"; ?></label>
            <div class=" col-md-9">
                <?php
                echo form_input(array(
                    "id" => "month",
                    "name" => "month",
                    "value" => "",
                    "class" => "form-control",
                    "placeholder" => "YYYY-MM",
                    "autofocus" => true,
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required")
                ));
                ?>
            </div>
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
        $("#payroll-form").appForm({
            onSuccess: function (result) {
                if (typeof RELOAD_PROJECT_VIEW_AFTER_UPDATE !== "undefined" && RELOAD_PROJECT_VIEW_AFTER_UPDATE) {
                    location.reload();
                } else {
                    $("#payroll-table").DataTable().ajax.reload();
                    $("#payroll-detail-table").DataTable().ajax.reload();
                }
            }
        });
       
        

        /*setDatePicker("#month", 
                    {format: 'dd-mm-yyyy', 
                    startView: "months", 
                    minViewMode: "months"});*/
        setDatePicker("#month", 
                    { 
                    startView: "months", 
                    minViewMode: "months"});

    });
</script>    