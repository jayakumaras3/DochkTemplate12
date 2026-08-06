<?php

namespace App\Controllers\Etrack;

use App\Controllers\BaseController;
use App\Models\Etrack\Employee_data_model;
use App\Models\Etrack\Leave_model;

#[\AllowDynamicProperties]
class ITSupport extends BaseController
{
    public function __construct()
    {
        $this->is_session_available();
        $this->Employee_data_model = new Employee_data_model();
        $this->Leave_model = new Leave_model();
    }

    private function is_session_available()
    {
        $client = session()->get('client');
        if ($client != 1) {
            header('Location:' . base_url('my_training'));
            exit();
        }
    }

    public function index() //fetch data from users table to display
    {
        $data = [];
        helper(['form']);
        $user = session()->get('id_user');

        $data['support_tickets'] = $this->Employee_data_model->get_my_tickets($user);
        $data['support_tickets_open'] = $this->Employee_data_model->get_my_tickets_num($user, 1);
        $data['support_tickets_closed'] = $this->Employee_data_model->get_my_tickets_num($user, 4);
        $data['support_tickets_reopen'] = $this->Employee_data_model->get_my_tickets_num($user, 3);

        $userlevel = session()->get('userlevel');
        $arrayuserlevel = array_map('intval', explode(',', $userlevel) ?? '');
        if (in_array('4154', $arrayuserlevel)) {
            $data['get_open_tickerts'] = $this->Employee_data_model->get_open_tickerts();
        }

        echo view('templates/header_view', $data);
        echo view('etrack/IT/ITSupport_view', $data);
        echo view('templates/footer_view');
    }

    public function support_admin()
    {
        $data = [];
        helper(['form']);
        $userlevel = session('userlevel');
        $arrayuserlevel = array_map('intval', explode(',', $userlevel) ?? '');
        if (in_array('4154', $arrayuserlevel)) {
            $data['get_open_tickerts'] = $this->Employee_data_model->get_open_tickerts();
            echo view('templates/header_view', $data);
            echo view('etrack/IT/ITSupport_admin_view', $data);
            echo view('templates/footer_view');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            return redirect()->to(base_url() . 'etrack/ITSupport');

        }
    }

    public function in_progress_tickets()
    {
        $data = [];
        helper(['form']);
        $data['get_not_closed_tickerts'] = $this->Employee_data_model->get_not_closed_tickerts();
        echo view('templates/header_view', $data);
        echo view('etrack/IT/ITSupport_admin_open_view', $data);
        echo view('templates/footer_view');
    }

    public function closed_tickets()
    {
        $data = [];
        helper(['form']);
        $data['start_date'] = time();
        $data['end_date'] = strtotime('-20 days');
        $data['get_closed_tickerts'] = $this->Employee_data_model->get_closed_tickerts($data['start_date'], $data['end_date']);
        echo view('templates/header_view', $data);
        echo view('etrack/IT/ITSupport_admin_closed_view', $data);
        echo view('templates/footer_view');
    }

