<?php

class Transactions_model extends Crud_model
{

    private $table = null;

    function __construct()
    {
        $this->table = 'transactions';
        parent::__construct($this->table,true,true);
    }


    function get_details($options = array())
    {
        $this->table = $this->db->dbprefix('transactions');
        $pending_transactions = $this->db->dbprefix('pending_transactions');
        $enteries = $this->db->dbprefix('enteries');
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

        //add filter by cost center id
        if( !can_view_all_cost_centers_data() && $this->login_user->cost_center_id > 0 ){
            $cost_center_id = $this->login_user->cost_center_id;
            $where .= " AND $this->table.cost_center_id = $cost_center_id";
        }

        $sql = "SELECT $this->table.*, a.credit, a.debit
        FROM $this->table 
                LEFT JOIN (SELECT SUM(IF(type='cr',amount, 0)) credit, 
                                   SUM(IF(type='dr',amount, 0)) debit, trans_id 
                            FROM  $enteries WHERE  $enteries.deleted = 0 GROUP BY trans_id) a
                ON (a.trans_id = $this->table.id)
                WHERE $this->table.deleted = 0  AND $this->table.type NOT LIKE '%Retail Invoice #%' $where ORDER BY $this->table.type DESC";

        if (strcasecmp($this->db->dbprefix, 'Integrated_Banners_') == 0) {
            $where2 = str_replace($this->table, $pending_transactions, $where);
            $sql = "SELECT $this->table.*,null as approved, a.credit, a.debit, $projects_table.title AS project_title
            FROM $this->table 
                    LEFT JOIN (SELECT SUM(IF(type='cr',amount, 0)) credit, 
                                       SUM(IF(type='dr',amount, 0)) debit, trans_id 
                                FROM  $enteries WHERE  $enteries.deleted = 0 GROUP BY trans_id) a
                    ON (a.trans_id = $this->table.id)
                    LEFT JOIN $projects_table ON $projects_table.id = $this->table.project_id
                    WHERE $this->table.deleted = 0  AND $this->table.type NOT LIKE '%Retail Invoice #%' $where
            
            UNION ALL
            
            SELECT $pending_transactions.* ,null as credit,null as debit, $projects_table.title AS project_title
            FROM $pending_transactions 
            LEFT JOIN $projects_table ON $projects_table.id = $pending_transactions.project_id
            WHERE $pending_transactions.deleted = 0 AND $pending_transactions.approved = 0 $where2
            
            ORDER BY type,date DESC
                    ";
        }
        return $this->db->query($sql);
    }

    function get_details_view($options = array())
    {
        $this->table = $this->db->dbprefix('transactions');
        $enteries = $this->db->dbprefix('enteries');

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
