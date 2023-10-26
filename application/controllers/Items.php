<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Items extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->permission_checker("can_access_items");
        $this->access_allowed_members();
    }

    /*protected function validate_access_to_items() {
        $access_invoice = $this->get_access_info("invoice");
        $access_estimate = $this->get_access_info("estimate");

        //don't show the items if invoice/estimate module is not enabled
        if(!(get_setting("module_invoice") == "1" || get_setting("module_estimate") == "1" )){
            redirect("forbidden");
        }
        
        if ($this->login_user->is_admin) {
            return true;
        } else if ($access_invoice->access_type === "all" || $access_estimate->access_type === "all") {
            return true;
        } else {
            redirect("forbidden");
        }
    }*/

    //load note list view
    function index()
    {
        //$this->validate_access_to_items();

        if (!$this->login_user->is_admin) {
            $view_data['can_create_module'] = get_array_value($this->login_user->permissions, 'can_create_items');
        } else {
            $view_data['can_create_module'] = 1;
        }

        $view_data['categories_dropdown'] = $this->_get_categories_dropdown();
        $view_data['types_dropdown'] = $this->_types_dropdown();

        $this->template->rander("items/index", $view_data);
    }

    //get categories dropdown
    private function _get_categories_dropdown()
    {
        $categories = $this->Item_categories_model->get_all_where(array("deleted" => 0), 0, 0, "title")->result();

        $categories_dropdown = array(array("id" => "", "text" => "- " . lang("category") . " -"));
        foreach ($categories as $category) {
            $categories_dropdown[] = array("id" => $category->id, "text" => $category->title);
        }

        return json_encode($categories_dropdown);
    }

    //get categories dropdown
    private function _types_dropdown()
    {

        $categories_dropdown = array(array("id" => "", "text" => "- " . lang("type") . " -"));
        $categories_dropdown[] = array("id" => "service", "text" => lang("service"));
        $categories_dropdown[] = array("id" => "product", "text" => lang("product"));

        return json_encode($categories_dropdown);
    }

    /* load item modal */

    function modal_form()
    {
        //$this->validate_access_to_items();

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['categories_dropdown'] = array("" => "-") + $this->Item_categories_model->get_dropdown_list(array("title"));
        $view_data['types_dropdown'] = array("" => "-", 'service' => lang('service'), 'product' => lang('product'));

        $view_data['model_info'] = $this->Items_model->get_one($this->input->post('id'));

        $this->load->view('items/modal_form', $view_data);
    }

    /* add or edit an item */

    function save()
    {
        //$this->validate_access_to_items();

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        if ($this->db->dbprefix == 'tarteeb_v3') {
            validate_submitted_data(array(
                "max_stock" => "numeric",
                "notify_max_stock_on" => "numeric"
            ));
        }

        $id = $this->input->post('id');
        if ($this->db->dbprefix == 'Tadqeeq') {
            $item_data = array(
                "title" => $this->input->post('title'),
                "description" => $this->input->post('description'),
                "unit_type" => $this->input->post('unit_type'),
                "certificate_no" => $this->input->post('certificate_no'),
                "rate" => unformat_currency($this->input->post('item_rate')),
                "cost" => unformat_currency($this->input->post('item_cost')),
                "category_id" => $this->input->post('category_id'),
                "item_type" => $this->input->post('item_type')
            );
        } else if ($this->db->dbprefix == 'tarteeb_v3') {
            $max_stock = $this->input->post('max_stock');
            $notify_max_stock_on = $this->input->post('notify_max_stock_on');
            if ($notify_max_stock_on > $max_stock) {
                echo json_encode(array("success" => false, 'message' => lang('notify_on_cannot_be_bigger_than_max_stock')));
                return;
            }
            $item_data = array(
                "title" => $this->input->post('title'),
                "description" => $this->input->post('description'),
                "unit_type" => $this->input->post('unit_type'),
                "rate" => unformat_currency($this->input->post('item_rate')),
                "cost" => unformat_currency($this->input->post('item_cost')),
                "category_id" => $this->input->post('category_id'),
                "item_type" => $this->input->post('item_type'),
                "max_stock" => $max_stock,
                "notify_max_stock_on" => $notify_max_stock_on
            );
        } else {
            $item_data = array(
                "title" => $this->input->post('title'),
                "description" => $this->input->post('description'),
                "unit_type" => $this->input->post('unit_type'),
                "rate" => unformat_currency($this->input->post('item_rate')),
                "cost" => unformat_currency($this->input->post('item_cost')),
                "category_id" => $this->input->post('category_id'),
                "item_type" => $this->input->post('item_type'),
            );
        }


        $item_id = $this->Items_model->save($item_data, $id);
        if ($item_id) {
            $options = array("id" => $item_id);
            $item_info = $this->Items_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $item_info->id, "data" => $this->_make_item_row($item_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    /* delete or undo an item */

    function delete()
    {
        //$this->validate_access_to_items();

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Items_model->delete($id, true)) {
                $options = array("id" => $id);
                $item_info = $this->Items_model->get_details($options)->row();
                echo json_encode(array("success" => true, "id" => $item_info->id, "data" => $this->_make_item_row($item_info), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Items_model->delete($id)) {
                $item_info = $this->Items_model->get_one($id);
                echo json_encode(array("success" => true, "id" => $item_info->id, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of items, prepared for datatable  */

    function list_data()
    {
        //$this->validate_access_to_items();
        $category_id = $this->input->post('category_id');
        $item_type = $this->input->post('item_type');
        $options = array(
            "category_id" => $category_id,
            "item_type" => $item_type
        );
        $this->permission_checker("item_manage_permission");
        if (!$this->login_user->is_admin || !$this->access_type === "all") {
            $options["allowed_members"] = $this->allowed_members;
        }
        $list_data = $this->Items_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_item_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function can_edit()
    {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_edit_items") == "1") {
                return true;
            }
        }
    }

    private function can_delete()
    {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_delete_items") == "1") {
                return true;
            }
        }
    }

    /* prepare a row of item list table */

    private function _make_item_row($data)
    {
        // var_dump($data);die();
        $type = $data->unit_type ? $data->unit_type : "";
        $stock = $data->purchased_qty - $data->invoiced_qty + $data->ad_qty - $data->purchase_return_qty + $data->sale_return_qty;

        if ($data->item_type == 'service') {
            $purchase = "-";
        } else {
            $purchase = $data->purchased_qty ? $data->purchased_qty : 0;
        }
        if ($data->item_type == 'service') {
            $deliver = "-";
        } else {
            $deliver = $data->delivered_qty ? $data->delivered_qty : 0;
        }
        if ($data->item_type == 'service') {
            $shipment = "-";
        } else {
            $shipment = $data->shipment_qty ? $data->shipment_qty : 0;
        }
        if ($data->item_type == 'service') {
            $ad = "-";
        } else {
            $ad = $data->ad_qty ? $data->ad_qty : 0;
        }
        if ($data->item_type == 'service') {
            $stocks = "-";
        } else {
            $stocks = $stock ? $stock : 0;
            // new 
            $stocks = $shipment - $deliver + $ad;
        }


        if ($this->db->dbprefix == 'Tadqeeq') {
            $certificate_no = $this->db->dbprefix == 'Tadqeeq' ? $data->certificate_no : '';
            $row = array(
                $data->id,
                $data->title,
                $data->category_title,
                lang($data->item_type),
                nl2br($data->description),
                $type,
                $certificate_no,
                $data->cost,
                $data->rate,
                $purchase,
                $shipment,
                $data->invoiced_qty ? $data->invoiced_qty : 0,
                $deliver,
                $stocks,
            );
        } else if ($this->db->dbprefix == 'tarteeb_v3') {
            $row = array(
                $data->id,
                $data->title,
                $data->category_title,
                lang($data->item_type),
                nl2br($data->description),
                $type,
                $data->cost,
                $data->rate,
                $purchase,
                $shipment,
                $data->invoiced_qty ? $data->invoiced_qty : 0,
                $deliver,
                $stocks,
                $data->max_stock,
            );
        } else {
            $row = array(
                $data->id,
                $data->title,
                $data->category_title,
                lang($data->item_type),
                nl2br($data->description),
                $type,
                $data->cost,
                $data->rate,
                $purchase,
                $shipment,
                $data->invoiced_qty ? $data->invoiced_qty : 0,
                $deliver,
                $stocks,
            );
        }

        $rowe = "";

        if ($this->can_edit()) {
            $rowe .= modal_anchor(get_uri("items/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_item'), "data-post-id" => $data->id));
        }

        if ($this->can_delete()) {
            $rowe .= js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("items/delete"), "data-action" => "delete"));
        }

        if ($this->can_edit()) {
            $rowe .= modal_anchor(get_uri("items/adjustment_modal_form"), "<i class='fa fa-plus-circle'></i>", array("class" => "edit", "title" => lang('adjust_stock'), "data-post-id" => $data->id));
        }

        $row[] = $rowe;

        return $row;
    }

    //load item add/edit modal form
    function adjustment_modal_form()
    {
        validate_submitted_data(array(
            "id" => "numeric|required"
        ));

        //make the drodown lists

        $item_id = $this->input->post('id');

        $view_data["item_id"] = $item_id;

        $view_data['model_info'] = $this->Items_model->get_details(array("id" => $item_id))->row();

        // $view_data['current_quantity'] = $view_data['model_info']->purchased_qty - $view_data['model_info']->invoiced_qty + $view_data['model_info']->ad_qty;
        $view_data['current_quantity'] = $view_data['model_info']->shipment_qty - $view_data['model_info']->delivered_qty + $view_data['model_info']->ad_qty;

        $data = $this->Stock_adjustments_model->get_all_where(array("item_id" => $item_id))->result()[0];
        $ad = $data->quantity;
        $view_data['adj_quantity'] = $ad ? $ad : 0;
        $view_data['adj_note'] = $data->note;
        $this->load->view('items/adjustment_modal_form', $view_data);
    }

    //save item
    function save_adjustment()
    {

        validate_submitted_data(array(
            "item_id" => "required|numeric",
            "quantity" => "numeric",
        ));

        $quantity = $this->input->post('quantity') ? $this->input->post('quantity') : 0;

        $item_id = $this->input->post('item_id');

        $item_info = $this->Items_model->get_details(array("id" => $item_id))->row();

        $id = $this->Stock_adjustments_model->get_all_where(array("item_id" => $item_id))->result()[0]->id;
        // $current_quantity = $item_info->purchased_qty - $item_info->invoiced_qty + $item_info->ad_qty;
        $current_quantity = $item_info->shipment_qty - $item_info->delivered_qty + $item_info->ad_qty;

        // $new_quantity = $quantity - $current_quantity;
        // $new_quantity = $quantity + $current_quantity;

        // if ($current_quantity != $quantity) {

        $item_data = array(
            "item_id" => $item_id,
            // "quantity" => $new_quantity,
            "quantity" => $quantity,
            "note" => $this->input->post('note'),
        );

        $stk_id = $this->Stock_adjustments_model->save($item_data, $id);
        // }

        $save_id = $item_id;
        if ($save_id) {
            $options = array("id" => $save_id);
            $item_info = $this->Items_model->get_details($options)->row();
            echo json_encode(array("success" => true, "data" => $this->_make_item_row($item_info), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function upload_excel_file()
    {
        upload_file_to_temp(true);
    }

    function import_items_modal_form()
    {
        $this->access_allowed_members();
        $this->load->view("items/import_items_modal_form");
    }

    function save_item_from_excel_file()
    {
        if ($this->validate_import_items_file_data(true)) {
            $file_name = $this->input->post('file_name');
            require_once(APPPATH . "third_party/php-excel-reader/SpreadsheetReader.php");

            $temp_file_path = get_setting("temp_file_path");
            $excel_file = new SpreadsheetReader($temp_file_path . $file_name);
            $allowed_headers = $this->_get_allowed_headers();

            foreach ($excel_file as $key => $value) {
                //first line should be headers, skip this row
                if ($key !== 0) {
                    $item_data = array();

                    foreach ($value as $row_data_key => $row_data_value) {
                        $header_key_value = get_array_value($allowed_headers, $row_data_key);


                        if ($row_data_value) {
                            if ($header_key_value == "category") {
                                $item_data["category_id"] = $this->_get_category_id($row_data_value);
                            } else if ($header_key_value == "type") {
                                $item_data["item_type"] = $this->_get_item_type($row_data_value);
                            } else {
                                $item_data[$header_key_value] = $row_data_value;
                            }
                        }
                    }

                    if ($item_data && count($item_data)) {
                        //save client data
                        $client_save_id = $this->Items_model->save($item_data);
                    }
                }
            }

            delete_file_from_directory($temp_file_path . $file_name); //delete temp file

            echo json_encode(array('success' => true, 'message' => lang("record_saved")));
        } else {
            echo json_encode(array('success' => false, 'message' => lang('error_occurred')));
        }
    }

    function validate_import_items_file()
    {
        $this->access_allowed_members();

        $file_name = $this->input->post("file_name");
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        if (!is_valid_file_to_upload($file_name)) {
            echo json_encode(array("success" => false, 'message' => lang('invalid_file_type')));
            exit();
        }

        if ($file_ext == "xlsx") {
            echo json_encode(array("success" => true));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('please_upload_a_excel_file') . " (.xlsx)"));
        }
    }

    function validate_import_items_file_data($check_on_submit = false)
    {
        $this->access_allowed_members();

        $table_data = "";
        $error_message = "";
        $headers = array();
        $got_error_header = false; //we've to check the valid headers first, and a single header at a time
        $got_error_table_data = false;

        $file_name = $this->input->post("file_name");

        require_once(APPPATH . "third_party/php-excel-reader/SpreadsheetReader.php");

        $temp_file_path = get_setting("temp_file_path");
        $excel_file = new SpreadsheetReader($temp_file_path . $file_name);

        $table_data .= '<table class="table table-responsive table-bordered table-hover" style="width: 100%; color: #444;">';

        $table_data_header_array = array();
        $table_data_body_array = array();

        foreach ($excel_file as $row_key => $value) {
            if ($row_key == 0) {
                $headers = $this->_store_headers_position($value);

                foreach ($headers as $row_data) {
                    $has_error_class = false;
                    if (get_array_value($row_data, "has_error") && !$got_error_header) {
                        $has_error_class = true;
                        $error_message = sprintf(lang("error"), lang(get_array_value($row_data, "key_value")));
                        $got_error_header = true;
                    }

                    array_push($table_data_header_array, array("has_error_class" => $has_error_class, "value" => get_array_value($row_data, "value")));
                }
            } else {
                $error_message_on_this_row = "<ol class='pl15'>";
                $has_contact_first_name = get_array_value($value, 1) ? true : false;

                foreach ($value as $key => $row_data) {
                    $has_error_class = false;

                    if (!$got_error_header) {
                        $row_data_validation = $this->_row_data_validation_and_get_error_message($key, $row_data, $has_contact_first_name);
                        if ($row_data_validation) {
                            $has_error_class = true;
                            $error_message_on_this_row .= "<li>" . $row_data_validation . "</li>";
                            $got_error_table_data = true;
                        }
                    }

                    $table_data_body_array[$row_key][] = array("has_error_class" => $has_error_class, "value" => $row_data);
                }

                $error_message_on_this_row .= "</ol>";

                //error messages for this row
                if ($got_error_table_data) {
                    $table_data_body_array[$row_key][] = array("has_error_text" => true, "value" => $error_message_on_this_row);
                }
            }
        }

        //return false if any error found on submitting file
        if ($check_on_submit) {
            return ($got_error_header || $got_error_table_data) ? false : true;
        }

        //add error header if there is any error in table body
        if ($got_error_table_data) {
            array_push($table_data_header_array, array("has_error_text" => true, "value" => lang("error")));
        }

        //add headers to table
        $table_data .= "<tr>";
        foreach ($table_data_header_array as $table_data_header) {
            $error_class = get_array_value($table_data_header, "has_error_class") ? "error" : "";
            $error_text = get_array_value($table_data_header, "has_error_text") ? "text-danger" : "";
            $value = get_array_value($table_data_header, "value");
            $table_data .= "<th class='$error_class $error_text'>" . $value . "</th>";
        }
        $table_data .= "<tr>";

        //add body data to table
        foreach ($table_data_body_array as $table_data_body_row) {
            $table_data .= "<tr>";

            foreach ($table_data_body_row as $table_data_body_row_data) {
                $error_class = get_array_value($table_data_body_row_data, "has_error_class") ? "error" : "";
                $error_text = get_array_value($table_data_body_row_data, "has_error_text") ? "text-danger" : "";
                $value = get_array_value($table_data_body_row_data, "value");
                $table_data .= "<td class='$error_class $error_text'>" . $value . "</td>";
            }

            $table_data .= "<tr>";
        }

        //add error message for header
        if ($error_message) {
            $total_columns = count($table_data_header_array);
            $table_data .= "<tr><td class='text-danger' colspan='$total_columns'><i class='fa fa-warning'></i> " . $error_message . "</td></tr>";
        }

        $table_data .= "</table>";

        echo json_encode(array("success" => true, 'table_data' => $table_data, 'got_error' => ($got_error_header || $got_error_table_data) ? true : false));
    }
    function view($id)
    {
        $item_info = $this->Items_model->get_details(['id' => $id])->row();
        $view_data["item_info"] = $item_info;
        if ($this->can_edit()) {
            $view_data["can_edit"] = true;
        } else {
            $view_data["can_edit"] = false;
        }
        $this->template->rander("items/view", $view_data);
    }
    function check_stock()
    {
        $options["allowed_members"] = $this->allowed_members;
        $list_data = $this->Items_model->get_details($options)->result();
        // var_dump($list_data);die();
        foreach ($list_data as $data) {
            $type = $data->unit_type ? $data->unit_type : "";
            $stock = $data->purchased_qty - $data->invoiced_qty + $data->ad_qty - $data->purchase_return_qty + $data->sale_return_qty;

            if ($data->item_type == 'service') {
                $purchase = "-";
            } else {
                $purchase = $data->purchased_qty ? $data->purchased_qty : 0;
            }
            if ($data->item_type == 'service') {
                $deliver = "-";
            } else {
                $deliver = $data->delivered_qty ? $data->delivered_qty : 0;
            }
            if ($data->item_type == 'service') {
                $shipment = "-";
            } else {
                $shipment = $data->shipment_qty ? $data->shipment_qty : 0;
            }
            if ($data->item_type == 'service') {
                $ad = "-";
            } else {
                $ad = $data->ad_qty ? $data->ad_qty : 0;
            }
            if ($data->item_type == 'service') {
                $stocks = "-";
            } else {
                $stocks = $stock ? $stock : 0;
            }
            // new 
            $stocks = $shipment - $deliver + $ad;

            // die();
            if ($stocks <= 3 && $data->item_type != 'service') {
                log_notification("the_inventory_item_is_running_low", array("to_user_id" => 1, "item_id" => $data->id), 0);
            }

            if ($this->db->dbprefix == 'tarteeb_v3') {
                //check max stock
                if ($data->max_stock && $data->item_type != 'service') {
                    if ($stocks >= $data->max_stock || $stocks >= $data->notify_max_stock_on) {
                        log_notification("the_inventory_item_is_reaching_max_stock", array("to_user_id" => 1, "item_id" => $data->id), 0);
                    }
                }
            }
        }
    }

    function download_sample_excel_file()
    {
        $this->access_allowed_members();
        download_app_files(get_setting("system_file_path"), serialize(array(array("file_name" => "import-items-sample.xlsx"))));
    }

    private function _get_allowed_headers()
    {

        return array(
            "title",
            "description",
            "unit_type",
            "rate",
            "cost",
            "category",
            "type",
        );
    }

    private function _get_category_id($category)
    {
        if ($category) {

            $category_id = "";
            $existing_category = $this->Item_categories_model->get_one_where(array("title" => $category, "deleted" => 0));
            if ($existing_category->id) {
                //client group exists, add the group id
                $category_id = $existing_category->id;
            } else {
                //client group doesn't exists, create a new one and add group id
                $data = array("title" => $category);
                $category_id = $this->Item_categories_model->save($data);
            }


            if ($category_id) {
                return $category_id;
            }
        }
    }

    private function _get_item_type($item_type)
    {
        if ($item_type) {

            if ($item_type == "service") {
                return $item_type;
            } else {
                return "product";
            }
        }
    }

    private function _store_headers_position($headers_row = array())
    {
        $allowed_headers = $this->_get_allowed_headers();

        //check if all headers are correct and on the right position
        $final_headers = array();
        foreach ($headers_row as $key => $header) {
            $key_value = str_replace(' ', '_', strtolower($header));
            $header_on_this_position = get_array_value($allowed_headers, $key);
            $header_array = array("key_value" => $header_on_this_position, "value" => $header);

            if ($header_on_this_position == $key_value) { //allowed header
                array_push($final_headers, $header_array);
            } else { //invalid header, flag as red
                $header_array["has_error"] = true;
                array_push($final_headers, $header_array);
            }
        }

        return $final_headers;
    }

    private function _row_data_validation_and_get_error_message($key, $data, $has_contact_first_name)
    {
        $allowed_headers = $this->_get_allowed_headers();
        $header_value = get_array_value($allowed_headers, $key);

        //company name field is required
        if ($header_value == "title" && !$data) {
            return lang("field_required");
        }
    }
}

/* End of file items.php */
/* Location: ./application/controllers/items.php */