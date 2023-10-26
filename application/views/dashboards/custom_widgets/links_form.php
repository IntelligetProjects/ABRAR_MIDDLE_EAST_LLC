<?php echo form_open(get_uri("dashboard/save_external_links"), array("id" => "links-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">

    <div class="form-group">
        <label for="website1" class=" col-md-3"><?php echo lang('website'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "website1",
                "name" => "website1",
                "value" => "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('website'),
                "autofocus" => true,
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="website2" class=" col-md-3"><?php echo lang('website'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "website2",
                "name" => "website2",
                "value" => "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('website'),
                "autofocus" => true,
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="website3" class=" col-md-3"><?php echo lang('website'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "website3",
                "name" => "website3",
                "value" => "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('website'),
                "autofocus" => true,
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="website4" class=" col-md-3"><?php echo lang('website'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "website4",
                "name" => "website4",
                "value" => "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('website'),
                "autofocus" => true,
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
        $("#links-form").appForm({
            onSuccess: function (result) {
                location.reload();
            }
        });

    });
</script>