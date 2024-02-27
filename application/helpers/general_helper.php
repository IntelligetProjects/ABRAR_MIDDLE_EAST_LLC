<?php

/**
 * use this to print link location
 *
 * @param string $uri
 * @return print url
 */
if (!function_exists('echo_uri')) {

    function echo_uri($uri = "")
    {
        echo get_uri($uri);
    }
}

/**
 * prepare uri
 * 
 * @param string $uri
 * @return full url 
 */
if (!function_exists('get_uri')) {

    function get_uri($uri = "")
    {
        $ci = get_instance();
        $index_page = $ci->config->item('index_page');
        return base_url($index_page . '/' . $uri);
    }
}

/**
 * use this to print file path
 * 
 * @param string $uri
 * @return full url of the given file path
 */
if (!function_exists('get_file_uri')) {

    function get_file_uri($uri = "")
    {
        return base_url($uri);
    }
}

/*ONE SIGNAL NOTIFICATIONS SENDING FUNCTION*/


if (!function_exists("send_onesignal")) {
    function send_onesignal($body, $link, $to, $notification_id, $imgUrl)
    {
        $ci = get_instance();
        $content      = array(
            "en" => $body
        );
        $hashes_array = array();
        array_push($hashes_array, array(
            "id" => "like-button",
            "text" => "View It"
        ));
        $fields = array(
            'app_id' => "ac26d81f-2b22-43eb-839a-eb0709c31f58",
            'included_segments' => array(
                'All'
            ),

            'filters' => array(array('field' => 'tag', 'key' => 'user_id', 'relation' => '=', 'value' => $to)),
            'data' => array("targetUrl" => $link),
            'big_picture' => $imgUrl,
            'chrome_big_picture' => $imgUrl,
            'contents' => $content
        );

        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Basic MjZiYmFmYzEtNGYwNC00NWI5LWIzNDktNmUxNDU3ZTVkZWIx'
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

        /*$data = array("desk_show" => 1);
        if($notification_id)
        {
            $ci->Notifications_model->save($data, $notification_id); 
        }*/
    }
}

/**
 * get the url of user avatar
 * 
 * @param string $image_name
 * @return url of the avatar of given image reference
 */
if (!function_exists('get_avatar')) {

    function get_avatar($image = "")
    {
        if ($image === "system_bot") {
            return base_url("assets/images/avatar-bot.jpg");
        } else if ($image) {
            $file = @unserialize($image);
            if (is_array($file)) {
                return get_source_url_of_file($file, get_setting("profile_image_path") . "/", "thumbnail");
            } else {
                return base_url(get_setting("profile_image_path")) . "/" . $image;
            }
        } else {
            return base_url("assets/images/avatar.jpg");
        }
    }
}

/**
 * link the css files 
 * 
 * @param array $array
 * @return print css links
 */
if (!function_exists('load_css')) {

    function load_css(array $array)
    {
        $version = get_setting("app_version");

        foreach ($array as $uri) {
            echo "<link rel='stylesheet' type='text/css' href='" . base_url($uri) . "?v=$version' />";
        }
    }
}


/**
 * link the javascript files 
 * 
 * @param array $array
 * @return print js links
 */
if (!function_exists('load_js')) {

    function load_js(array $array)
    {
        $version = get_setting("app_version");

        foreach ($array as $uri) {
            echo "<script type='text/javascript'  src='" . base_url($uri) . "?v=$version'></script>";
        }
    }
}

/**
 * check the array key and return the value 
 * 
 * @param array $array
 * @return extract array value safely
 */
if (!function_exists('get_array_value')) {

    function get_array_value(array $array, $key)
    {
        if (array_key_exists($key, $array)) {
            return $array[$key];
        }
    }
}

/**
 * prepare a anchor tag for any js request
 * 
 * @param string $title
 * @param array $attributes
 * @return html link of anchor tag
 */
if (!function_exists('js_anchor')) {

    function js_anchor($title = '', $attributes = '')
    {
        $title = (string) $title;
        $html_attributes = "";

        if (is_array($attributes)) {
            foreach ($attributes as $key => $value) {
                $html_attributes .= ' ' . $key . '="' . $value . '"';
            }
        }

        return '<a href="#!"' . $html_attributes . '>' . $title . '</a>';
    }
}

/**
 * prepare a anchor tag for any link_anchor request
 * 
 * @param string $title
 * @param array $attributes
 * @return html link of anchor tag
 */
if (!function_exists('link_anchor')) {

    function link_anchor($link = '', $title = '', $attributes = '')
    {
        $title = (string) $title;
        $html_attributes = "";

        if (is_array($attributes)) {
            foreach ($attributes as $key => $value) {
                $html_attributes .= ' ' . $key . '="' . $value . '"';
            }
        }

        return '<a href="' . $link . '"' . $html_attributes . '>' . $title . '</a>';
    }
}


/**
 * prepare a anchor tag for modal 
 * 
 * @param string $url
 * @param string $title
 * @param array $attributes
 * @return html link of anchor tag
 */
if (!function_exists('modal_anchor')) {

    function modal_anchor($url, $title = '', $attributes = '')
    {
        $attributes["data-act"] = "ajax-modal";
        if (get_array_value($attributes, "data-modal-title")) {
            $attributes["data-title"] = get_array_value($attributes, "data-modal-title");
        } else {
            $attributes["data-title"] = get_array_value($attributes, "title");
        }
        $attributes["data-action-url"] = $url;

        return js_anchor($title, $attributes);
    }
}

/**
 * prepare a anchor tag for ajax request
 * 
 * @param string $url
 * @param string $title
 * @param array $attributes
 * @return html link of anchor tag
 */
if (!function_exists('ajax_anchor')) {

    function ajax_anchor($url, $title = '', $attributes = '')
    {
        $attributes["data-act"] = "ajax-request";
        $attributes["data-action-url"] = $url;
        return js_anchor($title, $attributes);
    }
}

/**
 * get the selected menu 
 * 
 * @param string $url
 * @param array $submenu
 * @return string "active" indecating the active page
 */
if (!function_exists('active_menu')) {

    function active_menu($menu = "", $submenu = array())
    {
        $ci = &get_instance();
        $controller_name = strtolower(get_class($ci));

        //compare with controller name. if not found, check in submenu values
        if ($menu === $controller_name) {
            return "active";
        } else if ($submenu && count($submenu)) {
            foreach ($submenu as $sub_menu) {
                if (get_array_value($sub_menu, "name") === $controller_name) {
                    return "active";
                } else if (get_array_value($sub_menu, "category") === $controller_name) {
                    return "active";
                }
            }
        }
    }
}

/**
 * get the selected submenu
 * 
 * @param string $submenu
 * @param boolean $is_controller
 * @return string "active" indecating the active sub page
 */
if (!function_exists('active_submenu')) {

    function active_submenu($submenu = "", $is_controller = false)
    {
        $ci = &get_instance();
        //if submenu is a controller then compare with controller name, otherwise compare with method name
        if ($is_controller && $submenu === strtolower(get_class($ci))) {
            return "active";
        } else if ($submenu === strtolower($ci->router->method)) {
            return "active";
        }
    }
}

/**
 * get the defined config value by a key
 * @param string $key
 * @return config value
 */
if (!function_exists('get_setting')) {

    function get_setting($key = "")
    {
        $ci = get_instance();
        return $ci->config->item($key);
    }
}
if (!function_exists('set_setting')) {

    function set_setting($key = "", $value = "")
    {
        $ci = get_instance();
        $ci->Settings_model->save_setting($key, $value);
    }
}



/**
 * check if a string starts with a specified sting
 * 
 * @param string $string
 * @param string $needle
 * @return true/false
 */
if (!function_exists('starts_with')) {

    function starts_with($string, $needle)
    {
        $string = $string;
        return $needle === "" || strrpos($string, $needle, -strlen($string)) !== false;
    }
}

/**
 * check if a string ends with a specified sting
 * 
 * @param string $string
 * @param string $needle
 * @return true/false
 */
if (!function_exists('ends_with')) {

    function ends_with($string, $needle)
    {
        return $needle === "" || (($temp = strlen($string) - strlen($string)) >= 0 && strpos($string, $needle, $temp) !== false);
    }
}

/**
 * create a encoded id for sequrity pupose 
 * 
 * @param string $id
 * @param string $salt
 * @return endoded value
 */
if (!function_exists('encode_id')) {

    function encode_id($id, $salt)
    {
        $ci = get_instance();
        $id = $ci->encryption->encrypt($id . $salt);
        $id = str_replace("=", ".", $id);
        $id = str_replace("+", "_", $id);
        $id = str_replace("/", "-", $id);
        return $id;
    }
}


/**
 * decode the id which made by encode_id()
 * 
 * @param string $id
 * @param string $salt
 * @return decoded value
 */
if (!function_exists('decode_id')) {

    function decode_id($id, $salt)
    {
        $ci = get_instance();
        $id = str_replace("_", "+", $id);
        $id = str_replace(".", "=", $id);
        $id = str_replace("-", "/", $id);
        $id = $ci->encryption->decrypt($id);

        if ($id && strpos($id, $salt) != false) {
            return str_replace($salt, "", $id);
        } else {
            return "";
        }
    }
}

/**
 * decode html data which submited using a encode method of encodeAjaxPostData() function
 * 
 * @param string $html
 * @return htmle
 */
if (!function_exists('decode_ajax_post_data')) {

    function decode_ajax_post_data($html)
    {
        $html = str_replace("~", "=", $html);
        $html = str_replace("^", "&", $html);
        return $html;
    }
}

/**
 * check if fields has any value or not. and generate a error message for null value
 * 
 * @param array $fields
 * @return throw error for bad value
 */
