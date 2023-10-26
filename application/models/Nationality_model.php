<?php

class Nationality_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'nationality';
        parent::__construct($this->table);
        $this->init_activity_log($this->table, $this->table);
    }

    function get_details($options = array()) {
        $nationality_table = $this->db->dbprefix('nationality');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $nationality_table.id=$id";
        }

        $sql = "SELECT $nationality_table.*
        FROM $nationality_table
        WHERE $nationality_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
