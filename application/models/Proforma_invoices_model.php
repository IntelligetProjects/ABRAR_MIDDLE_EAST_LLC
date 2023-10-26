<?php

class Proforma_invoices_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'proforma_invoices';
        parent::__construct($this->table);
        $this->init_activity_log($this->table, $this->table);
    }

    function get_details($options = array()) {
        $invoices_table = $this->db->dbprefix('proforma_invoices');
        $clients_table = $this->db->dbprefix('clients');
        $projects_table = $this->db->dbprefix('projects');
        $taxes_table = $this->db->dbprefix('taxes');
        $invoice_payments_table = $this->db->dbprefix('proforma_invoice_payments');
        $invoice_items_table = $this->db->dbprefix('proforma_invoice_items');
        $users_table = $this->db->dbprefix('users');
        $log_table = $this->db->dbprefix('activity_logs');

        $where = "";
        $search_key = get_array_value($options, "search_key");
        if ($search_key) {
            $where .= " AND client_user.first_name  LIKE '%$search_key%'";
        }

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $invoices_table.id=$id";
        }

        $id_list = get_array_value($options, "id_list");
        if (is_array($id_list)) {
            $list = implode(",", $id_list); 
            $where .= " AND proforma_invoices.id in($list)";
        }
        
        $client_id = get_array_value($options, "client_id");
        if ($client_id) {
            $where .= " AND $invoices_table.client_id=$client_id";
        }

        $exclude_draft = get_array_value($options, "exclude_draft");
        if ($exclude_draft) {
            $where .= " AND $invoices_table.status!='draft' ";
        }

        $approval_status = get_array_value($options, "approval_status");
        if ($approval_status) {
            $where .= " AND $invoices_table.approval_status ='$approval_status' ";
        }

        

        $project_id = get_array_value($options, "project_id");
        if ($project_id) {
            $where .= " AND $invoices_table.project_id=$project_id";
        }

        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($invoices_table.bill_date BETWEEN '$start_date' AND '$end_date') ";
        }

        $monthly = get_array_value($options, "monthly");

        if ($monthly) {
            $numMonth = date("n");
            $year = date("Y");
            $where .= " AND (month($invoices_table.bill_date)=$numMonth AND year($invoices_table.bill_date)=$year)";
        }

        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $invoices_table.owner='$user_id'";
        }

        $next_recurring_start_date = get_array_value($options, "next_recurring_start_date");
        $next_recurring_end_date = get_array_value($options, "next_recurring_end_date");
        if ($next_recurring_start_date && $next_recurring_start_date) {
            $where .= " AND ($invoices_table.next_recurring_date BETWEEN '$next_recurring_start_date' AND '$next_recurring_end_date') ";
        } else if ($next_recurring_start_date) {
            $where .= " AND $invoices_table.next_recurring_date >= '$next_recurring_start_date' ";
        } else if ($next_recurring_end_date) {
            $where .= " AND $invoices_table.next_recurring_date <= '$next_recurring_end_date' ";
        }

        $recurring_invoice_id = get_array_value($options, "recurring_invoice_id");
        if ($recurring_invoice_id) {
            $where .= " AND $invoices_table.recurring_invoice_id=$recurring_invoice_id";
        }

        $now = get_my_local_time("Y-m-d");
        //  $options['status'] = "draft";
        $status = get_array_value($options, "status");


        $invoice_value_calculation_query = $this->_get_invoice_value_calculation_query($invoices_table);

        $select_invoice_value = "IFNULL(items_table.invoice_value,0)";

        $discountable_invoice_value = "(
                $select_invoice_value +
                IFNULL(items_table.tax_value,0)
               )";

        $discount_amount = "IF($invoices_table.discount_amount_type='percentage', IFNULL($invoices_table.discount_amount,0)/100* $discountable_invoice_value, $invoices_table.discount_amount)";


        $invoice_value_calculation_query = "(
                $select_invoice_value +
                IFNULL(items_table.tax_value,0)
                - $discount_amount
               )";


        $invoice_value_calculation = "TRUNCATE($invoice_value_calculation_query,2)";

        if ($status === "draft") {
            $where .= " AND $invoices_table.status='draft' AND IFNULL(payments_table.payment_received,0)<=0";
        } else if ($status === "not_paid") {
            $where .= " AND $invoices_table.status !='draft' AND $invoices_table.status!='cancelled' AND IFNULL(payments_table.payment_received,0)<=0";
        } else if ($status === "partially_paid") {
            $where .= " AND IFNULL(payments_table.payment_received,0)>0 AND IFNULL(payments_table.payment_received,0)<$invoice_value_calculation";
        } else if ($status === "fully_paid") {
            $where .= " AND TRUNCATE(IFNULL(payments_table.payment_received,0),2)>=$invoice_value_calculation";
        } else if ($status === "overdue") {
            $where .= " AND $invoices_table.status !='draft' AND $invoices_table.status!='cancelled' AND $invoices_table.due_date<'$now' AND TRUNCATE(IFNULL(payments_table.payment_received,0),2)<$invoice_value_calculation";
        } else if ($status === "cancelled") {
            $where .= " AND $invoices_table.status='cancelled' ";
        }


        $recurring = get_array_value($options, "recurring");
        if ($recurring) {
            $where .= " AND $invoices_table.recurring=1";
        }

        $currency = get_array_value($options, "currency");
        if ($currency) {
            $where .= $this->_get_clients_of_currency_query($currency, $invoices_table, $clients_table);
        }

        $exclude_due_reminder_date = get_array_value($options, "exclude_due_reminder_date");
        if ($exclude_due_reminder_date) {
            $where .= " AND ($invoices_table.due_reminder_date !='$exclude_due_reminder_date') ";
        }

        $exclude_recurring_reminder_date = get_array_value($options, "exclude_recurring_reminder_date");
        if ($exclude_recurring_reminder_date) {
            $where .= " AND ($invoices_table.recurring_reminder_date !='$exclude_recurring_reminder_date') ";
        }

        /////

        $allowed_members = get_array_value($options, "allowed_members");
        if (is_array($allowed_members) && count($allowed_members)) {
            $allowed_members = join(",", $allowed_members);
            $where .= " AND (log.user_id IN($allowed_members) OR $invoices_table.owner IN($allowed_members))";
        }


        $departments = get_array_value($options, "departments");
        if (is_array($departments) && count($departments)) {
            $departments = join(",", $departments);
            $where .= " AND ($invoices_table.department IN($departments) OR $invoices_table.department = 0)";
        }


        //prepare custom fild binding query
        $custom_fields = get_array_value($options, "custom_fields");
        $custom_field_query_info = $this->prepare_custom_field_query_string("invoices", $custom_fields, $invoices_table);
        $select_custom_fieds = get_array_value($custom_field_query_info, "select_string");
        $join_custom_fieds = get_array_value($custom_field_query_info, "join_string");




        $sql = "SELECT $invoices_table.*, $clients_table.currency, $clients_table.currency_symbol, $clients_table.company_name, $projects_table.title AS project_title, log.user_id as log_user_id, $clients_table.lead_source_id as lead_source, (items_table.tax_value) as tax_values,
           $invoice_value_calculation_query AS invoice_value, IFNULL(payments_table.payment_received,0) AS payment_received, tax_table.percentage AS tax_percentage, tax_table2.percentage AS tax_percentage2, CONCAT($users_table.first_name, ' ',$users_table.last_name) AS cancelled_by_user, log.user_id as log_user_id,
           log.created_at as create_time, log.create_user as create_user $select_custom_fieds
        FROM $invoices_table
        LEFT JOIN (SELECT a.*, CONCAT_WS(' ',b.first_name,b.last_name) as create_user, b.id as user_id  
                   FROM $log_table a
                   LEFT JOIN $users_table b on (a.created_by = b.id)
                   WHERE log_type = 'invoices' and action = 'created') as log ON ($invoices_table.id = log.log_type_id)
        LEFT JOIN $clients_table ON $clients_table.id= $invoices_table.client_id
        LEFT JOIN $projects_table ON $projects_table.id= $invoices_table.project_id
        LEFT JOIN $users_table ON $users_table.id= $invoices_table.cancelled_by
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $invoices_table.tax_id
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table2 ON tax_table2.id = $invoices_table.tax_id2
        LEFT JOIN (SELECT invoice_id, SUM(amount) AS payment_received FROM $invoice_payments_table WHERE deleted=0 and status='approved' GROUP BY invoice_id) AS payments_table ON payments_table.invoice_id = $invoices_table.id 
        LEFT JOIN (SELECT invoice_id, SUM(total) AS invoice_value, SUM((total*IFNULL(tax_table.percentage,0)*0.01) + (total*IFNULL(tax_table2.percentage,0)*0.01)) AS tax_value FROM $invoice_items_table 
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $invoice_items_table.tax_id
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table2 ON tax_table2.id = $invoice_items_table.tax_id2
        WHERE $invoice_items_table.deleted=0 GROUP BY invoice_id) AS items_table ON items_table.invoice_id = $invoices_table.id
        
        $join_custom_fieds
        WHERE $invoices_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_details2($options = array()) {
        $invoices_table = $this->db->dbprefix('proforma_invoices');
        $clients_table = $this->db->dbprefix('clients');
        $projects_table = $this->db->dbprefix('projects');
        $taxes_table = $this->db->dbprefix('taxes');
        $invoice_payments_table = $this->db->dbprefix('proforma_invoice_payments');
        $invoice_items_table = $this->db->dbprefix('proforma_invoice_items');
        $users_table = $this->db->dbprefix('users');
        $log_table = $this->db->dbprefix('activity_logs');
        $taxes_table = $this->db->dbprefix('taxes');

        $where = "";
        $search_key = get_array_value($options, "search_key");
        if ($search_key) {
            $where .= " AND client_user.first_name  LIKE '%$search_key%'";
        }

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $invoices_table.id=$id";
        }
        
        $client_id = get_array_value($options, "client_id");
        if ($client_id) {
            $where .= " AND $invoices_table.client_id=$client_id";
        }

        $exclude_draft = get_array_value($options, "exclude_draft");
        if ($exclude_draft) {
            $where .= " AND $invoices_table.status!='draft' ";
        }

        $approval_status = get_array_value($options, "approval_status");
        if ($approval_status) {
            $where .= " AND $invoices_table.approval_status ='$approval_status' ";
        }

        

        $project_id = get_array_value($options, "project_id");
        if ($project_id) {
            $where .= " AND $invoices_table.project_id=$project_id";
        }

        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($invoices_table.bill_date BETWEEN '$start_date' AND '$end_date') ";
        }

        $monthly = get_array_value($options, "monthly");

        if ($monthly) {
            $numMonth = date("n");
            $year = date("Y");
            $where .= " AND (month($invoices_table.bill_date)=$numMonth AND year($invoices_table.bill_date)=$year)";
        }

        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND log.user_id='$user_id'";
        }

        $next_recurring_start_date = get_array_value($options, "next_recurring_start_date");
        $next_recurring_end_date = get_array_value($options, "next_recurring_end_date");
        if ($next_recurring_start_date && $next_recurring_start_date) {
            $where .= " AND ($invoices_table.next_recurring_date BETWEEN '$next_recurring_start_date' AND '$next_recurring_end_date') ";
        } else if ($next_recurring_start_date) {
            $where .= " AND $invoices_table.next_recurring_date >= '$next_recurring_start_date' ";
        } else if ($next_recurring_end_date) {
            $where .= " AND $invoices_table.next_recurring_date <= '$next_recurring_end_date' ";
        }

        $recurring_invoice_id = get_array_value($options, "recurring_invoice_id");
        if ($recurring_invoice_id) {
            $where .= " AND $invoices_table.recurring_invoice_id=$recurring_invoice_id";
        }

        $now = get_my_local_time("Y-m-d");
        //  $options['status'] = "draft";
        $status = get_array_value($options, "status");


        $invoice_value_calculation_query = $this->_get_invoice_value_calculation_query($invoices_table);

        $select_invoice_value = "IFNULL(items_table.invoice_value,0)";

        $discountable_invoice_value = "(
                $select_invoice_value +
                IFNULL(items_table.tax_value,0)
               )";

        $discount_amount = "IF($invoices_table.discount_amount_type='percentage', IFNULL($invoices_table.discount_amount,0)/100* $discountable_invoice_value, $invoices_table.discount_amount)";


        $invoice_value_calculation_query = "(
                $select_invoice_value +
                IFNULL(items_table.tax_value,0)
                - $discount_amount
               )";


        $invoice_value_calculation = "TRUNCATE($invoice_value_calculation_query,2)";

        if ($status === "draft") {
            $where .= " AND $invoices_table.status='draft' AND IFNULL(payments_table.payment_received,0)<=0";
        } else if ($status === "not_paid") {
            $where .= " AND $invoices_table.status !='draft' AND $invoices_table.status!='cancelled' AND IFNULL(payments_table.payment_received,0)<=0";
        } else if ($status === "partially_paid") {
            $where .= " AND IFNULL(payments_table.payment_received,0)>0 AND IFNULL(payments_table.payment_received,0)<$invoice_value_calculation";
        } else if ($status === "fully_paid") {
            $where .= " AND TRUNCATE(IFNULL(payments_table.payment_received,0),2)>=$invoice_value_calculation";
        } else if ($status === "overdue") {
            $where .= " AND $invoices_table.status !='draft' AND $invoices_table.status!='cancelled' AND $invoices_table.due_date<'$now' AND TRUNCATE(IFNULL(payments_table.payment_received,0),2)<$invoice_value_calculation";
        } else if ($status === "cancelled") {
            $where .= " AND $invoices_table.status='cancelled' ";
        }


        $recurring = get_array_value($options, "recurring");
        if ($recurring) {
            $where .= " AND $invoices_table.recurring=1";
        }

        $currency = get_array_value($options, "currency");
        if ($currency) {
            $where .= $this->_get_clients_of_currency_query($currency, $invoices_table, $clients_table);
        }

        $exclude_due_reminder_date = get_array_value($options, "exclude_due_reminder_date");
        if ($exclude_due_reminder_date) {
            $where .= " AND ($invoices_table.due_reminder_date !='$exclude_due_reminder_date') ";
        }

        $exclude_recurring_reminder_date = get_array_value($options, "exclude_recurring_reminder_date");
        if ($exclude_recurring_reminder_date) {
            $where .= " AND ($invoices_table.recurring_reminder_date !='$exclude_recurring_reminder_date') ";
        }

        /////

        $allowed_members = get_array_value($options, "allowed_members");
        if (is_array($allowed_members) && count($allowed_members)) {
            $allowed_members = join(",", $allowed_members);
            $where .= " AND log.user_id IN($allowed_members)";
        }


        //prepare custom fild binding query
        $custom_fields = get_array_value($options, "custom_fields");
        $custom_field_query_info = $this->prepare_custom_field_query_string("invoices", $custom_fields, $invoices_table);
        $select_custom_fieds = get_array_value($custom_field_query_info, "select_string");
        $join_custom_fieds = get_array_value($custom_field_query_info, "join_string");




        $sql = "SELECT $invoices_table.*, $clients_table.currency, $clients_table.currency_symbol, $clients_table.company_name, $projects_table.title AS project_title, log.user_id as log_user_id, (items_table.tax_value) as tax_values,
           $invoice_value_calculation_query AS invoice_value, IFNULL(payments_table.payment_received,0) AS payment_received, tax_table.percentage AS tax_percentage, tax_table2.percentage AS tax_percentage2, CONCAT($users_table.first_name, ' ',$users_table.last_name) AS cancelled_by_user, log.user_id as log_user_id, pro.pro_value,
           log.created_at as create_time, log.create_user as create_user $select_custom_fieds
        FROM $invoices_table
        LEFT JOIN (SELECT a.*, CONCAT_WS(' ',b.first_name,b.last_name) as create_user, b.id as user_id  
                   FROM $log_table a
                   LEFT JOIN $users_table b on (a.created_by = b.id)
                   WHERE log_type = 'invoices' and action = 'created') as log ON ($invoices_table.id = log.log_type_id)
        LEFT JOIN $clients_table ON $clients_table.id= $invoices_table.client_id
        LEFT JOIN $projects_table ON $projects_table.id= $invoices_table.project_id
        LEFT JOIN $users_table ON $users_table.id= $invoices_table.cancelled_by
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $invoices_table.tax_id
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table2 ON tax_table2.id = $invoices_table.tax_id2
        LEFT JOIN (SELECT invoice_id, SUM(amount) AS payment_received FROM $invoice_payments_table WHERE deleted=0 and status='approved' GROUP BY invoice_id) AS payments_table ON payments_table.invoice_id = $invoices_table.id 
        LEFT JOIN (SELECT invoice_id, SUM(total) AS invoice_value, SUM((total*IFNULL(tax_table.percentage,0)*0.01) + (total*IFNULL(tax_table2.percentage,0)*0.01)) AS tax_value FROM $invoice_items_table 
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $invoice_items_table.tax_id
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table2 ON tax_table2.id = $invoice_items_table.tax_id2
        WHERE $invoice_items_table.deleted=0 GROUP BY invoice_id) AS items_table ON items_table.invoice_id = $invoices_table.id 

        LEFT JOIN (
            SELECT invoice_id, pro_detail.val as pro_value FROM proforma_invoices
                LEFT JOIN (SELECT SUM(total) val, proforma_invoice_id FROM proforma_invoice_items group by proforma_invoice_id) pro_detail
                ON (proforma_invoices.id = pro_detail.proforma_invoice_id)
        ) as pro ON (invoices.id = pro.invoice_id) 
        $join_custom_fieds
        WHERE $invoices_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_invoice_list() {
        $sql = "SELECT invoices.id, clients.company_name from invoices 
                LEFT JOIN clients on (invoices.client_id = clients.id)
                Where invoices.deleted = 0
        ";

         return $this->db->query($sql);
    }

    function get_details_search($options = array()) {
        $invoices_table = $this->db->dbprefix('proforma_invoices');
        $clients_table = $this->db->dbprefix('clients');
        $projects_table = $this->db->dbprefix('projects');
        $taxes_table = $this->db->dbprefix('taxes');
        $invoice_payments_table = $this->db->dbprefix('proforma_invoice_payments');
        $invoice_items_table = $this->db->dbprefix('proforma_invoice_items');
        $users_table = $this->db->dbprefix('users');
        $log_table = $this->db->dbprefix('activity_logs');

        $where = "";
        $search_key = get_array_value($options, "search_key");
        if ($search_key) {
            $where .= " AND ($users_table.first_name  LIKE '%$search_key%' OR $users_table.last_name  LIKE '%$search_key%' OR $clients_table.company_name  LIKE '%$search_key%')";
        }

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $invoices_table.id=$id";
        }
        
        $client_id = get_array_value($options, "client_id");
        if ($client_id) {
            $where .= " AND $invoices_table.client_id=$client_id";
        }

        $exclude_draft = get_array_value($options, "exclude_draft");
        if ($exclude_draft) {
            $where .= " AND $invoices_table.status!='draft' ";
        }

        $project_id = get_array_value($options, "project_id");
        if ($project_id) {
            $where .= " AND $invoices_table.project_id=$project_id";
        }

        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($invoices_table.due_date BETWEEN '$start_date' AND '$end_date') ";
        }


        $sql = "SELECT $invoices_table.*
        FROM $invoices_table
        LEFT JOIN $clients_table ON $clients_table.id= $invoices_table.client_id        
        LEFT JOIN $users_table ON $clients_table.id= $users_table.client_id 
        WHERE $invoices_table.deleted=0 $where
        ORDER BY $invoices_table.id DESC";

        return $this->db->query($sql);
    }
    function get_refunded_payments($invoice_id) {
        $invoice_payments_table = $this->db->dbprefix('proforma_invoice_payments');
        $refund_payment_sql = "SELECT SUM($invoice_payments_table.amount) AS total_paid
            FROM $invoice_payments_table
            WHERE $invoice_payments_table.deleted=0 AND $invoice_payments_table.invoice_id=$invoice_id AND $invoice_payments_table.status= 'refunded'";
            return $this->db->query($refund_payment_sql)->row();
    }

    function get_invoice_total_summary($invoice_id = 0) {
        $invoice_items_table = $this->db->dbprefix('proforma_invoice_items');
        $invoice_payments_table = $this->db->dbprefix('proforma_invoice_payments');
        $invoices_table = $this->db->dbprefix('proforma_invoices');
        $clients_table = $this->db->dbprefix('clients');
        $taxes_table = $this->db->dbprefix('taxes');

        $item_sql = "SELECT SUM($invoice_items_table.total) AS invoice_subtotal, SUM($invoice_items_table.total*tax_table.percentage*0.01) AS tax, SUM($invoice_items_table.total*tax_table2.percentage*0.01) AS tax2
        FROM $invoice_items_table
        LEFT JOIN $invoices_table ON $invoices_table.id= $invoice_items_table.invoice_id
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $invoice_items_table.tax_id
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table2 ON tax_table2.id = $invoice_items_table.tax_id2      
        WHERE $invoice_items_table.deleted=0 AND $invoice_items_table.invoice_id=$invoice_id AND $invoices_table.deleted=0";
        $item = $this->db->query($item_sql)->row();

        $payment_sql = "SELECT SUM($invoice_payments_table.amount) AS total_paid
        FROM $invoice_payments_table
        WHERE $invoice_payments_table.deleted=0 AND $invoice_payments_table.invoice_id=$invoice_id AND $invoice_payments_table.status= 'approved'";
        $payment = $this->db->query($payment_sql)->row();

        $refund_payment_sql = "SELECT SUM($invoice_payments_table.amount) AS total_paid
        FROM $invoice_payments_table
        WHERE $invoice_payments_table.deleted=0 AND $invoice_payments_table.invoice_id=$invoice_id AND $invoice_payments_table.status= 'refunded'";
        $refund_payment = $this->db->query($refund_payment_sql)->row();

        $invoice_sql = "SELECT $invoices_table.*, tax_table.percentage AS tax_percentage, tax_table.title AS tax_name,
            tax_table2.percentage AS tax_percentage2, tax_table2.title AS tax_name2
        FROM $invoices_table
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $invoices_table.tax_id
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table2 ON tax_table2.id = $invoices_table.tax_id2
        WHERE $invoices_table.deleted=0 AND $invoices_table.id=$invoice_id";
        $invoice = $this->db->query($invoice_sql)->row();

        $client_sql = "SELECT $clients_table.currency_symbol, $clients_table.currency FROM $clients_table WHERE $clients_table.id=$invoice->client_id";
        $client = $this->db->query($client_sql)->row();


        $result = new stdClass();
        $result->invoice_subtotal = $item->invoice_subtotal;
        $result->tax_percentage = $invoice->tax_percentage;
        $result->tax_percentage2 = $invoice->tax_percentage2;
        $result->tax_name = ("VAT Amount (5%)");
        $result->tax_name2 = ("Tax 2");
        $result->tax = 0;
        $result->tax2 = 0;
        $result->revised = $invoice->revised;

        $invoice_subtotal = $result->invoice_subtotal;
        $invoice_subtotal_for_taxes = $invoice_subtotal;
        //if ($invoice->discount_type == "before_tax") {
            $invoice_subtotal_for_taxes = $invoice_subtotal - ($invoice->discount_amount_type == "percentage" ? ($result->invoice_subtotal * ($invoice->discount_amount / 100)) : $invoice->discount_amount);
        //}

        //if ($invoice->tax_percentage) {
            $result->tax = $item->tax;
        //}
        //if ($invoice->tax_percentage2) {
            $result->tax2 = $item->tax2;
        //}
        $result->invoice_total = $item->invoice_subtotal + $result->tax + $result->tax2;

        $result->total_paid = $payment->total_paid - $refund_payment->total_paid;

        $result->net_payemnt = $payment->total_paid;
        $result->net_refund = $refund_payment->total_paid;

        $result->currency_symbol = $client->currency_symbol ? $client->currency_symbol : get_setting("currency_symbol");
        $result->currency = $client->currency ? $client->currency : get_setting("default_currency");

        //get discount total
        $result->discount_total = 0;
        /*if ($invoice->discount_type == "after_tax") {
            $invoice_subtotal = $result->invoice_total;
        }*/

        $result->discount_total = $invoice->discount_amount_type == "percentage" ? ($result->invoice_total * ($invoice->discount_amount / 100)) : $invoice->discount_amount;

        $result->discount_type = $invoice->discount_type;

        $result->total_after_discount = number_format($result->invoice_total, 3, ".", "") - number_format($result->discount_total, 3, ".", "");

        $result->balance_due = number_format($result->invoice_total, 3, ".", "") - number_format($payment->total_paid - $refund_payment->total_paid, 3, ".", "") - number_format($result->discount_total, 3, ".", "");

        return $result;
    }

    function invoice_statistics($options = array()) {
        $invoices_table = $this->db->dbprefix('proforma_invoices');
        $invoice_payments_table = $this->db->dbprefix('proforma_invoice_payments');
        $invoice_items_table = $this->db->dbprefix('proforma_invoice_items');
        $taxes_table = $this->db->dbprefix('taxes');
        $clients_table = $this->db->dbprefix('clients');

        $info = new stdClass();
        $year = get_my_local_time("Y");

        $where = "";
        $payments_where = "";
        $invoices_where = "";

        $client_id = get_array_value($options, "client_id");
        if ($client_id) {
            $where .= " AND $invoices_table.client_id=$client_id";
        } else {
            $invoices_where = $this->_get_clients_of_currency_query(get_array_value($options, "currency_symbol"), $invoices_table, $clients_table);

            $payments_where = " AND $invoice_payments_table.invoice_id IN(SELECT $invoices_table.id FROM $invoices_table WHERE $invoices_table.deleted=0 $invoices_where)";
        }

        $payments = "SELECT SUM($invoice_payments_table.amount) AS total, MONTH($invoice_payments_table.payment_date) AS month
            FROM $invoice_payments_table
            LEFT JOIN $invoices_table ON $invoices_table.id=$invoice_payments_table.invoice_id    
            WHERE $invoice_payments_table.deleted=0 AND YEAR($invoice_payments_table.payment_date)=$year AND $invoices_table.deleted=0 $where $payments_where
            GROUP BY MONTH($invoice_payments_table.payment_date)";

        $invoice_value_calculation_query = $this->_get_invoice_value_calculation_query($invoices_table);

        $invoices = "SELECT SUM(total) AS total, MONTH(due_date) AS month FROM (SELECT $invoice_value_calculation_query AS total ,$invoices_table.due_date
            FROM $invoices_table
            LEFT JOIN (SELECT $taxes_table.id, $taxes_table.percentage FROM $taxes_table) AS tax_table ON tax_table.id = $invoices_table.tax_id
            LEFT JOIN (SELECT $taxes_table.id, $taxes_table.percentage FROM $taxes_table) AS tax_table2 ON tax_table2.id = $invoices_table.tax_id2
            LEFT JOIN (SELECT invoice_id, SUM(total) AS invoice_value FROM $invoice_items_table WHERE deleted=0 GROUP BY invoice_id) AS items_table ON items_table.invoice_id = $invoices_table.id 
            WHERE $invoices_table.deleted=0 AND $invoices_table.status='not_paid' $where AND YEAR($invoices_table.due_date)=$year $invoices_where) as details_table
            GROUP BY  MONTH(due_date)";

        $info->payments = $this->db->query($payments)->result();
        $info->invoices = $this->db->query($invoices)->result();
        $info->currencies = $this->get_used_currencies_of_client()->result();

        return $info;
    }

    function get_used_currencies_of_client() {
        $clients_table = $this->db->dbprefix('clients');
        $default_currency = get_setting("default_currency");

        $sql = "SELECT $clients_table.currency
            FROM $clients_table
            WHERE $clients_table.deleted=0 AND $clients_table.currency!='' AND $clients_table.currency!='$default_currency'
            GROUP BY $clients_table.currency";

        return $this->db->query($sql);
    }

    function get_invoices_total_and_paymnts($invoice_array = "") {
        $invoices_table = $this->db->dbprefix('proforma_invoices');
        $invoice_payments_table = $this->db->dbprefix('proforma_invoice_payments');
        $invoice_items_table = $this->db->dbprefix('proforma_invoice_items');
        $taxes_table = $this->db->dbprefix('taxes');
        $info = new stdClass();

        $where_invoice = "";
        
        if (!empty($invoice_array)) {
            //$invoices_list = implode(",", $invoice_array);
            $where_invoice = " AND invoices.id in ($invoice_array)";
        }
        


        $payments = "SELECT SUM($invoice_payments_table.amount) AS total
            FROM $invoice_payments_table
            LEFT JOIN $invoices_table ON $invoices_table.id=$invoice_payments_table.invoice_id    
            WHERE $invoice_payments_table.deleted=0 AND $invoices_table.deleted=0 AND $invoice_payments_table.status='approved' $where_invoice";
        $info->payments = $this->db->query($payments)->result();

        $refund_payments = "SELECT SUM($invoice_payments_table.amount) AS total
            FROM $invoice_payments_table
            LEFT JOIN $invoices_table ON $invoices_table.id=$invoice_payments_table.invoice_id    
            WHERE $invoice_payments_table.deleted=0 AND $invoices_table.deleted=0 AND $invoice_payments_table.status='refunded' $where_invoice";
        $info->refund_payments = $this->db->query($refund_payments)->result();

        $invoice_value_calculation_query = $this->_get_invoice_value_calculation_query($invoices_table);

        $invoices = "SELECT SUM(total) AS total 
            FROM (SELECT $invoice_value_calculation_query AS total
                FROM $invoices_table
                LEFT JOIN (SELECT $taxes_table.id, $taxes_table.percentage FROM $taxes_table) AS tax_table ON tax_table.id = $invoices_table.tax_id
                LEFT JOIN (SELECT $taxes_table.id, $taxes_table.percentage FROM $taxes_table) AS tax_table2 ON tax_table2.id = $invoices_table.tax_id2
                LEFT JOIN (SELECT invoice_id, SUM(total) AS invoice_value FROM $invoice_items_table WHERE deleted=0 GROUP BY invoice_id) AS items_table ON items_table.invoice_id = $invoices_table.id 
                WHERE $invoices_table.deleted=0 AND $invoices_table.approval_status='approved' 
                    $where_invoice) as details_table";

        $payments_total = $this->db->query($payments)->row()->total;
        $refund_payments_total = $this->db->query($refund_payments)->row()->total;
        $invoices_total = $this->db->query($invoices)->row()->total;

        $info->payments_total = $payments_total - $refund_payments_total;
        $info->invoices_total = (($invoices_total > $payments_total) && ($invoices_total - $payments_total) < 0.05 ) ? $payments_total : $invoices_total;
        $info->due = $info->invoices_total - $info->payments_total;
        return $info;
    }


    function get_invoce_value() {

        $sql = "SELECT * from invoices WHERE ";

    }

    //update invoice status
    function update_invoice_status($invoice_id = 0, $status = "not_paid") {
        $status_data = array("status" => $status);
        return $this->save($status_data, $invoice_id);
    }

    //update e status
    function update_delivery_status($invoice_id = 0, $status = "not_delivered") {
        $status_data = array("delivery_status" => $status);
        return $this->save($status_data, $invoice_id);
    }

    //update e status
    function update_approval_status($invoice_id = 0, $status = "not_approved") {
        $status_data = array("approval_status" => $status);
        return $this->save($status_data, $invoice_id);
    }

    //get the recurring invoices which are ready to renew as on a given date
    function get_renewable_invoices($date) {
        $invoices_table = $this->db->dbprefix('proforma_invoices');

        $sql = "SELECT * FROM $invoices_table
                        WHERE $invoices_table.deleted=0 AND $invoices_table.recurring=1
                        AND $invoices_table.next_recurring_date IS NOT NULL AND $invoices_table.next_recurring_date<='$date'
                        AND ($invoices_table.no_of_cycles < 1 OR ($invoices_table.no_of_cycles_completed < $invoices_table.no_of_cycles ))";

        return $this->db->query($sql);
    }

    //get invoices dropdown list
    function get_invoices_dropdown_list() {
        $invoices_table = $this->db->dbprefix('proforma_invoices');

        $sql = "SELECT $invoices_table.id FROM $invoices_table
                        WHERE $invoices_table.deleted=0 
                        ORDER BY $invoices_table.id DESC";

        return $this->db->query($sql);
    }


    //get label suggestions
    function get_label_suggestions() {
        $invoices_table = $this->db->dbprefix('proforma_invoices');
        $sql = "SELECT GROUP_CONCAT(labels) as label_groups
        FROM $invoices_table
        WHERE $invoices_table.deleted=0";
        return $this->db->query($sql)->row()->label_groups;

    }
    
    //get invoice last id
    function get_last_invoice_id() {
        $invoices_table = $this->db->dbprefix('proforma_invoices');

        $sql = "SELECT MAX($invoices_table.id) AS last_id FROM $invoices_table";

        return $this->db->query($sql)->row()->last_id;
    }

    //save initial number of invoice
    function save_initial_number_of_invoice($value) {
        $invoices_table = $this->db->dbprefix('proforma_invoices');

        $sql = "ALTER TABLE $invoices_table AUTO_INCREMENT=$value;";

        return $this->db->query($sql);

    }

    //get i
    function get_service_and_products_total($invoice_id, $item_type) {
        $invoices_table = $this->db->dbprefix('proforma_invoice_items');
        $items_table = $this->db->dbprefix('items');

        $sql = "SELECT SUM(total) AS total
            FROM $invoices_table
            LEFT JOIN $items_table ON $invoices_table.item_id = $items_table.id
            WHERE $invoices_table.invoice_id = $invoice_id AND $invoices_table.deleted = 0 AND $items_table.item_type = '$item_type'";

        return $this->db->query($sql)->row()->total;
    }

    function get_invoice_products($invoice_id) {
        $invoices_table = $this->db->dbprefix('proforma_invoice_items');
        $items_table = $this->db->dbprefix('items');

        $sql = "SELECT $invoices_table.*
            FROM $invoices_table
            LEFT JOIN $items_table ON $invoices_table.item_id = $items_table.id
            WHERE $invoices_table.invoice_id = $invoice_id AND $invoices_table.deleted = 0 AND $items_table.item_type = 'product'";

        return $this->db->query($sql)->result();
    }

    function get_invoice_item_inventory($item_id) {
        $purchase_order_items_table = $this->db->dbprefix('purchase_order_items');
        $purchase_orders_table = $this->db->dbprefix('purchase_orders');

        $sql = "SELECT SUM($purchase_order_items_table.quantity) AS sum_qty, SUM($purchase_order_items_table.total) AS sum_total
        FROM $purchase_order_items_table
        LEFT JOIN $purchase_orders_table ON $purchase_orders_table.id=$purchase_order_items_table.purchase_order_id
        WHERE $purchase_order_items_table.deleted=0 AND $purchase_orders_table.deleted = 0 AND $purchase_orders_table.approval_status = 'approved' AND $purchase_order_items_table.item_id = $item_id";

        return $this->db->query($sql)->row();

    }

    function check_stock($item_id) {
        $purchase_order_items_table = $this->db->dbprefix('purchase_order_items');
        $purchase_orders_table = $this->db->dbprefix('purchase_orders');

        $sql = "SELECT SUM($purchase_order_items_table.quantity) AS sum_qty
        FROM $purchase_order_items_table
        LEFT JOIN $purchase_orders_table ON $purchase_orders_table.id=$purchase_order_items_table.purchase_order_id
        WHERE $purchase_order_items_table.deleted=0 AND $purchase_orders_table.deleted = 0 AND $purchase_orders_table.approval_status = 'approved' AND $purchase_order_items_table.item_id = $item_id";

        return $this->db->query($sql)->row();

    }


    function get_search_list($term) {


        $invoice_value_calculation_query = $this->_get_invoice_value_calculation_query("invoices");
        $estimate_value_calculation_query = $this->_get_estimate_value_calculation_query();

        $list = explode(" ", $term);
        $where = "";
        
        foreach ($list as $item) {
           $where .= " AND (module LIKE '%$item%' 
                OR ID LIKE '%$item%' 
                OR Name LIKE '%$item%'
                OR Value LIKE '%$item%') ";
        }

        $sql_invoice = "SELECT * FROM (

                SELECT 'Pro-Invoice' as module, 
                        invoices.id as ID,
                        '' as title,
                        clients.name as Name, 
                        $invoice_value_calculation_query AS Value

                From invoices

                LEFT JOIN (SELECT id, company_name as name 
                            FROM clients) clients
                    ON (clients.id = invoices.client_id)

                LEFT JOIN (SELECT invoice_id, SUM(total) AS invoice_value 
                           FROM invoice_items WHERE deleted=0 GROUP BY invoice_id) AS items_table
                    ON (items_table.invoice_id = invoices.id)  

                LEFT JOIN (SELECT taxes.* FROM taxes) AS tax_table ON tax_table.id = invoices.tax_id
                LEFT JOIN (SELECT taxes.* FROM taxes) AS tax_table2 ON tax_table2.id = invoices.tax_id2

                WHERE invoices.deleted = 0

                UNION 

                SELECT 'Quotation' as module, 
                        estimates.id as ID,
                        '' as title, 
                        clients.name as Name, 
                        $estimate_value_calculation_query AS Value

                From estimates

                LEFT JOIN (SELECT id, company_name as name 
                            FROM clients) clients
                    ON (clients.id = estimates.client_id)

                LEFT JOIN (SELECT estimate_id, SUM(total) AS estimate_value 
                           FROM estimate_items WHERE deleted=0 GROUP BY estimate_id) AS items_table
                    ON (items_table.estimate_id = estimates.id)  

                LEFT JOIN (SELECT taxes.* FROM taxes) AS tax_table ON tax_table.id = estimates.tax_id
                LEFT JOIN (SELECT taxes.* FROM taxes) AS tax_table2 ON tax_table2.id = estimates.tax_id2

                where estimates.deleted = 0

                UNION

                SELECT 'Expense' as module, 
                        expenses.id as ID, 
                        expenses.description as title,
                        employee.name as Name, 
                        expenses.amount as Value

                From expenses

                LEFT JOIN (SELECT id, CONCAT(users.first_name, ' ', users.last_name) as name 
                            FROM users) employee
                    ON (employee.id = expenses.user_id)   
                where expenses.deleted = 0

                UNION

                SELECT 'Payment' as module,
                        invoice_payments.id as ID,
                        invoice_payments.invoice_id as title,
                        clients.company_name as Name,
                        invoice_payments.amount as Value
                FROM invoice_payments

                left join invoices on (invoices.id = invoice_payments.invoice_id)
                left join clients on (clients.id = invoices.client_id) 
                where invoices.deleted = 0 AND invoice_payments.deleted = 0   


                UNION

                SELECT 'Internal-Transaction' as module,
                        internal_transactions.id as ID,
                        employee2.name as title,
                        employee.name as Name,
                        internal_transactions.amount as Value
                FROM internal_transactions

                 LEFT JOIN (SELECT id, CONCAT(users.first_name, ' ', users.last_name) as name 
                            FROM users) employee
                    ON (employee.id = internal_transactions.to_employee) 

                 LEFT JOIN (SELECT id, CONCAT(users.first_name, ' ', users.last_name) as name 
                            FROM users) employee2
                    ON (employee.id = internal_transactions.from_employee) 
                where internal_transactions.deleted = 0 


            ) as result

            WHERE True $where

        ";

        return $this->db->query($sql_invoice);
    }

    function get_invoice_editing($invoice_id) {

        $sql = "
            SELECT * FROM activity_logs 
            WHERE log_type = 'invoices' and log_type_id = $invoice_id 
        ";

    }

    function get_invoice_item_sold($item_id) {
        $purchase_order_items_table = $this->db->dbprefix('proforma_invoice_items');
        $purchase_orders_table = $this->db->dbprefix('proforma_invoices');

        $sql = "SELECT SUM($purchase_order_items_table.quantity) AS sum_qty, SUM($purchase_order_items_table.total) AS sum_total
        FROM $purchase_order_items_table
        LEFT JOIN $purchase_orders_table ON $purchase_orders_table.id=$purchase_order_items_table.invoice_id
        WHERE $purchase_order_items_table.deleted=0 AND $purchase_orders_table.deleted = 0 AND $purchase_orders_table.approval_status = 'approved' AND $purchase_order_items_table.item_id = $item_id";

        return $this->db->query($sql)->row();

    }

}
