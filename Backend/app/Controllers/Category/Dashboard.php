<?php

namespace App\Controllers\Category;

use App\Controllers\BaseController;
use App\Models\Category\Category_Dashboard_model;

#[\AllowDynamicProperties]
class Dashboard extends BaseController
{


    public function __construct()
    {
        $this->is_session_available();
        $this->Category_Dashboard_model = new Category_Dashboard_model();
    }
    private function is_session_available()
    {
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url());
            exit();
        }

        $arrayuserlevel = explode(',', $userlevel);

        if (!in_array('6', $arrayuserlevel)) {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            header('Location:' . base_url('my_training'));
            exit();
        }
    }
    public function index()
    {
        if ($response =  $this->requireRole(['6'])) {
            return $response;
        }
        $data = [];

        $data['get_meta'] = $this->Category_Dashboard_model->get_meta();

        echo view('templates/header_view', $data);
        echo view('category/cat_dashboard', $data);
        echo view('templates/footer_view', $data);
    }

    public function add_new_meta_category()
    {
        if ($response =  $this->requireRole(['6'])) {
            return $response;
        }
        $meta_category = $this->request->getPost('meta_category');
        if (!empty($meta_category)) {

            $data = [
                'description' => $meta_category,
                'typeofval' => 5,
                'status' => 1,
                'last_updated_on' => time(),
                'last_updated_by' => session()->get('id_user'),
            ];
            $this->Category_Dashboard_model->add_new_meta_category_m($data);
            session()->setFlashdata('success', lang('Messages.Success_0014'));
            return redirect()->to(base_url('category/dashboard'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->to(base_url('category/dashboard'));
        }
    }

    public function delete_meta_category()
    {
        if ($response =  $this->requireRole(['6'])) {
            return $response;
        }
        $mc_id = $this->request->getPost('mc_id');
        if (!empty($mc_id)) {

            $data = [
                'status' => 0,
                'last_updated_on' => time(),
                'last_updated_by' => session()->get('id_user'),
            ];
            $this->Category_Dashboard_model->delete_meta_category_m($mc_id, $data);
            session()->setFlashdata('success', lang('Messages.Success_0015'));
            return redirect()->to(base_url('category/dashboard'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->to(base_url('category/dashboard'));
        }
    }

    public function view_category()
    {
        if ($response =  $this->requireRole(['6'])) {
            return $response;
        }
        $data = [];

        if (isset($_POST['mc_id'])) {
            $data['mc_id'] = $_POST['mc_id'];
            $data['mc_name'] = $_POST['mc_name'];
            $_SESSION['mc_id'] = $_POST['mc_id'];
            $_SESSION['mc_name'] = $_POST['mc_name'];
        } elseif (isset($_SESSION['mc_id'])) {
            $data['mc_id'] = $_SESSION['mc_id'];
            $data['mc_name'] = $_SESSION['mc_name'];
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->to(base_url('category/dashboard'));
        }

        $data['get_category'] = $this->Category_Dashboard_model->get_category_m($data['mc_id']);

        echo view('templates/header_view', $data);
        echo view('category/cat_view_category', $data);
        echo view('templates/footer_view', $data);
    }

    function add_category_to_meta()
    {
        if ($response =  $this->requireRole(['6'])) {
            return $response;
        }
        $mc_id = $_SESSION['mc_id'];
        $category = $this->request->getPost('category');
        if (!empty($category)) {

            $data = [
                'description' => $category,
                'typeofval' => 2,
                'meta_category' => $mc_id,
                'status' => 1,
                'last_updated_on' => time(),
                'last_updated_by' => session()->get('id_user'),
            ];
            $this->Category_Dashboard_model->add_new_category_m($data);
            session()->setFlashdata('success', lang('Messages.Success_0016'));
            return redirect()->to(base_url('category/dashboard/view_category'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->to(base_url('category/dashboard/view_category'));
        }
    }

    function delete_category()
    {
        if ($response =  $this->requireRole(['6'])) {
            return $response;
        }
        $cat_id = $this->request->getPost('cat_id');
        if (!empty($cat_id)) {

            $data = [
                'status' => 0,
                'last_updated_on' => time(),
                'last_updated_by' => session()->get('id_user'),
            ];
            $this->Category_Dashboard_model->delete_category_m($cat_id, $data);
            session()->setFlashdata('success', lang('Messages.Success_0005'));
            return redirect()->to(base_url('category/dashboard/view_category'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->to(base_url('category/dashboard/view_category'));
        }
    }

    function view_courses()
    {
        if ($response =  $this->requireRole(['6'])) {
            return $response;
        }
        $data = [];

        if (isset($_POST['cat_id'])) {
            $data['cat_id'] = $_POST['cat_id'];
            $data['cat_name'] = $_POST['cat_name'];
            $_SESSION['cat_id'] = $_POST['cat_id'];
            $_SESSION['cat_name'] = $_POST['cat_name'];
        } elseif (isset($_SESSION['cat_id'])) {
            $data['cat_id'] = $_SESSION['cat_id'];
            $data['cat_name'] = $_SESSION['cat_name'];
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->to(base_url('category/dashboard'));
        }

        $data['get_cat_courses'] = $this->Category_Dashboard_model->get_cat_courses_m($data['cat_id']);
        $data['get_all_courses'] = $this->Category_Dashboard_model->get_all_courses(); // You can load courses related to the category here and pass to the view

        echo view('templates/header_view', $data);
        echo view('category/cat_view_courses', $data); // Create this view to display courses
        echo view('templates/footer_view', $data);
    }

    function add_course_to_category()
    {
        if ($response =  $this->requireRole(['6'])) {
            return $response;
        }
        $cat_id = $_SESSION['cat_id'];
        $course_id = $_POST['course_id'];
        // exit();
        if (!empty($course_id)) {

            $data = [
                'fk_sc_mcid' => $cat_id,
                'fk_scourse_id' => $course_id,
                'status' => 1,
                'last_updated_on' => time(),
                'last_updated_by' => session()->get('id_user'),
            ];
            $result = $this->Category_Dashboard_model->add_course_to_category_m($data);
            echo json_encode($result);
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->to(base_url('category/dashboard/view_courses'));
        }
    }

    function unlink_course()
    {
        if ($response =  $this->requireRole(['6'])) {
            return $response;
        }
        $mc_id = $this->request->getPost('mc_id');
        if (!empty($mc_id)) {

            $data = [
                'status' => 0,
                'last_updated_on' => time(),
                'last_updated_by' => session()->get('id_user'),
            ];
            $this->Category_Dashboard_model->unlink_course_m($mc_id, $data);
            session()->setFlashdata('success', lang('Messages.Success_0017'));
            return redirect()->to(base_url('category/dashboard/view_courses'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->to(base_url('category/dashboard/view_courses'));
        }
    }
}
