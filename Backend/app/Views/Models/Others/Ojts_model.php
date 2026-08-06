<?php

namespace App\Models\others;

use CodeIgniter\Model;

class Ojts_model extends Model
{

    function getojts_consolidatedData($ojts_id)
    {
        $builder = $this->db->table('ojts_details');
        $builder->select('*');
        $builder->where('ojts_id', $ojts_id);
        $builder->where('status !=', 0);
        $builder->orderBy('sl_no');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getsl_no($ojts_id)
    {
        $builder = $this->db->table('ojts_details');
        $builder->select('*');
        $builder->where('ojts_id', $ojts_id);
        $builder->where('status !=', 0);
        $builder->orderBy('sl_no', 'desc');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    // function getojts_consolidatedData()
    // {
    //     $builder = $this->db->table('ojts_consolidated_data');
    //     $builder->select('*');
    //     $builder->where('status !=', 0);
    //     $data = $builder->get()->getResultArray();
    //     return $data;
    // }
    function getojts_Data($ojd_id)
    {
        $builder = $this->db->table('ojts_details');
        $builder->select('*');
        $builder->where('ojd_id', $ojd_id);
        $builder->where('status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function addojtsfilename($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('ojts');
        $builder->insert($newdata);
        $data['insertID'] = $db->insertID();
        return $data;
    }
    function editfilenameOjts($newdata, $ojts_id)
    {
        $builder = $this->db->table('ojts');
        $builder->where('ojts_id', $ojts_id);
        $builder->update($newdata);
        return true;
    }
    function addOjts($newdata)
    {
        $builder = $this->db->table('ojts_details');
        $builder->insert($newdata);
        return true;
    }
    function editOjts($newdata, $ojd_id)
    {
        $builder = $this->db->table('ojts_details');
        $builder->where('ojd_id', $ojd_id);
        $builder->update($newdata);
        return true;
    }
    function viewojts_consolidatedData()
    {
        $builder = $this->db->table('ojts as o');
        $builder->select('o.*');
        $builder->where('o.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function viewojts_consolidatedpdfData()
    {
        $builder = $this->db->table('ojts as o');
        $builder->select('o.*,od.*');
        $builder->join('ojts_details as od', 'od.ojts_id = ojts_id', 'left');
        $builder->where('o.status !=', 0);
        $builder->where('od.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getojtsfilenameData($ojts_id)
    {
        $builder = $this->db->table('ojts as o');
        $builder->select('o.*');
        $builder->where('ojts_id', $ojts_id);
        $builder->where('o.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    // function viewojts_consolidatedData()
    // {
    //     $builder = $this->db->table('ojts_consolidated_data as o');
    //     $builder->select(' o.sl_no,o.filename');
    //     $builder->where('o.status !=',0);
    //     $builder->groupby('o.filename');
    //     $data = $builder->get()->getResultArray();
    //     return $data;
    // }
    function pdfojts_consolidatedData($ojts_id)
    {
        $builder = $this->db->table('ojts_details as od');
        $builder->select(' od.*,o.filename');
        $builder->join('ojts as o', 'o.ojts_id = od.ojts_id', 'left');
        $builder->where('od.status !=', 0);
        $builder->where('od.ojts_id', $ojts_id);
        $builder->orderBy('od.sl_no');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function addojtsgroupname($newdata)
    {
        $builder = $this->db->table('ojts_group');
        $builder->insert($newdata);
        return true;
    }
    function getojtsgroupdata()
    {
        $builder = $this->db->table('ojts_group as og');
        $builder->select(' og.*');
        $builder->where('og.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getojts_groupData($oj_group_id)
    {
        $builder = $this->db->table('ojts_group as og');
        $builder->select(' og.*');
        $builder->where('og.status !=', 0);
        $builder->where('og.oj_group_id', $oj_group_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function rowojts_groupData($oj_group_id)
    {
        $builder = $this->db->table('ojts_group_assigned as og');
        $builder->select(' og.*');
        $builder->where('og.status !=', 0);
        $builder->where('og.oj_group_id', $oj_group_id);
        $builder->orderBy('og.sequence', 'desc');

        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
    function editOjtsgroup($newdata, $oj_group_id)
    {
        $builder = $this->db->table('ojts_group');
        $builder->where('oj_group_id', $oj_group_id);
        $builder->update($newdata);
        return true;
    }
    function getojts_group_assigned($oj_group_id)
    {
        $builder = $this->db->table('ojts_group_assigned as og');
        $builder->select(' og.*,o.filename,o.language,gr.group_name');
        $builder->join('ojts as o', 'o.ojts_id = og.ojts_id', 'left');
        $builder->join('ojts_group as gr', 'gr.oj_group_id = og.oj_group_id', 'left');
        $builder->where('og.status !=', 0);
        $builder->where('o.status !=', 0);
        $builder->where('og.oj_group_id', $oj_group_id);
        $builder->orderBy("
            CASE
                WHEN UPPER(LEFT(o.language, 1)) BETWEEN 'E' AND 'Z' THEN 1
                WHEN UPPER(LEFT(o.language, 1)) BETWEEN 'A' AND 'C' THEN 2
                ELSE 3
            END", 'ASC', false);
        $builder->orderBy('o.language', 'ASC');
        $builder->orderBy('o.filename', 'ASC');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function add_ojts_to_gr($newdata)
    {
        foreach ($newdata['ojts_id'] as $ojts_id) {
            $ojts_group_assigned = [
                'ojts_id' => $ojts_id,
                'oj_group_id' => $newdata['oj_group_id'],
                'sequence' => isset($newdata['sequence']) ? $newdata['sequence'] : '1',
                'status' => $newdata['status'],
                'createdby' => $newdata['createdby'],
                'createdon' => time(),
                'last_updated_by' =>  session()->get('id_user'),
                'last_updated_on' => time()
            ];
            $builder = $this->db->table('ojts_group_assigned');
            $builder->insert($ojts_group_assigned);
            $data = $builder->get()->getResultArray();
            if (!empty($data)) {
                $data['status'] = "OK";
            } else {
                $data['status'] = "Error";
            }
        }
        return  $data;
    }
    function assign_ojts_delete($newdata, $og_assign_id)
    {
        $builder = $this->db->table('ojts_group_assigned');
        $builder->where('og_assign_id', $og_assign_id);
        $builder->update($newdata);
        return true;
    }
    function getlanguagefromfilename()
    {
        $languages = ['English', 'Spanish', 'French', 'Russian', 'Portuguese', 'Bahasa', 'Arabic', 'German', 'Italian']; // Add more as needed

        $builder = $this->db->table('ojts as o');
        $builder->select('o.*');
        $builder->where('o.status !=', 0);
        $data = $builder->get()->getResultArray();

        if (!empty($data)) {
            foreach ($data as $file) {
                $detectedLang = null;

                foreach ($languages as $lang) {
                    if (stripos($file['filename'], $lang) !== false) { // case-insensitive check
                        $detectedLang = $lang;
                        break; // Stop at first match
                    } else {
                        $detectedLang = 'English';
                    }
                }

                if ($detectedLang) {
                    $builder = $this->db->table('ojts');
                    $builder->set('language', $detectedLang);
                    $builder->where('ojts_id', $file['ojts_id']);
                    $builder->update();
                }
            }
            echo 'success';
        } else {
            echo lang('Messages.Error_0001');
        }
    }
}
