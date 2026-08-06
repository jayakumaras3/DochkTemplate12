<?php

namespace App\Controllers;

use App\Models\Settings\Verbs_model;
#[\AllowDynamicProperties]
class Verbs extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->verbs_model = new Verbs_model();
    }
    public function index()
    {
        $data = [];
        helper(['form']);
        helper(['form']);
        $data['header'] = 'Verbs';
        $data['verbs'] = $this->verbs_model->getAllVerbs();

        echo view('XAPI/verbs', $data);
    }

    public function admin_verbs()
    {
        $data = [];
        helper(['form']);
        $data['header'] = 'AR/VR/Sim Verbs';

        $data['verbs'] = $this->verbs_model->getAllVerbs();
        echo view('templates/header_view', $data);
        echo view('XAPI/admin_verbs', $data);
        echo view('templates/footer_view');
    }

    public function viewVerbDetails()
    {
        $data = [];
        helper(['form']);
        $data['header'] = 'AR/VR/Sim Verbs';
       if ($this->request->getPost()) {
            $verbid = $this->request->getVar('verbid');
            $data['specific'] = $this->verbs_model->getSpecificVerb($verbid);
            echo view('templates/header_view', $data);
            echo view('XAPI/admin_verbs_details', $data);
            echo view('templates/footer_view');
        } else {
            return redirect()->to(base_url() . '/verbs/admin_verbs');
        }
    }

    public function createNewVerb()
    {
        $data = [];
        helper(['form']);
       if ($this->request->getPost()) {
            $newdata = [
                'verb' => strtolower($this->request->getVar('verb')),
                'negative_verb'  => strtolower($this->request->getVar('negative_verb')),
                'description' => $this->request->getVar('description'),
                'status' => '1',
                'createdby' => session()->get('id_user'),
                'createdon' => time(),
                'last_updated_by' =>  session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $result = $this->verbs_model->createNewVerb($newdata);
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0011'));
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0001'));
                session()->setFlashdata('alert-class', 'alert-danger');
            }
            return redirect()->to(base_url() . '/verbs/admin_verbs');
        }
    }


    public function updateVariable()
    {
        $data = [];
        helper(['form']);
       if ($this->request->getPost()) {
            $verbid = $this->request->getVar('verbid');
            $newdata = [
                'verb' => strtolower($this->request->getVar('verb')),
                'negative_verb'  => strtolower($this->request->getVar('negative_verb')),
                'description' => $this->request->getVar('description'),
                'last_updated_by' =>  session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $result = $this->verbs_model->updateVerb($newdata, $verbid);
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0008'));
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0001'));
                session()->setFlashdata('alert-class', 'alert-danger');
            }
            return redirect()->to(base_url() . '/verbs/admin_verbs');
        }
    }
}
