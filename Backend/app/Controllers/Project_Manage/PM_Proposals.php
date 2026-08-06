<?php

namespace App\Controllers\Project_Manage;

use App\Controllers\BaseController;
use App\Models\Project_Manage\PM_Proposals_model;
use App\Models\Project_Manage\PM_pricing_sheet_model;
use Dompdf\Dompdf;
#[\AllowDynamicProperties]
class PM_Proposals extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->is_session_available();
        $this->PM_Proposals_model = new PM_Proposals_model();
        $this->PM_pricing_sheet_model = new PM_pricing_sheet_model();
    }
    private function is_session_available()
    {
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url());
            exit();
        }

        $arrayuserlevel = explode(',', $userlevel);

        if (!in_array('4', $arrayuserlevel)) {

            session()->setFlashdata('error', lang('Messages.Error_0004'));
            header('Location:' . base_url('my_training'));
            exit();
        }
    }
    public function index()
    {
        $data = [];
        helper(['form']);
        $data = [
            'link1' => 'my_training',
            'link1_name' => 'Dashboard',
            'link2' => '',
            'link2_name' => '',
            'link3_name' => 'Proposals'
        ];
        $data['proposal_list'] = $this->PM_Proposals_model->get_proposal_list();
        echo view('templates/header_view', $data);
      //  echo view('dashboard/projects_left_menu', $data);
        echo view('project_management/proposal_dashboard_view', $data);
        echo view('templates/footer_view');
    }


    public function proposal_details()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['proposal_id'])) {
            $data['proposal_id'] = $this->request->getVar('proposal_id');;
            $_SESSION['proposal_id'] =  $data['proposal_id'];
        } else if (isset($_SESSION['proposal_id'])) {
            $data['proposal_id'] = $_SESSION['proposal_id'];
        }
        $data['get_pricing_data'] = $this->PM_Proposals_model->get_pricing_data();
        $data['get_proposal_data'] = $this->PM_Proposals_model->get_proposal_data($data['proposal_id']);
        $data['get_proposal_details_ps'] = $this->PM_Proposals_model->get_proposal_details($data['proposal_id'], 1);
        $data['get_proposal_details_milestone'] = $this->PM_Proposals_model->get_proposal_details($data['proposal_id'], 2);
        $data['get_proposal_details_taxes'] = $this->PM_Proposals_model->get_proposal_details($data['proposal_id'], 3);
        $data['get_proposal_details_image'] = $this->PM_Proposals_model->get_proposal_details($data['proposal_id'], 4);
        // print_r($data['get_proposal_details_image']);
        // exit();
        echo view('templates/header_view', $data);
        echo view('project_management/proposal_detail', $data);
        echo view('templates/footer_view');
    }


    public function proposal_edit()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['proposal_id'])) {
            $data['proposal_id'] = $this->request->getVar('proposal_id');;
            $_SESSION['proposal_id'] =  $data['proposal_id'];
        } else if (isset($_SESSION['proposal_id'])) {
            $data['proposal_id'] = $_SESSION['proposal_id'];
        }

        $data['project_manager']  = $this->PM_pricing_sheet_model->get_project_manager();
        $data['access']  = $this->PM_pricing_sheet_model->get_access_info($data['proposal_id'], 3);
        $data['getclients'] = $this->PM_pricing_sheet_model->getclients_project_assignment();
        $data['get_proposal_data'] = $this->PM_Proposals_model->get_proposal_data($data['proposal_id']);
        $data['salesuser'] =  $this->PM_pricing_sheet_model->getSalesuseraccess(68);
        echo view('templates/header_view', $data);
        echo view('project_management/proposal_edit', $data);
        echo view('templates/footer_view');
    }
    public function add_proposal()
    {
        $data = [];
        helper(['form']); 
        $data['post'] = '';
        $data['getclients'] = $this->PM_pricing_sheet_model->getclients_project_assignment();
        $data['salesuser'] =  $this->PM_pricing_sheet_model->getSalesuseraccess(68);
        echo view('templates/header_view', $data);
        echo view('project_management/proposal_add', $data);
        echo view('templates/footer_view');
    }

    public function delete_details()
    {
        $data = [];
        if ($this->request->getPost()) {
            $timestamp = time();
            $proposal_details_id =  $this->request->getVar('proposal_details_id');
            $newdata = [
                'last_updated_on' => $timestamp,
                'last_updated_by' => session()->get('id_user'),
                'status' => '0'
            ];

            $result = $this->PM_Proposals_model->delete_proposal_details($newdata, $proposal_details_id);

            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0011'));
                return redirect()->to(base_url() . 'Project_Manage/PM_Proposals/proposal_details');
            } else {
                session()->setFlashdata('message', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . 'Project_Manage/PM_Proposals/proposal_details');
            }
        }
    }

    public function updatelockstatus()
    {
        $data = [];
        if ($this->request->getPost()) {
            $timestamp = time();
            $proposal_id =  $this->request->getVar('proposal_id');
            $status =  $this->request->getVar('status');
            $newdata = [
                'last_updated_on' => $timestamp,
                'last_updated_by' => session()->get('id_user'),
                'status' => $status
            ];

            $result = $this->PM_Proposals_model->update_proposal_status($newdata, $proposal_id);

            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0008'));
                return redirect()->to(base_url() . 'Project_Manage/PM_Proposals');
            } else {
                session()->setFlashdata('message', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . 'Project_Manage/PM_Proposals');
            }
        }
    }

    public function add_proposal_submit()
    {
        $data = [];
        if ($this->request->getPost()) {
            $timestamp = time();

            $newdata = [
                'short_name' => $this->request->getVar('proposal_name'),
                'client' => $this->request->getVar('client'),
                'account_manager' => $this->request->getVar('account_manager'),
                'templates' => $this->request->getVar('template'),

                'about_client' => $this->request->getVar('about_client'),
                'requirement' =>  $this->request->getVar('requirement'),
                'solution' => $this->request->getVar('solution'),
                'assumption' => $this->request->getVar('assumption'),

                'last_updated_on' => $timestamp,
                'last_updated_by' => session()->get('id_user'),
                'status' => '1'
            ];

            $result = $this->PM_Proposals_model->add_new_proposal($newdata);
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0011'));
                return redirect()->to(base_url() . '/Project_Manage/PM_Proposals');
            } else {
                session()->setFlashdata('message', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . '/Project_Manage/PM_Proposals');
            }
        }
    }
    public function add_proposal_details()
    {
        $data = [];

        if (isset($_POST['proposal_id'])) {
            $_SESSION['proposal_id'] = $this->request->getVar('proposal_id');;
        }

        if ($this->request->getPost()) {
            $timestamp = time();

            $newdata = [
                'proposal_id' => $this->request->getVar('proposal_id'),

                'details_01' => $this->request->getVar('details_01'),
                'details_02' => $this->request->getVar('details_02'),
                'details_03' => $this->request->getVar('details_03'),
                'types' => $this->request->getVar('types'),

                'created_on' => $timestamp,
                'created_by' => session()->get('id_user'),

                'last_updated_on' => $timestamp,
                'last_updated_by' => session()->get('id_user'),
                'status' => '1'
            ];

            $result = $this->PM_Proposals_model->add_proposal_details($newdata);

            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0011'));
                return redirect()->to(base_url() . '/Project_Manage/PM_Proposals/proposal_details');
            } else {
                session()->setFlashdata('message', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . '/Project_Manage/PM_Proposals/proposal_details');
            }
        }
    }
    public function edit_proposal_submit()
    {
        $data = [];
        if ($this->request->getPost()) {
            $timestamp = time();
            if (isset($_POST['proposal_id'])) {
                $data['proposal_id'] = $this->request->getVar('proposal_id');;
                $_SESSION['proposal_id'] =  $data['proposal_id'];
            } else if (isset($_SESSION['proposal_id'])) {
                $data['proposal_id'] = $_SESSION['proposal_id'];
            }
            $newdata = [
                'short_name' => $this->request->getVar('proposal_name'),
                'client' => $this->request->getVar('client'),
                'account_manager' => $this->request->getVar('account_manager'),
                'templates' => $this->request->getVar('template'),

                'about_client' => $this->request->getVar('about_client'),
                'requirement' =>  $this->request->getVar('requirement'),
                'solution' => $this->request->getVar('solution'),
                'assumption' => $this->request->getVar('assumption'),

                'last_updated_on' => $timestamp,
                'last_updated_by' => session()->get('id_user'),
                'status' =>  $this->request->getVar('status')
            ];

            $result = $this->PM_Proposals_model->edit_proposal($newdata, $data['proposal_id']);
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0011'));
                return redirect()->to(base_url() . '/Project_Manage/PM_Proposals');
            } else {
                session()->setFlashdata('message', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . '/Project_Manage/PM_Proposals');
            }
        }
    }



    public function purchase_orders()
    {
        $data = [];
        helper(['form']);
        $data = [
            'link1' => 'my_training',
            'link1_name' => 'Dashboard',
            'link2' => '',
            'link2_name' => '',
            'link3_name' => 'Purchase Orders'
        ];
        echo view('templates/header_view', $data);
        echo view('dashboard/projects_left_menu', $data);
        echo view('project_management/purchase_orders_dashboard_view', $data);
        echo view('templates/footer_view');
    }
    public function reports()
    {
        $data = [];
        helper(['form']);
        $data = [
            'link1' => 'my_training',
            'link1_name' => 'Dashboard',
            'link2' => '',
            'link2_name' => '',
            'link3_name' => 'Reports'
        ];
        echo view('templates/header_view', $data);
        echo view('dashboard/projects_left_menu', $data);
        echo view('project_management/reports_view', $data);
        echo view('templates/footer_view');
    }
    function upload_image()
    {
        $data = [];
        helper(['filesystem']);
        if (isset($_POST['proposal_id'])) {
            $data['proposal_id'] = $_POST['proposal_id'];
            $_SESSION['proposal_id'] =  $data['proposal_id'];
            $data['cover_pageid'] = $_POST['cover_pageid'];
            $_SESSION['cover_pageid'] =  $data['cover_pageid'];
        } else if (isset($_GET['proposal_id'])) {
            $data['proposal_id'] = $_GET['proposal_id'];
            $data['cover_pageid'] = $_GET['cover_pageid'];
        } else if (isset($_SESSION['proposal_id'])) {
            $data['proposal_id'] = $_SESSION['proposal_id'];
            $data['cover_pageid'] = $_SESSION['cover_pageid'];
        }

        if ($this->request->getPost()) {
            $rules = [
                'file' => 'uploaded[file]|ext_in[file,jpg,png]'
            ];
            // print_r($this->request->getFile('file'));
            // exit();
            if ($file = $this->request->getFile('file')) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $filename = $file->getName();
                    if (!is_dir(FCPATH . 'assets/assets/uploads/proposal_image/' . $data['proposal_id'])) {
                        mkdir('assets/assets/uploads/proposal_image/' . $data['proposal_id'], 0777, true);
                    }
                    if (file_exists(FCPATH . 'assets/assets/uploads/proposal_image/' . $data['proposal_id'] . "/" . $filename)) {
                        session()->setFlashdata('error', $filename . ' '.lang('Messages.Success_0051'));
                    } else {
                        if ($file->move(FCPATH . 'assets/assets/uploads/proposal_image/' . $data['proposal_id'], $filename)) {
                            $filepath = FCPATH . 'assets/assets/uploads/proposal_image/' . $data['proposal_id'] . '/' . $filename;
                            $get_proposal_details_image = $this->PM_Proposals_model->get_proposalimage_details($data['proposal_id'], 4);

                            $count = 0;
                            if (empty($get_proposal_details_image)) {
                                $count = 1;
                                // print_r(  $count."ss");
                            } elseif (!empty($get_proposal_details_image['0']['details_02'])) {
                                $count = $get_proposal_details_image['0']['details_02'] + 1;
                                // print_r(  $count."tt");
                            }
                            // exit();
                            $newdata = [
                                'proposal_id' => $data['proposal_id'],
                                'types' => '4',
                                'details_01' => $data['cover_pageid'],
                                'details_02' =>  $count,
                                'details_03' => $filename,
                                'status' => 1,
                                'created_by' =>  session()->get('id_user'),
                                'created_on' => time(),
                                'last_updated_by' =>  session()->get('id_user'),
                                'last_updated_on' => time(),
                            ];
                            $result = $this->PM_Proposals_model->add_proposal_details($newdata);
                            if ($result) {
                                session()->setFlashdata('success', lang('Messages.Success_0011'));
                                return redirect()->to(base_url() . 'Project_Manage/PM_Proposals/proposal_details');
                            } else {
                                session()->setFlashdata('error', lang('Messages.Error_0001'));
                                session()->setFlashdata('alert-class', 'alert-danger');
                                return redirect()->to(base_url() . 'Project_Manage/PM_Proposals/proposal_details');
                            }
                        }
                    }
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url() . 'Project_Manage/PM_Proposals/proposal_details');
                }
            }
        }
    }
    public function delete_image_details()
    {
        $data = [];
        if ($this->request->getPost()) {
            $timestamp = time();
            $proposal_details_id =  $this->request->getVar('proposal_details_id');
            $image_name =  $this->request->getVar('image_name');
            $proposal_id =  $this->request->getVar('proposal_id');
            $dir = '';
            $dir = FCPATH . 'assets/assets/uploads/proposal_image/' . $proposal_id . '/' . $image_name;
            if (file_exists($dir)) {
                unlink($dir);
            }
            $newdata = [
                'last_updated_on' => $timestamp,
                'last_updated_by' => session()->get('id_user'),
                'status' => '0'
            ];

            $result = $this->PM_Proposals_model->delete_proposal_details($newdata, $proposal_details_id);

            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0011'));
                return redirect()->to(base_url() . 'Project_Manage/PM_Proposals/proposal_details');
            } else {
                session()->setFlashdata('message', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . 'Project_Manage/PM_Proposals/proposal_details');
            }
        }
    }
    function export_proposal_pdf()
    {
        $dompdf = new Dompdf();
        $data = [];
        if ($this->request->getPost('proposal_id')) {
            $data['proposal_id'] = $this->request->getPost('proposal_id');
            $data['proposalImage'] = $this->PM_Proposals_model->get_proposalimage_details($data['proposal_id'],4);
            $data['get_proposal_data'] = $this->PM_Proposals_model->get_proposal_data($data['proposal_id']);
            // print_r( $data['proposalImage']);
            // exit();
            $html = view('project_management/export_proposal_pdf_view', $data);
            $dompdf->loadHtml($html);

            // Set paper size and orientation
            $dompdf->setPaper('A4', 'landscape');

            $date_today = date('Y-m-d');
            $dompdf->render();
            $dompdf->stream($data['proposal_id'] . '_' . $date_today . '_' . $data['get_proposal_data'][0]['client'] . '_proposal_sheet.pdf', ['Attachment' => true]);
        } else {
            return redirect()->to(base_url() . 'Project_Manage/PM_pricing_sheet');
        }
    }
}