    public function view_ticket_details() //fetch data from users table to display
    {
        $data = [];
        helper(['form']);
        $data['user'] = session()->get('id_user');
        if (isset($_POST['et_sup_id'])) {
            $data['et_sup_id'] = $_POST['et_sup_id'];
            $_SESSION['et_sup_id'] = $_POST['et_sup_id'];
        } elseif (isset($_SESSION['et_sup_id'])) {
            $data['et_sup_id'] = $_SESSION['et_sup_id'];
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->to(base_url() . 'etrack/ITSupport');
        }
        $data['support_ticket_details'] = $this->Employee_data_model->get_ticket_details($data['et_sup_id']);
        $data['support_ticket_reply'] = $this->Employee_data_model->get_ticket_reply_details($data['et_sup_id']);

        echo view('templates/header_view');
        echo view('etrack/IT/ITSupport_detail_view', $data);
        echo view('templates/footer_view');
    }
    public function add_ticket()
    {
        date_default_timezone_set("Asia/Kolkata");
        $newdata = [
            'id_user' => session()->get('id_user'),
            'location' => $_POST['location'],
            'priority' => $_POST['priority'],
            'short_desc' => $_POST['short_description'],
            'detail_desc' => $_POST['long_description'],
            'last_updated_by' => session()->get('id_user'),
            'created_on' => time(),
            'last_updated_on' => time(),
            'status' => 1
        ];
        $result = $this->Employee_data_model->add_ticket($newdata);
        if (!empty($result)) {
            if ($_POST['priority'] == 1) {
                $priority = 'Low';
            } elseif ($_POST['priority'] == 2) {
                $priority = 'Medium';
            } elseif ($_POST['priority'] == 3) {
                $priority = 'High';
            }
            $to = 'arun.p@TouchstoneLC.com';
            // $to = 'srividya.a@TouchstoneLC.com';
            // $to = 'keerthana.mk@TouchstoneLC.com';
            $subject = 'IT Ticket Request';
            $message = 'Hi,<br><br>Location: ' . $_POST['short_description'] . '<br/>Short Description: ' . $_POST['short_description'] . '<br>Details: ' . $_POST['long_description'] . '<br>Priority: ' . $priority . '<br>Issue rasied By: ' . session()->get('name') . '<br><br>'
                . 'Regards,<br>'
                . 'IT Support';
            $email = \Config\Services::email();
            $email->setFrom('do-not-reply@touchstonelc.com', 'Dochek');
            $email->setTo($to);
            // $email->setCC('another@another-example.com');
            //$email->setBCC('them@their-example.com');

            $email->setSubject($subject);
            $email->setMessage($message);

            if ($email->send()) {
                session()->setFlashdata('success', lang('Messages.Success_0011'));
            } else {
                $data = $email->printDebugger(['headers']);
                print_r($data);
                exit();
            }
            session()->setFlashdata('success', lang('Messages.Success_0011'));
            return redirect()->to(base_url() . 'etrack/ITSupport');
        } else {
            session()->setFlashdata('success', lang('Messages.Success_0011'));
            return redirect()->to(base_url() . 'etrack/ITSupport');
        }
    }

    public function reply_ticket()
    {
        date_default_timezone_set("Asia/Kolkata");
        $et_sup_id = $_POST['et_sup_id'];
        $newdata = [
            'assigned_to' => $_POST['assigned_to'],
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => $_POST['status']
        ];
        $this->Employee_data_model->update_ticket($newdata, $et_sup_id);

        $newdata = [
            'et_sup_id' => $et_sup_id,
            'reply' => $_POST['long_description'],
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => 1
        ];
        $this->Employee_data_model->add_reply_ticket($newdata);

        session()->setFlashdata('success', lang('Messages.Success_0011'));
        $userlevel = session()->get('userlevel');
        $arrayuserlevel = array_map('intval', explode(',', $userlevel) ?? '');
        if (in_array('4154', $arrayuserlevel)) {
            return redirect()->to(base_url() . 'etrack/ITSupport/in_progress_tickets');
        } else {
            return redirect()->to(base_url() . 'etrack/ITSupport');
        }
    }

    public function assets() //fetch data from users table to display
    {
        $data = [];
        helper(['form']);
        $client = session()->get('client');
        $data['all_users'] = $this->Leave_model->getUsersDetails($client);
        $data['all_assets'] = $this->Employee_data_model->get_all_assets();
        echo view('templates/header_view');
        echo view('etrack/IT/ITSupport_assets_view', $data);
        echo view('templates/footer_view');
    }

