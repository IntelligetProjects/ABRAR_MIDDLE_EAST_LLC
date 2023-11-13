<?php

class Clients_model extends Crud_model
{

    private $table = null;

    function __construct()
    {
        $this->table = 'clients';
        parent::__construct($this->table, true);
        $this->init_activity_log($this->table, $this->table);
    }

    function get_details($options = array())
    {
        $clients_table = $this->db->dbprefix('clients');
        $projects_table = $this->db->dbprefix('projects');
        $users_table = $this->db->dbprefix('users');
        $invoices_table = $this->db->dbprefix('invoices');
        $invoice_payments_table = $this->db->dbprefix('invoice_payments');
        $invoice_items_table = $this->db->dbprefix('invoice_items');
        $taxes_table = $this->db->dbprefix('taxes');
        $client_groups_table = $this->db->dbprefix('client_groups');
        $lead_status_table = $this->db->dbprefix('lead_status');
        $log_table = $this->db->dbprefix('activity_logs');
        $taxes_table = $this->db->dbprefix('taxes');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $clients_table.id=$id";
        }

        $custom_field_type = "client";

        $leads_only = get_array_value($options, "leads_only");
        if ($leads_only) {
            $custom_field_type = "leads";
            $where .= " AND $clients_table.is_lead=1";
        }

        $status = get_array_value($options, "status");
        if ($status) {
            $where .= " AND $clients_table.lead_status_id='$status'";
        }

        $source = get_array_value($options, "source");
        if ($source) {
            $where .= " AND $clients_table.lead_source_id='$source'";
        }

        $owner_id = get_array_value($options, "owner_id");
        if ($owner_id) {
            $where .= " AND $clients_table.owner_id='$owner_id'";
        }

        if (!$id && !$leads_only) {
            //only clients
            $where .= " AND $clients_table.is_lead=0";
        }

        $group_id = get_array_value($options, "group_id");
        if ($group_id) {
            $where .= " AND FIND_IN_SET('$group_id', $clients_table.group_ids)";
        }

        $allowed_members = get_array_value($options, "allowed_members");
        if (is_array($allowed_members) && count($allowed_members)) {
            $allowed_members = join(",", $allowed_members);
            $where .= " AND log.user_id IN($allowed_members)";
        }


        //add filter by cost center id
        if ( !can_view_all_cost_centers_data() && $this->login_user->cost_center_id > 0) {
            $cost_center_id = $this->login_user->cost_center_id;
            $where .= " AND $clients_table.cost_center_id = $cost_center_id";
        }


        //prepare custom fild binding query
        $custom_fields = get_array_value($options, "custom_fields");
        $custom_field_query_info = $this->prepare_custom_field_query_string($custom_field_type, $custom_fields, $clients_table);
        $select_custom_fieds = get_array_value($custom_field_query_info, "select_string");
        $join_custom_fieds = get_array_value($custom_field_query_info, "join_string");

        $invoice_value_calculation_query = "(SUM" . $this->_get_invoice_value_calculation_query($invoices_table) . ")";

        $this->db->query('SET SQL_BIG_SELECTS=1');

        $invoice_value_select = "IFNULL(invoice_details.invoice_value,0)";
        $payment_value_select = "IFNULL(invoice_details.payment_received,0)";

