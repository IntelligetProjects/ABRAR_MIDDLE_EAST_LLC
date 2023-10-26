<?php

class Item_uom_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'item_uom';
        parent::__construct($this->table);
        $this->init_activity_log($this->table, $this->table);
    }

    function get_details($options = array()) {
        $item_uom_table = $this->db->dbprefix('item_uom');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $item_uom_table.id=$id";
        }

        $sql = "SELECT $item_uom_table.*
        FROM $item_uom_table
        WHERE $item_uom_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
