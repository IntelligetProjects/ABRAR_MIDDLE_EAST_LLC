<?php
function get_client_name(){
$url = $_SERVER['REQUEST_URI'];
$parts = explode('/',$url);
return $parts[1];
echo $this->project_permission();
}
?>
<div id="sidebar" class="box-content ani-width">
    <div id="sidebar-scroll">
        <ul class="" id="sidebar-menu">
            <?php
            $dashboard_menu = array("name" => "dashboard", "url" => "dashboard", "class" => "fa-desktop dashboard-menu");

            $selected_dashboard_id = get_setting("user_" . $this->login_user->id . "_dashboard");
            if ($selected_dashboard_id) {
                $dashboard_menu = array("name" => "dashboard", "url" => "dashboard/view/" . $selected_dashboard_id, "class" => "fa-desktop dashboard-menu");
            }


            if ($this->login_user->user_type == "staff") {

                $sidebar_menu = array();

                $sidebar_menu[] = $dashboard_menu;

                $permissions = $this->login_user->permissions;

                /*$access_expense = get_array_value($permissions, "expense");
                $access_invoice = get_array_value($permissions, "invoice");
                $access_client = get_array_value($permissions, "client");
                $access_estimate = get_array_value($permissions, "estimate");
                $access_items = ($this->login_user->is_admin || $access_invoice || $access_estimate);*/
                ///////////////
                $can_manage_all_projects = get_array_value($permissions, "can_manage_all_projects");
                $access_contact = get_array_value($permissions, "can_access_contacts");
                $access_client = get_array_value($permissions, "can_access_clients");
                $access_invoice = get_array_value($permissions, "can_access_invoices");
                $access_invoice_return = get_array_value($permissions, "can_access_invoices_return");
                $access_estimate = get_array_value($permissions, "can_access_estimates");
                $access_supplier = get_array_value($permissions, "can_access_suppliers");
                $access_purchase_order = get_array_value($permissions, "can_access_purchase_orders");
                $access_delivery_note = get_array_value($permissions, "can_access_delivery_notes");
                $access_item = get_array_value($permissions, "can_access_items");
                $access_item_category = get_array_value($permissions, "can_access_items_category");
                $access_expense = get_array_value($permissions, "can_access_expenses");
                $access_expiries = get_array_value($permissions, "expiries");
                $access_invoice_payment = get_array_value($permissions, "can_access_invoice_payments");
                $can_access_purchase_order_payment = get_array_value($permissions, "can_access_purchase_order_payments");

                /////////////////
                $access_eroom = get_array_value($permissions, "eroom");
                $access_report = get_array_value($permissions, "reports");
                $access_log = get_array_value($permissions, "logs");
                $access_payroll = get_array_value($permissions, "payroll");
                $access_accounting = get_array_value($permissions, "accounting");
                $access_internal_transaction = get_array_value($permissions, "internal_transaction");
                $access_estimate_request = get_array_value($permissions, "estimate_request");

                ////////////////////
                $access_ticket = get_array_value($permissions, "ticket");
                $access_lead = get_array_value($permissions, "can_access_leads");
                $access_timecard = get_array_value($permissions, "attendance");
                $access_leave = get_array_value($permissions, "leave");
                $manage_help_and_knowledge_base = ($this->login_user->is_admin || get_array_value($permissions, "help_and_knowledge_base"));
                $accounting = ($this->login_user->is_admin || get_array_value($permissions, "accounting"));
                
                $can_view_salary_chart =  get_array_value($permissions, "can_view_salary_chart");

                $tools_submenu = array();

                // if ($this->login_user->is_admin || $access_contact) {
                $tools_submenu[] = array("name" => "phonebook", "url" => "contacts", "class" => "fa-group", "category" => "contacts"); 
                // }

                // if (get_setting("module_event") == "1") {
                    $tools_submenu[] = array("name" => "events", "url" => "events", "class" => "fa-calendar");
                // }

                if (get_setting("module_timeline") == "1") {
                    $tools_submenu[] = array("name" => "timeline", "url" => "timeline", "class" => " fa-comments font-18");
                }

                if (get_setting("module_message") == "1") {
                    $tools_submenu[] = array("name" => "messages", "url" => "messages", "class" => "fa-envelope", "devider" => true, "badge" => count_unread_message(), "badge_class" => "badge-secondary");
                }
                if (get_setting("module_message") == "1") {
                    $tools_submenu[] = array("name" => "email_messages", "url" => "email_messages", "class" => "fa-envelope", "devider" => true, "badge_class" => "badge-secondary");
                }

                if (get_setting("module_todo") == "1") {
                $tools_submenu[] = array("name" => "to_do", "url" => "todo", "class" => "fa-group", "category" => "todo");}


                if (get_setting("module_note") == "1") {
                    $tools_submenu[] = array("name" => "notes", "url" => "notes", "class" => "fa-book font-16");
                }
                

                if ($this->login_user->is_admin || $access_eroom) {
                $tools_submenu[] = array("name" => "eroom", "url" => "eroom", "class" => "fa-medium"); }
                // if(get_client_name()!='Creative_Knowledge_Land'){
            
                if(get_setting("module_expires") == "1"){
                    if (($this->login_user->is_admin || $access_expiries)) {
                $tools_submenu[] = array("name" => "expiries", "url" => "expires", "class" => "fa-medium"); 
                    }
                }
               
                
                if (get_setting("module_lead") == "1" && ($this->login_user->is_admin || $access_lead)) {
                    $sales_submenu[] = array("name" => "leads", "url" => "leads", "class" => "fa-cubes");
                    }

                if ($this->login_user->is_admin || $access_client) {
                    $sales_submenu[] = array("name" => "clients", "url" => "clients", "class" => "fa-briefcase");
                }


                if (get_setting("module_estimate") && ($this->login_user->is_admin || $access_estimate)) {
                $sales_submenu[] = array("name" => "estimates", "url" => "estimates", "class" => "fa-file");}
                if (get_setting("module_invoice") == "1" && ($this->login_user->is_admin || $access_invoice)) {
                    // $sales_submenu[] = array("name" => "Advance Payment Invoice", "url" => "proforma_invoices", "class" => "fa-file-text");
                }
                if (get_setting("module_invoice") == "1" && ($this->login_user->is_admin || $access_invoice)) {
                    $sales_submenu[] = array("name" => "invoices", "url" => "invoices", "class" => "fa-file-text");
                }
                if (get_setting("module_estimate") && ($this->login_user->is_admin || $access_estimate)) {
                    // $sales_submenu[] = array("name" => "budgeting", "url" => "budgeting", "class" => "fa-file");
                }
    

                if (get_setting("module_invoice") == "1" && ($this->login_user->is_admin || $access_invoice_payment)) {
                        $sales_submenu[] = array("name" => "invoice_payments", "url" => "invoice_payments");
                }

                if (get_setting("module_invoice") == "1" && ($this->login_user->is_admin || $access_delivery_note)) {
                    $sales_submenu[] = array("name" => "delivery_notes", "url" => "delivery_notes");
                }

                if (get_setting("module_invoice") == "1" && ($this->login_user->is_admin || $access_invoice_return)) {
                    $sales_submenu[] = array("name" => "sale_returns", "url" => "sale_returns", "class" => "fa-file-text");
                }

                // if (get_setting("module_estimate") && get_setting("module_estimate_request") && ($this->login_user->is_admin || $access_estimate)) {
                    // $sales_submenu[] = array("name" => "estimate_requests", "url" => "estimate_requests");
                    // if ($this->login_user->is_admin || $access_estimate_request == "all") {
                    // $sales_submenu[] = array("name" => "estimate_forms", "url" => "estimate_requests/estimate_forms");}
                // } 

                
                if(!empty($sales_submenu)) {
                    $sidebar_menu[] = array("name" => "sales", "url" => "", "class" => "fa-briefcase",
                        "submenu" => $sales_submenu
                    );
                }

                $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("projects", $this->login_user->is_admin, $this->login_user->user_type);
                $options = array(

                    // "statuses" => $statuses,
        
                    // "project_label" => $this->input->post("project_label"),
        
                    "custom_fields" => $custom_fields,
        
                    // "deadline" => $this->input->post('deadline'),
        
                );
                $list_data = $this->Projects_model->get_details($options)->result();
                // echo count($list_data);
//   $this->access_only_team_members();
//                 if($this->login_user->is_admin || $can_manage_all_projects){
                $project_submenu = array(
                    array("name" => "all_projects", "url" => "projects/all_projects"),
                    array("name" => "tasks", "url" => "projects/all_tasks")/*,
                    array("name" => "gantt", "url" => "projects/all_gantt")*/);
                // if (get_setting("module_project_timesheet")) {
                //     $project_submenu[] = array("name" => "timesheets", "url" => "projects/all_timesheets");
                // }
                //  }
                // echo get_array_value($this->login_user->permissions, "can_manage_all_projects"); die('test');
                if(get_array_value($this->login_user->permissions, "can_manage_all_projects") == "1"){
                    $project_permission = true;
                }elseif (!$this->login_user->is_admin) {
                    $options["user_id"] = $this->login_user->id;
                    $list_data = $this->Projects_model->get_details($options)->result();
                    if($list_data){
                        $project_permission = true;
                    }else{
                        $project_permission = false;
                    }
                }else{
                    $project_permission =true;
                }
                if(!empty($project_submenu)&&$project_permission) {
                $sidebar_menu[] = array("name" => "projects", "url" => "projects", "class" => "fa-th-large",
                    "submenu" => $project_submenu , "tip" => ""
                );
                }



                /*if (get_setting("module_estimate") && get_setting("module_estimate_request") && ($this->login_user->is_admin || $access_estimate)) {

                    if (get_setting("module_lead") == "1" && ($this->login_user->is_admin || $access_lead)) {
                    $estq[] = array("name" => "leads", "url" => "leads", "class" => "fa-cubes");
                    }

                    $estq[] = array("name" => "estimate_list", "url" => "estimates");

                    $estq[] = array("name" => "estimate_requests", "url" => "estimate_requests");

                    if ($this->login_user->is_admin || $access_estimate_request == "all") {
                    $estq[] = array("name" => "estimate_forms", "url" => "estimate_requests/estimate_forms");}

                    $sidebar_menu[] = array("name" => "estimates", "url" => "estimates", "class" => "fa-file",
                        "submenu" => $estq
                        );
                } else if (get_setting("module_estimate") && ($this->login_user->is_admin || $access_estimate)) {

                    if (get_setting("module_lead") == "1" && ($this->login_user->is_admin || $access_lead)) {
                    $sidebar_menu[] = array("name" => "leads", "url" => "leads", "class" => "fa-cubes");
                    }

                    $sidebar_menu[] = array("name" => "estimates", "url" => "estimates", "class" => "fa-file");
                }*/


                
                    $finance_submenu = array();
                    $finance_url = "";
                    $show_payments_menu = false;
                    $show_expenses_menu = false;


                    /*if (get_setting("module_invoice") == "1" && ($this->login_user->is_admin || $access_invoice_payment)) {
                        $finance_submenu[] = array("name" => "invoice_payments", "url" => "invoice_payments");
                        $finance_url = "invoice_payments";
                        $show_payments_menu = true;
                    }*/

                    /*if (($this->login_user->is_admin || $can_access_purchase_order_payment)) {
                    $finance_submenu[] = array("name" => "purchase_order_payments", "url" => "purchase_order_payments", "class" => "fa-list-ul");
                    $finance_url = "purchase_order_payments";
                        }*/

                    if (get_setting("module_expense") == "1" && ($this->login_user->is_admin || $access_expense)) {
                        $finance_submenu[] = array("name" => "expenses_list", "url" => "expenses");
                        
                        //TODO: for test accout only remove IF statement  if changes in database ready for all clients
                        if ($this->login_user->is_admin && (strcasecmp($this->db->dbprefix,'test_teamway') == 0 || strcasecmp($this->db->dbprefix,'tarteeb_v3') == 0 ))
                        {
                        $finance_submenu[] = array("name" => "service_provider", "url" => "service_provider");
                        }

                        $finance_url = "expenses";
                        $show_expenses_menu = true;
                    }
                    

                    
                    // if(get_client_name()!='Creative_Knowledge_Land'){
                    if(get_setting("module_petty_cash") == "1"){
                    if (get_setting("module_expense") == "1" && ($this->login_user->is_admin || $access_expense)) {
                        $finance_submenu[] = array("name" => "pt_cash", "url" => "PT_cash", "category" => "PT_cash");
                        $show_expenses_menu = true;
                    }
                    }


                    /*if ($show_expenses_menu && $show_payments_menu) {
                        $finance_submenu[] = array("name" => "income_vs_expenses", "url" => "expenses/income_vs_expenses");
                    }*/

                if (!empty($finance_submenu)) {

                    $sidebar_menu[] = array("name" => "expenses", "url" => $finance_url, "class" => "fa-money", "submenu" => $finance_submenu);
                }

                $admin_submenu = array();
                // if(get_client_name()=='Adhwa_Sohar'){
                if(get_setting("module_expires") == "1"){
                $admin_submenu[] = array("name" => "expires", "url" => "expires", "class" => "fa-comments", "category" => "expires");
                }
 

                if(!empty($admin_submenu)) {
                    $sidebar_menu[] = array("name" => "administration", "url" => "", "class" => "fa-cube",
                        "submenu" => $admin_submenu
                    );
                }


                $accounting_submenu = array();


                if (($this->login_user->is_admin || $access_accounting)) {
                $accounting_submenu[] = array("name" => "chart_of_accounts", "url" => "accounts", "class" => "fa-list-ul", "category" => "accounts");}

                if (($this->login_user->is_admin || $access_accounting)) {
                $accounting_submenu[] = array("name" => "accounts_list", "url" => "accounts/accounts_list", "class" => "fa-list-ul", "category" => "accounts/accounts_list");}

                if (($this->login_user->is_admin || $access_accounting)) {
                // $accounting_submenu[] = array("name" => "account_statements", "url" => "accounts/view", "class" => "fa-list-ul", "category" => "accounts/view");
                }

                if (($this->login_user->is_admin || $access_accounting)) {
                $accounting_submenu[] = array("name" => "general_journal_entries", "url" => "transactions", "class" => "fa-list-ul", "category" => "transactions");
                }

                if (($this->login_user->is_admin || $access_accounting)) {
                $accounting_submenu[] = array("name" => "banking", "url" => "banking", "class" => "fa-list-ul");
                }

                if (($this->login_user->is_admin || $access_accounting)) {
                    $accounting_submenu[] = array("name" => "treasury", "url" => "treasury", "class" => "fa-list-ul");
                }    
                
                if (($this->login_user->is_admin || $access_accounting)) {
                $accounting_submenu[] = array("name" => "cheques", "url" => "cheques", "class" => "fa-list-ul");
            }

                /*if (get_setting("module_expense") == "1" && ($this->login_user->is_admin || $access_accounting)) {
                        $accounting_submenu[] = array("name" => "internal_transactions", "url" => "internal_transactions");
                    }*/

                if (($this->login_user->is_admin || $access_accounting)) {
                        $accounting_submenu[] = array("name" => "financial_reports", "url" => "accounting_reports", "class" => "fa-list-ul");}

                // VAT REPORT 
                if ($this->login_user->is_admin && (strcasecmp($this->db->dbprefix,'test_teamway') == 0 || strcasecmp($this->db->dbprefix,'tarteeb_v3') == 0 )) {
                    $accounting_submenu[] = array("name" => "Vat Report", "url" => "vat_report", "class" => "fa fa-file");
                }
                if (!empty($accounting_submenu)) {
                    $sidebar_menu[] = array("name" => "accounting", "url" => "accounts", "class" => "fa-bank",
                    "submenu" => $accounting_submenu);
                }


                $inventory_submenu = array();

                if ($access_item || $this->login_user->is_admin) {
                    $inventory_submenu[] = array("name" => "items", "url" => "items", "class" => "fa-list-ul");
                }
               

                // if (($access_item && $access_purchase_order )|| $this->login_user->is_admin) {
                if ($access_item_category || $this->login_user->is_admin) {
                $inventory_submenu[] = array("name" => "item_categories", "url" => "item_categories");
                }
               

                if ($access_supplier || $this->login_user->is_admin) {
                $inventory_submenu[] = array("name" => "suppliers", "url" => "suppliers", "class" => "fa-list-ul");}
              
                // if ($access_purchase_order || $this->login_user->is_admin) {
                //     $inventory_submenu[] = array("name" => "material_request", "url" => "material_request", "class" => "fa-file");}
    
                if ($access_purchase_order || $this->login_user->is_admin) {
                $inventory_submenu[] = array("name" => "purchase_orders", "url" => "purchase_orders", "class" => "fa-list-ul");}

                if (($this->login_user->is_admin || $can_access_purchase_order_payment)) {
                    $inventory_submenu[] = array("name" => "purchase_order_payments", "url" => "purchase_order_payments", "class" => "fa-list-ul");
                        }

                if ($access_purchase_order || $this->login_user->is_admin) {
                $inventory_submenu[] = array("name" => "shipments", "url" => "shipments", "class" => "fa-list-ul");}

                if ($access_purchase_order || $this->login_user->is_admin) {
                $inventory_submenu[] = array("name" => "purchase_returns", "url" => "purchase_returns", "class" => "fa-list-ul");}

                if (!empty($inventory_submenu)) {
                    $sidebar_menu[] = array("name" => "inventory", "url" => "items", "class" => "fa-dropbox",
                    "submenu" => $inventory_submenu);
                }

                $hr_submenu = array();


                if (get_array_value($this->login_user->permissions, "hide_team_members_list") != "1") {
                    $hr_submenu[] = array("name" => "team_members", "url" => "team_members", "class" => "fa-user font-16");
                }
                /*if (get_array_value($this->login_user->permissions, "hide_team_members_list") != "1") {
                    $hr_submenu[] = array("name" => "org_chart", "url" => "team_members/org_chart", "class" => "fa-user font-16");
                }*/



                if (get_setting("module_attendance") == "1" && ($this->login_user->is_admin || $access_timecard)) {
                    $hr_submenu[] = array("name" => "attendance", "url" => "attendance", "class" => "fa-clock-o font-16");
                } else if (get_setting("module_attendance") == "1") {
                    $hr_submenu[] = array("name" => "attendance", "url" => "attendance/attendance_info", "class" => "fa-clock-o font-16");
                }

                if (get_setting("module_leave") == "1" && ($this->login_user->is_admin || $access_leave)) {
                    $hr_submenu[] = array("name" => "leaves", "url" => "leaves", "class" => "fa-sign-out font-16", "devider" => true);
                } else if (get_setting("module_leave") == "1") {
                    $hr_submenu[] = array("name" => "leaves", "url" => "leaves/leave_info", "class" => "fa-sign-out font-16", "devider" => true);
                }
// echo et_setting("module_announcement"); die('For Testing');
                if (get_setting("announcement_permission") == "1" || $this->login_user->is_admin ) {
                    $hr_submenu[] = array("name" => "announcements", "url" => "announcements", "class" => "fa-bullhorn");
                }

                if (get_setting("module_ticket") == "1" && ($this->login_user->is_admin || $access_ticket)) {

                    $ticket_badge = 0;
                    if ($this->login_user->is_admin || $access_ticket === "all") {
                        $ticket_badge = count_new_tickets();
                    } else if ($access_ticket === "specific") {
                        $specific_ticket_permission = get_array_value($permissions, "ticket_specific");
                        $ticket_badge = count_new_tickets($specific_ticket_permission);
                    }

                    // $hr_submenu[] = array("name" => "tickets", "url" => "tickets", "class" => "fa-life-ring", "devider" => true, "badge" => $ticket_badge, "badge_class" => "badge-secondary");
                }


                /* HR MENUS*/
                if (($this->login_user->is_admin || $can_view_salary_chart)) {
                    // $hr_submenu[] = array("name" => "salary_advance", "url" => "salary_advance", "class" => "fa-file");
                    // $hr_submenu[] = array("name" => "loans", "url" => "loans", "class" => "fa-file");
                    $hr_submenu[] = array("name" => "salary_chart", "url" => "team_members/employees_salary_chart", "class" => "fa-file");
                    // $hr_submenu[] = array("name" => "payroll", "url" => "payroll", "class" => "fa-file");
                }

                if ($access_payroll || $this->login_user->is_admin) {
                $hr_submenu[] = array("name" => "payroll", "url" => "payroll", "class" => "fa-credit-card");}

                if(!empty($hr_submenu)) {
                    $sidebar_menu[] = array("name" => "hr", "url" => "team_members", "class" => "fa-user",
                        "submenu" => $hr_submenu);
                }

                $managment_submenu = array();

                // if ($access_report || $this->login_user->is_admin) {
                // $managment_submenu[] = array("name" => "reports", "url" => "reports");
                // }
                // if (($this->login_user->is_admin || $managment_submenu)) {
                // $managment_submenu[] = array("name" => "cheques", "url" => "cheques", "class" => "fa-list-ul");}
                
                if ($access_log || $this->login_user->is_admin) {
                $managment_submenu[] = array("name" => "logs", "url" => "log", "class" => "fa-credit-card", "category" => "log");}
                if ($this->login_user->is_admin) {
                    $managment_submenu[] = array("name" => "settings", "url" => "settings/general", "class" => "fa-wrench");
                }

                if(!empty($managment_submenu)) {
                    $sidebar_menu[] = array("name" => "management", "url" => "reports", "class" => "fa-black-tie",
                        "submenu" => $managment_submenu);
                }

                if(!empty($tools_submenu)) {
                    $sidebar_menu[] = array("name" => "utilities", "url" => "notes", "class" => "fa-asterisk",
                        "submenu" => $tools_submenu
                    );
                }




                $module_help = get_setting("module_help") == "1" ? true : false;
                $module_knowledge_base = get_setting("module_knowledge_base") == "1" ? true : false;

                //prepere the help and suppor menues
                if ($module_help || $module_knowledge_base) {

                    $help_knowledge_base_menues = array();
                    $main_url = "help";

                    if ($module_help) {
                        $help_knowledge_base_menues[] = array("name" => "help", "url" => $main_url);
                    }

                    //push the help manage menu if user has access
                    if ($manage_help_and_knowledge_base && $module_help) {
                        $help_knowledge_base_menues[] = array("name" => "articles", "url" => "help/help_articles");
                        $help_knowledge_base_menues[] = array("name" => "categories", "url" => "help/help_categories");
                    }

                    if ($module_knowledge_base) {
                        $help_knowledge_base_menues[] = array("name" => "knowledge_base", "url" => "knowledge_base");
                    }

                    //push the knowledge_base manage menu if user has access
                    if ($manage_help_and_knowledge_base && $module_knowledge_base) {
                        $help_knowledge_base_menues[] = array("name" => "articles", "category" => "help", "url" => "help/knowledge_base_articles");
                        $help_knowledge_base_menues[] = array("name" => "categories", "category" => "help", "url" => "help/knowledge_base_categories");
                    }


                    if (!$module_help) {
                        $main_url = "knowledge_base";
                    }

                    $sidebar_menu[] = array("name" => "knowledge_base", "url" => $main_url, "class" => "fa-spinner",
                        "submenu" => $help_knowledge_base_menues
                    );
                }

                /*if ($this->login_user->is_admin) {
                    $sidebar_menu[] = array("name" => "settings", "url" => "settings/general", "class" => "fa-wrench");
                }*/
            } else {
                //client menu
                //get the array of hidden menu
                $hidden_menu = explode(",", get_setting("hidden_client_menus"));

                $sidebar_menu[] = $dashboard_menu;

                if (get_setting("module_event") == "1" && !in_array("events", $hidden_menu)) {
                    $sidebar_menu[] = array("name" => "events", "url" => "events", "class" => "fa-calendar");
                }

                //check message access settings for clients
                if (get_setting("module_message") && get_setting("client_message_users")) {
                    $sidebar_menu[] = array("name" => "messages", "url" => "messages", "class" => "fa-envelope", "badge" => count_unread_message());
                }

                if (!in_array("projects", $hidden_menu)) {
                    $sidebar_menu[] = array("name" => "projects", "url" => "projects/all_projects", "class" => "fa fa-th-large");
                }


                if (get_setting("module_estimate") && !in_array("estimates", $hidden_menu)) {
                    $sidebar_menu[] = array("name" => "estimates", "url" => "estimates", "class" => "fa-file");
                }
                if (get_setting("module_estimate") && !in_array("estimates", $hidden_menu)) {
                    $sidebar_menu[] = array("name" => "material_request", "url" => "material_request", "class" => "fa-file");
                }

                if (get_setting("module_invoice") == "1") {
                    if (!in_array("invoices", $hidden_menu)) {
                        // $sidebar_menu[] = array("name" => "Advance Payment Invoice", "url" => "proforma_invoices", "class" => "fa-file-text");
                    }
                    if (!in_array("invoices", $hidden_menu)) {
                        $sidebar_menu[] = array("name" => "invoices", "url" => "invoices", "class" => "fa-file-text");
                    }
                   
                    if (!in_array("payments", $hidden_menu)) {
                        $sidebar_menu[] = array("name" => "invoice_payments", "url" => "invoice_payments", "class" => "fa-money");
                    }
                }
                

                if (get_setting("module_ticket") == "1" && !in_array("tickets", $hidden_menu)) {
                    $sidebar_menu[] = array("name" => "tickets", "url" => "tickets", "class" => "fa-life-ring");
                }

                if (get_setting("module_announcement") == "1" && !in_array("announcements", $hidden_menu)) {
                    $sidebar_menu[] = array("name" => "announcements", "url" => "announcements", "class" => "fa-bullhorn");
                }
                

                $sidebar_menu[] = array("name" => "users", "url" => "clients/users", "class" => "fa-user");
                $sidebar_menu[] = array("name" => "my_profile", "url" => "clients/contact_profile/" . $this->login_user->id, "class" => "fa-cog");

                if (get_setting("module_knowledge_base") == "1" && !in_array("knowledge_base", $hidden_menu)) {
                    $sidebar_menu[] = array("name" => "knowledge_base", "url" => "knowledge_base", "class" => "fa-question-circle");
                }
                
                


            }

            foreach ($sidebar_menu as $main_menu) {
                $submenu = get_array_value($main_menu, "submenu");
                $expend_class = $submenu ? " expand " : "";
                $active_class = active_menu($main_menu['name'], $submenu);
                $submenu_open_class = "";
                //TIPS
                $tip = get_array_value($main_menu, "tip");
                
                if ($expend_class && $active_class) {
                    $submenu_open_class = " open ";
                }

                $devider_class = get_array_value($main_menu, "devider") ? "devider" : "";
                $badge = get_array_value($main_menu, "badge");
                $badge_class = get_array_value($main_menu, "badge_class");
                ?>
                <li data-tip_message="<?php echo lang($tip); ?>" class="<?php echo $active_class . " " . $expend_class . " " . $submenu_open_class . " $devider_class"; ?> main">
                    <a href="<?php echo_uri($main_menu['url']); ?>">
                        <i class="fa <?php echo ($main_menu['class']); ?>"></i>
                        <span><?php echo lang($main_menu['name']); ?></span>
                        <?php
                        if ($badge) {
                            echo "<span class='badge $badge_class'>$badge</span>";
                        }
                        ?>
                    </a>
                    <?php
                    if ($submenu) {
                        echo "<ul>";
                        foreach ($submenu as $s_menu) {
                            ?>
                        <li>
                            <a href="<?php echo_uri($s_menu['url']); ?>">
                                <i class="dot fa fa-circle"></i>
                                <span><?php echo lang($s_menu['name']); ?></span>
                            </a>
                        </li>
                        <?php
                    }
                    echo "</ul>";
                }
                ?>
                </li>
            <?php } ?>
        </ul>
        <div style="width:100%;">
            <img src="<?= base_url().'/assets/images/omantel.png'?>" style="width: 60%; margin-left: 30px;">
        </div>
        
    </div>
    <!-- Modal -->
<div style="margin-top: 100px;" class="modal fade" id="tip" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php echo lang("tips"); ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       tips
      </div>
      <div class="modal-footer">
    <button onclick="setDoNotShowTipsAgain()" type="button" class="btn btn-default"><span class="fa fa-close"></span> <?php echo lang('stop_showing_tips'); ?></button>
    <button type="button" class="btn btn-primary" data-dismiss="modal"><span class="fa fa-check-circle"></span> <?php echo lang('ok'); ?></button>
</div>
    </div>
  </div>
</div>

</div><!-- sidebar menu end -->
<script type="text/javascript">
    window.onload = function () {
        if(localStorage.getItem("logged_in_before") === null){
            localStorage.setItem("logged_in_before","true");
            localStorage.setItem("show_tips","true");
        }else{
            localStorage.setItem("show_tips","false");
        }
    };

    function setDoNotShowTipsAgain(){
        $('#tip').modal('hide');
        localStorage.setItem("show_tips","false");
    }
</script>