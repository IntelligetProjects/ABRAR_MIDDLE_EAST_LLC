<?php

class Purchase_orders_model extends Crud_model
{

    private $table = null;

    function __construct()
    {
        $this->table = 'purchase_orders';
        parent::__construct($this->table,true);
        $this->init_activity_log($this->table, $this->table);
    }

    function get_details($options = array())
    {
        $purchase_orders_table = $this->db->dbprefix('purchase_orders');
        $suppliers_table = $this->db->dbprefix('suppliers');
        $projects_table = $this->db->dbprefix('projects');
        $purchase_order_payments_table = $this->db->dbprefix('purchase_order_payments');
        $purchase_order_items_table = $this->db->dbprefix('purchase_order_items');
        $users_table = $this->db->dbprefix('users');
        $log_table = $this->db->dbprefix('activity_logs');
        $taxes_table = $this->db->dbprefix('taxes');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $purchase_orders_table.id=$id";
        }
        $supplier_id = get_array_value($options, "supplier_id");
        if ($supplier_id) {
            $where .= " AND $purchase_orders_table.supplier_id=$supplier_id";
        }

        $exclude_draft = get_array_value($options, "exclude_draft");
        if ($exclude_draft) {
            $where .= " AND $purchase_orders_table.status!='draft' ";
        }

