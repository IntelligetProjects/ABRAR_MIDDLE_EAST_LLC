<?php

class Expense_items_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'expense_items';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $expense_categories_table = $this->db->dbprefix('expense_items');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $expense_categories_table.id=$id";
        }

        $sql = "SELECT $expense_categories_table.*
        FROM $expense_categories_table
        WHERE $expense_categories_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
