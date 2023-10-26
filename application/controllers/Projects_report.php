<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Projects_report extends MY_Controller {

    function __construct() {
        parent::__construct();
        //$this->access_only_admin();
    }

    function index() {

        $project_count = $this->Reports_model->get_projects_count();
        $view_data["total_projects"] = isset($project_count[0]->total) ? $project_count[0]->total : 0;
        $view_data["total_projects_clients"] = isset($project_count[0]->total_clients) ? $project_count[0]->total_clients : 0;

        $invoice_p_count = $this->Reports_model->get_invoices_project_count();
        $view_data["total_p_invoices"] = isset($invoice_p_count[0]->total) ? $invoice_p_count[0]->total : 0;
        $view_data["total_p_invoices_clients"] = isset($invoice_p_count[0]->total_clients) ? $invoice_p_count[0]->total_clients : 0;

        $invoice_count = $this->Reports_model->get_invoices_count();
        $view_data["total_invoices"] = isset($invoice_count[0]->total) ? $invoice_count[0]->total : 0;
        $view_data["total_invoices_clients"] = isset($invoice_count[0]->total_clients) ? $invoice_count[0]->total_clients : 0;
        
        $this->load->view("reports_view/projects", $view_data);
    }

    function test() {
        $info = $this->Reports_model->get_project_status_statistics();
        var_dump($info);
    }


    function list_data_projects() {

        $list_data = $this->Reports_model->get_projects()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_project_row($data);  
        }
        echo json_encode(array("data" => $result));
    }

    //prepare list row

    private function _make_project_row($data) {

        $row = array(
            anchor(get_uri("projects/view/" . $data->id), $data->title),
            $data->company_name,
            $data->labels,
            $data->total_po_pay?round($data->total_po_pay, 2) : 0,
            $data->total_expense?round($data->total_expense, 2) : 0,
            $data->total_pay?round($data->total_pay, 2) : 0,
            $data->total_member?$data->total_member:0
        );

        return $row;
    }

}

/* End of file Sales_report.php */
/* Location: ./application/controllers/Sales_report.php */