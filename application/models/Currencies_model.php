<?php

class Currencies_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'currencies';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $currency_table = $this->db->dbprefix('currencies');
        $where = "";

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $currency_table.id = $id";
        }


        $sql = "SELECT  $currency_table.* FROM  $currency_table
        WHERE $currency_table.deleted = 0 $where";
        
        return $this->db->query($sql);
    }


}
