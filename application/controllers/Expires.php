<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Expires extends MY_Controller {

    function __construct() {
        parent::__construct();
       
        $this->permission_checker("expiries");
        // $this->access_allowed_members();
        $this->load->model('Departments_model');
        $this->load->model('Expires_model');
        
    }

    
    public function index() {
        $this->check_module_availability('module_expires');
        $view_data['status'] = array(
            array("id"=>"alert", "text"=>'alert'),
            array("id"=>"ignore", "text"=>'ignore')
        );

        $view_data['type_dropdown'] = array(
                    array ("id" => '', "text" => "-type-"),
                    array ("id" => "Domain", "text" => "Domain"),
                    array ("id" => "Hosting", "text" => "Hosting"),
                    array ("id" => "Visa", "text" => "Visa"),
                    array ("id" => "Insurance", "text" => "Insurance"),
                    array ("id" => "Car Insurance", "text" => "Car Insurance"),
                    array ("id" => "Car Registration", "text" => "Car Registration"),
                    array ("id" => "AMC Contract" , "text" => "AMC Contract")
                );
    	
        $this->template->rander("admin/index", $view_data); 
    }


    private function can_approve() {
        $this->check_module_availability('module_expires');
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin) {
                return true;
            }
        }
    }

    
   public function modal_form() {
    $this->check_module_availability('module_expires');
        validate_submitted_data(array(
            "id" => "numeric"
        ));
        $this->load->model('Expires_model');
        $view_data['model_info'] = $this->Expires_model->get_one($this->input->post('id'));
 		$view_data['departments_dropdown'] = array("" => "-") + $this->Departments_model->get_dropdown_list(array("title"));
		$view_data['clients_dropdown'] = array("" => "-") + $this->Clients_model->get_dropdown_list(array("company_name"));
		$where = array("user_type" => "staff", "status" => "active");

        $view_data['team_members_dropdown'] = array("" => "-") + $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", $where);
       
        $this->load->view('admin/modal_form', $view_data);

    }

    public function save() {
        $this->check_module_availability('module_expires');
        validate_submitted_data(array(
            "id" => "numeric",
            "type" => "required",
            // "department_id" => "required",
            // "responsible_id" => "required"
        ));

        $id = $this->input->post('id');

        $data = array(
            "item" => $this->input->post('item'),
            "type" => $this->input->post('type'),
            "department_id" => $this->input->post('department_id'),
            "client_id" => $this->input->post('client_id'),
            "expiry" => $this->input->post('expiry'),
            "recurring_charges" => $this->input->post('recurring_charges'),
            "responsible_id" => $this->input->post('responsible_id')
        );

        


        $save_id = $this->Expires_model->save($data, $id);

        if ($save_id) {
                      
            echo json_encode(array("success" => true, 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }

    }


     function delete() {
        $this->check_module_availability('module_expires');
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Expires_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Expires_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }


    public function list_data() {
        $this->check_module_availability('module_expires');
        $options = array(
            
            "expiry" => $this->input->post('expiry'),
            "type" => $this->input->post("type")
        );
        $this->load->model('Expires_model');
    	$list_data = $this->Expires_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    //get an expnese category list row
    private function _row_data($id) {
        $data = $this->Expires_model->get_one($id);
        return $this->_make_row($data);
    }

    //prepare an item category list row
    private function _make_row($data) {
        
        

        $actions = modal_anchor(get_uri("Expires/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit'), "data-post-id" => $data->id));

        $actions .= js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("Expires/delete"), "data-action" => "delete-confirmation"));
        $this->load->model('Departments_model');
        $departmentData = $this->Departments_model->get_one($data->department_id);
        //$client_data = $this->Clients_model->get_one($data->client_id);
        

       $todayPlus = date('Y-m-d', strtotime('30 day', strtotime(date("Y-m-d"))));

        $today = date('Y-m-d');
        
        
        
        if($data->expiry > $todayPlus)
        {
            $expiry = "<span class='bg-success p5 card' style='border-radius:5px;'>".$data->expiry."</span>";

        }

        if ($data->expiry <= $todayPlus) {
             $expiry = "<span class='bg-warning p5 card' style='border-radius:5px;'>".$data->expiry."</span>";



             if ($data->expiry <= $today) {
                $expiry = "<span class='bg-danger p5 card' style='border-radius:5px;'>".$data->expiry."</span>";
             }
        }




         if ($this->can_approve()) {
            $stat = js_anchor($data->status, array('title' => "status", "class" => "btn btn-default", "data-id" => $data->id, "data-value" => $data->status, "data-act" => "update-status"));
        } else {
            $stat = $data->status;
        }

        
        return array(
            $data->id,
            $data->item,
            $data->type,
            $departmentData->title,
            $expiry,
            $data->recurring_charges,
            $data->user,
            $data->company_name,
            $stat,
            $actions
        );
    }



    function update_status ($stat = 0, $sent_id = 0) {
        $this->check_module_availability('module_expires');
         if ($sent_id == 0) {
            $id = $this->input->post('id');
         } else {
            $id = $sent_id;
         } 

         if($stat == 0) {
            $stat = $this->input->post('value'); 
         } 
         
         $data = array(
            'status' => $stat
         );

        $status_id = $this->Expires_model->save($data, $id);

        if ($status_id) {
            echo json_encode(array("success" => true, 'message' => 'success'));            
        } else {
            echo json_encode(array("success" => false, 'message' => "failed"));
        }

    }

}