<?php

namespace App\Controllers\Project_Manage;

use App\Controllers\BaseController;
use App\Models\Project_Manage\PM_PO_model;
use App\Models\Project_Manage\PM_ucn_model;
use App\Models\Project_Manage\PM_pricing_sheet_model;
use App\Models\Project_Manage\PM_Proposals_model;
use App\Models\Settings\Settings_model;

#[\AllowDynamicProperties]
class PM_purchase_order extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->is_session_available();
        $this->PM_ucn_model = new PM_ucn_model();
        $this->PM_PO_model = new PM_PO_model();
        $this->settings_model = new Settings_model();
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
            'link3_name' => 'Purchase Orders'
        ];
        $user = session()->get('id_user');
        $data['purchase_order_list'] = $this->PM_PO_model->get_purchase_order_list($user);
        echo view('templates/header_view', $data);
        // echo view('dashboard/projects_left_menu', $data);
        echo view('project_management/purchase_orders_dashboard_view', $data);
        echo view('templates/footer_view');
    }

    public function add_new_purchase_order()
    {
        $data = [];
        helper(['form']);

        if (isset($_POST['ucn_id'])) {
            $data['ucn_id'] = $_POST['ucn_id'];
        } else {
            $data['ucn_id'] = 0;
        }
        $data['get_pricing_sheet_data'] = $this->PM_pricing_sheet_model->get_pricing_sheet_data($data['ppid']);
        $data['clientlist'] = $this->PM_pricing_sheet_model->getclients_project_assignment();
        $data['salesuser'] =  $this->PM_pricing_sheet_model->getSalesuseraccess(68);
        $data['check_purchase_orders'] =  $this->PM_pricing_sheet_model->check_purchase_orders($data['ppid']);

        echo view('templates/header_view', $data);
        echo view('project_management/purchase_orders_add', $data);
        echo view('templates/footer_view');
    }
    public function add_purchase_order()
    {
        $data = [];
        helper(['form']);
        $data['post'] = '';
        $data['pr_id'] =  session()->get('partner_code');
        if (isset($_POST['ppid'])) {
            $data['ppid'] = $_POST['ppid'];
        } else {
            $data['ppid'] = 0;
        }
        $data['get_pricing_sheet_data'] = $this->PM_pricing_sheet_model->get_pricing_sheet_data($data['ppid']);
        $data['clientlist'] = $this->PM_pricing_sheet_model->getclients_project_assignment();
        $data['salesuser'] =  $this->PM_pricing_sheet_model->getSalesuseraccess(68);
        $data['check_purchase_orders'] =  $this->PM_pricing_sheet_model->check_purchase_orders($data['ppid']);

        echo view('templates/header_view', $data);
        echo view('project_management/purchase_orders_add', $data);
        echo view('templates/footer_view');
    }

    public function add_purchase_order_new()
    {
        $data = [];
        helper(['form']);
        $data['ucn_id'] = $_POST['ucn_id'];
        echo view('templates/header_view', $data);
        echo view('project_management/purchase_orders_add_new', $data);
        echo view('templates/footer_view');
    }


    public function edit_purchase_order()
    {
        $data = [];
        helper(['form']);
        $data['post'] = '';
        $data['pr_id'] =  session()->get('partner_code');

        $data['getclients'] = $this->PM_pricing_sheet_model->getclients_project_assignment();
        if (isset($_POST['po_id'])) {
            $data['po_id'] = $_POST['po_id'];
            $_SESSION['po_id'] = $data['po_id'];
        } else if (isset($_SESSION['po_id'])) {
            $data['po_id'] = $_SESSION['po_id'];
        } else {
            session()->setFlashdata('message', 'Error loading page.');
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'Project_Manage/PM_purchase_order');
        }
        if (isset($_POST['return_url'])) {
            $data['return_url'] = $_POST['return_url'];
            $_SESSION['return_url'] = $data['return_url'];
        } else if (isset($_SESSION['return_url'])) {
            $data['return_url'] = $_SESSION['return_url'];
        } else {
            $data['return_url'] = 1;
        }
        $data['project_manager']  = $this->PM_pricing_sheet_model->get_project_manager();
        $data['access']  = $this->PM_pricing_sheet_model->get_access_info($data['po_id'], 4);

        $data['po_details'] = $this->PM_PO_model->get_po_details($data['po_id']);
        $data['po_upload_details'] = $this->PM_PO_model->get_po_upload_details($data['po_id']);
        $data['salesuser'] =  $this->PM_pricing_sheet_model->getSalesuseraccess(68);
        $pricing_sheet_id = $data['po_details'][0]['project_id'];
        // $data['get_pricing_sheet_data'] = $this->PM_pricing_sheet_model->get_pricing_sheet_data($pricing_sheet_id);
        $data['ucn_details'] = $this->PM_PO_model->get_ucn_details($data['po_id']);
        $data['milestone_details'] = $this->PM_PO_model->get_milestone_details($data['po_id']);
        // print_r("tt");
        // exit();

        echo view('templates/header_view', $data);
        echo view('project_management/purchase_orders_edit', $data);
        echo view('templates/footer_view');
    }

    public function edit_milestone()
    {
        $data = [];
        helper(['form']);

        $data['milestone_id'] = $_POST['milestone_id'];
        $data['milestone_details'] = $this->PM_PO_model->get_milestone_edit_details($data['milestone_id']);

        echo view('templates/header_view', $data);
        echo view('project_management/purchase_orders_milestone_edit', $data);
        echo view('templates/footer_view');
    }

    public function update_milestones()
    {
        $data = [];
        helper(['form']);
        if ($this->request->getPost()) {
            $timestamp = time();
            $milestone_id = $this->request->getVar('milestone_id');
            $newdata = [
                'description' => $this->request->getVar('description'),
                'currency' => $this->request->getVar('currency'),
                'value' => $this->request->getVar('value'),
                'invoicing_dt' => $this->request->getVar('invoicing_dt'),
                'notes' => $this->request->getVar('notes'),
                'last_updated_on' => $timestamp,
                'last_updated_by' => session()->get('id_user')
            ];

            $result = $this->PM_PO_model->update_milestone_data($newdata, $milestone_id);

            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0008'));
                return redirect()->to(base_url() . 'Project_Manage/PM_purchase_order/edit_purchase_order');
            } else {
                session()->setFlashdata('message', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . 'Project_Manage/PM_purchase_order/edit_purchase_order');
            }
        }
    }
    public function po_details()
    {
        $data = [];
        helper(['form']);
        $data['pr_id'] =  session()->get('partner_code');

        if (isset($_POST['po_id'])) {
            $data['po_id'] = $_POST['po_id'];
            $_SESSION['po_id'] = $data['po_id'];
        } else if (isset($_SESSION['po_id'])) {
            $data['po_id'] = $_SESSION['po_id'];
        } else {
            session()->setFlashdata('message', 'Error loading page.');
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'Project_Manage/PM_purchase_order');
        }
        $data['ucn_details'] = $this->PM_PO_model->get_ucn_details($data['po_id']);
        $data['milestone_details'] = $this->PM_PO_model->get_milestone_details($data['po_id']);

        echo view('templates/header_view', $data);
        echo view('project_management/purchase_orders_milestone', $data);
        echo view('templates/footer_view');
    }

    public function po_ucn()
    {
        $data = [];
        helper(['form']);
        $data['pr_id'] =  session()->get('partner_code');
        $data['pricing_name'] = '';
        if (isset($_POST['po_id'])) {
            $data['po_id'] = $_POST['po_id'];
            $_SESSION['po_id'] = $data['po_id'];
            // $data['client'] = $_POST['client'];
            $data['projectclient'] = $_POST['projectclient'];
            $_SESSION['projectclient'] = $data['projectclient'];
            $data['pricing_name'] = $_POST['pricing_name'];
            $data['account_manager'] =  $_POST['account_manager'];
            $_SESSION['account_manager'] =  $data['account_manager'];
        } else if (isset($_SESSION['po_id'])) {
            $data['po_id'] = $_SESSION['po_id'];
            // $data['client'] = $_SESSION['client'];
            $data['projectclient'] = $_SESSION['projectclient'];
            $data['account_manager'] = $_SESSION['account_manager'];
        } else {
            session()->setFlashdata('message', 'Error loading page.');
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'Project_Manage/PM_purchase_order');
        }

        $data['pr_id'] =  session()->get('partner_code');
        $user = session()->get('id_user');
        $data['ucn_list'] = $this->PM_ucn_model->get_ucn_list($user);
        $data['ucn_details'] = $this->PM_PO_model->get_ucn_details($data['po_id']);

        echo view('templates/header_view', $data);
        echo view('project_management/purchase_orders_ucn', $data);
        echo view('templates/footer_view');
    }


    function link_ucn_to_project()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['po_id'])) {
            $data['po_id'] = $_POST['po_id'];
            $_SESSION['po_id'] = $data['po_id'];
        } else if (isset($_SESSION['po_id'])) {
            $data['po_id'] = $_SESSION['po_id'];
        } else {
            session()->setFlashdata('message', 'Error loading page.');
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'Project_Manage/PM_purchase_order');
        }
        $data['ucn'] = $_POST['ucn'];

        $newdata = [
            'ucn' =>  $data['ucn'],
            'type_of_link' => 2,
            'table_id' =>  $data['po_id'],
            'status' =>  1,
            'created_on' =>  time(),
            'last_updated_on' => time(),
            'last_updated_by' => session()->get('id_user')
        ];

        $this->PM_PO_model->add_ucn_link($newdata);
        session()->setFlashdata('success', lang('Messages.Success_0039'));
        session()->setFlashdata('alert-class', 'alert-danger');
        return redirect()->to(base_url() . 'Project_Manage/PM_purchase_order/edit_purchase_order');
    }
    function po_upload()
    {
        $data = [];
        helper(['form']);
        $data['po_id'] =  session()->get('po_id');

        if (isset($_POST['po_id'])) {
            $data['po_id'] = $_POST['po_id'];
            $_SESSION['po_id'] = $data['po_id'];
        } else if (isset($_SESSION['po_id'])) {
            $data['po_id'] = $_SESSION['po_id'];
        } else {
            session()->setFlashdata('message', 'Error loading page.');
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'Project_Manage/PM_purchase_order');
        }
        $data['pr_id'] =  session()->get('partner_code');
        $data['po_details'] = $this->PM_PO_model->get_po_upload_details($data['po_id']);
        echo view('templates/header_view', $data);
        echo view('project_management/purchase_orders_upload', $data);
        echo view('templates/footer_view');
    }
    function uploadpdf()
    {
        $data = [];
        helper(['filesystem']);
        if (isset($_POST['po_id'])) {
            $data['po_id'] = $_POST['po_id'];
            $_SESSION['po_id'] =  $data['po_id'];
        } else if (isset($_GET['po_id'])) {
            $data['po_id'] = $_GET['po_id'];
        } else if (isset($_SESSION['po_id'])) {
            $data['po_id'] = $_SESSION['po_id'];
        }
        if ($this->request->getPost()) {
            $rules = [
                'file' => 'uploaded[file]|ext_in[file,pdf,PDF]'
            ];
            if (!$this->validate($rules)) {
                $data['povalidation'] = $this->validator;
            } else {
                if ($file = $this->request->getFile('file')) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $filename = $file->getName();
                        if (!is_dir(FCPATH . 'assets/assets/uploads/po_pdf/')) {
                            mkdir('assets/assets/uploads/po_pdf/' . $data['po_id'] . '/', 0777, true);
                        }
                        // if (file_exists(FCPATH . 'assets/assets/uploads/po_pdf/' . $data['po_id'] . '/' . $filename)) {
                        //     $pdf = FCPATH . 'assets/assets/uploads/po_pdf/' . $data['po_id'] . '/' . $filename;
                        //     unlink($pdf);
                        // } else {

                        if ($file->move(FCPATH . 'assets/assets/uploads/po_pdf/' . $data['po_id'], $filename)) {
                            $newdata = [
                                'po_id' => $data['po_id'],
                                'po_upload' => $filename,
                                'status' => 1,
                                'createdby' => session()->get('id_user'),
                                'createdon' => time(),

                            ];
                            // print_r($newdata);
                            // exit();
                            $result = $this->PM_PO_model->update_po_upload($newdata);

                            if ($result) {
                                session()->setFlashdata('success', lang('Messages.Success_0009'));
                                session()->setFlashdata('alert-class', 'alert-danger');
                            } else {
                                session()->setFlashdata('error', lang('Messages.Error_0001'));
                                session()->setFlashdata('alert-class', 'alert-danger');
                            }
                        }
                        // }
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0001'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                    }
                }
            }
        }
        return redirect()->to(base_url() . 'Project_Manage/PM_purchase_order/po_upload');
    }
    function delpo_upload()
    {
        if (isset($_POST['fileloc'])) {
            $dirPath = $_POST['fileloc'];
            if (file_exists($dirPath)) {
                if (!unlink($dirPath)) {
                    session()->setFlashdata('error', 'Failed to delete file: ' . $dirPath);
                }
            } else {
                // session()->setFlashdata('error', 'File does not exist: ' . $fileloc);
                // unlink($dirPath);
            }
            // unlink($dirPath);
            $po_uid = $this->request->getVar('po_uid');
            $newdata = [


                'status' => 0,
            ];
            $result = $this->PM_PO_model->delpo_upload($newdata, $po_uid);
            return json_encode($result);
        }
    }
    function uploadimage()
    {
        $data = [];
        helper(['filesystem']);
        if (isset($_POST['po_id'])) {
            $data['po_id'] = $_POST['po_id'];
            $_SESSION['po_id'] =  $data['po_id'];
        } else if (isset($_GET['po_id'])) {
            $data['po_id'] = $_GET['po_id'];
        } else if (isset($_SESSION['po_id'])) {
            $data['po_id'] = $_SESSION['po_id'];
        }

        if ($this->request->getPost()) {
            $rules = [
                'file' => 'uploaded[file]|ext_in[file,jpg,png]'
            ];
            if (!$this->validate($rules)) {
                $data['povalidation'] = $this->validator;
            } else {
                if ($file = $this->request->getFile('file')) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $filename = $file->getName();
                        // print_r($filename);
                        // exit();
                        if (!is_dir(FCPATH . 'assets/assets/uploads/po_image/' . $data['po_id'])) {
                            mkdir('assets/assets/uploads/po_image/' . $data['po_id'], 0777, true);
                        }
                        if (file_exists(FCPATH . 'assets/assets/uploads/po_image/' . $data['po_id'] . "/" . $filename)) {
                            session()->setFlashdata('error', $filename . ' ' . lang('Messages.Success_0051'));
                        } else {
                            if ($file->move(FCPATH . 'assets/assets/uploads/po_image/' . $data['po_id'], $filename)) {
                                $filepath = FCPATH . 'assets/assets/uploads/po_image/' . $data['po_id'] . '/' . $filename;
                                $newdata = [
                                    'po_upload' => $filename,
                                ];
                                $result = $this->PM_PO_model->update_purchase_order($newdata, $data['po_id']);
                                if ($result) {
                                    session()->setFlashdata('po_id', $data['po_id']);
                                    session()->setFlashdata('success', lang('Messages.Success_0009'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                    return redirect()->to(base_url() . 'Project_Manage/PM_purchase_order/po_upload');
                                } else {
                                    session()->setFlashdata('po_id',  $data['po_id']);
                                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                    return redirect()->to(base_url() . 'Project_Manage/PM_purchase_order/po_upload');
                                }
                            }
                        }
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0001'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                        return redirect()->to(base_url() . 'User_login/client_list/client_edit_view');
                    }
                }
            }
        }
    }
    public function edit_ucn()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['po_id'])) {
            $data['po_id'] = $_POST['po_id'];
            $_SESSION['po_id'] = $data['po_id'];
        } else if (isset($_SESSION['po_id'])) {
            $data['po_id'] = $_SESSION['po_id'];
        } else {
            session()->setFlashdata('message', 'Error loading page.');
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'Project_Manage/PM_purchase_order');
        }
        $data['pr_id'] =  session()->get('partner_code');
        $data['ucn_id'] =  $this->request->getVar('ucn_id');

        $data['proposal_list'] = $this->PM_Proposals_model->get_proposal_list();
        $data['pricing_sheet_list'] = $this->PM_pricing_sheet_model->get_pricing_sheet_list();
        $data['clientlist'] = $this->settings_model->clientuserlist($data['pr_id']);
        $data['ucn_edit_details'] = $this->PM_PO_model->get_ucn_edit_details($data['ucn_id']);
        $data['salesuser'] =  $this->PM_pricing_sheet_model->getSalesuseraccess(68);
        $data['getclients'] = $this->PM_pricing_sheet_model->getclients_project_assignment();
        echo view('templates/header_view', $data);
        echo view('project_management/purchase_orders_ucn_edit', $data);
        echo view('templates/footer_view');
    }

    public function ucn_status_change()
    {
        $data = [];
        if ($this->request->getPost()) {
            $timestamp = time();
            $po_id = $this->request->getVar('po_id');
            $ucn_id = $this->request->getVar('ucn_id');
            $_SESSION['po_id'] =  $po_id;

            $newdata = [
                'status' => $this->request->getVar('status'),
                'last_updated_on' => $timestamp,
                'last_updated_by' => session()->get('id_user')
            ];

            $result = $this->PM_PO_model->update_ucn_data($newdata, $ucn_id);

            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0008'));
                return redirect()->to(base_url() . 'Project_Manage/PM_purchase_order/edit_purchase_order');
            } else {
                session()->setFlashdata('message', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . 'Project_Manage/PM_purchase_order/edit_purchase_order');
            }
        }
    }
    public function update_ucn()
    {
        $data = [];
        if ($this->request->getPost()) {
            $timestamp = time();
            $po_id = $this->request->getVar('po_id');
            $ucn_id = $this->request->getVar('ucn_id');
            $_SESSION['po_id'] =  $po_id;
            $newdata = [
                'name' => $this->request->getVar('name'),
                'start_dt' => $this->request->getVar('start_date'),
                'end_dt' => $this->request->getVar('end_date'),
                'remarks' => $this->request->getVar('remarks'),
                'last_updated_on' => $timestamp,
                'last_updated_by' => session()->get('id_user')
            ];

            $result = $this->PM_PO_model->update_ucn_data($newdata, $ucn_id);

            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0008'));
                return redirect()->to(base_url() . 'Project_Manage/PM_purchase_order/edit_purchase_order');
            } else {
                session()->setFlashdata('message', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . 'Project_Manage/PM_purchase_order/edit_purchase_order');
            }
        }
    }
    public function add_ucn()
    {
        $data = [];
        if ($this->request->getPost()) {
            $timestamp = time();
            $po_id = $this->request->getVar('po_id');
            $data['po_details'] = $this->PM_PO_model->get_po_details($po_id);
            $_SESSION['po_id'] =  $po_id;
            $pop = isset($percentage_po) ? $percentage_po : 0;
            $end_dt = $this->request->getVar('end_date');
            $end_date = isset($end_dt) ? $end_dt : '';
            $newdata = [
                'po_id' =>  $this->request->getVar('po_id'),
                'proposal_id' =>  $this->request->getVar('proposal_id'),
                'pricing_id' =>  $this->request->getVar('pricing_id'),
                'name' => $this->request->getVar('name'),
                'account_manager' => $this->request->getVar('account_manager'),
                'client' => $this->request->getVar('projectclient'),
                'type_of_project' => $this->request->getVar('type_of_project'),
                'percentage_po' =>  $pop,
                'start_dt' => $this->request->getVar('start_date'),
                'end_dt' => $end_date,
                'remarks' => $this->request->getVar('remarks'),
                'created_on' => $timestamp,
                'created_by' => session()->get('id_user'),
                'last_updated_on' => $timestamp,
                'last_updated_by' => session()->get('id_user'),
                'status' =>  1
            ];

            $result = $this->PM_PO_model->add_ucn($newdata, $po_id);

            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0011'));
                return redirect()->to(base_url() . 'Project_Manage/PM_purchase_order/edit_purchase_order');
            } else {
                session()->setFlashdata('message', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . 'Project_Manage/PM_purchase_order/edit_purchase_order');
            }
        }
    }
    public function add_milestones()
    {
        $data = [];
        if ($this->request->getPost()) {
            $timestamp = time();
            $po_id = $this->request->getVar('po_id');
            $_SESSION['po_id'] =  $po_id;
            $newdata = [
                'po_id' =>  $this->request->getVar('po_id'),
                'ucn_id' => $this->request->getVar('ucn_id'),
                'description' => $this->request->getVar('description'),
                'currency' => $this->request->getVar('currency'),
                'value' => $this->request->getVar('milestone_value'),
                'invoicing_dt' => $this->request->getVar('invoice_date'),
                'notes' => $this->request->getVar('notes'),
                'created_on' => $timestamp,
                'created_by' => session()->get('id_user'),
                'last_updated_on' => $timestamp,
                'last_updated_by' => session()->get('id_user'),
                'status' =>  1
            ];

            $result = $this->PM_PO_model->add_milestone($newdata);

            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0011'));
                return redirect()->to(base_url() . 'Project_Manage/PM_purchase_order/edit_purchase_order');
            } else {
                session()->setFlashdata('message', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . 'Project_Manage/PM_purchase_order/edit_purchase_order');
            }
        }
    }

    public function del_milestone()
    {
        $data = [];
        if ($this->request->getPost()) {
            $timestamp = time();
            $po_id = $this->request->getVar('po_id');
            $milestone_id = $this->request->getVar('milestone_id');
            $_SESSION['po_id'] =  $po_id;
            $newdata = [
                'last_updated_on' => $timestamp,
                'last_updated_by' => session()->get('id_user'),
                'status' =>  0
            ];

            $result = $this->PM_PO_model->update_milestone($newdata, $milestone_id);

            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0005'));
                return redirect()->to(base_url() . 'Project_Manage/PM_purchase_order/edit_purchase_order');
            } else {
                session()->setFlashdata('message', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . 'Project_Manage/PM_purchase_order/edit_purchase_order');
            }
        }
    }
    public function edit_purchase_order_submit()
    {
        $data = [];
        if ($this->request->getPost()) {
            $timestamp = time();
            $po_id = $this->request->getVar('po_id');
            $_SESSION['po_id'] =  $po_id;
            $newdata = [
                'description' => $this->request->getVar('pricing_name'),
                'client_id' => $this->request->getVar('client'),
                'account_manager' => $this->request->getVar('account_manager'),

                'currency' => $this->request->getVar('currency'),

                'po_value' => $this->request->getVar('po_value'),
                'po_number' =>  $this->request->getVar('po_number'),

                'project_value' => $this->request->getVar('project_value'),
                'po_status' => $this->request->getVar('po_status'),

                'remarks' => $this->request->getVar('remarks'),

                'last_updated_on' => $timestamp,
                'last_updated_by' => session()->get('id_user'),
                'status' =>  $this->request->getVar('status')
            ];

            $result = $this->PM_PO_model->update_purchase_order($newdata, $po_id);

            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0011'));
                return redirect()->to(base_url() . 'Project_Manage/PM_purchase_order/edit_purchase_order');
            } else {
                session()->setFlashdata('message', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . 'Project_Manage/PM_purchase_order/edit_purchase_order');
            }
        }
    }

    public function add_purchase_order_submit_new()
    {
        $data = [];
        $data['ucn_id'] = $_POST['ucn_id'];
        $UCNdetails = $this->PM_ucn_model->getUCNdetails($data['ucn_id']);
        if ($this->request->getPost()) {
            $timestamp = time();
            $newdata = [
                'description' => $this->request->getVar('pricing_name'),
                'client_id' => $UCNdetails[0]['client'],
                'ucn_id'  => $data['ucn_id'],
                'account_manager' => $UCNdetails[0]['account_manager'],
                'po_value' => $this->request->getVar('po_value'),
                'po_number' =>  $this->request->getVar('po_number'),
                'project_value' => $this->request->getVar('project_value'),
                'po_status' => $this->request->getVar('po_status'),
                'remarks' => $this->request->getVar('remarks'),
                'created_on' => $timestamp,
                'created_by' => session()->get('id_user'),
                'last_updated_on' => $timestamp,
                'last_updated_by' => session()->get('id_user'),
                'status' => '1'
            ];
            $return_id = $this->PM_PO_model->add_new_purchase_order($newdata);
            $newdata = [
                'ucn' => $data['ucn_id'],
                'type_of_link' => 2,
                'table_id' =>  $return_id,
                'created_on' => $timestamp,
                'last_updated_on' => $timestamp,
                'last_updated_by' => session()->get('id_user'),
                'status' => '1'
            ];
            $return_id = $this->PM_PO_model->add_ucn_link($newdata);
            session()->setFlashdata('success', lang('Messages.Success_0011'));
            return redirect()->to(base_url() . 'Project_Manage/PM_ucn/edit_ucn');
        }
    }
    public function add_purchase_order_submit()
    {
        $data = [];
        if ($this->request->getPost()) {
            $timestamp = time();

            $newdata = [
                'description' => $this->request->getVar('pricing_name'),
                'client_id' => $this->request->getVar('client'),
                'account_manager' => $this->request->getVar('account_manager'),
                'project_id' => $this->request->getVar('pricing_sheet_id'),
                'currency' => $this->request->getVar('currency'),
                'po_value' => $this->request->getVar('po_value'),
                'po_number' =>  $this->request->getVar('po_number'),
                'project_value' => $this->request->getVar('project_value'),
                'po_status' => $this->request->getVar('po_status'),
                'remarks' => $this->request->getVar('remarks'),
                'created_on' => $timestamp,
                'created_by' => session()->get('id_user'),
                'last_updated_on' => $timestamp,
                'last_updated_by' => session()->get('id_user'),
                'status' => '1'
            ];

            $result = $this->PM_PO_model->add_new_purchase_order($newdata);

            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0011'));
                return redirect()->to(base_url() . 'Project_Manage/PM_purchase_order');
            } else {
                session()->setFlashdata('message', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . 'Project_Manage/PM_purchase_order');
            }
        }
    }
}
