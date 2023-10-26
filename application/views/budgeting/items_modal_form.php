<?php echo form_open(get_uri("budgeting/save_items"), array("id" => "estimate-items-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="estimate_id" value="<?php echo $estimate_id; ?>" />
   
    <div class="form-group">
        <div class="form-group">
        <label for="items" class=" col-md-3"><?php echo lang('item'); ?></label>
        <div class="col-md-9">
              <input type="text" value="" name="items" id="items_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('item'); ?>"  />    
        </div>
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
        $("#estimate-items-form").appForm({
            onSuccess: function (result) {
                location.reload();
            }
        });
        
         $("#items_dropdown").select2({
            multiple: true,
            data: <?php echo ($items_dropdown); ?>
        });
        
        $("#estimate-items-form .select2").select2(); 

    });
        
</script>