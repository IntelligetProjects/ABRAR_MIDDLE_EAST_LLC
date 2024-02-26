<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Banking extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->init_permission_checker("accounting");
        $this->access_only_allowed_members();
    }

    
    public function index() {
        $this->template->rander("Accounts/banks"); 
    }

    public function default_bank() {
        $view_data['dropdown'] = array("" => "-") + $this->Accounts_model->get_dropdown_list(array("acc_name","acc_code"), "id", array("deleted" => 0, "acc_parent" => 8));
 
        $this->load->view('Accounts/default_bank', $view_data);

    }

    function save_default_bank() {
        validate_submitted_data(array(
            "account" => "numeric|required"
        ));
        $this->Settings_model->save_setting("default_bank", $this->input->post("account"));

        echo json_encode(array("success" => true, 'message' => lang('settings_updated')));
    }

    public function bank_modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');

        $model_info = $this->Accounts_model->get_one($id);
        // $model_info->acc_parent = 13;
        $view_data['model_info'] = $model_info;

        // $child_count = $this->Accounts_model->get_all_where(array("deleted"=> 0, "acc_parent" => $id))->num_rows();
        // $is_parent = $child_count == 0 ? 0 : 1;
        // $entries_count = $this->Enteries_model->get_all_where(array("deleted"=> 0, "account" => $id))->num_rows();
        // $has_entries = $entries_count == 0 ? 0 : 1;

        // $view_data["is_parent"] = $is_parent;
        // $view_data["has_entries"] = $has_entries;

        // $list = array(1,2,3,4,5,6);

        // $childs = $this->Accounts_model->get_direct_childern(array("accounts" => $list));
        // $childs = explode(",", $childs);


        // // list where relating with module record is not allowed
        // $list = array_merge($childs, $list);

        // $list = array_map('strval', $list);

        // $view_data["list"] = $list;


        $this->load->view('Accounts/bank_modal_form', $view_data);
    }


    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Accounts_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Accounts_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }


    public function list_data() {
    	$list_data = $this->Accounts_model->get_banking_accounts();
        $result = array();
        foreach ($list_data as $data) {
            $bal = $this->Accounts_model->get_balance($data->id);
            $result[] = $this->_make_row($data, $bal);
        }
        echo json_encode(array("data" => $result));
    }

    //get an expnese category list row
    private function _row_data($id) {
        $data = $this->Accounts_model->get_one($id);
        $bal = $this->Accounts_model->get_balance($id);
        return $this->_make_row($data, $bal);
    }

    //prepare an item category list row
    private function _make_row($data, $bal) {
        $balance = $bal['total'];
        $balance_type = $bal['total_type'];
        $row = array(anchor(get_uri("accounts/view/" . $data->id), $data->acc_name),
            anchor(get_uri("accounts/view/" . $data->id), $data->acc_code),
            $balance . " " . $balance_type,  
        );
        $entries_count = $this->Enteries_model->get_all_where(array("deleted"=> 0, "account" => $data->id))->num_rows();
        if ($entries_count == 0 && $data->id != 52) {
            $row[] = modal_anchor(get_uri("banking/bank_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit'), "data-post-id" => $data->id))  . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("banking/delete"), "data-action" => "delete"));
        } else {
            $row[] = modal_anchor(get_uri("banking/bank_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit'), "data-post-id" => $data->id));
        }
        return $row;
    }

    public function transfer_money() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));
        $banks_accounts_parent = get_setting('banks_accounts_parent');
        $cash_on_hand_accounts_parent = get_setting('cash_on_hand_accounts_parent');
        // $view_data['from_to_dropdown'] = array("" => "-") + $this->Accounts_model->get_dropdown_list(array("acc_name","acc_code"), "id", array("deleted" => 0, "acc_parent" => 8));
        $banks = array("" => "-") + $this->Accounts_model->get_dropdown_list(array("acc_name","acc_code"), "id", array("deleted" => 0, "acc_parent" => $banks_accounts_parent));
        $safes =$this->Accounts_model->get_dropdown_list(array("acc_name","acc_code"), "id", array("deleted" => 0, "acc_parent" =>  $cash_on_hand_accounts_parent));
        $view_data['from_to_dropdown'] = $banks+ $safes;

        $this->load->view('Accounts/transfer_money', $view_data);

    }

    function save_transfer_money() {
        validate_submitted_data(array(
            "date" => "required",
            "amount" => "required|numeric",
            "to" => "required|numeric|differs[from]",
            "from" => "required|numeric"         
        ));


        $transaction_data = array(
            "date" => $this->input->post('date'),
            "type" => "Bank Money Transfer",
            "is_manual" => 1,
        );

        $transaction_id = $this->Transactions_model->save($transaction_data);  

        if ($transaction_id) {

            $data1 = array(
                            'account' => $this->input->post('from'),            
                            'type' => 'cr',
                            'amount' => $this->input->post('amount'),
                            'narration' => $this->input->post('narration'),
                            'trans_id' => $transaction_id
                        );

            $this->Enteries_model->save($data1);

            $data2 = array(
                            'account' => $this->input->post('to'),            
                            'type' => 'dr',
                            'amount' => $this->input->post('amount'),
                            'narration' => $this->input->post('narration'),
                            'trans_id' => $transaction_id
                        );

            $this->Enteries_model->save($data2);
        }
        
        if ($transaction_id) {
        echo json_encode(array("success" => true, 'id' => $transaction_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    public function test($acc) {
        /*$list = $this->Accounts_model->get_entries(7);
        $banks = array();
        $bank_accounts = $this->Accounts_model->get_children(18)->list;
        if($bank_accounts) {
           $banks = implode(',',  $bank_accounts);  
        } 
        $banks[] = 18;*/
        var_dump(get_setting('default_bank'));
    }   

}