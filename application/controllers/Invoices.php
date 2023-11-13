<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Invoices extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        //$this->init_permission_checker("invoice");
        $this->permission_checker("can_access_invoices");
    }

    private function can_edit()
    {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_edit_invoices") == "1") {
                return true;
            }
        }
    }

    private function can_delete()
    {
        if ($this->login_user->is_super) {
            return true;
        }
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_delete_invoices") == "1") {
                return true;
            }
        }
    }

    private function can_approve()
    {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "invoices") == "all") {
                return true;
            }
        }
    }

    /* load invoice list view */

    function index()
    {
        $this->check_module_availability("module_invoice");

        $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("invoices", $this->login_user->is_admin, $this->login_user->user_type);

        if ($this->login_user->user_type === "staff") {
            $this->access_allowed_members();

            $view_data["currencies_dropdown"] = $this->_get_currencies_dropdown();

            if (!$this->login_user->is_admin) {
                $view_data['can_create_module'] = get_array_value($this->login_user->permissions, 'can_create_invoices');
            } else {
                $view_data['can_create_module'] = 1;
            }
            $view_data['can_add_payment'] = $this->can_add_payment();

            $this->template->rander("invoices/index", $view_data);
        } else {
            if (!$this->login_user->is_admin) {
                $view_data['can_create_module'] = get_array_value($this->login_user->permissions, 'can_create_invoices');
            } else {
                $view_data['can_create_module'] = 1;
            }
            $view_data["client_info"] = $this->Clients_model->get_one($this->login_user->client_id);
            $view_data['client_id'] = $this->login_user->client_id;
            $view_data['page_type'] = "full";
            $this->template->rander("clients/invoices/index", $view_data);
        }
    }

    //load the yearly view of invoice list 
    function yearly()
    {
        $this->load->view("invoices/yearly_invoices");
    }

    //load the recurring view of invoice list 
    function recurring()
    {
        $view_data["currencies_dropdown"] = $this->_get_currencies_dropdown();
        $this->load->view("invoices/recurring_invoices_list", $view_data);
    }

    //load the custom view of invoice list 
    function custom()
    {
        $this->load->view("invoices/custom_invoices_list");
    }

    /* load new invoice modal */

    function modal_form()
    {
        $this->access_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric",
            "client_id" => "numeric",
            "project_id" => "numeric"
        ));

        $client_id = $this->input->post('client_id');
        $project_id = $this->input->post('project_id');
        $model_info = $this->Invoices_model->get_one($this->input->post('id'));


        //check if estimate_id posted. if found estimate_id, so, we'll show the estimate info to copy the estimate 
        $estimate_id = $this->input->post('estimate_id');
        $view_data['estimate_id'] = $estimate_id;
        if ($estimate_id) {
            $estimate_info = $this->Estimates_model->get_one($estimate_id);
            $now = get_my_local_time("Y-m-d");
            $model_info->bill_date = $now;
            $model_info->due_date = $now;
            $model_info->client_id = $estimate_info->client_id;
            $model_info->tax_id = $estimate_info->tax_id;
            $model_info->tax_id2 = $estimate_info->tax_id2;
        }

        //here has a project id. now set the client from the project
        if ($project_id) {
            $client_id = $this->Projects_model->get_one($project_id)->client_id;
            $model_info->client_id = $client_id;
        }


        $project_client_id = $client_id;
        if ($model_info->client_id) {
            $project_client_id = $model_info->client_id;
        }

        $view_data['model_info'] = $model_info;

        //make the drodown lists
        $view_data['taxes_dropdown'] = array("" => "-") + $this->Taxes_model->get_dropdown_list(array("title"));
        $view_data['clients_dropdown'] = array("" => "-") + $this->Clients_model->get_dropdown_list(array("company_name", "phone"), "id", array("is_lead" => 0));
        $view_data['sale_item_dropdown'] = array("" => "-") + $this->Sale_items_model->get_dropdown_list(array("title"), "id", array("deleted" => 0));
        $projects = $this->Projects_model->get_dropdown_list(array("title"), "id", array("client_id" => $project_client_id));
        $suggestion = array(array("id" => "", "text" => "-"));
        foreach ($projects as $key => $value) {
            $suggestion[] = array("id" => $key, "text" => $value);
        }
        $view_data['projects_suggestion'] = $suggestion;

        $view_data['client_id'] = $client_id;
        $view_data['project_id'] = $project_id;


        //prepare label suggestions
        $labels = explode(",", $this->Invoices_model->get_label_suggestions());
        $label_suggestions = array();
        foreach ($labels as $label) {
            if ($label && !in_array($label, $label_suggestions)) {
                $label_suggestions[] = $label;
            }
        }
        if (!count($label_suggestions)) {
            $label_suggestions = array("0" => "");
        }
        $view_data['label_suggestions'] = $label_suggestions;

        //clone invoice
        $is_clone = $this->input->post('is_clone');
        $view_data['is_clone'] = $is_clone;


        $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("invoices", $model_info->id, $this->login_user->is_admin, $this->login_user->user_type)->result();


        $this->load->view('invoices/modal_form', $view_data);
    }

    /* prepare project dropdown based on this suggestion */

    function get_project_suggestion($client_id = 0)
    {
        $this->access_allowed_members();

        $projects = $this->Projects_model->get_dropdown_list(array("title"), "id", array("client_id" => $client_id));
        $suggestion = array(array("id" => "", "text" => "-"));
        foreach ($projects as $key => $value) {
            $suggestion[] = array("id" => $key, "text" => $value);
        }
        echo json_encode($suggestion);
    }

    /* add or edit an invoice */

    function save()
    {

        $this->access_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric",
            "invoice_client_id" => "numeric",
            "invoice_bill_date" => "required",
            "invoice_due_date" => "required",
            "sale_item" => "required"
        ));

        $client_id = $this->input->post('invoice_client_id');

        $company_name = $this->input->post('company_name');
        $phone = $this->input->post('phone');
        if ($company_name && $phone && !$client_id) {
            $client_id = $this->save_client();
        }

        $id = $this->input->post('id');

        $recurring = $this->input->post('recurring') ? 1 : 0;
        $bill_date = $this->input->post('invoice_bill_date');
        $repeat_every = $this->input->post('repeat_every');
        $repeat_type = $this->input->post('repeat_type');
        $no_of_cycles = $this->input->post('no_of_cycles');


        $invoice_data = array(
            "client_id" => $client_id,
            "project_id" => $this->input->post('invoice_project_id') ? $this->input->post('invoice_project_id') : 0,
            "sale_item" => $this->input->post('sale_item') ? $this->input->post('sale_item') : 0,
            "bill_date" => $bill_date,
            "due_date" => $this->input->post('invoice_due_date'),
            "tax_id" => $this->input->post('tax_id') ? $this->input->post('tax_id') : 0,
            "tax_id2" => $this->input->post('tax_id2') ? $this->input->post('tax_id2') : 0,
            "recurring" => $recurring,
            "repeat_every" => $repeat_every ? $repeat_every : 0,
            "repeat_type" => $repeat_type ? $repeat_type : NULL,
            "no_of_cycles" => $no_of_cycles ? $no_of_cycles : 0,
            "note" => $this->input->post('invoice_note'),
            "labels" => $this->input->post('labels'),
        );


        $is_clone = $this->input->post('is_clone');

        $main_invoice_id = "";
        if ($is_clone && $id) {
            $main_invoice_id = $id; //store main invoice id to get items later
            $id = ""; //one cloning invoice, save as new
            // save discount when cloning
            $invoice_data["discount_amount"] = $this->input->post('discount_amount') ? $this->input->post('discount_amount') : 0;
            $invoice_data["discount_amount_type"] = $this->input->post('discount_amount_type') ? $this->input->post('discount_amount_type') : "percentage";
            $invoice_data["discount_type"] = $this->input->post('discount_type') ? $this->input->post('discount_type') : "before_tax";
        }


        if ($recurring) {
            //set next recurring date for recurring invoices

            if ($id) {
                //update
                if ($this->input->post('next_recurring_date')) { //submitted any recurring date? set it.
                    $invoice_data['next_recurring_date'] = $this->input->post('next_recurring_date');
                } else {
                    //re-calculate the next recurring date, if any recurring fields has changed.
                    $invoice_info = $this->Invoices_model->get_one($id);
                    if ($invoice_info->recurring != $invoice_data['recurring'] || $invoice_info->repeat_every != $invoice_data['repeat_every'] || $invoice_info->repeat_type != $invoice_data['repeat_type'] || $invoice_info->bill_date != $invoice_data['bill_date']) {
                        $invoice_data['next_recurring_date'] = add_period_to_date($bill_date, $repeat_every, $repeat_type);
                    }
                }
            } else {
                //insert new
                $invoice_data['next_recurring_date'] = add_period_to_date($bill_date, $repeat_every, $repeat_type);
            }


            //recurring date must have to set a future date
            if (get_array_value($invoice_data, "next_recurring_date") && get_today_date() >= $invoice_data['next_recurring_date']) {
                echo json_encode(array("success" => false, 'message' => lang('past_recurring_date_error_message_title'), 'next_recurring_date_error' => lang('past_recurring_date_error_message'), "next_recurring_date_value" => $invoice_data['next_recurring_date']));
                return false;
            }
        }

        //file operation
        $target_path = get_setting("timeline_file_path");
        $files_data = move_files_from_temp_dir_to_permanent_dir($target_path, "invoice");
        $new_files = unserialize($files_data);

        if ($id) {
            $event_info = $this->Invoices_model->get_one($id);
            $timeline_file_path = get_setting("timeline_file_path");
            $new_files = update_saved_files($timeline_file_path, $event_info->files, $new_files);
        }
        //file data
        $invoice_data["files"] = serialize($new_files);

        // Tadqeeq invoice prefix
        if ($this->db->dbprefix == "Tadqeeq") {
            $invoice = $this->Invoices_model->get_last_record();
            $last_count = $invoice[0]['count'];

            $d = $invoice_data["bill_date"];
            if (date("m") == date("m", strtotime($d))) {
                $invoice_data["count"] = $last_count + 1;
            } else {
                $invoice_data["count"] = 1;
            }
        }


        $invoice_id = $this->Invoices_model->save($invoice_data, $id);
        if ($invoice_id) {

            if ($is_clone && $main_invoice_id) {
                //add invoice items

                save_custom_fields("invoices", $invoice_id, 1, "staff"); //we have to keep this regarding as an admin user because non-admin user also can acquire the access to clone a invoice

                $invoice_items = $this->Invoice_items_model->get_all_where(array("invoice_id" => $main_invoice_id, "deleted" => 0))->result();

                foreach ($invoice_items as $invoice_item) {
                    //prepare new invoice item data
                    $invoice_item_data = (array) $invoice_item;
                    unset($invoice_item_data["id"]);
                    $invoice_item_data['invoice_id'] = $invoice_id;

                    $invoice_item = $this->Invoice_items_model->save($invoice_item_data);
                }
            } else {
                save_custom_fields("invoices", $invoice_id, $this->login_user->is_admin, $this->login_user->user_type);
            }

            //submitted copy_items_from_estimate? copy all items from estimate
            $copy_items_from_estimate = $this->input->post("copy_items_from_estimate");
            if ($copy_items_from_estimate) {
                ///
                $invoice_status["approval_status"] = "approved";
                $this->Invoices_model->save($invoice_status, $invoice_id);
                ////
                $estimate_items = $this->Estimate_items_model->get_details(array("estimate_id" => $copy_items_from_estimate))->result();

                foreach ($estimate_items as $data) {
                    $invoice_item_data = array(
                        "invoice_id" => $invoice_id,
                        "item_id" => $data->item_id,
                        "title" => $data->title ? $data->title : "",
                        "description" => $data->description ? $data->description : "",
                        "quantity" => $data->quantity ? $data->quantity : 0,
                        "unit_type" => $data->unit_type ? $data->unit_type : "",
                        "rate" => $data->rate ? $data->rate : 0,
                        "total" => $data->total ? $data->total : 0,
                        "discount_amount" => $data->discount_amount ? $data->discount_amount : 0,
                        "discount_amount_type" => $data->discount_amount_type ? $data->discount_amount_type : "",
                        "tax_id" => $data->tax_id ? $data->tax_id : 0,
                    );
                    $this->Invoice_items_model->save($invoice_item_data);
                }
            }
            if (isset($invoice_status) && $invoice_status["approval_status"] == "approved") {
                $info = $this->Invoices_model->get_details(array("id" => $invoice_id))->row();

                $this->make_inv_entries($info);
            }

            echo json_encode(array("success" => true, "data" => $this->_row_data($invoice_id), 'id' => $invoice_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function save_client()
    {

        validate_submitted_data(array(
            "company_name" => "required",
            "phone" => "required",
        ));
        $company_name = $this->input->post('company_name');
        $data = array(
            "company_name" => $company_name,
            "phone" => $this->input->post('phone'),
            "address" => $this->input->post('address'),
        );
        $data["created_date"] = get_current_utc_time();
        $data = clean_data($data);
        //check duplicate company name, if found then show an error message
        if (get_setting("disallow_duplicate_client_company_name") == "1" && $this->Clients_model->is_duplicate_company_name($data["company_name"], 0)) {
            echo json_encode(array("success" => false, 'message' => lang("account_already_exists_for_with_same_name")));
            exit();
        }
        //check duplicate company name, if found then show an error message
        if ($this->Clients_model->is_duplicate_phone($data["phone"], 0)) {
            echo json_encode(array("success" => false, 'message' => lang("account_already_exists_with_same_phone")));
            exit();
        }
        $save_id = $this->Clients_model->save($data);

        if ($save_id) {
            return $save_id;
        }
    }

    /* delete or undo an invoice */

    function delete()
    {
        $this->access_allowed_members();

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Invoices_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Invoices_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of invoices, prepared for datatable  */

    function list_data()
    {
        $this->access_allowed_members();

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("invoices", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array(
            "status" => $this->input->post("status"),
            "start_date" => $this->input->post("start_date"),
            "end_date" => $this->input->post("end_date"),
            "currency" => $this->input->post("currency"),
            "custom_fields" => $custom_fields
        );
        $this->permission_checker("invoice_manage_permission");
        if (!$this->login_user->is_admin || !$this->access_type === "all") {
            $options["allowed_members"] = $this->allowed_members;
        }

        $list_data = $this->Invoices_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }

        echo json_encode(array("data" => $result));
    }

    /* list of invoice of a specific client, prepared for datatable  */

    function invoice_list_data_of_client($client_id)
    {
        $this->access_allowed_members_or_client_contact($client_id);

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("invoices", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array(
            "client_id" => $client_id,
            "status" => $this->input->post("status"),
            "custom_fields" => $custom_fields
        );

        //don't show draft invoices to client
        if ($this->login_user->user_type == "client") {
            $options["exclude_draft"] = true;
        }


        $list_data = $this->Invoices_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }
        echo json_encode(array("data" => $result));
    }

    /* list of invoice of a specific project, prepared for datatable  */

    function invoice_list_data_of_project($project_id)
    {
        $this->access_allowed_members();

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("invoices", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array(
            "project_id" => $project_id,
            "status" => $this->input->post("status"),
            "custom_fields" => $custom_fields
        );
        $list_data = $this->Invoices_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }
        echo json_encode(array("data" => $result));
    }

    /* show sub invoices tab  */

    function sub_invoices($recurring_invoice_id)
    {
        $this->access_allowed_members();
        $view_data["recurring_invoice_id"] = $recurring_invoice_id;
        $this->load->view("invoices/sub_invoices", $view_data);
    }

    /* list of sub invoices of a recurring invoice, prepared for datatable  */

    function sub_invoices_list_data($recurring_invoice_id)
    {
        $this->access_allowed_members();

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("invoices", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array(
            "status" => $this->input->post("status"),
            "start_date" => $this->input->post("start_date"),
            "end_date" => $this->input->post("end_date"),
            "custom_fields" => $custom_fields,
            "recurring_invoice_id" => $recurring_invoice_id
        );

        $list_data = $this->Invoices_model->get_details($options)->result();

        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }


        echo json_encode(array("data" => $result));
    }

    /* return a row of invoice list table */

    private function _row_data($id)
    {
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("invoices", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array("id" => $id, "custom_fields" => $custom_fields);
        $data = $this->Invoices_model->get_details($options)->row();
        return $this->_make_row($data, $custom_fields);
    }

    /* prepare a row of invoice list table */

    private function _make_row($data, $custom_fields)
    {
        set_row_data_currency_rate($data->currency_rate_at_creation); //SET CURRENCY RATE

        $invoice_url = "";
        if ($this->login_user->user_type == "staff") {
            $invoice_url = anchor(get_uri("invoices/view/" . $data->id), get_invoice_id($data->id));
        } else {
            $invoice_url = anchor(get_uri("invoices/preview/" . $data->id), get_invoice_id($data->id));
        }

        $invoice_labels = "";
        if ($data->labels) {
            $labels = explode(",", $data->labels);
            foreach ($labels as $label) {
                $invoice_labels .= "<span class='mt0 label label-info large ml10 clickable'  title='$label'>" . $label . "</span>";
            }
        }

        $files = unserialize($data->files);
        if (count($files)) {
            $file_name = get_array_value($files[0], "file_name");
            $img_path = base_url() . '/files/timeline_files/' . $file_name;
            $file_url = "<a href=" . $img_path . " target='_blank'>Show</a>";
        } else {
            $file_url = '-';
        }

        $row_data = array(
            $data->id, $invoice_url,
            anchor(get_uri("clients/view/" . $data->client_id), $data->company_name . "</br>" . $data->phone),
            $data->project_title ? anchor(get_uri("projects/view/" . $data->project_id), $data->project_title) : "-",
            $data->bill_date,
            format_to_date($data->bill_date, false),
            $data->due_date,
            format_to_date($data->due_date, false),
            // to_currency($data->invoice_value + $data->tax_after_discount, $data->currency_symbol),
            to_currency($data->invoice_value + $data->tax_after_discount, $data->currency_symbol),
            to_currency($data->payment_received, $data->currency_symbol),
            to_currency($data->tax_after_discount, $data->currency_symbol),
            $file_url,
            $this->_get_delivery_status_label($data) . $this->_get_invoice_status_label($data) . $this->_get_approval_status_label($data) . $invoice_labels
        );

        foreach ($custom_fields as $field) {
            $cf_id = "cfv_" . $field->id;
            $row_data[] = $this->load->view("custom_fields/output_" . $field->field_type, array("value" => $data->$cf_id), true);
        }

        $row_data[] = $this->_make_options_dropdown($data->id);

        
        unset_row_data_currency_rate(); //UNSET CURRENCY RATE
        return $row_data;
    }

    function test($invoice_id = 8)
    {
        $do = $this->Delivery_notes_model->get_one_where(array("invoice_id" => $invoice_id, "deleted" => 0));
        $delivery_total_items = $this->Delivery_note_items_model->get_sum_items($do->id)->row();
        $invoice_total_items = $this->Invoice_items_model->get_sum_items($invoice_id)->row();

        var_dump($do->id . "  kjklk  " . $delivery_total_items->sum . "  kjklk  " . $invoice_total_items->sum);
    }

    private function can_add_payment()
    {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_create_invoice_payments") == "1") {
                return true;
            }
        }
    }

    private function can_add_do()
    {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_create_delivery_notes") == "1") {
                return true;
            }
        }
    }


    private function get_personal_language()
    {
        return get_setting('user_' . $this->login_user->id . '_personal_language');
    }
    private function is_arabic_language(): bool
    {
        return $this->get_personal_language() == 'arabic';
    }
    //prepare options dropdown for invoices list
    private function _make_options_dropdown($invoice_id = 0)
    {

        $invoice_status = $this->Invoices_model->get_one($invoice_id)->approval_status;
        $edit = "";
        $delete = "";
        $add_payment = "";
        if ($this->can_edit() && $invoice_status == "not_approved") {
            $edit = '<li role="presentation">' . modal_anchor(get_uri("invoices/modal_form"), "<i class='fa fa-pencil'></i> " . lang('edit'), array("title" => lang('edit_invoice'), "data-post-id" => $invoice_id)) . '</li>';
        }
        // var_dump($this->login_user);die();
        if ($this->login_user->is_super) {
            // if($this->can_delete()) {
            $delete = '<li role="presentation">' . js_anchor("<i class='fa fa-times fa-fw'></i>" . lang('delete'), array('title' => lang('delete_invoice'), "class" => "delete", "data-id" => $invoice_id, "data-action-url" => get_uri("invoices/delete"), "data-action" => "delete")) . '</li>';
            //  }
        } else {
            if ($this->can_delete() && $invoice_status == "not_approved") {
                $delete = '<li role="presentation">' . js_anchor("<i class='fa fa-times fa-fw'></i>" . lang('delete'), array('title' => lang('delete_invoice'), "class" => "delete", "data-id" => $invoice_id, "data-action-url" => get_uri("invoices/delete"), "data-action" => "delete")) . '</li>';
            }
        }

        if ($this->can_add_payment()) {
            $add_payment = '<li role="presentation">' . modal_anchor(get_uri("invoice_payments/payment_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_payment'), array("title" => lang('add_payment'), "data-post-invoice_id" => $invoice_id)) . '</li>';
        }

        $pull_right = $this->is_arabic_language() ? '' : 'pull-right';
        return '
                <span class="dropdown inline-block">
                    <button class="btn btn-default dropdown-toggle  mt0 mb0" type="button" data-toggle="dropdown" aria-expanded="true">
                        <i class="fa fa-cogs"></i>&nbsp;
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu ' . $pull_right . '" role="menu">' . $edit . $delete . $add_payment . '</ul>
                </span>';
    }

    //prepare invoice status label 
    private function _get_invoice_status_label($data, $return_html = true)
    {
        return get_invoice_status_label($data, $return_html);
    }

    //prepare invoice status label 
    private function _get_approval_status_label($data, $return_html = true)
    {
        return get_approval_status_label($data, $return_html);
    }

    //prepare  status label 
    private function _get_delivery_status_label($data, $return_html = true)
    {
        return get_delivery_status_label($data, $return_html);
    }

    // list of recurring invoices, prepared for datatable
    function recurring_list_data()
    {
        $this->access_allowed_members();


        $options = array(
            "recurring" => 1,
            "next_recurring_start_date" => $this->input->post("next_recurring_start_date"),
            "next_recurring_end_date" => $this->input->post("next_recurring_end_date"),
            "currency" => $this->input->post("currency")
        );

        $list_data = $this->Invoices_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_recurring_row($data);
        }

        echo json_encode(array("data" => $result));
    }

    /* prepare a row of recurring invoice list table */

    private function _make_recurring_row($data)
    {

        $invoice_url = anchor(get_uri("invoices/view/" . $data->id), get_invoice_id($data->id));

        $cycles = $data->no_of_cycles_completed . "/" . $data->no_of_cycles;
        if (!$data->no_of_cycles) { //if not no of cycles, so it's infinity
            $cycles = $data->no_of_cycles_completed . "/&#8734;";
        }

        $status = "active";
        $invoice_status_class = "label-success";
        $cycle_class = "";

        //don't show next recurring if recurring is completed
        $next_recurring = format_to_date($data->next_recurring_date, false);

        //show red color if any recurring date is past
        if ($data->next_recurring_date < get_today_date()) {
            $next_recurring = "<span class='text-danger'>" . $next_recurring . "</span>";
        }


        $next_recurring_date = $data->next_recurring_date;
        if ($data->no_of_cycles_completed > 0 && $data->no_of_cycles_completed == $data->no_of_cycles) {
            $next_recurring = "-";
            $next_recurring_date = 0;
            $status = "stopped";
            $invoice_status_class = "label-danger";
            $cycle_class = "text-danger";
        }

        return array(
            $invoice_url,
            anchor(get_uri("clients/view/" . $data->client_id), $data->company_name),
            $data->project_title ? anchor(get_uri("projects/view/" . $data->project_id), $data->project_title) : "-",
            $next_recurring_date,
            $next_recurring,
            $data->repeat_every . " " . lang("interval_" . $data->repeat_type),
            "<span class='$cycle_class'>" . $cycles . "</span>",
            "<span class='label $invoice_status_class large'>" . lang($status) . "</span>",
            to_currency($data->invoice_value, $data->currency_symbol),
            $this->_make_options_dropdown($data->id)
        );
    }

    /* load invoice details view */

    function view($invoice_id = 0)
    {
        $this->access_allowed_members();

        if ($invoice_id) {
            $view_data = get_invoice_making_data($invoice_id);
    
            if ($view_data) {
                set_row_data_currency_rate($view_data['invoice_info']->currency_rate_at_creation); //SET CURRENCY RATE

                // var_dump($view_data["invoice_info"]);
                $view_data['invoice_status'] = $this->_get_invoice_status_label($view_data["invoice_info"], false);
                $view_data['approval_status'] = $this->_get_approval_status_label($view_data["invoice_info"], false);
                $view_data['approval'] = $this->_get_approval_status_label($view_data["invoice_info"]);

                $view_data["delivery"] = $this->_get_delivery_status_label($view_data["invoice_info"]);
                $view_data["delivery_status"] = $this->_get_delivery_status_label($view_data["invoice_info"], false);

                $view_data["delivery_info"] = $this->Delivery_notes_model->get_one_where(array("invoice_id" => $invoice_id, "deleted" => 0));
                $view_data["can_approve"] = $this->can_approve();
                $view_data["can_edit"] = $this->can_edit();
                $view_data["can_add_payment"] = $this->can_add_payment();
                $view_data["can_add_do"] = $this->can_add_do();
                // var_dump( $view_data['invoice_status']);die();
                $this->template->rander("invoices/view", $view_data);
               
            } else {
                show_404();
            }
        }
    }

    /* invoice total section */

    private function _get_invoice_total_view($invoice_id = 0)
    {
        $view_data["invoice_total_summary"] = $this->Invoices_model->get_invoice_total_summary($invoice_id);
        $view_data["invoice_id"] = $invoice_id;
        $view_data["approval_status"] = $this->Invoices_model->get_one($invoice_id)->approval_status;
        return $this->load->view('invoices/invoice_total_section', $view_data, true);
    }

    /* load item modal */

    function item_modal_form()
    {
        $this->access_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $invoice_id = $this->input->post('invoice_id');

        $view_data['model_info'] = $this->Invoice_items_model->get_one($this->input->post('id'));
        if (!$invoice_id) {
            $invoice_id = $view_data['model_info']->invoice_id;
        }
        $view_data['categories_dropdown'] = array("" => "-") + $this->Item_categories_model->get_dropdown_list(array("title"));
        $view_data['types_dropdown'] = array("" => "-", 'service' => lang('service'), 'product' => lang('product'));
        $view_data['taxes_dropdown'] = array("" => "-") + $this->Taxes_model->get_dropdown_list(array("title"));
        $view_data['invoice_id'] = $invoice_id;
        $this->load->view('invoices/item_modal_form', $view_data);
    }
    function new_item_modal_form()
    {
        //$this->validate_access_to_items();

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['categories_dropdown'] = array("" => "-") + $this->Item_categories_model->get_dropdown_list(array("title"));
        $view_data['types_dropdown'] = array("" => "-", 'service' => lang('service'), 'product' => lang('product'));

        $view_data['model_info'] = $this->Items_model->get_one($this->input->post('id'));

        $this->load->view('invoices/new_item_modal_form', $view_data);
    }
    /* add or edit an invoice item */

    function save_item()
    {
        $this->access_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric",
            "invoice_id" => "required|numeric"
        ));

        $invoice_id = $this->input->post('invoice_id');

        $id = $this->input->post('id');
        $rate = unformat_currency($this->input->post('invoice_item_rate'));
        $quantity = unformat_currency($this->input->post('invoice_item_quantity'));
        if ($this->db->dbprefix == 'Tadqeeq') {
            $invoice_item_data = array(
                "invoice_id" => $invoice_id,
                "title" => $this->input->post('invoice_item_title'),
                "item_id" => $this->input->post('invoice_item_id'),
                "description" => $this->input->post('invoice_item_description'),
                "quantity" => $quantity,
                "unit_type" => $this->input->post('invoice_unit_type'),
                "certificate_no" => $this->input->post('certificate_no'),
                "rate" => unformat_currency($this->input->post('invoice_item_rate')),
                "discount_amount" => unformat_currency($this->input->post('discount_amount')),
                "discount_amount_type" => $this->input->post('discount_amount_type'),
                "tax_id" => $this->input->post('tax_id') ? $this->input->post('tax_id') : 0,
                "tax_id2" => $this->input->post('tax_id2') ? $this->input->post('tax_id2') : 0,
                "total" => $rate * $quantity,
            );
        } else {
            $invoice_item_data = array(
                "invoice_id" => $invoice_id,
                "title" => $this->input->post('invoice_item_title'),
                "item_id" => $this->input->post('invoice_item_id'),
                "description" => $this->input->post('invoice_item_description'),
                "quantity" => $quantity,
                "discount_amount" => unformat_currency($this->input->post('discount_amount')),
                "discount_amount_type" => $this->input->post('discount_amount_type'),
                "unit_type" => $this->input->post('invoice_unit_type'),
                "rate" => unformat_currency($this->input->post('invoice_item_rate')),
                "cost" => unformat_currency($this->input->post('cost')),
                "tax_id" => $this->input->post('tax_id') ? $this->input->post('tax_id') : 0,
                "tax_id2" => $this->input->post('tax_id2') ? $this->input->post('tax_id2') : 0,
                "total" => $rate * $quantity,
            );
        }

        $invoice_item_id = $this->Invoice_items_model->save($invoice_item_data, $id);
        if ($invoice_item_id) {

            //check if the add_new_item flag is on, if so, add the item to libary. 
            $add_new_item_to_library = $this->input->post('add_new_item_to_library');
            if ($add_new_item_to_library) {
                $library_item_data = array(
                    "title" => $this->input->post('invoice_item_title'),
                    "description" => $this->input->post('invoice_item_description'),
                    "unit_type" => $this->input->post('invoice_unit_type'),
                    "rate" => unformat_currency($this->input->post('invoice_item_rate')),
                    "category_id" => $this->input->post('category_id'),
                    "item_type" => $this->input->post('item_type')
                );
                $this->Items_model->save($library_item_data);
            }

            $options = array("id" => $invoice_item_id);
            $item_info = $this->Invoice_items_model->get_details($options)->row();
            echo json_encode(array("success" => true, "invoice_id" => $item_info->invoice_id, "data" => $this->_make_item_row($item_info), "invoice_total_view" => $this->_get_invoice_total_view($item_info->invoice_id), 'id' => $invoice_item_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    /* load multiple items modal */

    function items_modal_form()
    {
        $this->access_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $invoice_id = $this->input->post('invoice_id');

        $view_data['invoice_id'] = $invoice_id;


        $items = $this->Items_model->get_all_where(array("deleted" => 0))->result();
        $items_dropdown = array();

        foreach ($items as $item) {
            $items_dropdown[] = array("id" => $item->id, "text" => $item->title);
        }

        $view_data['items_dropdown'] = json_encode($items_dropdown);

        $this->load->view('invoices/items_modal_form', $view_data);
    }

    /* add or edit multiple invoice item */

    function save_items()
    {
        $this->access_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric",
            "invoice_id" => "required|numeric"
        ));

        $invoice_id = $this->input->post('invoice_id');

        $items = explode(',', $this->input->post('items'));

        foreach ($items as $item) {
            $item_data = $this->Items_model->get_one($item);
            if ($this->db->dbprefix == 'Tadqeeq') {
                $invoice_item_data = array(
                    "invoice_id" => $invoice_id,
                    "item_id" => $item,
                    "title" => $item_data->title,
                    "description" => $item_data->description,
                    "quantity" => '1',
                    "unit_type" => $item_data->unit_type,
                    "certificate_no" => $item_data->certificate_no,
                    "rate" => unformat_currency($item_data->rate),
                    "total" => $item_data->rate * 1,
                );
            } else {
                $invoice_item_data = array(
                    "invoice_id" => $invoice_id,
                    "item_id" => $item,
                    "title" => $item_data->title,
                    "description" => $item_data->description,
                    "quantity" => '1',
                    "unit_type" => $item_data->unit_type,
                    "rate" => unformat_currency($item_data->rate),
                    "total" => $item_data->rate * 1,
                );
            }


            $invoice_item_id = $this->Invoice_items_model->save($invoice_item_data);
        }

        if ($invoice_item_id) {
            echo json_encode(array("success" => true, "data" => '', 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    /* delete or undo an invoice item */

    function delete_item()
    {
        $this->access_allowed_members();

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Invoice_items_model->delete($id, true)) {
                $options = array("id" => $id);
                $item_info = $this->Invoice_items_model->get_details($options)->row();
                echo json_encode(array("success" => true, "invoice_id" => $item_info->invoice_id, "data" => $this->_make_item_row($item_info), "invoice_total_view" => $this->_get_invoice_total_view($item_info->invoice_id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Invoice_items_model->delete($id)) {
                /*$do_item_id = $this->Delivery_note_items_model->get_details(array("invoice_item_id" => $id))->row()->id;
                $this->Delivery_note_items_model->delete($do_item_id);*/
                $item_info = $this->Invoice_items_model->get_one($id);
                echo json_encode(array("success" => true, "invoice_id" => $item_info->invoice_id, "invoice_total_view" => $this->_get_invoice_total_view($item_info->invoice_id), 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of invoice items, prepared for datatable  */

    function item_list_data($invoice_id = 0)
    {
        $this->access_allowed_members();

        $list_data = $this->Invoice_items_model->get_details(array("invoice_id" => $invoice_id))->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_item_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    /* prepare a row of invoice item list table */

    private function _make_item_row($data)
    {
        $item = "<div class='item-row strong mb5' data-id='$data->id'><i class='fa fa-bars pull-left move-icon'></i> $data->title</div>";
        if ($data->description) {
            $item .= "<span style='margin-left:25px'>" . nl2br($data->description) . "</span>";
        }
        $type = $data->unit_type ? $data->unit_type : "";

        $do_item = array();
        $do_quantity =  0;
        $do_type = "";
        if ($this->Invoices_model->get_details(array("id" => $data->invoice_id))->row()->delivery_status == "delivered") {
            $do_item = $this->Delivery_note_items_model->get_details(array("invoice_item_id" => $data->id))->row();
            $do_qty = $this->Delivery_note_items_model->get_delivered_items($data->id);
            $do_type = isset($do_item->unit_type) ? ($do_item->unit_type ? $do_item->unit_type : "") : "";
            $do_quantity =  isset($do_qty) ? $do_qty : 0;
        }

        $data->tax_percentage = !empty($data->tax_percentage) ? $data->tax_percentage : 0;
        $data->tax_percentage2 = !empty($data->tax_percentage2) ? $data->tax_percentage2 : 0;
        if ($data->discount_amount && $data->discount_amount_type == 'percentage') {
            $discount = $data->total * $data->discount_amount / 100;
        } else {
            $discount = $data->discount_amount ? $data->discount_amount : 0;
        }
        $sub = $data->total - $discount;
        $tax = $sub * $data->tax_percentage * 0.01 + $data->total * $data->tax_percentage2 * 0.01;
        // if($this->db->dbprefix=='Tadqeeq'){
        //     $sub = $data->total-$discount;
        //     $tax=$sub*$data->tax_percentage*0.01 + $data->total*$data->tax_percentage2*0.01;
        // }else{
        //     $tax = $data->total*$data->tax_percentage*0.01 + $data->total*$data->tax_percentage2*0.01;
        // }


        $certificate_no = $this->db->dbprefix == 'Tadqeeq' ? $data->certificate_no : '';


        return array(
            $data->sort,
            $item,
            to_decimal_format($data->quantity) . " " . $type,
            to_decimal_format($do_quantity) . " " . $do_type,
            to_currency($data->rate, $data->currency_symbol),
            to_currency($discount, $data->currency_symbol),
            to_currency($tax, $data->currency_symbol),
            to_currency($data->total + $tax - $discount, $data->currency_symbol),
            $certificate_no,
            modal_anchor(get_uri("invoices/item_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_invoice'), "data-post-id" => $data->id))
                . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("invoices/delete_item"), "data-action" => "delete"))
                . js_anchor("<i class='fa fa-plus fa-fw'></i>", array('title' => lang('add'), "class" => "edit", "data-id" => $data->id, "data-qty" => $data->quantity, "data-action-url" => get_uri("invoices/change_item_qty/plus"), "data-action" => "qty"))
                . js_anchor("<i class='fa fa-minus fa-fw'></i>", array('title' => lang('substract'), "class" => "delete", "data-id" => $data->id, "data-qty" => $data->quantity, "data-action-url" => get_uri("invoices/change_item_qty/minus"), "data-action" => "qty"))
        );
    }

    /* add and minus qty */
    function change_item_qty($operation = 'plus')
    {
        $this->access_allowed_members();
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        $id = $this->input->post('id');
        $options = array("id" => $id);
        $item_info = $this->Invoice_items_model->get_details($options)->row();

        $qty = $item_info->quantity;
        if ($operation == 'plus') {
            $qty += 1;
        } else {
            if ($qty > 1) {
                $qty -= 1;
            }
        }

        $total = $qty * $item_info->rate;

        $data = array("quantity" => $qty, "total" => $total);

        $type = $item_info->unit_type ? $item_info->unit_type : "";
        $quantity = to_decimal_format($qty) . " " . $type;
        $sym = $item_info->currency_symbol;

        if ($this->Invoice_items_model->save($data, $id)) {
            $item_info = $this->Invoice_items_model->get_one($id);
            echo json_encode(array("success" => true, "quantity" => $quantity, "total" => to_currency($item_info->total, $sym), "invoice_total_view" => $this->_get_invoice_total_view($item_info->invoice_id), 'message' => lang('record_saved')));
        }
    }

    //update the sort value for the item
    function update_item_sort_values($id = 0)
    {

        $sort_values = $this->input->post("sort_values");
        if ($sort_values) {

            //extract the values from the comma separated string
            $sort_array = explode(",", $sort_values);


            //update the value in db
            foreach ($sort_array as $value) {
                $sort_item = explode("-", $value); //extract id and sort value

                $id = get_array_value($sort_item, 0);
                $sort = get_array_value($sort_item, 1);

                $data = array("sort" => $sort);
                $this->Invoice_items_model->save($data, $id);
            }
        }
    }

    /* prepare suggestion of invoice item */

    function get_invoice_item_suggestion()
    {
        $key = $_REQUEST["q"];
        $suggestion = array();

        $items = $this->Invoice_items_model->get_item_suggestion($key);

        foreach ($items as $item) {
            $suggestion[] = array("id" => $item->title, "text" => $item->title);
        }

        //$suggestion[] = array("id" => "+", "text" => "+ " . lang("create_new_item"));

        echo json_encode($suggestion);
    }

    function get_invoice_item_info_suggestion()
    {
        $item = $this->Invoice_items_model->get_item_info_suggestion($this->input->post("item_name"));
        if ($item) {
            echo json_encode(array("success" => true, "item_info" => $item));
        } else {
            echo json_encode(array("success" => false));
        }
    }

    //view html is accessable to client only.
    function preview($invoice_id = 0, $show_close_preview = false)
    {
        if ($invoice_id) {
            $view_data = get_invoice_making_data($invoice_id);

            $this->_check_invoice_access_permission($view_data);

            $view_data['invoice_preview'] = prepare_invoice_pdf($view_data, "html");

            //show a back button
            $view_data['show_close_preview'] = $show_close_preview && $this->login_user->user_type === "staff" ? true : false;

            $view_data['invoice_id'] = $invoice_id;
            $view_data['payment_methods'] = $this->Payment_methods_model->get_available_online_payment_methods();

            //$this->load->library("paypal");
            //$view_data['paypal_url'] = $this->paypal->get_paypal_url();

            $this->template->rander("invoices/invoice_preview", $view_data);
        } else {
            show_404();
        }
    }

    //print invoice
    function print_invoice($invoice_id = 0)
    {
        if ($invoice_id) {
            $view_data = get_invoice_making_data($invoice_id);

            $this->_check_invoice_access_permission($view_data);

            $view_data['invoice_preview'] = prepare_invoice_pdf($view_data, "html");

            echo json_encode(array("success" => true, "print_view" => $this->load->view("invoices/print_invoice", $view_data, true)));
        } else {
            echo json_encode(array("success" => false, lang('error_occurred')));
        }
    }

    function download_pdf($invoice_id = 0)
    {

        if ($invoice_id) {
            $invoice_data = get_invoice_making_data($invoice_id);
            $this->_check_invoice_access_permission($invoice_data);
            prepare_invoice_pdf($invoice_data, "download");
        } else {
            show_404();
        }
    }

    private function _check_invoice_access_permission($invoice_data)
    {
        //check for valid invoice
        if (!$invoice_data) {
            show_404();
        }

        //check for security
        $invoice_info = get_array_value($invoice_data, "invoice_info");
        if ($this->login_user->user_type == "client") {
            if ($this->login_user->client_id != $invoice_info->client_id) {
                redirect("forbidden");
            }
        } else {
            $this->access_allowed_members();
        }
    }

    function send_invoice_modal_form($invoice_id)
    {
        $this->access_allowed_members();

        if ($invoice_id) {
            $options = array("id" => $invoice_id);
            $invoice_info = $this->Invoices_model->get_details($options)->row();
            $view_data['invoice_info'] = $invoice_info;
            $contacts_options = array("user_type" => "client", "client_id" => $invoice_info->client_id);
            $contacts = $this->Users_model->get_details($contacts_options)->result();
            $contact_first_name = "";
            $contact_last_name = "";
            $contacts_dropdown = array();
            foreach ($contacts as $contact) {
                if ($contact->is_primary_contact) {
                    $contact_first_name = $contact->first_name;
                    $contact_last_name = $contact->last_name;
                    $contacts_dropdown[$contact->id] = $contact->first_name . " " . $contact->last_name . " (" . lang("primary_contact") . ")";
                }
            }

            foreach ($contacts as $contact) {
                if (!$contact->is_primary_contact) {
                    $contacts_dropdown[$contact->id] = $contact->first_name . " " . $contact->last_name;
                }
            }

            $view_data['contacts_dropdown'] = $contacts_dropdown;

            $email_template = $this->Email_templates_model->get_final_template("send_invoice");

            $invoice_total_summary = $this->Invoices_model->get_invoice_total_summary($invoice_id);

            $parser_data["INVOICE_ID"] = $invoice_info->id;
            $parser_data["CONTACT_FIRST_NAME"] = $contact_first_name;
            $parser_data["CONTACT_LAST_NAME"] = $contact_last_name;
            $parser_data["BALANCE_DUE"] = to_currency($invoice_total_summary->balance_due, $invoice_total_summary->currency_symbol);
            $parser_data["DUE_DATE"] = $invoice_info->due_date;
            $parser_data["PROJECT_TITLE"] = $invoice_info->project_title;
            $parser_data["INVOICE_URL"] = get_uri("invoices/preview/" . $invoice_info->id);
            $parser_data['SIGNATURE'] = $email_template->signature;
            $parser_data["LOGO_URL"] = get_logo_url();

            $view_data['message'] = $this->parser->parse_string($email_template->message, $parser_data, TRUE);
            $view_data['subject'] = $email_template->subject;

            $this->load->view('invoices/send_invoice_modal_form', $view_data);
        } else {
            show_404();
        }
    }

    function send_invoice()
    {
        $this->access_allowed_members();

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $invoice_id = $this->input->post('id');

        $contact_id = $this->input->post('contact_id');
        $cc = $this->input->post('invoice_cc');

        $custom_bcc = $this->input->post('invoice_bcc');
        $subject = $this->input->post('subject');
        $message = decode_ajax_post_data($this->input->post('message'));

        $contact = $this->Users_model->get_one($contact_id);

        $invoice_data = get_invoice_making_data($invoice_id);
        $attachement_url = prepare_invoice_pdf($invoice_data, "send_email");

        $default_bcc = get_setting('send_bcc_to'); //get default settings
        $bcc_emails = "";

        if ($default_bcc && $custom_bcc) {
            $bcc_emails = $default_bcc . "," . $custom_bcc;
        } else if ($default_bcc) {
            $bcc_emails = $default_bcc;
        } else if ($custom_bcc) {
            $bcc_emails = $custom_bcc;
        }

        if (send_app_mail($contact->email, $subject, $message, array("attachments" => array(array("file_path" => $attachement_url)), "cc" => $cc, "bcc" => $bcc_emails))) {
            // change email status
            $status_data = array("status" => "not_paid", "last_email_sent_date" => get_my_local_time());
            if ($this->Invoices_model->save($status_data, $invoice_id)) {
                echo json_encode(array('success' => true, 'message' => lang("invoice_sent_message"), "invoice_id" => $invoice_id));
            }
            // delete the temp invoice
            if (file_exists($attachement_url)) {
                unlink($attachement_url);
            }
        } else {
            echo json_encode(array('success' => false, 'message' => lang('error_occurred')));
        }
    }

    function get_invoice_status_bar($invoice_id = 0)
    {
        $this->access_allowed_members();

        $view_data["invoice_info"] = $this->Invoices_model->get_details(array("id" => $invoice_id))->row();

        $view_data['invoice_status_label'] = $this->_get_invoice_status_label($view_data["invoice_info"]);
        $view_data['approval'] = $this->_get_approval_status_label($view_data["invoice_info"]);
        $view_data["delivery"] = $this->_get_delivery_status_label($view_data["invoice_info"]);
        $this->load->view('invoices/invoice_status_bar', $view_data);
    }

    function update_invoice_status($invoice_id = 0, $status = "")
    {
        $this->access_allowed_members();

        if ($invoice_id && $status) {
            //change the draft status of the invoice
            $this->Invoices_model->update_invoice_status($invoice_id, $status);

            //save extra information for cancellation
            if ($status == "cancelled") {
                $data = array(
                    "cancelled_at" => get_current_utc_time(),
                    "cancelled_by" => $this->login_user->id
                );

                $this->Invoices_model->save($data, $invoice_id);
            }

            echo json_encode(array("success" => true, 'message' => lang('record_saved')));
        }

        return "";
    }

    function update_delivery_status($invoice_id = 0, $status = "")
    {
        $this->access_allowed_members();

        if ($invoice_id && $status) {
            //change the draft status of the invoice
            $this->Invoices_model->update_delivery_status($invoice_id, $status);

            echo json_encode(array("success" => true, 'message' => lang('record_saved')));
        }

        return "";
    }

    /* load discount modal */

    function discount_modal_form()
    {
        $this->access_allowed_members();

        validate_submitted_data(array(
            "invoice_id" => "required|numeric"
        ));

        $invoice_id = $this->input->post('invoice_id');
        $data = $this->Invoices_model->get_invoice_total_summary($invoice_id);
        $invoice_subtotal = $data->invoice_subtotal;
        $allowed_percentage = get_array_value($this->login_user->permissions, "discount");
        if ($invoice_subtotal != 0 && $allowed_percentage != 0) {
            $view_data["allowed_discount"] = ($invoice_subtotal * $allowed_percentage) / 100;
        } else if ($this->login_user->is_admin) {
            $view_data["allowed_discount"] = $invoice_subtotal;
        } else {
            $view_data["allowed_discount"] = 0;
        }


        $view_data['model_info'] = $this->Invoices_model->get_one($invoice_id);

        $this->load->view('invoices/discount_modal_form', $view_data);
    }

    /* save discount */

    function save_discount()
    {
        $this->access_allowed_members();

        if ($this->input->post('discount_amount_type') == "percentage") {
            validate_submitted_data(array(
                "invoice_id" => "required|numeric",
                //"discount_type" => "required",
                "discount_amount" => "numeric|callback_discount|less_than_equal_to[100]|greater_than_equal_to[0]",
                "discount_amount_type" => "required"
            ));
        } else {
            $rr = $this->input->post('allowed_discount');
            validate_submitted_data(array(
                "invoice_id" => "required|numeric",
                //"discount_type" => "required",
                "discount_amount" => "numeric|less_than_equal_to[$rr]",
                "discount_amount_type" => "required"
            ));
        }

        $invoice_id = $this->input->post('invoice_id');

        $data = array(
            "discount_type" => $this->input->post('discount_type') ? $this->input->post('discount_type') : "before_discount",
            "discount_amount" => $this->input->post('discount_amount'),
            "discount_amount_type" => $this->input->post('discount_amount_type')
        );

        $data = clean_data($data);

        $save_data = $this->Invoices_model->save($data, $invoice_id);
        if ($save_data) {
            echo json_encode(array("success" => true, "invoice_total_view" => $this->_get_invoice_total_view($invoice_id), 'message' => lang('record_saved'), "invoice_id" => $invoice_id));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function discount($str)
    {

        if ($this->login_user->is_admin) {
            return true;
        } else if ($str >= get_array_value($this->login_user->permissions, "discount")) {
            $message = 'The {field} must be less than or equal to' . " " . get_array_value($this->login_user->permissions, "discount") . " %";
            $this->form_validation->set_message('discount', $message);
            return false;
        } else {
            return true;
        }
    }

    function load_statistics_of_selected_currency($currency = "")
    {
        if ($currency) {
            $statistics = invoice_statistics_widget(true, array("currency" => $currency));

            if ($statistics) {
                echo json_encode(array("success" => true, "statistics" => $statistics));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
            }
        }
    }


    function update_approval_status($invoice_id = 0, $status = "")
    {
        $this->access_allowed_members();

        if ($invoice_id && $status) {
            //change the draft status of the invoice
            $no_stock = $this->check_stock($invoice_id);
            $total_summary = $this->Invoices_model->get_invoice_total_summary($invoice_id);
            if (($status == "approved" || $status == "request_approval") && ($total_summary->balance_due < 0)) {
                echo json_encode(array("success" => false, 'message' => ('balance_due_can_not_be_a_negative_value')));
            } /*else if ($no_stock != TRUE) {
                $message = 'not_enough_stock: '.$no_stock;
                echo json_encode(array("success" => false, "stock" => $no_stock, 'message' => $message));
            }*/ else {
                $invoice_status = $this->Invoices_model->update_approval_status($invoice_id, $status);
                if ($invoice_status) {
                    $info = $this->Invoices_model->get_details(array("id" => $invoice_status))->row();
                    if ($status == "approved") {
                        $this->make_inv_entries($info);
                        // $this->make_inventory_entries($info);
                        //$this->make_prepaid_to_recievable($info);
                        $this->pushes($info);
                    }
                    echo json_encode(array("success" => true, 'message' => lang('record_saved')));
                }
            }
        }

        return "";
    }


    function make_inv_entries($info)
    {
        $date = $info->bill_date;
        $type = "Invoice: " . get_invoice_id($info->id);

        $acc_array = array();
        $narration = "Invoice: " . get_invoice_id($info->id);

        $total_summary = $this->Invoices_model->get_invoice_total_summary($info->id);
        // var_dump($total_summary);die('hi');
        $client_info = $this->Clients_model->get_one($info->client_id);

        if (!empty($total_summary->invoice_total)) {
            $acc_array[] = array("account_id" => $client_info->account_id, "type" => 'dr', "amount" => $total_summary->invoice_total, "narration" => $narration);
        }

        if (!empty($total_summary->item_discount)) {
            $acc_array[] = array("account_id" => get_setting('discount'), "type" => 'dr', "amount" => number_format($total_summary->item_discount, 3, ".", ""), "narration" => $narration);
        }

        $acc_array[] = array("account_id" => get_setting('VAT_in'), "type" => 'cr', "amount" => number_format($total_summary->tax_after_discount, 3, ".", ""), "narration" => $narration);

        // $acc_array[] = array("account_id" => get_setting('default_sales'), "type" => 'cr',"amount" => number_format($total_summary->invoice_subtotal, 3, ".", ""), "narration" => $narration); 
        $sale_account_id = $this->Sale_items_model->get_one($info->sale_item)->sale_account_id;
        $acc_array[] = array("account_id" => $sale_account_id, "type" => 'cr', "amount" => number_format($total_summary->invoice_subtotal, 3, ".", ""), "narration" => $narration);

        $transaction_id = make_transaction($date, $acc_array, $type);
    }


    function make_inventory_entries($info)
    {
        $product_summary = $this->Invoices_model->get_service_and_products_total($info->id, 'product');
        if (!empty($product_summary)) {
            $date = $info->bill_date;
            $type = "Invoice Inventory: " . get_invoice_id($info->id);

            $acc_array = array();
            $narration = get_invoice_id($info->id);

            $total_items_cost = 0;
            $invoice_items = $this->Invoices_model->get_invoice_products($info->id);
            foreach ($invoice_items as $invoice_item) {
                $item_info = $this->Invoices_model->get_invoice_item_inventory($invoice_item->item_id);
                if (isset($item_info->sum_total) && !empty($item_info->sum_qty)) {
                    $item_avg_cost = $item_info->sum_total / $item_info->sum_qty;
                    $total_items_cost += $item_avg_cost * $invoice_item->quantity;
                }
            }

            $total_items_cost = number_format($total_items_cost, 2, ".", "");

            $acc_array[] = array("account_id" => get_setting('cost_of_goods_sold'), "type" => 'dr', "amount" => $total_items_cost, "narration" => $narration);

            $acc_array[] = array("account_id" => get_setting('default_inventory'), "type" => 'cr', "amount" => $total_items_cost, "narration" => $narration);

            $transaction_id = make_transaction($date, $acc_array, $type);
        }
    }


    function pushes($info)
    {
        $notifationTo = $info->log_user_id;
        $po = get_invoice_id($info->id);
        $body = $po . ' you created has been approved';
        send_onesignal($body, '#', $notifationTo, '', '');
    }


    function make_prepaid_to_recievable($info)
    {
        $payments = $this->Invoice_payments_model->get_all_where(array("invoice_id" => $info->id, "status" => "approved", "deleted" => 0))->result();
        foreach ($payments as $payment) {

            $date = $payment->payment_date;
            $type = "Automatic Prepaid to Recievable Account: " . get_invoice_id($payment->invoice_id);

            $client_info = $this->Clients_model->get_one($info->client_id);

            $acc_array = array();
            $narration = $payment->note;

            $acc_array[] = array("account_id" => $client_info->account_id, "type" => 'cr', "amount" => $payment->amount, "narration" => $narration);

            $acc_array[] = array("account_id" => $client_info->advance_account_id, "type" => 'dr', "amount" => $payment->amount, "narration" => $narration);

            $transaction_id = make_transaction($date, $acc_array, $type);
            if ($transaction_id) {
                $trans_data = array("advance_transaction_id" => $transaction_id);
                $this->Invoice_payments_model->save($trans_data, $payment->id);
            }
        }
    }

    function check_stock($invoice_id)
    {
        $invoice_items = $this->Invoices_model->get_invoice_products($invoice_id);
        $no_stock = array();
        foreach ($invoice_items as $invoice_item) {
            $item_info = $this->Invoices_model->check_stock($invoice_item->item_id);
            if (empty($item_info->sum_qty) || ($item_info->sum_qty < $invoice_item->quantity)) {
                $no_stock[] = $invoice_item->title;
            }
        }

        if (empty($no_stock)) {
            return TRUE;
        } else {
            return  implode(", ", $no_stock);
        }
    }
    function upload_file()
    {
        upload_file_to_temp();
    }
    /* check valid file for notes */

    function validate_events_file()
    {
        return validate_post_file($this->input->post("file_name"));
    }
}

/* End of file invoices.php */
/* Location: ./application/controllers/invoices.php */