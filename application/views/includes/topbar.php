<?php load_js(array("assets/js/push_notification/pusher/pusher.min.js")); ?>

<style style="text/css">
    .marquee {
        height: 50px;
        overflow: hidden;
        position: relative;
        background: #f39c12;
        color: #333;
        border: 1px solid #4a4a4a;
    }

    .marquee p {
        position: absolute;
        width: 100%;
        height: 100%;
        margin: 0;
        line-height: 50px;
        text-align: center;
        font-size: 20px;
        font-weight: 900;
    }

    .marquee a {
        color: black;
        text-decoration: underline;
    }


    .fade-wrapper {
        display: block;
        position: fixed;
        height: 100%;
        width: 100%;
        background: #f39c12fa;
        z-index: 9999;
    }


    .fade-wrapper p {
        position: absolute;
        width: 100%;
        height: 100%;
        margin: 0;
        line-height: 25;
        text-align: center;
        font-size: 20px;
        font-weight: 900;
    }

    .fade-wrapper a {
        color: black;
        text-decoration: underline;
    }
</style>

<nav class="navbar navbar-default navbar-fixed-top" role="navigation" id="default-navbar">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="fa fa-chevron-down"></span>
        </button>
        <!-- <button id="sidebar-toggle" type="button" class="navbar-toggle"  data-target="#sidebar">
            <span class="sr-only">Toggle navigation</span>
            <span class="fa fa-bars"></span>
        </button> -->
        <?php if ($this->login_user->user_type == "staff") { ?>
            <button id="sidebar-toggle" type="button" class="navbar-toggle" data-target="#sidebar">
                <span class="sr-only">Toggle navigation</span>
                <span class="fa fa-bars"></span>
            </button>
        <?php } else { ?>
            <button id="sidebar-toggle" type="button" class="navbar-toggle" data-target="#sidebar">
                <a href="<?php echo_uri('dashboard'); ?>" class="dropdown-toggle"><span class="fa fa-home"></span></a>
            </button>
        <?php } ?>

        <?php
        $user = $this->login_user->id;
        $dashboard_link = get_uri("dashboard");
        $user_dashboard = get_setting("user_" . $user . "_dashboard");
        if ($user_dashboard) {
            $dashboard_link = get_uri("dashboard/view/" . $user_dashboard);
        }
        ?>

        <a class="navbar-brand" href="<?php echo $dashboard_link; ?>"><img class="dashboard-image" src="<?php echo get_logo_url(); ?>" /></a>


    </div>

    <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-left inline-block hidden-xs">
            <li class="hidden-xs pl15 pr15  b-l">
                <?php if ($this->login_user->user_type == "staff") { ?>
                    <button class="hidden-xs" id="sidebar-toggle-md">
                        <span class="fa fa-dedent"></span>
                    </button>
                <?php } else { ?>
            <li class="todo-top-icon">
                <a href="<?php echo_uri('dashboard'); ?>" class="dropdown-toggle" data-toggle="tooltip" data-original-title=<?= lang("manage_dashboards") ?>><i class="fa fa-home"></i></a>
            </li>
        <?php } ?>
        </li>

        <?php
        //get the array of hidden topbar menus
        $hidden_topbar_menus = explode(",", get_setting("user_" . $user . "_hidden_topbar_menus"));

        if (!in_array("to_do", $hidden_topbar_menus)) {
            $this->load->view("todo/topbar_icon");
        }
        // if (!in_array("favorite_projects", $hidden_topbar_menus)) {
        //     $this->load->view("projects/star/topbar_icon");
        // }
        // if (!in_array("favorite_clients", $hidden_topbar_menus)) {
        //     $this->load->view("clients/star/topbar_icon");
        // }
        /*if (!in_array("dashboard_customization", $hidden_topbar_menus) && (get_setting("disable_new_dashboard_icon") != 1) && $this->login_user->user_type == "staff") {
                $this->load->view("dashboards/list/topbar_icon");
            }*/
        ?>
        <a href="https://system.teamway.om/erp_docs/" target="_blank" class="btn btn-default" style="margin-top:10px;"><?php echo lang('Documentation') ?></a>
        <?php echo my_open_timers(true); ?>
        </ul>
        <style>
            #outer-div {
                text-align: center;
                width: 70%;
                height: 0px;
            }
            

            #inner-div {
                display: inline-block;
                margin: 0 auto;
                padding: 3px;
            }

            @media only screen and (max-width: 600px) {
                #outer-div {
                    width: 100%;
                    height: auto;
                }
            }
        </style>

        <ul class="nav navbar-nav navbar-right inline-block">

            <?php
            if (!in_array("quick_add", $hidden_topbar_menus) && $this->login_user->user_type == "staff") {
                $this->load->view("settings/topbar_parts/quick_add");
            }
            ?>

            <!-- currency  -->
            <!-- <li class="view-currecny-option top_tooltip" data-toggle="tooltip" data-original-title='<?= lang("change_view_currency") ?>' data-placement="bottom">
                <?php echo js_anchor("<i class='fa fa-money'></i>", array("id" => "view-currecny-icon", "class" => "dropdown-toggle", "data-toggle" => "dropdown")); ?>

                <ul class="dropdown-menu p0" style="height: 120px; overflow-y: scroll; min-width: 170px;">
                    <li>
                        <?php
                        $currencies = $this->Currencies_model->get_details()->result();
                        $cur_view_currency_id = get_current_view_currency_id();

                        foreach ($currencies as $currency) {
                            $cur_status = "";
                            $cur_text = $currency->symbol;

                            if ($cur_view_currency_id == $currency->id) {
                                $cur_status = "<span class='pull-right checkbox-checked m0'></span>";
                                $cur_text = "<strong class='pull-left'>" . $currency->symbol . "</strong>";
                            }
                            echo ajax_anchor(get_uri("currencies/set_current_view_currency_in_session/$currency->id"), $cur_text . $cur_status, array("class" => "clearfix", "data-reload-on-success" => "1"));
                        }
                        ?>
                    </li>
                </ul>
            </li> -->
            <!-- currency  -->

            <?php if (!in_array("language", $hidden_topbar_menus) && (($this->login_user->user_type == "staff" && !get_setting("disable_language_selector_for_team_members")) || ($this->login_user->user_type == "client" && !get_setting("disable_language_selector_for_clients")))) { ?>

                <li class="user-language-option top_tooltip" data-toggle="tooltip" data-original-title='<?= lang("change_language") ?>' data-placement="bottom">
                    <?php echo js_anchor("<i class='fa fa-globe'></i>", array("id" => "personal-language-icon", "class" => "dropdown-toggle", "data-toggle" => "dropdown")); ?>

                    <ul class="dropdown-menu p0" style="height: 80px; overflow-y: scroll; min-width: 170px;">
                        <li>
                            <?php
                            $user_language = get_setting("user_" . $this->login_user->id . "_personal_language");
                            $system_language = get_setting("language");

                            foreach (get_language_list() as $language) {
                                $language_status = "";
                                $language_text = $language;

                                if ($user_language == strtolower($language) || (!$user_language && $system_language == strtolower($language))) {
                                    $language_status = "<span class='pull-right checkbox-checked m0'></span>";
                                    $language_text = "<strong class='pull-left'>" . $language . "</strong>";
                                }

                                if ($this->login_user->user_type == "staff") {
                                    echo ajax_anchor(get_uri("team_members/save_personal_language/$language"), $language_text . $language_status, array("class" => "clearfix", "data-reload-on-success" => "1"));
                                } else {
                                    echo ajax_anchor(get_uri("clients/save_personal_language/$language"), $language_text . $language_status, array("class" => "clearfix", "data-reload-on-success" => "1"));
                                }
                            }
                            ?>
                        </li>
                    </ul>
                </li>

            <?php } ?>

            <li class="top_tooltip" data-toggle="tooltip" data-original-title='<?= lang("check_notification") ?>' data-placement="bottom">
                <?php echo js_anchor("<i class='fa fa-bell-o'></i>", array("id" => "web-notification-icon", "class" => "dropdown-toggle", "data-toggle" => "dropdown")); ?>
                <div class="dropdown-menu aside-xl m0 p0 font-100p" style="min-width: 400px;">
                    <div class="dropdown-details panel bg-white m0">
                        <div class="list-group">
                            <span class="list-group-item inline-loader p10"></span>
                        </div>
                    </div>
                    <div class="panel-footer text-sm text-center">
                        <?php echo anchor("notifications", lang('see_all')); ?>
                    </div>
                </div>
            </li>

            <?php if (get_setting("module_message")) { ?>
                <li class="top_tooltip <?php echo ($this->login_user->user_type === "client" && !get_setting("client_message_users")) ? "hide" : ""; ?>" data-toggle="tooltip" data-original-title='<?= lang("check_messages") ?>' data-placement="bottom">
                    <?php echo js_anchor("<i class='fa fa-envelope-o'></i>", array("id" => "message-notification-icon", "class" => "dropdown-toggle", "data-toggle" => "dropdown")); ?>
                    <div class="dropdown-menu aside-xl m0 p0 w300 font-100p">
                        <div class="dropdown-details panel bg-white m0">
                            <div class="list-group">
                                <span class="list-group-item inline-loader p10"></span>
                            </div>
                        </div>
                        <div class="panel-footer text-sm text-center">
                            <?php echo anchor("messages", lang('see_all')); ?>
                        </div>
                    </div>
                </li>
            <?php } ?>

            <li class="dropdown pr15 dropdown-user">
                <a id="user-dropdown-icon" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                    <span class="avatar-xs avatar pull-left mt-5 mr10">
                        <img alt="..." src="<?php echo get_avatar($this->login_user->image); ?>">
                    </span><span class="topbar-user-name"><?php echo $this->login_user->first_name . " " . $this->login_user->last_name; ?></span> <span class="caret"></span></a>
                <ul class="dropdown-menu p0" role="menu">
                    <?php if ($this->login_user->user_type == "client") { ?>
                        <!-- <li><?php //echo get_client_contact_profile_link($this->login_user->id . '/general', "<i class='fa fa-user mr10'></i>" . lang('my_profile')); 
                                    ?></li> -->
                        <li><?php echo get_client_contact_profile_link($this->login_user->id, "<i class='fa fa-user mr10'></i>" . lang('my_profile')); ?></li>
                        <li><?php echo get_client_contact_profile_link($this->login_user->id . '/account', "<i class='fa fa-key mr10'></i>" . lang('change_password')); ?></li>
                        <li><?php echo get_client_contact_profile_link($this->login_user->id . '/my_preferences', "<i class='fa fa-cog mr10'></i>" . lang('my_preferences')); ?></li>
                    <?php } else { ?>
                        <!-- <li><?php //echo get_team_member_profile_link($this->login_user->id . '/general', "<i class='fa fa-user mr10'></i>" . lang('my_profile')); 
                                    ?></li> -->
                        <li><?php echo get_team_member_profile_link($this->login_user->id, "<i class='fa fa-user mr10'></i>" . lang('my_profile')); ?></li>
                        <li><?php echo get_team_member_profile_link($this->login_user->id . '/account', "<i class='fa fa-key mr10'></i>" . lang('change_password')); ?></li>
                        <!-- <li><?php echo get_team_member_profile_link($this->login_user->id . '/my_preferences', "<i class='fa fa-cog mr10'></i>" . lang('my_preferences')); ?></li> -->
                    <?php } ?>

                    <!-- <li class="divider theme-changer-devider"></li>    
                    <li class="pl10 ml10  mt10 theme-changer">

                        <?php
                        //scan the css files for theme color and show a list
                        try {
                            $dir = getcwd() . '/assets/css/color/';
                            $files = scandir($dir);
                            if ($files && is_array($files)) {

                                echo "<span class='color-tag clickable mr15 change-theme' style='background:#1d2632'> </span>"; //default color

                                foreach ($files as $file) {
                                    if ($file != "." && $file != ".." && $file != "index.html") {
                                        $color_colde = str_replace(".css", "", $file);
                                        echo "<span class='color-tag clickable mr15 change-theme' style='background:#$color_colde' data-color='$color_colde'> </span>";
                                    }
                                }
                            }
                        } catch (Exception $exc) {
                        }
                        ?>

                    </li>

                    <li class="divider"></li> -->
                    <li><a href="<?php echo_uri('signin/sign_out'); ?>"><i class="fa fa-power-off mr10"></i> <?php echo lang('sign_out'); ?></a></li>
                </ul>
            </li>
        </ul>
    </div><!--/.nav-collapse -->
