<?php
// this is test 
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class roles extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->access_only_admin();
    }

    //load the role view
    function index() {
        $this->template->rander("roles/index");
    }

    //load the role add/edit modal
    function modal_form() {

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Roles_model->get_one($this->input->post('id'));
        $view_data['roles_dropdown'] = array("" => "-") + $this->Roles_model->get_dropdown_list(array("title"), "id");
        $this->load->view('roles/modal_form', $view_data);
    }

    //get permisissions of a role
    function permissions($role_id) {
        if ($role_id) {
            $view_data['model_info'] = $this->Roles_model->get_one($role_id);

            $view_data['members_and_teams_dropdown'] = json_encode(get_team_members_and_teams_select2_data_list());
            $ticket_types_dropdown = array();
            $ticket_types = $this->Ticket_types_model->get_all_where(array("deleted" => 0))->result();
            foreach ($ticket_types as $type) {
                $ticket_types_dropdown[] = array("id" => $type->id, "text" => $type->title);
            }
            $view_data['ticket_types_dropdown'] = json_encode($ticket_types_dropdown);

            $permissions = unserialize($view_data['model_info']->permissions);

            if (!$permissions) {
                $permissions = array();
            }

            $view_data['leave'] = get_array_value($permissions, "leave");
            $view_data['leave_specific'] = get_array_value($permissions, "leave_specific");
            $view_data['attendance_specific'] = get_array_value($permissions, "attendance_specific");

            $view_data['attendance'] = get_array_value($permissions, "attendance");
            $view_data['invoice'] = get_array_value($permissions, "invoice");
            $view_data['job_info'] = get_array_value($permissions, "job_info");
            $view_data['account_setting'] = get_array_value($permissions, "account_setting");
            $view_data['final_settelment'] = get_array_value($permissions, "final_settelment");
            $view_data['estimate'] = get_array_value($permissions, "estimate");
            $view_data['expense'] = get_array_value($permissions, "expense");
            $view_data['client'] = get_array_value($permissions, "client");
            $view_data['lead'] = get_array_value($permissions, "lead");

            $view_data['accounting'] = get_array_value($permissions, "accounting");
            $view_data['payroll'] = get_array_value($permissions, "payroll");
            $view_data['eroom'] = get_array_value($permissions, "eroom");
            $view_data['logs'] = get_array_value($permissions, "logs");
            $view_data['reports'] = get_array_value($permissions, "reports");
            
            $view_data['ticket'] = get_array_value($permissions, "ticket");
            $view_data['ticket_specific'] = get_array_value($permissions, "ticket_specific"); 
            
            $view_data['announcement'] = get_array_value($permissions, "announcement");
            $view_data['approve_budgeting'] = get_array_value($permissions, "approve_budgeting");
            $view_data['help_and_knowledge_base'] = get_array_value($permissions, "help_and_knowledge_base");

            $view_data['can_manage_all_projects'] = get_array_value($permissions, "can_manage_all_projects");
            $view_data['can_create_projects'] = get_array_value($permissions, "can_create_projects");
            $view_data['can_edit_projects'] = get_array_value($permissions, "can_edit_projects");
            $view_data['can_delete_projects'] = get_array_value($permissions, "can_delete_projects");

            $view_data['can_add_remove_project_members'] = get_array_value($permissions, "can_add_remove_project_members");

            $view_data['can_create_tasks'] = get_array_value($permissions, "can_create_tasks");
            $view_data['can_edit_tasks'] = get_array_value($permissions, "can_edit_tasks");
            $view_data['can_delete_tasks'] = get_array_value($permissions, "can_delete_tasks");
            $view_data['can_comment_on_tasks'] = get_array_value($permissions, "can_comment_on_tasks");
            $view_data['show_assigned_tasks_only'] = get_array_value($permissions, "show_assigned_tasks_only");

            $view_data['can_create_milestones'] = get_array_value($permissions, "can_create_milestones");
            $view_data['can_edit_milestones'] = get_array_value($permissions, "can_edit_milestones");
            $view_data['can_delete_milestones'] = get_array_value($permissions, "can_delete_milestones");

            $view_data['can_delete_files'] = get_array_value($permissions, "can_delete_files");

            $view_data['can_view_team_members_contact_info'] = get_array_value($permissions, "can_view_team_members_contact_info");
            $view_data['can_view_team_members_social_links'] = get_array_value($permissions, "can_view_team_members_social_links");
            $view_data['team_member_update_permission'] = get_array_value($permissions, "team_member_update_permission");
            $view_data['team_member_update_permission_specific'] = get_array_value($permissions, "team_member_update_permission_specific");

            $view_data['timesheet_manage_permission'] = get_array_value($permissions, "timesheet_manage_permission");
            $view_data['timesheet_manage_permission_specific'] = get_array_value($permissions, "timesheet_manage_permission_specific");

            $view_data['disable_event_sharing'] = get_array_value($permissions, "disable_event_sharing");
            
            $view_data['hide_team_members_list'] = get_array_value($permissions, "hide_team_members_list");

            $view_data['can_delete_leave_application'] = get_array_value($permissions, "can_delete_leave_application");
            $view_data['can_add_team_member'] = get_array_value($permissions, "can_add_team_member");
            $view_data['can_view_salary_chart'] = get_array_value($permissions, "can_view_salary_chart");

            ///////////////////////////////////////////////////////////////////////////

            $view_data['can_access_suppliers'] = get_array_value($permissions, "can_access_suppliers");
            $view_data['can_create_suppliers'] = get_array_value($permissions, "can_create_suppliers");
            $view_data['can_edit_suppliers'] = get_array_value($permissions, "can_edit_suppliers");
            $view_data['can_delete_suppliers'] = get_array_value($permissions, "can_delete_suppliers");
            $view_data['supplier_manage_permission'] = get_array_value($permissions, "supplier_manage_permission");
            $view_data['supplier_manage_permission_specific'] = get_array_value($permissions, "supplier_manage_permission_specific");

            $view_data['can_access_purchase_orders'] = get_array_value($permissions, "can_access_purchase_orders");
            $view_data['can_create_purchase_orders'] = get_array_value($permissions, "can_create_purchase_orders");
            $view_data['can_edit_purchase_orders'] = get_array_value($permissions, "can_edit_purchase_orders");
            $view_data['can_delete_purchase_orders'] = get_array_value($permissions, "can_delete_purchase_orders");
            $view_data['purchase_order_manage_permission'] = get_array_value($permissions, "purchase_order_manage_permission");
            $view_data['purchase_order_manage_permission_specific'] = get_array_value($permissions, "purchase_order_manage_permission_specific");

            $view_data['can_access_delivery_notes'] = get_array_value($permissions, "can_access_delivery_notes");
            $view_data['can_create_delivery_notes'] = get_array_value($permissions, "can_create_delivery_notes");
            $view_data['can_edit_delivery_notes'] = get_array_value($permissions, "can_edit_delivery_notes");
            $view_data['can_delete_delivery_notes'] = get_array_value($permissions, "can_delete_delivery_notes");
            $view_data['delivery_note_manage_permission'] = get_array_value($permissions, "delivery_note_manage_permission");
            $view_data['delivery_note_manage_permission_specific'] = get_array_value($permissions, "delivery_note_manage_permission_specific");

            /*$view_data['can_access_dispatches'] = get_array_value($permissions, "can_access_dispatches");
            $view_data['can_create_dispatches'] = get_array_value($permissions, "can_create_dispatches");
            $view_data['can_edit_dispatches'] = get_array_value($permissions, "can_edit_dispatches");
            $view_data['can_delete_dispatches'] = get_array_value($permissions, "can_delete_dispatches");
            $view_data['dispatch_manage_permission'] = get_array_value($permissions, "dispatch_manage_permission");
            $view_data['dispatch_manage_permission_specific'] = get_array_value($permissions, "dispatch_manage_permission_specific");

            $view_data['can_access_shipments'] = get_array_value($permissions, "can_access_shipments");
            $view_data['can_create_shipments'] = get_array_value($permissions, "can_create_shipments");
            $view_data['can_edit_shipments'] = get_array_value($permissions, "can_edit_shipments");
            $view_data['can_delete_shipments'] = get_array_value($permissions, "can_delete_shipments");
            $view_data['shipment_manage_permission'] = get_array_value($permissions, "shipment_manage_permission");
            $view_data['shipment_manage_permission_specific'] = get_array_value($permissions, "shipment_manage_permission_specific");*/

            $view_data['can_access_items'] = get_array_value($permissions, "can_access_items");
            $view_data['can_create_items'] = get_array_value($permissions, "can_create_items");
            $view_data['can_edit_items'] = get_array_value($permissions, "can_edit_items");
            $view_data['can_delete_items'] = get_array_value($permissions, "can_delete_items");
            $view_data['item_manage_permission'] = get_array_value($permissions, "item_manage_permission");
            $view_data['item_manage_permission_specific'] = get_array_value($permissions, "item_manage_permission_specific");
            
            $view_data['can_access_items_category'] = get_array_value($permissions, "can_access_items_category");
            $view_data['can_create_items_category'] = get_array_value($permissions, "can_create_items_category");
            $view_data['can_edit_items_category'] = get_array_value($permissions, "can_edit_items_category");
            $view_data['can_delete_items_category'] = get_array_value($permissions, "can_delete_items_category");
            $view_data['item_category_manage_permission'] = get_array_value($permissions, "item_category_manage_permission");
            $view_data['item_category_manage_permission_specific'] = get_array_value($permissions, "item_category_manage_permission_specific");

            /////

            $view_data['can_access_expiries'] = get_array_value($permissions, "can_access_expiries");
            $view_data['can_access_petty_cash'] = get_array_value($permissions, "can_access_petty_cash");
            $view_data['can_access_expenses'] = get_array_value($permissions, "can_access_expenses");
            $view_data['can_create_expenses'] = get_array_value($permissions, "can_create_expenses");
            $view_data['can_edit_expenses'] = get_array_value($permissions, "can_edit_expenses");
            $view_data['can_delete_expenses'] = get_array_value($permissions, "can_delete_expenses");
            $view_data['expense_manage_permission'] = get_array_value($permissions, "expense_manage_permission");
            $view_data['expense_manage_permission_specific'] = get_array_value($permissions, "expense_manage_permission_specific");

            $view_data['internal_transaction'] = get_array_value($permissions, "internal_transaction");


            $view_data['can_access_invoice_payments'] = get_array_value($permissions, "can_access_invoice_payments");
            $view_data['can_create_invoice_payments'] = get_array_value($permissions, "can_create_invoice_payments");
            $view_data['can_edit_invoice_payments'] = get_array_value($permissions, "can_edit_invoice_payments");
            $view_data['can_delete_invoice_payments'] = get_array_value($permissions, "can_delete_invoice_payments");

            $view_data['can_access_purchase_order_payments'] = get_array_value($permissions, "can_access_purchase_order_payments");
            $view_data['can_create_purchase_order_payments'] = get_array_value($permissions, "can_create_purchase_order_payments");
            $view_data['can_edit_purchase_order_payments'] = get_array_value($permissions, "can_edit_purchase_order_payments");
            $view_data['can_delete_purchase_order_payments'] = get_array_value($permissions, "can_delete_purchase_order_payments");

            ////////////////////////////
             $view_data['can_access_contacts'] = get_array_value($permissions, "can_access_contacts");
            $view_data['can_create_contacts'] = get_array_value($permissions, "can_create_contacts");
            $view_data['can_edit_contacts'] = get_array_value($permissions, "can_edit_contacts");
            $view_data['can_delete_contacts'] = get_array_value($permissions, "can_delete_contacts");
            $view_data['contact_manage_permission'] = get_array_value($permissions, "contact_manage_permission");
            $view_data['contact_manage_permission_specific'] = get_array_value($permissions, "contact_manage_permission_specific");



            $view_data['can_access_clients'] = get_array_value($permissions, "can_access_clients");
            $view_data['can_create_clients'] = get_array_value($permissions, "can_create_clients");
            $view_data['can_edit_clients'] = get_array_value($permissions, "can_edit_clients");
            $view_data['can_delete_clients'] = get_array_value($permissions, "can_delete_clients");
            $view_data['client_manage_permission'] = get_array_value($permissions, "client_manage_permission");
            $view_data['client_manage_permission_specific'] = get_array_value($permissions, "client_manage_permission_specific");
            
            
            $view_data['can_access_leads'] = get_array_value($permissions, "can_access_leads");
            $view_data['can_create_leads'] = get_array_value($permissions, "can_create_leads");
            $view_data['can_edit_leads'] = get_array_value($permissions, "can_edit_leads");
            $view_data['can_delete_leads'] = get_array_value($permissions, "can_delete_leads");
            $view_data['leads_manage_permission'] = get_array_value($permissions, "leads_manage_permission");
            $view_data['leads_manage_permission_specific'] = get_array_value($permissions, "leads_manage_permission_specific");


            $view_data['can_access_invoices'] = get_array_value($permissions, "can_access_invoices");
            $view_data['can_create_invoices'] = get_array_value($permissions, "can_create_invoices");
            $view_data['can_edit_invoices'] = get_array_value($permissions, "can_edit_invoices");
            $view_data['can_delete_invoices'] = get_array_value($permissions, "can_delete_invoices");
            $view_data['invoice_manage_permission'] = get_array_value($permissions, "invoice_manage_permission");
            $view_data['invoice_manage_permission_specific'] = get_array_value($permissions, "invoice_manage_permission_specific");
           

            $view_data['can_access_invoices_return'] = get_array_value($permissions, "can_access_invoices_return");
            $view_data['can_create_invoices_return'] = get_array_value($permissions, "can_create_invoices_return");
            $view_data['can_edit_invoices_return'] = get_array_value($permissions, "can_edit_invoices_return");
            $view_data['can_delete_invoices_return'] = get_array_value($permissions, "can_delete_invoices_return");
            $view_data['invoice_return_manage_permission'] = get_array_value($permissions, "invoice_return_manage_permission");
            $view_data['invoice_return_manage_permission_specific'] = get_array_value($permissions, "invoice_return_manage_permission_specific");


            $view_data['can_access_estimates'] = get_array_value($permissions, "can_access_estimates");
            $view_data['can_create_estimates'] = get_array_value($permissions, "can_create_estimates");
            $view_data['can_edit_estimates'] = get_array_value($permissions, "can_edit_estimates");
            $view_data['can_delete_estimates'] = get_array_value($permissions, "can_delete_estimates");
            $view_data['estimate_manage_permission'] = get_array_value($permissions, "estimate_manage_permission");
            $view_data['estimate_manage_permission_specific'] = get_array_value($permissions, "estimate_manage_permission_specific");

            $view_data['estimate_request'] = get_array_value($permissions, "estimate_request");

            $view_data['discount'] = get_array_value($permissions, "discount");

            $view_data['invoice_payments'] = get_array_value($permissions, "invoice_payments");
            $view_data['purchase_order_payments'] = get_array_value($permissions, "purchase_order_payments");
            $view_data['expenses'] = get_array_value($permissions, "expenses");
            $view_data['expiries'] = get_array_value($permissions, "expiries");
            $view_data['petty_cash'] = get_array_value($permissions, "petty_cash");
            $view_data['internal_transactions'] = get_array_value($permissions, "internal_transactions");
            $view_data['estimates'] = get_array_value($permissions, "estimates");
            $view_data['invoices'] = get_array_value($permissions, "invoices");
            $view_data['delivery_notes'] = get_array_value($permissions, "delivery_notes");
            $view_data['purchase_orders'] = get_array_value($permissions, "purchase_orders");
            $view_data['payrolls'] = get_array_value($permissions, "payrolls");
            /*$view_data['dispatches'] = get_array_value($permissions, "dispatches");
            $view_data['shipments'] = get_array_value($permissions, "shipments");*/

            $this->load->view("roles/permissions", $view_data);
        }
    }

    //save a role
    function save() {
        validate_submitted_data(array(
            "id" => "numeric",
            "title" => "required"
        ));

        $id = $this->input->post('id');
        $copy_settings = $this->input->post('copy_settings');
        $data = array(
            "title" => $this->input->post('title'),
        );

        if ($copy_settings) {
            $role = $this->Roles_model->get_one($copy_settings);
            $data["permissions"] = $role->permissions;
        }

        $save_id = $this->Roles_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //save permissions of a role
    function save_permissions() {
        validate_submitted_data(array(
            "id" => "numeric|required",
            "discount" => "numeric|less_than[100]|greater_than_equal_to[0]"
        ));

        $id = $this->input->post('id');
        $leave = $this->input->post('leave_permission');
        $leave_specific = "";
        if ($leave === "specific") {
            $leave_specific = $this->input->post('leave_permission_specific');
        }

        $attendance = $this->input->post('attendance_permission');
        $attendance_specific = "";
        if ($attendance === "specific") {
            $attendance_specific = $this->input->post('attendance_permission_specific');
        }

        $invoice = $this->input->post('invoice_permission');
        $job_info = $this->input->post('job_info');
        $account_setting = $this->input->post('account_setting');
        $final_settelment = $this->input->post('final_settelment');
        $estimate = $this->input->post('estimate_permission');
        $expense = $this->input->post('expense_permission');
        $expiries = $this->input->post('expiries_permission');
        $petty_cash = $this->input->post('petty_cash_permission');
        $client = $this->input->post('client_permission');
        $lead = $this->input->post('lead_permission');
        
        
        $ticket = $this->input->post('ticket_permission');
 
        $ticket_specific = "";
        if ($ticket === "specific") {
            $ticket_specific = $this->input->post('ticket_permission_specific');
        }
        
        
        $can_manage_all_projects = $this->input->post('can_manage_all_projects');
        $can_create_projects = $this->input->post('can_create_projects');
        $can_edit_projects = $this->input->post('can_edit_projects');
        $can_delete_projects = $this->input->post('can_delete_projects');

        $can_add_remove_project_members = $this->input->post('can_add_remove_project_members');

        $can_create_tasks = $this->input->post('can_create_tasks');
        $can_edit_tasks = $this->input->post('can_edit_tasks');
        $can_delete_tasks = $this->input->post('can_delete_tasks');
        $can_comment_on_tasks = $this->input->post('can_comment_on_tasks');
        $show_assigned_tasks_only = $this->input->post('show_assigned_tasks_only');

        $can_create_milestones = $this->input->post('can_create_milestones');
        $can_edit_milestones = $this->input->post('can_edit_milestones');
        $can_delete_milestones = $this->input->post('can_delete_milestones');

        $can_delete_files = $this->input->post('can_delete_files');

        $announcement = $this->input->post('announcement_permission');
        $approve_budgeting = $this->input->post('approve_budgeting');
        $help_and_knowledge_base = $this->input->post('help_and_knowledge_base');

        $can_view_team_members_contact_info = $this->input->post('can_view_team_members_contact_info');
        $can_view_team_members_social_links = $this->input->post('can_view_team_members_social_links');
        $team_member_update_permission = $this->input->post('team_member_update_permission');
        $team_member_update_permission_specific = $this->input->post('team_member_update_permission_specific');

        $timesheet_manage_permission = $this->input->post('timesheet_manage_permission');
        $timesheet_manage_permission_specific = $this->input->post('timesheet_manage_permission_specific');

        $disable_event_sharing = $this->input->post('disable_event_sharing');
        
        $hide_team_members_list = $this->input->post('hide_team_members_list');
        
        $can_delete_leave_application = $this->input->post('can_delete_leave_application');

        $can_add_team_member = $this->input->post('can_add_team_member');
        $can_view_salary_chart = $this->input->post('can_view_salary_chart');

        $accounting = $this->input->post('accounting');
        $payroll = $this->input->post('payroll');
        $eroom = $this->input->post('eroom');
        $logs = $this->input->post('logs');
        $reports = $this->input->post('reports');

        /////////////////////////////////////////////

        $can_access_delivery_notes = $this->input->post('can_access_delivery_notes');
        $can_create_delivery_notes = $this->input->post('can_create_delivery_notes');
        $can_edit_delivery_notes = $this->input->post('can_edit_delivery_notes');
        $can_delete_delivery_notes = $this->input->post('can_delete_delivery_notes');
        $delivery_note_manage_permission = $this->input->post('delivery_note_manage_permission');
        $delivery_note_manage_permission_specific = $this->input->post('delivery_note_manage_permission_specific');
        $can_access_suppliers = $this->input->post('can_access_suppliers');
        $can_create_suppliers = $this->input->post('can_create_suppliers');
        $can_edit_suppliers = $this->input->post('can_edit_suppliers');
        $can_delete_suppliers = $this->input->post('can_delete_suppliers');
        $supplier_manage_permission = $this->input->post('supplier_manage_permission');
        $supplier_manage_permission_specific = $this->input->post('supplier_manage_permission_specific');

        $can_access_purchase_orders = $this->input->post('can_access_purchase_orders');
        $can_create_purchase_orders = $this->input->post('can_create_purchase_orders');
        $can_edit_purchase_orders = $this->input->post('can_edit_purchase_orders');
        $can_delete_purchase_orders = $this->input->post('can_delete_purchase_orders');
        $purchase_order_manage_permission = $this->input->post('purchase_order_manage_permission');
        $purchase_order_manage_permission_specific = $this->input->post('purchase_order_manage_permission_specific');

        /*$can_access_dispatches = $this->input->post('can_access_dispatches');
        $can_create_dispatches = $this->input->post('can_create_dispatches');
        $can_edit_dispatches = $this->input->post('can_edit_dispatches');
        $can_delete_dispatches = $this->input->post('can_delete_dispatches');
        $dispatch_manage_permission = $this->input->post('dispatch_manage_permission');
        $dispatch_manage_permission_specific = $this->input->post('dispatch_manage_permission_specific');*/

        $can_access_items = $this->input->post('can_access_items');
        $can_create_items = $this->input->post('can_create_items');
        $can_edit_items = $this->input->post('can_edit_items');
        $can_delete_items = $this->input->post('can_delete_items');
        $item_manage_permission = $this->input->post('item_manage_permission');
        $item_manage_permission_specific = $this->input->post('item_manage_permission_specific');
        
        $can_access_items_category = $this->input->post('can_access_items_category');
        $can_create_items_category = $this->input->post('can_create_items_category');
        $can_edit_items_category = $this->input->post('can_edit_items_category');
        $can_delete_items_category = $this->input->post('can_delete_items_category');
        $item_category_manage_permission = $this->input->post('item_category_manage_permission');
        $item_category_manage_permission_specific = $this->input->post('item_category_manage_permission_specific');
        /*$can_access_shipments = $this->input->post('can_access_shipments');
        $can_create_shipments = $this->input->post('can_create_shipments');
        $can_edit_shipments = $this->input->post('can_edit_shipments');
        $can_delete_shipments = $this->input->post('can_delete_shipments');
        $shipment_manage_permission = $this->input->post('shipment_manage_permission');
        $shipment_manage_permission_specific = $this->input->post('shipment_manage_permission_specific');*/

        ////////

        $can_access_expiries = $this->input->post('can_access_expiries');
        $can_access_petty_cash = $this->input->post('can_access_petty_cash');
        $can_access_expenses = $this->input->post('can_access_expenses');
        $can_create_expenses = $this->input->post('can_create_expenses');
        $can_edit_expenses = $this->input->post('can_edit_expenses');
        $can_delete_expenses = $this->input->post('can_delete_expenses');
        $expense_manage_permission = $this->input->post('expense_manage_permission');
        $expense_manage_permission_specific = $this->input->post('expense_manage_permission_specific');

        $internal_transaction = $this->input->post('internal_transaction');

        $can_access_invoice_payments = $this->input->post('can_access_invoice_payments');
        $can_create_invoice_payments = $this->input->post('can_create_invoice_payments');
        $can_edit_invoice_payments = $this->input->post('can_edit_invoice_payments');
        $can_delete_invoice_payments = $this->input->post('can_delete_invoice_payments');

        $can_access_purchase_order_payments = $this->input->post('can_access_purchase_order_payments');
        $can_create_purchase_order_payments = $this->input->post('can_create_purchase_order_payments');
        $can_edit_purchase_order_payments = $this->input->post('can_edit_purchase_order_payments');
        $can_delete_purchase_order_payments = $this->input->post('can_delete_purchase_order_payments');

        /////////////////////////////////////

        $can_access_contacts = $this->input->post('can_access_contacts');
        $can_create_contacts = $this->input->post('can_create_contacts');
        $can_edit_contacts = $this->input->post('can_edit_contacts');
        $can_delete_contacts = $this->input->post('can_delete_contacts');
        $contact_manage_permission = $this->input->post('contact_manage_permission');
        $contact_manage_permission_specific = $this->input->post('contact_manage_permission_specific');

        $can_access_clients = $this->input->post('can_access_clients');
        $can_create_clients = $this->input->post('can_create_clients');
        $can_edit_clients = $this->input->post('can_edit_clients');
        $can_delete_clients = $this->input->post('can_delete_clients');
        $client_manage_permission = $this->input->post('client_manage_permission');
        $client_manage_permission_specific = $this->input->post('client_manage_permission_specific');
        
        $can_access_leads = $this->input->post('can_access_leads');
        $can_create_leads = $this->input->post('can_create_leads');
        $can_edit_leads = $this->input->post('can_edit_leads');
        $can_delete_leads = $this->input->post('can_delete_leads');
        $leads_manage_permission = $this->input->post('leads_manage_permission');
        $leads_manage_permission_specific = $this->input->post('leads_manage_permission_specific');

        $can_access_invoices = $this->input->post('can_access_invoices');
        $can_create_invoices = $this->input->post('can_create_invoices');
        $can_edit_invoices = $this->input->post('can_edit_invoices');
        $can_delete_invoices = $this->input->post('can_delete_invoices');
        $invoice_manage_permission = $this->input->post('invoice_manage_permission');
        $invoice_manage_permission_specific = $this->input->post('invoice_manage_permission_specific');
      
        $can_access_invoices_return = $this->input->post('can_access_invoices_return');
        $can_create_invoices_return = $this->input->post('can_create_invoices_return');
        $can_edit_invoices_return = $this->input->post('can_edit_invoices_return');
        $can_delete_invoices_return = $this->input->post('can_delete_invoices_return');
        $invoice_return_manage_permission = $this->input->post('invoice_return_manage_permission');
        $invoice_return_manage_permission_specific = $this->input->post('invoice_return_manage_permission_specific');

        $can_access_estimates = $this->input->post('can_access_estimates');
        $can_create_estimates = $this->input->post('can_create_estimates');
        $can_edit_estimates = $this->input->post('can_edit_estimates');
        $can_delete_estimates = $this->input->post('can_delete_estimates');
        $estimate_manage_permission = $this->input->post('estimate_manage_permission');
        $estimate_manage_permission_specific = $this->input->post('estimate_manage_permission_specific');

        $estimate_request = $this->input->post('estimate_request');
        $discount = $this->input->post('discount');

        //////

        $expenses = $this->input->post('expenses');
        // $expiries = $this->input->post('expiries');
        $estimates = $this->input->post('estimates');
        $invoices = $this->input->post('invoices');
        $invoice_payments = $this->input->post('invoice_payments');
        $delivery_notes = $this->input->post('delivery_notes');
        $purchase_orders = $this->input->post('purchase_orders');
        $purchase_order_payments = $this->input->post('purchase_order_payments');
        $payrolls = $this->input->post('payrolls');
        $internal_transactions = $this->input->post('internal_transactions');
        /*$dispatches = $this->input->post('dispatches');
        $shipments = $this->input->post('shipments');*/


        $permissions = array(
            "leave" => $leave,
            "leave_specific" => $leave_specific,
            "attendance" => $attendance,
            "attendance_specific" => $attendance_specific,
            "invoice" => $invoice,
            "job_info" => $job_info,
            "account_setting" => $account_setting,
            "final_settelment" => $final_settelment,
            "estimate" => $estimate,
            "expense" => $expense,
            "expiries" => $expiries,
            "petty_cash" => $petty_cash,
            "client" => $client,
            "lead" => $lead,
            "ticket" => $ticket,
            "ticket_specific" => $ticket_specific,
            "announcement" => $announcement,
            "approve_budgeting" => $approve_budgeting,
            "approve_budgeting" => $approve_budgeting,
            "help_and_knowledge_base" => $help_and_knowledge_base,
            "can_manage_all_projects" => $can_manage_all_projects,
            "can_create_projects" => $can_create_projects,
            "can_edit_projects" => $can_edit_projects,
            "can_delete_projects" => $can_delete_projects,
            "can_add_remove_project_members" => $can_add_remove_project_members,
            "can_create_tasks" => $can_create_tasks,
            "can_edit_tasks" => $can_edit_tasks,
            "can_delete_tasks" => $can_delete_tasks,
            "can_comment_on_tasks" => $can_comment_on_tasks,
            "show_assigned_tasks_only" => $show_assigned_tasks_only,
            "can_create_milestones" => $can_create_milestones,
            "can_edit_milestones" => $can_edit_milestones,
            "can_delete_milestones" => $can_delete_milestones,
            "can_delete_files" => $can_delete_files,
            "can_view_team_members_contact_info" => $can_view_team_members_contact_info,
            "can_view_team_members_social_links" => $can_view_team_members_social_links,
            "team_member_update_permission" => $team_member_update_permission,
            "team_member_update_permission_specific" => $team_member_update_permission_specific,
            "timesheet_manage_permission" => $timesheet_manage_permission,
            "timesheet_manage_permission_specific" => $timesheet_manage_permission_specific,
            "disable_event_sharing" => $disable_event_sharing,
            "hide_team_members_list" => $hide_team_members_list,
            "can_delete_leave_application" => $can_delete_leave_application,
            "can_add_team_member" => $can_add_team_member,
            "can_view_salary_chart" => $can_view_salary_chart,
            "accounting"=>$accounting,
            "payroll"=>$payroll,
            "eroom"=>$eroom,
            "logs"=>$logs,
            "reports"=>$reports,
            //////////////////////////////////////////////////////////////

            'can_access_delivery_notes' => $can_access_delivery_notes,
            'can_create_delivery_notes' => $can_create_delivery_notes,
            'can_edit_delivery_notes' => $can_edit_delivery_notes,
            'can_delete_delivery_notes' => $can_delete_delivery_notes,
            "delivery_note_manage_permission" => $delivery_note_manage_permission,
            "delivery_note_manage_permission_specific" => $delivery_note_manage_permission_specific,

            'can_access_suppliers' => $can_access_suppliers,
                        'can_create_suppliers' => $can_create_suppliers,
                        'can_edit_suppliers' => $can_edit_suppliers,
                        'can_delete_suppliers' => $can_delete_suppliers,
            "supplier_manage_permission" => $supplier_manage_permission,
                        "supplier_manage_permission_specific" => $supplier_manage_permission_specific,

                        'can_access_purchase_orders' => $can_access_purchase_orders,
                        'can_create_purchase_orders' => $can_create_purchase_orders,
                        'can_edit_purchase_orders' => $can_edit_purchase_orders,
                        'can_delete_purchase_orders' => $can_delete_purchase_orders,
            "purchase_order_manage_permission" => $purchase_order_manage_permission,
                        "purchase_order_manage_permission_specific" => $purchase_order_manage_permission_specific,

                        /*'can_access_dispatches' => $can_access_dispatches,
                        'can_create_dispatches' => $can_create_dispatches,
                        'can_edit_dispatches' => $can_edit_dispatches,
                        'can_delete_dispatches' => $can_delete_dispatches,
            "dispatch_manage_permission" => $dispatch_manage_permission,
                        "dispatch_manage_permission_specific" => $dispatch_manage_permission_specific,*/

                        'can_access_items' => $can_access_items,
                        'can_create_items' => $can_create_items,
                        'can_edit_items' => $can_edit_items,
                        'can_delete_items' => $can_delete_items,
            "item_manage_permission" => $item_manage_permission,
                        "item_manage_permission_specific" => $item_manage_permission_specific,
                        
                        'can_access_items_category' => $can_access_items_category,
                        'can_create_items_category' => $can_create_items_category,
                        'can_edit_items_category' => $can_edit_items_category,
                        'can_delete_items_category' => $can_delete_items_category,
            "item_category_manage_permission" => $item_category_manage_permission,
                        "item_category_manage_permission_specific" => $item_category_manage_permission_specific,

            /*'can_access_shipments' => $can_access_shipments,
                        'can_create_shipments' => $can_create_shipments,
                        'can_edit_shipments' => $can_edit_shipments,
                        'can_delete_shipments' => $can_delete_shipments,
            "shipment_manage_permission" => $shipment_manage_permission,
                        "shipment_manage_permission_specific" => $shipment_manage_permission_specific,*/
            /////////////////////////////////////////

            'can_access_expiries' => $can_access_expiries,
            'can_access_petty_cash' => $can_access_petty_cash,
            'can_access_expenses' => $can_access_expenses,
                    'can_create_expenses' => $can_create_expenses,
                    'can_edit_expenses' => $can_edit_expenses,
                    'can_delete_expenses' => $can_delete_expenses,
            "expense_manage_permission" => $expense_manage_permission,
                    "expense_manage_permission_specific" => $expense_manage_permission_specific,

            "internal_transaction" => $internal_transaction,

            'can_access_invoice_payments' => $can_access_invoice_payments,
                    'can_create_invoice_payments' => $can_create_invoice_payments,
                    'can_edit_invoice_payments' => $can_edit_invoice_payments,
                    'can_delete_invoice_payments' => $can_delete_invoice_payments,

            'can_access_purchase_order_payments' => $can_access_purchase_order_payments,
                    'can_create_purchase_order_payments' => $can_create_purchase_order_payments,
                    'can_edit_purchase_order_payments' => $can_edit_purchase_order_payments,
                    'can_delete_purchase_order_payments' => $can_delete_purchase_order_payments,

            //////////////////////////////////////////////

            'can_access_contacts' => $can_access_contacts,
            'can_create_contacts' => $can_create_contacts,
            'can_edit_contacts' => $can_edit_contacts,
            'can_delete_contacts' => $can_delete_contacts,
            "contact_manage_permission" => $contact_manage_permission,
            "contact_manage_permission_specific" => $contact_manage_permission_specific,

            'can_access_clients' => $can_access_clients,
            'can_create_clients' => $can_create_clients,
            'can_edit_clients' => $can_edit_clients,
            'can_delete_clients' => $can_delete_clients,
            "client_manage_permission" => $client_manage_permission,
            "client_manage_permission_specific" => $client_manage_permission_specific,

            'can_access_leads' => $can_access_leads,
            'can_create_leads' => $can_create_leads,
            'can_edit_leads' => $can_edit_leads,
            'can_delete_leads' => $can_delete_leads,
            "leads_manage_permission" => $leads_manage_permission,
            "leads_manage_permission_specific" => $leads_manage_permission_specific,

            'can_access_invoices' => $can_access_invoices,
            'can_create_invoices' => $can_create_invoices,
            'can_edit_invoices' => $can_edit_invoices,
            'can_delete_invoices' => $can_delete_invoices,
            "invoice_manage_permission" => $invoice_manage_permission,
            "invoice_manage_permission_specific" => $invoice_manage_permission_specific,

            'can_access_invoices_return' => $can_access_invoices_return,
            'can_create_invoices_return' => $can_create_invoices_return,
            'can_edit_invoices_return' => $can_edit_invoices_return,
            'can_delete_invoices_return' => $can_delete_invoices_return,
            "invoice_return_manage_permission" => $invoice_return_manage_permission,
            "invoice_return_manage_permission_specific" => $invoice_return_manage_permission_specific,

            'can_access_estimates' => $can_access_estimates,
            'can_create_estimates' => $can_create_estimates,
            'can_edit_estimates' => $can_edit_estimates,
            'can_delete_estimates' => $can_delete_estimates,
            "estimate_manage_permission" => $estimate_manage_permission,
                    "estimate_manage_permission_specific" => $estimate_manage_permission_specific,
            "estimate_request" => $estimate_request,

            "discount" => $discount ? $discount : 0,

            /////////////////////////////

            "expenses" =>$expenses,
            "expiries" =>$expiries,
            "petty_cash" =>$petty_cash,
            "estimates" =>$estimates,
            "invoices" =>$invoices,
            "invoice_payments" =>$invoice_payments,
            "delivery_notes" =>$delivery_notes,
            "purchase_orders" =>$purchase_orders,
            "purchase_order_payments" =>$purchase_order_payments,
            "payrolls" =>$payrolls,
            "internal_transactions" =>$internal_transactions,
        
        );

        $data = array(
            "permissions" => serialize($permissions),
        );

        $save_id = $this->Roles_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //delete or undo a role
    function delete() {
        validate_submitted_data(array(
            "id" => "numeric|required"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Roles_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Roles_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    //get role list data
    function list_data() {
        $list_data = $this->Roles_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    //get a row of role list
    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Roles_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    //make a row of role list table
    private function _make_row($data) {
        return array("<a href='#' data-id='$data->id' class='role-row link'>" . $data->title . "</a>",
            "<a class='edit'><i class='fa fa-check' ></i></a>" . modal_anchor(get_uri("roles/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "", "title" => lang('edit_role'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_role'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("roles/delete"), "data-action" => "delete"))
        );
    }

}

/* End of file roles.php */
/* Location: ./application/controllers/roles.php */