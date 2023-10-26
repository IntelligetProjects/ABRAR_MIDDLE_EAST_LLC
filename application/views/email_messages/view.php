<div class="modal-body clearfix general-form">
    <div class="form-group">
        <div  class="col-md-12 notepad-title">
            <strong><?php echo $model_info->title; ?></strong>
        </div>
    </div>


    <?php if ($model_info->message) { ?>
        <div class="col-md-12 mb15 notepad">
            <?php
            echo nl2br($model_info->message);
            ?>
        </div>
    <?php } ?>


</div>

<div class="modal-footer">
    <a href="<?php echo get_uri("email_messages/message?id=").$model_info->id ?>">
    <button type="button" class="btn btn-info" style="padding: 7px;border-radius: 15px;width: 97px;margin-right: 9px;"><span class="fa fa-pencil"></span> <?php echo lang('edit'); ?></button></a>
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
</div>