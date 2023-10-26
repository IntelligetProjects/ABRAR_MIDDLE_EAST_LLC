<?php

class Suppliers_model extends Crud_model
{

    private $table = null;

    function __construct()
    {
        $this->table = 'suppliers';
        parent::__construct($this->table,true);
        $this->init_activity_log($this->table, $this->table);
    }

    function get_details($options = array())
    {
        $suppliers_table = $this->db->dbprefix('suppliers');
        $purchase_orders_table = $this->db->dbprefix('purchase_orders');
        $purchase_order_payments_table = $this->db->dbprefix('purchase_order_payments');
        $purchase_order_items_table = $this->db->dbprefix('purchase_order_items');
        $log_table = $this->db->dbprefix('activity_logs');
        $user_table = $this->db->dbprefix('users');
        $taxes_table = $this->db->dbprefix('taxes');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $suppliers_table.id=$id";
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

        $purchase_order_value_calculation_query = "(SUM" . $this->_get_purchase_order_value_calculation_query($purchase_orders_table) . ")";

        $this->db->query('SET SQL_BIG_SELECTS=1');

        $purchase_order_value_select = "IFNULL(purchase_order_details.purchase_order_value,0)";
        $payment_value_select = "IFNULL(purchase_order_details.payment_received,0)";

        $sql = "SELECT $suppliers_table.*, $payment_value_select AS payment_received,
                IF((($purchase_order_value_select > $payment_value_select) AND ($purchase_order_value_select - $payment_value_select) <0.05), $payment_value_select, $purchase_order_value_select) AS purchase_order_value, log.user_id as log_user_id,
           log.created_at as create_time, log.create_user as create_user
        FROM $suppliers_table
        LEFT JOIN (SELECT supplier_id, SUM(payments_table.payment_received) as payment_received, $purchase_order_value_calculation_query as purchase_order_value FROM $purchase_orders_table
                   LEFT JOIN (SELECT purchase_order_id, SUM(amount) AS payment_received FROM $purchase_order_payments_table WHERE deleted=0 GROUP BY purchase_order_id) AS payments_table ON payments_table.purchase_order_id=$purchase_orders_table.id AND $purchase_orders_table.deleted=0 AND $purchase_orders_table.approval_status='approved'
                   LEFT JOIN (SELECT purchase_order_id, SUM(total + IFNULL((total*tax_table.percentage*0.01),0) ) AS purchase_order_value 
                   FROM $purchase_order_items_table
                   LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $purchase_order_items_table.tax_id
                   WHERE $purchase_order_items_table.deleted=0 GROUP BY purchase_order_id) AS items_table 
                   ON items_table.purchase_order_id=$purchase_orders_table.id AND $purchase_orders_table.deleted=0 AND $purchase_orders_table.approval_status='approved'
                   WHERE $purchase_orders_table.approval_status='approved'
                   GROUP BY $purchase_orders_table.supplier_id    
                   ) AS purchase_order_details ON purchase_order_details.supplier_id= $suppliers_table.id 
        LEFT JOIN (SELECT a.*, CONCAT_WS(' ',b.first_name,b.last_name) as create_user, b.id as user_id  
                   FROM $log_table a
                   LEFT JOIN $user_table b on (a.created_by = b.id)
                   WHERE log_type = 'suppliers' and action = 'created') as log ON ($suppliers_table.id = log.log_type_id)
        WHERE $suppliers_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function is_duplicate_company_name($company_name, $id = 0)
    {

        $result = $this->get_all_where(array("company_name" => $company_name, "deleted" => 0));
        if ($result->num_rows() && $result->row()->id != $id) {
            return $result->row();
        } else {
            return false;
        }
    }
}
