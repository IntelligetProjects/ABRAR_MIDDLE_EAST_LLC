<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Contacts extends MY_Controller {

    function __construct() {
        parent::__construct();
        // $this->permission_checker("can_access_contacts");
        // $this->access_allowed_members();
    }

    //load list view
    function index() {
        if (!$this->login_user->is_admin) {
            $view_data['can_create_module'] = get_array_value($this->login_user->permissions,'can_create_contacts');
            } else {
                 $view_data['can_create_module'] = 1; 
            } 
            $view_data['can_create_module'] = 1; 
        $this->template->rander("contacts/index", $view_data);
    }

    //load add/edit modal form
    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
            
        ));
        
        $view_data['model_info'] = $this->Contacts_model->get_one($this->input->post('id'));
        $this->load->view('contacts/modal_form', $view_data);
    }

    //save
    function save() {

        validate_submitted_data(array(
            "id" => "numeric",
            "name" => "required"
        ));

        $id = $this->input->post('id');
        $data = array(
            "name" => $this->input->post('name'),
            "email" => $this->input->post('email'),
            "address" => $this->input->post('address'),
            "phone" => $this->input->post('phone'),
            "alternative_phone" => $this->input->post('alternative_phone'),
            "job_title" => $this->input->post('job_title'),
            "note" => $this->input->post('note'),
            /*"created_date" => get_current_utc_time(),
            "created_by" => $this->login_user->id,*/
        );

        if(!$id) {
            $data["created_date"] = get_current_utc_time();
            $data["created_by"] = $this->login_user->id;
        }

        $save_id = $this->Contacts_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //delete/undo
    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Contacts_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Contacts_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    //get data for  list
    function list_data() {
        $options = array();
        $this->permission_checker("contact_manage_permission");
        if(!$this->login_user->is_admin || !$this->access_type === "all"){
            $options["allowed_members"] = $this->allowed_members;
        }
        $list_data = $this->Contacts_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    //get an expnese category list row
    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Contacts_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    private function can_edit() {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_edit_contacts") == "1") {
                return true;
            }
        }
    }

    private function can_delete() {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_delete_contacts") == "1") {
                return true;
            }
        }
    }

    //prepare an item category list row
    private function _make_row($data) {
        $image_url = get_avatar($data->by_user_image);
        $by = "<span class='avatar avatar-xs mr10'><img src='$image_url' alt='...'></span> $data->by_user_name";

        $uploaded_by = get_team_member_profile_link($data->created_by, $by);
        $row = array($data->name,
            $data->job_title,
            $data->email,
            $data->phone,
            $data->alternative_phone,
            $data->address,
            $data->note,
            convert_date_utc_to_local($data->created_date, "Y-m-d"),
            $by, 
        );

        $rowe = "";     

        if ($this->can_edit()) { 
            $rowe .= modal_anchor(get_uri("contacts/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit'), "data-post-id" => $data->id));
        }
        
        if ($this->can_delete()) { 
            $rowe .= js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("contacts/delete"), "data-action" => "delete"));
        }

        $row[] = $rowe;

        return $row;
    }

}

/* End of file contacts.php */
/* Location: ./application/controllers/contacts.php */