        $sql = "SELECT $clients_table.*, CONCAT($users_table.first_name, ' ', $users_table.last_name) AS primary_contact, $users_table.id AS primary_contact_id, $users_table.image AS contact_avatar,  project_table.total_projects, $payment_value_select AS payment_received $select_custom_fieds,
                IF((($invoice_value_select > $payment_value_select) AND ($invoice_value_select - $payment_value_select) <0.05), $payment_value_select, $invoice_value_select) AS invoice_value,
                (SELECT GROUP_CONCAT($client_groups_table.title) FROM $client_groups_table WHERE FIND_IN_SET($client_groups_table.id, $clients_table.group_ids)) AS client_groups, $lead_status_table.title AS lead_status_title,  $lead_status_table.color AS lead_status_color,
                owner_details.owner_name, owner_details.owner_avatar
        FROM $clients_table
        LEFT JOIN $users_table ON $users_table.client_id = $clients_table.id AND $users_table.deleted=0 AND $users_table.is_primary_contact=1 
        LEFT JOIN (SELECT client_id, COUNT(id) AS total_projects FROM $projects_table WHERE deleted=0 GROUP BY client_id) AS project_table ON project_table.client_id= $clients_table.id
        LEFT JOIN (SELECT client_id, SUM(payments_table.payment_received) as payment_received, $invoice_value_calculation_query as invoice_value FROM $invoices_table
                   LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $invoices_table.tax_id
                   LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table2 ON tax_table2.id = $invoices_table.tax_id2 
                   LEFT JOIN (SELECT invoice_id, SUM(amount) AS payment_received FROM $invoice_payments_table WHERE deleted=0 AND status='approved' GROUP BY invoice_id) AS payments_table ON payments_table.invoice_id=$invoices_table.id AND $invoices_table.deleted=0 AND $invoices_table.status='not_paid' and $invoices_table.approval_status = 'approved'
                   LEFT JOIN (SELECT invoice_id, SUM(total + IFNULL((total*tax_table.percentage*0.01),0) ) AS invoice_value FROM $invoice_items_table 
                   LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $invoice_items_table.tax_id
                   WHERE $invoice_items_table.deleted=0 GROUP BY invoice_id) AS items_table ON items_table.invoice_id=$invoices_table.id AND $invoices_table.deleted=0 AND $invoices_table.status='not_paid'
                   WHERE $invoices_table.status='not_paid' and $invoices_table.approval_status = 'approved'
                   GROUP BY $invoices_table.client_id    
                   ) AS invoice_details ON invoice_details.client_id= $clients_table.id 
        LEFT JOIN $lead_status_table ON $clients_table.lead_status_id = $lead_status_table.id 
        LEFT JOIN (SELECT a.*, CONCAT_WS(' ',b.first_name,b.last_name) as create_user, b.id as user_id  
                   FROM $log_table a
                   LEFT JOIN $users_table b on (a.created_by = b.id)
                   WHERE log_type = 'clients' and action = 'created') as log ON ($clients_table.id = log.log_type_id)
        LEFT JOIN (SELECT $users_table.id, CONCAT($users_table.first_name, ' ', $users_table.last_name) AS owner_name, $users_table.image AS owner_avatar FROM $users_table WHERE $users_table.deleted=0 AND $users_table.user_type='staff') AS owner_details ON owner_details.id=$clients_table.owner_id
        $join_custom_fieds               
        WHERE $clients_table.deleted=0 $where";
        return $this->db->query($sql);
    }
    function get_sales_retuen_sum($client_id)
    {
        $sale_return_items_table = $this->db->dbprefix('sale_return_items');
        $sale_returns_table = $this->db->dbprefix('sale_returns');
        $purchase_order_items_table = $this->db->dbprefix('invoice_items');
        $purchase_order_payments_table = $this->db->dbprefix('invoice_payments');
        $purchase_orders_table = $this->db->dbprefix('invoices');
        $suppliers_table = $this->db->dbprefix('clients');
        $taxes_table = $this->db->dbprefix('taxes');

        $item_sql = "SELECT SUM($purchase_order_items_table.rate*$sale_return_items_table.quantity) AS purchase_order_subtotal,
        SUM(($purchase_order_items_table.rate*$sale_return_items_table.quantity-IF($purchase_order_items_table.discount_amount_type='percentage',$purchase_order_items_table.rate*$sale_return_items_table.quantity*$purchase_order_items_table.discount_amount/100,$purchase_order_items_table.discount_amount))*tax_table.percentage*0.01) AS tax,
         SUM($purchase_order_items_table.rate*$sale_return_items_table.quantity*tax_table2.percentage*0.01) AS tax2
       , SUM(IF($purchase_order_items_table.discount_amount_type='percentage',$purchase_order_items_table.total*$purchase_order_items_table.discount_amount/100,$purchase_order_items_table.discount_amount)) AS item_discount 
       FROM $sale_return_items_table

       LEFT JOIN $sale_returns_table ON $sale_returns_table.id=$sale_return_items_table.sale_return_id 
       LEFT JOIN $purchase_order_items_table ON $purchase_order_items_table.id=$sale_return_items_table.invoice_item_id

       LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $purchase_order_items_table.tax_id
       LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table2 ON tax_table2.id = $purchase_order_items_table.tax_id2

       WHERE $sale_return_items_table.deleted=0 AND $sale_returns_table.client_id =$client_id AND $sale_returns_table.deleted=0";
        //    WHERE $sale_return_items_table.deleted=0 AND $sale_return_items_table.sale_return_id=$purchase_order_id AND $sale_returns_table.deleted=0";
        $item = $this->db->query($item_sql)->row();

        $result = new stdClass();
        $result->purchase_order_subtotal = $item->purchase_order_subtotal;
        $result->tax_name = /*$estimate->tax_name*/ lang("tax");
        $result->tax_name2 = /*$estimate->tax_name2*/ lang("tax2");

        $purchase_order_subtotal = $result->purchase_order_subtotal;

        // $result->tax = $item->tax;
        $result->tax = $item->tax;

        $result->tax2 = $item->tax2;

        $result->purchase_order_total = $item->purchase_order_subtotal + $result->tax + $result->tax2;

        // $discount_percentage = empty($total_invoice) ? 0 : $discount/$total_invoice;
        $result->item_discount = $item->item_discount;
        // $result->discount = number_format($discount_percentage * $result->purchase_order_subtotal, 3, ".", "");
        $result->discount = number_format($item->item_discount, 3, ".", "");

        $result->total = number_format($result->purchase_order_total + $result->tax  - $result->discount, 3, ".", "");

        return $result;
    }
    function get_payments_sum($client_id = 0)
    {
        $invoice_payments_table = $this->db->dbprefix('invoice_payments');
        $invoices_table = $this->db->dbprefix('invoices');

        $payment_sql = "SELECT SUM($invoice_payments_table.amount) as total_payments FROM $invoices_table INNER JOIN $invoice_payments_table
        ON $invoice_payments_table.invoice_id = $invoices_table.id 
        WHERE $invoices_table.client_id=$client_id AND  $invoices_table.deleted=0 AND $invoice_payments_table.`status`='approved'
        AND $invoice_payments_table.deleted=0";
        $total_payment = $this->db->query($payment_sql)->row();
        return $total_payment;
    }
    function get_invoices_sum($client_id = 0)
    {
        $invoice_items_table = $this->db->dbprefix('invoice_items');
        $invoice_payments_table = $this->db->dbprefix('invoice_payments');
        $invoices_table = $this->db->dbprefix('invoices');
        $clients_table = $this->db->dbprefix('clients');
        $taxes_table = $this->db->dbprefix('taxes');

        $item_sql = "SELECT SUM(IF($invoice_items_table.discount_amount_type='percentage',$invoice_items_table.total*$invoice_items_table.discount_amount/100,$invoice_items_table.discount_amount)) AS item_discount ,SUM($invoice_items_table.total) AS invoice_subtotal, SUM($invoice_items_table.total*tax_table.percentage*0.01) AS tax,SUM(tax_table.percentage*0.01) AS pure_tax, SUM($invoice_items_table.total*tax_table2.percentage*0.01) AS tax2,
        SUM(($invoice_items_table.total-IF($invoice_items_table.discount_amount_type='percentage',$invoice_items_table.total*$invoice_items_table.discount_amount/100,$invoice_items_table.discount_amount))*tax_table.percentage*0.01) AS tax_after_discount
        FROM $invoice_items_table
        LEFT JOIN $invoices_table ON $invoices_table.id= $invoice_items_table.invoice_id    
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $invoice_items_table.tax_id
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table2 ON tax_table2.id = $invoice_items_table.tax_id2
        WHERE $invoice_items_table.deleted=0 AND $invoice_items_table.invoice_id=$invoices_table.id AND $invoices_table.deleted=0 AND $invoices_table.client_id=$client_id";
        $item = $this->db->query($item_sql)->row();

        $payment_sql = "SELECT SUM($invoice_payments_table.amount) AS total_paid
        FROM $invoice_payments_table
        LEFT JOIN $invoices_table ON $invoice_payments_table.invoice_id=$invoices_table.id
        WHERE $invoice_payments_table.deleted=0 AND $invoice_payments_table.status= 'approved'";
        $payment = $this->db->query($payment_sql)->row();

        $invoice_sql = "SELECT $invoices_table.*, tax_table.percentage AS tax_percentage, tax_table.title AS tax_name,
            tax_table2.percentage AS tax_percentage2, tax_table2.title AS tax_name2
        FROM $invoices_table
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $invoices_table.tax_id
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table2 ON tax_table2.id = $invoices_table.tax_id2
        WHERE $invoices_table.deleted=0 AND $invoices_table.client_id=$client_id";
        $invoice = $this->db->query($invoice_sql)->row();

        $client_sql = "SELECT $clients_table.currency_symbol, $clients_table.currency FROM $clients_table WHERE $clients_table.id=$client_id";
        $client = $this->db->query($client_sql)->row();


        $result = new stdClass();
        $result->invoice_subtotal = $item->invoice_subtotal;
        $result->tax_percentage = $invoice ? $invoice->tax_percentage : 0.00;
        $result->tax_percentage2 = $invoice ? $invoice->tax_percentage2 : 0.00;
        $result->tax_name = /*$estimate->tax_name*/ lang("tax");
        $result->tax_name2 = /*$estimate->tax_name2*/ lang("tax2");

        $result->tax = 0;
        $result->tax2 = 0;

        $invoice_subtotal = $result->invoice_subtotal;
        $invoice_subtotal_for_taxes = $invoice_subtotal;
        //if ($invoice->discount_type == "before_tax") {
        // $invoice_subtotal_for_taxes = $invoice_subtotal - ($invoice->discount_amount_type == "percentage" ? ($result->invoice_subtotal * ($invoice->discount_amount / 100)) : $invoice->discount_amount);
        //}

        //if ($invoice->tax_percentage) {
        $result->tax = $item->tax;
        $result->pure_tax = $item->pure_tax;
        $result->tax_after_discount = $item->tax_after_discount;
        $result->item_discount = $item->item_discount;
        $bill_tax = $item->pure_tax * ($item->invoice_subtotal - $item->item_discount);
        // echo $bill_tax; die();
        //}
        //if ($invoice->tax_percentage2) {
        $result->tax2 = $item->tax2;
        //}


        $result->total_paid = $payment->total_paid;

        $result->currency_symbol = $client->currency_symbol ? $client->currency_symbol : get_setting("currency_symbol");
        $result->currency = $client->currency ? $client->currency : get_setting("default_currency");


        $result->discount_total = 0;

        if ($invoice) {
            $result->discount_total = $invoice->discount_amount_type == "percentage" ? ($invoice_subtotal * ($invoice->discount_amount / 100)) : $invoice->discount_amount;
        } else {
            $result->discount_total =  0.00;
        }


        $result->discount_type =  $invoice ?
            $invoice->discount_type
            : 0.00;
        $result->tax = $item->pure_tax * ($result->invoice_subtotal - $result->discount_total);

        $result->invoice_total = $item->invoice_subtotal + $item->tax_after_discount + $result->tax2 - $item->item_discount;
        $result->total_after_discount = number_format($result->invoice_total, 2, ".", "") - number_format($result->discount_total, 2, ".", "");

        $result->balance_due = $result->invoice_total - number_format($payment->total_paid, 2, ".", "");
        return $result;
    }

    function get_primary_contact($client_id = 0, $info = false)
    {
        $users_table = $this->db->dbprefix('users');

        $sql = "SELECT $users_table.id, $users_table.first_name, $users_table.last_name
        FROM $users_table
        WHERE $users_table.deleted=0 AND $users_table.client_id=$client_id AND $users_table.is_primary_contact=1";
        $result = $this->db->query($sql);
        if ($result->num_rows()) {
            if ($info) {
                return $result->row();
            } else {
                return $result->row()->id;
            }
        }
    }

    function add_remove_star($project_id, $user_id, $type = "add")
    {
        $clients_table = $this->db->dbprefix('clients');

        $action = " CONCAT($clients_table.starred_by,',',':$user_id:') ";
        $where = " AND FIND_IN_SET(':$user_id:',$clients_table.starred_by) = 0"; //don't add duplicate

        if ($type != "add") {
            $action = " REPLACE($clients_table.starred_by, ',:$user_id:', '') ";
            $where = "";
        }

        $sql = "UPDATE $clients_table SET $clients_table.starred_by = $action
        WHERE $clients_table.id=$project_id $where";
        return $this->db->query($sql);
    }

    function get_starred_clients($user_id)
    {
        $clients_table = $this->db->dbprefix('clients');

        $sql = "SELECT $clients_table.id,  $clients_table.company_name
        FROM $clients_table
        WHERE $clients_table.deleted=0 AND FIND_IN_SET(':$user_id:',$clients_table.starred_by)
        ORDER BY $clients_table.company_name ASC";
        return $this->db->query($sql);
    }

    function delete_client_and_sub_items($client_id)
    {
        $clients_table = $this->db->dbprefix('clients');
        $general_files_table = $this->db->dbprefix('general_files');
        $users_table = $this->db->dbprefix('users');


        //get client files info to delete the files from directory 
        $client_files_sql = "SELECT * FROM $general_files_table WHERE $general_files_table.deleted=0 AND $general_files_table.client_id=$client_id; ";
        $client_files = $this->db->query($client_files_sql)->result();

        //delete the client and sub items
        //delete client
        $delete_client_sql = "UPDATE $clients_table SET $clients_table.deleted=1 WHERE $clients_table.id=$client_id; ";
        $this->db->query($delete_client_sql);

        //delete contacts
        $delete_contacts_sql = "UPDATE $users_table SET $users_table.deleted=1 WHERE $users_table.client_id=$client_id; ";
        $this->db->query($delete_contacts_sql);

        //delete the project files from directory
        $file_path = get_general_file_path("client", $client_id);
        foreach ($client_files as $file) {
            delete_app_files($file_path, array(make_array_of_file($file)));
        }

        return true;
    }

    function is_duplicate_company_name($company_name, $id = 0)
    {

        $result = $this->get_all_where(array("company_name" => $company_name, "deleted" => 0));
        if ($result->num_rows() && $result->row()->id != $id) {
            return $result->row();
        } else {
            return false;
        }
    }

    function is_duplicate_phone($phone, $id = 0)
    {

        $result = $this->get_all_where(array("phone" => $phone, "deleted" => 0));
        if ($result->num_rows() && $result->row()->id != $id) {
            return $result->row();
        } else {
            return false;
        }
    }

    function get_leads_kanban_details($options = array())
    {
        $clients_table = $this->db->dbprefix('clients');
        $lead_source_table = $this->db->dbprefix('lead_source');
        $users_table = $this->db->dbprefix('users');
        $events_table = $this->db->dbprefix('events');
        $notes_table = $this->db->dbprefix('notes');
        $estimates_table = $this->db->dbprefix('estimates');
        $general_files_table = $this->db->dbprefix('general_files');
        $estimate_requests_table = $this->db->dbprefix('estimate_requests');

        $where = "";

        $status = get_array_value($options, "status");
        if ($status) {
            $where .= " AND $clients_table.lead_status_id='$status'";
        }

        $owner_id = get_array_value($options, "owner_id");
        if ($owner_id) {
            $where .= " AND $clients_table.owner_id='$owner_id'";
        }

        $source = get_array_value($options, "source");
        if ($source) {
            $where .= " AND $clients_table.lead_source_id='$source'";
        }

        $users_where = "$users_table.client_id=$clients_table.id AND $users_table.deleted=0 AND $users_table.user_type='lead'";

        $this->db->query('SET SQL_BIG_SELECTS=1');

        $sql = "SELECT $clients_table.id, $clients_table.company_name, $clients_table.sort, IF($clients_table.sort!=0, $clients_table.sort, $clients_table.id) AS new_sort, $clients_table.lead_status_id, $clients_table.owner_id,
                (SELECT $users_table.image FROM $users_table WHERE $users_where AND $users_table.is_primary_contact=1) AS primary_contact_avatar,
                (SELECT COUNT($users_table.id) FROM $users_table WHERE $users_where) AS total_contacts_count,
                (SELECT COUNT($events_table.id) FROM $events_table WHERE $events_table.deleted=0 AND $events_table.client_id=$clients_table.id) AS total_events_count,
                (SELECT COUNT($notes_table.id) FROM $notes_table WHERE $notes_table.deleted=0 AND $notes_table.client_id=$clients_table.id) AS total_notes_count,
                (SELECT COUNT($estimates_table.id) FROM $estimates_table WHERE $estimates_table.deleted=0 AND $estimates_table.client_id=$clients_table.id) AS total_estimates_count,
                (SELECT COUNT($general_files_table.id) FROM $general_files_table WHERE $general_files_table.deleted=0 AND $general_files_table.client_id=$clients_table.id) AS total_files_count,
                (SELECT COUNT($estimate_requests_table.id) FROM $estimate_requests_table WHERE $estimate_requests_table.deleted=0 AND $estimate_requests_table.client_id=$clients_table.id) AS total_estimate_requests_count,
                $lead_source_table.title AS lead_source_title,
                CONCAT($users_table.first_name, ' ', $users_table.last_name) AS owner_name
        FROM $clients_table 
        LEFT JOIN $lead_source_table ON $clients_table.lead_source_id = $lead_source_table.id 
        LEFT JOIN $users_table ON $users_table.id = $clients_table.owner_id AND $users_table.deleted=0 AND $users_table.user_type='staff' 
        WHERE $clients_table.deleted=0 AND $clients_table.is_lead=1 $where 
        ORDER BY new_sort ASC";

        return $this->db->query($sql);
    }
}
