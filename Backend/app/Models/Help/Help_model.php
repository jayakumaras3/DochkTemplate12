<?php

namespace App\Models\Help;

use CodeIgniter\Model;

class Help_model extends Model
{
    function get_help_documents()
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('emanual_document as epg');
        $builder->select('epg.document_name, epg.emd_id, epg.last_updated_on as last_updated_on');
        $builder->where('epg.product_id', 8);
        $builder->groupBy('epg.emd_id');
        $builder->where('epg.status', 1);
        $builder->orderBy('epg.sequence', 'ASC');
        $data = $builder->get()->getResultArray();
        return $data;
    }
}