</nav>

<script type="text/javascript">
    //close navbar collapse panel on clicking outside of the panel
    $(document).click(function(e) {


        if (!$(e.target).is('#navbar') && isMobile()) {
            $('#navbar').collapse('hide');
        }
    });

    var notificationOptions = {};

    $(document).ready(function() {

        /*VALIDATATOR*/

        var current_url = window.location.href;
        var parts = current_url.split('/');
        var product_domain = parts[3];

        //console.log(product_domain);

        // $.ajax({
        //     url: "https://system.teamway.om/tasgeelxxx/index.php/saas_erp/check_subscription",
        //     type: 'POST',
        //     data: jQuery.param({
        //         product_domain: product_domain
        //     }),
        //     secure: true,
        //     headers: {
        //         'Access-Control-Allow-Origin': '*',
        //     },
        //     success: function(result) {
        //         if (result) {
        //             var resultObj = jQuery.parseJSON(result);

        //             if (resultObj.status == 'abt_expire') {
        //                 jQuery('#page-container').prepend(resultObj.html);
        //             } else if (resultObj.status == 'expired') {
        //                 jQuery('body').prepend(resultObj.html);
        //             }
        //         }




        //     }
        // });

        //load message notifications
        var messageOptions = {},
            $messageIcon = $("#message-notification-icon"),
            $notificationIcon = $("#web-notification-icon");

        //check message notifications
        messageOptions.notificationUrl = "<?php echo_uri('messages/get_notifications'); ?>";
        messageOptions.notificationStatusUpdateUrl = "<?php echo_uri('messages/update_notification_checking_status'); ?>";
        // messageOptions.checkNotificationAfterEvery = "<?php //echo get_setting('check_notification_after_every'); 
                                                            ?>";
        messageOptions.checkNotificationAfterEvery = "3000";
        messageOptions.icon = "fa-envelope-o";
        messageOptions.notificationSelector = $messageIcon;
        messageOptions.isMessageNotification = true;

        checkNotifications(messageOptions);

        window.updateLastMessageCheckingStatus = function() {
            checkNotifications(messageOptions, true);
        };

        $messageIcon.click(function() {
            checkNotifications(messageOptions, true);
        });




        //check web notifications
        notificationOptions.notificationUrl = "<?php echo_uri('notifications/count_notifications'); ?>";
        notificationOptions.notificationStatusUpdateUrl = "<?php echo_uri('notifications/update_notification_checking_status'); ?>";
        // notificationOptions.checkNotificationAfterEvery = "<?php echo get_setting('check_notification_after_every'); ?>";
        notificationOptions.checkNotificationAfterEvery = "3000";
        notificationOptions.icon = "fa-bell-o";
        notificationOptions.notificationSelector = $notificationIcon;
        notificationOptions.notificationType = "web";
        notificationOptions.pushNotification = "<?php echo get_setting("enable_push_notification") && $this->login_user->enable_web_notification && !get_setting('user_' . $this->login_user->id . '_disable_push_notification') ? true : false ?>";

        checkNotifications(notificationOptions); //start checking notification after starting the message checking 

        if (isMobile()) {
            //for mobile devices, load the notifications list with the page load
            notificationOptions.notificationUrlForMobile = "<?php echo_uri('notifications/get_notifications'); ?>";
            checkNotifications(notificationOptions);
        }

        $notificationIcon.click(function() {
            notificationOptions.notificationUrl = "<?php echo_uri('notifications/get_notifications'); ?>";
            checkNotifications(notificationOptions, true);
        });

        $('.top_tooltip').tooltip();
    });
</script>