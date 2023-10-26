<?php

class Loans_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'loans';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $loans_table = $this->db->dbprefix('loans');
        $users_table = $this->db->dbprefix('users');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $loans_table.id=$id";
        }
        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $loans_table.user_id=$user_id";
        }

        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($loans_table.date BETWEEN '$start_date' AND '$end_date') ";
        }

        $sql = "SELECT $loans_table.*, CONCAT($users_table.first_name,' ',$users_table.last_name) as employee_name
        FROM $loans_table
        LEFT JOIN $users_table on $users_table.id = $loans_table.user_id
        WHERE $loans_table.deleted=0 $where
        ORDER BY $loans_table.id ASC";
        return $this->db->query($sql);
    }

    function get_total_advance($options = array()) {
        $loans_table = $this->db->dbprefix('loans');
        $users_table = $this->db->dbprefix('users');

        $where = "";
        
        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $loans_table.user_id=$user_id";
        }

        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($loans_table.date BETWEEN '$start_date' AND '$end_date') ";
        }

        $sql = "SELECT SUM($loans_table.amount) as total_advance
        FROM $loans_table
        LEFT JOIN $users_table on $users_table.id = $loans_table.user_id
        WHERE $loans_table.deleted=0 $where
        ORDER BY $loans_table.id ASC";
        return $this->db->query($sql);
    }

    function get_sum($options = array())
    {
        $loans_table = $this->db->dbprefix('loans');

        $where = "";
        
        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $loans_table.user_id=$user_id";
        }

        $sql = "SELECT SUM($loans_table.amount) as total_loan
        FROM $loans_table
        WHERE $loans_table.deleted=0 $where ";
        return $this->db->query($sql);
    }


}
