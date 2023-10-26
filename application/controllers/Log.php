<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Log extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->init_permission_checker("logs");
        $this->access_only_allowed_members();
    }

    function index() {
        $this->template->rander("log/index");        
    }

    function list_data($module='') {

        $start_date = $this->input->post('start_date')." 00:00:00";
        $end_date = $this->input->post('end_date')." 23:59:59";
        $options = array();
        if ($module == "general"){
            $options = array("start_date" => $start_date, "end_date" => $end_date);
        } else {
            $options = array("module"=>$module, "start_date" => $start_date, "end_date" => $end_date);
        }

        $list_data = $this->Activity_logs_model->get_logs($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data) { 

        $change = "";

        if ($data->changes) {
            $changes = unserialize($data->changes);
            foreach ($changes as $field => $value) {
                $to = $value['to'] ? $value['to'] : "-";
                $from = $value['from'] ? $value['from'] : "-";

                $change .= "<strong>" . $field . " is changed from: </strong>" . $value['from'] . "<strong> to: </strong>" . $value['to'] ."<br>" ;
            }
        }


        if(in_array($data->log_type, array("clients", "estimates", "invoices", "delivery_notes", "purchase_orders"))) {
            $link = anchor(get_uri($data->log_type . "/view/" . $data->log_type_id), $data->log_type . " id #" . $data->log_type_id);
        } else {
            $link = $data->log_type . " id #" . $data->log_type_id;
        }

        return array(
             $data->action,
             $link,             
             $change,
             $data->created_by_user,
             $data->created_at 
        );
    }


    function show_tab($module) {
        $view_data['module'] = $module;
        $this->load->view("log/tables", $view_data);
    }

}

/* End of file Log.php */
/* Location: ./application/controllers/Log.php */