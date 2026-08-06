<?php

namespace App\Controllers\Emanual;

use App\Controllers\BaseController;

use App\Models\Emanual\Emanual_product_model;
use App\Models\Settings\Dropdown_model;
use CodeIgniter\I18n\Time;

#[\AllowDynamicProperties]
class Emanual_pagecontent extends BaseController
{
    public function __construct()
    {
        $this->emanual_product_model = new Emanual_product_model();
        $this->dropdown_model = new Dropdown_model();
    }
    public function index() //fetch data from users table to display
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['empg_id']) && (isset($_POST['emd_id']))) {
            $data['empg_id'] = $_POST['empg_id'];
            $data['emd_id'] = $_POST['emd_id'];
            $_SESSION['empg_id'] =  $data['empg_id'];
            $_SESSION['emd_id'] =  $data['emd_id'];
        } else if (isset($_SESSION['empg_id']) && isset($_SESSION['emd_id'])) {
            $data['empg_id'] = $_SESSION['empg_id'];
            $data['emd_id'] = $_SESSION['emd_id'];
        } else {
            return redirect()->to(base_url() . '/Emanual/emanual_product/page_view');
        }

        $data['getAllpagedetails'] =  $this->emanual_product_model->getAllpagedetails();
        $documentdata =  $this->emanual_product_model->getPagecount($data['emd_id']);
        $data['totalPages'] = $documentdata[0]['pagecount'];
        $pagedetails =  $this->emanual_product_model->getpageDetails($data['empg_id']);
        $data['page_name'] =  $pagedetails[0]['page_name'];
        $data['page_number'] =  $pagedetails[0]['page_number'];
        $data['header'] = 'e-Manual Product';
        $data['header_link'] = 'Emanual/emanual_product';
        $data['sub_header_1'] = 'Document View';
        $data['sub_header_1_link'] = 'Emanual/emanual_product/document_view';
        $data['sub_header_2'] = 'Page View';
        $data['sub_header_2_link'] = 'Emanual/emanual_product/page_view';
        $data['sub_header_3'] = 'Add Content - ' . $pagedetails[0]['page_name'];
        $data['sub_header_3_link'] = 'Emanual/emanual_pagecontent';
        $data['contenttype'] =  $this->dropdown_model->getCountrylist(18);
        // $data['productDetails'] = $this->emanual_product_model->getAllProductDetails();
        $data['pagecontentdata'] = $this->emanual_product_model->getPagecontentdata($data['empg_id']);
        echo view('templates/header_view', $data);
        echo view('emanual/content_list_view', $data);
        echo view('templates/footer_view');
    }
    function addContent()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['empg_id']) && isset($_POST['page_name'])) {
            $data['empg_id'] = $_POST['empg_id'];
            $data['page_name'] = $_POST['page_name'];
            $_SESSION['empg_id'] =  $data['empg_id'];
            $_SESSION['page_name'] =  $data['page_name'];
        } else if (isset($_GET['empg_id']) && isset($_POST['page_name'])) {
            $data['empg_id'] = $_GET['empg_id'];
            $data['page_name'] = $_GET['page_name'];
        } else if (isset($_SESSION['empg_id']) && isset($_POST['page_name'])) {
            $data['empg_id'] = $_SESSION['empg_id'];
            $data['page_name'] = $_SESSION['page_name'];
        } else {
            return redirect()->to(base_url() . '/Emanual/emanual_product/page_view');
        }
        $data['header'] = 'e-Manual Product';
        $data['header_link'] = 'Emanual/emanual_product';
        $data['sub_header_1'] = 'Document View';
        $data['sub_header_1_link'] = 'Emanual/emanual_product/document_view';
        $data['sub_header_2'] = 'Page View';
        $data['sub_header_2_link'] = 'Emanual/emanual_product/page_view';
        $data['sub_header_3'] = 'Add Content - ' . $data['page_name'];
        $data['sub_header_3_link'] = 'Emanual/emanual_pagecontent';

        if ($this->request->getPost()) {
            $rules = [
                'content1' => 'required',
            ];
            if (!$this->validate($rules)) {
                $data['coursevalidation'] = $this->validator;
            } else {
                $newdata = [
                    'content1' => $this->request->getVar('content1'),
                    'page_id' => $data['empg_id'],
                    'type' => $this->request->getVar('type'),
                    // 'sequence' =>  $this->request->getVar('sequence'),
                    'status' => '1',
                    'last_updated_by' =>  session()->get('id_user'),
                    'last_updated_on' => time(),

                ];
                $result = $this->emanual_product_model->addpagecontent($newdata, $data['empg_id']);
                if ($result) {
                    return json_encode($result);
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url('Emanual/emanual_pagecontent'));
                }
            }
        }
    }

    public function imageUpload()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['empg_id']) && isset($_POST['page_name'])) {
            $data['empg_id'] = $_POST['empg_id'];
            $data['page_name'] = $_POST['page_name'];
            $_SESSION['empg_id'] =  $data['empg_id'];
            $_SESSION['page_name'] =  $data['page_name'];
        } else if (isset($_GET['empg_id']) && isset($_POST['page_name'])) {
            $data['empg_id'] = $_GET['empg_id'];
            $data['page_name'] = $_GET['page_name'];
        } else if (isset($_SESSION['empg_id']) && isset($_POST['page_name'])) {
            $data['empg_id'] = $_SESSION['empg_id'];
            $data['page_name'] = $_SESSION['page_name'];
        } else {
            return redirect()->to(base_url() . '/Emanual/emanual_product/page_view');
        }
        $data['header'] = 'e-Manual Product';
        $data['header_link'] = 'Emanual/emanual_product';
        $data['sub_header_1'] = 'Document View';
        $data['sub_header_1_link'] = 'Emanual/emanual_product/document_view';
        $data['sub_header_2'] = 'Page View';
        $data['sub_header_2_link'] = 'Emanual/emanual_product/page_view';
        $data['sub_header_3'] = 'Add Content - ' . $data['page_name'];
        $data['sub_header_3_link'] = 'Emanual/emanual_pagecontent';

        if ($this->request->getPost()) {
            $rules = [
                'file' => 'uploaded[file]|ext_in[file,jpg,png]'
            ];
            if (!$this->validate($rules)) {
                $data['thumbnailvalidation'] = $this->validator;
            } else {
                if ($file = $this->request->getFile('file')) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $filename = $file->getName();
                        if (!is_dir(FCPATH . 'assets/assets/uploads/emanual_image/' . $data['empg_id'])) {
                            mkdir('assets/assets/uploads/emanual_image/' . $data['empg_id'], 0777, true);
                        }

                        if ($file->move(FCPATH . 'assets/assets/uploads/emanual_image/' . $data['empg_id'], $filename)) {
                            $filepath = FCPATH . 'assets/assets/uploads/emanual_image/' . $data['empg_id'] . '/' . $filename;
                            $newdata = [
                                'page_id' => $data['empg_id'],
                                'content1' => $filename,
                                'type' => $this->request->getVar('type'),
                                // 'sequence' =>  $this->request->getVar('sequence'),
                                'status' => '1',
                                'last_updated_by' =>  session()->get('id_user'),
                                'last_updated_on' => time(),
                            ];
                            $result = $this->emanual_product_model->addpagecontent($newdata, $data['empg_id']);
                            if ($result) {
                                return json_encode($result);
                            } else {
                                return json_encode($result);
                            }
                        }
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0001'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                    }
                }
            }
        }
        return redirect()->to(base_url() . '/Emanual/emanual_pagecontent');
    }
    function sortPageContent()
    {
        $sequence = $_POST['sequence'];
        // var_dump($_POST);
        $result = $this->emanual_product_model->addsequence($sequence);
        return json_encode($result);
    }
    function deleteContent()
    {
        $emc_id = $_POST['emc_id'];
        $sequence = $_POST['sequence'];
        // echo var_dump($emc_id);
        // exit();
        $newdata = [


            'status' => '0',
        ];
        $result = $this->emanual_product_model->deleteContent($newdata, $emc_id);
        return json_encode($result);
    }
    function editContent()
    {
        $emc_id = $_POST['emc_id'];
        $status = isset($_POST['status']) ? $_POST['status'] : '1';
        $page_id = $_POST['page_id'];

        // echo var_dump($emc_id);
        // exit();
        if ($status == 5) {
            $newdata = [
                'content1' => $this->request->getVar('content1'),
                'page_id' => $page_id,
                'reference_id' => $emc_id,
                'type' => $this->request->getVar('type'),
                'status' => 1,
                'sequence' => $_POST['sequence'],
                'last_updated_by' =>  session()->get('id_user'),
                'last_updated_on' => time(),

            ];
            $result = $this->emanual_product_model->copyContent($newdata, $emc_id);
        } else {
            $newdata = [
                'content1' => $this->request->getVar('content1'),
                'status' =>  $status,
                'last_update' => time(),
                'last_updated_by' =>  session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $result = $this->emanual_product_model->deleteContent($newdata, $emc_id);
        }



        if (isset($_SESSION['emd_id'])) {
            $emd_id = $_SESSION['emd_id'];
            $updatestat = [
                'last_updated_on' => time()
            ];
            $this->emanual_product_model->updatePageContentStatus($updatestat, $emd_id);
        }

        return json_encode($result);
    }
    public function readyforReview()
    {
        $emc_id = $_POST['emc_id'];
        $newdata = [
            'status' => 2,
            'last_update' => time(),
        ];
        $result = $this->emanual_product_model->deleteContent($newdata, $emc_id);
        return json_encode($result);
    }
    public function approveContent()
    {
        $emc_id = $_POST['emc_id'];
        $reference_id = $_POST['reference_id'];
        $newdata = [
            'status' => 5,
            'reference_id' => $reference_id,
            'last_update' => time(),
            'approvedby' => session()->get('id_user'),
            'approvedon' => time(),
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->emanual_product_model->approvContent($newdata, $emc_id);
        return json_encode($result);
    }
    public function rejectContent()
    {
        $emc_id = $_POST['emc_id'];
        $newdata = [
            'status' => 3,
            'last_update' => time(),
            'approvedby' => session()->get('id_user'),
            'approvedon' => time(),
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->emanual_product_model->deleteContent($newdata, $emc_id);
        return json_encode($result);
    }
    function editpageUpload()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['empg_id']) && isset($_POST['page_name'])) {
            $data['empg_id'] = $_POST['empg_id'];
            $data['page_name'] = $_POST['page_name'];
            $_SESSION['empg_id'] =  $data['empg_id'];
            $_SESSION['page_name'] =  $data['page_name'];
        } else if (isset($_GET['empg_id']) && isset($_POST['page_name'])) {
            $data['empg_id'] = $_GET['empg_id'];
            $data['page_name'] = $_GET['page_name'];
        } else if (isset($_SESSION['empg_id']) && isset($_POST['page_name'])) {
            $data['empg_id'] = $_SESSION['empg_id'];
            $data['page_name'] = $_SESSION['page_name'];
        } else {
            return redirect()->to(base_url() . '/Emanual/emanual_product/page_view');
        }
        $status = isset($_POST['status']) ? $_POST['status'] : '1';
        if ($this->request->getPost()) {
            $rules = [
                'file' => 'uploaded[file]|ext_in[file,jpg,png]'
            ];
            if (!$this->validate($rules)) {
                $data['thumbnailvalidation'] = $this->validator;
            } else {
                if ($file = $this->request->getFile('file')) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $filename = $file->getName();
                        if (!is_dir(FCPATH . 'assets/uploads/emanual_image/' . $data['empg_id'])) {
                            mkdir('assets/uploads/emanual_image/' . $data['empg_id'], 0777, true);
                        }
                        if (file_exists(FCPATH . 'assets/uploads/emanual_image/' . $data['empg_id'] . "/" . $filename)) {
                            //session()->setFlashdata('error', $filename . ' '.lang('Messages.Success_0051'));
                            // session()->setFlashdata('error', $filename . ' '.lang('Messages.Success_0051'));
                            $result['status'] = 'error';
                            return json_encode($result);
                            //return redirect()->to(base_url() . '/emanual_pagecontent');

                        } else {
                            if ($file->move(FCPATH . 'assets/uploads/emanual_image/' . $data['empg_id'], $filename)) {
                                $filepath = FCPATH . 'assets/uploads/emanual_image/' . $data['empg_id'] . '/' . $filename;
                                if ($status == 5) {
                                    $newdata = [
                                        'content1' => $filename,
                                        'page_id' =>  $data['empg_id'],
                                        'reference_id' =>  $_POST['emc_id'],
                                        'type' => $this->request->getVar('type'),
                                        'status' => 1,
                                        'sequence' => $_POST['sequence'],
                                        'last_updated_by' =>  session()->get('id_user'),
                                        'last_updated_on' => time(),

                                    ];
                                    $result = $this->emanual_product_model->copyContent($newdata, $_POST['emc_id']);
                                } else {
                                    $newdata = [
                                        'content1' => $filename,
                                        'status' =>  1,
                                        'last_update' => time(),
                                    ];
                                    $result = $this->emanual_product_model->deleteContent($newdata, $_POST['emc_id']);
                                }

                                if ($result) {
                                    return json_encode($result);
                                } else {
                                    return json_encode($result);
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
        return redirect()->to(base_url() . '/Emanual/emanual_pagecontent');
    }
    function videoUpload()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['empg_id']) && isset($_POST['page_name'])) {
            $data['empg_id'] = $_POST['empg_id'];
            $data['page_name'] = $_POST['page_name'];
            $_SESSION['empg_id'] =  $data['empg_id'];
            $_SESSION['page_name'] =  $data['page_name'];
        } else if (isset($_GET['empg_id']) && isset($_POST['page_name'])) {
            $data['empg_id'] = $_GET['empg_id'];
            $data['page_name'] = $_GET['page_name'];
        } else if (isset($_SESSION['empg_id']) && isset($_POST['page_name'])) {
            $data['empg_id'] = $_SESSION['empg_id'];
            $data['page_name'] = $_SESSION['page_name'];
        } else {
            return redirect()->to(base_url() . '/Emanual/emanual_product/page_view');
        }
        $data['header'] = 'e-Manual Product';
        $data['header_link'] = 'Emanual/emanual_product';
        $data['sub_header_1'] = 'Document View';
        $data['sub_header_1_link'] = 'Emanual/emanual_product/document_view';
        $data['sub_header_2'] = 'Page View';
        $data['sub_header_2_link'] = 'Emanual/emanual_product/page_view';
        $data['sub_header_3'] = 'Add Content - ' . $data['page_name'];
        $data['sub_header_3_link'] = 'Emanual/emanual_pagecontent';

        if ($this->request->getPost()) {
            $rules = [
                'file' => 'uploaded[file]|ext_in[file,mp4]'
            ];
            if (!$this->validate($rules)) {
                $data['thumbnailvalidation'] = $this->validator;
            } else {
                if ($file = $this->request->getFile('file')) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $filename = $file->getName();
                        if (!is_dir(FCPATH . 'assets/assets/uploads/emanual_video/' . $data['empg_id'])) {
                            mkdir('assets/assets/uploads/emanual_video/' . $data['empg_id'], 0777, true);
                        }
                        if (file_exists(FCPATH . 'assets/assets/uploads/emanual_video/' . $data['empg_id'] . "/" . $filename)) {
                            //session()->setFlashdata('error', $filename . ' '.lang('Messages.Success_0051'));
                            // session()->setFlashdata('error', $filename . ' '.lang('Messages.Success_0051'));
                            $result['status'] = 'error';
                            return json_encode($result);
                            //return redirect()->to(base_url() . '/emanual_pagecontent');

                        } else {
                            if ($file->move(FCPATH . 'assets/assets/uploads/emanual_video/' . $data['empg_id'], $filename)) {
                                $filepath = FCPATH . 'assets/assets/uploads/emanual_video/' . $data['empg_id'] . '/' . $filename;
                                $newdata = [
                                    'page_id' => $data['empg_id'],
                                    'content1' => $filename,
                                    'type' => $this->request->getVar('type'),
                                    // 'sequence' =>  $this->request->getVar('sequence'),
                                    'status' => '1',
                                    'last_updated_by' =>  session()->get('id_user'),
                                    'last_updated_on' => time(),
                                ];
                                $result = $this->emanual_product_model->addpagecontent($newdata, $data['empg_id']);
                                if ($result) {
                                    return json_encode($result);
                                } else {
                                    return json_encode($result);
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
        return redirect()->to(base_url() . '/emanual_pagecontent');
    }
    function editvideoUpload()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['empg_id']) && isset($_POST['page_name'])) {
            $data['empg_id'] = $_POST['empg_id'];
            $data['page_name'] = $_POST['page_name'];
            $_SESSION['empg_id'] =  $data['empg_id'];
            $_SESSION['page_name'] =  $data['page_name'];
        } else if (isset($_GET['empg_id']) && isset($_POST['page_name'])) {
            $data['empg_id'] = $_GET['empg_id'];
            $data['page_name'] = $_GET['page_name'];
        } else if (isset($_SESSION['empg_id']) && isset($_POST['page_name'])) {
            $data['empg_id'] = $_SESSION['empg_id'];
            $data['page_name'] = $_SESSION['page_name'];
        } else {
            return redirect()->to(base_url() . '/Emanual/emanual_product/page_view');
        }
        if ($this->request->getPost()) {
            $rules = [
                'file' => 'uploaded[file]|ext_in[file,mp4]'
            ];
            if (!$this->validate($rules)) {
                $data['thumbnailvalidation'] = $this->validator;
            } else {
                if ($file = $this->request->getFile('file')) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $filename = $file->getName();
                        if (!is_dir(FCPATH . 'assets/uploads/emanual_video/' . $data['empg_id'])) {
                            mkdir('assets/uploads/emanual_video/' . $data['empg_id'], 0777, true);
                        }
                        if (file_exists(FCPATH . 'assets/uploads/emanual_video/' . $data['empg_id'] . "/" . $filename)) {
                            //session()->setFlashdata('error', $filename . ' '.lang('Messages.Success_0051'));
                            // session()->setFlashdata('error', $filename . ' '.lang('Messages.Success_0051'));
                            $result['status'] = 'error';
                            return json_encode($result);
                            //return redirect()->to(base_url() . '/emanual_pagecontent');

                        } else {
                            if ($file->move(FCPATH . 'assets/uploads/emanual_video/' . $data['empg_id'], $filename)) {
                                $filepath = FCPATH . 'assets/uploads/emanual_video/' . $data['empg_id'] . '/' . $filename;
                                if ($status == 5) {
                                    $newdata = [
                                        'content1' => $filename,
                                        'page_id' =>  $data['empg_id'],
                                        'reference_id' =>  $_POST['emc_id'],
                                        'type' => $this->request->getVar('type'),
                                        'status' => 1,
                                        'sequence' => $_POST['sequence'],
                                        'last_updated_by' =>  session()->get('id_user'),
                                        'last_updated_on' => time(),

                                    ];
                                    $result = $this->emanual_product_model->copyContent($newdata, $_POST['emc_id']);
                                } else {
                                    $newdata = [
                                        'content1' => $filename,
                                        'status' =>  1,
                                        'last_update' => time(),
                                        'last_updated_by' =>  session()->get('id_user'),
                                        'last_updated_on' => time(),
                                    ];
                                    $result = $this->emanual_product_model->deleteContent($newdata, $_POST['emc_id']);
                                }
                                if ($result) {
                                    return json_encode($result);
                                } else {
                                    return json_encode($result);
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
        return redirect()->to(base_url() . '/Emanual/emanual_pagecontent');
    }
    public function pagecontent_translate() //fetch data from users table to display
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['empg_id']) && (isset($_POST['emd_id']))) {
            $data['empg_id'] = $_POST['empg_id'];
            $data['emd_id'] = $_POST['emd_id'];
            $_SESSION['empg_id'] =  $data['empg_id'];
            $_SESSION['emd_id'] =  $data['emd_id'];
            $data['lang_id'] = $_POST['lang_id'];
            $_SESSION['lang_id'] =  $data['lang_id'];
        } else if (isset($_GET['empg_id']) && isset($_GET['emd_id'])) {
            $data['empg_id'] = $_GET['empg_id'];
            $data['emd_id'] = $_GET['emd_id'];
            $data['lang_id'] = $_GET['lang_id'];
        } else if (isset($_SESSION['empg_id']) && isset($_SESSION['emd_id'])) {
            $data['empg_id'] = $_SESSION['empg_id'];
            $data['emd_id'] = $_SESSION['emd_id'];
            $data['lang_id'] = $_SESSION['lang_id'];
        } else {
            return redirect()->to(base_url() . '/Emanual/emanual_product/page_view');
        }

        $data['getAllpagedetails'] =  $this->emanual_product_model->getAllpagedetails();
        $documentdata =  $this->emanual_product_model->getPagecount($data['emd_id']);
        $data['totalPages'] = $documentdata[0]['pagecount'];
        $pagedetails =  $this->emanual_product_model->getpageDetails($data['empg_id']);
        $data['page_name'] =  $pagedetails[0]['page_name'];
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
        $data['form_link'] = 'Emanual/emanual_product/edittranslatepage';
        $data['sub_header_4'] = 'Add Content - ' . $pagedetails[0]['page_name'];
        $data['contenttype'] =  $this->dropdown_model->getCountrylist(18);
        $data['productDetails'] = $this->emanual_product_model->getAllProductDetails();
        $data['pagecontentdata'] = $this->emanual_product_model->getPagetranslatecontentdata($data['empg_id']);
        echo view('templates/header_editor', $data);
        echo view('emanual/content_translate_list_view', $data);
        echo view('templates/footer_view');
    }
    function edittranslateContent()
    {
        $emc_id = $_POST['emc_id'];
        $page_id = $_POST['page_id'];
        $lang_id = $_POST['lang_id'];
        $content_id =  $emc_id;

        $newdata = [
            'translate_content1' => $this->request->getVar('content1'),
            'emc_id' => $content_id,
            'lang_id' => $lang_id,
            'page_id' => $page_id,
            'reference_id' => $emc_id,
            'type' => $this->request->getVar('type'),
            'status' => 1,
            // 'sequence' => $_POST['sequence'],
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),

        ];
        $result = $this->emanual_product_model->addtransaltecontent($newdata, $content_id, $lang_id);
        return json_encode($result);
    }
    function edittranslatepageUpload()
    {
        $data = [];
        helper(['form']);
        $emc_id = $_POST['emc_id'];
        $data['page_id'] = $_POST['page_id'];
        $lang_id = $_POST['lang_id'];
        $content_id =  $emc_id;
        if ($this->request->getPost()) {
            $rules = [
                'file' => 'uploaded[file]|ext_in[file,jpg,png]'
            ];
            if (!$this->validate($rules)) {
                $data['thumbnailvalidation'] = $this->validator;
            } else {
                if ($file = $this->request->getFile('file')) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $filename = $file->getName();
                        if (!is_dir(FCPATH . 'assets/uploads/emanual_image/' . $data['page_id'])) {
                            mkdir('assets/uploads/emanual_image/' . $data['page_id'], 0777, true);
                        }
                        if (file_exists(FCPATH . 'assets/uploads/emanual_image/' . $data['page_id'] . "/" . $filename)) {
                            //session()->setFlashdata('error', $filename . ' '.lang('Messages.Success_0051'));
                            // session()->setFlashdata('error', $filename . ' '.lang('Messages.Success_0051'));
                            $result['status'] = 'error';
                            return json_encode($result);
                            //return redirect()->to(base_url() . '/emanual_pagecontent');

                        } else {
                            if ($file->move(FCPATH . 'assets/uploads/emanual_image/' . $data['page_id'], $filename)) {
                                $filepath = FCPATH . 'assets/uploads/emanual_image/' . $data['page_id'] . '/' . $filename;
                                $newdata = [
                                    'translate_content1' => $filename,
                                    'emc_id' => $content_id,
                                    'lang_id' => $lang_id,
                                    'page_id' => $data['page_id'],
                                    'reference_id' => $emc_id,
                                    'type' => $this->request->getVar('type'),
                                    'status' => 1,
                                    // 'sequence' => $_POST['sequence'],
                                    'createdby' => session()->get('id_user'),
                                    'createdon' => time(),
                                    'last_updated_by' =>  session()->get('id_user'),
                                    'last_updated_on' => time(),

                                ];
                                $result = $this->emanual_product_model->addtransaltecontent($newdata, $content_id, $lang_id);
                                return json_encode($result);
                            }
                        }
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0001'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                    }
                }
            }
        }
        return redirect()->to(base_url() . '/Emanual/emanual_pagecontent');
    }
}
