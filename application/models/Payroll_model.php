<?php

class Payroll_model extends Crud_model
{

    private $table = null;


    function __construct()
    {
        $this->table = 'payroll';
        parent::__construct($this->table, true);
        $this->init_activity_log($this->table, $this->table);
    }


    function get_details($options = array())
    {

        $payroll_table = $this->db->dbprefix('payroll');


        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $payroll_table.id=$id";
        }

        $month = get_array_value($options, "month");
        if ($month) {
            $where = " AND $payroll_table.month = '$month'";
        }

        //add filter by cost center id
        if ( !can_view_all_cost_centers_data() && $this->login_user->cost_center_id > 0) {
            $cost_center_id = $this->login_user->cost_center_id;
            $where .= " AND $payroll_table.cost_center_id = $cost_center_id";
        }

        $sql = "SELECT $payroll_table.*
                FROM $payroll_table
                WHERE $payroll_table.deleted=0 $where";


        return $this->db->query($sql);
    }
}
