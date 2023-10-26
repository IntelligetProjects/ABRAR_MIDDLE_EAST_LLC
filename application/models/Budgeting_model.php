<?php

class Budgeting_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'budgeting';
        parent::__construct($this->table);
        $this->init_activity_log($this->table, $this->table);
    }

    function get_details($options = array()) {
      
        $budgeting_table = $this->db->dbprefix('budgeting');
        $clients_table = $this->db->dbprefix('clients');
        $taxes_table = $this->db->dbprefix('taxes');
        $budgeting_items_table = $this->db->dbprefix('budgeting_items');
        $projects_table = $this->db->dbprefix('projects');
        $log_table = $this->db->dbprefix('activity_logs');
        $users_table = $this->db->dbprefix('users');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $budgeting_table.id=$id";
        }
       

        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($budgeting_table.estimate_date BETWEEN '$start_date' AND '$end_date') ";
        }

        $status = get_array_value($options, "status");
        if ($status) {
            $where .= " AND $budgeting_table.status='$status'";
        }

        $exclude_draft = get_array_value($options, "exclude_draft");
        if ($exclude_draft) {
            $where .= " AND $budgeting_table.status!='draft' ";
        }


        //prepare custom fild binding query
        $custom_fields = get_array_value($options, "custom_fields");
        $custom_field_query_info = $this->prepare_custom_field_query_string("budgeting", $custom_fields, $budgeting_table);
        $select_custom_fieds = get_array_value($custom_field_query_info, "select_string");
        $join_custom_fieds = get_array_value($custom_field_query_info, "join_string");

        $allowed_members = get_array_value($options, "allowed_members");
        if (is_array($allowed_members) && count($allowed_members)) {
            $allowed_members = join(",", $allowed_members);
            // $where .= " AND log.user_id IN($allowed_members)";
        }


        $sql = "SELECT $budgeting_table.*, $projects_table.title as project_title $select_custom_fieds
        FROM $budgeting_table

        LEFT JOIN $projects_table ON $projects_table.id= $budgeting_table.project_id
       
        
         $join_custom_fieds
        WHERE $budgeting_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_estimate_total_summary($estimate_id = 0) {
        $budgeting_items_table = $this->db->dbprefix('budgeting_items');
        $budgeting_table = $this->db->dbprefix('budgeting');
        $clients_table = $this->db->dbprefix('clients');
        $taxes_table = $this->db->dbprefix('taxes');

        $item_sql = "SELECT SUM($budgeting_items_table.total) AS estimate_subtotal, SUM($budgeting_items_table.total*tax_table.percentage*0.01) AS tax, SUM($budgeting_items_table.total*tax_table2.percentage*0.01) AS tax2
        FROM $budgeting_items_table
        LEFT JOIN $budgeting_table ON $budgeting_table.id= $budgeting_items_table.estimate_id
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $budgeting_items_table.tax_id
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table2 ON tax_table2.id = $budgeting_items_table.tax_id2    
        WHERE $budgeting_items_table.deleted=0 AND $budgeting_items_table.estimate_id=$estimate_id AND $budgeting_table.deleted=0";
        $item = $this->db->query($item_sql)->row();


        $estimate_sql = "SELECT $budgeting_table.*, tax_table.percentage AS tax_percentage, tax_table.title AS tax_name,
            tax_table2.percentage AS tax_percentage2, tax_table2.title AS tax_name2
        FROM $budgeting_table
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $budgeting_table.tax_id
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table2 ON tax_table2.id = $budgeting_table.tax_id2
        WHERE $budgeting_table.deleted=0 AND $budgeting_table.id=$estimate_id";
        $estimate = $this->db->query($estimate_sql)->row();

        $client_sql = "SELECT $clients_table.currency_symbol, $clients_table.currency FROM $clients_table WHERE $clients_table.id=$estimate->client_id";
        $client = $this->db->query($client_sql)->row();


        $result = new stdClass();
        $result->estimate_subtotal = $item->estimate_subtotal;


        $result->tax_percentage = $estimate->tax_percentage;
        $result->tax_percentage2 = $estimate->tax_percentage2;
        $result->tax_name = lang("tax");
        $result->tax_name2 = lang("tax2");
        $result->tax = 0;
        $result->tax2 = 0;

        $estimate_subtotal = $result->estimate_subtotal;
        $estimate_subtotal_for_taxes = $estimate_subtotal;
            $estimate_subtotal_for_taxes = $estimate_subtotal - ($estimate->discount_amount_type == "percentage" ? ($estimate_subtotal * ($estimate->discount_amount / 100)) : $estimate->discount_amount);

        $result->tax = $item->tax;

            
        $result->tax2 = $item->tax2;
        $estimate_total = $item->estimate_subtotal + $result->tax + $result->tax2;

        $result->discount_total = 0;

        $result->discount_total = $estimate->discount_amount_type == "percentage" ? ($estimate_subtotal * ($estimate->discount_amount / 100)) : $estimate->discount_amount;

        $result->discount_type = $estimate->discount_type;

        $result->estimate_total = $estimate_total - number_format($result->discount_total, 2, ".", "");

        $result->currency_symbol = $client->currency_symbol ? $client->currency_symbol : get_setting("currency_symbol");
        $result->currency = $client->currency ? $client->currency : get_setting("default_currency");
        return $result;
    }

    //get estimate last id
    function get_estimate_last_id() {
        $budgeting_table = $this->db->dbprefix('budgeting');

        $sql = "SELECT MAX($budgeting_table.id) AS last_id FROM $budgeting_table";

        return $this->db->query($sql)->row()->last_id;
    }

    //save initial number of estimate
    function save_initial_number_of_estimate($value) {
        $budgeting_table = $this->db->dbprefix('budgeting');

        $sql = "ALTER TABLE $budgeting_table AUTO_INCREMENT=$value;";

        return $this->db->query($sql);
    }

}