    public function view_employee_assets()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['user_select'])) {
            $data['user_select'] = $_POST['user_select'];
            $_SESSION['user_select'] = $_POST['user_select'];
        } elseif (isset($_SESSION['user_select'])) {
            $data['user_select'] = $_SESSION['user_select'];
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->to(base_url() . 'etrack/ITSupport/assets');
        }

        $user = $_POST['user_select'];
        $data['get_user_assets'] = $this->Employee_data_model->get_user_assets($user);
        $data['get_user_software'] = $this->Employee_data_model->get_user_software($user);
        //print_r($user);
        //  exit();
        echo view('templates/header_view');
        echo view('etrack/IT/ITSupport_assets_user_view', $data);
        echo view('templates/footer_view');
    }
    public function view_assets()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['assetid'])) {
            $data['assetid'] = $_POST['assetid'];
            $_SESSION['assetid'] = $_POST['assetid'];
        } elseif (isset($_SESSION['assetid'])) {
            $data['assetid'] = $_SESSION['assetid'];
        }
        $data['asset_desc'] = $this->Employee_data_model->get_asset_desc($data['assetid']);
        $data['asset_details'] = $this->Employee_data_model->get_asset_details($data['assetid']);
        echo view('templates/header_view');
        echo view('etrack/IT/ITSupport_assets_detail_view', $data);
        echo view('templates/footer_view');
    }


    public function edit_assets()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['et_ass_det_id'])) {
            $data['et_ass_det_id'] = $_POST['et_ass_det_id'];
            $_SESSION['et_ass_det_id'] = $_POST['et_ass_det_id'];
        } elseif (isset($_SESSION['et_ass_det_id'])) {
            $data['et_ass_det_id'] = $_SESSION['et_ass_det_id'];
        }
        $client = session()->get('client');
        $data['all_users'] = $this->Leave_model->getUsersDetails($client);
        $data['get_asset_details_edit'] = $this->Employee_data_model->get_asset_details_edit($data['et_ass_det_id']);
        $data['get_asset_history'] = $this->Employee_data_model->get_asset_history($data['et_ass_det_id']);
        echo view('templates/header_view');
        echo view('etrack/IT/ITSupport_assets_edit_view', $data);
        echo view('templates/footer_view');
    }

    public function edit_assets_history()
    {
        $data = [];
        helper(['form']);
        $data['et_assets_assign_id'] = $_POST['et_assets_assign_id'];
        $data['get_history_byID'] = $this->Employee_data_model->get_history_byID($data['et_assets_assign_id']);
        echo view('templates/header_view');
        echo view('etrack/IT/ITSupport_assets_his_edit_view', $data);
        echo view('templates/footer_view');
    }

    public function update_user_assign_asset()
    {
        $et_assets_assign_id = $_POST['et_assets_assign_id'];
        $newdata = [
            'assigned_on' => $_POST['assigned_on'],
            'returned_on' => $_POST['returned_on'],
            'expected_return_on' => $_POST['expected_return'],
            'remarks' => $_POST['remarks'],
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time()
        ];
        $this->Employee_data_model->update_asset_his($newdata, $et_assets_assign_id);
        session()->setFlashdata('success', lang('Messages.Success_0008'));
        return redirect()->to(base_url() . 'etrack/ITSupport/edit_assets');
    }
    public function update_asset_details()
    {
        date_default_timezone_set("Asia/Kolkata");
        $et_ass_det_id = $_POST['et_ass_det_id'];
        $newdata = [
            'description' => $_POST['description'],
            'fin_identifier' => $_POST['identifier'],
            'department' => $_POST['department'],
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time()
        ];
        $this->Employee_data_model->update_asset($newdata, $et_ass_det_id);
        session()->setFlashdata('success', lang('Messages.Success_0011'));
        return redirect()->to(base_url() . 'etrack/ITSupport/view_assets');
    }

    public function add_asset()
    {
        date_default_timezone_set("Asia/Kolkata");
        $newdata = [
            'description' => $_POST['assets'],
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => 1
        ];
        $this->Employee_data_model->add_asset($newdata);
        session()->setFlashdata('success', lang('Messages.Success_0011'));
        return redirect()->to(base_url() . 'etrack/ITSupport/assets');
    }

    public function add_asset_details()
    {
        date_default_timezone_set("Asia/Kolkata");
        $newdata = [
            'asset_id' => $_POST['asset_id'],
            'description' => $_POST['description'],
            'fin_identifier' => $_POST['identifier'],
            'department' => $_POST['department'],
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => 1
        ];
        $this->Employee_data_model->add_asset_details_mo($newdata);
        session()->setFlashdata('success', lang('Messages.Success_0011'));
        return redirect()->to(base_url() . 'etrack/ITSupport/view_assets');
    }

    public function assign_user_to_asset()
    {
        date_default_timezone_set("Asia/Kolkata");
        $newdata = [
            'assigned_to' => $_POST['user_select'],
            'assigned_on' => $_POST['assigned_on'],
            'asset_detail_id' => $_POST['et_ass_det_id'],
            'expected_return_on' => $_POST['expected_return'],
            'remarks' => isset($_POST['remarks']) ? $_POST['remarks'] : '',
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => 1
        ];
        $this->Employee_data_model->assign_asset_user($newdata);
        session()->setFlashdata('success', lang('Messages.Success_0018'));
        return redirect()->to(base_url() . 'etrack/ITSupport/edit_assets');
    }


    public function softwares() //fetch data from users table to display
    {
        $data = [];
        helper(['form']);
        $client = session()->get('client');
        $data['all_users'] = $this->Leave_model->getUsersDetails($client);
        $data['all_softwares'] = $this->Employee_data_model->get_all_softwares();
        echo view('templates/header_view');
        echo view('etrack/IT/ITSupport_software_view', $data);
        echo view('templates/footer_view');
    }

    public function add_software()
    {
        $newdata = [
            'soft_description' => $_POST['software'],
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => 1
        ];
        $this->Employee_data_model->add_new_software($newdata);
        session()->setFlashdata('success', lang('Messages.Success_0011'));
        return redirect()->to(base_url() . 'etrack/ITSupport/softwares');
    }
    public function delete_softwares()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['soft_id'])) {
            $data['soft_id'] = $_POST['soft_id'];
            $_SESSION['soft_id'] = $_POST['soft_id'];
        } elseif (isset($_SESSION['soft_id'])) {
            $data['soft_id'] = $_SESSION['soft_id'];
        }
        $newdata = [
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => $_POST['status'],
        ];
        $this->Employee_data_model->delete_softwares($newdata, $data['soft_id']);
        session()->setFlashdata('success', lang('Messages.Success_0005'));
        return redirect()->to(base_url() . 'etrack/ITSupport/softwares');
    }
    public function view_softwares() //fetch data from users table to display
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['soft_id'])) {
            $data['soft_id'] = $_POST['soft_id'];
            $_SESSION['soft_id'] = $_POST['soft_id'];
        } elseif (isset($_SESSION['soft_id'])) {
            $data['soft_id'] = $_SESSION['soft_id'];
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->to(base_url() . 'etrack/ITSupport/softwares');
        }
        $data['software_name'] = $this->Employee_data_model->get_softwareName($data['soft_id']);
        $data['software_by_id'] = $this->Employee_data_model->get_software_byID($data['soft_id']);
        echo view('templates/header_view');
        echo view('etrack/IT/ITSupport_software_detail_view', $data);
        echo view('templates/footer_view');
    }

    public function add_software_detail()
    {
        $newdata = [
            'soft_id' => $_POST['sofid'],
            'num_license' => $_POST['license'],
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => 1
        ];
        $this->Employee_data_model->add_new_software_details($newdata);
        session()->setFlashdata('success', lang('Messages.Success_0011'));
        return redirect()->to(base_url() . 'etrack/ITSupport/softwares');
    }



    public function edit_software_details()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['soft_detail_id'])) {
            $data['soft_detail_id'] = $_POST['soft_detail_id'];
            $_SESSION['soft_detail_id'] = $data['soft_detail_id'];
        } elseif (isset($_SESSION['soft_detail_id'])) {
            $data['soft_detail_id'] = $_SESSION['soft_detail_id'];
        }

        $client = session()->get('client');
        $data['all_users'] = $this->Leave_model->getUsersDetails($client);
        $data['software_user_assigned'] = $this->Employee_data_model->get_software_user_assigned($data['soft_detail_id']);
        $data['software_details_edit'] = $this->Employee_data_model->get_software_details_edit($data['soft_detail_id']);
        echo view('templates/header_view');
        echo view('etrack/IT/ITSupport_software_assign_view', $data);
        echo view('templates/footer_view');
    }

    public function assign_license()
    {
        $newdata = [
            'id_user' => $_POST['user_select'],
            'remarks' => $_POST['remarks'],
            'soft_detail_id' => $_POST['soft_detail_id'],
            'assigned_on' => time(),
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => 1
        ];
        $this->Employee_data_model->assign_software_license($newdata);
        session()->setFlashdata('success', lang('Messages.Success_0018'));
        return redirect()->to(base_url() . 'etrack/ITSupport/edit_software_details');
    }

    public function delete_software()
    {
        $sf_assign_id = $_POST['sf_assign_id'];
        $newdata = [
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => 0
        ];
        $this->Employee_data_model->delete_software_license($newdata, $sf_assign_id);
        session()->setFlashdata('success', lang('Messages.Success_0005'));
        return redirect()->to(base_url() . 'etrack/ITSupport/view_softwares');
    }

    public function update_software_details()
    {
        $soft_detail_id = $_POST['soft_detail_id'];
        $newdata = [
            'num_license' => $_POST['license'],
            'start_date' => $_POST['start_date'],
            'end_date	' => $_POST['end_date'],
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time()
        ];
        $this->Employee_data_model->update_software($newdata, $soft_detail_id);
        session()->setFlashdata('success', lang('Messages.Success_0008'));
        return redirect()->to(base_url() . 'etrack/ITSupport/view_softwares');
    }
}
