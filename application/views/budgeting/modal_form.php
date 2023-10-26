<?php echo form_open(get_uri("budgeting/save"), array("id" => "estimate-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <input type="hidden" name="estimate_request_id" value="<?php echo $estimate_request_id; ?>" />

    <?php if ($is_clone) { ?>
        <input type="hidden" name="is_clone" value="1" />
       
    <?php } ?>

    <div class="form-group">
        <label for="estimate_date" class=" col-md-3"><?php echo lang('budgeting_date'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "estimate_date",
                "name" => "estimate_date",
                "value" => $model_info->estimate_date,
                "class" => "form-control",
                "placeholder" => lang('budgeting_date'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>

        <div class="form-group">
            <label for="project_id" class=" col-md-3"><?php echo lang('project'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("project_id", $projects_dropdown, array($model_info->project_id), "class='select2 validate-hidden' id='estimate_client_id' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                ?>
            </div>
          
            </div>  

    <div class="form-group">
        <label for="profit" class=" col-md-3"><?php echo lang('profit'); ?> %</label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "profit",
                "name" => "profit",
                "value" => $model_info->profit? $model_info->profit:'',
                "class" => "form-control",
                "placeholder" => lang('profit'),
                "data-rule-required" => false,
                // "data-msg-required" => lang("field_required"),
                // "min" => 1,
            ));
            ?>
        </div>
    </div>
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
                    window.location = "<?php echo site_url('budgeting/view'); ?>/" + result.id;
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