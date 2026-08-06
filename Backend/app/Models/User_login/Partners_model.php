<?php

namespace App\Models\User_login;

use App\Controllers\Dropdown;
use CodeIgniter\Model;

class Partners_model extends Model
{
    protected $db;
    public function __construct()
    {
        $this->db = db_connect(); // Loading database
    }
    public function addPartner($newdata)
    {
        // print_r($newdata);
        // exit();
        $db = \Config\Database::connect();
        $builder = $this->db->table('partners');
        $builder->insert($newdata);
        // $data = $builder->get()->getResultArray();
        $data['insertID'] = $db->insertID();
        return  $data;
    }
    public function getParnterDetails()
    {

        $builder = $this->db->table('partners as p');
        $builder->select("p.*");
        $builder->where('p.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function editImagepartner($newdata, $id_c)
    {
        $builder = $this->db->table('client as p');
        $builder->where('p.id_c', $id_c);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return  $data;
    }
    public function editpartnerdetails($newdata, $pr_id)
    {
        // print_r($newdata);
        // exit();
        $builder = $this->db->table('partners as p');
        $builder->where('p.pr_id', $pr_id);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return  $data;
    }
    public function getPartnersDetails($pr_id)
    {
        $builder = $this->db->table('partners as p');
        $builder->select('p.*');
        $builder->where('p.pr_id', $pr_id);
        $builder->where('p.status', 1);
        $data = $builder->get()->getResultArray();
        // print_r($data);
        // exit();
        return  $data;
    }

    function verifyCode($code)
    {
        $builder = $this->db->table('client as p');
        $builder->select('p.*');
        $builder->where('p.code', $code);
        $data = $builder->get()->getResultArray();
        return  $data;
    }
    function addcountrytoclient($newdata, $id_c)
    {
        $builder = $this->db->table('client as p');
        $builder->where('p.id_c', $id_c);
        $builder->where('p.status', 1);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        if (!empty($data)) {
            $data['status'] = "OK";
        } else {
            $data['status'] = "Error";
        }
        return $data;
    }
    function getClientCountofPartner($client)
    {
        // print_r("tt");
        // exit();
        $db = \Config\Database::connect();
        $qc = $db->query("SELECT count(id_c) as partners_client_count FROM client  where partner_code =$client and status = 1");
        $resultc = $qc->getResultArray();
        // print_r($resultc);
        // exit();
        return $resultc;
    }

    function getPartneruserCount($client)
    {
        $db = \Config\Database::connect();
        $qc = $db->query("SELECT GROUP_CONCAT(DISTINCT(c.id_c) ORDER BY c.id_c ASC SEPARATOR ',') as clients  FROM client as c  
             where c.partner_code = " . $client . " and c.status = 1");
        $totalclientsofpartner = $qc->getResultArray();
        //  echo $this->db->getLastQuery();
        //     exit();
        if ($totalclientsofpartner) {
            $data = $db->query("SELECT count(u.id_user) as user_count
             FROM dropdown_users as du 
             left join users as u on u.id_user= du.fk_id_user and u.valid = 1 where fk_id_d
             in (" . $totalclientsofpartner[0]['clients'] . ") and du.fk_id_dc = 1  and du.status = 1 and du.createdon !='0'");
            $resultc = $data->getResultArray();
            // echo $this->db->getLastQuery();
            // exit();
            return $resultc;
        } else {
            return 0;
        }
    }
    function getPartneruserdetails()
    {
        $db = \Config\Database::connect();
        $qc =  $db->query("SELECT count(u.id_user) as user_count  ,GROUP_CONCAT(DISTINCT(p.id_c ) ORDER BY  p.id_c  ASC SEPARATOR ',') as partner_id,GROUP_CONCAT(DISTINCT(du.fk_id_d) ORDER BY du.fk_id_d ASC SEPARATOR ',') as clientid,DATE_FORMAT(FROM_UNIXTIME(du.createdon), '%m') as month,DATE_FORMAT(FROM_UNIXTIME(du.createdon), '%Y') as year FROM client as c  
       
        left join client as p on p.id_c  = c.partner_code  and p.status =1
        left join dropdown_users as du on du.fk_id_d = c.id_c  and du.fk_id_dc = 1 and du.status =1
        left join users as u on u.id_user= du.fk_id_user and u.valid = 1
        where  c.status = 1 and du.createdon !='0' 
        group by DATE_FORMAT(FROM_UNIXTIME(du.createdon), '%Y-%m'),c.partner_code order by p.id_c");
        $data = $qc->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        if (!empty($data)) {
            $builder = $this->db->table('partner_user_graph_report');
            $query = $builder->truncate();

            if (!$query) {
                $this->db->query('TRUNCATE TABLE client_user_graph_report');
            }
            $partnerCounts = [];

            foreach ($data as $key => $eachdata) {
                $partner_id = $eachdata['partner_id'];
                $user_count = $eachdata['user_count'];
                $everymonth_count = isset($partnerCounts[$partner_id]) ? $partnerCounts[$partner_id] + $user_count : $user_count;
                $partnerCounts[$partner_id] = $everymonth_count;
                $newdata = [
                    'partner_id' => $eachdata['partner_id'],
                    'client_id' => $eachdata['clientid'],
                    'user_count' => $partnerCounts[$partner_id],
                    'user_count_per_month' => $eachdata['user_count'],
                    'month' => isset($eachdata['month']) ? $eachdata['month'] : '0',
                    'year' => isset($eachdata['year']) ? $eachdata['year'] : '0',
                    'status' => 1,
                    'createdby' => session()->get('id_user'),
                    'createdon' => time(),
                ];
                $builder = $this->db->table('partner_user_graph_report');
                $builder->insert($newdata);
            }
            return $data;
        } else {
            return 0;
        }
    }
    function aristo_user_graph_report()
    {
        $date = date('Y');
        $builder = $this->db->table('aristo_user_graph_report as r');
        $builder->select('r.*');
        $builder->where('r.year', $date);
        $builder->where('r.status', 1);
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
    function getTotaluserCountofPartner($partner)
    {

        $date = date('Y');
        $builder = $this->db->table('partner_user_graph_report as r');
        $builder->select('r.*');
        $builder->where('r.partner_id', $partner);
        $builder->where('r.year', $date);
        $builder->where('r.status', 1);
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
    function getTotalusersofclientsPartner($partner)
    {
        $builder = $this->db->table('client as c');
        $builder->select('c.id_c,c.client_name,count(u.id_user) as user_count');
        $builder->join('dropdown_users as du', 'du.fk_id_d = c.id_c and du.fk_id_dc =1 and du.status =1', 'left');
        $builder->join('users as u', 'u.id_user = du.fk_id_user and u.valid =1', 'left');
        $builder->where('c.partner_code', $partner);
        $builder->where('c.status', 1);
        $builder->groupBy('c.id_c', 1);
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
    function gettotalusersofPartner()
    {
        $builder = $this->db->table('client as c');
        $builder->select('c.id_c,c.client_name,count(u.id_user) as user_count,c.code,c.discount,c.type');
        $builder->join('client as c1', 'c1.partner_code = c.id_c', 'left');
        $builder->join('dropdown_users as du', 'du.fk_id_d = c1.id_c and du.fk_id_dc =1 and du.status =1', 'left');
        $builder->join('users as u', 'u.id_user = du.fk_id_user and u.valid =1', 'left');
        $builder->where('c.status', 1);
        $builder->groupBy('c.id_c');
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
    function getclientlistofpartners($partner)
    {
        $builder = $this->db->table('client as c');
        $builder->select('c.id_c,c.client_name,c.code,c.discount,c.type,c.partner_code');
        $builder->where('c.partner_code', $partner);
        $builder->where('c.status', 1);
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
    function gettotalUserperMonth()
    {
        $db = \Config\Database::connect();
        $qc =  $db->query("SELECT count(u.id_user) as user_count  ,DATE_FORMAT(FROM_UNIXTIME(du.createdon), '%m') as month,DATE_FORMAT(FROM_UNIXTIME(du.createdon), '%Y') as year FROM users as u  
        left join dropdown_users as du on du.fk_id_user = u.id_user and du.fk_id_dc = 1 and du.status =1
        left join client as p on p.id_c = du.fk_id_d  and p.status =1
        where  u.valid = 1 
        group by DATE_FORMAT(FROM_UNIXTIME(du.createdon), '%Y-%m')");
        $data = $qc->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        if (!empty($data)) {
            $builder = $this->db->table('aristo_user_graph_report');
            $query = $builder->truncate();

            if (!$query) {
                $this->db->query('TRUNCATE TABLE aristo_user_graph_report');
            }
            $everymonth_count = 0;
            foreach ($data as $key => $eachdata) {
                $user_count = $eachdata['user_count'];
                $everymonth_count = $everymonth_count + $user_count;
                $newdata = [
                    'user_count' => $everymonth_count,
                    'user_count_per_month' => $eachdata['user_count'],
                    'month' => isset($eachdata['month']) ? $eachdata['month'] : '0',
                    'year' => isset($eachdata['year']) ? $eachdata['year'] : '0',
                    'status' => 1,
                    'createdby' => session()->get('id_user'),
                    'createdon' => time(),
                ];
                $builder = $this->db->table('aristo_user_graph_report');
                $builder->insert($newdata);
            }
            return $data;
        } else {
            return 0;
        }
    }
    public function getpartnerCourseCount($client, $lesson_status)
    {
        $builder = $this->db->table('client as c');
        $builder->select('count(s.sc_uid) as c_count');
        $builder->join('dropdown_users as du', 'du.fk_id_d = c.id_c and du.status =1', 'left');
        $builder->join('scorm_user_details as s', 's.student_id = du.fk_id_user and s.status =1', 'left');
        $builder->where('c.partner_code', $client);
        $builder->where('c.status', 1);
        $builder->whereIn('s.lesson_status', $lesson_status);
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
    public function getclientCourseCount($client, $lesson_status)
    {
        $builder = $this->db->table('client as c');
        $builder->select('count(s.sc_uid) as course_count');
        $builder->join('dropdown_users as du', 'du.fk_id_d = c.id_c and du.status =1', 'left');
        $builder->join('scorm_user_details as s', 's.student_id = du.fk_id_user and s.status =1', 'left');
        $builder->where('c.id_c', $client);
        $builder->where('c.status', 1);
        $builder->whereIn('s.lesson_status', $lesson_status);
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
    function getActivePartners()
    {
        $builder = $this->db->table('partners as p');
        $builder->select('count(p.pr_id) as partner_count');
        $builder->where('p.status', 1);
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
    function getActiveClients()
    {
        $builder = $this->db->table('client as c');
        $builder->select('count(c.id_c) as client_count');
        $builder->where('c.status', 1);
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
    function getTotalUsers()
    {
        $builder = $this->db->table('users as u');
        $builder->select('count(u.id_user) as totalUsers');
        $builder->where('u.valid', 1);
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
    function getTotalUsersofNonaristo()
    {
        $client_id = ['1', '163', '164', '158'];
        $builder = $this->db->table('users as u');
        $builder->select('count(u.id_user) as totalUsers');
        $builder->where('client_id !=', 1);
        $builder->whereNotIn('client_id ', $client_id);
        $builder->where('u.valid', 1);
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
    function getallcoursecompeted()
    {
        $completed = ['completed', 'passed'];
        $builder = $this->db->table('scorm_user_details as u');
        $builder->select('count(u.sc_uid) as completed_course_count');
        $builder->whereIn('u.lesson_status', $completed);
        $builder->where('u.status', 1);
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
    function userdata()
    {
        $builder = $this->db->table("users as u");
        $builder->select('u.id_user,GROUP_CONCAT(DISTINCT(c.id_c) ORDER BY c.id_c  ASC SEPARATOR ", ") as clientid,GROUP_CONCAT(DISTINCT(c.partner_code) ORDER BY c.partner_code  ASC SEPARATOR ", ") as partner,GROUP_CONCAT(DISTINCT(c.code) ORDER BY c.code  ASC SEPARATOR ", ") as code');
        $builder->join('dropdown_users as du', 'du.fk_id_user = u.id_user and du.status =1', "left");
        $builder->join('client as c', 'c.id_c = du.fk_id_d  and du.fk_id_dc=1 and du.status =1', "left");
        $builder->where('u.valid', '1');
        $builder->groupBy('u.id_user', 'asc');
        $data = $builder->get()->getResultArray();
        //  echo $this->db->getLastQuery();
        // exit();
        if ($data) {
            foreach ($data as $eachdata) {
                $builder = $this->db->table("users as u");
                $builder->set('client_id', $eachdata['clientid']);
                $builder->set('partner_id', $eachdata['partner']);
                $builder->set('partner_code', $eachdata['code']);
                $builder->where('id_user', $eachdata['id_user']);
                // $builder->where('client_id',0);
                // $builder->where('partner_id',0);
                $builder->where('partner_code', '');
                $builder->update();
            }
        }
    }
    public function partneruserlist()
    {

        $builder = $this->db->table('partners as p');
        $builder->select('p.*');
        $builder->join('client as c', 'c.partner_code = p.pr_id and c.status =1', 'left');
        $builder->join('dropdown_users as du', 'du.fk_id_d = c.id_c and du.fk_id_dc = 1 and  du.status=1', 'left');
        $builder->join('users as u1', 'u1.id_user = du.fk_id_user and u1.valid =1', 'left');
        $builder->join('users as u', 'u.id_user = c.createdby', 'left');
        $builder->where('p.status', '1');
        $builder->groupBy('p.pr_id');
        $builder->orderBy('p.pr_id', 'ASC');
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
}
