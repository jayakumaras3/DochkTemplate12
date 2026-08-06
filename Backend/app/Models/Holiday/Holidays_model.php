<?php

namespace App\Models\Holiday;

use CodeIgniter\Model;

class Holidays_model extends Model 
{
    function addholidays($newdata)
    {
        $builder = $this->db->table('holiday_list');
        $builder->insert($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function holiday_list($type, $year)
    {
        $builder = $this->db->table('holiday_list as hd');
        $builder->select('hd.*');
        $builder->where('hd.country', $type);
        $builder->where('hd.holiday_dt >=', date($year."-01-01"));
        $builder->where('hd.holiday_dt <=', date($year."-12-31"));
        $builder->where('hd.status', '1');
        $builder->orderBy('hd.holiday_dt');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function upcoming_holidays($type, $limit = 2)
    {
        $builder = $this->db->table('holiday_list as hd');
        $builder->select('hd.*');
        $builder->where('hd.country', $type);
        $builder->where('hd.holiday_dt >=', date('Y-m-d'));
        $builder->where('hd.status', '1');
        $builder->orderBy('hd.holiday_dt');
        $builder->limit($limit);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function usholiday_list()
    {
        $builder = $this->db->table('holiday_list as hd');
        $builder->select('hd.*');
        $builder->where('hd.country', '58');
        $builder->where('hd.holiday_dt >=', date("Y-m-d"));
        $builder->where('hd.status', '1');
        $builder->orderBy('hd.holiday_dt');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function deleteholidays($newdata, $id_hd)
    {
        $builder = $this->db->table('holiday_list');
        $builder->where('id_hd', $id_hd);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
}
