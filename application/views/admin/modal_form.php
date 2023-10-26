<?php echo form_open(get_uri("expires/save"), array("id" => "expiry-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id?$model_info->id:0; ?>" />


    <div class="form-group">
        <label for="item" class=" col-md-3"><?php echo lang('item (name)'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "item",
                "name" => "item",
                "class" => "form-control",
                "value" => $model_info->item?$model_info->item:'',
                "placeholder" => lang('item'),
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

                // $type_dropdown = array(
                //     "" => "-", 
                //     "Domain" => "Domain",
                //     "Hosting" => "Hosting",
                //     "Visa" => "Visa",
                //     "Insurance" => "Insurance",
                //     "Car Insurance" => "Car Insurance",
                //     "Car Registration" => "Car Registration",
                //     "AMC Contract" => "AMC Contract",
                // );

                // echo form_dropdown("type", $type_dropdown, $model_info->type?$model_info->type:'', "class='select2 ' id='type' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                
            ?>
            <?php
            echo form_input(array(
                "id" => "type",
                "name" => "type",
                "class" => "form-control",
                "value" => $model_info->item?$model_info->item:'',
                "placeholder" => lang('type'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="department_id" class=" col-md-3"><?php echo lang('department'); ?></label>
        <div class=" col-md-9">
            <?php

                // echo form_dropdown("department_id", $departments_dropdown, $model_info->department_id?$model_info->department_id:'', "class='select2 ' id='department_id' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                
            ?>
             <?php
            echo form_input(array(
                "id" => "department",
                "name" => "department_id",
                "class" => "form-control",
                "value" => $model_info->item?$model_info->item:'',
                "placeholder" => lang('department'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="expiry" class=" col-md-3"><?php echo lang('expiry'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "expiry",
                "name" => "expiry",
                "class" => "form-control",
                "value" => $model_info->expiry ? $model_info->expiry : '',
                "placeholder" => lang('expiry'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    
    <div class="form-group">
        <label for="recurring_charges" class=" col-md-3"><?php echo lang('recu_charges'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "recurring_charges",
                "name" => "recurring_charges",
                "class" => "form-control",
                "value" => $model_info->recurring_charges ? $model_info->recurring_charges : 0,
                "placeholder" => lang('recu_charges'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="responsible_id" class=" col-md-3"><?php echo lang('responsible'); ?></label>
        <div class=" col-md-9">
            <?php

                echo form_dropdown("responsible_id", $team_members_dropdown, $model_info->responsible_id?$model_info->responsible_id:'', "class='select2 ' id='responsible_id' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="client_id" class=" col-md-3"><?php echo lang('client'); ?></label>
        <div class=" col-md-9">
            <?php

                echo form_dropdown("client_id", $clients_dropdown, $model_info->client_id?$model_info->client_id:'', "class='select2 ' id='client_id'");
                
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
    $(document).ready(function() {

        $(".select2").select2();
        setDatePicker("#expiry");

        $("#expiry-form").appForm({
            onSuccess: function(result) {
                
                $("#expires-table").DataTable().ajax.reload();
                
            }
        });
    });
</script>    