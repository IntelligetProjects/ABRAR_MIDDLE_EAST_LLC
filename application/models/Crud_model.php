<?php

//extend from this model to execute basic db operations
class Crud_model extends CI_Model
{

    private $table;
    private $log_activity = false;
    private $log_type = "";
    private $log_for = "";
    private $log_for_key = "";
    private $log_for2 = "";
    private $log_for_key2 = "";
    private $has_cost_center = false;
    private $changable_currency_rate = false;

    function __construct($table = null, $has_cost_center = false, $changable_currency_rate = false)
    {
        $this->use_table($table, $has_cost_center, $changable_currency_rate);
        // $this->has_cost_center = $has_cost_center;

        $this->db->query("SET sql_mode = ''");
    }

    protected function use_table($table, $has_cost_center = false, $changable_currency_rate = false)
    {
        $this->table = $table;
        $this->has_cost_center = $has_cost_center;
        $this->changable_currency_rate = $changable_currency_rate;
    }

    protected function init_activity_log($log_type = "", $log_type_title_key = "", $log_for = "", $log_for_key = 0, $log_for2 = "", $log_for_key2 = 0)
    {
        if ($log_type) {
            $this->log_activity = true;
            $this->log_type = $log_type;
            $this->log_type_title_key = $log_type_title_key;
            $this->log_for = $log_for;
            $this->log_for_key = $log_for_key;
            $this->log_for2 = $log_for2;
            $this->log_for_key2 = $log_for_key2;
        }
    }

    function get_one($id = 0)
    {
        return $this->get_one_where(array('id' => $id));
    }

    function get_one_where($where = array())
    {
        $result = $this->db->get_where($this->table, $where, 1);
        if ($result->num_rows()) {
            return $result->row();
        } else {
            $db_fields = $this->db->list_fields($this->table);
            $fields = new stdClass();
            foreach ($db_fields as $field) {
                $fields->$field = "";
            }
            return $fields;
        }
    }

    function get_all($include_deleted = false)
    {
        $where = array("deleted" => 0);
        if ($include_deleted) {
            $where = array();
        }
        return $this->get_all_where($where);
    }

    function get_all_where($where = array(), $limit = 1000000, $offset = 0, $sort_by_field = null)
    {
        $where_in = get_array_value($where, "where_in");
        if ($where_in) {
            foreach ($where_in as $key => $value) {
                $this->db->where_in($key, $value);
            }
            unset($where["where_in"]);
        }

        if ($sort_by_field) {
            $this->db->order_by($sort_by_field, 'ASC');
        }

        if (!can_view_all_cost_centers_data() && $this->has_cost_center) {
            $where["cost_center_id"] = $this->login_user->cost_center_id;
        }

        return $this->db->get_where($this->table, $where, $limit, $offset);
    }

