<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payroll extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->init_permission_checker("payroll");
        $this->access_only_allowed_members();
        if ($this->login_user->is_admin||$this->get_access_info("payroll")->access_type=="all"||$this->get_access_info("final_settelment")->access_type=="all") {
        return true;
        }
    }

    
    function index() {
        $this->template->rander("payroll/index");
    }

 
    function modal_form() {
       validate_submitted_data(
            array(
                "id" => "numeric"
            )
        );
        $view_data = array();

        $id = $this->input->post('id');

       if ($id) {
            $options = array("id" => $id);
            $view_data['model_info'] = $this->Payroll_detail_model->get_details($options)->row();
        }

       $this->load->view('payroll/modal_form', $view_data);
    }

    
    function save() {

        $id = $this->input->post('id');

        validate_submitted_data(
            array(
                    "loan" => "numeric",
                    "manual_deduction" => "numeric",
                    "manual_bounce" => "numeric",
                    "advance" => "numeric",
                    "pasi_company" => "numeric",
                    "pasi_employee" => "numeric",
                    "job_s_company" => "numeric",
                    "job_s_employee" => "numeric",
                )
            );
       
        if ($id) {
            $data = array(
                "manual_deduction" => $this->input->post('manual_deduction'),
                "manual_deduction_reason" => $this->input->post('manual_deduction_reason'),
                "manual_bounce" => $this->input->post('manual_bounce'),
                "manual_bonus_reason" => $this->input->post('manual_bonus_reason'),
                "loan" => $this->input->post('loan'),
                "advance" => $this->input->post('advance'),
                "pasi_company" => $this->input->post('pasi_company'),
                "pasi_employee" => $this->input->post('pasi_employee'),
                "job_s_company" => $this->input->post('job_s_company'),
                "job_s_employee" => $this->input->post('job_s_employee'),
            );

            $save_id = $this->Payroll_detail_model->save($data, $id);

        } else {
            validate_submitted_data(
            array(
                    "month" => "required"
                )
            );

            $payroll_data = array(
                "month" => $this->input->post('month')
            );


            $save = $this->Payroll_model->save($payroll_data);


            $employees = $this->Users_model->get_details(array("user_type" => "staff", "status" => "active", "payroll" => 1))->result();

            foreach ($employees as $employee) {
                $data['payroll_id'] = $save; 
                $data['employee_id'] = $employee->id;
                $data['salary'] = get_gross_salary($employee->id)? get_gross_salary($employee->id) : 0;
                $data['pasi_company'] = get_company_pasi_share($employee->id)?get_company_pasi_share($employee->id):0;
                $data['pasi_employee'] = get_employee_pasi_share($employee->id)?get_employee_pasi_share($employee->id):0;
                // $data['job_s_company'] = get_company_job_s_share($employee->id)?get_company_job_s_share($employee->id):0;
                // $data['job_s_employee'] = get_employee_job_s_share($employee->id)?get_employee_job_s_share($employee->id):0;
                $data['loan'] = get_employee_loans($employee->id)['balance_loan']?get_employee_loans($employee->id)['balance_loan']:0;
                $save_id = $this->Payroll_detail_model->save($data);
            }
        }
       
        echo json_encode(array("success" => true, "data" => '', 'message' => lang('record_saved')));
    }

    //delete/undo an expense category
    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        $payroll_info = $this->Payroll_detail_model->get_one($id);


        if ($this->Payroll_detail_model->delete($id)) {
            
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    //delete/undo an expense category
    function delete_month() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        $payroll_info = $this->Payroll_model->get_one($id);


        if ($this->Payroll_model->delete($id)) {
            
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }


    function list_data() {
        $list_data = $this->Payroll_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    function detail_list_data($payroll_id) {
        $payroll_info = $this->Payroll_model->get_one($payroll_id);
        $month = $payroll_info->month;
        // First day of the month.
        $start_date = date('Y-m-01', strtotime($month));
        // Last day of the month.
        $end_date =  date('Y-m-t', strtotime($month));

        $options = array("payroll_id" => $payroll_id, "start_date" => $start_date, "end_date" => $end_date);
        $list_data = $this->Payroll_detail_model->get_details($options)->result();
        $result = array();
        $year = DateTime::createFromFormat("Y-m-d", $payroll_info->month)->format("Y");
        $startyear = $year."-01-01";
        $endyear = $year."-12-31";
        foreach ($list_data as $data) {
            /*$result[] = $this->_detail_make_row($data, $this->Users_model->total_leaves($data->employee_id, array("start_date" => $startyear, "end_date" => $endyear)));*/

            $result[] = $this->_detail_make_row($data);
        }
        // var_dump($result);die();
        echo json_encode(array("data" => $result));
    }

   
    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Payroll_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    private function _detail_row_data($id) {
        $options = array("payroll_id" => $id);
        $data = $this->Payroll_detail_model->get_details($options)->row();
        //pretty_me($data);
        return $this->_make_row($data);
    }

    function countDays($year, $month, $ignore = array()) {
        $count = 0;
        $counter = mktime(0, 0, 0, $month, 1, $year);
        while (date("n", $counter) == $month) {
            if (in_array(date("w", $counter), $ignore) == false) {
                $count++;
            }
            $counter = strtotime("+1 day", $counter);
        }
        return $count;
    }

    private function _detail_make_row($data) {
        $bounce = $data->manual_bounce;
        $deduction = $data->manual_deduction;
        $payroll_info = $this->Payroll_model->get_one($data->payroll_id);
        $start_date_payroll = date('Y-m-01', strtotime($payroll_info->month));
        $end_date_payroll = date('Y-m-t', strtotime($payroll_info->month));

        $advOptions = array(

            "user_id" => $data->employee_id,
            "start_date" => $start_date_payroll,
            "end_date" => $end_date_payroll

        );
        $advance = $this->Salary_advance_model->get_total_advance($advOptions)->row()->total_advance;

        $pasi_share = $data->pasi_employee;
        $job_s_share = $data->job_s_employee;
        $loan = $data->loan;
        $per_day_salary = $data->salary / 30;
        $employee_leaves = get_employee_leaves($data->employee_id, $start_date_payroll, $end_date_payroll);
        $unpaid_leaves = 0;
        if($employee_leaves)
        {
            $unpaid_leaves = 0;
            foreach ($employee_leaves as $key => $employee_leave) {

                $unpaid_leaves += $employee_leave->total_days;
            }
        }
        $unpaid_deduction_amount = $per_day_salary * $unpaid_leaves;

        $amount = $data->salary + $bounce - $deduction - $advance - $pasi_share - $job_s_share - $unpaid_deduction_amount - $loan; 

        $edit = "";
        $delete = "";
        $salary_slip = "";

        if ($data->stat == 'draft' || $data->stat == 'pending') 
        {
            
            $edit = modal_anchor(get_uri("payroll/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => "edit", "data-post-id" => $data->id));
        
            $delete = js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("payroll/delete"), "data-action" => "delete-confirmation"));
        }

        if ($data->stat == 'approved' || $data->stat == 'processed') 
        {
            $salary_slip_link = get_uri('payroll/salary_slip/').$data->employee_id."/".$data->payroll_id;
            $salary_slip = "<a class='btn btn-default' href='".$salary_slip_link."' target='_blank'>". lang("SLIP")."</a>";
        }
        if ($data->files) 
        {
            $file_link ='/'.$this->db->dbprefix.'/files/timeline_files/'.$data->files;
            $attachment  = "<a class='btn btn-info' href='".$file_link."' target='_blank'>". lang("SHOW")."</a>";
        }else{
            $attachment='';
        }

        $image_url = get_avatar($data->employee_avatar);
        $employee = "<span class='avatar avatar-xs mr10'><img src='$image_url' alt=''></span>" . $data->employee;

        
        if($this->db->dbprefix=='aitech'|| $this->db->dbprefix=='V3'){
            return array( 
                $employee,
                lang($data->bank_title),
                $data->account_title,
                $data->account_no,
                $data->salary,
                bcdiv($bounce,1,3),
                $data->manual_bonus_reason,
                bcdiv($deduction,1,3),
                $data->manual_deduction_reason,
                bcdiv($advance,1,3),
                bcdiv($loan,1,3),
                bcdiv($pasi_share,1,3),
                bcdiv($job_s_share,1,3),
                $unpaid_leaves,
                bcdiv($unpaid_deduction_amount,1,3),
                bcdiv($amount,1,3),
                $attachment,
                $edit . $delete . $salary_slip
            );
        }else{
            return array( 
                $employee,
                lang($data->bank_title),
                $data->account_title,
                $data->account_no,
                $data->salary,
                bcdiv($bounce,1,3),
                $data->manual_bonus_reason,
                bcdiv($deduction,1,3),
                $data->manual_deduction_reason,
                bcdiv($advance,1,3),
                bcdiv($loan,1,3),
                bcdiv($pasi_share,1,3),
                bcdiv($job_s_share,1,3),
                $unpaid_leaves,
                bcdiv($unpaid_deduction_amount,1,3),
                bcdiv($amount,1,3),
                $edit . $delete . $salary_slip
            );
        }
        

    }

    private function can_approve() {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "payrolls") == "all") {
                return true;
            }
        }
    }

    private function _make_row($data) {
    //    $month = anchor(get_uri('payroll/view/' . $data->id),date_format(date_create($data->month),"F-Y"));
    $mn=   lang(date_format(date_create($data->month),"F")).'-'.date_format(date_create($data->month),"Y");
    $month = anchor(get_uri('payroll/view/' . $data->id),$mn);

        /*if ($this->can_approve()) {
            $stat = js_anchor(lang($data->status), array('title' => "status", "class" => "btn btn-default", "data-id" => $data->id, "data-value" => $data->status, "data-act" => "update-task-status"));
        } else {
            $stat = lang($data->status);
        }*/

        $stat = lang($data->status);

        $delete = "";
        if ($data->status == 'draft' || $data->status == 'pending') {
            $delete = js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("payroll/delete_month"), "data-action" => "delete-confirmation"));
        } 

       
       return array(
            $month,
            $stat,
            $delete
       );

    }


    function view($payroll_id = 0) {
         if ($payroll_id) {
            $view_data = array();
            $options = array("id" => $payroll_id);
            $item_info = $this->Payroll_model->get_details($options)->row();
            
            if ($item_info->status == 'draft') {
                $view_data['status'] = 'label-default';
            } else if ($item_info->status == 'pending') {
                $view_data['status'] = 'label-warning';
            } else if ($item_info->status == 'approved') {
                $view_data['status'] = 'label-success';
            } else if ($item_info->status == 'processed') {
                $view_data['status'] = 'label-primary';
            }

            $view_data['can_approve'] = $this->can_approve();
            $view_data['item_info'] = $item_info;
            $view_data['payroll_id'] = $payroll_id;   
            // var_dump($view_data); die();            
            $view_data['banks_dropdown'] = array("" => "-") +$this->Accounts_model->get_dropdown_list(array("acc_name"), "id", array("acc_parent" => $this->Accounts_model->get_banking_accounts_id(), "deleted" => 0))+
            // array("" => "---------------------------------") +
            $this->Accounts_model->get_dropdown_list(array("acc_name"), "id", array("acc_parent" => $this->Accounts_model->get_treasury_accounts_id(), "deleted" => 0));
           
         }
        $this->template->rander("payroll/view", $view_data);
    }


    function generate_check($str) {
            $date = $str;
            $rows = $this->Payroll_model->get_details(array("month" => $date))->num_rows();
            if ($rows != 0) {
                $message = 'This Month slip is already generated';
                $this->form_validation->set_message('generate_check', $message);
                return FALSE;
            } else {
                $today = date('mm-yyyy');
                $month = date('mm-yyyy', strtotime($str));
                if ($today != $month) {
                    $message = 'You can only generate the slip for this month';
                     $this->form_validation->set_message('generate_check', $message);
                    return FALSE;
                }        
                return True;
            }  
    }


    function total_amount_of_payroll($payroll_id) {

        $options = array("payroll_id" => $payroll_id);
        $list_data = $this->Payroll_detail_model->get_details($options)->result();
        $amount = array();
        foreach ($list_data as $data) {
        $bounce =   $data->manual_bounce;
        $deduction =  $data->manual_deduction;
        $amount[] = $data->salary + $bounce - $deduction - $data->loan - $data->advance;
        }

        return array_sum($amount);

    }


    function status ($stat = '', $sent_id = 0){

         if ($sent_id == 0) {
            $payroll_id = $this->input->post('id');
         } else {
            $payroll_id = $sent_id;
         } 

         if($stat == 0) {
            $stat = $this->input->post('value'); 
         } 
         if ($stat == "processed") {
         $data = array(
            'status' => $stat,
            'payment_item_id' => $this->input->post('bank')
         );
        }else{
            $data = array(
                'status' => $stat
             );  
        }

        $payroll_status = $this->Payroll_model->save($data, $payroll_id);
        if ($payroll_status) {
            echo json_encode(array("success" => true, 'message' => 'success'));
            $options = array("payroll_id" => $payroll_status);
            $info = $this->Payroll_detail_model->get_details($options)->result();
            if ($stat == "approved") {
                $this->make_entries($payroll_id);
            } elseif ($stat == "processed") {
                $this->process_entries($payroll_id);
            } 
        }
        
    }

    function make_entries($payroll_id) {
        $narration ='';
        $d=$this->get_total_salaries($payroll_id);
        $payroll_info=$d['payroll_info'];
        $total_payable=$d['total_payable'];
        $date = get_today_date();
        $type = "Approved Employees Salaries - ".date_format(date_create($payroll_info->month),"F-Y");
        
        $acc_array[] = array("account_id" => get_setting('salary_expenses'), "type" => 'dr',"amount" =>  $total_payable, "narration" => $narration);

        $acc_array[] = array("account_id" => get_setting('payable_salaries'), "type" => 'cr',"amount" =>  $total_payable, "narration" => $narration);

        $transaction_id = make_transaction($date, $acc_array, $type);
        // foreach ($payroll_detail_info as $key => $item) {
           
        //     $salary = $item->salary;
        //     $bounce = $item->manual_bounce;
        //     $deduction = $item->manual_deduction;
        //     $advOptions = array(
        //         "user_id" => $item->employee_id,
        //         "start_date" => $start_date_payroll,
        //         "end_date" => $end_date_payroll
        //     );
        //     $advance = $this->Salary_advance_model->get_total_advance($advOptions)->row()->total_advance;
        //     $emp_pasi_share = $item->pasi_employee;
        //     $emp_job_s_share = $item->job_s_employee;
        //     $company_pasi_share = $item->pasi_company;
        //     $company_job_s_share = $item->job_s_company;
        //     $loan = $item->loan;
        //     $per_day_salary = $item->salary / 30;
        //     $monthly_leave_amount = $per_day_salary * 2.5;
        //     $monthly_graduatiy_amount = get_monthly_gratuity($item->employee_id);
        //     $employee_leaves = get_employee_leaves($item->employee_id, $start_date_payroll, $end_date_payroll);
        //     $unpaid_leaves = 0;
        //     if($employee_leaves)
        //     {
        //         $unpaid_leaves = 0;
        //         foreach ($employee_leaves as $key => $employee_leave) {

        //             $unpaid_leaves += $employee_leave->total_days;
        //         }
        //     }
        //     $unpaid_deduction_amount = $per_day_salary * $unpaid_leaves;
        //     $payable_salary = $item->salary + $bounce - $deduction - $unpaid_deduction_amount - $emp_pasi_share - $emp_job_s_share - $loan;

        //     $employee_info = $this->Users_model->get_details(array('id' => $item->employee_id))->row();

        //     /* ENTRIES */

        //     if(!$employee_info->national)
        //     {
        //         $date = get_today_date();
        //         $type = $item->employee." Automatic Salaries Booking: ".date_format(date_create($payroll_info->month),"F-Y");
                
        //         $acc_array = array();
        //         $narration = "Automatic Salaries booking: ".date_format(date_create($payroll_info->month),"F-Y");

        //         $acc_array[] = array("account_id" => get_setting('salary_expenses'), "type" => 'dr',"amount" => $payable_salary+$monthly_leave_amount+$monthly_graduatiy_amount, "narration" => $narration.' For '.$item->employee, "reference" => $item->id, "concerned_person" => $item->employee_id);

        //         $acc_array[] = array("account_id" => get_setting('payable_salaries'), "type" => 'cr',"amount" => $payable_salary, "narration" => $narration.' For '.$item->employee, "reference" => $item->id, "concerned_person" => $item->employee_id);

        //         $acc_array[] = array("account_id" => get_setting('leave_salary_expenses'), "type" => 'cr',"amount" => $monthly_leave_amount, "narration" => 'MONTHLY LEAVE - '.$narration.' For '.$item->employee, "reference" => $item->id, "concerned_person" => $item->employee_id);

        //         $acc_array[] = array("account_id" => get_setting('payable_EOS_benefits'), "type" => 'cr',"amount" => $monthly_graduatiy_amount, "narration" => 'MONTHLY GRADUATITY - '.$narration.' For '.$item->employee, "reference" => $item->id, "concerned_person" => $item->employee_id);


        //         $transaction_id = make_transaction($date, $acc_array, $type);
        //     }
        //     else
        //     {
        //         $date = get_today_date();
        //         $type = $item->employee." Automatic Salaries booking: ".date_format(date_create($payroll_info->month),"F-Y");
                
        //         $acc_array = array();
        //         $narration = "Automatic Salaries booking: ".date_format(date_create($payroll_info->month),"F-Y");

        //         $acc_array[] = array("account_id" => get_setting('salary_expenses'), "type" => 'dr',"amount" => $payable_salary+$monthly_leave_amount+$company_pasi_share+$company_job_s_share+$emp_pasi_share+$emp_job_s_share, "narration" => $narration.' For '.$item->employee,  "reference" => $item->id, "concerned_person" => $item->employee_id);

        //         $acc_array[] = array("account_id" => get_setting('payable_salaries'), "type" => 'cr',"amount" => $payable_salary, "narration" => $narration.' For '.$item->employee, "reference" => $item->id, "concerned_person" => $item->employee_id);

        //         $acc_array[] = array("account_id" => get_setting('leave_salary_expenses'), "type" => 'cr',"amount" => $monthly_leave_amount, "narration" => 'MONTHLY LEAVE - '.$narration, "reference" => $item->id, "concerned_person" => $item->employee_id,);

        //         $acc_array[] = array("account_id" => get_setting('payable_PASI'), "type" => 'cr',"amount" => $company_pasi_share, "narration" => 'PASI - Company - '.$narration.' For '.$item->employee, "reference" => $item->id, "concerned_person" => $item->employee_id);

        //         $acc_array[] = array("account_id" => get_setting('payable_PASI'), "type" => 'cr',"amount" => $emp_pasi_share, "narration" => 'PASI - EMPLOYEE - '.$narration.' For '.$item->employee, "reference" => $item->id, "concerned_person" => $item->employee_id);


        //         $acc_array[] = array("account_id" => get_setting('payable_job_security'), "type" => 'cr',"amount" => $company_job_s_share, "narration" => 'JOB SECURITY - Company - '.$narration.' For '.$item->employee, "reference" => $item->id, "concerned_person" => $item->employee_id);

        //         $acc_array[] = array("account_id" => get_setting('payable_job_security'), "type" => 'cr',"amount" => $emp_job_s_share, "narration" => 'JOB SECURITY - EMPLOYEE - '.$narration.' For '.$item->employee, "reference" => $item->id, "concerned_person" => $item->employee_id);


        //         $transaction_id = make_transaction($date, $acc_array, $type);
        //     }

        // }

    }
    function process_entries($payroll_id){
        $narration ='';
        $d=$this->get_total_salaries($payroll_id);
        $payroll_info=$d['payroll_info'];
        $total_payable=$d['total_payable'];
        $date = get_today_date();
        $type = "Processed Employees Salaries - ".date_format(date_create($payroll_info->month),"F-Y");
        
        $acc_array[] = array("account_id" => get_setting('payable_salaries'), "type" => 'dr',"amount" =>  $total_payable, "narration" => $narration);

        $acc_array[] = array("account_id" =>  $payroll_info->payment_item_id, "type" => 'cr',"amount" =>  $total_payable, "narration" => $narration);

        $transaction_id = make_transaction($date, $acc_array, $type);
    }
    // function process_entries($payroll_id) {
    //     $payroll_info = $this->Payroll_model->get_one($payroll_id);
    //     $payroll_options = array("payroll_id" => $payroll_id);
    //     $payroll_detail_info = $this->Payroll_detail_model->get_details($payroll_options)->result();
    //     $payroll_date = $payroll_info->month;

    //     $start_date_payroll = date('Y-m-01', strtotime($payroll_date));
    //     $end_date_payroll = date('Y-m-t', strtotime($payroll_date));
        

    //     foreach ($payroll_detail_info as $key => $item) {
    //         $salary = $item->salary;
    //         $bounce = $item->manual_bounce;
    //         $deduction = $item->manual_deduction;
    //         $advOptions = array(
    //             "user_id" => $item->employee_id,
    //             "start_date" => $start_date_payroll,
    //             "end_date" => $end_date_payroll
    //         );
    //         $advance = ($this->Salary_advance_model->get_total_advance($advOptions)->row()->total_advance)?$this->Salary_advance_model->get_total_advance($advOptions)->row()->total_advance:0;
    //         $emp_pasi_share = $item->pasi_employee;
    //         $emp_job_s_share = $item->job_s_employee;
    //         $company_pasi_share = $item->pasi_company;
    //         $company_job_s_share = $item->job_s_company;
    //         $loan = $item->loan;
    //         $per_day_salary = $item->salary / 30;
    //         $monthly_leave_amount = $per_day_salary * 2.5;
    //         $monthly_graduatiy_amount = get_monthly_gratuity($item->employee_id);
    //         $employee_leaves = get_employee_leaves($item->employee_id, $start_date_payroll, $end_date_payroll);
    //         $unpaid_leaves = 0;
    //         if($employee_leaves)
    //         {
    //             $unpaid_leaves = 0;
    //             foreach ($employee_leaves as $key => $employee_leave) {

    //                 $unpaid_leaves += $employee_leave->total_days;
    //             }
    //         }
    //         $unpaid_deduction_amount = $per_day_salary * $unpaid_leaves;
    //         $payable_salary = $item->salary + $bounce - $deduction - $unpaid_deduction_amount - $emp_pasi_share - $emp_job_s_share - $loan;

    //         $employee_info = $this->Users_model->get_details(array('id' => $item->employee_id))->row();

    //         /* ENTRIES */
    //         $date = get_today_date();
    //         $type = $item->employee." Automatic Salaries process: ".date_format(date_create($payroll_info->month),"F-Y");
            
    //         $acc_array = array();
    //         $narration = "Automatic Salaries process: ".date_format(date_create($payroll_info->month),"F-Y");

    //         $acc_array[] = array("account_id" => get_setting('payable_salaries'), "type" => 'dr',"amount" => $payable_salary+$advance, "narration" => $narration.' For '.$item->employee, "reference" => $item->id, "concerned_person" => $item->employee_id);

    //         $acc_array[] = array("account_id" => get_setting('default_bank'), "type" => 'cr',"amount" => $payable_salary, "narration" => 'Payable Salary - '.$narration.' For '.$item->employee, "reference" => $item->id, "concerned_person" => $item->employee_id);

    //         $acc_array[] = array("account_id" => get_setting('salary_advances'), "type" => 'cr',"amount" => $advance, "narration" => 'Advance - '.$narration.' For '.$item->employee, "reference" => $item->id, "concerned_person" => $item->employee_id);


    //         $transaction_id = make_transaction($date, $acc_array, $type);

    //     }

    // }
    function get_total_salaries($payroll_id){
        $payroll_info = $this->Payroll_model->get_one($payroll_id);
        $payroll_options = array("payroll_id" => $payroll_id);
        $payroll_detail_info = $this->Payroll_detail_model->get_details($payroll_options)->result();
        $payroll_date = $payroll_info->month;
       
        $start_date_payroll = date('Y-m-01', strtotime($payroll_date));
        $end_date_payroll = date('Y-m-t', strtotime($payroll_date));
        foreach ($payroll_detail_info as $key => $item) {
            $loan = $item->loan;
            $bounce = $item->manual_bounce;
            $deduction = $item->manual_deduction;
            $per_day_salary = $item->salary / 30;
            $monthly_leave_amount = $per_day_salary * 2.5;
            $monthly_graduatiy_amount = get_monthly_gratuity($item->employee_id);
            $employee_leaves = get_employee_leaves($item->employee_id, $start_date_payroll, $end_date_payroll);
            $unpaid_leaves = 0;
            if($employee_leaves)
            {
                $unpaid_leaves = 0;
                foreach ($employee_leaves as $key => $employee_leave) {

                    $unpaid_leaves += $employee_leave->total_days;
                }
            }
            

          
            $unpaid_deduction_amount = $per_day_salary * $unpaid_leaves;
            $payable_salary = $item->salary + $bounce - $deduction - $unpaid_deduction_amount  - $loan;

            // $total=$payable_salary+$monthly_leave_amount+$monthly_graduatiy_amount;
            $total=$payable_salary;
            $total_payable= $total_payable+$total;
          
            
           
        }
        $d=[];
        $d['payroll_info']=$payroll_info;
        $d['total_payable']=$total_payable;
        return  $d;
    }

    function auto_entries($payroll_id)
    {
        $this->make_entries($payroll_id);
        $this->process_entries($payroll_id);
    }

    function salary_slip($employee_id,$payroll_id)
    {
        $options = array('employee' => $employee_id, 'payroll_id' => $payroll_id);
        $salary_details = $this->Payroll_detail_model->get_details($options)->row();
        $payroll_info = $this->Payroll_model->get_one($payroll_id);

        $start_date_payroll = date('Y-m-01', strtotime($payroll_info->month));
        $end_date_payroll = date('Y-m-t', strtotime($payroll_info->month));

        $month = date('F',strtotime($payroll_info->month));
        $year = date('Y',strtotime($payroll_info->month));

        $all_leaves = get_employee_leaves_all($employee_id, $start_date_payroll, $end_date_payroll);

        $total_unpaid = 0;
        $total_paid = 0;
        $leave_deduction = 0;
        $total_leaves = 0;

        foreach ($all_leaves as $key => $leave) {

            $total_leaves += $leave->total_days;

            if($leave->paid_unpaid)
            {
                $total_unpaid += $leave->total_days;
            }
            else
            {
                $total_paid += $leave->total_days;
            }
        }

        $daily_salary = $salary_details->salary / 30;
        $leave_deduction = $total_unpaid * $daily_salary;

        $view_data['salary_details'] = $salary_details;
        $view_data['total_leaves'] = $total_leaves;
        $view_data['total_unpaid_leaves'] = $total_unpaid;
        $view_data['leave_deduction'] = $leave_deduction;
        $view_data['month'] = $month;
        $view_data['year'] = $year;
        $this->template->rander("payroll/salary_slip",$view_data);
    }

    function upload_files($employee_id,$payroll_id)
    {
        $options = array('employee' => $employee_id, 'payroll_id' => $payroll_id);
        $salary_details = $this->Payroll_detail_model->get_details($options)->row();
          //file operation
        $target_path = get_setting("timeline_file_path");
        $files_data = move_files_from_temp_dir_to_permanent_dir($target_path, "salary");
        $new_files = unserialize($files_data);
        $id=$salary_details->id;
        $data["files"] = '';
        $this->Payroll_detail_model->save($data,$id);
          if($id){
              $event_info = $this->Payroll_detail_model->get_one($id);
              $timeline_file_path = get_setting("timeline_file_path");
              $new_files = update_saved_files($timeline_file_path, $event_info->files, $new_files);
          }
         
          //file data
         serialize($new_files);
          $data["files"] =$new_files[0]['file_name'];
          $this->Payroll_detail_model->save($data,$id);
          $redirect_to="/payroll/salary_slip/$employee_id/$payroll_id";
          redirect($redirect_to);

    }


    function eos_slip($employee_id)
    {
        $options = array('id' => $employee_id);
        $employee_info = $this->Users_model->get_details($options)->row();

        $last_working_day = $this->input->post('last_working_day');
        $reason = $this->input->post('reason');
        $years_of_employement = get_employement_years($employee_id);
        $salary = bcdiv($employee_info->salary,1,3);;
        $housing = bcdiv($employee_info->housing,1,3);;
        $transportation = bcdiv($employee_info->transportation,1,3);;
        $telephone = bcdiv($employee_info->telephone,1,3);;
        $utility = bcdiv($employee_info->utility,1,3);;
        $start_date_payroll = date('Y-m-01', strtotime($last_working_day));
        $end_date_payroll = date('Y-m-t', strtotime($last_working_day));

        $now = strtotime(date('Y-m-d',strtotime($last_working_day))); // or your date as well
        $your_date = strtotime($start_date_payroll);
        $datediff = $now - $your_date;

        $last_month_working_days =  round($datediff / (60 * 60 * 24)) - 3;


        $start_date_leaves = date('Y-m-01');
        $end_date_leaves = date('Y-m-t');

        $unpaid_leaves = get_employee_unpaid_total_leaves($employee_id, $start_date_leaves, $end_date_leaves);

        $unpaid_leaves_deduction = ($salary + $housing + $transportation + $telephone + $utility) / 30 * (($unpaid_leaves)?$unpaid_leaves:0);

        /*pretty_me($unpaid_leaves_deduction);
        exit;*/

        $employee_loan = get_employee_loans($employee_id)['balance_loan'];

        $advOptions = array(

            "user_id" => $employee_id,
            "start_date" => $start_date_payroll,
            "end_date" => $end_date_payroll

        );


        $advance = $this->Salary_advance_model->get_total_advance($advOptions)->row()->total_advance;

        

        $view_data['employee_name'] = $employee_info->first_name.' '.$employee_info->last_name;
        $view_data['employee_info'] = $employee_info;
        $view_data['resident_card_no'] = $employee_info->resident_card_no;
        $view_data['current_date'] = date('d/m/Y');
        $view_data['designation'] = $employee_info->job_title;
        $view_data['date_of_joining'] = format_to_date($employee_info->date_of_hire);
        $view_data['last_date_work'] = $last_working_day;
        $view_data['last_month_working_days'] = $last_month_working_days;
        $view_data['years_of_employment'] = $years_of_employement;
        $view_data['reason'] = $reason;
        $view_data['salary'] = $salary;
        $view_data['housing'] = $housing;
        $view_data['transportation'] = $transportation;
        $view_data['telephone'] = $telephone;
        $view_data['utility'] = $utility;
        $view_data['gross_salary'] = bcdiv(($salary + $housing + $transportation + $telephone + $utility),1,3);    
        $last_month_salary = bcdiv(($salary + $housing + $transportation + $telephone + $utility) / 30 * $last_month_working_days,1,3);
        $view_data['last_month_salary'] = bcdiv($last_month_salary,1,3);
        $last_month_pasi = bcdiv(get_employee_pasi_share($employee_id) / 30 * $last_month_working_days,1,3);
        $last_month_job_s = bcdiv(get_employee_job_s_share($employee_id) / 30 * $last_month_working_days,1,3);
        $view_data['last_month_pasi'] = bcdiv($last_month_pasi,1,3);
        $view_data['last_month_job_s'] = bcdiv($last_month_job_s,1,3);

        $view_data['gratuity'] = bcdiv(get_gratuity($employee_id,$years_of_employement),1,3);

        $view_data['unpaid_leaves'] = $unpaid_leaves;
        $view_data['unpaid_leaves_deductions'] = bcdiv($unpaid_leaves_deduction,1,3);

        $view_data['loan_amount'] = $employee_loan;
        $view_data['advance_amount'] = $advance;

        $net_payable = bcdiv(get_gratuity($employee_id) + $last_month_salary - $last_month_pasi - $last_month_job_s - $unpaid_leaves_deduction,1,3);
        $view_data['net_payable'] = $net_payable;


        $this->template->rander("payroll/eos_slip",$view_data);
    }

    function eos_slip_view($employee_id)
    {
        $view_data['employee_id'] = $employee_id;

        $options = array('id' => $employee_id);
        $employee_info = $this->Users_model->get_details($options)->row();

        $view_data['years_of_employment'] = get_employement_years($employee_id);
        $view_data['salary'] = bcdiv($employee_info->salary,1,3);
        $view_data['housing'] = bcdiv($employee_info->housing,1,3);
        $view_data['transportation'] = bcdiv($employee_info->transportation,1,3);
        $view_data['telephone'] = bcdiv($employee_info->telephone,1,3);
        $view_data['utility'] = bcdiv($employee_info->utility,1,3);

        $this->load->view("team_members/final_settlement",$view_data);
    }
}