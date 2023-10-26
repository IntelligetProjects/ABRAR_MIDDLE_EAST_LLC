<?php

class Expenses_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'expenses';
        parent::__construct($this->table,true);
        $this->init_activity_log($this->table, $this->table);
    }

    function get_details($options = array()) {
        $expenses_table = $this->db->dbprefix('expenses');
        $expense_categories_table = $this->db->dbprefix('expense_categories');
        $projects_table = $this->db->dbprefix('projects');
        $users_table = $this->db->dbprefix('users');
        $taxes_table = $this->db->dbprefix('taxes');
        $clients_table = $this->db->dbprefix('clients');
        $log_table = $this->db->dbprefix('activity_logs');
        $service_provider_table = $this->db->dbprefix('service_provider');


        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $expenses_table.id=$id";
        }
        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($expenses_table.expense_date BETWEEN '$start_date' AND '$end_date') ";
        }

        $category_id = get_array_value($options, "category_id");
        if ($category_id) {
            $where .= " AND $expenses_table.category_id=$category_id";
        }

        $project_id = get_array_value($options, "project_id");
        if ($project_id) {
            $where .= " AND $expenses_table.project_id=$project_id";
        }

        $client_id = get_array_value($options, "client_id");
        if ($client_id) {
            $where .= " AND $expenses_table.client_id=$client_id";
        }

        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $expenses_table.user_id=$user_id";
        }

        $payment_mode = get_array_value($options, "payment_mode");
        if ($payment_mode) {
            $where .= " AND $expenses_table.payment_mode = '$payment_mode'";
        }

        $status = get_array_value($options, "status");
        if ($status) {
            $where .= " AND $expenses_table.status = '$status'";
        }


        //add filter by cost center id
        if($this->login_user->cost_center_id > 0){
            $cost_center_id = $this->login_user->cost_center_id;
            $where .= " AND $expenses_table.cost_center_id = $cost_center_id";
        }

        //prepare custom fild binding query
        $custom_fields = get_array_value($options, "custom_fields");
        $custom_field_query_info = $this->prepare_custom_field_query_string("expenses", $custom_fields, $expenses_table);
        $select_custom_fields = get_array_value($custom_field_query_info, "select_string");
        $join_custom_fields = get_array_value($custom_field_query_info, "join_string");

        $allowed_members = get_array_value($options, "allowed_members");
        if (is_array($allowed_members) && count($allowed_members)) {
            $allowed_members = join(",", $allowed_members);
            $where .= " AND $expenses_table.user_id IN($allowed_members)";
        }


        $sql = "SELECT $expenses_table.*, $expense_categories_table.title as category_title,
                 CONCAT($users_table.first_name, ' ', $users_table.last_name) AS linked_user_name, $clients_table.company_name as company_name,
                 $projects_table.title AS project_title, log.user_id as log_user_id,
           log.created_at as create_time, log.create_user as create_user,
                 tax_table.percentage AS tax_percentage,
                 tax_table2.percentage AS tax_percentage2
                 $select_custom_fields
        FROM $expenses_table
        LEFT JOIN $expense_categories_table ON $expense_categories_table.id= $expenses_table.category_id
        LEFT JOIN $projects_table ON $projects_table.id= $expenses_table.project_id
        LEFT JOIN $clients_table ON $clients_table.id= $expenses_table.client_id
        LEFT JOIN $users_table ON $users_table.id= $expenses_table.user_id
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $expenses_table.tax_id
        LEFT JOIN (SELECT a.*, CONCAT_WS(' ',b.first_name,b.last_name) as create_user, b.id as user_id  
                   FROM $log_table a
                   LEFT JOIN $users_table b on (a.created_by = b.id)
                   WHERE log_type = 'expenses' and action = 'created') as log ON ($expenses_table.id = log.log_type_id)
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table2 ON tax_table2.id = $expenses_table.tax_id2
            $join_custom_fields
        WHERE $expenses_table.deleted=0 $where";

