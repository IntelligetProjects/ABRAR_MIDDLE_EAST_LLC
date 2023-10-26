<?php

class Demo_login_events_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'login_records';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $login_records_table = $this->db->dbprefix('login_records');

        $where = "";
        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where = "WHERE ( DATE(login_date) BETWEEN DATE($start_date) AND DATE($end_date))";
        }

        $sql = " SELECT * FROM  $login_records_table $where";
        return $this->db->query($sql);
    }



}
