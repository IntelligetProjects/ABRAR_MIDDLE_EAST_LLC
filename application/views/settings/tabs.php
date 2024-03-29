<?php


$settings_menu = array(
    "app_settings" => array(
        array("name" => "general", "url" => "settings/general"),
        //array("name" => "email", "url" => "settings/email"),
        //array("name" => "email_templates", "url" => "email_templates"),
        // array("name" => "modules", "url" => "settings/modules"),
        //array("name" => "cron_job", "url" => "settings/cron_job"),
        array("name" => "notifications", "url" => "settings/notifications"),
        //array("name" => "integration", "url" => "settings/integration"),
        //array("name" => "updates", "url" => "Updates"),
    ),
    "access_permission" => array(
        array("name" => "roles", "url" => "roles"),
        array("name" => "team", "url" => "team"),
    ),
    "client" => array(
        //array("name" => "client_permissions", "url" => "settings/client_permissions"),
        array("name" => "client_groups", "url" => "client_groups"),
        //array("name" => "dashboard", "url" => "dashboard/client_default_dashboard"),
    ),
    "setup" => array(
        //array("name" => "custom_fields", "url" => "custom_fields"),
        array("name" => "accounting_settings", "url" => "settings/accounting_settings"),
        array("name" => "tasks", "url" => "task_status"),
    )
);

//restricted settings
// if (get_setting("module_attendance") == "1") {
    // $settings_menu["access_permission"][] = array("name" => "ip_restriction", "url" => "settings/ip_restriction");
    // $settings_menu["access_permission"][] = array("name" => "attendance", "url" => "settings/attendance");
// }

if (get_setting("module_event") == "1") {
    // $settings_menu["setup"][] = array("name" => "events", "url" => "settings/events");
}

if (get_setting("module_leave") == "1") {
    $settings_menu["setup"][] = array("name" => "leave_types", "url" => "leave_types");
}

if (get_setting("module_ticket") == "1") {
    $settings_menu["setup"][] = array("name" => "tickets", "url" => "ticket_types");
}

if (get_setting("module_expense") == "1") {
    $settings_menu["setup"][] = array("name" => "expense_categories", "url" => "expense_categories");
}
if (get_setting("module_expense") == "1") {
    $settings_menu["setup"][] = array("name" => "expense_items", "url" => "expense_items");
}
if (get_setting("module_invoice") == "1") {
    $settings_menu["setup"][] = array("name" => "sale_items", "url" => "sale_items");
}
if(get_client_name()=="Adhwa_Sohar"){
$settings_menu["setup"][] = array("name" => "departments", "url" => "departments");
}
if (get_setting("module_invoice") == "1") {
    $settings_menu["setup"][] = array("name" => "invoices", "url" => "settings/invoices");
}

if (get_setting("module_estimate") == "1") {
    $settings_menu["setup"][] = array("name" => "estimates", "url" => "settings/estimates");
}
if (get_setting("module_invoice") == "1"||get_setting("module_estimate") == "1") {
    // $settings_menu["setup"][] = array("name" => "items_levels", "url" => "items_levels");
}
if (get_setting("module_invoice") == "1"||get_setting("module_estimate") == "1") {
    $settings_menu["setup"][] = array("name" => "nationality", "url" => "nationality");
}

//$settings_menu["setup"][] = array("name" => "payment_methods", "url" => "payment_methods");
$settings_menu["setup"][] = array("name" => "company", "url" => "settings/company");
$settings_menu["setup"][] = array("name" => "taxes", "url" => "taxes");
$settings_menu["setup"][] = array("name" => "currencies", "url" => "currencies");
$settings_menu["setup"][] = array("name" => "cost_centers", "url" => "cost_centers");

if (get_setting("module_lead") == "1") {
    $settings_menu["setup"][] = array("name" => "leads", "url" => "lead_status");
}
?>

<ul class="nav nav-tabs vertical settings" role="tablist">
    <?php
    foreach ($settings_menu as $key => $value) {

        //collapse the selected settings tab panel
        $collapse_in = "";
        $collapsed_class = "collapsed";
        if (in_array($active_tab, array_column($value, "name"))) {
            $collapse_in = "in";
            $collapsed_class = "";
        }
        ?>

        <div class="clearfix settings-anchor <?php echo $collapsed_class; ?>" data-toggle="collapse" data-target="#settings-tab-<?php echo $key; ?>">
            <?php echo lang($key); ?>
            <span id="fa-plus-square-o" class="pull-right"><i class="fa fa-plus-square-o"></i></span>
        </div>

        <?php
        echo "<div id='settings-tab-$key' class='collapse $collapse_in'>";
        echo "<ul class='list-group help-catagory'>";

        foreach ($value as $sub_setting) {
            $active_class = "";
            $setting_name = get_array_value($sub_setting, "name");
            $setting_url = get_array_value($sub_setting, "url");

            if ($active_tab == $setting_name) {
                $active_class = "active";
            }

            echo "<a href='" . get_uri($setting_url) . "' class='list-group-item $active_class'>" . lang($setting_name) . "</a>";
        }

        echo "</ul>";
        echo "</div>";
    }
    ?>
</ul>