<?php

class Sale_returns_model extends Crud_model
{

    private $table = null;

    function __construct()
    {
        $this->table = 'sale_returns';
        parent::__construct($this->table);
        $this->init_activity_log($this->table, $this->table);
    }

    function get_details($options = array())
    {
        $sale_returns_table = $this->db->dbprefix('sale_returns');
        $clients_table = $this->db->dbprefix('clients');
        $sale_returns_table = $this->db->dbprefix('sale_returns');
        $users_table = $this->db->dbprefix('users');
        $log_table = $this->db->dbprefix('activity_logs');
        $invoices_table = $this->db->dbprefix('invoices');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $sale_returns_table.id=$id";
        }
        $client_id = get_array_value($options, "client_id");
        if ($client_id) {
            $where .= " AND $sale_returns_table.client_id=$client_id";
        }

        $exclude_draft = get_array_value($options, "exclude_draft");
        if ($exclude_draft) {
            $where .= " AND $sale_returns_table.status!='draft' ";
        }


        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($sale_returns_table.date BETWEEN '$start_date' AND '$end_date') ";
        }

        $allowed_members = get_array_value($options, "allowed_members");
        if (is_array($allowed_members) && count($allowed_members)) {
            $allowed_members = join(",", $allowed_members);
            $where .= " AND log.user_id IN($allowed_members)";
        }

        //add filter by cost center id
        if ($this->login_user->cost_center_id > 0) {
            $cost_center_id = $this->login_user->cost_center_id;
            $where .= " AND $invoices_table.cost_center_id = $cost_center_id";
        }

        $now = get_my_local_time("Y-m-d");
        $status = get_array_value($options, "status");


        $sql = "SELECT $sale_returns_table.*, $clients_table.company_name
        FROM $sale_returns_table
        LEFT JOIN $clients_table ON $clients_table.id= $sale_returns_table.client_id
        LEFT JOIN $invoices_table ON $invoices_table.id= $sale_returns_table.invoice_id
 
        WHERE $sale_returns_table.deleted=0 $where";

        // $sql = "SELECT $sale_returns_table.*, $clients_table.company_name, log.user_id as log_user_id,
        //    log.created_at as create_time, log.create_user as create_user
        // FROM $sale_returns_table
        // LEFT JOIN $clients_table ON $clients_table.id= $sale_returns_table.client_id
        // LEFT JOIN (SELECT a.*, CONCAT_WS(' ',b.first_name,b.last_name) as create_user, b.id as user_id  
        //            FROM $log_table a
        //            LEFT JOIN $users_table b on (a.created_by = b.id)
        //            WHERE log_type = 'sale_returns' and action = 'created') as log ON ($sale_returns_table.id = log.log_type_id)
        // WHERE $sale_returns_table.deleted=0 $where";


        // var_dump( $this->db->query($sql));
        return $this->db->query($sql);
    }

    //update e status
    function update_approval_status($id = 0, $status = "not_approved")
    {
        $status_data = array("status" => $status);
        return $this->save($status_data, $id);
    }

    function get_total_summary($purchase_order_id = 0)
    {
        $purchase_return_items_table = $this->db->dbprefix('sale_return_items');
        $purchase_returns_table = $this->db->dbprefix('sale_returns');
        $purchase_order_items_table = $this->db->dbprefix('invoice_items');
        $purchase_order_payments_table = $this->db->dbprefix('invoice_payments');
        $purchase_orders_table = $this->db->dbprefix('invoices');
        $suppliers_table = $this->db->dbprefix('clients');
        $taxes_table = $this->db->dbprefix('taxes');

        $item_sql = "SELECT SUM($purchase_order_items_table.rate*$purchase_return_items_table.quantity) AS purchase_order_subtotal,
         SUM(($purchase_order_items_table.rate*$purchase_return_items_table.quantity-IF($purchase_order_items_table.discount_amount_type='percentage',$purchase_order_items_table.rate*$purchase_return_items_table.quantity*$purchase_order_items_table.discount_amount/100,$purchase_order_items_table.discount_amount))*tax_table.percentage*0.01) AS tax,
          SUM($purchase_order_items_table.rate*$purchase_return_items_table.quantity*tax_table2.percentage*0.01) AS tax2
        , SUM(IF($purchase_order_items_table.discount_amount_type='percentage',$purchase_order_items_table.total*$purchase_order_items_table.discount_amount/100,$purchase_order_items_table.discount_amount)) AS item_discount 
        FROM $purchase_return_items_table

        LEFT JOIN $purchase_returns_table ON $purchase_returns_table.id=$purchase_return_items_table.sale_return_id
        LEFT JOIN $purchase_order_items_table ON $purchase_order_items_table.id=$purchase_return_items_table.invoice_item_id

        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $purchase_order_items_table.tax_id
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table2 ON tax_table2.id = $purchase_order_items_table.tax_id2

        WHERE $purchase_return_items_table.deleted=0 AND $purchase_return_items_table.sale_return_id=$purchase_order_id AND $purchase_returns_table.deleted=0";
        $item = $this->db->query($item_sql)->row();

        $invoice_id = $this->Sale_returns_model->get_one($purchase_order_id)->invoice_id;

        $invoice_summary = $this->Invoices_model->get_invoice_total_summary($invoice_id);

        $discount = $invoice_summary->discount_total;
        $total_invoice = $invoice_summary->invoice_subtotal;

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

        $result->total = number_format($result->purchase_order_total - $result->discount, 3, ".", "");

        return $result;
    }
}
