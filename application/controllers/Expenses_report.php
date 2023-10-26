<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Expenses_report extends MY_Controller {

    function __construct() {
        parent::__construct();
        //$this->access_only_admin();
    }

    function index() {
        $this->load->view("reports_view/expenses");
    }

    function test() {
        $info = $this->Reports_model->get_expense_categories()->result();
        var_dump($info);
    }

    function cats_list_data() {

        $options = array(
            "start_date" => $this->input->post("start_date"),
            "end_date" => $this->input->post("end_date"),
        );

        $list_data = $this->Reports_model->get_expense_categories($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->cat_make_row($data);
        }

        echo json_encode(array("data" => $result));
    }

    private function cat_make_row($data) {
        
        $row_data = array(
            $data->title,
            round($data->totals, 3),
        );


        return $row_data;
    }

    function emps_list_data() {

        $options = array(
            "start_date" => $this->input->post("start_date"),
            "end_date" => $this->input->post("end_date"),
        );

        $list_data = $this->Reports_model->get_expense_emps($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->emp_make_row($data);
        }

        echo json_encode(array("data" => $result));
    }

    private function emp_make_row($data) {
        
        $row_data = array(
            $data->title,
            round($data->totals, 3),
        );


        return $row_data;
    }


}

/* End of file Expenses_report.php */
/* Location: ./application/controllers/report.php */