<?php

namespace App\Controllers\Holiday;

use App\Controllers\BaseController;
use App\Models\Holiday\Holidays_model;
use App\Models\Settings\Dropdown_model;

#[\AllowDynamicProperties]

class Holidays extends BaseController
{
    protected $holidays_model;
    protected $dropdown_model;
    public function __construct()
    {
        $this->holidays_model = new Holidays_model();
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
        if (!in_array('6', $arrayuserlevel) && !in_array('2010', $arrayuserlevel)) {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            header('Location:' . base_url('my_training'));
            exit();
        }
    }
    function index()
    {
        $data = [];
        $data = [
            'link1' => '',
            'link1_name' => '',
            'link2' => '',
            'link2_name' => '',
            'link3_name' => 'Holidays'
        ];

        if (isset($_POST['show_year'])) {
            $data['show_year'] = $_POST['show_year'];
            $_SESSION['show_year'] = $_POST['show_year'];
        } elseif (isset($_SESSION['show_year'])) {
            $data['show_year'] = $_SESSION['show_year'];
        } else {
            $data['show_year'] = date('Y');
        }

        $data['indiaholidaydata'] = $this->holidays_model->holiday_list(1, $data['show_year']);
        $data['usholidaydata'] = $this->holidays_model->holiday_list(2, $data['show_year']);
        echo view('templates/header_view', $data);
        echo view('holidays/holidays_admin_view', $data);
        echo view('templates/footer_view');
    }

 /*    function access()
    {
        $data = [];
        $data = [
            'link1' => '',
            'link1_name' => '',
            'link2' => '',
            'link2_name' => '',
            'link3_name' => 'Access'
        ];
        $userlevel = session()->get('userlevel');
        $arrayuserlevel = array_map('intval', explode(',', $userlevel) ?? '');
        if (in_array('6', $arrayuserlevel)) {
            $client = session()->get('client');
            $data['userlevelData'] = $this->dropdown_model->accesslevels($client);

            echo view('templates/header_view', $data);
            // echo view('settings/admin_left_menu', $data);
            echo view('holidays/access_admin_view', $data);
            echo view('templates/footer_view');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            return redirect()->to(base_url() . 'holiday/holidays');
        }
    } */

  /*   function addNewAccess()
    {
        $newdata = [
            'id_ua' => $this->request->getVar('id_ua'),
            'fk_id_dc' => $this->request->getVar('type'),
            'name' => $this->request->getVar('name'),
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => '1'
        ];

        $this->dropdown_model->add_new_access($newdata);
        session()->setFlashdata('success', 'New Access Added.');
        return redirect()->to(base_url() . 'holiday/holidays/access');
    } */


   /*  function addnewAccessType()
    {
        $newdata = [
             
            
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => '0',
        ];

        $this->dropdown_model->delete_user_access($newdata);
        session()->setFlashdata('success', 'New Access Added.');
        return redirect()->to(base_url() . 'holiday/holidays');
    } */


    function view_access_users()
    {
        $data = [];
        $userlevel = session()->get('userlevel');
        $arrayuserlevel = array_map('intval', explode(',', $userlevel) ?? '');
        if (in_array('6', $arrayuserlevel)) {

            $data = [
                'link1' => 'holiday/holidays/access',
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
                session()->setFlashdata('error', lang('Messages.Error_0003'));
                return redirect()->to(base_url() . 'holiday/holidays/access');
            }
            $data['access_id'] = $this->request->getVar('access_id');
            $data['access_details'] = $this->dropdown_model->geteachtemplate($accessId);
            $data['userlevelData'] = $this->dropdown_model->access_users($accessId, $client);

            echo view('templates/header_view', $data);
            //  echo view('settings/admin_left_menu', $data);
            echo view('holidays/access_user_list_view', $data);
            echo view('templates/footer_view');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            return redirect()->to(base_url() . 'holiday/holidays');
        }
    }

    function delete_access()
    {
        $id_du = $this->request->getVar('access_id');
        $newdata = [
             
            
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => '0',
        ];

        $this->dropdown_model->delete_user_access($newdata, $id_du);
        session()->setFlashdata('success', lang('Messages.Success_0019'));
        return redirect()->to(base_url() . 'holiday/holidays/access');
    }
    function editAccesslevel()
    {
        $id_ua = $this->request->getVar('id_ua');
        $newdata = [
            'id_ua' => $this->request->getVar('id_ua'),
            'name' => $this->request->getVar('name'),
            'status' => $this->request->getVar('status'),
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),

        ];
        $this->dropdown_model->update_user_access_level($newdata, $id_ua);
        session()->setFlashdata('success', lang('Messages.Success_0008'));
        return redirect()->to(base_url() . 'holiday/holidays/access');
    }
    function addholidays()
    {
        $userlevel = session()->get('userlevel');
        $array = array_map('intval', str_split($userlevel));
        if (in_array("6", $array) || in_array("4", $array)) {
            $data = [];
            helper(['form']);
            $data['countrylist'] = $this->dropdown_model->getdropdownData(13);
            if ($this->request->getPost()) {
                $rules = [
                    'holiday_dt' => 'required',
                    'description' => 'required',
                    'country' => 'required',

                ];
                if (!$this->validate($rules)) {
                    $data['validation'] = $this->validator;
                } else {
                    $timestamp = time();
                    $newdata = [
                        'holiday_dt' => date('Y-m-d', strtotime($this->request->getVar('holiday_dt'))),
                        'description' => $this->request->getVar('description'),
                        'country' => $this->request->getVar('country'),
                        'type' => $this->request->getVar('type'),
                        'status' => '1',
                        'createdby' => session()->get('username'),
                        'createdon' => $timestamp,
                        'last_updated_by' => session()->get('id_user'),
                        'last_updated_on' => time(),
                    ];
                    $result = $this->holidays_model->addholidays($newdata);
                    if ($result) {
                        return redirect()->to(base_url() . 'holiday/holidays')
                            ->with('success', 'Added successfully');
                    }
                }
            }
            echo view('templates/header_view', $data);
            echo view('holidays/holidays_admin_view', $data);
            echo view('templates/footer_view');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            return redirect()->to(base_url() . 'holiday/holidays');
        }
    }
    function deleteholiday()
    {
        $id_hd = $this->request->getVar('id_hd');
        //echo 'Came here'; 
        //exit();
        $newdata = [
             
            
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => '0',
        ];
        // print_r($newdata);
        // exit();
        $result = $this->holidays_model->deleteholidays($newdata, $id_hd);
        if ($result) {
            return redirect()->to(base_url() . 'holiday/holidays')
                ->with('success', 'Deleted successfully');
        } else {
            return redirect()->to(base_url() . 'holiday/holidays')
                ->with('error', lang('Messages.Error_0001'));
        }
    }
}
