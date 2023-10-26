<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Material_request extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->permission_checker("can_access_purchase_orders");
        $this->access_allowed_members();
        $this->load->model('Material_request_model');
    }

    private function can_edit() {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_edit_purchase_orders") == "1") {
                return true;
            }
        }
    }

    private function can_delete() {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_delete_purchase_orders") == "1") {
                return true;
            }
        }
    }

    private function can_approve() {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "purchase_orders") == "all") {
                return true;
            }
        }
    }

    private function can_add_payment() {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_create_purchase_order_payments") == "1") {
                return true;
            }
        }
    }

    /* load purchase_order list view */

    function index() {

        if ($this->login_user->user_type === "staff") {

            $view_data["currencies_dropdown"] = $this->_get_currencies_dropdown();
            if (!$this->login_user->is_admin) {
                $view_data['can_create_module'] = get_array_value($this->login_user->permissions,'can_create_purchase_orders');
            } else {
                $view_data['can_create_module'] = 1; 
            }
            $view_data['can_add_payment'] = $this->can_add_payment();

            $this->template->rander("material_request/index", $view_data);
        } 
    }

    //load the yearly view of purchase_order list 
    function yearly() {
        $this->load->view("material_request/yearly_material_request");
    }

    /* load new purchase_order modal */

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric",
            "supplier_id" => "numeric",
            "project_id" => "numeric"
        ));

        $supplier_id = $this->input->post('supplier_id');
        $project_id = $this->input->post('project_id');
        $model_info = $this->Material_request_model->get_one($this->input->post('id'));

        $view_data['model_info'] = $model_info;

        //make the drodown lists
        $view_data['suppliers_dropdown'] = array("" => "-") + $this->Suppliers_model->get_dropdown_list(array("company_name"), "id");
        $view_data['projects_dropdown'] = array("" => "-") + $this->Projects_model->get_dropdown_list(array("title"), "id");

        $view_data['supplier_id'] = $supplier_id;
        $view_data['project_id'] = $project_id;


        //prepare label suggestions
        $labels = explode(",", $this->Material_request_model->get_label_suggestions());
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

        $this->load->view('material_request/modal_form', $view_data);
    }

    /* add or edit an purchase_order */

    function _check_purchase_type(){
        $type = $this->input->post('purchase_type');
        if($type != 0 && $type != 1){
            $this->form_validation->set_message('_check_purchase_type','Please enter a correct value!');
            return false;
            
        }else{
            return true;
        }
    }
    function _check_asset_type(){
        $type = $this->input->post('asset_type');
        if($type != 0 && $type != 1){
            $this->form_validation->set_message('_check_asset_type','Please enter a correct value!');
            return false;
            
        }else{
            return true;
        }
    }

    function save() {
        //TODO: for test accout only remove else block if changes in database ready for all clients
        if ($this->login_user->is_admin && ($this->db->dbprefix === 'Test_teamway' || $this->db->dbprefix === 'Tarteeb' )){
            validate_submitted_data(array(
                "id" => "numeric",
                "purchase_order_supplier_id" => "required|numeric",
                "material_request_date" => "required",
                "purchase_type" => "required|callback__check_purchase_type",
                "taxable_value_in_FC" => "numeric",
                "exchange_rate_at_PO_time" => "numeric",
                "asset_type" => "required|callback__check_asset_type"
            ));
        }else{
            //TOD: for other users, remove this block if chnages done
            validate_submitted_data(array(
                "id" => "numeric",
                // "purchase_order_supplier_id" => "required|numeric",
                "material_request_date" => "required",
            ));
        }
       

        $supplier_id = $this->input->post('purchase_order_supplier_id');
        $id = $this->input->post('id');

        $material_request_date = $this->input->post('material_request_date');

        //TODO: for test accout only remove else block if changes in database ready for all clients
        if ($this->login_user->is_admin && ($this->db->dbprefix === 'Test_teamway' || $this->db->dbprefix === 'Tarteeb' ))
        {
        
        $purchase_type = $this->input->post('purchase_type') == 0? "domestic":"import";
        $asset_type = $this->input->post('asset_type') == 0? "direct":"indirect";

        $purchase_order_data = array(
            "supplier_id" => $supplier_id,
            "project_id" => $this->input->post('purchase_order_project_id') ? $this->input->post('purchase_order_project_id') : 0,
            "material_request_date" => $material_request_date,
            "note" => $this->input->post('purchase_order_note'),
            "labels" => $this->input->post('labels'),
            
            "type" => $purchase_type,
            "invoice_ref_number" => $this->input->post('invoice_ref_number'),
            "taxable_value_in_FC" => $this->input->post('taxable_value_in_FC'),
            "exchange_rate_at_PO_time" => $this->input->post('exchange_rate_at_PO_time'),
            "asset_type" => $asset_type
        );
        }else{
        //TOOD: for other users, remove this block if chnages done
        $purchase_order_data = array(
            "supplier_id" => $supplier_id,
            "project_id" => $this->input->post('purchase_order_project_id') ? $this->input->post('purchase_order_project_id') : 0,
            "material_request_date" => $material_request_date,
            "note" => $this->input->post('purchase_order_note'),
            "labels" => $this->input->post('labels'),
        );
        }


        $material_request_id = $this->Material_request_model->save($purchase_order_data, $id);
        if($material_request_id){
            echo json_encode(array("success" => true, "data" => $this->_row_data($material_request_id), 'id' => $material_request_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }

    }

    /* delete or undo an purchase_order */

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Material_request_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Material_request_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of purchase_orders, prepared for datatable  */

    function list_data() {

        $options = array(
            "status" => $this->input->post("status"),
            "start_date" => $this->input->post("start_date"),
            "end_date" => $this->input->post("end_date"),
            "currency" => $this->input->post("currency"),
        );

        $this->permission_checker("purchase_order_manage_permission");
        if(!$this->login_user->is_admin || !$this->access_type === "all"){
            $options["allowed_members"] = $this->allowed_members;
        }

        $list_data = $this->Material_request_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }

        echo json_encode(array("data" => $result));
    }

    /* list of purchase_order of a specific project, prepared for datatable  */

    function material_request_list_data_of_project($project_id) {
        $options = array(
            "project_id" => $project_id,
            "status" => $this->input->post("status"),
        );
        $list_data = $this->Material_request_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }


    /* return a row of purchase_order list table */

    private function _row_data($id) {

        $options = array("id" => $id);
        $data = $this->Material_request_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    /* prepare a row of purchase_order list table */

    private function _make_row($data) {
        $purchase_order_url = "";
        if ($this->login_user->user_type == "staff") {
            $purchase_order_url = anchor(get_uri("material_request/view/" . $data->id), get_material_request_id($data->id));
        }

        $purchase_order_labels = "";
        if ($data->labels) {
            $labels = explode(",", $data->labels);
            foreach ($labels as $label) {
                $purchase_order_labels .= "<span class='mt0 label label-info large ml10 clickable'  title='$label'>" . $label . "</span>";
            }
        }

        $row_data = array($purchase_order_url,
            anchor(get_uri("suppliers/view/" . $data->supplier_id), $data->company_name),
            $data->project_title ? anchor(get_uri("projects/view/" . $data->project_id), $data->project_title) : "-",
            $data->material_request_date,
            format_to_date($data->material_request_date, false),
            to_currency($data->material_request_value + $data->tax_value, $data->currency_symbol),
            to_currency($data->payment_received, $data->currency_symbol),
            to_currency($data->tax_value, $data->currency_symbol),
            $this->_get_shipment_status_label($data).$this->_get_material_request_status_label($data). $this->_get_approval_status_label($data). $purchase_order_labels
        );

        $row_data[] = $this->_make_options_dropdown($data->id);

        return $row_data;
    }

    //prepare options dropdown for purchase_orders list
    private function _make_options_dropdown($material_request_id = 0) {
        $status = $this->Material_request_model->get_one($material_request_id)->approval_status;
        $edit = "";
        $delete = "";
        $add_payment = "";
        if($this->can_edit() && $status == "not_approved") {
        $edit = '<li role="presentation">' . modal_anchor(get_uri("material_request/modal_form"), "<i class='fa fa-pencil'></i> " . lang('edit'), array("title" => lang('edit_purchase_order'), "data-post-id" => $material_request_id)) . '</li>';}

        if($this->can_delete() && $status == "not_approved") {
        $delete = '<li role="presentation">' . js_anchor("<i class='fa fa-times fa-fw'></i>" . lang('delete'), array('title' => lang('delete_purchase_order'), "class" => "delete", "data-id" => $material_request_id, "data-action-url" => get_uri("material_request/delete"), "data-action" => "delete")) . '</li>';}

        // if($this->can_add_payment()) {
        // $add_payment = '<li role="presentation">' . modal_anchor(get_uri("purchase_order_payments/payment_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_payment'), array("title" => lang('add_payment'), "data-post-material_request_id" => $material_request_id)) . '</li>';}


        return '
                <span class="dropdown inline-block">
                    <button class="btn btn-default dropdown-toggle  mt0 mb0" type="button" data-toggle="dropdown" aria-expanded="true">
                        <i class="fa fa-cogs"></i>&nbsp;
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu pull-right" role="menu">' . $edit . $delete . $add_payment . '</ul>
                </span>';
    }

    //prepare purchase_order status label 
    private function _get_material_request_status_label($data, $return_html = true) {
        return get_material_request_status_label($data, $return_html);
    }

    //prepare purchase_order status label 
    private function _get_approval_status_label($data, $return_html = true) {
        return get_purchase_order_approval_status_label($data, $return_html);
    }

    //prepare purchase_order status label 
    private function _get_shipment_status_label($data, $return_html = true) {
        return get_shipment_status_label($data, $return_html);
    }

    /* load purchase_order details view */

    function view($material_request_id = 0) {

        if ($material_request_id) {
            $view_data = get_material_request_making_data($material_request_id);

            if ($view_data) {
                $view_data['purchase_order_status'] = $this->_get_material_request_status_label($view_data["purchase_order_info"], false);
                $view_data['approval_status'] = $this->_get_approval_status_label($view_data["purchase_order_info"], false);
                $view_data['approval_status_label'] = $this->_get_approval_status_label($view_data["purchase_order_info"]);

                $view_data['shipment_status'] = $this->_get_shipment_status_label($view_data["purchase_order_info"], false);
                $view_data['shipment_status_label'] = $this->_get_shipment_status_label($view_data["purchase_order_info"]);

                $view_data["can_approve"] = $this->can_approve();
                $view_data["can_add_payment"] = $this->can_add_payment();

                $this->template->rander("material_request/view", $view_data);
            } else {
                show_404();
            }
        }
    }

    /* purchase_order total section */

    private function _get_purchase_order_total_view($material_request_id = 0) {
        $view_data["purchase_order_total_summary"] = $this->Material_request_model->get_purchase_order_total_summary($material_request_id);
        $view_data["material_request_id"] = $material_request_id;
        $view_data["approval_status"] = $this->Material_request_model->get_one($material_request_id)->approval_status;
        $purchase_order_info = $this->Material_request_model->get_details(array("id"=>$material_request_id))->row();
        $view_data["shipment_status"] = $this->_get_shipment_status_label($purchase_order_info, false);
        return $this->load->view('material_request/purchase_order_total_section', $view_data, true);
    }

    /* load item modal */

    function item_modal_form() {

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $material_request_id = $this->input->post('material_request_id');
        $view_data['model_info'] = $this->Material_request_items_model->get_one($this->input->post('id'));
        if (!$material_request_id) {
            $material_request_id = $view_data['model_info']->material_request_id;
        }

        $view_data['categories_dropdown'] = array("" => "-") + $this->Item_categories_model->get_dropdown_list(array("title"));
        $view_data['types_dropdown'] = array("" => "-", 'product'=>lang('product'));
        $view_data['taxes_dropdown'] = array("" => "-") + $this->Taxes_model->get_dropdown_list(array("title"));
        
        $view_data['material_request_id'] = $material_request_id;
        $this->load->view('material_request/item_modal_form', $view_data);
    }

    /* add or edit an purchase_order item */

    function save_item() {

        validate_submitted_data(array(
            "id" => "numeric",
            "material_request_id" => "required|numeric"
        ));

        $material_request_id = $this->input->post('material_request_id');

        $id = $this->input->post('id');
        $cost = unformat_currency($this->input->post('purchase_order_item_cost'));
        $quantity = unformat_currency($this->input->post('purchase_order_item_quantity'));

        $purchase_order_item_data = array(
            "material_request_id" => $material_request_id,
            "item_id" => $this->input->post('purchase_order_item_id'),
            "title" => $this->input->post('purchase_order_item_title'),
            "description" => $this->input->post('purchase_order_item_description'),
            "quantity" => $quantity,
            // "unit_type" => $this->input->post('purchase_order_unit_type'),
            // "rate" => unformat_currency($this->input->post('purchase_order_item_rate')),
            // "total" =>floatval($this->input->post('purchase_order_item_rate'))*intval($quantity),
            
        );

        $purchase_order_item_id = $this->Material_request_items_model->save($purchase_order_item_data, $id);
        if ($purchase_order_item_id) {

            //check if the add_new_item flag is on, if so, add the item to libary. 
            $add_new_item_to_library = $this->input->post('add_new_item_to_library');
            if ($add_new_item_to_library) {
                $library_item_data = array(
                    "title" => $this->input->post('purchase_order_item_title'),
                    "description" => $this->input->post('purchase_order_item_description'),
                    "unit_type" => $this->input->post('purchase_order_unit_type'),
                    "rate" => unformat_currency($this->input->post('purchase_order_item_rate')),
                    "cost" => unformat_currency($this->input->post('purchase_order_item_cost')),
                    "category_id" => $this->input->post('category_id'),
                    "item_type" => $this->input->post('item_type')
                );
                $this->Items_model->save($library_item_data);
            }

            $options = array("id" => $purchase_order_item_id);
            $item_info = $this->Material_request_items_model->get_details($options)->row();
            echo json_encode(array("success" => true, "material_request_id" => $item_info->material_request_id, "data" => $this->_make_item_row($item_info), 'id' => $purchase_order_item_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    /* load multiple items modal */

    function items_modal_form() {
        //$this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $material_request_id = $this->input->post('material_request_id');
        
        $view_data['material_request_id'] = $material_request_id;


        $items = $this->Items_model->get_all_where(array("deleted" => 0, "item_type" => "product"))->result();
        $items_dropdown = array();

        foreach ($items as $item) {
            $items_dropdown[] = array("id" => $item->id, "text" => $item->title);
        }

        $view_data['items_dropdown'] = json_encode($items_dropdown);

        $this->load->view('material_request/items_modal_form', $view_data);
    }

    /* add or edit multiple purchase_order item */

    function save_items() {
        //$this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric",
            "material_request_id" => "required|numeric"
        ));

        $material_request_id = $this->input->post('material_request_id');

        $items = explode(',', $this->input->post('items'));

        foreach ($items as $item) {
            $item_data = $this->Items_model->get_one($item);

            $purchase_order_item_data = array(
                "material_request_id" => $material_request_id,
                "item_id" => $item, 
                "title" => $item_data->title,
                "description" => $item_data->description,
                "quantity" => '1',
                "unit_type" => $item_data->unit_type,
                "rate" => unformat_currency($item_data->cost),
                "total" => $item_data->cost * 1,
            );

            $purchase_order_item_id = $this->Material_request_items_model->save($purchase_order_item_data);
        }

        if($purchase_order_item_id) {
            echo json_encode(array("success" => true, "data" => '', 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    /* delete or undo an purchase_order item */

    function delete_item() {

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Material_request_items_model->delete($id, true)) {
                $options = array("id" => $id);
                $item_info = $this->Material_request_items_model->get_details($options)->row();
                echo json_encode(array("success" => true, "material_request_id" => $item_info->material_request_id, "data" => $this->_make_item_row($item_info), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Material_request_items_model->delete($id)) {
                $item_info = $this->Material_request_items_model->get_one($id);
                echo json_encode(array("success" => true, "material_request_id" => $item_info->material_request_id, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of purchase_order items, prepared for datatable  */

    function item_list_data($material_request_id = 0) {

        $list_data = $this->Material_request_items_model->get_details(array("material_request_id" => $material_request_id))->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_item_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    /* prepare a row of purchase_order item list table */

    private function _make_item_row($data) {
        $item = "<div class='item-row strong mb5' data-id='$data->id'><i class='fa fa-bars pull-left move-icon'></i> $data->title</div>";
        if ($data->description) {
            $item .= "<span style='margin-left:25px'>" . nl2br($data->description) . "</span>";
        }
        $type = $data->unit_type ? $data->unit_type : "";

        $data->tax_percentage = !empty($data->tax_percentage) ? $data->tax_percentage : 0;
        $data->tax_percentage2 = !empty($data->tax_percentage2) ? $data->tax_percentage2 : 0;

        $tax = $data->total*$data->tax_percentage*0.01 + $data->total*$data->tax_percentage2*0.01;

        $do_item = "";
        $do_quantity =  0;
        $do_type = "";
        $do_qty = $this->Shipment_items_model->get_delivered_items($data->id);
        $do_type = isset($data->unit_type) ? ($data->unit_type ? $data->unit_type : "") : "";
        $do_quantity =  isset($do_qty) ? $do_qty : 0;

        return array(
            $data->sort,
            $item,
            to_decimal_format($data->quantity) . " " . $type,
            to_decimal_format($do_quantity) . " " . $do_type,
            to_currency($data->rate, $data->currency_symbol),
            to_currency($tax, $data->currency_symbol),
            to_currency($data->total + $tax, $data->currency_symbol),
            modal_anchor(get_uri("material_request/item_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_purchase_order'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("material_request/delete_item"), "data-action" => "delete"))
            . js_anchor("<i class='fa fa-plus fa-fw'></i>", array('title' => lang('add'), "class" => "edit", "data-id" => $data->id, "data-qty" => $data->quantity,"data-action-url" => get_uri("material_request/change_item_qty/plus"), "data-action" => "qty"))
            . js_anchor("<i class='fa fa-minus fa-fw'></i>", array('title' => lang('substract'), "class" => "delete", "data-id" => $data->id, "data-qty" => $data->quantity, "data-action-url" => get_uri("material_request/change_item_qty/minus"), "data-action" => "qty"))
        );
    }

    /* add and minus qty */
    function change_item_qty($operation = 'plus') {
        //$this->access_only_allowed_members();
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        $id = $this->input->post('id');
        $options = array("id" => $id);
        $item_info = $this->Material_request_items_model->get_details($options)->row();

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

        if ($this->Material_request_items_model->save($data, $id)) {
            $item_info = $this->Material_request_items_model->get_one($id);
            echo json_encode(array("success" => true, "quantity" => $quantity,  'message' => lang('record_saved')));
        }
    }

    //update the sort value for the item
    function update_item_sort_values($id = 0) {

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
                $this->Material_request_items_model->save($data, $id);
            }
        }
    }

    /* prepare suggestion of purchase_order item */

    function get_purchase_order_item_suggestion() {
        $key = $_REQUEST["q"];
        $suggestion = array();

        $items = $this->Material_request_items_model->get_item_suggestion($key);

        foreach ($items as $item) {
            $suggestion[] = array("id" => $item->title, "text" => $item->title);
        }

        $suggestion[] = array("id" => "+", "text" => "+ " . lang("create_new_item"));

        echo json_encode($suggestion);
    }

    function get_purchase_order_item_info_suggestion() {
        $item = $this->Material_request_items_model->get_item_info_suggestion($this->input->post("item_name"));
        if ($item) {
            echo json_encode(array("success" => true, "item_info" => $item));
        } else {
            echo json_encode(array("success" => false));
        }
    }

    //view html is accessable to supplier only.
    function preview($material_request_id = 0, $show_close_preview = false) {
        if ($material_request_id) {
            $view_data = get_material_request_making_data($material_request_id);

            $this->_check_purchase_order_access_permission($view_data);

            $view_data['purchase_order_preview'] = prepare_material_request_pdf($view_data, "html");

            //show a back button
            $view_data['show_close_preview'] = $show_close_preview && $this->login_user->user_type === "staff" ? true : false;

            $view_data['material_request_id'] = $material_request_id;
            $view_data['payment_methods'] = $this->Payment_methods_model->get_available_online_payment_methods();

            $this->template->rander("material_request/purchase_order_preview", $view_data);
        } else {
            show_404();
        }
    }

    //print purchase_order
    function print_purchase_order($material_request_id = 0) {
        if ($material_request_id) {
            $view_data = get_material_request_making_data($material_request_id);

            $this->_check_purchase_order_access_permission($view_data);
            // var_dump($view_data);die();

            $view_data['purchase_order_preview'] = prepare_material_request_pdf($view_data, "html");

            echo json_encode(array("success" => true, "print_view" => $this->load->view("material_request/print_purchase_order", $view_data, true)));
        } else {
            echo json_encode(array("success" => false, lang('error_occurred')));
        }
    }

    function download_pdf($material_request_id = 0) {

        if ($material_request_id) {
            $purchase_order_data = get_material_request_making_data($material_request_id);
            $this->_check_purchase_order_access_permission($purchase_order_data);

            prepare_material_request_pdf($purchase_order_data, "download");
        } else {
            show_404();
        }
    }

    private function _check_purchase_order_access_permission($purchase_order_data) {
        //check for valid purchase_order
        if (!$purchase_order_data) {
            show_404();
        }

        //check for security
        $purchase_order_info = get_array_value($purchase_order_data, "purchase_order_info");
    }


    function get_purchase_order_status_bar($material_request_id = 0) {

        $view_data["purchase_order_info"] = $this->Material_request_model->get_details(array("id" => $material_request_id))->row();
        $view_data['purchase_order_status_label'] = $this->_get_material_request_status_label($view_data["purchase_order_info"]);
        $view_data['approval_status_label'] = $this->_get_approval_status_label($view_data["purchase_order_info"]);

        $view_data['shipment_status_label'] = $this->_get_shipment_status_label($view_data["purchase_order_info"]);
        $this->load->view('material_request/purchase_order_status_bar', $view_data);
    }

    function update_purchase_order_status($material_request_id = 0, $status = "") {

        if ($material_request_id && $status) {
            //change the draft status of the purchase_order
            $this->Material_request_model->update_purchase_order_status($material_request_id, $status);

            echo json_encode(array("success" => true, 'message' => lang('record_saved')));
        }

        return "";
    }

    function update_approval_status($material_request_id = 0, $status = "") {
        //$this->access_only_allowed_members();

        if ($material_request_id && $status) {
            //change the draft status of the invoice
            
            $total_summary = $this->Material_request_model->get_purchase_order_total_summary($material_request_id);
            if (($status == "approved" || $status == "request_approval") && ($total_summary->balance_due < 0)) {
                echo json_encode(array("success" => false, 'message' => ('balance_due_can_not_be_a_negative_value')));
            } else {
               $purchase_order_status = $this->Material_request_model->update_approval_status($material_request_id, $status);
               if ($purchase_order_status) {
                $info = $this->Material_request_model->get_details(array("id"=>$purchase_order_status))->row();
                            if ($status == "approved") {
                                // $this->make_po_entries($info);
                                //$this->make_prepayment_to_payable($info);
                                $this->pushes($info);
                            }
                    echo json_encode(array("success" => true, 'message' => lang('record_saved'))); 
                } 
            }
               
        }

        return "";
    }

    function make_po_entries($info) {
        $date = $info->material_request_date;
        $type = "Purchase Order: ". get_material_request_id($info->id);
        
        $acc_array = array();
        $narration = "Purchase Order: ". get_material_request_id($info->id);

        $total_summary = $this->Material_request_model->get_purchase_order_total_summary($info->id);

        $supplier_info = $this->Suppliers_model->get_one($info->supplier_id);

        $acc_array[] = array("account_id" => get_setting('default_inventory'), "type" => 'dr',"amount" => $total_summary->purchase_order_subtotal, "narration" => $narration);

        $acc_array[] = array("account_id" => get_setting('VAT_out'), "type" => 'dr',"amount" => $total_summary->tax, "narration" => $narration);

        $acc_array[] = array("account_id" => $supplier_info->account_id, "type" => 'cr',"amount" => $total_summary->purchase_order_total, "narration" => $narration);

        $transaction_id = make_transaction($date, $acc_array, $type);
    }

    function pushes($info) {   
        $notifationTo = $info->log_user_id;
        $po = get_material_request_id($info->id);
        $body = $po.' you created has been approved';
        send_onesignal($body, '#', $notifationTo, '', '');
    }

    function make_prepayment_to_payable($info) {
       $payments = $this->Purchase_order_payments_model->get_all_where(array("material_request_id"=> $info->id,"status"=>"approved","deleted"=>0))->result();
       foreach ($payments as $payment) {

            $date = $payment->payment_date;
            $type = "Automatic Prepayment to Supplier Account: ". get_material_request_id($payment->material_request_id);

            $supplier_info = $this->Suppliers_model->get_one($info->supplier_id);

            $acc_array = array();
            $narration = $payment->note;

            $acc_array[] = array("account_id" => $supplier_info->account_id, "type" => 'dr',"amount" => $payment->amount, "narration" => $narration);

            $acc_array[] = array("account_id" => $supplier_info->advance_account_id, "type" => 'cr',"amount" => $payment->amount, "narration" => $narration);

            $transaction_id = make_transaction($date, $acc_array, $type);

            if ($transaction_id) {
            $trans_data = array("prepayment_transaction_id" => $transaction_id);
            $this->Purchase_order_payments_model->save($trans_data, $payment->id);
        }

        }    
    }

}

/* End of file purchase_orders.php */
/* Location: ./application/controllers/purchase_orders.php */