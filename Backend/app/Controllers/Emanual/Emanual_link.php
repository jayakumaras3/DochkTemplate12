<?php

namespace App\Controllers\Emanual;

use App\Controllers\BaseController;

use App\Models\Emanual\Emanual_product_model;
use App\Models\Settings\Dropdown_model;

#[\AllowDynamicProperties]
class Emanual_link extends BaseController
{
    public function __construct()
    {
        $this->emanual_product_model = new Emanual_product_model();
        $this->dropdown_model = new Dropdown_model();
    }
    public function index()
    {
            
        $data = [];
        helper(['form']);
        if (isset($_POST['tempass'])) {
            $data['tempass'] = $_POST['tempass'];
            $_SESSION['tempass'] =  $data['tempass'];
        } else if (isset($_GET['tempass'])) {
            $data['tempass'] = $_GET['tempass'];
        } else if (isset($_SESSION['tempass'])) {
            $data['tempass'] = $_SESSION['tempass'];
        } else {
            return redirect()->to(base_url() . '/Emanual/emanual_product');
        }
        if (isset($_POST['empg_id'])) {
            $data['empg_id'] = $_POST['empg_id'];
            $_SESSION['empg_id'] =  $data['empg_id'];
        } else if (isset($_GET['empg_id'])) {
            $data['empg_id'] = $_GET['empg_id'];
        } else if (isset($_SESSION['empg_id'])) {
            $data['empg_id'] = $_SESSION['empg_id'];
        } else {
            return redirect()->to(base_url() . '/Emanual/emanual_product');
        }
        $data['tempass'] = $data['tempass'];
        $docID = $this->emanual_product_model->getdocumentID($data['tempass']);
        $data['emd_id'] = $docID[0]['emd_id'];
        $data['document_name'] = $docID[0]['document_name'];
        $empg_id =  base64_decode($data['empg_id']);
        $data['getAllpagedetails'] =  $this->emanual_product_model->getAllpagedetails();
        $documentdata =  $this->emanual_product_model->getPagecount($data['emd_id']);
        $data['totalPages'] = $documentdata[0]['pagecount'];
        $pagedetails =  $this->emanual_product_model->getpageDetails($empg_id);
        $data['pagealldetails'] = $this->emanual_product_model->getAssignpages($data['emd_id']);

        $data['page_name'] =  isset($pagedetails[0]['page_name']) ? $pagedetails[0]['page_name'] : '';
        $data['header'] = 'e-Manual Documet';
        $data['header_link'] = '/Emanual/emanual_product/document_view';
        $data['sub_header_1'] = 'Edit Document';
        $data['form_link'] = 'Emanual/emanual_product/editdocument';
        $data['typeval'] = 2;
        $data['form_url_1'] = 'Emanual/emanual_product/document_thumbnail_upload';
        $data['pagecontentdata'] = $this->emanual_product_model->getopenPagecontentdata($empg_id);
        //  echo view('emanual/emanual_link_view', $data);
        echo view('emanual/link_view', $data);
    }
    public function emanual_lang($emd_id, $empg_id)
    {
        $data = [];
        helper(['form']);
        // print_r($emd_id);
        if (isset($emd_id)) {
            $data['emd_id'] = $emd_id;
        } else {
            return redirect()->to(base_url() . '/Emanual/emanual_product');
        }
        // print_r($data['emd_id']);
        // if (isset($_POST['tempass'])) {
        //     $data['tempass'] = $_POST['tempass'];
        //     $_SESSION['tempass'] =  $data['tempass'];
        // } else if (isset($_GET['tempass'])) {
        //     $data['tempass'] = $_GET['tempass'];
        // } else if (isset($_SESSION['tempass'])) {
        //     $data['tempass'] = $_SESSION['tempass'];
        // } else {
        //     return redirect()->to(base_url() . '/Emanual/emanual_product');
        // }
        if (isset($_POST['empg_id'])) {
            $data['empg_id'] = $_POST['empg_id'];
            $_SESSION['empg_id'] =  $data['empg_id'];
        } else if (isset($_GET['empg_id'])) {
            $data['empg_id'] = $_GET['empg_id'];
        } else if (isset($_SESSION['empg_id'])) {
            $data['empg_id'] = $_SESSION['empg_id'];
        } else if (isset($empg_id)) {
            $data['empg_id'] = $empg_id;
        } else {
            return redirect()->to(base_url() . '/Emanual/emanual_product');
        }
        $data['page_id'] =  base64_decode($data['empg_id']);
        $data['getlangdata'] = $this->emanual_product_model->getlangdetails($data['emd_id']);
        echo view('emanual/emanual_language_view', $data);
    }
    public function trouble()
    {
        $data = [];
   
        
        if (isset($_POST['emd_id'])) {
            $data['emd_id'] = $_POST['emd_id'];
            $_SESSION['emd_id'] =  $data['emd_id'];
        } else if (isset($_SESSION['emd_id'])) {
            $data['emd_id'] = $_SESSION['emd_id'];
        } else {
            return redirect()->to(base_url() . 'my_training');
        }

      

        $data['product_details'] = $this->emanual_product_model->getDocumentDetails($data['emd_id']);
        if (isset($_POST['et_id']) && $_POST['et_id'] != 0) {
            $data['et_id'] = $_POST['et_id'];
            $data['troubleshootName'] = $this->emanual_product_model->dropdown_trouble_links($data['et_id']);
            $data['trouble_links']  =  $this->emanual_product_model->getTroubleshootEditDetails($data['et_id']);
           
        } else {
            $data['troubleshootName'] = array();
            $data['trouble_links']  =  array();
            $data['major_issues']  =  $this->emanual_product_model->getmajor_issues($data['emd_id']);
         
        }
        echo view('emanual/troubleshoot', $data);
    }
    public function trouble_page()
    {
        $data = [];
        if (isset($_POST['et_id'])) {
            $data['et_id'] = $_POST['et_id'];
            $_SESSION['et_id'] =  $data['et_id'];
        } else if (isset($_SESSION['et_id'])) {
            $data['et_id'] = $_SESSION['et_id'];
        } else {
            return redirect()->to(base_url() . 'my_training');
        }
        $data['trouble_pages']  =  $this->emanual_product_model->dropdown_trouble_pages($data['et_id']);
        if (count($data['trouble_pages']) > 0) {
            $empg_id =  $data['trouble_pages'][0]['empg_id'];
            return redirect()->to(base_url() . 'Emanual/emanual_link/link_v2/' . $empg_id);
        } else {
            return redirect()->to(base_url() . 'Emanual/emanual_link/trouble');
        }
    }
    public function link()
    {
        $data = [];
        if (isset($_POST['emd_id'])) {
            $data['emd_id'] = $_POST['emd_id'];
            $_SESSION['emd_id'] =  $data['emd_id'];
        } else if (isset($_SESSION['emd_id'])) {
            $data['emd_id'] = $_SESSION['emd_id'];
        } else {
            return redirect()->to(base_url() . 'my_training');
        }

        $data['getAllPages'] = $this->emanual_product_model->getAssignpages($data['emd_id']);
        $_SESSION['empg_id'] =  $data['getAllPages'][0]['empg_id'];
        return redirect()->to(base_url() . 'Emanual/emanual_link/link_v1');
    }
    public function link_v2($empg_id)
    {
        $data = [];
        // if (isset($emd_id)) {
        $_SESSION['empg_id'] =  $empg_id;
        return redirect()->to(base_url() . 'Emanual/emanual_link/link_v1');
        // } else {
        //   return redirect()->to(base_url() . 'my_training');
        // }

    }
    public function link_v1()
    {
        //echo $_SESSION['empg_id'];
        //   exit();
        $data = [];
        if (isset($_POST['empg_id'])) {
            $data['empg_id'] = $_POST['empg_id'];
            $_SESSION['empg_id'] =  $data['empg_id'];
        } else if (isset($_SESSION['empg_id'])) {
            $data['empg_id'] = $_SESSION['empg_id'];
        } else {
            return redirect()->to(base_url() . 'my_training');
        }

        $data['pageDetails'] = $this->emanual_product_model->getpageDetails($data['empg_id']);
        $docID = $data['pageDetails'][0]['document_id'];
        $data['page_name'] = $data['pageDetails'][0]['page_name'];
        $data['page_number'] = $data['pageDetails'][0]['page_number'];

        //  $data['getPagecontentdata'] = $this->emanual_product_model->getPagecontentdata($data['empg_id']);
        // echo $data['empg_id'];
        // exit();
        $data['pagecontentdata'] = $this->emanual_product_model->getopenPagecontentdata($data['empg_id']);

        $data['getAllPages'] = $this->emanual_product_model->getAssignpages($docID);
        $data['getDocumentDetails'] = $this->emanual_product_model->getDocumentDetails($docID);



        echo view('emanual/link_view_v1', $data);
    }
}