if (!function_exists('check_required_hidden_fields')) {

    function check_required_hidden_fields($fields = array())
    {
        $has_error = false;
        foreach ($fields as $field) {
            if (!$field) {
                $has_error = true;
            }
        }
        if ($has_error) {
            echo json_encode(array("success" => false, 'message' => lang('something_went_wrong')));
            exit();
        }
    }
}

/**
 * convert simple link text to clickable link
 * @param string $text
 * @return html link
 */
if (!function_exists('link_it')) {

    function link_it($text)
    {
        if ($text != strip_tags($text)) {
            //contains HTML, return the actual text
            return $text;
        } else {
            return preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s]?[^\s]+)?)?)@', '<a href="$1" target="_blank">$1</a>', $text);
        }
    }
}

/**
 * convert mentions to link or link text
 * @param string $text containing text with mentioned brace
 * @param string $return_type indicates what to return (link or text)
 * @return text with link or link text
 */
if (!function_exists('convert_mentions')) {

    function convert_mentions($text, $convert_links = true)
    {

        preg_match_all('#\@\[(.*?)\]#', $text, $matches);

        $members = array();

        $mentions = get_array_value($matches, 1);
        if ($mentions && count($mentions)) {
            foreach ($mentions as $mention) {
                $user = explode(":", $mention);
                if ($convert_links) {
                    $user_id = get_array_value($user, 1);
                    $members[] = get_team_member_profile_link($user_id, trim($user[0]));
                } else {
                    $members[] = $user[0];
                }
            }
        }

        if ($convert_links) {
            $text = nl2br(link_it($text));
        } else {
            $text = nl2br($text);
        }

        $text = preg_replace_callback('/\[[^]]+\]/', function ($matches) use (&$members) {
            return array_shift($members);
        }, $text);

        return $text;
    }
}

/**
 * get all the use_ids from comment mentions
 * @param string $text
 * @return array of user_ids
 */
if (!function_exists('get_members_from_mention')) {

    function get_members_from_mention($text)
    {

        preg_match_all('#\@\[(.*?)\]#', $text, $matchs);

        //find the user ids.
        $user_ids = array();
        $mentions = get_array_value($matchs, 1);

        if ($mentions && count($mentions)) {
            foreach ($mentions as $mention) {
                $user = explode(":", $mention);
                $user_id = get_array_value($user, 1);
                if ($user_id) {
                    array_push($user_ids, $user_id);
                }
            }
        }

        return $user_ids;
    }
}

/**
 * send mail
 * 
 * @param string $to
 * @param string $subject
 * @param string $message
 * @param array $optoins
 * @return true/false
 */
if (!function_exists('send_app_mail')) {

    function send_app_mail($to, $subject, $message, $optoins = array())
    {
        $email_config = array(
            'charset' => 'utf-8',
            'mailtype' => 'html'
        );

        //check mail sending method from settings
        if (get_setting("email_protocol") === "smtp") {
            $email_config["protocol"] = "smtp";
            $email_config["smtp_host"] = get_setting("email_smtp_host");
            $email_config["smtp_port"] = get_setting("email_smtp_port");
            $email_config["smtp_user"] = get_setting("email_smtp_user");
            $email_config["smtp_pass"] = get_setting("email_smtp_pass");
            $email_config["smtp_crypto"] = get_setting("email_smtp_security_type");

            if (!$email_config["smtp_crypto"]) {
                $email_config["smtp_crypto"] = "tls"; //for old clients, we have to set this by defaultsssssssss
            }

            if ($email_config["smtp_crypto"] === "none") {
                $email_config["smtp_crypto"] = "";
            }
        }

        $ci = get_instance();
        $ci->load->library('email', $email_config);
        $ci->email->clear(true); //clear previous message and attachment
        $ci->email->set_newline("\r\n");
        $ci->email->from(get_setting("email_sent_from_address"), get_setting("email_sent_from_name"));
        $ci->email->to($to);
        $ci->email->subject($subject);
        $ci->email->message($message);

        //add attachment
        $attachments = get_array_value($optoins, "attachments");
        if (is_array($attachments)) {
            foreach ($attachments as $value) {
                $file_path = get_array_value($value, "file_path");
                $file_name = get_array_value($value, "file_name");
                $ci->email->attach(trim($file_path), "attachment", $file_name);
            }
        }

        //check reply-to
        $reply_to = get_array_value($optoins, "reply_to");
        if ($reply_to) {
            $ci->email->reply_to($reply_to);
        }

        //check cc
        $cc = get_array_value($optoins, "cc");
        if ($cc) {
            $ci->email->cc($cc);
        }

        //check bcc
        $bcc = get_array_value($optoins, "bcc");
        if ($bcc) {
            $ci->email->bcc($bcc);
        }

        //send email
        if ($ci->email->send()) {
            return true;
        } else {
            //show error message in none production version
            if (ENVIRONMENT !== 'production') {
                show_error($ci->email->print_debugger());
            }
            return false;
        }
    }
}


/**
 * get users ip address
 * 
 * @return ip
 */
if (!function_exists('get_real_ip')) {

    function get_real_ip()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}

/**
 * check if it's localhost
 * 
 * @return boolean
 */
if (!function_exists('is_localhost')) {

    function is_localhost()
    {
        $known_localhost_ip = array(
            '127.0.0.1',
            '::1'
        );
        if (in_array(get_real_ip(), $known_localhost_ip)) {
            return true;
        }
    }
}


/**
 * convert string to url
 * 
 * @param string $address
 * @return url
 */
if (!function_exists('to_url')) {

    function to_url($address = "")
    {
        if (strpos($address, 'http://') === false && strpos($address, 'https://') === false) {
            $address = "http://" . $address;
        }
        return $address;
    }
}

/**
 * validate post data using the codeigniter's form validation method
 * 
 * @param string $address
 * @return throw error if foind any inconsistancy
 */
if (!function_exists('validate_submitted_data')) {

    function validate_submitted_data($fields = array())
    {
        $ci = get_instance();
        foreach ($fields as $field_name => $requirement) {
            $ci->form_validation->set_rules($field_name, $field_name, $requirement);
        }

        if ($ci->form_validation->run() == FALSE) {
            if (ENVIRONMENT === 'production') {
                $message = lang('something_went_wrong');
            } else {
                $message = validation_errors();
            }
            echo json_encode(array("success" => false, 'message' => $message));
            exit();
        }
    }
}


/**
 * validate post data using the codeigniter's form validation method
 * 
 * @param string $address
 * @return throw error if foind any inconsistancy
 */
if (!function_exists('validate_numeric_value')) {

    function validate_numeric_value($value = 0)
    {
        if ($value && !is_numeric($value)) {
            die("Invalid value");
        }
    }
}

/**
 * team members profile anchor. only clickable to team members
 * client's will see a none clickable link
 * 
 * @param string $id
 * @param string $name
 * @param array $attributes
 * @return html link
 */
if (!function_exists('get_team_member_profile_link')) {

    function get_team_member_profile_link($id = 0, $name = "", $attributes = array())
    {
        $ci = get_instance();
        if ($ci->login_user->user_type === "staff") {
            return anchor("team_members/view/" . $id, $name, $attributes);
        } else {
            return js_anchor($name, $attributes);
        }
    }
}


/**
 * team members profile anchor. only clickable to team members
 * client's will see a none clickable link
 * 
 * @param string $id
 * @param string $name
 * @param array $attributes
 * @return html link
 */
if (!function_exists('get_client_contact_profile_link')) {

    function get_client_contact_profile_link($id = 0, $name = "", $attributes = array())
    {
        return anchor("clients/contact_profile/" . $id, $name, $attributes);
    }
}


/**
 * return a colorful label accroding to invoice status
 * 
 * @param Object $invoice_info
 * @return html
 */
if (!function_exists('get_invoice_status_label')) {

    function get_invoice_status_label($invoice_info, $return_html = true)
    {
        $invoice_status_class = "label-default";
        $status = "not_paid";
        $now = get_my_local_time("Y-m-d");

        //ignore the hidden value. check only 2 decimal place.
        $intax = isset($invoice_info->tax_value) ? $invoice_info->tax_value : 0;
        // $invoice_info->invoice_value = floor(($invoice_info->invoice_value + $intax) * 100) / 100;
        $invoice_info->invoice_value = $invoice_info->invoice_value + $invoice_info->tax_after_discount;
        // echo $invoice_info->payment_received .'- '.$invoice_info->invoice_value .'|';

        if ($invoice_info->status == "cancelled") {
            $invoice_status_class = "label-danger";
            $status = "cancelled";
        } else if ($invoice_info->status != "draft" && $invoice_info->due_date < $now && $invoice_info->payment_received < $invoice_info->invoice_value) {
            $invoice_status_class = "label-danger";
            $status = "overdue";
        } else if ($invoice_info->status !== "draft" && $invoice_info->payment_received <= 0) {
            $invoice_status_class = "label-warning";
            $status = "not_paid";
        } else if ($invoice_info->payment_received * 1 && $invoice_info->payment_received >= $invoice_info->invoice_value) {
            $invoice_status_class = "label-success";
            $status = "fully_paid";
        } else if ($invoice_info->payment_received > 0 && $invoice_info->payment_received < $invoice_info->invoice_value) {
            $invoice_status_class = "label-primary";
            $status = "partially_paid";
        }
        // echo $invoice_info->payment_received.'-'.$invoice_info->id.'-'.$invoice_info->invoice_value.'-';


        // } else if ($invoice_info->status === "draft") {
        //     $invoice_status_class = "label-default";
        //     $status = "draft";
        // }

        $invoice_status = "<span class='mt0 label $invoice_status_class large clickable'>" . lang($status) . "</span>";
        if ($return_html) {
            return $invoice_status;
        } else {
            return $status;
        }
    }
}

