<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Features extends CI_Controller {

function index(){
    $pass= $this->input->post('PaSs');
    if($pass=='t^@9b%Xp$HkW6jRm!L#z'){
        $expires= $this->input->post('expires');
        $petty_cash= $this->input->post('petty_cash');
        set_setting("module_expires",$expires);
        set_setting("module_petty_cash",$petty_cash);
        echo json_encode(array("success" => true, 'message' => lang("successful")));
        exit();
    }else{
        die('Not Allow !');
    }
   
}
function get_features(){
    $d=[];
    $d['expires']=get_setting("module_expires");
    $d['petty_cash']=get_setting("module_petty_cash");
    echo json_encode(array("success" => true, 'message' => lang("successful"),'data'=>$d));
}

}