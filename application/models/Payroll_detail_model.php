<?php

class Payroll_detail_model extends Crud_model {

    private $table = null;
    

    function __construct() {
        $this->table = 'payroll_detail';
        parent::__construct($this->table);
        //$this->init_activity_log($this->table, $this->table);
    }

    
    function get_details($options = array()) {
        $payroll_detail_table = $this->db->dbprefix('payroll_detail');
        $payroll_table = $this->db->dbprefix('payroll');
        $employee_table = $this->db->dbprefix('users');
        $employee_salary_table =  $this->db->dbprefix('users');
        $employee_job_info_table =  $this->db->dbprefix('team_member_job_info');
        $attendnace_table = $this->db->dbprefix('attendance');
        $leave_applications_table = $this->db->dbprefix('leave_applications');

        $where = "";

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $payroll_detail_table.id=$id";
        }

        $month = get_array_value($options, "month");
        if ($month) {
            $where .= " AND $payroll_table.month = '$month'";
        }

        $payroll_id = get_array_value($options, "payroll_id");
        if ($payroll_id) {
            $where .=  " AND $payroll_detail_table.payroll_id = $payroll_id"; 
        }

        $employee = get_array_value($options, "employee");
        if ($employee) {
            $where .= " AND $payroll_detail_table.employee_id = $employee";
        }

        $where_attend = "";
        $offset = convert_seconds_to_time_format(get_timezone_offset());

        $start_date = get_array_value($options, "start_date");
        if ($start_date) {
            $where_attend .= " AND DATE(ADDTIME($attendnace_table.in_time,'$offset'))>='$start_date'";
        }
        $end_date = get_array_value($options, "end_date");
        if ($end_date) {
            $where_attend .= " AND DATE(ADDTIME($attendnace_table.in_time,'$offset'))<='$end_date'";
        }

        $attendance = "SELECT $attendnace_table.user_id, SUM(TIMESTAMPDIFF(SECOND, $attendnace_table.in_time, $attendnace_table.out_time)) AS total_duration
                    FROM $attendnace_table
                    WHERE $attendnace_table.deleted=0 $where_attend 
                    GROUP BY $attendnace_table.user_id";

        $where_leave = "";

        $where_leave .= " AND $leave_applications_table.status='approved'";


        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");

        if ($start_date && $end_date) {
            $where_leave .= " AND ($leave_applications_table.start_date BETWEEN '$start_date' AND '$end_date') ";
        }

        $leaves = "SELECT  SUM($leave_applications_table.total_hours) AS total_hours,
                SUM($leave_applications_table.total_days) AS total_days, MAX($leave_applications_table.applicant_id) AS applicant_id, $leave_applications_table.status
            FROM $leave_applications_table       
            WHERE $leave_applications_table.deleted=0 $where_leave
            GROUP BY $leave_applications_table.applicant_id";
       

        $sql = "SELECT $payroll_detail_table.*, $payroll_table.status as stat, CONCAT($employee_salary_table.first_name,' ',$employee_salary_table.last_name) as employee, $employee_salary_table.image as employee_avatar, total_duration, total_hours, total_days, 
        $employee_job_info_table.bank_title,
        $employee_job_info_table.account_title,
        $employee_job_info_table.account_no,
        $employee_table.job_title
        
        FROM $payroll_detail_table
        LEFT JOIN $payroll_table ON ($payroll_detail_table.payroll_id = $payroll_table.id)
        LEFT JOIN $employee_salary_table ON ($payroll_detail_table.employee_id = $employee_salary_table.id)
        LEFT JOIN $employee_job_info_table ON $employee_job_info_table.user_id = $payroll_detail_table.employee_id 
        LEFT JOIN ($attendance) as attendance_summary ON $payroll_detail_table.employee_id = attendance_summary.user_id
        LEFT JOIN ($leaves) as leaves_summary ON $payroll_detail_table.employee_id = leaves_summary.applicant_id
        WHERE $payroll_detail_table.deleted=0 $where";


        return $this->db->query($sql);
    }

    function get_sum($options = array()) {
        $payroll_detail_table = $this->db->dbprefix('payroll_detail');
        $payroll_table = $this->db->dbprefix('payroll');

        $where = "";

        $employee_id = get_array_value($options, "employee_id");
        if ($employee_id) {
            $where .= " AND $payroll_detail_table.employee_id=$employee_id";
        }
       

        $sql = "SELECT SUM($payroll_detail_table.loan) as paid_loan
        
        FROM $payroll_detail_table
        LEFT JOIN $payroll_table ON $payroll_table.id = $payroll_detail_table.payroll_id
        WHERE $payroll_detail_table.deleted=0 AND $payroll_table.status = 'processed' $where";


        return $this->db->query($sql);
    }

}
