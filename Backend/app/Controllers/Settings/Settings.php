<?php

namespace App\Controllers\Settings;

use App\Controllers\BaseController;

use App\Models\Settings\Settings_model;
use App\Models\Settings\Dropdown_model;
use App\Models\User_login\Users_model;
use App\Models\SCORM\Scorm_course_model;

#[\AllowDynamicProperties]
class Settings extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->settings_model = new Settings_model();
        $this->dropdown_model = new Dropdown_model();
        $this->users_model = new Users_model();
        $this->scorm_course_model = new Scorm_course_model();
    }
    public function index()
    {
        if ($response =  $this->requireRole(['6'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data['cid'] = $this->request->getVar('cid');
        $data['menu_view'] = $this->settings_model->menu_view($data['cid']);
        $data['default_dashboard'] = $this->settings_model->clientlicensedata($data['cid']);
        $data['moduleaccess'] = $this->dropdown_model->getdropdownData(17);
        echo view('templates/header_view', $data);
        echo view('settings/settings_view', $data);
        echo view('templates/footer_view');
    }
    public function clientaccesspermission()
    {
        if ($response =  $this->requireRole(['6'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $client_permission = $_POST['client_permission'];
        $client_id = $_POST['client_id'];
        $menu_id = $_POST['menu_id'];
        if (isset($_POST['client_id']) && isset($_POST['menu_id']) && isset($_POST['client_permission'])) {
            $data['client_id'] = $_POST['client_id'];
            $_SESSION['client_id'] =  $data['client_id'];
            $data['menu_id'] = $_POST['menu_id'];
            $_SESSION['menu_id'] =  $data['menu_id'];
            $data['client_permission'] = $_POST['client_permission'];
            $_SESSION['client_permission'] =  $data['client_permission'];
        }
        $newdata = [
            'menu_id' => $menu_id,
            'client_id' => $client_id,
            'client_permission' => $client_permission,
            'status' => 1,
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->settings_model->insertClientPermission($newdata);
        echo json_encode($result);
    }
    function defaultDashbaordControl()
    {
        if ($response =  $this->requireRole(['6'])) {
            return $response;
        }
        if (isset($_POST['icheck'])) {
            $data['icheck'] = $_POST['icheck'];
            $_SESSION['icheck'] =  $data['icheck'];
        }
        $data['client_id'] = $_POST['client_id'];
        $data['moduleaccess'] = $this->dropdown_model->getdropdownData(17);
        $result = $this->settings_model->givedefaultdashboardaccess($data['icheck'], $data['client_id']);
        if ($result) {
            $session = session();
            $session->setFlashdata('success', lang('Messages.Success_0008'));
            return redirect()->to(base_url() . '/Settings?cid=' . $data['client_id']);
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . '/Settings?cid=' . $data['client_id']);
        }
    }
    // function clientImpersonate()
    // {
    //     if ($response =  $this->requireRole(['6', '4'])) {
    //         return $response;
    //     }
    //     if (isset($_GET['cid'])) {
    //         $_SESSION['client'] = $_GET['cid'];
    //         $menu = $this->settings_model->menu_view($_SESSION['client']);
    //         $user['accessmenu']   = $menu[0]['accessmenu'];
    //         $_SESSION['accessmenu'] =  $user['accessmenu'];
    //         if ($_GET['cid'] != 1) {
    //             $_SESSION['super_admin_imper'] = 'imper';
    //         } else {
    //             $_SESSION['super_admin_imper'] = 'no';
    //         }

    //         return redirect()->to(base_url('my_training'));
    //     }
    // }
    public function clientImpersonate()
    {
        if ($response = $this->requireRole(['6', '4'])) {
            return $response;
        }

        $session = session();

        $cid = $this->request->getGet('cid');

        if (!$cid) {
            return redirect()->back();
        }

        // Save original client once
        if (!$session->get('original_client')) {
            $session->set('original_client', $session->get('client'));
        }

        $session->set('client', $cid);

        $menu = $this->settings_model->menu_view($cid);

        $session->set('accessmenu', $menu[0]['accessmenu'] ?? '');

        $session->set(
            'super_admin_imper',
            ($cid != 1 ? 'imper' : 'no')
        );
        return redirect()->to(base_url('my_training'));
    }
    // function userImpersonate()
    // {
    //     if ($response =  $this->requireRole(['6', '4'])) {
    //         return $response;
    //     }
    //     if (isset($_POST['id_user'])) {
    //         if (isset($_SESSION['super_user_imper'])) {
    //             if ($_SESSION['super_user_imper'] == 'user_imper') {
    //                 $_SESSION['id_user'] = $_SESSION['org_id_user'];
    //                 unset($_SESSION['super_user_imper']);
    //             } else {
    //                 $_SESSION['org_id_user'] = session()->get('id_user');
    //                 $_SESSION['super_user_imper'] = 'user_imper';
    //                 $_SESSION['id_user'] = $_GET['id_user'];
    //             }
    //         } else {
    //             $_SESSION['org_id_user'] = session()->get('id_user');
    //             $_SESSION['super_user_imper'] = 'user_imper';
    //             $_SESSION['id_user'] = $_POST['id_user'];
    //         }

    //         $_SESSION['isLoggedIn'] = true;
    //         $userdata = $this->users_model->usersedit_view($_SESSION['id_user']);
    //         $_SESSION['session_version'] = (int)($userdata[0]['session_version'] ?? 1);
    //         $_SESSION['username'] = $userdata[0]['user'];
    //         $_SESSION['profile_foldername'] = $userdata[0]['profile_foldername'];
    //         $_SESSION['profile_image'] = $userdata[0]['profile_image'];
    //         $_SESSION['name'] = $userdata[0]['name'];
    //         $_SESSION['client'] = $userdata[0]['id_c'];
    //         $menu = $this->settings_model->menu_view($_SESSION['client']);
    //         $user['accessmenu']   = $menu[0]['accessmenu'];
    //         $_SESSION['accessmenu'] =  $user['accessmenu'];
    //         $marketplaceaccess = $this->settings_model->marketplaceaccess($_SESSION['client']);
    //         if (!empty($marketplaceaccess)) {
    //             $user['marketplaceaccess'] = 1;
    //         } else {
    //             $user['marketplaceaccess'] = 0;
    //         }
    //         $_SESSION['marketplaceaccess'] =  $user['marketplaceaccess'];
    //         // userImpersonate


    //         $userleveldata = $this->users_model->getuserlevel($_SESSION['id_user']);

    //         $result = '';
    //         foreach ($userleveldata as $subArray) {
    //             $result .= $subArray['fk_id_d'] . ',';
    //         }

    //         $userlevel = rtrim($result, ',');

    //         $_SESSION['userlevel'] =  $userlevel;
    //         return redirect()->to(base_url('my_training'));
    //     }
    // }
    public function userImpersonate()
    {
        if ($response = $this->requireRole(['6', '4'])) {
            return $response;
        }

        $session = session();

        if (!$this->request->getPost('id_user')) {
            return redirect()->back();
        }

        // Stop impersonation
        if ($session->get('is_impersonating')) {

            $adminId = $session->get('org_id_user');

            $adminData = $this->users_model->usersedit_view($adminId);

            $session->set([
                'id_user'         => $adminId,
                'session_version' => (int)($adminData[0]['session_version'] ?? 1),
                'is_impersonating' => false
            ]);

            $session->remove([
                'org_id_user',
                'impersonator_version'
            ]);
        }
        // Start impersonation
        else {

            $targetUserId = (int)$this->request->getPost('id_user');

            $targetUser = $this->users_model->usersedit_view($targetUserId);

            $session->set([
                'org_id_user'         => $session->get('id_user'),
                'impersonator_version' => $session->get('session_version'),
                'is_impersonating'    => true,

                'id_user'             => $targetUserId,
                'session_version'     => (int)($targetUser[0]['session_version'] ?? 1),
            ]);
        }

        // Reload user details
        $userdata = $this->users_model->usersedit_view($session->get('id_user'));
        $userleveldata = $this->users_model->getuserlevel($session->get('id_user'));

        $result = '';
        foreach ($userleveldata as $subArray) {
            $result .= $subArray['fk_id_d'] . ',';
        }

        $userlevel = rtrim($result, ',');
        $_SESSION['client'] = $userdata[0]['id_c'];
        $menu = $this->settings_model->menu_view($_SESSION['client']);
        $user['accessmenu']   = $menu[0]['accessmenu'];
        $_SESSION['accessmenu'] =  $user['accessmenu'];
        $marketplaceaccess = $this->settings_model->marketplaceaccess($_SESSION['client']);
        if (!empty($marketplaceaccess)) {
            $user['marketplaceaccess'] = 1;
        } else {
            $user['marketplaceaccess'] = 0;
        }
        $_SESSION['marketplaceaccess'] =  $user['marketplaceaccess'];
        $session->set([
            'isLoggedIn'        => true,
            'username'          => $userdata[0]['user'],
            'profile_foldername' => $userdata[0]['profile_foldername'],
            'profile_image'     => $userdata[0]['profile_image'],
            'name'              => $userdata[0]['name'],
            'client'            => $userdata[0]['id_c'],
            'userlevel'        => $userlevel,
        ]);
        return redirect()->to(base_url('my_training'));
    }
    public function course_thumbnail_list()
    {
        if ($response =  $this->requireRole(['6'])) {
            return $response;
        }
        $data = [];
        $data['type'] = 0;

        $data['coursedata'] = $this->scorm_course_model->fullCourseDetails();

        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_dashboard/course_thumbnail_list_view');
        echo view('templates/footer_view');
    }
    public function course_thumbnaildetails()
    {
        if ($response =  $this->requireRole(['6'])) {
            return $response;
        }
        $data = [];
        $data['type'] = 0;

        $data['coursedata'] = $this->scorm_course_model->fullCourseDetails();

        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_dashboard/course_thumbnaildetails_view');
        echo view('templates/footer_view');
    }
    public function downloadAllThumbnails()
    {
        if ($response =  $this->requireRole(['6'])) {
            return $response;
        }
        $coursedata =  $this->scorm_course_model->fullCourseDetails();

        // Create temp ZIP
        $zip = new \ZipArchive();
        $zipName = tempnam(sys_get_temp_dir(), 'thumbnails_') . '.zip';

        if ($zip->open($zipName, \ZipArchive::CREATE) !== TRUE) {
            return redirect()->back()->with('error', 'Cannot create ZIP file');
        }

        foreach ($coursedata as $data) {
            if (!empty($data['thumbnail'])) {
                $filePath = FCPATH . 'assets/assets/uploads/SCORM_course_thumbnail/'
                    . $data['scourse_id'] . '/' . $data['thumbnail'];

                if (file_exists($filePath)) {
                    $zip->addFile($filePath, $data['course_name'] . '.jpg'); // optional: rename inside ZIP
                }
            }
        }

        $zip->close();

        // Send ZIP manually and delete temp file after
        return $this->response->setHeader('Content-Type', 'application/zip')
            ->setHeader('Content-Disposition', 'attachment; filename="course_thumbnails.zip"')
            ->setHeader('Content-Length', filesize($zipName))
            ->setBody(file_get_contents($zipName))
            ->send();

        // Delete temp file manually after sending
        unlink($zipName);
    }
}
