<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Service_provider extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->access_only_admin();
    }

    //load expense categories list view
    function index() {
        $view_data['can_create_module'] = true;
        $this->template->rander("service_provider/index", $view_data);
    }

    //load Service Provider add/edit modal form
    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));
        
        $view_data['model_info'] = $this->Service_provider_model->get_one($this->input->post('id'));
        $this->load->view('service_provider/modal_form', $view_data);
    }


    //save expense category
    function save() {

        validate_submitted_data(array(
            "id" => "numeric",
            "name" => "required",
        ));

        $id = $this->input->post('id');

        $data = array(
            "name" => $this->input->post('name'),
            "phone" => $this->input->post('phone'),
            "email" => $this->input->post('email'),
            "vat_number" => $this->input->post('vat_number')
        );
        $save_id = $this->Service_provider_model->save($data, $id);
        if ($save_id) {
            ////
            ////
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //delete/undo an expense category
    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Service_provider_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Service_provider_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    //get data for expenses category list
    function list_data() {
        $list_data = $this->Service_provider_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    //get an expnese category list row
    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Service_provider_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    //prepare an expense category list row
    private function _make_row($data) {
        return array($data->id,
            format_to_date($data->created_at,false),
            $data->name,
            $data->phone,
            $data->email,
            $data->vat_number,

            modal_anchor(get_uri("service_provider/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_expenses_category'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_expenses_category'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("service_provider/delete"), "data-action" => "delete"))
        );
    }

}

/* End of file service_provider.php */
/* Location: ./application/controllers/service_provider.php */