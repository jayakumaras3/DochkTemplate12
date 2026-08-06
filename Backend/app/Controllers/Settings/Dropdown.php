<?php

namespace App\Controllers;

use App\Models\Settings\Dropdown_model;
use CodeIgniter\I18n\Time;
#[\AllowDynamicProperties]
class Dropdown extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->is_session_available();
        $this->dropdown_model = new Dropdown_model();
    }
    private function is_session_available()
    {
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url('my_training'));
            exit();
        }

        $arrayuserlevel = explode(',', $userlevel);
        if (!in_array('4', $arrayuserlevel) || !in_array('6', $arrayuserlevel)) {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            header('Location:' . base_url('my_training'));
            exit();
        }
    }
    public function index() //fetch data from users table to display
    {
        $data = [];
        helper(['form']);
        //$data['locale'] = $this->request->getLocale();
        $userlevel = session()->get('userlevel');
        $arrayuserlevel  = explode(',', $userlevel);
        if (in_array('4', $arrayuserlevel) || in_array('6', $arrayuserlevel)) {
            $data['table'] = $this->dropdown_model->categoryDetails();
            $data['categoryData'] = $this->dropdown_model->getCategoryData();
            echo view('templates/header_view', $data);
            echo view('dropdown/dropdown_view', $data);
            echo view('templates/footer_view');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            return redirect()->to(base_url('dashboard'));
        }
    }
    public function category()
    {
        $data = [];
        helper(['form']);
       if ($this->request->getPost()) {
            $rulesData = [
                'catogery_name' => 'required|min_length[3]|max_length[32]',

            ];
            $data['table'] = $this->dropdown_model->categoryDetails();
            $data['categoryData'] = $this->dropdown_model->getCategoryData();
            if (!$this->validate($rulesData)) {
                $data['validationData'] = $this->validator;
            } else {

                $timestamp = time();
                $newdata = [
                    'name' => $this->request->getVar('catogery_name'),
                    'createdon' => $timestamp,
                    'createdby' => session()->get('username'),
                    'status' => '1',
                    'last_updated_by' =>  session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                //print_r($newdata);
                $result = $this->dropdown_model->saveCategory($newdata);
                if ($result) {
                    session()->setFlashdata('success', lang('Messages.Success_0011'));
                    return redirect()->to(base_url() . '/dropdown');
                }
                session()->setFlashdata('error', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->route('dropdown')->withInput();
            }
        }
        echo view('templates/header_view', $data);
        echo view('dropdown/dropdown_view', $data);
        echo view('templates/footer_view');
    }
    public function categoryItem()
    {
        $data = [];
        helper(['form']);
       if ($this->request->getPost()) {
            $rules = [
                'name' => 'required',
                'category' => 'required',
            ];
            $data['table'] = $this->dropdown_model->categoryDetails();
            $data['categoryData'] = $this->dropdown_model->getCategoryData();
            if (!$this->validate($rules)) {
                $data['validation'] = $this->validator;
            } else {
                $timestamp = time();
                $newdata = [
                    'fk_id_dc' => $this->request->getVar('category'),
                    'name' => $this->request->getVar('name'),
                    'createdon' => $timestamp,
                    'createdby' => session()->get('id_user'),
                    'status' => '1',
                    'last_updated_by' =>  session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                //print_r($newdata);
                $result = $this->dropdown_model->savecategoryItem($newdata);
                if ($result) {
                    session()->setFlashdata('success', lang('Messages.Success_0011'));
                    return redirect()->to(base_url() . '/dropdown');
                }
                session()->setFlashdata('error', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->route('dropdown')->withInput();
            }
        }

        echo view('templates/header_view');
        echo view('dropdown/dropdown_view', $data);
        echo view('templates/footer_view');
    }
    public function deleteCategory($categoryitemID)
    {
        $result = $this->dropdown_model->deleteCategoryItem($categoryitemID);
        if ($result) {
            $sessionData =  session();
            $sessionData->setFlashdata('success', 'Category item : deleted Successful');
            return redirect()->to(base_url() . '/dropdown');
        }
        session()->setFlashdata('error', lang('Messages.Error_0001'));
        session()->setFlashdata('alert-class', 'alert-danger');
        return redirect()->route('dropdown')->withInput();
    }
}
