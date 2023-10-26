<?php

class Cost_centers_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'cost_centers';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $cost_center_table = $this->db->dbprefix('cost_centers');
        $currency_table = $this->db->dbprefix('currencies');
        $where = "";

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $cost_center_table.id = $id";
        }


        $sql = "SELECT  $cost_center_table.*, $currency_table.symbol AS currency_symbol FROM  $cost_center_table
        LEFT JOIN $currency_table ON $currency_table.id = $cost_center_table.currency_id
        WHERE $cost_center_table.deleted = 0 $where";
        
        return $this->db->query($sql);
    }

}
