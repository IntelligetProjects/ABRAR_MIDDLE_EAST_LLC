<?php

class Estimates_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'estimates';
        parent::__construct($this->table, true, true);
        $this->init_activity_log($this->table, $this->table);
    }

    function get_details($options = array()) {
        $estimates_table = $this->db->dbprefix('estimates');
        $clients_table = $this->db->dbprefix('clients');
        $taxes_table = $this->db->dbprefix('taxes');
        $estimate_items_table = $this->db->dbprefix('estimate_items');
        $projects_table = $this->db->dbprefix('projects');
        $log_table = $this->db->dbprefix('activity_logs');
        $users_table = $this->db->dbprefix('users');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $estimates_table.id=$id";
        }
        $client_id = get_array_value($options, "client_id");
        if ($client_id) {
            $where .= " AND $estimates_table.client_id=$client_id";
        }

        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($estimates_table.estimate_date BETWEEN '$start_date' AND '$end_date') ";
        }

        $after_tax_1 = "(IFNULL(tax_table.percentage,0)/100*IFNULL(items_table.estimate_value,0))";
        $after_tax_2 = "(IFNULL(tax_table2.percentage,0)/100*IFNULL(items_table.estimate_value,0))";

        $discountable_estimate_value = "IF($estimates_table.discount_type='after_tax', (IFNULL(items_table.estimate_value,0) + $after_tax_1 + $after_tax_2), IFNULL(items_table.estimate_value,0) )";

        $discount_amount = "IF($estimates_table.discount_amount_type='percentage', IFNULL($estimates_table.discount_amount,0)/100* $discountable_estimate_value, $estimates_table.discount_amount)";

        $before_tax_1 = "(IFNULL(tax_table.percentage,0)/100* (IFNULL(items_table.estimate_value,0)- $discount_amount))";
        $before_tax_2 = "(IFNULL(tax_table2.percentage,0)/100* (IFNULL(items_table.estimate_value,0)- $discount_amount))";

        $estimate_value_calculation = "(
            IFNULL(items_table.estimate_value,0)+
            IF($estimates_table.discount_type='before_tax',  ($before_tax_1+ $before_tax_2), ($after_tax_1 + $after_tax_2))
            - $discount_amount
           )";

        $status = get_array_value($options, "status");
        if ($status) {
            $where .= " AND $estimates_table.status='$status'";
        }

        $exclude_draft = get_array_value($options, "exclude_draft");
        if ($exclude_draft) {
            $where .= " AND $estimates_table.status!='draft' ";
        }


        //add filter by cost center id
        if( !can_view_all_cost_centers_data() && $this->login_user->cost_center_id > 0){
            $cost_center_id = $this->login_user->cost_center_id;
            $where .= " AND $estimates_table.cost_center_id = $cost_center_id";
        }

        //prepare custom fild binding query
        $custom_fields = get_array_value($options, "custom_fields");
        $custom_field_query_info = $this->prepare_custom_field_query_string("estimates", $custom_fields, $estimates_table);
        $select_custom_fieds = get_array_value($custom_field_query_info, "select_string");
        $join_custom_fieds = get_array_value($custom_field_query_info, "join_string");

        $allowed_members = get_array_value($options, "allowed_members");
        if (is_array($allowed_members) && count($allowed_members)) {
            $allowed_members = join(",", $allowed_members);
            $where .= " AND log.user_id IN($allowed_members)";
        }


        $sql = "SELECT $estimates_table.*, $clients_table.currency, $clients_table.currency_symbol, $clients_table.company_name, $clients_table.phone, $projects_table.title as project_title, $clients_table.is_lead,
           $estimate_value_calculation AS estimate_value,items_table.item_discount as item_discount, items_table.tax as tax_value,items_table.tax_after_discount as tax_after_discount, tax_table.percentage AS tax_percentage, tax_table2.percentage AS tax_percentage2 $select_custom_fieds
        FROM $estimates_table
        LEFT JOIN $clients_table ON $clients_table.id= $estimates_table.client_id
        LEFT JOIN $projects_table ON $projects_table.id= $estimates_table.project_id
        LEFT JOIN (SELECT a.*, CONCAT_WS(' ',b.first_name,b.last_name) as create_user, b.id as user_id  
                   FROM $log_table a
                   LEFT JOIN $users_table b on (a.created_by = b.id)
                   WHERE log_type = 'estimates' and action = 'created') as log ON ($estimates_table.id = log.log_type_id)
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $estimates_table.tax_id
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table2 ON tax_table2.id = $estimates_table.tax_id2 
        LEFT JOIN (SELECT estimate_id, SUM(total-IF($estimate_items_table.discount_amount_type='percentage',$estimate_items_table.total*$estimate_items_table.discount_amount/100,$estimate_items_table.discount_amount)) AS estimate_value, 
        SUM(IF($estimate_items_table.discount_amount_type='percentage',$estimate_items_table.total*$estimate_items_table.discount_amount/100,$estimate_items_table.discount_amount)) AS item_discount, 
        SUM(total*tax_table.percentage*0.01) AS tax,
        SUM(($estimate_items_table.total-IF($estimate_items_table.discount_amount_type='percentage',$estimate_items_table.total*$estimate_items_table.discount_amount/100,$estimate_items_table.discount_amount))*tax_table.percentage*0.01) AS tax_after_discount

        FROM $estimate_items_table
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $estimate_items_table.tax_id
        WHERE $estimate_items_table.deleted=0 GROUP BY estimate_id) AS items_table ON items_table.estimate_id = $estimates_table.id 
        $join_custom_fieds
        WHERE $estimates_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_estimate_total_summary($estimate_id = 0) {
        $estimate_items_table = $this->db->dbprefix('estimate_items');
        $estimates_table = $this->db->dbprefix('estimates');
        $clients_table = $this->db->dbprefix('clients');
        $taxes_table = $this->db->dbprefix('taxes');

        $item_sql = "SELECT SUM(IF($estimate_items_table.discount_amount_type='percentage',$estimate_items_table.total*$estimate_items_table.discount_amount/100,$estimate_items_table.discount_amount)) AS item_discount, SUM($estimate_items_table.total) AS estimate_subtotal, SUM($estimate_items_table.total*tax_table.percentage*0.01) AS tax, SUM($estimate_items_table.total*tax_table2.percentage*0.01) AS tax2
        , SUM(($estimate_items_table.total-IF($estimate_items_table.discount_amount_type='percentage',$estimate_items_table.total*$estimate_items_table.discount_amount/100,$estimate_items_table.discount_amount))*tax_table.percentage*0.01) AS tax_after_discount
        FROM $estimate_items_table
        LEFT JOIN $estimates_table ON $estimates_table.id= $estimate_items_table.estimate_id
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $estimate_items_table.tax_id
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table2 ON tax_table2.id = $estimate_items_table.tax_id2    
        WHERE $estimate_items_table.deleted=0 AND $estimate_items_table.estimate_id=$estimate_id AND $estimates_table.deleted=0";
        $item = $this->db->query($item_sql)->row();


        $estimate_sql = "SELECT $estimates_table.*, tax_table.percentage AS tax_percentage, tax_table.title AS tax_name,
            tax_table2.percentage AS tax_percentage2, tax_table2.title AS tax_name2
        FROM $estimates_table
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $estimates_table.tax_id
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table2 ON tax_table2.id = $estimates_table.tax_id2
        WHERE $estimates_table.deleted=0 AND $estimates_table.id=$estimate_id";
        $estimate = $this->db->query($estimate_sql)->row();

        $client_sql = "SELECT $clients_table.currency_symbol, $clients_table.currency FROM $clients_table WHERE $clients_table.id=$estimate->client_id";
        $client = $this->db->query($client_sql)->row();


        $result = new stdClass();
        $result->estimate_subtotal = $item->estimate_subtotal- $item->item_discount;


        $result->tax_percentage = $estimate->tax_percentage;
        $result->tax_percentage2 = $estimate->tax_percentage2;
        $result->tax_name = lang("tax");
        $result->tax_name2 = lang("tax2");
        $result->tax = 0;
        $result->tax2 = 0;
        $result->tax_after_discount = $item->tax_after_discount;
        $estimate_subtotal = $result->estimate_subtotal;
        $estimate_subtotal_for_taxes = $estimate_subtotal;
            $estimate_subtotal_for_taxes = $estimate_subtotal - ($estimate->discount_amount_type == "percentage" ? ($estimate_subtotal * ($estimate->discount_amount / 100)) : $estimate->discount_amount);
        $result->item_discount = $item->item_discount;
        $result->tax = $item->tax;

            
        $result->tax2 = $item->tax2;
        $estimate_total = $item->estimate_subtotal + $result->tax + $result->tax2- $item->item_discount;

        $result->discount_total = 0;

        $result->discount_total = $estimate->discount_amount_type == "percentage" ? ($estimate_subtotal * ($estimate->discount_amount / 100)) : $estimate->discount_amount;

        $result->discount_type = $estimate->discount_type;

        // $result->estimate_total = $estimate_total - number_format($result->discount_total, 2, ".", "");
        // if( $this->db->dbprefix=='Tadqeeq'){
        //     $result->estimate_total = $item->estimate_subtotal + $item->tax_after_discount + $result->tax2-$item->item_discount;
        // }else{
        //     $result->estimate_total = $item->estimate_subtotal + $result->tax + $result->tax2 - $result->discount_total ;
        // }
        $result->estimate_total = $item->estimate_subtotal + $item->tax_after_discount + $result->tax2-$item->item_discount;
      
        $result->currency_symbol = $client->currency_symbol ? $client->currency_symbol : get_setting("currency_symbol");
        $result->currency = $client->currency ? $client->currency : get_setting("default_currency");
        return $result;
    }

    //get estimate last id
    function get_estimate_last_id() {
        $estimates_table = $this->db->dbprefix('estimates');

        $sql = "SELECT MAX($estimates_table.id) AS last_id FROM $estimates_table";

        return $this->db->query($sql)->row()->last_id;
    }

    //save initial number of estimate
    function save_initial_number_of_estimate($value) {
        $estimates_table = $this->db->dbprefix('estimates');

        $sql = "ALTER TABLE $estimates_table AUTO_INCREMENT=$value;";

        return $this->db->query($sql);
    }

}
