<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Items_report extends MY_Controller {

    function __construct() {
        parent::__construct();
        //$this->access_only_admin();
    }

    function index() {

        $view_data["products"] = $this->Items_model->get_all_where(array("deleted"=>0, "item_type"=>"product"))->num_rows();
        $view_data["services"] = $this->Items_model->get_all_where(array("deleted"=>0, "item_type"=>"service"))->num_rows();
        $view_data["suppliers"] = $this->Suppliers_model->get_all_where(array("deleted"=>0))->num_rows();
        $view_data["all_items"] = $view_data["services"] + $view_data["products"];
        
        $this->load->view("reports_view/items", $view_data);
    }

    function test() {
        $info = $this->Reports_model->get_items_cat_statistics();
        var_dump($info);
    }

    function list_data() {
        $list_data = $this->Reports_model->get_sold_items();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    //get the row of summary
    private function _make_row($data) {

        $item_avg_cost = number_format($data->cost_price * $data->total, 3);
        if ($data->type == 'product') {
        $product_info = $this->Invoices_model->get_invoice_item_inventory($data->id);
            $item_avg_cost = number_format($data->cost_price * $data->total, 3);
            if(isset($product_info->sum_total) && !empty($product_info->sum_qty)) {
                $item_cost = $product_info->sum_total / $product_info->sum_qty;
                $item_avg_cost = number_format($item_cost * $data->total, 3);
            }
        }
        $item_avg_rate = number_format($data->rate * $data->total, 3);
        $item_info = $this->Invoices_model->get_invoice_item_sold($data->id);
        if(isset($item_info->sum_total) && !empty($item_info->sum_qty)) {
            $item_rate = $item_info->sum_total / $item_info->sum_qty;
            $item_avg_rate = number_format($item_rate * $data->total, 3);
        }

        if ($item_avg_rate == 0 || $item_avg_cost == 0) {
            $percent = "-";
        } else {
            $a= floatval(floatval($item_avg_rate)-floatval($item_avg_cost));
            $b= floatval($item_avg_rate);
            $percent = round(floatval($a/$b*100),3);
        }

        return array(
            $data->title ." - ". lang($data->type),
            $data->total,
            $item_avg_cost,
            $item_avg_rate,
            $percent,
        );
    }

}

/* End of file Expenses_report.php */
/* Location: ./application/controllers/report.php */