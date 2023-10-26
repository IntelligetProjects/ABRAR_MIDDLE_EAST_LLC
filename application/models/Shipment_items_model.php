<?php

class Shipment_items_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'shipment_items';
        parent::__construct($this->table);
        $this->init_activity_log($this->table, $this->table);
    }

    function get_details($options = array()) {
        $shipment_items_table = $this->db->dbprefix('shipment_items');
        $shipments_table = $this->db->dbprefix('shipments');
        $purchase_order_items_table = $this->db->dbprefix('purchase_order_items');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $shipment_items_table.id=$id";
        }
        $po_item_id = get_array_value($options, "po_item_id");
        if ($po_item_id) {
            $where .= " AND $shipment_items_table.po_item_id = $po_item_id";
        }
        $shipment_id = get_array_value($options, "shipment_id");
        if ($shipment_id) {
            $where .= " AND $shipment_items_table.shipment_id=$shipment_id";
        }
        $quantity = get_array_value($options, "quantity");
        if ($quantity) {
            $where .= " AND $shipment_items_table.quantity != 0";
        }

        $sql = "SELECT $shipment_items_table.*, $purchase_order_items_table.title, $purchase_order_items_table.description, $purchase_order_items_table.unit_type
        FROM $shipment_items_table
        LEFT JOIN $shipments_table ON $shipments_table.id=$shipment_items_table.shipment_id
        LEFT JOIN $purchase_order_items_table ON $purchase_order_items_table.id=$shipment_items_table.po_item_id
        WHERE $shipment_items_table.deleted=0 and $shipments_table.deleted=0 $where
        ORDER BY $shipment_items_table.sort ASC";
        return $this->db->query($sql);
    }

    function get_sum_items($shipment_id = 0) {
        $shipment_items_table = $this->db->dbprefix('shipment_items');
        $shipments_table = $this->db->dbprefix('shipments');
        $where = "";
        if ($shipment_id) {
            $where .= " AND $shipments_table.purchase_order_id=$shipment_id";
        }

        $sql = "SELECT SUM(quantity) as sum
        FROM $shipment_items_table
        LEFT JOIN $shipments_table ON $shipments_table.id=$shipment_items_table.shipment_id
        WHERE $shipment_items_table.deleted=0 and $shipments_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_delivered_items($po_item_id = 0) {
        $shipment_items_table = $this->db->dbprefix('shipment_items');
        $shipments_table = $this->db->dbprefix('shipments');
        $where = "";
        if ($po_item_id) {
            $where .= " AND $shipment_items_table.po_item_id=$po_item_id";
        }

        $sql = "SELECT SUM(quantity) as sum
        FROM $shipment_items_table
        LEFT JOIN $shipments_table ON $shipments_table.id=$shipment_items_table.shipment_id
        WHERE $shipment_items_table.deleted=0 and $shipments_table.deleted=0 $where";
        return $this->db->query($sql)->row()->sum;
    }

}
