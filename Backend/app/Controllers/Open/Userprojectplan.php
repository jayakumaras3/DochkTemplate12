<?php

namespace App\Controllers\Open;

use App\Controllers\BaseController;
use App\Models\Settings\Event_model;
#[\AllowDynamicProperties]
class Userprojectplan extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->event_model = new Event_model();
    }
    public function getprojectplan($temp_id)
    {
        $data =[];
        // $temp_id = $this->request->getvar('temp_id');
        // print_r($temp_id);
        // exit();
        $tempdata = explode("_",$temp_id);
        $encryption =  $tempdata[0];
        $ciphering = "AES-128-CTR";
        // Use OpenSSl Encryption method
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;
        
        // Non-NULL Initialization Vector for encryption
        $encryption_iv = '1234567891011121';
        
        // Store the encryption key
        $encryption_key = "GeeksforGeeks";
        
        // Use openssl_encrypt() function to encrypt the data
        $decryption =openssl_decrypt ($encryption, $ciphering,
        $encryption_key, $options, $encryption_iv);
        $course_id = $decryption;
         $data['headerdata'] = $this->event_model->getheaderdata($course_id);
        $data['dealtimelineData'] = $this->event_model->dealtimeline_view($course_id);
       // $data['leveldata'] = $this->dropdown_model->getdropdownData(10);
       // print_r($data['dealtimelineData']);
        echo view('project_plan/project_planuser_view',$data);
       // echo view('templates/footer_view');

    }
}
?>