<?php echo form_open(get_uri("contacts/save"), array("id" => "contact-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />


    <div class="form-group">
        <label for="name" class=" col-md-3"><?php echo lang('name'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "name",
                "name" => "name",
                "class" => "form-control",
                "value" => $model_info->name,
                "placeholder" => lang('name'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    
    <div class="form-group">
        <label for="job_title" class=" col-md-3"><?php echo lang('job_title'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "job_title",
                "name" => "job_title",
                "class" => "form-control",
                "value" => $model_info->job_title ? $model_info->job_title : "",
                "placeholder" => lang('job_title'),
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
                "type" => "number",
                "value" => $model_info->phone ? $model_info->phone : "",
                "class" => "form-control",
                "placeholder" => lang('phone'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="phone" class=" col-md-3"><?php echo lang('email'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "email",
                "name" => "email",
                "type" => "email",
                "value" => $model_info->email ? $model_info->email : "",
                "class" => "form-control",
                "placeholder" => lang('email'),
                "data-rule-required" => false,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="alternative_phone" class=" col-md-3"><?php echo lang('alternative_phone'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "alternative_phone",
                "name" => "alternative_phone",
                "type" => "number",
                "value" => $model_info->alternative_phone ? $model_info->alternative_phone : "",
                "class" => "form-control",
                "placeholder" => lang('alternative_phone')
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="address" class=" col-md-3"><?php echo lang('address'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "address",
                "name" => "address",
                "value" => $model_info->address ? $model_info->address : "",
                "class" => "form-control",
                "placeholder" => lang('address')
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="note" class=" col-md-3"><?php echo lang('note'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "note",
                "name" => "note",
                "value" => $model_info->note ? $model_info->note : "",
                "class" => "form-control",
                "placeholder" => lang('note'),
                "data-rich-text-editor" => true
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
        $("#contact-form").appForm({
            onSuccess: function (result) {
                $("#contact-table").appTable({newData: result.data, dataId: result.id});
            }
        });
    });
</script>