<?php

class Purchase_return_items_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'purchase_return_items';
        parent::__construct($this->table);
        $this->init_activity_log($this->table, $this->table);
    }

    function get_details($options = array()) {
        $purchase_return_items_table = $this->db->dbprefix('purchase_return_items');
        $purchase_returns_table = $this->db->dbprefix('purchase_returns');
        $purchase_order_items_table = $this->db->dbprefix('purchase_order_items');
        $taxes_table = $this->db->dbprefix('taxes');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $purchase_return_items_table.id=$id";
        }
        $po_item_id = get_array_value($options, "po_item_id");
        if ($po_item_id) {
            $where .= " AND $purchase_return_items_table.po_item_id = $po_item_id";
        }
        $purchase_return_id = get_array_value($options, "purchase_return_id");
        if ($purchase_return_id) {
            $where .= " AND $purchase_return_items_table.purchase_return_id=$purchase_return_id";
        }
        $quantity = get_array_value($options, "quantity");
        if ($quantity) {
            $where .= " AND $purchase_return_items_table.quantity != 0";
        }

        $sql = "SELECT $purchase_return_items_table.*, $purchase_order_items_table.title, $purchase_order_items_table.description, $purchase_order_items_table.unit_type, $purchase_order_items_table.rate, tax_table.percentage AS tax_percentage, tax_table2.percentage AS tax_percentage2
        FROM $purchase_return_items_table
        LEFT JOIN $purchase_returns_table ON $purchase_returns_table.id=$purchase_return_items_table.purchase_return_id
        LEFT JOIN $purchase_order_items_table ON $purchase_order_items_table.id=$purchase_return_items_table.po_item_id
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $purchase_order_items_table.tax_id
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table2 ON tax_table2.id = $purchase_order_items_table.tax_id2
        WHERE $purchase_return_items_table.deleted=0 and $purchase_returns_table.deleted=0 $where
        ORDER BY $purchase_return_items_table.sort ASC";
        return $this->db->query($sql);
    }

    function get_sum_items($shimpment_id = 0) {
        $purchase_return_items_table = $this->db->dbprefix('purchase_return_items');
        $purchase_returns_table = $this->db->dbprefix('purchase_returns');
        $where = "";
        if ($shimpment_id) {
            $where .= " AND $purchase_returns_table.shimpment_id=$shimpment_id";
        }

        $sql = "SELECT SUM(quantity) as sum
        FROM $purchase_return_items_table
        LEFT JOIN $purchase_returns_table ON $purchase_returns_table.id=$purchase_return_items_table.purchase_return_id
        WHERE $purchase_return_items_table.deleted=0 and $purchase_returns_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_delivered_items($po_item_id = 0) {
        $purchase_return_items_table = $this->db->dbprefix('purchase_return_items');
        $purchase_returns_table = $this->db->dbprefix('purchase_returns');
        $where = "";
        if ($po_item_id) {
            $where .= " AND $purchase_return_items_table.po_item_id=$po_item_id";
        }

        $sql = "SELECT SUM(quantity) as sum
        FROM $purchase_return_items_table
        LEFT JOIN $purchase_returns_table ON $purchase_returns_table.id=$purchase_return_items_table.purchase_return_id
        WHERE $purchase_return_items_table.deleted=0 and $purchase_returns_table.deleted=0 $where";
        return $this->db->query($sql)->row()->sum;
    }

}
