<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Expenses extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->permission_checker("can_access_expenses");
        $this->access_allowed_members();
    }

    //load the expenses list view
    function index() {
        $this->check_module_availability("module_expense");

        if (!$this->login_user->is_admin) {
            $view_data['can_create_module'] = get_array_value($this->login_user->permissions,'can_create_expenses');
        } else {
            $view_data['can_create_module'] = 1; 
        }

        $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("expenses", $this->login_user->is_admin, $this->login_user->user_type);

        $view_data["can_approve"] = $this->can_approve();

        $view_data['categories_dropdown'] = $this->_get_categories_dropdown();
        $view_data['members_dropdown'] = $this->_get_team_members_dropdown();
        $view_data['projects_dropdown'] = $this->_get_projects_dropdown();
        $view_data['clients_dropdown'] = $this->_get_clients_dropdown();
        $view_data['modes_dropdown'] = $this->_get_modes_dropdown();

        $this->template->rander("expenses/index", $view_data);
    }

    //get categories dropdown
    private function _get_categories_dropdown() {
        $categories = $this->Expense_categories_model->get_all_where(array("deleted" => 0), 0, 0, "title")->result();

        $categories_dropdown = array(array("id" => "", "text" => "- " . lang("category") . " -"));
        foreach ($categories as $category) {
            $categories_dropdown[] = array("id" => $category->account_id, "text" => $category->title);
        }

        return json_encode($categories_dropdown);
    }

    //get team members dropdown
    private function _get_team_members_dropdown() {
        $team_members = $this->Users_model->get_all_where(array("deleted" => 0, "user_type" => "staff"), 0, 0, "first_name")->result();

        $members_dropdown = array(array("id" => "", "text" => "- " . lang("member") . " -"));
        foreach ($team_members as $team_member) {
            $members_dropdown[] = array("id" => $team_member->id, "text" => $team_member->first_name . " " . $team_member->last_name);
        }

        return json_encode($members_dropdown);
    }

    //get projects dropdown
    private function _get_projects_dropdown() {
        $projects = $this->Projects_model->get_all_where(array("deleted" => 0), 0, 0, "title")->result();

        $projects_dropdown = array(array("id" => "", "text" => "- " . lang("project") . " -"));
        foreach ($projects as $project) {
            $projects_dropdown[] = array("id" => $project->id, "text" => $project->title);
        }

        return json_encode($projects_dropdown);
    }

    //get projects dropdown
    private function _get_clients_dropdown() {
        $projects = $this->Clients_model->get_all_where(array("deleted" => 0), 0, 0, "company_name")->result();

        $projects_dropdown = array(array("id" => "", "text" => "- " . lang("client") . " -"));
        foreach ($projects as $project) {
            $projects_dropdown[] = array("id" => $project->id, "text" => $project->company_name);
        }

        return json_encode($projects_dropdown);
    }

    //get projects dropdown
    private function _get_modes_dropdown() {

        $dropdown = array(array("id" => "", "text" => "- " . lang("payment_mode") . " -"));
        /*$dropdown[] = array("id" => "pt_cash", "text" => lang("pt_cash"));
        $dropdown[] = array("id" => "reimbursement", "text" => lang("reimbursement"));*/
        $dropdown[] = array("id" => "cash_on_hand", "text" => lang("cash_on_hand"));
        $dropdown[] = array("id" => "bank", "text" => lang("bank"));
        $dropdown[] = array("id" => "cheque", "text" => lang("cheque"));

        return json_encode($dropdown);
    }

    //load the expenses list yearly view
    function yearly() {
        $this->load->view("expenses/yearly_expenses");
    }

    //load custom expenses list
    function custom() {
        $this->load->view("expenses/custom_expenses");
    }

    //load the add/edit expense form
    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $model_info = $this->Expenses_model->get_one($this->input->post('id'));
        $view_data['categories_dropdown'] = array("" => "-") + $this->Expense_categories_model->get_dropdown_list(array("title"),"account_id");
       if($model_info){
        $view_data['items_dropdown'] = array("" => "-") + $this->Accounts_model->get_dropdown_list(array("acc_name"), "id", array("acc_parent" => $model_info->category_id, "deleted" => 0));
       }else{
        $view_data['items_dropdown'] = array("" => "-") + $this->Accounts_model->get_dropdown_list(array("acc_name"), "id", array("acc_parent" => get_setting('expenses_accounts_parent'), "deleted" => 0));
       }
        //TODO: for test accout only remove if statement if changes in database ready for all clients
        if ($this->login_user->is_admin && (strcasecmp($this->db->dbprefix,'test_teamway') == 0 || strcasecmp($this->db->dbprefix,'tarteeb_v3') == 0 ))
        {
        $view_data['service_provider_dropdown'] = array("" => "-") + $this->Service_provider_model->get_dropdown_list(array("name"));
        }
        // $view_data['items_dropdown'] = array("" => "-");

        $team_members = $this->Users_model->get_all_where(array("deleted" => 0, "user_type" => "staff"))->result();
        $members_dropdown = array();

        foreach ($team_members as $team_member) {
            $this->permission_checker("expense_manage_permission");
            if($this->login_user->is_admin || $this->access_type === "all" || in_array($team_member->id, $this->allowed_members)){
            $members_dropdown[$team_member->id] = $team_member->first_name . " " . $team_member->last_name;
            }
        }

        $view_data['members_dropdown'] = array("" => "-") + $members_dropdown;
        $view_data['projects_dropdown'] = array("0" => "-") + $this->Projects_model->get_dropdown_list(array("title"));
        $view_data['taxes_dropdown'] = array("" => "-") + $this->Taxes_model->get_dropdown_list(array("title"));

        $modes_dropdown = array(
                    );
        if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "accounting") == "all") {
            $modes_dropdown["cash_on_hand"] = lang("cash_on_hand");
            $modes_dropdown["bank"] = lang("bank");
            $modes_dropdown["cheque"] = lang("cheque");
        }
        $modes_dropdown["pt_cash"] = lang("petty_cash");
        $view_data['banks_dropdown'] = array("" => "-") +$this->Accounts_model->get_dropdown_list(array("acc_name"), "id", array("acc_parent" => $this->Accounts_model->get_banking_accounts_id(), "deleted" => 0));
       
        $view_data['treasury'] = array();
        if($this->can_approve()) {
            $view_data['treasury'] = $view_data['treasury'] +  array("" => "-") + $this->Accounts_model->get_dropdown_list(array("acc_name"), "id", array("acc_parent" => $this->Accounts_model->get_treasury_accounts_id(), "deleted" => 0));
         }  
        

        $view_data['modes_dropdown'] = array("" => "-") + $modes_dropdown;


        $view_data['clients_dropdown'] = array("0" => "-") + $this->Clients_model->get_dropdown_list(array("company_name"));

        $model_info->project_id = $model_info->project_id ? $model_info->project_id : $this->input->post('project_id');
        $model_info->user_id = $model_info->user_id ? $model_info->user_id : $this->input->post('user_id');

        $view_data['model_info'] = $model_info;

        $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("expenses", $view_data['model_info']->id, $this->login_user->is_admin, $this->login_user->user_type)->result();
        $this->load->view('expenses/modal_form', $view_data);
    }

    function _check_expense_type(){
        $type = $this->input->post('purchase_type');
        if($type != 0 && $type != 1){
            $this->form_validation->set_message('_check_expense_type','Please enter a correct value!');
            return false;
            
        }else{
            return true;
        }
    }
    //save an expense
    function save() {
        //TODO: for test accout only remove else block if changes in database ready for all clients
        if ($this->login_user->is_admin && (strcasecmp($this->db->dbprefix,'test_teamway') == 0 || strcasecmp($this->db->dbprefix,'tarteeb_v3') == 0 ))
        {
        validate_submitted_data(array(
            "id" => "numeric",
            "expense_date" => "required",
            "category_id" => "required|numeric",
            "amount" => "required",
            "expense_user_id" => "required|numeric",
            "payment_mode" => "required",
            "service_provider_id" => "numeric",
            "expense_type" => "required|callback__check_expense_type",
        ));
        }else{
            validate_submitted_data(array(
                "id" => "numeric",
                "expense_date" => "required",
                "category_id" => "required|numeric",
                "amount" => "required",
                "expense_user_id" => "required|numeric",
                "payment_mode" => "required",
            ));
        }

        

        $id = $this->input->post('id');

        $target_path = get_setting("timeline_file_path");
        $files_data = move_files_from_temp_dir_to_permanent_dir($target_path, "expense");
        $new_files = unserialize($files_data);

        
        //TODO: for test accout only remove else block if changes in database ready for all clients
        if ($this->login_user->is_admin && (strcasecmp($this->db->dbprefix,'test_teamway') == 0 || strcasecmp($this->db->dbprefix,'tarteeb_v3') == 0 ))
        {
            //SAVE NEW SERVICE PROVIDER 
        $service_provider_id = $this->input->post('service_provider_id');

        // $service_provider_name = $this->input->post('service_provider_name');
        // if($service_provider_name && !$service_provider_id) {
        if(!$service_provider_id) {
             $service_provider_id = $this->save_service_provider();
        }
        
        $expense_type = $this->input->post('expense_type') == 0? "domestic":"import";
        $data = array(
            "expense_date" => $this->input->post('expense_date'),
            "title" => $this->input->post('title') ? $this->input->post('title') : "",
            "description" => $this->input->post('description'),
            "category_id" => $this-> input->post('category_id'),
            "item_id" => $this-> input->post('item_id'),
            "amount" => unformat_currency($this->input->post('amount')),
            "cheque_due_date" => $this->input->post('cheque_due_date'),
            "cheque_description" => $this->input->post('cheque_description'),
            "cheque_account" => $this->input->post('cheque_account'),
            "cheque_number" => $this->input->post('cheque_number'),
            "payment_mode" => $this->input->post('payment_mode'),
            "project_id" => $this->input->post('expense_project_id'),
            "user_id" => $this->input->post('expense_user_id'),
            "client_id" => $this->input->post('expense_client_id'),
            "tax_id" => $this->input->post('tax_id') ? $this->input->post('tax_id') : 0,
            "tax_id2" => $this->input->post('tax_id2') ? $this->input->post('tax_id2') : 0,
            "treasury" => $this->input->post('treasury') ? $this->input->post('treasury') : 0,
            "bank" => $this->input->post('bank') ? $this->input->post('bank') : 0,
            "pt_cash" => $this->input->post('pt_cash') ? $this->input->post('pt_cash') : 0,
            "service_provider_id" => $service_provider_id,
            "type" => $expense_type,
            "invoice_ref_number" => $this->input->post('invoice_ref_number'),
        );
    }else{
        $data = array(
            "expense_date" => $this->input->post('expense_date'),
            "title" => $this->input->post('title') ? $this->input->post('title') : "",
            "description" => $this->input->post('description'),
            "category_id" => $this-> input->post('category_id'),
            "item_id" => $this-> input->post('item_id'),
            "amount" => unformat_currency($this->input->post('amount')),
            "cheque_due_date" => $this->input->post('cheque_due_date'),
            "cheque_description" => $this->input->post('cheque_description'),
            "cheque_account" => $this->input->post('cheque_account'),
            "cheque_number" => $this->input->post('cheque_number'),
            "payment_mode" => $this->input->post('payment_mode'),
            "project_id" => $this->input->post('expense_project_id'),
            "user_id" => $this->input->post('expense_user_id'),
            "client_id" => $this->input->post('expense_client_id'),
            "tax_id" => $this->input->post('tax_id') ? $this->input->post('tax_id') : 0,
            "tax_id2" => $this->input->post('tax_id2') ? $this->input->post('tax_id2') : 0,
            "treasury" => $this->input->post('treasury') ? $this->input->post('treasury') : 0,
            "bank" => $this->input->post('bank') ? $this->input->post('bank') : 0,
            "pt_cash" => $this->input->post('pt_cash') ? $this->input->post('pt_cash') : 0,
        );
    }


        //is editing? update the files if required
        if ($id) {
            $expense_info = $this->Expenses_model->get_one($id);
            $timeline_file_path = get_setting("timeline_file_path");

            $new_files = update_saved_files($timeline_file_path, $expense_info->files, $new_files);
        }

        $data["files"] = serialize($new_files);


        $save_id = $this->Expenses_model->save($data, $id);
        if ($save_id) {
            save_custom_fields("expenses", $save_id, $this->login_user->is_admin, $this->login_user->user_type);
            $expense_info = $this->Expenses_model->get_one($save_id);

            if ($expense_info->status == 'approved') {
                $this->make_expense_entries($expense_info);
                $this->pushes($expense_info);
            }

            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function save_service_provider() {

        validate_submitted_data(array(
            "service_provider_name" => "required",
        ));
        $service_provider_name = $this->input->post('service_provider_name');
        $data = array(
            "name" => $service_provider_name,
            "phone" => $this->input->post('service_provider_phone'),
            "email" => $this->input->post('service_provider_email'),
            "vat_number" => $this->input->post('service_provider_vat_number'),
        );
        $data = clean_data($data);
        //check duplicate company name, if found then show an error message
        if ($this->Service_provider_model->is_duplicate_name($data["name"], 0)) {
            echo json_encode(array("success" => false, 'message' => lang("service_provider_already_exists_for_with_same_name")));
            exit();
        }
        //check duplicate company name, if found then show an error message
        if ($this->Service_provider_model->is_duplicate_vat_number($data["vat_number"], 0)) {
            echo json_encode(array("success" => false, 'message' => lang("service_provider_already_exists_with_same_VAT_number")));
            exit();
        }
        $save_id = $this->Service_provider_model->save($data);

        if($save_id) {
           return $save_id; 
        }
        
    }

    //delete/undo an expense
    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        $expense_info = $this->Expenses_model->get_one($id);


        if ($this->Expenses_model->delete($id)) {
            //delete the files
            $file_path = get_setting("timeline_file_path");
            if ($expense_info->files) {
                $files = unserialize($expense_info->files);

                foreach ($files as $file) {
                    delete_app_files($file_path, array($file));
                }
            }

            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    function child_list(){
        $parent_id = $this->input->get('parent_id');
        // if($parent_id==1){
        //     $childs=$this->Accounts_model->get_all_where(["acc_parent" => 30, "deleted" => 0])->result();
        // }
        // if($parent_id==2){
        //     $childs=$this->Accounts_model->get_all_where(["acc_parent" => 39, "deleted" => 0])->result();
        // }
        $childs=$this->Accounts_model->get_all_where(["acc_parent" => $parent_id, "deleted" => 0])->result();
        foreach($childs as $child){
            $d[]= "<option value='$child->id'>$child->acc_name</option>";
        }
        echo json_encode(array("success" => true, 'data' =>implode('',$d) ));
        // var_dump($d);
        // echo $parent_id;
    }

    //get the expnese list data
    function list_data() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $category_id = $this->input->post('category_id');
        $project_id = $this->input->post('project_id');
        $user_id = $this->input->post('user_id');
        $client_id = $this->input->post('client_id');
        $payment_mode = $this->input->post('payment_mode');
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("expenses", $this->login_user->is_admin, $this->login_user->user_type);
        $options = array("start_date" => $start_date, "end_date" => $end_date, "category_id" => $category_id, "project_id" => $project_id, "user_id" => $user_id, "client_id" => $client_id, "payment_mode"=> $payment_mode, "custom_fields" => $custom_fields);

        $this->permission_checker("expense_manage_permission");
        if(!$this->login_user->is_admin || !$this->access_type === "all"){
            $options["allowed_members"] = $this->allowed_members;
        }

        $list_data = $this->Expenses_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }
        echo json_encode(array("data" => $result));
    }


    //get a row of expnese list
    private function _row_data($id) {
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("expenses", $this->login_user->is_admin, $this->login_user->user_type);
        $options = array("id" => $id, "custom_fields" => $custom_fields);
        $data = $this->Expenses_model->get_details($options)->row();
        return $this->_make_row($data, $custom_fields);
    }

    private function can_edit() {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_edit_expenses") == "1") {
                return true;
            }
        }
    }

    private function can_delete() {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_delete_expenses") == "1") {
                return true;
            }
        }
    }

    private function can_approve() {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "expenses") == "all") {
                return true;
            }
        }
    }

    //prepare a row of expnese list
    private function _make_row($data, $custom_fields) {

        $description = $data->description;
        if ($data->project_title) {
            if ($description) {
                $description .= "<br /> ";
            }
            $description .= lang("project") . ": " . $data->project_title;
        }

        if ($data->linked_user_name) {
            if ($description) {
                $description .= "<br /> ";
            }
            $description .= lang("team_member") . ": " . $data->linked_user_name;
        }

        if ($data->company_name) {
            if ($description) {
                $description .= "<br /> ";
            }
            $description .= lang("client") . ": " . $data->company_name;
        }

        $payment_mode = "<b> ".lang($data->payment_mode)."</b> ";

        if($data->payment_mode == "cheque"){
            if ($data->cheque_due_date) {
                if ($payment_mode) {
                    $payment_mode .= "<br /> ";
                }
                $payment_mode .= lang("due_date") . ": " . $data->cheque_due_date;
            }

            if ($data->cheque_description) {
                if ($payment_mode) {
                    $payment_mode .= "<br /> ";
                }
                $payment_mode .= lang("description") . ": " . $data->cheque_description;
            }

            if ($data->cheque_account) {
                if ($payment_mode) {
                    $payment_mode .= "<br /> ";
                }
                $payment_mode .= lang("account") . ": " . $data->cheque_account;
            }

            if ($data->cheque_number) {
                if ($payment_mode) {
                    $payment_mode .= "<br /> ";
                }
                $payment_mode .= lang("number") . ": " . $data->cheque_number;
            }
        }

        $files_link = "";
        if ($data->files) {
            $files = unserialize($data->files);
            if (count($files)) {
                foreach ($files as $key => $value) {
                    $file_name = get_array_value($value, "file_name");
                    $link = " fa fa-" . get_file_icon(strtolower(pathinfo($file_name, PATHINFO_EXTENSION)));
                    $files_link .= js_anchor(" ", array('title' => "", "data-toggle" => "app-modal", "data-sidebar" => "0", "class" => "pull-left font-22 mr10 $link", "title" => remove_file_prefix($file_name), "data-url" => get_uri("expenses/file_preview/" . $data->id . "/" . $key)));
                }
            }
        }

        $tax = 0;
        $tax2 = 0;
        if ($data->tax_percentage) {
            $tax = $data->amount - ( $data->amount / (($data->tax_percentage / 100)+1) );
        }
        if ($data->tax_percentage2) {
            $tax2 = $data->amount * ($data->tax_percentage2 / 100);
        }

        //// permission
        if ($this->can_approve() || $data->status == "draft") {
            $stat = js_anchor(lang($data->status), array('title' => "status", "class" => "btn btn-default", "data-id" => $data->id, "data-value" => $data->status, "data-act" => "update-status"));
        } else {
            $stat = lang($data->status);
        }
        $category_name=$this->Accounts_model->get_one($data->category_id)->acc_name;
        $row_data = array(
            $data->expense_date,
            format_to_date($data->expense_date, false),
            $category_name,
            $data->title,
            $description,
            $files_link,
            to_currency($data->amount),
            to_currency($tax),
            $payment_mode,
            $stat,
            to_currency($tax),
            to_currency($tax2),
            to_currency($data->amount + $tax + $tax2)
        );

        foreach ($custom_fields as $field) {
            $cf_id = "cfv_" . $field->id;
            $row_data[] = $this->load->view("custom_fields/output_" . $field->field_type, array("value" => $data->$cf_id), true);
        }


        $rowe = "";
        if ($this->can_edit() && ($data->status == "draft")) { 
            $rowe .= modal_anchor(get_uri("expenses/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_expense'), "data-post-id" => $data->id));
        }
        
        if ($this->can_delete() && ($data->status == "draft")) { 
            $rowe .= js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_expense'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("expenses/delete"), "data-action" => "delete-confirmation"));
        }

        $row_data[] = $rowe;

        return $row_data;
    }

    function file_preview($id = "", $key = "") {
        if ($id) {
            $expense_info = $this->Expenses_model->get_one($id);
            $files = unserialize($expense_info->files);
            $file = get_array_value($files, $key);

            $file_name = get_array_value($file, "file_name");
            $file_id = get_array_value($file, "file_id");
            $service_type = get_array_value($file, "service_type");

            $view_data["file_url"] = get_source_url_of_file($file, get_setting("timeline_file_path"));
            $view_data["is_image_file"] = is_image_file($file_name);
            $view_data["is_google_preview_available"] = is_google_preview_available($file_name);
            $view_data["is_viewable_video_file"] = is_viewable_video_file($file_name);
            $view_data["is_google_drive_file"] = ($file_id && $service_type == "google") ? true : false;

            $this->load->view("expenses/file_preview", $view_data);
        } else {
            show_404();
        }
    }

    /* upload a file */

    function upload_file() {
        upload_file_to_temp();
    }

    /* check valid file for ticket */

    function validate_expense_file() {
        return validate_post_file($this->input->post("file_name"));
    }

    //load the expenses yearly chart view
    function yearly_chart() {
        $this->load->view("expenses/yearly_chart");
    }

    function yearly_chart_data() {

        $months = array("january", "february", "march", "april", "may", "june", "july", "august", "september", "october", "november", "december");
        $result = array();

        $year = $this->input->post("year");
        if ($year) {
            $expenses = $this->Expenses_model->get_yearly_expenses_chart($year);
            $values = array();
            foreach ($expenses as $value) {
                $values[$value->month - 1] = $value->total; //in array the month january(1) = index(0)
            }

            foreach ($months as $key => $month) {
                $value = get_array_value($values, $key);
                $result[] = array(lang("short_" . $month), $value ? $value : 0);
            }

            echo json_encode(array("data" => $result));
        }
    }

    function income_vs_expenses() {
        $this->load->view("expenses/income_vs_expenses_chart");
    }

    function income_vs_expenses_chart_data() {

        $year = $this->input->post("year");

        if ($year) {
            $expenses_data = $this->Expenses_model->get_yearly_expenses_chart($year);
            $payments_data = $this->Invoice_payments_model->get_yearly_payments_chart($year);
            $po_payments_data = $this->Purchase_order_payments_model->get_yearly_payments_chart($year);

            $payments = array();
            $payments_array = array();

            $po_payments = array();
            $po_payments_array = array();

            $expenses = array();
            $expenses_array = array();

            for ($i = 1; $i <= 12; $i++) {
                $payments[$i] = 0;
                $expenses[$i] = 0;
                $po_payments[$i] = 0;
            }

            foreach ($payments_data as $payment) {
                $payments[$payment->month] = $payment->total;
            }
            foreach ($expenses_data as $expense) {
                $expenses[$expense->month] = $expense->total;
            }
            foreach ($po_payments_data as $po_payment) {
                $po_payments[$po_payment->month] = $po_payment->total;
            }

            foreach ($payments as $key => $payment) {
                $payments_array[] = array($key, $payment);
            }

            foreach ($expenses as $key => $expense) {
                $sum = $po_payments[$key] + $expense;
                $expenses_array[] = array($key, $sum);
            }

            $expArray = array();
            $payArray = array();
            foreach ($expenses_array as $key => $data) {
                 $expArray[] = $data[1];
            }
            //$expArray = json_encode($expArray);

            foreach ($payments_array as $key => $data2) {
                 $payArray[] = $data2[1];
            }
            //$payArray = json_encode($payArray);

            echo json_encode(array("income" => $payArray, "expenses" => $expArray));
        }
    }

    function income_vs_expenses_summary() {
        $this->load->view("expenses/income_vs_expenses_summary");
    }

    function income_vs_expenses_summary_list_data() {

        $year = explode("-", $this->input->post("start_date"));

        if ($year) {
            $expenses_data = $this->Expenses_model->get_yearly_expenses_chart($year[0]);
            $payments_data = $this->Invoice_payments_model->get_yearly_payments_chart($year[0]);
            $po_payments_data = $this->Purchase_order_payments_model->get_yearly_payments_chart($year[0]);

            $payments = array();
            $po_payments = array();
            $expenses = array();

            for ($i = 1; $i <= 12; $i++) {
                $payments[$i] = 0;
                $expenses[$i] = 0;
                $po_payments[$i] = 0;
            }

            foreach ($payments_data as $payment) {
                $payments[$payment->month] = $payment->total;
            }
            foreach ($expenses_data as $expense) {
                $expenses[$expense->month] = $expense->total;
            }
            foreach ($po_payments_data as $po_payment) {
                $po_payments[$po_payment->month] = $po_payment->total;
            }

            //get the list of summary
            $result = array();
            for ($i = 1; $i <= 12; $i++) {
                $result[] = $this->_row_data_of_summary($i, $payments[$i], $expenses[$i], $po_payments[$i]);
            }

            echo json_encode(array("data" => $result));
        }
    }

    //get the row of summary
    private function _row_data_of_summary($month_index, $payments, $expenses, $po_payments) {
        //get the month name
        $month_array = array(" ", "january", "february", "march", "april", "may", "june", "july", "august", "september", "october", "november", "december");

        $month = get_array_value($month_array, $month_index);

        $month_name = lang($month);
        $profit = $payments - $expenses - $po_payments;

        return array(
            $month_index,
            $month_name,
            to_currency($payments),
            to_currency($expenses + $po_payments),
            to_currency($profit)
        );
    }

    function update_status ($stat = '', $sent_id = 0) {
         if ($sent_id == 0) {
            $id = $this->input->post('id');
         } else {
            $id = $sent_id;
         } 

         if($stat == 0) {
            $stat = $this->input->post('value'); 
         } 
         
         $data = array(
            'status' => $stat
         );

        $status_id = $this->Expenses_model->save($data, $id);

        if ($status_id) {
            echo json_encode(array("success" => true, 'message' => 'success'));
            $expense_info = $this->Expenses_model->get_details(array("id"=>$status_id))->row();
            if ($stat == "request_approval") {
                $this->delete_expense_entries($expense_info);
            } elseif ($stat == "draft") {
                $this->delete_expense_entries($expense_info);
            } elseif ($stat == "approved") {
                $this->make_expense_entries($expense_info);
                $this->pushes($expense_info);
            } elseif ($stat == "rejected") {
                $this->delete_expense_entries($expense_info);
                $this->pushes($expense_info);
            }
        }

    }

    function make_expense_entries($expense_info) {
        $date = $expense_info->expense_date;
        $type = "Automatic General Expense: ".lang($expense_info->payment_mode);
        
        $acc_array = array();
        $narration = $expense_info->description;
        if($expense_info->payment_mode == "cheque"){
            if ($expense_info->cheque_due_date) {
                if ($narration) {
                    $narration .= "<br /> ";
                }
                $narration .= lang("due_date") . ": " . $expense_info->cheque_due_date;
            }

            if ($expense_info->cheque_account) {
                if ($narration) {
                    $narration .= "<br /> ";
                }
                $narration .= lang("account") . ": " . $expense_info->cheque_account;
            }

            if ($expense_info->cheque_number) {
                if ($narration) {
                    $narration .= "<br /> ";
                }
                $narration .= lang("number") . ": " . $expense_info->cheque_number;
            }
        }


        $category_info = $this->Expense_categories_model->get_one($expense_info->category_id);

        $user_info = $this->Users_model->get_one($expense_info->user_id);

        if ($expense_info->tax_percentage) {
            $tax = $expense_info->amount - ( $expense_info->amount / (($expense_info->tax_percentage / 100)+1) );
        } else {
            $tax = 0;
        }

        if ($expense_info->payment_mode == "pt_cash") {
            $user_info = $this->Users_model->get_one($expense_info->pt_cash);
            $type = "PTC Expense from: ". $user_info->first_name ;

            // $acc_array[] = array("account_id" => $category_info->account_id, "type" => 'dr',"amount" => $expense_info->amount, "narration" => $narration);
            $acc_array[] = array("account_id" => $expense_info->item_id, "type" => 'dr',"amount" => $expense_info->amount-$tax, "narration" => $narration);
            $acc_array[] = array("account_id" => get_setting('VAT_out'), "type" => 'dr',"amount" => $tax, "narration" => $narration);

            // $acc_array[] = array("account_id" => $user_info->pt_account_id, "type" => 'cr',"amount" => $expense_info->amount, "narration" => $narration);
            $acc_array[] = array("account_id" =>  get_setting('petty_cash_parent'), "type" => 'cr',"amount" => $expense_info->amount, "narration" => $narration);

        } elseif ($expense_info->payment_mode == "reimbursement") {
            $acc_array[] = array("account_id" => $category_info->account_id, "type" => 'dr',"amount" => $expense_info->amount, "narration" => $narration);

            $acc_array[] = array("account_id" => $user_info->imb_account_id, "type" => 'cr',"amount" => $expense_info->amount, "narration" => $narration);
            
        } elseif ($expense_info->payment_mode == "cash_on_hand") {
            // $acc_array[] = array("account_id" => $category_info->account_id, "type" => 'dr',"amount" => $expense_info->amount-$tax, "narration" => $narration);
            $acc_array[] = array("account_id" => $expense_info->item_id, "type" => 'dr',"amount" => $expense_info->amount-$tax, "narration" => $narration);

            $acc_array[] = array("account_id" => get_setting('VAT_out'), "type" => 'dr',"amount" => $tax, "narration" => $narration);

            if(!empty($expense_info->treasury)) {
                $treasury = $expense_info->treasury;
            } else {
                // $treasury = get_setting('default_cash_on_hand') ? get_setting('default_cash_on_hand') : 0;
            } 
            
            // $acc_array[] = array("account_id" => $treasury, "type" => 'cr',"amount" => $expense_info->amount, "narration" => $narration);
            $acc_array[] = array("account_id" =>  $expense_info->treasury, "type" => 'cr',"amount" => $expense_info->amount, "narration" => $narration);
            
        } elseif ($expense_info->payment_mode == "bank") {

            // $acc_array[] = array("account_id" => $category_info->account_id, "type" => 'dr',"amount" => $expense_info->amount-$tax, "narration" => $narration);
            $acc_array[] = array("account_id" => $expense_info->item_id, "type" => 'dr',"amount" => $expense_info->amount-$tax, "narration" => $narration);

            $acc_array[] = array("account_id" => get_setting('VAT_out'), "type" => 'dr',"amount" => $tax, "narration" => $narration);

            // $acc_array[] = array("account_id" => get_setting('default_bank'), "type" => 'cr',"amount" => $expense_info->amount, "narration" => $narration);
            $acc_array[] = array("account_id" => $expense_info->bank, "type" => 'cr',"amount" => $expense_info->amount, "narration" => $narration);
            
        } elseif ($expense_info->payment_mode == "cheque") {
            // $acc_array[] = array("account_id" => $category_info->account_id, "type" => 'dr',"amount" => $expense_info->amount-$tax, "narration" => $narration);
            $acc_array[] = array("account_id" =>  $expense_info->item_id, "type" => 'dr',"amount" => $expense_info->amount-$tax, "narration" => $narration);

            $acc_array[] = array("account_id" => get_setting('VAT_out'), "type" => 'dr',"amount" => $tax, "narration" => $narration);
            $acc_array[] = array("account_id" => get_setting('payable_cheques'), "type" => 'cr',"amount" =>  $expense_info->amount, "narration" => $narration);
          
            
        }

        //var_dump($acc_array);

        $transaction_id = make_transaction($date, $acc_array, $type);

        if ($transaction_id) {
            $trans_data = array("transaction_id" => $transaction_id);
            $this->Expenses_model->save($trans_data, $expense_info->id);
        }
    }


    function delete_expense_entries($expense_info) {   
        $trans_id = $expense_info->transaction_id;
        if ($trans_id !== 0) {
            $transaction_id = delete_transaction($trans_id);
            if ($transaction_id) {
                $trans_data = array("transaction_id" => 0);
                $this->Expenses_model->save($trans_data, $expense_info->id);
            }
        }
        $cheque_trans_id = $expense_info->cheque_transaction_id;
        if ($cheque_trans_id !== 0) {
            $cheque_transaction_id = delete_transaction($cheque_trans_id);
            if ($cheque_transaction_id) {
                $che_trans_data = array("cheque_transaction_id" => 0);
                $this->Expenses_model->save($che_trans_data, $expense_info->id);
            }
        }
    }

    function pushes($expense_info) {   
        $notifationTo = $expense_info->user_id;
        $narration = '['.$expense_info->description.'] of amount ('.$expense_info->amount.')';
        $body = 'Dear '.'User'.'! Your Expense '.$narration.'has the status: '.lang($expense_info->status);
        send_onesignal($body, '#', $notifationTo, '', '');
    }

    function testtest() {

        $expense_info = $this->Expenses_model->get_one(50);
        $this->delete_expense_entries($expense_info);
        var_dump($expense_info);
            
    }
    function invoice_expense_list_data($invoice_id = 0) {

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("expenses", $this->login_user->is_admin, $this->login_user->user_type);
        $options["invoice_id"] = $invoice_id;
        if(!$this->login_user->is_admin){
                    $options["departments"] = explode(",",get_array_value($this->login_user->permissions, "branches_options"));
                }
        $list_data = $this->Expenses_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields, $invoice_id);
        }
        echo json_encode(array("data" => $result));
    }

}

/* End of file expenses.php */
/* Location: ./application/controllers/expenses.php */