<?php

namespace App\Controllers\Meeting;

use App\Controllers\BaseController;
use App\Models\Settings\Dropdown_model;
use App\Models\User_login\Login_model;
use App\Models\Settings\Event_model;
use App\Models\Meeting\Meeting_model;
use CodeIgniter\I18n\Time;
#[\AllowDynamicProperties]
class Meeting_agenda_client extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->dropdown_model = new Dropdown_model();
        $this->login_model = new Login_model();
        $this->event_model = new Event_model();
        $this->meeting_model = new Meeting_model();
    }
    public function index() //fetch data from users table to display
    {
        $data = [];
        $temp_id = $this->request->getVar('temp_id');
        $tempdata = explode("_", $temp_id);
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
        $decryption = openssl_decrypt(
            $encryption,
            $ciphering,
            $encryption_key,
            $options,
            $encryption_iv
        );
        $id_m = $decryption;
       // $data['meetingagendaheader'] = $this->meeting_model->meetingagendaheader_view($id_m);
        $data['meetingagenda'] = $this->meeting_model->meetingagendaclient_view($id_m);
      //  print_r($data['meetingagenda']);
       // print_r($data['meetingagenda']);
        echo view('meeting/meeting_agenda_client_view', $data);
        // echo view('templates/footer_view');
    }
    function updateremarksformat()
    {
        $value = $_POST['value'];
        $column = $_POST['column'];
        $id = $_POST['id'];
        //print_r($value);
        //print_r($column);
        //print_r($id);
        // exit();
        $result = $this->meeting_model->updateremarksformat($value, $column, $id);
        echo json_encode($result);
    }
}
