<?php

class Expires_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'expires';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $expiry_table = $this->db->dbprefix('expires');
        $users_table = $this->db->dbprefix('users');
        $clients_table = $this->db->dbprefix('clients');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $expiry_table.id=$id";
        }

        $expiry = get_array_value($options, "expiry");
        if ($expiry) {
             $now = get_my_local_time("Y-m-d");
            if ($expiry == 'expired') {               
                $where .= " AND $expiry_table.expiry < '$now' ";
            } else {
                 $where .= " AND ($expiry_table.expiry < '$expiry' AND $expiry_table.expiry >= '$now') ";
            }
           
        }


        $type = get_array_value($options, "type");
        if ($type) {
            $where .= " AND $expiry_table.type='$type'";
        }




        $sql = "SELECT $expiry_table.*, CONCAT($users_table.first_name, ' ', 
                $users_table.last_name) AS user,
                $clients_table.company_name as company_name
          FROM $expiry_table
         LEFT JOIN $users_table ON ($users_table.id = $expiry_table.responsible_id)
         LEFT JOIN $clients_table ON $clients_table.id= $expiry_table.client_id
       
        WHERE $expiry_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
