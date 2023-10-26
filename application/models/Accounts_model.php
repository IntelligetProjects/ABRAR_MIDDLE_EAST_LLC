<?php

class Accounts_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table =$this->db->dbprefix('accounts');
        parent::__construct($this->table);
       
    }   


    function get_details($options = array()) {

        $where = "";
       
        $parent = get_array_value($options, "parent_id");
        if ($parent) {
            if ($parent == 'root') {
                $where .= " AND acc_parent = 0";
            } else {
                $where .= " AND (acc_parent = $parent)";
            }
        }

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " And id = $id";
        }

        $sql = "SELECT * FROM $this->table WHERE $this->table.deleted = 0 $where";
        return $this->db->query($sql);
    }

    function get_all_order_by_acc_code($options = array()) {

        $where = "";
       
        $parent = get_array_value($options, "parent_id");
        if ($parent) {
            if ($parent == 'root') {
                $where .= " AND acc_parent = 0";
            } else {
                $where .= " AND (acc_parent = $parent)";
            }
        }

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " And id = $id";
        }

        $sql = "SELECT * FROM $this->table WHERE $this->table.deleted = 0 
        $where ORDER BY $this->table.acc_code ASC";
        return $this->db->query($sql);
    }


    /*function get_all_accounts($options = array()) {

        $sql = "
        SELECT
            *,
            @pv := `accounts`.id,
            @id := `accounts`.id,
            @list := 
                (
                SELECT
                    GROUP_CONCAT(id)
                FROM
                    (
                    SELECT
                        id,
                        acc_parent
                    FROM
                        accounts
                    ORDER BY
                        acc_parent,
                        id
                ) AS accounts_sorted,
                (
            SELECT
                (@pv)
            ) initialisation
        WHERE
            FIND_IN_SET(acc_parent, @pv) AND LENGTH(@pv := CONCAT(@pv, ',', id))
            )
        AS LIST,
            ( SELECT IFNULL(SUM(IF(enteries.type = 'dr', enteries.amount, 0)), 0) - IFNULL(SUM(IF(enteries.type = 'cr', enteries.amount, 0)), 0)
        FROM enteries INNER JOIN transactions ON enteries.trans_id = transactions.id WHERE transactions.deleted = 0 AND enteries.deleted = 0 AND ( FIND_IN_SET(enteries.account, @list) OR enteries.account = @id ) ) AS  balance
        FROM
            `accounts`
        WHERE
            `accounts`.deleted = 1";

        $sqlSetting  = "SET SESSION GROUP_CONCAT_MAX_LEN = 99999999;";

        $this->db->query($sqlSetting);

        return $this->db->query($sql);
    }*/


    function get_direct_childern($options) {

        $accounts = array();
        $accounts = get_array_value($options, "accounts");
        if (is_array($accounts) && count($accounts)) {
            $accounts = join(",", $accounts);
        }

        $sql = "SELECT  GROUP_CONCAT(id) list                
                from    $this->table
                where   $this->table.deleted = 0
                and     $this->table.acc_parent IN ($accounts)";

        return $this->db->query($sql)->row()->list;
    }

    function get_profit_loss_data($start_date ='2000-01-01', $end_date ='2099-12-31'){
        $invoice_items_table = $this->db->dbprefix('invoice_items');
        $invoices_table= $this->db->dbprefix('invoices');
        $taxes_table= $this->db->dbprefix('taxes');
        $sale_returns_table= $this->db->dbprefix('sale_returns');
        $sale_return_items_table= $this->db->dbprefix('sale_return_items');
        $expenses_table= $this->db->dbprefix('expenses');
        $taxes_table= $this->db->dbprefix('taxes');
        $taxes_table= $this->db->dbprefix('taxes');
        //Total invoices 
        $sale_invoices_sql="
        SELECT 
        SUM($invoice_items_table.total - IF($invoice_items_table.discount_amount_type='percentage',$invoice_items_table.total*$invoice_items_table.discount_amount/100,$invoice_items_table.discount_amount)) AS total,
        SUM(($invoice_items_table.total - IF($invoice_items_table.discount_amount_type='percentage',$invoice_items_table.total*$invoice_items_table.discount_amount/100,$invoice_items_table.discount_amount))*$taxes_table.percentage*0.01) AS tax
        FROM $invoices_table
        INNER JOIN $invoice_items_table
        ON $invoices_table.id = $invoice_items_table.invoice_id 
        LEFT JOIN $taxes_table
        ON $taxes_table.id= $invoice_items_table.tax_id
        WHERE approval_status ='approved' AND $invoices_table.deleted=0  AND $invoice_items_table.deleted=0 AND ($invoices_table.bill_date BETWEEN '$start_date' AND '$end_date')
        ";
        $sales = $this->db->query($sale_invoices_sql)->row();
        // Total Sales Return 
        $sales_return_sql="
        SELECT 
        SUM(($invoice_items_table.rate * $sale_return_items_table.quantity) - IF($invoice_items_table.discount_amount_type='percentage',$invoice_items_table.rate * $sale_return_items_table.quantity*$invoice_items_table.discount_amount/100,$invoice_items_table.discount_amount)) AS total,
        SUM((($invoice_items_table.rate * $sale_return_items_table.quantity) - IF($invoice_items_table.discount_amount_type='percentage',$invoice_items_table.rate * $sale_return_items_table.quantity*$invoice_items_table.discount_amount/100,$invoice_items_table.discount_amount))*$taxes_table.percentage*0.01) AS tax
        FROM $sale_returns_table
        INNER JOIN $invoices_table 
        ON $sale_returns_table.invoice_id = $invoices_table.id 
        INNER JOIN $sale_return_items_table
        ON $sale_return_items_table.sale_return_id=$sale_returns_table.id
        INNER JOIN  $invoice_items_table  
        ON $invoice_items_table.id = $sale_return_items_table.invoice_item_id
        LEFT JOIN $taxes_table
        ON $taxes_table.id= $invoice_items_table.tax_id
        WHERE $sale_returns_table.`status` ='approved' AND $sale_returns_table.deleted=0  AND $sale_return_items_table.deleted=0 AND $invoice_items_table.deleted=0 AND ($sale_returns_table.date BETWEEN '$start_date' AND '$end_date')
        ";
        $sales_return = $this->db->query($sales_return_sql)->row();
        // Total Cost of Goods 
        $cog_sql="
        SELECT 
        SUM($invoice_items_table.cost * $invoice_items_table.quantity) AS total_cost
        FROM $invoices_table
        INNER JOIN $invoice_items_table
        ON $invoices_table.id = $invoice_items_table.invoice_id 
        WHERE approval_status ='approved' AND $invoices_table.deleted=0  AND $invoice_items_table.deleted=0 AND ($invoices_table.bill_date BETWEEN '$start_date' AND '$end_date')
        ";
        $cos = $this->db->query($cog_sql)->row();
        // Total Expenses 
        $expenses_sql="
        SELECT SUM($expenses_table.amount) AS total,
        SUM(($expenses_table.amount * $taxes_table.percentage)*0.01) AS tax
        FROM $expenses_table 
        LEFT JOIN  $taxes_table 
        ON  $expenses_table.tax_id=$taxes_table.id
        WHERE $expenses_table.status ='approved' AND ($expenses_table.expense_date BETWEEN '$start_date' AND '$end_date')
        ";
        $expenses = $this->db->query($expenses_sql)->row();
        $result = new stdClass();
        $result->gross_sales=number_format($sales->total, 3, ".", "");
        $result->gross_sales_tax=number_format($sales->total +$sales->tax, 3, ".", "");
        $result->sales_return=number_format($sales_return->total, 3, ".", "");
        $result->sales_return_tax=number_format($sales_return->total +$sales_return->tax, 3, ".", "");
        $result->net_sales=number_format($result->gross_sales-$result->sales_return, 3, ".", "");
        $result->net_sales_tax=number_format($result->gross_sales_tax-$result->sales_return_tax, 3, ".", "");
        $result->cog=number_format($cos->total_cost, 3, ".", "");
        $result->gross_profit= number_format($result->net_sales - $result->cog, 3, ".", "");
        $result->gross_profit_tax= number_format($result->net_sales_tax - $result->cog, 3, ".", "");
        $result->expenses= number_format($expenses->total, 3, ".", "");
        $result->expenses_tax= number_format($expenses->total+$expenses->tax, 3, ".", "");
        $result->profit_befor_tax= number_format($result->gross_profit - $result->expenses, 3, ".", "");
        $result->profit_after_tax= number_format($result->gross_profit_tax - $result->expenses_tax, 3, ".", "");
        $result->income_from_operations=number_format(0, 3, ".", "");
        return $result;
    }
    function get_balance($acc, $start_date ='2000-01-01', $end_date ='2099-12-31', $branch_id = 0, $unit = ""){
        $enteries = $this->db->dbprefix('enteries');
        $transactions= $this->db->dbprefix('transactions');
        $dr_balance = 0;
        $cr_balance = 0; 

        $list = $this->get_children($acc)->list;

        if ($list) {
            $list .= "," . $acc;
        } else {
            $list .= $acc;
        }

        $where = "";
        if ($start_date && $end_date) {
            $where .= " AND (trans.date BETWEEN '$start_date' AND '$end_date') ";
        } else if ($end_date) {
            $where .= " AND (trans.date BETWEEN '2000-01-01' AND '$end_date') ";
        }

        $head_office_retail_allocation = get_setting('head_office_retail_allocation');
        $head_office_lab_allocation = get_setting('head_office_lab_allocation');
        $head_office_lab_refferal_allocation = get_setting('head_office_lab_refferal_allocation');
        $head_office_pharma_allocation = get_setting('head_office_pharma_allocation');
        $head_office_cryoviva_allocation = get_setting('head_office_cryoviva_allocation');
        $retail_g1_allocation  = get_setting("retail_g1_allocation");
        $retail_g2_allocation  = get_setting("retail_g2_allocation");
        $retail_qurum_allocation  = get_setting("retail_qurum_allocation");
        $central_store_retail_allocation  = get_setting("central_store_retail_allocation");
        $central_store_lab_allocation = get_setting("central_store_lab_allocation");
        $central_store_pharma_allocation = get_setting("central_store_pharma_allocation");

        if ($branch_id && $unit != "retail") {
            $where .= " AND ent.branch_id= 909009";
        } 

        $hoa = 100;
        $csa = 100;

        if ($unit == "retail") {
            $where .= " AND ent.unit IN ('central_store','head_office','retail')";
            $hoa = $head_office_retail_allocation;
            $csa = $central_store_retail_allocation;
            if ($branch_id) {
                $where .= " AND ( ent.branch_id = $branch_id OR ent.unit = 'head_office' ) ";
                if($branch_id = 4) {//g1
                    $hoa = $head_office_retail_allocation*(0.01*$retail_g1_allocation);
                } else if ($branch_id = 3) {//g2
                    $hoa = $head_office_retail_allocation*(0.01*$retail_g2_allocation);
                } else if ($branch_id = 6) {//qurum
                    $hoa = $head_office_retail_allocation*(0.01*$retail_qurum_allocation);
                } else {
                    $hoa = $head_office_retail_allocation;
                }
            }
        } else if ($unit == "lab") {
            $where .= " AND ent.unit IN ('central_store','head_office','lab')";
            $hoa = $head_office_lab_allocation;
            $csa = $central_store_lab_allocation;
        } else if ($unit == "lab_referral") {
            $where .= " AND ent.unit IN ('head_office','lab_referral')";
            $hoa = $head_office_lab_refferal_allocation;
            $csa = 0;
        } else if ($unit == "cryoviva") {
            $where .= " AND ent.unit IN ('head_office','cryoviva')";
            $hoa = $head_office_cryoviva_allocation;
            $csa = 0;
        } else if ($unit == "pharma") {
            $where .= " AND ent.unit IN ('central_store','head_office','pharma')";
            $hoa = $head_office_pharma_allocation;
            $csa = $central_store_pharma_allocation;
        } else if ($unit) {
            $where .= " AND ent.unit='$unit'";
            $hoa = 100;
            $csa = 100;
        }

        $head_office_salary_retail_allocation = get_setting('head_office_salary_retail_allocation');
        $head_office_salary_lab_allocation = get_setting('head_office_salary_lab_allocation');
        $head_office_salary_lab_refferal_allocation = get_setting('head_office_salary_lab_refferal_allocation');
        $head_office_salary_pharma_allocation = get_setting('head_office_salary_pharma_allocation');
        $head_office_salary_cryoviva_allocation = get_setting('head_office_salary_cryoviva_allocation');
        $retail_salary_g1_allocation  = get_setting("retail_salary_g1_allocation");
        $retail_salary_g2_allocation  = get_setting("retail_salary_g2_allocation");
        $retail_salary_qurum_allocation  = get_setting("retail_salary_qurum_allocation");

        $salary_expenses_acc = get_setting("salary_expenses");


        $hoa_s = 100;

        if ($unit == "retail") {
            $hoa_s = $head_office_salary_retail_allocation;
            if ($branch_id) {
                if($branch_id = 4) {//g1
                    $hoa_s = $head_office_salary_retail_allocation*(0.01*$retail_salary_g1_allocation);
                } else if ($branch_id = 3) {//g2
                    $hoa_s = $head_office_salary_retail_allocation*(0.01*$retail_salary_g2_allocation);
                } else if ($branch_id = 6) {//qurum
                    $hoa_s = $head_office_salary_retail_allocation*(0.01*$retail_salary_qurum_allocation);
                } else {
                    $hoa_s = $head_office_salary_retail_allocation;
                }
            }
        } else if ($unit == "lab") {
            $hoa_s = $head_office_salary_lab_allocation;
        } else if ($unit == "lab_referral") {
            $hoa_s = $head_office_salary_lab_refferal_allocation;
        } else if ($unit == "cryoviva") {
            $hoa_s = $head_office_salary_cryoviva_allocation;
        } else if ($unit == "pharma") {
            $hoa_s = $head_office_salary_pharma_allocation;
        } else if ($unit) {
            $hoa_s = 100;
        }

        $wholesale_staff = "
            WHEN ent.unit = 'lab' AND ent.account = $salary_expenses_acc AND ent.concerned_person = 31 THEN (ent.amount*0.01*69)
            WHEN ent.unit = 'lab' AND ent.account = $salary_expenses_acc AND ent.concerned_person = 7 THEN (ent.amount*0.01*62)
            WHEN ent.unit = 'lab' AND ent.account = $salary_expenses_acc AND ent.concerned_person = 6 THEN (ent.amount*0.01*62)
            WHEN ent.unit = 'lab' AND ent.account = $salary_expenses_acc AND ent.concerned_person = 16 THEN (ent.amount*0.01*69)
            

            WHEN ent.unit = 'pharma' AND ent.account = $salary_expenses_acc AND ent.concerned_person = 31 THEN (ent.amount*0.01*31)
            WHEN ent.unit = 'pharma' AND ent.account = $salary_expenses_acc AND ent.concerned_person = 7 THEN (ent.amount*0.01*28)
            WHEN ent.unit = 'pharma' AND ent.account = $salary_expenses_acc AND ent.concerned_person = 6 THEN (ent.amount*0.01*28)
            WHEN ent.unit = 'pharma' AND ent.account = $salary_expenses_acc AND ent.concerned_person = 16 THEN (ent.amount*0.01*31)
        

            WHEN ent.unit = 'lab_referral' AND ent.account = $salary_expenses_acc AND ent.concerned_person = 31 THEN (ent.amount*0.01*0)
            WHEN ent.unit = 'lab_referral' AND ent.account = $salary_expenses_acc AND ent.concerned_person = 7 THEN (ent.amount*0.01*10)
            WHEN ent.unit = 'lab_referral' AND ent.account = $salary_expenses_acc AND ent.concerned_person = 6 THEN (ent.amount*0.01*0)
            WHEN ent.unit = 'lab_referral' AND ent.account = $salary_expenses_acc AND ent.concerned_person = 16 THEN (ent.amount*0.01*0)
               
        ";


        $sql= "SELECT SUM(ent.amount) amount, ent.type type, trans.deleted, trans.date, ent.deleted FROM 
        (SELECT * FROM $enteries WHERE find_in_set ($enteries.account, '$list') AND $enteries.deleted = 0) ent  
        INNER JOIN
        (SELECT * FROM $transactions) trans
        ON (ent.trans_id = trans.id)
        WHERE trans.deleted = 0  AND ent.deleted = 0 $where GROUP BY ent.type";
        

        $data = $this->db->query($sql)->result();

        $query = $this->db->last_query();        

        foreach ($data as $element) {
            if ($element->type == 'dr') {
                $dr_balance = $element->amount;
            } else {
                $cr_balance = $element->amount;
            }
        }

        $total_type = '';
        $total = $dr_balance - $cr_balance;

        if ($total < 0) {
            $total = abs($total);
            $total_type = 'cr';
        } else if ($total > 0) {
            $total_type = 'dr';
        }

        $result = array(
            "cr_balance" => $cr_balance,
            "dr_balance" => $dr_balance,
            "total_type" => $total_type,
            "total" => $total,
            "data" => $data,
            "list" => $list,
            "query" => $query
        );
        return $result;

    }

    function get_balance__($acc){
        $enteries = $this->db->dbprefix('enteries');
        $transactions = $this->db->dbprefix('transactions');
        $dr_balance = 0;
        $cr_balance = 0; 

        $list = $this->get_children($acc)->list;

        if ($list) {
            $list .= "," . $acc;
        } else {
            $list .= $acc;
        }


        $sql= "SELECT ent.account, SUM(IF(ent.type = dr, ent.amount, 0)) dr_amount, SUM(IF(ent.type = cr, ent.amount, 0)) cr_amount, trans.deleted, trans.date, ent.deleted FROM 
        (SELECT * FROM $enteries WHERE find_in_set ($enteries.account, '$list') AND $enteries.deleted = 0) ent  
        INNER JOIN
        (SELECT * FROM $transactions) trans
        ON (ent.trans_id = trans.id)
        WHERE trans.deleted = 0  AND ent.deleted = 0 GROUP BY ent.account";

        $data = $this->db->query($sql)->result();

        $query = $this->db->last_query();        

        foreach ($data as $element) {
            if ($element->type == 'dr') {
                $dr_balance = $element->amount;
            } else {
                $cr_balance = $element->amount;
            }
        }

        $total_type = '';
        $total = $dr_balance - $cr_balance;

        /*if ($total < 0) {
            $total = abs($total);
            $total_type = 'cr';
        } else if ($total > 0) {
            $total_type = 'dr';
        }*/

        $result = array(
            "cr_balance" => $cr_balance,
            "dr_balance" => $dr_balance,
            "total_type" => $total_type,
            "total" => $total,
            "data" => $data,
            "list" => $list,
            "query" => $query
        );
        return $result;

    }


    function get_entries($acc, $start_date = "", $end_date = "", $branch_id = 0, $unit = "", $concerned_person = 0){
        $transactions = $this->db->dbprefix('transactions');
        $enteries = $this->db->dbprefix('enteries');
        if ($acc == 0) {
            $list = 0;
        } else {
            $list = $this->get_children($acc)->list;
        }
        
        if ($list) {
            $list .= "," . $acc;
        } else {
            $list = $acc;
        }

        $where = "";
        if ($start_date && $end_date) {
            $where .= " AND (  $transactions.date BETWEEN '$start_date' AND '$end_date') ";
        }

        if ($branch_id) {
            $where .= " AND $enteries.branch_id = $branch_id ";
        }

        if ($unit) {
            $where .= " AND $enteries.unit = '$unit' ";
        }

        if ($concerned_person) {
            $where .= " AND $enteries.concerned_person = $concerned_person ";
        }
      
        $sql = "SELECT *,    $transactions.id AS trans_id FROM   $transactions
                INNER JOIN $enteries
                ON ($enteries.trans_id =   $transactions.id)
                WHERE $enteries.deleted = 0 AND   $transactions.deleted = 0 AND $enteries.account in ($list) $where ORDER BY $transactions.date ASC";
        
        return $this->db->query($sql)->result();
    }

    function get_involved_transactions($acc_list) {
        $enteries = $this->db->dbprefix('enteries');
        $sql = "SELECT GROUP_CONCAT(trans_id) as list from $enteries where $enteries.deleted=0 and  $enteries.account in ($acc_list)";

        return $this->db->query($sql)->row();

    }


    function get_banking_accounts() {
        $parent_id= get_setting("banks_accounts_parent");
        $sql = "SELECT * FROM   $this->table
                WHERE (acc_parent = $parent_id) and deleted = 0";

        return $this->db->query($sql)->result();

    }

    function get_treasury_accounts() {
        $parent_id= get_setting("cash_on_hand_accounts_parent");
        $sql = "SELECT * FROM   $this->table
                WHERE (acc_parent =  $parent_id) and deleted = 0";
        return $this->db->query($sql)->result();
    }

    //the parent id
    function get_banking_accounts_id() {
        return get_setting("banks_accounts_parent");
    }
    function get_expense_accounts_id() {
        return get_setting("expenses_accounts_parent");
    }
    function get_client_accounts_id() {
        return get_setting("clients_accounts_parent");
    }
    function get_treasury_accounts_id() {
        return get_setting("cash_on_hand_accounts_parent");
    }


    function get_accounts_suggestion($keyword = "", $accounts = array(), $exclude_accounts = array()) {
        $where = "";
        if (is_array($accounts) && count($accounts)) {
            $accounts = join(",", $accounts);
            // $where .= "AND $this->table.acc_parent IN ($accounts)";
        }

        if (is_array($exclude_accounts) && count($exclude_accounts)) {
            $exclude_accounts = join(",", $exclude_accounts);
            // $where .= "AND $this->table.acc_parent NOT IN ($exclude_accounts)";
        }


        $sql = "SELECT id, acc_name, acc_code, acc_parent
                FROM $this->table
                WHERE $this->table.deleted = 0  AND ($this->table.acc_name LIKE '%$keyword%' OR $this->table.acc_code LIKE '%$keyword%') $where
                -- LIMIT 10
                ";

        return $this->db->query($sql)->result();
    }

    function get_children ($acc){
        $enteries = $this->db->dbprefix('enteries');
        //get the list of account and sub childs
        $sqlSetting  = "SET SESSION GROUP_CONCAT_MAX_LEN = 99999999;";

        $sql = "SELECT  GROUP_CONCAT(id) list                
                from    (select id,acc_parent from  $this->table
                         order by acc_parent, id) accounts_sorted,
                        (select @pv := '$acc') initialisation
                where   find_in_set(acc_parent, @pv)
                and     length(@pv := concat(@pv, ',', id))";

        $this->db->query($sqlSetting);

        return $this->db->query($sql)->row();
    }
    function get_children_l4 ($parent){
        // $sql = "SELECT parent.id,parent.acc_name,sub.acc_name from $this->table where acc_parent=$parent and  CHAR_LENGTH(acc_code)=5  ";
        $sql = "SELECT parent.acc_name,sub.id,sub.acc_name ,sub.acc_code from $this->table as parent INNER JOIN $this->table AS sub ON parent.id=sub.acc_parent WHERE parent.acc_parent=$parent AND sub.deleted =0 AND  CHAR_LENGTH(sub.acc_code)>=5";
        return $this->db->query($sql)->result();
    }

}
