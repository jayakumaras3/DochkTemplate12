<?php

namespace App\Controllers\Access;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Access\AccessModel;

#[\AllowDynamicProperties]

class AccessController extends BaseController
{
    protected $AccessModel;
    public function __construct()
    {
        $this->is_session_available();
        $this->AccessModel = new AccessModel();
    }
    private function is_session_available()
    {
        $client = session()->get('client');
        $userlevel = session('userlevel');
        $arrayuserlevel = array_map('intval', explode(',', $userlevel) ?? '');
        if (!in_array('6', $arrayuserlevel)) {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            header('Location:' . base_url('my_training'));
            exit();
        }
    }

    public function index()
    {
        if ($response =  $this->requireRole(['6'])) {
            return $response;
        }
        $data = [];
        $data = [
            'link1' => '',
            'link1_name' => '',
            'link2' => '',
            'link2_name' => '',
            'link3_name' => 'Access'
        ];

        $client = session()->get('client');
        $data['userlevelData'] = $this->AccessModel->accesslevels($client);
        echo view('templates/header_view', $data);
        echo view('access/access_admin_view', $data);
        echo view('templates/footer_view');
    }


    function addNewAccess()
    {
        if ($response =  $this->requireRole(['6'])) {
            return $response;
        }
        $newdata = [
            'id_ua' => $this->request->getVar('id_ua'),
            'fk_id_dc' => $this->request->getVar('type'),
            'name' => $this->request->getVar('name'),
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => '1'
        ];

        $this->AccessModel->add_new_access($newdata);
        session()->setFlashdata('success', lang('Messages.Success_0002'));
        return redirect()->to(base_url() . 'Access/accessController');
    }

    function delete_access()
    {
        if ($response =  $this->requireRole(['6'])) {
            return $response;
        }
        $id_du = $this->request->getVar('access_id');
        if (empty($id_du)) {
            session()->setFlashdata('error', lang('Messages.Error_0005'));
            return redirect()->to(base_url() . 'Access/accessController');
        }
        $newdata = [
             
            
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => '0',
        ];

        $this->AccessModel->delete_user_access($newdata, $id_du);
        session()->setFlashdata('success', lang('Messages.Success_0003'));
        return redirect()->to(base_url() . 'Access/accessController');
    }

    function view_access_users()
    {
        if ($response =  $this->requireRole(['6'])) {
            return $response;
        }
        $data = [];
        $userlevel = session()->get('userlevel');
        $arrayuserlevel = array_map('intval', explode(',', $userlevel) ?? '');
        if (in_array('6', $arrayuserlevel)) {

            $data = [
                'link1' => 'Access/accessController',
                'link1_name' => 'Access List',
                'link2' => '',
                'link2_name' => '',
                'link3_name' => 'Access'
            ];
            $client = 1;
            $accessId = $this->request->getVar('access_id');
            if (!empty($accessId)) {
                $data['access_id'] = $this->request->getVar('access_id');
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0005'));
                return redirect()->to(base_url() . 'Access/accessController');
            }
            $data['access_id'] = $this->request->getVar('access_id');
            $data['access_details'] = $this->AccessModel->geteachtemplate($accessId);
            $data['userlevelData'] = $this->AccessModel->access_users($accessId, $client);

            echo view('templates/header_view', $data);
            echo view('access/access_user_list_view', $data);
            echo view('templates/footer_view');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            return redirect()->to(base_url() . 'Access/accessController');
        }
    }

    function editAccesslevel()
    {
        if ($response =  $this->requireRole(['6'])) {
            return $response;
        }
        $id_ua = $this->request->getVar('id_ua');
        $newdata = [
            'id_ua' => $this->request->getVar('id_ua'),
            'name' => $this->request->getVar('name'),
            'status' => $this->request->getVar('status'),
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),

        ];
        $this->AccessModel->update_user_access_level($newdata, $id_ua);
        session()->setFlashdata('success', lang('Messages.Success_0004'));
        return redirect()->to(base_url() . 'Access/accessController');
    }
}