    function save(&$data = array(), $id = 0)
    {
        //unset custom created by field if it's defined for activity log
        $activity_log_created_by_app = false;
        if (get_array_value($data, "activity_log_created_by_app")) {
            $activity_log_created_by_app = true;
            unset($data["activity_log_created_by_app"]);
        }

        //add cost center id if the table has it
        if ($this->has_cost_center && !$id) {
            $data["cost_center_id"] = $this->login_user->cost_center_id;

            if ($this->changable_currency_rate) {
                $cur_rate  = $this->Cost_centers_model->get_currency_rate($this->login_user->cost_center_id);
                if (!$cur_rate) {
                    throw new RuntimeException("Cannot find currency rate for cost center ID = " . $this->login_user->cost_center_id);
                }
                $data["currency_rate_at_creation"] = $cur_rate->rate;
            }
        }

        if ($id) {
            //update
            $where = array("id" => $id);

            //to log an activity we have to know the changes. now collect the data before update anything
            if ($this->log_activity) {
                $data_before_update = $this->get_one($id);
            }

            $success = $this->update_where($data, $where);
            if ($success) {
                if ($this->log_activity) {
                    //to log this activity, check the changes
                    $fields_changed = array();
                    foreach ($data as $field => $value) {
                        if ($data_before_update->$field != $value) {
                            $fields_changed[$field] = array("from" => $data_before_update->$field, "to" => $value);
                        }
                    }
                    //has changes? log the changes.
                    if (count($fields_changed)) {
                        $log_for_id = 0;
                        if ($this->log_for_key) {
                            $log_for_key = $this->log_for_key;
                            $log_for_id = $data_before_update->$log_for_key;
                        }

                        $log_for_id2 = 0;
                        if ($this->log_for_key2) {
                            $log_for_key2 = $this->log_for_key2;
                            $log_for_id2 = $data_before_update->$log_for_key2;
                        }

                        $log_type_title_key = $this->log_type_title_key;
                        $log_type_title = isset($data_before_update->$log_type_title_key) ? $data_before_update->$log_type_title_key : "";

                        $log_data = array(
                            "action" => "updated",
                            "log_type" => $this->log_type,
                            "log_type_title" => $log_type_title,
                            "log_type_id" => $id,
                            "changes" => serialize($fields_changed),
                            "log_for" => $this->log_for,
                            "log_for_id" => $log_for_id,
                            "log_for2" => $this->log_for2,
                            "log_for_id2" => $log_for_id2,
                        );
                        $this->Activity_logs_model->save($log_data, $activity_log_created_by_app);
                        $activity_log_id = $this->db->insert_id();
                        $data["activity_log_id"] = $activity_log_id;
                    }
                }
            }
            return $success;
        } else {
            //insert
            if ($this->db->insert($this->table, $data)) {
                $insert_id = $this->db->insert_id();
                if ($this->log_activity) {
                    //log this activity
                    $log_for_id = 0;
                    if ($this->log_for_key) {
                        $log_for_id = get_array_value($data, $this->log_for_key);
                    }

                    $log_for_id2 = 0;
                    if ($this->log_for_key2) {
                        $log_for_id2 = get_array_value($data, $this->log_for_key2);
                    }

                    $log_type_title = get_array_value($data, $this->log_type_title_key);
                    $log_data = array(
                        "action" => "created",
                        "log_type" => $this->log_type,
                        "log_type_title" => $log_type_title ? $log_type_title : "",
                        "log_type_id" => $insert_id,
                        "log_for" => $this->log_for,
                        "log_for_id" => $log_for_id,
                        "log_for2" => $this->log_for2,
                        "log_for_id2" => $log_for_id2,
                    );
                    $this->Activity_logs_model->save($log_data, $activity_log_created_by_app);
                    $activity_log_id = $this->db->insert_id();
                    $data["activity_log_id"] = $activity_log_id;
                }
                return $insert_id;
            }
        }
    }

    function update_where($data = array(), $where = array())
    {
        if (count($where)) {
            if ($this->db->update($this->table, $data, $where)) {
                $id = get_array_value($where, "id");
                if ($id) {
                    return $id;
                } else {
                    return true;
                }
            }
        }
    }

    function delete($id = 0, $undo = false)
    {
        $data = array('deleted' => 1);
        if ($undo === true) {
            $data = array('deleted' => 0);
        }
        $this->db->where("id", $id);
        $success = $this->db->update($this->table, $data);
        if ($success) {
            if ($this->log_activity) {
                if ($undo) {
                    // remove previous deleted log.
                    $this->Activity_logs_model->delete_where(array("action" => "deleted", "log_type" => $this->log_type, "log_type_id" => $id));
                } else {
                    //to log this activity check the title
                    $model_info = $this->get_one($id);
                    $log_for_id = 0;
                    if ($this->log_for_key) {
                        $log_for_key = $this->log_for_key;
                        $log_for_id = $model_info->$log_for_key;
                    }
                    $log_type_title_key = $this->log_type_title_key;
                    //$log_type_title = $model_info->$log_type_title_key;

                    $log_type_title = isset($model_info->$log_type_title_key) ? $model_info->$log_type_title_key : "";

                    $log_data = array(
                        "action" => "deleted",
                        "log_type" => $this->log_type,
                        "log_type_title" => $log_type_title ? $log_type_title : "",
                        "log_type_id" => $id,
                        "log_for" => $this->log_for,
                        "log_for_id" => $log_for_id,
                    );
                    $this->Activity_logs_model->save($log_data);
                }
            }
        }
        return $success;
    }

    function get_dropdown_list($option_fields = array(), $key = "id", $where = array())
    {
        $where["deleted"] = 0;
        $list_data = $this->get_all_where($where, 0, 0, $option_fields[0])->result();
        $result = array();
        foreach ($list_data as $data) {
            $text = "";
            foreach ($option_fields as $option) {
                $text .= $data->$option . " ";
            }
            $result[$data->$key] = $text;
        }
        return $result;
    }

