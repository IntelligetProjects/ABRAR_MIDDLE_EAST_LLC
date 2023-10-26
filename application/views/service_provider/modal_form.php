<?php echo form_open(get_uri("service_provider/save"), array("id" => "category-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="form-group">
        <br />
        <label for="name" class=" col-md-3"><?php echo lang('name'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "name",
                "name" => "name",
                "value" => $model_info->name,
                "class" => "form-control",
                "placeholder" => lang('name'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="phone" class=" col-md-3"><?php echo lang('phone'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "phone",
                "name" => "phone",
                "value" => $model_info->phone,
                "class" => "form-control",
                "placeholder" => lang('phone'),
                "autofocus" => true,
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="email" class=" col-md-3"><?php echo lang('email'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "email",
                "name" => "email",
                "value" => $model_info->email,
                "class" => "form-control",
                "placeholder" => lang('email'),
                "autofocus" => true,
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="vat_number" class=" col-md-3"><?php echo lang('vat_number'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "vat_number",
                "name" => "vat_number",
                "value" => $model_info->vat_number,
                "class" => "form-control",
                "placeholder" => lang('vat_number'),
                "autofocus" => true,
            ));
            ?>
        </div>
        <br />
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#category-form").appForm({
            onSuccess: function(result) {
                $("#service-provider-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        $("#name").focus();
    });
</script>