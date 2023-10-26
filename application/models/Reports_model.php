<?php

class Reports_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'estimates';
        parent::__construct($this->table);
    }


    function get_total_value_by_user($user_id) {
        $estimate_items_table = $this->db->dbprefix('estimate_items');
        $estimates_table = $this->db->dbprefix('estimates');
        $user_table = $this->db->dbprefix('users');
        $log_table = $this->db->dbprefix('activity_logs');

        $estimate_value_calculation = "(
            IFNULL(items_table.estimate_value,0)- 
            IFNULL($estimates_table.discount_value,0)-
            (IFNULL($estimates_table.discount_percentage,0)/100*IFNULL(items_table.estimate_value,0))
           )";


    $sql = "SELECT sum($estimate_value_calculation) AS total_value, log.user_id as user_id
        FROM $estimates_table
        LEFT JOIN (SELECT estimate_id, SUM(total) AS estimate_value FROM $estimate_items_table WHERE deleted=0 GROUP BY estimate_id) AS items_table ON items_table.estimate_id = $estimates_table.id
        LEFT JOIN (SELECT $log_table.created_by as user_id, $log_table.log_type_id 
                   FROM $log_table
                   WHERE log_type = 'estimates' and action = 'created') as log 
            ON ($estimates_table.id = log.log_type_id)
        WHERE $estimates_table.deleted=0 
        Group by log.user_id 
        Having log.user_id = $user_id";
        return $this->db->query($sql)->result();

    }


    

    function get_monthly_value_by_user($user_id) {
        $estimate_items_table = $this->db->dbprefix('estimate_items');
        $estimates_table = $this->db->dbprefix('estimates');
        $user_table = $this->db->dbprefix('users');
        $log_table = $this->db->dbprefix('activity_logs');
        $month = date('m');
        $year = date('Y');

        $estimate_value_calculation = "(
            IFNULL(items_table.estimate_value,0)- 
            IFNULL($estimates_table.discount_value,0)-
            (IFNULL($estimates_table.discount_percentage,0)/100*IFNULL(items_table.estimate_value,0))
           )";


    $sql = "SELECT sum($estimate_value_calculation) AS total_value, MONTH(estimate_date) AS month, YEAR(estimate_date) AS year, log.user_id as user_id
        FROM $estimates_table
        LEFT JOIN (SELECT estimate_id, SUM(total) AS estimate_value FROM $estimate_items_table WHERE deleted=0 GROUP BY estimate_id) AS items_table ON items_table.estimate_id = $estimates_table.id
        LEFT JOIN (SELECT $log_table.created_by as user_id, $log_table.log_type_id 
                   FROM $log_table
                   WHERE log_type = 'estimates' and action = 'created') as log 
            ON ($estimates_table.id = log.log_type_id)
        WHERE $estimates_table.deleted=0 and MONTH(estimate_date) = $month and YEAR(estimate_date) = $year
        Group by log.user_id, month, year 
        Having log.user_id = $user_id";
        return $this->db->query($sql)->result();

    }

    function quotations_statistics() {
        $estimates_table = $this->db->dbprefix('estimates');
        $invoices_table = $this->db->dbprefix('invoices');
        $info = new stdClass();
        $year = get_my_local_time("Y");

        $total_invoices = "SELECT COUNT($invoices_table.id) AS total, MONTH($invoices_table.bill_date) AS month
            FROM $invoices_table  
            WHERE $invoices_table.deleted = 0 and $invoices_table.approval_status = 'approved'
            GROUP BY MONTH($invoices_table.bill_date)";

        $total_estimates = "SELECT COUNT($estimates_table.id) AS total, MONTH($estimates_table.estimate_date) AS month
            FROM $estimates_table  
            WHERE $estimates_table.deleted = 0 and $estimates_table.status = 'approved'
            GROUP BY MONTH($estimates_table.estimate_date)";
    
        $info->invoices = $this->db->query($total_invoices)->result();
        $info->estimates = $this->db->query($total_estimates)->result();
        return $info;
    }


    function monthly_value_by_user() {
        $estimate_items_table = $this->db->dbprefix('estimate_items');
        $estimates_table = $this->db->dbprefix('estimates');
        $user_table = $this->db->dbprefix('users');
        $log_table = $this->db->dbprefix('activity_logs');
        $month = date('m');
        $year = date('Y');

        $discountable_estimate_value = "IFNULL(items_table.estimate_value,0)";

        $discount_amount = "IF($estimates_table.discount_amount_type='percentage', IFNULL($estimates_table.discount_amount,0)/100* $discountable_estimate_value, $estimates_table.discount_amount)";

        $estimate_value_calculation = "(
            IFNULL(items_table.estimate_value,0) - $discount_amount
           )";


    $sql = "SELECT sum($estimate_value_calculation) AS total_value, MONTH(estimate_date) AS month, YEAR(estimate_date) AS year, log.user_id as user_id, log.create_user as create_user
        FROM $estimates_table
        LEFT JOIN (SELECT estimate_id, SUM(total) AS estimate_value FROM $estimate_items_table WHERE deleted=0 GROUP BY estimate_id) AS items_table ON items_table.estimate_id = $estimates_table.id
        LEFT JOIN (SELECT a.*, CONCAT_WS(' ',b.first_name,b.last_name) as create_user, b.id as user_id  
                   FROM $log_table a
                   LEFT JOIN $user_table b on (a.created_by = b.id)
                   WHERE log_type = 'estimates' and action = 'created') as log ON ($estimates_table.id = log.log_type_id)
        WHERE $estimates_table.deleted=0 and YEAR(estimate_date) = $year and $estimates_table.status = 'approved'
        Group by log.user_id, year";
        return $this->db->query($sql)->result();

    }

    function monthly_value_by_user_invoice() {
        $estimate_items_table = $this->db->dbprefix('invoice_items');
        $estimates_table = $this->db->dbprefix('invoices');
        $user_table = $this->db->dbprefix('users');
        $log_table = $this->db->dbprefix('activity_logs');
        $month = date('m');
        $year = date('Y');

        $discountable_estimate_value = "IFNULL(items_table.estimate_value,0)";

        $discount_amount = "IF($estimates_table.discount_amount_type='percentage', IFNULL($estimates_table.discount_amount,0)/100* $discountable_estimate_value, $estimates_table.discount_amount)";

        $estimate_value_calculation = "(
            IFNULL(items_table.estimate_value,0) - $discount_amount
           )";


    $sql = "SELECT sum($estimate_value_calculation) AS total_value, MONTH(bill_date) AS month, YEAR(bill_date) AS year, log.user_id as user_id, log.create_user as create_user
        FROM $estimates_table
        LEFT JOIN (SELECT invoice_id, SUM(total) AS estimate_value FROM $estimate_items_table WHERE deleted=0 GROUP BY invoice_id) AS items_table ON items_table.invoice_id = $estimates_table.id
        LEFT JOIN (SELECT a.*, CONCAT_WS(' ',b.first_name,b.last_name) as create_user, b.id as user_id  
                   FROM $log_table a
                   LEFT JOIN $user_table b on (a.created_by = b.id)
                   WHERE log_type = 'invoices' and action = 'created') as log ON ($estimates_table.id = log.log_type_id)
        WHERE $estimates_table.deleted=0 and YEAR(bill_date) = $year and $estimates_table.approval_status = 'approved'
        Group by log.user_id, year";
        return $this->db->query($sql)->result();

    }

    function get_payment_statistics($options = array()) {
        $payments_table = $this->db->dbprefix('invoice_payments');
        $payment_methods_table = $this->db->dbprefix('payment_methods');

        try {
            $this->db->query("SET sql_mode = ''");
        } catch (Exception $e) {
            
        }

        $sql = "SELECT COUNT($payments_table.id) AS total, $payment_methods_table.title
        FROM $payments_table
        LEFT JOIN $payment_methods_table ON $payments_table.payment_method_id = $payment_methods_table.id
        WHERE $payments_table.deleted=0 and $payments_table.status='approved'
        GROUP BY $payments_table.payment_method_id ORDER BY $payment_methods_table.id desc";
        return $this->db->query($sql)->result();
    }

    function get_estimates_count() {
        $estimates_table = $this->db->dbprefix('estimates');

        try {
            $this->db->query("SET sql_mode = ''");
        } catch (Exception $e) {
            
        }

        $sql = "SELECT COUNT($estimates_table.id) AS total, COUNT(DISTINCT $estimates_table.client_id) as total_clients
        FROM $estimates_table
        WHERE $estimates_table.deleted=0";
        return $this->db->query($sql)->result();
    }

    function get_invoices_count() {
        $estimates_table = $this->db->dbprefix('invoices');

        try {
            $this->db->query("SET sql_mode = ''");
        } catch (Exception $e) {
            
        }

        $sql = "SELECT COUNT($estimates_table.id) AS total, COUNT(DISTINCT $estimates_table.client_id) as total_clients
        FROM $estimates_table
        WHERE $estimates_table.deleted=0";
        return $this->db->query($sql)->result();
    }

    function get_projects($options = array()) {
        $projects_table = $this->db->dbprefix('projects');
        $project_members_table = $this->db->dbprefix('project_members');
        $clients_table = $this->db->dbprefix('clients');
        $expenses_table = $this->db->dbprefix('expenses');
        $payments_table = $this->db->dbprefix('invoice_payments');
        $invoices_table = $this->db->dbprefix('invoices');
        $po_payments_table = $this->db->dbprefix('purchase_order_payments');
        $po_table = $this->db->dbprefix('purchase_orders');
        $items_table = $this->db->dbprefix('items');
        $invoice_items_table = $this->db->dbprefix('invoice_items');
        $po_items_table = $this->db->dbprefix('purchase_order_items');

        
        $sql = "SELECT $projects_table.*, $clients_table.company_name, total_expenses_table.total_expenses as total_expense, total_pays_table.total_pays as total_pay, total_members_table.total_members as total_member,/* total_items_table.total_items as total_items*/total_po_pays_table.total_pays as total_po_pay
        FROM $projects_table

        LEFT JOIN $clients_table ON $clients_table.id= $projects_table.client_id

        LEFT JOIN (SELECT project_id, SUM(amount) AS total_expenses FROM $expenses_table WHERE deleted=0 and status='approved' GROUP BY project_id) AS  total_expenses_table ON total_expenses_table.project_id= $projects_table.id

        LEFT JOIN (SELECT $payments_table.*, $invoices_table.project_id, SUM(amount) AS total_pays FROM $payments_table
        LEFT JOIN $invoices_table ON $invoices_table.id = $payments_table.invoice_id 
        WHERE $payments_table.deleted=0 and $payments_table.status = 'approved' GROUP BY $invoices_table.project_id) AS total_pays_table ON total_pays_table.project_id = $projects_table.id

        LEFT JOIN (SELECT $po_payments_table.*, $po_table.project_id, SUM(amount) AS total_pays FROM $po_payments_table
        LEFT JOIN $po_table ON $po_table.id = $po_payments_table.purchase_order_id 
        WHERE $po_payments_table.deleted=0 and $po_payments_table.status = 'approved' GROUP BY $po_table.project_id) AS total_po_pays_table ON total_po_pays_table.project_id = $projects_table.id

        /*LEFT JOIN (SELECT $invoice_items_table.*, $invoices_table.project_id, SUM($items_table.cost*$invoice_items_table.quantity) AS total_items FROM $invoice_items_table
        LEFT JOIN $invoices_table ON $invoices_table.id = $invoice_items_table.invoice_id
        LEFT JOIN $items_table ON $items_table.id = $invoice_items_table.item_id 
        WHERE $invoice_items_table.deleted=0 AND $invoices_table.deleted=0 GROUP BY $invoices_table.project_id) AS  total_items_table ON total_items_table.project_id = $projects_table.id*/


        LEFT JOIN (SELECT project_id, COUNT(id) AS total_members FROM $project_members_table WHERE deleted=0 GROUP BY project_id) AS  total_members_table ON total_members_table.project_id= $projects_table.id


        WHERE $projects_table.deleted=0 
        ORDER BY $projects_table.start_date DESC";
        return $this->db->query($sql);
    }

    function get_project_status_statistics($options = array()) {
        $table = $this->db->dbprefix('projects');

        try {
            $this->db->query("SET sql_mode = ''");
        } catch (Exception $e) {
            
        }

        $sql = "SELECT COUNT($table.id) AS total, $table.status
        FROM $table
        WHERE $table.deleted=0
        GROUP BY $table.status";
        return $this->db->query($sql)->result();
    }

    function get_projects_count() {
        $table = $this->db->dbprefix('projects');

        try {
            $this->db->query("SET sql_mode = ''");
        } catch (Exception $e) {
            
        }

        $sql = "SELECT COUNT($table.id) AS total, COUNT(DISTINCT $table.client_id) as total_clients
        FROM $table
        WHERE $table.deleted=0";
        return $this->db->query($sql)->result();
    }


    function get_invoices_project_count() {
        $estimates_table = $this->db->dbprefix('invoices');

        try {
            $this->db->query("SET sql_mode = ''");
        } catch (Exception $e) {
            
        }

        $sql = "SELECT COUNT($estimates_table.id) AS total, COUNT(DISTINCT $estimates_table.client_id) as total_clients
        FROM $estimates_table
        WHERE $estimates_table.deleted=0 and $estimates_table.project_id != 0";
        return $this->db->query($sql)->result();
    }

    function get_expenses_statistics($options = array()) {
        $table = $this->db->dbprefix('expenses');
        $cats_table = $this->db->dbprefix('expense_categories');
        $year = date('Y');

        try {
            $this->db->query("SET sql_mode = ''");
        } catch (Exception $e) {
            
        }

        $sql = "SELECT SUM($table.amount) AS totals, $cats_table.title AS titles
        FROM $table
        LEFT JOIN $cats_table ON $table.category_id= $cats_table.id
        WHERE $table.deleted = 0 AND $cats_table.deleted = 0 and $table.status = 'approved'
        GROUP BY $table.category_id";
        return $this->db->query($sql)->result();
    }

    function get_expenses_emp_statistics($options = array()) {
        $table = $this->db->dbprefix('expenses');
        $user_table = $this->db->dbprefix('users');
        $log_table = $this->db->dbprefix('activity_logs');
        $year = date('Y');

        try {
            $this->db->query("SET sql_mode = ''");
        } catch (Exception $e) {
            
        }

        $sql = "SELECT SUM($table.amount) AS totals, CONCAT_WS(' ', $user_table.first_name, $user_table.last_name) as titles
        FROM $table
        LEFT JOIN $user_table ON $table.user_id= $user_table.id
        WHERE $table.deleted = 0 AND $user_table.deleted = 0 AND $user_table.status = 'active' AND $user_table.user_type = 'staff' and $table.status = 'approved'
        GROUP BY $table.user_id";
        return $this->db->query($sql)->result();
    }

    function get_yearly_expenses_chart() {
        $expenses_table = $this->db->dbprefix('expenses');
        $year = date('Y');
        $expenses = "SELECT SUM($expenses_table.amount) AS total, MONTH($expenses_table.expense_date) AS title
            FROM $expenses_table
            WHERE $expenses_table.deleted=0 AND YEAR($expenses_table.expense_date)= $year
            GROUP BY MONTH($expenses_table.expense_date)";
        return $this->db->query($expenses)->result();
    }

    function get_items_cat_statistics($options = array()) {
        $table = $this->db->dbprefix('items');
        $cats_table = $this->db->dbprefix('item_categories');

        try {
            $this->db->query("SET sql_mode = ''");
        } catch (Exception $e) {
            
        }

        $sql = "SELECT count($table.id) AS totals, $cats_table.title AS titles
        FROM $table
        LEFT JOIN $cats_table ON $table.category_id = $cats_table.id
        WHERE $table.deleted = 0 AND $cats_table.deleted = 0
        GROUP BY $table.category_id";
        return $this->db->query($sql)->result();
    }

    function get_sold_items() {
        $table = $this->db->dbprefix('invoice_items');
        $items_table = $this->db->dbprefix('items');


        $sql = "SELECT SUM($table.quantity) AS total, $items_table.title AS title, $items_table.cost AS cost_price, $items_table.rate AS rate, $items_table.id AS id, $items_table.item_type as type
        FROM $table
        LEFT JOIN $items_table ON $table.item_id = $items_table.id
        WHERE $table.deleted = 0 AND $items_table.deleted = 0
        GROUP BY $table.item_id";
        return $this->db->query($sql)->result();
    }

    function get_invoices_total_and_paymnts() {
        $invoices_table = $this->db->dbprefix('invoices');
        $invoice_payments_table = $this->db->dbprefix('invoice_payments');
        $invoice_items_table = $this->db->dbprefix('invoice_items');
        $taxes_table = $this->db->dbprefix('taxes');
        $info = new stdClass();


        $payments = "SELECT SUM($invoice_payments_table.amount) AS total
            FROM $invoice_payments_table
            LEFT JOIN $invoices_table ON $invoices_table.id=$invoice_payments_table.invoice_id    
            WHERE $invoice_payments_table.deleted=0 AND $invoices_table.deleted=0 AND $invoice_payments_table.status='approved'";
        $info->payments = $this->db->query($payments)->result();

        $invoice_value_calculation_query = $this->_get_invoice_value_calculation_query($invoices_table);

        $invoices = "SELECT SUM(total) AS total FROM (SELECT $invoice_value_calculation_query AS total
            FROM $invoices_table
            LEFT JOIN (SELECT $taxes_table.id, $taxes_table.percentage FROM $taxes_table) AS tax_table ON tax_table.id = $invoices_table.tax_id
            LEFT JOIN (SELECT $taxes_table.id, $taxes_table.percentage FROM $taxes_table) AS tax_table2 ON tax_table2.id = $invoices_table.tax_id2
            LEFT JOIN (SELECT invoice_id, SUM(total) AS invoice_value FROM $invoice_items_table WHERE deleted=0 GROUP BY invoice_id) AS items_table ON items_table.invoice_id = $invoices_table.id 
            WHERE $invoices_table.deleted=0 AND $invoices_table.approval_status='approved') as details_table";

        $payments_total = $this->db->query($payments)->row()->total;
        $invoices_total = $this->db->query($invoices)->row()->total;

        $info->payments_total = $payments_total;
        $info->invoices_total = (($invoices_total > $payments_total) && ($invoices_total - $payments_total) < 0.05 ) ? $payments_total : $invoices_total;
        $info->due = $info->invoices_total - $info->payments_total;
        $info->total_invoices_gen = $info->payments_total + ($info->due < 0? $info->due * (-1):$info->due);

        ////

        $quotations = "SELECT SUM(`total`) as total FROM `estimate_items` LEFT JOIN estimates ON estimates.id = estimate_items.estimate_id WHERE estimate_items.deleted = 0 AND estimates.deleted = 0";    
        $info->total_quotation = $this->db->query($quotations)->row()->total;
        return $info;
    }

    function get_leads_count() {
        $table = $this->db->dbprefix('clients');

        try {
            $this->db->query("SET sql_mode = ''");
        } catch (Exception $e) {
            
        }

        $sql = "SELECT COUNT($table.id) AS total
        FROM $table
        WHERE $table.deleted=0 AND $table.is_lead=1";
        return $this->db->query($sql)->result();
    }

    function get_clients_count() {
        $table = $this->db->dbprefix('clients');

        try {
            $this->db->query("SET sql_mode = ''");
        } catch (Exception $e) {
            
        }

        $sql = "SELECT COUNT($table.id) AS total
        FROM $table
        WHERE $table.deleted=0";
        return $this->db->query($sql)->result();
    }

    function get_expense_categories($options = array()) {
        $expense_categories_table = $this->db->dbprefix('expense_categories');
        $expenses_table = $this->db->dbprefix('expenses');
        $where = "";
        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($expenses_table.expense_date BETWEEN '$start_date' AND '$end_date') ";
        }

        $sql = "SELECT $expense_categories_table.*, SUM($expenses_table.amount) AS totals, $expenses_table.expense_date 
        FROM $expense_categories_table
        LEFT JOIN $expenses_table ON $expenses_table.category_id = $expense_categories_table.id
        WHERE $expenses_table.deleted = 0 and $expenses_table.status = 'approved' AND $expense_categories_table.deleted=0 $where
        GROUP BY $expense_categories_table.id";
        return $this->db->query($sql);
    }

    function get_expense_emps($options = array()) {
        $table = $this->db->dbprefix('expenses');
        $user_table = $this->db->dbprefix('users');
        $where = "";
        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($table.expense_date BETWEEN '$start_date' AND '$end_date') ";
        }
        

        $sql = "SELECT SUM($table.amount) AS totals, CONCAT_WS(' ', $user_table.first_name, $user_table.last_name) as title, $table.expense_date 
        FROM $table
        LEFT JOIN $user_table ON $table.user_id= $user_table.id
        WHERE $table.deleted = 0 AND $user_table.deleted = 0 AND $user_table.status = 'active' AND $user_table.user_type = 'staff' and $table.status = 'approved' $where
        GROUP BY $table.user_id";
        return $this->db->query($sql);
    }

    function get_invoice_revenue($options = array()){
        $prefix=$this->db->dbprefix;
        // echo $prefix; die('hi');

        $where = "";
        
        $invoice_id = get_array_value($options, "invoice_id");
        if ($invoice_id) {
            $where .= " AND ".$prefix."invoices.id = $invoice_id";
        }

        // $invoice_value_calculation_query = $this->_get_invoice_value_calculation_query("".$prefix."proforma_invoices");
        $invoice_value_calculation_query = 0;
       
        $sql = "

            SELECT
            ".$prefix."invoices.id, 
            ".$prefix."invoices.bill_date, 
                $invoice_value_calculation_query as invoice_value,
                items_table.item_cal_cost,
                items_table.table_cost,
                items_table.inventory_cost,
                invoice_expense.expense_value
                
            FROM ".$prefix."invoices


           
            LEFT JOIN (SELECT ".$prefix."taxes.id, ".$prefix."taxes.percentage FROM ".$prefix."taxes) AS tax_table ON tax_table.id = ".$prefix."invoices.tax_id
            LEFT JOIN (SELECT ".$prefix."taxes.id, ".$prefix."taxes.percentage FROM ".$prefix."taxes) AS tax_table2 ON tax_table2.id = ".$prefix."invoices.tax_id2

            -- invoice expense
            LEFT JOIN 
                (SELECT 
                ".$prefix."expenses.invoice_id,
                  sum(".$prefix."expenses.amount) expense_value               
                FROM ".$prefix."expenses 
                WHERE ".$prefix."expenses.invoice_id != 0 and status = 'approved'

                GROUP BY ".$prefix."expenses.invoice_id) as invoice_expense
            ON (invoice_expense.invoice_id = ".$prefix."invoices.id)

            -- invoice item cost
            LEFT JOIN 
                (SELECT 
                ".$prefix."invoice_items.invoice_id,
                    sum(".$prefix."invoice_items.quantity * costs.cal_cost) item_cal_cost,
                    sum(".$prefix."invoice_items.total) invoice_value ,
                    sum(".$prefix."invoice_items.cost * ".$prefix."invoice_items.quantity) table_cost,
                    sum(".$prefix."items.cost * ".$prefix."invoice_items.quantity) inventory_cost
                  

                FROM ".$prefix."invoice_items

                LEFT JOIN (
                    SELECT
                        item_id, 
                        (SUM(total) / SUM(quantity)) as cal_cost
                    FROM ".$prefix."purchase_order_items 
                    WHERE deleted = 0
                    GROUP BY item_id
                ) AS costs
                    ON (costs.item_id = ".$prefix."invoice_items.item_id)


                left join ".$prefix."items
                    ON (".$prefix."invoice_items.item_id = ".$prefix."items.id)

                WHERE ".$prefix."invoice_items.deleted = 0
                GROUP BY ".$prefix."invoice_items.invoice_id) as items_table
            ON (items_table.invoice_id = ".$prefix."invoices.id)


            WHERE ".$prefix."invoices.deleted = 0 $where
        ";


        return $this->db->query($sql) ;
    }
    function invoice_uncollected_cheques($invoice_id) {
        $invoice_payments_table = $this->db->dbprefix('invoice_payments');
        $refund_payment_sql = "SELECT SUM($invoice_payments_table.amount) AS amount
            FROM $invoice_payments_table
            WHERE $invoice_payments_table.deleted=0 AND $invoice_payments_table.invoice_id=$invoice_id AND $invoice_payments_table.status= 'approved' AND $invoice_payments_table.payment_method_id = 5 AND $invoice_payments_table.cheque_transaction_id= 0";
            return $this->db->query($refund_payment_sql)->row();
    }

}
