<?php

namespace App\Models\eTrack;

use CodeIgniter\Model;

class Sales_model extends Model
{

    public function get_sales_data(int $sales_manager, int $status)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('et_sales as et_sales');
        $builder->select('et_sales.*');
        $builder->where('et_sales.sales_manager', $sales_manager);
        $builder->orderBy('et_sales.sales_manager', 'ASC');
        $builder->orderBy('et_sales.client', 'ASC');
        if ($status == 20) {
            $builder->where('et_sales.status', 0);
        } else if ($status != 1) {
            $builder->where('et_sales.status', $status);
        } else {
            $builder->where('et_sales.status !=', 0);
            $builder->where('et_sales.status <', 10);
        }
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function get_closed_sales_value(int $sales_manager)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('et_sales as et_sales');
        $builder->select('SUM(et_sales.value) as closed_sales_value');
        $builder->where('et_sales.status', 11); // Assuming 11 is the status for closed sales
        $builder->where('et_sales.sales_manager', $sales_manager);
        $query = $builder->get();
        return $query->getRowArray()['closed_sales_value'] ?? 0;
    }   
    public function add_sales_data(array $data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('et_sales');
        $builder->insert($data);
        return $db->insertID();
    }

    public function add_sales_history(array $sales_history_data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('et_sales_history');
        $builder->insert($sales_history_data);
        return true;
    }

    public function get_sales_data_by_id(int $sales_id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('et_sales as et_sales');
        $builder->select('et_sales.*');
        $builder->where('et_sales.sales_id', $sales_id);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function get_sales_history(int $sales_id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('et_sales_history as et_sales_history');
        $builder->select('et_sales_history.*');
        $builder->where('et_sales_history.sales_id', $sales_id);
        $builder->where('et_sales_history.status !=', 0);
        $builder->orderBy('et_sales_history.sales_history_id', 'DESC');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function update_sales_data(int $sales_id, array $data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('et_sales');
        $builder->where('sales_id', $sales_id);
        $builder->update($data);
        return true;
    }
}
