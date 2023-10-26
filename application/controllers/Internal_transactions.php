<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Internal_transactions extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->init_permission_checker("internal_transaction");
        // $this->access_only_allowed_members();
    }

    private function can_approve() {
        $this->check_module_availability('module_petty_cash');
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "petty_cash") == "all") {
                return true;
            }
        }
    }

    //load  list view
    function index() {
        $this->check_module_availability('module_petty_cash');
        $view_data["can_approve"] = $this->can_approve();
        $this->template->rander("internal_transactions/index", $view_data);
    }

    //load expense category add/edit modal form
    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['from_dropdown'] =  array("" => "-") + array("0" => lang("cash_on_hand")) + array("999" => lang("bank")) + $this->Users_model->get_dropdown_list(array("first_name","last_name"), 'id', array("user_type" => "staff","deleted" => 0,"status" => "active"));


        $view_data['to_dropdown'] =  $this->Users_model->get_dropdown_list(array("first_name","last_name"), 'id', array("user_type" => "staff","deleted" => 0,"status" => "active"));

        $view_data['status_dropdown'] = array("draft"=>lang("draft"),"approved"=>lang("approved")); 

        $view_data['model_info'] = $this->Internal_transactions_model->get_one($this->input->post('id'));
        $view_data['banks_dropdown'] = array("" => "-") +$this->Accounts_model->get_dropdown_list(array("acc_name"), "id", array("acc_parent" => $this->Accounts_model->get_banking_accounts_id(), "deleted" => 0));
        $view_data['treasury'] =  array("" => "-") + $this->Accounts_model->get_dropdown_list(array("acc_name"), "id", array("acc_parent" => $this->Accounts_model->get_treasury_accounts_id(), "deleted" => 0));
       
        $this->load->view('internal_transactions/modal_form', $view_data);
    }

    //save expense category
    function save() {

        validate_submitted_data(array(
            "id" => "numeric",
            "date" => "required",
            "from_employee" =>"required",
            "to_employee" =>"required|differs[from_employee]|callback_not_bank_to_cash[from_employee]",
            "amount" =>"numeric|required",

        ));

        $id = $this->input->post('id');
        $data = array(
            "date" => $this->input->post('date'),
            "from_employee" => $this->input->post('from_employee'),
            "to_employee" => $this->input->post('to_employee'),
            "amount" => $this->input->post('amount'),
            "note" => $this->input->post('note'),
            "status" => $this->input->post('status') ? $this->input->post('status') : "draft",
            "treasury" => $this->input->post('treasury') ? $this->input->post('treasury') : 0,
            "bank" => $this->input->post('bank') ? $this->input->post('bank') : 0
        );
        $save_id = $this->Internal_transactions_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function not_bank_to_cash ($str, $bc) {

        if (($str == 0 || $str == 999) && ($bc == 0 || $bc == 999)) {
            $message = 'The {field} must be a team member';
             $this->form_validation->set_message('not_bank_to_cash', $message);
            return false;
        } else {
            return true;
        }

    }

    //delete/undo an expense category
    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Internal_transactions_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Internal_transactions_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    //get data for expenses category list
    function list_data() {

        $options = array();

        $list_data = $this->Internal_transactions_model->get_details($options)->result();


        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
            // var_dump($result);
        }
        echo json_encode(array("data" => $result));
    }

    //get an expnese category list row
    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Internal_transactions_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    private function approval() {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin) {
                return true;
            }
        }
    }

    //prepare an expense category list row
    private function _make_row($data) {

        $employees_cash = $this->PT_cash_model->get_details_to_date($data->date);
         
         $from_cash = '';
         $to_cash = '';

        if ($data->from_employee == '0') {
            $from = lang("cash_on_hand");
        } else if ($data->from_employee == '999'){
            $from = lang("bank");
        } else {
            $from = $data->first_name1 . " " . $data->last_name1;
            $from_cash = $employees_cash[$data->from_employee];
        }
        
        if ($data->to_employee == '0') {
            $to = lang("cash_on_hand");
        } else if ($data->to_employee == '999'){
            $to = lang("bank");
        } else {
            $to = $data->first_name2 . " " . $data->last_name2;
            $to_cash = $employees_cash[$data->to_employee];
        }
        
        //// permission
        if ($this->can_approve() || $data->status == "draft") {
            $stat = js_anchor(lang($data->status), array('title' => "status", "class" => "btn btn-default", "data-id" => $data->id, "data-value" => $data->status, "data-act" => "update-status"));
        } else {
            $stat = lang($data->status);
        }
       
        $row_data = array($data->date,
            $from,
            $to,
            number_format($data->amount, 3),
            $data->note,
            $from. ":" . $from_cash . "<br>" . $to . ":" . $to_cash,
            $stat,
        );
        $rowe = "";     
        
        if ($data->status == "draft") {
            $rowe = modal_anchor(get_uri("Internal_transactions/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit'), "data-post-id" => $data->id));
            
            $rowe .= js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("internal_transactions/delete"), "data-action" => "delete"));
        }
      
        $row_data[] = $rowe;

        return $row_data;
    }

    function update_status ($stat = '', $sent_id = 0){

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

        $status_id = $this->Internal_transactions_model->save($data, $id);
        if ($status_id) {
            echo json_encode(array("success" => true, 'message' => 'success'));
            $info = $this->Internal_transactions_model->get_one($status_id);
            if ($stat == "draft") {
                $this->delete_entries($info);
            } elseif ($stat == "approved") {
                $this->make_entries($info);
                $this->pushes($info);
            } 
        }

    }


    function make_entries($info) {
        $to_info = $this->Users_model->get_one($info->to_employee);
        $from_info = $this->Users_model->get_one($info->from_employee);
        $date = $info->date;
        // $type = "Automatic Internal Transaction";
        $acc_array = array();
        $narration = "";
        

       

        // if ($info->to_employee == "0") {
        //     $acc_array[] = array("account_id" => 19, "type" => 'dr',"amount" => $info->amount, "narration" => $narration);

        // } elseif ($info->to_employee == "2") {
        //     // $acc_array[] = array("account_id" => get_setting('default_bank')?get_setting('default_bank'):52, "type" => 'dr',"amount" => $info->amount, "narration" => $narration);
        //     $acc_array[] = array("account_id" =>$info->bank, "type" => 'dr',"amount" => $info->amount, "narration" => $narration);
            
        // } else {
        //     // $acc_array[] = array("account_id" => $to_info->pt_account_id, "type" => 'dr',"amount" => $info->amount, "narration" => $narration);
        //     $acc_array[] = array("account_id" => $to_info->pt_account_id, "type" => 'dr',"amount" => $info->amount, "narration" => $narration);
            
        // }
        $acc_array[] = array("account_id" => get_setting('petty_cash_parent'), "type" => 'dr',"amount" => $info->amount, "narration" => $narration);
        if ($info->from_employee == "0") {
                $type = "Petty Cash From Cash" . " To ".$to_info->first_name ;
                if (!empty($info->note)) {
                    $type .= ": ".$info->note;
                }
                // $acc_array[] = array("account_id" => 19, "type" => 'cr',"amount" => $info->amount, "narration" => $narration);
                $acc_array[] = array("account_id" =>$info->treasury, "type" => 'cr',"amount" => $info->amount, "narration" => $narration);
            
        } elseif ($info->from_employee == "999") {
            $type = "Petty Cash From Bank" . " To ".$to_info->first_name ;
            if (!empty($info->note)) {
                $type .= ": ".$info->note;
            }
            $acc_array[] = array("account_id" => $info->bank, "type" => 'cr',"amount" => $info->amount, "narration" => $narration);
            
        }
         else {
            $type = "Petty Cash From " .$from_info->first_name . " To ".$to_info->first_name ;
            if (!empty($info->note)) {
                $type .= ": ".$info->note;
            }
            $acc_array[] = array("account_id" => get_setting('petty_cash_parent'), "type" => 'cr',"amount" => $info->amount, "narration" => $narration);
            
        }

        

        //var_dump($acc_array);

        $transaction_id = make_transaction($date, $acc_array, $type);

        if ($transaction_id) {
            $trans_data = array("transaction_id" => $transaction_id);
            $this->Internal_transactions_model->save($trans_data, $info->id);
        }
    }


    function delete_entries($info) {   
        $trans_id = $info->transaction_id;
        if ($trans_id !== 0) {
            $transaction_id = delete_transaction($trans_id);
            if ($transaction_id) {
                $trans_data = array("transaction_id" => 0);
                $this->Internal_transactions_model->save($trans_data, $info->id);
            }
        }
    }

    function pushes($info) {
        if ($info->to_employee != 999 && $info->to_employee != 0) { 
            $notifationTo = $info->to_employee;
            $body = 'You have received an Internal Transaction of amount ('.$info->amount.')';
            send_onesignal($body, '#', $notifationTo, '', '');
        }
    }

    function testtest() {

        $notifationTo = $this->login_user->id;
        $body = 'You have received an Internal Transaction of amount ('."45454".')';
        send_onesignal($body, '#', $notifationTo, '', '');
            
    }

}

/* End of file Internal_transactions.php */
/* Location: ./application/controllers/Internal_transactions.php */