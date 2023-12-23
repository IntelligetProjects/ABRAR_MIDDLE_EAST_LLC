<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Transactions extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (strcasecmp($this->db->dbprefix, 'Integrated_Banners_') == 0) {
            $this->load->model('Pending_transactions_model');
        }
    }

    private function _get_cost_centers_dropdown() {
        $cost_centers = $this->Cost_centers_model->get_all_where(array("deleted" => 0), 0, 0, "name")->result();

        $cost_centers_dropdown = array(array("id" => "", "text" => "- " . lang("cost_center") . " -"));
        foreach ($cost_centers as $cost_center) {
            $cost_centers_dropdown[] = array("id" => $cost_center->id, "text" => $cost_center->name);
        }

        return json_encode($cost_centers_dropdown);
    }

    function index($pending = "")
    {
        $view_data = array();
        $start_financial = date("d-m", strtotime(get_setting("financial_year_end")));
        $current_date = get_my_local_time("d-m");
        $current_year = get_my_local_time("Y");

        /*getting the current financial period */

        if ($current_date > $start_financial) {
            $start_date = $start_financial . "-" . ($current_year);
            $end_date = get_my_local_time("d-m-Y");
        } else {
            $start_date = $start_financial . "-" . ($current_year - 1);
            $end_date = get_my_local_time("d-m-Y");
        }

        if (strcasecmp($this->db->dbprefix, 'Integrated_Banners_') == 0) {
            $status = array(
                array("id" => 0, "text" => lang("pending")),
                array("id" => 1, "text" => lang("approved"))
            );
            $view_data["status"] = $status;
        }

        $view_data["start_date"] = date("Y-m-d", strtotime($start_date));
        $view_data["end_date"] = date("Y-m-d", strtotime($end_date));
        $view_data["source"] =  array(array("id" => "", "text" => "- " . lang("source") . " -"), array("id" => 1, "text" => "Manual"), array("id" => 2, "text" => "Auto"));
        $view_data["pending"] = $pending;


        $view_data['cost_centers_dropdown'] = $this->_get_cost_centers_dropdown();

        // var_dump($view_data);die();
        $this->template->rander("Transactions/index", $view_data);
    }

    function list_data()
    {
        $options = array();
        $pending = $this->input->post('pending');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $manual_auto = $pending == "pending" ? 1 : $this->input->post('manual_auto');

        $options = array("start_date" => $start_date,
            "end_date" => $end_date,
            "manual_auto" => $manual_auto,
            "cost_center_id" => $this->input->post('cost_center_id')
        );
        $list_data = $this->Transactions_model->get_details($options)->result();

        $result = array();
        foreach ($list_data as $data) {
            if ($pending != "pending") {
                $result[] = $this->_make_row($data);
            } elseif ($pending == "pending" && (round($data->debit, 3) != round($data->credit, 3))) {
                $result[] = $this->_make_row($data);
            }
        }

        echo json_encode(array("data" => $result));
    }

    private function _make_row($data)
    {
        set_row_data_currency_rate($data->currency_rate_at_creation); //SET CURRENCY RATE

        $cr_amount = round($data->credit, 3);
        $dr_amount = round($data->debit, 3);
        $dr_cr = round(($dr_amount - $cr_amount), 3);

        if ($data->is_manual == 1) {
            $status = "manual";
            $transaction_label_class = "label-warning";
            $auto_man = "<span class='mt0 label $transaction_label_class large'>" . lang($status) . "</span>";
        } else {
            $status = "automatic";
            $transaction_label_class = "label-success";
            $auto_man = "";
        }

        $valid = 0;

        if (round($data->debit, 3) == round($data->credit, 3)) {
            $valid = 1;
        }

        if ($valid == 1) {
            $status_ = "valid";
            $transaction_label_class = "label-success";
        } else {
            $status_ = "not_valid";
            $transaction_label_class = "label-warning";
        }

        $transaction_status = "<span class='mt0 label $transaction_label_class large'>" . lang($status_) . "</span>";


        $row_data = array(
            $data->id,
            $data->date,
            format_to_date($data->date, false),
            lang($data->type),
            $transaction_status . " " . $auto_man,
            "<b>" . to_currency($dr_amount, " ") . "</b>",
        );

        //display project and approve status for integratted banners 
        if (strcasecmp($this->db->dbprefix, 'Integrated_Banners_') == 0) {
            $row_data[] = $data->project_title ? anchor(get_uri("projects/view/" . $data->project_id), $data->project_title) : "-";
            $approval_stat = "approved";
            if(isset($data->approved)){
                $approval_status_name = ($data->approved ? lang("pending"): lang("approve"));
                if ($this->can_approve() && $data->is_manual && !$data->approved) {
                    $approval_stat = js_anchor($approval_status_name, array('title' => "status", "class" => "btn btn-default", "data-id" => $data->id, "data-value" => 1, "data-act" => "update-status"));
                } else {
                    $approval_stat = $data->is_manual ? $approval_status_name : "-";
                }
            }
            $row_data[] = $approval_stat;
        }

        $rowe = array();

        if (strcasecmp($this->db->dbprefix, 'Integrated_Banners_') == 0) {
            if ($this->login_user->role_id == 1 || $this->login_user->is_admin) {
                if(isset($data->approved)){
                    $rowe[] = anchor(get_uri("pending_transactions/view/$data->id"), "<i class='fa fa-sign-in'></i>", array("class" => "edit", "title" => "Enteries"));
                    $rowe[] = modal_anchor(get_uri("transactions/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit'), "data-post-id" => $data->id));
                    $rowe[] = js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("pending_transactions/delete"), "data-action" => "delete"));
                }else{
                    $rowe[] = anchor(get_uri("transactions/view/$data->id"), "<i class='fa fa-sign-in'></i>", array("class" => "edit", "title" => "Enteries"));
                    $rowe[] = js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("transactions/delete"), "data-action" => "delete"));
                }
            }
        } else {
            $rowe[] = anchor(get_uri("transactions/view/$data->id"), "<i class='fa fa-sign-in'></i>", array("class" => "edit", "title" => "Enteries"));
            $rowe[] = modal_anchor(get_uri("transactions/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit'), "data-post-id" => $data->id));
            if ($data->is_manual == 1 || $this->login_user->is_admin) {
                $rowe[] =
                    js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("transactions/delete"), "data-action" => "delete"));
            }
        }

        $row_data[] = $rowe;

        unset_row_data_currency_rate(); //UNSET CURRENCY RATE
        return $row_data;
    }

    private function _row_data($id)
    {
        $options = array("id" => $id);
        $data = $this->Transactions_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    function delete()
    {   
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        $info = $this->Transactions_model->get_one($id);

        if ($this->input->post('undo')) {
            if ($this->Transactions_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Transactions_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    private function _get_validity_label($transaction_id, $return_html = true)
    {
        $valid = $this->check_valid_status($transaction_id);

        if ($valid == 1) {
            $status = "valid";
            $transaction_label_class = "label-success";
        } else {
            $status = "not_valid";
            $transaction_label_class = "label-warning";
        }

        $transaction_status = "<span class='mt0 label $transaction_label_class large'>" . lang($status) . "</span>";

        if ($return_html) {
            return $transaction_status;
        } else {
            return $status;
        }
    }

    //get team members dropdown
    private function _get_team_members_dropdown()
    {
        $team_members = $this->Users_model->get_all_where(array("deleted" => 0, "user_type" => "staff"), 0, 0, "first_name")->result();

        $members_dropdown = array(array("id" => "", "text" => "- " . lang("member") . " -"));
        foreach ($team_members as $team_member) {
            $members_dropdown[] = array("id" => $team_member->id, "text" => $team_member->first_name . " " . $team_member->last_name);
        }

        return json_encode($members_dropdown);
    }

    private function _get_projects_dropdown()
    {
        $items = array("" => "-") + $this->Projects_model->get_dropdown_list(array("title"), "id");
        return $items;
    }

    function modal_form()
    {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data["types_dropdown"] = array("journal_entry" => lang("journal_entry"), "payment_voucher" => lang("payment_voucher"), "receipt_voucher" => lang("receipt_voucher"));

        //get project dropdown for integratted banners client
        if (strcasecmp($this->db->dbprefix, 'Integrated_Banners_') == 0) {
            $view_data["projects_dropdwon"] = $this->_get_projects_dropdown();
        }

        $view_data['model_info'] = $this->Transactions_model->get_one($this->input->post('id'));
        
        if (strcasecmp($this->db->dbprefix, 'Integrated_Banners_') == 0) {
            $view_data['model_info'] = $this->Pending_transactions_model->get_one($this->input->post('id'));
        }

        $this->load->view('Transactions/modal_form', $view_data);
    }

    function save()
    {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');

        $transaction_data = array(
            "date" => $this->input->post('transaction_date'),
            "type" => $this->input->post('type'),
            "reference" => $this->input->post('reference'),
            "is_manual" => 1,
        );

        //save project for integratted banner client
        if (strcasecmp($this->db->dbprefix, 'Integrated_Banners_') == 0) {
            $transaction_data["project_id"] = $this->input->post('project_id');
            $transaction_id = $this->Pending_transactions_model->save($transaction_data, $id);
        }else{
            $transaction_id = $this->Transactions_model->save($transaction_data, $id);
        }
        
        if ($transaction_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($transaction_id), 'id' => $transaction_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function view($transaction_id)
    {
        if ($transaction_id) {
            $options = array("id" => $transaction_id);
            $transactions_info = $this->Transactions_model->get_details_view($options)->row();
            set_row_data_currency_rate($transactions_info->currency_rate_at_creation); //SET CURRENCY RATE
            // echo $transaction_id;
            // var_dump($transactions_info); die('hi');
            $view_data['transactions_info'] = $transactions_info;
            $view_data['transaction_valid_label'] = $this->_get_validity_label($transactions_info->id);
            $view_data['type_dropdown'] = array("dr" => "Dr", "cr" => "Cr");
            if ($transactions_info->type == "payment_voucher") {
                $view_data['type_dropdown'] = array("dr" => "Dr", "cr" => "Cr");
            } else if ($transactions_info->type == "receipt_voucher") {
                $view_data['type_dropdown'] = array("cr" => "Cr", "dr" => "Dr");
            }
            /*$view_data['branches_dropdown'] = array("" => lang("branch")) + $this->Branches_model->get_dropdown_list(array("title"));*/

            $team_members = $this->Users_model->get_all_where(array("deleted" => 0, "user_type" => "staff"))->result();
            $members_dropdown = array();

            foreach ($team_members as $team_member) {
                $members_dropdown[$team_member->id] = $team_member->first_name . " " . $team_member->last_name;
            }

            $view_data['concerned_persons_dropdown'] = array("0" => lang("concerned_person")) + $members_dropdown;

            $bank_accounts_dropdown = array("" => lang("bank_cash_account"));
            $accounts_dropdown = array("" => "-");
            $accounts = $this->Accounts_model->get_all_where(array("deleted" => 0, "where_in" => array("acc_parent" => array(8, 87))))->result();
            foreach ($accounts as $account) {
                $cash_accs = explode(",", get_setting("cash_accounts"));
                $bal = $this->Accounts_model->get_balance($account->id);
                $balance = number_format($bal['total'], 3, ".", ",");
                //if (!in_array($account->id, $cash_accs)) {
                $bank_accounts_dropdown[$account->id] = $account->acc_name . " " . "(" . $balance . ")";
                //}
            }

            $view_data['banks'] = $bank_accounts_dropdown;
            /*$view_data['units'] = array("" => lang("business_unit")) + array("retail" => lang("retail"), "lab" => lang("lab"), "pharma" => lang("pharma"), "lab_referral" => lang("lab_referral"), "cryoviva" => lang("cryoviva"), "shared" => lang("manual_share"), "head_office" => lang("head_office"), "central_store" => lang("central_store"));*/
            $this->template->rander("Transactions/view", $view_data);
        } else {
            $this->index();
        }
    }

    function enteries_list_data($transaction_id)
    {
        $list_data = $this->Enteries_model->get_details(array("trans_id" => $transaction_id))->result();
        $result = array();
        $sr = 0;
        foreach ($list_data as $data) {
            $sr++;
            $result[] = $this->_make_entry_row($data, $sr);
        }
        echo json_encode(array("data" => $result));
    }

    function entry_modal_form()
    {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Enteries_model->get_one($this->input->post('id'));
        $view_data['transaction_info'] = $this->Transactions_model->get_one($view_data['model_info']->trans_id);
        $view_data['type_dropdown'] = array("dr" => "Dr", "cr" => "Cr");
        /*$view_data['branches_dropdown'] = array("" => "-") + $this->Branches_model->get_dropdown_list(array("title"));
        $view_data['units'] = array("" => lang("business_unit")) + array("retail" => lang("retail"), "lab" => lang("lab"), "pharma" => lang("pharma"), "lab_referral" => lang("lab_referral"), "cryoviva" => lang("cryoviva"), "shared" => lang("manual_share"), "head_office" => lang("head_office"), "central_store" => lang("central_store"));*/


        $team_members = $this->Users_model->get_all_where(array("deleted" => 0, "user_type" => "staff"))->result();
        $members_dropdown = array();

        foreach ($team_members as $team_member) {
            $members_dropdown[$team_member->id] = $team_member->first_name . " " . $team_member->last_name;
        }

        $view_data['concerned_persons_dropdown'] = array("0" => lang("concerned_person")) + $members_dropdown;

        $this->load->view('Transactions/enteries_modal_form', $view_data);
    }

    private function _make_entry_row($data, $sr)
    {
        $credit = 0;
        $debit = 0;

        if ($data->type == 'cr') {
            $credit = $data->amount;
        } else {
            $debit = $data->amount;
        }

        if (!empty($data->acc_code)) {
            $account_code =  "[" . $data->acc_code . '] - ';
        } else {
            $account_code = '';
        }
        $parent = $this->Accounts_model->get_one($data->acc_parent);
        $account_parent = $parent->acc_name ? $parent->acc_name : "";

        /*if($data->unit) {
            $branch =  "</br>".lang("unit").": ".lang($data->unit);
        } else {
            $branch = '';
        }

        if($data->branch_id) {
            $branch .=  "</br>".lang("branch").": ".$this->Branches_model->get_one($data->branch_id)->title;
        } else {
            $branch .= '';
        }*/

        if ($data->reference) {
            $branch =  "</br>" . lang("reference") . ": " . lang($data->reference);
        } else {
            $branch = '';
        }

        $transactions_info = $this->Transactions_model->get_one($data->trans_id);

        $row = array(
            $sr,
            $account_parent,
            anchor(get_uri("accounts/view/" . $data->account), $data->acc_name),
            $data->narration . $branch,
            // number_format($debit, 3, ".", ","),
            // number_format($credit, 3, ".", ","),
            to_currency($debit),
            to_currency($credit),
        );


        if ($transactions_info->type == "payment_voucher" && $data->type == "cr") {
            $row[] = "";
        } else {


            if ($this->db->dbprefix == 'Integrated_Banners_') {
                // if ($this->login_user->role_id == 1 || $this->login_user->is_admin) {
                    // $row[] = modal_anchor(get_uri("transactions/entry_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit'), "data-post-id" => $data->entry_id));
                //         . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->entry_id, "data-action-url" => get_uri("transactions/delete_entry"), "data-action" => "delete"));
                // } else {
                    $row[] = "";
                // }
            } else {
                $row[] = modal_anchor(get_uri("transactions/entry_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit'), "data-post-id" => $data->entry_id))
                    . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->entry_id, "data-action-url" => get_uri("transactions/delete_entry"), "data-action" => "delete"));
            }
        }

        return $row;
    }


    function check_valid_status($transaction_id = 0)
    {
        $valid = 0;
        $transaction = $this->Transactions_model->get_details(array("id" => $transaction_id))->row();

        if (round($transaction->debit, 3) == round($transaction->credit, 3)) {
            $valid = 1;
        }

        return $valid;
    }

    function save_bank_account($transaction_id, $account_id)
    {
        $entries_count = $this->Enteries_model->get_all_where(array("deleted" => 0, "trans_id" => $transaction_id))->num_rows();
        $has_entries = $entries_count == 0 ? 0 : 1;
        if (!$has_entries) {
            $data = array("bank_cash" => $account_id);
            $transaction = $this->Transactions_model->save($data, $transaction_id);
            if ($transaction) {
                echo lang('bank_account_is_updated');
            } else {
                echo lang('error_occurred');
            }
        } else {
            echo lang('account_can_not_be_changed');
        }
    }

    function update_valid_status($transaction_id)
    {
        $options = array("id" => $transaction_id);
        $transactions_info = $this->Transactions_model->get_details($options)->row();
        $view['transactions_info'] = $transactions_info;

        $view['transaction_valid_label'] = $this->_get_validity_label($transaction_id);
        $this->load->view('Transactions/valid_status', $view);
    }


    private function _row_entry_data($entry_id)
    {
        $options = array("id" => $entry_id);
        $entry_info = $this->Enteries_model->get_details($options)->row();

        $srs = array("trans_id" => $entry_info->trans_id);
        $entry_infos = $this->Enteries_model->get_details($srs)->result();
        $entry_count = count($entry_infos);
        return $this->_make_entry_row($entry_info, $entry_count);
    }

    function enteries_save()
    {
        if(strcasecmp($this->db->dbprefix, 'Integrated_Banners_') == 0){
            return;//adding new entry to saved transaction is forbidden for integrated banners client
        }
        
        validate_submitted_data(array(
            "id" => "numeric",
            "transaction_id" => "required|numeric",
            "amount" => "required|numeric",
            "type" => "required",
            "account" => "required|numeric"
        ));

        $transaction_id = $this->input->post('transaction_id');
        $id = $this->input->post('id');

        $enteries_data = array(
            "trans_id" => $transaction_id,
            "account" => $this->input->post('account'),
            "type" => $this->input->post('type'),
            "amount" => round($this->input->post('amount'), 3),
            "narration" => $this->input->post('narration'),
            //"branch_id" => $this->input->post('branch_id') ?  $this->input->post('branch_id') : 0,
            "concerned_person" => $this->input->post('concerned_person') ?  $this->input->post('concerned_person') : 0,
            //"unit" => $this->input->post('unit'),
            "reference" => $this->input->post('reference'),
        );

        $entry_id = $this->Enteries_model->save($enteries_data, $id);
        if ($entry_id) {
            $transactions_info = $this->Transactions_model->get_one($transaction_id);
            if ($transactions_info->type == "payment_voucher" || $transactions_info->type == "receipt_voucher") {
                if ($transactions_info->type == "payment_voucher") {
                    $bank_entry_type = "cr";
                    $sum_entries = "dr";
                } else if ($transactions_info->type == "receipt_voucher") {
                    $bank_entry_type = "dr";
                    $sum_entries = "cr";
                }

                $srs = array("trans_id" => $transaction_id, "type" => $bank_entry_type, "deleted" => 0);
                $entry_num = $this->Enteries_model->get_all_where($srs)->num_rows();
                if ($entry_num == 0) {
                    $data = array(
                        "trans_id" => $transaction_id,
                        "account" => $transactions_info->bank_cash,
                        "type" => $bank_entry_type,
                        "amount" => round($this->input->post('amount'), 3),
                        "narration" => $this->input->post('narration'),
                        "branch_id" => 0,
                    );
                    $entry_i = $this->Enteries_model->save($data);
                } else {
                    $bank_entry = $this->Enteries_model->get_one_where($srs);
                    $total_amount = $this->Enteries_model->get_total_voucher(array("trans_id" => $transactions_info->id, "type" => $sum_entries, "no_bank_cash" => 1));
                    $data2 = array(
                        "amount" => round($total_amount, 3),
                    );
                    $entry_i = $this->Enteries_model->save($data2, $bank_entry->id);
                }
            }
            echo json_encode(array("success" => true, "data" => $this->_row_entry_data($entry_id), 'id' => $entry_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function delete_entry()
    {
        if(strcasecmp($this->db->dbprefix, 'Integrated_Banners_') == 0){
            return; //delete for aprove transaction is forbidden
        }

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        $info = $this->Enteries_model->get_one($id);

        if ($this->input->post('undo')) {
            if ($this->Enteries_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_entry_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Enteries_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    function get_accounts_suggestion_enteries()
    {

        $key = $_REQUEST["q"];
        $suggestion = array();

        $list = array(1, 2, 3, 5, 6);

        $accounts = $this->Accounts_model->get_accounts_suggestion($key, array(), $list);

        foreach ($accounts as $account) {
            if (!empty($account->acc_code)) {
                $account_code =  "[" . $account->acc_code . '] - ';
            } else {
                $account_code = '';
            }
            $parent = $this->Accounts_model->get_one($account->acc_parent);
            $account_parent = $parent->acc_name ? $parent->acc_name . " => " : "";
            //$bal = $this->Accounts_model->get_balance($account->id);
            //$balance = number_format($bal['total'], 3,".",",");
            //$balance_type = $bal['total_type'];

            $child_count = $this->Accounts_model->get_all_where(array("deleted" => 0, "acc_parent" => $account->id))->num_rows();
            $is_parent = $child_count == 0 ? 0 : 1;

            if (!$is_parent) {
                $suggestion[] = array("id" => $account->id, "text" => /*$account_parent . */ $account_code . $account->acc_name/*. " (" . $balance . " " . $balance_type . ")"*/);
            }
        }

        echo json_encode($suggestion);
    }

    private function can_approve()
    {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_approve_manual_transactions") == "1") {
                return true;
            }
        }
    }
}
