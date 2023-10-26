<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Suppliers extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->permission_checker("can_access_suppliers");
        $this->access_allowed_members();
    }

    private function can_edit() {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_edit_suppliers") == "1") {
                return true;
            }
        }
    }

    private function can_delete() {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_delete_suppliers") == "1") {
                return true;
            }
        }
    }

    /* load suppliers list view */

    function index() {
        if (!$this->login_user->is_admin) {
            $view_data['can_create_module'] = get_array_value($this->login_user->permissions,'can_create_suppliers');
        } else {
             $view_data['can_create_module'] = 1; 
        }
        $this->permission_checker("purchase_order_manage_permission");
        $view_data["show_info"] = ($this->login_user->is_admin || $this->access_type === "all") ? true : false;
        $this->template->rander("suppliers/index", $view_data);
    }

    /* load supplier add/edit modal */

    function modal_form() {

        $supplier_id = $this->input->post('id');
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['label_column'] = "col-md-3";
        $view_data['field_column'] = "col-md-9";

        $view_data['model_info'] = $this->Suppliers_model->get_one($supplier_id);
        $view_data["currency_dropdown"] = $this->_get_currency_dropdown_select2_data();

        $this->load->view('suppliers/modal_form', $view_data);
    }

    /* insert or update a supplier */

    function save() {
        $supplier_id = $this->input->post('id');

        validate_submitted_data(array(
            "id" => "numeric",
            "company_name" => "required"
        ));

        $company_name = $this->input->post('company_name');


        $data = array(
            "company_name" => $company_name,
            "contact_name" => $this->input->post('contact_name'),
            "email" => $this->input->post('email'),
            "address" => $this->input->post('address'),
            "city" => $this->input->post('city'),
            "state" => $this->input->post('state'),
            "zip" => $this->input->post('zip'),
            "country" => $this->input->post('country'),
            "phone" => $this->input->post('phone'),
            "alternative_phone" => $this->input->post('alternative_phone'),
            "website" => $this->input->post('website'),
            "vat_number" => $this->input->post('vat_number'),
            "note" => $this->input->post('note'),
        );


        if (!$supplier_id) {
            $data["created_date"] = get_current_utc_time();
        }


        if ($this->login_user->is_admin) {
            $data["currency_symbol"] = $this->input->post('currency_symbol') ? $this->input->post('currency_symbol') : "";
            $data["currency"] = $this->input->post('currency') ? $this->input->post('currency') : "";
        }

        $data = clean_data($data);


        //check duplicate company name, if found then show an error message
        if (get_setting("disallow_duplicate_supplier_company_name") == "1" && $this->Suppliers_model->is_duplicate_company_name($data["company_name"], $supplier_id)) {
            echo json_encode(array("success" => false, 'message' => lang("account_already_exists_for_your_company_name")));
            exit();
        }


        $save_id = $this->Suppliers_model->save($data, $supplier_id);
        if ($save_id) {
            ////
            $account_id = $this->Suppliers_model->get_one($save_id)->account_id;
            $account = generate_accounts($company_name, get_setting("suppliers_accounts_parent"), $account_id);
            if ($account_id !== 0) {
                $account_data = array("account_id" => $account);
               $this->Suppliers_model->save($account_data, $save_id);
            }
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'view' => $this->input->post('view'), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    /* delete or undo a supplier */

    function delete() {

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Suppliers_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Suppliers_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of suppliers, prepared for datatable  */

    function list_data() {

        $options = array();
        $this->permission_checker("supplier_manage_permission");
        if(!$this->login_user->is_admin || !$this->access_type === "all"){
            $options["allowed_members"] = $this->allowed_members;
        }
        $list_data = $this->Suppliers_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    /* return a row of supplier list  table */

    private function _row_data($id) {

        $options = array(
            "id" => $id,
        );
        $data = $this->Suppliers_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    /* prepare a row of supplier list table */

    private function _make_row($data) {

        $id = modal_anchor(get_uri("suppliers/view/".$data->id), $data->id, array("title" => lang('view'), "data-post-id" => $data->id));
        $company_name = modal_anchor(get_uri("suppliers/view/".$data->id), $data->company_name, array("title" => lang('view'), "data-post-id" => $data->id));

        $due = 0;
        if ($data->purchase_order_value) {
            $due = ignor_minor_value($data->purchase_order_value - $data->payment_received);
        }

        $row_data = array($id,
            $company_name,
            $data->contact_name ? $data->contact_name : "",
            $data->email ? $data->email : "",
            $data->phone,
            to_currency($data->purchase_order_value, $data->currency_symbol),
            to_currency($data->payment_received, $data->currency_symbol),
            to_currency($due, $data->currency_symbol)
        );

        $rowe = "";
        if ($this->can_edit()) { 
            $rowe .= modal_anchor(get_uri("suppliers/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_supplier'), "data-post-id" => $data->id));
        }
        
        if ($this->can_delete()) { 
            $rowe .= js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_supplier'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("suppliers/delete"), "data-action" => "delete"));
        }

        $row_data[] = $rowe;


        return $row_data;
    }

    function view($id) {

        $view_data['model_info'] = $this->Suppliers_model->get_one($id);
        $this->load->view("suppliers/view",$view_data);
        
    }


}

/* End of file suppliers.php */
/* Location: ./application/controllers/suppliers.php */