$sql2 = "SELECT $expenses_table.*, $expense_categories_table.title as category_title, $expense_categories_table.type AS asset_type,
$service_provider_table.name AS service_provider_name, $service_provider_table.vat_number AS service_provider_vat_number,
CONCAT($users_table.first_name, ' ', $users_table.last_name) AS linked_user_name, $clients_table.company_name as company_name,
$projects_table.title AS project_title, log.user_id as log_user_id,
log.created_at as create_time, log.create_user as create_user,
tax_table.percentage AS tax_percentage,
tax_table2.percentage AS tax_percentage2
$select_custom_fields
FROM $expenses_table
LEFT JOIN $expense_categories_table ON $expense_categories_table.id= $expenses_table.category_id
LEFT JOIN $projects_table ON $projects_table.id= $expenses_table.project_id
LEFT JOIN $clients_table ON $clients_table.id= $expenses_table.client_id
LEFT JOIN $service_provider_table ON $service_provider_table.id = $expenses_table.service_provider_id
LEFT JOIN $users_table ON $users_table.id= $expenses_table.user_id
LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $expenses_table.tax_id
LEFT JOIN (SELECT a.*, CONCAT_WS(' ',b.first_name,b.last_name) as create_user, b.id as user_id  
  FROM $log_table a
  LEFT JOIN $users_table b on (a.created_by = b.id)
  WHERE log_type = 'expenses' and action = 'created') as log ON ($expenses_table.id = log.log_type_id)
LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table2 ON tax_table2.id = $expenses_table.tax_id2
$join_custom_fields
WHERE $expenses_table.deleted=0 $where";

        if($this->db->dbprefix=="tarteeb_v3"){
            return $this->db->query($sql2);
        }else{
            return $this->db->query($sql);
        }
            
    }

    function get_income_expenses_info() {
        $expenses_table = $this->db->dbprefix('expenses');
        $invoice_payments_table = $this->db->dbprefix('invoice_payments');
        $taxes_table = $this->db->dbprefix('taxes');
        $info = new stdClass();

        $sql1 = "SELECT SUM($invoice_payments_table.amount) as total_income
        FROM $invoice_payments_table
        WHERE $invoice_payments_table.deleted=0";
        $income = $this->db->query($sql1)->row();

        $sql2 = "SELECT SUM($expenses_table.amount + IFNULL(tax_table.percentage,0)/100*IFNULL($expenses_table.amount,0) + IFNULL(tax_table2.percentage,0)/100*IFNULL($expenses_table.amount,0)) AS total_expenses
        FROM $expenses_table
        LEFT JOIN (SELECT $taxes_table.id, $taxes_table.percentage FROM $taxes_table) AS tax_table ON tax_table.id = $expenses_table.tax_id
        LEFT JOIN (SELECT $taxes_table.id, $taxes_table.percentage FROM $taxes_table) AS tax_table2 ON tax_table2.id = $expenses_table.tax_id2
        WHERE $expenses_table.deleted=0 and $expenses_table.status='approved'";
        $expenses = $this->db->query($sql2)->row();

        $info->income = $income->total_income;
        $info->expneses = $expenses->total_expenses;
        return $info;
    }

    function get_yearly_expenses_chart($year) {
        $expenses_table = $this->db->dbprefix('expenses');
        $taxes_table = $this->db->dbprefix('taxes');

        $expenses = "SELECT SUM($expenses_table.amount + IFNULL(tax_table.percentage,0)/100*IFNULL($expenses_table.amount,0) + IFNULL(tax_table2.percentage,0)/100*IFNULL($expenses_table.amount,0)) AS total, MONTH($expenses_table.expense_date) AS month
        FROM $expenses_table
        LEFT JOIN (SELECT $taxes_table.id, $taxes_table.percentage FROM $taxes_table) AS tax_table ON tax_table.id = $expenses_table.tax_id
        LEFT JOIN (SELECT $taxes_table.id, $taxes_table.percentage FROM $taxes_table) AS tax_table2 ON tax_table2.id = $expenses_table.tax_id2
        WHERE $expenses_table.deleted=0 AND YEAR($expenses_table.expense_date)= $year and $expenses_table.status='approved'
        GROUP BY MONTH($expenses_table.expense_date)";

        return $this->db->query($expenses)->result();
    }

}
