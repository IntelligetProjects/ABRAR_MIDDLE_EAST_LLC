<?php

class Budgeting_forms_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'budgeting_forms';
        parent::__construct($this->table);
        $this->init_activity_log($this->table, $this->table);
    }

    function get_details($options = array()) {
        $budgeting_forms_table = $this->db->dbprefix('budgeting_forms');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $budgeting_forms_table.id=$id";
        }


        $sql = "SELECT $budgeting_forms_table.*
        FROM $budgeting_forms_table
        WHERE $budgeting_forms_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
