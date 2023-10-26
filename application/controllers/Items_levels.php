<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Items_levels extends MY_Controller {

    function __construct() {
        parent::__construct();
    }

    //load item categories list view
    function index() {
        $this->template->rander("items_levels/index");
    }

    //load item category add/edit modal form
    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));
        
        $view_data['model_info'] = $this->Items_levels_model->get_one($this->input->post('id'));
        $this->load->view('items_levels/modal_form', $view_data);
    }

    //save item category
    function save() {

        validate_submitted_data(array(
            "id" => "numeric",
            "level_name" => "required"
        ));

        $id = $this->input->post('id');
        $data = array(
            "level_name" => $this->input->post('level_name')
        );
        $save_id = $this->Items_levels_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //delete/undo an item category
    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Items_levels_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Items_levels_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    //get data for items category list
    function list_data() {
        $list_data = $this->Items_levels_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    //get an expnese category list row
    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Items_levels_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    //prepare an item category list row
    private function _make_row($data) {
        return array($data->id,$data->level_name,
            modal_anchor(get_uri("Items_levels/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "level_name" => lang('edit_items_category'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('level_name' => lang('delete_items_category'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("Items_levels/delete"), "data-action" => "delete"))
        );
    }

}

/* End of file item_categories.php */
/* Location: ./application/controllers/item_categories.php */