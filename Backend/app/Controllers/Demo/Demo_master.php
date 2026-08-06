<?php

namespace App\Controllers\Demo;

use App\Controllers\BaseController;

use App\Models\Demo\Demos_model;

#[\AllowDynamicProperties]
class Demo_master extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->is_session_available();
        $this->demos_model = new Demos_model();
    }
    private function is_session_available()
    {
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url('my_training'));
            exit();
        }

        $arrayuserlevel = explode(',', $userlevel);
        if (!in_array('6', $arrayuserlevel) && !in_array('67', $arrayuserlevel) && !in_array('44', $arrayuserlevel)) {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            header('Location:' . base_url('my_training'));
            exit();
        }
    }
    public function index()
    {

        if ($response =  $this->requireRole(['8', '6', '67', '44', '46'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data['showactiveprojects'] = $this->demos_model->showactiveprojects();
        echo view('templates/header_view');
        echo view('demos/demo_master_view', $data);
        echo view('templates/footer_view');
    }
    function createnewdemo()
    {
        if ($response =  $this->requireRole(['8', '6', '67','46'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data['description'] = $this->request->getPost('description');
        $data['username'] = $this->request->getPost('username');
        $data['showactiveprojects'] = $this->demos_model->showactiveprojects();
        if ($this->request->getPost()) {
            $rules = [
                'description' => 'required',
            ];
            if (!$this->validate($rules)) {
                $data['demovalidation'] = $this->validator;
            } else {
                $newdata = [
                    'description' => $data['description'],
                    'status' => 1,
                    'createdon' => date("m-d-y"),
                    'createdby' =>  $data['username'],
                    'last_updated_by' =>  session()->get('id_user'),
                    'last_updated_on' => time(),

                ];
                $last_demo_id =  $this->demos_model->demo_details_save($newdata);
                if ($last_demo_id) {
                    return redirect()->to(base_url() . '/Demo/demo_master/demo_details_edit?demoid=' . $last_demo_id);
                } else {
                    return redirect()->to(base_url() . '/Demo/demo_master');
                }
            }
        }
        echo view('templates/header_view');
        echo view('demos/demo_master_view', $data);
        echo view('templates/footer_view');
    }
    function demo_details_edit()
    {
        if ($response =  $this->requireRole(['8', '6', '67', '46'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data['demoid'] = $this->request->getVar('demoid');
        $demoid = $data['demoid'];
        $data['project_name'] = $this->demos_model->inputbox($demoid, 3);
        $data['description'] = $this->demos_model->inputbox($demoid, 10);
        $data['course_link'] = $this->demos_model->inputbox($demoid, 6);
        $data['showcase_link'] = $this->demos_model->inputbox($demoid, 4);
        $data['addcat'] = $this->demos_model->addcatdata($demoid);
        echo view('templates/header_view');
        echo view('demos/demo_details_edit_view', $data);
        echo view('templates/footer_view');
    }
    function deletedesc($demoid)
    {
        if ($response =  $this->requireRole(['8', '6', '67', '46'])) {
            return $response;
        }
        $newdata = [
            'status' => '0',
        ];
        $result = $this->demos_model->updateDesc($newdata, $demoid);
        if ($result) {
            return redirect()->to(base_url() . '/Demo/demo_master')
                ->with('success', 'Deleted successfully');
        } else {
            return redirect()->to(base_url() . '/Demo/demo_master')
                ->with('message', lang('Messages.Error_0001'));
        }
    }
    function updatedata()
    {
        if ($response =  $this->requireRole(['8', '6', '67', '46'])) {
            return $response;
        }
        $data['demoid'] = $this->request->getPost('demoid');
        $data['valid'] = $this->request->getPost('valid');
        $data['valdetails'] = $this->request->getPost('valdetails');
        $data['typeofval'] = $this->request->getPost('typeofval');
        $result = $this->demos_model->updatedata($data);
        if ($result) {
            return redirect()->to(base_url() . '/Demo/demo_master/demo_details_edit?demoid=' . $data['demoid'])
                ->with('success', 'updated successfully');
        } else {
            return redirect()->to(base_url() . '/Demo/demo_master/demo_details_edit?demoid=' . $data['demoid'])
                ->with('message', lang('Messages.Error_0001'));
        }
    }
    function upload_demo_file()
    {
        if ($response =  $this->requireRole(['8', '6', '67', '46'])) {
            return $response;
        }
        $id_user  = session()->get('id_user');
        $username  = session()->get('username');
        $data['demoid'] = $this->request->getPost('demoid');
        $demoid = $data['demoid'];
        $data['project_name'] = $this->demos_model->inputbox($demoid, 3);
        // print_r(count($data['project_name']));
        $data['description'] = $this->demos_model->inputbox($demoid, 10);
        $data['course_link'] = $this->demos_model->inputbox($demoid, 6);
        $data['showcase_link'] = $this->demos_model->inputbox($demoid, 4);
        if ($this->request->getMethod() == 'post') {
            $rules = [
                'file' => 'uploaded[file]|ext_in[file,jpg,JPG,jpeg]',
            ];
            if (! $this->validate($rules)) {
                $file = $this->request->getFile('file');
                $data['filevalidation'] = $this->validator;
            } else {

                if ($file = $this->request->getFile('file')) {
                    if ($file->isValid() && ! $file->hasMoved()) {
                        $imagename = $file->getName();
                        $newfilename = $file->getRandomName();
                        if (!file_exists(FCPATH . 'assets/assets/uploads/client/' . $data['demoid'] . "/" . $imagename)) {
                            if (!is_dir(FCPATH . 'assets/assets/uploads/client/' . $data['demoid'])) {
                                mkdir(FCPATH . 'assets/assets/uploads/client/' . $data['demoid'] . "/", 0777, true);
                            }
                        }
                        $a = base_url() . '/assets/assets/uploads';
                        if (file_exists(FCPATH . 'assets/assets/uploads/client/' . $data['demoid'] . "/" . $imagename)) {
                            session()->setFlashdata('error', $imagename . ' ' . lang('Messages.Success_0051'));
                            return redirect()->to(base_url() . '/client');
                        } else {

                            $file->move(FCPATH . 'assets/assets/uploads/client/' . $data['demoid'], $imagename);
                            $filepath = FCPATH . 'assets/assets/uploads/client/' . $data['demoid'] . '/' . $imagename;

                            $newdata = [
                                'demoid' => $data['demoid'],
                                'typeofval' => 1,
                                'details' => $imagename,
                                'typeofval' => 1,
                                'status' => 1,
                                'last_updated_by' =>  session()->get('id_user'),
                                'last_updated_on' => time(),
                            ];
                            $result = $this->demos_model->addImgData($newdata);
                            if ($result) {
                                $session = session();
                                $session->setFlashdata('success', 'Uploaded Successfully!');
                                return redirect()->to(base_url() . '/Demo/demo_master/demo_details_edit?demoid=' . $data['demoid'])
                                    ->with('success', 'updated successfully');
                            } else {
                                session()->setFlashdata('error', lang('Messages.Error_0001'));
                                session()->setFlashdata('alert-class', 'alert-danger');
                                return redirect()->to(base_url() . '/Demo/demo_master/demo_details_edit?demoid=' . $data['demoid'])
                                    ->with('message', lang('Messages.Error_0001'));
                            }
                        }
                    }
                }
            }
        }
        echo view('demos/demo_details_edit_view', $data);
    }
    function category_process()
    {
        if ($response =  $this->requireRole(['8', '6', '67', '46'])) {
            return $response;
        }
        $data['demoid'] = $_POST['demoid'];
        $data['catlist'] = $_POST['catlist'];
        $result = $this->demos_model->savecategory($data);
        if ($result) {
            return redirect()->to(base_url() . '/Demo/demo_master/demo_details_edit?demoid=' . $data['demoid'])
                ->with('success', 'updated successfully');
        } else {
            return redirect()->to(base_url() . '/Demo/demo_master/demo_details_edit?demoid=' . $data['demoid'])
                ->with('message', 'Data not inserted');
        }
    }
    function removetag()
    {
        if ($response =  $this->requireRole(['8', '6', '67', '46'])) {
            return $response;
        }
        if (isset($_POST['remove_tag_id'])) {
            $id = $_POST['remove_tag_id'];
            $result = $this->demos_model->removetag($id);
            echo json_encode($result);
        }
    }
}
