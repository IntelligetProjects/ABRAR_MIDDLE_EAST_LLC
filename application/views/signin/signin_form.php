<style>
    iframe{
        width: 100% !important;
        height: 700px!important;
    }
    .g-recaptcha div{
    /* position: static; */
    width: 336px;
    height: 600px;
    }
    .panel-default {
    background: rgb(25 26 27 / 32%);
    border: 1px solid #686868;
    border-radius: 6px;
    display: inline-flex;
    }
    .btn-primary {
    border: none;
    background-color: rgb(4 29 54 / 32%);
    border: 1px solid gray;
    border-radius: 0px;
    padding: 8px 15px;
}
.panel-default>.panel-heading {
    background: rgb(255 255 255 / 33%) !important;
    border-bottom: 1px;
}
.signin-box {
    margin-top: 13%;
}
.panel-default img {
    margin-top: 75px;
}
/* shine effect  */

</style>


<div class="panel panel-default mb15">
    <div class="panel-heading text-center">
    <div class="image-wrapper shine">
    <img class="p0" src="<?php echo get_logo_url(); ?>" />
    </div>
       
    </div>
    <div class="panel-body p30">
        <?php echo form_open("signin", array("id" => "signin-form", "class" => "general-form", "role" => "form")); ?>

        <?php if (validation_errors()) { ?>
            <div class="alert alert-danger" role="alert">
                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                <?php echo validation_errors(); ?>
            </div>
        <?php } ?>
        <div class="form-group">
            <?php
            echo form_input(array(
                "id" => "email",
                "name" => "email",
                "value"=> $this->db->dbprefix=='new_erp_demo'?'demo@teamway.om':'',
                "class" => "form-control p10",
                "placeholder" => lang('email'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
                "data-rule-email" => true,
                "data-msg-email" => lang("enter_valid_email")
            ));
            ?>
        </div>
        <div class="form-group">
            <?php
            echo form_password(array(
                "id" => "password",
                "name" => "password",
                "value"=> $this->db->dbprefix=='new_erp_demo'?'123456':'',
                "class" => "form-control p10",
                "placeholder" => lang('password'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required")
            ));
            ?>
        </div>
        <input type="hidden" name="redirect" value="<?php
        if (isset($redirect)) {
            echo $redirect;
        }
        ?>" />

       
        <!-- <?php $this->load->view("signin/re_captcha"); ?> -->
       
        <div class="form-group mb0">
            <button class="btn btn-lg btn-primary btn-block mt15" type="submit"><?php echo lang('signin'); ?></button>
        </div>
        <?php echo form_close(); ?>
        <!-- <div class="mt5"><?php echo anchor("signin/request_reset_password", lang("forgot_password")); ?></div> -->

        <?php if (!get_setting("disable_client_signup")) { ?>
            <div class="mt20"><?php echo lang("you_dont_have_an_account") ?> &nbsp; <?php echo anchor("signup", lang("signup")); ?></div>
        <?php } ?>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        $("#signin-form").appForm({ajaxSubmit: false, isModal: false});
    });
</script>    