<?php

class Service_provider_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'service_provider';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $service_provider_table = $this->db->dbprefix('service_provider');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $service_provider_table.id=$id";
        }

        $sql = "SELECT $service_provider_table.*
        FROM $service_provider_table
        WHERE $service_provider_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function is_duplicate_name($name, $id = 0) {

        $result = $this->get_all_where(array("name" => $name,"deleted" => 0));
        if ($result->num_rows() && $result->row()->id != $id) {
            return $result->row();
        } else {
            return false;
        }
    }

    function is_duplicate_vat_number($vat_number, $id = 0) {

        $result = $this->get_all_where(array("vat_number" => $vat_number,"deleted" => 0));
        if ($result->num_rows() && $result->row()->id != $id) {
            return $result->row();
        } else {
            return false;
        }
    }

}
