<?php

class Purchase_returns_model extends Crud_model
{

    private $table = null;

    function __construct()
    {
        $this->table = 'purchase_returns';
        parent::__construct($this->table);
        $this->init_activity_log($this->table, $this->table);
    }

    function get_details($options = array())
    {
        $purchase_returns_table = $this->db->dbprefix('purchase_returns');
        $suppliers_table = $this->db->dbprefix('suppliers');
        $purchase_orders_table = $this->db->dbprefix('purchase_orders');
        $users_table = $this->db->dbprefix('users');
        $log_table = $this->db->dbprefix('activity_logs');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $purchase_returns_table.id=$id";
        }
        $supplier_id = get_array_value($options, "supplier_id");
        if ($supplier_id) {
            $where .= " AND $purchase_returns_table.supplier_id=$supplier_id";
        }

        $exclude_draft = get_array_value($options, "exclude_draft");
        if ($exclude_draft) {
            $where .= " AND $purchase_returns_table.status!='draft' ";
        }


        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($purchase_returns_table.date BETWEEN '$start_date' AND '$end_date') ";
        }

        $allowed_members = get_array_value($options, "allowed_members");
        if (is_array($allowed_members) && count($allowed_members)) {
            $allowed_members = join(",", $allowed_members);
            $where .= " AND log.user_id IN($allowed_members)";
        }

        //add filter by cost center id
        if ($this->login_user->cost_center_id > 0) {
            $cost_center_id = $this->login_user->cost_center_id;
            $where .= " AND $suppliers_table.cost_center_id = $cost_center_id";
        }

        $now = get_my_local_time("Y-m-d");
        $status = get_array_value($options, "status");


        $sql = "SELECT $purchase_returns_table.*, $suppliers_table.company_name, log.user_id as log_user_id,
           log.created_at as create_time, log.create_user as create_user
        FROM $purchase_returns_table
        LEFT JOIN $suppliers_table ON $suppliers_table.id= $purchase_returns_table.supplier_id
        LEFT JOIN $purchase_orders_table ON $purchase_orders_table.id= $purchase_returns_table.purchase_order_id
        LEFT JOIN (SELECT a.*, CONCAT_WS(' ',b.first_name,b.last_name) as create_user, b.id as user_id  
                   FROM $log_table a
                   LEFT JOIN $users_table b on (a.created_by = b.id)
                   WHERE log_type = 'purchase_returns' and action = 'created') as log ON ($purchase_returns_table.id = log.log_type_id)
        WHERE $purchase_returns_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    //update e status
    function update_approval_status($id = 0, $status = "not_approved")
    {
        $status_data = array("status" => $status);
        return $this->save($status_data, $id);
    }

    function get_total_summary($purchase_order_id = 0)
    {
        $purchase_return_items_table = $this->db->dbprefix('purchase_return_items');
        $purchase_returns_table = $this->db->dbprefix('purchase_returns');
        $purchase_order_items_table = $this->db->dbprefix('purchase_order_items');
        $purchase_order_payments_table = $this->db->dbprefix('purchase_order_payments');
        $purchase_orders_table = $this->db->dbprefix('purchase_orders');
        $suppliers_table = $this->db->dbprefix('suppliers');
        $taxes_table = $this->db->dbprefix('taxes');

        $item_sql = "SELECT SUM($purchase_order_items_table.rate*$purchase_return_items_table.quantity) AS purchase_order_subtotal, SUM($purchase_order_items_table.rate*$purchase_return_items_table.quantity*tax_table.percentage*0.01) AS tax, SUM($purchase_order_items_table.rate*$purchase_return_items_table.quantity*tax_table2.percentage*0.01) AS tax2
        FROM $purchase_return_items_table

        LEFT JOIN $purchase_returns_table ON $purchase_returns_table.id=$purchase_return_items_table.purchase_return_id
        LEFT JOIN $purchase_order_items_table ON $purchase_order_items_table.id=$purchase_return_items_table.po_item_id

        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $purchase_order_items_table.tax_id
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table2 ON tax_table2.id = $purchase_order_items_table.tax_id2

        WHERE $purchase_return_items_table.deleted=0 AND $purchase_return_items_table.purchase_return_id=$purchase_order_id AND $purchase_returns_table.deleted=0";
        $item = $this->db->query($item_sql)->row();


        $result = new stdClass();
        $result->purchase_order_subtotal = $item->purchase_order_subtotal;
        $result->tax_name = /*$estimate->tax_name*/ lang("tax");
        $result->tax_name2 = /*$estimate->tax_name2*/ lang("tax2");

        $purchase_order_subtotal = $result->purchase_order_subtotal;


        $result->tax = $item->tax;

        $result->tax2 = $item->tax2;

        $result->purchase_order_total = $item->purchase_order_subtotal + $result->tax + $result->tax2;

        $result->total = number_format($result->purchase_order_total, 2, ".", "");

        return $result;
    }
}
