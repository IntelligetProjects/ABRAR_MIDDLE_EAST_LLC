<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cheques extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->init_permission_checker("accounting");
        $this->access_only_allowed_members();
    }

    private function can_approve_exp() {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "expenses") == "all") {
                return true;
            }
        }
    }
    private function can_approve_po() {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "purhchase_order_payments") == "all") {
                return true;
            }
        }
    }
    private function can_approve_inv() {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "invoice_payments") == "all") {
                return true;
            }
        }
    }


    
    public function index() {
        $view_data = array();
        $this->template->rander("Accounts/cheques", $view_data); 
    }

    function expenses() {
        $view_data['can_approve'] = $this->can_approve_exp();
        $this->load->view("Accounts/expense_cheques", $view_data);
    }

    function purchase_payments() {
        $view_data['can_approve'] = $this->can_approve_po();
        $this->load->view("Accounts/purchase_cheques", $view_data);
    }

    function received_payments() {
        $view_data['can_approve'] = $this->can_approve_inv();
        $this->load->view("Accounts/invoice_cheques", $view_data);
    }

    //get the expnese list data
    function list_data() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $category_id = $this->input->post('category_id');
        $project_id = $this->input->post('project_id');
        $user_id = $this->input->post('user_id');
        $client_id = $this->input->post('client_id');
        $payment_mode = $this->input->post('payment_mode');
        $options = array("start_date" => $start_date, "end_date" => $end_date, "category_id" => $category_id, "project_id" => $project_id, "user_id" => $user_id, "client_id" => $client_id, "payment_mode"=> 'cheque', "status"=> '');


        $list_data = $this->Expenses_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }


    //prepare a row of expnese list
    private function _make_row($data) {
        set_row_data_currency_rate($data->currency_rate_at_creation); // used for cost centre

        $description = $data->description;
        if ($data->project_title) {
            if ($description) {
                $description .= "<br /> ";
            }
            $description .= lang("project") . ": " . $data->project_title;
        }

        if ($data->linked_user_name) {
            if ($description) {
                $description .= "<br /> ";
            }
            $description .= lang("team_member") . ": " . $data->linked_user_name;
        }

        if ($data->company_name) {
            if ($description) {
                $description .= "<br /> ";
            }
            $description .= lang("client") . ": " . $data->company_name;
        }

        $payment_mode = "<b> ".lang($data->payment_mode)."</b> ";

        if($data->payment_mode == "cheque"){
            if ($data->cheque_due_date) {
                if ($payment_mode) {
                    $payment_mode .= "<br /> ";
                }
                $payment_mode .= lang("due_date") . ": " . $data->cheque_due_date;
            }

            if ($data->cheque_description) {
                if ($payment_mode) {
                    $payment_mode .= "<br /> ";
                }
                $payment_mode .= lang("description") . ": " . $data->cheque_description;
            }

            if ($data->cheque_account) {
                if ($payment_mode) {
                    $payment_mode .= "<br /> ";
                }
                $payment_mode .= lang("account") . ": " . $data->cheque_account;
            }

            if ($data->cheque_number) {
                if ($payment_mode) {
                    $payment_mode .= "<br /> ";
                }
                $payment_mode .= lang("number") . ": " . $data->cheque_number;
            }
        }

        $files_link = "";
        if ($data->files) {
            $files = unserialize($data->files);
            if (count($files)) {
                foreach ($files as $key => $value) {
                    $file_name = get_array_value($value, "file_name");
                    $link = " fa fa-" . get_file_icon(strtolower(pathinfo($file_name, PATHINFO_EXTENSION)));
                    $files_link .= js_anchor(" ", array('title' => "", "data-toggle" => "app-modal", "data-sidebar" => "0", "class" => "pull-left font-22 mr10 $link", "title" => remove_file_prefix($file_name), "data-url" => get_uri("expenses/file_preview/" . $data->id . "/" . $key)));
                }
            }
        }

        $tax = 0;
        $tax2 = 0;
        if ($data->tax_percentage) {
            $tax = $data->amount * ($data->tax_percentage / 100);
        }
        if ($data->tax_percentage2) {
            $tax2 = $data->amount * ($data->tax_percentage2 / 100);
        }

        //// permission
        if ($this->can_approve_exp() || $data->status == "draft") {
            $stat = js_anchor(lang($data->status), array('title' => "status", "class" => "btn btn-default", "data-id" => $data->id, "data-value" => $data->status, "data-act" => "update-status"));
        } else {
            $stat = lang($data->status);
        }


        $row_data = array(
            $data->expense_date,
            format_to_date($data->expense_date, false),
            $data->category_title,
            $data->title,
            $description,
            $files_link,
            to_currency($data->amount),
            $payment_mode,
            $stat,
            to_currency($tax),
            to_currency($tax2),
            to_currency($data->amount + $tax + $tax2)
        );


        $rowe = "";
        if ($data->status == "approved") {
            $rowe .= 
            js_anchor(lang($data->cheque_transaction_id == 0 ? lang("not_cleared") : lang("cleared")), array('title' => "cheque_status", "class" => "btn btn-default", "data-id" => $data->id, "data-value" => $data->cheque_transaction_id, "data-act" => "update-cheque_status"));
        }
        
        $row_data[] = $rowe;

        unset_row_data_currency_rate(); // used for cost centre

        return $row_data;
    }

    /* list of invoice payments, prepared for datatable  */

    function invoice_payment_list_data($invoice_id = 0) {

        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $payment_method_id = $this->input->post('payment_method_id');
        $options = array(
            "start_date" => $start_date,
            "end_date" => $end_date,
            "invoice_id" => $invoice_id,
            "payment_method_id" => 5,
            "currency" => $this->input->post("currency"),
            "status"=> ''
        );

        $list_data = $this->Invoice_payments_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_invoice_payment_row($data);
        }
        echo json_encode(array("data" => $result));
    }


    private function _make_invoice_payment_row($data) {
        set_row_data_currency_rate($data->currency_rate_at_creation); //used for cost center
        $invoice_url = "";

        if ($this->login_user->user_type == "staff") {
            $invoice_url = anchor(get_uri("invoices/view/" . $data->invoice_id), get_invoice_id($data->invoice_id));
        } else {
            $invoice_url = anchor(get_uri("invoices/preview/" . $data->invoice_id), get_invoice_id($data->invoice_id));
        }

        $user_image = get_avatar($data->user_image);
        $user = "<span class='avatar avatar-xs mr10'><img src='$user_image' alt=''></span>" . $data->user_name;

        $client_name = anchor(get_uri("clients/view/" . $data->client_id), $data->company_name);

         //// permission
        if ($this->can_approve_inv() || $data->status == "draft") {
            $stat = js_anchor(lang($data->status), array('title' => lang("status"), "class" => "btn btn-default", "data-id" => $data->id, "data-value" => $data->status, "data-act" => "update-status"));
        } else {
            $stat = lang($data->status);
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

        $row = array(
            $invoice_url,
            $client_name,
            $data->payment_date,
            format_to_date($data->payment_date, false),
            $payment_method,
            $data->note,
            $stat,
            to_currency($data->amount, $data->currency_symbol),
            $user,
            $data->created_at,
        );

        $rowe = "";
        if ($data->status == "approved") {
        $rowe .= 
        js_anchor(lang($data->cheque_transaction_id == 0 ? lang("not_cleared") : lang("cleared")), array('title' => lang("cheque_status"), "class" => "btn btn-default", "data-id" => $data->id, "data-value" => $data->cheque_transaction_id, "data-act" => "update-cheque_status"));
    }
        $row[] = $rowe;

        unset_row_data_currency_rate();
        return $row;
    } 

    /* list of purchase_order payments, prepared for datatable  */
    function purchase_payment_list_data($purchase_order_id = 0) {

        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $payment_method_id = $this->input->post('payment_method_id');
        $options = array(
            "start_date" => $start_date,
            "end_date" => $end_date,
            "purchase_order_id" => $purchase_order_id,
            "payment_method_id" => 5,
            "currency" => $this->input->post("currency"),
            "status"=> ''
        );

        $list_data = $this->Purchase_order_payments_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_purchase_payment_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    /* prepare a row of purchase_order payment list table */

    private function _make_purchase_payment_row($data) {
        set_row_data_currency_rate($data->currency_rate_at_creation); //used for cost center
        $purchase_order_url = "";


        $purchase_order_url = anchor(get_uri("purchase_orders/view/" . $data->purchase_order_id), get_purchase_order_id($data->purchase_order_id));


        $user_image = get_avatar($data->user_image);
        $user = "<span class='avatar avatar-xs mr10'><img src='$user_image' alt=''></span>" . $data->user_name;

        $supplier_name = $data->company_name;

         //// permission
        if ($this->can_approve_po() || $data->status == "draft") {
            $stat = js_anchor(lang($data->status), array('title' => "status", "class" => "btn btn-default", "data-id" => $data->id, "data-value" => $data->status, "data-act" => "update-status"));
        } else {
            $stat = lang($data->status);
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
            $data->created_at,
        );

        $rowe = '';
        if ($data->status == "approved") {
        $rowe .= 
        js_anchor(lang($data->cheque_transaction_id == 0 ? lang("not_cleared") : lang("cleared")), array('title' => "cheque_status", "class" => "btn btn-default", "data-id" => $data->id, "data-value" => $data->cheque_transaction_id, "data-act" => "update-cheque_status"));
    }

        $row[] = $rowe;

        unset_row_data_currency_rate();
        return $row;
    }

    function expense_transaction($id) {

        if ($id) {

            $info = $this->Expenses_model->get_one($id);

            $date = $info->expense_date;
            $type = "Cheque Release: Expense";
            
            $acc_array = array();
            $narration = $info->description;

            ///from payable to bank

            $acc_array[] = array("account_id" => get_setting('payable_cheques'), "type" => 'dr',"amount" => $info->amount, "narration" => $narration);

            // $acc_array[] = array("account_id" => get_setting('default_bank'), "type" => 'cr',"amount" => $info->amount, "narration" => $narration);
            $acc_array[] = array("account_id" =>  $info->bank, "type" => 'cr',"amount" => $info->amount, "narration" => $narration);

            $transaction_id = make_transaction($date, $acc_array, $type);

            if ($transaction_id) {
                $trans_data = array("cheque_transaction_id" => $transaction_id);
                $this->Expenses_model->save($trans_data, $info->id);
            }

            echo json_encode(array("success" => true, 'message' => lang('record_saved'))); 
               
        }

        return "";
    }

    function invoice_transaction($id) {

        if ($id) {

            $info = $this->Invoice_payments_model->get_one($id);

            $date = $info->payment_date;
            $type = "Cheque collection: Received Payments ".get_invoice_id($info->invoice_id);
            
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

            ///from receivable to bank

            $acc_array[] = array("account_id" => get_setting('receivable_cheques'), "type" => 'cr',"amount" => $info->amount, "narration" => $narration);
            $acc_array[] = array("account_id" => $info->bank, "type" => 'dr',"amount" => $info->amount, "narration" => $narration);

            $transaction_id = make_transaction($date, $acc_array, $type);

            if ($transaction_id) {
                $trans_data = array("cheque_transaction_id" => $transaction_id);
                $this->Invoice_payments_model->save($trans_data, $info->id);
            }

            echo json_encode(array("success" => true, 'message' => lang('record_saved'))); 
               
        }

        return "";
    }

    function purchase_transaction($id) {

        if ($id) {

            $info = $this->Purchase_order_payments_model->get_one($id);

            $date = $info->payment_date;
            $type = "Cheque release: Received Payments ".get_purchase_order_id($info->purchase_order_id);
            
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

            ///from receivable to bank

            $acc_array[] = array("account_id" => get_setting('payable_cheques'), "type" => 'dr',"amount" => $info->amount, "narration" => $narration);
            // $acc_array[] = array("account_id" => get_setting('default_bank'), "type" => 'cr',"amount" => $info->amount, "narration" => $narration);
            $acc_array[] = array("account_id" =>$info->bank, "type" => 'cr',"amount" => $info->amount, "narration" => $narration);

            $transaction_id = make_transaction($date, $acc_array, $type);

            if ($transaction_id) {
                $trans_data = array("cheque_transaction_id" => $transaction_id);
                $this->Purchase_order_payments_model->save($trans_data, $info->id);
            }

            echo json_encode(array("success" => true, 'message' => lang('record_saved'))); 
               
        }

        return "";
    }

}