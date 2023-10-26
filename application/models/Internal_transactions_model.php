<?php

class Internal_transactions_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'internal_transactions';
        parent::__construct($this->table);
        $this->init_activity_log($this->table, $this->table);
    }

    function get_details($options = array()) {
        $internal_transactions_table = $this->db->dbprefix('internal_transactions');
        $users_table = $this->db->dbprefix('users');
        $log_table = $this->db->dbprefix('activity_logs');
        
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $internal_transactions_table.id=$id";
        }
       

        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND ($internal_transactions_table.to_employee=$user_id OR $internal_transactions_table.from_employee=$user_id)";
        }

        $sql = "SELECT $internal_transactions_table.*, user1.first_name as first_name1, user1.last_name as last_name1, user2.first_name as first_name2, user2.last_name as last_name2,  log.created_at as create_time, log.create_user as create_user
        FROM $internal_transactions_table
        LEFT JOIN $users_table as user1 ON (user1.id = $internal_transactions_table.from_employee)
        LEFT JOIN $users_table as user2 ON (user2.id = $internal_transactions_table.to_employee)
        LEFT JOIN (SELECT a.*, CONCAT_WS(' ',b.first_name,b.last_name) as create_user, b.id as user_id  
                   FROM $log_table a
                   LEFT JOIN $users_table b on (a.created_by = b.id)
                   WHERE log_type = 'internal_transactions' and action = 'created') as log ON ($internal_transactions_table.id = log.log_type_id)    
        WHERE $internal_transactions_table.deleted=0 $where";
        return $this->db->query($sql);
    }


    function set_transaction_status($id = 0, $stat='') {
        $status_data = array("status" => $stat);
        return $this->save($status_data, $id);
    }

}
