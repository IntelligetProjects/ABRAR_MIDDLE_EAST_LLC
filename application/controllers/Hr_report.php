<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Hr_report extends MY_Controller {

    function __construct() {
        parent::__construct();
        //$this->access_only_admin();
    }

    function index() {

        $view_data["products"] = $this->Items_model->get_all_where(array("deleted"=>0, "item_type"=>"product"))->num_rows();
        $view_data["services"] = $this->Items_model->get_all_where(array("deleted"=>0, "item_type"=>"service"))->num_rows();
        
        
        $this->load->view("reports_view/hr", $view_data);
    }

    function test() {
        $info = $this->Reports_model->get_items_cat_statistics();
        var_dump($info);
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
            $result[] = $this->_detail_make_row($data, $this->Users_model->total_leaves($data->employee_id, array("start_date" => $startyear, "end_date" => $endyear)));
        }
        echo json_encode(array("data" => $result));
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

    private function _detail_make_row($data, $total_info) {
        
        $image_url = get_avatar($data->employee_avatar);
        $employee = "<span class='avatar avatar-xs mr10'><img src='$image_url' alt=''></span>" . $data->employee;

        $weekend = explode(",", get_setting("weekend"));
        $working_hours = $this->Users_model->get_job_info($data->employee_id)->working_hours ? $this->Users_model->get_job_info($data->employee_id)->working_hours : 8;
        $date1 = $this->Payroll_model->get_one($data->payroll_id)->month;
        $date2 = DateTime::createFromFormat("Y-m-d", $date1);

        $working_days = $this->countDays($date2->format("Y"), $date2->format("m"), $weekend);
        $total_working_hours = $working_days*$working_hours;
        $duration = convert_seconds_to_time_format(abs($data->total_duration));
        $actual_working_hours = to_decimal_format(convert_time_string_to_decimal($duration));
        $attendance_percentage = round(($actual_working_hours/$total_working_hours*100), 2);

        if ($data->total_days > 1) {
            $duration = $data->total_days . " " . lang("days");
        } else {
            $duration = ($data->total_days ? $data->total_days:0) . " " . lang("day");
        }

        if ($data->total_hours > 1) {
            $duration = $duration . " (" . $data->total_hours . " " . lang("hours") . ")";
        } else {
            $duration = $duration . " (" . ($data->total_hours ? $data->total_hours :0) . " " . lang("hour") . ")";
        }

        $allowed_info = $this->Users_model->get_details(array("id" => $data->employee_id))->row();
        if(isset($allowed_info)) {
        $yearly_leaves = $allowed_info->yearly_leaves?$allowed_info->yearly_leaves:0;
        $remaining = ($yearly_leaves) - $total_info->total_days;
        } else {
           $yearly_leaves = 0;
            $remaining = ($yearly_leaves) - 0; 
        }

        return array( 
            $employee,
            $data->salary,
            anchor(get_uri("attendance"),$attendance_percentage." %"),
            anchor(get_uri("leaves"),$duration),
            ($yearly_leaves) . " " . lang("days").   "<span class='help' data-toggle='tooltip' title=' Total yearly leaves: ".($total_info->total_days?$total_info->total_days:0)." Days'><i class='fa fa-question-circle'></i></span>",
            $remaining . " " . lang("days"),
        );

    }

}

/* End of file Expenses_report.php */
/* Location: ./application/controllers/report.php */