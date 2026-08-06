<?php 

namespace App\Validation;


use App\Models\User_login\Login_model;

class UserRules
{
    protected $login_model;
    public function __construct()
    {
       // $this->db = db_connect(); // Loading database
        // OR $this->db = \Config\Database::connect();
        $this->login_model = new Login_model();
    }
    public function validateUser(string $str, string $fields, array $data)
    {
        //$model = new Login_model();
        $emailid = $data['username'] ?? ($_POST['username'] ?? '');
        $password = $data['password'] ?? ($_POST['password'] ?? $str);

        if ($emailid === '' || $password === '') {
            return false;
        }

        $user = $this->login_model->login_view($emailid);
        if (!$user || empty($user[0]['password'])) {
            return false;
        }

        return password_verify($password, $user[0]['password']);
    }
    public function startdt_check(string $str,string $fields,array $data){
        $start_date = strtotime($data['start_date']);
        $end_date = strtotime($data['end_date']);
        if ($end_date >= $start_date){
            return True;
        }else{
            return False;
        }
    }
}
?>