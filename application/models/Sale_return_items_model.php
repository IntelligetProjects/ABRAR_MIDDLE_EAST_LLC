<?php

class Sale_return_items_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'sale_return_items';
        parent::__construct($this->table);
        $this->init_activity_log($this->table, $this->table);
    }

    function get_details($options = array()) {
        $sale_return_items_table = $this->db->dbprefix('sale_return_items');
        $sale_returns_table = $this->db->dbprefix('sale_returns');
        $invoice_items_table = $this->db->dbprefix('invoice_items');
        $taxes_table = $this->db->dbprefix('taxes');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $sale_return_items_table.id=$id";
        }
        $invoice_item_id = get_array_value($options, "invoice_item_id");
        if ($invoice_item_id) {
            $where .= " AND $sale_return_items_table.invoice_item_id = $invoice_item_id";
        }
        $sale_return_id = get_array_value($options, "sale_return_id");
        if ($sale_return_id) {
            $where .= " AND $sale_return_items_table.sale_return_id=$sale_return_id";
        }
        $quantity = get_array_value($options, "quantity");
        if ($quantity) {
            $where .= " AND $sale_return_items_table.quantity != 0";
        }

        $sql = "SELECT $sale_return_items_table.*, $invoice_items_table.title, $invoice_items_table.description, $invoice_items_table.unit_type, $invoice_items_table.rate, tax_table.percentage AS tax_percentage, tax_table2.percentage AS tax_percentage2
        FROM $sale_return_items_table
        LEFT JOIN $sale_returns_table ON $sale_returns_table.id=$sale_return_items_table.sale_return_id
        LEFT JOIN $invoice_items_table ON $invoice_items_table.id=$sale_return_items_table.invoice_item_id
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $invoice_items_table.tax_id
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table2 ON tax_table2.id = $invoice_items_table.tax_id2
        WHERE $sale_return_items_table.deleted=0 and $sale_returns_table.deleted=0 $where
        ORDER BY $sale_return_items_table.sort ASC";
        return $this->db->query($sql);
    }

    function get_sum_items($invoice_id = 0) {
        $sale_return_items_table = $this->db->dbprefix('sale_return_items');
        $sale_returns_table = $this->db->dbprefix('sale_returns');
        $where = "";
        if ($invoice_id) {
            $where .= " AND $sale_returns_table.invoice_id=$invoice_id";
        }

        $sql = "SELECT SUM(quantity) as sum
        FROM $sale_return_items_table
        LEFT JOIN $sale_returns_table ON $sale_returns_table.id=$sale_return_items_table.sale_return_id
        WHERE $sale_return_items_table.deleted=0 and $sale_returns_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_delivered_items($invoice_item_id = 0) {
        $sale_return_items_table = $this->db->dbprefix('sale_return_items');
        $sale_returns_table = $this->db->dbprefix('sale_returns');
        $where = "";
        if ($invoice_item_id) {
            $where .= " AND $sale_return_items_table.invoice_item_id=$invoice_item_id";
        }

        $sql = "SELECT SUM(quantity) as sum
        FROM $sale_return_items_table
        LEFT JOIN $sale_returns_table ON $sale_returns_table.id=$sale_return_items_table.sale_return_id
        WHERE $sale_return_items_table.deleted=0 and $sale_returns_table.deleted=0 $where";
        return $this->db->query($sql)->row()->sum;
    }

}
