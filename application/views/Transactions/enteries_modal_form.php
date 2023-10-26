<?php echo form_open(get_uri("transactions/enteries_save"), array("id" => "entery-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type='hidden' name="id" value="<?php echo $model_info->id; ?>" />
    <input type='hidden' name="transaction_id" value="<?php echo $model_info->trans_id; ?>" />
    <input type='hidden' name="account" value="<?php echo $model_info->account; ?>" />

    <div class="form-group">
        <label for="type" class=" col-md-3"><?php echo 'type'; ?></label>
        <div class=" col-md-9">
            <?php
            echo form_dropdown("type", $type_dropdown, $model_info->type, "class='select2 validate-hidden' id='type' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="amount" class=" col-md-3"><?php echo 'Amount'; ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "amount",
                "name" => "amount",
                "value" => $model_info->amount,
                "class" => "form-control",
                "placeholder" => "Amount",
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>

    
    <div class="form-group">
        <label for="narration" class=" col-md-3"><?php echo 'Narration'; ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "narration",
                "name" => "narration",
                "value" => $model_info->narration,
                "class" => "form-control",
                "placeholder" => "Narration",
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>


    <div class="form-group">
        <label for="concerned_person" class=" col-md-3"><?php echo lang('concerned_person'); ?></label>
        <div class="col-md-9">
            <?php
                echo form_dropdown("concerned_person", $concerned_persons_dropdown, $model_info->concerned_person, "class='select2 validate-hidden' id='concerned_person'");
                ?>
        </div>
    </div>

    <div class="form-group">
        <label for="reference" class=" col-md-3"><?php echo lang('reference'); ?></label>
        <div class="col-md-3">
            <?php
            echo form_input(array(
                "id" => "reference",
                "name" => "reference",
                "class" => "form-control",
                "value" => $model_info->reference,
                "placeholder" => lang("reference"),
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
        $("#entery-form").appForm({            
            onSuccess: function (result) {
                $("#entries-table").DataTable().ajax.reload();
            }
        });

        $("#entery-form .select2").select2();
        //$("#branch_id").select2();
        $("#concerned_person").select2();
        //$("#units").select2();

    });
</script>