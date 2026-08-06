<?php

namespace App\Controllers\Contentforu;

use App\Controllers\BaseController;
use App\Models\Cforu\Cforu_model;
use App\Models\SCORM\Scorm_metacategory_model;

#[\AllowDynamicProperties]
class Dashboard extends BaseController
{
    private $db;
    public function __construct()
    {
        $this->Cforu_model = new Cforu_model();
        $this->scorm_metacategory_model = new Scorm_metacategory_model();
    }
    public function index()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data = [];
        $data['header'] = 'TQ Library';
        $data['clientCourseddata'] = $this->Cforu_model->getC4UCourses();
        echo view('templates/header_view', $data);
        echo view('contentforu/left_menu', $data);
        echo view('contentforu/tq_library_dashboard_view', $data);
        echo view('contentforu/right_menu', $data);
        echo view('templates/footer_view');
    }

    public function by_category($sc_mcid)
    {
         if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data = [];
        $data['sc_mcid'] = $sc_mcid;
        $data['course_by_category'] = $this->scorm_metacategory_model->getAllCoursesByCategory($data['sc_mcid']);
        //  print_r($data['sc_mcid']);
        //  exit();
        switch ($data['sc_mcid']) {
            case 43:
                $data['cat_name'] = 'Business Skills';
                break;
            case 102:
                $data['cat_name'] = 'Compliance';
                break;
            case 45:
                $data['cat_name'] = 'DEI';
                break;
            case 101:
                $data['cat_name'] = 'Technology';
                break;
            case 104:
                $data['cat_name'] = 'Safety';
                break;
            case 56:
                $data['cat_name'] = 'Wellness';
                break;
            case 65:
                $data['cat_name'] = 'Healthcare';
                break;
        }
        echo view('templates/header_view', $data);
        echo view('contentforu/left_menu', $data);
        echo view('contentforu/tq_library_category_view', $data);
        echo view('contentforu/right_menu', $data);
        echo view('templates/footer_view');
    }
    public function contactAdmin()
    {
         if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data = [];

        echo view('templates/header_view', $data);
        echo view('contentforu/left_menu', $data);
        echo view('contentforu/contact_admin', $data);
        echo view('contentforu/right_menu', $data);
        echo view('templates/footer_view');
    }
}
