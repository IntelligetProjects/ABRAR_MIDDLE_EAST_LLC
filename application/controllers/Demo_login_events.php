<?php


if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Demo_login_events extends CI_Controller {
    

function __construct() {
    parent::__construct();
}

    public function list_data(){

        validate_submitted_data(array(
            "start_date" => "trim|required",
            "end_date" => "trim|required"
        ));
      
        $start_date = $this->db->escape($this->input->post("start_date"));
        $end_date = $this->db->escape($this->input->post("end_date"));

        // if(!$this->valid_date($start_date) || !$this->valid_date($end_date)){
        //     echo json_encode(array("error"=> "Invalid date","data" => array()));
        //     return;
        // }

        $options = array(
            "start_date" => $start_date,
            "end_date" => $end_date
        );

        $list_data =  $this->Demo_login_events_model->get_details($options)->result();
        echo json_encode(array("data" => $list_data));
    }

    private function valid_date($date) {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

}

?>