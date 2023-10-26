<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Loans extends MY_Controller {

    function __construct() {
        parent::__construct();
        //$this->access_only_admin();
    }

    function index() {
        $this->template->rander("loans/index");
    }

    function modal_form() {

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Loans_model->get_one($this->input->post('id'));
        $view_data['users_dropdown'] = array("" => "-") + $this->Users_model->get_dropdown_list(array("first_name","last_name"), "id", array("status"=>"active", "user_type"=>"staff"));
        $this->load->view('loans/modal_form', $view_data);
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));


        $id = $this->input->post('id');
        $data = array(
            "user_id" => $this->input->post('user_id'),
            "amount" => $this->input->post('amount'),
            "date" => $this->input->post('date'),
        );

        $save_id = $this->Loans_model->save($data, $id);

        if ($save_id) {
            $this->loan_acc_entries($save_id);
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function loan_acc_entries($adv_salary_id = 0)
    {
        $adv_salary_info = $this->Loans_model->get_details(array("id" => $adv_salary_id))->row();
        $user_info = $this->Users_model->get_one($adv_salary_info->user_id);

        $date = get_today_date();
        $type = "ADVANCE SALARY FOR ".$adv_salary_info->employee_name;
        
        $acc_array = array();
        $narration = $type;
        $amount = $adv_salary_info->amount;


         $acc_array[] = array("account_id" => get_setting('salary_advances'), "type" => 'dr',"amount" => round($amount, 3), "narration" => $narration, "concerned_person"=>$adv_salary_info->user_id, "reference" => $adv_salary_id);

         $acc_array[] = array("account_id" => get_setting('default_bank'), "type" => 'cr',"amount" => round($amount, 3), "narration" => $narration, "concerned_person"=>$adv_salary_info->user_id, "reference" => $adv_salary_id);
            

        $transaction_id = make_transaction($date, $acc_array, $type);
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Loans_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Loans_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    function list_data() {
        $options = array("start_date" => $this->input->post("start_date"), "end_date" => $this->input->post("end_date"));
        $list_data = $this->Loans_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Loans_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    private function _make_row($data) {

        $delete = "";
        $edit = modal_anchor(get_uri("Loans/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_advance'), "data-post-id" => $data->id));

        $delete = js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_loans'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("Loans/delete"), "data-action" => "delete-confirmation"));

        return array(
            $data->id,
            $data->employee_name.' ('.get_employee_loans($data->user_id)['balance_loan'].'/- OMR Balance)',
            $data->amount,
            format_to_date($data->date,false),
            $edit . $delete
        );
    }

}

/* End of file Loans.php */
/* Location: ./application/controllers/Loans.php */