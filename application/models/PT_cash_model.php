<?php

class PT_cash_model extends Crud_model {

    private $table = null;

    function __construct() {
    }

    function get_details($options = array()) {
        $employee_table = $this->db->dbprefix('users');
        $internal_transactions_table = $this->db->dbprefix('internal_transactions');
        $expenses_table = $this->db->dbprefix('expenses');
        $log_table = $this->db->dbprefix('activity_logs');
        

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $employee_table.id=$id";
        }

        $allowed_members = get_array_value($options, "allowed_members");
        if (is_array($allowed_members) && count($allowed_members)) {
            $allowed_members = join(",", $allowed_members);
            $where .= " AND $employee_table.id IN($allowed_members)";
        }


        $sql = "SELECT CONCAT($employee_table.first_name, $employee_table.last_name) as employee, recieving.amount as total_recieved, transfering.amount as total_transfered, expenses.amount as total_expenses
        FROM $employee_table 
        LEFT JOIN (SELECT SUM($internal_transactions_table.amount) as amount, $internal_transactions_table.to_employee as emp 
            FROM $internal_transactions_table where deleted = 0 and status = 'approved'
            group by emp) as recieving
        ON $employee_table.id = recieving.emp
        LEFT JOIN (SELECT SUM($internal_transactions_table.amount) as amount, $internal_transactions_table.from_employee as emp 
            FROM $internal_transactions_table where deleted = 0 and status = 'approved'
            group by emp) as transfering
        ON $employee_table.id = transfering.emp
        LEFT JOIN (SELECT SUM(amount) as amount, pt_cash as emp
            FROM  $expenses_table where deleted = 0 and (payment_mode = 'reimbursement' or payment_mode = 'pt_cash') and status = 'approved'
            group by emp) as expenses
        ON $employee_table.id = expenses.emp

        Where $employee_table.deleted=0 AND $employee_table.status= 'active' and $employee_table.user_type= 'staff' $where";
        return $this->db->query($sql);
    }


    function get_details_to_date($date = '') {
        $employee_table = $this->db->dbprefix('users');
        $internal_transactions_table = $this->db->dbprefix('internal_transactions');
        $expenses_table = $this->db->dbprefix('expenses');

        $data = array();

       
        if (!$date) 
        {
             $date = date('Y-m-d');
        }

        $sql = "SELECT * FROM  $employee_table";
        $employees = $this->db->query($sql)->result();
        foreach ($employees as $employee) {
            $data[0] = 0; 
            $data[$employee->id] = 0;
        }


        $sql = "SELECT SUM($internal_transactions_table.amount) as amount, $internal_transactions_table.to_employee as emp 
            FROM $internal_transactions_table where deleted = 0 and status = 'approved' and date <= '$date'
            group by emp";

        $employees = $this->db->query($sql)->result();
        foreach ($employees as $employee) {
            $data[$employee->emp] = $employee->amount;
        }

        $sql = "SELECT SUM($internal_transactions_table.amount) as amount, $internal_transactions_table.from_employee as emp 
            FROM $internal_transactions_table where deleted = 0 and status = 'approved' and date <= '$date'
            group by emp";

        $employees = $this->db->query($sql)->result();
        if($employees){
        foreach ($employees as $employee) {
            if(isset($data[$employee->emp])&&$employee->emp){
                // echo $employee->emp;
            $data[$employee->emp] -= $employee->amount?$employee->amount:0;
            }
        }
    }

        $sql = "SELECT SUM(amount) as amount, user_id as emp
            FROM $expenses_table where deleted = 0 and expense_date <= '$date'
            group by emp";

        $employees = $this->db->query($sql)->result();
        foreach ($employees as $employee) {
            $data[$employee->emp] -= $employee->amount?$employee->amount:0;
        }


        return $data; 

    }

}
