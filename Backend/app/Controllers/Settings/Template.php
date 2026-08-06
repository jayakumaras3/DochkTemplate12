<?php

namespace App\Controllers;


use App\Models\Settings\Dropdown_model;
use App\Models\User_login\Login_model;
use App\Models\Settings\Template_model;
use CodeIgniter\I18n\Time;
#[\AllowDynamicProperties]
class Template extends BaseController
{
    private $db;

    public function __construct()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url() . '/');
        } else {
            $this->dropdown_model = new Dropdown_model();
            $this->login_model = new Login_model();
            $this->template_model = new Template_model();
        }
    }
    public function index() //fetch data from users table to display
    {
        $data = [];
        helper(['form']);
        $data['templatedata'] = $this->dropdown_model->getdropdownData(9);
        //print_r($data['templatedata']);
        //exit();
        echo view('templates/header_view', $data);
        echo view('project_template/template_view', $data);
        echo view('templates/footer_view');
    }
    function addtemplate()
    {
        $data = [];
        helper(['form']);
        $data['templatedata'] = $this->dropdown_model->getdropdownData(9);
        if ($this->request->getPost()) {
            $rulesData = [
                'templatename' => 'required|max_length[32]',
            ];
            $data['table'] = $this->dropdown_model->categoryDetails();
            if (!$this->validate($rulesData)) {
                $data['validationData'] = $this->validator;
            } else {

                $timestamp = time();
                $newdata = [
                    'fk_id_dc' => '9',
                    'name' => $this->request->getVar('templatename'),
                    'createdon' => $timestamp,
                    'createdby' => session()->get('id_user'),
                    'status' => '1',
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                //print_r($newdata);
                $result = $this->dropdown_model->saveTemplate($newdata);
                if ($result) {
                    session()->setFlashdata('success', lang('Messages.Success_0011'));
                    return redirect()->to(base_url() . '/template');
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url() . '/template');
                }
            }
        }
        echo view('templates/header_view', $data);
        echo view('project_template/template_view', $data);
        echo view('templates/footer_view');
    }
    function edittemplate($id_d)
    {
        $data = [];
        helper(['form']);
        $data['templatedata'] = $this->dropdown_model->getdropdownData(9);
        $eachtemplate = $this->dropdown_model->geteachtemplate($id_d);
        $data['row'] = $eachtemplate[0];
        // print_r($data['row']);
        if ($this->request->getPost()) {
            $rulesData = [
                'templatename' => 'required|max_length[32]',
            ];
            $data['table'] = $this->dropdown_model->categoryDetails();
            if (!$this->validate($rulesData)) {
                $data['validationData'] = $this->validator;
            } else {

                $timestamp = time();
                $newdata = [
                    'fk_id_dc' => '9',
                    'name' => $this->request->getVar('templatename'),
                    'createdon' => $timestamp,
                    'createdby' => session()->get('id_user'),
                    'status' => '1',
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                //print_r($newdata);
                $result = $this->dropdown_model->updateTemplate($newdata, $id_d);
                if ($result) {
                    session()->setFlashdata('success', lang('Messages.Success_0008'));
                    return redirect()->to(base_url() . '/template');
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url() . '/template');
                }
            }
        }
        echo view('templates/header_view', $data);
        echo view('project_template/template_edit_view', $data);
        echo view('templates/footer_view');
    }
    function templatedetails($id_d)
    {
        $data = [];
        helper(['form']);
        $data['id_d'] = $id_d;
        // $data['clientdata'] = $this->dropdown_model->getclientData();
        $data['clientdata'] = $this->dropdown_model->getdropdownData(12);
        $data['templatedata'] = $this->dropdown_model->getdropdownData(9);
        $data['templatedetails'] = $this->template_model->gettemplatedetails($id_d);
        echo view('templates/header_view', $data);
        echo view('project_template/template_details_view', $data);
        echo view('templates/footer_view');
    }
    function addtemplatedetails($id_d)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url() . '/');
        } else {
            $data = [];
            helper(['form']);
            $data['id_d'] = $id_d;
            //$data['clientdata'] = $this->dropdown_model->getclientData();
            $data['clientdata'] = $this->dropdown_model->getdropdownData(12);
            $data['templatedetails'] = $this->template_model->gettemplatedetails($id_d);
            if ($this->request->getPost()) {
                $rulesData = [

                    'item_type' => 'required|max_length[32]',
                    'item_description' => 'required|max_length[100]',
                    'duration' => 'required|max_length[10]',

                ];
                if (!$this->validate($rulesData)) {
                    $data['validationData'] = $this->validator;
                } else {

                    $timestamp = time();
                    $newdata = [
                        'fk_template_id' => $id_d,
                        'item_type' => $this->request->getVar('item_type'),
                        'item_description' => $this->request->getVar('item_description'),
                        'duration' => $this->request->getVar('duration'),
                        'status' => '1',
                        'createdon' => $timestamp,
                        'createdby' => session()->get('id_user'),
                        'status' => '1',
                        'last_updated_by' => session()->get('id_user'),
                        'last_updated_on' => time(),
                    ];
                    //print_r($newdata);

                    $result = $this->template_model->addtemplatedetails($newdata);
                    if ($result) {
                        session()->setFlashdata('success', lang('Messages.Success_0011'));
                        return redirect()->to(base_url() . '/template/templatedetails/' . $id_d);
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0003'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                        return redirect()->to(base_url() . '/template/templatedetails/' . $id_d);
                    }
                }
            }
            echo view('templates/header_view', $data);
            echo view('project_template/template_details_view', $data);
            echo view('templates/footer_view');
        }
    }
    function edittemplatedetails($fk_template_id, $t_id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url() . '/');
        } else {
            $data = [];
            helper(['form']);
            $data['t_id'] = $t_id;
            //$data['clientdata'] = $this->dropdown_model->getclientData();
            $data['clientdata'] = $this->dropdown_model->getdropdownData(12);
            $eachtempdetails = $this->template_model->geteachtempdetails($t_id);
            $data['row'] = $eachtempdetails[0];
            if ($this->request->getPost()) {
                $rulesData = [

                    'item_type' => 'required|max_length[32]',
                    'item_description' => 'required|max_length[100]',
                    'duration' => 'required|max_length[10]',

                ];
                if (!$this->validate($rulesData)) {
                    $data['validationData'] = $this->validator;
                } else {

                    $timestamp = time();
                    $newdata = [
                        'fk_template_id' => $fk_template_id,
                        'item_type' => $this->request->getVar('item_type'),
                        'item_description' => $this->request->getVar('item_description'),
                        'duration' => $this->request->getVar('duration'),
                        'status' => '1',
                        'createdon' => $timestamp,
                        'createdby' => session()->get('id_user'),
                        'status' => '1',
                        'last_updated_by' => session()->get('id_user'),
                        'last_updated_on' => time(),
                    ];
                    //print_r($newdata);

                    $result = $this->template_model->edittemplatedetails($newdata, $t_id);
                    if ($result) {
                        session()->setFlashdata('success', lang('Messages.Success_0008'));
                        return redirect()->to(base_url() . '/template/templatedetails/' . $fk_template_id);
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0003'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                        return redirect()->to(base_url() . '/template/templatedetails/' . $fk_template_id);
                    }
                }
            }
            echo view('templates/header_view', $data);
            echo view('project_template/template_details_edit_view', $data);
            echo view('templates/footer_view');
        }
    }
    function deletetemplate($id_d)
    {
        $newdata = ['id_d' => $id_d, 'status' => '0'  ];
        $result = $this->dropdown_model->deleteTemplate($id_d, $newdata);
        if ($result) {
            $sessionData = session();
            $sessionData->setFlashdata('success', 'Template item : Deleted Successful');
            return redirect()->to(base_url() . '/template');
        }
        session()->setFlashdata('error', lang('Messages.Error_0001'));
        session()->setFlashdata('alert-class', 'alert-danger');
        return redirect()->to(base_url() . '/template');
    }
    function deletetemplatedetails($id_d, $t_id)
    {
        $newdata = ['t_id' => $t_id, 'status' => '0'  ];
        $result = $this->template_model->deleteTemplatedetails($t_id, $newdata);
        if ($result) {
            $sessionData = session();
            $sessionData->setFlashdata('success', 'Template item : Deleted Successful');
            return redirect()->to(base_url() . '/template/templatedetails/' . $id_d);
        }
        session()->setFlashdata('error', lang('Messages.Error_0001'));
        session()->setFlashdata('alert-class', 'alert-danger');
        return redirect()->to(base_url() . '/template/templatedetails/' . $id_d);
    }
}
