<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reports extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->init_permission_checker("reports");
        $this->access_only_allowed_members();
    }

    //load  view
    function index() {
        $this->template->rander("reports_view/index");
    }

}

/* End of file expense_categories.php */
/* Location: ./application/controllers/expense_categories.php */