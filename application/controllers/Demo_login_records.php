<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Demo_login_records extends CI_Controller {
    

function __construct() {
    parent::__construct();
}

    public function list_data(){
        $list_data =  $this->Demo_login_records_model->get_details()->result();
        echo json_encode(array("data" => $list_data));
    }

}

?>