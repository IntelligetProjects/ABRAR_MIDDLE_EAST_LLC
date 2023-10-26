<?php echo form_open(get_uri("banking/save_default_bank"), array("id" => "default-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">


    <div class="form-group">
            <label for="from" class=" col-md-3"><?php echo lang('default_bank'); ?></label>
            <div class=" col-md-9">
                <?php
               echo form_dropdown("account", $dropdown, get_setting('default_bank'), "class='select2 validate-hidden' id='account' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
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
        $("#default-form").appForm({
            onSuccess: function (result) {
                location.reload();
            }
        });
        $("#default-form #account").select2();
    });
</script>