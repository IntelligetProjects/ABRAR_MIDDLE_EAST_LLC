<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Accounting_reports extends MY_Controller {

    function __construct() {
        parent::__construct();
    }

    private function _get_branches_dropdown() {
        $categories = $this->Branches_model->get_all_where(array("deleted" => 0), 0, 0, "title")->result();

        $categories_dropdown = array(array("id" => "", "text" => "- " . lang("branch") . " -"));
        foreach ($categories as $category) {
            $categories_dropdown[] = array("id" => $category->id, "text" => $category->title);
        }

        return json_encode($categories_dropdown);
    }

    private function _get_units_dropdown() {

        $categories_dropdown = array(array("id" => "", "text" => "- " . lang("business_unit_(Allocated)") . " -"));
        $categories = array("retail", "pharma", "lab", "lab_referral", "cryoviva","shared","head_office","central_store",);
        foreach ($categories as $category) {
            $categories_dropdown[] = array("id" => $category, "text" => lang($category));
        }

        return json_encode($categories_dropdown);
    }
    
    public function index() {
        $view_data = array();
        $this->template->rander("accounting_reports/index",$view_data); 
    }

    public function balance_sheet() {
        $view_data = array();
        $period_array = $this->financial_period();
        $view_data['start_date'] = $period_array["start_date"];
        $view_data['end_date'] = $period_array["end_date"];
        //$view_data['branches_dropdown'] = $this->_get_branches_dropdown();
        //$view_data['units_dropdown'] = $this->_get_units_dropdown();
        $this->load->view("accounting_reports/balance_sheet",$view_data); 
    }

    public function balance_sheet_list_data() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $branch_id = $this->input->post('branch_id');
        $unit = $this->input->post('unit');
        $status = $this->input->post('status');
        
        $ids_array = array(1,2,3);
        $list_data = $this->Accounts_model->get_all_where(array("deleted" => 0, "where_in" => array("id" => $ids_array)))->result();

        if (!isset($status)) {
            $status = array();
        }

        $result = array();
        foreach ($list_data as $data) {

            $balance = $this->Accounts_model->get_balance($data->id, $start_date, $end_date, $branch_id, $unit);
            $result[] = $this->balance_sheet_make_row($data, $balance);

            $list_data1 = $this->Accounts_model->get_all_where(array("deleted" => 0, "acc_parent" => $data->id))->result();
            foreach ($list_data1 as $data1) {
                $balance = $this->Accounts_model->get_balance($data1->id, $start_date, $end_date, $branch_id, $unit);
                $result[] = $this->balance_sheet_make_row($data1, $balance);

                $list_data2 = $this->Accounts_model->get_all_where(array("deleted" => 0, "acc_parent" => $data1->id))->result();
                foreach ($list_data2 as $data2) {
                    if (in_array("details", $status)) {
                            //var_dump($status);
                            $balance = $this->Accounts_model->get_balance($data2->id, $start_date, $end_date, $branch_id, $unit);
                            if ($balance["total"] != 0 || in_array("zero", $status)) {
                                $result[] = $this->balance_sheet_make_row($data2, $balance);
                            }
                    }
                }
            }
        }

        echo json_encode(array("data" => $result));
    }

    private function balance_sheet_make_row($data, $balance) {

        if ($data->acc_parent == 0) {
            $level = anchor(get_uri("accounts/view/$data->id"), "<i class='fa fa-sign-in'></i>", array("class" => "edit", "id" => "1", "title" => "view"));
            $acc_name = "<span style = 'font-weight: bold'>Total ".$data->acc_name."</span>";
        } else if (in_array($data->acc_parent, array(1,2,3))) {
            $level = anchor(get_uri("accounts/view/$data->id"), "<i class='fa fa-sign-in'></i>", array("class" => "edit level_2", "id" => "2", "title" => "view"));
            $acc_name = "<span style = 'margin-left: 15px; font-weight: bold'>Total ".$data->acc_name."</span>";
        } else {
            $level = anchor(get_uri("accounts/view/$data->id"), "<i class='fa fa-sign-in'></i>", array("class" => "edit level3", "id" => "3", "title" => "view"));
            $acc_name = "<span style = 'margin-left: 25px;'>".$data->acc_name."</span>";
        }
        

        $result = array(1 , $data->acc_code, $acc_name, to_currency($balance["total"]), $level);
        return  $result;
    }


    public function profit_and_loss() {
        $view_data = array();
        $period_array = $this->financial_period();
        $view_data['start_date'] = $period_array["start_date"];
        $view_data['end_date'] = $period_array["end_date"];
        //$view_data['branches_dropdown'] = $this->_get_branches_dropdown();
        //$view_data['units_dropdown'] = $this->_get_units_dropdown();
        $this->load->view("accounting_reports/profit_and_loss",$view_data); 
    }
    public function profit_and_loss_get_data() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');

        $list_data = $this->Accounts_model->get_profit_loss_data($start_date,$end_date);
        $d=[];
        $dx=[];
        $d[]=1;
        $d[]=lang("gross_sales");
        $d[]=$list_data->gross_sales;
        array_push($dx,$d);
        $d=[];
        $d[]=2;
        $d[]=lang("sales_return");
        $d[]=$list_data->sales_return;
        array_push($dx,$d);
        $d=[];
        $d[]=3;
        $d[]=lang("net_sales");
        $d[]=$list_data->net_sales;
        array_push($dx,$d);
        $d=[];
        $d[]=4;
        $d[]=lang("cost_of_goods");
        $d[]=$list_data->cog;
        array_push($dx,$d);
        $d=[];
        $d[]=5;
        $d[]=lang("gross_profit");
        $d[]=$list_data->gross_profit;
        array_push($dx,$d);
        $d=[];
        $d[]=6;
        $d[]=lang("expenses");
        $d[]=$list_data->expenses;
        array_push($dx,$d);
        $d=[];
        $d[]=7;
        $d[]=lang("income_from_operations");
        $d[]=$list_data->income_from_operations;
        array_push($dx,$d);
        $d=[];
        $d[]=8;
        $d[]=lang("profit_befor_tax");
        $d[]=$list_data->profit_befor_tax;
        array_push($dx,$d);
        $d=[];
        $d[]=9;
        $d[]=lang("profit_after_tax");
        $d[]=$list_data->profit_after_tax;
        array_push($dx,$d);
        echo json_encode(array("data" => $dx));
    }
    public function profit_and_loss_list_data() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $branch_id = $this->input->post('branch_id');
        $unit = $this->input->post('unit');
        $status = $this->input->post('status');
        
        $ids_array = array(4,5);
        $list_data = $this->Accounts_model->get_all_where(array("deleted" => 0, "where_in" => array("id" => $ids_array)))->result();

        if (!isset($status)) {
            $status = array();
        }

        $result = array();
        foreach ($list_data as $data) {

            $balance = $this->Accounts_model->get_balance($data->id, $start_date, $end_date, $branch_id, $unit);
            $result[] = $this->profit_and_loss_make_row($data, $balance);

            $list_data1 = $this->Accounts_model->get_all_where(array("deleted" => 0, "acc_parent" => $data->id))->result();
            foreach ($list_data1 as $data1) {
                $balance = $this->Accounts_model->get_balance($data1->id, $start_date, $end_date, $branch_id, $unit);
                $result[] = $this->profit_and_loss_make_row($data1, $balance);

                $list_data2 = $this->Accounts_model->get_all_where(array("deleted" => 0, "acc_parent" => $data1->id))->result();
                foreach ($list_data2 as $data2) {
                    if (in_array("details", $status)) {
                            $balance = $this->Accounts_model->get_balance($data2->id, $start_date, $end_date, $branch_id, $unit);
                            if ($balance["total"] != 0 || in_array("zero", $status)) {
                                $result[] = $this->profit_and_loss_make_row($data2, $balance);
                            }
                    }
                }
            }
        }

        // net profit //


        $net_profit = new stdClass();
        $net_profit->acc_code = '';
        $net_profit->acc_name = "Net Profit";
        $net_profit->acc_parent = 999999999;
        $net_income = array();
        $net_expenses = $this->Accounts_model->get_balance(5, $start_date, $end_date, $branch_id, $unit);
        $net_revenue = $this->Accounts_model->get_balance(4, $start_date, $end_date, $branch_id, $unit);
        $net_income["total"] = - $net_revenue["total"] + $net_expenses["total"];



        $result[] = $this->profit_and_loss_make_row($net_profit, $net_income); 


        echo json_encode(array("data" => $result));
    }

    private function profit_and_loss_make_row($data, $balance) {


        if ($data->acc_parent == 0) {
            $level = anchor(get_uri("accounts/view/$data->id"), "<i class='fa fa-sign-in'></i>", array("class" => "edit", "id" => "1", "title" => "view"));
            $acc_name = "<span style = 'font-weight: bold'>".$data->acc_name."</span>";
        } else if (in_array($data->acc_parent, array(4,5))) {
            $level = anchor(get_uri("accounts/view/$data->id"), "<i class='fa fa-sign-in'></i>", array("class" => "edit level_2", "id" => "2", "title" => "view"));
            $acc_name = "<span style = 'margin-left: 15px; font-weight: bold'>".$data->acc_name."</span>";
        } else if ($data->acc_parent == 999999999) {
            $level = "";
            $acc_name = "<span style = 'font-weight: bold'>".$data->acc_name."</span>";
            
        } else {
            $level = anchor(get_uri("accounts/view/$data->id"), "<i class='fa fa-sign-in'></i>", array("class" => "edit level3", "id" => "3", "title" => "view"));
            $acc_name = "<span style = 'margin-left: 25px;'>".$data->acc_name."</span>";
        }
        

        $result = array(1 , $data->acc_code, $acc_name, to_currency($balance["total"]), $level);
        return  $result;
    }


    public function cash_flow() {
        $view_data = array();
        $period_array = $this->financial_period();
        $view_data['start_date'] = $period_array["start_date"];
        $view_data['end_date'] = $period_array["end_date"];
        //$view_data['branches_dropdown'] = $this->_get_branches_dropdown();
        //$view_data['units_dropdown'] = $this->_get_units_dropdown();
        $this->load->view("accounting_reports/cash_flow",$view_data); 
    }

    public function cash_flow_list_data() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $branch_id = $this->input->post('branch_id');
        $unit = $this->input->post('unit');
        $list = array();

        // beginning of period

        $list[] = array("Cash and cash equivalents, beginning of period","",1);

        $start_date_minus_a_day = date("Y-m-d", strtotime($start_date. ' - 1 days'));


        $bank_and_cash_accounts = $this->Accounts_model->get_all_where(array("deleted" => 0, "acc_parent" => 8))->result();
        foreach ($bank_and_cash_accounts as $bank_and_cash_account) {
            $balance = $this->Accounts_model->get_balance($bank_and_cash_account->id, "", $start_date_minus_a_day, $branch_id, $unit);
            $list[] = array($bank_and_cash_account->acc_name, $balance["total"], 2);
        }


        $bank_and_cash_balance = $this->Accounts_model->get_balance(8, "", $start_date_minus_a_day, $branch_id, $unit);
        $list[] = array("Total Cash and cash equivalents, beginning of period", $bank_and_cash_balance["total"] ,1);


        //////// Cash flow from operations

        $list[] = array("Cash flow from operations","",1);


        // Net income

        $net_expenses = $this->Accounts_model->get_balance(5, $start_date, $end_date, $branch_id, $unit);
        $net_revenue = $this->Accounts_model->get_balance(4, $start_date, $end_date, $branch_id, $unit);
        $net_income["total"] = $net_revenue["total"] - $net_expenses["total"];

        $list[] = array("Net Income", $net_income["total"], 2);
        

        /// Net Change in cash and cash equivalents

        $Depreciation = $this->Accounts_model->get_balance(22, $start_date, $end_date, $branch_id, $unit);

        $list[] = array("Depreciation", $Depreciation["total"], 2);

        $Depreciation_accounts = $this->Accounts_model->get_all_where(array("deleted" => 0, "acc_parent" => 22))->result();
        foreach ($Depreciation_accounts as $Depreciation_account) {
            $balance = $this->Accounts_model->get_balance($Depreciation_account->id, $start_date, $end_date, $branch_id, $unit);
            $list[] = array($Depreciation_account->acc_name, $balance["total"], 3);
        }

        $Receivable = $this->Accounts_model->get_balance(7, $start_date, $end_date, $branch_id, $unit);

        $list[] = array("Receivable", ($Receivable["total"]*-1), 2);

        $Receivable_accounts = $this->Accounts_model->get_all_where(array("deleted" => 0, "acc_parent" => 7))->result();
        foreach ($Receivable_accounts as $Receivable_account) {
            $balance = $this->Accounts_model->get_balance($Receivable_account->id, $start_date, $end_date, $branch_id, $unit);
            $list[] = array($Receivable_account->acc_name, ($balance["total"]*-1), 3);
        }

        $Payable = $this->Accounts_model->get_balance(33, $start_date, $end_date, $branch_id, $unit);

        $list[] = array("Payable", $Payable["total"], 2);

        $Payable_accounts = $this->Accounts_model->get_all_where(array("deleted" => 0, "acc_parent" => 33))->result();
        foreach ($Payable_accounts as $Payable_account) {
            $balance = $this->Accounts_model->get_balance($Payable_account->id, $start_date, $end_date, $branch_id, $unit);
            $list[] = array($Payable_account->acc_name, $balance["total"], 3);
        }

        $Credit = $this->Accounts_model->get_balance(14, $start_date, $end_date, $branch_id, $unit);

        $list[] = array("Credit Card", $Credit["total"], 2);

        $Credit_accounts = $this->Accounts_model->get_all_where(array("deleted" => 0, "acc_parent" => 14))->result();
        foreach ($Credit_accounts as $Credit_account) {
            $balance = $this->Accounts_model->get_balance($Credit_account->id, $start_date, $end_date, $branch_id, $unit);
            $list[] = array($Credit_account->acc_name, $balance["total"], 3);
        }

        $Current_assets = $this->Accounts_model->get_balance(9, $start_date, $end_date, $branch_id, $unit);

        $list[] = array("Current Assets", ($Current_assets["total"]*-1), 2);

        $Current_assets_accounts = $this->Accounts_model->get_all_where(array("deleted" => 0, "acc_parent" => 9))->result();
        foreach ($Current_assets_accounts as $Current_assets_account) {
            $balance = $this->Accounts_model->get_balance($Current_assets_account->id, $start_date, $end_date, $branch_id, $unit);
            $list[] = array($Current_assets_account->acc_name, ($balance["total"]*-1), 3);
        }

        $Current_liabilities = $this->Accounts_model->get_balance(35, $start_date, $end_date, $branch_id, $unit);

        $list[] = array("Current Liabilities", $Current_liabilities["total"], 2);

        $Current_liabilities_accounts = $this->Accounts_model->get_all_where(array("deleted" => 0, "acc_parent" => 35))->result();
        foreach ($Current_liabilities_accounts as $Current_liabilities_account) {
            $balance = $this->Accounts_model->get_balance($Current_liabilities_account->id, $start_date, $end_date, $branch_id, $unit);
            $list[] = array($Current_liabilities_account->acc_name, $balance["total"], 3);
        }

        ////

        $total_operation = $Depreciation["total"] - $Receivable["total"] + $Payable["total"] + $Credit["total"] - $Current_assets["total"] + $Current_liabilities["total"];

        $list[] = array("Total Cash flow from operations", $total_operation, 1);


         //////// cash flow from investing

        $list[] = array("Investing Activities","",1);

        ///

        $nCurrent_assets = $this->Accounts_model->get_balance(10, $start_date, $end_date, $branch_id, $unit);

        $list[] = array("Non Current Assets", ($nCurrent_assets["total"]*-1), 2);

        $nCurrent_assets_accounts = $this->Accounts_model->get_all_where(array("deleted" => 0, "acc_parent" => 10))->result();
        foreach ($nCurrent_assets_accounts as $nCurrent_assets_account) {
            $balance = $this->Accounts_model->get_balance($nCurrent_assets_account->id, $start_date, $end_date, $branch_id, $unit);
            $list[] = array($nCurrent_assets_account->acc_name, ($balance["total"]*-1), 3);
        }

        /////

        $fCurrent_assets = $this->Accounts_model->get_balance(12, $start_date, $end_date, $branch_id, $unit);

        $list[] = array("Fixed Current Assets", ($fCurrent_assets["total"]*-1), 2);

        $fCurrent_assets_accounts = $this->Accounts_model->get_all_where(array("deleted" => 0, "acc_parent" => 12))->result();
        foreach ($fCurrent_assets_accounts as $fCurrent_assets_account) {
            $balance = $this->Accounts_model->get_balance($fCurrent_assets_account->id, $start_date, $end_date, $branch_id, $unit);
            $list[] = array($fCurrent_assets_account->acc_name, ($balance["total"]*-1), 3);
        }


        $total_investing_activites = -1*($fCurrent_assets["total"] + $nCurrent_assets["total"]);
        $list[] = array("Total Investing Activities", $total_investing_activites,1);


        /// cash flow from finanacing activites

        $list[] = array("Finanacing Activities","",1);

        ///

        $nCurrent_liabilities = $this->Accounts_model->get_balance(18, $start_date, $end_date, $branch_id, $unit);

        $list[] = array("Non Current Liabilities", ($nCurrent_liabilities["total"]), 2);

        $nCurrent_liabilities_accounts = $this->Accounts_model->get_all_where(array("deleted" => 0, "acc_parent" => 18))->result();
        foreach ($nCurrent_liabilities_accounts as $nCurrent_liabilities_account) {
            $balance = $this->Accounts_model->get_balance($nCurrent_liabilities_account->id, $start_date, $end_date, $branch_id, $unit);
            $list[] = array($nCurrent_liabilities_account->acc_name, ($balance["total"]), 3);
        }

        /// equity

        $equity = $this->Accounts_model->get_balance(19, $start_date, $end_date, $branch_id, $unit);

        $list[] = array("Issuance of Stock", ($equity["total"]), 2);

        $equity_accounts = $this->Accounts_model->get_all_where(array("deleted" => 0, "acc_parent" => 19))->result();
        foreach ($equity_accounts as $equity_account) {
            $balance = $this->Accounts_model->get_balance($equity_account->id, $start_date, $end_date, $branch_id, $unit);
            $list[] = array($equity_account->acc_name, ($balance["total"]), 3);
        }

        /// earings or dividends

        $earnings = $this->Accounts_model->get_balance(20, $start_date, $end_date, $branch_id, $unit);

        $list[] = array("Earnings and Payment of Dividends", ($earnings["total"]), 2);

        $earnings_accounts = $this->Accounts_model->get_all_where(array("deleted" => 0, "acc_parent" => 20))->result();
        foreach ($earnings_accounts as $earnings_account) {
            $balance = $this->Accounts_model->get_balance($earnings_account->id, $start_date, $end_date, $branch_id, $unit);
            $list[] = array($earnings_account->acc_name, -1*($balance["total"]), 3);
        }

        ////

        $total_financing_activites = ($nCurrent_liabilities["total"] + $nCurrent_assets["total"]);
        $list[] = array("Total Finanacing Activities", $total_financing_activites,1);

        /// closing of period

        $list[] = array("Cash and cash equivalents, closing balance","",1);


        $bank_and_cash_accounts2 = $this->Accounts_model->get_all_where(array("deleted" => 0, "acc_parent" => 8))->result();
        foreach ($bank_and_cash_accounts2 as $bank_and_cash_account2) {
            $balance = $this->Accounts_model->get_balance($bank_and_cash_account2->id, $start_date, $end_date, $branch_id, $unit);
            $list[] = array($bank_and_cash_account2->acc_name, $balance["total"], 2);
        }

        $bank_and_cash_balance2 = $this->Accounts_model->get_balance(8, $start_date, $end_date, $branch_id, $unit);
        $list[] = array("Total Cash and cash equivalents, closing balance", $bank_and_cash_balance2["total"] ,1);

        
        $bank_and_cash_balance_begin = $this->Accounts_model->get_balance(8, "", $start_date_minus_a_day, $branch_id, $unit);
        $bank_and_cash_balance_end = $this->Accounts_model->get_balance(8, $start_date, $end_date, $branch_id, $unit);
        $change_in_cash = $bank_and_cash_balance_begin["total"] - $bank_and_cash_balance_end["total"] ;
        $list[] = array("Total Change in Cash and cash equivalents", $change_in_cash     ,1);

        ///

        $result = array();

        foreach ($list as $lst) {
            $result[] = $this->cash_flow_make_row($lst);
        }

        echo json_encode(array("data" => $result));
    }

    private function cash_flow_make_row($data) {


        if ($data["2"] == 1) {
            $name = "<span style = ' font-weight: bold'>".$data["0"]."</span>";
        } else if ($data["2"] == 2) {
            $name = "<span style = 'margin-left: 15px; font-weight: bold; color: grey;'>".$data["0"]."</span>";
        } else {
            $name = "<span style = 'margin-left: 25px;'>".$data["0"]."</span>";
        }

        $result = array(1 , $name, is_numeric($data["1"]) ? to_currency($data["1"]) : $data["1"]);
        return  $result;
    }


    public function trial_balance() {
        $view_data = array();
        $period_array = $this->financial_period();
        $view_data['start_date'] = $period_array["start_date"];
        $view_data['end_date'] = $period_array["end_date"];
        //$view_data['branches_dropdown'] = $this->_get_branches_dropdown();
        //$view_data['units_dropdown'] = $this->_get_units_dropdown();
        $this->load->view("accounting_reports/trial_balance",$view_data); 
    }

    public function trial_balance_list_data(){
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $branch_id = $this->input->post('branch_id');
        $unit = $this->input->post('unit');
        $status = $this->input->post('status');
        // var_dump($this->get_opening_balance(1,$start_date)->type);
        // die();
        // $ids_array = array(4,5);
        // $list_data = $this->Accounts_model->get_all_where(array("deleted" => 0, "where_in" => array("id" => $ids_array)))->result();
        // $list_data = $this->Accounts_model->get_all_where(array("deleted" => 0))->result();
        $list_data = $this->Accounts_model->get_all_order_by_acc_code(array())->result();
        if (!isset($status)) {
            $status = array();
        }
        $result = array();
        foreach ($list_data as $data) {
            $opening_balance=$this->get_opening_balance($data->id,$start_date);
            // var_dump($opening_balance);die();
            $balance = $this->Accounts_model->get_balance($data->id, $start_date, $end_date, $branch_id, $unit);
            $result[] = $this->trial_balance_make_row($data,$opening_balance, $balance);

            $list_data1 = $this->Accounts_model->get_all_where(array("deleted" => 0, "acc_parent" => $data->id))->result();
            // foreach ($list_data1 as $data1) {
            //     $opening_balance=$this->get_opening_balance($data1->id,$start_date);
            //     $balance = $this->Accounts_model->get_balance($data1->id, $start_date, $end_date, $branch_id, $unit);
            //     $result[] = $this->trial_balance_make_row($data1, $opening_balance,$balance);

            //     $list_data2 = $this->Accounts_model->get_all_where(array("deleted" => 0, "acc_parent" => $data1->id))->result();
            //     foreach ($list_data2 as $data2) {
            //         if (in_array("details", $status)) {
            //             $opening_balance=$this->get_opening_balance($data2->id,$start_date);
            //                 $balance = $this->Accounts_model->get_balance($data2->id, $start_date, $end_date, $branch_id, $unit);
            //                 if ($balance["total"] != 0 || in_array("zero", $status)) {
            //                     $result[] = $this->trial_balance_make_row($data2,$opening_balance, $balance);
            //                 }
            //         }
            //     }
            // }
        }


      echo json_encode(array("data" => $result));
  }

  private function trial_balance_make_row($data,$opening_balance, $balance) {

// var_dump($balance['cr_balance']);
// var_dump($balance['dr_balance']);
      if ($data->acc_parent == 0) {
          $level = anchor(get_uri("accounts/view/$data->id"), "<i class='fa fa-sign-in'></i>", array("class" => "edit", "id" => "1", "title" => "view"));
          $acc_name = "<span style = 'font-weight: bold'>".$data->acc_name."</span>";
      } 
    //   else if (in_array($data->acc_parent, array(4,5))) {
    //     //   $level = anchor(get_uri("accounts/view/$data->id"), "<i class='fa fa-sign-in'></i>", array("class" => "edit level_2", "id" => "2", "title" => "view"));
    //     //   $acc_name = "<span style = 'margin-left: 15px; font-weight: bold'>".$data->acc_name."</span>";
    //   }
       else if ($data->acc_parent == 999999999) {
          $level = "";
          $acc_name = "<span style = 'font-weight: bold'>".$data->acc_name."</span>";
      } else {
          $level = anchor(get_uri("accounts/view/$data->id"), "<i class='fa fa-sign-in'></i>", array("class" => "edit level3", "id" => "3", "title" => "view"));
          $acc_name = "<span style = 'margin-left: 25px;'>".$data->acc_name."</span>";
      }
      
    $closing_balance_dr=$opening_balance->dr_balance+$balance["dr_balance"]-$opening_balance->cr_balance-$balance["cr_balance"];
    $closing_balance_cr=$opening_balance->cr_balance+$balance["cr_balance"]-$opening_balance->dr_balance-$balance["dr_balance"];
      $result = array(1 , $data->acc_code, $acc_name,to_currency($opening_balance->dr_balance),to_currency($opening_balance->cr_balance),
      to_currency($balance["dr_balance"]),to_currency($balance["cr_balance"]),
      to_currency($closing_balance_dr),to_currency($closing_balance_cr), $level);
      return  $result;
  }


    
    public function get_opening_balance($acc,$start_date){
      
        $sub_accounts_liabilities = $this->Accounts_model->get_children(2)->list;
        $sub_accounts_owners = $this->Accounts_model->get_children(3)->list;
        $sub_accounts_revenue = $this->Accounts_model->get_children(5)->list;
        $sub_accounts_expense = $this->Accounts_model->get_children(4)->list;
        $sub_accounts_off_bs = $this->Accounts_model->get_children(6)->list;
        $sub_accounts = $sub_accounts_liabilities.",".$sub_accounts_owners.",".$sub_accounts_revenue.",2,3,5";
        $sub_accounts = explode(',',  $sub_accounts); 

        $balance = 0;
        $result = array();
        if($acc == 0) {
            $list_data = array();
        }

        $sub_accounts2 = $sub_accounts_expense.",".$sub_accounts_off_bs.",".$sub_accounts_revenue.",4,6,5";
        $sub_accounts2 = explode(',',  $sub_accounts2); 

        $start_date_minus_a_day = date("Y-m-d", strtotime($start_date. ' - 1 days'));
        if ($start_date && $acc != 0 && !in_array($acc, $sub_accounts2)) {
        $bal = $this->Accounts_model->get_balance($acc, "", $start_date_minus_a_day);
        //var_dump($bal);
  
        $open_balance = new stdClass();
        $open_balance->id = 0;
        $open_balance->date = $start_date_minus_a_day;
        $open_balance->type = $bal["total_type"];
        $open_balance->cr_balance = $bal["cr_balance"];
        $open_balance->dr_balance = $bal["dr_balance"];
        $open_balance->reference = "Opening Balance";
        $open_balance->trans_id = 0;
        $open_balance->account = $acc;
        $open_balance->amount = $bal["total"];
        $open_balance->narration = "Opening Balance";
        $open_balance->branch_id = 0;
        $open_balance->sub_account_id = 0;
        $open_balance->sub_account_type = "";
  
     
     }else{
        $open_balance = new stdClass();
        $open_balance->id = 0;
        $open_balance->date = $start_date_minus_a_day;
        $open_balance->type = null;
        $open_balance->cr_balance = 0.00;
        $open_balance->dr_balance = 0.00;
        $open_balance->reference = "Opening Balance";
        $open_balance->trans_id = 0;
        $open_balance->account = $acc;
        $open_balance->amount = 0.00;
        $open_balance->narration = "Opening Balance";
        $open_balance->branch_id = 0;
        $open_balance->sub_account_id = 0;
        $open_balance->sub_account_type = "";
     }
    return  $open_balance;
    }

    public function financial_period () {
        $start_financial = date("m-d", strtotime(get_setting("financial_year_end")));
        $current_date = get_my_local_time("m-d");
        $current_year = get_my_local_time("Y");

        $start_financial_1day = date("m-d", strtotime(get_setting("financial_year_end"). ' + 1 days'));

        /*getting the current financial period */ 

        if($current_date > $start_financial) {
            $start_date = ($current_year)."-".$start_financial_1day;
            $end_date =  ($current_year+1)."-".$start_financial;
        } else {    
            $start_date = ($current_year-1)."-".$start_financial_1day;
            $end_date = ($current_year)."-".$start_financial;
        }

        return array("start_date" => $start_date, "end_date" => $end_date);
    }

    private function _get_accounts_dropdown($parent) {

        $categories = $this->Accounts_model->get_all_where(array("deleted" => 0, "acc_parent" => $parent), 0, 0, "acc_name")->result();

        $categories_dropdown = array(array("id" => "", "text" => "- " . lang("Account") . " -"));
        foreach ($categories as $category) {
            $categories_dropdown[] = array("id" => $category->id, "text" => $category->acc_name);
        }

        return json_encode($categories_dropdown);
    }

    

    public function expenses() {
        $view_data = array();
        $period_array = $this->financial_period();
        $view_data['start_date'] = $period_array["start_date"];
        $view_data['end_date'] = $period_array["end_date"];
        //$view_data['branches_dropdown'] = $this->_get_branches_dropdown();
        //$view_data['units_dropdown'] = $this->_get_units_dropdown();
        $this->load->view("accounting_reports/expenses",$view_data); 
    }

    public function expenses_list_data() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $branch_id = $this->input->post('branch_id');
        $unit = $this->input->post('unit');
        $status = $this->input->post('status');
        
        $ids_array = array(541,542);
        $list_data = $this->Accounts_model->get_all_where(array("deleted" => 0, "where_in" => array("id" => $ids_array)))->result();

        if (!isset($status)) {
            $status = array();
        }

        $result = array();
        foreach ($list_data as $data) {

            $balance = $this->Accounts_model->get_balance($data->id, $start_date, $end_date, $branch_id, $unit);
            $result[] = $this->expense_make_row($data, $balance);

            $list_data1 = $this->Accounts_model->get_all_where(array("deleted" => 0, "acc_parent" => $data->id))->result();
            foreach ($list_data1 as $data1) {
                $balance = $this->Accounts_model->get_balance($data1->id, $start_date, $end_date, $branch_id, $unit);
                $result[] = $this->expense_make_row($data1, $balance);

                $list_data2 = $this->Accounts_model->get_all_where(array("deleted" => 0, "acc_parent" => $data1->id))->result();
                foreach ($list_data2 as $data2) {
                    if (in_array("details", $status)) {
                            $balance = $this->Accounts_model->get_balance($data2->id, $start_date, $end_date, $branch_id, $unit);
                            if ($balance["total"] != 0 || in_array("zero", $status)) {
                                $result[] = $this->expense_make_row($data2, $balance);
                            }
                    }
                }
            }
        }


       // $result[] = $this->expense_make_row($net_profit, $net_income); 


        echo json_encode(array("data" => $result));
    }

    private function expense_make_row($data, $balance) {


        if (in_array($data->id, array(541,542))) {
            $level = anchor(get_uri("accounts/view/$data->id"), "<i class='fa fa-sign-in'></i>", array("class" => "edit", "id" => "1", "title" => "view"));
            $acc_name = "<span style = 'font-weight: bold'>".$data->acc_name."</span>";
        } else if (in_array($data->acc_parent, array(541,542))) {
            $level = anchor(get_uri("accounts/view/$data->id"), "<i class='fa fa-sign-in'></i>", array("class" => "edit level_2", "id" => "2", "title" => "view"));
            $acc_name = "<span style = 'margin-left: 15px; font-weight: bold'>".$data->acc_name."</span>";
        } else if ($data->acc_parent == 999999999) {
            $level = "";
            $acc_name = "<span style = 'font-weight: bold'>".$data->acc_name."</span>";
            
        } else {
            $level = anchor(get_uri("accounts/view/$data->id"), "<i class='fa fa-sign-in'></i>", array("class" => "edit level3", "id" => "3", "title" => "view"));
            $acc_name = "<span style = 'margin-left: 25px;'>".$data->acc_name."</span>";
        }
        

        $result = array(1 , $data->acc_code, $acc_name, to_currency($balance["total"]), $level);
        return  $result;
    }

    function test() {
        $list_data2 = $this->Accounts_model->get_clients_opening_balances()->result();
        pretty_me($list_data2);
    }

}