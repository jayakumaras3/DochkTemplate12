<?php

namespace App\Controllers;
use App\Models\Settings\Email_model;
#[\AllowDynamicProperties]
class Dochek_email extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->email_model = new Email_model();
    }
    public function assign_demo(){
        $email = \Config\Services::email();
        $postdata = $this->request->getVar();
        if(isset($postdata)){
            $successMessage = '';
            $errorMessage = '';

            //$hash = mt_rand(10000000, 99999999);
            $hash = isset($postdata['access_code'])?$postdata['access_code']:'';
            $data['keyval'] = $hash;
            $data['uid'] = isset($postdata['emailid'])?$postdata['emailid']:'';
            $data['demoid'] = isset($postdata['demo_id'])?$postdata['demo_id']:'0';

            $da = $postdata['demo_date'];
            $data['comment'] = $postdata['comment'];
            $dex = explode("/", $da);
            $data['demodate'] = $postdata['demo_date'];
            $data['expirystatus'] = 1;
            $data['createdon'] = date("Y-m-d");
            //print_r($hash);
            $resultcheckEmail = $this->email_model->mycartdata($data['uid'],$hash);
            $num_rowsc = count($resultcheckEmail);
            if ($num_rowsc > 0) {
                 return view('demos/assign_closedemo_view',$data);
            }else{
                $q = $this->email_model->savemycartdata($data);
                $subject ="test";
                $data['userid']= $this->my_simple_crypt($data['uid'], 'e');
                $bodyMsg = '<p>Click the link below to view the Demo.</p><p>and your password  is ' . $data['keyval'] . '</p>';
                $email->setFrom('do-not-reply@talentquest.com', 'Dochek');
                $email->setTo($data['uid']);
                
                $email->setSubject($subject);
                $email->setMessage($bodyMsg);

                if ($email->send()) {
                    $data['email$status'] = "Mail Successfully Sent";
                    $emailstatus = 1;
                }else{
                    $data['email$status'] = "Mail Error - >";
                    $emailstatus = 0;
                }
                return view('demos/assign_demo_view',$data);
            }
            
            
        }
        // 

                
    }
    function my_simple_crypt($string, $action = 'e') {
        // you may change these values to your own
        $secret_key = 'my_simple_secret_key';
        $secret_iv = 'my_simple_secret_iv';

        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if ($action == 'e') {
            $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
        } else if ($action == 'd') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }
}
