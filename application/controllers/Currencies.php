<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Currencies extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->access_only_admin();
    }

    function index() {
        $this->template->rander("currencies/index");
    }

    function modal_form() {

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Currencies_model->get_one($this->input->post('id'));
        $this->load->view('currencies/modal_form', $view_data);
    }

    function save() {

        validate_submitted_data(array(
            "id" => "numeric",
            "name" => "required",
            "symbol" => "required",
            "rate" => "required|numeric|greater_than[0]"
        ));
        
        $id = $this->input->post('id');

        if(!$this->can_add() || ($id && !$this->can_edit($id)) ){
            echo json_encode(array("success" => false, lang('forbidden')));
            return;
        }

        $data = array(
            "name" => $this->input->post('name'),
            "symbol" => $this->input->post('symbol'),
            "rate" => $this->input->post('rate')
        );
        $save_id = $this->Currencies_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "numeric|required"
        ));
        $id = $this->input->post('id');

        if(!$this->can_delete($id)){
            echo json_encode(array("success" => false, lang('forbidden')));
            return;
        }
    
        if ($this->input->post('undo')) {
            if ($this->Currencies_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Currencies_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    function list_data() {
        $list_data = $this->Currencies_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Currencies_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    private function _make_row($data) {

        $op = [];
        if($this->can_edit($data->id)){
           $op =  modal_anchor(get_uri("currencies/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_currencys'), "data-post-id" => $data->id));
        }

        if($this->can_delete($data->id)){
         $op .= js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_currency'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("currencies/delete"), "data-action" => "delete"));
        }
        $row_data =  array($data->name,
            $data->symbol,
            number_format($data->rate, 6),
            $op
        );
       return $row_data;
    }

    private function can_edit($id){
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin && $id != 1) {
                return true;
            }
        }
    }

    private function can_delete($id){
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin && $id != 1) {
                return true;
            }
        }
    }

    private function can_add(){
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin) {
                return true;
            }
        }
    }

    public function set_current_view_currency_in_session($currency_id){
        set_current_view_currency($currency_id);
        echo json_encode(array("success" => true, 'message' => lang('success')));
    }

}

/* End of file currencies.php */
/* Location: ./application/controllers/currencies.php */