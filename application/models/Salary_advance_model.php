<?php

class Salary_advance_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'salary_advance';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $salary_advance_table = $this->db->dbprefix('salary_advance');
        $users_table = $this->db->dbprefix('users');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $salary_advance_table.id=$id";
        }
        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $salary_advance_table.user_id=$user_id";
        }

        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($salary_advance_table.date BETWEEN '$start_date' AND '$end_date') ";
        }

        $sql = "SELECT $salary_advance_table.*, CONCAT($users_table.first_name,' ',$users_table.last_name) as employee_name
        FROM $salary_advance_table
        LEFT JOIN $users_table on $users_table.id = $salary_advance_table.user_id
        WHERE $salary_advance_table.deleted=0 $where
        ORDER BY $salary_advance_table.id ASC";
        return $this->db->query($sql);
    }

    function get_total_advance($options = array()) {
        $salary_advance_table = $this->db->dbprefix('salary_advance');
        $users_table = $this->db->dbprefix('users');

        $where = "";
        
        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $salary_advance_table.user_id=$user_id";
        }

        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($salary_advance_table.date BETWEEN '$start_date' AND '$end_date') ";
        }

        $sql = "SELECT SUM($salary_advance_table.amount) as total_advance
        FROM $salary_advance_table
        LEFT JOIN $users_table on $users_table.id = $salary_advance_table.user_id
        WHERE $salary_advance_table.deleted=0 $where
        ORDER BY $salary_advance_table.id ASC";
        return $this->db->query($sql);
    }


}
