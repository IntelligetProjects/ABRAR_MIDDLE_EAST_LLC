<?php

class Delivery_note_items_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'delivery_note_items';
        parent::__construct($this->table);
        $this->init_activity_log($this->table, $this->table);
    }

    function get_details($options = array()) {
        $delivery_note_items_table = $this->db->dbprefix('delivery_note_items');
        $delivery_notes_table = $this->db->dbprefix('delivery_notes');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $delivery_note_items_table.id=$id";
        }
        $invoice_item_id = get_array_value($options, "invoice_item_id");
        if ($invoice_item_id) {
            $where .= " AND $delivery_note_items_table.invoice_item_id = $invoice_item_id";
        }
        $delivery_note_id = get_array_value($options, "delivery_note_id");
        if ($delivery_note_id) {
            $where .= " AND $delivery_note_items_table.delivery_note_id=$delivery_note_id";
        }
        $quantity = get_array_value($options, "quantity");
        if ($quantity) {
            $where .= " AND $delivery_note_items_table.quantity != 0";
        }

        $sql = "SELECT $delivery_note_items_table.*
        FROM $delivery_note_items_table
        LEFT JOIN $delivery_notes_table ON $delivery_notes_table.id=$delivery_note_items_table.delivery_note_id
        WHERE $delivery_note_items_table.deleted=0 and $delivery_notes_table.deleted=0 $where
        ORDER BY $delivery_note_items_table.sort ASC";
        return $this->db->query($sql);
    }

    function get_sum_items($invoice_id = 0) {
        $delivery_note_items_table = $this->db->dbprefix('delivery_note_items');
        $delivery_notes_table = $this->db->dbprefix('delivery_notes');
        $where = "";
        if ($invoice_id) {
            $where .= " AND $delivery_notes_table.invoice_id=$invoice_id";
        }

        $sql = "SELECT SUM(quantity) as sum
        FROM $delivery_note_items_table
        LEFT JOIN $delivery_notes_table ON $delivery_notes_table.id=$delivery_note_items_table.delivery_note_id
        WHERE $delivery_note_items_table.deleted=0 and $delivery_notes_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_delivered_items($invoice_item_id = 0) {
        $delivery_note_items_table = $this->db->dbprefix('delivery_note_items');
        $delivery_notes_table = $this->db->dbprefix('delivery_notes');
        $where = "";
        if ($invoice_item_id) {
            $where .= " AND $delivery_note_items_table.invoice_item_id=$invoice_item_id";
        }

        $sql = "SELECT SUM(quantity) as sum
        FROM $delivery_note_items_table
        LEFT JOIN $delivery_notes_table ON $delivery_notes_table.id=$delivery_note_items_table.delivery_note_id
        WHERE $delivery_note_items_table.deleted=0 and $delivery_notes_table.deleted=0 $where";
        return $this->db->query($sql)->row()->sum;
    }

}
