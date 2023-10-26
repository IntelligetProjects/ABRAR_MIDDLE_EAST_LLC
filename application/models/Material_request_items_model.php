<?php

class Material_request_items_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'material_request_items';
        parent::__construct($this->table);
        $this->init_activity_log($this->table, $this->table);
    }

    function get_details($options = array()) {
        $material_request_items_table = $this->db->dbprefix('material_request_items');
        $material_request_table = $this->db->dbprefix('material_request');
        $suppliers_table = $this->db->dbprefix('suppliers');
        $taxes_table = $this->db->dbprefix('taxes');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $material_request_items_table.id=$id";
        }
        $material_request_id = get_array_value($options, "material_request_id");
        if ($material_request_id) {
            $where .= " AND $material_request_items_table.material_request_id=$material_request_id";
        }

        $sql = "SELECT $material_request_items_table.*, (SELECT $suppliers_table.currency_symbol FROM $suppliers_table WHERE $suppliers_table.id=$material_request_table.supplier_id limit 1) AS currency_symbol, tax_table.percentage AS tax_percentage, tax_table2.percentage AS tax_percentage2
        FROM $material_request_items_table
        LEFT JOIN $material_request_table ON $material_request_table.id=$material_request_items_table.material_request_id
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $material_request_items_table.tax_id
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table2 ON tax_table2.id = $material_request_items_table.tax_id2
        WHERE $material_request_items_table.deleted=0 $where
        ORDER BY $material_request_items_table.sort ASC";
        return $this->db->query($sql);
    }

    function get_item_suggestion($keyword = "") {
        $items_table = $this->db->dbprefix('items');
        

        $sql = "SELECT $items_table.title
        FROM $items_table
        WHERE $items_table.deleted=0 AND $items_table.item_type = 'product' AND $items_table.title LIKE '%$keyword%'
        LIMIT 10 
        ";
        return $this->db->query($sql)->result();
    }

    function get_item_info_suggestion($item_name = "") {

        $items_table = $this->db->dbprefix('items');
        

        $sql = "SELECT $items_table.*
        FROM $items_table
        WHERE $items_table.deleted=0  AND $items_table.title LIKE '%$item_name%'
        ORDER BY id DESC LIMIT 1
        ";
        
        $result = $this->db->query($sql); 

        if ($result->num_rows()) {
            return $result->row();
        }

    }


    function get_sum_items($material_request_id) {
        $invoice_items_table = $this->db->dbprefix('material_request_items');
        $invoices_table = $this->db->dbprefix('material_request');

        $sql = "SELECT SUM(quantity) as sum
        FROM $invoice_items_table
        LEFT JOIN $invoices_table ON $invoices_table.id=$invoice_items_table.material_request_id
        WHERE $invoice_items_table.deleted=0 and $invoices_table.deleted=0 and $invoice_items_table.material_request_id=$material_request_id";
        return $this->db->query($sql);
    }

}
