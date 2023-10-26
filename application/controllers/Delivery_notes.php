<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Delivery_notes extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->permission_checker("can_access_delivery_notes");
        $this->access_allowed_members();
    }

    private function can_edit() {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_edit_delivery_notes") == "1") {
                return true;
            }
        }
    }

    private function can_delete() {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_delete_delivery_notes") == "1") {
                return true;
            }
        }
    }

    private function can_approve() {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "delivery_notes") == "all") {
                return true;
            }
        }
    }

    /* load invoice list view */

    function index() {
        $this->check_module_availability("module_invoice");

        if ($this->login_user->user_type === "staff") {
            //$this->access_only_allowed_members();

            $this->template->rander("delivery_notes/index"/*, $view_data*/);
        }
    }

    /* load new invoice modal */

    function modal_form() {
        //$this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric",
            "invoice_id" => "numeric",
        ));

        $invoice_id = $this->input->post('invoice_id');
        $model_info = $this->Delivery_notes_model->get_one($this->input->post('id'));

        if ($invoice_id) {
            $invoice_info = $this->Invoices_model->get_one($invoice_id);
            $model_info->client_id = $invoice_info->client_id;
            $model_info->project_id = $invoice_info->project_id;
        }


        $view_data['model_info'] = $model_info;
        $view_data['invoice_id'] = $invoice_id;

        $this->load->view('delivery_notes/modal_form', $view_data);
    }


    /* add or edit an delivery_note */
    function save() {
        //$this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric",
            "delivery_note_date" => "required",
        ));

        $id = $this->input->post('id');

        $delivery_note_date = $this->input->post('delivery_note_date');
        $invoice_id = $this->input->post('invoice_id');
        $client_id = $this->input->post('client_id');
        $project_id = $this->input->post('project_id');

        $delivery_note_data = array(
            "delivery_note_date" => $delivery_note_date,
            "invoice_id"=> $invoice_id,
            "client_id"=> $client_id,
            "project_id"=> $project_id,
            "note" => $this->input->post('delivery_note_note'),
        );

        $delivery_note_id = $this->Delivery_notes_model->save($delivery_note_data, $id);

        if ($delivery_note_id) {
            $copy_items_from_invoice = $this->input->post("copy_items_from_invoice");
            if ($copy_items_from_invoice) {
                $invoice_items = $this->Invoice_items_model->get_details(array("invoice_id" => $copy_items_from_invoice))->result();
                foreach ($invoice_items as $data) {

                    $delivered_items = $this->Delivery_note_items_model->get_delivered_items($data->id);
                    if (isset($delivered_items)) {
                        $quantity = $data->quantity - $delivered_items;
                    } else {
                        $quantity = $data->quantity;
                    }

                    $invoice_item_data = array(
                        "delivery_note_id" => $delivery_note_id,
                        "item_id" => $data->item_id,
                        "invoice_item_id" => $data->id,
                        "title" => $data->title ? $data->title : "",
                        "description" => $data->description ? $data->description : "",
                        "quantity" => $quantity,/// - do items
                        "unit_type" => $data->unit_type ? $data->unit_type : "",
                        "rate" => $data->rate ? $data->rate : 0,
                        "total" => $data->rate ? $data->rate*$quantity : 0,
                        "sort" => $data->sort ? $data->sort : 0
                    );
                    if($this->Delivery_note_items_model->save($invoice_item_data)){
                        // $info = $this->Invoices_model->get_details(array("id"=>$invoice_id))->row();
                        // $this->make_inventory_entries($info);
                    }
                }

                //change the invoice_delivery status to delivered
                // $this->Invoices_model->update_delivery_status($invoice_id, "delivered");

            }
            echo json_encode(array("success" => true, "data" => $this->_row_data($delivery_note_id), 'id' => $delivery_note_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    /* delete or undo an invoice */

    function delete() {
        //$this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        $delivery_note_data = $this->Delivery_notes_model->get_one($id); 
        if ($this->input->post('undo')) {
            if ($this->Delivery_notes_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Delivery_notes_model->delete($id)) {
                //change the invoice_delivery status to delivered
                //$this->Invoices_model->update_delivery_status($delivery_note_data->invoice_id, "not_delivered");
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of Delivery_notes, prepared for datatable  */

    function list_data() {
        //$this->access_only_allowed_members();

        $options = array(
            "status" => $this->input->post("status"),
            "start_date" => $this->input->post("start_date"),
            "end_date" => $this->input->post("end_date"),
        );

        $this->permission_checker("invoice_manage_permission");
        if(!$this->login_user->is_admin || !$this->access_type === "all"){
            $options["allowed_members"] = $this->allowed_members;
        }

        $list_data = $this->Delivery_notes_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }

        echo json_encode(array("data" => $result));
    }


    /* return a row of invoice list table */

    private function _row_data($id) {

        $options = array();
        $data = $this->Delivery_notes_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    //prepare  status label 
    private function _get_delivery_status_label($data, $return_html = true) {
        return get_do_status_label($data, $return_html);
    }

    /* prepare a row of invoice list table */

    private function _make_row($data) {
        
        $delivery_note_url = anchor(get_uri("delivery_notes/view/" . $data->id), get_delivery_note_id($data->id));

        $invoice_url = anchor(get_uri("invoices/view/" . $data->invoice_id), get_invoice_id($data->invoice_id));

        $row_data = array(
            $delivery_note_url,
            $invoice_url, 
            format_to_date($data->delivery_note_date, false),
            anchor(get_uri("clients/view/" . $data->client_id), $data->company_name),
            $data->project_title ? anchor(get_uri("projects/view/" . $data->project_id), $data->project_title) : "-",
            $this->_get_delivery_status_label($data)
        );

        $rowe = "";

        if($this->can_edit() && $data->status == "draft") {
        $rowe .=  modal_anchor(get_uri("delivery_notes/modal_form"), "<i class='fa fa-pencil'></i> ", array("title" => lang('edit'), "data-post-id" => $data->id));
        }
        if($this->can_delete() && $data->status == "draft") {
        $rowe .=  js_anchor("<i class='fa fa-times fa-fw'></i>" , array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("delivery_notes/delete"), "data-action" => "delete"));
        }

        $row_data[] =  $rowe;

        return $row_data;
    }

    function update_approval_status($id = 0, $status = "") {
        // die('hi');
        //$this->access_only_allowed_members();
        if ($id && $status) {
            //change the draft status of the invoice
            
            $invoice = $this->Delivery_notes_model->get_one($id); 
            $invoice_id= $invoice->invoice_id;
            if($status=="approved"){
             $info = $this->Invoices_model->get_details(array("id"=>$invoice_id))->row();
            
             $this->make_inventory_entries($info);
            //  var_dump($info);die();
             $this->Delivery_notes_model->update_approval_status($id, $status);
             $this->Invoices_model->update_delivery_status($invoice_id, "delivered");
            }else{
                $this->Delivery_notes_model->update_approval_status($id, $status);
            }

            echo json_encode(array("success" => true, 'message' => lang('record_saved')));
        }

        return "";
    }

    /* load invoice details view */

    function view($delivery_note_id = 0) {
        //$this->access_only_allowed_members();

        if ($delivery_note_id) {
            $view_data = get_delivery_note_making_data($delivery_note_id);

            if ($view_data) {
                $view_data['status'] = $this->_get_delivery_status_label($view_data["delivery_note_info"], false);
                $view_data['status_label'] = $this->_get_delivery_status_label($view_data["delivery_note_info"]);
                $view_data['can_approve'] = $this->can_approve();

                $this->template->rander("delivery_notes/view", $view_data);
            } else {
                show_404();
            }
        }
    }

         /* load item modal */

    function item_modal_form() {

        validate_submitted_data(array(
            "id" => "numeric|required"
        ));

        $delivery_note_id = $this->input->post('delivery_note_id');

        $view_data['model_info'] = $this->Delivery_note_items_model->get_details(array("id" => $this->input->post('id')))->row();
        if (!$delivery_note_id) {
            $delivery_note_id = $view_data['model_info']->delivery_note_id;
        }
        $view_data['delivery_note_id'] = $delivery_note_id;

        $this->load->view('delivery_notes/item_modal_form', $view_data);
    }


    /* add or edit an estimate item */

    function save_item() {
        $this->access_allowed_members();

        validate_submitted_data(array(
            "id" => "required|numeric",
            "delivery_note_id" => "required|numeric",
        ));

        $delivery_note_id = $this->input->post('delivery_note_id');

        $id = $this->input->post('id');
        $quantity = unformat_currency($this->input->post('quantity'));

        $delivery_note_item_data = array(
            "quantity" => $quantity,
        );

        $do_item = $this->Delivery_note_items_model->get_one($id);

        $option_s = array("id" => $do_item->invoice_item_id);
        $invoice_item_info = $this->Invoice_items_model->get_details($option_s)->row();

        $delivered_items = $this->Delivery_note_items_model->get_delivered_items($do_item->invoice_item_id);
        if (isset($delivered_items) && isset($invoice_item_info->quantity)) {
            $inv_qty = $invoice_item_info->quantity - $delivered_items + $do_item->quantity;
        } else {
            $inv_qty = isset($invoice_item_info->quantity) ? $invoice_item_info->quantity : 0 ;
        }

        if ($quantity > $inv_qty) {
            echo json_encode(array("success" => false, 'message' => ($quantity." Quantity is more than the invoice quantity ".$inv_qty)));
            exit();
        }

        $delivery_note_item_id = $this->Delivery_note_items_model->save($delivery_note_item_data, $id);
        if ($delivery_note_item_data) {

            $options = array("id" => $delivery_note_item_id);
            $item_info = $this->Delivery_note_items_model->get_details($options)->row();
            echo json_encode(array("success" => true, "delivery_note_id" => $item_info->delivery_note_id, "data" => $this->_make_item_row($item_info), 'id' => $delivery_note_item_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }


    /* list of invoice items, prepared for datatable  */

    function item_list_data($delivery_note_id = 0) {
        //$this->access_only_allowed_members();

        $list_data = $this->Delivery_note_items_model->get_details(array("delivery_note_id" => $delivery_note_id))->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_item_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    /* prepare a row of invoice item list table */

    private function _make_item_row($data) {
        $item = "<div class='item-row strong mb5' data-id='$data->id'><i class='fa fa-bars pull-left move-icon'></i> $data->title</div>";
        if ($data->description) {
            $item .= "<span style='margin-left:25px'>" . nl2br($data->description) . "</span>";
        }
        $type = $data->unit_type ? $data->unit_type : "";

        return array(
            $data->sort,
            $item,
            to_decimal_format($data->quantity) . " " . $type,
            js_anchor("<i class='fa fa-plus fa-fw'></i>", array('title' => lang('add'), "class" => "edit", "data-id" => $data->id, "data-qty" => $data->quantity,"data-action-url" => get_uri("delivery_notes/change_item_qty/plus"), "data-action" => "qty"))
            . js_anchor("<i class='fa fa-minus fa-fw'></i>", array('title' => lang('substract'), "class" => "delete", "data-id" => $data->id, "data-qty" => $data->quantity, "data-action-url" => get_uri("delivery_notes/change_item_qty/minus"), "data-action" => "qty"))
            . modal_anchor(get_uri("delivery_notes/item_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit'), "data-post-id" => $data->id))
            
        );
    }

    /* delete or undo an invoice item */

    function delete_item() {
        //$this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Delivery_note_items_model->delete($id, true)) {
                $options = array("id" => $id);
                $item_info = $this->Delivery_note_items_model->get_details($options)->row();
                echo json_encode(array("success" => true, "delivery_note_id" => $item_info->delivery_note_id, "data" => $this->_make_item_row($item_info), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Delivery_note_items_model->delete($id)) {
                $item_info = $this->Delivery_note_items_model->get_one($id);
                echo json_encode(array("success" => true, "delivery_note_id" => $item_info->delivery_note_id, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* add and minus qty */
    function change_item_qty($operation = 'plus') {
        ////$this->access_only_allowed_members();
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        $id = $this->input->post('id');

        $options = array("id" => $id);
        $item_info = $this->Delivery_note_items_model->get_details($options)->row();

        $option_s = array("id" => $item_info->invoice_item_id);
        $invoice_item_info = $this->Invoice_items_model->get_details($option_s)->row();
        
        $delivered_items = $this->Delivery_note_items_model->get_delivered_items($item_info->invoice_item_id);
        if (isset($delivered_items)) {
            $inv_qty = $invoice_item_info->quantity - $delivered_items + $item_info->quantity;
        } else {
            $inv_qty = $invoice_item_info->quantity;
        }

        $qty = $item_info->quantity;
        if ($operation == 'plus') {
            if ($qty < $inv_qty) {
                $qty += 1;
            }
        } else {
            if ($qty > 0) {
                $qty -= 1;
            }
        }

        $data = array("quantity" => $qty);

        $type = $item_info->unit_type ? $item_info->unit_type : "";
        $quantity = to_decimal_format($qty) . " " . $type;

        if ($this->Delivery_note_items_model->save($data, $id)) {
            $item_info = $this->Delivery_note_items_model->get_one($id);
            echo json_encode(array("success" => true, "quantity" => $quantity, 'message' => lang('record_saved')));
        }
    }

    function get_delivery_status_bar($id = 0) {
        //$this->access_only_allowed_members();

        $view_data["delivery_note_info"] = $this->Delivery_notes_model->get_details(array("id" => $id))->row();
        $view_data['delivery_status_label'] = $this->_get_delivery_status_label($view_data["delivery_note_info"]);
        $this->load->view('delivery_notes/delivery_note_status_bar', $view_data);
    }


    //view html is accessable to client only.
    function preview($delivery_note_id = 0, $show_close_preview = false) {
        if ($delivery_note_id) {
            $view_data = get_delivery_note_making_data($delivery_note_id);

            $this->_check_delivery_note_access_permission($view_data);

            $view_data['delivery_note_preview'] = prepare_delivery_note_pdf($view_data, "html");

            //show a back button
            $view_data['show_close_preview'] = $show_close_preview && $this->login_user->user_type === "staff" ? true : false;

            $view_data['delivery_note_id'] = $delivery_note_id;

            $view_data['status'] = $this->_get_delivery_status_label($view_data["delivery_note_info"], false);

            $this->template->rander("delivery_notes/delivery_note_preview", $view_data);
        } else {
            show_404();
        }
    }

    //print delivery_note
    function print_delivery_note($delivery_note_id = 0) {
        if ($delivery_note_id) {
            $view_data = get_delivery_note_making_data($delivery_note_id);

            $this->_check_delivery_note_access_permission($view_data);

            $view_data['delivery_note_preview'] = prepare_delivery_note_pdf($view_data, "html");

            echo json_encode(array("success" => true, "print_view" => $this->load->view("delivery_notes/print_delivery_note", $view_data, true)));
        } else {
            echo json_encode(array("success" => false, lang('error_occurred')));
        }
    }

    function download_pdf($delivery_note_id = 0) {

        if ($delivery_note_id) {
            $delivery_note_data = get_delivery_note_making_data($delivery_note_id);
            $this->_check_delivery_note_access_permission($delivery_note_data);

            prepare_delivery_note_pdf($delivery_note_data, "download");
        } else {
            show_404();
        }
    }

    private function _check_delivery_note_access_permission($delivery_note_data) {
        //check for valid delivery_note
        if (!$delivery_note_data) {
            show_404();
        }

        //check for security
        $delivery_note_info = get_array_value($delivery_note_data, "delivery_note_info");
        
        //$this->access_only_allowed_members();
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
                $this->Delivery_note_items_model->save($data, $id);
            }
        }
    }

    function make_inventory_entries($info) {
        $product_summary = $this->Invoices_model->get_service_and_products_total($info->id, 'product');
        // var_dump($product_summary);die();
        if (!empty($product_summary)) {   
            $date = $info->bill_date;
            $type = "Invoice Inventory: ". get_invoice_id($info->id);
            
            $acc_array = array();
            $narration = get_invoice_id($info->id);

            $total_items_cost = 0;
            $invoice_items = $this->Invoices_model->get_invoice_products($info->id);
            foreach ($invoice_items as $invoice_item) {
                $item_info = $this->Invoices_model->get_invoice_item_inventory($invoice_item->item_id);
                if(isset($item_info->sum_total) && !empty($item_info->sum_qty)) {
                    $item_avg_cost = $item_info->sum_total / $item_info->sum_qty;
                    $total_items_cost += $item_avg_cost * $invoice_item->quantity;
                }
            }

            $total_items_cost = number_format($total_items_cost, 2, ".", "");

            // $acc_array[] = array("account_id" => get_setting('cost_of_goods_sold'), "type" => 'dr',"amount" => $total_items_cost, "narration" => $narration); 
            $sale_cost_account_id=$this->Sale_items_model->get_one($info->sale_item)->sale_cost_account_id;
       
            $acc_array[] = array("account_id" => $sale_cost_account_id, "type" => 'dr',"amount" => $total_items_cost, "narration" => $narration); 

            $acc_array[] = array("account_id" => get_setting('default_inventory'), "type" => 'cr',"amount" => $total_items_cost, "narration" => $narration);
     
            $transaction_id = make_transaction($date, $acc_array, $type);
        }
    }



}           

/* End of file invoices.php */
/* Location: ./application/controllers/invoices.php */