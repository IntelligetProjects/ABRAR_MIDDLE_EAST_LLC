<?php echo form_open(get_uri("accounts/save"), array("id" => "account-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">

    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />


    <?php //if (/*!$has_entries &&*/ $model_info->acc_parent != 8 && $model_info->acc_parent != 942) { ?>
        <div class="form-group">
            <label for="account_parent" class=" col-md-3"><?php echo lang('account_parent'); ?></label>
            <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "account_parent",
                        "name" => "account_parent",
                        "value" => $model_info->acc_parent,
                        "class" => "form-control validate-hidden",
                        "placeholder" => lang('account_parent'),
                        "data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),
                    ));
                    ?>
                    <a id="account_parent_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span>×</span></a>
            </div>
        </div>
          
    <?php //} else { ?>
        <!-- <input type="hidden" name="account_parent" value="<?php echo $model_info->acc_parent; ?>" /> -->
    <?php //} ?>

    <div class="form-group">
        <label for="account_name" class=" col-md-3"><?php echo lang('account_name'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "account_name",
                "name" => "account_name",
                "value" => $model_info->acc_name ? $model_info->acc_name : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('account_name'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="account_code" class=" col-md-3"><?php echo lang('account_code'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "account_code",
                "name" => "account_code",
                "value" => $model_info->acc_code ? $model_info->acc_code : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('account_code'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>

     <div class="form-group">
        <label for="description" class=" col-md-3"><?php echo lang('description'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "description",
                "name" => "description",
                "value" => $model_info->acc_description ? $model_info->acc_description : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('description'),
            ));
            ?>
        </div>
    </div>



    <?php if (empty($model_info->is_primary)) { ?>
        <div class="form-group">
            <label for="is_inactive" class=" col-md-3"><?php echo lang('is_inactive'); ?></label>
            <div class="col-md-9">
                <div>
                    <?php
                        echo form_checkbox("is_inactive", "1", $model_info->is_inactive ? true : false, "id='is_inactive'");
                    ?>
                </div>
            </div>
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
        $("#account-form").appForm({
            onSuccess: function (result) {
               // location.reload();
            }
        });

        $("#account-form .select2").select2();


        //show item suggestion dropdown when adding new item
        var isUpdate = "<?php echo $model_info->acc_parent ?>";
        //if (!isUpdate) {
            applySelect2OnItemTitle();
            code_suggest();
            //sub_account_level();
        //}

        //re-initialize item suggestion dropdown on request
        $("#account_parent_dropdwon_icon").click(function () {
            applySelect2OnItemTitle();
        })

       $("#account_parent").on("change", function () {
            code_suggest();
        });


    });


    function code_suggest() {
        var account_parent = $("#account_parent").val();
        if ($("#account_parent").val()) {
            $.ajax({
                url: "<?php echo get_uri("accounts/get_account_code_suggestion") ?>" + "/" + account_parent,
                dataType: "json",
                success: function (result) {
                    $("#account_code").val(result);
                }
            });
        }
    }

    function applySelect2OnItemTitle() {
    $("#account_parent").select2({
        showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("accounts/get_form_accounts_suggestion"); ?>",
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term // search term
                    };
                },
                results: function (data, page) {
                    return {results: data};
                }
            }
        });
    }



</script>