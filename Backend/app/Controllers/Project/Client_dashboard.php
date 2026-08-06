<?php

namespace App\Controllers\Project;

use App\Controllers\BaseController;
use App\Models\User_login\Client_model;
use App\Models\SCORM\Scorm_course_model;
use App\Models\Project_Manage\PM_ucn_model;
#[\AllowDynamicProperties]
class Client_dashboard extends BaseController
{
    private $db;
    protected $Client_model;
    protected $Scorm_course_model;
    protected $PM_ucn_model;
    public function __construct()
    {
        $this->is_session_available();
        $this->Client_model = new Client_model();
        $this->scorm_course_model = new Scorm_course_model();
        $this->PM_ucn_model = new PM_ucn_model();
    }
    private function is_session_available()
    {
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url('my_training'));
            exit();
        }

        $arrayuserlevel = explode(',', $userlevel);
        if (!in_array('10', $arrayuserlevel)) {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            header('Location:' . base_url('my_training'));
            exit();
        }
    }
    public function index()
    {

        $data = [];
        $client = session()->get('client');
        $data['client_active_projects'] = $this->Client_model->getClientProjects($client);
        echo view('templates/header_view', $data);
        echo view('client/client_dashboard_view');
        echo view('templates/footer_view');
    }
    public function escalation()
    {

        $data = [];
        echo view('templates/header_view', $data);
        echo view('client/client_escalation');
        echo view('templates/footer_view');
    }
    public function documents()
    {
        $data = [];
        echo view('templates/header_view', $data);
        echo view('client/client_documents');
        echo view('templates/footer_view');
    }
    public function courses()
    {
        $data = [];
        if (isset($_POST['projectid'])) {
            $data['projectid'] = $_POST['projectid'];
            $_SESSION['projectid'] =  $data['projectid'];
        } else if (isset($_GET['projectid'])) {
            $data['projectid'] = $_GET['projectid'];
        } else if (isset($_SESSION['projectid'])) {
            $data['projectid'] = $_SESSION['projectid'];
        }

        $data['courses_by_project'] = $this->scorm_course_model->get_courses_assigned_to_project($data['projectid']);
        echo view('templates/header_view', $data);
        echo view('client/client_courses');
        echo view('templates/footer_view');
    }

    public function reviewers()
    {
        $data = [];
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] =  $data['scourse_id'];
        }  else if (isset($_SESSION['projectid'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        }
        $client = session()->get('client');
        $data['client_access']  =  $this->PM_ucn_model->getAssignedUsercourse($data['scourse_id'], $client);
        echo view('templates/header_view', $data);
        echo view('client/client_reviewers');
        echo view('templates/footer_view');
    }
}
