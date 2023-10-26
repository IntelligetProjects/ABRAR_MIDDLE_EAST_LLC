<?php $hidden_menu = explode(",", get_setting("hidden_client_menus")); ?>
<div id="page-content" class="p20 clearfix" style="background-color: #ffffff">
<style>
    .panel-ashe{
        color: #fff;
    }
</style>
<!-- <div class="widget-container col-md-6">
    <div class="panel panel-ashe" style="background-color: #43ab61 ;border-radius: 24px;">
        <a href="<?php echo get_uri("projects"); ?>" class="white-link" >
            <div class="panel-body ">
                <div class="widget-icon">
                    <i class="fa fa-th-large"></i>
                </div>
                <div class="widget-details_internal_links">
                    <?php echo lang("projects"); ?>
                </div>
            </div>
        </a>
    </div>
</div>
<div class="widget-container col-md-6">
    <div class="panel panel-ashe" style="background-color: #8a6d3b;border-radius: 24px;">
        <a href="<?php echo get_uri("invoices"); ?>" class="white-link" >
            <div class="panel-body ">
                <div class="widget-icon">
                    <i class="fa fa-file-text"></i>
                </div>
                <div class="widget-details_internal_links">
                    <?php echo lang("invoices"); ?>
                </div>
            </div>
        </a>
    </div>
</div>
<div class="widget-container col-md-6">
    <div class="panel panel-ashe" style="background-color: #3b87bb;border-radius: 24px;">
        <a href="<?php echo get_uri("invoice_payments"); ?>" class="white-link" >
            <div class="panel-body ">
                <div class="widget-icon">
                    <i class="fa fa-money"></i>
                </div>
                <div class="widget-details_internal_links">
                    <?php echo lang("invoice_payments"); ?>
                </div>
            </div>
        </a>
    </div>
</div>
<div class="widget-container col-md-6">
    <div class="panel panel-ashe" style="background-color: #7d1f98;border-radius: 24px;">
        <a href="<?php echo get_uri("events"); ?>" class="white-link" >
            <div class="panel-body ">
                <div class="widget-icon">
                    <i class="fa fa-calendar"></i>
                </div>
                <div class="widget-details_internal_links">
                    <?php echo lang("events"); ?>
                </div>
            </div>
        </a>
    </div>
</div> -->
<!-- <div class="widget-container col-md-6">
    <div class="panel panel-ashe" style="background-color: #a94442;border-radius: 24px;">
        <a href="<?php echo get_uri("estimates"); ?>" class="white-link" >
            <div class="panel-body ">
                <div class="widget-icon">
                    <i class="fa fa-file"></i>
                </div>
                <div class="widget-details_internal_links">
                    <?php echo lang("estimate_requests"); ?>
                </div>
            </div>
        </a>
    </div>
</div> -->
<!-- <div class="widget-container col-md-6">
    <div class="panel panel-ashe" style="background-color: #00b393;border-radius: 24px;">
        <a href="http://arkitondesign.com" class="white-link" >
            <div class="panel-body ">
                <div class="widget-icon">
                    <i class="fa fa-briefcase"></i>
                </div>
                <div class="widget-details_internal_links">
                    <?php echo lang("arkitondesign"); ?>
                </div>
            </div>
        </a>
    </div>
</div> -->

<?php if(get_setting("module_event") == "1" && !in_array("events", $hidden_menu)) { ?>
    <div class="widget-container col-md-6">
            <div class="panel panel-ashe" style="background-color: #ca3f4a;border-radius: 24px;">
                <a href="<?php echo get_uri("events"); ?>" class="white-link" >
                    <div class="panel-body ">
                        <div class="widget-icon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <div class="widget-details_internal_links">
                            <?php echo lang("events"); ?>
                        </div>
                    </div>
                </a>
            </div>
        </div>
<?php }?>

<?php if(!in_array("projects", $hidden_menu)) { ?>
    <div class="widget-container col-md-6">
            <div class="panel panel-ashe" style="background-color: #823fca;border-radius: 24px;">
                <a href="<?php echo get_uri("projects"); ?>" class="white-link" >
                    <div class="panel-body ">
                        <div class="widget-icon">
                            <i class="fa fa-th-large"></i>
                        </div>
                        <div class="widget-details_internal_links">
                            <?php echo lang("projects"); ?>
                        </div>
                    </div>
                </a>
            </div>
        </div>
<?php }?>

