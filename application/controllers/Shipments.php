<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Shipments extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->permission_checker("can_access_purchase_orders");
        $this->access_allowed_members();
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

    /* load invoice list view */

    function index() {

        if ($this->login_user->user_type === "staff") {
            //$this->access_only_allowed_members();

            $this->template->rander("shipments/index"/*, $view_data*/);
        }
    }

    /* load new invoice modal */

    function modal_form() {
        //$this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric",
            "purchase_order_id" => "numeric",
        ));

        $purchase_order_id = $this->input->post('purchase_order_id');
        $model_info = $this->Shipments_model->get_one($this->input->post('id'));

        if ($purchase_order_id) {
            $invoice_info = $this->Purchase_orders_model->get_one($purchase_order_id);
            $model_info->supplier_id = $invoice_info->supplier_id;
        }


        $view_data['model_info'] = $model_info;
        $view_data['purchase_order_id'] = $purchase_order_id;

        $this->load->view('shipments/modal_form', $view_data);
    }


    /* add or edit an shipment */
    function save() {
        //$this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric",
            "shipment_date" => "required",
        ));

        $id = $this->input->post('id');

        $shipment_date = $this->input->post('shipment_date');
        $purchase_order_id = $this->input->post('purchase_order_id');
        $supplier_id = $this->input->post('supplier_id');

        $shipment_data = array(
            "date" => $shipment_date,
            "purchase_order_id"=> $purchase_order_id,
            "invoice_date" => $this->input->post('invoice_date'),
            "invoice_number" => $this->input->post('invoice_number'),
            "delivery_note_number" => $this->input->post('delivery_note_number'),
            "delivery_note_date" => $this->input->post('delivery_note_date'),
            "supplier_id"=> $supplier_id,
            "note" => $this->input->post('shipment_note'),
        );

        $shipment_id = $this->Shipments_model->save($shipment_data, $id);

        if ($shipment_id) {
            $copy_items_from_invoice = $this->input->post("copy_items_from_invoice");
            if ($copy_items_from_invoice) {
                $invoice_items = $this->Purchase_order_items_model->get_details(array("purchase_order_id" => $copy_items_from_invoice))->result();
                foreach ($invoice_items as $data) {

                    $delivered_items = $this->Shipment_items_model->get_delivered_items($data->id);
                    if (isset($delivered_items)) {
                        $quantity = $data->quantity - $delivered_items;
                    } else {
                        $quantity = $data->quantity;
                    }

                    $invoice_item_data = array(
                        "shipment_id" => $shipment_id,
                        "item_id" => $data->item_id,
                        "po_item_id" => $data->id,
                        "quantity" => $quantity,/// - do items
                    );
                    $this->Shipment_items_model->save($invoice_item_data);
                }

            }
            echo json_encode(array("success" => true, "data" => $this->_row_data($shipment_id), 'id' => $shipment_id, 'message' => lang('record_saved')));
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
        $shipment_data = $this->Shipments_model->get_one($id); 
        if ($this->input->post('undo')) {
            if ($this->Shipments_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Shipments_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of Shipments, prepared for datatable  */

    function list_data() {
        //$this->access_only_allowed_members();

        $options = array(
            "status" => $this->input->post("status"),
            "start_date" => $this->input->post("start_date"),
            "end_date" => $this->input->post("end_date"),
        );

        /*$this->permission_checker("invoice_manage_permission");
        if(!$this->login_user->is_admin || !$this->access_type === "all"){
            $options["allowed_members"] = $this->allowed_members;
        }*/

        $list_data = $this->Shipments_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }

        echo json_encode(array("data" => $result));
    }


    /* return a row of invoice list table */

    private function _row_data($id) {

        $options = array();
        $data = $this->Shipments_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    //prepare  status label 
    private function _get_delivery_status_label($data, $return_html = true) {
        return get_do_status_label($data, $return_html);
    }

    /* prepare a row of invoice list table */

    private function _make_row($data) {
        
        $shipment_url = anchor(get_uri("shipments/view/" . $data->id), get_shipment_id($data->id));

        $invoice_url = anchor(get_uri("purchase_orders/view/" . $data->purchase_order_id), get_purchase_order_id($data->purchase_order_id));

        $row_data = array(
            $data->id,
            $shipment_url,
            $invoice_url, 
            anchor(get_uri("suppliers/view/" . $data->supplier_id), $data->company_name),
            format_to_date($data->date, false),
            $data->invoice_number,
            format_to_date($data->invoice_date, false),
            $data->delivery_note_number,
            format_to_date($data->delivery_note_date, false),
            
            $this->_get_delivery_status_label($data)
        );

        $rowe = "";

        if($this->can_edit() && $data->status == "draft") {
        $rowe .=  modal_anchor(get_uri("shipments/modal_form"), "<i class='fa fa-pencil'></i> ", array("title" => lang('edit'), "data-post-id" => $data->id));
        }
        if($this->can_delete() && $data->status == "draft") {
        $rowe .=  js_anchor("<i class='fa fa-times fa-fw'></i>" , array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("shipments/delete"), "data-action" => "delete"));
        }

        $row_data[] =  $rowe;

        return $row_data;
    }

    function update_approval_status($id = 0, $status = "") {
        //$this->access_only_allowed_members();

        if ($id && $status) {
            //change the draft status of the invoice
            $this->Shipments_model->update_approval_status($id, $status);
            if($status=="approved"){
                $invoice = $this->Shipments_model->get_one($id); 
                $invoice_id= $invoice->purchase_order_id;
                $info = $this->Purchase_orders_model->get_details(array("id"=>$invoice_id))->row();
                $this->make_inventory_entries($info);
            }
            echo json_encode(array("success" => true, 'message' => lang('record_saved')));
        }

        return "";
    }

    /* load invoice details view */

    function view($shipment_id = 0) {
        //$this->access_only_allowed_members();

        if ($shipment_id) {
            $view_data = get_shipment_making_data($shipment_id);

            if ($view_data) {
                $view_data['status'] = $this->_get_delivery_status_label($view_data["shipment_info"], false);
                $view_data['status_label'] = $this->_get_delivery_status_label($view_data["shipment_info"]);
                $view_data['can_approve'] = $this->can_approve();

                $this->template->rander("shipments/view", $view_data);
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

        $shipment_id = $this->input->post('shipment_id');

        $view_data['model_info'] = $this->Shipment_items_model->get_details(array("id" => $this->input->post('id')))->row();
        if (!$shipment_id) {
            $shipment_id = $view_data['model_info']->shipment_id;
        }
        $view_data['shipment_id'] = $shipment_id;

        $this->load->view('shipments/item_modal_form', $view_data);
    }


    /* add or edit an estimate item */

    function save_item() {
        $this->access_allowed_members();

        validate_submitted_data(array(
            "id" => "required|numeric",
            "shipment_id" => "required|numeric",
        ));

        $shipment_id = $this->input->post('shipment_id');

        $id = $this->input->post('id');
        $quantity = unformat_currency($this->input->post('quantity'));

        $shipment_item_data = array(
            "quantity" => $quantity,
        );

        $do_item = $this->Shipment_items_model->get_one($id);

        $option_s = array("id" => $do_item->po_item_id);
        $invoice_item_info = $this->Purchase_order_items_model->get_details($option_s)->row();

        $delivered_items = $this->Shipment_items_model->get_delivered_items($do_item->po_item_id);
        if (isset($delivered_items) && isset($invoice_item_info->quantity)) {
            $inv_qty = $invoice_item_info->quantity - $delivered_items + $do_item->quantity;
        } else {
            $inv_qty = isset($invoice_item_info->quantity) ? $invoice_item_info->quantity : 0 ;
        }

        if ($quantity > $inv_qty) {
            echo json_encode(array("success" => false, 'message' => ($quantity." Quantity is more than the invoice quantity ".$inv_qty)));
            exit();
        }

        $shipment_item_id = $this->Shipment_items_model->save($shipment_item_data, $id);
        if ($shipment_item_data) {

            $options = array("id" => $shipment_item_id);
            $item_info = $this->Shipment_items_model->get_details($options)->row();
            echo json_encode(array("success" => true, "shipment_id" => $item_info->shipment_id, "data" => $this->_make_item_row($item_info), 'id' => $shipment_item_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }


    /* list of invoice items, prepared for datatable  */

    function item_list_data($shipment_id = 0) {
        //$this->access_only_allowed_members();

        $list_data = $this->Shipment_items_model->get_details(array("shipment_id" => $shipment_id))->result();
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
            js_anchor("<i class='fa fa-plus fa-fw'></i>", array('title' => lang('add'), "class" => "edit", "data-id" => $data->id, "data-qty" => $data->quantity,"data-action-url" => get_uri("shipments/change_item_qty/plus"), "data-action" => "qty"))
            . js_anchor("<i class='fa fa-minus fa-fw'></i>", array('title' => lang('substract'), "class" => "delete", "data-id" => $data->id, "data-qty" => $data->quantity, "data-action-url" => get_uri("shipments/change_item_qty/minus"), "data-action" => "qty"))
            . modal_anchor(get_uri("shipments/item_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit'), "data-post-id" => $data->id))
            
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
            if ($this->Shipment_items_model->delete($id, true)) {
                $options = array("id" => $id);
                $item_info = $this->Shipment_items_model->get_details($options)->row();
                echo json_encode(array("success" => true, "shipment_id" => $item_info->shipment_id, "data" => $this->_make_item_row($item_info), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Shipment_items_model->delete($id)) {
                $item_info = $this->Shipment_items_model->get_one($id);
                echo json_encode(array("success" => true, "shipment_id" => $item_info->shipment_id, 'message' => lang('record_deleted')));
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
        $item_info = $this->Shipment_items_model->get_details($options)->row();

        $option_s = array("id" => $item_info->po_item_id);
        $invoice_item_info = $this->Purchase_order_items_model->get_details($option_s)->row();
        
        $delivered_items = $this->Shipment_items_model->get_delivered_items($item_info->po_item_id);
        if (isset($delivered_items)) {
            $inv_qty = $invoice_item_info->quantity - $delivered_items + $item_info->quantity;
        } else {
            $inv_qty = $invoice_item_info->quantity;
        }

        $qty = $item_info->quantity;
        if ($operation == 'plus') {
            if ($qty < $inv_qty) {
                $qty += 1;
            } else {
                echo json_encode(array("success" => true, 'message' => lang('quantity_can_not_be_increased_above_purchase_order_quantity')));
                exit();
            }
        } else {
            if ($qty > 0) {
                $qty -= 1;
            }
        }

        $data = array("quantity" => $qty);

        $type = $item_info->unit_type ? $item_info->unit_type : "";
        $quantity = to_decimal_format($qty) . " " . $type;

        if ($this->Shipment_items_model->save($data, $id)) {
            $item_info = $this->Shipment_items_model->get_one($id);
            echo json_encode(array("success" => true, "quantity" => $quantity, 'message' => lang('record_saved')));
        }
    }

    function get_delivery_status_bar($id = 0) {
        //$this->access_only_allowed_members();

        $view_data["shipment_info"] = $this->Shipments_model->get_details(array("id" => $id))->row();
        $view_data['delivery_status_label'] = $this->_get_delivery_status_label($view_data["shipment_info"]);
        $this->load->view('shipments/status_bar', $view_data);
    }


    //view html is accessable to client only.
    function preview($shipment_id = 0, $show_close_preview = false) {
        if ($shipment_id) {
            $view_data = get_shipment_making_data($shipment_id);

            $this->_check_shipment_access_permission($view_data);

            $view_data['shipment_preview'] = prepare_shipment_pdf($view_data, "html");

            //show a back button
            $view_data['show_close_preview'] = $show_close_preview && $this->login_user->user_type === "staff" ? true : false;

            $view_data['shipment_id'] = $shipment_id;

            $view_data['status'] = $this->_get_delivery_status_label($view_data["shipment_info"], false);

            $this->template->rander("shipments/preview", $view_data);
        } else {
            show_404();
        }
    }

    //print shipment
    function print_shipment($shipment_id = 0) {
        if ($shipment_id) {
            $view_data = get_shipment_making_data($shipment_id);

            $this->_check_shipment_access_permission($view_data);

            $view_data['shipment_preview'] = prepare_shipment_pdf($view_data, "html");

            echo json_encode(array("success" => true, "print_view" => $this->load->view("shipments/print", $view_data, true)));
        } else {
            echo json_encode(array("success" => false, lang('error_occurred')));
        }
    }

    function download_pdf($shipment_id = 0) {

        if ($shipment_id) {
            $shipment_data = get_shipment_making_data($shipment_id);
            $this->_check_shipment_access_permission($shipment_data);

            prepare_shipment_pdf($shipment_data, "download");
        } else {
            show_404();
        }
    }

    private function _check_shipment_access_permission($shipment_data) {
        //check for valid shipment
        if (!$shipment_data) {
            show_404();
        }

        //check for security
        $shipment_info = get_array_value($shipment_data, "shipment_info");
        
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
                $this->Shipment_items_model->save($data, $id);
            }
        }
    }


    function make_inventory_entries($info) {
        // $product_summary = $this->Invoices_model->get_service_and_products_total($info->id, 'product');
        // // var_dump($product_summary);die();
        // if (!empty($product_summary)) {   
            $date = $info->purchase_order_date;
            // var_dump($info);
            // die();
            $type = "Invoice Shipment : ". get_invoice_id($info->id);
            
            $acc_array = array();
            $narration = get_invoice_id($info->id);

            $total_items_cost = 0;
            $invoice_items = $this->Purchase_orders_model->get_invoice_products($info->id);
            // var_dump($invoice_items);die();
            foreach ($invoice_items as $invoice_item) {
                $total_items_cost += $invoice_item->total;
                // $item_info = $this->Purchase_orders_model->get_invoice_item_inventory($invoice_item->item_id);
               
                // if(isset($item_info->sum_total) && !empty($item_info->sum_qty)) {
                //     $item_avg_cost = $item_info->sum_total / $item_info->sum_qty;
                //     $total_items_cost += $item_avg_cost * $invoice_item->quantity;
                // }
            }

            $total_items_cost = number_format($total_items_cost, 2, ".", "");

            $acc_array[] = array("account_id" => get_setting('default_inventory'), "type" => 'dr',"amount" => $total_items_cost, "narration" => $narration); 

            $acc_array[] = array("account_id" => get_setting('default_git'), "type" => 'cr',"amount" => $total_items_cost, "narration" => $narration);
     
            $transaction_id = make_transaction($date, $acc_array, $type);
        // }
    }

}

/* End of file invoices.php */
/* Location: ./application/controllers/invoices.php */