/**
 * return a colorful label accroding to invoice status
 * 
 * @param Object $invoice_info
 * @return html
 */
if (!function_exists('get_delivery_status_label')) {

    function get_delivery_status_label($invoice_info, $return_html = true)
    {
        $ci = get_instance();
        $invoice_status_class = "label-default";
        $status = "not_delivered";
        $now = get_my_local_time("Y-m-d");
        $do = $ci->Delivery_notes_model->get_one_where(array("invoice_id" => $invoice_info->id, "deleted" => 0));
        $delivery_total_items = $ci->Delivery_note_items_model->get_sum_items($invoice_info->id)->row();
        $invoice_total_items = $ci->Invoice_items_model->get_sum_items($invoice_info->id)->row();

        if ($invoice_info->delivery_status == "not_delivered") {
            $invoice_status_class = "label-default";
            $status = "not_delivered";
        } else if ($invoice_info->delivery_status === "delivered" && $delivery_total_items->sum == $invoice_total_items->sum) {
            $invoice_status_class = "label-success";
            $status = "delivered";
        } else if ($invoice_info->delivery_status === "delivered" && $delivery_total_items->sum < $invoice_total_items->sum) {
            $invoice_status_class = "label-primary";
            $status = "partially_delivered";
        } else if ($invoice_info->delivery_status === "delivered" && $delivery_total_items->sum > $invoice_total_items->sum) {
            $invoice_status_class = "label-danger";
            $status = "over_delivery";
        }

        $delivery_status = "<span class='mt0 label $invoice_status_class large clickable'>" . lang($status) . "</span>";
        if ($return_html) {
            return $delivery_status;
        } else {
            return $status;
        }
    }
}


/**
 * return a colorful label accroding to invoice status
 * 
 * @param Object $invoice_info
 * @return html
 */
if (!function_exists('get_shipment_status_label')) {

    function get_shipment_status_label($po_info, $return_html = true)
    {
        $ci = get_instance();
        $invoice_status_class = "label-default";
        $status = "not_delivered";
        $now = get_my_local_time("Y-m-d");
        $delivery_total_items = $ci->Shipment_items_model->get_sum_items($po_info->id)->row();
        $invoice_total_items = $ci->Purchase_order_items_model->get_sum_items($po_info->id)->row();

        /*        var_dump($delivery_total_items->sum);
        var_dump($invoice_total_items->sum);*/

        if ($delivery_total_items->sum == 0) {
            $invoice_status_class = "label-default";
            $status = "not_delivered";
        } else if ($delivery_total_items->sum == $invoice_total_items->sum) {
            $invoice_status_class = "label-success";
            $status = "delivered";
        } else if ($delivery_total_items->sum < $invoice_total_items->sum) {
            $invoice_status_class = "label-primary";
            $status = "partially_delivered";
        } else if ($delivery_total_items->sum > $invoice_total_items->sum) {
            $invoice_status_class = "label-danger";
            $status = "over_delivery";
        }

        $delivery_status = "<span class='mt0 label $invoice_status_class large clickable'>" . lang($status) . "</span>";
        if ($return_html) {
            return $delivery_status;
        } else {
            return $status;
        }
    }
}

/**
 * return a colorful label accroding to invoice status
 * 
 * @param Object $invoice_info
 * @return html
 */
if (!function_exists('get_do_status_label')) {

    function get_do_status_label($info, $return_html = true)
    {
        $status_class = "label-default";
        $status = "draft";

        if ($info->status == "request_approval") {
            $status_class = "label-warning";
            $status = "request_approval";
        } else if ($info->status === "approved") {
            $status_class = "label-success";
            $status = "approved";
        }

        $delivery_status = "<span class='mt0 label $status_class large clickable'>" . lang($status) . "</span>";
        if ($return_html) {
            return $delivery_status;
        } else {
            return $status;
        }
    }
}

/**
 * return a colorful label accroding to invoice status
 * 
 * @param Object $invoice_info
 * @return html
 */
if (!function_exists('get_shipment_status_label')) {

    function get_shipment_status_label($info, $return_html = true)
    {
        $status_class = "label-default";
        $status = "draft";

        if ($info->status == "request_approval") {
            $status_class = "label-warning";
            $status = "request_approval";
        } else if ($info->status === "approved") {
            $status_class = "label-success";
            $status = "approved";
        }

        $delivery_status = "<span class='mt0 label $status_class large clickable'>" . lang($status) . "</span>";
        if ($return_html) {
            return $delivery_status;
        } else {
            return $status;
        }
    }
}

/**
 * return a colorful label accroding to invoice status
 * 
 * @param Object $invoice_info
 * @return html
 */
if (!function_exists('get_payment_status_label')) {

    function get_payment_status_label($invoice_info, $return_html = true)
    {
        $status_class = "label-default";
        $status = "draft";
        $now = get_my_local_time("Y-m-d");

        if ($invoice_info->status == "approved") {
            $status_class = "label-success";
            $status = "approved";
        } else if ($invoice_info->status === "request_approval") {
            $status_class = "label-warning";
            $status = "request_approval";
        }

        $approval_status = "<span class='mt0 label $status_class large clickable'>" . lang($status) . "</span>";
        if ($return_html) {
            return $approval_status;
        } else {
            return $status;
        }
    }
}

/**
 * return a colorful label accroding to invoice status
 * 
 * @param Object $invoice_info
 * @return html
 */
if (!function_exists('get_approval_status_label')) {

    function get_approval_status_label($invoice_info, $return_html = true)
    {
        $status_class = "label-default";
        $status = "not_approved";
        $now = get_my_local_time("Y-m-d");

        if ($invoice_info->approval_status == "approved") {
            $status_class = "label-success";
            $status = "approved";
        } else if ($invoice_info->approval_status === "request_approval") {
            $status_class = "label-warning";
            $status = "request_approval";
        }

        $approval_status = "<span class='mt0 label $status_class large clickable'>" . lang($status) . "</span>";
        if ($return_html) {
            return $approval_status;
        } else {
            return $status;
        }
    }
}

/**
 * return a colorful label accroding to invoice status
 * 
 * @param Object $invoice_info
 * @return html
 */
if (!function_exists('get_purchase_order_status_label')) {

    function get_purchase_order_status_label($invoice_info, $return_html = true)
    {
        $invoice_status_class = "label-default";
        $status = "draft";
        $now = get_my_local_time("Y-m-d");
        //ignore the hidden value. check only 2 decimal place.
        // $intax=isset($invoice_info->tax_value)?$invoice_info->tax_value:0;
        // $invoice_info->invoice_value = floor(($invoice_info->invoice_value + $intax) * 100) / 100;

        $invoice_info->purchase_order_value = floor(isset($invoice_info->purchase_order_value) ? $invoice_info->purchase_order_value * 100 : 0 * 100) / 100;
        // echo $invoice_info->payment_received;die('hi');
        // echo $invoice_info->purchase_order_value;die('hi');

        if ($invoice_info->payment_received * 1 && $invoice_info->payment_received >= $invoice_info->purchase_order_value + $invoice_info->tax_value) {
            $invoice_status_class = "label-success";
            $status = "fully_paid";
        } else if ($invoice_info->payment_received > 0 && $invoice_info->payment_received < $invoice_info->purchase_order_value + $invoice_info->tax_value) {
            $invoice_status_class = "label-primary";
            $status = "partially_paid";
        } else if ($invoice_info->status === "not_paid" && $invoice_info->payment_received <= 0) {
            $invoice_status_class = "label-warning";
            $status = "not_paid";
        }

        $purchase_order_status = "<span class='mt0 label $invoice_status_class large clickable'>" . lang($status) . "</span>";
        if ($return_html) {
            return $purchase_order_status;
        } else {
            return $status;
        }
    }
}
if (!function_exists('get_material_request_status_label')) {

    function get_material_request_status_label($invoice_info, $return_html = true)
    {
        $invoice_status_class = "label-default";
        $status = "draft";
        $now = get_my_local_time("Y-m-d");
        //ignore the hidden value. check only 2 decimal place.
        // $invoice_info->material_request = floor($invoice_info->material_request * 100) / 100;


        if ($invoice_info->payment_received * 1 && $invoice_info->payment_received >= $invoice_info->material_request) {
            $invoice_status_class = "label-success";
            $status = "fully_paid";
        } else if ($invoice_info->payment_received > 0 && $invoice_info->payment_received < $invoice_info->material_request) {
            $invoice_status_class = "label-primary";
            $status = "partially_paid";
        } else if ($invoice_info->status === "not_paid" && $invoice_info->payment_received <= 0) {
            $invoice_status_class = "label-warning";
            $status = "not_paid";
        }

        $purchase_order_status = "<span class='mt0 label $invoice_status_class large clickable'>" . lang($status) . "</span>";
        if ($return_html) {
            return $purchase_order_status;
        } else {
            return $status;
        }
    }
}

/**
 * return a colorful label accroding to invoice status
 * 
 * @param Object $invoice_info
 * @return html
 */
if (!function_exists('get_purchase_order_approval_status_label')) {

    function get_purchase_order_approval_status_label($invoice_info, $return_html = true)
    {
        $status_class = "label-default";
        $status = "not_approved";
        $now = get_my_local_time("Y-m-d");

        if ($invoice_info->approval_status == "approved") {
            $status_class = "label-success";
            $status = "approved";
        } else if ($invoice_info->approval_status === "request_approval") {
            $status_class = "label-warning";
            $status = "request_approval";
        }

        $approval_status = "<span class='mt0 label $status_class large clickable'>" . lang($status) . "</span>";
        if ($return_html) {
            return $approval_status;
        } else {
            return $status;
        }
    }
}