        $project_id = get_array_value($options, "project_id");
        if ($project_id) {
            $where .= " AND $purchase_orders_table.project_id=$project_id";
        }

        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($purchase_orders_table.purchase_order_date BETWEEN '$start_date' AND '$end_date') ";
        }

        //$now = get_my_local_time("Y-m-d");
        //  $options['status'] = "draft";
        $status = get_array_value($options, "status");


        $purchase_order_value_calculation_query = $this->_get_purchase_order_value_calculation_query($purchase_orders_table);


        $purchase_order_value_calculation = "TRUNCATE($purchase_order_value_calculation_query,2)";

        if ($status === "draft") {
            $where .= " AND $purchase_orders_table.status='draft' AND IFNULL(payments_table.payment_received,0)<=0";
        } else if ($status === "not_paid") {
            $where .= " AND $purchase_orders_table.status !='draft' AND IFNULL(payments_table.payment_received,0)<=0";
        } else if ($status === "partially_paid") {
            $where .= " AND IFNULL(payments_table.payment_received,0)>0 AND IFNULL(payments_table.payment_received,0)<$purchase_order_value_calculation";
        } else if ($status === "fully_paid") {
            $where .= " AND TRUNCATE(IFNULL(payments_table.payment_received,0),2)>=$purchase_order_value_calculation";
        }



        $currency = get_array_value($options, "currency");
        if ($currency) {
            $where .= $this->_get_suppliers_of_currency_query($currency, $purchase_orders_table, $suppliers_table);
        }

        /////

        $allowed_members = get_array_value($options, "allowed_members");
        if (is_array($allowed_members) && count($allowed_members)) {
            $allowed_members = join(",", $allowed_members);
            $where .= " AND log.user_id IN($allowed_members)";
        }

        //add filter by cost center id
        if (!can_view_all_cost_centers_data() && $this->login_user->cost_center_id > 0) {
            $cost_center_id = $this->login_user->cost_center_id;
            $where .= " AND $purchase_orders_table.cost_center_id = $cost_center_id";
        }


        $sql = "SELECT $purchase_orders_table.*, $suppliers_table.currency, $suppliers_table.currency_symbol, $suppliers_table.company_name, $projects_table.title AS project_title,
           $purchase_order_value_calculation_query AS purchase_order_value, items_table.tax as tax_value, IFNULL(payments_table.payment_received,0) AS payment_received, log.user_id AS log_user_id
        FROM $purchase_orders_table
        LEFT JOIN $suppliers_table ON $suppliers_table.id= $purchase_orders_table.supplier_id
        LEFT JOIN (SELECT a.*, CONCAT_WS(' ',b.first_name,b.last_name) as create_user, b.id as user_id  
                   FROM $log_table a
                   LEFT JOIN $users_table b on (a.created_by = b.id)
                   WHERE log_type = 'purchase_orders' and action = 'created') as log ON ($purchase_orders_table.id = log.log_type_id)
        LEFT JOIN $projects_table ON $projects_table.id= $purchase_orders_table.project_id
        LEFT JOIN (SELECT purchase_order_id, SUM(amount) AS payment_received FROM $purchase_order_payments_table WHERE deleted=0 AND status='approved' GROUP BY purchase_order_id) AS payments_table ON payments_table.purchase_order_id = $purchase_orders_table.id 
        LEFT JOIN (SELECT purchase_order_id, SUM(total) AS purchase_order_value, SUM($purchase_order_items_table.total*tax_table.percentage*0.01) AS tax FROM $purchase_order_items_table
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $purchase_order_items_table.tax_id
        WHERE $purchase_order_items_table.deleted=0 GROUP BY purchase_order_id) AS items_table ON items_table.purchase_order_id = $purchase_orders_table.id 
        WHERE $purchase_orders_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_purchase_order_total_summary($purchase_order_id = 0)
    {
        $purchase_order_items_table = $this->db->dbprefix('purchase_order_items');
        $purchase_order_payments_table = $this->db->dbprefix('purchase_order_payments');
        $purchase_orders_table = $this->db->dbprefix('purchase_orders');
        $suppliers_table = $this->db->dbprefix('suppliers');
        $taxes_table = $this->db->dbprefix('taxes');

        $item_sql = "SELECT SUM($purchase_order_items_table.total) AS purchase_order_subtotal, SUM($purchase_order_items_table.total*tax_table.percentage*0.01) AS tax, SUM($purchase_order_items_table.total*tax_table2.percentage*0.01) AS tax2
        FROM $purchase_order_items_table
        LEFT JOIN $purchase_orders_table ON $purchase_orders_table.id= $purchase_order_items_table.purchase_order_id 
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $purchase_order_items_table.tax_id
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table2 ON tax_table2.id = $purchase_order_items_table.tax_id2    
        WHERE $purchase_order_items_table.deleted=0 AND $purchase_order_items_table.purchase_order_id=$purchase_order_id AND $purchase_orders_table.deleted=0";
        $item = $this->db->query($item_sql)->row();

        $payment_sql = "SELECT SUM($purchase_order_payments_table.amount) AS total_paid
        FROM $purchase_order_payments_table
        WHERE $purchase_order_payments_table.deleted=0 AND $purchase_order_payments_table.purchase_order_id=$purchase_order_id AND $purchase_order_payments_table.status='approved'";
        $payment = $this->db->query($payment_sql)->row();

        $purchase_order_sql = "SELECT $purchase_orders_table.*
        FROM $purchase_orders_table
        WHERE $purchase_orders_table.deleted=0 AND $purchase_orders_table.id=$purchase_order_id";
        $purchase_order = $this->db->query($purchase_order_sql)->row();

        $supplier_sql = "SELECT $suppliers_table.currency_symbol, $suppliers_table.currency FROM $suppliers_table WHERE $suppliers_table.id=$purchase_order->supplier_id";
        $supplier = $this->db->query($supplier_sql)->row();


        $result = new stdClass();
        $result->purchase_order_subtotal = $item->purchase_order_subtotal;
        $result->tax_name = /*$estimate->tax_name*/ lang("tax");
        $result->tax_name2 = /*$estimate->tax_name2*/ lang("tax2");

        $purchase_order_subtotal = $result->purchase_order_subtotal;

        $result->purchase_order_total = $item->purchase_order_subtotal;

        $result->tax = $item->tax;

        $result->tax2 = $item->tax2;

        $result->purchase_order_total = $item->purchase_order_subtotal + $result->tax + $result->tax2;

        $result->total_paid = $payment->total_paid;

        $result->currency_symbol = $supplier->currency_symbol ? $supplier->currency_symbol : get_setting("currency_symbol");
        $result->currency = $supplier->currency ? $supplier->currency : get_setting("default_currency");

        $result->balance_due = number_format($result->purchase_order_total, 2, ".", "") - number_format($payment->total_paid, 2, ".", "");

        return $result;
    }

    function get_used_currencies_of_supplier()
    {
        $suppliers_table = $this->db->dbprefix('suppliers');
        $default_currency = get_setting("default_currency");

        $sql = "SELECT $suppliers_table.currency
            FROM $suppliers_table
            WHERE $suppliers_table.deleted=0 AND $suppliers_table.currency!='' AND $suppliers_table.currency!='$default_currency'
            GROUP BY $suppliers_table.currency";

        return $this->db->query($sql);
    }

    function get_purchase_orders_total_and_paymnts()
    {
        $purchase_orders_table = $this->db->dbprefix('purchase_orders');
        $purchase_order_payments_table = $this->db->dbprefix('purchase_order_payments');
        $purchase_order_items_table = $this->db->dbprefix('purchase_order_items');
        $taxes_table = $this->db->dbprefix('taxes');
        $info = new stdClass();


        $payments = "SELECT SUM($purchase_order_payments_table.amount) AS total
            FROM $purchase_order_payments_table
            LEFT JOIN $purchase_orders_table ON $purchase_orders_table.id=$purchase_order_payments_table.purchase_order_id    
            WHERE $purchase_order_payments_table.deleted=0 AND $purchase_orders_table.deleted=0";
        $info->payments = $this->db->query($payments)->result();

        $purchase_order_value_calculation_query = $this->_get_purchase_order_value_calculation_query($purchase_orders_table);

        $purchase_orders = "SELECT SUM(total) AS total FROM (SELECT $purchase_order_value_calculation_query AS total
            FROM $purchase_orders_table
            LEFT JOIN (SELECT purchase_order_id, SUM(total + 0.01*IFNULL(tax_table.percentage,0)*total + 0.01*IFNULL(tax_table2.percentage,0)*total)  AS purchase_order_value FROM $purchase_order_items_table
            LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $purchase_order_items_table.tax_id
            LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table2 ON tax_table2.id = $purchase_order_items_table.tax_id2 WHERE deleted=0 GROUP BY purchase_order_id) AS items_table ON items_table.purchase_order_id = $purchase_orders_table.id 
            WHERE $purchase_orders_table.deleted=0 AND $purchase_orders_table.status='not_paid') as details_table";

        $payments_total = $this->db->query($payments)->row()->total;
        $purchase_orders_total = $this->db->query($purchase_orders)->row()->total;

        $info->payments_total = $payments_total;
        $info->purchase_orders_total = (($purchase_orders_total > $payments_total) && ($purchase_orders_total - $payments_total) < 0.05) ? $payments_total : $purchase_orders_total;
        $info->due = $info->purchase_orders_total - $info->payments_total;
        return $info;
    }

    //update purchase_order status
    function update_purchase_order_status($purchase_order_id = 0, $status = "not_paid")
    {
        $status_data = array("status" => $status);
        return $this->save($status_data, $purchase_order_id);
    }

    //update purchase_order status
    function update_approval_status($purchase_order_id = 0, $status = "not_approved")
    {
        $status_data = array("approval_status" => $status);
        return $this->save($status_data, $purchase_order_id);
    }

    //get purchase_orders dropdown list
    function get_purchase_orders_dropdown_list()
    {
        $purchase_orders_table = $this->db->dbprefix('purchase_orders');

        $sql = "SELECT $purchase_orders_table.id FROM $purchase_orders_table
                        WHERE $purchase_orders_table.deleted=0 
                        ORDER BY $purchase_orders_table.id DESC";

        return $this->db->query($sql);
    }


    //get label suggestions
    function get_label_suggestions()
    {
        $purchase_orders_table = $this->db->dbprefix('purchase_orders');
        $sql = "SELECT GROUP_CONCAT(labels) as label_groups
        FROM $purchase_orders_table
        WHERE $purchase_orders_table.deleted=0";
        return $this->db->query($sql)->row()->label_groups;
    }

    //get purchase_order last id
    function get_last_purchase_order_id()
    {
        $purchase_orders_table = $this->db->dbprefix('purchase_orders');

        $sql = "SELECT MAX($purchase_orders_table.id) AS last_id FROM $purchase_orders_table";

        return $this->db->query($sql)->row()->last_id;
    }

    function get_invoice_products($invoice_id)
    {
        $invoices_table = $this->db->dbprefix('purchase_order_items');
        $items_table = $this->db->dbprefix('items');

        $sql = "SELECT $invoices_table.*
            FROM $invoices_table
            LEFT JOIN $items_table ON $invoices_table.item_id = $items_table.id
            WHERE $invoices_table.purchase_order_id = $invoice_id AND $invoices_table.deleted = 0 AND $items_table.item_type = 'product'";

        return $this->db->query($sql)->result();
    }
    function get_invoice_item_inventory($item_id)
    {
        $purchase_order_items_table = $this->db->dbprefix('purchase_order_items');
        $purchase_orders_table = $this->db->dbprefix('purchase_orders');

        $sql = "SELECT SUM($purchase_order_items_table.quantity) AS sum_qty, SUM($purchase_order_items_table.total) AS sum_total
        FROM $purchase_order_items_table
        LEFT JOIN $purchase_orders_table ON $purchase_orders_table.id=$purchase_order_items_table.purchase_order_id
        WHERE $purchase_order_items_table.deleted=0 AND $purchase_orders_table.deleted = 0 AND $purchase_orders_table.approval_status = 'approved' AND $purchase_order_items_table.item_id = $item_id";

        return $this->db->query($sql)->row();
    }

    function get_details_for_vat_report($options = array())
    {
        $purchase_orders_table = $this->db->dbprefix('purchase_orders');
        $suppliers_table = $this->db->dbprefix('suppliers');
        $projects_table = $this->db->dbprefix('projects');
        $purchase_order_payments_table = $this->db->dbprefix('purchase_order_payments');
        $purchase_order_items_table = $this->db->dbprefix('purchase_order_items');
        $users_table = $this->db->dbprefix('users');
        $log_table = $this->db->dbprefix('activity_logs');
        $taxes_table = $this->db->dbprefix('taxes');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $purchase_orders_table.id=$id";
        }
        $supplier_id = get_array_value($options, "supplier_id");
        if ($supplier_id) {
            $where .= " AND $purchase_orders_table.supplier_id=$supplier_id";
        }

        $exclude_draft = get_array_value($options, "exclude_draft");
        if ($exclude_draft) {
            $where .= " AND $purchase_orders_table.status!='draft' ";
        }

        $project_id = get_array_value($options, "project_id");
        if ($project_id) {
            $where .= " AND $purchase_orders_table.project_id=$project_id";
        }

        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($purchase_orders_table.id IN (SELECT purchase_order_id FROM $purchase_order_payments_table WHERE $purchase_order_payments_table.payment_date  BETWEEN '$start_date' AND '$end_date' AND  $purchase_order_payments_table.deleted=0 AND $purchase_order_payments_table.status='approved')) ";
        }

        //$now = get_my_local_time("Y-m-d");
        //  $options['status'] = "draft";
        $status = get_array_value($options, "status");


        $purchase_order_value_calculation_query = $this->_get_purchase_order_value_calculation_query($purchase_orders_table);


        $purchase_order_value_calculation = "TRUNCATE($purchase_order_value_calculation_query,2)";

        if ($status === "draft") {
            $where .= " AND $purchase_orders_table.status='draft' AND IFNULL(payments_table.payment_received,0)<=0";
        } else if ($status === "not_paid") {
            $where .= " AND $purchase_orders_table.status !='draft' AND IFNULL(payments_table.payment_received,0)<=0";
        } else if ($status === "partially_paid") {
            $where .= " AND IFNULL(payments_table.payment_received,0)>0 AND IFNULL(payments_table.payment_received,0)<$purchase_order_value_calculation";
        } else if ($status === "fully_paid") {
            $where .= " AND TRUNCATE(IFNULL(payments_table.payment_received,0),2)>=$purchase_order_value_calculation";
        }



        $currency = get_array_value($options, "currency");
        if ($currency) {
            $where .= $this->_get_suppliers_of_currency_query($currency, $purchase_orders_table, $suppliers_table);
        }

        /////

        $allowed_members = get_array_value($options, "allowed_members");
        if (is_array($allowed_members) && count($allowed_members)) {
            $allowed_members = join(",", $allowed_members);
            $where .= " AND log.user_id IN($allowed_members)";
        }

        /////
        $import_or_doemstic =  get_array_value($options, "type");
        if ($import_or_doemstic) {
            $where .= " AND $purchase_orders_table.type='$import_or_doemstic'";
        }


        //TODO: remove IF-ELSE , remove the code inside else if changes to database done 
        $sql = "SELECT $purchase_orders_table.*, $suppliers_table.currency, $suppliers_table.vat_number AS supplier_vat_number, $suppliers_table.currency_symbol, $suppliers_table.company_name, $projects_table.title AS project_title,
            $purchase_order_value_calculation_query AS purchase_order_value, items_table.tax as tax_value, IFNULL(payments_table.payment_received,0) AS payment_received, log.user_id AS log_user_id
         FROM $purchase_orders_table
         LEFT JOIN $suppliers_table ON $suppliers_table.id= $purchase_orders_table.supplier_id
         LEFT JOIN (SELECT a.*, CONCAT_WS(' ',b.first_name,b.last_name) as create_user, b.id as user_id  
                    FROM $log_table a
                    LEFT JOIN $users_table b on (a.created_by = b.id)
                    WHERE log_type = 'purchase_orders' and action = 'created') as log ON ($purchase_orders_table.id = log.log_type_id)
         LEFT JOIN $projects_table ON $projects_table.id= $purchase_orders_table.project_id
         LEFT JOIN (SELECT purchase_order_id, SUM(amount) AS payment_received FROM $purchase_order_payments_table WHERE deleted=0 GROUP BY purchase_order_id) AS payments_table ON payments_table.purchase_order_id = $purchase_orders_table.id 
         LEFT JOIN (SELECT purchase_order_id, SUM(total) AS purchase_order_value, SUM($purchase_order_items_table.total*tax_table.percentage*0.01) AS tax FROM $purchase_order_items_table
         LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $purchase_order_items_table.tax_id
         WHERE $purchase_order_items_table.deleted=0 GROUP BY purchase_order_id) AS items_table ON items_table.purchase_order_id = $purchase_orders_table.id 
         WHERE $purchase_orders_table.deleted=0 $where";
        return $this->db->query($sql);
    }
}
