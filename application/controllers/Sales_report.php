<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sales_report extends MY_Controller {

    function __construct() {
        parent::__construct();
        //$this->access_only_admin();
    }

    function index() {
        $view_data["invoices_info"] = $this->Reports_model->get_invoices_total_and_paymnts();

        $estimate_count = $this->Reports_model->get_estimates_count();
        $view_data["total_estimates"] = isset($estimate_count[0]->total) ? $estimate_count[0]->total : 0;
        $view_data["total_estimates_clients"] = isset($estimate_count[0]->total_clients) ? $estimate_count[0]->total_clients : 0;

        $invoice_count = $this->Reports_model->get_invoices_count();
        $view_data["total_invoices"] = isset($invoice_count[0]->total) ? $invoice_count[0]->total : 0;
        $view_data["total_invoices_clients"] = isset($invoice_count[0]->total_clients) ? $invoice_count[0]->total_clients : 0;

        $leads_count = $this->Reports_model->get_leads_count();
        $view_data["leads_count"] = isset($leads_count[0]->total) ? $leads_count[0]->total : 0;
        $clients_count = $this->Reports_model->get_clients_count();
        $view_data["clients_count"] = isset($clients_count[0]->total) ? $clients_count[0]->total : 0;
        
        $this->load->view("reports_view/sales", $view_data);
    }

    function test() {
        $list_data = $this->Reports_model->get_payment_statistics();
        var_dump($list_data);
    }


    function list_data_estimate_user() {
        
        $list_data = $this->Reports_model->monthly_value_by_user();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row_estimate_user($data);
        }
        echo json_encode(array("data" => $result));
    }

    //prepare list row

    private function _make_row_estimate_user($data) {
        
        $currency_symbol = get_setting("currency_symbol");

        $row = array(
            $data->create_user,
            number_format($data->total_value, 3),
        );

        return $row;
    }

    function list_data_invoice_user() {
        
        $list_data = $this->Reports_model->monthly_value_by_user_invoice();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row_invoice_user($data);
        }
        echo json_encode(array("data" => $result));
    }

    //prepare list row

    private function _make_row_invoice_user($data) {
        
        $currency_symbol = get_setting("currency_symbol");

        $row = array(
            $data->create_user,
            number_format($data->total_value, 3),
        );

        return $row;
    }

}

/* End of file Sales_report.php */
/* Location: ./application/controllers/Sales_report.php */