<?php

class Pending_transactions_model extends Crud_model
{

    private $table = null;

    function __construct()
    {
        $this->table = 'pending_transactions';
        parent::__construct($this->table);
    }


    function get_details($options = array())
    {
        $this->table = $this->db->dbprefix('pending_transactions');
        $enteries = $this->db->dbprefix('pending_enteries');
        $projects_table = $this->db->dbprefix('projects');
        $where = "";

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $this->table.id=$id";
        }

        $manual_auto = get_array_value($options, "manual_auto");
        if ($manual_auto) {
            if ($manual_auto == 2) {
                $manual_auto = 0;
            }
            $where .= " AND $this->table.is_manual=$manual_auto";
        }

        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($this->table.date BETWEEN '$start_date' AND '$end_date') ";
        }

        $sql = "SELECT $this->table.*, a.credit, a.debit
        FROM $this->table 
                LEFT JOIN (SELECT SUM(IF(type='cr',amount, 0)) credit, 
                                   SUM(IF(type='dr',amount, 0)) debit, trans_id 
                            FROM  $enteries WHERE  $enteries.deleted = 0 GROUP BY trans_id) a
                ON (a.trans_id = $this->table.id)
                WHERE $this->table.deleted = 0  AND $this->table.type NOT LIKE '%Retail Invoice #%' $where ORDER BY $this->table.type DESC";

        return $this->db->query($sql);
    }

    function get_details_view($options = array())
    {
        $this->table = $this->db->dbprefix('pending_transactions');
        $enteries = $this->db->dbprefix('pending_enteries');

        $where = "";

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $this->table.id=$id";
        }

        $manual_auto = get_array_value($options, "manual_auto");
        if ($manual_auto) {
            if ($manual_auto == 2) {
                $manual_auto = 0;
            }
            $where .= " AND $this->table.is_manual=$manual_auto";
        }

        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($this->table.date BETWEEN '$start_date' AND '$end_date') ";
        }

        $sql = "SELECT $this->table.*, a.credit, a.debit FROM $this->table 
                LEFT JOIN (SELECT SUM(IF(type='cr',amount, 0)) credit, 
                                   SUM(IF(type='dr',amount, 0)) debit, trans_id 
                            FROM  $enteries WHERE  $enteries.deleted = 0 GROUP BY trans_id) a
                ON (a.trans_id = $this->table.id)
                WHERE $this->table.deleted = 0 $where ORDER BY $this->table.type DESC";

        return $this->db->query($sql);
    }
}
