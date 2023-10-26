<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Proforma_invoice_payments extends MY_Controller {

    function __construct() { 
        parent::__construct();
        $this->permission_checker("can_access_invoice_payments");
    }

    private function can_edit() {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_edit_invoice_payments") == "1") {
                return true;
            }
        }
    }

    private function can_delete() {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_delete_invoice_payments") == "1") {
                return true;
            }
        }
    }

    private function can_approve() {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "invoice_payments") == "all") {
                return true;
            }
        }
    }

    /* load invoice list view */

    function index($search="", $id=0) {
        if ($this->login_user->user_type === "staff") {
            $view_data['payment_method_dropdown'] = $this->get_payment_method_dropdown();
            $view_data["currencies_dropdown"] = $this->_get_currencies_dropdown();
            $view_data['can_approve'] = $this->can_approve();


            if ($search && $id) {
                $view_data['search_id'] = $id;
            }


            if($this->input->post("is_widget"))
            {
                $view_data["is_widget"] = true;
                $this->load->view("invoices/payment_received", $view_data);  
            }
            else
            {
                $this->template->rander("invoices/payment_received", $view_data);
            }
            
        } else {
            $view_data["client_info"] = $this->Clients_model->get_one($this->login_user->client_id);
            $view_data['client_id'] = $this->login_user->client_id;
            $view_data['page_type'] = "full";
            $view_data['can_approve'] = $this->can_approve();
            $this->template->rander("clients/payments/index", $view_data);
        }
    }

    function get_payment_method_dropdown() {
        $this->access_only_team_members();

        $payment_methods = $this->Payment_methods_model->get_all_where(array("deleted" => 0))->result();

        $payment_method_dropdown = array(array("id" => "", "text" => "- " . lang("payment_methods") . " -"));
        foreach ($payment_methods as $value) {
            $payment_method_dropdown[] = array("id" => $value->id, "text" => $value->title);
        }

        return json_encode($payment_method_dropdown);
    }

    //load the payment list yearly view
    function yearly() {
        $this->load->view("invoices/yearly_payments");
    }

    //load custom payment list
    function custom() {
        $this->load->view("invoices/custom_payments_list");
    }

    /* load payment modal */

    function payment_modal_form() {
        $this->access_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric",
            "invoice_id" => "numeric"
        ));

        $view_data['model_info'] = $this->Proforma_invoice_payments_model->get_one($this->input->post('id'));


        $invoice_id = $this->input->post('invoice_id') ? $this->input->post('invoice_id') : $view_data['model_info']->invoice_id;

        

        if (!$invoice_id) {
            //prepare invoices dropdown
            $invoices = $this->Proforma_invoices_model->get_invoices_dropdown_list()->result();
            $invoices_dropdown = array();

            foreach ($invoices as $invoice) {
                $invoices_dropdown[$invoice->id] = get_invoice_id($invoice->id);
            }

            $view_data['invoices_dropdown'] = array("" => "-") + $invoices_dropdown;
        } else {
            $view_data["balance_due"] = $this->Proforma_invoices_model->get_invoice_total_summary($invoice_id)->balance_due;
        }

        $view_data['payment_methods_dropdown'] = $this->Payment_methods_model->get_dropdown_list(array("title"), "id", array("online_payable" => 0, "deleted" => 0));

        $view_data['banks'] = array(0 => "Default Option");
        if($this->can_approve()) {
            $view_data['banks'] = $view_data['banks'] + $this->Accounts_model->get_dropdown_list(array("acc_name"), "id", array("acc_parent" => $this->Accounts_model->get_banking_accounts_id(), "deleted" => 0));
        }
        $view_data['invoice_id'] = $invoice_id;

        $this->load->view('proforma_invoices/payment_modal_form', $view_data);
    }

    /* add or edit a payment */

    function save_payment() {
        $this->access_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric",
            "invoice_id" => "required|numeric",
            "invoice_payment_method_id" => "required|numeric",
            "invoice_payment_date" => "required",
            "invoice_payment_amount" => "required"
        ));

        $id = $this->input->post('id');
        $invoice_id = $this->input->post('invoice_id');

        $invoice_payment_data = array(
            "invoice_id" => $invoice_id,
            "payment_date" => $this->input->post('invoice_payment_date'),
            "payment_method_id" => $this->input->post('invoice_payment_method_id'),
            "note" => $this->input->post('invoice_payment_note'),
            "amount" => unformat_currency($this->input->post('invoice_payment_amount')),
            "created_at" => get_current_utc_time(),
            "created_by" => $this->login_user->id,
            "cheque_due_date" => $this->input->post('cheque_due_date'),
            "cheque_description" => $this->input->post('cheque_description'),
            "cheque_account" => $this->input->post('cheque_account'),
            "cheque_number" => $this->input->post('cheque_number'),
            "bank" => $this->input->post('bank')?$this->input->post('bank'):0,
        );
        //file operation
        $target_path = get_setting("timeline_file_path");
        $files_data = move_files_from_temp_dir_to_permanent_dir($target_path, "invoice");
        $new_files = unserialize($files_data);

        if($id){
            $event_info = $this->Invoices_model->get_one($id);
            $timeline_file_path = get_setting("timeline_file_path");
            $new_files = update_saved_files($timeline_file_path, $event_info->files, $new_files);
        }
        //file data
        $invoice_payment_data["files"] = serialize($new_files);
        $invoice_payment_id = $this->Proforma_invoice_payments_model->save($invoice_payment_data, $id);
        if ($invoice_payment_id) {

            //As receiving payment for the invoice, we'll remove the 'draft' status from the invoice 
            $this->Proforma_invoices_model->update_invoice_status($invoice_id);

            if (!$id) { //show payment confirmation for new payments only
                log_notification("invoice_payment_confirmation", array("invoice_payment_id" => $invoice_payment_id, "invoice_id" => $invoice_id), "0");
            }
            //get payment data
            $options = array("id" => $invoice_payment_id);
            $item_info = $this->Proforma_invoice_payments_model->get_details($options)->row();
            echo json_encode(array("success" => true, "invoice_id" => $item_info->invoice_id, "data" => $this->_make_payment_row($item_info), "invoice_total_view" => $this->_get_invoice_total_view($item_info->invoice_id), 'id' => $invoice_payment_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    /* delete or undo a payment */

    function delete_payment() {
        $this->access_allowed_members();

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Proforma_invoice_payments_model->delete($id, true)) {
                $options = array("id" => $id);
                $item_info = $this->Proforma_invoice_payments_model->get_details($options)->row();
                echo json_encode(array("success" => true, "invoice_id" => $item_info->invoice_id, "data" => $this->_make_payment_row($item_info), "invoice_total_view" => $this->_get_invoice_total_view($item_info->invoice_id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Proforma_invoice_payments_model->delete($id)) {
                $item_info = $this->Proforma_invoice_payments_model->get_one($id);
                echo json_encode(array("success" => true, "invoice_id" => $item_info->invoice_id, "invoice_total_view" => $this->_get_invoice_total_view($item_info->invoice_id), 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of invoice payments, prepared for datatable  */

    function payment_list_data($invoice_id = 0, $widget = "") {
        $this->access_allowed_members();

        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $payment_method_id = $this->input->post('payment_method_id');
        $options = array(
            "start_date" => $start_date,
            "end_date" => $end_date,
            "invoice_id" => $invoice_id,
            "payment_method_id" => $payment_method_id,
            "currency" => $this->input->post("currency"),
        );

        if($widget)
        {
             $options["status"] = "draft";
        }

        if(!$this->login_user->is_admin){
            $options["departments"] = explode(",",get_array_value($this->login_user->permissions, "branches_options"));
        }

        $list_data = $this->Proforma_invoice_payments_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_payment_row($data);
        }
        echo json_encode(array("data" => $result));
    }


    function search_list($id="-1") {
        $this->access_allowed_members();
       
        $options["id"] = $id;

        $list_data = $this->Proforma_invoice_payments_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_payment_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    /* list of invoice payments, prepared for datatable  */

    function payment_list_data_of_client($client_id = 0) {

        $this->access_allowed_members_or_client_contact($client_id);

        $options = array("client_id" => $client_id);
        if(!$this->login_user->is_admin){
            $options["departments"] = explode(",",get_array_value($this->login_user->permissions, "branches_options"));
        }
        $list_data = $this->Proforma_invoice_payments_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_payment_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    /* list of invoice payments, prepared for datatable  */

    function payment_list_data_of_project($project_id = 0) {
        $options = array("project_id" => $project_id);
        if(!$this->login_user->is_admin){
            $options["departments"] = explode(",",get_array_value($this->login_user->permissions, "branches_options"));
        }
        $list_data = $this->Proforma_invoice_payments_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_payment_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    /* prepare a row of invoice payment list table */

    private function _make_payment_row($data) {
        $invoice_url = "";
        $this->access_allowed_members_or_client_contact($data->client_id);

        if ($this->login_user->user_type == "staff") {
            $invoice_url = anchor(get_uri("proforma_invoices/view/" . $data->invoice_id), get_invoice_id($data->invoice_id));
        } else {
            $invoice_url = anchor(get_uri("proforma_invoices/preview/" . $data->invoice_id), get_invoice_id($data->invoice_id));
        }

        $user_image = get_avatar($data->user_image);
        $user = "<span class='avatar avatar-xs mr10'><img src='$user_image' alt=''></span>" . $data->user_name;

        $client_name = anchor(get_uri("clients/view/" . $data->client_id), $data->company_name);

        //// permission
        if ($this->can_approve() /*&& $data->status != 'refunded'*/ && $data->cheque_transaction_id == 0) {
            $stat = js_anchor(lang($data->status), array('title' => "status", "class" => "btn btn-default", "data-id" => $data->id, "data-value" => $data->status, "data-act" => "update-status"));
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

            if ($data->cheque_transaction_id) {
                if ($payment_method) {
                    $payment_method .= "<br /> ";
                }
                $payment_method .= "<b style='color: green'> Collected </b>";
            }
        }

        $bank = $this->Accounts_model->get_one($data->bank);
        if (!empty($bank->id)) {
            $bank_title = $bank->acc_name;
        } else {
            $bank = $this->Accounts_model->get_one(get_setting("default_bank")?get_setting("default_bank"):52);
            $bank_title = $bank->acc_name;
        }

        $row = array(
            $data->id,
            $invoice_url,
            $client_name,
            $data->payment_date,
            format_to_date($data->payment_date, false),
            $payment_method."</br>".$bank_title,
            $data->note,
            $stat,
            to_currency($data->amount, $data->currency_symbol),
            $user."</br>".$data->created_at,
            
        );

        $rowe = "";
        if ($this->can_edit() && $data->status == "draft") {
            $rowe .= modal_anchor(get_uri("proforma_invoice_payments/payment_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_payment'), "data-post-id" => $data->id, "data-post-invoice_id" => $data->invoice_id,));
        }
        if ($this->can_delete() && $data->status == "draft") {
            $rowe .= js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("proforma_invoice_payments/delete_payment"), "data-action" => "delete"));
        }
        $row[] = $rowe;
        return $row;

    }

    //prepare invoice status label 
    private function _get_approval_status_label($data, $return_html = true) {
        return get_payment_status_label($data, $return_html);
    }

    /* invoice total section */

    private function _get_invoice_total_view($invoice_id = 0) {
        $view_data["invoice_total_summary"] = $this->Proforma_invoices_model->get_invoice_total_summary($invoice_id);
        $view_data["approval_status"] = $this->Proforma_invoices_model->get_one($invoice_id)->approval_status;
        $view_data["invoice_id"] = $invoice_id;
        $invoice_uncollected_cheques = $this->Reports_model->invoice_uncollected_cheques($invoice_id)->amount ? $this->Reports_model->invoice_uncollected_cheques($invoice_id)->amount : 0;
                $view_data["invoice_uncollected_cheques"] = $invoice_uncollected_cheques;
        return $this->load->view('invoices/invoice_total_section', $view_data, true);
    }

    function pay_invoice_via_stripe() {
        validate_submitted_data(array(
            "stripe_token" => "required",
            "invoice_id" => "required"
        ));

        $this->access_only_clients();

        $invoice_id = $this->input->post('invoice_id');
        $method_info = $this->Payment_methods_model->get_oneline_payment_method("stripe");

        //load stripe lib
        require_once(APPPATH . "third_party/Stripe/init.php");
        \Stripe\Stripe::setApiKey($method_info->secret_key);


        if (!$invoice_id) {
            redirect("forbidden");
        }

        $redirect_to = "invoices/preview/$invoice_id";

        try {

            //check payment token
            $card = $this->input->post('stripe_token');

            $invoice_data = (Object) get_invoice_making_data($invoice_id);
            $currency = $invoice_data->invoice_total_summary->currency;


            //check if partial payment allowed or not
            if (get_setting("allow_partial_invoice_payment_from_clients")) {
                $payment_amount = unformat_currency($this->input->post('payment_amount'));
            } else {
                $payment_amount = $invoice_data->invoice_total_summary->balance_due;
            }


            //validate payment amount
            if ($payment_amount < $method_info->minimum_payment_amount * 1) {
                $error_message = lang('minimum_payment_validation_message') . " " . to_currency($method_info->minimum_payment_amount, $currency . " ");
                $this->session->set_flashdata("error_message", $error_message);
                redirect($redirect_to);
            }



            //prepare stripe payment data

            $metadata = array(
                "invoice_id" => $invoice_id,
                "contact_user_id" => $this->login_user->id,
                "client_id" => $invoice_data->client_info->id
            );

            $stripe_data = array(
                "amount" => $payment_amount * 100, //convert to cents
                "currency" => $currency,
                "card" => $card,
                "metadata" => $metadata,
                "description" => get_invoice_id($invoice_id) . ", " . lang('amount') . ": " . to_currency($payment_amount, $currency . " ")
            );

            $charge = \Stripe\Charge::create($stripe_data);

            if ($charge->paid) {

                //payment complete, insert payment record
                $invoice_payment_data = array(
                    "invoice_id" => $invoice_id,
                    "payment_date" => get_my_local_time(),
                    "payment_method_id" => $method_info->id,
                    "note" => $this->input->post('invoice_payment_note'),
                    "amount" => $payment_amount,
                    "transaction_id" => $charge->id,
                    "created_at" => get_current_utc_time(),
                    "created_by" => $this->login_user->id,
                );

                $invoice_payment_id = $this->Proforma_invoice_payments_model->save($invoice_payment_data);
                if ($invoice_payment_id) {

                    //As receiving payment for the invoice, we'll remove the 'draft' status from the invoice 
                    $this->Proforma_invoices_model->update_invoice_status($invoice_id);

                    log_notification("invoice_payment_confirmation", array("invoice_payment_id" => $invoice_payment_id, "invoice_id" => $invoice_id), "0");
                    log_notification("invoice_online_payment_received", array("invoice_payment_id" => $invoice_payment_id, "invoice_id" => $invoice_id));
                    $this->session->set_flashdata("success_message", lang("payment_success_message"));
                    redirect($redirect_to);
                } else {
                    $this->session->set_flashdata("error_message", lang("payment_card_charged_but_system_error_message"));
                    redirect($redirect_to);
                }
            } else {
                $this->session->set_flashdata("error_message", lang("card_payment_failed_error_message"));
                redirect($redirect_to);
            }
        } catch (Stripe_CardError $e) {

            $error_data = $e->getJsonBody();
            $this->session->set_flashdata("error_message", $error_data['error']['message']);
            redirect($redirect_to);
        } catch (Stripe_InvalidRequestError $e) {

            $error_data = $e->getJsonBody();
            $this->session->set_flashdata("error_message", $error_data['error']['message']);
            redirect($redirect_to);
        } catch (Stripe_AuthenticationError $e) {

            $error_data = $e->getJsonBody();
            $this->session->set_flashdata("error_message", $error_data['error']['message']);
            redirect($redirect_to);
        } catch (Stripe_ApiConnectionError $e) {

            $error_data = $e->getJsonBody();
            $this->session->set_flashdata("error_message", $error_data['error']['message']);
            redirect($redirect_to);
        } catch (Stripe_Error $e) {

            $error_data = $e->getJsonBody();
            $this->session->set_flashdata("error_message", $error_data['error']['message']);
            redirect($redirect_to);
        } catch (Exception $e) {

            $error_data = $e->getJsonBody();
            $this->session->set_flashdata("error_message", $error_data['error']['message']);
            redirect($redirect_to);
        }
    }

    //load the expenses yearly chart view
    function yearly_chart() {
        $view_data["currencies_dropdown"] = $this->_get_currencies_dropdown();
        $this->load->view("invoices/yearly_payments_chart", $view_data);
    }

    function yearly_chart_data() {

        $months = array("january", "february", "march", "april", "may", "june", "july", "august", "september", "october", "november", "december");
        $result = array();

        $year = $this->input->post("year");
        if ($year) {
            $currency = $this->input->post("currency");
            $payments = $this->Proforma_invoice_payments_model->get_yearly_payments_chart($year, $currency);
            $values = array();
            foreach ($payments as $value) {
                $values[$value->month - 1] = $value->total; //in array the month january(1) = index(0)
            }

            foreach ($months as $key => $month) {
                $value = get_array_value($values, $key);
                $result[] = array(lang("short_" . $month), $value ? $value : 0);
            }

            echo json_encode(array("data" => $result, "currency_symbol" => $currency));
        }
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

        $status_id = $this->Proforma_invoice_payments_model->save($data, $id);
        if ($status_id) {
            echo json_encode(array("success" => true, 'message' => 'success'));
            $info = $this->Proforma_invoice_payments_model->get_details(array("id" => $status_id))->row();
            if ($stat == "draft") {
                // $this->delete_entries($info);
            } elseif ($stat == "approved") {
                // $this->make_entries($info);
                $this->pushes($info);
            }
        }
 
    }

    function make_entries($info) {
        $date = $info->payment_date;
        $type = "Automatic Payment Received: ". get_invoice_id($info->invoice_id);
        
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

        $client_info = $this->Clients_model->get_one($info->client_id);
        $inv_info = $this->Proforma_invoices_model->get_one($info->invoice_id);

        $account_id = $client_info->advance_account_id; 

        if ($info->payment_method_id == 1) {
            $acc_array[] = array("account_id" => $account_id, "type" => 'cr',"amount" => $info->amount, "narration" => $narration);

            $acc_array[] = array("account_id" => 19, "type" => 'dr',"amount" => $info->amount, "narration" => $narration);
            
        } else if ($info->payment_method_id == 4) {
            $acc_array[] = array("account_id" => $account_id, "type" => 'cr',"amount" => $info->amount, "narration" => $narration);

            if(!empty($info->bank)) {
                $bank = $info->bank;
            } else {
                $bank = get_setting('default_bank')?get_setting('default_bank'):52;
            }

            $acc_array[] = array("account_id" => $bank, "type" => 'dr',"amount" => $info->amount, "narration" => $narration);
            
        } else if ($info->payment_method_id == 5) {
            $acc_array[] = array("account_id" => $account_id, "type" => 'cr',"amount" => $info->amount, "narration" => $narration);

            $acc_array[] = array("account_id" => 21, "type" => 'dr',"amount" => $info->amount, "narration" => $narration);
            
        } else if ($info->payment_method_id == 7) {
             $acc_array[] = array("account_id" => $account_id, "type" => 'cr',"amount" => $info->amount, "narration" => $narration);
            
            $acc_array[] = array("account_id" => 51, "type" => 'dr',"amount" => $info->amount, "narration" => $narration);
        }

        //var_dump($acc_array);

        $transaction_id = make_transaction($date, $acc_array, $type);

        if ($transaction_id) {
            $trans_data = array("advance_transaction_id" => $transaction_id);
            $this->Proforma_invoice_payments_model->save($trans_data, $info->id);
        }
    }


    function delete_entries($info) {   
        //$trans_id = $info->account_transaction_id;
        $adv_trans_id = $info->advance_transaction_id;
        if ($adv_trans_id !== 0) {
            //$transaction_id = delete_transaction($trans_id);
            $advance_transaction_id = delete_transaction($adv_trans_id);
            if ($advance_transaction_id) {
                $trans_data = array("advance_transaction_id" => 0);
                $this->Proforma_invoice_payments_model->save($trans_data, $info->id);
            }
        }
        /*$cheque_trans_id = $info->cheque_transaction_id;
        if ($cheque_trans_id !== 0) {
            $cheque_transaction_id = delete_transaction($cheque_trans_id);
            if ($cheque_transaction_id) {
                $che_trans_data = array("cheque_transaction_id" => 0);
                $this->Proforma_invoice_payments_model->save($che_trans_data, $info->id);
            }
        }*/
    }

    function pushes($info) {   
        $notifationTo = $info->created_by;
        $body = 'The Payment of '.$info->amount." from ".$info->company_name.'  has been approved';
        send_onesignal($body, '#', $notifationTo, '', '');
    }

}

/* End of file payments.php */
/* Location: ./application/controllers/payments.php */