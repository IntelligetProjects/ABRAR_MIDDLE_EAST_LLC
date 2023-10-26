<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Accounts extends MY_Controller {

    function __construct() {
        parent::__construct();
    }

    
    public function index() {
        $index_data = array();
        $this->template->rander("Accounts/coa",$index_data); 
    }

    public function get_accounts($acc= 0) {
        $parent_id = $acc ? $acc : 'root';
        $options = array('parent_id'=> $parent_id);
        
        $accounts = $this->Accounts_model->get_details($options)->result(); 
        // $accounts = $this->Accounts_model->get_all_where(array("acc_parent" => $acc))->result();
// var_dump($accounts);die();
        /*$i = 1;*/
        $tree = array();
        foreach ($accounts as $account) {           

            $editButton = '<li role="presentation">' . modal_anchor(get_uri("Accounts/modal_form"), "<i class='editBtn fa fa-pencil'></i> ". lang('edit'), array("title" => lang('edit'), "data-post-id" => $account->id)) . "</li>";

            $deleteButton = '<li role="presentation">' . js_anchor("<i class='fa fa-times fa-fw'></i> " . lang('delete'), array('title' => lang('delete'), "class" => "deleteBtn", "data-id" => $account->id)) . '</li>'; 

            $viewButton = '<li role="presentation">' . js_anchor("<i class='fa fa-adjust fa-fw'></i> " . lang('view'), array('title' => lang('view'), "class" => "viewBtn", "data-id" => $account->id)) . '</li>';

            $viewButton2 = '<li role="presentation">' . js_anchor("<i class='btn btn-default fa fa-adjust'></i> ", array('title' => lang('view'), "class" => "viewBtn", "data-id" => $account->id)) . '</li>';  

            $period_array = $this->financial_period();

            $start_date = $period_array["start_date"];
            $end_date = $period_array["end_date"];

            $sub_accounts_revenue = $this->Accounts_model->get_children(5)->list;
            $sub_accounts_expense = $this->Accounts_model->get_children(4)->list;
            $sub_accounts_off_bs = $this->Accounts_model->get_children(6)->list;
            $sub_accounts2 = $sub_accounts_expense.",".$sub_accounts_off_bs.",".$sub_accounts_revenue.",4,6,5";
            $sub_accounts2 = explode(',',  $sub_accounts2); 

            if (in_array($account->id, $sub_accounts2)) {
            $bal = $this->Accounts_model->get_balance($account->id, $start_date, $end_date);
            } else {
            $bal = $this->Accounts_model->get_balance($account->id); 
            }

            //$bal = $this->Accounts_model->get_balance($account->id, $start_date, $end_date);

            $balance = number_format($bal['total'], 3,".",",");
            $balance_type = lang($bal['total_type']);

            $child_count = $this->Accounts_model->get_all_where(array("deleted"=> 0, "acc_parent" => $account->id))->num_rows();
            $is_parent = $child_count == 0 ? 0 : 1;
            $entries_count = $this->Enteries_model->get_all_where(array("deleted"=> 0, "account" => $account->id))->num_rows();
            $has_entries = $entries_count == 0 ? 0 : 1;

            if ($account->is_primary) {
              $buttons = "<div style='display:inline; margin-left:15px; float:right;'>" . $viewButton2. "</div>";
           
            } else if ($has_entries || $is_parent) {
                $buttons = "<div style='display:inline; margin-left:15px; float:right;'>" .
                '<span class="dropdown inline-block">
                    <button class="btn btn-default dropdown-toggle  mt0 mb0" style="padding: 1px 9px !important;border-color: #e2e5e8 !important;" type="button" data-toggle="dropdown" aria-expanded="true">
                        <i class="fa fa-cogs"></i>&nbsp;
                    </button>
                    <ul class="dropdown-menu pull-right" role="menu">'.$editButton . $deleteButton. $viewButton .'</ul>
                </span>'
                . "</div>";
            } else {
                $buttons = "<div style='display:inline; margin-left:15px; float:right;'>" .
                '<span class="dropdown inline-block">
                    <button class="btn btn-default dropdown-toggle  mt0 mb0" style="padding: 1px 9px !important;border-color: #e2e5e8 !important;" type="button" data-toggle="dropdown" aria-expanded="true">
                        <i class="fa fa-cogs"></i>&nbsp;
                    </button>
                    <ul class="dropdown-menu pull-right" role="menu">' . $editButton . $deleteButton . $viewButton . '</ul>
                </span>'
                . "</div>";
            }
            /// for adjusting acc codes.
            /*$parent_code = $this->Accounts_model->get_one($parent_id)->acc_code;
            $code_count = $i++;
            if(!empty($parent_id) && $parent_id != "root"){
               $account->acc_code = $parent_code."-".$code_count;
               $dataa = array("acc_code" => $parent_code."-".$code_count);
               $this->Accounts_model->save($dataa, $account->id); 
            }*/
            
            $tree[] = array(
                'id' => $account->id,
                'parent' =>  $account->acc_parent ? $account->acc_parent:'#',
                'text' => (!empty($account->acc_code) ? '<b>['.$account->acc_code.']</b> - ' : '') . $account->acc_name . " (" . $balance . " " . $balance_type . ")" . $buttons,
                "children" => true
            );
        }

         echo json_encode($tree);
    }

    public function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');

        $view_data['model_info'] = $this->Accounts_model->get_one($id);

        $child_count = $this->Accounts_model->get_all_where(array("deleted"=> 0, "acc_parent" => $id))->num_rows();
        $is_parent = $child_count == 0 ? 0 : 1;
        $entries_count = $this->Enteries_model->get_all_where(array("deleted"=> 0, "account" => $id))->num_rows();
        $has_entries = $entries_count == 0 ? 0 : 1;

        $view_data["is_parent"] = $is_parent;
        $view_data["has_entries"] = $has_entries;

        $list = array(1,2,3,4,5,6);

        $childs = $this->Accounts_model->get_direct_childern(array("accounts" => $list));
        $childs = explode(",", $childs);


        // list where relating with module record is not allowed
        $list = array_merge($childs, $list);

        $list = array_map('strval', $list);

        $view_data["list"] = $list;


        $this->load->view('Accounts/modal_form', $view_data);
    }

    function save() {

        validate_submitted_data(array(
            "id" => "numeric",
            "account_parent" => "required|numeric",
            "account_name" => "required",
        ));

        $id = $this->input->post('id');

        //check duplicate company name, if found then show an error message
        if ($this->Accounts_model->get_all_where(array("deleted"=> 0, "acc_parent" => $this->input->post('account_parent'), "module" => $this->input->post('module'), "module_id"=> $this->input->post('module_id')))->num_rows() != 0 && $this->input->post('module_id') && $this->input->post('module')) {
            echo json_encode(array("success" => false, 'message' => lang("account_already_exists_for_this_partner")));
            exit();
        }

        $data = array(
            "acc_name" => $this->input->post('account_name'),
            "acc_code" => $this->input->post('account_code'),
            "acc_parent" => $this->input->post('account_parent'),
            "acc_description" => $this->input->post('description') ? $this->input->post('description') : "" ,
            "is_inactive" => $this->input->post('is_inactive') ? $this->input->post('is_inactive') : 0 ,
            "module" => $this->input->post('module'),
            "module_id" => $this->input->post('module_id'),
            //apply a filter or check in the chart of accounts.
        );

        $account_id = $this->Accounts_model->save($data, $id);
        if ($account_id) {
            echo json_encode(array("success" => true, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function delete($id) {

        // $child_count = $this->Accounts_model->get_all_where(array("deleted" => 0, "acc_parent" => $id))->num_rows();
        // $is_parent = $child_count == 0 ? 0 : 1;

        // $entries_count = $this->Enteries_model->get_all_where(array("deleted" => 0, "account" => $id))->num_rows();
        // $has_entries = $entries_count == 0 ? 0 : 1;

        // if (!$has_entries && !$is_parent) {

            $delete_account = $this->Accounts_model->delete($id);

        // } else {

        //     $delete_account = 0;

        // }
        
        if ($delete_account) {

            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));

        } else {

            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));

        }
    }

    public function view($acc=0) {

        $period_array = $this->financial_period();

        $view_data['start_date'] = $period_array["start_date"];
        $view_data['end_date'] = $period_array["end_date"];

        $view_data['account'] = $acc;
        $options['id'] = $acc;
        $account = $this->Accounts_model->get_one($acc);
        if(!empty($account->acc_code)) {
            $view_data['account_name'] =  " - [".$account->acc_code.'] - '. $account->acc_name;
        } else {
            $view_data['account_name'] = " - ".$account->acc_name;
        }

        if(!empty($account->acc_description)) {
            $view_data['account_description'] =  " - ".$account->acc_description;
        } else {
            $view_data['account_description'] = "";
        }

/*        $view_data["units_dropdown"] = $this->_get_units_dropdown();
        $view_data["branches_dropdown"] = $this->_get_branches_dropdown();*/
        $view_data['concerned_persons_dropdown'] = $this->_get_team_members_dropdown();

        $this->template->rander("Accounts/view", $view_data);
    }

    //get team members dropdown
    private function _get_team_members_dropdown() {
        $team_members = $this->Users_model->get_all_where(array("deleted" => 0, "user_type" => "staff"), 0, 0, "first_name")->result();

        $members_dropdown = array(array("id" => "", "text" => "- " . lang("member") . " -"));
        foreach ($team_members as $team_member) {
            $members_dropdown[] = array("id" => $team_member->id, "text" => $team_member->first_name . " " . $team_member->last_name);
        }

        return json_encode($members_dropdown);
    }

    /*//get categories dropdown
    private function _get_units_dropdown() {

        $categories_dropdown = array(array("id" => "", "text" => "- " . lang("business_unit") . " -"));
        $categories = array("retail", "pharma", "lab", "lab_referral", "cryoviva","shared","head_office","central_store",);
        foreach ($categories as $category) {
            $categories_dropdown[] = array("id" => $category, "text" => lang($category));
        }

        return json_encode($categories_dropdown);
    }

    //get categories dropdown
    private function _get_branches_dropdown() {
        $categories = $this->Branches_model->get_all_where(array("deleted" => 0), 0, 0, "title")->result();

        $categories_dropdown = array(array("id" => "", "text" => "- " . lang("branch") . " -"));
        foreach ($categories as $category) {
            $categories_dropdown[] = array("id" => $category->id, "text" => $category->title);
        }

        return json_encode($categories_dropdown);
    }*/

    public function get_entries($acc=0) {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $account = $this->input->post('account');
        $unit = $this->input->post('unit');
        $branch_id = $this->input->post('branch_id');
        $concerned_person = $this->input->post('concerned_person');

        if ($account) {
            $acc = $account;
        }

        $list_data = $this->Accounts_model->get_entries($acc, $start_date, $end_date, $branch_id, $unit, $concerned_person); 
        /*var_dump($list_data);
        exit;*/
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

        if ($start_date && $acc != 0 && !in_array($acc, $sub_accounts2)) {

            $start_date_minus_a_day = date("Y-m-d", strtotime($start_date. ' - 1 days'));

            $bal = $this->Accounts_model->get_balance($acc, "", $start_date_minus_a_day);
            //var_dump($bal);

            $open_balance = new stdClass();
            $open_balance->id = 0;
            $open_balance->date = $start_date_minus_a_day;
            $open_balance->type = $bal["total_type"];
            $open_balance->reference = "Opening Balance";
            $open_balance->trans_id = 0;
            $open_balance->account = $acc;
            $open_balance->amount = $bal["total"];
            $open_balance->narration = "Opening Balance";
            $open_balance->branch_id = 0;
            $open_balance->sub_account_id = 0;
            $open_balance->sub_account_type = "";

            if ($bal["total_type"] === 'cr') {
                if(in_array($acc, $sub_accounts)) {
                    $bal["total"] = $bal["total"];
                } else {
                    $bal["total"] = ($bal["total"]*-1);
                }
            }

            $result[] = $this->_make_entry_row($open_balance, $bal["total"]); 

            $balance = $bal["total"];

        }

        foreach ($list_data as $data) {
            if ($data->type === 'cr') {
                if(in_array($acc, $sub_accounts)) {
                    $balance += $data->amount;
                }
                else {
                    $balance -= $data->amount;
                }
                
            }
            else {
                if(in_array($acc, $sub_accounts)) {
                    $balance -= $data->amount;
                }
                else {
                    $balance += $data->amount;
                }
            }           
            
            $result[] = $this->_make_entry_row($data, $balance);
        }

        echo json_encode(array("data" => $result));
    }

    private function _make_entry_row($data, $balance) {
        $debit = 0;
        $credit = 0;
        if ($data->type === 'cr') {
            $credit = $data->amount;
        } else {
            $debit = $data->amount;
        }

        $account = $this->Accounts_model->get_one($data->account);

        if(!empty($account->acc_code)) {
            $account_code =  $account->acc_code;
        } else {
            $account_code = '';
        }
        $parent = $this->Accounts_model->get_one($account->acc_parent);
        $account_parent = $parent->acc_name ? $parent->acc_name . " => " : "";

        /*if(isset($data->unit)) {
            $unit =  lang($data->unit);
        } else {
            $unit = '';
        }

        if(!empty($data->branch_id)) {
            $branch =  $this->Branches_model->get_one($data->branch_id)->title;
        } else {
            $branch = '';
        }*/

        if(!empty($data->concerned_person)) {
            $user = $this->Users_model->get_one($data->concerned_person);
            $concerned_person =  $user->first_name." ".$user->last_name;
        } else {
            $concerned_person = '';
        }

        $row = array(
            anchor(get_uri("transactions/view/$data->trans_id"), $data->date),
            $account_code,
            anchor(get_uri("accounts/view/" . $account->id), /*$account_parent.*/$account->acc_name),
            $data->narration,
            /*$unit,
            $branch,*/
            $concerned_person,
            empty($data->reference) ? "" : $data->reference,
            number_format($debit, 3,".",","),
            number_format($credit, 3,".",","),
            number_format($balance, 3,".",","),
        );

        return $row; 
    }

  

    public function get_accounts_suggestion_ledger() {
        $key = $_REQUEST["q"];
        $suggestion = array();

        $accounts = $this->Accounts_model->get_accounts_suggestion($key);

        foreach ($accounts as $account) {
            
            if(!empty($account->acc_code)) {
                $account_code =  "[".$account->acc_code.'] - ';
            } else {
                $account_code = '';
            }
            $parent = $this->Accounts_model->get_one($account->acc_parent);
            $account_parent = $parent->acc_name ? $parent->acc_name . " => " : "";

            
            $suggestion[] = array("id" => $account->id, "text" => $account_parent . $account_code . $account->acc_name);
        }        

        echo json_encode($suggestion);
    }

    // new codes //

    /* account code suggestion */

    public function get_account_code_suggestion($account_parent) {

        $parent_code = $this->Accounts_model->get_one($account_parent)->acc_code;

        $code_count = count($this->Accounts_model->get_all_where(array("deleted" => 0, "acc_parent" => $account_parent))->result());

        if (!empty($parent_code)) {
            $code_count = $code_count + 1;
            $acode = $parent_code."-".$code_count;
        } else {
            $acode = '';
        }

        if(!empty($code)) {
            $code = $code;  
        } else {
            $code = $acode;
        }

        echo json_encode(($code));
        //return /*json_encode*/($code);
    }

    // public function get_form_accounts_suggestion() {
    //     $key = $_REQUEST["q"];
    //     $suggestion = array();

        

    //     $accounts = $this->Accounts_model->get_accounts_suggestion($key, array(), array(0));
    //     // $accounts = $this->Accounts_model->get_all_where("acc_name LIKE %$$key% ")->result();
    //     $x=0;
    //     foreach ($accounts as $account) {
    //         $x++;
    //         if(!empty($account->acc_code)) {
    //             $account_code =  "[".$account->acc_code.'] - ';
    //         } else {
    //             $account_code = '';
    //         }

    //         $entries_count = $this->Enteries_model->get_all_where(array("deleted"=> 0, "account" => $account->id))->num_rows();
    //         $has_entries = $entries_count == 0 ? 0 : 1;

    //         $bal = $this->Accounts_model->get_balance($account->id);
    //         $balance = number_format($bal['total'], 3,".",",");
    //         $balance_type = $bal['total_type'];

    //         if (!$has_entries) {
    //             if($x==1){
    //                 $account_codex ='[0] - ';
    //                 $acc_namex=' - ';
    //                 $suggestion[] = array("id" => 0, "text" => $account_codex . $acc_namex);
    //             }
    //             $suggestion[] = array("id" => $account->id, "text" => $account_code . $account->acc_name. " (" . $balance . " " . $balance_type . ")");
    //         }
            
    //     }        

    //     echo json_encode($suggestion);
    // }

    public function get_form_accounts_suggestion() {
        $key = $_REQUEST["q"];
        $suggestion = array();

        

        $accounts = $this->Accounts_model->get_accounts_suggestion($key, array(), array(0));
        // $accounts = $this->Accounts_model->get_all_accounts();
        // var_dump($accounts);
        // $accounts = $this->Accounts_model->get_all_where("acc_name LIKE %$$key% ")->result();
        foreach ($accounts as $account) {
            // echo $account->acc_name.' '.$account->acc_code.'->'.strlen($account->acc_code).' | ';
            // if(strlen($account->acc_code)==5){
                
            
            if(!empty($account->acc_code)) {
                $account_code =  "[".$account->acc_code.'] - ';
            } else {
                $account_code = '';
            }

            $entries_count = $this->Enteries_model->get_all_where(array("deleted"=> 0, "account" => $account->id))->num_rows();
            $has_entries = $entries_count == 0 ? 0 : 1;

            $bal = $this->Accounts_model->get_balance($account->id);
            $balance = number_format($bal['total'], 3,".",",");
            $balance_type = $bal['total_type'];

            // if (!$has_entries) {
                $suggestion[] = array("id" => $account->id, "text" => $account_code . $account->acc_name. " (" . $balance . " " . $balance_type . ")");
            // }
            
        // }        
        }        

        echo json_encode($suggestion);
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

    /// helper code

    public function accounts_list() {
        $view_data = array();
        $this->template->rander("Accounts/accounts_list", $view_data);
    }

    function accounts_list_data() {

        $list_data = $this->Accounts_model->get_all_where(array("deleted" => 0))->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->accounts_list_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

        //prepare an item category list row
    private function accounts_list_make_row($data) {

        $parent = $this->Accounts_model->get_one($data->acc_parent);

        $result = array($data->id,
            $data->acc_code,
            anchor(get_uri("accounts/view/" . $data->id), $data->acc_name),
            js_anchor("<i class='fa fa-eye fa-fw'></i> View", array('title' => lang('view'), "class" => "edit", "data-id" => $data->id,"data-action-url" => get_uri("accounts/view_balance"), "data-action" => "view")),
            $parent->acc_code." ".$parent->acc_name,
        );

        return $result;
    }

    function view_balance() {

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        $bal = $this->Accounts_model->get_balance($id); 
        $balance = number_format($bal['total'], 3,".",",");
        $balance_type = strtoupper($bal['total_type']);

        echo json_encode(array("success" => true, "balance" => $balance." ".$balance_type,'message' => ('success')));

    }

}