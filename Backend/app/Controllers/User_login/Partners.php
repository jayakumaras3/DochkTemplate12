<?php

namespace App\Controllers\User_login;

use App\Controllers\BaseController;
use App\Models\Settings\Dropdown_model;
use App\Models\User_login\Partners_model;
use App\Models\User_login\Login_model;
use App\Models\Settings\Settings_model;
use App\Models\User_login\Users_model;
use App\Models\Project\Pricing_model;

#[\AllowDynamicProperties]
class Partners extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->is_session_available();
        $this->dropdown_model = new Dropdown_model();
        $this->partners_model = new Partners_model();
        $this->login_model = new Login_model();
        $this->settings_model = new Settings_model();
        $this->users_model = new Users_model();
        $this->pricing_model = new Pricing_model();
    }
    private function is_session_available()
    {
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url('my_training'));
            exit();
        }

        $arrayuserlevel = explode(',', $userlevel);
        if (!in_array('6', $arrayuserlevel)) {
            session()->setFlashdata('message', 'You do not have access to view client list');
            header('Location:' . base_url('my_training'));
            exit();
        }
    }
    public function index() //fetch data from projects and project_details table to display
    {
        if ($response =  $this->requireRole(['6'])) {
            return $response;
        }

        $data = [];
        helper(['form']);
        // print_r('tt');
        // exit();
        if (isset($_POST['client'])) {
            $data['client'] = $_POST['client'];
        } else if (isset($_GET['client'])) {
            $data['client'] = $_GET['client'];
        } else {
            $data['client'] = session()->get('client');
        }
        $data['header'] = 'Add New Partner';

        $data['sub_header_link'] = 'User_login/partners/partner_list';
        $data['sub_header'] = 'Partners';
        $data['create_new_partners_link'] = 'User_login/partners/partner_add_view';
        $data['settings_link'] = 'SCORM/scorm_courses/course_settings_view';
        $data['edit_link'] = 'User_login/partners/partners_edit_view';
        $data['form_link'] = 'User_login/partners/addpartner';
        echo view('templates/header_view', $data);
        echo view('partners/partners_view', $data);
        echo view('templates/footer_view');
    }
    public function partner_list()
    {
        if ($response =  $this->requireRole(['6'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $userlevel = session('userlevel');
        $arrayuserlevel = array_map('intval', explode(',', $userlevel) ?? '');
        if (in_array('6', $arrayuserlevel)) {
            $data = [
                'link1' => '',
                'link1_name' => '',
                'link2' => '',
                'link2_name' => '',
                'link3_name' => 'User_login/Partners'
            ];
            $data['header'] = 'User_login/Partners';
            $data['add'] = 'Add New Partner';
            $data['edit_link'] = 'User_login/partners/partners_edit_view';
            $data['delete_link'] = 'User_login/partners/deletepartnerlist';
            $data['partnerlist'] = $this->partners_model->partneruserlist();
            echo view('templates/header_view', $data);
            //echo view('settings/admin_left_menu', $data);
            echo view('partners/partner_list_view', $data);
            echo view('templates/footer_view');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            return redirect()->to(base_url() . 'partners');
        }
    }
    public function partner_add_view()
    {
        if ($response =  $this->requireRole(['6'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data['header'] = 'Partner';
        $data['header_link'] = 'User_login/Partners';
        $data['sub_header_1'] = 'Create New Partner';
        $data['form_link'] = 'User_login/partners/addpartner';
        $data['typeval'] = 2;
        $data['priceDetails'] = $this->pricing_model->getPriceDetails();
        echo view('templates/header_view', $data);
        echo view('partners/partners_add_view', $data);
        echo view('templates/footer_view');
    }

    function addpartner()
    {
        if ($response =  $this->requireRole(['6'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $user = session()->get('username');

        if ($this->request->getPost()) {
            //print_r("sss");
            $rules = [
                'partner_name' => 'required',
            ];

            if (!$this->validate($rules)) {
                $data['coursevalidation'] = $this->validator;
            } else {
                $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
                $Partners = array();
                $alpha_length = strlen($alphabet) - 1;
                for ($i = 0; $i < 6; $i++) {
                    $n = rand(0, $alpha_length);
                    $Partners[] = $alphabet[$n];
                }

                $data['code'] = implode($Partners);
                $data['cid'] = $this->request->getVar('cid');


                $newdata = [
                    'partner_name' => $this->request->getVar('partner_name'),
                    'location' => $this->request->getVar('location'),
                    'company' => $this->request->getVar('company'),
                    'email_id' => $this->request->getVar('email_id'),
                    'contact' => $this->request->getVar('contact'),
                    'status' => '1',
                    'createdby' => session()->get('id_user'),
                    'createdon' => time(),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                $result = $this->partners_model->addPartner($newdata);
                if ($result) {
                    session()->setFlashdata('success', lang('Messages.Success_0011'));
                } else {
                    session()->setFlashdata('message', lang('Messages.Error_0001'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
            return redirect()->to(base_url() . 'User_login/partners/partner_list');
        }
    }
    public function partners_edit_view()
    {
        if ($response =  $this->requireRole(['6'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data['header'] = 'User_login/Partners';
        $data['header_link'] = 'User_login/partners/partner_list';
        $data['sub_header_1'] = 'Edit Partner';
        $data['form_link'] = 'User_login/partners/editpartner';
        if (isset($_POST['pr_id'])) {
            $data['pr_id'] = $_POST['pr_id'];
            $_SESSION['pr_id'] = $data['pr_id'];
        } else if (isset($_GET['pr_id'])) {
            $data['pr_id'] = $_GET['pr_id'];
        } else if (isset($_SESSION['pr_id'])) {
            $data['pr_id'] = $_SESSION['pr_id'];
        } else {
            return redirect()->to(base_url() . 'User_login/Partners');
        }
        $getPartnerData = $this->partners_model->getPartnersDetails($data['pr_id']);
        $data['row'] = $getPartnerData[0];
        // print_r($data['row']);
        // exit();
        echo view('templates/header_view', $data);
        echo view('partners/partners_edit_view', $data);
        echo view('templates/footer_view');
    }

    function editpartner()
    {
        if ($response =  $this->requireRole(['6'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $pr_id = $this->request->getVar('pr_id');
        $data['pr_id'] = $pr_id;
        if ($this->request->getPost()) {
            $rules = [
                'partner_name' => 'required',

            ];
            if (!$this->validate($rules)) {

                $data['courseeditvalidation'] = $this->validator;
            } else {
                $data['row'] = $this->partners_model->getPartnersDetails($data['pr_id']);
                $newdata = [
                    'partner_name' => $this->request->getVar('partner_name'),
                    'location' => $this->request->getVar('location'),
                    'company' => $this->request->getVar('company'),
                    'email_id' => $this->request->getVar('email_id'),
                    'contact' => $this->request->getVar('contact'),
                    'status' => '1',
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                // print_r($newdata);
                // exit();
                $result = $this->partners_model->editpartnerdetails($newdata, $pr_id);
                if ($result) {
                    session()->setFlashdata('success', lang('Messages.Success_0008'));
                } else {
                    session()->setFlashdata('message', lang('Messages.Error_0001'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
            return redirect()->to(base_url() . 'User_login/partners/partners_edit_view');
        }
    }
    public function partners_list() //fetch data from users table to display
    {
        if ($response =  $this->requireRole(['6'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $clientid = 20;
        $partner_code = session()->get('partner_code');
        // print_r($partner_code);
        // exit();
        $data['usertable'] = $this->login_model->userspartner_view($clientid, $partner_code);
        $data['clientlicense'] = $this->settings_model->clientlicensedata($clientid);
        echo view('templates/header_view', $data);
        echo view('profile/partner_view', $data);
        echo view('templates/footer_view');
    }
    public function uploadPartnerlogo()
    {
        if ($response =  $this->requireRole(['6'])) {
            return $response;
        }
        $data = [];
        helper(['filesystem']);
        if (isset($_POST['pr_id'])) {
            $data['pr_id'] = $_POST['pr_id'];
            $_SESSION['pr_id'] = $data['pr_id'];
        } else if (isset($_GET['pr_id'])) {
            $data['pr_id'] = $_GET['pr_id'];
        } else if (isset($_SESSION['pr_id'])) {
            $data['pr_id'] = $_SESSION['pr_id'];
        }

        if ($this->request->getPost()) {
            $rules = [
                'file' => 'uploaded[file]|ext_in[file,jpg,png]'
            ];
            if (!$this->validate($rules)) {
                $data['logovalidation'] = $this->validator;
            } else {
                if ($file = $this->request->getFile('file')) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $filename = $file->getName();
                        // <?php echo base_url() . '/public/aristo_public/images/partners/
                        if (!is_dir(FCPATH . 'public/aristo_public/images/partners/' . $data['pr_id'])) {
                            mkdir('public/aristo_public/images/partners/' . $data['pr_id'], 0777, true);
                        }
                        if (file_exists(FCPATH . 'public/aristo_public/images/partners/' . $data['pr_id'] . "/" . $filename)) {
                            session()->setFlashdata('error', $filename . ' ' . lang('Messages.Success_0051'));
                        } else {
                            if ($file->move(FCPATH . 'public/aristo_public/images/partners/' . $data['pr_id'], $filename)) {
                                $filepath = FCPATH . 'public/aristo_public/images/partners/' . $data['pr_id'] . '/' . $filename;
                                $newdata = [
                                    'logo' => $filename,
                                ];
                                $result = $this->partners_model->editImagepartner($newdata, $data['pr_id']);
                                if ($result) {
                                    session()->setFlashdata('success', lang('Messages.Success_0009'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                    return redirect()->to(base_url() . 'User_login/partners/partners_edit_view');
                                } else {
                                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                    return redirect()->to(base_url() . 'User_login/partners/partners_edit_view');
                                }
                            }
                        }
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0001'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                        return redirect()->to(base_url() . 'User_login/partners/partners_edit_view?pr_id=' . $data['pr_id']);
                    }
                }
            }
        }
    }
    function deletePartnerlist()
    {
        if ($response =  $this->requireRole(['6'])) {
            return $response;
        }
        $pr_id = $_POST['pr_id'];
        $newdata = ['pr_id' => $pr_id, 'status' => '0'  ];
        $result = $this->partners_model->editpartnerdetails($newdata, $pr_id);
        if ($result) {
            $sessionData = session();
            $sessionData->setFlashdata('success', 'Deleted Successful');
            return redirect()->to(base_url() . 'User_login/partners/partner_list')->with('success', 'Deleted Successful');
        }
        session()->setFlashdata('error', lang('Messages.Error_0001'));
        session()->setFlashdata('alert-class', 'alert-danger');
        return redirect()->to(base_url() . 'User_login/partners/partner_list');
    }
}
