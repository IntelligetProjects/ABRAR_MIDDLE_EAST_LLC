<?php

class Material_request_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'material_request';
        parent::__construct($this->table);
        $this->init_activity_log($this->table, $this->table);
    }

    function get_details($options = array()) {
        $material_request_table = $this->db->dbprefix('material_request');
        $suppliers_table = $this->db->dbprefix('suppliers');
        $projects_table = $this->db->dbprefix('projects');
        $material_request_payments_table = $this->db->dbprefix('material_request_payments');
        $material_request_items_table = $this->db->dbprefix('material_request_items');
        $users_table = $this->db->dbprefix('users');
        $log_table = $this->db->dbprefix('activity_logs');
        $taxes_table = $this->db->dbprefix('taxes');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $material_request_table.id=$id";
        }
        
        $supplier_id = get_array_value($options, "supplier_id");
        if ($supplier_id) {
            $where .= " AND $material_request_table.supplier_id=$supplier_id";
        }

        $exclude_draft = get_array_value($options, "exclude_draft");
        if ($exclude_draft) {
            $where .= " AND $material_request_table.status!='draft' ";
        }

        $project_id = get_array_value($options, "project_id");
        if ($project_id) {
            $where .= " AND $material_request_table.project_id=$project_id";
        }

        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($material_request_table.material_request_date BETWEEN '$start_date' AND '$end_date') ";
        }

        //$now = get_my_local_time("Y-m-d");
        //  $options['status'] = "draft";
        $status = get_array_value($options, "status");


        $material_request_value_calculation_query = $this->_get_material_request_value_calculation_query($material_request_table);


        $material_request_value_calculation = "TRUNCATE($material_request_value_calculation_query,2)";

        if ($status === "draft") {
            $where .= " AND $material_request_table.status='draft' AND IFNULL(payments_table.payment_received,0)<=0";
        } else if ($status === "not_paid") {
            $where .= " AND $material_request_table.status !='draft' AND IFNULL(payments_table.payment_received,0)<=0";
        } else if ($status === "partially_paid") {
            $where .= " AND IFNULL(payments_table.payment_received,0)>0 AND IFNULL(payments_table.payment_received,0)<$material_request_value_calculation";
        } else if ($status === "fully_paid") {
            $where .= " AND TRUNCATE(IFNULL(payments_table.payment_received,0),2)>=$material_request_value_calculation";
        }



        $currency = get_array_value($options, "currency");
        if ($currency) {
            $where .= $this->_get_suppliers_of_currency_query($currency, $material_request_table, $suppliers_table);
        }

        /////

        $allowed_members = get_array_value($options, "allowed_members");
        if (is_array($allowed_members) && count($allowed_members)) {
            $allowed_members = join(",", $allowed_members);
            $where .= " AND log.user_id IN($allowed_members)";
        }

        /////
        $import_or_doemstic =  get_array_value($options, "type");
        if ($import_or_doemstic) {
            $where .= " AND $material_request_table.type='$import_or_doemstic'";
        }


        //TODO: remove IF-ELSE , remove the code inside else if changes to database done 
        if ($this->login_user->is_admin && ($this->db->dbprefix === 'Test_teamway' || $this->db->dbprefix === 'Tarteeb' )){
            $sql = "SELECT $material_request_table.*, $suppliers_table.currency, $suppliers_table.vat_number AS supplier_vat_number, $suppliers_table.currency_symbol, $suppliers_table.company_name, $projects_table.title AS project_title,
            $material_request_value_calculation_query AS material_request_value, items_table.tax as tax_value, IFNULL(payments_table.payment_received,0) AS payment_received, log.user_id AS log_user_id
         FROM $material_request_table
         LEFT JOIN $suppliers_table ON $suppliers_table.id= $material_request_table.supplier_id
         LEFT JOIN (SELECT a.*, CONCAT_WS(' ',b.first_name,b.last_name) as create_user, b.id as user_id  
                    FROM $log_table a
                    LEFT JOIN $users_table b on (a.created_by = b.id)
                    WHERE log_type = 'material_request' and action = 'created') as log ON ($material_request_table.id = log.log_type_id)
         LEFT JOIN $projects_table ON $projects_table.id= $material_request_table.project_id
         LEFT JOIN (SELECT material_request_id, SUM(amount) AS payment_received FROM $material_request_payments_table WHERE deleted=0 GROUP BY material_request_id) AS payments_table ON payments_table.material_request_id = $material_request_table.id 
         LEFT JOIN (SELECT material_request_id, SUM(total) AS material_request_value, SUM($material_request_items_table.total*tax_table.percentage*0.01) AS tax FROM $material_request_items_table
         LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $material_request_items_table.tax_id
         WHERE $material_request_items_table.deleted=0 GROUP BY material_request_id) AS items_table ON items_table.material_request_id = $material_request_table.id 
         WHERE $material_request_table.deleted=0 $where";
         return $this->db->query($sql);
        }else{
            ///for other users , remove if changes done to database
            $sql = "SELECT $material_request_table.*, $suppliers_table.currency, $suppliers_table.currency_symbol, $suppliers_table.company_name, $projects_table.title AS project_title,
            $material_request_value_calculation_query AS material_request_value, items_table.tax as tax_value, IFNULL(payments_table.payment_received,0) AS payment_received, log.user_id AS log_user_id
         FROM $material_request_table
         LEFT JOIN $suppliers_table ON $suppliers_table.id= $material_request_table.supplier_id
         LEFT JOIN (SELECT a.*, CONCAT_WS(' ',b.first_name,b.last_name) as create_user, b.id as user_id  
                    FROM $log_table a
                    LEFT JOIN $users_table b on (a.created_by = b.id)
                    WHERE log_type = 'material_request' and action = 'created') as log ON ($material_request_table.id = log.log_type_id)
         LEFT JOIN $projects_table ON $projects_table.id= $material_request_table.project_id
         LEFT JOIN (SELECT material_request_id, SUM(amount) AS payment_received FROM $material_request_payments_table WHERE deleted=0 GROUP BY material_request_id) AS payments_table ON payments_table.material_request_id = $material_request_table.id 
         LEFT JOIN (SELECT material_request_id, SUM(total) AS material_request_value, SUM($material_request_items_table.total*tax_table.percentage*0.01) AS tax FROM $material_request_items_table
         LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $material_request_items_table.tax_id
         WHERE $material_request_items_table.deleted=0 GROUP BY material_request_id) AS items_table ON items_table.material_request_id = $material_request_table.id 
         WHERE $material_request_table.deleted=0 $where";
         return $this->db->query($sql);
        }
       
    }


    function get_details_for_vat_report($options = array()) {
        $material_request_table = $this->db->dbprefix('material_request');
        $suppliers_table = $this->db->dbprefix('suppliers');
        $projects_table = $this->db->dbprefix('projects');
        $material_request_payments_table = $this->db->dbprefix('material_request_payments');
        $material_request_items_table = $this->db->dbprefix('material_request_items');
        $users_table = $this->db->dbprefix('users');
        $log_table = $this->db->dbprefix('activity_logs');
        $taxes_table = $this->db->dbprefix('taxes');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $material_request_table.id=$id";
        }
        $supplier_id = get_array_value($options, "supplier_id");
        if ($supplier_id) {
            $where .= " AND $material_request_table.supplier_id=$supplier_id";
        }

        $exclude_draft = get_array_value($options, "exclude_draft");
        if ($exclude_draft) {
            $where .= " AND $material_request_table.status!='draft' ";
        }

        $project_id = get_array_value($options, "project_id");
        if ($project_id) {
            $where .= " AND $material_request_table.project_id=$project_id";
        }

        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($material_request_table.id IN (SELECT material_request_id FROM $material_request_payments_table WHERE $material_request_payments_table.payment_date  BETWEEN '$start_date' AND '$end_date' AND  $material_request_payments_table.deleted=0 AND $material_request_payments_table.status='approved')) ";
        }

        //$now = get_my_local_time("Y-m-d");
        //  $options['status'] = "draft";
        $status = get_array_value($options, "status");


        $material_request_value_calculation_query = $this->_get_material_request_value_calculation_query($material_request_table);


        $material_request_value_calculation = "TRUNCATE($material_request_value_calculation_query,2)";

        if ($status === "draft") {
            $where .= " AND $material_request_table.status='draft' AND IFNULL(payments_table.payment_received,0)<=0";
        } else if ($status === "not_paid") {
            $where .= " AND $material_request_table.status !='draft' AND IFNULL(payments_table.payment_received,0)<=0";
        } else if ($status === "partially_paid") {
            $where .= " AND IFNULL(payments_table.payment_received,0)>0 AND IFNULL(payments_table.payment_received,0)<$material_request_value_calculation";
        } else if ($status === "fully_paid") {
            $where .= " AND TRUNCATE(IFNULL(payments_table.payment_received,0),2)>=$material_request_value_calculation";
        }



        $currency = get_array_value($options, "currency");
        if ($currency) {
            $where .= $this->_get_suppliers_of_currency_query($currency, $material_request_table, $suppliers_table);
        }

        /////

        $allowed_members = get_array_value($options, "allowed_members");
        if (is_array($allowed_members) && count($allowed_members)) {
            $allowed_members = join(",", $allowed_members);
            $where .= " AND log.user_id IN($allowed_members)";
        }

        /////
        $import_or_doemstic =  get_array_value($options, "type");
        if ($import_or_doemstic) {
            $where .= " AND $material_request_table.type='$import_or_doemstic'";
        }


        //TODO: remove IF-ELSE , remove the code inside else if changes to database done 
            $sql = "SELECT $material_request_table.*, $suppliers_table.currency, $suppliers_table.vat_number AS supplier_vat_number, $suppliers_table.currency_symbol, $suppliers_table.company_name, $projects_table.title AS project_title,
            $material_request_value_calculation_query AS material_request_value, items_table.tax as tax_value, IFNULL(payments_table.payment_received,0) AS payment_received, log.user_id AS log_user_id
         FROM $material_request_table
         LEFT JOIN $suppliers_table ON $suppliers_table.id= $material_request_table.supplier_id
         LEFT JOIN (SELECT a.*, CONCAT_WS(' ',b.first_name,b.last_name) as create_user, b.id as user_id  
                    FROM $log_table a
                    LEFT JOIN $users_table b on (a.created_by = b.id)
                    WHERE log_type = 'material_request' and action = 'created') as log ON ($material_request_table.id = log.log_type_id)
         LEFT JOIN $projects_table ON $projects_table.id= $material_request_table.project_id
         LEFT JOIN (SELECT material_request_id, SUM(amount) AS payment_received FROM $material_request_payments_table WHERE deleted=0 GROUP BY material_request_id) AS payments_table ON payments_table.material_request_id = $material_request_table.id 
         LEFT JOIN (SELECT material_request_id, SUM(total) AS material_request_value, SUM($material_request_items_table.total*tax_table.percentage*0.01) AS tax FROM $material_request_items_table
         LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $material_request_items_table.tax_id
         WHERE $material_request_items_table.deleted=0 GROUP BY material_request_id) AS items_table ON items_table.material_request_id = $material_request_table.id 
         WHERE $material_request_table.deleted=0 $where";
         return $this->db->query($sql);
       
    }

    function get_material_request_total_summary($material_request_id = 0) {
        $material_request_items_table = $this->db->dbprefix('material_request_items');
        $material_request_payments_table = $this->db->dbprefix('material_request_payments');
        $material_request_table = $this->db->dbprefix('material_request');
        $suppliers_table = $this->db->dbprefix('suppliers');
        $taxes_table = $this->db->dbprefix('taxes');

        $item_sql = "SELECT SUM($material_request_items_table.total) AS material_request_subtotal, SUM($material_request_items_table.total*tax_table.percentage*0.01) AS tax, SUM($material_request_items_table.total*tax_table2.percentage*0.01) AS tax2
        FROM $material_request_items_table
        LEFT JOIN $material_request_table ON $material_request_table.id= $material_request_items_table.material_request_id 
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $material_request_items_table.tax_id
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table2 ON tax_table2.id = $material_request_items_table.tax_id2    
        WHERE $material_request_items_table.deleted=0 AND $material_request_items_table.material_request_id=$material_request_id AND $material_request_table.deleted=0";
        $item = $this->db->query($item_sql)->row();

        $payment_sql = "SELECT SUM($material_request_payments_table.amount) AS total_paid
        FROM $material_request_payments_table
        WHERE $material_request_payments_table.deleted=0 AND $material_request_payments_table.material_request_id=$material_request_id AND $material_request_payments_table.status='approved'";
        $payment = $this->db->query($payment_sql)->row();

        $material_request_sql = "SELECT $material_request_table.*
        FROM $material_request_table
        WHERE $material_request_table.deleted=0 AND $material_request_table.id=$material_request_id";
        $material_request = $this->db->query($material_request_sql)->row();

        $supplier_sql = "SELECT $suppliers_table.currency_symbol, $suppliers_table.currency FROM $suppliers_table WHERE $suppliers_table.id=$material_request->supplier_id";
        $supplier = $this->db->query($supplier_sql)->row();


        $result = new stdClass();
        $result->material_request_subtotal = $item->material_request_subtotal;
        $result->tax_name = /*$estimate->tax_name*/lang("tax");
        $result->tax_name2 = /*$estimate->tax_name2*/lang("tax2");

        $material_request_subtotal = $result->material_request_subtotal;
        
        $result->material_request_total = $item->material_request_subtotal;

        $result->tax = $item->tax;

        $result->tax2 = $item->tax2;

        $result->material_request_total = $item->material_request_subtotal + $result->tax + $result->tax2;

        $result->total_paid = $payment->total_paid;

        $result->currency_symbol = $supplier->currency_symbol ? $supplier->currency_symbol : get_setting("currency_symbol");
        $result->currency = $supplier->currency ? $supplier->currency : get_setting("default_currency");

        $result->balance_due = number_format($result->material_request_total, 2, ".", "") - number_format($payment->total_paid, 2, ".", "");

        return $result;
    }

    function get_used_currencies_of_supplier() {
        $suppliers_table = $this->db->dbprefix('suppliers');
        $default_currency = get_setting("default_currency");

        $sql = "SELECT $suppliers_table.currency
            FROM $suppliers_table
            WHERE $suppliers_table.deleted=0 AND $suppliers_table.currency!='' AND $suppliers_table.currency!='$default_currency'
            GROUP BY $suppliers_table.currency";

        return $this->db->query($sql);
    }

    function get_material_request_total_and_paymnts() {
        $material_request_table = $this->db->dbprefix('material_request');
        $material_request_payments_table = $this->db->dbprefix('material_request_payments');
        $material_request_items_table = $this->db->dbprefix('material_request_items');
        $taxes_table = $this->db->dbprefix('taxes');
        $info = new stdClass();


        $payments = "SELECT SUM($material_request_payments_table.amount) AS total
            FROM $material_request_payments_table
            LEFT JOIN $material_request_table ON $material_request_table.id=$material_request_payments_table.material_request_id    
            WHERE $material_request_payments_table.deleted=0 AND $material_request_table.deleted=0";
        $info->payments = $this->db->query($payments)->result();

        $material_request_value_calculation_query = $this->_get_material_request_value_calculation_query($material_request_table);

        $material_request = "SELECT SUM(total) AS total FROM (SELECT $material_request_value_calculation_query AS total
            FROM $material_request_table
            LEFT JOIN (SELECT material_request_id, SUM(total + 0.01*IFNULL(tax_table.percentage,0)*total + 0.01*IFNULL(tax_table2.percentage,0)*total)  AS material_request_value FROM $material_request_items_table
            LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $material_request_items_table.tax_id
            LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table2 ON tax_table2.id = $material_request_items_table.tax_id2 WHERE deleted=0 GROUP BY material_request_id) AS items_table ON items_table.material_request_id = $material_request_table.id 
            WHERE $material_request_table.deleted=0 AND $material_request_table.status='not_paid') as details_table";

        $payments_total = $this->db->query($payments)->row()->total;
        $material_request_total = $this->db->query($material_request)->row()->total;

        $info->payments_total = $payments_total;
        $info->material_request_total = (($material_request_total > $payments_total) && ($material_request_total - $payments_total) < 0.05 ) ? $payments_total : $material_request_total;
        $info->due = $info->material_request_total - $info->payments_total;
        return $info;
    }

    //update material_request status
    function update_material_request_status($material_request_id = 0, $status = "not_paid") {
        $status_data = array("status" => $status);
        return $this->save($status_data, $material_request_id);
    }

    //update material_request status
    function update_approval_status($material_request_id = 0, $status = "not_approved") {
        $status_data = array("approval_status" => $status);
        return $this->save($status_data, $material_request_id);
    }

    //get material_request dropdown list
    function get_material_request_dropdown_list() {
        $material_request_table = $this->db->dbprefix('material_request');

        $sql = "SELECT $material_request_table.id FROM $material_request_table
                        WHERE $material_request_table.deleted=0 
                        ORDER BY $material_request_table.id DESC";

        return $this->db->query($sql);
    }


    //get label suggestions
    function get_label_suggestions() {
        $material_request_table = $this->db->dbprefix('material_request');
        $sql = "SELECT GROUP_CONCAT(labels) as label_groups
        FROM $material_request_table
        WHERE $material_request_table.deleted=0";
        return $this->db->query($sql)->row()->label_groups;
    }
    
    //get material_request last id
    function get_last_material_request_id() {
        $material_request_table = $this->db->dbprefix('material_request');

        $sql = "SELECT MAX($material_request_table.id) AS last_id FROM $material_request_table";

        return $this->db->query($sql)->row()->last_id;
    }
    function get_purchase_order_total_summary($material_request_id = 0) {
        $material_request_items_table = $this->db->dbprefix('material_request_items');
        $material_request_payments_table = $this->db->dbprefix('material_request_payments');
        $material_request_table = $this->db->dbprefix('material_request');
        $suppliers_table = $this->db->dbprefix('suppliers');
        $taxes_table = $this->db->dbprefix('taxes');

        $item_sql = "SELECT SUM($material_request_items_table.total) AS purchase_order_subtotal, SUM($material_request_items_table.total*tax_table.percentage*0.01) AS tax, SUM($material_request_items_table.total*tax_table2.percentage*0.01) AS tax2
        FROM $material_request_items_table
        LEFT JOIN $material_request_table ON $material_request_table.id= $material_request_items_table.material_request_id 
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $material_request_items_table.tax_id
        LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table2 ON tax_table2.id = $material_request_items_table.tax_id2    
        WHERE $material_request_items_table.deleted=0 AND $material_request_items_table.material_request_id=$material_request_id AND $material_request_table.deleted=0";
        $item = $this->db->query($item_sql)->row();

        $payment_sql = "SELECT SUM($material_request_payments_table.amount) AS total_paid
        FROM $material_request_payments_table
        WHERE $material_request_payments_table.deleted=0 AND $material_request_payments_table.material_request_id=$material_request_id AND $material_request_payments_table.status='approved'";
        $payment = $this->db->query($payment_sql)->row();

        $purchase_order_sql = "SELECT $material_request_table.*
        FROM $material_request_table
        WHERE $material_request_table.deleted=0 AND $material_request_table.id=$material_request_id";
        $purchase_order = $this->db->query($purchase_order_sql)->row();

        $supplier_sql = "SELECT $suppliers_table.currency_symbol, $suppliers_table.currency FROM $suppliers_table WHERE $suppliers_table.id=$purchase_order->supplier_id";
        $supplier = $this->db->query($supplier_sql)->row();


        $result = new stdClass();
        $result->purchase_order_subtotal = $item->purchase_order_subtotal;
        $result->tax_name = /*$estimate->tax_name*/lang("tax");
        $result->tax_name2 = /*$estimate->tax_name2*/lang("tax2");

        $purchase_order_subtotal = $result->purchase_order_subtotal;
        
        $result->purchase_order_total = $item->purchase_order_subtotal;

        $result->tax = $item->tax;

        $result->tax2 = $item->tax2;

        $result->purchase_order_total = $item->purchase_order_subtotal + $result->tax + $result->tax2;

        $result->total_paid = $payment->total_paid;

        $result->currency_symbol = $supplier->currency_symbol ? $supplier->currency_symbol : get_setting("currency_symbol");
        $result->currency = $supplier->currency ? $supplier->currency : get_setting("default_currency");

        $result->balance_due = number_format($result->purchase_order_total, 2, ".", "") - number_format($payment->total_paid, 2, ".", "");

        return $result;
    }

}