/**
 * get all data to make an delivery_note
 * 
 * @param Int $delivery_note_id
 * @return array
 */
if (!function_exists('get_delivery_note_making_data')) {

    function get_delivery_note_making_data($delivery_note_id)
    {
        $ci = get_instance();
        $delivery_note_info = $ci->Delivery_notes_model->get_details(array("id" => $delivery_note_id))->row();
        if ($delivery_note_info) {
            $data['delivery_note_info'] = $delivery_note_info;
            $data['client_info'] = $ci->Clients_model->get_one($data['delivery_note_info']->client_id);
            $data['delivery_note_items'] = $ci->Delivery_note_items_model->get_details(array("delivery_note_id" => $delivery_note_id, "quantity" => true))->result();
            return $data;
        }
    }
}

/**
 * get all data to make an delivery_note
 * 
 * @param Int $delivery_note_id
 * @return array
 */
if (!function_exists('get_purchase_return_making_data')) {

    function get_purchase_return_making_data($delivery_note_id)
    {
        $ci = get_instance();
        $purchase_return_info = $ci->Purchase_returns_model->get_details(array("id" => $delivery_note_id))->row();
        if ($purchase_return_info) {
            $data['purchase_return_info'] = $purchase_return_info;
            $data['supplier_info'] = $ci->Suppliers_model->get_one($data['purchase_return_info']->supplier_id);
            $data['purchase_return_items'] = $ci->Purchase_return_items_model->get_details(array("delivery_note_id" => $delivery_note_id, "quantity" => true))->result();
            return $data;
        }
    }
}

/**
 * get all data to make an delivery_note
 * 
 * @param delivery_note making data $delivery_note_data
 * @return array
 */
if (!function_exists('prepare_purchase_return_pdf')) {

    function prepare_purchase_return_pdf($delivery_note_data, $mode = "download")
    {
        $ci = get_instance();
        $ci->load->library('pdf');
        $ci->pdf->setPrintHeader(false);
        $ci->pdf->setPrintFooter(false);
        $ci->pdf->SetCellPadding(1.5);
        $ci->pdf->setImageScale(1.42);
        $ci->pdf->AddPage();
        $ci->pdf->SetFontSize(10);

        if ($delivery_note_data) {

            $delivery_note_data["mode"] = $mode;

            $html = $ci->load->view("purchase_returns/pdf", $delivery_note_data, true);

            if ($mode != "html") {
                $align = "";
                if (is_arabic_personal_language()) {
                    $ci->pdf->setRTL(true);
                    $align = "R";
                }
                $ci->pdf->writeHTML($html, true, false, true, false, $align);
            }

            $delivery_note_info = get_array_value($delivery_note_data, "purchase_return_info");
            $pdf_file_name = lang("purchase_return") . "-" . $delivery_note_info->id . ".pdf";

            if ($mode === "download") {
                $ci->pdf->Output($pdf_file_name, "D");
            } else if ($mode === "send_email") {
                $temp_download_path = getcwd() . "/" . get_setting("temp_file_path") . $pdf_file_name;
                $ci->pdf->Output($temp_download_path, "F");
                return $temp_download_path;
            } else if ($mode === "view") {
                $ci->pdf->Output($pdf_file_name, "I");
            } else if ($mode === "html") {
                return $html;
            }
        }
    }
}

/**
 * get all data to make an delivery_note
 * 
 * @param delivery_note making data $delivery_note_data
 * @return array
 */
if (!function_exists('prepare_delivery_note_pdf')) {

    function prepare_delivery_note_pdf($delivery_note_data, $mode = "download")
    {
        $ci = get_instance();
        $ci->load->library('pdf');
        $ci->pdf->setPrintHeader(false);
        $ci->pdf->setPrintFooter(false);
        $ci->pdf->SetCellPadding(1.5);
        $ci->pdf->setImageScale(1.42);
        $ci->pdf->AddPage();
        $ci->pdf->SetFontSize(10);

        if ($delivery_note_data) {

            $delivery_note_data["mode"] = $mode;

            $html = $ci->load->view("delivery_notes/delivery_note_pdf", $delivery_note_data, true);

            if ($mode != "html") {
                $align = "";
                if (is_arabic_personal_language()) {
                    $ci->pdf->setRTL(true);
                    $align = "R";
                }
                $ci->pdf->writeHTML($html, true, false, true, false, $align);
            }

            $delivery_note_info = get_array_value($delivery_note_data, "delivery_note_info");
            $pdf_file_name = lang("delivery_note") . "-" . $delivery_note_info->id . ".pdf";

            if ($mode === "download") {
                $ci->pdf->Output($pdf_file_name, "D");
            } else if ($mode === "send_email") {
                $temp_download_path = getcwd() . "/" . get_setting("temp_file_path") . $pdf_file_name;
                $ci->pdf->Output($temp_download_path, "F");
                return $temp_download_path;
            } else if ($mode === "view") {
                $ci->pdf->Output($pdf_file_name, "I");
            } else if ($mode === "html") {
                return $html;
            }
        }
    }
}


/**
 * get all data to make an shipment
 * 
 * @param Int $shipment_id
 * @return array
 */
if (!function_exists('get_shipment_making_data')) {

    function get_shipment_making_data($shipment_id)
    {
        $ci = get_instance();
        $shipment_info = $ci->Shipments_model->get_details(array("id" => $shipment_id))->row();
        if ($shipment_info) {
            $data['shipment_info'] = $shipment_info;
            $data['supplier_info'] = $ci->Suppliers_model->get_one($data['shipment_info']->supplier_id);
            $data['shipment_items'] = $ci->Delivery_note_items_model->get_details(array("shipment_id" => $shipment_id, "quantity" => true))->result();
            return $data;
        }
    }
}

/**
 * get all data to make an shipment
 * 
 * @param shipment making data $shipment_data
 * @return array
 */
if (!function_exists('prepare_shipment_pdf')) {

    function prepare_shipment_pdf($shipment_data, $mode = "download")
    {
        $ci = get_instance();
        $ci->load->library('pdf');
        $ci->pdf->setPrintHeader(false);
        $ci->pdf->setPrintFooter(false);
        $ci->pdf->SetCellPadding(1.5);
        $ci->pdf->setImageScale(1.42);
        $ci->pdf->AddPage();
        $ci->pdf->SetFontSize(10);

        if ($shipment_data) {

            $shipment_data["mode"] = $mode;

            $html = $ci->load->view("shipments/pdf", $shipment_data, true);

            if ($mode != "html") {
                $ci->pdf->writeHTML($html, true, false, true, false, '');
            }

            $shipment_info = get_array_value($shipment_data, "shipment_info");
            $pdf_file_name = lang("shipment") . "-" . $shipment_info->id . ".pdf";

            if ($mode === "download") {
                $ci->pdf->Output($pdf_file_name, "D");
            } else if ($mode === "send_email") {
                $temp_download_path = getcwd() . "/" . get_setting("temp_file_path") . $pdf_file_name;
                $ci->pdf->Output($temp_download_path, "F");
                return $temp_download_path;
            } else if ($mode === "view") {
                $ci->pdf->Output($pdf_file_name, "I");
            } else if ($mode === "html") {
                return $html;
            }
        }
    }
}

/**
 * get all data to make an invoice
 * 
 * @param Int $invoice_id
 * @return array
 */
if (!function_exists('get_invoice_making_data')) {

    function get_invoice_making_data($invoice_id)
    {
        $ci = get_instance();
        $invoice_info = $ci->Invoices_model->get_details(array("id" => $invoice_id))->row();

        if ($invoice_info) {
            $data['invoice_info'] = $invoice_info;
            $data['client_info'] = $ci->Clients_model->get_one($data['invoice_info']->client_id);
            $data['invoice_items'] = $ci->Invoice_items_model->get_details(array("invoice_id" => $invoice_id))->result();
            $data['invoice_payments'] = $ci->Invoice_payments_model->get_details(array("invoice_id" => $invoice_id))->result();
            // $data['invoice_status_label'] = get_invoice_status_label($invoice_info);
            $data['invoice_status_label'] = get_invoice_status_label($invoice_info);
            $data["invoice_total_summary"] = $ci->Invoices_model->get_invoice_total_summary($invoice_id);

            $data['invoice_info']->custom_fields = $ci->Custom_field_values_model->get_details(array("related_to_type" => "invoices", "show_in_invoice" => true, "related_to_id" => $invoice_id))->result();
            $data['client_info']->custom_fields = $ci->Custom_field_values_model->get_details(array("related_to_type" => "clients", "show_in_invoice" => true, "related_to_id" => $data['invoice_info']->client_id))->result();
            return $data;
        }
    }
}

if (!function_exists('get_porforma_invoice_making_data')) {

    function get_proforma_invoice_making_data($invoice_id)
    {
        $ci = get_instance();
        // $ci->load->model('Proforma_invoices_model');
        $invoice_info = $ci->Proforma_invoices_model->get_details(array("id" => $invoice_id))->row();
        if ($invoice_info) {
            $data['invoice_info'] = $invoice_info;
            $data['client_info'] = $ci->Clients_model->get_one($data['invoice_info']->client_id);
            $data['invoice_items'] = $ci->Proforma_invoice_items_model->get_details(array("invoice_id" => $invoice_id))->result();
            $data['invoice_status_label'] = get_invoice_status_label($invoice_info);

            $data["invoice_total_summary"] = $ci->Proforma_invoices_model->get_invoice_total_summary($invoice_id);

            $data['invoice_info']->custom_fields = $ci->Custom_field_values_model->get_details(array("related_to_type" => "invoices", "show_in_invoice" => true, "related_to_id" => $invoice_id))->result();
            $data['client_info']->custom_fields = $ci->Custom_field_values_model->get_details(array("related_to_type" => "clients", "show_in_invoice" => true, "related_to_id" => $data['invoice_info']->client_id))->result();

            return $data;
        }
    }
}

