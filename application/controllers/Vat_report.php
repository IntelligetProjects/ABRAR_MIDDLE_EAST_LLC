<?php

use LDAP\Result;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Vat_report extends MY_Controller {

    function __construct() {
        parent::__construct();
        //$this->init_permission_checker("invoice");
        $this->permission_checker("can_access_invoices");
        $this->permission_checker("can_access_invoice_payments");
    }

    /* load invoice list view */

    function index() {
        $this->check_module_availability("module_invoice");

            $view_data['can_add_payment'] = true;

            $this->template->rander("vat-report/index", $view_data);
    }

    function purchase_import() {
        $this->load->view("vat-report/purchase_import");
    }
    function purchase_domestic() {
        $this->load->view("vat-report/purchase_domestic");
    }
    function vat_report() {
        $this->load->view("vat-report/vat_report");
    }
    function history() {
        $this->template->rander("vat-report/history");
    }

    function list_history(){
        $x=[];
        $dx=[];
        $x=['2021','First Quarter','1,200 OMR','Paid',"<button class='btn btn-info'>Print</button>"];
        array_push($dx,$x);
        $x=['2021','Second Quarter','2,200 OMR','UnPaid',"<button class='btn btn-success'>Pay</button>"];
        array_push($dx,$x);
        $x=['2021','Third Quarter','4,000 OMR','UnPaid',"<button class='btn btn-success'>Pay</button>"];
        array_push($dx,$x);
        $x=['2021','Fourth Quarter','4,000 OMR','UnPaid',"<button class='btn btn-success'>Pay</button>"];
        array_push($dx,$x);
        $x=['2022','First Quarter','1,200 OMR','Paid',"<button class='btn btn-info'>Print</button>"];
        array_push($dx,$x);
        $x=['2022','Second Quarter','2,200 OMR','UnPaid',"<button class='btn btn-success'>Pay</button>"];
        array_push($dx,$x);
        $x=['2022','Third Quarter','4,000 OMR','UnPaid',"<button class='btn btn-success'>Pay</button>"];
        array_push($dx,$x);
        $x=['2022','Fourth Quarter','4,000 OMR','UnPaid',"<button class='btn btn-success'>Pay</button>"];
        array_push($dx,$x);
        $x=['2023','First Quarter','1,200 OMR','Paid',"<button class='btn btn-info'>Print</button>"];
        array_push($dx,$x);
        
        echo json_encode(array("data" => $dx));
    }

    function list_data_output_vat(){
        $this->access_allowed_members();

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("invoices", $this->login_user->is_admin, $this->login_user->user_type);
      
        $start_date = $this->get_quarter_start_date();
        $end_date = $this->get_quarter_end_date();

        $options = array(
            "status" => "partially_paid_or_fully_paid",
            "start_date" => $start_date,
            "end_date" => $end_date,
            "currency" => $this->input->post("currency"),
            "custom_fields" => $custom_fields
        );
        $this->permission_checker("invoice_manage_permission");
        if(!$this->login_user->is_admin || !$this->access_type === "all"){
            $options["allowed_members"] = $this->allowed_members;
        }

        $list_data = $this->Invoices_model->get_details_for_vat_report($options)->result();
       
       
        $result = array();
        foreach ($list_data as $data) {
            $options2 = array("invoice_id"=>$data->id,
                        "start_date" => $start_date,
                        "end_date" => $end_date,
                        );
            $payments = $this->Invoice_payments_model->get_details($options2)->result();
            foreach($payments as $payment){
                $result[] = $this->_make_row_of_output_vat($data, $custom_fields,$payment);
            }
            
        }

        echo json_encode(array("data" => $result));
    }

    private function _make_row_of_output_vat($data, $custom_fields,$payment) {
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
        
        $calculated_tax = number_format(($payment->amount * $data->tax_value)/($data->invoice_value + $data->tax_value),3);
        
        $row_data = array($data->id,$invoice_url,
            anchor(get_uri("clients/view/" . $data->client_id), $data->company_name."</br>".$data->phone),
            $data->client_vat_number,
            $data->bill_date,
            format_to_date($data->bill_date, false),
            $payment->payment_date,
            format_to_date($payment->payment_date, false),
            // to_currency($data->invoice_value + $data->tax_value, $data->currency_symbol),
            to_currency($payment->amount, $data->currency_symbol),
            "5%",
            to_currency($calculated_tax , $data->currency_symbol),
        );

        foreach ($custom_fields as $field) {
            $cf_id = "cfv_" . $field->id;
            $row_data[] = $this->load->view("custom_fields/output_" . $field->field_type, array("value" => $data->$cf_id), true);
        }

        // $row_data[] = $this->_make_options_dropdown($data->id);

        return $row_data;
    }



    function list_data_purchase_import(){

        $start_date = $this->get_quarter_start_date();
        $end_date = $this->get_quarter_end_date();

        $options = array(
            "status" => $this->input->post("status"),
            "start_date" => $start_date,
            "end_date" => $end_date,
            "currency" => $this->input->post("currency"),
            "type" => "import"
        );

        $this->permission_checker("purchase_order_manage_permission");
        if(!$this->login_user->is_admin || !$this->access_type === "all"){
            $options["allowed_members"] = $this->allowed_members;
        }

        $list_data = $this->Purchase_orders_model->get_details_for_vat_report($options)->result();
        $result = array();
        foreach ($list_data as $data) { 
            $options2 = array("purchase_order_id"=>$data->id,
            "start_date" => $start_date,
            "end_date" => $end_date,
            );
            $payments = $this->Purchase_order_payments_model->get_details($options2)->result();
            foreach($payments as $payment){
                $result[] = $this->_make_row_of_purchase_import($data,$payment);
            }
            
        }

        echo json_encode(array("data" => $result));
    }

    private function _make_row_of_purchase_import($data,$payment) {
        $purchase_order_url = "";
        if ($this->login_user->user_type == "staff") {
            $purchase_order_url = anchor(get_uri("purchase_orders/view/" . $data->id), get_purchase_order_id($data->id));
        }

        $purchase_order_labels = "";
        if ($data->labels) {
            $labels = explode(",", $data->labels);
            foreach ($labels as $label) {
                $purchase_order_labels .= "<span class='mt0 label label-info large ml10 clickable'  title='$label'>" . $label . "</span>";
            }
        }

        $taxable_value_in_OMR = $payment->amount;
        $vat_rate = 5;//%
        $calculated_tax = ( $taxable_value_in_OMR / (($vat_rate / 100)+1) );
        
        $auto_input_vat ='';
        $supplier_vat_number = '';
        if($data->supplier_vat_number){
            $supplier_vat_number = $data->supplier_vat_number;
            $auto_input_vat = to_currency(number_format($taxable_value_in_OMR - $calculated_tax,3));
        }else{
            $auto_input_vat = '❌';
            $supplier_vat_number = '<mark style="background-color:#871414;color:white">❌'.lang("supplier_VAT_number_not_found").'</mark>';
        }
        $row_data = array($purchase_order_url,
            format_to_date($data->purchase_order_date, false),
            $supplier_vat_number,
            $data->invoice_ref_number,
            anchor(get_uri("suppliers/view/" . $data->supplier_id), $data->company_name),
            $data->currency_symbol?$data->currency_symbol:trim(get_setting("currency_symbol"),'/'),
            format_to_date($payment->payment_date),
            // to_currency($data->taxable_value_in_FC,"no"),
            // $data->exchange_rate_at_PO_time,
            to_currency($taxable_value_in_OMR, $data->currency_symbol),
            $vat_rate."%",
            $auto_input_vat
        );

        return $row_data;
    }

    function list_data_purchase_domestic(){

        $start_date = $this->get_quarter_start_date();
        $end_date = $this->get_quarter_end_date();

        $options = array(
            "status" => $this->input->post("status"),
            "start_date" => $start_date,
            "end_date" => $end_date,
            "currency" => $this->input->post("currency"),
            "type" => "domestic"
        );

        $this->permission_checker("purchase_order_manage_permission");
        if(!$this->login_user->is_admin || !$this->access_type === "all"){
            $options["allowed_members"] = $this->allowed_members;
        }

        $list_data = $this->Purchase_orders_model->get_details_for_vat_report($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $options2 = array("purchase_order_id"=>$data->id,
            "start_date" => $start_date,
            "end_date" => $end_date,
            );
            $payments = $this->Purchase_order_payments_model->get_details($options2)->result();
            foreach($payments as $payment){
                $result[] = $this->_make_row_of_purchase_domestic($data,$payment);
            }
        }

        echo json_encode(array("data" => $result));
    }


    private function _make_row_of_purchase_domestic($data,$payment) {
        $purchase_order_url = "";
        if ($this->login_user->user_type == "staff") {
            $purchase_order_url = anchor(get_uri("purchase_orders/view/" . $data->id), get_purchase_order_id($data->id));
        }

        $purchase_order_labels = "";
        if ($data->labels) {
            $labels = explode(",", $data->labels);
            foreach ($labels as $label) {
                $purchase_order_labels .= "<span class='mt0 label label-info large ml10 clickable'  title='$label'>" . $label . "</span>";
            }
        }

        $taxable_value_in_OMR = $payment->amount;
        $vat_rate = 5;//%
        $calculated_tax = ($taxable_value_in_OMR / (($vat_rate / 100)+1) );
        $auto_input_vat ='';
        $supplier_vat_number = '';
        if($data->supplier_vat_number){
            $supplier_vat_number = $data->supplier_vat_number;
            $auto_input_vat = to_currency(number_format($taxable_value_in_OMR - $calculated_tax,3));
        }else{
            $auto_input_vat = '❌';
            $supplier_vat_number = '<mark style="background-color:#871414;color:white">❌'.lang("supplier_VAT_number_not_found").'</mark>';
        }

        $row_data = array(
            $purchase_order_url,
            format_to_date($data->purchase_order_date, false),
            $supplier_vat_number,
            $data->invoice_ref_number,
            anchor(get_uri("suppliers/view/" . $data->supplier_id), $data->company_name),
            $data->currency_symbol?$data->currency_symbol:trim(get_setting("currency_symbol"),'/'),
            $payment->payment_date,
            $data->asset_type,
            to_currency($payment->amount, $data->currency_symbol),
            $vat_rate."%",
            $auto_input_vat
        );

        return $row_data;
    }

    function list_data_vat_summery(){
        $vat_summery_view =get_uri('vat_report/vat_report_view/');
        $start_date = $this->get_quarter_start_date();
        $end_date = $this->get_quarter_end_date();
        $url_param  = "start_date=".$start_date;
        $url_param .="&";
        $url_param .= "end_date=".$end_date;
        $result[] = array("<iframe width='100%' height='1000px' src='".$vat_summery_view."?".$url_param."'></iframe>");
        echo json_encode(array("data" => $result));
    }

    function vat_report_view(){
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        
        $zero = number_format(0,3);
        //1
        $a_1_result = $this->get_output_vat_total($start_date,$end_date);
        
        $a_1_vat_due = get_array_value($a_1_result,"vat_due");
        $a_1_taxable_value = get_array_value($a_1_result,"taxable_value");
        $b_1_taxable_value = $zero;
        $b_1_vat_due = $zero;
        $c_1_taxable_value = $zero;
        $c_1_vat_due = $zero;
        $d_1_taxable_value = $zero;
        $d_1_vat_due = $zero;
        $e_1_taxable_value = $zero;
        $e_1_vat_due = $zero;
        $f_1_taxable_value = $zero;
        $f_1_vat_due = $zero;

        //2
        $a_2_taxable_value = $zero;
        $a_2_vat_due = $zero;
        $b_2_taxable_value = $zero;
        $b_2_vat_due = $zero;

        //3
        $a_3_taxable_value = $zero;
        $a_3_vat_due = $zero;

        //4
        $a_4_taxable_value = $zero;
        $a_4_vat_due = $zero;

        $b_4_result = $this->get_purchase_import_total($start_date,$end_date,"import");
        $b_4_taxable_value = get_array_value($b_4_result,"taxable_value");
        $b_4_vat_due = get_array_value($b_4_result,"vat_due");

        //5
        // 5(a) Total VAT due under (1(a) + 1(f) + 2(a) + 2(b) + 4(a))
        $a_5_taxable_value = $zero;
        $a_5_vat_due = number_format($a_1_vat_due + $f_1_vat_due + $a_2_vat_due + $b_2_vat_due + $a_4_vat_due,3);
        $b_5_taxable_value = $zero;
        $b_5_vat_due = $zero;

        //6
        $a_6_result = $this->get_purchase_import_total($start_date,$end_date,"domestic");
        $a_6_taxable_value = get_array_value($a_6_result,"taxable_value");
        $a_6_vat_due = get_array_value($a_6_result,"vat_due");

        $b_6_taxable_value = $b_4_taxable_value;
        $b_6_vat_due = $b_4_vat_due;

        $c_6_taxable_value = $zero;
        $c_6_vat_due = $zero;
        $d_6_taxable_value = $zero;
        $d_6_vat_due = $zero;

        //7
        // 7(a) Total VAT due (5(a) + 5(b))
        $a_7_taxable_value = $zero;
        $a_7_vat_due = number_format($a_5_vat_due + $b_5_vat_due ,3);
        // 7(b) Total input VAT Credit (6(a) + 6(b) + 6(c) + 6(d))
        $b_7_taxable_value = $zero;
        $b_7_vat_due = number_format($a_6_vat_due + $b_6_vat_due + $c_6_vat_due + $d_6_vat_due,3);

        // 7(c) Total (7(a) - 7(b))
        $c_7_taxable_value = $zero;
        $c_7_vat_due = number_format($a_7_vat_due - $b_7_vat_due,3);

        $data = array(
            'start_date' => $start_date,
            'end_date' => $end_date,
            //1
            'a_1_vat_due' => $a_1_vat_due,
            'a_1_taxable_value' => $a_1_taxable_value,
            'b_1_vat_due' => $b_1_vat_due,
            'b_1_taxable_value' => $b_1_taxable_value,
            'c_1_vat_due' => $c_1_vat_due,
            'c_1_taxable_value' => $c_1_taxable_value,
            'd_1_vat_due' => $d_1_vat_due,
            'd_1_taxable_value' => $d_1_taxable_value,
            'e_1_vat_due' => $e_1_vat_due,
            'e_1_taxable_value' => $e_1_taxable_value,
            'f_1_vat_due' => $f_1_vat_due,
            'f_1_taxable_value' => $f_1_taxable_value,

            //2
            'a_2_vat_due' => $a_2_vat_due,
            'a_2_taxable_value' => $a_2_taxable_value,
            'b_2_vat_due' => $b_2_vat_due,
            'b_2_taxable_value' => $b_2_taxable_value,

            //3
            'a_3_vat_due' => $a_3_vat_due,
            'a_3_taxable_value' => $a_3_taxable_value,

            //4
            'a_4_vat_due' => $a_4_vat_due,
            'a_4_taxable_value' => $a_4_taxable_value,
            'b_4_vat_due' => $b_4_vat_due,
            'b_4_taxable_value' => $b_4_taxable_value,

            //5
            'a_5_vat_due' => $a_5_vat_due,
            'a_5_taxable_value' => $a_5_taxable_value,
            'b_5_vat_due' => $b_5_vat_due,
            'b_5_taxable_value' => $b_5_taxable_value,

            //6
            'a_6_vat_due' => $a_6_vat_due,
            'a_6_taxable_value' => $a_6_taxable_value,
            'b_6_vat_due' => $b_6_vat_due,
            'b_6_taxable_value' => $b_6_taxable_value,
            'c_6_vat_due' => $c_6_vat_due,
            'c_6_taxable_value' => $c_6_taxable_value,
            'd_6_vat_due' => $d_6_vat_due,
            'd_6_taxable_value' => $d_6_taxable_value,

            //7
            'a_7_vat_due' => $a_7_vat_due,
            'a_7_taxable_value' => $a_7_taxable_value,
            'b_7_vat_due' => $b_7_vat_due,
            'b_7_taxable_value' => $b_7_taxable_value,
            'c_7_vat_due' => $c_7_vat_due,
            'c_7_taxable_value' => $c_7_taxable_value,

        );
        $this->load->view("vat-report/vat_report_view",$data);
    }


    /***********************************************
    *****************   EXPENSESS  ****************
    ***********************************************/

    function list_data_expensess_vat_report(){
        $start_date = $this->get_quarter_start_date();
        $end_date = $this->get_quarter_end_date();

        $category_id = $this->input->post('category_id');
        $project_id = $this->input->post('project_id');
        $user_id = $this->input->post('user_id');
        $client_id = $this->input->post('client_id');
        $payment_mode = $this->input->post('payment_mode');
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("expenses", $this->login_user->is_admin, $this->login_user->user_type);
        $options = array("status" => "approved",
        "start_date" => $start_date,
        "end_date" => $end_date, 
        "category_id" => $category_id, 
        "project_id" => $project_id, 
        "user_id" => $user_id, 
        "client_id" => $client_id, 
        "payment_mode"=> $payment_mode, 
        "custom_fields" => $custom_fields);

        $this->permission_checker("expense_manage_permission");
        if(!$this->login_user->is_admin || !$this->access_type === "all"){
            $options["allowed_members"] = $this->allowed_members;
        }

        $list_data = $this->Expenses_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row_of_expensess_vat_report($data, $custom_fields);
        }
        echo json_encode(array("data" => $result));
    }

    function _make_row_of_expensess_vat_report($data){
        $calculated_tax = to_currency(number_format(
            $data->amount - ( $data->amount / (($data->tax_percentage / 100)+1) )
        ,3));

        $service_provider_vat_number = $data->service_provider_vat_number;

        if($data->service_provider_vat_number==null){
            $service_provider_vat_number = '<mark style="background-color:#871414;color:white">❌'.lang("service_provider_VAT_number_not_found").'</mark>';;
            $calculated_tax = '❌';
        }

        $row_data = array(
            format_to_date($data->expense_date, false),
            $service_provider_vat_number,
            $data->invoice_ref_number,
            $data->service_provider_name,
            isset($data->currency_symbol)?$data->currency_symbol:trim(get_setting("currency_symbol"),'/'),
            $data->type,//domestic import
            $data->asset_type,
            to_currency($data->amount),// add the value of expenss
            $data->tax_percentage."%",
            $calculated_tax
        );

        return $row_data;
    }

    function expensess_vat_report(){
        $this->load->view("vat-report/expensess_vat_report");
    }

    function get_quarter_start_date(){
        $quarter = $this->input->post("quarter");
        if($quarter == null){
            $quarter = $this->get_current_quarter();
        }
        $year = date('Y',strtotime($this->input->post("start_date")));

        switch($quarter){
            case 1:
                return $year."-01-01";
            case 2:
                return $year."-04-01";
            case 3:
                return $year."-07-01";
            case 4:    
                return $year."-10-01";
        }
    }

    function get_quarter_end_date(){
        $quarter = $this->input->post("quarter");
        if($quarter == null){
            $quarter = $this->get_current_quarter();
        }
        $year = date('Y',strtotime($this->input->post("start_date")));

        switch($quarter){
            case 1:
                return $year."-03-31";
            case 2:
                return $year."-06-30";
            case 3:
                return $year."-09-30";
            case 4:    
                return $year."-12-31";
        }
    }

    function get_current_quarter(){
        $month = date('n');
        return ceil($month / 3);
    }

    function get_output_vat_total($start_date,$end_date){
        $options = array(
            "status" => "partially_paid_or_fully_paid",
            "start_date" => $start_date,
            "end_date" => $end_date,
            "currency" => $this->input->post("currency"),
        );

        $list_data = $this->Invoices_model->get_details_for_vat_report($options)->result();
       
        $vat_due = 0.00;
        $taxable_value =0.00;
        foreach ($list_data as $data) {
            $options2 = array("invoice_id"=>$data->id,
                        "start_date" => $start_date,
                        "end_date" => $end_date,
                        );
            $payments = $this->Invoice_payments_model->get_details($options2)->result();
            foreach($payments as $payment){
                $calculated_tax = ($payment->amount * $data->tax_value)/($data->invoice_value + $data->tax_value);
                $vat_due += $calculated_tax;

                if($data->tax_value > 0){
                    $taxable_value += $payment->amount;
                }
            }
            
        }
        return array("vat_due" => number_format($vat_due,3),
                    "taxable_value"=> number_format($taxable_value,3));
    }

    function get_purchase_import_total($start_date,$end_date,$type){

        $options = array(
            "status" => $this->input->post("status"),
            "start_date" => $start_date,
            "end_date" => $end_date,
            "currency" => $this->input->post("currency"),
            "type" => $type
        );

        $this->permission_checker("purchase_order_manage_permission");
        if(!$this->login_user->is_admin || !$this->access_type === "all"){
            $options["allowed_members"] = $this->allowed_members;
        }

        $list_data = $this->Purchase_orders_model->get_details_for_vat_report($options)->result();

        $vat_rate = 5;//%
        $auto_input_vat = 0.00;
        $taxable_value =0.00;

        foreach ($list_data as $data) { 
            $options2 = array("purchase_order_id"=>$data->id,
            "start_date" => $start_date,
            "end_date" => $end_date,
            );
            $payments = $this->Purchase_order_payments_model->get_details($options2)->result();
            foreach($payments as $payment){
                $taxable_value_in_OMR = $payment->amount;
                $calculated_tax = ( $taxable_value_in_OMR / (($vat_rate / 100)+1) );

                if($data->supplier_vat_number){
                    $auto_input_vat += $taxable_value_in_OMR - $calculated_tax;
                    $taxable_value += $payment->amount;
                }
            }
            
        }

        return array("vat_due"=>  number_format($auto_input_vat,3) ,
                     "taxable_value"=> number_format($taxable_value,3) 
                    );
    }

}

/* End of file Vat_report.php */
/* Location: ./application/controllers/vat_report.php */