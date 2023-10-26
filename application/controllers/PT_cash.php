<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class PT_cash extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->permission_checker("can_access_expenses");
        $this->access_allowed_members();
        
    }

    //load note list view
    function index() {
        $this->check_module_availability('module_petty_cash');
        $view_data['module'] = get_array_value($this->login_user->permissions,'internal_transaction');
        
        $this->template->rander("pt_cash/index", $view_data);
    }


    function list_cash_data () {
        $this->check_module_availability('module_petty_cash');
        $options = array();
        
        $this->permission_checker("expense_manage_permission");
        if(!$this->login_user->is_admin || !$this->access_type === "all"){
            $options["allowed_members"] = $this->allowed_members;
        }

    	$list_data = $this->PT_cash_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_cash_row($data);
        }
        echo json_encode(array("data" => $result));
    }


    private function _make_cash_row($data, $edit='') {
        $this->check_module_availability('module_petty_cash');
    	$cash_on_hand = $data->total_recieved - $data->total_transfered - $data->total_expenses;
        $row = array(
       		$data->employee,
       		to_currency($cash_on_hand),
       		to_currency($data->total_recieved),
       		to_currency($data->total_transfered),
       		to_currency($data->total_expenses)            
        );
       return $row;
    }


    function cash_on_hand() {
        $this->check_module_availability('module_petty_cash');
    	$this->load->view("pt_cash/cash_on_hand");    	
    }

    private function can_approve_e() {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "expenses") == "all") {
                return true;
            }
        }
    }

    private function can_approve() {
        $this->check_module_availability('module_petty_cash');
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "petty_cash") == "all") {
                return true;
            }
        }
    }

    function internal_transactions () {
        $this->check_module_availability('module_petty_cash');
        $view_data["can_approve"] = $this->can_approve();
    	$this->load->view("internal_transactions/index", $view_data);
    }


    //load the expenses list view
    function expenses() {
        $this->check_module_availability("module_expense");

        $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("expenses", $this->login_user->is_admin, $this->login_user->user_type);
        $view_data["can_approve"] = $this->can_approve_e();
        if (!$this->login_user->is_admin) {
            $view_data['can_create_module'] = get_array_value($this->login_user->permissions,'can_create_expenses');
            } else {
                 $view_data['can_create_module'] = 1; 
            } 

        $view_data['categories_dropdown'] = $this->_get_categories_dropdown();
        $view_data['members_dropdown'] = $this->_get_team_members_dropdown();
        $view_data['projects_dropdown'] = $this->_get_projects_dropdown();
        $view_data['clients_dropdown'] = $this->_get_clients_dropdown();
        $view_data['modes_dropdown'] = $this->_get_modes_dropdown();

        $this->load->view("expenses/index",$view_data);
    }

    //get categories dropdown
    private function _get_categories_dropdown() {
        $categories = $this->Expense_categories_model->get_all_where(array("deleted" => 0), 0, 0, "title")->result();

        $categories_dropdown = array(array("id" => "", "text" => "- " . lang("category") . " -"));
        foreach ($categories as $category) {
            $categories_dropdown[] = array("id" => $category->id, "text" => $category->title);
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
        $dropdown[] = array("id" => "pt_cash", "text" => lang("pt_cash"));
        $dropdown[] = array("id" => "reimbursement", "text" => lang("reimbursement"));
        $dropdown[] = array("id" => "cash_on_hand", "text" => lang("cash_on_hand"));
        $dropdown[] = array("id" => "bank", "text" => lang("bank"));
        $dropdown[] = array("id" => "cheque", "text" => lang("cheque"));

        return json_encode($dropdown);
    }

}

/* End of file contacts.php */
/* Location: ./application/controllers/contacts.php */