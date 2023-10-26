<?php

class Proforma_invoice_payments_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'proforma_invoice_payments';
        parent::__construct($this->table);
        $this->init_activity_log($this->table, $this->table);
    }

    function get_details($options = array()) {
        $invoice_payments_table = $this->db->dbprefix('proforma_invoice_payments');
        $invoices_table = $this->db->dbprefix('proforma_invoices');
        $payment_methods_table = $this->db->dbprefix('payment_methods');
        $clients_table = $this->db->dbprefix('clients');
        $users_table = $this->db->dbprefix('users');

        $where = "";

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $invoice_payments_table.id=$id";
        }

        $invoice_id = get_array_value($options, "invoice_id");
        if ($invoice_id) {
            $where .= " AND $invoice_payments_table.invoice_id=$invoice_id";
        }

        $client_id = get_array_value($options, "client_id");
        if ($client_id) {
            $where .= " AND $invoices_table.client_id=$client_id";
        }

        $project_id = get_array_value($options, "project_id");
        if ($project_id) {
            $where .= " AND $invoices_table.project_id=$project_id";
        }

        $payment_method_id = get_array_value($options, "payment_method_id");
        if ($payment_method_id) {
            $where .= " AND $invoice_payments_table.payment_method_id=$payment_method_id";
        }

        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($invoice_payments_table.payment_date BETWEEN '$start_date' AND '$end_date') ";
        }

        $start_date_c = get_array_value($options, "start_date_c");
        $end_date_c = get_array_value($options, "end_date_c");
        if ($start_date_c && $end_date_c) {
            $where .= " AND ($invoice_payments_table.cheque_due_date BETWEEN '$start_date_c' AND '$end_date_c') ";
        }

        $currency = get_array_value($options, "currency");
        if ($currency) {
            $where .= $this->_get_clients_of_currency_query($currency, $invoices_table, $clients_table);
        }

        $status = get_array_value($options, "status");
        if ($status) {
            if ($status == "moniter") {
                $limit_date = date("Y-m-d", strtotime("-5 month"));
                $where .= " AND ($invoice_payments_table.status ='approved' OR ($invoice_payments_table.status ='delayed' AND $invoice_payments_table.cheque_due_date < '$limit_date'))";
            } elseif ($status == "collected") {
                $where .= " AND $invoice_payments_table.cheque_transaction_id != 0";
            } else {
                 $where .= " AND $invoice_payments_table.status='$status'";
            }
        }

        $collected = get_array_value($options, "collected");
        if ($collected == 'not_collected') {
            $where .= " AND $invoice_payments_table.cheque_transaction_id = 0";
        } 

        $due = get_array_value($options, "due");
        if ($due) {
            $today = date('Y-m-d');
            $where .= " AND $invoice_payments_table.cheque_due_date <= '$today'";
        } 

        $departments = get_array_value($options, "departments");
        if (is_array($departments) && count($departments)) {
            $departments = join(",", $departments);
            $where .= " AND $invoices_table.department IN($departments)";
        }


        $sql = "SELECT $invoice_payments_table.*, $invoices_table.client_id, (SELECT $clients_table.currency_symbol FROM $clients_table WHERE $clients_table.id=$invoices_table.client_id limit 1) AS currency_symbol,  (SELECT $clients_table.company_name FROM $clients_table WHERE $clients_table.id=$invoices_table.client_id limit 1) AS company_name, $payment_methods_table.title AS payment_method_title, CONCAT($users_table.first_name,' ',$users_table.last_name) as user_name, $users_table.image as user_image
        FROM $invoice_payments_table
        LEFT JOIN $invoices_table ON $invoices_table.id=$invoice_payments_table.invoice_id
        LEFT JOIN $payment_methods_table ON $payment_methods_table.id = $invoice_payments_table.payment_method_id
        LEFT JOIN $users_table ON $users_table.id=$invoice_payments_table.created_by
        WHERE $invoice_payments_table.deleted=0 AND $invoices_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_yearly_payments_chart($year, $currency = "") {
        $payments_table = $this->db->dbprefix('proforma_invoice_payments');
        $invoices_table = $this->db->dbprefix('proforma_invoices');
        $clients_table = $this->db->dbprefix('clients');

        $where = "";
        if ($currency) {
            $where = $this->_get_clients_of_currency_query($currency, $invoices_table, $clients_table);
        }

        $payments = "SELECT SUM($payments_table.amount) AS total, MONTH($payments_table.payment_date) AS month
            FROM $payments_table
            LEFT JOIN $invoices_table ON $invoices_table.id=$payments_table.invoice_id
            WHERE $payments_table.deleted=0 AND YEAR($payments_table.payment_date)= $year AND $invoices_table.deleted=0 AND $payments_table.status='approved' AND (payment_method_id != 5 or cheque_transaction_id != 0) $where
            GROUP BY MONTH($payments_table.payment_date)";
        return $this->db->query($payments)->result();
    }


    function get_expected_yearly_payments_chart($year, $currency = "") {
        $payments_table = $this->db->dbprefix('proforma_invoice_payments');
        $invoices_table = $this->db->dbprefix('proforma_invoices');
        $clients_table = $this->db->dbprefix('clients');

        $where = "";
        if ($currency) {
            $where = $this->_get_clients_of_currency_query($currency, $invoices_table, $clients_table);
        }

        $payments = "SELECT SUM($payments_table.amount) AS total, MONTH($payments_table.payment_date) AS month
            FROM $payments_table
            LEFT JOIN $invoices_table ON $invoices_table.id=$payments_table.invoice_id
            WHERE $payments_table.deleted=0 
            AND $invoices_table.deleted=0
            AND YEAR($payments_table.payment_date)= $year
            AND $payments_table.status='approved' 
            AND (payment_method_id = 5 and cheque_transaction_id = 0)             
            $where
            GROUP BY MONTH($payments_table.payment_date)";
        return $this->db->query($payments)->result();
    }

}
