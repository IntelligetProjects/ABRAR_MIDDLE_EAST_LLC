<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sale_returns extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->permission_checker("can_access_invoices_return");
        $this->access_allowed_members();
    }

    private function can_edit() {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_edit_invoices_return") == "1") {
                return true;
            }
        }
    }

    private function can_delete() {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_delete_invoices_return") == "1") {
                return true;
            }
        }
    }

    private function can_approve() {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "invoices") == "all") {
                return true;
            }
        }
    }

    /* load invoice list view */

    function index() {

        if ($this->login_user->user_type === "staff") {
            //$this->access_only_allowed_members();
            $this->p_access_check("can_access_invoices_return");
            $view_data['can_create'] =$this->p_check("can_create_invoices_return");
            $this->template->rander("sale_returns/index", $view_data);
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

    /* load new invoice modal */

    function modal_form() {
        //$this->access_only_allowed_members();
        // $this->p_access_check("can_edit_invoices_return");

        validate_submitted_data(array(
            "id" => "numeric",
            "invoice_id" => "numeric",
        ));

        $invoice_id = $this->input->post('invoice_id');
        $model_info = $this->Sale_returns_model->get_one($this->input->post('id'));

        if ($invoice_id) {
            $po_info = $this->Invoices_model->get_one($invoice_id);
            $model_info->client_id = $po_info->client_id;
        }

        $view_data['invoices_dropdown'] = array("" => "-") + $this->Invoices_model->get_dropdown_list(array("id"), "id", array("approval_status"=>"approved"));
        $view_data['model_info'] = $model_info;
        $view_data['invoice_id'] = $invoice_id;
        $view_data['payment_methods_dropdown'] = $this->Payment_methods_model->get_dropdown_list(array("title"), "id", array("online_payable" => 0, "deleted" => 0));

        $this->load->view('sale_returns/modal_form', $view_data);
    }


    /* add or edit an sale_return */
    function save() {
        //$this->access_only_allowed_members();
        if($this->p_check("can_create_invoices_return")!=true){
            echo json_encode(array("success" => false, 'message' => lang("You don't have permission to add this module.")));
            exit();
        }
        validate_submitted_data(array(
            "id" => "numeric",
            "sale_return_date" => "required",
            "invoice_id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        $sale_return_date = $this->input->post('sale_return_date');
        $invoice_id = $this->input->post('invoice_id');
        $po_info = $this->Invoices_model->get_one($invoice_id);
        $client_id = $po_info->client_id;

        $sale_return_data = array(
            "date" => $sale_return_date,
            "invoice_id"=> $invoice_id,
            "client_id"=> $client_id,
            "status" => "draft",
            "note" => $this->input->post('sale_return_note'),
            "payment_method_id" => $this->input->post('payment_method_id'),
        );

        $sale_return_id = $this->Sale_returns_model->save($sale_return_data, $id);

        if ($sale_return_id) {
            $copy_items_from_invoice = $this->input->post("copy_items_from_invoice");
            if ($copy_items_from_invoice) {
                $invoice_items = $this->Invoice_items_model->get_details(array("invoice_id" => $invoice_id))->result();
                foreach ($invoice_items as $data) {

                    $invoice_item_data = array(
                        "sale_return_id" => $sale_return_id,
                        "item_id" => $data->item_id,
                        "invoice_item_id" => $data->id,
                        "quantity" => $data->quantity,/// - do items
                        "sort" => $data->sort ? $data->sort : 0
                    );
                    $this->Sale_return_items_model->save($invoice_item_data);
                }

            }
            echo json_encode(array("success" => true, "data" => $this->_row_data($sale_return_id), 'id' => $sale_return_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    /* delete or undo an invoice */

    function delete() {
        //$this->access_only_allowed_members();
        $this->p_access_check("can_access_invoices_return");
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        $sale_return_data = $this->Sale_returns_model->get_one($id); 
        if ($this->input->post('undo')) {
            if ($this->Sale_returns_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Sale_returns_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of Sale_returns, prepared for datatable  */

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

        $list_data = $this->Sale_returns_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }

        echo json_encode(array("data" => $result));
    }
    function list_client_data($client_id) {
        $options = array(
            "client_id" => $client_id,
            "status" => $this->input->post("status"),
            "start_date" => $this->input->post("start_date"),
            "end_date" => $this->input->post("end_date"),
        );

        $list_data = $this->Sale_returns_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }

        echo json_encode(array("data" => $result));
    }


    /* return a row of invoice list table */

    private function _row_data($id) {

        $options = array();
        $data = $this->Sale_returns_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    //prepare  status label 
    private function _get_delivery_status_label($data, $return_html = true) {
        return get_do_status_label($data, $return_html);
    }

    /* prepare a row of invoice list table */

    private function _make_row($data) {
        
        $sale_return_url = anchor(get_uri("sale_returns/view/" . $data->id), get_sale_return_id($data->id));

        $invoice_url = anchor(get_uri("invoices/view/" . $data->invoice_id), get_invoice_id($data->invoice_id));

        // echo 'hiii'. $invoice_url; die();
        $total_summary = $this->Sale_returns_model->get_total_summary($data->id);

        $payment_method = $this->Payment_methods_model->get_one($data->payment_method_id);

        $row_data = array(
            $data->id,
            $sale_return_url,
            $invoice_url, 
            anchor(get_uri("clients/view/" . $data->client_id), $data->company_name),
            format_to_date($data->date, false),
            to_currency($total_summary->total),
            to_currency($total_summary->tax),
            isset($payment_method->title) ? $payment_method->title : "",
            $data->note,
            $this->_get_delivery_status_label($data)
        );

        $rowe = "";

        if($this->can_edit() && $data->status == "draft") {
        $rowe .=  modal_anchor(get_uri("sale_returns/modal_form"), "<i class='fa fa-pencil'></i> ", array("title" => lang('edit'), "data-post-id" => $data->id));
        }
        if($this->can_delete() && $data->status == "draft") {
        $rowe .=  js_anchor("<i class='fa fa-times fa-fw'></i>" , array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("sale_returns/delete"), "data-action" => "delete"));
        }

        $row_data[] =  $rowe;

        return $row_data;
    }

    function update_approval_status($id = 0, $status = "") {
        //$this->access_only_allowed_members();
        // echo 't'.get_setting('default_bank');die();
        if ($id && $status) {
            //change the draft status of the invoice
            // $this->Sale_returns_model->update_approval_status($id, $status);
            $info = $this->Sale_returns_model->get_details(array("id"=>$id))->row();
           
            if ($status == "approved") {
                $this->Sale_returns_model->update_approval_status($id, $status);
                $this->make_inv_entries($info);
                // $this->make_entries($info);
            }
            if ($status == "refund_approved") {
                $this->make_entries($info);
            }

            echo json_encode(array("success" => true, 'message' => lang('record_saved')));
        }
        return "";
    }


    function make_entries($info) {
        $date = $info->date;
        $type = "Sale Return Refund: ". get_sale_return_id($info->id);
        
        $acc_array = array();
        $narration = $type. " ". $info->note;

        $client_info = $this->Clients_model->get_one($info->client_id);

        $account_id = $client_info->account_id;

        $total_summary = $this->Sale_returns_model->get_total_summary($info->id);
        $amount = $total_summary->total;
// echo $info->payment_method_id ;die();
        if ($info->payment_method_id == '1') {
            $acc_array[] = array("account_id" => $account_id, "type" => 'dr',"amount" => $amount, "narration" => $narration);

            // $acc_array[] = array("account_id" => get_setting('default_cash_on_hand'), "type" => 'cr',"amount" => $amount, "narration" => $narration);
            $acc_array[] = array("account_id" =>  $info->treasury, "type" => 'cr',"amount" => $amount, "narration" => $narration);
            
        } else if ($info->payment_method_id == '4') {
            $acc_array[] = array("account_id" => $account_id, "type" => 'dr',"amount" => $amount, "narration" => $narration);

            // $acc_array[] = array("account_id" => get_setting('default_bank'), "type" => 'cr',"amount" => $amount, "narration" => $narration);
            $acc_array[] = array("account_id" =>  $info->bank, "type" => 'cr',"amount" => $amount, "narration" => $narration);
            
        } else if ($info->payment_method_id == '5') {
            $acc_array[] = array("account_id" => $account_id, "type" => 'dr',"amount" => $amount, "narration" => $narration);

            $acc_array[] = array("account_id" => get_setting('receivable_cheques'), "type" => 'cr',"amount" => $amount, "narration" => $narration);
            
        }

        //var_dump($acc_array);

        $transaction_id = make_transaction($date, $acc_array, $type);

    }

    function make_inv_entries($info) {
        $date = $info->date;
        $type = "Sale Return: ". get_sale_return_id($info->id);
        
        $acc_array = array();
        $narration = "Sale Return: ".get_sale_return_id($info->id);

        $total_summary = $this->Sale_returns_model->get_total_summary($info->id);

        $client_info = $this->Clients_model->get_one($info->client_id);

        $main_invoice = $this->Invoices_model->get_one($info->invoice_id);
        $sale_cost_account_id=$this->Sale_items_model->get_one($main_invoice->sale_item)->sale_account_id;
        // $acc_array[] = array("account_id" => get_setting('default_sales'), "type" => 'dr',"amount" => number_format($total_summary->purchase_order_subtotal, 3, ".", ""), "narration" => $narration); 
        $acc_array[] = array("account_id" => $sale_cost_account_id, "type" => 'dr',"amount" => number_format($total_summary->purchase_order_subtotal, 3, ".", ""), "narration" => $narration); 

        $acc_array[] = array("account_id" => get_setting('VAT_in'), "type" => 'dr',"amount" => number_format($total_summary->tax, 3, ".", ""), "narration" => $narration); 

        $acc_array[] = array("account_id" => $client_info->account_id, "type" => 'cr',"amount" => $total_summary->total, "narration" => $narration);

        $transaction_id = make_transaction($date, $acc_array, $type);
    }


    /* load invoice details view */

    function view($sale_return_id = 0) {
        //$this->access_only_allowed_members();

        if ($sale_return_id) {
            $view_data = get_sale_return_making_data($sale_return_id);

            if ($view_data) {
                $view_data['status'] = $this->_get_delivery_status_label($view_data["sale_return_info"], false);
                $view_data['status_label'] = $this->_get_delivery_status_label($view_data["sale_return_info"]);
                $view_data['can_approve'] = $this->can_approve();
                $view_data["total_summary"] = $this->Sale_returns_model->get_total_summary($sale_return_id);

                $this->template->rander("sale_returns/view", $view_data);
            } else {
                show_404();
            }
        }
    }

    private function _get_total_view($sale_return_id = 0) {
        $view_data["total_summary"] = $this->Sale_returns_model->get_total_summary($sale_return_id);
        $view_data["sale_return_id"] = $sale_return_id;
        $view_data["status"] = $this->Sale_returns_model->get_one($sale_return_id)->status;
        return $this->load->view('sale_returns/total_section', $view_data, true);
    }



    /* list of invoice items, prepared for datatable  */

    function item_list_data($sale_return_id = 0) {
        //$this->access_only_allowed_members();

        $list_data = $this->Sale_return_items_model->get_details(array("sale_return_id" => $sale_return_id))->result();
        
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

        $data->tax_percentage = !empty($data->tax_percentage) ? $data->tax_percentage : 0;
        $data->tax_percentage2 = !empty($data->tax_percentage2) ? $data->tax_percentage2 : 0;

        $tax = $data->quantity*$data->rate*$data->tax_percentage*0.01 + $data->quantity*$data->rate*$data->tax_percentage2*0.01;

        return array(
            $data->sort,
            $item,
            to_decimal_format($data->quantity) . " " . $type,
            to_currency($data->rate),
            to_currency($tax),
            to_currency(($data->rate*$data->quantity) + $tax),
            js_anchor("<i class='fa fa-plus fa-fw'></i>", array('title' => lang('add'), "class" => "edit", "data-id" => $data->id, "data-qty" => $data->quantity,"data-action-url" => get_uri("sale_returns/change_item_qty/plus"), "data-action" => "qty"))
            . js_anchor("<i class='fa fa-minus fa-fw'></i>", array('title' => lang('substract'), "class" => "delete", "data-id" => $data->id, "data-qty" => $data->quantity, "data-action-url" => get_uri("sale_returns/change_item_qty/minus"), "data-action" => "qty"))
            . modal_anchor(get_uri("sale_returns/item_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit'), "data-post-id" => $data->id))
            
        );
    }

             /* load item modal */

    function item_modal_form() {

        validate_submitted_data(array(
            "id" => "numeric|required"
        ));

        $sale_return_id = $this->input->post('sale_return_id');

        $view_data['model_info'] = $this->Sale_return_items_model->get_details(array("id" => $this->input->post('id')))->row();
        if (!$sale_return_id) {
            $sale_return_id = $view_data['model_info']->sale_return_id;
        }
        $view_data['sale_return_id'] = $sale_return_id;

        $this->load->view('sale_returns/item_modal_form', $view_data);
    }


    /* add or edit an estimate item */

    function save_item() {
        $this->access_allowed_members();

        validate_submitted_data(array(
            "id" => "required|numeric",
            "sale_return_id" => "required|numeric",
        ));

        $sale_return_id = $this->input->post('sale_return_id');

        $id = $this->input->post('id');
        $quantity = unformat_currency($this->input->post('quantity'));

        $sale_return_item_data = array(
            "quantity" => $quantity,
        );

        $do_item = $this->Sale_return_items_model->get_one($id);

        $option_s = array("id" => $do_item->invoice_item_id);
        $invoice_item_info = $this->Invoice_items_model->get_details($option_s)->row();

        $inv_qty = isset($invoice_item_info->quantity) ? $invoice_item_info->quantity : 0 ;

        if ($quantity > $inv_qty) {
            echo json_encode(array("success" => false, 'message' => ($quantity." Quantity is more than the invoice quantity ".$inv_qty)));
            exit();
        }

        $sale_return_item_id = $this->Sale_return_items_model->save($sale_return_item_data, $id);
        if ($sale_return_item_data) {

            $options = array("id" => $sale_return_item_id);
            $item_info = $this->Sale_return_items_model->get_details($options)->row();
            echo json_encode(array("success" => true, "sale_return_id" => $item_info->sale_return_id, "data" => $this->_make_item_row($item_info), "total_view" => $this->_get_total_view($item_info->sale_return_id), 'id' => $sale_return_item_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    /* delete or undo an invoice item */

    function delete_item() {
        //$this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Sale_return_items_model->delete($id, true)) {
                $options = array("id" => $id);
                $item_info = $this->Sale_return_items_model->get_details($options)->row();
                echo json_encode(array("success" => true, "sale_return_id" => $item_info->sale_return_id, "data" => $this->_make_item_row($item_info), "total_view" => $this->_get_total_view($item_info->sale_return_id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Sale_return_items_model->delete($id)) {
                $item_info = $this->Sale_return_items_model->get_one($id);
                echo json_encode(array("success" => true, "total_view" => $this->_get_total_view($item_info->sale_return_id), "sale_return_id" => $item_info->sale_return_id, 'message' => lang('record_deleted')));
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
        $item_info = $this->Sale_return_items_model->get_details($options)->row();

        $option_s = array("id" => $item_info->invoice_item_id);
        $invoice_item_info = $this->Invoice_items_model->get_details($option_s)->row();
        
        
        $inv_qty = $invoice_item_info->quantity;

        $qty = $item_info->quantity;
        if ($operation == 'plus') {
            if ($qty < $inv_qty) {
                $qty += 1;
            } else {
                echo json_encode(array("success" => true, 'message' => lang('quantity_can_not_be_increased_above_invoice_quantity')));
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

        if ($this->Sale_return_items_model->save($data, $id)) {
            $item_info = $this->Sale_return_items_model->get_one($id);
            echo json_encode(array("success" => true, "total_view" => $this->_get_total_view($item_info->sale_return_id), "quantity" => $quantity, 'message' => lang('record_saved')));
        }
    }

    function get_delivery_status_bar($id = 0) {
        //$this->access_only_allowed_members();

        $view_data["sale_return_info"] = $this->Sale_returns_model->get_details(array("id" => $id))->row();
        $view_data['delivery_status_label'] = $this->_get_delivery_status_label($view_data["sale_return_info"]);
        $this->load->view('sale_returns/sale_return_status_bar', $view_data);
    }


    //view html is accessable to client only.
    function preview($sale_return_id = 0, $show_close_preview = false) {
        if ($sale_return_id) {
            $view_data = get_sale_return_making_data($sale_return_id);

            $this->_check_sale_return_access_permission($view_data);

            $view_data['sale_return_preview'] = prepare_sale_return_pdf($view_data, "html");

            //show a back button
            $view_data['show_close_preview'] = $show_close_preview && $this->login_user->user_type === "staff" ? true : false;

            $view_data['sale_return_id'] = $sale_return_id;

            $view_data['status'] = $this->_get_delivery_status_label($view_data["sale_return_info"], false);

            $this->template->rander("sale_returns/preview", $view_data);
        } else {
            show_404();
        }
    }

    //print sale_return
    function print_sale_return($sale_return_id = 0) {
        if ($sale_return_id) {
            $view_data = get_sale_return_making_data($sale_return_id);

            $this->_check_sale_return_access_permission($view_data);

            $view_data['sale_return_preview'] = prepare_sale_return_pdf($view_data, "html");

            echo json_encode(array("success" => true, "print_view" => $this->load->view("sale_returns/print", $view_data, true)));
        } else {
            echo json_encode(array("success" => false, lang('error_occurred')));
        }
    }

    function download_pdf($sale_return_id = 0) {

        if ($sale_return_id) {
            $sale_return_data = get_sale_return_making_data($sale_return_id);
            $this->_check_sale_return_access_permission($sale_return_data);

            prepare_sale_return_pdf($sale_return_data, "download");
        } else {
            show_404();
        }
    }

    private function _check_sale_return_access_permission($sale_return_data) {
        //check for valid sale_return
        if (!$sale_return_data) {
            show_404();
        }

        //check for security
        $sale_return_info = get_array_value($sale_return_data, "sale_return_info");
        
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
                $this->Sale_return_items_model->save($data, $id);
            }
        }
    }

}

/* End of file invoices.php */
/* Location: ./application/controllers/invoices.php */