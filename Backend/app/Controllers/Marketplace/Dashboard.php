<?php

namespace App\Controllers\Marketplace;

use App\Controllers\BaseController;
use App\Models\Marketplace\M_Dashboard_model;
use App\Models\Payment\Billing_model;

#[\AllowDynamicProperties]
class Dashboard extends BaseController
{


    public function __construct()
    {
        $this->is_session_available();
        $this->M_Dashboard_model = new M_Dashboard_model();
        $this->billing_model = new Billing_model();
    }
    private function is_session_available()
    {
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url());
            exit();
        }
    }
    public function index()
    {
        if ($response =  $this->requireRole(['5', '44', '3'])) {
            return $response;
        }
        return redirect()->to(base_url('marketplace/catalog/marketplace_view'));
    }

    public function search_by_coursename()
    {
        if ($response =  $this->requireRole(['5', '44', '3'])) {
            return $response;
        }
        $data = [];
        if (isset($_POST['type'])) {
            $data['type'] = $_POST['type'];
            $_SESSION['type'] = $_POST['type'];
        } elseif (isset($_SESSION['type'])) {
            $data['type'] = $_SESSION['type'];
        } else {
            $data['type'] = 1;
        }
        if ($data['type'] == 1) {
            $data['header_title'] = "Marketplace Dashboard";
            $data['header_link'] = "marketplace/dashboard";
            $data['admin'] = "Create New Marketplace";
        } else {
            $data['header_title'] = "Learning Plan Dashboard";
            $data['header_link'] = "marketplace/learning_dashboard";
            $data['admin'] = "Create New Learning Plan";
        }
        $data['admin_page'] = "marketplace/admin";
        $data['type'] = '1';
        $client_id = session()->get('client');
        $id_user = session()->get('id_user');
        $data['skills'] = $this->M_Dashboard_model->getSkills();
        $data['language'] = $this->M_Dashboard_model->getLanguage();
        $search_term = $this->request->getPost('search_term');
        if ($search_term == '') {
            return redirect()->to(base_url('marketplace/dashboard'));
        }
        //   print_r($search_term);
        // exit();
        $data['get_courses'] = $this->M_Dashboard_model->get_courses_search($client_id, $search_term);
        $data['getsubscribebillingdata'] = $this->billing_model->getsubscribebillingdata($id_user);

        $data['cat_list'] = $_SESSION['cat_list'] = 100;
        $data['mp_language'] = $_SESSION['mp_language'] = "All";

        echo view('templates/header_view', $data);
        echo view('marketplace/mp_left_menu', $data);
        echo view('marketplace/mp_search', $data);
        echo view('templates/footer_view', $data);
    }

    public function load_more($page)
    {
        if ($response =  $this->requireRole(['5', '44', '3'])) {
            return $response;
        }
        $data = [];
        $client_id = session()->get('client');
        $pageId = intval($page);
        if (isset($_POST['mp_language'])) {
            $_SESSION['mp_language'] = $_POST['mp_language'];
        } elseif (isset($_SESSION['mp_language'])) {
        } else {
            $_SESSION['mp_language'] = "All";
        }

        if (isset($_POST['cat_list'])) {
            $_SESSION['cat_list'] = $_POST['cat_list'];
        } elseif (isset($_SESSION['cat_list'])) {
        } else {
            $_SESSION['cat_list'] = 126;
        }

        $data['cat_list'] = $_SESSION['cat_list'];
        $data['mp_language'] = $_SESSION['mp_language'];
        $data['get_courses'] = $this->M_Dashboard_model->get_courses($client_id, $pageId, $_SESSION['mp_language'], $data['cat_list']);
        return view('marketplace/mp_load_more', $data);
    }

    // public function languagelist($cagetory_code)
    // {
    //     $data = [];
    //     if ($cagetory_code > 0 && is_numeric($cagetory_code) && $cagetory_code < 20) {
    //         switch ($cagetory_code) {
    //             case 1:
    //                 $data['mp_language'] = "English";
    //                 break;
    //             case 2:
    //                 $data['mp_language'] = "French";
    //                 break;
    //             case 3:
    //                 $data['mp_language'] = "German";
    //                 break;
    //             case 4:
    //                 $data['mp_language'] = "Italian";
    //                 break;
    //             case 5:
    //                 $data['mp_language'] = "Spanish";
    //                 break;
    //             case 10:
    //                 $data['mp_language'] = "All";
    //                 break;
    //             default:
    //                 $data['mp_language'] = "All";
    //                 break;
    //         }
    //         $_SESSION['mp_language'] = $data['mp_language'];
    //     }
    //     header('Location:' . base_url('marketplace/dashboard'));
    //     exit();
    // }
    public function languagelist($language = null)
    {
        if ($response =  $this->requireRole(['5', '44', '3'])) {
            return $response;
        }
        if (!empty($language)) {
            $lang = urldecode($language);
            $_SESSION['mp_language'] = $lang;
        }

        header('Location: ' . base_url('marketplace/dashboard'));
        exit();
    }


    public function categorylist($cagetory_code)
    {
        if ($response =  $this->requireRole(['5', '44', '3'])) {
            return $response;
        }
        $_SESSION['cat_list'] = $cagetory_code;
        header('Location:' . base_url('marketplace/dashboard'));
        exit();
    }
    public function getSkills()
    {
        if ($response =  $this->requireRole(['5', '44', '3'])) {
            return $response;
        }
        $this->M_Dashboard_model->getLanguage();
    }
}