    //prepare a query string to get custom fields like as a normal field
    protected function prepare_custom_field_query_string($related_to, $custom_fields, $related_to_table)
    {

        $join_string = "";
        $select_string = "";
        $custom_field_values_table = $this->db->dbprefix('custom_field_values');


        if ($related_to && $custom_fields) {
            foreach ($custom_fields as $cf) {
                $cf_id = $cf->id;
                $virtual_table = "cfvt_$cf_id"; //custom field values virtual table

                $select_string .= " , $virtual_table.value AS cfv_$cf_id ";
                $join_string .= " LEFT JOIN $custom_field_values_table AS $virtual_table ON $virtual_table.related_to_type='$related_to' AND $virtual_table.related_to_id=$related_to_table.id AND $virtual_table.deleted=0 AND $virtual_table.custom_field_id=$cf_id ";
            }
        }

        return array("select_string" => $select_string, "join_string" => $join_string);
    }

    //get query of clients data according to to currency
    protected function _get_clients_of_currency_query($currency, $invoices_table, $clients_table)
    {
        $default_currency = get_setting("default_currency");
        $currency = $currency ? $currency : $default_currency;

        $client_where = ($currency == $default_currency) ? " AND $clients_table.currency='$default_currency' OR $clients_table.currency='' OR $clients_table.currency IS NULL" : " AND $clients_table.currency='$currency'";

        return " AND $invoices_table.client_id IN(SELECT $clients_table.id FROM $clients_table WHERE $clients_table.deleted=0 $client_where)";
    }

    //get query of clients data according to to currency
    protected function _get_suppliers_of_currency_query($currency, $purchase_orders_table, $suppliers_table)
    {
        $default_currency = get_setting("default_currency");
        $currency = $currency ? $currency : $default_currency;

        $supplier_where = ($currency == $default_currency) ? " AND $suppliers_table.currency='$default_currency' OR $suppliers_table.currency='' OR $suppliers_table.currency IS NULL" : " AND $suppliers_table.currency='$currency'";

        return " AND $purchase_orders_table.supplier_id IN(SELECT $suppliers_table.id FROM $suppliers_table WHERE $suppliers_table.deleted=0 $supplier_where)";
    }

    //get total invoice value calculation query
    protected function _get_invoice_value_calculation_query($invoices_table)
    {
        // $invoices_table=  strtolower($invoices_table);
        // $invoices_table=  'almukhainiproforma_invoices';
        $select_invoice_value = "IFNULL(items_table.invoice_value,0)";

        $after_tax_1 = "(IFNULL(tax_table.percentage,0)/100*$select_invoice_value)";
        $after_tax_2 = "(IFNULL(tax_table2.percentage,0)/100*$select_invoice_value)";

        $discountable_invoice_value = "IF($invoices_table.discount_type='after_tax', ($select_invoice_value + $after_tax_1 + $after_tax_2), $select_invoice_value )";

        $discount_amount = "IF($invoices_table.discount_amount_type='percentage', IFNULL($invoices_table.discount_amount,0)/100* $discountable_invoice_value, $invoices_table.discount_amount)";

        $before_tax_1 = "(IFNULL(tax_table.percentage,0)/100* ($select_invoice_value- $discount_amount))";
        $before_tax_2 = "(IFNULL(tax_table2.percentage,0)/100* ($select_invoice_value- $discount_amount))";


        $invoice_value_calculation_query = "(
                $select_invoice_value+
                IF($invoices_table.discount_type='before_tax',  ($before_tax_1+ $before_tax_2), ($after_tax_1 + $after_tax_2))
                - $discount_amount
               )";

        // echo $invoice_value_calculation_query;die();
        return $invoice_value_calculation_query;
    }

    //get total invoice value calculation query
    protected function _get_purchase_order_value_calculation_query($invoices_table)
    {
        $select_invoice_value = "IFNULL(items_table.purchase_order_value,0)";


        $invoice_value_calculation_query = "(
                $select_invoice_value
               )";

        return $invoice_value_calculation_query;
    }
    protected function _get_material_request_value_calculation_query($invoices_table)
    {
        $select_invoice_value = "IFNULL(items_table.material_request_value,0)";


        $invoice_value_calculation_query = "(
                $select_invoice_value
               )";

        return $invoice_value_calculation_query;
    }

    protected function _get_invoice_tax_value_calculation_query($invoices_table)
    {
        $select_invoice_value = "IFNULL(items_table.invoice_value,0)";
        $discount_amount = "IF($invoices_table.discount_amount_type='percentage', IFNULL($invoices_table.discount_amount,0)/100* $select_invoice_value, $invoices_table.discount_amount)";
        $invoice_value =  "($select_invoice_value - $discount_amount)";
        return "IF( $invoice_value * 0.01 *tax_table.percentage > 0, $invoice_value *0.01*tax_table.percentage,items_table.tax)";
    }
}
