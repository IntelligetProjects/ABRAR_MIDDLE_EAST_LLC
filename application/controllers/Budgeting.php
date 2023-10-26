<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Budgeting extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->permission_checker("can_access_estimates");
        //$this->access_allowed_members();
    }

    private function can_edit() {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_edit_estimates") == "1") {
                return true;
            }
        }
    }

    private function can_delete() {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_delete_estimates") == "1") {
                return true;
            }
        }
    }

    private function can_approve() {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "approve_budgeting") == "all") {
                return true;
            }
        }
    }

    /* load estimate list view */

    function index() {

    //    log_notification("item_low_stock", array("notify_to_admins_only" => 1),1);
       log_notification("the_inventory_item_is_running_low", array("to_user_id" => 1,"item_id" => 2),0);
    //    die('hi');

        $this->check_module_availability("module_estimate");
        $view_data['can_request_estimate'] = false;

        $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("estimates", $this->login_user->is_admin, $this->login_user->user_type);

        if ($this->login_user->user_type === "staff") {
            $this->access_allowed_members();
            if (!$this->login_user->is_admin) {
                $view_data['can_create_module'] = get_array_value($this->login_user->permissions,'can_create_estimates');
            } else {
                $view_data['can_create_module'] = 1; 
            }

            $this->template->rander("budgeting/index", $view_data);
        } else {
            //client view
            $view_data["client_info"] = $this->Clients_model->get_one($this->login_user->client_id);
            $view_data['client_id'] = $this->login_user->client_id;
            $view_data['page_type'] = "full";


            if (get_setting("module_estimate_request") == "1") {
                $view_data['can_request_estimate'] = true;
            }

            $this->template->rander("clients/estimates/client_portal", $view_data);
        }
    }

    //load the yearly view of estimate list
    function yearly() {
        $this->load->view("budgeting/yearly_estimates");
    }

    /* load new estimate modal */

    function modal_form() {
        $this->access_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric",
            "project_id" => "numeric"
        ));
        $view_data['projects_dropdown'] = array("" => "-") + $this->Projects_model->get_dropdown_list(array("title"));

        $view_data['model_info'] = $this->Budgeting_model->get_one($this->input->post('id'));
        $estimate_request_id = $this->input->post('estimate_request_id');
        $view_data['estimate_request_id'] = $estimate_request_id;

        //clone estimate data
        $is_clone = $this->input->post('is_clone');
        $view_data['is_clone'] = $is_clone;

        $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("estimates", $view_data['model_info']->id, $this->login_user->is_admin, $this->login_user->user_type)->result();

        $this->load->view('budgeting/modal_form', $view_data);
    }

    function save_client() {

        validate_submitted_data(array(
            "company_name" => "required",
            "phone" => "required",
        ));

        $company_name = $this->input->post('company_name');
        $data = array(
            "company_name" => $company_name,
            "phone" => $this->input->post('phone'),
            "address" => $this->input->post('address'),
            "is_lead" => 1,
        );
        $data["created_date"] = get_current_utc_time();
        $data = clean_data($data);
        //check duplicate company name, if found then show an error message
        if (get_setting("disallow_duplicate_client_company_name") == "1" && $this->Clients_model->is_duplicate_company_name($data["company_name"], 0)) {
            echo json_encode(array("success" => false, 'message' => lang("account_already_exists_for_your_company_name")));
            exit();
        }
        //check duplicate company name, if found then show an error message
        if ($this->Clients_model->is_duplicate_phone($data["phone"], 0)) {
            echo json_encode(array("success" => false, 'message' => lang("account_already_exists_for_your_company_name")));
            exit();
        }
        $save_id = $this->Clients_model->save($data);

        if($save_id) {
           return $save_id; 
        }
        
    }

    /* add, edit or clone an estimate */

    function save() {
        $this->access_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric",
            "estimate_date" => "required",
            "project_id" => "required",
        ));

     
        $id = $this->input->post('id');

        $estimate_data = array(
            "estimate_date" => $this->input->post('estimate_date'),
            "project_id" => $this->input->post('project_id'),
            "profit" => $this->input->post('profit'),
            "note" => $this->input->post('estimate_note')
        );

        $is_clone = $this->input->post('is_clone');
        $estimate_request_id = $this->input->post('estimate_request_id');

        //estimate creation from estimate request
        //store the estimate request id for the first time only
        //don't copy estimate request id on cloning too
        if ($estimate_request_id && !$id && !$is_clone) {
            $estimate_data["estimate_request_id"] = $estimate_request_id;
        }

        $main_estimate_id = "";
        if ($is_clone && $id) {
            $main_estimate_id = $id; //store main estimate id to get items later
            $id = ""; //on cloning estimate, save as new
            //save discount when cloning
        }

        $estimate_id = $this->Budgeting_model->save($estimate_data, $id);
        if ($estimate_id) {

            if ($is_clone && $main_estimate_id) {
                //add estimate items

                save_custom_fields("budgeting", $estimate_id, 1, "staff"); //we have to keep this regarding as an admin user because non-admin user also can acquire the access to clone a estimate

                $estimate_items = $this->Budgeting_items_model->get_all_where(array("estimate_id" => $main_estimate_id, "deleted" => 0))->result();

                foreach ($estimate_items as $estimate_item) {
                    //prepare new estimate item data
                    $estimate_item_data = (array) $estimate_item;
                    unset($estimate_item_data["id"]);
                    $estimate_item_data['estimate_id'] = $estimate_id;

                    $estimate_item = $this->Budgeting_items_model->save($estimate_item_data);
                }
            } else {
                save_custom_fields("estimates", $estimate_id, $this->login_user->is_admin, $this->login_user->user_type);
            }

            echo json_encode(array("success" => true, "data" => $this->_row_data($estimate_id), 'id' => $estimate_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //update estimate status
    function update_estimate_status($estimate_id, $status) {
        if ($estimate_id && $status) {
            $estmate_info = $this->Budgeting_model->get_one($estimate_id);
            $this->access_allowed_members_or_client_contact($estmate_info->client_id);


            if ($this->login_user->user_type == "client") {
                //updating by client
                //client can only update the status once and the value should be either accepted or declined
                if ($estmate_info->status == "sent" && ($status == "accepted" || $status == "declined")) {

                    $estimate_data = array("status" => $status);
                    $estimate_id = $this->Budgeting_model->save($estimate_data, $estimate_id);

                    //create notification
                    if ($status == "accepted") {
                        log_notification("estimate_accepted", array("estimate_id" => $estimate_id));
                    } else if ($status == "declined") {
                        log_notification("estimate_rejected", array("estimate_id" => $estimate_id));
                    }
                }
            } else {
                //updating by team members

                if ($status == "sent" || $status == "accepted" || $status == "declined" || $status == "request_approval" || $status == "approved" ||$status == "draft") {
                    $estimate_data = array("status" => $status);
                    $estimate_id = $this->Budgeting_model->save($estimate_data, $estimate_id);

                    //create notification
                    if ($status == "sent") {
                        log_notification("estimate_sent", array("estimate_id" => $estimate_id));
                    }
                }
            }
        }
    }

    /* delete or undo an estimate */

    function delete() {
        $this->access_allowed_members();

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Budgeting_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Budgeting_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of estimates, prepared for datatable  */

    function list_data() {
        $this->access_allowed_members();

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("estimates", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array(
            "status" => $this->input->post("status"),
            "start_date" => $this->input->post("start_date"),
            "end_date" => $this->input->post("end_date"),
            "custom_fields" => $custom_fields
        );

        $this->permission_checker("estimate_manage_permission");
        if(!$this->login_user->is_admin || !$this->access_type === "all"){
            $options["allowed_members"] = $this->allowed_members;
        }

        $list_data = $this->Budgeting_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }

        echo json_encode(array("data" => $result));
    }
    function list_data_projects($project_id=0) {
        $this->access_allowed_members();

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("estimates", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array(
            "status" => $this->input->post("status"),
            "start_date" => $this->input->post("start_date"),
            "end_date" => $this->input->post("end_date"),
            "project_id" => $project_id,
            "custom_fields" => $custom_fields
            
        );

        $this->permission_checker("estimate_manage_permission");
        if(!$this->login_user->is_admin || !$this->access_type === "all"){
            $options["allowed_members"] = $this->allowed_members;
        }

        // $list_data = $this->Budgeting_model->get_details([ "project_id" => $project_id])->row();
        $list_data = $this->Budgeting_model->get_all_where([ "project_id" => $project_id])->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }

        echo json_encode(array("data" => $result));
    }

    /* list of estimate of a specific client, prepared for datatable  */

    function estimate_list_data_of_client($client_id) {
        $this->access_allowed_members_or_client_contact($client_id);

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("estimates", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array("client_id" => $client_id, "status" => $this->input->post("status"), "custom_fields" => $custom_fields);

        if ($this->login_user->user_type == "client") {
            //don't show draft estimates to clients.
            $options["exclude_draft"] = true;
        }

        $list_data = $this->Budgeting_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }
        echo json_encode(array("data" => $result));
    }

    /* return a row of estimate list table */

    private function _row_data($id) {
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("estimates", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array("id" => $id, "custom_fields" => $custom_fields);
        $data = $this->Budgeting_model->get_details($options)->row();
        return $this->_make_row($data, $custom_fields);
    }

    /* prepare a row of estimate list table */

    private function _make_row($data, $custom_fields) {
        $estimate_url = "";
        if ($this->login_user->user_type == "staff") {
            $estimate_url = anchor(get_uri("budgeting/view/" . $data->id), get_budget_id($data->id));
        } 
        else {
            //for client client
            $estimate_url = anchor(get_uri("budgeting/preview/" . $data->id), get_budget_id($data->id));
        }
        
        // $project_url = anchor(get_uri("projects/view/" . $data->project_id), get_budget_id($data->project_id));
        $project_url = anchor(get_uri("projects/view/" . $data->project_id), "Project #".$data->project_id);
        // if ($data->is_lead) {
        //     $client = anchor(get_uri("leads/view/" . $data->client_id), $data->company_name . "</br>" . $data->phone);
        // }

        $row_data = array(
            $data->id,
            $estimate_url,
            $project_url,
            $data->estimate_date,
            $data->profit,
            format_to_date($data->estimate_date, false),
        );

        foreach ($custom_fields as $field) {
            $cf_id = "cfv_" . $field->id;
            $row_data[] = $this->load->view("custom_fields/output_" . $field->field_type, array("value" => $data->$cf_id), true);
        }


        $rowe = "";
        
        if ($this->can_edit() && ($data->status == "draft")) { 
            $rowe .= modal_anchor(get_uri("budgeting/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_budgeting'), "data-post-id" => $data->id));
        }
        
        if ($this->can_delete() && ($data->status == "draft")) { 
            $rowe .= js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_estimate'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("budgeting/delete"), "data-action" => "delete"));
        }

        $row_data[] = $rowe;

        return $row_data;
    }

    //prepare estimate status label 
    private function _get_estimate_status_label($estimate_info, $return_html = true) {
        $estimate_status_class = "label-default";

        //don't show sent status to client, change the status to 'new' from 'sent'

        if ($this->login_user->user_type == "client") {
            if ($estimate_info->status == "sent") {
                $estimate_info->status = "new";
            } else if ($estimate_info->status == "declined") {
                $estimate_info->status = "rejected";
            }
        }

        if ($estimate_info->status == "draft") {
            $estimate_status_class = "label-default";
        } else if ($estimate_info->status == "declined" || $estimate_info->status == "rejected") {
            $estimate_status_class = "label-danger";
        } else if ($estimate_info->status == "accepted") {
            $estimate_status_class = "label-success";
        } else if ($estimate_info->status == "sent") {
            $estimate_status_class = "label-primary";
        } else if ($estimate_info->status == "new") {
            $estimate_status_class = "label-warning";
        } else if ($estimate_info->status == "approved") {
            $estimate_status_class = "label-success";
        } else {
            $estimate_status_class = "label-warning";
        }

        $estimate_status = "<span class='mt0 label $estimate_status_class large'>" . lang($estimate_info->status) . "</span>";
        if ($return_html) {
            return $estimate_status;
        } else {
            return $estimate_info->status;
        }
    }

    /* load estimate details view */

    function view($estimate_id = 0) {
        $this->access_allowed_members();

        if ($estimate_id) {

            $view_data = get_budgeting_making_data($estimate_id);
        //    var_dump($view_data);die();
            if ($view_data) {
                $view_data['estimate_status_label'] = $this->_get_estimate_status_label($view_data["estimate_info"]);
                $view_data['estimate_status'] = $this->_get_estimate_status_label($view_data["estimate_info"], false);

                $access_info = $this->get_access("can_create_invoices");
                $view_data["show_invoice_option"] = (get_setting("module_invoice") && $access_info->access_type == "1") ? true : false;

                $view_data["can_create_projects"] = $this->can_create_projects();
                $view_data["can_approve"] = $this->can_approve();

                $view_data["estimate_id"] = $estimate_id;

                $this->template->rander("budgeting/view", $view_data);
            } else {
                show_404();
            }
        }
    }

    /* estimate total section */

    private function _get_estimate_total_view($estimate_id = 0) {
        $view_data["estimate_total_summary"] = $this->Budgeting_model->get_estimate_total_summary($estimate_id);
        $view_data["estimate_id"] = $estimate_id;
        $view_data["estimate_status"] = $this->Budgeting_model->get_one($estimate_id)->status;
        return $this->load->view('budgeting/estimate_total_section', $view_data, true);
    }

    /* load discount modal */

    function discount_modal_form() {
        $this->access_allowed_members();

        validate_submitted_data(array(
            "estimate_id" => "required|numeric"
        ));

        $estimate_id = $this->input->post('estimate_id');
         $data = $this->Budgeting_model->get_estimate_total_summary($estimate_id);
         $estimate_subtotal = $data->estimate_subtotal;
         $allowed_percentage = get_array_value($this->login_user->permissions, "discount");
         if($estimate_subtotal != 0 && $allowed_percentage !=0) {
            $view_data["allowed_discount"] = ($estimate_subtotal * $allowed_percentage) / 100;
        } else if ($this->login_user->is_admin){
           $view_data["allowed_discount"] = $estimate_subtotal; 
        } else {
           $view_data["allowed_discount"] = 0; 
        }

        $view_data['model_info'] = $this->Budgeting_model->get_one($estimate_id);

        $this->load->view('budgeting/discount_modal_form', $view_data);
    }

    /* save discount */

    function save_discount() {
        $this->access_allowed_members();
        if ($this->input->post('discount_amount_type') == "percentage") {
            validate_submitted_data(array(
                "estimate_id" => "required|numeric",
                //"discount_type" => "required",
                "discount_amount" => "numeric|callback_discount|less_than_equal_to[100]|greater_than_equal_to[0]",
                "discount_amount_type" => "required"
            ));
        } else {
            $rr = $this->input->post('allowed_discount');
            validate_submitted_data(array(
                "estimate_id" => "required|numeric",
                //"discount_type" => "required",
                "discount_amount" => "numeric|less_than_equal_to[$rr]",
                "discount_amount_type" => "required"
            ));

        } 

        $estimate_id = $this->input->post('estimate_id');

        $data = array(
            "discount_type" => $this->input->post('discount_type') ? $this->input->post('discount_type'): "before_tax",
            "discount_amount" => $this->input->post('discount_amount'),
            "discount_amount_type" => $this->input->post('discount_amount_type')
        );

        $data = clean_data($data);

        $save_data = $this->Budgeting_model->save($data, $estimate_id);
        if ($save_data) {
            echo json_encode(array("success" => true, 'message' => lang('record_saved'), "estimate_id" => $estimate_id));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function discount ($str) {

        if ($this->login_user->is_admin) {
            return true;
        } else if ($str >= get_array_value($this->login_user->permissions, "discount")) {
            $message = 'The {field} must be less than or equal to'. " " . get_array_value($this->login_user->permissions, "discount")." %";
             $this->form_validation->set_message('discount', $message);
            return false;
        } else {
            return true;
        }

    }

    /* load item modal */

    function item_modal_form() {
        $this->access_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $estimate_id = $this->input->post('estimate_id');

        $view_data['model_info'] = $this->Budgeting_items_model->get_one($this->input->post('id'));
        // var_dump($view_data['model_info']);
        if (!$estimate_id) {
            $estimate_id = $view_data['model_info']->estimate_id;
        }
        $view_data['estimate_id'] = $estimate_id;

        $view_data['uom_dropdown'] = array("" => "-") + $this->Item_uom_model->get_dropdown_list(array("title"));
       
        $this->load->view('budgeting/item_modal_form', $view_data);
    }

    /* add or edit an estimate item */

    function save_item() {
        $this->access_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric",
            "estimate_id" => "required|numeric"
        ));

        $estimate_id = $this->input->post('estimate_id');

        $id = $this->input->post('id');
        $quotation_selling_rate = $this->input->post('quotation_selling_rate')?unformat_currency($this->input->post('quotation_selling_rate')):0;
        $item_price = $this->input->post('item_price')?unformat_currency($this->input->post('item_price')):0;
        $quotation_quantity = $this->input->post('quotation_quantity')?unformat_currency($this->input->post('quotation_quantity')):0;
        $cost_rate = $this->input->post('cost_rate')?unformat_currency($this->input->post('cost_rate')):0;
        $cost_quantity = $this->input->post('cost_quantity')?unformat_currency($this->input->post('cost_quantity')):0;

        $estimate_item_data = array(
            "estimate_id" => $estimate_id,
            "title" => $this->input->post('estimate_item_title'),
            "item_price" => $item_price,
            "item_id" => $this->input->post('estimate_item_id'),
            "description" => $this->input->post('estimate_item_description'),
            "quotation_uom" => $this->input->post('quotation_uom'),
            "quotation_quantity" => $quotation_quantity,
            "quotation_selling_rate" => $quotation_selling_rate,
            "quotation_total" => $quotation_quantity * $quotation_selling_rate,
            "cost_uom" => $this->input->post('cost_uom'),
            "cost_quantity" => $cost_quantity ,
            "cost_rate" =>  $cost_rate,
            "cost_total" => $cost_quantity * $cost_rate,
            "material_cost" => $this->input->post('material_cost'),
            "labour_cost" => $this->input->post('labour_cost'),
            "machinery" => $this->input->post('machinery'),
            "others" => $this->input->post('others'),
            // "rate" => unformat_currency($this->input->post('estimate_item_rate')),
            // "tax_id" => $this->input->post('tax_id') ? $this->input->post('tax_id') : 0,
            // "tax_id2" => $this->input->post('tax_id2') ? $this->input->post('tax_id2') : 0,
        );

        $estimate_item_id = $this->Budgeting_items_model->save($estimate_item_data, $id);
        if ($estimate_item_id) {


            //check if the add_new_item flag is on, if so, add the item to libary. 
            $add_new_item_to_library = $this->input->post('add_new_item_to_library');
            if ($add_new_item_to_library) {
                $library_item_data = array(
                    "title" => $this->input->post('estimate_item_title'),
                    "description" => $this->input->post('estimate_item_description'),
                    "unit_type" => $this->input->post('estimate_unit_type'),
                    "rate" => unformat_currency($this->input->post('estimate_item_rate')),
                    "category_id" => $this->input->post('category_id'),
                    "item_type" => $this->input->post('item_type')
                );
                $this->Items_model->save($library_item_data);
            }

            $options = array("id" => $estimate_item_id);
            $item_info = $this->Budgeting_items_model->get_details($options)->row();
            echo json_encode(array("success" => true, "estimate_id" => $item_info->estimate_id, "data" => $this->_make_item_row($item_info),  'id' => $estimate_item_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    /* load multiple items modal */

    function items_modal_form() {
        $this->access_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $estimate_id = $this->input->post('estimate_id');
        
        $view_data['estimate_id'] = $estimate_id;


        $items = $this->Items_model->get_all_where(array("deleted" => 0))->result();
        $items_dropdown = array();

        foreach ($items as $item) {
            $items_dropdown[] = array("id" => $item->id, "text" => $item->title);
        }

        $view_data['items_dropdown'] = json_encode($items_dropdown);

        $this->load->view('budgeting/items_modal_form', $view_data);
    }

    /* add or edit multiple estimate item */

    function save_items() {
        $this->access_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric",
            "estimate_id" => "required|numeric"
        ));

        $estimate_id = $this->input->post('estimate_id');

        $items = explode(',', $this->input->post('items'));

        foreach ($items as $item) {
            $item_data = $this->Items_model->get_one($item);

            $estimate_item_data = array(
                "estimate_id" => $estimate_id,
                "item_id" => $item, 
                "title" => $item_data->title,
                "description" => $item_data->description,
                "quotation_quantity" => '1',
              
            );

            $estimate_item_id = $this->Budgeting_items_model->save($estimate_item_data);
        }

        if($estimate_item_id) {
            echo json_encode(array("success" => true, "data" => '', 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    /* delete or undo an estimate item */

    function delete_item() {
        $this->access_allowed_members();

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Budgeting_items_model->delete($id, true)) {
                $options = array("id" => $id);
                $item_info = $this->Budgeting_items_model->get_details($options)->row();
                echo json_encode(array("success" => true, "estimate_id" => $item_info->estimate_id, "data" => $this->_make_item_row($item_info),  "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Budgeting_items_model->delete($id)) {
                $item_info = $this->Budgeting_items_model->get_one($id);
                echo json_encode(array("success" => true, "estimate_id" => $item_info->estimate_id,  'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of estimate items, prepared for datatable  */

    function item_list_data($estimate_id = 0) {
        $this->access_allowed_members();

        $list_data = $this->Budgeting_items_model->get_details(array("estimate_id" => $estimate_id))->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_item_row($data);
        }
        echo json_encode(array("data" => $result));
    }


    /* prepare a row of estimate item list table */

    private function _make_item_row($data) {
        $item = "<b>$data->title</b>";
        if ($data->description) {
            $item .= "<br /><span>" . nl2br($data->description) . "</span>";
        }
       
        return array(
            $item,
        //    $data->quotation_uom,
        //    $data->quotation_quantity,
        $data->cost_uom,
        $data->cost_quantity,
        $data->material_cost,
        $data->labour_cost,
        $data->machinery,
        $data->others,
        $data->cost_rate,
        $data->cost_total,
        $data->quotation_selling_rate,
        $data->quotation_total,
          
           
           
          
          
            modal_anchor(get_uri("budgeting/item_modal_form"), "<i class='fa fa-pencil'></i>",
             array("class" => "edit", "title" => lang('edit_estimate'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>",
             array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, 
             "data-action-url" => get_uri("budgeting/delete_item"), "data-action" => "delete")) 
             
        );
    }

    /* add and minus qty */
    function change_item_qty($operation = 'plus') {
        $this->access_allowed_members();
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        $id = $this->input->post('id');
        $options = array("id" => $id);
        $item_info = $this->Budgeting_items_model->get_details($options)->row();

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

        if ($this->Budgeting_items_model->save($data, $id)) {
            $item_info = $this->Budgeting_items_model->get_one($id);
            echo json_encode(array("success" => true, "quantity" => $quantity, 'message' => lang('record_saved')));
        }
    }

    /* prepare suggestion of estimate item */

    function get_estimate_item_suggestion() {
        $key = $_REQUEST["q"];
        $suggestion = array();

        $items = $this->Invoice_items_model->get_item_suggestion($key);

        foreach ($items as $item) {
            $suggestion[] = array("id" => $item->title, "text" => $item->title);
        }

        //$suggestion[] = array("id" => "+", "text" => "+ " . lang("create_new_item"));

        echo json_encode($suggestion);
    }

    function get_estimate_item_info_suggestion() {
        $item = $this->Invoice_items_model->get_item_info_suggestion($this->input->post("item_name"));
        if ($item) {
            echo json_encode(array("success" => true, "item_info" => $item));
        } else {
            echo json_encode(array("success" => false));
        }
    }

    //view html is accessable to client only.
    function preview($estimate_id = 0, $show_close_preview = false) {

        $view_data = array();

        if ($estimate_id) {

            $estimate_data = get_estimate_making_data($estimate_id);
            $this->_check_estimate_access_permission($estimate_data);

            //get the label of the estimate
            $estimate_info = get_array_value($estimate_data, "estimate_info");
            $estimate_data['estimate_status_label'] = $this->_get_estimate_status_label($estimate_info);

            $view_data['estimate_preview'] = prepare_estimate_pdf($estimate_data, "html");

            //show a back button
            $view_data['show_close_preview'] = $show_close_preview && $this->login_user->user_type === "staff" ? true : false;

            $view_data['estimate_id'] = $estimate_id;

            $this->template->rander("budgeting/estimate_preview", $view_data);
        } else {
            show_404();
        }
    }

    function download_pdf($estimate_id = 0) {
        if ($estimate_id) {
            $estimate_data = get_estimate_making_data($estimate_id);
            $this->_check_estimate_access_permission($estimate_data);

            if (@ob_get_length())
                @ob_clean();
            //so, we have a valid estimate data. Prepare the view.

            prepare_estimate_pdf($estimate_data, "download");
        } else {
            show_404();
        }
    }

    private function _check_estimate_access_permission($estimate_data) {
        //check for valid estimate
        if (!$estimate_data) {
            show_404();
        }

        //check for security
        $estimate_info = get_array_value($estimate_data, "estimate_info");
        if ($this->login_user->user_type == "client") {
            if ($this->login_user->client_id != $estimate_info->client_id) {
                redirect("forbidden");
            }
        } else {
            $this->access_allowed_members();
        }
    }

    function get_estimate_status_bar($estimate_id = 0) {
        $this->access_allowed_members();

        $view_data["estimate_info"] = $this->Budgeting_model->get_details(array("id" => $estimate_id))->row();
        $view_data['estimate_status_label'] = $this->_get_estimate_status_label($view_data["estimate_info"]);
        $this->load->view('budgeting/estimate_status_bar', $view_data);
    }

    function send_estimate_modal_form($estimate_id) {
        $this->access_allowed_members();

        if ($estimate_id) {
            $options = array("id" => $estimate_id);
            $estimate_info = $this->Budgeting_model->get_details($options)->row();
            $view_data['estimate_info'] = $estimate_info;

            $is_lead = $this->input->post('is_lead');
            if ($is_lead) {
                $contacts_options = array("user_type" => "lead", "client_id" => $estimate_info->client_id);
            } else {
                $contacts_options = array("user_type" => "client", "client_id" => $estimate_info->client_id);
            }

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

            $email_template = $this->Email_templates_model->get_final_template("estimate_sent");

            $parser_data["ESTIMATE_ID"] = $estimate_info->id;
            $parser_data["CONTACT_FIRST_NAME"] = $contact_first_name;
            $parser_data["CONTACT_LAST_NAME"] = $contact_last_name;
            $parser_data["PROJECT_TITLE"] = $estimate_info->project_title;
            $parser_data["ESTIMATE_URL"] = get_uri("budgeting/preview/" . $estimate_info->id);
            $parser_data['SIGNATURE'] = $email_template->signature;
            $parser_data["LOGO_URL"] = get_logo_url();

            $view_data['message'] = $this->parser->parse_string($email_template->message, $parser_data, TRUE);
            $view_data['subject'] = $email_template->subject;

            $this->load->view('budgeting/send_estimate_modal_form', $view_data);
        } else {
            show_404();
        }
    }

    function send_estimate() {
        $this->access_allowed_members();

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $estimate_id = $this->input->post('id');

        $contact_id = $this->input->post('contact_id');
        $cc = $this->input->post('estimate_cc');

        $custom_bcc = $this->input->post('estimate_bcc');
        $subject = $this->input->post('subject');
        $message = decode_ajax_post_data($this->input->post('message'));

        $contact = $this->Users_model->get_one($contact_id);

        $estimate_data = get_estimate_making_data($estimate_id);
        $attachement_url = prepare_estimate_pdf($estimate_data, "send_email");

        $default_bcc = get_setting('send_estimate_bcc_to');
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
            $status_data = array("status" => "sent", "last_email_sent_date" => get_my_local_time());
            if ($this->Budgeting_model->save($status_data, $estimate_id)) {
                echo json_encode(array('success' => true, 'message' => lang("estimate_sent_message"), "estimate_id" => $estimate_id));
            }
            // delete the temp estimate
            if (file_exists($attachement_url)) {
                unlink($attachement_url);
            }
        } else {
            echo json_encode(array('success' => false, 'message' => lang('error_occurred')));
        }
    }

}

/* End of file estimates.php */
/* Location: ./application/controllers/estimates.php */