/**
 * get all data to make an purchase_order
 * 
 * @param Int $purchase_order_id
 * @return array
 */
if (!function_exists('get_purchase_order_making_data')) {

    function get_purchase_order_making_data($purchase_order_id)
    {
        $ci = get_instance();
        $purchase_order_info = $ci->Purchase_orders_model->get_details(array("id" => $purchase_order_id))->row();
        if ($purchase_order_info) {
            $data['purchase_order_info'] = $purchase_order_info;
            $data['supplier_info'] = $ci->Suppliers_model->get_one($data['purchase_order_info']->supplier_id);
            $data['purchase_order_items'] = $ci->Purchase_order_items_model->get_details(array("purchase_order_id" => $purchase_order_id))->result();
            $data['purchase_order_status_label'] = get_purchase_order_status_label($purchase_order_info);
            $data["purchase_order_total_summary"] = $ci->Purchase_orders_model->get_purchase_order_total_summary($purchase_order_id);

            return $data;
        }
    }
}
if (!function_exists('get_material_request_making_data')) {

    function get_material_request_making_data($purchase_order_id)
    {
        $ci = get_instance();
        $purchase_order_info = $ci->Material_request_model->get_details(array("id" => $purchase_order_id))->row();
        if ($purchase_order_info) {
            $data['purchase_order_info'] = $purchase_order_info;
            $data['supplier_info'] = $ci->Suppliers_model->get_one($data['purchase_order_info']->supplier_id);
            $data['purchase_order_items'] = $ci->Material_request_items_model->get_details(array("material_request_id" => $purchase_order_id))->result();
            $data['purchase_order_status_label'] = get_purchase_order_status_label($purchase_order_info);
            $data["purchase_order_total_summary"] = $ci->Material_request_model->get_material_request_total_summary($purchase_order_id);
            return $data;
        }
    }
}

/**
 * get all data to make an purchase_order
 * 
 * @param Invoice making data $purchase_order_data
 * @return array
 */
if (!function_exists('prepare_purchase_order_pdf')) {

    function prepare_purchase_order_pdf($purchase_order_data, $mode = "download")
    {
        $ci = get_instance();
        $ci->load->library('pdf');
        $ci->pdf->setPrintHeader(false);
        $ci->pdf->setPrintFooter(false);
        $ci->pdf->SetCellPadding(1.5);
        $ci->pdf->setImageScale(1.42);
        $ci->pdf->AddPage();
        $ci->pdf->SetFontSize(10);

        if ($purchase_order_data) {

            $purchase_order_data["mode"] = $mode;

            $html = $ci->load->view("purchase_orders/purchase_order_pdf", $purchase_order_data, true);

            if ($mode != "html") {
                $align = "";
                if (is_arabic_personal_language()) {
                    $ci->pdf->setRTL(true);
                    $align = "R";
                }
                $ci->pdf->writeHTML($html, true, false, true, false, $align);
            }

            $purchase_order_info = get_array_value($purchase_order_data, "purchase_order_info");
            $pdf_file_name = lang("purchase_order") . "-" . $purchase_order_info->id . ".pdf";

            if ($mode === "download") {
                $ci->pdf->Output($pdf_file_name, "D");
            } else if ($mode === "send_email") {
                $temp_download_path = getcwd() . "/" . get_setting("temp_file_path") . $pdf_file_name;
                $ci->pdf->Output($temp_download_path, "F");
                return $temp_download_path;
            } else if ($mode === "view") {
                $ci->pdf->Output($pdf_file_name, "I");
            } else if ($mode === "html") {
                return $html;
            }
        }
    }
}
if (!function_exists('prepare_material_request_pdf')) {

    function prepare_material_request_pdf($purchase_order_data, $mode = "download")
    {
        $ci = get_instance();
        $ci->load->library('pdf');
        $ci->pdf->setPrintHeader(false);
        $ci->pdf->setPrintFooter(false);
        $ci->pdf->SetCellPadding(1.5);
        $ci->pdf->setImageScale(1.42);
        $ci->pdf->AddPage();
        $ci->pdf->SetFontSize(10);

        if ($purchase_order_data) {

            $purchase_order_data["mode"] = $mode;

            $html = $ci->load->view("material_request/purchase_order_pdf", $purchase_order_data, true);

            if ($mode != "html") {
                $ci->pdf->writeHTML($html, true, false, true, false, '');
            }

            $purchase_order_info = get_array_value($purchase_order_data, "purchase_order_info");
            $pdf_file_name = lang("purchase_order") . "-" . $purchase_order_info->id . ".pdf";

            if ($mode === "download") {
                $ci->pdf->Output($pdf_file_name, "D");
            } else if ($mode === "send_email") {
                $temp_download_path = getcwd() . "/" . get_setting("temp_file_path") . $pdf_file_name;
                $ci->pdf->Output($temp_download_path, "F");
                return $temp_download_path;
            } else if ($mode === "view") {
                $ci->pdf->Output($pdf_file_name, "I");
            } else if ($mode === "html") {
                return $html;
            }
        }
    }
}

/**
 * get all data to make an invoice
 * 
 * @param Invoice making data $invoice_data
 * @return array
 */
if (!function_exists('prepare_invoice_pdf')) {

    function prepare_invoice_pdf($invoice_data, $mode = "download")
    {
        $ci = get_instance();
        $ci->load->library('pdf');
        $ci->pdf->setPrintHeader(false);
        $ci->pdf->setPrintFooter(false);
        $ci->pdf->SetCellPadding(1.5);
        $ci->pdf->setImageScale(1.42);
        $ci->pdf->AddPage();
        $ci->pdf->SetFontSize(10);

        if ($invoice_data) {

            $invoice_data["mode"] = $mode;

            $html = $ci->load->view("invoices/invoice_pdf", $invoice_data, true);

            if ($mode != "html") {
                $align = "";
                if (is_arabic_personal_language()) {
                    $ci->pdf->setRTL(true);
                    $align = "R";
                }
                $ci->pdf->writeHTML($html, true, false, true, false, $align);
            }

            $invoice_info = get_array_value($invoice_data, "invoice_info");
            $pdf_file_name = lang("invoice") . "-" . $invoice_info->id . ".pdf";

            if ($mode === "download") {
                $ci->pdf->Output($pdf_file_name, "D");
            } else if ($mode === "send_email") {
                $temp_download_path = getcwd() . "/" . get_setting("temp_file_path") . $pdf_file_name;
                $ci->pdf->Output($temp_download_path, "F");
                return $temp_download_path;
            } else if ($mode === "view") {
                $ci->pdf->Output($pdf_file_name, "I");
            } else if ($mode === "html") {
                return $html;
            }
        }
    }
}

/**
 * get all data to make an estimate
 * 
 * @param emtimate making data $estimate_data
 * @return array
 */
if (!function_exists('prepare_estimate_pdf')) {

    function prepare_estimate_pdf($estimate_data, $mode = "download")
    {
        $ci = get_instance();
        $ci->load->library('pdf');
        $ci->pdf->setPrintHeader(false);
        $ci->pdf->setPrintFooter(false);
        $ci->pdf->SetCellPadding(1.5);
        $ci->pdf->setImageScale(1.42);
        $ci->pdf->AddPage();

        if ($estimate_data) {

            $estimate_data["mode"] = $mode;

            $html = $ci->load->view("estimates/estimate_pdf", $estimate_data, true);
            if ($mode != "html") {
                $align = "";
                if (is_arabic_personal_language()) {
                    $ci->pdf->setRTL(true);
                    $align = "R";
                }
                $ci->pdf->writeHTML($html, true, false, true, false, $align);
            }

            $estimate_info = get_array_value($estimate_data, "estimate_info");
            $pdf_file_name = lang("estimate") . "-$estimate_info->id.pdf";

            if ($mode === "download") {
                $ci->pdf->Output($pdf_file_name, "D");
            } else if ($mode === "send_email") {
                $temp_download_path = getcwd() . "/" . get_setting("temp_file_path") . $pdf_file_name;
                $ci->pdf->Output($temp_download_path, "F");
                return $temp_download_path;
            } else if ($mode === "view") {
                $ci->pdf->Output($pdf_file_name, "I");
            } else if ($mode === "html") {
                return $html;
            }
        }
    }
}

/**
 * 
 * get invoice number
 * @param Int $invoice_id
 * @return string
 */
if (!function_exists('get_delivery_note_id')) {

    function get_delivery_note_id($delivery_note_id)
    {
        //$prefix = get_setting("invoice_prefix");
        $prefix = strtoupper(lang("delivery_note")) . " #";
        return $prefix . $delivery_note_id;
    }
}

/**
 * 
 * get invoice number
 * @param Int $invoice_id
 * @return string
 */
if (!function_exists('get_purchase_return_id')) {

    function get_purchase_return_id($purchase_return_id)
    {
        //$prefix = get_setting("invoice_prefix");
        $prefix = strtoupper(lang("purchase_return")) . " #";
        return $prefix . $purchase_return_id;
    }
}

/**
 * 
 * get invoice number
 * @param Int $invoice_id
 * @return string
 */
