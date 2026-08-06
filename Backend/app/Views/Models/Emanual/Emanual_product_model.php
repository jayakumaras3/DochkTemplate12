<?php

namespace App\Models\Emanual;

use DateTime;

use CodeIgniter\Model;

class Emanual_product_model extends Model
{

    public function __construct()
    {
        $this->db = db_connect(); // Loading database
        // OR $this->db = \Config\Database::connect();
    }
    public function getAllProductDetails()
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('emanual_product as p');
        $builder->select('p.*,count(ed.product_id) as documentcount');
        $builder->join('emanual_document as ed', 'ed.product_id = p.em_id and ed.status =1', 'left');
        $builder->where('p.status !=', '0');
        $builder->groupBy('p.em_id');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function updatePageContentStatus($newdata, $emd_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('emanual_document');
        $builder->where('emd_id', $emd_id);
        $builder->update($newdata);
        return  true;
    }

    public function getProductDetails($em_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('emanual_product as p');
        $builder->select('p.*');
        $builder->where('p.em_id', $em_id);
        $builder->where('p.status !=', '0');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function addNewTroubleshoot($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('emanual_troubleshoot');
        $builder->insert($newdata);
        return  true;
    }
    public function getTroubleshootEditDetails($et_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('emanual_troubleshoot_link as etl');
        $builder->select('etl.etl_id as etl_id, et.*');
        $builder->join('emanual_troubleshoot as et', 'et.et_id = etl.link_id and et.status =1', 'left');
        $builder->where('etl.link_type', 1);
        $builder->where('etl.status', 1);
        $builder->where('etl.et_id', $et_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function  dropdown_trouble_links($et_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('emanual_troubleshoot as et');
        $builder->select('et.*');
        $builder->where('et.et_id', $et_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function getmajor_issues($emd_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('emanual_troubleshoot as et');
        $builder->select('et.*');
        $builder->where('et.document_id', $emd_id);
        $builder->where('et.status !=', 0);
        $builder->where('et.type', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function  dropdown_trouble_pages($et_id)
    {
        $builder = $this->db->table('emanual_troubleshoot_link as etl');
        $builder->select('etl.etl_id as etl_id, ep.*');
        $builder->join('emanual_page as ep', 'ep.empg_id = etl.link_id and ep.status =1', 'left');
        $builder->where('etl.link_type', 2);
        $builder->where('etl.status', 1);
        $builder->where('etl.et_id', $et_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }


    public function addNewDocument($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('emanual_document');
        $builder->insert($newdata);
        return  true;
    }

    public function addTroubleshootinglink($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('emanual_troubleshoot_link');
        $builder->insert($newdata);
        return  true;
    }
    public function addproductdetails($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('emanual_product');
        $builder->insert($newdata);
        $data['course_id'] = $db->insertID();
        return  $data;
    }
    public function editproductdetails($newdata, $em_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('emanual_product');
        $builder->where('em_id', $em_id);
        $builder->update($newdata);
        return  true;
    }

    public function updateTroubleshooting($newdata, $et_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('emanual_troubleshoot');
        $builder->where('et_id', $et_id);
        $builder->update($newdata);
        return  true;
    }
    public function assigndocumetdata($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('emanual_document');
        $builder->insert($newdata);
        $data = $builder->get()->getResultArray();
        return  $data;
    }
    // public function getAssigndocument($em_id)
    // {
    //     $builder = $this->db->table('emanual_document as ed');
    //     $builder->select('ed.*,epg.document_id,count(epg.document_id) as pagecount,epg.empg_id');
    //     $builder->join('emanual_page as epg', 'epg.document_id = ed.emd_id and epg.status =1', 'left');
    //     $builder->where('ed.product_id', $em_id);
    //     $builder->where('ed.status', '1');
    //     $builder->groupBy('ed.emd_id');
    //     $data = $builder->get()->getResultArray();
    //     //print_r($data);
    //     return $data;
    // }
    public function getAssigndocument($em_id)
    {
        $builder = $this->db->table('emanual_document as epg');
        $builder->select('epg.*');
        $builder->where('epg.product_id', $em_id);
        $builder->where('epg.status', '1');
        // $builder->groupBy('epg.product_id');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function getDocumentDetails($emd_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('emanual_document as d');
        $builder->select('d.*');
        $builder->where('d.emd_id', $emd_id);
        $builder->where('d.status !=', '0');
        $data = $builder->get()->getResultArray();
        return $data;
    }


    public function getdocumentID($hash)
    {
        // $builder = $this->db->table('scorm_courses as d');
        // $builder->select('d.*');
        // $builder->where('d.hash', $hash);
        // $builder->where('d.status !=', '0');
        // $data = $builder->get()->getResultArray();
        // return $data;

        $builder = $this->db->table('emanual_document as d');
        $builder->select('d.*');
        $builder->where('d.emd_id', $hash);
        $builder->where('d.status !=', '0');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getDocumentpageDetails($emd_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('emanual_document as d');
        $builder->select('d.*,epg.*,c.*');
        $builder->join('emanual_page as epg', 'epg.document_id = d.emd_id and epg.status =1', 'left');
        $builder->join('emanual_content as c', 'c.page_id = epg.empg_id and c.status =1', 'left');
        $builder->where('d.emd_id', $emd_id);
        $builder->where('d.status !=', '0');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    
    public function del_troble_link_value($newdata, $etl_id)
    {
        $builder = $this->db->table('emanual_troubleshoot_link');
        $builder->where('etl_id', $etl_id);
        $builder->update($newdata);
        return true;
    }
    public function deletedocument($newdata, $emd_id)
    {
        $builder = $this->db->table('emanual_document');
        $builder->where('emd_id', $emd_id);
        $builder->update($newdata);
        return true;
    }


    public function editdocumentdetails($newdata, $emd_id)
    {
        $builder = $this->db->table('emanual_document');
        $builder->where('emd_id', $emd_id);
        $builder->update($newdata);
        return true;
    }
    public function getAssignpages($emd_id)
    {
        $builder = $this->db->table('emanual_page as pg');
        $builder->select('pg.*');
        $builder->where('pg.document_id', $emd_id);
        $builder->where('pg.status', '1');
        $builder->orderBy('pg.page_number', 'asc');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function getAssigntranslationpages($emd_id)
    {
        $builder = $this->db->table('emanual_page as pg');
        $builder->select('pg.*,pl.translate_page_name');
        $builder->join('emanual_page_langauge as pl', 'pl.document_id = pg.document_id and pl.page_id = pg.empg_id', 'left');
        // $builder->join('dropdown as d', 'd.id_d = pl.lang_id', 'left');
        $builder->where('pg.document_id', $emd_id);
        $builder->where('pg.status', '1');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function addpagedata($newdata)
    {
        $builder = $this->db->table('emanual_page');
        $builder->insert($newdata);
        return  true;
    }
    public function getpageDetails($empg_id)
    {
        $builder = $this->db->table('emanual_page as d');
        $builder->select('d.*');
        $builder->where('d.empg_id', $empg_id);
        $builder->where('d.status !=', '0');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function editpagedetails($newdata, $empg_id)
    {
        $builder = $this->db->table('emanual_page');
        $builder->where('empg_id', $empg_id);
        $builder->update($newdata);
        return true;
    }
    public function addpagecontent($newdata, $empg_id)
    {
        $builder = $this->db->table('emanual_content as ec');
        $builder->select('ec.*');
        $builder->where('ec.page_id', $empg_id);
        $builder->where('ec.status', 1);
        $builder->orderBy('ec.sequence', 'desc');
        $builder->limit('1');
        $contentdata = $builder->get()->getResultArray();
        if (!empty($contentdata)) {
            $newdata['sequence'] = $contentdata[0]['sequence'] + 1;
        }
        $builder = $this->db->table('emanual_content');
        $builder->insert($newdata);
        $data = $builder->get()->getResultArray();
        if (isset($data)) {

            $data['status'] = "OK";
        } else {
            $data['status'] = "Error: ";
        }
        return $data;
    }
    public function getPagecontentdata($empg_id)
    {
        $builder = $this->db->table('emanual_content as ec');
        $builder->select('ec.*');
        $builder->where('page_id', $empg_id);
        $builder->where('status !=', 0);
        $builder->orderBy('sequence');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getPagetranslatecontentdata($empg_id)
    {
        $builder = $this->db->table('emanual_content as ec');
        $builder->select('ec.*');
        // $builder->join('emanual_content_language as cl', 'cl.emc_id = ec.emc_id and cl.page_id = ec.page_id', 'left');
        // $builder->join('dropdown as d', 'd.id_d = cl.lang_id', 'left');
        $builder->where('ec.page_id', $empg_id);
        $builder->where('ec.status !=', 0);
        $builder->orderBy('sequence');
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }

    public function getAllTroubleshootValues($emd_id)
    {
        $builder = $this->db->table('emanual_troubleshoot as et');
        $builder->select('et.*');
        $builder->where('et.document_id', $emd_id);
        $builder->where('et.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function getopenPagecontentdata($empg_id)
    {
        $builder = $this->db->table('emanual_content as ec');
        $builder->select('ec.*');
        $builder->where('page_id', $empg_id);
        $builder->where('status !=', 0);
        $builder->orderBy('sequence');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getopenPagelangcontentdata($empg_id, $lang_id)
    {
        $builder = $this->db->table('emanual_content as ec');
        $builder->select('ec.*');
        $builder->where('page_id', $empg_id);
        $builder->where('status', 5);
        $builder->orderBy('sequence');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function addsequence($sequence)
    {
        foreach ($sequence as $order => $itemId) {
            $builder = $this->db->table('emanual_content as ec');
            $builder->set('ec.sequence', $order);
            $builder->where('ec.emc_id', $itemId);
            $builder->update();
            $data = $builder->get()->getResultArray();
        }
        if (isset($data)) {

            $data['status'] = "OK";
        } else {
            $data['status'] = "Error: ";
        }
        return $data;
    }
    function deleteContent($newdata, $emc_id)
    {

        $builder = $this->db->table('emanual_content as ec');
        $builder->where('ec.emc_id', $emc_id);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        if (isset($data)) {
            $data['status'] = "OK";
        } else {
            $data['status'] = "error";
        }
        return $data;
    }
    function copyContent($newdata, $emc_id)
    {
        $builder = $this->db->table('emanual_content');
        $builder->insert($newdata);
        $data = $builder->get()->getResultArray();
        if (isset($data)) {
            $data['status'] = "OK";
        } else {
            $data['status'] = "error";
        }
        return $data;
    }
    function approvContent($newdata, $emc_id)
    {
        $builder = $this->db->table('emanual_content as ec');
        $builder->select('ec.emc_id');
        $approvContentdata = $builder->get()->getResultArray();
        if (in_array($newdata['reference_id'], array_column($approvContentdata, 'emc_id'))) {
            $builder = $this->db->table('emanual_content as ec');
            $builder->set('ec.status', 0);
            $builder->where('ec.emc_id', $newdata['reference_id']);
            $builder->update();
            $data = $builder->get()->getResultArray();
        }

        $builder = $this->db->table('emanual_content as ec');
        $builder->where('ec.emc_id', $emc_id);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();

        if (isset($data)) {
            $data['status'] = "OK";
        } else {
            $data['status'] = "error";
        }
        return $data;
    }

    public function getPagecount($emd_id)
    {
        // print_r($emd_id);
        // exit();
        $builder = $this->db->table('emanual_page as ep');
        $builder->select('count(ep.document_id) as pagecount');
        $builder->where('document_id', $emd_id);
        $builder->groupBy('document_id');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getAllpagedetails()
    {
        $builder = $this->db->table('emanual_page as ep');
        $builder->select('ep.*');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function addlanguagedata($newdata)
    {
        $builder = $this->db->table('emanual_lang');
        $builder->insert($newdata);
        $data = $builder->get()->getResultArray();
        if (isset($data)) {
            $data['status'] = "OK";
        } else {
            $data['status'] = "error";
        }
        return $data;
    }
    function deletelanguagedata($newdata, $el_id)
    {
        $builder = $this->db->table('emanual_lang');
        $builder->where('el_id', $el_id);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        if (isset($data)) {
            $data['status'] = "OK";
        } else {
            $data['status'] = "error";
        }
        return $data;
    }
    public function getLanguagedata($em_id)
    {
        $builder = $this->db->table('emanual_lang as el');
        $builder->select("el.*,d.name as lang_name");
        $builder->join('dropdown as d', 'd.id_d = el.lang_id and el.status =1', 'left');
        $builder->where('el.document_id', $em_id);
        $builder->where('el.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function addtranslatepagename($newdata, $page_id, $lang_id)
    {
        $builder = $this->db->table('emanual_page_langauge as pl');
        $builder->select('pl.*');
        $builder->where('pl.page_id', $page_id);
        $builder->where('pl.lang_id', $lang_id);
        $builder->where('pl.status', 1);
        $pglangdata = $builder->get()->getResultArray();
        if (empty($pglangdata)) {
            $builder = $this->db->table('emanual_page_langauge');
            $builder->insert($newdata);
            $data = $builder->get()->getResultArray();
        } else {
            $builder = $this->db->table('emanual_page_langauge');
            $builder->where('page_id', $page_id);
            $builder->update($newdata);
            $data = $builder->get()->getResultArray();
        }
        return $data;
    }
    public function addtransaltecontent($newdata, $emc_id, $lang_id)
    {
        $builder = $this->db->table('emanual_content_language as cl');
        $builder->select('cl.*');
        $builder->where('cl.emc_id', $emc_id);
        $builder->where('cl.lang_id', $lang_id);
        $builder->where('cl.status', 1);
        $pglangdata = $builder->get()->getResultArray();
        if (empty($pglangdata)) {
            $builder = $this->db->table('emanual_content_language');
            $builder->insert($newdata);
            $data = $builder->get()->getResultArray();
        } else {
            $builder = $this->db->table('emanual_content_language as clc');
            $builder->where('clc.emc_id', $emc_id);
            $builder->update($newdata);
            $data = $builder->get()->getResultArray();
        }
        if (isset($data)) {
            $data['status'] = "OK";
        } else {
            $data['status'] = "error";
        }
        return $data;
    }
    public function getLangname($lang_id)
    {
        $builder = $this->db->table('dropdown as d');
        $builder->select('d.name as lang_name');
        $builder->where('d.id_d', $lang_id);
        $builder->where('d.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getlangdetails($emd_id)
    {
        // print_r($emd_id);
        // exit();
        $builder = $this->db->table('emanual_lang as l');
        $builder->select('d.name as lang_name,l.lang_id');
        $builder->join('dropdown as d', 'd.id_d = l.lang_id and d.status=1', 'left');
        $builder->where('l.document_id', $emd_id);
        $builder->where('l.status', 1);
        $data = $builder->get()->getResultArray();
        // print_r($data);
        // exit();
        return $data;
    }
}
