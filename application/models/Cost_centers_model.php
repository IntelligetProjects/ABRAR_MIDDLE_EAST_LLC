<?php

class Cost_centers_model extends Crud_model
{

    private $table = null;

    function __construct()
    {
        $this->table = 'cost_centers';
        parent::__construct($this->table);
    }

    function get_details($options = array())
    {
        $cost_center_table = $this->db->dbprefix('cost_centers');
        $currency_table = $this->db->dbprefix('currencies');
        $where = "";

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $cost_center_table.id = $id";
        }


        $sql = "SELECT  $cost_center_table.*, 
        $currency_table.symbol AS currency_symbol, $currency_table.name AS currency_name, $currency_table.rate AS currency_rate 
        FROM  $cost_center_table
        LEFT JOIN $currency_table ON $currency_table.id = $cost_center_table.currency_id
        WHERE $cost_center_table.deleted = 0 $where";

        return $this->db->query($sql);
    }

    function get_currency_rate($cost_center_id)
    {
        $cost_center_table = $this->db->dbprefix('cost_centers');
        $currency_table = $this->db->dbprefix('currencies');

        $sql = "SELECT $currency_table.rate 
        FROM  $cost_center_table
        LEFT JOIN $currency_table ON $currency_table.id = $cost_center_table.currency_id
        WHERE $cost_center_table.deleted = 0 and $cost_center_table.id = $cost_center_id";

        return $this->db->query($sql)->row();
    }
}
