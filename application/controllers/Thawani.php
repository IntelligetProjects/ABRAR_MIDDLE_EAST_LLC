<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Thawani extends MY_Controller {


    function get_payment_url(){
     
        try {
            $mode='uat';
            $public_key="HGvTMLDssJghr9tlN9gr4DVYt0qyBy";
            $secret_key="rRQ26GcsZzoEhbrP2HZvLYDbn9C9et";
            $id=get_setting("company_phone");
            $amount=$_SESSION['thawani_amount'];
            $name= get_setting("company_name");
            $phone=get_setting("company_phone");
            $success_url=get_uri("thawani/success");
            $cancel_url=get_uri("vat_report");
            $url = 'https://'.$mode.'checkout.thawani.om/api/v1/checkout/session';
            $ch = curl_init($url);
            $data =  array('client_reference_id' => $id, 
                    'mode' => 'payment',
                    'products'=> array(array('name'=> $name,'quantity' => 1,'unit_amount'=> $amount)),
                    'success_url' =>  $success_url,
                    'cancel_url' =>  $cancel_url,
                    'customer_id'  =>  '',
                    'metadata' => array( 'customer_name' => $name,'order_id' =>  $id, 'phone' =>$phone)   );
            $payload = json_encode($data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER,
            array('Content-Type:application/json','thawani-api-key:'.$secret_key));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            //   var_dump($result);die();
            if($result){
            $r=json_decode($result);
            //  var_dump($r);die();
              if(!$r->success){
                  echo $r->description ." - ".$r->data->error[0]->message;
                  die();
              }
            if($r->success){
                $session_id= $r->data->session_id;
                $pay_url= 'https://'.$mode.'checkout.thawani.om/pay/'. $session_id.'?key='.$public_key;
                session_start();
                $_SESSION['transaction_ref']=$session_id;
                redirect($pay_url, 'location');    
            }else{
                return false; 
            }
            }
        } catch (\Exception $ex) {
            var_dump($ex);
            die();
            echo 'Payment process is failed.';
            $cancel_url=get_uri("vat_report");
            redirect($cancel_url, 'location'); 
        }
    }
    function success(){
        $session_id= $_SESSION['transaction_ref'];
        $mode='uat';
        $secret_key="rRQ26GcsZzoEhbrP2HZvLYDbn9C9et";
        if(isset($session_id)){
        $url = 'https://'.$mode.'checkout.thawani.om/api/v1/checkout/session/'. $session_id;
        $options = array(
            'http' => array(
                'header'  => "thawani-api-key: $secret_key",
                'method'  => 'GET',
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        // echo  $session_id;
        // var_dump($result); 
        if ($result === FALSE) { 
        echo  "Something wrong";
        }
        if(isset($result)){
            $data= json_decode($result);
            $payment_status=$data->data->payment_status;
            $client_reference_id=$data->data->client_reference_id;
             //  echo "order_id". $order_id;
            if($payment_status=="paid"){
                $this->load->view("vat-report/receipt",$data);
            }
        }else{
            echo "No result";
        }
    }
       
    }

}