<?php if(get_setting("module_estimate") && !in_array("estimates", $hidden_menu)) { ?>
    <div class="widget-container col-md-6">
            <div class="panel panel-ashe" style="background-color: #3f62ca;border-radius: 24px;">
                <a href="<?php echo get_uri("estimates"); ?>" class="white-link" >
                    <div class="panel-body ">
                        <div class="widget-icon">
                            <i class="fa fa-file"></i>
                        </div>
                        <div class="widget-details_internal_links">
                            <?php echo lang("estimates"); ?>
                        </div>
                    </div>
                </a>
            </div>
        </div>
<?php }?>

<?php if(get_setting("module_invoice") == "1") { ?>
    <?php if(!in_array("invoices", $hidden_menu)) { ?>
        <div class="widget-container col-md-6">
            <div class="panel panel-ashe" style="background-color: #3fc2ca;border-radius: 24px;">
                <a href="<?php echo get_uri("invoices"); ?>" class="white-link" >
                    <div class="panel-body ">
                        <div class="widget-icon">
                            <i class="fa fa-file-text"></i>
                        </div>
                        <div class="widget-details_internal_links">
                            <?php echo lang("invoices"); ?>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    <?php }?>
    <?php if(!in_array("payments", $hidden_menu)) { ?>
        <div class="widget-container col-md-6">
            <div class="panel panel-ashe" style="background-color: #3fca8a;border-radius: 24px;">
                <a href="<?php echo get_uri("invoice_payments"); ?>" class="white-link" >
                    <div class="panel-body ">
                        <div class="widget-icon">
                            <i class="fa fa-money"></i>
                        </div>
                        <div class="widget-details_internal_links">
                            <?php echo lang("invoice_payments"); ?>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    <?php }?>
<?php }?>

<div class="widget-container col-md-6">
    <div class="panel panel-ashe" style="background-color: #53ca3f;border-radius: 24px;">
        <a href="<?php echo get_uri("clients/users"); ?>" class="white-link" >
            <div class="panel-body ">
                <div class="widget-icon">
                    <i class="fa fa-user"></i>
                </div>
                <div class="widget-details_internal_links">
                    <?php echo lang("users"); ?>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="widget-container col-md-6">
    <div class="panel panel-ashe" style="background-color: #cac63f;border-radius: 24px;">
        <a href="<?php echo get_uri('clients/contact_profile/' . $this->login_user->id); ?>" class="white-link" >
            <div class="panel-body ">
                <div class="widget-icon">
                    <i class="fa fa-cog"></i>
                </div>
                <div class="widget-details_internal_links">
                    <?php echo lang("my_profile"); ?>
                </div>
            </div>
        </a>
    </div>
</div>


<?php if(get_setting("module_ticket") == "1" && !in_array("tickets", $hidden_menu)) { ?>
    <div class="widget-container col-md-6">
            <div class="panel panel-ashe" style="background-color: #ca793f;border-radius: 24px;">
                <a href="<?php echo get_uri("tickets"); ?>" class="white-link" >
                    <div class="panel-body ">
                        <div class="widget-icon">
                            <i class="fa fa-life-ring"></i>
                        </div>
                        <div class="widget-details_internal_links">
                            <?php echo lang("tickets"); ?>
                        </div>
                    </div>
                </a>
            </div>
        </div>
<?php }?>

<?php if(get_setting("module_knowledge_base") == "1" && !in_array("knowledge_base", $hidden_menu)) { ?>
    <div class="widget-container col-md-6">
            <div class="panel panel-ashe" style="background-color: #ca663f;border-radius: 24px;">
                <a href="<?php echo get_uri("knowledge_base"); ?>" class="white-link" >
                    <div class="panel-body ">
                        <div class="widget-icon">
                            <i class="fa fa-question-circle"></i>
                        </div>
                        <div class="widget-details_internal_links">
                            <?php echo lang("knowledge_base"); ?>
                        </div>
                    </div>
                </a>
            </div>
        </div>
<?php }?>

</div>
<script type="text/javascript">
    $(document).ready(function () {

    });
</script>    

