<?php

class Purchase_order_payments_model extends Crud_model
{

    private $table = null;

    function __construct()
    {
        $this->table = 'purchase_order_payments';
        parent::__construct($this->table);
        $this->init_activity_log($this->table, $this->table);
    }

    function get_details($options = array())
    {
        $purchase_order_payments_table = $this->db->dbprefix('purchase_order_payments');
        $purchase_orders_table = $this->db->dbprefix('purchase_orders');
        $payment_methods_table = $this->db->dbprefix('payment_methods');
        $suppliers_table = $this->db->dbprefix('suppliers');
        $users_table = $this->db->dbprefix('users');

        $where = "";

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $purchase_order_payments_table.id=$id";
        }

        $purchase_order_id = get_array_value($options, "purchase_order_id");
        if ($purchase_order_id) {
            $where .= " AND $purchase_order_payments_table.purchase_order_id=$purchase_order_id";
        }

        $supplier_id = get_array_value($options, "supplier_id");
        if ($supplier_id) {
            $where .= " AND $purchase_orders_table.supplier_id=$supplier_id";
        }

        $project_id = get_array_value($options, "project_id");
        if ($project_id) {
            $where .= " AND $purchase_orders_table.project_id=$project_id";
        }

        $payment_method_id = get_array_value($options, "payment_method_id");
        if ($payment_method_id) {
            $where .= " AND $purchase_order_payments_table.payment_method_id=$payment_method_id";
        }

        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($purchase_order_payments_table.payment_date BETWEEN '$start_date' AND '$end_date') ";
        }

        $currency = get_array_value($options, "currency");
        if ($currency) {
            $where .= $this->_get_suppliers_of_currency_query($currency, $purchase_orders_table, $suppliers_table);
        }

        $status = get_array_value($options, "status");
        if ($status) {
            $where .= " AND $purchase_order_payments_table.status='$status'";
        }


        //add filter by cost center id
        if (!can_view_all_cost_centers_data() && $this->login_user->cost_center_id > 0) {
            $cost_center_id = $this->login_user->cost_center_id;
            $where .= " AND $purchase_orders_table.cost_center_id = $cost_center_id";
        }

        $sql = "SELECT $purchase_order_payments_table.*, $purchase_orders_table.supplier_id, $purchase_orders_table.currency_rate_at_creation , (SELECT $suppliers_table.currency_symbol FROM $suppliers_table WHERE $suppliers_table.id=$purchase_orders_table.supplier_id limit 1) AS currency_symbol, $payment_methods_table.title AS payment_method_title,  (SELECT $suppliers_table.company_name FROM $suppliers_table WHERE $suppliers_table.id=$purchase_orders_table.supplier_id limit 1) AS company_name, CONCAT($users_table.first_name,' ',$users_table.last_name) as user_name, $users_table.image as user_image
        FROM $purchase_order_payments_table
        LEFT JOIN $purchase_orders_table ON $purchase_orders_table.id=$purchase_order_payments_table.purchase_order_id
        LEFT JOIN $payment_methods_table ON $payment_methods_table.id = $purchase_order_payments_table.payment_method_id
        LEFT JOIN $users_table ON $users_table.id=$purchase_order_payments_table.created_by
        WHERE $purchase_order_payments_table.deleted=0 AND $purchase_orders_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_yearly_payments_chart($year, $currency = "")
    {
        $payments_table = $this->db->dbprefix('purchase_order_payments');
        $purchase_orders_table = $this->db->dbprefix('purchase_orders');
        $suppliers_table = $this->db->dbprefix('suppliers');

        $where = "";
        if ($currency) {
            $where = $this->_get_suppliers_of_currency_query($currency, $purchase_orders_table, $suppliers_table);
        }

        $payments = "SELECT SUM($payments_table.amount) AS total, MONTH($payments_table.payment_date) AS month
            FROM $payments_table
            LEFT JOIN $purchase_orders_table ON $purchase_orders_table.id=$payments_table.purchase_order_id
            WHERE $payments_table.deleted=0 AND YEAR($payments_table.payment_date)= $year AND $purchase_orders_table.deleted=0 AND $purchase_orders_table.approval_status='approved' $where
            GROUP BY MONTH($payments_table.payment_date)";
        return $this->db->query($payments)->result();
    }
}
