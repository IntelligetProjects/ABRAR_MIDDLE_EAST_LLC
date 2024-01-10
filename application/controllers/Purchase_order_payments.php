<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Purchase_order_payments extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->permission_checker("can_access_purchase_order_payments");
        $this->access_allowed_members();
    }

    private function can_edit() {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_edit_purchase_order_payments") == "1") {
                return true;
            }
        }
    }

    private function can_delete() {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_delete_purchase_order_payments") == "1") {
                return true;
            }
        }
    }

    private function can_approve() {
       
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "purchase_order_payments") == "all") {
                return true;
            }
        }
    }

    /* load purchase_order list view */

    function index() {
        if ($this->login_user->user_type === "staff") {
            $view_data['payment_method_dropdown'] = $this->get_payment_method_dropdown();
            $view_data['can_approve'] = $this->can_approve();
            $view_data["currencies_dropdown"] = $this->_get_currencies_dropdown();
            $this->template->rander("purchase_orders/payment_received", $view_data);
        } 
    }

    function get_payment_method_dropdown() {

        $payment_methods = $this->Payment_methods_model->get_all_where(array("deleted" => 0))->result();

        $payment_method_dropdown = array(array("id" => "", "text" => "- " . lang("payment_methods") . " -"));
        foreach ($payment_methods as $value) {
            $payment_method_dropdown[] = array("id" => $value->id, "text" => $value->title);
        }

        return json_encode($payment_method_dropdown);
    }

    //load the payment list yearly view
    function yearly() {
        $this->load->view("purchase_orders/yearly_payments");
    }

    //load custom payment list
    function custom() {
        $this->load->view("purchase_orders/custom_payments_list");
    }

    /* load payment modal */

    function payment_modal_form() {

        validate_submitted_data(array(
            "id" => "numeric",
            "purchase_order_id" => "numeric"
        ));

        $view_data['model_info'] = $this->Purchase_order_payments_model->get_one($this->input->post('id'));

        $purchase_order_id = $this->input->post('purchase_order_id') ? $this->input->post('purchase_order_id') : $view_data['model_info']->purchase_order_id;

        if (!$purchase_order_id) {
            //prepare purchase_orders dropdown
            $purchase_orders = $this->Purchase_orders_model->get_purchase_orders_dropdown_list()->result();
            $purchase_orders_dropdown = array();

            foreach ($purchase_orders as $purchase_order) {
                $purchase_orders_dropdown[$purchase_order->id] = get_purchase_order_id($purchase_order->id);
            }

            $view_data['purchase_orders_dropdown'] = array("" => "-") + $purchase_orders_dropdown;
        } else {

        $view_data["balance_due"] = $this->Purchase_orders_model->get_purchase_order_total_summary($purchase_order_id)->balance_due;
      
        }

        $view_data['payment_methods_dropdown'] =  array("" => "-") + $this->Payment_methods_model->get_dropdown_list(array("title"), "id", array("online_payable" => 0, "deleted" => 0));
        $view_data['purchase_order_id'] = $purchase_order_id;
        $view_data['banks_dropdown'] = array("" => "-") +$this->Accounts_model->get_dropdown_list(array("acc_name"), "id", array("acc_parent" => $this->Accounts_model->get_banking_accounts_id(), "deleted" => 0));
        $view_data['treasury'] =  array("" => "-") + $this->Accounts_model->get_dropdown_list(array("acc_name"), "id", array("acc_parent" => $this->Accounts_model->get_treasury_accounts_id(), "deleted" => 0));
        $this->load->view('purchase_orders/payment_modal_form', $view_data);
    }

    /* add or edit a payment */

    function save_payment() {

        validate_submitted_data(array(
            "id" => "numeric",
            "purchase_order_id" => "required|numeric",
            "purchase_order_payment_method_id" => "required|numeric",
            "purchase_order_payment_date" => "required",
            "purchase_order_payment_amount" => "required"
        ));

        $id = $this->input->post('id');
        $purchase_order_id = $this->input->post('purchase_order_id');

        $purchase_order_payment_data = array(
            "purchase_order_id" => $purchase_order_id,
            "payment_date" => $this->input->post('purchase_order_payment_date'),
            "payment_method_id" => $this->input->post('purchase_order_payment_method_id'),
            "note" => $this->input->post('purchase_order_payment_note'),
            "amount" => unformat_currency($this->input->post('purchase_order_payment_amount')),
            "created_at" => get_current_utc_time(),
            "created_by" => $this->login_user->id,
            "cheque_due_date" => $this->input->post('cheque_due_date'),
            "cheque_description" => $this->input->post('cheque_description'),
            "cheque_account" => $this->input->post('cheque_account'),
            "cheque_number" => $this->input->post('cheque_number'),
            "bank" => $this->input->post('bank')?$this->input->post('bank'):0,
            "treasury" => $this->input->post('treasury')?$this->input->post('treasury'):0,
        );
        ///file operation
        $target_path = get_setting("timeline_file_path");
        $files_data = move_files_from_temp_dir_to_permanent_dir($target_path, "invoice");
        $new_files = unserialize($files_data);

        if($id){
            $event_info = $this->Purchase_order_payments_model->get_one($id);
            $timeline_file_path = get_setting("timeline_file_path");
            $new_files = update_saved_files($timeline_file_path, $event_info->files, $new_files);
        }
        //file data
        $purchase_order_payment_data["files"] = serialize($new_files);

        $purchase_order_payment_id = $this->Purchase_order_payments_model->save($purchase_order_payment_data, $id);
        if ($purchase_order_payment_id) {

            //As receiving payment for the purchase_order, we'll remove the 'draft' status from the purchase_order 
            $this->Purchase_orders_model->update_purchase_order_status($purchase_order_id);

            if (!$id) { //show payment confirmation for new payments only
                log_notification("purchase_order_payment_confirmation", array("purchase_order_payment_id" => $purchase_order_payment_id, "purchase_order_id" => $purchase_order_id), "0");
            }
            //get payment data
            $options = array("id" => $purchase_order_payment_id);
            $item_info = $this->Purchase_order_payments_model->get_details($options)->row();
            echo json_encode(array("success" => true, "purchase_order_id" => $item_info->purchase_order_id, "data" => $this->_make_payment_row($item_info), "purchase_order_total_view" => $this->_get_purchase_order_total_view($item_info->purchase_order_id), 'id' => $purchase_order_payment_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    /* delete or undo a payment */

    function delete_payment() {

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Purchase_order_payments_model->delete($id, true)) {
                $options = array("id" => $id);
                $item_info = $this->Purchase_order_payments_model->get_details($options)->row();
                echo json_encode(array("success" => true, "purchase_order_id" => $item_info->purchase_order_id, "data" => $this->_make_payment_row($item_info), "purchase_order_total_view" => $this->_get_purchase_order_total_view($item_info->purchase_order_id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Purchase_order_payments_model->delete($id)) {
                $item_info = $this->Purchase_order_payments_model->get_one($id);
                echo json_encode(array("success" => true, "purchase_order_id" => $item_info->purchase_order_id, "purchase_order_total_view" => $this->_get_purchase_order_total_view($item_info->purchase_order_id), 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of purchase_order payments, prepared for datatable  */

    function payment_list_data($purchase_order_id = 0) {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $payment_method_id = $this->input->post('payment_method_id');
        $options = array(
            "start_date" => $start_date,
            "end_date" => $end_date,
            "purchase_order_id" => $purchase_order_id,
            "payment_method_id" => $payment_method_id,
            "currency" => $this->input->post("currency"),
        );

        $list_data = $this->Purchase_order_payments_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_payment_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    /* list of purchase_order payments, prepared for datatable  */

    function payment_list_data_of_project($project_id = 0) {
        $options = array("project_id" => $project_id);
        $list_data = $this->Purchase_order_payments_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_payment_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    /* prepare a row of purchase_order payment list table */

    private function _make_payment_row($data) {
        set_row_data_currency_rate($data->currency_rate_at_creation);
        $purchase_order_url = "";


        $purchase_order_url = anchor(get_uri("purchase_orders/view/" . $data->purchase_order_id), get_purchase_order_id($data->purchase_order_id));


        $user_image = get_avatar($data->user_image);
        $user = "<span class='avatar avatar-xs mr10'><img src='$user_image' alt=''></span>" . $data->user_name;

        $supplier_name = $data->company_name;
        //// permission
        if ($data->status == 'draft' || $this->can_approve()) {
            $stat = js_anchor(lang($data->status), array('title' => lang("status"), "class" => "btn btn-default", "data-id" => $data->id, "data-value" => $data->status, "data-act" => "update-status"));
        } else {
            $stat = $this->_get_approval_status_label($data);
        }

        $payment_method = "<b> ".$data->payment_method_title."</b> ";

        if($data->payment_method_id == 5){
            if ($data->cheque_due_date) {
                if ($payment_method) {
                    $payment_method .= "<br /> ";
                }
                $payment_method .= lang("due_date") . ": " . $data->cheque_due_date;
            }

            if ($data->cheque_description) {
                if ($payment_method) {
                    $payment_method .= "<br /> ";
                }
                $payment_method .= lang("description") . ": " . $data->cheque_description;
            }

            if ($data->cheque_account) {
                if ($payment_method) {
                    $payment_method .= "<br /> ";
                }
                $payment_method .= lang("account") . ": " . $data->cheque_account;
            }

            if ($data->cheque_number) {
                if ($payment_method) {
                    $payment_method .= "<br /> ";
                }
                $payment_method .= lang("number") . ": " . $data->cheque_number;
            }
        }

        
        $files = unserialize($data->files);
        if ($files && count($files)) {
                $file_name = get_array_value($files[0], "file_name");
                $img_path=base_url().'/files/timeline_files/'. $file_name;
                $file_url="<a href=".$img_path." target='_blank'>Show</a>";
        }else{
            $file_url ='-';
        }
        $row =  array(
            $purchase_order_url,
            $supplier_name,
            $data->payment_date,
            format_to_date($data->payment_date, false),
            $payment_method,
            $data->note,
            $stat,
            to_currency($data->amount, $data->currency_symbol),
            $user,
            $file_url,
            $data->created_at,
        );

        $rowe = "";
        if ($this->can_edit() && $data->status == "draft") {
            $rowe .= modal_anchor(get_uri("purchase_order_payments/payment_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_payment'), "data-post-id" => $data->id, "data-post-purchase_order_id" => $data->purchase_order_id,));
        }
        if ($this->can_delete() && $data->status == "draft") {
            $rowe .= js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("purchase_order_payments/delete_payment"), "data-action" => "delete"));
        }
        $row[] = $rowe;

        unset_row_data_currency_rate();
        return $row;
    }

    /* purchase_order total section */

    private function _get_purchase_order_total_view($purchase_order_id = 0) {
        $view_data["purchase_order_total_summary"] = $this->Purchase_orders_model->get_purchase_order_total_summary($purchase_order_id);
        $view_data["purchase_order_id"] = $purchase_order_id;
        $view_data["approval_status"] = $this->Purchase_orders_model->get_one($purchase_order_id)->approval_status;
        return $this->load->view('purchase_orders/purchase_order_total_section', $view_data, true);
    }

    //prepare invoice status label 
    private function _get_approval_status_label($data, $return_html = true) {
        return get_po_payment_status_label($data, $return_html);
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

        $status_id = $this->Purchase_order_payments_model->save($data, $id);
        if ($status_id) {
            echo json_encode(array("success" => true, 'message' => 'success'));
            $info = $this->Purchase_order_payments_model->get_details(array("id" => $status_id))->row();
            if ($stat == "request_approval") {
                $this->delete_entries($info);
            } elseif ($stat == "draft") {
                $this->delete_entries($info);
            } elseif ($stat == "approved") {
                $this->make_entries($info);
                $this->pushes($info);
            } 
        }
 
    }


    function make_entries($info) {
        $date = $info->payment_date;
        $type = "Automatic Purchase Payment: ". get_purchase_order_id($info->purchase_order_id);
        
        $acc_array = array();
        $narration = $info->note;
        if($info->payment_method_id == 5){
            if ($info->cheque_due_date) {
                if ($narration) {
                    $narration .= "<br /> ";
                }
                $narration .= lang("due_date") . ": " . $info->cheque_due_date;
            }

            if ($info->cheque_account) {
                if ($narration) {
                    $narration .= "<br /> ";
                }
                $narration .= lang("account") . ": " . $info->cheque_account;
            }

            if ($info->cheque_number) {
                if ($narration) {
                    $narration .= "<br /> ";
                }
                $narration .= lang("number") . ": " . $info->cheque_number;
            }
            if ($info->cheque_description) {
                if ($narration) {
                    $narration .= "<br /> ";
                }
                $narration .= lang("description") . ": " . $info->cheque_description;
            }
        }

        $supplier_info = $this->Suppliers_model->get_one($info->supplier_id);
        $po_info = $this->Purchase_orders_model->get_one($info->purchase_order_id);

        $account_id = $supplier_info->account_id;
        

        if ($info->payment_method_id == 1) {
            $acc_array[] = array("account_id" => $account_id, "type" => 'dr',"amount" => $info->amount, "narration" => $narration);

            // $acc_array[] = array("account_id" => get_setting('default_cash_on_hand'), "type" => 'cr',"amount" => $info->amount, "narration" => $narration);
            $acc_array[] = array("account_id" => $info->treasury, "type" => 'cr',"amount" => $info->amount, "narration" => $narration);
            
        } else if ($info->payment_method_id == 4) {
            $acc_array[] = array("account_id" => $account_id, "type" => 'dr',"amount" => $info->amount, "narration" => $narration);

            // $acc_array[] = array("account_id" => get_setting('default_bank'), "type" => 'cr',"amount" => $info->amount, "narration" => $narration);
            $acc_array[] = array("account_id" => $info->bank, "type" => 'cr',"amount" => $info->amount, "narration" => $narration);
            
        } else if ($info->payment_method_id == 5) {
            $acc_array[] = array("account_id" => $account_id, "type" => 'dr',"amount" => $info->amount, "narration" => $narration);

            $acc_array[] = array("account_id" => get_setting('payable_cheques'), "type" => 'cr',"amount" => $info->amount, "narration" => $narration);
            // $acc_array[] = array("account_id" => $info->bank, "type" => 'cr',"amount" => $info->amount, "narration" => $narration);
            
        }

        //var_dump($acc_array);

        $transaction_id = make_transaction($date, $acc_array, $type);

        if ($transaction_id) {
            $trans_data = array("transaction_id" => $transaction_id);
            $this->Purchase_order_payments_model->save($trans_data, $info->id);
        }
    }


    function delete_entries($info) {   
        $trans_id = $info->transaction_id;
        $pre_trans_id = $info->prepayment_transaction_id;
        if ($trans_id !== 0) {
            $transaction_id = delete_transaction($trans_id);
            $prepayment_transaction_id = delete_transaction($pre_trans_id);
            if ($transaction_id) {
                $trans_data = array("transaction_id" => 0, "prepayment_transaction_id" => 0);
                $this->Purchase_order_payments_model->save($trans_data, $info->id);
            }
        }
        $cheque_trans_id = $info->cheque_transaction_id;
        if ($cheque_trans_id !== 0) {
            $cheque_transaction_id = delete_transaction($cheque_trans_id);
            if ($cheque_transaction_id) {
                $che_trans_data = array("cheque_transaction_id" => 0);
                $this->Purchase_order_payments_model->save($che_trans_data, $info->id);
            }
        }
    }

    function pushes($info) {   
        $notifationTo = $info->created_by;
        $body = 'The Payment of '.$info->amount." to ".$info->company_name.'  has been approved';
        send_onesignal($body, '#', $notifationTo, '', '');
    }

}

/* End of file payments.php */
/* Location: ./application/controllers/payments.php */