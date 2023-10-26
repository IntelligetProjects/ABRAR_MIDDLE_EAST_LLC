<?php

class Email_messages_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'email_messages';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $email_messages_table = $this->db->dbprefix('email_messages');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $email_messages_table.id=$id";
        }


    


        $status = get_array_value($options, "status");
        if ($status) {
            $where .= " AND FIND_IN_SET($email_messages_table.status,'$status')";
        }


        $sql = "SELECT $email_messages_table.*
        FROM $email_messages_table
        WHERE $email_messages_table.deleted=0 $where";
        return $this->db->query($sql);
    }



}
