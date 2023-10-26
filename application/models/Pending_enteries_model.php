<?php

class Pending_enteries_model extends Crud_model
{

    private $table = null;

    function __construct()
    {
        // $this->table = 'enteries';
        $this->table = $this->db->dbprefix('pending_enteries');
        parent::__construct($this->table);
    }


    function get_details($options = array())
    {
        $accounts = $this->db->dbprefix('accounts');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $this->table.id=$id";
        }

        $trans_id = get_array_value($options, "trans_id");
        if ($trans_id) {
            $where .= " AND $this->table.trans_id=$trans_id";
        }

        $sql = "SELECT *, $accounts.acc_name, $this->table.id as entry_id, $this->table.branch_id FROM $this->table 
                LEFT JOIN $accounts on ($accounts.id = $this->table.account)
        WHERE $this->table.deleted = 0 $where ORDER BY $this->table.type ASC ";

        return $this->db->query($sql);
    }


    function get_total_amount($options = array())
    {

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $this->table.id=$id";
        }

        $trans_id = get_array_value($options, "trans_id");
        if ($trans_id) {
            $where .= " AND $this->table.trans_id=$trans_id";
        }

        $type = get_array_value($options, "type");
        if ($type) {
            $where .= " AND $this->table.type='$type'";
        }

        $sql = "SELECT SUM(amount) as total_amount
        FROM $this->table 
        WHERE $this->table.deleted = 0 $where";

        return $this->db->query($sql)->row()->total_amount;
    }


    function get_total_voucher($options = array())
    {

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $this->table.id=$id";
        }

        $trans_id = get_array_value($options, "trans_id");
        if ($trans_id) {
            $where .= " AND $this->table.trans_id=$trans_id";
        }

        $type = 'dr';
        $type1 = get_array_value($options, "type");
        if ($type1) {
            $type = $type1;
        }

        $no_bank_cash = get_array_value($options, "no_bank_cash");
        if ($no_bank_cash) {
            $acc = 8;
            $list = $this->Accounts_model->get_children($acc)->list;
            if ($list) {
                $list .= "," . $acc;
            } else {
                $list .= $acc;
            }

            $where .= " AND $this->table.account NOT IN ($list)";
        }

        $sql = "SELECT SUM(CASE WHEN $this->table.type = '$type' THEN (amount) ELSE (amount * -1) END) as total_amount
        FROM $this->table 
        WHERE $this->table.deleted = 0 $where";

        return $this->db->query($sql)->row()->total_amount;
    }

    function copy_to_real_enteries_table($trans_id,$pending_transs_id)
    {
        $enteries = $this->db->dbprefix('enteries');

        $sql =  "INSERT INTO $enteries (trans_id, account, type, amount, narration, branch_id, concerned_person, unit, reference)
                SELECT $trans_id,account, type, amount, narration, branch_id, concerned_person, unit, reference
                FROM $this->table WHERE $this->table.trans_id = $pending_transs_id AND $this->table.deleted = 0";

        return $this->db->query($sql);
    }
}
