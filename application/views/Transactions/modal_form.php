<?php echo form_open(get_uri("transactions/save"), array("id" => "transaction-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="form-group">
        <label for="transaction_date" class=" col-md-3"><?php echo lang('date'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "transaction_date",
                "name" => "transaction_date",
                "value" => $model_info->date ? $model_info->date : get_today_date(),
                "class" => "form-control",
                "placeholder" => lang('transaction_date'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="type" class=" col-md-3"><?php echo lang('type'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_dropdown("type", $types_dropdown, $model_info->type, "class='select2 validate-hidden' id='type' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
            ?>
        </div>
    </div>
    
    <!-- add project selection for integrated banners  -->
    <?php if(strcasecmp($this->db->dbprefix,'Integrated_Banners_') == 0){ ?>
    <div class="form-group">
        <label for="project_id" class=" col-md-3"><?php echo lang('project'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_dropdown("project_id", $projects_dropdwon, $model_info->project_id, "class='select2 validate-hidden' id='project_id' ");
            ?>
        </div>
    </div>
    <?php } ?>

    <div class="form-group">
        <label for="reference" class=" col-md-3"><?php echo lang('note'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "reference",
                "name" => "reference",
                "value" => $model_info->reference,
                "class" => "form-control",
                "placeholder" => lang('note'),
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
        $("#transaction-form").appForm({
            onSuccess: function (result) {
                if (typeof RELOAD_VIEW_AFTER_UPDATE !== "undefined" && RELOAD_VIEW_AFTER_UPDATE) {
                    $("#entries-table").DataTable().ajax.reload();
                } else {
                    <?php
                     if (strcasecmp($this->db->dbprefix, 'Integrated_Banners_') == 0) {
                        echo "window.location = '". site_url('pending_transactions/view')."/'"."+ result.id;";
                     }else{
                        echo "window.location = '". site_url('transactions/view')."/'"."+ result.id;";
                     }
                    ?>
                }
            }
        });
        $("#transaction-form .select2").select2();
        setDatePicker("#transaction_date");
    });
</script>