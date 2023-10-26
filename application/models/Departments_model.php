<?php

class Departments_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'departments';
        parent::__construct($this->table);
       // $this->init_activity_log($this->table, $this->table);
    }

    function get_details($options = array()) {
        $items_table = $this->db->dbprefix('departments');
     
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $items_table.id=$id";
        }

        $sql = " SELECT * FROM  $items_table
        WHERE $items_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
