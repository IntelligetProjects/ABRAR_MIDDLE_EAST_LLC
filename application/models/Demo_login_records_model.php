<?php

class Demo_login_records_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'login_records';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $login_records_table = $this->db->dbprefix('login_records');

        $where = "";

        $sql = " SELECT * FROM  $login_records_table";
        return $this->db->query($sql);
    }



}
