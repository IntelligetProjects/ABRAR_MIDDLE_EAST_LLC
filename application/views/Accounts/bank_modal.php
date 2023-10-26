<?php echo form_open(get_uri("accounts/save"), array("id" => "account-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">

    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <input type="hidden" name="account_parent" value= '<?php echo  $account_par ; ?>'  />

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
    <!-- <input type="text" id="account_name" value="<?php get_setting('banks_accounts_parent') ?>" > -->

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
            ));
            ?>
        </div>
    </div>

   <!--  <div class="form-group">
        <label for="account_parent" class=" col-md-3"><?

        /*php echo lang('account_parent');*/ ?></label>
        <div class="col-md-9">
            <?php
            /*echo form_input(array(
                "id" => "account_parent",
                "name" => "account_parent",
                "value" => $account_name,
                "class" => "form-control validate-hidden",
                "placeholder" => lang('account_parent'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));*/
            ?>
            <a id="account_parent_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span>×</span></a>
        </div>
    </div>

</div> -->

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#account-form").appForm({
            onSuccess: function (result) {
                location.reload();
            }
        });

        //show item suggestion dropdown when adding new item
       var isUpdate = "<?php echo $model_info->id; ?>";
        if (!isUpdate) {
            applySelect2OnItemTitle();
        }

        //re-initialize item suggestion dropdown on request
      $("#account_parent_dropdwon_icon").click(function () {
            applySelect2OnItemTitle();
        })

    });

    function applySelect2OnItemTitle() {
        $("#account_parent").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("accounts/get_accounts_suggestion"); ?>",
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