if (!function_exists('get_shipment_id')) {

    function get_shipment_id($shipment_id)
    {
        //$prefix = get_setting("invoice_prefix");
        $prefix = strtoupper(lang("shipment")) . " #";
        return $prefix . $shipment_id;
    }
}

/**
 * 
 * get invoice number
 * @param Int $invoice_id
 * @return string
 */
if (!function_exists('get_invoice_id')) {

    function get_invoice_id($invoice_id)
    {
        $prefix = get_setting("invoice_prefix");
        $ci = get_instance();
        if ($ci->db->dbprefix == 'Tadqeeq') {
            $invoice = $ci->Invoices_model->get_one($invoice_id);
            $prefix = date("ym", strtotime($invoice->bill_date)) . '0' . $invoice->count;
            return $prefix;
        } else {
            $prefix = $prefix ? $prefix : strtoupper(lang("invoice")) . " #";
            return $prefix . $invoice_id;
        }
    }
}
if (!function_exists('get_advance_invoice_id')) {

    function get_advance_invoice_id($invoice_id)
    {
        $prefix = get_setting("invoice_prefix");
        $prefix = $prefix ? $prefix : strtoupper(lang("advance_payment_invoice")) . " #";
        return $prefix . $invoice_id;
    }
}

/**
 * 
 * get invoice number
 * @param Int $invoice_id
 * @return string
 */
if (!function_exists('get_purchase_order_id')) {

    function get_purchase_order_id($purchase_order_id)
    {
        //$prefix = get_setting("invoice_prefix");
        $prefix = strtoupper(lang("purchase_order")) . " #";
        return $prefix . $purchase_order_id;
    }
}
if (!function_exists('get_material_request_id')) {

    function get_material_request_id($material_request_id)
    {
        //$prefix = get_setting("invoice_prefix");
        $prefix = strtoupper(lang("material_request")) . " #";
        return $prefix . $material_request_id;
    }
}

/**
 * 
 * get estimate number
 * @param Int $estimate_id
 * @return string
 */
if (!function_exists('get_estimate_id')) {

    function get_estimate_id($estimate_id)
    {
        $prefix = get_setting("estimate_prefix");
        $prefix = $prefix ? $prefix : strtoupper(lang("estimate")) . " #";
        return $prefix . $estimate_id;
    }
}
if (!function_exists('get_budget_id')) {

    function get_budget_id($estimate_id)
    {
        $prefix = get_setting("estimate_prefix");
        $prefix = $prefix ? $prefix : strtoupper(lang("Budget")) . " #";
        return $prefix . $estimate_id;
    }
}

/**
 * 
 * get ticket number
 * @param Int $ticket_id
 * @return string
 */
if (!function_exists('get_ticket_id')) {

    function get_ticket_id($ticket_id)
    {
        $prefix = get_setting("ticket_prefix");
        $prefix = $prefix ? $prefix : lang("ticket") . " #";
        return $prefix . $ticket_id;
    }
}


/**
 * get all data to make an estimate
 * 
 * @param Int $estimate_id
 * @return array
 */
if (!function_exists('get_estimate_making_data')) {

    function get_estimate_making_data($estimate_id)
    {
        $ci = get_instance();
        $estimate_info = $ci->Estimates_model->get_details(array("id" => $estimate_id))->row();
        if ($estimate_info) {
            $data['estimate_info'] = $estimate_info;
            $data['client_info'] = $ci->Clients_model->get_one($data['estimate_info']->client_id);
            $data['estimate_items'] = $ci->Estimate_items_model->get_details(array("estimate_id" => $estimate_id))->result();
            $data["estimate_total_summary"] = $ci->Estimates_model->get_estimate_total_summary($estimate_id);

            $data['estimate_info']->custom_fields = $ci->Custom_field_values_model->get_details(array("related_to_type" => "estimates", "show_in_estimate" => true, "related_to_id" => $estimate_id))->result();
            return $data;
        }
    }
}
/**
 * get all data to make an budget
 * 
 * @param Int $estimate_id
 * @return array
 */
if (!function_exists('get_budgeting_making_data')) {

    function get_budgeting_making_data($estimate_id)
    {
        $ci = get_instance();
        $estimate_info = $ci->Budgeting_model->get_details(array("id" => $estimate_id))->row();

        if ($estimate_info) {
            $data['estimate_info'] = $estimate_info;
            $data['project_info'] = $ci->Projects_model->get_one($data['estimate_info']->project_id);

            $data['estimate_items'] = $ci->Budgeting_items_model->get_details(array("estimate_id" => $estimate_id))->result();

            // $data["estimate_total_summary"] = $ci->Budgeting_model->get_estimate_total_summary($estimate_id);

            $data['estimate_info']->custom_fields = $ci->Custom_field_values_model->get_details(array("related_to_type" => "estimates", "show_in_estimate" => true, "related_to_id" => $estimate_id))->result();

            return $data;
        }
    }
}


/**
 * get team members and teams select2 dropdown data list
 * 
 * @return array
 */
if (!function_exists('get_team_members_and_teams_select2_data_list')) {

    function get_team_members_and_teams_select2_data_list()
    {
        $ci = get_instance();

        $team_members = $ci->Users_model->get_all_where(array("deleted" => 0, "user_type" => "staff"))->result();
        $members_and_teams_dropdown = array();

        foreach ($team_members as $team_member) {
            $members_and_teams_dropdown[] = array("type" => "member", "id" => "member:" . $team_member->id, "text" => $team_member->first_name . " " . $team_member->last_name);
        }

        $team = $ci->Team_model->get_all_where(array("deleted" => 0))->result();
        foreach ($team as $team) {
            $members_and_teams_dropdown[] = array("type" => "team", "id" => "team:" . $team->id, "text" => $team->title);
        }

        return $members_and_teams_dropdown;
    }
}



/**
 * submit data for notification
 * 
 * @return array
 */
if (!function_exists('log_notification')) {

    function log_notification($event, $options = array(), $user_id = 0)
    {

        $ci = get_instance();

        $url = get_uri("notification_processor/create_notification");

        $req = "event=" . encode_id($event, "notification");
        if ($user_id) {
            $req .= "&user_id=" . $user_id;
        } else if ($user_id === "0") {
            $req .= "&user_id=" . $user_id; //if user id is 0 (string) we'll assume that it's system bot 
        } else if (isset($ci->login_user)) {
            $req .= "&user_id=" . $ci->login_user->id;
        }


        foreach ($options as $key => $value) {
            $value = urlencode($value);
            $req .= "&$key=$value";
        }


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);


        if (get_setting("add_useragent_to_curl")) {
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; rv:19.0) Gecko/20100101 Firefox/19.0");
        }
        curl_exec($ch);
        curl_close($ch);
    }
}


/**
 * save custom fields for any context
 * 
 * @param Int $estimate_id
 * @return array
 */
if (!function_exists('save_custom_fields')) {

    function save_custom_fields($related_to_type, $related_to_id, $is_admin = 0, $user_type = "", $activity_log_id = 0, $save_to_related_type = "", $user_id = 0)
    {
        $ci = get_instance();

        $custom_fields = $ci->Custom_fields_model->get_combined_details($related_to_type, $related_to_id, $is_admin, $user_type)->result();

        // we have to update the activity logs table according to the changes of custom fields
        $changes = array();

        //for migration, we've to save on new related type
        if ($save_to_related_type) {
            $related_to_type = $save_to_related_type;
        }

        //save custom fields
        foreach ($custom_fields as $field) {
            $field_name = "custom_field_" . $field->id;

            //to get the custom field values for per users from the same page, we've to use the user id
            if ($user_id) {
                $field_name .= "_" . $user_id;
            }

            //save only submitted fields
            if (array_key_exists($field_name, $_POST)) {
                $value = $ci->input->post($field_name);

                if ($value) {
                    $field_value_data = array(
                        "related_to_type" => $related_to_type,
                        "related_to_id" => $related_to_id,
                        "custom_field_id" => $field->id,
                        "value" => $value
                    );

                    $field_value_data = clean_data($field_value_data);

                    $save_data = $ci->Custom_field_values_model->upsert($field_value_data, $save_to_related_type);

                    if ($save_data) {
                        $changed_values = get_array_value($save_data, "changes");
                        $field_title = get_array_value($changed_values, "title");
                        $field_type = get_array_value($changed_values, "field_type");
                        $visible_to_admins_only = get_array_value($changed_values, "visible_to_admins_only");
                        $hide_from_clients = get_array_value($changed_values, "hide_from_clients");

                        //add changes of custom fields
                        if (get_array_value($save_data, "operation") == "update") {
                            //update
                            $changes[$field_title . "[:" . $field->id . "," . $field_type . "," . $visible_to_admins_only . "," . $hide_from_clients . ":]"] = array("from" => get_array_value($changed_values, "from"), "to" => get_array_value($changed_values, "to"));
                        } else if (get_array_value($save_data, "operation") == "insert") {
                            //insert
                            $changes[$field_title . "[:" . $field->id . "," . $field_type . "," . $visible_to_admins_only . "," . $hide_from_clients . ":]"] = array("from" => "", "to" => $value);
                        }
                    }
                }
            }
        }

        //finally save the changes to activity logs table
        return update_custom_fields_changes($related_to_type, $related_to_id, $changes, $activity_log_id);
    }
}

/**
 * update custom fields changes to activity logs table
 */
