<div id="page-content" class="p20  clearfix">
    <div id="sidebarSettings" class="col-sm-3 col-lg-2">
        <?php
        $tab_view['active_tab'] = "attendance";
        $this->load->view("settings/tabs", $tab_view);
        ?>
    </div>

    <div class="col-sm-9 col-lg-10">
        <?php echo form_open(get_uri("settings/save_attendance_password"), array("id" => "attendance-settings-form", "class" => "general-form", "role" => "form")); ?>
        <div class="panel">
            <div class="panel-default panel-heading">
                <h4><?php echo lang("attendance"); ?></h4>
            </div>

            <div class="form-group">
                <div class="p15 col-md-12 clearfix">
                </div>
                <div class="form-group">
                    <label for="enable_attendance" class=" col-md-6"><?php echo ('Enable attendance by Geolocation'); ?></label>
                    <div class=" col-md-6">
                        <?php echo form_checkbox("enable_attendance", "1", get_setting('enable_attendance') ? true : false, "id='enable_attendance'");
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                
                <div class="form-group">
                    <label for="attendance_password" class=" col-md-2"><?php echo ('Attendance Password'); ?></label>
                    <div class=" col-md-10">
                        <?php
                        echo form_input(array(
                            "id" => "attendance_password",
                            "name" => "attendance_password",
                            "value" => get_setting('attendance_password') ? get_setting('attendance_password') : "",
                            "class" => "form-control",
                            "placeholder" => 'Attendance Password',
                            "autocomplete" => "off",
                            "data-rule-required" => true,
                            "data-msg-required" => lang("field_required"),
                            "data-rule-minlength" => 6,
                            "data-msg-minlength" => lang("enter_minimum_6_characters"),
                        ));
                        ?>
                    </div>
                </div>
                <div class="pt5 col-md-12 clearfix">
                    <i class="fa fa-info-circle"></i> <?php echo ("Password to set attendance settings of each user on the respective devices"); ?>
                </div>
            </div>
            <div class="panel-footer">
                <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        $("#attendance-settings-form").appForm({
            isModal: false,
            onSuccess: function(result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });
    });
</script>