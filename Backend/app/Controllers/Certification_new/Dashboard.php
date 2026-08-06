<?php

namespace App\Controllers\Certification;

use App\Controllers\BaseController;
use App\Models\Certification\Certification_model;
use App\Models\Marketplace\M_Dashboard_model;
use App\Models\User_login\Users_model;
use App\Models\SCORM\Scorm_user_group_model;
use App\Models\Settings\Dropdown_model;


#[\AllowDynamicProperties]


class Dashboard extends BaseController
{


    public function __construct()
    {
        // $this->is_session_available();
        $this->Certification_model = new Certification_model();
        $this->M_Dashboard_model = new M_Dashboard_model();
        $this->users_model = new Users_model();
        $this->scorm_user_group_model = new Scorm_user_group_model();
        $this->dropdown_model = new Dropdown_model();
    }
    private function is_session_available()
    {
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url());
            exit();
        }

        $arrayuserlevel = explode(',', $userlevel);

        if (!in_array('5', $arrayuserlevel) && !in_array('44', $arrayuserlevel) && !in_array('3', $arrayuserlevel)) {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            header('Location:' . base_url('my_training'));
            exit();
        }
    }
    public function index()
    {
        if ($response =  $this->requireRole(['5', '44', '3'])) {
            return $response;
        }
        $data = [];
        $client = session()->get('client');
        $data['client_id'] = $client;
        $userlevel = session()->get('userlevel');
        $arrayuserlevel = explode(',', $userlevel);
        if (in_array('5', $arrayuserlevel) || in_array('44', $arrayuserlevel)) {
            $data['get_all_certificates'] = $this->Certification_model->get_all_certifications($client);
        } else {
            $data['get_all_certificates'] = $this->Certification_model->get_all_certificates_for_user($client, session()->get('id_user'));
        }

        echo view('templates/header_view', $data);
        echo view('certification/dashboard_view', $data);
        echo view('templates/footer_view', $data);
    }

    public function create_new_certificate()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $data = [];
        $data['client_id'] = session()->get('client');
        echo view('templates/header_view', $data);
        echo view('certification/create_certificate_view', $data);
        echo view('templates/footer_view', $data);
    }

    public function add_new_certificate()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $name = $this->request->getPost('name');
        $description = $this->request->getPost('description');
        $type = $this->request->getPost('type');
        $font_size = $this->request->getPost('font_size') ?? 16;
        $font_weight = $this->request->getPost('font_weight') ?? 'normal';
        $font_color = $this->request->getPost('font_color') ?? '#000000';
        $duration = $this->request->getPost('duration') ?? '0';
        $client = session()->get('client');


        $data = [
            'client_id' => $client,
            'name' => $name,
            'description' => $description,
            'type' => $type,
            'font_size' => $font_size,
            'font_weight' => $font_weight,
            'font_color' => $font_color,
            'duration' => $duration,
            'status' => 1,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];

        // Handle background image upload
        $file = $this->request->getFile('background_image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Validate file type
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($file->getMimeType(), $allowed_types)) {
                session()->setFlashdata('error', lang('Messages.Error_0009'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . 'Certification/Dashboard/create_new_certificate');
            }

            // Validate file size (5MB max)
            if ($file->getSize() > 5242880) { // 5MB in bytes
                session()->setFlashdata('error', lang('Messages.Error_0010'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . 'Certification/Dashboard/create_new_certificate');
            }
        }

        $certificate_id = $this->Certification_model->add_new_certificate($data);

        if ($certificate_id) {
            // Handle background image upload after certificate is created
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $new_name = 'background.' . $file->getExtension();
                $upload_path = 'assets/assets/uploads/certificates/' . $certificate_id . '/';

                // Create directory if it doesn't exist
                if (!is_dir($upload_path)) {
                    mkdir($upload_path, 0755, true);
                }

                // Move file to upload directory
                $file->move($upload_path, $new_name);

                // Update certificate with image path
                $image_data = ['background_image' => $upload_path . $new_name];
                $this->Certification_model->update_certificate($certificate_id, $image_data);
            }

            $data_client = [
                'cert_id' => $certificate_id,
                'client_id' => $client,
                'can_edit' => 1,
                'status' => 1,
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $this->Certification_model->link_cert_to_client($data_client);
            session()->setFlashdata('success', lang('Messages.Success_0011'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            session()->setFlashdata('alert-class', 'alert-danger');
        }
        return redirect()->to(base_url() . 'Certification/Dashboard');
    }

    public function Edit_certificate()
    {
         if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $data = [];

        if (isset($_POST['certificate_id'])) {
            $data['certificate_id'] = $_POST['certificate_id'];
            $_SESSION['certificate_id'] = $data['certificate_id'];
        } else if (isset($_SESSION['certificate_id'])) {
            $data['certificate_id'] = $_SESSION['certificate_id'];
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->to(base_url() . 'Certification/Dashboard');
        }
        $data['client_id'] = session()->get('client');
        $data['get_certificate_details'] = $this->Certification_model->get_certificate_details($data['certificate_id']);
        $data['getclients'] = $this->dropdown_model->getclientData();
        echo view('templates/header_view', $data);
        echo view('certification/edit_certificate_view', $data);
        echo view('templates/footer_view', $data);
    }
    function assign_certification_to_client()
    {
         if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $data = [];

        if (isset($_POST['certificate_id'])) {
            $data['certificate_id'] = $_POST['certificate_id'];
            $_SESSION['certificate_id'] = $data['certificate_id'];
        } else if (isset($_SESSION['certificate_id'])) {
            $data['certificate_id'] = $_SESSION['certificate_id'];
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->to(base_url() . 'Certification/Dashboard');
        }
        $client = $this->request->getPost('client');
        $data_client = [
            'cert_id' =>  $data['certificate_id'],
            'client_id' => $client,
            'can_edit' => 1,
            'status' => 1,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $this->Certification_model->link_cert_to_client($data_client);
        session()->setFlashdata('success', lang('Messages.Success_0008'));
        return redirect()->to(base_url() . 'Certification/Dashboard/Edit_certificate');
    }
    public function update_certificate()
    {
         if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $name = $this->request->getPost('name');
        $description = $this->request->getPost('description');
        $type = $this->request->getPost('type');
        $status = $this->request->getPost('status');
        $font_size = $this->request->getPost('font_size') ?? 16;
        $font_weight = $this->request->getPost('font_weight') ?? 'normal';
        $font_color = $this->request->getPost('font_color') ?? '#000000';
        $duration = $this->request->getPost('duration') ?? '0';


        if (isset($_SESSION['certificate_id'])) {
            $certificate_id = $_SESSION['certificate_id'];
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->to(base_url() . 'Certification/Dashboard');
        }
        $data = [
            'name' => $name,
            'description' => $description,
            'type' => $type,
            'status' => $status,
            'font_size' => $font_size,
            'font_weight' => $font_weight,
            'font_color' => $font_color,
            'duration' => $duration,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];

        // Handle background image upload
        $file = $this->request->getFile('background_image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Validate file type
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($file->getMimeType(), $allowed_types)) {
                session()->setFlashdata('error', lang('Messages.Error_0009'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . 'Certification/Dashboard/Edit_certificate');
            }

            // Validate file size (5MB max)
            if ($file->getSize() > 5242880) { // 5MB in bytes
                session()->setFlashdata('error', lang('Messages.Error_0010'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . 'Certification/Dashboard/Edit_certificate');
            }

            // Generate unique filename
            $new_name = 'background.' . $file->getExtension();
            $upload_path = 'assets/assets/uploads/certificates_bg/' . $certificate_id . '/';

            // Create directory if it doesn't exist
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0755, true);
            }
            $existingFile = $upload_path . $new_name;

            if (file_exists($existingFile)) {
                unlink($existingFile);
            }

            // print_r($new_name);
            // exit();
            // Move file to upload directory
            $file->move($upload_path, $new_name, true);

            $data['background_image'] = $upload_path . $new_name;
        }

        $updated = $this->Certification_model->update_certificate($certificate_id, $data);

        if ($updated) {
            session()->setFlashdata('success', lang('Messages.Success_0008'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            session()->setFlashdata('alert-class', 'alert-danger');
        }
        return redirect()->to(base_url() . 'Certification/Dashboard/Edit_certificate');
    }


    function Assign_certificate()
    {
         if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $data = [];

        if (isset($_POST['certificate_id'])) {
            $data['certificate_id'] = $_POST['certificate_id'];
            $_SESSION['certificate_id'] = $data['certificate_id'];
        } else if (isset($_SESSION['certificate_id'])) {
            $data['certificate_id'] = $_SESSION['certificate_id'];
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->to(base_url() . 'Certification/Dashboard');
        }

        $data['client_id'] = session()->get('client');

        $certificate_details = $this->Certification_model->get_certificate_details($data['certificate_id']);
        $data['type'] = $certificate_details[0]['type'];

        if ($data['type'] == 3) {
            $data['get_all_courses'] = $this->Certification_model->get_assigned_courses($data['client_id']);
            $data['get_assigned_courses'] = $this->Certification_model->get_assigned_details_courses($data['certificate_id']);
        }

        if ($data['type'] < 3) {
            $data['get_all_learning_plan'] = $this->Certification_model->get_assigned_lp($data['client_id'], $data['type']);
            $data['get_assigned_lp'] = $this->Certification_model->get_assigned_details_lp($data['certificate_id'], $data['type']);
        }
        if ($data['type'] == 4) { //certification
            $data['get_all_learning_plan'] = $this->Certification_model->get_assigned_lp($data['client_id'], 2); // learning plan list
            $data['get_assigned_lp'] = $this->Certification_model->get_assigned_details_lp($data['certificate_id'], $data['type']);
        }

        echo view('templates/header_view', $data);
        echo view('certification/assign_certificate_view', $data);
        echo view('templates/footer_view', $data);
    }

    // Show certificate preview for the current user and course
    public function view_certificate()
    {
         if ($response =  $this->requireRole(['5', '44', '3'])) {
            return $response;
        }
        $course_mp_id = $this->request->getPost('course_mp_id');
        if (isset($course_mp_id)) {
            $_SESSION['course_mp_id'] = $course_mp_id;
        } else if (isset($_SESSION['course_mp_id'])) {
            $course_mp_id = $_SESSION['course_mp_id'];
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->back();
        }
        $type = $this->request->getPost('type');
        if (isset($type)) {
            $_SESSION['type'] = $type;
        } else if (isset($_SESSION['type'])) {
            $type = $_SESSION['type'];
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->back();
        }

        $data = [];
        if ($type == 3) {
            $data['header_title'] = "Course Certificate";
        } elseif ($type == 2) {
            $data['header_title'] = "Learning Plan Certificate";
        } elseif ($type == 4) {
            $data['header_title'] = "Certification";
        } else {
            $data['header_title'] = "Marketplace Certificate";
        }
        $client = session()->get('client');
        $userId = session()->get('id_user');

        // Find certificate assignment for this course
        $builder = $this->Certification_model->db->table('certificates_assigned as ca');
        $builder->select('ca.*');
        if ($type == 4) {
            $builder->where('ca.certificate_id', $course_mp_id);
        } else {
            $builder->where('ca.course_lp_mp_id', $course_mp_id);
        }
        $builder->where('ca.type', $type);
        $builder->where('ca.client_id', $client);
        $builder->where('ca.status !=', 0);
        $assign = $builder->get()->getRowArray();
        // print_r($course_mp_id);
        // print_r($type);
        // print_r($assign);
        // exit();

        if (empty($assign)) {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->back();
            // return  redirect()->to(base_url('marketplace/Learning_dashboard/learning_courses'));
        }
        if ($type == 4) {
            $certificate = $this->Certification_model->get_certificate_details($course_mp_id);
        } else {
            $certificate = $this->Certification_model->get_certificate_details($assign['certificate_id']);
        }
        //  print_r($certificate);
        // exit();
        if (empty($certificate)) {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->back();
            // return  redirect()->to(base_url('marketplace/Learning_dashboard/learning_courses'));
        }


        $data['certificate'] = $certificate[0];
        $data['assign'] = $assign;
        $data['user'] = [
            'id_user' => $userId,
            'name' => session()->get('name') . ' ' . session()->get('last_name'),
        ];
        $data['course_mp_id'] = $course_mp_id;
        if ($type == 2) {
            $data['course_name'] = $this->Certification_model->db
                ->table('marketplace as mp')
                ->select('mp.mp_name')
                ->where('mp.mp_id', $course_mp_id)
                ->get()->getRow('mp_name');
            $userDetails = $this->Certification_model->db

                ->table('assign_users_learning_plan')
                ->where('user_id', $userId)
                ->where('mp_id', $course_mp_id)
                ->where('status', '3')
                ->where('status !=', 0)
                ->orderBy('last_updated_on', 'DESC')
                ->get()->getRowArray();
            $data['user_details'] = $userDetails;
            $data['completionDate'] = !empty($assign['last_updated_on']) ? date('Y-m-d', $assign['last_updated_on']) : date('Y-m-d');
        } elseif ($type == 4) {
            $data['course_name'] = $this->Certification_model->db
                ->table('certificates as c')
                ->select('c.name')
                ->where('c.cert_id', $course_mp_id)
                ->get()->getRow('name');
            $userDetails = $this->Certification_model->db

                ->table('assign_users_learning_plan')
                ->where('user_id', $userId)
                ->where('mp_id', $course_mp_id)
                ->where('status', '3')
                ->where('status !=', 0)
                ->orderBy('last_updated_on', 'DESC')
                ->get()->getRowArray();
            $data['user_details'] = $userDetails;
            $data['completionDate'] = !empty($assign['last_updated_on']) ? date('Y-m-d', $assign['last_updated_on']) : date('Y-m-d');
        } else {


            $data['course_name'] = $this->Certification_model->db
                ->table('scorm_courses as c')
                ->select('c.course_name')
                ->where('c.scourse_id', $course_mp_id)
                ->get()->getRow('course_name');
            // User progress
            $userDetails = $this->Certification_model->db

                ->table('scorm_users_courses_assigned')
                ->where('course_id', $course_mp_id)
                ->where('id_user', $userId)
                ->where('course_status', '2')
                ->where('status !=', 0)
                ->orderBy('last_updated_on', 'DESC')
                ->get()->getRowArray();
            $data['user_details'] = $userDetails;
            $data['completionDate'] = !empty($userDetails['last_updated_on']) ? date('Y-m-d', $userDetails['last_updated_on']) : date('Y-m-d');
        }
        // echo view('templates/header_view', $data);
        echo view('certification/user_certificate_view', $data);
        // echo view('templates/footer_view', $data);
    }

    // Download certificate as PDF (basic implementation using mPDF)
    // public function download_certificate($course_id = null)
    // {
    //     if (empty($course_id)) {
    //         return redirect()->back();
    //     }

    //     $client = session()->get('client');
    //     $userId = session()->get('id_user');

    //     $builder = $this->Certification_model->db->table('certificates_assigned as ca');
    //     $builder->select('ca.*');
    //     $builder->where('ca.course_lp_mp_id', $course_id);
    //     $builder->where('ca.type', 3);
    //     $builder->where('ca.client_id', $client);
    //     $builder->where('ca.status !=', 0);
    //     $assign = $builder->get()->getRowArray();

    //     if (empty($assign)) {
    //         session()->setFlashdata('error', 'No certificate assigned to this course.');
    //         return redirect()->back();
    //     }

    //     $certificate = $this->Certification_model->get_certificate_details($assign['certificate_id']);
    //     if (empty($certificate)) {
    //         session()->setFlashdata('error', 'Certificate template missing.');
    //         return redirect()->back();
    //     }

    //     $data = [];
    //     $data['certificate'] = $certificate[0];
    //     $data['assign'] = $assign;
    //     $data['user'] = [
    //         'id_user' => $userId,
    //         'name' => session()->get('name') ?? '',
    //     ];
    //     $data['course_id'] = $course_id;

    //     // Render HTML
    //     $html = view('certification/user_certificate_view', $data);

    //     // Generate PDF using mPDF
    //     try {
    //         $mpdf = new \Mpdf\Mpdf();
    //         $mpdf->WriteHTML($html);
    //         $filename = 'certificate_' . $course_id . '_' . $userId . '.pdf';
    //         $mpdf->Output($filename, \Mpdf\Output\Destination::INLINE);
    //         exit();
    //     } catch (\Exception $e) {
    //         session()->setFlashdata('error', 'PDF generation failed: ' . $e->getMessage());
    //         return redirect()->back();
    //     }
    // }
    public function download_certificate($course_id = null)
    {
         if ($response =  $this->requireRole(['5', '44', '3'])) {
            return $response;
        }
        if (empty($course_id)) {
            return redirect()->back();
        }

        $client = session()->get('client');
        $userId = session()->get('id_user');

        // Assigned certificate
        $assign = $this->Certification_model->db
            ->table('certificates_assigned')
            ->where([
                'course_lp_mp_id' => $course_id,
                'type'            => 3,
                'client_id'       => $client
            ])
            ->where('status !=', 0)
            ->get()->getRowArray();

        if (!$assign) {
            return redirect()->back()->with('error', 'Certificate not assigned.');
        }

        // Certificate template
        $certificateData = $this->Certification_model
            ->get_certificate_details($assign['certificate_id']);

        if (empty($certificateData)) {
            return redirect()->back()->with('error', 'Certificate not found.');
        }

        $certificate = $certificateData[0];

        // SAFE defaults
        $certificate['font_size']   = (!empty($certificate['font_size']) && $certificate['font_size'] > 0)
            ? (int)$certificate['font_size']
            : 16;

        $certificate['font_weight'] = $certificate['font_weight'] ?? 'normal';
        $certificate['font_color']  = $certificate['font_color'] ?? '#000000';

        // Background image (absolute path)
        $bg_path = '';
        if (!empty($certificate['background_image'])) {
            $path = FCPATH . ltrim($certificate['background_image'], '/');
            if (file_exists($path)) {
                $bg_path = $path;
            }
        }

        // Course name
        $courseName = $this->Certification_model->db
            ->table('scorm_courses')
            ->where('scourse_id', $course_id)
            ->get()->getRow('course_name');

        // User progress
        $userDetails = $this->Certification_model->db

            ->table('scorm_users_courses_assigned')
            ->where('course_id', $course_id)
            ->where('id_user', $userId)
            ->where('course_status', '2')
            ->where('status !=', 0)
            ->orderBy('last_updated_on', 'DESC')
            ->get()->getRowArray();

        $data = [
            'certificate'  => $certificate,
            'course_name'  => $courseName,
            'user_details' => $userDetails,
            'user' => [
                'name' => session()->get('name') ?? ''
            ],
            'bg_path' => $bg_path
        ];

        $html = view('certification/user_certificate_pdf', $data);

        try {
            $mpdf = new \Mpdf\Mpdf([
                'format' => 'A4',
                'margin_left'   => 0,
                'margin_right'  => 0,
                'margin_top'    => 0,
                'margin_bottom' => 0,
            ]);

            $mpdf->showImageErrors = true;
            $mpdf->WriteHTML($html);

            $mpdf->Output(
                'certificate_' . $course_id . '_' . $userId . '.pdf',
                \Mpdf\Output\Destination::INLINE
            );
            exit;
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }



    function assign_cert_to_course()
    {
         if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        if (isset($_SESSION['certificate_id'])) {
            $data['certificate_id'] = $_SESSION['certificate_id'];
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->to(base_url() . 'Certification/Dashboard');
        }
        $course_id = $this->request->getPost('course_id');
        $type = $this->request->getPost('type');
        $data['client_id'] = session()->get('client');
        $data['user_id'] = session()->get('id_user');


        $data = [
            'client_id' => $data['client_id'],
            'certificate_id' => $data['certificate_id'],
            'course_lp_mp_id' => $course_id,
            'type' => $type,
            'status' => 1,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];

        $this->Certification_model->assign_certificate($data);

        session()->setFlashdata('success', lang('Messages.Success_0018'));
        return redirect()->to(base_url() . 'Certification/Dashboard/Assign_certificate');
    }

    function Un_Assign()
    {
         if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        if (isset($_SESSION['certificate_id'])) {
            $data['certificate_id'] = $_SESSION['certificate_id'];
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->to(base_url() . 'Certification/Dashboard');
        }
        $cert_assign_id = $this->request->getPost('cert_assign_id');

        $this->Certification_model->un_assign_certificate($cert_assign_id);

        session()->setFlashdata('success', lang('Messages.Success_0019'));
        return redirect()->to(base_url() . 'Certification/Dashboard/Assign_certificate');
    }
    public function certification_view()
    {
         if ($response =  $this->requireRole(['5', '44', '3'])) {
            return $response;
        }
        $data = [];
        if (isset($_POST['certificate_id'])) {
            $data['certificate_id'] = $_POST['certificate_id'];
            $_SESSION['certificate_id'] = $data['certificate_id'];
        } elseif (isset($_SESSION['certificate_id'])) {
            $data['certificate_id'] = $_SESSION['certificate_id'];
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
        }
        $data['detail_type'] = $this->request->getPost('detail_type');

        if (isset($_POST['type'])) {
            $data['type'] = $_POST['type'];
            $_SESSION['type'] = $data['type'];
        } elseif (isset($_SESSION['type'])) {
            $data['type'] = $_SESSION['type'];
        } else {
            $data['type'] = 4;
        }

        $client_id = session()->get('client');
        $data['client_id'] = $client_id;
        $user_id = session()->get('id_user');
        // $data['get_courses'] = $this->M_Dashboard_model->get_courses($client_id, 0, $_SESSION['mp_language'], $data['cat_list']);
        $data['get_certification_learning_plan_courses'] = $this->M_Dashboard_model->get_certification_learning_plan_courses($data['certificate_id'], $client_id, $user_id, $data['type']);
        if (!empty($data['get_certification_learning_plan_courses'])) {
            $learningarrayid = array_column($data['get_certification_learning_plan_courses'], 'mp_id');
            $this->Certification_model->updateCertificationStatus($data['certificate_id'], $user_id, $learningarrayid, $data['type']);
        }
        echo view('templates/header_view', $data);
        echo view('certification/certification_dashboard', $data);
        echo view('templates/footer_view', $data);
    }
    function Assign_user_to_certification_view()
    {
         if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $data = [];
        $client_id = session()->get('client');
        if (isset($_POST['certificate_id'])) {
            $data['certificate_id'] = $_POST['certificate_id'];
            $_SESSION['certificate_id'] = $data['certificate_id'];
        } elseif (isset($_SESSION['certificate_id'])) {
            $data['certificate_id'] = $_SESSION['certificate_id'];
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
        }
        if (isset($_POST['type'])) {
            $data['type'] = $_POST['type'];
            $_SESSION['type'] = $data['type'];
        } elseif (isset($_SESSION['type'])) {
            $data['type'] = $_SESSION['type'];
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
        }
        $data['getUserclientlist'] = $this->users_model->getUserclientlist($client_id);
        $data['usergroupdata'] = $this->scorm_user_group_model->getCoursegroupdate(4, $client_id);
        $data['get_assigned_users'] = $this->Certification_model->get_assigned_users($data['certificate_id'], $data['type'], $client_id);


        echo view('templates/header_view', $data);
        echo view('certification/certification_assign_users', $data);
        echo view('templates/footer_view', $data);
    }
    function Assign_user_to_certification()
    {
         if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        if (isset($_POST['certificate_id'])) {
            $data['certificate_id'] = $_POST['certificate_id'];
            $_SESSION['certificate_id'] = $_POST['certificate_id'];
        } elseif (isset($_SESSION['certificate_id'])) {
            $data['certificate_id'] = $_SESSION['certificate_id'];
        } else {
            header('Location:' . base_url('Certification/Dashboard/Assign_user_to_certification_view'));
            exit();
        }
        if (isset($_POST['type'])) {
            $data['type'] = $_POST['type'];
            $_SESSION['type'] = $_POST['type'];
        } elseif (isset($_SESSION['type'])) {
            $data['type'] = $_SESSION['type'];
        } else {
            $data['type'] = 1;
        }
        $user_ids = isset($_POST['userid']) ? $_POST['userid'] : [];

        foreach ($user_ids as $user_id) {

            $data = [
                'certificate_id' => $data['certificate_id'],
                'user_id' => $user_id,
                'client_id' => session()->get('client'),
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
                'status' => 1
            ];
            $this->Certification_model->enroll_user_to_certificate($user_id, $data);
        }

        echo json_encode(['status' => 'OK']);
    }
    public function add_usergroup_to_learning_plan()
    {
         if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        if (isset($_POST['mp_id'])) {
            $data['mp_id'] = $_POST['mp_id'];
            $_SESSION['mp_id'] = $_POST['mp_id'];
        } elseif (isset($_SESSION['mp_id'])) {
            $data['mp_id'] = $_SESSION['mp_id'];
        } else {
            header('Location:' . base_url('marketplace/learning_dashboard'));
            exit();
        }
        $group_id = isset($_POST['group_id']) ? $_POST['group_id'] : [];

        $this->M_Dashboard_model->enroll_usergroup_to_learning_plan($group_id, $data);
        echo json_encode(['status' => 'OK']);
    }
    public function Un_Assign_certificate_user()
    {
         if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        if (isset($_POST['certificate_id'])) {
            $data['certificate_id'] = $_POST['certificate_id'];
            $_SESSION['certificate_id'] = $_POST['certificate_id'];
        } elseif (isset($_SESSION['certificate_id'])) {
            $data['certificate_id'] = $_SESSION['certificate_id'];
        } else {
            header('Location:' . base_url('Certification/Dashboard/Assign_user_to_certification_view'));
            exit();
        }
        $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : 0;

        $this->Certification_model->delete_user_from_certificate($user_id, $data['certificate_id']);
        session()->setFlashdata('success', lang('Messages.Success_0020'));
        header('Location:' . base_url('Certification/Dashboard/Assign_user_to_certification_view'));
        exit();
    }
    public function thumbnail_upload()
    {
         if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $data = [];
        helper(['filesystem']);
        if (isset($_POST['certificate_id'])) {
            $data['certificate_id'] = $_POST['certificate_id'];
            $_SESSION['certificate_id'] = $_POST['certificate_id'];
        } elseif (isset($_SESSION['certificate_id'])) {
            $data['certificate_id'] = $_SESSION['certificate_id'];
        } else {
            header('Location:' . base_url('Certification/Dashboard/Assign_user_to_certification_view'));
            exit();
        }


        if ($this->request->getPost()) {
            $rules = [
                'file' => [
                    'rules' => 'uploaded[file]'
                        . '|is_image[file]'
                        . '|mime_in[file,image/jpg,image/jpeg,image/png]'
                        . '|max_size[file,200]', // 200 KB
                    'errors' => [
                        'uploaded' => 'Please upload a thumbnail.',
                        'is_image' => 'The uploaded file must be an image.',
                        'mime_in' => 'Only JPG, JPEG or PNG images are allowed.',
                        'max_size' => 'Thumbnail size must not exceed 200KB.'
                    ]
                ]
            ];

            if (!$this->validate($rules)) {
                // print_r("thumbnailvalidation");
                // exit();
                $data['thumbnailvalidation'] = $this->validator;
            } else {
                if ($file = $this->request->getFile('file')) {

                    $imageInfo = getimagesize($file->getTempName());
                    $width = $imageInfo[0];
                    $height = $imageInfo[1];

                    if ($width != 518 || $height != 309) {
                        session()->setFlashdata('error', lang('Messages.Error_0011'));
                        return redirect()->to(base_url('Certification/Dashboard/Edit_certificate'));
                    }
                    if ($file->isValid() && !$file->hasMoved()) {
                        $filename = $file->getName();
                        if (!is_dir(FCPATH . 'assets/assets/uploads/certification_path/' . $data['certificate_id'])) {
                            mkdir('assets/assets/uploads/certification_path/' . $data['certificate_id'], 0777, true);
                        }
                        if (file_exists(FCPATH . 'assets/assets/uploads/certification_path/' . $data['certificate_id'] . "/" . $filename)) {
                            session()->setFlashdata('error', $filename . ' ' . lang('Messages.Success_0051'));
                            return redirect()->to(base_url('Certification/Dashboard/Edit_certificate'));
                        } else {
                            if ($file->move(FCPATH . 'assets/assets/uploads/certification_path/' . $data['certificate_id'], $filename)) {
                                $filepath = FCPATH . 'assets/assets/uploads/certification_path/' . $data['certificate_id'] . '/' . $filename;
                                $newdata = [
                                    // 'certificate_id' => $data['certificate_id'],
                                    'thumbnail' => $filename,
                                ];
                                $result = $this->Certification_model->update_certificate($data['certificate_id'], $newdata);
                                if ($result) {
                                    session()->setFlashdata('success', lang('Messages.Success_0009'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                    return redirect()->to(base_url('Certification/Dashboard/Edit_certificate'));
                                } else {
                                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                    return redirect()->to(base_url('Certification/Dashboard/Edit_certificate'));
                                }
                            }
                        }
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0001'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                        return redirect()->to(base_url('Certification/Dashboard/Edit_certificate'));
                    }
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url('Certification/Dashboard/Edit_certificate'));
                }
            }
            $data['client_id'] = session()->get('client');
            $data['get_certificate_details'] = $this->Certification_model->get_certificate_details($data['certificate_id']);
        }
        echo view('templates/header_view', $data);
        echo view('certification/edit_certificate_view', $data);
        echo view('templates/footer_view', $data);
    }
}
