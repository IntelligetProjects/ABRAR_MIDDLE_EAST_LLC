<?php

class Purchase_order_items_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'purchase_order_items';
        parent::__construct($this->table);
        $this->init_activity_log($this->table, $this->table);
    }

    function get_details($options = array()) {
        $purchase_order_items_table = $this->db->dbprefix('purchase_order_items');
        $purchase_orders_table = $this->db->dbprefix('purchase_orders');
        $suppliers_table = $this->db->dbprefix('suppliers');
        $taxes_table = $this->db->dbprefix('taxes');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $purchase_order_items_table.id=$id";
        }
        $purchase_order_id = get_array_value($options, "purchase_order_id");
        if ($purchase_order_id) {
            $where .= " AND $purchase_order_items_table.purchase_order_id=$purchase_order_id";
        }

        //add filter by cost center id
        if (!can_view_all_cost_centers_data() && $this->login_user->cost_center_id > 0) {
            $cost_center_id = $this->login_user->cost_center_id;
            $where .= " AND $purchase_orders_table.cost_center_id = $cost_center_id";
        }

        $sql = "SELECT $purchase_order_items_table.*, (SELECT $suppliers_table.currency_symbol FROM $suppliers_table WHERE $suppliers_table.id=$purchase_orders_table.supplier_id limit 1) AS currency_symbol, tax_table.percentage AS tax_percentage, tax_table2.percentage AS tax_percentage2
        ,$purchase_orders_table.currency_rate_at_creation
        FROM $purchase_order_items_table
        LEFT JOIN $purchase_orders_table ON $purchase_orders_table.id=$purchase_order_items_table.purchase_order_id
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $purchase_order_items_table.tax_id
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table2 ON tax_table2.id = $purchase_order_items_table.tax_id2
        WHERE $purchase_order_items_table.deleted=0 $where
        ORDER BY $purchase_order_items_table.sort ASC";
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


    function get_sum_items($purchase_order_id) {
        $invoice_items_table = $this->db->dbprefix('purchase_order_items');
        $invoices_table = $this->db->dbprefix('purchase_orders');

        $sql = "SELECT SUM(quantity) as sum
        FROM $invoice_items_table
        LEFT JOIN $invoices_table ON $invoices_table.id=$invoice_items_table.purchase_order_id
        WHERE $invoice_items_table.deleted=0 and $invoices_table.deleted=0 and $invoice_items_table.purchase_order_id=$purchase_order_id";
        return $this->db->query($sql);
    }

}
