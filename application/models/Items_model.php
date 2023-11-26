<?php

class Items_model extends Crud_model
{

    private $table = null;

    function __construct()
    {
        $this->table = 'items';
        parent::__construct($this->table, true);
        $this->init_activity_log($this->table, $this->table);
    }

    function get_details($options = array())
    {
        $items_table = $this->db->dbprefix('items');
        $item_categories_table = $this->db->dbprefix('item_categories');
        $purchased_orders_table = $this->db->dbprefix("purchase_orders");
        $purchased_order_items_table = $this->db->dbprefix("purchase_order_items");
        $invoices_table = $this->db->dbprefix("invoices");
        $invoice_items_table = $this->db->dbprefix("invoice_items");
        $delivery_notes_table = $this->db->dbprefix('delivery_notes');
        $delivery_note_items_table = $this->db->dbprefix('delivery_note_items');
        $shipments_table = $this->db->dbprefix('shipments');
        $shipment_items_table = $this->db->dbprefix('shipment_items');
        $log_table = $this->db->dbprefix('activity_logs');
        $user_table = $this->db->dbprefix('users');
        $stock_adjustments_table = $this->db->dbprefix("stock_adjustments");
        $sale_return_items_table = $this->db->dbprefix('sale_return_items');
        $sale_returns_table = $this->db->dbprefix('sale_returns');
        $purchase_return_items_table = $this->db->dbprefix('purchase_return_items');
        $purchase_returns_table = $this->db->dbprefix('purchase_returns');
        
        $currencies_table = $this->db->dbprefix('currencies');
        $cost_centers_table = $this->db->dbprefix('cost_centers');


        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $items_table.id=$id";
        }

        $category_id = get_array_value($options, "category_id");
        if ($category_id) {
            $where .= " AND $items_table.category_id=$category_id";
        }

        $item_type = get_array_value($options, "item_type");
        if ($item_type) {
            $where .= " AND $items_table.item_type='$item_type'";
        }

        $allowed_members = get_array_value($options, "allowed_members");
        if (is_array($allowed_members) && count($allowed_members)) {
            $allowed_members = join(",", $allowed_members);
            $where .= " AND log.user_id IN($allowed_members)";
        }

        //add filter by cost center id
        if (!can_view_all_cost_centers_data() && $this->login_user->cost_center_id > 0) {
            $cost_center_id = $this->login_user->cost_center_id;
            $where .= " AND $items_table.cost_center_id = $cost_center_id";
        }

        //if user can view all data get the current cost center currency rate
        $join_currency_query = "";
        $select_currency_rate = "";
        if(can_view_all_cost_centers_data()){
            $select_currency_rate = ",$currencies_table.rate AS currency_rate";
            $join_currency_query = "
            LEFT JOIN $cost_centers_table AS cs ON $items_table.cost_center_id = cs.id
            LEFT JOIN $currencies_table ON cs.currency_id = $currencies_table.id
            ";
        }

        $sql = "SELECT $items_table.*, $item_categories_table.title AS category_title, purchased_qty, invoiced_qty, delivered_qty,shipment_qty, sale_return_qty, purchase_return_qty, log.user_id as log_user_id, ad_qty,
        log.created_at as create_time, log.create_user as create_user
        $select_currency_rate
        FROM $items_table
        LEFT JOIN $item_categories_table ON $items_table.category_id = $item_categories_table.id
        $join_currency_query

        LEFT JOIN (SELECT item_id, sum(quantity) as purchased_qty 
                   FROM $purchased_order_items_table as detail
                   LEFT JOIN $purchased_orders_table as main
                   ON detail.purchase_order_id = main.id
                   WHERE  main.approval_status = 'approved' AND main.deleted = 0 AND detail.deleted = 0
                   GROUP BY item_id) as purchased
            ON purchased.item_id = $items_table.id

        LEFT JOIN (SELECT item_id, sum(quantity) as invoiced_qty 
                   FROM $invoice_items_table as detail
                   LEFT JOIN $invoices_table as main                   
                   ON detail.invoice_id = main.id
                   WHERE main.approval_status = 'approved' AND main.deleted = 0 AND detail.deleted = 0
                   GROUP BY item_id) as invoiced
            ON invoiced.item_id = $items_table.id

        LEFT JOIN (SELECT item_id, sum(quantity) as delivered_qty 
                   FROM $delivery_note_items_table as detail
                   LEFT JOIN $delivery_notes_table as main
                   ON detail.delivery_note_id = main.id
                   WHERE main.deleted = 0 and main.status = 'approved' AND detail.deleted = 0
                   GROUP BY item_id) as delivered
            ON delivered.item_id = $items_table.id

        LEFT JOIN (SELECT item_id, sum(quantity) as shipment_qty 
                   FROM $shipment_items_table as detail
                   LEFT JOIN $shipments_table as main
                   ON detail.shipment_id = main.id
                   WHERE main.deleted = 0 and main.status = 'approved' AND detail.deleted = 0
                   GROUP BY item_id) as shipment
            ON shipment.item_id = $items_table.id

        LEFT JOIN (SELECT item_id, sum(quantity) as sale_return_qty 
                   FROM $sale_return_items_table as detail
                   LEFT JOIN $sale_returns_table as main
                   ON detail.sale_return_id = main.id
                   WHERE main.deleted = 0 and main.status = 'approved' AND detail.deleted = 0
                   GROUP BY item_id) as sale_returned
            ON sale_returned.item_id = $items_table.id

        LEFT JOIN (SELECT item_id, sum(quantity) as purchase_return_qty 
                   FROM $purchase_return_items_table as detail
                   LEFT JOIN $purchase_returns_table as main
                   ON detail.purchase_return_id = main.id
                   WHERE main.deleted = 0 and main.status = 'approved' AND detail.deleted = 0
                   GROUP BY item_id) as purchase_returned
            ON purchase_returned.item_id = $items_table.id

        LEFT JOIN (SELECT item_id, sum(quantity) as ad_qty 
                   FROM $stock_adjustments_table as detail
                   GROUP BY item_id) as adjusted
            ON adjusted.item_id = $items_table.id

        LEFT JOIN (SELECT a.*, CONCAT_WS(' ',b.first_name,b.last_name) as create_user, b.id as user_id  
                   FROM $log_table a
                   LEFT JOIN $user_table b on (a.created_by = b.id)
                   WHERE log_type = 'items' and action = 'created') as log ON ($items_table.id = log.log_type_id)

        WHERE $items_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_one_with_currency_data($id = 0)
    {
        $items_table = $this->db->dbprefix('items');

        $currencies_table = $this->db->dbprefix('currencies');
        $cost_centers_table = $this->db->dbprefix('cost_centers');

        if(!$id){
            return $this->get_empty_table_object();
        }

        $sql = "SELECT $items_table.*, $currencies_table.symbol AS currency_symbol 
                FROM $items_table
                LEFT JOIN $cost_centers_table AS cs ON $items_table.cost_center_id = cs.id
                LEFT JOIN $currencies_table ON cs.currency_id = $currencies_table.id
                WHERE $items_table.deleted = 0 AND $items_table.id = $id
        ";

        $result =  $this->db->query($sql);

        if ($result->num_rows()) {
            return $result->row();
        } else {
            return $this->get_empty_table_object();
        }
    }

    private function get_empty_table_object(){
        $db_fields = $this->db->list_fields($this->table);
            $fields = new stdClass();
            foreach ($db_fields as $field) {
                $fields->$field = "";
            }
            return $fields;
    }
}
