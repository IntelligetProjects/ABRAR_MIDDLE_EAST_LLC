<?php

class Budgeting_items_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'budgeting_items';
        parent::__construct($this->table);
        $this->init_activity_log($this->table, $this->table);
    }

    function get_details($options = array()) {
        $budgeting_items_table = $this->db->dbprefix('budgeting_items');
        $budgeting_table = $this->db->dbprefix('budgeting');
        $clients_table = $this->db->dbprefix('clients');
        $taxes_table = $this->db->dbprefix('taxes');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $budgeting_items_table.id=$id";
        }
        $estimate_id = get_array_value($options, "estimate_id");
        if ($estimate_id) {
            $where .= " AND $budgeting_items_table.estimate_id=$estimate_id";
        }

        $sql = "SELECT $budgeting_items_table.*, (SELECT $clients_table.currency_symbol FROM $clients_table WHERE $clients_table.id=$budgeting_table.client_id limit 1) AS currency_symbol, tax_table.percentage AS tax_percentage, tax_table2.percentage AS tax_percentage2
        FROM $budgeting_items_table
        LEFT JOIN $budgeting_table ON $budgeting_table.id=$budgeting_items_table.estimate_id
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $budgeting_items_table.tax_id
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table2 ON tax_table2.id = $budgeting_items_table.tax_id2
        WHERE $budgeting_items_table.deleted=0 $where";
        return $this->db->query($sql);  
    }

}
