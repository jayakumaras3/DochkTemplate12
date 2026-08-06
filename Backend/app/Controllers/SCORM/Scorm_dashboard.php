<?php

namespace App\Controllers\SCORM;

use App\Controllers\BaseController;

use App\Models\SCORM\Scorm_dashboard_model;
use App\Models\SCORM\Scorm_lanuch_model;

#[\AllowDynamicProperties]
class Scorm_dashboard extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->is_session_available();
        $this->scorm_dashboard_model = new Scorm_dashboard_model();
        $this->scorm_lanuch_model = new Scorm_lanuch_model();
    }
    private function is_session_available()
    {
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url('my_training'));
            exit();
        }

        $arrayuserlevel = explode(',', $userlevel);
        if (!in_array('6', $arrayuserlevel) && !in_array('5', $arrayuserlevel) && !in_array('73', $arrayuserlevel) && !in_array('3', $arrayuserlevel) && !in_array('86', $arrayuserlevel) && !in_array('67', $arrayuserlevel) && !in_array('44', $arrayuserlevel) && !in_array('46', $arrayuserlevel)) {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            header('Location:' . base_url('my_training'));
            exit();
        }
    }
    public function index()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '3', '67', '73', '86','46'])) {
            return $response;
        }
        $data = [];
        $data['header'] = 'Dashboard';
        $data['type'] = 2;
        $data['clientCourseddata'] = $this->scorm_dashboard_model->getAllCourseddata($data['type']);
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_dashboard/scorm_dashboardtable_view');
        echo view('templates/footer_view');
    }
    public function dashboardtable()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '3', '67', '73', '86','46'])) {
            return $response;
        }
        $data = [];
        $data['header'] = 'Dashboard';
        $data['type'] = 2;
        $userlevel = session()->get('userlevel');
        $arrayuserlevel  = array_map('intval', explode(',', $userlevel));
        if (in_array('73', $arrayuserlevel) || in_array('5', $arrayuserlevel) || in_array('44', $arrayuserlevel)) {
            if ($this->request->getGet('search')) {
                $data['search'] = $this->request->getGet('search');
                $data['clientCourseddata'] = $this->scorm_dashboard_model->getsearchAllCourseddata($data['type'], $data['search']);
            } else {
                $data['clientCourseddata'] = $this->scorm_dashboard_model->getAllCourseddata($data['type']);
            }
            echo view('templates/header_view', $data);
            echo view('SCORM/scorm_dashboard/scorm_dashboard_view');
            echo view('templates/footer_view');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            return redirect()->to(base_url('my_training'));
        }
    }
    public function launchpromo_video()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '3', '67', '73', '86','46'])) {
            return $response;
        }
        $data = [];
        $course_id  =  session()->get('crid');

        $clienteachCourseddata = $this->scorm_dashboard_model->getAlleachCourseddata($course_id);
        // print_r( $course_id );
        //  print_r( $clienteachCourseddata[0]['scourse_id']);
        //  exit();
        $videoData = [
            'title' => $clienteachCourseddata[0]['course_name'],
            'videoUrl' =>  base_url("assets/assets/uploads/SCORM_course_promovideo/" . $clienteachCourseddata[0]['scourse_id'] . "/" . $clienteachCourseddata[0]['promo_video']),
        ];
        return view('SCORM/scorm_dashboard/scorm_promovideo_view', $videoData);
    }
    public function launchyoutube_video()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '3', '67', '73', '86','46'])) {
            return $response;
        }
        $data = [];
        $course_id  =  session()->get('course_id');
        $clienteachCourseddata = $this->scorm_dashboard_model->getAlleachCourseddata($course_id);
        $videoUrl = $clienteachCourseddata[0]['launch_link'];
        $data['addscormuserdetails'] = $this->scorm_lanuch_model->addyoutubledetails($course_id, $videoUrl);
        $videoData = [
            'title' => $clienteachCourseddata[0]['course_name'],
            'videoUrl' =>   $videoUrl,
        ];
        return view('SCORM/scorm_dashboard/scorm_youtubevideo_view', $videoData);
    }
    public function launchDescriptionPopup()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '3', '67', '73', '86','46'])) {
            return $response;
        }
        $data = [];
        $course_id  =  session()->get('course_id');
        $clienteachCourseddata = $this->scorm_dashboard_model->getAlleachCourseddata($course_id);
        $contentData = [
            'content' => $clienteachCourseddata,
            'title' => $clienteachCourseddata[0]['course_name'],
        ];
        echo view('SCORM/scorm_dashboard/scorm_description_view', $contentData);
    }
    public function downloadPdf()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '3', '67', '73', '86','46'])) {
            return $response;
        }
        $data = [];
        $courseId = $this->request->getVar('course_id');
        $clienteachCourseddata = $this->scorm_dashboard_model->getAlleachCourseddata($courseId);
        $pdfFilePath = FCPATH . 'assets/assets/uploads/SCORM_course_pdf/' . $courseId . '/' . $clienteachCourseddata[0]['pdf_filename'];
        if (file_exists($pdfFilePath)) {
            $pdfData = file_get_contents($pdfFilePath);

            return $this->response->setHeader('Content-Type', 'application/pdf')->setBody($pdfData);
        } else {
            echo 'PDF file not found.';
        }
    }
}
