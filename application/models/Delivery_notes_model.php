<?php

class Delivery_notes_model extends Crud_model
{

    private $table = null;

    function __construct()
    {
        $this->table = 'delivery_notes';
        parent::__construct($this->table, true);
        $this->init_activity_log($this->table, $this->table);
    }

    function get_details($options = array())
    {
        $delivery_notes_table = $this->db->dbprefix('delivery_notes');
        $clients_table = $this->db->dbprefix('clients');
        $projects_table = $this->db->dbprefix('projects');
        $invoices_table = $this->db->dbprefix('invoices');
        $users_table = $this->db->dbprefix('users');
        $log_table = $this->db->dbprefix('activity_logs');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $delivery_notes_table.id=$id";
        }
        $client_id = get_array_value($options, "client_id");
        if ($client_id) {
            $where .= " AND $delivery_notes_table.client_id=$client_id";
        }

        $exclude_draft = get_array_value($options, "exclude_draft");
        if ($exclude_draft) {
            $where .= " AND $delivery_notes_table.status!='draft' ";
        }

        $project_id = get_array_value($options, "project_id");
        if ($project_id) {
            $where .= " AND $delivery_notes_table.project_id=$project_id";
        }

        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($delivery_notes_table.delivery_note_date BETWEEN '$start_date' AND '$end_date') ";
        }

        $allowed_members = get_array_value($options, "allowed_members");
        if (is_array($allowed_members) && count($allowed_members)) {
            $allowed_members = join(",", $allowed_members);
            $where .= " AND log.user_id IN($allowed_members)";
        }

        //add filter by cost center id
        if ($this->login_user->cost_center_id > 0) {
            $cost_center_id = $this->login_user->cost_center_id;
            $where .= " AND $delivery_notes_table.cost_center_id = $cost_center_id";
        }

        $now = get_my_local_time("Y-m-d");
        $status = get_array_value($options, "status");

        //add filter by cost center id
        if( !can_view_all_cost_centers_data() && $this->login_user->cost_center_id > 0 ){
            $cost_center_id = $this->login_user->cost_center_id;
            $where .= " AND $invoices_table.cost_center_id = $cost_center_id";
        }


        $sql = "SELECT $delivery_notes_table.*, $clients_table.company_name, $projects_table.title AS project_title, log.user_id as log_user_id,
           log.created_at as create_time, log.create_user as create_user, $invoices_table.currency_rate_at_creation
        FROM $delivery_notes_table
        LEFT JOIN $clients_table ON $clients_table.id= $delivery_notes_table.client_id
        LEFT JOIN $invoices_table ON $invoices_table.id= $delivery_notes_table.invoice_id
        LEFT JOIN $projects_table ON $projects_table.id= $delivery_notes_table.project_id
        LEFT JOIN (SELECT a.*, CONCAT_WS(' ',b.first_name,b.last_name) as create_user, b.id as user_id  
                   FROM $log_table a
                   LEFT JOIN $users_table b on (a.created_by = b.id)
                   WHERE log_type = 'delivery_notes' and action = 'created') as log ON ($delivery_notes_table.id = log.log_type_id)
        WHERE $delivery_notes_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    //update e status
    function update_approval_status($id = 0, $status = "not_approved")
    {
        $status_data = array("status" => $status);
        return $this->save($status_data, $id);
    }

    //get delivery_notes dropdown list
    /*function get_delivery_notes_dropdown_list() {
        $delivery_notes_table = $this->db->dbprefix('delivery_notes');

        $sql = "SELECT $delivery_notes_table.id FROM $delivery_notes_table
                        WHERE $delivery_notes_table.deleted=0 
                        ORDER BY $delivery_notes_table.id DESC";

        return $this->db->query($sql);
    }*/
}
