<?php
namespace App\Models\Cron;
use CodeIgniter\Model;
class Cron_model extends Model
{
    function insert_cron_time($data)
    {
        $builder = $this->db->table('cron_time');
        $builder->insert($data);
        return true;
    }

    function get_birthday_buddies($dateofbirth)
    {
        $month = date('m', strtotime($dateofbirth));
        $day = date('d', strtotime($dateofbirth));
        $builder = $this->db->table('users_personal_data as p');
        $builder->select('u.name as fname, u.last_name as lname');
        $builder->join('users as u', 'u.id_user  = p.userid', 'left');
        $builder->where('MONTH(DOB)', $month);
        $builder->where('DAY(DOB)', $day);
        $builder->where('u.valid', 1);
        $builder->where('p.status !=', 0);
        $query = $builder->get();
        //    echo $this->db->getLastQuery();
        //    exit();
        return $query->getResultArray();
    }
    public function get_employees_lwd()
    {
        $builder = $this->db->table('users as u');
        $builder->select('u.name as fname, u.last_name as lname, u.LWD');

        $today = date('Y-m-d');
        $five_days_later = date('Y-m-d', strtotime('+4 days'));

        $builder->where('u.LWD >=', $today);
        $builder->where('u.LWD <=', $five_days_later);
        $builder->where('u.valid', 1);

        $query = $builder->get();
        // echo $this->db->getLastQuery(); exit();
        return $query->getResultArray();
    }


}