if (!function_exists('update_custom_fields_changes')) {

    function update_custom_fields_changes($related_to_type, $related_to_id, $changes, $activity_log_id = 0)
    {
        if ($changes && count($changes)) {
            $ci = get_instance();

            $related_to_data = new stdClass();

            $log_type = "";
            $log_for = "";
            $log_type_title = "";
            $log_for_id = "";

            if ($related_to_type == "tasks") {
                $related_to_data = $ci->Tasks_model->get_one($related_to_id);
                $log_type = "task";
                $log_for = "project";
                $log_type_title = $related_to_data->title;
                $log_for_id = $related_to_data->project_id;
            }

            $log_data = array(
                "action" => "updated",
                "log_type" => $log_type,
                "log_type_title" => $log_type_title,
                "log_type_id" => $related_to_id,
                "log_for" => $log_for,
                "log_for_id" => $log_for_id
            );


            if ($activity_log_id) {
                $before_changes = array();

                //we have to combine with the existing changes of activity logs
                $activity_log = $ci->Activity_logs_model->get_one($activity_log_id);
                $activity_logs_changes = unserialize($activity_log->changes);
                if (is_array($activity_logs_changes)) {
                    foreach ($activity_logs_changes as $key => $value) {
                        $before_changes[$key] = array("from" => get_array_value($value, "from"), "to" => get_array_value($value, "to"));
                    }
                }

                $log_data["changes"] = serialize(array_merge($before_changes, $changes));

                if ($activity_log->action != "created") {
                    $ci->Activity_logs_model->update_where($log_data, array("id" => $activity_log_id));
                }
            } else {
                $log_data["changes"] = serialize($changes);
                return $ci->Activity_logs_model->save($log_data);
            }
        }
    }
}


/**
 * use this to clean xss and html elements
 * the best practice is to use this before rendering 
 * but you can use this before saving for suitable cases
 *
 * @param string or array $data
 * @return clean $data
 */
if (!function_exists("clean_data")) {

    function clean_data($data)
    {
        $ci = get_instance();

        $data = $ci->security->xss_clean($data);
        $disable_html_input = get_setting("disable_html_input");

        if ($disable_html_input == "1") {
            $data = html_escape($data);
        }

        return $data;
    }
}


//return site logo
if (!function_exists("get_logo_url")) {

    function get_logo_url()
    {
        return get_file_from_setting("site_logo");
    }
}

//get logo from setting
if (!function_exists("get_file_from_setting")) {

    function get_file_from_setting($setting_name = "", $only_file_path_with_slash = false)
    {

        if ($setting_name) {
            $setting_value = get_setting($setting_name);
            if ($setting_value) {
                $file = @unserialize($setting_value);
                if (is_array($file)) {

                    //show full size thumbnail for signin page background
                    $show_full_size_thumbnail = false;
                    if ($setting_name == "signin_page_background") {
                        $show_full_size_thumbnail = true;
                    }

                    return get_source_url_of_file($file, get_setting("system_file_path"), "thumbnail", $only_file_path_with_slash, $only_file_path_with_slash, $show_full_size_thumbnail);
                } else {
                    if ($only_file_path_with_slash) {
                        return "/" . (get_setting("system_file_path") . $setting_value);
                    } else {
                        return get_file_uri(get_setting("system_file_path") . $setting_value);
                    }
                }
            }
        }
    }
}

//get site favicon
if (!function_exists("get_favicon_url")) {

    function get_favicon_url()
    {
        $favicon_from_setting = get_file_from_setting('favicon');
        return $favicon_from_setting ? $favicon_from_setting : get_file_uri("assets/images/favicon.png");
    }
}

//add custom variable data
if (!function_exists("get_custom_variables_data")) {

    function get_custom_variables_data($related_to_type = "", $related_to_id = 0)
    {
        if ($related_to_type && $related_to_id) {
            $ci = get_instance();
            $variables_array = array();

            $options = array("related_to_type" => $related_to_type, "related_to_id" => $related_to_id);
            $values = $ci->Custom_field_values_model->get_details($options)->result();

            foreach ($values as $value) {
                if ($value->example_variable_name && $value->value) {
                    $variables_array[$value->example_variable_name] = $value->value;
                }
            }

            return $variables_array;
        }
    }
}



/**
 * get all data to make an delivery_note
 * 
 * @param Int $delivery_note_id
 * @return array
 */
if (!function_exists('get_sale_return_making_data')) {

    function get_sale_return_making_data($delivery_note_id)
    {
        $ci = get_instance();
        $sale_return_info = $ci->Sale_returns_model->get_details(array("id" => $delivery_note_id))->row();
        if ($sale_return_info) {
            $data['sale_return_info'] = $sale_return_info;
            $data['client_info'] = $ci->Clients_model->get_one($data['sale_return_info']->client_id);
            $data['sale_return_items'] = $ci->Purchase_return_items_model->get_details(array("delivery_note_id" => $delivery_note_id, "quantity" => true))->result();
            return $data;
        }
    }
}

/**
 * get all data to make an delivery_note
 * 
 * @param delivery_note making data $delivery_note_data
 * @return array
 */
if (!function_exists('prepare_sale_return_pdf')) {

    function prepare_sale_return_pdf($delivery_note_data, $mode = "download")
    {
        $ci = get_instance();
        $ci->load->library('pdf');
        $ci->pdf->setPrintHeader(false);
        $ci->pdf->setPrintFooter(false);
        $ci->pdf->SetCellPadding(1.5);
        $ci->pdf->setImageScale(1.42);
        $ci->pdf->AddPage();
        $ci->pdf->SetFontSize(10);

        if ($delivery_note_data) {

            $delivery_note_data["mode"] = $mode;

            $html = $ci->load->view("sale_returns/pdf", $delivery_note_data, true);

            if ($mode != "html") {
                $align = "";
                if (is_arabic_personal_language()) {
                    $ci->pdf->setRTL(true);
                    $align = "R";
                }
                $ci->pdf->writeHTML($html, true, false, true, false, $align);
            }

            $delivery_note_info = get_array_value($delivery_note_data, "sale_return_info");
            $pdf_file_name = lang("sale_return") . "-" . $delivery_note_info->id . ".pdf";

            if ($mode === "download") {
                $ci->pdf->Output($pdf_file_name, "D");
            } else if ($mode === "send_email") {
                $temp_download_path = getcwd() . "/" . get_setting("temp_file_path") . $pdf_file_name;
                $ci->pdf->Output($temp_download_path, "F");
                return $temp_download_path;
            } else if ($mode === "view") {
                $ci->pdf->Output($pdf_file_name, "I");
            } else if ($mode === "html") {
                return $html;
            }
        }
    }
}

/**
 * 
 * get invoice number
 * @param Int $invoice_id
 * @return string
 */
if (!function_exists('get_sale_return_id')) {

    function get_sale_return_id($sale_return_id)
    {
        //$prefix = get_setting("invoice_prefix");
        $prefix = strtoupper(lang("sale_return")) . " #";
        return $prefix . $sale_return_id;
    }
}

if (!function_exists("pretty_me")) {
    function pretty_me($data)
    {

        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }
}

if (!function_exists("generate_accounts")) {
    function generate_accounts($title, $parent, $account_id = 0)
    {
        $ci = get_instance();

        $parent_code = $ci->Accounts_model->get_one($parent)->acc_code;

        $code_count = count($ci->Accounts_model->get_all_where(array("deleted" => 0, "acc_parent" => $parent))->result());

        if (!empty($parent_code)) {
            $code_count = $code_count + 1;
            $acode = $parent_code . "-" . $code_count;
        } else {
            $acode = '';
        }

        if (!empty($code)) {
            $code = $code;
        } else {
            $code = $acode;
        }

        $data = array(
            'acc_name' => $title,
            'is_primary' => 0,

        );

        if (empty($account_id)) {
            $data["acc_parent"] = $parent;
            $data["acc_code"] = $code;
        }

        $account = $ci->Accounts_model->save($data, $account_id);

        return $account;
    }
}


if (!function_exists("make_transaction")) {
    function make_transaction($date, $acc_array = array(), $type)
    {

        $ci = get_instance();
        $data = array(
            'date' => $date,
            'type' => $type
        );
        $save_id = $ci->Transactions_model->save($data);

        if ($save_id) {
            foreach ($acc_array as $acc) {
                //write a routine to check the validity of the entries i.e total debit = total credit

                $data = array(
                    'account' => !empty($acc['account_id']) ? $acc['account_id'] : 26,
                    'type' => $acc['type'],
                    'amount' => round($acc['amount'], 3),
                    'narration' => $acc['narration'],
                    'branch_id' => !empty($acc['branch_id']) ? $acc['branch_id'] : 0,
                    'unit' => !empty($acc['unit']) ? $acc['unit'] : 0,
                    'reference' => !empty($acc['reference']) ? $acc['reference'] : 0,
                    'concerned_person' => !empty($acc['concerned_person']) ? $acc['concerned_person'] : 0,
                    'trans_id' => $save_id
                );
                $ci->Enteries_model->save($data);
            }

            return $save_id;
        }
    }
}

if (!function_exists("delete_transaction")) {
    function delete_transaction($id = 0)
    {
        $ci = get_instance();
        if ($id) {
            $delete_transaction = $ci->Transactions_model->delete($id);
            return $delete_transaction;
        }
    }
}

if (!function_exists("format_balance")) {
    function format_balance($balance, $type)
    {
        $format = "<span>";
        if ($balance['total_type'] == 'Cr' && $type == 'asset') {
            $format = "<span style='color: red'>";
        }
        $format .= number_format($balance['total'], 3) . " OMR (" . $balance['total_type'] . ") </span>";

        return $format;
    }
}


