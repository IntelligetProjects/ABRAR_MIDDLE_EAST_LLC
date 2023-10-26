<?php

class Shipments_model extends Crud_model
{

    private $table = null;

    function __construct()
    {
        $this->table = 'shipments';
        parent::__construct($this->table);
        $this->init_activity_log($this->table, $this->table);
    }

    function get_details($options = array())
    {
        $shipments_table = $this->db->dbprefix('shipments');
        $suppliers_table = $this->db->dbprefix('suppliers');
        $shipments_table = $this->db->dbprefix('shipments');
        $users_table = $this->db->dbprefix('users');
        $log_table = $this->db->dbprefix('activity_logs');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $shipments_table.id=$id";
        }
        $supplier_id = get_array_value($options, "supplier_id");
        if ($supplier_id) {
            $where .= " AND $shipments_table.supplier_id=$supplier_id";
        }

        $exclude_draft = get_array_value($options, "exclude_draft");
        if ($exclude_draft) {
            $where .= " AND $shipments_table.status!='draft' ";
        }


        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($shipments_table.date BETWEEN '$start_date' AND '$end_date') ";
        }

        $allowed_members = get_array_value($options, "allowed_members");
        if (is_array($allowed_members) && count($allowed_members)) {
            $allowed_members = join(",", $allowed_members);
            $where .= " AND log.user_id IN($allowed_members)";
        }

        //add filter by cost center id
        if ($this->login_user->cost_center_id > 0) {
            $cost_center_id = $this->login_user->cost_center_id;
            $where .= " AND $suppliers_table.cost_center_id = $cost_center_id";
        }

        $now = get_my_local_time("Y-m-d");
        $status = get_array_value($options, "status");

        $sql = "SELECT $shipments_table.*, $suppliers_table.company_name, log.user_id as log_user_id,
           log.created_at as create_time, log.create_user as create_user
        FROM $shipments_table
        LEFT JOIN $suppliers_table ON $suppliers_table.id= $shipments_table.supplier_id
        LEFT JOIN (SELECT a.*, CONCAT_WS(' ',b.first_name,b.last_name) as create_user, b.id as user_id  
                   FROM $log_table a
                   LEFT JOIN $users_table b on (a.created_by = b.id)
                   WHERE log_type = 'shipments' and action = 'created') as log ON ($shipments_table.id = log.log_type_id)
        WHERE $shipments_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    //update e status
    function update_approval_status($id = 0, $status = "not_approved")
    {
        $status_data = array("status" => $status);
        return $this->save($status_data, $id);
    }
}
