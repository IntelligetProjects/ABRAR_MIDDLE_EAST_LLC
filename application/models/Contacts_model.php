<?php

class Contacts_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'contacts';
        parent::__construct($this->table);
        $this->init_activity_log($this->table, $this->table);
    }

    function get_details($options = array()) {
        $contacts_table = $this->db->dbprefix('contacts');
        $users_table = $this->db->dbprefix('users');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $contacts_table.id=$id";
        }

        $allowed_members = get_array_value($options, "allowed_members");
        if (is_array($allowed_members) && count($allowed_members)) {
            $allowed_members = join(",", $allowed_members);
            $where .= " AND $contacts_table.created_by IN($allowed_members)";
        }

         //add filter by cost center id
         if($this->login_user->cost_center_id > 0){
            $cost_center_id = $this->login_user->cost_center_id;
            $where .= " AND $users_table.cost_center_id = $cost_center_id";
        }

        $sql = "SELECT $contacts_table.*, CONCAT($users_table.first_name, ' ', $users_table.last_name) AS by_user_name, $users_table.image AS by_user_image
        FROM $contacts_table
        LEFT JOIN $users_table ON $users_table.id= $contacts_table.created_by
        WHERE $contacts_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