if (!function_exists("number_to_omr")) {
    function number_to_omr($num)
    {
        $decones = array(
            '01' => "One",
            '02' => "Two",
            '03' => "Three",
            '04' => "Four",
            '05' => "Five",
            '06' => "Six",
            '07' => "Seven",
            '08' => "Eight",
            '09' => "Nine",
            10 => "Ten",
            11 => "Eleven",
            12 => "Twelve",
            13 => "Thirteen",
            14 => "Fourteen",
            15 => "Fifteen",
            16 => "Sixteen",
            17 => "Seventeen",
            18 => "Eighteen",
            19 => "Nineteen"
        );
        $ones = array(
            0 => " ",
            1 => "One",
            2 => "Two",
            3 => "Three",
            4 => "Four",
            5 => "Five",
            6 => "Six",
            7 => "Seven",
            8 => "Eight",
            9 => "Nine",
            10 => "Ten",
            11 => "Eleven",
            12 => "Twelve",
            13 => "Thirteen",
            14 => "Fourteen",
            15 => "Fifteen",
            16 => "Sixteen",
            17 => "Seventeen",
            18 => "Eighteen",
            19 => "Nineteen"
        );
        $tens = array(
            0 => "",
            2 => "Twenty",
            3 => "Thirty",
            4 => "Forty",
            5 => "Fifty",
            6 => "Sixty",
            7 => "Seventy",
            8 => "Eighty",
            9 => "Ninety"
        );
        $hundreds = array(
            "Hundred",
            "Thousand",
            "Million",
            "Billion",
            "Trillion",
            "Quadrillion"
        ); //limit t quadrillion 
        $num = number_format($num, 3, ".", ",");

        $num_arr = explode(".", $num);
        $wholenum = $num_arr[0];
        $decnum = $num_arr[1];
        $whole_arr = array_reverse(explode(",", $wholenum));
        krsort($whole_arr);
        $rettxt = "";
        foreach ($whole_arr as $key => $i) {
            if ($i < 20) {
                $rettxt .= $ones[$i];
            } elseif ($i < 100) {
                $rettxt .= $tens[substr($i, 0, 1)];
                $rettxt .= " " . $ones[substr($i, 1, 1)];
            } else {
                $rettxt .= $ones[substr($i, 0, 1)] . " " . $hundreds[0];
                $rettxt .= " " . $ones[substr($i, 1, 2)];
                //$rettxt .= " ".$ones[substr($i,2,1)]; 
            }
            if ($key > 0) {
                $rettxt .= " " . $hundreds[$key] . " ";
            }
        }
        $rettxt = $rettxt . " Riyal Omani/s";

        if ($decnum > 0) {

            //$decnum = intval($decnum);
            $rettxt .= " and ";
            if ($decnum < 20) {
                $rettxt .= $ones[$decnum];
            } else if ($decnum < 100) {

                $rettxt .= $tens[substr($decnum, 0, 1)];
            } else {
                $rettxt .= $ones[substr($decnum, 0, 1)] . " " . $hundreds[0] . ' ';
                $rettxt .= $tens[substr($decnum, 1, 1)] . ' ';
                $rettxt .= $ones[substr($decnum, 2, 1)] . ' ';
            }
            $rettxt = $rettxt . " Baiza/s";
        }
        return $rettxt . ' only';
    }
}


function get_employement_years($user_id)
{
    $ci = get_instance();
    $options = array("id" => $user_id);
    $user_info = $ci->Users_model->get_details($options)->row();

    $joining_date = $user_info->date_of_hire;
    $current_date = date('Y-m-d');

    $date1 = date_create($current_date);
    $date2 = date_create($joining_date);
    $diff = date_diff($date1, $date2);

    // $diff = $diff / 365;

    //pretty_me($diff);
    //return $current_date;
    return round($diff->days / 365, 3);
}

function get_gross_salary($user_id)
{
    $ci = get_instance();
    $options = array("id" => $user_id);
    $user_info = $ci->Users_model->get_details($options)->row();

    $basic = $user_info->salary;
    $housing = $user_info->housing;
    $transportation = $user_info->transportation;
    $telephone = $user_info->telephone;
    $utility = $user_info->utility;

    $gross_salary = $basic + $housing + $transportation + $telephone + $utility;
    // $gross_salary = $basic ;

    return $gross_salary;
}

function basic_salary($user_id)
{
    $ci = get_instance();
    $options = array("id" => $user_id);
    $user_info = $ci->Users_model->get_details($options)->row();

    $basic = $user_info->salary;
    return $basic;
}

function get_gratuity($user_id = 0, $years_of_employement = 0)
{
    $ci = get_instance();
    $options = array("id" => $user_id);
    $user_info = $ci->Users_model->get_details($options)->row();
    if (!$user_info->national) {
        $basic_salary = $user_info->salary;
        $years_of_employement = ($years_of_employement) ? $years_of_employement : get_employement_years($user_id);
        $gratuity = ($basic_salary / 2) * $years_of_employement;

        if ($years_of_employement >= 3) {
            $gratuity = (($basic_salary / 2) * 3) + ($basic_salary * ($years_of_employement - 3));
        } elseif ($years_of_employement < 1) {
            $gratuity = 0;
        }
    } else {
        $gratuity = 0;
    }


    return $gratuity;
}

function get_monthly_gratuity($user_id)
{
    $ci = get_instance();
    $options = array("id" => $user_id);
    $user_info = $ci->Users_model->get_details($options)->row();
    if (!$user_info->national) {
        $basic_salary = $user_info->salary;
        $years_of_employement = get_employement_years($user_id);
        $gratuity = ($basic_salary / 2) / 12;

        if ($years_of_employement >= 3) {
            $gratuity = $basic_salary / 12;
        } elseif ($years_of_employement < 1) {
            $gratuity = 0;
        }
    } else {
        $gratuity = 0;
    }


    return $gratuity;
}

function get_company_pasi_share($user_id)
{
    $ci = get_instance();
    $user_info = $ci->Users_model->get_details(array('id' => $user_id))->row();
    if ($user_info->national && $user_info->pasi) {
        // $gross_total = get_gross_salary($user_id);
        $gross_total = basic_salary($user_id);
        // $pasi_share = $gross_total * 0.115;
        $pasi_share = $gross_total * 0.125;
    } else {
        $pasi_share = 0;
    }

    return $pasi_share;
}

function get_employee_pasi_share($user_id)
{
    $ci = get_instance();
    $user_info = $ci->Users_model->get_details(array('id' => $user_id))->row();
    if ($user_info->national && $user_info->pasi) {
        // $gross_total = get_gross_salary($user_id);
        $gross_total = basic_salary($user_id);
        // $pasi_share = $gross_total * 0.07;
        $pasi_share = $gross_total * 0.08;
    } else {
        $pasi_share = 0;
    }

    return $pasi_share;
}

function get_company_job_s_share($user_id)
{
    $ci = get_instance();
    $user_info = $ci->Users_model->get_details(array('id' => $user_id))->row();
    if ($user_info->national && $user_info->pasi) {
        $gross_total = get_gross_salary($user_id);
        $job_s_share = $gross_total * 0.01;
    } else {
        $job_s_share = 0;
    }


    return $job_s_share;
}

function get_employee_job_s_share($user_id)
{
    $ci = get_instance();
    $user_info = $ci->Users_model->get_details(array('id' => $user_id))->row();
    if ($user_info->national && $user_info->pasi) {
        $gross_total = get_gross_salary($user_id);
        $job_s_share = $gross_total * 0.01;
    } else {
        $job_s_share = 0;
    }


    return $job_s_share;
}

function get_employee_leaves($user_id, $start_date, $end_date)
{
    $ci = get_instance();

    $options = array("start_date" => $start_date, "end_date" => $end_date, "applicant_id" => $user_id, "login_user_id" => $ci->login_user->id, "paid_unpaid" => 0);

    $leaves_info = $ci->Leave_applications_model->get_list($options)->result();

    return $leaves_info;
}

function get_employee_unpaid_total_leaves($user_id, $start_date, $end_date)
{
    $ci = get_instance();

    $options = array("start_date" => $start_date, "end_date" => $end_date, "applicant_id" => $user_id, "login_user_id" => $ci->login_user->id, "paid_unpaid" => 0);

    $leaves_info = $ci->Leave_applications_model->get_count($options);

    return $leaves_info->total_unpaid;
}

function get_employee_leaves_all($user_id, $start_date, $end_date)
{
    $ci = get_instance();

    $options = array("start_date" => $start_date, "end_date" => $end_date, "applicant_id" => $user_id, "login_user_id" => $ci->login_user->id);

    $leaves_info = $ci->Leave_applications_model->get_list($options)->result();

    return $leaves_info;
}

function get_employee_loans($user_id)
{
    $ci = get_instance();

    $loan_total = 0;
    $loan_balance = 0;

    $loans_options = array("user_id" => $user_id);
    $payroll_options = array("employee_id" => $user_id);

    $loans_info = $ci->Loans_model->get_sum($loans_options)->row();
    $payroll_info = $ci->Payroll_detail_model->get_sum($payroll_options)->row();

    $total_loan = $loans_info->total_loan;
    $total_paid_loan = $payroll_info->paid_loan;
    $balance_loan = $total_loan - $total_paid_loan;


    $loan_details = array();

    $loan_details['total_loan'] = $total_loan;
    $loan_details['total_paid_loan'] = $total_paid_loan;
    $loan_details['balance_loan'] = $balance_loan;



    return $loan_details;
}

if (!function_exists('get_personal_language')) {
    function get_personal_language()
    {
        $ci = get_instance();
        return get_setting('user_' .  $ci->login_user->id . '_personal_language');
    }
}

if (!function_exists('is_arabic_personal_language')) {
    function is_arabic_personal_language(): bool
    {
        return get_personal_language() == 'arabic';
    }
}
