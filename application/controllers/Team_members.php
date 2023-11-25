<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Team_members extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->access_only_team_members();
    }

    function chart_array()
    {
        ////////////////
        $rows = $this->Users_model->get_details(array("deleted" => 0, "user_type" => "staff", "status" => "active"))->result();
        function buildTree($list_data, $superior_id = -1)
        {
            $branch = array();


            foreach ($list_data as $data) {
                //var_dump($data->superior_id);
                if ($data->superior_id == $superior_id) {
                    $children = buildTree($list_data, $data->id);
                    if ($children) {
                        $data->children = $children;
                    }
                    $image_url = get_avatar($data->image);
                    $branch[] = array("id" => $data->id, "name" => $data->first_name . " " . $data->last_name, 'title' => $data->job_title, "img" => $image_url, "children" => $children);
                }
            }

            return $branch;
        }

        $tree = buildTree($rows);

        return $tree;


        /////////////////////////
        /*var datascource = {
          'id' : '123',
          'name': 'Lao Lao',
          'title': 'general manager',
          'children': [
            { 'name': 'Bo Miao', 'title': 'department manager','id' : '123', },
            { 'name': 'Su Miao', 'title': 'department manager','id' : '123',
              'children': [
                { 'name': 'Tie Hua', 'title': 'senior engineer','id' : '123', },
                { 'name': 'Hei Hei', 'title': 'senior engineer','id' : '123',
                  'children': [
                    { 'name': 'Pang Pang', 'title': 'engineer','id' : '123', },
                    { 'name': 'Dan Dan', 'title': 'UE engineer','id' : '123', 
                    'children': [
                      { 'name': 'Er Dan', 'title': 'engineer','id' : '123', },
                      { 'name': 'San Dan', 'title': 'engineer','id' : '123',
                        'children': [
                          { 'name': 'Si Dan', 'title': 'intern','id' : '123', },
                          { 'name': 'Wu Dan', 'title': 'intern','id' : '123', }
                        ]
                      }
                    ]}
                  ]
                }
              ]
            },
            { 'name': 'Hong Miao', 'title': 'department manager','id' : '123', },
            { 'name': 'Chun Miao', 'title': 'department manager','id' : '123',
              'children': [
                { 'name': 'Bing Qin', 'title': 'senior engineer','id' : '123', },
                { 'name': 'Yue Yue', 'title': 'senior engineer','id' : '123',
                  'children': [
                    { 'name': 'Er Yue', 'title': 'engineer','id' : '123', },
                    { 'name': 'San Yue', 'title': 'UE engineer','id' : '123', }
                  ]
                }
              ]
            }
          ]
        };*/
    }

    private function can_view_salary_chart()
    {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin) {
                return true;
            } else if (get_array_value($this->login_user->permissions, "can_view_salary_chart") == "1") {
                return true;
            }
        }
    }
    private function can_add_team_member()
    {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin) {
                return true;
            } else if (get_array_value($this->login_user->permissions, "can_add_team_member") == "1") {
                return true;
            }
        }
    }
    private function can_view_team_members_contact_info()
    {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin) {
                return true;
            } else if (get_array_value($this->login_user->permissions, "can_view_team_members_contact_info") == "1") {
                return true;
            }
        }
    }

    private function can_view_team_members_social_links()
    {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin) {
                return true;
            } else if (get_array_value($this->login_user->permissions, "can_view_team_members_social_links") == "1") {
                return true;
            }
        }
    }

    private function update_only_allowed_members($user_id)
    {
        if ($this->can_update_team_members_info($user_id)) {
            return true; //own profile
        } else {
            redirect("forbidden");
        }
    }

    //only admin can change other user's info
    //none admin users can only change his/her own info
    //allowed members can update other members info    
    private function can_update_team_members_info($user_id)
    {
        $access_info = $this->get_access_info("team_member_update_permission");

        if ($this->login_user->id === $user_id) {
            return true; //own profile
        } else if ($access_info->access_type == "all") {
            return true; //has access to change all user's profile
        } else if ($user_id && in_array($user_id, $access_info->allowed_members)) {
            return true; //has permission to update this user's profile
        } else {

            return false;
        }
    }

    //only admin can change other user's info
    //none admin users can only change his/her own info
    private function only_admin_or_own($user_id)
    {
        if ($user_id && ($this->login_user->is_admin || $this->login_user->id === $user_id)) {
            return true;
        } else {
            redirect("forbidden");
        }
    }

    public function index()
    {
        if (!$this->can_view_team_members_list()) {
            redirect("forbidden");
        }

        $view_data["show_contact_info"] = $this->can_view_team_members_contact_info();

        $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("team_members", $this->login_user->is_admin, $this->login_user->user_type);

        $view_data["can_add_team_member"] = $this->can_add_team_member();
        $this->template->rander("team_members/index", $view_data);
    }

    /* open new member modal */

    function modal_form()
    {
        if (!$this->can_add_team_member()) {
            redirect("forbidden");
        }
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['role_dropdown'] = $this->_get_roles_dropdown();
        // $view_data['nationality'] = array("" => "-") + $this->Nationality_model->get_dropdown_list(array("title"));
        $view_data['nationality'] = array("" => "-") + $this->_get_nationality_dropdown();

        $id = $this->input->post('id');
        $options = array(
            "id" => $id,
        );

        $view_data['model_info'] = $this->Users_model->get_details($options)->row();

        $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("team_members", 0, $this->login_user->is_admin, $this->login_user->user_type)->result();

        $this->load->view('team_members/modal_form', $view_data);
    }

    /* save new member */

    function add_team_member()
    {
        if (!$this->can_add_team_member()) {
            redirect("forbidden");
        }

        //check duplicate email address, if found then show an error message
        if ($this->Users_model->is_email_exists($this->input->post('email'))) {
            echo json_encode(array("success" => false, 'message' => lang('duplicate_email')));
            exit();
        }

        validate_submitted_data(array(
            "email" => "required|valid_email",
            "first_name" => "required",
            "last_name" => "required",
            "job_title" => "required",
            "role" => "required"
        ));

        $user_data = array(
            "nationality" => $this->input->post('nationality'),
            "email" => $this->input->post('email'),
            "password" => md5($this->input->post('password')),
            "first_name" => $this->input->post('first_name'),
            "last_name" => $this->input->post('last_name'),
            "is_admin" => $this->input->post('is_admin'),
            "address" => $this->input->post('address'),
            "phone" => $this->input->post('phone'),
            "gender" => $this->input->post('gender'),
            "job_title" => $this->input->post('job_title'),
            "phone" => $this->input->post('phone'),
            "gender" => $this->input->post('gender'),
            "user_type" => "staff",
            "created_at" => get_current_utc_time(),
        );

        //make role id or admin permission 
        $role = $this->input->post('role');
        $role_id = $role;

        if ($role === "admin") {
            $user_data["is_admin"] = 1;
            $user_data["role_id"] = 0;
        } else {
            $user_data["is_admin"] = 0;
            $user_data["role_id"] = $role_id;
        }


        //add a new team member
        $user_id = $this->Users_model->save($user_data);
        if ($user_id) {
            //user added, now add the job info for the user
            $job_data = array(
                "user_id" => $user_id,
                "salary" => $this->input->post('salary') ? $this->input->post('salary') : 0,
                "salary_term" => $this->input->post('salary_term'),
                "working_hours" => $this->input->post('working_hours'),
                "yearly_leaves" => $this->input->post('yearly_leaves'),
                "date_of_hire" => $this->input->post('date_of_hire')
            );
            $this->Users_model->save_job_info($job_data);


            save_custom_fields("team_members", $user_id, $this->login_user->is_admin, $this->login_user->user_type);

            //send login details to user
            if ($this->input->post('email_login_details')) {

                //get the login details template
                $email_template = $this->Email_templates_model->get_final_template("login_info");

                $parser_data["SIGNATURE"] = $email_template->signature;
                $parser_data["USER_FIRST_NAME"] = $user_data["first_name"];
                $parser_data["USER_LAST_NAME"] = $user_data["last_name"];
                $parser_data["USER_LOGIN_EMAIL"] = $user_data["email"];
                $parser_data["USER_LOGIN_PASSWORD"] = $this->input->post('password');
                $parser_data["DASHBOARD_URL"] = base_url();
                $parser_data["LOGO_URL"] = get_logo_url();

                $message = $this->parser->parse_string($email_template->message, $parser_data, TRUE);

                send_app_mail($this->input->post('email'), $email_template->subject, $message);
            }
        }

        if ($user_id) {
            ////
            ////
            echo json_encode(array("success" => true, "data" => $this->_row_data($user_id), 'id' => $user_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }


    /* open invitation modal */

    function invitation_modal()
    {
        $this->access_only_admin();
        $this->load->view('team_members/invitation_modal');
    }

    //send a team member invitation to an email address
    function send_invitation()
    {
        $this->access_only_admin();

        validate_submitted_data(array(
            "email[]" => "required|valid_email"
        ));

        $email_array = $this->input->post('email');
        $email_array = array_unique($email_array);

        //get the send invitation template 
        $email_template = $this->Email_templates_model->get_final_template("team_member_invitation");

        $parser_data["INVITATION_SENT_BY"] = $this->login_user->first_name . " " . $this->login_user->last_name;
        $parser_data["SIGNATURE"] = $email_template->signature;
        $parser_data["SITE_URL"] = get_uri();
        $parser_data["LOGO_URL"] = get_logo_url();

        $send_email = array();

        foreach ($email_array as $email) {
            //make the invitation url with 24hrs validity
            $key = encode_id($this->encryption->encrypt('staff|' . $email . '|' . (time() + (24 * 60 * 60))), "signup");
            $parser_data['INVITATION_URL'] = get_uri("signup/accept_invitation/" . $key);

            //send invitation email
            $message = $this->parser->parse_string($email_template->message, $parser_data, TRUE);

            $send_email[] = send_app_mail($email, $email_template->subject, $message);
        }

        if (!in_array(false, $send_email)) {
            if (count($send_email) != 0 && count($send_email) == 1) {
                echo json_encode(array('success' => true, 'message' => lang("invitation_sent")));
            } else {
                echo json_encode(array('success' => true, 'message' => lang("invitations_sent")));
            }
        } else {
            echo json_encode(array('success' => false, 'message' => lang('error_occurred')));
        }
    }

    //prepere the data for members list
    function list_data()
    {
        if (!$this->can_view_team_members_list()) {
            redirect("forbidden");
        }

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("team_members", $this->login_user->is_admin, $this->login_user->user_type);
        $options = array(
            "status" => $this->input->post("status"),
            "user_type" => "staff",
            "custom_fields" => $custom_fields
        );


        $list_data = $this->Users_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }
        echo json_encode(array("data" => $result));
    }

    //get a row data for member list
    function _row_data($id)
    {
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("team_members", $this->login_user->is_admin, $this->login_user->user_type);
        $options = array(
            "id" => $id,
            "custom_fields" => $custom_fields
        );

        $data = $this->Users_model->get_details($options)->row();
        return $this->_make_row($data, $custom_fields);
    }

    //prepare team member list row
    private function _make_row($data, $custom_fields)
    {
        $image_url = get_avatar($data->image);
        $user_avatar = "<span class='avatar avatar-xs'><img src='$image_url' alt='...'></span>";
        $full_name = $data->first_name . " " . $data->last_name . " ";


        //check contact info view permissions
        $show_cotact_info = $this->can_view_team_members_contact_info();

        $row_data = array(
            $user_avatar,
            get_team_member_profile_link($data->id, $full_name),
            $data->nationality,
            $data->job_title,
            $show_cotact_info ? $data->email : "",
            $show_cotact_info && $data->phone ? $data->phone : "-"
        );

        foreach ($custom_fields as $field) {
            $cf_id = "cfv_" . $field->id;
            $row_data[] = $this->load->view("custom_fields/output_" . $field->field_type, array("value" => $data->$cf_id), true);
        }

        $delete_link = "";
        if ($this->login_user->is_admin && $this->login_user->id != $data->id) {
            $delete_link = js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_team_member'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("team_members/delete"), "data-action" => "delete-confirmation"));
        }

        $row_data[] = $delete_link;

        return $row_data;
    }

    //delete a team member
    function delete()
    {
        $this->access_only_admin();

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        if ($id != $this->login_user->id && $this->Users_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    //show team member's details view
    function view($id = 0, $tab = "")
    {
        if ($id) {
            $show_job_info = $this->get_access_info("job_info")->access_type;
            //if team member's list is disabled, but the user can see his/her own profile.
            if ($show_job_info == "all" || $this->can_view_team_members_list() || $this->login_user->id == $id) {
            } else {
                redirect("forbidden");
            }
            // if (!$this->can_view_team_members_list() && $this->login_user->id != $id) {
            //     redirect("forbidden");
            // }



            //we have an id. view the team_member's profie
            $options = array("id" => $id, "user_type" => "staff");
            $user_info = $this->Users_model->get_details($options)->row();
            if ($user_info) {

                //check which tabs are viewable for current logged in user
                $view_data['show_timeline'] = get_setting("module_timeline") ? true : false;

                $can_update_team_members_info = $this->can_update_team_members_info($id);

                $view_data['show_general_info'] = $can_update_team_members_info;
                $view_data['show_job_info'] = false;

                $view_data['show_account_settings'] = false;
                $view_data['account_setting'] = false;
                $show_attendance_settings = false;
                $show_attendance = false;
                $show_leave = false;

                $expense_access_info = $this->get_access_info("expense");
                $view_data["show_expense_info"] = (get_setting("module_expense") == "1" && $expense_access_info->access_type == "all") ? true : false;

                //admin can access all members attendance and leave
                //none admin users can only access to his/her own information 

                if ($this->login_user->is_admin || $user_info->id === $this->login_user->id) {

                    $show_attendance = true;
                    $show_attendance_settings = true;
                    $show_leave = true;
                    $view_data['show_job_info'] = true;
                    $view_data['show_account_settings'] = true;
                    
                } else {
                    //none admin users but who has access to this team member's attendance and leave can access this info
                    $access_timecard = $this->get_access_info("attendance");
                    if ($access_timecard->access_type === "all" || in_array($user_info->id, $access_timecard->allowed_members)) {
                        $show_attendance = true;
                        $show_attendance_settings = true;
                    }

                    $access_leave = $this->get_access_info("leave");
                    if ($access_leave->access_type === "all" || in_array($user_info->id, $access_leave->allowed_members)) {
                        $show_leave = true;
                    }
                }


                //check module availability
                $view_data['show_attendance'] = $show_attendance && get_setting("module_attendance") ? true : false;
                $view_data['show_attendance_settings'] = $show_attendance_settings && get_setting("module_attendance") ? true : false;
                $view_data['show_leave'] = $show_leave && get_setting("module_leave") ? true : false;


                //check contact info view permissions
                $show_cotact_info = $this->can_view_team_members_contact_info();
                $show_social_links = $this->can_view_team_members_social_links();

                //own info is always visible
                if ($id == $this->login_user->id) {
                    $show_cotact_info = true;
                    $show_social_links = true;
                }

                $view_data['show_cotact_info'] = $show_cotact_info;
                $view_data['show_social_links'] = $show_social_links;


                //show projects tab to admin
                $view_data['show_projects'] = false;
                if ($this->login_user->is_admin) {
                    $view_data['show_projects'] = true;
                }

                $view_data['show_projects_count'] = false;
                if ($this->can_manage_all_projects()) {
                    $view_data['show_projects_count'] = true;
                }

                $view_data['tab'] = $tab; //selected tab
                $view_data['user_info'] = $user_info;
                $view_data['social_link'] = $this->Social_links_model->get_one($id);
                if ($this->get_access_info("job_info")->access_type == "all") {
                    $view_data['show_job_info'] = true;
                }
                if ($this->get_access_info("account_setting")->access_type == "all") {
                    $view_data['account_setting'] = true;
                }
                if ($this->get_access_info("final_settelment")->access_type == "all") {
                    $view_data['final_settelment'] = true;
                }else{
                    $view_data['final_settelment'] = false;
                }
                $this->template->rander("team_members/view", $view_data);
            } else {
                show_404();
            }
        } else {

            if (!$this->can_view_team_members_list()) {
                redirect("forbidden");
            }


            //we don't have any specific id to view. show the list of team_member
            $view_data['team_members'] = $this->Users_model->get_details(array("user_type" => "staff", "status" => "active"))->result();
            $this->template->rander("team_members/profile_card", $view_data);
        }
    }

    //show the job information of a team member
    function job_info($user_id)
    {
        // $this->only_admin_or_own($user_id);
        if ($this->login_user->is_admin || $this->get_access_info("job_info")->access_type == "all") {
        } else {
            redirect("forbidden");
        }
        $options = array("id" => $user_id);
        $user_info = $this->Users_model->get_details($options)->row();

        $view_data['user_id'] = $user_id;
        $view_data['job_info'] = $this->Users_model->get_job_info($user_id);


        $view_data['job_info']->job_title = $user_info->job_title;


        $view_data['user_id'] = $user_id;
        $view_data['user_info'] = $user_info;
        if ($this->get_access_info("job_info")->access_type == "all") {
            $view_data['show_job_info'] = true;
        }
        $this->load->view("team_members/job_info", $view_data);
    }

    //save job information of a team member
    function save_job_info()
    {
        // $this->access_only_admin();
        if ($this->login_user->is_admin || $this->get_access_info("job_info")->access_type == "all") {
        } else {
            redirect("forbidden");
        }

        validate_submitted_data(array(
            "user_id" => "required|numeric"
        ));

        $user_id = $this->input->post('user_id');

        $job_data = array(
            "user_id" => $user_id,
            "salary" => unformat_currency($this->input->post('salary')),
            "housing" => unformat_currency($this->input->post('housing')),
            "working_hours" => unformat_currency($this->input->post('working_hours')),
            "transportation" => unformat_currency($this->input->post('transportation')),
            "telephone" => unformat_currency($this->input->post('telephone')),
            "utility" => unformat_currency($this->input->post('utility')),
            "national" => $this->input->post('national'),
            "pasi" => $this->input->post('pasi'),
            "date_of_hire" => $this->input->post('date_of_hire'),
            "bank_title" => $this->input->post('bank_title'),
            "account_title" => $this->input->post('account_title'),
            "account_no" => $this->input->post('account_no'),
            "yearly_leaves" => $this->input->post('yearly_leaves'),
        );

        //we'll save the job title in users table
        $user_data = array(
            "job_title" => $this->input->post('job_title')
        );

        $this->Users_model->save($user_data, $user_id);
        if ($this->Users_model->save_job_info($job_data)) {
            echo json_encode(array("success" => true, 'message' => lang('record_updated')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //show general information of a team member
    function general_info($user_id)
    {
        $this->update_only_allowed_members($user_id);

        $view_data['user_info'] = $this->Users_model->get_one($user_id);
        $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("team_members", $user_id, $this->login_user->is_admin, $this->login_user->user_type)->result();
        $view_data['users_ddl'] =  array("-1" => "-") + $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id");

        $this->load->view("team_members/general_info", $view_data);
    }

    //save general information of a team member
    function save_general_info($user_id)
    {
        $this->update_only_allowed_members($user_id);

        validate_submitted_data(array(
            "first_name" => "required",
            "last_name" => "required"
        ));

        $user_data = array(
            "first_name" => $this->input->post('first_name'),
            "last_name" => $this->input->post('last_name'),
            // "business_unit" => $this->input->post('business_unit'),
            // "branch_id" => $this->input->post('branch_id'),
            "address" => $this->input->post('address'),
            "phone" => $this->input->post('phone'),
            "nationality" => $this->input->post('nationality'),
            "resident_card_no" => $this->input->post('resident_card_no'),
            "resident_card_expiry" => $this->input->post('resident_card_expiry'),
            "passport_no" => $this->input->post('passport_no'),
            "passport_expiry" => $this->input->post('passport_expiry'),
            "gender" => $this->input->post('gender'),
            "alternative_address" => $this->input->post('alternative_address'),
            "alternative_phone" => $this->input->post('alternative_phone'),
            "dob" => $this->input->post('dob'),
        );

        $user_data = clean_data($user_data);
        $user_info_updated = $this->Users_model->save($user_data, $user_id);
        // var_dump($this->db->error()); 



        save_custom_fields("team_members", $user_id, $this->login_user->is_admin, $this->login_user->user_type);

        if ($user_info_updated) {
            echo json_encode(array("success" => true, 'message' => lang('record_updated')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function org_chart()
    {

        $all_users = $this->Users_model->get_details()->result();
        $user_data = array();
        foreach ($all_users as $key => $user) {
            $user_data = $this->getNestedItems($user->id);
        }

        $tree = $this->chart_array();

        $tree_json = json_encode($tree, JSON_UNESCAPED_SLASHES);
        $view_data['users_data'] = $tree_json;

        $this->template->rander("team_members/org_chart", $view_data);
    }

    function getItemsByParent($ParentID)
    {

        $main_options = array("superior_id" => $ParentID);
        $all_users = $this->Users_model->get_details($main_options)->result();
        $Items = array();

        foreach ($all_users as $key => $user) {

            $options = array("superior_id" => $user->id);
            $row['children'] = $this->Users_model->get_details($options)->result();
            $Items[] = $row;
        }
        return $Items;
    }

    function getNestedItems($id)
    {
        return $this->getItemsByParent($id);
    }




    //show social links of a team member
    function social_links($user_id)
    {
        //important! here id=user_id
        $this->update_only_allowed_members($user_id);

        $view_data['user_id'] = $user_id;
        $view_data['model_info'] = $this->Social_links_model->get_one($user_id);
        $this->load->view("users/social_links", $view_data);
    }

    //save social links of a team member
    function save_social_links($user_id)
    {
        $this->update_only_allowed_members($user_id);

        $id = 0;
        $has_social_links = $this->Social_links_model->get_one($user_id);
        if (isset($has_social_links->id)) {
            $id = $has_social_links->id;
        }

        $social_link_data = array(
            "facebook" => $this->input->post('facebook'),
            "twitter" => $this->input->post('twitter'),
            "linkedin" => $this->input->post('linkedin'),
            "googleplus" => $this->input->post('googleplus'),
            "digg" => $this->input->post('digg'),
            "youtube" => $this->input->post('youtube'),
            "pinterest" => $this->input->post('pinterest'),
            "instagram" => $this->input->post('instagram'),
            "github" => $this->input->post('github'),
            "tumblr" => $this->input->post('tumblr'),
            "vine" => $this->input->post('vine'),
            "user_id" => $user_id,
            "id" => $id ? $id : $user_id
        );

        $social_link_data = clean_data($social_link_data);

        $this->Social_links_model->save($social_link_data, $id);
        echo json_encode(array("success" => true, 'message' => lang('record_updated')));
    }

    //show account settings of a team member
    function account_settings($user_id)
    {
        // $this->only_admin_or_own($user_id);
        if ($this->login_user->is_admin || $this->get_access_info("account_setting")->access_type == "all") {
        } else {
            redirect("forbidden");
        }

        $view_data['user_info'] = $this->Users_model->get_one($user_id);
        if ($view_data['user_info']->is_admin) {
            $view_data['user_info']->role_id = "admin";
        }
        $view_data['role_dropdown'] = $this->_get_roles_dropdown();

     
        $view_data['cost_centers_dropdown'] = array("" => "-") + $this->Cost_centers_model->get_dropdown_list(array("name"));
        

        $this->load->view("users/account_settings", $view_data);
    }

    //show my preference settings of a team member
    function my_preferences()
    {
        $view_data["user_info"] = $this->Users_model->get_one($this->login_user->id);

        //language dropdown
        $view_data['language_dropdown'] = array();
        if (!get_setting("disable_language_selector_for_team_members")) {
            $view_data['language_dropdown'] = get_language_list();
        }

        $view_data["hidden_topbar_menus_dropdown"] = $this->get_hidden_topbar_menus_dropdown();

        $this->load->view("team_members/my_preferences", $view_data);
    }

    function save_my_preferences()
    {
        //setting preferences
        $settings = array("notification_sound_volume", "disable_push_notification", "hidden_topbar_menus");

        if (!get_setting("disable_language_selector_for_team_members")) {
            array_push($settings, "personal_language");
        }

        foreach ($settings as $setting) {
            $value = $this->input->post($setting);
            if (is_null($value)) {
                $value = "";
            }

            $this->Settings_model->save_setting("user_" . $this->login_user->id . "_" . $setting, $value, "user");
        }

        //there was 2 settings in users table.
        //so, update the users table also


        $user_data = array(
            "enable_web_notification" => $this->input->post("enable_web_notification"),
            "enable_email_notification" => $this->input->post("enable_email_notification"),
        );

        $user_data = clean_data($user_data);

        $this->Users_model->save($user_data, $this->login_user->id);

        echo json_encode(array("success" => true, 'message' => lang('settings_updated')));
    }

    function save_personal_language($language)
    {
        if (!get_setting("disable_language_selector_for_team_members") && ($language || $language === "0")) {

            $language = clean_data($language);

            $this->Settings_model->save_setting("user_" . $this->login_user->id . "_personal_language", strtolower($language), "user");
        }
    }

    //prepare the dropdown list of roles
    private function _get_roles_dropdown()
    {
        $role_dropdown = array(
            "0" => lang('team_member'),
            "admin" => lang('admin') //static role
        );
        $roles = $this->Roles_model->get_all()->result();
        foreach ($roles as $role) {
            $role_dropdown[$role->id] = $role->title;
        }
        return $role_dropdown;
    }
    private function _get_nationality_dropdown()
    {
        $nationality_dropdown = [];
        $nationalitys = $this->Nationality_model->get_all()->result();
        foreach ($nationalitys as $nationality) {
            $nationality_dropdown[$nationality->title] = $nationality->title;
        }
        return $nationality_dropdown;
    }

    //save account settings of a team member
    function save_account_settings($user_id)
    {
        if ($this->db->dbprefix == 'erp_demo' || $this->db->dbprefix == 'new_erp_demo') {
            echo json_encode(array("success" => false, 'message' => lang('only_for_subscribed_client')));
            exit();
        };
        // $this->only_admin_or_own($user_id);
        if ($this->login_user->is_admin || $this->get_access_info("account_setting")->access_type == "all") {
        } else {
            redirect("forbidden");
        }
        if ($this->Users_model->is_email_exists($this->input->post('email'), $user_id)) {
            echo json_encode(array("success" => false, 'message' => lang('duplicate_email')));
            exit();
        }
        $account_data = array(
            "email" => $this->input->post('email'),
          
        );

        if( can_view_all_cost_centers_data() ){
            $account_data["cost_center_id"] = $this->input->post('cost_center_id');
        }

        if ($this->login_user->is_admin && $this->login_user->id != $user_id) {
            //only admin user has permission to update team member's role
            //but admin user can't update his/her own role 
            $role = $this->input->post('role');
            $role_id = $role;

            if ($role === "admin") {
                $account_data["is_admin"] = 1;
                $account_data["role_id"] = 0;
            } else {
                $account_data["is_admin"] = 0;
                $account_data["role_id"] = $role_id;
            }

            $account_data['disable_login'] = $this->input->post('disable_login');
            $account_data['status'] = $this->input->post('status') === "inactive" ? "inactive" : "active";
        }

        //don't reset password if user doesn't entered any password
        if ($this->input->post('password')) {
            $account_data['password'] = md5($this->input->post('password'));
        }

        if ($this->Users_model->save($account_data, $user_id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_updated')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //save profile image of a team member
    function save_profile_image($user_id = 0)
    {
        $this->update_only_allowed_members($user_id);
        $user_info = $this->Users_model->get_one($user_id);

        //process the the file which has uploaded by dropzone
        $profile_image = str_replace("~", ":", $this->input->post("profile_image"));

        if ($profile_image) {
            $profile_image = serialize(move_temp_file("avatar.png", get_setting("profile_image_path"), "", $profile_image));

            //delete old file
            delete_app_files(get_setting("profile_image_path"), array(@unserialize($user_info->image)));

            $image_data = array("image" => $profile_image);

            $this->Users_model->save($image_data, $user_id);
            echo json_encode(array("success" => true, 'message' => lang('profile_image_changed')));
        }

        //process the the file which has uploaded using manual file submit
        if ($_FILES) {
            $profile_image_file = get_array_value($_FILES, "profile_image_file");
            $image_file_name = get_array_value($profile_image_file, "tmp_name");
            if ($image_file_name) {
                $profile_image = serialize(move_temp_file("avatar.png", get_setting("profile_image_path"), "", $image_file_name));

                //delete old file
                delete_app_files(get_setting("profile_image_path"), array(@unserialize($user_info->image)));

                $image_data = array("image" => $profile_image);
                $this->Users_model->save($image_data, $user_id);
                echo json_encode(array("success" => true, 'message' => lang('profile_image_changed')));
            }
        }
    }

    //show projects list of a team member
    function projects_info($user_id)
    {
        if ($user_id) {
            $view_data['user_id'] = $user_id;
            $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("projects", $this->login_user->is_admin, $this->login_user->user_type);
            $this->load->view("team_members/projects_info", $view_data);
        }
    }

    //show attendance list of a team member
    function attendance_info($user_id)
    {
        if ($user_id) {
            $view_data['user_id'] = $user_id;
            $this->load->view("team_members/attendance_info", $view_data);
        }
    }

    //show weekly attendance list of a team member
    function weekly_attendance()
    {
        $this->load->view("team_members/weekly_attendance");
    }

    //show weekly attendance list of a team member
    function custom_range_attendance()
    {
        $this->load->view("team_members/custom_range_attendance");
    }

    //show attendance summary of a team member
    function attendance_summary($user_id)
    {
        $view_data["user_id"] = $user_id;
        $this->load->view("team_members/attendance_summary", $view_data);
    }

    //show leave list of a team member
    function leave_info($applicant_id)
    {
        if ($applicant_id) {
            $view_data['applicant_id'] = $applicant_id;
            $this->load->view("team_members/leave_info", $view_data);
        }
    }

    //show yearly leave list of a team member
    function yearly_leaves()
    {
        $this->load->view("team_members/yearly_leaves");
    }

    private function can_approve()
    {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "expenses") == "all") {
                return true;
            }
        }
    }

    //show yearly leave list of a team member
    function expense_info($user_id)
    {
        $view_data["user_id"] = $user_id;
        $view_data["can_approve"] = $this->can_approve();
        if (!$this->login_user->is_admin) {
            $view_data['can_create_module'] = get_array_value($this->login_user->permissions, 'can_create_expenses');
        } else {
            $view_data['can_create_module'] = 1;
        }
        $this->load->view("team_members/expenses", $view_data);
    }

    /* load files tab */

    function files($user_id)
    {

        $this->update_only_allowed_members($user_id);

        $options = array("user_id" => $user_id);
        $view_data['files'] = $this->General_files_model->get_details($options)->result();
        $view_data['user_id'] = $user_id;
        $this->load->view("team_members/files/index", $view_data);
    }

    /* file upload modal */

    function file_modal_form()
    {
        $view_data['model_info'] = $this->General_files_model->get_one($this->input->post('id'));
        $user_id = $this->input->post('user_id') ? $this->input->post('user_id') : $view_data['model_info']->user_id;

        $this->update_only_allowed_members($user_id);

        $view_data['user_id'] = $user_id;
        $this->load->view('team_members/files/modal_form', $view_data);
    }

    /* save file data and move temp file to parmanent file directory */

    function save_file()
    {


        validate_submitted_data(array(
            "id" => "numeric",
            "user_id" => "required|numeric"
        ));

        $user_id = $this->input->post('user_id');
        $this->update_only_allowed_members($user_id);


        $files = $this->input->post("files");
        $success = false;
        $now = get_current_utc_time();

        $target_path = getcwd() . "/" . get_general_file_path("team_members", $user_id);

        //process the fiiles which has been uploaded by dropzone
        if ($files && get_array_value($files, 0)) {
            foreach ($files as $file) {
                $file_name = $this->input->post('file_name_' . $file);
                $file_info = move_temp_file($file_name, $target_path);
                if ($file_info) {
                    $data = array(
                        "user_id" => $user_id,
                        "file_name" => get_array_value($file_info, 'file_name'),
                        "file_id" => get_array_value($file_info, 'file_id'),
                        "service_type" => get_array_value($file_info, 'service_type'),
                        "description" => $this->input->post('description_' . $file),
                        "file_size" => $this->input->post('file_size_' . $file),
                        "created_at" => $now,
                        "uploaded_by" => $this->login_user->id
                    );
                    $success = $this->General_files_model->save($data);
                } else {
                    $success = false;
                }
            }
        }


        if ($success) {
            echo json_encode(array("success" => true, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    /* list of files, prepared for datatable  */

    function files_list_data($user_id = 0)
    {
        $options = array("user_id" => $user_id);

        $this->update_only_allowed_members($user_id);

        $list_data = $this->General_files_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_file_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_file_row($data)
    {
        $file_icon = get_file_icon(strtolower(pathinfo($data->file_name, PATHINFO_EXTENSION)));

        $image_url = get_avatar($data->uploaded_by_user_image);
        $uploaded_by = "<span class='avatar avatar-xs mr10'><img src='$image_url' alt='...'></span> $data->uploaded_by_user_name";

        $uploaded_by = get_team_member_profile_link($data->uploaded_by, $uploaded_by);

        $description = "<div class='pull-left'>" .
            js_anchor(remove_file_prefix($data->file_name), array('title' => "", "data-toggle" => "app-modal", "data-sidebar" => "0", "data-url" => get_uri("team_members/view_file/" . $data->id)));

        if ($data->description) {
            $description .= "<br /><span>" . $data->description . "</span></div>";
        } else {
            $description .= "</div>";
        }

        $options = anchor(get_uri("team_members/download_file/" . $data->id), "<i class='fa fa fa-cloud-download'></i>", array("title" => lang("download")));

        $options .= js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_file'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("team_members/delete_file"), "data-action" => "delete-confirmation"));


        return array(
            $data->id,
            "<div class='fa fa-$file_icon font-22 mr10 pull-left'></div>" . $description,
            convert_file_size($data->file_size),
            $uploaded_by,
            format_to_datetime($data->created_at),
            $options
        );
    }

    function view_file($file_id = 0)
    {
        $file_info = $this->General_files_model->get_details(array("id" => $file_id))->row();

        if ($file_info) {

            if (!$file_info->user_id) {
                redirect("forbidden");
            }

            $this->update_only_allowed_members($file_info->user_id);

            $view_data['can_comment_on_files'] = false;

            $view_data["file_url"] = get_source_url_of_file(make_array_of_file($file_info), get_general_file_path("team_members", $file_info->user_id));
            $view_data["is_image_file"] = is_image_file($file_info->file_name);
            $view_data["is_google_preview_available"] = is_google_preview_available($file_info->file_name);
            $view_data["is_viewable_video_file"] = is_viewable_video_file($file_info->file_name);
            $view_data["is_google_drive_file"] = ($file_info->file_id && $file_info->service_type == "google") ? true : false;

            $view_data["file_info"] = $file_info;
            $view_data['file_id'] = $file_id;
            $this->load->view("team_members/files/view", $view_data);
        } else {
            show_404();
        }
    }

    /* download a file */

    function download_file($id)
    {

        $file_info = $this->General_files_model->get_one($id);

        if (!$file_info->user_id) {
            redirect("forbidden");
        }
        $this->update_only_allowed_members($file_info->user_id);

        //serilize the path
        $file_data = serialize(array(make_array_of_file($file_info)));

        download_app_files(get_general_file_path("team_members", $file_info->user_id), $file_data);
    }

    /* upload a post file */

    function upload_file()
    {
        upload_file_to_temp();
    }

    /* check valid file for user */

    function validate_file()
    {
        return validate_post_file($this->input->post("file_name"));
    }

    /* delete a file */

    function delete_file()
    {

        $id = $this->input->post('id');
        $info = $this->General_files_model->get_one($id);

        if (!$info->user_id) {
            redirect("forbidden");
        }

        $this->update_only_allowed_members($info->user_id);

        if ($this->General_files_model->delete($id)) {

            //delete the files
            delete_app_files(get_general_file_path("team_members", $info->user_id), array(make_array_of_file($info)));

            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    function attendance_settings($user_id)
    {

        $this->only_admin_or_own($user_id);
        if ($user_id) {
            if (isset($_COOKIE['Device'])) {
                $view_data['status'] = "active";
                $view_data['status_label'] = "label-success";
            } else {
                $view_data['status'] = "inactive";
                $view_data['status_label'] = "label-warning";
            }
            $view_data['user_id'] = $user_id;
            $this->load->view("users/attendance_settings", $view_data);
        }
    }

    function save_attendance_settings()
    {

        $this->only_admin_or_own($this->login_user->id);

        if ($this->input->post("password") == get_setting("attendance_password") && is_numeric($this->input->post("allowed_distance"))) {

            $cookie_name = "Device";
            $cookie_value = "123";

            setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");

            $this->Settings_model->save_setting('user_' . $this->login_user->id . '_location', $this->input->post("location"), "user");
            $this->Settings_model->save_setting('user_' . $this->login_user->id . '_allowed_distance', $this->input->post("allowed_distance"), "user");

            echo json_encode(array("success" => true, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => ('Wrong Password or invalid allowed distance')));
        }
    }

    function employees_salary_chart()
    {
        if (!$this->can_view_salary_chart()) {
            redirect("forbidden");
        }

        $this->template->rander("team_members/salary_chart");
    }



    function list_data_salary_chart()
    {
        $options = array("payroll" => 1, "status" => "active", "user_type" => "staff");
        $list_data = $this->Users_model->get_details($options)->result();

        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row_salary_chart($data);
        }

        echo json_encode(array("data" => $result));
    }

    private function _make_row_salary_chart($data)
    {
        $image_url = get_avatar($data->image);
        $user_avatar = "<span class='avatar avatar-xs'><img src='$image_url' alt='...'></span>";
        $full_name = $data->first_name . " " . $data->last_name . " ";

        $row_data = array(
            $data->id,
            $user_avatar,
            get_team_member_profile_link($data->id, $full_name),
            $data->job_title,
            bcdiv($data->salary, 1, 3),
            bcdiv($data->housing, 1, 3),
            bcdiv($data->transportation, 1, 3),
            bcdiv($data->telephone, 1, 3),
            bcdiv($data->utility, 1, 3),
            bcdiv(get_gross_salary($data->id), 1, 3),
            $data->date_of_hire,
            bcdiv(get_employement_years($data->id), 1, 3),
            bcdiv(get_gratuity($data->id), 1, 3),
            bcdiv(get_company_pasi_share($data->id), 1, 3),
            bcdiv(get_employee_pasi_share($data->id), 1, 3),
            bcdiv(get_company_job_s_share($data->id), 1, 3),
            bcdiv(get_employee_job_s_share($data->id), 1, 3)
        );

        return $row_data;
    }
}

/* End of file team_member.php */
/* Location: ./application/controllers/team_member.php */