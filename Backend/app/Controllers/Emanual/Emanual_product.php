<?php

namespace App\Controllers\Emanual;

use App\Controllers\BaseController;

use App\Models\Emanual\Emanual_product_model;
use App\Models\Settings\Dropdown_model;
use App\Models\SCORM\Scorm_course_model;

#[\AllowDynamicProperties]
class Emanual_product extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->emanual_product_model = new Emanual_product_model();
        $this->dropdown_model = new Dropdown_model();
        $this->scorm_course_model = new Scorm_course_model();
    }
    public function index() //fetch data from users table to display
    {
        $data = [];
        helper(['form']);
        $data['header'] = 'e-Manual';
        $data['create_new_course_link'] = 'Emanual/emanual_product/product_add_view';
        $data['settings_link'] = 'Emanual/emanual_product/document_view';
        $data['edit_link'] = 'Emanual/emanual_product/product_edit_view';
        $userlevel = session()->get('userlevel');
        $array  = array_map('intval', str_split($userlevel));
        if (in_array(6, $array)) {
            $data['productDetails'] = $this->emanual_product_model->getAllProductDetails();
            echo view('templates/header_view', $data);
            echo view('emanual/product_list_view', $data);
            echo view('templates/footer_view');
        } else {
            return redirect()->to(base_url() . 'my_training');
        }
    }
    public function product_add_view()
    {
        $userlevel = session()->get('userlevel');


        $data = [];
        helper(['form']);
        $data['header'] = 'e-Manual Product';
        $data['header_link'] = 'Emanual/emanual_product';
        $data['sub_header_1'] = 'Create New Product';
        $data['form_link'] = 'Emanual/emanual_product/addproduct';
        $clientid = session()->get('client');
        $data['typeval'] = 50 + $clientid;

        echo view('templates/header_view', $data);
        echo view('emanual/products_add_view', $data);
        echo view('templates/footer_view');
    }
    public function addproduct()
    {
        $data = [];
        helper(['form']);
        if ($this->request->getPost()) {
            $newdata = [
                'product_name' => $this->request->getVar('product_name'),
                'description' => $this->request->getVar('description'),
                'status' => '1',
                'last_updated_by' =>  session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $this->emanual_product_model->addproductdetails($newdata);
            session()->setFlashdata('success', lang('Messages.Success_0011'));
            return redirect()->to(base_url() . 'Emanual/emanual_product');
        }
    }
    public function product_edit_view()
    {
        $data = [];
        helper(['form']);
        $data['header'] = 'e-Manual Product';
        $data['header_link'] = 'Emanual/emanual_product';
        $data['sub_header_1'] = 'Edit e-Manual';
        $data['form_link'] = 'Emanual/emanual_product/editproduct';
        $data['typeval'] = 2;
        $data['form_url_1'] = 'Emanual/emanual_product/thumbnail_upload';
        $data['form_url_3'] = 'Emanual/emanual_product/uploadvideo';
        if (isset($_POST['em_id'])) {
            $data['em_id'] = $_POST['em_id'];
            $_SESSION['em_id'] =  $data['em_id'];
        } else if (isset($_SESSION['em_id'])) {
            $data['em_id'] = $_SESSION['em_id'];
        } else {
            return redirect()->to(base_url() . '/Emanual/emanual_product');
        }
        $getProductData = $this->emanual_product_model->getProductDetails($data['em_id']);
        $data['row'] = $getProductData[0];
        echo view('templates/header_view', $data);
        echo view('emanual/products_edit_view', $data);
        echo view('templates/footer_view');
    }


    public function editproduct()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['em_id'])) {
            $em_id = $_POST['em_id'];
            $_SESSION['em_id'] =  $em_id;
        } else {
            return redirect()->to(base_url() . '/Emanual/emanual_product');
        }
        $newdata = [
            'product_name' =>  $this->request->getVar('product_name'),
            'description' =>  $this->request->getVar('description'),
            'status' =>  $this->request->getVar('status')
        ];
        $this->emanual_product_model->editproductdetails($newdata, $em_id);
        session()->setFlashdata('success', lang('Messages.Success_0008'));
        if ($this->request->getVar('status') == 0) {
            return redirect()->to(base_url() . 'Emanual/emanual_product');
        } else {
            return redirect()->to(base_url() . 'Emanual/emanual_product/product_edit_view');
        }
    }

    public function documents() //fetch data from users table to display
    {
        $data = [];
        helper(['form']);
        $data['header'] = 'e-Manual Product';
        $data['header_link'] = 'Emanual/emanual_product';
        $data['sub_header_1'] = 'Document View';
        $data['sub_header_1_link'] = 'Emanual/emanual_product/document_view';
        $data['create_new_course_link'] = 'Emanual/emanual_product/document_add_view';
        $data['settings_link'] = 'Emanual/emanual_product/page_view';
        $data['edit_link'] = 'Emanual/emanual_product/document_edit_view';
        $data['emanual_link'] = 'Emanual/emanual_link';
        $data['emanual_lang_link'] = 'Emanual/emanual_product/emanual_lang';
        $userlevel = session()->get('userlevel');
        if (isset($_POST['em_id'])) {
            $data['em_id'] = $_POST['em_id'];
            $_SESSION['em_id'] =  $data['em_id'];
        } else if (isset($_SESSION['em_id'])) {
            $data['em_id'] = $_SESSION['em_id'];
        } else {
            return redirect()->to(base_url() . '/Emanual/emanual_product');
        }

        $data['e_manual_name'] =  $this->emanual_product_model->getProductDetails($data['em_id']);
        $data['getAssigndocument'] = $this->emanual_product_model->getAssigndocument($data['em_id']);
        echo view('templates/header_view', $data);
        echo view('emanual/document_view', $data);
        echo view('templates/footer_view');
    }
    public function document_view() //fetch data from users table to display
    {
        $data = [];
        helper(['form']);
        $data['header'] = 'e-Manual Product';
        $data['header_link'] = 'Emanual/emanual_product';
        $data['sub_header_1'] = 'Document View';
        $data['sub_header_1_link'] = 'Emanual/emanual_product/document_view';
        $data['create_new_course_link'] = 'Emanual/emanual_product/document_add_view';
        $data['settings_link'] = 'Emanual/emanual_product/page_view';
        $data['edit_link'] = 'Emanual/emanual_product/document_edit_view';
        $data['emanual_link'] = 'Emanual/emanual_link';
        $data['emanual_lang_link'] = 'Emanual/emanual_product/emanual_lang';
        $userlevel = session()->get('userlevel');

        if (isset($_SESSION['em_id'])) {
            $data['em_id'] = $_SESSION['em_id'];
        } else {
            if (session()->get('id_user') == 1 || session()->get('id_user') == '1115') {
                $data['em_id'] = 7;
            } else {
                return redirect()->to(base_url() . 'my_training');
            }
        }
        $_SESSION['em_id'] =  $data['em_id'];
        $data['e_manual_name'] =  $this->emanual_product_model->getProductDetails($data['em_id']);
        $data['getAssigndocument'] = $this->emanual_product_model->getAssigndocument($data['em_id']);
        echo view('templates/header_view', $data);
        echo view('emanual/document_list_view', $data);
        echo view('templates/footer_view');
    }

    public function helpdocument_view() //fetch data from users table to display
    {
        $data = [];
        helper(['form']);
        if (session()->get('id_user') == 1 || session()->get('id_user') == '1184' || session()->get('id_user') == '834') {
            $data['em_id'] = 8;
            $_SESSION['em_id'] =  $data['em_id'];
            return redirect()->to(base_url() . 'Emanual/emanual_product/document_view');
        } else {
            return redirect()->to(base_url() . 'my_training');
        }
    }

    public function troubleshoot_view()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['emd_id'])) {
            $data['emd_id'] = $_POST['emd_id'];
            $_SESSION['emd_id'] =  $data['emd_id'];
        } else if (isset($_SESSION['emd_id'])) {
            $data['emd_id'] = $_SESSION['emd_id'];
        } else {
            return redirect()->to(base_url() . '/Emanual/emanual_product');
        }
        $data['troubleshoot_data']  =  $this->emanual_product_model->getAllTroubleshootValues($data['emd_id']);
        echo view('templates/header_view', $data);
        echo view('emanual/troubleshoot_add_view', $data);
        echo view('templates/footer_view');
    }
    public function troubleshoot_edit()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['et_id'])) {
            $data['et_id'] = $_POST['et_id'];
            $_SESSION['et_id'] =  $data['et_id'];
        } else if (isset($_SESSION['et_id'])) {
            $data['et_id'] = $_SESSION['et_id'];
        } else {
            return redirect()->to(base_url() . '/Emanual/emanual_product');
        }
        $data['troubleshoot_data']  =  $this->emanual_product_model->getTroubleshootEditDetails($data['et_id']);
        echo view('templates/header_view', $data);
        echo view('emanual/troubleshoot_edit_view', $data);
        echo view('templates/footer_view');
    }

    public function troubleshoot_link()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['et_id'])) {
            $data['et_id'] = $_POST['et_id'];
            $_SESSION['et_id'] =  $data['et_id'];
        } else if (isset($_SESSION['et_id'])) {
            $data['et_id'] = $_SESSION['et_id'];
        } else {
            return redirect()->to(base_url() . '/Emanual/emanual_product');
        }

        if (isset($_POST['emd_id'])) {
            $data['emd_id'] = $_POST['emd_id'];
            $_SESSION['emd_id'] =  $data['emd_id'];
        } else if (isset($_SESSION['emd_id'])) {
            $data['emd_id'] = $_SESSION['emd_id'];
        } else {
            return redirect()->to(base_url() . '/Emanual/emanual_product');
        }

        $data['trouble_links']  =  $this->emanual_product_model->getTroubleshootEditDetails($data['et_id']);
        $data['troubleshoot_data']  =  $this->emanual_product_model->getAllTroubleshootValues($data['emd_id']);

        $data['trouble_pages']  =  $this->emanual_product_model->dropdown_trouble_pages($data['et_id']);
        $data['getAssignpages'] = $this->emanual_product_model->getAssignpages(1);

        echo view('templates/header_view', $data);
        echo view('emanual/troubleshoot_link_view', $data);
        echo view('templates/footer_view');
    }
    public function del_trouble_link()
    {
        $newdata = [
            'status' => 0,
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $etl_id = $this->request->getVar('etl_id');
        $this->emanual_product_model->del_troble_link_value($newdata, $etl_id);
        session()->setFlashdata('success', lang('Messages.Error_0005'));
        return redirect()->to(base_url() . 'Emanual/emanual_product/troubleshoot_link');
    }
    public function troubleshoot_addLink()
    {
        $newdata = [
            'link_id' => $this->request->getVar('link_id'),
            'et_id' => $this->request->getVar('et_id'),
            'link_type' => $this->request->getVar('link_type'),
            'status' => 1,
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
        ];

        $this->emanual_product_model->addTroubleshootinglink($newdata);
        session()->setFlashdata('success', 'Troubleshooting link added successfully!');
        return redirect()->to(base_url() . 'Emanual/emanual_product/troubleshoot_link');
    }
    public function troubleshoot_update()
    {
        $et_id = $this->request->getVar('et_id');
        $newdata = [
            'question' => $this->request->getVar('question'),
            'description' => $this->request->getVar('description'),
            'status' => $this->request->getVar('status'),
            'last_udpated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
        ];

        $this->emanual_product_model->updateTroubleshooting($newdata, $et_id);
        session()->setFlashdata('success', 'Troubleshooting item updated successfully!');
        return redirect()->to(base_url() . 'Emanual/emanual_product/troubleshoot_view');
    }
    public function  troubleshoot_add()
    {

        $newdata = [
            'question' => $this->request->getVar('troubleshoot_name'),
            'document_id' => $this->request->getVar('emd_id'),
            'description' => $this->request->getVar('description'),
            'type' => $this->request->getVar('type'),
            'status' => '1',
            'last_udpated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
        ];

        $this->emanual_product_model->addNewTroubleshoot($newdata);
        session()->setFlashdata('success', 'Troubleshooting item added successfully!');
        return redirect()->to(base_url() . 'Emanual/emanual_product/troubleshoot_view');
    }

    public function document_add_view()
    {
        $userlevel = session()->get('userlevel');

        $data = [];
        helper(['form']);
        if (isset($_POST['em_id'])) {
            $data['em_id'] = $_POST['em_id'];
            $_SESSION['em_id'] =  $data['em_id'];
        } else if (isset($_SESSION['em_id'])) {
            $data['em_id'] = $_SESSION['em_id'];
        } else {
            return redirect()->to(base_url() . '/Emanual/emanual_product');
        }
        $data['header'] = 'Document View';
        $data['header_link'] = 'Emanual/emanual_product/document_view';
        $data['sub_header_1'] = 'Create New Document';
        $data['form_link'] = 'Emanual/emanual_product/adddocumnet';
        echo view('templates/header_view', $data);
        echo view('emanual/document_add_view', $data);
        echo view('templates/footer_view');
    }
    public function adddocumnet()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['em_id'])) {
            $data['em_id'] = $_POST['em_id'];
            $_SESSION['em_id'] =  $data['em_id'];
        } else if (isset($_SESSION['em_id'])) {
            $data['em_id'] = $_SESSION['em_id'];
        } else {
            return redirect()->to(base_url() . 'my_training');
        }

        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $password = array();
        $alpha_length = strlen($alphabet) - 1;
        for ($i = 0; $i < 20; $i++) {
            $n = rand(0, $alpha_length);
            $password[] = $alphabet[$n];
        }

        $temppass = implode($password);

        if ($this->request->getPost()) {
            $newdata = [
                'document_name' => $this->request->getVar('document_name'),
                'description' => $this->request->getVar('description'),
                'product_id' => $this->request->getVar('em_id'),
                'type' => $this->request->getVar('type'),
                'sequence' => $this->request->getVar('sequence'),
                'typeofLink' => $this->request->getVar('launch_link'),
                'hash' => $temppass,
                'status' => '1',
                'last_updated_by' =>  session()->get('id_user'),
                'last_updated_on' => time(),
            ];
             $this->emanual_product_model->addNewDocument($newdata);
            session()->setFlashdata('success', 'Document added successfully!');
            return redirect()->to(base_url() . 'Emanual/emanual_product/document_view');
        }
        return redirect()->to(base_url() . 'my_training');
    }
    public function document_edit_view()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['emd_id'])) {
            $data['emd_id'] = $_POST['emd_id'];
            $_SESSION['emd_id'] =  $data['emd_id'];
        } else if (isset($_SESSION['emd_id'])) {
            $data['emd_id'] = $_SESSION['emd_id'];
        } else {
            return redirect()->to(base_url() . '/emanual_product');
        }
        $data['header_main'] = 'e-Manual Product';
        $data['header_link_main'] = 'Emanual/emanual_product';
        $data['header'] = 'Document View';
        $data['header_link'] = 'Emanual/emanual_product/document_view';
        $data['sub_header_1'] = 'Edit Document';
        $data['form_link'] = 'Emanual/emanual_product/editdocument';
        $data['typeval'] = 2;
        $data['form_url_1'] = 'Emanual/emanual_product/document_thumbnail_upload';
        $getDocumentData = $this->emanual_product_model->getDocumentDetails($data['emd_id']);
        $data['row'] = $getDocumentData[0];
        echo view('templates/header_view', $data);
        echo view('emanual/document_edit_view', $data);
        echo view('templates/footer_view');
    }
    public function editdocument()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['emd_id'])) {
            $data['emd_id'] = $_POST['emd_id'];
            $_SESSION['emd_id'] =  $data['emd_id'];
        } else if (isset($_SESSION['emd_id'])) {
            $data['emd_id'] = $_SESSION['emd_id'];
        } else {
            return redirect()->to(base_url() . '/Emanual/emanual_product');
        }

        if ($this->request->getPost()) {
            $newdata = [
                'document_name' =>  $this->request->getVar('document_name'),
                'type' =>  $this->request->getVar('type'),
                'typeofLink' =>  $this->request->getVar('launch_link'),
                'sequence' =>  $this->request->getVar('sequence'),
                'status' =>  $this->request->getVar('status'),
                'last_updated_by' =>  session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $this->emanual_product_model->editDocumentDetails($newdata, $data['emd_id']);
            session()->setFlashdata('success', lang('Messages.Success_0008'));
            return redirect()->to(base_url() . 'Emanual/emanual_product/document_edit_view');
        }
    }
    public function page_view() //fetch data from users table to display
    {
        $data = [];
        helper(['form']);
        $data['header'] = 'e-Manual Product';
        $data['header_link'] = 'Emanual/emanual_product';
        $data['sub_header_1'] = 'Document View';
        $data['sub_header_1_link'] = 'Emanual/emanual_product/document_view';
        $data['sub_header_2'] = 'Page View';
        $data['sub_header_2_link'] = 'Emanual/emanual_product/page_view';

        $data['create_new_course_link'] = 'Emanual/emanual_product/page_add_view';
        $data['settings_link'] = 'Emanual/emanual_pagecontent';
        $data['edit_link'] = 'Emanual/emanual_product/page_edit_view';

        if (isset($_POST['emd_id'])) {
            $data['emd_id'] = $_POST['emd_id'];
            $_SESSION['emd_id'] =  $data['emd_id'];
        } else if (isset($_SESSION['emd_id'])) {
            $data['emd_id'] = $_SESSION['emd_id'];
        } else {
            return redirect()->to(base_url() . '/Emanual/emanual_product');
        }

        $data['getAssignpages'] = $this->emanual_product_model->getAssignpages($data['emd_id']);
        echo view('templates/header_view', $data);
        echo view('emanual/page_list_view', $data);
        echo view('templates/footer_view');
    }
    public function page_add_view()
    {
        $userlevel = session()->get('userlevel');

        $data = [];
        helper(['form']);
        if (isset($_POST['emd_id'])) {
            $data['emd_id'] = $_POST['emd_id'];
            $_SESSION['emd_id'] =  $data['emd_id'];
        } else if (isset($_SESSION['emd_id'])) {
            $data['emd_id'] = $_SESSION['emd_id'];
        } else {
            return redirect()->to(base_url() . '/Emanual/emanual_product');
        }
        $data['sub_header_2'] = 'Page View';
        $data['sub_header_2_link'] = 'Emanual/emanual_product/page_view';
        $data['sub_header_3'] = 'Create New Page';
        $data['form_link'] = 'Emanual/emanual_product/addpage';
        echo view('templates/header_view', $data);
        echo view('emanual/page_add_view', $data);
        echo view('templates/footer_view');
    }
    function addpage()
    {
        $data = [];
        helper(['form']);
        $data['sub_header_2'] = 'Page View';
        $data['sub_header_2_link'] = 'Emanual/emanual_product/page_view';
        $data['sub_header_3'] = 'Create New Page';
        $data['form_link'] = 'Emanual/emanual_product/addpage';

        if (isset($_POST['emd_id'])) {
            $data['emd_id'] = $_POST['emd_id'];
            $_SESSION['emd_id'] =  $data['emd_id'];
        } else if (isset($_SESSION['emd_id'])) {
            $data['emd_id'] = $_SESSION['emd_id'];
        } else {
            return redirect()->to(base_url() . '/Emanual/emanual_product');
        }
        if ($this->request->getPost()) {
            $newdata = [
                'document_id' => $this->request->getVar('emd_id'),
                'page_number' => $this->request->getVar('page_number'),
                'page_name' => $this->request->getVar('page_name'),
                'status' => '1',
                'last_updated_by' =>  session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $this->emanual_product_model->addpagedata($newdata);
            session()->setFlashdata('success', 'New Page Added');
            return redirect()->to(base_url() . 'Emanual/emanual_product/page_view');
        }
    }
    public function page_edit_view()
    {
        $data = [];
        helper(['form']);
        $data['header'] = 'Document View';
        $data['header_link'] = 'Emanual/emanual_product/document_view';
        $data['sub_header_1'] = 'Edit Page';
        $data['form_link'] = 'Emanual/emanual_product/editpage';
        $data['typeval'] = 2;
        $data['form_url_1'] = 'Emanual/emanual_product/document_thumbnail_upload';
        $data['sub_header_2'] = 'Page View';
        $data['sub_header_2_link'] = 'Emanual/emanual_product/page_view';

        if (isset($_POST['empg_id'])) {
            $data['empg_id'] = $_POST['empg_id'];
            $_SESSION['empg_id'] =  $data['empg_id'];
        } else if (isset($_SESSION['empg_id'])) {
            $data['empg_id'] = $_SESSION['empg_id'];
        } else {
            return redirect()->to(base_url() . 'Emanual/emanual_product');
        }
        $getDocumentData = $this->emanual_product_model->getpageDetails($data['empg_id']);
        $data['row'] = $getDocumentData[0];
        echo view('templates/header_view', $data);
        echo view('emanual/page_edit_view', $data);
        echo view('templates/footer_view');
    }
    public function editpage()
    {
        $data = [];
        helper(['form']);

        if (isset($_POST['empg_id'])) {
            $data['empg_id'] = $_POST['empg_id'];
            $_SESSION['empg_id'] =  $data['empg_id'];
        } else if (isset($_SESSION['empg_id'])) {
            $data['empg_id'] = $_SESSION['empg_id'];
        } else {
            return redirect()->to(base_url() . '/Emanual/emanual_product');
        }

        $newdata = [
            'page_name' =>  $this->request->getVar('page_name'),
            'page_number' =>  $this->request->getVar('page_number'),
            'status' =>  $this->request->getVar('status'),
        ];
        $this->emanual_product_model->editpagedetails($newdata, $data['empg_id']);
        session()->setFlashdata('success', 'Updated Successfully');
        return redirect()->to(base_url() . 'Emanual/emanual_product/page_view');
    }
    public function thumbnail_upload()
    {
        $data = [];
        helper(['filesystem']);
        if (isset($_POST['em_id'])) {
            $data['em_id'] = $_POST['em_id'];
            $_SESSION['em_id'] =  $data['em_id'];
        } else if (isset($_SESSION['em_id'])) {
            $data['em_id'] = $_SESSION['em_id'];
        }

        if ($this->request->getPost()) {
            $rules = [
                'file' => 'uploaded[file]|max_size[file,550]|ext_in[file,jpg]'
            ];

            if (!$this->validate($rules)) {

                $data['thumbnailvalidation'] = $this->validator;
            } else {
                if ($file = $this->request->getFile('file')) {

                    if ($file->isValid() && !$file->hasMoved()) {
                        $filename = $file->getName();
                        if (!is_dir(FCPATH . 'assets/assets/uploads/emanual_thumbnail/' . $data['em_id'])) {
                            mkdir('assets/assets/uploads/emanual_thumbnail/' . $data['em_id'], 0777, true);
                        }
                        if (file_exists(FCPATH . 'assets/assets/uploads/emanual_thumbnail/' . $data['em_id'] . "/" . $filename)) {
                            session()->setFlashdata('error', $filename . ' ' . lang('Messages.Success_0051'));
                        } else {
                            if ($file->move(FCPATH . 'assets/assets/uploads/emanual_thumbnail/' . $data['em_id'], $filename)) {
                                $filepath = FCPATH . 'assets/assets/uploads/emanual_thumbnail/' . $data['em_id'] . '/' . $filename;
                                $newdata = [
                                    'em_id' => $data['em_id'],
                                    'thumbnail' => $filename,
                                    'last_updated_by' =>  session()->get('id_user'),
                                    'last_updated_on' => time(),
                                ];
                                $result = $this->emanual_product_model->editproductdetails($newdata, $data['em_id']);
                                if ($result) {
                                    session()->setFlashdata('success', lang('Messages.Success_0009'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                } else {
                                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                }
                            }
                        }
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0001'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                    }
                }
            }
        }
        return redirect()->to(base_url() . '/Emanual/emanual_product/product_edit_view');
    }
    public function uploadvideo()
    {
        $data = [];
        helper(['filesystem']);
        if (isset($_POST['em_id'])) {
            $data['em_id'] = $_POST['em_id'];
            $_SESSION['em_id'] =  $data['em_id'];
        }
        if ($this->request->getPost()) {
            $rules = [
                'file' => 'uploaded[file]|max_size[file,20480]|ext_in[file,mp4]'
            ];
            if (!$this->validate($rules)) {
                $data['promovalidation'] = $this->validator;
            } else {
                if ($file = $this->request->getFile('file')) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $filename = $file->getName();
                        if (!is_dir(FCPATH . 'assets/assets/uploads/emanual_video/' . $data['em_id'])) {
                            mkdir('assets/assets/uploads/emanual_video/' . $data['em_id'], 0777, true);
                        }
                        if (file_exists(FCPATH . 'assets/assets/uploads/emanual_video/' . $data['em_id'] . "/" . $filename)) {
                            session()->setFlashdata('error', $filename . ' ' . lang('Messages.Success_0051'));
                            return redirect()->to(base_url() . '/Emanual/emanual_product/product_edit_view');
                        } else {

                            if ($file->move(FCPATH . 'assets/assets/uploads/emanual_video/' . $data['em_id'], $filename)) {
                                $filepath = FCPATH . 'assets/assets/uploads/emanual_video/' . $data['em_id'] . '/' . $filename;
                                $newdata = [
                                    'em_id' => $data['em_id'],
                                    'video_upload' => $filename,
                                    'last_updated_by' =>  session()->get('id_user'),
                                    'last_updated_on' => time(),
                                ];
                                $result = $this->emanual_product_model->editproductdetails($newdata, $data['em_id']);
                                if ($result) {
                                    session()->setFlashdata('success', lang('Messages.Success_0009'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                } else {
                                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                }
                            }
                        }
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0001'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                    }
                }
            }
        }
        return redirect()->to(base_url() . '/Emanual/emanual_product/product_edit_view');
    }
    public function document_thumbnail_upload()
    {
        $data = [];
        helper(['filesystem']);
        if (isset($_POST['emd_id'])) {
            $data['emd_id'] = $_POST['emd_id'];
            $_SESSION['emd_id'] =  $data['emd_id'];
        } else if (isset($_GET['emd_id'])) {
            $data['emd_id'] = $_GET['emd_id'];
        } else if (isset($_SESSION['emd_id'])) {
            $data['emd_id'] = $_SESSION['emd_id'];
        }

        if ($this->request->getPost()) {
            $rules = [
                'file' => 'uploaded[file]|max_size[file,500]|ext_in[file,jpg]'
            ];
            if (!$this->validate($rules)) {
                $data['thumbnailvalidation'] = $this->validator;
            } else {
                if ($file = $this->request->getFile('file')) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $filename = $file->getName();
                        if (!is_dir(FCPATH . 'assets/assets/uploads/emanual_document_thumbnail/' . $data['emd_id'])) {
                            mkdir('assets/assets/uploads/emanual_document_thumbnail/' . $data['emd_id'], 0777, true);
                        }
                        if (file_exists(FCPATH . 'assets/assets/uploads/emanual_document_thumbnail/' . $data['emd_id'] . "/" . $filename)) {
                            session()->setFlashdata('error', $filename . ' ' . lang('Messages.Success_0051'));
                        } else {
                            if ($file->move(FCPATH . 'assets/assets/uploads/emanual_document_thumbnail/' . $data['emd_id'], $filename)) {
                                $filepath = FCPATH . 'assets/assets/uploads/emanual_document_thumbnail/' . $data['emd_id'] . '/' . $filename;
                                $newdata = [
                                    'emd_id' => $data['emd_id'],
                                    'thumbnail' => $filename,
                                    'last_updated_by' =>  session()->get('id_user'),
                                    'last_updated_on' => time(),
                                ];
                                $result = $this->emanual_product_model->editdocumentdetails($newdata, $data['emd_id']);
                                if ($result) {
                                    session()->setFlashdata('success', lang('Messages.Success_0009'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                } else {
                                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                }
                            }
                        }
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0001'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                    }
                }
            }
        }
        return redirect()->to(base_url() . '/Emanual/emanual_product/document_edit_view');
    }
    public function deletedocumentdetails()
    {
        $emd_id = $_POST['emd_id'];
        $newdata = [


            'status' => '0',
        ];
        $result = $this->emanual_product_model->deletedocument($newdata, $emd_id);
        if ($result) {
            session()->setFlashdata('success', 'Deleted Successfully');
            return redirect()->to(base_url() . '/Emanual/emanual_product/product_edit_view');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . '/Emanual/emanual_product/product_edit_view');
        }
    }
    public function emanual_lang($document_id) //fetch data from users table to display
    {
        $data = [];
        helper(['form']);
        $data['header_main'] = 'e-Manual Product';
        $data['header_link_main'] = 'Emanual/emanual_product';
        $data['header'] = 'Document View';
        $data['header_link'] = 'Emanual/emanual_product/document_view';
        $data['sub_header_1'] = 'Langauge Translate';
        $data['form_link'] = 'Emanual/emanual_product/addlanguage';
        $data['typeval'] = 2;
        $data['form_url_1'] = 'Emanual/emanual_product/emanual_page_translate';
        $data['form_url_2'] = 'Emanual/emanual_product/emanual_lang_delete';
        $userlevel = session()->get('userlevel');
        if (isset($_POST['document_id'])) {
            $data['document_id'] = $_POST['document_id'];
            $_SESSION['document_id'] =  $data['document_id'];
        } else if (isset($_GET['document_id'])) {
            $data['document_id'] = $_GET['document_id'];
        } else if (isset($_SESSION['document_id'])) {
            $data['document_id'] = $_SESSION['document_id'];
        } else if (isset($document_id)) {
            $data['document_id'] = $document_id;
        } else {
            return redirect()->to(base_url() . '/Emanual/emanual_product');
        }

        $data['languageData'] = $this->dropdown_model->getCountrylist(19);
        $data['getLanguagedata'] = $this->emanual_product_model->getLanguagedata($data['document_id']);
        echo view('templates/header_view', $data);
        echo view('emanual/emanual_lang_view', $data);
        echo view('templates/footer_view');
    }
    public function addlanguage()
    {
        $data = [];
        helper(['form']);
        $newdata = [
            'lang_id' => $_POST['language'],
            'document_id' => $_POST['document_id'],
            'status' => 1,
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->emanual_product_model->addlanguagedata($newdata);
        echo json_encode($result);
    }
    public function emanual_lang_delete()
    {
        $data = [];
        helper(['form']);
        $data['el_id'] =  $_POST['el_id'];
        // print_r($data['el_id']);
        // exit();
        $newdata = [
            'status' => 0,
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->emanual_product_model->deletelanguagedata($newdata, $data['el_id']);
        if ($result) {
            echo json_encode($result);
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url('Emanual/emanual_pagecontent'));
        }
    }
    public function emanual_page_translate()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['document_id'])) {
            $data['document_id'] = $_POST['document_id'];
            $_SESSION['document_id'] =  $data['document_id'];
            $data['lang_id'] = $_POST['lang_id'];
            $_SESSION['lang_id'] =  $data['lang_id'];
        } else if (isset($_GET['document_id'])) {
            $data['document_id'] = $_GET['document_id'];
        } else if (isset($_SESSION['document_id'])) {
            $data['document_id'] = $_SESSION['document_id'];
            $data['lang_id'] = $_SESSION['lang_id'];
        } else {
            return redirect()->to(base_url() . '/Emanual/emanual_product');
        }
        $langdata = $this->emanual_product_model->getLangname($data['lang_id']);
        $data['header'] = 'e-Manual Product';
        $data['header_link'] = 'Emanual/emanual_product';
        $data['sub_header_1'] = 'Document View';
        $data['sub_header_1_link'] = 'Emanual/emanual_product/document_view';
        $data['sub_header_2'] = 'Language Translate';
        $data['sub_header_2_link'] = 'Emanual/emanual_product/emanual_lang';
        $data['sub_header_3'] = 'Page Translated View - ' . $langdata[0]['lang_name'];
        $data['sub_header_3_link'] = 'Emanual/emanual_product/emanual_page_translate';
        $data['create_new_course_link'] = 'Emanual/emanual_product/page_add_view';
        $data['settings_link'] = 'Emanual/emanual_pagecontent/pagecontent_translate';
        $data['edit_link'] = 'Emanual/emanual_product/pageTranslate_edit_view';
        $data['getAssigntranslationpages'] = $this->emanual_product_model->getAssigntranslationpages($data['document_id']);
        echo view('templates/header_view', $data);
        echo view('emanual/emanual_lang_pagetranslate_view', $data);
        echo view('templates/footer_view');
    }
    public function pageTranslate_edit_view()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['empg_id'])) {
            $data['empg_id'] = $_POST['empg_id'];
            $_SESSION['empg_id'] =  $data['empg_id'];
            $data['lang_id'] = $_POST['lang_id'];
            $_SESSION['lang_id'] =  $data['lang_id'];
            $data['emd_id'] = $_POST['emd_id'];
            $_SESSION['emd_id'] =  $data['emd_id'];
        } else if (isset($_GET['empg_id'])) {
            $data['empg_id'] = $_GET['empg_id'];
        } else if (isset($_SESSION['empg_id'])) {
            $data['empg_id'] = $_SESSION['empg_id'];
            $data['lang_id'] = $_SESSION['lang_id'];
            $data['emd_id'] = $_SESSION['emd_id'];
        } else {
            return redirect()->to(base_url() . '/Emanual/emanual_product');
        }
        $langdata = $this->emanual_product_model->getLangname($data['lang_id']);

        $data['header'] = 'e-Manual Product';
        $data['header_link'] = 'Emanual/emanual_product';
        $data['sub_header_1'] = 'Document View';
        $data['sub_header_1_link'] = 'Emanual/emanual_product/document_view';
        $data['sub_header_2'] = 'Language Translate';
        $data['sub_header_2_link'] = 'Emanual/emanual_product/emanual_lang';
        $data['sub_header_3'] = 'Page Translated View - ' . $langdata[0]['lang_name'];
        $data['sub_header_3_link'] = 'Emanual/emanual_product/emanual_page_translate';
        $data['sub_header_4'] = 'Translate - ' . $langdata[0]['lang_name'];
        $data['create_new_course_link'] = 'Emanual/emanual_product/page_add_view';
        $data['settings_link'] = 'Emanual/emanual_pagecontent/pagecontent_translate';
        $data['edit_link'] = 'Emanual/emanual_product/pageTranslate_edit_view';
        $data['form_link'] = 'Emanual/emanual_product/edittranslatepage';
        $data['sub_header_4'] = 'Translate ';
        $getDocumentData = $this->emanual_product_model->getpageDetails($data['empg_id']);
        $data['row'] = $getDocumentData[0];
        echo view('templates/header_view', $data);
        echo view('emanual/page_tanslate_edit_view', $data);
        echo view('templates/footer_view');
    }
    function edittranslatepage()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['page_id'])) {
            $data['page_id'] = $_POST['page_id'];
            $_SESSION['page_id'] =  $data['page_id'];
            $data['lang_id'] = $_POST['lang_id'];
            $_SESSION['lang_id'] =  $data['lang_id'];
            $data['document_id'] = $_POST['document_id'];
            $_SESSION['document_id'] =  $data['document_id'];
        } else if (isset($_GET['page_id'])) {
            $data['page_id'] = $_GET['page_id'];
        } else if (isset($_SESSION['page_id'])) {
            $data['page_id'] = $_SESSION['page_id'];
            $data['lang_id'] = $_SESSION['lang_id'];
            $data['document_id'] = $_SESSION['document_id'];
        } else {
            return redirect()->to(base_url() . '/Emanual/emanual_product');
        }
        $data['header'] = 'e-Manual Document';
        $data['header_link'] = 'Emanual/emanual_product/document_view';
        $data['sub_header_1'] = 'Edit Page';
        $data['form_link'] = 'Emanual/emanual_product/editpage';
        $data['typeval'] = 2;
        $data['form_url_1'] = 'Emanual/emanual_product/document_thumbnail_upload';
        $data['sub_header_2'] = 'Page View';
        $data['sub_header_2_link'] = 'Emanual/emanual_product/page_view';

        if ($this->request->getPost()) {
            $rules = [
                'page_name' => 'required',
            ];
            if (!$this->validate($rules)) {
                $data['productditvalidation'] = $this->validator;
            } else {
                $lang_id = $this->request->getVar('lang_id');
                $newdata = [
                    'translate_page_name' =>  $this->request->getVar('page_name'),
                    'document_id' =>  $this->request->getVar('document_id'),
                    'page_id' =>  $this->request->getVar('page_id'),
                    'lang_id' =>  $this->request->getVar('lang_id'),
                    'status' => '1',
                    'last_updated_by' =>  session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                $result = $this->emanual_product_model->addtranslatepagename($newdata, $data['page_id'], $lang_id);
                if ($result) {
                    session()->setFlashdata('success', 'Updated Successfully');
                    return redirect()->to(base_url() . '/Emanual/emanual_product/emanual_page_translate');
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url() . '/Emanual/emanual_product/emanual_page_translate');
                }
            }
            echo view('templates/header_view', $data);
            echo view('emanual/page_edit_view', $data);
            echo view('templates/footer_view');
        }
    }
}
