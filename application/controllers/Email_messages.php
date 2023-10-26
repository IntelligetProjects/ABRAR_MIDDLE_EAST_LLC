<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Email_messages extends MY_Controller {

    function __construct() {
        parent::__construct();
    }

    protected function validate_access($todo_info) {
       
    }

    //load todo list view
    function index() {
        $this->check_module_availability("module_todo");

        $this->template->rander("email_messages/index");
    }

    function modal_form() {
        $view_data['model_info'] = $this->Email_messages_model->get_one($this->input->post('id'));

      
        //check permission for saved todo list
        if ($view_data['model_info']->id) {
            $this->validate_access($view_data['model_info']);
        }
         $clients= $this->Clients_model->get_all_where(['deleted'=>0])->result();
            $d=[];
            $dx=[];
            foreach($clients as $client){
                $d['id']=$client->id;
                $d['text']=$client->company_name;
                array_push($dx,$d);
            }
         $view_data['to_data']=$dx;
                // $this->load->view('email_messages/modal_form', $view_data);
                $this->template->rander("email_messages/message", $view_data);
    }
    function message() {
        $view_data['model_info'] = $this->Email_messages_model->get_one($this->input->get('id'));

      
        //check permission for saved todo list
        if ($view_data['model_info']->id) {
            $this->validate_access($view_data['model_info']);
        }
         $clients= $this->Clients_model->get_all_where(['deleted'=>0])->result();
            $d=[];
            $dx=[];
            foreach($clients as $client){
                $d['id']=$client->id;
                $d['text']=$client->company_name;
                array_push($dx,$d);
            }
         $view_data['to_data']=$dx;
                // $this->load->view('email_messages/message', $view_data);
                $this->template->rander("email_messages/message", $view_data);
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric",
            "title" => "required"
        ));

        $id = $this->input->post('id');

        $data = array(
            "to" => $this->input->post('to'),
            "title" => $this->input->post('title'),
            "message" => $this->input->post('message') ? $this->input->post('message') : "",
            "date" => date('y-m-d h:m:s'),
        );

        $data = clean_data($data);
        
         //set null value after cleaning the data
       
        
        if ($id) {
            //saving existing todo. check permission
            $todo_info = $this->Email_messages_model->get_one($id);

            $this->validate_access($todo_info);
        }
        $save_id = $this->Email_messages_model->save($data, $id);
        if ($save_id) {
            $this->template->rander("email_messages/index");
            // echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    /* upadate a task status */

    function save_status() {

        validate_submitted_data(array(
            "id" => "numeric|required",
            "status" => "required"
        ));

        $this->access_only_team_members();
        $data = array(
            "status" => $this->input->post('status')
        );

        $save_id = $this->Email_messages_model->save($data, $this->input->post('id'));

        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, "message" => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, lang('error_occurred')));
        }
    }

    function send($id){
        $email_data = $this->Email_messages_model->get_one($id);
        $ids=explode(',',$email_data->to);
        for($x=0;$x<count($ids);$x++){
            $client= $this->Clients_model->get_one($ids[$x]);
            if($client->email){
                $this->send_email($email_data,$client->email);
            }
        }
        $data = array(
            "status" => 'Sent'
        );
        $this->Email_messages_model->save($data,$id);
        $this->template->rander("email_messages/index");

    }
    function send_email($data,$to){
        $subject = $data->title;
        $from = $this->login_user->email;
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        // Create email headers
        $headers .= 'From: '.$from."\r\n".
            'Reply-To: '.$from."\r\n" .
            'X-Mailer: PHP/' . phpversion();
        $new_message = $data->message;
        try{
            mail($to, $subject, $new_message, $headers);
          }catch(Exception $e){
          var_dump($e);
          }
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        $todo_info = $this->Email_messages_model->get_one($id);
        $this->validate_access($todo_info);

        if ($this->input->post('undo')) {
            if ($this->Email_messages_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Email_messages_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    function list_data() {

        $status = $this->input->post('status') ? implode(",", $this->input->post('status')) : "";
        $options = array(
            "deleted" => 0
        );

        $list_data = $this->Email_messages_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Email_messages_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    private function _make_row($data) {
        $title = modal_anchor(get_uri("email_messages/view/" . $data->id), $data->title, array("class" => "edit", "title" => lang(''), "data-post-id" => $data->id));
       if($data->status=='Draft'){
        $action ="<a href='".get_uri("email_messages/send/").$data->id."'><button class='cen btn btn-primary'>".lang('send')."</button></a>";
       }else{
        $action ="<a href='".get_uri("email_messages/send/").$data->id."'><button class='cen btn btn-info'>".lang('send_again')."</button></a>";
       }
       $edit ="<a href='".get_uri("email_messages/message?id=").$data->id."'><i class='fa fa-pencil'></i></a>";
        return array(
          
            $title,
            $action,
            $data->status,
            $data->date,
            $edit. js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("email_messages/delete"), "data-action" => "delete"))
        );
    }

    function view() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $model_info = $this->Email_messages_model->get_one($this->input->post('id'));

        $this->validate_access($model_info);

        $view_data['model_info'] = $model_info;
        $this->load->view('email_messages/view', $view_data);
    }

}


