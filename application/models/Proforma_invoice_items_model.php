<?php

class Proforma_invoice_items_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'proforma_invoice_items';
        parent::__construct($this->table);
        $this->init_activity_log($this->table, $this->table);
    }

    function get_details($options = array()) {
        $invoice_items_table = $this->db->dbprefix('proforma_invoice_items');
        $items_table = $this->db->dbprefix('items');
        $invoices_table = $this->db->dbprefix('proforma_invoices');
        $clients_table = $this->db->dbprefix('clients');
        $taxes_table = $this->db->dbprefix('taxes');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $invoice_items_table.id=$id";
        }
        $invoice_id = get_array_value($options, "invoice_id");
        if ($invoice_id) {
            $where .= " AND $invoice_items_table.invoice_id=$invoice_id";
        }

        $item_type = get_array_value($options, "item_type");
        if ($item_type) {
            $where .= " AND $items_table.item_type= '$item_type'";
        }

        $sql = "SELECT $invoice_items_table.*, (SELECT $clients_table.currency_symbol FROM $clients_table WHERE $clients_table.id=$invoices_table.client_id limit 1) AS currency_symbol, tax_table.percentage AS tax_percentage, tax_table2.percentage AS tax_percentage2
        FROM $invoice_items_table
        LEFT JOIN $invoices_table ON $invoices_table.id=$invoice_items_table.invoice_id
        LEFT JOIN $items_table ON $items_table.id=$invoice_items_table.item_id
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $invoice_items_table.tax_id
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table2 ON tax_table2.id = $invoice_items_table.tax_id2
        WHERE $invoice_items_table.deleted=0 $where
        ORDER BY $invoice_items_table.sort ASC";
        return $this->db->query($sql);
    }


    

    function get_total() {
        $sql = "";
    }

    function get_item_suggestion($keyword = "", $allowed_levels = array()) {
        $items_table = $this->db->dbprefix('items');
        
        $where = '';

        if (is_array($allowed_levels) && count($allowed_levels)) {
            $allowed_levels[] = 0;
            $allowed_levels = join(",", $allowed_levels);
            $where .= " AND $items_table.item_level IN($allowed_levels)";
        } else if ($this->login_user->is_admin) {
            $where = '';
        } else {
            $where .= " AND $items_table.item_level = 0";
        }

        $sql = "SELECT $items_table.title
        FROM $items_table
        WHERE $items_table.deleted=0  AND $items_table.title LIKE '%$keyword%' $where
        LIMIT 10 
        ";
        return $this->db->query($sql)->result();
    }

    function get_items($allowed_levels = array(), $product = null) {
        $items_table = $this->db->dbprefix('items');
        
        $where = '';

        if($product) {
            $where .= " AND $items_table.item_type = 'product'";
        }

        if (is_array($allowed_levels) && count($allowed_levels)) {
            $allowed_levels[] = 0;
            $allowed_levels = join(",", $allowed_levels);
            $where .= " AND $items_table.item_level IN($allowed_levels)";
        } else if ($this->login_user->is_admin) {
            $where .= '';
        } else {
            $where .= " AND $items_table.item_level = 0";
        }

        $sql = "SELECT $items_table.*
        FROM $items_table
        WHERE $items_table.deleted=0 $where 
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

    function get_sum_items($invoice_id) {
        $invoice_items_table = $this->db->dbprefix('proforma_invoice_items');
        $invoices_table = $this->db->dbprefix('proforma_invoices');

        $sql = "SELECT SUM(quantity) as sum
        FROM $invoice_items_table
        LEFT JOIN $invoices_table ON $invoices_table.id=$invoice_items_table.invoice_id
        WHERE $invoice_items_table.deleted=0 and $invoices_table.deleted=0 and $invoice_items_table.invoice_id=$invoice_id";
        return $this->db->query($sql);
    }
}
