<?php namespace App\Models\Settings;

use CodeIgniter\Model;

class Email_model extends Model
{
    protected $db;
    public function __construct()
    {
        $this->db = db_connect(); // Loading database
        
        // OR $this->db = \Config\Database::connect();
    }
    function mycartdata($uid,$hash){
        $checkEmail = $this->db->query("SELECT * FROM mycart where uid = '" . $uid . "' and keyval='".$hash."'");
        $resultcheckEmail = $checkEmail->getResultArray($checkEmail);
        return $resultcheckEmail;
    }
    function savemycartdata($data){
        $builder = $this->db->table('mycart');
        $builder->insert($data);
        // $data = $builder->get()->getResultArray();
        $data = true;
    } 
}
?>